@extends('layouts.admin')

@section('title', 'Cities')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="font-display text-3xl font-semibold text-velvet">Cities</h1>
        <a href="{{ route('admin.cities.create') }}" class="btn btn-gold text-sm">Add city</a>
    </div>

    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-porcelain text-left text-xs uppercase tracking-widest text-gold">
                <tr>
                    <th class="p-3">Name</th>
                    <th class="p-3">State</th>
                    <th class="p-3">Coordinates</th>
                    <th class="p-3">Businesses</th>
                    <th class="p-3">Linked categories</th>
                    <th class="p-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cities as $city)
                    <tr class="border-t border-line">
                        <td class="p-3 font-bold">{{ $city->name }}</td>
                        <td class="p-3">{{ $city->state }}</td>
                        <td class="p-3 text-ink/60">{{ $city->lat }}, {{ $city->lng }}</td>
                        <td class="p-3">{{ $city->businesses_count }}</td>
                        <td class="p-3">{{ $city->categories_count }}</td>
                        <td class="p-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.cities.edit', $city) }}" class="text-velvet font-bold hover:text-gold">Edit</a>
                            <form action="{{ route('admin.cities.destroy', $city) }}" method="post" class="inline ml-3"
                                  onsubmit="return confirm('Delete this city and all of its businesses?')">
                                @csrf @method('DELETE')
                                <button class="text-red-700 font-bold hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $cities->links() }}</div>
@endsection
