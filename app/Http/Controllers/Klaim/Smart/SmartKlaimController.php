<?php

namespace App\Http\Controllers\Klaim\Smart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SmartKlaimController extends Controller
{
    function index()
    {
        return view('pages.klaim.smart.index');
    }
}
