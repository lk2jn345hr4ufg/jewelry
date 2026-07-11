@extends('layouts.app')

@section('title', ($q ? "Search: {$q}" : 'All Businesses') . ' — Gleamion.com')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    @include('partials.breadcrumbs', ['crumbs' => [['label' => $q ? 'Search results' : 'All businesses']]])

    <div class="mt-6 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <h1 class="font-display text-4xl font-semibold">
            {{ $q ? 'Results for “' . $q . '”' : 'All businesses' }}
        </h1>
        @include('partials.search', ['class' => 'sm:max-w-md'])
    </div>

    <div class="mt-8">
        @if($businesses->isEmpty())
            <p class="text-[color:var(--stone)]">Nothing matched. Try a category like “watch repair” or a city name.</p>
        @else
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($businesses as $business)
                    @include('partials.business-card', ['business' => $business])
                @endforeach
            </div>
            <div class="mt-8">{{ $businesses->links() }}</div>
        @endif
    </div>
</div>
@endsection
