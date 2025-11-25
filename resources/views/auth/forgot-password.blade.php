<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background:#f5f7fa;font-family:Segoe UI,Arial;margin:0; }
        .auth-center { display:flex;justify-content:center;align-items:center;min-height:100vh; }
        .auth-card {
            width:100%;max-width:420px;background:white;padding:35px;border-radius:12px;
            box-shadow:0 4px 12px rgba(0,0,0,.1)
        }
        .label-text { font-weight:600;margin-top:10px; }
        .input-box {
            width:100%;padding:10px;border-radius:6px;border:1px solid #ddd;margin-top:6px;
        }
        .btn-primary { width:100%;padding:10px;background:#3498db;color:white;border:0;border-radius:6px;font-weight:600;margin-top:15px; }
    </style>
</head>

<body>
<div class="auth-center">

    <div class="auth-card">
        <h2 style="font-weight:700">Lupa Password</h2>
        <p style="color:#888;font-size:0.9rem;margin-bottom:20px">Masukkan email Anda untuk mengatur ulang password.</p>

        @if (session('status'))
            <div style="background:#2ecc71;padding:10px;border-radius:6px;color:white;margin-bottom:12px">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <label class="label-text">Email</label>
            <input type="email" name="email" class="input-box" required>

            <button class="btn-primary">Kirim Link Reset</button>
        </form>

        <div style="margin-top:15px;text-align:center">
            <a href="{{ route('login') }}" style="color:#3498db;text-decoration:none;font-weight:600">
                Kembali ke Login
            </a>
        </div>

    </div>

</div>
</body>
</html>
