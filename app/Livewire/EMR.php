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
        return view('pages.v3.emr.index')->layout('layouts.v3.app');
    }

    public function detailV3(string $KUNJUNGAN)
    {
        $show = DB::table('pendaftaran.kunjungan AS pk')
            ->select(
                'pk.*',
                'pp.NORM',
                'pp.TANGGAL AS TGLDAFTAR',
                'kjs.noSEP AS NOSEP',
                'kjs.tglSEP AS TGLSEP',
                'kjs.noKartu AS NOBPJS',
                'pjs.prolanisPRB AS PRBPASIEN',
                'pjs.ketStatusPeserta AS STATUSBPJS',
                'ru.ID AS IDRUANGAN',
                'ru.DESKRIPSI AS NAMARUANGAN',
                'kips.NOMOR AS NIKPASIEN',
                'ps.NAMA AS NAMALENGKAPPASIEN',
                'ps.PANGGILAN AS PANGGILANPASIEN',
                'ps.TANGGAL_LAHIR AS TGLLAHIRPASIEN',
                'kps.NOMOR AS NOHPPASIEN',
                'kgs.NAMA AS KELUARGAPASIEN',
                DB::raw("
                    CASE
                        WHEN ps.JENIS_KELAMIN = 1 THEN 'LAKI-LAKI'
                        WHEN ps.JENIS_KELAMIN = 2 THEN 'PEREMPUAN'
                        ELSE 'TIDAK DIKETAHUI'
                    END AS JKPASIEN
                "),
                DB::raw("
                    CASE
                        WHEN kgs.JENIS_KELAMIN = 1 THEN 'LAKI-LAKI'
                        WHEN kgs.JENIS_KELAMIN = 2 THEN 'PEREMPUAN'
                        ELSE 'TIDAK DIKETAHUI'
                    END AS JKKELUARGAPASIEN
                "),
                DB::raw("(
                    SELECT DESKRIPSI
                    FROM master.referensi AS refkgs
                    WHERE refkgs.ID = kgs.SHDK
                    AND refkgs.JENIS = 7
                ) AS STKELUARGAPASIEN"),
                DB::raw('master.getNamaLengkap(ps.NORM) AS NAMAPASIEN'),
                DB::raw('master.getAlamatPasienCustom(ps.NORM) AS ALAMATPASIEN'),
                DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'),
                DB::raw('master.getCariUmur(pp.TANGGAL, ps.TANGGAL_LAHIR) AS UMURPASIEN'),
                DB::raw("(
                    SELECT DESKRIPSI
                    FROM master.wilayah AS wil
                    WHERE wil.ID = ps.TEMPAT_LAHIR
                ) AS TLPASIEN")
            )
            ->leftJoin(
                'pendaftaran.pendaftaran AS pp',
                'pp.NOMOR',
                '=',
                'pk.NOPEN'
            )
            ->leftJoin(
                'pendaftaran.penjamin AS pj',
                'pj.NOPEN',
                '=',
                'pp.NOMOR'
            )
            ->leftJoin(
                'bpjs.kunjungan AS kjs',
                'kjs.noSEP',
                '=',
                'pj.NOMOR'
            )
            ->leftJoin('bpjs.peserta AS pjs', function ($join) {
                $join->on('pjs.noKartu', '=', 'kjs.noKartu');
            })
            ->leftJoin(
                'master.pasien AS ps',
                'ps.NORM',
                '=',
                'pp.NORM'
            )
            ->leftJoin(
                'master.kontak_pasien AS kps',
                'ps.NORM',
                '=',
                'kps.NORM'
            )
            ->leftJoin(
                'master.keluarga_pasien AS kgs',
                'ps.NORM',
                '=',
                'kgs.NORM'
            )
            ->leftJoin(
                'master.kartu_identitas_pasien AS kips',
                'ps.NORM',
                '=',
                'kips.NORM'
            )
            ->leftJoin(
                'master.ruangan AS ru',
                'ru.ID',
                '=',
                'pk.RUANGAN'
            )
            ->leftJoin(
                'master.dokter AS dr',
                'dr.ID',
                '=',
                'pk.DPJP'
            )
            ->where('pk.NOMOR', $KUNJUNGAN)
            ->first();

        if (!$show) {
            abort(404, "Kunjungan {$KUNJUNGAN} tidak ditemukan.");
        }

        /*
        |--------------------------------------------------------------------------
        | RIWAYAT ALERGI
        |--------------------------------------------------------------------------
        */

        $riwayat_alergi = DB::table('medicalrecord.riwayat_alergi as ra')
            ->leftJoin('master.referensi as ref', function ($join) {
                $join->on('ra.JENIS', '=', 'ref.ID')
                    ->where('ref.JENIS', 180)
                    ->where('ref.STATUS', 1);
            })
            ->join(
                'pendaftaran.kunjungan as pk',
                'ra.KUNJUNGAN',
                '=',
                'pk.NOMOR'
            )
            ->join(
                'pendaftaran.pendaftaran as pp',
                'pk.NOPEN',
                '=',
                'pp.NOMOR'
            )
            ->leftJoin(
                'master.ruangan as ru',
                'ru.ID',
                '=',
                'pk.RUANGAN'
            )
            ->leftJoin(
                'aplikasi.pengguna as pe',
                'pe.ID',
                '=',
                'ra.OLEH'
            )
            ->select(
                'ra.*',
                'ref.DESKRIPSI as JENIS_ALERGI',
                'ru.DESKRIPSI as NAMA_RUANGAN',
                DB::raw(
                    'master.getNamaLengkapPegawai(pe.NIP) as NAMA_USER'
                )
            )
            ->where('pp.NORM', $show->NORM)
            ->where('ra.STATUS', 1)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | TANDA TANGAN ELEKTRONIK
        |--------------------------------------------------------------------------
        */

        $tte_pegawai = DB::table('simrspku_klaim.tanda_tangan_pegawai')
            ->where('nip', Auth::user()->NIP)
            ->whereNull('deleted_at')
            ->exists();

        /*
        |--------------------------------------------------------------------------
        | JUMLAH CPPT
        |--------------------------------------------------------------------------
        */

        $countCppt = DB::table('medicalrecord.cppt as cp')
            ->join(
                'pendaftaran.kunjungan as pk',
                'cp.KUNJUNGAN',
                '=',
                'pk.NOMOR'
            )
            ->where('cp.STATUS', '!=', 0)
            ->where('pk.NOPEN', $show->NOPEN)
            ->where('cp.KUNJUNGAN', $KUNJUNGAN)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | DATA RUANGAN DAN UMUR
        |--------------------------------------------------------------------------
        */

        $idRuangan = (string) ($show->IDRUANGAN ?? '');
        $kodeRuangan = substr($idRuangan, 0, 5);

        $umurHari = null;
        $umurTahun = null;

        if (
            !empty($show->TGLLAHIRPASIEN) &&
            !empty($show->TGLDAFTAR)
        ) {
            try {
                $tanggalLahir = Carbon::parse($show->TGLLAHIRPASIEN);
                $tanggalKunjungan = Carbon::parse($show->TGLDAFTAR);

                $umurHari = $tanggalLahir->diffInDays($tanggalKunjungan);
                $umurTahun = $tanggalLahir->diffInYears($tanggalKunjungan);
            } catch (\Throwable $e) {
                $umurHari = null;
                $umurTahun = null;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FORM PENGKAJIAN
        |--------------------------------------------------------------------------
        */

        $formPengkajian = [];

        if ($kodeRuangan === '10202') {
            // Rawat darurat
            $formPengkajian = [
                'pengkajian-gd',
            ];
        } elseif ($kodeRuangan === '10201') {
            // Rawat jalan
            if ($idRuangan === '102010114') {
                $formPengkajian = [
                    'pengkajian-rajal-psikiatri',
                ];
            } elseif ($idRuangan === '102010102') {
                $formPengkajian = [
                    'pengkajian-rajal-obsgyn',
                ];
            } elseif ($idRuangan === '102010103') {
                // Poli interna
                if ($umurTahun !== null && $umurTahun < 18) {
                    $formPengkajian = [
                        'pengkajian-rajal-anak',
                    ];
                } elseif ($umurTahun !== null && $umurTahun > 65) {
                    $formPengkajian = [
                        'pengkajian-rajal-dewasa',
                        'pengkajian-rajal-geriatri',
                    ];
                } else {
                    $formPengkajian = [
                        'pengkajian-rajal-dewasa',
                    ];
                }
            } else {
                // Poli rawat jalan lainnya
                if ($umurTahun !== null && $umurTahun < 18) {
                    $formPengkajian = [
                        'pengkajian-rajal-anak',
                        'pengkajian-rajal-neonatus',
                    ];
                } else {
                    $formPengkajian = [
                        'pengkajian-rajal-dewasa',
                    ];
                }
            }
        } elseif ($kodeRuangan === '10203') {
            // Rawat inap
            if ($idRuangan === '102030106') {
                $formPengkajian = [
                    'pengkajian-ranap-obsgyn',
                ];
            } elseif (
                in_array($idRuangan, ['102030203', '102030204'], true) ||
                ($umurHari !== null && $umurHari <= 28)
            ) {
                $formPengkajian = [
                    'pengkajian-ranap-neonatus',
                ];
            } elseif ($umurTahun !== null && $umurTahun < 18) {
                $formPengkajian = [
                    'pengkajian-ranap-anak',
                ];
            } else {
                $formPengkajian = [
                    'pengkajian-ranap-dewasa',
                ];
            }
        } elseif ($kodeRuangan === '10208') {
            // Bedah dan anestesi
            $formPengkajian = [
                'pengkajian-prabedah',
                'pengkajian-praanestesiinduksi',
                'pengkajian-laporananestesi',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | DATA UNTUK VIEW
        |--------------------------------------------------------------------------
        */

        $data = [
            'show' => $show,
            'readmisi' => $this->potensiReadmisi($KUNJUNGAN),
            'riwayat_alergi' => $riwayat_alergi,
            'KUNJUNGAN' => $KUNJUNGAN,
            'tte_pegawai' => $tte_pegawai,
            'cpptCount' => $countCppt,
            'form_pengkajian' => $formPengkajian,
            'umur_hari' => $umurHari,
            'umur_tahun' => $umurTahun,
            'kode_ruangan' => $kodeRuangan,
        ];

        return view('pages.v3.emr.detail.index', [
            'list' => $data,
        ]);
    }

    private function potensiReadmisi(string $kunjungan): array
    {
        // Ambil data kunjungan saat ini
        $kunjunganSaatIni = DB::table('pendaftaran.kunjungan as k')
            ->join('pendaftaran.pendaftaran as p', 'p.NOMOR', '=', 'k.NOPEN')
            ->where('k.NOMOR', $kunjungan)
            ->select(
                'k.NOMOR',
                'k.NOPEN',
                'k.MASUK',
                'k.RUANGAN',
                'p.NORM'
            )
            ->first();

        if (!$kunjunganSaatIni) {
            return [
                'potensi_readmisi' => false,
                'keterangan' => 'Data kunjungan tidak ditemukan',
                'tanggal_kunjungan' => null,
                'tanggal_pulang_sebelumnya' => null,
                'selisih_hari' => null,
                'kunjungan_sebelumnya' => null,
            ];
        }

        // Cari kepulangan sebelumnya dari pasien yang sama.
        // Kepulangan Lanjut Rawat Inap bukan kepulangan akhir.
        $pulangSebelumnya = DB::table('layanan.pasien_pulang as pp')
            ->join('pendaftaran.pendaftaran as p', 'p.NOMOR', '=', 'pp.NOPEN')
            ->where('p.NORM', $kunjunganSaatIni->NORM)
            ->where('pp.STATUS', 1)
            ->where('pp.KUNJUNGAN', '!=', $kunjunganSaatIni->NOMOR)
            ->where('pp.TANGGAL', '<=', $kunjunganSaatIni->MASUK)
            ->whereNotIn('pp.CARA', [6, 7, 50])
            ->where('pp.KEADAAN', '!=', 60)
            ->orderByDesc('pp.TANGGAL')
            ->select(
                'pp.ID',
                'pp.KUNJUNGAN',
                'pp.NOPEN',
                'pp.TANGGAL',
                'pp.CARA',
                'pp.KEADAAN'
            )
            ->first();

        if (!$pulangSebelumnya) {
            return [
                'potensi_readmisi' => false,
                'keterangan' => 'Tidak ditemukan kepulangan sebelumnya yang memenuhi kriteria',
                'tanggal_kunjungan' => $kunjunganSaatIni->MASUK,
                'tanggal_pulang_sebelumnya' => null,
                'selisih_hari' => null,
                'kunjungan_sebelumnya' => null,
            ];
        }

        $tanggalKunjungan = Carbon::parse($kunjunganSaatIni->MASUK)->startOfDay();
        $tanggalPulang = Carbon::parse($pulangSebelumnya->TANGGAL)->startOfDay();

        $selisihHari = $tanggalPulang->diffInDays($tanggalKunjungan, false);

        $potensiReadmisi = $selisihHari >= 0 && $selisihHari <= 30;

        return [
            'potensi_readmisi' => $potensiReadmisi,
            'keterangan' => $potensiReadmisi
                ? 'Potensi Readmisi'
                : 'Tidak terindikasi Potensi Readmisi',
            'tanggal_kunjungan' => $kunjunganSaatIni->MASUK,
            'tanggal_pulang_sebelumnya' => $pulangSebelumnya->TANGGAL,
            'selisih_hari' => $selisihHari,
            'kunjungan_sebelumnya' => $pulangSebelumnya->KUNJUNGAN,
        ];
    }
}
