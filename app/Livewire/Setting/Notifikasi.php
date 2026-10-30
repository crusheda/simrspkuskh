<?php

namespace app\Livewire\Setting;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use App\Models\simrspku_klaim\klaim_verifikasi_catatan;

class Notifikasi extends Component
{
    public function render()
    {
        $user = Auth::user();

        $isAdmin = $user->hasRole('admin');

        /*
        |--------------------------------------------------------------------------
        | UNIT PENGGUNA
        |--------------------------------------------------------------------------
        */

        $unitUser = $user->unit;

        if (!is_array($unitUser)) {
            $unitUser = [$unitUser];
        }

        $unitUser = array_map('strval', $unitUser);

        /*
        |--------------------------------------------------------------------------
        | QUERY DASAR NOTIFIKASI
        |--------------------------------------------------------------------------
        */

        $queryDasar = klaim_verifikasi_catatan::query()
            ->whereNull('deleted_at')
            ->when(!$isAdmin, function ($query) use ($unitUser) {

                $query->where(function ($query) use ($unitUser) {

                    // Notifikasi tanpa unit dapat dilihat semua pengguna.
                    $query->whereNull('unit')
                        ->orWhere('unit', '')
                        ->orWhereRaw('JSON_LENGTH(unit) = 0');

                    // Notifikasi sesuai unit pengguna.
                    foreach ($unitUser as $unit) {
                        $query->orWhereJsonContains('unit', $unit);
                    }
                });
            });

        /*
        |--------------------------------------------------------------------------
        | TOTAL NOTIFIKASI PENDING
        |--------------------------------------------------------------------------
        */

        $totalNotifikasiPending = (clone $queryDasar)
            ->where('status', 1)
            ->where('solved', 0)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | TOTAL NOTIFIKASI SELESAI
        |--------------------------------------------------------------------------
        */

        $totalNotifikasiSelesai = (clone $queryDasar)
            ->where('status', 1)
            ->where('solved', 1)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA NOTIFIKASI
        | Data diambil dari database dan ditampilkan melalui SSR.
        | Search, sorting, filter, dan pagination ditangani DataTables.
        |--------------------------------------------------------------------------
        */

        $notifikasi = $queryDasar
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
            ]);

        /*
        |--------------------------------------------------------------------------
        | DATA ROLE / UNIT
        |--------------------------------------------------------------------------
        */

        $roleNames = Role::query()
            ->pluck('name', 'id')
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | KONVERSI ID UNIT MENJADI NAMA ROLE
        |--------------------------------------------------------------------------
        */

        $notifikasi->transform(function ($item) use ($roleNames) {

            $unit = $item->unit;

            if (is_string($unit)) {
                $unit = json_decode($unit, true);
            }

            if (!is_array($unit)) {
                $unit = [];
            }

            $item->unitNames = collect($unit)
                ->map(function ($id) use ($roleNames) {
                    return $roleNames[(string) $id]
                        ?? $roleNames[$id]
                        ?? null;
                })
                ->filter()
                ->values()
                ->all();

            return $item;
        });

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view('pages.v3.setting.notifikasi', [
            'notifikasi' => $notifikasi,
            'totalNotifikasiPending' => $totalNotifikasiPending,
            'totalNotifikasiSelesai' => $totalNotifikasiSelesai,
            'roleNames' => $roleNames,
        ])->layout('layouts.v3.app');
    }
}
