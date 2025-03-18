<?php

use Illuminate\Support\Facades\Route;

// INITIALIZE PATH CONTROLLER
use App\Http\Controllers\Pelayanan\Pasien\DaftarPasienController;
use App\Http\Controllers\Pelayanan\Pasien\PasienController;
use App\Http\Controllers\Klaim\Smart\SmartKlaimController;
use App\Http\Controllers\Jasper\JasperController;
use App\Http\Controllers\Jasper\JasperReportsController;

// STARTING CREATIONS
// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('pages.dashboard.index');
})->name('dashboard');

Route::get('/pelayanan/pasien', [DaftarPasienController::class, 'index'])->name('pelayanan.pasien');
Route::get('/pelayanan/pasien/identitas/{KUNJUNGAN}', [PasienController::class, 'indexIdentitas'])->name('pelayanan.pasien.identitas.index');
Route::get('/pelayanan/pasien/resume/{KUNJUNGAN}', [PasienController::class, 'indexResume'])->name('pelayanan.pasien.resume.index');
Route::get('/klaim/smart', [SmartKlaimController::class, 'index'])->name('klaim.pasien');

//Jasper
Route::get('jasper/compile', [JasperController::class, 'compile']);
Route::get('jasper/report/{name}/{ext?}', [JasperController::class, 'report']);
Route::get('/compile', [PasienController::class, 'compile'])->name('report.jrxml.compile');
Route::get('/report', [PasienController::class, 'report'])->name('report.jrxml.build');
Route::get('/view', [PasienController::class, 'view'])->name('report.jrxml.view');
Route::get('/full', [PasienController::class, 'fullJasper'])->name('report.jrxml.full');

// AUTHENTICATION LARAVEL (AUTH UI BOOTSTRAP + SPATIE ROLES PERMISSIONS)
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::group(['middleware' => ['auth']], function() {

});
