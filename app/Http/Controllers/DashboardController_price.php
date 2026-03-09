<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController_price extends Controller
{
    protected $dashboardService;
    
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }
    
    public function index(Request $request)
    {
        $date = $request->get('date', date('Y-m-d'));
        
        return Inertia::render('Dashboard/Index', [
            'summary' => $this->dashboardService->getTodaySummary(),
            'breakdown' => $this->dashboardService->getDailyBreakdown($date),
            'topProducts' => $this->dashboardService->getTopProductsByProfit(10, 'today'),
            'marginTrend' => $this->dashboardService->getMarginTrend(7),
            'customerAnalysis' => $this->dashboardService->getCustomerTypeAnalysis($date),
            'filters' => [
                'date' => $date
            ]
        ]);
    }
    
    public function pricingAnalysis(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        
        return Inertia::render('Dashboard/PricingAnalysis', [
            'comparison' => $this->dashboardService->getPricingModeComparison($startDate, $endDate),
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate
            ]
        ]);
    }
    
    public function apiSummary(Request $request)
    {
        $date = $request->get('date', date('Y-m-d'));
        
        return response()->json([
            'summary' => $this->dashboardService->getTodaySummary(),
            'breakdown' => $this->dashboardService->getDailyBreakdown($date),
            'top_products' => $this->dashboardService->getTopProductsByProfit(5, 'today'),
        ]);
    }
}