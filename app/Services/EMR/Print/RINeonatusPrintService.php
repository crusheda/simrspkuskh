<?php

namespace App\Services\EMR\Print;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RINeonatusPrintService implements PrintDataInterface
{
    public function getData(
        string $kunjungan,
        ?string $sub = null
    ): array {
        return match (strtoupper((string) $sub)) {
            'DOKTER'  => $this->getDataDokter($kunjungan),
            'PERAWAT' => $this->getDataPerawat($kunjungan),
            default   => [],
        };
    }

    /**
     * ========================================================================
     * DATA DOKTER
     * Pengkajian Awal Medis Rawat Inap Neonatus
     * ========================================================================
     */
    private function getDataDokter(string $kunjungan): array
    {
        /*
        |--------------------------------------------------------------------------
        | TTD USER FINALISASI
        |--------------------------------------------------------------------------
        */
        $finalisasi = DB::table(
            'simrspku_pengkajian.finalisasi as fin'
        )
            ->leftJoin('aplikasi.pengguna as usr', 'usr.ID', '=', 'fin.USER_CREATED')
            ->select('fin.*', 'usr.NIP as NIP_USER_FINALISASI', DB::raw('master.getNamaLengkapPegawai(usr.NIP) as NAMA_USER_FINALISASI'))
            ->where('fin.KUNJUNGAN', $kunjungan)
            ->where('fin.FORM', 'pengkajian-ranap-neonatus')
            ->where('fin.SUB', 'DOKTER')
            ->where('fin.STATUS', 2)
            ->first();

        $ttd_finalisasi = DB::table('simrspku_klaim.tanda_tangan_pegawai')
            ->where('nip', $finalisasi->NIP_USER_FINALISASI)
            // ->where('nip', 1912314)
            ->where('status', 1)
            ->whereNull('deleted_at')
            ->inRandomOrder()
            ->first();

        /*
        |--------------------------------------------------------------------------
        | STATUS OBSTETRI DAN NEONATUS
        |--------------------------------------------------------------------------
        */
        $statusObstetri = DB::table('medicalrecord.sirmed_status_obstetri')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first();

        $statusNeonatus = DB::table('medicalrecord.sirmed_status_neonatus')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first();

        $centangPilihan = static function ($nilai, array $pilihan): array {
            $hasil = [];

            foreach ($pilihan as $value => $label) {
                $hasil[] = ((string) ($nilai ?? '') === (string) $value ? '☑' : '☐')
                    . ' ' . $label;
            }

            return $hasil;
        };

        $nilaiInteger = function ($nilai): string {
            $nilai = $this->value($nilai);

            return $nilai !== '' && is_numeric($nilai)
                ? (string) (int) round((float) $nilai)
                : $nilai;
        };

        $centangSatuBaris = static function ($nilai, array $pilihan): string {
            $hasil = [];

            foreach ($pilihan as $value => $label) {
                $hasil[] = ((string) ($nilai ?? '') === (string) $value ? '☑' : '☐')
                    . ' ' . $label;
            }

            return implode('   ', $hasil);
        };

        $detailStatusObstetri = [];
        $detailStatusNeonatus = [];

        if ($statusObstetri || $statusNeonatus) {
            $detailStatusObstetri[] =
                '☑ Bayi lahir di RS PKU Muhammadiyah Sukoharjo';

            if ($statusObstetri) {
                $detailStatusObstetri[] = '';

                foreach ([
                    'Umur ibu' => $statusObstetri->UMUR_IBU ?? null,
                    'G' => $statusObstetri->G ?? null,
                    'P' => $statusObstetri->P ?? null,
                    'A' => $statusObstetri->A ?? null,
                    'Umur kehamilan (minggu)' =>
                        $statusObstetri->UMUR_KEHAMILAN ?? null,
                ] as $label => $nilai) {
                    $nilai = $this->value($nilai);
                    if ($nilai !== '') {
                        $detailStatusObstetri[] = "{$label}: {$nilai}";
                    }
                }

                $detailStatusObstetri[] = 'Komplikasi: '
                    . $centangSatuBaris($statusObstetri->KOMPLIKASI ?? null, [
                        '0' => 'Tidak ada',
                        '1' => 'Ada',
                    ]);

                $komplikasiKet = $this->value(
                    $statusObstetri->KOMPLIKASI_KET ?? null
                );

                if ($komplikasiKet !== '') {
                    $detailStatusObstetri[] = 'Keterangan komplikasi: ' . $komplikasiKet;
                }

                $golonganDarahAyah =
                        (int) ($statusObstetri->GOL_AYAH_TIDAK ?? 0) === 1
                            ? 'Tidak diketahui'
                            : $this->value($statusObstetri->GOL_DARAH_AYAH ?? null);

                foreach ([
                    'Golongan darah ibu' =>
                        $statusObstetri->GOL_DARAH_IBU ?? null,
                    'Rhesus ibu' => $statusObstetri->RH_IBU ?? null,
                    'Golongan darah ayah' => $golonganDarahAyah ?? null,
                    'Rhesus ayah' => $statusObstetri->RH_AYAH ?? null,
                    'Ketuban pecah jam' =>
                        $statusObstetri->KK_PECAH_JAM ?? null,
                ] as $label => $nilai) {
                    $nilai = $this->value($nilai);
                    if ($nilai !== '') {
                        $detailStatusObstetri[] = "{$label}: {$nilai}";
                    }
                }
            }

            if ($statusNeonatus) {
                $detailStatusNeonatus[] = '';

                foreach ([
                    'Tanggal lahir' => $statusNeonatus->TANGGAL_LAHIR ?? null,
                    'Jam lahir' => $statusNeonatus->JAM_LAHIR ?? null,
                ] as $label => $nilai) {
                    $nilai = $this->value($nilai);
                    if ($nilai !== '') {
                        $detailStatusNeonatus[] = "{$label}: {$nilai}";
                    }
                }

                $detailStatusNeonatus[] = 'Jenis kelamin: '
                    . $centangSatuBaris($statusNeonatus->JENIS_KELAMIN ?? null, [
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ]);

                foreach ([
                    'Berat badan lahir (gram)' => $statusNeonatus->BB_LAHIR ?? null,
                    'Panjang badan lahir' => $nilaiInteger($statusNeonatus->PB_LAHIR) ?? null,
                    'Lingkar kepala' => $nilaiInteger($statusNeonatus->LK) ?? null,
                    'Lingkar dada' => $nilaiInteger($statusNeonatus->LD) ?? null,
                    'Lingkar perut' => $nilaiInteger($statusNeonatus->LP) ?? null,
                    'LILA' => $nilaiInteger($statusNeonatus->LILA) ?? null,
                    'Resusitasi intubasi' =>
                        $statusNeonatus->RESUSITASI_INTUBASI ?? null,
                    'Resusitasi pompa' =>
                        $statusNeonatus->RESUSITASI_POMPA ?? null,
                    'Berulang' => $statusNeonatus->BERULANG ?? null,
                    'Indikasi' => $statusNeonatus->INDIKASI ?? null,
                ] as $label => $nilai) {
                    $nilai = $this->value($nilai);
                    if ($nilai !== '') {
                        $detailStatusNeonatus[] = "{$label}: {$nilai}";
                    }
                }

                $detailStatusNeonatus[] = 'Jenis partus: '
                    . $centangSatuBaris($statusNeonatus->JENIS_PARTUS ?? null, [
                        'SC' => 'SC',
                        'V' => 'Spontan',
                        'S' => 'Lainnya',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | PENILAIAN AWAL BAYI BARU LAHIR
        |--------------------------------------------------------------------------
        */
        $dataAwalBayi = DB::table('medicalrecord.sirmed_penilaian_awal_bayi')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first();

        $angkaAtauStrip = static function ($nilai): string {
            return $nilai !== null && $nilai !== ''
                ? (string) (int) round((float) $nilai)
                : '-';
        };

        $centangNilai = static function ($nilai, $pilihan): string {
            return (string) ($nilai ?? '') === (string) $pilihan ? '☑' : '☐';
        };

        $dataApgar = [];

        if ($dataAwalBayi) {
            foreach ([1, 5, 10] as $menit) {
                $prefix = "APGAR_{$menit}_MENIT_";

                foreach ([
                    'DENYUT' => 'DENYUT',
                    'PERNAFASAN' => 'PERNAFASAN',
                    'TONUS' => 'TONUS',
                    'RANGSANG' => 'RANGSANG',
                    'WARNA' => 'WARNA',
                ] as $key => $kolom) {
                    $dataApgar["APGAR_{$menit}_{$key}"] =
                        $angkaAtauStrip($dataAwalBayi->{$prefix . $kolom} ?? null);
                }

                $dataApgar["APGAR_TOTAL_{$menit}"] =
                    $angkaAtauStrip(
                        $dataAwalBayi->{"APGAR_TOTAL_{$menit}_MENIT"} ?? null
                    );
            }

            $statusBayi = (string) ($dataAwalBayi->APGAR_STATUS_BAYI ?? '');

            $dataApgar['BAYI_BUGAR'] =
                $centangNilai($statusBayi, 'bugar') . ' Bayi Bugar';

            $dataApgar['BAYI_TIDAK_BUGAR'] =
                $centangNilai($statusBayi, 'tidak_bugar') . ' Bayi Tidak Bugar';

            /*
            * Hasil resusitasi hanya diisi bila status bayi tidak bugar.
            * Saat bayi bugar, seluruh isi blok ini dikosongkan.
            */
            $detailResusitasi = [];

            if ($statusBayi === 'tidak_bugar') {
                $resusitasi = (string) ($dataAwalBayi->APGAR_RESUSITASI ?? '');

                $detailResusitasi[] = 'Resusitasi: '
                    . $centangNilai($resusitasi, 'dilakukan') . ' Dilakukan   '
                    . $centangNilai($resusitasi, 'tidak_dilakukan') . ' Tidak dilakukan';

                foreach ([
                    'APGAR_LANGKAH_AWAL' => [
                        'label' => 'Langkah awal',
                        'detik' => 'APGAR_LANGKAH_AWAL_DETIK',
                    ],
                    'APGAR_VTP' => [
                        'label' => 'VTP',
                        'detik' => 'APGAR_VTP_DETIK',
                    ],
                    'APGAR_KOMPRESI_DADA' => [
                        'label' => 'Kompresi dada',
                        'detik' => 'APGAR_KOMPRESI_DADA_DETIK',
                    ],
                    'APGAR_ETT' => [
                        'label' => 'ETT',
                        'detik' => null,
                    ],
                ] as $kolom => $item) {
                    $dipilih = (int) ($dataAwalBayi->{$kolom} ?? 0) === 1;
                    $baris = $centangNilai($dipilih ? 1 : 0, 1)
                        . ' ' . $item['label'];

                    if ($item['detik']) {
                        $detik = $angkaAtauStrip(
                            $dataAwalBayi->{$item['detik']} ?? null
                        );

                        if ($detik !== '-') {
                            $baris .= ' selama ' . $detik . ' detik';
                        }
                    }

                    $detailResusitasi[] = $baris;
                }

                $dihentikan =
                    (int) ($dataAwalBayi->APGAR_RESUSITASI_DIHENTIKAN ?? 0) === 1;

                $barisBerhenti = $centangNilai($dihentikan ? 1 : 0, 1)
                    . ' Resusitasi dihentikan';

                $menitBerhenti = $angkaAtauStrip(
                    $dataAwalBayi->APGAR_RESUSITASI_DIHENTIKAN_MENIT ?? null
                );

                if ($menitBerhenti !== '-') {
                    $barisBerhenti .= ' pada menit ke-' . $menitBerhenti;
                }

                $detailResusitasi[] = $barisBerhenti;
            }

            $dataApgar['RESUSITASI_DETAIL'] = implode("\n", $detailResusitasi);
        }

        /*
        |--------------------------------------------------------------------------
        | ANAMNESIS DIPEROLEH
        |--------------------------------------------------------------------------
        */
        $anamnesisDiperoleh = DB::table(
            'medicalrecord.anamnesis_diperoleh'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first([
                'AUTOANAMNESIS',
                'ALLOANAMNESIS',
                'DARI',
            ]);

        $anamnesis = [];

        if ($anamnesisDiperoleh) {
            // $anamnesis[] = (int) $anamnesisDiperoleh->AUTOANAMNESIS;
            if ((int) $anamnesisDiperoleh->AUTOANAMNESIS === 1) {
                $anamnesis[] = 'Autoanamnesis';
            }

            if ((int) $anamnesisDiperoleh->ALLOANAMNESIS === 1) {
                $dari = $this->value($anamnesisDiperoleh->DARI);

                $anamnesis[] = 'Alloanamnesis'
                    . ($dari !== '' ? " dari {$dari}" : '');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | KELUHAN UTAMA
        |--------------------------------------------------------------------------
        */

        $keluhanUtama = DB::table('medicalrecord.keluhan_utama')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->value('DESKRIPSI');

        /*
        |--------------------------------------------------------------------------
        | RIWAYAT PENYAKIT SEKARANG
        |--------------------------------------------------------------------------
        */

        $riwayatPenyakitSekarang = DB::table('medicalrecord.anamnesis')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->value('DESKRIPSI');

        /*
        |--------------------------------------------------------------------------
        | RIWAYAT PENYAKIT DAHULU
        |--------------------------------------------------------------------------
        */

        $riwayatPenyakitDahulu = DB::table('medicalrecord.rpp')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->value('DESKRIPSI');

        /*
        |--------------------------------------------------------------------------
        | RIWAYAT PENYAKIT KELUARGA
        |--------------------------------------------------------------------------
        */

        $riwayatKeluarga = DB::table(
            'medicalrecord.riwayat_penyakit_keluarga'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first([
                'HIPERTENSI',
                'DIABETES_MELITUS',
                'PENYAKIT_JANTUNG',
                'ASMA',
                'LAINNYA',
            ]);

        $daftarRiwayatKeluarga = [];

        if ($riwayatKeluarga) {
            if ((int) $riwayatKeluarga->HIPERTENSI === 1) {
                $daftarRiwayatKeluarga[] = 'Hipertensi';
            }

            if ((int) $riwayatKeluarga->DIABETES_MELITUS === 1) {
                $daftarRiwayatKeluarga[] = 'Diabetes Melitus';
            }

            if ((int) $riwayatKeluarga->PENYAKIT_JANTUNG === 1) {
                $daftarRiwayatKeluarga[] = 'Penyakit Jantung';
            }

            if ((int) $riwayatKeluarga->ASMA === 1) {
                $daftarRiwayatKeluarga[] = 'Asma';
            }

            $lainnya = $this->value($riwayatKeluarga->LAINNYA);

            if ($lainnya !== '') {
                $daftarRiwayatKeluarga[] = $lainnya;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | TANDA VITAL
        |--------------------------------------------------------------------------
        */

        $tandaVital = DB::table('medicalrecord.tanda_vital')
            ->where('KUNJUNGAN', $kunjungan)
            ->whereIn('STATUS', [1, 2])
            ->orderByDesc('ID')
            ->first([
                'KEADAAN_UMUM',
                'SISTOLIK',
                'DISTOLIK',
                'FREKUENSI_NADI',
                'FREKUENSI_NADI_CB',
                'SUHU',
                'SATURASI_O2',
                'FREKUENSI_NAFAS',
                'FREKUENSI_NAFAS_CB',
                'EYE',
                'VERBAL',
                'MOTORIK',
                'GCS',
                'KESADARAN_NEONATUS', // kolom ini sebelumnya belum diambil
            ]);

        $nutrisi = DB::table('medicalrecord.nutrisi')
            ->where('KUNJUNGAN', $kunjungan)
            ->whereIn('STATUS', [1, 2])
            ->orderByDesc('ID')
            ->first([
                'BERAT_BADAN',
                'TINGGI_BADAN',
                'INDEX_MASSA_TUBUH',
            ]);

        $angkaAtauStrip = static function ($nilai): string {
            return $nilai !== null && $nilai !== ''
                ? (string) (int) round((float) $nilai)
                : '-';
        };

        $sistolik = $angkaAtauStrip($tandaVital->SISTOLIK ?? null);
        $diastolik = $angkaAtauStrip($tandaVital->DISTOLIK ?? null);

        $tekananDarah = $sistolik !== '-' || $diastolik !== '-'
            ? $sistolik . '/' . $diastolik . ' mmHg'
            : '-';

        $nadiReguler = match ((string) ($tandaVital->FREKUENSI_NADI_CB ?? '')) {
            '1' => 'Reguler',
            '2' => 'Ireguler',
            default => '',
        };

        $nadi = $angkaAtauStrip($tandaVital->FREKUENSI_NADI ?? null);
        $nadi = $nadi !== '-'
            ? $nadi . ' x/menit' . ($nadiReguler !== '' ? ' (' . $nadiReguler . ')' : '')
            : '-';

        $napasSimetris = match ((string) ($tandaVital->FREKUENSI_NAFAS_CB ?? '')) {
            '1' => 'Simetris',
            '2' => 'Asimetris',
            default => '',
        };

        $frekuensiNapas = $angkaAtauStrip(
            $tandaVital->FREKUENSI_NAFAS ?? null
        );

        $frekuensiNapas = $frekuensiNapas !== '-'
            ? $frekuensiNapas . ' x/menit'
                . ($napasSimetris !== '' ? ' (' . $napasSimetris . ')' : '')
            : '-';

        /*
        |--------------------------------------------------------------------------
        | KESADARAN NEONATUS / GCS
        |--------------------------------------------------------------------------
        */
        $kodeKesadaranNeonatus = $tandaVital->KESADARAN_NEONATUS;

        $labelKesadaranNeonatus = [
            1 => 'S1 - Mata tertutup, menangis -, gerak -',
            2 => 'S2 - Mata tertutup, menangis -, gerak +',
            3 => 'S3 - Mata terbuka, menangis -, gerak -',
            4 => 'S4 - Mata terbuka, menangis -, gerak +',
            5 => 'S5 - Mata terbuka, menangis +, gerak +',
        ];

        if (isset($labelKesadaranNeonatus[$kodeKesadaranNeonatus])) {
            $kesadaranGcs = $labelKesadaranNeonatus[$kodeKesadaranNeonatus];
        } else {
            $eye = (int) ($tandaVital->EYE ?? 0);
            $verbal = (int) ($tandaVital->VERBAL ?? 0);
            $motorik = (int) ($tandaVital->MOTORIK ?? 0);
            $gcs = (int) ($tandaVital->GCS ?? 0);

            $kesadaranGcs = ($eye || $verbal || $motorik || $gcs)
                ? "E{$eye} V{$verbal} M{$motorik} (GCS {$gcs})"
                : '-';
        }

        /*
        |--------------------------------------------------------------------------
        | PEMERIKSAAN FISIK NEONATUS
        |--------------------------------------------------------------------------
        */
        $pemeriksaanFisikNeo = DB::table(
            'medicalrecord.sirmed_pemeriksaan_fisik_neonatus'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first();

        $pfnText = function (string $kolom) use ($pemeriksaanFisikNeo): string {
            if (!$pemeriksaanFisikNeo) {
                return '';
            }

            return $this->placeholder(
                $pemeriksaanFisikNeo->{$kolom} ?? null
            );
        };

        $pfnCentang = function (string $kolom) use ($pemeriksaanFisikNeo): string {
            if (!$pemeriksaanFisikNeo) {
                return '';
            }

            return (int) ($pemeriksaanFisikNeo->{$kolom} ?? 0) === 1
                ? '☑'
                : '☐';
        };

        /*
        |--------------------------------------------------------------------------
        | DIAGNOSIS ICD
        |--------------------------------------------------------------------------
        */

        $nopen = DB::table('pendaftaran.kunjungan')
            ->where('NOMOR', $kunjungan)
            ->value('NOPEN');

        $diagnosis = collect();

        if ($nopen) {
            $diagnosis = DB::table('medicalrecord.diagnosa as diag')
                ->leftJoin('master.mrconso as mrc', function ($join) {
                    $join->on('diag.KODE', '=', 'mrc.CODE')
                        ->whereNotIn('mrc.TTY', ['HT', 'PS'])
                        ->where(function ($query) {
                            $query->where('mrc.SAB', 'ICD10_2020')
                                ->orWhere(function ($query) {
                                    $query->where(
                                        'mrc.SAB',
                                        'ICD10_1998'
                                    )
                                    ->whereNotExists(function ($subquery) {
                                        $subquery->select(DB::raw(1))
                                            ->from('master.mrconso as mrc2020')
                                            ->whereColumn(
                                                'mrc2020.CODE',
                                                'diag.KODE'
                                            )
                                            ->where(
                                                'mrc2020.SAB',
                                                'ICD10_2020'
                                            )
                                            ->whereNotIn(
                                                'mrc2020.TTY',
                                                ['HT', 'PS']
                                            );
                                    });
                                });
                        });
                })
                ->where('diag.NOPEN', $nopen)
                ->where('diag.STATUS', 1)
                ->select([
                    'diag.DIAGNOSA',
                    'diag.KODE',
                    'diag.UTAMA',
                    'mrc.STR as NAMA_DIAGNOSA',
                ])
                ->get();
        }

        $daftarDiagnosis = [];

        foreach ($diagnosis as $item) {
            $nama = $this->value(
                $item->NAMA_DIAGNOSA ?: $item->DIAGNOSA
            );

            if ($nama === '') {
                continue;
            }

            $kode = $this->value($item->KODE);

            $jenis = (int) $item->UTAMA === 1
                ? 'Utama'
                : 'Sekunder';

            $daftarDiagnosis[] = $jenis . ': ' . $nama
                . ($kode !== '' ? " [{$kode}]" : '');
        }

        /*
        |--------------------------------------------------------------------------
        | TATA LAKSANA, TARGET TERAPI, KONSULTASI
        |--------------------------------------------------------------------------
        */
        $tataLaksana = DB::table(
            'medicalrecord.sirmed_tata_laksana_terapi'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->value('DESKRIPSI');

        $targetTerapi = DB::table(
            'medicalrecord.sirmed_target_terapi'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->value('DESKRIPSI');

        $rencanaKonsultasi = DB::table(
            'medicalrecord.sirmed_rencana_konsultasi'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->value('DESKRIPSI');

        /*
        |--------------------------------------------------------------------------
        | KRITERIA PULANG
        |--------------------------------------------------------------------------
        */
        $kriteriaPulang = DB::table(
            'medicalrecord.sirmed_kriteria_pulang'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first([
                'KRITERIA_PULANG',
                'HARI',
                'KARENA',
            ]);

        $nilaiKriteriaPulang = $kriteriaPulang->KRITERIA_PULANG ?? null;

        $kriteriaPulangText = match ((string) $nilaiKriteriaPulang) {
            '1' => 'Sudah bisa ditetapkan',
            '0' => 'Belum bisa ditetapkan',
            default => '',
        };

        $kriteriaPulangHari = (
            (string) $nilaiKriteriaPulang === '1' &&
            !empty($kriteriaPulang->HARI)
        )
            ? ' (' . $kriteriaPulang->HARI . ' Hari)'
            : '';

        $kriteriaPulangKarena = (
            (string) $nilaiKriteriaPulang === '0'
        )
            ? $this->value($kriteriaPulang->KARENA ?? null)
            : '';

        /*
        |--------------------------------------------------------------------------
        | FORMAT TANDA VITAL
        |--------------------------------------------------------------------------
        */

        $tekananDarah = '-';

        if (
            $tandaVital &&
            (
                $tandaVital->SISTOLIK !== null ||
                $tandaVital->DISTOLIK !== null
            )
        ) {
            $tekananDarah =
                ((int) $tandaVital->SISTOLIK ?? '-')
                . '/'
                . ((int) $tandaVital->DISTOLIK ?? '-')
                . ' mmHg';
        }

        $nadi = '-';

        if ($tandaVital && $tandaVital->FREKUENSI_NADI !== null) {
            $nadi = (int) $tandaVital->FREKUENSI_NADI . ' x/menit';

            if ($tandaVital->FREKUENSI_NADI_CB) {
                $nadi .= ' (' . ($tandaVital->FREKUENSI_NADI_CB == 1 ? 'Reguler' : 'Ireguler')  . ')';
            }
        }

        $frekuensiNapas = '-';

        if ($tandaVital && $tandaVital->FREKUENSI_NAFAS !== null) {
            $frekuensiNapas = (int) $tandaVital->FREKUENSI_NAFAS . ' x/menit';

            if ($tandaVital->FREKUENSI_NAFAS_CB) {
                $frekuensiNapas .=
                    ' (' . ($tandaVital->FREKUENSI_NAFAS_CB == 1 ? 'Simetris' : 'Asimetris') . ')';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | PEMERIKSAAN PENUNJANG
        |--------------------------------------------------------------------------
        */

        // Hasil laboratorium
        $hasilLaboratorium = $this->formatHasilLaboratorium(
            $this->getRiwayatLab($kunjungan)
        );

        // Hasil radiologi
        $hasilRadiologi = $this->formatHasilRadiologi(
            $this->getRiwayatRad($kunjungan)
        );

        // print_r($hasilLaboratorium);
        // die();

        /*
        |--------------------------------------------------------------------------
        | RETURN DATA TEMPLATE
        |--------------------------------------------------------------------------
        */

        return [

            'STATUS_OBSTETRI' => $this->placeholder(
                implode("\n", $detailStatusObstetri)
            ),

            'STATUS_NEONATUS' => $this->placeholder(
                implode("\n", $detailStatusNeonatus)
            ),

            'BAYI_BUGAR' => $this->placeholder($dataApgar['BAYI_BUGAR'] ?? ''),
            'BAYI_TIDAK_BUGAR' => $this->placeholder($dataApgar['BAYI_TIDAK_BUGAR'] ?? ''),

            'APGAR_1_DENYUT' => $this->placeholder($dataApgar['APGAR_1_DENYUT'] ?? '-'),
            'APGAR_5_DENYUT' => $this->placeholder($dataApgar['APGAR_5_DENYUT'] ?? '-'),
            'APGAR_10_DENYUT' => $this->placeholder($dataApgar['APGAR_10_DENYUT'] ?? '-'),

            'APGAR_1_PERNAFASAN' => $this->placeholder($dataApgar['APGAR_1_PERNAFASAN'] ?? '-'),
            'APGAR_5_PERNAFASAN' => $this->placeholder($dataApgar['APGAR_5_PERNAFASAN'] ?? '-'),
            'APGAR_10_PERNAFASAN' => $this->placeholder($dataApgar['APGAR_10_PERNAFASAN'] ?? '-'),

            'APGAR_1_TONUS' => $this->placeholder($dataApgar['APGAR_1_TONUS'] ?? '-'),
            'APGAR_5_TONUS' => $this->placeholder($dataApgar['APGAR_5_TONUS'] ?? '-'),
            'APGAR_10_TONUS' => $this->placeholder($dataApgar['APGAR_10_TONUS'] ?? '-'),

            'APGAR_1_RANGSANG' => $this->placeholder($dataApgar['APGAR_1_RANGSANG'] ?? '-'),
            'APGAR_5_RANGSANG' => $this->placeholder($dataApgar['APGAR_5_RANGSANG'] ?? '-'),
            'APGAR_10_RANGSANG' => $this->placeholder($dataApgar['APGAR_10_RANGSANG'] ?? '-'),

            'APGAR_1_WARNA' => $this->placeholder($dataApgar['APGAR_1_WARNA'] ?? '-'),
            'APGAR_5_WARNA' => $this->placeholder($dataApgar['APGAR_5_WARNA'] ?? '-'),
            'APGAR_10_WARNA' => $this->placeholder($dataApgar['APGAR_10_WARNA'] ?? '-'),

            'APGAR_TOTAL_1' => $this->placeholder($dataApgar['APGAR_TOTAL_1'] ?? '-'),
            'APGAR_TOTAL_5' => $this->placeholder($dataApgar['APGAR_TOTAL_5'] ?? '-'),
            'APGAR_TOTAL_10' => $this->placeholder($dataApgar['APGAR_TOTAL_10'] ?? '-'),

            'RESUSITASI_DETAIL' => $this->placeholder(
                $dataApgar['RESUSITASI_DETAIL'] ?? ''
            ),
            /*
            | ANAMNESIS
            */

            'ANAMNESIS_DIPEROLEH' => $this->placeholder(
                implode(', ', $anamnesis)
            ),
            'KELUHAN_UTAMA' => $this->placeholder($keluhanUtama),
            'RIWAYAT_PENYAKIT_SEKARANG' => $this->placeholder(
                $riwayatPenyakitSekarang
            ),
            'RIWAYAT_PENYAKIT_DAHULU' => $this->placeholder(
                $riwayatPenyakitDahulu
            ),
            'RIWAYAT_PENYAKIT_KELUARGA' => $this->placeholder(
                implode(', ', $daftarRiwayatKeluarga)
            ),

            /*
            | TANDA VITAL DAN ANTROPOMETRI
            */

            'KEADAAN_UMUM' => $this->placeholder(
                $tandaVital->KEADAAN_UMUM ?? null
            ),

            'TEKANAN_DARAH' => $this->placeholder($tekananDarah),
            'NADI' => $this->placeholder($nadi),
            'FREKUENSI_NAPAS' => $this->placeholder($frekuensiNapas),

            'SUHU' => $tandaVital && $tandaVital->SUHU !== null
                ? $tandaVital->SUHU . ' °C'
                : '-',

            'SATURASI_O2' => $tandaVital
                ? $angkaAtauStrip($tandaVital->SATURASI_O2 ?? null) . ' %'
                : '-',

            'KESADARAN_GCS' => $this->placeholder($kesadaranGcs),

            'BERAT_BADAN' => $nutrisi && $nutrisi->BERAT_BADAN !== null
                ? $angkaAtauStrip($nutrisi->BERAT_BADAN) . ' kg'
                : '-',

            'TINGGI_BADAN' => $nutrisi && $nutrisi->TINGGI_BADAN !== null
                ? $angkaAtauStrip($nutrisi->TINGGI_BADAN) . ' cm'
                : '-',

            'IMT' => $nutrisi && $nutrisi->INDEX_MASSA_TUBUH !== null
                ? $angkaAtauStrip($nutrisi->INDEX_MASSA_TUBUH)
                : '-',

            /*
            | PEMERIKSAAN FISIK
            */
            'PFN_BENTUK' => $pfnText('BENTUK'),
            'PFN_SUTURAE' => $pfnText('SUTURAE'),
            'PFN_FONTANELLA' => $pfnText('FONTANELLA'),
            'PFN_MATA' => $pfnText('MATA'),
            'PFN_HIDUNG' => $pfnText('HIDUNG'),
            'PFN_CAPUT_SUCCEDANEUM' => $pfnText('CAPUT_SUCCEDANEUM'),
            'PFN_CEPHAL_HEMATOM' => $pfnText('CEPHAL_HEMATOM'),
            'PFN_TELINGA' => $pfnText('TELINGA'),
            'PFN_MULUT' => $pfnText('MULUT'),
            'PFN_LEHER' => $pfnText('LEHER'),
            'PFN_PARU' => $pfnText('PARU'),
            'PFN_JANTUNG' => $pfnText('JANTUNG'),
            'PFN_ABDOMEN' => $pfnText('ABDOMEN'),
            'PFN_EKSTREMITAS' => $pfnText('EKSTREMITAS'),
            'PFN_KULIT_KETERANGAN' => $pfnText('KULIT_KETERANGAN'),

            // Pemeriksaan fisik neonatus: checkbox neurologi
            'PFN_ROOTING' => $pfnCentang('ROOTING'),
            'PFN_SUCKING' => $pfnCentang('SUCKING'),
            'PFN_MORO' => $pfnCentang('MORO'),
            'PFN_ATN' => $pfnCentang('ASYMMETRIC_TONIC_NECK'),
            'PFN_BABINSKI' => $pfnCentang('BABINSKI'),
            'PFN_MENGGENGGAM' => $pfnCentang('MENGGENGGAM'),

            // Pemeriksaan fisik neonatus: checkbox suara
            'PFN_SUARA_DIAM' => $pfnCentang('SUARA_DIAM'),
            'PFN_SUARA_MERINTIH' => $pfnCentang('SUARA_MERINTIH'),
            'PFN_SUARA_KUAT' => $pfnCentang('SUARA_KUAT'),

            // Pemeriksaan fisik neonatus: checkbox kulit
            'PFN_IKTERIK' => $pfnCentang('IKRENIK'),

            /*
            |--------------------------------------------------------------------------
            | PEMERIKSAAN PENUNJANG
            |--------------------------------------------------------------------------
            */
            'HASIL_LABORATORIUM' => $hasilLaboratorium,
            'HASIL_RADIOLOGI' => $hasilRadiologi,

            /*
            | DIAGNOSIS
            */

            'DIAGNOSIS' => $this->placeholder(
                implode("\n", $daftarDiagnosis)
            ),

            /*
            | TATA LAKSANA DAN RENCANA
            */

            'TATA_LAKSANA_TERAPI' => $this->placeholder($tataLaksana),
            'TARGET_TERAPI' => $this->placeholder($targetTerapi),
            'RENCANA_KONSULTASI' => $this->placeholder($rencanaKonsultasi),

            'KRITERIA_PULANG' => $this->placeholder(
                $kriteriaPulangText
            ),

            'KRITERIA_PULANG_HARI' => $this->placeholder(
                $kriteriaPulangHari
            ),

            'KRITERIA_PULANG_KARENA' => $this->placeholder(
                $kriteriaPulangKarena
            ),

            /*
            | TANDA TANGAN USER FINALISASI
            */
            'NAMA_DOKTER_FINALISASI' => $finalisasi ? $finalisasi->NAMA_USER_FINALISASI : null,
            'TTD_DOKTER_FINALISASI' => $ttd_finalisasi ? $ttd_finalisasi->signature_path : null,
            'NAMA_PERAWAT_FINALISASI' => null,
            'TTD_PERAWAT_FINALISASI' => null,
        ];
    }

    /**
     * ========================================================================
     * DATA PERAWAT
     * ========================================================================
     */
    private function getDataPerawat(string $kunjungan): array
    {
        /*
        |--------------------------------------------------------------------------
        | TTD USER FINALISASI
        |--------------------------------------------------------------------------
        */
        $finalisasi_dokter = DB::table(
            'simrspku_pengkajian.finalisasi as fin'
        )
            ->leftJoin('aplikasi.pengguna as usr', 'usr.ID', '=', 'fin.USER_CREATED')
            ->select('fin.*', 'usr.NIP as NIP_USER_FINALISASI', DB::raw('master.getNamaLengkapPegawai(usr.NIP) as NAMA_USER_FINALISASI'))
            ->where('fin.KUNJUNGAN', $kunjungan)
            ->where('fin.FORM', 'pengkajian-ranap-neonatus')
            ->where('fin.SUB', 'DOKTER')
            ->where('fin.STATUS', 2)
            ->first();
        if ($finalisasi_dokter) {
            $ttd_finalisasi_dokter = DB::table('simrspku_klaim.tanda_tangan_pegawai')
                ->where('nip', $finalisasi_dokter->NIP_USER_FINALISASI)
                // ->where('nip', 1912314)
                ->where('status', 1)
                ->whereNull('deleted_at')
                ->inRandomOrder()
                ->first();
        } else {
            $ttd_finalisasi_dokter = '';
        }

        $finalisasi = DB::table(
            'simrspku_pengkajian.finalisasi as fin'
        )
            ->leftJoin('aplikasi.pengguna as usr', 'usr.ID', '=', 'fin.USER_CREATED')
            ->select('fin.*', 'usr.NIP as NIP_USER_FINALISASI', DB::raw('master.getNamaLengkapPegawai(usr.NIP) as NAMA_USER_FINALISASI'))
            ->where('fin.KUNJUNGAN', $kunjungan)
            ->where('fin.FORM', 'pengkajian-ranap-neonatus')
            ->where('fin.SUB', 'PERAWAT')
            ->where('fin.STATUS', 2)
            ->first();

        $ttd_finalisasi = DB::table('simrspku_klaim.tanda_tangan_pegawai')
            ->where('nip', $finalisasi->NIP_USER_FINALISASI)
            // ->where('nip', 1912314)
            ->where('status', 1)
            ->whereNull('deleted_at')
            ->inRandomOrder()
            ->first();

        /*
        |--------------------------------------------------------------------------
        | ANAMNESIS DIPEROLEH
        |--------------------------------------------------------------------------
        */
        $anamnesisDiperoleh = DB::table(
            'medicalrecord.anamnesis_diperoleh'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first([
                'AUTOANAMNESIS',
                'ALLOANAMNESIS',
                'DARI',
            ]);

        $anamnesis = [];

        if ($anamnesisDiperoleh) {
            // $anamnesis[] = (int) $anamnesisDiperoleh->AUTOANAMNESIS;
            if ((int) $anamnesisDiperoleh->AUTOANAMNESIS === 1) {
                $anamnesis[] = 'Autoanamnesis';
            }

            if ((int) $anamnesisDiperoleh->ALLOANAMNESIS === 1) {
                $dari = $this->value($anamnesisDiperoleh->DARI);

                $anamnesis[] = 'Alloanamnesis'
                    . ($dari !== '' ? " dari {$dari}" : '');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | KELUHAN UTAMA
        |--------------------------------------------------------------------------
        */

        $keluhanUtama = DB::table('medicalrecord.keluhan_utama')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->value('DESKRIPSI');

        /*
        |--------------------------------------------------------------------------
        | RIWAYAT PENYAKIT SEKARANG
        |--------------------------------------------------------------------------
        */

        $riwayatPenyakitSekarang = DB::table('medicalrecord.anamnesis')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->value('DESKRIPSI');

        /*
        |--------------------------------------------------------------------------
        | RIWAYAT PENYAKIT DAHULU
        |--------------------------------------------------------------------------
        */

        $riwayatPenyakitDahulu = DB::table('medicalrecord.rpp')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->value('DESKRIPSI');

        /*
        |--------------------------------------------------------------------------
        | RIWAYAT PENYAKIT KELUARGA
        |--------------------------------------------------------------------------
        */

        $riwayatKeluarga = DB::table(
            'medicalrecord.riwayat_penyakit_keluarga'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first([
                'HIPERTENSI',
                'DIABETES_MELITUS',
                'PENYAKIT_JANTUNG',
                'ASMA',
                'LAINNYA',
            ]);

        $daftarRiwayatKeluarga = [];

        if ($riwayatKeluarga) {
            if ((int) $riwayatKeluarga->HIPERTENSI === 1) {
                $daftarRiwayatKeluarga[] = 'Hipertensi';
            }

            if ((int) $riwayatKeluarga->DIABETES_MELITUS === 1) {
                $daftarRiwayatKeluarga[] = 'Diabetes Melitus';
            }

            if ((int) $riwayatKeluarga->PENYAKIT_JANTUNG === 1) {
                $daftarRiwayatKeluarga[] = 'Penyakit Jantung';
            }

            if ((int) $riwayatKeluarga->ASMA === 1) {
                $daftarRiwayatKeluarga[] = 'Asma';
            }

            $lainnya = $this->value($riwayatKeluarga->LAINNYA);

            if ($lainnya !== '') {
                $daftarRiwayatKeluarga[] = $lainnya;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | RIWAYAT TUMBUH KEMBANG, KELAHIRAN, DAN IMUNISASI
        |--------------------------------------------------------------------------
        */
        $statusReproduksi = DB::table('medicalrecord.sirmed_status_reproduksi')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first([
                'RIWAYAT_TUMBUH_KEMBANG',
                'RIWAYAT_KELAHIRAN',
                'USIA_KEHAMILAN',
                'PERSALINAN',
                'PERSALINAN_LAINNYA',
            ]);

        $riwayatImunisasi = DB::table('medicalrecord.riwayat_tumbuh_kembang')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first([
                'IMUNISASI',
                'IMUNISASI_LAIN',
            ]);

        $persalinan = match ((string) ($statusReproduksi->PERSALINAN ?? '')) {
            '1' => 'Sectio Caesarea',
            '2' => 'Spontan',
            '3' => 'Vacum',
            '4' => 'Lainnya'
                . (($detail = $this->value(
                    $statusReproduksi->PERSALINAN_LAINNYA ?? null
                )) !== '' ? ': ' . $detail : ''),
            default => '',
        };

        $imunisasi = match ((string) ($riwayatImunisasi->IMUNISASI ?? '')) {
            '1' => 'Imunisasi Dasar Lengkap',
            '2' => 'Imunisasi Dasar Tidak Lengkap',
            '3' => 'Tidak Imunisasi',
            '4' => 'Lain-lain'
                . (($detail = $this->value(
                    $riwayatImunisasi->IMUNISASI_LAIN ?? null
                )) !== '' ? ': ' . $detail : ''),
            default => '',
        };

        /*
        |--------------------------------------------------------------------------
        | PENILAIAN AWAL BAYI BARU LAHIR
        |--------------------------------------------------------------------------
        */
        $dataAwalBayi = DB::table('medicalrecord.sirmed_penilaian_awal_bayi')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first();

        $angkaAtauStrip = static function ($nilai): string {
            return $nilai !== null && $nilai !== ''
                ? (string) (int) round((float) $nilai)
                : '-';
        };

        $centangNilai = static function ($nilai, $pilihan): string {
            return (string) ($nilai ?? '') === (string) $pilihan ? '☑' : '☐';
        };

        $dataApgar = [];

        if ($dataAwalBayi) {
            foreach ([1, 5, 10] as $menit) {
                $prefix = "APGAR_{$menit}_MENIT_";

                foreach ([
                    'DENYUT' => 'DENYUT',
                    'PERNAFASAN' => 'PERNAFASAN',
                    'TONUS' => 'TONUS',
                    'RANGSANG' => 'RANGSANG',
                    'WARNA' => 'WARNA',
                ] as $key => $kolom) {
                    $dataApgar["APGAR_{$menit}_{$key}"] =
                        $angkaAtauStrip($dataAwalBayi->{$prefix . $kolom} ?? null);
                }

                $dataApgar["APGAR_TOTAL_{$menit}"] =
                    $angkaAtauStrip(
                        $dataAwalBayi->{"APGAR_TOTAL_{$menit}_MENIT"} ?? null
                    );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | INTRA NATAL DAN POST NATAL
        |--------------------------------------------------------------------------
        */
        $centangPilihanNatal = static function ($nilai, array $pilihan): string {
            $hasil = [];

            foreach ($pilihan as $value => $label) {
                $hasil[] = ((string) ($nilai ?? '') === (string) $value ? '☑' : '☐')
                    . ' ' . $label;
            }

            return implode('   ', $hasil);
        };

        $angkaNatal = static function ($nilai): string {
            return $nilai !== null && $nilai !== ''
                ? (string) (int) round((float) $nilai)
                : '';
        };

        $intraUmurKehamilan = '';
        $intraJenisPersalinan = '';
        $intraPenyulitPersalinan = '';
        $intraKomplikasiPersalinan = '';

        $postBbl = '';
        $postPbl = '';
        $postLk = '';
        $postLd = '';
        $postLp = '';
        $postLila = '';
        $postTraumaLahir = '';
        $postUsahaNafas = '';

        if ($dataAwalBayi) {
            $intraUmurKehamilan = $centangPilihanNatal(
                $dataAwalBayi->UMUR_KEHAMILAN ?? null,
                [
                    1 => 'Kurang Bulan',
                    2 => 'Cukup Bulan',
                    3 => 'Sesuai Kelahiran',
                    4 => 'Serotinus',
                ]
            );

            $intraJenisPersalinan = $centangPilihanNatal(
                $dataAwalBayi->JENIS_PERSALINAN ?? null,
                [
                    1 => 'Spontan',
                    2 => 'Forcep',
                    3 => 'VE',
                    4 => 'SC',
                ]
            );

            $intraPenyulitPersalinan = $centangPilihanNatal(
                $dataAwalBayi->PENYULIT_PERSALINAN ?? null,
                [
                    1 => 'Preskep',
                    2 => 'Lintang',
                    3 => 'Gemelli',
                    4 => 'Lainnya',
                ]
            );

            $penyulitLain = $this->value(
                $dataAwalBayi->PENYULIT_LAIN ?? null
            );

            if ($penyulitLain !== '') {
                $intraPenyulitPersalinan .= ' (' . $penyulitLain . ')';
            }

            $intraKomplikasiPersalinan = $centangPilihanNatal(
                $dataAwalBayi->KOMPLIKASI_PERSALINAN ?? null,
                [
                    1 => 'KPD',
                    2 => 'Eklamsia/Preeklamsia',
                    3 => 'Solusio Plasenta',
                    4 => 'Placenta Previa',
                    5 => 'Lainnya',
                ]
            );

            $komplikasiLain = $this->value(
                $dataAwalBayi->KOMPLIKASI_LAIN ?? null
            );

            if ($komplikasiLain !== '') {
                $intraKomplikasiPersalinan .= ' (' . $komplikasiLain . ')';
            }

            $postBbl = $angkaNatal($dataAwalBayi->BBL ?? null);
            $postPbl = $angkaNatal($dataAwalBayi->PBL ?? null);
            $postLk = $angkaNatal($dataAwalBayi->LK ?? null);
            $postLd = $angkaNatal($dataAwalBayi->LD ?? null);
            $postLp = $angkaNatal($dataAwalBayi->LP ?? null);
            $postLila = $angkaNatal($dataAwalBayi->LILA ?? null);

            $postTraumaLahir = $centangPilihanNatal(
                $dataAwalBayi->TRAUMA_LAHIR ?? null,
                [
                    1 => 'Tidak Ada',
                    2 => 'Ada',
                ]
            );

            $postUsahaNafas = $centangPilihanNatal(
                $dataAwalBayi->USAHA_NAFAS ?? null,
                [
                    1 => 'Spontan',
                    2 => 'Dengan Bantuan',
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | TANDA VITAL
        |--------------------------------------------------------------------------
        */

        $tandaVital = DB::table('medicalrecord.tanda_vital')
            ->where('KUNJUNGAN', $kunjungan)
            ->whereIn('STATUS', [1, 2])
            ->orderByDesc('ID')
            ->first([
                'KEADAAN_UMUM',
                'SISTOLIK',
                'DISTOLIK',
                'FREKUENSI_NADI',
                'FREKUENSI_NADI_CB',
                'SUHU',
                'SATURASI_O2',
                'FREKUENSI_NAFAS',
                'FREKUENSI_NAFAS_CB',
                'EYE',
                'VERBAL',
                'MOTORIK',
                'GCS',
                'KESADARAN_NEONATUS', // kolom ini sebelumnya belum diambil
            ]);

        $nutrisi = DB::table('medicalrecord.nutrisi')
            ->where('KUNJUNGAN', $kunjungan)
            ->whereIn('STATUS', [1, 2])
            ->orderByDesc('ID')
            ->first([
                'BERAT_BADAN',
                'TINGGI_BADAN',
                'INDEX_MASSA_TUBUH',
            ]);

        $angkaAtauStrip = static function ($nilai): string {
            return $nilai !== null && $nilai !== ''
                ? (string) (int) round((float) $nilai)
                : '-';
        };

        $sistolik = $angkaAtauStrip($tandaVital->SISTOLIK ?? null);
        $diastolik = $angkaAtauStrip($tandaVital->DISTOLIK ?? null);

        $tekananDarah = $sistolik !== '-' || $diastolik !== '-'
            ? $sistolik . '/' . $diastolik . ' mmHg'
            : '-';

        $nadiReguler = match ((string) ($tandaVital->FREKUENSI_NADI_CB ?? '')) {
            '1' => 'Reguler',
            '2' => 'Ireguler',
            default => '',
        };

        $nadi = $angkaAtauStrip($tandaVital->FREKUENSI_NADI ?? null);
        $nadi = $nadi !== '-'
            ? $nadi . ' x/menit' . ($nadiReguler !== '' ? ' (' . $nadiReguler . ')' : '')
            : '-';

        $napasSimetris = match ((string) ($tandaVital->FREKUENSI_NAFAS_CB ?? '')) {
            '1' => 'Simetris',
            '2' => 'Asimetris',
            default => '',
        };

        $frekuensiNapas = $angkaAtauStrip(
            $tandaVital->FREKUENSI_NAFAS ?? null
        );

        $frekuensiNapas = $frekuensiNapas !== '-'
            ? $frekuensiNapas . ' x/menit'
                . ($napasSimetris !== '' ? ' (' . $napasSimetris . ')' : '')
            : '-';

        /*
        |--------------------------------------------------------------------------
        | KESADARAN NEONATUS / GCS
        |--------------------------------------------------------------------------
        */
        $kodeKesadaranNeonatus = $tandaVital->KESADARAN_NEONATUS;

        $labelKesadaranNeonatus = [
            1 => 'S1 - Mata tertutup, menangis -, gerak -',
            2 => 'S2 - Mata tertutup, menangis -, gerak +',
            3 => 'S3 - Mata terbuka, menangis -, gerak -',
            4 => 'S4 - Mata terbuka, menangis -, gerak +',
            5 => 'S5 - Mata terbuka, menangis +, gerak +',
        ];

        if (isset($labelKesadaranNeonatus[$kodeKesadaranNeonatus])) {
            $kesadaranGcs = $labelKesadaranNeonatus[$kodeKesadaranNeonatus];
        } else {
            $eye = (int) ($tandaVital->EYE ?? 0);
            $verbal = (int) ($tandaVital->VERBAL ?? 0);
            $motorik = (int) ($tandaVital->MOTORIK ?? 0);
            $gcs = (int) ($tandaVital->GCS ?? 0);

            $kesadaranGcs = ($eye || $verbal || $motorik || $gcs)
                ? "E{$eye} V{$verbal} M{$motorik} (GCS {$gcs})"
                : '-';
        }

        /*
        |--------------------------------------------------------------------------
        | FORMAT TANDA VITAL
        |--------------------------------------------------------------------------
        */

        $tekananDarah = '-';

        if (
            $tandaVital &&
            (
                $tandaVital->SISTOLIK !== null ||
                $tandaVital->DISTOLIK !== null
            )
        ) {
            $tekananDarah =
                ((int) $tandaVital->SISTOLIK ?? '-')
                . '/'
                . ((int) $tandaVital->DISTOLIK ?? '-')
                . ' mmHg';
        }

        $nadi = '-';

        if ($tandaVital && $tandaVital->FREKUENSI_NADI !== null) {
            $nadi = (int) $tandaVital->FREKUENSI_NADI . ' x/menit';

            if ($tandaVital->FREKUENSI_NADI_CB) {
                $nadi .= ' (' . ($tandaVital->FREKUENSI_NADI_CB == 1 ? 'Reguler' : 'Ireguler')  . ')';
            }
        }

        $frekuensiNapas = '-';

        if ($tandaVital && $tandaVital->FREKUENSI_NAFAS !== null) {
            $frekuensiNapas = (int) $tandaVital->FREKUENSI_NAFAS . ' x/menit';

            if ($tandaVital->FREKUENSI_NAFAS_CB) {
                $frekuensiNapas .=
                    ' (' . ($tandaVital->FREKUENSI_NAFAS_CB == 1 ? 'Simetris' : 'Asimetris') . ')';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | PEMERIKSAAN FISIK PERAWAT NEONATUS
        |--------------------------------------------------------------------------
        */
        $pernafasan = DB::table('medicalrecord.sirmed_sistem_pernafasan')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first();

        $kardiovaskuler = DB::table('medicalrecord.sirmed_sistem_kardiovaskuler')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first();

        $persyarafan = DB::table('medicalrecord.sirmed_sistem_persyarafan')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first();

        $pencernaan = DB::table('medicalrecord.sirmed_sistem_gastrointestinal')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first();

        $perkemihan = DB::table('medicalrecord.sirmed_sistem_perkemihan')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first();

        $integumen = DB::table('medicalrecord.sirmed_sistem_muskuloskeletal_integumen')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first();

        $detailPemeriksaanFisikNeoPerawat = [];

        $adaDataPemeriksaanFisikNeo = $pernafasan
            || $kardiovaskuler
            || $persyarafan
            || $pencernaan
            || $perkemihan
            || $integumen;

        $nilaiFisikNeo = function ($row, string $kolom): string {
            return $this->value($row->{$kolom} ?? null);
        };

        $barisPilihanFisikNeo = static function (
            string $label,
            $nilai,
            array $pilihan
        ): string {
            $hasil = [];

            foreach ($pilihan as $value => $teks) {
                $hasil[] = ((string) ($nilai ?? '') === (string) $value ? '☑' : '☐')
                    . ' ' . $teks;
            }

            return $label . ': ' . implode('   ', $hasil);
        };

        $barisCentangFisikNeo = static function (
            string $label,
            $nilai
        ): string {
            return ((int) ($nilai ?? 0) === 1 ? '☑' : '☐') . ' ' . $label;
        };

        if ($adaDataPemeriksaanFisikNeo) {

            /*
            |--------------------------------------------------------------------------
            | SISTEM PERNAFASAN
            |--------------------------------------------------------------------------
            */
            if ($pernafasan) {
                $detailPemeriksaanFisikNeoPerawat[] = 'SISTEM PERNAFASAN';

                $detailPemeriksaanFisikNeoPerawat[] = $barisPilihanFisikNeo(
                    'Usaha napas',
                    $pernafasan->USAHA_NAFAS ?? null,
                    [
                        1 => 'Spontan',
                        2 => 'Bagging',
                        3 => 'Ventilator',
                    ]
                );

                $detailPemeriksaanFisikNeoPerawat[] = $barisPilihanFisikNeo(
                    'Tipe napas',
                    $pernafasan->TIPE_NAFAS ?? null,
                    [
                        1 => 'Perut',
                        2 => 'Dada',
                    ]
                );

                $frekuensi = $nilaiFisikNeo(
                    $pernafasan,
                    'TIPE_NAFAS_FREKUENSI'
                );

                if ($frekuensi !== '') {
                    $detailPemeriksaanFisikNeoPerawat[] =
                        'Frekuensi napas: ' . (int) round((float) $frekuensi)
                        . ' x/menit';
                }

                $detailPemeriksaanFisikNeoPerawat[] = $barisPilihanFisikNeo(
                    'Inspeksi',
                    $pernafasan->INSPEKSI ?? null,
                    [
                        4 => 'Normal',
                        1 => 'Lendir',
                        2 => 'Napas cuping hidung',
                        3 => 'Retraksi dada',
                    ]
                );

                $detailPemeriksaanFisikNeoPerawat[] = $barisPilihanFisikNeo(
                    'Palpasi',
                    $pernafasan->PALPASI ?? null,
                    [
                        1 => 'Simetris',
                        2 => 'Asimetris',
                        3 => 'Fremitus',
                        4 => 'Krepitasi',
                    ]
                );

                $detailPemeriksaanFisikNeoPerawat[] = $barisPilihanFisikNeo(
                    'Perkusi',
                    $pernafasan->PERKUSI ?? null,
                    [
                        1 => 'Sonor',
                        2 => 'Hipersonor',
                        3 => 'Redup',
                    ]
                );

                foreach ([
                    'VESIKULER' => [
                        'label' => 'Vesikuler',
                        'kanan' => 'AUSKULTASI_VESIKULER_KANAN',
                        'kiri' => 'AUSKULTASI_VESIKULER_KIRI',
                    ],
                    'RONCHI' => [
                        'label' => 'Ronchi',
                        'kanan' => 'AUSKULTASI_RONCHI_KANAN',
                        'kiri' => 'AUSKULTASI_RONCHI_KIRI',
                    ],
                    'WHEEZING' => [
                        'label' => 'Wheezing',
                        'kanan' => 'AUSKULTASI_WHEEZING_KANAN',
                        'kiri' => 'AUSKULTASI_WHEEZING_KIRI',
                    ],
                ] as $jenis => $item) {
                    $kolomCheckbox = 'AUSKULTASI_' . $jenis;

                    $detailPemeriksaanFisikNeoPerawat[] =
                        $barisCentangFisikNeo(
                            $item['label'],
                            $pernafasan->{$kolomCheckbox} ?? 0
                        )
                        . ' (Kanan: '
                        . $nilaiFisikNeo($pernafasan, $item['kanan'])
                        . ', Kiri: '
                        . $nilaiFisikNeo($pernafasan, $item['kiri'])
                        . ')';
                }
            }

            /*
            |--------------------------------------------------------------------------
            | SISTEM KARDIOVASKULER
            |--------------------------------------------------------------------------
            */
            if ($kardiovaskuler) {
                $detailPemeriksaanFisikNeoPerawat[] = '';
                $detailPemeriksaanFisikNeoPerawat[] = 'SISTEM KARDIOVASKULER';

                $detailPemeriksaanFisikNeoPerawat[] = $barisPilihanFisikNeo(
                    'Bunyi jantung',
                    $kardiovaskuler->BUNYI_JANTUNG ?? null,
                    [
                        1 => 'Normal',
                        2 => 'Abnormal',
                    ]
                );

                $detailPemeriksaanFisikNeoPerawat[] = $barisPilihanFisikNeo(
                    'Irama jantung',
                    $kardiovaskuler->IRAMA_JANTUNG ?? null,
                    [
                        1 => 'Reguler',
                        2 => 'Ireguler',
                    ]
                );

                $detailPemeriksaanFisikNeoPerawat[] = $barisPilihanFisikNeo(
                    'Pulsasi',
                    $kardiovaskuler->PULSASI ?? null,
                    [
                        1 => 'Sangat kuat',
                        2 => 'Kuat',
                        3 => 'Lemah',
                    ]
                );

                $detailPemeriksaanFisikNeoPerawat[] = $barisPilihanFisikNeo(
                    'Teraba',
                    $kardiovaskuler->TERABA ?? null,
                    [
                        3 => 'Teraba',
                        1 => 'Hilang timbul',
                        2 => 'Tidak teraba',
                    ]
                );

                $detailPemeriksaanFisikNeoPerawat[] = $barisPilihanFisikNeo(
                    'CRT',
                    $kardiovaskuler->CRT ?? null,
                    [
                        1 => '< 3 detik',
                        2 => '> 3 detik',
                    ]
                );

                $detailPemeriksaanFisikNeoPerawat[] = $barisPilihanFisikNeo(
                    'Akral',
                    $kardiovaskuler->AKRAL ?? null,
                    [
                        1 => 'Hangat',
                        2 => 'Dingin',
                    ]
                );

                $suhuAkral = $nilaiFisikNeo($kardiovaskuler, 'AKRAL_SUHU');

                if ($suhuAkral !== '') {
                    $detailPemeriksaanFisikNeoPerawat[] =
                        'Suhu akral: ' . $suhuAkral . ' °C';
                }
            }

            /*
            |--------------------------------------------------------------------------
            | SISTEM NEUROLOGIS
            |--------------------------------------------------------------------------
            */
            if ($persyarafan) {
                $detailPemeriksaanFisikNeoPerawat[] = '';
                $detailPemeriksaanFisikNeoPerawat[] = 'SISTEM NEUROLOGIS';

                $detailPemeriksaanFisikNeoPerawat[] = $barisPilihanFisikNeo(
                    'Kesadaran',
                    $persyarafan->KESADARAN ?? null,
                    [
                        1 => 'CM',
                        2 => 'Apatis',
                        3 => 'Somnolent',
                        4 => 'Sopor',
                        5 => 'Coma',
                    ]
                );

                $detailPemeriksaanFisikNeoPerawat[] = $barisPilihanFisikNeo(
                    'Sklera/konjungtiva',
                    $persyarafan->SKLERA ?? null,
                    [
                        1 => 'Normal',
                        2 => 'Anemia',
                        3 => 'Ikterus',
                    ]
                );

                $detailPemeriksaanFisikNeoPerawat[] = $barisPilihanFisikNeo(
                    'Pupil',
                    $persyarafan->PUPIL ?? null,
                    [
                        1 => 'Isokor',
                        2 => 'Anisokor',
                        3 => 'Midriasis',
                        4 => 'Miosis',
                    ]
                );

                foreach ([
                    'REFLEK_CAHAYA_KANAN' => 'Reflek cahaya kanan',
                    'REFLEK_CAHAYA_KIRI' => 'Reflek cahaya kiri',
                ] as $kolom => $label) {
                    $detailPemeriksaanFisikNeoPerawat[] = $barisPilihanFisikNeo(
                        $label,
                        $persyarafan->{$kolom} ?? null,
                        [
                            1 => 'Reaksi (+)',
                            2 => 'Reaksi (-)',
                        ]
                    );
                }

                foreach ([
                    'REFLEK_MORO' => 'Moro',
                    'REFLEK_MENGGENGGAM' => 'Menggenggam',
                    'REFLEK_BABINSKI' => 'Babinski',
                    'REFLEK_PALMAR' => 'Palmar grasp',
                    'REFLEK_ROOTING' => 'Rooting',
                ] as $kolom => $label) {
                    $detailPemeriksaanFisikNeoPerawat[] =
                        $barisCentangFisikNeo(
                            'Reflek ' . $label,
                            $persyarafan->{$kolom} ?? 0
                        );
                }

                $detailPemeriksaanFisikNeoPerawat[] = $barisPilihanFisikNeo(
                    'Kejang',
                    $persyarafan->KEJANG ?? null,
                    [
                        1 => 'Ya',
                        2 => 'Tidak',
                    ]
                );

                $lamaKejang = $nilaiFisikNeo($persyarafan, 'KEJANG_LAMA');

                if ($lamaKejang !== '') {
                    $detailPemeriksaanFisikNeoPerawat[] =
                        'Keterangan/lama kejang: ' . $lamaKejang;
                }

                foreach ([
                    'PERGERAKAN_KAKI_KANAN' => 'Pergerakan kaki kanan',
                    'PERGERAKAN_KAKI_KIRI' => 'Pergerakan kaki kiri',
                    'PERGERAKAN_TANGAN_KANAN' => 'Pergerakan tangan kanan',
                    'PERGERAKAN_TANGAN_KIRI' => 'Pergerakan tangan kiri',
                ] as $kolom => $label) {
                    $detailPemeriksaanFisikNeoPerawat[] = $barisPilihanFisikNeo(
                        $label,
                        $persyarafan->{$kolom} ?? null,
                        [
                            1 => 'Kuat',
                            2 => 'Lemah',
                        ]
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | SISTEM GASTROINTESTINAL
            |--------------------------------------------------------------------------
            */
            if ($pencernaan) {
                $detailPemeriksaanFisikNeoPerawat[] = '';
                $detailPemeriksaanFisikNeoPerawat[] = 'SISTEM GASTROINTESTINAL';

                $frekuensiBab = $nilaiFisikNeo($pencernaan, 'BAB_FREKUENSI');
                if ($frekuensiBab !== '') {
                    $detailPemeriksaanFisikNeoPerawat[] =
                        'Frekuensi BAB: ' . $frekuensiBab . ' x/hari';
                }

                $detailPemeriksaanFisikNeoPerawat[] = $barisPilihanFisikNeo(
                    'Konsistensi BAB',
                    $pencernaan->BAB_KONSISTENSI ?? null,
                    [
                        2 => 'Cair',
                        3 => 'Lembek',
                    ]
                );

                $warnaBab = $nilaiFisikNeo($pencernaan, 'BAB_WARNA');
                if ($warnaBab !== '') {
                    $detailPemeriksaanFisikNeoPerawat[] = 'Warna BAB: ' . $warnaBab;
                }

                $detailPemeriksaanFisikNeoPerawat[] = $barisPilihanFisikNeo(
                    'Muntah',
                    $pencernaan->MUNTAH ?? null,
                    [
                        1 => 'Tidak',
                        2 => 'Ya',
                    ]
                );

                $frekuensiMuntah = $nilaiFisikNeo(
                    $pencernaan,
                    'MUNTAH_FREKUENSI'
                );
                if ($frekuensiMuntah !== '') {
                    $detailPemeriksaanFisikNeoPerawat[] =
                        'Frekuensi muntah: ' . $frekuensiMuntah . ' x/hari';
                }

                $detailPemeriksaanFisikNeoPerawat[] = $barisPilihanFisikNeo(
                    'Inspeksi',
                    $pencernaan->INSPEKSI ?? null,
                    [
                        2 => 'Kembung',
                        3 => 'Datar',
                    ]
                );

                foreach ([
                    'PALPASI_HEPAR' => 'Palpasi hepar',
                    'PALPASI_LIEN_KANAN' => 'Palpasi lien kanan',
                    'PALPASI_LIEN_KIRI' => 'Palpasi lien kiri',
                ] as $kolom => $label) {
                    $detailPemeriksaanFisikNeoPerawat[] = $barisPilihanFisikNeo(
                        $label,
                        $pencernaan->{$kolom} ?? null,
                        [
                            1 => 'Teraba',
                            2 => 'Tidak teraba',
                        ]
                    );
                }

                $detailPemeriksaanFisikNeoPerawat[] = $barisPilihanFisikNeo(
                    'Perkusi',
                    $pencernaan->PERKUSI ?? null,
                    [
                        2 => 'Tympani',
                        3 => 'Pekak',
                    ]
                );

                $detailPemeriksaanFisikNeoPerawat[] = $barisPilihanFisikNeo(
                    'Auskultasi',
                    $pencernaan->AUSKULTASI ?? null,
                    [
                        1 => 'Bising',
                        2 => 'Peristaltik',
                    ]
                );

                $detailAuskultasi = $nilaiFisikNeo(
                    $pencernaan,
                    'AUSKULTASI_DETAIL'
                );
                if ($detailAuskultasi !== '') {
                    $detailPemeriksaanFisikNeoPerawat[] =
                        'Detail auskultasi: ' . $detailAuskultasi;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | SISTEM PERKEMIHAN
            |--------------------------------------------------------------------------
            */
            if ($perkemihan) {
                $detailPemeriksaanFisikNeoPerawat[] = '';
                $detailPemeriksaanFisikNeoPerawat[] = 'SISTEM PERKEMIHAN';

                $frekuensiBak = $nilaiFisikNeo($perkemihan, 'BAK_FREKUENSI');
                if ($frekuensiBak !== '') {
                    $detailPemeriksaanFisikNeoPerawat[] =
                        'Frekuensi BAK: ' . $frekuensiBak . ' x/hari';
                }

                $detailPemeriksaanFisikNeoPerawat[] = $barisPilihanFisikNeo(
                    'Buang air kecil',
                    $perkemihan->BAK ?? null,
                    [
                        1 => 'Lancar',
                        2 => 'Inkontinensia',
                        3 => 'Menetes',
                        4 => 'Retensi',
                        5 => 'Down catheter',
                    ]
                );

                $detailPemeriksaanFisikNeoPerawat[] = $barisPilihanFisikNeo(
                    'Warna urine',
                    $perkemihan->BAK_WARNA ?? null,
                    [
                        1 => 'Jernih',
                        2 => 'Keruh',
                    ]
                );
            }

            /*
            |--------------------------------------------------------------------------
            | SISTEM INTEGUMEN
            |--------------------------------------------------------------------------
            */
            if ($integumen) {
                $detailPemeriksaanFisikNeoPerawat[] = '';
                $detailPemeriksaanFisikNeoPerawat[] = 'SISTEM INTEGUMEN';

                $detailPemeriksaanFisikNeoPerawat[] = $barisPilihanFisikNeo(
                    'Warna kulit',
                    $integumen->WARNA ?? null,
                    [
                        1 => 'Normal',
                        2 => 'Pucat',
                        3 => 'Sianosis',
                        4 => 'Jaundice',
                    ]
                );

                $detailPemeriksaanFisikNeoPerawat[] = $barisPilihanFisikNeo(
                    'Turgor',
                    $integumen->TURGOR ?? null,
                    [
                        1 => 'Baik',
                        2 => 'Sedang',
                        3 => 'Buruk',
                    ]
                );

                $detailPemeriksaanFisikNeoPerawat[] = $barisPilihanFisikNeo(
                    'UKK',
                    $integumen->UKK ?? null,
                    [
                        1 => 'Ada',
                        2 => 'Tidak ada',
                    ]
                );
            }
        }

        // ==========================================================
        // HUBUNGAN STATUS PSIKOSOSIAL
        // ==========================================================
        $psikososial = DB::table('medicalrecord.kondisi_sosial')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first();

        // Referensi agama dan pekerjaan pasien
        $refPsikososial = DB::table('pendaftaran.kunjungan AS pk')
            ->leftJoin('pendaftaran.pendaftaran AS pd', 'pd.NOMOR', '=', 'pk.NOPEN')
            ->leftJoin('master.pasien AS p', 'p.NORM', '=', 'pd.NORM')
            ->leftJoin('master.referensi AS ag', function ($join) {
                $join->on('ag.ID', '=', 'p.AGAMA')
                    ->where('ag.JENIS', '=', '1');
            })
            ->leftJoin('master.referensi AS kj', function ($join) {
                $join->on('kj.ID', '=', 'p.PEKERJAAN')
                    ->where('kj.JENIS', '=', '4');
            })
            ->where('pk.NOMOR', $kunjungan)
            ->select(
                'ag.DESKRIPSI AS AGAMA',
                'kj.DESKRIPSI AS PEKERJAAN'
            )
            ->first();

        // Helper checkbox untuk pilihan tunggal
        $centangPsikososial = function ($aktual, $nilai) {
            return (string) $aktual === (string) $nilai ? '☑' : '☐';
        };

        // Helper checkbox untuk kolom boolean 0/1
        $centangBoolean = function ($nilai) {
            return (int) $nilai === 1 ? '☑' : '☐';
        };

        $detailPsikososial = [];

        if ($psikososial) {
            // ------------------------------------------------------
            // STATUS PSIKOLOGI
            // ------------------------------------------------------
            $detailPsikososial[] = 'STATUS PSIKOLOGI';

            $opsiPsikologi = [
                'TIDAK_ADA_KELAINAN' => 'Tidak ada kelainan',
                'MARAH' => 'Marah',
                'CEMAS' => 'Cemas',
                'TAKUT' => 'Takut',
                'SEDIH' => 'Sedih',
                'BUNUH_DIRI' => 'Kecenderungan bunuh diri',
            ];

            foreach (array_chunk($opsiPsikologi, 3, true) as $baris) {
                $pilihanPerBaris = [];

                foreach ($baris as $kolom => $label) {
                    $pilihanPerBaris[] = $centangBoolean(
                        $psikososial->{$kolom} ?? 0
                    ) . ' ' . $label;
                }

                $detailPsikososial[] = implode('    ', $pilihanPerBaris);
            }

            // Pilihan lainnya
            $detailPsikososial[] =
                $centangPsikososial(
                    !empty($psikososial->LAINNYA) ? 1 : 0,
                    1
                )
                . ' Lainnya: '
                . ($psikososial->LAINNYA ?: '-');

            $detailPsikososial[] = '';

            // ------------------------------------------------------
            // STATUS MENTAL
            // ------------------------------------------------------
            $detailPsikososial[] = 'STATUS MENTAL';

            $detailPsikososial[] =
                $centangPsikososial($psikososial->STATUS_MENTAL, 1)
                . ' Sadar dan orientasi baik';

            $detailPsikososial[] =
                $centangPsikososial($psikososial->STATUS_MENTAL, 2)
                . ' Ada masalah perilaku'
                . (
                    !empty($psikososial->MASALAH_PERILAKU)
                        ? ': ' . $psikososial->MASALAH_PERILAKU
                        : ''
                );

            $detailPsikososial[] =
                $centangPsikososial($psikososial->STATUS_MENTAL, 3)
                . ' Perilaku kekerasan yang dialami pasien sebelumnya'
                . (
                    !empty($psikososial->PERILAKU_KEKERASAN_DIALAMI_SEBELUMNYA)
                        ? ': ' . $psikososial->PERILAKU_KEKERASAN_DIALAMI_SEBELUMNYA
                        : ''
                );

            $detailPsikososial[] = '';

            // ------------------------------------------------------
            // HUBUNGAN SOSIAL
            // ------------------------------------------------------
            $detailPsikososial[] = 'HUBUNGAN SOSIAL';

            $detailPsikososial[] =
                'Hubungan pasien dengan anggota keluarga: '
                . $centangPsikososial(
                    $psikososial->HUBUNGAN_PASIEN_DENGAN_KELUARGA,
                    1
                ) . ' Baik  '
                . $centangPsikososial(
                    $psikososial->HUBUNGAN_PASIEN_DENGAN_KELUARGA,
                    2
                ) . ' Tidak baik';

            $detailPsikososial[] =
                'Tempat tinggal: '
                . $centangPsikososial($psikososial->TEMPAT_TINGGAL, 1)
                . ' Rumah  '
                . $centangPsikososial($psikososial->TEMPAT_TINGGAL, 2)
                . ' Panti  '
                . $centangPsikososial($psikososial->TEMPAT_TINGGAL, 3)
                . ' Lainnya'
                . (
                    !empty($psikososial->TEMPAT_TINGGAL_LAINNYA)
                        ? ': ' . $psikososial->TEMPAT_TINGGAL_LAINNYA
                        : ''
                );

            $detailPsikososial[] = '';

            // ------------------------------------------------------
            // HUBUNGAN SPIRITUAL
            // ------------------------------------------------------
            $detailPsikososial[] = 'HUBUNGAN SPIRITUAL';

            $detailPsikososial[] =
                'Agama: ' . ($refPsikososial->AGAMA ?? '-');

            $detailPsikososial[] =
                'Kebiasaan beribadah teratur: '
                . $centangPsikososial(
                    $psikososial->KEBIASAAN_BERIBADAH_TERATUR,
                    1
                ) . ' Ya  '
                . $centangPsikososial(
                    $psikososial->KEBIASAAN_BERIBADAH_TERATUR,
                    2
                ) . ' Tidak';

            $detailPsikososial[] =
                'Nilai-nilai kepercayaan: '
                . $centangPsikososial(
                    $psikososial->NILAI_KEPERCAYAAN,
                    1
                ) . ' Ada  '
                . $centangPsikososial(
                    $psikososial->NILAI_KEPERCAYAAN,
                    2
                ) . ' Tidak ada'
                . (
                    !empty($psikososial->NILAI_KEPERCAYAAN_DESKRIPSI)
                        ? ' — ' . $psikososial->NILAI_KEPERCAYAAN_DESKRIPSI
                        : ''
                );

            $detailPsikososial[] =
                'Pengambil keputusan dalam keluarga: '
                . ($psikososial->PENGAMBIL_KEPUTUSAN_DALAM_KELUARGA ?: '-');

            $detailPsikososial[] = '';

            // ------------------------------------------------------
            // EKONOMI
            // ------------------------------------------------------
            $detailPsikososial[] = 'EKONOMI';

            $detailPsikososial[] =
                'Pekerjaan: ' . ($refPsikososial->PEKERJAAN ?? '-');

            $detailPsikososial[] =
                'Penghasilan per bulan: '
                . $centangPsikososial($psikososial->PENGHASILAN_PERBULAN, 1)
                . ' < Rp. 5.000.000  '
                . $centangPsikososial($psikososial->PENGHASILAN_PERBULAN, 2)
                . ' Rp. 5.000.000 - Rp. 10.000.000  '
                . $centangPsikososial($psikososial->PENGHASILAN_PERBULAN, 3)
                . ' > Rp. 10.000.000';
        }

        $detailPsikososial = array_filter(
            $detailPsikososial,
            fn ($baris) => $baris !== null
        );

        $detailPsikososial = implode("\n", $detailPsikososial);


        // ==========================================================
        // STATUS FUNGSIONAL
        // ==========================================================
        $statusFungsional = DB::table('medicalrecord.status_fungsional')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first();

        $detailFungsional = [];

        if ($statusFungsional) {
            $detailFungsional[] = 'STATUS FUNGSIONAL';

            $detailFungsional[] = 'Alat bantu mobilitas: '
                . $centangBoolean($statusFungsional->TANPA_ALAT_BANTU)
                . ' Tanpa alat bantu  '
                . $centangBoolean($statusFungsional->TONGKAT)
                . ' Tongkat  '
                . $centangBoolean($statusFungsional->KURSI_RODA)
                . ' Kursi roda  '
                . $centangBoolean($statusFungsional->BRANKARD)
                . ' Brankard  '
                . $centangBoolean($statusFungsional->WALKER)
                . ' Walker';

            $detailFungsional[] =
                'Alat bantu lainnya: '
                . ($statusFungsional->ALAT_BANTU ?: '-');

            $detailFungsional[] =
                'Cacat tubuh: '
                . $centangBoolean($statusFungsional->CACAT_TUBUH_TIDAK)
                . ' Tidak  '
                . $centangBoolean($statusFungsional->CACAT_TUBUH_YA)
                . ' Ya';

            $detailFungsional[] =
                'Keterangan cacat tubuh: '
                . ($statusFungsional->KET_CACAT_TUBUH ?: '-');
        }

        $detailFungsional = implode("\n", $detailFungsional);

        /*
        |--------------------------------------------------------------------------
        | SKRINING NYERI
        |--------------------------------------------------------------------------
        */
        $nyeri = DB::table('medicalrecord.penilaian_nyeri')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first([
                'NYERI',
                'ONSET',
                'SKALA',
                'METODE',
                'SKOR1',
                'SKOR2',
                'SKOR3',
                'SKOR4',
                'SKOR5',
                'SKOR6',
                'PENCETUS',
                'GAMBARAN',
                'DURASI',
                'LOKASI',
            ]);

        $centangNyeri = static function ($nilai, $pilihan): string {
            return (string) ($nilai ?? '') === (string) $pilihan
                ? '☑'
                : '☐';
        };

        $detailNyeri = [];

        if ($nyeri) {

            /*
            |--------------------------------------------------------------------------
            | STATUS NYERI
            |--------------------------------------------------------------------------
            */
            $detailNyeri[] = 'Nyeri: '
                . $centangNyeri($nyeri->NYERI, 1) . ' Ya'
                . '     '
                . $centangNyeri($nyeri->NYERI, 0) . ' Tidak';

            /*
            |--------------------------------------------------------------------------
            | ONSET
            |--------------------------------------------------------------------------
            */
            $detailNyeri[] = 'Onset: '
                . $centangNyeri($nyeri->ONSET, 1) . ' Akut'
                . '     '
                . $centangNyeri($nyeri->ONSET, 2) . ' Kronis';

            /*
            |--------------------------------------------------------------------------
            | METODE PENILAIAN
            |--------------------------------------------------------------------------
            */
            $metodeNyeri = [
                1 => 'NRS',
                2 => 'BPS',
                3 => 'NIPS',
                4 => 'FLACC',
                5 => 'VAS',
            ];

            $barisMetode = [];

            foreach ($metodeNyeri as $nilai => $label) {
                $barisMetode[] =
                    $centangNyeri($nyeri->METODE, $nilai)
                    . ' ' . $label;
            }

            $detailNyeri[] = 'Metode: '
                . implode('     ', $barisMetode);

            /*
            |--------------------------------------------------------------------------
            | SKALA NYERI
            |--------------------------------------------------------------------------
            | NRS dan VAS cukup menampilkan skala langsung.
            | BPS, NIPS dan FLACC menampilkan rincian skor indikator.
            |--------------------------------------------------------------------------
            */
            $metode = (string) ($nyeri->METODE ?? '');
            $skala = $this->value($nyeri->SKALA ?? null);

            if (in_array($metode, ['1', '5'], true)) {

                // NRS / VAS: tanpa slider dan tanpa rincian indikator
                $detailNyeri[] = 'Skala Nyeri: '
                    . ($skala !== '' ? $skala : '-');

                $interpretasiNyeri = '';

                if ($skala !== '' && is_numeric($skala)) {
                    $nilaiSkala = (float) $skala;

                    if ($nilaiSkala == 0) {
                        $interpretasiNyeri = 'Tidak nyeri';
                    } elseif ($nilaiSkala <= 3) {
                        $interpretasiNyeri = 'Nyeri ringan';
                    } elseif ($nilaiSkala <= 6) {
                        $interpretasiNyeri = 'Nyeri sedang';
                    } else {
                        $interpretasiNyeri = 'Nyeri berat';
                    }
                }

                if ($interpretasiNyeri !== '') {
                    $detailNyeri[] =
                        'Interpretasi: ' . $interpretasiNyeri;
                }

            } elseif ($metode === '2') {

                // BPS
                $detailNyeri[] = 'Rincian Skor BPS:';

                foreach ([
                    'SKOR1' => 'Indikator 1',
                    'SKOR2' => 'Indikator 2',
                    'SKOR3' => 'Indikator 3',
                ] as $kolom => $label) {
                    $detailNyeri[] = '   '
                        . $label . ': '
                        . $this->value($nyeri->{$kolom} ?? null);
                }

                $detailNyeri[] = 'Skala Nyeri Total: '
                    . ($skala !== '' ? $skala : '-');

                if ($skala !== '' && is_numeric($skala)) {
                    $nilaiSkala = (float) $skala;

                    $interpretasiNyeri = match (true) {
                        $nilaiSkala <= 3 => 'Tidak nyeri',
                        $nilaiSkala <= 6 => 'Nyeri ringan',
                        $nilaiSkala <= 9 => 'Nyeri sedang',
                        default => 'Nyeri berat',
                    };

                    $detailNyeri[] =
                        'Interpretasi: ' . $interpretasiNyeri;
                }

            } elseif ($metode === '3') {

                // NIPS
                $detailNyeri[] = 'Rincian Skor NIPS:';

                foreach ([
                    'SKOR1' => 'Ekspresi wajah',
                    'SKOR2' => 'Tangisan',
                    'SKOR3' => 'Gerakan lengan',
                    'SKOR4' => 'Gerakan tungkai',
                    'SKOR5' => 'Status terjaga',
                    'SKOR6' => 'Pola napas',
                ] as $kolom => $label) {
                    $detailNyeri[] = '   '
                        . $label . ': '
                        . $this->value($nyeri->{$kolom} ?? null);
                }

                $detailNyeri[] = 'Skala Nyeri Total: '
                    . ($skala !== '' ? $skala : '-');

                if ($skala !== '' && is_numeric($skala)) {
                    $detailNyeri[] =
                        'Interpretasi: '
                        . ((float) $skala > 3
                            ? 'Nyeri'
                            : 'Tidak nyeri');
                }

            } elseif ($metode === '4') {

                // FLACC
                $detailNyeri[] = 'Rincian Skor FLACC:';

                foreach ([
                    'SKOR1' => 'Wajah',
                    'SKOR2' => 'Kaki',
                    'SKOR3' => 'Aktivitas',
                    'SKOR4' => 'Menangis',
                    'SKOR5' => 'Bersuara',
                ] as $kolom => $label) {
                    $detailNyeri[] = '   '
                        . $label . ': '
                        . $this->value($nyeri->{$kolom} ?? null);
                }

                $detailNyeri[] = 'Skala Nyeri Total: '
                    . ($skala !== '' ? $skala : '-');
            }

            /*
            |--------------------------------------------------------------------------
            | DETAIL KELUHAN NYERI
            |--------------------------------------------------------------------------
            */
            foreach ([
                'PENCETUS' => 'Pencetus',
                'GAMBARAN' => 'Gambaran',
                'DURASI'   => 'Durasi',
                'LOKASI'   => 'Lokasi',
            ] as $kolom => $label) {

                $nilai = $this->value($nyeri->{$kolom} ?? null);

                $detailNyeri[] = $label . ': '
                    . ($nilai !== '' ? $nilai : '-');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | SKRINING RISIKO JATUH HUMPTY DUMPTY
        |--------------------------------------------------------------------------
        */
        $humptyDumpty = DB::table(
            'medicalrecord.penilaian_skala_humpty_dumpty'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first([
                'UMUR',
                'JENIS_KELAMIN',
                'DIAGNOSA',
                'GANGGUAN_KONGNITIF',
                'FAKTOR_LINGKUNGAN',
                'RESPON',
                'PENGGUNAAN_OBAT',
            ]);

        $centangHumptyDumpty = static function (
            $nilai,
            array $pilihan
        ): string {
            $hasil = [];

            foreach ($pilihan as $value => $label) {
                $hasil[] =
                    ((string) ($nilai ?? '') === (string) $value ? '☑' : '☐')
                    . ' ' . $label;
            }

            return implode('   ', $hasil);
        };

        $detailRisikoJatuh = [];

        if ($humptyDumpty) {
            $detailRisikoJatuh[] =
                'SKRINING RISIKO JATUH - HUMPTY DUMPTY';

            $pilihanHumptyDumpty = [
                'UMUR' => [
                    'label' => 'Usia',
                    'pilihan' => [
                        1 => '< 3 Tahun',
                        2 => '3 s.d. < 7 Tahun',
                        3 => '7 s.d. < 13 Tahun',
                        4 => '≥ 13 Tahun',
                    ],
                ],
                'JENIS_KELAMIN' => [
                    'label' => 'Jenis kelamin',
                    'pilihan' => [
                        2 => 'Perempuan',
                        1 => 'Laki-laki',
                    ],
                ],
                'DIAGNOSA' => [
                    'label' => 'Diagnosa',
                    'pilihan' => [
                        4 => 'Kelainan neurologis, meningitis, ensefalitis, kejang atau gelisah',
                        3 => 'Gangguan perilaku/psikiatri',
                        2 => 'Perubahan oksigenasi',
                        1 => 'Diagnosis lainnya',
                    ],
                ],
                'GANGGUAN_KONGNITIF' => [
                    'label' => 'Gangguan kognitif',
                    'pilihan' => [
                        3 => 'Belum punya kontrol diri/gelisah',
                        2 => 'Lupa kondisi sakit/kadang gelisah',
                        1 => 'Orientasi terhadap kemampuan diri',
                    ],
                ],
                'FAKTOR_LINGKUNGAN' => [
                    'label' => 'Faktor lingkungan',
                    'pilihan' => [
                        4 => 'Riwayat jatuh/tempat tidur dewasa',
                        3 => 'Menggunakan alat bantu, di kursi atau pangkuan',
                        2 => 'Tempat tidur bayi/khusus anak',
                        1 => 'Area di luar rumah sakit',
                    ],
                ],
                'RESPON' => [
                    'label' => 'Respon terhadap tindakan operasi/anestesi/sedasi',
                    'pilihan' => [
                        3 => 'Dalam 24 jam',
                        2 => 'Dalam 48 jam',
                        1 => '48 jam/tidak menjalani operasi',
                    ],
                ],
                'PENGGUNAAN_OBAT' => [
                    'label' => 'Penggunaan obat',
                    'pilihan' => [
                        3 => 'Multiple sedative dan obat risiko tinggi lainnya',
                        2 => 'Menggunakan salah satu obat tersebut',
                        1 => 'Obat lain/tidak menggunakan obat tersebut',
                    ],
                ],
            ];

            $totalSkorHumptyDumpty = 0;
            $semuaNilaiTerisi = true;

            foreach ($pilihanHumptyDumpty as $kolom => $item) {
                $nilai = $humptyDumpty->{$kolom} ?? null;

                $detailRisikoJatuh[] = $item['label'] . ': '
                    . $centangHumptyDumpty($nilai, $item['pilihan']);

                if ($nilai === null || $nilai === '') {
                    $semuaNilaiTerisi = false;
                } else {
                    $totalSkorHumptyDumpty += (int) $nilai;
                }
            }

            if ($semuaNilaiTerisi) {
                $kategoriHumptyDumpty = $totalSkorHumptyDumpty >= 12
                    ? 'High Risk Dumpty'
                    : 'Low Humpty Dumpty';

                $detailRisikoJatuh[] = '';
                $detailRisikoJatuh[] =
                    'Total skor: ' . $totalSkorHumptyDumpty;
                $detailRisikoJatuh[] =
                    'Kategori: ' . $kategoriHumptyDumpty;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | SKRINING GIZI STRONG KID
        |--------------------------------------------------------------------------
        */
        $strongKid = DB::table('medicalrecord.penilaian_strong_kid')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first();

        $centangStrongKid = static function ($nilai, $pilihan): string {
            return (string) ($nilai ?? '') === (string) $pilihan
                ? '☑'
                : '☐';
        };

        $detailSkriningGizi = [];

        if ($strongKid) {
            $pertanyaanStrongKid = [
                'TAMPAK_KURUS' => 'Apakah pasien tampak kurus',
                'PENURUNAN_BERAT_BADAN' =>
                    'Apakah terjadi penurunan berat badan selama 1 bulan terakhir',
                'DIARE_INTAKE_MAKANAN' =>
                    'Apakah terdapat diare/muntah atau asupan makanan berkurang',
                'RESIKO_MALNUTRISI' =>
                    'Apakah terdapat penyakit atau keadaan yang berisiko menyebabkan malnutrisi',
            ];

            foreach ($pertanyaanStrongKid as $kolom => $pertanyaan) {
                $nilai = $strongKid->{$kolom} ?? null;

                $detailSkriningGizi[] = $pertanyaan;
                $detailSkriningGizi[] =
                    $centangStrongKid($nilai, 0) . ' Tidak    '
                    . $centangStrongKid($nilai, 1) . ' Ya';
            }

            $skor = $strongKid->SKOR ?? null;

            if ($skor !== null) {
                $kategoriStrongKid = match (true) {
                    (int) $skor >= 3 => 'Risiko Tinggi',
                    (int) $skor === 2 => 'Risiko Sedang',
                    default => 'Tidak Berisiko',
                };

                $detailSkriningGizi[] = 'Skor: ' . $skor;
                $detailSkriningGizi[] = 'Kategori: ' . $kategoriStrongKid;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | KEBUTUHAN EDUKASI
        |--------------------------------------------------------------------------
        */
                $edukasiPasien = DB::table('medicalrecord.edukasi_pasien_keluarga')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first();

        $centang = static function ($nilai, $pilihan = 1): string {
            return (string) ($nilai ?? '') === (string) $pilihan ? '☑' : '☐';
        };

        // Kelompok 1: edukasi awal
        $barisEdukasiAwal = [
            ' Kesediaan pasien/keluarga menerima informasi : '
                . $centang($edukasiPasien->KESEDIAAN ?? null, 1) . 'Ya' . ' '
                . $centang($edukasiPasien->KESEDIAAN ?? null, 0) . 'Tidak',
            ' Terdapat hambatan dalam edukasi : '
                . $centang($edukasiPasien->HAMBATAN ?? null, 1) . 'Ya' . ' '
                . $centang($edukasiPasien->HAMBATAN ?? null, 0) . 'Tidak',
            ' Dibutuhkan penerjemah : '
                . $centang($edukasiPasien->PENERJEMAH ?? null, 1) . 'Ya' . ' '
                . $centang($edukasiPasien->PENERJEMAH ?? null, 0) . 'Tidak',
        ];

        $kolomEdukasi = [
            [
                'EDUKASI_DIAGNOSA' => 'Kondisi kesehatan dan diagnosa serta penatalaksanaannya',
                'EDUKASI_REHAB_MEDIK' => 'Teknik rehabilitasi',
                'EDUKASI_HKP' => 'Hak dan kewajiban pasien',
                'EDUKASI_PEMBERIAN_INFORMED_CONSENT' => 'Proses pemberian informed consent',
                'EDUKASI_CUCI_TANGAN' => 'Cuci tangan dengan benar',
                'EDUKASI_PERENCANAAN_PULANG' => 'Edukasi perencanaan pulang',
            ],
            [
                'EDUKASI_OBAT' => 'Penggunaan obat secara efektif dan efek samping/interaksinya',
                'EDUKASI_NYERI' => 'Manajemen nyeri',
                'EDUKASI_HAK_BERPARTISIPASI' => 'Hak berpartisipasi pada pelayanan',
                'EDUKASI_PENUNDAAN_PELAYANAN' => 'Penundaan pelayanan',
                'EDUKASI_BAHAYA_MEROKO' => 'Bahaya merokok',
                'EDUKASI_RUJUKAN_PASIEN' => 'Edukasi rujukan pasien',
            ],
            [
                'EDUKASI_NUTRISI' => 'Diet dan nutrisi',
                'EDUKASI_PENGGUNAAN_ALAT' => 'Penggunaan alat medis yang aman',
                'EDUKASI_PROSEDURE_PENUNJANG' => 'Prosedur pemeriksaan penunjang',
                'EDUKASI_KELAMBATAN_PELAYANAN' => 'Keterlambatan pelayanan',
                'STATUS_LAIN' => 'Lain-lainnya',
            ],
        ];

        $barisKolomEdukasi = [];

        foreach ($kolomEdukasi as $kolom) {
            $baris = [];

            foreach ($kolom as $field => $label) {
                $baris[] = $centang($edukasiPasien->{$field} ?? null)
                    . ' ' . $label;
            }

            $barisKolomEdukasi[] = implode("\n", $baris);
        }

        $edukasiLainnya = $this->value(
            $edukasiPasien->DESKRIPSI_LAINYA ?? null
        );

        if ($edukasiLainnya !== '') {
            $barisKolomEdukasi[2] .= "\nKeterangan lainnya: " . $edukasiLainnya;
        }

        $dataKebutuhanEdukasi = [
            'KESEDIAAN_DAN_HAMBATAN' => implode("\n", [
                ...$barisEdukasiAwal,
            ]),
            'KEBUTUHAN_EDUKASI_KOLOM_1' => $barisKolomEdukasi[0],
            'KEBUTUHAN_EDUKASI_KOLOM_2' => $barisKolomEdukasi[1],
            'KEBUTUHAN_EDUKASI_KOLOM_3' => $barisKolomEdukasi[2],
        ];

        /*
        |--------------------------------------------------------------------------
        | MASALAH KEPERAWATAN
        |--------------------------------------------------------------------------
        */
        $masalahKeperawatan = DB::table('medicalrecord.masalah_keperawatan')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first();

        $daftarMasalahKeperawatan = [];

        $opsiMasalahKeperawatan = [
            'BERSIHAN_JALAN_NAFAS_TIDAK_EFEKTIF' => 'Bersihan jalan nafas tidak efektif',
            'POLA_NAFAS_TIDAK_EFEKTIF' => 'Pola nafas tidak efektif',
            'PERFUSI_PERIFER_TIDAK_EFEKTIF' => 'Perfusi perifer tidak efektif',
            'DIARE' => 'Diare',
            'NYERI_AKUT' => 'Nyeri akut',
            'NAUSEA' => 'Nausea',
            'HIPERTERMI' => 'Hipertermia',
            'ANSIETAS' => 'Ansietas',
            'GANGGUAN_INTEGRITAS_KULIT_JARINGAN' => 'Gangguan integritas kulit/jaringan',
            'GANGGUAN_ELIMINASI_URINE' => 'Gangguan eliminasi urine',
            'INTOLERANSI_AKTIVITAS' => 'Intoleransi aktivitas',
            'GANGGUAN_MOBILITAS_FISIK' => 'Gangguan mobilitas fisik',
            'GANGGUAN_PERTUKARAN_GAS' => 'Gangguan pertukaran gas',
        ];

        if ($masalahKeperawatan) {
            foreach ($opsiMasalahKeperawatan as $kolom => $label) {
                $nilai = (int) ($masalahKeperawatan->{$kolom} ?? 0);

                $kotak = $nilai === 1 ? '☑' : '☐';

                $daftarMasalahKeperawatan[] = $kotak . ' ' . $label;
            }

            $masalahLain = $this->value(
                $masalahKeperawatan->MASALAH_LAIN ?? null
            );

            if ($masalahLain !== '') {
                $daftarMasalahKeperawatan[] = '☐ Masalah lain: ' . $masalahLain;
            }
        }

        $jumlahPerKolom = (int) ceil(count($daftarMasalahKeperawatan) / 3);

        $kolomMasalahKeperawatan = array_chunk(
            $daftarMasalahKeperawatan,
            max(1, $jumlahPerKolom)
        );

        $kolomMasalahKeperawatan = array_pad(
            $kolomMasalahKeperawatan,
            3,
            []
        );

        /*
        |--------------------------------------------------------------------------
        | PERENCANAAN DAN TINDAKAN
        |--------------------------------------------------------------------------
        */
        $perencanaan = DB::table('medicalrecord.masalah_keperawatan')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first();

        $daftarTindakan = [];
        $daftarRawatLuka = [];
        $daftarTerapi = [];

        if ($perencanaan) {

            // KELOMPOK TINDAKAN
            foreach ([
                'TINDAKAN_RELAKSASI_NAFAS_DALAM' => 'Ajarkan teknik relaksasi dan nafas dalam',
                'TINDAKAN_BODY_ALIGNMENT' => 'Pertahankan body alignment dan posisi yang nyaman',
                'TINDAKAN_TENANGKAN_PASIEN' => 'Tenangkan pasien',
                'TINDAKAN_PENDIDIKAN_KESEHATAN' => 'Berikan pendidikan kesehatan kepada pasien dan keluarga',
            ] as $kolom => $label) {

                $nilai = (int) ($perencanaan->{$kolom} ?? 0);
                $kotak = $nilai === 1 ? '☑' : '☐';

                $daftarTindakan[] = $kotak . ' ' . $label;
            }

            // KELOMPOK RAWAT LUKA
            $nilaiRawatLuka = (int) (
                $perencanaan->TINDAKAN_RAWAT_LUKA ?? 0
            );

            $kotakRawatLuka = $nilaiRawatLuka === 1 ? '☑' : '☐';

            $daftarRawatLuka[] = $kotakRawatLuka . ' Rawat luka';

            // KELOMPOK TERAPI ORAL
            $nilaiOral = (int) ($perencanaan->TERAPI_ORAL ?? 0);
            $kotakOral = $nilaiOral === 1 ? '☑' : '☐';

            $detailOral = $this->value(
                $perencanaan->TERAPI_ORAL_DETAIL ?? null
            );

            $daftarTerapi[] = $kotakOral . ' Terapi oral'
                . ($detailOral !== '' ? ': ' . $detailOral : '');

            // KELOMPOK TERAPI IV/SC/IM
            $nilaiIV = (int) ($perencanaan->TERAPI_IV_SC_IM ?? 0);
            $kotakIV = $nilaiIV === 1 ? '☑' : '☐';

            $detailIV = $this->value(
                $perencanaan->TERAPI_IV_SC_IM_DETAIL ?? null
            );

            $daftarTerapi[] = $kotakIV . ' Terapi IV/SC/IM'
                . ($detailIV !== '' ? ': ' . $detailIV : '');
        }

        /*
        |--------------------------------------------------------------------------
        | DISCHARGE PLANNING
        |--------------------------------------------------------------------------
        */
        $dpFaktorRisiko = DB::table(
            'medicalrecord.discharge_planning_faktor_risiko'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first();

        $dpSkrining = DB::table(
            'medicalrecord.discharge_planning_skrining'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first();

        $dpCentang = static function ($nilai, $pilihan): string {
            return (string) ($nilai ?? '') === (string) $pilihan ? '☑' : '☐';
        };

        $dpYaTidak = static function ($nilai) use ($dpCentang): string {
            return $dpCentang($nilai, 1) . ' Ya   '
                . $dpCentang($nilai, 0) . ' Tidak';
        };

        $dpCheckbox = static function ($nilai, string $label): string {
            return ((int) ($nilai ?? 0) === 1 ? '☑' : '☐')
                . ' ' . $label;
        };

        $detailDischargePlanningKolom1 = [];
        $detailDischargePlanningKolom2 = [];

        /*
        |--------------------------------------------------------------------------
        | KOLOM 1 — FAKTOR RISIKO DAN PERAWATAN LANJUTAN
        |--------------------------------------------------------------------------
        */
        if ($dpFaktorRisiko || $dpSkrining) {
            $detailDischargePlanningKolom1[] = 'DISCHARGE PLANNING';
            $detailDischargePlanningKolom1[] = '';
            $detailDischargePlanningKolom1[] = 'FAKTOR RISIKO';

            if ($dpFaktorRisiko) {
                $detailDischargePlanningKolom1[] =
                    'Pasien tinggal sendiri: '
                    . $dpYaTidak($dpFaktorRisiko->PASIEN_TINGGAL_SENDIRI ?? null);

                $detailDischargePlanningKolom1[] =
                    'Pasien/keluarga khawatir ketika kembali ke rumah: '
                    . $dpYaTidak(
                        $dpFaktorRisiko->PASIEN_KHAWATIR_KETIKA_DIRUMAH ?? null
                    );

                $detailDischargePlanningKolom1[] =
                    'Tidak ada yang merawat pasien di rumah: '
                    . $dpYaTidak(
                        $dpFaktorRisiko->PASIEN_TAK_ADA_YANG_MERAWAT ?? null
                    );

                $detailDischargePlanningKolom1[] =
                    'Pasien tinggal di lantai atas: '
                    . $dpYaTidak($dpFaktorRisiko->PASIEN_DILANTAI_ATAS ?? null);
            }

            if ($dpSkrining) {
                $detailDischargePlanningKolom1[] = '';
                $detailDischargePlanningKolom1[] =
                    'Perawatan lanjutan/penggunaan alat medis di rumah: '
                    . $dpYaTidak(
                        $dpSkrining->PERAWATAN_LANJUTAN_MEDIS ?? null
                    );

                $detailDischargePlanningKolom1[] =
                    $dpCheckbox($dpSkrining->PLM_KATETER_URIN ?? 0, 'Kateter urin')
                    . '   '
                    . $dpCheckbox($dpSkrining->PLM_TRAECHOSTOMY ?? 0, 'Traechostomy');

                $detailDischargePlanningKolom1[] =
                    $dpCheckbox($dpSkrining->PLM_NGT ?? 0, 'NGT')
                    . '   '
                    . $dpCheckbox($dpSkrining->PLM_COLOSTOMY ?? 0, 'Colostomy');

                $perawatanLain = $this->value(
                    $dpSkrining->KEBUTUHAN_PELAYANAN_BERKELANJUTAN_LAINNYA ?? null
                );

                if ($perawatanLain !== '') {
                    $detailDischargePlanningKolom1[] =
                        'Lainnya: ' . $perawatanLain;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | KOLOM 2 — KRITERIA, KPB, ALAT MEDIS, SKRINING LANJUTAN
        |--------------------------------------------------------------------------
        */
        if ($dpSkrining) {
            $detailDischargePlanningKolom2[] = 'KRITERIA DAN TINDAK LANJUT';

            $detailDischargePlanningKolom2[] =
                'Pasien pulang dengan jumlah obat > 6: '
                . $dpYaTidak($dpSkrining->PASIEN_PULANG ?? null);

            $detailDischargePlanningKolom2[] =
                'Pasien mengajukan pendampingan ke rumah sakit: '
                . $dpYaTidak($dpSkrining->PASIEN_MENGAJUKAN ?? null);

            $detailDischargePlanningKolom2[] =
                'Tidak ada kriteria: '
                . $dpYaTidak($dpSkrining->TIDAK_ADA_KRITERIA ?? null);

            $detailDischargePlanningKolom2[] = '';
            $detailDischargePlanningKolom2[] =
                'Kebutuhan pelayanan berkelanjutan (KPB): '
                . $dpYaTidak(
                    $dpSkrining->KEBUTUHAN_PELAYANAN_BERKELANJUTAN_KPB ?? null
                );

            $detailDischargePlanningKolom2[] =
                $dpCheckbox($dpSkrining->KPB_RAWAT_LUKA ?? 0, 'Rawat luka')
                . '   '
                . $dpCheckbox($dpSkrining->KPB_TB ?? 0, 'TB')
                . '   '
                . $dpCheckbox(
                    $dpSkrining->KPB_DM_TERAPI_INSULIN ?? 0,
                    'DM dengan terapi insulin'
                );

            $detailDischargePlanningKolom2[] =
                $dpCheckbox($dpSkrining->KPB_PPOK ?? 0, 'PPOK')
                . '   '
                . $dpCheckbox($dpSkrining->KPB_PASIEN_KEMO ?? 0, 'Pasien kemoterapi')
                . '   '
                . $dpCheckbox($dpSkrining->KPB_HIV ?? 0, 'HIV/AIDS');

            $detailDischargePlanningKolom2[] =
                $dpCheckbox($dpSkrining->KPB_DM ?? 0, 'DM')
                . '   '
                . $dpCheckbox($dpSkrining->KPB_STROKE ?? 0, 'Stroke')
                . '   '
                . $dpCheckbox($dpSkrining->KPB_CKD ?? 0, 'CKD');

            $detailDischargePlanningKolom2[] = '';
            $detailDischargePlanningKolom2[] =
                'Penggunaan alat medis/bantu: '
                . $dpYaTidak($dpSkrining->PENGGUNAAN_ALAT_MEDIS_PAM ?? null);

            $detailDischargePlanningKolom2[] =
                $dpCheckbox($dpSkrining->PAM_KATETER_URIN ?? 0, 'Kateter urin')
                . '   '
                . $dpCheckbox($dpSkrining->PAM_TRAECHOSTOMY ?? 0, 'Traechostomy');

            $detailDischargePlanningKolom2[] =
                $dpCheckbox($dpSkrining->PAM_NGT ?? 0, 'NGT')
                . '   '
                . $dpCheckbox($dpSkrining->PAM_COLOSTOMY ?? 0, 'Colostomy');

            $alatMedisLain = $this->value($dpSkrining->PAM_LAINNYA ?? null);

            if ($alatMedisLain !== '') {
                $detailDischargePlanningKolom2[] =
                    'Alat medis lainnya: ' . $alatMedisLain;
            }

            $detailDischargePlanningKolom2[] = '';
            $detailDischargePlanningKolom2[] =
                'Skrining lanjutan: '
                . $dpYaTidak($dpSkrining->SKRINING_LANJUTAN ?? null);

            $detailDischargePlanningKolom2[] =
                $dpCheckbox($dpSkrining->SKRINING ?? null, 'Konsul MPP')
                . '   '
                . $dpCheckbox($dpSkrining->SKRINING ?? null, 'Edukasi');
        }

        // print_r($hasilLaboratorium);
        // die();

        /*
        |--------------------------------------------------------------------------
        | RETURN DATA TEMPLATE
        |--------------------------------------------------------------------------
        */

        return [
            /*
            | ANAMNESIS
            */

            'ANAMNESIS_DIPEROLEH' => $this->placeholder(
                implode(', ', $anamnesis)
            ),
            'KELUHAN_UTAMA' => $this->placeholder($keluhanUtama),
            'RIWAYAT_PENYAKIT_SEKARANG' => $this->placeholder(
                $riwayatPenyakitSekarang
            ),
            'RIWAYAT_PENYAKIT_DAHULU' => $this->placeholder(
                $riwayatPenyakitDahulu
            ),
            'RIWAYAT_PENYAKIT_KELUARGA' => $this->placeholder(
                implode(', ', $daftarRiwayatKeluarga)
            ),
            'RIWAYAT_TUMBUH_KEMBANG' => $this->placeholder(
                $statusReproduksi->RIWAYAT_TUMBUH_KEMBANG ?? null
            ),
            'RIWAYAT_KELAHIRAN' => $this->placeholder(
                $statusReproduksi->RIWAYAT_KELAHIRAN ?? null
            ),
            'USIA_KEHAMILAN' => $this->placeholder(
                isset($statusReproduksi->USIA_KEHAMILAN)
                    ? $statusReproduksi->USIA_KEHAMILAN . ' Minggu'
                    : null
            ),
            'PERSALINAN' => $this->placeholder($persalinan),
            'RIWAYAT_IMUNISASI' => $this->placeholder($imunisasi),

            'INTRA_UMUR_KEHAMILAN' => $this->placeholder($intraUmurKehamilan),
            'INTRA_JENIS_PERSALINAN' => $this->placeholder($intraJenisPersalinan),
            'INTRA_PENYULIT_PERSALINAN' => $this->placeholder($intraPenyulitPersalinan),
            'INTRA_KOMPLIKASI_PERSALINAN' => $this->placeholder($intraKomplikasiPersalinan),

            'POST_BBL' => $this->placeholder($postBbl),
            'POST_PBL' => $this->placeholder($postPbl),
            'POST_LK' => $this->placeholder($postLk),
            'POST_LD' => $this->placeholder($postLd),
            'POST_LP' => $this->placeholder($postLp),
            'POST_LILA' => $this->placeholder($postLila),
            'POST_TRAUMA_LAHIR' => $this->placeholder($postTraumaLahir),
            'POST_USAHA_NAFAS' => $this->placeholder($postUsahaNafas),

            /*
            | APGAR
            */

            'APGAR_1_DENYUT' => $this->placeholder($dataApgar['APGAR_1_DENYUT'] ?? '-'),
            'APGAR_5_DENYUT' => $this->placeholder($dataApgar['APGAR_5_DENYUT'] ?? '-'),
            'APGAR_10_DENYUT' => $this->placeholder($dataApgar['APGAR_10_DENYUT'] ?? '-'),

            'APGAR_1_PERNAFASAN' => $this->placeholder($dataApgar['APGAR_1_PERNAFASAN'] ?? '-'),
            'APGAR_5_PERNAFASAN' => $this->placeholder($dataApgar['APGAR_5_PERNAFASAN'] ?? '-'),
            'APGAR_10_PERNAFASAN' => $this->placeholder($dataApgar['APGAR_10_PERNAFASAN'] ?? '-'),

            'APGAR_1_TONUS' => $this->placeholder($dataApgar['APGAR_1_TONUS'] ?? '-'),
            'APGAR_5_TONUS' => $this->placeholder($dataApgar['APGAR_5_TONUS'] ?? '-'),
            'APGAR_10_TONUS' => $this->placeholder($dataApgar['APGAR_10_TONUS'] ?? '-'),

            'APGAR_1_RANGSANG' => $this->placeholder($dataApgar['APGAR_1_RANGSANG'] ?? '-'),
            'APGAR_5_RANGSANG' => $this->placeholder($dataApgar['APGAR_5_RANGSANG'] ?? '-'),
            'APGAR_10_RANGSANG' => $this->placeholder($dataApgar['APGAR_10_RANGSANG'] ?? '-'),

            'APGAR_1_WARNA' => $this->placeholder($dataApgar['APGAR_1_WARNA'] ?? '-'),
            'APGAR_5_WARNA' => $this->placeholder($dataApgar['APGAR_5_WARNA'] ?? '-'),
            'APGAR_10_WARNA' => $this->placeholder($dataApgar['APGAR_10_WARNA'] ?? '-'),

            'APGAR_TOTAL_1' => $this->placeholder($dataApgar['APGAR_TOTAL_1'] ?? '-'),
            'APGAR_TOTAL_5' => $this->placeholder($dataApgar['APGAR_TOTAL_5'] ?? '-'),
            'APGAR_TOTAL_10' => $this->placeholder($dataApgar['APGAR_TOTAL_10'] ?? '-'),

            /*
            | TANDA VITAL DAN ANTROPOMETRI
            */

            'KEADAAN_UMUM' => $this->placeholder(
                $tandaVital->KEADAAN_UMUM ?? null
            ),
            'TEKANAN_DARAH' => $tekananDarah,
            'NADI' => $nadi,
            'FREKUENSI_NAPAS' => $frekuensiNapas,
            'SUHU' => $tandaVital && $tandaVital->SUHU !== null
                ? $tandaVital->SUHU . ' °C'
                : '-',
            'SATURASI_O2' => $tandaVital && $tandaVital->SATURASI_O2 !== null
                ? ((int) $tandaVital->SATURASI_O2) . ' %'
                : '-',
            'KESADARAN_GCS' => $this->placeholder($kesadaranGcs),

            'BERAT_BADAN' => $nutrisi && $nutrisi->BERAT_BADAN !== null
                ? ((int) $nutrisi->BERAT_BADAN) . ' kg'
                : '-',
            'TINGGI_BADAN' => $nutrisi && $nutrisi->TINGGI_BADAN !== null
                ? ((int) $nutrisi->TINGGI_BADAN) . ' cm'
                : '-',
            'IMT' => $this->placeholder(
                $nutrisi->INDEX_MASSA_TUBUH ?? null
            ),

            'PEMERIKSAAN_FISIK_NEONATUS' => $this->placeholder(
                implode("\n", $detailPemeriksaanFisikNeoPerawat)
            ),

            'HUBUNGAN_STATUS_PSIKOSOSIAL' => $this->placeholder(
                $detailPsikososial
            ),

            'STATUS_FUNGSIONAL' => $this->placeholder(
                $detailFungsional
            ),

            'SKRINING_NYERI' => $this->placeholder(
                implode("\n", $detailNyeri)
            ),

            'SKRINING_RISIKO_JATUH' => $this->placeholder(
                implode("\n", $detailRisikoJatuh)
            ),

            'SKRINING_GIZI' => $this->placeholder(
                implode("\n", $detailSkriningGizi)
            ),

            'KESEDIAAN_DAN_HAMBATAN' => $this->placeholder(
                $dataKebutuhanEdukasi['KESEDIAAN_DAN_HAMBATAN']
            ),
            'KEBUTUHAN_EDUKASI_1' => $this->placeholder(
                $dataKebutuhanEdukasi['KEBUTUHAN_EDUKASI_KOLOM_1']
            ),
            'KEBUTUHAN_EDUKASI_2' => $this->placeholder(
                $dataKebutuhanEdukasi['KEBUTUHAN_EDUKASI_KOLOM_2']
            ),
            'KEBUTUHAN_EDUKASI_3' => $this->placeholder(
                $dataKebutuhanEdukasi['KEBUTUHAN_EDUKASI_KOLOM_3']
            ),

            'MASALAH_KEPERAWATAN_1' => $this->placeholder(
                implode("\n", $kolomMasalahKeperawatan[0])
            ),

            'MASALAH_KEPERAWATAN_2' => $this->placeholder(
                implode("\n", $kolomMasalahKeperawatan[1])
            ),

            'MASALAH_KEPERAWATAN_3' => $this->placeholder(
                implode("\n", $kolomMasalahKeperawatan[2])
            ),

            'TINDAKAN' => $this->placeholder(
                implode("\n", $daftarTindakan)
            ),

            'RAWAT_LUKA' => $this->placeholder(
                implode("\n", $daftarRawatLuka)
            ),

            'TERAPI' => $this->placeholder(
                implode("\n", $daftarTerapi)
            ),

            'DISCHARGE_PLANNING_KOLOM_1' => $this->placeholder(
                implode("\n", $detailDischargePlanningKolom1)
            ),

            'DISCHARGE_PLANNING_KOLOM_2' => $this->placeholder(
                implode("\n", $detailDischargePlanningKolom2)
            ),

            /*
            | TANDA TANGAN USER FINALISASI
            */
            'NAMA_DOKTER_FINALISASI' => $finalisasi_dokter ? $finalisasi_dokter->NAMA_USER_FINALISASI : null,
            'TTD_DOKTER_FINALISASI' => $ttd_finalisasi_dokter ? $ttd_finalisasi_dokter->signature_path : null,
            'NAMA_PERAWAT_FINALISASI' => $finalisasi ? $finalisasi->NAMA_USER_FINALISASI : null,
            'TTD_PERAWAT_FINALISASI' => $ttd_finalisasi ? $ttd_finalisasi->signature_path : null,
        ];
    }

    private function getRiwayatLab($kunjungan) {
        $data = DB::select("
            SELECT LPAD(p.NORM, 8, '0') AS NORM,
            master.getNamaLengkap(p.NORM) AS NAMALENGKAP,
            CONCAT(rjk.DESKRIPSI, ' / ', DATE_FORMAT(p.TANGGAL_LAHIR, '%d-%m-%Y')) AS JKTGLLAHIR,

            master.getNamaLengkapPegawai(mp.NIP) AS DOKTER,
            mp.NIP AS NIPDPJP,
            master.getNamaLengkapPegawai(mpasal.NIP) AS DOKTERASAL,
            master.getNamaLengkapPegawai(mpper.NIP) AS ANALIS,

            pk.NOPEN,
            pk.MASUK AS TGLREG,
            hlab.TANGGAL AS TANGGALHASIL,
            chl.CATATAN,

            r.DESKRIPSI AS UNITPENGANTAR,
            ks.ALASAN AS DIAGNOSA,

            tm.KUNJUNGAN,

            ggl.DESKRIPSI AS GROUPLAB,
            kgl.DESKRIPSI AS KLPLAB,

            mt.NAMA AS NAMATINDAKAN,
            ptl.PARAMETER,

            IFNULL(hlab.NILAI_NORMAL, ptl.NILAI_RUJUKAN) AS NILAI_RUJUKAN,
            hlab.HASIL,
            IFNULL(hlab.SATUAN, sl.DESKRIPSI) AS SATUAN,
            hlab.KETERANGAN

            FROM layanan.hasil_lab hlab

            JOIN layanan.tindakan_medis tm ON hlab.TINDAKAN_MEDIS = tm.ID
            LEFT JOIN layanan.catatan_hasil_lab chl ON tm.KUNJUNGAN = chl.KUNJUNGAN
            LEFT JOIN master.dokter dok ON chl.DOKTER = dok.ID
            LEFT JOIN master.pegawai mp ON dok.NIP = mp.NIP
            LEFT JOIN layanan.petugas_tindakan_medis ptm ON ptm.TINDAKAN_MEDIS = tm.ID AND ptm.JENIS = 6 AND ptm.KE = 1 AND ptm.STATUS <> 0
            LEFT JOIN master.pegawai mpper ON ptm.MEDIS = mpper.ID

            JOIN master.parameter_tindakan_lab ptl ON hlab.PARAMETER_TINDAKAN = ptl.ID
            LEFT JOIN master.referensi sl ON ptl.SATUAN = sl.ID AND sl.JENIS = 35

            JOIN master.tindakan mt ON ptl.TINDAKAN = mt.ID
            LEFT JOIN master.mapping_group_pemeriksaan mgp ON mt.ID = mgp.PEMERIKSAAN AND mgp.STATUS = 1
            LEFT JOIN master.group_pemeriksaan kgl ON mgp.GROUP_PEMERIKSAAN_ID = kgl.ID AND kgl.JENIS = 8 AND kgl.STATUS = 1
            LEFT JOIN master.group_pemeriksaan ggl ON ggl.KODE = LEFT(kgl.KODE, 2) AND ggl.JENIS = 8 AND ggl.STATUS = 1

            JOIN pendaftaran.kunjungan pk ON tm.KUNJUNGAN = pk.NOMOR

            JOIN pendaftaran.pendaftaran pp ON pk.NOPEN = pp.NOMOR

            JOIN master.pasien p ON pp.NORM = p.NORM
            LEFT JOIN master.referensi rjk ON p.JENIS_KELAMIN = rjk.ID AND rjk.JENIS = 2
            LEFT JOIN layanan.order_lab ks ON pk.REF = ks.NOMOR
            LEFT JOIN pendaftaran.kunjungan kj ON ks.KUNJUNGAN = kj.NOMOR
            LEFT JOIN master.ruangan r ON kj.RUANGAN = r.ID AND r.JENIS = 5
            LEFT JOIN master.dokter dokasal ON ks.DOKTER_ASAL = dokasal.ID
            LEFT JOIN master.pegawai mpasal ON dokasal.NIP = mpasal.NIP

            WHERE kj.NOMOR = ?
            AND hlab.STATUS = 1 AND hlab.HASIL IS NOT NULL AND hlab.HASIL <> ''

            ORDER BY ggl.ID,
            kgl.ID,
            mt.ID,
            ptl.INDEKS ", [$kunjungan]);

            return $data;
    }

    private function getRiwayatRad($kunjungan)
    {
        $data = DB::table('layanan.hasil_rad as hrad')
            ->leftJoin('master.dokter as dok', 'hrad.DOKTER', '=', 'dok.ID')
            ->leftJoin('master.pegawai as mp', 'dok.NIP', '=', 'mp.NIP')
            ->join('layanan.tindakan_medis as tm', 'hrad.TINDAKAN_MEDIS', '=', 'tm.ID')
            ->leftJoin('master.tindakan as t', 'tm.TINDAKAN', '=', 't.ID')
            ->leftJoin('pendaftaran.kunjungan as pku', 'pku.NOMOR', '=', 'tm.KUNJUNGAN')
            ->leftJoin('layanan.order_rad as orad', function($join){
                $join->on('orad.NOMOR', '=', 'pku.REF')
                    ->whereIn('orad.STATUS', [1,2]);
            })
            ->leftJoin('master.dokter as dokasal', 'orad.DOKTER_ASAL', '=', 'dokasal.ID')
            ->leftJoin('layanan.petugas_tindakan_medis as ptm', function($join){
                $join->on('ptm.TINDAKAN_MEDIS', '=', 'tm.ID')
                    ->where('ptm.JENIS', 3)
                    ->where('ptm.KE', 1)
                    ->where('ptm.STATUS', '!=', 0);
            })
            ->leftJoin('master.perawat as prad', 'ptm.MEDIS', '=', 'prad.ID')
            ->join('pendaftaran.kunjungan as pk', 'tm.KUNJUNGAN', '=', 'pk.NOMOR')
            ->leftJoin('layanan.order_rad as ks', 'pk.REF', '=', 'ks.NOMOR')
            ->leftJoin('pendaftaran.kunjungan as kj', 'ks.KUNJUNGAN', '=', 'kj.NOMOR')
            ->leftJoin('master.ruangan as r', function($join){
                $join->on('kj.RUANGAN', '=', 'r.ID')
                    ->where('r.JENIS', 5);
            })
            ->join('pendaftaran.pendaftaran as pp', 'pk.NOPEN', '=', 'pp.NOMOR')
            ->join('master.pasien as p', 'pp.NORM', '=', 'p.NORM')
            ->leftJoin('master.referensi as rjk', function($join){
                $join->on('p.JENIS_KELAMIN', '=', 'rjk.ID')
                    ->where('rjk.JENIS', 2);
            })
            ->select([
                DB::raw("DATE_FORMAT(SYSDATE(),'%d-%m-%Y %H:%i:%s') AS TGLSKRG"),
                DB::raw("LPAD(p.NORM,8,'0') AS NORM"),
                DB::raw("master.getNamaLengkap(p.NORM) AS NAMALENGKAP"),
                DB::raw("CONCAT(rjk.DESKRIPSI,' / ',DATE_FORMAT(p.TANGGAL_LAHIR,'%d-%m-%Y')) AS JKTGLALHIR"),
            'hrad.TANGGAL',
                'hrad.KLINIS',
                'hrad.KESAN',
                'hrad.USUL',
                'hrad.HASIL',
                'hrad.BTK',
            DB::raw("master.getNamaLengkapPegawai(mp.NIP) AS DOKTER"),
                'mp.NIP AS NIPDOKTER',
            'pk.NOPEN',
                'pk.MASUK AS TGLREG',
            't.NAMA AS NAMATINDAKAN',
                'r.DESKRIPSI AS UNITPENGANTAR',
                'orad.ALASAN AS DIAGNOSA',
            'p.ALAMAT',
            DB::raw("master.getNamaLengkapPegawai(dokasal.NIP) AS DOKTERASAL"),
                DB::raw("master.getNamaLengkapPegawai(prad.NIP) AS RADIOGRAFER"),
            ])

            ->whereIn('tm.STATUS', [1,2])
            ->where('hrad.STATUS', '!=', 0)
            ->where('kj.NOMOR', $kunjungan)

            ->orderBy('t.ID')
            ->get();

        return $data;
    }

    /**
     * ============================================================================
     * FORMAT HASIL LABORATORIUM
     * ============================================================================
     */
    private function formatHasilLaboratorium($data): string
    {
        $data = collect($data);

        if ($data->isEmpty()) {
            return 'Belum terdapat data hasil pemeriksaan laboratorium.';
        }

        $pertama = $data->first();

        $lines = [
            'HASIL PEMERIKSAAN LABORATORIUM',
            str_repeat('─', 38),
        ];

        /*
        |--------------------------------------------------------------------------
        | INFORMASI PEMERIKSAAN
        |--------------------------------------------------------------------------
        */

        $informasi = [
            'Tanggal hasil' => $this->formatTanggalWaktuMedis(
                $pertama->TANGGALHASIL ?? null
            ),

            'Unit pengantar' => $this->value(
                $pertama->UNITPENGANTAR ?? null
            ),

            'Diagnosis / indikasi' => $this->value(
                $pertama->DIAGNOSA ?? null
            ),

            'Dokter pengirim' => $this->value(
                $pertama->DOKTERASAL ?? null
            ),

            'Dokter pemeriksa' => $this->value(
                $pertama->DOKTER ?? null
            ),

            'Analis laboratorium' => $this->value(
                $pertama->ANALIS ?? null
            ),
        ];

        foreach ($informasi as $label => $nilai) {
            if ($nilai !== '') {
                $lines[] = $label . ' : ' . $nilai;
            }
        }

        $lines[] = '';
        $lines[] = 'RINCIAN HASIL PEMERIKSAAN';
        $lines[] = str_repeat('─', 38);

        /*
        |--------------------------------------------------------------------------
        | KELOMPOK PEMERIKSAAN
        |--------------------------------------------------------------------------
        */

        $kelompokUtama = $data->groupBy(
            fn ($item) => $this->value($item->GROUPLAB ?? null)
                ?: 'Pemeriksaan Laboratorium'
        );

        foreach ($kelompokUtama as $namaKelompok => $itemKelompok) {
            $lines[] = '';
            $lines[] = strtoupper($namaKelompok);

            $kelompokDetail = $itemKelompok->groupBy(
                fn ($item) => $this->value($item->KLPLAB ?? null)
                    ?: 'Pemeriksaan'
            );

            foreach ($kelompokDetail as $namaSubkelompok => $itemSubkelompok) {
                $lines[] = '';
                $lines[] = '  ' . $namaSubkelompok;

                /*
                |--------------------------------------------------------------------------
                | NAMA TINDAKAN
                |--------------------------------------------------------------------------
                */

                $tindakan = $itemSubkelompok->groupBy(
                    fn ($item) => $this->value($item->NAMATINDAKAN ?? null)
                        ?: 'Pemeriksaan'
                );

                foreach ($tindakan as $namaTindakan => $itemTindakan) {
                    $lines[] = '    ' . $namaTindakan;

                    foreach ($itemTindakan as $item) {
                        $parameter = $this->value(
                            $item->PARAMETER ?? null
                        );

                        $hasil = $this->value(
                            $item->HASIL ?? null
                        );

                        $satuan = $this->value(
                            $item->SATUAN ?? null
                        );

                        $nilaiRujukan = $this->value(
                            $item->NILAI_RUJUKAN ?? null
                        );

                        $keterangan = $this->value(
                            $item->KETERANGAN ?? null
                        );

                        /*
                        |--------------------------------------------------------------------------
                        | NILAI HASIL
                        |--------------------------------------------------------------------------
                        */

                        $barisHasil = '      • '
                            . ($parameter !== '' ? $parameter : 'Hasil')
                            . ' : '
                            . ($hasil !== '' ? $hasil : '-');

                        if ($satuan !== '') {
                            $barisHasil .= ' ' . $satuan;
                        }

                        $lines[] = $barisHasil;

                        /*
                        |--------------------------------------------------------------------------
                        | NILAI RUJUKAN
                        |--------------------------------------------------------------------------
                        */

                        if ($nilaiRujukan !== '') {
                            $barisRujukan = '        Nilai rujukan : '
                                . $nilaiRujukan;

                            if ($satuan !== '') {
                                $barisRujukan .= ' ' . $satuan;
                            }

                            $lines[] = $barisRujukan;
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | KETERANGAN PARAMETER
                        |--------------------------------------------------------------------------
                        */

                        if ($keterangan !== '') {
                            $lines[] = '        Keterangan : '
                                . $keterangan;
                        }
                    }
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CATATAN HASIL
        |--------------------------------------------------------------------------
        */

        $catatan = $this->value(
            $pertama->CATATAN ?? null
        );

        if ($catatan !== '') {
            $lines[] = '';
            $lines[] = 'CATATAN LABORATORIUM';
            $lines[] = $catatan;
        }

        $lines[] = '';
        $lines[] = str_repeat('─', 38);
        $lines[] = 'Akhir laporan laboratorium';

        return implode("\n", $lines);
    }

    /**
     * ============================================================================
     * FORMAT HASIL RADIOLOGI
     * ============================================================================
     */
    private function formatHasilRadiologi($data): string
    {
        $data = collect($data);

        if ($data->isEmpty()) {
            return 'Belum terdapat data hasil pemeriksaan radiologi.';
        }

        /*
        |--------------------------------------------------------------------------
        | HINDARI BARIS LAPORAN DUPLIKAT AKIBAT JOIN
        |--------------------------------------------------------------------------
        */

        $data = $data->unique(function ($item) {
            return md5(serialize([
                $item->TANGGAL ?? null,
                $item->NAMATINDAKAN ?? null,
                $item->KLINIS ?? null,
                $item->HASIL ?? null,
                $item->KESAN ?? null,
                $item->USUL ?? null,
                $item->BTK ?? null,
                $item->DOKTER ?? null,
            ]));
        })->values();

        $lines = [
            'HASIL PEMERIKSAAN RADIOLOGI',
            str_repeat('─', 38),
        ];

        foreach ($data as $index => $item) {
            if ($index > 0) {
                $lines[] = '';
                $lines[] = str_repeat('─', 38);
            }

            $nomorLaporan = $index + 1;

            $lines[] = '';
            $lines[] = 'LAPORAN RADIOLOGI ' . $nomorLaporan;

            /*
            |--------------------------------------------------------------------------
            | IDENTITAS PEMERIKSAAN
            |--------------------------------------------------------------------------
            */

            $informasi = [
                'Tanggal pemeriksaan' => $this->formatTanggalWaktuMedis(
                    $item->TANGGAL ?? null
                ),

                'Nama pemeriksaan' => $this->value(
                    $item->NAMATINDAKAN ?? null
                ),

                'Unit pengantar' => $this->value(
                    $item->UNITPENGANTAR ?? null
                ),

                'Diagnosis / indikasi' => $this->value(
                    $item->DIAGNOSA ?? null
                ),

                'Dokter pengirim' => $this->value(
                    $item->DOKTERASAL ?? null
                ),

                'Dokter pemeriksa' => $this->value(
                    $item->DOKTER ?? null
                ),

                'Radiografer' => $this->value(
                    $item->RADIOGRAFER ?? null
                ),
            ];

            foreach ($informasi as $label => $nilai) {
                if ($nilai !== '') {
                    $lines[] = $label . ' : ' . $nilai;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | INDIKASI KLINIS
            |--------------------------------------------------------------------------
            */

            $klinis = $this->value(
                $item->KLINIS ?? null
            );

            if ($klinis !== '') {
                $lines[] = '';
                $lines[] = 'INDIKASI KLINIS';
                $lines[] = $klinis;
            }

            /*
            |--------------------------------------------------------------------------
            | HASIL PEMERIKSAAN
            |--------------------------------------------------------------------------
            */

            $hasil = $this->value(
                $item->HASIL ?? null
            );

            if ($hasil !== '') {
                $lines[] = '';
                $lines[] = 'HASIL PEMERIKSAAN';
                $lines[] = $hasil;
            }

            /*
            |--------------------------------------------------------------------------
            | KESAN
            |--------------------------------------------------------------------------
            */

            $kesan = $this->value(
                $item->KESAN ?? null
            );

            if ($kesan !== '') {
                $lines[] = '';
                $lines[] = 'KESAN';
                $lines[] = $kesan;
            }

            /*
            |--------------------------------------------------------------------------
            | USUL / SARAN
            |--------------------------------------------------------------------------
            */

            $usul = $this->value(
                $item->USUL ?? null
            );

            if ($usul !== '') {
                $lines[] = '';
                $lines[] = 'USUL / SARAN';
                $lines[] = $usul;
            }

            /*
            |--------------------------------------------------------------------------
            | BTK
            |--------------------------------------------------------------------------
            */

            $btk = $this->value(
                $item->BTK ?? null
            );

            if ($btk !== '') {
                $lines[] = '';
                $lines[] = 'BTK : ' . $btk;
            }
        }

        $lines[] = '';
        $lines[] = str_repeat('─', 38);
        $lines[] = 'Akhir laporan radiologi';

        return implode("\n", $lines);
    }

    /**
     * Ubah nilai null/kosong menjadi string.
     */
    private function value($value): string
    {
        if ($value === null) {
            return '';
        }

        if (is_bool($value)) {
            return $value ? 'Ya' : 'Tidak';
        }

        return trim((string) $value);
    }

    /**
     * Placeholder default jika data kosong.
     */
    private function placeholder($value): string
    {
        $value = $this->value($value);

        return $value !== '' ? $value : '-';
    }

    /**
     * Format tanggal dan jam untuk dokumen cetak.
     */
    private function formatJam($jam): string
    {
        if (empty($jam)) {
            return '-';
        }

        try {
            return Carbon::parse($jam)->format('H:i') . ' WIB';
        } catch (\Throwable $e) {
            return (string) $jam;
        }
    }
    private function formatTanggal($tanggal): string
    {
        if (empty($tanggal)) {
            return '-';
        }

        try {
            return Carbon::parse($tanggal)
                ->locale('id')
                ->translatedFormat('d F Y');
        } catch (\Throwable $e) {
            return (string) $tanggal;
        }
    }
    /**
     * Format tanggal dan waktu hasil pemeriksaan.
     */
    private function formatTanggalWaktuMedis($tanggal): string
    {
        if (empty($tanggal)) {
            return '';
        }

        try {
            return Carbon::parse($tanggal)
                ->locale('id')
                ->isoFormat('D MMMM YYYY, HH:mm')
                . ' WIB';
        } catch (\Throwable $e) {
            return (string) $tanggal;
        }
    }
}
