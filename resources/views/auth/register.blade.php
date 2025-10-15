@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">Patient Registration</h1>

    <form method="POST" action="{{ url('/register') }}">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Full name</label>
            <input name="fullName" required class="mt-1 block w-full" value="{{ old('fullName') }}">
            @error('fullName') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Date of birth</label>
                <input type="date" name="dob" class="mt-1 block w-full" value="{{ old('dob') }}">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Gender</label>
                <input name="gender" class="mt-1 block w-full" value="{{ old('gender') }}">
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Contact (phone or email)</label>
            <input name="contact" class="mt-1 block w-full" value="{{ old('contact') }}">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Email (optional)</label>
            <input name="email" type="email" class="mt-1 block w-full" value="{{ old('email') }}">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Address</label>
            <input name="address" class="mt-1 block w-full" value="{{ old('address') }}">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Exposure date</label>
            <input type="date" name="exposureDate" class="mt-1 block w-full" value="{{ old('exposureDate') }}">
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('login') }}" class="text-sm text-gray-600">Already have an account?</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Register</button>
        </div>
    </form>
</div>
@endsection