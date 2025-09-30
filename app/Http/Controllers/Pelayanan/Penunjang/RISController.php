<?php

namespace App\Http\Controllers\Pelayanan\Penunjang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RISController extends Controller
{
    function indexRIS()
    {
        return view('pages.pelayanan.penunjang.ris.index');
    }

    function getDCOM($filename)
    {
        $path = "\\\\192.168.254.50\\share\\TITIP_DICOM\\dcom 2025\\" . $filename;

        if (!file_exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($path, [
            'Content-Type' => 'application/dicom'
        ]);
    }
}
