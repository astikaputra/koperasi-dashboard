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
        'aktif',
        'markup_override',
        'minimum_price',
        'maximum_price'
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

        // Relationships
    public function pricings()
    {
        return $this->hasMany(ProductPricing::class, 'product_id', 'id_produk');
    }

    public function activePricing($customerType = 'umum')
    {
        return $this->hasOne(ProductPricing::class, 'product_id', 'id_produk')
            ->where('customer_type', $customerType)
            ->where('is_active', true)
            ->where('effective_date', '<=', now()->toDateString())
            ->latest('effective_date');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'kategori', 'id_kategori');
    }

    public function transactionTrackings()
    {
        return $this->hasMany(TransactionMarkupTracking::class, 'product_id', 'id_produk');
    }

    // Scopes
    public function scopeAutoPriced($query)
    {
        return $query->where('mode_harga', 'auto');
    }

    public function scopeManualPriced($query)
    {
        return $query->where('mode_harga', 'manual');
    }

    public function scopeActive($query)
    {
        return $query->where('aktif', 'Y');
    }

    // Helpers
    public function getCurrentPrice($customerType = 'umum')
    {
        if ($this->mode_harga === 'manual') {
            return match($customerType) {
                'anggota' => $this->harga_anggota,
                'karyawan' => $this->harga_karyawan,
                default => $this->harga_umum
            };
        }

        $pricing = $this->activePricing($customerType)->first();
        return $pricing ? $pricing->selling_price : 0;
    }
}
