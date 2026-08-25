@extends('layouts.app')
@section('title', 'Pendant Size Guide — Proportion, Chain Weight & Bail — Gleamion.com')
@section('meta_description', 'Choose a pendant that suits your frame and chain: size guidance in millimetres, chain thickness matching and bail compatibility.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Pendant Size Guide</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Most pendant mistakes are proportion mistakes — a chain too fine to carry the weight, or a pendant too small to read against the frame.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-3">
            <div>
                <label class="block text-sm font-medium mb-1" for="frame">Build</label>
                <select id="frame" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="petite">Petite</option>
                    <option value="average" selected>Average</option>
                    <option value="broad">Broader / taller</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="style">Effect wanted</label>
                <select id="style" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="subtle">Subtle, everyday</option>
                    <option value="balanced" selected>Balanced</option>
                    <option value="statement">Statement</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="chain">Chain length (inches)</label>
                <input id="chain" type="number" min="14" max="36" step="1" value="18" class="w-full border border-[color:var(--line)] px-3 py-2">
            </div>
        </div>
        <div id="out" class="grid gap-3 sm:grid-cols-3 mt-6"></div>
        <div id="note" class="mt-4"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">Matching pendant to chain</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="text-left border-b border-[color:var(--line)]">
                    <th class="py-2 pr-4">Pendant weight</th><th class="py-2 pr-4">Minimum chain</th><th class="py-2">Why</th>
                </tr></thead>
                <tbody class="text-[color:var(--stone)]">
                    <tr class="border-b border-[color:var(--line)]"><td class="py-2 pr-4 font-medium text-[color:#23262e]">Under 2 g</td><td class="py-2 pr-4">0.8–1.0 mm</td><td class="py-2">Fine cable or box chain is fine for a small charm.</td></tr>
                    <tr class="border-b border-[color:var(--line)]"><td class="py-2 pr-4 font-medium text-[color:#23262e]">2–5 g</td><td class="py-2 pr-4">1.0–1.5 mm</td><td class="py-2">The usual range for a stone pendant or small locket.</td></tr>
                    <tr class="border-b border-[color:var(--line)]"><td class="py-2 pr-4 font-medium text-[color:#23262e]">5–10 g</td><td class="py-2 pr-4">1.5–2.5 mm</td><td class="py-2">A fine chain here will stretch and eventually snap at the bail.</td></tr>
                    <tr><td class="py-2 pr-4 font-medium text-[color:#23262e]">10 g+</td><td class="py-2 pr-4">2.5 mm+</td><td class="py-2">Heavy pendants need a substantial chain, both for strength and for visual balance.</td></tr>
                </tbody>
            </table>
        </div>
        <p class="text-xs text-[color:var(--stone)] mt-4">
            Check the <strong>bail</strong> — the loop the chain passes through — before buying either half separately.
            A thick chain will not pass through a narrow bail, and this is the most common reason a pendant and chain
            bought apart do not work together.
        </p>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const F = { petite:[10,18], average:[14,25], broad:[18,32] };
    const S = { subtle:0.7, balanced:1, statement:1.45 };
    const frame = document.getElementById('frame'), style = document.getElementById('style'), chain = document.getElementById('chain');
    const out = document.getElementById('out'), note = document.getElementById('note');
    function card(l, v, s) {
        return '<div class="border border-[color:var(--line)] p-4 text-center">' +
            '<div class="text-xs uppercase tracking-wider text-[color:var(--stone)]">' + l + '</div>' +
            '<div class="font-display text-2xl font-semibold mt-1" style="color:var(--gold)">' + v + '</div>' +
            (s ? '<div class="text-xs text-[color:var(--stone)] mt-1">' + s + '</div>' : '') + '</div>';
    }
    function render() {
        const r = F[frame.value], m = S[style.value];
        const low = Math.round(r[0] * m), high = Math.round(r[1] * m);
        const c = parseFloat(chain.value) || 18;
        const drop = (c / 2 + 1).toFixed(1);
        out.innerHTML =
            card('Pendant height', low + '–' + high + ' mm', 'including the bail') +
            card('Sits at roughly', drop + '"', 'below the collarbone') +
            card('Chain thickness', m > 1.2 ? '1.5–2.5 mm' : (m < 0.8 ? '0.8–1.2 mm' : '1.0–1.8 mm'), 'proportional');
        note.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC"><p class="text-sm">' +
            (m > 1.2 ? 'A statement pendant carries best on a shorter chain (16–18") where it sits high on the chest rather than disappearing into a neckline. Make sure the chain can physically bear the weight.'
             : m < 0.8 ? 'Small pendants read best at 16–18". Any longer and a delicate piece stops registering visually at conversational distance.'
             : 'At ' + c + '", this size will sit ' + drop + '" down and read clearly without dominating. The most versatile combination.') +
            '</p></div>';
    }
    [frame, style, chain].forEach(function (e) { e.addEventListener('input', render); e.addEventListener('change', render); });
    render();
})();
</script>
@endpush
