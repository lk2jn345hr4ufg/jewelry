@extends('layouts.admin')

@section('title', 'Reviews')

@section('content')
    <h1 class="font-display text-3xl font-semibold text-velvet mb-6">Review moderation</h1>

    <div class="flex flex-wrap gap-2 mb-6">
        @foreach(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'all' => 'All'] as $key => $label)
            <a href="{{ route('admin.reviews.index', $key === 'all' ? [] : ['status' => $key]) }}"
               @class(['chip', '!border-gold !bg-velvet !text-white' => $status === $key || ($key === 'all' && ! in_array($status, ['pending', 'approved', 'rejected']))])>
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="space-y-4">
        @forelse($reviews as $review)
            <article class="card p-5">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <span class="font-bold">{{ $review->author_name }}</span>
                        @if($review->author_email)
                            <span class="text-ink/50 text-sm">&lt;{{ $review->author_email }}&gt;</span>
                        @endif
                        <span class="text-gold ml-2">{{ str_repeat('★', $review->rating) }}<span class="text-line">{{ str_repeat('★', 5 - $review->rating) }}</span></span>
                    </div>
                    <div class="text-xs text-ink/50">
                        {{ $review->created_at->format('M j, Y H:i') }} ·
                        <span @class([
                            'font-bold uppercase tracking-wider',
                            'text-amber-700' => $review->status === 'pending',
                            'text-velvet' => $review->status === 'approved',
                            'text-red-700' => $review->status === 'rejected',
                        ])>{{ $review->status }}</span>
                    </div>
                </div>

                <p class="text-sm text-ink/75 mt-2">{{ $review->body }}</p>

                <p class="text-sm mt-2">
                    on <a href="{{ route('business.show', $review->business) }}" class="font-bold text-velvet hover:text-gold">{{ $review->business->name }}</a>
                </p>

                <div class="flex flex-wrap gap-2 mt-4">
                    @foreach(['approved' => 'Approve', 'rejected' => 'Reject', 'pending' => 'Mark pending'] as $newStatus => $label)
                        @if($review->status !== $newStatus)
                            <form method="post" action="{{ route('admin.reviews.status', $review) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="{{ $newStatus }}">
                                <button @class([
                                    'btn !py-1.5 !px-3 text-sm',
                                    'btn-velvet' => $newStatus === 'approved',
                                    'btn-outline' => $newStatus !== 'approved',
                                ])>{{ $label }}</button>
                            </form>
                        @endif
                    @endforeach
                    <form method="post" action="{{ route('admin.reviews.destroy', $review) }}"
                          onsubmit="return confirm('Permanently delete this review?')">
                        @csrf @method('DELETE')
                        <button class="btn !py-1.5 !px-3 text-sm text-red-700 border border-red-200 hover:bg-red-50">Delete</button>
                    </form>
                </div>
            </article>
        @empty
            <div class="card p-8 text-center text-ink/60">No reviews with this status.</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $reviews->links() }}</div>
@endsection
