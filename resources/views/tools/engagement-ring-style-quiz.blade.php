@extends('layouts.app')

@section('title', 'Engagement Ring Style Quiz — Find Their Taste — Gleamion.com')
@section('meta_description', 'Answer five questions about their taste and lifestyle to narrow down an engagement ring style before you shop.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Engagement Ring Style Quiz</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Answer as <em>they</em> would, not as you would. Five questions.</p>

    <div class="card p-6 mt-6">
        <div id="questions" class="space-y-6"></div>
        <div id="result" class="mt-6"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">Before you buy anything</h2>
        <ul class="space-y-2 text-sm text-[color:var(--stone)]">
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Look at the jewelry they already wear.</strong> It is the most reliable signal there is — yellow or white metal, delicate or bold, plain or ornate.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Ask a close friend or their sibling.</strong> They usually know, and they can keep a secret better than you can shop blind.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Check the return and resize policy.</strong> Even a perfect guess often needs a size adjustment.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Consider proposing with a placeholder.</strong> Choosing the real ring together is increasingly common — and removes the guessing entirely.</span></li>
        </ul>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const Q = [
        { q:'Their wardrobe is mostly…', a:[
            ['Classic, tailored, timeless', {classic:2}],
            ['Minimal, modern, clean lines', {modern:2}],
            ['Vintage finds and texture', {vintage:2}],
            ['Bold colour and statement pieces', {statement:2}],
        ]},
        { q:'The jewelry they wear now is…', a:[
            ['Fine and delicate — barely there', {classic:1, modern:1}],
            ['Chunky and noticeable', {statement:2}],
            ['Antique or inherited pieces', {vintage:2}],
            ['They rarely wear any', {modern:1, classic:1}],
        ]},
        { q:'Their hands are busy with…', a:[
            ['Desk work, mostly', {statement:1, vintage:1}],
            ['Kids, gym, gardening', {modern:1, classic:1}],
            ['Manual or clinical work with gloves', {modern:2}],
        ]},
        { q:'If they saw a huge diamond, they would…', a:[
            ['Love it — bigger is better', {statement:2}],
            ['Find it a bit much', {classic:1, vintage:1}],
            ['Prefer something unusual instead', {vintage:1, modern:1}],
        ]},
        { q:'Metal colour they wear most…', a:[
            ['Yellow gold', {vintage:1, classic:1}],
            ['White gold or platinum', {classic:1, modern:1}],
            ['Rose gold', {vintage:2}],
            ['Mixed — no pattern', {modern:1, statement:1}],
        ]},
    ];

    const RESULTS = {
        classic: ['Classic solitaire', 'A single round or oval stone on a plain band — the style that has outlasted every trend since the 1880s. Prioritise cut quality over size, and choose a six-prong or bezel setting for security. Impossible to get badly wrong, and easy to add a matching band to later.'],
        modern: ['Clean and contemporary', 'Think bezel or half-bezel settings, plain polished bands, and geometric shapes like emerald or radiant cuts. Low-profile settings suit active hands and catch on nothing. Platinum or white gold reinforces the look.'],
        vintage: ['Vintage-inspired', 'Milgrain edges, filigree detail, halo settings and rose or yellow gold. Consider a genuine Edwardian or Art Deco piece — often better value than a new reproduction, with real history. Older cuts like old European and rose cuts glow rather than sparkle.'],
        statement: ['Statement and distinctive', 'A larger centre stone, a halo to maximise apparent size, or a coloured centre stone — sapphire and emerald are increasingly chosen over diamond. Three-stone settings and split-shank bands add presence without simply chasing carats.'],
    };

    const box = document.getElementById('questions');
    const result = document.getElementById('result');
    const answers = {};

    Q.forEach(function (item, qi) {
        const wrap = document.createElement('div');
        wrap.innerHTML = '<p class="font-medium mb-2">' + (qi + 1) + '. ' + item.q + '</p>';
        const opts = document.createElement('div');
        opts.className = 'grid gap-2 sm:grid-cols-2';
        item.a.forEach(function (opt, ai) {
            const b = document.createElement('button');
            b.type = 'button';
            b.textContent = opt[0];
            b.className = 'text-left text-sm px-3 py-2 border';
            b.style.borderColor = 'var(--line)';
            b.addEventListener('click', function () {
                answers[qi] = opt[1];
                [...opts.children].forEach(function (c) {
                    c.style.borderColor = 'var(--line)'; c.style.background = ''; c.style.color = '';
                });
                b.style.borderColor = 'var(--gold)';
                b.style.background = 'var(--gold)';
                b.style.color = '#fff';
                render();
            });
            opts.appendChild(b);
        });
        wrap.appendChild(opts);
        box.appendChild(wrap);
    });

    function render() {
        const answered = Object.keys(answers).length;
        if (answered < Q.length) {
            result.innerHTML = '<p class="text-sm text-[color:var(--stone)]">' + answered + ' of ' + Q.length + ' answered.</p>';
            return;
        }
        const score = { classic:0, modern:0, vintage:0, statement:0 };
        Object.keys(answers).forEach(function (k) {
            Object.keys(answers[k]).forEach(function (s) { score[s] += answers[k][s]; });
        });
        const best = Object.keys(score).sort(function (a, b) { return score[b] - score[a]; })[0];
        const r = RESULTS[best];

        result.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC">' +
            '<p class="text-xs uppercase tracking-wider text-[color:var(--stone)]">Their likely style</p>' +
            '<p class="font-display text-2xl font-semibold" style="color:var(--gold)">' + r[0] + '</p>' +
            '<p class="text-sm mt-2">' + r[1] + '</p></div>';
    }

    render();
})();
</script>
@endpush
