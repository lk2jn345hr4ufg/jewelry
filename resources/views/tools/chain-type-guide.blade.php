@extends('layouts.app')

@section('title', 'Chain Type Guide — Cable, Curb, Figaro, Rope & More — Gleamion.com')
@section('meta_description', 'Compare every common chain style on strength, repairability and what each suits, so you buy one that lasts.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Chain Type Guide</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Filter by what you need — chain choice is mostly about strength and repairability, not looks.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium mb-1" for="use">What is it for?</label>
                <select id="use" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="any" selected>Show everything</option>
                    <option value="pendant">Holding a pendant</option>
                    <option value="alone">Worn on its own</option>
                    <option value="daily">Never taken off</option>
                    <option value="layer">Layering</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="strength">Minimum strength</label>
                <select id="strength" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="1" selected>Any</option>
                    <option value="3">Reasonably strong</option>
                    <option value="4">Very strong</option>
                </select>
            </div>
        </div>
        <div id="chains" class="mt-6 space-y-3"></div>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const CHAINS = [
        { name:'Cable', strength:3, repair:5, uses:['pendant','layer','alone'], note:'Simple oval links, the most common chain made. Easy for any jeweler to repair and inexpensive. The default choice for a pendant.' },
        { name:'Curb', strength:4, repair:5, uses:['pendant','alone','daily'], note:'Twisted flattened links that lie flat against the skin. Strong, classic and highly repairable — an excellent everyday chain.' },
        { name:'Figaro', strength:4, repair:5, uses:['alone','daily'], note:'Alternating long and short curb links. Strong and distinctive; a staple in men’s jewelry.' },
        { name:'Rope', strength:5, repair:3, uses:['alone','pendant','daily'], note:'Twisted strands that catch light beautifully and resist kinking. Among the strongest styles, though a break mid-chain is harder to mend invisibly.' },
        { name:'Box', strength:4, repair:4, uses:['pendant','daily'], note:'Square links forming a smooth continuous line. Holds pendants squarely and resists twisting, but can kink permanently if bent sharply.' },
        { name:'Wheat (Spiga)', strength:5, repair:3, uses:['pendant','alone','daily'], note:'Four twisted strands woven together. Very strong and elegant, with a subtle texture. Costlier per gram due to the labour.' },
        { name:'Snake', strength:2, repair:1, uses:['alone'], note:'Smooth, fluid and beautiful — but the weakest common style. A single kink is usually permanent and often unrepairable. Avoid for daily wear.' },
        { name:'Singapore', strength:3, repair:3, uses:['alone','layer'], note:'A twisted curb variant that sparkles as it moves. Fine for regular wear but tangles more than most.' },
        { name:'Herringbone', strength:1, repair:1, uses:['alone'], note:'Flat, liquid-looking and striking, but notoriously fragile — it kinks permanently and rarely survives repair. Wear it carefully, occasionally.' },
        { name:'Ball (bead)', strength:2, repair:2, uses:['alone','layer'], note:'Beads on a wire. Casual and inexpensive; the connecting wire fatigues over time.' },
        { name:'Mariner (anchor)', strength:4, repair:4, uses:['alone','daily'], note:'Links with a bar across the centre, from nautical chain. Strong, masculine and durable.' },
        { name:'Cuban link', strength:5, repair:5, uses:['alone','daily'], note:'Thick interlocking links, tightly woven. Extremely strong and heavy — the weight is the point, and the price.' },
    ];

    const use = document.getElementById('use');
    const strength = document.getElementById('strength');
    const box = document.getElementById('chains');

    function bar(label, n) {
        let d = '';
        for (let i = 1; i <= 5; i++) d += '<span class="facet-sm" style="background:' + (i <= n ? 'var(--gold)' : 'var(--line)') + '"></span>';
        return '<div class="flex items-center gap-2 text-xs"><span class="w-20 text-[color:var(--stone)]">' + label + '</span><span class="flex gap-1">' + d + '</span></div>';
    }

    function render() {
        const u = use.value, minS = parseInt(strength.value, 10);
        const list = CHAINS.filter(function (c) {
            return c.strength >= minS && (u === 'any' || c.uses.includes(u));
        }).sort(function (a, b) { return b.strength - a.strength; });

        box.innerHTML = list.length ? list.map(function (c) {
            return '<div class="border border-[color:var(--line)] p-4">' +
                '<div class="flex flex-wrap gap-4 items-start justify-between">' +
                '<h3 class="font-display text-xl font-semibold">' + c.name + '</h3>' +
                '<div class="space-y-1">' + bar('Strength', c.strength) + bar('Repairable', c.repair) + '</div></div>' +
                '<p class="text-sm text-[color:var(--stone)] mt-2">' + c.note + '</p></div>';
        }).join('') : '<p class="text-sm text-[color:var(--stone)]">No chain styles match those filters.</p>';
    }

    use.addEventListener('change', render);
    strength.addEventListener('change', render);
    render();
})();
</script>
@endpush
