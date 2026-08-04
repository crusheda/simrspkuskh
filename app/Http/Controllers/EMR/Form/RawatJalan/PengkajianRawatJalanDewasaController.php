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

        $dpjp = DB::table('pendaftaran.kunjungan AS pk')
            ->leftJoin('master.dokter AS dok', 'dok.ID', '=', 'pk.DPJP')
            ->select('dok.ID', DB::raw('master.getNamaLengkapPegawai(dok.NIP) AS NAMADOKTER'))
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

        $data = [
            'kunjungan' => $kunjungan,
            'jenis_ruang' => $jenis_ruang,
            'jenis_perawatan' => $jenis_perawatan,
            'dpjp' => $dpjp,
            'jenis_alergi' => $jenis_alergi,
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
    }


}
