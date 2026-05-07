@extends('layouts.app')

@section('title', 'Manage Reviews')

@section('content')
<section class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-extrabold">Manage Reviews</h1>

        <form method="GET" class="flex gap-2">
            <select name="status" class="border border-gray-300 rounded-xl px-3 py-2">
                <option value="">All Statuses</option>
                @foreach(['pending', 'approved', 'rejected'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>

            <button class="bg-green-600 text-white font-bold px-4 py-2 rounded-xl">
                Filter
            </button>
        </form>
    </div>

    <div class="space-y-5">
        @forelse($reviews as $review)
            <div class="bg-white border border-gray-200 rounded-2xl p-5">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="font-extrabold">
                            {{ $review->title ?: 'Review' }}
                        </p>

                        <p class="text-sm text-gray-500">
                            {{ $review->business?->name }} ·
                            by {{ $review->user?->name }} ·
                            Status: {{ ucfirst($review->status) }}
                        </p>
                    </div>

                    <span class="text-yellow-500 font-bold">
                        ★ {{ $review->rating }}
                    </span>
                </div>

                <p class="mt-4 text-gray-700 whitespace-pre-line">
                    {{ $review->body }}
                </p>

                <div class="mt-5 flex gap-3">
                    <form method="POST" action="{{ route('admin.reviews.approve', $review) }}">
                        @csrf
                        @method('PATCH')
                        <button class="bg-green-600 text-white font-bold px-4 py-2 rounded-xl">
                            Approve
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.reviews.reject', $review) }}">
                        @csrf
                        @method('PATCH')
                        <button class="bg-red-600 text-white font-bold px-4 py-2 rounded-xl">
                            Reject
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white border border-gray-200 rounded-2xl p-10 text-center text-gray-500">
                No reviews found.
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $reviews->links() }}
    </div>
</section>
@endsection
