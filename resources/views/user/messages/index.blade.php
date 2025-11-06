@extends('user.layout')

@section('content')
    <div class="container">
        <h2 class="text-2xl mb-4">Inbox</h2>

        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th>When</th>
                    <th>From</th>
                    <th>Subject</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($messages as $msg)
                    <tr class="{{ $msg->read_at ? '' : 'font-bold' }}">
                        <td>{{ $msg->created_at->format('Y-m-d H:i') }}</td>
                        <td>{{ optional($msg->sender)->name ?? 'System' }}</td>
                        <td>{{ $msg->subject ?? '-' }}</td>
                        <td><a href="{{ route('user.messages.show', $msg->id) }}">Open</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">{{ $messages->links() }}</div>
    </div>
@endsection
