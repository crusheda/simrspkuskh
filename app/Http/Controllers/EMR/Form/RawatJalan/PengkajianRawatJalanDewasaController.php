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
            ->select(DB::raw('master.getNamaLengkapPegawai(dok.NIP) AS NAMADOKTER'))
            ->where('pk.NOMOR', $kunjungan)
            ->first();

        $frekuensi = DB::table('master.frekuensi_aturan_resep')
                ->select('ID','FREKUENSI')
                ->where('STATUS',1)
                ->orderBy('ID','ASC')
                ->get();

        $rute = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',217)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $data = [
            'kunjungan' => $kunjungan,
            'jenis_ruang' => $jenis_ruang,
            'jenis_perawatan' => $jenis_perawatan,
            'dpjp' => $dpjp,
            'frekuensi' => $frekuensi,
            'rute' => $rute,
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

            // Riwayat Alergi
            if ($request->ra == 1) {
                DB::table('medicalrecord.riwayat_alergi')
                    ->where('KUNJUNGAN',$request->NOKUNJ)
                    ->delete();
                foreach($request->alergi as $a){
                    DB::table('medicalrecord.riwayat_alergi')->insert([
                        'KUNJUNGAN'=>$request->NOKUNJ,
                        'JENIS'=>$a['jenis_id'],
                        'DESKRIPSI'=>$a['deskripsi'],
                        'OLEH'=>auth()->id(),
                        'STATUS'=>1,
                        'TANGGAL'=>now()
                    ]);
                };
            };

            //Riwayat Penggunaan Obat
            if ($request->rpo == 1 && !empty($request->obat)) {
                DB::table('medicalrecord.riwayat_pemberian_obat')
                    ->where('KUNJUNGAN', $request->NOKUNJ)
                    ->delete();
                foreach ($request->obat as $o) {
                    DB::table('medicalrecord.riwayat_pemberian_obat')->insert([
                        'KUNJUNGAN' => $request->NOKUNJ,
                        'OBAT' => $o['nama'],
                        'DOSIS' => $o['dosis'],
                        'FREKUENSI' => $o['frekuensi'],
                        'RUTE' => $o['rute'],
                        'LAMA_PENGGUNAAN' => $o['lama'],
                        'OLEH' => auth()->id(),
                        'STATUS' => 1,
                        'TANGGAL' => now()
                    ]);
                }
            }

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
