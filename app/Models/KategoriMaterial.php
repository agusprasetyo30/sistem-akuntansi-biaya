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
}
