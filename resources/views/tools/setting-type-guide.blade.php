@extends('layouts.app')

@section('title', 'Ring Setting Guide — Prong, Bezel, Pavé & More — Gleamion.com')
@section('meta_description', 'Compare ring setting styles on security, sparkle, snagging and upkeep, and find the one that suits your lifestyle.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Ring Setting Guide</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">The setting decides whether your stone survives the next twenty years. Pick your lifestyle for a recommendation.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium mb-1" for="life">Your daily life</label>
                <select id="life" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="desk" selected>Desk work, careful with hands</option>
                    <option value="active">Active — gym, gardening, kids</option>
                    <option value="manual">Manual work or healthcare (gloves daily)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="want">Priority</label>
                <select id="want" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="sparkle" selected>Maximum light and sparkle</option>
                    <option value="secure">Keeping the stone safe</option>
                    <option value="low">Low maintenance</option>
                </select>
            </div>
        </div>
        <div id="rec" class="mt-6"></div>
        <div id="list" class="mt-6 space-y-3"></div>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const SETTINGS = [
        { name:'Prong (claw)', secure:3, sparkle:5, snag:2, upkeep:3,
          note:'Metal claws hold the stone with minimal coverage, letting light in from every angle — the most brilliant option. Prongs wear thin over 5–10 years and need re-tipping; check them yearly.' },
        { name:'Bezel', secure:5, sparkle:3, snag:5, upkeep:5,
          note:'A metal rim wraps the stone completely. The most secure setting made, snag-free and nearly maintenance-free — the trade-off is less light entering the stone and a slightly smaller look.' },
        { name:'Half bezel', secure:4, sparkle:4, snag:4, upkeep:4,
          note:'Metal on two sides only. A sensible compromise — most of the protection of a bezel with more light and a larger apparent size.' },
        { name:'Channel', secure:4, sparkle:3, snag:5, upkeep:4,
          note:'Small stones sit in a groove between two metal walls, flush with the surface. Excellent for wedding bands worn under gloves; dirt collects in the channel and needs regular cleaning.' },
        { name:'Pavé', secure:2, sparkle:5, snag:3, upkeep:2,
          note:'Tiny stones held by minute beads of metal, creating a continuous glitter. Beautiful, but small stones do come loose over the years — expect occasional resetting.' },
        { name:'Tension', secure:2, sparkle:5, snag:3, upkeep:2,
          note:'The stone appears suspended, held by the spring pressure of the band. Dramatic and modern, but it cannot be resized easily and a hard knock can release the stone.' },
        { name:'Flush (gypsy)', secure:5, sparkle:2, snag:5, upkeep:5,
          note:'The stone sits sunk into the metal, its table level with the surface. Essentially indestructible and completely snag-free — the standard for men’s rings and hard manual work.' },
        { name:'Halo', secure:3, sparkle:5, snag:3, upkeep:2,
          note:'A ring of small stones surrounds the centre, adding up to 1 mm of apparent size cheaply. The many small stones mean more to keep an eye on.' },
        { name:'Cathedral', secure:4, sparkle:4, snag:3, upkeep:3,
          note:'Arches of metal sweep up to support the stone, adding height and stability. Elegant and reasonably protective, though the raised profile catches on things.' },
    ];

    const life = document.getElementById('life');
    const want = document.getElementById('want');
    const rec = document.getElementById('rec');
    const list = document.getElementById('list');

    function bar(label, n) {
        let d = '';
        for (let i = 1; i <= 5; i++) d += '<span class="facet-sm" style="background:' + (i <= n ? 'var(--gold)' : 'var(--line)') + '"></span>';
        return '<div class="flex items-center gap-2 text-xs"><span class="w-24 text-[color:var(--stone)]">' + label + '</span><span class="flex gap-1">' + d + '</span></div>';
    }

    function render() {
        const scores = SETTINGS.map(function (s) {
            let score = 0;
            if (life.value === 'desk') score += s.sparkle * 1.2 + s.secure * 0.5;
            if (life.value === 'active') score += s.secure * 1.2 + s.snag * 0.8 + s.sparkle * 0.4;
            if (life.value === 'manual') score += s.secure * 1.5 + s.snag * 1.2 + s.upkeep * 0.6;
            if (want.value === 'sparkle') score += s.sparkle * 1.2;
            if (want.value === 'secure') score += s.secure * 1.5;
            if (want.value === 'low') score += s.upkeep * 1.4;
            return { s: s, score: score };
        }).sort(function (a, b) { return b.score - a.score; });

        const best = scores[0].s;
        rec.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC">' +
            '<p class="text-xs uppercase tracking-wider text-[color:var(--stone)]">Suggested setting</p>' +
            '<p class="font-display text-2xl font-semibold" style="color:var(--gold)">' + best.name + '</p>' +
            '<p class="text-sm mt-1">' + best.note + '</p></div>';

        list.innerHTML = scores.map(function (o) {
            return '<div class="border border-[color:var(--line)] p-4">' +
                '<div class="flex flex-wrap gap-4 justify-between items-start">' +
                '<h3 class="font-display text-lg font-semibold">' + o.s.name + '</h3>' +
                '<div class="space-y-1">' + bar('Security', o.s.secure) + bar('Sparkle', o.s.sparkle) + bar('Snag-free', o.s.snag) + bar('Low upkeep', o.s.upkeep) + '</div></div>' +
                '<p class="text-sm text-[color:var(--stone)] mt-2">' + o.s.note + '</p></div>';
        }).join('');
    }

    life.addEventListener('change', render);
    want.addEventListener('change', render);
    render();
})();
</script>
@endpush
