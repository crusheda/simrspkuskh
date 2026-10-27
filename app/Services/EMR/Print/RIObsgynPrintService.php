<?php

namespace App\Services\EMR\Print;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RIObsgynPrintService implements PrintDataInterface
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
            ->where('fin.FORM', 'pengkajian-ranap-obsgyn')
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
        | RIWAYAT OBSTETRI
        |--------------------------------------------------------------------------
        */
        $riwayatObstetri = DB::table('medicalrecord.riwayat_obstetri as ro')
            ->leftJoin('master.referensi as uk', function ($join) {
                $join->on('ro.USIA_KEHAMILAN', '=', 'uk.ID')
                    ->where('uk.JENIS', 299)
                    ->where('uk.STATUS', 1);
            })
            ->leftJoin('master.referensi as jp', function ($join) {
                $join->on('ro.JENIS_PERSALINAN', '=', 'jp.ID')
                    ->where('jp.JENIS', 300)
                    ->where('jp.STATUS', 1);
            })
            ->leftJoin('master.referensi as py', function ($join) {
                $join->on('ro.PENYULIT', '=', 'py.ID')
                    ->where('py.JENIS', 301)
                    ->where('py.STATUS', 1);
            })
            ->leftJoin('master.referensi as jk', function ($join) {
                $join->on('ro.JENIS_KELAMIN', '=', 'jk.ID')
                    ->where('jk.JENIS', 2)
                    ->where('jk.STATUS', 1);
            })
            ->leftJoin('master.referensi as pn', function ($join) {
                $join->on('ro.PENOLONG', '=', 'pn.ID')
                    ->where('pn.JENIS', 303)
                    ->where('pn.STATUS', 1);
            })
            ->leftJoin('master.referensi as tp', function ($join) {
                $join->on('ro.TEMPAT', '=', 'tp.ID')
                    ->where('tp.JENIS', 304)
                    ->where('tp.STATUS', 1);
            })
            ->leftJoin('master.referensi as ks', function ($join) {
                $join->on('ro.KEADAAN_SAAT_INI', '=', 'ks.ID')
                    ->where('ks.JENIS', 302)
                    ->where('ks.STATUS', 1);
            })
            ->where('ro.KUNJUNGAN', $kunjungan)
            ->where('ro.STATUS', 1)
            ->orderByDesc('ro.TAHUN')
            ->orderByDesc('ro.ID')
            ->get([
                'ro.ID',
                'ro.TAHUN',
                'ro.KETERANGAN_PENOLONG',
                'ro.KETERANGAN_TEMPAT',
                'ro.BERAT_BADAN',
                'uk.DESKRIPSI as USIA_KEHAMILAN_DESC',
                'jp.DESKRIPSI as JENIS_PERSALINAN_DESC',
                'py.DESKRIPSI as PENYULIT_DESC',
                'jk.DESKRIPSI as JENIS_KELAMIN_DESC',
                'pn.DESKRIPSI as PENOLONG_DESC',
                'tp.DESKRIPSI as TEMPAT_DESC',
                'ks.DESKRIPSI as KEADAAN_SAAT_INI_DESC',
            ]);

        $barisRiwayatObstetri = [];

        foreach ($riwayatObstetri as $index => $item) {
            $nomor = $index + 1;

            $penolong = $this->value($item->PENOLONG_DESC ?? null);
            $keteranganPenolong = $this->value(
                $item->KETERANGAN_PENOLONG ?? null
            );

            if ($keteranganPenolong !== '') {
                $penolong .= ($penolong !== '' ? ': ' : '') . $keteranganPenolong;
            }

            $tempat = $this->value($item->TEMPAT_DESC ?? null);
            $keteranganTempat = $this->value(
                $item->KETERANGAN_TEMPAT ?? null
            );

            if ($keteranganTempat !== '') {
                $tempat .= ($tempat !== '' ? ': ' : '') . $keteranganTempat;
            }

            $barisRiwayatObstetri[] = implode("\n", [
                $nomor . '. Riwayat Obstetri',
                'Tahun: ' . $this->value($item->TAHUN ?? null),
                'Usia kehamilan: '
                    . $this->value($item->USIA_KEHAMILAN_DESC ?? null),
                'Persalinan: '
                    . $this->value($item->JENIS_PERSALINAN_DESC ?? null),
                'Penyulit: '
                    . $this->value($item->PENYULIT_DESC ?? null),
                'Penolong: ' . $penolong,
                'Tempat: ' . $tempat,
                'Jenis kelamin: '
                    . $this->value($item->JENIS_KELAMIN_DESC ?? null),
                'Berat badan: '
                    . $this->value($item->BERAT_BADAN ?? null) . ' gram',
                'Keadaan saat ini: '
                    . $this->value($item->KEADAAN_SAAT_INI_DESC ?? null),
            ]);
        }

        $dataRiwayatObstetri = implode("\n\n", $barisRiwayatObstetri);

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
        | PEMERIKSAAN OBSGYN
        |--------------------------------------------------------------------------
        */
        $rowPemeriksaanFisikObsgyn = DB::table(
            'medicalrecord.sirmed_pemeriksaan_obsgyn'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first([
                'FISIK',
                'OBSTETRI',
                'GYNEKOLOGI',
            ]);

        $detailPemeriksaanFisikObsgyn = [];

        if ($rowPemeriksaanFisikObsgyn) {
            foreach ([
                'Pemeriksaan Fisik' => 'FISIK',
                'Pemeriksaan Obstetri' => 'OBSTETRI',
                'Pemeriksaan Gynekologi' => 'GYNEKOLOGI',
            ] as $label => $kolom) {
                $nilai = $this->value(
                    $rowPemeriksaanFisikObsgyn->{$kolom} ?? null
                );

                if ($nilai !== '') {
                    $nilai = str_replace(["\r\n", "\r"], "\n", $nilai);

                    $detailPemeriksaanFisikObsgyn[] = $label . ':';
                    $detailPemeriksaanFisikObsgyn[] = $nilai;
                    $detailPemeriksaanFisikObsgyn[] = '';
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | PEMERIKSAAN OBSGYN
        |--------------------------------------------------------------------------
        */
        $penunjangObsgyn = DB::table(
            'medicalrecord.pemeriksaan_eeg'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first([
                'HASIL',
                'KESIMPULAN',
            ]);

        $penunjangLain = DB::table(
            'medicalrecord.sirmed_pemeriksaan_penunjang_lain'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first([
                'DESKRIPSI',
            ]);

        $detailPemeriksaanObsgyn = [];

        if ($penunjangObsgyn) {
            foreach ([
                'HASIL' => 'Hasil Pemeriksaan',
                'KESIMPULAN' => 'Kesimpulan',
            ] as $kolom => $label) {
                $nilai = $this->value(
                    $penunjangObsgyn->{$kolom} ?? null
                );

                if ($nilai !== '') {
                    $detailPemeriksaanObsgyn[] = $label . ":\n" . $nilai;
                }
            }
        }
        $detailPemeriksaanObsgyn[] = "Penunjang Lain:\n" . $this->value($penunjangLain->DESKRIPSI ?? null);

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

            'RIWAYAT_OBSTETRI' => $this->placeholder(
                $dataRiwayatObstetri
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

            'PEMERIKSAAN_FISIK_OBSGYN' => $this->placeholder(
                implode("\n", $detailPemeriksaanFisikObsgyn)
            ),

            /*
            |--------------------------------------------------------------------------
            | PEMERIKSAAN PENUNJANG
            |--------------------------------------------------------------------------
            */
            'HASIL_LABORATORIUM' => $hasilLaboratorium,
            'HASIL_RADIOLOGI' => $hasilRadiologi,
            'PEMERIKSAAN_OBSGYN' => $this->placeholder(
                implode("\n", $detailPemeriksaanObsgyn)
            ),

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
            ->where('fin.FORM', 'pengkajian-ranap-obsgyn')
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
            ->where('fin.FORM', 'pengkajian-ranap-obsgyn')
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
        | RIWAYAT OBSTETRI
        |--------------------------------------------------------------------------
        */
        $riwayatObstetri = DB::table('medicalrecord.riwayat_obstetri as ro')
            ->leftJoin('master.referensi as uk', function ($join) {
                $join->on('ro.USIA_KEHAMILAN', '=', 'uk.ID')
                    ->where('uk.JENIS', 299)
                    ->where('uk.STATUS', 1);
            })
            ->leftJoin('master.referensi as jp', function ($join) {
                $join->on('ro.JENIS_PERSALINAN', '=', 'jp.ID')
                    ->where('jp.JENIS', 300)
                    ->where('jp.STATUS', 1);
            })
            ->leftJoin('master.referensi as py', function ($join) {
                $join->on('ro.PENYULIT', '=', 'py.ID')
                    ->where('py.JENIS', 301)
                    ->where('py.STATUS', 1);
            })
            ->leftJoin('master.referensi as jk', function ($join) {
                $join->on('ro.JENIS_KELAMIN', '=', 'jk.ID')
                    ->where('jk.JENIS', 2)
                    ->where('jk.STATUS', 1);
            })
            ->leftJoin('master.referensi as pn', function ($join) {
                $join->on('ro.PENOLONG', '=', 'pn.ID')
                    ->where('pn.JENIS', 303)
                    ->where('pn.STATUS', 1);
            })
            ->leftJoin('master.referensi as tp', function ($join) {
                $join->on('ro.TEMPAT', '=', 'tp.ID')
                    ->where('tp.JENIS', 304)
                    ->where('tp.STATUS', 1);
            })
            ->leftJoin('master.referensi as ks', function ($join) {
                $join->on('ro.KEADAAN_SAAT_INI', '=', 'ks.ID')
                    ->where('ks.JENIS', 302)
                    ->where('ks.STATUS', 1);
            })
            ->where('ro.KUNJUNGAN', $kunjungan)
            ->where('ro.STATUS', 1)
            ->orderByDesc('ro.TAHUN')
            ->orderByDesc('ro.ID')
            ->get([
                'ro.ID',
                'ro.TAHUN',
                'ro.KETERANGAN_PENOLONG',
                'ro.KETERANGAN_TEMPAT',
                'ro.BERAT_BADAN',
                'uk.DESKRIPSI as USIA_KEHAMILAN_DESC',
                'jp.DESKRIPSI as JENIS_PERSALINAN_DESC',
                'py.DESKRIPSI as PENYULIT_DESC',
                'jk.DESKRIPSI as JENIS_KELAMIN_DESC',
                'pn.DESKRIPSI as PENOLONG_DESC',
                'tp.DESKRIPSI as TEMPAT_DESC',
                'ks.DESKRIPSI as KEADAAN_SAAT_INI_DESC',
            ]);

        $barisRiwayatObstetri = [];

        foreach ($riwayatObstetri as $index => $item) {
            $nomor = $index + 1;

            $penolong = $this->value($item->PENOLONG_DESC ?? null);
            $keteranganPenolong = $this->value(
                $item->KETERANGAN_PENOLONG ?? null
            );

            if ($keteranganPenolong !== '') {
                $penolong .= ($penolong !== '' ? ': ' : '') . $keteranganPenolong;
            }

            $tempat = $this->value($item->TEMPAT_DESC ?? null);
            $keteranganTempat = $this->value(
                $item->KETERANGAN_TEMPAT ?? null
            );

            if ($keteranganTempat !== '') {
                $tempat .= ($tempat !== '' ? ': ' : '') . $keteranganTempat;
            }

            $barisRiwayatObstetri[] = implode("\n", [
                $nomor . '. Riwayat Obstetri',
                'Tahun: ' . $this->value($item->TAHUN ?? null),
                'Usia kehamilan: '
                    . $this->value($item->USIA_KEHAMILAN_DESC ?? null),
                'Persalinan: '
                    . $this->value($item->JENIS_PERSALINAN_DESC ?? null),
                'Penyulit: '
                    . $this->value($item->PENYULIT_DESC ?? null),
                'Penolong: ' . $penolong,
                'Tempat: ' . $tempat,
                'Jenis kelamin: '
                    . $this->value($item->JENIS_KELAMIN_DESC ?? null),
                'Berat badan: '
                    . $this->value($item->BERAT_BADAN ?? null) . ' gram',
                'Keadaan saat ini: '
                    . $this->value($item->KEADAAN_SAAT_INI_DESC ?? null),
            ]);
        }

        $dataRiwayatObstetri = implode("\n\n", $barisRiwayatObstetri);

        /*
        |--------------------------------------------------------------------------
        | RIWAYAT KB DAN MENSTRUASI
        |--------------------------------------------------------------------------
        */
        $riwayatKbMenstruasi = DB::table(
            'medicalrecord.sirmed_riwayat_kb_menstruasi'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first();

        $centangKb = static function ($nilai): string {
            return (int) ($nilai ?? 0) === 1 ? '☑' : '☐';
        };

        $detailKbMenstruasi = [];

        if ($riwayatKbMenstruasi) {
            $detailKbMenstruasi[] = 'Riwayat KB:';

            $opsiKb = [
                'KB_SUNTIK' => 'Suntik',
                'KB_IUD' => 'IUD',
                'KB_PIL' => 'Pil',
                'KB_KONDOM' => 'Kondom',
                'KB_KALENDER' => 'Kalender',
                'KB_MOW' => 'MOW',
                'KB_MOP' => 'MOP',
                'KB_IMPLAN' => 'Implan',
                'TIDAK_KB' => 'Tidak KB',
            ];

            foreach (array_chunk($opsiKb, 3, true) as $baris) {
                $pilihanPerBaris = [];

                foreach ($baris as $kolom => $label) {
                    $pilihanPerBaris[] = $centangKb(
                        $riwayatKbMenstruasi->{$kolom} ?? 0
                    ) . ' ' . $label;
                }

                $detailKbMenstruasi[] = implode('    ', $pilihanPerBaris);
            }

            $keluhanKb = $this->value(
                $riwayatKbMenstruasi->KB_KELUHAN ?? null
            );

            if ($keluhanKb !== '') {
                $detailKbMenstruasi[] = 'Keluhan KB: ' . $keluhanKb;
            }

            $teratur = (string) (
                $riwayatKbMenstruasi->MENSTRUASI_TERATUR ?? ''
            );

            $detailKbMenstruasi[] = 'Menstruasi teratur: '
                . ($teratur === '1' ? '☑ Ya' : '☐ Ya')
                . '   '
                . ($teratur === '0' ? '☑ Tidak' : '☐ Tidak');

            $keluhanMenstruasi = $this->value(
                $riwayatKbMenstruasi->MENSTRUASI_KELUHAN ?? null
            );

            if ($keluhanMenstruasi !== '') {
                $detailKbMenstruasi[] =
                    'Keluhan menstruasi: ' . $keluhanMenstruasi;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | RIWAYAT PERNIKAHAN
        |--------------------------------------------------------------------------
        */
        $riwayatNikah = DB::table('medicalrecord.sirmed_riwayat_nikah')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderBy('ID')
            ->get([
                'LAMA_NIKAH',
                'KETERANGAN',
            ]);

        $detailRiwayatNikah = [];

        foreach ($riwayatNikah as $index => $riwayat) {
            $detailRiwayatNikah[] = 'Riwayat Pernikahan ' . ($index + 1);
            $detailRiwayatNikah[] = 'Lama pernikahan: '
                . $this->value($riwayat->LAMA_NIKAH ?? null)
                . ' tahun';
            $detailRiwayatNikah[] = 'Keterangan: '
                . $this->value($riwayat->KETERANGAN ?? null);
            $detailRiwayatNikah[] = '';
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

        /*
        |--------------------------------------------------------------------------
        | PEMERIKSAAN FISIK INAP OBSGYN
        |--------------------------------------------------------------------------
        */
        $pemfisObs = DB::table('medicalrecord.sirmed_pemeriksaan_fisik_obsgyn')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first();

        $centangObs = static function ($nilai, $pilihan): string {
            return (string) ($nilai ?? '') === (string) $pilihan ? '☑' : '☐';
        };

        $detailPemfisObs = [];

        if ($pemfisObs) {
            $opsiPemfisObs = [
                'Mata' => [
                    'kolom' => 'MATA',
                    'keterangan' => 'MATA_KETERANGAN',
                    'opsi' => [
                        ['value' => 1, 'label' => 'Pandangan kabur'],
                        ['value' => 2, 'label' => 'Kelainan'],
                    ],
                ],
                'Sklera' => [
                    'kolom' => 'SKLERA',
                    'opsi' => [
                        ['value' => 2, 'label' => 'An ikterik'],
                        ['value' => 1, 'label' => 'Ikterik'],
                    ],
                ],
                'Konjungtiva' => [
                    'kolom' => 'KONJUNGTIVA',
                    'opsi' => [
                        ['value' => 2, 'label' => 'Tak anemis'],
                        ['value' => 1, 'label' => 'Anemis'],
                    ],
                ],
                'Kepala' => [
                    'kolom' => 'KEPALA',
                    'keterangan' => 'KEPALA_KETERANGAN',
                    'opsi' => [
                        ['value' => 1, 'label' => 'Normal'],
                        ['value' => 2, 'label' => 'Kelainan'],
                    ],
                ],
                'Telinga' => [
                    'kolom' => 'TELINGA',
                    'keterangan' => 'TELINGA_KETERANGAN',
                    'opsi' => [
                        ['value' => 1, 'label' => 'Normal'],
                        ['value' => 2, 'label' => 'Kelainan'],
                    ],
                ],
                'Hidung' => [
                    'kolom' => 'HIDUNG',
                    'keterangan' => 'HIDUNG_KETERANGAN',
                    'opsi' => [
                        ['value' => 1, 'label' => 'Normal'],
                        ['value' => 2, 'label' => 'Kelainan'],
                    ],
                ],
                'Tenggorokan' => [
                    'kolom' => 'TENGGOROKAN',
                    'keterangan' => 'TENGGOROKAN_KETERANGAN',
                    'opsi' => [
                        ['value' => 1, 'label' => 'Normal'],
                        ['value' => 2, 'label' => 'Kelainan'],
                    ],
                ],
                'Leher' => [
                    'kolom' => 'LEHER',
                    'keterangan' => 'LEHER_KETERANGAN',
                    'opsi' => [
                        ['value' => 1, 'label' => 'Normal'],
                        ['value' => 2, 'label' => 'Kelainan'],
                    ],
                ],
                'Dada' => [
                    'kolom' => 'DADA',
                    'keterangan' => 'DADA_KETERANGAN',
                    'opsi' => [
                        ['value' => 1, 'label' => 'Normal'],
                        ['value' => 2, 'label' => 'Kelainan'],
                    ],
                ],
                'Jantung' => [
                    'kolom' => 'JANTUNG',
                    'keterangan' => 'JANTUNG_KETERANGAN',
                    'opsi' => [
                        ['value' => 1, 'label' => 'Normal'],
                        ['value' => 2, 'label' => 'Kelainan'],
                    ],
                ],
                'Paru-paru' => [
                    'kolom' => 'PARU',
                    'keterangan' => 'PARU_KETERANGAN',
                    'opsi' => [
                        ['value' => 1, 'label' => 'Normal'],
                        ['value' => 2, 'label' => 'Kelainan'],
                    ],
                ],
                'Abdomen' => [
                    'kolom' => 'ABDOMEN',
                    'keterangan' => 'ABDOMEN_KETERANGAN',
                    'opsi' => [
                        ['value' => 1, 'label' => 'Normal'],
                        ['value' => 2, 'label' => 'Kelainan'],
                    ],
                ],
                'Anggota gerak atas' => [
                    'kolom' => 'ANGGOTA_GERAK_ATAS',
                    'opsi' => [
                        ['value' => 2, 'label' => 'Tak oedema'],
                        ['value' => 1, 'label' => 'Oedema'],
                    ],
                ],
                'Anggota gerak bawah' => [
                    'kolom' => 'ANGGOTA_GERAK_BAWAH',
                    'opsi' => [
                        ['value' => 2, 'label' => 'Tak oedema'],
                        ['value' => 1, 'label' => 'Oedema'],
                    ],
                ],
            ];

            foreach ($opsiPemfisObs as $label => $item) {
                $pilihan = [];

                foreach ($item['opsi'] as $opsi) {
                    $pilihan[] = $centangObs(
                        $pemfisObs->{$item['kolom']} ?? null,
                        $opsi['value']
                    ) . ' ' . $opsi['label'];
                }

                $detailPemfisObs[] = $label . ': ' . implode('   ', $pilihan);

                if (!empty($item['keterangan'])) {
                    $keterangan = $this->value(
                        $pemfisObs->{$item['keterangan']} ?? null
                    );

                    if ($keterangan !== '') {
                        $detailPemfisObs[] = 'Keterangan ' . strtolower($label)
                            . ': ' . $keterangan;
                    }
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | PEMERIKSAAN KHUSUS INAP OBSGYN
        |--------------------------------------------------------------------------
        */
        $pemfisKhususObs = DB::table(
            'medicalrecord.sirmed_pemeriksaan_khusus_obsgyn'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first();

        $detailPemfisKhususObs = [];

        if ($pemfisKhususObs) {
            $detailPemfisKhususObs[] = 'Dada:';

            // Mammae
            $pilihanMammae = [];
            foreach ([
                ['value' => 1, 'label' => 'Simetris'],
                ['value' => 2, 'label' => 'Asimetris'],
            ] as $opsi) {
                $pilihanMammae[] = $centangObs(
                    $pemfisKhususObs->MAMMAE ?? null,
                    $opsi['value']
                ) . ' ' . $opsi['label'];
            }
            $detailPemfisKhususObs[] = 'Mammae: ' . implode('   ', $pilihanMammae);

            // Areola
            $detailPemfisKhususObs[] = 'Areola: '
                . $centangObs($pemfisKhususObs->AREOLA ?? null, 1)
                . ' Hiperpigmentasi';

            // Puting
            $pilihanPuting = [];
            foreach ([
                ['value' => 1, 'label' => 'Menonjol'],
                ['value' => 2, 'label' => 'Tidak'],
            ] as $opsi) {
                $pilihanPuting[] = $centangObs(
                    $pemfisKhususObs->PUTING ?? null,
                    $opsi['value']
                ) . ' ' . $opsi['label'];
            }
            $detailPemfisKhususObs[] = 'Puting: ' . implode('   ', $pilihanPuting);

            foreach ([
                'KOLOSTRUM1' => 'Kolostrum 1',
                'KOLOSTRUM2' => 'Kolostrum 2',
            ] as $kolom => $label) {
                $nilai = $this->value($pemfisKhususObs->{$kolom} ?? null);

                $detailPemfisKhususObs[] = $label . ': '
                    . ($nilai === '1' ? '+' : ($nilai === '0' ? '-' : $nilai));
            }

            $keteranganKolostrum = $this->value(
                $pemfisKhususObs->KOLOSTRUM_KETERANGAN ?? null
            );

            if ($keteranganKolostrum !== '') {
                $detailPemfisKhususObs[] =
                    'Keterangan kolostrum: ' . $keteranganKolostrum;
            }

            $detailPemfisKhususObs[] = '';
            $detailPemfisKhususObs[] = 'Abdomen:';

            $opsiAbdomen = [];
            foreach ([
                'ABDOMEN_LUKA_BEKAS_OP' => 'Luka bekas OP',
                'ABDOMEN_LINEA_ALBA' => 'Linea alba',
                'ABDOMEN_LINEA_NIGRA' => 'Linea nigra',
                'ABDOMEN_STRIAE_LIVIDA' => 'Striae livida',
                'ABDOMEN_STRIAE_ALBICAN' => 'Striae albican',
            ] as $kolom => $label) {
                $opsiAbdomen[] = $centangObs(
                    $pemfisKhususObs->{$kolom} ?? null,
                    1
                ) . ' ' . $label;
            }
            $detailPemfisKhususObs[] = implode('   ', $opsiAbdomen);

            $detailPemfisKhususObs[] = '';
            $detailPemfisKhususObs[] = 'Leopold I:';

            $tfu = $this->value($pemfisKhususObs->LEOPOLD_1_TFU ?? null);
            $detailPemfisKhususObs[] = 'TFU: '
                . ($tfu !== '' ? $tfu . ' cm' : '-');

            $detailPemfisKhususObs[] = 'Leopold II: '
                . $centangObs($pemfisKhususObs->LEOPOLD_2 ?? null, 1)
                . ' Punggung kanan   '
                . $centangObs($pemfisKhususObs->LEOPOLD_2 ?? null, 2)
                . ' Punggung kiri';

            $detailPemfisKhususObs[] = 'Leopold III: '
                . $centangObs($pemfisKhususObs->LEOPOLD_3 ?? null, 1)
                . ' Kepala   '
                . $centangObs($pemfisKhususObs->LEOPOLD_3 ?? null, 2)
                . ' Bokong';

            $detailPemfisKhususObs[] = 'Leopold IV: '
                . $centangObs($pemfisKhususObs->LEOPOLD_4 ?? null, 1)
                . ' Sudah masuk PAP   '
                . $centangObs($pemfisKhususObs->LEOPOLD_4 ?? null, 2)
                . ' Belum masuk PAP';

            $djj = $this->value($pemfisKhususObs->DJJ ?? null);
            if ($djj !== '') {
                $detailPemfisKhususObs[] = 'DJJ: ' . $djj;
            }

            $pilihanKondisiDjj = [];
            foreach ([
                ['value' => 1, 'label' => 'Teratur'],
                ['value' => 2, 'label' => 'Tidak teratur'],
            ] as $opsi) {
                $pilihanKondisiDjj[] = $centangObs(
                    $pemfisKhususObs->DJJ_KONDISI ?? null,
                    $opsi['value']
                ) . ' ' . $opsi['label'];
            }
            $detailPemfisKhususObs[] =
                'Kondisi DJJ: ' . implode('   ', $pilihanKondisiDjj);

            $his = $this->value($pemfisKhususObs->HIS ?? null);
            if ($his !== '') {
                $detailPemfisKhususObs[] = 'HIS: ' . $his;
            }

            $durasiHis = $this->value($pemfisKhususObs->HIS_DURASI ?? null);
            if ($durasiHis !== '') {
                $detailPemfisKhususObs[] = 'Durasi HIS: ' . $durasiHis;
            }

            $pilihanKekuatanHis = [];
            foreach ([
                ['value' => 1, 'label' => 'Kuat'],
                ['value' => 2, 'label' => 'Sedang'],
                ['value' => 3, 'label' => 'Lemah'],
            ] as $opsi) {
                $pilihanKekuatanHis[] = $centangObs(
                    $pemfisKhususObs->HIS_KEKUATAN ?? null,
                    $opsi['value']
                ) . ' ' . $opsi['label'];
            }
            $detailPemfisKhususObs[] =
                'Kekuatan HIS: ' . implode('   ', $pilihanKekuatanHis);

            $detailPemfisKhususObs[] = '';
            $detailPemfisKhususObs[] = 'Anogenital:';

            $opsiAnogenital = [];
            foreach ([
                'ANOGENITAL_DARAH' => 'Darah',
                'ANOGENITAL_LENDIR' => 'Lendir',
                'ANOGENITAL_AIR_KETUBAN' => 'Air ketuban',
                'ANOGENITAL_LAINNYA' => 'Lainnya',
            ] as $kolom => $label) {
                $opsiAnogenital[] = $centangObs(
                    $pemfisKhususObs->{$kolom} ?? null,
                    1
                ) . ' ' . $label;
            }
            $detailPemfisKhususObs[] = implode('   ', $opsiAnogenital);

            $keteranganAnogenital = $this->value(
                $pemfisKhususObs->ANOGENITAL_LAINNYA_KETERANGAN ?? null
            );

            if ($keteranganAnogenital !== '') {
                $detailPemfisKhususObs[] =
                    'Keterangan lainnya: ' . $keteranganAnogenital;
            }

            foreach ([
                'VAGINA_TAUCHER' => 'Vagina toucher',
                'PEMERIKSAAN_LAIN_LAIN' => 'Pemeriksaan lain-lain',
            ] as $kolom => $label) {
                $nilai = $this->value($pemfisKhususObs->{$kolom} ?? null);

                if ($nilai !== '') {
                    $detailPemfisKhususObs[] = $label . ': ' . $nilai;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | PEMERIKSAAN OBSGYN
        |--------------------------------------------------------------------------
        */
        $rowPemeriksaanFisikObsgyn = DB::table(
            'medicalrecord.sirmed_pemeriksaan_obsgyn'
        )
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first([
                'FISIK',
                'OBSTETRI',
                'GYNEKOLOGI',
            ]);

        $detailPemeriksaanFisikObsgyn = [];

        if ($rowPemeriksaanFisikObsgyn) {
            foreach ([
                'Pemeriksaan Fisik' => 'FISIK',
                'Pemeriksaan Obstetri' => 'OBSTETRI',
                'Pemeriksaan Gynekologi' => 'GYNEKOLOGI',
            ] as $label => $kolom) {
                $nilai = $this->value(
                    $rowPemeriksaanFisikObsgyn->{$kolom} ?? null
                );

                if ($nilai !== '') {
                    $nilai = str_replace(["\r\n", "\r"], "\n", $nilai);

                    $detailPemeriksaanFisikObsgyn[] = $label . ':';
                    $detailPemeriksaanFisikObsgyn[] = $nilai;
                    $detailPemeriksaanFisikObsgyn[] = '';
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
        | SKRINING RISIKO JATUH SKALA MORSE
        |--------------------------------------------------------------------------
        */
        $morse = DB::table('medicalrecord.penilaian_skala_morse')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->orderByDesc('ID')
            ->first([
                'RIWAYAT_JATUH',
                'DIAGNOSIS',
                'ALAT_BANTU',
                'HEPARIN',
                'GAYA_BERJALAN',
                'KESADARAN',
            ]);

        if ($morse) {
            $centangMorse = static function ($nilai, $pilihan): string {
                return (string) ($nilai ?? '') === (string) $pilihan ? '☑' : '☐';
            };

            $pilihanMorse = [
                'Riwayat jatuh (3 bulan terakhir)' => [
                    'kolom' => 'RIWAYAT_JATUH',
                    'opsi' => [
                        ['value' => 1, 'label' => 'Tidak', 'skor' => 0],
                        ['value' => 2, 'label' => 'Ya', 'skor' => 25],
                    ],
                ],
                'Diagnosis sekunder' => [
                    'kolom' => 'DIAGNOSIS',
                    'opsi' => [
                        ['value' => 1, 'label' => 'Tidak', 'skor' => 0],
                        ['value' => 2, 'label' => 'Ya', 'skor' => 15],
                    ],
                ],
                'Menggunakan alat bantu' => [
                    'kolom' => 'ALAT_BANTU',
                    'opsi' => [
                        ['value' => 1, 'label' => 'Tidak ada / bed rest', 'skor' => 0],
                        ['value' => 2, 'label' => 'Tongkat / penopang / walker', 'skor' => 15],
                        ['value' => 3, 'label' => 'Furnitur', 'skor' => 30],
                    ],
                ],
                'Penggunaan obat yang memengaruhi keseimbangan' => [
                    'kolom' => 'HEPARIN',
                    'opsi' => [
                        ['value' => 1, 'label' => 'Tidak', 'skor' => 0],
                        ['value' => 2, 'label' => 'Ya', 'skor' => 20],
                    ],
                ],
                'Gaya berjalan' => [
                    'kolom' => 'GAYA_BERJALAN',
                    'opsi' => [
                        ['value' => 1, 'label' => 'Normal', 'skor' => 0],
                        ['value' => 2, 'label' => 'Lemah', 'skor' => 10],
                        ['value' => 3, 'label' => 'Terganggu', 'skor' => 20],
                    ],
                ],
                'Kesadaran' => [
                    'kolom' => 'KESADARAN',
                    'opsi' => [
                        ['value' => 1, 'label' => 'Baik', 'skor' => 0],
                        ['value' => 2, 'label' => 'Lupa / pelupa', 'skor' => 15],
                    ],
                ],
            ];

            $detailRisikoJatuh[] = 'Skala Morse:';

            $totalSkorMorse = 0;
            $semuaPilihanMorseTerisi = true;

            foreach ($pilihanMorse as $label => $item) {
                $nilai = $morse->{$item['kolom']} ?? null;
                $pilihan = [];

                foreach ($item['opsi'] as $opsi) {
                    $pilihan[] = $centangMorse($nilai, $opsi['value'])
                        . ' ' . $opsi['label'];

                    if ((string) ($nilai ?? '') === (string) $opsi['value']) {
                        $totalSkorMorse += $opsi['skor'];
                    }
                }

                $detailRisikoJatuh[] = $label . ': ' . implode('   ', $pilihan);

                $nilaiValid = false;
                foreach ($item['opsi'] as $opsi) {
                    if ((string) ($nilai ?? '') === (string) $opsi['value']) {
                        $nilaiValid = true;
                        break;
                    }
                }

                if (!$nilaiValid) {
                    $semuaPilihanMorseTerisi = false;
                }
            }

            if ($semuaPilihanMorseTerisi) {
                if ($totalSkorMorse >= 45) {
                    $kategoriMorse = 'Risiko Tinggi';
                } elseif ($totalSkorMorse >= 25) {
                    $kategoriMorse = 'Risiko Sedang';
                } else {
                    $kategoriMorse = 'Risiko Rendah';
                }

                $detailRisikoJatuh[] = 'Skor Morse: '
                    . $totalSkorMorse . ' (' . $kategoriMorse . ')';
            }

            $detailRisikoJatuh[] = '';
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

            'RIWAYAT_OBSTETRI' => $this->placeholder(
                $dataRiwayatObstetri
            ),

            'RIWAYAT_KB_MENSTRUASI' => $this->placeholder(
                implode("\n", $detailKbMenstruasi)
            ),

            'RIWAYAT_NIKAH' => $this->placeholder(
                implode("\n", $detailRiwayatNikah)
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

            'PEMERIKSAAN_FISIK_OBSGYN_INAP' => $this->placeholder(
                implode("\n", $detailPemfisObs)
            ),

            'PEMERIKSAAN_KHUSUS_OBSGYN_INAP' => $this->placeholder(
                implode("\n", $detailPemfisKhususObs)
            ),

            'PEMERIKSAAN_FISIK_OBSGYN' => $this->placeholder(
                implode("\n", $detailPemeriksaanFisikObsgyn)
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
