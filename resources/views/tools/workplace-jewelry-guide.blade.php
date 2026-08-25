@extends('layouts.app')
@section('title', 'Jewelry at Work — What’s Safe and Allowed — Gleamion.com')
@section('meta_description', 'What jewelry is safe and permitted in healthcare, food service, manufacturing, labs and corporate settings — and what to wear instead.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Jewelry at Work</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Some workplaces restrict jewelry for hygiene, some for safety, and some for both. Here is what usually applies, and what to wear instead.</p>

    <div class="card p-6 mt-6">
        <label class="block text-sm font-medium mb-1" for="job">Work environment</label>
        <select id="job" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white"></select>
        <div id="out" class="mt-6"></div>
    </div>

    <div class="card p-6 mt-6">
        <h2 class="font-display text-2xl font-semibold mb-3">The ring-removal problem</h2>
        <p class="text-sm text-[color:var(--stone)]">
            <strong class="text-[color:#23262e]">Degloving injury</strong> is the reason many industrial and mechanical
            workplaces ban rings outright: a ring caught on machinery or a ladder rung can strip the soft tissue from the
            finger. It is rare but catastrophic and effectively unrepairable.
        </p>
        <p class="text-sm text-[color:var(--stone)] mt-2">
            A related point worth knowing: <strong class="text-[color:#23262e]">tungsten and ceramic rings cannot be cut
            off in an emergency</strong> the way gold or silver can — they must be cracked with specialised pliers, and
            not every emergency department has them. If your hands are at risk of swelling or injury, a softer metal or a
            silicone band is the safer choice.
        </p>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const JOBS = {
        'Healthcare / nursing': {
            rules:'Most hospitals follow "bare below the elbow" policies: no wrist jewelry or watches, and usually a plain band only. Stones and settings harbour bacteria and tear gloves.',
            allowed:['Plain wedding band (often permitted)','Small stud earrings','Nothing on the wrists'],
            avoid:['Rings with stones or raised settings','Bracelets and wristwatches','Long necklaces that swing forward'],
            alt:'Wear the engagement ring on a chain under scrubs, or leave it at home. Silicone bands are widely accepted and cheap to replace.'
        },
        'Food service / kitchens': {
            rules:'Food hygiene regulations in most countries permit a plain wedding band only. Anything with crevices can trap food and bacteria, and stones can fall into food.',
            allowed:['Plain band','Plain stud earrings (sometimes)'],
            avoid:['Any stone-set ring','Bracelets and watches','Dangling earrings'],
            alt:'A silicone band satisfies most policies and survives dishwashing, heat and constant handwashing.'
        },
        'Manufacturing / mechanical': {
            rules:'Safety-driven. Rings and chains near rotating machinery are a serious entanglement and electrocution risk. Many sites ban all hand jewelry.',
            allowed:['Usually nothing on the hands','Necklaces tucked in, if permitted at all'],
            avoid:['Any ring near machinery','Bracelets','Loose chains'],
            alt:'Silicone bands break away under load by design — the standard solution for tradespeople who want to wear something.'
        },
        'Laboratory / chemical': {
            rules:'Chemicals seep under rings and stay in contact with skin. Some solvents also attack metals and dissolve stone treatments.',
            allowed:['Ideally nothing on the hands'],
            avoid:['Porous stones (opal, turquoise, pearl)','Plated jewelry','Anything you would be upset to damage'],
            alt:'Remove and store in a dedicated case. Chlorine and bromine attack gold alloys and cause stress cracking over time.'
        },
        'Corporate / office': {
            rules:'No safety restrictions — the only considerations are proportion and noise. Bracelets that clatter on a desk irritate colleagues and yourself.',
            allowed:['Essentially anything','One watch, one or two rings, a chain'],
            avoid:['Multiple loud bangles if you type all day','Very large rings that catch on keyboards'],
            alt:'Keep metals consistent across pieces for a deliberate look rather than an accumulated one.'
        },
        'Childcare / education': {
            rules:'Practical rather than regulated. Small hands grab, and dangling jewelry gets pulled hard.',
            allowed:['Studs','Plain rings','Short necklaces'],
            avoid:['Hoop and drop earrings','Long necklaces','Prong settings that scratch'],
            alt:'Bezel settings will not scratch a child who bumps into your hand, and hold up better to constant washing.'
        },
        'Gym / sport': {
            rules:'Sweat accelerates tarnish and nickel release; impact damages settings and bends bands.',
            allowed:['Silicone band','Titanium or steel pieces'],
            avoid:['Anything gold or set with stones','Chains that swing during movement'],
            alt:'Weight training in particular bends soft gold bands out of round — remove rings before lifting.'
        },
    };
    const job = document.getElementById('job'), out = document.getElementById('out');
    Object.keys(JOBS).forEach(function (k) { const o = document.createElement('option'); o.value = k; o.textContent = k; job.appendChild(o); });
    function list(items, colour) {
        return items.map(function (t) {
            return '<li class="flex gap-3 text-sm"><span class="facet-sm mt-1.5 shrink-0" style="background:' + colour + '"></span><span>' + t + '</span></li>';
        }).join('');
    }
    function render() {
        const j = JOBS[job.value];
        out.innerHTML = '<div class="border-l-4 p-4 mb-4" style="border-color:var(--gold); background:#FBF7EC"><p class="text-sm">' + j.rules + '</p></div>' +
            '<div class="grid gap-4 sm:grid-cols-2">' +
            '<div><h3 class="font-medium mb-2">Usually fine</h3><ul class="space-y-2">' + list(j.allowed, 'var(--gold)') + '</ul></div>' +
            '<div><h3 class="font-medium mb-2">Avoid</h3><ul class="space-y-2">' + list(j.avoid, '#C0563A') + '</ul></div></div>' +
            '<p class="text-sm text-[color:var(--stone)] mt-4"><strong>Instead:</strong> ' + j.alt + '</p>' +
            '<p class="text-xs text-[color:var(--stone)] mt-2">Always check your employer’s own policy — these are general norms, not rules that apply everywhere.</p>';
    }
    job.addEventListener('change', render);
    render();
})();
</script>
@endpush
