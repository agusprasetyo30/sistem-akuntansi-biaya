<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriMaterialController;
use App\Http\Controllers\KategoriProdukController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ModalController;
use App\Http\Controllers\PlantController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegionsController;
use App\Http\Controllers\CostCenterController;
use App\Http\Controllers\SelectController;
use App\Http\Controllers\AsumsiUmumController;
use App\Http\Controllers\GroupAccountController;
use App\Http\Controllers\PriceRenDaanController;
use App\Http\Controllers\QtyRenDaanController;
use App\Http\Controllers\QtyRenProdController;
use App\Http\Controllers\SaldoAwalController;
use App\Http\Controllers\TotalDaanController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KursController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ConsRateController;
use App\Http\Controllers\SalrController;
use App\Http\Controllers\ZcoController;
use App\Http\Controllers\GroupAccountFixedCostController;
use App\Http\Controllers\GLAccountController;
use App\Http\Controllers\GLAccountFixedCostController;
use App\Http\Controllers\GlosCCController;
use App\Http\Controllers\LabaRugiController;
use App\Http\Controllers\PJPemakaianController;
use App\Http\Controllers\PJPenjualanController;
use App\Http\Controllers\KategoriBalansController;
use App\Http\Controllers\KontrolProyeksiController;
use App\Http\Controllers\MapKetegoriBalansController;
use App\Http\Controllers\SimulasiProyeksiController;
use  App\Http\Controllers\BalansController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('pages.dashboard.index');
// })->middleware(['auth'])->name('dashboard');

// require __DIR__.'/auth.php';

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.update');
});

/**
 * Auth
 */
Route::group(['middleware' => 'auth'], function () {

    /**
     * Admin
     */

    Route::get('/', function () {
        return redirect()->route('dashboard_admin');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard_admin');

    Route::group(['prefix' => 'master'], function () {
        Route::group(['prefix' => 'material'], function () {
            Route::get('/', [MaterialController::class, 'index'])->name('material');
            Route::post('insert', [MaterialController::class, 'create'])->name('insert_material');
            Route::post('update', [MaterialController::class, 'update'])->name('update_material');
            Route::post('delete', [MaterialController::class, 'delete'])->name('delete_material');
            Route::post('import', [MaterialController::class, 'import'])->name('import_material');
            Route::get('export', [MaterialController::class, 'export'])->name('export_material');
        });

        Route::group(['prefix' => 'plant'], function () {
            Route::get('/', [PlantController::class, 'index'])->name('plant');
            Route::post('insert', [PlantController::class, 'create'])->name('insert_plant');
            Route::post('update', [PlantController::class, 'update'])->name('update_plant');
            Route::post('delete', [PlantController::class, 'delete'])->name('delete_plant');
            Route::post('import', [PlantController::class, 'import'])->name('import_plant');
            Route::get('export', [PlantController::class, 'export'])->name('export_plant');
        });

        Route::group(['prefix' => 'kategori-material'], function () {
            Route::get('/', [KategoriMaterialController::class, 'index'])->name('kategori_material');
            Route::post('insert', [KategoriMaterialController::class, 'create'])->name('insert_kategori_material');
            Route::post('update', [KategoriMaterialController::class, 'update'])->name('update_kategori_material');
            Route::post('delete', [KategoriMaterialController::class, 'delete'])->name('delete_kategori_material');
            Route::post('import', [KategoriMaterialController::class, 'import'])->name('import_kategori_material');
            Route::get('export', [KategoriMaterialController::class, 'export'])->name('export_kategori_material');
        });

        Route::group(['prefix' => 'kategori-produk'], function () {
            Route::get('/', [KategoriProdukController::class, 'index'])->name('kategori_produk');
            Route::post('insert', [KategoriProdukController::class, 'create'])->name('insert_kategori_produk');
            Route::post('update', [KategoriProdukController::class, 'update'])->name('update_kategori_produk');
            Route::post('delete', [KategoriProdukController::class, 'delete'])->name('delete_kategori_produk');
            Route::post('import', [KategoriProdukController::class, 'import'])->name('import_kategori_produk');
            Route::get('export', [KategoriProdukController::class, 'export'])->name('export_kategori_produk');
        });

        Route::group(['prefix' => 'regions'], function () {
            Route::get('/', [RegionsController::class, 'index'])->name('regions');
            Route::post('insert', [RegionsController::class, 'create'])->name('insert_regions');
            Route::post('update', [RegionsController::class, 'update'])->name('update_regions');
            Route::post('delete', [RegionsController::class, 'delete'])->name('delete_regions');
            Route::post('import', [RegionsController::class, 'import'])->name('import_regions');
            Route::get('export', [RegionsController::class, 'export'])->name('export_regions');
        });

        Route::group(['prefix' => 'cost_center'], function () {
            Route::get('/', [CostCenterController::class, 'index'])->name('cost_center');
            Route::post('insert', [CostCenterController::class, 'create'])->name('insert_cost_center');
            Route::post('update', [CostCenterController::class, 'update'])->name('update_cost_center');
            Route::post('delete', [CostCenterController::class, 'delete'])->name('delete_cost_center');
            Route::post('import', [CostCenterController::class, 'import'])->name('import_cost_center');
            Route::get('export', [CostCenterController::class, 'export'])->name('export_cost_center');
        });

        Route::group(['prefix' => 'glos-cc'], function () {
            Route::get('/', [GlosCCController::class, 'index'])->name('glos_cc');
            Route::post('insert', [GlosCCController::class, 'create'])->name('insert_glos_cc');
            Route::post('update', [GlosCCController::class, 'update'])->name('update_glos_cc');
            Route::post('delete', [GlosCCController::class, 'delete'])->name('delete_glos_cc');
            Route::post('import', [GlosCCController::class, 'import'])->name('import_glos_cc');
            Route::get('export', [GlosCCController::class, 'export'])->name('export_glos_cc');
        });

        Route::group(['prefix' => 'role'], function () {
            Route::get('/', [RoleController::class, 'index'])->name('role');
            Route::post('insert', [RoleController::class, 'create'])->name('insert_role');
            Route::post('update', [RoleController::class, 'update'])->name('update_role');
            Route::post('delete', [RoleController::class, 'delete'])->name('delete_role');
        });

        Route::group(['prefix' => 'user'], function () {
            Route::get('/', [UserController::class, 'index'])->name('user');
            Route::post('insert', [UserController::class, 'create'])->name('insert_user');
            Route::post('update', [UserController::class, 'update'])->name('update_user');
            Route::post('delete', [UserController::class, 'delete'])->name('delete_user');
        });

        Route::group(['prefix' => 'company'], function () {
            Route::get('/', [CompanyController::class, 'index'])->name('company');
            Route::post('insert', [CompanyController::class, 'create'])->name('insert_company');
            Route::post('update', [CompanyController::class, 'update'])->name('update_company');
            Route::post('delete', [CompanyController::class, 'delete'])->name('delete_company');
            Route::post('import', [CompanyController::class, 'import'])->name('import_company');
        });

        Route::group(['prefix' => 'kurs'], function () {
            Route::get('/', [KursController::class, 'index'])->name('kurs');
            Route::post('insert', [KursController::class, 'create'])->name('insert_kurs');
            Route::post('update', [KursController::class, 'update'])->name('update_kurs');
            Route::post('delete', [KursController::class, 'delete'])->name('delete_kurs');
        });

        Route::group(['prefix' => 'group-account'], function () {
            Route::get('/', [GroupAccountController::class, 'index'])->name('group_account');
            Route::post('insert', [GroupAccountController::class, 'create'])->name('insert_group_account');
            Route::post('update', [GroupAccountController::class, 'update'])->name('update_group_account');
            Route::post('delete', [GroupAccountController::class, 'delete'])->name('delete_group_account');
            Route::post('import', [GroupAccountController::class, 'import'])->name('import_group_account');
            Route::get('export', [GroupAccountController::class, 'export'])->name('export_group_account');
        });

        Route::group(['prefix' => 'group-account-fixed-cost'], function () {
            Route::get('/', [GroupAccountFixedCostController::class, 'index'])->name('group_account_fc');
            Route::post('insert', [GroupAccountFixedCostController::class, 'create'])->name('insert_group_account_fc');
            Route::post('update', [GroupAccountFixedCostController::class, 'update'])->name('update_group_account_fc');
            Route::post('delete', [GroupAccountFixedCostController::class, 'delete'])->name('delete_group_account_fc');
            Route::post('import', [GroupAccountFixedCostController::class, 'import'])->name('import_group_account_fc');
            Route::get('export', [GroupAccountFixedCostController::class, 'export'])->name('export_group_account_fc');
        });

        Route::group(['prefix' => 'gl-account'], function () {
            Route::get('/', [GLAccountController::class, 'index'])->name('gl_account');
            Route::post('insert', [GLAccountController::class, 'create'])->name('insert_gl_account');
            Route::post('update', [GLAccountController::class, 'update'])->name('update_gl_account');
            Route::post('delete', [GLAccountController::class, 'delete'])->name('delete_gl_account');
            Route::post('import', [GLAccountController::class, 'import'])->name('import_gl_account');
            Route::get('export', [GLAccountController::class, 'export'])->name('export_gl_account');
        });

        Route::group(['prefix' => 'gl-account-fc'], function () {
            Route::get('/', [GLAccountFixedCostController::class, 'index'])->name('gl_account_fc');
            Route::post('insert', [GLAccountFixedCostController::class, 'create'])->name('insert_gl_account_fc');
            Route::post('update', [GLAccountFixedCostController::class, 'update'])->name('update_gl_account_fc');
            Route::post('delete', [GLAccountFixedCostController::class, 'delete'])->name('delete_gl_account_fc');
            Route::post('import', [GLAccountFixedCostController::class, 'import'])->name('import_gl_account_fc');
            Route::get('export', [GLAccountFixedCostController::class, 'export'])->name('export_gl_account_fc');
        });

        Route::group(['prefix' => 'asumsi_umum'], function () {
            Route::get('/', [AsumsiUmumController::class, 'index'])->name('asumsi_umum');
            Route::post('insert', [AsumsiUmumController::class, 'create'])->name('insert_asumsi_umum');
            Route::post('update', [AsumsiUmumController::class, 'update'])->name('update_asumsi_umum');
            Route::post('delete', [AsumsiUmumController::class, 'delete'])->name('delete_asumsi_umum');

            // view

            Route::post('view_asumsi_umum', [AsumsiUmumController::class, 'view'])->name('view_asumsi_umum');
            Route::post('view_edit_asumsi_umum', [AsumsiUmumController::class, 'view_edit'])->name('view_edit_asumsi_umum');
        });

        Route::group(['prefix' => 'kategori_balans'], function () {
            Route::get('/', [KategoriBalansController::class, 'index'])->name('kategori_balans');
            Route::post('insert', [KategoriBalansController::class, 'create'])->name('insert_kategori_balans');
            Route::post('update', [KategoriBalansController::class, 'update'])->name('update_kategori_balans');
            Route::post('delete', [KategoriBalansController::class, 'delete'])->name('delete_kategori_balans');
        });

        Route::group(['prefix' => 'map_kategori_balans'], function () {
            Route::get('/', [MapKetegoriBalansController::class, 'index'])->name('map_kategori_balans');
            Route::post('insert', [MapKetegoriBalansController::class, 'create'])->name('insert_map_kategori_balans');
            Route::post('update', [MapKetegoriBalansController::class, 'update'])->name('update_map_kategori_balans');
            Route::post('delete', [MapKetegoriBalansController::class, 'delete'])->name('delete_map_kategori_balans');
        });

        // Main Select2
        Route::group(['prefix' => 'main_data'], function () {
            Route::get('/plant_select', [SelectController::class, 'plant'])->name('plant_select');
            Route::get('/plant_balans_select', [SelectController::class, 'plant_balans'])->name('plant_balans_select');
            Route::get('/periode_select', [SelectController::class, 'periode'])->name('periode_select');
            Route::get('/kategori_material', [SelectController::class, 'kategori_material'])->name('kategori_material_select');
            Route::get('/kategori_produk', [SelectController::class, 'kategori_produk'])->name('kategori_produk_select');
            Route::get('/kategori_balans_select', [SelectController::class, 'kategori_balans'])->name('kategori_balans_select');
            Route::get('/material_select', [SelectController::class, 'material'])->name('material_select');
            Route::get('/material_balans_select', [SelectController::class, 'material_balans'])->name('material_balans_select');
            Route::get('/material_keyword_select', [SelectController::class, 'material_keyword'])->name('material_keyword_select');
            Route::get('/region_select', [SelectController::class, 'region'])->name('region_select');
            Route::get('/role_select', [SelectController::class, 'role'])->name('role_select');
            Route::get('/group_account_select', [SelectController::class, 'group_account'])->name('group_account_select');
            Route::get('/version_select', [SelectController::class, 'version'])->name('version_select');
            Route::get('/version_detail', [SelectController::class, 'version_detail'])->name('version_detail_select');
            Route::get('/version_inflasi', [SelectController::class, 'version_inflasi'])->name('version_inflasi_select');
            Route::get('/group_account_fc_select', [SelectController::class, 'group_account_fc'])->name('group_account_fc_select');
            Route::get('/general_ledger_fc_select', [SelectController::class, 'general_ledger_fc'])->name('general_ledger_fc_select');
            Route::get('/general_ledger_fc_detail_select', [SelectController::class, 'general_ledger_fc_detail'])->name('general_ledger_fc_detail_select');
            Route::get('/cost_center', [SelectController::class, 'cost_center'])->name('cost_center_select');
            Route::get('/cost_center_salr', [SelectController::class, 'cost_center_salr'])->name('cost_center_salr_select');
            Route::get('/cost_element', [SelectController::class, 'cost_element'])->name('cost_element_select');


            //            Helper
            Route::post('/check_username', [SelectController::class, 'check_username'])->name('helper_username');
            Route::post('/check_email', [SelectController::class, 'check_email'])->name('helper_email');
            Route::post('/check_kurs', [SelectController::class, 'check_kurs'])->name('helper_kurs');
            Route::post('/check_kursv1', [SelectController::class, 'check_kursv1'])->name('helper_kursv1');
            Route::post('/check_kursv2', [SelectController::class, 'check_kursv2'])->name('helper_kursv2');

            //            Datatable
            Route::get('/version_dt', [SelectController::class, 'version_dt'])->name('version_dt');
            Route::get('/material_dt', [SelectController::class, 'material_dt'])->name('material_dt');
            Route::get('/material_balans_dt', [SelectController::class, 'material_balans_dt'])->name('material_balans_dt');
            Route::get('/plant_dt', [SelectController::class, 'plant_dt'])->name('plant_dt');
            Route::get('/kategori_material_dt', [SelectController::class, 'kategori_material_dt'])->name('kategori_material_dt');
            Route::get('/kategori_produk_dt', [SelectController::class, 'kategori_produk_dt'])->name('kategori_produk_dt');
            Route::get('/kategori_balans_dt', [SelectController::class, 'kategori_balans_dt'])->name('kategori_balans_dt');
            Route::get('/group_account_dt', [SelectController::class, 'group_account_dt'])->name('group_account_dt');
            Route::get('/group_account_fc_dt', [SelectController::class, 'group_account_fc_dt'])->name('group_account_fc_dt');
            Route::get('/gl_account_fc_dt', [SelectController::class, 'gl_account_fc_dt'])->name('gl_account_fc_dt');
            Route::get('/cost_center_dt', [SelectController::class, 'cost_center_dt'])->name('cost_center_dt');
            Route::get('/cost_element_dt', [SelectController::class, 'cost_element_dt'])->name('cost_element_dt');
            Route::get('/zco_product_dt', [SelectController::class, 'zco_product_dt'])->name('zco_product_dt');
            Route::get('/zco_plant_dt', [SelectController::class, 'zco_plant_dt'])->name('zco_plant_dt');
        });
    });

    Route::group(['prefix' => 'buku-besar'], function () {

        Route::group(['prefix' => 'consrate'], function () {
            Route::get('/', [ConsRateController::class, 'index'])->name('consrate');
            Route::post('insert', [ConsRateController::class, 'create'])->name('insert_consrate');
            Route::post('update', [ConsRateController::class, 'update'])->name('update_consrate');
            Route::post('delete', [ConsRateController::class, 'delete'])->name('delete_consrate');
            Route::post('import', [ConsRateController::class, 'import'])->name('import_consrate');
            Route::post('export', [ConsRateController::class, 'export'])->name('export_consrate');
            Route::post('check', [ConsRateController::class, 'check'])->name('check_consrate');
            Route::post('check_duplicated', [ConsRateController::class, 'check_duplicated'])->name('check_consrate_dublicate');
        });

        Route::group(['prefix' => 'saldo-awal'], function () {
            Route::get('/', [SaldoAwalController::class, 'index'])->name('saldo_awal');
            Route::post('insert', [SaldoAwalController::class, 'create'])->name('insert_saldo_awal');
            Route::post('update', [SaldoAwalController::class, 'update'])->name('update_saldo_awal');
            Route::post('delete', [SaldoAwalController::class, 'delete'])->name('delete_saldo_awal');
            Route::post('import', [SaldoAwalController::class, 'import'])->name('import_saldo_awal');
            Route::get('export', [SaldoAwalController::class, 'export'])->name('export_saldo_awal');
            Route::post('check', [SaldoAwalController::class, 'check'])->name('check_saldo_awal');
            Route::post('check_input', [SaldoAwalController::class, 'check_input'])->name('check_input_saldo_awal');
        });

        Route::group(['prefix' => 'qty-renprod'], function () {
            Route::get('/', [QtyRenProdController::class, 'index'])->name('qty_renprod');
            Route::post('insert', [QtyRenProdController::class, 'create'])->name('insert_qty_renprod');
            Route::post('update', [QtyRenProdController::class, 'update'])->name('update_qty_renprod');
            Route::post('delete', [QtyRenProdController::class, 'delete'])->name('delete_qty_renprod');
            Route::post('import', [QtyRenProdController::class, 'import'])->name('import_qty_renprod');
            Route::get('export', [QtyRenProdController::class, 'export'])->name('export_qty_renprod');
            Route::post('check', [QtyRenProdController::class, 'check'])->name('check_qty_renprod');
        });

        Route::group(['prefix' => 'qty-rendaan'], function () {
            Route::get('/', [QtyRenDaanController::class, 'index'])->name('qty_rendaan');
            Route::post('insert', [QtyRenDaanController::class, 'create'])->name('insert_qty_rendaan');
            Route::post('update', [QtyRenDaanController::class, 'update'])->name('update_qty_rendaan');
            Route::post('delete', [QtyRenDaanController::class, 'delete'])->name('delete_qty_rendaan');
            Route::post('export', [QtyRenDaanController::class, 'export'])->name('export_qty_rendaan');
            Route::post('import', [QtyRenDaanController::class, 'import'])->name('import_qty_rendaan');
            Route::post('check', [QtyRenDaanController::class, 'check'])->name('check_qty_rendaan');
        });

        Route::group(['prefix' => 'price-rendaan'], function () {
            Route::get('/', [PriceRenDaanController::class, 'index'])->name('price_rendaan');
            Route::post('insert', [PriceRenDaanController::class, 'create'])->name('insert_price_rendaan');
            Route::post('update', [PriceRenDaanController::class, 'update'])->name('update_price_rendaan');
            Route::post('delete', [PriceRenDaanController::class, 'delete'])->name('delete_price_rendaan');
            Route::post('export', [PriceRenDaanController::class, 'export'])->name('export_price_rendaan');
            Route::post('import', [PriceRenDaanController::class, 'import'])->name('import_price_rendaan');
            Route::post('check', [PriceRenDaanController::class, 'check'])->name('check_price_rendaan');
        });

        Route::group(['prefix' => 'total-daan'], function () {
            Route::get('/', [TotalDaanController::class, 'index'])->name('total_daan');
        });

        Route::group(['prefix' => 'zco'], function () {
            Route::get('/', [ZcoController::class, 'index'])->name('zco');
            Route::post('insert', [ZcoController::class, 'create'])->name('insert_zco');
            Route::post('update', [ZcoController::class, 'update'])->name('update_zco');
            Route::post('delete', [ZcoController::class, 'delete'])->name('delete_zco');
            Route::get('export', [ZcoController::class, 'export'])->name('export_zco');
            Route::post('import', [ZcoController::class, 'import'])->name('import_zco');
            Route::post('check', [ZcoController::class, 'check'])->name('check_zco');
        });

        Route::group(['prefix' => 'salr'], function () {
            Route::get('/', [SalrController::class, 'index'])->name('salr');
            Route::post('insert', [SalrController::class, 'create'])->name('insert_salr');
            Route::post('update', [SalrController::class, 'update'])->name('update_salr');
            Route::post('delete', [SalrController::class, 'delete'])->name('delete_salr');
            Route::post('export', [SalrController::class, 'export'])->name('export_salr');
            Route::post('import', [SalrController::class, 'import'])->name('import_salr');
            Route::post('check', [SalrController::class, 'check'])->name('check_salr');
        });

        Route::group(['prefix' => 'laba-rugi'], function () {
            Route::get('/', [LabaRugiController::class, 'index'])->name('laba_rugi');
            Route::post('insert', [LabaRugiController::class, 'create'])->name('insert_laba_rugi');
            Route::post('update', [LabaRugiController::class, 'update'])->name('update_laba_rugi');
            Route::post('delete', [LabaRugiController::class, 'delete'])->name('delete_laba_rugi');
            Route::post('export', [LabaRugiController::class, 'export'])->name('export_laba_rugi');
            Route::post('import', [LabaRugiController::class, 'import'])->name('import_laba_rugi');
            Route::post('check', [LabaRugiController::class, 'check'])->name('check_laba_rugi');
        });

        Route::group(['prefix' => 'balans'], function () {
            Route::get('/', [BalansController::class, 'index'])->name('balans');
//                Route::post('insert', [LabaRugiController::class, 'create'])->name('insert_laba_rugi');
//                Route::post('update', [LabaRugiController::class, 'update'])->name('update_laba_rugi');
//                Route::post('delete', [LabaRugiController::class, 'delete'])->name('delete_laba_rugi');
//                Route::post('export', [LabaRugiController::class, 'export'])->name('export_laba_rugi');
//                Route::post('import', [LabaRugiController::class, 'import'])->name('import_laba_rugi');
//                Route::post('check', [LabaRugiController::class, 'check'])->name('check_laba_rugi');
        });

        Route::group(['prefix' => 'pakai-jual'], function () {

            Route::group(['prefix' => 'pemakaian'], function () {
                Route::get('/', [PJPemakaianController::class, 'index'])->name('pemakaian');
                Route::post('insert', [PJPemakaianController::class, 'create'])->name('insert_pemakaian');
                Route::post('update', [PJPemakaianController::class, 'update'])->name('update_pemakaian');
                Route::post('delete', [PJPemakaianController::class, 'delete'])->name('delete_pemakaian');
                Route::get('export', [PJPemakaianController::class, 'export'])->name('export_pemakaian');
                Route::post('import', [PJPemakaianController::class, 'import'])->name('import_pemakaian');
                Route::post('check', [PJPemakaianController::class, 'check'])->name('check_pemakaian');
            });

            Route::group(['prefix' => 'penjualan'], function () {
                Route::get('/', [PJPenjualanController::class, 'index'])->name('penjualan');
                Route::post('insert', [PJPenjualanController::class, 'create'])->name('insert_penjualan');
                Route::post('update', [PJPenjualanController::class, 'update'])->name('update_penjualan');
                Route::post('delete', [PJPenjualanController::class, 'delete'])->name('delete_penjualan');
                Route::get('export', [PJPenjualanController::class, 'export'])->name('export_penjualan');
                Route::post('import', [PJPenjualanController::class, 'import'])->name('import_penjualan');
                Route::post('check', [PJPenjualanController::class, 'check'])->name('check_penjualan');
            });
        });
    });

    Route::get('/simulasi-proyeksi', [SimulasiProyeksiController::class, 'index'])->name('simulasi_proyeksi');

    Route::get('/kontrol-proyeksi', [KontrolProyeksiController::class, 'index'])->name('kontrol_proyeksi');

    Route::get('/get-modal', [ModalController::class, 'getModal']);

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
/**
 * End
 */
