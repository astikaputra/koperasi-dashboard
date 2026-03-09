<?php
// app/Http/Controllers/Api/DashboardController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DailyDashboardAggregate;
use App\Models\TransactionMarkupTracking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function summary(Request $request)
    {
        $date = $request->get('date', date('Y-m-d'));
        
        $summary = DailyDashboardAggregate::where('date', $date)
            ->where('source', 'TOTAL')
            ->where('customer_type', 'TOTAL')
            ->first();
            
        if (!$summary) {
            $summary = (object) [
                'total_sales' => 0,
                'total_markup' => 0,
                'total_overhead' => 0,
                'total_tax' => 0,
                'total_gross_profit' => 0,
                'margin_percent' => 0,
                'total_transactions' => 0,
                'total_quantity' => 0
            ];
        }
        
        // Pricing mode comparison
        $pricingComparison = TransactionMarkupTracking::selectRaw('
                pricing_mode,
                COUNT(*) as total_transactions,
                SUM(total_sales) as total_sales,
                SUM(total_markup) as total_markup
            ')
            ->whereDate('transaction_date', $date)
            ->groupBy('pricing_mode')
            ->get();
            
        return response()->json([
            'summary' => $summary,
            'pricing_comparison' => $pricingComparison,
            'timestamp' => now()->toDateTimeString()
        ]);
    }
}