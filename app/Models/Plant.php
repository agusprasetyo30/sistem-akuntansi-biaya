<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Plant extends Model
{
    use HasFactory;

    protected $table = 'plant';
    protected $primaryKey = 'plant_code';
    protected $keyType = 'string';

    protected $fillable = [
        'plant_code',
        'plant_desc',
        'is_active',
        'company_code',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
