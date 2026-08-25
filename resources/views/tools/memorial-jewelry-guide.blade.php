@extends('layouts.app')
@section('title', 'Memorial & Keepsake Jewelry Guide — Gleamion.com')
@section('meta_description', 'Types of memorial jewelry — cremation urns, fingerprint pieces, hair and ash settings — with practical guidance on materials, cost and choosing well.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Memorial &amp; Keepsake Jewelry</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Jewelry made to hold a memory — of a person, or sometimes a pet. There is no correct choice here, only what feels right to you.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium mb-1" for="type">What you would like to keep</label>
                <select id="type" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="ashes" selected>A small portion of ashes</option>
                    <option value="hair">A lock of hair</option>
                    <option value="print">A fingerprint or handwriting</option>
                    <option value="symbolic">Something symbolic, nothing physical</option>
                    <option value="existing">Repurpose their own jewelry</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="wear">How you would wear it</label>
                <select id="wear" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="daily" selected>Every day</option>
                    <option value="sometimes">Occasionally</option>
                    <option value="keep">Kept rather than worn</option>
                </select>
            </div>
        </div>
        <div id="out" class="mt-6"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">Worth knowing before you order</h2>
        <ul class="space-y-2 text-sm text-[color:var(--stone)]">
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Only a very small amount is needed</strong> — usually a pinch of ashes or a few strands of hair. You are not committing the whole of anything.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Ask how material is tracked.</strong> Reputable makers document the process and often photograph each stage. This is a reasonable thing to ask about directly.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">Consider making two.</strong> Siblings and family often want their own. Ordering together is far easier than trying to match one years later.</span></li>
            <li class="flex gap-3"><span class="facet-sm mt-1.5 shrink-0" style="background:var(--champagne)"></span><span><strong class="text-[color:#23262e]">There is no rush.</strong> Some people order within weeks; others wait years. Both are entirely normal, and grief makes decisions harder in the early months.</span></li>
        </ul>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const T = {
        ashes: {
            options:[
                ['Cremation urn pendant','$60–400','A small sealed chamber holding a pinch of ashes. Sealed with a threaded screw and adhesive. Choose stainless steel or solid gold — plated versions wear through at the seal.'],
                ['Ashes set in glass or resin','$150–600','Ashes suspended visibly in a glass cabochon, often with colour. Beautiful, though glass can chip — bezel setting helps.'],
                ['Memorial diamond','$1,500–20,000','Carbon extracted from ashes and grown into a genuine lab diamond over several months. The most substantial option; ask for documentation of the process and expect 6–9 months.'],
            ],
            note:'Whichever you choose, keep the remaining ashes separately — pieces can be lost, and having a reserve removes that fear.'
        },
        hair: {
            options:[
                ['Hair in a locket','$50–300','The oldest form of memorial jewelry, dating back centuries. Simple, reversible, and needs no specialist maker.'],
                ['Hair set in resin','$120–400','A lock preserved visibly in clear or tinted resin. Stable indefinitely if kept out of direct sun.'],
                ['Woven hair work','$200–800','Traditional Victorian technique where hair is braided into the piece itself. Few artisans still do it; expect a wait.'],
            ],
            note:'Hair is remarkably durable — Victorian hair jewelry survives in good condition today. It needs no special preservation beyond keeping it dry.'
        },
        print: {
            options:[
                ['Fingerprint pendant or ring','$80–500','Their print impressed or engraved into metal. Taken from an ink print, a photograph of one, or a mould made by a funeral director.'],
                ['Handwriting piece','$70–400','A signature or a phrase in their own hand, laser-engraved. Often the most personal option, and needs only a photo of the writing.'],
                ['Soundwave engraving','$100–450','A voicemail or recording rendered as a visual waveform. Modern, and moving for many people.'],
            ],
            note:'If someone is seriously ill, taking a fingerprint or asking them to write a short phrase is something you can do gently, in advance. Many people find they are glad they did.'
        },
        symbolic: {
            options:[
                ['Birthstone or date piece','$60–500','Their birthstone, or a date engraved. Quiet and private — nobody need know unless you tell them.'],
                ['Coordinates or initial','$60–400','A place that mattered, or simply their initial.'],
                ['Meaningful motif','$50–600','A bird, a tree, a symbol from their faith or their work. Chosen freely rather than prescribed.'],
            ],
            note:'Symbolic pieces carry no logistics and no waiting. For many people that is the right choice, particularly early on.'
        },
        existing: {
            options:[
                ['Reset their stone','$200–1,200','Take a stone from their ring and set it in something you will wear. Keeps the material itself, in a form that suits you.'],
                ['Melt and remake','$300–1,500','Their gold reused in a new piece. The metal is literally the same. Ask whether the jeweler can keep it separate from other material — good ones can.'],
                ['Wear it as it is','Free','Worth saying: their ring on a chain around your neck needs no jeweler at all, and many people find this is enough.'],
            ],
            note:'Repurposing is irreversible. If you have any hesitation, wearing the piece unchanged for a year first costs nothing and often settles the question.'
        },
    };
    const WEAR = {
        daily:'For daily wear, choose solid gold, platinum or surgical steel — plated pieces wear through within a year or two, and a worn seal on an urn pendant is distressing. Bezel settings protect better than prongs.',
        sometimes:'Occasional wear widens your options considerably; more delicate constructions are fine. Store it somewhere specific rather than loose in a drawer.',
        keep:'If it will be kept rather than worn, you can prioritise appearance over durability entirely — glass, resin and delicate work are all reasonable.',
    };
    const type = document.getElementById('type'), wear = document.getElementById('wear'), out = document.getElementById('out');
    function render() {
        const t = T[type.value];
        out.innerHTML = t.options.map(function (o) {
            return '<div class="border border-[color:var(--line)] p-4 mb-2">' +
                '<div class="flex flex-wrap items-baseline gap-3"><span class="font-display text-lg font-semibold">' + o[0] + '</span>' +
                '<span class="text-sm" style="color:var(--gold)">' + o[1] + '</span></div>' +
                '<p class="text-sm text-[color:var(--stone)] mt-1">' + o[2] + '</p></div>';
        }).join('') +
        '<div class="border-l-4 p-4 mt-3" style="border-color:var(--gold); background:#FBF7EC">' +
        '<p class="text-sm">' + t.note + '</p>' +
        '<p class="text-sm mt-2">' + WEAR[wear.value] + '</p></div>';
    }
    type.addEventListener('change', render); wear.addEventListener('change', render);
    render();
})();
</script>
@endpush
