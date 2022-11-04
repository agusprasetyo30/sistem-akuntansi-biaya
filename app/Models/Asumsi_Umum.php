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
        'periode_id',
        'kurs',
        'handling_bb',
//        'data_saldo_awal',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];
}
