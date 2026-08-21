@extends('layouts.app')

@section('title', 'Ring Size Converter — US, UK, EU & mm — Gleamion.com')
@section('meta_description', 'Convert ring sizes between US, UK, EU and Japanese scales, or find your size from inside diameter or circumference in millimetres.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Ring Size Converter</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Pick any one value — the rest update instantly.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium mb-1" for="scale">Convert from</label>
                <select id="scale" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="us">US / Canada size</option>
                    <option value="uk">UK / Australia size</option>
                    <option value="eu">EU / ISO size</option>
                    <option value="jp">Japan size</option>
                    <option value="dia">Inside diameter (mm)</option>
                    <option value="circ">Inside circumference (mm)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="val">Value</label>
                <select id="valSelect" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white"></select>
                <input id="valInput" type="number" step="0.1" class="w-full border border-[color:var(--line)] px-3 py-2 hidden" placeholder="e.g. 17.3">
            </div>
        </div>

        <div id="results" class="grid gap-3 sm:grid-cols-3 mt-6"></div>

        <p class="text-xs text-[color:var(--stone)] mt-5 border-t border-[color:var(--line)] pt-4">
            Sizes are rounded to the nearest standard value. Measure at the end of the day when fingers are largest, and
            size up slightly for wide bands (6&nbsp;mm+). For an exact fit, have a jeweler measure you with a sizing set.
        </p>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">Full conversion chart</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left border-b border-[color:var(--line)]">
                        <th class="py-2 pr-4">US</th>
                        <th class="py-2 pr-4">UK</th>
                        <th class="py-2 pr-4">EU</th>
                        <th class="py-2 pr-4">Japan</th>
                        <th class="py-2 pr-4">Diameter</th>
                        <th class="py-2">Circumference</th>
                    </tr>
                </thead>
                <tbody id="chartBody"></tbody>
            </table>
        </div>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    // US size, UK letter, EU (ISO circumference rounded), Japan, diameter mm, circumference mm
    const DATA = [
        [3,   'F',    44.2, 4,  14.1, 44.2],
        [3.5, 'G',    45.5, 5,  14.5, 45.5],
        [4,   'H',    46.8, 6,  14.9, 46.8],
        [4.5, 'I',    48.0, 8,  15.3, 48.0],
        [5,   'J½',   49.3, 9,  15.7, 49.3],
        [5.5, 'K½',   50.6, 10, 16.1, 50.6],
        [6,   'L½',   51.9, 12, 16.5, 51.9],
        [6.5, 'M½',   53.1, 13, 16.9, 53.1],
        [7,   'N½',   54.4, 14, 17.3, 54.4],
        [7.5, 'O½',   55.7, 15, 17.7, 55.7],
        [8,   'P½',   57.0, 16, 18.1, 57.0],
        [8.5, 'Q½',   58.3, 17, 18.5, 58.3],
        [9,   'R½',   59.5, 18, 19.0, 59.5],
        [9.5, 'S½',   60.8, 19, 19.4, 60.8],
        [10,  'T½',   62.1, 20, 19.8, 62.1],
        [10.5,'U½',   63.4, 22, 20.2, 63.4],
        [11,  'V½',   64.6, 23, 20.6, 64.6],
        [11.5,'W½',   65.9, 24, 21.0, 65.9],
        [12,  'X½',   67.2, 25, 21.4, 67.2],
        [12.5,'Z',    68.5, 26, 21.8, 68.5],
        [13,  'Z+1',  69.7, 27, 22.2, 69.7],
    ];
    const IDX = { us:0, uk:1, eu:2, jp:3, dia:4, circ:5 };

    const scale = document.getElementById('scale');
    const valSelect = document.getElementById('valSelect');
    const valInput = document.getElementById('valInput');
    const results = document.getElementById('results');

    function isNumericScale(s) { return s === 'dia' || s === 'circ'; }

    function fillSelect() {
        const i = IDX[scale.value];
        valSelect.innerHTML = '';
        DATA.forEach(function (row, n) {
            const o = document.createElement('option');
            o.value = n;
            o.textContent = row[i] + (i === 4 || i === 5 ? ' mm' : '');
            valSelect.appendChild(o);
        });
        valSelect.value = 8; // default US 7
    }

    function nearestByValue(colIdx, value) {
        let best = 0, diff = Infinity;
        DATA.forEach(function (row, n) {
            const d = Math.abs(row[colIdx] - value);
            if (d < diff) { diff = d; best = n; }
        });
        return best;
    }

    function card(label, value) {
        return '<div class="border border-[color:var(--line)] p-4 text-center">' +
               '<div class="text-xs uppercase tracking-wider text-[color:var(--stone)]">' + label + '</div>' +
               '<div class="font-display text-2xl font-semibold mt-1" style="color:var(--gold)">' + value + '</div>' +
               '</div>';
    }

    function render() {
        let rowIndex;
        if (isNumericScale(scale.value)) {
            const v = parseFloat(valInput.value);
            if (isNaN(v)) { results.innerHTML = '<p class="text-sm text-[color:var(--stone)] sm:col-span-3">Enter a measurement in millimetres.</p>'; return; }
            rowIndex = nearestByValue(IDX[scale.value], v);
        } else {
            rowIndex = parseInt(valSelect.value, 10);
        }
        const r = DATA[rowIndex];
        results.innerHTML =
            card('US / Canada', r[0]) +
            card('UK / Australia', r[1]) +
            card('EU / ISO', r[2]) +
            card('Japan', r[3]) +
            card('Diameter', r[4] + ' mm') +
            card('Circumference', r[5] + ' mm');
    }

    function syncInputs() {
        const numeric = isNumericScale(scale.value);
        valSelect.classList.toggle('hidden', numeric);
        valInput.classList.toggle('hidden', !numeric);
        if (!numeric) fillSelect();
        else if (valInput.value === '') valInput.value = scale.value === 'dia' ? 17.3 : 54.4;
        render();
    }

    scale.addEventListener('change', syncInputs);
    valSelect.addEventListener('change', render);
    valInput.addEventListener('input', render);

    // Full chart
    document.getElementById('chartBody').innerHTML = DATA.map(function (r) {
        return '<tr class="border-b border-[color:var(--line)]">' +
            '<td class="py-2 pr-4 font-medium">' + r[0] + '</td>' +
            '<td class="py-2 pr-4">' + r[1] + '</td>' +
            '<td class="py-2 pr-4">' + r[2] + '</td>' +
            '<td class="py-2 pr-4">' + r[3] + '</td>' +
            '<td class="py-2 pr-4">' + r[4] + ' mm</td>' +
            '<td class="py-2">' + r[5] + ' mm</td></tr>';
    }).join('');

    syncInputs();
})();
</script>
@endpush
