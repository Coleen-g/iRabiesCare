@extends('layouts.app')

@section('title', 'Registration Complete')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center bg-gradient-to-b from-blue-50 to-white py-10 px-4">
    <div class="w-full max-w-lg bg-white shadow-xl rounded-xl p-8 transition transform hover:-translate-y-1 hover:shadow-2xl">
        
        {{-- Success Icon Header --}}
        <div class="flex flex-col items-center mb-6 text-center">
            <div class="w-16 h-16 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 mb-3">
                <i class="fa-solid fa-circle-check text-3xl"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-gray-800">Registration Complete</h1>
            <p class="text-gray-600 mt-2 text-sm sm:text-base">
                Thank you for registering with our clinic. Your information has been successfully recorded.
            </p>
        </div>

        {{-- Patient ID Card --}}
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-5 text-center mb-6 shadow-sm">
            <p class="text-gray-700 font-medium mb-1">Your Patient ID</p>
            <div class="text-3xl font-bold text-blue-700 tracking-wider">
                {{ $patient->id }}
            </div>
        </div>

        {{-- Message Section --}}
        <div class="text-gray-600 text-center mb-6 leading-relaxed">
            <p>
                Our administrator will create your login credentials and provide them to you soon. 
                If you have any questions or need assistance, please contact the clinic reception.
            </p>
        </div>

        {{-- Back to Login Button --}}
        <div class="flex justify-center">
            <a href="{{ route('login') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 hover:shadow-lg transition-all duration-200">
                <i class="fa-solid fa-arrow-left"></i>
                Back to Login
            </a>
        </div>
    </div>
</div>
@endsection
