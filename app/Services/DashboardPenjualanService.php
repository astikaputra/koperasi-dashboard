<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

use App\Models\PenjualanMaster;
use App\Models\PenjualanDetail;
use App\Models\OrderOnline;
use App\Models\OrderDetail;

class DashboardPenjualanService
{
    // ==================== METHOD UNTUK PENJUALAN OFFLINE ====================
    
    public function getPenjualanOfflineHarian($date = null)
    {
        $date = $date ?? now()->toDateString();
        
        $data = DB::table('pj_penjualan_master')
            ->where('tgl', $date)
            ->where('isPosting', 0)
            ->selectRaw('tipe_pelanggan, COUNT(*) as jumlah_transaksi, SUM(grand_total) as total_penjualan')
            ->groupBy('tipe_pelanggan')
            ->get();

        $summary = [
            'date' => $date,
            'total_transaksi' => $data->sum('jumlah_transaksi'),
            'total_penjualan' => $data->sum('total_penjualan'),
            'by_tipe' => $data->mapWithKeys(function ($item) {
                return [$item->tipe_pelanggan => [
                    'transaksi' => $item->jumlah_transaksi,
                    'total' => $item->total_penjualan
                ]];
            })->toArray()
        ];

        return $summary;
    }

    public function getPenjualanOfflineBulanan($year = null, $month = null)
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;
        
        $data = DB::table('pj_penjualan_master')
            ->whereYear('tgl', $year)
            ->whereMonth('tgl', $month)
            ->where('isPosting', 0)
            ->selectRaw('tipe_pelanggan, COUNT(*) as jumlah_transaksi, SUM(grand_total) as total_penjualan')
            ->groupBy('tipe_pelanggan')
            ->get();

        $summary = [
            'period' => "$year-$month",
            'total_transaksi' => $data->sum('jumlah_transaksi'),
            'total_penjualan' => $data->sum('total_penjualan'),
            'by_tipe' => $data->mapWithKeys(function ($item) {
                return [$item->tipe_pelanggan => [
                    'transaksi' => $item->jumlah_transaksi,
                    'total' => $item->total_penjualan
                ]];
            })->toArray()
        ];

        return $summary;
    }

    // ==================== METHOD UNTUK PENJUALAN ONLINE ====================
    
    public function getPenjualanOnlineHarian($date = null)
    {
        $date = $date ?? now()->toDateString();
        
        $data = DB::table('tbl_order')
            ->where('tanggal', $date)
            ->where('order_online', 'Y')
            ->selectRaw('status, COUNT(*) as jumlah_order, SUM(total_order) as total_penjualan')
            ->groupBy('status')
            ->get();

        $summary = [
            'date' => $date,
            'total_order' => $data->sum('jumlah_order'),
            'total_penjualan' => $data->sum('total_penjualan'),
            'by_status' => $data->mapWithKeys(function ($item) {
                return [$item->status => [
                    'order' => $item->jumlah_order,
                    'total' => $item->total_penjualan
                ]];
            })->toArray()
        ];

        return $summary;
    }

    public function getPenjualanOnlineBulanan($year = null, $month = null)
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;
        
        $data = DB::table('tbl_order')
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->where('order_online', 'Y')
            ->selectRaw('status, COUNT(*) as jumlah_order, SUM(total_order) as total_penjualan')
            ->groupBy('status')
            ->get();

        $summary = [
            'period' => "$year-$month",
            'total_order' => $data->sum('jumlah_order'),
            'total_penjualan' => $data->sum('total_penjualan'),
            'by_status' => $data->mapWithKeys(function ($item) {
                return [$item->status => [
                    'order' => $item->jumlah_order,
                    'total' => $item->total_penjualan
                ]];
            })->toArray()
        ];

        return $summary;
    }

    // ==================== METHOD UNTUK GABUNGAN OFFLINE + ONLINE ====================
    
    public function getTotalPenjualanHarian($date = null)
    {
        $date = $date ?? now()->toDateString();
        
        // Data Offline
        $offline = $this->getPenjualanOfflineHarian($date);
        
        // Data Online
        $online = $this->getPenjualanOnlineHarian($date);
        
        // Gabungkan
        $summary = [
            'date' => $date,
            'offline' => $offline,
            'online' => $online,
            'total_gabungan' => [
                'transaksi' => $offline['total_transaksi'] + $online['total_order'],
                'penjualan' => $offline['total_penjualan'] + $online['total_penjualan']
            ]
        ];

        return $summary;
    }

    public function getTotalPenjualanBulanan($year = null, $month = null)
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;
        
        // Data Offline
        $offline = $this->getPenjualanOfflineBulanan($year, $month);
        
        // Data Online
        $online = $this->getPenjualanOnlineBulanan($year, $month);
        
        // Gabungkan
        $summary = [
            'period' => "$year-$month",
            'offline' => $offline,
            'online' => $online,
            'total_gabungan' => [
                'transaksi' => $offline['total_transaksi'] + $online['total_order'],
                'penjualan' => $offline['total_penjualan'] + $online['total_penjualan']
            ]
        ];

        return $summary;
    }

    // ==================== CHART DATA ====================
    
    public function getChartDataHarian($date = null)
    {
        $date = $date ?? now()->toDateString();
        
        // Data Offline per jam - FIXED: Tambahkan jam ke GROUP BY
        $offlineData = DB::table('pj_penjualan_master')
            ->where('tgl', $date)
            ->where('isPosting', 0)
            ->selectRaw("CAST(SUBSTRING(jam, 1, 2) AS UNSIGNED) as jam, 'offline' as sumber, SUM(grand_total) as total")
            ->groupBy(DB::raw("CAST(SUBSTRING(jam, 1, 2) AS UNSIGNED)"), DB::raw("'offline'"))
            ->orderBy('jam')
            ->get();
        
        // Data Online per jam - FIXED: Tambahkan jam ke GROUP BY
        $onlineData = DB::table('tbl_order')
            ->where('tanggal', $date)
            ->where('order_online', 'Y')
            ->selectRaw("CAST(SUBSTRING(jam, 1, 2) AS UNSIGNED) as jam, 'online' as sumber, SUM(total_order) as total")
            ->groupBy(DB::raw("CAST(SUBSTRING(jam, 1, 2) AS UNSIGNED)"), DB::raw("'online'"))
            ->orderBy('jam')
            ->get();
        
        // Gabungkan data
        $data = $offlineData->merge($onlineData);
        
        // Convert jam ke integer
        $data->transform(function ($item) {
            $item->jam = (int) $item->jam;
            $item->total = (float) $item->total;
            return $item;
        });

        return $data;
    }

    public function getChartDataBulanan($year = null, $month = null)
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;
        
        // Data Offline per hari - FIXED: Tambahkan hari ke GROUP BY
        $offlineData = DB::table('pj_penjualan_master')
            ->whereYear('tgl', $year)
            ->whereMonth('tgl', $month)
            ->where('isPosting', 0)
            ->selectRaw("DAY(tgl) as hari, 'offline' as sumber, SUM(grand_total) as total")
            ->groupBy(DB::raw("DAY(tgl)"), DB::raw("'offline'"))
            ->orderBy('hari')
            ->get();
        
        // Data Online per hari - FIXED: Tambahkan hari ke GROUP BY
        $onlineData = DB::table('tbl_order')
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->where('order_online', 'Y')
            ->selectRaw("DAY(tanggal) as hari, 'online' as sumber, SUM(total_order) as total")
            ->groupBy(DB::raw("DAY(tanggal)"), DB::raw("'online'"))
            ->orderBy('hari')
            ->get();
        
        // Gabungkan data
        $data = $offlineData->merge($onlineData);
        
        // Convert hari ke integer
        $data->transform(function ($item) {
            $item->hari = (int) $item->hari;
            $item->total = (float) $item->total;
            return $item;
        });

        return $data;
    }

    // ==================== STATISTIK MINGGUAN ====================
    
    public function getStatistikMingguan()
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        
        // Data Offline mingguan - FIXED: Tambahkan tgl ke GROUP BY
        $offlineData = DB::table('pj_penjualan_master')
            ->whereBetween('tgl', [$startOfWeek, $endOfWeek])
            ->where('isPosting', 0)
            ->selectRaw("tgl, 'offline' as sumber, COUNT(*) as transaksi, SUM(grand_total) as total")
            ->groupBy('tgl', DB::raw("'offline'"))
            ->orderBy('tgl')
            ->get();
        
        // Data Online mingguan - FIXED: Tambahkan tanggal ke GROUP BY
        $onlineData = DB::table('tbl_order')
            ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
            ->where('order_online', 'Y')
            ->selectRaw("tanggal as tgl, 'online' as sumber, COUNT(*) as transaksi, SUM(total_order) as total")
            ->groupBy('tanggal', DB::raw("'online'"))
            ->orderBy('tanggal')
            ->get();
        
        return $offlineData->merge($onlineData);
    }

    // ==================== METODE PEMBAYARAN (OFFLINE ONLY) ====================
    
    public function getMetodePembayaranHarian($date = null)
    {
        $date = $date ?? now()->toDateString();
        
        $data = DB::table('pj_penjualan_master')
            ->where('tgl', $date)
            ->where('isPosting', 0)
            ->selectRaw('metode_bayar, COUNT(*) as jumlah, SUM(grand_total) as total')
            ->groupBy('metode_bayar')
            ->get();

        return $data;
    }

    // ==================== TOP PRODUCTS ONLINE ====================
    
    // public function getTopProductsOnline($limit = 10, $date = null)
    // {
    //     $date = $date ?? now()->toDateString();
        
    //     $data = DB::table('tbl_detail_order as detail')
    //         ->join('tbl_order as order', 'detail.order_id', '=', 'order.id')
    //         ->where('order.tanggal', $date)
    //         ->where('order.order_online', 'Y')
    //         ->selectRaw('detail.produk, SUM(detail.qty) as total_qty, SUM(detail.qty * CAST(detail.harga AS DECIMAL(10,2))) as total_value')
    //         ->groupBy('detail.produk')
    //         ->orderByDesc('total_qty')
    //         ->limit($limit)
    //         ->get();

    //     return $data;
    // }

    public function getTopProductsOnline($limit = 10, $date = null)
{
    $date = $date ?? now()->toDateString();
    
    $data = DB::table('tbl_detail_order as detail')
        ->join('tbl_order as order', 'detail.order_id', '=', 'order.id')
        ->leftJoin('tbl_produk as produk', 'detail.produk', '=', 'produk.id_produk')
        ->leftJoin('tbl_kategori as kategori', 'produk.kategori', '=', 'kategori.id')
        ->where('order.tanggal', $date)
        ->where('order.order_online', 'Y')
        ->selectRaw('
            detail.produk,
            COALESCE(produk.nama_produk, "Produk Tidak Ditemukan") as nama_produk,
            COALESCE(kategori.nama_kategori, "Tidak Berkategori") as nama_kategori,
            SUM(detail.qty) as total_qty,
            SUM(detail.qty * CAST(detail.harga AS DECIMAL(10,2))) as total_value
        ')
        ->groupBy('detail.produk', 'produk.nama_produk', 'kategori.nama_kategori')
        ->orderByDesc('total_qty')
        ->limit($limit)
        ->get();

    return $data;
}

// Alternatif: Top produk untuk offline
public function getTopProductsOffline($limit = 10, $date = null)
{
    $date = $date ?? now()->toDateString();
    
    $data = DB::table('pj_penjualan_detail as detail')
        ->join('pj_penjualan_master as master', 'detail.id_penjualan_m', '=', 'master.id_penjualan_m')
        ->leftJoin('tbl_produk as produk', 'detail.id_barang', '=', 'produk.id_produk')
        ->leftJoin('tbl_kategori as kategori', 'produk.kategori', '=', 'kategori.id')
        ->where('master.tgl', $date)
        ->where('master.isPosting', 1)
        ->selectRaw('
            detail.id_barang as produk_id,
            COALESCE(produk.nama_produk, "Produk Tidak Ditemukan") as nama_produk,
            COALESCE(kategori.nama_kategori, "Tidak Berkategori") as nama_kategori,
            SUM(detail.jumlah_beli) as total_qty,
            SUM(detail.jumlah_beli * detail.harga_satuan) as total_value
        ')
        ->groupBy('detail.id_barang', 'produk.nama_produk', 'kategori.nama_kategori')
        ->orderByDesc('total_qty')
        ->limit($limit)
        ->get();

    return $data;
}

// Method untuk gabungan top produk offline+online
public function getTopProductsGabungan($limit = 10, $date = null)
{
    $date = $date ?? now()->toDateString();
    
    // Data Offline
    $offlineData = DB::table('pj_penjualan_detail as detail')
        ->join('pj_penjualan_master as master', 'detail.id_penjualan_m', '=', 'master.id_penjualan_m')
        ->leftJoin('tbl_produk as produk', 'detail.id_barang', '=', 'produk.id_produk')
        ->leftJoin('tbl_kategori as kategori', 'produk.kategori', '=', 'kategori.id')
        ->where('master.tgl', $date)
        ->where('master.isPosting', 1)
        ->selectRaw('
            detail.id_barang as produk_id,
            COALESCE(produk.nama_produk, "Produk Tidak Ditemukan") as nama_produk,
            COALESCE(kategori.nama_kategori, "Tidak Berkategori") as nama_kategori,
            SUM(detail.jumlah_beli) as total_qty,
            SUM(detail.jumlah_beli * detail.harga_satuan) as total_value,
            "offline" as sumber
        ')
        ->groupBy('detail.id_barang', 'produk.nama_produk', 'kategori.nama_kategori');
    
    // Data Online
    $onlineData = DB::table('tbl_detail_order as detail')
        ->join('tbl_order as order', 'detail.order_id', '=', 'order.id')
        ->leftJoin('tbl_produk as produk', 'detail.produk', '=', 'produk.id_produk')
        ->leftJoin('tbl_kategori as kategori', 'produk.kategori', '=', 'kategori.id')
        ->where('order.tanggal', $date)
        ->where('order.order_online', 'Y')
        ->selectRaw('
            detail.produk as produk_id,
            COALESCE(produk.nama_produk, "Produk Tidak Ditemukan") as nama_produk,
            COALESCE(kategori.nama_kategori, "Tidak Berkategori") as nama_kategori,
            SUM(detail.qty) as total_qty,
            SUM(detail.qty * CAST(detail.harga AS DECIMAL(10,2))) as total_value,
            "online" as sumber
        ')
        ->groupBy('detail.produk', 'produk.nama_produk', 'kategori.nama_kategori');
    
    // Gabungkan dan hitung total
    $data = $offlineData->unionAll($onlineData)
        ->orderByDesc('total_qty')
        ->limit($limit)
        ->get();

    return $data;
}

    // ==================== ALTERNATIVE: QUERY DENGANYANG LEBIH COMPATIBLE ====================
    
    public function getChartDataHarianAlternative($date = null)
    {
        $date = $date ?? now()->toDateString();
        
        // Gunakan subquery untuk menghindari masalah GROUP BY
        $offlineData = DB::select("
            SELECT jam_hour as jam, 'offline' as sumber, SUM(total) as total
            FROM (
                SELECT CAST(SUBSTRING(jam, 1, 2) AS UNSIGNED) as jam_hour, grand_total as total
                FROM pj_penjualan_master 
                WHERE tgl = ? AND isPosting = 1
            ) as subquery
            GROUP BY jam_hour
            ORDER BY jam_hour
        ", [$date]);
        
        $onlineData = DB::select("
            SELECT jam_hour as jam, 'online' as sumber, SUM(total) as total
            FROM (
                SELECT CAST(SUBSTRING(jam, 1, 2) AS UNSIGNED) as jam_hour, total_order as total
                FROM tbl_order 
                WHERE tanggal = ? AND order_online = 'Y'
            ) as subquery
            GROUP BY jam_hour
            ORDER BY jam_hour
        ", [$date]);
        
        // Gabungkan data
        $data = collect(array_merge($offlineData, $onlineData));
        
        // Convert jam ke integer
        $data->transform(function ($item) {
            $item->jam = (int) $item->jam;
            $item->total = (float) $item->total;
            return $item;
        });

        return $data;
    }

// Method untuk kategori terlaris
public function getTopCategories($limit = 5, $date = null)
{
    $date = $date ?? now()->toDateString();
    
    // Data Offline
    $offlineData = DB::table('pj_penjualan_detail as detail')
        ->join('pj_penjualan_master as master', 'detail.id_penjualan_m', '=', 'master.id_penjualan_m')
        ->leftJoin('tbl_produk as produk', 'detail.id_barang', '=', 'produk.id_produk')
        ->leftJoin('tbl_kategori as kategori', 'produk.kategori', '=', 'kategori.id')
        ->where('master.tgl', $date)
        ->where('master.isPosting', 1)
        ->whereNotNull('kategori.nama_kategori')
        ->selectRaw('
            kategori.id as kategori_id,
            COALESCE(kategori.nama_kategori, "Tidak Berkategori") as nama_kategori,
            SUM(detail.jumlah_beli) as total_qty,
            SUM(detail.jumlah_beli * detail.harga_satuan) as total_value,
            "offline" as sumber
        ')
        ->groupBy('kategori.id', 'kategori.nama_kategori');
    
    // Data Online
    $onlineData = DB::table('tbl_detail_order as detail')
        ->join('tbl_order as order', 'detail.order_id', '=', 'order.id')
        ->leftJoin('tbl_produk as produk', 'detail.produk', '=', 'produk.id_produk')
        ->leftJoin('tbl_kategori as kategori', 'produk.kategori', '=', 'kategori.id')
        ->where('order.tanggal', $date)
        ->where('order.order_online', 'Y')
        ->whereNotNull('kategori.nama_kategori')
        ->selectRaw('
            kategori.id as kategori_id,
            COALESCE(kategori.nama_kategori, "Tidak Berkategori") as nama_kategori,
            SUM(detail.qty) as total_qty,
            SUM(detail.qty * CAST(detail.harga AS DECIMAL(10,2))) as total_value,
            "online" as sumber
        ')
        ->groupBy('kategori.id', 'kategori.nama_kategori');
    
    // Gabungkan
    $data = $offlineData->unionAll($onlineData)
        ->orderByDesc('total_qty')
        ->limit($limit)
        ->get();

    return $data;
}
public function getChartDataBulananDetail($year = null, $month = null)
{
    $year = $year ?? now()->year;
    $month = $month ?? now()->month;
    
    // Hitung jumlah hari dalam bulan
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    
    // Data Offline per hari - dengan detail per tipe pelanggan
    $offlineData = DB::table('pj_penjualan_master')
        ->whereYear('tgl', $year)
        ->whereMonth('tgl', $month)
        ->where('isPosting', 1)
        ->selectRaw('
            DAY(tgl) as hari,
            tipe_pelanggan,
            SUM(grand_total) as total
        ')
        ->groupBy('hari', 'tipe_pelanggan')
        ->orderBy('hari')
        ->get();
    
    // Data Online per hari
    $onlineData = DB::table('tbl_order')
        ->whereYear('tanggal', $year)
        ->whereMonth('tanggal', $month)
        ->where('order_online', 'Y')
        ->selectRaw('
            DAY(tanggal) as hari,
            "online" as tipe_pelanggan,
            SUM(total_order) as total
        ')
        ->groupBy('hari')
        ->orderBy('hari')
        ->get();
    
    // Gabungkan data
    $data = [
        'days_in_month' => $daysInMonth,
        'offline' => $offlineData,
        'online' => $onlineData,
        'month' => $month,
        'year' => $year
    ];

    return $data;
}

// Method untuk grafik bulanan gabungan (total per hari)
public function getChartBulananGabungan($year = null, $month = null)
{
    $year = $year ?? now()->year;
    $month = $month ?? now()->month;
    
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $days = range(1, $daysInMonth);
    
    // Data Offline total per hari
    $offlineData = DB::table('pj_penjualan_master')
        ->whereYear('tgl', $year)
        ->whereMonth('tgl', $month)
        ->where('isPosting', 1)
        ->selectRaw('DAY(tgl) as hari, SUM(grand_total) as total')
        ->groupBy('hari')
        ->orderBy('hari')
        ->get()
        ->keyBy('hari');
    
    // Data Online total per hari
    $onlineData = DB::table('tbl_order')
        ->whereYear('tanggal', $year)
        ->whereMonth('tanggal', $month)
        ->where('order_online', 'Y')
        ->selectRaw('DAY(tanggal) as hari, SUM(total_order) as total')
        ->groupBy('hari')
        ->orderBy('hari')
        ->get()
        ->keyBy('hari');
    
    // Format data untuk chart
    $chartData = [
        'labels' => array_map(function($day) {
            return 'Tanggal ' . $day;
        }, $days),
        'offline' => [],
        'online' => [],
        'total' => []
    ];
    
    foreach ($days as $day) {
        $offlineTotal = isset($offlineData[$day]) ? (float)$offlineData[$day]->total : 0;
        $onlineTotal = isset($onlineData[$day]) ? (float)$onlineData[$day]->total : 0;
        
        $chartData['offline'][] = $offlineTotal;
        $chartData['online'][] = $onlineTotal;
        $chartData['total'][] = $offlineTotal + $onlineTotal;
    }
    
    return $chartData;
}

// Method untuk statistik bulanan per tipe pelanggan offline
public function getStatistikBulananPerTipe($year = null, $month = null)
{
    $year = $year ?? now()->year;
    $month = $month ?? now()->month;
    
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $days = range(1, $daysInMonth);
    
    // Data per tipe pelanggan
    $data = DB::table('pj_penjualan_master')
        ->whereYear('tgl', $year)
        ->whereMonth('tgl', $month)
        ->where('isPosting', 1)
        ->selectRaw('
            DAY(tgl) as hari,
            tipe_pelanggan,
            SUM(grand_total) as total
        ')
        ->groupBy('hari', 'tipe_pelanggan')
        ->orderBy('hari')
        ->get();
    
    // Format data untuk chart
    $chartData = [
        'labels' => array_map(function($day) {
            return 'Tgl ' . $day;
        }, $days),
        'datasets' => []
    ];
    
    // Tipe pelanggan
    $tipePelanggan = ['umum', 'anggota', 'karyawan'];
    $colors = [
        'umum' => 'rgba(59, 130, 246, 0.8)',
        'anggota' => 'rgba(16, 185, 129, 0.8)',
        'karyawan' => 'rgba(245, 158, 11, 0.8)'
    ];
    
    foreach ($tipePelanggan as $tipe) {
        $dataset = [
            'label' => ucfirst($tipe),
            'data' => [],
            'backgroundColor' => $colors[$tipe],
            'borderColor' => str_replace('0.8', '1', $colors[$tipe]),
            'borderWidth' => 1
        ];
        
        foreach ($days as $day) {
            $total = $data->where('hari', $day)
                        ->where('tipe_pelanggan', $tipe)
                        ->first();
            $dataset['data'][] = $total ? (float)$total->total : 0;
        }
        
        $chartData['datasets'][] = $dataset;
    }
    
    return $chartData;
}
}