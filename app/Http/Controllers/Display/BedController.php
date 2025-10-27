<?php

namespace App\Http\Controllers\Display;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BedController extends Controller
{
    function index()
    {
        // // Ambil semua bangsal aktif (JENIS = 5 = Bangsal)
        // $bangsal = DB::table('master.ruangan')
        //     ->where('JENIS', 5)
        //     ->where('JENIS_KUNJUNGAN', 3)
        //     ->where('STATUS', 1)
        //     ->orderBy('DESKRIPSI')
        //     ->get();
        // // print_r($bangsal);
        // // die();

        // $data = [];

        // foreach ($bangsal as $b) {
        //     // Ambil kamar di bangsal tersebut
        //     $kamar = DB::table('master.ruang_kamar')
        //         ->where('RUANGAN', $b->ID)
        //         ->where('STATUS', 1)
        //         ->get();

        //     // Hitung tempat tidur per kelas
        //     $kelasData = DB::table('master.ruang_kamar as rk')
        //         ->join('master.ruang_kamar_tidur as rkt', 'rkt.RUANG_KAMAR', '=', 'rk.ID')
        //         ->select(
        //             'rk.KELAS',
        //             DB::raw('COUNT(rkt.ID) as total_bed'),
        //             DB::raw("SUM(CASE WHEN rkt.STATUS = 1 THEN 1 ELSE 0 END) as kosong"),
        //             DB::raw("SUM(CASE WHEN rkt.STATUS = 3 THEN 1 ELSE 0 END) as terisi")
        //         )
        //         ->where('rk.RUANGAN', $b->ID)
        //         ->whereIn('rkt.STATUS',[1,2,3])
        //         ->groupBy('rk.KELAS')
        //         ->get();

        //     // Gabungkan dengan nama kelas dari referensi
        //     foreach ($kelasData as $k) {
        //         $ref = DB::table('master.referensi')
        //             ->where('JENIS', 19)
        //             ->where('ID', $k->KELAS)
        //             ->first();

        //         $k->nama_kelas = $ref ? $ref->DESKRIPSI : '-';
        //     }

        //     $data[] = [
        //         'bangsal' => $b->DESKRIPSI,
        //         'kelas' => $kelasData
        //     ];
        // }

        // // Total keseluruhan
        // $total = DB::table('ruang_kamar_tidur')->selectRaw('
        //     SUM(CASE WHEN STATUS = 1 THEN 1 ELSE 0 END) as kosong,
        //     SUM(CASE WHEN STATUS = 3 THEN 1 ELSE 0 END) as terisi,
        //     COUNT(*) as total_bed
        // ')->first();

        return view('pages.display.bed.index');
    }

    function getDisplayTt()
    {
        // Ambil semua bangsal aktif (JENIS = 5 = Bangsal)
        $bangsalList = DB::table('master.ruangan')
            ->where('JENIS', 5)
            ->where('JENIS_KUNJUNGAN', 3)
            ->where('STATUS', 1)
            ->orderBy('DESKRIPSI')
            ->get();

        $dataBangsal = [];

        foreach ($bangsalList as $b) {
            // Ambil data kelas & tempat tidur per bangsal
            $kelasData = DB::table('master.ruang_kamar as rk')
                ->join('master.ruang_kamar_tidur as rkt', 'rkt.RUANG_KAMAR', '=', 'rk.ID')
                ->select(
                    'rk.KELAS',
                    DB::raw('COUNT(rkt.ID) as total_bed'),
                    DB::raw("SUM(CASE WHEN rkt.STATUS = 1 THEN 1 ELSE 0 END) as kosong"),
                    DB::raw("SUM(CASE WHEN rkt.STATUS = 3 THEN 1 ELSE 0 END) as terisi")
                )
                ->where('rk.RUANGAN', $b->ID)
                ->whereIn('rkt.STATUS', [1, 2, 3])
                ->groupBy('rk.KELAS')
                ->get();

            // Tambahkan nama kelas dari referensi
            foreach ($kelasData as $k) {
                $ref = DB::table('master.referensi')
                    ->where('JENIS', 19)
                    ->where('ID', $k->KELAS)
                    ->first();

                $k->nama_kelas = $ref ? $ref->DESKRIPSI : '-';
            }

            // Gabungkan hasil per bangsal
            $dataBangsal[] = [
                'bangsal' => $b->DESKRIPSI,
                'kelas' => $kelasData
            ];
        }

        // Hitung total keseluruhan tempat tidur
        $total = DB::table('master.ruang_kamar_tidur')
                ->selectRaw('
                    SUM(CASE WHEN STATUS = 1 THEN 1 ELSE 0 END) as kosong,
                    SUM(CASE WHEN STATUS = 2 THEN 1 ELSE 0 END) as terpesan,
                    SUM(CASE WHEN STATUS = 3 THEN 1 ELSE 0 END) as terisi,
                    SUM(CASE WHEN STATUS <> 0 THEN 1 ELSE 0 END) as total_bed
                ')
                ->first();

        // Gabungkan semua ke dalam satu array utama
        $data = [
            'bangsal' => $dataBangsal,
            'total' => $total
        ];

        // Atau kalau mau dijadikan API JSON:
        return response()->json($data, 200);
    }
}
