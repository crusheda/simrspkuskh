<?php

namespace App\Http\Controllers\EMR\Form\GawatDarurat;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
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

        $data = [
            'kunjungan' => $kunjungan,
            'tingkat_kesadaran' => $tingkat_kesadaran,
            'cara_keluar' => $cara_keluar,
            'keadaan_keluar' => $keadaan_keluar,
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
                        ->where('STATUS', 1)
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
            DB::beginTransaction();

            try {

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
                        'OLEH'          => auth()->id(),
                        'STATUS'        => 1,
                        'TANGGAL'       => now()
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
