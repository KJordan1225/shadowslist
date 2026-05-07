@extends('layouts.app')

@section('title', $message->subject ?: 'Message')

@section('content')
<section class="max-w-3xl mx-auto px-4 py-8">
    <a href="{{ route('messages.index') }}" class="text-sm text-green-700 hover:underline">
        ← Back to Messages
    </a>

    <div class="bg-white border border-gray-200 rounded-2xl p-6 mt-4">
        <h1 class="text-2xl font-extrabold">
            {{ $message->subject ?: 'No Subject' }}
        </h1>

        <p class="text-sm text-gray-500 mt-2">
            From {{ $message->sender?->name ?? 'Unknown' }}
            to {{ $message->recipient?->name ?? 'Unknown' }}
            · {{ $message->created_at->format('M d, Y g:i A') }}
        </p>

        @if($message->business)
            <p class="text-sm text-gray-500 mt-1">
                Related business:
                <a href="{{ route('businesses.show', $message->business) }}" class="text-green-700 hover:underline">
                    {{ $message->business->name }}
                </a>
            </p>
        @endif

        <div class="mt-6 text-gray-700 whitespace-pre-line">
            {{ $message->body }}
        </div>
    </div>
</section>
@endsection
