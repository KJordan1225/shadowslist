@extends('layouts.app')

@section('title', 'New Service Request')

@section('content')
<section class="max-w-3xl mx-auto px-4 py-8">
    <div class="bg-white border border-gray-200 rounded-2xl p-6">
        <h1 class="text-2xl font-extrabold mb-6">Request a Quote</h1>

        <form method="POST" action="{{ route('service-requests.store') }}" class="space-y-5">
            @csrf

            @include('service-requests._form', [
                'serviceRequest' => null,
                'categories' => $categories,
                'buttonText' => 'Submit Request'
            ])
        </form>
    </div>
</section>
@endsection
