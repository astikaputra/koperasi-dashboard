<?php

namespace App\Services;

use App\Models\DailyDashboardAggregate;
use App\Models\TransactionMarkupTracking;
use Carbon\Carbon;

class DashboardService
{
    public function getTodaySummary()
    {
        return DailyDashboardAggregate::forDate(today())
            ->where('source', 'TOTAL')
            ->where('customer_type', 'TOTAL')
            ->first();
    }

    public function getDailyBreakdown($date = null)
    {
        $date = $date ? Carbon::parse($date) : today();
        
        return DailyDashboardAggregate::forDate($date)
            ->where('source', '!=', 'TOTAL')
            ->get()
            ->groupBy('source');
    }

    public function getPricingModeComparison($startDate, $endDate = null)
    {
        $endDate = $endDate ?? $startDate;
        
        return TransactionMarkupTracking::whereBetween('transaction_date', [$startDate, $endDate])
            ->selectRaw('
                pricing_mode,
                COUNT(*) as total_transactions,
                SUM(quantity) as total_quantity,
                SUM(total_sales) as total_sales,
                SUM(total_gross_profit) as total_profit,
                AVG(markup_percent) as avg_markup_percent
            ')
            ->groupBy('pricing_mode')
            ->get();
    }

    public function getTopProductsByProfit($limit = 10, $period = 'today')
    {
        $query = TransactionMarkupTracking::with('product');
        
        match($period) {
            'today' => $query->today(),
            'week' => $query->whereBetween('transaction_date', 
                [now()->startOfWeek(), now()->endOfWeek()]),
            'month' => $query->thisMonth(),
            default => null,
        };
        
        return $query->selectRaw('
                product_id,
                SUM(quantity) as total_quantity,
                SUM(total_sales) as total_sales,
                SUM(total_gross_profit) as total_profit,
                (SUM(total_gross_profit) / SUM(total_sales)) * 100 as margin_percent
            ')
            ->groupBy('product_id')
            ->orderByDesc('total_profit')
            ->limit($limit)
            ->get()
            ->map(function($item) {
                $item->product_name = $item->product->nama_produk ?? 'Unknown';
                return $item;
            });
    }

    public function getMarginTrend($days = 30)
    {
        return DailyDashboardAggregate::where('source', 'TOTAL')
            ->where('customer_type', 'TOTAL')
            ->whereDate('date', '>=', now()->subDays($days))
            ->orderBy('date')
            ->get(['date', 'margin_percent', 'total_sales', 'total_gross_profit']);
    }

    public function getCustomerTypeAnalysis($date = null)
    {
        $date = $date ?? today();
        
        return DailyDashboardAggregate::forDate($date)
            ->where('source', 'TOTAL')
            ->where('customer_type', '!=', 'TOTAL')
            ->orderByDesc('total_sales')
            ->get();
    }
}