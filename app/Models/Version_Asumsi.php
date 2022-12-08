<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Version_Asumsi extends Model
{
    use HasFactory;
    protected $table = 'version_asumsi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'version',
        'data_bulan',
        'created_at',
        'updated_at',
    ];
}
