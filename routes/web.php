<?php

use Illuminate\Support\Facades\Route;

// INITIALIZE PATH CONTROLLER
use App\Http\Controllers\Pelayanan\Pasien\DaftarPasienController;
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
Route::get('/klaim/smart', [SmartKlaimController::class, 'index'])->name('klaim.pasien');

// TAMBAHAN NIH COBA YA
// TAMBAHAN NIH COBA YA
// TAMBAHAN NIH COBA YA








// Route::get('/', function () {
//     return view('welcome');
// });
