<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Pengguna;

class DashboardController extends Controller
{
    function index()
    {
        // $user = auth()->guard('web')->user();

        // $user = Pengguna::find(28);
        // $user->givePermissionTo('kunjungan_pasien','monitoring','smart_claim');

        return view('pages.dashboard.index');
    }

    function tambahPermission()
    {

    }
}

// REFERENSI
    // dd($user->getRoleNames());
    // dd($user->getAllPermissions());$roles = auth()->user()->getRoleNames();
    // dd(auth()->guard('web')->user()->id);

    // Role::create(['name' => 'direksi', 'guard_name' => 'web']);
    // Role::create(['name' => 'dokter', 'guard_name' => 'web']);
    // Role::create(['name' => 'perawat', 'guard_name' => 'web']);
    // Role::create(['name' => 'asuransi', 'guard_name' => 'web']);
    // Permission::create(['name' => 'kunjungan_pasien', 'guard_name' => 'web']);
    // Permission::create(['name' => 'smart_claim', 'guard_name' => 'web']);
    // $user = Pengguna::find(7);
    // $user->assignRole('admin');
    // $user->assignRole('admin');

    // $role = Role::findByName('admin', 'web');
    // $permission = Permission::findByName('monitoring', 'web');
    // $role->givePermissionTo($permission);

    // dd($user->getAllPermissions());
