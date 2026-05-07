@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<section class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-extrabold mb-6">Messages</h1>

    <div class="bg-white border border-gray-200 rounded-2xl divide-y divide-gray-100">
        @forelse($messages as $message)
            <a href="{{ route('messages.show', $message) }}"
               class="block p-5 hover:bg-gray-50">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="font-bold">
                            {{ $message->subject ?: 'No Subject' }}
                        </p>

                        <p class="text-sm text-gray-500">
                            From {{ $message->sender?->name ?? 'Unknown' }}
                            @if($message->business)
                                · {{ $message->business->name }}
                            @endif
                        </p>

                        <p class="text-sm text-gray-600 mt-2 line-clamp-2">
                            {{ $message->body }}
                        </p>
                    </div>

                    <div class="text-right">
                        <p class="text-xs text-gray-400">
                            {{ $message->created_at->diffForHumans() }}
                        </p>

                        @unless($message->isRead())
                            <span class="inline-block mt-2 w-2.5 h-2.5 bg-red-500 rounded-full"></span>
                        @endunless
                    </div>
                </div>
            </a>
        @empty
            <div class="p-10 text-center text-gray-500">
                <p class="text-4xl mb-3">💬</p>
                <p class="font-bold">No messages yet.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $messages->links() }}
    </div>
</section>
@endsection
