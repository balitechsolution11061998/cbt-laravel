<?php

use App\Http\Controllers\GuruController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PerformanceController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Auth::routes();
Route::get('/scanbarcode', function(){
    return view('auth.scanbarcode');
});
Route::get('/', [LoginController::class, 'index'])->name('formlogin');
Route::post('/formlogin/check_login', [LoginController::class, 'check_login'])->name('formlogin.check_login');
Route::post('/login-with-qr', [LoginController::class, 'loginWithQrCode']);

Route::group(['middleware' => ['verifiedmiddleware','verified','auth','log.user.access']], function () {

// Route::group(['middleware' => ['verifiedmiddleware','twostep','verified','auth','log.user.access']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');





    Route::prefix('permissions')->name('permissions.')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/', 'PermissionsController@index')->name('index');
        Route::get('/data', 'PermissionsController@data')->name('data');
        Route::post('/store', 'PermissionsController@store')->name('store');
        Route::get('{id}/edit', 'PermissionsController@edit')->name('data');
        Route::get('{id}/edit', 'PermissionsController@edit')->name('data');
        Route::delete('/delete', 'PermissionsController@delete')->name('delete');
        Route::get('/getAllPermissions', 'PermissionsController@getAllPermissions')->name('getAllPermissions');
        Route::post('/submitToRole', 'PermissionsController@submitToRole')->name('submitToRole');
        Route::get('/getPermissionsByRole', 'PermissionsController@getPermissionsByRole')->name('getPermissionsByRole');
    });

    Route::prefix('roles')->name('roles.')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/', 'RoleController@index')->name('index');
        Route::get('/data', 'RoleController@data')->name('data');
        Route::post('/store', 'RoleController@store')->name('store');
        Route::get('/{id}/edit', 'RoleController@edit')->name('edit');
        Route::delete('/delete', 'RoleController@delete')->name('delete');
        Route::get('/getAllRoles', 'RoleController@getAllRoles')->name('getAllRoles');
        Route::post('/submitRolesToUser', 'RoleController@submitRolesToUser')->name('submitRolesToUser');
        Route::get('/getRolesByUser', 'RoleController@getRolesByUser')->name('getRolesByUser');
    });


    Route::prefix('guru')->name('guru.')->group(function () {
        Route::get('/', [GuruController::class, 'index'])->name('index');
        Route::get('/data', [GuruController::class, 'getData'])->name('data'); // This is the important line
        Route::get('/countData', [GuruController::class, 'countData'])->name('countData'); // This is the important line

        Route::post('/store', [GuruController::class, 'store'])->name('store');
        Route::get('/{guru}/edit', [GuruController::class, 'edit'])->name('edit');
        Route::put('/{guru}', [GuruController::class, 'update'])->name('update');
        Route::delete('/{guru}', [GuruController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('users')->name('users.')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/', 'UserController@index')->name('index');
        Route::get('/create', 'UserController@create')->name('create');
        Route::post('/store','UserController@store')->name('store');
        Route::get('/data', 'UserController@data')->name('data');
        Route::post('/reset-password/{id}', 'UserController@resetPassword')->name('reset-password');
        Route::get('/{id}/edit', 'UserController@edit')->name('edit');
        Route::get('/{id}/dataEdit', 'UserController@dataEdit')->name('dataEdit');
        Route::delete('/delete/{id}','UserController@delete')->name('delete');
        Route::post('/send-account-details', 'UserController@sendAccountDetails');

        Route::get('/{userId}/generate-qr-code', 'UserController@generateQRCode');
        Route::get('/profile', 'UserController@profile')->name('dataEdit');
        Route::get('/{userId}/download-qr-code-pdf', 'UserController@downloadQRCodePDF');


    });




    Route::prefix('paket-soal')->name('paket-soal.')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/index', 'PaketSoalController@index')->name('index');
        Route::get('/data', 'PaketSoalController@data')->name('data');
        Route::post('/store', 'PaketSoalController@store')->name('store');
        Route::get('{id}/edit', 'PaketSoalController@edit')->name('edit');
        Route::post('/update/{id}', 'PaketSoalController@update')->name('update');
        Route::delete('/delete/{id}', 'PaketSoalController@destroy')->name('destroy');
        Route::get('/options', 'PaketSoalController@dataoptions')->name('options');

    });


    Route::prefix('kelas')->name('kelas.')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/index', 'KelasController@index')->name('index');
        Route::get('/data', 'KelasController@data')->name('data');
        Route::post('/store', 'KelasController@store')->name('store');
        Route::get('{id}/edit', 'KelasController@edit')->name('edit');
        Route::delete('/delete/{id}', 'KelasController@destroy')->name('destroy');
        Route::get('/options', 'KelasController@dataoptions')->name('options');
        Route::get('/getKelasData', 'KelasController@getKelasData');

    });


    Route::prefix('siswa')->name('siswa.')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/index', 'SiswaController@index')->name('index');
        Route::get('/data', 'SiswaController@data')->name('data');
        Route::get('/getStudentData', 'SiswaController@getStudentData')->name('getStudentData');
        Route::post('/store', 'SiswaController@store')->name('store');
        Route::get('{id}/edit', 'SiswaController@edit')->name('edit');
        Route::delete('/delete/{id}', 'SiswaController@destroy')->name('destroy');
    });

    Route::prefix('mata-pelajaran')->name('mata-pelajaran.')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/index', 'MataPelajaranController@index')->name('index');
        Route::get('/data', 'MataPelajaranController@data')->name('data');
        Route::post('/store', 'MataPelajaranController@store')->name('store');
        Route::get('{id}/edit', 'MataPelajaranController@edit')->name('edit');
        Route::post('/update/{id}', 'MataPelajaranController@update')->name('update');
        Route::delete('/delete/{id}', 'MataPelajaranController@destroy')->name('destroy');
        Route::get('/options', 'MataPelajaranController@dataoptions')->name('options');
        Route::get('/getMataPelajaranData', 'MataPelajaranController@getMataPelajaranData')->name('getMataPelajaranData');

    });

    Route::prefix('soal')->name('soal.')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/index', 'ManagementSoalController@index')->name('index');
        Route::get('/data', 'ManagementSoalController@data')->name('data');
        Route::post('/store', 'ManagementSoalController@store')->name('store');
        Route::get('{id}/edit', 'ManagementSoalController@edit')->name('edit');
        Route::post('/update/{id}', 'ManagementSoalController@update')->name('update');
        Route::delete('/delete/{id}', 'ManagementSoalController@destroy')->name('destroy');
        Route::get('/options', 'ManagementSoalController@dataoptions')->name('options');
        Route::post('/import', 'ManagementSoalController@import')->name('import');
    });

    Route::prefix('ujian')->name('ujian.')->namespace('App\Http\Controllers')->group(function () {
        Route::get('/index', 'UjianController@index')->name('index');
        Route::get('/data', 'UjianController@data')->name('data');
        Route::post('/store', 'UjianController@store')->name('store');
        Route::get('{id}/edit', 'UjianController@edit')->name('edit');
        Route::post('/update/{id}', 'UjianController@update')->name('update');
        Route::delete('/delete/{id}', 'UjianController@destroy')->name('destroy');
        Route::get('/options', 'UjianController@dataoptions')->name('options');
        Route::get('/start/{ujian_id}/{nis}/{paketSoal_id}', 'UjianController@start')->name('start');
        Route::get('/show', 'UjianController@show')->name('show');
        Route::post('/end', 'UjianController@end')->name('end');
        Route::get('/hasil-ujian/{id}', 'UjianController@showHasilUjian')->name('hasil-ujian');
        Route::get('/fetchHistory', 'UjianController@fetchHistory')->name('fetchHistory');

        Route::get('/download-pdf', 'UjianController@downloadPdf')->name('downloadPdf');
        Route::get('/download-excel', 'UjianController@downloadExcel')->name('downloadExcel');

    });

});






