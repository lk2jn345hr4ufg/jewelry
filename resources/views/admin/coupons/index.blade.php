@extends('layouts.admin')

@section('title', 'Coupons & deals')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="font-display text-3xl font-semibold text-velvet">Coupons &amp; deals</h1>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-gold text-sm">Add coupon</a>
    </div>

    <div class="card overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-porcelain text-left text-xs uppercase tracking-widest text-gold">
                <tr>
                    <th class="p-3">Title</th>
                    <th class="p-3">Business</th>
                    <th class="p-3">Code</th>
                    <th class="p-3">Discount</th>
                    <th class="p-3">Expires</th>
                    <th class="p-3">Status</th>
                    <th class="p-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                    <tr class="border-t border-line">
                        <td class="p-3 font-bold">{{ $coupon->title }}</td>
                        <td class="p-3">{{ $coupon->business?->name }}</td>
                        <td class="p-3 font-mono tracking-widest">{{ $coupon->code ?: '—' }}</td>
                        <td class="p-3">{{ $coupon->discount ?: '—' }}</td>
                        <td class="p-3">{{ $coupon->expires_at?->format('M j, Y') ?? 'Never' }}</td>
                        <td class="p-3">
                            @php($expired = $coupon->expires_at && $coupon->expires_at->isPast())
                            @if($coupon->is_active && ! $expired)
                                <span class="text-xs font-bold text-velvet border border-line bg-porcelain px-2 py-0.5">Active</span>
                            @elseif($expired)
                                <span class="text-xs font-bold text-red-700 border border-red-200 px-2 py-0.5">Expired</span>
                            @else
                                <span class="text-xs font-bold text-ink/50 border border-line px-2 py-0.5">Inactive</span>
                            @endif
                        </td>
                        <td class="p-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-velvet font-bold hover:text-gold">Edit</a>
                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="post" class="inline ml-3"
                                  onsubmit="return confirm('Delete this coupon?')">
                                @csrf @method('DELETE')
                                <button class="text-red-700 font-bold hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="p-6 text-center text-ink/60">No coupons yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $coupons->links() }}</div>
@endsection
