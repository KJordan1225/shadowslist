<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BusinessController extends Controller
{
    use AuthorizesRequests;

    public function home()
    {
        $featuredBusinesses = Business::active()
            ->featured()
            ->with(['categories', 'primaryPhoto'])
            ->latest()
            ->limit(6)
            ->get();

        $categories = Category::active()
            ->topLevel()
            ->orderBy('name')
            ->limit(12)
            ->get();

        return view('home', compact('featuredBusinesses', 'categories'));
    }

    public function search(Request $request)
    {
        $filters = $request->only([
            'query',
            'location',
            'category',
            'min_rating',
            'licensed',
            'insured',
            'sort',
        ]);

        $categories = Category::active()
            ->topLevel()
            ->orderBy('name')
            ->get();

        $businesses = Business::active()
            ->with(['categories', 'primaryPhoto'])
            ->when($filters['query'] ?? null, function ($q, $query) {
                $q->where(function ($inner) use ($query) {
                    $inner->where('name', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%");
                });
            })
            ->when($filters['location'] ?? null, function ($q, $location) {
                $q->where(function ($inner) use ($location) {
                    $inner->where('city', 'like', "%{$location}%")
                        ->orWhere('zip', $location)
                        ->orWhere('state', $location);
                });
            })
            ->when($filters['category'] ?? null, function ($q, $categoryId) {
                $q->withCategory($categoryId);
            })
            ->when(($filters['licensed'] ?? false), fn ($q) => $q->where('licensed', true))
            ->when(($filters['insured'] ?? false), fn ($q) => $q->where('insured', true))
            ->when(($filters['min_rating'] ?? 0), fn ($q, $rating) => $q->where('avg_rating', '>=', $rating))
            ->when(($filters['sort'] ?? null) === 'name', fn ($q) => $q->orderBy('name'))
            ->when(($filters['sort'] ?? null) === 'review_count', fn ($q) => $q->orderByDesc('review_count'))
            ->when(! in_array(($filters['sort'] ?? null), ['name', 'review_count'], true), fn ($q) => $q->orderByDesc('avg_rating'))
            ->paginate(12)
            ->withQueryString();

        return view('businesses.search', compact('businesses', 'categories', 'filters'));
    }

    public function show(Business $business)
    {
        abort_if($business->status !== 'active', 404);

        $business->load([
            'user',
            'categories',
            'photos',
            'hours',
            'approvedReviews.user',
        ]);

        $similarBusinesses = Business::active()
            ->whereHas('categories', function ($q) use ($business) {
                $q->whereIn('categories.id', $business->categories->pluck('id'));
            })
            ->where('id', '!=', $business->id)
            ->with(['primaryPhoto', 'categories'])
            ->inRandomOrder()
            ->limit(4)
            ->get();

        /** @var User|null $user */
        $user = Auth::user();

        $isFavorited = $user?->hasFavorited($business) ?? false;

        return view('businesses.show', compact(
            'business',
            'similarBusinesses',
            'isFavorited'
        ));
    }

    public function create()
    {
        $this->authorize('create', Business::class);

        $categories = Category::active()
            ->orderBy('name')
            ->get();

        return view('businesses.create', compact('categories'));
    }

    public function store(Request $request)
    {       
        
        $this->authorize('create', Business::class);        

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:5'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'size:2'],
            'zip' => ['required', 'string', 'max:10'],
            'service_radius' => ['required', 'integer', 'min:1', 'max:200'],
            'years_in_business' => ['nullable', 'integer', 'min:0'],
            'licensed' => ['nullable', 'boolean'],
            'insured' => ['nullable', 'boolean'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'cover_photo' => ['nullable', 'image', 'max:4096'],
        ]);

                // dd($validated);
        /** @var User $user */
        $user = Auth::user();

        $slug = Str::slug($validated['name']) . '-' . $user->id . '-' . Str::random(5);

        $business = $user->business()->create([
            ...$validated,
            'slug' => $slug,
            'licensed' => $request->boolean('licensed'),
            'insured' => $request->boolean('insured'),
            'status' => 'pending',
        ]);

        

        if ($request->hasFile('logo')) {
            $business->update([
                'logo' => $request->file('logo')->store('business-logos', 'public'),
            ]);
        }

        if ($request->hasFile('cover_photo')) {
            $business->update([
                'cover_photo' => $request->file('cover_photo')->store('business-covers', 'public'),
            ]);
        }

        $business->categories()->sync($validated['categories']);

        return redirect()
            ->route('dashboard.provider')
            ->with('success', 'Business submitted for review.');
    }

    public function edit(Business $business)
    {
        $this->authorize('update', $business);

        $categories = Category::active()
            ->orderBy('name')
            ->get();

        $business->load(['categories', 'hours', 'photos']);

        return view('businesses.edit', compact('business', 'categories'));
    }

    public function update(Request $request, Business $business)
    {
        $this->authorize('update', $business);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:25'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'size:2'],
            'zip' => ['required', 'string', 'max:10'],
            'service_radius' => ['required', 'integer', 'min:1', 'max:200'],
            'years_in_business' => ['nullable', 'integer', 'min:0'],
            'licensed' => ['nullable', 'boolean'],
            'insured' => ['nullable', 'boolean'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'cover_photo' => ['nullable', 'image', 'max:4096'],
        ]);

        $validated['licensed'] = $request->boolean('licensed');
        $validated['insured'] = $request->boolean('insured');

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('business-logos', 'public');
        }

        if ($request->hasFile('cover_photo')) {
            $validated['cover_photo'] = $request->file('cover_photo')->store('business-covers', 'public');
        }

        $business->update($validated);
        $business->categories()->sync($validated['categories']);

        return redirect()
            ->route('businesses.show', $business)
            ->with('success', 'Business updated successfully.');
    }

    public function toggleFavorite(Business $business)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $user->favorites()->toggle($business->id);

        return back()->with('success', 'Favorites updated.');
    }

    public function providerDashboard()
    {
        /** @var User $user */
        $user = Auth::user();

        $business = $user->business()
            ->with(['categories', 'reviews.user', 'photos'])
            ->first();

        return view('dashboard.provider', compact('business'));
    }
}
