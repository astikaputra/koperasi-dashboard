<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'tbl_detail_order';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'order_id', 'produk', 'qty', 'harga', 'posting',
        'tgl', 'tgl_close', 'jam', 'terima', 'keterangan'
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(OrderOnline::class, 'order_id', 'id');
    }
}