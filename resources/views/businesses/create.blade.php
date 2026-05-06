@extends('layouts.app')

@section('title', 'Create Business')

@section('content')
<section class="max-w-4xl mx-auto px-4 py-8">
    <div class="bg-white border border-gray-200 rounded-2xl p-6">
        <h1 class="text-2xl font-extrabold mb-6">Create Business Listing</h1>

        <form method="POST" action="{{ route('businesses.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            @include('businesses._form', [
                'business' => null,
                'categories' => $categories,
                'selectedCategories' => old('categories', []),
                'buttonText' => 'Submit Business'
            ])
        </form>
    </div>
</section>
@endsection
