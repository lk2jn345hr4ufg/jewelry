@extends('layouts.app')

@section('title', $business->name . ' Coupons & Deals — ' . $business->city->full_name . ' — Gleamion.com')
@section('meta_description', 'All verified ' . $business->name . ' coupon codes and deals in ' . $business->city->full_name . ': ' . $highlights['codes'] . ' promo codes and ' . $highlights['deals'] . ' deals, checked for expiry.')

@section('content')
<div class="max-w-6xl mx-auto px-4 pt-6">

    @include('partials.breadcrumbs', ['crumbs' => [
        ['label' => 'Stores, Coupons & Deals', 'url' => route('coupons.index')],
        ['label' => $business->name],
    ]])

    {{-- Header --}}
    <header class="mt-5">
        <p class="eyebrow mb-2">Discount store</p>
        <h1 class="font-display text-3xl sm:text-5xl font-semibold">{{ $business->name }} Coupons &amp; Deals</h1>
        <p class="mt-3 text-[color:var(--stone)] max-w-3xl">
            {{ $business->name }} is a {{ strtolower($business->category->name) }} business in {{ $business->city->full_name }}.
            Below {{ $highlights['total'] === 1 ? 'is the 1 verified offer' : 'are all ' . $highlights['total'] . ' verified offers' }}
            currently available — {{ $highlights['codes'] }} {{ Str::plural('promo code', $highlights['codes']) }}
            and {{ $highlights['deals'] }} {{ Str::plural('deal', $highlights['deals']) }} — each checked against its expiry date.
        </p>
        <p class="mt-3 text-sm">
            <a href="{{ route('business.show', $business) }}" class="font-medium hover:text-[color:var(--gold)]">Visit the {{ $business->name }} store profile →</a>
        </p>
    </header>

    {{-- Highlights --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-8">
        <div class="card p-4 text-center">
            <p class="font-display text-3xl font-semibold" style="color:var(--gold)">{{ $highlights['total'] }}</p>
            <p class="text-xs uppercase tracking-wider text-[color:var(--stone)] mt-1">Active offers</p>
        </div>
        <div class="card p-4 text-center">
            <p class="font-display text-3xl font-semibold" style="color:var(--gold)">{{ $highlights['codes'] }}</p>
            <p class="text-xs uppercase tracking-wider text-[color:var(--stone)] mt-1">Coupon codes</p>
        </div>
        <div class="card p-4 text-center">
            <p class="font-display text-3xl font-semibold" style="color:var(--gold)">{{ $highlights['deals'] }}</p>
            <p class="text-xs uppercase tracking-wider text-[color:var(--stone)] mt-1">Deals</p>
        </div>
        <div class="card p-4 text-center">
            <p class="font-display text-3xl font-semibold" style="color:var(--gold)">{{ $highlights['best'] ?? '—' }}</p>
            <p class="text-xs uppercase tracking-wider text-[color:var(--stone)] mt-1">Best discount</p>
        </div>
    </div>

    {{-- Horizontal tab menu --}}
    <nav class="mt-10 border-b border-[color:var(--line)] overflow-x-auto" aria-label="Sections">
        <ul class="flex gap-6 sm:gap-8 whitespace-nowrap text-sm font-medium">
            @foreach(['coupons' => 'Coupons', 'deals' => 'Deals', 'categories' => 'Categories', 'alternatives' => 'Alternatives', 'reviews' => 'Reviews'] as $anchor => $label)
                <li>
                    <a href="#{{ $anchor }}" class="inline-block py-3 border-b-2 border-transparent hover:border-[color:var(--gold)] hover:text-[color:var(--gold)]">{{ $label }}</a>
                </li>
            @endforeach
        </ul>
    </nav>

    {{-- Coupons (offers with a code) --}}
    <section id="coupons" class="pt-12" style="scroll-margin-top:1rem">
        <h2 class="font-display text-3xl font-semibold mb-2">{{ $business->name }} Coupon Codes</h2>
        <div class="rule-gold mb-8"></div>
        @if($codes->isEmpty())
            <p class="text-[color:var(--stone)]">No promo codes at the moment — see the deals below, no code required.</p>
        @else
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($codes as $coupon)
                    <div class="card p-5 flex flex-col border-l-4" style="border-left-color:var(--gold)">
                        @if($coupon->discount)
                            <p class="font-display text-3xl font-semibold" style="color:var(--gold)">{{ $coupon->discount }}</p>
                        @endif
                        <p class="font-semibold mt-1">{{ $coupon->title }}</p>
                        @if($coupon->description)
                            <p class="text-sm text-[color:var(--stone)] mt-1">{{ $coupon->description }}</p>
                        @endif
                        <div class="mt-auto pt-3 flex items-center justify-between">
                            <button type="button" onclick="navigator.clipboard.writeText('{{ $coupon->code }}'); this.textContent='Copied!'"
                                    class="px-3 py-1 text-sm border border-dashed border-[color:var(--champagne)] bg-[color:var(--paper)] hover:border-[color:var(--gold)]">{{ $coupon->code }}</button>
                            @if($coupon->expires_at)
                                <span class="text-xs text-[color:var(--stone)]">Until {{ $coupon->expires_at->format('M j, Y') }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Deals (no code needed) --}}
    <section id="deals" class="pt-12" style="scroll-margin-top:1rem">
        <h2 class="font-display text-3xl font-semibold mb-2">{{ $business->name }} Deals</h2>
        <div class="rule-gold mb-8"></div>
        @if($deals->isEmpty())
            <p class="text-[color:var(--stone)]">No code-free deals right now — the coupon codes above are the current offers.</p>
        @else
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($deals as $coupon)
                    <div class="card p-5 flex flex-col border-l-4" style="border-left-color:var(--champagne)">
                        @if($coupon->discount)
                            <p class="font-display text-3xl font-semibold" style="color:var(--gold)">{{ $coupon->discount }}</p>
                        @endif
                        <p class="font-semibold mt-1">{{ $coupon->title }}</p>
                        @if($coupon->description)
                            <p class="text-sm text-[color:var(--stone)] mt-1">{{ $coupon->description }}</p>
                        @endif
                        <div class="mt-auto pt-3 flex items-center justify-between">
                            <span class="text-xs text-[color:var(--stone)]">No code needed</span>
                            @if($coupon->expires_at)
                                <span class="text-xs text-[color:var(--stone)]">Until {{ $coupon->expires_at->format('M j, Y') }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Categories --}}
    <section id="categories" class="pt-12" style="scroll-margin-top:1rem">
        <h2 class="font-display text-3xl font-semibold mb-2">Categories</h2>
        <div class="rule-gold mb-8"></div>
        <p class="text-sm text-[color:var(--stone)] mb-4">
            {{ $business->name }} is listed under
            <a href="{{ route('city.category', [$business->city, $business->category]) }}" class="font-medium hover:text-[color:var(--gold)]">{{ $business->category->name }}</a>.
            Browse other jewelry categories in {{ $business->city->name }}:
        </p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($cityCategories as $category)
                <a href="{{ route('city.category', [$business->city, $category]) }}" class="group flex items-center gap-3 px-4 py-3 border border-[color:var(--line)] hover:border-[color:var(--champagne)] transition-colors">
                    <span class="facet-sm" style="background:var(--champagne)"></span>
                    <span class="font-medium group-hover:text-[color:var(--gold)]">{{ $category->name }}</span>
                    <span class="ml-auto text-xs text-[color:var(--stone)]">{{ $category->businesses_count }}</span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Alternatives --}}
    <section id="alternatives" class="pt-12" style="scroll-margin-top:1rem">
        <h2 class="font-display text-3xl font-semibold mb-2">Alternative Stores with Deals</h2>
        <div class="rule-gold mb-8"></div>
        @if($alternatives->isEmpty())
            <p class="text-[color:var(--stone)]">No other stores with active offers right now.</p>
        @else
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($alternatives as $store)
                    <div class="card p-5 flex flex-col">
                        <a href="{{ route('coupons.show', $store) }}" class="font-display text-xl font-semibold hover:text-[color:var(--gold)]">{{ $store->name }}</a>
                        <p class="text-sm text-[color:var(--stone)] mt-1">{{ $store->category?->name }} · {{ $store->city?->full_name }}</p>
                        <p class="text-sm mt-2">
                            <span class="font-medium" style="color:var(--gold)">{{ $store->codes_count }}</span> {{ Str::plural('code', $store->codes_count) }} ·
                            <span class="font-medium" style="color:var(--gold)">{{ $store->deals_count }}</span> {{ Str::plural('deal', $store->deals_count) }}
                        </p>
                        <div class="mt-auto pt-3 text-sm">
                            <a href="{{ route('coupons.show', $store) }}" class="font-medium hover:text-[color:var(--gold)]">View offers →</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- Reviews --}}
    <section id="reviews" class="pt-12" style="scroll-margin-top:1rem">
        <h2 class="font-display text-3xl font-semibold mb-2">{{ $business->name }} Reviews</h2>
        <div class="rule-gold mb-8"></div>
        @if($reviews->isEmpty())
            <p class="text-[color:var(--stone)]">No approved reviews yet.
                <a href="{{ route('business.show', $business) }}#reviews" class="font-medium hover:text-[color:var(--gold)]">Be the first to write one →</a>
            </p>
        @else
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($reviews as $review)
                    <blockquote class="card p-5">
                        @include('partials.rating', ['rating' => $review->rating])
                        <p class="text-sm mt-2">“{{ Str::limit($review->body, 180) }}”</p>
                        <footer class="mt-3 text-sm">
                            <span class="font-medium">{{ $review->author_name }}</span>
                            <span class="text-[color:var(--stone)]"> · {{ $review->created_at->format('M j, Y') }}</span>
                        </footer>
                    </blockquote>
                @endforeach
            </div>
            <p class="mt-6 text-sm">
                <a href="{{ route('business.show', $business) }}#reviews" class="font-medium hover:text-[color:var(--gold)]">Read all reviews or write your own on the store profile →</a>
            </p>
        @endif
    </section>

    {{-- SEO text --}}
    <section class="pt-12">
        <div class="border-t border-[color:var(--line)] pt-8 text-sm text-[color:var(--stone)] leading-relaxed max-w-3xl space-y-4">
            <h2 class="font-display text-2xl font-semibold text-gray-800">About {{ $business->name }} coupons and deals</h2>
            <p>
                Looking to save at {{ $business->name }}? This page collects every active {{ $business->name }} offer in one place —
                {{ $highlights['codes'] }} {{ Str::plural('coupon code', $highlights['codes']) }} you can copy with one click
                and {{ $highlights['deals'] }} {{ Str::plural('deal', $highlights['deals']) }} that apply without any code.
                As a {{ strtolower($business->category->name) }} business serving {{ $business->city->full_name }},
                {{ $business->name }} regularly runs promotions on services and purchases; expired offers are removed from this page automatically,
                so everything you see here is current.
            </p>
            <p>
                To redeem a coupon, copy the code and present it in store or enter it at checkout online.
                For details about the business itself — address, opening hours, phone numbers and the full set of customer reviews —
                visit the <a href="{{ route('business.show', $business) }}" class="font-medium hover:text-[color:var(--gold)]">{{ $business->name }} profile page</a>.
                You can also compare offers from other jewelry stores in
                <a href="{{ route('city.show', $business->city) }}" class="font-medium hover:text-[color:var(--gold)]">{{ $business->city->name }}</a>
                or browse all current promotions on the
                <a href="{{ route('coupons.index') }}" class="font-medium hover:text-[color:var(--gold)]">Stores, Coupons &amp; Deals</a> page.
            </p>
        </div>
    </section>

    {{-- FAQ --}}
    <section class="pt-12 pb-4">
        <h2 class="font-display text-3xl font-semibold mb-2">Frequently Asked Questions</h2>
        <div class="rule-gold mb-8"></div>
        <div class="max-w-3xl divide-y divide-[color:var(--line)] border-y border-[color:var(--line)]">
            @foreach($faq as $item)
                <details class="group py-4">
                    <summary class="flex items-center justify-between gap-4 cursor-pointer font-medium list-none">
                        {{ $item['q'] }}
                        <span class="facet-sm shrink-0 transition-transform group-open:rotate-90" style="background:var(--gold)"></span>
                    </summary>
                    <p class="mt-3 text-sm text-[color:var(--stone)] leading-relaxed">{{ $item['a'] }}</p>
                </details>
            @endforeach
        </div>
    </section>
</div>

{{-- FAQ structured data for search engines --}}
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => collect($faq)->map(fn ($item) => [
        '@type' => 'Question',
        'name' => $item['q'],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['a']],
    ])->values(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection
