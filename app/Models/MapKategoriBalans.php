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

    public function material()
    {
        return $this->hasOne(Material::class, 'material_code', 'material_code');
    }

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

    public function const_rate(){
        return $this->hasMany(ConsRate::class, 'material_code', 'material_code');
    }

    public function glos_cc(){
        return $this->hasMany(GLosCC::class, 'material_code', 'material_code');
    }

    public function simulasi_proyeksi(){
        return $this->hasMany(SimulasiProyeksi::class, 'product_code', 'material_code');
    }

    public function get_data_qty_rencana_pengadaan($asumsi){
        $qty_rendaan = $this->qty_rencana_pengadaan->where('asumsi_umum_id', $asumsi)->sum('qty_rendaan_value');
        return $qty_rendaan;
    }

    public function get_data_qty_rencana_produksi($cost_center, $asumsi){
        $qty_produksi = $this->qty_rencana_produksi
            ->where('asumsi_umum_id', $asumsi)
            ->where('cost_center', $cost_center)->get();
        return $qty_produksi;
    }

    public function get_data_total_pengadaan($asumsi, $kurs, $adjustment){
        $qty_rendaan = (double) $this->qty_rencana_pengadaan->where('asumsi_umum_id', $asumsi)->sum('qty_rendaan_value');
        $price_rendaan = (double) $this->price_rencana_pengadaan->where('asumsi_umum_id', $asumsi)->sum('price_rendaan_value');

        if ($qty_rendaan > 0 && $price_rendaan == 0){
            $result = 0;
        }else{
            $result = $qty_rendaan * ($price_rendaan * (1 + ($adjustment / 100)) * $kurs);
        }
        return $result;
    }

    public function get_data_nilai_pamakaian($asumsi){
        $pemkaian = (double) $this->pemakaian->where('asumsi_umum_id', $asumsi)->sum('pj_pemakaian_value');
        $result = $pemkaian;
        return $result;
    }

    public function get_data_nilai_penjualan($asumsi){
        $penjualan = (double) $this->penjualan->where('asumsi_umum_id', $asumsi)->sum('pj_penjualan_value');

        $result = $penjualan;
        return $result;
    }

    public function get_data_cons_rate($material, $plant, $asumsi){
        $temp_plant = explode(' - ', $material->plant_code);

        $result = $this->const_rate()->with(['asumsi_umum' => function($query) use($asumsi){
            $query->where('id', $asumsi);
        }])
            ->where('material_code', $material->material_code)
            ->where('product_code', $temp_plant[2])
            ->where('plant_code', $plant)
            ->sum('cons_rate');
        return $result;
    }

    public function get_data_glos_cc($plant_code){
        $plant = explode(' - ', $plant_code);
        $result = $this->glos_cc()
            ->where('cost_center', $plant[0])->get();
        return $result;
    }

    public function get_data_qty_renprod($cost_center, $asumsi){
        $result = $this->glos_cc()
            ->with(['renprod' => function($query) use($cost_center, $asumsi){
                $query->where('cost_center', $cost_center)
                    ->where('asumsi_umum_id', $asumsi);
            }])
            ->where('cost_center', $cost_center)
            ->get();
        return $result;
    }

    public function get_data_simulasi($glos_cc, $asumsi){
        try {
            $result = $this->simulasi_proyeksi()
                ->where('cost_center', $glos_cc[0]->cost_center)
                ->where('plant_code', $glos_cc[0]->plant_code)
                ->where('asumsi_umum_id', $asumsi)
                ->where('code', '=', 'COGM')
                ->sum('biaya_perton');
        }catch (\Exception $exception){
            dd('model',$glos_cc, $asumsi, $exception);
        }
        return $result;
    }

}
