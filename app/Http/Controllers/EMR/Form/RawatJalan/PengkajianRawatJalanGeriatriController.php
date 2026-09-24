<?php

namespace App\Http\Controllers\EMR\Form\RawatJalan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth, Storage;

class PengkajianRawatJalanGeriatriController extends Controller
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

        $pasien = DB::table('pendaftaran.kunjungan AS pk')
            ->leftJoin('pendaftaran.pendaftaran AS pd', 'pd.NOMOR', '=', 'pk.NOPEN')
            ->leftJoin('master.pasien AS p', 'p.NORM', '=', 'pd.NORM')
            ->leftJoin('master.referensi AS ag', function ($join) {
                $join->on('ag.ID', '=', 'p.AGAMA')
                    ->where('ag.JENIS', '=', '1');
            })
            ->leftJoin('master.referensi AS kj', function ($join) {
                $join->on('kj.ID', '=', 'p.PEKERJAAN')
                    ->where('kj.JENIS', '=', '4');
            })
            ->leftJoin('master.dokter AS dok', 'dok.ID', '=', 'pk.DPJP')
            ->select('dok.ID', DB::raw('master.getNamaLengkapPegawai(dok.NIP) AS NAMADOKTER'), 'ag.DESKRIPSI AS AGAMA', 'kj.DESKRIPSI AS PEKERJAAN')
            ->where('pk.NOMOR', $kunjungan)
            ->first();

        // $frekuensi_obat = DB::table('master.frekuensi_aturan_resep')
        //         ->select('ID','FREKUENSI')
        //         ->where('STATUS',1)
        //         ->orderBy('ID','ASC')
        //         ->get();

        // $rute_obat = DB::table('master.referensi')
        //         ->select('ID','DESKRIPSI')
        //         ->where('JENIS',217)
        //         ->where('STATUS',1)
        //         ->orderBy('TABEL_ID','ASC')
        //         ->get();

        $jenis_alergi = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',180)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $kesadaran = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',179)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();
        $jenisBarthel = [
            232 => 'Mengendalikan rangsang defekasi',
            233 => 'Mengendalikan rangsang berkemih',
            234 => 'Membersihkan diri (seka muka, sisir rambut, sikat gigi)',
            235 => 'Penggunaan jamban, masuk dan keluar',
            236 => 'Makan',
            237 => 'Berubah sikap dari berbaring ke duduk',
            238 => 'Berpindah/berjalan',
            239 => 'Memakai baju',
            240 => 'Naik turun tangga',
            241 => 'Mandi',
        ];

        $referensiBarthel = DB::table('master.referensi')
            ->whereIn('JENIS', array_keys($jenisBarthel))
            ->where('STATUS', 1)
            ->orderBy('JENIS')
            ->orderBy('ID')
            ->get()
            ->groupBy('JENIS');

        $data = [
            'kunjungan' => $kunjungan,
            'jenis_ruang' => $jenis_ruang,
            'jenis_perawatan' => $jenis_perawatan,
            'pasien' => $pasien,
            'jenis_alergi' => $jenis_alergi,
            'kesadaran' => $kesadaran,
            'jenisBarthel' => $jenisBarthel,
            'referensiBarthel' => $referensiBarthel,
        ];
        // print_r($data);
        // die();
        return view('pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.geriatri.index')->with('list',$data);
    }

    function simpanFormDokterRJG(Request $request)
    {
        
    }

    public function getFormDokterRJG($kunjungan)
    {
        
    }

    function simpanFormPerawatRJG(Request $request)
    {
        
    }

    public function getFormPerawatRJG($kunjungan)
    {
        
    }
}
