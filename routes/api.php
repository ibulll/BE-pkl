<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Admin\PermissionController;

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
Route::post('/login', [App\Http\Controllers\Api\Auth\LoginController::class, 'index']);


// group route with middleware "auth"
Route::group(['middleware' => 'auth:api'], function () { // -> rawan bug....
    // logout
    Route::post('/logout', [App\Http\Controllers\Api\Auth\LoginController::class, 'logout']);
});


// group route with prefix "admin"
Route::prefix('admin')->group(function () {
    // group route with middleware "auth:api"
    Route::group(['middleware' => 'auth:api'], function () {
        // dashboard
        Route::get('/dashboard/count-data', [DashboardController::class, 'getCountData']);

        //roles
        Route::apiResource('/roles', App\Http\Controllers\Api\Admin\RoleController::class)->middleware('permission:roles.index|roles.store|roles.update|roles.delete');

        //permission
        Route::get('/permissions', [\App\Http\Controllers\Api\Admin\PermissionController::class, 'index'])->middleware('permission:permission.index');

        //user
        Route::apiResource('/roles', App\Http\Controllers\Api\Admin\RoleController::class)->middleware('permission:user.index|user.store|user.update|user.delete');
        
    });
});


      