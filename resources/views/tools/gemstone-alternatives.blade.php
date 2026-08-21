@extends('layouts.app')

@section('title', 'Gemstone Alternatives Finder — Cheaper Look-Alikes — Gleamion.com')
@section('meta_description', 'Want the look of a diamond, emerald or ruby for less? Find durable, affordable alternatives with honest notes on how each compares.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Gemstone Alternatives Finder</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Pick the stone you want and how it will be worn — we’ll show alternatives that hold up, and be straight about the trade-offs.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium mb-1" for="target">The look you want</label>
                <select id="target" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="diamond">Diamond — white and brilliant</option>
                    <option value="emerald">Emerald — vivid green</option>
                    <option value="ruby">Ruby — rich red</option>
                    <option value="sapphire">Blue sapphire</option>
                    <option value="pearl">Pearl</option>
                    <option value="opal">Opal — play of colour</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="use">How it will be worn</label>
                <select id="use" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="daily">Daily — engagement or everyday ring</option>
                    <option value="occasional" selected>Occasional — earrings, pendant, dress ring</option>
                </select>
            </div>
        </div>
        <div id="alts" class="mt-6 space-y-3"></div>
        <div id="warn" class="mt-4"></div>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const ALTS = {
        diamond: [
            { name:'Lab-grown diamond', save:'60–85% less', mohs:9.5, daily:true, note:'Chemically and optically identical to a mined diamond — the same material, grown rather than dug. Indistinguishable without lab equipment. Resale value is poor, but so is a natural diamond’s.' },
            { name:'Moissanite', save:'90% less', mohs:9.25, daily:true, note:'Nearly as hard as diamond and even more fiery — some find the extra rainbow flash a giveaway in bright light. Excellent for daily wear.' },
            { name:'White sapphire', save:'95% less', mohs:9, daily:true, note:'Durable and genuinely natural, but noticeably less brilliant — reads glassy rather than sparkly, and shows scratches on the facets over time.' },
            { name:'Cubic zirconia', save:'99% less', mohs:8.5, daily:false, note:'Convincing when new, but abrades and clouds within a year or two of daily wear. Fine for costume or travel pieces, poor for an engagement ring.' },
        ],
        emerald: [
            { name:'Tsavorite garnet', save:'50–70% less', mohs:7.25, daily:true, note:'A vivid green garnet, harder than emerald and typically untreated — often a genuinely better stone, just less famous.' },
            { name:'Green tourmaline', save:'70–85% less', mohs:7.25, daily:true, note:'Ranges from mint to forest green, usually clean and eye-catching. Durable enough for a ring worn daily.' },
            { name:'Chrome diopside', save:'95% less', mohs:5.5, daily:false, note:'Deep emerald colour at a very low price, but soft — best in earrings or pendants where it won’t take knocks.' },
            { name:'Lab-grown emerald', save:'80% less', mohs:7.75, daily:false, note:'Real emerald composition with fewer inclusions. Still brittle like natural emerald — set it protectively.' },
        ],
        ruby: [
            { name:'Lab-grown ruby', save:'90% less', mohs:9, daily:true, note:'Identical material to natural ruby, hard and hard-wearing. The colour is often more even than nature manages.' },
            { name:'Red spinel', save:'60–80% less', mohs:8, daily:true, note:'Historically mistaken for ruby in crown jewels. Naturally untreated, durable and increasingly collected in its own right.' },
            { name:'Rhodolite garnet', save:'90% less', mohs:7.25, daily:true, note:'Raspberry to purple-red. Not a true ruby colour, but a lovely stone that wears well.' },
            { name:'Ruby-red topaz', save:'92% less', mohs:8, daily:false, note:'Hard but with perfect cleavage — chips if struck at the wrong angle. Better in pendants than rings.' },
        ],
        sapphire: [
            { name:'Lab-grown sapphire', save:'90% less', mohs:9, daily:true, note:'Same material, same hardness, a fraction of the price. Excellent everyday choice.' },
            { name:'Blue spinel', save:'70% less', mohs:8, daily:true, note:'Bright, clean blue, usually untreated. Rarer than sapphire but priced lower.' },
            { name:'Tanzanite', save:'50% less', mohs:6.5, daily:false, note:'A gorgeous violet-blue, but soft and prone to fracture — keep it out of daily rings.' },
            { name:'Iolite', save:'95% less', mohs:7.25, daily:false, note:'Inky violet-blue at a very low price. Colour shifts with viewing angle, which some love.' },
        ],
        pearl: [
            { name:'Freshwater cultured pearl', save:'70–90% less', mohs:3, daily:false, note:'The standard today — real nacre, wide range of shapes and colours. Akoya and South Sea cost far more for rounder, glossier examples.' },
            { name:'Shell (mabe) pearl', save:'95% less', mohs:3.5, daily:false, note:'Made from shell nacre — genuinely lustrous and much tougher than a solid pearl, though not a true pearl.' },
            { name:'Glass or resin pearl', save:'99% less', mohs:5, daily:false, note:'Coated beads. Convincing at a distance; the coating wears at drill holes and along the string over time.' },
        ],
        opal: [
            { name:'Lab-created opal', save:'85% less', mohs:6, daily:false, note:'Genuine play-of-colour with more consistent patterning. Still porous and heat-sensitive like natural opal.' },
            { name:'Opal doublet / triplet', save:'80% less', mohs:6, daily:false, note:'A thin slice of real opal backed and capped for strength. Do not submerge — water separates the layers.' },
            { name:'Labradorite', save:'95% less', mohs:6.25, daily:false, note:'Flashes blue and gold rather than rainbow, but has a similar magic and costs very little.' },
            { name:'Rainbow moonstone', save:'92% less', mohs:6, daily:false, note:'A soft blue shimmer instead of opal’s colour play. Delicate but affordable and widely available.' },
        ],
    };

    const target = document.getElementById('target');
    const use = document.getElementById('use');
    const alts = document.getElementById('alts');
    const warn = document.getElementById('warn');

    function render() {
        const daily = use.value === 'daily';
        const list = ALTS[target.value];

        alts.innerHTML = list.map(function (a) {
            const unsuitable = daily && !a.daily;
            return '<div class="border p-4" style="border-color:' + (unsuitable ? 'var(--line)' : 'var(--champagne)') + ';opacity:' + (unsuitable ? '0.55' : '1') + '">' +
                '<div class="flex flex-wrap items-baseline gap-3">' +
                    '<h3 class="font-display text-xl font-semibold">' + a.name + '</h3>' +
                    '<span class="text-sm font-medium" style="color:var(--gold)">' + a.save + '</span>' +
                    '<span class="text-xs text-[color:var(--stone)]">Mohs ' + a.mohs + '</span>' +
                    (unsuitable ? '<span class="text-xs px-2 py-0.5 border" style="border-color:#C0563A;color:#C0563A">Not for daily wear</span>' : '') +
                '</div>' +
                '<p class="text-sm text-[color:var(--stone)] mt-2">' + a.note + '</p></div>';
        }).join('');

        warn.innerHTML = daily
            ? '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC"><p class="text-sm">For a ring worn every day, aim for Mohs 8 or above. Anything softer will abrade visibly within a few years — fine for earrings, disappointing on a hand.</p></div>'
            : '';
    }

    target.addEventListener('change', render);
    use.addEventListener('change', render);
    render();
})();
</script>
@endpush
