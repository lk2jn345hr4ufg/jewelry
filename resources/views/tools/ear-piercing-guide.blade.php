@extends('layouts.app')

@section('title', 'Ear Piercing Guide — Placements, Healing Times & Aftercare — Gleamion.com')
@section('meta_description', 'Compare ear piercing placements on pain, healing time and difficulty, with evidence-based aftercare and what to avoid.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Ear Piercing Guide</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Cartilage piercings take far longer to heal than most people expect — often a year or more. Plan around that, not around the two weeks a lobe takes.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium mb-1" for="tolerance">Pain tolerance</label>
                <select id="tolerance" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="low">Low — keep it gentle</option>
                    <option value="mid" selected>Average</option>
                    <option value="high">High — not a concern</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="patience">How long can you wait to heal?</label>
                <select id="patience" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="2">A couple of months</option>
                    <option value="6" selected>Up to six months</option>
                    <option value="18">A year or more is fine</option>
                </select>
            </div>
        </div>
        <div id="list" class="mt-6 space-y-2"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">Aftercare that actually works</h2>
        <ul class="space-y-2 text-sm text-[color:var(--stone)]">
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Sterile saline, twice a day.</strong> That is the whole routine. Spray or soak, then pat dry with clean gauze.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Do not twist or rotate it.</strong> Old advice, now known to tear healing tissue and prolong everything.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Skip alcohol, peroxide and tea tree oil.</strong> All kill the new cells you are trying to grow.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Choose a needle piercer, not a gun.</strong> Guns blunt-force the tissue and cannot be properly sterilised — they are unsuitable for cartilage entirely.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Implant-grade titanium for the initial piece.</strong> Nickel in cheap studs is a common cause of a piercing that never settles.</span></li>
        </ul>
        <p class="text-xs text-[color:var(--stone)] mt-4 border-t border-[color:var(--line)] pt-4">
            Some swelling and clear crusting is normal for weeks. Spreading redness, heat, throbbing pain, yellow-green
            discharge or fever are not — see a doctor rather than waiting it out, and do not remove the jewelry first,
            as that can trap infection inside.
        </p>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const P = [
        { name:'Lobe', pain:1, heal:2, where:'The soft lower ear', note:'The easiest piercing there is. Heals fast, takes almost any jewelry, and can be stretched later if you want.' },
        { name:'Upper lobe', pain:1, heal:3, where:'Higher on the soft lobe', note:'Nearly as easy as a standard lobe. Good for building a curated stack without touching cartilage.' },
        { name:'Helix', pain:3, heal:9, where:'Outer upper rim', note:'The classic cartilage piercing. Straightforward but slow to heal — and sleeping on that side will set you back weeks.' },
        { name:'Forward helix', pain:3, heal:9, where:'Front of the ear, near the face', note:'Sits on a tight curve of cartilage. Looks excellent in a triple stack; needs a skilled piercer for correct angling.' },
        { name:'Flat', pain:3, heal:9, where:'Flat area below the upper rim', note:'A broad canvas that suits decorative studs. Prone to irritation bumps if knocked.' },
        { name:'Tragus', pain:3, heal:8, where:'The small flap over the canal', note:'Less painful than it looks. Awkward with earphones during healing — plan for that.' },
        { name:'Conch', pain:4, heal:10, where:'The large inner bowl', note:'Suits both studs and large hoops. Thick cartilage means a longer heal and a firmer pressure sensation.' },
        { name:'Daith', pain:4, heal:9, where:'Inner fold above the canal', note:'Widely claimed to help migraines — the evidence for that does not hold up, so choose it because you like how it looks.' },
        { name:'Rook', pain:4, heal:10, where:'Ridge in the upper inner ear', note:'Pierces a thick fold of cartilage. Among the more painful placements and slowest to settle.' },
        { name:'Industrial', pain:5, heal:12, where:'A bar spanning two helix holes', note:'Two cartilage piercings joined by one bar. Striking, but the most trouble-prone — the bar transmits every knock to both holes.' },
        { name:'Snug', pain:5, heal:12, where:'Inner ridge, mid-ear', note:'The most demanding placement. Not everyone’s anatomy supports it — a good piercer will turn you away if yours does not.' },
    ];

    const tol = document.getElementById('tolerance');
    const pat = document.getElementById('patience');
    const list = document.getElementById('list');

    function dots(n, max, colour) {
        let d = '';
        for (let i = 1; i <= max; i++) d += '<span class="facet-sm" style="background:' + (i <= n ? colour : 'var(--line)') + '"></span>';
        return '<span class="flex gap-1">' + d + '</span>';
    }

    function render() {
        const maxPain = { low:2, mid:4, high:5 }[tol.value];
        const maxHeal = parseInt(pat.value, 10);

        list.innerHTML = P.map(function (p) {
            const ok = p.pain <= maxPain && p.heal <= maxHeal;
            return '<div class="border p-4" style="border-color:' + (ok ? 'var(--champagne)' : 'var(--line)') + ';opacity:' + (ok ? '1' : '0.5') + '">' +
                '<div class="flex flex-wrap gap-4 justify-between items-start">' +
                '<div><h3 class="font-display text-lg font-semibold">' + p.name + '</h3>' +
                '<p class="text-xs text-[color:var(--stone)]">' + p.where + '</p></div>' +
                '<div class="space-y-1 text-xs">' +
                '<div class="flex items-center gap-2"><span class="w-12 text-[color:var(--stone)]">Pain</span>' + dots(p.pain, 5, 'var(--gold)') + '</div>' +
                '<div class="flex items-center gap-2"><span class="w-12 text-[color:var(--stone)]">Healing</span><span>' + p.heal + ' months</span></div>' +
                '</div></div>' +
                '<p class="text-sm text-[color:var(--stone)] mt-2">' + p.note + '</p></div>';
        }).join('');
    }

    tol.addEventListener('change', render);
    pat.addEventListener('change', render);
    render();
})();
</script>
@endpush
