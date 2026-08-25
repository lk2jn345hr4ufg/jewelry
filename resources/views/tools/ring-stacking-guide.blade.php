@extends('layouts.app')
@section('title', 'Ring Stacking Guide — Build a Stack That Works — Gleamion.com')
@section('meta_description', 'How to stack rings without them wearing each other down: widths, metals, spacing and which fingers to use.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Ring Stacking Guide</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Stacking looks effortless and is not — the pieces rub each other constantly, so metal and width choices matter more than in a single ring.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-3">
            <div>
                <label class="block text-sm font-medium mb-1" for="count">Rings in the stack</label>
                <select id="count" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="2">2</option><option value="3" selected>3</option>
                    <option value="4">4</option><option value="5">5</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="look">Look</label>
                <select id="look" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="uniform">Uniform — matching set</option>
                    <option value="graduated" selected>Graduated — varied widths</option>
                    <option value="eclectic">Eclectic — mixed metals and textures</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="finger">Placement</label>
                <select id="finger" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="one" selected>All on one finger</option>
                    <option value="spread">Spread across the hand</option>
                </select>
            </div>
        </div>
        <div id="plan" class="mt-6 space-y-2"></div>
        <div id="advice" class="mt-4"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">Rules worth knowing</h2>
        <ul class="space-y-2 text-sm text-[color:var(--stone)]">
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Keep hardness similar.</strong> A platinum ring against 14K gold will wear the gold noticeably faster. Titanium against anything softer is the worst pairing.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Stacked rings fit tighter.</strong> Three bands on one finger need roughly a quarter to half size more than one alone.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Put the widest at the base.</strong> Anchoring with the heaviest ring nearest the knuckle keeps the stack stable rather than sliding.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Prong settings snag on neighbours.</strong> Bezel or flush settings stack far more comfortably and do not catch on each other.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Expect scratching.</strong> It is inherent to stacking. Budget a polish every year or two, and accept a little patina as part of the look.</span></li>
        </ul>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const count = document.getElementById('count'), look = document.getElementById('look'), finger = document.getElementById('finger');
    const plan = document.getElementById('plan'), advice = document.getElementById('advice');
    function render() {
        const n = parseInt(count.value, 10), l = look.value;
        let widths;
        if (l === 'uniform') widths = Array(n).fill(2);
        else if (l === 'graduated') { widths = []; for (let i = 0; i < n; i++) widths.push(Math.round((4 - i * (2.5 / n)) * 10) / 10); }
        else { widths = []; for (let i = 0; i < n; i++) widths.push([1.5, 3, 2, 4, 2.5][i % 5]); }
        plan.innerHTML = widths.map(function (w, i) {
            return '<div class="flex items-center gap-4 p-3 border border-[color:var(--line)]">' +
                '<div class="w-20 text-xs text-[color:var(--stone)]">' + (i === 0 ? 'Base' : (i === widths.length - 1 ? 'Top' : 'Middle')) + '</div>' +
                '<div style="height:' + (w * 4) + 'px;flex:1;background:linear-gradient(90deg,#EDE4CC,#B98B2F,#EDE4CC);border-radius:2px"></div>' +
                '<div class="w-16 text-right font-display text-lg font-semibold" style="color:var(--gold)">' + w + ' mm</div></div>';
        }).join('');
        const total = widths.reduce(function (a, b) { return a + b; }, 0);
        let msg = 'Total stack width: ' + total.toFixed(1) + ' mm. ';
        if (finger.value === 'one') {
            msg += total > 10 ? 'That is a substantial stack on one finger — it will restrict bending and may need sizing up half a size. Consider moving one ring to an adjacent finger.'
                              : 'A comfortable width for a single finger. Size up about a quarter size versus wearing one band alone.';
        } else {
            msg += 'Spread across the hand, leave at least one bare finger between groups so each stack reads separately. Keeping all pieces in one metal tone is what makes a spread look intentional.';
        }
        if (l === 'eclectic') msg += ' With mixed metals, repeat each metal at least twice across the hand so it looks deliberate rather than accidental.';
        advice.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC"><p class="text-sm">' + msg + '</p></div>';
    }
    [count, look, finger].forEach(function (e) { e.addEventListener('change', render); });
    render();
})();
</script>
@endpush
