@extends('layouts.admin')

@section('title', $business->exists ? 'Edit business' : 'Add business')

@section('content')
    <h1 class="font-display text-3xl font-semibold text-velvet mb-6">
        {{ $business->exists ? 'Edit: '.$business->name : 'Add business profile' }}
    </h1>

    @if($errors->any())
        <div class="border border-red-300 bg-red-50 text-red-800 text-sm px-4 py-3 mb-6">
            Please fix the errors below.
        </div>
    @endif

    <form method="post"
          action="{{ $business->exists ? route('admin.businesses.update', $business) : route('admin.businesses.store') }}"
          class="card p-6 max-w-3xl space-y-6">
        @csrf
        @if($business->exists) @method('PUT') @endif

        <div class="grid sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label class="form-label" for="name">Business name</label>
                <input class="field" id="name" name="name" value="{{ old('name', $business->name) }}" required>
                @error('name')<p class="text-red-700 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label" for="category_id">Category</label>
                <select class="field" id="category_id" name="category_id" required>
                    <option value="">— select —</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $business->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<p class="text-red-700 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label" for="city_id">City</label>
                <select class="field" id="city_id" name="city_id" required>
                    <option value="">— select —</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" @selected(old('city_id', $business->city_id) == $city->id)>{{ $city->full_name }}</option>
                    @endforeach
                </select>
                @error('city_id')<p class="text-red-700 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="form-label" for="about">About</label>
            <textarea class="field" id="about" name="about" rows="4">{{ old('about', $business->about) }}</textarea>
            @error('about')<p class="text-red-700 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label class="form-label" for="address">Address</label>
                <input class="field" id="address" name="address" value="{{ old('address', $business->address) }}">
                @error('address')<p class="text-red-700 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label" for="phone">Phone</label>
                <input class="field" id="phone" name="phone" value="{{ old('phone', $business->phone) }}">
            </div>
            <div>
                <label class="form-label" for="phone_alt">Alternative phone</label>
                <input class="field" id="phone_alt" name="phone_alt" value="{{ old('phone_alt', $business->phone_alt) }}">
            </div>
            <div>
                <label class="form-label" for="email">Email</label>
                <input class="field" id="email" name="email" type="email" value="{{ old('email', $business->email) }}">
                @error('email')<p class="text-red-700 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label" for="website">Website</label>
                <input class="field" id="website" name="website" placeholder="https://…" value="{{ old('website', $business->website) }}">
                @error('website')<p class="text-red-700 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label" for="lat">Latitude</label>
                <input class="field" id="lat" name="lat" value="{{ old('lat', $business->lat) }}" placeholder="40.7128">
                @error('lat')<p class="text-red-700 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label" for="lng">Longitude</label>
                <input class="field" id="lng" name="lng" value="{{ old('lng', $business->lng) }}" placeholder="-74.0060">
                @error('lng')<p class="text-red-700 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <span class="form-label">Opening hours <span class="font-normal text-ink/50">(leave blank for closed days)</span></span>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                @foreach($days as $day)
                    <label class="flex items-center gap-3 text-sm">
                        <span class="w-10 font-bold">{{ $day }}</span>
                        <input class="field !py-1.5" name="hours[{{ $day }}]" placeholder="10:00 – 18:00"
                               value="{{ old('hours.'.$day, $business->hours[$day] ?? '') }}">
                    </label>
                @endforeach
            </div>
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $business->is_active ?? true))>
            <span class="font-bold">Active</span> — visible on the public site
        </label>

        <div class="flex gap-3">
            <button class="btn btn-velvet">{{ $business->exists ? 'Save changes' : 'Create profile' }}</button>
            <a href="{{ route('admin.businesses.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
@endsection
