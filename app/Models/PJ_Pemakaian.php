<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PJ_Pemakaian extends Model
{
    use HasFactory;
    protected $table = 'pj_pemakaian';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_code',
        'material_code',
        'version_id',
        'asumsi_umum_id',
        'pj_pemakaian_value',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
