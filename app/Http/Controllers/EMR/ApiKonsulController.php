<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiKonsulController extends Controller
{
    public function showKonsul($NOMOR)
    {
        $data = DB::table('pendaftaran.konsul AS kon')
            ->select(
                'kon.*',
                'ru.DESKRIPSI AS NAMARUANGAN',
                DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER')
            )
            ->leftJoin('pendaftaran.kunjungan AS pk','pk.NOMOR','=','kon.KUNJUNGAN')
            ->leftJoin('master.ruangan AS ru','ru.ID','=','pk.RUANGAN')
            ->leftJoin('master.dokter AS dr','dr.ID','=','pk.DPJP')
            ->where('kunjungan', $NOMOR)
            ->first();
        return response()->json($data, 200);
    }
}
