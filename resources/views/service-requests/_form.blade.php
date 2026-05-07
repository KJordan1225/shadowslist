<div>
    <label class="block text-sm font-bold mb-1">Category</label>
    <select name="category_id" class="w-full border border-gray-300 rounded-xl px-4 py-2">
        <option value="">General / Not Sure</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}"
                @selected(old('category_id', $serviceRequest->category_id ?? '') == $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label class="block text-sm font-bold mb-1">Project Title</label>
    <input
        name="title"
        value="{{ old('title', $serviceRequest->title ?? '') }}"
        class="w-full border border-gray-300 rounded-xl px-4 py-2"
        required
    >
</div>

<div>
    <label class="block text-sm font-bold mb-1">Description</label>
    <textarea
        name="description"
        rows="6"
        class="w-full border border-gray-300 rounded-xl px-4 py-2"
        required
    >{{ old('description', $serviceRequest->description ?? '') }}</textarea>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-5">
    <div>
        <label class="block text-sm font-bold mb-1">City</label>
        <input
            name="city"
            value="{{ old('city', $serviceRequest->city ?? auth()->user()?->city) }}"
            class="w-full border border-gray-300 rounded-xl px-4 py-2"
            required
        >
    </div>

    <div>
        <label class="block text-sm font-bold mb-1">State</label>
        <input
            name="state"
            maxlength="2"
            value="{{ old('state', $serviceRequest->state ?? auth()->user()?->state) }}"
            class="w-full border border-gray-300 rounded-xl px-4 py-2"
            required
        >
    </div>

    <div>
        <label class="block text-sm font-bold mb-1">ZIP</label>
        <input
            name="zip"
            value="{{ old('zip', $serviceRequest->zip ?? auth()->user()?->zip) }}"
            class="w-full border border-gray-300 rounded-xl px-4 py-2"
            required
        >
    </div>
</div>

<div>
    <label class="block text-sm font-bold mb-1">Desired Date</label>
    <input
        type="date"
        name="desired_date"
        value="{{ old('desired_date', isset($serviceRequest?->desired_date) ? $serviceRequest->desired_date->format('Y-m-d') : '') }}"
        class="w-full border border-gray-300 rounded-xl px-4 py-2"
    >
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
        <label class="block text-sm font-bold mb-1">Budget Min</label>
        <input
            type="number"
            step="0.01"
            name="budget_min"
            value="{{ old('budget_min', $serviceRequest->budget_min ?? '') }}"
            class="w-full border border-gray-300 rounded-xl px-4 py-2"
        >
    </div>

    <div>
        <label class="block text-sm font-bold mb-1">Budget Max</label>
        <input
            type="number"
            step="0.01"
            name="budget_max"
            value="{{ old('budget_max', $serviceRequest->budget_max ?? '') }}"
            class="w-full border border-gray-300 rounded-xl px-4 py-2"
        >
    </div>
</div>

@if($serviceRequest)
    <div>
        <label class="block text-sm font-bold mb-1">Status</label>
        <select name="status" class="w-full border border-gray-300 rounded-xl px-4 py-2">
            @foreach(['open', 'closed', 'cancelled'] as $status)
                <option value="{{ $status }}" @selected(old('status', $serviceRequest->status) === $status)>
                    {{ ucfirst($status) }}
                </option>
            @endforeach
        </select>
    </div>
@endif

<button class="bg-green-600 text-white font-bold px-6 py-3 rounded-xl hover:bg-green-700">
    {{ $buttonText }}
</button>
