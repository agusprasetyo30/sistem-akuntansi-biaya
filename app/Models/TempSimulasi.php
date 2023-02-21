<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempSimulasi extends Model
{

    use HasFactory;
    protected $table = 'temp_proyeksi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'proyeksi_no',
        'proyeksi_name',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
