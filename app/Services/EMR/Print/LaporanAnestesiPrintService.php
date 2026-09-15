<?php

namespace App\Services\EMR\Print;

use Illuminate\Support\Facades\DB;

class LaporanAnestesiPrintService implements PrintDataInterface
{
    public function getData(
        string $kunjungan,
        ?string $sub = null
    ): array {

        return match ($sub) {

            'PENATA-ANESTESI' =>
                $this->getDataLaporanAnestesi($kunjungan),

            'DOKTER-ANESTESI' =>
                $this->getDataInstruksiPascaAnestesi($kunjungan),

            default => [],
        };
    }


    /**
     * ========================================================================
     * DOKTER
     * ========================================================================
     */
    private function getDataLaporanAnestesi(
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

            'KELUHAN_UTAMA' =>
                $data->KELUHAN_UTAMA ?? '',

            'RIWAYAT_PENYAKIT' =>
                $data->RIWAYAT_PENYAKIT ?? '',

            'KESADARAN' =>
                $data->KESADARAN ?? '',

            'TEKANAN_DARAH' =>
                $data->TEKANAN_DARAH ?? '',

            'NADI' =>
                $data->NADI ?? '',

            'RESPIRASI' =>
                $data->RESPIRASI ?? '',

            'SUHU' =>
                $data->SUHU ?? '',

            'DIAGNOSIS' =>
                $data->DIAGNOSIS ?? '',
        ];
    }


    /**
     * ========================================================================
     * PERAWAT
     * ========================================================================
     */
    private function getDataInstruksiPascaAnestesi(
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
