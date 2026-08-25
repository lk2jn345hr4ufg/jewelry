@extends('layouts.app')

@section('title', 'Ring Resizing Guide — Cost, Limits & Feasibility — Gleamion.com')
@section('meta_description', 'Can your ring be resized, by how much, and what will it cost? Check by metal and setting before you take it to a jeweler.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Ring Resizing Guide</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Not every ring can be resized, and some should not be. Check before you buy or commit.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium mb-1" for="metal">Metal</label>
                <select id="metal" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="gold" selected>Gold (any karat)</option>
                    <option value="silver">Sterling silver</option>
                    <option value="platinum">Platinum</option>
                    <option value="palladium">Palladium</option>
                    <option value="titanium">Titanium</option>
                    <option value="tungsten">Tungsten carbide</option>
                    <option value="steel">Stainless steel</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="band">Band style</label>
                <select id="band" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="plain" selected>Plain band</option>
                    <option value="partial">Stones on the top half only</option>
                    <option value="eternity">Full eternity (stones all around)</option>
                    <option value="tension">Tension setting</option>
                    <option value="engraved">Engraved or patterned all around</option>
                    <option value="inlay">Wood, ceramic or inlay</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="change">Size change needed</label>
                <select id="change" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="0.5">Half size</option>
                    <option value="1" selected>1 size</option>
                    <option value="2">2 sizes</option>
                    <option value="3">3 or more sizes</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="dir">Direction</label>
                <select id="dir" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="down" selected>Smaller</option>
                    <option value="up">Larger</option>
                </select>
            </div>
        </div>

        <div id="verdict" class="mt-6"></div>
        <div id="cost" class="grid gap-3 sm:grid-cols-3 mt-4"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">Alternatives when resizing isn’t possible</h2>
        <ul class="space-y-2 text-sm text-[color:var(--stone)]">
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Sizing beads</strong> — two small beads soldered inside the band take it down about half a size and stop a loose ring spinning. Cheap and reversible.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">A spring insert or ring guard</strong> — adjusts fit without altering the ring, useful if your fingers change with heat or pregnancy.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Remaking the band</strong> — for eternity rings, a jeweler can build a new band and transfer the stones. Expensive, but preserves the piece.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Exchange</strong> — many retailers offer one free resize or exchange within 30–90 days. Ask before buying, not after.</span></li>
        </ul>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const METAL = {
        gold:      { ok:true,  base:40, note:'Gold is the easiest metal to resize — any jeweler can cut, add or remove metal and solder invisibly.' },
        silver:    { ok:true,  base:35, note:'Silver resizes easily and cheaply, though the solder seam can be faintly visible on high-polish bands.' },
        platinum:  { ok:true,  base:80, note:'Platinum requires higher temperatures and specialist equipment, so expect roughly double the cost of gold.' },
        palladium: { ok:true,  base:75, note:'Resizable, but fewer jewelers work with palladium — call ahead rather than turning up.' },
        titanium:  { ok:false, base:0,  note:'Titanium cannot be resized. It is too hard to stretch and cannot be soldered with conventional equipment. The ring must be replaced.' },
        tungsten:  { ok:false, base:0,  note:'Tungsten carbide cannot be resized at all — it is ceramic-hard and will shatter rather than bend. Most sellers offer a lifetime size-exchange programme instead; check yours.' },
        steel:     { ok:false, base:0,  note:'Stainless steel is technically resizable by a specialist, but few jewelers will attempt it and the cost usually exceeds the ring’s value.' },
    };

    const BAND = {
        plain:    { mult:1,   max:4, note:'' },
        partial:  { mult:1.3, max:2, note:'With stones on the top half only, the plain back can be adjusted safely — but no more than about two sizes before the proportions look wrong.' },
        eternity: { mult:0,   max:0, note:'A full eternity ring cannot be resized — there is no plain metal to cut. The band must be remade and the stones reset, which typically costs $400–1,200.' },
        tension:  { mult:0,   max:0, note:'Tension settings rely on precise spring pressure to hold the stone. Altering the band destroys that tension, so resizing is not possible.' },
        engraved: { mult:1.6, max:1, note:'All-round engraving or patterning will be interrupted at the seam. A skilled engraver can re-cut the pattern, but expect extra cost and an imperfect match.' },
        inlay:    { mult:0,   max:0, note:'Wood, ceramic and inlaid materials crack under the heat and pressure of resizing. Replacement is the only route.' },
    };

    const ids = ['metal','band','change','dir'];
    const el = {};
    ids.forEach(function (i) { el[i] = document.getElementById(i); });
    const verdict = document.getElementById('verdict');
    const cost = document.getElementById('cost');

    function card(label, value, sub) {
        return '<div class="border border-[color:var(--line)] p-4 text-center">' +
            '<div class="text-xs uppercase tracking-wider text-[color:var(--stone)]">' + label + '</div>' +
            '<div class="font-display text-2xl font-semibold mt-1" style="color:var(--gold)">' + value + '</div>' +
            (sub ? '<div class="text-xs text-[color:var(--stone)] mt-1">' + sub + '</div>' : '') + '</div>';
    }

    function render() {
        const m = METAL[el.metal.value];
        const b = BAND[el.band.value];
        const change = parseFloat(el.change.value);
        const up = el.dir.value === 'up';

        let possible = m.ok && b.mult > 0;
        let msg = '';
        let colour = 'var(--gold)';

        if (!m.ok) { possible = false; msg = m.note; colour = '#C0563A'; }
        else if (b.mult === 0) { possible = false; msg = b.note; colour = '#C0563A'; }
        else if (change > b.max) {
            possible = false; colour = '#C0563A';
            msg = 'A change of ' + change + ' sizes exceeds what this band can take safely (about ' + b.max + '). ' +
                  'Pushing further thins the metal dangerously or distorts the setting. Consider remaking the band instead.';
        } else {
            msg = m.note + (b.note ? ' ' + b.note : '') +
                (up ? ' Sizing up adds metal, so expect a small materials charge on top of labour.'
                    : ' Sizing down removes metal — ask the jeweler to return the offcut, it is yours.');
        }

        verdict.innerHTML = '<div class="border-l-4 p-4" style="border-color:' + colour + '; background:#FBF7EC">' +
            '<p class="font-display text-2xl font-semibold" style="color:' + colour + '">' +
            (possible ? 'Yes — resizable' : 'Not resizable') + '</p>' +
            '<p class="text-sm mt-2">' + msg + '</p></div>';

        if (possible) {
            const low = Math.round(m.base * b.mult * (change > 1 ? 1.4 : 1));
            const high = Math.round(low * 1.9);
            cost.innerHTML =
                card('Estimated cost', '$' + low + '–' + high, 'typical jeweler') +
                card('Turnaround', change > 1 ? '3–10 days' : '1–5 days', 'longer at busy periods') +
                card('Max safe change', '±' + b.max + ' sizes', 'for this band style');
        } else {
            cost.innerHTML = '';
        }
    }

    ids.forEach(function (i) { el[i].addEventListener('change', render); });
    render();
})();
</script>
@endpush
