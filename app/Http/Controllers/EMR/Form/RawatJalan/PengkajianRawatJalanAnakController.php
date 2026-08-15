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
        $data['tu'] = '';

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
            DB::table('medicalrecord.penilaian_getup_and_go')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'CARA_BERJALAN_PASIEN' => $request->cara_berjalan ?? 0,
                    'FAKTOR_RESIKO'        => $request->faktor_risiko ?? 0,
                    'OBAT_YANG_DIMINUM'    => $request->kon_obat ?? 0,

                    'OLEH'     => auth()->id(),
                    'STATUS'   => 1,
                    'TANGGAL'  => now(),
                ]
            );

            // PENILAIAN STRONG KID
            DB::table('medicalrecord.penilaian_strong_kid')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'TAMPAK_KURUS' => $request->sga1 ?? 0,
                    'PENURUNAN_BERAT_BADAN' => $request->sga2 ?? 0,
                    'DIARE_INTAKE_MAKANAN' => $request->sga3 ?? 0,
                    'RESIKO_MALNUTRISI' => $request->sga4 ?? 0,
                    'SKOR' => $request->skor_sga ?? 0,
                    'STATUS_SKOR' => $request->status_sga ?? 0,
                    'OLEH' => auth()->id(),
                    'STATUS' => 1,
                    'TANGGAL' => now()
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

        if ($getup) {

            $data['cara_berjalan'] = $getup->CARA_BERJALAN_PASIEN;
            $data['faktor_risiko'] = $getup->FAKTOR_RESIKO;
            $data['kon_obat']      = $getup->OBAT_YANG_DIMINUM;
        }


        // ======================================================
        // PENILAIAN STRONG KID
        // ======================================================
        $strong = DB::table('medicalrecord.penilaian_strong_kid')
            ->where('KUNJUNGAN',$kunjungan)
            ->first();


        $data['sga1'] = $strong->TAMPAK_KURUS ?? 0;
        $data['sga2'] = $strong->PENURUNAN_BERAT_BADAN ?? 0;
        $data['sga3'] = $strong->DIARE_INTAKE_MAKANAN ?? 0;
        $data['sga4'] = $strong->RESIKO_MALNUTRISI ?? 0;

        $data['skor_sga'] = $strong->SKOR ?? 0;
        $data['status_sga'] = $strong->STATUS_SKOR ?? 0;

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
            'diag_10' => 'GANGGUAN_ELIMINASI_URIN',
            'diag_11' => 'INTOLERANSI_AKTIFITAS',
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

        $data['terapi_oral'] = '';
        $data['terapi_iv'] = '';

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
