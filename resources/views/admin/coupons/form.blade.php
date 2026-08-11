@extends('layouts.admin')

@section('title', $coupon->exists ? 'Edit coupon' : 'Add coupon')

@section('content')
    <h1 class="font-display text-3xl font-semibold text-velvet mb-6">{{ $coupon->exists ? 'Edit coupon' : 'Add coupon' }}</h1>

    <form method="post"
          action="{{ $coupon->exists ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}"
          class="card p-6 max-w-2xl space-y-5">
        @csrf
        @if($coupon->exists) @method('PUT') @endif

        @if($coupon->origin === 'api')
            <div class="border-l-4 border-amber-500 bg-amber-50 p-3 text-sm">
                Imported through the offers API as <code>{{ $coupon->slug }}</code>.
                Edits here are overwritten the next time the feed sends this offer.
            </div>
        @endif

        <div>
            <label class="form-label" for="business_id">Business</label>
            <select class="field" id="business_id" name="business_id" required>
                <option value="">— select —</option>
                @foreach($businesses as $business)
                    <option value="{{ $business->id }}" @selected(old('business_id', $coupon->business_id) == $business->id)>{{ $business->name }}</option>
                @endforeach
            </select>
            @error('business_id')<p class="text-red-700 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="form-label" for="title">Title</label>
            <input class="field" id="title" name="title" value="{{ old('title', $coupon->title) }}" required placeholder="15% off jewelry repair">
            @error('title')<p class="text-red-700 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="form-label" for="code">Code</label>
                <input class="field" id="code" name="code" value="{{ old('code', $coupon->code) }}" placeholder="SPARKLE15">
                @error('code')<p class="text-red-700 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label" for="discount">Discount label</label>
                <input class="field" id="discount" name="discount" value="{{ old('discount', $coupon->discount) }}" placeholder="15% OFF">
            </div>
            <div>
                <label class="form-label" for="starts_at">Starts</label>
                <input class="field" id="starts_at" name="starts_at" type="date"
                       value="{{ old('starts_at', $coupon->starts_at?->format('Y-m-d')) }}">
                <p class="text-xs text-[color:var(--stone)] mt-1">Leave empty to publish immediately.</p>
                @error('starts_at')<p class="text-red-700 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label" for="expires_at">Expires</label>
                <input class="field" id="expires_at" name="expires_at" type="date"
                       value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d')) }}">
                <p class="text-xs text-[color:var(--stone)] mt-1">Inclusive — the offer still runs on this day.</p>
                @error('expires_at')<p class="text-red-700 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="form-label" for="deep_link">Deal link</label>
            <input class="field" id="deep_link" name="deep_link" type="url"
                   value="{{ old('deep_link', $coupon->deep_link) }}" placeholder="https://store.example.com/sale">
            <p class="text-xs text-[color:var(--stone)] mt-1">Where the offer sends visitors. Defaults to the store website.</p>
            @error('deep_link')<p class="text-red-700 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="form-label" for="description">Description / terms</label>
            <div id="descEditor" class="bg-white border border-line" style="min-height:8rem"></div>
            <textarea name="description" id="description" class="hidden">{{ old('description', $coupon->description) }}</textarea>
            @error('description')<p class="text-red-700 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $coupon->is_active ?? true))>
            <span class="font-bold">Active</span> — shown on the deals page and business profile
        </label>

        <div class="flex gap-3">
            <button class="btn btn-velvet">{{ $coupon->exists ? 'Save changes' : 'Create coupon' }}</button>
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>

@push('head')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script>
        (function () {
            const textarea = document.getElementById('description');
            const quill = new Quill('#descEditor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ list: 'ordered' }, { list: 'bullet' }],
                        ['link'],
                        ['clean']
                    ]
                }
            });
            // Load existing content
            if (textarea.value.trim() !== '') {
                quill.clipboard.dangerouslyPasteHTML(textarea.value);
            }
            // Sync to the hidden textarea before submit
            const form = textarea.closest('form');
            form.addEventListener('submit', function () {
                const html = quill.root.innerHTML;
                textarea.value = (quill.getText().trim() === '') ? '' : html;
            });
        })();
    </script>
@endpush

@endsection