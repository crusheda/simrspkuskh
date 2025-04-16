<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// INITIALIZATION
use App\Http\Controllers\Klaim\Smart\SmartKlaimController;
use App\Http\Controllers\Klaim\Smart\ApiSmartKlaimController; // API
use App\Http\Controllers\Pelayanan\Pasien\ResumeMedisController;
use App\Http\Controllers\Pelayanan\Pasien\ApiResumeMedisController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// CONTOH BRIDGING BPJS
Route::get('/surkon/table', [App\Http\Controllers\Simgos\RegOnline\surkonController::class, 'table'])->name('surkon.table');

// CONTOH BRIDGING SIMGOS
Route::get('/simgos/kunjungan/pasien', [App\Http\Controllers\Pelayanan\Pasien\DaftarPasienController::class, 'table'])->name('simgos.kunjungan.pasien');

//---------------------------------------------------------------------------------------------------------------------------------------------------------
// DIGITAL - SMART KLAIM
Route::get('klaim/smart/rj/{status}', [ApiSmartKlaimController::class, 'tableRj'])->name('api.klaim.pasien.rj');
// Route::get('klaim/smart/rj/check/{kunjungan}', [ApiSmartKlaimController::class, 'checkList'])->name('api.monitoring.check.rj');
Route::get('klaim/smart/ri/{status}', [ApiSmartKlaimController::class, 'tableRi'])->name('api.klaim.pasien.ri');
Route::get('klaim/smart/rd/{status}', [ApiSmartKlaimController::class, 'tableRd'])->name('api.klaim.pasien.rd');
    // MONITORING
    Route::get('ujicoba', [ApiSmartKlaimController::class, 'compile'])->name('api.ujicoba.compile');
    Route::get('pasien/{kunjungan}/tindakan', [ApiSmartKlaimController::class, 'tindakan'])->name('api.pasien.tindakan');
    Route::get('pasien/{kunjungan}/cppt', [ApiSmartKlaimController::class, 'cppt'])->name('api.pasien.cppt');
    Route::get('pasien/{kunjungan}/skdp', [ApiSmartKlaimController::class, 'compileSkdp'])->name('api.pasien.skdp');
    Route::get('pasien/{kunjungan}/sep', [ApiSmartKlaimController::class, 'compileSep'])->name('api.pasien.sep');

// PELAYANAN PASIEN
    // RESUME
    Route::get('pelayanan/pasien/rj/resume/{kunjungan}', [ApiResumeMedisController::class, 'compileResumeRj'])->name('api.pasien.resume.rj');
    Route::post('pelayanan/pasien/resume/ttd/simpan', [ResumeMedisController::class, 'storeTtd'])->name('api.pasien.resume.ttd');
