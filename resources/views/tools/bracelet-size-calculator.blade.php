@extends('layouts.app')

@section('title', 'Bracelet & Bangle Size Calculator — Gleamion.com')
@section('meta_description', 'Measure your wrist and get the right bracelet length for a snug, comfortable or loose fit — plus bangle sizing, which works differently.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Bracelet &amp; Bangle Size Calculator</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Wrap a tape measure or a strip of paper around your wrist just below the wrist bone, then enter the measurement.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-3">
            <div>
                <label class="block text-sm font-medium mb-1" for="wrist">Wrist measurement</label>
                <div class="flex gap-2">
                    <input id="wrist" type="number" min="4" max="12" step="0.1" value="6.5" class="w-full border border-[color:var(--line)] px-3 py-2">
                    <select id="unit" class="border border-[color:var(--line)] px-3 py-2 bg-white">
                        <option value="in">in</option>
                        <option value="cm">cm</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="fit">Preferred fit</label>
                <select id="fit" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="0.25">Snug — barely moves</option>
                    <option value="0.75" selected>Comfort — slides a little</option>
                    <option value="1.25">Loose — drapes and moves</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="style">Style</label>
                <select id="style" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="chain">Chain / link bracelet</option>
                    <option value="tennis">Tennis bracelet</option>
                    <option value="beaded">Beaded / stretch</option>
                    <option value="cuff">Cuff</option>
                    <option value="bangle">Bangle (solid)</option>
                    <option value="watch">Watch strap</option>
                </select>
            </div>
        </div>

        <div id="results" class="grid gap-3 sm:grid-cols-3 mt-6"></div>
        <div id="advice" class="mt-4"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">Standard bracelet sizes</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="text-left border-b border-[color:var(--line)]">
                    <th class="py-2 pr-4">Size</th><th class="py-2 pr-4">Wrist</th><th class="py-2 pr-4">Bracelet length</th><th class="py-2">Typically</th>
                </tr></thead>
                <tbody class="text-[color:var(--stone)]">
                    <tr class="border-b border-[color:var(--line)]"><td class="py-2 pr-4 font-medium text-[color:#23262e]">XS</td><td class="py-2 pr-4">5.0–5.5"</td><td class="py-2 pr-4">6.0"</td><td class="py-2">Petite / teen</td></tr>
                    <tr class="border-b border-[color:var(--line)]"><td class="py-2 pr-4 font-medium text-[color:#23262e]">S</td><td class="py-2 pr-4">5.5–6.0"</td><td class="py-2 pr-4">6.5"</td><td class="py-2">Small women’s</td></tr>
                    <tr class="border-b border-[color:var(--line)]"><td class="py-2 pr-4 font-medium text-[color:#23262e]">M</td><td class="py-2 pr-4">6.0–6.5"</td><td class="py-2 pr-4">7.0"</td><td class="py-2">Average women’s</td></tr>
                    <tr class="border-b border-[color:var(--line)]"><td class="py-2 pr-4 font-medium text-[color:#23262e]">L</td><td class="py-2 pr-4">6.5–7.0"</td><td class="py-2 pr-4">7.5"</td><td class="py-2">Large women’s / small men’s</td></tr>
                    <tr class="border-b border-[color:var(--line)]"><td class="py-2 pr-4 font-medium text-[color:#23262e]">XL</td><td class="py-2 pr-4">7.0–7.75"</td><td class="py-2 pr-4">8.5"</td><td class="py-2">Average men’s</td></tr>
                    <tr><td class="py-2 pr-4 font-medium text-[color:#23262e]">XXL</td><td class="py-2 pr-4">7.75–8.5"</td><td class="py-2 pr-4">9.0"</td><td class="py-2">Large men’s</td></tr>
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
    const wrist = document.getElementById('wrist');
    const unit = document.getElementById('unit');
    const fit = document.getElementById('fit');
    const style = document.getElementById('style');
    const results = document.getElementById('results');
    const advice = document.getElementById('advice');

    const NOTES = {
        chain:  'Chain bracelets are easy to shorten — most jewelers remove links for a few dollars. Buy slightly long rather than short.',
        tennis: 'Tennis bracelets should sit snug: too loose and the line of stones rotates to the underside of the wrist. Take 0.25" off a comfort fit.',
        beaded: 'Stretch bracelets need almost no ease — measure carefully, as the elastic only stretches to get over the hand.',
        cuff:   'Cuffs are measured as the inside circumference minus the gap. Aim for roughly 1" of opening, and never bend a cuff repeatedly — the metal fatigues.',
        bangle: 'Bangles are sized by the widest part of your hand, not your wrist. Squeeze your hand as if slipping one on and measure around the knuckles.',
        watch:  'A watch should sit above the wrist bone with room for one finger. Metal bracelets can have links removed; leather straps need the right hole spacing.',
    };

    function card(label, value, sub) {
        return '<div class="border border-[color:var(--line)] p-4 text-center">' +
            '<div class="text-xs uppercase tracking-wider text-[color:var(--stone)]">' + label + '</div>' +
            '<div class="font-display text-2xl font-semibold mt-1" style="color:var(--gold)">' + value + '</div>' +
            (sub ? '<div class="text-xs text-[color:var(--stone)] mt-1">' + sub + '</div>' : '') + '</div>';
    }

    function sizeName(inches) {
        if (inches < 5.5) return 'XS';
        if (inches < 6.0) return 'S';
        if (inches < 6.5) return 'M';
        if (inches < 7.0) return 'L';
        if (inches < 7.75) return 'XL';
        return 'XXL';
    }

    function render() {
        const isCm = unit.value === 'cm';
        const w = (parseFloat(wrist.value) || 0) / (isCm ? 2.54 : 1);
        if (w <= 0) { results.innerHTML = ''; return; }

        let ease = parseFloat(fit.value);
        if (style.value === 'tennis') ease -= 0.25;
        if (style.value === 'beaded') ease = 0.2;
        if (style.value === 'bangle') ease = 0;

        const len = w + ease;

        results.innerHTML =
            card('Your wrist', w.toFixed(1) + '"', (w * 2.54).toFixed(1) + ' cm') +
            card('Bracelet length', len.toFixed(2) + '"', (len * 2.54).toFixed(1) + ' cm') +
            card('Standard size', sizeName(w), 'closest off-the-shelf');

        advice.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC">' +
            '<p class="text-sm">' + NOTES[style.value] + '</p></div>';
    }

    [wrist, unit, fit, style].forEach(function (e) {
        e.addEventListener('input', render);
        e.addEventListener('change', render);
    });
    render();
})();
</script>
@endpush
