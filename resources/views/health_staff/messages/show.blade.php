@extends('health_staff.layout')

@section('title','Message')

@section('content')
<div class="card">
    <h2>{{ $message->subject ?? 'Message' }}</h2>
    <div style="margin-top:.5rem;color:#6b7280">From: {{ optional($message->sender)->name ?? 'System' }} — {{ optional($message->created_at)->toDayDateTimeString() }}</div>

    <div style="margin-top:1rem;padding:1rem;background:#f8fafc;border-radius:8px">{!! nl2br(e($message->body)) !!}</div>

    <div style="margin-top:1rem">
        <a href="{{ route('health_staff.messages.index') }}" class="btn ghost">Back</a>
    </div>
</div>

@endsection
@extends('health_staff.layout')

@section('content')
    <div class="container">
        <div class="mb-4">
            <a href="{{ route('health_staff.messages.index') }}">&larr; Back to inbox</a>
        </div>

        <h2 class="text-xl">{{ $message->subject ?? 'No subject' }}</h2>
        <div class="text-sm text-gray-600">From: {{ optional($message->sender)->name ?? 'System' }} — Sent: {{ $message->created_at->format('Y-m-d H:i') }}</div>

        <div class="mt-4 p-4 border bg-white">
            {!! nl2br(e($message->body)) !!}
        </div>
    </div>
@endsection
