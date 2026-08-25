@extends('layouts.app')
@section('title', 'Opal Type Guide — Black, Boulder, Crystal & Doublets — Gleamion.com')
@section('meta_description', 'Compare opal types by value and durability, understand solid vs doublet vs triplet, and learn how to care for one properly.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Opal Type Guide</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Opal value spans from a few dollars to more per carat than diamond. Type and construction explain almost all of that range.</p>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-4">Types by value</h2>
        <div id="types" class="space-y-2"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">Solid, doublet or triplet — this matters most</h2>
        <div class="space-y-3 text-sm">
            <div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC">
                <p class="font-medium">Solid opal</p>
                <p class="text-[color:var(--stone)] mt-1">One piece of natural opal throughout. The only type that holds value, can be safely worn long-term, and can be re-polished if scratched.</p>
            </div>
            <div class="border-l-4 p-4" style="border-color:var(--stone); background:#FBF7EC">
                <p class="font-medium">Doublet</p>
                <p class="text-[color:var(--stone)] mt-1">A thin slice of opal glued to a dark backing to deepen the colour. Looks impressive for a fraction of the price — but water seeps between the layers over time, clouding it permanently. Never submerge one.</p>
            </div>
            <div class="border-l-4 p-4" style="border-color:#C0563A; background:#FDF3F0">
                <p class="font-medium">Triplet</p>
                <p class="text-[color:var(--stone)] mt-1">Opal slice with a backing <em>and</em> a clear quartz or glass cap. The cheapest construction, and the opal layer can be paper-thin. Effectively costume jewelry — expect a limited life and no resale value.</p>
            </div>
        </div>
        <p class="text-xs text-[color:var(--stone)] mt-4">
            Check the side profile: layers are visible as distinct lines at the edge of the stone. A seller who will not
            confirm construction in writing is telling you something.
        </p>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">Living with an opal</h2>
        <ul class="space-y-2 text-sm text-[color:var(--stone)]">
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span>Mohs 5.5–6.5 — softer than window glass. Not suitable for a ring worn daily unless bezel-set and treated carefully.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span>Contains 3–10% water. Extreme dryness or sudden heat can cause crazing — a network of fine cracks that cannot be repaired.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span>Never ultrasonic, never steam, never a hot car dashboard. Damp cloth only.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span>Ethiopian opal is hydrophane — it absorbs water and can temporarily lose its colour play entirely. It usually recovers when dry, but avoid getting it wet.</span></li>
        </ul>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const T = [
        ['Black opal','Highest','Dark body tone that makes the colour play blaze. Almost exclusively from Lightning Ridge, Australia. Fine examples exceed $10,000 per carat — the most valuable opal by a wide margin.'],
        ['Boulder opal','High','Opal formed in ironstone, cut with the host rock left as backing. Naturally dark backing gives strong colour, and the ironstone adds real durability. Excellent value for the effect.'],
        ['Crystal opal','High','Transparent to semi-transparent with colour play throughout the body. Luminous and highly prized when the play is strong.'],
        ['White / milky opal','Moderate','Pale body tone, softer colour play. The most common and most affordable solid opal — what most people picture.'],
        ['Fire opal','Moderate','Orange to red body colour, often transparent, from Mexico. May show no play of colour at all and is valued on body colour alone.'],
        ['Ethiopian (Welo) opal','Moderate','Bright colour play at accessible prices. Hydrophane — absorbs water and changes appearance temporarily, which unsettles some buyers.'],
        ['Matrix opal','Lower','Opal dispersed through host rock in a web. Attractive and inexpensive; often treated with sugar and acid to darken.'],
        ['Synthetic / lab opal','Lowest','Genuine colour play, perfectly regular patterning that gives it away. Priced as a fashion stone and behaves like natural opal for care.'],
    ];
    document.getElementById('types').innerHTML = T.map(function (t) {
        return '<div class="border border-[color:var(--line)] p-4">' +
            '<div class="flex flex-wrap items-baseline gap-3"><span class="font-display text-lg font-semibold">' + t[0] + '</span>' +
            '<span class="text-xs px-2 py-0.5 border" style="border-color:var(--gold);color:var(--gold)">' + t[1] + ' value</span></div>' +
            '<p class="text-sm text-[color:var(--stone)] mt-1">' + t[2] + '</p></div>';
    }).join('');
})();
</script>
@endpush
