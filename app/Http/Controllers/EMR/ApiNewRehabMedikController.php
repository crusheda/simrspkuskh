<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\simrspku_klaim\klaim_verifikasi;
use App\Models\simrspku_klaim\klaim_file;
use App\Models\Pengguna;
use App\Models\simrspku_klaim\form_kfr;
use App\Models\simrspku_klaim\form_kfr_jp;
use App\Models\simrspku_klaim\form_kfr_ks;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\TemplateProcessor;
use PHPJasper\PHPJasper;
use Carbon\Carbon;
use Auth, Storage;

class ApiNewRehabMedikController extends Controller
{
    // CONTROLLER FOR LIBRE OFFICE ON LINUX SERVER
    public function libreOffice($input, $output)
    {
        $soffice = env('LIBREOFFICE_PATH', '/usr/bin/soffice');

        // Pastikan path absolut
        $input  = realpath($input);
        $output = realpath($output);

        if (!$input || !$output) {
            \Log::error('Path input/output tidak valid', compact('input','output'));
            return [false, [], -1];
        }

        // Kill proses soffice lama (Linux)
        exec('pkill -f soffice 2>/dev/null');

        // Command convert
        $cmd = sprintf(
            '%s --headless --nologo --nofirststartwizard --convert-to pdf %s --outdir %s 2>&1',
            escapeshellcmd($soffice),
            escapeshellarg($input),
            escapeshellarg($output)
        );

        exec($cmd, $log, $result);

        $outputPdf = $output . '/' . pathinfo($input, PATHINFO_FILENAME) . '.pdf';

        if ($result !== 0 || !file_exists($outputPdf)) {
            \Log::error('LibreOffice Linux gagal konversi', [
                'cmd' => $cmd,
                'log' => $log,
                'result' => $result
            ]);
            return [false, $log, $result];
        }

        return [true, $log, $result];
    }

    // CONTROLLER FOR LIBRE OFFICE ON WINDOWS SERVER
    // public function libreOffice($input, $output)
    // {
    //     // LINK DOWNLOAD LIBRE OFFICE = https://www.libreoffice.org/download/download
    //     // Ambil path dari .env (lebih fleksibel kalau update LibreOffice)
    //     $soffice = env('LIBREOFFICE_PATH', 'C:/Program Files/LibreOffice/program/soffice.exe');
    //     $soffice = '"'.$soffice.'"';

    //     // 🔹 Kill proses lama dulu (biar tidak nyangkut)
    //     exec('taskkill /IM soffice.bin /F 2> NUL');

    //     // Jalankan konversi
    //     $cmd = $soffice . ' --headless --convert-to pdf ' . escapeshellarg($input) . ' --outdir ' . escapeshellarg($output);
    //     exec($cmd, $log, $result);

    //     // 🔹 Cek hasil
    //     $outputPdf = $output . '/' . pathinfo($input, PATHINFO_FILENAME) . '.pdf';
    //     if ($result !== 0 || !file_exists($outputPdf)) {
    //         \Log::error('LibreOffice gagal konversi', [
    //             'cmd' => $cmd,
    //             'log' => $log,
    //             'result' => $result
    //         ]);
    //         return [false, $log, $result];
    //     }

    //     return [true, $log, $result];
    // }

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

    function formKfr() {

    }
}
