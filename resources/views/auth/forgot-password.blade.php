@extends('auth.login')

@section('content')
    <h2>Reset password</h2>
    <p class="login-sub">Enter your email and we'll send a reset link.</p>

    @if(session('status'))
        <p class="register-text" style="color:green">{{ session('status') }}</p>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <div class="input-group">
            <input type="email" name="email" placeholder="Your email" required value="{{ old('email') }}">
        </div>
        @error('email') <p class="error-text">{{ $message }}</p> @enderror

        <button type="submit" class="login-btn">Send reset link</button>

        <p class="register-text"><a href="{{ route('login') }}">Back to login</a></p>
    </form>
@endsection
