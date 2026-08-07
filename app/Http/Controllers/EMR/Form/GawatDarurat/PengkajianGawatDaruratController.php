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
        public function getFormPerawatGd($KUNJUNGAN)
        {
            // Helper untuk mengambil 1 data berdasarkan kunjungan
            $getData = function ($table, $columns = ['*']) use ($KUNJUNGAN) {
                return DB::table($table)
                    ->select($columns)
                    ->where('KUNJUNGAN', $KUNJUNGAN)
                    ->whereIn('STATUS', [1, 2])
                    ->orderByDesc('ID')
                    ->first();
            };

            // ==========================================
            // TRIAGE
            // ==========================================
            $triage = $getData(
                'medicalrecord.triage',
                [
                    'KUNJUNGAN',
                    'KEDATANGAN',
                    'KASUS',
                    'ANAMNESE',
                    'TANDA_VITAL',
                    'OBGYN',
                    'KEBUTUHAN_KHUSUS',
                    'KRITERIA',
                    'RISIKO_PENULARAN_INFEKSI',
                ]
            );

            // Decode JSON TRIAGE
            if ($triage) {
                $triage->KEDATANGAN = $triage->KEDATANGAN
                    ? json_decode($triage->KEDATANGAN)
                    : null;

                $triage->KASUS = $triage->KASUS
                    ? json_decode($triage->KASUS)
                    : null;

                $triage->ANAMNESE = $triage->ANAMNESE
                    ? json_decode($triage->ANAMNESE)
                    : null;

                $triage->TANDA_VITAL = $triage->TANDA_VITAL
                    ? json_decode($triage->TANDA_VITAL)
                    : null;

                $triage->OBGYN = $triage->OBGYN
                    ? json_decode($triage->OBGYN)
                    : null;

                $triage->KEBUTUHAN_KHUSUS = $triage->KEBUTUHAN_KHUSUS
                    ? json_decode($triage->KEBUTUHAN_KHUSUS)
                    : null;
            }

            // ==========================================
            // ANAMNESIS DIPEROLEH
            // ==========================================
            $anamnesisDiperoleh = $getData(
                'medicalrecord.anamnesis_diperoleh'
            );

            // ==========================================
            // KONDISI SOSIAL
            // ==========================================
            $kondisiSosial = $getData(
                'medicalrecord.kondisi_sosial',
                [
                    'TIDAK_ADA_KELAINAN',
                    'MARAH',
                    'CEMAS',
                    'TAKUT',
                    'SEDIH',
                    'BUNUH_DIRI',
                    'LAINNYA',

                    'STATUS_MENTAL',
                    'MASALAH_PERILAKU',
                    'PERILAKU_KEKERASAN_DIALAMI_SEBELUMNYA',

                    'HUBUNGAN_PASIEN_DENGAN_KELUARGA',
                    'TEMPAT_TINGGAL',
                    'TEMPAT_TINGGAL_LAINNYA',

                    'KEBIASAAN_BERIBADAH_TERATUR',
                    'NILAI_KEPERCAYAAN',
                    'NILAI_KEPERCAYAAN_DESKRIPSI',
                    'PENGAMBIL_KEPUTUSAN_DALAM_KELUARGA',

                    'PENGHASILAN_PERBULAN',
                ]
            );

            // ==========================================
            // PENILAIAN NYERI
            // ==========================================
            $penilaianNyeri = $getData(
                'medicalrecord.penilaian_nyeri',
                [
                    'NYERI',
                    'ONSET',
                    'SKALA',
                    'METODE',
                    'PENCETUS',
                    'GAMBARAN',
                    'DURASI',
                    'LOKASI',
                ]
            );

            // ==========================================
            // HUMPTY DUMPTY
            // ==========================================
            $humptyDumpty = $getData(
                'medicalrecord.penilaian_skala_humpty_dumpty',
                [
                    'UMUR',
                    'JENIS_KELAMIN',
                    'DIAGNOSA',
                    'GANGGUAN_KONGNITIF',
                    'FAKTOR_LINGKUNGAN',
                    'RESPON',
                    'PENGGUNAAN_OBAT',
                ]
            );

            // ==========================================
            // MORSE
            // ==========================================
            $morse = $getData(
                'medicalrecord.penilaian_skala_morse',
                [
                    'RIWAYAT_JATUH',
                    'DIAGNOSIS',
                    'ALAT_BANTU',
                    'HEPARIN',
                    'GAYA_BERJALAN',
                    'KESADARAN',
                ]
            );

            // ==========================================
            // EPFRA
            // ==========================================
            $epfra = $getData(
                'medicalrecord.penilaian_epfra',
                [
                    'USIA',
                    'STATUS_MENTAL',
                    'ELIMINASI',
                    'MEDIKASI',
                    'DIAGNOSIS',
                    'AMBULASI',
                    'NUTRISI',
                    'GANGGUAN_TIDUR',
                    'RIWAYAT_JATUH',
                ]
            );

            // ==========================================
            // SKRINING GIZI - MUST
            // ==========================================
            $must = $getData(
                'medicalrecord.permasalahan_gizi',
                [
                    'BERAT_BADAN_SIGNIFIKAN',
                    'PERUBAHAN_BERAT_BADAN',
                    'INTAKE_MAKANAN',
                    'KONDISI_KHUSUS',
                    'SKOR',
                    'STATUS_SKOR',
                ]
            );

            // ==========================================
            // SKRINING GIZI - STRONG KID
            // ==========================================
            $strongKid = $getData(
                'medicalrecord.penilaian_strong_kid',
                [
                    'TAMPAK_KURUS',
                    'PENURUNAN_BERAT_BADAN',
                    'DIARE_INTAKE_MAKANAN',
                    'RESIKO_MALNUTRISI',
                    'SKOR',
                    'STATUS_SKOR',
                ]
            );

            // ==========================================
            // STATUS REPRODUKSI
            // ==========================================
            $statusReproduksi = $getData(
                'medicalrecord.status_reproduksi',
                [
                    'KASUS_OBSTETRI_GINEKOLOGI',
                    'STATUS_REPRODUKSI',
                    'HPHT',
                    'SIKLUS',
                    'KB',
                    'HAMIL_GRAVIDA',
                    'HAMIL_PARITAS',
                    'HAMIL_ABORTUS',
                ]
            );

            // ==========================================
            // MASALAH KEPERAWATAN
            // Hanya field yang ada di HTML
            // ==========================================
            $masalahKeperawatan = $getData(
                'medicalrecord.masalah_keperawatan',
                [
                    'NYERI',
                    'CEMAS',
                    'PERUBAHAN_NUTRISI',
                    'GANGGUAN_PERNAFASAN',
                    'GANGGUAN_PERFUSI_JARINGAN',
                    'GANGGUAN_VOLUME_CAIRAN',
                    'POTENSI_INFEKSI',
                    'HIPERTEMI',
                    'TAKUT',
                    'KETIDAKEFEKTIFAN_POLA_MAKAN',
                    'MASALAH_LAIN',
                ]
            );

            // ==========================================
            // DISCHARGE PLANNING - FAKTOR RISIKO
            // ==========================================
            $dischargeFaktorRisiko = $getData(
                'medicalrecord.discharge_planning_faktor_risiko',
                [
                    'PASIEN_TINGGAL_SENDIRI',
                    'PASIEN_KHAWATIR_KETIKA_DIRUMAH',
                    'PASIEN_TAK_ADA_YANG_MERAWAT',
                    'PASIEN_DILANTAI_ATAS',
                    'PERAWATAN_LANJUTAN_PASIEN',
                    'PENGAJUAN_PENDAMPINGAN_PASIEN',
                ]
            );

            // ==========================================
            // DISCHARGE PLANNING - SKRINING
            // ==========================================
            $dischargeSkrining = $getData(
                'medicalrecord.discharge_planning_skrining',
                [
                    'PASIEN_PULANG',
                    'PASIEN_MENGAJUKAN',
                    'TIDAK_ADA_KRITERIA',

                    'KEBUTUHAN_PELAYANAN_BERKELANJUTAN_KPB',
                    'KPB_RAWAT_LUKA',
                    'KPB_HIV',
                    'KPB_TB',
                    'KPB_DM',
                    'KPB_DM_TERAPI_INSULIN',
                    'KPB_STROKE',
                    'KPB_PPOK',
                    'KPB_CKD',
                    'KPB_PASIEN_KEMO',
                    'KEBUTUHAN_PELAYANAN_BERKELANJUTAN_LAINNYA',

                    'PENGGUNAAN_ALAT_MEDIS_PAM',
                    'PAM_KATETER_URIN',
                    'PAM_NGT',
                    'PAM_TRAECHOSTOMY',
                    'PAM_COLOSTOMY',
                    'PAM_LAINNYA',

                    'SKRINING_LANJUTAN',
                    'SKRINING',
                ]
            );

            // ==========================================
            // RESPONSE
            // ==========================================
            return response()->json([
                'status' => true,

                'data' => [
                    'triage'                    => $triage,
                    'anamnesis_diperoleh'       => $anamnesisDiperoleh,
                    'kondisi_sosial'            => $kondisiSosial,
                    'penilaian_nyeri'           => $penilaianNyeri,
                    'humpty_dumpty'             => $humptyDumpty,
                    'morse'                     => $morse,
                    'epfra'                     => $epfra,
                    'must'                      => $must,
                    'strong_kid'                => $strongKid,
                    'status_reproduksi'         => $statusReproduksi,
                    'masalah_keperawatan'       => $masalahKeperawatan,
                    'discharge_faktor_risiko'   => $dischargeFaktorRisiko,
                    'discharge_skrining'        => $dischargeSkrining,
                ],
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
                $jenisKedatangan = (int) $request->dd_ck ?? 0;

                if (!in_array($jenisKedatangan, [1, 2, 3])) {
                    DB::rollBack();

                    return response()->json([
                        'status'  => false,
                        'message' => 'Jenis Cara Kedatangan Belum diisi.'
                    ], 422);
                }

                if ($request->tgl_ck === null || $request->tr_ck === null) {
                    DB::rollBack();

                    return response()->json([
                        'status'  => false,
                        'message' => 'Tanggal dan alat transportasi kedatangan wajib diisi.'
                    ], 422);
                }
                $kedatangan = [
                    'JENIS'             => $jenisKedatangan,
                    'TANGGAL'           => Carbon::parse($request->tgl_ck)->format('Y-m-d H:i:s'),
                    'PENGANTAR'         => '',
                    'KEPOLISIAN'        => '',
                    'ASAL_RUJUKAN'      => '',
                    'ALAT_TRANSPORTASI' => $request->tr_ck ?? '',
                ];

                // Datang sendiri
                if ($jenisKedatangan === 1) {

                    $kedatangan['PENGANTAR'] = $request->dd_ck_p ?? '';

                }

                // Rujukan dari
                elseif ($jenisKedatangan === 2) {

                    $kedatangan['ASAL_RUJUKAN'] = $request->dd_ck_k ?? '';

                }

                // Dikirim oleh polisi
                elseif ($jenisKedatangan === 3) {

                    $kedatangan['VISUM'] = $request->boolean('dd_ck_a_v') ? 1 : 0;
                    $kedatangan['KEPOLISIAN'] = $request->dd_ck_a ?? '';

                }


                // ==========================================
                // JENIS KASUS
                // ==========================================
                $jenisKasus = $request->input('jks');

                $kasus = [
                    'JENIS'            => $jenisKasus !== null && $jenisKasus !== ''
                                            ? (int) $jenisKasus
                                            : '',

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

                    $kasus['DIMANA'] = $request->jks_end_dm ?? '';

                }

                // ==========================================
                // ANAMNESE
                // ==========================================
                $anamnese = [
                    'KELUHAN_UTAMA' => $request->anm_ku ?? '',
                    'TERPIMPIN'     => $request->anm_tp ?? '',
                ];


                // ==========================================
                // TANDA VITAL
                // ==========================================
                $tandaVital = [
                    'SUHU'          => $request->tv_sh ?? '',
                    'SISTOLE'       => $request->tv_up ?? '',
                    'DIASTOLE'      => $request->tv_down ?? '',
                    'FREK_NADI'     => $request->tv_nadi ?? '',
                    'FREK_NAFAS'    => $request->tv_fr ?? '',
                    'METODE_UKUR'   => $request->tv_mu ?? '',
                    'SKALA_NYERI'   => $request->tv_sn ?? '',
                ];


                // ==========================================
                // OBGYN
                // ==========================================
                $obgyn = [
                    'USIA_GESTASI'       => $request->ko_ug ?? '',
                    'KONTRAKSI_UTERUS'   => $request->ko_ku ?? '',
                    'DETAK_JANTUNG'      => $request->ko_dj ?? '',
                    'DILATASI_SERVIKS'   => $request->ko_ds ?? '',
                ];


                // ==========================================
                // KEBUTUHAN KHUSUS
                // ==========================================
                $kebutuhanKhusus = [
                    'AIRBONE'      => $request->kk_a ?? '',
                    'DEKONTAMINAN' => $request->kk_d ?? '',
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
                        'TIDAK_ADA_KELAINAN' => $request->tak ? 1 : 0,
                        'MARAH'              => $request->marah ? 1 : 0,
                        'CEMAS'              => $request->cemas ? 1 : 0,
                        'TAKUT'              => $request->takut ? 1 : 0,
                        'SEDIH'              => $request->sedih ? 1 : 0,
                        'BUNUH_DIRI'         => $request->bundir ? 1 : 0,
                        'LAINNYA'            => $request->pse_lain ?? '',

                        // Status Mental
                        'STATUS_MENTAL'                         => $request->sm ?? 0,
                        'MASALAH_PERILAKU'                      => $request->sm2_lain ?? '',
                        'PERILAKU_KEKERASAN_DIALAMI_SEBELUMNYA' => $request->sm3_lain ?? '',

                        // Hubungan Sosial
                        'HUBUNGAN_PASIEN_DENGAN_KELUARGA' => $request->hub ?? 0,
                        'TEMPAT_TINGGAL'                  => $request->tinggal ?? 0,
                        'TEMPAT_TINGGAL_LAINNYA'          => $request->tinggal_lain ?? '',

                        // Spiritual
                        'KEBIASAAN_BERIBADAH_TERATUR' => $request->kbt ?? 0,
                        'NILAI_KEPERCAYAAN'           => $request->nk ?? 0,
                        'NILAI_KEPERCAYAAN_DESKRIPSI' => $request->nk_lain ?? '',
                        'PENGAMBIL_KEPUTUSAN_DALAM_KELUARGA' => $request->pk ?? 0,

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
                        'ONSET'     => $request->sn_onset ?? 0,
                        'SKALA'     => $request->sn_skala ?? '',
                        'METODE'    => $request->sn_metode ?? '',
                        'PENCETUS'  => $request->sn_pencetus ?? '',
                        'GAMBARAN'  => $request->sn_gambaran ?? '',
                        'DURASI'    => $request->sn_durasi ?? '',
                        'LOKASI'    => $request->sn_lokasi ?? '',

                        'OLEH'      => auth()->id(),
                        'STATUS'    => 1,
                        'TANGGAL'   => now(),
                    ]
                );

                // ==========================================
                // SKRINING RISIKO JATUH - HUMPTY DUMPTY
                // ==========================================
                if ($request->rj_usia != '' || $request->rj_jk != '') {
                    DB::table('medicalrecord.penilaian_skala_humpty_dumpty')->updateOrInsert(
                        [
                            'KUNJUNGAN' => $request->NOKUNJ
                        ],
                        [
                            'UMUR'                => $request->rj_usia ?? 0,
                            'JENIS_KELAMIN'       => $request->rj_jk ?? 0,
                            'DIAGNOSA'            => $request->rj_hd_1 ?? 0,
                            'GANGGUAN_KONGNITIF'  => $request->rj_hd_2 ?? 0,
                            'FAKTOR_LINGKUNGAN'   => $request->rj_hd_3 ?? 0,
                            'RESPON'              => $request->rj_hd_4 ?? 0,
                            'PENGGUNAAN_OBAT'     => $request->rj_hd_5 ?? 0,
                            'TANGGAL'             => now(),
                            'OLEH'                => auth()->id(),
                            'STATUS'              => 1,
                        ]
                    );
                }

                // ==========================================
                // SKRINING RISIKO JATUH - MORSE
                // ==========================================
                DB::table('medicalrecord.penilaian_skala_morse')->updateOrInsert(
                    [
                        'KUNJUNGAN' => $request->NOKUNJ
                    ],
                    [
                        'RIWAYAT_JATUH' => $request->rj_sm_1 ?? 0,
                        'DIAGNOSIS'     => $request->rj_sm_2 ?? 0,
                        'ALAT_BANTU'    => $request->rj_sm_3 ?? 0,
                        'HEPARIN'       => $request->rj_sm_4 ?? 0,
                        'GAYA_BERJALAN' => $request->rj_sm_5 ?? 0,
                        'KESADARAN'     => $request->rj_sm_6 ?? 0,
                        'TANGGAL'       => now(),
                        'OLEH'          => auth()->id(),
                        'STATUS'        => 1,
                    ]
                );

                // ==========================================
                // SKRINING RISIKO JATUH - EPFRA
                // ==========================================
                if ($request->rj_epfra_usia != '') {
                    DB::table('medicalrecord.penilaian_epfra')->updateOrInsert(
                        [
                            'KUNJUNGAN' => $request->NOKUNJ
                        ],
                        [
                            'USIA'            => $request->rj_epfra_usia ?? 0,
                            'STATUS_MENTAL'   => $request->rj_epfra_1 ?? 0,
                            'ELIMINASI'       => $request->rj_epfra_2 ?? 0,
                            'MEDIKASI'        => $request->rj_epfra_3 ?? 0,
                            'DIAGNOSIS'       => $request->rj_epfra_4 ?? 0,
                            'AMBULASI'        => $request->rj_epfra_5 ?? 0,
                            'NUTRISI'         => $request->rj_epfra_6 ?? 0,
                            'GANGGUAN_TIDUR'  => $request->rj_epfra_7 ?? 0,
                            'RIWAYAT_JATUH'   => $request->rj_epfra_8 ?? 0,
                            'TANGGAL'         => now(),
                            'OLEH'            => auth()->id(),
                            'STATUS'          => 1,
                        ]
                    );
                }

                // ==========================================
                // SKRINING GIZI - MUST
                // ==========================================
                DB::table('medicalrecord.permasalahan_gizi')->updateOrInsert(
                    [
                        'KUNJUNGAN' => $request->NOKUNJ
                    ],
                    [
                        'BERAT_BADAN_SIGNIFIKAN' => $request->sgd1 ?? 0,
                        'PERUBAHAN_BERAT_BADAN'  => $request->sgd1_c ?? 0,
                        'INTAKE_MAKANAN'         => $request->sgd2 ?? 0,
                        'KONDISI_KHUSUS'         => $request->sgd3 ?? 0,
                        'SKOR'                   => $request->skor_sgd ?? 0,
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
                        'TAMPAK_KURUS'          => $request->sga1 ?? 0,
                        'PENURUNAN_BERAT_BADAN' => $request->sga2 ?? 0,
                        'DIARE_INTAKE_MAKANAN'  => $request->sga3 ?? 0,
                        'RESIKO_MALNUTRISI'     => $request->sga4 ?? 0,
                        'SKOR'                  => $request->skor_sga ?? 0,
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
                        'KASUS_OBSTETRI_GINEKOLOGI' => $request->kasus_obstetri_ginekologi ?? 0,
                        'STATUS_REPRODUKSI'          => $request->status_reproduksi ?? 0,
                        'HPHT'                       => $request->hpht ?? 0,
                        'SIKLUS'                     => $request->siklus ?? 0,
                        'KB'                         => $request->kb ?? 0,
                        'HAMIL_GRAVIDA'              => $request->hamil_gravida ?? 0,
                        'HAMIL_PARITAS'              => $request->hamil_paritas ?? 0,
                        'HAMIL_ABORTUS'              => $request->hamil_abortus ?? 0,
                        'TANGGAL'                    => now(),
                        'OLEH'                       => auth()->id(),
                        'STATUS'                     => 1,
                    ]
                );

                // ==========================================
                // MASALAH KEPERAWATAN
                // ==========================================
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
                        'MASALAH_LAIN'                  => $request->input('dmk_lain', '') ?? '',

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
                        'PASIEN_TINGGAL_SENDIRI'          => $request->input('dp_1', 0) ?? 0,
                        'PASIEN_KHAWATIR_KETIKA_DIRUMAH'  => $request->input('dp_2', 0) ?? 0,
                        'PASIEN_TAK_ADA_YANG_MERAWAT'     => $request->input('dp_3', 0) ?? 0,
                        'PASIEN_DILANTAI_ATAS'             => $request->input('dp_4', 0) ?? 0,
                        'PERAWATAN_LANJUTAN_PASIEN'        => $request->input('dp_5', 0) ?? 0,
                        'PENGAJUAN_PENDAMPINGAN_PASIEN'    => 0,

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
                        'PASIEN_PULANG'                         => $request->input('dp_6', 0) ?? 0,
                        'PASIEN_MENGAJUKAN'                     => $request->input('dp_7', 0) ?? 0,
                        'TIDAK_ADA_KRITERIA'                    => $request->input('dp_8', 0) ?? 0,

                        // ------------------------------------------
                        // Kebutuhan Pelayanan Berkelanjutan (KPB)
                        // ------------------------------------------
                        'KEBUTUHAN_PELAYANAN_BERKELANJUTAN_KPB' => $request->input('dp_9', 0) ?? 0,

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
                        'KEBUTUHAN_PELAYANAN_BERKELANJUTAN_LAINNYA' => $request->input('dp_5_lain', '') ?? '',

                        // ------------------------------------------
                        // Penggunaan alat medis / bantu
                        // ------------------------------------------
                        'PENGGUNAAN_ALAT_MEDIS_PAM'             => $request->input('dp_10', 0) ?? 0,

                        'PAM_KATETER_URIN'                      => $request->boolean('dp_10_1') ? 1 : 0,
                        'PAM_TRAECHOSTOMY'                      => $request->boolean('dp_10_2') ? 1 : 0,
                        'PAM_NGT'                               => $request->boolean('dp_10_3') ? 1 : 0,
                        'PAM_COLOSTOMY'                         => $request->boolean('dp_10_4') ? 1 : 0,
                        'PAM_LAINNYA'                           => $request->input('dp_10_lain', '') ?? '',

                        // ------------------------------------------
                        // Skrining lanjutan
                        // ------------------------------------------
                        'SKRINING_LANJUTAN'                     => $request->input('dp_11', 0) ?? 0,

                        // 1 = Konsul MPP
                        // 2 = Edukasi
                        'SKRINING'                              => $request->input('dp_11_skrining', 0) ?? 0,

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
}
