@extends('layouts.app')

@section('title', 'Pearl Buying Guide — Types, Grading & Value — Gleamion.com')
@section('meta_description', 'Compare Akoya, freshwater, Tahitian and South Sea pearls, understand grading, and learn how to spot an imitation in seconds.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Pearl Buying Guide</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Pearl pricing spans two orders of magnitude. Knowing the type tells you most of what you need.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium mb-1" for="budget">Budget for a strand</label>
                <select id="budget" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="1">Under $200</option>
                    <option value="2" selected>$200 – $1,000</option>
                    <option value="3">$1,000 – $5,000</option>
                    <option value="4">$5,000+</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="look">Look you want</label>
                <select id="look" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="any" selected>Any</option>
                    <option value="classic">Classic white</option>
                    <option value="dark">Dark / dramatic</option>
                    <option value="warm">Warm / golden</option>
                    <option value="unusual">Unusual shapes</option>
                </select>
            </div>
        </div>
        <div id="types" class="mt-6 space-y-3"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">Judging quality — in order of importance</h2>
        <ol class="space-y-2 text-sm text-[color:var(--stone)] list-decimal pl-5">
            <li><strong class="text-[color:#23262e]">Lustre.</strong> The single biggest factor. Hold the pearl near a window — you should see a sharp reflection of the light source, not a diffuse glow. Chalky pearls are cheap for a reason and never improve.</li>
            <li><strong class="text-[color:#23262e]">Surface.</strong> Some blemishing is normal and expected. Pits and cracks are not.</li>
            <li><strong class="text-[color:#23262e]">Shape.</strong> Perfectly round commands the highest price, but baroque shapes are increasingly fashionable and much better value.</li>
            <li><strong class="text-[color:#23262e]">Nacre thickness.</strong> Thin nacre peels within years. If you can see the bead through the drill hole, walk away.</li>
            <li><strong class="text-[color:#23262e]">Matching.</strong> On a strand, consistency of size and colour costs real money to achieve.</li>
        </ol>
        <div class="border-l-4 p-4 mt-4" style="border-color:var(--gold); background:#FBF7EC">
            <p class="text-sm"><strong>The tooth test.</strong> Rub a pearl gently against the edge of your front tooth. Real nacre feels faintly gritty; glass and plastic feel perfectly smooth. It takes two seconds and catches most imitations.</p>
        </div>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const PEARLS = [
        { name:'Freshwater', budget:1, looks:['classic','warm','unusual'], size:'6–10 mm', origin:'China, lakes and rivers',
          note:'The most affordable and the most produced. Modern freshwater quality has improved enormously — good strands now rival Akoya at a third of the price. Shapes range from near-round to wildly baroque.' },
        { name:'Akoya', budget:2, looks:['classic'], size:'6–9 mm', origin:'Japan and China, saltwater',
          note:'The classic white pearl necklace. Prized for sharp, mirror-like lustre and consistent roundness. Smaller than South Sea but the most reflective of all types.' },
        { name:'Tahitian', budget:3, looks:['dark'], size:'8–14 mm', origin:'French Polynesia',
          note:'Naturally dark — grey, green, aubergine and peacock. Never actually black despite the name; anything sold as jet black has been dyed. Large and dramatic.' },
        { name:'South Sea (white)', budget:4, looks:['classic'], size:'9–20 mm', origin:'Australia, Indonesia',
          note:'The largest and most valuable cultured pearls. Thick nacre gives a soft satin glow rather than Akoya’s sharp shine. A well-matched strand is a serious investment.' },
        { name:'South Sea (golden)', budget:4, looks:['warm'], size:'9–16 mm', origin:'Philippines, Indonesia',
          note:'Naturally golden from the gold-lipped oyster. Deep natural gold commands the highest prices of any pearl — and is widely imitated with dye.' },
        { name:'Keshi', budget:2, looks:['unusual'], size:'Varies', origin:'By-product of culturing',
          note:'Formed without a bead nucleus, so they are solid nacre — often intensely lustrous. Irregular shapes, and excellent value for the quality of shine.' },
        { name:'Mabe (blister)', budget:1, looks:['classic','unusual'], size:'10–20 mm', origin:'Grown against the shell',
          note:'Dome-shaped and hollow-backed, used in earrings and rings. Inexpensive and lustrous, but the thin dome cracks under pressure.' },
        { name:'Baroque', budget:1, looks:['unusual'], size:'Varies', origin:'Any type',
          note:'Deliberately irregular. Once considered seconds, now sought after for their character — and priced far below round pearls of identical nacre quality.' },
    ];

    const budget = document.getElementById('budget');
    const look = document.getElementById('look');
    const types = document.getElementById('types');

    function render() {
        const b = parseInt(budget.value, 10);
        const l = look.value;
        const list = PEARLS.filter(function (p) {
            return p.budget <= b && (l === 'any' || p.looks.includes(l));
        });

        types.innerHTML = list.length ? list.map(function (p) {
            return '<div class="border border-[color:var(--line)] p-4">' +
                '<div class="flex flex-wrap items-baseline gap-3">' +
                '<h3 class="font-display text-xl font-semibold">' + p.name + '</h3>' +
                '<span class="text-xs text-[color:var(--stone)]">' + p.size + ' · ' + p.origin + '</span></div>' +
                '<p class="text-sm text-[color:var(--stone)] mt-2">' + p.note + '</p></div>';
        }).join('') : '<p class="text-sm text-[color:var(--stone)]">Nothing in that range for that look — try widening the budget.</p>';
    }

    budget.addEventListener('change', render);
    look.addEventListener('change', render);
    render();
})();
</script>
@endpush
