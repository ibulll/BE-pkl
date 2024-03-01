<?php

use App\Models\Absensi;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Admin\PdfController;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\SppdController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\EmailController;
use App\Http\Controllers\APi\Admin\JurnalController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\AbsenSiswaController;
use App\Http\Controllers\Api\Admin\PembimbingController;
use App\Http\Controllers\Api\Admin\PermissionController;
use App\Http\Controllers\Api\Admin\PerusahaanController;
use App\Http\Controllers\Api\Admin\PengajuanSiswaController;
use App\Http\Controllers\Api\Siswa\JurnalSiswaController;
use App\Http\Controllers\Api\Siswa\DashboardSiswaController;
use App\Http\Controllers\Api\Siswa\InfoPembimbingController;
use App\Http\Controllers\Api\Siswa\AbsensiController;
use App\Http\Controllers\Api\Siswa\PengajuanPKLController;
use App\Http\Controllers\Api\Pembimbing\JurnalDataController;
use App\Http\Controllers\Api\Pembimbing\DataAbsenController;
use App\Http\Controllers\Api\Pembimbing\PengajuanSppdController;
use App\Http\Controllers\Api\Pembimbing\DashboardPembimbingController;
use App\Http\Controllers\Api\Kaprog\DataJurnalController;
use App\Http\Controllers\Api\Kaprog\DashboardKaprogController;
use App\Http\Controllers\Api\Kaprog\AbsenController;


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
    Route::group(['middleware' => ['auth:api', 'role:admin']], function () {
        // dashboard
        Route::get('/dashboard/count-data', [DashboardController::class, 'getCountData']);

        Route::get('/dashboard/count-pending-applications', [DashboardController::class, 'countPendingApplications']);

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
        Route::get('/jurnal-siswa/{id}', [JurnalController::class, 'getJurnalSiswa']);


        //pengajuansiswa
        Route::get('/pengajuan/all', [PengajuanSiswaController::class, 'getAllPengajuan']);

        Route::put('/update-status/{id}', [PengajuanSiswaController::class, 'updateStatus']);

        Route::post('/send-email', [EmailController::class, 'sendEmail']);

        Route::get('/absensi', [AbsenSiswaController::class, 'index']);

        Route::get('/absensi/{id}', [AbsenSiswaController::class, 'show']);

        Route::get('/photos/{filename}', [AbsenSiswaController::class,]);

        Route::get('pengajuan-pkl/{id}/view-cv', [PengajuanSiswaController::class, 'viewCV']);

        Route::get('pengajuan-pkl/{id}/view-portofolio', [PengajuanSiswaController::class, 'viewPortofolio']);

        Route::get('/detail-pengajuan/{groupId}', [PengajuanSiswaController::class, 'detail']);

        //Perusahaan
        Route::get('/perusahaan', [PerusahaanController::class, 'index']);
        Route::post('/perusahaan', [PerusahaanController::class, 'store']);
        Route::get('/perusahaan/{id}', [PerusahaanController::class, 'show']);
        Route::put('/perusahaan/{id}', [PerusahaanController::class, 'update']);
        Route::delete('/perusahaan/{id}', [PerusahaanController::class, 'destroy']);

        //pembimbing
        Route::get('/daftar', [PembimbingController::class, 'index']);
        Route::get('/daftar-pengajuan', [PembimbingController::class, 'all']);
        Route::get('/data-sppd', [PembimbingController::class, 'datasppd']);
        Route::get('/daftar-siswa', [PembimbingController::class, 'getDaftarSiswa']);
        Route::get('/daftar-kelas', [PengajuanPKLController::class, 'getDaftarKelas']);
        // Route::get('/daftar-pembimbing', [PembimbingController::class, 'getDaftarPembimbing']);
        Route::post('/assign', [PembimbingController::class, 'assignToGroup']);


        Route::post('/generate-pdf', [PdfController::class, 'generatePDF']);

        //sppdcontroller
        Route::get('/sppd', [SppdController::class, 'index']);
        Route::get('/detail-sppd/{id}', [SppdController::class, 'detail']);
        Route::post('/tambah-nosurat', [SppdController::class, 'generatesppd']);

    });
});

Route::prefix('siswa')->group(function () {

    Route::group(['middleware' => ['auth:api', 'role:siswa']], function () {

        Route::apiResource('/pengajuan-pkl', PengajuanPKLController::class)->middleware('permission:pengajuan.index|pengajuan.store');

        Route::get('/dashboard', [DashboardSiswaController::class, 'getDaftarAkunSiswa']);

        Route::get('/status', [DashboardSiswaController::class, 'status']);
        Route::get('/statusWithCountdown', [DashboardSiswaController::class, 'status']);
        ;
        //pengajuan siswa
        Route::get('/daftar-siswa', [PengajuanPKLController::class, 'getDaftarSiswa']);
        Route::get('/daftar-kelas-2', [PengajuanPKLController::class, 'getDaftarKelas']);
        Route::get('/daftar-perusahaan', [PengajuanPKLController::class, 'getDaftarPerusahaan']);


        Route::apiResource('/jurnal', JurnalSiswaController::class)->middleware('permission:jurnal.index|jurnal.store|jurnal.update|jurnal.destroy|jurnal.show');

        Route::post('/absen', [AbsensiController::class, 'store']);
        Route::get('/absensi-list', [AbsensiController::class, 'list']);

        //info pembimbing
        Route::get('/info-pembimbing', [InfoPembimbingController::class, 'show']);
    });
});

Route::prefix('pembimbing')->group(function () {

    Route::group(['middleware' => ['auth:api', 'role:pembimbing']], function () {

        Route::get('/dashboard', [DashboardPembimbingController::class, 'siswaDibimbing']);
        Route::get('/absen-siswa', [DataAbsenController::class, 'index']);

        Route::post('/pengajuan-sppd', [PengajuanSppdController::class, 'store']);

        Route::get('/data-jurnal', [JurnalDataController::class, 'index']);
    });
});

Route::prefix('kaprog')->group(function () {

    Route::group(['middleware' => ['auth:api', 'role:kaprog']], function () {

        //Dashboard
        Route::get('/dashboard', [DashboardKaprogController::class, 'index']);

        //AbsenSiswa
        Route::get('/absen-siswa', [AbsenController::class, 'index']);
    });
});