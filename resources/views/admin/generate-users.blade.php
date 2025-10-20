@extends('admin.layout')

@section('content')
<div class="p-8 bg-gradient-to-b from-gray-50 to-white min-h-screen">
    {{-- Header --}}
    <div class="flex items-center gap-3 mb-8 border-b pb-4">
        <div class="p-3 bg-blue-100 text-blue-600 rounded-lg">
            <i class="fa-solid fa-user-gear text-2xl"></i>
        </div>
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Generate User Accounts</h1>
            <p class="text-gray-500">Automatically create login accounts for patients without existing users.</p>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white shadow-md rounded-xl p-6 mb-8 border border-gray-100 transition hover:shadow-lg">
        <form method="POST" action="{{ route('admin.generate-users') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Select Role</label>
                <select name="role" class="border-gray-300 rounded-lg w-full p-3 focus:ring-2 focus:ring-blue-500">
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <button type="submit"
                class="flex items-center gap-2 px-5 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition duration-200">
                <i class="fa-solid fa-user-plus"></i>
                Generate for All Patients Without Accounts
            </button>
        </form>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mt-5 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-md flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-green-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- CSV Download --}}
        @if(file_exists(storage_path('patient_new_users.csv')))
            <div class="mt-5">
                <a href="{{ route('admin.generate-users.download') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition">
                    <i class="fa-solid fa-file-arrow-down"></i>
                    Download CSV of Generated Credentials
                </a>
            </div>
        @endif
    </div>

    {{-- Patients Without Accounts --}}
    @if (!empty($patients) && $patients->count())
        <div class="bg-white shadow-md rounded-xl p-6 mb-8 border border-gray-100">
            <h2 class="text-xl font-semibold mb-4 flex items-center gap-2">
                <i class="fa-solid fa-user-clock text-blue-600"></i>
                Patients Without Accounts ({{ $patients->count() }})
            </h2>
            <ul class="divide-y divide-gray-200 text-gray-700">
                @foreach($patients as $p)
                    <li class="py-2 flex justify-between items-center">
                        <span>#{{ $p->id }} — {{ $p->name }}</span>
                        <span class="text-gray-500 text-sm">
                            {{ $p->email ?? 'No email provided' }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Generated Accounts Table --}}
    @if (!empty($results) && count($results))
        <div class="bg-white shadow-md rounded-xl p-6 border border-gray-100">
            <h2 class="text-xl font-semibold mb-4 flex items-center gap-2">
                <i class="fa-solid fa-users text-blue-600"></i>
                Generated Accounts
            </h2>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-blue-600 text-white">
                        <tr>
                            <th class="px-4 py-2 text-left">Patient ID</th>
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-left">Username</th>
                            <th class="px-4 py-2 text-left">Password</th>
                            <th class="px-4 py-2 text-left">Email</th>
                            <th class="px-4 py-2 text-left">User ID</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($results as $r)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-2 text-gray-800">{{ $r['patient_id'] }}</td>
                                <td class="px-4 py-2">{{ $r['name'] }}</td>
                                <td class="px-4 py-2 font-mono text-blue-700">{{ $r['username'] }}</td>
                                <td class="px-4 py-2 font-mono text-gray-600">{{ $r['password'] }}</td>
                                <td class="px-4 py-2">{{ $r['email'] }}</td>
                                <td class="px-4 py-2">{{ $r['user_id'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <p class="mt-3 text-sm text-gray-500 flex items-center gap-2">
                <i class="fa-solid fa-lock"></i>
                Passwords are shown once only; all are securely hashed in the database.
            </p>
        </div>
    @endif
</div>
@endsection
