@extends('layouts.app')
@section('title', 'Necklace Layering Calculator — Spacing That Works — Gleamion.com')
@section('meta_description', 'Work out exactly which chain lengths to buy for a layered necklace look that hangs cleanly without tangling.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Necklace Layering Calculator</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Most failed layering comes down to spacing that is too tight. Get the gaps right and it works itself.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-3">
            <div>
                <label class="block text-sm font-medium mb-1" for="base">Shortest chain (inches)</label>
                <input id="base" type="number" min="12" max="24" step="0.5" value="16" class="w-full border border-[color:var(--line)] px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="count">How many layers?</label>
                <select id="count" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="2">2</option><option value="3" selected>3</option>
                    <option value="4">4</option><option value="5">5</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="style">Look</label>
                <select id="style" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="2">Tight and stacked</option>
                    <option value="3" selected>Balanced</option>
                    <option value="4">Dramatic and spread</option>
                </select>
            </div>
        </div>
        <div id="layers" class="mt-6 space-y-2"></div>
        <div id="tips" class="mt-4"></div>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const base = document.getElementById('base'), count = document.getElementById('count'), style = document.getElementById('style');
    const layers = document.getElementById('layers'), tips = document.getElementById('tips');
    function render() {
        const b = parseFloat(base.value) || 16;
        const n = parseInt(count.value, 10);
        const gap = parseFloat(style.value);
        let html = '';
        for (let i = 0; i < n; i++) {
            const len = b + gap * i;
            const width = 30 + i * 14;
            html += '<div class="flex items-center gap-4 p-3 border border-[color:var(--line)]">' +
                '<div class="w-16 font-display text-xl font-semibold" style="color:var(--gold)">' + len + '"</div>' +
                '<div class="flex-1"><div style="height:3px;width:' + width + '%;background:var(--champagne)"></div></div>' +
                '<div class="text-xs text-[color:var(--stone)] whitespace-nowrap">' + Math.round(len * 2.54) + ' cm · layer ' + (i + 1) + '</div></div>';
        }
        layers.innerHTML = html;
        const total = b + gap * (n - 1);
        tips.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC"><p class="text-sm">' +
            'Your set spans ' + b + '" to ' + total + '". ' +
            (gap < 3 ? 'Tight spacing keeps the pieces reading as one unit — use chains of clearly different weights or textures so they do not merge visually. '
                     : 'Wider spacing lets each piece stand alone — good for mixing a pendant with plain chains. ') +
            'Vary chain thickness rather than repeating the same style, put any pendant on the longest chain, and use a layering clasp if they tangle.' +
            '</p></div>';
    }
    [base, count, style].forEach(function (e) { e.addEventListener('input', render); e.addEventListener('change', render); });
    render();
})();
</script>
@endpush
