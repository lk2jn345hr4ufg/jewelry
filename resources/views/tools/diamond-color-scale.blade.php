@extends('layouts.app')
@section('title', 'Diamond Colour Scale D–Z — Visual Guide — Gleamion.com')
@section('meta_description', 'See the GIA diamond colour scale from D to Z, which grades are worth paying for, and how metal choice changes what you notice.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Diamond Colour Scale</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Colour is graded face-down against masterstones under controlled light. Face-up in a ring, most people cannot tell adjacent grades apart.</p>

    <div class="card p-6 mt-6">
        <label class="block text-sm font-medium mb-1" for="metal">Setting metal</label>
        <select id="metal" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
            <option value="white" selected>White gold / platinum</option>
            <option value="yellow">Yellow gold</option>
            <option value="rose">Rose gold</option>
        </select>
        <div id="scale" class="mt-6 space-y-1"></div>
        <div id="advice" class="mt-4"></div>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const GRADES = [
        ['D','Colourless','#FFFFFF','The top grade. A meaningful premium for a difference invisible once set.'],
        ['E','Colourless','#FEFEFC','Indistinguishable from D to almost everyone.'],
        ['F','Colourless','#FDFDF8','The last of the colourless grades and the best value among them.'],
        ['G','Near colourless','#FCFBF2','The sweet spot — appears white in any setting, at a real discount to D–F.'],
        ['H','Near colourless','#FAF8EC','Still reads white face-up. Excellent value.'],
        ['I','Near colourless','#F8F4E2','A hint of warmth only visible next to a higher grade.'],
        ['J','Near colourless','#F5F0D8','Slight warmth detectable in larger stones; hidden well by yellow gold.'],
        ['K','Faint','#F1EAC8','Visible warmth. Charming in yellow or rose gold, obvious in platinum.'],
        ['L','Faint','#EDE4B8','Clearly tinted to most eyes in white metal.'],
        ['M','Faint','#E8DDA6','Noticeably warm. Priced accordingly.'],
        ['N-R','Very light','#E0D28C','Obvious yellow tint. Sold as budget or vintage-look stones.'],
        ['S-Z','Light','#D8C572','Strongly tinted. Below Z it becomes "fancy yellow" and prices rise again.'],
    ];
    const ADVICE = {
        white:['G','White metal reflects no colour into the stone, so tint shows more readily. G or H is the practical floor — below J, warmth becomes obvious against platinum.'],
        yellow:['J','Yellow gold reflects warmth into the stone, masking lower colour grades entirely. Buying above J is usually money spent on something nobody will see.'],
        rose:['J','Rose gold hides tint as effectively as yellow. J or K looks perfectly white against the warm metal and saves a great deal.'],
    };
    const metal = document.getElementById('metal');
    const scale = document.getElementById('scale');
    const advice = document.getElementById('advice');
    function render() {
        const [floor, text] = ADVICE[metal.value];
        const floorIdx = GRADES.findIndex(function (g) { return g[0] === floor; });
        scale.innerHTML = GRADES.map(function (g, i) {
            const good = i <= floorIdx;
            return '<div class="flex items-center gap-4 p-2 border" style="border-color:' + (i === floorIdx ? 'var(--gold)' : 'var(--line)') + '">' +
                '<div style="width:34px;height:34px;border-radius:50%;background:' + g[2] + ';border:1px solid #ccc;flex:none"></div>' +
                '<div class="w-12 font-display text-lg font-semibold">' + g[0] + '</div>' +
                '<div class="w-28 text-xs text-[color:var(--stone)]">' + g[1] + '</div>' +
                '<div class="flex-1 text-sm text-[color:var(--stone)]">' + g[3] + '</div>' +
                (i === floorIdx ? '<span class="text-xs px-2 py-0.5 border whitespace-nowrap" style="border-color:var(--gold);color:var(--gold)">Best value</span>' : '') +
                '</div>';
        }).join('');
        advice.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC"><p class="text-sm">' + text + '</p></div>';
    }
    metal.addEventListener('change', render);
    render();
})();
</script>
@endpush
