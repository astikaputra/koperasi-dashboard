<?php
// app/Models/PricingChangeLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingChangeLog extends Model
{
    protected $table = 'pricing_change_logs';
    
    protected $fillable = [
        'action',
        'product_id',
        'mode',
        'category_id',
        'old_value',
        'new_value',
        'changed_by'
    ];
    
    protected $casts = [
        'old_value' => 'array',
        'new_value' => 'array',
        'created_at' => 'datetime'
    ];
    
    // Relationship dengan produk
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_produk');
    }
}