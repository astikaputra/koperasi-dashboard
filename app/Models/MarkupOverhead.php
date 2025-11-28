<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarkupOverhead extends Model
{
    protected $table = 'tb_markup_overhead';

    protected $fillable = [
        'bulan',
        'sewa_ruangan',
        'service_charge',
        'operasional'
    ];

    protected $casts = [
        'sewa_ruangan' => 'float',
        'service_charge' => 'float',
        'operasional' => 'float'
    ];
}
