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

class PengkajianKhususRemajaController extends Controller
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

        return view('pages.v2.medicalrecord.detail.form.pengkajian.khusus.remaja.index')->with('list',$data);
    }

    public function getFormKhusus(Request $request, $KUNJUNGAN)
    {
        $data = DB::table('simrspku_pengkajian.pengkajian_remaja')
            ->where('KUNJUNGAN', $KUNJUNGAN)
            ->where('STATUS', 1)
            ->first();

        if (!$data) {
            return response()->json([
                'data' => null
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | DECODE JSON
        |--------------------------------------------------------------------------
        */

        $data->MENSTRUASI = $this->decodeJson($data->MENSTRUASI);
        $data->SIKLUS_HAID = $this->decodeJson($data->SIKLUS_HAID);
        $data->EMOSI_HAID = $this->decodeJson($data->EMOSI_HAID);
        $data->KELUHAN_HAID = $this->decodeJson($data->KELUHAN_HAID);
        $data->TINDAKAN_HAID = $this->decodeJson($data->TINDAKAN_HAID);

        $data->MIMPI_BASAH = $this->decodeJson($data->MIMPI_BASAH);

        $data->NIKAH_DINI = $this->decodeJson($data->NIKAH_DINI);
        $data->ALASAN_NIKAH_DINI = $this->decodeJson($data->ALASAN_NIKAH_DINI);

        $data->PENGETAHUAN_PMS = $this->decodeJson($data->PENGETAHUAN_PMS);
        $data->SUMBER_PENGETAHUAN_PMS = $this->decodeJson($data->SUMBER_PENGETAHUAN_PMS);
        $data->PENCEGAHAN_PMS = $this->decodeJson($data->PENCEGAHAN_PMS);

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

            // KHUSUS PEREMPUAN

            $menstruasi = $request->input(
                'pubertas_perempuan_menstruasi'
            );

            $haidPertamaUsia = $request->input(
                'pubertas_haid_pertama_usia'
            );

            $siklusHaid = $request->input(
                'pubertas_siklus_haid'
            );

            $lamaHaid = $request->input(
                'pubertas_lama_haid'
            );

            $jarakSiklusHaid = $request->input(
                'pubertas_jarak_siklus'
            );

            $emosiHaid = $request->input(
                'pubertas_emosi_haid',
                []
            );

            $sumberInfoPubertas = $request->input(
                'pubertas_sumber_info'
            );

            $keluhanHaid = $request->input(
                'pubertas_keluhan_haid'
            );

            $tindakanHaid = $request->input(
                'pubertas_tindakan_haid',
                []
            );

            $tindakanHaidLainnya = $request->input(
                'pubertas_tindakan_haid_lainnya'
            );


            // KHUSUS LAKI-LAKI

            $mimpiBasah = $request->input(
                'pubertas_laki_mimpibasah'
            );

            $mimpiBasahPertamaUsia = $request->input(
                'pubertas_mimpi_pertama_usia'
            );


            // PERNIKAHAN DINI

            $nikahDini = $request->input(
                'pubertas_nikah_dini'
            );

            $alasanNikahDini = $request->input(
                'pubertas_alasan_nikah_dini',
                []
            );

            $alasanNikahDiniLainnya = $request->input(
                'pubertas_alasan_nikah_dini_lainnya'
            );


            // PMS

            $pengetahuanPms = $request->input(
                'pubertas_pengetahuan_pms'
            );

            $sumberPengetahuanPms = $request->input(
                'pubertas_sumber_pms',
                []
            );

            $pencegahanPms = $request->input(
                'pubertas_pencegahan_pms'
            );


            /*
            |--------------------------------------------------------------------------
            | BERSIHKAN ARRAY
            |--------------------------------------------------------------------------
            */

            if (!is_array($emosiHaid)) {
                $emosiHaid = $emosiHaid !== null
                    ? [$emosiHaid]
                    : [];
            }

            if (!is_array($tindakanHaid)) {
                $tindakanHaid = $tindakanHaid !== null
                    ? [$tindakanHaid]
                    : [];
            }

            if (!is_array($alasanNikahDini)) {
                $alasanNikahDini = $alasanNikahDini !== null
                    ? [$alasanNikahDini]
                    : [];
            }

            if (!is_array($sumberPengetahuanPms)) {
                $sumberPengetahuanPms = $sumberPengetahuanPms !== null
                    ? [$sumberPengetahuanPms]
                    : [];
            }


            /*
            |--------------------------------------------------------------------------
            | DATA YANG DISIMPAN
            |--------------------------------------------------------------------------
            */

            $save = [

                'KUNJUNGAN' => $KUNJUNGAN,


                /*
                |----------------------------------------------------------------------
                | KHUSUS PEREMPUAN
                |----------------------------------------------------------------------
                */

                'MENSTRUASI' =>
                    $menstruasi !== null
                        ? json_encode(
                            $menstruasi,
                            JSON_UNESCAPED_UNICODE
                        )
                        : null,

                'HAID_PERTAMA_USIA' =>
                    $haidPertamaUsia !== null && $haidPertamaUsia !== ''
                        ? $haidPertamaUsia
                        : null,

                'SIKLUS_HAID' =>
                    $siklusHaid !== null
                        ? json_encode(
                            $siklusHaid,
                            JSON_UNESCAPED_UNICODE
                        )
                        : null,

                'LAMA_HAID' =>
                    $lamaHaid !== null && $lamaHaid !== ''
                        ? $lamaHaid
                        : null,

                'JARAK_SIKLUS_HAID' =>
                    $jarakSiklusHaid !== null && $jarakSiklusHaid !== ''
                        ? $jarakSiklusHaid
                        : null,

                'EMOSI_HAID' =>
                    !empty($emosiHaid)
                        ? json_encode(
                            array_values($emosiHaid),
                            JSON_UNESCAPED_UNICODE
                        )
                        : null,

                'SUMBER_INFO_PUBERTAS' =>
                    $sumberInfoPubertas,

                'KELUHAN_HAID' =>
                    $keluhanHaid !== null
                        ? json_encode(
                            $keluhanHaid,
                            JSON_UNESCAPED_UNICODE
                        )
                        : null,

                'TINDAKAN_HAID' =>
                    !empty($tindakanHaid)
                        ? json_encode(
                            array_values($tindakanHaid),
                            JSON_UNESCAPED_UNICODE
                        )
                        : null,

                'TINDAKAN_HAID_LAINNYA' =>
                    $tindakanHaidLainnya,


                /*
                |----------------------------------------------------------------------
                | KHUSUS LAKI-LAKI
                |----------------------------------------------------------------------
                */

                'MIMPI_BASAH' =>
                    $mimpiBasah !== null
                        ? json_encode(
                            $mimpiBasah,
                            JSON_UNESCAPED_UNICODE
                        )
                        : null,

                'MIMPI_BASAH_PERTAMA_USIA' =>
                    $mimpiBasahPertamaUsia !== null && $mimpiBasahPertamaUsia !== ''
                        ? $mimpiBasahPertamaUsia
                        : null,


                /*
                |----------------------------------------------------------------------
                | PERNIKAHAN DINI
                |----------------------------------------------------------------------
                */

                'NIKAH_DINI' =>
                    $nikahDini !== null
                        ? json_encode(
                            $nikahDini,
                            JSON_UNESCAPED_UNICODE
                        )
                        : null,

                'ALASAN_NIKAH_DINI' =>
                    !empty($alasanNikahDini)
                        ? json_encode(
                            array_values($alasanNikahDini),
                            JSON_UNESCAPED_UNICODE
                        )
                        : null,

                'ALASAN_NIKAH_DINI_LAINNYA' =>
                    $alasanNikahDiniLainnya,


                /*
                |----------------------------------------------------------------------
                | PENYAKIT MENULAR SEKSUAL
                |----------------------------------------------------------------------
                */

                'PENGETAHUAN_PMS' =>
                    $pengetahuanPms !== null
                        ? json_encode(
                            $pengetahuanPms,
                            JSON_UNESCAPED_UNICODE
                        )
                        : null,

                'SUMBER_PENGETAHUAN_PMS' =>
                    !empty($sumberPengetahuanPms)
                        ? json_encode(
                            array_values($sumberPengetahuanPms),
                            JSON_UNESCAPED_UNICODE
                        )
                        : null,

                'PENCEGAHAN_PMS' =>
                    $pencegahanPms !== null
                        ? json_encode(
                            $pencegahanPms,
                            JSON_UNESCAPED_UNICODE
                        )
                        : null,


                /*
                |----------------------------------------------------------------------
                | AUDIT
                |----------------------------------------------------------------------
                */

                'OLEH' => auth()->id(),

                'STATUS' => 1,
            ];


            /*
            |--------------------------------------------------------------------------
            | INSERT / UPDATE
            |--------------------------------------------------------------------------
            */

            $table = 'simrspku_pengkajian.pengkajian_remaja';

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
