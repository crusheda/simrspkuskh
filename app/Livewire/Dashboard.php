<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Carbon\Carbon;

class Dashboard extends Component
{
    public string $leaderboardPeriod = 'monthly';
    public string $leaderboardRole = 'all';

    private function getRoleInfo(): array
    {
        $user = Auth::user();

        $roles = DB::table('simrspku_klaim.model_has_roles as mhr')
            ->join('simrspku_klaim.roles as r', 'r.id', '=', 'mhr.role_id')
            ->where('mhr.model_id', $user->getAuthIdentifier())
            ->where('mhr.model_type', $user->getMorphClass())
            ->where('r.guard_name', 'web')
            ->select('r.id', 'r.name', 'r.jabatan', 'r.jenis')
            ->get();

        $isAdmin = $roles->contains(
            fn ($role) => strtolower($role->name) === 'admin'
        );

        $jenis = $roles
            ->pluck('jenis')
            ->map(fn ($v) => (int) $v)
            ->filter(fn ($v) => in_array($v, [1, 2]))
            ->unique()
            ->values();

        return [
            'isAdmin' => $isAdmin,
            'jenis' => $jenis->all(),
            'roles' => $roles,
        ];
    }

    private function getRoleOptions()
    {
        return DB::table('simrspku_klaim.roles')
            ->where('visible', 1)
            ->where('guard_name', 'web')
            ->whereIn('jenis', [1, 2])
            ->orderBy('jenis')
            ->orderBy('jabatan')
            ->get(['id', 'name', 'jabatan', 'jenis']);
    }

    private function getPeriodRange(string $period): array
    {
        $now = Carbon::now('Asia/Jakarta');

        if ($period === 'yearly') {
            $start = $now->copy()->startOfYear();
        } else {
            $start = $now->copy()
                ->startOfMonth()
                ->subMonths(2);
        }

        return [
            $start,
            $now->copy(),
        ];
    }

    private function getAllowedSubs(array $roleInfo): array
    {
        if ($roleInfo['isAdmin']) {
            if ($this->leaderboardRole === 'doctor') {
                return ['DOKTER'];
            }

            if ($this->leaderboardRole === 'nurse') {
                return ['PERAWAT'];
            }

            if (is_numeric($this->leaderboardRole)) {
                $role = DB::table('simrspku_klaim.roles')
                    ->where('id', (int) $this->leaderboardRole)
                    ->where('visible', 1)
                    ->first();

                if (!$role) {
                    return ['DOKTER', 'PERAWAT'];
                }

                return (int) $role->jenis === 1
                    ? ['DOKTER']
                    : ['PERAWAT'];
            }

            return ['DOKTER', 'PERAWAT'];
        }

        $subs = [];

        if (in_array(1, $roleInfo['jenis'])) {
            $subs[] = 'DOKTER';
        }

        if (in_array(2, $roleInfo['jenis'])) {
            $subs[] = 'PERAWAT';
        }

        return $subs;
    }

    /**
     * Query aggregate pengkajian.
     */
    private function getLeaderboardQuery(
        array $roleInfo,
        string $period = 'monthly'
    ) {
        $user = Auth::user();
        [$startDate, $endDate] = $this->getPeriodRange($period);
        $allowedSubs = $this->getAllowedSubs($roleInfo);

        if (empty($allowedSubs)) {
            return collect();
        }

        $query = DB::table('simrspku_pengkajian.finalisasi as f')
            ->join('aplikasi.pengguna as u', 'u.ID', '=', 'f.USER_CREATED')
            ->where('f.STATUS', 2)
            ->whereIn('f.SUB', $allowedSubs)
            ->whereBetween('f.CREATED', [
                $startDate->toDateTimeString(),
                $endDate->toDateTimeString(),
            ]);

        // Admin memilih role spesifik
        if ($roleInfo['isAdmin'] && is_numeric($this->leaderboardRole)) {
            $roleId = (int) $this->leaderboardRole;

            $query->whereExists(function ($subQuery) use ($roleId, $user) {
                $subQuery->selectRaw('1')
                    ->from('simrspku_klaim.model_has_roles as mhr')
                    ->whereColumn('mhr.model_id', 'u.ID')
                    ->where('mhr.role_id', $roleId)
                    ->where('mhr.model_type', $user->getMorphClass());
            });
        }

        return $query
            ->select(
                'f.USER_CREATED',
                'u.NIP',
                DB::raw('master.getNamaLengkapPegawai(u.NIP) AS nama')
            )
            ->selectRaw('GROUP_CONCAT(DISTINCT f.SUB ORDER BY f.SUB SEPARATOR ", ") AS kategori')
            ->selectRaw('COUNT(*) AS total_pengkajian')
            ->groupBy(
                'f.USER_CREATED',
                'u.NIP'
            )
            ->get()
            ->sort(function ($a, $b) {
                $totalCompare = (int) $b->total_pengkajian <=> (int) $a->total_pengkajian;

                if ($totalCompare !== 0) {
                    return $totalCompare;
                }

                return strcasecmp($a->nama, $b->nama);
            })
            ->values();
    }

    /**
     * Ranking dan Top 10.
     */
    private function buildRanking($data)
    {
        $ranking = $data
            ->sortByDesc('total_pengkajian')
            ->values()
            ->map(function ($item, $index) {
                $item->rank = $index + 1;
                return $item;
            });

        return [
            'all' => $ranking,
            'top10' => $ranking->take(10),
        ];
    }

    /**
     * Data finalisasi harian untuk streak.
     */
    private function getStreakData(array $roleInfo)
    {
        $user = Auth::user();
        $allowedSubs = $this->getAllowedSubs($roleInfo);

        if (empty($allowedSubs)) {
            return [
                'streak' => 0,
                'today' => false,
            ];
        }

        $query = DB::table('simrspku_pengkajian.finalisasi as f')
            ->where('f.USER_CREATED', $user->getAuthIdentifier())
            ->where('f.STATUS', 2)
            ->whereIn('f.SUB', $allowedSubs);

        // Jika admin sedang memilih role tertentu,
        // streak juga mengikuti role tersebut.
        if ($roleInfo['isAdmin'] && is_numeric($this->leaderboardRole)) {
            $roleId = (int) $this->leaderboardRole;

            $query->whereExists(function ($subQuery) use ($roleId, $user) {
                $subQuery->selectRaw('1')
                    ->from('simrspku_klaim.model_has_roles as mhr')
                    ->whereColumn('mhr.model_id', 'f.USER_CREATED')
                    ->where('mhr.role_id', $roleId)
                    ->where('mhr.model_type', $user->getMorphClass());
            });
        }

        $dates = $query
            ->selectRaw('DATE(f.CREATED) AS tanggal')
            ->distinct()
            ->orderByDesc('tanggal')
            ->pluck('tanggal')
            ->map(fn ($date) => Carbon::parse($date)->startOfDay());

        if ($dates->isEmpty()) {
            return [
                'streak' => 0,
                'today' => false,
            ];
        }

        $today = Carbon::now('Asia/Jakarta')->startOfDay();
        $yesterday = $today->copy()->subDay();

        $lastActivity = $dates->first();

        // Streak masih aktif jika aktivitas hari ini atau kemarin.
        if (!$lastActivity->equalTo($today) && !$lastActivity->equalTo($yesterday)) {
            return [
                'streak' => 0,
                'today' => false,
            ];
        }

        $expectedDate = $lastActivity->copy();
        $streak = 0;

        foreach ($dates as $date) {
            if (!$date->equalTo($expectedDate)) {
                break;
            }

            $streak++;
            $expectedDate->subDay();
        }

        return [
            'streak' => $streak,
            'today' => $lastActivity->equalTo($today),
        ];
    }

    /**
     * Champion bulan berjalan.
     */
    private function getMonthlyChampion(array $roleInfo)
    {
        $user = Auth::user();
        $allowedSubs = $this->getAllowedSubs($roleInfo);

        if (empty($allowedSubs)) {
            return null;
        }

        $now = Carbon::now('Asia/Jakarta');

        $query = DB::table('simrspku_pengkajian.finalisasi as f')
            ->join('aplikasi.pengguna as u', 'u.ID', '=', 'f.USER_CREATED')
            ->where('f.STATUS', 2)
            ->whereIn('f.SUB', $allowedSubs)
            ->whereBetween('f.CREATED', [
                $now->copy()->startOfMonth()->toDateTimeString(),
                $now->copy()->endOfDay()->toDateTimeString(),
            ]);

        if ($roleInfo['isAdmin'] && is_numeric($this->leaderboardRole)) {
            $roleId = (int) $this->leaderboardRole;

            $query->whereExists(function ($subQuery) use ($roleId, $user) {
                $subQuery->selectRaw('1')
                    ->from('simrspku_klaim.model_has_roles as mhr')
                    ->whereColumn('mhr.model_id', 'u.ID')
                    ->where('mhr.role_id', $roleId)
                    ->where('mhr.model_type', $user->getMorphClass());
            });
        }

        return $query
            ->select(
                'f.USER_CREATED',
                'u.NIP',
                DB::raw('master.getNamaLengkapPegawai(u.NIP) AS nama')
            )
            ->selectRaw('GROUP_CONCAT(DISTINCT f.SUB ORDER BY f.SUB SEPARATOR ", ") AS kategori')
            ->selectRaw('COUNT(*) AS total_pengkajian')
            ->groupBy(
                'f.USER_CREATED',
                'u.NIP'
            )
            ->orderByDesc('total_pengkajian')
            ->orderBy('nama')
            ->first();
    }

    /**
     * Ranking personal.
     */
    private function getMyRanking($ranking)
    {
        $myId = Auth::user()->getAuthIdentifier();

        $myData = $ranking->firstWhere('USER_CREATED', $myId);

        if (!$myData) {
            return [
                'rank' => null,
                'total' => 0,
                'next' => null,
                'needed' => null,
                'progress' => 0,
            ];
        }

        $above = $ranking->firstWhere('rank', $myData->rank - 1);

        if (!$above || $myData->rank === 1) {
            return [
                'rank' => $myData->rank,
                'total' => $myData->total_pengkajian,
                'next' => null,
                'needed' => 0,
                'progress' => 100,
            ];
        }

        $needed = max(
            0,
            (int) $above->total_pengkajian
                - (int) $myData->total_pengkajian
                + 1
        );

        $progress = $above->total_pengkajian > 0
            ? min(100, round(
                ($myData->total_pengkajian / $above->total_pengkajian) * 100
            ))
            : 100;

        return [
            'rank' => $myData->rank,
            'total' => $myData->total_pengkajian,
            'next' => $above,
            'needed' => $needed,
            'progress' => $progress,
        ];
    }

    public function render()
    {
        $roleInfo = $this->getRoleInfo();

        $monthlyData = $this->getLeaderboardQuery($roleInfo, 'monthly');
        $yearlyData = $this->getLeaderboardQuery($roleInfo, 'yearly');

        $activeData = $this->leaderboardPeriod === 'yearly'
            ? $yearlyData
            : $monthlyData;

        $ranking = $this->buildRanking($activeData);

        $championData = $this->getMonthlyChampion($roleInfo);

        $myRanking = $this->getMyRanking($ranking['all']);

        $streakData = $this->getStreakData($roleInfo);

        return view('pages.v3.dashboard.index', [
            'leaderboard' => $ranking['top10'],
            'allRanking' => $ranking['all'],
            'myRanking' => $myRanking,
            'streakData' => $streakData,
            'monthlyChampion' => $championData,
            'leaderboardRoleOptions' => $this->getRoleOptions(),
            'leaderboardIsAdmin' => $roleInfo['isAdmin'],
            'leaderboardJenis' => $roleInfo['jenis'],
            'leaderboardPeriod' => $this->leaderboardPeriod,
        ])->layout('layouts.v3.app');
    }
}
