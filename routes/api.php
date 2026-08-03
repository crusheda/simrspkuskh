<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// INITIALIZATION
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ApiDashboardController;
use App\Http\Controllers\Tools\Bpjs\ICareController;
use App\Http\Controllers\Log\BerkasController;
use App\Http\Controllers\Setting\ProfilController;
use App\Http\Controllers\Setting\RolesController;
use App\Http\Controllers\Setting\PermissionsController;
use App\Http\Controllers\Display\BedController;
use App\Http\Controllers\Display\AntrianPoliController;
use App\Http\Controllers\Display\AntrianAdmisiController;
use App\Http\Controllers\EMR\EMRController;
use App\Http\Controllers\EMR\Form\GawatDarurat\PengkajianGawatDaruratController;
use App\Http\Controllers\EMR\Form\RawatJalan\PengkajianRawatJalanDewasaController;
use App\Http\Controllers\EMR\ApiRehabMedikController;
use App\Http\Controllers\EMR\ApiNewRehabMedikController;
use App\Http\Controllers\EMR\ApiMatriksController;
use App\Http\Controllers\EMR\ApiKonsulController;
use App\Http\Controllers\EMR\ApiUploadController;
use App\Http\Controllers\Klaim\Smart\SmartKlaimController;
use App\Http\Controllers\Klaim\Smart\ApiSmartKlaimController;
use App\Http\Controllers\Monitoring\MonitoringController;
use App\Http\Controllers\Monitoring\ApiMonitoringController;
use App\Http\Controllers\Pelayanan\Penunjang\RISController;
use App\Http\Controllers\Pelayanan\Pasien\ResumeMedisController;
use App\Http\Controllers\Pelayanan\Pasien\ApiResumeMedisController;
use App\Http\Controllers\Display\RatingController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// CONTOH BRIDGING BPJS
Route::get('/surkon/table', [App\Http\Controllers\Simgos\RegOnline\surkonController::class, 'table'])->name('surkon.table');

// CONTOH BRIDGING SIMGOS
Route::get('/simgos/kunjungan/pasien', [App\Http\Controllers\Pelayanan\Pasien\DaftarPasienController::class, 'table'])->name('simgos.kunjungan.pasien');

//---------------------------------------------------------------    A  P  I    L  O  K  A  L    -----------------------------------------------------------------
Route::group(['prefix' => 'v2', 'as' => ''], function () { // SIRMED v.2
    // DIGITAL
        // MEDICAL RECORD
            // FORM PENGKAJIAN
                // GAWAT DARURAT
                    // DOKTER
                    Route::get('emr/form/pengkajian/gd/dr/{kunjungan}', [PengkajianGawatDaruratController::class, 'getFormDokter']);
                    Route::post('emr/form/pengkajian/gd/dr/simpan', [PengkajianGawatDaruratController::class, 'simpanFormDokter']);
                    // PERAWAT
                    Route::post('emr/form/pengkajian/gd/pr/simpan', [PengkajianGawatDaruratController::class, 'simpanFormPerawat']);

                // RAWAT JALAN DEWASA
                Route::post('emr/form/pengkajian/rjd/dr/simpan', [PengkajianRawatJalanDewasaController::class, 'simpanFormDokter']);
                Route::post('emr/form/pengkajian/rjd/pr/simpan', [PengkajianRawatJalanDewasaController::class, 'simpanFormPerawat']);
});

// SIRMED v.1
//API PUBLIC
    // DISPLAY
        // TEMPAT TIDUR
            Route::get('display/tt', [BedController::class, 'getDisplayTt'])->name('api.display.tt');
        // POLI
            Route::post('display/antrian/poli', [AntrianPoliController::class, 'getDisplayAntrianPoli'])->name('api.display.antrian.poli');
            Route::post('display/antrian/poli/update', [AntrianPoliController::class, 'updatePanggilanAntrian'])->name('api.display.antrian.update');

        // ADMISI
            Route::post('display/antrian/admisi', [AntrianAdmisiController::class, 'getDisplayAntrianAdmisi'])->name('api.display.antrian.admisi');
            Route::post('display/antrian/admisi/update', [AntrianAdmisiController::class, 'updatePanggilanAntrian'])->name('api.display.antrian.admisi.update');

        // RATING
            Route::post('rating', [RatingController::class,'store'])->name('api.rating.store');

//API PRIVATE
Route::group(['middleware' => ['web', 'auth']], function() {
    // DASHBOARD
        Route::get('dashboard/dataDiag/{tgl}', [ApiDashboardController::class, 'dataDiag'])->name('dashboard.dataDiag');

    //PROFIL
        //TTD PEGAWAI
        Route::get('pegawai/{NIP}/ttd', [ProfilController::class, 'showTtdPeg'])->name('api.pegawai.ttdPeg');
        // Route::post('pegawai/profil/ttdPeg/simpan', [ProfilController::class, 'storeTtdPeg'])->name('api.pegawai.storeTtdPeg');
        Route::post('pegawai/ttd', [ProfilController::class, 'storeTtdPeg'])->name('api.pegawai.storeTtdPeg');

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

    // EMR
        // I - CARE
        Route::get('emr/riwayat/kunjungan/{RM}', [EMRController::class, 'getRiwayatKunjungan'])->name('api.emr.riwayat.kunjungan');
        Route::get('emr/bpjs/icare/{RM}', [ICareController::class, 'getICare'])->name('api.emr.bpjs.icare');
        Route::get('emr/bpjs/icare/auto/{RM}', [ICareController::class, 'apiICare'])->name('api.emr.bpjs.icare.auto');

        // REHABILITASI MEDIK
            // OLD REHAB MEDIK
                // FORM KFR
                Route::post('emr', [EMRController::class, 'table'])->name('api.emr');
                Route::get('emr/penjamin', [EMRController::class, 'penjamin'])->name('api.penjamin');
                Route::get('emr/ruangan/{id}', [EMRController::class, 'ruangan'])->name('api.ruangan');
                Route::get('emr/ruangan/{ruangan}/dpjp', [EMRController::class, 'dpjp'])->name('api.dpjp');
                Route::get('emr/{NORM}/fkfr/{KUNJUNGAN}', [ApiRehabMedikController::class, 'getFormKfr'])->name('api.emr.fkfr.get');
                Route::post('emr/fkfr/formbaru', [ApiRehabMedikController::class, 'simpanFormKfrBaru'])->name('api.emr.fkfr.simpanformbaru');
                Route::post('emr/fkfr/formlama', [ApiRehabMedikController::class, 'simpanFormKfrLama'])->name('api.emr.fkfr.simpanformlama');
                Route::get('emr/fkfr/{id}', [ApiRehabMedikController::class, 'getFormKfrByGroup'])->name('api.emr.fkfr.getFormKfrByGroup');
                Route::delete('emr/fkfr/{NOMOR}/hapus/{USER}', [ApiRehabMedikController::class, 'hapusFormKfr'])->name('api.emr.fkfr.hapusFormKfr');
                Route::delete('emr/fkfr/{NOMOR}/hapus/{USER}/all/{GROUP}', [ApiRehabMedikController::class, 'hapusFormKfrAll'])->name('api.emr.fkfr.hapusFormKfrAll');
                Route::get('emr/fkfr/{KUNJUNGAN}/preview/{GROUP}', [ApiRehabMedikController::class, 'compileFormKfr'])->name('api.emr.fkfr.preview');
                // JP - JADWAL PELAYANAN
                Route::get('emr/{NORM}/jp/{KUNJUNGAN}', [ApiRehabMedikController::class, 'getFormJp'])->name('api.emr.jp.get');
                Route::get('emr/jp/{id}', [ApiRehabMedikController::class, 'getFormJpByGroup'])->name('api.emr.fkfr.getFormJpByGroup');
                Route::get('emr/jp/{KUNJUNGAN}/preview/{GROUP}', [ApiRehabMedikController::class, 'compileFormJp'])->name('api.emr.jp.preview');
                Route::post('emr/jp', [ApiRehabMedikController::class, 'simpanJp'])->name('api.emr.jp.simpanJp');
                Route::delete('emr/jp/hapus/{id}', [ApiRehabMedikController::class, 'hapusFormJp'])->name('api.emr.jp.hapusFormJp');
                // KS - REKOMENDASI DOKTER
                Route::get('emr/{NORM}/ks/{KUNJUNGAN}', [ApiRehabMedikController::class, 'getFormKs'])->name('api.emr.ks.get');
                Route::get('emr/ks/{id}', [ApiRehabMedikController::class, 'getFormKsByGroup'])->name('api.emr.fkfr.getFormKsByGroup');
                Route::get('emr/ks/{KUNJUNGAN}/preview/{GROUP}', [ApiRehabMedikController::class, 'compileFormKs'])->name('api.emr.ks.preview');
                Route::post('emr/ks', [ApiRehabMedikController::class, 'simpanKs'])->name('api.emr.ks.simpan');
                Route::delete('emr/ks/hapus/{id}', [ApiRehabMedikController::class, 'hapusFormKs'])->name('api.emr.ks.hapus');
            // NEW REHAB MEDIK
                // FORM KFR
                Route::get('emr/kfr/rm/{NORM}/{KUNJUNGAN}', [ApiNewRehabMedikController::class, 'getByRM'])->name('api.emr.kfr.getByRM');
                Route::get('emr/kfr/rm/{NORM}/{KUNJUNGAN}/{TGLS}', [ApiNewRehabMedikController::class, 'getByRMnTgl'])->name('api.emr.kfr.getByRMnTgl');
                Route::get('emr/kfr/{KUNJUNGAN}', [ApiNewRehabMedikController::class, 'get'])->name('api.emr.kfr.get');
                Route::get('emr/kfr/{KUNJUNGAN}/show', [ApiNewRehabMedikController::class, 'lihatFormKfr'])->name('api.emr.kfr.lihatFormKfr');
                Route::get('emr/kfr/{KUNJUNGAN}/cppt/{IDCPPT}/copy', [ApiNewRehabMedikController::class, 'copyCpptKfr'])->name('api.emr.kfr.copyCppt');
                Route::get('emr/kfr/{RM}/cppt/{KUNJUNGAN}/{TGLS}', [ApiNewRehabMedikController::class, 'getCppt'])->name('api.emr.kfr.getCppt');
                Route::get('emr/kfr/{KUNJUNGAN}/generate', [ApiNewRehabMedikController::class, 'generateUlangFormKfr'])->name('api.emr.kfr.generateUlangFormKfr');
                Route::post('emr/kfr/sync', [ApiNewRehabMedikController::class, 'syncFormLama'])->name('api.emr.kfr.syncFormLama');
                Route::post('emr/kfr/unsync', [ApiNewRehabMedikController::class, 'unsyncFormLama'])->name('api.emr.kfr.unsyncFormLama');
                Route::post('emr/kfr/store', [ApiNewRehabMedikController::class, 'store'])->name('api.emr.kfr.store');
                Route::put('emr/kfr/update/{IDCPPT}', [ApiNewRehabMedikController::class, 'update'])->name('api.emr.kfr.update');
                Route::get('emr/kfr/ubah/{KUNJUNGAN}/{NORM}/{KODE}', [ApiNewRehabMedikController::class, 'ubah'])->name('api.emr.kfr.ubah'); // Ubah Group & Periode Kunjungan
                Route::post('emr/kfr/destroy', [ApiNewRehabMedikController::class, 'destroy'])->name('api.emr.kfr.destroy');
                Route::post('emr/kfr/destroy/all', [ApiNewRehabMedikController::class, 'destroyAll'])->name('api.emr.kfr.destroyAll');
                // PROGRAM TERAPI
                Route::get('emr/pterapi/{KUNJUNGAN}', [ApiNewRehabMedikController::class, 'getProgram'])->name('api.emr.pterapi.getProgram');
                Route::get('emr/pterapi/{KUNJUNGAN}/cppt/{IDCPPT}/copy', [ApiNewRehabMedikController::class, 'copyCpptProgram'])->name('api.emr.pterapi.copyCpptProgram');
                Route::get('emr/pterapi/{RM}/cppt/{KUNJUNGAN}/{TGLS}', [ApiNewRehabMedikController::class, 'getCpptProgram'])->name('api.emr.pterapi.getCpptProgram');
                Route::get('emr/pterapi/{KUNJUNGAN}/riwayat', [ApiNewRehabMedikController::class, 'getRiwayatProgram'])->name('api.emr.pterapi.getRiwayatProgram');
                Route::get('emr/pterapi/get/{KUNJUNGAN}/{GROUP}/{QUEUE}', [ApiNewRehabMedikController::class, 'getProgramEdit'])->name('api.emr.pterapi.getProgramEdit');
                Route::put('emr/pterapi/update', [ApiNewRehabMedikController::class, 'updateProgramTerapi'])->name('api.emr.pterapi.updateProgramTerapi');
                Route::get('emr/pterapi/{KUNJUNGAN}/{GROUP}/{QUEUE}', [ApiNewRehabMedikController::class, 'lihatFormProgramTerapi'])->name('api.emr.pterapi.lihatFormProgramTerapi');
                Route::post('emr/pterapi/store', [ApiNewRehabMedikController::class, 'storeProgram'])->name('api.emr.pterapi.storeProgram');
                Route::post('emr/pterapi/destroy', [ApiNewRehabMedikController::class, 'destroyProgram'])->name('api.emr.pterapi.destroyProgram');
                // JADWAL PELAYANAN
                Route::get('emr/jadwal/{KUNJUNGAN}', [ApiNewRehabMedikController::class, 'getFormJadwalPelayanan'])->name('api.emr.jadwal.getFormJadwalPelayanan');
                Route::get('emr/jadwal/{KUNJUNGAN}/preview', [ApiNewRehabMedikController::class, 'previewFormJadwalPelayanan'])->name('api.emr.jadwal.previewFormJadwalPelayanan');
                Route::post('emr/jadwal/ttd-pasien', [ApiNewRehabMedikController::class, 'storeFormJadwalPelayanan'])->name('api.emr.jadwal.storeFormJadwalPelayanan');
                Route::post('emr/jadwal/regenerate', [ApiNewRehabMedikController::class, 'regenerateFormJadwalPelayanan'])->name('api.emr.jadwal.regenerateFormJadwalPelayanan');
                Route::delete('emr/jadwal/hapus/{KUNJUNGAN}', [ApiNewRehabMedikController::class, 'destroyFormJadwalPelayanan'])->name('api.emr.jadwal.destroyFormJadwalPelayanan');
            // RIS
            Route::get('dcom/{filename}', [RISController::class, 'getDCOM'])->name('api.emr.ris.getDCOM');

    // MATRIKS
    // Route::get('emr/matriks/{NOMOR}', [ApiMatriksController::class, 'showMatriks'])->name('api.emr.matriks.show');
    // Route::post('emr/matriks', [ApiMatriksController::class, 'store'])->name('api.emr.matriks.store');
    // Route::get('emr/{NOMOR}/matriks', [ApiMatriksController::class, 'compileMatriks'])->name('api.emr.matriks.preview');
    Route::prefix('emr/matriks')->group(function () {
        Route::get('{NOMOR}', [ApiMatriksController::class, 'showMatriks'])->name('api.emr.matriks.show');
        Route::post('/', [ApiMatriksController::class, 'store'])->name('api.emr.matriks.store');
        Route::get('{NOMOR}/preview', [ApiMatriksController::class, 'previewMatriks'])->name('api.emr.matriks.preview');
    });

    // KONSUL
    Route::get('emr/konsul/{NOMOR}', [ApiKonsulController::class, 'showKonsul'])->name('api.emr.konsul.show');
    Route::get('emr/konsul/masuk/{NOMOR}', [ApiKonsulController::class, 'masukKonsul'])->name('api.emr.konsul.masuk.show');
    Route::get('emr/konsul/jawaban/{NOMOR}', [ApiKonsulController::class, 'getJawabanKonsul'])->name('api.emr.konsul.jawaban.show');
    Route::get('emr/konsulk/ruangan', [ApiKonsulController::class, 'listRuangan'])->name('api.emr.konsul.ruangan.show');
    Route::get('emr/konsulk/ruangan/dokter/{id}', [ApiKonsulController::class, 'dokterByRuangan']);
    Route::post('emr/konsulko/tambah', [ApiKonsulController::class, 'store'])->name('api.emr.simpankonsul');
    Route::post('emr/konsulkon/jawaban', [ApiKonsulController::class, 'simpanJawaban']);
    Route::get('emr/konsulkons/jawaban/{nomor}', [ApiKonsulController::class, 'getJawabKonsul']);
    Route::post('emr/konsulkonsu/batal/{nomor}', [ApiKonsulController::class, 'batal']);
    Route::get('emr/konsulkonsul/cetak/{nomor}', [ApiKonsulController::class, 'cetakPDF'])->name('konsul.cetak');
    //UPLOAD FILE
    Route::get('emr/file-upload/{nomor}', [ApiUploadController::class, 'index'])->name('file.tambahan.index');
    Route::post('emr/file-upload/{nomor}', [ApiUploadController::class, 'store'])->name('file.tambahan.upload');
    Route::get('emr/file-upload/{nomor}/list', [ApiUploadController::class, 'listFiles'])->name('file.tambahan.list');
    Route::delete('emr/file-upload/{nomor}/{id}', [ApiUploadController::class, 'destroy'])->name('file.tambahan.delete');


    // DIGITAL
    Route::post('monitoring', [ApiMonitoringController::class, 'table'])->name('api.monitoring');
        // MONITORING - KLAIM
            // UMUM
            Route::get('pasien/{kunjungan}/tindakan', [ApiMonitoringController::class, 'tindakan'])->name('api.pasien.tindakan');
            Route::get('pasien/{kunjungan}/cppt', [ApiMonitoringController::class, 'cppt'])->name('api.pasien.cppt');
            Route::get('pasien/{kunjungan}/skdp', [ApiMonitoringController::class, 'compileSkdp'])->name('api.pasien.skdp');
            Route::get('pasien/{kunjungan}/sep', [ApiMonitoringController::class, 'compileSep'])->name('api.pasien.sep');
            Route::get('pasien/{kunjungan}/resumeRj', [ApiMonitoringController::class, 'compileResumeRj'])->name('api.pasien.resumeRj');
            Route::delete('pasien/{kunjungan}/hapusTtdResumeRj', [ApiMonitoringController::class, 'hapusTtdResumeRj'])->name('api.pasien.hapusTtdResumeRj');
            Route::get('pasien/{kunjungan}/individual', [ApiMonitoringController::class, 'compileIndividual'])->name('api.pasien.individual');
            Route::get('pasien/{kunjungan}/billing', [ApiMonitoringController::class, 'compileBilling'])->name('api.pasien.billing');
            Route::get('pasien/{kunjungan}/lab', [ApiMonitoringController::class, 'compileLab'])->name('api.pasien.lab');
            Route::get('pasien/{kunjungan}/rad', [ApiMonitoringController::class, 'compileRad'])->name('api.pasien.rad');
            Route::get('pasien/{kunjungan}/triage', [ApiMonitoringController::class, 'compileTriage'])->name('api.pasien.triage');
            Route::get('pasien/{kunjungan}/operasi', [ApiMonitoringController::class, 'compileOperasi'])->name('api.pasien.operasi');
            Route::get('pasien/{kunjungan}/kwitansiResep', [ApiMonitoringController::class, 'compileKwitansiResep'])->name('api.pasien.kwitansiResep');
            // TTE
                // RAWAT JALAN
                Route::get('pasien/{kunjungan}/ttdRj', [ApiMonitoringController::class, 'showTtdResumeRj'])->name('api.pasien.ttdResumeRj');
                Route::post('pasien/resume/ttdRj/simpan', [ApiMonitoringController::class, 'storeTtdResumeRj'])->name('api.pasien.storeTtdResumeRj');
        // SMART KLAIM
            // BERKAS KLAIM
            Route::get('klaim/upload/{id}/show', [ApiSmartKlaimController::class, 'showUpload'])->name('api.klaim.showUpload');
            Route::post('klaim/upload', [ApiSmartKlaimController::class, 'upload'])->name('api.klaim.upload');
            Route::delete('klaim/upload/{id}/hapus', [ApiSmartKlaimController::class, 'hapusUpload'])->name('api.klaim.hapusUpload');
            Route::delete('klaim/rehab/{id}/hapus', [ApiSmartKlaimController::class, 'hapusRehab'])->name('api.klaim.hapusRehab');
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
            Route::get('klaim/{tahun}/{bulan}/{kunjungan}/pdf/{sep}', [ApiSmartKlaimController::class, 'showKlaim'])->name('api.klaim.showKlaim');
            Route::get('klaim/{kunjungan}/pdf/download', [ApiSmartKlaimController::class, 'downloadKlaim'])->name('api.klaim.downloadKlaim');
            Route::get('klaim/table/{pel}/{tgls}/{tgle}/{bln}/{dpjp}', [ApiSmartKlaimController::class, 'table'])->name('api.klaim.table');
            // BERKAS KLAIM FARMASI
            Route::get('klaim/farmasi/{kunjungan}/data', [ApiSmartKlaimController::class, 'getKlaimFarmasi'])->name('api.klaim.farmasi.getKlaim');
            Route::get('klaim/farmasi/{kunjungan}/verifikasi', [ApiSmartKlaimController::class, 'verifikasiKlaimFarmasi'])->name('api.klaim.farmasi.verifikasiKlaim');
            Route::get('klaim/farmasi/{kunjungan}/batalverifikasi', [ApiSmartKlaimController::class, 'batalVerifikasiKlaimFarmasi'])->name('api.klaim.farmasi.batalVerifikasiKlaim');
            Route::post('klaim/farmasi/submit', [ApiSmartKlaimController::class, 'submitFarmasi'])->name('api.klaim.farmasi.submit');
            Route::get('klaim/farmasi/{tahun}/{bulan}/{kunjungan}/pdf', [ApiSmartKlaimController::class, 'showKlaimFarmasi'])->name('api.klaim.farmasi.showKlaim');
            Route::delete('klaim/farmasi/{kunjungan}/hapus', [ApiSmartKlaimController::class, 'hapusKlaimFarmasi'])->name('api.klaim.farmasi.hapusKlaim');

    // LOG
        // BERKAS
            Route::get('log/berkas/table', [BerkasController::class, 'table'])->name('api.log.berkas.table');
            Route::get('log/berkas/{id}/show', [BerkasController::class, 'show'])->name('api.log.berkas.show');
            Route::delete('log/berkas/{id}/delete', [BerkasController::class, 'delete'])->name('api.log.berkas.delete');
    // TIDAK DIPAKAI =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
    // PELAYANAN PASIEN
    Route::get('pelayanan/pasien', [ApiResumeMedisController::class, 'table'])->name('api.pelayanan.pasien.table');
        // RESUME
        Route::get('pelayanan/pasien/rj/resume/{kunjungan}', [ApiResumeMedisController::class, 'compileResumeRj'])->name('api.pasien.resume.rj');
        Route::post('pelayanan/pasien/resume/ttd/simpan', [ResumeMedisController::class, 'storeTtd'])->name('api.pasien.resume.ttd');

    // EKLAIM
    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
});

