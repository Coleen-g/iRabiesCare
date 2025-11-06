@extends('admin.layout')

@section('content')
    <div class="container">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl">Messages</h2>
            <a href="{{ route('admin.messages.create') }}" class="btn btn-primary">New Message</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th>When</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Subject</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($messages as $msg)
                    <tr>
                        <td>{{ $msg->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ optional($msg->sender)->name ?? 'System' }}</td>
                        <td>{{ optional($msg->recipient)->name ?? 'Unknown' }}</td>
                        <td>{{ $msg->subject ?? '-' }}</td>
                        <td><a href="{{ route('admin.messages.show', $msg->id) }}">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">{{ $messages->links() }}</div>
    </div>
@endsection
