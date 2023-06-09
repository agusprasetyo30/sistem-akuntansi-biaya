<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostCenter extends Model
{
    use HasFactory;
    protected $table = 'cost_center';
    protected $primaryKey = 'cost_center';
    protected $keyType = 'string';

    protected $fillable = [
        'cost_center',
        'cost_center_desc',
        'company_code',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
