<?php

namespace App\Services\EMR\Print;

use Illuminate\Support\Facades\DB;

class KHTerminalPrintService implements PrintDataInterface
{
    public function getData(
        string $kunjungan,
        ?string $sub = null
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
