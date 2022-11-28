<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class KategoriProduk extends Model
{
    use HasFactory;

    protected $table = 'kategori_produk';
    protected $primaryKey = 'id';

    protected $fillable = [
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
        $result = DB::table(DB::raw('kategori_produk kp'))
            ->leftJoin(DB::raw('produk prod'), 'prod.kategori_produk_id', '=', 'kp.id')
            ->where('prod.kategori_produk_id', $id)
            ->whereNull('prod.deleted_at');

        $result = $result->first();
        return $result;
    }
}
