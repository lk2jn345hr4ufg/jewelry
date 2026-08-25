@extends('layouts.app')
@section('title', 'Children’s Jewelry Guide — Safe Choices by Age — Gleamion.com')
@section('meta_description', 'What jewelry is safe for babies, toddlers and children — materials, choking risks, and which popular products to avoid entirely.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Children’s Jewelry Guide</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Children’s jewelry is a safety question before it is a style question. Choose by age.</p>

    <div class="card p-6 mt-6">
        <label class="block text-sm font-medium mb-1" for="age">Age</label>
        <select id="age" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white max-w-xs">
            <option value="baby" selected>Under 1 year</option>
            <option value="toddler">1–3 years</option>
            <option value="young">4–8 years</option>
            <option value="older">9–12 years</option>
            <option value="teen">13+ years</option>
        </select>
        <div id="out" class="mt-6"></div>
    </div>

    <div class="card p-6 mt-6" style="border-color:#C0563A">
        <h2 class="font-display text-2xl font-semibold mb-3" style="color:#C0563A">Products to avoid</h2>
        <div class="space-y-3 text-sm">
            <div class="border-l-4 p-4" style="border-color:#C0563A; background:#FDF3F0">
                <p class="font-medium">Amber teething necklaces</p>
                <p class="text-[color:var(--stone)] mt-1">Marketed as a teething remedy, with no evidence that they work. The FDA has warned against them following reports of strangulation and choking, including a death. Never put any necklace on a sleeping or unsupervised infant. If teething pain is the concern, a chilled teether or advice from your paediatrician is both safer and more effective.</p>
            </div>
            <div class="border-l-4 p-4" style="border-color:#C0563A; background:#FDF3F0">
                <p class="font-medium">Cheap costume jewelry for young children</p>
                <p class="text-[color:var(--stone)] mt-1">Repeatedly found to contain lead and cadmium above safe limits. Small children mouth everything. Buy from sellers who state compliance with children's product safety standards, or stick to solid gold, sterling silver, or surgical steel.</p>
            </div>
            <div class="border-l-4 p-4" style="border-color:#C0563A; background:#FDF3F0">
                <p class="font-medium">Anything with small detachable parts</p>
                <p class="text-[color:var(--stone)] mt-1">Charms, beads and small stones are choking hazards for anyone under three, and often for older children too.</p>
            </div>
        </div>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const A = {
        baby: {
            verdict:'Almost nothing is appropriate',
            colour:'#C0563A',
            text:'Infants should not wear necklaces or bracelets at all — strangulation and choking are real, documented risks, and jewelry offers no benefit at this age. Ear piercing is a personal and cultural decision; if you choose it, have it done by a medical professional or an experienced piercer using sterile technique, with small, flat-backed studs in implant-grade titanium or 14K+ gold.',
            ok:['Small flat-backed screw-on earrings, if pierced','Keepsake jewelry stored, not worn'],
            no:['All necklaces, including amber','Bracelets and anklets','Anything with beads or charms'],
        },
        toddler: {
            verdict:'Very limited',
            colour:'#C0563A',
            text:'Toddlers mouth everything and pull hard on anything within reach. Keep jewelry to secure earrings only, and supervise closely. Nothing should be worn during sleep or naps.',
            ok:['Screw-back or locking-back stud earrings','Nothing else during unsupervised play'],
            no:['Necklaces and bracelets','Any small detachable component','Costume metal of unknown composition'],
        },
        young: {
            verdict:'Simple pieces, supervised',
            colour:'var(--stone)',
            text:'By four or five, a simple necklace worn during the day is reasonable for most children, provided it has a breakaway clasp that releases under tension. Keep pieces small, solid and inexpensive enough that loss is not a crisis.',
            ok:['Breakaway-clasp necklaces','Stud earrings with secure backs','Simple sterling or gold bracelets'],
            no:['Necklaces worn to bed','Long chains that can catch','Rings that may be swallowed if loose'],
        },
        older: {
            verdict:'Most jewelry is fine',
            colour:'var(--gold)',
            text:'This is the age when a first "real" piece often lands well — a birthstone pendant or small gold studs. Choose materials that will not react: 14K gold, sterling silver or surgical steel rather than plated base metal, which wears through and exposes nickel.',
            ok:['Birthstone or initial pendants','Small hoops and studs','Simple bracelets and rings'],
            no:['Plated costume pieces for daily wear','Anything valuable enough to worry about losing at school'],
        },
        teen: {
            verdict:'Adult choices, with material care',
            colour:'var(--gold)',
            text:'Style is now their decision. The one thing worth guiding is material: nickel sensitivity often develops in the teens, and cheap plated jewelry is the usual trigger. Solid metals cost more initially and last enormously longer.',
            ok:['Solid gold, silver, steel or titanium','Meaningful first pieces that will last'],
            no:['Plated jewelry for daily wear','Piercing jewelry of unknown metal composition'],
        },
    };
    const age = document.getElementById('age'), out = document.getElementById('out');
    function list(items, colour) {
        return items.map(function (t) {
            return '<li class="flex gap-3 text-sm"><span class="facet-sm mt-1.5 shrink-0" style="background:' + colour + '"></span><span>' + t + '</span></li>';
        }).join('');
    }
    function render() {
        const a = A[age.value];
        out.innerHTML = '<div class="border-l-4 p-4" style="border-color:' + a.colour + '; background:#FBF7EC">' +
            '<p class="font-display text-2xl font-semibold" style="color:' + a.colour + '">' + a.verdict + '</p>' +
            '<p class="text-sm mt-2">' + a.text + '</p></div>' +
            '<div class="grid gap-4 sm:grid-cols-2 mt-4">' +
            '<div><h3 class="font-medium mb-2">Reasonable</h3><ul class="space-y-2">' + list(a.ok, 'var(--gold)') + '</ul></div>' +
            '<div><h3 class="font-medium mb-2">Avoid</h3><ul class="space-y-2">' + list(a.no, '#C0563A') + '</ul></div></div>';
    }
    age.addEventListener('change', render);
    render();
})();
</script>
@endpush
