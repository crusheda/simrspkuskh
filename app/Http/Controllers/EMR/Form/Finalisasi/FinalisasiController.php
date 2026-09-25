<?php

namespace App\Http\Controllers\EMR\Form\Finalisasi;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Throwable;
use Carbon\Carbon;
use Auth, Storage;

use App\Http\Controllers\EMR\Form\AddOnPengkajianController;
use App\Support\FinalisasiMap;

class FinalisasiController extends Controller
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
        return FinalisasiMap::get($formKey);
    }

    private function getStatusFinalisasi(
        string $kunjungan,
        string $formKey
    ): array {
        $mapping = $this->getFinalisasiFormSub($formKey);

        $data = DB::table('simrspku_pengkajian.finalisasi as fin')
            ->leftJoin('aplikasi.pengguna as usr1','usr1.ID','=','fin.USER_CREATED')
            ->leftJoin('aplikasi.pengguna as usr2','usr2.ID','=','fin.USER_UPDATED')
            ->select(
                'fin.*',
                DB::raw('master.getNamaLengkapPegawai(usr1.NIP) AS NAMAUSER_CREATED'),
                DB::raw('master.getNamaLengkapPegawai(usr2.NIP) AS NAMAUSER_UPDATED'),
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

    public function statusFinalisasiPengkajian(
        Request $request,
        string $kunjungan
    ) {
        $formKey = $request->formKey;

        return response()->json(
            $this->getStatusFinalisasi($kunjungan, $formKey)
        );
    }

    private function generateSoapPengkajian($kunjungan, $form, $sub)
    {
        return match ($form) {

            /*
            |--------------------------------------------------------------------------
            | RAWAT DARURAT
            |--------------------------------------------------------------------------
            */
            'pengkajian-radar' =>
                $this->generateSoapGawatDarurat(
                    $kunjungan
                ),

            /*
            |--------------------------------------------------------------------------
            | RAWAT INAP
            |--------------------------------------------------------------------------
            */
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
            
            /*
            |--------------------------------------------------------------------------
            | RAWAT JALAN
            |--------------------------------------------------------------------------
            */
            'pengkajian-rajal-dewasa',
            'pengkajian-rajal-anak',
            'pengkajian-rajal-psikiatri',
            'pengkajian-rajal-geriatri', =>
                $this->generateSoapRajalDewasa(
                    $kunjungan,
                    $sub
                ),

            'pengkajian-rajal-obsgyn' =>
                $this->generateSoapRajalObsgyn(
                    $kunjungan,
                    $sub
                ),

            default => throw new \InvalidArgumentException(
                "Form SOAP tidak dikenali: {$form}"
            ),
        };
    }

    public function finalisasiPengkajian(Request $request, $kunjungan)
    {
        DB::beginTransaction();

        try {
            $created = now();
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
            | GET DATA KUNJUNGAN PASIEN
            |--------------------------------------------------------------------------
            */
            $getKunjungan = DB::table('pendaftaran.kunjungan as pk')
                                ->join(
                                    'pendaftaran.pendaftaran as pp',
                                    'pp.NOMOR',
                                    '=',
                                    'pk.NOPEN'
                                )
                                ->select(
                                    'pp.NOMOR',
                                    'pp.TANGGAL AS TGL_DAFTAR',
                                    'pk.MASUK AS TGL_MASUK',
                                    'pk.KELUAR AS TGL_KELUAR',
                                )
                                ->where('pk.NOMOR', $kunjungan)
                                ->whereIn('pk.STATUS',[1,2])
                                ->first();

            if (!$getKunjungan) {

                DB::rollBack();

                return response()->json([
                    'status' => false,
                    'message' => 'Data kunjungan pasien tidak ditemukan, pastikan data pasien adalah VALID dan tidak berstatus Batal Kunjungan.'
                ], 422);
            }

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
            | SIMPAN SOAP KHUSUS DOKTER
            |--------------------------------------------------------------------------
            |
            | Finalisasi perawat hanya mengubah status pada tabel
            | simrspku_pengkajian.finalisasi. Tidak membuat atau memperbarui
            | CPPT maupun medicalrecord.push_cppt.
            |
            */
            $soap = null;

            if ($sub === 'DOKTER') {
                $soap = $this->simpanSoapPengkajian(
                    $kunjungan,
                    $form,
                    $sub
                );
            }

            /*
            |--------------------------------------------------------------------------
            | VALIDASI PER FORM
            |--------------------------------------------------------------------------
            */
            // if ($form == 'pengkajian-radar') {
            //     DB::table('layanan.pasien_pulang')
            //         ->updateOrInsert(
            //             [
            //                 'KUNJUNGAN' => $kunjungan,
            //                 'NOPEN'     => $getDataKunjungan->NOPEN,
            //             ],
            //             [
            //                 'CARA'      => $request->tla_ck,
            //                 'KEADAAN'   => $request->tla_kk,
            //                 'DIAGNOSA'  => '',
            //                 'TANGGAL'   => now(),
            //                 'DOKTER'    => $getDataDokter->ID ?? 0,
            //                 'OLEH'      => auth()->id(),
            //                 'STATUS'    => 1,
            //             ]
            //         );
            // }

            /*
            |--------------------------------------------------------------------------
            | FINALISASI & VALIDATION
            |--------------------------------------------------------------------------
            */
            if (str_starts_with($form, 'pengkajian-ranap-')) { // KHUSUS SEMUA FORM RAWAT INAP TGL FINALISASI ADALAH 15 JAM SETELAH PASIEN MASUK RANAP
                $created = Carbon::parse($getKunjungan->TGL_MASUK)->addHours(15);
            }

            if ($finalisasi) {

                DB::table('simrspku_pengkajian.finalisasi')
                    ->where('ID', $finalisasi->ID)
                    ->update([
                        'STATUS' => 2,
                        'USER_UPDATED' => auth()->id(),
                        'UPDATED' => now(),
                        // 'REASON' => null,
                    ]);

            } else {

                DB::table('simrspku_pengkajian.finalisasi')
                    ->insert([
                        'KUNJUNGAN' => $kunjungan,
                        'FORM' => $form,
                        'SUB' => $sub,
                        'USER_CREATED' => auth()->id(),
                        'USER_UPDATED' => auth()->id(),
                        'CREATED' => $created,
                        'STATUS' => 2,
                        // 'REASON' => null,
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
    public function batalFinalisasiPengkajian(Request $request, $kunjungan)
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

    private function generateSoapGawatDarurat(string $kunjungan): array
    {
        /*
        |--------------------------------------------------------------------------
        | DATA KUNJUNGAN
        |--------------------------------------------------------------------------
        */
        $getDataKunjungan = DB::table('pendaftaran.kunjungan as pk')
            ->join(
                'pendaftaran.pendaftaran as pp',
                'pp.NOMOR',
                '=',
                'pk.NOPEN'
            )
            ->select(
                'pk.NOMOR as KUNJUNGAN',
                'pp.NOMOR as NOPEN',
                'pp.NORM'
            )
            ->where('pk.NOMOR', $kunjungan)
            ->first();

        if (!$getDataKunjungan) {
            throw new \InvalidArgumentException(
                "Data kunjungan tidak ditemukan: {$kunjungan}"
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SUBYEKTIF
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

        $anamnesis = DB::table('medicalrecord.anamnesis')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first([
                'DESKRIPSI'
            ]);

        $keluhanUtama = DB::table('medicalrecord.keluhan_utama')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
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

        $rps = $anamnesis?->DESKRIPSI ?? '';

        $rows_s = [];

        if ($anamnesisDiperoleh) {

            if ((int) $anamnesisDiperoleh->AUTOANAMNESIS === 1) {
                $rows_s[] = 'Autoanamnesis';
            }

            if ((int) $anamnesisDiperoleh->ALLOANAMNESIS === 1) {
                $dari = $this->soapValue($anamnesisDiperoleh->DARI);

                $rows_s[] = 'Alloanamnesis' . (
                    $dari ? ' dari ' . $dari : ''
                );
            }
        }

        if ($keluhanUtama) {
            $value = $this->soapValue($keluhanUtama->DESKRIPSI);

            if ($value) {
                $rows_s[] = 'Keluhan Utama: ' . $value;
            }
        }

        if ($anamnesis) {
            $value = $this->soapValue($anamnesis->DESKRIPSI);

            if ($value) {
                $rows_s[] = 'Riwayat Penyakit Sekarang: ' . $value;
            }
        }

        if ($rpp) {
            $value = $this->soapValue($rpp->DESKRIPSI);

            if ($value) {
                $rows_s[] = 'Riwayat Penyakit Dahulu: ' . $value;
            }
        }

        $cppt_s = implode("\n", $rows_s);

        /*
        |--------------------------------------------------------------------------
        | OBYEKTIF / TANDA VITAL
        |--------------------------------------------------------------------------
        */

        $tandaVital = DB::table('medicalrecord.tanda_vital')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first([
                'KEADAAN_UMUM',
                'SISTOLIK',
                'DISTOLIK',
                'FREKUENSI_NADI',
                'FREKUENSI_NADI_CB',
                'SUHU',
                'SATURASI_O2',
                'TINGKAT_KESADARAN',
                'FREKUENSI_NAFAS',
                'FREKUENSI_NAFAS_CB',
                'PUPIL',
                'DIAMETER_PUPIL_UP',
                'DIAMETER_PUPIL_DOWN',
                'RC_UP',
                'RC_DOWN',
                'VAS',
                'EYE',
                'MOTORIK',
                'VERBAL',
                'GCS',
                'JALAN_NAFAS',
                'ALAT_BANTU_NAFAS',
                'KULIT',
            ]);


        $cppt_o = "<div style='color:#9CC96B'>Pemeriksan Umum:</div>";

        if ($tandaVital) {

            $kulitcp = '-';
            if ($tandaVital->KULIT) {
                // Kulit
                if ($tandaVital->KULIT == 1) {
                    $kulitcp = "Normal";
                } elseif ($tandaVital->KULIT == 2) {
                    $kulitcp = "Jaundice";
                } elseif ($tandaVital->KULIT == 3) {
                    $kulitcp = "Akral Dingin";
                } elseif ($tandaVital->KULIT == 4) {
                    $kulitcp = "Sianotik";
                } elseif ($tandaVital->KULIT == 5) {
                    $kulitcp = "Berkeringat";
                } else {
                    $kulitcp = "-";
                }
            }

            $freknadicp = '';
            if ($tandaVital->FREKUENSI_NADI_CB) {
                if ($tandaVital->FREKUENSI_NADI_CB == 1) {
                    $freknadicp = "(Reguler)";
                } else {
                    $freknadicp = "(Ireguler)";
                }
            }

            $freknafascp = '';
            if ($tandaVital->FREKUENSI_NAFAS_CB) {
                if ($tandaVital->FREKUENSI_NAFAS_CB == 1) {
                    $freknafascp = "(Simetris)";
                } else {
                    $freknafascp = "(Asimetris)";
                }
            }

            $pupilcp = '';
            if ($tandaVital->PUPIL) {
                if ($tandaVital->PUPIL == 1) {
                    $pupilcp = "Isokor";
                } else {
                    $pupilcp = "Anisokor";
                }
            }

            $tgktkesadarancp = '-';
            if ($tandaVital->TINGKAT_KESADARAN) {
                $reftgksdr = DB::table('master.referensi')
                            ->select('DESKRIPSI')
                            ->where('JENIS',179)
                            ->where('ID',$tandaVital->TINGKAT_KESADARAN)
                            ->where('STATUS',1)
                            ->first();
                $tgktkesadarancp = $reftgksdr->DESKRIPSI;
            }

            $pemeriksaanFisik = DB::table('medicalrecord.pemeriksaan_fisik')
                ->where('KUNJUNGAN', $kunjungan)
                ->where('PENDAFTARAN', $getDataKunjungan->NOPEN)
                ->first();

            $cppt_o .= implode("\n", array_filter([

                "Keadaan Umum: " . ($tandaVital->KEADAAN_UMUM ?? ''),

                (
                    ($tandaVital->SISTOLIK ?? '') !== '' ||
                    ($tandaVital->DISTOLIK ?? '') !== ''
                )
                    ? "Tekanan Darah: "
                        . $this->soapNumber($tandaVital->SISTOLIK)
                        . "/"
                        . $this->soapNumber($tandaVital->DISTOLIK)
                        . ' mmHg'
                    : '',

                ($tandaVital->FREKUENSI_NADI ?? '') !== ''
                    ? "Frekuensi Nadi: "
                        . $this->soapNumber($tandaVital->FREKUENSI_NADI)
                        . " {$freknadicp}"
                    : '',

                "Suhu: " . ($tandaVital->SUHU ?? '') ."°C",

                ($tandaVital->SATURASI_O2 ?? '') !== ''
                    ? "Saturasi O2: "
                        . $this->soapNumber($tandaVital->SATURASI_O2)
                        . "%"
                    : '',

                "Tingkat Kesadaran: " . ($tgktkesadarancp ?? ''),

                ($tandaVital->FREKUENSI_NAFAS ?? '') !== ''
                    ? "Frekuensi Nafas: "
                        . $this->soapNumber($tandaVital->FREKUENSI_NAFAS) . 'x/menit'
                        . " {$freknafascp}"
                    : '',

                implode(', ', array_filter([
                    ($tandaVital->PUPIL ?? '') !== ''
                        ? 'Pupil: ' . $pupilcp
                        : '',

                    (
                        ($tandaVital->DIAMETER_PUPIL_UP ?? '') !== '' &&
                        ($tandaVital->DIAMETER_PUPIL_DOWN ?? '') !== ''
                    )
                        ? 'Diameter: '
                            . $tandaVital->DIAMETER_PUPIL_UP
                            . 'mm / '
                            . $tandaVital->DIAMETER_PUPIL_DOWN
                            . 'mm'
                        : '',

                    (
                        ($tandaVital->RC_UP ?? '') !== '' &&
                        ($tandaVital->RC_DOWN ?? '') !== ''
                    )
                        ? 'Refleks Cahaya: '
                            . $tandaVital->RC_UP
                            . ' / '
                            . $tandaVital->RC_DOWN
                        : '',
                ])),

                ($tandaVital->GCS ?? '') !== ''
                    ? "GCS: "
                        . $this->soapNumber($tandaVital->GCS)
                        . " (E/"
                        . ($tandaVital->EYE ?? '')
                        . " M/"
                        . ($tandaVital->MOTORIK ?? '')
                        . " V/"
                        . ($tandaVital->VERBAL ?? '')
                        . "), VAS: "
                        . ($tandaVital->VAS ?? '')
                    : '',

                "Alat Bantu Nafas: "
                    . (($tandaVital->ALAT_BANTU_NAFAS ?? null) == 2
                        ? 'Ya'
                        : 'Tidak'),

                "Kulit: {$kulitcp}",

                "",

                "<div style='color:#9CC96B'>Pemeriksaan Fisik: </div>"
                    . ($pemeriksaanFisik->DESKRIPSI ?? '-'),

            ], function ($value) {

                // Elemen HTML tetap ditampilkan
                if (str_starts_with(trim($value), '<')) {
                    return true;
                }

                // Ambil nilai setelah tanda titik dua
                $posisi = strpos($value, ':');

                if ($posisi === false) {
                    return trim($value) !== '';
                }

                return trim(substr($value, $posisi + 1)) !== '';
            }));
        }


        /*
        |--------------------------------------------------------------------------
        | ASSESMENT / DIAGNOSA
        |--------------------------------------------------------------------------
        */

        $getDiagnosa = DB::table('medicalrecord.diagnosa as diag')
            ->select(
                'diag.ID',
                'diag.DIAGNOSA',
                DB::raw("
                    CASE
                        WHEN diag.UTAMA = 1
                        THEN 'UTAMA'
                        ELSE 'SEKUNDER'
                    END AS UTAMA
                ")
            )
            ->where('diag.NOPEN', $getDataKunjungan->NOPEN)
            ->where('diag.STATUS', 1)
            ->get();

        $diagnosa = $getDiagnosa
            ->map(function ($item) {

                return $item->DIAGNOSA .
                    ' (' .
                    $item->UTAMA .
                    ')';
            })
            ->implode("\n");

        $cppt_a =
            // "<div style='color:#9CC96B'>Diagnosa Dokter:</div>" .
            $diagnosa;


        /*
        |--------------------------------------------------------------------------
        | PLANNING
        |--------------------------------------------------------------------------
        */

        $rencanaTerapi = DB::table('medicalrecord.rencana_terapi')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first([
                'DESKRIPSI'
            ]);

        $hasilLapor = DB::table('medicalrecord.hasil_lapor_dpjp')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first([
                'DESKRIPSI'
            ]);

        $cppt_p = implode("\n", array_filter([

            "<div style='color:#9CC96B'>Perencanaan Terapi:</div>" .
                ($rencanaTerapi->DESKRIPSI ?? ''),

            "\n<div style='color:#9CC96B'>Hasil Lapor DPJP:</div>" .
                ($hasilLapor->DESKRIPSI ?? ''),

        ], function ($value) {

            return trim(str_replace(
                [
                    'Perencanaan Terapi:',
                    'Hasil Lapor DPJP:'
                ],
                '',
                $value
            )) !== '';
        }));


        /*
        |--------------------------------------------------------------------------
        | INSTRUKSI
        |--------------------------------------------------------------------------
        */

        $cppt_i = '-';


        /*
        |--------------------------------------------------------------------------
        | RETURN SOAP
        |--------------------------------------------------------------------------
        */

        return [
            'SUBYEKTIF' => $cppt_s,
            'OBYEKTIF'  => $cppt_o,
            'ASSESMENT' => $cppt_a,
            'PLANNING'  => $cppt_p,
            'INSTRUKSI' => $cppt_i,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | SOAP
    |--------------------------------------------------------------------------
    */
    private function simpanSoapPengkajian($kunjungan, $form, $sub)
    {
        $soap = $this->generateSoapPengkajian(
            $kunjungan,
            $form,
            $sub
        );

        $tenagaMedis = DB::table('aplikasi.pengguna AS pe')
            ->leftJoin('master.pegawai AS peg','peg.NIP','=','pe.NIP')
            ->where('pe.ID', auth()->id())
            ->select(
                'peg.ID',
                'peg.NIP'
            )
            ->first();

        $dataCppt = [
            'KUNJUNGAN' => $kunjungan,
            'TANGGAL' => now(),
            'SUBYEKTIF' => $soap['SUBYEKTIF'] ?? '',
            'OBYEKTIF' => $soap['OBYEKTIF'] ?? '',
            'ASSESMENT' => $soap['ASSESMENT'] ?? '',
            'PLANNING' => $soap['PLANNING'] ?? '',
            'INSTRUKSI' => $soap['INSTRUKSI'] ?? '',
            'JENIS' => 1,
            'TENAGA_MEDIS' => $tenagaMedis->ID,
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

            $persalinan = [
                1 => 'SC',
                2 => 'SPONTAN',
                3 => 'VACUM',
                4 => 'LAINNYA',
            ];

            $rows[] = $this->soapLine(
                'Persalinan',
                $persalinan[$statusReproduksi->PERSALINAN] ?? $statusReproduksi->PERSALINAN
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

            $imunisasi = [
                1 => 'Lengkap',
                2 => 'Tidak Lengkap',
                3 => 'Tidak Imunisasi',
                4 => 'Lain-lain',
            ];

            $rows[] = $this->soapLine(
                'Imunisasi',
                $imunisasi[$tumbuhKembang->IMUNISASI] ?? $tumbuhKembang->IMUNISASI
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

        $o[] = "<b style='color:#9CC96B'>Pemeriksaan Umum</b>";

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

            $freknadicb = '';
            if ($tandaVital->FREKUENSI_NADI_CB == 1) {
                $freknadicb = 'Reguler';
            } else {
                $freknadicb = 'Ireguler';
            }

            if ($tandaVital->FREKUENSI_NADI !== null) {
                $o[] = 'Nadi: ' .
                    $tandaVital->FREKUENSI_NADI .
                    ' x/menit' . ($freknadicb != '' ? " ({$freknadicb})" : "");
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

            $freknafascb = '';
            if ($tandaVital->FREKUENSI_NADI_CB == 1) {
                $freknafascb = 'Simetris';
            } else {
                $freknafascb = 'Asimetris';
            }

            if ($tandaVital->FREKUENSI_NAFAS !== null) {
                $o[] = 'RR: ' .
                    $tandaVital->FREKUENSI_NAFAS .
                    ' x/menit' . ($freknafascb != '' ? " ({$freknafascb})" : "");
            }

            if ($tandaVital->GCS !== null) {
                $o[] = 'GCS: ' .
                    $tandaVital->GCS .
                    ' (E/' .
                    ($tandaVital->EYE ?? '-') .
                    ' V/' .
                    ($tandaVital->VERBAL ?? '-') .
                    ' M/' .
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

        $o[] = "<b style='color:#9CC96B'>Pemeriksaan Fisik</b>";

        $anatomi = DB::table('medicalrecord.sirmed_pemeriksaan_anatomi')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first();

        if ($anatomi) {

            // Mata
            $mata = [];

            if ($this->soapValue($anatomi->pf_anemis) == 0) {
                $mata[] = 'Anemis (-)';
            } else if ($this->soapValue($anatomi->pf_anemis) == 1) {
                $mata[] = 'Anemis (+)';
            } else {}

            if ($this->soapValue($anatomi->pf_ikterus) == 0) {
                $mata[] = 'Ikterus (-)';
            } else if ($this->soapValue($anatomi->pf_ikterus) == 1) {
                $mata[] = 'Ikterus (+)';
            } else {}

            if ($this->soapValue($anatomi->pf_upal) == 0) {
                $mata[] = 'Edema Palpebrae (-)';
            } else if ($this->soapValue($anatomi->pf_upal) == 1) {
                $mata[] = 'Edema Palpebrae (+)';
            } else {}

            // if ($this->soapValue($anatomi->pf_ikterus)) {
            //     $mata[] = 'Ikterus';
            // }

            // if ($this->soapValue($anatomi->pf_upal)) {
            //     $mata[] = 'Udema Palpebrae';
            // }

            if ($this->soapValue($anatomi->pf_pupil) == 1) {
                $mata[] = 'Pupil Isokor';
            } else {
                $mata[] = 'Pupil Anisokor';
            }

            if ($this->soapValue($anatomi->pf_dia_up) && $this->soapValue($anatomi->pf_dia_down)) {
                $mata[] = 'Diameter: ' . $this->soapValue($anatomi->pf_dia_up) . 'mm/' . $this->soapValue($anatomi->pf_dia_down) .'mm';
            }

            if ($this->soapValue($anatomi->pf_kelainan_mata)) {
                $mata[] = 'Ada Kelainan:'.$this->soapValue($anatomi->pf_kelainan_mata);
            }

            if ($mata) {
                $o[] = '<b style="color:#38e2c6">Mata</b>: ' . implode(', ', $mata);
            }


            // Mulut
            if ($this->soapValue($anatomi->pf_mulut)) {
                $o[] = '<b style="color:#38e2c6">Mulut</b>: ' .
                    $this->soapValue($anatomi->pf_mulut);
            }


            // Leher
            $leher = [];

            if ($this->soapValue($anatomi->pf_jvp)) {
                $leher[] = 'JVP ' . $this->soapValue($anatomi->pf_jvp);
            }

            if ($this->soapValue($anatomi->pf_pkl) == 1) {
                $leher[] = 'Ada Pembesaran kelenjar limfe';
            }

            if ($this->soapValue($anatomi->pf_pkl_lain)) {
                $leher[] = $this->soapValue($anatomi->pf_pkl_lain);
            }

            if ($this->soapValue($anatomi->pf_kd)) {
                $leher[] = 'Kaku Duduk Ada';
            }

            if ($this->soapValue($anatomi->pf_kd_lain)) {
                $leher[] = $this->soapValue($anatomi->pf_kd_lain);
            }

            if ($this->soapValue($anatomi->pf_kelainan_leher) == 2) {
                $leher[] = 'Ada kelainan leher';
            }

            if ($leher) {
                $o[] = '<b style="color:#38e2c6">Leher</b>: ' . implode(', ', $leher);
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
                $thoraks[] = 'Cor S1/S2: ' .
                    $this->soapValue($anatomi->pf_cor);
            }

            if ($this->soapValue($anatomi->pf_cor_cb) == 1) {
                $thoraks[] = '(Irama Reguler)';
            }

            if ($this->soapValue($anatomi->pf_cor_cb) == 2) {
                $thoraks[] = '(Irama Ireguler)';
            }

            if ($this->soapValue($anatomi->pf_murmur)) {
                $thoraks[] = 'Murmur: ' . $this->soapValue($anatomi->pf_murmur);
            }

            if ($this->soapValue($anatomi->pf_murmur_lain)) {
                $thoraks[] = 'Lain-lain: ' . $this->soapValue($anatomi->pf_murmur_lain);
            }

            if ($this->soapValue($anatomi->pf_pulmo)) {
                $thoraks[] = 'Pulmo: ' . $this->soapValue($anatomi->pf_pulmo);
            }

            // Ronchi
            $ronchiLeft = $this->soapValue($anatomi->pf_ronchi_left ?? null);
            $ronchiRight = $this->soapValue($anatomi->pf_ronchi_right ?? null);

            if ($ronchiLeft || $ronchiRight) {
                $thoraks[] = 'Ronchi : '
                    . ($ronchiLeft ?: '-')
                    . ' / '
                    . ($ronchiRight ?: '-');

                if ($this->soapValue($anatomi->pf_ronchi_lain ?? null)) {
                    $thoraks[] = $this->soapValue($anatomi->pf_ronchi_lain);
                }
            }


            // Wheezing
            $wheezingLeft = $this->soapValue($anatomi->pf_wheezing_left ?? null);
            $wheezingRight = $this->soapValue($anatomi->pf_wheezing_right ?? null);

            if ($wheezingLeft || $wheezingRight) {
                $thoraks[] = 'Wheezing : '
                    . ($wheezingLeft ?: '-')
                    . ' / '
                    . ($wheezingRight ?: '-');

                if ($this->soapValue($anatomi->pf_wheezing_lain ?? null)) {
                    $thoraks[] = $this->soapValue($anatomi->pf_wheezing_lain);
                }
            }

            if ($this->soapValue($anatomi->pf_kelainan_dada) == 2) {
                $thoraks[] = 'Ada Kelainan dada';
            }

            if ($thoraks) {
                $o[] = '<b style="color:#38e2c6">Thoraks</b>: ' . implode(', ', $thoraks);
            }


            // Abdomen
            $abdomen = [];

            if ($this->soapValue($anatomi->pf_distended) == 0) {
                $abdomen[] = 'Distended (-)';
            } else if ($this->soapValue($anatomi->pf_distended) == 1) {
                $abdomen[] = 'Distended (+)';
            } else {}

            if ($this->soapValue($anatomi->pf_meteor) == 0) {
                $abdomen[] = 'Meteorismus (-)';
            } else if ($this->soapValue($anatomi->pf_meteor) == 1) {
                $abdomen[] = 'Meteorismus (+)';
            } else {}

            if ($this->soapValue($anatomi->pf_asites) == 0) {
                $abdomen[] = 'Asites (-)';
            } else if ($this->soapValue($anatomi->pf_asites) == 1) {
                $abdomen[] = 'Asites (+)';
            } else {}

            // if ($this->soapValue($anatomi->pf_meteor)) {
            //     $abdomen[] = 'Meteorismus';
            // }

            // if ($this->soapValue($anatomi->pf_asites)) {
            //     $abdomen[] = 'Asites';
            // }

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
                $abdomen[] = 'Nyeri tekan ' . " ({$this->soapValue($anatomi->pf_nyeri_tekan_lain)})";
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
                $o[] = '<b style="color:#38e2c6">Abdomen</b>: ' . implode(', ', $abdomen);
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
                $extremitas[] = 'Edema Palpebrae' . " ({$this->soapValue($anatomi->pf_udem_lain)})";
            }

            if ($this->soapValue($anatomi->pf_dada_lain)) {
                $thoraks[] = $this->soapValue($anatomi->pf_dada_lain);
            }

            if ($extremitas) {
                $o[] = '<b style="color:#38e2c6">Ekstremitas</b>: ' .
                    implode(', ', $extremitas);
            }


            // Status Lokalis
            if ($this->soapValue($anatomi->status_lokalis)) {
                $o[] = '<b style="color:#38e2c6">Status Lokalis</b>: ' .
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

        // $a[] = "<b style='color:#9CC96B'>Diagnosa Dokter</b>";
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
                ? 'Diagnosa Utama'
                : 'Diagnosa Sekunder';

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
            $krpl = '';

            if ($kriteriaPulang->KRITERIA_PULANG !== null) {
                if ((int) $kriteriaPulang->KRITERIA_PULANG === 0) {
                    $karena = $this->soapValue($kriteriaPulang->KARENA);

                    $krpl = 'Belum bisa ditetapkan, karena'
                        . ($karena ? ' ' . $karena : '');
                } elseif ((int) $kriteriaPulang->KRITERIA_PULANG === 1) {
                    $hari = $this->soapValue($kriteriaPulang->HARI);

                    $krpl = 'Sudah bisa ditetapkan dalam'
                        . ($hari ? ' ' . $hari . ' hari' : '');
                } else {
                    $krpl = '-';
                }
            }

            $rows[] = '<br>Kriteria Pulang: ' . $krpl;

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
        $s = [];

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

        $statusNeonatus = DB::table('medicalrecord.sirmed_status_neonatus')
                ->where('KUNJUNGAN', $kunjungan)
                ->where('STATUS', 1)
                ->first();

        /*
        |--------------------------------------------------------------------------
        | STATUS OBSTETRI
        |--------------------------------------------------------------------------
        */

        if ($statusObstetri) {

            $dataObstetri = [
                'UMUR_IBU' => 'Umur Ibu',
                'UMUR_KEHAMILAN' => 'Umur Kehamilan',
                'GOL_DARAH_IBU' => 'Golongan Darah Ibu',
                'RH_IBU' => 'Rhesus Ibu',
                'GOL_DARAH_AYAH' => 'Golongan Darah Ayah',
                'RH_AYAH' => 'Rhesus Ayah',
                'KK_PECAH_JAM' => 'Ketuban Pecah',
            ];

            $statusObstetriText = [];

            /*
            |--------------------------------------------------------------------------
            | DATA OBSTETRI
            |--------------------------------------------------------------------------
            */

            foreach ($dataObstetri as $field => $label) {

                if (property_exists($statusObstetri, $field)) {

                    $value = $this->soapValue(
                        $statusObstetri->{$field}
                    );

                    if ($value !== null) {
                        $statusObstetriText[] =
                            $label . ': ' . $value;
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | G P A
            |--------------------------------------------------------------------------
            */

            $gpa = [];

            foreach (['G', 'P', 'A'] as $field) {

                if (property_exists($statusObstetri, $field)) {

                    $value = $this->soapValue(
                        $statusObstetri->{$field}
                    );

                    if ($value !== null) {
                        $gpa[] = $field . ' : ' . $value;
                    }
                }
            }

            if (!empty($gpa)) {
                $statusObstetriText[] = implode(', ', $gpa);
            }

            /*
            |--------------------------------------------------------------------------
            | KOMPLIKASI
            |--------------------------------------------------------------------------
            */

            if (
                property_exists($statusObstetri, 'KOMPLIKASI') &&
                $statusObstetri->KOMPLIKASI !== null
            ) {

                if ((int) $statusObstetri->KOMPLIKASI === 1) {

                    $komplikasi = 'Ada';

                    if (
                        property_exists($statusObstetri, 'KOMPLIKASI_KET')
                        && $this->soapValue($statusObstetri->KOMPLIKASI_KET) !== null
                    ) {
                        $komplikasi .= ': ' .
                            $this->soapValue(
                                $statusObstetri->KOMPLIKASI_KET
                            );
                    }

                } else {
                    $komplikasi = 'Tidak Ada';
                }

                $statusObstetriText[] =
                    'Komplikasi: ' . $komplikasi;
            }

            /*
            |--------------------------------------------------------------------------
            | GOLONGAN DARAH AYAH TIDAK TAHU
            |--------------------------------------------------------------------------
            */

            if (
                property_exists($statusObstetri, 'GOL_AYAH_TIDAK') &&
                $statusObstetri->GOL_AYAH_TIDAK !== null
            ) {

                if ((int) $statusObstetri->GOL_AYAH_TIDAK === 1) {
                    $statusObstetriText[] =
                        'Golongan Darah Ayah: Tidak Tahu';
                }
            }

            if (!empty($statusObstetriText)) {
                $s[] = 'Status Obstetri:';

                foreach ($statusObstetriText as $item) {
                    $s[] = $item;
                }
            }
        }

        if (!empty($statusObstetriText)) {
            $s[] = '';
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS NEONATUS
        |--------------------------------------------------------------------------
        */

        if ($statusNeonatus) {

            $statusNeonatusText = [];

            /*
            |--------------------------------------------------------------------------
            | TANGGAL DAN JAM LAHIR
            |--------------------------------------------------------------------------
            */

            if (
                property_exists($statusNeonatus, 'TANGGAL_LAHIR') &&
                $this->soapValue($statusNeonatus->TANGGAL_LAHIR) !== null
            ) {

                $tanggalLahir = $this->soapValue(
                    $statusNeonatus->TANGGAL_LAHIR
                );

                if (
                    property_exists($statusNeonatus, 'JAM_LAHIR') &&
                    $this->soapValue($statusNeonatus->JAM_LAHIR) !== null
                ) {

                    $jamLahir = $this->soapValue(
                        $statusNeonatus->JAM_LAHIR
                    );

                    $tanggalLahir .= ' ' . $jamLahir;
                }

                $statusNeonatusText[] =
                    'Tanggal/Jam Lahir: ' . $tanggalLahir;
            }

            /*
            |--------------------------------------------------------------------------
            | JENIS KELAMIN
            |--------------------------------------------------------------------------
            */

            if (
                property_exists($statusNeonatus, 'JENIS_KELAMIN') &&
                $this->soapValue($statusNeonatus->JENIS_KELAMIN) !== null
            ) {

                $jenisKelamin =
                    strtoupper(
                        $statusNeonatus->JENIS_KELAMIN
                    );

                if ($jenisKelamin === 'L') {
                    $jenisKelamin = 'Laki-laki';
                } elseif ($jenisKelamin === 'P') {
                    $jenisKelamin = 'Perempuan';
                }

                $statusNeonatusText[] =
                    'Jenis Kelamin: ' . $jenisKelamin;
            }

            /*
            |--------------------------------------------------------------------------
            | ANTROPOMETRI LAHIR
            |--------------------------------------------------------------------------
            */

            $dataNeonatus = [
                'BB_LAHIR' => 'BB Lahir',
                'PB_LAHIR' => 'PB Lahir',
                'LK' => 'Lingkar Kepala',
                'LD' => 'Lingkar Dada',
                'LP' => 'Lingkar Perut',
                'LILA' => 'LILA',
            ];

            $satuanNeonatus = [
                'BB_LAHIR' => 'gram',
                'PB_LAHIR' => 'cm',
                'LK' => 'cm',
                'LD' => 'cm',
                'LP' => 'cm',
                'LILA' => 'cm',
            ];

            foreach ($dataNeonatus as $field => $label) {

                if (property_exists($statusNeonatus, $field)) {

                    $value = $this->soapValue(
                        $statusNeonatus->{$field}
                    );

                    if ($value !== null) {

                        $value = (int) $value;

                        $satuan = $satuanNeonatus[$field] ?? '';

                        $statusNeonatusText[] =
                            $label . ': ' . $value . ' ' . $satuan;
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | RESUSITASI
            |--------------------------------------------------------------------------
            */

            if (
                property_exists($statusNeonatus, 'RESUSITASI_INTUBASI') &&
                $statusNeonatus->RESUSITASI_INTUBASI !== null
            ) {

                $statusNeonatusText[] =
                    'Resusitasi Intubasi: ' .
                    (
                        (int) $statusNeonatus->RESUSITASI_INTUBASI === 1
                            ? 'Ya'
                            : 'Tidak'
                    );
            }

            if (
                property_exists($statusNeonatus, 'RESUSITASI_POMPA') &&
                $statusNeonatus->RESUSITASI_POMPA !== null
            ) {

                $statusNeonatusText[] =
                    'Resusitasi Pompa: ' .
                    (
                        (int) $statusNeonatus->RESUSITASI_POMPA === 1
                            ? 'Ya'
                            : 'Tidak'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | BERULANG
            |--------------------------------------------------------------------------
            */

            if (
                property_exists($statusNeonatus, 'BERULANG')
            ) {

                $value = $this->soapValue(
                    $statusNeonatus->BERULANG
                );

                if ($value !== null) {

                    $statusNeonatusText[] =
                        'Berulang: ' . $value;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | JENIS PARTUS
            |--------------------------------------------------------------------------
            */

            if (
                property_exists($statusNeonatus, 'JENIS_PARTUS') &&
                $this->soapValue($statusNeonatus->JENIS_PARTUS) !== null
            ) {

                $jenisPartus =
                    strtoupper(
                        $statusNeonatus->JENIS_PARTUS
                    );

                $jenisPartusLabel = match ($jenisPartus) {
                    'SC' => 'Sectio Caesarea',
                    'V' => 'Vacuum',
                    'S' => 'Spontan',
                    default => $jenisPartus,
                };

                $statusNeonatusText[] =
                    'Jenis Partus: ' . $jenisPartusLabel;
            }

            /*
            |--------------------------------------------------------------------------
            | INDIKASI
            |--------------------------------------------------------------------------
            */

            if (
                property_exists($statusNeonatus, 'INDIKASI')
            ) {

                $value = $this->soapValue(
                    $statusNeonatus->INDIKASI
                );

                if ($value !== null) {

                    $statusNeonatusText[] =
                        'Indikasi: ' . $value;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | MASUK KE S
            |--------------------------------------------------------------------------
            */

            if (!empty($statusNeonatusText)) {
                $s[] = 'Status Neonatus:';

                foreach ($statusNeonatusText as $item) {
                    $s[] = $item;
                }
            }
        }

        $s[] = '';

        $dewasa = $this->generateSoapRanapDewasa(
            $kunjungan,
            $sub
        );

        if ($dewasa['SUBYEKTIF']) {
            $s[] = 'Anamnesis';
            $s[] = $dewasa['SUBYEKTIF'];
        }


        /*
        |--------------------------------------------------------------------------
        | O
        |--------------------------------------------------------------------------
        */

        $o = [];

        $penilaianBayi = DB::table(
            'medicalrecord.sirmed_penilaian_awal_bayi'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first();


        /*
        |--------------------------------------------------------------------------
        | APGAR
        |--------------------------------------------------------------------------
        */

        if ($penilaianBayi) {

            /*
            |--------------------------------------------------------------------------
            | STATUS BAYI
            |--------------------------------------------------------------------------
            */

            if (
                property_exists($penilaianBayi, 'APGAR_STATUS_BAYI') &&
                $this->soapValue($penilaianBayi->APGAR_STATUS_BAYI) !== null
            ) {

                $statusBayi = $this->soapValue(
                    $penilaianBayi->APGAR_STATUS_BAYI
                );

                $statusBayiLabel = match ($statusBayi) {
                    'bugar' => 'Bugar',
                    'tidak_bugar' => 'Tidak Bugar',
                    default => $statusBayi,
                };

                $o[] = 'Status Bayi: ' . $statusBayiLabel;
            }


            /*
            |--------------------------------------------------------------------------
            | BAYI BUGAR
            |--------------------------------------------------------------------------
            | Tampilkan APGAR 1, 5, dan 10 menit
            |--------------------------------------------------------------------------
            */

            if (
                property_exists($penilaianBayi, 'APGAR_STATUS_BAYI') &&
                $penilaianBayi->APGAR_STATUS_BAYI === 'bugar'
            ) {

                $apgarMenit = [
                    1 => [
                        // 'DENYUT' => 'APGAR 1 Menit - Denyut',
                        // 'PERNAFASAN' => 'APGAR 1 Menit - Pernafasan',
                        // 'TONUS' => 'APGAR 1 Menit - Tonus',
                        // 'RANGSANG' => 'APGAR 1 Menit - Rangsang',
                        // 'WARNA' => 'APGAR 1 Menit - Warna',
                        'TOTAL' => 'APGAR Total 1 Menit',
                    ],
                    5 => [
                        // 'DENYUT' => 'APGAR 5 Menit - Denyut',
                        // 'PERNAFASAN' => 'APGAR 5 Menit - Pernafasan',
                        // 'TONUS' => 'APGAR 5 Menit - Tonus',
                        // 'RANGSANG' => 'APGAR 5 Menit - Rangsang',
                        // 'WARNA' => 'APGAR 5 Menit - Warna',
                        'TOTAL' => 'APGAR Total 5 Menit',
                    ],
                    10 => [
                        // 'DENYUT' => 'APGAR 10 Menit - Denyut',
                        // 'PERNAFASAN' => 'APGAR 10 Menit - Pernafasan',
                        // 'TONUS' => 'APGAR 10 Menit - Tonus',
                        // 'RANGSANG' => 'APGAR 10 Menit - Rangsang',
                        // 'WARNA' => 'APGAR 10 Menit - Warna',
                        'TOTAL' => 'APGAR Total 10 Menit',
                    ],
                ];

                foreach ($apgarMenit as $menit => $fields) {

                    foreach ($fields as $suffix => $label) {

                        if ($suffix === 'TOTAL') {
                            $field = 'APGAR_TOTAL_' . $menit . '_MENIT';
                        } else {
                            $field = 'APGAR_' . $menit . '_MENIT_' . $suffix;
                        }

                        if (property_exists($penilaianBayi, $field)) {

                            $value = $this->soapValue(
                                $penilaianBayi->{$field}
                            );

                            if ($value !== null) {
                                $o[] = $label . ': ' . $value;
                            }
                        }
                    }
                }
            }


            /*
            |--------------------------------------------------------------------------
            | BAYI TIDAK BUGAR
            |--------------------------------------------------------------------------
            | Tampilkan data resusitasi
            |--------------------------------------------------------------------------
            */

            if (
                property_exists($penilaianBayi, 'APGAR_STATUS_BAYI') &&
                $penilaianBayi->APGAR_STATUS_BAYI === 'tidak_bugar'
            ) {

                /*
                | APGAR Resusitasi
                */

                if (
                    property_exists($penilaianBayi, 'APGAR_RESUSITASI') &&
                    $this->soapValue($penilaianBayi->APGAR_RESUSITASI) !== null
                ) {

                    $resusitasi = $this->soapValue(
                        $penilaianBayi->APGAR_RESUSITASI
                    );

                    $resusitasiLabel = match ($resusitasi) {
                        'dilakukan' => 'Dilakukan',
                        'tidak_dilakukan' => 'Tidak Dilakukan',
                        default => $resusitasi,
                    };

                    $o[] = 'APGAR Resusitasi: ' . $resusitasiLabel;
                }


                /*
                | APGAR Langkah Awal
                */

                if (
                    property_exists($penilaianBayi, 'APGAR_LANGKAH_AWAL') &&
                    $penilaianBayi->APGAR_LANGKAH_AWAL !== null
                ) {

                    $o[] = 'APGAR Langkah Awal: ' .
                        (
                            (int) $penilaianBayi->APGAR_LANGKAH_AWAL === 1
                                ? 'Ya'
                                : 'Tidak'
                        );
                }

                if (
                    property_exists($penilaianBayi, 'APGAR_LANGKAH_AWAL_DETIK')
                ) {

                    $value = $this->soapValue(
                        $penilaianBayi->APGAR_LANGKAH_AWAL_DETIK
                    );

                    if ($value !== null) {
                        $o[] = 'APGAR Langkah Awal Detik: ' . $value;
                    }
                }


                /*
                | APGAR VTP
                */

                if (
                    property_exists($penilaianBayi, 'APGAR_VTP') &&
                    $penilaianBayi->APGAR_VTP !== null
                ) {

                    $o[] = 'APGAR VTP: ' .
                        (
                            (int) $penilaianBayi->APGAR_VTP === 1
                                ? 'Ya'
                                : 'Tidak'
                        );
                }

                if (
                    property_exists($penilaianBayi, 'APGAR_VTP_DETIK')
                ) {

                    $value = $this->soapValue(
                        $penilaianBayi->APGAR_VTP_DETIK
                    );

                    if ($value !== null) {
                        $o[] = 'APGAR VTP Detik: ' . $value;
                    }
                }


                /*
                | APGAR Kompresi Dada
                */

                if (
                    property_exists($penilaianBayi, 'APGAR_KOMPRESI_DADA') &&
                    $penilaianBayi->APGAR_KOMPRESI_DADA !== null
                ) {

                    $o[] = 'APGAR Kompresi Dada: ' .
                        (
                            (int) $penilaianBayi->APGAR_KOMPRESI_DADA === 1
                                ? 'Ya'
                                : 'Tidak'
                        );
                }

                if (
                    property_exists($penilaianBayi, 'APGAR_KOMPRESI_DADA_DETIK')
                ) {

                    $value = $this->soapValue(
                        $penilaianBayi->APGAR_KOMPRESI_DADA_DETIK
                    );

                    if ($value !== null) {
                        $o[] = 'APGAR Kompresi Dada Detik: ' . $value;
                    }
                }


                /*
                | APGAR ETT
                */

                if (
                    property_exists($penilaianBayi, 'APGAR_ETT') &&
                    $penilaianBayi->APGAR_ETT !== null
                ) {

                    $o[] = 'APGAR ETT: ' .
                        (
                            (int) $penilaianBayi->APGAR_ETT === 1
                                ? 'Ya'
                                : 'Tidak'
                        );
                }


                /*
                | APGAR Resusitasi Dihentikan
                */

                if (
                    property_exists(
                        $penilaianBayi,
                        'APGAR_RESUSITASI_DIHENTIKAN'
                    ) &&
                    $penilaianBayi->APGAR_RESUSITASI_DIHENTIKAN !== null
                ) {

                    $o[] = 'APGAR Resusitasi Dihentikan: ' .
                        (
                            (int) $penilaianBayi->APGAR_RESUSITASI_DIHENTIKAN === 1
                                ? 'Ya'
                                : 'Tidak'
                        );
                }

                if (
                    property_exists(
                        $penilaianBayi,
                        'APGAR_RESUSITASI_DIHENTIKAN_MENIT'
                    )
                ) {

                    $value = $this->soapValue(
                        $penilaianBayi->APGAR_RESUSITASI_DIHENTIKAN_MENIT
                    );

                    if ($value !== null) {
                        $o[] =
                            'APGAR Resusitasi Dihentikan Menit: ' .
                            $value;
                    }
                }
            }


            /*
            |--------------------------------------------------------------------------
            | DATA YANG SELALU DITAMPILKAN
            |--------------------------------------------------------------------------
            | Tidak peduli bugar / tidak bugar
            |--------------------------------------------------------------------------
            */

            if (
                property_exists($penilaianBayi, 'APGAR_TANGGAL')
            ) {

                $value = $this->soapValue(
                    $penilaianBayi->APGAR_TANGGAL
                );

                if ($value !== null) {
                    $o[] = 'APGAR Tanggal: ' . $value;
                }
            }

            if (
                property_exists($penilaianBayi, 'APGAR_JAM')
            ) {

                $value = $this->soapValue(
                    $penilaianBayi->APGAR_JAM
                );

                if ($value !== null) {
                    $o[] = 'APGAR Jam: ' . $value;
                }
            }

            if (
                property_exists($penilaianBayi, 'APGAR_BB_SEKARANG')
            ) {

                $value = $this->soapValue(
                    $penilaianBayi->APGAR_BB_SEKARANG
                );

                if ($value !== null) {
                    $o[] = 'APGAR BB Sekarang: ' . $value . ' gram';
                }
            }
        }

        $o[] = '';

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

        if ($tandaVital) {
            $o[] = 'Tanda Vital';
            $o[] = $this->soapLine(
                'Keadaan Umum',
                $tandaVital->KEADAAN_UMUM
            );

            // if (
            //     $tandaVital->SISTOLIK !== null ||
            //     $tandaVital->DISTOLIK !== null
            // ) {
            //     $o[] = 'TD: ' .
            //         ($tandaVital->SISTOLIK ?? '-') .
            //         '/' .
            //         ($tandaVital->DISTOLIK ?? '-') .
            //         ' mmHg';
            // }

            if ($tandaVital->FREKUENSI_NADI !== null) {
                $o[] = 'Nadi: ' .
                    round($tandaVital->FREKUENSI_NADI) .
                    ' x/menit';
            }

            if ($tandaVital->FREKUENSI_NADI_CB !== null) {
                $nadi = [
                    1 => 'Reguler',
                    2 => 'Irreguler',
                ];

                $value = $tandaVital->FREKUENSI_NADI_CB;

                $o[] = 'Nadi Catatan: ' . ($nadi[$value] ?? $value);
            }

            if ($tandaVital->SUHU !== null) {
                $o[] = 'Suhu: ' .
                    $tandaVital->SUHU .
                    ' °C';
            }

            if ($tandaVital->SATURASI_O2 !== null) {
                $o[] = 'SpO2: ' .
                    round($tandaVital->SATURASI_O2) .
                    ' %';
            }

            if ($tandaVital->FREKUENSI_NAFAS !== null) {
                $o[] = 'RR: ' .
                    round($tandaVital->FREKUENSI_NAFAS) .
                    ' x/menit';
            }

            if ($tandaVital->FREKUENSI_NAFAS_CB !== null) {
                $nafas = [
                    1 => 'Simetris',
                    2 => 'Asimetris',
                ];

                $value = $tandaVital->FREKUENSI_NAFAS_CB;

                $o[] = 'RR Catatan: ' . ($nafas[$value] ?? $value);
            }

            if ($tandaVital->KESADARAN_NEONATUS !== null) {

                $kesadaranNeonatus = [
                    1 => [
                        'label' => 'S1',
                        'mata' => 'Tertutup',
                        'menangis' => '-',
                        'gerak' => '-',
                    ],
                    2 => [
                        'label' => 'S2',
                        'mata' => 'Tertutup',
                        'menangis' => '-',
                        'gerak' => '+',
                    ],
                    3 => [
                        'label' => 'S3',
                        'mata' => 'Terbuka',
                        'menangis' => '-',
                        'gerak' => '-',
                    ],
                    4 => [
                        'label' => 'S4',
                        'mata' => 'Terbuka',
                        'menangis' => '-',
                        'gerak' => '+',
                    ],
                    5 => [
                        'label' => 'S5',
                        'mata' => 'Terbuka',
                        'menangis' => '+',
                        'gerak' => '+',
                    ],
                ];

                $nilaiKesadaran = $tandaVital->KESADARAN_NEONATUS;

                if (isset($kesadaranNeonatus[$nilaiKesadaran])) {

                    $data = $kesadaranNeonatus[$nilaiKesadaran];

                    $o[] = 'Kesadaran Neonatus: ' . $data['label'];
                    $o[] = 'Mata: ' . $data['mata'];
                    $o[] = 'Menangis: ' . $data['menangis'];
                    $o[] = 'Gerak: ' . $data['gerak'];

                } else {

                    $o[] = 'Kesadaran Neonatus: ' . $nilaiKesadaran;

                }
            }

        }

        if ($nutrisi) {

            if ($nutrisi->BERAT_BADAN !== null) {
                $o[] = 'BB: ' .
                    round((float) $nutrisi->BERAT_BADAN) .
                    ' kg';
            }

            if ($nutrisi->TINGGI_BADAN !== null) {
                $o[] = 'PB: ' .
                    round((float) $nutrisi->TINGGI_BADAN) .
                    ' cm';
            }

            // if ($nutrisi->INDEX_MASSA_TUBUH !== null) {
            //     $o[] = 'IMT: ' .
            //         $nutrisi->INDEX_MASSA_TUBUH;
            // }
        }

        $o[] = '';

        $fisikNeo = DB::table(
            'medicalrecord.sirmed_pemeriksaan_fisik_neonatus'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first();

        if ($fisikNeo) {
            $o[] = 'Pemeriksaan Fisik';
            $skipFields = [
                'ID',
                'KUNJUNGAN',
                'TANGGAL',
                'OLEH',
                'STATUS',

                // Field yang akan kita format khusus
                'ROOTING',
                'SUCKING',
                'MORO',
                'ASYMMETRIC_TONIC_NECK',
                'BABINSKI',
                'MENGGENGGAM',
                'SUARA_DIAM',
                'SUARA_MERINTIH',
                'SUARA_KUAT',
                'IKRENIK',
                'KULIT_KETERANGAN',
            ];

            // ==========================================
            // PEMERIKSAAN FISIK UMUM
            // ==========================================
            foreach ((array) $fisikNeo as $field => $value) {

                if (in_array($field, $skipFields)) {
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

            // ==========================================
            // NEUROLOGI
            // ==========================================
            $o[] = 'Neurologi';

            $neurologi = [
                'ROOTING' => 'Rooting',
                'SUCKING' => 'Sucking',
                'MORO' => 'Moro',
                'ASYMMETRIC_TONIC_NECK' => 'Asymmetric Tonic Neck',
                'BABINSKI' => 'Babinski',
                'MENGGENGGAM' => 'Menggenggam',
            ];

            foreach ($neurologi as $field => $label) {

                $value = (int) ($fisikNeo->$field ?? 0);

                $o[] = $label . ': ' . ($value === 1 ? 'Ya' : 'Tidak');
            }

            // ==========================================
            // KULIT
            // ==========================================
            $o[] = 'Kulit';

            // Suara: ambil yang nilainya 1
            $suara = null;

            if ((int) ($fisikNeo->SUARA_DIAM ?? 0) === 1) {
                $suara = 'Suara Diam';
            } elseif ((int) ($fisikNeo->SUARA_MERINTIH ?? 0) === 1) {
                $suara = 'Suara Merintih';
            } elseif ((int) ($fisikNeo->SUARA_KUAT ?? 0) === 1) {
                $suara = 'Suara Kuat';
            }

            if ($suara !== null) {
                $o[] = 'Suara: ' . $suara;
            }

            // Ikrenik
            $ikrenik = (int) ($fisikNeo->IKRENIK ?? 0);

            $o[] = 'Ikrenik: ' . ($ikrenik === 1 ? 'Ya' : 'Tidak');

            // Keterangan kulit
            $kulitKeterangan = $this->soapValue(
                $fisikNeo->KULIT_KETERANGAN ?? null
            );

            if ($kulitKeterangan !== null) {
                $o[] = 'Keterangan: ' . $kulitKeterangan;
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
            'INSTRUKSI' => '-',
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

        $o = ['-'];

        $tandaVital = DB::table('medicalrecord.tanda_vital')
            ->where('KUNJUNGAN', $kunjungan)
            ->whereIn('PPA', [1, 2])
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
        | PEMERIKSAAN FISIK OBSGYN
        |--------------------------------------------------------------------------
        */

        $fisikObs = DB::table(
            'medicalrecord.sirmed_pemeriksaan_obsgyn'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first();

        if ($fisikObs) {

            foreach ((array) $fisikObs as $field => $value) {

                if (in_array($field, [
                    'ID',
                    'KUNJUNGAN',
                    'PENDAFTARAN',
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
            'INSTRUKSI' => '-',
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

    private function soapNumber($value)
    {
        if ($value === null || $value === '') {
            return '';
        }

        $number = (float) $value;

        return fmod($number, 1) === 0.0
            ? (string) (int) $number
            : (string) $number;
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
