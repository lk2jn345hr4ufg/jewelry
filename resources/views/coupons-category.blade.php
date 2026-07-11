@extends('layouts.app')

@section('title', $category->name . ' Coupons & Deals — Gleamion.com')
@section('meta_description', 'Active coupons, promo codes and deals from ' . strtolower($category->name) . ' businesses — verified against expiry dates.')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">

    @include('partials.breadcrumbs', ['crumbs' => [
        ['label' => 'Stores, Coupons & Deals', 'url' => route('coupons.index')],
        ['label' => $category->name],
    ]])

    <div class="mt-6 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
        <div>
            <p class="eyebrow mb-2">{{ $coupons->total() }} active {{ Str::plural('offer', $coupons->total()) }}</p>
            <h1 class="font-display text-4xl font-semibold">{{ $category->name }} Coupons &amp; Deals</h1>
            <p class="mt-3 text-[color:var(--stone)] max-w-2xl">
                Current promotions from {{ strtolower($category->name) }} businesses in the directory —
                copy a code with one click or browse the
                <a href="{{ route('category.show', $category) }}" class="font-medium hover:text-[color:var(--gold)]">full {{ $category->name }} category</a>.
            </p>
        </div>
        @include('partials.search', ['placeholder' => 'Quick search: stores, categories, cities…', 'class' => 'lg:max-w-md'])
    </div>

    {{-- Featured stores in this category --}}
    @if($stores->isNotEmpty())
        <div class="mt-10">
            <h2 class="font-display text-2xl font-semibold mb-2">Featured {{ $category->name }} stores</h2>
            <div class="rule-gold mb-6"></div>
            <div class="grid gap-3 grid-cols-2 lg:grid-cols-4">
                @foreach($stores as $store)
                    <a href="{{ route('coupons.show', $store) }}" class="card px-4 py-3 text-sm">
                        <span class="block font-medium">{{ $store->name }}</span>
                        <span class="text-xs text-[color:var(--stone)]">{{ $store->city?->full_name }} · {{ $store->live_coupons_count }} {{ Str::plural('offer', $store->live_coupons_count) }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Offers --}}
    <div class="mt-10">
        <h2 class="font-display text-2xl font-semibold mb-2">Latest {{ $category->name }} offers</h2>
        <div class="rule-gold mb-6"></div>
        @if($coupons->isEmpty())
            <p class="text-[color:var(--stone)]">No active offers in this category right now — check back soon.</p>
        @else
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($coupons as $coupon)
                    @include('partials.coupon-card', ['coupon' => $coupon])
                @endforeach
            </div>
            <div class="mt-8">{{ $coupons->links() }}</div>
        @endif
    </div>

    {{-- Other deal categories --}}
    @if($otherCategories->isNotEmpty())
        <div class="mt-12">
            <h2 class="font-display text-2xl font-semibold mb-2">Other categories with deals</h2>
            <div class="rule-gold mb-6"></div>
            <div class="flex flex-wrap gap-2">
                @foreach($otherCategories as $other)
                    <a href="{{ route('coupons.category', $other) }}"
                       class="px-3 py-1.5 text-sm border border-[color:var(--line)] hover:border-[color:var(--champagne)] hover:text-[color:var(--gold)] transition-colors">
                        {{ $other->name }} <span class="text-xs text-[color:var(--stone)]">{{ $other->live_coupons_count }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
