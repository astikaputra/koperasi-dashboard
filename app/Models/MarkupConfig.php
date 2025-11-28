<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarkupConfig extends Model
{
    protected $table = 'tb_markup_config';

    protected $fillable = [
        'metode_overhead',
        'pajak_persen',
        'bulatkan_ke'
    ];
}
