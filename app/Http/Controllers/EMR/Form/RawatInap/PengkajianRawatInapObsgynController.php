<?php

namespace App\Http\Controllers\EMR\Form\RawatInap;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\FieldEmpty;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpWord\TemplateProcessor;
use App\Services\LibreOfficeService;
use PHPJasper\PHPJasper;
use Carbon\Carbon;
use Auth, Storage;

class PengkajianRawatInapObsgynController extends Controller
{
    use FieldEmpty;

    function index($kunjungan)
    {
        $tingkat_kesadaran = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',179)
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

        $riwayat_alergi = DB::table('master.referensi')
            ->select('ID','DESKRIPSI')
            ->where('JENIS',180)
            ->where('STATUS',1)
            ->orderBy('TABEL_ID','ASC')
            ->get();

        $usia_kehamilan = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',299)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $jenis_persalinan = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',300)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $penyulit = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',301)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $jenis_kelamin = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',2)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $penolong = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',303)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $tempat = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',304)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $keadaan_sat_ini = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',302)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $usia = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',192)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $data = [
            'kunjungan' => $kunjungan,
            'tingkat_kesadaran' => $tingkat_kesadaran,
            'pasien' => $pasien,
            'riwayat_alergi' => $riwayat_alergi,
            'usia_kehamilan' => $usia_kehamilan,
            'jenis_persalinan' => $jenis_persalinan,
            'penyulit' => $penyulit,
            'jenis_kelamin' => $jenis_kelamin,
            'penolong' => $penolong,
            'tempat' => $tempat,
            'keadaan_sat_ini' => $keadaan_sat_ini,
            'usia' => $usia,
        ];

        return view('pages.v2.medicalrecord.detail.form.pengkajian.rawat-inap.obsgyn.index')->with('list',$data);
    }
}
