@extends('user.layout')

@section('content')
    <div class="container">
        <div class="mb-4">
            <a href="{{ route('user.messages.index') }}">&larr; Back to inbox</a>
        </div>

        <h2 class="text-xl">{{ $message->subject ?? 'No subject' }}</h2>
        <div class="text-sm text-gray-600">From: {{ optional($message->sender)->name ?? 'System' }} — Sent: {{ $message->created_at->format('Y-m-d H:i') }}</div>

        <div class="mt-4 p-4 border bg-white">
            {!! nl2br(e($message->body)) !!}
        </div>
    </div>
@endsection
