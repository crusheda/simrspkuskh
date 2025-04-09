<?php

namespace App\Http\Controllers\Pelayanan\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\pendaftaran\kunjungan;
use Illuminate\Support\Facades\DB;

class ResumeMedisController extends Controller
{
    function indexResume($KUNJUNGAN)
    {
        $resume = DB::table('pendaftaran.kunjungan AS pk')
                ->select(
                    'pk.*',
                    'pp.NORM',
                    'ps.TANGGAL_LAHIR',
                    'ru.DESKRIPSI',
                    'rjk.DESKRIPSI AS JK',
                    DB::raw('master.getNamaLengkap(ps.NORM) AS NAMAPASIEN'),
                    DB::raw('master.getNamaLengkapPegawai(md.NIP) AS NAMADOKTER'))
                ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                ->leftJoin('master.pasien AS ps','ps.NORM','=','pp.NORM')
                ->leftJoin('master.ruangan AS ru','ru.ID','=','pk.RUANGAN')
                ->leftJoin('master.dokter AS md','md.ID','=','pk.DPJP')
                ->leftJoin('master.referensi AS rjk', function($join) {
                                $join->on('rjk.ID','=','ps.JENIS_KELAMIN')
                                    ->where('rjk.JENIS',2);
                            })
                ->where(function ($query) {
                    $query->where('pk.RUANGAN', 'LIKE', '1020101%')
                            ->orWhere('pk.RUANGAN', 'LIKE', '1020201%');
                })

                ->where('ru.STATUS', 1)
                ->where('pk.NOMOR',$KUNJUNGAN)
                ->where(function ($query) {
                    $query->where('pk.STATUS', '=', '1')
                            ->orWhere('pk.STATUS', '=', '2');
                })
                ->first();

        $awal = DB::table('pendaftaran.kunjungan AS pku')
                ->select(
                    'pku.*',
                    'ku.DESKRIPSI'
                )
                ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pku.NOPEN')
                ->leftJoin('medicalrecord.keluhan_utama AS ku','ku.KUNJUNGAN','=','pku.NOMOR')
                ->where('pku.NOMOR',$KUNJUNGAN)
                ->where('pku.REF', null)
                ->first();

        $data = [
            'resume' => $resume,
            'awal' => $awal,
            'KUNJUNGAN' => $KUNJUNGAN,
        ];
        // print_r($awal);
        // die();
        // return view('layouts.index2');
        return view('pages.pelayanan.pasien.resume.index')->with('list',$data);
    }
}
