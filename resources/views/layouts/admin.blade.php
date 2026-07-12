<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Karla:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: {
            colors: { porcelain:'#FAF7F1', ink:'#221D14', velvet:'#14352C', velvetdeep:'#0D2620', gold:'#B08C3D', goldlight:'#D9C08A', line:'#E7DFCE' },
            fontFamily: { display:['"Cormorant Garamond"','serif'], body:['Karla','sans-serif'] }
        } } };
    </script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="font-body bg-porcelain min-h-screen md:flex">

    <aside class="bg-velvet text-white md:w-60 md:min-h-screen flex-none">
        <div class="p-5 flex items-center gap-3 border-b border-white/10">
            <span class="facet facet--outline !w-3 !h-3"></span>
            <div>
                <p class="font-display text-xl font-semibold leading-none">Gleamion.com</p>
                <p class="text-[0.6rem] uppercase tracking-[0.25em] text-goldlight mt-1">Admin panel</p>
            </div>
        </div>
        @php($nav = [
            'Dashboard' => route('admin.dashboard'),
            'Businesses' => route('admin.businesses.index'),
            'Categories' => route('admin.categories.index'),
            'Cities' => route('admin.cities.index'),
            'Reviews' => route('admin.reviews.index'),
            'Coupons' => route('admin.coupons.index'),
            'Import' => route('admin.import.form'),
            'Website check' => route('admin.sitecheck.index'),
            'Redirects' => route('admin.redirects.index'),
            'AI descriptions' => route('admin.ai.rewrite'),
        ])
        <nav class="p-3 flex md:flex-col gap-1 overflow-x-auto text-sm">
            @foreach($nav as $label => $url)
                <a href="{{ $url }}" @class([
                    'px-3 py-2 whitespace-nowrap hover:bg-white/10',
                    'bg-white/10 border-l-2 border-gold' => url()->current() === $url,
                ])>{{ $label }}</a>
            @endforeach
            <a href="{{ route('home') }}" class="px-3 py-2 whitespace-nowrap text-goldlight/80 hover:bg-white/10">View site ↗</a>
            <form method="post" action="{{ route('admin.logout') }}">
                @csrf
                <button class="px-3 py-2 text-left w-full text-white/60 hover:bg-white/10 whitespace-nowrap">Log out</button>
            </form>
        </nav>
    </aside>

    <main class="flex-1 p-4 md:p-8 max-w-6xl w-full mx-auto">
        @if(session('ok'))
            <div class="border border-gold bg-goldlight/25 text-velvet px-4 py-3 mb-6 text-sm">{{ session('ok') }}</div>
        @endif
        @yield('content')
    </main>
</body>
</html>
