<?php

namespace App\Http\Controllers\EMR\Form\GawatDarurat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpWord\TemplateProcessor;
use App\Services\LibreOfficeService;
use PHPJasper\PHPJasper;
use Carbon\Carbon;
use Auth, Storage;

class PengkajianGawatDaruratController extends Controller
{
    function index($kunjungan)
    {
        $tingkat_kesadaran = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',179)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $riw_alergi = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',180)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $usia = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',192)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $jk = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',193)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $cara_keluar = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',45)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $keadaan_keluar = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',46)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $frekuensi_obat = DB::table('master.frekuensi_aturan_resep')
                ->select('ID','FREKUENSI')
                ->where('STATUS',1)
                ->orderBy('ID','ASC')
                ->get();

        $rute_obat = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',217)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $data = [
            'kunjungan' => $kunjungan,
            'tingkat_kesadaran' => $tingkat_kesadaran,
            'riwayat_alergi' => $riw_alergi,
            'cara_keluar' => $cara_keluar,
            'usia' => $usia,
            'jk' => $jk,
            'keadaan_keluar' => $keadaan_keluar,
            'frekuensi_obat' => $frekuensi_obat,
            'rute_obat' => $rute_obat
        ];

        return view('pages.v2.medicalrecord.detail.form.pengkajian.gawat-darurat.index')->with('list',$data);
    }

    // FORM DOKTER
        public function getFormDokter($KUNJUNGAN)
        {
            /*
            |--------------------------------------------------------------------------
            | DATA TRIAGE
            |--------------------------------------------------------------------------
            */
            $triage = DB::table('medicalrecord.triage')
            ->where('KUNJUNGAN', $KUNJUNGAN)
            ->whereIn('STATUS', [1, 2])
            ->orderByDesc('ID')
            ->first();

            /*
            |--------------------------------------------------------------------------
            | DECODE JSON FIELD TRIAGE
            |--------------------------------------------------------------------------
            */
            if ($triage) {

                $jsonFields = [
                    'KEDATANGAN',
                    'KASUS',
                    'ANAMNESE',
                    'TANDA_VITAL',
                    'OBGYN',
                    'KEBUTUHAN_KHUSUS',
                    'RESUSITASI',
                    'EMERGENCY',
                    'URGENT',
                    'LESS_URGENT',
                    'NON_URGENT',
                    'DOA',
                ];

                foreach ($jsonFields as $field) {
                    if (
                        isset($triage->$field) &&
                        $triage->$field !== null &&
                        $triage->$field !== ''
                    ) {
                        $triage->$field = json_decode(
                            $triage->$field,
                            true
                        );
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | ANAMNESIS DIPEROLEH
            |--------------------------------------------------------------------------
            */
            $anamnesisDiperoleh = DB::table(
                'medicalrecord.anamnesis_diperoleh'
            )
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->where('STATUS', 1)
                ->orderByDesc('ID')
                ->first();

            /*
            |--------------------------------------------------------------------------
            | TANDA VITAL / PRIMARY SURVEY
            |--------------------------------------------------------------------------
            */
            $tandaVital = DB::table(
                'medicalrecord.tanda_vital'
            )
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->where('STATUS', 1)
                ->orderByDesc('ID')
                ->first();

            /*
            |--------------------------------------------------------------------------
            | STATUS REPRODUKSI
            |--------------------------------------------------------------------------
            */
            $statusReproduksi = DB::table(
                'medicalrecord.status_reproduksi'
            )
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->where('STATUS', 1)
                ->orderByDesc('ID')
                ->first();

            /*
            |--------------------------------------------------------------------------
            | KELUHAN UTAMA
            |--------------------------------------------------------------------------
            */
            $keluhanUtama = DB::table(
                'medicalrecord.keluhan_utama'
            )
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->where('STATUS', 1)
                ->orderByDesc('ID')
                ->first();

            /*
            |--------------------------------------------------------------------------
            | RIWAYAT PENYAKIT SEKARANG / ANAMNESIS
            |--------------------------------------------------------------------------
            */
            $anamnesis = DB::table(
                'medicalrecord.anamnesis'
            )
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->where('STATUS', 1)
                ->orderByDesc('ID')
                ->first();

            /*
            |--------------------------------------------------------------------------
            | RIWAYAT PENYAKIT DAHULU
            |--------------------------------------------------------------------------
            */
            $rpp = DB::table(
                'medicalrecord.rpp'
            )
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->where('STATUS', 1)
                ->orderByDesc('ID')
                ->first();

            /*
            |--------------------------------------------------------------------------
            | PEMERIKSAAN FISIK
            |--------------------------------------------------------------------------
            */
            $pemeriksaanFisik = DB::table(
                'medicalrecord.pemeriksaan_fisik'
            )
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->where('STATUS', 1)
                ->orderByDesc('ID')
                ->first();

            /*
            |--------------------------------------------------------------------------
            | PERENCANAAN TERAPI
            |--------------------------------------------------------------------------
            */
            $perencanaanTerapi = DB::table(
                'medicalrecord.rencana_terapi'
            )
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->where('STATUS', 1)
                ->orderByDesc('ID')
                ->first();

            /*
            |--------------------------------------------------------------------------
            | HASIL LAPOR DPJP
            |--------------------------------------------------------------------------
            */

            /*
            |--------------------------------------------------------------------------
            | TINDAK LANJUT ASUHAN
            |--------------------------------------------------------------------------
            */
            $pasienPulang = DB::table(
                'layanan.pasien_pulang'
            )
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->where('STATUS', 1)
                ->orderByDesc('ID')
                ->first();

            /*
            |--------------------------------------------------------------------------
            | RESPONSE
            |--------------------------------------------------------------------------
            */
            return response()->json([
                'status' => true,
                'data' => [
                    // TRIAGE
                    'triage' => $triage,

                    // ANAMNESIS DIPEROLEH
                    'anamnesis_diperoleh' => $anamnesisDiperoleh,

                    // PRIMARY SURVEY
                    'tanda_vital' => $tandaVital,
                    'status_reproduksi' => $statusReproduksi,

                    // SECONDARY SURVEY
                    'keluhan_utama' => $keluhanUtama,
                    'anamnesis' => $anamnesis,
                    'rpp' => $rpp,
                    'pemeriksaan_fisik' => $pemeriksaanFisik,

                    'perencanaan_terapi' => $perencanaanTerapi,
                    'tindak_lanjut_asuhan' => $pasienPulang,
                ],
            ]);
        }

        public function simpanFormDokter(Request $request)
        {
            $validator = Validator::make(
                $request->all(),
                [
                    'NOKUNJ' => 'required',
                    'ats_p'  => 'required|in:1,2,3,4,5',
                ],
                [
                    'NOKUNJ.required' => 'Kunjungan wajib diisi.',
                    'ats_p.required'  => 'Kategori ATS wajib dipilih.',
                    'ats_p.in'        => 'Kategori ATS tidak valid.',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            DB::beginTransaction();

            try {

                // ==========================================
                // DATA DOKTER
                // ==========================================
                $getDataDokter = DB::table('master.dokter as dr')
                    ->leftJoin('aplikasi.pengguna as pe', function ($join) {
                        $join->on('pe.NIP', '=', 'dr.NIP')
                            ->where('pe.STATUS', '=', 1);
                    })
                    ->select('dr.ID')
                    ->where('pe.ID', auth()->id())
                    ->where('dr.STATUS', 1)
                    ->first();


                // ==========================================
                // DATA KUNJUNGAN
                // ==========================================
                $getDataKunjungan = DB::table('pendaftaran.kunjungan as pk')
                    ->join(
                        'pendaftaran.pendaftaran as pp',
                        'pp.NOMOR',
                        '=',
                        'pk.NOPEN'
                    )
                    ->select(
                        'pp.NORM',
                        'pp.NOMOR as NOPEN'
                    )
                    ->where('pk.NOMOR', $request->NOKUNJ)
                    ->first();

                if (!$getDataKunjungan) {
                    DB::rollBack();

                    return response()->json([
                        'status'  => false,
                        'message' => 'Data kunjungan tidak ditemukan.'
                    ], 404);
                }


                // ==========================================
                // NILAI ATS
                // ==========================================
                $atsP = (int) $request->input('ats_p');


                // ==========================================
                // RESUSITASI (P1)
                // ==========================================
                $resusitasi = [
                    'CHECKED' => $atsP === 1 ? 1 : 0,

                    'KESADARAN' => 'Tidak Sadar',

                    'SIRKULASI' => [
                        'NADI_TIDAK_TERABA' =>
                            $request->boolean('ats_sr_1') ? 1 : 0,
                    ],

                    'PERNAPASAN' => [
                        'HENTI_NAFAS' =>
                            $request->boolean('ats_pf_1_1') ? 1 : 0,

                        'GASPING_DIBAWAH_12_X_PER_MENIT' =>
                            $request->boolean('ats_pf_1_3') ? 1 : 0,

                        'NAPAS_TIDAK_ADEKUAT_DIATAS_40_X_PER_MENIT' =>
                            $request->boolean('ats_pf_1_2') ? 1 : 0,
                    ],

                    'JALAN_NAPAS' => [
                        'SUMBATAN_JALAN_NAPAS_TOTAL' =>
                            $request->boolean('ats_jn_1') ? 1 : 0,
                    ],
                ];


                // ==========================================
                // EMERGENCY (P2)
                // ==========================================
                $emergency = [
                    'CHECKED' => $atsP === 2 ? 1 : 0,

                    'KESADARAN' => 'Tidak Sadar',

                    'SIRKULASI' => [
                        'PUCAT' =>
                            $request->boolean('ats_sr_2_6') ? 1 : 0,

                        'SIANOTIK' =>
                            $request->boolean('ats_sr_2_5') ? 1 : 0,

                        'NYERI_BERAT' =>
                            $request->boolean('ats_sr_2_2') ? 1 : 0,

                        'AKRAL_DINGIN' =>
                            $request->boolean('ats_sr_2_7') ? 1 : 0,

                        'KERINGAT_DINGIN' =>
                            $request->boolean('ats_sr_2_8') ? 1 : 0,

                        'NADI_SANGAT_LEMAH' =>
                            $request->boolean('ats_sr_2_1') ? 1 : 0,

                        'SPO2_DIBAWAH_90_PERSEN' =>
                            $request->boolean('ats_sr_2_10') ? 1 : 0,

                        'IRAMA_NADI_TIDAK_TERATUR' =>
                            $request->boolean('ats_sr_2_3') ? 1 : 0,

                        'TDS_DIBAWAH_80_ATAU_DIATAS_180_MMHG' =>
                            $request->boolean('ats_sr_2_9') ? 1 : 0,

                        'NADI_DIBAWAH_50_ATAU_DIATAS_150_X_PER_MENIT' =>
                            $request->boolean('ats_sr_2_4') ? 1 : 0,
                    ],

                    'PERNAPASAN' => [
                        'RONCHI' =>
                            $request->boolean('ats_pf_2_4') ? 1 : 0,

                        'GURGLING' =>
                            $request->boolean('ats_pf_2_5') ? 1 : 0,

                        'WHEEZING' =>
                            $request->boolean('ats_pf_2_3') ? 1 : 0,

                        'DISTRESS_PERNAPASAN' =>
                            $request->boolean('ats_pf_2_1') ? 1 : 0,

                        'FREKUENSI_PERNAPASAN_24_SAMPAI_31_X_PER_MENIT' =>
                            $request->boolean('ats_pf_2_2') ? 1 : 0,
                    ],

                    'JALAN_NAPAS' => [
                        'SUMBATAN_JALAN_NAPAS_PARSIAL' =>
                            $request->boolean('ats_jn_2') ? 1 : 0,
                    ],
                ];


                // ==========================================
                // URGENT (P3)
                // ==========================================
                $urgent = [
                    'CHECKED' => $atsP === 3 ? 1 : 0,

                    'KESADARAN' => 'Sadar',

                    'SIRKULASI' => [
                        'NYERI_SEDANG' =>
                            $request->boolean('ats_sr_3_2') ? 1 : 0,

                        'NADI_TERABA_LEMAH' =>
                            $request->boolean('ats_sr_3_1') ? 1 : 0,

                        'WARNA_KULIT_NORMAL' =>
                            $request->boolean('ats_sr_3_5') ? 1 : 0,

                        'SPO2_DIATAS_95_PERSEN' =>
                            $request->boolean('ats_sr_3_7') ? 1 : 0,

                        'IRAMA_NADI_TIDAK_TERATUR' =>
                            $request->boolean('ats_sr_3_3') ? 1 : 0,

                        'TDS_80_SAMPAI_100_ATAU_150_SAMPAI_180_MMHG' =>
                            $request->boolean('ats_sr_3_6') ? 1 : 0,

                        'NADI_50_SAMPAI_59_ATAU_101_SAMPAI_150_X_PER_MENIT' =>
                            $request->boolean('ats_sr_3_4') ? 1 : 0,
                    ],

                    'PERNAPASAN' => [
                        'RONCHI' =>
                            $request->boolean('ats_pf_3_4') ? 1 : 0,

                        'WHEEZING' =>
                            $request->boolean('ats_pf_3_3') ? 1 : 0,

                        'RETRAKSI_ATAU_NAPAS_CUPING_HIDUNG' =>
                            $request->boolean('ats_pf_3_1') ? 1 : 0,

                        'FREKUENSI_PERNAPASAN_24_SAMPAI_31_X_PER_MENIT' =>
                            $request->boolean('ats_pf_3_2') ? 1 : 0,
                    ],

                    'JALAN_NAPAS' => [
                        'JALAN_NAPAS_BEBAS' =>
                            $request->boolean('ats_jn_3_1') ? 1 : 0,

                        'CORPUS_ALLIENUM_TANDA2_GANGUAN_NAPAS' =>
                            $request->boolean('ats_jn_3_2') ? 1 : 0,
                    ],
                ];


                // ==========================================
                // LESS URGENT (P4)
                // ==========================================
                $lessUrgent = [
                    'CHECKED' => $atsP === 4 ? 1 : 0,

                    'KESADARAN' => 'Sadar',

                    'SIRKULASI' => [
                        'AKRAL_HANGAT' =>
                            $request->boolean('ats_sr_4_5') ? 1 : 0,

                        'NYERI_RINGAN' =>
                            $request->boolean('ats_sr_4_2') ? 1 : 0,

                        'NADI_TERABA_KUAT' =>
                            $request->boolean('ats_sr_4_1') ? 1 : 0,

                        'IRAMA_NADI_TERATUR' =>
                            $request->boolean('ats_sr_4_3') ? 1 : 0,

                        'SPO2_DIATAS_95_PERSEN' =>
                            $request->boolean('ats_sr_4_7') ? 1 : 0,

                        'NADI_60_SAMPAI_100_X_PER_MENIT' =>
                            $request->boolean('ats_sr_4_4') ? 1 : 0,

                        'TDS_DIATAS_100_ATAU_DIBAWAH_150_MMHG' =>
                            $request->boolean('ats_sr_4_6') ? 1 : 0,
                    ],

                    'PERNAPASAN' => [
                        'RETRAKSI_ATAU_NAPAS_CUPING_HIDUNG' =>
                            $request->boolean('ats_pf_4_1') ? 1 : 0,

                        'FREKUENSI_PERNAPASAN_21_SAMPAI_23_X_PER_MENIT' =>
                            $request->boolean('ats_pf_4_2') ? 1 : 0,
                    ],

                    'JALAN_NAPAS' => [
                        'JALAN_NAPAS_BEBAS' =>
                            $request->boolean('ats_jn_4') ? 1 : 0,
                    ],
                ];


                // ==========================================
                // NON URGENT (P5)
                // ==========================================
                $nonUrgent = [
                    /*
                    * KHUSUS:
                    * ats_p = 4 membuat P4 dan P5 CHECKED = 1
                    *
                    * ats_p = 5 hanya DOA yang CHECKED = 1,
                    * sehingga NON_URGENT = 0.
                    */
                    'CHECKED' => $atsP === 4 ? 1 : 0,

                    'KESADARAN' => 'Sadar',

                    'SIRKULASI' => [
                        'AKRAL_HANGAT' =>
                            $request->boolean('ats_sr_5_5') ? 1 : 0,

                        'TIDAK_ADA_NYERI' =>
                            $request->boolean('ats_sr_5_2') ? 1 : 0,

                        'NADI_TERABA_KUAT' =>
                            $request->boolean('ats_sr_5_1') ? 1 : 0,

                        'IRAMA_NADI_TERATUR' =>
                            $request->boolean('ats_sr_5_3') ? 1 : 0,

                        'SPO2_DIATAS_95_PERSEN' =>
                            $request->boolean('ats_sr_5_7') ? 1 : 0,

                        'TDS_100_KOMA_150_MMHG' =>
                            $request->boolean('ats_sr_5_6') ? 1 : 0,

                        'NADI_60_SAMPAI_100_X_PER_MENIT' =>
                            $request->boolean('ats_sr_5_4') ? 1 : 0,
                    ],

                    'PERNAPASAN' => [
                        'TIDAK_ADA_RETRAKSI' =>
                            $request->boolean('ats_pf_5_1') ? 1 : 0,

                        'FREKUENSI_PERNAPASAN_12_SAMPAI_20_X_PER_MENIT' =>
                            $request->boolean('ats_pf_5_2') ? 1 : 0,
                    ],

                    'JALAN_NAPAS' => [
                        'JALAN_NAPAS_BEBAS' =>
                            $request->boolean('ats_jn_5') ? 1 : 0,
                    ],
                ];


                // ==========================================
                // DOA (P5)
                // ==========================================
                $doa = [
                    'CHECKED' => $atsP === 5 ? 1 : 0,

                    'KESADARAN' => 'Pupil Midriasis Total Kaku Mayat',
                ];


                // ==========================================
                // RISIKO PENULARAN INFEKSI
                // ==========================================
                /*
                * Kolom database RISIKO_PENULARAN_INFEKSI
                * adalah INT, bukan JSON.
                */
                $risikoPenularanInfeksi = $request->input('rpi');


                // ==========================================
                // SIMPAN TRIAGE
                // ==========================================
                DB::table('medicalrecord.triage')->updateOrInsert(
                    [
                        'NORM'      => $getDataKunjungan->NORM,
                        'KUNJUNGAN' => $request->NOKUNJ,
                        'NOPEN'     => $getDataKunjungan->NOPEN,
                    ],
                    [
                        /*
                        * KATEGORI_PEMERIKSAAN bukan ats_p.
                        *
                        * Karena pada HTML yang Anda kirim tidak ada
                        * input khusus untuk kategori pemeriksaan,
                        * jangan isi dengan ats_p secara otomatis.
                        */
                        'KATEGORI_PEMERIKSAAN' => $request->input(
                            'kategori_pemeriksaan',
                            1
                        ),

                        'RESUSITASI' => json_encode(
                            $resusitasi,
                            JSON_UNESCAPED_UNICODE
                        ),

                        'EMERGENCY' => json_encode(
                            $emergency,
                            JSON_UNESCAPED_UNICODE
                        ),

                        'URGENT' => json_encode(
                            $urgent,
                            JSON_UNESCAPED_UNICODE
                        ),

                        'LESS_URGENT' => json_encode(
                            $lessUrgent,
                            JSON_UNESCAPED_UNICODE
                        ),

                        'NON_URGENT' => json_encode(
                            $nonUrgent,
                            JSON_UNESCAPED_UNICODE
                        ),

                        'DOA' => json_encode(
                            $doa,
                            JSON_UNESCAPED_UNICODE
                        ),

                        'RISIKO_PENULARAN_INFEKSI' =>
                            $risikoPenularanInfeksi !== null
                                ? (int) $risikoPenularanInfeksi
                                : null,

                        'KRITERIA' =>
                            $request->input('ats'),

                        'PLAN' =>
                            $atsP,

                        'DOKTER_ID' =>
                            $getDataDokter->ID ?? 0,

                        'OLEH' =>
                            auth()->id(),

                        'STATUS' =>
                            2,

                        'TANGGAL' =>
                            now(),
                    ]
                );

                // ==========================
                // ANAMNESIS DIPEROLEH
                // ==========================
                DB::table('medicalrecord.anamnesis_diperoleh')->updateOrInsert(
                    [
                        'KUNJUNGAN' => $request->NOKUNJ
                    ],
                    [
                        'AUTOANAMNESIS' => ($request->anam == 1) ? 1 : 0,
                        'ALLOANAMNESIS' => ($request->anam == 2) ? 1 : 0,
                        'DARI'          => "",
                        'OLEH'          => auth()->id(),
                        'STATUS'        => 1,
                        'TANGGAL'       => now()
                    ]
                );

                // ==========================
                // PRIMARY SURVEY - TTV
                // ==========================
                DB::table('medicalrecord.tanda_vital')->updateOrInsert(
                    [
                        'KUNJUNGAN' => $request->NOKUNJ
                    ],
                    [
                        'KEADAAN_UMUM'          => $request->keu,
                        'KESADARAN'             => "",
                        'SISTOLIK'              => $request->td_up,
                        'DISTOLIK'              => $request->td_down,
                        'FREKUENSI_NADI'        => $request->nadi,
                        'SUHU'                  => $request->suhu,
                        'SATURASI_O2'           => $request->spo2,
                        'TINGKAT_KESADARAN'     => $request->tks,
                        'FREKUENSI_NAFAS'       => $request->fr,
                        'FREKUENSI_NAFAS_CB'    => $request->fr_cb,
                        'PUPIL'                 => $request->pupil,
                        'DIAMETER_PUPIL_UP'     => $request->dia_up,
                        'DIAMETER_PUPIL_DOWN'   => $request->dia_down,
                        'RC_UP'                 => $request->rc_up,
                        'RC_DOWN'               => $request->rc_down,
                        'VAS'                   => $request->vas,
                        'EYE'                   => $request->gcs_e,
                        'MOTORIK'               => $request->gcs_v,
                        'VERBAL'                => $request->gcs_m,
                        'GCS'                   => $request->gcs_t,
                        'JALAN_NAFAS'           => $request->jn,
                        'ALAT_BANTU_NAFAS'      => $request->abn,
                        'KULIT'                 => $request->kulit,
                        'OLEH'                  => auth()->id(),
                        'STATUS'                => 1,
                        'TANGGAL'               => now()
                    ]
                );

                // ==========================
                // PRIMARY SURVEY - STATUS REPRODUKSI
                // ==========================
                DB::table('medicalrecord.status_reproduksi')->updateOrInsert(
                    [
                        'KUNJUNGAN' => $request->NOKUNJ
                    ],
                    [
                        'KASUS_OBSTETRI_GINEKOLOGI' => $request->sr,
                        'HPHT'                      => $request->sr_hpht,
                        'SIKLUS'                    => $request->sr_siklus,
                        'KB'                        => $request->sr_kb,
                        'STATUS_REPRODUKSI'         => $request->sr_hamil,
                        'HAMIL_GRAVIDA'             => $request->sr_grv,
                        'HAMIL_PARITAS'             => $request->sr_prt,
                        'HAMIL_ABORTUS'             => $request->sr_abr,
                        'OLEH'                      => auth()->id(),
                        'STATUS'                    => 1,
                        'TANGGAL'                   => now()
                    ]
                );

                // ==========================
                // SECONDARY SURVEY
                // ==========================
                    // KELUHAN UTAMA
                    DB::table('medicalrecord.keluhan_utama')->updateOrInsert(
                        [
                            'KUNJUNGAN'     => $request->NOKUNJ
                        ],
                        [
                            'DESKRIPSI'     => $request->ku,
                            'SNOMED_CT_ID'  => 0,
                            'TANGGAL'       => now(),
                            'OLEH'          => auth()->id(),
                            'STATUS'        => 1,
                        ]
                    );
                    // RIWAYAT PENYAKIT SEKARANG (TABEL ANAMNESIS)
                    DB::table('medicalrecord.anamnesis')->updateOrInsert(
                        [
                            'KUNJUNGAN'     => $request->NOKUNJ,
                            'PENDAFTARAN'     => $getDataKunjungan->NOPEN
                        ],
                        [
                            'SNOMED_CT_ID'  => 0,
                            'DESKRIPSI'     => $request->rps,
                            'TANGGAL'       => now(),
                            'OLEH'          => auth()->id(),
                            'STATUS'        => 1,
                        ]
                    );
                    // RIWAYAT PENYAKIT DAHULU (TABEL RPP) - update all field
                    DB::table('medicalrecord.rpp')->updateOrInsert(
                        [
                            'KUNJUNGAN'     => $request->NOKUNJ
                        ],
                        [
                            'SNOMED_CT_ID'  => 0,
                            'DESKRIPSI'     => $request->rpd,
                            'TANGGAL'       => now(),
                            'OLEH'          => auth()->id(),
                            'STATUS'        => 1,
                        ]
                    );
                    // PEMERIKSAAN FISIK (TABEL PEMERIKSAAN_FISIK)
                    DB::table('medicalrecord.pemeriksaan_fisik')->updateOrInsert(
                        [
                            'KUNJUNGAN'     => $request->NOKUNJ,
                            'PENDAFTARAN'   => $getDataKunjungan->NOPEN
                        ],
                        [
                            'DESKRIPSI'     => $request->pf,
                            'TANGGAL'       => now(),
                            'OLEH'          => auth()->id(),
                            'STATUS'        => 1,
                        ]
                    );
                    // PERENCANAAN TERAPI (TABEL RENCANA_TERAPI)
                    DB::table('medicalrecord.rencana_terapi')->updateOrInsert(
                        [
                            'KUNJUNGAN'     => $request->NOKUNJ,
                        ],
                        [
                            'DESKRIPSI'     => $request->pt,
                            'TANGGAL'       => now(),
                            'OLEH'          => auth()->id(),
                            'STATUS'        => 1,
                        ]
                    );
                    // HASIL LAPOR DPJP ( )
                    // DB::table('medicalrecord.')->updateOrInsert(
                    //     [
                    //         'KUNJUNGAN'     => $request->NOKUNJ,
                    //     ],
                    //     [
                    //         'DESKRIPSI'     => $request->pt,
                    //         'TANGGAL'       => now(),
                    //         'OLEH'          => auth()->id(),
                    //         'STATUS'        => 1,
                    //     ]
                    // );
                    // TINDAK LANJUT ASUHAN (PASIEN_PULANG)
                    DB::table('layanan.pasien_pulang')->updateOrInsert(
                        [
                            'KUNJUNGAN'     => $request->NOKUNJ,
                            'NOPEN'         => $getDataKunjungan->NOPEN
                        ],
                        [
                            'CARA'          => $request->tla_ck,
                            'KEADAAN'       => $request->tla_kk,
                            'DIAGNOSA'      => "",
                            'TANGGAL'       => now(),
                            'DOKTER'        => $getDataDokter->ID ?? 0,
                            'OLEH'          => auth()->id(),
                            'STATUS'        => 1,
                        ]
                    );

                // ==========================================
                // COMMIT
                // ==========================================
                DB::commit();

                return response()->json([
                    'status'  => true,
                    'message' => 'Pengkajian dokter berhasil disimpan.'
                ], 200);

            } catch (\Throwable $e) {

                DB::rollBack();

                return response()->json([
                    'status'  => false,
                    'message' => 'Data pengkajian gagal disimpan.',
                    'error'   => $e->getMessage(),
                ], 500);
            }
        }

    // FORM PERAWAT
    function getFormPerawat()
    {
        $triage = DB::table('medicalrecord.triage')
                    ->select(
                        'KRITERIA',
                        'RISIKO_PENULARAN_INFEKSI'
                    )
                    ->where('KUNJUNGAN', $KUNJUNGAN)
                    ->whereIn('STATUS', [1,2])
                    ->first();

        $anamnesis_diperoleh = DB::table('medicalrecord.anamnesis_diperoleh')
                                ->where('KUNJUNGAN', $KUNJUNGAN)
                                ->first();

        $data = [
            'triage' => $triage,
            'anamnesis_diperoleh' => $anamnesis_diperoleh
        ];

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    function simpanFormPerawat(Request $request)
    {
        print_r($request->all());
        die();
    }
}
