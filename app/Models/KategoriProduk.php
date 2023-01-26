<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function get_kategori($id)
    {
        $result = KategoriProduk::leftjoin('material', 'kategori_produk.id', '=', 'material.kategori_produk_id')
            ->where('material.kategori_produk_id', $id)
            ->whereNull('material.deleted_at')->first();

        return $result;
    }
}
