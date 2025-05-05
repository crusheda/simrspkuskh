<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Pengguna;
use DB;

class PermissionsController extends Controller
{
    function index()
    {
        $show = '';

        $data = [
            'show' => $show,
        ];

        return view('pages.setting.permissions')->with('list', $data);
    }
}
