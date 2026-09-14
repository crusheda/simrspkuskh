<?php

namespace App\Http\Controllers\EMR\Form;

use App\Http\Controllers\Controller;
use App\Services\LibreOfficeService;
use App\Support\FinalisasiMap;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\TemplateProcessor;

class PrintPreviewController extends Controller
{
    protected LibreOfficeService $libreOffice;

    public function __construct(LibreOfficeService $libreOffice)
    {
        $this->libreOffice = $libreOffice;
    }

    /**
     * ========================================================================
     * PRINT PREVIEW
     * ========================================================================
     */
    public function printPreview(Request $request, $kunjungan)
    {
        try {
            /*
            |--------------------------------------------------------------------------
            | VALIDASI
            |--------------------------------------------------------------------------
            */

            $request->validate([
                'formKey' => ['required', 'string'],
            ]);

            $formKey = $request->formKey;

            /*
            |--------------------------------------------------------------------------
            | AMBIL MAPPING FORM
            |--------------------------------------------------------------------------
            */

            $map = FinalisasiMap::get($formKey);

            $form = $map['form'];
            $sub = $map['sub'];
            $template = $map['template'];
            $title = $map['title'];

            /*
            |--------------------------------------------------------------------------
            | CEK FINALISASI
            |--------------------------------------------------------------------------
            */

            $finalisasi = DB::table(
                'simrspku_pengkajian.finalisasi'
            )
                ->where('kunjungan', $kunjungan)
                ->where('form', $form)
                ->where('sub', $sub)
                ->where('status', 2)
                ->first();

            /*
            |--------------------------------------------------------------------------
            | WAJIB SUDAH FINAL
            |--------------------------------------------------------------------------
            */

            if (!$finalisasi) {
                return response()->json([
                    'status' => false,
                    'message' =>
                        'Form belum difinalisasi. ' .
                        'Print Preview hanya dapat dilakukan setelah form difinalisasi.',
                ], 403);
            }

            /*
            |--------------------------------------------------------------------------
            | DATA FINALISASI
            |--------------------------------------------------------------------------
            */

            $namaFinalisasi = '-';

            if (!empty($finalisasi->user)) {
                $namaFinalisasi = DB::table(
                    'master.pegawai'
                )
                    ->where('ID', $finalisasi->user)
                    ->value('NAMA') ?? '-';
            }

            $tanggalFinalisasi = '-';

            if (!empty($finalisasi->created_at)) {
                $tanggalFinalisasi = Carbon::parse(
                    $finalisasi->created_at
                )->translatedFormat(
                    'DD MMMM YYYY [Pukul] HH:mm:ss'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | TEMPLATE
            |--------------------------------------------------------------------------
            */

            $templatePath = public_path(
                'doc/emr/' . $template
            );

            if (!File::exists($templatePath)) {
                return response()->json([
                    'status' => false,
                    'message' =>
                        "Template {$template} tidak ditemukan.",
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | OUTPUT DIRECTORY
            |--------------------------------------------------------------------------
            */

            $outputDir = storage_path(
                'app/public/files/emr/preview/' . $kunjungan
            );

            if (!File::exists($outputDir)) {
                File::makeDirectory(
                    $outputDir,
                    0755,
                    true
                );
            }

            /*
            |--------------------------------------------------------------------------
            | NAMA FILE
            |--------------------------------------------------------------------------
            */

            $safeFormKey = preg_replace(
                '/[^A-Za-z0-9_\-]/',
                '_',
                $formKey
            );

            $fileName =
                $safeFormKey . '_' . $kunjungan;

            $temporaryWord =
                $outputDir
                . DIRECTORY_SEPARATOR
                . $fileName
                . '.docx';

            $finalPdf =
                $outputDir
                . DIRECTORY_SEPARATOR
                . $fileName
                . '.pdf';

            /*
            |--------------------------------------------------------------------------
            | HAPUS FILE LAMA
            |--------------------------------------------------------------------------
            */

            if (File::exists($temporaryWord)) {
                File::delete($temporaryWord);
            }

            if (File::exists($finalPdf)) {
                File::delete($finalPdf);
            }

            /*
            |--------------------------------------------------------------------------
            | TEMPLATE PROCESSOR
            |--------------------------------------------------------------------------
            */

            $templateProcessor = new TemplateProcessor(
                $templatePath
            );

            /*
            |--------------------------------------------------------------------------
            | DATA DUMMY
            |--------------------------------------------------------------------------
            */

            $data = [
                /*
                |--------------------------------------------------------------------------
                | INSTANSI
                |--------------------------------------------------------------------------
                */

                'NAMAINST' =>
                    'RS PKU MUHAMMADIYAH SUKOHARJO',

                'ALAMATINST' =>
                    'Jl. Slamet Riyadi No. 534 Sukoharjo',

                /*
                |--------------------------------------------------------------------------
                | PASIEN
                |--------------------------------------------------------------------------
                */

                'KUNJUNGAN' =>
                    $kunjungan,

                'NORM' =>
                    '00000001',

                'NAMALENGKAP' =>
                    'NAMA PASIEN DUMMY',

                'JK' =>
                    'Laki-laki',

                'TGLLAHIR' =>
                    '01 Januari 1990',

                'UMUR' =>
                    '36 Tahun',

                'ALAMAT' =>
                    'Alamat Pasien Dummy',

                /*
                |--------------------------------------------------------------------------
                | FORM
                |--------------------------------------------------------------------------
                */

                'FORMKEY' =>
                    $formKey,

                'FORM' =>
                    $form,

                'SUB' =>
                    $sub,

                'JUDULFORM' =>
                    $title,

                /*
                |--------------------------------------------------------------------------
                | FINALISASI
                |--------------------------------------------------------------------------
                */

                'FINALISASI_OLEH' =>
                    $namaFinalisasi,

                'FINALISASI_TANGGAL' =>
                    $tanggalFinalisasi,

                /*
                |--------------------------------------------------------------------------
                | CETAK
                |--------------------------------------------------------------------------
                */

                'TANGGAL_CETAK' =>
                    now()->translatedFormat(
                        'DD MMMM YYYY [Pukul] HH:mm:ss'
                    ),
            ];

            /*
            |--------------------------------------------------------------------------
            | SET VALUE
            |--------------------------------------------------------------------------
            */

            foreach ($data as $key => $value) {
                $templateProcessor->setValue(
                    $key,
                    $value ?? ''
                );
            }

            /*
            |--------------------------------------------------------------------------
            | SAVE DOCX
            |--------------------------------------------------------------------------
            */

            $templateProcessor->saveAs(
                $temporaryWord
            );

            if (!File::exists($temporaryWord)) {
                throw new \Exception(
                    'Gagal membuat file DOCX print preview.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | CONVERT DOCX -> PDF
            |--------------------------------------------------------------------------
            */

            [$success] = $this->libreOffice->generatePdf(
                $temporaryWord,
                $outputDir
            );

            if (
                !$success ||
                !File::exists($finalPdf)
            ) {
                throw new \Exception(
                    'Gagal generate PDF print preview.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | HAPUS DOCX
            |--------------------------------------------------------------------------
            */

            if (File::exists($temporaryWord)) {
                File::delete($temporaryWord);
            }

            /*
            |--------------------------------------------------------------------------
            | RETURN PDF
            |--------------------------------------------------------------------------
            |
            | SAMA DENGAN previewFormJadwalPelayanan()
            |
            */

            return response()->file($finalPdf, [
                'Content-Type' =>
                    'application/pdf',

                'Cache-Control' =>
                    'no-store, no-cache, must-revalidate, max-age=0',

                'Pragma' =>
                    'no-cache',

                'Expires' =>
                    '0',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
