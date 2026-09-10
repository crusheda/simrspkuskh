<?php

namespace App\Http\Controllers\EMR\Form\RawatJalan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth, Storage;

class PengkajianRawatJalanJiwaController extends Controller
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
        return view('pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.jiwa.index')->with('list',$data);
    }

    function simpanFormDokterRJJ(Request $request)
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

            // Tindak Lanjut Rawat Jalan
            DB::table('medicalrecord.tindak_lanjut_pengkajian')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    // Tindak Lanjut
                    // 2 = Pulang
                    // 1 = MRS
                    'TINDAK_LANJUT' => $request->tl,

                    // Rujukan
                    // 1 = Ahli Gizi
                    // 2 = Rehabilitasi Medik
                    // 3 = Klinik Spesialis
                    // 4 = Lainnya
                    'RUJUKAN'          => $request->rujuk,
                    'RUJUKAN_LAINNYA'  => $request->rujuk_lainnya,

                    'OLEH'    => auth()->id(),
                    'STATUS'  => 1,
                    'TANGGAL' => now()
                ]
            );

            if($request->tl == 1){

                // Ambil nomor terakhir
                $lastNomor = DB::table('medicalrecord.perencanaan_rawat_inap')
                    ->orderByDesc('ID')
                    ->value('NOMOR');

                // Jika belum ada data, mulai dari 1
                $nomor = $lastNomor ? str_pad(((int)$lastNomor + 1), 6, '0', STR_PAD_LEFT) : '000001';

                // Perencanaan Rawat Inap
                DB::table('medicalrecord.perencanaan_rawat_inap')->updateOrInsert(
                    [
                        'KUNJUNGAN' => $request->NOKUNJ
                    ],
                    [
                        'NOMOR'                 => $nomor,
                        'NOMOR_REFERENSI'       => '',
                        'JENIS_RUANG_PERAWATAN' => $request->pri_ruang,
                        'JENIS_PERAWATAN'       => $request->pri_perawatan,
                        'INDIKASI'              => $request->pri_indikasi,
                        'DESKRIPSI'            => $request->pri_ket,
                        'DOKTER'                => $request->pri_dpjp,
                        'OLEH'                  => auth()->id(),
                        'STATUS'                => 1,
                        'TANGGAL'               => now()
                    ]
                );
            }

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

    public function getFormDokterRJJ($kunjungan)
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

        // Riwayat Terapi
        $assesment = DB::table('medicalrecord.sirmed_assesment')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        if ($assesment) {
            $data['tu'] = $assesment->TOLAK_UKUR;
            $data['eval'] = $assesment->EVALUASI;
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

        // Tindak Lanjut Pengkajian
        $tindak_lanjut = DB::table('medicalrecord.tindak_lanjut_pengkajian')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();
        if ($tindak_lanjut) {
            $data['tl'] = $tindak_lanjut->TINDAK_LANJUT;
            $data['rujuk'] = $tindak_lanjut->RUJUKAN;
            $data['rujuk_lainnya'] = $tindak_lanjut->RUJUKAN_LAINNYA;
        }

        // Perencanaan Rawat Inap
        $perencanaan = DB::table('medicalrecord.perencanaan_rawat_inap')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        if ($perencanaan) {
            $data['pri_ruang'] = $perencanaan->JENIS_RUANG_PERAWATAN;
            $data['pri_perawatan'] = $perencanaan->JENIS_PERAWATAN;
            $data['pri_indikasi'] = $perencanaan->INDIKASI;
            $data['pri_ket'] = $perencanaan->DESKRIPSI;
            $data['pri_dpjp'] = $perencanaan->DOKTER;
        }
        // dd($data);

        return response()->json($data);
    }

    function simpanFormPerawatRJJ(Request $request)
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

            // ==========================
            // MASALAH KEPERAWATAN JIWA
            // ==========================
            DB::table('medicalrecord.masalah_keperawatan')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'JIWA_ANSIETAS' => $request->input('diag_jiwa_1') ? 1 : 0,
                    'JIWA_DEFISIT_PENGETAHUAN' => $request->input('diag_jiwa_2') ? 1 : 0,
                    'JIWA_RISIKO_PERILAKU_KEKERASAN' => $request->input('diag_jiwa_3') ? 1 : 0,
                    'JIWA_DEFISIT_PERAWATAN_DIRI' => $request->input('diag_jiwa_4') ? 1 : 0,
                    'JIWA_HARGA_DIRI_RENDAH' => $request->input('diag_jiwa_5') ? 1 : 0,
                    'JIWA_ISOLASI_SOSIAL' => $request->input('diag_jiwa_6') ? 1 : 0,
                    'JIWA_KEPUTUSASAAN' => $request->input('diag_jiwa_7') ? 1 : 0,
                    'JIWA_KOPING_TIDAK_EFEKTIF' => $request->input('diag_jiwa_8') ? 1 : 0,
                    'JIWA_WAHAM' => $request->input('diag_jiwa_9') ? 1 : 0,
                    'JIWA_PERILAKU_KEKERASAN' => $request->input('diag_jiwa_10') ? 1 : 0,
                    'JIWA_GANGGUAN_PERSEPSI_SENSORI' => $request->input('diag_jiwa_11') ? 1 : 0,
                    'JIWA_TINDAKAN_RELAKSASI' => $request->input('tin_jiwa_1') ? 1 : 0,
                    'JIWA_TINDAKAN_BINA_HUBUNGAN_SALING_PERCAYA' => $request->input('tin_jiwa_2') ? 1 : 0,
                    'JIWA_TINDAKAN_DISKUSI_PASIEN_KELUARGA' => $request->input('tin_jiwa_3') ? 1 : 0,
                    'JIWA_TINDAKAN_STRATEGI_PELAKSANAAN' => $request->input('tin_jiwa_4') ? 1 : 0,
                    'DIAGNOSA_LAIN' => $request->input('diag_lain') ? 1 : 0,
                    // Gunakan field yang SUDAH ADA
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

    public function getFormPerawatRJJ($kunjungan)
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
        // MAPPING FIELD MASALAH KEPERAWATAN JIWA
        // ======================================================
        $masalah = DB::table('medicalrecord.masalah_keperawatan')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        $diag_jiwa_field = [

            'diag_jiwa_1'  => 'JIWA_ANSIETAS',
            'diag_jiwa_2'  => 'JIWA_DEFISIT_PENGETAHUAN',
            'diag_jiwa_3'  => 'JIWA_RISIKO_PERILAKU_KEKERASAN',
            'diag_jiwa_4'  => 'JIWA_DEFISIT_PERAWATAN_DIRI',
            'diag_jiwa_5'  => 'JIWA_HARGA_DIRI_RENDAH',

            'diag_jiwa_6'  => 'JIWA_ISOLASI_SOSIAL',
            'diag_jiwa_7'  => 'JIWA_KEPUTUSASAAN',
            'diag_jiwa_8'  => 'JIWA_KOPING_TIDAK_EFEKTIF',
            'diag_jiwa_9'  => 'JIWA_WAHAM',
            'diag_jiwa_10' => 'JIWA_PERILAKU_KEKERASAN',
            'diag_jiwa_11' => 'JIWA_GANGGUAN_PERSEPSI_SENSORI',
        ];

        $tin_jiwa_field = [
            'tin_jiwa_1' => 'JIWA_TINDAKAN_RELAKSASI',
            'tin_jiwa_2' => 'JIWA_TINDAKAN_BINA_HUBUNGAN_SALING_PERCAYA',
            'tin_jiwa_3' => 'JIWA_TINDAKAN_DISKUSI_PASIEN_KELUARGA',
            'tin_jiwa_4' => 'JIWA_TINDAKAN_STRATEGI_PELAKSANAAN',
        ];

        // ======================================================
        // JIKA DATA SUDAH ADA
        // ======================================================
        if ($masalah) {

            // --------------------------
            // Masalah Keperawatan Jiwa
            // --------------------------
            foreach ($diag_jiwa_field as $key => $column) {

                $data[$key] = (int) $masalah->$column;
            }

            foreach ($tin_jiwa_field as $key => $column) {
                $data[$key] = (int) $masalah->$column;
            }

            // --------------------------
            // Detail Terapi
            // --------------------------
            $data['tin_6'] = $masalah->TERAPI_ORAL;
            $data['tin_7'] = $masalah->TERAPI_IV_SC_IM;
            $data['terapi_oral'] = $masalah->TERAPI_ORAL_DETAIL;
            $data['terapi_iv'] = $masalah->TERAPI_IV_SC_IM_DETAIL;
        }

        return response()->json($data);
    }
}
