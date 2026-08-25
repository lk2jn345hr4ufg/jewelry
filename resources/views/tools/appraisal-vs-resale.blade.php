@extends('layouts.app')

@section('title', 'Appraisal vs Resale Value — What Your Jewelry Is Really Worth — Gleamion.com')
@section('meta_description', 'Why your appraisal says $8,000 and buyers offer $1,500. Understand replacement, fair market and liquidation value, and which applies to you.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Appraisal vs Resale Value</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">The most common disappointment in jewelry: an appraisal is not what anyone will pay you. Here is roughly what to expect.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium mb-1" for="paid">What it’s appraised at (or cost new)</label>
                <input id="paid" type="number" min="0" step="250" value="6000" class="w-full border border-[color:var(--line)] px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="type">Type of piece</label>
                <select id="type" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="massmarket" selected>Mass-market retail (chain store)</option>
                    <option value="independent">Independent jeweler / custom</option>
                    <option value="designer">Signed designer (Cartier, Tiffany, VCA)</option>
                    <option value="antique">Antique or period piece</option>
                    <option value="labgrown">Lab-grown centre stone</option>
                    <option value="goldonly">Plain gold, no stones</option>
                </select>
            </div>
        </div>

        <div id="results" class="grid gap-3 sm:grid-cols-3 mt-6"></div>
        <div id="note" class="mt-4"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">The three values, and when each applies</h2>
        <dl class="space-y-3 text-sm">
            <div><dt class="font-medium">Replacement value — for insurance</dt><dd class="text-[color:var(--stone)]">What it would cost to buy an equivalent piece at full retail today. Deliberately the highest figure, because the insurer must replace it. This is what your appraisal document states, and it is not a selling price.</dd></div>
            <div><dt class="font-medium">Fair market value — for estates and tax</dt><dd class="text-[color:var(--stone)]">What a willing buyer would pay a willing seller, neither under pressure. Typically 30–50% of replacement value for ordinary retail jewelry.</dd></div>
            <div><dt class="font-medium">Liquidation value — what you get today</dt><dd class="text-[color:var(--stone)]">What a dealer pays when they buy for resale. They carry inventory risk and need margin, so expect 20–40% of replacement for most pieces — and close to melt value for anything without brand or stone value.</dd></div>
        </dl>
        <div class="border-l-4 p-4 mt-4" style="border-color:var(--gold); background:#FBF7EC">
            <p class="text-sm"><strong>Why the gap is so wide.</strong> Retail jewelry carries a substantial markup covering showroom, staff and inventory. That margin evaporates the moment the piece leaves the store — the same way a new car does. Jewelry is rarely an investment; buy it because you want to wear it.</p>
        </div>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const TYPES = {
        massmarket: { fmv:[0.30,0.45], liq:[0.15,0.30], note:'Mass-market retail carries the largest markup, so it loses the most on resale. Selling privately (to another individual) typically doubles what a dealer will offer, at the cost of time and effort.' },
        independent:{ fmv:[0.35,0.50], liq:[0.20,0.35], note:'Custom and independent work often uses better stones for the money, which holds up somewhat better — though the labour and design value rarely transfer to a second owner.' },
        designer:   { fmv:[0.50,0.75], liq:[0.35,0.60], note:'Signed pieces from the major houses retain value far better, and some models appreciate. Keep the box, papers and receipt — they can add 20% or more to what a buyer will pay.' },
        antique:    { fmv:[0.60,1.10], liq:[0.40,0.80], note:'Period pieces are valued as objects rather than materials, and genuinely good examples can exceed their appraisal. Never break up or melt an antique before getting a specialist opinion.' },
        labgrown:   { fmv:[0.10,0.25], liq:[0.03,0.15], note:'Lab-grown stones have almost no secondary market — production costs keep falling, so a stone bought today competes with cheaper new ones tomorrow. Buy lab-grown for the wearing, never for the resale.' },
        goldonly:   { fmv:[0.55,0.75], liq:[0.50,0.70], note:'Plain gold is the closest jewelry gets to a liquid asset — it tracks the metal price and every buyer knows what it is worth. Scrap value sets a firm floor.' },
    };

    const paid = document.getElementById('paid');
    const type = document.getElementById('type');
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
        const v = parseFloat(paid.value) || 0;
        const t = TYPES[type.value];

        results.innerHTML =
            card('Insurance value', money(v), 'what the appraisal says') +
            card('Private sale', money(v * t.fmv[0]) + '–' + money(v * t.fmv[1]), 'selling it yourself') +
            card('Dealer offer', money(v * t.liq[0]) + '–' + money(v * t.liq[1]), 'immediate cash');

        note.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC">' +
            '<p class="text-sm">' + t.note + '</p></div>';
    }

    paid.addEventListener('input', render);
    type.addEventListener('change', render);
    render();
})();
</script>
@endpush
