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
                <a href="/dashboard" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard Utama
                </a>
                <a href="/dashboard-vendor" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Dashboard Vendor
                </a>
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
                Heatmap Penjualan
            </h3>
            <div class="grid grid-cols-7 gap-1">
                @foreach(['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day)
                    <div class="text-center text-xs font-medium text-gray-600 py-1">{{ $day }}</div>
                @endforeach
                
                @foreach($heatmap as $week)
                    @foreach($week as $day)
                        @php
                            $bgColor = '#f3f4f6'; // Default gray
                            $title = '';
                            
                            if ($day) {
                                $avgPerHari = $statistik['total_gabungan'] / $penjualanBulanan['days_in_month'];
                                $ratio = $avgPerHari > 0 ? $day['total'] / ($avgPerHari * 2) : 0;
                                $ratio = min(1, max(0, $ratio));
                                
                                // Tentukan warna berdasarkan ratio
                                if ($ratio <= 0.2) {
                                    $bgColor = '#c7d2fe'; // Light indigo
                                } elseif ($ratio <= 0.4) {
                                    $bgColor = '#a5b4fc';
                                } elseif ($ratio <= 0.6) {
                                    $bgColor = '#818cf8';
                                } elseif ($ratio <= 0.8) {
                                    $bgColor = '#6366f1';
                                } else {
                                    $bgColor = '#4f46e5';
                                }
                                
                                $title = $day['tanggal'] . ' - Offline: Rp ' . number_format($day['offline'], 0, ',', '.') . 
                                        ', Online: Rp ' . number_format($day['online'], 0, ',', '.');
                            }
                        @endphp
                        <div class="aspect-square rounded-lg flex items-center justify-center text-center p-1"
                            style="background-color: {{ $bgColor }}"
                            @if($day) title="{{ $title }}" @endif>
                            @if($day)
                                <div>
                                    <span class="text-xs font-bold text-white">{{ $day['hari'] }}</span>
                                    <span class="text-[10px] block text-white">Rp {{ number_format($day['total'] / 1000, 0) }}k</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endforeach
            </div>
            <div class="mt-4 flex justify-between text-xs text-gray-600">
                <span>Rendah</span>
                <div class="flex-1 mx-4 h-2 rounded-full bg-gradient-to-r from-indigo-100 to-indigo-600"></div>
                <span>Tinggi</span>
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
                                        $sebelumnya = $harian[$hari['hari'] - 1]['total']['penjualan'] ?? 0;
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

    @php
    function getHeatmapColor($value, $avg)
    {
        if ($value == 0) return '#f3f4f6';
        $ratio = $value / ($avg * 2);
        $ratio = min(1, max(0, $ratio));
        $colors = [
            [0.2, '#c7d2fe'], // Light indigo
            [0.4, '#a5b4fc'],
            [0.6, '#818cf8'],
            [0.8, '#6366f1'],
            [1.0, '#4f46e5']
        ];
        
        foreach ($colors as $color) {
            if ($ratio <= $color[0]) {
                return $color[1];
            }
        }
        return '#4f46e5';
    }
    @endphp

    <script>
        // Data dari controller
        const chartData = @json($chartData);
        const perbandingan = @json($perbandingan);
        
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

        // Chart Perbandingan
        const ctxComparison = document.getElementById('comparisonChart').getContext('2d');
        new Chart(ctxComparison, {
            type: 'doughnut',
            data: {
                labels: perbandingan.labels,
                datasets: [{
                    data: [perbandingan.offline, perbandingan.online],
                    backgroundColor: perbandingan.colors,
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
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = ((value / total) * 100).toFixed(1);
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