<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsRate extends Model
{
    use HasFactory;
    use \Awobaz\Compoships\Compoships;
    protected $table = 'cons_rate';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_code',
        'product_code',
        'material_code',
        'version_id',
        'plant_code',
        'cons_rate',
        'month_year',
        'is_active',
        'status_pengajuan',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];

    public function glos_cc()
    {
        return $this->hasOne(GLosCC::class, 'material_code', 'product_code');
    }

    public function glos_cc1()
    {
        return $this->hasOne(GLosCC::class, ['material_code', 'plant_code'], ['product_code', 'plant_code']);
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_code', 'material_code');
    }

    public function asumsi_umum()
    {
        return $this->hasMany(Asumsi_Umum::class, 'month_year', 'month_year');
    }

    public function simulasi_proyeksi(){
        return $this->hasOne(SimulasiProyeksi::class, 'code', 'material_code');
    }
}
