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

class PengkajianRawatInapNeonatusController extends Controller
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

        $data = [
            'kunjungan' => $kunjungan,
            'tingkat_kesadaran' => $tingkat_kesadaran,
            'pasien' => $pasien,
            'riwayat_alergi' => $riwayat_alergi
        ];

        return view('pages.v2.medicalrecord.detail.form.pengkajian.rawat-inap.neonatus.index')->with('list',$data);
    }
}
