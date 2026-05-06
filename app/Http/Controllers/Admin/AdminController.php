<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'totalBusinesses' => Business::count(),
            'pendingBusinesses' => Business::where('status', 'pending')->count(),
            'activeBusinesses' => Business::where('status', 'active')->count(),
            'totalReviews' => Review::count(),
            'pendingReviews' => Review::where('status', 'pending')->count(),
            'totalUsers' => User::count(),
            'providers' => User::where('role', 'provider')->count(),
            'homeowners' => User::where('role', 'homeowner')->count(),
        ]);
    }

    public function businesses(Request $request)
    {
        $businesses = Business::with('user')
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.businesses', compact('businesses'));
    }

    public function approveBusiness(Business $business)
    {
        $business->update([
            'status' => 'active',
        ]);

        return back()->with('success', "Business '{$business->name}' approved.");
    }

    public function suspendBusiness(Business $business)
    {
        $business->update([
            'status' => 'suspended',
        ]);

        return back()->with('success', "Business '{$business->name}' suspended.");
    }

    public function toggleFeatured(Business $business)
    {
        $business->update([
            'featured' => ! $business->featured,
        ]);

        return back()->with('success', 'Featured status updated.');
    }

    public function reviews(Request $request)
    {
        $reviews = Review::with(['business', 'user'])
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.reviews', compact('reviews'));
    }

    public function approveReview(Review $review)
    {
        $review->update([
            'status' => 'approved',
        ]);

        $review->business?->recalculateRating();

        return back()->with('success', 'Review approved.');
    }

    public function rejectReview(Review $review)
    {
        $review->update([
            'status' => 'rejected',
        ]);

        $review->business?->recalculateRating();

        return back()->with('success', 'Review rejected.');
    }
}
