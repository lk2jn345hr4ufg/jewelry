@extends('layouts.app')

@section('title', 'Silver & Platinum Value Calculator — Gleamion.com')
@section('meta_description', 'Calculate the melt value of sterling silver, platinum and palladium jewelry or flatware from weight, purity and current spot price.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Silver &amp; Platinum Value Calculator</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">For sterling, platinum and palladium — the metals the gold calculator doesn’t cover.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium mb-1" for="metal">Metal &amp; purity</label>
                <select id="metal" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="0.999|silver">Fine silver — 999</option>
                    <option value="0.925|silver" selected>Sterling silver — 925</option>
                    <option value="0.900|silver">Coin silver — 900</option>
                    <option value="0.800|silver">Continental silver — 800</option>
                    <option value="0.950|platinum">Platinum — PT950</option>
                    <option value="0.900|platinum">Platinum — PT900</option>
                    <option value="0.950|palladium">Palladium — PD950</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="weight">Weight</label>
                <div class="flex gap-2">
                    <input id="weight" type="number" min="0" step="0.1" value="100" class="w-full border border-[color:var(--line)] px-3 py-2">
                    <select id="unit" class="border border-[color:var(--line)] px-3 py-2 bg-white">
                        <option value="1">grams</option>
                        <option value="31.1035">troy oz</option>
                        <option value="28.3495">oz</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="spot">Spot price (per troy ounce)</label>
                <div class="flex gap-2">
                    <select id="currency" class="border border-[color:var(--line)] px-3 py-2 bg-white">
                        <option value="$">USD $</option><option value="€">EUR €</option><option value="£">GBP £</option>
                    </select>
                    <input id="spot" type="number" min="0" step="0.5" value="31" class="w-full border border-[color:var(--line)] px-3 py-2">
                </div>
                <p class="text-xs text-[color:var(--stone)] mt-1" id="spotHint">Silver typically trades around $25–35.</p>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="payout">Dealer payout (%)</label>
                <input id="payout" type="number" min="0" max="100" step="1" value="75" class="w-full border border-[color:var(--line)] px-3 py-2">
                <p class="text-xs text-[color:var(--stone)] mt-1">Silver buyers often pay less than gold buyers — 60–80% is typical.</p>
            </div>
        </div>

        <div id="results" class="grid gap-3 sm:grid-cols-3 mt-6"></div>

        <p class="text-xs text-[color:var(--stone)] mt-5 border-t border-[color:var(--line)] pt-4">
            <strong>Silver is frequently worth more as an object than as metal.</strong> Antique flatware, designer pieces
            and hallmarked Georgian or Victorian silver routinely sell for several times melt. Never scrap a marked piece
            before checking what it sells for intact — melting is irreversible.
        </p>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const ids = ['metal','weight','unit','spot','currency','payout'];
    const el = {};
    ids.forEach(function (i) { el[i] = document.getElementById(i); });
    const results = document.getElementById('results');
    const hint = document.getElementById('spotHint');

    const HINTS = {
        silver:'Silver typically trades around $25–35 per troy ounce.',
        platinum:'Platinum typically trades around $900–1,100 per troy ounce.',
        palladium:'Palladium typically trades around $900–1,200 per troy ounce.',
    };

    function card(label, value, sub) {
        return '<div class="border border-[color:var(--line)] p-4 text-center">' +
            '<div class="text-xs uppercase tracking-wider text-[color:var(--stone)]">' + label + '</div>' +
            '<div class="font-display text-2xl font-semibold mt-1" style="color:var(--gold)">' + value + '</div>' +
            (sub ? '<div class="text-xs text-[color:var(--stone)] mt-1">' + sub + '</div>' : '') + '</div>';
    }
    function money(n, c) { return c + n.toLocaleString(undefined, { minimumFractionDigits:2, maximumFractionDigits:2 }); }

    function render() {
        const [purityStr, kind] = el.metal.value.split('|');
        const purity = parseFloat(purityStr);
        hint.textContent = HINTS[kind];

        const grams = (parseFloat(el.weight.value) || 0) * parseFloat(el.unit.value);
        const spot = parseFloat(el.spot.value) || 0;
        const cur = el.currency.value;
        const payout = parseFloat(el.payout.value);

        if (grams <= 0 || spot <= 0) { results.innerHTML = '<p class="text-sm text-[color:var(--stone)] sm:col-span-3">Enter a weight and spot price.</p>'; return; }

        const pure = grams * purity;
        const perGram = spot / 31.1035;
        const melt = pure * perGram;

        results.innerHTML =
            card('Total weight', grams.toFixed(1) + ' g') +
            card('Pure metal', pure.toFixed(1) + ' g', (purity * 100).toFixed(1) + '%') +
            card('Price per gram', money(perGram, cur)) +
            card('Melt value', money(melt, cur)) +
            card('Likely payout', money(melt * (isNaN(payout) ? 100 : payout) / 100, cur), (isNaN(payout) ? 100 : payout) + '% of melt') +
            card('Per troy oz', money(perGram * 31.1035 * purity, cur), 'of this alloy');
    }

    ids.forEach(function (i) { el[i].addEventListener('input', render); el[i].addEventListener('change', render); });
    render();
})();
</script>
@endpush
