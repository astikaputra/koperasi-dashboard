<?php
// app/Models/TransactionMarkupTracking.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionMarkupTracking extends Model
{
    protected $table = 'transaction_markup_tracking';
    
    protected $fillable = [
        'source',
        'source_id',
        'product_id',
        'pricing_mode',
        'customer_type',
        'quantity',
        'transaction_date',
        'transaction_time',
        'cost_price',
        'selling_price',
        'markup_percent',
        'markup_amount',
        'overhead_percent',
        'overhead_amount',
        'tax_percent',
        'tax_amount',
        'total_cost',
        'total_sales',
        'total_markup',
        'total_overhead',
        'total_tax',
        'total_gross_profit',
        'pricing_reference_id'
    ];
    
    protected $casts = [
        'transaction_date' => 'date',
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'markup_percent' => 'decimal:2',
        'markup_amount' => 'decimal:2',
        'overhead_percent' => 'decimal:2',
        'overhead_amount' => 'decimal:2',
        'tax_percent' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'total_sales' => 'decimal:2',
        'total_markup' => 'decimal:2',
        'total_overhead' => 'decimal:2',
        'total_tax' => 'decimal:2',
        'total_gross_profit' => 'decimal:2',
    ];
    
    // Relationship dengan produk
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_produk');
    }
    
    // Relationship dengan pricing reference
    public function pricingReference()
    {
        return $this->belongsTo(ProductPricing::class, 'pricing_reference_id');
    }
    
    // Scope untuk hari ini
    public function scopeToday($query)
    {
        return $query->whereDate('transaction_date', today());
    }
    
    // Scope untuk bulan ini
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('transaction_date', now()->month)
                    ->whereYear('transaction_date', now()->year);
    }
}