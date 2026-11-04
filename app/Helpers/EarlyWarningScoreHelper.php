<?php

namespace App\Helpers;

use Carbon\Carbon;
use Throwable;

class EarlyWarningScoreHelper
{
    /*
    |--------------------------------------------------------------------------
    | BATAS UMUR DEWASA
    |--------------------------------------------------------------------------
    |
    | >= 16 tahun : EWSS
    | <  16 tahun : PEWSS
    |
    | UMUR disimpan dalam satuan bulan.
    |
    */

    private const BATAS_UMUR_DEWASA = 192;


    /*
    |--------------------------------------------------------------------------
    | FUNGSI UTAMA
    |--------------------------------------------------------------------------
    |
    | Input:
    | - Parameter tanda vital
    | - Tanggal lahir pasien
    | - Waktu pemeriksaan
    |
    | Output:
    | - UMUR
    | - EWSS
    | - PEWSS
    |
    */

    public static function hitung(
        array $vital,
        ?string $tanggalLahir,
        $waktuPemeriksaan = null
    ): array {

        // Hitung umur pasien dalam bulan.
        $umurBulan = self::hitungUmurBulan(
            $tanggalLahir,
            $waktuPemeriksaan
        );


        // Jika tanggal lahir tidak tersedia,
        // jangan mengasumsikan kelompok umur pasien.
        if ($umurBulan === null) {

            return [
                'UMUR' => null,
                'EWSS' => null,
                'PEWSS' => null,
                'JENIS' => null,
                'LENGKAP' => false,
                'DETAIL' => [],
            ];

        }


        // ==========================================================
        // PASIEN DEWASA
        // ==========================================================

        if ($umurBulan >= self::BATAS_UMUR_DEWASA) {

            $hasil = self::hitungEWSS($vital);

            return [
                'UMUR' => $umurBulan,
                'EWSS' => $hasil['TOTAL'],
                'PEWSS' => null,
                'JENIS' => 'EWSS',
                'LENGKAP' => $hasil['LENGKAP'],
                'DETAIL' => $hasil['DETAIL'],
            ];

        }


        // ==========================================================
        // PASIEN ANAK
        // ==========================================================

        $hasil = self::hitungPEWSS($vital);

        return [
            'UMUR' => $umurBulan,
            'EWSS' => null,
            'PEWSS' => $hasil['TOTAL'],
            'JENIS' => 'PEWSS',
            'LENGKAP' => $hasil['LENGKAP'],
            'DETAIL' => $hasil['DETAIL'],
        ];

    }


    /*
    |--------------------------------------------------------------------------
    | HITUNG UMUR DALAM BULAN
    |--------------------------------------------------------------------------
    */

    private static function hitungUmurBulan(
        ?string $tanggalLahir,
        $waktuPemeriksaan = null
    ): ?int {

        if (empty($tanggalLahir)) {
            return null;
        }

        try {

            $lahir = Carbon::parse($tanggalLahir)->startOfDay();

            $periksa = Carbon::parse(
                $waktuPemeriksaan ?? now()
            )->startOfDay();

            // Tanggal lahir tidak boleh melebihi tanggal pemeriksaan.
            if ($lahir->greaterThan($periksa)) {
                return null;
            }

            return max(
                0,
                (int) $lahir->diffInMonths($periksa)
            );

        } catch (Throwable $e) {

            report($e);

            return null;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | NORMALISASI NILAI ANGKA
    |--------------------------------------------------------------------------
    */

    private static function angka(
        array $data,
        string $key
    ): ?float {

        $value = $data[$key] ?? null;

        if (
            $value === null ||
            $value === '' ||
            !is_numeric($value)
        ) {
            return null;
        }

        $number = (float) $value;

        return is_finite($number)
            ? $number
            : null;

    }


    /*
    |--------------------------------------------------------------------------
    | EWSS
    |--------------------------------------------------------------------------
    |
    | Menggunakan NEWS2 Scale 1.
    |
    | Parameter:
    | 1. Frekuensi napas
    | 2. Saturasi O2
    | 3. Penggunaan alat bantu napas/O2
    | 4. Tekanan darah sistolik
    | 5. Frekuensi nadi
    | 6. Suhu
    | 7. GCS sebagai pendekatan kesadaran
    |
    | GCS bukan pengganti langsung ACVPU atau new confusion.
    |
    */

    private static function hitungEWSS(array $vital): array
    {

        $detail = [];


        // ==========================================================
        // 1. FREKUENSI NAPAS
        // ==========================================================

        $rr = self::angka(
            $vital,
            'FREKUENSI_NAFAS'
        );

        if ($rr !== null) {

            $detail['RESPIRASI'] = match (true) {

                $rr <= 8 => 3,

                $rr <= 11 => 1,

                $rr <= 20 => 0,

                $rr <= 24 => 2,

                default => 3,

            };

        }


        // ==========================================================
        // 2. SATURASI O2
        // ==========================================================

        $spo2 = self::angka(
            $vital,
            'SATURASI_O2'
        );

        if ($spo2 !== null) {

            $detail['SATURASI_O2'] = match (true) {

                $spo2 <= 91 => 3,

                $spo2 <= 93 => 2,

                $spo2 <= 95 => 1,

                default => 0,

            };

        }


        // ==========================================================
        // 3. PENGGUNAAN ALAT BANTU NAPAS
        // ==========================================================

        $abn = self::angka(
            $vital,
            'ALAT_BANTU_NAFAS'
        );

        if ($abn !== null) {

            // 0 = Tidak
            // 2 = Ya

            $detail['OKSIGEN'] = $abn > 0
                ? 2
                : 0;

        }


        // ==========================================================
        // 4. TEKANAN DARAH SISTOLIK
        // ==========================================================

        $sistolik = self::angka(
            $vital,
            'SISTOLIK'
        );

        if ($sistolik !== null) {

            $detail['TEKANAN_DARAH'] = match (true) {

                $sistolik <= 90 => 3,

                $sistolik <= 100 => 2,

                $sistolik <= 110 => 1,

                $sistolik <= 219 => 0,

                default => 3,

            };

        }


        // ==========================================================
        // 5. FREKUENSI NADI
        // ==========================================================

        $nadi = self::angka(
            $vital,
            'FREKUENSI_NADI'
        );

        if ($nadi !== null) {

            $detail['NADI'] = match (true) {

                $nadi <= 40 => 3,

                $nadi <= 50 => 1,

                $nadi <= 90 => 0,

                $nadi <= 110 => 1,

                $nadi <= 130 => 2,

                default => 3,

            };

        }


        // ==========================================================
        // 6. SUHU
        // ==========================================================

        $suhu = self::angka(
            $vital,
            'SUHU'
        );

        if ($suhu !== null) {

            $detail['SUHU'] = match (true) {

                $suhu <= 35 => 3,

                $suhu <= 36 => 1,

                $suhu <= 38 => 0,

                $suhu <= 39 => 1,

                default => 2,

            };

        }


        // ==========================================================
        // 7. GCS / KESADARAN
        // ==========================================================

        $gcs = self::angka(
            $vital,
            'GCS'
        );

        // GCS valid antara 3 sampai 15.
        // GCS 0 dianggap belum dinilai.

        if (
            $gcs !== null &&
            $gcs >= 3 &&
            $gcs <= 15
        ) {

            $detail['KESADARAN'] = $gcs < 15
                ? 3
                : 0;

        }


        // ==========================================================
        // TOTAL EWSS
        // ==========================================================

        // Harus tersedia seluruh 7 parameter.
        // Jangan menyimpan skor parsial sebagai skor final.

        $lengkap = count($detail) === 7;

        return [

            'TOTAL' => $lengkap
                ? array_sum($detail)
                : null,

            'LENGKAP' => $lengkap,

            'DETAIL' => $detail,

        ];

    }


    /*
    |--------------------------------------------------------------------------
    | PEWSS
    |--------------------------------------------------------------------------
    |
    | Adaptasi sederhana berdasarkan parameter yang tersedia.
    |
    | Parameter:
    | 1. Frekuensi napas
    | 2. Saturasi O2
    | 3. Penggunaan alat bantu napas
    | 4. Tekanan darah sistolik
    | 5. Frekuensi nadi
    | 6. Suhu
    | 7. GCS sebagai pendekatan kesadaran
    |
    | Belum memasukkan:
    | - CRT
    | - Retraksi / usaha napas
    | - Volume oksigen L/menit
    | - Penilaian perilaku
    |
    */

    private static function hitungPEWSS(array $vital): array
    {

        $detail = [];


        // ==========================================================
        // 1. FREKUENSI NAPAS
        // ==========================================================

        $rr = self::angka(
            $vital,
            'FREKUENSI_NAFAS'
        );

        if ($rr !== null) {

            $detail['RESPIRASI'] = match (true) {

                $rr <= 10 => 3,

                $rr <= 15 => 2,

                $rr <= 29 => 0,

                $rr <= 39 => 1,

                $rr <= 49 => 2,

                default => 3,

            };

        }


        // ==========================================================
        // 2. SATURASI O2
        // ==========================================================

        $spo2 = self::angka(
            $vital,
            'SATURASI_O2'
        );

        if ($spo2 !== null) {

            $detail['SATURASI_O2'] = match (true) {

                $spo2 <= 85 => 3,

                $spo2 <= 89 => 2,

                $spo2 <= 93 => 1,

                default => 0,

            };

        }


        // ==========================================================
        // 3. ALAT BANTU NAPAS
        // ==========================================================

        $abn = self::angka(
            $vital,
            'ALAT_BANTU_NAFAS'
        );

        if ($abn !== null) {

            /*
             * 0 = Tidak menggunakan alat bantu.
             * 2 = Menggunakan alat bantu.
             *
             * Karena form hanya menyediakan Ya/Tidak,
             * belum dapat membedakan kebutuhan O2 <=2 L
             * dan >2 L.
             *
             * Skor 2 digunakan sebagai asumsi konservatif.
             */

            $detail['OKSIGEN'] = $abn > 0
                ? 2
                : 0;

        }


        // ==========================================================
        // 4. TEKANAN DARAH SISTOLIK
        // ==========================================================

        $sistolik = self::angka(
            $vital,
            'SISTOLIK'
        );

        if ($sistolik !== null) {

            $detail['TEKANAN_DARAH'] = match (true) {

                $sistolik <= 80 => 3,

                $sistolik <= 89 => 2,

                $sistolik <= 119 => 0,

                $sistolik <= 129 => 1,

                $sistolik <= 139 => 2,

                default => 3,

            };

        }


        // ==========================================================
        // 5. FREKUENSI NADI
        // ==========================================================

        $nadi = self::angka(
            $vital,
            'FREKUENSI_NADI'
        );

        if ($nadi !== null) {

            $detail['NADI'] = match (true) {

                $nadi <= 50 => 3,

                $nadi <= 69 => 2,

                $nadi <= 110 => 0,

                $nadi <= 129 => 1,

                $nadi <= 149 => 2,

                default => 3,

            };

        }


        // ==========================================================
        // 6. SUHU
        // ==========================================================

        $suhu = self::angka(
            $vital,
            'SUHU'
        );

        if ($suhu !== null) {

            $detail['SUHU'] = match (true) {

                $suhu <= 35 => 3,

                $suhu < 36 => 1,

                $suhu <= 37 => 0,

                $suhu < 38.5 => 1,

                default => 3,

            };

        }


        // ==========================================================
        // 7. GCS / KESADARAN
        // ==========================================================

        $gcs = self::angka(
            $vital,
            'GCS'
        );

        if (
            $gcs !== null &&
            $gcs >= 3 &&
            $gcs <= 15
        ) {

            $detail['KESADARAN'] = match (true) {

                $gcs >= 15 => 0,

                $gcs >= 13 => 1,

                $gcs >= 9 => 2,

                default => 3,

            };

        }


        // ==========================================================
        // TOTAL PEWSS
        // ==========================================================

        $lengkap = count($detail) === 7;

        return [

            'TOTAL' => $lengkap
                ? array_sum($detail)
                : null,

            'LENGKAP' => $lengkap,

            'DETAIL' => $detail,

        ];

    }

}
