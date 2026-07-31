<?php

namespace App\Http\Controllers\EMR\Form\RawatJalan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengkajianRawatJalanDewasaController extends Controller
{
    function index($kunjungan)
    {
        return view('pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.dewasa.index');
    }
}
