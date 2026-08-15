<?php

namespace App\Http\Controllers\EMR\Form\RawatJalan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth, Storage;

class PengkajianRawatJalanObsgynController extends Controller
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

        $usia_kehamilan = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',299)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $jenis_persalinan = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',300)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $penyulit = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',301)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $jenis_kelamin = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',2)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $penolong = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',303)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $tempat = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',304)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $keadaan_sat_ini = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',302)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $usia = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',192)
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
            'usia_kehamilan' => $usia_kehamilan,
            'jenis_persalinan' => $jenis_persalinan,
            'penyulit' => $penyulit,
            'jenis_kelamin' => $jenis_kelamin,
            'penolong' => $penolong,
            'tempat' => $tempat,
            'keadaan_sat_ini' => $keadaan_sat_ini,
            'usia' => $usia,
            ];
        // print_r($data);
        // die();
        return view('pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.obsgyn.index')->with('list',$data);
    }

    function simpanFormDokterRJO(Request $request)
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

            // SPRI
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

            // Riwayat Menstruasi
            DB::table('medicalrecord.sirmed_status_reproduksi')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    // --------------------------
                    // Riwayat KB
                    // --------------------------
                    'KB_SUNTIK' => $request->input('kb_suntik') ? 1 : 0,
                    'KB_IUD' => $request->input('kb_iud') ? 1 : 0,
                    'KB_PIL' => $request->input('kb_pil') ? 1 : 0,
                    'KB_KONDOM' => $request->input('kb_kondom') ? 1 : 0,
                    'KB_KALENDER' => $request->input('kb_kalender') ? 1 : 0,
                    'KB_MOW' => $request->input('kb_mow') ? 1 : 0,
                    'KB_MOP' => $request->input('kb_mop') ? 1 : 0,
                    'KB_IMPLAN' => $request->input('kb_implan') ? 1 : 0,

                    'KB_KELUHAN' => $request->input('kb_keluhan'),

                    // --------------------------
                    // Riwayat Menstruasi
                    // --------------------------
                    'MENSTRUASI_TERATUR' =>
                        $request->has('menstruasi_teratur')
                            ? (int) $request->input('menstruasi_teratur')
                            : 0,

                    'MENSTRUASI_KELUHAN' =>
                        $request->input('menstruasi_keluhan'),

                    // --------------------------
                    // Audit
                    // --------------------------
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

    public function getFormDokterRJO($kunjungan)
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
            $data['ku']          = $tanda_vital->KEADAAN_UMUM;
            $data['kesadaran']   = $tanda_vital->KESADARAN;
            $data['eye']         = $tanda_vital->EYE;
            $data['motorik']     = $tanda_vital->MOTORIK;
            $data['verbal']      = $tanda_vital->VERBAL;
            $data['gcs']         = $tanda_vital->GCS;

            $data['td_up']       = $tanda_vital->SISTOLIK;
            $data['td_down']     = $tanda_vital->DISTOLIK;
            $data['spo2']        = $tanda_vital->SATURASI_O2;
            $data['nafas']       = $tanda_vital->FREKUENSI_NAFAS;
            $data['suhu']        = $tanda_vital->SUHU;
            $data['nadi']        = $tanda_vital->FREKUENSI_NADI;
            $data['abn']         = $tanda_vital->ALAT_BANTU_NAFAS;
        }

        // Anamnesis diperoleh
        $anam = DB::table('medicalrecord.anamnesis_diperoleh')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        $data['anam'] = 0;

        if ($anam) {
            if ($anam->AUTOANAMNESIS == 1) {
                $data['anam'] = 1;
            } elseif ($anam->ALLOANAMNESIS == 1) {
                $data['anam'] = 2;
            }
            $data['anamnesis_oleh'] = $anam->DARI;
        }

        // Keluhan Utama
        $keluhan_utama = DB::table('medicalrecord.keluhan_utama')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        $data['keluhan_utama'] = '';

        if ($keluhan_utama) {
            $data['keluhan_utama'] = $keluhan_utama->DESKRIPSI;
        }

        // Riwayat Penyakit Sekarang
        $anamnesis = DB::table('medicalrecord.anamnesis')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        $data['rps'] = '';

        if ($anamnesis) {
            $data['rps'] = $anamnesis->DESKRIPSI;
        }

        // Riwayat Penyakit Dahulu
        $rpp = DB::table('medicalrecord.rpp')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        $data['rpd'] = '';

        if ($rpp) {
            $data['rpd'] = $rpp->DESKRIPSI;
        }

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

        $data['terapi_tind'] = '';

        if ($rencana_terapi) {
            $data['terapi_tind'] = $rencana_terapi->DESKRIPSI;
        }

        // Tindak Lanjut Pengkajian
        $tindak_lanjut = DB::table('medicalrecord.tindak_lanjut_pengkajian')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        $data['tl'] = '';
        $data['rujuk'] = '';
        $data['rujuk_lainnya'] = '';

        if ($tindak_lanjut) {
            $data['tl'] = $tindak_lanjut->TINDAK_LANJUT;
            $data['rujuk'] = $tindak_lanjut->RUJUKAN;
            $data['rujuk_lainnya'] = $tindak_lanjut->RUJUKAN_LAINNYA;
        }

        // Perencanaan Rawat Inap
        $perencanaan = DB::table('medicalrecord.perencanaan_rawat_inap')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        $data['pri_ruang'] = '';
        $data['pri_perawatan'] = '';
        $data['pri_indikasi'] = '';
        $data['pri_ket'] = '';
        $data['pri_dpjp'] = '';

        if ($perencanaan) {
            $data['pri_ruang'] = $perencanaan->JENIS_RUANG_PERAWATAN;
            $data['pri_perawatan'] = $perencanaan->JENIS_PERAWATAN;
            $data['pri_indikasi'] = $perencanaan->INDIKASI;
            $data['pri_ket'] = $perencanaan->DESKRIPSI;
            $data['pri_dpjp'] = $perencanaan->DOKTER;
        }
        // dd($data);

        $reproduksi = DB::table('medicalrecord.sirmed_status_reproduksi')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        $data['kb_suntik'] = 0;
        $data['kb_iud'] = 0;
        $data['kb_pil'] = 0;
        $data['kb_kondom'] = 0;
        $data['kb_kalender'] = 0;
        $data['kb_mow'] = 0;
        $data['kb_mop'] = 0;
        $data['kb_implan'] = 0;

        $data['kb_keluhan'] = '';

        $data['menstruasi_teratur'] = '';
        $data['menstruasi_keluhan'] = '';

        if ($reproduksi) {
            $data['kb_suntik'] = (int) $reproduksi->KB_SUNTIK;
            $data['kb_iud'] = (int) $reproduksi->KB_IUD;
            $data['kb_pil'] = (int) $reproduksi->KB_PIL;
            $data['kb_kondom'] = (int) $reproduksi->KB_KONDOM;
            $data['kb_kalender'] = (int) $reproduksi->KB_KALENDER;
            $data['kb_mow'] = (int) $reproduksi->KB_MOW;
            $data['kb_mop'] = (int) $reproduksi->KB_MOP;
            $data['kb_implan'] = (int) $reproduksi->KB_IMPLAN;

            $data['kb_keluhan'] =
                $reproduksi->KB_KELUHAN ?? '';

            $data['menstruasi_teratur'] =
                $reproduksi->MENSTRUASI_TERATUR;

            $data['menstruasi_keluhan'] =
                $reproduksi->MENSTRUASI_KELUHAN ?? '';
        }

        return response()->json($data);
    }

    function simpanFormPerawatRJO(Request $request)
    {
        // print_r($request->all());
        // die();
        // dd($request->all());

        DB::beginTransaction();

        try {

            // TANDA VITAL
            DB::table('medicalrecord.tanda_vital')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'KELUHAN_UTAMA' => $request->anm_ku,
                    'KEADAAN_UMUM' => $request->ku,
                    'KESADARAN'    => $request->kesadaran,
                    'EYE'          => $request->eye,
                    'MOTORIK'      => $request->motorik,
                    'VERBAL'       => $request->verbal,
                    'GCS'          => $request->gcs,

                    'SISTOLIK'          => $request->td_up,
                    'DISTOLIK'          => $request->td_down,
                    'SATURASI_O2'       => $request->spo2,
                    'FREKUENSI_NAFAS'   => $request->nafas,
                    'SUHU'              => $request->suhu,
                    'FREKUENSI_NADI'    => $request->nadi,
                    'ALAT_BANTU_NAFAS'  => $request->abn,
                    'EWSS'              => '0',
                    'UMUR'              => '0',
                    'PEWSS'             => '0',
                    'WAKTU_PEMERIKSAAN' => now(),

                    'OLEH'         => auth()->id(),
                    'STATUS'       => 1,
                    'TANGGAL'      => now(),
                ]
            );

            //KONDISI SOSIAL
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
                    'MASALAH_PERILAKU'                      => $request->perilaku,
                    'PERILAKU_KEKERASAN_DIALAMI_SEBELUMNYA' => $request->kekerasan,

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

            // PENILAIAN NYERI
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

            // RESIKO JATUH GET UP AND GO
            $caraBerjalan = $request->cara_berjalan ?? 0;
            $faktorRisiko = $request->faktor_risiko ?? 0;
            $obatDiminum  = $request->kon_obat ?? 0;

            if ($caraBerjalan == 0 && $faktorRisiko == 0 && $obatDiminum == 0) {
                DB::table('medicalrecord.penilaian_getup_and_go')
                    ->where('KUNJUNGAN', $request->NOKUNJ)
                    ->delete();
            } else {
                DB::table('medicalrecord.penilaian_getup_and_go')->updateOrInsert(
                    [
                        'KUNJUNGAN' => $request->NOKUNJ
                    ],
                    [
                        'CARA_BERJALAN_PASIEN' => $caraBerjalan,
                        'FAKTOR_RESIKO'        => $faktorRisiko,
                        'OBAT_YANG_DIMINUM'    => $obatDiminum,
                        'OLEH'                 => auth()->id(),
                        'STATUS'               => 1,
                        'TANGGAL'              => now(),
                    ]
                );
            };

            $usia = $request->rj_usia ?? 0;
            $jk   = $request->rj_jk ?? 0;
            $hd1  = $request->rj_hd_1 ?? 0;
            $hd2  = $request->rj_hd_2 ?? 0;
            $hd3  = $request->rj_hd_3 ?? 0;
            $hd4  = $request->rj_hd_4 ?? 0;
            $hd5  = $request->rj_hd_5 ?? 0;

            if (
                $usia == 0 &&
                $jk == 0 &&
                $hd1 == 0 &&
                $hd2 == 0 &&
                $hd3 == 0 &&
                $hd4 == 0 &&
                $hd5 == 0
            ) {
                DB::table('medicalrecord.penilaian_skala_humpty_dumpty')
                    ->where('KUNJUNGAN', $request->NOKUNJ)
                    ->update([
                        'STATUS' => 0,
                        'OLEH'   => auth()->id(),
                    ]);
            } else {
                DB::table('medicalrecord.penilaian_skala_humpty_dumpty')
                    ->updateOrInsert(
                        [
                            'KUNJUNGAN' => $request->NOKUNJ
                        ],
                        [
                            'UMUR'               => $usia,
                            'JENIS_KELAMIN'      => $jk,
                            'DIAGNOSA'           => $hd1,
                            'GANGGUAN_KONGNITIF' => $hd2,
                            'FAKTOR_LINGKUNGAN'  => $hd3,
                            'RESPON'             => $hd4,
                            'PENGGUNAAN_OBAT'    => $hd5,
                            'OLEH'               => auth()->id(),
                            'STATUS'             => 1,
                            'TANGGAL'            => now(),
                        ]
                    );
            }

            // SKRINING GIZI
            DB::table('medicalrecord.permasalahan_gizi')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'BERAT_BADAN_SIGNIFIKAN' => $request->bb_turun ?? 0,
                    'PERUBAHAN_BERAT_BADAN'  => $request->bb_ubah ?? 0,
                    'INTAKE_MAKANAN'         => $request->nafsu_makan ?? 0,
                    'KONDISI_KHUSUS'         => $request->kondisi_khusus,

                    'SKOR'                  => $request->skor_gizi,
                    'STATUS_SKOR'           => $request->status_skor,

                    'OLEH'                  => auth()->id(),
                    'STATUS'                => 1,
                    'TANGGAL'               => now(),
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
                    'GANGGUAN_ELIMINASI_URIN' => $request->input('diag_10') ? 1 : 0,
                    'INTOLERANSI_AKTIFITAS' => $request->input('diag_11') ? 1 : 0,
                    'GANGGUAN_MOBILITAS_FISIK' => $request->input('diag_12') ? 1 : 0,
                    'GANGGUAN_PERTUKARAN_GAS' => $request->input('diag_13') ? 1 : 0,

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

            // ======================================================
            // STATUS FUNGSIONAL
            // ======================================================

            $alatBantu = $request->input('alat_bantu_fungsional');

            DB::table('medicalrecord.status_fungsional')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    // Alat bantu
                    'TANPA_ALAT_BANTU' => $alatBantu === 'tanpa' ? 1 : 0,
                    'TONGKAT'          => $alatBantu === 'tongkat' ? 1 : 0,
                    'KURSI_RODA'       => $alatBantu === 'kursi_roda' ? 1 : 0,
                    'BRANKARD'         => $alatBantu === 'brankard' ? 1 : 0,
                    'WALKER'           => $alatBantu === 'walker' ? 1 : 0,

                    // Alat bantu lainnya
                    'ALAT_BANTU' => $request->input('alat_bantu', ''),

                    // Cacat tubuh
                    'CACAT_TUBUH_TIDAK' => $request->input('cacat_tubuh') == '0' ? 1 : 0,
                    'CACAT_TUBUH_YA'    => $request->input('cacat_tubuh') == '1' ? 1 : 0,

                    // Keterangan
                    'KET_CACAT_TUBUH' => $request->input('ket_cacat_tubuh', ''),

                    'OLEH'    => auth()->id(),
                    'STATUS'  => 1,
                    'TANGGAL' => now(),
                ]
            );

            // DIAGNOSIS KEPERAWATAN
            DB::table('medicalrecord.sirmed_diagnosa_keperawatan')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'NYERI' =>
                        $request->input('diag_keperawatan_1') ? 1 : 0,

                    'GANGGUAN_PERFUSI_CEREBRAL' =>
                        $request->input('diag_keperawatan_2') ? 1 : 0,

                    'CEMAS' =>
                        $request->input('diag_keperawatan_3') ? 1 : 0,

                    'SENSORI_PERSEPSI' =>
                        $request->input('diag_keperawatan_4') ? 1 : 0,

                    'HIPERTERMI' =>
                        $request->input('diag_keperawatan_5') ? 1 : 0,

                    'KERUSAKAN_INTEGRITAS_KULIT' =>
                        $request->input('diag_keperawatan_6') ? 1 : 0,

                    'GANGGUAN_PERFUSI_JARINGAN' =>
                        $request->input('diag_keperawatan_7') ? 1 : 0,

                    'BODY_IMAGE' =>
                        $request->input('diag_keperawatan_8') ? 1 : 0,

                    'GANGGUAN_MOBILITAS_FISIK' =>
                        $request->input('diag_keperawatan_9') ? 1 : 0,

                    'KURANG_PENGETAHUAN' =>
                        $request->input('diag_keperawatan_10') ? 1 : 0,

                    'PERUBAHAN_NUTRISI_KURANG_DARI_KEBUTUHAN' =>
                        $request->input('diag_keperawatan_11') ? 1 : 0,

                    'RENCANA_ASUHAN_KEPERAWATAN' =>
                        $request->input('rencana_asuhan_keperawatan'),

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

    public function getFormPerawatRJO($kunjungan)
    {
        $data = [];

        // ======================================================
        // TANDA VITAL
        // ======================================================
        $tanda_vital = DB::table('medicalrecord.tanda_vital')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        // Default
        $data['anm_ku'] = '';
        $data['ku'] = '';
        $data['kesadaran'] = '';
        $data['eye'] = '';
        $data['motorik'] = '';
        $data['verbal'] = '';
        $data['gcs'] = '';

        $data['td_up'] = '';
        $data['td_down'] = '';
        $data['spo2'] = '';
        $data['nafas'] = '';
        $data['suhu'] = '';
        $data['nadi'] = '';
        $data['abn'] = '';

        if ($tanda_vital) {

            $data['anm_ku']      = $tanda_vital->KELUHAN_UTAMA;
            $data['ku']          = $tanda_vital->KEADAAN_UMUM;
            $data['kesadaran']   = $tanda_vital->KESADARAN;
            $data['eye']         = $tanda_vital->EYE;
            $data['motorik']     = $tanda_vital->MOTORIK;
            $data['verbal']      = $tanda_vital->VERBAL;
            $data['gcs']         = $tanda_vital->GCS;

            $data['td_up']       = $tanda_vital->SISTOLIK;
            $data['td_down']     = $tanda_vital->DISTOLIK;
            $data['spo2']        = $tanda_vital->SATURASI_O2;
            $data['nafas']       = $tanda_vital->FREKUENSI_NAFAS;
            $data['suhu']        = $tanda_vital->SUHU;
            $data['nadi']        = $tanda_vital->FREKUENSI_NADI;
            $data['abn']         = $tanda_vital->ALAT_BANTU_NAFAS;
        }
        // dd($tanda_vital);

        // ======================================================
        // KONDISI SOSIAL
        // ======================================================
        $kondisi_sosial = DB::table('medicalrecord.kondisi_sosial')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();


        // Default Status Psikologi
        $data['tak'] = 0;
        $data['marah'] = 0;
        $data['cemas'] = 0;
        $data['takut'] = 0;
        $data['sedih'] = 0;
        $data['bundir'] = 0;
        $data['pse_lain'] = '';


        // Status Mental
        $data['sm'] = 0;
        $data['perilaku'] = '';
        $data['kekerasan'] = '';


        // Hubungan Sosial
        $data['hub'] = 0;
        $data['tinggal'] = 0;
        $data['tinggal_lain'] = '';


        // Spiritual
        $data['kbt'] = 0;
        $data['nk'] = 0;
        $data['nk_lain'] = '';
        $data['pk'] = '';


        // Ekonomi
        $data['hasil'] = 0;


        if ($kondisi_sosial) {

            // Status Psikologi
            $data['tak']       = $kondisi_sosial->TIDAK_ADA_KELAINAN;
            $data['marah']     = $kondisi_sosial->MARAH;
            $data['cemas']     = $kondisi_sosial->CEMAS;
            $data['takut']     = $kondisi_sosial->TAKUT;
            $data['sedih']     = $kondisi_sosial->SEDIH;
            $data['bundir']    = $kondisi_sosial->BUNUH_DIRI;
            $data['pse_lain']  = $kondisi_sosial->LAINNYA;


            // Status Mental
            $data['sm']        = $kondisi_sosial->STATUS_MENTAL;
            $data['perilaku']  = $kondisi_sosial->MASALAH_PERILAKU;
            $data['kekerasan'] = $kondisi_sosial->PERILAKU_KEKERASAN_DIALAMI_SEBELUMNYA;


            // Hubungan Sosial
            $data['hub']          = $kondisi_sosial->HUBUNGAN_PASIEN_DENGAN_KELUARGA;
            $data['tinggal']      = $kondisi_sosial->TEMPAT_TINGGAL;
            $data['tinggal_lain'] = $kondisi_sosial->TEMPAT_TINGGAL_LAINNYA;


            // Spiritual
            $data['kbt']     = $kondisi_sosial->KEBIASAAN_BERIBADAH_TERATUR;
            $data['nk']      = $kondisi_sosial->NILAI_KEPERCAYAAN;
            $data['nk_lain'] = $kondisi_sosial->NILAI_KEPERCAYAAN_DESKRIPSI;
            $data['pk']      = $kondisi_sosial->PENGAMBIL_KEPUTUSAN_DALAM_KELUARGA;


            // Ekonomi
            $data['hasil'] = $kondisi_sosial->PENGHASILAN_PERBULAN;
        }

        // ======================================================
        // PENILAIAN NYERI
        // ======================================================
        $nyeri = DB::table('medicalrecord.penilaian_nyeri')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        $data['sn_nyeri'] = 0;
        $data['sn_onset'] = '';
        $data['sn_skala'] = '';
        $data['sn_metode'] = '';
        $data['sn_pencetus'] = '';
        $data['sn_gambaran'] = '';
        $data['sn_durasi'] = '';
        $data['sn_lokasi'] = '';

        if ($nyeri) {

            $data['sn_nyeri']    = $nyeri->NYERI;
            $data['sn_onset']    = $nyeri->ONSET;
            $data['sn_skala']    = $nyeri->SKALA;
            $data['sn_metode']   = $nyeri->METODE;
            $data['sn_pencetus'] = $nyeri->PENCETUS;
            $data['sn_gambaran'] = $nyeri->GAMBARAN;
            $data['sn_durasi']   = $nyeri->DURASI;
            $data['sn_lokasi']   = $nyeri->LOKASI;
        }


        // ======================================================
        // RESIKO JATUH GET UP AND GO
        // ======================================================
        $getup = DB::table('medicalrecord.penilaian_getup_and_go')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        $data['cara_berjalan'] = 0;
        $data['faktor_risiko'] = 0;
        $data['kon_obat'] = 0;

        if ($getup) {

            $data['cara_berjalan'] = $getup->CARA_BERJALAN_PASIEN;
            $data['faktor_risiko'] = $getup->FAKTOR_RESIKO;
            $data['kon_obat']      = $getup->OBAT_YANG_DIMINUM;
        }

        $data['humpty_dumpty'] = DB::table('medicalrecord.penilaian_skala_humpty_dumpty')
            ->select([
                'UMUR',
                'JENIS_KELAMIN',
                'DIAGNOSA',
                'GANGGUAN_KONGNITIF',
                'FAKTOR_LINGKUNGAN',
                'RESPON',
                'PENGGUNAAN_OBAT',
            ])
            ->where('KUNJUNGAN', $kunjungan)
            ->whereIn('STATUS', [1, 2])
            ->orderByDesc('ID')
            ->first();

        // ======================================================
        // SKRINING GIZI
        // ======================================================
        $gizi = DB::table('medicalrecord.permasalahan_gizi')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();


        $data['bb_turun'] = 0;
        $data['bb_ubah'] = 0;
        $data['nafsu_makan'] = 0;
        $data['kondisi_khusus'] = '';

        $data['skor_gizi'] = '';
        $data['status_skor'] = '';


        if ($gizi) {

            $data['bb_turun']       = $gizi->BERAT_BADAN_SIGNIFIKAN;
            $data['bb_ubah']        = $gizi->PERUBAHAN_BERAT_BADAN;
            $data['nafsu_makan']    = $gizi->INTAKE_MAKANAN;
            $data['kondisi_khusus'] = $gizi->KONDISI_KHUSUS;

            $data['skor_gizi']      = $gizi->SKOR;
            $data['status_skor']    = $gizi->STATUS_SKOR;
        }

        // ======================================================
        // STATUS FUNGSIONAL
        // ======================================================
        $status_fungsional = DB::table('medicalrecord.status_fungsional')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first();

        $data['alat_bantu_fungsional'] = '';
        $data['alat_bantu'] = '';
        $data['cacat_tubuh'] = '0';
        $data['ket_cacat_tubuh'] = '';

        if ($status_fungsional) {

            // Tentukan radio alat bantu
            if ($status_fungsional->TANPA_ALAT_BANTU == 1) {
                $data['alat_bantu_fungsional'] = 'tanpa';

            } elseif ($status_fungsional->TONGKAT == 1) {
                $data['alat_bantu_fungsional'] = 'tongkat';

            } elseif ($status_fungsional->KURSI_RODA == 1) {
                $data['alat_bantu_fungsional'] = 'kursi_roda';

            } elseif ($status_fungsional->BRANKARD == 1) {
                $data['alat_bantu_fungsional'] = 'brankard';

            } elseif ($status_fungsional->WALKER == 1) {
                $data['alat_bantu_fungsional'] = 'walker';
            }

            $data['alat_bantu'] =
                $status_fungsional->ALAT_BANTU ?? '';

            // Cacat tubuh
            $data['cacat_tubuh'] =
                $status_fungsional->CACAT_TUBUH_YA == 1 ? '1' : '0';

            $data['ket_cacat_tubuh'] =
                $status_fungsional->KET_CACAT_TUBUH ?? '';
        }

        // ======================================================
        // DIAGNOSIS KEPERAWATAN
        // ======================================================
        $diagnosa_keperawatan = DB::table('medicalrecord.sirmed_diagnosa_keperawatan')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        $diagnosa_keperawatan_field = [

            'diag_keperawatan_1' =>
                'NYERI',

            'diag_keperawatan_2' =>
                'GANGGUAN_PERFUSI_CEREBRAL',

            'diag_keperawatan_3' =>
                'CEMAS',

            'diag_keperawatan_4' =>
                'SENSORI_PERSEPSI',

            'diag_keperawatan_5' =>
                'HIPERTERMI',

            'diag_keperawatan_6' =>
                'KERUSAKAN_INTEGRITAS_KULIT',

            'diag_keperawatan_7' =>
                'GANGGUAN_PERFUSI_JARINGAN',

            'diag_keperawatan_8' =>
                'BODY_IMAGE',

            'diag_keperawatan_9' =>
                'GANGGUAN_MOBILITAS_FISIK',

            'diag_keperawatan_10' =>
                'KURANG_PENGETAHUAN',

            'diag_keperawatan_11' =>
                'PERUBAHAN_NUTRISI_KURANG_DARI_KEBUTUHAN',
        ];


        foreach ($diagnosa_keperawatan_field as $key => $column) {
            $data[$key] = 0;
        }

        $data['rencana_asuhan_keperawatan'] = '';

        if ($diagnosa_keperawatan) {

            foreach ($diagnosa_keperawatan_field as $key => $column) {

                $data[$key] =
                    (int) ($diagnosa_keperawatan->$column ?? 0);
            }

            $data['rencana_asuhan_keperawatan'] =
                $diagnosa_keperawatan->RENCANA_ASUHAN_KEPERAWATAN ?? '';
        }

        return response()->json($data);
    }
}
