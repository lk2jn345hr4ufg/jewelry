@extends('layouts.app')

@section('title', 'Diamond Certificate Decoder — Read a GIA Report — Gleamion.com')
@section('meta_description', 'Understand every line on a diamond grading report: cut grades, fluorescence, proportions, lab differences and the red flags to watch for.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Diamond Certificate Decoder</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Pick a term from a grading report to see what it actually means for the stone in front of you.</p>

    <div class="card p-6 mt-6">
        <label class="block text-sm font-medium mb-1" for="term">Report term</label>
        <select id="term" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white"></select>
        <div id="explain" class="mt-5"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">Which lab issued it matters</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="text-left border-b border-[color:var(--line)]">
                    <th class="py-2 pr-4">Lab</th><th class="py-2 pr-4">Strictness</th><th class="py-2">What to know</th>
                </tr></thead>
                <tbody class="text-[color:var(--stone)]">
                    <tr class="border-b border-[color:var(--line)]"><td class="py-2 pr-4 font-medium text-[color:#23262e]">GIA</td><td class="py-2 pr-4">Strictest</td><td class="py-2">The industry benchmark. Consistent and conservative — a GIA G colour is genuinely G.</td></tr>
                    <tr class="border-b border-[color:var(--line)]"><td class="py-2 pr-4 font-medium text-[color:#23262e]">AGS</td><td class="py-2 pr-4">Strictest</td><td class="py-2">Equally rigorous, with the best cut-grading science. Uses a 0–10 scale rather than words.</td></tr>
                    <tr class="border-b border-[color:var(--line)]"><td class="py-2 pr-4 font-medium text-[color:#23262e]">IGI</td><td class="py-2 pr-4">Moderate</td><td class="py-2">Widely used for lab-grown stones. Often grades one step more generously than GIA on natural stones.</td></tr>
                    <tr class="border-b border-[color:var(--line)]"><td class="py-2 pr-4 font-medium text-[color:#23262e]">EGL</td><td class="py-2 pr-4">Loose</td><td class="py-2">Frequently two to three grades softer. An "EGL H VS2" may be a GIA J SI2 — price accordingly.</td></tr>
                    <tr><td class="py-2 pr-4 font-medium text-[color:#23262e]">In-house / none</td><td class="py-2 pr-4">Meaningless</td><td class="py-2">A seller grading their own stone is not evidence. Treat as ungraded and price as such.</td></tr>
                </tbody>
            </table>
        </div>
        <p class="text-xs text-[color:var(--stone)] mt-4">
            Always check the report number on the issuing lab’s own website — every major lab has a free verification
            lookup. Reports are forged, and the online record is the only proof that matters.
        </p>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const TERMS = {
        'Cut grade': 'Rated Excellent to Poor, this measures how well the stone was proportioned to return light — the single biggest driver of sparkle. It is the one grade never worth compromising. Note that only round brilliants get an official GIA cut grade; fancy shapes do not, so you must judge those on proportions yourself.',
        'Polish': 'The smoothness of the facet surfaces. Excellent or Very Good is fine; anything lower can dull the surface subtly. Rarely a deal-breaker on its own.',
        'Symmetry': 'How precisely the facets align. Good or better is acceptable — poor symmetry can misdirect light and reduce brilliance, but small deviations are invisible.',
        'Fluorescence': 'How the stone glows under UV light. Around 30% of diamonds fluoresce. Faint or Medium is usually harmless and can even make a lower colour grade look whiter. Strong or Very Strong Blue occasionally makes a stone look hazy in sunlight — and sells at a 10–15% discount, which is a genuine bargain if the stone looks good to your eye.',
        'Table %': 'The width of the flat top facet relative to the diameter. For rounds, 54–58% is the sweet spot. Too large and the stone loses fire; too small and it loses brightness.',
        'Depth %': 'Total height relative to width. 59–62.5% is ideal for rounds. A deep stone hides weight below the girdle and looks smaller face-up than its carat suggests.',
        'Girdle': 'The rim where crown meets pavilion. Thin to Slightly Thick is ideal. Extremely Thin risks chipping; Extremely Thick wastes weight you paid for.',
        'Culet': 'The tiny facet at the very bottom point. None or Very Small is preferred — a large culet appears as a visible dark dot through the table.',
        'Clarity characteristics': 'The plotted map of inclusions. What matters is not the count but the position: an inclusion under the table is visible, one near the girdle hides under a prong. Ask for the plot, not just the grade.',
        'Measurements': 'The actual millimetre dimensions. Compare these against carat weight — a 1 ct round should measure roughly 6.4–6.5 mm. Smaller means depth is hiding weight.',
        'Laser inscription': 'A microscopic report number on the girdle. Confirms the stone matches the paperwork. Ask the jeweler to show it to you under magnification before purchase.',
        'Treatment / clarity enhanced': 'Fracture filling, laser drilling or HPHT colour treatment. All legal if disclosed, all significantly reduce value, and filled stones can be damaged by a jeweler’s torch during repairs. A treated stone should cost far less — if it does not, walk away.',
    };

    const sel = document.getElementById('term');
    const explain = document.getElementById('explain');

    Object.keys(TERMS).forEach(function (k) {
        const o = document.createElement('option');
        o.value = k; o.textContent = k;
        sel.appendChild(o);
    });

    function render() {
        explain.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC">' +
            '<p class="font-display text-xl font-semibold" style="color:var(--gold)">' + sel.value + '</p>' +
            '<p class="text-sm mt-2">' + TERMS[sel.value] + '</p></div>';
    }
    sel.addEventListener('change', render);
    render();
})();
</script>
@endpush
