@extends('user.layout')

@section('title','Notifications')

@section('content')
<div class="card">
    <h2>Notifications</h2>

    @if($notifications->count())
        <ul style="list-style:none;padding:0;margin:0">
            @foreach($notifications as $n)
                <li style="padding:.75rem;border-bottom:1px solid #eee;background:{{ $n->read_at ? '#fff' : '#f8fafc' }};display:flex;justify-content:space-between;align-items:center">
                    <a href="{{ $n->data['url'] ?? '#' }}" style="flex:1;text-decoration:none;color:inherit;display:flex;justify-content:space-between;align-items:center">
                        <div>
                            <strong>{{ $n->data['title'] ?? 'Notification' }}</strong>
                            <div style="color:#444">{{ $n->data['message'] ?? '' }}</div>
                            <div style="font-size:12px;color:#6b7280;margin-top:4px">{{ $n->created_at->diffForHumans() }}</div>
                        </div>
                        <div style="margin-left:1rem">
                            @if(! $n->read_at)
                                <form method="POST" action="{{ route('user.notifications.read', $n->id) }}">
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
