@extends('admin.layout')

@section('title','Notifications')

@section('content')
<div class="card">
    <h2>Notifications</h2>

    @if($notifications->count())
        <ul style="list-style:none;padding:0;margin:0">
            @foreach($notifications as $n)
                @php
                    // normalize payload
                    $payload = $n->data;
                    if (is_object($payload)) {
                        $dataArr = (array) $payload;
                    } elseif (is_array($payload)) {
                        $dataArr = $payload;
                    } else {
                        $dataArr = json_decode(json_encode($payload), true) ?: [];
                    }
                    $title = $dataArr['title'] ?? 'Notification';
                    $message = $dataArr['message'] ?? ($dataArr['sender_name'] ?? '');
                @endphp
                <li style="padding:.75rem;border-bottom:1px solid #eee;background:{{ $n->read_at ? '#fff' : '#f8fafc' }};display:flex;justify-content:space-between;align-items:center">
                    @php
                        // Prefer an internal admin message link when message_id is present
                        if (!empty($dataArr['message_id'])) {
                            $link = route('admin.messages.show', $dataArr['message_id']);
                        } else {
                            $link = $dataArr['url'] ?? '#';
                        }
                    @endphp
                    <a href="{{ $link }}" style="flex:1;text-decoration:none;color:inherit;display:flex;justify-content:space-between;align-items:center">
                        <div>
                            <strong>{{ $title }}</strong>
                            <div style="color:#444">{{ $message }}</div>
                            <div style="font-size:12px;color:#6b7280;margin-top:4px">{{ $n->created_at->diffForHumans() }}</div>
                        </div>
                        <div style="margin-left:1rem">
                            @if(! $n->read_at)
                                <form method="POST" action="{{ route('admin.notifications.mark_read', $n->id) }}">
                                    @csrf
                                    <button class="btn-primary" type="submit">Mark read</button>
                                </form>
                            @endif
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>

        <div style="margin-top:.75rem">{{ $notifications->links() }}</div>
    @else
        <div class="empty-state">
            <i class="bi bi-bell-slash"></i>
            <p>No notifications</p>
        </div>
    @endif
</div>

@endsection
