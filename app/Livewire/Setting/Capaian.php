<?php

namespace App\Livewire\Setting;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Capaian extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';
    public string $filterRole = 'all';
    public string $filterStatus = 'all';
    public string $filterVariant = 'all';
    public string $sortBy = 'nama';
    public string $sortDirection = 'asc';
    public int $perPage = 15;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterRole' => ['except' => 'all'],
        'filterStatus' => ['except' => 'all'],
        'filterVariant' => ['except' => 'all'],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterRole()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function updatedFilterVariant()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        $allowed = ['nama', 'nip', 'jumlah_tte'];

        if (!in_array($field, $allowed)) {
            return;
        }

        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc'
                ? 'desc'
                : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    private function getRoleOptions()
    {
        return DB::table('simrspku_klaim.roles')
            ->where('visible', 1)
            ->where('guard_name', 'web')
            ->whereNotNull('jabatan')
            ->where('jabatan', '!=', '')
            ->orderBy('jabatan')
            ->get(['id', 'name', 'jabatan']);
    }

    /**
     * Query agregasi TTE per NIP.
     */
    private function signatureAggregateQuery()
    {
        return DB::table('simrspku_klaim.tanda_tangan_pegawai')
            ->select('nip')
            ->selectRaw('COUNT(DISTINCT queue) AS jumlah_tte')
            ->selectRaw('MAX(CASE WHEN queue = 1 THEN 1 ELSE 0 END) AS varian_1')
            ->selectRaw('MAX(CASE WHEN queue = 2 THEN 1 ELSE 0 END) AS varian_2')
            ->selectRaw('MAX(CASE WHEN queue = 3 THEN 1 ELSE 0 END) AS varian_3')
            ->selectRaw('MAX(CASE WHEN queue = 4 THEN 1 ELSE 0 END) AS varian_4')
            ->selectRaw('MAX(CASE WHEN queue = 5 THEN 1 ELSE 0 END) AS varian_5')
            ->selectRaw('MAX(CASE WHEN queue = 6 THEN 1 ELSE 0 END) AS varian_6')
            ->where('status', 1)
            ->whereNull('deleted_at')
            ->groupBy('nip');
    }

    /**
     * Query dasar pegawai aktif yang memiliki akun SIRMED.
     */
    private function employeeQuery()
    {
        $tte = $this->signatureAggregateQuery();

        return DB::table('master.pegawai as pg')
            ->join('aplikasi.pengguna as ap', 'ap.NIP', '=', 'pg.NIP')
            ->leftJoinSub($tte, 'tte', function ($join) {
                $join->on('tte.nip', '=', 'pg.NIP');
            })
            ->whereExists(function ($query) {
                $query->selectRaw('1')
                    ->from('simrspku_klaim.model_has_roles as mhr')
                    ->whereColumn('mhr.model_id', 'ap.ID')
                    ->where('mhr.model_type', 'App\Models\Pengguna');
            })
            ->whereExists(function ($query) {
                $query->selectRaw('1')
                    ->from('simrspku_klaim.model_has_roles as mhr')
                    ->join(
                        'simrspku_klaim.roles as r',
                        'r.id',
                        '=',
                        'mhr.role_id'
                    )
                    ->whereColumn('mhr.model_id', 'ap.ID');
            })
            ->select(
                'pg.NIP as nip',
                DB::raw('master.getNamaLengkapPegawai(pg.NIP) AS nama'),
                DB::raw('COALESCE(tte.jumlah_tte, 0) AS jumlah_tte'),
                DB::raw('COALESCE(tte.varian_1, 0) AS varian_1'),
                DB::raw('COALESCE(tte.varian_2, 0) AS varian_2'),
                DB::raw('COALESCE(tte.varian_3, 0) AS varian_3'),
                DB::raw('COALESCE(tte.varian_4, 0) AS varian_4'),
                DB::raw('COALESCE(tte.varian_5, 0) AS varian_5'),
                DB::raw('COALESCE(tte.varian_6, 0) AS varian_6')
            )
            ->where('pg.STATUS', 1)
            ->whereNotNull('pg.NIP')
            ->where('pg.NIP', '!=', '')
            ->distinct();
    }

    /**
     * Query pegawai untuk list, termasuk filter.
     */
    private function filteredEmployeeQuery()
    {
        $query = $this->employeeQuery();

        if ($this->search !== '') {
            $keyword = trim($this->search);

            $query->where(function ($q) use ($keyword) {
                $q->where('pg.NIP', 'like', "%{$keyword}%")
                  ->orWhereRaw(
                      'master.getNamaLengkapPegawai(pg.NIP) LIKE ?',
                      ["%{$keyword}%"]
                  );
            });
        }

        if ($this->filterRole !== 'all') {
            $query->whereExists(function ($subQuery) {
                $subQuery->selectRaw('1')
                    ->from('simrspku_klaim.model_has_roles as mhr')
                    ->whereColumn('mhr.model_id', 'ap.ID')
                    ->where('mhr.role_id', $this->filterRole);
            });
        }

        if ($this->filterStatus === 'sudah') {
            $query->whereRaw('COALESCE(tte.jumlah_tte, 0) > 0');
        } elseif ($this->filterStatus === 'belum') {
            $query->whereRaw('COALESCE(tte.jumlah_tte, 0) = 0');
        }

        if ($this->filterVariant !== 'all') {
            $variantColumn = 'varian_' . $this->filterVariant;

            $query->whereRaw(
                "COALESCE(tte.{$variantColumn}, 0) = 1"
            );
        }

        return $query;
    }

    /**
     * Statistik utama.
     */
    private function getStatistics(): array
    {
        $query = $this->employeeQuery();

        $totalPegawai = (clone $query)->count();

        $sudahTte = (clone $query)
            ->whereRaw('COALESCE(tte.jumlah_tte, 0) > 0')
            ->count();

        $belumTte = $totalPegawai - $sudahTte;

        $persentase = $totalPegawai > 0
            ? round(($sudahTte / $totalPegawai) * 100, 1)
            : 0;

        $totalVariasi = DB::table('simrspku_klaim.tanda_tangan_pegawai')
            ->where('status', 1)
            ->whereNull('deleted_at')
            ->count();

        $variantStats = DB::table('simrspku_klaim.tanda_tangan_pegawai')
            ->select('queue')
            ->selectRaw('COUNT(DISTINCT nip) AS jumlah_pegawai')
            ->selectRaw('COUNT(*) AS jumlah_tte')
            ->where('status', 1)
            ->whereNull('deleted_at')
            ->groupBy('queue')
            ->get()
            ->keyBy('queue');

        $variants = [];

        for ($i = 1; $i <= 6; $i++) {
            $data = $variantStats->get($i);

            $variants[$i] = [
                'pegawai' => $data->jumlah_pegawai ?? 0,
                'tte' => $data->jumlah_tte ?? 0,
            ];
        }

        return [
            'totalPegawai' => $totalPegawai,
            'sudahTte' => $sudahTte,
            'belumTte' => $belumTte,
            'persentase' => $persentase,
            'totalVariasi' => $totalVariasi,
            'variants' => $variants,
        ];
    }

    public function render()
    {
        // Validasi arah sorting
        $direction = in_array(strtolower($this->sortDirection), ['asc', 'desc'])
            ? strtolower($this->sortDirection)
            : 'asc';

        $query = $this->filteredEmployeeQuery();

        switch ($this->sortBy) {
            case 'nip':
                $query->orderBy('pg.NIP', $direction);
                break;

            case 'jumlah_tte':
                $query->orderByRaw('COALESCE(tte.jumlah_tte, 0) ' . $direction);
                break;

            case 'nama':
            default:
                // Jangan menggunakan alias "nama" karena ambigu
                $query->orderByRaw(
                    "master.getNamaLengkapPegawai(pg.NIP) {$direction}"
                );
                break;
        }

        return view('pages.v3.setting.capaian', [
            'pegawai' => $query->paginate($this->perPage),
            'stats' => $this->getStatistics(),
            'roleOptions' => $this->getRoleOptions(),
        ])->layout('layouts.v3.app');
    }
}
