<?php

namespace App\Http\Controllers\EMR\IGD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ModulMatrixController extends Controller
{
    // INDEX
    function index()
    {
        $yearMonth = Carbon::now()->isoFormat('YYYY-MM');
        $dr = DB::table('master.dokter AS dr')
                ->select(
                    'dr.id',
                    'dr.NIP',
                    DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'),
                    'ref.DESKRIPSI'
                )
                ->leftJoin('master.pegawai AS pg','pg.NIP','=','dr.NIP')
                ->leftJoin('master.referensi AS ref', function($join) {
                    $join->on('ref.ID','=','pg.SMF')
                        ->where('ref.JENIS', '26');
                })
                ->leftJoin('master.dokter_ruangan AS dru','dru.DOKTER','=','dr.ID')
                ->where('dr.STATUS','1')
                ->where('dru.STATUS','1')
                ->where(function ($query) {
                    $query->where('dru.RUANGAN', 'LIKE', '1020101%');
                })
                // ->orderByRaw("CASE WHEN ref.ID = '0' THEN 1 ELSE 0 END")
                ->orderBy('ref.DESKRIPSI','ASC')
                ->groupBy('dr.id','dr.NIP','NAMADOKTER')
                ->get();

        $data = [
            'yearMonth' => $yearMonth,
            'dr' => $dr,
        ];

        return view('pages.klaim.index')->with('list', $data);
    }
}
