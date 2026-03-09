<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Laba Rugi Kotor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @keyframes slideIn {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .animate-slide-in {
            animation: slideIn 0.5s ease-out;
        }
        .profit-card {
            transition: all 0.3s ease;
        }
        .profit-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
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
                Dashboard Laba Rugi Kotor
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
        <div class="bg-white p-6 rounded-lg shadow-md mb-8 animate-slide-in">
            <h2 class="text-xl font-semibold mb-4 text-gray-700">Filter Periode</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Harian</label>
                    <input type="date" id="filterDate" value="{{ $date }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                    <input type="month" id="filterMonth" value="{{ $year }}-{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500">
                </div>
                <div class="flex items-end">
                    <button onclick="filterData()" 
                            class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition">
                        Tampilkan
                    </button>
                </div>
                <div class="flex items-end">
                    <a href="/debug-lab rugi" class="w-full bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition text-center">
                        Debug
                    </a>
                </div>
            </div>
        </div>

        <!-- Ringkasan Laba Rugi -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 animate-slide-in">
            <!-- Total Penjualan -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-lg shadow-lg profit-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Total Penjualan</p>
                        <p class="text-2xl font-bold mt-1">Rp {{ number_format($ringkasan['total_penjualan'], 0, ',', '.') }}</p>
                    </div>
                    <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="mt-2 text-sm">
                    <div class="flex justify-between">
                        <span>Offline:</span>
                        <span>Rp {{ number_format($bulanan['penjualan']['offline']['nominal'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Online:</span>
                        <span>Rp {{ number_format($bulanan['penjualan']['online']['nominal'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Total HPP -->
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white p-6 rounded-lg shadow-lg profit-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Total HPP</p>
                        <p class="text-2xl font-bold mt-1">Rp {{ number_format($ringkasan['total_hpp'], 0, ',', '.') }}</p>
                    </div>
                    <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div class="mt-2 text-sm">
                    <div class="flex justify-between">
                        <span>Offline:</span>
                        <span>Rp {{ number_format($bulanan['hpp']['offline'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Online:</span>
                        <span>Rp {{ number_format($bulanan['hpp']['online'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Laba Kotor -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded-lg shadow-lg profit-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Laba Kotor</p>
                        <p class="text-2xl font-bold mt-1">Rp {{ number_format($ringkasan['laba_kotor'], 0, ',', '.') }}</p>
                    </div>
                    <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="mt-2 text-sm">
                    <div class="flex justify-between">
                        <span>Margin:</span>
                        <span class="font-bold">{{ $ringkasan['margin'] }}%</span>
                    </div>
                </div>
            </div>

            <!-- Rata-rata Harian -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-6 rounded-lg shadow-lg profit-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Rata-rata per Hari</p>
                        <p class="text-2xl font-bold mt-1">Rp {{ number_format($ringkasan['avg_laba'], 0, ',', '.') }}</p>
                    </div>
                    <svg class="w-12 h-12 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="mt-2 text-sm">
                    @if($ringkasan['best_day'])
                    <div class="flex justify-between">
                        <span>Hari Terbaik:</span>
                        <span>{{ $ringkasan['best_day']['tanggal'] }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Grafik Laba Rugi -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8 animate-slide-in">
            <h2 class="text-xl font-semibold mb-4 text-gray-700 flex items-center">
                <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Grafik Laba Rugi Harian - {{ $bulanan['nama_bulan'] }}
            </h2>
            
            <canvas id="profitChart" height="100"></canvas>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-blue-800">Total Laba Bulan Ini</h3>
                    <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($ringkasan['laba_kotor'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-green-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-green-800">Margin Keuntungan</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $ringkasan['margin'] }}%</p>
                </div>
                <div class="bg-purple-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-purple-800">Rasio Laba/Penjualan</h3>
                    <p class="text-2xl font-bold text-purple-600">1 : {{ $ringkasan['total_penjualan'] > 0 ? round($ringkasan['total_penjualan'] / $ringkasan['laba_kotor'], 2) : 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Dua Kolom: Laba per Sumber dan Produk -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8 animate-slide-in">
            <!-- Laba per Sumber -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4 text-gray-700 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Laba per Sumber Penjualan
                </h2>
                
                <div class="space-y-4">
                    <!-- Offline -->
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-bold text-blue-800">Offline</h3>
                            <span class="text-sm bg-blue-200 text-blue-800 px-2 py-1 rounded-full">
                                {{ $bulanan['penjualan']['offline']['transaksi'] }} transaksi
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-blue-600">Penjualan</p>
                                <p class="text-xl font-bold text-blue-800">Rp {{ number_format($bulanan['penjualan']['offline']['nominal'], 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-blue-600">HPP</p>
                                <p class="text-xl font-bold text-blue-800">Rp {{ number_format($bulanan['hpp']['offline'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="mt-2 pt-2 border-t border-blue-200">
                            <div class="flex justify-between">
                                <span class="font-medium text-blue-800">Laba Kotor:</span>
                                <span class="font-bold text-green-600">Rp {{ number_format($bulanan['penjualan']['offline']['nominal'] - $bulanan['hpp']['offline'], 0, ',', '.') }}</span>
                            </div>
                            <div class="w-full bg-blue-200 rounded-full h-2 mt-2">
                                @php
                                    $marginOffline = $bulanan['penjualan']['offline']['nominal'] > 0 ? 
                                        (($bulanan['penjualan']['offline']['nominal'] - $bulanan['hpp']['offline']) / $bulanan['penjualan']['offline']['nominal'] * 100) : 0;
                                @endphp
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ $marginOffline }}%"></div>
                            </div>
                            <p class="text-xs text-right mt-1">Margin: {{ number_format($marginOffline, 2) }}%</p>
                        </div>
                    </div>
                    
                    <!-- Online -->
                    <div class="p-4 bg-green-50 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-bold text-green-800">Online</h3>
                            <span class="text-sm bg-green-200 text-green-800 px-2 py-1 rounded-full">
                                {{ $bulanan['penjualan']['online']['order'] }} order
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-green-600">Penjualan</p>
                                <p class="text-xl font-bold text-green-800">Rp {{ number_format($bulanan['penjualan']['online']['nominal'], 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-green-600">HPP</p>
                                <p class="text-xl font-bold text-green-800">Rp {{ number_format($bulanan['hpp']['online'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="mt-2 pt-2 border-t border-green-200">
                            <div class="flex justify-between">
                                <span class="font-medium text-green-800">Laba Kotor:</span>
                                <span class="font-bold text-green-600">Rp {{ number_format($bulanan['penjualan']['online']['nominal'] - $bulanan['hpp']['online'], 0, ',', '.') }}</span>
                            </div>
                            <div class="w-full bg-green-200 rounded-full h-2 mt-2">
                                @php
                                    $marginOnline = $bulanan['penjualan']['online']['nominal'] > 0 ? 
                                        (($bulanan['penjualan']['online']['nominal'] - $bulanan['hpp']['online']) / $bulanan['penjualan']['online']['nominal'] * 100) : 0;
                                @endphp
                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ $marginOnline }}%"></div>
                            </div>
                            <p class="text-xs text-right mt-1">Margin: {{ number_format($marginOnline, 2) }}%</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top 5 Produk dengan Laba Tertinggi -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4 text-gray-700 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                    Top 5 Produk dengan Laba Tertinggi
                </h2>
                
                <div class="space-y-4">
                    <h3 class="font-semibold text-blue-700 text-sm">Offline</h3>
                    @forelse($produk['offline'] as $index => $item)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center">
                                <span class="w-6 h-6 flex items-center justify-center rounded-full bg-blue-100 text-blue-800 text-xs font-bold mr-3">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $item->nama_produk }}</p>
                                    <p class="text-xs text-gray-500">{{ $item->nama_kategori ?? 'Tanpa Kategori' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-green-600">Rp {{ number_format($item->laba_kotor, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500">Margin: {{ $item->total_penjualan > 0 ? round(($item->laba_kotor / $item->total_penjualan) * 100, 1) : 0 }}%</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-4">Tidak ada data produk offline</p>
                    @endforelse
                    
                    <h3 class="font-semibold text-green-700 text-sm mt-4">Online</h3>
                    @forelse($produk['online'] as $index => $item)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center">
                                <span class="w-6 h-6 flex items-center justify-center rounded-full bg-green-100 text-green-800 text-xs font-bold mr-3">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $item->nama_produk }}</p>
                                    <p class="text-xs text-gray-500">{{ $item->nama_kategori ?? 'Tanpa Kategori' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-green-600">Rp {{ number_format($item->laba_kotor, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500">Margin: {{ $item->total_penjualan > 0 ? round(($item->laba_kotor / $item->total_penjualan) * 100, 1) : 0 }}%</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-4">Tidak ada data produk online</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Tabel Laba Rugi Harian -->
        <div class="bg-white p-6 rounded-lg shadow-md animate-slide-in">
            <h2 class="text-xl font-semibold mb-4 text-gray-700 flex items-center">
                <svg class="w-6 h-6 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
                Detail Laba Rugi Harian
            </h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hari</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penjualan</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">HPP</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Laba Kotor</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Margin</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($bulanan['harian'] as $day => $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ Carbon\Carbon::parse($item['date'])->format('l') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ $item['tanggal'] }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    Rp {{ number_format($item['penjualan']['total'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    Rp {{ number_format($item['hpp']['total'], 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="font-bold {{ $item['laba_kotor'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        Rp {{ number_format($item['laba_kotor'], 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $item['margin'] >= 20 ? 'bg-green-100 text-green-800' : ($item['margin'] >= 10 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $item['margin'] }}%
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Data chart
        const chartData = @json($chartData);
        
        // Chart Laba Rugi
        const ctx = document.getElementById('profitChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'Penjualan',
                        data: chartData.penjualan,
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        yAxisID: 'y'
                    },
                    {
                        label: 'HPP',
                        data: chartData.hpp,
                        borderColor: '#F59E0B',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Laba Kotor',
                        data: chartData.laba,
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
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
                                let value = context.raw;
                                return label + ': Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });

        // Filter function
        function filterData() {
            const date = document.getElementById('filterDate').value;
            const month = document.getElementById('filterMonth').value;
            
            let url = new URL(window.location.href);
            url.searchParams.set('date', date);
            
            if (month) {
                const [year, monthVal] = month.split('-');
                url.searchParams.set('year', year);
                url.searchParams.set('month', monthVal);
            }
            
            window.location.href = url.toString();
        }
    </script>
</body>
</html>