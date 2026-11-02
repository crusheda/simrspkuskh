<?php

namespace App\Services\EMR\Print\components;

use Illuminate\Support\Facades\DB;

class skrining_nyeri
{
    /**
     * ========================================================================
     * SKRINING NYERI
     * Query database dan format hasil skrining nyeri.
     * ========================================================================
     */
    public function getData(string $kunjungan): string
    {
        $nyeri = DB::table('medicalrecord.penilaian_nyeri')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first([
                'NYERI',
                'ONSET',
                'SKALA',
                'METODE',
                'SKOR1',
                'SKOR2',
                'SKOR3',
                'SKOR4',
                'SKOR5',
                'SKOR6',
                'PENCETUS',
                'GAMBARAN',
                'DURASI',
                'LOKASI',
            ]);

        $centangNyeri = static function ($nilai, $pilihan): string {
            return (string) ($nilai ?? '') === (string) $pilihan
                ? '☑'
                : '☐';
        };

        $detailNyeri = [];

        if (!$nyeri) {

            $detailNyeri[] = 'Nyeri: Tidak';

        } else {

            $nyeriYa = (string) ($nyeri->NYERI ?? '') === '1';

            $detailNyeri[] = $nyeriYa ? 'Nyeri: Ya' : 'Nyeri: Tidak';

            if ($nyeriYa) {
                /*
                |--------------------------------------------------------------------------
                | ONSET
                |--------------------------------------------------------------------------
                */
                $detailNyeri[] = 'Onset: '
                    . $centangNyeri($nyeri->ONSET, 1) . ' Akut'
                    . '     '
                    . $centangNyeri($nyeri->ONSET, 2) . ' Kronis';

                /*
                |--------------------------------------------------------------------------
                | METODE PENILAIAN
                |--------------------------------------------------------------------------
                */
                $metodeNyeri = [
                    1 => 'NRS',
                    2 => 'BPS',
                    3 => 'NIPS',
                    4 => 'FLACC',
                    5 => 'VAS',
                ];

                $barisMetode = [];

                foreach ($metodeNyeri as $nilai => $label) {
                    $barisMetode[] =
                        $centangNyeri($nyeri->METODE, $nilai)
                        . ' ' . $label;
                }

                $detailNyeri[] = 'Metode: '
                    . implode('     ', $barisMetode);

                /*
                |--------------------------------------------------------------------------
                | SKALA NYERI
                |--------------------------------------------------------------------------
                | NRS dan VAS cukup menampilkan skala langsung.
                | BPS, NIPS dan FLACC menampilkan rincian skor indikator.
                |--------------------------------------------------------------------------
                */
                $metode = (string) ($nyeri->METODE ?? '');
                $skala = $this->value($nyeri->SKALA ?? null);

                if (in_array($metode, ['1', '5'], true)) {

                    // NRS / VAS: tanpa slider dan tanpa rincian indikator
                    $detailNyeri[] = 'Skala Nyeri: '
                        . ($skala !== '' ? $skala : '-');

                    $interpretasiNyeri = '';

                    if ($skala !== '' && is_numeric($skala)) {
                        $nilaiSkala = (float) $skala;

                        if ($nilaiSkala == 0) {
                            $interpretasiNyeri = 'Tidak nyeri';
                        } elseif ($nilaiSkala <= 3) {
                            $interpretasiNyeri = 'Nyeri ringan';
                        } elseif ($nilaiSkala <= 6) {
                            $interpretasiNyeri = 'Nyeri sedang';
                        } else {
                            $interpretasiNyeri = 'Nyeri berat';
                        }
                    }

                    if ($interpretasiNyeri !== '') {
                        $detailNyeri[] =
                            'Interpretasi: ' . $interpretasiNyeri;
                    }

                } elseif ($metode === '2') {

                    // BPS
                    $detailNyeri[] = 'Rincian Skor BPS:';

                    foreach ([
                        'SKOR1' => 'Indikator 1',
                        'SKOR2' => 'Indikator 2',
                        'SKOR3' => 'Indikator 3',
                    ] as $kolom => $label) {
                        $detailNyeri[] = '   '
                            . $label . ': '
                            . $this->value($nyeri->{$kolom} ?? null);
                    }

                    $detailNyeri[] = 'Skala Nyeri Total: '
                        . ($skala !== '' ? $skala : '-');

                    if ($skala !== '' && is_numeric($skala)) {
                        $nilaiSkala = (float) $skala;

                        $interpretasiNyeri = match (true) {
                            $nilaiSkala <= 3 => 'Tidak nyeri',
                            $nilaiSkala <= 6 => 'Nyeri ringan',
                            $nilaiSkala <= 9 => 'Nyeri sedang',
                            default => 'Nyeri berat',
                        };

                        $detailNyeri[] =
                            'Interpretasi: ' . $interpretasiNyeri;
                    }

                } elseif ($metode === '3') {

                    // NIPS
                    $detailNyeri[] = 'Rincian Skor NIPS:';

                    foreach ([
                        'SKOR1' => 'Ekspresi wajah',
                        'SKOR2' => 'Tangisan',
                        'SKOR3' => 'Gerakan lengan',
                        'SKOR4' => 'Gerakan tungkai',
                        'SKOR5' => 'Status terjaga',
                        'SKOR6' => 'Pola napas',
                    ] as $kolom => $label) {
                        $detailNyeri[] = '   '
                            . $label . ': '
                            . $this->value($nyeri->{$kolom} ?? null);
                    }

                    $detailNyeri[] = 'Skala Nyeri Total: '
                        . ($skala !== '' ? $skala : '-');

                    if ($skala !== '' && is_numeric($skala)) {
                        $detailNyeri[] =
                            'Interpretasi: '
                            . ((float) $skala > 3
                                ? 'Nyeri'
                                : 'Tidak nyeri');
                    }

                } elseif ($metode === '4') {

                    // FLACC
                    $detailNyeri[] = 'Rincian Skor FLACC:';

                    foreach ([
                        'SKOR1' => 'Wajah',
                        'SKOR2' => 'Kaki',
                        'SKOR3' => 'Aktivitas',
                        'SKOR4' => 'Menangis',
                        'SKOR5' => 'Bersuara',
                    ] as $kolom => $label) {
                        $detailNyeri[] = '   '
                            . $label . ': '
                            . $this->value($nyeri->{$kolom} ?? null);
                    }

                    $detailNyeri[] = 'Skala Nyeri Total: '
                        . ($skala !== '' ? $skala : '-');
                }

                /*
                |--------------------------------------------------------------------------
                | DETAIL KELUHAN NYERI
                |--------------------------------------------------------------------------
                */
                foreach ([
                    'PENCETUS' => 'Pencetus',
                    'GAMBARAN' => 'Gambaran',
                    'DURASI'   => 'Durasi',
                    'LOKASI'   => 'Lokasi',
                ] as $kolom => $label) {

                    $nilai = $this->value($nyeri->{$kolom} ?? null);

                    $detailNyeri[] = $label . ': '
                        . ($nilai !== '' ? $nilai : '-');
                }
            }
        }

        return $detailNyeri
            ? implode("\n", $detailNyeri)
            : 'Tidak Ada';
    }

    private function value($value): string
    {
        if ($value === null) {
            return '';
        }

        if (is_bool($value)) {
            return $value ? 'Ya' : 'Tidak';
        }

        return trim((string) $value);
    }
}
