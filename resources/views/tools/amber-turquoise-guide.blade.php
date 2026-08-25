@extends('layouts.app')
@section('title', 'Amber & Turquoise Guide — Spotting Fakes — Gleamion.com')
@section('meta_description', 'How to tell real amber from plastic and copal, and natural turquoise from stabilised, reconstituted or dyed howlite.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Amber &amp; Turquoise Guide</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Two of the most faked materials in jewelry — and two where simple tests genuinely help.</p>

    <div class="card p-6 mt-6">
        <label class="block text-sm font-medium mb-1" for="mat">Material</label>
        <select id="mat" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
            <option value="amber" selected>Amber</option>
            <option value="turquoise">Turquoise</option>
        </select>
        <div id="grades" class="mt-6 space-y-2"></div>
        <div id="tests" class="mt-6"></div>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const DATA = {
        amber: {
            grades: [
                ['Natural Baltic amber','Highest','40–50 million years old, hardened tree resin. Warm to the touch, very light, and may contain genuine inclusions. Baltic amber contains succinic acid, which labs can confirm.'],
                ['Pressed / reconstituted amber','Moderate','Small fragments heated and compressed into larger pieces. Legally still amber and commonly sold as such, but worth far less than a solid natural piece. Look for a flow pattern of tiny sun-spangle discs.'],
                ['Copal','Low','Resin only thousands of years old, not millions. Softer, stickier and easily mistaken for amber — often sold with dramatic insect inclusions, which should itself raise suspicion.'],
                ['Plastic / resin imitation','None','Bakelite, polyester or acrylic. Feels heavier and colder, sinks in salt water, and any inclusions look implausibly perfect and centred.'],
            ],
            tests: [
                ['Salt water float','Dissolve two tablespoons of salt in a cup of warm water. Genuine amber floats or hovers; most plastics and glass sink. Remove the piece and rinse afterwards.'],
                ['Warmth and weight','Amber warms quickly in the hand and feels surprisingly light for its size. Glass and stone stay cold; plastic feels dense.'],
                ['Static','Rubbed briskly on wool, amber picks up small paper fragments. Copal does too, but most plastics do not.'],
                ['UV light','Baltic amber fluoresces a soft blue-white under UV. Copal shows a duller, whiter glow; most plastics show nothing.'],
                ['A warning about "hot needle" tests','Widely recommended online, but it damages the piece and copal smells similar to amber. Not worth doing on anything you intend to keep.'],
            ],
        },
        turquoise: {
            grades: [
                ['Natural, untreated','Highest','Hard enough to polish without treatment — under 5% of mined turquoise qualifies. Persian and high-grade American material commands very high prices per carat.'],
                ['Stabilised','Moderate','Impregnated with clear resin to harden porous stone. Genuinely improves durability and is the norm in commercial jewelry — accepted, provided it is disclosed.'],
                ['Colour-treated / dyed','Low','Dye added along with stabilisation to deepen or even out colour. Fades with sunlight and skin oils.'],
                ['Reconstituted','Very low','Turquoise powder mixed with resin and pressed into blocks. Uniform colour and unnaturally regular matrix give it away.'],
                ['Dyed howlite or magnesite','None','A cheap white stone dyed blue. Extremely common and often sold openly as turquoise. The dye often pools darker in the veins rather than the veins being naturally darker.'],
            ],
            tests: [
                ['Acetone on a hidden spot','A cotton bud with nail polish remover, applied somewhere inconspicuous. Dye lifts onto the swab; natural colour does not. Do this before buying, with permission, not after.'],
                ['Look at the matrix','Natural veining is irregular and sits at varying depths. Dyed howlite shows dye concentrated *in* the veins, and reconstituted material shows an implausibly even pattern.'],
                ['Price sanity check','Large, evenly coloured, cheap "turquoise" is essentially always treated or imitation. Fine natural turquoise is rarer than most people assume.'],
                ['Scratch test','Turquoise is Mohs 5–6 and will not be scratched by a fingernail, but howlite is softer at 3.5 and can be marked by a steel blade far more readily. Test only on an inconspicuous area.'],
            ],
        },
    };
    const mat = document.getElementById('mat'), grades = document.getElementById('grades'), tests = document.getElementById('tests');
    function render() {
        const d = DATA[mat.value];
        grades.innerHTML = '<h2 class="font-display text-2xl font-semibold mb-2">What is sold as ' + mat.value + '</h2>' +
            d.grades.map(function (g) {
                const c = g[1] === 'None' || g[1] === 'Very low' ? '#C0563A' : 'var(--gold)';
                return '<div class="border border-[color:var(--line)] p-4">' +
                    '<div class="flex flex-wrap items-baseline gap-3"><span class="font-display text-lg font-semibold">' + g[0] + '</span>' +
                    '<span class="text-xs px-2 py-0.5 border" style="border-color:' + c + ';color:' + c + '">' + g[1] + ' value</span></div>' +
                    '<p class="text-sm text-[color:var(--stone)] mt-1">' + g[2] + '</p></div>';
            }).join('');
        tests.innerHTML = '<h2 class="font-display text-2xl font-semibold mb-2">Tests you can do yourself</h2>' +
            d.tests.map(function (t) {
                return '<div class="border-l-4 p-4 mb-2" style="border-color:var(--gold); background:#FBF7EC">' +
                    '<p class="font-medium text-sm">' + t[0] + '</p>' +
                    '<p class="text-sm text-[color:var(--stone)] mt-1">' + t[1] + '</p></div>';
            }).join('') +
            '<p class="text-xs text-[color:var(--stone)] mt-2">Home tests give strong hints, not certainty. For anything expensive, a gemological lab is the only definitive answer — and costs far less than the mistake.</p>';
    }
    mat.addEventListener('change', render);
    render();
})();
</script>
@endpush
