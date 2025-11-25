<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Koperasi Konsinyasi</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            font-family: Segoe UI, Arial;
            background: #f5f7fa;
        }

        .login-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar kiri (branding) */
        .login-left {
            width: 380px;
            background: #2c3e50;
            color: white;
            padding: 40px 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-left h1 {
            font-size: 1.8rem;
            margin-top: 10px;
        }

        .login-left p {
            color: #bdc3c7;
            margin-top: 10px;
            font-size: 0.9rem;
        }

        /* Form login */
        .login-right {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .label-text {
            color: #555;
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
        }

        .input-box {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .btn-primary {
            width: 100%;
            padding: 10px;
            background: #3498db;
            border: 0;
            color: white;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .forgot-link {
            font-size: 0.9rem;
            color: #3498db;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="login-container">

    <!-- Branding sisi kiri -->
    <div class="login-left">
        <i class="fas fa-store fa-3x"></i>
        <h1>Koperasi Konsinyasi</h1>
        <p>Sistem Manajemen Produk, Vendor, Penjualan, dan Pembayaran</p>
    </div>

    <!-- Form login -->
    <div class="login-right">
        <div class="login-card">

            <h2 style="font-weight:700;margin-bottom:20px">Masuk</h2>

            <!-- FORM -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <label class="label-text">Email</label>
                <input type="email" name="email" class="input-box" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div style="color:#e74c3c;font-size:0.9rem;margin-bottom:10px">{{ $message }}</div>
                @enderror

                <!-- Password -->
                <label class="label-text">Password</label>
                <input type="password" name="password" class="input-box" required>
                @error('password')
                    <div style="color:#e74c3c;font-size:0.9rem;margin-bottom:10px">{{ $message }}</div>
                @enderror

                <!-- Remember Me -->
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px">
                    <div>
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Ingat saya</label>
                    </div>

                    @if (Route::has('password.request'))
                        <a class="forgot-link" href="{{ route('password.request') }}">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <button class="btn-primary" type="submit">Masuk</button>

            </form>

        </div>
    </div>
</div>

</body>
</html>
