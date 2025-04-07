<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// INITIALIZATION
use App\Http\Controllers\Klaim\Smart\SmartKlaimController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// CONTOH BRIDGING BPJS
Route::get('/surkon/table', [App\Http\Controllers\Simgos\RegOnline\surkonController::class, 'table'])->name('surkon.table');

// CONTOH BRIDGING SIMGOS
Route::get('/simgos/kunjungan/pasien', [App\Http\Controllers\Pelayanan\Pasien\DaftarPasienController::class, 'table'])->name('simgos.kunjungan.pasien');

//---------------------------------------------------------------------------------------------------------------------------------------------------------
// DIGITAL - SMART KLAIM
Route::get('klaim/smart/rj', [SmartKlaimController::class, 'tableRj'])->name('api.klaim.pasien.rj');
Route::get('klaim/smart/ri', [SmartKlaimController::class, 'tableRi'])->name('api.klaim.pasien.ri');
Route::get('klaim/smart/rd', [SmartKlaimController::class, 'tableRd'])->name('api.klaim.pasien.rd');
