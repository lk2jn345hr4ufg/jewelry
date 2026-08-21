@extends('layouts.app')

@section('title', 'Diamond Carat Size Visualizer — Actual mm by Shape — Gleamion.com')
@section('meta_description', 'See how big a diamond actually looks at any carat weight, drawn to scale, with real millimetre dimensions for every popular shape.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Carat Size Visualizer</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Carat is weight, not size — and shape changes how large that weight looks. Drag to compare.</p>

    <div class="card p-6 mt-6">
        <label class="block text-sm font-medium mb-1" for="ct">Carat weight: <span id="ctLabel" class="font-semibold" style="color:var(--gold)">1.00 ct</span></label>
        <input id="ct" type="range" min="0.25" max="4" step="0.05" value="1" class="w-full accent-[color:var(--gold)]">
        <div class="flex justify-between text-xs text-[color:var(--stone)] mb-6"><span>0.25 ct</span><span>4 ct</span></div>

        <div id="shapes" class="grid gap-4 sm:grid-cols-3 lg:grid-cols-4"></div>

        <p class="text-xs text-[color:var(--stone)] mt-5 border-t border-[color:var(--line)] pt-4">
            Shapes are drawn to scale relative to each other. Exact millimetre sizes vary with cut proportions —
            a deeply cut stone hides weight in its depth and looks smaller face-up than a well-proportioned one of the
            same carat. Elongated shapes (oval, marquise, pear) consistently look larger per carat than round or princess.
        </p>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">Getting more size for your money</h2>
        <ul class="space-y-2 text-sm text-[color:var(--stone)]">
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Choose an elongated shape.</strong> A 1 ct oval reads noticeably larger than a 1 ct round for the same weight and often costs less.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Buy just below the magic weights.</strong> 0.90–0.95 ct is visually identical to 1.00 ct and priced meaningfully lower.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">A halo setting adds 0.5–1 mm of apparent width</strong> for a fraction of the cost of a bigger centre stone.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Thin bands make stones look larger</strong> by contrast — a wide band does the opposite.</span></li>
        </ul>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    // [name, mm-length factor, mm-width factor] at 1ct; scale by cube root of carat
    const SHAPES = [
        { name:'Round',    l:6.5,  w:6.5,  r:'50%' },
        { name:'Princess', l:5.5,  w:5.5,  r:'0' },
        { name:'Oval',     l:7.7,  w:5.7,  r:'50%' },
        { name:'Emerald',  l:7.0,  w:5.0,  r:'6%' },
        { name:'Cushion',  l:5.8,  w:5.8,  r:'22%' },
        { name:'Pear',     l:8.5,  w:5.5,  r:'50% 50% 50% 50% / 60% 60% 40% 40%' },
        { name:'Marquise', l:10.0, w:5.0,  r:'50%' },
        { name:'Radiant',  l:6.5,  w:5.3,  r:'10%' },
    ];

    const ct = document.getElementById('ct');
    const ctLabel = document.getElementById('ctLabel');
    const container = document.getElementById('shapes');
    const PX_PER_MM = 7;

    function render() {
        const c = parseFloat(ct.value);
        ctLabel.textContent = c.toFixed(2) + ' ct';
        const scale = Math.cbrt(c);

        container.innerHTML = SHAPES.map(function (s) {
            const lmm = s.l * scale, wmm = s.w * scale;
            return '<div class="border border-[color:var(--line)] p-4 text-center">' +
                '<div class="flex items-center justify-center" style="height:110px">' +
                    '<div style="width:' + (wmm * PX_PER_MM) + 'px;height:' + (lmm * PX_PER_MM) + 'px;' +
                        'border-radius:' + s.r + ';' +
                        'background:linear-gradient(135deg,#EDE4CC,#B98B2F 55%,#EDE4CC);' +
                        'border:1px solid #9e7526;"></div>' +
                '</div>' +
                '<div class="font-medium text-sm mt-2">' + s.name + '</div>' +
                '<div class="text-xs text-[color:var(--stone)]">' + lmm.toFixed(1) + ' × ' + wmm.toFixed(1) + ' mm</div>' +
                '</div>';
        }).join('');
    }

    ct.addEventListener('input', render);
    render();
})();
</script>
@endpush
