<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\simrspku_klaim\klaim_verifikasi;
use App\Models\simrspku_klaim\klaim_file;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PHPJasper\PHPJasper;
use Carbon\Carbon;
use Auth, Storage;

class ApiRehabMedikController extends Controller
{
    function getFormKfr($NORM, $KUNJUNGAN)
    {
        $show = DB::table('simrspku_klaim.form_kfr AS kfr')
                ->where('kfr.rm',$NORM)
                ->whereNull('kfr.deleted_at')
                ->orderBy('kfr.tgl','DESC')
                ->get();

        $form = DB::table('simrspku_klaim.form_kfr AS kfr')
                ->where('kfr.nomor',$KUNJUNGAN)
                ->whereNull('kfr.deleted_at')
                ->first();

        $data = [
            'show' => $show,
            'form' => $form,
        ];

        return response()->json($data, 200);
    }
}
