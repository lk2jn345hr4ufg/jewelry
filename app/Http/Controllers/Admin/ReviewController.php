<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');

        $reviews = Review::with('business')
            ->when(in_array($status, ['pending', 'approved', 'rejected']), fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.reviews.index', compact('reviews', 'status'));
    }

    public function updateStatus(Request $request, Review $review)
    {
        $request->validate(['status' => ['required', 'in:pending,approved,rejected']]);
        $review->update(['status' => $request->status]);

        return back()->with('ok', 'Review ' . $request->status . '.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('ok', 'Review deleted.');
    }
}
