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
        'awal_periode',
        'akhir_periode',
        'saldo_awal',
        'company_code',
        'created_at',
        'updated_at',
    ];

    public function asumsi_umum(){
        return $this->hasMany(Asumsi_Umum::class, 'version_id', 'id');
    }
}
