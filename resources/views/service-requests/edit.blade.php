@extends('layouts.app')

@section('title', 'Edit Service Request')

@section('content')
<section class="max-w-3xl mx-auto px-4 py-8">
    <div class="bg-white border border-gray-200 rounded-2xl p-6">
        <h1 class="text-2xl font-extrabold mb-6">Edit Service Request</h1>

        <form method="POST" action="{{ route('service-requests.update', $serviceRequest) }}" class="space-y-5">
            @csrf
            @method('PUT')

            @include('service-requests._form', [
                'serviceRequest' => $serviceRequest,
                'categories' => $categories,
                'buttonText' => 'Update Request'
            ])
        </form>
    </div>
</section>
@endsection
