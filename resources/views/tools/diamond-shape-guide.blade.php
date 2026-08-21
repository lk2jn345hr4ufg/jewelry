@extends('layouts.app')

@section('title', 'Diamond Shape Selector — Which Cut Suits You — Gleamion.com')
@section('meta_description', 'Compare diamond shapes on sparkle, price per carat, durability and how large they look, and get a recommendation for your style and hand.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Diamond Shape Selector</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Answer three questions for a recommendation, or browse how the shapes compare.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-3">
            <div>
                <label class="block text-sm font-medium mb-1" for="qStyle">Style preference</label>
                <select id="qStyle" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="classic" selected>Classic and timeless</option>
                    <option value="modern">Modern and clean</option>
                    <option value="vintage">Vintage and romantic</option>
                    <option value="unique">Distinctive and unusual</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="qPriority">Top priority</label>
                <select id="qPriority" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="sparkle" selected>Maximum sparkle</option>
                    <option value="size">Biggest look per carat</option>
                    <option value="value">Best value for money</option>
                    <option value="durable">Toughest for daily wear</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="qHand">Finger shape</label>
                <select id="qHand" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="any" selected>No preference</option>
                    <option value="short">Shorter fingers — want elongating</option>
                    <option value="long">Long, slender fingers</option>
                </select>
            </div>
        </div>
        <div id="rec" class="mt-6"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-4">All shapes compared</h2>
        <div id="grid" class="grid gap-4 sm:grid-cols-2"></div>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const SHAPES = {
        round:    { name:'Round brilliant', r:'50%', l:6.5, w:6.5, sparkle:5, size:2, value:1, durable:5,
                    note:'The most brilliant shape by design, and the safest choice — 60% of all engagement rings. You pay a premium for that popularity, and it loses more rough in cutting than any other shape.' },
        oval:     { name:'Oval', r:'50%', l:7.7, w:5.7, sparkle:4, size:5, value:4, durable:4,
                    note:'Nearly round-level sparkle but looks visibly larger per carat and flatters the hand by elongating the finger. Watch for the “bow tie” — a dark band across the centre in poorly cut stones.' },
        princess: { name:'Princess', r:'0', l:5.5, w:5.5, sparkle:4, size:2, value:4, durable:2,
                    note:'Crisp, modern and cheaper per carat than round. The sharp corners are its weak point — they chip if unprotected, so insist on a setting with corner prongs.' },
        cushion:  { name:'Cushion', r:'22%', l:5.8, w:5.8, sparkle:4, size:2, value:4, durable:4,
                    note:'Soft, pillowy outline with a warm, romantic fire. Hides colour slightly better than round, so you can drop a colour grade and save.' },
        emerald:  { name:'Emerald cut', r:'6%', l:7.0, w:5.0, sparkle:2, size:4, value:5, durable:4,
                    note:'Step cuts flash broad planes of light rather than sparkle. Elegant and architectural, but utterly unforgiving — inclusions and tint show, so buy higher clarity and colour.' },
        pear:     { name:'Pear', r:'50% 50% 50% 50% / 60% 60% 40% 40%', l:8.5, w:5.5, sparkle:4, size:5, value:4, durable:3,
                    note:'Distinctive and elongating, worn with the point toward the fingernail. The tip is fragile — a V-prong is essential.' },
        marquise: { name:'Marquise', r:'50%', l:10.0, w:5.0, sparkle:3, size:5, value:5, durable:2,
                    note:'The biggest apparent size of any shape per carat, and the most elongating on the hand. Both points need protecting, and bow-tie effects are common.' },
        radiant:  { name:'Radiant', r:'10%', l:6.5, w:5.3, sparkle:5, size:3, value:4, durable:4,
                    note:'A hybrid — emerald outline with brilliant faceting. Very sparkly, hides inclusions well and has trimmed corners that resist chipping.' },
        asscher:  { name:'Asscher', r:'8%', l:5.7, w:5.7, sparkle:2, size:2, value:5, durable:4,
                    note:'A square step cut with dramatic concentric flashes — the Art Deco signature. Like emerald cuts, it demands high clarity.' },
    };

    const grid = document.getElementById('grid');
    const rec = document.getElementById('rec');

    function gem(s, size) {
        const px = size || 46;
        const h = px * (s.l / s.w);
        return '<div style="width:' + px + 'px;height:' + h + 'px;border-radius:' + s.r + ';' +
            'background:linear-gradient(135deg,#EDE4CC,#B98B2F 55%,#EDE4CC);border:1px solid #9e7526;flex:none"></div>';
    }

    function bar(label, n) {
        let d = '';
        for (let i = 1; i <= 5; i++) {
            d += '<span class="facet-sm" style="background:' + (i <= n ? 'var(--gold)' : 'var(--line)') + '"></span>';
        }
        return '<div class="flex items-center gap-2 text-xs"><span class="w-16 text-[color:var(--stone)]">' + label + '</span><span class="flex gap-1">' + d + '</span></div>';
    }

    grid.innerHTML = Object.keys(SHAPES).map(function (k) {
        const s = SHAPES[k];
        return '<div class="border border-[color:var(--line)] p-4">' +
            '<div class="flex gap-4">' + gem(s) +
            '<div class="flex-1"><h3 class="font-display text-lg font-semibold">' + s.name + '</h3>' +
            '<div class="mt-2 space-y-1">' + bar('Sparkle', s.sparkle) + bar('Size look', s.size) + bar('Value', s.value) + bar('Toughness', s.durable) + '</div></div></div>' +
            '<p class="text-sm text-[color:var(--stone)] mt-3">' + s.note + '</p></div>';
    }).join('');

    function renderRec() {
        const style = document.getElementById('qStyle').value;
        const pri = document.getElementById('qPriority').value;
        const hand = document.getElementById('qHand').value;

        const score = {};
        Object.keys(SHAPES).forEach(function (k) { score[k] = 0; });

        // Style weighting
        ({ classic:['round','oval'], modern:['princess','radiant','emerald'],
           vintage:['cushion','asscher'], unique:['pear','marquise','asscher'] })[style]
            .forEach(function (k, i) { score[k] += 3 - i * 0.5; });

        // Priority weighting
        const key = { sparkle:'sparkle', size:'size', value:'value', durable:'durable' }[pri];
        Object.keys(SHAPES).forEach(function (k) { score[k] += SHAPES[k][key] * 0.8; });

        // Hand
        if (hand === 'short') ['oval','marquise','pear','emerald'].forEach(function (k) { score[k] += 2; });
        if (hand === 'long') ['round','cushion','princess','asscher'].forEach(function (k) { score[k] += 1.5; });

        const best = Object.keys(score).sort(function (a, b) { return score[b] - score[a]; })[0];
        const s = SHAPES[best];

        rec.innerHTML = '<div class="border-l-4 p-4 flex gap-4 items-start" style="border-color:var(--gold); background:#FBF7EC">' +
            gem(s, 40) +
            '<div><p class="text-xs uppercase tracking-wider text-[color:var(--stone)]">Suggested shape</p>' +
            '<p class="font-display text-2xl font-semibold" style="color:var(--gold)">' + s.name + '</p>' +
            '<p class="text-sm mt-1">' + s.note + '</p></div></div>';
    }

    ['qStyle','qPriority','qHand'].forEach(function (id) {
        document.getElementById(id).addEventListener('change', renderRec);
    });
    renderRec();
})();
</script>
@endpush
