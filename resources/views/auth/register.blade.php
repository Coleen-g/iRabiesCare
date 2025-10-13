<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - iRabiesCare</title>
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
    <div style="max-width:420px;margin:3rem auto;padding:1rem;border:1px solid #ddd;border-radius:6px;">
        <h2>Register</h2>
        <form method="POST" action="/register">
            @csrf
            <div style="margin-bottom:.5rem;">
                <label for="name">Full name</label><br>
                <input id="name" name="name" type="text" required style="width:100%;padding:.5rem;" />
            </div>
            <div style="margin-bottom:.5rem;">
                <label for="contact">Contact (optional)</label><br>
                <input id="contact" name="contact" type="text" style="width:100%;padding:.5rem;" />
            </div>
            <div style="margin-bottom:.5rem;display:flex;gap:.5rem;">
                <div style="flex:1;">
                    <label for="dob">Date of birth (optional)</label><br>
                    <input id="dob" name="dob" type="date" style="width:100%;padding:.5rem;" />
                </div>
                <div style="flex:1;">
                    <label for="gender">Gender (optional)</label><br>
                    <input id="gender" name="gender" type="text" style="width:100%;padding:.5rem;" />
                </div>
            </div>
            <div style="margin-bottom:.5rem;">
                <label for="address">Address (optional)</label><br>
                <textarea id="address" name="address" style="width:100%;padding:.5rem"></textarea>
            </div>
            <div style="margin-bottom:.5rem;">
                <label for="email">Email</label><br>
                <input id="email" name="email" type="email" required style="width:100%;padding:.5rem;" />
            </div>
            <div style="margin-bottom:.5rem;">
                <label for="password">Password</label><br>
                <input id="password" name="password" type="password" required style="width:100%;padding:.5rem;" />
            </div>
            <div style="margin-bottom:.5rem;">
                <label for="password_confirmation">Confirm password</label><br>
                <input id="password_confirmation" name="password_confirmation" type="password" required style="width:100%;padding:.5rem;" />
            </div>
            <div>
                <button type="submit" style="padding:.5rem 1rem;">Register</button>
                <a href="/" style="margin-left:1rem;">Back to login</a>
            </div>
        </form>
    </div>
</body>
</html>