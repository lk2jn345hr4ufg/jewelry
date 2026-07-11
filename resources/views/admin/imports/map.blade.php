@extends('layouts.admin')

@section('title', 'Map import columns')

@section('content')
    <h1 class="font-display text-3xl font-semibold text-velvet mb-2">Map columns</h1>
    <p class="text-sm text-ink/60 mb-6">
        Tell the importer what each CSV column contains. Detected delimiter:
        <span class="font-bold">{{ ['comma' => 'comma ( , )', 'semicolon' => 'semicolon ( ; )', 'tab' => 'tab'][$delimiter] }}</span>.
    </p>

    <form method="post" action="{{ route('admin.import.run') }}" class="space-y-6 max-w-4xl">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="delimiter" value="{{ $delimiter }}">

        <div class="card overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-porcelain text-left text-xs uppercase tracking-widest text-gold">
                    <tr>
                        <th class="p-3">CSV column</th>
                        <th class="p-3">Import as</th>
                        <th class="p-3">Sample values</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($headers as $i => $header)
                        <tr class="border-t border-line align-top">
                            <td class="p-3 font-bold">{{ $header !== '' ? $header : 'Column '.($i + 1) }}</td>
                            <td class="p-3">
                                <select class="field !py-1.5" name="mapping[{{ $i }}]">
                                    @foreach($targets as $value => $label)
                                        <option value="{{ $value }}" @selected($guessed[$i] === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="p-3 text-ink/60">
                                @foreach(array_slice(array_column($samples, $i), 0, 3) as $sample)
                                    <div class="truncate max-w-xs">{{ \Illuminate\Support\Str::limit((string) $sample, 60) ?: '—' }}</div>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card p-6 space-y-5">
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label" for="default_category_id">Default category <span class="text-red-700">*</span></label>
                    <select class="field" id="default_category_id" name="default_category_id" required>
                        <option value="">— select —</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-ink/50 mt-1">Used when a row has no category column or an empty value.</p>
                    @error('default_category_id')<p class="text-red-700 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label" for="default_city_id">Default city</label>
                    <select class="field" id="default_city_id" name="default_city_id">
                        <option value="">— none: skip rows without a city —</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->full_name }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-ink/50 mt-1">Fallback when the city can't be read or derived from the row.</p>
                </div>
            </div>

            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="import_hidden" value="1">
                <span><span class="font-bold">Import as hidden</span> — profiles won't appear on the public site until you activate them.</span>
            </label>

            <div class="flex gap-3">
                <button class="btn btn-velvet">Run import</button>
                <a href="{{ route('admin.import.form') }}" class="btn btn-outline">Cancel</a>
            </div>
            <p class="text-xs text-ink/50">Large files (tens of thousands of rows) can take a minute — leave the page open.</p>
        </div>
    </form>
@endsection
