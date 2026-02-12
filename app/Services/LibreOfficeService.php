<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class LibreOfficeService
{
    public function generatePdf(string $input, string $output): array
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'
            ? $this->convertWindows($input, $output)
            : $this->convertLinux($input, $output);
    }

    /**
     * =======================
     * LINUX SERVER
     * =======================
     */
    protected function convertLinux(string $input, string $output): array
    {
        $soffice = env('LIBREOFFICE_PATH', '/usr/bin/soffice');

        $input  = realpath($input);

        if (!is_dir($output)) {
            mkdir($output, 0755, true);
        }

        $output = realpath($output);

        if (!$input || !$output) {
            Log::error('Path input/output tidak valid', compact('input','output'));
            return [false, [], -1];
        }

        putenv('HOME=/tmp');
        putenv('XDG_CACHE_HOME=/tmp');

        $profile = '/tmp/lo_' . uniqid();

        $cmd = sprintf(
            '%s --headless --nologo --nofirststartwizard ' .
            '-env:UserInstallation=file://%s ' .
            '--convert-to pdf %s --outdir %s 2>&1',
            escapeshellcmd($soffice),
            escapeshellarg($profile),
            escapeshellarg($input),
            escapeshellarg($output)
        );

        Log::info('LibreOffice CMD', compact('cmd'));

        exec($cmd, $log, $result);

        $outputPdf = $output . '/' . pathinfo($input, PATHINFO_FILENAME) . '.pdf';

        exec('rm -rf ' . escapeshellarg($profile));

        if ($result !== 0 || !file_exists($outputPdf)) {
            Log::error('LibreOffice Linux gagal konversi', compact('cmd','log','result'));
            return [false, $log, $result];
        }

        return [true, $outputPdf, $result];
    }

    /**
     * =======================
     * WINDOWS SERVER
     * =======================
     */
    protected function convertWindows(string $input, string $output): array
    {
        $soffice = env(
            'LIBREOFFICE_PATH',
            'C:/Program Files/LibreOffice/program/soffice.exe'
        );

        $soffice = '"' . $soffice . '"';

        exec('taskkill /IM soffice.bin /F 2> NUL');

        $cmd = $soffice .
            ' --headless --convert-to pdf ' .
            escapeshellarg($input) .
            ' --outdir ' .
            escapeshellarg($output);

        exec($cmd, $log, $result);

        $outputPdf = $output . '/' . pathinfo($input, PATHINFO_FILENAME) . '.pdf';

        if ($result !== 0 || !file_exists($outputPdf)) {
            Log::error('LibreOffice Windows gagal konversi', compact('cmd','log','result'));
            return [false, $log, $result];
        }

        return [true, $outputPdf, $result];
    }
}
