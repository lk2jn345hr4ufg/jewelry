{{-- Coupon / deal card. Expects $coupon with business.city loaded. --}}
<div class="card p-5 flex flex-col border-l-4" style="border-left-color:var(--gold)">
    @if($coupon->discount)
        <p class="font-display text-3xl font-semibold" style="color:var(--gold)">{{ $coupon->discount }}</p>
    @endif
    <p class="font-semibold mt-1">{{ $coupon->title }}</p>
    @if($coupon->description)
        <p class="text-sm text-[color:var(--stone)] mt-1">{{ $coupon->description }}</p>
    @endif
    <div class="mt-3 text-sm">
        <a href="{{ route('coupons.show', $coupon->business) }}" class="font-medium hover:text-[color:var(--gold)]">{{ $coupon->business->name }}</a>
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
