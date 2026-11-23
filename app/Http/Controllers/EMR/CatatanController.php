<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatatanController
{
    /**
     * Referensi ruangan catatan
     */
    public function referensiRuangan()
    {
        return DB::table('master.ruangan')
            ->where('JENIS', 5)
            ->where('STATUS', 1)
            ->orderBy('DESKRIPSI')
            ->select('ID', 'DESKRIPSI')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    */
    private function resolveCatatanVisit(string $kunjungan): ?object
    {
        return DB::table('pendaftaran.kunjungan as pk')
            ->join('pendaftaran.pendaftaran as pp', 'pp.NOMOR', '=', 'pk.NOPEN')
            ->leftJoin('master.ruangan as ru', 'ru.ID', '=', 'pk.RUANGAN')
            ->where('pk.NOMOR', $kunjungan)
            ->where('pk.STATUS', '!=', 0)
            ->where('pp.STATUS', '!=', 0)
            ->select([
                'pk.NOMOR as KUNJUNGAN',
                'pk.RUANGAN',
                'ru.DESKRIPSI as NAMARUANGAN',
                'pp.NORM as RM',
            ])
            ->first();
    }

    private function currentCatatanUserId(): int
    {
        return (int) auth()->user()->ID;
    }

    private function currentCatatanRoles(): array
    {
        $user = auth()->user();

        if (!$user) {
            return [];
        }

        $roles = [];

        // Nama role
        if (method_exists($user, 'getRoleNames')) {
            $roles = array_merge(
                $roles,
                $user->getRoleNames()->toArray()
            );
        }

        // ID role dari relasi Spatie Permission
        if (method_exists($user, 'roles')) {
            $roles = array_merge(
                $roles,
                $user->roles()->pluck('id')->map(fn ($id) => (string) $id)->toArray()
            );
        }

        return array_unique($roles);
    }

    public function getCatatanEMR(Request $request, string $kunjungan)
    {
        $visit = $this->resolveCatatanVisit($kunjungan);

        if (!$visit) {
            return response()->json([
                'success' => false,
                'message' => 'Kunjungan tidak ditemukan.',
            ], 404);
        }

        $userId = $this->currentCatatanUserId();
        $roles = $this->currentCatatanRoles();

        /*
        |--------------------------------------------------------------------------
        | CATATAN INTERNAL
        |--------------------------------------------------------------------------
        | RM harus sama.
        | Ruangan NULL = global untuk RM tersebut.
        | Ruangan terisi = berlaku pada seluruh kunjungan RM di ruangan itu.
        |--------------------------------------------------------------------------
        */

        $internal = DB::table('simrspku_pengkajian.catatan as c')
            ->leftJoin('master.ruangan as ru', 'ru.ID', '=', 'c.RUANGAN')
            ->leftJoin('aplikasi.pengguna as u', 'u.ID', '=', 'c.USER')
            ->where('c.RM', $visit->RM)
            ->where('c.STATUS', '!=', 0)
            ->whereNull('c.DELETED_AT')
            ->where(function ($query) use ($visit) {
                $query->whereNull('c.RUANGAN')
                    ->orWhere('c.RUANGAN', $visit->RUANGAN);
            })
            ->select([
                'c.ID',
                'c.KUNJUNGAN',
                'c.RM',
                'c.RUANGAN',
                'ru.DESKRIPSI as NAMARUANGAN',
                'c.DESKRIPSI',
                'c.STATUS',
                'c.USER',
                'c.CREATED_AT',
                'c.UPDATED_AT',
                'u.NAMA as NAMAUSER',
            ])
            ->orderByRaw('CASE WHEN c.STATUS = 1 THEN 0 ELSE 1 END')
            ->orderByDesc('c.CREATED_AT')
            ->get()
            ->map(function ($item) use ($userId) {
                $item->SOURCE = 'internal';
                $item->CAN_EDIT = (int) $item->USER === $userId
                    && (int) $item->STATUS === 1;
                $item->IS_SOLVED = (int) $item->STATUS === 2;

                return $item;
            });

        /*
        |--------------------------------------------------------------------------
        | CATATAN ASURANSI
        |--------------------------------------------------------------------------
        | Hanya nomor kunjungan yang sama.
        | Unit NULL / [] / kosong = tampil ke semua user.
        | Unit berisi role = tampil kepada role yang sesuai.
        |--------------------------------------------------------------------------
        */

        $insuranceQuery = DB::table('simrspku_klaim.klaim_verifikasi_catatan as c')
            ->leftJoin('aplikasi.pengguna as u', 'u.ID', '=', 'c.user')
            ->where('c.nomor', $kunjungan)
            ->where('c.status', 1)
            ->whereNull('c.deleted_at')
            ->select([
                'c.id',
                'c.nomor',
                'c.unit',
                'c.user',
                'c.deskripsi',
                'c.status',
                'c.solved',
                'c.deskripsi_solved',
                'c.user_solved',
                'c.created_at',
                'c.updated_at',
                'u.NAMA as NAMAUSER',
            ])
            ->orderByRaw('CASE WHEN c.solved = 0 THEN 0 ELSE 1 END')
            ->orderByDesc('c.created_at');

        $insurance = $insuranceQuery->get()
            ->filter(function ($item) use ($roles) {

                $unit = $item->unit;

                // NULL atau kosong = dapat dilihat seluruh user
                if ($unit === null || $unit === '') {
                    return true;
                }

                if (is_string($unit)) {
                    $unit = json_decode($unit, true);
                }

                if (!is_array($unit) || empty($unit)) {
                    return true;
                }

                // JSON array berisi nama roles
                return count(array_intersect($roles, $unit)) > 0;
            })
            ->values()
            ->map(function ($item) {
                $item->SOURCE = 'insurance';
                $item->CAN_EDIT = false;
                $item->IS_SOLVED = (int) $item->solved === 1;

                return $item;
            });

        $total = $internal->count() + $insurance->count();

        $unsolvedInternal = $internal->where('STATUS', 1)->count();

        $unsolvedInsurance = $insurance->filter(function ($item) {
            return (int) ($item->status ?? 0) !== 1
                || (int) ($item->solved ?? 0) !== 1;
        })->count();

        return response()->json([
            'success' => true,
            'data' => [
                'visit' => $visit,
                'internal' => $internal,
                'insurance' => $insurance,
                'total' => $total,
                'unsolved' => $unsolvedInternal + $unsolvedInsurance,
                'solved' => $total - ($unsolvedInternal + $unsolvedInsurance),
            ],
        ]);
    }

    public function storeCatatanInternal(Request $request, string $kunjungan)
    {
        $request->validate([
            'deskripsi' => ['required', 'string', 'min:3'],
            // 'ruangan' => ['nullable', 'integer'],
            'ruangan' => ['nullable', 'string', 'max:10'],
        ]);

        $visit = $this->resolveCatatanVisit($kunjungan);

        if (!$visit) {
            return response()->json([
                'success' => false,
                'message' => 'Kunjungan tidak ditemukan.',
            ], 404);
        }

        $id = DB::table('simrspku_pengkajian.catatan')->insertGetId([
            'KUNJUNGAN' => $kunjungan,
            'RM' => $visit->RM,
            'RUANGAN' => $request->ruangan ?: null,
            'DESKRIPSI' => $request->deskripsi,
            'STATUS' => 1,
            'USER' => $this->currentCatatanUserId(),
            'CREATED_AT' => now(),
            'UPDATED_AT' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Catatan berhasil ditambahkan.',
            'id' => $id,
        ]);
    }

    public function updateCatatanInternal(Request $request, int $id)
    {
        $request->validate([
            'deskripsi' => ['required', 'string', 'min:3'],
            'ruangan' => ['nullable', 'integer'],
        ]);

        $userId = $this->currentCatatanUserId();

        $catatan = DB::table('simrspku_pengkajian.catatan')
            ->where('ID', $id)
            ->where('USER', $userId)
            ->where('STATUS', 1)
            ->whereNull('DELETED_AT')
            ->first();

        if (!$catatan) {
            return response()->json([
                'success' => false,
                'message' => 'Catatan tidak ditemukan atau bukan milik Anda.',
            ], 403);
        }

        DB::table('simrspku_pengkajian.catatan')
            ->where('ID', $id)
            ->where('USER', $userId)
            ->update([
                'DESKRIPSI' => $request->deskripsi,
                'RUANGAN' => $request->ruangan ?: null,
                'UPDATED_AT' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Catatan berhasil diperbarui.',
        ]);
    }

    public function deleteCatatanInternal(int $id)
    {
        $userId = $this->currentCatatanUserId();

        $updated = DB::table('simrspku_pengkajian.catatan')
            ->where('ID', $id)
            ->where('USER', $userId)
            ->where('STATUS', 1)
            ->whereNull('DELETED_AT')
            ->update([
                'STATUS' => 0,
                'DELETED_AT' => now(),
                'UPDATED_AT' => now(),
            ]);

        if (!$updated) {
            return response()->json([
                'success' => false,
                'message' => 'Catatan tidak ditemukan atau bukan milik Anda.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Catatan berhasil dihapus.',
        ]);
    }

    public function solveCatatanInternal(Request $request, $id)
    {
        try {
            $catatan = DB::table('simrspku_pengkajian.catatan')
                ->where('ID', $id)
                ->whereNull('DELETED_AT')
                ->first();

            if (!$catatan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Catatan tidak ditemukan.',
                ], 404);
            }

            if ((int) $catatan->STATUS === 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Catatan sudah diselesaikan sebelumnya.',
                ], 422);
            }

            if ((int) $catatan->STATUS !== 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Catatan tidak dalam status aktif.',
                ], 422);
            }

            DB::table('simrspku_pengkajian.catatan')
                ->where('ID', $id)
                ->where('STATUS', 1)
                ->whereNull('DELETED_AT')
                ->update([
                    'STATUS'            => 2,
                    'DESKRIPSI_SOLVED'  => $request->deskripsi_solved,
                    'UPDATED_AT'        => now(),
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Catatan internal berhasil diselesaikan.',
                'data' => [
                    'ID'         => $catatan->ID,
                    'STATUS'     => 2,
                    'UPDATED_AT' => now()->toDateTimeString(),
                ],
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyelesaikan catatan internal.',
            ], 500);
        }
    }

    public function solveCatatanAsuransi(Request $request, int $id)
    {
        $request->validate([
            'deskripsi_solved' => ['nullable', 'string', 'max:10000'],
        ]);

        $catatan = DB::table('simrspku_klaim.klaim_verifikasi_catatan')
            ->where('id', $id)
            ->where('status', 1)
            ->where('solved', 0)
            ->whereNull('deleted_at')
            ->first();

        if (!$catatan) {
            return response()->json([
                'success' => false,
                'message' => 'Catatan tidak ditemukan atau sudah diselesaikan.',
            ], 404);
        }

        // Validasi unit/roles
        $roles = $this->currentCatatanRoles();
        $unit = $catatan->unit;

        if ($unit !== null && $unit !== '') {
            if (is_string($unit)) {
                $unit = json_decode($unit, true);
            }

            if (is_array($unit) && !empty($unit)
                && count(array_intersect($roles, $unit)) === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk menyelesaikan catatan ini.',
                ], 403);
            }
        }

        DB::table('simrspku_klaim.klaim_verifikasi_catatan')
            ->where('id', $id)
            ->where('solved', 0)
            ->update([
                'solved' => 1,
                'deskripsi_solved' => $request->deskripsi_solved,
                'user_solved' => $this->currentCatatanUserId(),
                'updated_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Catatan asuransi berhasil diselesaikan.',
        ]);
    }
}
