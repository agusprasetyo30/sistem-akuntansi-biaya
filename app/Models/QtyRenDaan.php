<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QtyRenDaan extends Model
{
    use HasFactory;
    protected $table = 'qty_rendaan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'material_id',
        'periode_id',
        'region_id',
        'qty_rendaan_desc',
        'qty_rendaan_value',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
