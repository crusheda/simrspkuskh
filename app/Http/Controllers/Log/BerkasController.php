<?php

namespace App\Http\Controllers\Log;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\simrspku_klaim\klaim_verifikasi;
use App\Models\simrspku_klaim\klaim_file;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PHPJasper\PHPJasper;
use setasign\Fpdi\Fpdi;
use Carbon\Carbon;
use Auth, Storage;

class BerkasController extends Controller
{
    function index()
    {
        return view('pages.log.berkas.index');
    }

    function table()
    {
        $show = klaim_file::select('*')
                ->whereNull('deleted_at')
                ->where('status', 1)
                ->orderBy('updated_at','DESC')
                ->get();

        $data = [
            'show' => $show,
        ];

        return response()->json($data, 200);
    }

    function show($id)
    {
        $getFile = klaim_file::where('id',$id)->first();
        $output = storage_path('app/public/'.$getFile->filename);

        if (!file_exists($output)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($output,[
            'Content-Type' => 'application/pdf',
        ]);
    }
}
