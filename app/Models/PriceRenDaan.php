<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceRenDaan extends Model
{
    use HasFactory;
    protected $table = 'price_rendaan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'material_id',
        'periode_id',
        'region_id',
        'price_rendaan_desc',
        'price_rendaan_value',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
