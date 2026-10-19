<?php

namespace App\Services\EMR\Print;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GDPrintService implements PrintDataInterface
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
     * DOKTER
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
            ->where('fin.FORM', 'pengkajian-radar')
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
        | ATS / TRIAGE AWAL
        |--------------------------------------------------------------------------
        */

        $triage = DB::table('medicalrecord.triage')
            ->where('KUNJUNGAN', $kunjungan)
            ->whereIn('STATUS', [1, 2])
            ->orderByDesc('ID')
            ->first();

        $atsKategori = '-';
        $atsPrioritas = '-';
        $atsKriteria = $this->value($triage->KRITERIA ?? null);
        $atsPlan = '-';
        $atsDetail = [];

        $atsKategoriMap = [
            'RESUSITASI' => ['prioritas' => 'P1', 'label' => 'Ruang Resusitasi / Zona Merah'],
            'EMERGENCY' => ['prioritas' => 'P2', 'label' => 'Ruang Kritis / Zona Merah'],
            'URGENT' => ['prioritas' => 'P3', 'label' => 'Zona Kuning'],
            'LESS_URGENT' => ['prioritas' => 'P4', 'label' => 'Zona Hijau'],
            'NON_URGENT' => ['prioritas' => 'P5', 'label' => 'Zona Hijau / Non-Urgent'],
            'DOA' => ['prioritas' => 'DOA', 'label' => 'Zona Hitam / DOA'],
        ];

        if ($triage) {
            $atsKategoriTerpilih = false;

            foreach ($atsKategoriMap as $field => $info) {
                $raw = $triage->{$field} ?? null;
                $decoded = is_array($raw) ? $raw : json_decode((string) $raw, true);

                if (!is_array($decoded)) {
                    continue;
                }

                if (!empty($decoded['CHECKED'])) {
                    // Ambil kategori pertama yang tercentang agar data lama yang
                    // memiliki lebih dari satu CHECKED tidak menimpa prioritas utama.
                    if ($atsKategoriTerpilih) {
                        continue;
                    }

                    $atsKategoriTerpilih = true;
                    $atsPrioritas = $info['prioritas'];
                    $atsKategori = $info['label'];

                    $kesadaran = $this->value($decoded['KESADARAN'] ?? null);
                    if ($kesadaran !== '') {
                        $atsDetail[] = 'Kesadaran: ' . $kesadaran;
                    }

                    foreach (['JALAN_NAPAS' => 'Jalan napas', 'PERNAPASAN' => 'Pernapasan', 'SIRKULASI' => 'Sirkulasi'] as $bagian => $labelBagian) {
                        $detailBagian = [];
                        $nilaiBagian = $decoded[$bagian] ?? [];

                        if (is_array($nilaiBagian)) {
                            foreach ($nilaiBagian as $key => $value) {
                                if ((string) $key === 'CHECKED' || is_array($value) || !in_array($value, [1, true, '1'], true)) {
                                    continue;
                                }

                                $labelKriteria = ucwords(strtolower(str_replace('_', ' ', (string) $key)));
                                $detailBagian[] = $labelKriteria;
                            }
                        }

                        if ($detailBagian) {
                            $atsDetail[] = $labelBagian . ': ' . implode(', ', $detailBagian);
                        }
                    }
                }
            }

            $atsPlan = match ((string) ($triage->PLAN ?? '')) {
                '1' => 'Resusitasi / Emergency',
                '2' => 'Urgent',
                '3' => 'Non-Urgent',
                '4' => 'DOA',
                default => '',
            };
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
            ->first(); // Ambil seluruh kolom Primary Survey yang disimpan controller.

        $tingkat_kesadaran = DB::table('master.referensi')
            ->where('JENIS',179)
            ->where('STATUS',1)
            ->where('ID',$tandaVital->TINGKAT_KESADARAN)
            ->orderBy('TABEL_ID','ASC')
            ->value('DESKRIPSI');

        // Status reproduksi yang menjadi bagian dari Primary Survey.
        $statusReproduksi = DB::table('medicalrecord.sirmed_status_reproduksi')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first();

        $labelPilihan = static function ($nilai, array $opsi): string {
            $nilai = trim((string) ($nilai ?? ''));
            return $nilai !== '' ? ($opsi[$nilai] ?? $nilai) : '';
        };

        $tekananDarah = ($tandaVital && ($tandaVital->SISTOLIK !== null || $tandaVital->DISTOLIK !== null))
            ? $this->value((int) $tandaVital->SISTOLIK ?? '-') . '/' . $this->value((int) $tandaVital->DISTOLIK ?? '-') . ' mmHg'
            : '-';

        $nadi = $tandaVital && $this->value($tandaVital->FREKUENSI_NADI ?? null) !== ''
            ? $this->value((int) $tandaVital->FREKUENSI_NADI) . ' x/menit'
                . (($tandaVital->FREKUENSI_NADI_CB ?? null) !== null
                    ? ' (' . $labelPilihan($tandaVital->FREKUENSI_NADI_CB, ['1' => 'Reguler', '2' => 'Ireguler']) . ')'
                    : '')
            : '-';

        $frekuensiNapas = $tandaVital && $this->value($tandaVital->FREKUENSI_NAFAS ?? null) !== ''
            ? $this->value((int) $tandaVital->FREKUENSI_NAFAS) . ' x/menit'
                . (($tandaVital->FREKUENSI_NAFAS_CB ?? null) !== null
                    ? ' (' . $labelPilihan($tandaVital->FREKUENSI_NAFAS_CB, ['1' => 'Simetris', '2' => 'Asimetris']) . ')'
                    : '')
            : '-';

        $gcs = '-';
        if ($tandaVital) {
            $gcsTotal = $this->value($tandaVital->GCS ?? null);
            $eye = $this->value($tandaVital->EYE ?? null);
            $verbal = $this->value($tandaVital->VERBAL ?? null);
            $motorik = $this->value($tandaVital->MOTORIK ?? null);
            if ($gcsTotal !== '' || $eye !== '' || $verbal !== '' || $motorik !== '') {
                $gcs = ($gcsTotal !== '' ? (int) $gcsTotal : '-')
                    . ' (E' . ($eye !== '' ? $eye : '-')
                    . ' V' . ($verbal !== '' ? $verbal : '-')
                    . ' M' . ($motorik !== '' ? $motorik : '-') . ')';
            }
        }

        $diameterPupil = [];
        if ($this->value($tandaVital->DIAMETER_PUPIL_UP ?? null) !== '' && $this->value($tandaVital->DIAMETER_PUPIL_DOWN ?? null) !== '') {
            $diameterPupil[] = $this->value($tandaVital->DIAMETER_PUPIL_UP) . '/' . $this->value($tandaVital->DIAMETER_PUPIL_DOWN) . ' mm';
        }

        $refleksCahaya = [];
        if ($this->value($tandaVital->RC_UP ?? null) !== '' && $this->value($tandaVital->RC_DOWN ?? null) !== '') {
            $refleksCahaya[] = $this->value($tandaVital->RC_UP) . '/' . $this->value($tandaVital->RC_DOWN);
        }

        $primarySurvey = [
            'Keadaan umum: ' . $this->placeholder($tandaVital->KEADAAN_UMUM ?? null),
            'A - Airway/jalan napas: ' . $this->placeholder($labelPilihan($tandaVital->JALAN_NAFAS ?? null, ['1' => 'Paten', '2' => 'Obstruksi parsial', '3' => 'Obstruksi total'])),
            'Alat bantu napas: ' . $this->placeholder($tandaVital->ALAT_BANTU_NAFAS ?? null),
            'B - Breathing: ' . $this->placeholder($frekuensiNapas),
            'C - Circulation: TD ' . $tekananDarah . '; Nadi ' . $nadi,
            'Suhu: ' . ($this->value($tandaVital->SUHU ?? null) !== '' ? $this->value($tandaVital->SUHU) . ' °C' : '-'),
            'SpO2: ' . ($this->value($tandaVital->SATURASI_O2 ?? null) !== '' ? $this->value($tandaVital->SATURASI_O2) . ' %' : '-'),
            'D - Disability/kesadaran: ' . $this->placeholder($tandaVital->TINGKAT_KESADARAN ?? null),
            'GCS: ' . $gcs,
            'Pupil: ' . $this->placeholder($labelPilihan($tandaVital->PUPIL ?? null, ['1' => 'Isokor', '2' => 'Anisokor'])),
            'Diameter pupil: ' . $this->placeholder(implode('; ', $diameterPupil)),
            'Refleks cahaya: ' . $this->placeholder(implode('; ', $refleksCahaya)),
            'VAS/nyeri: ' . $this->placeholder($tandaVital->VAS ?? null),
            'E - Exposure/kulit: ' . $this->placeholder($tandaVital->KULIT ?? null),
        ];
        $primarySurveyText = implode("\n", $primarySurvey);

        $statusReproduksiText = [];
        if ($statusReproduksi) {
            $kasusObgyn = $this->value($statusReproduksi->KASUS_OBSTETRI_GINEKOLOGI ?? null);
            if ($kasusObgyn !== '') $statusReproduksiText[] = 'Kasus obstetri/ginekologi: ' . $kasusObgyn;
            if ($this->value($statusReproduksi->HPHT ?? null) !== '') $statusReproduksiText[] = 'HPHT: ' . $this->value($statusReproduksi->HPHT);
            if ($this->value($statusReproduksi->SIKLUS ?? null) !== '') $statusReproduksiText[] = 'Siklus: ' . $this->value($statusReproduksi->SIKLUS);
            if ($this->value($statusReproduksi->KB ?? null) !== '') $statusReproduksiText[] = 'KB: ' . $this->value($statusReproduksi->KB);
            if ($this->value($statusReproduksi->HAMIL_GRAVIDA ?? null) !== '') $statusReproduksiText[] = 'Gravida: ' . $this->value($statusReproduksi->HAMIL_GRAVIDA);
            if ($this->value($statusReproduksi->HAMIL_PARITAS ?? null) !== '') $statusReproduksiText[] = 'Paritas: ' . $this->value($statusReproduksi->HAMIL_PARITAS);
            if ($this->value($statusReproduksi->HAMIL_ABORTUS ?? null) !== '') $statusReproduksiText[] = 'Abortus: ' . $this->value($statusReproduksi->HAMIL_ABORTUS);
        }
        if ($statusReproduksiText) {
            $primarySurveyText .= "\nStatus reproduksi: " . implode('; ', $statusReproduksiText);
        }

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

        $pemfis = DB::table(
            'medicalrecord.pemeriksaan_fisik'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first();
        $pemeriksaanFisikRajal = strip_tags(
            str_replace(
                ['<br>', '<br/>', '<br />'],
                "\n",
                $pemfis->DESKRIPSI ?? ''
            )
        );

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
        | TINDAK LANJUT DAN PERENCANAAN RAWAT INAP
        |--------------------------------------------------------------------------
        */
        $dataTindakLanjut = DB::table(
            'medicalrecord.tindak_lanjut_pengkajian'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        $nilaiTindakLanjut = (string) ($dataTindakLanjut->TINDAK_LANJUT ?? '');

        $tindakLanjut = match ($nilaiTindakLanjut) {
            '1' => 'Lanjut Rawat Inap',
            '2' => 'Pulang',
            default => '',
        };

        $rujukan = match ((string) ($dataTindakLanjut->RUJUKAN ?? '')) {
            '1' => 'Ahli Gizi',
            '2' => 'Rehabilitasi Medik',
            '3' => 'Klinik Spesialis',
            '4' => 'Lainnya',
            default => '',
        };

        if ($rujukan === 'Lainnya') {
            $rujukanLainnya = $this->value(
                $dataTindakLanjut->RUJUKAN_LAINNYA ?? null
            );

            if ($rujukanLainnya !== '') {
                $rujukan .= ': ' . $rujukanLainnya;
            }
        }

        if ($rujukan !== '') {
            $tindakLanjut .= ($tindakLanjut !== '' ? "\n" : '')
                . 'Rujukan: ' . $rujukan;
        }

        // Detail Perencanaan Rawat Inap dicetak hanya saat tindak lanjut MRS.
        if ($nilaiTindakLanjut === '1') {
            $perencanaanRawatInap = DB::table(
                'medicalrecord.perencanaan_rawat_inap'
            )
                ->where('KUNJUNGAN', $kunjungan)
                ->first();

            if ($perencanaanRawatInap) {
                $jenisRuang = DB::table('master.referensi')
                    ->where('JENIS', 242)
                    ->where('STATUS', 1)
                    ->where('ID', (int) $perencanaanRawatInap->JENIS_RUANG_PERAWATAN)
                    ->value('DESKRIPSI');

                $jenisPerawatan = DB::table('master.referensi')
                    ->where('JENIS', 243)
                    ->where('STATUS', 1)
                    ->where('ID', (int) $perencanaanRawatInap->JENIS_PERAWATAN)
                    ->value('DESKRIPSI');

                $dokter = DB::table('master.dokter AS dok')
                    ->where('dok.STATUS',1)
                    ->where('dok.ID', (int) $perencanaanRawatInap->DOKTER)
                    ->value(DB::raw('master.getNamaLengkapPegawai(dok.NIP) AS NAMADOKTER'));

                $detailPerencanaan = [
                    'Jenis ruang perawatan: '
                        . $this->placeholder($jenisRuang),
                    'Jenis perawatan: '
                        . $this->placeholder($jenisPerawatan),
                    'Tanggal: '
                        . $this->value($perencanaanRawatInap->TANGGAL ?? null),
                    'Indikasi: '
                        . $this->value($perencanaanRawatInap->INDIKASI ?? null),
                    'Keterangan: '
                        . $this->value($perencanaanRawatInap->DESKRIPSI ?? null),
                    'DPJP: '
                        . $this->placeholder($dokter),
                ];

                $tindakLanjut .= "\n" . implode("\n", $detailPerencanaan);
            }
        }

         /*
        |--------------------------------------------------------------------------
        | TERAPI
        |--------------------------------------------------------------------------
        */

        $rencanaTerapi = DB::table('medicalrecord.rencana_terapi')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first();

        $terapi = $rencanaTerapi->DESKRIPSI ?? null;

        /*
        |--------------------------------------------------------------------------
        | HASIL LAPOR DPJP
        |--------------------------------------------------------------------------
        */
        $hasilLaporDpjp = DB::table('medicalrecord.hasil_lapor_dpjp')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->value('DESKRIPSI');

        /*
        |--------------------------------------------------------------------------
        | CARA KELUAR DAN KEADAAN KELUAR
        |--------------------------------------------------------------------------
        */
        $dataCaraPulang = null;

        if ($nopen) {
            $dataCaraPulang = DB::table('layanan.pasien_pulang')
                ->where('KUNJUNGAN', $kunjungan)
                ->where('NOPEN', $nopen)
                ->where('STATUS', 1)
                ->orderByDesc('TANGGAL')
                ->first();
        }

        $caraKeluarId = $dataCaraPulang->CARA ?? null;
        $keadaanKeluarId = $dataCaraPulang->KEADAAN ?? null;

        // Cari deskripsi berdasarkan ID master. Jika tidak ditemukan,
        // gunakan ID tersimpan agar informasi tidak hilang saat dicetak.
        $caraKeluar = $caraKeluarId !== null && $caraKeluarId !== ''
            ? (DB::table('master.referensi')->where('ID', $caraKeluarId)->where('STATUS', 1)->value('DESKRIPSI') ?? (string) $caraKeluarId)
            : null;

        $keadaanKeluar = $keadaanKeluarId !== null && $keadaanKeluarId !== ''
            ? (DB::table('master.referensi')->where('ID', $keadaanKeluarId)->where('STATUS', 1)->value('DESKRIPSI') ?? (string) $keadaanKeluarId)
            : null;

        $caraPulangText = implode("\n", array_filter([
            'Cara keluar: ' . $this->placeholder($caraKeluar),
            'Keadaan keluar: ' . $this->placeholder($keadaanKeluar),
        ], fn ($baris) => !str_ends_with($baris, ': -')));

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
            | ATS / TRIAGE AWAL
            | Letakkan bagian ini sebelum PRIMARY_SURVEY pada template DOCX.
            */
            'ATS_KATEGORI' => $this->placeholder($atsKategori),
            'ATS_PRIORITAS' => $this->placeholder($atsPrioritas),
            'ATS_KRITERIA' => $this->placeholder($atsKriteria),
            'ATS_PLAN' => $this->placeholder($atsPlan),
            'ATS_DETAIL' => $this->placeholder(implode("\n", $atsDetail)),

            /*
            | PRIMARY SURVEY
            | Kunci-kunci ini menjadi sumber data baru untuk placeholder DOCX.
            */
            'PRIMARY_SURVEY' => $this->placeholder($primarySurveyText),
            'PS_KEADAAN_UMUM' => $this->placeholder($tandaVital->KEADAAN_UMUM ?? null),
            'PS_AIRWAY' => $this->placeholder($labelPilihan($tandaVital->JALAN_NAFAS ?? null, ['1' => 'Paten', '2' => 'Obstruksi parsial', '3' => 'Obstruksi total'])),
            'PS_ALAT_BANTU_NAPAS' => $this->placeholder($tandaVital->ALAT_BANTU_NAFAS == '1' ? 'Ya' : 'Tidak'),
            'PS_BREATHING' => $this->placeholder($frekuensiNapas),
            'PS_TEKANAN_DARAH' => $tekananDarah,
            'PS_NADI' => $nadi,
            'PS_SUHU' => $this->value($tandaVital->SUHU ?? null) !== '' ? $this->value($tandaVital->SUHU) . ' °C' : '-',
            'PS_SATURASI_O2' => $this->value($tandaVital->SATURASI_O2 ?? null) !== '' ? $this->value((int) $tandaVital->SATURASI_O2) . ' %' : '-',
            'PS_KESADARAN' => $this->placeholder($tingkat_kesadaran ?? null),
            'PS_GCS' => $gcs,
            'PS_PUPIL' => $this->placeholder($labelPilihan($tandaVital->PUPIL ?? null, ['1' => 'Isokor', '2' => 'Anisokor'])),
            'PS_DIAMETER_PUPIL' => $this->placeholder(implode('; ', $diameterPupil)),
            'PS_REFLEKS_CAHAYA' => $this->placeholder(implode('; ', $refleksCahaya)),
            'PS_VAS' => $this->placeholder($tandaVital->VAS ?? null),
            'PS_KULIT' => $this->placeholder($labelPilihan($tandaVital->KULIT ?? null, ['1' => 'Normal', '2' => 'Jaundice', '3' => 'Akral Dingin', '4' => 'Sianotik', '5' => 'Berkeringat'])),
            'PS_STATUS_REPRODUKSI' => $this->placeholder(implode("\n", $statusReproduksiText)),

            /*
            | SECONDARY SURVEY
            */
            'SECONDARY_SURVEY' => $this->placeholder(implode("\n", [
                'Anamnesis diperoleh: ' . $this->placeholder(implode(', ', $anamnesis)),
                'Keluhan utama: ' . $this->placeholder($keluhanUtama),
                'Riwayat penyakit sekarang: ' . $this->placeholder($riwayatPenyakitSekarang),
                'Riwayat penyakit dahulu: ' . $this->placeholder($riwayatPenyakitDahulu),
                'Riwayat penyakit keluarga: ' . $this->placeholder(implode(', ', $daftarRiwayatKeluarga)),
                'Pemeriksaan fisik: ' . $this->placeholder($pemeriksaanFisikRajal),
            ])),
            'SS_ANAMNESIS_DIPEROLEH' => $this->placeholder(implode(', ', $anamnesis)),
            'SS_KELUHAN_UTAMA' => $this->placeholder($keluhanUtama),
            'SS_RIWAYAT_PENYAKIT_SEKARANG' => $this->placeholder($riwayatPenyakitSekarang),
            'SS_RIWAYAT_PENYAKIT_DAHULU' => $this->placeholder($riwayatPenyakitDahulu),
            'SS_RIWAYAT_PENYAKIT_KELUARGA' => $this->placeholder(implode(', ', $daftarRiwayatKeluarga)),
            'SS_PEMERIKSAAN_FISIK' => $this->placeholder($pemeriksaanFisikRajal),

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
            | TERAPI DAN TINDAK LANJUT
            */

            'PERENCANAAN_TERAPI' => $this->placeholder($terapi),
            'HASIL_LAPOR_DPJP' => $this->placeholder($hasilLaporDpjp),
            'CARA_KELUAR' => $this->placeholder($caraKeluar),
            'KEADAAN_KELUAR' => $this->placeholder($keadaanKeluar),
            'CARA_PULANG' => $this->placeholder($caraPulangText),

            'TINDAK_LANJUT' => $this->placeholder($tindakLanjut),

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
     * PERAWAT
     * ========================================================================
     */
    private function getDataPerawat(string $kunjungan): array
    {
        $value = static function ($raw): string {
            if ($raw === null) {
                return '';
            }

            if (is_bool($raw)) {
                return $raw ? '1' : '0';
            }

            return trim((string) $raw);
        };

        $placeholder = static function ($raw) use ($value): string {
            $raw = $value($raw);

            return $raw !== '' ? $raw : '-';
        };

        $mapValue = static function ($raw, array $map) use ($value): string {
            $key = $value($raw);

            if ($key === '') {
                return '-';
            }

            if (array_key_exists($key, $map)) {
                return $map[$key];
            }

            // Hindari mencetak angka 0 sebagai pilihan yang sudah dipilih
            // apabila pada pilihan tersebut 0 merupakan nilai default.
            if ($key === '0' && $map !== []) {
                return 'Belum diisi';
            }

            return $key;
        };

        $yesNo = static function ($raw): string {
            if ($raw === null || $raw === '') {
                return '-';
            }

            return (string) $raw === '1'
                ? 'Ya'
                : ((string) $raw === '0' ? 'Tidak' : (string) $raw);
        };

        $formatLines = static function (array $lines): string {
            $lines = array_values(
                array_filter(
                    $lines,
                    static fn ($line) =>
                        $line !== null && trim((string) $line) !== ''
                )
            );

            return $lines ? implode("\n", $lines) : '-';
        };

        $push = static function (
            array &$lines,
            string $label,
            $raw,
            array $map = []
        ) use ($value): void {
            $key = $value($raw);

            if ($key === '') {
                return;
            }

            $mapped = array_key_exists($key, $map)
                ? $map[$key]
                : (($key === '0' && $map !== []) ? 'Belum diisi' : $key);

            $lines[] = $label . ': ' . $mapped;
        };

        $decodeJson = static function ($raw): array {
            if (is_array($raw)) {
                return $raw;
            }

            if (is_object($raw)) {
                return (array) $raw;
            }

            if (!is_string($raw) || trim($raw) === '') {
                return [];
            }

            $decoded = json_decode($raw, true);

            return is_array($decoded) ? $decoded : [];
        };

        $getLatest = static function (
            string $table,
            array $statuses = [1, 2]
        ) use ($kunjungan) {
            $query = DB::table($table)
                ->where('KUNJUNGAN', $kunjungan);

            if ($statuses !== []) {
                $query->whereIn('STATUS', $statuses);
            }

            return $query->orderByDesc('ID')->first();
        };

        $checkedItems = static function (
            $row,
            array $columns
        ) use ($value): string {
            if (!$row) {
                return '-';
            }

            $items = [];

            foreach ($columns as $column => $label) {
                if ($value($row->{$column} ?? null) === '1') {
                    $items[] = $label;
                }
            }

            return $items ? implode(', ', $items) : '-';
        };

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
            ->join(
                'master.pasien as p',
                'p.NORM',
                '=',
                'pp.NORM'
            )
            ->leftJoin('master.referensi as rjk', function ($join) {
                $join->on('rjk.ID', '=', 'p.JENIS_KELAMIN')
                    ->where('rjk.JENIS', 2);
            })
            ->leftJoin('master.referensi as ag', function ($join) {
                $join->on('ag.ID', '=', 'p.AGAMA')
                    ->where('ag.JENIS', 1);
            })
            ->leftJoin('master.referensi as pek', function ($join) {
                $join->on('pek.ID', '=', 'p.PEKERJAAN')
                    ->where('pek.JENIS', 4);
            })
            ->leftJoin('master.ruangan as r', function ($join) {
                $join->on('r.ID', '=', 'pk.RUANGAN')
                    ->where('r.JENIS', 5);
            })
            ->leftJoin(
                'master.dokter as dok',
                'dok.ID',
                '=',
                'pk.DPJP'
            )
            ->select([
                'pk.NOMOR as KUNJUNGAN',
                'pk.NOPEN',
                'pk.MASUK as TANGGAL_MASUK',
                'pk.KELUAR as TANGGAL_KELUAR',
                'pk.RUANGAN',
                'pk.DPJP',

                DB::raw("LPAD(pp.NORM, 8, '0') as NORM_FORMATTED"),
                DB::raw('master.getNamaLengkap(p.NORM) as NAMA_PASIEN'),
                'p.TANGGAL_LAHIR',
                'p.ALAMAT',

                'rjk.DESKRIPSI as JENIS_KELAMIN',
                'ag.DESKRIPSI as AGAMA',
                'pek.DESKRIPSI as PEKERJAAN',
                'r.DESKRIPSI as NAMA_RUANGAN',

                DB::raw(
                    'master.getNamaLengkapPegawai(dok.NIP) as NAMA_DPJP'
                ),
            ])
            ->where('pk.NOMOR', $kunjungan)
            ->first();

        $norm = $value($pasien->NORM_FORMATTED ?? null);
        $namaPasien = $value($pasien->NAMA_PASIEN ?? null);
        $tanggalLahirRaw = $value($pasien->TANGGAL_LAHIR ?? null);

        $umur = '-';

        if ($tanggalLahirRaw !== '') {
            try {
                $umur = \Carbon\Carbon::parse($tanggalLahirRaw)->age
                    . ' tahun';
            } catch (\Throwable $e) {
                $umur = '-';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | DATA FORM PERAWAT
        |--------------------------------------------------------------------------
        */

        $triage = $getLatest('medicalrecord.triage');

        $anamnesisDiperoleh = $getLatest(
            'medicalrecord.anamnesis_diperoleh'
        );

        $tandaVital = $getLatest(
            'medicalrecord.tanda_vital'
        );

        $statusReproduksi = $getLatest(
            'medicalrecord.sirmed_status_reproduksi'
        );

        $psikososial = $getLatest(
            'medicalrecord.kondisi_sosial'
        );

        $fungsional = $getLatest(
            'medicalrecord.status_fungsional'
        );

        $nyeri = $getLatest(
            'medicalrecord.penilaian_nyeri'
        );

        $humpty = $getLatest(
            'medicalrecord.penilaian_skala_humpty_dumpty'
        );

        $morse = $getLatest(
            'medicalrecord.penilaian_skala_morse'
        );

        $epfra = $getLatest(
            'medicalrecord.penilaian_epfra'
        );

        $getUpAndGo = $getLatest(
            'medicalrecord.penilaian_getup_and_go'
        );

        $must = $getLatest(
            'medicalrecord.permasalahan_gizi'
        );

        $strongKid = $getLatest(
            'medicalrecord.penilaian_strong_kid'
        );

        $implementasi = $getLatest(
            'medicalrecord.sirmed_implementasi_keperawatan'
        );

        $kolaborasi = $getLatest(
            'medicalrecord.sirmed_tindakan_kolaborasi'
        );

        $masalah = $getLatest(
            'medicalrecord.masalah_keperawatan'
        );

        $discharge1 = $getLatest(
            'medicalrecord.discharge_planning_faktor_risiko'
        );

        $discharge2 = $getLatest(
            'medicalrecord.discharge_planning_skrining'
        );

        /*
        |--------------------------------------------------------------------------
        | FINALISASI DAN TANDA TANGAN PERAWAT
        |--------------------------------------------------------------------------
        */

        $finalisasi = DB::table(
            'simrspku_pengkajian.finalisasi as fin'
        )
            ->leftJoin(
                'aplikasi.pengguna as usr',
                'usr.ID',
                '=',
                'fin.USER_CREATED'
            )
            ->select([
                'fin.KUNJUNGAN',
                'fin.FORM',
                'fin.SUB',
                'fin.STATUS',

                'usr.NIP as NIP_USER_FINALISASI',

                DB::raw(
                    'master.getNamaLengkapPegawai(usr.NIP) as NAMA_USER_FINALISASI'
                ),
            ])
            ->where('fin.KUNJUNGAN', $kunjungan)
            ->where('fin.FORM', 'pengkajian-radar')
            ->where('fin.SUB', 'PERAWAT')
            ->where('fin.STATUS', 2)
            ->first();

        $ttdPerawat = null;

        $nipFinalisasi = $value(
            $finalisasi->NIP_USER_FINALISASI ?? null
        );

        if ($nipFinalisasi !== '') {
            $ttdPerawat = DB::table(
                'simrspku_klaim.tanda_tangan_pegawai'
            )
                ->where('nip', $nipFinalisasi)
                ->where('status', 1)
                ->whereNull('deleted_at')
                ->inRandomOrder()
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | TRIAGE: CARA KEDATANGAN
        |--------------------------------------------------------------------------
        */

        $kedatangan = $decodeJson(
            $triage->KEDATANGAN ?? null
        );

        $jenisKedatangan = $value(
            $kedatangan['JENIS'] ?? null
        );

        $caraKedatanganMap = [
            '1' => 'Datang sendiri, diantar oleh',
            '2' => 'Rujukan dari',
            '3' => 'Dikirim oleh Polisi dari',
        ];

        $caraKedatangan = [];

        $push(
            $caraKedatangan,
            'Cara kedatangan',
            $jenisKedatangan,
            $caraKedatanganMap
        );

        if ($jenisKedatangan === '1') {
            $push(
                $caraKedatangan,
                'Nama pengantar',
                $kedatangan['PENGANTAR'] ?? null
            );
        } elseif ($jenisKedatangan === '2') {
            $push(
                $caraKedatangan,
                'Asal rujukan',
                $kedatangan['ASAL_RUJUKAN'] ?? null
            );
        } elseif ($jenisKedatangan === '3') {
            $push(
                $caraKedatangan,
                'Kepolisian',
                $kedatangan['KEPOLISIAN'] ?? null
            );

            if (array_key_exists('VISUM', $kedatangan)) {
                $push(
                    $caraKedatangan,
                    'Permintaan visum et repertum',
                    $yesNo($kedatangan['VISUM'])
                );
            }
        }

        $tanggalDatang = $value(
            $kedatangan['TANGGAL'] ?? null
        );

        if ($tanggalDatang !== '') {
            $caraKedatangan[] =
                'Tanggal kedatangan: '
                . $this->formatTanggalWaktuMedis($tanggalDatang);
        }

        $push(
            $caraKedatangan,
            'Alat transportasi',
            $kedatangan['ALAT_TRANSPORTASI'] ?? null
        );

        /*
        |--------------------------------------------------------------------------
        | TRIAGE: JENIS KASUS
        |--------------------------------------------------------------------------
        */

        $kasus = $decodeJson(
            $triage->KASUS ?? null
        );

        $jenisKasus = $value(
            $kasus['JENIS'] ?? null
        );

        $jenisKasusMap = [
            '1' => 'Trauma',
            '0' => 'Non trauma',
        ];

        $detailKasus = [];

        $push(
            $detailKasus,
            'Jenis kasus',
            $jenisKasus,
            $jenisKasusMap
        );

        if ($jenisKasus === '1') {
            $detailTrauma = [];

            foreach ([
                'LAKA_LANTAS' => 'Kecelakaan lalu lintas',
                'KECELAKAAN_KERJA' => 'Kecelakaan kerja',
                'UPPA' => 'Kasus perempuan dan anak (UPPA)',
            ] as $column => $label) {
                if ($value($kasus[$column] ?? null) === '1') {
                    $detailTrauma[] = $label;
                }
            }

            if ($detailTrauma) {
                $detailKasus[] =
                    'Rincian trauma: '
                    . implode(', ', $detailTrauma);
            }
        } elseif ($jenisKasus === '0') {
            $push(
                $detailKasus,
                'Daerah endemis / keterangan',
                $kasus['DIMANA'] ?? null
            );
        }

        /*
        |--------------------------------------------------------------------------
        | RISIKO PENULARAN INFEKSI
        |--------------------------------------------------------------------------
        */

        $risikoInfeksiMap = [
            '1' => 'Batuk > 2 minggu dengan demam dan sesak napas',
            '2' => 'Rujukan dengan suspek/konfirmasi airborne disease',
            '3' => 'Tidak berisiko penularan airborne disease',
            '4' => 'B-20',
        ];

        $risikoInfeksiRaw = $value(
            $triage->RISIKO_PENULARAN_INFEKSI ?? null
        );

        $risikoInfeksi = (
            $risikoInfeksiRaw === ''
            || $risikoInfeksiRaw === '0'
        )
            ? '-'
            : (
                $risikoInfeksiMap[$risikoInfeksiRaw]
                ?? $risikoInfeksiRaw
            );

        /*
        |--------------------------------------------------------------------------
        | ANAMNESIS PADA TRIAGE
        |--------------------------------------------------------------------------
        */

        $anamneseTriage = $decodeJson(
            $triage->ANAMNESE ?? null
        );

        $detailAnamnesis = [];

        $push(
            $detailAnamnesis,
            'Keluhan utama',
            $anamneseTriage['KELUHAN_UTAMA'] ?? null
        );

        $push(
            $detailAnamnesis,
            'Anamnesis terpimpin',
            $anamneseTriage['TERPIMPIN'] ?? null
        );

        /*
        |--------------------------------------------------------------------------
        | ATS / KATEGORI TRIAGE
        |--------------------------------------------------------------------------
        */

        $atsMap = [
            'RESUSITASI' => [
                'prioritas' => 'P1',
                'label' => 'Ruang Resusitasi / Zona Merah',
            ],
            'EMERGENCY' => [
                'prioritas' => 'P2',
                'label' => 'Ruang Kritis / Zona Merah',
            ],
            'URGENT' => [
                'prioritas' => 'P3',
                'label' => 'Zona Kuning',
            ],
            'LESS_URGENT' => [
                'prioritas' => 'P4',
                'label' => 'Zona Hijau',
            ],
            'NON_URGENT' => [
                'prioritas' => 'P5',
                'label' => 'Zona Hijau / Non-Urgent',
            ],
            'DOA' => [
                'prioritas' => 'DOA',
                'label' => 'Zona Hitam / DOA',
            ],
        ];

        $atsKategori = '-';
        $atsPrioritas = '-';
        $atsDetail = [];

        foreach ($atsMap as $column => $info) {
            $kategori = $decodeJson(
                $triage->{$column} ?? null
            );

            if (empty($kategori['CHECKED'])) {
                continue;
            }

            $atsKategori = $info['label'];
            $atsPrioritas = $info['prioritas'];

            $push(
                $atsDetail,
                'Kesadaran',
                $kategori['KESADARAN'] ?? null
            );

            foreach ([
                'JALAN_NAPAS' => 'Jalan napas',
                'PERNAPASAN' => 'Pernapasan',
                'SIRKULASI' => 'Sirkulasi',
            ] as $key => $label) {
                $values = $kategori[$key] ?? [];
                $selected = [];

                if (is_array($values)) {
                    foreach ($values as $subKey => $subValue) {
                        if (
                            (string) $subKey === 'CHECKED'
                            || is_array($subValue)
                            || !in_array(
                                $subValue,
                                [1, true, '1'],
                                true
                            )
                        ) {
                            continue;
                        }

                        $selected[] = ucwords(
                            strtolower(
                                str_replace(
                                    '_',
                                    ' ',
                                    (string) $subKey
                                )
                            )
                        );
                    }
                }

                if ($selected) {
                    $atsDetail[] =
                        $label . ': ' . implode(', ', $selected);
                }
            }

            break;
        }

        $planMap = [
            '1' => 'Resusitasi / Emergency',
            '2' => 'Urgent',
            '3' => 'Non-Urgent',
            '4' => 'DOA',
        ];

        $atsPlan = $mapValue(
            $triage->PLAN ?? null,
            $planMap
        );

        /*
        |--------------------------------------------------------------------------
        | PRIMARY SURVEY / TANDA VITAL
        |--------------------------------------------------------------------------
        */

        $tingkatKesadaran = $value(
            $tandaVital->TINGKAT_KESADARAN ?? null
        );

        if ($tingkatKesadaran === '0') {
            $tingkatKesadaran = '';
        }

        if ($tingkatKesadaran !== '') {
            $deskripsiKesadaran = DB::table('master.referensi')
                ->where('JENIS', 179)
                ->where('STATUS', 1)
                ->where('ID', $tingkatKesadaran)
                ->value('DESKRIPSI');

            if ($deskripsiKesadaran) {
                $tingkatKesadaran = (string) $deskripsiKesadaran;
            }
        }

        $nadiRegulerMap = [
            '1' => 'Reguler',
            '2' => 'Ireguler',
        ];

        $napasMap = [
            '1' => 'Simetris',
            '2' => 'Asimetris',
        ];

        $airwayMap = [
            '1' => 'Paten',
            '2' => 'Obstruksi parsial',
            '3' => 'Obstruksi total',
        ];

        $pupilMap = [
            '1' => 'Isokor',
            '2' => 'Anisokor',
        ];

        $kulitMap = [
            '1' => 'Normal',
            '2' => 'Jaundice',
            '3' => 'Akral dingin',
            '4' => 'Sianotik',
            '5' => 'Berkeringat',
        ];

        $primarySurvey = [];

        $push(
            $primarySurvey,
            'Keadaan umum',
            $tandaVital->KEADAAN_UMUM ?? null
        );

        $push(
            $primarySurvey,
            'Airway / jalan napas',
            $tandaVital->JALAN_NAFAS ?? null,
            $airwayMap
        );

        $push(
            $primarySurvey,
            'Alat bantu napas',
            $tandaVital->ALAT_BANTU_NAFAS ?? null,
            ['0' => 'Tidak', '1' => 'Ya']
        );

        $frekuensiNapas = $value(
            $tandaVital->FREKUENSI_NAFAS ?? null
        );

        if ($frekuensiNapas !== '') {
            $frekuensiNapas .= ' x/menit';

            $napas = $value(
                $tandaVital->FREKUENSI_NAFAS_CB ?? null
            );

            if ($napas !== '') {
                $frekuensiNapas .=
                    ' (' . ($napasMap[$napas] ?? $napas) . ')';
            }

            $primarySurvey[] =
                'Frekuensi napas: ' . $frekuensiNapas;
        }

        $sistolik = $value(
            $tandaVital->SISTOLIK ?? null
        );

        $distolik = $value(
            $tandaVital->DISTOLIK ?? null
        );

        if ($sistolik !== '' || $distolik !== '') {
            $primarySurvey[] =
                'Tekanan darah: '
                . ($sistolik !== '' ? $sistolik : '-')
                . '/'
                . ($distolik !== '' ? $distolik : '-')
                . ' mmHg';
        }

        $frekuensiNadi = $value(
            $tandaVital->FREKUENSI_NADI ?? null
        );

        if ($frekuensiNadi !== '') {
            $frekuensiNadi .= ' x/menit';

            $nadi = $value(
                $tandaVital->FREKUENSI_NADI_CB ?? null
            );

            if ($nadi !== '') {
                $frekuensiNadi .=
                    ' (' . ($nadiRegulerMap[$nadi] ?? $nadi) . ')';
            }

            $primarySurvey[] =
                'Nadi: ' . $frekuensiNadi;
        }

        $suhu = $value(
            $tandaVital->SUHU ?? null
        );

        if ($suhu !== '') {
            $primarySurvey[] = 'Suhu: ' . $suhu . ' °C';
        }

        $spo2 = $value(
            $tandaVital->SATURASI_O2 ?? null
        );

        if ($spo2 !== '') {
            $primarySurvey[] = 'SpO₂: ' . $spo2 . '%';
        }

        $push(
            $primarySurvey,
            'Tingkat kesadaran',
            $tingkatKesadaran
        );

        $gcsTotal = $value(
            $tandaVital->GCS ?? null
        );

        $eye = $value(
            $tandaVital->EYE ?? null
        );

        $verbal = $value(
            $tandaVital->VERBAL ?? null
        );

        $motorik = $value(
            $tandaVital->MOTORIK ?? null
        );

        if (
            $gcsTotal !== ''
            || $eye !== ''
            || $verbal !== ''
            || $motorik !== ''
        ) {
            $primarySurvey[] =
                'GCS: '
                . ($gcsTotal !== '' ? $gcsTotal : '-')
                . ' (E' . ($eye !== '' ? $eye : '-')
                . ' V' . ($verbal !== '' ? $verbal : '-')
                . ' M' . ($motorik !== '' ? $motorik : '-')
                . ')';
        }

        $push(
            $primarySurvey,
            'Pupil',
            $tandaVital->PUPIL ?? null,
            $pupilMap
        );

        $diameterKanan = $value(
            $tandaVital->DIAMETER_PUPIL_UP ?? null
        );

        $diameterKiri = $value(
            $tandaVital->DIAMETER_PUPIL_DOWN ?? null
        );

        if ($diameterKanan !== '' || $diameterKiri !== '') {
            $primarySurvey[] =
                'Diameter pupil kanan/kiri: '
                . ($diameterKanan !== '' ? $diameterKanan : '-')
                . '/'
                . ($diameterKiri !== '' ? $diameterKiri : '-')
                . ' mm';
        }

        $refleksKanan = $value(
            $tandaVital->RC_UP ?? null
        );

        $refleksKiri = $value(
            $tandaVital->RC_DOWN ?? null
        );

        if ($refleksKanan !== '' || $refleksKiri !== '') {
            $primarySurvey[] =
                'Refleks cahaya kanan/kiri: '
                . ($refleksKanan !== '' ? $refleksKanan : '-')
                . '/'
                . ($refleksKiri !== '' ? $refleksKiri : '-');
        }

        $push(
            $primarySurvey,
            'VAS / nyeri',
            $tandaVital->VAS ?? null
        );

        $push(
            $primarySurvey,
            'Kulit',
            $tandaVital->KULIT ?? null,
            $kulitMap
        );

        /*
        |--------------------------------------------------------------------------
        | STATUS KEHAMILAN, OBGYN, DAN KEBUTUHAN KHUSUS
        |--------------------------------------------------------------------------
        */

        $statusKehamilan = [];

        $statusHamil = $value(
            $statusReproduksi->STATUS_REPRODUKSI ?? null
        );

        $push(
            $statusKehamilan,
            'Hamil',
            $statusHamil,
            ['0' => 'Tidak', '1' => 'Ya']
        );

        $push(
            $statusKehamilan,
            'G (Gravida)',
            $statusReproduksi->HAMIL_GRAVIDA ?? null
        );

        $push(
            $statusKehamilan,
            'P (Paritas)',
            $statusReproduksi->HAMIL_PARITAS ?? null
        );

        $push(
            $statusKehamilan,
            'A (Abortus)',
            $statusReproduksi->HAMIL_ABORTUS ?? null
        );

        $hpht = $value(
            $statusReproduksi->HPHT ?? null
        );

        if ($hpht !== '') {
            $hphtFormatted = $this->formatTanggal($hpht);

            $statusKehamilan[] =
                'HPHT: '
                . ($hphtFormatted !== '-' ? $hphtFormatted : $hpht);
        }

        $obgyn = $decodeJson(
            $triage->OBGYN ?? null
        );

        $push(
            $statusKehamilan,
            'Usia gestasi',
            $obgyn['USIA_GESTASI'] ?? null
        );

        if ($value($obgyn['USIA_GESTASI'] ?? null) !== '') {
            $statusKehamilan[count($statusKehamilan) - 1] .= ' minggu';
        }

        $push(
            $statusKehamilan,
            'Kontraksi uterus',
            $obgyn['KONTRAKSI_UTERUS'] ?? null
        );

        $push(
            $statusKehamilan,
            'Detak jantung janin',
            $obgyn['DETAK_JANTUNG'] ?? null
        );

        if ($value($obgyn['DETAK_JANTUNG'] ?? null) !== '') {
            $statusKehamilan[count($statusKehamilan) - 1] .= ' x/menit';
        }

        $push(
            $statusKehamilan,
            'Dilatasi serviks',
            $obgyn['DILATASI_SERVIKS'] ?? null
        );

        if ($value($obgyn['DILATASI_SERVIKS'] ?? null) !== '') {
            $statusKehamilan[count($statusKehamilan) - 1] .= ' cm';
        }

        $kebutuhanKhusus = $decodeJson(
            $triage->KEBUTUHAN_KHUSUS ?? null
        );

        $push(
            $statusKehamilan,
            'Kebutuhan khusus - Airborne',
            $kebutuhanKhusus['AIRBONE'] ?? null
        );

        $push(
            $statusKehamilan,
            'Kebutuhan khusus - Dekontaminan',
            $kebutuhanKhusus['DEKONTAMINAN'] ?? null
        );

        /*
        |--------------------------------------------------------------------------
        | ANAMNESIS DIPEROLEH
        |--------------------------------------------------------------------------
        */

        $anamnesisDiperolehText = [];

        if ($value($anamnesisDiperoleh->AUTOANAMNESIS ?? null) === '1') {
            $anamnesisDiperolehText[] = 'Autoanamnesis';
        }

        if ($value($anamnesisDiperoleh->ALLOANAMNESIS ?? null) === '1') {
            $dari = $value($anamnesisDiperoleh->DARI ?? null);

            $anamnesisDiperolehText[] =
                'Alloanamnesis'
                . ($dari !== '' ? ' dari ' . $dari : '');
        }

        /*
        |--------------------------------------------------------------------------
        | PSIKOSOSIAL, SPIRITUAL, DAN EKONOMI
        |--------------------------------------------------------------------------
        */

        $psikososialText = [];

        $psikologi = $checkedItems($psikososial, [
            'TIDAK_ADA_KELAINAN' => 'Tidak ada kelainan',
            'MARAH' => 'Marah',
            'CEMAS' => 'Cemas',
            'TAKUT' => 'Takut',
            'SEDIH' => 'Sedih',
            'BUNUH_DIRI' => 'Kecenderungan bunuh diri',
        ]);

        if ($psikologi !== '-') {
            $psikososialText[] =
                'Status psikologi: ' . $psikologi;
        }

        $psikologiLain = $value(
            $psikososial->LAINNYA ?? null
        );

        if ($psikologiLain !== '') {
            $psikososialText[] =
                'Status psikologi lainnya: ' . $psikologiLain;
        }

        $statusMentalMap = [
            '1' => 'Sadar dan orientasi baik',
            '2' => 'Ada masalah perilaku',
            '3' => 'Perilaku kekerasan yang dialami pasien sebelumnya',
        ];

        $statusMentalRaw = $value(
            $psikososial->STATUS_MENTAL ?? null
        );

        if (isset($statusMentalMap[$statusMentalRaw])) {
            $psikososialText[] =
                'Status mental: ' . $statusMentalMap[$statusMentalRaw];
        }

        $masalahPerilaku = $value(
            $psikososial->MASALAH_PERILAKU ?? null
        );

        if ($masalahPerilaku !== '') {
            $psikososialText[] =
                'Keterangan masalah perilaku: ' . $masalahPerilaku;
        }

        $riwayatKekerasan = $value(
            $psikososial->PERILAKU_KEKERASAN_DIALAMI_SEBELUMNYA ?? null
        );

        if ($riwayatKekerasan !== '') {
            $psikososialText[] =
                'Keterangan perilaku kekerasan sebelumnya: '
                . $riwayatKekerasan;
        }

        $push(
            $psikososialText,
            'Hubungan pasien dengan keluarga',
            $psikososial->HUBUNGAN_PASIEN_DENGAN_KELUARGA ?? null,
            ['1' => 'Baik', '2' => 'Tidak baik']
        );

        $push(
            $psikososialText,
            'Tempat tinggal',
            $psikososial->TEMPAT_TINGGAL ?? null,
            ['1' => 'Rumah', '2' => 'Panti', '3' => 'Lainnya']
        );

        $tempatTinggalLain = $value(
            $psikososial->TEMPAT_TINGGAL_LAINNYA ?? null
        );

        if ($tempatTinggalLain !== '') {
            $psikososialText[] =
                'Keterangan tempat tinggal: ' . $tempatTinggalLain;
        }

        $push(
            $psikososialText,
            'Agama',
            $pasien->AGAMA ?? null
        );

        $push(
            $psikososialText,
            'Kebiasaan beribadah teratur',
            $psikososial->KEBIASAAN_BERIBADAH_TERATUR ?? null,
            ['1' => 'Ya', '2' => 'Tidak']
        );

        $push(
            $psikososialText,
            'Nilai-nilai kepercayaan',
            $psikososial->NILAI_KEPERCAYAAN ?? null,
            ['1' => 'Ada', '2' => 'Tidak ada']
        );

        $nilaiKepercayaan = $value(
            $psikososial->NILAI_KEPERCAYAAN_DESKRIPSI ?? null
        );

        if ($nilaiKepercayaan !== '') {
            $psikososialText[] =
                'Keterangan nilai kepercayaan: ' . $nilaiKepercayaan;
        }

        $push(
            $psikososialText,
            'Pengambil keputusan dalam keluarga',
            $psikososial->PENGAMBIL_KEPUTUSAN_DALAM_KELUARGA ?? null
        );

        $push(
            $psikososialText,
            'Pekerjaan',
            $pasien->PEKERJAAN ?? null
        );

        $push(
            $psikososialText,
            'Penghasilan per bulan',
            $psikososial->PENGHASILAN_PERBULAN ?? null,
            [
                '1' => '< Rp 5.000.000',
                '2' => 'Rp 5.000.000 - Rp 10.000.000',
                '3' => '> Rp 10.000.000',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | STATUS FUNGSIONAL
        |--------------------------------------------------------------------------
        */

        $fungsionalText = [];

        $alatBantu = $checkedItems($fungsional, [
            'TANPA_ALAT_BANTU' => 'Tanpa alat bantu',
            'TONGKAT' => 'Tongkat',
            'KURSI_RODA' => 'Kursi roda',
            'BRANKARD' => 'Brankard',
            'WALKER' => 'Walker',
        ]);

        if ($alatBantu !== '-') {
            $fungsionalText[] =
                'Alat bantu mobilitas: ' . $alatBantu;
        }

        $alatBantuLain = $value(
            $fungsional->ALAT_BANTU ?? null
        );

        if ($alatBantuLain !== '') {
            $fungsionalText[] =
                'Alat bantu lainnya: ' . $alatBantuLain;
        }

        $cacatTubuh = '-';

        if ($value($fungsional->CACAT_TUBUH_YA ?? null) === '1') {
            $cacatTubuh = 'Ya';
        } elseif ($value($fungsional->CACAT_TUBUH_TIDAK ?? null) === '1') {
            $cacatTubuh = 'Tidak';
        }

        if ($cacatTubuh !== '-') {
            $fungsionalText[] = 'Cacat tubuh: ' . $cacatTubuh;
        }

        $ketCacat = $value(
            $fungsional->KET_CACAT_TUBUH ?? null
        );

        if ($ketCacat !== '') {
            $fungsionalText[] =
                'Keterangan cacat tubuh: ' . $ketCacat;
        }

        /*
        |--------------------------------------------------------------------------
        | SKRINING NYERI
        |--------------------------------------------------------------------------
        */

        $nyeriText = [];

        $push(
            $nyeriText,
            'Nyeri',
            $nyeri->NYERI ?? null,
            ['0' => 'Tidak', '1' => 'Ya']
        );

        $push(
            $nyeriText,
            'Onset',
            $nyeri->ONSET ?? null,
            ['1' => 'Akut', '2' => 'Kronis']
        );

        $skalaNyeri = $value(
            $nyeri->SKALA ?? null
        );

        if ($skalaNyeri !== '') {
            $nyeriText[] = 'Skala nyeri: ' . $skalaNyeri;

            $metodeRaw = $value(
                $nyeri->METODE ?? null
            );

            if (in_array($metodeRaw, ['1', '5'], true)) {
                $skor = (int) $skalaNyeri;

                $interpretasi = $skor === 0
                    ? 'Tidak nyeri'
                    : (
                        $skor <= 3
                            ? 'Nyeri ringan'
                            : (
                                $skor <= 6
                                    ? 'Nyeri sedang'
                                    : 'Nyeri berat'
                            )
                    );

                $nyeriText[] =
                    'Interpretasi skala: ' . $interpretasi;
            }
        }

        $metodeNyeriMap = [
            '1' => 'NRS',
            '2' => 'BPS',
            '3' => 'NIPS',
            '4' => 'FLACC',
            '5' => 'VAS',
        ];

        $metodeNyeriRaw = $value(
            $nyeri->METODE ?? null
        );

        $push(
            $nyeriText,
            'Metode penilaian',
            $metodeNyeriRaw,
            $metodeNyeriMap
        );

        if (in_array($metodeNyeriRaw, ['2', '3', '4'], true)) {
            $maks = $metodeNyeriRaw === '2'
                ? 3
                : ($metodeNyeriRaw === '3' ? 6 : 5);

            $komponen = [];

            for ($i = 1; $i <= $maks; $i++) {
                $score = $value(
                    $nyeri->{'SKOR' . $i} ?? null
                );

                if ($score !== '') {
                    $komponen[] =
                        'Komponen ' . $i . ': ' . $score;
                }
            }

            if ($komponen) {
                $nyeriText[] =
                    'Rincian skor '
                    . ($metodeNyeriMap[$metodeNyeriRaw] ?? '')
                    . ': '
                    . implode('; ', $komponen);
            }
        }

        foreach ([
            'PENCETUS' => 'Pencetus',
            'GAMBARAN' => 'Gambaran nyeri',
            'DURASI' => 'Durasi',
            'LOKASI' => 'Lokasi',
        ] as $column => $label) {
            $push(
                $nyeriText,
                $label,
                $nyeri->{$column} ?? null
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SKRINING RISIKO JATUH — HUMPTY DUMPTY
        |--------------------------------------------------------------------------
        */

        $humptyText = [];

        $humptyMaps = [
            'UMUR' => [
                '1' => '< 3 tahun',
                '2' => '3 s.d. < 7 tahun',
                '3' => '7 s.d. < 13 tahun',
                '4' => '>= 13 tahun',
            ],
            'JENIS_KELAMIN' => [
                '1' => 'Laki-laki',
                '2' => 'Perempuan',
            ],
            'DIAGNOSA' => [
                '1' => 'Diagnosis lainnya',
                '2' => 'Perubahan oksigenasi',
                '3' => 'Gangguan perilaku / psikiatri',
                '4' => 'Kelainan neurologis (meningitis, ensefalitis, kejang dan/atau gelisah)',
            ],
            'GANGGUAN_KONGNITIF' => [
                '1' => 'Orientasi terhadap kemampuan diri',
                '2' => 'Lupa kondisi sakit / kadang gelisah',
                '3' => 'Belum memiliki kontrol diri / gelisah',
            ],
            'FAKTOR_LINGKUNGAN' => [
                '1' => 'Area di luar rumah sakit',
                '2' => 'Tempat tidur bayi / khusus anak',
                '3' => 'Kursi / pangkuan',
                '4' => 'Riwayat jatuh / tempat tidur dewasa',
            ],
            'RESPON' => [
                '1' => '48 jam / tidak menjalani operasi',
                '2' => 'Dalam 48 jam',
                '3' => 'Dalam 24 jam',
            ],
            'PENGGUNAAN_OBAT' => [
                '1' => 'Obat lain / tidak menggunakan obat berisiko',
                '2' => 'Menggunakan salah satu obat berisiko',
                '3' => 'Multiple sedatif / hipnotik / barbiturat / antidepresan / laksatif / diuretik / narkose',
            ],
        ];

        foreach ($humptyMaps as $column => $map) {
            $push(
                $humptyText,
                str_replace('_', ' ', ucfirst(strtolower($column))),
                $humpty->{$column} ?? null,
                $map
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SKRINING RISIKO JATUH — MORSE
        |--------------------------------------------------------------------------
        */

        $morseText = [];

        $morseMaps = [
            'RIWAYAT_JATUH' => [
                '1' => 'Tidak',
                '2' => 'Ya',
            ],
            'DIAGNOSIS' => [
                '1' => 'Tidak',
                '2' => 'Ya',
            ],
            'ALAT_BANTU' => [
                '1' => 'Tidak ada / bed rest',
                '2' => 'Tongkat / alat penopang / walker',
                '3' => 'Furnitur',
            ],
            'HEPARIN' => [
                '1' => 'Tidak',
                '2' => 'Ya',
            ],
            'GAYA_BERJALAN' => [
                '1' => 'Normal',
                '2' => 'Lemah',
                '3' => 'Terganggu',
            ],
            'KESADARAN' => [
                '1' => 'Baik',
                '2' => 'Lupa / pelupa',
            ],
        ];

        foreach ($morseMaps as $column => $map) {
            $label = [
                'RIWAYAT_JATUH' => 'Riwayat jatuh (3 bulan terakhir)',
                'DIAGNOSIS' => 'Diagnosis sekunder',
                'ALAT_BANTU' => 'Alat bantu',
                'HEPARIN' => 'Penggunaan obat yang memengaruhi keseimbangan',
                'GAYA_BERJALAN' => 'Gaya berjalan',
                'KESADARAN' => 'Kesadaran',
            ][$column];

            $push(
                $morseText,
                $label,
                $morse->{$column} ?? null,
                $map
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SKRINING RISIKO JATUH — EPFRA
        |--------------------------------------------------------------------------
        */

        $epfraText = [];

        $usiaEpfra = $value(
            $epfra->USIA ?? null
        );

        if ($usiaEpfra !== '') {
            $deskripsiUsia = DB::table('master.referensi')
                ->where('ID', $usiaEpfra)
                ->where('STATUS', 1)
                ->value('DESKRIPSI');

            $epfraText[] =
                'Usia pasien: '
                . ($deskripsiUsia ?: $usiaEpfra);
        }

        $epfraMaps = [
            'STATUS_MENTAL' => [
                '1' => 'Sadar penuh dan orientasi waktu baik',
                '2' => 'Agitasi / cemas',
                '3' => 'Sering bingung',
                '4' => 'Bingung dan disorientasi',
            ],
            'ELIMINASI' => [
                '1' => 'Mandiri BAB dan BAK',
                '2' => 'Memakai kateter / ostomy',
                '3' => 'BAB dan BAK dengan bantuan',
                '4' => 'Gangguan eliminasi',
                '5' => 'Inkontinensia tetapi bisa ambulasi mandiri',
            ],
            'MEDIKASI' => [
                '1' => 'Tidak ada pengobatan',
                '2' => 'Obat-obatan jantung',
                '3' => 'Obat psikiatri termasuk benzodiazepin / antidepresan',
                '4' => 'Dosis obat meningkat / ditambahkan dalam 24 jam terakhir',
            ],
            'DIAGNOSIS' => [
                '1' => 'Bipolar / gangguan skizoafektif',
                '2' => 'Penyalahgunaan zat terlarang dan alkohol',
                '3' => 'Gangguan depresi mayor',
                '4' => 'Demensia / delirium',
            ],
            'AMBULASI' => [
                '1' => 'Mandiri / langkah stabil atau imobil',
                '2' => 'Menggunakan alat bantu yang tepat',
                '3' => 'Vertigo / hipotensi ortostatik / kelemahan',
                '4' => 'Langkah tidak stabil, butuh bantuan dan menyadari kemampuan',
                '5' => 'Langkah tidak stabil, butuh bantuan dan tidak menyadari ketidakmampuan',
            ],
            'NUTRISI' => [
                '1' => 'Asupan makanan / minum sedikit dalam 24 jam terakhir',
                '2' => 'Nafsu makan baik',
            ],
            'GANGGUAN_TIDUR' => [
                '1' => 'Tidak ada gangguan tidur',
                '2' => 'Ada gangguan tidur',
            ],
            'RIWAYAT_JATUH' => [
                '1' => 'Tidak ada riwayat jatuh',
                '2' => 'Ada riwayat jatuh dalam 3 bulan terakhir',
            ],
        ];

        foreach ($epfraMaps as $column => $map) {
            $push(
                $epfraText,
                str_replace('_', ' ', ucfirst(strtolower($column))),
                $epfra->{$column} ?? null,
                $map
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SKRINING RISIKO JATUH — GET UP AND GO
        |--------------------------------------------------------------------------
        */

        $getUpAndGoText = [];

        $push(
            $getUpAndGoText,
            'Cara berjalan pasien berisiko',
            $getUpAndGo->CARA_BERJALAN_PASIEN ?? null,
            ['0' => 'Tidak', '1' => 'Ya']
        );

        $push(
            $getUpAndGoText,
            'Faktor risiko',
            $getUpAndGo->FAKTOR_RESIKO ?? null,
            ['0' => 'Tidak', '1' => 'Ya']
        );

        $push(
            $getUpAndGoText,
            'Obat yang diminum berisiko',
            $getUpAndGo->OBAT_YANG_DIMINUM ?? null,
            ['0' => 'Tidak', '1' => 'Ya']
        );

        /*
        |--------------------------------------------------------------------------
        | SKRINING GIZI — MUST
        |--------------------------------------------------------------------------
        */

        $mustText = [];

        $push(
            $mustText,
            'Perubahan berat badan signifikan 3 bulan terakhir',
            $must->BERAT_BADAN_SIGNIFIKAN ?? null,
            ['0' => 'Tidak', '1' => 'Ya']
        );

        $push(
            $mustText,
            'Jumlah perubahan berat badan',
            $must->PERUBAHAN_BERAT_BADAN ?? null,
            [
                '1' => '0,5 - 5 kg',
                '2' => '> 5 - 10 kg',
                '3' => '> 10 - 15 kg',
                '4' => '> 15 kg',
            ]
        );

        $push(
            $mustText,
            'Intake makanan berkurang karena tidak ada nafsu makan',
            $must->INTAKE_MAKANAN ?? null,
            ['0' => 'Tidak', '1' => 'Ya']
        );

        $push(
            $mustText,
            'Kondisi khusus',
            $must->KONDISI_KHUSUS ?? null
        );

        $mustScore = $value(
            $must->SKOR ?? null
        );

        if ($mustScore !== '') {
            $mustCategory = ((int) $mustScore >= 4)
                ? 'Risiko Berat'
                : (
                    ((int) $mustScore >= 2)
                        ? 'Risiko Sedang'
                        : 'Risiko Ringan'
                );

            $mustText[] =
                'Skor MUST: ' . $mustScore
                . ' (' . $mustCategory . ')';
        }

        /*
        |--------------------------------------------------------------------------
        | SKRINING GIZI — STRONG KID
        |--------------------------------------------------------------------------
        */

        $strongKidText = [];

        $push(
            $strongKidText,
            'Pasien tampak kurus',
            $strongKid->TAMPAK_KURUS ?? null,
            ['0' => 'Tidak', '1' => 'Ya']
        );

        $push(
            $strongKidText,
            'Penurunan berat badan',
            $strongKid->PENURUNAN_BERAT_BADAN ?? null,
            ['0' => 'Tidak', '1' => 'Ya']
        );

        $push(
            $strongKidText,
            'Diare / intake makanan berkurang',
            $strongKid->DIARE_INTAKE_MAKANAN ?? null,
            ['0' => 'Tidak', '1' => 'Ya']
        );

        $push(
            $strongKidText,
            'Penyakit / keadaan berisiko malnutrisi',
            $strongKid->RESIKO_MALNUTRISI ?? null,
            ['0' => 'Tidak', '1' => 'Ya']
        );

        $strongKidScore = $value(
            $strongKid->SKOR ?? null
        );

        if ($strongKidScore !== '') {
            $strongKidCategory = ((int) $strongKidScore >= 3)
                ? 'Risiko Tinggi'
                : (
                    ((int) $strongKidScore === 2)
                        ? 'Risiko Sedang'
                        : 'Tidak Berisiko'
                );

            $strongKidText[] =
                'Skor STRONG KID: ' . $strongKidScore
                . ' (' . $strongKidCategory . ')';
        }

        /*
        |--------------------------------------------------------------------------
        | IMPLEMENTASI KEPERAWATAN
        |--------------------------------------------------------------------------
        */

        $implementasiText = [];

        $implementasiLabels = [
            'IK1' => 'Melakukan observasi TTV',
            'IK2' => 'Melakukan observasi keadaan umum pasien',
            'IK3' => 'Memonitor intake output',
            'IK4' => 'Memonitor pernapasan: irama, pengembangan dinding dada, otot bantu dan bunyi napas',
            'IK5' => 'Melakukan pemasangan oximetri',
            'IK6' => 'Mengobservasi produk sputum, jumlah, warna dan kekentalan',
            'IK7' => 'Memberikan posisi semi Fowler atau posisi miring yang nyaman',
            'IK8' => 'Melakukan pemasangan OPA',
            'IK9' => 'Melakukan suction bila perlu',
            'IK10' => 'Mengajarkan napas dalam dan batuk efektif',
            'IK11' => 'Memberikan oksigen',
            'IK12' => 'Mengimobilisasikan daerah cedera (bidai / spalk / sling)',
            'IK13' => 'Melakukan perawatan luka',
            'IK14' => 'Mengajarkan manajemen pengelolaan nyeri',
            'IK15' => 'Melakukan tindakan dengan teknik aseptik',
        ];

        foreach ($implementasiLabels as $column => $label) {
            if ($value($implementasi->{$column} ?? null) === '1') {
                if ($column === 'IK11') {
                    $liter = $value(
                        $implementasi->IK11_LAIN ?? null
                    );

                    if ($liter !== '') {
                        $label .= ' (' . $liter . ' liter/menit)';
                    }
                }

                $implementasiText[] = $label;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | TINDAKAN KOLABORASI
        |--------------------------------------------------------------------------
        */

        $kolaborasiText = [];

        $kolaborasiLabels = [
            1 => 'Terapi oksigenasi',
            2 => 'Infus',
            3 => 'Injeksi',
            4 => 'NGT / OGT',
            5 => 'Kateter urin (DC)',
            6 => 'EKG',
            7 => 'Suction',
            8 => 'OPA',
            9 => 'Collar neck',
            10 => 'Resusitasi',
            11 => 'Nebulizer',
            12 => 'Medikasi',
            13 => 'Ekstraksi corpal',
            14 => 'Hecting',
            15 => 'Bilas lambung',
            16 => 'Bidai',
            18 => 'Tampon',
            99 => 'Lain-lain',
        ];

        foreach ($kolaborasiLabels as $number => $label) {
            $column = 'TK' . $number;

            if ($value($kolaborasi->{$column} ?? null) !== '1') {
                continue;
            }

            if ($number === 99) {
                $lain = $value(
                    $kolaborasi->TK99_LAIN ?? null
                );

                if ($lain !== '') {
                    $label .= ': ' . $lain;
                }
            }

            $time = $value(
                $kolaborasi->{'TK' . $number . '_TIME'} ?? null
            );

            if ($time !== '') {
                try {
                    $time = \Carbon\Carbon::parse($time)->format('H:i');
                } catch (\Throwable $e) {
                    $time = substr($time, 0, 5);
                }

                $label .= ' (pukul ' . $time . ')';
            }

            $kolaborasiText[] = $label;
        }

        /*
        |--------------------------------------------------------------------------
        | DIAGNOSIS / MASALAH KEPERAWATAN
        |--------------------------------------------------------------------------
        */

        $masalahText = [];

        $masalahLabels = [
            'NYERI' => 'Nyeri',
            'CEMAS' => 'Cemas',
            'PERUBAHAN_NUTRISI' => 'Perubahan nutrisi',
            'GANGGUAN_PERNAFASAN' => 'Gangguan pernapasan',
            'GANGGUAN_PERFUSI_JARINGAN' => 'Gangguan perfusi jaringan',
            'GANGGUAN_VOLUME_CAIRAN' => 'Gangguan volume cairan',
            'POTENSI_INFEKSI' => 'Potensi infeksi',
            'HIPERTERMI' => 'Hipertermi',
            'TAKUT' => 'Takut (pada anak)',
            'KETIDAKEFEKTIFAN_POLA_MAKAN' => 'Ketidakefektifan pola makan',
        ];

        foreach ($masalahLabels as $column => $label) {
            if ($value($masalah->{$column} ?? null) === '1') {
                $masalahText[] = $label;
            }
        }

        $masalahLain = $value(
            $masalah->MASALAH_LAIN ?? null
        );

        if ($masalahLain !== '') {
            $masalahText[] =
                'Masalah keperawatan lain: ' . $masalahLain;
        }

        /*
        |--------------------------------------------------------------------------
        | DISCHARGE PLANNING
        |--------------------------------------------------------------------------
        */

        $dischargeText = [];

        foreach ([
            'PASIEN_TINGGAL_SENDIRI' => 'Pasien tinggal sendiri',
            'PASIEN_KHAWATIR_KETIKA_DIRUMAH' => 'Pasien / keluarga khawatir ketika kembali ke rumah',
            'PASIEN_TAK_ADA_YANG_MERAWAT' => 'Pasien tidak ada yang merawat di rumah',
            'PASIEN_DILANTAI_ATAS' => 'Pasien tinggal di lantai atas rumah',
        ] as $column => $label) {
            $push(
                $dischargeText,
                $label,
                $discharge1->{$column} ?? null,
                ['0' => 'Tidak', '1' => 'Ya']
            );
        }

        $dischargeBooleanFields = [
            'PASIEN_PULANG' => 'Pasien pulang dengan jumlah obat > 6',
            'PASIEN_MENGAJUKAN' => 'Pasien mengajukan pendampingan ke rumah sakit',
            'TIDAK_ADA_KRITERIA' => 'Tidak ada kriteria pasien',
            'PERAWATAN_LANJUTAN_MEDIS' => 'Perawatan lanjutan / penggunaan alat medis di rumah',
            'KEBUTUHAN_PELAYANAN_BERKELANJUTAN_KPB' => 'Kebutuhan pelayanan berkelanjutan',
            'PENGGUNAAN_ALAT_MEDIS_PAM' => 'Penggunaan alat medis / bantu',
            'SKRINING_LANJUTAN' => 'Skrining lanjutan',
        ];

        foreach ($dischargeBooleanFields as $column => $label) {
            $push(
                $dischargeText,
                $label,
                $discharge2->{$column} ?? null,
                ['0' => 'Tidak', '1' => 'Ya']
            );
        }

        $plm = $checkedItems($discharge2, [
            'PLM_KATETER_URIN' => 'Kateter urin',
            'PLM_TRAECHOSTOMY' => 'Trakeostomi',
            'PLM_NGT' => 'NGT',
            'PLM_COLOSTOMY' => 'Kolostomi',
        ]);

        if ($plm !== '-') {
            $dischargeText[] =
                'Rincian perawatan lanjutan: ' . $plm;
        }

        $lainLanjutan = $value(
            $discharge2->KEBUTUHAN_PELAYANAN_BERKELANJUTAN_LAINNYA ?? null
        );

        if ($lainLanjutan !== '') {
            $dischargeText[] =
                'Perawatan / pelayanan berkelanjutan lainnya: '
                . $lainLanjutan;
        }

        $kpb = $checkedItems($discharge2, [
            'KPB_RAWAT_LUKA' => 'Rawat luka',
            'KPB_TB' => 'TB',
            'KPB_DM_TERAPI_INSULIN' => 'DM dengan terapi insulin',
            'KPB_PPOK' => 'PPOK',
            'KPB_PASIEN_KEMO' => 'Pasien kemoterapi',
            'KPB_HIV' => 'HIV / AIDS',
            'KPB_DM' => 'DM',
            'KPB_STROKE' => 'Stroke',
            'KPB_CKD' => 'CKD',
        ]);

        if ($kpb !== '-') {
            $dischargeText[] =
                'Rincian kebutuhan pelayanan berkelanjutan: ' . $kpb;
        }

        $pam = $checkedItems($discharge2, [
            'PAM_KATETER_URIN' => 'Kateter urin',
            'PAM_TRAECHOSTOMY' => 'Trakeostomi',
            'PAM_NGT' => 'NGT',
            'PAM_COLOSTOMY' => 'Kolostomi',
        ]);

        if ($pam !== '-') {
            $dischargeText[] =
                'Rincian penggunaan alat medis / bantu: ' . $pam;
        }

        $pamLain = $value(
            $discharge2->PAM_LAINNYA ?? null
        );

        if ($pamLain !== '') {
            $dischargeText[] =
                'Alat medis / bantu lainnya: ' . $pamLain;
        }

        $push(
            $dischargeText,
            'Jenis skrining lanjutan',
            $discharge2->SKRINING ?? null,
            ['1' => 'Konsul MPP', '2' => 'Edukasi']
        );

        /*
        |--------------------------------------------------------------------------
        | DATA RETURN UNTUK TEMPLATE print_radar_perawat.docx
        |--------------------------------------------------------------------------
        */

        $data = [
            // Identitas pasien dan kunjungan
            'NAMA_PASIEN' => $placeholder($namaPasien),
            'NORM' => $placeholder($norm),
            'JENIS_KELAMIN' => $placeholder(
                $pasien->JENIS_KELAMIN ?? null
            ),
            'TANGGAL_LAHIR' => $this->formatTanggal($tanggalLahirRaw),
            'UMUR' => $umur,
            'NOPEN' => $placeholder($pasien->NOPEN ?? null),
            'KUNJUNGAN' => $placeholder(
                $pasien->KUNJUNGAN ?? $kunjungan
            ),
            'TANGGAL_MASUK' => $this->formatTanggalWaktuMedis(
                $pasien->TANGGAL_MASUK ?? null
            ) ?: '-',
            'TANGGAL_KELUAR' => $this->formatTanggalWaktuMedis(
                $pasien->TANGGAL_KELUAR ?? null
            ) ?: '-',
            'RUANGAN' => $placeholder(
                $pasien->NAMA_RUANGAN ?? null
            ),
            'DPJP' => $placeholder(
                $pasien->NAMA_DPJP ?? null
            ),

            // Triage
            'TRIAGE_CARA_KEDATANGAN' => $formatLines($caraKedatangan),
            'TRIAGE_JENIS_KASUS' => $formatLines($detailKasus),
            'TRIAGE_RISIKO_INFEKSI' => $risikoInfeksi,
            'TRIAGE_KRITERIA' => $placeholder(
                $triage->KRITERIA ?? null
            ),
            'ATS_KATEGORI' => $atsKategori,
            'ATS_PRIORITAS' => $atsPrioritas,
            'ATS_PLAN' => $atsPlan,
            'ATS_DETAIL' => $formatLines($atsDetail),
            'TRIAGE_ANAMNESIS' => $formatLines($detailAnamnesis),

            // Primary survey dan status reproduksi
            'PRIMARY_SURVEY' => $formatLines($primarySurvey),
            'STATUS_KEHAMILAN' => $formatLines($statusKehamilan),
            'ANAMNESIS_DIPEROLEH' => $formatLines($anamnesisDiperolehText),

            // Asesmen psikososial dan fungsional
            'PSIKOSOSIAL' => $formatLines($psikososialText),
            'STATUS_FUNGSIONAL' => $formatLines($fungsionalText),

            // Skrining
            'SKRINING_NYERI' => $formatLines($nyeriText),
            'RISIKO_JATUH_HUMPTY_DUMPTY' => $formatLines($humptyText),
            'RISIKO_JATUH_MORSE' => $formatLines($morseText),
            'RISIKO_JATUH_EPFRA' => $formatLines($epfraText),
            'RISIKO_JATUH_GET_UP_AND_GO' => $formatLines($getUpAndGoText),
            'SKRINING_GIZI_MUST' => $formatLines($mustText),
            'SKRINING_GIZI_STRONG_KID' => $formatLines($strongKidText),

            // Implementasi dan tindakan
            'IMPLEMENTASI_KEPERAWATAN' => $formatLines($implementasiText),
            'TINDAKAN_KOLABORASI' => $formatLines($kolaborasiText),
            'DIAGNOSIS_KEPERAWATAN' => $formatLines($masalahText),
            'DISCHARGE_PLANNING' => $formatLines($dischargeText),

            // Finalisasi perawat
            'NAMA_PERAWAT_FINALISASI' => $placeholder(
                $finalisasi->NAMA_USER_FINALISASI ?? null
            ),
            'NIP_PERAWAT_FINALISASI' => $placeholder($nipFinalisasi),
            'TTD_PERAWAT_FINALISASI' => $ttdPerawat->signature_path ?? null,
        ];

        // print_r($data);
        // die();

        return $data;
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
