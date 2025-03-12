<?php

use Illuminate\Support\Facades\Route;

// INITIALIZE PATH CONTROLLER
use App\Http\Controllers\Pelayanan\Pasien\DaftarPasienController;
use App\Http\Controllers\Pelayanan\Pasien\PasienController;
use App\Http\Controllers\Klaim\Smart\SmartKlaimController;

// STARTING CREATIONS
// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard', function () {
    return view('pages.dashboard.index');
})->name('dashboard');

Route::get('/pelayanan/pasien', [DaftarPasienController::class, 'index'])->name('pelayanan.pasien');
Route::get('/pelayanan/pasien/identitas/{KUNJUNGAN}', [PasienController::class, 'indexIdentitas'])->name('pelayanan.pasien.identitas.index');
Route::get('/pelayanan/pasien/resume/{KUNJUNGAN}', [PasienController::class, 'indexResume'])->name('pelayanan.pasien.resume.index');
Route::get('/klaim/smart', [SmartKlaimController::class, 'index'])->name('klaim.pasien');

// TAMBAHAN NIH COBA YA
// TAMBAHAN NIH COBA YA
// TAMBAHAN NIH COBA YA








// Route::get('/', function () {
//     return view('welcome');
// });
