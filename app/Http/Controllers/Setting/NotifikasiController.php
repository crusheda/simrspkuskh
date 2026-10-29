<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\simrspku_klaim\klaim_verifikasi_catatan;
use Auth;

class NotifikasiController extends Controller
{
    /**
     * Menampilkan notifikasi catatan verifikasi klaim
     * untuk user yang sedang login.
     */
    public function notifikasiKlaim(Request $request)
    {
        $unitUser = auth()->user()->unit;

        if (!is_array($unitUser)) {
            $unitUser = [$unitUser];
        }

        $unitUser = array_map('strval', $unitUser);

        $data = klaim_verifikasi_catatan::where('solved', 0)
            ->whereNull('deleted_at')
            ->orderByDesc('updated_at')
            ->get([
                'id',
                'nomor',
                'unit',
                'user',
                'deskripsi',
                'status',
                'solved',
                'created_at',
                'updated_at',
            ])
            ->filter(function ($item) use ($unitUser) {

                /*
                * Unit kosong/null = tampil ke semua user
                */
                if (empty($item->unit)) {
                    return true;
                }

                /*
                * Karena model menggunakan $casts:
                * 'unit' => 'array'
                *
                * maka $item->unit sudah berupa array.
                */
                if (!is_array($item->unit)) {
                    return false;
                }

                $unit = array_map('strval', $item->unit);

                /*
                * Tampilkan jika minimal ada 1 unit
                * yang sama dengan unit user.
                */
                return !empty(array_intersect($unitUser, $unit));
            })
            ->values();

        $total = $data->count();

        $data = $data
            ->take(20)
            ->map(function ($item) {
                return [
                    'id'         => $item->id,
                    'nomor'      => $item->nomor,
                    'unit'       => $item->unit,
                    'deskripsi'  => strip_tags($item->deskripsi),
                    'created_at' => $item->updated_at,
                    // 'url'        => url('/v2/emr/' . $item->nomor),
                    'url'        => url('/v2/emr/' . $item->nomor),
                ];
            })
            ->values();

        return response()->json([
            'status' => true,
            'total'  => $total,
            'data'   => $data,
        ]);
    }

    public function notifikasiKlaimV3(Request $request)
    {
        $unitUser = auth()->user()->unit;

        if (!is_array($unitUser)) {
            $unitUser = [$unitUser];
        }

        $unitUser = array_map('strval', $unitUser);

        $data = klaim_verifikasi_catatan::where('solved', 0)
            ->whereNull('deleted_at')
            ->orderByDesc('updated_at')
            ->get([
                'id',
                'nomor',
                'unit',
                'user',
                'deskripsi',
                'status',
                'solved',
                'created_at',
                'updated_at',
            ])
            ->filter(function ($item) use ($unitUser) {

                /*
                * Unit kosong/null = tampil ke semua user
                */
                if (empty($item->unit)) {
                    return true;
                }

                /*
                * Karena model menggunakan $casts:
                * 'unit' => 'array'
                *
                * maka $item->unit sudah berupa array.
                */
                if (!is_array($item->unit)) {
                    return false;
                }

                $unit = array_map('strval', $item->unit);

                /*
                * Tampilkan jika minimal ada 1 unit
                * yang sama dengan unit user.
                */
                return !empty(array_intersect($unitUser, $unit));
            })
            ->values();

        $total = $data->count();

        $data = $data
            ->take(20)
            ->map(function ($item) {
                return [
                    'id'         => $item->id,
                    'nomor'      => $item->nomor,
                    'unit'       => $item->unit,
                    'deskripsi'  => strip_tags($item->deskripsi),
                    'created_at' => $item->updated_at,
                    // URL detail kunjungan untuk iframe workspace SIRMED V3.
                    'url' => url(
                        '/v3/emr/' . rawurlencode((string) $item->nomor)
                        . '?workspace=1'
                    ),
                ];
            })
            ->values();

        return response()->json([
            'status' => true,
            'total'  => $total,
            'data'   => $data,
        ]);
    }

    public function index()
    {
        return view('pages.v2.setting.notifikasi');
    }

    public function getData(Request $request)
    {
        $status = $request->get('status');

        $user = Auth::user();
        $isAdmin = $user->hasRole('admin');

        $unitUser = $user->unit;

        if (!is_array($unitUser)) {
            $unitUser = [$unitUser];
        }

        $unitUser = array_map('strval', $unitUser);

                // Query dasar untuk menghitung total notifikasi
        $queryTotal = klaim_verifikasi_catatan::whereNull('deleted_at')
            ->when(!$isAdmin, function ($query) use ($unitUser) {
                $query->where(function ($query) use ($unitUser) {
                    // Unit kosong/null dapat dilihat semua user
                    $query->whereNull('unit')
                        ->orWhere('unit', '')
                        ->orWhereRaw("JSON_LENGTH(unit) = 0");

                    // Unit yang sesuai dengan unit user
                    foreach ($unitUser as $unit) {
                        $query->orWhereJsonContains('unit', $unit);
                    }
                });
            });

        // Total pending: status = 1 dan solved = 0
        $totalNotifikasiPending = (clone $queryTotal)
            ->where('status', 1)
            ->where('solved', 0)
            ->count();

        // Total selesai: status = 1 dan solved = 1
        $totalNotifikasiSelesai = (clone $queryTotal)
            ->where('status', 1)
            ->where('solved', 1)
            ->count();

        $data = klaim_verifikasi_catatan::whereNull('deleted_at')
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                if ((string) $status === '0') {
                    // Pending: status = 1 dan solved = 0
                    $query->where('status', 1)
                        ->where('solved', 0);
                } elseif ((string) $status === '1') {
                    // Selesai: status = 1 dan solved = 1
                    $query->where('status', 1)
                        ->where('solved', 1);
                }
            })
            ->orderByDesc('updated_at')
            ->get([
                'id',
                'nomor',
                'unit',
                'user',
                'deskripsi',
                'status',
                'solved',
                'created_at',
                'updated_at',
            ])
            ->filter(function ($item) use ($unitUser, $isAdmin) {

                // Admin dapat melihat semua data
                if ($isAdmin) {
                    return true;
                }

                // Unit kosong/null = bisa dilihat semua user
                if (empty($item->unit)) {
                    return true;
                }

                $unit = $item->unit;

                // Jika belum otomatis di-cast oleh model
                if (is_string($unit)) {
                    $unit = json_decode($unit, true);
                }

                if (!is_array($unit)) {
                    return false;
                }

                $unit = array_map('strval', $unit);

                // User cukup memiliki salah satu unit tujuan
                return !empty(array_intersect($unitUser, $unit));
            })
            ->values()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nomor' => $item->nomor,
                    'unit' => $item->unit,
                    'deskripsi' => strip_tags($item->deskripsi),
                    'status' => $item->status,
                    'solved' => $item->solved,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ];
            });

        return response()->json([
            'status' => true,
            'data' => $data,
            'totalNotifikasiPending' => $totalNotifikasiPending,
            'totalNotifikasiSelesai' => $totalNotifikasiSelesai,
        ]);
    }

    public function showDetail(Request $request, $id)
    {
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin');

        $unitUser = $user->unit;

        if (!is_array($unitUser)) {
            $unitUser = [$unitUser];
        }

        $unitUser = array_map('strval', $unitUser);

        $item = klaim_verifikasi_catatan::where('id', $id)
            ->whereNull('deleted_at')
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Validasi hak akses berdasarkan unit
        |--------------------------------------------------------------------------
        */

        // Admin dapat melihat semua data
        if (!$isAdmin && !empty($item->unit)) {

            $unit = $item->unit;

            if (is_string($unit)) {
                $unit = json_decode($unit, true);
            }

            if (!is_array($unit)) {
                abort(403, 'Anda tidak memiliki akses ke notifikasi ini.');
            }

            $unit = array_map('strval', $unit);

            if (empty(array_intersect($unitUser, $unit))) {
                abort(403, 'Anda tidak memiliki akses ke notifikasi ini.');
            }
        }

        $pasien = DB::table('pendaftaran.kunjungan AS pk')
            ->leftJoin('pendaftaran.pendaftaran AS pd', 'pd.NOMOR', '=', 'pk.NOPEN')
            ->leftJoin('master.pasien AS p', 'p.NORM', '=', 'pd.NORM')
            ->leftJoin('master.ruangan AS ru', 'ru.ID', '=', 'pk.RUANGAN')
            ->leftJoin('master.dokter AS dok', 'dok.ID', '=', 'pk.DPJP')
            ->select(
                'p.NORM AS NORM',
                'pd.TANGGAL as TGLPENDAFTARAN',
                DB::raw('master.getNamaLengkapPegawai(dok.NIP) AS NAMADOKTER'),
                DB::raw('master.getCariUmur(now(), (p.TANGGAL_LAHIR)) AS UMURPASIEN'),
                DB::raw('master.getNamaLengkap(p.NORM) AS NAMAPASIEN'),
                DB::raw('master.getAlamatPasienCustom(p.NORM) AS ALAMATPASIEN'),
                'ru.DESKRIPSI AS NAMARUANGAN',
                'p.TANGGAL_LAHIR AS TGLLAHIRPASIEN',
            )
            ->where('pk.NOMOR', $item->nomor)
            ->first();

        return response()->json([
            'status' => true,
            'data' => [
                'pasien' => $pasien,
                'id' => $item->id,
                'nomor' => $item->nomor,
                'unit' => $item->unit,
                'deskripsi' => $item->deskripsi,
                'status' => $item->status,
                'solved' => $item->solved,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ],
        ]);
    }
}
