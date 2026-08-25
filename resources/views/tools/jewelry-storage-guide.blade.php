@extends('layouts.app')
@section('title', 'Jewelry Storage Guide — Protect What You Own — Gleamion.com')
@section('meta_description', 'How to store jewelry so it does not scratch, tarnish or tangle — by material, plus travel and security advice.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Jewelry Storage Guide</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Most jewelry damage happens in the box, not on the body — diamonds scratching gold, silver tarnishing in humid air, chains knotting themselves solid.</p>

    <div class="card p-6 mt-6">
        <label class="block text-sm font-medium mb-1" for="what">What are you storing?</label>
        <select id="what" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white max-w-md"></select>
        <div id="out" class="mt-6"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">Travelling with jewelry</h2>
        <ul class="space-y-2 text-sm text-[color:var(--stone)]">
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Never in checked luggage.</strong> Most airline liability policies exclude jewelry entirely, and most travel insurance caps it very low. Carry it on your person or in hand luggage.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Thread chains through a drinking straw</strong> before packing, and clasp them. It is the cheapest anti-tangle solution there is.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Photograph everything before you go.</strong> If something is lost, a photo plus a receipt is the difference between a paid claim and an argument.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Consider leaving the good pieces home.</strong> Hotel safes are not as secure as they appear, and a lost heirloom cannot be replaced at any price.</span></li>
        </ul>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const S = {
        'Diamonds and hard stones':{
            how:'Individually, in soft pouches or a lined box with separate compartments.',
            why:'Diamond scratches everything softer than itself — which is everything, including other diamonds. A single loose diamond in a shared drawer will mark every piece it touches.',
            avoid:['Sharing a compartment with anything','Loose in a drawer or dish'],
        },
        'Sterling silver':{
            how:'In a sealed zip bag with an anti-tarnish strip, in a dry drawer.',
            why:'Tarnish is a reaction with sulphur compounds in air. Removing the air removes the problem almost entirely — sealed silver stays bright for years.',
            avoid:['Bathrooms and anywhere humid','Rubber bands and newspaper, which accelerate tarnish','Open jewelry trees, however attractive'],
        },
        'Pearls and opals':{
            how:'Flat, in a soft cloth pouch, somewhere neither hot nor bone-dry.',
            why:'Both contain water and can dry out and crack. Pearls also need air, so never seal them in plastic — a breathable pouch is right.',
            avoid:['Airtight plastic bags','Safes with desiccant packs','Hanging strands, which stretch the silk over time'],
        },
        'Chains and necklaces':{
            how:'Hanging, or laid flat and fully clasped in individual slots.',
            why:'Chains tangle because they are loose and free at one end. Clasping them closed solves most of it; hanging solves the rest.',
            avoid:['Piling several chains together','Storing unclasped','Coiling tightly, which sets kinks in snake and herringbone chains'],
        },
        'Watches':{
            how:'In their own boxes, or a watch roll. Automatic watches you wear rarely can go on a winder.',
            why:'Case backs and bracelets scratch other pieces and each other. A winder keeps an automatic running so complications do not need resetting, though it is a convenience, not a requirement.',
            avoid:['Storing near magnets, including speakers and laptop closures','Leaving leather straps compressed and folded'],
        },
        'Costume and plated':{
            how:'Dry, separate from fine jewelry, in a compartmented box.',
            why:'Plating wears through where pieces rub. Base metals also corrode and can transfer marks onto softer precious metals stored alongside.',
            avoid:['Humidity of any kind','Storing against silver or gold'],
        },
    };
    const what = document.getElementById('what'), out = document.getElementById('out');
    Object.keys(S).forEach(function (k) { const o = document.createElement('option'); o.value = k; o.textContent = k; what.appendChild(o); });
    function render() {
        const s = S[what.value];
        out.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC">' +
            '<p class="text-xs uppercase tracking-wider text-[color:var(--stone)]">How to store it</p>' +
            '<p class="font-display text-xl font-semibold mt-1" style="color:var(--gold)">' + s.how + '</p>' +
            '<p class="text-sm mt-2">' + s.why + '</p></div>' +
            '<div class="border border-[color:var(--line)] p-4 mt-3"><p class="font-medium text-sm mb-2">Avoid</p><ul class="space-y-2">' +
            s.avoid.map(function (a) {
                return '<li class="flex gap-3 text-sm"><span class="facet-sm mt-1.5 shrink-0" style="background:#C0563A"></span><span>' + a + '</span></li>';
            }).join('') + '</ul></div>';
    }
    what.addEventListener('change', render);
    render();
})();
</script>
@endpush
