<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MapKategoriBalans extends Model
{
    use HasFactory;
    protected $table = 'map_kategori_balans';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_code',
        'kategori_balans_id',
        'version_id',
        'material_code',
        'plant_code',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
