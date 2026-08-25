@extends('layouts.app')
@section('title', 'Custom Jewelry Design Cost Estimator — Gleamion.com')
@section('meta_description', 'Estimate what a bespoke ring or pendant costs to design and make, how long it takes, and where custom is cheaper than retail.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Custom Design Cost Estimator</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Custom is often cheaper than a comparable retail piece, because you are not paying for showroom inventory. It always takes longer.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium mb-1" for="piece">Piece</label>
                <select id="piece" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="ring" selected>Ring</option>
                    <option value="pendant">Pendant</option>
                    <option value="earrings">Earrings (pair)</option>
                    <option value="bracelet">Bracelet</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="complexity">Complexity</label>
                <select id="complexity" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="1">Simple — plain or single stone</option>
                    <option value="1.7" selected>Moderate — some detail, side stones</option>
                    <option value="2.8">Complex — pavé, engraving, unusual form</option>
                    <option value="4.5">Highly complex — hand-fabricated, articulated</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="metal">Metal</label>
                <select id="metal" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="1">Silver</option>
                    <option value="2.2" selected>14K gold</option>
                    <option value="2.8">18K gold</option>
                    <option value="4">Platinum</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="stones">Stone budget (you supply or buy separately)</label>
                <input id="stones" type="number" min="0" step="250" value="2000" class="w-full border border-[color:var(--line)] px-3 py-2">
            </div>
        </div>
        <div id="out" class="grid gap-3 sm:grid-cols-3 mt-6"></div>
        <div id="timeline" class="mt-4"></div>
        <p class="text-xs text-[color:var(--stone)] mt-5 border-t border-[color:var(--line)] pt-4">
            Expect to pay a design deposit (often $100–300, sometimes credited against the final piece) before any CAD
            work begins. Get the quote, timeline and revision policy in writing — "two rounds of CAD revisions included"
            is a normal and reasonable term.
        </p>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">How the process runs</h2>
        <ol class="space-y-2 text-sm text-[color:var(--stone)] list-decimal pl-5">
            <li><strong class="text-[color:#23262e]">Consultation.</strong> Bring images of what you like and what you do not. Being specific about dislikes saves more time than anything else.</li>
            <li><strong class="text-[color:#23262e]">CAD rendering.</strong> A 3D model to approve. Ask to see it from the side — most people only check the top view and are surprised by the height.</li>
            <li><strong class="text-[color:#23262e]">Wax or resin model.</strong> Some jewelers will print one so you can try the shape on your hand. Worth asking for.</li>
            <li><strong class="text-[color:#23262e]">Casting and finishing.</strong> The metal is cast, cleaned and polished.</li>
            <li><strong class="text-[color:#23262e]">Stone setting.</strong> Done last, by hand. This is where craftsmanship shows most.</li>
            <li><strong class="text-[color:#23262e]">Final approval.</strong> Inspect under magnification before paying the balance — check prong evenness and that stones sit level.</li>
        </ol>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const BASE = { ring:450, pendant:350, earrings:550, bracelet:700 };
    const WEEKS = { 1:[3,5], 1.7:[4,7], 2.8:[6,10], 4.5:[8,16] };
    const ids = ['piece','complexity','metal','stones'];
    const el = {}; ids.forEach(function (i) { el[i] = document.getElementById(i); });
    const out = document.getElementById('out'), timeline = document.getElementById('timeline');
    function card(l, v, s) {
        return '<div class="border border-[color:var(--line)] p-4 text-center">' +
            '<div class="text-xs uppercase tracking-wider text-[color:var(--stone)]">' + l + '</div>' +
            '<div class="font-display text-2xl font-semibold mt-1" style="color:var(--gold)">' + v + '</div>' +
            (s ? '<div class="text-xs text-[color:var(--stone)] mt-1">' + s + '</div>' : '') + '</div>';
    }
    function money(n) { return '$' + Math.max(0, Math.round(n)).toLocaleString(); }
    function render() {
        const base = BASE[el.piece.value];
        const cx = parseFloat(el.complexity.value);
        const mt = parseFloat(el.metal.value);
        const stones = parseFloat(el.stones.value) || 0;
        const labour = base * cx;
        const metalCost = base * 0.5 * mt;
        const setting = stones > 0 ? Math.max(60, stones * 0.04) : 0;
        const total = labour + metalCost + setting + stones;
        const w = WEEKS[cx];
        out.innerHTML =
            card('Design & labour', money(labour)) +
            card('Metal', money(metalCost)) +
            card('Stone setting', money(setting), 'excluding the stones') +
            card('Your stones', money(stones)) +
            card('Estimated total', money(total)) +
            card('Retail equivalent', money(total * 1.4) + '–' + money(total * 1.8), 'what a shop would charge');
        timeline.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC">' +
            '<p class="text-sm"><strong>Expect ' + w[0] + '–' + w[1] + ' weeks</strong> from first consultation to finished piece, ' +
            'longer around wedding season and December. If you have a date to hit, add a month of margin — rushing a custom piece is how mistakes happen.</p></div>';
    }
    ids.forEach(function (i) { el[i].addEventListener('input', render); el[i].addEventListener('change', render); });
    render();
})();
</script>
@endpush
