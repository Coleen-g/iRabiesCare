@extends('health_staff.layout')

@section('content')
    <div class="max-w-6xl mx-auto mt-6">
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-semibold text-gray-900">Messages</h1>
                    <p class="text-sm text-gray-500">All messages sent and received for your account.</p>
                </div>

                <div class="flex items-center gap-3">
                    <form method="GET" action="" class="hidden sm:flex items-center">
                        <input name="q" type="search" placeholder="Search subject or sender" class="border rounded px-3 py-2 text-sm w-64" value="{{ request('q') }}">
                    </form>
                    <a href="{{ route('health_staff.messages.create') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        New Message
                    </a>
                </div>
            </div>

            <div class="p-4">
                @if(session('success'))
                    <div class="mb-4 rounded-lg bg-green-50 border border-green-100 p-3 text-green-800">{{ session('success') }}</div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                            <tr>
                                <th class="px-4 py-3">When</th>
                                <th class="px-4 py-3">From</th>
                                <th class="px-4 py-3">To</th>
                                <th class="px-4 py-3">Subject</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach($messages as $msg)
                                <tr class="hover:bg-gray-50 {{ $msg->read_at ? '' : 'font-medium' }}">
                                    <td class="px-4 py-3 text-gray-600 w-40">{{ $msg->created_at->format('Y-m-d H:i') }}</td>
                                    <td class="px-4 py-3">{{ optional($msg->sender)->name ?? 'System' }}</td>
                                    <td class="px-4 py-3">{{ optional($msg->recipient)->name ?? 'Unknown' }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-800">{{ $msg->subject ?? '-' }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('health_staff.messages.show', $msg->id) }}" class="text-blue-600 hover:underline">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ $messages->links() }}</div>
            </div>
        </div>
    </div>
@endsection
