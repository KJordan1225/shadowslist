@extends('layouts.app')

@section('title', 'Manage Businesses')

@section('content')
<section class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-extrabold">Manage Businesses</h1>

        <form method="GET" class="flex gap-2">
            <select name="status" class="border border-gray-300 rounded-xl px-3 py-2">
                <option value="">All Statuses</option>
                @foreach(['pending', 'active', 'suspended', 'rejected'] as $status)
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

    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-left">
                <tr>
                    <th class="px-4 py-3">Business</th>
                    <th class="px-4 py-3">Owner</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Featured</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($businesses as $business)
                    <tr>
                        <td class="px-4 py-3">
                            <p class="font-bold">{{ $business->name }}</p>
                            <p class="text-xs text-gray-500">
                                {{ $business->city }}, {{ $business->state }}
                            </p>
                        </td>

                        <td class="px-4 py-3">
                            {{ $business->user?->name }}
                        </td>

                        <td class="px-4 py-3">
                            {{ ucfirst($business->status) }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $business->featured ? 'Yes' : 'No' }}
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <form method="POST" action="{{ route('admin.businesses.approve', $business) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="text-green-700 font-bold">
                                        Approve
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.businesses.suspend', $business) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="text-red-700 font-bold">
                                        Suspend
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.businesses.featured', $business) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="text-blue-700 font-bold">
                                        Toggle Featured
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-gray-500">
                            No businesses found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8">
        {{ $businesses->links() }}
    </div>
</section>
@endsection
