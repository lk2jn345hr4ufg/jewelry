<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gleamion.com — Jewelry Business Directory')</title>
    <meta name="description" content="@yield('meta_description', 'Find trusted jewelry businesses near you: jewelers, watch repair, engagement rings, appraisers and more.')">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    @stack('head')
    <style>
        :root {
            --ink: #171A21;
            --ink-soft: #232733;
            --gold: #B98B2F;
            --champagne: #D8BC7A;
            --paper: #FBFAF7;
            --stone: #5C6270;
            --line: #E7E2D6;
        }
        body { font-family: 'Jost', sans-serif; background: var(--paper); color: #23262e; }
        .font-display { font-family: 'Cormorant Garamond', serif; }
        .facet { display:inline-block; width:.5em; height:.5em; background:var(--gold); transform:rotate(45deg); }
        .facet-sm { display:inline-block; width:7px; height:7px; transform:rotate(45deg); }
        .eyebrow { letter-spacing:.22em; text-transform:uppercase; font-size:.72rem; font-weight:500; color:var(--gold); }
        .rule-gold { height:1px; background:linear-gradient(90deg, var(--gold), transparent); }
        .btn-gold { background:var(--gold); color:#fff; transition:background .15s; }
        .btn-gold:hover { background:#9e7526; }
        .btn-ghost { border:1px solid var(--line); transition:border-color .15s, color .15s; }
        .btn-ghost:hover { border-color:var(--gold); color:var(--gold); }
        .card { background:#fff; border:1px solid var(--line); }
        .card:hover { border-color:var(--champagne); }
        input:focus, textarea:focus, select:focus { outline:none; border-color:var(--gold) !important; }
        .suggest-item:hover, .suggest-item.active { background:#F6F1E4; }
        @media (prefers-reduced-motion: reduce) { * { transition:none !important; animation:none !important; } }
    </style>
</head>
<body class="min-h-screen flex flex-col">

<header style="background:var(--ink)" class="text-white">
    <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between gap-4">
        <a href="{{ route('home') }}" class="flex items-center gap-3 group">
            <span class="facet !w-3 !h-3 group-hover:rotate-[135deg] transition-transform duration-300"></span>
            <span class="font-display text-2xl font-semibold tracking-wide">Gleamion.com</span>
        </a>
        <nav class="flex items-center gap-2 sm:gap-6 text-sm">
            <a href="{{ route('home') }}" class="hover:text-[color:var(--champagne)] px-2 py-1">Home</a>
            <a href="{{ route('coupons.index') }}" class="hover:text-[color:var(--champagne)] px-2 py-1">Stores &amp; Deals</a>
            <a href="{{ route('search') }}" class="hidden sm:inline hover:text-[color:var(--champagne)] px-2 py-1">All businesses</a>
        </nav>
    </div>
</header>

<main class="flex-1">
    @yield('content')
</main>

<footer style="background:var(--ink)" class="text-white mt-16">
    <div class="max-w-6xl mx-auto px-4 py-10 grid gap-8 sm:grid-cols-3 text-sm">
        <div>
            <div class="flex items-center gap-2 mb-3">
                <span class="facet"></span>
                <span class="font-display text-xl font-semibold">Gleamion.com</span>
            </div>
            <p class="text-white/60 leading-relaxed">A directory of jewelry businesses — jewelers, watchmakers, appraisers and designers in your city.</p>
        </div>
        <div>
            <p class="eyebrow mb-3">Explore</p>
            <ul class="space-y-2 text-white/70">
                <li><a href="{{ route('home') }}" class="hover:text-[color:var(--champagne)]">Browse by city</a></li>
                <li><a href="{{ route('coupons.index') }}" class="hover:text-[color:var(--champagne)]">Stores, coupons &amp; deals</a></li>
                <li><a href="{{ route('search') }}" class="hover:text-[color:var(--champagne)]">Search businesses</a></li>
            </ul>
        </div>
        <div>
            <p class="eyebrow mb-3">Owners</p>
            <ul class="space-y-2 text-white/70">
                <li><a href="{{ route('admin.login') }}" class="hover:text-[color:var(--champagne)]">Admin sign in</a></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-white/10 py-4 text-center text-xs text-white/40">© {{ date('Y') }} Gleamion.com</div>
</footer>

<script>
document.querySelectorAll('[data-suggest]').forEach(function (input) {
    const box = input.closest('.suggest-wrap').querySelector('.suggest-box');
    let timer = null, controller = null;

    input.addEventListener('input', function () {
        clearTimeout(timer);
        const q = input.value.trim();
        if (q.length < 2) { box.classList.add('hidden'); box.innerHTML = ''; return; }
        timer = setTimeout(async function () {
            if (controller) controller.abort();
            controller = new AbortController();
            try {
                const res = await fetch(`{{ route('search.suggest') }}?q=${encodeURIComponent(q)}`, { signal: controller.signal });
                const data = await res.json();
                if (!data.suggestions.length) { box.classList.add('hidden'); box.innerHTML = ''; return; }
                box.innerHTML = data.suggestions.map(s =>
                    `<a href="${s.url}" class="suggest-item flex items-center justify-between gap-3 px-4 py-2.5 text-sm">
                        <span class="truncate text-gray-800">${escapeHtml(s.label)}</span>
                        <span class="shrink-0 text-xs" style="color:var(--gold)">${escapeHtml(s.type)}${s.meta ? ' · ' + escapeHtml(s.meta) : ''}</span>
                    </a>`).join('');
                box.classList.remove('hidden');
            } catch (e) { /* aborted */ }
        }, 220);
    });

    document.addEventListener('click', function (e) {
        if (!input.closest('.suggest-wrap').contains(e.target)) box.classList.add('hidden');
    });
});

function escapeHtml(s) {
    return String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
}
</script>
@stack('scripts')
</body>
</html>
