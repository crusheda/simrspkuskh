<?php

namespace App\Support;

use InvalidArgumentException;

// Gawat Darurat
use App\Services\EMR\Print\GDPrintService;

// Rawat Jalan
use App\Services\EMR\Print\RJDewasaPrintService;
use App\Services\EMR\Print\RJAnakPrintService;
use App\Services\EMR\Print\RJJiwaPrintService;
use App\Services\EMR\Print\RJGeriatriPrintService;
use App\Services\EMR\Print\RJObsgynPrintService;

// Rawat Inap
use App\Services\EMR\Print\RIDewasaPrintService;
use App\Services\EMR\Print\RIAnakPrintService;
use App\Services\EMR\Print\RINeonatusPrintService;
use App\Services\EMR\Print\RIObsgynPrintService;

// Bedah & Anestesi
use App\Services\EMR\Print\PraBedahPrintService;
use App\Services\EMR\Print\PraAnestesiInduksiPrintService;
use App\Services\EMR\Print\LaporanAnestesiPrintService;

// Khusus
use App\Services\EMR\Print\KHRemajaPrintService;
use App\Services\EMR\Print\KHTerminalPrintService;
use App\Services\EMR\Print\KHNyeriKronikPrintService;
use App\Services\EMR\Print\KHSistemImunPrintService;
use App\Services\EMR\Print\KHKecanduanObatPrintService;
use App\Services\EMR\Print\KHKorbanKekerasanPrintService;
use App\Services\EMR\Print\KHPenyakitMenularPrintService;
use App\Services\EMR\Print\KHLanjutanPrintService;

// Lain
use App\Services\EMR\Print\LembarTransferPasienPrintService;

final class FinalisasiMap
{
    private const FORM_SUB = [

        // =====================================================================
        // PENGKAJIAN AWAL
        // =====================================================================

        // ---------------------------------------------------------------------
        // Rawat Darurat
        // ---------------------------------------------------------------------

        'gdd_dokter' => [
            'form' => 'pengkajian-radar',
            'sub' => 'DOKTER',
            'template' => 'print_radar_dokter.docx',
            'title' => 'Pengkajian Awal Rawat Darurat - Dokter',
            'service' => GDPrintService::class,
        ],

        'gdp_dokter' => [
            'form' => 'pengkajian-radar',
            'sub' => 'PERAWAT',
            'template' => 'print_radar_perawat.docx',
            'title' => 'Pengkajian Awal Rawat Darurat - Perawat',
            'service' => GDPrintService::class,
        ],

        // ---------------------------------------------------------------------
        // Rawat Jalan
        // ---------------------------------------------------------------------

        'rjd_dokter' => [
            'form' => 'pengkajian-rajal-dewasa',
            'sub' => 'DOKTER',
            'template' => 'print_rajal_dewasa_dokter.docx',
            'title' => 'Pengkajian Awal Rawat Jalan Dewasa - Dokter',
            'service' => RJDewasaPrintService::class,
        ],

        'rjd_perawat' => [
            'form' => 'pengkajian-rajal-dewasa',
            'sub' => 'PERAWAT',
            'template' => 'print_rajal_dewasa_perawat.docx',
            'title' => 'Pengkajian Awal Rawat Jalan Dewasa - Perawat',
            'service' => RJDewasaPrintService::class,
        ],

        'rja_dokter' => [
            'form' => 'pengkajian-rajal-anak',
            'sub' => 'DOKTER',
            'template' => 'print_rajal_anak_dokter.docx',
            'title' => 'Pengkajian Awal Rawat Jalan Anak - Dokter',
            'service' => RJAnakPrintService::class,
        ],

        'rja_perawat' => [
            'form' => 'pengkajian-rajal-anak',
            'sub' => 'PERAWAT',
            'template' => 'print_rajal_anak_perawat.docx',
            'title' => 'Pengkajian Awal Rawat Jalan Anak - Perawat',
            'service' => RJAnakPrintService::class,
        ],

        'rjj_dokter' => [
            'form' => 'pengkajian-rajal-jiwa',
            'sub' => 'DOKTER',
            'template' => 'print_rajal_jiwa_dokter.docx',
            'title' => 'Pengkajian Awal Rawat Jalan Jiwa - Dokter',
            'service' => RJJiwaPrintService::class,
        ],

        'rjj_perawat' => [
            'form' => 'pengkajian-rajal-jiwa',
            'sub' => 'PERAWAT',
            'template' => 'print_rajal_jiwa_perawat.docx',
            'title' => 'Pengkajian Awal Rawat Jalan Jiwa - Perawat',
            'service' => RJJiwaPrintService::class,
        ],

        'rjg_dokter' => [
            'form' => 'pengkajian-rajal-geriatri',
            'sub' => 'DOKTER',
            'template' => 'print_rajal_geriatri_dokter.docx',
            'title' => 'Pengkajian Awal Rawat Jalan Geriatri - Dokter',
            'service' => RJGeriatriPrintService::class,
        ],

        'rjg_perawat' => [
            'form' => 'pengkajian-rajal-geriatri',
            'sub' => 'PERAWAT',
            'template' => 'print_rajal_geriatri_perawat.docx',
            'title' => 'Pengkajian Awal Rawat Jalan Geriatri - Perawat',
            'service' => RJGeriatriPrintService::class,
        ],

        'rjo_dokter' => [
            'form' => 'pengkajian-rajal-obsgyn',
            'sub' => 'DOKTER',
            'template' => 'print_rajal_obsgyn_dokter.docx',
            'title' => 'Pengkajian Awal Rawat Jalan Obsgyn - Dokter',
            'service' => RJObsgynPrintService::class,
        ],

        'rjo_perawat' => [
            'form' => 'pengkajian-rajal-obsgyn',
            'sub' => 'PERAWAT',
            'template' => 'print_rajal_obsgyn_perawat.docx',
            'title' => 'Pengkajian Awal Rawat Jalan Obsgyn - Perawat',
            'service' => RJObsgynPrintService::class,
        ],

        // ---------------------------------------------------------------------
        // Rawat Inap
        // ---------------------------------------------------------------------

        'rid_dokter' => [
            'form' => 'pengkajian-ranap-dewasa',
            'sub' => 'DOKTER',
            'template' => 'print_ranap_dewasa_dokter.docx',
            'title' => 'Pengkajian Awal Rawat Inap Dewasa - Dokter',
            'service' => RIDewasaPrintService::class,
        ],

        'rid_perawat' => [
            'form' => 'pengkajian-ranap-dewasa',
            'sub' => 'PERAWAT',
            'template' => 'print_ranap_dewasa_perawat.docx',
            'title' => 'Pengkajian Awal Rawat Inap Dewasa - Perawat',
            'service' => RIDewasaPrintService::class,
        ],

        'ria_dokter' => [
            'form' => 'pengkajian-ranap-anak',
            'sub' => 'DOKTER',
            'template' => 'print_ranap_anak_dokter.docx',
            'title' => 'Pengkajian Awal Rawat Inap Anak - Dokter',
            'service' => RIAnakPrintService::class,
        ],

        'ria_perawat' => [
            'form' => 'pengkajian-ranap-anak',
            'sub' => 'PERAWAT',
            'template' => 'print_ranap_anak_perawat.docx',
            'title' => 'Pengkajian Awal Rawat Inap Anak - Perawat',
            'service' => RIAnakPrintService::class,
        ],

        'rin_dokter' => [
            'form' => 'pengkajian-ranap-neonatus',
            'sub' => 'DOKTER',
            'template' => 'print_ranap_neonatus_dokter.docx',
            'title' => 'Pengkajian Awal Rawat Inap Neonatus - Dokter',
            'service' => RINeonatusPrintService::class,
        ],

        'rin_perawat' => [
            'form' => 'pengkajian-ranap-neonatus',
            'sub' => 'PERAWAT',
            'template' => 'print_ranap_neonatus_perawat.docx',
            'title' => 'Pengkajian Awal Rawat Inap Neonatus - Perawat',
            'service' => RINeonatusPrintService::class,
        ],

        'rio_dokter' => [
            'form' => 'pengkajian-ranap-obsgyn',
            'sub' => 'DOKTER',
            'template' => 'print_ranap_obsgyn_dokter.docx',
            'title' => 'Pengkajian Awal Rawat Inap Obstetri & Ginekologi - Dokter',
            'service' => RIObsgynPrintService::class,
        ],

        'rio_perawat' => [
            'form' => 'pengkajian-ranap-obsgyn',
            'sub' => 'PERAWAT',
            'template' => 'print_ranap_obsgyn_perawat.docx',
            'title' => 'Pengkajian Awal Rawat Inap Obstetri & Ginekologi - Perawat',
            'service' => RIObsgynPrintService::class,
        ],

        // =====================================================================
        // BEDAH & ANESTESI
        // =====================================================================

        'prabedah' => [
            'form' => 'pengkajian-prabedah',
            'sub' => 'BEDAH',
            'template' => 'print_prabedah.docx',
            'title' => 'Pengkajian Pra Bedah',
            'service' => PraBedahPrintService::class,
        ],

        'praanestesiinduksi' => [
            'form' => 'pengkajian-praanestesiinduksi',
            'sub' => 'ANESTESI',
            'template' => 'print_praanestesi_induksi.docx',
            'title' => 'Pengkajian Pra Anestesi Induksi',
            'service' => PraAnestesiInduksiPrintService::class,
        ],

        'lap_anestesi' => [
            'form' => 'laporan-anestesi',
            'sub' => 'PENATA-ANESTESI',
            'template' => 'print_laporan_anestesi.docx',
            'title' => 'Laporan Anestesi',
            'service' => LaporanAnestesiPrintService::class,
        ],

        'lap_pasca_anestesi' => [
            'form' => 'instruksi-pasca-anestesi',
            'sub' => 'DOKTER-ANESTESI',
            'template' => 'print_pasca_anestesi.docx',
            'title' => 'Instruksi Pasca Anestesi',
            'service' => LaporanAnestesiPrintService::class,
        ],

        // =====================================================================
        // PENGKAJIAN KHUSUS
        // =====================================================================

        'kh_remaja' => [
            'form' => 'pengkajian-khusus-remaja',
            'sub' => 'KHUSUS',
            'template' => 'print_khusus_remaja.docx',
            'title' => 'Pengkajian Khusus Remaja',
            'service' => KHRemajaPrintService::class,
        ],

        'kh_terminal' => [
            'form' => 'pengkajian-khusus-terminal',
            'sub' => 'KHUSUS',
            'template' => 'print_khusus_terminal.docx',
            'title' => 'Pengkajian Khusus Terminal',
            'service' => KHTerminalPrintService::class,
        ],

        'kh_nyerikronik' => [
            'form' => 'pengkajian-khusus-nyerikronik',
            'sub' => 'KHUSUS',
            'template' => 'print_khusus_nyerikronik.docx',
            'title' => 'Pengkajian Khusus Nyeri Kronik',
            'service' => KHNyeriKronikPrintService::class,
        ],

        'kh_sistemimun' => [
            'form' => 'pengkajian-khusus-sistemimunterganggu',
            'sub' => 'KHUSUS',
            'template' => 'print_khusus_sistem_imun.docx',
            'title' => 'Pengkajian Khusus Sistem Imun Terganggu',
            'service' => KHSistemImunPrintService::class,
        ],

        'kh_kecanduanobat' => [
            'form' => 'pengkajian-khusus-kecanduanobatalkohol',
            'sub' => 'KHUSUS',
            'template' => 'print_khusus_kecanduan_obat_alkohol.docx',
            'title' => 'Pengkajian Khusus Kecanduan Obat / Alkohol',
            'service' => KHKecanduanObatPrintService::class,
        ],

        'kh_korbankekerasan' => [
            'form' => 'pengkajian-khusus-korbankekerasan',
            'sub' => 'KHUSUS',
            'template' => 'print_khusus_korban_kekerasan.docx',
            'title' => 'Pengkajian Khusus Korban Kekerasan',
            'service' => KHKorbanKekerasanPrintService::class,
        ],

        'kh_penyakitmenular' => [
            'form' => 'pengkajian-khusus-penyakitmenular',
            'sub' => 'KHUSUS',
            'template' => 'print_khusus_penyakit_menular.docx',
            'title' => 'Pengkajian Khusus Penyakit Menular',
            'service' => KHPenyakitMenularPrintService::class,
        ],

        'kh_lanjutan' => [
            'form' => 'pengkajian-khusus-lanjutan',
            'sub' => 'KHUSUS',
            'template' => 'print_khusus_lanjutan.docx',
            'title' => 'Pengkajian Khusus Lanjutan',
            'service' => KHLanjutanPrintService::class,
        ],

        // =====================================================================
        // FORM LAIN
        // =====================================================================

        'ln_transfer' => [
            'form' => 'transfer-pasien',
            'sub' => 'PERAWAT',
            'template' => 'print_transfer_pasien.docx',
            'title' => 'Transfer Pasien',
            'service' => LembarTransferPasienPrintService::class,
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

    public static function exists(string $formKey): bool
    {
        return isset(self::FORM_SUB[$formKey]);
    }

    public static function template(string $formKey): string
    {
        return self::get($formKey)['template'];
    }

    public static function title(string $formKey): string
    {
        return self::get($formKey)['title'];
    }
}
