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
        $show = DB::table('aplikasi.pengguna AS ap')
                    ->leftJoin('master.pegawai AS pg','pg.NIP','=','ap.NIP')
                    ->leftJoin('pegawai.kontak_pegawai AS kp','kp.NIP','=','pg.NIP')
                    ->leftJoin('master.referensi AS ref', function($join) {
                        $join->on('ref.ID', '=', 'kp.JENIS')
                                ->where('ref.JENIS', '=', 8);
                    })
                    ->select(
                        'pg.*',
                        'kp.NOMOR AS NOHP',
                        'ref.DESKRIPSI AS JENISNOHP',
                        DB::raw('master.getNamaLengkapPegawai(pg.NIP) AS NAMALENGKAP')
                    )
                    ->where('ap.ID', Auth::user()->ID)
                    ->where('pg.STATUS', 1)
                    ->where('kp.STATUS', 1)
                    ->first();

        $data = [
            'show' => $show,
        ];

        return view('pages.setting.profil')->with('list', $data);
    }
}
