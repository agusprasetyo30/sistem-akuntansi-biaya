<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalDaan extends Model
{
    use HasFactory;
    protected $table = 'total_daan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'material_id',
        'periode_id',
        'region_id',
        'total_daan_desc',
        'total_daan_value',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
