<?php

namespace Tests\Feature\Api;

use App\Models\Business;
use App\Models\Category;
use App\Models\City;
use App\Models\Coupon;
use App\Models\RedirectRule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OffersTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;

    protected Business $store;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'vouchers.api_tokens' => ['test-token'],
            'vouchers.autocreate_stores' => true,
            'vouchers.autocreate_store_active' => false,
            'vouchers.coupon_active_on_import' => true,
        ]);

        $this->category = Category::create(['name' => 'Jewelry Stores']);
        config(['vouchers.default_category_id' => $this->category->id]);

        $this->store = Business::create([
            'name' => 'Aurora Jewelers',
            'category_id' => $this->category->id,
            'website' => 'https://aurora.example.com/shop',
            'is_active' => true,
        ]);
    }

    /** @param  array<int, array<string, mixed>>  $offers */
    protected function submit(array $offers, string $token = 'test-token')
    {
        return $this->withHeader('X-API-Token', $token)
            ->postJson('/api/submit_offers', $offers);
    }

    protected function offer(array $overrides = []): array
    {
        return array_merge([
            'shop_name' => 'Aurora Jewelers',
            'shop_url' => 'https://aurora.example.com/',
            'slug' => 'aurora-10-off',
            'title' => '10% off everything',
            'coupon_type' => 'code',
            'coupon_code' => 'SAVE10',
            'valid_at' => '2030-12-31',
        ], $overrides);
    }

    public function test_it_rejects_a_missing_or_wrong_token(): void
    {
        $this->getJson('/api/get_offers')
            ->assertStatus(401)
            ->assertExactJson(['message' => 'Unauthorized.']);

        $this->submit([$this->offer()], 'wrong-token')
            ->assertStatus(401)
            ->assertExactJson(['message' => 'Unauthorized.']);

        $this->assertSame(0, Coupon::count());
    }

    public function test_it_imports_an_offer_for_an_existing_store_without_touching_it(): void
    {
        $this->submit([$this->offer()])
            ->assertOk()
            ->assertExactJson(['status' => 'ok']);

        $coupon = Coupon::sole();

        $this->assertSame($this->store->id, $coupon->business_id);
        $this->assertSame('10% off everything', $coupon->title);
        $this->assertSame('SAVE10', $coupon->code);
        $this->assertSame('api', $coupon->origin);
        $this->assertTrue($coupon->is_active);
        $this->assertSame('2030-12-31', $coupon->expires_at->toDateString());

        // No second store, and the curated name survives.
        $this->assertSame(1, Business::count());
        $this->assertSame('Aurora Jewelers', $this->store->fresh()->name);
    }

    public function test_it_creates_an_unknown_store_hidden(): void
    {
        $this->submit([$this->offer([
            'shop_name' => 'Blue Nile',
            'shop_url' => 'https://WWW.BlueNile.com/engagement?utm_source=feed',
            'slug' => 'bluenile-offer',
        ])])->assertOk();

        $store = Business::where('name', 'Blue Nile')->sole();

        $this->assertSame('bluenile.com', $store->website_host);
        $this->assertSame('https://bluenile.com/', $store->website);
        $this->assertFalse($store->is_active);
        $this->assertNull($store->city_id);
        $this->assertSame($this->category->id, $store->category_id);
        $this->assertSame('api', $store->origin);
    }

    public function test_resending_a_slug_updates_instead_of_duplicating(): void
    {
        $this->submit([$this->offer()])->assertOk();
        $this->submit([$this->offer(['title' => 'Now 20% off'])])->assertOk();

        $this->assertSame(1, Coupon::count());
        $this->assertSame('Now 20% off', Coupon::sole()->title);
    }

    public function test_lists_in_meta_accumulate_across_imports(): void
    {
        $this->submit([$this->offer([
            'categories' => 'Jewelry Stores, Marketplace',
            'countries' => ['us', ' ca '],
            'sources' => ['Partner Feed'],
        ])])->assertOk();

        $this->submit([$this->offer([
            'categories' => ['MARKETPLACE', 'Watches'],
            'countries' => ['US', 'GB'],
        ])])->assertOk();

        $meta = Coupon::sole()->meta;

        // First spelling wins, new values are appended, nothing is dropped.
        $this->assertSame(['Jewelry Stores', 'Marketplace', 'Watches'], $meta['categories']);
        $this->assertSame(['US', 'CA', 'GB'], $meta['countries']);
        $this->assertSame(['Partner Feed'], $meta['sources']);
    }

    public function test_an_offer_moves_when_its_shop_url_changes(): void
    {
        $this->submit([$this->offer()])->assertOk();

        $this->submit([$this->offer([
            'shop_name' => 'Blue Nile',
            'shop_url' => 'https://bluenile.com/',
        ])])->assertOk();

        $moved = Business::where('website_host', 'bluenile.com')->sole();

        $this->assertSame(1, Coupon::count());
        $this->assertSame($moved->id, Coupon::sole()->business_id);
    }

    public function test_an_invalid_batch_writes_nothing(): void
    {
        $this->submit([
            $this->offer(),
            $this->offer(['slug' => 'broken', 'title' => '', 'coupon_type' => 'sale', 'shop_url' => 'not a url']),
        ])
            ->assertStatus(422)
            ->assertJsonPath('message', 'The given data was invalid.')
            ->assertJsonValidationErrors(['1.title', '1.coupon_type', '1.shop_url']);

        $this->assertSame(0, Coupon::count());
        $this->assertSame(1, Business::count());
    }

    public function test_deep_link_falls_back_to_the_store_website(): void
    {
        $this->submit([$this->offer(['deep_link' => ''])])->assertOk();

        $this->assertSame($this->store->website, Coupon::sole()->deep_link);
    }

    public function test_a_code_offer_without_a_code_is_stored_as_a_deal(): void
    {
        $this->submit([$this->offer(['coupon_code' => null])])->assertOk();

        $coupon = Coupon::sole();

        $this->assertSame('deal', $coupon->coupon_type);
        $this->assertNull($coupon->code);
    }

    public function test_it_strips_scripts_from_the_description(): void
    {
        $this->submit([$this->offer([
            'description' => '<script>alert(1)</script><p>Bring <b>any</b> ring</p><img src=x onerror=y>',
        ])])->assertOk();

        $description = Coupon::sole()->description;

        $this->assertStringNotContainsString('script', $description);
        $this->assertStringNotContainsString('alert(1)', $description);
        $this->assertStringNotContainsString('<img', $description);
        $this->assertStringContainsString('<p>Bring <b>any</b> ring</p>', $description);
    }

    public function test_an_offer_that_has_not_started_is_hidden_everywhere(): void
    {
        $this->submit([$this->offer([
            'title' => 'Not yet running',
            'starts_at' => now()->addMonth()->toDateString(),
            'valid_at' => now()->addYear()->toDateString(),
        ])])->assertOk();

        $this->assertSame(1, Coupon::count());
        $this->assertSame(0, Coupon::live()->count());

        $this->withHeader('X-API-Token', 'test-token')
            ->getJson('/api/get_offers')
            ->assertOk()
            ->assertJsonCount(0, 'offers');

        $this->get('/deals')->assertOk()->assertDontSee('Not yet running');
    }

    public function test_get_offers_hides_coupons_of_hidden_stores(): void
    {
        $this->submit([$this->offer([
            'shop_name' => 'Blue Nile',
            'shop_url' => 'https://bluenile.com/',
            'slug' => 'bluenile-offer',
        ])])->assertOk();

        // The store was created hidden, so its offer must not be listed.
        $this->withHeader('X-API-Token', 'test-token')
            ->getJson('/api/get_offers')
            ->assertOk()
            ->assertJsonCount(0, 'offers');
    }

    public function test_get_offers_reports_admin_coupons_and_pages_stably(): void
    {
        foreach (range(1, 5) as $i) {
            Coupon::create([
                'business_id' => $this->store->id,
                'title' => "Manual offer {$i}",
                'discount' => '5% OFF',
                'is_active' => true,
            ]);
        }

        $first = $this->withHeader('X-API-Token', 'test-token')
            ->getJson('/api/get_offers?per_page=2&page=1')
            ->assertOk()
            ->assertJsonPath('pagination.total_pages', 3)
            ->assertJsonPath('pagination.page', 1)
            ->assertJsonCount(2, 'offers')
            ->json('offers');

        $second = $this->withHeader('X-API-Token', 'test-token')
            ->getJson('/api/get_offers?per_page=2&page=2')
            ->assertOk()
            ->json('offers');

        // Coupons with no API slug are still reported, under a synthetic one.
        $this->assertMatchesRegularExpression('/^coupon-\d+$/', $first[0]['slug']);
        $this->assertSame('deal', $first[0]['coupon_type']);
        $this->assertSame('https://aurora.example.com/', $first[0]['shop_url']);

        $this->assertEmpty(array_intersect(
            array_column($first, 'slug'),
            array_column($second, 'slug')
        ));
    }

    public function test_public_pages_render_for_a_store_the_feed_created_without_a_city(): void
    {
        config(['vouchers.autocreate_store_active' => true]);

        $this->submit([$this->offer([
            'shop_name' => 'Blue Nile',
            'shop_url' => 'https://bluenile.com/',
            'slug' => 'bluenile-offer',
        ])])->assertOk();

        $store = Business::where('website_host', 'bluenile.com')->sole();
        $this->assertNull($store->city_id);

        // Every city-scoped link and sentence has to fall away cleanly.
        $this->get('/deals')->assertOk()->assertSee('Blue Nile');
        $this->get("/deals/{$store->slug}")->assertOk();
        $this->get("/business/{$store->slug}")->assertOk();
    }

    public function test_a_store_that_has_a_city_still_shows_it(): void
    {
        $city = City::create(['name' => 'New York', 'state' => 'NY']);
        $this->store->update(['city_id' => $city->id]);

        $this->submit([$this->offer()])->assertOk();

        $this->get('/deals')->assertOk()->assertSee('New York, NY');
        $this->get("/deals/{$this->store->slug}")->assertOk()->assertSee('New York, NY');
    }

    public function test_a_catch_all_redirect_rule_does_not_swallow_the_api(): void
    {
        RedirectRule::create([
            'from_pattern' => '^/(.*)$',
            'to_pattern' => '/deals',
            'is_regex' => true,
            'status_code' => 301,
            'is_active' => true,
        ]);

        $this->withHeader('X-API-Token', 'test-token')
            ->getJson('/api/get_offers')
            ->assertOk();

        $this->get('/category/anything')->assertRedirect('/deals');
    }
}
