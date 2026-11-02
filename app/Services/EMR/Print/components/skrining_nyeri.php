<?php

namespace app\Services\EMR\Print\components;

use Illuminate\Support\Facades\DB;

class skrining_nyeri
{
    /**
     * ========================================================================
     * SKRINING NYERI
     * Query database dan format hasil skrining nyeri.
     * ========================================================================
     */
    public static function getData(string $kunjungan): string
    {
        /*
        |--------------------------------------------------------------------------
        | QUERY DATA SKRINING NYERI
        |--------------------------------------------------------------------------
        */

        $nyeri = DB::table('medicalrecord.penilaian_nyeri')
            ->where('KUNJUNGAN', $kunjungan)
            ->whereIn('STATUS', [1, 2])
            ->orderByDesc('ID')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | JIKA DATA TIDAK ADA
        |--------------------------------------------------------------------------
        */

        if (!$nyeri) {
            return 'Tidak Ada';
        }

        /*
        |--------------------------------------------------------------------------
        | HELPER VALUE
        |--------------------------------------------------------------------------
        */

        $value = static function ($raw): string {
            if ($raw === null) {
                return '';
            }

            if (is_bool($raw)) {
                return $raw ? '1' : '0';
            }

            return trim((string) $raw);
        };

        /*
        |--------------------------------------------------------------------------
        | HELPER PUSH
        |--------------------------------------------------------------------------
        */

        $push = static function (
            array &$lines,
            string $label,
            $raw,
            array $map = []
        ) use ($value): void {
            $key = $value($raw);

            if ($key === '') {
                return;
            }

            $mapped = array_key_exists($key, $map)
                ? $map[$key]
                : (
                    ($key === '0' && $map !== [])
                        ? 'Belum diisi'
                        : $key
                );

            $lines[] = $label . ': ' . $mapped;
        };

        /*
        |--------------------------------------------------------------------------
        | FORMAT SKRINING NYERI
        |--------------------------------------------------------------------------
        */

        $nyeriText = [];

        // Nyeri
        $push(
            $nyeriText,
            'Nyeri',
            $nyeri->NYERI ?? null,
            [
                '0' => 'Tidak',
                '1' => 'Ya',
            ]
        );

        // Onset
        $push(
            $nyeriText,
            'Onset',
            $nyeri->ONSET ?? null,
            [
                '1' => 'Akut',
                '2' => 'Kronis',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | SKALA NYERI
        |--------------------------------------------------------------------------
        */

        $skalaNyeri = $value($nyeri->SKALA ?? null);

        if ($skalaNyeri !== '') {
            $nyeriText[] = 'Skala nyeri: ' . $skalaNyeri;

            $metodeRaw = $value($nyeri->METODE ?? null);

            if (in_array($metodeRaw, ['1', '5'], true)) {
                $skor = (int) $skalaNyeri;

                $interpretasi = $skor === 0
                    ? 'Tidak nyeri'
                    : (
                        $skor <= 3
                            ? 'Nyeri ringan'
                            : (
                                $skor <= 6
                                    ? 'Nyeri sedang'
                                    : 'Nyeri berat'
                            )
                    );

                $nyeriText[] = 'Interpretasi skala: ' . $interpretasi;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | METODE PENILAIAN NYERI
        |--------------------------------------------------------------------------
        */

        $metodeNyeriMap = [
            '1' => 'NRS',
            '2' => 'BPS',
            '3' => 'NIPS',
            '4' => 'FLACC',
            '5' => 'VAS',
        ];

        $metodeNyeriRaw = $value($nyeri->METODE ?? null);

        $push(
            $nyeriText,
            'Metode penilaian',
            $metodeNyeriRaw,
            $metodeNyeriMap
        );

        /*
        |--------------------------------------------------------------------------
        | RINCIAN SKOR BPS / NIPS / FLACC
        |--------------------------------------------------------------------------
        */

        if (in_array($metodeNyeriRaw, ['2', '3', '4'], true)) {
            $maks = match ($metodeNyeriRaw) {
                '2' => 3,
                '3' => 6,
                '4' => 5,
                default => 0,
            };

            $komponen = [];

            for ($i = 1; $i <= $maks; $i++) {
                $score = $value($nyeri->{'SKOR' . $i} ?? null);

                if ($score !== '') {
                    $komponen[] = 'Komponen ' . $i . ': ' . $score;
                }
            }

            if ($komponen) {
                $nyeriText[] = 'Rincian skor '
                    . ($metodeNyeriMap[$metodeNyeriRaw] ?? '')
                    . ': '
                    . implode('; ', $komponen);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | KETERANGAN TAMBAHAN
        |--------------------------------------------------------------------------
        */

        foreach ([
            'PENCETUS' => 'Pencetus',
            'GAMBARAN' => 'Gambaran nyeri',
            'DURASI'   => 'Durasi',
            'LOKASI'   => 'Lokasi',
        ] as $column => $label) {
            $push(
                $nyeriText,
                $label,
                $nyeri->{$column} ?? null
            );
        }

        /*
        |--------------------------------------------------------------------------
        | RETURN HASIL
        |--------------------------------------------------------------------------
        */

        $nyeriText = array_values(
            array_filter(
                $nyeriText,
                static fn ($line) =>
                    $line !== null && trim((string) $line) !== ''
            )
        );

        return $nyeriText
            ? implode("\n", $nyeriText)
            : 'Tidak Ada';
    }
}
