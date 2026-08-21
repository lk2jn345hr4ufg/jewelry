@extends('layouts.app')

@section('title', 'Birthstone & Gemstone Guide — By Month, Hardness & Meaning — Gleamion.com')
@section('meta_description', 'Find the birthstone for any month with its colour, Mohs hardness, meaning and care notes — plus a searchable guide to major gemstones.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Gemstone &amp; Birthstone Guide</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Pick a month — or search any stone by name.</p>

    <div class="card p-6 mt-6">
        <div class="flex flex-wrap gap-2" id="monthTabs"></div>

        <div id="monthPanel" class="mt-6"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">All gemstones</h2>
        <input id="gemSearch" type="search" placeholder="Search by stone name, colour or month…"
               class="w-full border border-[color:var(--line)] px-3 py-2 mb-4">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="text-left border-b border-[color:var(--line)]">
                    <th class="py-2 pr-4">Stone</th><th class="py-2 pr-4">Month</th>
                    <th class="py-2 pr-4">Colour</th><th class="py-2 pr-4">Hardness</th><th class="py-2">Care</th>
                </tr></thead>
                <tbody id="gemBody"></tbody>
            </table>
        </div>
        <p class="text-xs text-[color:var(--stone)] mt-4">
            Hardness is on the Mohs scale (1 = talc, 10 = diamond). Anything below 7 scratches with everyday dust and
            needs gentler wear and storage.
        </p>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const GEMS = [
        { m:'January',   name:'Garnet',      colour:'Deep red (also green, orange)', mohs:'6.5–7.5', meaning:'Protection, enduring friendship and steadfast loyalty.', care:'Warm soapy water; avoid steam and ultrasonic cleaners.' },
        { m:'February',  name:'Amethyst',    colour:'Purple, from lilac to deep violet', mohs:'7', meaning:'Clarity of mind, calm and sobriety.', care:'Soapy water; fades in prolonged sunlight — store in the dark.' },
        { m:'March',     name:'Aquamarine',  colour:'Pale sea blue to blue-green', mohs:'7.5–8', meaning:'Courage, tranquillity and safe passage at sea.', care:'Soapy water or ultrasonic; keep away from heat.' },
        { m:'April',     name:'Diamond',     colour:'Colourless (all colours occur)', mohs:'10', meaning:'Endurance, clarity and lasting commitment.', care:'Ultrasonic-safe; clean often — it attracts grease readily.' },
        { m:'May',       name:'Emerald',     colour:'Vivid green', mohs:'7.5–8', meaning:'Renewal, growth and fertility.', care:'Soft brush and soapy water only — never ultrasonic; oils can be washed out.' },
        { m:'June',      name:'Pearl',       colour:'White, cream, pink, black', mohs:'2.5–4.5', meaning:'Purity, wisdom and integrity.', care:'Wipe with a damp cloth. Last on, first off — perfume and hairspray damage nacre.' },
        { m:'June',      name:'Alexandrite', colour:'Green in daylight, red under lamplight', mohs:'8.5', meaning:'Balance, adaptability and good fortune.', care:'Ultrasonic-safe; extremely rare in natural form.' },
        { m:'July',      name:'Ruby',        colour:'Rich red to pinkish red', mohs:'9', meaning:'Passion, vitality and protection.', care:'Very durable; ultrasonic-safe unless fracture-filled.' },
        { m:'August',    name:'Peridot',     colour:'Olive to lime green', mohs:'6.5–7', meaning:'Strength, lightness and warding off nightmares.', care:'Soapy water only; sensitive to acids and rapid temperature change.' },
        { m:'September', name:'Sapphire',    colour:'Blue (also pink, yellow, white)', mohs:'9', meaning:'Wisdom, nobility and faithfulness.', care:'Very durable; ultrasonic-safe.' },
        { m:'October',   name:'Opal',        colour:'Iridescent play of colour', mohs:'5–6.5', meaning:'Creativity, hope and imagination.', care:'Damp cloth only. Never ultrasonic — it can crack from heat or dryness.' },
        { m:'October',   name:'Tourmaline',  colour:'Every colour; often bi-coloured', mohs:'7–7.5', meaning:'Inspiration and emotional balance.', care:'Soapy water; avoid sharp heat.' },
        { m:'November',  name:'Topaz',       colour:'Golden, blue, colourless, pink', mohs:'8', meaning:'Warmth, generosity and good health.', care:'Soapy water; perfect cleavage means it chips if knocked.' },
        { m:'November',  name:'Citrine',     colour:'Yellow to amber', mohs:'7', meaning:'Optimism, energy and abundance.', care:'Soapy water; colour can fade in strong sun.' },
        { m:'December',  name:'Turquoise',   colour:'Sky blue to green', mohs:'5–6', meaning:'Protection, healing and good fortune.', care:'Dry cloth only — porous and absorbs oils, lotions and water.' },
        { m:'December',  name:'Tanzanite',   colour:'Violet-blue', mohs:'6–7', meaning:'Transformation and insight.', care:'Soapy water only; never ultrasonic — it fractures easily.' },
        { m:'December',  name:'Zircon',      colour:'Blue, golden, colourless', mohs:'6–7.5', meaning:'Prosperity and restful sleep.', care:'Soapy water; facet edges abrade with hard wear.' },
    ];

    const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];
    const tabs = document.getElementById('monthTabs');
    const panel = document.getElementById('monthPanel');

    function showMonth(month) {
        [...tabs.children].forEach(function (b) {
            const on = b.dataset.month === month;
            b.style.background = on ? 'var(--gold)' : '';
            b.style.color = on ? '#fff' : '';
            b.style.borderColor = on ? 'var(--gold)' : 'var(--line)';
        });
        const list = GEMS.filter(function (g) { return g.m === month; });
        panel.innerHTML = list.map(function (g) {
            return '<div class="border border-[color:var(--line)] p-5 mb-3">' +
                '<div class="flex items-baseline gap-3 flex-wrap">' +
                    '<h3 class="font-display text-2xl font-semibold">' + g.name + '</h3>' +
                    '<span class="text-xs uppercase tracking-wider text-[color:var(--stone)]">Mohs ' + g.mohs + '</span>' +
                '</div>' +
                '<p class="text-sm mt-2"><span class="text-[color:var(--stone)]">Colour:</span> ' + g.colour + '</p>' +
                '<p class="text-sm mt-1"><span class="text-[color:var(--stone)]">Meaning:</span> ' + g.meaning + '</p>' +
                '<p class="text-sm mt-1"><span class="text-[color:var(--stone)]">Care:</span> ' + g.care + '</p>' +
            '</div>';
        }).join('');
    }

    MONTHS.forEach(function (m) {
        const b = document.createElement('button');
        b.type = 'button';
        b.dataset.month = m;
        b.textContent = m;
        b.className = 'px-3 py-1.5 text-sm border';
        b.style.borderColor = 'var(--line)';
        b.addEventListener('click', function () { showMonth(m); });
        tabs.appendChild(b);
    });

    // Full table + search
    const body = document.getElementById('gemBody');
    function renderTable(filter) {
        const f = (filter || '').toLowerCase();
        const rows = GEMS.filter(function (g) {
            return !f || (g.name + ' ' + g.m + ' ' + g.colour).toLowerCase().includes(f);
        });
        body.innerHTML = rows.length ? rows.map(function (g) {
            return '<tr class="border-b border-[color:var(--line)]">' +
                '<td class="py-2 pr-4 font-medium">' + g.name + '</td>' +
                '<td class="py-2 pr-4">' + g.m + '</td>' +
                '<td class="py-2 pr-4 text-[color:var(--stone)]">' + g.colour + '</td>' +
                '<td class="py-2 pr-4">' + g.mohs + '</td>' +
                '<td class="py-2 text-[color:var(--stone)]">' + g.care + '</td></tr>';
        }).join('') : '<tr><td colspan="5" class="py-4 text-center text-[color:var(--stone)]">No stones match that search.</td></tr>';
    }

    document.getElementById('gemSearch').addEventListener('input', function (e) { renderTable(e.target.value); });

    const now = new Date();
    showMonth(MONTHS[now.getMonth()]);
    renderTable('');
})();
</script>
@endpush
