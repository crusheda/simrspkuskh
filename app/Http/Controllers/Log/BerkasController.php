<?php

namespace App\Http\Controllers\Log;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\simrspku_klaim\klaim_verifikasi;
use App\Models\simrspku_klaim\klaim_file;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PHPJasper\PHPJasper;
use setasign\Fpdi\Fpdi;
use Carbon\Carbon;
use Auth, Storage;

class BerkasController extends Controller
{
    function index()
    {
        if (auth()->user()->can('log_berkas')) {
            return view('pages.log.berkas.index');
        }

        abort(403, 'Anda tidak memiliki izin untuk melihat halaman ini.');
    }

    function table()
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $that = $this;

        $show = klaim_file::select('*')
            ->whereNull('deleted_at')
            ->where('status', 1)
            ->orderBy('updated_at', 'DESC')
            ->get()
            ->map(function ($item) use ($that) {

                $path = storage_path('app/public/' . $item->filename);

                if (File::exists($path)) {
                    try {
                        $bytes = File::size($path);
                        $item->size = $that->formatSize($bytes);
                    } catch (\Throwable $e) {
                        $item->size = 'Tidak bisa membaca ukuran';
                    }
                } else {
                    $item->size = 'File tidak ditemukan!';
                }

                return $item;
            });

        $now = Carbon::now()->translatedFormat('l, d F Y H:i:s');

        $storagePath = storage_path();

        // ===============================
        // INFO DISK (SAFE)
        // ===============================
        $total = $free = $used = 0;

        try {
            if (is_readable($storagePath)) {
                $total = disk_total_space($storagePath);
                $free  = disk_free_space($storagePath);
                $used  = $total - $free;
            }
        } catch (\Throwable $e) {
            $total = $free = $used = 0;
        }

        // ===============================
        // SIZE STORAGE PUBLIC (SAFE)
        // ===============================
        $path_storage = storage_path('app/public');
        $size_storage = 0;

        try {
            $size_storage = $this->folderSize($path_storage);
        } catch (\Throwable $e) {
            $size_storage = 0;
        }

        $data = [
            'show'         => $show,
            'now'          => $now,
            'size_storage' => $this->formatSize($size_storage),
            'disk_total'   => $this->formatSize($total),
            'disk_used'    => $this->formatSize($used),
            'disk_free'    => $this->formatSize($free),
        ];

        return response()->json($data, 200);
    }

    function show($id)
    {
        $getFile = klaim_file::where('id', $id)->first();
        $output = storage_path('app/public/' . $getFile->filename);

        if (!file_exists($output)) {
            abort(404, 'File tidak ditemukan di Storage');
        }

        return response()->file($output, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    function delete($id)
    {
        $time = Carbon::now()->isoFormat('YYYY-MM-DD HH:mm:ss');
        $file = klaim_file::where('id', $id)->first();

        if (!$file) {
            abort(404, 'File tidak ditemukan');
        }

        $file->status = 0;
        $file->user_deleted = Auth::user()->ID;
        $file->save();
        $file->delete();

        return response()->json($time, 200);
    }

    /**
     * ===============================
     * SAFE FOLDER SIZE (NO allFiles)
     * ===============================
     */
    function folderSize($dir)
    {
        $size = 0;

        if (!is_dir($dir) || !is_readable($dir)) {
            return 0;
        }

        foreach (scandir($dir) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            $path = $dir . DIRECTORY_SEPARATOR . $file;

            if (is_file($path)) {
                $size += filesize($path);
            } elseif (is_dir($path)) {
                $size += $this->folderSize($path);
            }
        }

        return $size;
    }

    function formatSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}
