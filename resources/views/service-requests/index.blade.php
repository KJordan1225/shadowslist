@extends('layouts.app')

@section('title', 'My Service Requests')

@section('content')
<section class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-extrabold">My Service Requests</h1>

        <a href="{{ route('service-requests.create') }}"
           class="bg-green-600 text-white font-bold px-5 py-2.5 rounded-xl hover:bg-green-700">
            New Request
        </a>
    </div>

    <div class="space-y-4">
        @forelse($requests as $request)
            <div class="bg-white border border-gray-200 rounded-2xl p-5">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <a href="{{ route('service-requests.show', $request) }}"
                           class="text-lg font-extrabold hover:text-green-700">
                            {{ $request->title }}
                        </a>

                        <p class="text-sm text-gray-500 mt-1">
                            {{ $request->category?->name ?? 'General' }} ·
                            {{ $request->city }}, {{ $request->state }} ·
                            {{ ucfirst($request->status) }}
                        </p>

                        <p class="text-sm text-gray-700 mt-3 line-clamp-2">
                            {{ $request->description }}
                        </p>
                    </div>

                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full font-bold">
                        {{ $request->quotes->count() }} quotes
                    </span>
                </div>
            </div>
        @empty
            <div class="bg-white border border-gray-200 rounded-2xl p-10 text-center">
                <p class="text-4xl mb-3">📝</p>
                <p class="font-bold">No service requests yet.</p>
                <p class="text-sm text-gray-500 mt-1">Create one to receive quotes from local pros.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $requests->links() }}
    </div>
</section>
@endsection
