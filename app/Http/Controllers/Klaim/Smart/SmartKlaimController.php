<?php

namespace App\Http\Controllers\Klaim\Smart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SmartKlaimController extends Controller
{
    // INDEX
    function index()
    {
        return view('pages.klaim.smart.index');
    }

    function indexRj()
    {
        return view('pages.klaim.smart.rj.index');
    }

    function indexRi()
    {
        return view('pages.klaim.smart.ri.index');
    }

    function indexRd()
    {
        return view('pages.klaim.smart.rd.index');
    }

    // API --
    function tableRj()
    {
        $show = DB::table('pendaftaran.kunjungan AS pk')
                ->select(
                    'pk.*',
                    'pp.NORM',
                    'ru.DESKRIPSI AS NAMARUANGAN',
                    DB::raw('master.getNamaLengkap(ps.NORM) AS NAMAPASIEN'),
                    DB::raw('master.getNamaLengkapPegawai(master.dokter.NIP) AS NAMADOKTER')
                )
                // ->selectRaw('SELECT master.getNamaLengkapPegawai("1708205") from master')
                ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                ->leftJoin('master.pasien AS ps','ps.NORM','=','pp.NORM')
                ->leftJoin('aplikasi.pengguna','aplikasi.pengguna.ID','=','pk.DITERIMA_OLEH')
                ->leftJoin('master.ruangan AS ru','ru.ID','=','pk.RUANGAN')
                ->leftJoin('master.dokter','master.dokter.ID','=','pk.DPJP')

                ->where(function ($query) {
                    $query->where('pk.RUANGAN', 'LIKE', '1020101%')
                            ->orWhere('pk.RUANGAN', 'LIKE', '1020201%');
                })

                ->where('ru.STATUS', 1)
                ->where('pk.STATUS', 1)
                ->where('pk.KELUAR', null)
                ->orderBy('pk.MASUK','DESC')
                ->get();

        $data = [
            'show' => $show,
        ];

        return response()->json($data, 200);
    }
}
