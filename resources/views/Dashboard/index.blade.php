<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penjualan Terpadu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
        <!-- Header -->
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8 animate-fade-in">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Dashboard Penjualan Harian
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
        <!-- Debug Info Section -->
        <!-- @if(isset($offlineHarian['debug']) || isset($offlineBulanan['debug']))
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <h3 class="text-lg font-semibold text-yellow-800 mb-2">⚠️ Debug Information</h3>
            
            @if(isset($offlineHarian['debug']))
            <div class="mb-3">
                <h4 class="font-medium text-yellow-700">Offline Harian Debug:</h4>
                <ul class="text-sm text-yellow-600">
                    <li>Query Date: {{ $offlineHarian['debug']['query_date'] ?? 'N/A' }}</li>
                    <li>Records with isPosting=1: {{ $offlineHarian['debug']['records_with_posting'] ?? 0 }}</li>
                </ul>
            </div>
            @endif
            
            <div class="mt-3">
                <a href="/debug-db" class="text-blue-600 hover:text-blue-800 text-sm">
                    → Check Database Connection
                </a>
                @if(isset($offlineHarian['debug']['records_with_posting']) && $offlineHarian['debug']['records_with_posting'] == 0)
                <a href="/create-test-data" class="ml-4 text-blue-600 hover:text-blue-800 text-sm">
                    → Create Test Data
                </a>
                @endif
            </div>
        </div>
        @endif -->

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Penjualan Hari Ini -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-lg shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Total Penjualan</h3>
                        <p class="text-3xl font-bold mt-2">Rp {{ number_format($totalHarian['total_gabungan']['penjualan'], 0, ',', '.') }}</p>
                    </div>
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="mt-4 text-sm">
                    <div class="flex justify-between">
                        <span>Offline:</span>
                        <span>Rp {{ number_format($offlineHarian['total_penjualan'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Online:</span>
                        <span>Rp {{ number_format($onlineHarian['total_penjualan'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Total Transaksi -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-lg shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Total Transaksi</h3>
                        <p class="text-3xl font-bold mt-2">{{ number_format($totalHarian['total_gabungan']['transaksi'], 0, ',', '.') }}</p>
                    </div>
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div class="mt-4 text-sm">
                    <div class="flex justify-between">
                        <span>Offline:</span>
                        <span>{{ number_format($offlineHarian['total_transaksi'], 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Online:</span>
                        <span>{{ number_format($onlineHarian['total_order'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Rata-rata per Transaksi -->
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-lg shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Rata-rata/Transaksi</h3>
                        <p class="text-3xl font-bold mt-2">
                            @php
                                $avgTotal = $totalHarian['total_gabungan']['transaksi'] > 0 ? 
                                    $totalHarian['total_gabungan']['penjualan'] / $totalHarian['total_gabungan']['transaksi'] : 0;
                            @endphp
                            Rp {{ number_format($avgTotal, 0, ',', '.') }}
                        </p>
                    </div>
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="mt-4 text-sm">
                    @php
                        $avgOffline = $offlineHarian['total_transaksi'] > 0 ? 
                            $offlineHarian['total_penjualan'] / $offlineHarian['total_transaksi'] : 0;
                        $avgOnline = $onlineHarian['total_order'] > 0 ? 
                            $onlineHarian['total_penjualan'] / $onlineHarian['total_order'] : 0;
                    @endphp
                    <div class="flex justify-between">
                        <span>Offline:</span>
                        <span>Rp {{ number_format($avgOffline, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Online:</span>
                        <span>Rp {{ number_format($avgOnline, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Status Order Online -->
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white p-6 rounded-lg shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">Order Online</h3>
                        <p class="text-3xl font-bold mt-2">{{ number_format($onlineHarian['total_order'], 0, ',', '.') }}</p>
                    </div>
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="mt-4 text-sm">
                    @php
                        $statuses = ['CLOSE' => 'Selesai', 'OPEN' => 'Proses', 'CANCEL' => 'Batal'];
                    @endphp
                    @foreach($statuses as $key => $label)
                        @if(isset($onlineHarian['by_status'][$key]))
                        <div class="flex justify-between">
                            <span>{{ $label }}:</span>
                            <span>{{ $onlineHarian['by_status'][$key]['order'] }}</span>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-xl font-semibold mb-4">Filter Data</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Harian</label>
                    <input type="date" id="filterDate" value="{{ $date }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                    <input type="month" id="filterMonth" value="{{ $year }}-{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-end">
                    <button onclick="filterData()" 
                            class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                        Filter Data
                    </button>
                </div>
            </div>
        </div>

        <!-- Dua Kolom: Offline dan Online -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Kolom Kiri: Penjualan Offline -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4 text-blue-600">
                    <svg class="w-6 h-6 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Penjualan Offline
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-blue-800">Transaksi</h3>
                        <p class="text-3xl font-bold text-blue-600">{{ number_format($offlineHarian['total_transaksi'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-green-800">Total</h3>
                        <p class="text-3xl font-bold text-green-600">Rp {{ number_format($offlineHarian['total_penjualan'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-purple-800">Rata-rata</h3>
                        <p class="text-3xl font-bold text-purple-600">
                            Rp {{ $offlineHarian['total_transaksi'] > 0 ? 
                                number_format($offlineHarian['total_penjualan'] / $offlineHarian['total_transaksi'], 0, ',', '.') : 0 }}
                        </p>
                    </div>
                <!-- Top Products Offline -->
            @if($topProductsOffline->isNotEmpty())
            <h3 class="text-lg font-semibold mb-3">Produk Terlaris Offline</h3>
            <div class="space-y-2 mb-6">
                @foreach($topProductsOffline as $index => $product)
                <div class="flex items-center justify-between bg-gradient-to-r from-blue-50 to-blue-100 p-3 rounded-lg border border-blue-200">
                    <div class="flex items-center">
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold mr-3 px-2.5 py-0.5 rounded-full">
                            {{ $index + 1 }}
                        </span>
                        <div>
                            <p class="font-medium text-gray-800">{{ $product->nama_produk }}</p>
                            <p class="text-xs text-gray-600">{{ $product->nama_kategori }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-blue-700">Rp {{ number_format($product->total_value, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-600">{{ $product->total_qty }} item terjual</p>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
                
                </div>

                <!-- Detail per Tipe Pelanggan -->
                <h3 class="text-lg font-semibold mb-3">Tipe Pelanggan</h3>
                <div class="overflow-x-auto mb-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Transaksi</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">%</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach(['umum', 'anggota', 'karyawan'] as $tipe)
                                @php
                                    $data = $offlineHarian['by_tipe'][$tipe] ?? ['transaksi' => 0, 'total' => 0];
                                    $percentage = $offlineHarian['total_penjualan'] > 0 ? 
                                        ($data['total'] / $offlineHarian['total_penjualan'] * 100) : 0;
                                @endphp
                                <tr>
                                    <td class="px-4 py-2 font-medium">{{ ucfirst($tipe) }}</td>
                                    <td class="px-4 py-2">{{ number_format($data['transaksi'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-2">Rp {{ number_format($data['total'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-2">
                                        <div class="flex items-center">
                                            <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                            </div>
                                            <span class="text-sm">{{ number_format($percentage, 1) }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Metode Pembayaran -->
                @if($metodeBayar->isNotEmpty())
                <h3 class="text-lg font-semibold mb-3">Metode Pembayaran</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    @foreach($metodeBayar as $item)
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <h4 class="font-medium text-gray-700 text-sm">{{ ucfirst($item->metode_bayar) }}</h4>
                            <div class="mt-1">
                                <p class="text-lg font-bold text-gray-800">Rp {{ number_format($item->total, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-600">{{ $item->jumlah }} transaksi</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif
            </div>
            <!-- Kolom Kanan: Penjualan Online -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4 text-green-600">
                    <svg class="w-6 h-6 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                    </svg>
                    Penjualan Online
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-blue-800">Order</h3>
                        <p class="text-3xl font-bold text-blue-600">{{ number_format($onlineHarian['total_order'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-green-800">Total</h3>
                        <p class="text-3xl font-bold text-green-600">Rp {{ number_format($onlineHarian['total_penjualan'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-purple-800">Rata-rata</h3>
                        <p class="text-3xl font-bold text-purple-600">
                            Rp {{ $onlineHarian['total_order'] > 0 ? 
                                number_format($onlineHarian['total_penjualan'] / $onlineHarian['total_order'], 0, ',', '.') : 0 }}
                        </p>
                    </div>
                </div>

                <!-- Status Order -->
                <h3 class="text-lg font-semibold mb-3">Status Order</h3>
                <div class="overflow-x-auto mb-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">%</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                $statusList = ['CLOSE' => 'Selesai', 'OPEN' => 'Dalam Proses', 'CANCEL' => 'Dibatalkan'];
                            @endphp
                            @foreach($statusList as $key => $label)
                                @php
                                    $data = $onlineHarian['by_status'][$key] ?? ['order' => 0, 'total' => 0];
                                    $percentage = $onlineHarian['total_penjualan'] > 0 ? 
                                        ($data['total'] / $onlineHarian['total_penjualan'] * 100) : 0;
                                @endphp
                                <tr>
                                    <td class="px-4 py-2 font-medium">
                                        @if($key == 'CLOSE')
                                            <span class="text-green-600">{{ $label }}</span>
                                        @elseif($key == 'OPEN')
                                            <span class="text-yellow-600">{{ $label }}</span>
                                        @else
                                            <span class="text-red-600">{{ $label }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">{{ number_format($data['order'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-2">Rp {{ number_format($data['total'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-2">
                                        <div class="flex items-center">
                                            <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                                @if($key == 'CLOSE')
                                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                                @elseif($key == 'OPEN')
                                                    <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                                @else
                                                    <div class="bg-red-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                                @endif
                                            </div>
                                            <span class="text-sm">{{ number_format($percentage, 1) }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Chart Gabungan -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-xl font-semibold mb-4">Grafik Penjualan Harian per Jam</h2>
            <canvas id="chartHarian" height="80"></canvas>
        </div>

        <!-- Statistik Bulanan -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Bulanan Offline -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4 text-blue-600">Penjualan Bulanan Offline</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-blue-800">Transaksi</h3>
                        <p class="text-3xl font-bold text-blue-600">{{ number_format($offlineBulanan['total_transaksi'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-green-800">Total</h3>
                        <p class="text-3xl font-bold text-green-600">Rp {{ number_format($offlineBulanan['total_penjualan'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-purple-800">Rata-rata/Hari</h3>
                        <p class="text-3xl font-bold text-purple-600">
                            Rp {{ number_format($offlineBulanan['total_penjualan'] / date('t', strtotime("$year-$month-01")), 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <!-- Detail per Tipe Pelanggan Bulanan -->
                <h3 class="text-lg font-semibold mb-3">Detail per Tipe Pelanggan</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tipe</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Transaksi</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">%</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach(['umum', 'anggota', 'karyawan'] as $tipe)
                                @php
                                    $data = $offlineBulanan['by_tipe'][$tipe] ?? ['transaksi' => 0, 'total' => 0];
                                    $percentage = $offlineBulanan['total_penjualan'] > 0 ? 
                                        ($data['total'] / $offlineBulanan['total_penjualan'] * 100) : 0;
                                @endphp
                                <tr>
                                    <td class="px-4 py-2 font-medium">{{ ucfirst($tipe) }}</td>
                                    <td class="px-4 py-2">{{ number_format($data['transaksi'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-2">Rp {{ number_format($data['total'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-2">
                                        <div class="flex items-center">
                                            <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                            </div>
                                            <span class="text-sm">{{ number_format($percentage, 1) }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Bulanan Online -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4 text-green-600">Penjualan Bulanan Online</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-blue-800">Order</h3>
                        <p class="text-3xl font-bold text-blue-600">{{ number_format($onlineBulanan['total_order'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-green-800">Total</h3>
                        <p class="text-3xl font-bold text-green-600">Rp {{ number_format($onlineBulanan['total_penjualan'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-purple-800">Rata-rata/Hari</h3>
                        <p class="text-3xl font-bold text-purple-600">
                            Rp {{ number_format($onlineBulanan['total_penjualan'] / date('t', strtotime("$year-$month-01")), 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <!-- Detail Status Order Bulanan -->
                <h3 class="text-lg font-semibold mb-3">Status Order Bulanan</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">%</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($statusList as $key => $label)
                                @php
                                    $data = $onlineBulanan['by_status'][$key] ?? ['order' => 0, 'total' => 0];
                                    $percentage = $onlineBulanan['total_penjualan'] > 0 ? 
                                        ($data['total'] / $onlineBulanan['total_penjualan'] * 100) : 0;
                                @endphp
                                <tr>
                                    <td class="px-4 py-2 font-medium">
                                        @if($key == 'CLOSE')
                                            <span class="text-green-600">{{ $label }}</span>
                                        @elseif($key == 'OPEN')
                                            <span class="text-yellow-600">{{ $label }}</span>
                                        @else
                                            <span class="text-red-600">{{ $label }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">{{ number_format($data['order'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-2">Rp {{ number_format($data['total'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-2">
                                        <div class="flex items-center">
                                            <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                                @if($key == 'CLOSE')
                                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                                @elseif($key == 'OPEN')
                                                    <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                                @else
                                                    <div class="bg-red-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                                @endif
                                            </div>
                                            <span class="text-sm">{{ number_format($percentage, 1) }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
   

    <!-- Section Khusus Produk Terlaris -->
<div class="bg-white p-6 rounded-lg shadow-md mb-8">
    <h2 class="text-xl font-semibold mb-4 text-purple-600">
        <svg class="w-6 h-6 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
        </svg>
        Produk & Kategori Terlaris
    </h2>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Produk Terlaris -->
        <div>
            <h3 class="text-lg font-semibold mb-3">Top 5 Produk Terlaris</h3>
            @if($topProductsGabungan->isNotEmpty())
            <div class="space-y-3">
                @foreach($topProductsGabungan->take(5) as $index => $product)
                <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50">
                    <div class="flex items-center flex-1">
                        <div class="text-center mr-4">
                            <div class="w-8 h-8 flex items-center justify-center rounded-full 
                                @if($index == 0) bg-yellow-100 text-yellow-800
                                @elseif($index == 1) bg-gray-100 text-gray-800
                                @elseif($index == 2) bg-orange-100 text-orange-800
                                @else bg-blue-100 text-blue-800
                                @endif">
                                <span class="font-bold">{{ $index + 1 }}</span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-800 truncate">{{ $product->nama_produk }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-600">{{ $product->nama_kategori }}</span>
                                <span class="text-xs px-2 py-1 rounded-full 
                                    @if($product->sumber == 'online') bg-green-100 text-green-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ $product->sumber == 'online' ? 'Online' : 'Offline' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right ml-4">
                        <p class="font-bold text-green-700">Rp {{ number_format($product->total_value, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-600">{{ $product->total_qty }} pcs</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="mt-2">Tidak ada data produk terlaris</p>
            </div>
            @endif
        </div>
        
        <!-- Kategori Terlaris -->
        <div>
            <h3 class="text-lg font-semibold mb-3">Top 5 Kategori Terlaris</h3>
            @if($topCategories->isNotEmpty())
            <div class="space-y-3">
                @foreach($topCategories->take(5) as $index => $category)
                <div class="bg-gradient-to-r from-purple-50 to-white p-4 rounded-lg border border-purple-100">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center">
                            <div class="w-8 h-8 flex items-center justify-center rounded-full bg-purple-100 text-purple-800 mr-3">
                                <span class="font-bold">{{ $index + 1 }}</span>
                            </div>
                            <h4 class="font-bold text-gray-800">{{ $category->nama_kategori }}</h4>
                        </div>
                        <span class="text-xs px-2 py-1 
                            @if($category->sumber == 'online') bg-green-100 text-green-800
                            @else bg-blue-100 text-blue-800 @endif rounded-full">
                            {{ $category->sumber == 'online' ? 'Online' : 'Offline' }}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-purple-700">{{ number_format($category->total_qty, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-600">Qty Terjual</p>
                        </div>
                        <div class="text-center">
                            <p class="text-xl font-bold text-green-700">Rp {{ number_format($category->total_value, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-600">Total Nilai</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <p class="mt-2">Tidak ada data kategori terlaris</p>
            </div>
            @endif
        </div>
    </div>
</div>
 </div>

    <script>
        // Prepare data for charts
        const chartHarianData = @json($chartHarian);
        
        // Chart Harian Gabungan
        const ctxHarian = document.getElementById('chartHarian').getContext('2d');
        const hours = Array.from({length: 24}, (_, i) => i);
        
        // Data Offline per jam
        const offlineData = hours.map(hour => {
            const data = chartHarianData.find(d => d.jam == hour && d.sumber === 'offline');
            return data ? parseFloat(data.total) : 0;
        });
        
        // Data Online per jam
        const onlineData = hours.map(hour => {
            const data = chartHarianData.find(d => d.jam == hour && d.sumber === 'online');
            return data ? parseFloat(data.total) : 0;
        });

        new Chart(ctxHarian, {
            type: 'line',
            data: {
                labels: hours.map(h => h + ':00'),
                datasets: [
                    {
                        label: 'Penjualan Offline',
                        data: offlineData,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Penjualan Online',
                        data: onlineData,
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Penjualan Harian per Jam (Offline vs Online)'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Total Penjualan (Rp)'
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

        // Auto refresh every 5 minutes
        setInterval(() => {
            window.location.reload();
        }, 300000);
    </script>
</body>
</html>