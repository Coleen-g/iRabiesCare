@extends('auth.login')

@section('content')
    <h2>Login</h2>
    <p class="login-sub">Welcome back! Please log in to continue.</p>

    @if(session('error'))
      <p class="error-text">{{ session('error') }}</p>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="input-group">
        <input type="text" name="username" placeholder="Username or Email" required>
      </div>
      <div class="input-group">
        <input type="password" name="password" placeholder="Password" required>
      </div>

      <div class="remember-forgot">
        <label><input type="checkbox" name="remember"> Remember me</label>
        <a href="{{ route('password.request') }}">Forgot Password?</a>
      </div>

      <button type="submit" class="login-btn">LOGIN</button>

      <p class="register-text">New user? <a href="{{ route('register') }}">Sign up</a></p>
    </form>
@endsection
