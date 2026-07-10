@extends('layouts.app')

@section('title', 'Stores, Coupons & Deals — JewelFind')
@section('meta_description', 'Current coupons, promo codes and deals from jewelry stores in the JewelFind directory.')

@section('content')
<section style="background:var(--ink)" class="text-white">
    <div class="max-w-6xl mx-auto px-4 py-12 text-center">
        <p class="eyebrow mb-3">Save on sparkle</p>
        <h1 class="font-display text-4xl sm:text-5xl font-semibold">Stores, Coupons &amp; Deals</h1>
    </div>
</section>

<div class="max-w-6xl mx-auto px-4 py-12">
    @if($coupons->isEmpty())
        <p class="text-[color:var(--stone)]">No active deals right now — check back soon.</p>
    @else
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($coupons as $coupon)
                <div class="card p-5 flex flex-col border-l-4" style="border-left-color:var(--gold)">
                    @if($coupon->discount)
                        <p class="font-display text-3xl font-semibold" style="color:var(--gold)">{{ $coupon->discount }}</p>
                    @endif
                    <p class="font-semibold mt-1">{{ $coupon->title }}</p>
                    @if($coupon->description)
                        <p class="text-sm text-[color:var(--stone)] mt-1">{{ $coupon->description }}</p>
                    @endif
                    <div class="mt-3 text-sm">
                        <a href="{{ route('business.show', $coupon->business) }}" class="font-medium hover:text-[color:var(--gold)]">{{ $coupon->business->name }}</a>
                        <span class="text-[color:var(--stone)]"> · {{ $coupon->business->city?->full_name }}</span>
                    </div>
                    <div class="mt-auto pt-3 flex items-center justify-between">
                        @if($coupon->code)
                            <button type="button" onclick="navigator.clipboard.writeText('{{ $coupon->code }}'); this.textContent='Copied!'"
                                    class="px-3 py-1 text-sm border border-dashed border-[color:var(--champagne)] bg-[color:var(--paper)] hover:border-[color:var(--gold)]">{{ $coupon->code }}</button>
                        @else
                            <span class="text-xs text-[color:var(--stone)]">No code needed</span>
                        @endif
                        @if($coupon->expires_at)
                            <span class="text-xs text-[color:var(--stone)]">Until {{ $coupon->expires_at->format('M j, Y') }}</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $coupons->links() }}</div>
    @endif
</div>
@endsection
