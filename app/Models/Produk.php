<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'tbl_produk';
    protected $primaryKey = 'id_produk';

    protected $fillable = [
        'nama_produk',
        'deskripsi',
        'harga',
        'harga_anggota',
        'harga_karyawan',
        'harga_umum',
        'mode_harga',
        'stok',
        'satuanbesar',
        'satuankecil',
        'isi',
        'konsinyasi',
        'gambar',
        'kategori',
        'barcode',
        'harga_beli',
        'status',
        'min',
        'max',
        'aktif'
    ];

    public $timestamps = true;
    // =========================
    // RELASI
    // =========================

    public function kategoriRelation()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function satuanRelation()
    {
        return $this->belongsTo(Satuan::class, 'id_satuan');
    }

    public function hargaHistories()
    {
        return $this->hasMany(HargaHistory::class, 'id_produk');
    }

    // =========================
    // FUNGSI HITUNG MARKUP
    // =========================

    public function hitungMarkup()
    {
        if ($this->gunakan_markup == 'Y') {

            $markup = Markup::where('aktif', 'Y')->first();

            if (!$markup) {
                return $this->harga_manual ?: $this->harga_jual;
            }

            // hitung harga jual dari harga beli + persentase markup
            $persen = $markup->persentase; // misalnya 20 berarti 20%
            $harga = $this->harga_beli + ($this->harga_beli * $persen / 100);

            return intval($harga);
        }

        return $this->harga_manual ?: $this->harga_jual;
    }
}
