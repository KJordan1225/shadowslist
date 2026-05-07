@extends('layouts.app')

@section('title', $serviceRequest->title)

@section('content')
<section class="max-w-5xl mx-auto px-4 py-8">
    <div class="bg-white border border-gray-200 rounded-2xl p-6 mb-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold">{{ $serviceRequest->title }}</h1>

                <p class="text-sm text-gray-500 mt-1">
                    {{ $serviceRequest->category?->name ?? 'General' }} ·
                    {{ $serviceRequest->city }}, {{ $serviceRequest->state }} {{ $serviceRequest->zip }} ·
                    {{ ucfirst($serviceRequest->status) }}
                </p>
            </div>

            @can('update', $serviceRequest)
                <a href="{{ route('service-requests.edit', $serviceRequest) }}"
                   class="border border-gray-300 px-4 py-2 rounded-xl font-bold text-sm hover:bg-gray-50">
                    Edit
                </a>
            @endcan
        </div>

        <p class="mt-5 text-gray-700 whitespace-pre-line">
            {{ $serviceRequest->description }}
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6 text-sm">
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-gray-500">Desired Date</p>
                <p class="font-bold">
                    {{ $serviceRequest->desired_date?->format('M d, Y') ?? 'Flexible' }}
                </p>
            </div>

            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-gray-500">Budget</p>
                <p class="font-bold">
                    @if($serviceRequest->budget_min || $serviceRequest->budget_max)
                        ${{ number_format($serviceRequest->budget_min ?? 0, 2) }}
                        -
                        ${{ number_format($serviceRequest->budget_max ?? 0, 2) }}
                    @else
                        Not specified
                    @endif
                </p>
            </div>

            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-gray-500">Quotes</p>
                <p class="font-bold">{{ $serviceRequest->quotes->count() }}</p>
            </div>
        </div>
    </div>

    @auth
        @if(auth()->user()->role === 'provider' && auth()->user()->business && $serviceRequest->status === 'open')
            <div class="bg-white border border-gray-200 rounded-2xl p-6 mb-6">
                <h2 class="text-xl font-extrabold mb-4">Submit a Quote</h2>

                <form method="POST" action="{{ route('quotes.store', $serviceRequest) }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-bold mb-1">Quote Amount</label>
                        <input
                            type="number"
                            step="0.01"
                            name="amount"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-bold mb-1">Estimated Days</label>
                        <input
                            type="number"
                            name="estimated_days"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-bold mb-1">Message</label>
                        <textarea
                            name="message"
                            rows="5"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2"
                            required
                        ></textarea>
                    </div>

                    <button class="bg-green-600 text-white font-bold px-5 py-2.5 rounded-xl hover:bg-green-700">
                        Submit Quote
                    </button>
                </form>
            </div>
        @endif
    @endauth

    <div class="bg-white border border-gray-200 rounded-2xl p-6">
        <h2 class="text-xl font-extrabold mb-5">Quotes</h2>

        <div class="space-y-4">
            @forelse($serviceRequest->quotes as $quote)
                <div class="border border-gray-200 rounded-2xl p-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="font-extrabold">
                                {{ $quote->business?->name ?? 'Business' }}
                            </p>

                            <p class="text-sm text-gray-500">
                                Status: {{ ucfirst($quote->status) }}
                            </p>
                        </div>

                        @if($quote->amount)
                            <p class="font-extrabold text-green-700">
                                ${{ number_format($quote->amount, 2) }}
                            </p>
                        @endif
                    </div>

                    <p class="mt-4 text-gray-700 whitespace-pre-line">
                        {{ $quote->message }}
                    </p>

                    @if($quote->estimated_days)
                        <p class="text-sm text-gray-500 mt-2">
                            Estimated time: {{ $quote->estimated_days }} days
                        </p>
                    @endif

                    @can('accept', $quote)
                        @if($quote->status === 'sent' && $serviceRequest->status === 'open')
                            <form method="POST" action="{{ route('quotes.accept', $quote) }}" class="mt-4">
                                @csrf
                                <button class="bg-green-600 text-white font-bold px-4 py-2 rounded-xl hover:bg-green-700">
                                    Accept Quote
                                </button>
                            </form>
                        @endif
                    @endcan
                </div>
            @empty
                <p class="text-gray-500 text-sm">No quotes yet.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
