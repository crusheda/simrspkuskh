<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// INITIALIZATION
use App\Http\Controllers\Setting\ProfilController;
use App\Http\Controllers\Klaim\Smart\SmartKlaimController;
use App\Http\Controllers\Klaim\Smart\ApiSmartKlaimController;
use App\Http\Controllers\Monitoring\MonitoringController;
use App\Http\Controllers\Monitoring\ApiMonitoringController;
use App\Http\Controllers\Pelayanan\Pasien\ResumeMedisController;
use App\Http\Controllers\Pelayanan\Pasien\ApiResumeMedisController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// CONTOH BRIDGING BPJS
Route::get('/surkon/table', [App\Http\Controllers\Simgos\RegOnline\surkonController::class, 'table'])->name('surkon.table');

// CONTOH BRIDGING SIMGOS
Route::get('/simgos/kunjungan/pasien', [App\Http\Controllers\Pelayanan\Pasien\DaftarPasienController::class, 'table'])->name('simgos.kunjungan.pasien');

//-----------------------------------------------------------------    A  P  I    -----------------------------------------------------------------
// DIGITAL
Route::get('monitoring/rj/{rawat}/{status}/{tgls}/{tgle}/{dpjp}', [ApiMonitoringController::class, 'tableRj'])->name('api.monitoring.rj');
    // MONITORING - KLAIM
        // UMUM
        Route::get('pasien/{kunjungan}/tindakan', [ApiMonitoringController::class, 'tindakan'])->name('api.pasien.tindakan');
        Route::get('pasien/{kunjungan}/cppt', [ApiMonitoringController::class, 'cppt'])->name('api.pasien.cppt');
        Route::get('pasien/{kunjungan}/skdp', [ApiMonitoringController::class, 'compileSkdp'])->name('api.pasien.skdp');
        Route::get('pasien/{kunjungan}/sep', [ApiMonitoringController::class, 'compileSep'])->name('api.pasien.sep');
        Route::get('pasien/{kunjungan}/resumeRj', [ApiMonitoringController::class, 'compileResumeRj'])->name('api.pasien.resumeRj');
        Route::get('pasien/{kunjungan}/individual', [ApiMonitoringController::class, 'compileIndividual'])->name('api.pasien.individual');
        Route::get('pasien/{kunjungan}/billing', [ApiMonitoringController::class, 'compileBilling'])->name('api.pasien.billing');
        Route::get('pasien/{kunjungan}/lab', [ApiMonitoringController::class, 'compileLab'])->name('api.pasien.lab');
        Route::get('pasien/{kunjungan}/triage', [ApiMonitoringController::class, 'compileTriage'])->name('api.pasien.triage');
        Route::get('pasien/{kunjungan}/operasi', [ApiMonitoringController::class, 'compileOperasi'])->name('api.pasien.operasi');
        // TTE
            // RAWAT JALAN
            Route::get('pasien/{kunjungan}/ttdRj', [ApiMonitoringController::class, 'showTtdResumeRj'])->name('api.pasien.ttdResumeRj');
            Route::post('pasien/resume/ttdRj/simpan', [ApiMonitoringController::class, 'storeTtdResumeRj'])->name('api.pasien.storeTtdResumeRj');
    // SMART KLAIM
        // BERKAS KLAIM
        Route::post('klaim/submit', [ApiSmartKlaimController::class, 'submit'])->name('api.klaim.submit');
        Route::get('klaim/{kunjungan}/data', [ApiSmartKlaimController::class, 'getKlaim'])->name('api.klaim.getKlaim');
        Route::delete('klaim/{kunjungan}/hapus', [ApiSmartKlaimController::class, 'hapusKlaim'])->name('api.klaim.hapusKlaim');
        Route::get('klaim/{tahun}/{bulan}/{kunjungan}/pdf', [ApiSmartKlaimController::class, 'showKlaim'])->name('api.klaim.showKlaim');
        Route::get('klaim/table/{pel}/{bln}/{dpjp}', [ApiSmartKlaimController::class, 'table'])->name('api.klaim.table');

// PELAYANAN PASIEN
Route::get('pelayanan/pasien/rj/{status}/{tgls}/{tgle}/{dpjp}', [ApiResumeMedisController::class, 'tableRj'])->name('api.pelayanan.pasien.rj');
    // RESUME
    Route::get('pelayanan/pasien/rj/resume/{kunjungan}', [ApiResumeMedisController::class, 'compileResumeRj'])->name('api.pasien.resume.rj');
    Route::post('pelayanan/pasien/resume/ttd/simpan', [ResumeMedisController::class, 'storeTtd'])->name('api.pasien.resume.ttd');
