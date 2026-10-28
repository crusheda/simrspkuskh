<?php

namespace App\Services\EMR\Print;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RIAnakPrintService implements PrintDataInterface
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
     * Pengkajian Awal Medis Rawat Inap Anak
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
            ->where('fin.FORM', 'pengkajian-ranap-anak')
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
            ->where('fin.FORM', 'pengkajian-ranap-anak')
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
            ->where('fin.FORM', 'pengkajian-ranap-anak')
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

        $labelPilihan = static function ($nilai, array $opsi): string {
            $nilai = trim((string) ($nilai ?? ''));
            return $nilai !== '' ? ($opsi[$nilai] ?? $nilai) : '';
        };

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
        | PEMERIKSAAN FISIK RAWAT INAP
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

        $perkemihan = DB::table('medicalrecord.sirmed_sistem_perkemihan')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first();

        $pencernaan = DB::table('medicalrecord.sirmed_sistem_pencernaan')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first();

        $muskuloskeletal = DB::table(
            'medicalrecord.sirmed_sistem_muskuloskeletal_integumen'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first();

        $endokrin = DB::table('medicalrecord.sirmed_sistem_endokrin')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first();

        $centangFisik = static function ($nilai, $pilihan): string {
            return (string) ($nilai ?? '') === (string) $pilihan ? '☑' : '☐';
        };

        $tambahChecklistFisik = static function (
            array &$baris,
            $nilai,
            string $label,
            array $opsi
        ) use ($centangFisik): void {
            $pilihan = [];

            foreach ($opsi as $value => $nama) {
                $pilihan[] = $centangFisik($nilai, $value) . ' ' . $nama;
            }

            $baris[] = $label . ': ' . implode('   ', $pilihan);
        };

        $tambahNilaiFisik = function (
            array &$baris,
            string $label,
            $nilai,
            string $satuan = ''
        ): void {
            $nilai = $this->value($nilai);

            if ($nilai !== '') {
                $baris[] = $label . ': ' . $nilai . $satuan;
            }
        };

        $detailPemeriksaanFisikRI = [];

        /* Sistem pernafasan */
        $detailPemeriksaanFisikRI[] = 'SISTEM PERNAFASAN';

        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $pernafasan->SESAK_NAFAS ?? null,
            'Sesak napas',
            [1 => 'Tidak', 2 => 'Ya']
        );
        $tambahNilaiFisik(
            $detailPemeriksaanFisikRI,
            'Jenis sesak napas',
            $pernafasan->SESAK_NAFAS_JENIS ?? null
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $pernafasan->BATUK ?? null,
            'Batuk',
            [1 => 'Tidak', 2 => 'Ya']
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $pernafasan->NYERI_WAKTU_NAFAS ?? null,
            'Nyeri waktu napas',
            [1 => 'Tidak', 2 => 'Ya']
        );

        foreach ([
            'SEKRET_WARNA' => 'Sekret/sputum warna',
            'SEKRET_JUMLAH' => 'Sekret/sputum jumlah',
            'SEKRET_BAU' => 'Sekret/sputum bau',
            'SEKRET_JENIS' => 'Sekret/sputum jenis',
        ] as $kolom => $label) {
            $tambahNilaiFisik(
                $detailPemeriksaanFisikRI,
                $label,
                $pernafasan->{$kolom} ?? null
            );
        }

        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $pernafasan->IRAMA_NAFAS ?? null,
            'Irama napas',
            [1 => 'Tidak', 2 => 'Ya']
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $pernafasan->IRAMA_NAFAS_JENIS ?? null,
            'Jenis irama napas',
            [1 => 'Dispnea', 2 => 'Kussmaul']
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $pernafasan->SUARA_NAFAS ?? null,
            'Suara napas',
            [
                1 => 'Vesikuler',
                2 => 'Bronko vesikuler',
                3 => 'Ronki',
                4 => 'Wheezing',
                5 => 'Lain-lain',
            ]
        );
        $tambahNilaiFisik(
            $detailPemeriksaanFisikRI,
            'Suara napas lain-lain',
            $pernafasan->SUARA_NAFAS_LAIN ?? null
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $pernafasan->ALAT_BANTU_NAFAS ?? null,
            'Alat bantu napas',
            [1 => 'Tidak', 2 => 'Ya']
        );
        $tambahNilaiFisik(
            $detailPemeriksaanFisikRI,
            'Jenis alat bantu napas',
            $pernafasan->ALAT_BANTU_NAFAS_JENIS ?? null
        );
        $tambahNilaiFisik(
            $detailPemeriksaanFisikRI,
            'Flow alat bantu napas',
            $pernafasan->ALAT_BANTU_NAFAS_FLOW ?? null
        );

        /* Sistem kardiovaskuler */
        $detailPemeriksaanFisikRI[] = '';
        $detailPemeriksaanFisikRI[] = 'SISTEM KARDIOVASKULER';

        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $kardiovaskuler->NYERI_DADA ?? null,
            'Nyeri dada',
            [1 => 'Tidak', 2 => 'Ya']
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $kardiovaskuler->PULSASI ?? null,
            'Pulsasi',
            [
                1 => 'Sangat kuat',
                2 => 'Kuat',
                3 => 'Lemah, teraba',
                4 => 'Hilang timbul',
                5 => 'Tidak teraba',
                6 => 'CRT',
            ]
        );
        $tambahNilaiFisik(
            $detailPemeriksaanFisikRI,
            'CRT',
            $kardiovaskuler->PULSASI_CRT ?? null,
            ' detik'
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $kardiovaskuler->AKRAL ?? null,
            'Akral',
            [1 => 'Hangat', 2 => 'Dingin', 3 => 'Lain-lain']
        );
        $tambahNilaiFisik(
            $detailPemeriksaanFisikRI,
            'Akral lain-lain',
            $kardiovaskuler->AKRAL_LAIN ?? null
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $kardiovaskuler->IRAMA_JANTUNG ?? null,
            'Irama jantung',
            [1 => 'Reguler', 2 => 'Ireguler']
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $kardiovaskuler->PERDARAHAN ?? null,
            'Perdarahan',
            [1 => 'Tidak ada', 2 => 'Ada']
        );
        $tambahNilaiFisik(
            $detailPemeriksaanFisikRI,
            'Lokasi perdarahan',
            $kardiovaskuler->PERDARAHAN_LOKASI ?? null
        );
        $tambahNilaiFisik(
            $detailPemeriksaanFisikRI,
            'Keterangan kardiovaskuler lainnya',
            $kardiovaskuler->LAIN_LAIN ?? null
        );

        /* Sistem persyarafan */
        $detailPemeriksaanFisikRI[] = '';
        $detailPemeriksaanFisikRI[] = 'SISTEM PERSYARAFAN';

        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $persyarafan->SKLERA ?? null,
            'Sklera',
            [1 => 'Normal', 2 => 'Anemia', 3 => 'Ikterus']
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $persyarafan->PUPIL ?? null,
            'Pupil',
            [1 => 'Isokor', 2 => 'Anisokor', 3 => 'Midriasis', 4 => 'Miosis']
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $persyarafan->PENGLIHATAN ?? null,
            'Penglihatan',
            [1 => 'Normal', 2 => 'Tidak normal', 3 => 'Kacamata']
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $persyarafan->PENDENGARAN ?? null,
            'Pendengaran',
            [1 => 'Normal', 2 => 'Tuli kanan/kiri', 3 => 'Alat bantu dengar']
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $persyarafan->PENCIUMAN ?? null,
            'Penciuman',
            [1 => 'Normal', 2 => 'Tidak normal']
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $persyarafan->KEJANG ?? null,
            'Kejang',
            [1 => 'Tidak', 2 => 'Ya']
        );
        $tambahNilaiFisik(
            $detailPemeriksaanFisikRI,
            'Istirahat/tidur',
            $persyarafan->ISTIRAHAT_TIDUR ?? null,
            ' jam'
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $persyarafan->GANGGUAN_TIDUR ?? null,
            'Gangguan tidur',
            [1 => 'Tidak', 2 => 'Ya']
        );
        $tambahNilaiFisik(
            $detailPemeriksaanFisikRI,
            'Keterangan gangguan tidur',
            $persyarafan->GANGGUAN_TIDUR_LAIN ?? null
        );
        $tambahNilaiFisik(
            $detailPemeriksaanFisikRI,
            'Keterangan persyarafan lainnya',
            $persyarafan->LAIN_LAIN ?? null
        );

        /* Sistem perkemihan */
        $detailPemeriksaanFisikRI[] = '';
        $detailPemeriksaanFisikRI[] = 'SISTEM PERKEMIHAN';

        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $perkemihan->KEBERSIHAN_AREA_GENITAL ?? null,
            'Kebersihan area genital',
            [1 => 'Bersih', 2 => 'Kurang bersih']
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $perkemihan->KANDUNG_KEMIH_MEMBESAR ?? null,
            'Kandung kemih membesar',
            [1 => 'Tidak', 2 => 'Ya']
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $perkemihan->BAK ?? null,
            'BAK',
            [
                1 => 'Normal',
                2 => 'Ada keluhan',
                3 => 'Inkontinensia',
                4 => 'Disuria',
                5 => 'Anuria',
                6 => 'Warna',
            ]
        );
        $tambahNilaiFisik(
            $detailPemeriksaanFisikRI,
            'Keterangan anuria',
            $perkemihan->BAK_ANURIA ?? null
        );
        $tambahNilaiFisik(
            $detailPemeriksaanFisikRI,
            'Warna BAK',
            $perkemihan->BAK_WARNA ?? null
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $perkemihan->NYERI_TEKAN ?? null,
            'Nyeri tekan',
            [1 => 'Tidak', 2 => 'Ya']
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $perkemihan->ALAT_BANTU_KATETER ?? null,
            'Alat bantu kateter',
            [1 => 'Tidak', 2 => 'Ya']
        );
        $tambahNilaiFisik(
            $detailPemeriksaanFisikRI,
            'Sejak tanggal',
            $perkemihan->SEJAK_TANGGAL ?? null
        );
        $tambahNilaiFisik(
            $detailPemeriksaanFisikRI,
            'Keterangan perkemihan lainnya',
            $perkemihan->LAIN_LAIN ?? null
        );

        /* Sistem pencernaan */
        $detailPemeriksaanFisikRI[] = '';
        $detailPemeriksaanFisikRI[] = 'SISTEM PENCERNAAN';

        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $pencernaan->MULUT ?? null,
            'Mulut',
            [1 => 'Bersih', 2 => 'Kotor']
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $pencernaan->MUKOSA ?? null,
            'Mukosa',
            [1 => 'Lembab', 2 => 'Kering', 3 => 'Stomatitis']
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $pencernaan->JENIS_DIET ?? null,
            'Jenis diet',
            [1 => 'Padat', 2 => 'Lunak', 3 => 'Cair']
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $pencernaan->NAFSU_MAKAN ?? null,
            'Nafsu makan',
            [1 => 'Tidak', 2 => 'Ya']
        );
        $tambahNilaiFisik(
            $detailPemeriksaanFisikRI,
            'Frekuensi makan',
            $pencernaan->NAFSU_MAKAN_FREKUENSI ?? null
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $pencernaan->MUAL ?? null,
            'Mual',
            [1 => 'Tidak', 2 => 'Ya']
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $pencernaan->MUNTAH ?? null,
            'Muntah',
            [1 => 'Tidak', 2 => 'Ya']
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $pencernaan->TENGGOROKAN ?? null,
            'Tenggorokan',
            [
                1 => 'Dalam batas normal',
                2 => 'Sakit menelan',
                3 => 'Kesulitan menelan',
                4 => 'Pembesaran tonsil',
            ]
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $pencernaan->ABDOMEN ?? null,
            'Abdomen',
            [
                1 => 'Dalam batas normal',
                2 => 'Tegang/distended',
                3 => 'Kembung',
                4 => 'Ascites',
            ]
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $pencernaan->NYERI_TEKAN ?? null,
            'Nyeri tekan',
            [1 => 'Tidak', 2 => 'Ya']
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $pencernaan->KONSISTENSI ?? null,
            'Konsistensi',
            [1 => 'Keras', 2 => 'Lunak/cair', 3 => 'Lendir/darah']
        );
        $tambahNilaiFisik(
            $detailPemeriksaanFisikRI,
            'Peristaltik',
            $pencernaan->PERISTALTIK ?? null
        );
        $tambahNilaiFisik(
            $detailPemeriksaanFisikRI,
            'BAB',
            $pencernaan->BAB ?? null
        );
        $tambahNilaiFisik(
            $detailPemeriksaanFisikRI,
            'Keterangan pencernaan lainnya',
            $pencernaan->LAIN_LAIN ?? null
        );

        /* Sistem muskuloskeletal dan integumen */
        $detailPemeriksaanFisikRI[] = '';
        $detailPemeriksaanFisikRI[] = 'SISTEM MUSKULOSKELETAL DAN INTEGUMEN';

        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $muskuloskeletal->PERGERAKAN_SENDI ?? null,
            'Pergerakan sendi',
            [1 => 'Bebas', 2 => 'Terbatas']
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $muskuloskeletal->KELAINAN_EKSTREMITAS ?? null,
            'Kelainan ekstremitas',
            [1 => 'Tidak', 2 => 'Ya']
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $muskuloskeletal->KELAINAN_TULANG_BELAKANG ?? null,
            'Kelainan tulang belakang',
            [1 => 'Tidak', 2 => 'Ya']
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $muskuloskeletal->FRAKTUR ?? null,
            'Fraktur',
            [1 => 'Tidak', 2 => 'Ya']
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $muskuloskeletal->GIPS_SPALK_TRAKSI ?? null,
            'Gips/spalk/traksi',
            [1 => 'Tidak ada', 2 => 'Ada']
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $muskuloskeletal->KOMPARTEMEN_SYNDROME ?? null,
            'Kompartemen syndrome',
            [1 => 'Tidak', 2 => 'Ya']
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $muskuloskeletal->KULIT ?? null,
            'Kulit',
            [
                1 => 'Dalam batas normal',
                2 => 'Ikterus',
                3 => 'Sianosis',
                4 => 'Kemerahan',
                5 => 'Hiperpigmentasi',
            ]
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $muskuloskeletal->TURGOR ?? null,
            'Turgor',
            [1 => 'Baik', 2 => 'Kurang', 3 => 'Jelek']
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $muskuloskeletal->OEDEMA ?? null,
            'Oedema',
            [1 => 'Tidak', 2 => 'Ya']
        );
        $tambahNilaiFisik(
            $detailPemeriksaanFisikRI,
            'Lokasi oedema',
            $muskuloskeletal->OEDEMA_LOKASI ?? null
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $muskuloskeletal->LUKA_DEKUBITUS ?? null,
            'Luka dekubitus',
            [1 => 'Tidak', 2 => 'Ya']
        );
        $tambahNilaiFisik(
            $detailPemeriksaanFisikRI,
            'Keterangan muskuloskeletal lainnya',
            $muskuloskeletal->LAIN_LAIN ?? null
        );

        /* Sistem endokrin */
        $detailPemeriksaanFisikRI[] = '';
        $detailPemeriksaanFisikRI[] = 'SISTEM ENDOKRIN';

        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $endokrin->PERBESARAN_KELENJAR_TIROID ?? null,
            'Perbesaran kelenjar tiroid',
            [1 => 'Tidak', 2 => 'Ya']
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $endokrin->PEMBESARAN_KELENJAR_GETAH_BENING ?? null,
            'Pembesaran kelenjar getah bening',
            [1 => 'Tidak', 2 => 'Ya']
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $endokrin->HIPOGLIKEMIA ?? null,
            'Hipoglikemia',
            [1 => 'Tidak', 2 => 'Ya']
        );
        $tambahChecklistFisik(
            $detailPemeriksaanFisikRI,
            $endokrin->HIPERGLIKEMIA ?? null,
            'Hiperglikemia',
            [1 => 'Tidak', 2 => 'Ya']
        );

        /*
        |--------------------------------------------------------------------------
        | AKTIVITAS, LATIHAN, DAN PERSONAL HYGIENE
        |--------------------------------------------------------------------------
        */
        $alph = DB::table(
            'medicalrecord.sirmed_aktivitas_latihan_personal_hygiene'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first([
                'TINGKAT_KETERGANTUNGAN',
                'MANDI',
                'GANTI_PAKAIAN',
                'KERAMAS',
                'GOSOK_GIGI',
                'MEMOTONG_KUKU',
                'TIDUR_SIANG',
                'TIDUR_MALAM',
            ]);

        $detailALPH = [];

        if ($alph) {
            $detailALPH[] = 'Tingkat ketergantungan: '
                . $centangFisik($alph->TINGKAT_KETERGANTUNGAN ?? null, 1)
                . ' Total   '
                . $centangFisik($alph->TINGKAT_KETERGANTUNGAN ?? null, 2)
                . ' Bantuan sebagian   '
                . $centangFisik($alph->TINGKAT_KETERGANTUNGAN ?? null, 3)
                . ' Bantuan minimal   '
                . $centangFisik($alph->TINGKAT_KETERGANTUNGAN ?? null, 4)
                . ' Mandiri';

            foreach ([
                'MANDI' => 'Mandi',
                'GANTI_PAKAIAN' => 'Ganti pakaian',
                'KERAMAS' => 'Keramas',
                'GOSOK_GIGI' => 'Gosok gigi',
            ] as $kolom => $label) {
                $nilai = $this->value($alph->{$kolom} ?? null);

                if ($nilai !== '') {
                    $detailALPH[] = $label . ': ' . $nilai . ' x/hari';
                }
            }

            $detailALPH[] = 'Memotong kuku: '
                . $centangFisik($alph->MEMOTONG_KUKU ?? null, 0)
                . ' Tidak   '
                . $centangFisik($alph->MEMOTONG_KUKU ?? null, 1)
                . ' Ya';

            foreach ([
                'TIDUR_SIANG' => 'Tidur siang',
                'TIDUR_MALAM' => 'Tidur malam',
            ] as $kolom => $label) {
                $nilai = $this->value($alph->{$kolom} ?? null);

                if ($nilai !== '') {
                    $detailALPH[] = $label . ': ' . $nilai . ' jam';
                }
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

        if ($humptyDumpty) {
            $centangHumpty = static function ($nilai, $pilihan): string {
                return (string) ($nilai ?? '') === (string) $pilihan
                    ? '☑'
                    : '☐';
            };

            $kriteriaHumpty = [
                'Usia' => [
                    'kolom' => 'UMUR',
                    'opsi' => [
                        1 => '< 3 tahun',
                        2 => '3 s.d. < 7 tahun',
                        3 => '7 s.d. < 13 tahun',
                        4 => '>= 13 tahun',
                    ],
                ],
                'Jenis kelamin' => [
                    'kolom' => 'JENIS_KELAMIN',
                    'opsi' => [
                        2 => 'Perempuan',
                        1 => 'Laki-laki',
                    ],
                ],
                'Diagnosa' => [
                    'kolom' => 'DIAGNOSA',
                    'opsi' => [
                        4 => 'Kelainan neurologis',
                        3 => 'Gangguan perilaku/psikiatri',
                        2 => 'Perubahan oksigenasi',
                        1 => 'Diagnosis lainnya',
                    ],
                ],
                'Gangguan kognitif' => [
                    'kolom' => 'GANGGUAN_KONGNITIF',
                    'opsi' => [
                        3 => 'Belum punya kontrol diri/gelisah',
                        2 => 'Lupa kondisi sakit/kadang gelisah',
                        1 => 'Orientasi terhadap kemampuan diri',
                    ],
                ],
                'Faktor lingkungan' => [
                    'kolom' => 'FAKTOR_LINGKUNGAN',
                    'opsi' => [
                        4 => 'Riwayat jatuh/tempat tidur dewasa',
                        3 => 'Alat bantu/kursi/pangkuan',
                        2 => 'Tempat tidur bayi/khusus anak',
                        1 => 'Area di luar rumah sakit',
                    ],
                ],
                'Respon terhadap tindakan operasi' => [
                    'kolom' => 'RESPON',
                    'opsi' => [
                        3 => 'Dalam 24 jam',
                        2 => 'Dalam 48 jam',
                        1 => '48 jam/tidak menjalani operasi',
                    ],
                ],
                'Penggunaan obat' => [
                    'kolom' => 'PENGGUNAAN_OBAT',
                    'opsi' => [
                        3 => 'Multiple sedatif dan obat terkait',
                        2 => 'Salah satu obat terkait',
                        1 => 'Obat lain/tidak menggunakan obat terkait',
                    ],
                ],
            ];

            $detailRisikoJatuh[] = 'Humpty Dumpty:';

            $totalSkorHumpty = 0;
            $semuaKriteriaHumptyTerisi = true;

            foreach ($kriteriaHumpty as $label => $kriteria) {
                $nilai = $humptyDumpty->{$kriteria['kolom']} ?? null;
                $pilihan = [];
                $nilaiValid = false;

                foreach ($kriteria['opsi'] as $kode => $namaOpsi) {
                    $pilihan[] = $centangHumpty($nilai, $kode) . ' ' . $namaOpsi;

                    if ((string) ($nilai ?? '') === (string) $kode) {
                        // Nilai pilihan pada form adalah skor Humpty Dumpty.
                        $totalSkorHumpty += (int) $kode;
                        $nilaiValid = true;
                    }
                }

                $detailRisikoJatuh[] = $label . ': ' . implode('   ', $pilihan);

                if (!$nilaiValid) {
                    $semuaKriteriaHumptyTerisi = false;
                }
            }

            if ($semuaKriteriaHumptyTerisi) {
                if ($totalSkorHumpty >= 12) {
                    $kategoriHumpty = 'High Risk Dumpty';
                    $keteranganHumpty =
                        'Lakukan pencegahan risiko jatuh sesuai prosedur.';
                } else {
                    $kategoriHumpty = 'Low Humpty Dumpty';
                    $keteranganHumpty =
                        'Monitoring dan evaluasi risiko jatuh sesuai prosedur.';
                }

                $detailRisikoJatuh[] =
                    'Skor Humpty Dumpty: '
                    . $totalSkorHumpty
                    . ' (' . $kategoriHumpty . ')';

                $detailRisikoJatuh[] = $keteranganHumpty;
            }

            $detailRisikoJatuh[] = '';
        } else {
            $detailRisikoJatuh[] = '';
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
        | SKRINING DEKUBITUS
        |--------------------------------------------------------------------------
        */
        $dekubitus = DB::table('medicalrecord.penilaian_dekubitus')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first([
                'KONDISI_FISIK',
                'KESADARAN',
                'AKTIVITAS',
                'MOBILITAS',
                'INKONTINENSIA',
            ]);

        $centangDekubitus = static function ($nilai, $pilihan): string {
            return (string) ($nilai ?? '') === (string) $pilihan ? '☑' : '☐';
        };

        $detailDekubitus = [];

        if ($dekubitus) {
            $kriteriaDekubitus = [
                'Kondisi fisik umum' => [
                    'kolom' => 'KONDISI_FISIK',
                    'opsi' => [
                        1 => ['label' => 'Baik', 'skor' => 4],
                        2 => ['label' => 'Lumayan', 'skor' => 3],
                        3 => ['label' => 'Buruk', 'skor' => 2],
                        4 => ['label' => 'Sangat buruk', 'skor' => 1],
                    ],
                ],
                'Kesadaran' => [
                    'kolom' => 'KESADARAN',
                    'opsi' => [
                        1 => ['label' => 'Kompos mentis', 'skor' => 4],
                        2 => ['label' => 'Apatis', 'skor' => 3],
                        3 => ['label' => 'Konfus/soporis', 'skor' => 2],
                        4 => ['label' => 'Stupor/koma', 'skor' => 1],
                    ],
                ],
                'Aktivitas' => [
                    'kolom' => 'AKTIVITAS',
                    'opsi' => [
                        1 => ['label' => 'Dapat berpindah', 'skor' => 4],
                        2 => ['label' => 'Berjalan dengan bantuan', 'skor' => 3],
                        3 => ['label' => 'Terbatas di kursi', 'skor' => 2],
                        4 => ['label' => 'Terbatas di tempat tidur', 'skor' => 1],
                    ],
                ],
                'Mobilitas' => [
                    'kolom' => 'MOBILITAS',
                    'opsi' => [
                        1 => ['label' => 'Bergerak bebas', 'skor' => 4],
                        2 => ['label' => 'Sedikit terbatas', 'skor' => 3],
                        3 => ['label' => 'Sangat terbatas', 'skor' => 2],
                        4 => ['label' => 'Tidak bisa bergerak', 'skor' => 1],
                    ],
                ],
                'Inkontinensia' => [
                    'kolom' => 'INKONTINENSIA',
                    'opsi' => [
                        1 => ['label' => 'Tidak ngompol', 'skor' => 4],
                        2 => ['label' => 'Kadang-kadang', 'skor' => 3],
                        3 => ['label' => 'Sering inkontinensia urin', 'skor' => 2],
                        4 => ['label' => 'Sering inkontinensia alvi dan urin', 'skor' => 1],
                    ],
                ],
            ];

            $detailDekubitus[] = 'SKRINING DEKUBITUS';

            $totalSkorDekubitus = 0;
            $adaNilaiDekubitus = false;

            foreach ($kriteriaDekubitus as $label => $kriteria) {
                $nilai = $dekubitus->{$kriteria['kolom']} ?? null;
                $pilihan = [];

                foreach ($kriteria['opsi'] as $kode => $opsi) {
                    $pilihan[] = $centangDekubitus($nilai, $kode)
                        . ' ' . $opsi['label'];

                    if ((string) ($nilai ?? '') === (string) $kode) {
                        $totalSkorDekubitus += $opsi['skor'];
                        $adaNilaiDekubitus = true;
                    }
                }

                $detailDekubitus[] = $label . ': ' . implode('   ', $pilihan);
            }

            if ($adaNilaiDekubitus) {
                if ($totalSkorDekubitus <= 11) {
                    $kategoriDekubitus = 'Peningkatan Risiko';
                    $keteranganDekubitus =
                        'Risiko 50x lebih besar terjadinya ulkus decubitus.';
                } elseif ($totalSkorDekubitus <= 13) {
                    $kategoriDekubitus = 'Risiko Sedang';
                    $keteranganDekubitus =
                        'Pasien memiliki risiko sedang terjadinya ulkus decubitus.';
                } elseif ($totalSkorDekubitus === 14) {
                    $kategoriDekubitus = 'Risiko Tinggi';
                    $keteranganDekubitus =
                        'Risiko tinggi terjadinya ulkus decubitus.';
                } else {
                    $kategoriDekubitus = 'Risiko Kecil';
                    $keteranganDekubitus =
                        'Risiko kecil terjadinya ulkus decubitus.';
                }

                $detailDekubitus[] = 'Skor: '
                    . $totalSkorDekubitus . ' (' . $kategoriDekubitus . ')';
                $detailDekubitus[] = $keteranganDekubitus;
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
            'GANGGUAN_PERTUKARAN_GAS' => 'Gangguan pertukaran gas',
            'GANGGUAN_VENTILASI_SPONTAN' => 'Gangguan ventilasi spontan',
            'POLA_NYERI_TIDAK_EFEKTIF' => 'Pola nyeri tidak efektif',
            'GANGGUAN_SIRKULASI_SPONTAN' => 'Gangguan sirkulasi spontan',
            'PENURUNAN_CURAH_JANTUNG' => 'Penurunan curah jantung',
            'PERFUSI_PERIFER_TIDAK_EFEKTIF' => 'Perfusi perifer tidak efektif',
            'TERMOREGULASI_TIDAK_EFEKTIF' => 'Termoregulasi tidak efektif',
            'RESIKO_PERFUSI_GASTROINTESTINAL_TIDAK_EFEKTIF' => 'Resiko perfusi gastrointestinal tidak efektif',
            'RESIKO_PERDARAHAN' => 'Resiko perdarahan',
            'DEFISIT_NUTRISI' => 'Defisit nutrisi',
            'DIARE' => 'Diare',
            'KETIDAKSTABILAN_KADAR_GLUKOSA_DARAH' => 'Ketidakstabilan kadar glukosa darah',
            'RESIKO_KETIDAKSEIMBANGAN_CAIRAN' => 'Resiko ketidakseimbangan cairan',
            'RESIKO_KETIDAKSEIMBANGAN_ELEKTROLIT' => 'Resiko ketidakseimbangan elektrolit',
            'RESIKO_SYOK' => 'Resiko syok',
            'DISFUNGSI_MOTILITAS_GASTROINTESTINAL' => 'Disfungsi motilitas gastrointestinal',
            'GANGGUAN_ELIMINASI_URINE' => 'Gangguan eliminasi urine',
            'KONSTIPASI' => 'Konstipasi',
            'RETENSI_URINE' => 'Retensi urine',
            'GANGGUAN_MOBILITAS_FISIK' => 'Gangguan mobilitas fisik',
            'GANGGUAN_POLA_TIDUR' => 'Gangguan pola tidur',
            'INTOLERANSI_AKTIVITAS' => 'Intoleransi aktivitas',
            'GANGGUAN_MENELAN' => 'Gangguan menelan',
            'GANGGUAN_RASA_NYAMAN' => 'Gangguan rasa nyaman',
            'NAUSEA' => 'Nausea',
            'NYERI_AKUT' => 'Nyeri akut',
            'NYERI_KRONIS' => 'Nyeri kronis',
            'ANSIETAS' => 'Ansietas',
            'GANGGUAN_PERSEPSI_SENSORI' => 'Gangguan persepsi sensori',
            'DEFISIT_PERAWATAN_DIRI' => 'Defisit perawatan diri',
            'DEFISIT_PENGETAHUAN' => 'Defisit pengetahuan',
            'GANGGUAN_INTERAKSI_SOSIAL' => 'Gangguan interaksi sosial',
            'GANGGUAN_KOMUNIKASI_VERBAL' => 'Gangguan komunikasi verbal',
            'GANGGUAN_INTEGRITAS_KULIT_JARINGAN' => 'Gangguan integritas kulit/jaringan',
            'HIPERTERMI' => 'Hipertermia',
            'HIPOTERMI' => 'Hipotermia',
            'PERLAMBATAN_PEMULIHAN_PASCA_BEDAH' => 'Perlambatan pemulihan pasca bedah',
            'RESIKO_ALERGI' => 'Resiko alergi',
            'RESIKO_CIDERA' => 'Resiko cidera',
            'RESIKO_INFEKSI' => 'Resiko infeksi',
            'HIPERVOLEMIA' => 'Hipervolemia',
            'HIPOVOLEMIA' => 'Hipovolemia',
            'BERAT_BADAN_LEBIH' => 'Berat badan lebih',
            'CEMAS' => 'Cemas',
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

            'PEMERIKSAAN_FISIK_RI' => $this->placeholder(
                implode("\n", $detailPemeriksaanFisikRI)
            ),

            'AKTIVITAS_LATIHAN_PERSONAL_HYGIENE' => $this->placeholder(
                implode("\n", $detailALPH)
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

            'SKRINING_DEKUBITUS' => $this->placeholder(
                implode("\n", $detailDekubitus)
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
