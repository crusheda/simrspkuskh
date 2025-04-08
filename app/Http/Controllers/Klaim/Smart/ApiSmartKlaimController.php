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
        $subCppt = DB::table('medicalrecord.cppt')
                ->select('KUNJUNGAN', 'TANGGAL')
                ->where('STATUS', 1) // kalau perlu
                ->orderBy('TANGGAL', 'desc')
                ->limit(1); // ambil 1 cppt terakhir
        // MAIN QUERY
        $show = DB::table('pendaftaran.kunjungan AS pk')
                ->select(
                    'pk.*',
                    'pp.NORM',
                    'kjs.noSEP AS NOSEP',
                    'ru.DESKRIPSI AS NAMARUANGAN',
                    'cp.TANGGAL AS CPPTTANGGAL',
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

    // MONITORING
        // CPPT
        function cppt($kunjungan)
        {
            $cppt = DB::table('medicalrecord.cppt')
                    // ->select('*')
                    ->where('KUNJUNGAN', $kunjungan) // kalau perlu
                    ->orderBy('TANGGAL', 'desc')
                    ->get(); // ambil 1 cppt terakhir

            $show = DB::select('CALL medicalrecord.CetakCPPT(?, ?)', ['2406020039', $kunjungan]);

            $data = [
                'show' => $show,
            ];

            return response()->json($data, 200);
        }
}
