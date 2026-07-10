@extends('layouts.admin')

@section('title', $city->exists ? 'Edit city' : 'Add city')

@section('content')
    <h1 class="font-display text-3xl font-semibold text-velvet mb-6">{{ $city->exists ? 'Edit city' : 'Add city' }}</h1>

    <form method="post"
          action="{{ $city->exists ? route('admin.cities.update', $city) : route('admin.cities.store') }}"
          class="card p-6 max-w-2xl space-y-5">
        @csrf
        @if($city->exists) @method('PUT') @endif

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="form-label" for="name">Name</label>
                <input class="field" id="name" name="name" value="{{ old('name', $city->name) }}" required>
                @error('name')<p class="text-red-700 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label" for="state">State / region</label>
                <input class="field" id="state" name="state" value="{{ old('state', $city->state) }}">
            </div>
            <div>
                <label class="form-label" for="lat">Latitude</label>
                <input class="field" id="lat" name="lat" value="{{ old('lat', $city->lat) }}" placeholder="40.7128">
                @error('lat')<p class="text-red-700 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label" for="lng">Longitude</label>
                <input class="field" id="lng" name="lng" value="{{ old('lng', $city->lng) }}" placeholder="-74.0060">
                @error('lng')<p class="text-red-700 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label" for="population">Population <span class="font-normal text-ink/50">(used to rank "biggest cities")</span></label>
                <input class="field" id="population" name="population" type="number" min="0" value="{{ old('population', $city->population ?? 0) }}">
                @error('population')<p class="text-red-700 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <span class="form-label">Connected categories</span>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-56 overflow-y-auto border border-line p-3">
                @foreach($categories as $category)
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                               @checked(in_array($category->id, old('categories', $linked)))>
                        {{ $category->name }}
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex gap-3">
            <button class="btn btn-velvet">{{ $city->exists ? 'Save changes' : 'Create city' }}</button>
            <a href="{{ route('admin.cities.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
@endsection
