@extends('admin.layout')

@section('title','Message')

@section('content')
    <div class="container">
        <a href="{{ route('admin.messages.index') }}" class="btn-ghost" style="margin-bottom:12px; display:inline-block">&larr; Back to messages</a>

        <div class="card">
            <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:1rem;">
                <div style="flex:1;">
                    <h1 style="margin:0 0 6px 0; font-size:1.25rem;">{{ $message->subject ?? 'No subject' }}</h1>

                    <div style="color:#6b7280; font-size:13px;">
                        From: <strong>{{ optional($message->sender)->name ?? 'System' }}</strong>
                        &nbsp;&middot;&nbsp;
                        To: <strong>{{ optional($message->recipient)->name ?? 'Unknown' }}</strong>
                        @if(optional($message->recipient)->role)
                            &nbsp;(<span style="text-transform:capitalize">{{ optional($message->recipient)->role }}</span>)
                        @endif
                        &nbsp;&middot;&nbsp;
                        Sent: {{ $message->created_at->format('M j, Y \a\t g:ia') }}
                    </div>
                </div>

                <div style="display:flex; gap:.5rem; align-items:center;">
                    <a href="{{ route('admin.messages.create') }}?recipient_id={{ $message->recipient_id }}" class="action-edit" title="Compose to this recipient">Compose</a>
                    {{-- Deletion is not implemented for admin messages; remove delete action to avoid RouteNotFoundException --}}
                </div>
            </div>

            <div style="margin-top:1rem; padding:1rem; background:#fff; border-radius:8px; border:1px solid #f1f1f1; color:#111; line-height:1.6;">
                {{-- Message body --}}
                {!! nl2br(e($message->body)) !!}
            </div>
        </div>
    </div>
@endsection
