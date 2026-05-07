@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<section class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-extrabold mb-6">Admin Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
        <a href="{{ route('admin.businesses') }}" class="bg-white border border-gray-200 rounded-2xl p-5">
            <p class="text-sm text-gray-500">Businesses</p>
            <p class="text-4xl font-extrabold mt-2">{{ $totalBusinesses }}</p>
        </a>

        <a href="{{ route('admin.businesses', ['status' => 'pending']) }}" class="bg-white border border-gray-200 rounded-2xl p-5">
            <p class="text-sm text-gray-500">Pending Businesses</p>
            <p class="text-4xl font-extrabold text-yellow-600 mt-2">{{ $pendingBusinesses }}</p>
        </a>

        <a href="{{ route('admin.reviews') }}" class="bg-white border border-gray-200 rounded-2xl p-5">
            <p class="text-sm text-gray-500">Reviews</p>
            <p class="text-4xl font-extrabold mt-2">{{ $totalReviews }}</p>
        </a>

        <a href="{{ route('admin.reviews', ['status' => 'pending']) }}" class="bg-white border border-gray-200 rounded-2xl p-5">
            <p class="text-sm text-gray-500">Pending Reviews</p>
            <p class="text-4xl font-extrabold text-yellow-600 mt-2">{{ $pendingReviews }}</p>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-5">
        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <p class="text-sm text-gray-500">Users</p>
            <p class="text-4xl font-extrabold mt-2">{{ $totalUsers }}</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <p class="text-sm text-gray-500">Providers</p>
            <p class="text-4xl font-extrabold mt-2">{{ $providers }}</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-5">
            <p class="text-sm text-gray-500">Homeowners</p>
            <p class="text-4xl font-extrabold mt-2">{{ $homeowners }}</p>
        </div>
    </div>
</section>
@endsection
