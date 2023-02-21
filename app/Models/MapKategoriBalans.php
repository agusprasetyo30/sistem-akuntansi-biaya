<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MapKategoriBalans extends Model
{
    use HasFactory;
    protected $table = 'map_kategori_balans';
    protected $primaryKey = 'id';

    protected $fillable = [
        'company_code',
        'kategori_balans_id',
        'version_id',
        'material_code',
        'plant_code',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];

    public function saldo_awal()
    {
        return $this->hasMany(Saldo_Awal::class, 'material_code', 'material_code');
    }

    public function kategori_balans(){
        return $this->hasOne(KategoriBalans::class, 'id', 'kategori_balans_id');
    }

    public function qty_rencana_pengadaan(){
        return $this->hasMany(QtyRenDaan::class, 'material_code', 'material_code');
    }

    public function price_rencana_pengadaan(){
        return $this->hasMany(PriceRenDaan::class, 'material_code', 'material_code');
    }

    public function pemakaian(){
        return $this->hasMany(PJ_Pemakaian::class, 'material_code', 'material_code');
    }

    public function penjualan(){
        return $this->hasMany(PJ_Penjualan::class, 'material_code', 'material_code');
    }

    public function get_data_qty_rencana_pengadaan($asumsi){
        $qty_rendaan = $this->qty_rencana_pengadaan()->where('asumsi_umum_id', $asumsi)->get();
        return $qty_rendaan;
    }

    public function get_data_total_pengadaan($asumsi, $kurs, $adjustment){
        $qty_rendaan = (double) $this->qty_rencana_pengadaan()->where('asumsi_umum_id', $asumsi)->sum('qty_rendaan_value');
        $price_rendaan = (double) $this->price_rencana_pengadaan()->where('asumsi_umum_id', $asumsi)->sum('price_rendaan_value');

        if ($qty_rendaan > 0 && $price_rendaan == 0){
            $result = 0;
        }else{
            $result = $qty_rendaan * ($price_rendaan * (1 + ($adjustment / 100)) * $kurs);
        }
        return $result;
    }

    public function get_data_nilai_pakai_jual($asumsi){
        $pemkaian = (double) $this->pemakaian()->where('asumsi_umum_id', $asumsi)->sum('pj_pemakaian_value');
        $penjualan = (double) $this->penjualan()->where('asumsi_umum_id', $asumsi)->sum('pj_penjualan_value');

        $result = $pemkaian + $penjualan;
        return $result;
    }




}
