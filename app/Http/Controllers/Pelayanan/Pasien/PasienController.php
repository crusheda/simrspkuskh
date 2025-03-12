<?php

namespace App\Http\Controllers\Pelayanan\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\pendaftaran\kunjungan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PasienController extends Controller
{
    function index($NOPEN)
    {
        // $show = kunjungan::where('STATUS', 1)
        //         ->where('KELUAR', null)
        //         ->orderBy('MASUK','DESC')
        //         ->get();

        // print_r($show);
        // die();

        // $data = [
        //     'show' => $show,
        // ];

        // return view('layouts.index2');
        return view('pages.pelayanan.pasien.resume.index');
    }
}
