<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class KategoriMaterial extends Model
{
    use HasFactory;

    protected $table = 'kategori_material';
    protected $primaryKey = 'id';

    protected $fillable = [
        'kategori_material_name',
        'kategori_material_desc',
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
        $result = DB::table(DB::raw('kategori_material km'))
            ->leftJoin(DB::raw('material mat'), 'mat.kategori_material_id', '=', 'km.id')
            ->where('mat.kategori_material_id', $id)
            ->whereNull('mat.deleted_at');

        $result = $result->first();
        return $result;
    }
}
