<?php

use App\Models\ConsRate;
use App\Models\Material;
use App\Models\Salr;
use App\Models\Zco;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use App\Models\Asumsi_Umum;
use App\Models\Version_Asumsi;
use App\Models\Saldo_Awal;
use App\Models\QtyRenDaan;
// use Image;

function secureToken()
{
    return hash('sha256', mt_rand(10000, 99999) . time() . md5(time()));
}

function secretNumber()
{
    return mt_rand(10000, 99999) . time();
}

function uniquecode()
{
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 6);
}

function clean($string = null)
{
    return ($string) ? strip_tags($string) : '';
}

function stamp()
{
    return date('Y-m-d H:i:s');
}

function isImage($file = NULL)
{
    $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];
    $contentType = mime_content_type($file);

    if (!in_array($contentType, $allowedMimeTypes)) {
        return 0;
    } else {
        return 1;
    }
}

function imgSizes()
{
    return [
        'thumbnail' => [
            150, 150
        ],
        'medium' => [
            300, 300
        ],
        'medium_large' => [
            768, 768
        ],
        'large' => [
            1024, 1024
        ],
    ];
}

function doUpload($data = NULL)
{
    if ($data == NULL) {
        return FALSE;
    } else {
        if (!is_array($data)) {
            return FALSE;
        } else {
            $msg = '';

            $file = (isset($data['file'])) ? $data['file'] : $msg .= '<p>File not found.</p>';
            $path = (isset($data['path'])) ? $data['path'] : $msg .= '<p>Path is not defined.</p>';
            $type = (isset($data['allow_type'])) ? $data['allow_type'] : '';
            $size = (isset($data['allow_size'])) ? $data['allow_size'] : '';

            if ($msg != '') {
                return $msg;
            } else {

                $fileOriginalName = $file->getClientOriginalName();
                $fileExtension = $file->guessExtension();
                $fileSize = $file->getSize();
                $fileRealPath = $file->getRealPath();

                $isImage = isImage($fileRealPath);

                if ($type != '') {
                    $allowedType = explode('|', $type);
                    if (!in_array(mime_content_type($fileRealPath), $allowedType)) {
                        return [
                            'response' => 'error',
                            'code' => 406,
                            'message' => '<p>File yang anda upload tidak diizinkan.</p>'
                        ];

                        exit;
                    }
                }

                if ($size != '') {
                    $allowedSize = $size * 1000;
                    if ($fileSize > $allowedSize) {
                        return [
                            'response' => 'error',
                            'code' => 406,
                            'message' => '<p>File yand anda upload melebihi batas maksimal yang diizinkan.</p>'
                        ];

                        exit;
                    }
                }

                $newFileName = date('Ymd') . '_' . md5(uniqid(time() . $fileOriginalName, true));

                $imgSize = imgSizes();
                $otherSizes = [];

                foreach ($imgSize as $key => $val) {
                    $image = $data['file'];
                    $imageName = $newFileName . '_' . $key . '.' . $fileExtension;
                    $filePath = 'uploads';

                    $img = Image::make($image->path());
                    $img->resize($val[0], $val[1], function ($const) {
                        $const->aspectRatio();
                    })->save($filePath . '/' . $imageName);

                    $otherSizes[] = [
                        'originName' => $image->getClientOriginalName(),
                        'randomName' => $imageName,
                        'path' => URL::to('/') . '/' . $path . $imageName,
                        'ext' => $image->guessExtension(),
                        'size' => $image->getSize(),
                        'isImage' => $isImage
                    ];
                }

                $file->move($path, $newFileName . '.' . $fileExtension);

                return [
                    'response' => 'ok',
                    'code' => 200,
                    'message' => '<p>File berhasil di upload.</p>',
                    'data' => [
                        'originName' => $fileOriginalName,
                        'randomName' => $newFileName,
                        'path' => URL::to('/') . '/' . $path . $newFileName,
                        'ext' => $fileExtension,
                        'size' => $fileSize,
                        'isImage' => $isImage,
                        'otherSizes' => $otherSizes
                    ]
                ];
            }
        }
    }
}

function limitChar($string = null, $limit = 100)
{
    if (strlen($string) > $limit) {

        $stringCut = substr($string, 0, $limit);
        $endPoint = strrpos($stringCut, ' ');

        $string = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
        $string .= '...';
    }
    return $string;
}

function splitMonth($date = null, $lang = 'en')
{
    if ($date) {
        $en = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
        $id = ['jan', 'feb', 'mar', 'apr', 'mei', 'jun', 'jul', 'agus', 'sep', 'okt', 'nov', 'des'];

        $month = date('m', strtotime($date));

        switch ($month) {
            case $month:
                return ($lang == 'en') ? $en[$month - 1] : $id[$month - 1];
                break;
            default:
                return '-';
        }
    } else {
        return $date;
    }
}

function helpIndoDay($var)
{
    $dayArray = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
    if (array_key_exists($var, $dayArray)) {
        return $dayArray[$var];
    } else {
        return 'Undefined';
    }
}

function helpIndoMonth($num)
{
    $monthArray = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
    if (array_key_exists($num, $monthArray)) {
        return $monthArray[$num];
    } else {
        return 'Undefined';
    }
}

function helpDate($var, $mode = 'se')
{
    switch ($mode) {
        case 'se':
            return date('Y-m-d', strtotime($var));
            break;
        case 'si':
            return date('d-m-Y', strtotime($var));
            break;
        case 'me':
            return date('F d, Y', strtotime($var));
            break;
        case 'mi':
            $day = date('d', strtotime($var));
            $month = date('n', strtotime($var));
            $year = date('Y', strtotime($var));

            $month = helpIndoMonth($month - 1);
            return $day . ' ' . $month . ' ' . $year;
            break;
        case 'le':
            return date('l F d, Y', strtotime($var));
            break;
        case 'li':
            $dow = date('w', strtotime($var));
            $day = date('d', strtotime($var));
            $month = date('n', strtotime($var));
            $year = date('Y', strtotime($var));

            $hari = helpIndoDay($dow);
            $month = helpIndoMonth($month - 1);
            return $hari . ', ' . $day . ' ' . $month . ' ' . $year;
            break;
        case 'bi':
            $month = date('n', strtotime($var));
            $year = date('Y', strtotime($var));

            $month = helpIndoMonth($month - 1);
            return $year . ' ' . $month;
            break;
        default:
            return 'Undefined';
            break;
    }
}

if (!function_exists("isNullOrEmptyString")) {
    function isNullOrEmptyString($str)
    {
        return (!isset($str) || trim($str) === "");
    }
}

if (!function_exists('status_is_active')) {
    function status_is_active()
    {
        $status = array(
            true => 'Aktif',
            false => 'Tidak Aktif',

        );

        return $status;
    }
}

if (!function_exists('status_dt')) {
    function status_dt()
    {
        $status = array(
            'all' => 'Semua',
            true => 'Aktif',
            false => 'Tidak Aktif',

        );

        return $status;
    }
}

if (!function_exists('status_is_dummy')) {
    function status_is_dummy()
    {
        $status = array(
            true => 'Iya',
            false => 'Tidak',

        );

        return $status;
    }
}

if (!function_exists('dummy_dt')) {
    function dummy_dt()
    {
        $status = array(
            'all' => 'Semua',
            true => 'Iya',
            false => 'Tidak',

        );

        return $status;
    }
}

if (!function_exists('value_dt')) {
    function value_dt()
    {
        $status = array(
            '0' => 'Semua',
            '1' => 'With Price',
            '2' => 'No Price',

        );

        return $status;
    }
}

if (!function_exists('format_salr')) {
    function format_salr()
    {
        $status = array(
            '0' => '1 Tahun',
            '1' => '1 Bulan',
            '2' => 'Custom',
        );
        return $status;
    }
}


if (!function_exists('format_zco')) {
    function format_zco()
    {
        $status = array(
            'all' => 'Semua',
            '0' => '1 Bulan',
            '1' => 'Custom',
        );
        return $status;
    }
}

if (!function_exists('login_method')) {
    function login_method()
    {
        $login_method = array(
            'DB' => 'DATABASE',
            'SSO' => 'SSO',
        );

        return $login_method;
    }
}

if (!function_exists('mata_uang')) {
    function mata_uang()
    {
        $mata_uang = array(
            'IDR' => 'Rupiah',
            'USD' => 'Dollar',
        );

        return $mata_uang;
    }
}

if (!function_exists('rupiah')) {
    function rupiah($angka)
    {

        $hasil_rupiah = "Rp " . number_format($angka, 0, ',', '.');
        return $hasil_rupiah;
    }
}

if (!function_exists('check_month')) {
    function check_month($periode)
    {
        $monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        $data = str_split($periode);

        //        dd(count($data));
        if (count($data) == 1) {
            return $monthNames[$periode];
        } else {
            if ($data[0] == 0) {
                return $monthNames[$data[1]];
            } else {
                return $monthNames[$periode];
            }
        }
    }
}

if (!function_exists('format_month')) {
    function format_month($date, $method = 'def')
    {
        switch ($method) {
            case 'def':
                $data = Carbon::parse($date)->format('F-Y');
                return $data;
                break;
            case 'se':
                $data = Carbon::parse($date)->format('m-Y');
                return $data;
                break;
            case 'bi':
                $data = Carbon::parse($date)->format('m/Y');
                return $data;
                break;
            case 'fe':
                $data = Carbon::parse($date)->format('Y-m-d');
                return $data;
                break;
            case 'ye':
                $data = Carbon::parse($date)->format('Y-m');
                return $data;
                break;
            default:
                return 'Undefined';
                break;
        }
    }
}

if (!function_exists('format_year')) {
    function format_year($date, $method = 'def')
    {
        switch ($method) {
            case 'def':
                $data = Carbon::parse($date)->format('Y');
                return $data;
                break;
            default:
                return 'Undefined';
                break;
        }
    }
}


if (!function_exists('helpRupiah')) {
    function helpRupiah($angka)
    {
        $hasil_rupiah = "Rp " . number_format($angka, 2, ',', '.');
        return $hasil_rupiah;
    }
}

if (!function_exists('mapping_plant')) {
    function mapping_plant($product)
    {
        $plant = 'all';

        if ($product == '2000000') {
            $plant = 'B018 - Plant Utilitas II';
        } elseif ($product == '2000001') {
            $plant = 'B018 - Plant Utilitas II';
        } elseif ($product == '2000002') {
            $plant = 'B031 - Plant Utilitas IIIA';
        }

        return $plant;
    }
}

if (!function_exists('mapping_plant_insert')) {
    function mapping_plant_insert($material_code)
    {
        $data_plant = mapping_plant($material_code);

        $versi = Version_Asumsi::get();
        foreach ($versi as $data) {
            $mapping = [
                [
                    'material_code' => strtoupper($material_code),
                    'kategori_balans_id' => 1,
                    'version_id' => $data->id,
                    'plant_code' => $data_plant,
                    'company_code' => auth()->user()->company_code,
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'material_code' => strtoupper($material_code),
                    'kategori_balans_id' => 2,
                    'version_id' => $data->id,
                    'plant_code' => 'B601 - Pergudangan dan Pemeliharaan',
                    'company_code' => auth()->user()->company_code,
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'material_code' => strtoupper($material_code),
                    'kategori_balans_id' => 3,
                    'version_id' => $data->id,
                    'plant_code' => $data_plant,
                    'company_code' => auth()->user()->company_code,
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'material_code' => strtoupper($material_code),
                    'kategori_balans_id' => 4,
                    'version_id' => $data->id,
                    'plant_code' => $data_plant,
                    'company_code' => auth()->user()->company_code,
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'material_code' => strtoupper($material_code),
                    'kategori_balans_id' => 5,
                    'version_id' => $data->id,
                    'plant_code' => $data_plant,
                    'company_code' => auth()->user()->company_code,
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ];
            DB::table('map_kategori_balans')->insert($mapping);
            //            array_push($data_ready, $mapping);
        }
    }
}

if (!function_exists('find_lower_material')) {
    function find_lower_material($versi)
    {
        $material_consrate = ConsRate::select('cons_rate.material_code')
            ->leftjoin('material', 'material.material_code', '=', 'cons_rate.material_code')
            ->where([
                'cons_rate.version_id' => $versi,
                'material.kategori_material_id' => 1
            ])
            ->groupBy('cons_rate.material_code')
            ->get();

        $product_consrate = ConsRate::select('cons_rate.product_code')
            ->leftjoin('material', 'material.material_code', '=', 'cons_rate.product_code')
            ->where([
                'cons_rate.version_id' => $versi,
                'material.kategori_material_id' => 1
            ])
            ->groupBy('cons_rate.product_code')
            ->get();

        $result = array_diff($material_consrate->pluck('material_code')->all(), $product_consrate->pluck('product_code')->all());

        return $result;
    }
}

//if (!function_exists('antrian_material_balans')) {
//    function antrian_material_balans($versi)
//    {
//        $resulty = [];
//        $material_balans = ConsRate::leftjoin('material', 'material.material_code', '=', 'cons_rate.material_code')
//            ->where([
//                'material.kategori_material_id' => 1,
//                'cons_rate.deleted_at' => null,
//            ])
//            ->get();
//
//        $data = find_lower_material($versi);
//
//        foreach ($data as $items){
//            $check = true;
//            $temp = [];
//            $i = 0;
//            while ($check){
//                if ($i == 0){
//                    $material = $items;
//                    array_push($temp, $material);
//                }
//                $produk = $material_balans->where('material_code',$material)->pluck('product_code')->all();
//
//                $count = count($produk);
//                if ($count == 1){
//                    $material = $produk[0];
//                    array_push($temp, $material);
//                }elseif ($count > 1){
//                    foreach ($produk as $items1){
//                        $temp_data = temp_material_produk($items1, $material_balans);
//                        $check_temp = count($temp_data);
//
//                        if ($check_temp == 1){
//                            if ($temp_data['status'] != 'end'){
//                                array_push($temp, $items1);
//                                array_push($temp, $temp_data['material']);
//                            }else{
//                                array_push($temp, $temp_data['material']);
//                            }
//                        }elseif ($check_temp > 1){
//                            foreach ($temp_data as $items2){
//                                if ($temp_data['status'] != 'end'){
//                                    array_push($temp, $items1);
//                                    array_push($temp, $temp_data['material']);
//                                }else{
//                                    array_push($temp, $temp_data['material']);
//                                }
//                            }
//                        }
//                    }
//                    $check = false;
//                }elseif ($count == 0){
//                    $check = false;
//                }
//                $i++;
//                var_dump($i);
//            }
////            dd($temp);
//            array_push($resulty, $temp);
//        }
//
//        return $resulty;
//    }
//}
//
//if (!function_exists('temp_material_produk')) {
//    function temp_material_produk($items1, $material_balans)
//    {
//        $produk = $material_balans->where('material_code',$items1)->pluck('product_code')->all();
//
//        $r = [];
//        $count = count($produk);
//        if ($count == 1){
//            $result['material'] = $produk[0];
//            $result['status'] = 'low';
//        }elseif ($count > 1){
//            foreach ($produk as $items2){
//                $temp_data = temp_material_produk($items2, $material_balans);
//                $result['material'] = $temp_data['material'];
//                $result['status'] = 'mid';
//                array_push($r, $result);
//            }
//        }else{
//            $result['material'] = $items1;
//            $result['status'] = 'end';
//        }
//
////        if ($items1 =='MATERIAL 6'){
////            dd($r);
////        }
//
//        return $result;
//
//    }
//}

//// Cara 1
//if (!function_exists('antrian_material_balans')) {
//    function antrian_material_balans($versi)
//    {
//        $resulty = [];
//        $material_balans = ConsRate::leftjoin('material', 'material.material_code', '=', 'cons_rate.material_code')
//            ->where([
//                'material.kategori_material_id' => 1,
//                'cons_rate.deleted_at' => null,
//            ])
//            ->get();
//        $data = find_lower_material($versi);
//
//        foreach ($data as $items){
//            $temp = [$items];
//            $temp_resulty = '';
//            $product = temp_material_produk($items, $material_balans, $temp, $temp_resulty);
//
//            var_dump($product);
//            array_push($resulty, $product);
//
//        }
//
//        return $resulty;
//    }
//}
//
//if (!function_exists('temp_material_produk')) {
//    function temp_material_produk($items1, $material_balans, $temp, $temp_resulty)
//    {
//        $temp_hasil = $temp;
//        $temp_resulty1 = $items1.'->';
//
//
//        $produk = $material_balans->where('material_code',$items1)->pluck('product_code')->all();
//
//        $count_produk = count($produk);
//
//        if ($count_produk == 1){
//            $data_produk = $produk[0];
//            $hasil = temp_material_produk($data_produk, $material_balans, $temp_hasil,$temp_resulty1);
//
//            $temp_resulty1 .= $hasil;
//        }else if ($count_produk > 1){
//            foreach ($produk as $items){
//                $data_produk = $items;
//                $hasil = temp_material_produk($data_produk, $material_balans, $temp_hasil,$temp_resulty1);
//                $temp_resulty1 .= $hasil;
//            }
//        }
//
//        return $temp_resulty1;
//
//    }
//}

// Cara 2
if (!function_exists('antrian_material_balans')) {
    function antrian_material_balans($versi)
    {
        //        dd($versi);
        $resulty = [];
        $material_balans = ConsRate::leftjoin('material', 'material.material_code', '=', 'cons_rate.material_code')
            ->where([
                'material.kategori_material_id' => 1,
                'cons_rate.deleted_at' => null,
            ])
            ->get();
        $data = find_lower_material($versi);

        foreach ($data as $items) {
            $temp = [$items];
            $temp_resulty = [];
            $product = temp_material_produk($items, $material_balans, $temp, $temp_resulty);

            array_push($resulty, $product);
        }

        return $resulty;
    }
}

if (!function_exists('temp_material_produk')) {
    function temp_material_produk($items1, $material_balans, $temp, $temp_resulty)
    {
        $temp_hasil = $temp;
        $temp_resulty1 = $temp_resulty;
        array_push($temp_resulty1, $items1);


        $produk = $material_balans->where('material_code', $items1)->pluck('product_code')->all();

        $count_produk = count($produk);

        if ($count_produk == 1) {
            $data_produk = $produk[0];
            $hasil = temp_material_produk($data_produk, $material_balans, $temp_hasil, $temp_resulty1);
            //            dd($hasil);
            //            array_push($temp_resulty1, $hasil);
            $temp_resulty1 = $hasil;
        } else if ($count_produk > 1) {
            foreach ($produk as $items) {
                $data_produk = $items;
                $hasil = temp_material_produk($data_produk, $material_balans, $temp_hasil, $temp_resulty1);
                //                array_push($temp_resulty1, $hasil);
                $temp_resulty1 = $hasil;
            }
        }

        return $temp_resulty1;
    }
}

if (!function_exists('get_data_balans')) {
    function get_data_balans($kategori, $plant, $material, $asumsi, $versi = null)
    {
        $plant = explode(';', $plant);
        $data_plant = [];

        if (count($plant) > 1) {
            foreach ($plant as $items) {
                $temp = explode(' - ', $items);
                array_push($data_plant, $temp[0]);
            }
        } else {
            $temp = explode(' - ', $plant[0]);
            array_push($data_plant, $temp[0]);
        }


        if ($kategori == 1) {
            $result = Saldo_Awal::select(DB::raw('SUM(total_stock) as total_stock'), DB::raw('SUM(total_value) as total_value'))
                ->where('month_year', 'ilike', '%' . $asumsi . '%')
                ->where('material_code', $material);
            if ($data_plant[0] != 'all') {
                $result = $result->whereIn('plant_code', $data_plant);
            }

            $result = $result->first();
        } elseif ($kategori == 2) {
            $result = QtyRenDaan::select(DB::raw('SUM(qty_rendaan.qty_rendaan_value) as qty_rendaan_value'))
                ->leftjoin('asumsi_umum', 'asumsi_umum.id', '=', 'qty_rendaan.asumsi_umum_id')
                ->where('asumsi_umum.month_year', 'ilike', '%' . $asumsi . '%')
                ->where('asumsi_umum.version_id', $versi)
                ->where('qty_rendaan.material_code', $material);
            $result = $result->first();
        } elseif ($kategori == 4) {
            $cc = auth()->user()->company_code;
            $pemakaian = DB::table('pj_pemakaian')
                ->select('pj_pemakaian.pj_pemakaian_value')
                ->leftjoin('asumsi_umum', 'asumsi_umum.id', '=', 'pj_pemakaian.asumsi_umum_id')
                ->where('pj_pemakaian.company_code', $cc)
                ->where('pj_pemakaian.material_code', $material)
                ->where('pj_pemakaian.version_id', $versi)
                ->where('asumsi_umum.month_year', 'ilike', '%' . $asumsi . '%')
                ->whereNull('pj_pemakaian.deleted_at')
                ->first();

            $penjualan = DB::table('pj_penjualan')
                ->select('pj_penjualan.pj_penjualan_value')
                ->leftjoin('asumsi_umum', 'asumsi_umum.id', '=', 'pj_penjualan.asumsi_umum_id')
                ->where('pj_penjualan.company_code', $cc)
                ->where('pj_penjualan.material_code', $material)
                ->where('pj_penjualan.version_id', $versi)
                ->where('asumsi_umum.month_year', 'ilike', '%' . $asumsi . '%')
                ->whereNull('pj_penjualan.deleted_at')
                ->first();

            return $pemakaian->pj_pemakaian_value + $penjualan->pj_penjualan_value;
        } elseif ($kategori == 'total_daan') {
            $cc = auth()->user()->company_code;
            $qty_rendaan = DB::table('qty_rendaan')
                ->leftjoin('asumsi_umum', 'asumsi_umum.id', '=', 'qty_rendaan.asumsi_umum_id')
                ->where('qty_rendaan.company_code', $cc)
                ->where('asumsi_umum.version_id', $versi)
                ->where('asumsi_umum.month_year', 'ilike', '%' . $asumsi . '%')
                ->where('qty_rendaan.material_code', $material)
                ->whereNull('qty_rendaan.deleted_at')
                ->first();

            $price_rendaan = DB::table('price_rendaan')
                ->leftjoin('asumsi_umum', 'asumsi_umum.id', '=', 'price_rendaan.asumsi_umum_id')
                ->where('price_rendaan.company_code', $cc)
                ->where('asumsi_umum.version_id', $versi)
                ->where('asumsi_umum.month_year', 'ilike', '%' . $asumsi . '%')
                ->where('price_rendaan.material_code', $material)
                ->whereNull('price_rendaan.deleted_at')
                ->first();

            $val_qty_rendaan =  $qty_rendaan ? $qty_rendaan->qty_rendaan_value : 0;
            $val_price_daan = $price_rendaan ? $price_rendaan->price_rendaan_value : 0;
            $val_adjustment = $qty_rendaan ? $qty_rendaan->adjustment : 0;
            $val_kurs = $qty_rendaan ? $qty_rendaan->usd_rate : 0;


            if ($val_qty_rendaan > 0 && $val_price_daan == 0) {
                $result = '-';
            } else {
                $result = $val_qty_rendaan * ($val_price_daan * (1 + ($val_adjustment / 100)) * $val_kurs);
            }
        } else {
            $result = 0;
        }
        return $result;
    }
}

if (!function_exists('get_data_balans_db')) {
    function get_data_balans_db($balans, $kategori, $plant, $material, $asumsi, $versi = null)
    {
        if ($kategori == 1) {
            $result = $balans->where('kategori_balans_id', 5)
                ->where('month_year', $asumsi)
                ->where('company_code', auth()->user()->company_code)
                ->where('material_code', $material)
                ->first();

            return $result;
        } else {
            return 0;
        }
    }
}

if (!function_exists('hsBalans')) {
    function hsBalans($periode, $material, $produk)
    {
        if ($produk == $material) {
            return 0;
        } else {
            //mengambil biaya perton berdasarkan periode, material, dan tersedia
            $balans = DB::table("balans")
                ->where('balans.material_code', $material)
                ->where('balans.asumsi_umum_id', $periode)
                ->where('balans.kategori_balans_id', 3)
                ->first();

            $res = $balans->p ?? 0;

            return $res;
        }
    }
}

if (!function_exists('hsZco')) {
    function hsZco($produk, $plant, $material)
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

        // if ($this->format == '0') {
        //     $temp = explode('-', $this->moth);
        //     $timemonth = $temp[1] . '-' . $temp[0];

        //     $total_qty->where('periode', 'ilike', '%' . $timemonth . '%');
        //     $total_biaya->where('periode', 'ilike', '%' . $timemonth . '%');
        //     $kuantum_produksi->where('periode', 'ilike', '%' . $timemonth . '%');
        // } else if ($this->format == '1') {
        //     $start_temp = explode('-', $this->start_month);
        //     $end_temp = explode('-', $this->end_month);
        //     $start_date = $start_temp[1] . '-' . $start_temp[0] . '-01 00:00:00';
        //     $end_date = $end_temp[1] . '-' . $end_temp[0] . '-01 00:00:00';

        //     $total_qty->whereBetween('periode', [$start_date, $end_date]);
        //     $total_biaya->whereBetween('periode', [$start_date, $end_date]);
        //     $kuantum_produksi->whereBetween('periode', [$start_date, $end_date]);
        // }

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
}

if (!function_exists('hsStock')) {
    function hsStock($material, $version)
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
}

if (!function_exists('hsKantong')) {
    function hsKantong($material, $version)
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
}

if (!function_exists('kuantumProduksi')) {
    function kuantumProduksi($cost_center, $periode)
    {
        //        dd($cost_center, $periode);
        $renprod = DB::table("qty_renprod")->where('qty_renprod.cost_center', $cost_center)
            ->where('qty_renprod.asumsi_umum_id', $periode)
            ->first();

        //                dd($renprod != null);
        //         if ($renprod == null){
        //             $renprod = DB::select('select 0 as qty_renprod_value');
        //         }
        //        dd($renprod);

        return $renprod;
    }
}

if (!function_exists('consRate')) {
    function consRate($plant, $produk, $material)
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
}

if (!function_exists('totalSalr')) {
    function totalSalr($cost_center, $group_account, $inflasi)
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
}

if (!function_exists('labaRugi')) {
    function labaRugi($produk)
    {
        $lb = DB::table("laba_rugi")
            ->leftjoin('material', 'material.kategori_produk_id', '=', 'laba_rugi.kategori_produk_id')
            ->where('material.material_code', $produk)
            ->first();

        return $lb;
    }
}

if (!function_exists('totalBB')) {
    function totalBB($data, $plant, $produk, $version, $periode)
    {
        $res_bb = [];

        foreach ($data as $key => $value) {
            $consrate_bb = consRate($plant, $produk, $value->code) ?? 0;
            if ($value->kategori == 1) {
                $hs_balans = hsBalans($periode, $value->code, $produk);
                $biayaperton1 = $hs_balans * $consrate_bb;
                array_push($res_bb, $biayaperton1);
            } else if ($value->kategori == 2) {
                $hs_zco = hsZco($produk, $plant, $value->code);
                $biayaperton2 = $hs_zco * $consrate_bb;
                array_push($res_bb, $biayaperton2);
            } else if ($value->kategori == 3) {
                $hs_stock = hsStock($value->code, $version);
                $biayaperton3 = $hs_stock * $consrate_bb;
                array_push($res_bb, $biayaperton3);
            } else {
                $hs_kantong = hsKantong($value->code, $version);
                $biayaperton4 = $hs_kantong * $consrate_bb;
                array_push($res_bb, $biayaperton4);
            }
        }

        $res = array_sum($res_bb);
        return $res;
    }
}

if (!function_exists('handle_null')) {
    function handle_null($data, $check)
    {
        $result = $data != null ? $check : 0;
        return $result;
    }
}

if (!function_exists('totalGL')) {
    function totalGL($data, $cost_center, $asum_id, $asum_inflasi)
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
                $kp = kuantumProduksi($cost_center, $asum_id) ?? 0;
                //                dd($kp);
                $total = totalSalr($salr->cost_center, $salr->group_account_fc, $asum_inflasi);

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






















function helpDollar($money, $dollar)
{
    $res = round($money / $dollar, 2);
    $res = "$ " . $res;

    return $res;
}
