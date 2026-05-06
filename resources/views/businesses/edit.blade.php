@extends('layouts.app')

@section('title', 'Edit Business')

@section('content')
<section class="max-w-4xl mx-auto px-4 py-8">
    <div class="bg-white border border-gray-200 rounded-2xl p-6">
        <h1 class="text-2xl font-extrabold mb-6">Edit Business Listing</h1>

        <form method="POST" action="{{ route('businesses.update', $business) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            @include('businesses._form', [
                'business' => $business,
                'categories' => $categories,
                'selectedCategories' => old('categories', $business->categories->pluck('id')->toArray()),
                'buttonText' => 'Update Business'
            ])
        </form>
    </div>
</section>
@endsection
