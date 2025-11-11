<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Password Reset</title>
  <style>
    /* Simple, email-friendly styles with inline fallbacks for clients */
    body {
      font-family: Arial, Helvetica, sans-serif;
      background-color: #f7fdf6; /* very light green */
      color: #0f1722; /* dark text for good contrast */
      margin: 0;
      padding: 24px;
    }
    .container {
      max-width: 600px;
      margin: 0 auto;
    }
    .card {
      background-color: #ffffff;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 2px 8px rgba(15, 23, 34, 0.08);
    }
    .brand {
      display: block;
      font-weight: 700;
      color: #065f46; /* emerald-700 */
      font-size: 20px;
      margin-bottom: 8px;
    }
    h2 {
      margin: 0 0 12px 0;
      font-size: 18px;
      color: #065f46;
    }
    p {
      margin: 0 0 12px 0;
      line-height: 1.4;
      color: #0f1722;
    }
    .details {
      background-color: #ecfdf5; /* emerald-50 */
      border-left: 4px solid #10b981; /* emerald-500 */
      padding: 12px 14px;
      margin: 12px 0;
      border-radius: 4px;
      color: #064e3b;
      word-break: break-word;
    }
    .label {
      font-weight: 600;
      color: #065f46;
    }
    a.button {
      display: inline-block;
      margin-top: 8px;
      background-color: #10b981;
      color: #ffffff !important;
      text-decoration: none;
      padding: 10px 14px;
      border-radius: 6px;
      font-weight: 600;
    }
    .footer {
      margin-top: 16px;
      font-size: 13px;
      color: #334155;
    }
    @media only screen and (max-width: 480px) {
      .container { padding: 0 12px; }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="card">
      <span class="brand">iRabiesCare</span>

     
  <p>Dear {{ optional($user->patient)->name ?? $user->name ?? $user->email }},</p>

      <p>You Have succesfully reset your IRABIESCARE password! Here is your login details:</p>

      <div class="details" style="background-color:#ecfdf5;border-left:4px solid #10b981;">
        <div><span class="label">Username:</span> {{ $user->name}}</div>
        <div style="margin-top:6px"><span class="label">Temporary Password:</span> <strong style="color:#064e3b;">{{ $temporaryPassword }}</strong></div>
      </div>

      <a class="button" href="{{ url('/login') }}">Log in to your account</a>

      <p class="footer">If you did not request this change, please contact your administrator immediately.</p>

      <p style="margin-top:12px">Thank you,<br/>iRabieCare Team</p>
    </div>
  </div>
</body>
</html>