<div>
    <label class="block text-sm font-bold mb-1">Business Name</label>
    <input
        name="name"
        value="{{ old('name', $business->name ?? '') }}"
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
    >{{ old('description', $business->description ?? '') }}</textarea>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
        <label class="block text-sm font-bold mb-1">Phone</label>
        <input
            name="phone"
            value="{{ old('phone', $business->phone ?? '') }}"
            class="w-full border border-gray-300 rounded-xl px-4 py-2"
        >
    </div>

    <div>
        <label class="block text-sm font-bold mb-1">Email</label>
        <input
            name="email"
            type="email"
            value="{{ old('email', $business->email ?? '') }}"
            class="w-full border border-gray-300 rounded-xl px-4 py-2"
        >
    </div>
</div>

<div>
    <label class="block text-sm font-bold mb-1">Website</label>
    <input
        name="website"
        value="{{ old('website', $business->website ?? '') }}"
        class="w-full border border-gray-300 rounded-xl px-4 py-2"
        placeholder="https://example.com"
    >
</div>

<div>
    <label class="block text-sm font-bold mb-1">Street Address</label>
    <input
        name="address"
        value="{{ old('address', $business->address ?? '') }}"
        class="w-full border border-gray-300 rounded-xl px-4 py-2"
        required
    >
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-5">
    <div>
        <label class="block text-sm font-bold mb-1">City</label>
        <input
            name="city"
            value="{{ old('city', $business->city ?? '') }}"
            class="w-full border border-gray-300 rounded-xl px-4 py-2"
            required
        >
    </div>

    <div>
        <label class="block text-sm font-bold mb-1">State</label>
        <input
            name="state"
            maxlength="2"
            value="{{ old('state', $business->state ?? '') }}"
            class="w-full border border-gray-300 rounded-xl px-4 py-2"
            required
        >
    </div>

    <div>
        <label class="block text-sm font-bold mb-1">ZIP</label>
        <input
            name="zip"
            value="{{ old('zip', $business->zip ?? '') }}"
            class="w-full border border-gray-300 rounded-xl px-4 py-2"
            required
        >
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
        <label class="block text-sm font-bold mb-1">Service Radius</label>
        <input
            name="service_radius"
            type="number"
            min="1"
            max="200"
            value="{{ old('service_radius', $business->service_radius ?? 25) }}"
            class="w-full border border-gray-300 rounded-xl px-4 py-2"
        >
    </div>

    <div>
        <label class="block text-sm font-bold mb-1">Years in Business</label>
        <input
            name="years_in_business"
            type="number"
            min="0"
            value="{{ old('years_in_business', $business->years_in_business ?? '') }}"
            class="w-full border border-gray-300 rounded-xl px-4 py-2"
        >
    </div>
</div>

<div>
    <label class="block text-sm font-bold mb-2">Categories</label>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
        @foreach($categories as $category)
            <label class="flex items-center gap-2 text-sm border border-gray-200 rounded-xl px-3 py-2">
                <input
                    type="checkbox"
                    name="categories[]"
                    value="{{ $category->id }}"
                    @checked(in_array($category->id, $selectedCategories))
                >
                {{ $category->name }}
            </label>
        @endforeach
    </div>
</div>

<div class="flex items-center gap-6">
    <label class="flex items-center gap-2">
        <input
            type="checkbox"
            name="licensed"
            value="1"
            @checked(old('licensed', $business->licensed ?? false))
        >
        Licensed
    </label>

    <label class="flex items-center gap-2">
        <input
            type="checkbox"
            name="insured"
            value="1"
            @checked(old('insured', $business->insured ?? false))
        >
        Insured
    </label>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
        <label class="block text-sm font-bold mb-1">Logo</label>
        <input type="file" name="logo" class="w-full border border-gray-300 rounded-xl px-4 py-2">
    </div>

    <div>
        <label class="block text-sm font-bold mb-1">Cover Photo</label>
        <input type="file" name="cover_photo" class="w-full border border-gray-300 rounded-xl px-4 py-2">
    </div>
</div>

<button class="bg-green-600 text-white font-bold px-6 py-3 rounded-xl hover:bg-green-700">
    {{ $buttonText }}
</button>
