@extends('layouts.admin')

@section('title', 'Website check')

@section('content')
    <h1 class="font-display text-3xl font-semibold text-velvet mb-2">Website check</h1>
    <p class="text-sm text-ink/60 mb-6">Finds business websites that no longer respond (connection failed, 404, 410) so you can hide or delete those profiles.</p>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="card p-4 text-center"><p class="font-display text-3xl font-semibold text-velvet">{{ $total }}</p><p class="text-xs uppercase tracking-widest text-gold mt-1">With website</p></div>
        <div class="card p-4 text-center"><p class="font-display text-3xl font-semibold text-velvet" id="statChecked">{{ $checked }}</p><p class="text-xs uppercase tracking-widest text-gold mt-1">Checked</p></div>
        <div class="card p-4 text-center"><p class="font-display text-3xl font-semibold text-red-700">{{ $deadCount }}</p><p class="text-xs uppercase tracking-widest text-gold mt-1">Dead sites</p></div>
        <div class="card p-4 text-center"><p class="font-display text-3xl font-semibold text-amber-700">{{ $errorCount }}</p><p class="text-xs uppercase tracking-widest text-gold mt-1">Other errors</p></div>
    </div>

    <div class="card p-5 mb-8">
        <div class="flex flex-wrap items-center gap-3">
            <button id="runBtn" class="btn btn-velvet text-sm">Run check</button>
            <form method="post" action="{{ route('admin.sitecheck.hidedead') }}" onsubmit="return confirm('Hide ALL businesses whose website is dead (0 / 404 / 410)?')">
                @csrf
                <button class="btn btn-outline text-sm">Hide all dead</button>
            </form>
            <form method="post" action="{{ route('admin.sitecheck.reset') }}" onsubmit="return confirm('Clear all results and start over?')">
                @csrf
                <button class="btn btn-outline text-sm">Reset results</button>
            </form>
            <span id="progressText" class="text-sm text-ink/60">{{ $total - $checked }} websites left to check.</span>
        </div>
        <p class="text-xs text-ink/50 mt-3">Checking runs in batches of ~15 while this tab stays open. {{ $total }} sites take a while — you can stop anytime and continue later; results are saved as it goes.</p>
    </div>

    <h2 class="eyebrow mb-4"><span class="facet"></span> Problem websites</h2>
    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-porcelain text-left text-xs uppercase tracking-widest text-gold">
                <tr>
                    <th class="p-3">Business</th>
                    <th class="p-3">Website</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Visible</th>
                    <th class="p-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($problems as $business)
                    <tr class="border-t border-line">
                        <td class="p-3">
                            <span class="font-bold">{{ $business->name }}</span><br>
                            <span class="text-ink/50 text-xs">{{ $business->category?->name }} · {{ $business->city?->name }}</span>
                        </td>
                        <td class="p-3 max-w-xs truncate">
                            <a href="{{ $business->website }}" target="_blank" rel="noopener nofollow" class="text-velvet hover:text-gold">{{ Str::limit($business->website, 45) }}</a>
                        </td>
                        <td class="p-3">
                            @if(in_array($business->website_status, [0, 404, 410]))
                                <span class="text-xs font-bold text-red-700 border border-red-200 px-2 py-0.5">{{ $business->website_status === 0 ? 'NO CONNECTION' : $business->website_status }}</span>
                            @else
                                <span class="text-xs font-bold text-amber-700 border border-amber-200 px-2 py-0.5">{{ $business->website_status }}</span>
                            @endif
                        </td>
                        <td class="p-3">{{ $business->is_active ? 'Yes' : 'Hidden' }}</td>
                        <td class="p-3 text-right whitespace-nowrap">
                            @if($business->is_active)
                                <form method="post" action="{{ route('admin.sitecheck.hide', $business) }}" class="inline">
                                    @csrf @method('PATCH')
                                    <button class="text-velvet font-bold hover:text-gold">Hide</button>
                                </form>
                            @endif
                            <a href="{{ route('admin.businesses.edit', $business) }}" class="ml-3 text-velvet font-bold hover:text-gold">Edit</a>
                            <form method="post" action="{{ route('admin.businesses.destroy', $business) }}" class="inline ml-3"
                                  onsubmit="return confirm('Permanently delete this business, its reviews and coupons?')">
                                @csrf @method('DELETE')
                                <button class="text-red-700 font-bold hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-6 text-center text-ink/60">No problem websites found yet — run the check.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $problems->links() }}</div>

    <script>
        const runBtn = document.getElementById('runBtn');
        const progressText = document.getElementById('progressText');
        let running = false;

        runBtn.addEventListener('click', async function () {
            if (running) { running = false; runBtn.textContent = 'Run check'; return; }
            running = true;
            runBtn.textContent = 'Stop';
            let checkedNow = 0, foundNow = 0;

            while (running) {
                try {
                    const res = await fetch('{{ route('admin.sitecheck.run') }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                    });
                    const data = await res.json();
                    checkedNow += data.checked;
                    foundNow += data.found;
                    progressText.textContent = `Checked ${checkedNow} this session · ${data.remaining} remaining · ${foundNow} problems found`;
                    if (data.done) {
                        progressText.textContent += ' — finished! Reloading…';
                        running = false;
                        setTimeout(() => location.reload(), 1200);
                    }
                } catch (e) {
                    progressText.textContent = 'Request failed — press Run to continue.';
                    running = false;
                    runBtn.textContent = 'Run check';
                }
            }
        });
    </script>
@endsection
