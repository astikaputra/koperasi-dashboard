<?php

namespace App\Http\Controllers;

use App\Services\DashboardBulananService;
use Illuminate\Http\Request;

class DashboardBulananController extends Controller
{
    protected $bulananService;

    public function __construct(DashboardBulananService $bulananService)
    {
        $this->bulananService = $bulananService;
    }

 public function index(Request $request)
{
    $month = $request->input('month', now()->month);
    $year = $request->input('year', now()->year);

    try {
        // Data penjualan bulanan
        $penjualanBulanan = $this->bulananService->getPenjualanBulanan($year, $month);
        
        // Data untuk chart
        $chartData = $this->bulananService->getChartData($year, $month);
        
        // Data perbandingan
        $perbandinganPembayaran = $this->bulananService->getPerbandinganPembayaran($year, $month);
        $perbandinganTipeOffline = $this->bulananService->getPerbandinganTipePelangganOffline($year, $month);
        $perbandinganTipeOnline = $this->bulananService->getPerbandinganTipePelangganOnline($year, $month);
        //$chartTipeOnline = $this->bulananService->getChartTipePelangganOnline($year, $month);
        $perbandinganPembayaranHarian = $this->bulananService->getPerbandinganPembayaranHarian($penjualanBulanan['harian'][1]['tanggal'] ?? now()->toDateString());
        
        // Data heatmap
        $heatmap = $this->bulananService->getHeatmapData($year, $month);
        
        // Data statistik
        $statistik = $penjualanBulanan['statistik'];
        $harian = $penjualanBulanan['harian'];

    } catch (\Exception $e) {
        return view('dashboard.bulanan-error', [
            'error' => $e->getMessage(),
            'month' => $month,
            'year' => $year
        ]);
    }

    return view('dashboard.bulanan', compact(
        'penjualanBulanan',
        'chartData',
        'perbandinganPembayaran',
        'perbandinganTipeOffline',
        'perbandinganTipeOnline',
       // 'chartTipeOnline',
        'perbandinganPembayaranHarian',
        'heatmap',
        'statistik',
        'harian',
        'month',
        'year'
    ));
}

    public function apiBulanan(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        
        $data = $this->bulananService->getPenjualanBulanan($year, $month);
        
        return response()->json($data);
    }
}