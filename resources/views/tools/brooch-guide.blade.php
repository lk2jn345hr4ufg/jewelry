@extends('layouts.app')
@section('title', 'Brooch & Pin Guide — Types, Placement & Fabric — Gleamion.com')
@section('meta_description', 'Choose and wear a brooch: clasp types, where to place it, and which fabrics a pin will damage.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Brooch &amp; Pin Guide</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">The most underrated category in jewelry — vintage brooches are consistently the best value in the antique market, because fashion moved on and prices did not follow it back.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium mb-1" for="garment">What you are pinning it to</label>
                <select id="garment" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="coat" selected>Coat or heavy jacket</option>
                    <option value="blazer">Blazer or structured jacket</option>
                    <option value="knit">Knitwear</option>
                    <option value="silk">Silk or fine blouse</option>
                    <option value="scarf">Scarf or wrap</option>
                    <option value="bag">Bag or hat</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="weight">Brooch size</label>
                <select id="weight" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="small">Small — under 3 cm</option>
                    <option value="medium" selected>Medium — 3–5 cm</option>
                    <option value="large">Large — over 5 cm</option>
                </select>
            </div>
        </div>
        <div id="out" class="mt-6"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">Clasp types</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="text-left border-b border-[color:var(--line)]">
                    <th class="py-2 pr-4">Clasp</th><th class="py-2 pr-4">Era</th><th class="py-2">Security</th>
                </tr></thead>
                <tbody class="text-[color:var(--stone)]">
                    <tr class="border-b border-[color:var(--line)]"><td class="py-2 pr-4 font-medium text-[color:#23262e]">C-clasp</td><td class="py-2 pr-4">Pre-1890</td><td class="py-2">A simple hook with no lock. Insecure by modern standards — useful for dating a piece, but have it converted if you plan to wear it.</td></tr>
                    <tr class="border-b border-[color:var(--line)]"><td class="py-2 pr-4 font-medium text-[color:#23262e]">Trombone</td><td class="py-2 pr-4">1890–1930</td><td class="py-2">A tube pulled out to release the pin. European, and reasonably secure when the spring is intact.</td></tr>
                    <tr class="border-b border-[color:var(--line)]"><td class="py-2 pr-4 font-medium text-[color:#23262e]">Rollover safety</td><td class="py-2 pr-4">1910 onward</td><td class="py-2">A rotating hood locks over the pin point. Still the standard, and genuinely secure.</td></tr>
                    <tr><td class="py-2 pr-4 font-medium text-[color:#23262e]">Locking pin / stick</td><td class="py-2 pr-4">Modern</td><td class="py-2">Threaded or clutch backs, similar to a lapel pin. The most secure of all.</td></tr>
                </tbody>
            </table>
        </div>
        <p class="text-xs text-[color:var(--stone)] mt-4">
            Long pin stems are original to older brooches — they were designed for the thick fabrics of the period.
            Do not have one shortened; it reduces both value and authenticity.
        </p>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const G = {
        coat: { max:'large', place:'High on the left lapel, roughly at collarbone height. Heavy coat fabric supports almost any weight without sagging.',
                risk:'low', note:'The ideal home for a large statement brooch. Wool and tweed close up around the pin holes completely.' },
        blazer: { max:'medium', place:'On the lapel, or on the pocket flap for a more modern look. Keep to one side rather than centring it.',
                  risk:'low', note:'Structured fabric with a lining underneath handles medium weights well. Pin through the lapel only, not through the lining.' },
        knit: { max:'medium', place:'Near the shoulder or at the neckline of a cardigan, in place of a top button.',
                risk:'medium', note:'Pins push knit stitches apart rather than piercing them, so holes close up — but a heavy brooch will stretch the knit permanently. Back it with a small piece of felt inside to spread the load.' },
        silk: { max:'small', place:'At the neckline or collar point. Keep it light.',
                risk:'high', note:'Silk and fine weaves show permanent pin holes and can snag or run. Use a brooch converter or magnetic clasp if you have one, or pin through a seam where the damage is hidden.' },
        scarf: { max:'large', place:'Where the fabric gathers, using the brooch to hold the drape in place.',
                 risk:'low', note:'Functionally the brooch’s original job. Layered scarf fabric supports weight well and hides the pin entirely.' },
        bag: { max:'medium', place:'Near the top corner of a bag, or on a hat band.',
               risk:'medium', note:'Fun and increasingly common. On leather, pin holes are permanent — use a clip-on converter instead.' },
    };
    const ORDER = { small:1, medium:2, large:3 };
    const garment = document.getElementById('garment'), weight = document.getElementById('weight'), out = document.getElementById('out');
    function render() {
        const g = G[garment.value];
        const tooHeavy = ORDER[weight.value] > ORDER[g.max];
        const RISK = { low:['Low risk to fabric','var(--gold)'], medium:['Some risk','var(--stone)'], high:['High risk','#C0563A'] }[g.risk];
        out.innerHTML = '<div class="border-l-4 p-4" style="border-color:' + (tooHeavy ? '#C0563A' : 'var(--gold)') + '; background:#FBF7EC">' +
            '<p class="text-xs uppercase tracking-wider text-[color:var(--stone)]">Where to place it</p>' +
            '<p class="text-sm mt-1">' + g.place + '</p>' +
            (tooHeavy ? '<p class="text-sm mt-2" style="color:#C0563A"><strong>That brooch is likely too heavy for this fabric</strong> — it will sag, rotate and eventually stretch or tear the material. Choose a smaller piece, or pin it somewhere more substantial.</p>' : '') +
            '</div>' +
            '<div class="border border-[color:var(--line)] p-4 mt-3">' +
            '<span class="text-xs px-2 py-0.5 border" style="border-color:' + RISK[1] + ';color:' + RISK[1] + '">' + RISK[0] + '</span>' +
            '<p class="text-sm text-[color:var(--stone)] mt-2">' + g.note + '</p></div>';
    }
    garment.addEventListener('change', render); weight.addEventListener('change', render);
    render();
})();
</script>
@endpush
