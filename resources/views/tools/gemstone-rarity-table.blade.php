@extends('layouts.app')
@section('title', 'Gemstone Rarity & Price Table — Gleamion.com')
@section('meta_description', 'How rare each gemstone actually is, with typical price ranges per carat and which stones are undervalued relative to their scarcity.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Gemstone Rarity &amp; Price</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Rarity and price are not the same thing. Diamond is common; its price comes from marketing and supply control. Several genuinely rarer stones cost far less.</p>

    <div class="card p-6 mt-6">
        <label class="block text-sm font-medium mb-1" for="sort">Sort by</label>
        <select id="sort" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white max-w-xs">
            <option value="rarity" selected>Rarity</option>
            <option value="price">Price per carat</option>
            <option value="value">Best value for rarity</option>
        </select>
        <div class="overflow-x-auto mt-6">
            <table class="w-full text-sm">
                <thead><tr class="text-left border-b border-[color:var(--line)]">
                    <th class="py-2 pr-4">Stone</th><th class="py-2 pr-4">Rarity</th>
                    <th class="py-2 pr-4">Typical $/carat</th><th class="py-2">Note</th>
                </tr></thead>
                <tbody id="rows"></tbody>
            </table>
        </div>
        <p class="text-xs text-[color:var(--stone)] mt-4 border-t border-[color:var(--line)] pt-4">
            Prices are broad indicative ranges for good commercial quality at around 1 carat, and vary enormously with
            colour, clarity, origin and treatment. Exceptional examples of any stone exceed these figures substantially.
        </p>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    // name, rarity 1-10, low $, high $, note
    const G = [
        ['Painite',10,50000,60000,'Once the rarest mineral on earth — only a handful of crystals were known before 2005.'],
        ['Musgravite',10,35000,45000,'Fewer than a hundred documented gem-quality stones exist.'],
        ['Alexandrite',9,8000,30000,'Genuine colour-change alexandrite is rarer than fine diamond and priced accordingly.'],
        ['Jadeite (imperial)',9,10000,30000,'Top imperial green jade reaches millions per piece at auction.'],
        ['Padparadscha sapphire',9,3000,15000,'The rare pink-orange sapphire. Widely imitated with treated stones.'],
        ['Ruby (Burmese, untreated)',9,10000,50000,'Fine untreated Burmese ruby exceeds diamond per carat comfortably.'],
        ['Tanzanite',8,300,900,'Found in one place on earth — about 8 sq km in Tanzania — yet priced modestly. Genuinely undervalued for its scarcity.'],
        ['Emerald (Colombian)',8,2000,15000,'Fine untreated Colombian material is rare; oiled commercial stones are common.'],
        ['Spinel (red/pink)',7,500,3000,'Historically confused with ruby, rarer than ruby, and a fraction of the price. The connoisseur’s value pick.'],
        ['Demantoid garnet',8,1000,5000,'A brilliant green garnet with more fire than diamond. Little known outside the trade.'],
        ['Sapphire (blue, fine)',6,800,4000,'Widely available in commercial grades; fine untreated Kashmir material is extraordinarily rare.'],
        ['Opal (black)',7,1000,10000,'Lightning Ridge black opal is genuinely scarce and rising in price.'],
        ['Diamond (colourless)',3,3000,12000,'Far more common than its price suggests. Supply management and a century of marketing sustain the market.'],
        ['Aquamarine',4,150,800,'Attractive, durable and abundant. Excellent value for a large clean stone.'],
        ['Tourmaline (Paraíba)',9,5000,30000,'The neon blue-green Paraíba variety is one of the great modern rarities.'],
        ['Tourmaline (other)',3,50,500,'Common in most colours and very affordable for the durability offered.'],
        ['Amethyst',2,10,50,'Once reserved for royalty; large Brazilian deposits made it inexpensive. Beauty unchanged.'],
        ['Citrine',2,10,40,'Abundant, and most is heat-treated amethyst. Large clean stones cost very little.'],
        ['Garnet (common)',2,20,100,'Widely available, durable and underrated.'],
        ['Peridot',3,50,300,'Moderately available. Soft, so best in earrings and pendants.'],
        ['Moissanite (lab)',1,300,600,'Manufactured, so rarity is irrelevant — priced on production cost.'],
        ['Diamond (lab-grown)',1,300,1500,'Prices continue to fall as production scales. No scarcity value whatsoever.'],
    ];
    const sort = document.getElementById('sort'), rows = document.getElementById('rows');
    function render() {
        const list = G.slice();
        if (sort.value === 'rarity') list.sort(function (a, b) { return b[1] - a[1]; });
        if (sort.value === 'price') list.sort(function (a, b) { return b[3] - a[3]; });
        if (sort.value === 'value') list.sort(function (a, b) { return (b[1] / Math.log10(b[3] + 10)) - (a[1] / Math.log10(a[3] + 10)); });
        rows.innerHTML = list.map(function (g) {
            let d = '';
            for (let i = 1; i <= 10; i += 2) d += '<span class="facet-sm" style="background:' + (i <= g[1] ? 'var(--gold)' : 'var(--line)') + '"></span>';
            return '<tr class="border-b border-[color:var(--line)]">' +
                '<td class="py-2 pr-4 font-medium">' + g[0] + '</td>' +
                '<td class="py-2 pr-4"><span class="flex gap-1">' + d + '</span></td>' +
                '<td class="py-2 pr-4 whitespace-nowrap">$' + g[2].toLocaleString() + '–' + g[3].toLocaleString() + '</td>' +
                '<td class="py-2 text-[color:var(--stone)]">' + g[4] + '</td></tr>';
        }).join('');
    }
    sort.addEventListener('change', render);
    render();
})();
</script>
@endpush
