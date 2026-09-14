<?php

namespace App\Support;

use InvalidArgumentException;

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
            'template' => 'print_rawatdarurat_dokter.docx',
            'title' => 'Pengkajian Awal Rawat Darurat - Dokter',
        ],

        'gdp_dokter' => [
            'form' => 'pengkajian-radar',
            'sub' => 'PERAWAT',
            'template' => 'print_rawatdarurat_perawat.docx',
            'title' => 'Pengkajian Awal Rawat Darurat - Perawat',
        ],

        // ---------------------------------------------------------------------
        // Rawat Jalan
        // ---------------------------------------------------------------------

        '??' => [ // FORM KEY
            'form' => '??', // simrspku_pengkajian.finalisasi.FORM
            'sub' => 'DOKTER', // simrspku_pengkajian.finalisasi.SUB
            'template' => 'print_??_dokter.docx', // file di public\doc\emr\??.docx
            'title' => 'Pengkajian Awal Rawat Jalan ?? - Dokter',
        ],


        // ---------------------------------------------------------------------
        // Rawat Inap
        // ---------------------------------------------------------------------

        'rid_dokter' => [
            'form' => 'pengkajian-ranap-dewasa',
            'sub' => 'DOKTER',
            'template' => 'print_rawatinap_dewasa_dokter.docx',
            'title' => 'Pengkajian Awal Rawat Inap Dewasa - Dokter',
        ],

        'rid_perawat' => [
            'form' => 'pengkajian-ranap-dewasa',
            'sub' => 'PERAWAT',
            'template' => 'print_rawatinap_dewasa_perawat.docx',
            'title' => 'Pengkajian Awal Rawat Inap Dewasa - Perawat',
        ],

        'ria_dokter' => [
            'form' => 'pengkajian-ranap-anak',
            'sub' => 'DOKTER',
            'template' => 'print_rawatinap_anak_dokter.docx',
            'title' => 'Pengkajian Awal Rawat Inap Anak - Dokter',
        ],

        'ria_perawat' => [
            'form' => 'pengkajian-ranap-anak',
            'sub' => 'PERAWAT',
            'template' => 'print_rawatinap_anak_perawat.docx',
            'title' => 'Pengkajian Awal Rawat Inap Anak - Perawat',
        ],

        'rin_dokter' => [
            'form' => 'pengkajian-ranap-neonatus',
            'sub' => 'DOKTER',
            'template' => 'print_rawatinap_neonatus_dokter.docx',
            'title' => 'Pengkajian Awal Rawat Inap Neonatus - Dokter',
        ],

        'rin_perawat' => [
            'form' => 'pengkajian-ranap-neonatus',
            'sub' => 'PERAWAT',
            'template' => 'print_rawatinap_neonatus_perawat.docx',
            'title' => 'Pengkajian Awal Rawat Inap Neonatus - Perawat',
        ],

        'rio_dokter' => [
            'form' => 'pengkajian-ranap-obsgyn',
            'sub' => 'DOKTER',
            'template' => 'print_rawatinap_obsgyn_dokter.docx',
            'title' => 'Pengkajian Awal Rawat Inap Obstetri & Ginekologi - Dokter',
        ],

        'rio_perawat' => [
            'form' => 'pengkajian-ranap-obsgyn',
            'sub' => 'PERAWAT',
            'template' => 'print_rawatinap_obsgyn_perawat.docx',
            'title' => 'Pengkajian Awal Rawat Inap Obstetri & Ginekologi - Perawat',
        ],

        // =====================================================================
        // BEDAH & ANESTESI
        // =====================================================================

        'prabedah' => [
            'form' => 'pengkajian-prabedah',
            'sub' => 'BEDAH',
            'template' => 'print_prabedah.docx',
            'title' => 'Pengkajian Pra Bedah',
        ],

        'praanestesiinduksi' => [
            'form' => 'pengkajian-praanestesiinduksi',
            'sub' => 'ANESTESI',
            'template' => 'print_praanestesi_induksi.docx',
            'title' => 'Pengkajian Pra Anestesi Induksi',
        ],

        'lap_anestesi' => [
            'form' => 'laporan-anestesi',
            'sub' => 'PENATA-ANESTESI',
            'template' => 'print_laporan_anestesi.docx',
            'title' => 'Laporan Anestesi',
        ],

        'lap_pasca_anestesi' => [
            'form' => 'instruksi-pasca-anestesi',
            'sub' => 'DOKTER-ANESTESI',
            'template' => 'print_pasca_anestesi.docx',
            'title' => 'Instruksi Pasca Anestesi',
        ],

        // =====================================================================
        // PENGKAJIAN KHUSUS
        // =====================================================================

        'kh_remaja' => [
            'form' => 'pengkajian-khusus-remaja',
            'sub' => 'KHUSUS',
            'template' => 'print_khusus_remaja.docx',
            'title' => 'Pengkajian Khusus Remaja',
        ],

        'kh_terminal' => [
            'form' => 'pengkajian-khusus-terminal',
            'sub' => 'KHUSUS',
            'template' => 'print_khusus_terminal.docx',
            'title' => 'Pengkajian Khusus Terminal',
        ],

        'kh_nyerikronik' => [
            'form' => 'pengkajian-khusus-nyerikronik',
            'sub' => 'KHUSUS',
            'template' => 'print_khusus_nyerikronik.docx',
            'title' => 'Pengkajian Khusus Nyeri Kronik',
        ],

        'kh_sistemimun' => [
            'form' => 'pengkajian-khusus-sistemimunterganggu',
            'sub' => 'KHUSUS',
            'template' => 'print_khusus_sistem_imun.docx',
            'title' => 'Pengkajian Khusus Sistem Imun Terganggu',
        ],

        'kh_kecanduanobat' => [
            'form' => 'pengkajian-khusus-kecanduanobatalkohol',
            'sub' => 'KHUSUS',
            'template' => 'print_khusus_kecanduan_obat_alkohol.docx',
            'title' => 'Pengkajian Khusus Kecanduan Obat / Alkohol',
        ],

        'kh_korbankekerasan' => [
            'form' => 'pengkajian-khusus-korbankekerasan',
            'sub' => 'KHUSUS',
            'template' => 'print_khusus_korban_kekerasan.docx',
            'title' => 'Pengkajian Khusus Korban Kekerasan',
        ],

        'kh_penyakitmenular' => [
            'form' => 'pengkajian-khusus-penyakitmenular',
            'sub' => 'KHUSUS',
            'template' => 'print_khusus_penyakit_menular.docx',
            'title' => 'Pengkajian Khusus Penyakit Menular',
        ],

        'kh_lanjutan' => [
            'form' => 'pengkajian-khusus-lanjutan',
            'sub' => 'KHUSUS',
            'template' => 'print_khusus_lanjutan.docx',
            'title' => 'Pengkajian Khusus Lanjutan',
        ],

        // =====================================================================
        // FORM LAIN
        // =====================================================================

        'ln_transfer' => [
            'form' => 'transfer-pasien',
            'sub' => 'PERAWAT',
            'template' => 'print_transfer_pasien.docx',
            'title' => 'Transfer Pasien',
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
