@extends('layouts.admin')

@section('title', $redirect->exists ? 'Edit redirect' : 'Add redirect')

@section('content')
    <h1 class="font-display text-3xl font-semibold text-velvet mb-6">{{ $redirect->exists ? 'Edit redirect' : 'Add redirect' }}</h1>

    <form method="post"
          action="{{ $redirect->exists ? route('admin.redirects.update', $redirect) : route('admin.redirects.store') }}"
          class="card p-6 max-w-2xl space-y-5">
        @csrf
        @if($redirect->exists) @method('PUT') @endif

        <div>
            <label class="form-label" for="from_pattern">From (URL path or regex)</label>
            <input class="field font-mono" id="from_pattern" name="from_pattern" value="{{ old('from_pattern', $redirect->from_pattern) }}" placeholder="/old-page or ^/blog/(.*)$" required>
            @error('from_pattern')<p class="text-red-700 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="form-label" for="to_pattern">To (path or full URL; $1, $2… for regex captures)</label>
            <input class="field font-mono" id="to_pattern" name="to_pattern" value="{{ old('to_pattern', $redirect->to_pattern) }}" placeholder="/new-page or /articles/$1" required>
            @error('to_pattern')<p class="text-red-700 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="form-label" for="status_code">HTTP status</label>
                <select class="field" id="status_code" name="status_code">
                    @foreach([301 => '301 — permanent', 302 => '302 — temporary', 307 => '307', 308 => '308'] as $code => $label)
                        <option value="{{ $code }}" @selected(old('status_code', $redirect->status_code) == $code)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col justify-end gap-2 pb-1">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_regex" value="1" @checked(old('is_regex', $redirect->is_regex))>
                    <span class="font-bold">Regular expression</span>
                </label>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $redirect->is_active ?? true))>
                    <span class="font-bold">Active</span>
                </label>
            </div>
        </div>

        <div class="flex gap-3">
            <button class="btn btn-velvet">{{ $redirect->exists ? 'Save changes' : 'Create redirect' }}</button>
            <a href="{{ route('admin.redirects.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
@endsection
