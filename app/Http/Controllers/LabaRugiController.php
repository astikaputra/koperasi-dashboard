<?php

namespace App\Http\Controllers;

use App\Services\LabaRugiService;
use Illuminate\Http\Request;

class LabaRugiController extends Controller
{
    protected $labaRugiService;

    public function __construct(LabaRugiService $labaRugiService)
    {
        $this->labaRugiService = $labaRugiService;
    }

    public function index(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        try {
            // Data harian
            $harian = $this->labaRugiService->getLabaRugiHarian($date);
            
            // Data bulanan
            $bulanan = $this->labaRugiService->getLabaRugiBulanan($year, $month);
            
            // Data chart
            $chartData = $this->labaRugiService->getChartData($year, $month);
            
            // Data produk
            $produk = $this->labaRugiService->getLabaRugiPerProduk($year, $month, 5);
            
            // Ringkasan
            $ringkasan = $this->labaRugiService->getRingkasan($year, $month);

        } catch (\Exception $e) {
            return view('dashboard.labarugi-error', [
                'error' => $e->getMessage(),
                'date' => $date,
                'month' => $month,
                'year' => $year
            ]);
        }

        return view('dashboard.labarugi', compact(
            'harian',
            'bulanan',
            'chartData',
            'produk',
            'ringkasan',
            'date',
            'month',
            'year'
        ));
    }

    public function apiHarian(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $data = $this->labaRugiService->getLabaRugiHarian($date);
        
        return response()->json($data);
    }

    public function apiBulanan(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $data = $this->labaRugiService->getLabaRugiBulanan($year, $month);
        
        return response()->json($data);
    }
}