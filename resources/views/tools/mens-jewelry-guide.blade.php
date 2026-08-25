@extends('layouts.app')
@section('title', 'Men’s Jewelry Guide — Rings, Chains, Cufflinks & Watches — Gleamion.com')
@section('meta_description', 'What suits, what lasts and what to spend on men’s jewelry: ring widths, chain weights, cufflink types and how to wear more than one piece.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Men’s Jewelry Guide</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Choose by build and daily routine — proportion is what separates deliberate from accidental.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-3">
            <div>
                <label class="block text-sm font-medium mb-1" for="build">Build</label>
                <select id="build" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="slim">Slim / smaller frame</option>
                    <option value="avg" selected>Average</option>
                    <option value="large">Larger frame</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="work">Work environment</label>
                <select id="work" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="formal">Formal / corporate</option>
                    <option value="casual" selected>Casual office</option>
                    <option value="manual">Manual or hands-on</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="item">Item</label>
                <select id="item" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="ring" selected>Ring</option>
                    <option value="chain">Chain</option>
                    <option value="bracelet">Bracelet</option>
                    <option value="cufflinks">Cufflinks</option>
                </select>
            </div>
        </div>
        <div id="out" class="mt-6"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">Wearing more than one piece</h2>
        <ul class="space-y-2 text-sm text-[color:var(--stone)]">
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Keep metals consistent.</strong> Watch, ring and chain in the same tone reads deliberate; mixed tones read accidental unless done confidently.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Three pieces is a sensible ceiling</strong> for most settings — watch, one ring, one chain.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Match weight to build.</strong> A 10 mm Cuban chain overwhelms a slim frame; a 2 mm chain disappears on a large one.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Buy fewer, better.</strong> One solid 14K chain outlasts five plated ones and looks it from the first day.</span></li>
        </ul>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const build = document.getElementById('build'), work = document.getElementById('work'), item = document.getElementById('item');
    const out = document.getElementById('out');
    const W = { slim:0, avg:1, large:2 };
    const DATA = {
        ring: {
            spec: [['4–6 mm width','6–7 mm width','7–9 mm width']],
            work: { formal:'Keep it to a plain band or a discreet signet — polished, low profile, nothing that catches on a cuff.',
                    casual:'You have latitude for texture, brushed finishes or a modest stone. Comfort-fit interiors are worth the small premium.',
                    manual:'Choose a flush or bezel setting, or a plain band. Avoid tungsten if you work near machinery — it cannot be cut off in an emergency, unlike gold or silver.' },
            note:'Men’s rings run wider than women’s, and a wider band fits tighter — size up a quarter to half size versus a narrow one.'
        },
        chain: {
            spec: [['2–3 mm','3–5 mm','5–8 mm']],
            work: { formal:'Wear it under the shirt. A fine cable or box chain at 20–22" sits below the collar without showing.',
                    casual:'Visible is fine. Curb, Figaro and rope all wear well; 20–24" is the usual range.',
                    manual:'Consider not wearing a chain at all around machinery. If you do, keep it short and tucked in.' },
            note:'Solid beats hollow every time — hollow chains dent and cannot usually be repaired. Ask specifically; the weight tells you.'
        },
        bracelet: {
            spec: [['6–8 mm','8–11 mm','11–14 mm']],
            work: { formal:'A slim metal bracelet or none at all. Avoid anything that rattles against a desk or keyboard.',
                    casual:'Leather, beaded or metal all work. Wear it on the opposite wrist to your watch, or well clear of it.',
                    manual:'Skip bracelets entirely during work — they catch, scratch and get damaged.' },
            note:'Fit should allow about a finger’s width of movement. Too tight looks strained; too loose slides over the hand.'
        },
        cufflinks: {
            spec: [['Understated 10–14 mm','Standard 14–18 mm','Larger 16–20 mm']],
            work: { formal:'Silk knots, mother-of-pearl or plain metal for business. Save novelty designs for social occasions.',
                    casual:'Only relevant with French-cuff shirts — check you own some before buying.',
                    manual:'Not applicable to most hands-on work.' },
            note:'Whale-back and bullet-back closures are the easiest to fasten single-handed. Chain-link styles look best but take practice.'
        },
    };
    function render() {
        const d = DATA[item.value];
        const spec = d.spec[0][W[build.value]];
        out.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC">' +
            '<p class="text-xs uppercase tracking-wider text-[color:var(--stone)]">Suggested size</p>' +
            '<p class="font-display text-2xl font-semibold" style="color:var(--gold)">' + spec + '</p>' +
            '<p class="text-sm mt-2">' + d.work[work.value] + '</p>' +
            '<p class="text-sm text-[color:var(--stone)] mt-2">' + d.note + '</p></div>';
    }
    [build, work, item].forEach(function (e) { e.addEventListener('change', render); });
    render();
})();
</script>
@endpush
