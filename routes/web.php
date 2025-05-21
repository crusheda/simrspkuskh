<?php

use Illuminate\Support\Facades\Route;

// INITIALIZE PATH CONTROLLER
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Setting\ProfilController;
use App\Http\Controllers\Setting\RolesController;
use App\Http\Controllers\Setting\PermissionsController;
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
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('test', [DashboardController::class, 'test'])->name('test');
    Route::get('clearcache', [DashboardController::class, 'clearCache'])->name('clear.cache');
    // Route::get('dashboard', function () {
    //     return view('pages.dashboard.index');
    // })->name('dashboard');

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

    // DIGITAL
        // MONITORING
        Route::get('monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');

        // SMART KLAIM
            // RAWAT JALAN
            Route::get('klaim', [SmartKlaimController::class, 'index'])->name('klaim.index');
            Route::get('klaim/{KUNJUNGAN}', [SmartKlaimController::class, 'show'])->name('klaim.show');
            // RAWAT INAP
            // Route::get('klaim/smart/ri', [SmartKlaimController::class, 'indexRi'])->name('klaim.pasien.indexRi');
            // RAWAT DARURAT
            // Route::get('klaim/smart/rd', [SmartKlaimController::class, 'indexRd'])->name('klaim.pasien.indexRd');
});

Route::fallback(function () {
    return response()->view('pages.errors.custom-404', [], 404);
});
