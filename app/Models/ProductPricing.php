<?php
// app/Models/ProductPricing.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPricing extends Model
{
    protected $table = 'product_pricings';
    
    protected $fillable = [
        'product_id',
        'pricing_mode',
        'customer_type',
        'manual_price',
        'cost_price',
        'selling_price',
        'markup_percent',
        'markup_amount',
        'overhead_percent',
        'overhead_amount',
        'tax_percent',
        'tax_amount',
        'effective_date',
        'is_active',
        'created_by'
    ];
    
    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'manual_price' => 'decimal:2',
        'markup_percent' => 'decimal:2',
        'markup_amount' => 'decimal:2',
        'overhead_percent' => 'decimal:2',
        'overhead_amount' => 'decimal:2',
        'tax_percent' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'effective_date' => 'date',
        'is_active' => 'boolean'
    ];
    
    // Relationship dengan produk
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_produk');
    }
    
    // Scope untuk harga aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    // Scope untuk tanggal efektif
    public function scopeEffectiveDate($query, $date = null)
    {
        $date = $date ?? now();
        return $query->where('effective_date', '<=', $date);
    }
    
    // Scope untuk tipe customer
    public function scopeCustomerType($query, $customerType)
    {
        return $query->where('customer_type', $customerType);
    }
}