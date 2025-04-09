<?php

namespace App\Http\Controllers\Klaim\Smart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApiSmartKlaimController extends Controller
{
    function tableRj($status) // RAWAT JALAN
    {
        $time = Carbon::now()->isoFormat('YYYY-MM-DD HH:mm:ss');
        // SUB QUERY FROM ANY TABEL
        // $subCppt = DB::table('medicalrecord.cppt')
        //         ->select('KUNJUNGAN', 'TANGGAL')
        //         ->where('STATUS', 1) // kalau perlu
        //         // ->where('KUNJUNGAN', '1020101042406080005')
        //         ->orderBy('TANGGAL', 'desc')
        //         ->get(); // ambil 1 cppt terakhir
        $subCppt = DB::table(DB::raw('
            (
                SELECT *,
                    ROW_NUMBER() OVER (PARTITION BY KUNJUNGAN ORDER BY TANGGAL DESC) AS rn
                FROM medicalrecord.cppt
                WHERE STATUS = 1
            ) AS cp
        '))
        ->where('cp.rn', 1); // hanya baris CPPT terakhir per kunjungan
        // MAIN QUERY
        $show = DB::table('pendaftaran.kunjungan AS pk')
                ->select(
                    'pk.*',
                    'pp.NORM','pp.TANGGAL AS TGLDAFTAR',
                    'kjs.noSEP AS NOSEP',
                    'ru.DESKRIPSI AS NAMARUANGAN',
                    'cp.TANGGAL AS TGLCPPT',
                    DB::raw('master.getNamaLengkap(ps.NORM) AS NAMAPASIEN'),
                    DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER')
                )
                // ->selectRaw('SELECT master.getNamaLengkapPegawai("1708205") from master')
                // ->leftJoin('medicalrecord.cppt AS cp', function($join) { // medicalrecord.cppt
                //     $join->on('cp.KUNJUNGAN', '=', 'pk.NOMOR')
                //         ->where('cp.STATUS', '=', 1);
                // })
                ->leftJoinSub($subCppt, 'cp', function ($join) {
                    $join->on('cp.KUNJUNGAN', '=', 'pk.NOMOR');
                })
                ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                ->leftJoin('pendaftaran.penjamin AS pj','pj.NOPEN','=','pp.NOMOR')
                ->leftJoin('bpjs.kunjungan AS kjs','kjs.noSEP','=','pj.NOMOR')
                ->leftJoin('master.pasien AS ps','ps.NORM','=','pp.NORM')
                // ->leftJoin('master.kartu_identitas_pasien AS kip','kip.NORM','=','pp.NORM')
                ->leftJoin('aplikasi.pengguna','aplikasi.pengguna.ID','=','pk.DITERIMA_OLEH')
                ->leftJoin('master.ruangan AS ru','ru.ID','=','pk.RUANGAN')
                ->leftJoin('master.dokter AS dr','dr.ID','=','pk.DPJP')
                ->where(function ($query) {
                    $query->where('pk.RUANGAN', 'LIKE', '1020101%');
                            // ->orWhere('pk.RUANGAN', 'LIKE', '1020201%');
                })
                ->where('pj.JENIS', 2) // PENJAMIN BPJS ONLY
                ->where('pk.BARU', 1) // KUNJUNGAN PERTAMA
                ->where('ru.STATUS', 1) // STATUS RUANGAN AKTIF
                ->where('pk.STATUS', $status) // 0=BATAL;1=MASIH DILAYANI;2=SELESAI
                // ->where('pk.KELUAR', null)
                ->orderBy('pk.MASUK','DESC')
                ->get();

        $data = [
            'show' => $show,
            'time' => $time,
        ];

        return response()->json($data, 200);
    }

    // function checkList($kunjungan)
    // {
    //     $cppt = DB::table('medicalrecord.cppt')
    //             ->select('*')
    //             ->where('KUNJUNGAN', $kunjungan)
    //             ->orderBy('TANGGAL', 'desc')
    //             ->first(); // ambil 1 cppt terakhir

    //     $sep = DB::table('pendaftaran.kunjungan AS pk')
    //             ->select('kjs.*')
    //             ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
    //             ->leftJoin('pendaftaran.penjamin AS pj','pj.NOPEN','=','pp.NOMOR')
    //             ->leftJoin('bpjs.kunjungan AS kjs','kjs.noSEP','=','pj.NOMOR')
    //             ->where('pk.NOMOR', $kunjungan)
    //             ->where('pk.BARU', 1) // KUNJUNGAN PERTAMA
    //             ->orderBy('pk.MASUK','DESC')
    //             ->first(); // ambil 1 sep terakhir

    //     $data = [
    //         'cppt' => $cppt,
    //         'sep' => $sep,
    //     ];

    //     return response()->json($data, 200);
    // }

    // MONITORING
        // CPPT
        function cppt($kunjungan)
        {
            // $cppt = DB::table('medicalrecord.cppt')
            //         ->where('KUNJUNGAN', $kunjungan) // kalau perlu
            //         ->orderBy('TANGGAL', 'desc')
            //         ->get(); // ambil 1 cppt terakhir

            $getNopen = DB::table('pendaftaran.kunjungan AS pk')
                        ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                        ->select('pk.NOPEN','pp.NORM')
                        ->where('pk.NOMOR',$kunjungan)
                        ->first();
            $show = DB::select('CALL medicalrecord.CetakCPPT(?, ?)', [$getNopen->NOPEN, $kunjungan]); // NOPEN & NOKUNJUNGAN
            // print_r($show);
            // die();

            $data = [
                'pen' => $getNopen,
                'show' => $show,
            ];

            return response()->json($data, 200);
        }
}
