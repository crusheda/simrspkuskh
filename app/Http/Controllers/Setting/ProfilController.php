<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;

class ProfilController extends Controller
{
    function index()
    {
        $show = '';

        $data = [
            'show' => $show,
        ];

        return view('pages.setting.profil')->with('list', $data);
    }
}
