<?php

namespace App\Http\Controllers\EMR\Form\RawatJalan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth, Storage;

class PengkajianRawatJalanDewasaController extends Controller
{
    function index($kunjungan)
    {
        return view('pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.dewasa.index');
    }
}
