@extends('layouts.app')

@section('title', 'Jewelry Care & Cleaning Guide — Safe Methods by Material — Gleamion.com')
@section('meta_description', 'Choose your metal and stone to get safe, step-by-step cleaning instructions — plus the methods that will damage your piece.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Jewelry Care &amp; Cleaning Guide</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Tell us what your piece is made of and we’ll show the safe method — and what to avoid.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium mb-1" for="metal">Metal</label>
                <select id="metal" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="gold">Gold (any karat)</option>
                    <option value="whitegold">White gold (rhodium plated)</option>
                    <option value="silver">Sterling silver</option>
                    <option value="platinum">Platinum</option>
                    <option value="plated">Gold plated / vermeil</option>
                    <option value="costume">Costume / base metal</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="stone">Stone</label>
                <select id="stone" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="none">No stones</option>
                    <option value="diamond">Diamond</option>
                    <option value="sapphire">Sapphire / ruby</option>
                    <option value="emerald">Emerald</option>
                    <option value="pearl">Pearl</option>
                    <option value="opal">Opal / turquoise</option>
                    <option value="soft">Other soft stone (amber, coral, tanzanite)</option>
                </select>
            </div>
        </div>

        <div id="advice" class="mt-6"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">Habits that protect any piece</h2>
        <ul class="space-y-2 text-sm text-[color:var(--stone)]">
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Last on, first off.</strong> Put jewelry on after perfume, lotion and hairspray; take it off before washing up, swimming or the gym.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Store pieces separately.</strong> Diamonds scratch everything, including other diamonds. Use pouches or a lined box with compartments.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Check settings twice a year.</strong> Gently shake near your ear — a rattle means a loose stone. Prongs wear thin over years of wear.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Never use toothpaste or baking soda.</strong> Both are abrasive enough to leave permanent micro-scratches on gold and silver.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Insure and appraise.</strong> Get valuations updated every few years — metal and stone prices move.</span></li>
        </ul>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const METALS = {
        gold:      { do:['Soak 20–30 minutes in warm water with a few drops of mild dish soap.','Brush gently with a soft toothbrush, paying attention to the underside of settings where grime collects.','Rinse in clean warm water and dry with a lint-free cloth.'], avoid:['Chlorine and bleach — they attack the alloys and can cause stress cracks.','Abrasive powders, toothpaste and paper towels.'] },
        whitegold: { do:['Clean as for gold: warm soapy water and a soft brush.','Expect to have the rhodium plating renewed every 12–24 months — that is normal wear, not a defect.'], avoid:['Polishing cloths with compound, which strip the rhodium faster.','Chlorine, which dulls the plating quickly.'] },
        silver:    { do:['Wash in warm soapy water, then dry completely — moisture accelerates tarnish.','Use a proper silver polishing cloth for tarnish; work in straight lines, not circles.','Store in a sealed bag with an anti-tarnish strip.'], avoid:['Dips and “instant” tarnish removers on antiqued or oxidised pieces — they strip the intentional dark detail.','Leaving silver in humid bathrooms.'] },
        platinum:  { do:['Soak in warm soapy water and brush gently; platinum is very tolerant.','Have it professionally polished occasionally — platinum develops a soft patina rather than losing metal.'], avoid:['Nothing much — platinum is the most forgiving metal. Still keep it away from harsh chemicals.'] },
        plated:    { do:['Wipe with a soft dry cloth after each wear.','If needed, use a barely damp cloth and dry immediately.'], avoid:['Soaking, brushing or any cleaner — plating is microns thick and rubs away.','Perfume, sweat and water, which all shorten plating life.'] },
        costume:   { do:['Wipe with a dry or barely damp cloth only.','Store dry, away from humidity.'], avoid:['Water, soaking and ultrasonic cleaners — glue-set stones fall out and base metal corrodes.'] },
    };

    const STONES = {
        none:      { note:null, avoid:[] },
        diamond:   { note:'Diamonds tolerate soapy water, soft brushing and ultrasonic cleaning well. They attract grease, so clean more often than you think you need to.', avoid:['Skip the ultrasonic if the stone is fracture-filled or has visible feathers reaching the surface.'] },
        sapphire:  { note:'Sapphire and ruby are hard (Mohs 9) and take normal cleaning comfortably.', avoid:['Avoid ultrasonic cleaning on stones that have been fracture-filled or heavily treated.'] },
        emerald:   { note:'Emeralds are almost always oil- or resin-treated to fill natural fissures. Cleaning can strip that treatment and make the stone look cloudy.', avoid:['Never use ultrasonic or steam cleaners.','No hot water, solvents or detergents — a barely damp cloth is safest.'] },
        pearl:     { note:'Pearls are organic nacre, soft (Mohs 2.5–4.5) and easily dulled. Wipe with a soft damp cloth after every wear.', avoid:['Never soak pearls — water weakens the silk thread and can discolour it.','No ultrasonic, no chemicals, no perfume contact.','Restring annually if worn often.'] },
        opal:      { note:'Opal and turquoise are porous and can craze or discolour. Clean with a slightly damp cloth only.', avoid:['No soaking, ultrasonic, steam or heat.','Keep away from oils, lotions and sudden temperature changes.'] },
        soft:      { note:'Soft or heat-sensitive stones need the gentlest handling: a dry or barely damp cloth.', avoid:['No ultrasonic or steam — these stones fracture or fade.','Avoid prolonged sunlight, which fades many soft stones.'] },
    };

    const metal = document.getElementById('metal');
    const stone = document.getElementById('stone');
    const advice = document.getElementById('advice');

    function list(items, colour) {
        return items.map(function (t) {
            return '<li class="flex gap-3 text-sm"><span class="facet-sm mt-1.5 shrink-0" style="background:' + colour + '"></span><span>' + t + '</span></li>';
        }).join('');
    }

    function render() {
        const m = METALS[metal.value];
        const s = STONES[stone.value];

        // Strictest rule wins: if the stone is delicate, its warnings override the metal's method.
        const delicate = ['pearl','opal','emerald','soft'].includes(stone.value) || ['plated','costume'].includes(metal.value);

        let html = '';

        if (delicate) {
            html += '<div class="border-l-4 p-4 mb-5" style="border-color:var(--gold); background:#FBF7EC">' +
                '<p class="text-sm"><strong>Gentle handling required.</strong> This combination includes a delicate material — ' +
                'follow the “avoid” list strictly, and when in doubt take it to a jeweler rather than cleaning at home.</p></div>';
        }

        html += '<h3 class="font-display text-xl font-semibold mb-2">How to clean it</h3><ul class="space-y-2 mb-5">' + list(m.do, 'var(--gold)') + '</ul>';

        if (s.note) {
            html += '<div class="border border-[color:var(--line)] p-4 mb-5 text-sm"><strong>About your stone:</strong> ' + s.note + '</div>';
        }

        const avoid = m.avoid.concat(s.avoid);
        html += '<h3 class="font-display text-xl font-semibold mb-2">Avoid</h3><ul class="space-y-2">' + list(avoid, '#C0563A') + '</ul>';

        advice.innerHTML = html;
    }

    metal.addEventListener('change', render);
    stone.addEventListener('change', render);
    render();
})();
</script>
@endpush
