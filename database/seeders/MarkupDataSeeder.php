<?php
// database/seeders/MarkupDataSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MarkupDataSeeder extends Seeder
{
    public function run()
    {
        // Hapus data lama jika ada
        DB::table('daily_dashboard_aggregates')->truncate();
        
        // Buat data untuk 7 hari terakhir
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $totalSales = rand(10000000, 30000000);
            $totalCost = $totalSales * 0.6; // 60% dari penjualan
            $totalMarkup = $totalSales * 0.2; // 20% dari penjualan
            $totalOverhead = $totalSales * 0.05; // 5% dari penjualan
            $totalTax = $totalSales * 0.11; // 11% pajak
            $totalGrossProfit = $totalSales - $totalCost - $totalOverhead - $totalTax;
            $marginPercent = ($totalGrossProfit / $totalSales) * 100;
            
            // Total data
            DB::table('daily_dashboard_aggregates')->insert([
                'date' => $date->toDateString(),
                'source' => 'TOTAL',
                'customer_type' => 'TOTAL',
                'total_transactions' => rand(50, 150),
                'total_quantity' => rand(200, 500),
                'total_sales' => $totalSales,
                'total_cost' => $totalCost,
                'total_markup' => $totalMarkup,
                'total_overhead' => $totalOverhead,
                'total_tax' => $totalTax,
                'total_gross_profit' => $totalGrossProfit,
                'margin_percent' => round($marginPercent, 2),
                'last_updated' => now(),
            ]);
            
            // POS data (60% dari total)
            DB::table('daily_dashboard_aggregates')->insert([
                'date' => $date->toDateString(),
                'source' => 'POS',
                'customer_type' => 'TOTAL',
                'total_transactions' => rand(30, 100),
                'total_quantity' => rand(100, 300),
                'total_sales' => $totalSales * 0.6,
                'total_cost' => $totalCost * 0.6,
                'total_markup' => $totalMarkup * 0.6,
                'total_overhead' => $totalOverhead * 0.6,
                'total_tax' => $totalTax * 0.6,
                'total_gross_profit' => $totalGrossProfit * 0.6,
                'margin_percent' => round($marginPercent, 2),
                'last_updated' => now(),
            ]);
            
            // ONLINE data (40% dari total)
            DB::table('daily_dashboard_aggregates')->insert([
                'date' => $date->toDateString(),
                'source' => 'ONLINE',
                'customer_type' => 'TOTAL',
                'total_transactions' => rand(20, 50),
                'total_quantity' => rand(80, 200),
                'total_sales' => $totalSales * 0.4,
                'total_cost' => $totalCost * 0.4,
                'total_markup' => $totalMarkup * 0.4,
                'total_overhead' => $totalOverhead * 0.4,
                'total_tax' => $totalTax * 0.4,
                'total_gross_profit' => $totalGrossProfit * 0.4,
                'margin_percent' => round($marginPercent, 2),
                'last_updated' => now(),
            ]);
            
            // Customer type data
            $customerTypes = ['umum', 'anggota', 'karyawan'];
            $weights = [0.6, 0.25, 0.15]; // 60% umum, 25% anggota, 15% karyawan
            
            foreach ($customerTypes as $index => $type) {
                DB::table('daily_dashboard_aggregates')->insert([
                    'date' => $date->toDateString(),
                    'source' => 'TOTAL',
                    'customer_type' => $type,
                    'total_transactions' => rand(10, 50),
                    'total_quantity' => rand(30, 120),
                    'total_sales' => $totalSales * $weights[$index],
                    'total_cost' => $totalCost * $weights[$index],
                    'total_markup' => $totalMarkup * $weights[$index],
                    'total_overhead' => $totalOverhead * $weights[$index],
                    'total_tax' => $totalTax * $weights[$index],
                    'total_gross_profit' => $totalGrossProfit * $weights[$index],
                    'margin_percent' => round($marginPercent, 2),
                    'last_updated' => now(),
                ]);
            }
        }
        
        // Buat data product_pricings
        $products = DB::table('tbl_produk')
            ->where('aktif', 'Y')
            ->limit(10)
            ->get(['id_produk', 'harga_beli']);
            
        foreach ($products as $product) {
            $costPrice = (float) $product->harga_beli;
            if ($costPrice > 0) {
                foreach (['umum', 'anggota', 'karyawan'] as $customerType) {
                    $markupPercent = match($customerType) {
                        'umum' => 50,
                        'anggota' => 40,
                        'karyawan' => 30,
                        default => 50
                    };
                    
                    $markupAmount = $costPrice * ($markupPercent / 100);
                    $priceBeforeOverhead = $costPrice + $markupAmount;
                    $overheadAmount = $priceBeforeOverhead * 0.1; // 10% overhead
                    $priceBeforeTax = $priceBeforeOverhead + $overheadAmount;
                    $taxAmount = $priceBeforeTax * 0.11; // 11% pajak
                    $sellingPrice = $priceBeforeTax + $taxAmount;
                    
                    // Bulatkan ke ribuan
                    $sellingPrice = ceil($sellingPrice / 1000) * 1000;
                    
                    DB::table('product_pricings')->insert([
                        'product_id' => $product->id_produk,
                        'pricing_mode' => 'auto',
                        'customer_type' => $customerType,
                        'cost_price' => $costPrice,
                        'selling_price' => $sellingPrice,
                        'markup_percent' => $markupPercent,
                        'markup_amount' => $markupAmount,
                        'overhead_percent' => 10,
                        'overhead_amount' => $overheadAmount,
                        'tax_percent' => 11,
                        'tax_amount' => $taxAmount,
                        'effective_date' => now()->subDays(30)->toDateString(),
                        'is_active' => true,
                        'created_by' => 'system',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
        
        $this->command->info('✅ Markup data seeder berhasil dijalankan!');
        $this->command->info('📊 Data dummy untuk 7 hari terakhir telah dibuat.');
    }
}