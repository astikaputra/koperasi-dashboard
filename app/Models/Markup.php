<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Markup extends Model
{
    use HasFactory;

    protected $table = 'tb_markup';
    protected $primaryKey = 'id';

    protected $fillable = [
        'tipe',
        'persen',
        'aktif'
    ];
}
