<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SmartClaim extends Component
{
    /**
     * Nomor kunjungan pasien.
     */
    public ?string $KUNJUNGAN = null;

    /**
     * Mode workspace:
     * - index
     * - detail
     * - farmasi
     */
    public string $mode = 'index';

    /**
     * Inisialisasi component.
     */
    public function mount(?string $KUNJUNGAN = null): void
    {
        $this->KUNJUNGAN = $KUNJUNGAN;

        $routeName = Route::currentRouteName();

        if ($routeName === 'v3.smartclaim.detail.farmasi') {
            $this->mode = 'farmasi';
            return;
        }

        if ($routeName === 'v3.smartclaim.detail') {
            $this->mode = 'detail';
            return;
        }

        $this->mode = 'index';
    }

    /**
     * Data index SmartClaim.
     * Adaptasi dari indexV2() SIRMED V2.
     */
    protected function getDataIndex(): array
    {
        $yearMonth = Carbon::now()->isoFormat('YYYY-MM');

        $dr = DB::table('master.dokter AS dr')
            ->select(
                'dr.id',
                'dr.NIP',
                DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'),
                'ref.DESKRIPSI'
            )
            ->leftJoin('master.pegawai AS pg', 'pg.NIP', '=', 'dr.NIP')
            ->leftJoin('master.referensi AS ref', function ($join) {
                $join->on('ref.ID', '=', 'pg.SMF')
                    ->where('ref.JENIS', '26');
            })
            ->leftJoin('master.dokter_ruangan AS dru', 'dru.DOKTER', '=', 'dr.ID')
            ->where('dr.STATUS', '1')
            ->where('dru.STATUS', '1')
            ->where(function ($query) {
                $query->where('dru.RUANGAN', 'LIKE', '1020101%');
            })
            ->orderBy('ref.DESKRIPSI', 'ASC')
            ->groupBy(
                'dr.id',
                'dr.NIP',
                'NAMADOKTER'
            )
            ->get();

        return [
            'yearMonth' => $yearMonth,
            'dr' => $dr,
        ];
    }

    /**
     * Render halaman SmartClaim V3.
     */
    public function render()
    {
        $view = match ($this->mode) {
            'detail' => 'pages.v3.smartclaim.detail',
            'farmasi' => 'pages.v3.smartclaim.detail-farmasi',
            default => 'pages.v3.smartclaim.index',
        };

        $data = [
            'KUNJUNGAN' => $this->KUNJUNGAN,
            'mode' => $this->mode,
            'title' => match ($this->mode) {
                'detail' => 'Smart Claim Detail',
                'farmasi' => 'Smart Claim Farmasi',
                default => 'Smart Claim',
            },
        ];

        if ($this->mode === 'index') {
            $data['list'] = $this->getDataIndex();
        }

        return view($view, $data)
            ->layout('layouts.v3.app', [
                'title' => $data['title'],
            ]);
    }
}
