<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBalans extends Model
{
    use HasFactory;
    protected $table = 'kategori_balans';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_code',
        'kategori_balans',
        'kategori_balans_desc',
        'order_view',
        'type_kategori_balans',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
