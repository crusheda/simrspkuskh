<?php

namespace App\Http\Controllers\EMR\Form\Khusus;

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

class PengkajianKhususKorbanKekerasanController extends Controller
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
            ->select('dok.ID', DB::raw('master.getNamaLengkapPegawai(dok.NIP) AS NAMADOKTER'), 'ag.DESKRIPSI AS AGAMA', 'kj.DESKRIPSI AS PEKERJAAN')
            ->where('pk.NOMOR', $kunjungan)
            ->first();

        $data = [
            'kunjungan' => $kunjungan,
            'pasien' => $pasien
        ];

        return view('pages.v2.medicalrecord.detail.form.pengkajian.khusus.korbankekerasan.index')->with('list',$data);
    }

    function getFormKhusus($KUNJUNGAN)
    {
        $data = DB::table('simrspku_pengkajian.pengkajian_kekerasan_penganiayaan')
            ->where('KUNJUNGAN', $KUNJUNGAN)
            ->where('STATUS', 1)
            ->first();

        return response()->json([
            'data' => $data
        ]);
    }


    function simpanFormKhusus(Request $request, $KUNJUNGAN)
    {
        $data = [
            'KUNJUNGAN' => $KUNJUNGAN,

            'MENGALAMI_KEKERASAN' =>
                $request->input('kp_mengalami_kekerasan'),

            'JENIS_KEKERASAN' =>
                $request->input('kp_jenis_kekerasan'),

            'LAMA_KEKERASAN' =>
                $request->input('kp_lama_kekerasan'),

            'FREKUENSI_KEKERASAN' =>
                $request->input('kp_frekuensi_kekerasan'),

            'PELAKU_KEKERASAN' =>
                $request->input('kp_pelaku_kekerasan'),

            'MEMERLUKAN_PENDAMPINGAN' =>
                $request->input('kp_memerlukan_pendampingan'),

            'OLEH' => auth()->id(),
            'STATUS' => 1,
        ];

        $existing = DB::table(
            'simrspku_pengkajian.pengkajian_kekerasan_penganiayaan'
        )
            ->where('KUNJUNGAN', $KUNJUNGAN)
            ->first();

        if ($existing) {

            DB::table(
                'simrspku_pengkajian.pengkajian_kekerasan_penganiayaan'
            )
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->update($data);

        } else {

            DB::table(
                'simrspku_pengkajian.pengkajian_kekerasan_penganiayaan'
            )
                ->insert($data);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.'
        ]);
    }

}
