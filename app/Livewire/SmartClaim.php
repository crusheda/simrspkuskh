<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\simrspku_klaim\klaim_verifikasi;

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
     * DATA INDEX SMARTCLAIM
     * Adaptasi indexV2() SIRMED V2.
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
            ->where('dru.RUANGAN', 'LIKE', '1020101%')
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
     * DATA DETAIL SMARTCLAIM
     * Adaptasi showV2() SIRMED V2.
     */
    protected function getDataDetail(string $KUNJUNGAN): array
    {
        /**
         * DATA VERIFIKASI KLAIM
         */
        $klaim = klaim_verifikasi::where('nomor', $KUNJUNGAN)
            ->where('status', true)
            ->first();

        /**
         * DATA KUNJUNGAN
         */
        $show = DB::table('pendaftaran.kunjungan AS pk')
            ->select(
                'pk.*',

                // =====================================================
                // PENDAFTARAN
                // =====================================================
                'pp.NORM',
                'pp.TANGGAL AS TGLDAFTAR',

                // =====================================================
                // ASURANSI / BPJS
                // =====================================================
                'npj.DESKRIPSI AS NAMAASURANSI',
                'npj.REF_ID AS REFIDASURANSI',
                'pj.JENIS AS IDASURANSI',
                'pj.NOMOR AS NOASURANSI',

                'kjs.noKartu AS NOBPJS',
                'kjs.noSEP AS NOSEP',
                'kjs.tglSEP AS TGLSEP',

                'pjs.prolanisPRB AS PRBPASIEN',
                'pjs.ketStatusPeserta AS STATUSBPJS',

                // =====================================================
                // RUANGAN
                // =====================================================
                'ru.ID AS IDRUANGAN',
                'ru.DESKRIPSI AS NAMARUANGAN',

                'rk.KAMAR AS NAMAKAMAR',
                'rkt.TEMPAT_TIDUR AS NAMATT',

                // =====================================================
                // IDENTITAS PASIEN
                // =====================================================
                'ps.NAMA AS NAMALENGKAPPASIEN',
                'ps.PANGGILAN AS PANGGILANPASIEN',
                'ps.TANGGAL_LAHIR AS TGLLAHIRPASIEN',

                'kips.NOMOR AS NIKPASIEN',
                'kps.NOMOR AS NOHPPASIEN',

                'kgs.NAMA AS KELUARGAPASIEN',

                DB::raw('master.getNamaLengkap(ps.NORM) AS NAMAPASIEN'),

                DB::raw("
                    CASE
                        WHEN ps.JENIS_KELAMIN = 1 THEN 'LAKI-LAKI'
                        WHEN ps.JENIS_KELAMIN = 2 THEN 'PEREMPUAN'
                        ELSE 'TIDAK DIKETAHUI'
                    END AS JKPASIEN
                "),

                DB::raw(
                    'master.getCariUmur(pp.TANGGAL, ps.TANGGAL_LAHIR) AS UMURPASIEN'
                ),

                // =====================================================
                // DPJP
                // =====================================================
                DB::raw(
                    'master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'
                ),

                // =====================================================
                // JENIS KUNJUNGAN
                // =====================================================
                DB::raw("
                    CASE
                        WHEN pkonsul.NOMOR IS NOT NULL THEN 'KONSUL'
                        ELSE 'UTAMA'
                    END AS JENIS_KUNJUNGAN
                "),

                // =====================================================
                // DATA KONSUL
                // =====================================================
                DB::raw("
                    CASE
                        WHEN pkonsul.NOMOR IS NOT NULL
                        THEN ru_asal.DESKRIPSI
                        ELSE NULL
                    END AS RUANGAN_KONSUL
                "),

                DB::raw("
                    CASE
                        WHEN pkonsul.NOMOR IS NOT NULL
                        THEN master.getNamaLengkapPegawai(dr_asal.NIP)
                        ELSE NULL
                    END AS DOKTER_KONSUL
                "),

                DB::raw("
                    CASE
                        WHEN pkonsul.NOMOR IS NOT NULL
                        THEN pk_asal.MASUK
                        ELSE NULL
                    END AS TGL_KUNJUNGAN_KONSUL
                "),

                DB::raw("
                    CASE
                        WHEN pkonsul.NOMOR IS NOT NULL
                        THEN pk_asal.NOMOR
                        ELSE NULL
                    END AS NO_KUNJUNGAN_KONSUL
                ")
            )

            /**
             * RELASI KONSUL
             */
            ->leftJoin(
                'pendaftaran.konsul AS pkonsul',
                'pkonsul.NOMOR',
                '=',
                'pk.REF'
            )

            /**
             * KUNJUNGAN ASAL KONSUL
             */
            ->leftJoin(
                'pendaftaran.kunjungan AS pk_asal',
                'pk_asal.NOMOR',
                '=',
                'pkonsul.KUNJUNGAN'
            )

            /**
             * DOKTER ASAL KONSUL
             */
            ->leftJoin(
                'master.dokter AS dr_asal',
                'dr_asal.ID',
                '=',
                'pkonsul.DOKTER_ASAL'
            )

            /**
             * RUANGAN ASAL KONSUL
             */
            ->leftJoin(
                'master.ruangan AS ru_asal',
                'ru_asal.ID',
                '=',
                'pk_asal.RUANGAN'
            )

            /**
             * RELASI PENDAFTARAN
             */
            ->leftJoin(
                'pendaftaran.pendaftaran AS pp',
                'pp.NOMOR',
                '=',
                'pk.NOPEN'
            )

            /**
             * RELASI PENJAMIN
             */
            ->leftJoin(
                'pendaftaran.penjamin AS pj',
                'pj.NOPEN',
                '=',
                'pp.NOMOR'
            )

            /**
             * RELASI BPJS
             */
            ->leftJoin(
                'bpjs.kunjungan AS kjs',
                'kjs.noSEP',
                '=',
                'pj.NOMOR'
            )

            /**
             * RELASI PASIEN
             */
            ->leftJoin(
                'master.pasien AS ps',
                'ps.NORM',
                '=',
                'pp.NORM'
            )

            /**
             * RELASI RUANGAN
             */
            ->leftJoin(
                'master.ruangan AS ru',
                'ru.ID',
                '=',
                'pk.RUANGAN'
            )

            /**
             * RELASI DPJP
             */
            ->leftJoin(
                'master.dokter AS dr',
                'dr.ID',
                '=',
                'pk.DPJP'
            )

            /**
             * RELASI LAIN-LAIN
             */
            ->leftJoin('master.referensi as npj', function ($join) {
                $join->on('npj.ID', '=', 'pj.JENIS')
                    ->where('npj.JENIS', 10);
            })

            ->leftJoin('bpjs.peserta as pjs', function ($join) {
                $join->on('pjs.noKartu', '=', 'kjs.noKartu');
            })

            ->leftJoin('master.kontak_pasien as kps', function ($join) {
                $join->on('ps.NORM', '=', 'kps.NORM');
            })

            ->leftJoin('master.keluarga_pasien as kgs', function ($join) {
                $join->on('ps.NORM', '=', 'kgs.NORM');
            })

            ->leftJoin('master.kartu_identitas_pasien as kips', function ($join) {
                $join->on('ps.NORM', '=', 'kips.NORM');
            })

            ->leftJoin('master.ruang_kamar_tidur as rkt', function ($join) {
                $join->on('rkt.ID', '=', 'pk.RUANG_KAMAR_TIDUR')
                    ->whereIn('rkt.STATUS', [1, 2, 3]);
            })

            ->leftJoin('master.ruang_kamar as rk', function ($join) {
                $join->on('rk.ID', '=', 'rkt.RUANG_KAMAR')
                    ->where('rk.STATUS', 1);
            })

            ->where('pk.NOMOR', $KUNJUNGAN)
            ->first();

        /**
         * DATA UNTUK BLADE
         *
         * Struktur tetap menggunakan:
         * $list['klaim']
         * $list['show']
         * $list['KUNJUNGAN']
         */
        return [
            'klaim' => $klaim,
            'show' => $show,
            'KUNJUNGAN' => $KUNJUNGAN,
        ];
    }

    /**
     * RENDER SMARTCLAIM V3
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

        /**
         * MODE INDEX
         */
        if ($this->mode === 'index') {
            $data['list'] = $this->getDataIndex();
        }

        /**
         * MODE DETAIL / FARMASI
         */
        elseif (in_array($this->mode, ['detail', 'farmasi'], true)) {

            if (!empty($this->KUNJUNGAN)) {
                $data['list'] = $this->getDataDetail(
                    $this->KUNJUNGAN
                );
            } else {
                $data['list'] = [
                    'klaim' => null,
                    'show' => null,
                    'KUNJUNGAN' => null,
                ];
            }
        }

        /**
         * LAYOUT SIRMED V3
         */
        return view($view, $data)
            ->layout('layouts.v3.app', [
                'title' => $data['title'],
            ]);
    }
}
