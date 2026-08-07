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
        public function getFormDokterGd($KUNJUNGAN)
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
            $hasilLaporDPJP = DB::table(
                'medicalrecord.hasil_lapor_dpjp'
            )
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->where('STATUS', 1)
                ->orderByDesc('ID')
                ->first();

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
                    'hasil_lapor_dpjp' => $hasilLaporDPJP,
                    'tindak_lanjut_asuhan' => $pasienPulang,
                ],
            ]);
        }

        public function simpanFormDokterGd(Request $request)
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
                    // HASIL LAPOR DPJP (TABEL MEDICALRECORD HASIL_LAPOR_DPJP)
                    DB::table('medicalrecord.hasil_lapor_dpjp')->updateOrInsert(
                        [
                            'KUNJUNGAN'     => $request->NOKUNJ,
                        ],
                        [
                            'DESKRIPSI'     => $request->hld,
                            'TANGGAL'       => now(),
                            'OLEH'          => auth()->id(),
                            'STATUS'        => 1,
                        ]
                    );
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

                // ========================================== PUSH CPPT !!!!!!!!!!!!!!!!!!!!!!!!
                // ==========================================
                // DIAGNOSA
                // ==========================================
                $getDiagnosa = DB::table('medicalrecord.diagnosa as diag')
                    ->select(
                        'diag.ID',
                        'diag.DIAGNOSA',
                        DB::raw("CASE WHEN diag.UTAMA = 1 THEN 'UTAMA' ELSE 'SEKUNDER' END AS UTAMA")
                    )
                    ->where('diag.NOPEN', $getDataKunjungan->NOPEN)
                    ->where('diag.STATUS', 1)
                    ->get();

                // ==========================================
                // SUBYEKTIF
                // ==========================================
                $cppt_s = "<div style='color:#9CC96B'>Riwayat Penyakit Sekarang:</div>" . ($request->rps ?? '');

                // ==========================================
                // OBYEKTIF / TTV
                // ==========================================
                $cppt_o = "<div style='color:#9CC96B'>Pemeriksan Umum / Tanda Vital:</div>" . implode("\n", array_filter([
                    "Keadaan Umum: " . ($request->keu ?? ''),
                    "Sistolik: " . ($request->td_up ?? ''),
                    "Diastolik: " . ($request->td_down ?? ''),
                    "Frekuensi Nadi: " . ($request->nadi ?? ''),
                    "Suhu: " . ($request->suhu ?? ''),
                    "Saturasi O2: " . ($request->spo2 ?? ''),
                    "Tingkat Kesadaran: " . ($request->tks ?? ''),
                    "Frekuensi Nafas: " . ($request->fr ?? ''),
                    "Frekuensi Nafas CB: " . ($request->fr_cb ?? ''),
                    "Pupil: " . ($request->pupil ?? ''),
                    "Diameter Pupil Kanan: " . ($request->dia_up ?? ''),
                    "Diameter Pupil Kiri: " . ($request->dia_down ?? ''),
                    "Refleks Cahaya Kanan: " . ($request->rc_up ?? ''),
                    "Refleks Cahaya Kiri: " . ($request->rc_down ?? ''),
                    "VAS: " . ($request->vas ?? ''),
                    "GCS Eye: " . ($request->gcs_e ?? ''),
                    "GCS Motorik: " . ($request->gcs_v ?? ''),
                    "GCS Verbal: " . ($request->gcs_m ?? ''),
                    "GCS Total: " . ($request->gcs_t ?? ''),
                    "Jalan Nafas: " . ($request->jn ?? ''),
                    "Alat Bantu Nafas: " . ($request->abn ?? ''),
                    "Kulit: " . ($request->kulit ?? ''),
                ], function ($value) {
                    // Hanya tampilkan field yang memiliki nilai
                    return trim(substr($value, strpos($value, ':') + 1)) !== '';
                }));

                // ==========================================
                // ASSESMENT / DIAGNOSA
                // ==========================================
                $diagnosa = $getDiagnosa->map(function ($item) {
                    return $item->DIAGNOSA . ' (' . $item->UTAMA . ')';
                })->implode("\n");

                $cppt_a = "<div style='color:#9CC96B'>Diagnosa Dokter:</div>" . $diagnosa;

                // ==========================================
                // PLANNING
                // ==========================================
                $cppt_p = implode("\n", array_filter([
                    "<div style='color:#9CC96B'>Perencanaan Terapi:</div>" . ($request->pt ?? ''),
                    "\n<div style='color:#9CC96B'>Hasil Lapor DPJP:</div>" . ($request->hld ?? ''),
                ], function ($value) {
                    return trim(str_replace(
                        ['Perencanaan Terapi:', 'Hasil Lapor DPJP:'],
                        '',
                        $value
                    )) !== '';
                }));

                $cppt_i = "-";

                // ==========================================
                // DATA DASAR CPPT
                // ==========================================
                $dataCppt = [
                    'KUNJUNGAN'    => $request->NOKUNJ,
                    'TANGGAL'      => now(),
                    'SUBYEKTIF'    => $cppt_s,
                    'OBYEKTIF'     => $cppt_o,
                    'ASSESMENT'    => $cppt_a,
                    'PLANNING'     => $cppt_p,
                    'INSTRUKSI'    => $cppt_i,
                    'JENIS'        => 1,
                    'TENAGA_MEDIS' => $getDataDokter->ID ?? 0,
                    'OLEH'         => auth()->id(),
                    'STATUS'       => 1,
                ];

                // ==========================================
                // CEK PUSH CPPT
                // ==========================================
                $pushCppt = DB::table('medicalrecord.push_cppt')
                    ->where('KUNJUNGAN', $request->NOKUNJ)
                    ->where('FORM', 'pengkajian-gd')
                    ->first();

                if ($pushCppt) {

                    // ==========================================
                    // DATA SUDAH ADA
                    // ==========================================

                    // Update CPPT yang sudah terhubung
                    DB::table('medicalrecord.cppt')
                        ->where('ID', $pushCppt->ID_CPPT)
                        ->update($dataCppt);

                    // Update push_cppt
                    DB::table('medicalrecord.push_cppt')
                        ->where('ID_CPPT', $pushCppt->ID_CPPT)
                        ->update([
                            'SUBYEKTIF' => $cppt_s,
                            'OBYEKTIF'  => $cppt_o,
                            'ASSESMENT' => $cppt_a,
                            'PLANNING'  => $cppt_p,
                            'INSTRUKSI' => $cppt_i,
                            'TANGGAL'   => now(),
                            'OLEH'      => auth()->id(),
                            'STATUS'    => 1,
                        ]);

                } else {

                    // ==========================================
                    // DATA BELUM ADA
                    // ==========================================

                    // Buat CPPT baru
                    $ID_CPPT = DB::table('medicalrecord.cppt')->insertGetId($dataCppt);

                    // Buat push_cppt
                    DB::table('medicalrecord.push_cppt')
                        ->insert([
                            'ID_CPPT'    => $ID_CPPT,
                            'KUNJUNGAN'  => $request->NOKUNJ,
                            'FORM'       => 'pengkajian-gd',
                            'SUBYEKTIF'  => $cppt_s,
                            'OBYEKTIF'   => $cppt_o,
                            'ASSESMENT'  => $cppt_a,
                            'PLANNING'   => $cppt_p,
                            'INSTRUKSI'  => $cppt_i,
                            'TANGGAL'    => now(),
                            'OLEH'       => auth()->id(),
                            'STATUS'     => 1,
                        ]);
                }

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
    function getFormPerawatGd()
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

    public function simpanFormPerawatGd(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'NOKUNJ' => 'required',
            ],
            [
                'NOKUNJ.required' => 'Kunjungan wajib diisi.',
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
            // CARA KEDATANGAN
            // ==========================================
            $jenisKedatangan = (int) $request->input('dd_ck', 0);

            $kedatangan = [
                'JENIS'             => $jenisKedatangan,
                'TANGGAL'           => $request->input('tgl_ck'),
                'PENGANTAR'         => '',
                'KEPOLISIAN'        => '',
                'ASAL_RUJUKAN'      => '',
                'ALAT_TRANSPORTASI' => $request->input('tr_ck'),
            ];

            // Datang sendiri
            if ($jenisKedatangan === 1) {

                $kedatangan['PENGANTAR'] = $request->input('dd_ck_p');

            }

            // Rujukan dari
            elseif ($jenisKedatangan === 2) {

                $kedatangan['ASAL_RUJUKAN'] = $request->input('dd_ck_k');

            }

            // Dikirim oleh polisi
            elseif ($jenisKedatangan === 3) {

                $kedatangan['VISUM'] = $request->boolean('dd_ck_a_v') ? 1 : 0;
                $kedatangan['KEPOLISIAN'] = $request->input('dd_ck_a');

            }


            // ==========================================
            // JENIS KASUS
            // ==========================================
            $jenisKasus = $request->input('jks');

            $kasus = [
                'JENIS'            => $jenisKasus !== null && $jenisKasus !== ''
                                        ? (int) $jenisKasus
                                        : null,

                'LAKA_LANTAS'      => 0,
                'KECELAKAAN_KERJA' => 0,
                'UPPA'             => 0,
                'DIMANA'           => '',
            ];

            // Trauma
            if ((int) $jenisKasus === 1) {

                $kasus['LAKA_LANTAS'] =
                    $request->boolean('jks_kll') ? 1 : 0;

                $kasus['KECELAKAAN_KERJA'] =
                    $request->boolean('jks_kk') ? 1 : 0;

                $kasus['UPPA'] =
                    $request->boolean('jks_uppa') ? 1 : 0;

            }

            // Non Trauma
            elseif ((int) $jenisKasus === 0) {

                $kasus['DIMANA'] = $request->input('jks_end_dm', '');

            }

            // ==========================================
            // ANAMNESE
            // ==========================================
            $anamnese = [
                'KELUHAN_UTAMA' => $request->input('anm_ku', ''),
                'TERPIMPIN'     => $request->input('anm_tp', ''),
            ];


            // ==========================================
            // TANDA VITAL
            // ==========================================
            $tandaVital = [
                'SUHU'          => $request->input('tv_sh', ''),
                'SISTOLE'       => $request->input('tv_up', ''),
                'DIASTOLE'      => $request->input('tv_down', ''),
                'FREK_NADI'     => $request->input('tv_nadi', ''),
                'FREK_NAFAS'    => $request->input('tv_fr', ''),
                'METODE_UKUR'   => $request->input('tv_mu', ''),
                'SKALA_NYERI'   => $request->input('tv_sn', ''),
            ];


            // ==========================================
            // OBGYN
            // ==========================================
            $obgyn = [
                'USIA_GESTASI'       => $request->input('ko_ug', ''),
                'KONTRAKSI_UTERUS'   => $request->input('ko_ku', ''),
                'DETAK_JANTUNG'      => $request->input('ko_dj', ''),
                'DILATASI_SERVIKS'   => $request->input('ko_ds', ''),
            ];


            // ==========================================
            // KEBUTUHAN KHUSUS
            // ==========================================
            $kebutuhanKhusus = [
                'AIRBONE'      => $request->input('kk_a', ''),
                'DEKONTAMINAN' => $request->input('kk_d', ''),
            ];

            // ==========================================
            // TRIAGE - CARA KEDATANGAN & JENIS KASUS
            // ==========================================
            DB::table('medicalrecord.triage')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    // ==============================
                    // CARA KEDATANGAN
                    // ==============================
                    'KEDATANGAN' => json_encode(
                        $kedatangan,
                        JSON_UNESCAPED_UNICODE
                    ),

                    // ==============================
                    // JENIS KASUS
                    // ==============================
                    'KASUS' => json_encode(
                        $kasus,
                        JSON_UNESCAPED_UNICODE
                    ),

                    // ==============================
                    // ANAMNESE
                    // ==============================
                    'ANAMNESE' => json_encode(
                        $anamnese,
                        JSON_UNESCAPED_UNICODE
                    ),

                    // ==============================
                    // TANDA VITAL
                    // ==============================
                    'TANDA_VITAL' => json_encode(
                        $tandaVital,
                        JSON_UNESCAPED_UNICODE
                    ),

                    // ==============================
                    // OBGYN
                    // ==============================
                    'OBGYN' => json_encode(
                        $obgyn,
                        JSON_UNESCAPED_UNICODE
                    ),

                    // ==============================
                    // KEBUTUHAN KHUSUS
                    // ==============================
                    'KEBUTUHAN_KHUSUS' => json_encode(
                        $kebutuhanKhusus,
                        JSON_UNESCAPED_UNICODE
                    ),

                    'OLEH'   => auth()->id(),
                    'STATUS' => 1,
                    'TANGGAL' => now(),
                ]
            );

            // ==========================================
            // KONDISI SOSIAL
            // ==========================================
            DB::table('medicalrecord.kondisi_sosial')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    // Status Psikologi
                    'TIDAK_ADA_KELAINAN' => $request->input('tak') ? 1 : 0,
                    'MARAH'              => $request->input('marah') ? 1 : 0,
                    'CEMAS'              => $request->input('cemas') ? 1 : 0,
                    'TAKUT'              => $request->input('takut') ? 1 : 0,
                    'SEDIH'              => $request->input('sedih') ? 1 : 0,
                    'BUNUH_DIRI'         => $request->input('bundir') ? 1 : 0,
                    'LAINNYA'            => $request->pse_lain,

                    // Status Mental
                    'STATUS_MENTAL'                         => $request->sm ?? 0,
                    'MASALAH_PERILAKU'                      => $request->sm2_lain,
                    'PERILAKU_KEKERASAN_DIALAMI_SEBELUMNYA' => $request->sm3_lain,

                    // Hubungan Sosial
                    'HUBUNGAN_PASIEN_DENGAN_KELUARGA' => $request->hub ?? 0,
                    'TEMPAT_TINGGAL'                  => $request->tinggal ?? 0,
                    'TEMPAT_TINGGAL_LAINNYA'          => $request->tinggal_lain,

                    // Spiritual
                    'KEBIASAAN_BERIBADAH_TERATUR' => $request->kbt ?? 0,
                    'NILAI_KEPERCAYAAN'           => $request->nk ?? 0,
                    'NILAI_KEPERCAYAAN_DESKRIPSI' => $request->nk_lain,
                    'PENGAMBIL_KEPUTUSAN_DALAM_KELUARGA' => $request->pk,

                    // Ekonomi
                    'PENGHASILAN_PERBULAN' => $request->hasil ?? 0,

                    // Audit
                    'OLEH'    => auth()->id(),
                    'STATUS'  => 1,
                    'TANGGAL' => now(),
                ]
            );

            // ==========================================
            // PENILAIAN / SKRINING NYERI
            // ==========================================
            DB::table('medicalrecord.penilaian_nyeri')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'NYERI'     => $request->sn_nyeri ?? 0,
                    'ONSET'     => $request->sn_onset ?? null,
                    'SKALA'     => $request->sn_skala,
                    'METODE'    => $request->sn_metode,
                    'PENCETUS'  => $request->sn_pencetus,
                    'GAMBARAN'  => $request->sn_gambaran,
                    'DURASI'    => $request->sn_durasi,
                    'LOKASI'    => $request->sn_lokasi,

                    'OLEH'      => auth()->id(),
                    'STATUS'    => 1,
                    'TANGGAL'   => now(),
                ]
            );

            // ==========================================
            // SKRINING RISIKO JATUH - HUMPTY DUMPTY
            // ==========================================
            DB::table('medicalrecord.penilaian_skala_humpty_dumpty')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'UMUR'                => $request->rj_usia,
                    'JENIS_KELAMIN'       => $request->rj_jk,
                    'DIAGNOSA'            => $request->rj_hd_1,
                    'GANGGUAN_KONGNITIF'  => $request->rj_hd_2,
                    'FAKTOR_LINGKUNGAN'   => $request->rj_hd_3,
                    'RESPON'              => $request->rj_hd_4,
                    'PENGGUNAAN_OBAT'     => $request->rj_hd_5,
                    'TANGGAL'             => now(),
                    'OLEH'                => auth()->id(),
                    'STATUS'              => 1,
                ]
            );

            // ==========================================
            // SKRINING RISIKO JATUH - MORSE
            // ==========================================
            DB::table('medicalrecord.penilaian_skala_morse')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'RIWAYAT_JATUH' => $request->rj_sm_1,
                    'DIAGNOSIS'     => $request->rj_sm_2,
                    'ALAT_BANTU'    => $request->rj_sm_3,
                    'HEPARIN'       => $request->rj_sm_4,
                    'GAYA_BERJALAN' => $request->rj_sm_5,
                    'KESADARAN'     => $request->rj_sm_6,
                    'TANGGAL'       => now(),
                    'OLEH'          => auth()->id(),
                    'STATUS'        => 1,
                ]
            );

            // ==========================================
            // SKRINING RISIKO JATUH - EPFRA
            // ==========================================
            DB::table('medicalrecord.penilaian_epfra')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'USIA'            => $request->rj_epfra_usia,
                    'STATUS_MENTAL'   => $request->rj_epfra_1,
                    'ELIMINASI'       => $request->rj_epfra_2,
                    'MEDIKASI'        => $request->rj_epfra_3,
                    'DIAGNOSIS'       => $request->rj_epfra_4,
                    'AMBULASI'        => $request->rj_epfra_5,
                    'NUTRISI'         => $request->rj_epfra_6,
                    'GANGGUAN_TIDUR'  => $request->rj_epfra_7,
                    'RIWAYAT_JATUH'   => $request->rj_epfra_8,
                    'TANGGAL'         => now(),
                    'OLEH'            => auth()->id(),
                    'STATUS'          => 1,
                ]
            );

            // ==========================================
            // SKRINING GIZI - MUST
            // ==========================================
            DB::table('medicalrecord.permasalahan_gizi')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'BERAT_BADAN_SIGNIFIKAN' => $request->sgd1,
                    'PERUBAHAN_BERAT_BADAN'  => $request->sgd1_c,
                    'INTAKE_MAKANAN'         => $request->sgd2,
                    'KONDISI_KHUSUS'         => $request->sgd3,
                    'SKOR'                   => $request->skor_sgd,
                    'STATUS_SKOR'            => 1,
                    'TANGGAL'                => now(),
                    'OLEH'                   => auth()->id(),
                    // 'STATUS_VALIDASI'        => 0,
                    // 'TANGGAL_VALIDASI'       => '0000-00-00 00:00:00',
                    // 'USER_VALIDASI'          => 0,
                    'STATUS'                 => 1,
                ]
            );

            // ==========================================
            // SKRINING GIZI - STRONG KID
            // ==========================================
            DB::table('medicalrecord.penilaian_strong_kid')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'TAMPAK_KURUS'          => $request->sga1,
                    'PENURUNAN_BERAT_BADAN' => $request->sga2,
                    'DIARE_INTAKE_MAKANAN'  => $request->sga3,
                    'RESIKO_MALNUTRISI'     => $request->sga4,
                    'SKOR'                  => $request->skor_sga,
                    'STATUS_SKOR'           => 1,
                    'TANGGAL'               => now(),
                    'OLEH'                  => auth()->id(),
                    // 'STATUS_VALIDASI'       => 0,
                    // 'TANGGAL_VALIDASI'      => '0000-00-00 00:00:00',
                    // 'USER_VALIDASI'         => 0,
                    'STATUS'                => 1,
                ]
            );

            // ==========================================
            // STATUS REPRODUKSI
            // ==========================================
            DB::table('medicalrecord.status_reproduksi')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'KASUS_OBSTETRI_GINEKOLOGI' => $request->kasus_obstetri_ginekologi,
                    'STATUS_REPRODUKSI'          => $request->status_reproduksi,
                    'HPHT'                       => $request->hpht,
                    'SIKLUS'                     => $request->siklus,
                    'KB'                         => $request->kb,
                    'HAMIL_GRAVIDA'              => $request->hamil_gravida,
                    'HAMIL_PARITAS'              => $request->hamil_paritas,
                    'HAMIL_ABORTUS'              => $request->hamil_abortus,
                    'TANGGAL'                    => now(),
                    'OLEH'                       => auth()->id(),
                    'STATUS'                     => 1,
                ]
            );

            // ==========================================
            // MASALAH KEPERAWATAN
            // ==========================================

            // Sedangkan dmk_3, dmk_6, dmk_7, dmk_9, dmk_10, dan dmk_lain tidak saya masukkan karena tabel masalah_keperawatan
            // yang Anda berikan tidak mempunyai kolom yang sesuai.

            // Catatan penting: kalau sebenarnya dmk_4 Gangguan Pernafasan ingin mewakili BERSIHAN_JALAN_NAFAS_TIDAK_EFEKTIF atau
            // POLA_NAFAS_TIDAK_EFEKTIF, tinggal ubah mapping tersebut. Dari label HTML saja memang tidak bisa ditentukan secara pasti.

            DB::table('medicalrecord.masalah_keperawatan')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    // ------------------------------------------
                    // Masalah Keperawatan sesuai HTML
                    // ------------------------------------------
                    'NYERI'                         => $request->boolean('dmk_1') ? 1 : 0,
                    'CEMAS'                         => $request->boolean('dmk_2') ? 1 : 0,
                    'PERUBAHAN_NUTRISI'             => $request->boolean('dmk_3') ? 1 : 0,
                    'GANGGUAN_PERNAFASAN'           => $request->boolean('dmk_4') ? 1 : 0,
                    'GANGGUAN_PERFUSI_JARINGAN'     => $request->boolean('dmk_5') ? 1 : 0,
                    'GANGGUAN_VOLUME_CAIRAN'        => $request->boolean('dmk_6') ? 1 : 0,
                    'POTENSI_INFEKSI'               => $request->boolean('dmk_7') ? 1 : 0,
                    'HIPERTEMI'                     => $request->boolean('dmk_8') ? 1 : 0,
                    'TAKUT'                         => $request->boolean('dmk_9') ? 1 : 0,
                    'KETIDAKEFEKTIFAN_POLA_MAKAN'  => $request->boolean('dmk_10') ? 1 : 0,
                    'MASALAH_LAIN'                  => $request->input('dmk_lain', ''),

                    'TANGGAL'                       => now(),
                    'OLEH'                          => auth()->id(),
                    'STATUS'                        => 1,
                ]
            );

            // ==========================================
            // DISCHARGE PLANNING - FAKTOR RISIKO
            // ==========================================
            DB::table('medicalrecord.discharge_planning_faktor_risiko')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'PASIEN_TINGGAL_SENDIRI'          => $request->input('dp_1', 0),
                    'PASIEN_KHAWATIR_KETIKA_DIRUMAH'  => $request->input('dp_2', 0),
                    'PASIEN_TAK_ADA_YANG_MERAWAT'     => $request->input('dp_3', 0),
                    'PASIEN_DILANTAI_ATAS'             => $request->input('dp_4', 0),
                    'PERAWATAN_LANJUTAN_PASIEN'        => $request->input('dp_5', 0),
                    'PENGAJUAN_PENDAMPINGAN_PASIEN'    => $request->input('dp_7', 0),

                    'TANGGAL'                           => now(),
                    'OLEH'                              => auth()->id(),
                    'STATUS'                            => 1,
                ]
            );

            // ==========================================
            // DISCHARGE PLANNING - SKRINING
            // ==========================================
            DB::table('medicalrecord.discharge_planning_skrining')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    // ------------------------------------------
                    // Kriteria dasar
                    // ------------------------------------------
                    'PASIEN_PULANG'                         => $request->input('dp_6', 0),
                    'PASIEN_MENGAJUKAN'                     => $request->input('dp_7', 0),
                    'TIDAK_ADA_KRITERIA'                    => $request->input('dp_8', 0),

                    // ------------------------------------------
                    // Kebutuhan Pelayanan Berkelanjutan (KPB)
                    // ------------------------------------------
                    'KEBUTUHAN_PELAYANAN_BERKELANJUTAN_KPB' => $request->input('dp_9', 0),

                    'KPB_RAWAT_LUKA'                        => $request->boolean('dp_9_1') ? 1 : 0,
                    'KPB_TB'                                => $request->boolean('dp_9_2') ? 1 : 0,
                    'KPB_DM_TERAPI_INSULIN'                 => $request->boolean('dp_9_3') ? 1 : 0,
                    'KPB_PPOK'                              => $request->boolean('dp_9_4') ? 1 : 0,
                    'KPB_PASIEN_KEMO'                       => $request->boolean('dp_9_5') ? 1 : 0,
                    'KPB_HIV'                               => $request->boolean('dp_9_6') ? 1 : 0,
                    'KPB_DM'                                => $request->boolean('dp_9_7') ? 1 : 0,
                    'KPB_STROKE'                            => $request->boolean('dp_9_8') ? 1 : 0,
                    'KPB_CKD'                               => $request->boolean('dp_9_9') ? 1 : 0,

                    // Lainnya pada "Perawatan lanjutan pasien"
                    // dp_5_lain berasal dari field "Jika Ada, sebutkan"
                    'KEBUTUHAN_PELAYANAN_BERKELANJUTAN_LAINNYA' => $request->input('dp_5_lain', ''),

                    // ------------------------------------------
                    // Penggunaan alat medis / bantu
                    // ------------------------------------------
                    'PENGGUNAAN_ALAT_MEDIS_PAM'             => $request->input('dp_10', 0),

                    'PAM_KATETER_URIN'                      => $request->boolean('dp_10_1') ? 1 : 0,
                    'PAM_TRAECHOSTOMY'                      => $request->boolean('dp_10_2') ? 1 : 0,
                    'PAM_NGT'                               => $request->boolean('dp_10_3') ? 1 : 0,
                    'PAM_COLOSTOMY'                         => $request->boolean('dp_10_4') ? 1 : 0,
                    'PAM_LAINNYA'                           => $request->input('dp_10_lain', ''),

                    // ------------------------------------------
                    // Skrining lanjutan
                    // ------------------------------------------
                    'SKRINING_LANJUTAN'                     => $request->input('dp_11', 0),

                    // 1 = Konsul MPP
                    // 2 = Edukasi
                    'SKRINING'                              => $request->input('dp_11_skrining', 0),

                    'TANGGAL'                               => now(),
                    'OLEH'                                  => auth()->id(),
                    'STATUS'                                => 1,
                ]
            );

            // ==========================================
            // COMMIT
            // ==========================================
            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Pengkajian perawat berhasil disimpan.'
            ], 200);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Data pengkajian perawat gagal disimpan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // Array
    // (
    //     [NOKUNJ] => 1020201022607290001
    //     [dd_ck] =>
    //     [dd_ck_p] =>
    //     [dd_ck_k] =>
    //     [dd_ck_a] =>
    //     [dd_ck_a_v] =>
    //     [tgl_ck] =>
    //     [tr_ck] =>
    //     [jks] =>
    //     [jks_kll] =>
    //     [jks_kk] =>
    //     [jks_uppa] =>
    //     [jks_end] =>
    //     [jks_end_dm] =>
    //     [anm_ku] =>
    //     [anm_tp] =>
    //     [tv_up] =>
    //     [tv_down] =>
    //     [tv_fr] =>
    //     [tv_nadi] =>
    //     [tv_sh] =>
    //     [tv_sn] =>
    //     [tv_mu] =>
    //     [ko_ug] =>
    //     [ko_ku] =>
    //     [ko_dj] =>
    //     [ko_ds] =>
    //     [kk_a] =>
    //     [kk_d] =>
    //     [psi] =>
    //     [psi_lain] =>
    //     [sm] =>
    //     [sm2_lain] =>
    //     [sm3_lain] =>
    //     [hub] =>
    //     [tinggal] =>
    //     [tinggal_lain] =>
    //     [agama] =>
    //     [kbt] =>
    //     [nk] =>
    //     [nk_lain] =>
    //     [pk] =>
    //     [kerja] =>
    //     [hasil] =>
    //     [sn_nyeri] => 0
    //     [sn_onset] =>
    //     [sn_skala] => 0
    //     [sn_metode] =>
    //     [sn_pencetus] =>
    //     [sn_gambaran] =>
    //     [sn_durasi] =>
    //     [sn_lokasi] =>
    //     [rj_usia] =>
    //     [rj_jk] =>
    //     [rj_hd_1] =>
    //     [rj_hd_2] =>
    //     [rj_hd_3] =>
    //     [rj_hd_4] =>
    //     [rj_hd_5] =>
    //     [skor_rj_hd] => 0
    //     [rj_sm_1] =>
    //     [rj_sm_2] =>
    //     [rj_sm_3] =>
    //     [rj_sm_4] =>
    //     [rj_sm_5] =>
    //     [rj_sm_6] =>
    //     [skor_rj_sm] => 0
    //     [rj_epfra_usia] =>
    //     [rj_epfra_1] =>
    //     [rj_epfra_2] =>
    //     [rj_epfra_3] =>
    //     [rj_epfra_4] =>
    //     [rj_epfra_5] =>
    //     [rj_epfra_6] =>
    //     [rj_epfra_7] =>
    //     [rj_epfra_8] =>
    //     [skor_rj_epfra] => 0
    //     [sgd1] =>
    //     [sgd1_c] =>
    //     [sgd2] =>
    //     [sgd3] =>
    //     [skor_sgd] => 0
    //     [sga1] =>
    //     [sga2] =>
    //     [sga3] =>
    //     [sga4] =>
    //     [skor_sga] => 0
    //     [sh] => 0
    //     [sh_g] =>
    //     [sh_p] =>
    //     [sh_a] =>
    //     [sh_h] =>
    //     [ik_1] =>
    //     [ik_1_dt] =>
    //     [ik_2] =>
    //     [ik_2_dt] =>
    //     [ik_3] =>
    //     [ik_3_dt] =>
    //     [ik_4] =>
    //     [ik_4_dt] =>
    //     [ik_5] =>
    //     [ik_5_dt] =>
    //     [ik_6] =>
    //     [ik_6_dt] =>
    //     [ik_7] =>
    //     [ik_7_dt] =>
    //     [ik_8] =>
    //     [ik_8_dt] =>
    //     [ik_9] =>
    //     [ik_9_dt] =>
    //     [ik_10] =>
    //     [ik_10_dt] =>
    //     [ik_11_inp] =>
    //     [ik_11] =>
    //     [ik_11_dt] =>
    //     [ik_12] =>
    //     [ik_12_dt] =>
    //     [ik_13] =>
    //     [ik_13_dt] =>
    //     [ik_14] =>
    //     [ik_14_dt] =>
    //     [ik_15] =>
    //     [ik_15_dt] =>
    //     [tk_1] =>
    //     [tk_1_dt] =>
    //     [tk_2] =>
    //     [tk_2_dt] =>
    //     [tk_3] =>
    //     [tk_3_dt] =>
    //     [tk_4] =>
    //     [tk_4_dt] =>
    //     [tk_5] =>
    //     [tk_5_dt] =>
    //     [tk_6] =>
    //     [tk_6_dt] =>
    //     [tk_7] =>
    //     [tk_7_dt] =>
    //     [tk_8] =>
    //     [tk_8_dt] =>
    //     [tk_9_inp] =>
    //     [tk_9] =>
    //     [tk_9_dt] =>
    //     [tk_10] =>
    //     [tk_10_dt] =>
    //     [tk_11] =>
    //     [tk_11_dt] =>
    //     [tk_12] =>
    //     [tk_12_dt] =>
    //     [tk_13] =>
    //     [tk_13_dt] =>
    //     [tk_14_inp] =>
    //     [tk_14] =>
    //     [tk_14_dt] =>
    //     [tk_15] =>
    //     [tk_15_dt] =>
    //     [tk_16_inp] =>
    //     [tk_16] =>
    //     [tk_16_dt] =>
    //     [tk_17_inp] =>
    //     [tk_17] =>
    //     [tk_17_dt] =>
    //     [tk_18_inp] =>
    //     [tk_18] =>
    //     [tk_18_dt] =>
    //     [tk_19_inp] =>
    //     [tk_19] =>
    //     [tk_19_dt] =>
    //     [tk_20_inp] =>
    //     [tk_20] =>
    //     [tk_20_dt] =>
    //     [tk_21] =>
    //     [tk_21_dt] =>
    //     [tk_22] =>
    //     [tk_22_dt] =>
    //     [tk_23] =>
    //     [tk_23_dt] =>
    //     [tk_24] =>
    //     [tk_24_dt] =>
    //     [tk_25] =>
    //     [tk_25_dt] =>
    //     [tk_26] =>
    //     [tk_26_dt] =>
    //     [tk_27] =>
    //     [tk_27_dt] =>
    //     [dmk_1] =>
    //     [dmk_2] =>
    //     [dmk_3] =>
    //     [dmk_4] =>
    //     [dmk_5] =>
    //     [dmk_6] =>
    //     [dmk_7] =>
    //     [dmk_8] =>
    //     [dmk_9] =>
    //     [dmk_10] =>
    //     [dmk_lain] =>
    //     [dp_1] => 0
    //     [dp_2] => 0
    //     [dp_3] => 0
    //     [dp_4] => 0
    //     [dp_5] => 0
    //     [dp_5_1] =>
    //     [dp_5_2] =>
    //     [dp_5_3] =>
    //     [dp_5_4] =>
    //     [dp_5_lain] =>
    //     [dp_9] => 0
    //     [dp_9_1] =>
    //     [dp_9_2] =>
    //     [dp_9_3] =>
    //     [dp_9_4] =>
    //     [dp_9_5] =>
    //     [dp_9_6] =>
    //     [dp_9_7] =>
    //     [dp_9_8] =>
    //     [dp_9_9] =>
    //     [dp_6] => 0
    //     [dp_7] => 0
    //     [dp_8] => 0
    //     [dp_10] => 0
    //     [dp_10_1] =>
    //     [dp_10_2] =>
    //     [dp_10_3] =>
    //     [dp_10_4] =>
    //     [dp_10_lain] =>
    //     [dp_11] => 0
    //     [dp_11_skrining] =>
    // )

}
