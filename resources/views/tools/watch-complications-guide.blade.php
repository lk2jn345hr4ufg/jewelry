@extends('layouts.app')
@section('title', 'Watch Complications Guide — What Each One Does — Gleamion.com')
@section('meta_description', 'Every common watch complication explained: chronograph, GMT, moonphase, annual calendar, tourbillon — what they cost and whether you will use them.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Watch Complications Guide</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">A complication is anything the watch does beyond telling the time. Each adds cost, thickness and servicing complexity — so it should earn its place.</p>

    <div class="card p-6 mt-6">
        <label class="block text-sm font-medium mb-1" for="filter">Show</label>
        <select id="filter" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
            <option value="all" selected>Everything</option>
            <option value="useful">Ones you will actually use</option>
            <option value="travel">Useful for travel</option>
            <option value="prestige">Prestige and craft</option>
        </select>
        <div id="list" class="mt-6 space-y-2"></div>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const C = [
        { name:'Date window', tags:['useful'], cost:'Minimal', use:5,
          note:'The most useful complication there is, and near-universal. Watch for legibility — some date windows are tiny. Avoid setting the date between roughly 9pm and 3am on most movements; the gear train is engaged and can be damaged.' },
        { name:'Day-date', tags:['useful'], cost:'Small', use:4,
          note:'Adds the weekday alongside the date. Genuinely handy, at the price of a busier dial.' },
        { name:'Chronograph', tags:['useful'], cost:'Significant', use:3,
          note:'A stopwatch. The most popular complication and the most used-for-looks — most owners rarely time anything. Adds real thickness and roughly doubles service cost.' },
        { name:'GMT / dual time', tags:['useful','travel'], cost:'Moderate', use:4,
          note:'Tracks a second time zone with a 24-hour hand. The single most practical complication for anyone who travels or works across time zones.' },
        { name:'World timer', tags:['travel'], cost:'High', use:3,
          note:'Shows all 24 zones at once via a rotating city ring. Beautiful, and genuinely useful if you deal with many regions — otherwise a GMT does the job more legibly.' },
        { name:'Power reserve indicator', tags:['useful'], cost:'Moderate', use:3,
          note:'Shows how much winding is left. Most useful on manual-wind watches, where running down is a daily possibility.' },
        { name:'Moonphase', tags:['prestige'], cost:'Moderate', use:1,
          note:'Tracks the lunar cycle. Almost purely decorative for most owners, but a long-standing horological tradition and a charming detail. Good ones drift by a day only every 122 years.' },
        { name:'Annual calendar', tags:['useful','prestige'], cost:'High', use:4,
          note:'Handles month lengths automatically except February. A sensible middle ground — most of the convenience of a perpetual calendar at a fraction of the cost.' },
        { name:'Perpetual calendar', tags:['prestige'], cost:'Very high', use:3,
          note:'Accounts for leap years and will not need correction until 2100. Mechanically remarkable. Leave it unworn for months and resetting is a genuine chore — a winder helps.' },
        { name:'Tourbillon', tags:['prestige'], cost:'Extreme', use:1,
          note:'A rotating escapement cage designed to counter gravity in pocket watches. On a wristwatch that moves constantly, the practical accuracy benefit is negligible — you are buying craftsmanship and status, which is a legitimate reason, just not an accuracy one.' },
        { name:'Minute repeater', tags:['prestige'], cost:'Extreme', use:1,
          note:'Chimes the time on demand. Among the most difficult things to build in watchmaking, and priced accordingly. Originally a solution for telling time in the dark before electric light.' },
        { name:'Tachymeter', tags:[], cost:'None (bezel)', use:1,
          note:'A bezel scale for calculating speed over a measured distance. Requires a chronograph to use, and virtually nobody does — it is a design signature at this point.' },
    ];
    const filter = document.getElementById('filter'), list = document.getElementById('list');
    function bar(n) {
        let d = '';
        for (let i = 1; i <= 5; i++) d += '<span class="facet-sm" style="background:' + (i <= n ? 'var(--gold)' : 'var(--line)') + '"></span>';
        return d;
    }
    function render() {
        const f = filter.value;
        const out = C.filter(function (c) { return f === 'all' || c.tags.includes(f); });
        list.innerHTML = out.map(function (c) {
            return '<div class="border border-[color:var(--line)] p-4">' +
                '<div class="flex flex-wrap gap-3 justify-between items-baseline">' +
                '<span class="font-display text-lg font-semibold">' + c.name + '</span>' +
                '<span class="flex items-center gap-3 text-xs text-[color:var(--stone)]">Added cost: ' + c.cost +
                '<span class="flex gap-1 items-center">Everyday use ' + bar(c.use) + '</span></span></div>' +
                '<p class="text-sm text-[color:var(--stone)] mt-2">' + c.note + '</p></div>';
        }).join('');
    }
    filter.addEventListener('change', render);
    render();
})();
</script>
@endpush
