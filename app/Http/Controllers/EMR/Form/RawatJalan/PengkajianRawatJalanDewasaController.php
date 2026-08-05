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

    function simpanFormDokter(Request $request)
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

            // Edukasi Rawat Jalan
            DB::table('medicalrecord.edukasi_rajal')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    // Materi Edukasi
                    'ME_TANDA_GEJALA'            => $request->has('me_1') ? 1 : 0,
                    'ME_HASIL_PEMERIKSAAN'       => $request->has('me_2') ? 1 : 0,
                    'ME_DIAGNOSIS'               => $request->has('me_3') ? 1 : 0,
                    'ME_RENCANA_PENATALAKSANAAN' => $request->has('me_4') ? 1 : 0,
                    'ME_TINDAKAN_TUJUAN_TERAPI'         => $request->has('me_5') ? 1 : 0,

                    // Sarana Informasi Edukasi
                    'SIE_LEAFLET' => $request->has('sie_1') ? 1 : 0,
                    'SIE_LISAN'   => $request->has('sie_2') ? 1 : 0,

                    // Evaluasi
                    'EVAL_SUDAH_MENGERTI' => $request->has('eval_1') ? 1 : 0,
                    'EVAL_RE_EDUKASI'     => $request->has('eval_2') ? 1 : 0,

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
                    // 1 = Pulang
                    // 2 = MRS
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

    function simpanFormPerawat(Request $request)
    {
        print_r($request->all());
        die();

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
                    'TIDAK_ADA_KELAINAN' => $request->has('tak') ? 1 : 0,
                    'MARAH'              => $request->has('marah') ? 1 : 0,
                    'CEMAS'              => $request->has('cemas') ? 1 : 0,
                    'TAKUT'              => $request->has('takut') ? 1 : 0,
                    'SEDIH'              => $request->has('sedih') ? 1 : 0,
                    'BUNUH_DIRI'         => $request->has('bundir') ? 1 : 0,
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

                    // Kebutuhan edukasi
                    'EDUKASI_DIAGNOSA' => $request->edukasi_diagnosa ?? 0,
                    'EDUKASI_REHAB_MEDIK' => $request->edukasi_rehab_medik ?? 0,
                    'EDUKASI_HKP' => $request->edukasi_hkp ?? 0,

                    'EDUKASI_PEMBERIAN_INFORMED_CONSENT' => $request->edukasi_informed_consent ?? 0,

                    'EDUKASI_CUCI_TANGAN' => $request->edukasi_cuci_tangan ?? 0,
                    'EDUKASI_PERENCANAAN_PULANG' => $request->edukasi_perencanaan_pulang ?? 0,

                    'EDUKASI_OBAT' => $request->edukasi_obat ?? 0,
                    'EDUKASI_NYERI' => $request->edukasi_nyeri ?? 0,
                    'EDUKASI_HAK_BERPARTISIPASI' => $request->edukasi_hak_partisipasi ?? 0,

                    'EDUKASI_PENUNDAAN_PELAYANAN' => $request->edukasi_penundaan ?? 0,
                    'EDUKASI_BAHAYA_MEROKO' => $request->edukasi_bahaya_merokok ?? 0,

                    'EDUKASI_NUTRISI' => $request->edukasi_nutrisi ?? 0,
                    'EDUKASI_PENGGUNAAN_ALAT' => $request->edukasi_penggunaan_alat ?? 0,
                    'EDUKASI_PROSEDURE_PENUNJANG' => $request->edukasi_prosedure ?? 0,

                    'EDUKASI_KELAMBATAN_PELAYANAN' => $request->edukasi_keterlambatan ?? 0,
                    'EDUKASI_RUJUKAN_PASIEN' => $request->edukasi_rujukan ?? 0,

                    // Lainnya
                    'STATUS_LAIN' => $request->status_lain ?? 0,
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
                    'BERSIHAN_JALAN_NAFAS_TIDAK_EFEKTIF' => $request->has('diag_1') ? 1 : 0,
                    'POLA_NAFAS_TIDAK_EFEKTIF' => $request->has('diag_2') ? 1 : 0,
                    'PERFUSI_PERIFER_TIDAK_EFEKTIF' => $request->has('diag_3') ? 1 : 0,
                    'DIARE' => $request->has('diag_4') ? 1 : 0,
                    'NYERI_AKUT' => $request->has('diag_5') ? 1 : 0,
                    'NAUSEA' => $request->has('diag_6') ? 1 : 0,
                    'HIPERTERMI' => $request->has('diag_7') ? 1 : 0,
                    'ANSIETAS' => $request->has('diag_8') ? 1 : 0,

                    'GANGGUAN_INTEGRITAS_KULIT_JARINGAN' => $request->has('diag_9') ? 1 : 0,
                    'GANGGUAN_ELIMINASI_URIN' => $request->has('diag_10') ? 1 : 0,
                    'INTOLERANSI_AKTIFITAS' => $request->has('diag_11') ? 1 : 0,
                    'GANGGUAN_MOBILITAS_FISIK' => $request->has('diag_12') ? 1 : 0,
                    'GANGGUAN_PERTUKARAN_GAS' => $request->has('diag_13') ? 1 : 0,

                    'TINDAKAN_RELAKSASI_NAFAS_DALAM' => $request->has('tin_1') ? 1 : 0,
                    'TINDAKAN_BODY_ALIGNMENT' => $request->has('tin_2') ? 1 : 0,

                    'TINDAKAN_TENANGKAN_PASIEN' => $request->has('tin_3') ? 1 : 0,
                    'TINDAKAN_PENDIDIKAN_KESEHATAN' => $request->has('tin_4') ? 1 : 0,
                    'TINDAKAN_RAWAT_LUKA' => $request->has('tin_5') ? 1 : 0,

                    'TERAPI_ORAL' => $request->has('tin_6') ? 1 : 0,
                    'TERAPI_ORAL_DETAIL' => $request->terapi_oral,

                    'TERAPI_IV_SC_IM' => $request->has('tin_7') ? 1 : 0,
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


}
