@extends('layouts.app')

@section('title', 'Diamond Price Estimator — Carat, Cut, Colour & Clarity — Gleamion.com')
@section('meta_description', 'Estimate a diamond’s ballpark price from the 4Cs — carat, cut, colour and clarity — and see how each factor changes the value.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Diamond Price Estimator</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Adjust the 4Cs to see a ballpark retail range for a natural, GIA-graded round brilliant.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium mb-1" for="carat">Carat weight: <span id="caratLabel" class="font-semibold" style="color:var(--gold)">1.00 ct</span></label>
                <input id="carat" type="range" min="0.25" max="5" step="0.05" value="1" class="w-full accent-[color:var(--gold)]">
                <div class="flex justify-between text-xs text-[color:var(--stone)]"><span>0.25 ct</span><span>5 ct</span></div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="cut">Cut</label>
                <select id="cut" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="1.15">Excellent / Ideal</option>
                    <option value="1.05" selected>Very Good</option>
                    <option value="0.92">Good</option>
                    <option value="0.78">Fair</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="colour">Colour</label>
                <select id="colour" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="1.45">D — colourless</option>
                    <option value="1.35">E</option>
                    <option value="1.25">F</option>
                    <option value="1.12" selected>G — near colourless</option>
                    <option value="1.05">H</option>
                    <option value="0.95">I</option>
                    <option value="0.85">J</option>
                    <option value="0.72">K — faint warmth</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="clarity">Clarity</label>
                <select id="clarity" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="1.55">FL / IF — flawless</option>
                    <option value="1.35">VVS1</option>
                    <option value="1.25">VVS2</option>
                    <option value="1.12">VS1</option>
                    <option value="1.00" selected>VS2 — eye clean</option>
                    <option value="0.88">SI1</option>
                    <option value="0.74">SI2</option>
                    <option value="0.55">I1 — visible inclusions</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="origin">Type</label>
                <select id="origin" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="1" selected>Natural diamond</option>
                    <option value="0.18">Lab-grown</option>
                </select>
            </div>
        </div>

        <div id="results" class="grid gap-3 sm:grid-cols-3 mt-6"></div>

        <p class="text-xs text-[color:var(--stone)] mt-5 border-t border-[color:var(--line)] pt-4">
            <strong>This is a rough guide, not an appraisal.</strong> Real diamond pricing depends on the certificate,
            fluorescence, exact proportions, shape, market conditions and the seller’s margin — two stones with identical
            4Cs can differ by 30% or more. Always buy against a GIA or AGS report, and get a written appraisal for insurance.
        </p>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">What each “C” actually does</h2>
        <dl class="space-y-3 text-sm">
            <div><dt class="font-medium">Cut — the one to prioritise</dt><dd class="text-[color:var(--stone)]">Governs sparkle. A well-cut lower-colour stone outshines a poorly cut high-colour one. Never compromise here first.</dd></div>
            <div><dt class="font-medium">Carat — price jumps at round numbers</dt><dd class="text-[color:var(--stone)]">Prices rise sharply at 0.50, 1.00, 1.50 and 2.00 ct. Buying just under (0.95 ct) can save 10–20% for a visually identical stone.</dd></div>
            <div><dt class="font-medium">Colour — G–H is the sweet spot</dt><dd class="text-[color:var(--stone)]">Below J, warmth becomes visible to the naked eye. In yellow or rose gold settings, lower colours look perfectly white.</dd></div>
            <div><dt class="font-medium">Clarity — VS2 / SI1 is usually enough</dt><dd class="text-[color:var(--stone)]">“Eye clean” matters more than the grade. Paying for VVS is paying for something only a loupe reveals.</dd></div>
        </dl>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const carat = document.getElementById('carat');
    const caratLabel = document.getElementById('caratLabel');
    const ids = ['cut','colour','clarity','origin'];
    const el = {};
    ids.forEach(function (i) { el[i] = document.getElementById(i); });
    const results = document.getElementById('results');

    // Base price per carat (USD) rises non-linearly with size.
    function basePerCarat(ct) {
        if (ct < 0.5)  return 2200;
        if (ct < 0.7)  return 3200;
        if (ct < 0.9)  return 4200;
        if (ct < 1.0)  return 5200;
        if (ct < 1.5)  return 7000;
        if (ct < 2.0)  return 9500;
        if (ct < 3.0)  return 14000;
        if (ct < 4.0)  return 19000;
        return 24000;
    }

    function card(label, value, sub) {
        return '<div class="border border-[color:var(--line)] p-4 text-center">' +
            '<div class="text-xs uppercase tracking-wider text-[color:var(--stone)]">' + label + '</div>' +
            '<div class="font-display text-2xl font-semibold mt-1" style="color:var(--gold)">' + value + '</div>' +
            (sub ? '<div class="text-xs text-[color:var(--stone)] mt-1">' + sub + '</div>' : '') + '</div>';
    }

    function money(n) {
        return '$' + Math.round(n).toLocaleString();
    }

    function render() {
        const ct = parseFloat(carat.value);
        caratLabel.textContent = ct.toFixed(2) + ' ct';

        const mult = ids.reduce(function (acc, i) { return acc * parseFloat(el[i].value); }, 1);
        const mid = basePerCarat(ct) * ct * mult;
        const low = mid * 0.8;
        const high = mid * 1.25;

        results.innerHTML =
            card('Estimated range', money(low) + ' – ' + money(high), 'typical retail') +
            card('Midpoint', money(mid), 'best single guess') +
            card('Price per carat', money(mid / ct), 'at ' + ct.toFixed(2) + ' ct');
    }

    carat.addEventListener('input', render);
    ids.forEach(function (i) { el[i].addEventListener('change', render); });
    render();
})();
</script>
@endpush
