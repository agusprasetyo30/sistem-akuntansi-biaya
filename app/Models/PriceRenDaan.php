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
        'company_code',
        'material_code',
        'region_name',
        'version_id',
        'asumsi_umum_id',
        'price_rendaan_value',
        'type_currency',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
