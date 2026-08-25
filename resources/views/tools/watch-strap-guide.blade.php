@extends('layouts.app')
@section('title', 'Watch Strap Guide — Leather, Steel, NATO, Rubber — Gleamion.com')
@section('meta_description', 'Choose the right watch strap for the watch, the occasion and your wrist: materials compared on comfort, durability and formality.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Watch Strap Guide</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">A strap change is the cheapest way to transform a watch. Match it to the watch type and where you wear it.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-3">
            <div>
                <label class="block text-sm font-medium mb-1" for="watch">Watch type</label>
                <select id="watch" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="dress">Dress watch</option>
                    <option value="field" selected>Field / everyday</option>
                    <option value="dive">Dive / sports</option>
                    <option value="chrono">Chronograph</option>
                    <option value="vintage">Vintage</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="context">Mostly worn</label>
                <select id="context" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="formal">Formal / office</option>
                    <option value="casual" selected>Casual</option>
                    <option value="active">Active / wet</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="lug">Lug width (mm)</label>
                <select id="lug" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option>16</option><option>18</option><option value="20" selected>20</option>
                    <option>22</option><option>24</option>
                </select>
            </div>
        </div>
        <div id="rec" class="mt-6"></div>
        <div id="list" class="mt-6 space-y-2"></div>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const S = [
        { name:'Leather (calf)', formal:5, durable:3, wet:1, watches:['dress','field','chrono','vintage'],
          note:'The default for dress and vintage watches. Ages beautifully but hates water — sweat and rain break down the fibres and it will start to smell. Expect to replace every 1–2 years with regular wear.' },
        { name:'Alligator / croc', formal:5, durable:3, wet:1, watches:['dress','vintage'],
          note:'The traditional pairing for a fine dress watch. Expensive, and genuinely delicate — reserve it for occasions rather than daily wear.' },
        { name:'Steel bracelet', formal:4, durable:5, wet:5, watches:['field','dive','chrono'],
          note:'Durable, waterproof and adjustable by removing links. Heavier than a strap, and a poorly finished bracelet is the fastest way to make a watch feel cheap — quality here is very visible.' },
        { name:'NATO / pass-through', formal:1, durable:4, wet:4, watches:['field','dive','vintage'],
          note:'A single nylon strap that passes under the case, so the watch stays on the wrist even if a spring bar fails. Casual, cheap and endlessly swappable. Adds a few millimetres of height under the watch.' },
        { name:'Rubber / FKM', formal:1, durable:5, wet:5, watches:['dive','chrono','field'],
          note:'The practical choice for swimming and sport. Modern FKM rubber resists ageing far better than cheap silicone, which attracts dust and degrades.' },
        { name:'Perlon', formal:2, durable:3, wet:4, watches:['field','vintage'],
          note:'Woven mesh with infinite adjustment — the buckle pin passes anywhere through the weave, so fit is exact. Light, breathable and inexpensive.' },
        { name:'Milanese mesh', formal:4, durable:4, wet:4, watches:['dress','field','vintage'],
          note:'Fine steel mesh with a sliding clasp. Comfortable and infinitely adjustable, though it can catch arm hair and shows scratches readily.' },
        { name:'Suede / nubuck', formal:3, durable:2, wet:1, watches:['field','vintage','chrono'],
          note:'Soft, matte and characterful. The least water-tolerant option of all — one soaking ruins it.' },
        { name:'Canvas / sailcloth', formal:2, durable:4, wet:3, watches:['field','dive','chrono'],
          note:'Tough and casual, often with a rubber backing for comfort and water resistance. A good middle ground for everyday wear.' },
    ];
    const watch = document.getElementById('watch'), context = document.getElementById('context'), lug = document.getElementById('lug');
    const rec = document.getElementById('rec'), list = document.getElementById('list');
    function bar(label, n) {
        let d = '';
        for (let i = 1; i <= 5; i++) d += '<span class="facet-sm" style="background:' + (i <= n ? 'var(--gold)' : 'var(--line)') + '"></span>';
        return '<div class="flex items-center gap-2 text-xs"><span class="w-16 text-[color:var(--stone)]">' + label + '</span><span class="flex gap-1">' + d + '</span></div>';
    }
    function render() {
        const w = watch.value, c = context.value;
        const scored = S.map(function (s) {
            let sc = 0;
            if (s.watches.includes(w)) sc += 4;
            if (c === 'formal') sc += s.formal * 1.2;
            if (c === 'casual') sc += (6 - Math.abs(3 - s.formal)) * 0.8 + s.durable * 0.5;
            if (c === 'active') sc += s.wet * 1.5 + s.durable * 0.8;
            return { s: s, sc: sc };
        }).sort(function (a, b) { return b.sc - a.sc; });
        const best = scored[0].s;
        rec.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC">' +
            '<p class="text-xs uppercase tracking-wider text-[color:var(--stone)]">Suggested strap</p>' +
            '<p class="font-display text-2xl font-semibold" style="color:var(--gold)">' + best.name + ' · ' + lug.value + ' mm</p>' +
            '<p class="text-sm mt-2">' + best.note + '</p>' +
            '<p class="text-sm text-[color:var(--stone)] mt-2">Buy a spring bar tool (about $10) and quick-release spring bars — changing straps then takes under a minute and needs no jeweler.</p></div>';
        list.innerHTML = scored.map(function (o) {
            return '<div class="border border-[color:var(--line)] p-4">' +
                '<div class="flex flex-wrap gap-4 justify-between items-start">' +
                '<h3 class="font-display text-lg font-semibold">' + o.s.name + '</h3>' +
                '<div class="space-y-1">' + bar('Formal', o.s.formal) + bar('Durable', o.s.durable) + bar('Water', o.s.wet) + '</div></div>' +
                '<p class="text-sm text-[color:var(--stone)] mt-2">' + o.s.note + '</p></div>';
        }).join('');
    }
    [watch, context, lug].forEach(function (e) { e.addEventListener('change', render); });
    render();
})();
</script>
@endpush
