@extends('layouts.app')
@section('title', 'Jewelry Repair Cost Estimator — Gleamion.com')
@section('meta_description', 'Estimate what common jewelry repairs cost: chain soldering, prong re-tipping, stone replacement, clasp fitting, restringing and rhodium plating.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Repair Cost Estimator</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Typical independent-jeweler prices. Chain retailers charge more; mail-in services less, with more risk.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium mb-1" for="job">Repair needed</label>
                <select id="job" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white"></select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="metal">Metal</label>
                <select id="metal" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="1">Silver</option>
                    <option value="1.3" selected>Gold</option>
                    <option value="2">Platinum</option>
                </select>
            </div>
        </div>
        <div id="out" class="mt-6"></div>
        <p class="text-xs text-[color:var(--stone)] mt-5 border-t border-[color:var(--line)] pt-4">
            Get a written quote before authorising work, and photograph the piece from several angles first. For anything
            valuable, ask whether the work is done in-house or sent away — and whether the jeweler is insured while it is
            in their care.
        </p>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const JOBS = [
        ['Solder a broken chain', 25, 60, 'Quick for most chains. Herringbone and snake chains often cannot be repaired invisibly — ask before committing.'],
        ['Re-tip worn prongs (per prong)', 15, 40, 'Prongs thin over 5–10 years. Doing all four or six at once is cheaper than one at a time, and far cheaper than losing the stone.'],
        ['Replace a full head / setting', 90, 250, 'When prongs are beyond re-tipping. A new head is often better value than repeated repairs.'],
        ['Reset a loose stone', 30, 80, 'If a stone rattles, stop wearing it immediately — this is the cheapest repair on the list and prevents the most expensive loss.'],
        ['Replace a small melee stone', 40, 120, 'Includes the stone. Matching an unusual colour or cut costs more.'],
        ['Ring sizing (1 size)', 40, 120, 'Simple on plain bands; more on patterned or stone-set shanks.'],
        ['Fit a new clasp', 30, 90, 'Upgrading a spring ring to a lobster clasp is a worthwhile cheap improvement on any chain you wear often.'],
        ['Restring a pearl strand', 45, 150, 'With knots between pearls, as it should be. Restring annually if worn weekly — a snapped unknotted strand scatters everywhere.'],
        ['Rhodium replating (white gold)', 50, 120, 'Every 1–2 years as the plating wears. Have it done alongside a polish for a single labour charge.'],
        ['Professional clean & polish', 20, 60, 'Removes fine scratches and restores shine. Repeated polishing slowly removes metal, so do not overdo it.'],
        ['Rebuild a worn shank', 120, 400, 'When the band has thinned dangerously underneath — common on rings worn daily for 20+ years.'],
        ['Laser weld repair', 60, 200, 'Precise, low-heat welding. Necessary near heat-sensitive stones like emerald or opal.'],
        ['Appraisal (written, insurance)', 75, 200, 'Charged per piece or hourly. Use an independent appraiser, not the seller of the item.'],
    ];
    const job = document.getElementById('job'), metal = document.getElementById('metal'), out = document.getElementById('out');
    JOBS.forEach(function (j, i) {
        const o = document.createElement('option'); o.value = i; o.textContent = j[0]; job.appendChild(o);
    });
    function render() {
        const j = JOBS[parseInt(job.value, 10)], m = parseFloat(metal.value);
        const low = Math.round(j[1] * m), high = Math.round(j[2] * m);
        out.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC">' +
            '<p class="text-xs uppercase tracking-wider text-[color:var(--stone)]">Typical cost</p>' +
            '<p class="font-display text-3xl font-semibold" style="color:var(--gold)">$' + low + ' – $' + high + '</p>' +
            '<p class="text-sm mt-2">' + j[3] + '</p></div>';
    }
    job.addEventListener('change', render); metal.addEventListener('change', render);
    render();
})();
</script>
@endpush
