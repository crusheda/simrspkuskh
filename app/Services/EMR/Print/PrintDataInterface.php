<?php

namespace App\Services\EMR\Print;

interface PrintDataInterface
{
    public function getData(
        string $kunjungan,
        ?string $sub = null
    ): array;
}
