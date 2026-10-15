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
use PhpOffice\PhpWord\Element\TextRun;
use Illuminate\Support\Facades\Log;
use Storage;

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
                'simrspku_pengkajian.finalisasi as fin'
            )
                ->leftJoin('aplikasi.pengguna as usr', 'usr.ID', '=', 'fin.USER_CREATED')
                ->select('fin.*', 'usr.NIP as NIP_USER_FINALISASI', DB::raw('master.getNamaLengkapPegawai(usr.NIP) as NAMA_USER_FINALISASI'))
                ->where('fin.KUNJUNGAN', $kunjungan)
                ->where('fin.FORM', $form)
                ->where('fin.SUB', $sub)
                ->where('fin.STATUS', 2)
                ->first();

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
            $namaFinalisasi = $finalisasi->NAMA_USER_FINALISASI ?? '-';

            $tanggalFinalisasi = '-';
            $jamFinalisasi = '-';

            if (!empty($finalisasi->CREATED)) {
                $tanggalFinalisasi = Carbon::parse($finalisasi->CREATED)
                    ->isoFormat('dddd, D MMMM YYYY')
                    . ' WIB';
                $jamFinalisasi = Carbon::parse($finalisasi->CREATED)->isoFormat('[Pukul] H:mm:ss');
            }

            // print_r($ttd_finalisasi);
            // die();
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

            $fileName = $safeFormKey . '_' . $kunjungan;

            $temporaryWord =
                $outputDir .
                DIRECTORY_SEPARATOR .
                $fileName .
                '.docx';

            $finalPdf =
                $outputDir .
                DIRECTORY_SEPARATOR .
                $fileName .
                '.pdf';

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
            | DATA UMUM
            |--------------------------------------------------------------------------
            */
            $data = $this->getDataUmum(
                $kunjungan,
                $formKey,
                $form,
                $sub,
                $title,
                $namaFinalisasi,
                $tanggalFinalisasi,
                $jamFinalisasi
            );

            /*
            |--------------------------------------------------------------------------
            | DATA KHUSUS FORM
            |--------------------------------------------------------------------------
            */
            $dataForm = $this->getDataForm(
                $formKey,
                $kunjungan,
                $sub
            );

            /*
            |--------------------------------------------------------------------------
            | GABUNGKAN DATA
            |--------------------------------------------------------------------------
            */

            $data = array_merge(
                $data,
                $dataForm
            );

            /*
            |--------------------------------------------------------------------------
            | SET VALUE WORD
            |--------------------------------------------------------------------------
            */
            foreach ($data as $key => $value) {

                /*
                |--------------------------------------------------------------------------
                | TANDA TANGAN DOKTER
                |--------------------------------------------------------------------------
                */
                if ($key === 'TTD_DOKTER_FINALISASI') {

                    // Ambil path TTD dokter
                    $namaDokter = $data['NAMA_DOKTER_FINALISASI'] ?? '';
                    $ttdDokter = $data['TTD_DOKTER_FINALISASI'] ?? null;

                    // Keluarkan TTD dari array agar tidak diproses sebagai teks
                    unset($data['TTD_DOKTER_FINALISASI']);

                    // Isi seluruh placeholder teks
                    foreach ($data as $key => $value) {
                        $templateProcessor->setValue(
                            $key,
                            (string) ($value ?? '')
                        );
                    }

                    // Masukkan gambar TTD dokter
                    if (
                        $ttdDokter &&
                        Storage::disk('public')->exists($ttdDokter)
                    ) {
                        $this->setImgWord(
                            $templateProcessor,
                            'TTD_DOKTER_FINALISASI',
                            Storage::disk('public')->path($ttdDokter),
                            200
                        );
                    } else {
                        throw new \Exception(
                            "File TTD {$namaDokter} (Dokter) tidak ditemukan: "
                            . ($ttdDokter ?? '-')
                        );
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | HASIL LABORATORIUM DAN RADIOLOGI
                |--------------------------------------------------------------------------
                */

                // if (in_array($key, [
                //     'HASIL_LABORATORIUM',
                //     'HASIL_RADIOLOGI',
                // ], true)) {

                //     $textRun = new TextRun();

                //     $baris = explode(
                //         "\n",
                //         (string) ($value ?? '')
                //     );

                //     foreach ($baris as $index => $teks) {

                //         if ($index > 0) {
                //             $textRun->addTextBreak();
                //         }

                //         $teks = rtrim($teks);

                //         if ($teks === '') {
                //             continue;
                //         }

                //         /*
                //         * Judul laporan dan subjudul menggunakan huruf tebal.
                //         */
                //         $judul = (
                //             mb_strtoupper($teks, 'UTF-8') === $teks
                //             && preg_match('/[A-Z]/', $teks)
                //             && mb_strlen($teks, 'UTF-8') < 80
                //         );

                //         $textRun->addText(
                //             $teks,
                //             $judul
                //                 ? ['bold' => true, 'size' => 9]
                //                 : ['size' => 9]
                //         );
                //     }

                //     $templateProcessor->setComplexValue(
                //         $key,
                //         $textRun
                //     );

                //     continue;
                // }

                /*
                |--------------------------------------------------------------------------
                | DATA TEKS LAINNYA
                |--------------------------------------------------------------------------
                */
                $templateProcessor->setValue(
                    $key,
                    (string) ($value ?? '')
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

            [$success, $convertLog, $exitCode] = $this->libreOffice->generatePdf(
                $temporaryWord,
                $outputDir
            );

            clearstatcache(true, $finalPdf);

            if (
                !$success ||
                !File::exists($finalPdf) ||
                File::size($finalPdf) === 0
            ) {
                Log::error(
                    'Print Preview: gagal generate PDF',
                    [
                        'kunjungan' => $kunjungan,
                        'formKey' => $formKey,
                        'temporaryWord' => $temporaryWord,
                        'expectedPdf' => $finalPdf,
                        'pdfExists' => File::exists($finalPdf),
                        'pdfSize' => File::exists($finalPdf)
                            ? File::size($finalPdf)
                            : 0,
                        'success' => $success,
                        'exitCode' => $exitCode,
                        'convertLog' => $convertLog,
                    ]
                );

                throw new \Exception(
                    'Gagal generate PDF print preview. Periksa storage/logs/laravel.log.'
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


    /**
     * ========================================================================
     * DATA UMUM
     * ========================================================================
     */
    private function getDataUmum(
        $kunjungan,
        $formKey,
        $form,
        $sub,
        $title,
        $namaFinalisasi,
        $tanggalFinalisasi,
        $jamFinalisasi
    ): array {

        /*
        |--------------------------------------------------------------------------
        | IDENTITAS PASIEN DAN KUNJUNGAN
        |--------------------------------------------------------------------------
        */

        $pasien = DB::table('pendaftaran.kunjungan as pk')
            ->join(
                'pendaftaran.pendaftaran as pp',
                'pp.NOMOR',
                '=',
                'pk.NOPEN'
            )
            ->leftJoin(
                'master.pasien as ps',
                'ps.NORM',
                '=',
                'pp.NORM'
            )
            ->leftJoin(
                'master.ruangan as ru',
                'ru.ID',
                '=',
                'pk.RUANGAN'
            )
            ->leftJoin(
                'master.dokter as dr',
                'dr.ID',
                '=',
                'pk.DPJP'
            )
            ->select([
                'pp.NOMOR as NOPEN',

                // NORM selalu minimal 8 digit
                DB::raw("LPAD(pp.NORM, 8, '0') as NORM_FORMATTED"),

                'pp.TANGGAL as TGLDAFTAR',

                'pk.NOMOR as KUNJUNGAN',
                'pk.MASUK as TGLKUNJUNGAN',
                'pk.KELUAR as TGLKELUAR',

                'ru.DESKRIPSI as NAMARUANGAN',

                'ps.*',

                DB::raw(
                    'master.getNamaLengkap(ps.NORM) as NAMALENGKAP'
                ),

                DB::raw(
                    'master.getAlamatPasienCustom(ps.NORM) as ALAMAT'
                ),

                DB::raw(
                    'master.getCariUmur(pp.TANGGAL, ps.TANGGAL_LAHIR) as UMUR'
                ),

                DB::raw(
                    'master.getNamaLengkapPegawai(dr.NIP) as NAMADOKTER'
                ),
            ])
            ->where('pk.NOMOR', $kunjungan)
            ->first();

        if (!$pasien) {
            return [];
        }

        /*
        |--------------------------------------------------------------------------
        | RETURN DATA TEMPLATE
        |--------------------------------------------------------------------------
        */

        return [

            // INSTANSI
            'NAMAINST' => 'RS PKU MUHAMMADIYAH SUKOHARJO',
            'ALAMATINST' => 'Jl. Mayor Sunaryo No.37, Gawanan, Sukoharjo',

            // KUNJUNGAN
            'KUNJUNGAN' => $kunjungan,
            'NOPEN' => $pasien->NOPEN ?? '',

            // IDENTITAS PASIEN
            'NORM' => $pasien->NORM_FORMATTED ?? '',
            'NAMALENGKAP' => $pasien->NAMALENGKAP ?? '',

            'JK' => (
                $pasien->JENIS_KELAMIN
                ?? $pasien->JK
                ?? ''
            ),

            'TGLLAHIR' => $this->formatTanggal(
                $pasien->TANGGAL_LAHIR ?? null
            ),

            'UMUR' => $pasien->UMUR ?? '',
            'ALAMAT' => $pasien->ALAMAT ?? '',

            // TANGGAL DAN JAM KUNJUNGAN
            'TGLDAFTAR' => $this->formatTanggal(
                $pasien->TGLDAFTAR
            ),

            'TGLKUNJUNGAN' => $this->formatTanggal(
                $pasien->TGLKUNJUNGAN
            ),

            'JAMKUNJUNGAN' => $this->formatJam(
                $pasien->TGLKUNJUNGAN
            ),

            'TGLKELUAR' => $this->formatTanggal(
                $pasien->TGLKELUAR
            ),

            'NAMARUANGAN' => $pasien->NAMARUANGAN ?? '',
            'NAMADOKTER' => $pasien->NAMADOKTER ?? '',

            // FORM
            'FORMKEY' => $formKey,
            'FORM' => $form,
            'SUB' => $sub,
            'JUDULFORM' => $title,

            // FINALISASI
            'FINALISASI_OLEH' => $namaFinalisasi,
            'FINALISASI_TANGGAL' => $tanggalFinalisasi,
            'FINALISASI_JAM' => $jamFinalisasi,

            // CETAK
            'TANGGAL_CETAK' => now('Asia/Jakarta')
                ->locale('id')
                ->isoFormat('dddd, D MMMM YYYY [Pukul] HH:mm:ss')
                . ' WIB',
        ];
    }

    private function getDataForm(
        string $formKey,
        string $kunjungan,
        string $sub
    ): array {

        $map = FinalisasiMap::get($formKey);

        if (empty($map['service'])) {
            return [];
        }

        $service = app($map['service']);

        return $service->getData(
            $kunjungan,
            $sub
        );
    }


    // ====================================================================================================================================
    // =============================================================  ADD ONS  ============================================================
    // ====================================================================================================================================
    public static function setImgWord(TemplateProcessor $templateProcessor, string $key, string $imagePath, int $targetWidth)
    {
        if (!file_exists($imagePath)) {
            throw new \Exception("Gambar TTE tidak ditemukan: {$imagePath}");
        }

        [$originalWidth, $originalHeight] = getimagesize($imagePath);

        if ($originalWidth === 0) {
            throw new \Exception("Lebar gambar 0: {$imagePath}");
        }

        $ratio = $originalHeight / $originalWidth;
        $targetHeight = $targetWidth * $ratio;

        $templateProcessor->setImageValue($key, [
            'path' => $imagePath,
            'width' => $targetWidth,
            'height' => $targetHeight,
        ]);
    }
    /**
     * Format tanggal dan jam untuk dokumen cetak.
     */
    private function formatJam($jam): string
    {
        if (empty($jam)) {
            return '-';
        }

        try {
            return Carbon::parse($jam)->format('H:i') . ' WIB';
        } catch (\Throwable $e) {
            return (string) $jam;
        }
    }
    private function formatTanggal($tanggal): string
    {
        if (empty($tanggal)) {
            return '-';
        }

        try {
            return Carbon::parse($tanggal)
                ->locale('id')
                ->translatedFormat('d F Y');
        } catch (\Throwable $e) {
            return (string) $tanggal;
        }
    }
}
