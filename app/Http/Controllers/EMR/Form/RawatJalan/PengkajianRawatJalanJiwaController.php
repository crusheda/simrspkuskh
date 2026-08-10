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
                    'EVALUASI'     => $request->eval,
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

        // Riwayat Terapi
        $assesment = DB::table('medicalrecord.sirmed_assesment')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        $data['tu'] = '';
        $data['eval'] = '';

        if ($assesment) {
            $data['tu'] = $assesment->TOLAK_UKUR;
            $data['eval'] = $assesment->EVALUASI;
        }

        // Edukasi Rawat Jalan
        $edukasi = DB::table('medicalrecord.edukasi_rajal')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        // Default
        $data['me_1'] = 0;
        $data['me_2'] = 0;
        $data['me_3'] = 0;
        $data['me_4'] = 0;
        $data['me_5'] = 0;

        $data['sie_1'] = 0;
        $data['sie_2'] = 0;

        $data['eval_1'] = 0;
        $data['eval_2'] = 0;

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

        // Default
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

        // ======================================================
        // EDUKASI PASIEN DAN KELUARGA
        // ======================================================
        $edukasi_pk = DB::table('medicalrecord.edukasi_pasien_keluarga')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();


        // Default Edukasi Awal
        $data['edu_1'] = 0;
        $data['edu_2'] = 0;
        $data['edu_3'] = 0;


        // Kebutuhan Edukasi
        $data['edukasi_diagnosa'] = 0;
        $data['edukasi_rehab_medik'] = 0;
        $data['edukasi_hkp'] = 0;
        $data['edukasi_informed_consent'] = 0;

        $data['edukasi_cuci_tangan'] = 0;
        $data['edukasi_perencanaan_pulang'] = 0;

        $data['edukasi_obat'] = 0;
        $data['edukasi_nyeri'] = 0;
        $data['edukasi_hak_partisipasi'] = 0;

        $data['edukasi_penundaan'] = 0;
        $data['edukasi_bahaya_merokok'] = 0;

        $data['edukasi_nutrisi'] = 0;
        $data['edukasi_penggunaan_alat'] = 0;
        $data['edukasi_prosedure'] = 0;

        $data['edukasi_keterlambatan'] = 0;
        $data['edukasi_rujukan'] = 0;


        // Lainnya
        $data['status_lain'] = 0;
        $data['kb_edu_lain'] = '';


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
        // DEFAULT MASALAH KEPERAWATAN JIWA
        // ======================================================
        foreach ($diag_jiwa_field as $key => $column) {
            $data[$key] = 0;
        }
        foreach ($tin_jiwa_field as $key => $column) {
            $data[$key] = 0;
        }

        // ======================================================
        // DEFAULT DETAIL TERAPI
        // ======================================================
        $data['tin_6'] = '';
        $data['tin_7'] = '';
        $data['terapi_oral'] = '';
        $data['terapi_iv'] = '';

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
