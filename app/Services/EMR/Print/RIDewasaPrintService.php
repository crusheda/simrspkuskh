<?php

namespace App\Services\EMR\Print;

use Illuminate\Support\Facades\DB;

class RIDewasaPrintService implements PrintDataInterface
{
    public function getData(
        string $kunjungan,
        ?string $sub = null
    ): array {

        return match ($sub) {

            'DOKTER' =>
                $this->getDataDokter($kunjungan),

            'PERAWAT' =>
                $this->getDataPerawat($kunjungan),

            default => [],
        };
    }


    /**
     * ========================================================================
     * DOKTER
     * ========================================================================
     */
    private function getDataDokter(
        string $kunjungan
    ): array {

        // $data = DB::table(
        //     'simrspku_pengkajian.pengkajian_ranap_dewasa'
        // )
        //     ->where('KUNJUNGAN', $kunjungan)
        //     ->first();

        // if (!$data) {
        //     return [];
        // }

        return [
            /*
            |--------------------------------------------------------------------------
            | DATA DUMMY
            |--------------------------------------------------------------------------
            */
            'KUNJUNGAN' =>
                $kunjungan,

            'NORM' =>
                '00000001',

            'NAMALENGKAP' =>
                'NAMA PASIEN DUMMY',

            'JK' =>
                'Laki-laki',

            'TGLLAHIR' =>
                '01 Januari 1990',

            'UMUR' =>
                '36 Tahun',

            'ALAMAT' =>
                'Alamat Pasien Dummy',

            /*
            |--------------------------------------------------------------------------
            | CETAK
            |--------------------------------------------------------------------------
            */

            'TANGGAL_CETAK' =>
            now()->translatedFormat(
                'DD MMMM YYYY [Pukul] HH:mm:ss'
            ),
        ];
    }


    /**
     * ========================================================================
     * PERAWAT
     * ========================================================================
     */
    private function getDataPerawat(
        string $kunjungan
    ): array {

        $data = DB::table(
            'simrspku_pengkajian.pengkajian_ranap_dewasa'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        if (!$data) {
            return [];
        }

        return [

            'STATUS_FUNGSIONAL' =>
                $data->STATUS_FUNGSIONAL ?? '',

            'RISIKO_JATUH' =>
                $data->RISIKO_JATUH ?? '',

            'SKRINING_NYERI' =>
                $data->SKRINING_NYERI ?? '',

            'KEBUTUHAN_KEPERAWATAN' =>
                $data->KEBUTUHAN_KEPERAWATAN ?? '',
        ];
    }
}
