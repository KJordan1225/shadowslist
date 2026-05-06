<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Business $business)
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'quality_rating' => ['nullable', 'integer', 'between:1,5'],
            'responsiveness_rating' => ['nullable', 'integer', 'between:1,5'],
            'punctuality_rating' => ['nullable', 'integer', 'between:1,5'],
            'professionalism_rating' => ['nullable', 'integer', 'between:1,5'],
            'title' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string', 'min:20'],
            'service_used' => ['nullable', 'string', 'max:255'],
            'service_date' => ['nullable', 'date', 'before_or_equal:today'],
            'price_paid' => ['nullable', 'numeric', 'min:0'],
            'would_hire_again' => ['nullable', 'boolean'],
        ]);

        /** @var User $user */
        $user = Auth::user();

        $alreadyReviewed = Review::where('business_id', $business->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyReviewed) {
            return back()
                ->withErrors(['review' => 'You have already reviewed this business.'])
                ->withInput();
        }

        $business->reviews()->create([
            ...$validated,
            'user_id' => $user->id,
            'would_hire_again' => $request->boolean('would_hire_again', true),
            'status' => 'pending',
        ]);

        return back()->with('success', 'Review submitted and awaiting approval.');
    }

    public function respond(Request $request, Review $review)
    {
        $this->authorize('respond', $review);

        $validated = $request->validate([
            'response' => ['required', 'string', 'min:5', 'max:2000'],
        ]);

        $review->update([
            'owner_response' => $validated['response'],
            'owner_responded_at' => now(),
        ]);

        return back()->with('success', 'Response posted.');
    }
}
