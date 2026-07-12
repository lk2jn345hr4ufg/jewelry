@extends('layouts.admin')

@section('title', 'Businesses')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <h1 class="font-display text-3xl font-semibold text-velvet">Business profiles</h1>
        <a href="{{ route('admin.businesses.create') }}" class="btn btn-gold text-sm">Add business</a>
    </div>

    <form method="get" class="mb-4 flex gap-2 max-w-md">
        <input class="field" type="search" name="q" value="{{ request('q') }}" placeholder="Search by name…">
        <input type="hidden" name="status" value="{{ $status }}">
        <button class="btn btn-outline text-sm">Search</button>
    </form>

    <div class="flex flex-wrap gap-2 mb-4">
        @foreach(['all' => 'All', 'active' => 'Active', 'hidden' => 'Disabled'] as $key => $label)
            <a href="{{ route('admin.businesses.index', array_filter(['status' => $key, 'q' => request('q')])) }}"
               @class(['chip', '!border-gold !bg-velvet !text-white' => $status === $key])>
                {{ $label }} <span @class(['text-ink/40', '!text-goldlight' => $status === $key])>{{ $counts[$key] }}</span>
            </a>
        @endforeach
    </div>

    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-porcelain text-left text-xs uppercase tracking-widest text-gold">
                <tr>
                    <th class="p-3">Name</th>
                    <th class="p-3">Category</th>
                    <th class="p-3">City</th>
                    <th class="p-3">Status</th>
                    <th class="p-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($businesses as $business)
                    <tr class="border-t border-line">
                        <td class="p-3 font-bold">
                            {{ $business->name }}
                            <a href="{{ route('business.show', $business) }}" class="ml-1 text-ink/40 font-normal hover:text-gold" title="View public profile">↗</a>
                        </td>
                        <td class="p-3">{{ $business->category?->name }}</td>
                        <td class="p-3">{{ $business->city?->name }}</td>
                        <td class="p-3">
                            <form method="post" action="{{ route('admin.businesses.toggle', $business) }}" class="inline">
                                @csrf @method('PATCH')
                                @if($business->is_active)
                                    <button class="text-xs font-bold text-velvet border border-line bg-porcelain px-2 py-0.5 hover:border-gold" title="Click to disable">● Active</button>
                                @else
                                    <button class="text-xs font-bold text-ink/50 border border-line px-2 py-0.5 hover:border-gold" title="Click to activate">○ Disabled</button>
                                @endif
                            </form>
                        </td>
                        <td class="p-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.businesses.edit', $business) }}" class="text-velvet font-bold hover:text-gold">Edit</a>
                            <form action="{{ route('admin.businesses.destroy', $business) }}" method="post" class="inline ml-3"
                                  onsubmit="return confirm('Delete this business profile, its reviews and coupons?')">
                                @csrf @method('DELETE')
                                <button class="text-red-700 font-bold hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-6 text-center text-ink/60">No businesses found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $businesses->links() }}</div>
@endsection
