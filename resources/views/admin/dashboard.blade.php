@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <h1 class="font-display text-3xl font-semibold text-velvet mb-6">Dashboard</h1>

    <div class="grid gap-4 grid-cols-2 md:grid-cols-5 mb-10">
        @foreach($stats as $label => $value)
            <div class="card p-4 text-center">
                <p class="font-display text-3xl font-semibold text-velvet">{{ $value }}</p>
                <p class="text-xs uppercase tracking-widest text-gold mt-1">{{ $label }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid gap-8 lg:grid-cols-2">
        <section>
            <h2 class="eyebrow mb-4"><span class="facet"></span> Reviews awaiting moderation</h2>
            <div class="space-y-3">
                @forelse($pending as $review)
                    <div class="card p-4 text-sm">
                        <p><strong>{{ $review->author_name }}</strong> · {{ $review->rating }}★ on <em>{{ $review->business->name }}</em></p>
                        <p class="text-ink/60 mt-1">{{ Str::limit($review->body, 120) }}</p>
                    </div>
                @empty
                    <p class="text-sm text-ink/60">Nothing pending. 🎉</p>
                @endforelse
            </div>
            <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline mt-4 text-sm">Moderate reviews</a>
        </section>

        <section>
            <h2 class="eyebrow mb-4"><span class="facet"></span> Latest business profiles</h2>
            <div class="space-y-3">
                @foreach($latest as $business)
                    <div class="card p-4 text-sm flex justify-between gap-3">
                        <span><strong>{{ $business->name }}</strong><br><span class="text-ink/60">{{ $business->category->name }} · {{ $business->city->name }}</span></span>
                        <a href="{{ route('admin.businesses.edit', $business) }}" class="text-velvet font-bold hover:text-gold self-center">Edit</a>
                    </div>
                @endforeach
            </div>
            <a href="{{ route('admin.businesses.create') }}" class="btn btn-gold mt-4 text-sm">Add business</a>
        </section>
    </div>
@endsection
