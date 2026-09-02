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

    function getFormTransfer(string $kunjungan)
    {
        $data = DB::table('simrspku_pengkajian.lembar_transfer_internal')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    function simpanFormTransfer(
        Request $request,
        string $kunjungan
    ) {
        try {

            DB::table('simrspku_pengkajian.lembar_transfer_internal')
                ->updateOrInsert(
                    [
                        'KUNJUNGAN' => $kunjungan
                    ],
                    [
                        'UNIT_TUJUAN' => $request->unit_tujuan,
                        'PETUGAS' => $request->petugas,

                        'TANGGAL_TRANSFER' => $request->sn_tanggal_lahir,
                        'JAM_TRANSFER' => $request->sn_jam_lahir,

                        'KLINIS' => $request->klinis,
                        'INDIKASI' => $request->indikasi,
                        'TERAPI' => $request->terapi,

                        'KATEGORI_TRANS' => $request->kategori_trans,

                        'SPRI'       => $request->spri ?? 0,
                        'SURAT_HASIL' => $request->shp ?? 0,
                        'SURAT_LAIN' => $request->slain ?? 0,

                        'OLEH'                  => auth()->id(),
                        'STATUS'                => 1,
                        'TANGGAL'               => now()
                    ]
                );

            return response()->json([
                'status'  => true,
                'message' => 'Data lembar transfer internal berhasil disimpan.'
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Data gagal disimpan.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
