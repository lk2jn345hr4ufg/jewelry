@extends('layouts.app')

@section('title', 'Gemstone Durability Checker — Can I Wear It Daily? — Gleamion.com')
@section('meta_description', 'Check whether a gemstone can survive daily wear. Hardness, toughness, cleavage and treatment risks for 30 popular stones.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Gemstone Durability Checker</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Hardness is only half the story — a diamond is the hardest thing there is and still chips. Check before you commit a stone to daily wear.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium mb-1" for="stone">Gemstone</label>
                <select id="stone" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white"></select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="piece">Type of piece</label>
                <select id="piece" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="ring" selected>Ring worn daily</option>
                    <option value="occasional">Occasional ring</option>
                    <option value="earrings">Earrings or pendant</option>
                    <option value="bracelet">Bracelet</option>
                </select>
            </div>
        </div>
        <div id="verdict" class="mt-6"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">All stones ranked</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="text-left border-b border-[color:var(--line)]">
                    <th class="py-2 pr-4">Stone</th><th class="py-2 pr-4">Mohs</th>
                    <th class="py-2 pr-4">Toughness</th><th class="py-2">Daily ring?</th>
                </tr></thead>
                <tbody id="rows"></tbody>
            </table>
        </div>
        <p class="text-xs text-[color:var(--stone)] mt-4">
            <strong>Hardness</strong> resists scratching; <strong>toughness</strong> resists chipping and breaking.
            They are independent — jade is only Mohs 6 but almost impossible to shatter, while diamond is Mohs 10 and
            will split cleanly if struck along its cleavage plane.
        </p>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    // name, mohs, toughness(1-5), cleavage risk, note
    const STONES = [
        ['Diamond',10,4,'Perfect cleavage — can split if struck at the wrong angle, despite its hardness.'],
        ['Ruby',9,5,'Nearly ideal for daily wear: hard, tough and no cleavage. One of the best choices for a ring.'],
        ['Sapphire',9,5,'Same material as ruby. Excellent everyday stone in any colour.'],
        ['Moissanite',9.25,4,'Very hard and durable; an excellent daily alternative to diamond.'],
        ['Chrysoberyl',8.5,5,'Underrated and extremely durable. Includes alexandrite and cat’s eye.'],
        ['Spinel',8,4,'Hard, tough and usually untreated. A genuinely good everyday stone.'],
        ['Topaz',8,2,'Hard but with perfect cleavage — chips readily if knocked. Better in pendants.'],
        ['Emerald',7.75,2,'Brittle and almost always oil-treated. Protective settings only; never ultrasonic.'],
        ['Aquamarine',7.75,4,'Hard and reasonably tough. Fine for daily rings with a sensible setting.'],
        ['Morganite',7.75,4,'Same family as aquamarine and equally wearable. Colour can fade in strong heat.'],
        ['Tourmaline',7.25,3,'Adequate for regular wear, though heat-sensitive and sometimes included.'],
        ['Garnet',7.25,3,'Reasonably durable for everyday pieces, but avoids sudden temperature changes.'],
        ['Amethyst',7,3,'Fine for regular wear; colour fades with prolonged sunlight exposure.'],
        ['Citrine',7,3,'Same as amethyst — durable enough, but keep it out of long sun.'],
        ['Quartz (rose, smoky)',7,3,'Everyday-capable but will accumulate surface scratches over years.'],
        ['Jade (jadeite)',6.75,5,'The toughest gem material there is — nearly unbreakable, though it scratches.'],
        ['Peridot',6.75,2,'Soft and brittle. Scratches quickly on a hand; best in earrings.'],
        ['Zircon',7,2,'Facet edges abrade noticeably with wear. Not ideal for daily rings.'],
        ['Tanzanite',6.5,2,'Beautiful but genuinely fragile — one knock can cleave it. Pendants and earrings only.'],
        ['Moonstone',6,2,'Prone to cleaving. Lovely in protected settings, poor in exposed rings.'],
        ['Opal',5.75,2,'Soft, porous and can craze from dryness or heat. Never a daily ring stone.'],
        ['Turquoise',5.5,3,'Porous and easily discoloured by oils and lotions. Keep to occasional wear.'],
        ['Lapis lazuli',5.5,3,'Soft and often dyed. Wipe only; avoid water and daily ring wear.'],
        ['Apatite',5,2,'Too soft for rings entirely. Collector and pendant stone.'],
        ['Pearl',3,3,'Organic nacre — scratches, dulls with perfume and dissolves in acid. Never a daily ring.'],
        ['Amber',2.5,2,'Fossilised resin; extremely soft and heat-sensitive. Pendants and beads only.'],
        ['Coral',3.5,3,'Soft, porous and bleaches with sunlight and chemicals. Handle gently.'],
    ];

    const stone = document.getElementById('stone');
    const piece = document.getElementById('piece');
    const verdict = document.getElementById('verdict');

    STONES.forEach(function (s, i) {
        const o = document.createElement('option');
        o.value = i; o.textContent = s[0];
        stone.appendChild(o);
    });

    function rating(mohs, tough, kind) {
        const score = mohs + tough;
        if (kind === 'ring') return score >= 12 ? 2 : (score >= 10 ? 1 : 0);
        if (kind === 'occasional') return score >= 10 ? 2 : (score >= 8 ? 1 : 0);
        if (kind === 'bracelet') return score >= 10 ? 2 : (score >= 7.5 ? 1 : 0);
        return score >= 7 ? 2 : 1; // earrings/pendant
    }

    const LABELS = [
        ['Not recommended','#C0563A','This stone will visibly deteriorate in this role. Choose a different piece type, or accept it as a short-life item.'],
        ['With care','var(--stone)','Workable if you are mindful — remove it for cleaning, gardening, gym and manual tasks, and choose a protective setting.'],
        ['Well suited','var(--gold)','This stone can handle the role comfortably with normal sensible care.'],
    ];

    function render() {
        const s = STONES[parseInt(stone.value, 10)];
        const r = rating(s[1], s[2], piece.value);
        const l = LABELS[r];

        verdict.innerHTML = '<div class="border-l-4 p-4" style="border-color:' + l[1] + '; background:#FBF7EC">' +
            '<div class="flex flex-wrap items-baseline gap-3">' +
            '<span class="font-display text-2xl font-semibold">' + s[0] + '</span>' +
            '<span class="text-sm px-2 py-0.5 border" style="border-color:' + l[1] + ';color:' + l[1] + '">' + l[0] + '</span>' +
            '<span class="text-xs text-[color:var(--stone)]">Mohs ' + s[1] + ' · toughness ' + s[2] + '/5</span></div>' +
            '<p class="text-sm mt-2">' + l[2] + '</p>' +
            '<p class="text-sm text-[color:var(--stone)] mt-1">' + s[3] + '</p></div>';
    }

    stone.addEventListener('change', render);
    piece.addEventListener('change', render);
    render();

    document.getElementById('rows').innerHTML = STONES.slice().sort(function (a, b) {
        return (b[1] + b[2]) - (a[1] + a[2]);
    }).map(function (s) {
        const r = rating(s[1], s[2], 'ring');
        const l = LABELS[r];
        return '<tr class="border-b border-[color:var(--line)]">' +
            '<td class="py-2 pr-4 font-medium">' + s[0] + '</td>' +
            '<td class="py-2 pr-4">' + s[1] + '</td>' +
            '<td class="py-2 pr-4">' + s[2] + '/5</td>' +
            '<td class="py-2" style="color:' + l[1] + '">' + l[0] + '</td></tr>';
    }).join('');
})();
</script>
@endpush
