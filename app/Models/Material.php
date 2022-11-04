<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Material extends Model
{
    use HasFactory;

    protected $table = 'material';
    protected $primaryKey = 'id';

    protected $fillable = [
        'material_name',
        'material_desc',
        'kategori_material_id',
        'uom',
        'is_active',
        'is_dummy',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
