<?php

use Illuminate\Support\Facades\Route;

// INITIALIZE PATH CONTROLLER
use App\Http\Controllers\Auth\LupaPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RilisController;
use App\Http\Controllers\Log\BerkasController;
use App\Http\Controllers\Setting\ProfilController;
use App\Http\Controllers\Setting\RolesController;
use App\Http\Controllers\Setting\PermissionsController;
use App\Http\Controllers\Pelayanan\Pasien\DaftarPasienController;
use App\Http\Controllers\Pelayanan\Pasien\PasienController;
use App\Http\Controllers\Pelayanan\Pasien\ResumeMedisController;
use App\Http\Controllers\Pelayanan\Penunjang\RISController;
use App\Http\Controllers\Display\BedController;
use App\Http\Controllers\Display\AntrianPoliController;
use App\Http\Controllers\Display\Farmasi\AntrianController as AntrianFarmasiController;
use App\Http\Controllers\Display\Farmasi\LoketController as LoketFarmasiController;
use App\Http\Controllers\Display\Farmasi\DisplayController as DisplayFarmasiController;
use App\Http\Controllers\Display\Farmasi\JenisAntrianController as JenisAntrianFarmasiController;
use App\Http\Controllers\Display\Farmasi\LoketMasterController as LoketMasterFarmasiController;
use App\Http\Controllers\EMR\EMRController;
use App\Http\Controllers\EMR\IGD\ModulMatrixController;
use App\Http\Controllers\Monitoring\MonitoringController;
use App\Http\Controllers\Klaim\Smart\SmartKlaimController;
use App\Http\Controllers\Jasper\JasperController;
use App\Http\Controllers\Jasper\JasperReportsController;

// STARTING CREATIONS
// Route::get('/', function () {
//     return view('welcome');
// });

//---------------------------------------------------------------------------------------------------------------------------------------------------------
// Jasper Function
Route::get('jasper/compile', [JasperController::class, 'compile']);
Route::get('jasper/report/{name}/{ext?}', [JasperController::class, 'report']);
Route::get('compile', [PasienController::class, 'compile'])->name('report.jrxml.compile');
Route::get('report', [PasienController::class, 'report'])->name('report.jrxml.build');
Route::get('view', [PasienController::class, 'view'])->name('report.jrxml.view');
Route::get('full', [PasienController::class, 'fullJasper'])->name('report.jrxml.full');
//---------------------------------------------------------------------------------------------------------------------------------------------------------

// AUTHENTICATION LARAVEL (AUTH UI BOOTSTRAP + SPATIE ROLES PERMISSIONS)
Route::get('/lupapassword', [LupaPasswordController::class, 'index'])->name('lupapassword.index');
Route::post('/lupapassword/update', [LupaPasswordController::class, 'update'])->name('lupapassword.update');
Auth::routes(['register' => false]); // Cannot Access /register
Route::group(['middleware' => ['web', 'auth']], function() {
    // DASHBOARD
    Route::get('/', function () { return redirect()->route('dashboard'); });
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('rilis', [RilisController::class, 'index'])->name('rilis.index');
    Route::get('test', [DashboardController::class, 'test'])->name('test');
    Route::get('clearcache', [DashboardController::class, 'clearCache'])->name('clear.cache');
    // Route::get('dashboard', function () {
    //     return view('pages.dashboard.index');
    // })->name('dashboard');

    // DISPLAY
        // TT - BED
        Route::get('display/bed', [BedController::class, 'index'])->name('display.bed.index');
        // ANTRIAN POLI
        Route::get('display/antrian/poli', [AntrianPoliController::class, 'index'])->name('display.antrian.poli.index');
        // FARMASI
        Route::get('display', [DisplayFarmasiController::class, 'index'])->name('display.antrian.farmasi.display.index');

    // SETTING - PROFIL
    Route::get('setting/profil', [ProfilController::class, 'index'])->name('profil');
    Route::get('setting/roles', [RolesController::class, 'index'])->name('roles');
    Route::get('setting/permissions', [PermissionsController::class, 'index'])->name('permissions');

    // PELAYANAN
        // KUNJUNGAN PASIEN
        Route::get('/pelayanan/pasien', [DaftarPasienController::class, 'indexRj'])->name('pelayanan.pasien');
            //IDENTITAS PASIEN
            Route::get('/pelayanan/pasien/identitas/{KUNJUNGAN}', [PasienController::class, 'indexIdentitas'])->name('pelayanan.pasien.identitas.index');
            //RESUME
            Route::get('/pelayanan/pasien/resume/{KUNJUNGAN}', [ResumeMedisController::class, 'indexResume'])->name('pelayanan.pasien.resume.index');
            Route::get('/pelayanan/pasien/resume/{KUNJUNGAN}/print', [ResumeMedisController::class, 'printResume'])->name('pelayanan.pasien.resume.print');
        // PENUNJANG MEDIS
            // RIS - RADIOLOGI
            Route::get('pelayanan/ris', [RISController::class, 'indexRIS'])->name('pelayanan.ris.index');

    // RME
    Route::get('emr', [EMRController::class, 'index'])->name('emr.index');
    Route::get('emr/{KUNJUNGAN}', [EMRController::class, 'detail'])->name('emr.detail');
        // IGD
            // FORM MATRIX
            Route::get('rme/igd/matrix', [ModulMatrixController::class, 'index'])->name('rme.igd.matrix.index');

    // DIGITAL
        // MONITORING
        Route::get('monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');

        // SMART KLAIM
            // RAWAT JALAN
            Route::get('klaim', [SmartKlaimController::class, 'index'])->name('klaim.index');
            Route::get('klaim/{KUNJUNGAN}', [SmartKlaimController::class, 'show'])->name('klaim.show');
            Route::get('klaim/farmasi/{KUNJUNGAN}', [SmartKlaimController::class, 'showFarmasi'])->name('klaim.farmasi.show');
            // RAWAT INAP
            // Route::get('klaim/smart/ri', [SmartKlaimController::class, 'indexRi'])->name('klaim.pasien.indexRi');
            // RAWAT DARURAT
            // Route::get('klaim/smart/rd', [SmartKlaimController::class, 'indexRd'])->name('klaim.pasien.indexRd');

    // LOG
        // BERKAS
            Route::get('log/berkas', [BerkasController::class, 'index'])->name('log.berkas.index');
});

Route::fallback(function () {
    return response()->view('pages.errors.custom-404', [], 404);
});
