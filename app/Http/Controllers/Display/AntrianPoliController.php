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

        $dokter = DB::table('penjamin_rs.dpjp as dpjp')
            ->leftJoin('master.dokter as md','md.ID','=','dpjp.DPJP_RS')
            ->leftJoin('aplikasi.pengguna as pe','pe.NIP','=','md.NIP')
            ->select('dpjp.DPJP_RS as ID_DPJP_SIMRS','dpjp.DPJP_PENJAMIN as ID_DPJP_BPJS',DB::raw('master.getNamaLengkapPegawai(md.NIP) AS NAMADOKTER'))
            ->where('dpjp.STATUS',1)
            ->get();

        $mapDokterPoli = DB::table('master.dokter_ruangan')->select('DOKTER','RUANGAN')->where('STATUS',1)->get();

        $data = [
            'poli' => $poli,
            'dokter' => $dokter,
            'mapDokterPoli' => $mapDokterPoli,
        ];

        // print_r($data);
        // die();
        return view('pages.display.antrian.poli.index')->with('list', $data);
    }

    function getDisplayAntrianPoli($tgl,$ruangan,$dr)
    {
        // $tgl = Carbon::now()->isoFormat('YYYY-MM-DD');
        $tgl = '2025-10-30';
        // $ruangan = '102010105'; // POLI BEDAH

        // Ambil yang sedang dipanggil dulu
        $dipanggil = DB::table('pendaftaran.panggilan_antrian_ruangan AS par')
            ->select(
                'par.ID',
                'pp.NORM',
                DB::raw('master.getNamaLengkap(pp.NORM) AS NAMAPASIEN'),
                'ar.POS','ar.NOMOR AS NOMORANTREAN','ar.STATUS AS STATUSANTREAN',
                'par.STATUS AS STATUSPANGGILAN',
                'ru.DESKRIPSI AS NAMARUANGAN',
                'ar.ID AS ANTRIAN_ID'
            )
            ->join('pendaftaran.antrian_ruangan AS ar', function($join) {
                $join->on('ar.ID','=','par.ANTRIAN_RUANGAN')
                    ->where('ar.STATUS', '!=', 0); // TIDAK BATAL KUNJUNGAN
            })
            ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','ar.REF')
            ->leftJoin('master.ruangan AS ru','ar.RUANGAN','=','ru.ID')
            ->where('ar.RUANGAN', $ruangan)
            ->where('ar.DOKTER', $dr)
            ->whereIn('par.STATUS', [1,2])
            ->where('ar.TANGGAL',$tgl)
            ->orderBy('par.ID', 'DESC')
            ->first();

        $antrianDipanggilId = $dipanggil->ANTRIAN_ID ?? null;

        // MENUNGGU (exclude yang dipanggil)
        $menunggu = DB::table('pendaftaran.antrian_ruangan AS ar')
            ->select(
                'pp.NORM',
                'ar.ID',
                DB::raw('master.getNamaLengkap(pp.NORM) AS NAMAPASIEN'),
                'ar.POS','ar.NOMOR AS NOMORANTREAN','ar.STATUS AS STATUSANTREAN',
                // 'par.STATUS AS STATUSPANGGILAN',
                'ru.DESKRIPSI AS NAMARUANGAN'
            )
            // ->join('pendaftaran.antrian_ruangan AS ar', function($join) {
            //     $join->on('ar.ID','=','par.ANTRIAN_RUANGAN')
            //         ->where('ar.STATUS', '!=', 0); // TIDAK BATAL KUNJUNGAN
            // })
            ->leftJoin('master.ruangan AS ru','ar.RUANGAN','=','ru.ID')
            ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','ar.REF')
            ->where('ar.RUANGAN', $ruangan)
            ->where('ar.DOKTER', $dr)
            ->where('ar.STATUS', 1) // MENUNGGU DIPANGGIL
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
                'pp.NORM','ar.ID',
                DB::raw('master.getNamaLengkap(pp.NORM) AS NAMAPASIEN'),
                'ar.POS','ar.NOMOR AS NOMORANTREAN','ar.STATUS AS STATUSANTREAN',
                'par.STATUS AS STATUSPANGGILAN',
                'ru.DESKRIPSI AS NAMARUANGAN',
                // 'ar.ID AS ANTRIAN_ID'
            )
            ->join('pendaftaran.antrian_ruangan AS ar', function($join) {
                $join->on('ar.ID','=','par.ANTRIAN_RUANGAN')
                    ->where('ar.STATUS', '!=', 0); // TIDAK BATAL KUNJUNGAN
            })
            ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','ar.REF')
            ->leftJoin('master.ruangan AS ru','ar.RUANGAN','=','ru.ID')
            ->where('ar.RUANGAN', $ruangan)
            ->where('ar.DOKTER', $dr)
            ->where('par.STATUS', 2)
            ->where('ar.TANGGAL',$tgl)
            ->when($antrianDipanggilId, function($q) use ($antrianDipanggilId) {
                $q->where('ar.ID','!=',$antrianDipanggilId);
            })
            ->groupBy('NORM','ID','NAMAPASIEN','POS','NOMORANTREAN','STATUSANTREAN','STATUSPANGGILAN','NAMARUANGAN')
            ->orderBy('par.ID', 'DESC')
            ->get();

        $poli = DB::table('master.ruangan AS ru')
            ->select('ru.DESKRIPSI AS NAMARUANGAN')
            ->where('ru.ID', $ruangan)
            ->first();

        $dokter = DB::table('penjamin_rs.dpjp as dpjp')
            ->leftJoin('master.dokter as md','md.ID','=','dpjp.DPJP_RS')
            ->leftJoin('aplikasi.pengguna as pe','pe.NIP','=','md.NIP')
            ->select(DB::raw('master.getNamaLengkapPegawai(md.NIP) AS NAMADOKTER'))
            ->where('dpjp.STATUS',1)
            ->where('dpjp.DPJP_PENJAMIN',$dr)
            ->first();

        return response()->json([
            'menunggu' => $menunggu,
            'dipanggil' => $dipanggil,
            'selesai' => $selesai,
            'poli' => $poli,
            'dokter' => $dokter,
        ], 200);
    }
}
