<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'tbl_kategori';
    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = ['nama_kategori'];
}
