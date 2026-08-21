@extends('layouts.app')

@section('title', 'Jewelry Gift Finder — Ideas by Recipient, Occasion & Budget — Gleamion.com')
@section('meta_description', 'Stuck for a jewelry gift? Choose the recipient, occasion and budget to get specific, practical suggestions with sizing advice.')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    @include('tools._nav')

    <p class="eyebrow mb-2 mt-6">Tool</p>
    <h1 class="font-display text-4xl font-semibold">Jewelry Gift Finder</h1>
    <div class="rule-gold mt-3 mb-4" style="max-width:10rem"></div>
    <p class="text-[color:var(--stone)]">Three choices, and we’ll suggest pieces that fit — including which ones don’t need you to know their size.</p>

    <div class="card p-6 mt-6">
        <div class="grid gap-5 sm:grid-cols-3">
            <div>
                <label class="block text-sm font-medium mb-1" for="who">Who is it for?</label>
                <select id="who" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="partner" selected>Partner / spouse</option>
                    <option value="mother">Mother</option>
                    <option value="friend">Friend</option>
                    <option value="teen">Teenager</option>
                    <option value="men">Man</option>
                    <option value="milestone">New baby / new home</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="occasion">Occasion</label>
                <select id="occasion" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="birthday" selected>Birthday</option>
                    <option value="anniversary">Anniversary</option>
                    <option value="holiday">Christmas / holiday</option>
                    <option value="graduation">Graduation</option>
                    <option value="justbecause">Just because</option>
                    <option value="apology">Making amends</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="budget">Budget</label>
                <select id="budget" class="w-full border border-[color:var(--line)] px-3 py-2 bg-white">
                    <option value="1">Under $100</option>
                    <option value="2" selected>$100 – $400</option>
                    <option value="3">$400 – $1,500</option>
                    <option value="4">$1,500+</option>
                </select>
            </div>
        </div>
        <div id="ideas" class="mt-6 space-y-3"></div>
        <div id="tip" class="mt-4"></div>
    </div>

    @include('tools._other')
</div>
@endsection

@push('scripts')
<script>
(function () {
    // idea: [name, minBudget, sizingRisk, note]
    const BASE = {
        partner: [
            ['Pendant necklace', 1, 'none', 'The safest fine-jewelry gift — no sizing needed and it suits every style. A birthstone or initial makes it personal.'],
            ['Stud earrings', 1, 'none', 'Diamond, pearl or gemstone studs get worn constantly. Check whether their ears are pierced first.'],
            ['Tennis bracelet', 3, 'low', 'A classic milestone gift. Bracelets are forgiving on sizing and most can be shortened afterwards.'],
            ['Eternity or anniversary band', 4, 'high', 'Beautiful but needs their exact ring size — borrow an existing ring and have it measured.'],
            ['Layering chain set', 2, 'none', 'Two or three chains of different lengths; lets them build a look rather than wear one fixed piece.'],
        ],
        mother: [
            ['Birthstone pendant (children’s stones)', 1, 'none', 'One stone per child. Consistently the most-loved gift in this category.'],
            ['Pearl studs or strand', 2, 'none', 'Timeless, appropriate for any age and any occasion.'],
            ['Charm bracelet', 2, 'low', 'Builds over years — you can add to it each birthday, which makes future gifting easy.'],
            ['Locket', 1, 'none', 'Sentimental and inexpensive; add a photo before wrapping it.'],
        ],
        friend: [
            ['Silver or gold-vermeil earrings', 1, 'none', 'Generous without being uncomfortably intimate.'],
            ['Delicate chain bracelet', 1, 'low', 'Adjustable styles remove the sizing problem entirely.'],
            ['Birthstone ring', 2, 'high', 'Lovely, but you need their size — risky unless you can check discreetly.'],
            ['Enamel or beaded piece', 1, 'none', 'Colourful and casual; a good choice when fine jewelry would feel too weighty.'],
        ],
        teen: [
            ['Small hoop earrings', 1, 'none', 'Endlessly wearable. Sterling silver or 14K gold-filled resists reactions better than plated.'],
            ['Initial or name pendant', 1, 'none', 'Personal, inexpensive and reliably well received.'],
            ['Birthstone stud earrings', 1, 'none', 'A first “proper” piece of jewelry without being overwhelming.'],
            ['Adjustable ring', 1, 'low', 'Sidesteps sizing entirely — useful when you cannot ask.'],
        ],
        men: [
            ['Signet or band ring', 2, 'high', 'Striking, but men’s ring sizes vary widely — measure rather than guess.'],
            ['Cufflinks', 1, 'none', 'Only if they actually wear French-cuff shirts; otherwise they sit in a drawer.'],
            ['Leather or steel bracelet', 1, 'low', 'Casual and easy to wear. Most have adjustable clasps.'],
            ['Watch', 3, 'low', 'The classic men’s milestone gift. Straps and metal bracelets can both be adjusted after.'],
            ['Chain necklace', 2, 'none', 'Choose weight carefully — a chain that is too fine reads as delicate, too heavy as ostentatious.'],
        ],
        milestone: [
            ['Baby’s birthstone pendant', 1, 'none', 'For the parent, marking the child’s birth month.'],
            ['Engraved bar necklace', 1, 'none', 'A name, date or coordinates — quietly meaningful.'],
            ['Mother’s ring', 3, 'high', 'One stone per child. Needs an accurate ring size.'],
        ],
    };

    const OCCASION_NOTE = {
        birthday: 'Birthstone pieces are an easy win for a birthday — personal without needing you to guess their taste.',
        anniversary: 'Check the traditional gemstone for the year — our anniversary guide lists all of them, and it makes an ordinary gift feel considered.',
        holiday: 'Buy earlier than you think. Jewelers get busy in December, and engraving or resizing can take one to two weeks.',
        graduation: 'Choose something durable that suits a first job — classic studs or a simple watch outlast trend pieces.',
        justbecause: 'An unprompted gift lands harder than an expected one. Keep it modest and personal rather than grand.',
        apology: 'Jewelry does not repair a rift on its own. If you give it, keep the focus on the conversation, not the box — an expensive piece offered instead of an apology tends to land badly.',
    };

    const who = document.getElementById('who');
    const occasion = document.getElementById('occasion');
    const budget = document.getElementById('budget');
    const ideas = document.getElementById('ideas');
    const tip = document.getElementById('tip');

    const RISK = { none:['No sizing needed','var(--gold)'], low:['Easy to adjust','var(--stone)'], high:['Need their size','#C0563A'] };

    function render() {
        const b = parseInt(budget.value, 10);
        const list = BASE[who.value].filter(function (i) { return i[1] <= b; });

        ideas.innerHTML = list.length ? list.map(function (i) {
            const r = RISK[i[2]];
            return '<div class="border border-[color:var(--line)] p-4">' +
                '<div class="flex flex-wrap items-baseline gap-3">' +
                '<h3 class="font-display text-xl font-semibold">' + i[0] + '</h3>' +
                '<span class="text-xs px-2 py-0.5 border" style="border-color:' + r[1] + ';color:' + r[1] + '">' + r[0] + '</span></div>' +
                '<p class="text-sm text-[color:var(--stone)] mt-2">' + i[3] + '</p></div>';
        }).join('') : '<p class="text-sm text-[color:var(--stone)]">Nothing in this budget for that recipient — try widening the budget.</p>';

        tip.innerHTML = '<div class="border-l-4 p-4" style="border-color:var(--gold); background:#FBF7EC">' +
            '<p class="text-sm">' + OCCASION_NOTE[occasion.value] + '</p></div>';
    }

    [who, occasion, budget].forEach(function (e) { e.addEventListener('change', render); });
    render();
})();
</script>
@endpush
