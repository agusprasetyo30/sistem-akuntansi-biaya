<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Material extends Model
{
    use HasFactory;

    protected $table = 'material';
    protected $primaryKey = 'material_code';
    protected $keyType = 'string';

    protected $fillable = [
        'company_code',
        'material_code',
        'material_name',
        'material_desc',
        'kategori_material_id',
        'kategori_produk_id',
        'group_account_code',
        'material_uom',
        'is_active',
        'is_dummy',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];

    public function saldo_awal()
    {
        return $this->belongsTo(MapKategoriBalans::class, 'material_code', 'material_code');
    }

    public function balans()
    {
        return $this->hasOne(Balans::class, 'material_code', 'material_code');
    }

    public function zco()
    {
        return $this->hasMany(Zco::class, 'material_code', 'material_code');
    }

    public function const_rate()
    {
        return $this->hasMany(ConsRate::class, 'material_code', 'material_code');
    }

    public function glos_cc()
    {
        return $this->hasOne(GLosCC::class, ['material_code', 'plant_code'], ['product_code', 'plant_code']);
    }

    public function glos_cc1()
    {
        return $this->hasOne(GLosCC::class, ['material_code', 'plant_code'], ['product_code', 'plant_code']);
    }

    public function saldoawal()
    {
        return $this->hasMany(Saldo_Awal::class, 'material_code', 'material_code');
    }

    public function renprod()
    {
        return $this->hasMany(QtyRenProd::class, 'cost_center', 'cost_center');
    }

    public function gl_account_fc()
    {
        return $this->hasMany(GLAccountFC::class, 'group_account_fc', 'material_code');
    }

    public function tarif()
    {
        return $this->hasMany(Tarif::class, 'product_code', 'data_produk');
    }

    public function laba_rugi()
    {
        return $this->hasMany(LabaRugi::class, 'kategori_produk_id', 'kategori_produk');
    }

    public function kuantumProduksi($periode)
    {
        $renprod = $this->const_rate()->with('glos_cc1.renprod', function ($q) use ($periode) {
            $q->where('asumsi_umum_id', '=', $periode);
        })->first();

        $result = sizeof($renprod->glos_cc1->renprod);
        return $result;
    }

    public function kpValue($periode, $produk, $data_plant)
    {

        $renprod = $this->const_rate()->where('plant_code', $data_plant)->with('glos_cc1.renprod', function ($q) use ($periode) {
            $q->where('asumsi_umum_id', '=', $periode);
        })->where('product_code', '=', $produk)->first();

        // $renprod = $renprod->glos_cc->renprod;
        // $renprod = $renprod[0]->qty_renprod_value;
        // $result = isset($renprod->glos_cc->renprod->first()->qty_renprod_value) ? $renprod->glos_cc->renprod->first()->qty_renprod_value : 0;
        // $result = isset($qtyRenprodVal) ? $qtyRenprodVal : 0;
        $result = $renprod->glos_cc1->renprod->first()->qty_renprod_value ?? 0;

//        if ($produk == '2000002'){
//            dd($result, $renprod, $periode, $produk, $data_plant);
//        }
        // print_r($renprod->glos_cc->renprod->first() . '<br><br><br>');
        return $result;
    }

    public function consRate($version, $plant, $periode)
    {
        // dd($plant, $produk, $material);
        // $total_cr = $this->const_rate()->where([
        //     'plant_code' => $plant,
        //     'product_code' => $produk,
        //     'material_code' => $material
        // ])->get();

        $total_cr = $this->const_rate()
            ->where('version_id', $version)
            ->where('plant_code', $plant)
            ->where('month_year', $periode)
            ->sum('cons_rate');
        // dd($total_cr[0]['cons_rate']);
        // $cr = (float) $total_cr[0]['cons_rate'];
        // dd($total_cr);

        return $total_cr;
    }

    public function hsBalans($periode, $material, $produk)
    {
        if ($produk == $material) {
            return 0;
        } else {
            // $balans = $this->balans()->where([
            //     'material_code' => $material,
            //     'asumsi_umum_id' => $periode,
            //     'kategori_balans_id' => 3
            // ])->first();


            //            dd($periode, $material, $produk);
            // $balans = $this->balans()->where('asumsi_umum_id', $periode)->where('kategori_balans_id', 3)->where('material_code', $material)->get();     dd($periode, $material, $produk);
            $balans = $this->balans()
                ->where('asumsi_umum_id', $periode)
                ->where('kategori_balans_id', '=', 3)
                ->where('material_code', $material)
                ->first();

            //             if ($balans->isNotEmpty()) {
            //                 $res = (float) $balans->p ?? 0;
            //             } else {
            //                 $res = 0;
            //             }

            //            dd($balans, $this->balans());
            $res = 0;

            if ($balans) {
                $res = $balans->p;
            }

            return $res;
        }
    }

    public function hsZco($data_plant)
    {
        $total_qty = $this->zco()->where('plant_code', $data_plant)->sum('total_qty');
        $total_biaya = $this->zco()->where('plant_code', $data_plant)->sum('total_amount');

//        dd($total_qty);
        $kuantum_produksi = $this->zco()->select('product_qty', 'periode')->groupBy('product_qty', 'periode')->get()->toArray();

        $total_qty = (float) $total_qty;
        $total_biaya = (float) $total_biaya;
        // dd($kuantum_produksi);
        // $total_qty = (float) $this->zco()->where([
        //     'product_code' => $produk,
        //     'plant_code' => $plant,
        //     'material_code' => $material,
        // ])->sum('total_qty');

        // $total_biaya = (float) $this->zco()->where([
        //     'product_code' => $produk,
        //     'plant_code' => $plant,
        //     'material_code' => $material,
        // ])->sum('total_amount');


        // $kuantum_produksi = (float) $this->zco()->select('product_qty', 'periode')
        //     ->where([
        //         'product_code' => $produk,
        //         'plant_code' => $plant,
        //     ])
        //     ->groupBy('product_qty', 'periode');

        // $total_qty = $total_qty->first();
        // $total_biaya = $total_biaya->first();
        // $kuantum_produksi = $kuantum_produksi->get()->toArray();

        try {
            $tot_kuanprod = 0;
            for ($i = 0; $i < count($kuantum_produksi); $i++) {
                $tot_kuanprod += $kuantum_produksi[$i]['product_qty'];
            }

            $biaya_perton = 0;
            if ($total_biaya != 0 && $tot_kuanprod > 0) {
                $biaya_perton = $total_biaya / $tot_kuanprod;
            }

            $cr = 0;
            if ($total_qty != 0 && $tot_kuanprod > 0) {
                $cr = $total_qty / $tot_kuanprod;
            }

            $harga_satuan = 0;
            if ($cr != 0){
                $harga_satuan = $biaya_perton / $cr;
            }else{
                $harga_satuan = 0;
            }
        }catch (\Exception $exception){
            dd($exception);
        }

//        dd($harga_satuan);
        return $harga_satuan;
    }

    public function hsStock($version)
    {
        $total_sa = $this->saldoawal()->where('version_id', $version)->sum('total_value');
        $stok_sa = $this->saldoawal()->where('version_id', $version)->sum('total_stock');
        // dd($total_sa, $stok_sa);
        // $total_sa = $this->saldoawal()->where('material_code', $material)->where('version_id', $version)->sum('total_value');
        // $stok_sa = $this->saldoawal()->where('material_code', $material)->where('version_id', $version)->sum('total_stock');
        $biaya_stok = 0;
        if ($total_sa > 0 && $stok_sa > 0) {
            $biaya_stok = $total_sa / $stok_sa;
        }
        return $biaya_stok;
    }

    public function hsKantong($version)
    {
        $total_sa = $this->saldoawal()->where('version_id', $version)->sum('total_value');
        $stok_sa = $this->saldoawal()->where('version_id', $version)->sum('total_stock');
        // $total_sa = (float) $this->saldoawal()->where('material_code', $material)->where('version_id', $version)->sum('total_value');
        // $stok_sa = (float) $this->saldoawal()->where('material_code', $material)->where('version_id', $version)->sum('total_stock');

        $biaya_kantong = 0;
        if ($total_sa > 0 && $stok_sa > 0) {
            $biaya_kantong = $total_sa / $stok_sa;
        }

        return $biaya_kantong;
    }

    public function getSalr($data_cost_center, $group_account)
    {
        // dd($data_cost_center, $group_account);
        // $res_salr = $this->group_account_fc()->with('gl_account_fc.salr')->first();
        // $result = $res_salr->gl_account_fc;
        // $result = sizeof($salr->gl_account_fc->first()->salr);


        // $res_salr =  $this->group_account_fc()->with('gl_account_fc.salr')->first()->gl_account_fc;
        // dd($data_cost_center);
        // $res_salr = $this->gl_account_fc()->with('salr', function ($s) use ($data_cost_center) {
        //     $s->where('cost_center', $data_cost_center);
        // })->get();

        // $res_salr =  $this->group_account_fc()->with('gl_account_fc.salr', function ($query) use ($data_cost_center) {
        //     $query->where('cost_center', $data_cost_center);
        // })->where('group_account_fc', $group_account)->get();

        // $res_salr = $this->gl_account_fc()->with(['salr' => function ($query) use ($data_cost_center) {
        //     $query->where('cost_center', $data_cost_center);
        // }])->where('group_account_fc', $group_account)->get();


        $res_salr = $this->gl_account_fc()->with(['salr' => function ($query) use ($data_cost_center) {
            $query->where('cost_center', $data_cost_center);
        }])->where('group_account_fc', $group_account)->get();

        $result = $res_salr;
        // dd($result);
        return $result;
    }

    public function gaTarif($code, $plant)
    {
        $tarif = $this->tarif()->where('group_account_fc', $code)->where('plant_code', $plant)->first();

        $res = $tarif->tarif_value ?? 0;
        return $res;
    }

    public function getLabarugi()
    {
        $lb = $this->laba_rugi()->first();

        return $lb;
    }
}
