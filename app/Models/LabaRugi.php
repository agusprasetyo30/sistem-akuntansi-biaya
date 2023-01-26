<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabaRugi extends Model
{
    use HasFactory;
    protected $table = 'laba_rugi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_code',
        'periode',
        'kategori_produk_id',
        'value_bp',
        'value_bau',
        'value_bb',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
