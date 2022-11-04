<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldo_Awal extends Model
{
    use HasFactory;
    protected $table = 'saldo_awal';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_code',
        'gl_account',
        'valuation_class',
        'price_control',
        'material_id',
        'plant_id',
        'total_stock',
        'total_value',
        'nilai_satuan',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}