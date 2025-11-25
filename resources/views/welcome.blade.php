<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang | Koperasi Konsinyasi</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            margin: 0;
            font-family: Segoe UI, Arial;
            background: #f5f7fa;
        }

        .welcome-container {
            display: flex;
            min-height: 100vh;
        }

        /* Left Brand Panel */
        .welcome-left {
            width: 420px;
            background: #2c3e50;
            color: white;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .welcome-left h1 {
            font-size: 2rem;
            margin-top: 10px;
        }

        .welcome-left p {
            font-size: 1rem;
            color: #bdc3c7;
            margin-top: 10px;
            line-height: 1.6;
        }

        /* Right Content */
        .welcome-right {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 50px;
        }

        .welcome-card {
            background: white;
            padding: 40px;
            border-radius: 14px;
            width: 100%;
            max-width: 480px;
            text-align: center;
            box-shadow: 0 6px 14px rgba(0,0,0,0.08);
        }

        .welcome-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .welcome-desc {
            color: #666;
            margin-bottom: 30px;
            font-size: 1rem;
        }

        .btn-primary {
            width: 100%;
            padding: 12px;
            background: #3498db;
            color: white;
            border: none;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            margin-bottom: 15px;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .btn-secondary {
            width: 100%;
            padding: 12px;
            background: #2c3e50;
            color: white;
            border: none;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-secondary:hover {
            background: #1f2d3a;
        }
    </style>
</head>

<body>

<div class="welcome-container">

    <!-- Branding Sidebar -->
    <div class="welcome-left">
        <i class="fas fa-store fa-3x"></i>
        <h1>Koperasi Konsinyasi</h1>
        <p>
            Sistem manajemen penjualan, vendor, produk konsinyasi, pembayaran,
            laporan, dan akuntansi untuk koperasi modern.
        </p>
    </div>

    <!-- Main Section -->
    <div class="welcome-right">
        <div class="welcome-card">

            <div class="welcome-title">Selamat Datang!</div>
            <div class="welcome-desc">
                Silakan masuk untuk menggunakan dashboard koperasi atau daftar jika Anda belum memiliki akun.
            </div>

            <a href="{{ route('login') }}">
                <button class="btn-primary">Masuk</button>
            </a>

            <a href="{{ route('register') }}">
                <button class="btn-secondary">Daftar Akun</button>
            </a>

        </div>
    </div>

</div>

</body>
</html>
