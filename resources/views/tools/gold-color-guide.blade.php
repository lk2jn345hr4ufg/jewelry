@extends('layouts.app')
@section('title', 'Gold Colour Guide — Yellow, White, Rose & Green — Gleamion.com')
@section('meta_description', 'What makes gold yellow, white, rose or green, how each wears over time, and which suits your skin tone and stone.')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')
    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Gold Colour Guide</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">All gold is yellow. The colour you see comes from what it is alloyed with — and that changes how it ages.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label class="block text-sm font-medium mb-1" for="skin">Skin undertone</label>
                <select id="skin" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="any" selected>Not sure</option>
                    <option value="warm">Warm — veins look green, gold suits you</option>
                    <option value="cool">Cool — veins look blue, silver suits you</option>
                    <option value="neutral">Neutral — both work</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="upkeep">Upkeep tolerance</label>
                <select id="upkeep" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="any" selected>No preference</option>
                    <option value="low">I want zero maintenance</option>
                </select>
            </div>
        </div>
        <div id="cards" class="mt-6 space-y-3"></div>
    </div>
    @include('tools._other')
</div>
@endsection
@push('scripts')
<script>
(function () {
    const GOLDS = [
        { name:'Yellow gold', colour:'#D9B44A', alloy:'Silver + copper', skin:['warm','neutral'], upkeep:5,
          note:'The traditional choice, and the only one that needs no plating. Warm tone flatters warm undertones and masks lower diamond colour grades. Shows scratches as a soft patina rather than bright marks.' },
        { name:'White gold', colour:'#E8E8E6', alloy:'Palladium or nickel + rhodium plating', skin:['cool','neutral'], upkeep:2,
          note:'Not naturally white — it is plated with rhodium, which wears through every 1–2 years and needs replating at $50–100. Nickel-alloyed versions are a common allergy trigger; ask for palladium white gold instead.' },
        { name:'Rose gold', colour:'#E0A899', alloy:'Higher copper content', skin:['warm','neutral'], upkeep:5,
          note:'Copper gives the pink tone and also makes it the hardest and most scratch-resistant of the three. Never needs plating. Deepens in colour slightly over decades, which most people like.' },
        { name:'Green gold (electrum)', colour:'#C9C67A', alloy:'Silver-heavy, no copper', skin:['neutral','cool'], upkeep:4,
          note:'A subtle pale yellow-green, rare in commercial jewelry. Softer than other alloys and mostly seen in artisan and Art Nouveau pieces.' },
        { name:'Two-tone / mixed', colour:'linear-gradient(90deg,#D9B44A 50%,#E8E8E6 50%)', alloy:'Combined', skin:['warm','cool','neutral'], upkeep:3,
          note:'Combines metals in one piece — practical if you already own both, since it matches everything. White portions still need periodic replating.' },
    ];
    const skin = document.getElementById('skin'), upkeep = document.getElementById('upkeep');
    const cards = document.getElementById('cards');
    function render() {
        const s = skin.value, u = upkeep.value;
        const list = GOLDS.filter(function (g) {
            if (s !== 'any' && !g.skin.includes(s)) return false;
            if (u === 'low' && g.upkeep < 4) return false;
            return true;
        });
        cards.innerHTML = list.length ? list.map(function (g) {
            return '<div class="border border-[color:var(--line)] p-4 flex gap-4">' +
                '<div style="width:48px;height:48px;border-radius:50%;background:' + g.colour + ';border:1px solid #bbb;flex:none"></div>' +
                '<div><h3 class="font-display text-xl font-semibold">' + g.name + '</h3>' +
                '<p class="text-xs text-[color:var(--stone)]">Alloyed with: ' + g.alloy + '</p>' +
                '<p class="text-sm text-[color:var(--stone)] mt-2">' + g.note + '</p></div></div>';
        }).join('') : '<p class="text-sm text-[color:var(--stone)]">Nothing matches — try relaxing a filter.</p>';
    }
    skin.addEventListener('change', render); upkeep.addEventListener('change', render);
    render();
})();
</script>
@endpush
