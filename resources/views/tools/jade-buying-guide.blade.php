@extends('layouts.app')
@section('title', 'Jade Buying Guide — Type A, B and C Explained — Gleamion.com')
@section('meta_description', 'Understand jadeite vs nephrite, the A/B/C treatment grades, and how to avoid paying jade prices for bleached and polymer-filled stone.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Jade Buying Guide</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Jade is the most treatment-plagued gem in the trade. The A/B/C grading refers to treatment, not quality — and the difference is worth thousands.</p>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-4">Treatment grades</h2>
        <div class="space-y-3">
            <div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC">
                <p class="font-display text-xl font-semibold" style="color:var(--gold)">Type A — untreated</p>
                <p class="text-sm mt-1">Natural jadeite, waxed on the surface at most. Colour and structure are entirely natural, and it holds value indefinitely. Only Type A is worth investment prices. Insist on certification from a recognised lab for anything expensive.</p>
            </div>
            <div class="border-l-4 p-4" style="border-color:#C0563A; background:#FDF3F0">
                <p class="font-display text-xl font-semibold" style="color:#C0563A">Type B — bleached and polymer-impregnated</p>
                <p class="text-sm mt-1">Acid-bleached to remove brown staining, then filled with polymer resin to restore structure. Looks bright and clean when new, but the resin yellows and degrades within years to decades. Worth a small fraction of Type A, and often sold as if it were natural.</p>
            </div>
            <div class="border-l-4 p-4" style="border-color:#C0563A; background:#FDF3F0">
                <p class="font-display text-xl font-semibold" style="color:#C0563A">Type C — dyed</p>
                <p class="text-sm mt-1">Colour added artificially, usually after bleaching (Type B+C is common). The dye fades with sunlight and skin contact. Essentially costume material at any price above nominal.</p>
            </div>
        </div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">Jadeite vs nephrite</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="text-left border-b border-[color:var(--line)]">
                    <th class="py-2 pr-4"></th><th class="py-2 pr-4">Jadeite</th><th class="py-2">Nephrite</th>
                </tr></thead>
                <tbody class="text-[color:var(--stone)]">
                    <tr class="border-b border-[color:var(--line)]"><td class="py-2 pr-4 font-medium text-[color:#23262e]">Hardness</td><td class="py-2 pr-4">6.5–7</td><td class="py-2">6–6.5</td></tr>
                    <tr class="border-b border-[color:var(--line)]"><td class="py-2 pr-4 font-medium text-[color:#23262e]">Value</td><td class="py-2 pr-4">Far higher — "imperial" green is among the most expensive gems on earth</td><td class="py-2">Modest; widely available</td></tr>
                    <tr class="border-b border-[color:var(--line)]"><td class="py-2 pr-4 font-medium text-[color:#23262e]">Look</td><td class="py-2 pr-4">Glassy, vivid, slightly granular texture</td><td class="py-2">Waxy, softer sheen, often mottled</td></tr>
                    <tr class="border-b border-[color:var(--line)]"><td class="py-2 pr-4 font-medium text-[color:#23262e]">Origin</td><td class="py-2 pr-4">Almost entirely Myanmar</td><td class="py-2">China, Russia, Canada, New Zealand</td></tr>
                    <tr><td class="py-2 pr-4 font-medium text-[color:#23262e]">Note</td><td class="py-2 pr-4">"Jade" without qualification usually means jadeite in the fine trade</td><td class="py-2">Historic Chinese carvings are mostly nephrite</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">Judging quality — and spotting trouble</h2>
        <ul class="space-y-2 text-sm text-[color:var(--stone)]">
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Translucency beats colour.</strong> Hold it to light — the best jade glows from within. Opaque green is far less valuable than translucent pale green.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Listen to it.</strong> Tapping two pieces of Type A jade gives a clear ringing chime; polymer-filled Type B sounds dull and plasticky.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Suspiciously even colour is a warning.</strong> Natural jade varies subtly. Uniform, saturated colour throughout often means dye.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Certification is not optional above a few hundred dollars.</strong> Only a lab can distinguish A from B reliably — no home test is conclusive.</span></li>
        </ul>
    </div>
    @include('tools._other')
</div>
@endsection
