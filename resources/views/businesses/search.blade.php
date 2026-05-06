@extends('layouts.app')

@section('title', 'Find a Pro')

@section('content')
<section class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-extrabold mb-5">Find a Pro</h1>

        <form method="GET" action="{{ route('businesses.search') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <input
                type="text"
                name="query"
                value="{{ $filters['query'] ?? '' }}"
                placeholder="Service or business name"
                class="md:col-span-2 border border-gray-300 rounded-xl px-4 py-3"
            >

            <input
                type="text"
                name="location"
                value="{{ $filters['location'] ?? '' }}"
                placeholder="City, State, or ZIP"
                class="border border-gray-300 rounded-xl px-4 py-3"
            >

            <select name="category" class="border border-gray-300 rounded-xl px-4 py-3">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(($filters['category'] ?? '') == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <button class="bg-green-600 text-white font-bold rounded-xl px-5 py-3 hover:bg-green-700">
                Search
            </button>
        </form>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 py-8 flex gap-8">
    <aside class="hidden lg:block w-64 shrink-0">
        <form method="GET" action="{{ route('businesses.search') }}" class="bg-white border border-gray-200 rounded-2xl p-5 space-y-5 sticky top-6">
            <input type="hidden" name="query" value="{{ $filters['query'] ?? '' }}">
            <input type="hidden" name="location" value="{{ $filters['location'] ?? '' }}">
            <input type="hidden" name="category" value="{{ $filters['category'] ?? '' }}">

            <h3 class="font-extrabold">Filters</h3>

            <div>
                <label class="text-sm font-bold text-gray-600">Sort</label>
                <select name="sort" class="mt-1 w-full border border-gray-300 rounded-xl px-3 py-2">
                    <option value="avg_rating" @selected(($filters['sort'] ?? '') === 'avg_rating')>Highest Rated</option>
                    <option value="review_count" @selected(($filters['sort'] ?? '') === 'review_count')>Most Reviewed</option>
                    <option value="name" @selected(($filters['sort'] ?? '') === 'name')>Name A-Z</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-bold text-gray-600">Minimum Rating</label>
                <select name="min_rating" class="mt-1 w-full border border-gray-300 rounded-xl px-3 py-2">
                    <option value="">Any</option>
                    <option value="4" @selected(($filters['min_rating'] ?? '') == 4)>4+ stars</option>
                    <option value="3" @selected(($filters['min_rating'] ?? '') == 3)>3+ stars</option>
                    <option value="2" @selected(($filters['min_rating'] ?? '') == 2)>2+ stars</option>
                </select>
            </div>

            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="licensed" value="1" @checked($filters['licensed'] ?? false)>
                Licensed
            </label>

            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="insured" value="1" @checked($filters['insured'] ?? false)>
                Insured
            </label>

            <button class="w-full bg-green-600 text-white font-bold rounded-xl px-4 py-2 hover:bg-green-700">
                Apply Filters
            </button>
        </form>
    </aside>

    <main class="flex-1">
        <p class="text-sm text-gray-500 mb-5">
            {{ $businesses->total() }} results found
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($businesses as $business)
                @include('businesses._card', ['business' => $business])
            @empty
                <div class="col-span-full bg-white border border-gray-200 rounded-2xl p-10 text-center text-gray-500">
                    <div class="text-5xl mb-3">🔍</div>
                    <p class="font-bold">No businesses found.</p>
                    <p class="text-sm mt-1">Try changing your search filters.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $businesses->links() }}
        </div>
    </main>
</section>
@endsection
