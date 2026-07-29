<?php

namespace App\Http\Controllers\EMR\Form\GawatDarurat;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth, Storage;

class PengkajianGawatDaruratController extends Controller
{
    function index()
    {
        $cara_keluar = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',45)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $keadaan_keluar = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',46)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $data = [
            'cara_keluar' => $cara_keluar,
            'keadaan_keluar' => $keadaan_keluar,
        ];

        return view('pages.v2.medicalrecord.detail.form.pengkajian.gawat-darurat.index')->with('list',$data);
    }
}
