@extends('layouts.app')

@section('title', 'Jewelry Tools & Calculators — Gleamion.com')
@section('meta_description', 'Free jewelry tools: ring size converter, gold value calculator, diamond price estimator, birthstone guide, care instructions and metal comparison.')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    <p class="eyebrow mb-2">Useful tools</p>
    <h1 class="font-display text-4xl font-semibold">Jewelry Tools &amp; Calculators</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:12rem"></div>
    <p class="text-[color:var(--stone)] max-w-2xl">
        Quick, free tools for buying, selling and caring for jewelry — no sign-up, results update as you type.
    </p>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 mt-8">
        @foreach($tools as $slug => $tool)
            <a href="{{ route('tools.show', $slug) }}" class="card p-5 flex flex-col group">
                <span class="facet-sm mb-3" style="background:var(--gold)"></span>
                <h2 class="font-display text-xl font-semibold group-hover:text-[color:var(--gold)]">{{ $tool['title'] }}</h2>
                <p class="text-sm text-[color:var(--stone)] mt-2 flex-1">{{ $tool['blurb'] }}</p>
                <span class="text-sm font-medium mt-4 group-hover:text-[color:var(--gold)]">Open tool →</span>
            </a>
        @endforeach
    </div>
</div>
@endsection
