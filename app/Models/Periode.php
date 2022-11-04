<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class periode extends Model
{
    use HasFactory;
    protected $table = 'periode';
    protected $primaryKey = 'id';

    protected $fillable = [
        'periode_name',
        'awal_periode',
        'akhir_periode',
        'is_active',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
