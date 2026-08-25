@extends('layouts.app')

@section('title', 'Earring Style Guide — Which Suits Your Face & Lifestyle — Gleamion.com')
@section('meta_description', 'Compare studs, hoops, drops, huggies and more on comfort, security and what suits your face shape and daily routine.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Earring Style Guide</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Filter by what matters to you — weight and back type decide whether earrings get worn or left in a drawer.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-3">
            <div>
                <label class="block text-sm font-medium mb-1" for="face">Face shape</label>
                <select id="face" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="any" selected>No preference</option>
                    <option value="round">Round</option>
                    <option value="oval">Oval</option>
                    <option value="square">Square / angular</option>
                    <option value="heart">Heart</option>
                    <option value="long">Long / oblong</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="wear">Wearing them</label>
                <select id="wear" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="any" selected>Any occasion</option>
                    <option value="daily">Every day, never removed</option>
                    <option value="work">Work / professional</option>
                    <option value="evening">Evening and events</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="ears">Ear considerations</label>
                <select id="ears" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="none" selected>None</option>
                    <option value="stretched">Stretched or sensitive lobes</option>
                    <option value="unpierced">Not pierced</option>
                </select>
            </div>
        </div>
        <div id="list" class="mt-6 space-y-3"></div>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    const STYLES = [
        { name:'Stud', weight:1, secure:5, faces:['any','round','oval','square','heart','long'], wear:['daily','work','evening'], pierced:true,
          note:'The most worn earring there is — light, secure and appropriate everywhere. Use screw-backs or locking backs for anything valuable; friction backs work loose and are the main reason single earrings go missing.' },
        { name:'Huggie', weight:2, secure:5, faces:['any','round','oval','square','heart','long'], wear:['daily','work'], pierced:true,
          note:'A small hoop that hugs the lobe. Excellent for sleeping in and for stretched lobes, since the weight sits close rather than pulling down.' },
        { name:'Small hoop', weight:2, secure:4, faces:['any','square','long'], wear:['daily','work','evening'], pierced:true,
          note:'Softens angular features. Light enough for daily wear; check the hinge or click closure regularly as they loosen with time.' },
        { name:'Large hoop', weight:4, secure:3, faces:['any','square','long'], wear:['evening'], pierced:true,
          note:'Bold and elongating, but heavier hoops stretch lobes over years. Tube hoops are hollow and much lighter than they look — ask.' },
        { name:'Drop', weight:3, secure:4, faces:['any','round','square'], wear:['work','evening'], pierced:true,
          note:'A fixed element hanging below the lobe. Lengthens a rounder face. Avoid anything that swings into a collar all day.' },
        { name:'Chandelier', weight:5, secure:3, faces:['any','round','square'], wear:['evening'], pierced:true,
          note:'Statement evening pieces. Genuinely heavy — expect discomfort after several hours and never sleep in them.' },
        { name:'Threader', weight:1, secure:2, faces:['any','round','oval'], wear:['evening'], pierced:true,
          note:'A fine chain that threads through the piercing. Feather-light and modern, but with nothing holding them, they slip out easily.' },
        { name:'Ear climber', weight:2, secure:4, faces:['any','oval','heart'], wear:['work','evening'], pierced:true,
          note:'Follows the curve of the ear upward. Distinctive and surprisingly comfortable; needs the right lobe shape to sit flush.' },
        { name:'Ear cuff', weight:2, secure:3, faces:['any'], wear:['evening','work'], pierced:false,
          note:'Clips onto the cartilage with no piercing required. Can pinch if worn for many hours — take it off before it aches.' },
        { name:'Clip-on', weight:3, secure:3, faces:['any'], wear:['evening','work'], pierced:false,
          note:'Modern clip mechanisms are far more comfortable than older ones. The only option for unpierced ears at any size.' },
        { name:'Jacket', weight:3, secure:4, faces:['any','oval','heart'], wear:['evening'], pierced:true,
          note:'A stud with a decorative element behind the lobe. Versatile — wear the stud alone by day, add the jacket at night.' },
    ];

    const face = document.getElementById('face');
    const wear = document.getElementById('wear');
    const ears = document.getElementById('ears');
    const list = document.getElementById('list');

    function bar(label, n, invert) {
        let d = '';
        for (let i = 1; i <= 5; i++) d += '<span class="facet-sm" style="background:' + (i <= n ? 'var(--gold)' : 'var(--line)') + '"></span>';
        return '<div class="flex items-center gap-2 text-xs"><span class="w-16 text-[color:var(--stone)]">' + label + '</span><span class="flex gap-1">' + d + '</span></div>';
    }

    function render() {
        let out = STYLES.filter(function (s) {
            if (ears.value === 'unpierced' && s.pierced) return false;
            if (ears.value === 'stretched' && s.weight > 2) return false;
            if (face.value !== 'any' && !s.faces.includes(face.value)) return false;
            if (wear.value !== 'any' && !s.wear.includes(wear.value)) return false;
            return true;
        });

        list.innerHTML = out.length ? out.map(function (s) {
            return '<div class="border border-[color:var(--line)] p-4">' +
                '<div class="flex flex-wrap gap-4 justify-between items-start">' +
                '<h3 class="font-display text-xl font-semibold">' + s.name + '</h3>' +
                '<div class="space-y-1">' + bar('Weight', s.weight) + bar('Secure', s.secure) + '</div></div>' +
                '<p class="text-sm text-[color:var(--stone)] mt-2">' + s.note + '</p></div>';
        }).join('') : '<p class="text-sm text-[color:var(--stone)]">Nothing matches those filters — try relaxing one.</p>';
    }

    [face, wear, ears].forEach(function (e) { e.addEventListener('change', render); });
    render();
})();
</script>
@endpush
