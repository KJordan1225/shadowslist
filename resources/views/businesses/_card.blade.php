<div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition">
    <a href="{{ route('businesses.show', $business) }}">
        @if($business->primaryPhoto)
            <img src="{{ $business->primaryPhoto->url }}" class="w-full h-44 object-cover" alt="{{ $business->name }}">
        @else
            <div class="w-full h-44 bg-gray-100 flex items-center justify-center text-gray-400 text-4xl">
                🏢
            </div>
        @endif
    </a>

    <div class="p-5">
        <div class="flex items-start justify-between gap-3">
            <div>
                <a href="{{ route('businesses.show', $business) }}" class="font-extrabold text-lg hover:text-green-700">
                    {{ $business->name }}
                </a>

                <p class="text-sm text-gray-500">
                    {{ $business->city }}, {{ $business->state }}
                </p>
            </div>

            @if($business->featured)
                <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full font-bold">
                    Featured
                </span>
            @endif
        </div>

        <div class="mt-3 flex items-center gap-2 text-sm">
            <span class="font-bold text-yellow-500">★ {{ number_format($business->avg_rating, 1) }}</span>
            <span class="text-gray-400">({{ $business->review_count }} reviews)</span>
        </div>

        <div class="mt-3 flex flex-wrap gap-2">
            @foreach($business->categories->take(3) as $category)
                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                    {{ $category->name }}
                </span>
            @endforeach
        </div>

        <p class="mt-4 text-sm text-gray-600 line-clamp-3">
            {{ $business->description }}
        </p>

        <div class="mt-5 flex items-center gap-2">
            @if($business->licensed)
                <span class="text-xs bg-green-50 text-green-700 px-2 py-1 rounded-full font-semibold">
                    Licensed
                </span>
            @endif

            @if($business->insured)
                <span class="text-xs bg-blue-50 text-blue-700 px-2 py-1 rounded-full font-semibold">
                    Insured
                </span>
            @endif
        </div>
    </div>
</div>
