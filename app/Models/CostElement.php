<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostElement extends Model
{
    use HasFactory;
    protected $table = 'cost_element';
    protected $primaryKey = 'cost_element';

    protected $fillable = [
        'cost_element',
        'cost_element_desc',
        'company_code',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
