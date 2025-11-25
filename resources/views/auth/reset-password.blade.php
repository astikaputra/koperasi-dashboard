<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { background:#f5f7fa;font-family:Segoe UI;margin:0; }
        .auth-center { display:flex;justify-content:center;align-items:center;min-height:100vh; }
        .auth-card {
            width:100%;max-width:420px;background:white;padding:35px;border-radius:12px;
            box-shadow:0 4px 12px rgba(0,0,0,.1)
        }
        .label-text { font-weight:600;margin-top:10px; }
        .input-box { width:100%;padding:10px;border-radius:6px;border:1px solid #ddd;margin-top:6px; }
        .btn-primary { width:100%;padding:10px;background:#3498db;color:white;border:0;border-radius:6px;font-weight:600;margin-top:15px; }
    </style>
</head>

<body>
<div class="auth-center">

    <div class="auth-card">
        <h2 style="font-weight:700">Reset Password</h2>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <label class="label-text">Email</label>
            <input type="email" name="email" class="input-box" required value="{{ old('email', $email) }}">

            <label class="label-text">Password Baru</label>
            <input type="password" name="password" class="input-box" required>

            <label class="label-text">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="input-box" required>

            <button class="btn-primary">Ubah Password</button>
        </form>
    </div>

</div>
</body>
</html>
