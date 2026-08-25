@extends('layouts.app')
@section('title', 'Charm Bracelet Guide — Systems, Capacity & Building a Collection — Gleamion.com')
@section('meta_description', 'Compare charm bracelet systems, work out how many charms fit your wrist, and plan a collection that stays wearable.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Charm Bracelet Guide</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">The best gift that keeps giving — you can add to it every year. Just start with the right system, because they rarely mix.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-3">
            <div>
                <label class="block text-sm font-medium mb-1" for="wrist">Wrist (inches)</label>
                <input id="wrist" type="number" min="5" max="9" step="0.25" value="6.5" class="w-full border border-[color:var(--line)] px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="system">System</label>
                <select id="system" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="bead" selected>Threaded bead (Pandora-style)</option>
                    <option value="traditional">Traditional link &amp; dangle</option>
                    <option value="italian">Italian modular links</option>
                    <option value="slider">Slider / mesh</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="fill">How full?</label>
                <select id="fill" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="0.5">Sparse — a few meaningful pieces</option>
                    <option value="0.75" selected>Comfortably filled</option>
                    <option value="1">Completely full</option>
                </select>
            </div>
        </div>
        <div id="out" class="grid gap-3 sm:grid-cols-3 mt-6"></div>
        <div id="note" class="mt-4"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">Practical points</h2>
        <ul class="space-y-2 text-sm text-[color:var(--stone)]">
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Systems are not interchangeable.</strong> Threaded beads only fit their own brand’s bracelet thread. Choose the system before buying charms, not after.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Buy the bracelet one size larger</strong> than a plain bracelet. Charms take up circumference and pull the fit tighter as you add them.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">A full bracelet is heavy and noisy.</strong> Many people find 60–75% coverage more wearable day to day.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Check the clasp yearly.</strong> Charm bracelets are heavy and clasps fail — it is the most common way an entire collection is lost at once.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Use safety chains and stoppers.</strong> On traditional bracelets a safety chain is essential; on bead systems, clips stop charms sliding into a clump.</span></li>
        </ul>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const SYS = {
        bead: { w:9, ease:1.0, note:'Threaded beads screw along the bracelet thread. Charms are chunky and each takes about 9 mm. The most popular system, and widely available — but strictly brand-specific.' },
        traditional: { w:14, ease:0.75, note:'Dangling charms attached with jump rings or lobster clasps. The classic form, endlessly variable, and charms from any maker will fit. They jingle and can catch on fabric.' },
        italian: { w:9, ease:0.5, note:'Flat modular links that sit flush and form a continuous band. Discreet and snag-free, but the bracelet is sized exactly to your wrist and links must be added or removed by a jeweler.' },
        slider: { w:11, ease:0.75, note:'Charms slide onto a mesh or leather band. Comfortable and quiet; the band itself wears out before the charms do.' },
    };
    const wrist = document.getElementById('wrist'), system = document.getElementById('system'), fill = document.getElementById('fill');
    const out = document.getElementById('out'), note = document.getElementById('note');
    function card(l, v, s) {
        return '<div class="border border-[color:var(--line)] p-4 text-center">' +
            '<div class="text-xs uppercase tracking-wider text-[color:var(--stone)]">' + l + '</div>' +
            '<div class="font-display text-2xl font-semibold mt-1" style="color:var(--gold)">' + v + '</div>' +
            (s ? '<div class="text-xs text-[color:var(--stone)] mt-1">' + s + '</div>' : '') + '</div>';
    }
    function render() {
        const w = parseFloat(wrist.value) || 6.5;
        const s = SYS[system.value];
        const braceletIn = w + s.ease;
        const usableMm = braceletIn * 25.4 - 25;
        const max = Math.floor(usableMm / s.w);
        const planned = Math.max(1, Math.round(max * parseFloat(fill.value)));
        out.innerHTML =
            card('Bracelet length', braceletIn.toFixed(2) + '"', Math.round(braceletIn * 25.4) + ' mm') +
            card('Maximum charms', max, 'completely full') +
            card('Suggested', planned, 'for this look');
        note.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC">' +
            '<p class="text-sm">' + s.note + '</p>' +
            '<p class="text-sm mt-2">At ' + planned + ' charms the bracelet will weigh roughly ' + Math.round(planned * 2.5) + '–' + Math.round(planned * 4) + ' g in silver. ' +
            'If it is a gift, start with two or three charms rather than a full bracelet — the point is having something to add each year.</p></div>';
    }
    [wrist, system, fill].forEach(function (e) { e.addEventListener('input', render); e.addEventListener('change', render); });
    render();
})();
</script>
@endpush
