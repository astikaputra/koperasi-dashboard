<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderOnline extends Model
{
    protected $table = 'tbl_order';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'tanggal', 'tgl_close', 'jam', 'nik', 'deposit',
        'total_order', 'status', 'order_online', 'keterangan1', 'keterangan2'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tgl_close' => 'date',
        'deposit' => 'decimal:2',
        'total_order' => 'decimal:2',
    ];

    // Scope untuk filter
    public function scopeHarian($query, $date = null)
    {
        $date = $date ?? now()->toDateString();
        return $query->where('tanggal', $date);
    }

    public function scopeBulanan($query, $year = null, $month = null)
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;
        
        return $query->whereYear('tanggal', $year)
                    ->whereMonth('tanggal', $month);
    }

    public function scopeByStatus($query, $status = null)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'CLOSE');
    }
}