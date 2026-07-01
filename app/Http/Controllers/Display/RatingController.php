<?php

namespace App\Http\Controllers\Display;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\rating;
use Carbon\Carbon;

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

    function laporan($bulan)
    {
        if ($bulan == 0) {
            $data = rating::all();
            $nama_bulan = 'Semua Bulan';
        } else {
            $data = rating::whereMonth('created_at', $bulan)->get();
            $nama_bulan = date('F', mktime(0, 0, 0, $bulan, 10));
        }

        // print_r($data);
        // die();

        $pdf = Pdf::loadView('pages.display.rating.pdf', [
            'rating' => $data,
            'bulan' => $nama_bulan
        ])->setPaper('F4','portrait');

        $now = Carbon::now()->locale('id')->format('His');

        return $pdf->download("rekap_rating_pkuskh_$now.pdf");
    }
}
