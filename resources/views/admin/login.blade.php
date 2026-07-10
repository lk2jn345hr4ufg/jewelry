<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin login — {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600&family=Karla:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: {
            colors: { porcelain:'#FAF7F1', ink:'#221D14', velvet:'#14352C', gold:'#B08C3D', goldlight:'#D9C08A', line:'#E7DFCE' },
            fontFamily: { display:['"Cormorant Garamond"','serif'], body:['Karla','sans-serif'] }
        } } };
    </script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="font-body bg-velvet min-h-screen flex items-center justify-center p-4">
    <form method="post" action="{{ route('admin.login.post') }}" class="bg-white w-full max-w-sm p-8 border-t-2 border-gold">
        @csrf
        <div class="text-center mb-6">
            <span class="facet facet--outline !w-3 !h-3 inline-block mb-3"></span>
            <h1 class="font-display text-3xl font-semibold text-velvet">Admin sign in</h1>
        </div>
        @if($errors->any())
            <div class="border border-red-300 bg-red-50 text-red-800 text-sm px-3 py-2 mb-4">{{ $errors->first() }}</div>
        @endif
        <label class="form-label" for="email">Email</label>
        <input class="field mb-4" type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
        <label class="form-label" for="password">Password</label>
        <input class="field mb-4" type="password" id="password" name="password" required>
        <label class="flex items-center gap-2 text-sm mb-5">
            <input type="checkbox" name="remember" value="1"> Remember me
        </label>
        <button class="btn btn-velvet w-full">Sign in</button>
    </form>
</body>
</html>
