<?php

namespace App\Support;

use InvalidArgumentException;

final class FinalisasiMap
{
    private const FORM_SUB = [
        'rid_dokter' => [
            'form' => 'pengkajian-ranap-dewasa',
            'sub' => 'DOKTER',
        ],
        'rid_perawat' => [
            'form' => 'pengkajian-ranap-dewasa',
            'sub' => 'PERAWAT',
        ],

        'ria_dokter' => [
            'form' => 'pengkajian-ranap-anak',
            'sub' => 'DOKTER',
        ],
        'ria_perawat' => [
            'form' => 'pengkajian-ranap-anak',
            'sub' => 'PERAWAT',
        ],

        'rin_dokter' => [
            'form' => 'pengkajian-ranap-neonatus',
            'sub' => 'DOKTER',
        ],
        'rin_perawat' => [
            'form' => 'pengkajian-ranap-neonatus',
            'sub' => 'PERAWAT',
        ],

        'rio_dokter' => [
            'form' => 'pengkajian-ranap-obsgyn',
            'sub' => 'DOKTER',
        ],
        'rio_perawat' => [
            'form' => 'pengkajian-ranap-obsgyn',
            'sub' => 'PERAWAT',
        ],

        // Tambahkan mapping rajal, IGD, atau form lain di sini nanti.
    ];

    public static function get(string $formKey): array
    {
        if (!isset(self::FORM_SUB[$formKey])) {
            throw new InvalidArgumentException(
                "Form key tidak dikenal: {$formKey}"
            );
        }

        return self::FORM_SUB[$formKey];
    }
}
