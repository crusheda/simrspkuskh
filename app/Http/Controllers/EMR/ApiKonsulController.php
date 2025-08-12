<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiKonsulController extends Controller
{
    public function showKonsul($NOMOR)
    {
        $data = DB::table('pendaftaran.konsul AS kon')
            ->select(
                'kon.*',
                'ru.DESKRIPSI AS NAMARUANGAN',
                DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER')
            )
            ->leftJoin('pendaftaran.kunjungan AS pk','pk.NOMOR','=','kon.KUNJUNGAN')
            ->leftJoin('master.ruangan AS ru','ru.ID','=','pk.RUANGAN')
            ->leftJoin('master.dokter AS dr','dr.ID','=','pk.DPJP')
            ->where('kunjungan', $NOMOR)
            ->get();
        return response()->json($data, 200);
    }
    public function masukKonsul($NOMOR)
    {
        $show = DB::table('pendaftaran.kunjungan AS pk')
            ->select(
                'pk.NOMOR AS KUNJUNGAN',
                'pp.NORM AS NORM',
                'pp.NOMOR AS NOPEN',
                'pk.RUANGAN AS RUANGAN',
                'pk.DPJP AS DPJP'
            )
            ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
            ->where('pk.NOMOR',$NOMOR)
            ->first();
        // print_r($show);
        // die();
        $data = DB::table('pendaftaran.konsul AS kon')
            ->select(
                'kon.*',
                'ru.DESKRIPSI AS NAMARUANGAN',
                'ruang.DESKRIPSI AS TUJUAN',
                DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER')
            )
            ->leftJoin('pendaftaran.kunjungan AS pk','pk.NOMOR','=','kon.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
            ->leftJoin('master.ruangan AS ru','ru.ID','=','pk.RUANGAN')
            ->leftJoin('master.ruangan AS ruang','ruang.ID','=','kon.TUJUAN')
            ->leftJoin('master.dokter AS dr','dr.ID','=','pk.DPJP')
            ->where('pp.NORM', $show->NORM)
            ->where('kon.TUJUAN', $show->RUANGAN)
            ->where('kon.DOKTER_TUJUAN', $show->DPJP)
            ->get();
        return response()->json($data, 200);
    }
    public function getJawabanKonsul($NOMOR)
    {
        $jawaban = DB::table('pendaftaran.konsul AS kon')
            ->select(
                'kon.*',
                'jk.JAWABAN AS JAWABAN',
                'jk.ANJURAN AS ANJURAN',
                'jk.TANGGAL AS TANGGAL_JAWABAN',
                'dr.NIP AS KODE_DOKTER',
                DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS JAWABDOKTER')
            )
            ->leftJoin('pendaftaran.jawaban_konsul AS jk','jk.KONSUL_NOMOR','=','kon.NOMOR')
            ->leftJoin('master.dokter AS dr','dr.ID','=','jk.DOKTER')
            ->where('kon.NOMOR', $NOMOR)
            ->first();
        // print_r($jawaban);
        // die();
        return response()->json($jawaban,200);
    }
}
