@extends('layouts.app')

@section('title', 'Gold Purity & Value Calculator — Karat, Weight & Spot Price — Gleamion.com')
@section('meta_description', 'Calculate the pure gold content and scrap value of jewelry from its karat, weight and the current gold spot price. Supports grams, ounces and pennyweight.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Gold Purity &amp; Value Calculator</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Enter your piece’s karat and weight, plus today’s gold price, to see its pure gold content and melt value.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium mb-1" for="karat">Karat / purity</label>
                <select id="karat" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="1">24K — 99.9% pure</option>
                    <option value="0.9167">22K — 91.7%</option>
                    <option value="0.75" selected>18K — 75.0%</option>
                    <option value="0.585">14K — 58.5%</option>
                    <option value="0.417">10K — 41.7%</option>
                    <option value="0.375">9K — 37.5%</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="weight">Weight</label>
                <div class="flex gap-2">
                    <input id="weight" type="number" min="0" step="0.01" value="10"
                           class="w-full border border-[color:var(--line)] px-3 py-2">
                    <select id="unit" class="border border-[color:var(--line)] px-3 py-2 bg-white">
                        <option value="1">grams</option>
                        <option value="31.1035">troy oz</option>
                        <option value="1.55517">pennyweight</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="spot">Gold spot price (per troy ounce)</label>
                <div class="flex gap-2">
                    <select id="currency" class="border border-[color:var(--line)] px-3 py-2 bg-white">
                        <option value="$">USD $</option>
                        <option value="€">EUR €</option>
                        <option value="£">GBP £</option>
                    </select>
                    <input id="spot" type="number" min="0" step="1" value="2400"
                           class="w-full border border-[color:var(--line)] px-3 py-2">
                </div>
                <p class="text-xs text-[color:var(--stone)] mt-1">Check a live source for today’s price — this doesn’t auto-update.</p>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="payout">Dealer payout (% of melt)</label>
                <input id="payout" type="number" min="0" max="100" step="1" value="100"
                       class="w-full border border-[color:var(--line)] px-3 py-2">
                <p class="text-xs text-[color:var(--stone)] mt-1">Buyers typically pay 70–90% of melt value.</p>
            </div>
        </div>

        <div id="results" class="grid gap-3 sm:grid-cols-3 mt-6"></div>

        <p class="text-xs text-[color:var(--stone)] mt-5 border-t border-[color:var(--line)] pt-4">
            This estimates <strong>metal melt value only</strong>. It excludes gemstones, brand value, craftsmanship and
            collectibility — an antique or designer piece is often worth considerably more intact than melted. Get an
            independent appraisal before selling anything with stones or a maker’s mark.
        </p>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">Karat reference</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="text-left border-b border-[color:var(--line)]">
                    <th class="py-2 pr-4">Karat</th><th class="py-2 pr-4">Gold content</th>
                    <th class="py-2 pr-4">Common hallmark</th><th class="py-2">Typical use</th>
                </tr></thead>
                <tbody class="text-[color:var(--stone)]">
                    <tr class="border-b border-[color:var(--line)]"><td class="py-2 pr-4 font-medium text-[color:#23262e]">24K</td><td class="py-2 pr-4">99.9%</td><td class="py-2 pr-4">999</td><td class="py-2">Bullion; too soft for daily wear</td></tr>
                    <tr class="border-b border-[color:var(--line)]"><td class="py-2 pr-4 font-medium text-[color:#23262e]">22K</td><td class="py-2 pr-4">91.7%</td><td class="py-2 pr-4">916</td><td class="py-2">Traditional Indian &amp; Middle Eastern jewelry</td></tr>
                    <tr class="border-b border-[color:var(--line)]"><td class="py-2 pr-4 font-medium text-[color:#23262e]">18K</td><td class="py-2 pr-4">75.0%</td><td class="py-2 pr-4">750</td><td class="py-2">Fine jewelry — rich colour, good durability</td></tr>
                    <tr class="border-b border-[color:var(--line)]"><td class="py-2 pr-4 font-medium text-[color:#23262e]">14K</td><td class="py-2 pr-4">58.5%</td><td class="py-2 pr-4">585</td><td class="py-2">The US everyday standard; hard-wearing</td></tr>
                    <tr class="border-b border-[color:var(--line)]"><td class="py-2 pr-4 font-medium text-[color:#23262e]">10K</td><td class="py-2 pr-4">41.7%</td><td class="py-2 pr-4">417</td><td class="py-2">Budget pieces; minimum legal “gold” in the US</td></tr>
                    <tr><td class="py-2 pr-4 font-medium text-[color:#23262e]">9K</td><td class="py-2 pr-4">37.5%</td><td class="py-2 pr-4">375</td><td class="py-2">Common in the UK; not sold as gold in the US</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const ids = ['karat','weight','unit','spot','currency','payout'];
    const el = {};
    ids.forEach(function (i) { el[i] = document.getElementById(i); });
    const results = document.getElementById('results');

    function card(label, value, sub) {
        return '<div class="border border-[color:var(--line)] p-4 text-center">' +
            '<div class="text-xs uppercase tracking-wider text-[color:var(--stone)]">' + label + '</div>' +
            '<div class="font-display text-2xl font-semibold mt-1" style="color:var(--gold)">' + value + '</div>' +
            (sub ? '<div class="text-xs text-[color:var(--stone)] mt-1">' + sub + '</div>' : '') +
            '</div>';
    }

    function money(n, cur) {
        return cur + n.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function render() {
        const purity = parseFloat(el.karat.value);
        const weight = parseFloat(el.weight.value);
        const toGram = parseFloat(el.unit.value);
        const spot = parseFloat(el.spot.value);
        const cur = el.currency.value;
        const payout = parseFloat(el.payout.value);

        if ([purity, weight, toGram, spot].some(isNaN) || weight <= 0 || spot <= 0) {
            results.innerHTML = '<p class="text-sm text-[color:var(--stone)] sm:col-span-3">Enter a weight and spot price to see the value.</p>';
            return;
        }

        const grams = weight * toGram;
        const pureGrams = grams * purity;
        const pricePerGram = spot / 31.1035;
        const melt = pureGrams * pricePerGram;
        const offer = melt * (isNaN(payout) ? 100 : payout) / 100;

        results.innerHTML =
            card('Total weight', grams.toFixed(2) + ' g') +
            card('Pure gold content', pureGrams.toFixed(2) + ' g', (purity * 100).toFixed(1) + '% purity') +
            card('Gold price', money(pricePerGram, cur), 'per gram') +
            card('Melt value', money(melt, cur), '100% of gold content') +
            card('Likely payout', money(offer, cur), (isNaN(payout) ? 100 : payout) + '% of melt') +
            card('Per gram (piece)', money(melt / grams, cur), 'of the item as weighed');
    }

    ids.forEach(function (i) {
        el[i].addEventListener('input', render);
        el[i].addEventListener('change', render);
    });
    render();
})();
</script>
@endpush
