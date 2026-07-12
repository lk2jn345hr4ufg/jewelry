<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Gleamion.com — Coming soon</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600&family=Jost:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root { --ink:#1C1A17; --gold:#B08C3D; --paper:#FAF7F1; --line:#E7DFCE; --stone:#8B8574; }
        * { box-sizing: border-box; margin: 0; }
        body { min-height: 100vh; display: flex; align-items: center; justify-content: center;
               background: var(--ink); color: var(--paper); font-family: 'Jost', sans-serif; padding: 1.5rem; }
        .box { width: 100%; max-width: 24rem; text-align: center; }
        .facet { display: inline-block; width: 12px; height: 12px; border: 1.5px solid var(--gold); transform: rotate(45deg); margin-bottom: 1.25rem; }
        h1 { font-family: 'Cormorant Garamond', serif; font-size: 2.2rem; font-weight: 600; }
        p { color: var(--stone); margin: .75rem 0 1.75rem; font-size: .95rem; }
        input { width: 100%; padding: .8rem 1rem; border: 1px solid var(--line); background: var(--paper);
                color: var(--ink); font: inherit; font-size: 1rem; }
        input:focus { outline: 2px solid var(--gold); border-color: var(--gold); }
        button { width: 100%; margin-top: .75rem; padding: .8rem 1rem; background: var(--gold); color: #fff;
                 border: 0; font: inherit; font-weight: 500; letter-spacing: .05em; cursor: pointer; }
        button:hover { background: #9a7930; }
        .error { color: #f0a9a9; font-size: .85rem; margin-top: .75rem; }
    </style>
</head>
<body>
    <div class="box">
        <span class="facet"></span>
        <h1>Gleamion.com</h1>
        <p>The jewelry directory is being polished.<br>Enter the access password to preview.</p>
        <form method="post" action="{{ route('site.unlock') }}">
            @csrf
            <input type="password" name="password" placeholder="Access password" autofocus required>
            <button type="submit">Enter</button>
            @error('password')<div class="error">{{ $message }}</div>@enderror
        </form>
    </div>
</body>
</html>
