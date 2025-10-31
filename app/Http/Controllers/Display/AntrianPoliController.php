<?php

namespace App\Http\Controllers\Display;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AntrianPoliController extends Controller
{
    function index()
    {
        $poli = DB::table('master.ruangan AS ru')
            ->select('ru.ID AS IDRUANGAN','ru.DESKRIPSI AS NAMARUANGAN')
            ->where(function ($query) {
                $query->where('ru.ID', 'LIKE', '1020101%')
                    ->orWhere('ru.ID', 'LIKE', '1020702%');
            })
            ->where('ru.JENIS',5)
            ->where('ru.STATUS',1)
            ->get();

        $data = [
            'poli' => $poli,
        ];

        return view('pages.display.antrian.poli.index')->with('list', $data);
    }

    function getDisplayAntrianPoli($tgl,$ruangan)
    {
        // $tgl = Carbon::now()->isoFormat('YYYY-MM-DD');
        $tgl = '2025-10-15';
        // $ruangan = '102010105'; // POLI BEDAH

        // Ambil yang sedang dipanggil dulu
        $dipanggil = DB::table('pendaftaran.panggilan_antrian_ruangan AS par')
            ->select(
                'par.ID',
                'pp.NORM',
                'ar.POS','ar.NOMOR AS NOMORANTREAN','ar.STATUS AS STATUSANTREAN',
                'par.STATUS AS STATUSPANGGILAN',
                'ru.DESKRIPSI AS NAMARUANGAN',
                'ar.ID AS ANTRIAN_ID'
            )
            ->leftJoin('pendaftaran.antrian_ruangan AS ar','par.ANTRIAN_RUANGAN','=','ar.ID')
            ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','ar.REF')
            ->leftJoin('master.ruangan AS ru','ar.RUANGAN','=','ru.ID')
            ->where('ar.RUANGAN', $ruangan)
            ->where('par.STATUS', 2)
            ->where('ar.TANGGAL',$tgl)
            ->orderBy('par.ID', 'DESC')
            ->first();

        $antrianDipanggilId = $dipanggil->ANTRIAN_ID ?? null;

        // MENUNGGU (exclude yang dipanggil)
        $menunggu = DB::table('pendaftaran.antrian_ruangan AS ar')
            ->select(
                'pp.NORM',
                DB::raw('master.getNamaLengkap(pp.NORM) AS NAMAPASIEN'),
                'ar.POS','ar.NOMOR AS NOMORANTREAN','ar.STATUS AS STATUSANTREAN',
                'ru.DESKRIPSI AS NAMARUANGAN'
            )
            ->leftJoin('master.ruangan AS ru','ar.RUANGAN','=','ru.ID')
            ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','ar.REF')
            ->where('ar.RUANGAN', $ruangan)
            ->whereIn('ar.STATUS', [1]) // MENUNGGU
            ->where('ar.TANGGAL',$tgl)
            ->when($antrianDipanggilId, function($q) use ($antrianDipanggilId) {
                $q->where('ar.ID','!=',$antrianDipanggilId);
            })
            ->whereNotExists(function($q) {
                $q->select(DB::raw(1))
                ->from('pendaftaran.panggilan_antrian_ruangan AS par2')
                ->whereRaw('par2.ANTRIAN_RUANGAN = ar.ID');
            })
            ->orderBy('ar.NOMOR', 'ASC')
            ->get();

        // SELESAI (exclude yang dipanggil)
        $selesai = DB::table('pendaftaran.panggilan_antrian_ruangan AS par')
            ->select(
                'pp.NORM',
                'ar.POS','ar.NOMOR AS NOMORANTREAN','ar.STATUS AS STATUSANTREAN',
                'par.STATUS AS STATUSPANGGILAN',
                'ru.DESKRIPSI AS NAMARUANGAN',
                'ar.ID AS ANTRIAN_ID'
            )
            ->leftJoin('pendaftaran.antrian_ruangan AS ar','par.ANTRIAN_RUANGAN','=','ar.ID')
            ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','ar.REF')
            ->leftJoin('master.ruangan AS ru','ar.RUANGAN','=','ru.ID')
            ->where('ar.RUANGAN', $ruangan)
            ->where('par.STATUS', 2)
            ->where('ar.TANGGAL',$tgl)
            ->when($antrianDipanggilId, function($q) use ($antrianDipanggilId) {
                $q->where('ar.ID','!=',$antrianDipanggilId);
            })
            ->orderBy('par.ID', 'DESC')
            ->get();

        $poli = DB::table('master.ruangan AS ru')
            ->select('ru.DESKRIPSI AS NAMARUANGAN')
            ->where('ru.ID', $ruangan)
            ->first();

        return response()->json([
            'menunggu' => $menunggu,
            'dipanggil' => $dipanggil,
            'selesai' => $selesai,
            'poli' => $poli,
        ], 200);
    }
}
