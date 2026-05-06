@extends('layouts.app')

@section('title', $business->name)

@section('content')
<section class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
            <div>
                <div class="flex flex-wrap gap-2 mb-3">
                    @foreach($business->categories as $category)
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                            {{ $category->name }}
                        </span>
                    @endforeach
                </div>

                <h1 class="text-3xl font-extrabold">
                    {{ $business->name }}
                </h1>

                <p class="text-gray-500 mt-1">
                    {{ $business->address }}, {{ $business->city }}, {{ $business->state }} {{ $business->zip }}
                </p>

                <div class="mt-3 flex items-center gap-3">
                    <span class="font-bold text-yellow-500">
                        ★ {{ number_format($business->avg_rating, 1) }}
                    </span>

                    <span class="text-sm text-gray-500">
                        {{ $business->review_count }} reviews
                    </span>

                    @if($business->licensed)
                        <span class="text-xs bg-green-50 text-green-700 px-2 py-1 rounded-full font-bold">
                            Licensed
                        </span>
                    @endif

                    @if($business->insured)
                        <span class="text-xs bg-blue-50 text-blue-700 px-2 py-1 rounded-full font-bold">
                            Insured
                        </span>
                    @endif
                </div>
            </div>

            <div class="flex gap-2">
                @auth
                    <form method="POST" action="{{ route('businesses.favorite', $business) }}">
                        @csrf
                        <button class="border border-gray-300 px-5 py-2.5 rounded-xl font-bold hover:bg-gray-50">
                            {{ $isFavorited ? '♥ Saved' : '♡ Save' }}
                        </button>
                    </form>
                @endauth

                <a href="#contact"
                   class="bg-green-600 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-green-700">
                    Contact Pro
                </a>
            </div>
        </div>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 py-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
    <main class="lg:col-span-2 space-y-8">
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
            @if($business->cover_photo)
                <img src="{{ asset('storage/' . $business->cover_photo) }}" class="w-full h-72 object-cover">
            @elseif($business->primaryPhoto)
                <img src="{{ $business->primaryPhoto->url }}" class="w-full h-72 object-cover">
            @else
                <div class="w-full h-72 bg-gray-100 flex items-center justify-center text-5xl text-gray-400">
                    🏢
                </div>
            @endif
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <h2 class="text-xl font-extrabold mb-4">About</h2>
            <p class="text-gray-700 whitespace-pre-line">
                {{ $business->description }}
            </p>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <h2 class="text-xl font-extrabold mb-4">Photos</h2>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @forelse($business->photos as $photo)
                    <img src="{{ $photo->url }}" class="rounded-xl h-40 w-full object-cover">
                @empty
                    <p class="text-gray-500 text-sm">No photos uploaded yet.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <h2 class="text-xl font-extrabold mb-4">Reviews</h2>

            @auth
                <form method="POST" action="{{ route('reviews.store', $business) }}" class="mb-8 space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-bold mb-1">Rating</label>
                        <select name="rating" class="border border-gray-300 rounded-xl px-4 py-2 w-full">
                            <option value="">Select rating</option>
                            @foreach([5,4,3,2,1] as $rating)
                                <option value="{{ $rating }}">{{ $rating }} stars</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold mb-1">Title</label>
                        <input name="title" class="border border-gray-300 rounded-xl px-4 py-2 w-full">
                    </div>

                    <div>
                        <label class="block text-sm font-bold mb-1">Review</label>
                        <textarea name="body" rows="5" class="border border-gray-300 rounded-xl px-4 py-2 w-full"></textarea>
                    </div>

                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="would_hire_again" value="1" checked>
                        I would hire this business again
                    </label>

                    <button class="bg-green-600 text-white font-bold px-5 py-2.5 rounded-xl hover:bg-green-700">
                        Submit Review
                    </button>
                </form>
            @endauth

            <div class="space-y-5">
                @forelse($business->approvedReviews as $review)
                    @include('reviews._review', ['review' => $review])
                @empty
                    <p class="text-gray-500 text-sm">No reviews yet.</p>
                @endforelse
            </div>
        </div>
    </main>

    <aside class="space-y-6">
        <div id="contact" class="bg-white border border-gray-200 rounded-2xl p-6">
            <h3 class="text-lg font-extrabold mb-4">Contact</h3>

            @if($business->phone)
                <p class="text-sm mb-2">
                    <strong>Phone:</strong> {{ $business->phone }}
                </p>
            @endif

            @if($business->email)
                <p class="text-sm mb-2">
                    <strong>Email:</strong> {{ $business->email }}
                </p>
            @endif

            @if($business->website)
                <p class="text-sm mb-4">
                    <strong>Website:</strong>
                    <a href="{{ $business->website }}" target="_blank" class="text-green-700 hover:underline">
                        Visit Website
                    </a>
                </p>
            @endif

            @auth
                <form method="POST" action="{{ route('businesses.message', $business) }}" class="space-y-3">
                    @csrf

                    <input
                        name="subject"
                        placeholder="Subject"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2"
                    >

                    <textarea
                        name="body"
                        rows="4"
                        placeholder="Message"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2"
                    ></textarea>

                    <button class="w-full bg-green-600 text-white font-bold px-4 py-2 rounded-xl hover:bg-green-700">
                        Send Message
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block text-center bg-green-600 text-white font-bold px-4 py-2 rounded-xl">
                    Login to Message
                </a>
            @endauth
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <h3 class="text-lg font-extrabold mb-4">Business Hours</h3>

            <div class="space-y-2">
                @forelse($business->hours as $hour)
                    <div class="flex justify-between text-sm">
                        <span>{{ $hour->day_name }}</span>
                        <span>
                            @if($hour->is_closed)
                                Closed
                            @else
                                {{ $hour->opens_at }} - {{ $hour->closes_at }}
                            @endif
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">Hours not posted.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <h3 class="text-lg font-extrabold mb-4">Similar Pros</h3>

            <div class="space-y-4">
                @forelse($similarBusinesses as $similar)
                    <a href="{{ route('businesses.show', $similar) }}" class="block border-b border-gray-100 pb-3 last:border-0">
                        <p class="font-bold hover:text-green-700">{{ $similar->name }}</p>
                        <p class="text-sm text-gray-500">
                            ★ {{ number_format($similar->avg_rating, 1) }} · {{ $similar->city }}
                        </p>
                    </a>
                @empty
                    <p class="text-sm text-gray-500">No similar businesses found.</p>
                @endforelse
            </div>
        </div>
    </aside>
</section>
@endsection
