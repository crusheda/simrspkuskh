<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotifikasiController extends Controller
{
    /**
     * Menampilkan notifikasi catatan verifikasi klaim
     * untuk user yang sedang login.
     */
    public function notifikasiKlaim(Request $request)
    {
        $userId = auth()->id();

        $data = DB::table('simrspku_klaim.klaim_verifikasi_catatan')
            // ->where('user', $userId)
            ->where('solved', 0)
            ->whereNull('deleted_at')
            ->orderByDesc('updated_at')
            ->limit(20)
            ->get([
                'id',
                'nomor',
                'user',
                'deskripsi',
                'status',
                'solved',
                'created_at',
                'updated_at',
            ]);

        $total = DB::table('simrspku_klaim.klaim_verifikasi_catatan')
            // ->where('user', $userId)
            ->where('solved', 0)
            ->whereNull('deleted_at')
            ->count();

        $data = $data->map(function ($item) {
            return [
                'id'         => $item->id,
                'nomor'      => $item->nomor,
                'deskripsi'  => strip_tags($item->deskripsi),
                'created_at' => $item->updated_at,
                'url'        => url('/v2/emr/' . $item->nomor),
            ];
        });

        return response()->json([
            'status' => true,
            'total'  => $total,
            'data'   => $data,
        ]);
    }
}
