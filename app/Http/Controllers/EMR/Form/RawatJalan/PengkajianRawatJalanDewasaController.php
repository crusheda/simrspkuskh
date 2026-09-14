<?php

namespace App\Http\Controllers\EMR\Form\RawatJalan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth, Storage;

class PengkajianRawatJalanDewasaController extends Controller
{
    function index($kunjungan)
    {
        $jenis_ruang = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',242)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $jenis_perawatan = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',243)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $pasien = DB::table('pendaftaran.kunjungan AS pk')
            ->leftJoin('pendaftaran.pendaftaran AS pd', 'pd.NOMOR', '=', 'pk.NOPEN')
            ->leftJoin('master.pasien AS p', 'p.NORM', '=', 'pd.NORM')
            ->leftJoin('master.referensi AS ag', function ($join) {
                $join->on('ag.ID', '=', 'p.AGAMA')
                    ->where('ag.JENIS', '=', '1');
            })
            ->leftJoin('master.referensi AS kj', function ($join) {
                $join->on('kj.ID', '=', 'p.PEKERJAAN')
                    ->where('kj.JENIS', '=', '4');
            })
            ->leftJoin('master.dokter AS dok', 'dok.ID', '=', 'pk.DPJP')
            ->select('dok.ID', DB::raw('master.getNamaLengkapPegawai(dok.NIP) AS NAMADOKTER'), 'ag.DESKRIPSI AS AGAMA', 'kj.DESKRIPSI AS PEKERJAAN')
            ->where('pk.NOMOR', $kunjungan)
            ->first();

        // $frekuensi_obat = DB::table('master.frekuensi_aturan_resep')
        //         ->select('ID','FREKUENSI')
        //         ->where('STATUS',1)
        //         ->orderBy('ID','ASC')
        //         ->get();

        // $rute_obat = DB::table('master.referensi')
        //         ->select('ID','DESKRIPSI')
        //         ->where('JENIS',217)
        //         ->where('STATUS',1)
        //         ->orderBy('TABEL_ID','ASC')
        //         ->get();

        $jenis_alergi = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',180)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $kesadaran = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',179)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $data = [
            'kunjungan' => $kunjungan,
            'jenis_ruang' => $jenis_ruang,
            'jenis_perawatan' => $jenis_perawatan,
            'pasien' => $pasien,
            'jenis_alergi' => $jenis_alergi,
            'kesadaran' => $kesadaran,
        ];
        // print_r($data);
        // die();
        return view('pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.dewasa.index')->with('list',$data);
    }

    function simpanFormDokterRJD(Request $request)
    {
        // print_r($request->all());
        // die();

        DB::beginTransaction();

        try {

            // Riwayat Pemeriksaan Fisik
            DB::table('medicalrecord.pemeriksaan_fisik')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'PENDAFTARAN'  => DB::table('pendaftaran.kunjungan')->where('NOMOR', $request->NOKUNJ)->value('NOPEN'),
                    'DESKRIPSI'    => $request->pfisik,
                    'OLEH'         => auth()->id(),
                    'STATUS'       => 1,
                    'TANGGAL'      => now()
                ]
            );

            // Rencana Terapi
            DB::table('medicalrecord.rencana_terapi')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'DESKRIPSI'    => $request->terapi_tind,
                    'OLEH'         => auth()->id(),
                    'STATUS'       => 1,
                    'TANGGAL'      => now()
                ]
            );

            // ASSESMENT
            DB::table('medicalrecord.sirmed_assesment')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'TOLAK_UKUR'   => $request->tu,
                    'OLEH'         => auth()->id(),
                    'STATUS'       => 1,
                    'TANGGAL'      => now()
                ]
            );

            // Edukasi Rawat Jalan
            DB::table('medicalrecord.edukasi_rajal')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    // Materi Edukasi
                    'ME_TANDA_GEJALA'            => $request->input('me_1') ? 1 : 0,
                    'ME_HASIL_PEMERIKSAAN'       => $request->input('me_2') ? 1 : 0,
                    'ME_DIAGNOSIS'               => $request->input('me_3') ? 1 : 0,
                    'ME_RENCANA_PENATALAKSANAAN' => $request->input('me_4') ? 1 : 0,
                    'ME_TINDAKAN_TUJUAN_TERAPI'         => $request->input('me_5') ? 1 : 0,

                    // Sarana Informasi Edukasi
                    'SIE_LEAFLET' => $request->input('sie_1') ? 1 : 0,
                    'SIE_LISAN'   => $request->input('sie_2') ? 1 : 0,

                    // Evaluasi
                    'EVAL_SUDAH_MENGERTI' => $request->input('eval_1') ? 1 : 0,
                    'EVAL_RE_EDUKASI'     => $request->input('eval_2') ? 1 : 0,

                    'OLEH'    => auth()->id(),
                    'STATUS'  => 1,
                    'TANGGAL' => now()
                ]
            );

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

    public function getFormDokterRJD($kunjungan)
    {
        $data = [];

        // Riwayat Pemeriksaan Fisik
        $pemeriksaan_fisik = DB::table('medicalrecord.pemeriksaan_fisik')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        if ($pemeriksaan_fisik) {
            $data['pfisik'] = $pemeriksaan_fisik->DESKRIPSI;
        }

        // Riwayat Terapi
        $rencana_terapi = DB::table('medicalrecord.rencana_terapi')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        if ($rencana_terapi) {
            $data['terapi_tind'] = $rencana_terapi->DESKRIPSI;
        }

        // Riwayat Terapi
        $assesment = DB::table('medicalrecord.sirmed_assesment')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        if ($assesment) {
            $data['tu'] = $assesment->TOLAK_UKUR;
        }

        // Edukasi Rawat Jalan
        $edukasi = DB::table('medicalrecord.edukasi_rajal')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        if ($edukasi) {
            $data['me_1'] = $edukasi->ME_TANDA_GEJALA;
            $data['me_2'] = $edukasi->ME_HASIL_PEMERIKSAAN;
            $data['me_3'] = $edukasi->ME_DIAGNOSIS;
            $data['me_4'] = $edukasi->ME_RENCANA_PENATALAKSANAAN;
            $data['me_5'] = $edukasi->ME_TINDAKAN_TUJUAN_TERAPI;

            $data['sie_1'] = $edukasi->SIE_LEAFLET;
            $data['sie_2'] = $edukasi->SIE_LISAN;

            $data['eval_1'] = $edukasi->EVAL_SUDAH_MENGERTI;
            $data['eval_2'] = $edukasi->EVAL_RE_EDUKASI;
        }


        // dd($data);

        return response()->json($data);
    }

    function simpanFormPerawatRJD(Request $request)
    {
        // print_r($request->all());
        // die();

        DB::beginTransaction();

        try {

            // TANDA VITAL
            DB::table('medicalrecord.tanda_vital')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'KELUHAN_UTAMA'=> $request->anm_ku,
                    'WAKTU_PEMERIKSAAN' => now(),

                    'OLEH'         => auth()->id(),
                    'STATUS'       => 1,
                    'TANGGAL'      => now(),
                ]
            );

            // EDUKASI PASIEN DAN KELUARGA
            DB::table('medicalrecord.edukasi_pasien_keluarga')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    // Edukasi awal
                    'KESEDIAAN' => $request->edu_1 ?? 0,
                    'HAMBATAN' => $request->edu_2 ?? 0,
                    'PENERJEMAH' => $request->edu_3 ?? 0,

                    // Kebutuhan Edukasi
                    'EDUKASI_DIAGNOSA' => $request->kb_edu_1 ?? 0,
                    'EDUKASI_REHAB_MEDIK' => $request->kb_edu_2 ?? 0,
                    'EDUKASI_HKP' => $request->kb_edu_3 ?? 0,

                    'EDUKASI_PEMBERIAN_INFORMED_CONSENT' => $request->kb_edu_4 ?? 0,

                    'EDUKASI_CUCI_TANGAN' => $request->kb_edu_5 ?? 0,
                    'EDUKASI_PERENCANAAN_PULANG' => $request->kb_edu_6 ?? 0,

                    'EDUKASI_OBAT' => $request->kb_edu_7 ?? 0,
                    'EDUKASI_NYERI' => $request->kb_edu_8 ?? 0,
                    'EDUKASI_HAK_BERPARTISIPASI' => $request->kb_edu_9 ?? 0,

                    'EDUKASI_PENUNDAAN_PELAYANAN' => $request->kb_edu_10 ?? 0,
                    'EDUKASI_BAHAYA_MEROKO' => $request->kb_edu_11 ?? 0,

                    'EDUKASI_NUTRISI' => $request->kb_edu_13 ?? 0,
                    'EDUKASI_PENGGUNAAN_ALAT' => $request->kb_edu_14 ?? 0,
                    'EDUKASI_PROSEDURE_PENUNJANG' => $request->kb_edu_15 ?? 0,

                    'EDUKASI_KELAMBATAN_PELAYANAN' => $request->kb_edu_16 ?? 0,
                    'EDUKASI_RUJUKAN_PASIEN' => $request->kb_edu_17 ?? 0,

                    // Lainnya
                    'STATUS_LAIN' => $request->kb_edu_12 ?? 0,
                    'DESKRIPSI_LAINYA' => $request->kb_edu_lain,

                    'OLEH' => auth()->id(),
                    'STATUS' => 1,
                    'TANGGAL' => now(),
                ]
            );

            // MASALAH KEPERAWATAN
            DB::table('medicalrecord.masalah_keperawatan')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'BERSIHAN_JALAN_NAFAS_TIDAK_EFEKTIF' => $request->input('diag_1') ? 1 : 0,
                    'POLA_NAFAS_TIDAK_EFEKTIF' => $request->input('diag_2') ? 1 : 0,
                    'PERFUSI_PERIFER_TIDAK_EFEKTIF' => $request->input('diag_3') ? 1 : 0,
                    'DIARE' => $request->input('diag_4') ? 1 : 0,
                    'NYERI_AKUT' => $request->input('diag_5') ? 1 : 0,
                    'NAUSEA' => $request->input('diag_6') ? 1 : 0,
                    'HIPERTERMI' => $request->input('diag_7') ? 1 : 0,
                    'ANSIETAS' => $request->input('diag_8') ? 1 : 0,

                    'GANGGUAN_INTEGRITAS_KULIT_JARINGAN' => $request->input('diag_9') ? 1 : 0,
                    'GANGGUAN_ELIMINASI_URINE' => $request->input('diag_10') ? 1 : 0,
                    'INTOLERANSI_AKTIVITAS' => $request->input('diag_11') ? 1 : 0,
                    'GANGGUAN_MOBILITAS_FISIK' => $request->input('diag_12') ? 1 : 0,
                    'GANGGUAN_PERTUKARAN_GAS' => $request->input('diag_13') ? 1 : 0,
                    'DIAGNOSA_LAIN' => $request->input('diag_lain') ? 1 : 0,

                    'TINDAKAN_RELAKSASI_NAFAS_DALAM' => $request->input('tin_1') ? 1 : 0,
                    'TINDAKAN_BODY_ALIGNMENT' => $request->input('tin_2') ? 1 : 0,

                    'TINDAKAN_TENANGKAN_PASIEN' => $request->input('tin_3') ? 1 : 0,
                    'TINDAKAN_PENDIDIKAN_KESEHATAN' => $request->input('tin_4') ? 1 : 0,
                    'TINDAKAN_RAWAT_LUKA' => $request->input('tin_5') ? 1 : 0,

                    'TERAPI_ORAL' => $request->input('tin_6') ? 1 : 0,
                    'TERAPI_ORAL_DETAIL' => $request->terapi_oral,

                    'TERAPI_IV_SC_IM' => $request->input('tin_7') ? 1 : 0,
                    'TERAPI_IV_SC_IM_DETAIL' => $request->terapi_iv,

                    'OLEH' => auth()->id(),
                    'STATUS' => 1,
                    'TANGGAL' => now(),
                ]
            );

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

    public function getFormPerawatRJD($kunjungan)
    {
        $data = [];

        // ======================================================
        // TANDA VITAL
        // ======================================================
        $tanda_vital = DB::table('medicalrecord.tanda_vital')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        if ($tanda_vital) {

            $data['anm_ku']      = $tanda_vital->KELUHAN_UTAMA;
        }
        // dd($tanda_vital);

        // ======================================================
        // EDUKASI PASIEN DAN KELUARGA
        // ======================================================
        $edukasi_pk = DB::table('medicalrecord.edukasi_pasien_keluarga')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        if ($edukasi_pk) {

            // Edukasi awal
            $data['edu_1'] = $edukasi_pk->KESEDIAAN;
            $data['edu_2'] = $edukasi_pk->HAMBATAN;
            $data['edu_3'] = $edukasi_pk->PENERJEMAH;


            // Kebutuhan edukasi
            $data['edukasi_diagnosa'] = $edukasi_pk->EDUKASI_DIAGNOSA;
            $data['edukasi_rehab_medik'] = $edukasi_pk->EDUKASI_REHAB_MEDIK;
            $data['edukasi_hkp'] = $edukasi_pk->EDUKASI_HKP;

            $data['edukasi_informed_consent'] = $edukasi_pk->EDUKASI_PEMBERIAN_INFORMED_CONSENT;

            $data['edukasi_cuci_tangan'] = $edukasi_pk->EDUKASI_CUCI_TANGAN;
            $data['edukasi_perencanaan_pulang'] = $edukasi_pk->EDUKASI_PERENCANAAN_PULANG;

            $data['edukasi_obat'] = $edukasi_pk->EDUKASI_OBAT;
            $data['edukasi_nyeri'] = $edukasi_pk->EDUKASI_NYERI;
            $data['edukasi_hak_partisipasi'] = $edukasi_pk->EDUKASI_HAK_BERPARTISIPASI;

            $data['edukasi_penundaan'] = $edukasi_pk->EDUKASI_PENUNDAAN_PELAYANAN;
            $data['edukasi_bahaya_merokok'] = $edukasi_pk->EDUKASI_BAHAYA_MEROKO;

            $data['edukasi_nutrisi'] = $edukasi_pk->EDUKASI_NUTRISI;
            $data['edukasi_penggunaan_alat'] = $edukasi_pk->EDUKASI_PENGGUNAAN_ALAT;
            $data['edukasi_prosedure'] = $edukasi_pk->EDUKASI_PROSEDURE_PENUNJANG;

            $data['edukasi_keterlambatan'] = $edukasi_pk->EDUKASI_KELAMBATAN_PELAYANAN;
            $data['edukasi_rujukan'] = $edukasi_pk->EDUKASI_RUJUKAN_PASIEN;


            // Lainnya
            $data['status_lain'] = $edukasi_pk->STATUS_LAIN;
            $data['kb_edu_lain'] = $edukasi_pk->DESKRIPSI_LAINYA;
        }

        // dd($edukasi_pk);

        // ======================================================
        // MASALAH KEPERAWATAN
        // ======================================================
        $masalah = DB::table('medicalrecord.masalah_keperawatan')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();


        $diag_field = [
            'diag_1'  => 'BERSIHAN_JALAN_NAFAS_TIDAK_EFEKTIF',
            'diag_2'  => 'POLA_NAFAS_TIDAK_EFEKTIF',
            'diag_3'  => 'PERFUSI_PERIFER_TIDAK_EFEKTIF',
            'diag_4'  => 'DIARE',
            'diag_5'  => 'NYERI_AKUT',
            'diag_6'  => 'NAUSEA',
            'diag_7'  => 'HIPERTERMI',
            'diag_8'  => 'ANSIETAS',

            'diag_9'  => 'GANGGUAN_INTEGRITAS_KULIT_JARINGAN',
            'diag_10' => 'GANGGUAN_ELIMINASI_URINE',
            'diag_11' => 'INTOLERANSI_AKTIVITAS',
            'diag_12' => 'GANGGUAN_MOBILITAS_FISIK',
            'diag_13' => 'GANGGUAN_PERTUKARAN_GAS',
            'diag_lain' => 'DIAGNOSA_LAIN',

            'tin_1' => 'TINDAKAN_RELAKSASI_NAFAS_DALAM',
            'tin_2' => 'TINDAKAN_BODY_ALIGNMENT',
            'tin_3' => 'TINDAKAN_TENANGKAN_PASIEN',
            'tin_4' => 'TINDAKAN_PENDIDIKAN_KESEHATAN',
            'tin_5' => 'TINDAKAN_RAWAT_LUKA',

            'tin_6' => 'TERAPI_ORAL',
            'tin_7' => 'TERAPI_IV_SC_IM',
        ];

        if ($masalah) {

            foreach ($diag_field as $key => $column) {
                $data[$key] = $masalah->$column;
            }

            $data['terapi_oral'] = $masalah->TERAPI_ORAL_DETAIL;
            $data['terapi_iv'] = $masalah->TERAPI_IV_SC_IM_DETAIL;
        }

        return response()->json($data);
    }

}
