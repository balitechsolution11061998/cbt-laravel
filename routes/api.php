<?php

use App\Http\Controllers\Api\AbsensiController;
use App\Http\Controllers\Api\ItemSupplierController;
use App\Http\Controllers\Api\IzinController;
use App\Http\Controllers\Api\PoController;
use App\Http\Controllers\Api\RcvController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/users', [UserController::class, 'index']);
Route::post('/po/store', [PoController::class, 'store']);
Route::post('/rcv/store', [RcvController::class, 'store']);

Route::post('/check_login', App\Http\Controllers\Api\LoginController::class)->name('check_login');


// Route::post('/supplier/store', [SupplierController::class, 'store']);
// Route::post('/stores/store', [StoreController::class, 'store']);
Route::post('/po/store', [PoController::class, 'store']);
Route::post('/itemsupplier/store', [ItemSupplierController::class, 'store']);
Route::post('/rcv/store', [RcvController::class, 'store']);

Route::post('/izin/store', [IzinController::class, 'store']);
Route::get('/izin/history', [IzinController::class, 'index']);
Route::post('/cuti/store', [IzinController::class, 'storeCuti']);
Route::get('/cuti/history', [IzinController::class, 'indexCuti']);
Route::post('/absensi/store', [AbsensiController::class, 'store']);
Route::get('/absensi/history', [AbsensiController::class, 'index']);

