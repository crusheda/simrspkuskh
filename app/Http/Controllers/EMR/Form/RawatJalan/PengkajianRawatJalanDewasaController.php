<?php

namespace App\Http\Controllers\EMR\Form\RawatJalan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth, Storage;

class PengkajianRawatJalanDewasaController extends Controller
{
    function index($kunjungan)
    {
        $jenis_ruang = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',242)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $jenis_perawatan = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',243)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $dpjp = DB::table('pendaftaran.kunjungan AS pk')
            ->leftJoin('master.dokter AS dok', 'dok.ID', '=', 'pk.DPJP')
            ->select(DB::raw('master.getNamaLengkapPegawai(dok.NIP) AS NAMADOKTER'))
            ->where('pk.NOMOR', $kunjungan)
            ->first();

        $data = [
            'kunjungan' => $kunjungan,
            'jenis_ruang' => $jenis_ruang,
            'jenis_perawatan' => $jenis_perawatan,
            'dpjp' => $dpjp,
        ];
        // print_r($data);
        // die();
        return view('pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.dewasa.index')->with('list',$data);
    }

    function simpanFormDokter(Request $request)
    {
        print_r($request->all());
        die();
    }

    function simpanFormPerawat(Request $request)
    {
        print_r($request->all());
        die();
    }
}
