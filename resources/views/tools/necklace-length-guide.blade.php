@extends('layouts.app')

@section('title', 'Necklace & Chain Length Guide — Gleamion.com')
@section('meta_description', 'Find the right necklace length for your height, neckline and layering plans, with a visual guide from choker to opera length.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Necklace &amp; Chain Length Guide</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">See where each length falls, and get a recommendation for your neckline and build.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-3">
            <div>
                <label class="block text-sm font-medium mb-1" for="neck">Neck circumference</label>
                <div class="flex gap-2">
                    <input id="neck" type="number" min="8" max="24" step="0.5" value="14" class="w-full border border-[color:var(--line)] px-3 py-2">
                    <select id="unit" class="border border-[color:var(--line)] px-3 py-2 bg-white">
                        <option value="in">in</option>
                        <option value="cm">cm</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="neckline">Neckline</label>
                <select id="neckline" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="crew">Crew / high neck</option>
                    <option value="v" selected>V-neck</option>
                    <option value="scoop">Scoop / round</option>
                    <option value="strapless">Strapless / off-shoulder</option>
                    <option value="collar">Collared shirt</option>
                    <option value="turtle">Turtleneck</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="goal">Wearing it</label>
                <select id="goal" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="single" selected>On its own</option>
                    <option value="layer">Layered with others</option>
                    <option value="pendant">With a pendant</option>
                </select>
            </div>
        </div>

        <div id="rec" class="mt-6"></div>
        <div id="lengths" class="mt-6 space-y-2"></div>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const LENGTHS = [
        { in:14, name:'Collar',        where:'Snug around the middle of the neck',      note:'Formal and striking; needs an open neckline.' },
        { in:16, name:'Choker',        where:'At the base of the neck',                 note:'Works with almost anything; the most versatile short length.' },
        { in:18, name:'Princess',      where:'Just below the collarbone',               note:'The default for pendants — the most popular length sold.' },
        { in:20, name:'Matinee (short)',where:'A few inches below the collarbone',      note:'Good over crew necks and for a first layering piece.' },
        { in:24, name:'Matinee',       where:'At or just above the bust',               note:'Elongates the neckline; strong with business wear.' },
        { in:30, name:'Opera',         where:'At or below the bust',                    note:'Dramatic. Can be doubled into a two-strand choker.' },
        { in:36, name:'Rope',          where:'Below the bust, at the waist',            note:'Very long; usually knotted or doubled.' },
    ];

    const neck = document.getElementById('neck');
    const unit = document.getElementById('unit');
    const neckline = document.getElementById('neckline');
    const goal = document.getElementById('goal');
    const rec = document.getElementById('rec');
    const lengths = document.getElementById('lengths');

    const NECKLINE_PICK = {
        crew:       [20, 'Sits clear of a high neckline instead of fighting with it.'],
        v:          [18, 'Follow the V — a princess length echoes the neckline without crowding it.'],
        scoop:      [16, 'A choker length traces the curve of a scoop neck neatly.'],
        strapless:  [16, 'Short lengths keep the focus on bare shoulders.'],
        collar:     [18, 'Sits just below an open collar; longer disappears into the shirt.'],
        turtle:     [24, 'Long enough to read against the fabric rather than vanish into it.'],
    };

    function render() {
        const isCm = unit.value === 'cm';
        const neckIn = (parseFloat(neck.value) || 14) / (isCm ? 2.54 : 1);

        let [base, why] = NECKLINE_PICK[neckline.value];

        // A larger neck needs proportionally more length to sit the same way.
        const adjust = Math.round((neckIn - 14) / 2) * 2;
        let pick = base + Math.max(0, adjust);

        let extra = '';
        if (goal.value === 'layer') {
            extra = ' For layering, buy this plus lengths 2–4 inches apart (for example ' + pick + '", ' + (pick + 4) + '" and ' + (pick + 8) + '") so each piece reads separately.';
        } else if (goal.value === 'pendant') {
            extra = ' With a pendant, remember the drop adds 1–2 inches below where the chain itself sits.';
        }

        rec.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC">' +
            '<p class="text-xs uppercase tracking-wider text-[color:var(--stone)]">Suggested length</p>' +
            '<p class="font-display text-3xl font-semibold mt-1" style="color:var(--gold)">' + pick + '"' +
            ' <span class="text-lg text-[color:var(--stone)]">(' + Math.round(pick * 2.54) + ' cm)</span></p>' +
            '<p class="text-sm mt-2">' + why + extra + '</p></div>';

        lengths.innerHTML = LENGTHS.map(function (l) {
            const on = l.in === pick;
            return '<div class="flex items-center gap-4 p-3 border" style="border-color:' + (on ? 'var(--gold)' : 'var(--line)') + ';background:' + (on ? '#FBF7EC' : '#fff') + '">' +
                '<div class="w-16 text-right font-display text-xl font-semibold" style="color:var(--gold)">' + l.in + '"</div>' +
                '<div class="flex-1"><div class="font-medium">' + l.name + '</div>' +
                '<div class="text-xs text-[color:var(--stone)]">' + l.where + ' — ' + l.note + '</div></div>' +
                '<div class="text-xs text-[color:var(--stone)] whitespace-nowrap">' + Math.round(l.in * 2.54) + ' cm</div></div>';
        }).join('');
    }

    [neck, unit, neckline, goal].forEach(function (e) {
        e.addEventListener('input', render);
        e.addEventListener('change', render);
    });
    render();
})();
</script>
@endpush
