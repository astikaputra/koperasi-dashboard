<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Dashboard' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome & Chart.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* ==== Dashboard Style dari file .html Anda ==== */
        :root{--primary:#3498db;--secondary:#2ecc71;--danger:#e74c3c;--warning:#f39c12;--dark:#2c3e50;--light:#ecf0f1;--gray:#95a5a6}
        body{background:#f5f7fa;color:#333;margin:0;font-family:Segoe UI,Arial}
        .container-dashboard{display:flex;min-height:100vh}

        /* Sidebar */
        .sidebar{width:250px;background:var(--dark);color:white}
        .sidebar-header{padding:20px;display:flex;align-items:center;background:rgba(255,255,255,.1)}
        .sidebar-header i{font-size:28px}
        .sidebar-header h2{margin-left:10px;font-size:1.2rem}

        .sidebar ul{list-style:none;padding:0;margin:0}
        .sidebar li{padding:12px 20px}
        .sidebar a{color:white;text-decoration:none;display:flex;align-items:center}
        .sidebar li.active{background:var(--primary)}

        /* Main Content */
        .main{flex:1;padding:25px}
        .header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;padding-bottom:10px;border-bottom:1px solid #ddd}
        .cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:20px;margin-bottom:20px}
        .card{background:white;border-radius:10px;padding:20px;box-shadow:0 4px 6px rgba(0,0,0,0.1)}
    </style>

    @stack('head')
</head>
<body>
    <div class="container-dashboard">

        <!-- SIDEBAR -->
        <div class="sidebar">
            <div class="sidebar-header">
                <i class="fas fa-store"></i>
                <h2>Koperasi</h2>
            </div>

<ul>
    <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}">
            <i class="fas fa-tachometer-alt" style="width:20px"></i>
            <span style="margin-left:8px">Dashboard</span>
        </a>
    </li>

    <li class="{{ request()->is('produk*') ? 'active' : '' }}">
        <a href="{{ route('produk.index') }}">
            <i class="fas fa-box"></i>
            <span style="margin-left:8px">Produk</span>
        </a>
    </li>

    <li class="{{ request()->is('kategori*') ? 'active' : '' }}">
        <a href="{{ route('kategori.index') }}">
            <i class="fas fa-tags"></i>
            <span style="margin-left:8px">Kategori Produk</span>
        </a>
    </li>

    <li><a href="#"><i class="fas fa-truck"></i><span style="margin-left:8px">Pemasok</span></a></li>

    <li><a href="#"><i class="fas fa-shopping-cart"></i><span style="margin-left:8px">Penjualan</span></a></li>

    <li><a href="#"><i class="fas fa-file-invoice"></i><span style="margin-left:8px">Pembayaran</span></a></li>

    <li><a href="#"><i class="fas fa-chart-line"></i><span style="margin-left:8px">Laporan</span></a></li>

    <!-- ===================== -->
    <!--      PENGATURAN       -->
    <!-- ===================== -->
    <li class="{{ request()->is('markup*') || request()->is('overhead*') ? 'active' : '' }}">
        <a href="#">
            <i class="fas fa-cog"></i>
            <span style="margin-left:8px">Pengaturan</span>
        </a>

        <ul class="nav nav-second-level" style="margin-left:20px; margin-top:5px;">
            <li class="{{ request()->is('markup*') ? 'active' : '' }}">
                <a href="{{ route('markup.index') }}">
                    → Markup Global
                </a>
            </li>

            <li class="{{ request()->is('overhead*') ? 'active' : '' }}">
                <a href="{{ route('overhead.index') }}">
                    → Overhead Biaya
                </a>
            </li>
        </ul>
    </li>

    <li>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button style="margin-top:10px" class="btn btn-primary w-full">Logout</button>
        </form>
    </li>

</ul>

        </div>

        <!-- MAIN CONTENT -->
        <div class="main">
            <div class="header">
                <h1>{{ $title ?? 'Dashboard' }}</h1>

                <div style="display:flex;align-items:center">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=3498db&color=fff"
                         style="width:45px;height:45px;border-radius:50%;margin-right:10px">
                    <div>
                        <div style="font-weight:600">{{ auth()->user()->name }}</div>
                        <div style="font-size:12px;color:gray">{{ auth()->user()->email }}</div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            {{ $slot }}
        </div>
    </div>

    @stack('scripts')
</body>
</html>
