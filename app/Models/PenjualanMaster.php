<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualanMaster extends Model
{
    //protected $connection = 'db_ksa_q';
    protected $table = 'pj_penjualan_master';
    protected $primaryKey = 'id_penjualan_m';
    public $timestamps = false;

    protected $fillable = [
        'nomor_nota', 'tanggal', 'tgl', 'jam', 'grand_total',
        'bayar', 'type_bayar', 'keterangan_lain', 'isPosting',
        'id_pelanggan', 'tipe_pelanggan', 'id_user', 'metode_bayar'
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'tgl' => 'date',
        'grand_total' => 'decimal:2',
        'bayar' => 'decimal:2',
    ];

    // Scope untuk filter
    public function scopeHarian($query, $date = null)
    {
        $date = $date ?? now()->toDateString();
        return $query->where('tgl', $date);
    }

    public function scopeBulanan($query, $year = null, $month = null)
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;
        
        return $query->whereYear('tgl', $year)
                    ->whereMonth('tgl', $month);
    }

    public function scopeByTipePelanggan($query, $tipe = null)
    {
        if ($tipe) {
            return $query->where('tipe_pelanggan', $tipe);
        }
        return $query;
    }
}