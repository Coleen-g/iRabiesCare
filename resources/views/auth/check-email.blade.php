@extends('auth.login')

@section('content')
    <h2>Check your email</h2>
    <p class="login-sub">We have sent a password reset link to the email address you provided (if an account exists).</p>

    @if(session('status'))
        <p class="register-text" style="color:green">{{ session('status') }}</p>
    @endif
    <p class="register-text">If you don't see the email, check your spam folder or try again in a few minutes.</p>

    <div style="margin-top:1rem;">
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <label for="email" class="register-text">Resend reset link</label>
            <div class="input-group">
                <input id="email" type="email" name="email" placeholder="Email" required value="{{ old('email') }}">
            </div>
            @error('email') <p class="error-text">{{ $message }}</p> @enderror

            <button type="submit" class="login-btn" style="margin-top:.5rem">Resend link</button>
        </form>
        <p class="register-text" style="margin-top:.5rem; font-size:.9rem; color:#666">Note: To prevent abuse this form is rate-limited. If you see an error please wait a minute and try again.</p>
    </div>

    <p class="register-text" style="margin-top:1rem"><a href="{{ route('login') }}">Back to login</a></p>
@endsection
