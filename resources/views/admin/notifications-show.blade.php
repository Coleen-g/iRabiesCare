@extends('admin.layout')

@section('title','Notification')

@section('content')
<div class="card">
    <h2>Notification Details</h2>
    <div style="margin-top:.8rem">
        <p><strong>When:</strong> {{ optional($notification->created_at)->toDayDateTimeString() }}</p>
        <p><strong>Type:</strong> {{ class_basename($notification->type) }}</p>

        @php
            $payload = $notification->data;
            if (is_object($payload)) {
                $dataArr = (array) $payload;
            } elseif (is_array($payload)) {
                $dataArr = $payload;
            } else {
                $dataArr = json_decode(json_encode($payload), true) ?: [];
            }
        @endphp

        <p><strong>Title:</strong> {{ $dataArr['title'] ?? '-' }}</p>
        <p><strong>Message:</strong></p>
        <div style="background:#f8fafc;border-radius:8px;padding:1rem">{!! nl2br(e($dataArr['message'] ?? '')) !!}</div>

        @if(!empty($dataArr['sender_name']) || !empty($dataArr['sender_id']))
            <p style="margin-top:.5rem"><strong>From:</strong> {{ $dataArr['sender_name'] ?? ('User #' . ($dataArr['sender_id'] ?? '-')) }}</p>
        @endif

        @php
            // If a message_id exists prefer the admin messages show route so admins land in the admin UI
            if (!empty($dataArr['message_id'])) {
                $msgLink = route('admin.messages.show', $dataArr['message_id']);
            } else {
                $msgLink = $dataArr['url'] ?? null;
            }
        @endphp
        @if(!empty($msgLink))
            <p><strong>Link:</strong> <a href="{{ $msgLink }}">Open message</a></p>
        @endif

        <p><strong>Raw data:</strong></p>
        <pre style="background:#f6f7fb;border-radius:8px;padding:1rem">{{ json_encode($notification->data, JSON_PRETTY_PRINT) }}</pre>
    </div>
    <div style="margin-top:1rem">
        <a href="{{ route('admin.notifications.index') }}" class="btn ghost">Back</a>
    </div>
</div>

@endsection
