@extends('layouts.app')

@section('title', 'Gemstone Treatment Guide — What’s Been Done to Your Stone — Gleamion.com')
@section('meta_description', 'Understand gemstone treatments: heating, oiling, fracture filling, irradiation and dyeing — which are acceptable, which destroy value, and what must be disclosed.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Gemstone Treatment Guide</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Most coloured stones on the market are treated. Some treatments are normal and stable; others hide serious weakness. Knowing which is which protects you.</p>

    <div class="card p-6 mt-6">
        <label class="block text-sm font-medium mb-1" for="stone">Which stone?</label>
        <select id="stone" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white"></select>
        <div id="detail" class="mt-5"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">Treatments ranked by acceptability</h2>
        <div id="ranked" class="space-y-2"></div>
        <p class="text-xs text-[color:var(--stone)] mt-4 border-t border-[color:var(--line)] pt-4">
            In the US, the FTC requires sellers to disclose treatments that affect value or require special care.
            A seller who cannot tell you what has been done to a stone either does not know — which is its own warning —
            or is choosing not to say. For anything expensive, insist on a report from an independent lab.
        </p>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const TREATMENTS = {
        heat:      ['Heat treatment', 5, 'Permanent and stable, practised for centuries. Improves colour and clarity. Universally accepted and expected in most sapphire and ruby — an untreated stone commands a large premium precisely because it is rare.'],
        oil:       ['Oiling / resin', 3, 'Colourless oil or resin fills surface-reaching fissures, making them less visible. Standard for emerald and disclosed as minor, moderate or significant. It can dry out or leak over decades and may need redoing — and it rules out ultrasonic cleaning permanently.'],
        fracture:  ['Fracture filling (glass/lead)', 1, 'Molten glass fills large cracks, dramatically improving apparent clarity. Common in cheap "ruby". The filling can be damaged by a jeweler’s torch, ultrasonic cleaning or even lemon juice. These stones are worth a small fraction of untreated equivalents.'],
        irradiate: ['Irradiation', 4, 'Radiation alters colour — nearly all blue topaz is irradiated. Stable and safe (stones are held until residual radioactivity decays to legal limits), and widely accepted.'],
        diffusion: ['Diffusion / lattice', 2, 'Colour is driven into the surface layer at high heat. If the colour is only skin-deep, a re-polish or chip exposes pale material underneath. Must be disclosed; substantially reduces value.'],
        dye:       ['Dyeing', 2, 'Colour added to porous stones — common in turquoise, jade, lapis and low-grade pearls. Can fade or bleed with sunlight, solvents or even sweat.'],
        hpht:      ['HPHT (diamond)', 3, 'High pressure, high temperature treatment that whitens brownish diamonds or creates fancy colours. Permanent, but must be disclosed and reduces value versus a naturally coloured stone.'],
        coating:   ['Coating', 1, 'A thin film applied to the surface (mystic topaz, some quartz). Scratches off with normal wear. Effectively decorative rather than permanent.'],
        stabilise: ['Stabilisation', 4, 'Resin impregnation that hardens porous material — nearly all commercial turquoise is stabilised. Genuinely improves durability and is widely accepted at this price level.'],
        none:      ['Typically untreated', 5, 'This stone is usually sold in its natural state. Untreated status still deserves confirmation on expensive purchases.'],
    };

    const STONES = {
        'Sapphire': ['heat','diffusion','fracture'],
        'Ruby': ['heat','fracture','diffusion'],
        'Emerald': ['oil','fracture','dye'],
        'Diamond': ['hpht','fracture','irradiate'],
        'Blue topaz': ['irradiate','coating'],
        'Tanzanite': ['heat'],
        'Aquamarine': ['heat'],
        'Amethyst': ['heat','irradiate'],
        'Citrine': ['heat'],
        'Turquoise': ['stabilise','dye'],
        'Jade': ['dye','stabilise'],
        'Pearl': ['dye','irradiate'],
        'Opal': ['none','stabilise'],
        'Garnet': ['none'],
        'Spinel': ['none'],
        'Tourmaline': ['heat','irradiate'],
        'Peridot': ['none'],
        'Moissanite': ['none'],
    };

    const stone = document.getElementById('stone');
    const detail = document.getElementById('detail');

    Object.keys(STONES).forEach(function (k) {
        const o = document.createElement('option');
        o.value = k; o.textContent = k;
        stone.appendChild(o);
    });

    const RATING = { 5:['Widely accepted','var(--gold)'], 4:['Accepted','var(--gold)'], 3:['Disclose & care','var(--stone)'], 2:['Reduces value','#C0563A'], 1:['Major concern','#C0563A'] };

    function render() {
        const list = STONES[stone.value];
        detail.innerHTML = '<p class="text-sm text-[color:var(--stone)] mb-3">Treatments commonly seen in ' + stone.value.toLowerCase() + ':</p>' +
            list.map(function (k) {
                const t = TREATMENTS[k];
                const r = RATING[t[1]];
                return '<div class="border-l-4 p-4 mb-2" style="border-color:' + r[1] + '; background:#FBF7EC">' +
                    '<div class="flex flex-wrap items-baseline gap-3">' +
                    '<span class="font-display text-lg font-semibold">' + t[0] + '</span>' +
                    '<span class="text-xs px-2 py-0.5 border" style="border-color:' + r[1] + ';color:' + r[1] + '">' + r[0] + '</span></div>' +
                    '<p class="text-sm mt-1">' + t[2] + '</p></div>';
            }).join('');
    }

    stone.addEventListener('change', render);
    render();

    document.getElementById('ranked').innerHTML = Object.keys(TREATMENTS)
        .map(function (k) { return TREATMENTS[k]; })
        .sort(function (a, b) { return b[1] - a[1]; })
        .map(function (t) {
            const r = RATING[t[1]];
            return '<div class="border border-[color:var(--line)] p-3">' +
                '<div class="flex flex-wrap items-baseline gap-3">' +
                '<span class="font-medium">' + t[0] + '</span>' +
                '<span class="text-xs px-2 py-0.5 border" style="border-color:' + r[1] + ';color:' + r[1] + '">' + r[0] + '</span></div>' +
                '<p class="text-sm text-[color:var(--stone)] mt-1">' + t[2] + '</p></div>';
        }).join('');
})();
</script>
@endpush
