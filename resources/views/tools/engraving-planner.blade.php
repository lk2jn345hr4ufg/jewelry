@extends('layouts.app')

@section('title', 'Ring Engraving Planner — Character Limits & Ideas — Gleamion.com')
@section('meta_description', 'Plan an engraving that actually fits. Check character limits by ring size and band width, preview fonts and browse inscription ideas.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Engraving Planner</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Most people write something too long and have to cut it at the counter. Check it fits first.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-3">
            <div>
                <label class="block text-sm font-medium mb-1" for="size">Ring size (US)</label>
                <select id="size" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white"></select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="width">Band width</label>
                <select id="width" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="1">2 mm — very narrow</option>
                    <option value="1.15" selected>3–4 mm — typical</option>
                    <option value="1.35">5–6 mm — wide</option>
                    <option value="1.6">7 mm+ — very wide</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="method">Method</label>
                <select id="method" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="1.25">Machine — small, neat</option>
                    <option value="1" selected>Laser — fine detail</option>
                    <option value="0.75">Hand engraved — larger</option>
                </select>
            </div>
        </div>

        <label class="block text-sm font-medium mb-1 mt-5" for="text">Your inscription</label>
        <input id="text" type="text" value="Always, J &amp; M — 12.06.26" class="w-full border border-[color:var(--line)] px-3 py-2">

        <div id="meter" class="mt-4"></div>
        <div id="preview" class="mt-5"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">Inscription ideas that age well</h2>
        <div id="ideas" class="grid gap-2 sm:grid-cols-2"></div>
        <p class="text-xs text-[color:var(--stone)] mt-4 border-t border-[color:var(--line)] pt-4">
            Engraving is usually permanent — removing it means sanding metal away and re-polishing, which thins the band.
            Check spelling twice at the counter, and remember that inside engraving cannot be done on full eternity or
            tension-set rings.
        </p>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const sizeSel = document.getElementById('size');
    for (let s = 3; s <= 14; s += 0.5) {
        const o = document.createElement('option');
        o.value = s; o.textContent = 'Size ' + s;
        if (s === 7) o.selected = true;
        sizeSel.appendChild(o);
    }

    const width = document.getElementById('width');
    const method = document.getElementById('method');
    const text = document.getElementById('text');
    const meter = document.getElementById('meter');
    const preview = document.getElementById('preview');

    function capacity() {
        const size = parseFloat(sizeSel.value);
        // Usable inner circumference grows with size; roughly 2.2 chars per US size at typical settings
        const base = 14 + (size - 3) * 1.6;
        return Math.floor(base * parseFloat(width.value) * parseFloat(method.value));
    }

    function render() {
        const max = capacity();
        const len = text.value.length;
        const pct = Math.min(100, (len / max) * 100);
        const over = len > max;

        meter.innerHTML =
            '<div class="flex justify-between text-sm mb-1">' +
                '<span>' + len + ' of about ' + max + ' characters</span>' +
                '<span style="color:' + (over ? '#C0563A' : 'var(--gold)') + '">' + (over ? 'Too long — trim ' + (len - max) + ' characters' : 'Fits') + '</span>' +
            '</div>' +
            '<div style="height:8px;background:var(--line)"><div style="height:8px;width:' + pct + '%;background:' + (over ? '#C0563A' : 'var(--gold)') + '"></div></div>';

        const fonts = [
            ['Script', "'Cormorant Garamond', serif", 'italic'],
            ['Serif', "'Cormorant Garamond', serif", 'normal'],
            ['Block', "'Jost', sans-serif", 'normal'],
        ];
        preview.innerHTML = '<p class="text-xs uppercase tracking-wider text-[color:var(--stone)] mb-2">Preview</p>' +
            fonts.map(function (f) {
                return '<div class="border border-[color:var(--line)] p-4 mb-2 text-center" style="background:linear-gradient(#FBF9F4,#F1EADC)">' +
                    '<div class="text-xs text-[color:var(--stone)] mb-1">' + f[0] + '</div>' +
                    '<div style="font-family:' + f[1] + ';font-style:' + f[2] + ';font-size:1.35rem;color:#6b5a2e">' +
                    (text.value || '&nbsp;') + '</div></div>';
            }).join('');
    }

    const IDEAS = [
        ['Dates', 'The proposal date, wedding date, or the day you met — in a format you will still parse in thirty years.'],
        ['Coordinates', 'The latitude and longitude of somewhere that matters. Compact and quietly personal.'],
        ['Initials + date', 'The most common choice for a reason: it survives changing tastes.'],
        ['A private phrase', 'Something only the two of you understand. Ages better than a quotation.'],
        ['Infinity or symbols', '∞, ♥ or a small motif — useful when the band is too narrow for words.'],
        ['Both names', 'Simple and unambiguous. Works on wider bands where there is room.'],
    ];

    document.getElementById('ideas').innerHTML = IDEAS.map(function (i) {
        return '<div class="border border-[color:var(--line)] p-3">' +
            '<div class="font-medium text-sm">' + i[0] + '</div>' +
            '<div class="text-xs text-[color:var(--stone)] mt-1">' + i[1] + '</div></div>';
    }).join('');

    [sizeSel, width, method, text].forEach(function (e) {
        e.addEventListener('input', render);
        e.addEventListener('change', render);
    });
    render();
})();
</script>
@endpush
