<?php

namespace App\Services\EMR\Print;

use Illuminate\Support\Facades\DB;

class GDPrintService implements PrintDataInterface
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

        $data = DB::table(
            'simrspku_pengkajian.??'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        if (!$data) {
            return [];
        }

        return [

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
            'simrspku_pengkajian.??'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        if (!$data) {
            return [];
        }

        return [

        ];
    }
}
