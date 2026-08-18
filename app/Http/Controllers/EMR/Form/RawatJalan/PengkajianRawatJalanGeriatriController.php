<?php

namespace App\Http\Controllers\EMR\Form\RawatJalan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth, Storage;

class PengkajianRawatJalanGeriatriController extends Controller
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
        return view('pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.geriatri.index')->with('list',$data);
    }

    function simpanFormDokterRJG(Request $request)
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

    public function getFormDokterRJG($kunjungan)
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

        if ($keluhan_utama) {
            $data['keluhan_utama'] = $keluhan_utama->DESKRIPSI;
        }

        // Riwayat Penyakit Sekarang
        $anamnesis = DB::table('medicalrecord.anamnesis')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        if ($anamnesis) {
            $data['rps'] = $anamnesis->DESKRIPSI;
        }

        // Riwayat Penyakit Dahulu
        $rpp = DB::table('medicalrecord.rpp')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        if ($rpp) {
            $data['rpd'] = $rpp->DESKRIPSI;
        }

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

    function simpanFormPerawatRJG(Request $request)
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

            // ======================================================
            // ASSESMEN SINDROM GERIATRI
            // ======================================================
            DB::table('medicalrecord.sirmed_assesmen_sindrom_geriatri')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'ADL' => $request->input('geriatri_adl'),
                    'IADL' => $request->input('geriatri_iadl'),
                    'ACS' => $request->input('geriatri_acs'),
                    'NUTRISI' => $request->input('geriatri_nutrisi'),
                    'KOGNITIF' => $request->input('geriatri_kognitif'),
                    'DEPRESI' => $request->input('geriatri_depresi'),
                    'INKONTINENSIA' => $request->input('geriatri_inkontinensia'),
                    'DVT' => $request->input('geriatri_dvt'),
                    'ULKUS' => $request->input('geriatri_ulkus'),
                    'INSOMNIA' => $request->input('geriatri_insomnia'),

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

    public function getFormPerawatRJG($kunjungan)
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

        // ======================================================
        // ASSESMEN SINDROM GERIATRI
        // ======================================================
        $geriatri = DB::table('medicalrecord.sirmed_assesmen_sindrom_geriatri')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        $data['geriatri_adl'] = '';
        $data['geriatri_iadl'] = '';
        $data['geriatri_acs'] = '';
        $data['geriatri_nutrisi'] = '';
        $data['geriatri_kognitif'] = '';
        $data['geriatri_depresi'] = '';
        $data['geriatri_inkontinensia'] = '';
        $data['geriatri_dvt'] = '';
        $data['geriatri_ulkus'] = '';
        $data['geriatri_insomnia'] = '';

        if ($geriatri) {

            $data['geriatri_adl'] =
                $geriatri->ADL ?? '';

            $data['geriatri_iadl'] =
                $geriatri->IADL ?? '';

            $data['geriatri_acs'] =
                $geriatri->ACS ?? '';

            $data['geriatri_nutrisi'] =
                $geriatri->NUTRISI ?? '';

            $data['geriatri_kognitif'] =
                $geriatri->KOGNITIF ?? '';

            $data['geriatri_depresi'] =
                $geriatri->DEPRESI ?? '';

            $data['geriatri_inkontinensia'] =
                $geriatri->INKONTINENSIA ?? '';

            $data['geriatri_dvt'] =
                $geriatri->DVT ?? '';

            $data['geriatri_ulkus'] =
                $geriatri->ULKUS ?? '';

            $data['geriatri_insomnia'] =
                $geriatri->INSOMNIA ?? '';
        }

        return response()->json($data);
    }
}
