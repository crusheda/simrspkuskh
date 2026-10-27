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
            // ->where('PPA', 1)
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
            ]);

        $nutrisi = DB::table('medicalrecord.nutrisi')
            ->where('KUNJUNGAN', $kunjungan)
            // ->where('PPA', 1)
            ->whereIn('STATUS', [1, 2])
            ->orderByDesc('ID')
            ->first([
                'BERAT_BADAN',
                'TINGGI_BADAN',
                'INDEX_MASSA_TUBUH',
            ]);

        /*
        |--------------------------------------------------------------------------
        | PEMERIKSAAN FISIK
        |--------------------------------------------------------------------------
        */

        $anatomi = DB::table(
            'medicalrecord.sirmed_pemeriksaan_anatomi'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first();

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

        $kesadaranGcs = '-';

        if ($tandaVital) {
            $gcs = (int) $this->value($tandaVital->GCS);

            $kesadaranGcs = $gcs;

            if (
                $tandaVital->EYE !== null ||
                $tandaVital->VERBAL !== null ||
                $tandaVital->MOTORIK !== null
            ) {
                $kesadaranGcs .= ' (E'
                    . ($tandaVital->EYE ?? '-')
                    . ' V'
                    . ($tandaVital->VERBAL ?? '-')
                    . ' M'
                    . ($tandaVital->MOTORIK ?? '-')
                    . ')';
            }

            $kesadaranGcs = trim($kesadaranGcs);
        }

        /*
        |--------------------------------------------------------------------------
        | HASIL PEMERIKSAAN FISIK
        |--------------------------------------------------------------------------
        */

        $pemeriksaanFisik = $this->formatPemeriksaanFisik($anatomi);

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
            'TEKANAN_DARAH' => $tekananDarah,
            'NADI' => $nadi,
            'FREKUENSI_NAPAS' => $frekuensiNapas,
            'SUHU' => $tandaVital && $tandaVital->SUHU !== null
                ? $tandaVital->SUHU . ' °C'
                : '-',
            'SATURASI_O2' => $tandaVital && $tandaVital->SATURASI_O2 !== null
                ? ((int) $tandaVital->SATURASI_O2) . ' %'
                : '-',
            'KESADARAN_GCS' => $kesadaranGcs,

            'BERAT_BADAN' => $nutrisi && $nutrisi->BERAT_BADAN !== null
                ? ((int) $nutrisi->BERAT_BADAN) . ' kg'
                : '-',
            'TINGGI_BADAN' => $nutrisi && $nutrisi->TINGGI_BADAN !== null
                ? ((int) $nutrisi->TINGGI_BADAN) . ' cm'
                : '-',
            'IMT' => $this->placeholder(
                $nutrisi->INDEX_MASSA_TUBUH ?? null
            ),

            /*
            | PEMERIKSAAN FISIK
            */

            'PF_MATA' => $this->placeholder(
                $this->formatAnatomi($anatomi, [
                    'pf_anemis' => 'Anemis',
                    'pf_ikterus' => 'Ikterus',
                    'pf_upal' => 'Edema palpebra',
                    'pf_pupil' => 'Pupil',
                    'pf_dia_up' => 'Diameter pupil kanan',
                    'pf_dia_down' => 'Diameter pupil kiri',
                    'pf_kelainan_mata' => 'Kelainan mata',
                ])
            ),

            'PF_MULUT_TENGGOROKAN' => $this->placeholder(
                $this->formatAnatomi($anatomi, [
                    'pf_mulut' => 'Mulut / tenggorokan',
                ])
            ),

            'PF_LEHER' => $this->placeholder(
                $this->formatAnatomi($anatomi, [
                    'pf_jvp' => 'JVP',
                    'pf_pkl' => 'Pembesaran kelenjar limfe',
                    'pf_pkl_lain' => 'Keterangan kelenjar limfe',
                    'pf_kd' => 'Kaku kuduk',
                    'pf_kd_lain' => 'Keterangan kaku kuduk',
                    'pf_kelainan_leher' => 'Kelainan leher',
                ])
            ),

            'PF_DADA' => $this->placeholder(
                $this->formatAnatomi($anatomi, [
                    'pf_thoraks' => 'Thoraks',
                    'pf_thoraks_lain' => 'Keterangan thoraks',
                    'pf_kelainan_dada' => 'Kelainan dada',
                    'pf_dada_lain' => 'Keterangan dada',
                ])
            ),

            'PF_COR' => $this->placeholder(
                $this->formatAnatomi($anatomi, [
                    'pf_cor' => 'Cor',
                    'pf_cor_cb' => 'Irama jantung',
                    'pf_murmur' => 'Murmur',
                    'pf_murmur_lain' => 'Keterangan murmur',
                ])
            ),

            'PF_PULMO' => $this->placeholder(
                $this->formatAnatomi($anatomi, [
                    'pf_pulmo' => 'Pulmo',
                    'pf_ronchi' => 'Ronki (kiri/kanan)',
                    'pf_ronchi_lain' => 'Keterangan ronki',
                    'pf_wheezing' => 'Wheezing (kiri/kanan)',
                    'pf_wheezing_lain' => 'Keterangan wheezing',
                ])
            ),

            'PF_PERUT' => $this->placeholder(
                $this->formatAnatomi($anatomi, [
                    'pf_distended' => 'Distensi abdomen',
                    'pf_meteor' => 'Meteorismus',
                    'pf_asites' => 'Asites',
                    'pf_peristaltik' => 'Peristaltik',
                    'pf_nyeri_tekan' => 'Nyeri tekan',
                    'pf_nyeri_tekan_lain' => 'Lokasi nyeri tekan',
                    'pf_hepar' => 'Hepar',
                    'pf_lien' => 'Lien',
                ])
            ),

            'PF_EKSTREMITAS' => $this->placeholder(
                $this->formatAnatomi($anatomi, [
                    'pf_akral' => 'Akral',
                    'pf_udem' => 'Edema',
                    'pf_udem_lain' => 'Keterangan edema',
                ])
            ),

            'STATUS_LOKALIS' => $this->placeholder(
                $anatomi->status_lokalis ?? null
            ),

            'PEMERIKSAAN_FISIK' => $this->placeholder(
                $pemeriksaanFisik
            ),

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
        $data = DB::table(
            'simrspku_pengkajian.pengkajian_ranap_neonatus'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        if (!$data) {
            return [];
        }

        return [
            'STATUS_FUNGSIONAL' => $this->placeholder(
                $data->STATUS_FUNGSIONAL ?? null
            ),
            'RISIKO_JATUH' => $this->placeholder(
                $data->RISIKO_JATUH ?? null
            ),
            'SKRINING_NYERI' => $this->placeholder(
                $data->SKRINING_NYERI ?? null
            ),
            'KEBUTUHAN_KEPERAWATAN' => $this->placeholder(
                $data->KEBUTUHAN_KEPERAWATAN ?? null
            ),
        ];
    }

    /**
     * ========================================================================
     * FORMAT PEMERIKSAAN FISIK
     * ========================================================================
     */
    private function formatPemeriksaanFisik($anatomi): string
    {
        if (!$anatomi) {
            return '-';
        }

        $bagian = [
            'Mata' => $this->formatAnatomi($anatomi, [
                'pf_anemis' => 'Anemis',
                'pf_ikterus' => 'Ikterus',
                'pf_upal' => 'Edema palpebra',
                'pf_pupil' => 'Pupil',
                'pf_dia_up' => 'Diameter pupil kanan',
                'pf_dia_down' => 'Diameter pupil kiri',
                'pf_kelainan_mata' => 'Kelainan mata',
            ]),
            'Mulut / tenggorokan' => $this->formatAnatomi($anatomi, [
                'pf_mulut' => 'Mulut / tenggorokan',
            ]),
            'Leher' => $this->formatAnatomi($anatomi, [
                'pf_jvp' => 'JVP',
                'pf_pkl' => 'Pembesaran kelenjar limfe',
                'pf_pkl_lain' => 'Keterangan kelenjar limfe',
                'pf_kd' => 'Kaku kuduk',
                'pf_kd_lain' => 'Keterangan kaku kuduk',
                'pf_kelainan_leher' => 'Kelainan leher',
            ]),
            'Thoraks' => $this->formatAnatomi($anatomi, [
                'pf_thoraks' => 'Thoraks',
                'pf_thoraks_lain' => 'Keterangan thoraks',
                'pf_kelainan_dada' => 'Kelainan dada',
                'pf_dada_lain' => 'Keterangan dada',
            ]),
            'Cor' => $this->formatAnatomi($anatomi, [
                'pf_cor' => 'Cor',
                'pf_cor_cb' => 'Irama jantung',
                'pf_murmur' => 'Murmur',
                'pf_murmur_lain' => 'Keterangan murmur',
            ]),
            'Pulmo' => $this->formatAnatomi($anatomi, [
                'pf_pulmo' => 'Pulmo',
                'pf_ronchi' => 'Ronki (kiri/kanan)',
                'pf_ronchi_lain' => 'Keterangan ronki',
                'pf_wheezing' => 'Wheezing (kiri/kanan)',
                'pf_wheezing_lain' => 'Keterangan wheezing',
            ]),
            'Abdomen' => $this->formatAnatomi($anatomi, [
                'pf_distended' => 'Distensi abdomen',
                'pf_meteor' => 'Meteorismus',
                'pf_asites' => 'Asites',
                'pf_peristaltik' => 'Peristaltik',
                'pf_nyeri_tekan' => 'Nyeri tekan',
                'pf_nyeri_tekan_lain' => 'Lokasi nyeri tekan',
                'pf_hepar' => 'Hepar',
                'pf_lien' => 'Lien',
            ]),
            'Ekstremitas' => $this->formatAnatomi($anatomi, [
                'pf_akral' => 'Akral',
                'pf_udem' => 'Edema',
                'pf_udem_lain' => 'Keterangan edema',
            ]),
            'Status lokalis' => $this->value($anatomi->status_lokalis ?? null),
        ];

        $hasil = [];
        foreach ($bagian as $label => $isi) {
            if ($isi !== '') {
                $hasil[] = $label . ': ' . $isi;
            }
        }

        return $hasil ? implode("\n", $hasil) : '-';
    }

    /**
     * Format nilai pemeriksaan anatomi menjadi istilah medis.
     * Nilai NULL/kosong tidak ditafsirkan sebagai hasil negatif.
     */
    private function formatAnatomi($anatomi, array $fields): string
    {
        if (!$anatomi) {
            return '';
        }

        $hasil = [];

        foreach ($fields as $field => $label) {
            $raw = $anatomi->{$field} ?? null;

            // Jangan menyamakan data yang belum diisi dengan hasil negatif.
            if ($raw === null || $raw === '') {
                // Untuk field gabungan, cek nama kolom yang sebenarnya.
                if ($field === 'pf_ronchi') {
                    $kiri = $this->value($anatomi->pf_ronchi_left ?? null);
                    $kanan = $this->value($anatomi->pf_ronchi_right ?? null);
                    if ($kiri !== '' || $kanan !== '') {
                        $hasil[] = $label . ': ' . ($kiri !== '' ? $kiri : '-') . ' / ' . ($kanan !== '' ? $kanan : '-');
                    }
                } elseif ($field === 'pf_wheezing') {
                    $kiri = $this->value($anatomi->pf_wheezing_left ?? null);
                    $kanan = $this->value($anatomi->pf_wheezing_right ?? null);
                    if ($kiri !== '' || $kanan !== '') {
                        $hasil[] = $label . ': ' . ($kiri !== '' ? $kiri : '-') . ' / ' . ($kanan !== '' ? $kanan : '-');
                    }
                } elseif ($field === 'pf_peristaltik') {
                    $peristaltik = [];
                    if ((int) ($anatomi->pf_peristal_normal ?? 0) === 1) $peristaltik[] = 'Normal';
                    if ((int) ($anatomi->pf_peristal_meningkat ?? 0) === 1) $peristaltik[] = 'Meningkat';
                    if ((int) ($anatomi->pf_peristal_menurun ?? 0) === 1) $peristaltik[] = 'Menurun';
                    if ((int) ($anatomi->pf_peristal_tidak ?? 0) === 1) $peristaltik[] = 'Tidak ada';
                    if ($peristaltik) $hasil[] = $label . ': ' . implode(', ', $peristaltik);
                } elseif ($field === 'pf_akral') {
                    if ((int) ($anatomi->pf_extremitas_hangat ?? 0) === 1) {
                        $hasil[] = $label . ': Hangat';
                    } elseif ((int) ($anatomi->pf_extremitas_dingin ?? 0) === 1) {
                        $hasil[] = $label . ': Dingin';
                    }
                }
                continue;
            }

            $nilai = trim((string) $raw);

            // Field checkbox biner: nilai 0 harus tetap tercetak sebagai hasil negatif.
            $biner = [
                'pf_anemis' => ['Anemis', 'Tidak ditemukan anemis'],
                'pf_ikterus' => ['Ikterus', 'Tidak ditemukan ikterus'],
                'pf_upal' => ['Terdapat edema palpebra', 'Tidak ditemukan edema palpebra'],
                'pf_pkl' => ['Terdapat pembesaran kelenjar limfe', 'Tidak ditemukan pembesaran kelenjar limfe'],
                'pf_kd' => ['Terdapat kaku kuduk', 'Tidak ditemukan kaku kuduk'],
                'pf_distended' => ['Abdomen distended', 'Abdomen tidak distended'],
                'pf_meteor' => ['Terdapat meteorismus', 'Tidak ditemukan meteorismus'],
                'pf_asites' => ['Terdapat asites', 'Tidak ditemukan asites'],
                'pf_nyeri_tekan' => ['Terdapat nyeri tekan', 'Tidak ditemukan nyeri tekan'],
                'pf_udem' => ['Terdapat edema', 'Tidak ditemukan edema'],
            ];

            if (isset($biner[$field])) {
                if (in_array($nilai, ['0', '1'], true)) {
                    $hasil[] = $nilai === '1' ? $biner[$field][0] : $biner[$field][1];
                }
                continue;
            }

            // Kolom enumerasi yang memiliki nilai 0/1/2.
            if ($field === 'pf_pupil') {
                $teks = match ($nilai) {
                    '1' => 'Isokor',
                    '2' => 'Anisokor',
                    default => '',
                };
                if ($teks !== '') $hasil[] = 'Pupil: ' . $teks;
                continue;
            }

            if (in_array($field, ['pf_kelainan_leher', 'pf_kelainan_dada'], true)) {
                $teks = match ($nilai) {
                    '0' => 'Tidak diperiksa',
                    '1' => 'Tidak ditemukan kelainan',
                    '2' => 'Ditemukan kelainan',
                    default => '',
                };
                if ($teks !== '') $hasil[] = $label . ': ' . $teks;
                continue;
            }

            if ($field === 'pf_thoraks') {
                $teks = match ($nilai) {
                    '1' => 'Simetris',
                    '2' => 'Asimetris',
                    default => '',
                };
                if ($teks !== '') $hasil[] = $label . ': ' . $teks;
                continue;
            }

            if ($field === 'pf_cor_cb') {
                $teks = match ($nilai) {
                    '1' => 'Reguler',
                    '2' => 'Ireguler',
                    default => '',
                };
                if ($teks !== '') $hasil[] = $label . ': ' . $teks;
                continue;
            }

            // Nilai teks dan tanda +/- tetap ditampilkan, termasuk tanda minus.
            if (in_array($field, ['pf_dia_up', 'pf_dia_down'], true)) {
                $hasil[] = $label . ': ' . $nilai . ' mm';
            } elseif (in_array($field, ['pf_ronchi_lain', 'pf_wheezing_lain'], true)) {
                if (!in_array($nilai, ['-', '0'], true)) $hasil[] = $label . ': ' . $nilai;
            } else {
                $hasil[] = $label . ': ' . $nilai;
            }
        }

        return implode(', ', $hasil);
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
