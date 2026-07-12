@extends('layouts.admin')

@section('title', 'SEO redirects')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="font-display text-3xl font-semibold text-velvet">SEO redirects</h1>
        <a href="{{ route('admin.redirects.create') }}" class="btn btn-gold text-sm">Add redirect</a>
    </div>

    <div class="card overflow-x-auto mb-8">
        <table class="w-full text-sm">
            <thead class="bg-porcelain text-left text-xs uppercase tracking-widest text-gold">
                <tr>
                    <th class="p-3">From</th>
                    <th class="p-3">To</th>
                    <th class="p-3">Type</th>
                    <th class="p-3">Code</th>
                    <th class="p-3">Hits</th>
                    <th class="p-3">Active</th>
                    <th class="p-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($redirects as $redirect)
                    <tr class="border-t border-line">
                        <td class="p-3 font-mono text-xs max-w-xs truncate">{{ $redirect->from_pattern }}</td>
                        <td class="p-3 font-mono text-xs max-w-xs truncate">{{ $redirect->to_pattern }}</td>
                        <td class="p-3">{{ $redirect->is_regex ? 'Regex' : 'Exact' }}</td>
                        <td class="p-3">{{ $redirect->status_code }}</td>
                        <td class="p-3">{{ $redirect->hits }}</td>
                        <td class="p-3">{{ $redirect->is_active ? 'Yes' : 'No' }}</td>
                        <td class="p-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.redirects.edit', $redirect) }}" class="text-velvet font-bold hover:text-gold">Edit</a>
                            <form method="post" action="{{ route('admin.redirects.destroy', $redirect) }}" class="inline ml-3"
                                  onsubmit="return confirm('Delete this redirect?')">
                                @csrf @method('DELETE')
                                <button class="text-red-700 font-bold hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="p-6 text-center text-ink/60">No redirects yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mb-8">{{ $redirects->links() }}</div>

    <h2 class="eyebrow mb-4"><span class="facet"></span> Bulk upload</h2>
    <form method="post" action="{{ route('admin.redirects.bulk') }}" class="card p-6 max-w-3xl space-y-4">
        @csrf
        <div>
            <label class="form-label" for="rules">One redirect per line</label>
            <textarea class="field font-mono text-xs" id="rules" name="rules" rows="8"
                      placeholder="/old-page;/new-page&#10;/old-page-2;/new-page-2;302&#10;^/blog/(.*)$;/articles/$1;301;regex"></textarea>
            @error('rules')<p class="text-red-700 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="text-xs text-ink/60 border border-line bg-porcelain p-4">
            <p class="font-bold text-velvet mb-1">Format: <code>from;to[;code][;regex]</code></p>
            <p><code>/old-page;/new-page</code> — exact match, 301</p>
            <p><code>/sale;/deals;302</code> — exact match, custom code (301, 302, 307, 308)</p>
            <p><code>^/profile/(.*)$;/business/$1;301;regex</code> — regular expression with capture groups ($1, $2…)</p>
            <p class="mt-1">Lines starting with # are ignored. Paths are matched case-insensitively against the URL path (no domain, no query string). Query strings are carried over automatically.</p>
        </div>
        <button class="btn btn-velvet">Import redirects</button>
    </form>
@endsection
