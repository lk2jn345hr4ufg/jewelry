@extends('layouts.admin')

@section('title', 'Import businesses')

@section('content')
    <h1 class="font-display text-3xl font-semibold text-velvet mb-6">Import businesses from CSV</h1>

    @if($report = session('import_report'))
        <div class="border border-gold bg-goldlight/25 text-velvet px-4 py-3 mb-6 text-sm">
            <p class="font-bold">Import finished:</p>
            <p>{{ $report['created'] }} created · {{ $report['updated'] }} updated · {{ $report['skipped'] }} skipped.</p>
            @if(!empty($report['errors']))
                <details class="mt-2">
                    <summary class="cursor-pointer font-bold">First {{ count($report['errors']) }} row errors</summary>
                    <ul class="mt-1 list-disc pl-5">
                        @foreach($report['errors'] as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </details>
            @endif
        </div>
    @endif

    @if($errors->any())
        <div class="border border-red-300 bg-red-50 text-red-800 text-sm px-4 py-3 mb-6">{{ $errors->first() }}</div>
    @endif

    <form method="post" action="{{ route('admin.import.preview') }}" enctype="multipart/form-data"
          class="card p-6 max-w-2xl space-y-5">
        @csrf

        <div>
            <label class="form-label" for="file">CSV file</label>
            <input class="field" type="file" id="file" name="file" accept=".csv,.txt" required>
            <p class="text-xs text-ink/50 mt-2">
                Comma-, semicolon- or tab-separated, with a header row. Up to 50&nbsp;MB.
                On the next step you'll match each column to a business field, so any column layout works.
            </p>
        </div>

        <div class="text-sm text-ink/70 border border-line bg-porcelain p-4">
            <p class="font-bold text-velvet mb-1">What the importer can do</p>
            <ul class="list-disc pl-5 space-y-1">
                <li>Create new business profiles and update existing ones (matched by name).</li>
                <li>Create cities and categories on the fly when a row names one that doesn't exist yet.</li>
                <li>Derive the business name — and the city, when recognizable — from a profile URL column
                    (e.g. <code>…/profile/the-diamond-ring-company-san-jose/</code>).</li>
                <li>Import per-day opening hours from split open/close columns (e.g. <code>opening_monday_open</code>).</li>
                <li>Clean the data: decodes HTML entities, strips a trailing "— City" from names, removes utm_* tracking from websites.</li>
                <li>Optionally import everything as hidden, so you can review before publishing.</li>
            </ul>
        </div>

        <button class="btn btn-velvet">Upload &amp; map columns</button>
    </form>
@endsection
