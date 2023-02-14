<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimulasiProyeksi extends Model
{
    use HasFactory;
    protected $table = 'simulasi_proyeksi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'version_id',
        'plant_code',
        'product_code',
        'cost_center',
        'no',
        'kategori',
        'name',
        'code',
        'harga_satuan',
        'cr',
        'biaya_perton',
        'total_biaya',
        'asumsi_umum_id',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
