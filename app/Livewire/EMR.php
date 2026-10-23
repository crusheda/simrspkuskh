<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EMR extends Component
{
    public array $list = [];

    public function mount(): void
    {
        $yearMonth = Carbon::now()->isoFormat('YYYY-MM');

        $user = Auth::user();

        $tte_pegawai = DB::table('simrspku_klaim.tanda_tangan_pegawai')
            ->where('nip', $user->NIP)
            ->whereNull('deleted_at')
            ->exists();

        // Daftar kategori dan prefix ruangan
        $kategoriRuangan = [
            1 => ['10201', '10207'],
            2 => ['10202'],
            3 => ['10203'],
            4 => ['10208'],
        ];

        // Admin mendapatkan seluruh pilihan
        if ($user->hasRole('admin')) {
            $jenisPerawatan = [
                1 => 'Rawat Jalan',
                2 => 'Rawat Darurat (Tanpa Inap)',
                3 => 'Rawat Inap & Intensif',
                4 => 'Operatif (OK)',
                5 => 'Semua Perawatan',
            ];
        } else {
            // Ambil seluruh akses ruangan aktif milik pengguna
            $aksesRuangan = DB::table('aplikasi.pengguna_akses_ruangan')
                ->where('PENGGUNA', $user->ID)
                ->where('STATUS', 1)
                ->pluck('RUANGAN')
                ->map(fn ($ruangan) => (string) $ruangan)
                ->unique()
                ->values()
                ->all();

            $punyaAksesKategori = [];

            foreach ($kategoriRuangan as $kategori => $prefixes) {
                $punyaAksesKategori[$kategori] = false;

                foreach ($aksesRuangan as $ruangan) {
                    foreach ($prefixes as $prefix) {
                        if (
                            str_starts_with($ruangan, $prefix) ||
                            str_starts_with($prefix, $ruangan)
                        ) {
                            $punyaAksesKategori[$kategori] = true;
                            break 2;
                        }
                    }
                }
            }

            $jenisPerawatan = [];

            $namaKategori = [
                1 => 'Rawat Jalan',
                2 => 'Rawat Darurat (Tanpa Inap)',
                3 => 'Rawat Inap & Intensif',
                4 => 'Operatif (OK)',
            ];

            foreach ($namaKategori as $kategori => $nama) {
                if ($punyaAksesKategori[$kategori]) {
                    $jenisPerawatan[$kategori] = $nama;
                }
            }

            // Opsi Semua Perawatan
            $aksesRuanganInduk = in_array('102', $aksesRuangan, true);

            $aksesSemuaKategori = !in_array(
                false,
                $punyaAksesKategori,
                true
            );

            if ($aksesRuanganInduk || $aksesSemuaKategori) {
                $jenisPerawatan[5] = 'Semua Perawatan';
            }

            ksort($jenisPerawatan);
        }

        $this->list = [
            'yearMonth' => $yearMonth,
            'tte_pegawai' => $tte_pegawai,
            'jenisPerawatan' => $jenisPerawatan,
        ];
    }

    public function render()
    {
        return view('livewire.emr.index')->layout('layouts.v3.app');
    }
}
