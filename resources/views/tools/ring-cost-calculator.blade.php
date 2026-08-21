@extends('layouts.app')

@section('title', 'True Cost of a Ring — Financing & Ownership Calculator — Gleamion.com')
@section('meta_description', 'See what a ring really costs once financing interest, insurance, resizing and maintenance are counted — and how much saving first would save you.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">True Cost of Ownership</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">The sticker price is rarely the whole story. This adds financing interest, insurance and upkeep over the years you’ll own it.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium mb-1" for="price">Ring price</label>
                <input id="price" type="number" min="0" step="100" value="4000" class="w-full border border-[color:var(--line)] px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="down">Paid upfront</label>
                <input id="down" type="number" min="0" step="100" value="1000" class="w-full border border-[color:var(--line)] px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="apr">Financing APR (%)</label>
                <input id="apr" type="number" min="0" max="40" step="0.5" value="19.9" class="w-full border border-[color:var(--line)] px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="term">Repayment term (months)</label>
                <input id="term" type="number" min="1" max="72" step="1" value="24" class="w-full border border-[color:var(--line)] px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="years">Years you’ll own it</label>
                <input id="years" type="number" min="1" max="60" step="1" value="20" class="w-full border border-[color:var(--line)] px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="insure">Annual insurance (% of value)</label>
                <input id="insure" type="number" min="0" max="5" step="0.1" value="1.5" class="w-full border border-[color:var(--line)] px-3 py-2">
            </div>
        </div>

        <div id="results" class="grid gap-3 sm:grid-cols-3 mt-6"></div>
        <div id="verdict" class="mt-4"></div>

        <p class="text-xs text-[color:var(--stone)] mt-5 border-t border-[color:var(--line)] pt-4">
            Maintenance assumes roughly $60 a year for professional cleaning and prong checks, plus one $120 resizing and
            one $400 re-tipping or setting repair over a 20-year life. Skipping prong checks is how stones get lost —
            the cheapest line item here is the one that protects everything else.
        </p>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const ids = ['price','down','apr','term','years','insure'];
    const el = {};
    ids.forEach(function (i) { el[i] = document.getElementById(i); });
    const results = document.getElementById('results');
    const verdict = document.getElementById('verdict');

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

        const financed = Math.max(0, v.price - v.down);
        const r = v.apr / 100 / 12;
        let monthly = 0, totalPaid = financed;

        if (financed > 0 && v.term > 0) {
            monthly = r > 0
                ? financed * r / (1 - Math.pow(1 + r, -v.term))
                : financed / v.term;
            totalPaid = monthly * v.term;
        }
        const interest = totalPaid - financed;

        const insurance = v.price * (v.insure / 100) * v.years;
        const maintenance = 60 * v.years + 120 + (v.years >= 10 ? 400 : 0);
        const grand = v.price + interest + insurance + maintenance;

        results.innerHTML =
            card('Monthly payment', financed > 0 ? money(monthly) : '—', financed > 0 ? 'for ' + v.term + ' months' : 'paid in full') +
            card('Interest paid', money(interest), financed > 0 ? 'at ' + v.apr + '% APR' : 'none — well done') +
            card('Insurance', money(insurance), 'over ' + v.years + ' years') +
            card('Maintenance', money(maintenance), 'cleaning, resizing, repairs') +
            card('True total cost', money(grand), 'everything included') +
            card('Above sticker price', '+' + Math.round((grand / Math.max(1, v.price) - 1) * 100) + '%', 'vs ' + money(v.price));

        let msg;
        if (interest > v.price * 0.2) {
            msg = 'Interest is adding more than a fifth to the price of this ring. Saving for a few more months and buying a slightly smaller stone outright would leave you with more jewelry and less debt — the stone you can afford today is worth more than the one you are still paying for in two years.';
        } else if (interest > 0) {
            msg = 'Financing is adding ' + money(interest) + ' to the cost. If the retailer offers a genuine 0% promotion and you are certain you will clear it before the promotional period ends, that can be reasonable — deferred-interest deals often backdate all the interest if you miss the deadline.';
        } else {
            msg = 'Paying outright — the cheapest way to own anything. Do still budget for insurance and prong checks; a lost stone costs far more than either.';
        }
        verdict.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC"><p class="text-sm">' + msg + '</p></div>';
    }

    ids.forEach(function (i) { el[i].addEventListener('input', render); });
    render();
})();
</script>
@endpush
