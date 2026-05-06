<div class="border-b border-gray-100 pb-5 last:border-0">
    <div class="flex items-start justify-between gap-3">
        <div>
            <p class="font-bold">
                {{ $review->title ?: 'Review' }}
            </p>

            <p class="text-sm text-gray-500">
                by {{ $review->user?->name ?? 'Anonymous' }} · {{ $review->created_at->diffForHumans() }}
            </p>
        </div>

        <span class="text-yellow-500 font-bold">
            ★ {{ $review->rating }}
        </span>
    </div>

    <p class="mt-3 text-gray-700 whitespace-pre-line">
        {{ $review->body }}
    </p>

    @if($review->service_used)
        <p class="mt-2 text-sm text-gray-500">
            Service used: {{ $review->service_used }}
        </p>
    @endif

    @if($review->would_hire_again)
        <p class="mt-2 text-sm text-green-700 font-semibold">
            Would hire again
        </p>
    @endif

    @if($review->owner_response)
        <div class="mt-4 bg-gray-50 border border-gray-200 rounded-xl p-4">
            <p class="text-sm font-bold mb-1">Owner Response</p>
            <p class="text-sm text-gray-700 whitespace-pre-line">
                {{ $review->owner_response }}
            </p>
        </div>
    @endif
</div>
