@extends('layouts.app')

@section('title', 'JewelFind — Find Jewelry Businesses Near You')

@section('content')

{{-- Hero + search --}}
<section style="background:var(--ink)" class="text-white">
    <div class="max-w-6xl mx-auto px-4 pt-14 pb-16 sm:pt-20 sm:pb-24 text-center">
        <p class="eyebrow mb-4">The jewelry business directory</p>
        <h1 class="font-display text-4xl sm:text-6xl font-semibold leading-tight max-w-3xl mx-auto">
            Every jeweler worth knowing,<br class="hidden sm:block"> city by city
        </h1>
        <p class="mt-4 text-white/60 max-w-xl mx-auto">Jewelers, watchmakers, appraisers, engagement-ring specialists and custom designers — reviewed by real customers.</p>
        <div class="mt-8 flex justify-center">
            @include('partials.search')
        </div>
    </div>
</section>

{{-- Browse by City --}}
<section class="max-w-6xl mx-auto px-4 py-14">
    <div class="flex items-end justify-between gap-4 mb-2">
        <h2 class="font-display text-3xl font-semibold">Browse by City</h2>
        <span class="text-sm text-[color:var(--stone)]">{{ $totalCities }} cities</span>
    </div>
    <div class="rule-gold mb-8"></div>

    <div id="cityGrid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
        @foreach($cities as $city)
            <a href="{{ route('city.show', $city) }}" class="card px-4 py-3 flex items-center justify-between text-sm transition-colors">
                <span class="font-medium">{{ $city->full_name }}</span>
                <span class="text-xs text-[color:var(--stone)]">{{ $city->active_businesses_count }}</span>
            </a>
        @endforeach
    </div>

    @if($totalCities > $cities->count())
        <div class="mt-6 text-center">
            <button id="loadCitiesBtn" data-offset="{{ $cities->count() }}" class="btn-ghost px-8 py-2.5 text-sm font-medium">Load more cities</button>
        </div>
    @endif
</section>

{{-- Popular Categories --}}
<section class="border-y border-[color:var(--line)] bg-white">
    <div class="max-w-6xl mx-auto px-4 py-14">
        <h2 class="font-display text-3xl font-semibold mb-2">Popular Categories</h2>
        <div class="rule-gold mb-8"></div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($categories as $category)
                <a href="{{ route('search', ['q' => $category->name]) }}" class="group flex items-center gap-3 px-4 py-3 border border-[color:var(--line)] hover:border-[color:var(--champagne)] transition-colors">
                    <span class="facet-sm" style="background:var(--champagne)"></span>
                    <span class="font-medium group-hover:text-[color:var(--gold)]">{{ $category->name }}</span>
                    <span class="ml-auto text-xs text-[color:var(--stone)]">{{ $category->businesses_count }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Recently Added --}}
<section class="max-w-6xl mx-auto px-4 py-14">
    <h2 class="font-display text-3xl font-semibold mb-2">Recently Added</h2>
    <div class="rule-gold mb-8"></div>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($recent as $business)
            @include('partials.business-card', ['business' => $business])
        @empty
            <p class="text-[color:var(--stone)]">No businesses yet. Check back soon.</p>
        @endforelse
    </div>
</section>

{{-- Latest Reviews --}}
@if($latestReviews->isNotEmpty())
<section class="border-t border-[color:var(--line)] bg-white">
    <div class="max-w-6xl mx-auto px-4 py-14">
        <h2 class="font-display text-3xl font-semibold mb-2">Latest Reviews</h2>
        <div class="rule-gold mb-8"></div>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($latestReviews as $review)
                <blockquote class="card p-5">
                    @include('partials.rating', ['rating' => $review->rating])
                    <p class="mt-3 text-sm leading-relaxed text-gray-700">“{{ \Illuminate\Support\Str::limit($review->body, 160) }}”</p>
                    <footer class="mt-4 text-sm">
                        <span class="font-medium">{{ $review->author_name }}</span>
                        <span class="text-[color:var(--stone)]"> on </span>
                        <a href="{{ route('business.show', $review->business) }}" class="hover:text-[color:var(--gold)] font-medium">{{ $review->business->name }}</a>
                    </footer>
                </blockquote>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@push('scripts')
<script>
const loadBtn = document.getElementById('loadCitiesBtn');
if (loadBtn) {
    loadBtn.addEventListener('click', async function () {
        loadBtn.disabled = true;
        loadBtn.textContent = 'Loading…';
        const offset = parseInt(loadBtn.dataset.offset, 10);
        try {
            const res = await fetch(`{{ route('cities.load') }}?offset=${offset}`);
            const data = await res.json();
            const grid = document.getElementById('cityGrid');
            data.cities.forEach(c => {
                const a = document.createElement('a');
                a.href = c.url;
                a.className = 'card px-4 py-3 flex items-center justify-between text-sm transition-colors';
                a.innerHTML = `<span class="font-medium">${escapeHtml(c.name)}</span><span class="text-xs text-[color:var(--stone)]">${c.count}</span>`;
                grid.appendChild(a);
            });
            loadBtn.dataset.offset = offset + data.cities.length;
            if (!data.hasMore) loadBtn.remove();
            else { loadBtn.disabled = false; loadBtn.textContent = 'Load more cities'; }
        } catch (e) {
            loadBtn.disabled = false;
            loadBtn.textContent = 'Load more cities';
        }
    });
}
</script>
@endpush
