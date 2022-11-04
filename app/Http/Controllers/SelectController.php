<?php

namespace App\Http\Controllers;

use App\Models\KategoriMaterial;
use App\Models\KategoriProduk;
use App\Models\Material;
use App\Models\periode;
use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SelectController extends Controller
{
    public function plant(Request $request){
        $search = $request->search;
//        dd($request);
        if($search == ''){
            $plant = Plant::limit(10)
                ->get();

        }else{
            $plant = Plant::where('plant_code', 'ilike', '%'.$search.'%')
                ->limit(10)
                ->get();
        }

        $response = array();
        foreach($plant as $items){
            $response[] = array(
                "id"=>$items->id,
                "text"=>$items->plant_code
            );
        }
        
        return response()->json($response);
    }

    public function periode(Request $request){
        $search = $request->search;
//        dd($request);
        if($search == ''){
            $plant = Periode::limit(10)
                ->get();

        }else{
            $plant = periode::where('periode_name', 'ilike', '%'.$search.'%')
                ->limit(10)
                ->get();
        }

        $response = array();
        foreach($plant as $items){
            $response[] = array(
                "id"=>$items->id,
                "text"=>$items->periode_name
            );
        }

        return response()->json($response);
    }

    public function kategori_material(Request $request){
        $search = $request->search;
        if($search == ''){
            $kategori_material = KategoriMaterial::limit(10)
                ->where('is_active','t')
                ->get();

        }else{
            $kategori_material = KategoriMaterial::where('kategori_material_name', 'ilike', '%'.$search.'%')
                ->limit(10)
                ->where('is_active','t')
                ->get();
        }

        $response = array();
        foreach($kategori_material as $items){
            $response[] = array(
                "id"=>$items->id,
                "text"=>$items->kategori_material_name
            );
        }

        return response()->json($response);
    }

    public function kategori_produk(Request $request){
        $search = $request->search;
        if($search == ''){
            $kategori_produk = KategoriProduk::limit(10)
                ->where('is_active','t')
                ->get();

        }else{
            $kategori_produk = KategoriProduk::where('kategori_produk_name', 'ilike', '%'.$search.'%')
                ->limit(10)
                ->where('is_active','t')
                ->get();
        }

        $response = array();
        foreach($kategori_produk as $items){
            $response[] = array(
                "id"=>$items->id,
                "text"=>$items->kategori_produk_name
            );
        }

        return response()->json($response);
    }

    public function material(Request $request){
        $search = $request->search;
        if($search == ''){
            $material = Material::limit(10)
                ->where('is_active','t')
                ->get();

        }else{
            $material = Material::where('material_name', 'ilike', '%'.$search.'%')
                ->limit(10)
                ->where('is_active','t')
                ->get();
        }

        $response = array();
        foreach($material as $items){
            $response[] = array(
                "id"=>$items->id,
                "text"=>$items->material_name
            );
        }

        return response()->json($response);
    }
}
