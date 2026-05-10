@extends('layouts.app')

@section('title', 'Provider Dashboard')

@section('content')

<section class="max-w-7xl mx-auto px-4 py-8">
    <a href="{{ route('businesses.create') }}">
        <button class="bg-green-600 text-white font-bold px-6 py-3 rounded-xl hover:bg-green-700">
            Add Business
        </button>
    </a>
    </br></br>
    <h1 class="text-2xl font-extrabold mb-6">Provider Dashboard</h1>

    @unless($business)
        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-8 text-center">
            <p class="text-4xl mb-3">🏗️</p>
            <h2 class="text-lg font-bold text-gray-800 mb-2">
                You do not have a business listing yet.
            </h2>
            <p class="text-gray-500 mb-5">
                Create your profile to start receiving leads.
            </p>
            <a href="{{ route('businesses.create') }}"
               class="bg-green-600 text-white font-bold px-6 py-3 rounded-xl hover:bg-green-700">
                Create My Business Listing
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <div class="bg-white border border-gray-200 rounded-2xl p-5">
                <p class="text-sm text-gray-500">Average Rating</p>
                <p class="text-4xl font-extrabold text-green-600 mt-2">
                    {{ number_format($business->avg_rating, 1) }}
                </p>
            </div>

            <div class="bg-white border border-gray-200 rounded-2xl p-5">
                <p class="text-sm text-gray-500">Reviews</p>
                <p class="text-4xl font-extrabold text-blue-600 mt-2">
                    {{ $business->review_count }}
                </p>
            </div>

            <div class="bg-white border border-gray-200 rounded-2xl p-5">
                <p class="text-sm text-gray-500">Status</p>
                <p class="text-2xl font-extrabold mt-2">
                    {{ ucfirst($business->status) }}
                </p>
            </div>
        </div>

        <div class="flex flex-wrap gap-3 mb-8">
            <a href="{{ route('businesses.edit', $business) }}"
               class="bg-green-600 text-white font-bold px-5 py-2.5 rounded-xl hover:bg-green-700">
                Edit Business
            </a>

            @if($business->status === 'active')
                <a href="{{ route('businesses.show', $business) }}"
                   class="border border-gray-300 text-gray-700 font-bold px-5 py-2.5 rounded-xl hover:bg-gray-50">
                    View Public Profile
                </a>
            @endif

            <a href="{{ route('messages.index') }}"
               class="border border-gray-300 text-gray-700 font-bold px-5 py-2.5 rounded-xl hover:bg-gray-50">
                Messages
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <h2 class="text-xl font-extrabold mb-5">Recent Reviews</h2>

            <div class="space-y-5">
                @forelse($business->reviews->take(5) as $review)
                    @include('reviews._review', ['review' => $review])
                @empty
                    <p class="text-gray-500 text-sm">No reviews yet.</p>
                @endforelse
            </div>
        </div>
    @endunless
</section>
@endsection
