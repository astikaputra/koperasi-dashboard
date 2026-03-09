<?php
// app/Models/DailyDashboardAggregate.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyDashboardAggregate extends Model
{
    protected $table = 'daily_dashboard_aggregates';
    
    // Nonaktifkan timestamps karena kita pakai last_updated
    public $timestamps = false;
    
    protected $fillable = [
        'date',
        'source',
        'customer_type',
        'total_transactions',
        'total_quantity',
        'total_sales',
        'total_cost',
        'total_markup',
        'total_overhead',
        'total_tax',
        'total_gross_profit',
        'margin_percent',
        'last_updated'
    ];
    
    protected $casts = [
        'date' => 'date',
        'total_sales' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'total_markup' => 'decimal:2',
        'total_overhead' => 'decimal:2',
        'total_tax' => 'decimal:2',
        'total_gross_profit' => 'decimal:2',
        'margin_percent' => 'decimal:2',
        'last_updated' => 'datetime'
    ];
    
    // Scope untuk data hari ini
    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }
    
    // Scope untuk source tertentu
    public function scopeSource($query, $source)
    {
        return $query->where('source', $source);
    }
    
    // Scope untuk customer type tertentu
    public function scopeCustomerType($query, $customerType)
    {
        return $query->where('customer_type', $customerType);
    }
}