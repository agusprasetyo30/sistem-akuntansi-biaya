<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balans extends Model
{
    use HasFactory;

    protected $table = 'balans';
    protected $primaryKey = 'id';

    protected $fillable = [
        'material_code',
        'plant_code',
        'kategori_balans_id',
        'asumsi_umum_id',
        'q',
        'p',
        'nilai',
        'type_kategori_balans',
        'kategori_balans_desc',
        'version_id',
        'order_view',
        'material_name',
        'month_year',
        'company_code',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];

    public function asumsi_umum()
    {
        return $this->hasOne(Asumsi_Umum::class, 'id', 'asumsi_umum_id');
    }

    public function kategori_balans()
    {
        return $this->hasOne(KategoriBalans::class, 'id', 'kategori_balans_id');
    }

    public function version_asumsi()
    {
        return $this->hasOne(Version_Asumsi::class, 'id', 'version_id');
    }
}
