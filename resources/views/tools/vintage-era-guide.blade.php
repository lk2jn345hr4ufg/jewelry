@extends('layouts.app')

@section('title', 'Vintage Jewelry Era Identifier — Victorian to Retro — Gleamion.com')
@section('meta_description', 'Identify the era of an antique or vintage piece from its style, materials and construction, from Georgian through mid-century Retro.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Vintage Era Identifier</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Browse the eras, or answer two questions to narrow down what you might have.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium mb-1" for="look">Overall look</label>
                <select id="look" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="any" selected>Not sure</option>
                    <option value="ornate">Ornate, naturalistic, sentimental</option>
                    <option value="delicate">Light, lacy, garland-like</option>
                    <option value="geometric">Geometric, symmetrical, bold contrast</option>
                    <option value="chunky">Chunky, curved, oversized</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="metalq">Metal</label>
                <select id="metalq" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="any" selected>Not sure</option>
                    <option value="silvergold">Silver on gold (silver-topped)</option>
                    <option value="yellow">Yellow gold</option>
                    <option value="platinum">Platinum or white metal</option>
                    <option value="rose">Rose or pink gold</option>
                </select>
            </div>
        </div>
        <div id="eras" class="mt-6 space-y-3"></div>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const ERAS = [
        { name:'Georgian', years:'1714–1837', look:['ornate'], metal:['silvergold','yellow'],
          tells:'Entirely handmade — no two pieces identical. Closed-back settings with foil behind the stones to boost colour. Rose-cut and old mine-cut diamonds. Extremely rare; most surviving pieces are in museums.',
          value:'Very high when authentic, but reproductions are common. Never clean one — water behind a foiled stone destroys it.' },
        { name:'Victorian', years:'1837–1901', look:['ornate'], metal:['yellow','silvergold'],
          tells:'Sentimental motifs: hearts, snakes, hands, lockets and hair jewelry. Heavy yellow gold, seed pearls, garnets, jet and onyx. Mourning jewelry is a whole category. Late-period pieces show early machine work.',
          value:'Widely available and often affordable. Condition and intact enamel drive price.' },
        { name:'Art Nouveau', years:'1890–1915', look:['ornate','delicate'], metal:['yellow'],
          tells:'Flowing, asymmetric natural forms — dragonflies, irises, women with long hair. Heavy use of plique-à-jour enamel, opal, moonstone and horn. Craftsmanship valued over stone size.',
          value:'Highly collectible, especially signed pieces. The enamel is fragile and expensive to restore.' },
        { name:'Edwardian', years:'1901–1915', look:['delicate'], metal:['platinum'],
          tells:'Platinum enabled lacy, almost weightless designs: milgrain edges, filigree, garland and bow motifs. Diamonds and pearls dominate. Everything is white — yellow gold is rare.',
          value:'Consistently sought after. Check filigree for repairs; the fine metal fatigues and breaks.' },
        { name:'Art Deco', years:'1915–1935', look:['geometric'], metal:['platinum'],
          tells:'Strong geometry, symmetry and colour contrast. Calibré-cut sapphires and rubies set against diamonds. Emerald, Asscher and baguette cuts. Egyptian and Asian influences after 1922.',
          value:'The most in-demand vintage era by far. Also the most reproduced — verify construction and cut styles carefully.' },
        { name:'Retro', years:'1935–1950', look:['chunky'], metal:['rose','yellow'],
          tells:'Big, bold and curved — wartime platinum restrictions pushed makers to rose and yellow gold. Oversized cocktail rings, tank-track bracelets, large synthetic rubies and citrines.',
          value:'Often good value for the gold weight alone. Rising in popularity as Deco prices climb.' },
        { name:'Mid-century modern', years:'1950–1970', look:['chunky','geometric'], metal:['yellow','platinum'],
          tells:'Textured gold, abstract forms, starburst and atomic motifs. Cultured pearls become widespread. Signed designer pieces from this era carry strong premiums.',
          value:'Wide range — an unsigned piece may be worth scrap, a signed one many times that. Check for maker’s marks.' },
    ];

    const look = document.getElementById('look');
    const metalq = document.getElementById('metalq');
    const box = document.getElementById('eras');

    function render() {
        const l = look.value, m = metalq.value;
        const scored = ERAS.map(function (e) {
            let s = 0;
            if (l !== 'any' && e.look.includes(l)) s += 2;
            if (m !== 'any' && e.metal.includes(m)) s += 2;
            return { e: e, s: s };
        });
        const anyMatch = scored.some(function (o) { return o.s > 0; });
        scored.sort(function (a, b) { return b.s - a.s; });

        box.innerHTML = scored.map(function (o) {
            const dim = anyMatch && o.s === 0;
            return '<div class="border p-4" style="border-color:' + (o.s >= 2 ? 'var(--champagne)' : 'var(--line)') + ';opacity:' + (dim ? '0.5' : '1') + '">' +
                '<div class="flex flex-wrap items-baseline gap-3">' +
                '<h3 class="font-display text-xl font-semibold">' + o.e.name + '</h3>' +
                '<span class="text-sm text-[color:var(--stone)]">' + o.e.years + '</span>' +
                (o.s >= 4 ? '<span class="text-xs px-2 py-0.5 border" style="border-color:var(--gold);color:var(--gold)">Strong match</span>' : '') + '</div>' +
                '<p class="text-sm mt-2"><strong>Telltale signs:</strong> ' + o.e.tells + '</p>' +
                '<p class="text-sm text-[color:var(--stone)] mt-1"><strong>Value note:</strong> ' + o.e.value + '</p></div>';
        }).join('');
    }

    look.addEventListener('change', render);
    metalq.addEventListener('change', render);
    render();
})();
</script>
@endpush
