<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SimulasiProyeksi extends Model
{
    use HasFactory;
    protected $table = 'simulasi_proyeksi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'version_id',
        'plant_code',
        'product_code',
        'cost_center',
        'no',
        'kategori',
        'name',
        'code',
        'harga_satuan',
        'cr',
        'biaya_perton',
        'total_biaya',
        'asumsi_umum_id',
        'created_by',
        'created_at',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];

    public function hsBalans($periode, $material, $produk)
    {
        if ($produk == $material) {
            return 0;
        } else {
            $balans = DB::table("balans")
                ->where('balans.material_code', $material)
                ->where('balans.asumsi_umum_id', $periode)
                ->where('balans.kategori_balans_id', 3)
                ->first();

            $res = $balans->p ?? 0;

            return $res;
        }
    }

    public function hsZco($produk, $plant, $material)
    {
        $total_qty = Zco::select(DB::raw('SUM(total_qty) as total_qty'))
            ->where([
                'product_code' => $produk,
                'plant_code' => $plant,
                'material_code' => $material,
            ]);

        $total_biaya = Zco::select(DB::raw('SUM(total_amount) as total_amount'))
            ->where([
                'product_code' => $produk,
                'plant_code' => $plant,
                'material_code' => $material,
            ]);

        $kuantum_produksi = Zco::select(DB::raw('product_qty', 'periode'))
            ->where([
                'product_code' => $produk,
                'plant_code' => $plant,
            ])->groupBy('product_qty', 'periode');

        $total_qty = $total_qty->first();
        $total_biaya = $total_biaya->first();
        $kuantum_produksi = $kuantum_produksi->get()->toArray();

        $tot_kuanprod = 0;

        for ($i = 0; $i < count($kuantum_produksi); $i++) {
            $tot_kuanprod = $tot_kuanprod + $kuantum_produksi[$i]['product_qty'];
        }

        $biaya_perton = 0;
        if ($total_biaya->total_amount > 0 && $tot_kuanprod > 0) {
            $biaya_perton = $total_biaya->total_amount / $tot_kuanprod;
        }

        $cr = 0;
        if ($total_qty->total_qty > 0 && $tot_kuanprod > 0) {
            $cr = $total_qty->total_qty / $tot_kuanprod;
        }

        $harga_satuan = 0;
        if ($biaya_perton > 0 && $cr > 0) {
            $harga_satuan = $biaya_perton / $cr;
        }

        return $harga_satuan;
    }

    public function hsStock($material, $version)
    {
        $total_sa = Saldo_Awal::select(DB::raw('SUM(total_value) as total_value'))
            ->where([
                'material_code' => $material,
                'version_id' => $version,
            ])->first();

        $stok_sa = Saldo_Awal::select(DB::raw('SUM(total_stock) as total_stock'))
            ->where([
                'material_code' => $material,
                'version_id' => $version,
            ])->first();

        if ($total_sa->total_value > 0 && $stok_sa->total_stock > 0) {
            $biaya_stok = $total_sa->total_value / $stok_sa->total_stock;
        } else {
            $biaya_stok = 0;
        }

        return $biaya_stok;
    }

    public function hsKantong($material, $version)
    {
        $total_sa = Saldo_Awal::select(DB::raw('SUM(total_value) as total_value'))
            ->where([
                'material_code' => $material,
                'version_id' => $version,
            ])->first();

        $stok_sa = Saldo_Awal::select(DB::raw('SUM(total_stock) as total_stock'))
            ->where([
                'material_code' => $material,
                'version_id' => $version,
            ])->first();

        if ($total_sa->total_value > 0 && $stok_sa->total_stock > 0) {
            $biaya_kantong = $total_sa->total_value / $stok_sa->total_stock;
        } else {
            $biaya_kantong = 0;
        }

        return $biaya_kantong;
    }

    public function kuantumProduksi($cost_center, $periode)
    {
        $renprod = DB::table("qty_renprod")->where('qty_renprod.cost_center', $cost_center)
            ->where('qty_renprod.asumsi_umum_id', $periode)
            ->first();

        return $renprod;
    }

    public function consRate($plant, $produk, $material)
    {
        $total_cr = ConsRate::select(DB::raw('SUM(cons_rate) as cons_rate'))
            ->where([
                'cons_rate.plant_code' => $plant,
                'cons_rate.product_code' => $produk,
                'cons_rate.material_code' => $material
            ])->first();

        $cr = $total_cr->cons_rate;
        return $cr;
    }

    public function totalSalr($cost_center, $group_account, $inflasi)
    {
        $total = Salr::select(DB::raw('SUM(value) as value'))
            ->leftjoin('gl_account_fc', 'gl_account_fc.gl_account_fc', '=', 'salrs.gl_account_fc')
            ->leftjoin('group_account_fc', 'group_account_fc.group_account_fc', '=', 'gl_account_fc.group_account_fc')
            ->where([
                'salrs.cost_center' => $cost_center,
                'group_account_fc.group_account_fc' => $group_account
            ])->first();

        $result = $total->value * $inflasi / 100;
        return $result;
    }

    public function labaRugi($produk)
    {
        $lb = DB::table("laba_rugi")
            ->leftjoin('material', 'material.kategori_produk_id', '=', 'laba_rugi.kategori_produk_id')
            ->where('material.material_code', $produk)
            ->first();

        return $lb;
    }

    public function totalBB($data, $plant, $produk, $version, $periode, $cost_center)
    {
        $res_bb = [];

        foreach ($data as $key => $value) {
            $kp = SimulasiProyeksi::kuantumProduksi($cost_center, $periode);

            if ($kp) {
                if ($kp->qty_renprod_value == 1) {
                    $consrate_bb = 0;
                } else {
                    $consrate_bb = SimulasiProyeksi::consRate($plant, $produk, $value->code) ?? 0;
                }
            } else {
                $consrate_bb = 0;
            }

            if ($value->kategori == 1) {
                $hs_balans = SimulasiProyeksi::hsBalans($periode, $value->code, $produk);
                $biayaperton1 = $hs_balans * $consrate_bb;
                array_push($res_bb, $biayaperton1);
            } else if ($value->kategori == 2) {
                $hs_zco = SimulasiProyeksi::hsZco($produk, $plant, $value->code);
                $biayaperton2 = $hs_zco * $consrate_bb;
                array_push($res_bb, $biayaperton2);
            } else if ($value->kategori == 3) {
                $hs_stock = SimulasiProyeksi::hsStock($value->code, $version);
                $biayaperton3 = $hs_stock * $consrate_bb;
                array_push($res_bb, $biayaperton3);
            } else {
                $hs_kantong = SimulasiProyeksi::hsKantong($value->code, $version);
                $biayaperton4 = $hs_kantong * $consrate_bb;
                array_push($res_bb, $biayaperton4);
            }
        }

        $res = array_sum($res_bb);
        return $res;
    }

    public function totalGL($data, $cost_center, $asum_id, $asum_inflasi)
    {
        $res_gl = [];

        foreach ($data as $key => $value) {
            $salr = DB::table("salrs")
                ->leftjoin('gl_account_fc', 'gl_account_fc.gl_account_fc', '=', 'salrs.gl_account_fc')
                ->leftjoin('group_account_fc', 'group_account_fc.group_account_fc', '=', 'gl_account_fc.group_account_fc')
                ->where('salrs.cost_center', $cost_center)
                ->where('group_account_fc.group_account_fc', $value->code)
                ->first();

            if ($salr) {
                $kp = SimulasiProyeksi::kuantumProduksi($cost_center, $asum_id) ?? 0;
                //                dd($kp);
                $total = SimulasiProyeksi::totalSalr($salr->cost_center, $salr->group_account_fc, $asum_inflasi);

                $biaya_perton = 0;

                $biaya_perton = 0;
                if ($total > 0 && $kp != null) {
                    $biaya_perton = $total / $kp->qty_renprod_value;
                }
                array_push($res_gl, $biaya_perton);
            }
        }

        $res = array_sum($res_gl);
        return $res;
    }
}
