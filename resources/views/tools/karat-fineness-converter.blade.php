@extends('layouts.app')
@section('title', 'Karat, Fineness & Purity Converter — Gleamion.com')
@section('meta_description', 'Convert between karat, fineness (millesimal), percentage purity and grams of pure gold in any piece.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Karat &amp; Fineness Converter</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Three ways of saying the same thing — karat, fineness and percentage. Convert between any of them.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-3">
            <div>
                <label class="block text-sm font-medium mb-1" for="from">Enter as</label>
                <select id="from" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="k" selected>Karat (e.g. 18)</option>
                    <option value="f">Fineness (e.g. 750)</option>
                    <option value="p">Percentage (e.g. 75)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="val">Value</label>
                <input id="val" type="number" min="0" step="0.1" value="18" class="w-full border border-[color:var(--line)] px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="weight">Piece weight (g, optional)</label>
                <input id="weight" type="number" min="0" step="0.1" value="10" class="w-full border border-[color:var(--line)] px-3 py-2">
            </div>
        </div>
        <div id="out" class="grid gap-3 sm:grid-cols-4 mt-6"></div>
        <div class="overflow-x-auto mt-6">
            <table class="w-full text-sm">
                <thead><tr class="text-left border-b border-[color:var(--line)]">
                    <th class="py-2 pr-4">Karat</th><th class="py-2 pr-4">Fineness</th><th class="py-2 pr-4">Purity</th><th class="py-2">Region where common</th>
                </tr></thead>
                <tbody id="rows"></tbody>
            </table>
        </div>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const T = [
        [24,999,'99.9%','Bullion worldwide; jewelry in India, China, Middle East'],
        [22,916,'91.7%','India, Middle East, South-East Asia'],
        [21,875,'87.5%','Middle East, North Africa'],
        [20,833,'83.3%','Some Asian markets'],
        [18,750,'75.0%','Europe, fine jewelry worldwide'],
        [14,585,'58.5%','United States everyday standard'],
        [12,500,'50.0%','Rare; some older pieces'],
        [10,417,'41.7%','United States budget jewelry'],
        [9,375,'37.5%','United Kingdom, Ireland, Australia'],
        [8,333,'33.3%','Germany, some European markets'],
    ];
    const from = document.getElementById('from'), val = document.getElementById('val'), weight = document.getElementById('weight');
    const out = document.getElementById('out');
    function card(l, v, s) {
        return '<div class="border border-[color:var(--line)] p-4 text-center">' +
            '<div class="text-xs uppercase tracking-wider text-[color:var(--stone)]">' + l + '</div>' +
            '<div class="font-display text-2xl font-semibold mt-1" style="color:var(--gold)">' + v + '</div>' +
            (s ? '<div class="text-xs text-[color:var(--stone)] mt-1">' + s + '</div>' : '') + '</div>';
    }
    function render() {
        const v = parseFloat(val.value) || 0;
        let purity;
        if (from.value === 'k') purity = v / 24;
        else if (from.value === 'f') purity = v / 1000;
        else purity = v / 100;
        purity = Math.max(0, Math.min(1, purity));
        const w = parseFloat(weight.value) || 0;
        out.innerHTML =
            card('Karat', (purity * 24).toFixed(1) + 'K') +
            card('Fineness', Math.round(purity * 1000)) +
            card('Purity', (purity * 100).toFixed(1) + '%') +
            card('Pure gold', w > 0 ? (w * purity).toFixed(2) + ' g' : '—', w > 0 ? 'in ' + w + ' g piece' : 'enter a weight');
    }
    [from, val, weight].forEach(function (e) { e.addEventListener('input', render); e.addEventListener('change', render); });
    render();
    document.getElementById('rows').innerHTML = T.map(function (t) {
        return '<tr class="border-b border-[color:var(--line)]"><td class="py-2 pr-4 font-medium">' + t[0] + 'K</td>' +
            '<td class="py-2 pr-4">' + t[1] + '</td><td class="py-2 pr-4">' + t[2] + '</td>' +
            '<td class="py-2 text-[color:var(--stone)]">' + t[3] + '</td></tr>';
    }).join('');
})();
</script>
@endpush
