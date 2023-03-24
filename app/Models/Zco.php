<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zco extends Model
{
    use HasFactory;
    protected $table = 'zco';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_code',
        'version_id',
        'plant_code',
        'periode',
        'product_code',
        'product_qty',
        'cost_element',
        'material_code',
        'total_qty',
        'currency',
        'total_amount',
        'unit_price_product',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
