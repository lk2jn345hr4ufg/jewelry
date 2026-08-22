@extends('layouts.app')

@section('title', 'Hallmark & Purity Mark Decoder — Gleamion.com')
@section('meta_description', 'Decode the stamps inside your jewelry: 925, 750, 14K, PLAT and more. Find out what metal you have and how pure it is.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Hallmark Decoder</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Type the stamp you can see inside the band or on the clasp — usually tiny, so use a loupe or your phone camera zoomed in.</p>

    <div class="card p-6 mt-6">
        <label class="block text-sm font-medium mb-1" for="mark">Hallmark or stamp</label>
        <input id="mark" type="text" placeholder="e.g. 925, 750, 14K, PLAT, GF" class="w-full border border-[color:var(--line)] px-3 py-2 font-mono">
        <div id="result" class="mt-5"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">Common marks at a glance</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="text-left border-b border-[color:var(--line)]">
                    <th class="py-2 pr-4">Mark</th><th class="py-2 pr-4">Metal</th><th class="py-2 pr-4">Purity</th><th class="py-2">Notes</th>
                </tr></thead>
                <tbody id="allMarks"></tbody>
            </table>
        </div>
        <p class="text-xs text-[color:var(--stone)] mt-4">
            An absent hallmark does not always mean fake — older and handmade pieces often carry none. Equally, a stamp
            can be forged. If value matters, a jeweler’s acid or XRF test settles it in minutes and usually costs nothing.
        </p>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const MARKS = [
        ['999','Fine silver','99.9%','Very soft; usually bullion or fine chain.'],
        ['958','Britannia silver','95.8%','Higher grade than sterling; British standard.'],
        ['925','Sterling silver','92.5%','The global standard for silver jewelry.'],
        ['900','Coin silver','90%','Older American pieces, often pre-1900.'],
        ['800','Continental silver','80%','Common in European antique pieces.'],
        ['999.9','Fine gold (24K)','99.99%','Bullion grade; too soft to wear.'],
        ['24K','Pure gold','99.9%','Bullion and traditional Asian jewelry.'],
        ['22K','Gold','91.7%','Also stamped 916. Indian and Middle Eastern jewelry.'],
        ['18K','Gold','75%','Also stamped 750. Fine jewelry standard.'],
        ['14K','Gold','58.5%','Also stamped 585. The US everyday standard.'],
        ['10K','Gold','41.7%','Also stamped 417. Minimum legal “gold” in the US.'],
        ['9K','Gold','37.5%','Also stamped 375. Common in the UK; not gold in the US.'],
        ['750','Gold','75%','European mark for 18K.'],
        ['585','Gold','58.5%','European mark for 14K.'],
        ['417','Gold','41.7%','European mark for 10K.'],
        ['375','Gold','37.5%','European mark for 9K.'],
        ['916','Gold','91.7%','European mark for 22K.'],
        ['PLAT','Platinum','95%','Also stamped PT950 or 950.'],
        ['PT950','Platinum','95%','The usual platinum jewelry alloy.'],
        ['PT900','Platinum','90%','Slightly lower grade, often older.'],
        ['PD950','Palladium','95%','Hypoallergenic, lighter than platinum.'],
        ['GF','Gold filled','5% by weight','A thick bonded layer — far more durable than plating, but not solid gold.'],
        ['1/20 12K GF','Gold filled','5% of 12K','The fraction states the weight of gold in the whole piece.'],
        ['GP','Gold plated','Microns','A thin electroplated layer that wears through with use.'],
        ['HGE','Heavy gold electroplate','Microns','Thicker plating, still not solid.'],
        ['VERMEIL','Gold over sterling','2.5+ microns','Sterling base with heavy gold plate. Better than plated base metal.'],
        ['GE','Gold electroplate','Microns','Thin plating.'],
        ['STERLING','Sterling silver','92.5%','Word form of 925.'],
        ['TITANIUM','Titanium','Commercially pure','Hypoallergenic, cannot be resized.'],
        ['STAINLESS','Stainless steel','Alloy','Durable, inexpensive, hypoallergenic if 316L.'],
        ['CZ','Cubic zirconia','—','Marks the stone, not the metal — a simulant, not a diamond.'],
        ['MOI','Moissanite','—','Marks the stone. A durable diamond alternative.'],
        ['LG','Lab-grown','—','Indicates a laboratory-created diamond or gem.'],
    ];

    const input = document.getElementById('mark');
    const result = document.getElementById('result');

    function show(list, query) {
        if (!query) { result.innerHTML = ''; return; }
        if (!list.length) {
            result.innerHTML = '<div class="border border-[color:var(--line)] p-4"><p class="text-sm text-[color:var(--stone)]">' +
                'No match for “' + query + '”. Marks are often worn or partially struck — try just the numbers, ' +
                'or check the clasp and the inside of the band. Maker’s marks (initials or symbols) are separate from purity marks.</p></div>';
            return;
        }
        result.innerHTML = list.map(function (m) {
            return '<div class="border-l-4 p-4 mb-3" style="border-color:var(--gold); background:#FBF7EC">' +
                '<div class="flex flex-wrap items-baseline gap-3">' +
                '<span class="font-mono text-lg font-semibold">' + m[0] + '</span>' +
                '<span class="font-display text-xl font-semibold" style="color:var(--gold)">' + m[1] + '</span>' +
                '<span class="text-sm text-[color:var(--stone)]">' + m[2] + '</span></div>' +
                '<p class="text-sm mt-1">' + m[3] + '</p></div>';
        }).join('');
    }

    input.addEventListener('input', function () {
        const q = input.value.trim().toUpperCase().replace(/\s+/g, '');
        const list = MARKS.filter(function (m) {
            return m[0].replace(/\s+/g, '').indexOf(q) === 0 || m[0].replace(/\s+/g, '') === q;
        });
        show(list, q);
    });

    document.getElementById('allMarks').innerHTML = MARKS.map(function (m) {
        return '<tr class="border-b border-[color:var(--line)]">' +
            '<td class="py-2 pr-4 font-mono font-medium">' + m[0] + '</td>' +
            '<td class="py-2 pr-4">' + m[1] + '</td>' +
            '<td class="py-2 pr-4">' + m[2] + '</td>' +
            '<td class="py-2 text-[color:var(--stone)]">' + m[3] + '</td></tr>';
    }).join('');
})();
</script>
@endpush
