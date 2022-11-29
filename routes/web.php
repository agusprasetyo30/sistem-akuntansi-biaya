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
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\ProdukController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegionsController;
use App\Http\Controllers\CostCenterController;
use App\Http\Controllers\SelectController;
use App\Http\Controllers\AsumsiUmumController;
use App\Http\Controllers\PriceRenDaanController;
use App\Http\Controllers\QtyRenDaanController;
use App\Http\Controllers\QtyRenProdController;
use App\Http\Controllers\SaldoAwalController;
use App\Http\Controllers\TotalDaanController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

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
        });

        Route::group(['prefix' => 'produk'], function () {
            Route::get('/', [ProdukController::class, 'index'])->name('produk');
            Route::post('insert', [ProdukController::class, 'create'])->name('insert_produk');
            Route::post('update', [ProdukController::class, 'update'])->name('update_produk');
            Route::post('delete', [ProdukController::class, 'delete'])->name('delete_produk');
        });

        Route::group(['prefix' => 'periode'], function () {
            Route::get('/', [PeriodeController::class, 'index'])->name('periode');
            Route::post('insert', [PeriodeController::class, 'create'])->name('insert_periode');
            Route::post('update', [PeriodeController::class, 'update'])->name('update_periode');
            Route::post('delete', [PeriodeController::class, 'delete'])->name('delete_periode');
        });

        Route::group(['prefix' => 'plant'], function () {
            Route::get('/', [PlantController::class, 'index'])->name('plant');
            Route::post('insert', [PlantController::class, 'create'])->name('insert_plant');
            Route::post('update', [PlantController::class, 'update'])->name('update_plant');
            Route::post('delete', [PlantController::class, 'delete'])->name('delete_plant');
            Route::post('import', [PlantController::class, 'import'])->name('import_plant');
        });

        Route::group(['prefix' => 'kategori-material'], function () {
            Route::get('/', [KategoriMaterialController::class, 'index'])->name('kategori_material');
            Route::post('insert', [KategoriMaterialController::class, 'create'])->name('insert_kategori_material');
            Route::post('update', [KategoriMaterialController::class, 'update'])->name('update_kategori_material');
            Route::post('delete', [KategoriMaterialController::class, 'delete'])->name('delete_kategori_material');
        });

        Route::group(['prefix' => 'kategori-produk'], function () {
            Route::get('/', [KategoriProdukController::class, 'index'])->name('kategori_produk');
            Route::post('insert', [KategoriProdukController::class, 'create'])->name('insert_kategori_produk');
            Route::post('update', [KategoriProdukController::class, 'update'])->name('update_kategori_produk');
            Route::post('delete', [KategoriProdukController::class, 'delete'])->name('delete_kategori_produk');
        });

        Route::group(['prefix' => 'regions'], function () {
            Route::get('/', [RegionsController::class, 'index'])->name('regions');
            Route::post('insert', [RegionsController::class, 'create'])->name('insert_regions');
            Route::post('update', [RegionsController::class, 'update'])->name('update_regions');
            Route::post('delete', [RegionsController::class, 'delete'])->name('delete_regions');
        });

        Route::group(['prefix' => 'cost_center'], function () {
            Route::get('/', [CostCenterController::class, 'index'])->name('cost_center');
            Route::post('insert', [CostCenterController::class, 'create'])->name('insert_cost_center');
            Route::post('update', [CostCenterController::class, 'update'])->name('update_cost_center');
            Route::post('delete', [CostCenterController::class, 'delete'])->name('delete_cost_center');
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

        // Main Select2
        Route::group(['prefix' => 'main_data'], function () {
            Route::get('/plant_select', [SelectController::class, 'plant'])->name('plant_select');
            Route::get('/periode_select', [SelectController::class, 'periode'])->name('periode_select');
            Route::get('/kategori_material', [SelectController::class, 'kategori_material'])->name('kategori_material_select');
            Route::get('/kategori_produk', [SelectController::class, 'kategori_produk'])->name('kategori_produk_select');
            Route::get('/material_select', [SelectController::class, 'material'])->name('material_select');
            Route::get('/region_select', [SelectController::class, 'region'])->name('region_select');
            Route::get('/role_select', [SelectController::class, 'role'])->name('role_select');

//            Helper
            Route::post('/check_username', [SelectController::class, 'check_username'])->name('helper_username');
            Route::post('/check_email', [SelectController::class, 'check_email'])->name('helper_email');
        });
    });

    Route::group(['prefix' => 'buku-besar'], function () {
        Route::group(['prefix' => 'cost_center'], function () {
            Route::get('/', [CostCenterController::class, 'index'])->name('cost_center');
            Route::post('insert', [CostCenterController::class, 'create'])->name('insert_cost_center');
            Route::post('update', [CostCenterController::class, 'update'])->name('update_cost_center');
            Route::post('delete', [CostCenterController::class, 'delete'])->name('delete_cost_center');
        });

        Route::group(['prefix' => 'asumsi_umum'], function () {
            Route::get('/', [AsumsiUmumController::class, 'index'])->name('asumsi_umum');
            Route::post('insert', [AsumsiUmumController::class, 'create'])->name('insert_asumsi_umum');
            Route::post('update', [AsumsiUmumController::class, 'update'])->name('update_asumsi_umum');
            Route::post('delete', [AsumsiUmumController::class, 'delete'])->name('delete_asumsi_umum');
        });

        Route::group(['prefix' => 'saldo-awal'], function () {
            Route::get('/', [SaldoAwalController::class, 'index'])->name('saldo_awal');
            Route::post('insert', [SaldoAwalController::class, 'create'])->name('insert_saldo_awal');
            Route::post('update', [SaldoAwalController::class, 'update'])->name('update_saldo_awal');
            Route::post('delete', [SaldoAwalController::class, 'delete'])->name('delete_saldo_awal');
        });

        Route::group(['prefix' => 'qty-renprod'], function () {
            Route::get('/', [QtyRenProdController::class, 'index'])->name('qty_renprod');
            Route::post('insert', [QtyRenProdController::class, 'create'])->name('insert_qty_renprod');
            Route::post('update', [QtyRenProdController::class, 'update'])->name('update_qty_renprod');
            Route::post('delete', [QtyRenProdController::class, 'delete'])->name('delete_qty_renprod');
        });

        Route::group(['prefix' => 'qty-rendaan'], function () {
            Route::get('/', [QtyRenDaanController::class, 'index'])->name('qty_rendaan');
            Route::post('insert', [QtyRenDaanController::class, 'create'])->name('insert_qty_rendaan');
            Route::post('update', [QtyRenDaanController::class, 'update'])->name('update_qty_rendaan');
            Route::post('delete', [QtyRenDaanController::class, 'delete'])->name('delete_qty_rendaan');
        });

        Route::group(['prefix' => 'price-rendaan'], function () {
            Route::get('/', [PriceRenDaanController::class, 'index'])->name('price_rendaan');
            Route::post('insert', [PriceRenDaanController::class, 'create'])->name('insert_price_rendaan');
            Route::post('update', [PriceRenDaanController::class, 'update'])->name('update_price_rendaan');
            Route::post('delete', [PriceRenDaanController::class, 'delete'])->name('delete_price_rendaan');
        });

        Route::group(['prefix' => 'total-daan'], function () {
            Route::get('/', [TotalDaanController::class, 'index'])->name('total_daan');
            Route::post('insert', [TotalDaanController::class, 'create'])->name('insert_total_daan');
            Route::post('update', [TotalDaanController::class, 'update'])->name('update_total_daan');
            Route::post('delete', [TotalDaanController::class, 'delete'])->name('delete_total_daan');
        });


    });

    Route::get('/get-modal', [ModalController::class, 'getModal']);

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
/**
 * End
 */
