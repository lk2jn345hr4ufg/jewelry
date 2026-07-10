@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="font-display text-3xl font-semibold text-velvet">Categories</h1>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-gold text-sm">Add category</a>
    </div>

    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-porcelain text-left text-xs uppercase tracking-widest text-gold">
                <tr>
                    <th class="p-3">Name</th>
                    <th class="p-3">Slug</th>
                    <th class="p-3">Businesses</th>
                    <th class="p-3">Linked cities</th>
                    <th class="p-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr class="border-t border-line">
                        <td class="p-3 font-bold">{{ $category->name }}</td>
                        <td class="p-3 text-ink/60">{{ $category->slug }}</td>
                        <td class="p-3">{{ $category->businesses_count }}</td>
                        <td class="p-3">{{ $category->cities_count }}</td>
                        <td class="p-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-velvet font-bold hover:text-gold">Edit</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="post" class="inline ml-3"
                                  onsubmit="return confirm('Delete this category and all of its businesses?')">
                                @csrf @method('DELETE')
                                <button class="text-red-700 font-bold hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $categories->links() }}</div>
@endsection
