<?php

namespace App\Services\EMR\Print;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RIDewasaPrintService implements PrintDataInterface
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
     * Pengkajian Awal Medis Rawat Inap Dewasa
     * ========================================================================
     */
    private function getDataDokter(string $kunjungan): array
    {
        /*
        |--------------------------------------------------------------------------
        | IDENTITAS PASIEN DAN KUNJUNGAN
        |--------------------------------------------------------------------------
        */

        $pasien = DB::table('pendaftaran.kunjungan as pk')
            ->join(
                'pendaftaran.pendaftaran as pp',
                'pp.NOMOR',
                '=',
                'pk.NOPEN'
            )
            ->leftJoin(
                'master.pasien as ps',
                'ps.NORM',
                '=',
                'pp.NORM'
            )
            ->leftJoin(
                'master.ruangan as ru',
                'ru.ID',
                '=',
                'pk.RUANGAN'
            )
            ->leftJoin(
                'master.dokter as dr',
                'dr.ID',
                '=',
                'pk.DPJP'
            )
            ->select([
                'pp.NOMOR as NOPEN',
                'pp.NORM',
                'pp.TANGGAL as TGLDAFTAR',

                'pk.NOMOR as KUNJUNGAN',
                'pk.MASUK as TGLKUNJUNGAN',
                'pk.KELUAR as TGLKELUAR',

                'ru.DESKRIPSI as NAMARUANGAN',

                'ps.*',

                DB::raw(
                    'master.getNamaLengkap(ps.NORM) as NAMALENGKAP'
                ),

                DB::raw(
                    'master.getAlamatPasienCustom(ps.NORM) as ALAMAT'
                ),

                DB::raw(
                    'master.getCariUmur(pp.TANGGAL, ps.TANGGAL_LAHIR) as UMUR'
                ),

                DB::raw(
                    'master.getNamaLengkapPegawai(dr.NIP) as NAMADOKTER'
                ),
            ])
            ->where('pk.NOMOR', $kunjungan)
            ->first();

        if (!$pasien) {
            return [];
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
            ->where('PPA', 1)
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
            ->where('PPA', 1)
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
        | TATA LAKSANA, TARGET TERAPI, KONSULTASI, KRITERIA PULANG
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
                ($tandaVital->SISTOLIK ?? '-')
                . '/'
                . ($tandaVital->DISTOLIK ?? '-')
                . ' mmHg';
        }

        $nadi = '-';

        if ($tandaVital && $tandaVital->FREKUENSI_NADI !== null) {
            $nadi = $tandaVital->FREKUENSI_NADI . ' x/menit';

            if ($tandaVital->FREKUENSI_NADI_CB) {
                $nadi .= ' (' . $tandaVital->FREKUENSI_NADI_CB . ')';
            }
        }

        $frekuensiNapas = '-';

        if ($tandaVital && $tandaVital->FREKUENSI_NAFAS !== null) {
            $frekuensiNapas =
                $tandaVital->FREKUENSI_NAFAS . ' x/menit';

            if ($tandaVital->FREKUENSI_NAFAS_CB) {
                $frekuensiNapas .=
                    ' (' . $tandaVital->FREKUENSI_NAFAS_CB . ')';
            }
        }

        $kesadaranGcs = '-';

        if ($tandaVital) {
            $gcs = $this->value($tandaVital->GCS);

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
        | RETURN DATA TEMPLATE
        |--------------------------------------------------------------------------
        */

        return [
            /*
            | IDENTITAS PASIEN
            */

            'KUNJUNGAN' => $this->value($pasien->KUNJUNGAN),
            'NOPEN' => $this->value($pasien->NOPEN),
            'NORM' => $this->value($pasien->NORM),

            'NAMALENGKAP' => $this->value($pasien->NAMALENGKAP),
            'JK' => $this->value(
                $pasien->JENIS_KELAMIN
                    ?? $pasien->JK
                    ?? ''
            ),
            'TGLLAHIR' => $this->formatTanggal(
                $pasien->TANGGAL_LAHIR ?? null
            ),
            'UMUR' => $this->value($pasien->UMUR),
            'ALAMAT' => $this->value($pasien->ALAMAT),

            'TGLDAFTAR' => $this->formatTanggal(
                $pasien->TGLDAFTAR
            ),
            'TGLKUNJUNGAN' => $this->formatTanggal(
                $pasien->TGLKUNJUNGAN
            ),
            'TGLKELUAR' => $this->formatTanggal(
                $pasien->TGLKELUAR
            ),

            'NAMARUANGAN' => $this->value($pasien->NAMARUANGAN),
            'NAMADOKTER' => $this->value($pasien->NAMADOKTER),

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
                ? $tandaVital->SATURASI_O2 . ' %'
                : '-',
            'KESADARAN_GCS' => $kesadaranGcs,

            'BERAT_BADAN' => $nutrisi && $nutrisi->BERAT_BADAN !== null
                ? $nutrisi->BERAT_BADAN . ' kg'
                : '-',
            'TINGGI_BADAN' => $nutrisi && $nutrisi->TINGGI_BADAN !== null
                ? $nutrisi->TINGGI_BADAN . ' cm'
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
                    'pf_upal' => 'Pupil isokor',
                    'pf_dia_up' => 'Diameter pupil atas',
                    'pf_dia_down' => 'Diameter pupil bawah',
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
                    'pf_kd' => 'Kelainan daerah leher',
                    'pf_kd_lain' => 'Keterangan kelainan daerah leher',
                    'pf_kelainan_leher' => 'Kelainan leher',
                ])
            ),

            'PF_DADA' => $this->placeholder(
                $this->formatAnatomi($anatomi, [
                    'pf_thoraks' => 'Kelainan thoraks',
                    'pf_thoraks_lain' => 'Keterangan thoraks',
                    'pf_kelainan_dada' => 'Kelainan dada',
                    'pf_dada_lain' => 'Keterangan dada',
                ])
            ),

            'PF_COR' => $this->placeholder(
                $this->formatAnatomi($anatomi, [
                    'pf_cor' => 'Cor',
                    'pf_cor_cb' => 'Cor CB',
                    'pf_murmur' => 'Murmur',
                    'pf_murmur_lain' => 'Keterangan murmur',
                ])
            ),

            'PF_PULMO' => $this->placeholder(
                $this->formatAnatomi($anatomi, [
                    'pf_pulmo' => 'Pulmo',
                    'pf_ronchi' => 'Ronchi',
                    'pf_ronchi_lain' => 'Keterangan ronchi',
                    'pf_wheezing' => 'Wheezing',
                    'pf_wheezing_lain' => 'Keterangan wheezing',
                ])
            ),

            'PF_PERUT' => $this->placeholder(
                $this->formatAnatomi($anatomi, [
                    'pf_distended' => 'Distensi',
                    'pf_meteor' => 'Meteorismus',
                    'pf_asites' => 'Asites',
                    'pf_peristal_normal' => 'Peristaltik normal',
                    'pf_peristal_meningkat' => 'Peristaltik meningkat',
                    'pf_peristal_menurun' => 'Peristaltik menurun',
                    'pf_peristal_tidak' => 'Peristaltik tidak ada',
                    'pf_nyeri_tekan' => 'Nyeri tekan',
                    'pf_nyeri_tekan_lain' => 'Keterangan nyeri tekan',
                    'pf_hepar' => 'Hepar',
                    'pf_lien' => 'Lien',
                ])
            ),

            'PF_EKSTREMITAS' => $this->placeholder(
                $this->formatAnatomi($anatomi, [
                    'pf_extremitas_hangat' => 'Hangat',
                    'pf_extremitas_dingin' => 'Dingin',
                    'pf_udem' => 'Udem',
                    'pf_udem_lain' => 'Keterangan udem',
                ])
            ),

            'STATUS_LOKALIS' => $this->placeholder(
                $anatomi->status_lokalis ?? null
            ),

            'PEMERIKSAAN_FISIK' => $this->placeholder(
                $pemeriksaanFisik
            ),

            /*
            | PEMERIKSAAN PENUNJANG
            |
            | Placeholder disediakan untuk template.
            | Query laboratorium/radiologi harus disesuaikan dengan
            | tabel penyimpanan komponen pemeriksaan yang sebenarnya.
            */

            'HASIL_LABORATORIUM' => '-',
            'HASIL_RADIOLOGI' => '-',

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
                $kriteriaPulang->KRITERIA_PULANG ?? null
            ),
            'KRITERIA_PULANG_HARI' => $this->placeholder(
                $kriteriaPulang->HARI ?? null
            ),
            'KRITERIA_PULANG_KARENA' => $this->placeholder(
                $kriteriaPulang->KARENA ?? null
            ),

            /*
            | CETAK
            */

            'TANGGAL_CETAK' => now()->translatedFormat(
                'DD MMMM YYYY [Pukul] HH:mm:ss'
            ),
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
            'simrspku_pengkajian.pengkajian_ranap_dewasa'
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
            'TANGGAL_CETAK' => now()->translatedFormat(
                'DD MMMM YYYY [Pukul] HH:mm:ss'
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
                'pf_upal' => 'Pupil isokor',
                'pf_kelainan_mata' => 'Kelainan mata',
            ]),
            'Mulut / tenggorokan' => $this->formatAnatomi($anatomi, [
                'pf_mulut' => 'Mulut / tenggorokan',
            ]),
            'Leher' => $this->formatAnatomi($anatomi, [
                'pf_jvp' => 'JVP',
                'pf_pkl_lain' => 'Kelenjar limfe',
                'pf_kelainan_leher' => 'Kelainan leher',
            ]),
            'Thoraks' => $this->formatAnatomi($anatomi, [
                'pf_thoraks_lain' => 'Thoraks',
                'pf_cor' => 'Cor',
                'pf_murmur' => 'Murmur',
                'pf_pulmo' => 'Pulmo',
                'pf_ronchi_lain' => 'Ronchi',
                'pf_wheezing_lain' => 'Wheezing',
            ]),
            'Abdomen' => $this->formatAnatomi($anatomi, [
                'pf_nyeri_tekan_lain' => 'Nyeri tekan',
                'pf_hepar' => 'Hepar',
                'pf_lien' => 'Lien',
            ]),
            'Ekstremitas' => $this->formatAnatomi($anatomi, [
                'pf_udem_lain' => 'Udem',
            ]),
            'Status lokalis' => $this->value(
                $anatomi->status_lokalis ?? null
            ),
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
     * Format sekumpulan kolom anatomi menjadi teks.
     */
    private function formatAnatomi($anatomi, array $fields): string
    {
        if (!$anatomi) {
            return '';
        }

        $hasil = [];

        foreach ($fields as $field => $label) {
            $nilai = $this->value($anatomi->{$field} ?? null);

            if ($nilai === '') {
                continue;
            }

            /*
             * Checkbox bernilai 1 ditampilkan sebagai label.
             * Nilai teks ditampilkan bersama labelnya.
             */
            if (in_array($nilai, ['1', 'true', 'TRUE'], true)) {
                $hasil[] = $label;
            } elseif (!in_array($nilai, ['0', 'false', 'FALSE'], true)) {
                $hasil[] = $label . ': ' . $nilai;
            }
        }

        return implode(', ', $hasil);
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
     * Format tanggal untuk dokumen cetak.
     */
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
}
