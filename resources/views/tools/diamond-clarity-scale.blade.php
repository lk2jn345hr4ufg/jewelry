@extends('layouts.app')
@section('title', 'Diamond Clarity Scale FL–I3 — What You Can Actually See — Gleamion.com')
@section('meta_description', 'Understand every diamond clarity grade from Flawless to I3, which are eye-clean, and where paying more stops making any visible difference.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Diamond Clarity Scale</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Clarity is graded at 10× magnification — not by eye. The only question that matters in practice is whether you can see anything without a loupe.</p>

    <div class="card p-6 mt-6">
        <label class="block text-sm font-medium mb-1" for="ct">Carat weight: <span id="ctLabel" class="font-semibold" style="color:var(--gold)">1.00 ct</span></label>
        <input id="ct" type="range" min="0.3" max="4" step="0.05" value="1" class="w-full accent-[color:var(--gold)]">
        <p class="text-xs text-[color:var(--stone)]">Larger stones spread inclusions over a wider table, so the eye-clean threshold rises with size.</p>
        <div id="scale" class="mt-6 space-y-1"></div>
        <div id="advice" class="mt-4"></div>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const G = [
        ['FL','Flawless','No inclusions at 10×. Under 1% of gems. A collector’s grade — pure rarity, zero visible benefit.'],
        ['IF','Internally Flawless','No internal inclusions, minor surface marks only. Still paying for the certificate, not the look.'],
        ['VVS1','Very very slightly included','Inclusions extremely hard to see even at 10×. Invisible to the eye at any size.'],
        ['VVS2','Very very slightly included','Same in practice. A significant premium over VS with no visible difference.'],
        ['VS1','Very slightly included','Minor inclusions, difficult to spot at 10×. Reliably eye-clean at any size.'],
        ['VS2','Very slightly included','The value sweet spot — eye-clean in nearly all stones up to about 2 ct.'],
        ['SI1','Slightly included','Inclusions easy to see at 10×, usually still invisible by eye. Excellent value if you check the plot.'],
        ['SI2','Slightly included','Sometimes eye-visible, especially above 1 ct. Never buy this grade unseen.'],
        ['I1','Included','Inclusions generally visible to the naked eye, and may affect durability.'],
        ['I2-I3','Included','Obvious inclusions that dull the stone and can compromise its structure.'],
    ];
    const ct = document.getElementById('ct'), label = document.getElementById('ctLabel');
    const scale = document.getElementById('scale'), advice = document.getElementById('advice');
    function threshold(c) { return c < 0.75 ? 7 : (c < 1.5 ? 6 : (c < 2.5 ? 5 : 4)); }
    function render() {
        const c = parseFloat(ct.value);
        label.textContent = c.toFixed(2) + ' ct';
        const t = threshold(c);
        scale.innerHTML = G.map(function (g, i) {
            const eyeClean = i <= t;
            return '<div class="flex items-start gap-4 p-2 border" style="border-color:' + (i === t ? 'var(--gold)' : 'var(--line)') + '">' +
                '<div class="w-16 font-display text-lg font-semibold">' + g[0] + '</div>' +
                '<div class="flex-1"><div class="text-xs text-[color:var(--stone)]">' + g[1] + '</div>' +
                '<div class="text-sm text-[color:var(--stone)]">' + g[2] + '</div></div>' +
                '<span class="text-xs px-2 py-0.5 border whitespace-nowrap" style="border-color:' + (eyeClean ? 'var(--gold)' : '#C0563A') + ';color:' + (eyeClean ? 'var(--gold)' : '#C0563A') + '">' +
                (eyeClean ? 'Eye-clean' : 'May show') + '</span></div>';
        }).join('');
        advice.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC"><p class="text-sm">' +
            'At ' + c.toFixed(2) + ' ct, <strong>' + G[t][0] + '</strong> is the lowest grade that is reliably eye-clean. ' +
            'Anything above it costs more for something only a loupe reveals. Always ask to see the clarity plot — an inclusion under the table shows, one near the girdle hides under a prong.' +
            '</p></div>';
    }
    ct.addEventListener('input', render);
    render();
})();
</script>
@endpush
