<?php

namespace App\Http\Controllers\EMR\Form\RawatJalan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth, Storage;

class PengkajianRawatJalanAnakController extends Controller
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
        return view('pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.anak.index')->with('list',$data);
    }

    function simpanFormDokterRJA(Request $request)
    {
        // print_r($request->all());
        // die();

        DB::beginTransaction();

        try {

            // ANAMNESIS DIPEROLEH
            DB::table('medicalrecord.anamnesis_diperoleh')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'AUTOANAMNESIS' => ($request->anam == 1) ? 1 : 0,
                    'ALLOANAMNESIS' => ($request->anam == 2) ? 1 : 0,
                    'DARI'          => $request->anamnesis_oleh,
                    'OLEH'          => auth()->id(),
                    'STATUS'        => 1,
                    'TANGGAL'       => now()
                ]
            );

            // KELUHAN UTAMA
            DB::table('medicalrecord.keluhan_utama')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'DESKRIPSI'    => $request->keluhan_utama,
                    'SNOMED_CT_ID' => 0,
                    'OLEH'         => auth()->id(),
                    'STATUS'       => 1,
                    'TANGGAL'      => now()
                ]
            );

            // Riwayat Penyakit Sekarang
            DB::table('medicalrecord.anamnesis')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'PENDAFTARAN'  => DB::table('pendaftaran.kunjungan')->where('NOMOR', $request->NOKUNJ)->value('NOPEN'),
                    'DESKRIPSI'    => $request->rps,
                    'SNOMED_CT_ID' => 0,
                    'OLEH'         => auth()->id(),
                    'STATUS'       => 1,
                    'TANGGAL'      => now()
                ]
            );

            // Riwayat Penyakit Dahulu
            DB::table('medicalrecord.rpp')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'DESKRIPSI'    => $request->rpd,
                    'SNOMED_CT_ID' => 0,
                    'OLEH'         => auth()->id(),
                    'STATUS'       => 1,
                    'TANGGAL'      => now()
                ]
            );

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
                        'DESKRIPSI'             => $request->pri_ket,
                        'DOKTER'                => $request->pri_dpjp,
                        'OLEH'                  => auth()->id(),
                        'STATUS'                => 1,
                        'TANGGAL'               => now()
                    ]
                );
            };

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

    public function getFormDokterRJA($kunjungan)
    {
        $data = [];

        // Riwayat Pemeriksaan Fisik
        $pemeriksaan_fisik = DB::table('medicalrecord.pemeriksaan_fisik')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        $data['pfisik'] = '';

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

    function simpanFormPerawatRJA(Request $request)
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
                    'KELUHAN_UTAMA' => $request->anm_ku,
                    'WAKTU_PEMERIKSAAN' => now(),

                    'OLEH'         => auth()->id(),
                    'STATUS'       => 1,
                    'TANGGAL'      => now(),
                ]
            );

            DB::table('medicalrecord.riwayat_perinatal')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'LAMA_HAMIL'      => $request->rp_lama_hamil,
                    'SATUAN'          => $request->rp_satuan,
                    'KOMPLIKASI'      => $request->rp_komplikasi,
                    'KOMPLIKASI_KET'  => $request->rp_komplikasi_des,
                    'PERSALINAN'      => $request->rp_persalinan,
                    'PENYULIT'        => $request->rp_penyulit,
                    'PENYULIT_KET'    => $request->rp_penyulit_des,
                    'OLEH'            => auth()->id(),
                    'STATUS'          => 1,
                    'TANGGAL'         => now(),
                ]
            );

            // ==========================
            // Riwayat Tumbuh Kembang
            // ==========================
            DB::table('medicalrecord.riwayat_tumbuh_kembang')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'LK_LAHIR'      => $request->lk_lahir,
                    'BB_LAHIR'      => $request->bb_lahir,
                    'TB_LAHIR'      => $request->tb_lahir,

                    'ASI_SAMPAI'    => $request->asi_sampai,
                    'ASI_SATUAN'    => $request->asi_satuan,

                    'SUFOR_MULAI'   => $request->sufor_mulai,
                    'SUFOR_SATUAN'  => $request->sufor_satuan,

                    'MPASI_MULAI'   => $request->mpasi_mulai,
                    'MPASI_SATUAN'  => $request->mpasi_satuan,

                    'TENGKURAP'     => $request->tengkurap,
                    'DUDUK'         => $request->duduk,
                    'MERANGKAK'     => $request->merangkak,
                    'BERDIRI'       => $request->berdiri,
                    'BERJALAN'      => $request->berjalan,

                    'NEONATUS'      => $request->neonatus,
                    'NEONATUS_KET'  => $request->neonatus_ket,

                    'KELUHAN'       => $request->keluhan_tumbuh_kembang,

                    'IMUNISASI'     => $request->imunisasi,
                    'IMUNISASI_LAIN'=> $request->imunisasi_lain,

                    'OLEH'          => auth()->id(),
                    'STATUS'        => 1,
                    'TANGGAL'       => now()
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

    public function getFormPerawatRJA($kunjungan)
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

        // ==========================
        // Riwayat Perinatal
        // ==========================
        $perinatal = DB::table('medicalrecord.riwayat_perinatal')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first();

        if ($perinatal) {

            $data['rp_lama_hamil'] = $perinatal->LAMA_HAMIL;
            $data['rp_satuan'] = $perinatal->SATUAN;

            $data['rp_komplikasi'] = $perinatal->KOMPLIKASI;
            $data['rp_komplikasi_ket'] = $perinatal->KOMPLIKASI_KET;

            $data['rp_persalinan'] = $perinatal->PERSALINAN;

            $data['rp_penyulit'] = $perinatal->PENYULIT;
            $data['rp_penyulit_ket'] = $perinatal->PENYULIT_KET;
        }

        // ==========================
        // Riwayat Tumbuh Kembang
        // ==========================
        $tumbuh = DB::table('medicalrecord.riwayat_tumbuh_kembang')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first();

        if ($tumbuh) {

            $data['lk_lahir'] = $tumbuh->LK_LAHIR;
            $data['bb_lahir'] = $tumbuh->BB_LAHIR;
            $data['tb_lahir'] = $tumbuh->TB_LAHIR;

            $data['asi_sampai'] = $tumbuh->ASI_SAMPAI;
            $data['asi_satuan'] = $tumbuh->ASI_SATUAN;

            $data['sufor_mulai'] = $tumbuh->SUFOR_MULAI;
            $data['sufor_satuan'] = $tumbuh->SUFOR_SATUAN;

            $data['mpasi_mulai'] = $tumbuh->MPASI_MULAI;
            $data['mpasi_satuan'] = $tumbuh->MPASI_SATUAN;

            $data['tengkurap'] = $tumbuh->TENGKURAP;
            $data['duduk'] = $tumbuh->DUDUK;
            $data['merangkak'] = $tumbuh->MERANGKAK;
            $data['berdiri'] = $tumbuh->BERDIRI;
            $data['berjalan'] = $tumbuh->BERJALAN;

            $data['neonatus'] = $tumbuh->NEONATUS;
            $data['neonatus_ket'] = $tumbuh->NEONATUS_KET;

            $data['keluhan_tumbuh_kembang'] = $tumbuh->KELUHAN;

            $data['imunisasi'] = $tumbuh->IMUNISASI;
            $data['imunisasi_lain'] = $tumbuh->IMUNISASI_LAIN;
        }

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
