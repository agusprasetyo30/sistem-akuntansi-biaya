<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriProduk extends Model
{
    protected $table = 'kategori_produk';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_code',
        'kategori_produk_name',
        'kategori_produk_desc',
        'is_active',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
