<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsRate extends Model
{
    use HasFactory;
    protected $table = 'cons_rate';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_code',
        'material_code',
        'version',
        'plant_code',
        'cons_rate',
        'is_active',
        'month_year',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
