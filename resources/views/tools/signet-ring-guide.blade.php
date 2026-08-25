@extends('layouts.app')
@section('title', 'Signet Ring Guide — Shape, Size & Engraving — Gleamion.com')
@section('meta_description', 'Choose a signet ring: face shapes, sizing by hand, engraving styles and which finger tradition says it belongs on.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Signet Ring Guide</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Originally a seal for stamping wax — which is why traditional engraving is cut in reverse. Now largely decorative, but the conventions still shape how they are made.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-3">
            <div>
                <label class="block text-sm font-medium mb-1" for="hand">Hand size</label>
                <select id="hand" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="small">Smaller hands</option>
                    <option value="medium" selected>Average</option>
                    <option value="large">Larger hands</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="shape">Face shape</label>
                <select id="shape" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="oval" selected>Oval — the classic</option>
                    <option value="cushion">Cushion — softened square</option>
                    <option value="round">Round</option>
                    <option value="rect">Rectangular</option>
                    <option value="octagon">Octagonal</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="engrave">Engraving</label>
                <select id="engrave" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="none" selected>None — plain face</option>
                    <option value="initials">Initials / monogram</option>
                    <option value="crest">Crest or emblem</option>
                    <option value="seal">Reverse-cut for sealing wax</option>
                </select>
            </div>
        </div>
        <div id="out" class="mt-6"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">Which finger?</h2>
        <ul class="space-y-2 text-sm text-[color:var(--stone)]">
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Little finger of the non-dominant hand</strong> is the English tradition, and remains the most common placement.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Ring finger</strong> is the continental European convention, often alongside or in place of a wedding band.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Thumb</strong> works for larger, bolder signets and is entirely modern.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span>None of this is a rule. Wear it where it fits and looks right to you — the conventions are historical, not binding.</span></li>
        </ul>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const SIZE = { small:[9,11], medium:[11,14], large:[13,17] };
    const SHAPE = {
        oval:'The traditional English form, taller than it is wide. Flatters most hands and suits a monogram or crest equally well.',
        cushion:'A square face with rounded corners. Slightly more contemporary, and gives more engraving area than an oval of the same width.',
        round:'Simple and symmetrical. Best for a single initial or a circular emblem; a three-letter monogram can look cramped.',
        rect:'Wider than tall, sometimes called an "oxford". Good for longer words or a horizontal crest, and reads as more modern.',
        octagon:'Faceted edges give a distinctly vintage, Art Deco feel. Less common, and quite striking.',
    };
    const ENG = {
        none:'A plain polished face is a legitimate and increasingly popular choice. It can always be engraved later, so there is no urgency to decide.',
        initials:'Traditional monograms place the surname initial larger in the centre. Block, script and Old English are the usual letterforms — block ages best and stays legible as the ring wears.',
        crest:'A family crest or personal emblem. Hand engraving costs $200–800 and takes weeks; laser is cheaper and faster but shallower, so it wears away sooner.',
        seal:'Cut in reverse so the wax impression reads correctly. This is the original purpose of the ring. Requires deeper hand engraving and a jeweler who has actually done it before — ask to see examples.',
    };
    const hand = document.getElementById('hand'), shape = document.getElementById('shape'), engrave = document.getElementById('engrave');
    const out = document.getElementById('out');
    function render() {
        const s = SIZE[hand.value];
        out.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC">' +
            '<p class="text-xs uppercase tracking-wider text-[color:var(--stone)]">Suggested face size</p>' +
            '<p class="font-display text-2xl font-semibold" style="color:var(--gold)">' + s[0] + '–' + s[1] + ' mm</p>' +
            '<p class="text-sm mt-2">' + SHAPE[shape.value] + '</p></div>' +
            '<div class="border border-[color:var(--line)] p-4 mt-3"><p class="font-medium text-sm mb-1">Engraving</p>' +
            '<p class="text-sm text-[color:var(--stone)]">' + ENG[engrave.value] + '</p></div>' +
            '<p class="text-xs text-[color:var(--stone)] mt-3">A signet is heavier on one side, so it rotates unless the fit is right. ' +
            'Size it slightly snugger than a plain band, and ask about a weighted or shaped shank if it still spins.</p>';
    }
    [hand, shape, engrave].forEach(function (e) { e.addEventListener('change', render); });
    render();
})();
</script>
@endpush
