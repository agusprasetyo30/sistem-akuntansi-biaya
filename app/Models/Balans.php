<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balans extends Model
{
    use HasFactory;

    protected $table = 'balans';
    protected $fillable = [
        'material_code',
        'plant_code',
        'kategori_balans_id',
        'asumsi_umum_id',
        'q',
        'p',
        'nilai',
        'company_code',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
