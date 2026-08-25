@extends('layouts.app')
@section('title', 'Ruby Buying Guide — Colour, Origin & Treatment — Gleamion.com')
@section('meta_description', 'What makes a ruby valuable: pigeon blood colour, Burmese origin, and why most rubies on the market are heavily treated.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Ruby Buying Guide</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Fine ruby is rarer than comparable diamond and can exceed it per carat. It is also the most aggressively treated stone in the trade.</p>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-4">Colour grades</h2>
        <div id="colours" class="space-y-2"></div>
        <p class="text-xs text-[color:var(--stone)] mt-4">
            The line between ruby and pink sapphire is a judgement call, not a rule — and it moves depending on the market.
            The same stone can be certified either way by different labs, with a large price difference attached.
        </p>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-4">Origins</h2>
        <div id="origins" class="space-y-2"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">Treatments — read this before spending</h2>
        <div class="space-y-3 text-sm">
            <div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC">
                <p class="font-medium">Heat only — acceptable</p>
                <p class="text-[color:var(--stone)] mt-1">Over 90% of rubies are heated. Permanent, stable and expected. An unheated stone with fine colour commands a very large premium precisely because it is rare.</p>
            </div>
            <div class="border-l-4 p-4" style="border-color:#C0563A; background:#FDF3F0">
                <p class="font-medium">Lead-glass filled — buy only with open eyes</p>
                <p class="text-[color:var(--stone)] mt-1">Heavily fractured low-grade corundum is impregnated with molten lead glass, transforming near-worthless material into apparently clean red stones. These fill the high-street market. They are damaged by ordinary jeweler's heat, ultrasonic cleaning, and even lemon juice — and are worth perhaps 1–2% of an equivalent untreated ruby. If a large "ruby" costs a few hundred dollars, this is why.</p>
            </div>
            <div class="border-l-4 p-4" style="border-color:#C0563A; background:#FDF3F0">
                <p class="font-medium">Beryllium diffusion — significant devaluation</p>
                <p class="text-[color:var(--stone)] mt-1">Colour driven in at high temperature with beryllium. Must be disclosed; substantially reduces value versus a simply heated stone.</p>
            </div>
        </div>
        <p class="text-xs text-[color:var(--stone)] mt-4 border-t border-[color:var(--line)] pt-4">
            For any ruby above roughly $1,000, insist on a report from GIA, SSEF, Gübelin or AGL stating treatment status.
            A seller who resists this is answering the question for you.
        </p>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const C = [
        ['Pigeon blood','#C4142B','Vivid pure red with a faint blue undertone and strong fluorescence. The benchmark term, applied by labs only to exceptional Burmese material. Prices run to hundreds of thousands per carat.'],
        ['Vivid red','#CE1F2E','Strong saturation without the specific pigeon blood designation. Still commands very high prices.'],
        ['Medium red','#C43244','The commercial fine range. Attractive and far more attainable.'],
        ['Purplish red','#B03050','Purple undertone lowers value somewhat, though many find it beautiful and it offers real value.'],
        ['Orangey red','#CB4A31','Orange modifier reduces price. Often seen in Thai and African material.'],
        ['Dark red','#8E1B26','Overly dark stones read almost black in low light and are priced well below vivid material.'],
        ['Pinkish red','#D4566B','Borders on pink sapphire. Certification determines the name — and a large part of the price.'],
    ];
    const O = [
        ['Burma (Myanmar) — Mogok','Highest','The classic source of pigeon blood ruby, with strong red fluorescence that makes stones glow. Note that Burmese stones carry genuine ethical sourcing concerns, and US import restrictions have applied at various times — ask about provenance and documentation.'],
        ['Mozambique','High','The dominant modern source, producing excellent material that increasingly rivals Burmese. Generally better value for comparable appearance.'],
        ['Thailand','Moderate','Historically darker, more garnet-toned rubies. Bangkok remains the centre of the global treatment and trading business.'],
        ['Sri Lanka','Moderate','Lighter, pinker stones with good clarity and brightness.'],
        ['Madagascar','Moderate','Growing source with variable quality across a wide range.'],
        ['Tanzania (Winza)','Moderate','Some exceptional untreated material found since 2007, often with distinctive inclusions.'],
    ];
    document.getElementById('colours').innerHTML = C.map(function (c) {
        return '<div class="border border-[color:var(--line)] p-4 flex gap-3">' +
            '<div style="width:36px;height:36px;border-radius:50%;background:' + c[1] + ';border:1px solid #bbb;flex:none"></div>' +
            '<div><h3 class="font-medium">' + c[0] + '</h3><p class="text-sm text-[color:var(--stone)] mt-1">' + c[2] + '</p></div></div>';
    }).join('');
    document.getElementById('origins').innerHTML = O.map(function (o) {
        return '<div class="border border-[color:var(--line)] p-4">' +
            '<div class="flex flex-wrap items-baseline gap-3"><span class="font-display text-lg font-semibold">' + o[0] + '</span>' +
            '<span class="text-xs px-2 py-0.5 border" style="border-color:var(--gold);color:var(--gold)">' + o[1] + ' premium</span></div>' +
            '<p class="text-sm text-[color:var(--stone)] mt-1">' + o[2] + '</p></div>';
    }).join('');
})();
</script>
@endpush
