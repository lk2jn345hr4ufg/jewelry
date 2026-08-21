@extends('layouts.app')

@section('title', 'Engagement Ring Budget Calculator — Gleamion.com')
@section('meta_description', 'Work out a sensible engagement ring budget from your actual finances — savings, income and timeline — not outdated marketing rules.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Engagement Ring Budget Calculator</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Based on what you can actually afford — not the “three months’ salary” rule, which was invented by a diamond advertising campaign in the 1930s.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium mb-1" for="income">Monthly take-home pay</label>
                <input id="income" type="number" min="0" step="100" value="4000" class="w-full border border-[color:var(--line)] px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="expenses">Monthly essential expenses</label>
                <input id="expenses" type="number" min="0" step="100" value="2600" class="w-full border border-[color:var(--line)] px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="savings">Savings available now</label>
                <input id="savings" type="number" min="0" step="100" value="3000" class="w-full border border-[color:var(--line)] px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="months">Months until you want to propose</label>
                <input id="months" type="number" min="0" max="60" step="1" value="6" class="w-full border border-[color:var(--line)] px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="keep">Emergency fund to keep untouched</label>
                <input id="keep" type="number" min="0" step="100" value="2000" class="w-full border border-[color:var(--line)] px-3 py-2">
                <p class="text-xs text-[color:var(--stone)] mt-1">Never spend this on a ring.</p>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="other">Other upcoming costs (wedding, moving)</label>
                <input id="other" type="number" min="0" step="100" value="0" class="w-full border border-[color:var(--line)] px-3 py-2">
            </div>
        </div>

        <div id="results" class="grid gap-3 sm:grid-cols-3 mt-6"></div>
        <div id="note" class="mt-4"></div>

        <p class="text-xs text-[color:var(--stone)] mt-5 border-t border-[color:var(--line)] pt-4">
            This assumes you save cash rather than borrow. Starting a marriage with ring debt is a poor trade — a smaller
            ring bought outright beats a larger one financed at 20% APR. If a jeweler pushes financing, treat that as a
            reason to shop elsewhere.
        </p>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">Where the money actually goes</h2>
        <ul class="space-y-2 text-sm text-[color:var(--stone)]">
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">The centre stone is 60–80% of the price.</strong> Everything else — setting, metal, sizing — is comparatively minor.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Lab-grown stones cost 60–85% less</strong> for a physically identical diamond. The trade-off is resale value, which is poor for both.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Buy just under the round carat marks.</strong> A 0.92 ct looks identical to a 1.00 ct and can cost 15% less.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Budget 1–2% a year for insurance,</strong> plus around $80–150 for resizing and periodic prong checks.</span></li>
        </ul>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const ids = ['income','expenses','savings','months','keep','other'];
    const el = {};
    ids.forEach(function (i) { el[i] = document.getElementById(i); });
    const results = document.getElementById('results');
    const note = document.getElementById('note');

    function card(label, value, sub) {
        return '<div class="border border-[color:var(--line)] p-4 text-center">' +
            '<div class="text-xs uppercase tracking-wider text-[color:var(--stone)]">' + label + '</div>' +
            '<div class="font-display text-2xl font-semibold mt-1" style="color:var(--gold)">' + value + '</div>' +
            (sub ? '<div class="text-xs text-[color:var(--stone)] mt-1">' + sub + '</div>' : '') + '</div>';
    }
    function money(n) { return '$' + Math.max(0, Math.round(n)).toLocaleString(); }

    function render() {
        const v = {};
        ids.forEach(function (i) { v[i] = parseFloat(el[i].value) || 0; });

        const surplus = Math.max(0, v.income - v.expenses);
        const saveRate = surplus * 0.4;                       // 40% of surplus toward the ring
        const fromSavings = Math.max(0, v.savings - v.keep - v.other);
        const total = fromSavings + (saveRate * v.months);

        results.innerHTML =
            card('Monthly surplus', money(surplus), 'income minus essentials') +
            card('Save toward ring', money(saveRate) + '/mo', '40% of surplus') +
            card('Available from savings', money(fromSavings), 'after emergency fund') +
            card('Comfortable budget', money(total), 'in ' + v.months + ' months') +
            card('Stretch budget', money(total * 1.25), 'if you tighten up') +
            card('Minimum sensible', money(total * 0.6), 'plenty of good options here');

        let msg, tone;
        if (total < 800) {
            msg = 'A modest budget buys a genuinely lovely ring — consider a smaller natural stone, a coloured gemstone like sapphire, or a vintage piece. Many couples upgrade for a later anniversary.';
        } else if (total < 3000) {
            msg = 'This is a comfortable, common range. A well-cut 0.5–0.9 ct natural stone, or a larger lab-grown one, sits nicely here with money left for the wedding.';
        } else if (total < 8000) {
            msg = 'You have real choice. Prioritise cut quality over carat weight — a superbly cut 1 ct outshines a mediocre 1.5 ct every time.';
        } else {
            msg = 'A generous budget. Consider a certified stone from a reputable dealer, and put the surplus toward a setting you both love rather than chasing the highest clarity grade.';
        }
        note.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC"><p class="text-sm">' + msg + '</p></div>';
    }

    ids.forEach(function (i) { el[i].addEventListener('input', render); });
    render();
})();
</script>
@endpush
