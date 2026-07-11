@extends('layouts.app')

@section('title', $category->name . ' — Gleamion.com')
@section('meta_description', "Browse {$total} " . strtolower($category->name) . " businesses across the directory: addresses, opening hours, reviews and map, city by city.")

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">

    @include('partials.breadcrumbs', ['crumbs' => [
        ['label' => $category->name],
    ]])

    <div class="mt-6 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
        <div>
            <p class="eyebrow mb-2">{{ $total }} {{ \Illuminate\Support\Str::plural('business', $total) }} listed</p>
            <h1 class="font-display text-4xl font-semibold">{{ $category->name }}</h1>
            @if($category->description)
                <p class="mt-3 text-[color:var(--stone)] max-w-2xl">{{ $category->description }}</p>
            @endif
        </div>
        @include('partials.search', ['placeholder' => 'Search ' . strtolower($category->name) . '…', 'class' => 'lg:max-w-md'])
    </div>

    {{-- Cities offering this category --}}
    @if($cities->isNotEmpty())
        <div class="mt-8">
            <h2 class="font-display text-2xl font-semibold mb-2">{{ $category->name }} by city</h2>
            <div class="rule-gold mb-5"></div>
            <div class="flex flex-wrap gap-2">
                @foreach($cities as $city)
                    <a href="{{ route('city.category', [$city, $category]) }}"
                       class="px-3 py-1.5 text-sm border border-[color:var(--line)] hover:border-[color:var(--champagne)] hover:text-[color:var(--gold)] transition-colors">
                        {{ $city->name }} <span class="text-xs text-[color:var(--stone)]">{{ $city->businesses_count }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Map --}}
    @if($mapBusinesses->isNotEmpty())
        <div class="mt-8">
            <div id="map" class="map-frame" data-businesses="{{ json_encode($mapBusinesses) }}"></div>
        </div>
    @endif

    {{-- Businesses --}}
    <div class="mt-10">
        <h2 class="font-display text-2xl font-semibold mb-2">All {{ $category->name }}</h2>
        <div class="rule-gold mb-6"></div>
        @if($businesses->isEmpty())
            <p class="text-[color:var(--stone)]">No businesses in this category yet.</p>
        @else
            <div id="businessGrid" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($businesses as $business)
                    @include('partials.business-card', ['business' => $business])
                @endforeach
            </div>
            @if($hasMore)
                <div class="text-center mt-8">
                    <button id="loadMoreBtn" data-offset="{{ $businesses->count() }}" class="btn-ghost px-8 py-2.5 text-sm font-medium">Load more</button>
                </div>
            @endif
        @endif
    </div>

    {{-- Other categories --}}
    @if($otherCategories->isNotEmpty())
        <div class="mt-12">
            <h2 class="font-display text-2xl font-semibold mb-2">Other categories</h2>
            <div class="rule-gold mb-5"></div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($otherCategories as $other)
                    <a href="{{ route('category.show', $other) }}" class="group flex items-center gap-3 px-4 py-3 border border-[color:var(--line)] hover:border-[color:var(--champagne)] transition-colors">
                        <span class="facet-sm" style="background:var(--champagne)"></span>
                        <span class="font-medium group-hover:text-[color:var(--gold)]">{{ $other->name }}</span>
                        <span class="ml-auto text-xs text-[color:var(--stone)]">{{ $other->businesses_count }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@push('head')
@if($mapBusinesses->isNotEmpty())
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endif
@endpush

@push('scripts')
@if($mapBusinesses->isNotEmpty())
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
    const el = document.getElementById('map');
    if (!el || !window.L) return;
    const businesses = JSON.parse(el.dataset.businesses || '[]');
    if (!businesses.length) return;
    const map = L.map(el).setView([businesses[0].lat, businesses[0].lng], 4);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
    const icon = L.divIcon({
        className: '',
        html: '<div style="width:13px;height:13px;background:var(--gold,#B08C3D);transform:rotate(45deg);border:2px solid #fff;box-shadow:0 1px 4px rgba(0,0,0,.4)"></div>',
        iconSize: [13, 13],
        iconAnchor: [7, 7]
    });
    const markers = businesses.map(b => L.marker([b.lat, b.lng], { icon })
        .addTo(map)
        .bindPopup('<strong><a href="' + b.url + '">' + b.name + '</a></strong><br>' + (b.address || '')));
    if (markers.length > 1) {
        map.fitBounds(L.featureGroup(markers).getBounds().pad(0.15));
    } else {
        map.setView([businesses[0].lat, businesses[0].lng], 12);
    }
})();

const moreBtn = document.getElementById('loadMoreBtn');
if (moreBtn) {
    moreBtn.addEventListener('click', async function () {
        moreBtn.disabled = true;
        moreBtn.textContent = 'Loading…';
        const offset = parseInt(moreBtn.dataset.offset, 10);
        const url = new URL(`{{ route('category.businesses', $category) }}`, window.location.origin);
        url.searchParams.set('offset', offset);
        try {
            const res = await fetch(url);
            const data = await res.json();
            document.getElementById('businessGrid').insertAdjacentHTML('beforeend', data.html);
            moreBtn.dataset.offset = offset + {{ \App\Http\Controllers\CategoryController::PER_PAGE }};
            if (!data.hasMore) moreBtn.remove();
            else { moreBtn.disabled = false; moreBtn.textContent = 'Load more'; }
        } catch (e) {
            moreBtn.disabled = false;
            moreBtn.textContent = 'Load more';
        }
    });
}
</script>
@else
<script>
const moreBtn = document.getElementById('loadMoreBtn');
if (moreBtn) {
    moreBtn.addEventListener('click', async function () {
        moreBtn.disabled = true;
        const offset = parseInt(moreBtn.dataset.offset, 10);
        const url = new URL(`{{ route('category.businesses', $category) }}`, window.location.origin);
        url.searchParams.set('offset', offset);
        try {
            const res = await fetch(url);
            const data = await res.json();
            document.getElementById('businessGrid').insertAdjacentHTML('beforeend', data.html);
            moreBtn.dataset.offset = offset + {{ \App\Http\Controllers\CategoryController::PER_PAGE }};
            if (!data.hasMore) moreBtn.remove();
            else moreBtn.disabled = false;
        } catch (e) { moreBtn.disabled = false; }
    });
}
</script>
@endif
@endpush
