@extends('layouts.admin')

@section('title', 'AI descriptions')

@section('content')
    <h1 class="font-display text-3xl font-semibold text-velvet mb-2">AI description rewriter</h1>
    <p class="text-sm text-ink/60 mb-6">Rewrites business "About" texts with Google Gemini using your prompt. Placeholders: <code>{name}</code>, <code>{category}</code>, <code>{city}</code>, <code>{about}</code>.</p>

    @unless($keySet)
        <div class="border border-red-300 bg-red-50 text-red-800 text-sm px-4 py-3 mb-6">
            <strong>GEMINI_API_KEY is not set.</strong> Get a free key at
            <a href="https://aistudio.google.com/apikey" target="_blank" rel="noopener" class="underline">aistudio.google.com/apikey</a>,
            add <code>GEMINI_API_KEY=your-key</code> to <code>.env</code>, then run <code>php artisan config:cache</code>.
        </div>
    @endunless

    <div class="grid grid-cols-3 gap-4 mb-6 max-w-xl">
        <div class="card p-4 text-center"><p class="font-display text-2xl font-semibold text-velvet">{{ $counts['all'] }}</p><p class="text-xs uppercase tracking-widest text-gold mt-1">All profiles</p></div>
        <div class="card p-4 text-center"><p class="font-display text-2xl font-semibold text-velvet">{{ $counts['empty'] }}</p><p class="text-xs uppercase tracking-widest text-gold mt-1">Empty about</p></div>
        <div class="card p-4 text-center"><p class="font-display text-2xl font-semibold text-velvet">{{ $counts['short'] }}</p><p class="text-xs uppercase tracking-widest text-gold mt-1">Under 200 chars</p></div>
    </div>

    <div class="card p-6 max-w-3xl space-y-5">
        <div>
            <label class="form-label" for="prompt">Prompt</label>
            <textarea class="field font-mono text-xs" id="prompt" rows="9">{{ $defaultPrompt }}</textarea>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="form-label" for="mode">Which profiles</label>
                <select class="field" id="mode">
                    <option value="empty">Only empty descriptions ({{ $counts['empty'] }})</option>
                    <option value="short">Empty or short — under 200 chars ({{ $counts['short'] }})</option>
                    <option value="all">All profiles ({{ $counts['all'] }}) — overwrites everything</option>
                </select>
            </div>
            <div>
                <label class="form-label" for="batch">Profiles per request</label>
                <select class="field" id="batch">
                    <option value="3">3 (safest)</option>
                    <option value="5" selected>5</option>
                    <option value="10">10</option>
                </select>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button id="runBtn" class="btn btn-velvet" @disabled(! $keySet)>Start rewriting</button>
            <span id="progressText" class="text-sm text-ink/60">Model: {{ $model }}</span>
        </div>
        <p class="text-xs text-ink/50">Runs while this tab is open; each profile is saved as soon as it's rewritten, so you can stop anytime. Free-tier API keys are rate-limited — if errors appear, lower the batch size or wait a minute.</p>
    </div>

    <div class="mt-6 max-w-3xl">
        <div id="logBox" class="card p-4 text-xs font-mono max-h-80 overflow-auto hidden"></div>
    </div>

    <script>
        const runBtn = document.getElementById('runBtn');
        const progressText = document.getElementById('progressText');
        const logBox = document.getElementById('logBox');
        let running = false;

        function log(line, ok) {
            logBox.classList.remove('hidden');
            const div = document.createElement('div');
            div.textContent = line;
            div.style.color = ok ? '#14352C' : '#b91c1c';
            logBox.prepend(div);
        }

        runBtn.addEventListener('click', async function () {
            if (running) { running = false; runBtn.textContent = 'Start rewriting'; return; }
            running = true;
            runBtn.textContent = 'Stop';
            let lastId = 0, done = 0, failed = 0;

            while (running) {
                try {
                    const res = await fetch('{{ route('admin.ai.rewrite.run') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                        body: JSON.stringify({
                            prompt: document.getElementById('prompt').value,
                            mode: document.getElementById('mode').value,
                            batch: parseInt(document.getElementById('batch').value, 10),
                            last_id: lastId
                        })
                    });
                    if (!res.ok) { throw new Error('HTTP ' + res.status); }
                    const data = await res.json();
                    lastId = data.last_id;
                    (data.items || []).forEach(item => {
                        if (item.ok) { done++; log('✓ ' + item.name, true); }
                        else { failed++; log('✗ ' + item.name + ' — ' + (item.error || 'failed'), false); }
                    });
                    progressText.textContent = `${done} rewritten · ${failed} failed`;
                    if (data.fatal) { log('STOPPED: ' + data.fatal, false); running = false; runBtn.textContent = 'Start rewriting'; }
                    if (data.done) {
                        progressText.textContent += ' — finished!';
                        running = false;
                        runBtn.textContent = 'Start rewriting';
                    }
                } catch (e) {
                    log('Request failed: ' + e.message + ' — press Start to continue.', false);
                    running = false;
                    runBtn.textContent = 'Start rewriting';
                }
            }
        });
    </script>
@endsection
