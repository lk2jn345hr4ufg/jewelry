@extends('layouts.app')
@section('title', 'Sapphire Colour & Origin Guide — Gleamion.com')
@section('meta_description', 'Compare sapphire colours and origins — Kashmir, Burma, Ceylon, Madagascar — and learn which factors actually drive price.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Sapphire Colour &amp; Origin Guide</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Sapphire is corundum in every colour except red — that is ruby. Colour and origin drive price far more than carat weight.</p>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-4">Colours</h2>
        <div id="colours" class="grid gap-3 sm:grid-cols-2"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-4">Origins and what they mean for price</h2>
        <div id="origins" class="space-y-2"></div>
        <p class="text-xs text-[color:var(--stone)] mt-4 border-t border-[color:var(--line)] pt-4">
            Origin premiums are substantial and origin is determined by trace-element analysis, not by a seller's word.
            For any stone where origin affects the price materially, insist on a report from a recognised lab —
            and be aware that lab opinions on origin occasionally differ.
        </p>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const C = [
        ['Cornflower blue','#4A6FBF','The benchmark. Medium-strong, slightly violet blue with high saturation. Commands the highest prices of any sapphire colour.'],
        ['Royal blue','#2B4A9B','Deeper and more intense. Prized, though very dark stones lose life and are worth less than they look.'],
        ['Padparadscha','#F09A6B','A rare pink-orange found mainly in Sri Lanka. Among the most expensive sapphires per carat; heavily imitated by treated stones.'],
        ['Pink','#E8809E','Increasingly popular as an engagement alternative. Deep pink shades border on ruby, which raises price sharply.'],
        ['Yellow','#EFC85B','Warm and affordable relative to blue. Most are heat-treated to strengthen colour.'],
        ['Green','#6E9B70','Undervalued and widely available. Rarely intense; often a good-value durable stone.'],
        ['White / colourless','#F2F2EE','Used as a diamond alternative. Durable but noticeably less brilliant — reads glassy rather than sparkly.'],
        ['Colour-change','#7A6BAF','Blue in daylight, purple under incandescent light. Rare and priced for the phenomenon.'],
        ['Star sapphire','#5A6B8C','Shows a six-rayed star from rutile inclusions. Valued on the sharpness and centring of the star.'],
    ];
    const O = [
        ['Kashmir','Extraordinary','Mined out since the 1930s. A velvety cornflower blue that defines the ideal. Certified Kashmir stones sell at many multiples of equivalent material — often $50,000+ per carat.'],
        ['Burma (Myanmar)','Very high','Rich royal blue with excellent saturation. Strong premium, though ethical sourcing concerns are real and worth asking about.'],
        ['Ceylon (Sri Lanka)','High','Bright, lively lighter blues and the source of most padparadscha. The most common origin in fine jewelry and generally well regarded.'],
        ['Madagascar','Moderate','Now a major source producing material comparable to Ceylon. Often better value for identical appearance.'],
        ['Thailand / Cambodia','Moderate','Historically important, typically darker stones. Bangkok remains the world centre for heat treatment and trading.'],
        ['Australia','Lower','Often very dark blue or green-blue. Inexpensive and commonly used in commercial jewelry.'],
        ['Montana (USA)','Moderate','Distinctive steely blues and teals. Popular for ethically traceable sourcing, which carries its own premium.'],
    ];
    document.getElementById('colours').innerHTML = C.map(function (c) {
        return '<div class="border border-[color:var(--line)] p-4 flex gap-3">' +
            '<div style="width:36px;height:36px;border-radius:50%;background:' + c[1] + ';border:1px solid #bbb;flex:none"></div>' +
            '<div><h3 class="font-medium">' + c[0] + '</h3><p class="text-sm text-[color:var(--stone)] mt-1">' + c[2] + '</p></div></div>';
    }).join('');
    document.getElementById('origins').innerHTML = O.map(function (o) {
        return '<div class="border border-[color:var(--line)] p-4">' +
            '<div class="flex flex-wrap items-baseline gap-3"><span class="font-display text-lg font-semibold">' + o[0] + '</span>' +
            '<span class="text-xs px-2 py-0.5 border" style="border-color:var(--gold);color:var(--gold)">' + o[1] + ' premium</span></div>' +
            '<p class="text-sm text-[color:var(--stone)] mt-1">' + o[2] + '</p></div>';
    }).join('');
})();
</script>
@endpush
