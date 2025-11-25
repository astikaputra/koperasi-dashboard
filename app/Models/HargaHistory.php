<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaHistory extends Model
{
    use HasFactory;

    protected $table = 'tb_harga_history';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_produk',

        'old_harga',
        'old_harga_anggota',
        'old_harga_karyawan',
        'old_harga_umum',

        'new_harga',
        'new_harga_anggota',
        'new_harga_karyawan',
        'new_harga_umum',

        'updated_by',
    ];

    /**
     * Relasi ke produk
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }

    /**
     * Relasi user admin yang mengupdate
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
