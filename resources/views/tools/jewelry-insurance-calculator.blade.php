@extends('layouts.app')

@section('title', 'Jewelry Insurance Calculator — Cover Cost & Coverage Gaps — Gleamion.com')
@section('meta_description', 'Estimate what insuring your jewelry costs, see whether your home policy already covers it, and find the gaps that leave people uninsured.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Jewelry Insurance Calculator</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Most home policies cap jewelry far lower than people assume — often at $1,500 total, regardless of what you own.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium mb-1" for="value">Total jewelry value</label>
                <input id="value" type="number" min="0" step="500" value="8000" class="w-full border border-[color:var(--line)] px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="cap">Your home policy’s jewelry limit</label>
                <input id="cap" type="number" min="0" step="250" value="1500" class="w-full border border-[color:var(--line)] px-3 py-2">
                <p class="text-xs text-[color:var(--stone)] mt-1">Check the schedule — $1,000–2,500 is typical.</p>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="area">Where you live</label>
                <select id="area" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="1.0">Low-crime suburb or rural</option>
                    <option value="1.4" selected>Average town or city</option>
                    <option value="2.0">Major city centre</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="wear">How often worn out of the house</label>
                <select id="wear" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="0.8">Rarely — mostly kept in a safe</option>
                    <option value="1.0" selected>Regularly</option>
                    <option value="1.25">Daily, including travel</option>
                </select>
            </div>
        </div>

        <div id="results" class="grid gap-3 sm:grid-cols-3 mt-6"></div>
        <div id="gap" class="mt-4"></div>

        <p class="text-xs text-[color:var(--stone)] mt-5 border-t border-[color:var(--line)] pt-4">
            Rates are illustrative — actual premiums depend on the insurer, your claims history and whether you have a
            safe or alarm. Get real quotes before deciding. Scheduled personal property (a rider naming each piece with
            its appraisal) generally covers loss as well as theft; standard home contents cover usually does not.
        </p>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">The gaps that catch people out</h2>
        <ul class="space-y-2 text-sm text-[color:var(--stone)]">
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">“Mysterious disappearance” is usually excluded.</strong> A stone falling out of a setting, or a ring simply lost, is the most common way jewelry vanishes — and the least covered.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Appraisals go stale.</strong> A ring appraised in 2015 is insured at 2015 prices. Update every 3–5 years or you are underinsured.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Photograph everything now.</strong> Clear photos plus receipts and certificates make claims straightforward. Without them, insurers pay the minimum defensible amount.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Travel is often limited.</strong> Many policies cap or exclude items taken abroad. Check before a honeymoon.</span></li>
        </ul>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const ids = ['value','cap','area','wear'];
    const el = {};
    ids.forEach(function (i) { el[i] = document.getElementById(i); });
    const results = document.getElementById('results');
    const gap = document.getElementById('gap');

    function card(label, value, sub) {
        return '<div class="border border-[color:var(--line)] p-4 text-center">' +
            '<div class="text-xs uppercase tracking-wider text-[color:var(--stone)]">' + label + '</div>' +
            '<div class="font-display text-2xl font-semibold mt-1" style="color:var(--gold)">' + value + '</div>' +
            (sub ? '<div class="text-xs text-[color:var(--stone)] mt-1">' + sub + '</div>' : '') + '</div>';
    }
    function money(n) { return '$' + Math.max(0, Math.round(n)).toLocaleString(); }

    function render() {
        const v = parseFloat(el.value.value) || 0;
        const cap = parseFloat(el.cap.value) || 0;
        const areaMult = parseFloat(el.area.value);
        const wearMult = parseFloat(el.wear.value);

        const uncovered = Math.max(0, v - cap);
        const baseRate = 0.01;                       // ~1% of value per year
        const annual = uncovered * baseRate * areaMult * wearMult;

        results.innerHTML =
            card('Covered by home policy', money(Math.min(v, cap)), 'existing limit') +
            card('Not covered', money(uncovered), 'needs a rider') +
            card('Estimated premium', money(annual) + '/yr', money(annual / 12) + ' per month') +
            card('Over 10 years', money(annual * 10), 'cost of cover') +
            card('Rate', (annual / Math.max(1, uncovered) * 100).toFixed(2) + '%', 'of insured value') +
            card('Cost vs risk', uncovered > 0 ? money(annual) + ' to protect ' + money(uncovered) : '—', 'annual trade-off');

        gap.innerHTML = uncovered > 0
            ? '<div class="border-l-4 p-4" style="border-color:#C0563A; background:#FDF3F0"><p class="text-sm"><strong>' + money(uncovered) + ' of your jewelry is currently unprotected.</strong> A scheduled rider naming each piece typically costs 1–2% of value annually and covers loss, not just theft — the difference that matters most in practice.</p></div>'
            : '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC"><p class="text-sm">Your home policy limit covers your stated value. Still confirm whether it covers <em>loss</em> as well as theft, and whether it pays replacement cost or depreciated value.</p></div>';
    }

    ids.forEach(function (i) { el[i].addEventListener('input', render); el[i].addEventListener('change', render); });
    render();
})();
</script>
@endpush
