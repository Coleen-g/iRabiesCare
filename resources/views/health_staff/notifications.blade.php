@extends('health_staff.layout')

@section('title','Notifications')

@section('content')
<div class="max-w-3xl mx-auto mt-6">
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <div>
                <h1 class="text-lg font-semibold text-gray-900">Notifications</h1>
                <p class="text-sm text-gray-500">Recent alerts and messages for your account.</p>
            </div>
            <div class="text-sm text-gray-500">{{ $notifications->total() ?? 0 }} items</div>
        </div>

        <div class="p-4">
            @if($notifications->count())
                <ul class="space-y-3">
                    @foreach($notifications as $n)
                        @php
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
                            // prefer internal health_staff route when message_id present
                            $link = !empty($dataArr['message_id']) ? route('health_staff.messages.show', $dataArr['message_id']) : ($dataArr['url'] ?? '#');
                        @endphp

                        <li class="flex items-start gap-4 p-3 rounded-lg {{ $n->read_at ? 'bg-white' : 'bg-blue-50' }} shadow-sm">
                            <div class="flex-shrink-0 mt-1">
                                {{-- bell/envelope icon inline SVG --}}
                                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            </div>

                            <div class="flex-1">
                                <a href="{{ $link }}" class="block text-sm font-medium text-gray-900 hover:underline">{{ $title }}</a>
                                <p class="text-sm text-gray-600 mt-1">{{ Illuminate\Support\Str::limit($message, 180) }}</p>
                                <div class="mt-2 text-xs text-gray-400">{{ $n->created_at->diffForHumans() }}</div>
                            </div>

                            <div class="flex flex-col items-end gap-2">
                                @if(! $n->read_at)
                                    <form method="POST" action="{{ route('health_staff.notifications.read', $n->id) }}">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            Mark read
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-500">Read</span>
                                @endif
                                @if(!empty($dataArr['message_id']))
                                    <a href="{{ route('health_staff.messages.show', $dataArr['message_id']) }}" class="text-sm text-blue-600 hover:underline">Open message</a>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-4">{{ $notifications->links() }}</div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <p class="mt-4 text-sm text-gray-500">No notifications</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
