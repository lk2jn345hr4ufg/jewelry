@extends('layouts.app')

@section('title', 'Metal Allergy Checker — Safe Jewelry for Sensitive Skin — Gleamion.com')
@section('meta_description', 'Find out which jewelry metals are safe for nickel allergies and sensitive skin, and which to avoid, with practical tips for existing pieces.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Metal Allergy Checker</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Nickel is the most common contact allergen in the world, affecting roughly one in ten people. Check what’s safe before you buy.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium mb-1" for="sensitivity">Your sensitivity</label>
                <select id="sensitivity" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="mild" selected>Mild — occasional itching or redness</option>
                    <option value="strong">Strong — confirmed nickel allergy</option>
                    <option value="severe">Severe — reacts to most jewelry</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="place">Where it’s worn</label>
                <select id="place" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="ears">Earrings (piercings)</option>
                    <option value="ring" selected>Ring</option>
                    <option value="neck">Necklace</option>
                    <option value="wrist">Bracelet or watch</option>
                </select>
            </div>
        </div>
        <div id="verdict" class="mt-6"></div>
        <div id="metals" class="mt-6 space-y-2"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">If a piece you own irritates you</h2>
        <ul class="space-y-2 text-sm text-[color:var(--stone)]">
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Have it rhodium plated.</strong> A jeweler can plate almost any ring for $50–100, creating a barrier that lasts a year or two.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Keep it dry.</strong> Sweat and water leach nickel from the alloy — reactions are far worse in summer and at the gym.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Clear barrier coatings are temporary.</strong> Sold as a fix, they wear off within days and need constant reapplying.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Green skin isn’t always allergy.</strong> Copper in the alloy reacting with sweat stains skin harmlessly. An allergy itches, blisters or forms a rash.</span></li>
        </ul>
        <p class="text-xs text-[color:var(--stone)] mt-4 border-t border-[color:var(--line)] pt-4">
            Persistent rashes, broken skin or spreading reactions are worth showing to a doctor or dermatologist —
            patch testing identifies exactly which metal is responsible, and some reactions are not what they appear.
        </p>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const METALS = [
        { name:'Titanium (implant grade)', safe:3, note:'The safest metal available — used for surgical implants. No nickel, no reactions recorded in normal use.' },
        { name:'Niobium', safe:3, note:'Completely hypoallergenic and often used for first-piercing jewelry. Can be anodised into colours.' },
        { name:'Platinum (950)', safe:3, note:'95% pure with iridium or ruthenium alloys — no nickel. Excellent for permanent daily wear.' },
        { name:'Palladium (950)', safe:3, note:'Naturally white and nickel-free. A lighter, usually cheaper alternative to platinum.' },
        { name:'Surgical steel (316L)', safe:2, note:'Contains some nickel but bound tightly in the alloy. Fine for most people, but a small minority still react.' },
        { name:'24K / 22K gold', safe:3, note:'Nearly pure gold has almost no alloy content to react to. Soft, so best for pieces that take little abuse.' },
        { name:'18K yellow gold', safe:2, note:'75% gold, typically alloyed with copper and silver. Safe for most, though not guaranteed nickel-free.' },
        { name:'14K yellow gold', safe:2, note:'58.5% gold. More alloy means more risk — ask the jeweler for a nickel-free alloy specifically.' },
        { name:'Sterling silver', safe:2, note:'92.5% silver with copper. Nickel-free in reputable pieces, but cheap "silver" can be nickel-plated — buy hallmarked.' },
        { name:'White gold', safe:1, note:'Traditionally alloyed WITH nickel to whiten it. A common cause of reactions. Palladium-white-gold alloys exist — ask specifically.' },
        { name:'Costume / base metal', safe:0, note:'Usually nickel-heavy brass or pot metal with thin plating. Once the plating wears, reactions begin.' },
        { name:'Gold plated (over brass)', safe:0, note:'The plating is microns thick. When it wears through — often within months — the nickel base is against your skin.' },
    ];

    const sensitivity = document.getElementById('sensitivity');
    const place = document.getElementById('place');
    const verdict = document.getElementById('verdict');
    const metals = document.getElementById('metals');

    const LABEL = { 3:['Safe','var(--gold)'], 2:['Usually fine','var(--stone)'], 1:['Risky','#C0563A'], 0:['Avoid','#C0563A'] };

    function render() {
        const s = sensitivity.value;
        const min = s === 'severe' ? 3 : (s === 'strong' ? 2 : 1);

        let msg = '';
        if (place.value === 'ears') {
            msg = 'Pierced ears are the most reactive site — the metal sits inside broken skin. Use implant-grade titanium or niobium for new piercings, and treat anything plated as unsuitable regardless of your sensitivity level.';
        } else if (place.value === 'ring') {
            msg = 'Rings trap moisture and soap underneath, which accelerates nickel release. If you react only sometimes, try removing the ring before washing your hands and see whether it settles.';
        } else if (place.value === 'neck') {
            msg = 'Necklaces sit against skin that sweats and rubs. The clasp is often a different, cheaper metal than the chain — check it separately if a reaction appears in one spot.';
        } else {
            msg = 'Watch cases and backs are a common hidden source of nickel. A titanium or coated caseback solves it without replacing the whole watch.';
        }

        verdict.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC"><p class="text-sm">' + msg + '</p></div>';

        metals.innerHTML = METALS.map(function (m) {
            const dim = m.safe < min;
            const l = LABEL[m.safe];
            return '<div class="border p-3 flex flex-wrap gap-3 items-baseline" style="border-color:' + (dim ? 'var(--line)' : 'var(--champagne)') + ';opacity:' + (dim ? '0.5' : '1') + '">' +
                '<span class="font-medium">' + m.name + '</span>' +
                '<span class="text-xs px-2 py-0.5 border" style="border-color:' + l[1] + ';color:' + l[1] + '">' + l[0] + '</span>' +
                '<span class="text-sm text-[color:var(--stone)] w-full">' + m.note + '</span></div>';
        }).join('');
    }

    sensitivity.addEventListener('change', render);
    place.addEventListener('change', render);
    render();
})();
</script>
@endpush
