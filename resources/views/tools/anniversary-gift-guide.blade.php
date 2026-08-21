@extends('layouts.app')

@section('title', 'Anniversary Gift Guide — Traditional & Modern by Year — Gleamion.com')
@section('meta_description', 'Find the traditional material, modern gift and gemstone for every wedding anniversary year, with jewelry ideas for each.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Anniversary Gift Guide</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Every year has a traditional material, a modern equivalent and an associated gemstone.</p>

    <div class="card p-6 mt-6">
        <label class="block text-sm font-medium mb-1" for="year">Which anniversary? <span id="yearLabel" class="font-semibold" style="color:var(--gold)">1st</span></label>
        <input id="year" type="range" min="1" max="60" step="1" value="1" class="w-full accent-[color:var(--gold)]">
        <div class="flex justify-between text-xs text-[color:var(--stone)]"><span>1</span><span>60 years</span></div>
        <div id="detail" class="mt-6"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">All anniversaries</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="text-left border-b border-[color:var(--line)]">
                    <th class="py-2 pr-4">Year</th><th class="py-2 pr-4">Traditional</th><th class="py-2 pr-4">Modern</th><th class="py-2">Gemstone</th>
                </tr></thead>
                <tbody id="allRows"></tbody>
            </table>
        </div>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const Y = {
        1:['Paper','Clock','Gold jewelry'], 2:['Cotton','China','Garnet'], 3:['Leather','Crystal','Pearl'],
        4:['Fruit / flowers','Appliances','Blue topaz'], 5:['Wood','Silverware','Sapphire'],
        6:['Iron / candy','Wood','Amethyst'], 7:['Wool / copper','Desk set','Onyx'],
        8:['Bronze / pottery','Linen','Tourmaline'], 9:['Pottery / willow','Leather','Lapis lazuli'],
        10:['Tin / aluminium','Diamond jewelry','Diamond'], 11:['Steel','Fashion jewelry','Turquoise'],
        12:['Silk / linen','Pearl','Jade'], 13:['Lace','Textiles / furs','Citrine'],
        14:['Ivory','Gold jewelry','Opal'], 15:['Crystal','Watches','Ruby'],
        20:['China','Platinum','Emerald'], 25:['Silver','Silver','Silver jubilee'],
        30:['Pearl','Diamond','Pearl'], 35:['Coral','Jade','Emerald'],
        40:['Ruby','Ruby','Ruby'], 45:['Sapphire','Sapphire','Sapphire'],
        50:['Gold','Gold','Golden jubilee'], 55:['Emerald','Emerald','Emerald'],
        60:['Diamond','Diamond','Diamond jubilee'],
    };

    const IDEAS = {
        'Gold jewelry':'A slim gold band, hoop earrings or a simple pendant — the classic first-anniversary gift.',
        'Garnet':'Deep red garnet studs or a solitaire pendant; affordable and warm in tone.',
        'Pearl':'A strand, studs or a single pearl pendant — timeless and works at any budget.',
        'Blue topaz':'Bright and inexpensive; a large stone gives real impact for little money.',
        'Sapphire':'Hard-wearing and rich in colour. A sapphire eternity band marks five years beautifully.',
        'Amethyst':'Purple and regal, and one of the best value fine gemstones available.',
        'Onyx':'Black onyx cufflinks, signet rings or a bold cocktail ring.',
        'Tourmaline':'Comes in every colour — pick the shade that suits them rather than the “standard”.',
        'Lapis lazuli':'Deep blue with gold pyrite flecks; striking in a pendant or inlaid band.',
        'Diamond':'The ten-year milestone. An anniversary band or upgraded studs are the usual choices.',
        'Turquoise':'Warm, characterful and inexpensive. Suits everyday silver pieces.',
        'Jade':'Green jade bangles carry a long tradition of protection and good fortune.',
        'Citrine':'Sunny yellow to amber, large sizes stay affordable.',
        'Opal':'Iridescent and personal. Keep it to earrings or pendants — opal is too soft for daily rings.',
        'Ruby':'A major milestone stone. Rich red, extremely durable, and holds value well.',
        'Emerald':'Vivid green; set it protectively as emeralds chip more easily than their hardness suggests.',
        'Silver jubilee':'Traditionally silver, but many mark 25 years with a diamond upgrade or eternity band.',
        'Golden jubilee':'Gold, generously. A wide band, a heavy chain, or resetting the original rings.',
        'Diamond jubilee':'Sixty years. Diamonds in any form — often the original ring, remade.',
    };

    const year = document.getElementById('year');
    const yearLabel = document.getElementById('yearLabel');
    const detail = document.getElementById('detail');

    function ordinal(n) {
        const s = ['th','st','nd','rd'], v = n % 100;
        return n + (s[(v - 20) % 10] || s[v] || s[0]);
    }

    function nearest(n) {
        if (Y[n]) return n;
        const keys = Object.keys(Y).map(Number).filter(function (k) { return k <= n; });
        return keys.length ? keys[keys.length - 1] : 1;
    }

    function render() {
        const n = parseInt(year.value, 10);
        yearLabel.textContent = ordinal(n);
        const key = nearest(n);
        const [trad, modern, gem] = Y[key];
        const exact = key === n;

        detail.innerHTML =
            (exact ? '' : '<p class="text-xs text-[color:var(--stone)] mb-3">No specific tradition for year ' + n + ' — showing the ' + ordinal(key) + ', the nearest established milestone.</p>') +
            '<div class="grid gap-3 sm:grid-cols-3">' +
                ['Traditional', 'Modern', 'Gemstone'].map(function (label, i) {
                    const val = [trad, modern, gem][i];
                    return '<div class="border border-[color:var(--line)] p-4 text-center">' +
                        '<div class="text-xs uppercase tracking-wider text-[color:var(--stone)]">' + label + '</div>' +
                        '<div class="font-display text-xl font-semibold mt-1" style="color:var(--gold)">' + val + '</div></div>';
                }).join('') +
            '</div>' +
            (IDEAS[gem] ? '<div class="border-l-4 p-4 mt-4" style="border-color:var(--gold); background:#FBF7EC"><p class="text-sm"><strong>Jewelry idea:</strong> ' + IDEAS[gem] + '</p></div>' : '');
    }

    year.addEventListener('input', render);
    render();

    document.getElementById('allRows').innerHTML = Object.keys(Y).map(Number).sort(function (a, b) { return a - b; }).map(function (k) {
        return '<tr class="border-b border-[color:var(--line)]">' +
            '<td class="py-2 pr-4 font-medium">' + ordinal(k) + '</td>' +
            '<td class="py-2 pr-4 text-[color:var(--stone)]">' + Y[k][0] + '</td>' +
            '<td class="py-2 pr-4 text-[color:var(--stone)]">' + Y[k][1] + '</td>' +
            '<td class="py-2">' + Y[k][2] + '</td></tr>';
    }).join('');
})();
</script>
@endpush
