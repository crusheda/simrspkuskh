<?php

namespace App\Http\Controllers\Klaim\Smart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SmartKlaimController extends Controller
{
    // INDEX
    function index()
    {
        return view('pages.klaim.smart.index');
    }

    function indexRj()
    {
        return view('pages.klaim.smart.rj.index');
    }

    function indexRi()
    {
        return view('pages.klaim.smart.ri.index');
    }

    function indexRd()
    {
        return view('pages.klaim.smart.rd.index');
    }
}
