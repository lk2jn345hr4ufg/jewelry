@extends('layouts.admin')

@section('title', $coupon->exists ? 'Edit coupon' : 'Add coupon')

@section('content')
    <h1 class="font-display text-3xl font-semibold text-velvet mb-6">{{ $coupon->exists ? 'Edit coupon' : 'Add coupon' }}</h1>

    <form method="post"
          action="{{ $coupon->exists ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}"
          class="card p-6 max-w-2xl space-y-5">
        @csrf
        @if($coupon->exists) @method('PUT') @endif

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

        <div class="grid sm:grid-cols-3 gap-4">
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
                <label class="form-label" for="expires_at">Expires</label>
                <input class="field" id="expires_at" name="expires_at" type="date"
                       value="{{ old('expires_at', $coupon->expires_at?->format('Y-m-d')) }}">
                @error('expires_at')<p class="text-red-700 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="form-label" for="description">Description / terms</label>
            <textarea class="field" id="description" name="description" rows="3">{{ old('description', $coupon->description) }}</textarea>
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
@endsection
