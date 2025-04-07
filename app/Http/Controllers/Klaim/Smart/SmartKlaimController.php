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
    function tableRj() // RAWAT JALAN
    {
        $show = DB::table('pendaftaran.kunjungan AS pk')
                ->select(
                    'pk.*',
                    'pp.NORM',
                    'kjs.noSEP AS NOSEP',
                    'ru.DESKRIPSI AS NAMARUANGAN',
                    DB::raw('master.getNamaLengkap(ps.NORM) AS NAMAPASIEN'),
                    DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER')
                )
                // ->selectRaw('SELECT master.getNamaLengkapPegawai("1708205") from master')
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
