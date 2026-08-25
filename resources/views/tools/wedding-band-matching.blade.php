@extends('layouts.app')

@section('title', 'Wedding Band Matching Guide — Pairing With an Engagement Ring — Gleamion.com')
@section('meta_description', 'Find a wedding band that sits flush with your engagement ring, in a metal that won’t wear it down, with practical advice on contours and stacking.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Wedding Band Matching</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">The band sits against the engagement ring for decades — the wrong pairing rubs, gaps or wears grooves into the softer metal.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-3">
            <div>
                <label class="block text-sm font-medium mb-1" for="setting">Engagement ring setting</label>
                <select id="setting" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="solitaire" selected>Plain solitaire</option>
                    <option value="cathedral">Cathedral (raised arches)</option>
                    <option value="halo">Halo</option>
                    <option value="lowset">Low-set or bezel</option>
                    <option value="vintage">Vintage / ornate</option>
                    <option value="threestone">Three stone</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="ringmetal">Engagement ring metal</label>
                <select id="ringmetal" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="platinum">Platinum</option>
                    <option value="18k" selected>18K gold</option>
                    <option value="14k">14K gold</option>
                    <option value="whitegold">White gold</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="bandmetal">Band metal you want</label>
                <select id="bandmetal" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="same" selected>Same as engagement ring</option>
                    <option value="platinum">Platinum</option>
                    <option value="18k">18K gold</option>
                    <option value="14k">14K gold</option>
                    <option value="titanium">Titanium / tungsten</option>
                </select>
            </div>
        </div>
        <div id="shape" class="mt-6"></div>
        <div id="metalwarn" class="mt-4"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">Practical points people learn too late</h2>
        <ul class="space-y-2 text-sm text-[color:var(--stone)]">
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Buy the band where you bought the ring, if you can.</strong> Matching a contour to an existing ring by eye rarely works; the maker has the exact profile.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Two rings on one finger fit tighter than one.</strong> Size the pair together — many people need a quarter size up.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Soldering them together</strong> stops spinning and rubbing entirely, but means they can never be worn separately or resized independently.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Order early.</strong> Custom contoured bands take four to eight weeks, and longer before wedding season.</span></li>
        </ul>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const SHAPES = {
        solitaire: ['Straight band', 'A plain solitaire sits high enough that a straight band usually meets it flush. The simplest and cheapest pairing, and easy to replace or match later.'],
        cathedral: ['Contoured (curved) band', 'Cathedral arches sweep down to the band, so a straight ring leaves a visible gap. A contoured band curves to nest against the arches — have it made to match your specific ring rather than bought off the shelf.'],
        halo: ['Contoured or notched band', 'The halo extends beyond the band width, so a straight band will sit apart from it. A notched band cut to clear the halo gives the closest fit.'],
        lowset: ['Straight band', 'Low-set and bezel rings have no overhang, so a straight band sits flush with no shaping needed. The most forgiving setting to match.'],
        vintage: ['Shaped or matching band', 'Ornate vintage rings rarely pair neatly with modern bands. Either commission a shaped band, or lean into contrast and wear the band on the other hand.'],
        threestone: ['Straight or gently contoured', 'Side stones often sit low and wide. A straight band works if they clear the band line; otherwise a shallow contour closes the gap.'],
    };

    const HARDNESS = { platinum:4, '18k':3, '14k':3.5, whitegold:3.5, titanium:6 };

    const setting = document.getElementById('setting');
    const ringmetal = document.getElementById('ringmetal');
    const bandmetal = document.getElementById('bandmetal');
    const shape = document.getElementById('shape');
    const metalwarn = document.getElementById('metalwarn');

    function render() {
        const s = SHAPES[setting.value];
        shape.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC">' +
            '<p class="text-xs uppercase tracking-wider text-[color:var(--stone)]">Suggested band shape</p>' +
            '<p class="font-display text-2xl font-semibold" style="color:var(--gold)">' + s[0] + '</p>' +
            '<p class="text-sm mt-2">' + s[1] + '</p></div>';

        const bm = bandmetal.value === 'same' ? ringmetal.value : bandmetal.value;
        const rh = HARDNESS[ringmetal.value], bh = HARDNESS[bm];

        let msg, colour = 'var(--gold)';
        if (bandmetal.value === 'same') {
            msg = 'Matching metals wear at the same rate and age together — the safest choice, and why most people do it.';
        } else if (bh - rh >= 2) {
            colour = '#C0563A';
            msg = 'A significantly harder band will grind a groove into the softer engagement ring over years of contact. This pairing is a genuine problem — titanium and tungsten against gold or platinum is the classic mistake.';
        } else if (Math.abs(bh - rh) >= 0.5) {
            colour = 'var(--stone)';
            msg = 'A modest hardness difference. Expect the softer ring to show slightly more wear where they touch, but nothing dramatic over a normal lifetime.';
        } else {
            msg = 'These metals are close enough in hardness to wear evenly against each other.';
        }

        metalwarn.innerHTML = '<div class="border-l-4 p-4" style="border-color:' + colour + '; background:#FBF7EC">' +
            '<p class="text-sm">' + msg + '</p></div>';
    }

    [setting, ringmetal, bandmetal].forEach(function (e) { e.addEventListener('change', render); });
    render();
})();
</script>
@endpush
