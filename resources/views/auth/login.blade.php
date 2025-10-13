<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - iRabiesCare</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <div style="max-width:420px;margin:3rem auto;padding:1rem;border:1px solid #ddd;border-radius:6px;">
        <h2>Login</h2>
        <form method="POST" action="/login">
            @csrf
            <div style="margin-bottom:.5rem;">
                <label for="username">Username</label><br>
                <input id="username" name="username" type="text" required style="width:100%;padding:.5rem;" />
            </div>
            <div style="margin-bottom:.5rem;">
                <label for="password">Password</label><br>
                <input id="password" name="password" type="password" required style="width:100%;padding:.5rem;" />
            </div>
            <div style="margin-bottom:.5rem;">
                <label><input type="checkbox" name="remember"> Remember me</label>
            </div>
            <div>
                <button type="submit" style="padding:.5rem 1rem;">Login</button>
                <a href="/register" style="margin-left:1rem;">Register</a>
            </div>
        </form>
    </div>
</body>
</html>