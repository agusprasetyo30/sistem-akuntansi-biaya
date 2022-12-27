<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QtyRenProd extends Model
{
    use HasFactory;
    protected $table = 'qty_renprod';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_code',
        'material_code',
        'version_id',
        'asumsi_umum_id',
        'qty_renprod_desc',
        'qty_renprod_value',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
