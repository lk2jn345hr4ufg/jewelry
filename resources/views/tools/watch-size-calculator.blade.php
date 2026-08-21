@extends('layouts.app')

@section('title', 'Watch Size Calculator — Case Diameter for Your Wrist — Gleamion.com')
@section('meta_description', 'Find the right watch case diameter, thickness and lug-to-lug width for your wrist size, with guidance on proportion and strap width.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Watch Size Calculator</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Measure around your wrist where you wear a watch, then see which case sizes will sit properly.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-3">
            <div>
                <label class="block text-sm font-medium mb-1" for="wrist">Wrist circumference</label>
                <div class="flex gap-2">
                    <input id="wrist" type="number" min="120" max="230" step="1" value="175" class="w-full border border-[color:var(--line)] px-3 py-2">
                    <select id="unit" class="border border-[color:var(--line)] px-3 py-2 bg-white">
                        <option value="mm">mm</option>
                        <option value="in">in</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="style">Watch style</label>
                <select id="style" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="dress">Dress watch</option>
                    <option value="everyday" selected>Everyday / field</option>
                    <option value="diver">Diver / sports</option>
                    <option value="chrono">Chronograph</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="fitpref">Look you want</label>
                <select id="fitpref" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="-2">Understated</option>
                    <option value="0" selected>Balanced</option>
                    <option value="2">Bold / oversized</option>
                </select>
            </div>
        </div>

        <div id="results" class="grid gap-3 sm:grid-cols-3 mt-6"></div>
        <div id="note" class="mt-4"></div>

        <p class="text-xs text-[color:var(--stone)] mt-5 border-t border-[color:var(--line)] pt-4">
            <strong>Lug-to-lug matters more than diameter.</strong> A watch overhangs and sits awkwardly if the lug-to-lug
            distance exceeds the flat width of your wrist, regardless of how modest the case diameter sounds.
        </p>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">Rules of thumb</h2>
        <ul class="space-y-2 text-sm text-[color:var(--stone)]">
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Strap width ≈ half the case diameter.</strong> A 40 mm case usually takes a 20 mm strap.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Thickness under 12 mm slides under a cuff.</strong> Dive watches at 14 mm+ will not.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Dress watches run small on purpose</strong> — 36–39 mm is traditional and looks intentional, not undersized.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Trends move; proportion does not.</strong> Oversized cases dated quickly — a well-proportioned watch always looks right.</span></li>
        </ul>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const wrist = document.getElementById('wrist');
    const unit = document.getElementById('unit');
    const style = document.getElementById('style');
    const fitpref = document.getElementById('fitpref');
    const results = document.getElementById('results');
    const note = document.getElementById('note');

    const STYLE_ADJ = { dress:-2, everyday:0, diver:2, chrono:1 };
    const STYLE_THICK = { dress:9, everyday:11, diver:14, chrono:13 };

    function card(label, value, sub) {
        return '<div class="border border-[color:var(--line)] p-4 text-center">' +
            '<div class="text-xs uppercase tracking-wider text-[color:var(--stone)]">' + label + '</div>' +
            '<div class="font-display text-2xl font-semibold mt-1" style="color:var(--gold)">' + value + '</div>' +
            (sub ? '<div class="text-xs text-[color:var(--stone)] mt-1">' + sub + '</div>' : '') + '</div>';
    }

    function render() {
        let mm = parseFloat(wrist.value) || 0;
        if (unit.value === 'in') mm = mm * 25.4;
        if (mm <= 0) { results.innerHTML = ''; return; }

        // Base case size scales with wrist circumference.
        const base = 26 + (mm - 140) * 0.18;
        const size = base + STYLE_ADJ[style.value] + parseFloat(fitpref.value);
        const low = Math.round(size - 2), high = Math.round(size + 2);
        const lugMax = Math.round(mm * 0.29);
        const strap = Math.round(size / 2);
        const thick = STYLE_THICK[style.value];

        results.innerHTML =
            card('Your wrist', Math.round(mm) + ' mm', (mm / 25.4).toFixed(2) + '"') +
            card('Case diameter', low + '–' + high + ' mm', 'ideal range') +
            card('Max lug-to-lug', lugMax + ' mm', 'beyond this it overhangs') +
            card('Strap width', strap + ' mm', 'proportional') +
            card('Typical thickness', '≤ ' + thick + ' mm', style.options[style.selectedIndex].text.toLowerCase()) +
            card('Avoid above', (high + 4) + ' mm', 'will look oversized');

        let msg;
        if (mm < 155) msg = 'You have a slim wrist — smaller cases will look intentional and elegant. Vintage watches (34–38 mm) suit you particularly well, and modern “small” releases are increasingly available.';
        else if (mm < 180) msg = 'An average wrist, which is the easiest to shop for — most watches are designed around this size and will sit correctly.';
        else msg = 'A larger wrist carries bigger cases comfortably, but do not assume bigger is automatically better — a 40 mm watch with the right lug span still looks excellent.';

        note.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC"><p class="text-sm">' + msg + '</p></div>';
    }

    [wrist, unit, style, fitpref].forEach(function (e) {
        e.addEventListener('input', render);
        e.addEventListener('change', render);
    });
    render();
})();
</script>
@endpush
