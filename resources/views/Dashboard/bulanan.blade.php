<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penjualan Bulanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .progress-bar {
            transition: width 1s ease-in-out;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8 animate-fade-in">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Dashboard Penjualan Bulanan
            </h1>
            <div class="flex space-x-2">
                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard Utama
                </a>
                <a href="{{ route('dashboard.bulanan') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Dashboard Bulanan
                </a>
                <a href="{{ route('dashboard.labarugi') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Dashboard Laba Rugi
                </a>
                <div class="flex space-x-2">
            </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8 animate-fade-in">
            <h2 class="text-xl font-semibold mb-4 text-gray-700">Pilih Periode</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                    <select id="monthSelect" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                    <select id="yearSelect" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @for($i = now()->year - 2; $i <= now()->year; $i++)
                            <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="flex items-end">
                    <button onclick="filterData()" 
                            class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        Tampilkan Data
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistik Utama -->
        @php
            $statistik = $penjualanBulanan['statistik'];
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 animate-fade-in">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-lg shadow-lg card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Total Penjualan</p>
                        <p class="text-2xl font-bold mt-1">Rp {{ number_format($statistik['total_gabungan'], 0, ',', '.') }}</p>
                    </div>
                    <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="mt-4 text-sm flex justify-between">
                    <span>Offline: Rp {{ number_format($statistik['total_offline'], 0, ',', '.') }}</span>
                    <span>Online: Rp {{ number_format($statistik['total_online'], 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-lg shadow-lg card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Total Transaksi</p>
                        <p class="text-2xl font-bold mt-1">{{ number_format($statistik['total_transaksi_gabungan'], 0, ',', '.') }}</p>
                    </div>
                    <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div class="mt-4 text-sm flex justify-between">
                    <span>Offline: {{ number_format($statistik['total_transaksi_offline'], 0, ',', '.') }}</span>
                    <span>Online: {{ number_format($statistik['total_order_online'], 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-lg shadow-lg card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Rata-rata per Hari</p>
                        <p class="text-2xl font-bold mt-1">Rp {{ number_format($statistik['rata_rata_harian_gabungan'], 0, ',', '.') }}</p>
                    </div>
                    <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="mt-4 text-sm">
                    <span>{{ $penjualanBulanan['nama_bulan'] }}</span>
                </div>
            </div>

            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white p-6 rounded-lg shadow-lg card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Komposisi</p>
                        <p class="text-2xl font-bold mt-1">{{ $statistik['persentase_offline'] }}% / {{ $statistik['persentase_online'] }}%</p>
                    </div>
                    <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                    </svg>
                </div>
                <div class="mt-4">
                    <div class="w-full bg-white bg-opacity-30 rounded-full h-2">
                        <div class="bg-white h-2 rounded-full progress-bar" style="width: {{ $statistik['persentase_offline'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik Utama -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8 animate-fade-in">
            <h2 class="text-xl font-semibold mb-4 text-gray-700 flex items-center">
                <svg class="w-6 h-6 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                </svg>
                Grafik Penjualan Harian - {{ $penjualanBulanan['nama_bulan'] }}
            </h2>
            
            <div class="mb-6">
                <canvas id="mainChart" height="100"></canvas>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-blue-800 flex items-center">
                        <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                        Penjualan Tertinggi
                    </h3>
                    @if($statistik['hari_dengan_penjualan_tertinggi'])
                    <p class="text-2xl font-bold mt-2">{{ $statistik['hari_dengan_penjualan_tertinggi']['tanggal'] }}</p>
                    <p class="text-lg text-blue-600">Rp {{ number_format($statistik['hari_dengan_penjualan_tertinggi']['total'], 0, ',', '.') }}</p>
                    @else
                    <p class="text-gray-500 mt-2">Tidak ada data</p>
                    @endif
                </div>
                
                <div class="bg-green-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-green-800 flex items-center">
                        <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                        Rata-rata Harian
                    </h3>
                    <p class="text-2xl font-bold mt-2">Rp {{ number_format($statistik['rata_rata_harian_gabungan'], 0, ',', '.') }}</p>
                    <p class="text-sm text-gray-600">dari {{ $penjualanBulanan['days_in_month'] }} hari</p>
                </div>
                
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-yellow-800 flex items-center">
                        <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                        Hari dengan Penjualan
                    </h3>
                    @php
                        $hariDenganPenjualan = 0;
                        foreach($harian as $item) {
                            if($item['total']['penjualan'] > 0) $hariDenganPenjualan++;
                        }
                    @endphp
                    <p class="text-2xl font-bold mt-2">{{ $hariDenganPenjualan }} / {{ $penjualanBulanan['days_in_month'] }} hari</p>
                    <div class="w-full bg-yellow-200 rounded-full h-2 mt-2">
                        <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ ($hariDenganPenjualan / $penjualanBulanan['days_in_month']) * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Perbandingan -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8 animate-fade-in">
            <!-- Perbandingan Metode Pembayaran -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4 text-gray-700 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Perbandingan Metode Pembayaran Offline
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <!-- Cash -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-lg border border-green-200">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-bold text-green-800">Cash</h3>
                            <span class="text-2xl text-green-600">💰</span>
                        </div>
                        <p class="text-2xl font-bold text-green-700">Rp {{ number_format($perbandinganPembayaran['cash']['penjualan'], 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-600">{{ $perbandinganPembayaran['cash']['transaksi'] }} transaksi</p>
                        <div class="mt-2">
                            <div class="w-full bg-green-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $perbandinganPembayaran['cash']['persentase'] }}%"></div>
                            </div>
                            <p class="text-xs text-right mt-1">{{ $perbandinganPembayaran['cash']['persentase'] }}% dari total</p>
                        </div>
                    </div>
                    
                    <!-- QRIS -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg border border-blue-200">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-bold text-blue-800">QRIS</h3>
                            <span class="text-2xl text-blue-600">📱</span>
                        </div>
                        <p class="text-2xl font-bold text-blue-700">Rp {{ number_format($perbandinganPembayaran['qris']['penjualan'], 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-600">{{ $perbandinganPembayaran['qris']['transaksi'] }} transaksi</p>
                        <div class="mt-2">
                            <div class="w-full bg-blue-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $perbandinganPembayaran['qris']['persentase'] }}%"></div>
                            </div>
                            <p class="text-xs text-right mt-1">{{ $perbandinganPembayaran['qris']['persentase'] }}% dari total</p>
                        </div>
                    </div>
                    
                    <!-- Lainnya -->
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-4 rounded-lg border border-gray-200">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-bold text-gray-800">Lainnya</h3>
                            <span class="text-2xl text-gray-600">💳</span>
                        </div>
                        <p class="text-2xl font-bold text-gray-700">Rp {{ number_format($perbandinganPembayaran['lainnya']['penjualan'], 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-600">{{ $perbandinganPembayaran['lainnya']['transaksi'] }} transaksi</p>
                        <div class="mt-2">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gray-600 h-2 rounded-full" style="width: {{ $perbandinganPembayaran['lainnya']['persentase'] }}%"></div>
                            </div>
                            <p class="text-xs text-right mt-1">{{ $perbandinganPembayaran['lainnya']['persentase'] }}% dari total</p>
                        </div>
                    </div>
                </div>
                
                <!-- Grafik Perbandingan Pembayaran -->
                <canvas id="paymentChart" height="80"></canvas>
            </div>

            <!-- Perbandingan Tipe Pelanggan Offline -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4 text-gray-700 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Perbandingan Tipe Pelanggan Offline
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <!-- Umum -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg border border-blue-200">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-bold text-blue-800">Umum</h3>
                            <span class="text-2xl text-blue-600">👤</span>
                        </div>
                        <p class="text-2xl font-bold text-blue-700">Rp {{ number_format($perbandinganTipeOffline['data']['umum']['penjualan'] ?? 0, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-600">{{ $perbandinganTipeOffline['data']['umum']['transaksi'] ?? 0 }} transaksi</p>
                        <p class="text-xs text-gray-500">Rata-rata: Rp {{ number_format($perbandinganTipeOffline['data']['umum']['rata_rata'] ?? 0, 0, ',', '.') }}</p>
                        <div class="mt-2">
                            <div class="w-full bg-blue-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $perbandinganTipeOffline['data']['umum']['persentase'] ?? 0 }}%"></div>
                            </div>
                            <p class="text-xs text-right mt-1">{{ $perbandinganTipeOffline['data']['umum']['persentase'] ?? 0 }}% dari total</p>
                        </div>
                    </div>
                    
                    <!-- Anggota -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-lg border border-green-200">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-bold text-green-800">Anggota</h3>
                            <span class="text-2xl text-green-600">👥</span>
                        </div>
                        <p class="text-2xl font-bold text-green-700">Rp {{ number_format($perbandinganTipeOffline['data']['anggota']['penjualan'] ?? 0, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-600">{{ $perbandinganTipeOffline['data']['anggota']['transaksi'] ?? 0 }} transaksi</p>
                        <p class="text-xs text-gray-500">Rata-rata: Rp {{ number_format($perbandinganTipeOffline['data']['anggota']['rata_rata'] ?? 0, 0, ',', '.') }}</p>
                        <div class="mt-2">
                            <div class="w-full bg-green-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ $perbandinganTipeOffline['data']['anggota']['persentase'] ?? 0 }}%"></div>
                            </div>
                            <p class="text-xs text-right mt-1">{{ $perbandinganTipeOffline['data']['anggota']['persentase'] ?? 0 }}% dari total</p>
                        </div>
                    </div>
                    
                    <!-- Karyawan -->
                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-4 rounded-lg border border-yellow-200">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-bold text-yellow-800">Karyawan</h3>
                            <span class="text-2xl text-yellow-600">👔</span>
                        </div>
                        <p class="text-2xl font-bold text-yellow-700">Rp {{ number_format($perbandinganTipeOffline['data']['karyawan']['penjualan'] ?? 0, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-600">{{ $perbandinganTipeOffline['data']['karyawan']['transaksi'] ?? 0 }} transaksi</p>
                        <p class="text-xs text-gray-500">Rata-rata: Rp {{ number_format($perbandinganTipeOffline['data']['karyawan']['rata_rata'] ?? 0, 0, ',', '.') }}</p>
                        <div class="mt-2">
                            <div class="w-full bg-yellow-200 rounded-full h-2">
                                <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $perbandinganTipeOffline['data']['karyawan']['persentase'] ?? 0 }}%"></div>
                            </div>
                            <p class="text-xs text-right mt-1">{{ $perbandinganTipeOffline['data']['karyawan']['persentase'] ?? 0 }}% dari total</p>
                        </div>
                    </div>
                </div>
                
                <!-- Grafik Perbandingan Tipe Pelanggan Offline -->
                <canvas id="customerOfflineChart" height="80"></canvas>
            </div>
        </div>

        <!-- Section Perbandingan Online -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8 animate-fade-in">
            <!-- Perbandingan Status Order Online -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4 text-gray-700 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Status Order Online
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <!-- Selesai -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-lg border border-green-200">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-bold text-green-800">Selesai</h3>
                            <span class="text-2xl text-green-600">✅</span>
                        </div>
                        <p class="text-2xl font-bold text-green-700">Rp {{ number_format($perbandinganTipeOnline['status']['selesai']['penjualan'] ?? 0, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-600">{{ $perbandinganTipeOnline['status']['selesai']['order'] ?? 0 }} order</p>
                    </div>
                    
                    <!-- Proses -->
                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-4 rounded-lg border border-yellow-200">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-bold text-yellow-800">Proses</h3>
                            <span class="text-2xl text-yellow-600">⏳</span>
                        </div>
                        <p class="text-2xl font-bold text-yellow-700">Rp {{ number_format($perbandinganTipeOnline['status']['proses']['penjualan'] ?? 0, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-600">{{ $perbandinganTipeOnline['status']['proses']['order'] ?? 0 }} order</p>
                    </div>
                    
                    <!-- Batal -->
                    <div class="bg-gradient-to-br from-red-50 to-red-100 p-4 rounded-lg border border-red-200">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-bold text-red-800">Batal</h3>
                            <span class="text-2xl text-red-600">❌</span>
                        </div>
                        <p class="text-2xl font-bold text-red-700">Rp {{ number_format($perbandinganTipeOnline['status']['batal']['penjualan'] ?? 0, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-600">{{ $perbandinganTipeOnline['status']['batal']['order'] ?? 0 }} order</p>
                    </div>
                </div>
                
                <!-- Grafik Status Order -->
                <canvas id="orderStatusChart" height="80"></canvas>
            </div>

        <!-- Perbandingan Tipe Pelanggan Online -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4 text-gray-700 flex items-center">
                <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Tipe Pelanggan Online
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Anggota -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl border border-purple-200 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-purple-200 rounded-full flex items-center justify-center mr-3">
                                <span class="text-2xl text-purple-600">⭐</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-purple-800">Anggota</h3>
                                <p class="text-xs text-purple-600">Member dengan keanggotaan</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-medium text-purple-600">{{ $perbandinganTipeOnline['tipe_pelanggan']['anggota']['persentase_penjualan'] ?? 0 }}%</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="bg-white bg-opacity-60 p-3 rounded-lg">
                            <p class="text-xs text-purple-600">Total Order</p>
                            <p class="text-2xl font-bold text-purple-800">{{ number_format($perbandinganTipeOnline['tipe_pelanggan']['anggota']['order'] ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-white bg-opacity-60 p-3 rounded-lg">
                            <p class="text-xs text-purple-600">Total Penjualan</p>
                            <p class="text-lg font-bold text-purple-800">Rp {{ number_format($perbandinganTipeOnline['tipe_pelanggan']['anggota']['penjualan'] ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-purple-700">Rata-rata per Order</span>
                            <span class="font-bold text-purple-800">Rp {{ number_format($perbandinganTipeOnline['tipe_pelanggan']['anggota']['rata_rata'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-purple-200 rounded-full h-2">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $perbandinganTipeOnline['tipe_pelanggan']['anggota']['persentase_penjualan'] ?? 0 }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-purple-600">
                            <span>Kontribusi Order: {{ $perbandinganTipeOnline['tipe_pelanggan']['anggota']['persentase_order'] ?? 0 }}%</span>
                            <span>Kontribusi Nilai: {{ $perbandinganTipeOnline['tipe_pelanggan']['anggota']['persentase_penjualan'] ?? 0 }}%</span>
                        </div>
                    </div>
                </div>
                
                <!-- Karyawan -->
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-6 rounded-xl border border-yellow-200 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-yellow-200 rounded-full flex items-center justify-center mr-3">
                                <span class="text-2xl text-yellow-600">👔</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-yellow-800">Karyawan</h3>
                                <p class="text-xs text-yellow-600">Karyawan non-anggota</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-medium text-yellow-600">{{ $perbandinganTipeOnline['tipe_pelanggan']['karyawan']['persentase_penjualan'] ?? 0 }}%</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="bg-white bg-opacity-60 p-3 rounded-lg">
                            <p class="text-xs text-yellow-600">Total Order</p>
                            <p class="text-2xl font-bold text-yellow-800">{{ number_format($perbandinganTipeOnline['tipe_pelanggan']['karyawan']['order'] ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-white bg-opacity-60 p-3 rounded-lg">
                            <p class="text-xs text-yellow-600">Total Penjualan</p>
                            <p class="text-lg font-bold text-yellow-800">Rp {{ number_format($perbandinganTipeOnline['tipe_pelanggan']['karyawan']['penjualan'] ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-yellow-700">Rata-rata per Order</span>
                            <span class="font-bold text-yellow-800">Rp {{ number_format($perbandinganTipeOnline['tipe_pelanggan']['karyawan']['rata_rata'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="w-full bg-yellow-200 rounded-full h-2">
                            <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $perbandinganTipeOnline['tipe_pelanggan']['karyawan']['persentase_penjualan'] ?? 0 }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-yellow-600">
                            <span>Kontribusi Order: {{ $perbandinganTipeOnline['tipe_pelanggan']['karyawan']['persentase_order'] ?? 0 }}%</span>
                            <span>Kontribusi Nilai: {{ $perbandinganTipeOnline['tipe_pelanggan']['karyawan']['persentase_penjualan'] ?? 0 }}%</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Grafik Perbandingan Anggota vs Karyawan -->
            <div class="mt-6">
                <h3 class="text-md font-semibold mb-3 text-gray-700">Perbandingan Anggota vs Karyawan</h3>
                <canvas id="customerOnlineChart" height="80"></canvas>
            </div>
            
            <!-- Ringkasan Perbandingan -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-medium text-purple-800">Selisih Order</span>
                        @php
                            $orderAnggota = $perbandinganTipeOnline['tipe_pelanggan']['anggota']['order'] ?? 0;
                            $orderKaryawan = $perbandinganTipeOnline['tipe_pelanggan']['karyawan']['order'] ?? 0;
                            $selisihOrder = abs($orderAnggota - $orderKaryawan);
                            $lebihBanyak = $orderAnggota > $orderKaryawan ? 'Anggota' : ($orderKaryawan > $orderAnggota ? 'Karyawan' : 'Sama');
                        @endphp
                        <span class="text-lg font-bold text-purple-800">{{ number_format($selisihOrder, 0, ',', '.') }} order</span>
                    </div>
                    <p class="text-sm text-purple-600">Lebih banyak oleh <span class="font-bold">{{ $lebihBanyak }}</span></p>
                </div>
                
                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-medium text-yellow-800">Selisih Penjualan</span>
                        @php
                            $penjualanAnggota = $perbandinganTipeOnline['tipe_pelanggan']['anggota']['penjualan'] ?? 0;
                            $penjualanKaryawan = $perbandinganTipeOnline['tipe_pelanggan']['karyawan']['penjualan'] ?? 0;
                            $selisihPenjualan = abs($penjualanAnggota - $penjualanKaryawan);
                            $lebihBanyakPenjualan = $penjualanAnggota > $penjualanKaryawan ? 'Anggota' : ($penjualanKaryawan > $penjualanAnggota ? 'Karyawan' : 'Sama');
                        @endphp
                        <span class="text-lg font-bold text-yellow-800">Rp {{ number_format($selisihPenjualan, 0, ',', '.') }}</span>
                    </div>
                    <p class="text-sm text-yellow-600">Lebih besar oleh <span class="font-bold">{{ $lebihBanyakPenjualan }}</span></p>
                </div>
            </div>
            
            <!-- Total Keseluruhan -->
            <div class="mt-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-blue-800">Total Order (Anggota + Karyawan):</span>
                        <span class="float-right font-bold text-blue-900">{{ number_format(($perbandinganTipeOnline['tipe_pelanggan']['anggota']['order'] ?? 0) + ($perbandinganTipeOnline['tipe_pelanggan']['karyawan']['order'] ?? 0), 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <span class="font-medium text-blue-800">Total Penjualan:</span>
                        <span class="float-right font-bold text-blue-900">Rp {{ number_format(($perbandinganTipeOnline['tipe_pelanggan']['anggota']['penjualan'] ?? 0) + ($perbandinganTipeOnline['tipe_pelanggan']['karyawan']['penjualan'] ?? 0), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <!-- Grafik Perbandingan dan Tren -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8 animate-fade-in">
            <!-- Grafik Perbandingan Offline vs Online -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold mb-4 text-gray-700 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Perbandingan Offline vs Online
                </h3>
                <canvas id="comparisonChart" height="120"></canvas>
                <div class="mt-4 grid grid-cols-2 gap-4">
                    <div class="text-center p-3 bg-blue-50 rounded-lg">
                        <p class="text-sm text-blue-800">Offline</p>
                        <p class="text-xl font-bold text-blue-600">Rp {{ number_format($statistik['total_offline'], 0, ',', '.') }}</p>
                        <p class="text-xs text-blue-600">{{ $statistik['persentase_offline'] }}% dari total</p>
                    </div>
                    <div class="text-center p-3 bg-green-50 rounded-lg">
                        <p class="text-sm text-green-800">Online</p>
                        <p class="text-xl font-bold text-green-600">Rp {{ number_format($statistik['total_online'], 0, ',', '.') }}</p>
                        <p class="text-xs text-green-600">{{ $statistik['persentase_online'] }}% dari total</p>
                    </div>
                </div>
            </div>

            <!-- Grafik Tren Mingguan -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold mb-4 text-gray-700 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Heatmap Penjualan (Offline vs Online)
                </h3>
                
                <!-- Legend -->
                <div class="flex flex-wrap gap-4 mb-4 text-sm">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
                        <span>Offline</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                        <span>Online</span>
                    </div>
                </div>
                
                <div class="grid grid-cols-7 gap-1">
                    @foreach(['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day)
                        <div class="text-center text-xs font-medium text-gray-600 py-1">{{ $day }}</div>
                    @endforeach
                    
                    @foreach($heatmap as $weekIndex => $week)
                        @foreach($week as $dayIndex => $day)
                            @php
                                $hasData = $day && $day['hari'] !== null;
                                $offlineValue = $hasData ? $day['offline'] : 0;
                                $onlineValue = $hasData ? $day['online'] : 0;
                                $totalValue = $offlineValue + $onlineValue;
                                
                                // Hitung intensitas warna untuk offline
                                $maxOffline = $statistik['total_offline'] > 0 ? $statistik['total_offline'] / $penjualanBulanan['days_in_month'] * 2 : 1;
                                $offlineIntensity = $maxOffline > 0 ? min(100, ($offlineValue / $maxOffline) * 100) : 0;
                                
                                // Hitung intensitas warna untuk online
                                $maxOnline = $statistik['total_online'] > 0 ? $statistik['total_online'] / $penjualanBulanan['days_in_month'] * 2 : 1;
                                $onlineIntensity = $maxOnline > 0 ? min(100, ($onlineValue / $maxOnline) * 100) : 0;
                                
                                // Tentukan warna berdasarkan intensitas tertinggi
                                if ($offlineValue > $onlineValue) {
                                    $bgColor = $offlineValue > 0 ? "rgba(59, 130, 246, " . ($offlineIntensity / 100) . ")" : '#f3f4f6';
                                    $textColor = $offlineIntensity > 50 ? 'text-white' : 'text-gray-800';
                                    $dominant = 'offline';
                                } else {
                                    $bgColor = $onlineValue > 0 ? "rgba(16, 185, 129, " . ($onlineIntensity / 100) . ")" : '#f3f4f6';
                                    $textColor = $onlineIntensity > 50 ? 'text-white' : 'text-gray-800';
                                    $dominant = 'online';
                                }
                                
                                $title = '';
                                if ($hasData) {
                                    $title = $day['tanggal'] . "\n";
                                    $title .= "Offline: Rp " . number_format($offlineValue, 0, ',', '.') . " (" . $day['offline_transaksi'] . " transaksi)\n";
                                    $title .= "Online: Rp " . number_format($onlineValue, 0, ',', '.') . " (" . $day['online_order'] . " order)\n";
                                    $title .= "Total: Rp " . number_format($totalValue, 0, ',', '.');
                                }
                            @endphp
                            <div class="aspect-square rounded-lg flex items-center justify-center text-center p-1 {{ $textColor }} relative group"
                                style="background-color: {{ $bgColor }}; border: {{ $hasData ? '1px solid #e5e7eb' : 'none' }}"
                                @if($hasData) title="{{ $title }}" @endif>
                                
                                @if($hasData)
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity bg-black bg-opacity-50 rounded-lg text-white text-[8px] p-1">
                                        <div class="text-center">
                                            <div class="font-bold">{{ $day['hari'] }}</div>
                                            <div>O: Rp {{ number_format($offlineValue/1000,0) }}k</div>
                                            <div>N: Rp {{ number_format($onlineValue/1000,0) }}k</div>
                                        </div>
                                    </div>
                                    <div class="text-center {{ $textColor }} group-hover:opacity-0 transition-opacity">
                                        <span class="text-xs font-bold block">{{ $day['hari'] }}</span>
                                        <span class="text-[10px] block">
                                            @if($dominant == 'offline')
                                                <span class="text-blue-600">●</span>
                                            @elseif($dominant == 'online')
                                                <span class="text-green-600">●</span>
                                            @endif
                                            Rp{{ number_format($totalValue/1000,0) }}k
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endforeach
                </div>
                
                <!-- Legend Intensitas -->
                <div class="mt-6 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-medium text-gray-600 mb-2">Intensitas Offline</p>
                        <div class="flex items-center space-x-1">
                            <span class="text-xs">Rendah</span>
                            <div class="flex-1 h-2 rounded-full bg-gradient-to-r from-blue-100 to-blue-600"></div>
                            <span class="text-xs">Tinggi</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-600 mb-2">Intensitas Online</p>
                        <div class="flex items-center space-x-1">
                            <span class="text-xs">Rendah</span>
                            <div class="flex-1 h-2 rounded-full bg-gradient-to-r from-green-100 to-green-600"></div>
                            <span class="text-xs">Tinggi</span>
                        </div>
                    </div>
                </div>
                
                <!-- Statistik Heatmap -->
                <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                    <div class="bg-blue-50 p-2 rounded">
                        <span class="font-medium">Total Offline:</span>
                        <span class="float-right">Rp {{ number_format($statistik['total_offline'], 0, ',', '.') }}</span>
                    </div>
                    <div class="bg-green-50 p-2 rounded">
                        <span class="font-medium">Total Online:</span>
                        <span class="float-right">Rp {{ number_format($statistik['total_online'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Detail Harian -->
        <div class="bg-white p-6 rounded-lg shadow-md animate-fade-in">
            <h2 class="text-xl font-semibold mb-4 text-gray-700 flex items-center">
                <svg class="w-6 h-6 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
                Detail Penjualan Harian
            </h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hari</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Offline</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Online</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kontribusi</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tren</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($harian as $hari)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="font-medium">{{ \Carbon\Carbon::create($year, $month, $hari['hari'])->format('l') }}</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ $hari['tanggal'] }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div>
                                        <p class="font-medium">Rp {{ number_format($hari['offline']['penjualan'], 0, ',', '.') }}</p>
                                        <p class="text-xs text-gray-500">{{ $hari['offline']['transaksi'] }} transaksi</p>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div>
                                        <p class="font-medium text-green-600">Rp {{ number_format($hari['online']['penjualan'], 0, ',', '.') }}</p>
                                        <p class="text-xs text-gray-500">{{ $hari['online']['order'] }} order</p>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <p class="font-bold text-lg">Rp {{ number_format($hari['total']['penjualan'], 0, ',', '.') }}</p>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @php
                                        $kontribusi = $statistik['total_gabungan'] > 0 ? 
                                            ($hari['total']['penjualan'] / $statistik['total_gabungan'] * 100) : 0;
                                    @endphp
                                    <div class="flex items-center">
                                        <div class="w-20 bg-gray-200 rounded-full h-2 mr-2">
                                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $kontribusi }}%"></div>
                                        </div>
                                        <span class="text-sm">{{ number_format($kontribusi, 1) }}%</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @php
                                        $sebelumnya = isset($harian[$hari['hari'] - 1]) ? $harian[$hari['hari'] - 1]['total']['penjualan'] : 0;
                                        $trend = $sebelumnya > 0 ? (($hari['total']['penjualan'] - $sebelumnya) / $sebelumnya * 100) : 0;
                                    @endphp
                                    @if($trend > 0)
                                        <span class="text-green-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                            </svg>
                                            {{ number_format($trend, 1) }}%
                                        </span>
                                    @elseif($trend < 0)
                                        <span class="text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                            </svg>
                                            {{ number_format(abs($trend), 1) }}%
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Data dari controller
        const chartData = @json($chartData);
        
        // Chart Utama
        const ctxMain = document.getElementById('mainChart').getContext('2d');
        new Chart(ctxMain, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'Offline',
                        data: chartData.offline,
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    },
                    {
                        label: 'Online',
                        data: chartData.online,
                        backgroundColor: 'rgba(16, 185, 129, 0.7)',
                        borderColor: 'rgb(16, 185, 129)',
                        borderWidth: 1
                    },
                    {
                        label: 'Total',
                        data: chartData.total,
                        type: 'line',
                        borderColor: 'rgb(139, 92, 246)',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgb(139, 92, 246)',
                        pointBorderColor: 'white',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.3,
                        yAxisID: 'y'
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                return label;
                            }
                        }
                    },
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Penjualan (Rp)'
                        },
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // Chart Tipe Pelanggan Online (Hanya Anggota & Karyawan)
const ctxCustomerOnline = document.getElementById('customerOnlineChart').getContext('2d');
new Chart(ctxCustomerOnline, {
    type: 'bar',
    data: {
        labels: ['Anggota', 'Karyawan'],
        datasets: [
            {
                label: 'Total Penjualan',
                data: [
                    {{ $perbandinganTipeOnline['tipe_pelanggan']['anggota']['penjualan'] ?? 0 }},
                    {{ $perbandinganTipeOnline['tipe_pelanggan']['karyawan']['penjualan'] ?? 0 }}
                ],
                backgroundColor: ['#8B5CF6', '#F59E0B'],
                borderColor: ['#7C3AED', '#D97706'],
                borderWidth: 1,
                yAxisID: 'y'
            },
            {
                label: 'Jumlah Order',
                data: [
                    {{ $perbandinganTipeOnline['tipe_pelanggan']['anggota']['order'] ?? 0 }},
                    {{ $perbandinganTipeOnline['tipe_pelanggan']['karyawan']['order'] ?? 0 }}
                ],
                type: 'line',
                borderColor: '#EF4444',
                backgroundColor: 'transparent',
                borderWidth: 2,
                pointBackgroundColor: '#EF4444',
                pointBorderColor: 'white',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                tension: 0.3,
                yAxisID: 'y1'
            }
        ]
    },
    options: {
        responsive: true,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.label || '';
                        let value = context.raw;
                        if (context.dataset.label === 'Total Penjualan') {
                            return label + ': Rp ' + value.toLocaleString('id-ID');
                        }
                        return label + ': ' + value.toLocaleString('id-ID') + ' order';
                    }
                }
            }
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: {
                    display: true,
                    text: 'Penjualan (Rp)'
                },
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: {
                    display: true,
                    text: 'Jumlah Order'
                },
                grid: {
                    drawOnChartArea: false,
                },
                ticks: {
                    stepSize: 1,
                    callback: function(value) {
                        return value + ' order';
                    }
                }
            }
        }
    }
});
        

        // Chart Perbandingan
        const ctxComparison = document.getElementById('comparisonChart').getContext('2d');
        new Chart(ctxComparison, {
            type: 'doughnut',
            data: {
                labels: ['Offline', 'Online'],
                datasets: [{
                    data: [{{ $statistik['total_offline'] }}, {{ $statistik['total_online'] }}],
                    backgroundColor: ['#3B82F6', '#10B981'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw;
                                let total = {{ $statistik['total_gabungan'] }};
                                let percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return label + ': Rp ' + value.toLocaleString('id-ID') + ' (' + percentage + '%)';
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });

        // Filter function
        function filterData() {
            const month = document.getElementById('monthSelect').value;
            const year = document.getElementById('yearSelect').value;
            
            let url = new URL(window.location.href);
            url.searchParams.set('month', month);
            url.searchParams.set('year', year);
            
            window.location.href = url.toString();
        }

        // Auto refresh every 10 minutes
        setInterval(() => {
            window.location.reload();
        }, 600000);
    </script>
</body>
</html>