@extends('layouts.app')
@section('title', 'Vintage Watch Buying Checklist — Gleamion.com')
@section('meta_description', 'What to check before buying a vintage watch: originality, redials, service history, franken-watches and the red flags that cost the most.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Vintage Watch Checklist</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">In vintage watches, originality drives value more than condition. A worn original beats a beautifully restored one, almost always.</p>

    <div class="card p-6 mt-6">
        <p class="text-sm text-[color:var(--stone)] mb-4">Tick each item as you check it. Your progress is not saved — this is a checklist to work through with the watch in front of you.</p>
        <div id="checks" class="space-y-2"></div>
        <div id="score" class="mt-5"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">The three expensive mistakes</h2>
        <div class="space-y-3 text-sm">
            <div class="border-l-4 p-4" style="border-color:#C0563A; background:#FDF3F0">
                <p class="font-medium">The redial</p>
                <p class="text-[color:var(--stone)] mt-1">A repainted dial. Common, and it halves the value or worse. Signs: printing that looks too crisp or slightly blurred at the edges, lume plots that do not align with the hour markers, spelling or font that differs from known originals, and minute-track dots that vary in size.</p>
            </div>
            <div class="border-l-4 p-4" style="border-color:#C0563A; background:#FDF3F0">
                <p class="font-medium">The franken-watch</p>
                <p class="text-[color:var(--stone)] mt-1">Assembled from parts of several watches — correct case, wrong movement, replacement dial, aftermarket hands. Looks right in photos, worth a fraction of an honest example. Check that case, movement and dial all belong to the same reference and period.</p>
            </div>
            <div class="border-l-4 p-4" style="border-color:#C0563A; background:#FDF3F0">
                <p class="font-medium">Over-polishing</p>
                <p class="text-[color:var(--stone)] mt-1">Aggressive polishing rounds off the sharp edges and bevels that define a case. Irreversible — metal removed cannot be replaced. Compare the lug profile against reference photos of unpolished examples.</p>
            </div>
        </div>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const ITEMS = [
        ['Dial is original (not redialled)','The single biggest value factor. Ask directly, in writing, and examine under magnification.'],
        ['Hands match the dial and period','Aftermarket or mismatched hands are common and easy to miss. Check lume colour matches the dial plots — aged lume should be consistent.'],
        ['Movement matches the reference','Confirm the calibre is correct for that model and serial range. A swapped movement changes what you are buying entirely.'],
        ['Serial and reference numbers are consistent','Between case, movement and papers. Mismatches need explaining.'],
        ['Case is unpolished or lightly polished','Look at the lug edges and bevels from the side. Sharp, defined edges are what you want.'],
        ['Crown and crystal are correct','Frequently replaced with generic parts. A signed crown matters on many models.'],
        ['Service history is documented','When was it last serviced, and by whom? Unserviced for 15 years means budget $400–1,000 immediately.'],
        ['It actually runs and keeps time','Wind it, wear it for a day if you can. Check the chronograph pushers, date change and any complications.'],
        ['Bracelet or strap is period-correct','Original bracelets can be worth thousands on their own. Clasp codes date them.'],
        ['Box, papers and receipts present','"Full set" carries a substantial premium — often 15–30% on collectible references.'],
        ['Price checked against recent sales','Auction results and dealer sold-listings, not asking prices. Asking prices are aspirational.'],
        ['Seller has a return policy','Reputable dealers offer one. Its absence on an expensive watch is a meaningful signal.'],
    ];
    const box = document.getElementById('checks'), score = document.getElementById('score');
    const state = {};
    ITEMS.forEach(function (it, i) {
        const d = document.createElement('label');
        d.className = 'flex gap-3 items-start p-3 border cursor-pointer';
        d.style.borderColor = 'var(--line)';
        d.innerHTML = '<input type="checkbox" class="mt-1"><div><div class="font-medium text-sm">' + it[0] + '</div>' +
            '<div class="text-xs text-[color:var(--stone)] mt-1">' + it[1] + '</div></div>';
        d.querySelector('input').addEventListener('change', function (e) {
            state[i] = e.target.checked;
            d.style.borderColor = e.target.checked ? 'var(--gold)' : 'var(--line)';
            d.style.background = e.target.checked ? '#FBF7EC' : '';
            render();
        });
        box.appendChild(d);
    });
    function render() {
        const done = Object.keys(state).filter(function (k) { return state[k]; }).length;
        const pct = Math.round(done / ITEMS.length * 100);
        let msg;
        if (done === ITEMS.length) msg = 'Everything checks out. This is about as much diligence as you can do without a watchmaker — for a significant purchase, a pre-sale inspection by an independent watchmaker is still money well spent.';
        else if (done >= 9) msg = 'Mostly sound. Get clear answers on the remaining points before committing — and get them in writing.';
        else if (done >= 5) msg = 'Significant gaps. Each unchecked item is a place where value can quietly disappear. Do not rush this.';
        else msg = 'Too many unknowns to buy safely at vintage prices. Keep asking questions, or walk away — there is always another watch.';
        score.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC">' +
            '<p class="font-display text-2xl font-semibold" style="color:var(--gold)">' + done + ' of ' + ITEMS.length + ' checked (' + pct + '%)</p>' +
            '<p class="text-sm mt-2">' + msg + '</p></div>';
    }
    render();
})();
</script>
@endpush
