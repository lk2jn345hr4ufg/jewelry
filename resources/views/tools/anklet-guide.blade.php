@extends('layouts.app')
@section('title', 'Anklet Size & Style Guide — Gleamion.com')
@section('meta_description', 'Measure for an anklet, choose a chain style that survives daily wear, and know which metals will not survive the beach.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Anklet Size &amp; Style Guide</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Anklets take more abuse than any other jewelry — shoes, sand, sun cream and constant movement. Material choice matters more than usual.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-3">
            <div>
                <label class="block text-sm font-medium mb-1" for="ankle">Ankle measurement</label>
                <div class="flex gap-2">
                    <input id="ankle" type="number" min="6" max="14" step="0.25" value="9" class="w-full border border-[color:var(--line)] px-3 py-2">
                    <select id="unit" class="border border-[color:var(--line)] px-3 py-2 bg-white">
                        <option value="in">in</option><option value="cm">cm</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="fit">Fit</label>
                <select id="fit" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="0.4">Snug — sits above the bone</option>
                    <option value="0.9" selected>Comfortable — moves a little</option>
                    <option value="1.5">Loose — drapes on the foot</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="use">Where worn</label>
                <select id="use" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="beach" selected>Beach and holidays</option>
                    <option value="daily">Daily, under trousers and shoes</option>
                    <option value="occasion">Occasionally, with sandals</option>
                </select>
            </div>
        </div>
        <div id="out" class="grid gap-3 sm:grid-cols-3 mt-6"></div>
        <div id="advice" class="mt-4"></div>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const USE = {
        beach:{ metal:'Solid gold, titanium or surgical steel', chain:'Curb, rope or figaro — nothing that kinks',
                note:'Salt water and chlorine destroy plated jewelry within a single holiday and tarnish silver rapidly. Sun cream leaves a film that dulls everything. If you want silver, accept that it will need regular polishing; solid gold and titanium simply do not care.' },
        daily:{ metal:'Solid gold or steel', chain:'Flat curb or box — sits close to the skin',
                note:'Worn under socks and trousers, an anklet gets constant friction. Avoid anything with dangling charms, which catch and snap. A flat chain profile is far more comfortable inside a shoe.' },
        occasion:{ metal:'Anything, including silver and plated', chain:'Any style — including delicate and charm anklets',
                   note:'Occasional wear means you can choose freely. Store it flat rather than coiled, and wipe it after each wear before putting it away.' },
    };
    const ankle = document.getElementById('ankle'), unit = document.getElementById('unit'), fit = document.getElementById('fit'), use = document.getElementById('use');
    const out = document.getElementById('out'), advice = document.getElementById('advice');
    function card(l, v, s) {
        return '<div class="border border-[color:var(--line)] p-4 text-center">' +
            '<div class="text-xs uppercase tracking-wider text-[color:var(--stone)]">' + l + '</div>' +
            '<div class="font-display text-2xl font-semibold mt-1" style="color:var(--gold)">' + v + '</div>' +
            (s ? '<div class="text-xs text-[color:var(--stone)] mt-1">' + s + '</div>' : '') + '</div>';
    }
    function render() {
        const a = (parseFloat(ankle.value) || 9) / (unit.value === 'cm' ? 2.54 : 1);
        const len = a + parseFloat(fit.value);
        const u = USE[use.value];
        out.innerHTML =
            card('Your ankle', a.toFixed(1) + '"', (a * 2.54).toFixed(1) + ' cm') +
            card('Anklet length', len.toFixed(1) + '"', (len * 2.54).toFixed(1) + ' cm') +
            card('Standard size', len <= 9 ? '9"' : (len <= 10 ? '10"' : '11"'), 'closest stock size');
        advice.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC">' +
            '<p class="text-sm"><strong>Metal:</strong> ' + u.metal + '. <strong>Chain:</strong> ' + u.chain + '.</p>' +
            '<p class="text-sm mt-2">' + u.note + '</p>' +
            '<p class="text-sm text-[color:var(--stone)] mt-2">Buy an adjustable style with extender chain if you can — ankles swell noticeably in heat and after long days on your feet.</p></div>';
    }
    [ankle, unit, fit, use].forEach(function (e) { e.addEventListener('input', render); e.addEventListener('change', render); });
    render();
})();
</script>
@endpush
