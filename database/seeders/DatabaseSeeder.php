<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Category;
use App\Models\City;
use App\Models\Coupon;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Administrator', 'password' => 'password', 'is_admin' => true]
        );

        $cities = collect([
            ['New York', 'NY', 40.7128, -74.0060, 8258035],
            ['Los Angeles', 'CA', 34.0522, -118.2437, 3820914],
            ['Chicago', 'IL', 41.8781, -87.6298, 2664452],
            ['Houston', 'TX', 29.7604, -95.3698, 2314157],
            ['Miami', 'FL', 25.7617, -80.1918, 455924],
            ['San Francisco', 'CA', 37.7749, -122.4194, 808988],
            ['Seattle', 'WA', 47.6062, -122.3321, 755078],
            ['Boston', 'MA', 42.3601, -71.0589, 653833],
            ['Dallas', 'TX', 32.7767, -96.7970, 1302868],
            ['Atlanta', 'GA', 33.7490, -84.3880, 510823],
            ['Denver', 'CO', 39.7392, -104.9903, 716577],
            ['Philadelphia', 'PA', 39.9526, -75.1652, 1550542],
        ])->map(fn ($c) => City::firstOrCreate(['slug' => Str::slug($c[0])], [
            'name' => $c[0], 'state' => $c[1], 'lat' => $c[2], 'lng' => $c[3], 'population' => $c[4],
        ]));

        $categories = collect([
            ['Jewelry Stores', 'Full-service jewelers carrying fine jewelry, diamonds and precious metals.'],
            ['Engagement Rings & Bridal', 'Specialists in engagement rings, wedding bands and bridal sets.'],
            ['Custom Jewelry Design', 'Bespoke studios that design and craft one-of-a-kind pieces.'],
            ['Watch Shops & Repair', 'Luxury watch dealers, service centers and certified watchmakers.'],
            ['Jewelry Repair & Restoration', 'Ring sizing, stone setting, soldering, polishing and antique restoration.'],
            ['Vintage & Estate Jewelry', 'Curated antique, vintage and estate pieces with history.'],
            ['Gold & Diamond Buyers', 'Licensed buyers of gold, diamonds, watches and estate jewelry.'],
            ['Pearl & Gemstone Dealers', 'Loose gemstones, pearls and certified colored stones.'],
        ])->map(fn ($c) => Category::firstOrCreate(['slug' => Str::slug($c[0])], [
            'name' => $c[0], 'description' => $c[1],
        ]));

        $prefixes = ['Aurora', 'Golden Hour', 'Meridian', 'Lumen', 'Heritage', 'Facet & Flame', 'Marlowe', 'Solstice', 'Ivory Gate', 'Atelier Vera', 'Northlight', 'Old Crown', 'Silver Birch', 'Bellamy', 'Opaline', 'Crescent', 'Stonebridge', 'Velvet Box', 'Amberline', 'Clara June'];
        $suffixByCategory = [
            'jewelry-stores' => 'Jewelers',
            'engagement-rings-bridal' => 'Bridal Jewelers',
            'custom-jewelry-design' => 'Design Studio',
            'watch-shops-repair' => 'Watch Co.',
            'jewelry-repair-restoration' => 'Jewelry Repair',
            'vintage-estate-jewelry' => 'Estate Jewelry',
            'gold-diamond-buyers' => 'Gold Buyers',
            'pearl-gemstone-dealers' => 'Gem House',
        ];
        $streets = ['Main St', 'Market St', 'Grand Ave', '5th Ave', 'Union Sq', 'Oak Blvd', 'Pearl St', 'King St', 'Lakeview Dr', 'Broadway'];
        $reviewers = ['Hannah R.', 'Marcus T.', 'Priya S.', 'Daniel K.', 'Sofia M.', 'James L.', 'Aisha B.', 'Victor N.', 'Elena G.', 'Tom W.'];
        $comments = [
            'They resized my grandmother\'s ring and it looks brand new. Careful, honest work.',
            'Picked out an engagement ring here — zero pressure, great education on the 4 Cs.',
            'Fair quote on my gold, paid on the spot, and everything was weighed in front of me.',
            'My watch came back running perfectly and they explained exactly what was serviced.',
            'Gorgeous selection of vintage pieces. Found an Art Deco brooch I could not leave behind.',
            'Custom pendant turned out better than the sketch. Communication was excellent.',
            'Quick chain repair while I waited, very reasonable price.',
            'Knowledgeable staff and a beautiful showroom. Highly recommend.',
            'They sourced a sapphire to match my budget and it is stunning.',
            'Appraisal was thorough and well documented. Professional from start to finish.',
        ];

        $n = 0;
        foreach ($cities as $ci => $city) {
            $perCity = $ci < 6 ? 4 : 2;
            for ($j = 0; $j < $perCity; $j++) {
                $category = $categories[($n + $j) % $categories->count()];
                $prefix = $prefixes[$n % count($prefixes)];
                $name = $prefix.' '.$suffixByCategory[$category->slug];
                $slug = Str::slug($name.' '.$city->slug);
                $n++;

                $business = Business::firstOrCreate(['slug' => $slug], [
                    'name' => $name,
                    'category_id' => $category->id,
                    'city_id' => $city->id,
                    'about' => "{$name} is a trusted name for ".strtolower($category->name)." in {$city->name}. Family-run and appointment-friendly, the team combines old-world craftsmanship with modern service — every piece is inspected, documented and guaranteed. Stop by the showroom or call ahead for a private consultation.",
                    'address' => (100 + ($n * 37) % 899).' '.$streets[$n % count($streets)].", {$city->name}, {$city->state}",
                    'phone' => sprintf('(%d) 555-%04d', 200 + ($n * 7) % 700, 1000 + $n * 13 % 9000),
                    'phone_alt' => $n % 3 === 0 ? sprintf('(%d) 555-%04d', 300 + $n % 600, 2000 + $n * 17 % 8000) : null,
                    'email' => 'hello@'.Str::slug($prefix).'.example.com',
                    'website' => 'https://'.Str::slug($prefix).'.example.com',
                    'lat' => $city->lat + (mt_rand(-450, 450) / 10000),
                    'lng' => $city->lng + (mt_rand(-450, 450) / 10000),
                    'hours' => [
                        'Mon' => '10:00 – 18:00', 'Tue' => '10:00 – 18:00', 'Wed' => '10:00 – 18:00',
                        'Thu' => '10:00 – 19:00', 'Fri' => '10:00 – 19:00', 'Sat' => '11:00 – 17:00',
                    ],
                    'is_active' => true,
                    'created_at' => now()->subDays(mt_rand(1, 120)),
                ]);

                $category->cities()->syncWithoutDetaching([$city->id]);

                if ($business->reviews()->count() === 0) {
                    foreach (range(1, mt_rand(1, 3)) as $r) {
                        Review::create([
                            'business_id' => $business->id,
                            'author_name' => $reviewers[array_rand($reviewers)],
                            'rating' => mt_rand(3, 5),
                            'body' => $comments[array_rand($comments)],
                            'status' => mt_rand(1, 10) > 2 ? 'approved' : 'pending',
                            'created_at' => now()->subDays(mt_rand(0, 60)),
                        ]);
                    }
                }

                if ($n % 3 === 0 && $business->coupons()->count() === 0) {
                    Coupon::create([
                        'business_id' => $business->id,
                        'title' => collect(['15% off jewelry repair', 'Free ring cleaning & inspection', '$50 off purchases over $500', '10% off your first custom design', 'Free watch battery with any service'])->random(),
                        'code' => strtoupper(Str::random(3)).mt_rand(10, 99),
                        'discount' => collect(['15% OFF', '$50 OFF', '10% OFF', 'FREE'])->random(),
                        'description' => 'Mention this code in store or online. One per customer, cannot be combined with other offers.',
                        'expires_at' => now()->addDays(mt_rand(20, 120)),
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}
