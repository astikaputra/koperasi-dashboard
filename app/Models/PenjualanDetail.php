<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    //protected $connection = 'db_ksa_q';
    protected $table = 'pj_penjualan_detail';
    protected $primaryKey = 'id_penjualan_d';
    public $timestamps = false;

    protected $fillable = [
        'id_penjualan_m', 'id_barang', 'jumlah_beli',
        'harga_satuan', 'total', 'tgl', 'jam', 'user'
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function master()
    {
        return $this->belongsTo(PenjualanMaster::class, 'id_penjualan_m', 'id_penjualan_m');
    }
}