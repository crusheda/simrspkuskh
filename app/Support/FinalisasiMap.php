<?php

namespace App\Support;

use InvalidArgumentException;

final class FinalisasiMap
{
    private const FORM_SUB = [
        // Pengkajian Awal
            // Rawat Darurat
            'gdD_dokter' => [
                'form' => 'pengkajian-ranap-dewasa',
                'sub' => 'DOKTER',
            ],

            // Rawat Inap
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

            // Anestesi
            'lap_anestesi' => ['form' => 'laporan-anestesi', 'sub' => 'PENATA-ANESTESI'],
            'lap_pasca_anestesi' => ['form' => 'instruksi-pasca-anestesi', 'sub' => 'DOKTER-ANESTESI'],

            // Bedah
            'prabedah' => [
                'form' => 'pengkajian-prabedah',
                'sub' => 'BEDAH',
            ],
            'praanestesiinduksi' => [
                'form' => 'pengkajian-praanestesiinduksi',
                'sub' => 'ANESTESI',
            ],

        // Pengkajian Khusus
        'kh_remaja' => ['form' => 'pengkajian-khusus-remaja', 'sub' => 'KHUSUS'],
        'kh_terminal' => ['form' => 'pengkajian-khusus-terminal', 'sub' => 'KHUSUS'],
        'kh_nyerikronik' => ['form' => 'pengkajian-khusus-nyerikronik', 'sub' => 'KHUSUS'],
        'kh_sistemimun' => ['form' => 'pengkajian-khusus-sistemimunterganggu', 'sub' => 'KHUSUS'],
        'kh_kecanduanobat' => ['form' => 'pengkajian-khusus-kecanduanobatalkohol', 'sub' => 'KHUSUS'],
        'kh_korbankekerasan' => ['form' => 'pengkajian-khusus-korbankekerasan', 'sub' => 'KHUSUS'],
        'kh_penyakitmenular' => ['form' => 'pengkajian-khusus-penyakitmenular', 'sub' => 'KHUSUS'],
        'kh_lanjutan' => ['form' => 'pengkajian-khusus-lanjutan', 'sub' => 'KHUSUS'],

        // Form Lain
        'ln_transfer' => [
            'form' => 'transfer-pasien',
            'sub' => 'PERAWAT',
        ],
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
