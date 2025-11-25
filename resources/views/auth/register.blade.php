<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar | Koperasi Konsinyasi</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            font-family: Segoe UI, Arial;
            background: #f5f7fa;
        }

        .auth-container { display: flex; min-height: 100vh; }

        /* Left branding */
        .auth-left {
            width: 380px;
            background: #2c3e50;
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-left h1 { font-size: 1.8rem; }
        .auth-left p { color: #bdc3c7; font-size: 0.9rem; }

        /* Right form */
        .auth-right {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .auth-card {
            width: 100%;
            max-width: 450px;
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .label-text { color:#555; font-weight:600; margin-bottom:5px; display:block; }
        .input-box {
            width:100%; padding:10px; border-radius:6px; border:1px solid #ddd;
            margin-bottom:15px;
        }

        .btn-primary {
            width:100%; padding:10px; background:#3498db; border:0; color:white;
            border-radius:6px; cursor:pointer;
        }
        .btn-primary:hover { background:#2980b9; }

    </style>
</head>

<body>
<div class="auth-container">

    <div class="auth-left">
        <i class="fas fa-user-plus fa-3x"></i>
        <h1>Buat Akun Baru</h1>
        <p>Daftar untuk mengakses Dashboard Koperasi</p>
    </div>

    <div class="auth-right">
        <div class="auth-card">
            <h2 style="font-weight:700;margin-bottom:20px">Daftar Akun</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <label class="label-text">Nama</label>
                <input type="text" name="name" class="input-box" required value="{{ old('name') }}">

                <label class="label-text">Email</label>
                <input type="email" name="email" class="input-box" required value="{{ old('email') }}">

                <label class="label-text">Password</label>
                <input type="password" name="password" class="input-box" required>

                <label class="label-text">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="input-box" required>

                <button class="btn-primary">Daftar</button>

                <div style="margin-top:15px;text-align:center">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" style="color:#3498db;text-decoration:none;font-weight:600">
                        Masuk di sini
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>
</body>
</html>
