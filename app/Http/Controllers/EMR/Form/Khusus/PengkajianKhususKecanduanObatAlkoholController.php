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

class PengkajianKhususKecanduanObatAlkoholController extends Controller
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

        return view('pages.v2.medicalrecord.detail.form.pengkajian.khusus.kecanduanobat.index')->with('list',$data);
    }

    public function getFormKhusus(Request $request, $KUNJUNGAN)
    {
        $table = 'simrspku_pengkajian.pengkajian_kecanduan';

        $data = DB::table($table)
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
        | DECODE FIELD JSON
        |--------------------------------------------------------------------------
        */

        $data->HUBUNGAN_ORANG_TERDEKAT =
            $this->decodeJson($data->HUBUNGAN_ORANG_TERDEKAT);

        $data->JENIS_MASALAH =
            $this->decodeJson($data->JENIS_MASALAH);

        $data->PENYEBAB_BERMASALAH =
            $this->decodeJson($data->PENYEBAB_BERMASALAH);

        $data->SEJAK_KAPAN_PENGALAMAN_TIDAK_MENYENANGKAN =
            $this->decodeJson(
                $data->SEJAK_KAPAN_PENGALAMAN_TIDAK_MENYENANGKAN
            );

        $data->PERASAAN_YANG_TIMBUL =
            $this->decodeJson($data->PERASAAN_YANG_TIMBUL);

        $data->TINDAKAN_PENCEGAHAN =
            $this->decodeJson($data->TINDAKAN_PENCEGAHAN);

        $data->JENIS_ZAT_DIGUNAKAN =
            $this->decodeJson($data->JENIS_ZAT_DIGUNAKAN);

        $data->REAKSI_KECANDUAN =
            $this->decodeJson($data->REAKSI_KECANDUAN);

        $data->REAKSI_INTOKSIKASI =
            $this->decodeJson($data->REAKSI_INTOKSIKASI);

        $data->REAKSI_OVERDOSIS =
            $this->decodeJson($data->REAKSI_OVERDOSIS);

        $data->REHABILITASI_PSIKOSOSIAL =
            $this->decodeJson($data->REHABILITASI_PSIKOSOSIAL);

        $data->REHABILITASI_KEJIWAAN =
            $this->decodeJson($data->REHABILITASI_KEJIWAAN);

        /*
        |--------------------------------------------------------------------------
        | FIELD VARCHAR TIDAK PERLU DECODE
        |--------------------------------------------------------------------------
        |
        | SEJAK_KAPAN_BERMASALAH
        | KETERANGAN_ZAT
        | CARA_PENGGUNAAN_ZAT
        |
        */

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

            $hubunganOrangTerdekat = $request->input(
                'pn_hubungan_orang_terdekat'
            );

            $mulaiBermasalah = $request->input(
                'pn_mulai_bermasalah'
            );

            $jenisMasalah = $request->input(
                'pn_jenis_masalah'
            );

            $penyebabMasalah = $request->input(
                'pn_penyebab_masalah'
            );

            $pengalamanTidakMenyenangkan = $request->input(
                'pn_pengalaman_tidak_menyenangkan'
            );

            $perasaan = $request->input(
                'pn_perasaan'
            );

            $tindakanPencegahan = $request->input(
                'pn_tindakan_pencegahan'
            );

            $jenisZat = $request->input(
                'pn_jenis_zat'
            );

            $keteranganZatNarkoba = $request->input(
                'pn_zat_narkoba_keterangan'
            );

            $keteranganZatPsikotropika = $request->input(
                'pn_zat_psikotropika_keterangan'
            );

            $keteranganZatAdiktif = $request->input(
                'pn_zat_adiktif_keterangan'
            );

            $caraPenggunaanZat = $request->input(
                'pn_cara_penggunaan_zat'
            );

            $kecanduan = $request->input(
                'pn_kecanduan'
            );

            $intoksikasi = $request->input(
                'pn_intoksikasi'
            );

            $overdosis = $request->input(
                'pn_overdosis'
            );

            $rehabilitasiPsikososial = $request->input(
                'pn_rehabilitasi_psikososial'
            );

            $rehabilitasiKejiwaan = $request->input(
                'pn_rehabilitasi_kejiwaan'
            );


            /*
            |--------------------------------------------------------------------------
            | DATA YANG DISIMPAN
            |--------------------------------------------------------------------------
            */

            $save = [

                'KUNJUNGAN' => $KUNJUNGAN,

                /*
                |--------------------------------------------------------------------------
                | JSON FIELD
                |--------------------------------------------------------------------------
                */

                'HUBUNGAN_ORANG_TERDEKAT' =>
                    $this->encodeJson($hubunganOrangTerdekat),

                'JENIS_MASALAH' =>
                    $this->encodeJson($jenisMasalah),

                'PENYEBAB_BERMASALAH' =>
                    $this->encodeJson($penyebabMasalah),

                'SEJAK_KAPAN_PENGALAMAN_TIDAK_MENYENANGKAN' =>
                    $this->encodeJson(
                        $pengalamanTidakMenyenangkan
                    ),

                'PERASAAN_YANG_TIMBUL' =>
                    $this->encodeJson($perasaan),

                'TINDAKAN_PENCEGAHAN' =>
                    $this->encodeJson($tindakanPencegahan),

                'JENIS_ZAT_DIGUNAKAN' =>
                    $this->encodeJson($jenisZat),

                'REAKSI_KECANDUAN' =>
                    $this->encodeJson($kecanduan),

                'REAKSI_INTOKSIKASI' =>
                    $this->encodeJson($intoksikasi),

                'REAKSI_OVERDOSIS' =>
                    $this->encodeJson($overdosis),

                'REHABILITASI_PSIKOSOSIAL' =>
                    $this->encodeJson(
                        $rehabilitasiPsikososial
                    ),

                'REHABILITASI_KEJIWAAN' =>
                    $this->encodeJson(
                        $rehabilitasiKejiwaan
                    ),


                /*
                |--------------------------------------------------------------------------
                | VARCHAR FIELD
                |--------------------------------------------------------------------------
                */

                'SEJAK_KAPAN_BERMASALAH' =>
                    $mulaiBermasalah,

                'KETERANGAN_NARKOBA' =>
                    $keteranganZatNarkoba,

                'KETERANGAN_PSIKOTROPIKA' =>
                    $keteranganZatPsikotropika,

                'KETERANGAN_ZAT_ADIKTIF' =>
                    $keteranganZatAdiktif,

                'CARA_PENGGUNAAN_ZAT' =>
                    $caraPenggunaanZat,


                /*
                |--------------------------------------------------------------------------
                | SYSTEM FIELD
                |--------------------------------------------------------------------------
                */

                'OLEH' => auth()->id(),

                'STATUS' => 1,
            ];


            /*
            |--------------------------------------------------------------------------
            | INSERT / UPDATE
            |--------------------------------------------------------------------------
            */

            $table = 'simrspku_pengkajian.pengkajian_kecanduan';

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


    /*
    |--------------------------------------------------------------------------
    | ENCODE JSON
    |--------------------------------------------------------------------------
    */

    private function encodeJson($value)
    {
        /*
        | Jika tidak ada data
        */
        if ($value === null || $value === '') {
            return null;
        }

        /*
        | Kalau sudah berupa array/object,
        | langsung encode.
        */
        if (is_array($value) || is_object($value)) {

            return json_encode(
                $value,
                JSON_UNESCAPED_UNICODE
            );
        }

        /*
        | Kalau string biasa, simpan sebagai
        | JSON string.
        |
        | Contoh:
        | "Ya" -> "Ya"
        |
        | Jika field form memang menghasilkan
        | string dan kolom database JSON,
        | ini tetap valid JSON.
        */
        return json_encode(
            $value,
            JSON_UNESCAPED_UNICODE
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DECODE JSON
    |--------------------------------------------------------------------------
    */

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
