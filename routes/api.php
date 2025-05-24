<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// INITIALIZATION
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ApiDashboardController;
use App\Http\Controllers\Setting\ProfilController;
use App\Http\Controllers\Setting\RolesController;
use App\Http\Controllers\Setting\PermissionsController;
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
Route::group(['middleware' => ['web', 'auth']], function() {
    // DASHBOARD
        Route::get('dashboard/dataDiag/{tgl}', [ApiDashboardController::class, 'dataDiag'])->name('dashboard.dataDiag');

    // SETTING
        // PERMISSION x ROLES
            // PERMISSION SETTING
            Route::get('permissions/data', [PermissionsController::class, 'dataPermissions'])->name('permissions.data');
            Route::post('permissions/create', [PermissionsController::class, 'createPermissions'])->name('permissions.create');
            Route::get('permissions/{id}/show', [PermissionsController::class, 'showPermissions'])->name('permissions.show');
            Route::post('permissions/update', [PermissionsController::class, 'updatePermissions'])->name('permissions.update');
            Route::delete('permissions/{id}/delete', [PermissionsController::class, 'deletePermissions'])->name('permissions.delete');
            // ROLES SETTING
            Route::get('roles/data', [RolesController::class, 'dataRoles'])->name('roles.data');
            Route::post('roles/create', [RolesController::class, 'createRoles'])->name('roles.create');
            Route::get('roles/{id}/show', [RolesController::class, 'showRoles'])->name('roles.show');
            Route::post('roles/update', [RolesController::class, 'updateRoles'])->name('roles.update');
            Route::delete('roles/{id}/delete', [RolesController::class, 'deleteRoles'])->name('roles.delete');
            // USER ROLES SETTING
            Route::get('roles/user/data', [RolesController::class, 'dataRolesUser'])->name('roles.user.data');
            Route::get('roles/user/{id}/show', [RolesController::class, 'showRolesUser'])->name('roles.user.show');
            Route::post('roles/user/update', [RolesController::class, 'updateRolesUser'])->name('roles.user.update');
            Route::delete('roles/user/{id}/delete', [RolesController::class, 'deleteRolesUser'])->name('roles.user.delete');

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
            Route::get('pasien/{kunjungan}/rad', [ApiMonitoringController::class, 'compileRad'])->name('api.pasien.rad');
            Route::get('pasien/{kunjungan}/triage', [ApiMonitoringController::class, 'compileTriage'])->name('api.pasien.triage');
            Route::get('pasien/{kunjungan}/operasi', [ApiMonitoringController::class, 'compileOperasi'])->name('api.pasien.operasi');
            // TTE
                // RAWAT JALAN
                Route::get('pasien/{kunjungan}/ttdRj', [ApiMonitoringController::class, 'showTtdResumeRj'])->name('api.pasien.ttdResumeRj');
                Route::post('pasien/resume/ttdRj/simpan', [ApiMonitoringController::class, 'storeTtdResumeRj'])->name('api.pasien.storeTtdResumeRj');
        // SMART KLAIM
            // BERKAS KLAIM
            Route::get('klaim/upload/{id}/show', [ApiSmartKlaimController::class, 'showUpload'])->name('api.klaim.showUpload');
            Route::post('klaim/upload', [ApiSmartKlaimController::class, 'upload'])->name('api.klaim.upload');
            Route::delete('klaim/upload/{id}/hapus', [ApiSmartKlaimController::class, 'hapusUpload'])->name('api.klaim.hapusUpload');
            Route::post('klaim/submit', [ApiSmartKlaimController::class, 'submit'])->name('api.klaim.submit');
            Route::get('klaim/{sep}/verif', [ApiSmartKlaimController::class, 'verifSep'])->name('api.klaim.verifSep');
            Route::get('klaim/{kunjungan}/catatan', [ApiSmartKlaimController::class, 'getCatatan'])->name('api.klaim.getCatatan');
            Route::get('klaim/catatan/{id}', [ApiSmartKlaimController::class, 'showCatatan'])->name('api.klaim.showCatatan');
            Route::post('klaim/catatan/simpan', [ApiSmartKlaimController::class, 'simpanCatatan'])->name('api.klaim.simpanCatatan');
            Route::get('klaim/catatan/{id}/solved', [ApiSmartKlaimController::class, 'solvedCatatan'])->name('api.klaim.solvedCatatan');
            Route::get('klaim/catatan/{id}/unsolved', [ApiSmartKlaimController::class, 'unsolvedCatatan'])->name('api.klaim.unsolvedCatatan');
            Route::post('klaim/catatan/{id}/ubah', [ApiSmartKlaimController::class, 'ubahCatatan'])->name('api.klaim.ubahCatatan');
            Route::delete('klaim/catatan/{id}/hapus', [ApiSmartKlaimController::class, 'hapusCatatan'])->name('api.klaim.hapusCatatan');
            Route::get('klaim/{kunjungan}/data', [ApiSmartKlaimController::class, 'getKlaim'])->name('api.klaim.getKlaim');
            Route::get('klaim/{kunjungan}/verifikasi', [ApiSmartKlaimController::class, 'verifikasiKlaim'])->name('api.klaim.verifikasiKlaim');
            Route::get('klaim/{kunjungan}/batalverifikasi', [ApiSmartKlaimController::class, 'batalVerifikasiKlaim'])->name('api.klaim.batalVerifikasiKlaim');
            Route::delete('klaim/{kunjungan}/hapus', [ApiSmartKlaimController::class, 'hapusKlaim'])->name('api.klaim.hapusKlaim');
            Route::get('klaim/{tahun}/{bulan}/{kunjungan}/pdf', [ApiSmartKlaimController::class, 'showKlaim'])->name('api.klaim.showKlaim');
            Route::get('klaim/table/{pel}/{bln}/{dpjp}', [ApiSmartKlaimController::class, 'table'])->name('api.klaim.table');

    // PELAYANAN PASIEN
    Route::get('pelayanan/pasien/rj/{status}/{tgls}/{tgle}/{dpjp}', [ApiResumeMedisController::class, 'tableRj'])->name('api.pelayanan.pasien.rj');
        // RESUME
        Route::get('pelayanan/pasien/rj/resume/{kunjungan}', [ApiResumeMedisController::class, 'compileResumeRj'])->name('api.pasien.resume.rj');
        Route::post('pelayanan/pasien/resume/ttd/simpan', [ResumeMedisController::class, 'storeTtd'])->name('api.pasien.resume.ttd');

    // EKLAIM

});

