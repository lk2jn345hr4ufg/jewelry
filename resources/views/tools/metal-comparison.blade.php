@extends('layouts.app')

@section('title', 'Jewelry Metal Comparison — Gold vs Platinum vs Silver — Gleamion.com')
@section('meta_description', 'Compare gold, white gold, platinum, silver, palladium and titanium on durability, upkeep, hypoallergenic properties, resizing and cost.')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Metal Comparison</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Select the metals you’re weighing up — or answer three questions and get a recommendation.</p>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-4">Which metal suits you?</h2>
        <div class="grid gap-5 sm:grid-cols-3">
            <div>
                <label class="block text-sm font-medium mb-1" for="qBudget">Budget</label>
                <select id="qBudget" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="low">Keep it affordable</option>
                    <option value="mid" selected>Mid-range</option>
                    <option value="high">Premium — buy once</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="qSkin">Sensitive skin?</label>
                <select id="qSkin" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="no" selected>No reactions</option>
                    <option value="yes">Yes — nickel allergy</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="qWear">How hard is the wear?</label>
                <select id="qWear" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="light">Occasional</option>
                    <option value="daily" selected>Daily</option>
                    <option value="rough">Daily + manual work</option>
                </select>
            </div>
        </div>
        <div id="recommendation" class="mt-5"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-4">Side by side</h2>
        <div class="flex flex-wrap gap-2 mb-5" id="metalToggles"></div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="compareTable"></table>
        </div>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const METALS = {
        yellowgold: {
            name:'Yellow gold (14–18K)',
            durability:'Good — 14K harder than 18K',
            hardness:'~150 HV (14K)',
            upkeep:'Polish occasionally; scratches show',
            hypo:'Usually fine; some alloys contain nickel',
            resize:'Easy',
            tarnish:'Does not tarnish',
            cost:'$$$',
            best:'Classic warmth, traditional engagement and wedding bands'
        },
        whitegold: {
            name:'White gold',
            durability:'Good',
            hardness:'~150 HV',
            upkeep:'Rhodium replating every 1–2 years',
            hypo:'Often contains nickel — check the alloy',
            resize:'Easy',
            tarnish:'Plating dulls over time',
            cost:'$$$',
            best:'A bright white look at lower cost than platinum'
        },
        platinum: {
            name:'Platinum',
            durability:'Excellent — displaces rather than loses metal',
            hardness:'~120 HV but very dense',
            upkeep:'Develops a patina; polish if you prefer shine',
            hypo:'Yes — 95% pure, ideal for allergies',
            resize:'Possible, needs a specialist',
            tarnish:'Never tarnishes',
            cost:'$$$$',
            best:'Heirloom pieces, sensitive skin, secure stone settings'
        },
        silver: {
            name:'Sterling silver',
            durability:'Soft — bends and scratches easily',
            hardness:'~80 HV',
            upkeep:'Frequent polishing; tarnishes',
            hypo:'Usually fine; contains copper',
            resize:'Easy',
            tarnish:'Tarnishes readily',
            cost:'$',
            best:'Fashion pieces, occasional wear, larger statement designs'
        },
        palladium: {
            name:'Palladium',
            durability:'Very good',
            hardness:'~120 HV',
            upkeep:'Low — naturally white, no plating needed',
            hypo:'Yes',
            resize:'Possible, specialist required',
            tarnish:'Does not tarnish',
            cost:'$$$',
            best:'Platinum’s look and feel at lower weight and often lower price'
        },
        titanium: {
            name:'Titanium',
            durability:'Extremely scratch-resistant',
            hardness:'~350 HV',
            upkeep:'Almost none',
            hypo:'Yes — fully biocompatible',
            resize:'Cannot be resized',
            tarnish:'Does not tarnish',
            cost:'$',
            best:'Men’s bands, hard manual work, active lifestyles'
        },
    };

    const ROWS = [
        ['durability','Durability'], ['hardness','Hardness'], ['upkeep','Upkeep'],
        ['hypo','Hypoallergenic'], ['resize','Resizing'], ['tarnish','Tarnish'],
        ['cost','Relative cost'], ['best','Best for'],
    ];

    let selected = ['yellowgold','platinum','silver'];

    const toggles = document.getElementById('metalToggles');
    const table = document.getElementById('compareTable');

    function renderToggles() {
        toggles.innerHTML = '';
        Object.keys(METALS).forEach(function (key) {
            const b = document.createElement('button');
            b.type = 'button';
            b.textContent = METALS[key].name;
            b.className = 'px-3 py-1.5 text-sm border';
            const on = selected.includes(key);
            b.style.borderColor = on ? 'var(--gold)' : 'var(--line)';
            b.style.background = on ? 'var(--gold)' : '';
            b.style.color = on ? '#fff' : '';
            b.addEventListener('click', function () {
                if (selected.includes(key)) {
                    if (selected.length > 1) selected = selected.filter(function (k) { return k !== key; });
                } else {
                    selected.push(key);
                }
                renderToggles();
                renderTable();
            });
            toggles.appendChild(b);
        });
    }

    function renderTable() {
        let head = '<thead><tr class="text-left border-b border-[color:var(--line)]"><th class="py-2 pr-4"></th>';
        selected.forEach(function (k) {
            head += '<th class="py-2 pr-4 font-display text-lg font-semibold">' + METALS[k].name + '</th>';
        });
        head += '</tr></thead>';

        let body = '<tbody>';
        ROWS.forEach(function (r) {
            body += '<tr class="border-b border-[color:var(--line)]">' +
                '<td class="py-2 pr-4 text-xs uppercase tracking-wider text-[color:var(--stone)] whitespace-nowrap">' + r[1] + '</td>';
            selected.forEach(function (k) {
                body += '<td class="py-2 pr-4 align-top">' + METALS[k][r[0]] + '</td>';
            });
            body += '</tr>';
        });
        body += '</tbody>';

        table.innerHTML = head + body;
    }

    // Recommendation
    const rec = document.getElementById('recommendation');
    function renderRec() {
        const budget = document.getElementById('qBudget').value;
        const skin = document.getElementById('qSkin').value;
        const wear = document.getElementById('qWear').value;

        let pick, why;

        if (skin === 'yes' && budget === 'high') {
            pick = 'platinum'; why = 'It is 95% pure with no nickel, never tarnishes, and holds stones more securely than any other metal — the safest choice for reactive skin when budget allows.';
        } else if (skin === 'yes' && wear === 'rough') {
            pick = 'titanium'; why = 'Fully biocompatible and the most scratch-resistant option here. The trade-off is that it cannot be resized, so get the fit right first time.';
        } else if (skin === 'yes') {
            pick = 'palladium'; why = 'Naturally white, hypoallergenic and lighter on the budget than platinum, with no replating to keep up with.';
        } else if (wear === 'rough' && budget === 'low') {
            pick = 'titanium'; why = 'Takes daily abuse without showing it, and costs a fraction of the precious metals. Cannot be resized.';
        } else if (budget === 'high') {
            pick = 'platinum'; why = 'Wears by displacing metal rather than losing it, so an heirloom piece survives generations. Heaviest and priciest, but essentially permanent.';
        } else if (budget === 'low') {
            pick = 'silver'; why = 'Easily the most affordable, and beautiful for statement pieces — accepting that it is soft and needs regular polishing.';
        } else if (wear === 'daily') {
            pick = 'yellowgold'; why = '14K yellow gold is the sensible everyday standard: hard enough to resist knocks, never tarnishes, easy for any jeweler to resize or repair.';
        } else {
            pick = 'whitegold'; why = 'A bright white finish at a comfortable price. Budget for rhodium replating every year or two to keep it crisp.';
        }

        const m = METALS[pick];
        rec.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC">' +
            '<p class="text-xs uppercase tracking-wider text-[color:var(--stone)]">Suggested metal</p>' +
            '<p class="font-display text-2xl font-semibold mt-1" style="color:var(--gold)">' + m.name + '</p>' +
            '<p class="text-sm mt-2">' + why + '</p></div>';

        if (!selected.includes(pick)) {
            selected.push(pick);
            renderToggles();
            renderTable();
        }
    }

    ['qBudget','qSkin','qWear'].forEach(function (id) {
        document.getElementById(id).addEventListener('change', renderRec);
    });

    renderToggles();
    renderTable();
    renderRec();
})();
</script>
@endpush
