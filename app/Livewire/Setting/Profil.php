<?php

namespace App\Livewire\Setting;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Profil extends Component
{
    public $pegawai;
    public $user;
    public $role;
    public $lastPasswordUpdated;

    public array $ttds = [];

    public bool $editingTtd = false;
    public string $activeTab = 'profil-pane';

    /**
     * Mount: data profil dirender dari server (SSR).
     */
    public function mount(): void
    {
        $this->user = Auth::user();

        $this->role = $this->user->getRoleNames()->first() ?? 'USER';

        $this->lastPasswordUpdated = $this->user->TERAKHIR_UBAH_PASSWOD
            ? Carbon::parse($this->user->TERAKHIR_UBAH_PASSWOD)
                ->translatedFormat('d F Y, H:i')
            : null;

        $this->loadProfil();
        $this->loadTtd();

        $this->activeTab = session()->get('profil_active_tab', 'profil-pane');

        if (!in_array($this->activeTab, [
            'profil-pane',
            'ttd-pane',
            'password-pane',
        ])) {
            $this->activeTab = 'profil-pane';
        }
    }

    public function setTab(string $tab): void
    {
        $allowedTabs = [
            'profil-pane',
            'ttd-pane',
            'password-pane',
        ];

        if (!in_array($tab, $allowedTabs)) {
            return;
        }

        $this->activeTab = $tab;

        // Simpan tab aktif di session Laravel
        session()->put('profil_active_tab', $tab);
    }

    /**
     * Mengambil data identitas pegawai.
     */
    protected function loadProfil(): void
    {
        $this->pegawai = DB::table('aplikasi.pengguna AS ap')
            ->leftJoin('master.pegawai AS pg', 'pg.NIP', '=', 'ap.NIP')
            ->leftJoin('pegawai.kontak_pegawai AS kp', function ($join) {
                $join->on('kp.NIP', '=', 'pg.NIP')
                    ->where('kp.STATUS', '=', 1);
            })
            ->leftJoin('master.referensi AS ref', function ($join) {
                $join->on('ref.ID', '=', 'kp.JENIS')
                    ->where('ref.JENIS', '=', 8);
            })
            ->select(
                'pg.*',
                'kp.NOMOR AS NOHP',
                'ref.DESKRIPSI AS JENISNOHP',
                DB::raw('master.getNamaLengkapPegawai(pg.NIP) AS NAMALENGKAP'),
                'pg.nama AS NAMA'
            )
            ->where('ap.ID', $this->user->ID)
            ->where('pg.STATUS', 1)
            ->first();

        if (!$this->pegawai) {
            abort(404, 'Data pegawai tidak ditemukan.');
        }
    }

    /**
     * Memuat daftar tanda tangan pegawai.
     */
    public function loadTtd(): void
    {
        $nip = (string) $this->user->NIP;

        $rows = DB::table('simrspku_klaim.tanda_tangan_pegawai')
            ->where('nip', $nip)
            ->whereNull('deleted_at')
            ->where('status', 1)
            ->orderBy('queue')
            ->get();

        $this->ttds = $rows->map(function ($row) {
            return [
                'queue' => (int) $row->queue,
                'signature_path' => $row->signature_path,
                'signature_url' => Storage::disk('public')
                    ->url($row->signature_path),
                'updated_at' => $row->updated_at,
            ];
        })->values()->toArray();
    }

    /**
     * Refresh TTE dari database.
     */
    public function refreshTtd(): void
    {
        $this->loadTtd();

        $this->editingTtd = false;

        $this->dispatch(
            'profil-ttd-refreshed',
            message: 'Data tanda tangan berhasil diperbarui.'
        );
    }

    /**
     * Membuka mode enrollment / edit TTE.
     */
    public function editTtd(): void
    {
        $this->editingTtd = true;
    }

    /**
     * Membatalkan mode enrollment.
     */
    public function cancelEditTtd(): void
    {
        $this->editingTtd = false;
    }

    /**
     * Menyimpan variasi tanda tangan.
     *
     * $signatures format:
     * [
     *   ['queue' => 1, 'image' => 'data:image/png;base64,...'],
     *   ...
     * ]
     */
    public function storeTtd(array $signatures): array
    {
        $this->validate([
            'user.NIP' => ['required'],
        ]);

        if (count($signatures) < 1 || count($signatures) > 6) {
            return [
                'success' => false,
                'message' => 'Minimal 1 dan maksimal 6 variasi TTE.',
            ];
        }

        $nip = (string) Auth::user()->NIP;

        // Validasi kembali NIP dan data pegawai yang sedang login.
        $pegawai = DB::table('aplikasi.pengguna AS ap')
            ->leftJoin('master.pegawai AS pg', 'pg.NIP', '=', 'ap.NIP')
            ->select(
                'pg.*',
                DB::raw('master.getNamaLengkapPegawai(pg.NIP) AS NAMALENGKAP')
            )
            ->where('ap.ID', Auth::user()->ID)
            ->where('ap.NIP', $nip)
            ->where('pg.STATUS', 1)
            ->first();

        if (!$pegawai) {
            return [
                'success' => false,
                'message' => 'Data pegawai tidak ditemukan.',
            ];
        }

        $namaPegawai = $pegawai->NAMALENGKAP ?: $pegawai->NAMA;

        $queues = [];
        $decodedSignatures = [];

        // Validasi payload sebelum mulai menyimpan file.
        foreach ($signatures as $signature) {
            $queue = filter_var(
                $signature['queue'] ?? null,
                FILTER_VALIDATE_INT
            );

            if (!$queue || $queue < 1 || $queue > 6) {
                return [
                    'success' => false,
                    'message' => 'Nomor variasi TTE harus antara 1 sampai 6.',
                ];
            }

            if (in_array($queue, $queues, true)) {
                return [
                    'success' => false,
                    'message' => 'Terdapat variasi TTE yang duplikat.',
                ];
            }

            $queues[] = $queue;

            $image = $signature['image'] ?? '';

            if (!preg_match(
                '/^data:image\/png;base64,([A-Za-z0-9+\/=]+)$/',
                $image,
                $matches
            )) {
                return [
                    'success' => false,
                    'message' => "Format gambar variasi {$queue} tidak valid.",
                ];
            }

            $imageData = base64_decode($matches[1], true);

            if (!$imageData) {
                return [
                    'success' => false,
                    'message' => "Data gambar variasi {$queue} tidak valid.",
                ];
            }

            $imageInfo = @getimagesizefromstring($imageData);

            if (!$imageInfo || $imageInfo['mime'] !== 'image/png') {
                return [
                    'success' => false,
                    'message' => "Gambar variasi {$queue} bukan PNG valid.",
                ];
            }

            // Batasi ukuran tiap gambar 2 MB.
            if (strlen($imageData) > 2 * 1024 * 1024) {
                return [
                    'success' => false,
                    'message' => "Ukuran gambar variasi {$queue} terlalu besar.",
                ];
            }

            $decodedSignatures[] = [
                'queue' => $queue,
                'image' => $imageData,
            ];
        }

        $savedFiles = [];

        DB::beginTransaction();

        try {
            foreach ($decodedSignatures as $signature) {
                $queue = $signature['queue'];
                $imageData = $signature['image'];

                $filename = 'signatures/pegawai/ttd_'
                    . time()
                    . '_'
                    . Str::random(10)
                    . '_'
                    . $queue
                    . '.png';

                Storage::disk('public')->put($filename, $imageData);

                $savedFiles[] = $filename;

                DB::table('simrspku_klaim.tanda_tangan_pegawai')
                    ->updateOrInsert(
                        [
                            'nip' => $nip,
                            'queue' => $queue,
                        ],
                        [
                            'nama_pegawai' => $namaPegawai,
                            'signature_path' => $filename,
                            'status' => 1,
                            'deleted_at' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
            }

            DB::commit();

            $this->loadTtd();

            $this->editingTtd = false;

            $this->dispatch(
                'profil-ttd-saved',
                message: 'Tanda tangan elektronik berhasil disimpan.'
            );

            return [
                'success' => true,
                'message' => 'Tanda tangan elektronik berhasil disimpan.',
            ];
        } catch (\Throwable $e) {
            DB::rollBack();

            foreach ($savedFiles as $file) {
                Storage::disk('public')->delete($file);
            }

            report($e);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan tanda tangan.',
            ];
        }
    }

    public function render()
    {
        return view('pages.v3.setting.profil')->layout('layouts.v3.app');
    }
}
