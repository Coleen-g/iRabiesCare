@extends('auth.login')

@section('content')
    <h2>Reset password</h2>
    <p class="login-sub">Set a new password for your account.</p>

    @if(session('status'))
        <p class="register-text" style="color:green">{{ session('status') }}</p>
    @endif

    {{-- Show the target email prominently so users know which account they're resetting --}}
    @php $visibleEmail = $email ?? old('email'); @endphp
    @if(!empty($visibleEmail))
        <p class="register-text" style="font-weight:600">Resetting password for: {{ $visibleEmail }}</p>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="input-group">
            <input type="email" name="email" placeholder="Email" required value="{{ $email ?? old('email') }}">
        </div>
        @error('email') <p class="error-text">{{ $message }}</p> @enderror

        <div class="input-group">
            <input type="password" name="password" placeholder="New password" required>
        </div>
        @error('password') <p class="error-text">{{ $message }}</p> @enderror

        <div class="input-group">
            <input type="password" name="password_confirmation" placeholder="Confirm new password" required>
        </div>

        <button type="submit" class="login-btn">Reset password</button>

        <p class="register-text"><a href="{{ route('login') }}">Back to login</a></p>
    </form>
@endsection
