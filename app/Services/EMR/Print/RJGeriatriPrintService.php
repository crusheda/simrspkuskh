<?php

namespace App\Services\EMR\Print;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RJGeriatriPrintService implements PrintDataInterface
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
            ->where('fin.FORM', 'pengkajian-rajal-geriatri')
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
        | TOLAK UKUR DAN TERAPI
        |--------------------------------------------------------------------------
        */
        $assessment = DB::table('medicalrecord.sirmed_assesment')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        $tolokUkur = $assessment->TOLAK_UKUR ?? null;

        $rencanaTerapi = DB::table('medicalrecord.rencana_terapi')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        $terapi = $rencanaTerapi->DESKRIPSI ?? null;

        /*
        |--------------------------------------------------------------------------
        | MATERI EDUKASI
        |--------------------------------------------------------------------------
        */
        $edukasi = DB::table('medicalrecord.edukasi_rajal')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        $daftarMateriEdukasi = [];

        if ($edukasi) {
            $materiEdukasi = [
                'ME_TANDA_GEJALA' => 'Tanda dan gejala',
                'ME_HASIL_PEMERIKSAAN' => 'Hasil pemeriksaan',
                'ME_DIAGNOSIS' => 'Diagnosis',
                'ME_RENCANA_PENATALAKSANAAN' => 'Rencana penatalaksanaan',
                'ME_TINDAKAN_TUJUAN_TERAPI' => 'Tindakan dan tujuan terapi',
            ];

            foreach ($materiEdukasi as $kolom => $label) {
                if ((int) ($edukasi->{$kolom} ?? 0) === 1) {
                    $daftarMateriEdukasi[] = $label;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | TINDAK LANJUT
        |--------------------------------------------------------------------------
        */
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

        $pemeriksaanFisik = $this->formatPemeriksaanFisik($pemfis);

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

            'PEMERIKSAAN_FISIK' => $this->placeholder($pemeriksaanFisikRajal),

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
            | TOLAK UKUR, TERAPI, EDUKASI, DAN TINDAK LANJUT
            */

            'TOLOK_UKUR' => $this->placeholder($tolokUkur),
            'TERAPI' => $this->placeholder($terapi),
            'MATERI_EDUKASI' => $this->placeholder(
                implode(', ', $daftarMateriEdukasi)
            ),
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
            ->where('fin.FORM', 'pengkajian-rajal-geriatri')
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
            ->where('fin.FORM', 'pengkajian-rajal-geriatri')
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
        | SKRINING RISIKO JATUH GET UP AND GO
        |--------------------------------------------------------------------------
        */
        $getUpAndGo = DB::table('medicalrecord.penilaian_getup_and_go')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first([
                'CARA_BERJALAN_PASIEN',
                'FAKTOR_RESIKO',
                'OBAT_YANG_DIMINUM',
            ]);

        // Helper checkbox khusus Get Up and Go
        $centangGetUpAndGo = static function ($nilai, $pilihan): string {
            return (string) ($nilai ?? '') === (string) $pilihan
                ? '☑'
                : '☐';
        };

        $detailRisikoJatuh = [];

        if ($getUpAndGo) {

            /*
            |--------------------------------------------------------------------------
            | 1. CARA BERJALAN PASIEN
            |--------------------------------------------------------------------------
            */
            $caraBerjalan =
                $getUpAndGo->CARA_BERJALAN_PASIEN ?? null;

            $detailRisikoJatuh[] =
                '1. Cara Berjalan Pasien (Salah Satu atau Lebih):';

            $detailRisikoJatuh[] =
                '   a. Tidak seimbang / menyopang / limbung';

            $detailRisikoJatuh[] =
                '   b. Jalan menggunakan alat bantu '
                . '(tongkat, kursi roda, dibantu orang lain)';

            $detailRisikoJatuh[] =
                '   '
                . $centangGetUpAndGo($caraBerjalan, 1) . ' Ya'
                . '     '
                . $centangGetUpAndGo($caraBerjalan, 0) . ' Tidak';

            /*
            |--------------------------------------------------------------------------
            | 2. FAKTOR RISIKO
            |--------------------------------------------------------------------------
            */
            $faktorResiko =
                $getUpAndGo->FAKTOR_RESIKO ?? null;

            $detailRisikoJatuh[] = '';

            $detailRisikoJatuh[] =
                '2. Faktor Risiko:';

            $detailRisikoJatuh[] =
                '   A. Umur ≥ 60 tahun, anak < 3 tahun';

            $detailRisikoJatuh[] =
                '   B. Diagnosis penyakit / situasi / keluhan '
                . 'yang mungkin menyebabkan pasien berisiko jatuh:';

            $detailRisikoJatuh[] =
                '      - Vertigo / Pusing';

            $detailRisikoJatuh[] =
                '      - Parkinson';

            $detailRisikoJatuh[] =
                '      - Gangguan penglihatan yang belum terkoreksi '
                . '(Glaukoma, Katarak, Gangguan Lapangan Penglihatan)';

            $detailRisikoJatuh[] =
                '      - Riwayat tirah baring lama yang akan '
                . 'dipindahkan untuk pemeriksaan penunjang';

            $detailRisikoJatuh[] =
                '      - Pasien yang mendapat sedasi';

            $detailRisikoJatuh[] =
                '   C. Lingkungan:';

            $detailRisikoJatuh[] =
                '      - Area berisiko jatuh (tangga, penerangan kurang, '
                . 'jalan menurun / menanjak)';

            $detailRisikoJatuh[] =
                '      - Poli tujuan: Rehabilitasi Medik, '
                . 'Radiologi, Radioterapi';

            $detailRisikoJatuh[] =
                '   '
                . $centangGetUpAndGo($faktorResiko, 1) . ' Ya'
                . '     '
                . $centangGetUpAndGo($faktorResiko, 0) . ' Tidak';

            /*
            |--------------------------------------------------------------------------
            | 3. OBAT-OBATAN
            |--------------------------------------------------------------------------
            */
            $obat = $getUpAndGo->OBAT_YANG_DIMINUM ?? null;

            $detailRisikoJatuh[] = '';

            $detailRisikoJatuh[] =
                '3. Obat-obatan yang diminum pasien saat ini '
                . 'yang mungkin menyebabkan risiko jatuh:';

            $detailRisikoJatuh[] =
                '   - Narkotik';

            $detailRisikoJatuh[] =
                '   - Anti hipertensi';

            $detailRisikoJatuh[] =
                '   - Diuretik';

            $detailRisikoJatuh[] =
                '   - Obat penyakit jantung';

            $detailRisikoJatuh[] =
                '   - Pengencer darah';

            $detailRisikoJatuh[] =
                '   - Tetes mata yang menyebabkan midriasis';

            $detailRisikoJatuh[] =
                '   - Antihistamin dengan dosis tinggi';

            $detailRisikoJatuh[] =
                '   - Obat antidiabetes / insulin';

            $detailRisikoJatuh[] =
                '   '
                . $centangGetUpAndGo($obat, 1) . ' Ya'
                . '     '
                . $centangGetUpAndGo($obat, 0) . ' Tidak';

            /*
            |--------------------------------------------------------------------------
            | HASIL SKOR DAN KATEGORI
            |--------------------------------------------------------------------------
            */
            // Mengikuti rumus pada JavaScript form Get Up and Go
            $skorGetUpAndGo =
                (int) ($caraBerjalan ?? 0)
                + (int) ($faktorResiko ?? 0)
                + (int) ($obat ?? 0);

            // Skor dianggap valid jika ketiga penilaian sudah terisi
            $dataLengkap =
                $caraBerjalan !== null
                && $faktorResiko !== null
                && $obat !== null;

            if ($dataLengkap) {
                $kategoriRisikoJatuh = $skorGetUpAndGo > 0
                    ? 'Berisiko Jatuh'
                    : 'Tidak Berisiko Jatuh';

                $detailRisikoJatuh[] = '';

                $detailRisikoJatuh[] =
                    'Skor Risiko Jatuh: ' . $skorGetUpAndGo;

                $detailRisikoJatuh[] =
                    'Kesimpulan: ' . $kategoriRisikoJatuh;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | SKRINING GIZI MST
        |--------------------------------------------------------------------------
        */
        $skriningGizi = DB::table('medicalrecord.permasalahan_gizi')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first([
                'BERAT_BADAN_SIGNIFIKAN',
                'PERUBAHAN_BERAT_BADAN',
                'INTAKE_MAKANAN',
                'KONDISI_KHUSUS',
                'SKOR',
                'STATUS_SKOR',
            ]);

        // Helper kotak centang untuk pilihan MST
        $centangGizi = static function ($nilai, $pilihan): string {
            return (string) ($nilai ?? '') === (string) $pilihan
                ? '☑'
                : '☐';
        };

        $detailSkriningGizi = [];

        if ($skriningGizi) {

            // Pertanyaan 1: Perubahan berat badan signifikan
            $beratBadanSignifikan =
                $skriningGizi->BERAT_BADAN_SIGNIFIKAN ?? null;

            $detailSkriningGizi[] =
                '1. Adakah perubahan berat badan signifikan dalam 3 bulan terakhir?';

            $detailSkriningGizi[] =
                '   '
                . $centangGizi($beratBadanSignifikan, 0) . ' Tidak'
                . '     '
                . $centangGizi($beratBadanSignifikan, 1) . ' Ya';

            // Pertanyaan 2: Jumlah perubahan berat badan
            $jumlahBeratBadan =
                $skriningGizi->PERUBAHAN_BERAT_BADAN ?? null;

            $detailSkriningGizi[] =
                '   Jumlah perubahan berat badan:';

            $opsiBeratBadan = [
                1 => '0,5 - 5 kg',
                2 => '> 5 - 10 kg',
                3 => '> 10 - 15 kg',
                4 => '> 15 kg',
            ];

            $barisBeratBadan = [];

            foreach ($opsiBeratBadan as $nilai => $label) {
                $barisBeratBadan[] =
                    $centangGizi($jumlahBeratBadan, $nilai)
                    . ' ' . $label;
            }

            $detailSkriningGizi[] =
                '   ' . implode('     ', $barisBeratBadan);

            // Pertanyaan 3: Intake makanan
            $intakeMakanan =
                $skriningGizi->INTAKE_MAKANAN ?? null;

            $detailSkriningGizi[] =
                '2. Intake makanan kurang karena tidak ada nafsu makan?';

            $detailSkriningGizi[] =
                '   '
                . $centangGizi($intakeMakanan, 0) . ' Tidak'
                . '     '
                . $centangGizi($intakeMakanan, 1) . ' Ya';

            // Pertanyaan 4: Kondisi khusus berupa isian teks
            $kondisiKhusus = $this->value(
                $skriningGizi->KONDISI_KHUSUS ?? null
            );

            $detailSkriningGizi[] =
                '3. Kondisi khusus: '
                . ($kondisiKhusus !== '' ? $kondisiKhusus : '........................');

            // Hasil skor MST
            $skorMST = $skriningGizi->SKOR ?? null;

            $kategoriMST = '';

            if ($skorMST !== null && $skorMST !== '') {
                $skorMST = (int) $skorMST;

                if ($skorMST >= 4) {
                    $kategoriMST = 'Risiko Berat';
                } elseif ($skorMST >= 2) {
                    $kategoriMST = 'Risiko Sedang';
                } else {
                    $kategoriMST = 'Risiko Ringan';
                }

                $detailSkriningGizi[] = '';
                $detailSkriningGizi[] =
                    'Skor MST: ' . $skorMST;

                $detailSkriningGizi[] =
                    'Kategori: ' . $kategoriMST;
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
        | RENCANA ASUHAN KEPERAWATAN
        |--------------------------------------------------------------------------
        */
        $rencanaAsuhanKeperawatan = DB::table(
            'medicalrecord.sirmed_diagnosa_keperawatan'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->value('RENCANA_ASUHAN_KEPERAWATAN');

        /*
        |--------------------------------------------------------------------------
        | ASESMEN SINDROM GERIATRI
        |--------------------------------------------------------------------------
        */
        $geriatri = DB::table('medicalrecord.sirmed_assesmen_sindrom_geriatri')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first();

        $kotakPilihanGeriatri = static function ($nilai, array $opsi): string {
            $nilai = (string) ($nilai ?? '');
            $baris = [];

            foreach ($opsi as $value => $label) {
                $centang = $nilai !== '' && $nilai === (string) $value
                    ? '☑'
                    : '☐';

                $baris[] = $centang . ' ' . $label;
            }

            return implode('   ', $baris);
        };

        $detailGeriatri = [];

        /*
        |--------------------------------------------------------------------------
        | BARTHEL INDEX
        |--------------------------------------------------------------------------
        */
        $barthel = DB::table('medicalrecord.penilaian_barthel_index')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first();

        $detailGeriatri = [
            '1. Penapisan Status Fungsional',
            '1.a. Activity Daily Living (ADL) Barthel',
        ];

        $aktivitasBarthel = [
            232 => ['KENDALI_RANGSANG_DEFEKASI', 'Kontrol rangsang defekasi'],
            233 => ['KENDALI_RANGSANG_KEMIH', 'Kontrol rangsang kemih'],
            234 => ['BERSIH_DIRI', 'Bersih diri'],
            235 => ['PENGGUNAAN_JAMBAN', 'Penggunaan jamban'],
            236 => ['MAKAN', 'Makan'],
            237 => ['PERUBAHAN_SIKAP', 'Berpindah tempat'],
            238 => ['PINDAH_JALAN', 'Pindah/jalan'],
            239 => ['PAKAI_BAJU', 'Memakai baju'],
            240 => ['NAIK_TURUN_TANGGA', 'Naik turun tangga'],
            241 => ['MANDI', 'Mandi'],
        ];

        if ($barthel) {
            foreach ($aktivitasBarthel as $jenis => [$kolom, $label]) {
                $nilai = $barthel->{$kolom} ?? null;

                $deskripsi = null;

                if ($nilai !== null && $nilai !== '') {
                    $deskripsi = DB::table('master.referensi')
                        ->where('JENIS', $jenis)
                        ->where('ID', $nilai)
                        ->value('DESKRIPSI');
                }

                $nomorAktivitas = array_search($jenis, array_keys($aktivitasBarthel), true) + 1;

                $detailGeriatri[] = '1.a.' . $nomorAktivitas . '. '
                    . $label . ': ' . $this->value($deskripsi ?? '-');
            }
        }

        $skorAdl = $this->value($geriatri->ADL ?? null);

        if ($skorAdl !== '') {
            $kategoriAdl = match (true) {
                (int) $skorAdl <= 4 => 'Ketergantungan total',
                (int) $skorAdl <= 8 => 'Ketergantungan berat',
                (int) $skorAdl <= 12 => 'Ketergantungan sedang',
                (int) $skorAdl <= 16 => 'Ketergantungan ringan',
                default => 'Mandiri',
            };

            $detailGeriatri[] = 'Total skor ADL Barthel: '
                . $skorAdl . ' (' . $kategoriAdl . ')';
        }

        /*
        |--------------------------------------------------------------------------
        | ASESMEN SINDROM GERIATRI
        |--------------------------------------------------------------------------
        */
        if ($geriatri) {
            $detailGeriatri[] = '1.b. Instrumental ADL (IADL): '
                . $kotakPilihanGeriatri($geriatri->IADL ?? null, [
                    1 => 'Independen (0)',
                    2 => 'Kadang-kadang perlu bantuan (1)',
                    3 => 'Perlu bantuan sepanjang waktu (2)',
                    4 => 'Tidak beraktivitas / dikerjakan orang lain (3–8)',
                ]);

            $detailGeriatri[] = '1.c. ACS / Sindrom Delirium Akut: '
                . $kotakPilihanGeriatri($geriatri->ACS ?? null, [
                    1 => 'Ya',
                    0 => 'Tidak',
                ]);

            $detailGeriatri[] = '2. Penilaian Status Nutrisi (MNA): '
                . $kotakPilihanGeriatri($geriatri->NUTRISI ?? null, [
                    1 => 'Normal',
                    2 => 'Risiko malnutrisi',
                    3 => 'Kemungkinan malnutrisi',
                    4 => 'Malnutrisi (<17)',
                ]);

            $detailGeriatri[] = '3. Penapisan Kognitif (MMSE): '
                . $kotakPilihanGeriatri($geriatri->KOGNITIF ?? null, [
                    1 => 'Normal (24–30)',
                    2 => 'Gangguan kognitif ringan (MCI 17–23)',
                    3 => 'Gangguan kognitif pasti (≤16)',
                    4 => 'Belum dapat dievaluasi',
                ]);

            $detailGeriatri[] = '4. Penapisan Depresi (GDS): '
                . $kotakPilihanGeriatri($geriatri->DEPRESI ?? null, [
                    1 => 'Normal (0–5)',
                    2 => 'Risiko depresi (6–10)',
                    3 => '≥10',
                    4 => 'Belum dapat dievaluasi',
                ]);

            $detailGeriatri[] = '5. Penapisan Inkontinensia: '
                . $kotakPilihanGeriatri($geriatri->INKONTINENSIA ?? null, [
                    0 => 'Tidak inkontinensia',
                    1 => 'Ada inkontinensia',
                ]);

            $detailGeriatri[] = '6. Risiko Tromboemboli Vena (DVT): '
                . $kotakPilihanGeriatri($geriatri->DVT ?? null, [
                    1 => 'Risiko rendah (<1)',
                    2 => 'Risiko sedang (1–2)',
                    3 => 'Risiko tinggi (>3)',
                ]);

            $detailGeriatri[] = '7. Risiko Jatuh pada Imobilisasi (Skala Norton): '
                . $kotakPilihanGeriatri($geriatri->ULKUS ?? null, [
                    1 => 'Risiko rendah (14)',
                    2 => 'Risiko sedang (12–13)',
                    3 => 'Risiko tinggi',
                ]);

            $detailGeriatri[] = '8. Penapisan Insomnia: '
                . $kotakPilihanGeriatri($geriatri->INSOMNIA ?? null, [
                    0 => 'Tidak ada',
                    1 => 'General insomnia',
                    2 => 'Initial insomnia',
                    3 => 'Middle insomnia',
                    4 => 'Late insomnia',
                ]);
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

            'RENCANA_ASUHAN_KEPERAWATAN' => $this->placeholder(
                $rencanaAsuhanKeperawatan
            ),

            'ASESMEN_GERIATRI' => $this->placeholder(
                implode("\n", $detailGeriatri)
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
