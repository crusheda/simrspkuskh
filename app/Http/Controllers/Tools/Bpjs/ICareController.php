<?php

namespace App\Http\Controllers\Tools\Bpjs;

use App\Http\Controllers\Controller;
use App\Services\BpjsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

class ICareController extends Controller
{
    protected $BpjsService;

    public function __construct(BpjsService $BpjsService)
    {
        $this->bpjs = $BpjsService;
    }

    public function getICare($RM) // $no_kartu, $kd_dokter
    {
        $getNoKartu = DB::table('master.kartu_asuransi_pasien AS kap')
                        ->select('kap.NOMOR as no_bpjs')
                        ->where('kap.NORM', $RM)
                        ->where('kap.JENIS', 2) // BPJS
                        ->first();

        if (!$getNoKartu) {
            $getNoKartu = DB::table('bpjs.peserta AS pbs')
                            ->select('pbs.noKartu as no_bpjs')
                            ->where('pbs.norm', $RM)
                            // ->where('kap.JENIS', 2) // BPJS
                            ->first();
        }

        if (!$getNoKartu) {
            return response()->json([
                'status' => false,
                'message'=> 'Data nomor kartu asuransi Pasien tidak ditemukan'
            ]);
        }

        $dokter = DB::table('penjamin_rs.dpjp as dp')
                ->join('master.dokter as dr', function ($join) {
                    $join->on('dp.DPJP_RS', '=', 'dr.ID')
                        ->where('dr.STATUS', 1);
                })
                ->join('aplikasi.pengguna as pe', function ($join) {
                    $join->on('pe.NIP', '=', 'dr.NIP')
                        ->where('pe.STATUS', 1);
                })
                ->where('pe.ID', auth()->id())
                ->where('dp.PENJAMIN', 2)
                ->where('dp.STATUS', 1)
                ->select(
                    'dp.DPJP_PENJAMIN as kd_dokter',
                    DB::raw('master.getNamaLengkapPegawai(dr.NIP) as NAMADOKTER')
                )
                ->orderByDesc('dp.TANGGAL')
                ->first();

        if (!$dokter) {
            return response()->json([
                'status' => false,
                'message'=> 'Kode DPJP BPJS untuk Dokter login saat ini tidak ditemukan'
            ]);
        }

        $url = '/wsihs/api/rs/validate';

        $retryableErrors = [
            'service unavailable',
            'the service is unavailable',
            'no healthy upstream',
            'upstream connect error',
            'connection termination',
            'connection was reset',
            'connection reset by peer',
            'recv failure',
            'ssl_read',
            'timeout',
            'timed out',
        ];

        $maxRetry = 5;
        $attempt = 0;
        $result = null;

        do {

            $attempt++;

            $result = $this->bpjs->serviceGetIcare(
                $url,
                $getNoKartu->no_bpjs,
                $dokter->kd_dokter
            );

            // sukses
            if (($result['metaData']['code'] ?? 500) == 200) {
                break;
            }

            $message = strtolower($result['metaData']['message'] ?? '');

            $isRetryable = false;

            foreach ($retryableErrors as $error) {
                if (str_contains($message, $error)) {
                    $isRetryable = true;
                    break;
                }
            }

            // jika bukan error jaringan/server BPJS, jangan retry
            if (!$isRetryable) {
                break;
            }

            if ($attempt < $maxRetry) {
                sleep(1);
            }

        } while ($attempt < $maxRetry);

        if (($result['metaData']['code'] ?? 500) != 200) {

            $message = $result['metaData']['message'] ?? 'Terjadi kesalahan pada layanan BPJS';

            $messageLower = strtolower($message);

            // Gangguan layanan BPJS
            if (
                str_contains($messageLower, 'service unavailable') ||
                str_contains($messageLower, 'the service is unavailable') ||
                str_contains($messageLower, 'no healthy upstream') ||
                str_contains($messageLower, 'upstream connect error') ||
                str_contains($messageLower, 'connection termination')
            ) {
                $message = 'Layanan iCare BPJS sedang mengalami gangguan. Silakan coba beberapa saat lagi.';
            }

            // Koneksi terputus
            elseif (
                str_contains($messageLower, 'connection was reset') ||
                str_contains($messageLower, 'connection reset by peer') ||
                str_contains($messageLower, 'recv failure') ||
                str_contains($messageLower, 'ssl_read')
            ) {
                $message = 'Koneksi ke layanan iCare BPJS terputus. Silakan coba beberapa saat lagi.';
            }

            // Timeout
            elseif (
                str_contains($messageLower, 'timed out') ||
                str_contains($messageLower, 'timeout')
            ) {
                $message = 'Koneksi ke layanan iCare BPJS timeout. Silakan coba beberapa saat lagi.';
            }

            return response()->json([
                'status' => false,
                'message' => $message,
                'raw' => $result
            ], 422);
        }

        $getDecryption = $this->bpjs->stringDecrypt($result['response']);

        if (empty($getDecryption)) {
            return response()->json([
                'status' => false,
                'message' => 'Layanan iCare BPJS sedang tidak dapat diproses. Silakan coba kembali beberapa saat lagi.',
                'raw' => $getDecryption
            ], 500);
        }

        $newUrl = json_decode($getDecryption, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'status' => false,
                'message' => 'Response iCare tidak valid',
                'raw' => $getDecryption
            ], 500);
        }

        if (
            isset($newUrl['metaData']['code']) &&
            $newUrl['metaData']['code'] != 200
        ) {
            return response()->json([
                'status' => false,
                'message' => $newUrl['metaData']['message'] ?? 'Gagal mengambil data iCare'
            ], 422);
        }

        if (empty($newUrl['url'])) {
            return response()->json([
                'status' => false,
                'message' => 'URL iCare tidak ditemukan'
            ], 422);
        }

        return response()->json([
            'status' => true,
            'url' => $newUrl['url'],
            'no_kartu' => $getNoKartu->no_bpjs
        ]);
    }
}
