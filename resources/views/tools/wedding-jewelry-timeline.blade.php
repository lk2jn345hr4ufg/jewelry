@extends('layouts.app')
@section('title', 'Wedding Jewelry Timeline — When to Order What — Gleamion.com')
@section('meta_description', 'A countdown of when to order rings, bridal jewelry and gifts before a wedding, so nothing arrives late.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Wedding Jewelry Timeline</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Enter the date and see exactly when each item needs ordering. Engraving and resizing are what catch people out.</p>

    <div class="card p-6 mt-6">
        <label class="block text-sm font-medium mb-1" for="date">Wedding date</label>
        <input id="date" type="date" class="w-full border border-[color:var(--line)] px-3 py-2 max-w-xs">
        <div id="timeline" class="mt-6 space-y-2"></div>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const ITEMS = [
        [26,'Start looking at wedding bands','Especially if you want them to match the engagement ring or be custom made. Browsing early costs nothing and prevents rushed decisions later.'],
        [20,'Order custom or contoured bands','Bespoke bands take 6–10 weeks, and longer in spring. A contoured band shaped to an existing engagement ring cannot be rushed.'],
        [16,'Choose bridal jewelry','Earrings and necklace to suit the dress neckline. Bring a photo of the dress when you shop — necklines change everything.'],
        [12,'Order off-the-shelf bands','Stock rings still need sizing, and often engraving. Twelve weeks is comfortable; six is tight.'],
        [10,'Book any engraving','Hand engraving takes 1–3 weeks and cannot be redone if the date is wrong. Check the spelling twice.'],
        [8,'Order wedding party gifts','If they are being personalised or engraved, this is the deadline, not a suggestion.'],
        [6,'Final ring sizing','Fingers change with weight, heat and stress. Size now rather than six months ago — and remember two rings on one finger fit tighter.'],
        [4,'Insure the rings','Add them to your policy or schedule a rider before they exist as an uninsured object in your house.'],
        [3,'Collect everything','Pick up all pieces and check them under good light. Leaves time to fix anything wrong.'],
        [2,'Professional clean','Have the engagement ring cleaned and the prongs checked. It will be photographed hundreds of times.'],
        [1,'Assign a keeper','Decide who physically holds the rings on the day, and where the bridal jewelry lives before the ceremony.'],
    ];
    const date = document.getElementById('date'), out = document.getElementById('timeline');
    const d = new Date(); d.setMonth(d.getMonth() + 9);
    date.value = d.toISOString().slice(0, 10);
    function render() {
        const wedding = new Date(date.value);
        if (isNaN(wedding)) { out.innerHTML = '<p class="text-sm text-[color:var(--stone)]">Choose a date.</p>'; return; }
        const today = new Date(); today.setHours(0,0,0,0);
        out.innerHTML = ITEMS.map(function (i) {
            const due = new Date(wedding); due.setDate(due.getDate() - i[0] * 7);
            const past = due < today;
            const soon = !past && (due - today) / 86400000 < 21;
            const colour = past ? '#C0563A' : (soon ? 'var(--gold)' : 'var(--line)');
            return '<div class="border-l-4 p-4" style="border-color:' + colour + ';background:' + (past || soon ? '#FBF7EC' : '#fff') + '">' +
                '<div class="flex flex-wrap gap-3 justify-between items-baseline">' +
                '<span class="font-medium">' + i[1] + '</span>' +
                '<span class="text-sm" style="color:' + (past ? '#C0563A' : 'var(--gold)') + '">' +
                due.toLocaleDateString(undefined, { day:'numeric', month:'short', year:'numeric' }) +
                (past ? ' — overdue' : (soon ? ' — soon' : '')) + '</span></div>' +
                '<p class="text-sm text-[color:var(--stone)] mt-1">' + i[2] + '</p>' +
                '<p class="text-xs text-[color:var(--stone)] mt-1">' + i[0] + ' weeks before</p></div>';
        }).join('');
    }
    date.addEventListener('change', render);
    render();
})();
</script>
@endpush
