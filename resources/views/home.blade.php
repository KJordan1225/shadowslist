@extends('layouts.app')

@section('title', 'Find Trusted Local Pros')

@section('content')
<section class="bg-green-700 text-white">
    <div class="max-w-7xl mx-auto px-4 py-16">
        <h1 class="text-4xl font-extrabold mb-4">
            Find trusted local professionals
        </h1>

        <p class="text-green-100 text-lg mb-8 max-w-2xl">
            Compare businesses, read reviews, request quotes, and hire the right pro for your next project.
        </p>

        <form action="{{ route('businesses.search') }}" method="GET" class="bg-white rounded-2xl p-4 flex flex-col md:flex-row gap-3 shadow">
            <input
                type="text"
                name="query"
                placeholder="What service do you need?"
                class="flex-1 border border-gray-300 rounded-xl px-4 py-3 text-gray-900"
            >

            <input
                type="text"
                name="location"
                placeholder="City, State, or ZIP"
                class="md:w-64 border border-gray-300 rounded-xl px-4 py-3 text-gray-900"
            >

            <button class="bg-green-600 text-white font-bold px-6 py-3 rounded-xl hover:bg-green-700">
                Search
            </button>
        </form>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 py-12">
    <h2 class="text-2xl font-extrabold mb-6">Popular Categories</h2>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @foreach($categories as $category)
            <a href="{{ route('businesses.search', ['category' => $category->id]) }}"
               class="bg-white border border-gray-200 rounded-2xl p-5 text-center hover:border-green-500 hover:shadow transition">
                <div class="text-3xl mb-2">{{ $category->icon ?? '🛠️' }}</div>
                <div class="font-bold text-sm">{{ $category->name }}</div>
            </a>
        @endforeach
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 py-12">
    <h2 class="text-2xl font-extrabold mb-6">Featured Pros</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($featuredBusinesses as $business)
            @include('businesses._card', ['business' => $business])
        @empty
            <p class="text-gray-500">No featured businesses yet.</p>
        @endforelse
    </div>
</section>
@endsection
