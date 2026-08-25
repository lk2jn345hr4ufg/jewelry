@extends('layouts.app')
@section('title', 'Diamond Cut Proportion Checker — Table & Depth % — Gleamion.com')
@section('meta_description', 'Enter a diamond’s table and depth percentages to see whether the proportions fall in the ideal range for maximum brilliance.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Cut Proportion Checker</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Only round brilliants get an official GIA cut grade. For every other shape you must judge the proportions yourself — this does that.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-3">
            <div>
                <label class="block text-sm font-medium mb-1" for="shape">Shape</label>
                <select id="shape" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="round" selected>Round brilliant</option>
                    <option value="princess">Princess</option>
                    <option value="cushion">Cushion</option>
                    <option value="oval">Oval</option>
                    <option value="emerald">Emerald / Asscher</option>
                    <option value="pear">Pear / Marquise</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="table">Table %</label>
                <input id="table" type="number" min="40" max="90" step="0.1" value="57" class="w-full border border-[color:var(--line)] px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="depth">Depth %</label>
                <input id="depth" type="number" min="40" max="90" step="0.1" value="61.5" class="w-full border border-[color:var(--line)] px-3 py-2">
            </div>
        </div>
        <div id="verdict" class="mt-6"></div>
        <div id="ranges" class="mt-4"></div>
        <p class="text-xs text-[color:var(--stone)] mt-5 border-t border-[color:var(--line)] pt-4">
            Proportions are a strong indicator, not the whole story — crown angle, pavilion angle and star length all
            matter too. If a stone falls outside these ranges but looks superb in person, trust your eyes; if it falls
            inside but looks dull, trust your eyes then too.
        </p>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const R = {
        round:    { t:[54,57,60], d:[59,61.8,62.6], note:'Round brilliants are the most studied cut. Table 54–57% with depth 59–62.6% is the classic ideal window.' },
        princess: { t:[67,72,77], d:[64,71,75], note:'Princess cuts run much deeper than rounds — a 70%+ depth is normal, not a fault. Look for depth close to table.' },
        cushion:  { t:[58,62,66], d:[61,66,68], note:'Cushions vary enormously between "chunky" and "crushed ice" faceting. Proportions alone tell you less here — view in person.' },
        oval:     { t:[53,57,63], d:[58,62,65], note:'Watch the length-to-width ratio too: 1.35–1.50 is generally considered most attractive. Check for a bow-tie shadow across the centre.' },
        emerald:  { t:[61,66,69], d:[61,67,70], note:'Step cuts prize symmetry over brilliance. Even, parallel facets matter more than hitting an exact table figure.' },
        pear:     { t:[53,58,65], d:[58,63,66], note:'Length-to-width around 1.45–1.75 for pear, 1.85–2.10 for marquise. Both are prone to bow-tie effects.' },
    };
    const shape = document.getElementById('shape'), tbl = document.getElementById('table'), dep = document.getElementById('depth');
    const verdict = document.getElementById('verdict'), ranges = document.getElementById('ranges');
    function judge(val, r) {
        if (val >= r[0] && val <= r[2]) {
            return Math.abs(val - r[1]) <= (r[2] - r[0]) / 4 ? 2 : 1;
        }
        return 0;
    }
    function render() {
        const s = R[shape.value];
        const t = parseFloat(tbl.value), d = parseFloat(dep.value);
        const ts = judge(t, s.t), ds = judge(d, s.d);
        const total = ts + ds;
        const LABEL = [
            ['Outside the ideal range','#C0563A','These proportions are likely to leak light — the stone may look dull or glassy. Compare it side by side with a better-proportioned stone before deciding.'],
            ['Acceptable','var(--stone)','Within tolerance but not optimal. Likely to look good without being exceptional.'],
            ['Good','var(--gold)','Solidly proportioned. Should return light well.'],
            ['Excellent','var(--gold)','Right in the sweet spot for this shape. These proportions support maximum brilliance.'],
        ][Math.min(3, total)];
        verdict.innerHTML = '<div class="border-l-4 p-4" style="border-color:' + LABEL[1] + '; background:#FBF7EC">' +
            '<p class="font-display text-2xl font-semibold" style="color:' + LABEL[1] + '">' + LABEL[0] + '</p>' +
            '<p class="text-sm mt-2">' + LABEL[2] + '</p>' +
            '<p class="text-sm text-[color:var(--stone)] mt-2">' + s.note + '</p></div>';
        function row(name, val, r, score) {
            const c = score === 2 ? 'var(--gold)' : (score === 1 ? 'var(--stone)' : '#C0563A');
            return '<div class="border border-[color:var(--line)] p-3 flex flex-wrap gap-3 items-baseline">' +
                '<span class="w-20 font-medium">' + name + '</span>' +
                '<span class="font-display text-xl" style="color:' + c + '">' + val + '%</span>' +
                '<span class="text-xs text-[color:var(--stone)]">ideal ' + r[0] + '–' + r[2] + '% (best near ' + r[1] + '%)</span></div>';
        }
        ranges.innerHTML = row('Table', t, s.t, ts) + row('Depth', d, s.d, ds);
    }
    [shape, tbl, dep].forEach(function (e) { e.addEventListener('input', render); e.addEventListener('change', render); });
    render();
})();
</script>
@endpush
