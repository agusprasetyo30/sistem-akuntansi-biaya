<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;
    protected $table = 'feature';
    protected $primaryKey = 'kode_unik';
    protected $keyType = 'string';

    protected $fillable = [
        'kode_unik',
        'feature',
        'feature_name',
        'db',
        'created_at',
        'updated_at',
    ];

    public function kurs(){
        return $this->hasMany(Kurs::class, 'kode_feature', 'kode_unik');
    }

    public function version(){
        return $this->hasMany(Version_Asumsi::class, 'kode_feature', 'kode_unik');
    }

    public function asumsi_umum(){
        return $this->hasMany(Asumsi_Umum::class, 'kode_feature', 'kode_unik');
    }

    public function cons_rate(){
        return $this->hasMany(ConsRate::class, 'kode_feature', 'kode_unik');
    }

    public function saldo_awal(){
        return $this->hasMany(Saldo_Awal::class, 'kode_feature', 'kode_unik');
    }

    public function qty_renprod(){
        return $this->hasMany(QtyRenProd::class, 'kode_feature', 'kode_unik');
    }

    public function qty_rendaan(){
        return $this->hasMany(QtyRenDaan::class, 'kode_feature', 'kode_unik');
    }

    public function price_rendaan(){
        return $this->hasMany(PriceRenDaan::class, 'kode_feature', 'kode_unik');
    }

    public function zco(){
        return $this->hasMany(Zco::class, 'kode_feature', 'kode_unik');
    }

    public function salr(){
        return $this->hasMany(Salr::class, 'kode_feature', 'kode_unik');
    }

    public function laba_rugi(){
        return $this->hasMany(LabaRugi::class, 'kode_feature', 'kode_unik');
    }

    public function pj_pemakaian(){
        return $this->hasMany(PJ_Pemakaian::class, 'kode_feature', 'kode_unik');
    }

    public function pj_penjualan(){
        return $this->hasMany(PJ_Penjualan::class, 'kode_feature', 'kode_unik');
    }

    public function tarif(){
        return $this->hasMany(Tarif::class, 'kode_feature', 'kode_unik');
    }

}
