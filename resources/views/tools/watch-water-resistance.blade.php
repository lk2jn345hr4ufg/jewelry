@extends('layouts.app')

@section('title', 'Watch Water Resistance Decoder — What ATM Really Means — Gleamion.com')
@section('meta_description', 'Find out what your watch’s water resistance rating actually allows. 30m does not mean you can swim in it — here is what each rating really permits.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Watch Water Resistance Decoder</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Ratings are tested in still water at fixed pressure — not while moving. Real-world limits are far lower than the number suggests.</p>

    <div class="card p-6 mt-6">
        <label class="block text-sm font-medium mb-1" for="rating">Your watch’s rating</label>
        <select id="rating" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
            <option value="0">No marking / "water resistant" only</option>
            <option value="30">30 m / 3 ATM</option>
            <option value="50" selected>50 m / 5 ATM</option>
            <option value="100">100 m / 10 ATM</option>
            <option value="200">200 m / 20 ATM</option>
            <option value="300">300 m / 30 ATM+ (dive rated)</option>
        </select>

        <div id="activities" class="grid gap-2 sm:grid-cols-2 mt-6"></div>
        <div id="note" class="mt-4"></div>

        <p class="text-xs text-[color:var(--stone)] mt-5 border-t border-[color:var(--line)] pt-4">
            Gaskets perish with age and heat. A ten-year-old watch does not hold its original rating unless it has been
            pressure tested and resealed — budget for a test every two years if you swim with it. <strong>Never operate
            the crown or pushers underwater</strong>, and screw down the crown fully before any contact with water.
        </p>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const ACTS = [
        ['Rain and splashes', 30],
        ['Hand washing', 50],
        ['Showering', 100],
        ['Swimming (pool, surface)', 100],
        ['Snorkelling', 100],
        ['Water sports, jet ski', 200],
        ['Scuba diving', 200],
        ['Saturation / deep diving', 300],
    ];

    const NOTES = {
        0:'An unmarked watch or one simply labelled “water resistant” should be kept away from water entirely. Treat rain as the maximum exposure.',
        30:'Despite the number, 30 m does not mean 30 metres of swimming. It survives rain and an accidental splash only — remove it before washing your hands.',
        50:'Fine for hand washing and rain, but not for swimming. The pressure of an arm stroke through water briefly exceeds the static test rating.',
        100:'The practical minimum for swimming. Comfortable for pool and surface use, though repeated hot showers degrade gaskets faster than cold water.',
        200:'A genuine sports rating. Suitable for swimming, snorkelling and recreational diving, provided the crown screws down and seals are current.',
        300:'ISO dive-rated territory. Built for saturation and deep work, with a screw-down crown, tested case back and usually a helium escape valve.',
    };

    const rating = document.getElementById('rating');
    const acts = document.getElementById('activities');
    const note = document.getElementById('note');

    function render() {
        const r = parseInt(rating.value, 10);
        acts.innerHTML = ACTS.map(function (a) {
            const ok = r >= a[1];
            const c = ok ? 'var(--gold)' : '#C0563A';
            return '<div class="flex items-center gap-3 p-3 border" style="border-color:' + (ok ? 'var(--champagne)' : 'var(--line)') + '">' +
                '<span class="facet-sm shrink-0" style="background:' + c + '"></span>' +
                '<span class="text-sm ' + (ok ? '' : 'text-[color:var(--stone)]') + '">' + a[0] + '</span>' +
                '<span class="ml-auto text-xs" style="color:' + c + '">' + (ok ? 'Safe' : 'Avoid') + '</span></div>';
        }).join('');
        note.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC"><p class="text-sm">' + NOTES[r] + '</p></div>';
    }

    rating.addEventListener('change', render);
    render();
})();
</script>
@endpush
