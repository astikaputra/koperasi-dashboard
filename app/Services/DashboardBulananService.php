<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardBulananService
{
    /**
     * Mendapatkan data penjualan harian dalam satu bulan (offline + online)
     */
    public function getPenjualanBulanan($year = null, $month = null)
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;
        
        // Hitung jumlah hari dalam bulan
        $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;
        $days = range(1, $daysInMonth);
        
        // Data Penjualan Offline per hari
        $offlineData = DB::table('pj_penjualan_master')
            ->whereYear('tgl', $year)
            ->whereMonth('tgl', $month)
            ->where('isPosting', 0)
            ->selectRaw('
                DAY(tgl) as hari,
                COUNT(*) as jumlah_transaksi,
                SUM(grand_total) as total_penjualan
            ')
            ->groupBy(DB::raw('DAY(tgl)'))
            ->orderBy('hari')
            ->get()
            ->keyBy('hari');
        
        // Data Penjualan Online per hari
        $onlineData = DB::table('tbl_order')
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->where('order_online', 'Y')
            ->selectRaw('
                DAY(tanggal) as hari,
                COUNT(*) as jumlah_order,
                SUM(total_order) as total_penjualan
            ')
            ->groupBy(DB::raw('DAY(tanggal)'))
            ->orderBy('hari')
            ->get()
            ->keyBy('hari');
        
        // Siapkan array untuk semua hari
        $harian = [];
        $totalOffline = 0;
        $totalOnline = 0;
        $totalTransaksiOffline = 0;
        $totalOrderOnline = 0;
        
        foreach ($days as $hari) {
            $offline = $offlineData->get($hari);
            $online = $onlineData->get($hari);
            
            $offlinePenjualan = $offline ? (float) $offline->total_penjualan : 0;
            $onlinePenjualan = $online ? (float) $online->total_penjualan : 0;
            $offlineTransaksi = $offline ? (int) $offline->jumlah_transaksi : 0;
            $onlineOrder = $online ? (int) $online->jumlah_order : 0;
            
            $harian[$hari] = [
                'hari' => $hari,
                'tanggal' => Carbon::create($year, $month, $hari)->format('d M Y'),
                'offline' => [
                    'transaksi' => $offlineTransaksi,
                    'penjualan' => $offlinePenjualan
                ],
                'online' => [
                    'order' => $onlineOrder,
                    'penjualan' => $onlinePenjualan
                ],
                'total' => [
                    'transaksi' => $offlineTransaksi + $onlineOrder,
                    'penjualan' => $offlinePenjualan + $onlinePenjualan
                ]
            ];
            
            $totalOffline += $offlinePenjualan;
            $totalOnline += $onlinePenjualan;
            $totalTransaksiOffline += $offlineTransaksi;
            $totalOrderOnline += $onlineOrder;
        }
        
        // Statistik tambahan
        $statistik = [
            'total_offline' => $totalOffline,
            'total_online' => $totalOnline,
            'total_gabungan' => $totalOffline + $totalOnline,
            'total_transaksi_offline' => $totalTransaksiOffline,
            'total_order_online' => $totalOrderOnline,
            'total_transaksi_gabungan' => $totalTransaksiOffline + $totalOrderOnline,
            'rata_rata_harian_offline' => $daysInMonth > 0 ? $totalOffline / $daysInMonth : 0,
            'rata_rata_harian_online' => $daysInMonth > 0 ? $totalOnline / $daysInMonth : 0,
            'rata_rata_harian_gabungan' => $daysInMonth > 0 ? ($totalOffline + $totalOnline) / $daysInMonth : 0,
            'hari_dengan_penjualan_tertinggi' => $this->getHariTertinggi($harian),
            'hari_dengan_penjualan_terendah' => $this->getHariTerendah($harian),
            'persentase_offline' => ($totalOffline + $totalOnline) > 0 ? 
                round(($totalOffline / ($totalOffline + $totalOnline)) * 100, 2) : 0,
            'persentase_online' => ($totalOffline + $totalOnline) > 0 ? 
                round(($totalOnline / ($totalOffline + $totalOnline)) * 100, 2) : 0
        ];
        
        return [
            'year' => $year,
            'month' => $month,
            'nama_bulan' => Carbon::create($year, $month, 1)->format('F Y'),
            'days_in_month' => $daysInMonth,
            'harian' => $harian,
            'statistik' => $statistik
        ];
    }

    /**
     * Mendapatkan data perbandingan tipe pelanggan online
     */
    public function getPerbandinganTipePelangganOnline($year = null, $month = null)
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;
        
        // Query untuk mendapatkan semua order online
        $allOrders = DB::table('tbl_order')
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->where('order_online', 'Y')
            ->select('id', 'nik', 'total_order', 'status')
            ->get();
        
        // Inisialisasi array hasil
        $result = [
            'status' => [
                'selesai' => ['order' => 0, 'penjualan' => 0],
                'proses' => ['order' => 0, 'penjualan' => 0],
                'batal' => ['order' => 0, 'penjualan' => 0],
            ],
            'tipe_pelanggan' => [
                'anggota' => ['order' => 0, 'penjualan' => 0, 'nama' => 'Anggota'],
                'karyawan' => ['order' => 0, 'penjualan' => 0, 'nama' => 'Karyawan'],
            ]
        ];
        
        // Jika tidak ada data, kembalikan array kosong
        if ($allOrders->isEmpty()) {
            return $this->getEmptyOnlineData();
        }
        
        // Jika ada NIK, cek di tabel karyawan
        $niks = $allOrders->pluck('nik')->filter()->unique()->toArray();
        
        // Ambil data karyawan berdasarkan NIK
        $karyawanData = [];
        if (!empty($niks)) {
            $karyawanData = DB::table('tb_karyawan')
                ->whereIn('nik', $niks)
                ->select('nik', 'anggota', 'aktif')
                ->get()
                ->keyBy('nik');
        }
        
        foreach ($allOrders as $order) {
            $penjualan = (float) $order->total_order;
            
            // Klasifikasi berdasarkan status
            switch ($order->status) {
                case 'CLOSE':
                    $result['status']['selesai']['order']++;
                    $result['status']['selesai']['penjualan'] += $penjualan;
                    break;
                case 'OPEN':
                    $result['status']['proses']['order']++;
                    $result['status']['proses']['penjualan'] += $penjualan;
                    break;
                case 'CANCEL':
                    $result['status']['batal']['order']++;
                    $result['status']['batal']['penjualan'] += $penjualan;
                    break;
                default:
                    $result['status']['proses']['order']++;
                    $result['status']['proses']['penjualan'] += $penjualan;
                    break;
            }
            
            // Klasifikasi berdasarkan tipe pelanggan
            if (!empty($order->nik) && isset($karyawanData[$order->nik])) {
                $karyawan = $karyawanData[$order->nik];
                if ($karyawan->anggota == 'Y') {
                    $result['tipe_pelanggan']['anggota']['order']++;
                    $result['tipe_pelanggan']['anggota']['penjualan'] += $penjualan;
                } else {
                    $result['tipe_pelanggan']['karyawan']['order']++;
                    $result['tipe_pelanggan']['karyawan']['penjualan'] += $penjualan;
                }
            }
        }
        
        // Hitung total dan persentase
        $totalPenjualan = $allOrders->sum('total_order');
        $totalOrder = $allOrders->count();
        
        // Hitung rata-rata per status
        foreach ($result['status'] as $key => &$status) {
            $status['rata_rata'] = $status['order'] > 0 ? 
                $status['penjualan'] / $status['order'] : 0;
            $status['persentase_order'] = $totalOrder > 0 ? 
                round(($status['order'] / $totalOrder) * 100, 2) : 0;
            $status['persentase_penjualan'] = $totalPenjualan > 0 ? 
                round(($status['penjualan'] / $totalPenjualan) * 100, 2) : 0;
        }
        
        // Hitung persentase per tipe pelanggan
        $totalIdentified = $result['tipe_pelanggan']['anggota']['order'] + $result['tipe_pelanggan']['karyawan']['order'];
        $totalPenjualanIdentified = $result['tipe_pelanggan']['anggota']['penjualan'] + $result['tipe_pelanggan']['karyawan']['penjualan'];
        
        foreach ($result['tipe_pelanggan'] as $key => &$tipe) {
            $tipe['persentase_order'] = $totalIdentified > 0 ? 
                round(($tipe['order'] / $totalIdentified) * 100, 2) : 0;
            $tipe['persentase_penjualan'] = $totalPenjualanIdentified > 0 ? 
                round(($tipe['penjualan'] / $totalPenjualanIdentified) * 100, 2) : 0;
            $tipe['rata_rata'] = $tipe['order'] > 0 ? 
                $tipe['penjualan'] / $tipe['order'] : 0;
        }
        
        return [
            'status' => $result['status'],
            'tipe_pelanggan' => $result['tipe_pelanggan'],
            'total' => $totalPenjualan,
            'total_order' => $totalOrder,
            'total_identified' => $totalIdentified,
            'total_penjualan_identified' => $totalPenjualanIdentified
        ];
    }

    /**
     * Mendapatkan data kosong untuk online
     */
    private function getEmptyOnlineData()
    {
        return [
            'status' => [
                'selesai' => ['order' => 0, 'penjualan' => 0, 'rata_rata' => 0, 'persentase_order' => 0, 'persentase_penjualan' => 0],
                'proses' => ['order' => 0, 'penjualan' => 0, 'rata_rata' => 0, 'persentase_order' => 0, 'persentase_penjualan' => 0],
                'batal' => ['order' => 0, 'penjualan' => 0, 'rata_rata' => 0, 'persentase_order' => 0, 'persentase_penjualan' => 0],
            ],
            'tipe_pelanggan' => [
                'anggota' => ['order' => 0, 'penjualan' => 0, 'rata_rata' => 0, 'persentase_order' => 0, 'persentase_penjualan' => 0],
                'karyawan' => ['order' => 0, 'penjualan' => 0, 'rata_rata' => 0, 'persentase_order' => 0, 'persentase_penjualan' => 0],
            ],
            'total' => 0,
            'total_order' => 0,
            'total_identified' => 0,
            'total_penjualan_identified' => 0
        ];
    }

    /**
     * Mendapatkan data perbandingan tipe pembayaran
     */
    public function getPerbandinganPembayaran($year = null, $month = null)
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;
        
        // Data pembayaran dengan GROUP BY yang benar
        $paymentData = DB::table('pj_penjualan_master')
            ->whereYear('tgl', $year)
            ->whereMonth('tgl', $month)
            ->where('isPosting', 0)
            ->selectRaw('
                CASE 
                    WHEN LOWER(metode_bayar) IN ("cash", "Cash", "CASH") THEN "cash"
                    WHEN LOWER(metode_bayar) IN ("qris", "QRIS", "Qris") THEN "qris"
                    ELSE "lainnya"
                END as metode,
                COUNT(*) as jumlah_transaksi,
                SUM(grand_total) as total_penjualan
            ')
            ->groupBy('metode')
            ->get()
            ->keyBy('metode');
        
        $totalPenjualan = $paymentData->sum('total_penjualan');
        
        return [
            'cash' => [
                'transaksi' => $paymentData->get('cash')->jumlah_transaksi ?? 0,
                'penjualan' => $paymentData->get('cash')->total_penjualan ?? 0,
                'persentase' => $totalPenjualan > 0 ? 
                    round((($paymentData->get('cash')->total_penjualan ?? 0) / $totalPenjualan) * 100, 2) : 0
            ],
            'qris' => [
                'transaksi' => $paymentData->get('qris')->jumlah_transaksi ?? 0,
                'penjualan' => $paymentData->get('qris')->total_penjualan ?? 0,
                'persentase' => $totalPenjualan > 0 ? 
                    round((($paymentData->get('qris')->total_penjualan ?? 0) / $totalPenjualan) * 100, 2) : 0
            ],
            'lainnya' => [
                'transaksi' => $paymentData->get('lainnya')->jumlah_transaksi ?? 0,
                'penjualan' => $paymentData->get('lainnya')->total_penjualan ?? 0,
                'persentase' => $totalPenjualan > 0 ? 
                    round((($paymentData->get('lainnya')->total_penjualan ?? 0) / $totalPenjualan) * 100, 2) : 0
            ],
            'total' => $totalPenjualan
        ];
    }

    /**
     * Mendapatkan data perbandingan tipe pelanggan offline
     */
    public function getPerbandinganTipePelangganOffline($year = null, $month = null)
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;
        
        $data = DB::table('pj_penjualan_master')
            ->whereYear('tgl', $year)
            ->whereMonth('tgl', $month)
            ->where('isPosting', 0)
            ->selectRaw('
                tipe_pelanggan,
                COUNT(*) as jumlah_transaksi,
                SUM(grand_total) as total_penjualan,
                AVG(grand_total) as rata_rata
            ')
            ->groupBy('tipe_pelanggan')
            ->get()
            ->keyBy('tipe_pelanggan');
        
        $totalPenjualan = $data->sum('total_penjualan');
        
        $result = [];
        $tipeList = ['umum', 'anggota', 'karyawan'];
        
        foreach ($tipeList as $tipe) {
            $item = $data->get($tipe);
            $result[$tipe] = [
                'transaksi' => $item->jumlah_transaksi ?? 0,
                'penjualan' => $item->total_penjualan ?? 0,
                'rata_rata' => $item->rata_rata ?? 0,
                'persentase' => $totalPenjualan > 0 ? 
                    round((($item->total_penjualan ?? 0) / $totalPenjualan) * 100, 2) : 0
            ];
        }
        
        return [
            'data' => $result,
            'total' => $totalPenjualan,
            'total_transaksi' => $data->sum('jumlah_transaksi')
        ];
    }

    /**
     * Mendapatkan data chart
     */
    public function getChartData($year = null, $month = null)
    {
        $data = $this->getPenjualanBulanan($year, $month);
        
        $chartData = [
            'labels' => [],
            'offline' => [],
            'online' => [],
            'total' => [],
            'transaksi_offline' => [],
            'transaksi_online' => []
        ];
        
        foreach ($data['harian'] as $hari => $item) {
            $chartData['labels'][] = 'Tgl ' . $hari;
            $chartData['offline'][] = $item['offline']['penjualan'];
            $chartData['online'][] = $item['online']['penjualan'];
            $chartData['total'][] = $item['total']['penjualan'];
            $chartData['transaksi_offline'][] = $item['offline']['transaksi'];
            $chartData['transaksi_online'][] = $item['online']['order'];
        }
        
        return $chartData;
    }

    /**
     * Mendapatkan data heatmap
     */
    public function getHeatmapData($year = null, $month = null)
    {
        $data = $this->getPenjualanBulanan($year, $month);
        
        $weeks = [];
        $week = [];
        $dayOfWeek = 0;
        
        $firstDay = Carbon::create($data['year'], $data['month'], 1);
        $startDayOfWeek = $firstDay->dayOfWeek;
        
        // Tambahkan hari kosong di awal bulan
        for ($i = 0; $i < $startDayOfWeek; $i++) {
            $week[] = [
                'hari' => null,
                'tanggal' => null,
                'total' => 0,
                'offline' => 0,
                'online' => 0,
                'offline_penjualan' => 0,
                'online_penjualan' => 0
            ];
            $dayOfWeek++;
        }
        
        foreach ($data['harian'] as $hari => $item) {
            $week[] = [
                'hari' => $hari,
                'tanggal' => $item['tanggal'],
                'total' => $item['total']['penjualan'],
                'offline' => $item['offline']['penjualan'],
                'online' => $item['online']['penjualan'],
                'offline_penjualan' => $item['offline']['penjualan'],
                'online_penjualan' => $item['online']['penjualan'],
                'offline_transaksi' => $item['offline']['transaksi'],
                'online_order' => $item['online']['order']
            ];
            
            $dayOfWeek++;
            
            if ($dayOfWeek == 7) {
                $weeks[] = $week;
                $week = [];
                $dayOfWeek = 0;
            }
        }
        
        // Tambahkan sisa hari
        if (!empty($week)) {
            while (count($week) < 7) {
                $week[] = [
                    'hari' => null,
                    'tanggal' => null,
                    'total' => 0,
                    'offline' => 0,
                    'online' => 0,
                    'offline_penjualan' => 0,
                    'online_penjualan' => 0
                ];
            }
            $weeks[] = $week;
        }
        
        return $weeks;
    }

    /**
     * Mendapatkan hari dengan penjualan tertinggi
     */
    private function getHariTertinggi($harian)
    {
        $maxHari = null;
        $maxValue = 0;
        
        foreach ($harian as $hari => $item) {
            if ($item['total']['penjualan'] > $maxValue) {
                $maxValue = $item['total']['penjualan'];
                $maxHari = $hari;
            }
        }
        
        return $maxHari ? [
            'hari' => $maxHari,
            'tanggal' => $harian[$maxHari]['tanggal'],
            'total' => $maxValue
        ] : null;
    }

    /**
     * Mendapatkan hari dengan penjualan terendah
     */
    private function getHariTerendah($harian)
    {
        $minHari = null;
        $minValue = PHP_FLOAT_MAX;
        
        foreach ($harian as $hari => $item) {
            if ($item['total']['penjualan'] > 0 && $item['total']['penjualan'] < $minValue) {
                $minValue = $item['total']['penjualan'];
                $minHari = $hari;
            }
        }
        
        return $minHari ? [
            'hari' => $minHari,
            'tanggal' => $harian[$minHari]['tanggal'],
            'total' => $minValue
        ] : null;
    }

    /**
     * Mendapatkan data perbandingan pembayaran harian
     */
    public function getPerbandinganPembayaranHarian($date = null)
    {
        $date = $date ?? now()->toDateString();
        
        $data = DB::table('pj_penjualan_master')
            ->where('tgl', $date)
            ->where('isPosting', 0)
            ->selectRaw('
                CASE 
                    WHEN LOWER(metode_bayar) IN ("cash") THEN "cash"
                    WHEN LOWER(metode_bayar) IN ("qris") THEN "qris"
                    ELSE "lainnya"
                END as metode,
                COUNT(*) as jumlah_transaksi,
                SUM(grand_total) as total_penjualan
            ')
            ->groupBy('metode')
            ->get()
            ->keyBy('metode');
        
        return [
            'cash' => [
                'transaksi' => $data->get('cash')->jumlah_transaksi ?? 0,
                'penjualan' => $data->get('cash')->total_penjualan ?? 0
            ],
            'qris' => [
                'transaksi' => $data->get('qris')->jumlah_transaksi ?? 0,
                'penjualan' => $data->get('qris')->total_penjualan ?? 0
            ],
            'lainnya' => [
                'transaksi' => $data->get('lainnya')->jumlah_transaksi ?? 0,
                'penjualan' => $data->get('lainnya')->total_penjualan ?? 0
            ]
        ];
    }
}