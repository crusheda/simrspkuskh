<?php

namespace App\Http\Controllers\EMR\Form\Lain;

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

class LembarTransferPasienInternalController extends Controller
{
    use FieldEmpty;

    function index($kunjungan)
    {

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
            ->leftJoin('master.ruangan AS ru', 'ru.ID', '=', 'pk.RUANGAN')
            ->select('pd.TANGGAL AS TGL_KEDATANGAN','dok.ID', DB::raw('master.getNamaLengkapPegawai(dok.NIP) AS NAMADOKTER'), 'ru.DESKRIPSI AS RUANGAN', 'ag.DESKRIPSI AS AGAMA', 'kj.DESKRIPSI AS PEKERJAAN')
            ->where('pk.NOMOR', $kunjungan)
            ->first();
        $ruangan = DB::table('master.ruangan as ru')
            ->where(function ($query) {
                $query->where('ru.ID', 'like', '1020301%')
                    ->orWhere('ru.ID', 'like', '1020302%');
            })
            ->where('ru.JENIS', '5')
            ->get();

        $data = [
            'kunjungan' => $kunjungan,
            'pasien' => $pasien,
            'ruangan' => $ruangan,
        ];

        return view('pages.v2.medicalrecord.detail.form.lain.lembar-transfer-pasien.index')->with('list',$data);
    }
}
