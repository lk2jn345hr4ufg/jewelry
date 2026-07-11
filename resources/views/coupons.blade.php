@extends('layouts.app')

@section('title', 'Stores, Coupons & Deals — Gleamion.com')
@section('meta_description', 'Current coupons, promo codes and deals from jewelry stores in the Gleamion.com directory — browse by category or store.')

@section('content')
<section style="background:var(--ink)" class="text-white">
    <div class="max-w-6xl mx-auto px-4 py-12 text-center">
        <p class="eyebrow mb-3">Save on sparkle</p>
        <h1 class="font-display text-4xl sm:text-5xl font-semibold">Stores, Coupons &amp; Deals</h1>
        <div class="mt-7 flex justify-center">
            @include('partials.search', ['placeholder' => 'Quick search: stores, categories, cities…', 'class' => 'mx-auto'])
        </div>
    </div>
</section>

<div class="max-w-6xl mx-auto px-4 py-12">

    {{-- Discount store categories --}}
    @if($dealCategories->isNotEmpty())
        <div class="mb-12">
            <h2 class="font-display text-2xl font-semibold mb-2">Discount store categories</h2>
            <div class="rule-gold mb-6"></div>
            <div class="flex flex-wrap gap-2">
                @foreach($dealCategories as $dealCategory)
                    <a href="{{ route('coupons.category', $dealCategory) }}"
                       class="px-3 py-1.5 text-sm border border-[color:var(--line)] hover:border-[color:var(--champagne)] hover:text-[color:var(--gold)] transition-colors">
                        {{ $dealCategory->name }}
                        <span class="text-xs text-[color:var(--stone)]">{{ $dealCategory->live_coupons_count }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Discount stores --}}
    @if($stores->isNotEmpty())
        <div class="mb-12">
            <h2 class="font-display text-2xl font-semibold mb-2">Discount stores</h2>
            <div class="rule-gold mb-6"></div>
            <div class="grid gap-3 grid-cols-2 lg:grid-cols-4">
                @foreach($stores as $store)
                    <a href="{{ route('coupons.show', $store) }}" class="card px-4 py-3 flex items-center justify-between text-sm">
                        <span class="font-medium">{{ $store->name }}</span>
                        <span class="text-xs text-[color:var(--stone)]">{{ $store->live_coupons_count }} {{ Str::plural('offer', $store->live_coupons_count) }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Per-category: latest offers + featured stores --}}
    @foreach($categoryBlocks as $block)
        <div class="mb-12">
            <div class="flex flex-wrap items-end justify-between gap-3 mb-2">
                <h2 class="font-display text-2xl font-semibold">{{ $block['category']->name }} — latest offers</h2>
                <a href="{{ route('coupons.category', $block['category']) }}" class="text-sm font-medium hover:text-[color:var(--gold)]">
                    All {{ $block['category']->name }} deals →
                </a>
            </div>
            <div class="rule-gold mb-6"></div>

            <div class="grid gap-4 lg:grid-cols-4">
                <div class="lg:col-span-3 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($block['offers'] as $coupon)
                        @include('partials.coupon-card', ['coupon' => $coupon])
                    @endforeach
                </div>

                <div class="card p-4">
                    <p class="text-xs uppercase tracking-wider text-[color:var(--stone)] mb-3">Featured stores</p>
                    <ul class="space-y-2 text-sm">
                        @foreach($block['stores'] as $store)
                            <li class="flex items-center justify-between gap-2">
                                <a href="{{ route('coupons.show', $store) }}" class="font-medium hover:text-[color:var(--gold)] truncate">{{ $store->name }}</a>
                                <span class="text-xs text-[color:var(--stone)] shrink-0">{{ $store->live_coupons_count }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('coupons.category', $block['category']) }}" class="inline-block mt-4 text-sm font-medium hover:text-[color:var(--gold)]">View category →</a>
                </div>
            </div>
        </div>
    @endforeach

    {{-- All current coupons --}}
    <h2 class="font-display text-2xl font-semibold mb-2">All current coupons</h2>
    <div class="rule-gold mb-6"></div>
    @if($coupons->isEmpty())
        <p class="text-[color:var(--stone)]">No active deals right now — check back soon.</p>
    @else
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($coupons as $coupon)
                @include('partials.coupon-card', ['coupon' => $coupon])
            @endforeach
        </div>
        <div class="mt-8">{{ $coupons->links() }}</div>
    @endif
</div>
@endsection
