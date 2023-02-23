<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GLosCC extends Model
{
    use HasFactory;

    protected $table = 'glos_cc';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_code',
        'plant_code',
        'cost_center',
        'material_code',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];


}
