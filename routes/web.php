<?php

use Illuminate\Support\Facades\Route;

// INITIALIZE PATH CONTROLLER
use App\Http\Controllers\Setting\ProfilController;
use App\Http\Controllers\Pelayanan\Pasien\DaftarPasienController;
use App\Http\Controllers\Pelayanan\Pasien\PasienController;
use App\Http\Controllers\Pelayanan\Pasien\ResumeMedisController;
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
Auth::routes(['register' => false]); // Cannot Access /register
Route::group(['middleware' => ['web', 'auth']], function() {
    // DASHBOARD
    Route::get('/', function () { return redirect()->route('dashboard'); });
    Route::get('dashboard', function () {
        return view('pages.dashboard.index');
    })->name('dashboard');

    // SETTING - PROFIL
    Route::get('setting/profil', [ProfilController::class, 'index'])->name('profil');

    // PELAYANAN
        // KUNJUNGAN PASIEN
        Route::get('/pelayanan/pasien', [DaftarPasienController::class, 'indexRj'])->name('pelayanan.pasien');
            //IDENTITAS PASIEN
            Route::get('/pelayanan/pasien/identitas/{KUNJUNGAN}', [PasienController::class, 'indexIdentitas'])->name('pelayanan.pasien.identitas.index');
            //RESUME
            Route::get('/pelayanan/pasien/resume/{KUNJUNGAN}', [ResumeMedisController::class, 'indexResume'])->name('pelayanan.pasien.resume.index');
            Route::get('/pelayanan/pasien/resume/{KUNJUNGAN}/print', [ResumeMedisController::class, 'printResume'])->name('pelayanan.pasien.resume.print');

    // DIGITAL
        // MONITORING
        Route::get('monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');

        // SMART KLAIM
            // RAWAT JALAN
            Route::get('klaim', [SmartKlaimController::class, 'index'])->name('klaim.index');
            // RAWAT INAP
            // Route::get('klaim/smart/ri', [SmartKlaimController::class, 'indexRi'])->name('klaim.pasien.indexRi');
            // RAWAT DARURAT
            // Route::get('klaim/smart/rd', [SmartKlaimController::class, 'indexRd'])->name('klaim.pasien.indexRd');
});
