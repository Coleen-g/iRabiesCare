@extends('admin.layout')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Generate User Accounts for Patients</h1>

    <form method="POST" action="{{ route('admin.generate-users') }}">
        @csrf
        <label class="block mb-2">Role:
            <select name="role" class="border p-2">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </label>
        <button class="px-4 py-2 bg-blue-600 text-white rounded">Generate for all patients without accounts</button>
    </form>

    @if(session('success'))
        <div class="mt-3 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    @if(file_exists(storage_path('patient_new_users.csv')))
        <div class="mt-3">
            <a class="px-3 py-2 bg-gray-800 text-white rounded" href="{{ route('admin.generate-users.download') }}">Download CSV of generated credentials</a>
        </div>
    @endif

    @if (!empty($patients) && $patients->count())
        <div class="mt-6">
            <h2 class="font-semibold mb-2">Patients without accounts ({{ $patients->count() }})</h2>
            <ul>
            @foreach($patients as $p)
                <li>#{{ $p->id }} — {{ $p->name }} ({{ $p->email ?? 'no email' }})</li>
            @endforeach
            </ul>
        </div>
    @endif

    @if (!empty($results) && count($results))
        <div class="mt-6">
            <h2 class="font-semibold mb-2">Generated accounts</h2>
            <table class="w-full border">
                <thead><tr><th>patient_id</th><th>name</th><th>username</th><th>password</th><th>email</th><th>user_id</th></tr></thead>
                <tbody>
                @foreach($results as $r)
                    <tr>
                        <td class="border px-2">{{ $r['patient_id'] }}</td>
                        <td class="border px-2">{{ $r['name'] }}</td>
                        <td class="border px-2">{{ $r['username'] }}</td>
                        <td class="border px-2">{{ $r['password'] }}</td>
                        <td class="border px-2">{{ $r['email'] }}</td>
                        <td class="border px-2">{{ $r['user_id'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <p class="mt-2 text-sm text-gray-600">Note: passwords shown once here; they are hashed in the database.</p>
        </div>
    @endif
</div>
@endsection
