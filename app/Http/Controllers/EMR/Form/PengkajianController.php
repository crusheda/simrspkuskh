<?php

namespace App\Http\Controllers\EMR\Form;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\simrspku_klaim\klaim_verifikasi;
use App\Models\simrspku_klaim\klaim_file;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use PHPJasper\PHPJasper;
use Carbon\Carbon;
use Auth, Storage;

//////////////////////////////////////////////////////////////////////////////////////////
// CONTROLLER FORM
//////////////////////////////////////////////////////////////////////////////////////////
use App\Http\Controllers\EMR\Form\GawatDarurat\PengkajianGawatDaruratController;

use App\Http\Controllers\EMR\Form\RawatJalan\PengkajianRawatJalanDewasaController;
use App\Http\Controllers\EMR\Form\RawatJalan\PengkajianRawatJalanAnakController;
use App\Http\Controllers\EMR\Form\RawatJalan\PengkajianRawatJalanNeonatusController;
use App\Http\Controllers\EMR\Form\RawatJalan\PengkajianRawatJalanJiwaController;
use App\Http\Controllers\EMR\Form\RawatJalan\PengkajianRawatJalanGeriatriController;
use App\Http\Controllers\EMR\Form\RawatJalan\PengkajianRawatJalanObsgynController;

use App\Http\Controllers\EMR\Form\RawatInap\PengkajianRawatInapDewasaController;
use App\Http\Controllers\EMR\Form\RawatInap\PengkajianRawatInapAnakController;
use App\Http\Controllers\EMR\Form\RawatInap\PengkajianRawatInapNeonatusController;
use App\Http\Controllers\EMR\Form\RawatInap\PengkajianRawatInapObsgynController;

use App\Http\Controllers\EMR\Form\BedahAnestesi\PengkajianPraBedahController;
use App\Http\Controllers\EMR\Form\BedahAnestesi\PengkajianPraAnestesiInduksiController;
use App\Http\Controllers\EMR\Form\BedahAnestesi\PengkajianLaporanAnestesiController;

use App\Http\Controllers\EMR\Form\Khusus\PengkajianKhususRemajaController;
use App\Http\Controllers\EMR\Form\Khusus\PengkajianKhususTerminalController;
use App\Http\Controllers\EMR\Form\Khusus\PengkajianKhususNyeriKronikController;
use App\Http\Controllers\EMR\Form\Khusus\PengkajianKhususSistemImunTergangguController;
use App\Http\Controllers\EMR\Form\Khusus\PengkajianKhususKecanduanObatAlkoholController;
use App\Http\Controllers\EMR\Form\Khusus\PengkajianKhususKorbanKekerasanController;
use App\Http\Controllers\EMR\Form\Khusus\PengkajianKhususPenyakitMenularController;
use App\Http\Controllers\EMR\Form\Khusus\PengkajianKhususLanjutanController;

use App\Http\Controllers\EMR\Form\Lain\LembarTransferPasienInternalController;
use App\Http\Controllers\EMR\Form\Lain\DischargePlanningController;

use App\Support\FinalisasiMap;

//////////////////////////////////////////////////////////////////////////////////////////

class PengkajianController extends Controller
{
    public function loadFormPengkajian($form, $kunjungan)
    {
        $controllers = [
            'pengkajian-gd'                             => PengkajianGawatDaruratController::class,

            'pengkajian-rajal-dewasa'                   => PengkajianRawatJalanDewasaController::class,
            'pengkajian-rajal-anak'                     => PengkajianRawatJalanAnakController::class,
            'pengkajian-rajal-neonatus'                 => PengkajianRawatJalanNeonatusController::class,
            'pengkajian-rajal-psikiatri'                => PengkajianRawatJalanJiwaController::class,
            'pengkajian-rajal-geriatri'                 => PengkajianRawatJalanGeriatriController::class,
            'pengkajian-rajal-obsgyn'                   => PengkajianRawatJalanObsgynController::class,

            'pengkajian-ranap-dewasa'                   => PengkajianRawatInapDewasaController::class,
            'pengkajian-ranap-anak'                     => PengkajianRawatInapAnakController::class,
            'pengkajian-ranap-neonatus'                 => PengkajianRawatInapNeonatusController::class,
            'pengkajian-ranap-obsgyn'                   => PengkajianRawatInapObsgynController::class,

            'pengkajian-prabedah'                       => PengkajianPraBedahController::class,
            'pengkajian-praanestesiinduksi'             => PengkajianPraAnestesiInduksiController::class,
            'pengkajian-laporananestesi'                => PengkajianLaporanAnestesiController::class,

            'pengkajian-khusus-remaja'                  => PengkajianKhususRemajaController::class,
            'pengkajian-khusus-terminal'                => PengkajianKhususTerminalController::class,
            'pengkajian-khusus-nyerikronik'             => PengkajianKhususNyeriKronikController::class,
            'pengkajian-khusus-sistemimunterganggu'     => PengkajianKhususSistemImunTergangguController::class,
            'pengkajian-khusus-kecanduanobatalkohol'    => PengkajianKhususKecanduanObatAlkoholController::class,
            'pengkajian-khusus-korbankekerasan'         => PengkajianKhususKorbanKekerasanController::class,
            'pengkajian-khusus-penyakitmenular'         => PengkajianKhususPenyakitMenularController::class,
            'pengkajian-khusus-lanjutan'                => PengkajianKhususLanjutanController::class,

            'form-transfer-pasien'                      => LembarTransferPasienInternalController::class,
            'form-discharge-planning'                   => DischargePlanningController::class,
        ];

        abort_unless(isset($controllers[$form]), 404);

        return app($controllers[$form])->index($kunjungan);
    }

    public function loadSubFormPengkajian(
        Request $request,
        string $kunjungan,
        string $formKey
    ) {
        $forms = [
            // Gawat Darurat
                'gd_dokter' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.gawat-darurat.form_dokter',
                ],
                'gd_perawat' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.gawat-darurat.form_perawat',
                ],

            // Rawat Jalan
                // Dewasa
                'rjd_dokter' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.dewasa.form_dokter',
                ],
                'rjd_perawat' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.dewasa.form_perawat',
                ],

                // Anak
                'rja_dokter' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.anak.form_dokter',
                ],
                'rja_perawat' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.anak.form_perawat',
                ],

                // Anak
                'rjn_dokter' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.neonatus.form_dokter',
                ],
                'rjn_perawat' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.neonatus.form_perawat',
                ],

                // Geriatri
                'rjg_dokter' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.geriatri.form_dokter',
                ],
                'rjg_perawat' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.geriatri.form_perawat',
                ],

                // Jiwa
                'rjj_dokter' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.jiwa.form_dokter',
                ],
                'rjj_perawat' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.jiwa.form_perawat',
                ],

                // Obsgyn
                'rjo_dokter' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.obsgyn.form_dokter',
                ],
                'rjo_perawat' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.obsgyn.form_perawat',
                ],

            // Rawat Inap
                // Dewasa
                'rid_dokter' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-inap.dewasa.form_dokter',
                ],
                'rid_perawat' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-inap.dewasa.form_perawat',
                ],

                // Anak
                'ria_dokter' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-inap.anak.form_dokter',
                ],
                'ria_perawat' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-inap.anak.form_perawat',
                ],

                // Neonatus
                'rin_dokter' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-inap.neonatus.form_dokter',
                ],
                'rin_perawat' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-inap.neonatus.form_perawat',
                ],

                // Obsgyn
                'rio_dokter' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-inap.obsgyn.form_dokter',
                ],
                'rio_perawat' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-inap.obsgyn.form_perawat',
                ],

            // BEDAH & ANESTESI
                // LAPORAN ANESTESI
                'lap_anestesi' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.bedahanestesi.laporananestesi.laporananestesi',
                ],
                'lap_pasca_anestesi' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.bedahanestesi.laporananestesi.laporanpascaanestesi',
                ],

        ];

        abort_unless(
            array_key_exists($formKey, $forms),
            404,
            'Form pengkajian tidak ditemukan.'
        );

        /*
         * Tambahkan query kunjungan/pasien Anda di sini bila partial
         * membutuhkan data lain selain kunjungan.
         */
        $getDataKunjungan = DB::table('pendaftaran.kunjungan AS pk')
                ->select(
                    'pk.*',
                    'pp.NORM','pp.TANGGAL AS TGLDAFTAR',
                    'kjs.noSEP AS NOSEP','kjs.tglSEP AS TGLSEP',
                    'kjs.noKartu AS NOBPJS',
                    'ru.ID AS IDRUANGAN',
                    'ru.DESKRIPSI AS NAMARUANGAN',
                    'kips.NOMOR AS NIKPASIEN',
                    'ps.NAMA AS NAMALENGKAPPASIEN',
                    'ps.PANGGILAN AS PANGGILANPASIEN',
                    'ps.TANGGAL_LAHIR AS TGLLAHIRPASIEN',
                    DB::raw("
                        IF(
                            ps.JENIS_KELAMIN = 1,
                            'LAKI-LAKI',
                            IF(
                                ps.JENIS_KELAMIN = 2,
                                'PEREMPUAN',
                                'TIDAK DIKETAHUI'
                            )
                        ) AS JKPASIEN
                    "),
                    DB::raw('master.getNamaLengkap(ps.NORM) AS NAMAPASIEN'),
                    DB::raw('master.getAlamatPasienCustom(ps.NORM) AS ALAMATPASIEN'),
                    DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'),
                    DB::raw('master.getCariUmur(pp.TANGGAL,ps.TANGGAL_LAHIR) AS UMURPASIEN'),
                    DB::raw("(
                        SELECT DESKRIPSI
                        FROM master.wilayah AS wil
                        WHERE wil.ID = ps.TEMPAT_LAHIR
                    ) AS TLPASIEN"),
                )
                ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                ->leftJoin('pendaftaran.penjamin AS pj','pj.NOPEN','=','pp.NOMOR')
                ->leftJoin('bpjs.kunjungan AS kjs','kjs.noSEP','=','pj.NOMOR')
                ->leftJoin('master.pasien AS ps','ps.NORM','=','pp.NORM')
                ->leftJoin('master.kartu_identitas_pasien AS kips','ps.NORM','=','kips.NORM')
                ->leftJoin('master.ruangan AS ru','ru.ID','=','pk.RUANGAN')
                ->leftJoin('master.dokter AS dr','dr.ID','=','pk.DPJP')
                ->where('pk.NOMOR',$kunjungan)
                ->first();

        $list = [
            'kunjungan' => $kunjungan,
            'dataKunjungan' => $getDataKunjungan,
        ];

        if (
            in_array(
                $formKey,
                [
                    'gd_dokter',
                    'gd_perawat',

                    'rjd_dokter',
                    'rjd_perawat',
                    'rja_dokter',
                    'rja_perawat',
                    'rjn_dokter',
                    'rjn_perawat',
                    'rjg_dokter',
                    'rjg_perawat',
                    'rjj_dokter',
                    'rjj_perawat',
                    'rjo_dokter',
                    'rjo_perawat',

                    'rio_dokter',
                    'rio_perawat',
                ],
                true
            )
        ) {
            $list = array_merge(
                $list,
                $this->getDataMaster($kunjungan)
            );
        }

        $identification = $this->formInputIdentification($kunjungan);
        $init = [
            'input_date' => $identification['inputdate'],
        ];

        // Kirim status finalisasi bersama partial. Dengan demikian backdrop
        // dan tombol yang tepat sudah dirender pada paint pertama, tidak
        // perlu menunggu request AJAX kedua setelah form terlihat.
        $initialFinalisasi = null;

        try {
            $initialFinalisasi = $this->getStatusFinalisasi(
                $kunjungan,
                $formKey
            );
        } catch (\InvalidArgumentException $e) {
            // Form tanpa konfigurasi finalisasi tetap dapat dimuat seperti biasa.
        }

        return view(
            $forms[$formKey]['view'],
            compact(
                'init',
                'list',
                'kunjungan',
                'formKey',
                'initialFinalisasi'
            )
        );
    }

    private function getFinalisasiFormSub(string $formKey): array
    {
        return FinalisasiMap::get($formKey);
    }

    private function getStatusFinalisasi(
        string $kunjungan,
        string $formKey
    ): array {
        $mapping = $this->getFinalisasiFormSub($formKey);

        $data = DB::table('simrspku_pengkajian.finalisasi as fin')
            ->leftJoin(
                'aplikasi.pengguna as usr1',
                'usr1.ID',
                '=',
                'fin.USER_CREATED'
            )
            ->leftJoin(
                'aplikasi.pengguna as usr2',
                'usr2.ID',
                '=',
                'fin.USER_UPDATED'
            )
            ->select(
                'fin.*',
                DB::raw(
                    'master.getNamaLengkapPegawai(usr1.NIP) AS NAMAUSER_CREATED'
                ),
                DB::raw(
                    'master.getNamaLengkapPegawai(usr2.NIP) AS NAMAUSER_UPDATED'
                ),
            )
            ->where('fin.KUNJUNGAN', $kunjungan)
            ->where('fin.FORM', $mapping['form'])
            ->where('fin.SUB', $mapping['sub'])
            ->first();

        return [
            'status' => $data ? (int) $data->STATUS : 1,
            'is_final' => $data && (int) $data->STATUS === 2,
            'data' => $data,
        ];
    }

    public function statusFinalisasi(
        Request $request,
        string $kunjungan
    ) {
        $formKeys = collect($request->input('formKeys', []))
            ->filter(fn ($formKey) => is_string($formKey))
            ->unique()
            ->values();

        $result = [];

        foreach ($formKeys as $formKey) {
            try {
                $status = $this->getStatusFinalisasi(
                    $kunjungan,
                    $formKey
                );

                $result[$formKey] = [
                    'status' => $status['status'],
                    'is_final' => $status['is_final'],
                ];
            } catch (\InvalidArgumentException $e) {
                // Abaikan form key yang tidak ada di FinalisasiMap.
            }
        }

        return response()
            ->json([
                'status' => true,
                'data' => $result,
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
