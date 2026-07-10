@extends('layouts.admin')

@section('title', $category->exists ? 'Edit category' : 'Add category')

@section('content')
    <h1 class="font-display text-3xl font-semibold text-velvet mb-6">{{ $category->exists ? 'Edit category' : 'Add category' }}</h1>

    <form method="post"
          action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
          class="card p-6 max-w-2xl space-y-5">
        @csrf
        @if($category->exists) @method('PUT') @endif

        <div>
            <label class="form-label" for="name">Name</label>
            <input class="field" id="name" name="name" value="{{ old('name', $category->name) }}" required>
            @error('name')<p class="text-red-700 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="form-label" for="description">Description</label>
            <textarea class="field" id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
            @error('description')<p class="text-red-700 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <span class="form-label">Connected cities</span>
            <p class="text-xs text-ink/50 mb-2">Cities where this category is offered. (Adding a business also links its city automatically.)</p>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 max-h-56 overflow-y-auto border border-line p-3">
                @foreach($cities as $city)
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="cities[]" value="{{ $city->id }}"
                               @checked(in_array($city->id, old('cities', $linked)))>
                        {{ $city->label() }}
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex gap-3">
            <button class="btn btn-velvet">{{ $category->exists ? 'Save changes' : 'Create category' }}</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
@endsection
