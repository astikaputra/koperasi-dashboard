<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinanceDashboardController extends Controller
{
    public function index()
    {
        // Blade will fetch data via AJAX from /finance/dashboard/data
        return view('finance.dashboard');
    }

    /**
     * JSON endpoint for dashboard data (used by AJAX)
     */
    public function data(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $startMonth = Carbon::now()->startOfMonth()->toDateString();
        $start30 = Carbon::now()->subDays(29)->toDateString(); // last 30 days

        // 1) Totals today
        $totalsToday = DB::table('finance_logs')
            ->whereDate('tanggal_log', $today)
            ->selectRaw('
                COALESCE(SUM(total_value),0) as omzet,
                COALESCE(SUM(markup_value),0) as total_markup,
                COALESCE(SUM(overhead_value),0) as total_overhead,
                COALESCE(SUM(tax_value),0) as total_pajak,
                COALESCE(SUM(total_margin),0) as total_margin
            ')
            ->first();

        // 2) Trend last 30 days (group by date)
        $trend = DB::table('finance_logs')
            ->whereBetween('tanggal_log', [$start30, $today])
            ->selectRaw('tanggal_log as date,
                         COALESCE(SUM(total_value),0) as omzet,
                         COALESCE(SUM(markup_value),0) as markup,
                         COALESCE(SUM(overhead_value),0) as overhead,
                         COALESCE(SUM(tax_value),0) as pajak')
            ->groupBy('tanggal_log')
            ->orderBy('tanggal_log')
            ->get();

        // 3) Breakdown by sumber (POS vs ONLINE)
        $bySource = DB::table('finance_logs')
            ->whereBetween('tanggal_log', [$startMonth, $today])
            ->selectRaw('sumber,
                         COALESCE(SUM(total_value),0) as omzet,
                         COALESCE(SUM(markup_value),0) as markup,
                         COALESCE(SUM(overhead_value),0) as overhead,
                         COALESCE(SUM(tax_value),0) as pajak')
            ->groupBy('sumber')
            ->get();

        // 4) Top products (by omzet) this month
        $topProducts = DB::table('finance_logs as f')
            ->join('products as p','p.id','f.product_id')
            ->whereBetween('f.tanggal_log', [$startMonth, $today])
            ->selectRaw('p.id, p.nama as product_name, COALESCE(SUM(f.total_value),0) as omzet, COALESCE(SUM(f.markup_value),0) as markup')
            ->groupBy('p.id','p.nama')
            ->orderByDesc('omzet')
            ->limit(10)
            ->get();

        // 5) KPI: achievement ratio (optional) - if you have daily target in config or table
        $dailyTarget = DB::table('overhead_config')->value('daily_target'); // optional column
        $achievement = $dailyTarget ? round($totalsToday->omzet / $dailyTarget * 100, 2) : null;

        return response()->json([
            'today' => $totalsToday,
            'trend' => $trend,
            'by_source' => $bySource,
            'top_products' => $topProducts,
            'achievement' => $achievement,
            'date' => $today
        ]);
    }
}
