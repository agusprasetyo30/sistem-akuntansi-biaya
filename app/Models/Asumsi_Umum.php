<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asumsi_Umum extends Model
{
    use HasFactory;
    protected $table = 'asumsi_umum';
    protected $primaryKey = 'id';

    protected $fillable = [
        'version_id',
        'usd_rate',
        'adjustment',
        'month_year',
        'inflasi',
        'saldo_awal',
        'company_code',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
