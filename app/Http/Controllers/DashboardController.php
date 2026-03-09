<?php

namespace App\Http\Controllers;

use App\Services\DashboardPenjualanService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $penjualanService;

    public function __construct(DashboardPenjualanService $penjualanService)
    {
        $this->penjualanService = $penjualanService;
    }

    // public function index(Request $request)
    // {
    //     $date = $request->input('date', now()->toDateString());
    //     $month = $request->input('month', now()->month);
    //     $year = $request->input('year', now()->year);

    //     // Data Offline
    //     $offlineHarian = $this->penjualanService->getPenjualanOfflineHarian($date);
    //     $offlineBulanan = $this->penjualanService->getPenjualanOfflineBulanan($year, $month);
        
    //     // Data Online
    //     $onlineHarian = $this->penjualanService->getPenjualanOnlineHarian($date);
    //     $onlineBulanan = $this->penjualanService->getPenjualanOnlineBulanan($year, $month);
        
    //     // Data Gabungan
    //     $totalHarian = $this->penjualanService->getTotalPenjualanHarian($date);
    //     $totalBulanan = $this->penjualanService->getTotalPenjualanBulanan($year, $month);
        
    //     // Chart Data
    //     $chartHarian = $this->penjualanService->getChartDataHarian($date);
    //     $chartBulanan = $this->penjualanService->getChartDataBulanan($year, $month);
        
    //     // Statistik lainnya
    //     $mingguan = $this->penjualanService->getStatistikMingguan();
    //     $metodeBayar = $this->penjualanService->getMetodePembayaranHarian($date);
    //     $topProducts = $this->penjualanService->getTopProductsOnline(5, $date);

    //     return view('dashboard.index', compact(
    //         'offlineHarian',
    //         'offlineBulanan',
    //         'onlineHarian',
    //         'onlineBulanan',
    //         'totalHarian',
    //         'totalBulanan',
    //         'chartHarian',
    //         'chartBulanan',
    //         'mingguan',
    //         'metodeBayar',
    //         'topProducts',
    //         'date',
    //         'month',
    //         'year'
    //     ));
    // }

    public function index(Request $request)
{
    $date = $request->input('date', now()->toDateString());
    $month = $request->input('month', now()->month);
    $year = $request->input('year', now()->year);

    try {
        // Data Offline
        $offlineHarian = $this->penjualanService->getPenjualanOfflineHarian($date);
        $offlineBulanan = $this->penjualanService->getPenjualanOfflineBulanan($year, $month);
        
        // Data Online
        $onlineHarian = $this->penjualanService->getPenjualanOnlineHarian($date);
        $onlineBulanan = $this->penjualanService->getPenjualanOnlineBulanan($year, $month);
        
        // Data Gabungan
        $totalHarian = $this->penjualanService->getTotalPenjualanHarian($date);
        $totalBulanan = $this->penjualanService->getTotalPenjualanBulanan($year, $month);
        
        // Chart Data
        $chartHarian = $this->penjualanService->getChartDataHarian($date);
        $chartBulanan = $this->penjualanService->getChartDataBulanan($year, $month);
        $chartBulananDetail = $this->penjualanService->getChartDataBulananDetail($year, $month);
        $chartBulananGabungan = $this->penjualanService->getChartBulananGabungan($year, $month);
        $chartBulananPerTipe = $this->penjualanService->getStatistikBulananPerTipe($year, $month);
        
        // Statistik lainnya
        $metodeBayar = $this->penjualanService->getMetodePembayaranHarian($date);
        
        // Top Products - Semua sumber
        $topProductsOnline = $this->penjualanService->getTopProductsOnline(5, $date);
        $topProductsOffline = $this->penjualanService->getTopProductsOffline(5, $date);
        $topProductsGabungan = $this->penjualanService->getTopProductsGabungan(10, $date);
        
        // Kategori terlaris
        $topCategories = $this->penjualanService->getTopCategories(5, $date);

    } catch (\Exception $e) {
        // Jika ada error, tampilkan data kosong
        return view('dashboard.error', [
            'error' => $e->getMessage(),
            'date' => $date,
            'month' => $month,
            'year' => $year
        ]);
    }

    return view('dashboard.index', compact(
        'offlineHarian',
        'offlineBulanan',
        'onlineHarian',
        'onlineBulanan',
        'totalHarian',
        'totalBulanan',
        'chartHarian',
        'chartBulanan',
        'chartBulananDetail',
        'chartBulananGabungan',
        'chartBulananPerTipe',
        'metodeBayar',
        'topProductsOnline',
        'topProductsOffline',
        'topProductsGabungan',
        'topCategories',
        'date',
        'month',
        'year'
    ));
}

    // API Endpoints
    public function getDataHarian(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $data = $this->penjualanService->getTotalPenjualanHarian($date);
        
        return response()->json($data);
    }

    public function getDataBulanan(Request $request)
    {
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);
        $data = $this->penjualanService->getTotalPenjualanBulanan($year, $month);
        
        return response()->json($data);
    }
    
    // Debug functions
    public function testDatabase()
    {
        // ... (sama seperti sebelumnya)
    }
    
    public function generateDummyData()
    {
        // ... (sama seperti sebelumnya)
    }
}