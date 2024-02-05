<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Admin\EmailController;
use App\Http\Controllers\APi\Admin\JurnalController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\PermissionController;
use App\Http\Controllers\Api\Siswa\PengajuanPKLController;
use App\Http\Controllers\Api\Admin\PengajuanSiswaController;
use App\Http\Controllers\Api\Siswa\DashboardSiswaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route Login
Route::post('/login', [LoginController::class, 'index']);

// group route with middleware "auth"
Route::group(['middleware' => 'auth:api'], function () {
    // logout
    Route::post('/logout', [LoginController::class, 'logout']);
});

/// group route with prefix "admin"
Route::prefix('admin')->group(function () {
    // group route with middleware "auth:api" and "checkRole:admin"
    Route::group(['middleware' => ['auth:api', 'checkRole:1']], function () {
        // dashboard
        Route::get('/dashboard/count-data', [DashboardController::class, 'getCountData']);

        // roles
        Route::apiResource('/roles', RoleController::class)->middleware('permission:roles.index|roles.store|roles.update|roles.delete');

        // permissions
        Route::get('/permissions', [PermissionController::class, 'index'])->middleware('permission:permissions.index');

        // users
        Route::apiResource('/users', UserController::class)->middleware('permission:users.index|users.store|users.update|users.delete');

        Route::get('/api/admin/users/byRoleId', [UserController::class, 'getUsersByRoleId']);
        //jurnal
        Route::apiResource('/jurnal', JurnalController::class)->middleware('permission:jurnal.index|jurnal.updateStatus');

        Route::get('/jurnal-siswa', [JurnalController::class, 'getJurnalSiswa']);
        Route::get('/jurnal-siswa/{id}', [JurnalController::class, 'getJurnalSiswaById']);

        //pengajuansiswa
        Route::get('/pengajuan/all', [PengajuanSiswaController::class, 'getAllPengajuan']);

        Route::put('/update-status/{nama}', [PengajuanSiswaController::class, 'updateStatus']);

        Route::post('/send-email', [EmailController::class, 'sendEmail']);
    });
});

Route::prefix('siswa')->group(function () {

    Route::group(['middleware' => ['auth:api', 'checkRole:4']], function () {

        Route::apiResource('/pengajuan-pkl', PengajuanPKLController::class)->middleware('permission:pengajuan.index|pengajuan.store');

        Route::get('/dashboard', [DashboardSiswaController::class, 'getDaftarAkunSiswa']);


    });
});
