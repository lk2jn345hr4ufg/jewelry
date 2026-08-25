@extends('layouts.app')
@section('title', 'Body Jewelry Gauge Converter — Gauge to mm — Gleamion.com')
@section('meta_description', 'Convert body jewelry gauges to millimetres and inches, with standard starting gauges for every piercing and safe stretching guidance.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Body Jewelry Gauge Converter</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Gauge runs backwards — the higher the number, the thinner the jewelry.</p>

    <div class="card p-6 mt-6">
        <label class="block text-sm font-medium mb-1" for="piercing">Show the standard gauge for…</label>
        <select id="piercing" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white"></select>
        <div id="std" class="mt-5"></div>
        <div class="overflow-x-auto mt-6">
            <table class="w-full text-sm">
                <thead><tr class="text-left border-b border-[color:var(--line)]">
                    <th class="py-2 pr-4">Gauge</th><th class="py-2 pr-4">Millimetres</th><th class="py-2 pr-4">Inches</th><th class="py-2">Typically used for</th>
                </tr></thead>
                <tbody id="rows"></tbody>
            </table>
        </div>
        <p class="text-xs text-[color:var(--stone)] mt-4 border-t border-[color:var(--line)] pt-4">
            <strong>Stretch one size at a time and wait 6–8 weeks between.</strong> Skipping sizes or forcing it causes
            tears, scar tissue and blowouts, which are permanent. Beyond roughly 0g, lobes often will not close again if
            you change your mind.
        </p>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const G = [
        ['20g',0.81,'Nostril, some earlobe studs'],
        ['18g',1.0,'Earlobe, nostril, cartilage'],
        ['16g',1.2,'Helix, tragus, conch, eyebrow, navel (some)'],
        ['14g',1.6,'Navel, septum, tongue, industrial, nipple'],
        ['12g',2.0,'Stretched lobes, some septum'],
        ['10g',2.4,'Stretched lobes'],
        ['8g',3.2,'Stretched lobes'],
        ['6g',4.0,'Stretched lobes'],
        ['4g',5.0,'Stretched lobes'],
        ['2g',6.0,'Stretched lobes'],
        ['0g',8.0,'Stretched lobes — often the point of no return'],
        ['00g',10.0,'Stretched lobes'],
        ['7/16"',11.0,'Large stretched lobes'],
        ['1/2"',12.7,'Large stretched lobes'],
    ];
    const STD = {
        'Earlobe':'18g or 20g','Helix':'16g','Tragus':'16g','Conch':'16g or 14g','Daith':'16g',
        'Rook':'16g','Industrial':'14g','Nostril':'18g or 20g','Septum':'16g or 14g','Navel':'14g','Eyebrow':'16g','Tongue':'14g',
    };
    const sel = document.getElementById('piercing'), std = document.getElementById('std');
    Object.keys(STD).forEach(function (k) { const o = document.createElement('option'); o.value = k; o.textContent = k; sel.appendChild(o); });
    function render() {
        std.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC">' +
            '<p class="text-sm"><strong>' + sel.value + '</strong> is usually pierced at <strong style="color:var(--gold)">' + STD[sel.value] + '</strong>. ' +
            'Buying jewelry a size thinner than your piercing lets the hole shrink; thicker will not fit without stretching.</p></div>';
    }
    sel.addEventListener('change', render); render();
    document.getElementById('rows').innerHTML = G.map(function (g) {
        return '<tr class="border-b border-[color:var(--line)]">' +
            '<td class="py-2 pr-4 font-medium">' + g[0] + '</td>' +
            '<td class="py-2 pr-4">' + g[1].toFixed(2) + ' mm</td>' +
            '<td class="py-2 pr-4">' + (g[1] / 25.4).toFixed(3) + '"</td>' +
            '<td class="py-2 text-[color:var(--stone)]">' + g[2] + '</td></tr>';
    }).join('');
})();
</script>
@endpush
