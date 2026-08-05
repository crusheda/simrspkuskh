<?php

namespace App\Http\Controllers\Display;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\rating;
use Carbon\Carbon;
use ZipArchive;
use setasign\Fpdi\Fpdi;

class RatingController extends Controller
{
    public function index()
    {
        $rating = rating::selectRaw('rating, count(*) as total')
            ->groupBy('rating')
            ->pluck('total','rating');

        return view('pages.display.rating.index', compact('rating'));
    }

    public function store(Request $request)
    {
        // $ip = $request->ip();

        // $cek = rating::where('ip',$ip)->first();

        // if($cek){
        //     return response()->json([
        //         'status'=>false
        //     ]);
        // }

        rating::create([
            'rating' => $request->rating,
            'ip' => $request->ip()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Rating berhasil disimpan'
        ],200);

    }

    public function laporan($bulan)
    {
        $bulan = (int) $bulan;

        if ($bulan == 0) {
            $query = rating::query()
                ->whereYear('created_at', now()->year);

            $nama_bulan = 'Semua Bulan';
        } else {
            $query = rating::query()
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', $bulan);

            $nama_bulan = Carbon::create()
                ->month($bulan)
                ->locale('id')
                ->translatedFormat('F');
        }

        $total = $query->count();

        if ($total === 0) {
            abort(404, 'Data rating tidak ditemukan.');
        }

        // Maksimal data yang diproses DomPDF dalam sekali proses
        $perChunk = 1500;

        $jumlahFile = (int) ceil($total / $perChunk);

        // Folder temporary
        $tempDir = storage_path('app/temp-rating');

        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0775, true);
        }

        /*
        |--------------------------------------------------------------------------
        | Generate PDF per bagian
        |--------------------------------------------------------------------------
        */

        $pdfFiles = [];

        for ($page = 0; $page < $jumlahFile; $page++) {

            $data = (clone $query)
                ->orderBy('created_at')
                ->orderBy('id')
                ->offset($page * $perChunk)
                ->limit($perChunk)
                ->get();

            $pdf = Pdf::loadView('pages.display.rating.pdf', [
                'rating'       => $data,
                'bulan'        => $nama_bulan,
                'bagian'       => $page + 1,
                'total_bagian' => $jumlahFile,
                'nomor_awal'   => ($page * $perChunk) + 1,
            ])->setPaper('F4', 'portrait');

            $pdfName = 'rating_bagian_' . ($page + 1) . '.pdf';

            $pdfPath = $tempDir . '/' . $pdfName;

            file_put_contents(
                $pdfPath,
                $pdf->output()
            );

            $pdfFiles[] = $pdfPath;

            // Bersihkan memory
            unset($pdf);
            unset($data);

            gc_collect_cycles();
        }

        /*
        |--------------------------------------------------------------------------
        | Jika hanya 1 bagian, langsung gunakan PDF tersebut
        |--------------------------------------------------------------------------
        */

        if ($jumlahFile === 1) {

            $finalName = 'rekap_rating_pkuskh_' . now()->format('YmdHis') . '.pdf';

            $finalPath = $tempDir . '/' . $finalName;

            rename($pdfFiles[0], $finalPath);

            return response()
                ->download($finalPath)
                ->deleteFileAfterSend(true);
        }

        /*
        |--------------------------------------------------------------------------
        | Gabungkan semua PDF menggunakan FPDI
        |--------------------------------------------------------------------------
        */

        $fpdi = new Fpdi();

        foreach ($pdfFiles as $pdfPath) {

            $pageCount = $fpdi->setSourceFile($pdfPath);

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {

                $template = $fpdi->importPage($pageNo);

                $size = $fpdi->getTemplateSize($template);

                $fpdi->AddPage(
                    $size['orientation'],
                    [
                        $size['width'],
                        $size['height']
                    ]
                );

                $fpdi->useTemplate($template);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan PDF final
        |--------------------------------------------------------------------------
        */

        $finalName = 'rekap_rating_pkuskh_' . now()->format('YmdHis') . '.pdf';

        $finalPath = $tempDir . '/' . $finalName;

        $fpdi->Output('F', $finalPath);

        /*
        |--------------------------------------------------------------------------
        | Hapus PDF bagian
        |--------------------------------------------------------------------------
        */

        foreach ($pdfFiles as $pdfPath) {

            if (file_exists($pdfPath)) {
                unlink($pdfPath);
            }
        }

        unset($fpdi);

        gc_collect_cycles();

        /*
        |--------------------------------------------------------------------------
        | Download PDF final
        |--------------------------------------------------------------------------
        */

        return response()
            ->download($finalPath)
            ->deleteFileAfterSend(true);
    }
}
