<?php

namespace App\Http\Controllers\Pelayanan\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\pendaftaran\kunjungan;
use Illuminate\Support\Facades\DB;

class ResumeMedisController extends Controller
{
    function indexResume($KUNJUNGAN)
    {
        $resume = DB::table('pendaftaran.kunjungan AS pk')
                ->select(
                    'pk.*',
                    'pp.NORM',
                    DB::raw('master.getNamaLengkap(ps.NORM) AS NAMAPASIEN'))
                ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                ->leftJoin('master.pasien AS ps','ps.NORM','=','pp.NORM')
                ->where('pk.NOMOR',$KUNJUNGAN)
                ->first();

        $data = [
            'resume' => $resume,
            'KUNJUNGAN' => $KUNJUNGAN,
        ];
        // print_r($resume);
        // die();
        // return view('layouts.index2');
        return view('pages.pelayanan.pasien.resume.index')->with('list',$data);
    }
}
