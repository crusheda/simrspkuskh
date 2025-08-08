<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiKonsulController extends Controller
{
    public function showKonsul($NOMOR)
    {
        $data = DB::table('pendaftaran.konsul')
            ->where('kunjungan', $NOMOR)
            ->first();
        return response()->json($data, 200);
    }
}
