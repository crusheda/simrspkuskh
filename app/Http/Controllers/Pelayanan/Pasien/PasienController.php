<?php

namespace App\Http\Controllers\Pelayanan\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\pendaftaran\kunjungan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PasienController extends Controller
{
    function indexIdentitas($KUNJUNGAN)
    {
        $data = [
            'KUNJUNGAN' => $KUNJUNGAN,
        ];

        return view('pages.pelayanan.pasien.identitas.index')->with('list',$data);
    }

    function indexResume($KUNJUNGAN)
    {
        // $show = kunjungan::where('STATUS', 1)
        //         ->where('KELUAR', null)
        //         ->orderBy('MASUK','DESC')
        //         ->get();

        // print_r($show);
        // die();

        $data = [
            // 'show' => $show,
            'KUNJUNGAN' => $KUNJUNGAN,
        ];

        // return view('layouts.index2');
        return view('pages.pelayanan.pasien.resume.index')->with('list',$data);
    }
}
