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

class PengkajianKhususPenyakitMenularController extends Controller
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

        return view('pages.v2.medicalrecord.detail.form.pengkajian.khusus.penyakitmenular.index')->with('list',$data);
    }

    function getFormKhusus(Request $request, $KUNJUNGAN)
    {
        $data = DB::table('simrspku_pengkajian.pengkajian_penyakit_menular')
            ->where('KUNJUNGAN', $KUNJUNGAN)
            ->where('STATUS', 1)
            ->first();

        if (!$data) {
            return response()->json([
                'data' => null
            ]);
        }

        // Decode JSON agar response menjadi array/object JSON,
        // bukan string JSON.
        $data->PASIEN_MENGETAHUI = $this->decodeJson($data->PASIEN_MENGETAHUI);
        $data->SUMBER_INFORMASI = $this->decodeJson($data->SUMBER_INFORMASI);
        $data->INFORMASI_PENGOBATAN = $this->decodeJson($data->INFORMASI_PENGOBATAN);
        $data->LAMA_PENGOBATAN = $this->decodeJson($data->LAMA_PENGOBATAN);
        $data->PEMERIKSAAN_RUTIN = $this->decodeJson($data->PEMERIKSAAN_RUTIN);
        $data->CARA_PENULARAN = $this->decodeJson($data->CARA_PENULARAN);
        $data->PENYAKIT_PENYERTA = $this->decodeJson($data->PENYAKIT_PENYERTA);

        return response()->json([
            'data' => $data
        ]);
    }

    public function simpanFormKhusus(Request $request, $KUNJUNGAN)
    {
        try {

            /*
            |--------------------------------------------------------------------------
            | DATA DARI FORM
            |--------------------------------------------------------------------------
            */

            $pasienMengetahui = $request->input('pm_pasien_mengetahui');

            $sumberInformasi = $request->input(
                'pm_sumber_informasi',
                []
            );

            $infoPengobatan = $request->input(
                'pm_info_pengobatan'
            );

            $lamaPengobatan = [
                'lama' => $request->input('pm_lama_pengobatan'),
                'satuan' => $request->input('pm_satuan_pengobatan'),
            ];

            $pemeriksaanRutin = $request->input(
                'pm_pemeriksaan_rutin'
            );

            $tempatPemeriksaanRutin = $request->input(
                'pm_pemeriksaan_rutin_tempat'
            );

            $caraPenularan = $request->input(
                'pm_cara_penularan',
                []
            );

            $penyakitPenyerta = $request->input(
                'pm_penyakit_penyerta'
            );

            $keteranganPenyerta = $request->input(
                'pm_penyerta_keterangan'
            );


            /*
            |--------------------------------------------------------------------------
            | BERSIHKAN ARRAY
            |--------------------------------------------------------------------------
            */

            if (!is_array($sumberInformasi)) {
                $sumberInformasi = [$sumberInformasi];
            }

            if (!is_array($caraPenularan)) {
                $caraPenularan = [$caraPenularan];
            }


            /*
            |--------------------------------------------------------------------------
            | LAMA PENGOBATAN
            |--------------------------------------------------------------------------
            */

            // Jika dua-duanya kosong, simpan NULL
            if (
                empty($lamaPengobatan['lama']) &&
                empty($lamaPengobatan['satuan'])
            ) {
                $lamaPengobatan = null;
            }


            /*
            |--------------------------------------------------------------------------
            | DATA YANG DISIMPAN
            |--------------------------------------------------------------------------
            */

            $save = [

                'KUNJUNGAN' => $KUNJUNGAN,

                'PASIEN_MENGETAHUI' =>
                    $pasienMengetahui !== null
                        ? json_encode(
                            $pasienMengetahui,
                            JSON_UNESCAPED_UNICODE
                        )
                        : null,

                'SUMBER_INFORMASI' =>
                    !empty($sumberInformasi)
                        ? json_encode(
                            array_values($sumberInformasi),
                            JSON_UNESCAPED_UNICODE
                        )
                        : null,

                'INFORMASI_PENGOBATAN' =>
                    $infoPengobatan !== null
                        ? json_encode(
                            $infoPengobatan,
                            JSON_UNESCAPED_UNICODE
                        )
                        : null,

                'LAMA_PENGOBATAN' =>
                    $lamaPengobatan !== null
                        ? json_encode(
                            $lamaPengobatan,
                            JSON_UNESCAPED_UNICODE
                        )
                        : null,

                'PEMERIKSAAN_RUTIN' =>
                    $pemeriksaanRutin !== null
                        ? json_encode(
                            $pemeriksaanRutin,
                            JSON_UNESCAPED_UNICODE
                        )
                        : null,

                'TEMPAT_PEMERIKSAAN_RUTIN' =>
                    $tempatPemeriksaanRutin,

                'CARA_PENULARAN' =>
                    !empty($caraPenularan)
                        ? json_encode(
                            array_values($caraPenularan),
                            JSON_UNESCAPED_UNICODE
                        )
                        : null,

                'PENYAKIT_PENYERTA' =>
                    $penyakitPenyerta !== null
                        ? json_encode(
                            $penyakitPenyerta,
                            JSON_UNESCAPED_UNICODE
                        )
                        : null,

                'KETERANGAN_PENYAKIT_PENYERTA' =>
                    $keteranganPenyerta,

                'OLEH' => auth()->id(),

                'STATUS' => 1,
            ];


            /*
            |--------------------------------------------------------------------------
            | INSERT / UPDATE
            |--------------------------------------------------------------------------
            */

            $table = 'simrspku_pengkajian.pengkajian_penyakit_menular';

            $existing = DB::table($table)
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->first();

            if ($existing) {

                DB::table($table)
                    ->where('KUNJUNGAN', $KUNJUNGAN)
                    ->update($save);

            } else {

                DB::table($table)
                    ->insert($save);
            }


            /*
            |--------------------------------------------------------------------------
            | RESPONSE
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan.'
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Data gagal disimpan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function decodeJson($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_array($value)) {
            return $value;
        }

        $decoded = json_decode($value, true);

        return json_last_error() === JSON_ERROR_NONE
            ? $decoded
            : $value;
    }

}
