<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Pengguna;
use DB;

class RolesController extends Controller
{
    function index()
    {
        return view('pages.setting.roles');
    }

    // API ------------------------------------------------------------------------------------------------------------------
    function table()
    {
        $show = DB::table('aplikasi.pengguna AS ap')
                ->leftJoin('master.pegawai AS pg','pg.NIP','=','ap.NIP')
                ->leftJoin('simrspku_klaim.model_has_roles AS mhr', function($join) {
                    $join->on('mhr.model_id', '=', 'ap.ID')
                            ->where('mhr.model_type', '=', 'App\Models\Pengguna');
                })
                ->leftJoin('simrspku_klaim.roles AS r', 'r.id', '=', 'mhr.role_id')
                ->select(
                    'pg.*',
                    'ap.NAMA as APNAMA',
                    'ap.NIP as APNIP',
                    'ap.ID as ID_PENGGUNA',
                    'ap.LOGIN as USERNAME',
                    'ap.PASSWORD',
                    'ap.TERAKHIR_UBAH_PASSWOD',
                    DB::raw('master.getNamaLengkapPegawai(pg.NIP) AS NAMALENGKAP'),
                    DB::raw("GROUP_CONCAT(r.name SEPARATOR ', ') as ROLE_NAMES")
                )
                ->where('ap.ID','!=','0')
                ->groupBy('ap.ID')
                ->get();

        $data = [
            'show' => $show,
        ];

        return response()->json($data, 200);
    }

    function showRoles()
    {
        $show = Role::all();

        return response()->json($show, 200);
    }

    function createRoles(Request $request)
    {
        $show = Role::create(['name' => $request->role, 'guard_name' => 'web']);

        return response()->json($show, 200);
    }

    function updateRoles(Request $request)
    {
        $show = Role::find($request->id);
        $show->name = $request->name;
        $show->save();

        return response()->json($show, 200);
    }

    function deleteRoles($id)
    {
        $show = Role::find($request->id);
        $show->delete();

        return response()->json($show, 200);
    }

    // USER ------------------------------------------------------------------------------------------------------------------

    function updateRolesUser(Request $request)
    {
        $user = Pengguna::find($request->id);
        $user->assignRole([$request->role]);

        return response()->json($show, 200);
    }

}
