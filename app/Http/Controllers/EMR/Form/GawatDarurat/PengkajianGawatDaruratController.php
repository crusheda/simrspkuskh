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
            'keadaan_keluar' => $keadaan_keluar,
            'frekuensi_obat' => $frekuensi_obat,
            'rute_obat' => $rute_obat
        ];

        return view('pages.v2.medicalrecord.detail.form.pengkajian.gawat-darurat.index')->with('list',$data);
    }

    // FORM DOKTET
        function getFormDokter($KUNJUNGAN)
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

            // $data = DB::table('pendaftaran.kunjungan AS pk')
            //     ->leftJoin('simrspku_klaim.emr_form_terapi as eft', function($join){
            //         $join->on('eft.id_cppt','=','cppt.ID')
            //             ->whereNull('eft.deleted_at')
            //             ->where('eft.status',1);
            //     })
            //     ->join('pendaftaran.pendaftaran as pf', function($join) use ($RM) {
            //         $join->on('pf.NOMOR','=','kj.NOPEN')
            //             ->where('pf.NORM', $RM);
            //     })
            //     ->leftJoin('pendaftaran.penjamin AS pj','pj.NOPEN','=','pf.NOMOR')
            //     ->where('pk.NOMOR', $KUNJUNGAN)
            //     ->select(
            //         'pf.TANGGAL as TGLPENDAFTARAN',
            //         'ru.DESKRIPSI AS NAMARUANGAN',
            //         'cppt.ID AS ID_CPPT',
            //         'cppt.JENIS AS JENIS_CPPT',
            //         'cppt.KUNJUNGAN',
            //         'cppt.TANGGAL',
            //         'cppt.SUBYEKTIF',
            //         'cppt.OBYEKTIF',
            //         'cppt.ASSESMENT',
            //         'cppt.PLANNING',
            //         'cppt.INSTRUKSI',
            //         DB::raw('IF(eft.id IS NULL, 0, 1) as IS_TERAPI'),
            //         DB::raw('IF(ekf.id IS NULL, 0, 1) as IS_KFR'),
            //         DB::raw('master.getNamaLengkapPegawai(pg.NIP) AS NAMADOKTER'),
            //         DB::raw('master.getNamaLengkapPegawai(dpjp.NIP) AS NAMADPJP'),
            //         DB::raw('master.getNamaLengkapPegawai(pe.NIP) AS NAMAUSER'),
            //     )
            //     ->orderBy('cppt.TANGGAL', 'DESC')
            //     ->get();

            // if ($data->isEmpty()) {
            //     return response()->json([
            //         'status' => false,
            //         'message'=> 'Data CPPT tidak ditemukan untuk kunjungan ini'
            //     ]);
            // }

            $data = [
                'triage' => $triage,
                'anamnesis_diperoleh' => $anamnesis_diperoleh
            ];

            return response()->json([
                'status' => true,
                'data' => $data,
            ]);
        }

        function simpanFormDokter(Request $request)
        {
        $validator = Validator::make(
            $request->all(),
            [
                'NOKUNJ'    => 'required',
            ],
            [
                'NOKUNJ.required'        => 'Kunjungan wajib diisi.',
            ]
        );

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message'=> $validator->errors()->first()
                ], 422);
            }

            DB::beginTransaction();

            try {
                $getDataDokter = DB::table('master.dokter as dr')
                            ->leftJoin('aplikasi.pengguna as pe', function($join) {
                                $join->on('pe.NIP', '=', 'dr.NIP')
                                    ->where('pe.STATUS', '=', 1);
                            })
                            ->select('dr.ID')
                            ->where('pe.ID', auth()->id())
                            ->where('dr.STATUS', 1)
                            ->first();

                $getDataKunjungan = DB::table('pendaftaran.kunjungan as pk')
                                    ->join('pendaftaran.pendaftaran as pp', 'pp.NOMOR','=','pk.NOPEN')
                                    ->select('pp.NORM', 'pp.NOMOR as NOPEN')
                                    ->where('pk.NOMOR', $request->NOKUNJ)
                                    ->first();

                // ==========================
                // TRIAGE
                // ==========================
                DB::table('medicalrecord.triage')->updateOrInsert(
                    [
                        'NORM'      => $getDataKunjungan->NORM,
                        'KUNJUNGAN' => $request->NOKUNJ,
                        'NOPEN'     => $getDataKunjungan->NOPEN,
                    ],
                    [
                        'KRITERIA'      => $request->ats,
                        'RISIKO_PENULARAN_INFEKSI' => $request->rpi,
                        'DOKTER_ID'     => $getDataDokter->ID ?? 0,
                        'OLEH'          => auth()->id(),
                        'STATUS'        => 2,
                        'TANGGAL'       => now()
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
                DB::table('medicalrecord.tanda-vital')->updateOrInsert(
                    [
                        'KUNJUNGAN' => $request->NOKUNJ
                    ],
                    [
                        'KEADAAN_UMUM'          => $request->keu,
                        'KESADARAN'             => "",
                        'SISTOLIK'              => $request->td_up,
                        'DIASTOLIK'             => $request->td_down,
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
                    DB::table('medicalrecord.riwayat_penyakit_sekarang')->updateOrInsert(
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
                            'PENDAFTARAN'     => $getDataKunjungan->NOPEN
                        ],
                        [
                            'DESKRIPSI'     => $request->pf,
                            'TANGGAL'       => now(),
                            'OLEH'          => auth()->id(),
                            'STATUS'        => 1,
                        ]
                    );
                    // RIWAYAT ALERGI (TABEL RIWAYAT_ALERGI)
                    DB::table('medicalrecord.riwayat_alergi')->updateOrInsert(
                        [
                            'KUNJUNGAN'     => $request->NOKUNJ
                        ],
                        [
                            'JENIS'  => $request->ra_cb, // REF JENIS = 180
                            'DESKRIPSI'     => $request->ra,
                            'TANGGAL'       => now(),
                            'OLEH'          => auth()->id(),
                            'STATUS'        => 1,
                        ]
                    );

                // DB::rollBack();
                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Berhasil disimpan'
                ]);

            } catch (\Exception $e) {

                DB::rollBack();

                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage()
                ], 500);

            }
        }


    function simpanFormPerawat(Request $request)
    {
        print_r($request->all());
        die();
    }
}
