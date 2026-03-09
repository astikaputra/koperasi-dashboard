<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LabaRugiService
{
    /**
     * Mendapatkan data laba rugi kotor harian
     */
    public function getLabaRugiHarian($date = null)
    {
        $date = $date ?? now()->toDateString();
        
        // Data penjualan offline per hari
        $offlineSales = DB::table('pj_penjualan_master')
            ->where('tgl', $date)
            ->where('isPosting', 0)
            ->selectRaw('
                COUNT(*) as jumlah_transaksi,
                SUM(grand_total) as total_penjualan
            ')
            ->first();
        
        // Data penjualan online per hari
        $onlineSales = DB::table('tbl_order')
            ->where('tanggal', $date)
            ->where('order_online', 'Y')
            ->selectRaw('
                COUNT(*) as jumlah_order,
                SUM(total_order) as total_penjualan
            ')
            ->first();
        
        // Hitung HPP (Harga Pokok Penjualan) dari detail penjualan offline
        $offlineHPP = DB::table('pj_penjualan_detail as detail')
            ->join('pj_penjualan_master as master', 'detail.id_penjualan_m', '=', 'master.id_penjualan_m')
            ->join('tbl_produk as produk', 'detail.id_barang', '=', 'produk.id_produk')
            ->where('master.tgl', $date)
            ->where('master.isPosting', 0)
            ->selectRaw('SUM(detail.jumlah_beli * CAST(produk.harga_beli AS DECIMAL(10,2))) as total_hpp')
            ->first();
        
        // Hitung HPP dari detail order online
        $onlineHPP = DB::table('tbl_detail_order as detail')
            ->join('tbl_order as order', 'detail.order_id', '=', 'order.id')
            ->join('tbl_produk as produk', 'detail.produk', '=', 'produk.id_produk')
            ->where('order.tanggal', $date)
            ->where('order.order_online', 'Y')
            ->selectRaw('SUM(detail.qty * CAST(produk.harga_beli AS DECIMAL(10,2))) as total_hpp')
            ->first();
        
        $totalPenjualan = ($offlineSales->total_penjualan ?? 0) + ($onlineSales->total_penjualan ?? 0);
        $totalHPP = ($offlineHPP->total_hpp ?? 0) + ($onlineHPP->total_hpp ?? 0);
        $labaKotor = $totalPenjualan - $totalHPP;
        $margin = $totalPenjualan > 0 ? ($labaKotor / $totalPenjualan) * 100 : 0;
        
        return [
            'date' => $date,
            'tanggal' => Carbon::parse($date)->format('d M Y'),
            'penjualan' => [
                'offline' => [
                    'transaksi' => $offlineSales->jumlah_transaksi ?? 0,
                    'nominal' => $offlineSales->total_penjualan ?? 0,
                ],
                'online' => [
                    'order' => $onlineSales->jumlah_order ?? 0,
                    'nominal' => $onlineSales->total_penjualan ?? 0,
                ],
                'total' => $totalPenjualan,
            ],
            'hpp' => [
                'offline' => $offlineHPP->total_hpp ?? 0,
                'online' => $onlineHPP->total_hpp ?? 0,
                'total' => $totalHPP,
            ],
            'laba_kotor' => $labaKotor,
            'margin' => round($margin, 2),
        ];
    }

    /**
     * Mendapatkan data laba rugi kotor bulanan
     */
    public function getLabaRugiBulanan($year = null, $month = null)
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;
        
        // Data penjualan offline bulanan
        $offlineSales = DB::table('pj_penjualan_master')
            ->whereYear('tgl', $year)
            ->whereMonth('tgl', $month)
            ->where('isPosting', 0)
            ->selectRaw('
                COUNT(*) as jumlah_transaksi,
                SUM(grand_total) as total_penjualan
            ')
            ->first();
        
        // Data penjualan online bulanan
        $onlineSales = DB::table('tbl_order')
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->where('order_online', 'Y')
            ->selectRaw('
                COUNT(*) as jumlah_order,
                SUM(total_order) as total_penjualan
            ')
            ->first();
        
        // Hitung HPP dari detail penjualan offline bulanan
        $offlineHPP = DB::table('pj_penjualan_detail as detail')
            ->join('pj_penjualan_master as master', 'detail.id_penjualan_m', '=', 'master.id_penjualan_m')
            ->join('tbl_produk as produk', 'detail.id_barang', '=', 'produk.id_produk')
            ->whereYear('master.tgl', $year)
            ->whereMonth('master.tgl', $month)
            ->where('master.isPosting', 0)
            ->selectRaw('SUM(detail.jumlah_beli * CAST(produk.harga_beli AS DECIMAL(10,2))) as total_hpp')
            ->first();
        
        // Hitung HPP dari detail order online bulanan
        $onlineHPP = DB::table('tbl_detail_order as detail')
            ->join('tbl_order as order', 'detail.order_id', '=', 'order.id')
            ->join('tbl_produk as produk', 'detail.produk', '=', 'produk.id_produk')
            ->whereYear('order.tanggal', $year)
            ->whereMonth('order.tanggal', $month)
            ->where('order.order_online', 'Y')
            ->selectRaw('SUM(detail.qty * CAST(produk.harga_beli AS DECIMAL(10,2))) as total_hpp')
            ->first();
        
        $totalPenjualan = ($offlineSales->total_penjualan ?? 0) + ($onlineSales->total_penjualan ?? 0);
        $totalHPP = ($offlineHPP->total_hpp ?? 0) + ($onlineHPP->total_hpp ?? 0);
        $labaKotor = $totalPenjualan - $totalHPP;
        $margin = $totalPenjualan > 0 ? ($labaKotor / $totalPenjualan) * 100 : 0;
        
        // Data harian untuk chart
        $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;
        $harian = [];
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($year, $month, $day)->format('Y-m-d');
            $harian[$day] = $this->getLabaRugiHarian($date);
        }
        
        return [
            'period' => "$year-$month",
            'nama_bulan' => Carbon::create($year, $month, 1)->format('F Y'),
            'days_in_month' => $daysInMonth,
            'penjualan' => [
                'offline' => [
                    'transaksi' => $offlineSales->jumlah_transaksi ?? 0,
                    'nominal' => $offlineSales->total_penjualan ?? 0,
                ],
                'online' => [
                    'order' => $onlineSales->jumlah_order ?? 0,
                    'nominal' => $onlineSales->total_penjualan ?? 0,
                ],
                'total' => $totalPenjualan,
            ],
            'hpp' => [
                'offline' => $offlineHPP->total_hpp ?? 0,
                'online' => $onlineHPP->total_hpp ?? 0,
                'total' => $totalHPP,
            ],
            'laba_kotor' => $labaKotor,
            'margin' => round($margin, 2),
            'harian' => $harian,
        ];
    }

    /**
     * Mendapatkan data laba rugi per produk
     */
    public function getLabaRugiPerProduk($year = null, $month = null, $limit = 10)
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;
        
        // Produk offline
        $offlineProducts = DB::table('pj_penjualan_detail as detail')
            ->join('pj_penjualan_master as master', 'detail.id_penjualan_m', '=', 'master.id_penjualan_m')
            ->join('tbl_produk as produk', 'detail.id_barang', '=', 'produk.id_produk')
            ->leftJoin('tbl_kategori as kategori', 'produk.kategori', '=', 'kategori.id')
            ->whereYear('master.tgl', $year)
            ->whereMonth('master.tgl', $month)
            ->where('master.isPosting', 0)
            ->selectRaw('
                produk.id_produk,
                produk.nama_produk,
                kategori.nama_kategori,
                SUM(detail.jumlah_beli) as total_qty,
                SUM(detail.jumlah_beli * detail.harga_satuan) as total_penjualan,
                SUM(detail.jumlah_beli * CAST(produk.harga_beli AS DECIMAL(10,2))) as total_hpp,
                SUM(detail.jumlah_beli * (detail.harga_satuan - CAST(produk.harga_beli AS DECIMAL(10,2)))) as laba_kotor
            ')
            ->groupBy('produk.id_produk', 'produk.nama_produk', 'kategori.nama_kategori')
            ->orderByDesc('laba_kotor')
            ->limit($limit)
            ->get();
        
        // Produk online
        $onlineProducts = DB::table('tbl_detail_order as detail')
            ->join('tbl_order as order', 'detail.order_id', '=', 'order.id')
            ->join('tbl_produk as produk', 'detail.produk', '=', 'produk.id_produk')
            ->leftJoin('tbl_kategori as kategori', 'produk.kategori', '=', 'kategori.id')
            ->whereYear('order.tanggal', $year)
            ->whereMonth('order.tanggal', $month)
            ->where('order.order_online', 'Y')
            ->selectRaw('
                produk.id_produk,
                produk.nama_produk,
                kategori.nama_kategori,
                SUM(detail.qty) as total_qty,
                SUM(detail.qty * CAST(detail.harga AS DECIMAL(10,2))) as total_penjualan,
                SUM(detail.qty * CAST(produk.harga_beli AS DECIMAL(10,2))) as total_hpp,
                SUM(detail.qty * (CAST(detail.harga AS DECIMAL(10,2)) - CAST(produk.harga_beli AS DECIMAL(10,2)))) as laba_kotor
            ')
            ->groupBy('produk.id_produk', 'produk.nama_produk', 'kategori.nama_kategori')
            ->orderByDesc('laba_kotor')
            ->limit($limit)
            ->get();
        
        return [
            'offline' => $offlineProducts,
            'online' => $onlineProducts,
        ];
    }

    /**
     * Mendapatkan data chart laba rugi harian
     */
    public function getChartData($year = null, $month = null)
    {
        $data = $this->getLabaRugiBulanan($year, $month);
        
        $chartData = [
            'labels' => [],
            'penjualan' => [],
            'hpp' => [],
            'laba' => [],
            'margin' => []
        ];
        
        foreach ($data['harian'] as $day => $item) {
            $chartData['labels'][] = 'Tgl ' . $day;
            $chartData['penjualan'][] = $item['penjualan']['total'];
            $chartData['hpp'][] = $item['hpp']['total'];
            $chartData['laba'][] = $item['laba_kotor'];
            $chartData['margin'][] = $item['margin'];
        }
        
        return $chartData;
    }

    /**
     * Mendapatkan ringkasan laba rugi
     */
    public function getRingkasan($year = null, $month = null)
    {
        $data = $this->getLabaRugiBulanan($year, $month);
        
        $totalPenjualan = $data['penjualan']['total'];
        $totalHPP = $data['hpp']['total'];
        $labaKotor = $data['laba_kotor'];
        $margin = $data['margin'];
        
        // Hitung rata-rata per hari
        $avgPenjualan = $data['days_in_month'] > 0 ? $totalPenjualan / $data['days_in_month'] : 0;
        $avgLaba = $data['days_in_month'] > 0 ? $labaKotor / $data['days_in_month'] : 0;
        
        // Cari hari terbaik
        $bestDay = null;
        $bestLaba = 0;
        foreach ($data['harian'] as $day => $item) {
            if ($item['laba_kotor'] > $bestLaba) {
                $bestLaba = $item['laba_kotor'];
                $bestDay = $day;
            }
        }
        
        return [
            'total_penjualan' => $totalPenjualan,
            'total_hpp' => $totalHPP,
            'laba_kotor' => $labaKotor,
            'margin' => $margin,
            'avg_penjualan' => $avgPenjualan,
            'avg_laba' => $avgLaba,
            'best_day' => $bestDay ? [
                'hari' => $bestDay,
                'tanggal' => $data['harian'][$bestDay]['tanggal'],
                'laba' => $bestLaba,
            ] : null,
        ];
    }
}