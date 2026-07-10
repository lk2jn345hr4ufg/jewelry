{{-- $business with city & category loaded --}}
<article class="card p-5 flex flex-col gap-2 transition-colors">
    <div class="flex items-start justify-between gap-3">
        <h3 class="font-display text-xl font-semibold leading-snug">
            <a href="{{ route('business.show', $business) }}" class="hover:text-[color:var(--gold)]">{{ $business->name }}</a>
        </h3>
        @include('partials.rating', ['rating' => $business->averageRating()])
    </div>
    <p class="text-sm" style="color:var(--gold)">{{ $business->category?->name }}</p>
    @if($business->address)
        <p class="text-sm text-[color:var(--stone)]">{{ $business->address }}</p>
    @endif
    <div class="mt-auto pt-2 flex items-center justify-between text-sm">
        <a href="{{ route('city.show', $business->city) }}" class="text-[color:var(--stone)] hover:text-[color:var(--gold)]">{{ $business->city?->full_name }}</a>
        <a href="{{ route('business.show', $business) }}" class="font-medium hover:text-[color:var(--gold)]">View profile →</a>
    </div>
</article>
