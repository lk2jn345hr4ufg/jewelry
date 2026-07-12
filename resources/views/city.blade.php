@extends('layouts.app')

@php
    $pageTitle = $category
        ? "{$category->name} in {$city->full_name}"
        : "Jewelry Businesses in {$city->full_name}";
@endphp

@section('title', $pageTitle . ' — Gleamion.com')
@section('meta_description', "Browse {$total} " . ($category ? strtolower($category->name) : 'jewelry') . " businesses in {$city->full_name}: addresses, opening hours, reviews and map.")

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">

    @include('partials.breadcrumbs', ['crumbs' => array_filter([
        ['label' => $city->full_name, 'url' => $category ? route('city.show', $city) : null],
        $category ? ['label' => $category->name] : null,
    ])])

    <div class="mt-6 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
        <div>
            <p class="eyebrow mb-2">{{ $total }} {{ \Illuminate\Support\Str::plural('business', $total) }} listed</p>
            <h1 class="font-display text-4xl font-semibold">{{ $pageTitle }}</h1>
        </div>
        @include('partials.search', ['placeholder' => 'Search in ' . $city->name . '…', 'class' => 'lg:max-w-md'])
    </div>

    {{-- Category filter chips for this city --}}
    @if($cityCategories->isNotEmpty())
        <div class="mt-6 flex flex-wrap gap-2">
            <a href="{{ route('city.show', $city) }}"
               class="px-3 py-1.5 text-sm border {{ !$category ? 'btn-gold border-transparent' : 'btn-ghost' }}">All categories</a>
            @foreach($cityCategories as $cat)
                <a href="{{ route('city.category', [$city, $cat]) }}"
                   class="px-3 py-1.5 text-sm border {{ $category && $category->id === $cat->id ? 'btn-gold border-transparent' : 'btn-ghost' }}">{{ $cat->name }}</a>
            @endforeach
        </div>
    @endif

    {{-- Map --}}
    @if($mapBusinesses->isNotEmpty())
        <div class="mt-8 card p-0 overflow-hidden">
            <div id="cityMap" class="w-full" style="height: 380px"></div>
        </div>
    @endif

    {{-- Businesses list --}}
    <div class="mt-8">
        <h2 class="font-display text-2xl font-semibold mb-2">All jewelry businesses profiles in {{ $city->name }}</h2>
        <div class="rule-gold mb-6"></div>

        @if($businesses->isEmpty())
            <p class="text-[color:var(--stone)]">No businesses listed here yet.
                <a href="{{ route('home') }}" class="text-[color:var(--gold)] hover:underline">Browse other cities</a>.</p>
        @else
            <div id="businessGrid" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($businesses as $business)
                    @include('partials.business-card', ['business' => $business])
                @endforeach
            </div>

            @if($hasMore)
                <div class="mt-6 text-center">
                    <button id="loadMoreBtn" data-offset="{{ $businesses->count() }}" class="btn-ghost px-8 py-2.5 text-sm font-medium">Load more</button>
                </div>
            @endif
        @endif
    </div>

    {{-- Biggest cities --}}
    <div class="mt-14">
        <h2 class="font-display text-2xl font-semibold mb-2">Biggest cities with jewelry businesses near {{ $city->name }}</h2>
        <div class="rule-gold mb-6"></div>
        <div class="flex flex-wrap gap-2">
            @foreach($biggestCities as $bc)
                <a href="{{ $category ? route('city.category', [$bc, $category]) : route('city.show', $bc) }}"
                   class="btn-ghost px-3 py-1.5 text-sm {{ $bc->id === $city->id ? 'border-[color:var(--gold)] text-[color:var(--gold)]' : '' }}">{{ $bc->full_name }}</a>
            @endforeach
        </div>
    </div>
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
const points = @json($mapBusinesses);
const map = L.map('cityMap', { scrollWheelZoom: false });
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);
const goldIcon = L.divIcon({
    className: '',
    html: '<div style="width:14px;height:14px;background:#B98B2F;transform:rotate(45deg);border:2px solid #fff;box-shadow:0 1px 4px rgba(0,0,0,.4)"></div>',
    iconSize: [14, 14],
    iconAnchor: [7, 7]
});
const bounds = [];
points.forEach(p => {
    bounds.push([p.lat, p.lng]);
    L.marker([p.lat, p.lng], { icon: goldIcon })
        .addTo(map)
        .bindPopup(`<strong><a href="${p.url}">${escapeHtml(p.name)}</a></strong><br>${escapeHtml(p.address ?? '')}`);
});
if (bounds.length > 1) map.fitBounds(bounds, { padding: [30, 30] });
else map.setView(bounds[0], 13);
</script>
@endif
<script>
const moreBtn = document.getElementById('loadMoreBtn');
if (moreBtn) {
    moreBtn.addEventListener('click', async function () {
        moreBtn.disabled = true;
        moreBtn.textContent = 'Loading…';
        const offset = parseInt(moreBtn.dataset.offset, 10);
        const url = new URL(`{{ route('city.businesses', $city) }}`, window.location.origin);
        url.searchParams.set('offset', offset);
        @if($category) url.searchParams.set('category', '{{ $category->slug }}'); @endif
        try {
            const res = await fetch(url);
            const data = await res.json();
            document.getElementById('businessGrid').insertAdjacentHTML('beforeend', data.html);
            moreBtn.dataset.offset = offset + {{ \App\Http\Controllers\CityController::PER_PAGE }};
            if (!data.hasMore) moreBtn.remove();
            else { moreBtn.disabled = false; moreBtn.textContent = 'Load more'; }
        } catch (e) {
            moreBtn.disabled = false;
            moreBtn.textContent = 'Load more';
        }
    });
}
</script>
@endpush
