@extends('layouts.app')

@section('title', 'Watch Movement Guide — Quartz vs Automatic vs Manual — Gleamion.com')
@section('meta_description', 'Understand watch movements: quartz, automatic, manual, solar and spring drive. Accuracy, servicing costs and which suits how you actually live.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Watch Movement Guide</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">The movement decides accuracy, upkeep cost and whether the watch still runs when you pick it up after a month in a drawer.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium mb-1" for="how">How you’d wear it</label>
                <select id="how" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="daily" selected>Daily, the only watch I own</option>
                    <option value="rotate">One of several, worn occasionally</option>
                    <option value="rare">Special occasions only</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="care">Attitude to upkeep</label>
                <select id="care" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="none" selected>I want zero maintenance</option>
                    <option value="some">Happy to service occasionally</option>
                    <option value="enjoy">I enjoy the ritual of it</option>
                </select>
            </div>
        </div>
        <div id="rec" class="mt-6"></div>
        <div id="list" class="mt-6 space-y-3"></div>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const MOVES = [
        { key:'quartz', name:'Quartz (battery)', accuracy:'±15 sec/month', service:'Battery every 2–4 years, ~$15',
          upkeep:5, ritual:1, forever:1,
          note:'A battery drives a quartz crystal oscillating 32,768 times a second. Far more accurate than any mechanical watch and effectively maintenance-free. The trade-off is emotional rather than practical — there is no craft to admire, and the movement is usually replaced rather than repaired.' },
        { key:'auto', name:'Automatic (self-winding)', accuracy:'−20 to +40 sec/day', service:'Full service every 5–10 years, $200–800',
          upkeep:3, ritual:4, forever:5,
          note:'A weighted rotor winds the mainspring as your wrist moves. Runs indefinitely if worn regularly, stops after 40–70 hours off the wrist. The mainstream choice for mechanical watches, and repairable essentially forever.' },
        { key:'manual', name:'Manual wind', accuracy:'−20 to +40 sec/day', service:'Every 5–10 years, $200–700',
          upkeep:2, ritual:5, forever:5,
          note:'You wind it yourself, usually daily. Thinner than automatics since there is no rotor, and the daily ritual is the appeal. Forget for two days and it stops — which some find charming and others find tedious.' },
        { key:'solar', name:'Solar quartz', accuracy:'±15 sec/month', service:'Capacitor every 10–20 years',
          upkeep:5, ritual:1, forever:2,
          note:'Quartz accuracy with light-charged power — no battery changes for a decade or more. Practical and unglamorous; excellent for a watch you want to ignore completely.' },
        { key:'springdrive', name:'Spring Drive', accuracy:'±1 sec/day', service:'Every 3–5 years, $500+',
          upkeep:2, ritual:4, forever:4,
          note:'Seiko’s hybrid: a mechanical mainspring regulated electronically. Quartz-level accuracy with a mechanical soul and a uniquely smooth gliding seconds hand. Expensive and only serviceable by the maker.' },
        { key:'smart', name:'Smartwatch', accuracy:'Perfect (network synced)', service:'Battery degrades in 3–5 years',
          upkeep:4, ritual:1, forever:0,
          note:'Functionally excellent and entirely disposable — obsolete within a few years and unrepairable. A tool rather than a piece of jewelry, and not something anyone inherits.' },
    ];

    const how = document.getElementById('how');
    const care = document.getElementById('care');
    const rec = document.getElementById('rec');
    const list = document.getElementById('list');

    function bar(label, n) {
        let d = '';
        for (let i = 1; i <= 5; i++) d += '<span class="facet-sm" style="background:' + (i <= n ? 'var(--gold)' : 'var(--line)') + '"></span>';
        return '<div class="flex items-center gap-2 text-xs"><span class="w-24 text-[color:var(--stone)]">' + label + '</span><span class="flex gap-1">' + d + '</span></div>';
    }

    function render() {
        const scored = MOVES.map(function (m) {
            let s = 0;
            if (care.value === 'none') s += m.upkeep * 1.5;
            if (care.value === 'some') s += m.upkeep * 0.6 + m.forever * 0.6;
            if (care.value === 'enjoy') s += m.ritual * 1.5 + m.forever * 0.8;
            if (how.value === 'daily') s += (m.key === 'auto' || m.key === 'quartz' || m.key === 'solar') ? 2 : 0;
            if (how.value === 'rotate') s += (m.key === 'quartz' || m.key === 'solar' || m.key === 'manual') ? 1.5 : 0;
            if (how.value === 'rare') s += (m.key === 'quartz' || m.key === 'solar') ? 2 : 0;
            return { m: m, s: s };
        }).sort(function (a, b) { return b.s - a.s; });

        const best = scored[0].m;
        let extra = '';
        if (how.value === 'rare' && (best.key === 'auto' || best.key === 'manual')) {
            extra = ' Note that a mechanical watch worn rarely will always need resetting before you wear it.';
        }

        rec.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC">' +
            '<p class="text-xs uppercase tracking-wider text-[color:var(--stone)]">Suggested movement</p>' +
            '<p class="font-display text-2xl font-semibold" style="color:var(--gold)">' + best.name + '</p>' +
            '<p class="text-sm mt-2">' + best.note + extra + '</p></div>';

        list.innerHTML = scored.map(function (o) {
            return '<div class="border border-[color:var(--line)] p-4">' +
                '<div class="flex flex-wrap gap-4 justify-between items-start">' +
                '<div><h3 class="font-display text-lg font-semibold">' + o.m.name + '</h3>' +
                '<p class="text-xs text-[color:var(--stone)]">' + o.m.accuracy + ' · ' + o.m.service + '</p></div>' +
                '<div class="space-y-1">' + bar('Low upkeep', o.m.upkeep) + bar('Ritual/charm', o.m.ritual) + bar('Lasts forever', o.m.forever) + '</div></div>' +
                '<p class="text-sm text-[color:var(--stone)] mt-2">' + o.m.note + '</p></div>';
        }).join('');
    }

    how.addEventListener('change', render);
    care.addEventListener('change', render);
    render();
})();
</script>
@endpush
