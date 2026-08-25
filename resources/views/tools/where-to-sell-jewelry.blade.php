@extends('layouts.app')
@section('title', 'Where to Sell Jewelry — Compare Every Channel — Gleamion.com')
@section('meta_description', 'Compare pawn shops, gold buyers, consignment, auction houses and private sale on payout, speed and effort — with realistic percentages.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Where to Sell Jewelry</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Every channel trades payout against speed and effort. Enter what you have and see the realistic range for each.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-3">
            <div>
                <label class="block text-sm font-medium mb-1" for="value">Retail / appraised value</label>
                <input id="value" type="number" min="0" step="250" value="4000" class="w-full border border-[color:var(--line)] px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="kind">What is it?</label>
                <select id="kind" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="generic" selected>Unbranded gold / diamond piece</option>
                    <option value="designer">Signed designer piece</option>
                    <option value="antique">Antique or period piece</option>
                    <option value="scrap">Broken, scrap or odd items</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="speed">How fast do you need it?</label>
                <select id="speed" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="today">Today</option>
                    <option value="weeks" selected>A few weeks is fine</option>
                    <option value="months">Months — maximise the price</option>
                </select>
            </div>
        </div>
        <div id="channels" class="mt-6 space-y-2"></div>
        <p class="text-xs text-[color:var(--stone)] mt-5 border-t border-[color:var(--line)] pt-4">
            <strong>Always get three quotes.</strong> Offers for identical items routinely vary by 100% between buyers.
            Never let a piece out of your sight for "testing", never accept an offer made after the item has been
            dismantled, and get any mail-in offer in writing before you post anything.
        </p>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const CH = [
        { name:'Pawn shop', pct:[0.10,0.25], time:'Same day', effort:1, best:['scrap','generic'],
          note:'Fastest cash, lowest payout. They price for immediate resale and assume you may not return. Reasonable only when speed genuinely outweighs money.' },
        { name:'Gold / scrap buyer', pct:[0.15,0.35], time:'Same day', effort:1, best:['scrap'],
          note:'Pays on metal weight alone, ignoring stones and craftsmanship. Perfectly fair for broken chain and odd earrings; a poor choice for anything intact.' },
        { name:'Local jeweler (outright)', pct:[0.25,0.45], time:'1–7 days', effort:2, best:['generic','designer'],
          note:'Buys to resell in their own case, so pays more than a pawnbroker. Best when the piece suits their clientele — ask before assuming they buy at all.' },
        { name:'Consignment', pct:[0.45,0.65], time:'1–6 months', effort:2, best:['designer','antique','generic'],
          note:'They sell it for you and take 20–40%. Much better payout than outright sale, but you wait, and it may not sell at all.' },
        { name:'Auction house', pct:[0.50,0.90], time:'2–6 months', effort:3, best:['antique','designer'],
          note:'Right for genuinely rare or signed pieces. Seller commission runs 10–25%, and there is real variance — a strong room beats every other channel, a weak one disappoints.' },
        { name:'Online marketplace (private)', pct:[0.50,0.75], time:'Weeks–months', effort:5, best:['designer','generic','antique'],
          note:'Highest realistic payout for ordinary pieces, because no middleman takes a cut. You handle photography, questions, shipping, and the fraud risk — use tracked, insured, signed-for delivery only.' },
        { name:'Specialist online buyer', pct:[0.35,0.55], time:'3–10 days', effort:2, best:['designer','generic'],
          note:'Mail-in services for watches and branded jewelry. Convenient and competitive for known brands; insist on a firm quote before shipping and insure the parcel for full value.' },
    ];
    const v = document.getElementById('value'), kind = document.getElementById('kind'), speed = document.getElementById('speed');
    const box = document.getElementById('channels');
    function money(n) { return '$' + Math.max(0, Math.round(n)).toLocaleString(); }
    function render() {
        const val = parseFloat(v.value) || 0;
        const k = kind.value, sp = speed.value;
        const maxEffort = sp === 'today' ? 1 : (sp === 'weeks' ? 3 : 5);
        const scored = CH.map(function (c) {
            let s = (c.pct[0] + c.pct[1]) / 2 * 10;
            if (c.best.includes(k)) s += 3;
            if (c.effort > maxEffort) s -= 8;
            if (sp === 'today' && c.time !== 'Same day') s -= 6;
            return { c: c, s: s };
        }).sort(function (a, b) { return b.s - a.s; });
        box.innerHTML = scored.map(function (o, i) {
            const c = o.c, fit = i === 0;
            return '<div class="border p-4" style="border-color:' + (fit ? 'var(--gold)' : 'var(--line)') + ';background:' + (fit ? '#FBF7EC' : '#fff') + '">' +
                '<div class="flex flex-wrap gap-3 items-baseline justify-between">' +
                '<div><span class="font-display text-lg font-semibold">' + c.name + '</span>' +
                (fit ? '<span class="ml-2 text-xs px-2 py-0.5 border" style="border-color:var(--gold);color:var(--gold)">Best fit</span>' : '') + '</div>' +
                '<div class="font-display text-xl font-semibold" style="color:var(--gold)">' + money(val * c.pct[0]) + ' – ' + money(val * c.pct[1]) + '</div></div>' +
                '<div class="text-xs text-[color:var(--stone)] mt-1">' + c.time + ' · ' + Math.round(c.pct[0] * 100) + '–' + Math.round(c.pct[1] * 100) + '% of retail</div>' +
                '<p class="text-sm text-[color:var(--stone)] mt-2">' + c.note + '</p></div>';
        }).join('');
    }
    [v, kind, speed].forEach(function (e) { e.addEventListener('input', render); e.addEventListener('change', render); });
    render();
})();
</script>
@endpush
