<?php

namespace App\Http\Controllers\EMR\Form\Finalisasi;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\EMR\Form\AddOnPengkajianController;
use Illuminate\Http\Request;
use Throwable;
use Carbon\Carbon;
use Auth, Storage;

class FinalisasiRanapController extends Controller
{
    protected AddOnPengkajianController $addOnPengkajianController;

    public function __construct(
        AddOnPengkajianController $addOnPengkajianController
    ) {
        $this->addOnPengkajianController = $addOnPengkajianController;
    }

    // ======================================================================================================================= FUNCTION STARTED !!!!

    private function getFinalisasiFormSub(string $formKey): array
    {
        return match ($formKey) {
            'rid_dokter' => [
                'form' => 'pengkajian-ranap-dewasa',
                'sub' => 'DOKTER',
            ],
            'rid_perawat' => [
                'form' => 'pengkajian-ranap-dewasa',
                'sub' => 'PERAWAT',
            ],

            'ria_dokter' => [
                'form' => 'pengkajian-ranap-anak',
                'sub' => 'DOKTER',
            ],
            'ria_perawat' => [
                'form' => 'pengkajian-ranap-anak',
                'sub' => 'PERAWAT',
            ],

            'rin_dokter' => [
                'form' => 'pengkajian-ranap-neonatus',
                'sub' => 'DOKTER',
            ],
            'rin_perawat' => [
                'form' => 'pengkajian-ranap-neonatus',
                'sub' => 'PERAWAT',
            ],

            'rio_dokter' => [
                'form' => 'pengkajian-ranap-obsgyn',
                'sub' => 'DOKTER',
            ],
            'rio_perawat' => [
                'form' => 'pengkajian-ranap-obsgyn',
                'sub' => 'PERAWAT',
            ],

            default => throw new \InvalidArgumentException(
                "Form key tidak dikenal: {$formKey}"
            ),
        };
    }

    private function getStatusFinalisasi(
        string $kunjungan,
        string $formKey
    ): array {
        $mapping = $this->getFinalisasiFormSub($formKey);

        $data = DB::table('simrspku_pengkajian.finalisasi')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('FORM', $mapping['form'])
            ->where('SUB', $mapping['sub'])
            ->first();

        return [
            'status' => $data ? (int) $data->STATUS : 1,
            'is_final' => $data && (int) $data->STATUS === 2,
            'data' => $data,
        ];
    }

    public function statusFinalisasiPengkajianRanap(
        Request $request,
        string $kunjungan
    ) {
        $formKey = $request->formKey;

        return response()->json(
            $this->getStatusFinalisasi($kunjungan, $formKey)
        );
    }

    private function generateSoapPengkajianRanap($kunjungan, $form, $sub)
    {
        return match ($form) {

            'pengkajian-ranap-dewasa',
            'pengkajian-ranap-anak' =>
                $this->generateSoapRanapDewasa(
                    $kunjungan,
                    $sub
                ),

            'pengkajian-ranap-neonatus' =>
                $this->generateSoapRanapNeonatus(
                    $kunjungan,
                    $sub
                ),

            'pengkajian-ranap-obsgyn' =>
                $this->generateSoapRanapObsgyn(
                    $kunjungan,
                    $sub
                ),

            default => throw new \InvalidArgumentException(
                "Form SOAP Ranap tidak dikenali: {$form}"
            ),
        };
    }

    public function finalisasiPengkajianRanap(Request $request, $kunjungan)
    {
        DB::beginTransaction();

        try {
            $formKey = $request->input('formKey');

            if (!$formKey) {
                DB::rollBack();

                return response()->json([
                    'status' => false,
                    'message' => 'Form Key tidak ditemukan.'
                ], 422);
            }

            $mapping = $this->getFinalisasiFormSub($formKey);

            if (!$mapping) {
                DB::rollBack();

                return response()->json([
                    'status' => false,
                    'message' => 'Mapping form finalisasi tidak ditemukan.'
                ], 422);
            }

            $form = $mapping['form'];
            $sub = $mapping['sub'];

            $finalisasi = DB::table('simrspku_pengkajian.finalisasi')
                ->where('KUNJUNGAN', $kunjungan)
                ->where('FORM', $form)
                ->where('SUB', $sub)
                ->whereIn('STATUS', [1, 2])
                ->first();

            /*
            |--------------------------------------------------------------------------
            | SUDAH FINAL
            |--------------------------------------------------------------------------
            */

            if ($finalisasi && (int) $finalisasi->STATUS === 2) {
                DB::rollBack();

                return response()->json([
                    'status' => false,
                    'message' => 'Form sudah difinalisasi dan tidak dapat diubah.'
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | SIMPAN SOAP
            |--------------------------------------------------------------------------
            */

            $soap = $this->simpanSoapPengkajianRanap(
                $kunjungan,
                $form,
                $sub
            );

            /*
            |--------------------------------------------------------------------------
            | FINALISASI
            |--------------------------------------------------------------------------
            */

            if ($finalisasi) {

                DB::table('simrspku_pengkajian.finalisasi')
                    ->where('ID', $finalisasi->ID)
                    ->update([
                        'STATUS' => 2,
                        'USER_UPDATED' => auth()->id(),
                        'UPDATED' => now(),
                        'REASON' => null,
                    ]);

            } else {

                DB::table('simrspku_pengkajian.finalisasi')
                    ->insert([
                        'KUNJUNGAN' => $kunjungan,
                        'FORM' => $form,
                        'SUB' => $sub,
                        'USER_CREATED' => auth()->id(),
                        'STATUS' => 2,
                        'REASON' => null,
                    ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Pengkajian berhasil difinalisasi.',
                'data' => [
                    'kunjungan' => $kunjungan,
                    'form' => $form,
                    'sub' => $sub,
                    'status' => 2,
                    'ID_CPPT' => $soap['ID_CPPT'] ?? null,
                ]
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Finalisasi pengkajian gagal.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | BATAL FINALISASI
    |--------------------------------------------------------------------------
    */
    public function batalFinalisasiPengkajianRanap(Request $request, $kunjungan)
    {
        try {

            $formKey = $request->input('formKey');
            $reason = trim((string) $request->input('reason'));

            if (!$formKey) {
                return response()->json([
                    'status' => false,
                    'message' => 'Form Key tidak ditemukan.'
                ], 422);
            }

            if ($reason === '') {
                return response()->json([
                    'status' => false,
                    'message' => 'Alasan pembatalan finalisasi wajib diisi.'
                ], 422);
            }

            $mapping = $this->getFinalisasiFormSub($formKey);

            if (!$mapping) {
                return response()->json([
                    'status' => false,
                    'message' => 'Mapping form finalisasi tidak ditemukan.'
                ], 422);
            }

            $updated = DB::table('simrspku_pengkajian.finalisasi')
                ->where('KUNJUNGAN', $kunjungan)
                ->where('FORM', $mapping['form'])
                ->where('SUB', $mapping['sub'])
                ->where('STATUS', 2)
                ->update([
                    'STATUS' => 1,
                    'REASON' => $reason,
                    'USER_UPDATED' => auth()->id(),
                    'UPDATED' => now(),
                ]);

            if (!$updated) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data finalisasi tidak ditemukan atau sudah dibatalkan.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Finalisasi berhasil dibatalkan.',
                'data' => [
                    'status' => 1,
                    'reason' => $reason
                ]
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => false,
                'message' => 'Pembatalan finalisasi gagal.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SOAP
    |--------------------------------------------------------------------------
    */
    private function simpanSoapPengkajianRanap($kunjungan, $form, $sub)
    {
        $soap = $this->generateSoapPengkajianRanap(
            $kunjungan,
            $form,
            $sub
        );

        $dataCppt = [
            'KUNJUNGAN' => $kunjungan,
            'TANGGAL' => now(),
            'SUBYEKTIF' => $soap['SUBYEKTIF'] ?? '',
            'OBYEKTIF' => $soap['OBYEKTIF'] ?? '',
            'ASSESMENT' => $soap['ASSESMENT'] ?? '',
            'PLANNING' => $soap['PLANNING'] ?? '',
            'INSTRUKSI' => $soap['INSTRUKSI'] ?? '',
            'OLEH' => auth()->id(),
            'STATUS' => 1,
        ];

        $pushCppt = DB::table('medicalrecord.push_cppt')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('FORM', $form)
            ->where('SUB', $sub)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | UPDATE CPPT
        |--------------------------------------------------------------------------
        */

        if ($pushCppt) {

            DB::table('medicalrecord.cppt')
                ->where('ID', $pushCppt->ID_CPPT)
                ->update($dataCppt);

            DB::table('medicalrecord.push_cppt')
                ->where('ID_CPPT', $pushCppt->ID_CPPT)
                ->update([
                    'KUNJUNGAN' => $kunjungan,
                    'FORM' => $form,
                    'SUB' => $sub,
                    'SUBYEKTIF' => $soap['SUBYEKTIF'] ?? '',
                    'OBYEKTIF' => $soap['OBYEKTIF'] ?? '',
                    'ASSESMENT' => $soap['ASSESMENT'] ?? '',
                    'PLANNING' => $soap['PLANNING'] ?? '',
                    'INSTRUKSI' => $soap['INSTRUKSI'] ?? '',
                    'TANGGAL' => now(),
                    'OLEH' => auth()->id(),
                    'STATUS' => 1,
                ]);

            return [
                'ID_CPPT' => $pushCppt->ID_CPPT,
                'action' => 'update',
                'soap' => $soap,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | INSERT CPPT
        |--------------------------------------------------------------------------
        */

        $idCppt = DB::table('medicalrecord.cppt')
            ->insertGetId($dataCppt);

        /*
        |--------------------------------------------------------------------------
        | INSERT PUSH CPPT
        |--------------------------------------------------------------------------
        */

        DB::table('medicalrecord.push_cppt')
            ->insert([
                'ID_CPPT' => $idCppt,
                'KUNJUNGAN' => $kunjungan,
                'FORM' => $form,
                'SUB' => $sub,
                'SUBYEKTIF' => $soap['SUBYEKTIF'] ?? '',
                'OBYEKTIF' => $soap['OBYEKTIF'] ?? '',
                'ASSESMENT' => $soap['ASSESMENT'] ?? '',
                'PLANNING' => $soap['PLANNING'] ?? '',
                'INSTRUKSI' => $soap['INSTRUKSI'] ?? '',
                'TANGGAL' => now(),
                'OLEH' => auth()->id(),
                'STATUS' => 1,
            ]);

        return [
            'ID_CPPT' => $idCppt,
            'action' => 'insert',
            'soap' => $soap,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | SOAP RANAP DEWASA
    |--------------------------------------------------------------------------
    */
    private function generateSoapRanapDewasa($kunjungan, $sub)
    {
        /*
        |--------------------------------------------------------------------------
        | S
        |--------------------------------------------------------------------------
        */

        $anamnesisDiperoleh = DB::table('medicalrecord.anamnesis_diperoleh')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first([
                'AUTOANAMNESIS',
                'ALLOANAMNESIS',
                'DARI',
            ]);

        $keluhanUtama = DB::table('medicalrecord.keluhan_utama')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first([
                'DESKRIPSI',
            ]);

        $anamnesis = DB::table('medicalrecord.anamnesis')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first([
                'DESKRIPSI',
            ]);

        $rpp = DB::table('medicalrecord.rpp')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first([
                'DESKRIPSI',
            ]);

        $rpk = DB::table('medicalrecord.riwayat_penyakit_keluarga')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first([
                'HIPERTENSI',
                'DIABETES_MELITUS',
                'PENYAKIT_JANTUNG',
                'ASMA',
                'LAINNYA',
            ]);

        $statusReproduksi = DB::table('medicalrecord.sirmed_status_reproduksi')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first([
                'RIWAYAT_TUMBUH_KEMBANG',
                'RIWAYAT_KELAHIRAN',
                'USIA_KEHAMILAN',
                'PERSALINAN',
                'PERSALINAN_LAINNYA',
            ]);

        $tumbuhKembang = DB::table('medicalrecord.riwayat_tumbuh_kembang')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first([
                'IMUNISASI',
                'IMUNISASI_LAIN',
            ]);

        $s = [];

        if ($anamnesisDiperoleh) {

            if ((int) $anamnesisDiperoleh->AUTOANAMNESIS === 1) {
                $s[] = 'Autoanamnesis';
            }

            if ((int) $anamnesisDiperoleh->ALLOANAMNESIS === 1) {
                $dari = $this->soapValue($anamnesisDiperoleh->DARI);

                $s[] = 'Alloanamnesis' . (
                    $dari ? ' dari ' . $dari : ''
                );
            }
        }

        if ($keluhanUtama) {
            $value = $this->soapValue($keluhanUtama->DESKRIPSI);

            if ($value) {
                $s[] = 'Keluhan Utama: ' . $value;
            }
        }

        if ($anamnesis) {
            $value = $this->soapValue($anamnesis->DESKRIPSI);

            if ($value) {
                $s[] = 'Riwayat Penyakit Sekarang: ' . $value;
            }
        }

        if ($rpp) {
            $value = $this->soapValue($rpp->DESKRIPSI);

            if ($value) {
                $s[] = 'Riwayat Penyakit Dahulu: ' . $value;
            }
        }

        if ($rpk) {

            $riwayatKeluarga = [];

            if ((int) $rpk->HIPERTENSI === 1) {
                $riwayatKeluarga[] = 'Hipertensi';
            }

            if ((int) $rpk->DIABETES_MELITUS === 1) {
                $riwayatKeluarga[] = 'Diabetes Melitus';
            }

            if ((int) $rpk->PENYAKIT_JANTUNG === 1) {
                $riwayatKeluarga[] = 'Penyakit Jantung';
            }

            if ((int) $rpk->ASMA === 1) {
                $riwayatKeluarga[] = 'Asma';
            }

            $lainnya = $this->soapValue($rpk->LAINNYA);

            if ($lainnya) {
                $riwayatKeluarga[] = $lainnya;
            }

            if ($riwayatKeluarga) {
                $s[] = 'Riwayat Penyakit Keluarga: ' .
                    implode(', ', $riwayatKeluarga);
            }
        }

        if ($statusReproduksi) {

            $rows = [];

            $rows[] = $this->soapLine(
                'Riwayat Tumbuh Kembang',
                $statusReproduksi->RIWAYAT_TUMBUH_KEMBANG
            );

            $rows[] = $this->soapLine(
                'Riwayat Kelahiran',
                $statusReproduksi->RIWAYAT_KELAHIRAN
            );

            $rows[] = $this->soapLine(
                'Usia Kehamilan',
                $statusReproduksi->USIA_KEHAMILAN
            );

            $rows[] = $this->soapLine(
                'Persalinan',
                $statusReproduksi->PERSALINAN
            );

            $rows[] = $this->soapLine(
                'Persalinan Lainnya',
                $statusReproduksi->PERSALINAN_LAINNYA
            );

            $riwayat = array_values(array_filter($rows));

            if ($riwayat) {
                $s[] = implode("\n", $riwayat);
            }
        }

        if ($tumbuhKembang) {

            $rows = [];

            $rows[] = $this->soapLine(
                'Imunisasi',
                $tumbuhKembang->IMUNISASI
            );

            $rows[] = $this->soapLine(
                'Imunisasi Lain',
                $tumbuhKembang->IMUNISASI_LAIN
            );

            $imunisasi = array_values(array_filter($rows));

            if ($imunisasi) {
                $s[] = implode("\n", $imunisasi);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | O - TANDA VITAL
        |--------------------------------------------------------------------------
        */

        $tandaVital = DB::table('medicalrecord.tanda_vital')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('PPA', 1)
            ->whereIn('STATUS', [1, 2])
            ->orderByDesc('ID')
            ->first([
                'KEADAAN_UMUM',
                'SISTOLIK',
                'DISTOLIK',
                'FREKUENSI_NADI',
                'FREKUENSI_NADI_CB',
                'SUHU',
                'SATURASI_O2',
                'FREKUENSI_NAFAS',
                'FREKUENSI_NAFAS_CB',
                'EYE',
                'VERBAL',
                'MOTORIK',
                'GCS',
                'KESADARAN_NEONATUS',
            ]);

        $nutrisi = DB::table('medicalrecord.nutrisi')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('PPA', 1)
            ->whereIn('STATUS', [1, 2])
            ->orderByDesc('ID')
            ->first([
                'BERAT_BADAN',
                'TINGGI_BADAN',
                'INDEX_MASSA_TUBUH',
            ]);

        $o = [];

        if ($tandaVital) {

            $o[] = $this->soapLine(
                'Keadaan Umum',
                $tandaVital->KEADAAN_UMUM
            );

            if (
                $tandaVital->SISTOLIK !== null ||
                $tandaVital->DISTOLIK !== null
            ) {
                $o[] = 'TD: ' .
                    ($tandaVital->SISTOLIK ?? '-') .
                    '/' .
                    ($tandaVital->DISTOLIK ?? '-') .
                    ' mmHg';
            }

            if ($tandaVital->FREKUENSI_NADI !== null) {
                $o[] = 'Nadi: ' .
                    $tandaVital->FREKUENSI_NADI .
                    ' x/menit';
            }

            if ($tandaVital->FREKUENSI_NADI_CB !== null) {
                $o[] = 'Nadi Catatan: ' .
                    $tandaVital->FREKUENSI_NADI_CB;
            }

            if ($tandaVital->SUHU !== null) {
                $o[] = 'Suhu: ' .
                    $tandaVital->SUHU .
                    ' °C';
            }

            if ($tandaVital->SATURASI_O2 !== null) {
                $o[] = 'SpO2: ' .
                    $tandaVital->SATURASI_O2 .
                    ' %';
            }

            if ($tandaVital->FREKUENSI_NAFAS !== null) {
                $o[] = 'RR: ' .
                    $tandaVital->FREKUENSI_NAFAS .
                    ' x/menit';
            }

            if ($tandaVital->FREKUENSI_NAFAS_CB !== null) {
                $o[] = 'RR Catatan: ' .
                    $tandaVital->FREKUENSI_NAFAS_CB;
            }

            if ($tandaVital->GCS !== null) {
                $o[] = 'GCS: ' .
                    $tandaVital->GCS .
                    ' (E' .
                    ($tandaVital->EYE ?? '-') .
                    ' V' .
                    ($tandaVital->VERBAL ?? '-') .
                    ' M' .
                    ($tandaVital->MOTORIK ?? '-') .
                    ')';
            }
        }

        if ($nutrisi) {

            if ($nutrisi->BERAT_BADAN !== null) {
                $o[] = 'BB: ' .
                    $nutrisi->BERAT_BADAN .
                    ' kg';
            }

            if ($nutrisi->TINGGI_BADAN !== null) {
                $o[] = 'TB: ' .
                    $nutrisi->TINGGI_BADAN .
                    ' cm';
            }

            if ($nutrisi->INDEX_MASSA_TUBUH !== null) {
                $o[] = 'IMT: ' .
                    $nutrisi->INDEX_MASSA_TUBUH;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | O - PEMERIKSAAN ANATOMI
        |--------------------------------------------------------------------------
        */

        $anatomi = DB::table('medicalrecord.sirmed_pemeriksaan_anatomi')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first();

        if ($anatomi) {

            // Mata
            $mata = [];

            if ($this->soapValue($anatomi->pf_anemis)) {
                $mata[] = 'Anemis';
            }

            if ($this->soapValue($anatomi->pf_ikterus)) {
                $mata[] = 'Ikterus';
            }

            if ($this->soapValue($anatomi->pf_upal)) {
                $mata[] = 'Pupil isokor';
            }

            if ($this->soapValue($anatomi->pf_dia_up)) {
                $mata[] = 'Diameter pupil atas: ' .
                    $this->soapValue($anatomi->pf_dia_up);
            }

            if ($this->soapValue($anatomi->pf_dia_down)) {
                $mata[] = 'Diameter pupil bawah: ' .
                    $this->soapValue($anatomi->pf_dia_down);
            }

            if ($this->soapValue($anatomi->pf_kelainan_mata)) {
                $mata[] = $this->soapValue($anatomi->pf_kelainan_mata);
            }

            if ($mata) {
                $o[] = 'Mata: ' . implode(', ', $mata);
            }


            // Mulut
            if ($this->soapValue($anatomi->pf_mulut)) {
                $o[] = 'Mulut: ' .
                    $this->soapValue($anatomi->pf_mulut);
            }


            // Leher
            $leher = [];

            if ($this->soapValue($anatomi->pf_jvp)) {
                $leher[] = 'JVP ' . $this->soapValue($anatomi->pf_jvp);
            }

            if ($this->soapValue($anatomi->pf_pkl)) {
                $leher[] = 'Pembesaran kelenjar limfe';
            }

            if ($this->soapValue($anatomi->pf_pkl_lain)) {
                $leher[] = $this->soapValue($anatomi->pf_pkl_lain);
            }

            if ($this->soapValue($anatomi->pf_kd)) {
                $leher[] = 'Kelainan daerah leher';
            }

            if ($this->soapValue($anatomi->pf_kd_lain)) {
                $leher[] = $this->soapValue($anatomi->pf_kd_lain);
            }

            if ($this->soapValue($anatomi->pf_kelainan_leher)) {
                $leher[] = 'Kelainan leher';
            }

            if ($leher) {
                $o[] = 'Leher: ' . implode(', ', $leher);
            }


            // Thoraks
            $thoraks = [];

            if ($this->soapValue($anatomi->pf_thoraks)) {
                $thoraks[] = 'kelainan thoraks';
            }

            if ($this->soapValue($anatomi->pf_thoraks_lain)) {
                $thoraks[] = $this->soapValue($anatomi->pf_thoraks_lain);
            }

            if ($this->soapValue($anatomi->pf_cor)) {
                $thoraks[] = 'Cor: ' .
                    $this->soapValue($anatomi->pf_cor);
            }

            if ($this->soapValue($anatomi->pf_cor_cb)) {
                $thoraks[] = 'Cor CB abnormal';
            }

            if ($this->soapValue($anatomi->pf_murmur)) {
                $thoraks[] = 'Murmur: ' .
                    $this->soapValue($anatomi->pf_murmur);
            }

            if ($this->soapValue($anatomi->pf_murmur_lain)) {
                $thoraks[] = $this->soapValue($anatomi->pf_murmur_lain);
            }

            if ($this->soapValue($anatomi->pf_pulmo)) {
                $thoraks[] = 'Pulmo: ' .
                    $this->soapValue($anatomi->pf_pulmo);
            }

            if ($this->soapValue($anatomi->pf_ronchi)) {
                $thoraks[] = 'Ronchi';
            }

            if ($this->soapValue($anatomi->pf_ronchi_lain)) {
                $thoraks[] = $this->soapValue($anatomi->pf_ronchi_lain);
            }

            if ($this->soapValue($anatomi->pf_wheezing)) {
                $thoraks[] = 'Wheezing';
            }

            if ($this->soapValue($anatomi->pf_wheezing_lain)) {
                $thoraks[] = $this->soapValue($anatomi->pf_wheezing_lain);
            }

            if ($this->soapValue($anatomi->pf_kelainan_dada)) {
                $thoraks[] = 'Kelainan dada';
            }

            if ($this->soapValue($anatomi->pf_dada_lain)) {
                $thoraks[] = $this->soapValue($anatomi->pf_dada_lain);
            }

            if ($thoraks) {
                $o[] = 'Thoraks: ' . implode(', ', $thoraks);
            }


            // Abdomen
            $abdomen = [];

            if ($this->soapValue($anatomi->pf_distended)) {
                $abdomen[] = 'Distensi';
            }

            if ($this->soapValue($anatomi->pf_meteor)) {
                $abdomen[] = 'Meteorismus';
            }

            if ($this->soapValue($anatomi->pf_asites)) {
                $abdomen[] = 'Asites';
            }

            if ($this->soapValue($anatomi->pf_peristal_normal)) {
                $abdomen[] = 'Peristaltik normal';
            }

            if ($this->soapValue($anatomi->pf_peristal_meningkat)) {
                $abdomen[] = 'Peristaltik meningkat';
            }

            if ($this->soapValue($anatomi->pf_peristal_menurun)) {
                $abdomen[] = 'Peristaltik menurun';
            }

            if ($this->soapValue($anatomi->pf_peristal_tidak)) {
                $abdomen[] = 'Peristaltik tidak ada';
            }

            if ($this->soapValue($anatomi->pf_nyeri_tekan)) {
                $abdomen[] = 'Nyeri tekan';
            }

            if ($this->soapValue($anatomi->pf_nyeri_tekan_lain)) {
                $abdomen[] = $this->soapValue($anatomi->pf_nyeri_tekan_lain);
            }

            if ($this->soapValue($anatomi->pf_hepar)) {
                $abdomen[] = 'Hepar: ' .
                    $this->soapValue($anatomi->pf_hepar);
            }

            if ($this->soapValue($anatomi->pf_lien)) {
                $abdomen[] = 'Lien: ' .
                    $this->soapValue($anatomi->pf_lien);
            }

            if ($abdomen) {
                $o[] = 'Abdomen: ' . implode(', ', $abdomen);
            }


            // Ekstremitas
            $extremitas = [];

            if ($this->soapValue($anatomi->pf_extremitas_hangat)) {
                $extremitas[] = 'Hangat';
            }

            if ($this->soapValue($anatomi->pf_extremitas_dingin)) {
                $extremitas[] = 'Dingin';
            }

            if ($this->soapValue($anatomi->pf_udem)) {
                $extremitas[] = 'Udem';
            }

            if ($this->soapValue($anatomi->pf_udem_lain)) {
                $extremitas[] = $this->soapValue($anatomi->pf_udem_lain);
            }

            if ($extremitas) {
                $o[] = 'Ekstremitas: ' .
                    implode(', ', $extremitas);
            }


            // Status Lokalis
            if ($this->soapValue($anatomi->status_lokalis)) {
                $o[] = 'Status Lokalis: ' .
                    $this->soapValue($anatomi->status_lokalis);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | A - DIAGNOSIS
        |--------------------------------------------------------------------------
        */

        $nopen = DB::table('pendaftaran.kunjungan')
            ->where('NOMOR', $kunjungan)
            ->value('NOPEN');

        $diagnosis = collect();

        if ($nopen) {
            $diagnosis = DB::table('medicalrecord.diagnosa as diag')
                ->leftJoin('master.mrconso as mrc', function ($join) {
                    $join->on('diag.KODE', '=', 'mrc.CODE')
                        ->whereNotIn('mrc.TTY', ['HT', 'PS'])
                        ->where(function ($q) {
                            $q->where('mrc.SAB', 'ICD10_2020')
                                ->orWhere(function ($q) {
                                    $q->where('mrc.SAB', 'ICD10_1998')
                                        ->whereNotExists(function ($sub) {
                                            $sub->select(DB::raw(1))
                                                ->from('master.mrconso as mrc2020')
                                                ->whereColumn(
                                                    'mrc2020.CODE',
                                                    'diag.KODE'
                                                )
                                                ->where(
                                                    'mrc2020.SAB',
                                                    'ICD10_2020'
                                                )
                                                ->whereNotIn(
                                                    'mrc2020.TTY',
                                                    ['HT', 'PS']
                                                );
                                        });
                                });
                        });
                })
                ->where('diag.NOPEN', $nopen)
                ->where('diag.STATUS', 1)
                ->select([
                    'diag.DIAGNOSA',
                    'diag.KODE',
                    'diag.UTAMA',
                    'mrc.STR as NAMA_DIAGNOSA',
                ])
                ->get();
        }

        $a = [];

        foreach ($diagnosis as $diag) {

            $nama = $this->soapValue(
                $diag->NAMA_DIAGNOSA
            );

            if (!$nama) {
                $nama = $this->soapValue(
                    $diag->DIAGNOSA
                );
            }

            if (!$nama) {
                continue;
            }

            $kode = $this->soapValue($diag->KODE);

            $jenis = ((int) $diag->UTAMA === 1)
                ? 'Utama'
                : 'Sekunder';

            $a[] = $jenis . ': ' .
                $nama .
                ($kode ? ' [' . $kode . ']' : '');
        }

        /*
        |--------------------------------------------------------------------------
        | P - TERAPI
        |--------------------------------------------------------------------------
        */

        $tataLaksana = DB::table(
            'medicalrecord.sirmed_tata_laksana_terapi'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->value('DESKRIPSI');

        $targetTerapi = DB::table(
            'medicalrecord.sirmed_target_terapi'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->value('DESKRIPSI');

        $rencanaKonsultasi = DB::table(
            'medicalrecord.sirmed_rencana_konsultasi'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->value('DESKRIPSI');

        $kriteriaPulang = DB::table(
            'medicalrecord.sirmed_kriteria_pulang'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first([
                'KRITERIA_PULANG',
                'HARI',
                'KARENA',
            ]);

        $p = [];

        if ($this->soapValue($tataLaksana)) {
            $p[] = 'Tata Laksana Terapi: ' .
                $this->soapValue($tataLaksana);
        }

        if ($this->soapValue($targetTerapi)) {
            $p[] = 'Target Terapi: ' .
                $this->soapValue($targetTerapi);
        }

        if ($this->soapValue($rencanaKonsultasi)) {
            $p[] = 'Rencana Konsultasi: ' .
                $this->soapValue($rencanaKonsultasi);
        }

        if ($kriteriaPulang) {

            $rows = [];

            if ($this->soapValue($kriteriaPulang->KRITERIA_PULANG)) {
                $rows[] = 'Kriteria Pulang: ' .
                    $this->soapValue($kriteriaPulang->KRITERIA_PULANG);
            }

            if ($this->soapValue($kriteriaPulang->HARI)) {
                $rows[] = 'Hari: ' .
                    $this->soapValue($kriteriaPulang->HARI);
            }

            if ($this->soapValue($kriteriaPulang->KARENA)) {
                $rows[] = 'Karena: ' .
                    $this->soapValue($kriteriaPulang->KARENA);
            }

            $p = array_merge($p, $rows);
        }

        return [
            'SUBYEKTIF' => implode("\n", $s),
            'OBYEKTIF' => implode("\n", $o),
            'ASSESMENT' => implode("\n", $a),
            'PLANNING' => implode("\n", $p),
            'INSTRUKSI' => '',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | SOAP RANAP ANAK
    |--------------------------------------------------------------------------
    */
    // SAMA DENGAN RANAP DEWASA = JADI PAKAI generateSoapRanapDewasa();

    /*
    |--------------------------------------------------------------------------
    | SOAP RANAP NEONATUS
    |--------------------------------------------------------------------------
    */
    private function generateSoapRanapNeonatus($kunjungan, $sub)
    {
        /*
        |--------------------------------------------------------------------------
        | S - ANAMNESIS
        |--------------------------------------------------------------------------
        */

        $dewasa = $this->generateSoapRanapDewasa(
            $kunjungan,
            $sub
        );

        /*
        |--------------------------------------------------------------------------
        | S - DATA KHUSUS NEONATUS
        |--------------------------------------------------------------------------
        */

        $statusObstetri = DB::table(
            'medicalrecord.sirmed_status_obstetri'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first();

        $penilaianBayi = DB::table(
            'medicalrecord.sirmed_penilaian_awal_bayi'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first();

        $s = [];

        if ($dewasa['SUBYEKTIF']) {
            $s[] = $dewasa['SUBYEKTIF'];
        }

        /*
        |--------------------------------------------------------------------------
        | DATA NEONATUS
        |--------------------------------------------------------------------------
        |
        | Kita ambil hanya kolom yang benar-benar berisi.
        | Tidak mengubah nilai database.
        |--------------------------------------------------------------------------
        */

        foreach ([
            'STATUS_BAYI' => 'Status Bayi',
            'JENIS_KELAMIN' => 'Jenis Kelamin',
            'BERAT_BADAN_LAHIR' => 'Berat Badan Lahir',
            'PANJANG_BADAN' => 'Panjang Badan',
            'LINGKAR_KEPALA' => 'Lingkar Kepala',
            'APGAR_1' => 'APGAR 1 Menit',
            'APGAR_5' => 'APGAR 5 Menit',
            'APGAR_10' => 'APGAR 10 Menit',
        ] as $field => $label) {

            if (
                $penilaianBayi &&
                property_exists($penilaianBayi, $field)
            ) {
                $value = $this->soapValue(
                    $penilaianBayi->{$field}
                );

                if ($value !== null) {
                    $s[] = $label . ': ' . $value;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | O
        |--------------------------------------------------------------------------
        */

        $dewasaSoap = $this->generateSoapRanapDewasa(
            $kunjungan,
            $sub
        );

        $o = [];

        if ($dewasaSoap['OBYEKTIF']) {
            $o[] = $dewasaSoap['OBYEKTIF'];
        }

        $fisikNeo = DB::table(
            'medicalrecord.sirmed_pemeriksaan_fisik_neonatus'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first();

        if ($fisikNeo) {

            foreach ((array) $fisikNeo as $field => $value) {

                if (in_array($field, [
                    'ID',
                    'KUNJUNGAN',
                    'TANGGAL',
                    'OLEH',
                    'STATUS',
                ])) {
                    continue;
                }

                $value = $this->soapValue($value);

                if ($value === null) {
                    continue;
                }

                $label = ucwords(
                    strtolower(
                        str_replace('_', ' ', $field)
                    )
                );

                $o[] = $label . ': ' . $value;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | RIWAYAT IMUNISASI
        |--------------------------------------------------------------------------
        */

        $imunisasi = DB::table(
            'medicalrecord.riwayat_tumbuh_kembang'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first([
                'IMUNISASI',
                'IMUNISASI_LAIN',
            ]);

        if ($imunisasi) {

            if ($this->soapValue($imunisasi->IMUNISASI)) {
                $o[] = 'Imunisasi: ' .
                    $this->soapValue($imunisasi->IMUNISASI);
            }

            if ($this->soapValue($imunisasi->IMUNISASI_LAIN)) {
                $o[] = 'Imunisasi Lain: ' .
                    $this->soapValue($imunisasi->IMUNISASI_LAIN);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | A + P
        |--------------------------------------------------------------------------
        */

        $dewasaSoap = $this->generateSoapRanapDewasa(
            $kunjungan,
            $sub
        );

        return [
            'SUBYEKTIF' => implode("\n", $s),
            'OBYEKTIF' => implode("\n", $o),
            'ASSESMENT' => $dewasaSoap['ASSESMENT'],
            'PLANNING' => $dewasaSoap['PLANNING'],
            'INSTRUKSI' => '',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | SOAP RANAP OBSGYN
    |--------------------------------------------------------------------------
    */
    private function generateSoapRanapObsgyn($kunjungan, $sub)
    {
        /*
        |--------------------------------------------------------------------------
        | ANAMNESIS
        |--------------------------------------------------------------------------
        */

        $soapDasar = $this->generateSoapRanapDewasa(
            $kunjungan,
            $sub
        );

        $s = $soapDasar['SUBYEKTIF'];

        /*
        |--------------------------------------------------------------------------
        | TANDA VITAL
        |--------------------------------------------------------------------------
        */

        $o = [];

        $tandaVital = DB::table('medicalrecord.tanda_vital')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('PPA', 1)
            ->whereIn('STATUS', [1, 2])
            ->orderByDesc('ID')
            ->first([
                'KEADAAN_UMUM',
                'SISTOLIK',
                'DISTOLIK',
                'FREKUENSI_NADI',
                'FREKUENSI_NADI_CB',
                'SUHU',
                'SATURASI_O2',
                'FREKUENSI_NAFAS',
                'FREKUENSI_NAFAS_CB',
                'GCS',
            ]);

        if ($tandaVital) {

            if ($this->soapValue($tandaVital->KEADAAN_UMUM)) {
                $o[] = 'Keadaan Umum: ' .
                    $this->soapValue($tandaVital->KEADAAN_UMUM);
            }

            if (
                $tandaVital->SISTOLIK !== null ||
                $tandaVital->DISTOLIK !== null
            ) {
                $o[] = 'TD: ' .
                    ($tandaVital->SISTOLIK ?? '-') .
                    '/' .
                    ($tandaVital->DISTOLIK ?? '-') .
                    ' mmHg';
            }

            if ($tandaVital->FREKUENSI_NADI !== null) {
                $o[] = 'Nadi: ' .
                    $tandaVital->FREKUENSI_NADI .
                    ' x/menit';
            }

            if ($tandaVital->SUHU !== null) {
                $o[] = 'Suhu: ' .
                    $tandaVital->SUHU .
                    ' °C';
            }

            if ($tandaVital->SATURASI_O2 !== null) {
                $o[] = 'SpO2: ' .
                    $tandaVital->SATURASI_O2 .
                    ' %';
            }

            if ($tandaVital->FREKUENSI_NAFAS !== null) {
                $o[] = 'RR: ' .
                    $tandaVital->FREKUENSI_NAFAS .
                    ' x/menit';
            }

            if ($tandaVital->GCS !== null) {
                $o[] = 'GCS: ' .
                    $tandaVital->GCS;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | PEMERIKSAAN FISIK OBSGYN
        |--------------------------------------------------------------------------
        */

        $fisikObs = DB::table(
            'medicalrecord.sirmed_pemeriksaan_fisik_obsgyn'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first();

        if ($fisikObs) {

            foreach ((array) $fisikObs as $field => $value) {

                if (in_array($field, [
                    'ID',
                    'KUNJUNGAN',
                    'TANGGAL',
                    'OLEH',
                    'STATUS',
                ])) {
                    continue;
                }

                $value = $this->soapValue($value);

                if ($value === null) {
                    continue;
                }

                $label = ucwords(
                    strtolower(
                        str_replace('_', ' ', $field)
                    )
                );

                $o[] = $label . ': ' . $value;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | PENUNJANG LAIN
        |--------------------------------------------------------------------------
        */

        $eeg = DB::table('medicalrecord.pemeriksaan_eeg')
            ->where('KUNJUNGAN', $kunjungan)
            ->first([
                'HASIL',
                'KESIMPULAN',
            ]);

        if ($eeg) {

            if ($this->soapValue($eeg->HASIL)) {
                $o[] = 'Penunjang - Hasil: ' .
                    $this->soapValue($eeg->HASIL);
            }

            if ($this->soapValue($eeg->KESIMPULAN)) {
                $o[] = 'Penunjang - Kesimpulan: ' .
                    $this->soapValue($eeg->KESIMPULAN);
            }
        }

        $penunjang = DB::table(
            'medicalrecord.sirmed_pemeriksaan_penunjang_lain'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->first([
                'DESKRIPSI',
            ]);

        if ($penunjang && $this->soapValue($penunjang->DESKRIPSI)) {
            $o[] = 'Penunjang Lain: ' .
                $this->soapValue($penunjang->DESKRIPSI);
        }

        /*
        |--------------------------------------------------------------------------
        | A + P
        |--------------------------------------------------------------------------
        */

        return [
            'SUBYEKTIF' => $s,
            'OBYEKTIF' => implode("\n", $o),
            'ASSESMENT' => $soapDasar['ASSESMENT'],
            'PLANNING' => $soapDasar['PLANNING'],
            'INSTRUKSI' => '',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER FINALISASI
    |--------------------------------------------------------------------------
    */
    private function soapValue($value)
    {
        if ($value === null) {
            return null;
        }

        $value = trim(strip_tags((string) $value));

        return $value === '' ? null : $value;
    }

    private function soapLine($label, $value)
    {
        $value = $this->soapValue($value);

        if ($value === null) {
            return null;
        }

        return $label . ': ' . $value;
    }

    private function soapJoin($rows)
    {
        return implode("\n", array_values(array_filter(
            $rows,
            fn($row) => $row !== null && trim($row) !== ''
        )));
    }
}
