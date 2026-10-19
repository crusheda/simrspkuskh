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
            // Log::error('Path input/output tidak valid', compact('input','output'));
            return [false, [], -1];
        }

        putenv('HOME=/tmp');
        putenv('XDG_CACHE_HOME=/tmp');

        $profile = '/tmp/lo_' . uniqid();

        $cmd = sprintf(
            '%s --headless --nologo --nofirststartwizard ' .
            // '-env:UserInstallation=file://%s ' .
            '-env:UserInstallation=file:///%s ' .
            '--convert-to pdf %s --outdir %s 2>&1',
            escapeshellcmd($soffice),
            // escapeshellarg($profile),
            ltrim($profile,'/'),
            escapeshellarg($input),
            escapeshellarg($output)
        );

        // Log::info('LibreOffice CMD', compact('cmd'));

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
    // protected function convertWindows(string $input, string $output): array
    // {
    //     $soffice = env(
    //         'LIBREOFFICE_PATH',
    //         'C:/Program Files/LibreOffice/program/soffice.exe'
    //     );

    //     $soffice = '"' . $soffice . '"';

    //     exec('taskkill /IM soffice.bin /F 2> NUL');

    //     $cmd = $soffice .
    //         ' --headless --convert-to pdf ' .
    //         escapeshellarg($input) .
    //         ' --outdir ' .
    //         escapeshellarg($output);

    //     // exec($cmd, $log, $result);

    //     // $outputPdf = $output . '/' . pathinfo($input, PATHINFO_FILENAME) . '.pdf';

    //     // if ($result !== 0 || !file_exists($outputPdf)) {
    //     //     Log::error('LibreOffice Windows gagal konversi', compact('cmd','log','result'));
    //     //     return [false, $log, $result];
    //     // }

    //     // return [true, $outputPdf, $result];

    //     $conversion = $this->executeLibreOffice($cmd, 60);

    //     $outputPdf = rtrim($output, '/\\')
    //         . DIRECTORY_SEPARATOR
    //         . pathinfo($input, PATHINFO_FILENAME)
    //         . '.pdf';

    //     if (
    //         !$conversion['success']
    //         || !file_exists($outputPdf)
    //     ) {
    //         Log::error('LibreOffice Windows gagal konversi', [
    //             'cmd' => $cmd,
    //             'log' => $conversion['output'],
    //             'result' => $conversion['exit_code'],
    //             'output_exists' => file_exists($outputPdf),
    //         ]);

    //         return [
    //             false,
    //             $conversion['output'],
    //             $conversion['exit_code'],
    //         ];
    //     }

    //     return [true, $outputPdf, $conversion['exit_code']];
    // }

    protected function convertWindows(string $input, string $output): array
    {
        $soffice = env(
            'LIBREOFFICE_PATH',
            'C:/Program Files/LibreOffice/program/soffice.com'
        );

        // Pastikan path input valid
        $input = realpath($input);

        if (!$input || !file_exists($input)) {
            Log::error('File DOCX tidak ditemukan', [
                'input' => $input,
            ]);

            return [false, ['File DOCX tidak ditemukan.'], -1];
        }

        // Pastikan direktori output tersedia
        if (!is_dir($output)) {
            if (!mkdir($output, 0755, true) && !is_dir($output)) {
                Log::error('Gagal membuat direktori output PDF', [
                    'output' => $output,
                ]);

                return [false, ['Gagal membuat direktori output PDF.'], -1];
            }
        }

        $output = realpath($output);

        if (!$output) {
            return [false, ['Direktori output tidak valid.'], -1];
        }

        $outputPdf = $output . DIRECTORY_SEPARATOR
            . pathinfo($input, PATHINFO_FILENAME) . '.pdf';

        // Hapus PDF lama agar tidak dianggap sebagai hasil konversi baru
        if (file_exists($outputPdf)) {
            if (!unlink($outputPdf)) {
                Log::error('Gagal menghapus PDF lama', [
                    'outputPdf' => $outputPdf,
                ]);

                return [false, ['Gagal menghapus PDF lama.'], -1];
            }
        }

        // Gunakan profil terpisah agar tidak bentrok dengan sesi LibreOffice lain
        $profile = sys_get_temp_dir()
            . DIRECTORY_SEPARATOR
            . 'lo-preview-' . uniqid('', true);

        if (!mkdir($profile, 0755, true) && !is_dir($profile)) {
            return [false, ['Gagal membuat profil LibreOffice.'], -1];
        }

        $profileUri = 'file:///' . str_replace(
            '\\',
            '/',
            $profile
        );

        $cmd = sprintf(
            '"%s" --headless --nologo --nofirststartwizard '
            . '-env:UserInstallation="%s" '
            . '--convert-to pdf:writer_pdf_Export '
            . '--outdir "%s" "%s" 2>&1',
            $soffice,
            $profileUri,
            $output,
            $input
        );

        // Log::info('LibreOffice Windows CMD', [
        //     'cmd' => $cmd,
        //     'input' => $input,
        //     'output' => $output,
        //     'expectedPdf' => $outputPdf,
        // ]);

        try {
            $conversion = $this->executeLibreOffice($cmd, 60);

            clearstatcache(true, $outputPdf);

            if (
                !$conversion['success']
                || !is_file($outputPdf)
                || filesize($outputPdf) === 0
            ) {
                Log::error('LibreOffice Windows gagal konversi', [
                    'cmd' => $cmd,
                    'log' => $conversion['output'],
                    'result' => $conversion['exit_code'],
                    'output_exists' => file_exists($outputPdf),
                    'output_size' => file_exists($outputPdf)
                        ? filesize($outputPdf)
                        : 0,
                ]);

                return [
                    false,
                    $conversion['output'],
                    $conversion['exit_code'],
                ];
            }

            // Log::info('LibreOffice Windows berhasil konversi', [
            //     'pdf' => $outputPdf,
            //     'size' => filesize($outputPdf),
            // ]);

            return [true, $outputPdf, $conversion['exit_code']];
        } finally {
            // Bersihkan profil sementara
            if (is_dir($profile)) {
                try {
                    $this->removeDirectory($profile);
                } catch (\Throwable $e) {
                    Log::warning('Gagal membersihkan profil LibreOffice', [
                        'profile' => $profile,
                        'message' => $e->getMessage(),
                    ]);
                }
            }
        }
    }

    protected function removeDirectory(string $directory): void
    {
        if (!is_dir($directory)) {
            return;
        }

        foreach (scandir($directory) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $directory . DIRECTORY_SEPARATOR . $item;

            if (is_dir($path)) {
                $this->removeDirectory($path);
            } else {
                @unlink($path);
            }
        }

        @rmdir($directory);
    }

    /**
     * Menjalankan LibreOffice dengan batas waktu.
     */
    protected function executeLibreOffice(
        string $cmd,
        int $timeout = 60
    ): array {
        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $process = proc_open(
            $cmd,
            $descriptors,
            $pipes
        );

        if (!is_resource($process)) {
            return [
                'success' => false,
                'output' => 'Gagal menjalankan proses LibreOffice.',
                'exit_code' => -1,
            ];
        }

        fclose($pipes[0]);

        stream_set_blocking($pipes[1], false);
        stream_set_blocking($pipes[2], false);

        $output = '';
        $errorOutput = '';
        $startTime = microtime(true);
        $exitCode = null;
        $timedOut = false;

        while (true) {
            $output .= stream_get_contents($pipes[1]);
            $errorOutput .= stream_get_contents($pipes[2]);

            $status = proc_get_status($process);

            if (!$status['running']) {
                $exitCode = $status['exitcode'];
                break;
            }

            if (microtime(true) - $startTime >= $timeout) {
                $timedOut = true;

                proc_terminate($process, 15);

                usleep(300000);

                $status = proc_get_status($process);

                if ($status['running']) {
                    proc_terminate($process, 9);
                }

                break;
            }

            usleep(100000);
        }

        $output .= stream_get_contents($pipes[1]);
        $errorOutput .= stream_get_contents($pipes[2]);

        fclose($pipes[1]);
        fclose($pipes[2]);

        $closeCode = proc_close($process);

        if ($exitCode === null || $exitCode < 0) {
            $exitCode = $closeCode;
        }

        if ($timedOut) {
            return [
                'success' => false,
                'output' => 'Konversi PDF melewati batas waktu '
                    . $timeout . ' detik.',
                'exit_code' => -1,
            ];
        }

        return [
            'success' => $exitCode === 0,
            'output' => trim($output . "\n" . $errorOutput),
            'exit_code' => $exitCode,
        ];
    }
}
