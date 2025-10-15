@extends('layouts.app')

@section('title', 'Registration Complete')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">Registration Complete</h1>

    <p class="mb-4">Thank you. Your registration is recorded. Your patient ID is:</p>

    <div class="p-4 bg-gray-100 rounded mb-4">
        <strong>Patient ID:</strong> {{ $patient->id }}
    </div>

    <p class="mb-4">An administrator will create your login credentials and provide them to you. If you have questions, contact the clinic.</p>

    <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Back to login</a>
</div>
@endsection
