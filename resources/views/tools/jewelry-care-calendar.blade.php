@extends('layouts.app')
@section('title', 'Jewelry Maintenance Calendar — What to Do and When — Gleamion.com')
@section('meta_description', 'A maintenance schedule for your jewelry: how often to clean, check prongs, replate, restring and reappraise, based on how often you wear it.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Maintenance Calendar</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Nearly every lost stone was preceded by a prong that could have been re-tipped for $25. Here is the schedule that prevents it.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium mb-1" for="type">Piece</label>
                <select id="type" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="ringstone" selected>Ring with stones</option>
                    <option value="ringplain">Plain band</option>
                    <option value="whitegold">White gold piece</option>
                    <option value="pearls">Pearl strand</option>
                    <option value="silver">Silver jewelry</option>
                    <option value="watch">Mechanical watch</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="freq">How often worn</label>
                <select id="freq" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="0.6">Daily, never removed</option>
                    <option value="1" selected>Most days</option>
                    <option value="1.8">Occasionally</option>
                </select>
            </div>
        </div>
        <div id="tasks" class="mt-6 space-y-2"></div>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const TASKS = {
        ringstone: [
            [0.25,'Rinse and soft-brush at home','Warm water, a drop of dish soap, a soft toothbrush. Two minutes restores most of the sparkle people pay a jeweler for.'],
            [6,'Professional check and clean','The jeweler inspects prongs under magnification. This is the appointment that saves stones.'],
            [12,'Shake test yourself','Hold it to your ear and shake gently. Any rattle means a loose stone — stop wearing it and get it seen this week.'],
            [60,'Re-tip prongs','Every 5–10 years depending on wear. Cheaper and easier than replacing a whole head.'],
            [48,'Update the appraisal','Every 3–5 years, so insurance reflects current replacement cost.'],
        ],
        ringplain: [
            [1,'Wipe and inspect','Soap builds up underneath. A quick clean prevents skin irritation.'],
            [12,'Professional polish','Removes fine scratches. Do not over-polish — each polish removes a little metal.'],
            [120,'Check shank thickness','After 10+ years of daily wear the underside thins. A jeweler can rebuild it before it splits.'],
        ],
        whitegold: [
            [0.5,'Home clean','Soapy water and a soft cloth. Avoid polishing compounds, which strip rhodium faster.'],
            [18,'Rhodium replating','Every 12–24 months as the plating wears yellow-grey at contact points. $50–100.'],
            [12,'Professional check','Combine with replating for one labour charge.'],
        ],
        pearls: [
            [0.03,'Wipe after every wear','A soft damp cloth removes perfume, sweat and cosmetics — the three things that dull nacre permanently.'],
            [12,'Restring','Annually if worn weekly. Knotted between pearls, so a break loses one pearl rather than all of them.'],
            [6,'Check the clasp and silk','Fraying near the clasp is the first sign a restring is due.'],
        ],
        silver: [
            [1,'Polish with a silver cloth','Straight strokes, not circles. Do not use dips on oxidised or antiqued pieces.'],
            [0.1,'Store sealed','In a zip bag with an anti-tarnish strip. Air is what tarnishes silver — sealed storage nearly eliminates the job.'],
        ],
        watch: [
            [0.25,'Wipe the case and bracelet','Sweat corrodes; a soft cloth after wear prevents most of it.'],
            [24,'Pressure test seals','Every two years if you swim with it. Gaskets perish silently and you find out when water gets in.'],
            [72,'Full service','Every 5–7 years: disassembly, cleaning, fresh lubricants, regulation. $200–800 depending on movement.'],
        ],
    };
    const type = document.getElementById('type'), freq = document.getElementById('freq'), tasks = document.getElementById('tasks');
    function label(m) {
        if (m < 0.1) return 'After each wear';
        if (m < 1) return 'Weekly';
        if (m < 2) return 'Monthly';
        if (m < 12) return 'Every ' + Math.round(m) + ' months';
        if (m < 24) return 'Yearly';
        return 'Every ' + (m / 12).toFixed(0) + ' years';
    }
    function render() {
        const f = parseFloat(freq.value);
        tasks.innerHTML = TASKS[type.value].map(function (t) {
            const interval = t[0] * f;
            return '<div class="border border-[color:var(--line)] p-4 flex flex-wrap gap-4 items-start">' +
                '<span class="text-xs px-2 py-1 border whitespace-nowrap" style="border-color:var(--gold);color:var(--gold)">' + label(interval) + '</span>' +
                '<div class="flex-1"><div class="font-medium">' + t[1] + '</div>' +
                '<div class="text-sm text-[color:var(--stone)] mt-1">' + t[2] + '</div></div></div>';
        }).join('');
    }
    type.addEventListener('change', render); freq.addEventListener('change', render);
    render();
})();
</script>
@endpush
