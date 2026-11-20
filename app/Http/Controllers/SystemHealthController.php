<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class SystemHealthController extends Controller
{
    public function simgos()
    {
        $url = 'https://simgos.rspkusukoharjo.com:1111/';

        try {
            $response = Http::connectTimeout(2)
                ->timeout(4)
                ->get($url);

            // HTTP response apa pun (403, 404, 500, dll)
            // tetap dianggap server berhasil dijangkau.
            return response()->json([
                'online' => true,
                'message' => 'Terhubung dengan sistem',
                'http_code' => $response->status(),
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'online' => false,
                'message' => 'Sistem Utama sedang Down',
            ]);
        }
    }
}
