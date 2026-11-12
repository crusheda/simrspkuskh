<?php

namespace App\Http\Controllers\EMR\Form\ResumeMedis;

use App\Http\Controllers\Controller;
use App\Services\LibreOfficeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Element\TextRun;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Throwable;
use Storage;

class ResumeRanapController extends Controller
{
    protected LibreOfficeService $libreOffice;

    public function __construct(LibreOfficeService $libreOffice)
    {
        $this->libreOffice = $libreOffice;
    }

    /**
     * Nama tabel Resume Rawat Inap.
     * Jika tabel berada di database/schema tertentu,
     * sesuaikan nama tabel ini.
     */
    private const TABLE = 'simrspku_klaim.resume_rawat_inap';

    /**
     * Field formulir yang disimpan ke database.
     */
    private const FIELDS = [
        'indikasi',
        'rps',
        'rpd',
        'rpm',
        'ra',
        'pemeriksaan_fisik',
        'diagnosa_utama',
        'diagnosa_sekunder',
        'diagnosa_dokter_icd10',
        'diagnosa_perawat',
        'diagnosa_dokter_icd9',
        'terapi',
        'kondisi_keluar',
        'cara_keluar',
        'ku_td_up',
        'ku_td_down',
        'ku_nafas',
        'ku_tk',
        'ku_sn',
        'ku_nadi',
        'ku_suhu',
        'layanan_lanjutan',
        'edukasi',
    ];


    /**
     * ==========================================================
     * GET DATA RESUME MEDIS
     * ==========================================================
     *
     * GET:
     * /api/v2/emr/resume/ranap/{kunjungan}
     */
    public function getData($kunjungan)
    {
        try {

            // 1. Ambil data pendaftaran dari kunjungan utama (Rawat Inap)
            $kunjunganUtama = DB::table('pendaftaran.kunjungan as pk')
                ->join('pendaftaran.pendaftaran as pp', 'pp.NOMOR', '=', 'pk.NOPEN')
                ->join('master.dokter as dr', function($join){
                    $join->on('dr.ID', '=', 'pk.DPJP')
                        ->where('dr.STATUS', 1);
                })
                ->where('pk.NOMOR', $kunjungan)
                ->where('pk.STATUS', '!=', 0)
                ->where('pp.STATUS', '!=', 0)
                ->select(
                    'pk.NOMOR as KUNJUNGAN',
                    'pk.NOPEN',
                    'pp.NORM',
                    'pp.TANGGAL as TANGGAL_PENDAFTARAN',
                    'dr.NIP as NIP_DOKTER'
                )
                ->first();

            // 2. Cari kunjungan Poli / Rawat Darurat H-1 sampai tanggal Rawat Inap
            $kunjunganPoli = null;

            if ($kunjunganUtama) {

                $tanggalUtama = Carbon::parse($kunjunganUtama->TANGGAL_PENDAFTARAN);

                $tanggalAwal = $tanggalUtama->copy()->subDay();

                $kunjunganPoli = DB::table('pendaftaran.kunjungan as pk')
                    ->join('pendaftaran.pendaftaran as pp', 'pp.NOMOR', '=', 'pk.NOPEN')
                    ->join('master.ruangan as ru', 'ru.ID', '=', 'pk.RUANGAN')
                    ->select('pk.*')
                    ->where('pp.NORM', $kunjunganUtama->NORM)
                    ->where('pp.STATUS', '!=', 0)
                    ->where('pk.STATUS', '!=', 0)
                    ->whereIn('ru.JENIS_KUNJUNGAN', [1, 2])
                    ->whereBetween('pp.TANGGAL', [
                        $tanggalAwal->format('Y-m-d H:i:s'),
                        $tanggalUtama->format('Y-m-d H:i:s'),
                    ])
                    ->orderBy('pp.TANGGAL', 'DESC')
                    ->orderBy('pk.NOMOR', 'DESC')
                    // ->value('pk.NOMOR');
                    ->first();
            }

            /*
             * Pengambilan Data Kebutuhan Resume Medis
             */
            // $old = (object) [];
            $old = collect();

            // INDIKASI
            $perencanaan_ri = DB::table('medicalrecord.perencanaan_rawat_inap')
                        ->where('KUNJUNGAN', $kunjunganPoli->NOMOR)
                        ->where('STATUS', 1)
                        ->orderByDesc('ID')
                        ->first();

            // RPS
            $anamnesis = DB::table('medicalrecord.anamnesis')
                ->where('KUNJUNGAN', $kunjunganPoli->NOMOR)
                ->where('PENDAFTARAN', $kunjunganPoli->NOPEN)
                ->where('STATUS', 1)
                ->orderByDesc('ID')
                ->first();

            // RPD
            $rpp = DB::table('medicalrecord.rpp')
                ->where('KUNJUNGAN', $kunjunganPoli->NOMOR)
                ->where('STATUS', 1)
                ->orderByDesc('ID')
                ->first();

            // RIWAYAT PENGGUNAAN OBAT
            $rpm = DB::table('medicalrecord.riwayat_pemberian_obat as rpo')
                ->leftJoin('master.frekuensi_aturan_resep as far', function($join){
                    $join->on('rpo.FREKUENSI', '=', 'far.ID')
                        ->where('far.STATUS',1);
                })
                ->leftJoin('master.referensi as ref_rute', function($join){
                    $join->on('rpo.RUTE', '=', 'ref_rute.ID')
                        ->where('ref_rute.JENIS',217)
                        ->where('ref_rute.STATUS',1);
                })
                ->select('rpo.*', 'far.FREKUENSI as FREKUENSI_NAMA', 'far.KETERANGAN as FREKUENSI_KETERANGAN', 'ref_rute.DESKRIPSI as RUTE_NAMA')
                ->where('rpo.KUNJUNGAN', $kunjunganPoli->NOMOR)
                ->where('rpo.STATUS', 1)
                ->get();

            $riwayatPenggunaanObatText = $rpm->isNotEmpty()
                                        ? $rpm->sortBy('ID')
                                            ->pluck('OBAT')
                                            ->implode(', ')
                                        : '';

            // RIWAYAT ALERGI
            $ra = DB::table('medicalrecord.riwayat_alergi as ra')
                ->leftJoin('master.referensi as ref', function($join){
                    $join->on('ra.JENIS', '=', 'ref.ID')
                        ->where('ref.JENIS',180)
                        ->where('ref.STATUS',1);
                })
                ->join('pendaftaran.kunjungan as pk', 'ra.KUNJUNGAN', '=', 'pk.NOMOR')
                ->join('pendaftaran.pendaftaran as pp', 'pk.NOPEN', '=', 'pp.NOMOR')
                ->leftJoin('master.ruangan as ru', 'ru.ID', '=', 'pk.RUANGAN')
                ->leftJoin('aplikasi.pengguna as pe', 'pe.ID', '=', 'ra.OLEH')
                ->select('ra.*', 'ref.DESKRIPSI as JENIS_ALERGI', 'ru.DESKRIPSI as NAMA_RUANGAN', DB::raw('master.getNamaLengkapPegawai(pe.NIP) as NAMA_USER'))
                ->where('pp.NORM', $kunjunganUtama->NORM)
                ->where('ra.STATUS', 1)
                ->get();

            $riwayatAlergiText = $ra->isNotEmpty()
                                ? $ra->sortBy('JENIS')
                                    ->map(function ($item) {
                                        return "{$item->JENIS_ALERGI}: {$item->DESKRIPSI}";
                                    })
                                    ->implode("\n")
                                : '';

            // TANDA VITAL
            $tandaVital = DB::table('medicalrecord.tanda_vital as ttv')
                ->leftJoin('master.referensi as refKesadaran', function ($join) {
                    $join->on('ttv.TINGKAT_KESADARAN', '=', 'refKesadaran.SCORING')
                        ->where('refKesadaran.JENIS', 179)
                        ->where('refKesadaran.STATUS', 1);
                })
                ->select(
                    'ttv.*',
                    'refKesadaran.DESKRIPSI as DESKRIPSI_TINGKAT_KESADARAN'
                )
                ->where('ttv.KUNJUNGAN', $kunjunganPoli->NOMOR)
                ->where('ttv.STATUS', 1)
                ->orderByDesc('ttv.ID')
                ->first();

            // PEMERIKSAAN FISIK
            $pemeriksaanFisik = DB::table('medicalrecord.pemeriksaan_fisik')
                ->where('KUNJUNGAN', $kunjunganPoli->NOMOR)
                ->where('PENDAFTARAN', $kunjunganPoli->NOPEN)
                ->where('STATUS', 1)
                ->orderByDesc('ID')
                ->first();

            // FORMAT TANDA VITAL
            $ttvText = '';

            if ($tandaVital) {
                $ttv = $tandaVital;

                $frekuensiNadiCB = match ((int) $ttv->FREKUENSI_NADI_CB) {
                    1 => 'Reguler',
                    2 => 'Ireguler',
                    default => '',
                };

                $frekuensiNafasCB = match ((int) $ttv->FREKUENSI_NAFAS_CB) {
                    1 => 'Simetris',
                    2 => 'Asimetris',
                    default => '',
                };

                $jalanNafas = match ((int) $ttv->JALAN_NAFAS) {
                    1 => 'Paten',
                    2 => 'Obstruksi Parsial',
                    3 => 'Obstruksi Total',
                    default => '',
                };

                $alatBantuNafas = match ((int) $ttv->ALAT_BANTU_NAFAS) {
                    0 => 'Tidak',
                    1 => 'Ya',
                    default => '',
                };

                $kulit = match ((int) $ttv->KULIT) {
                    1 => 'Normal',
                    2 => 'Jaundice',
                    3 => 'Akral Dingin',
                    4 => 'Sianotik',
                    5 => 'Berkeringat',
                    default => '',
                };

                $pupil = match ((int) $ttv->PUPIL) {
                    1 => 'Isokor',
                    2 => 'Anisokor',
                    default => '',
                };

                $gcs = $ttv->GCS !== null
                    ? rtrim(rtrim(number_format((float) $ttv->GCS, 2, '.', ''), '0'), '.')
                    : '';

                $eye = $ttv->EYE ?? '';
                $motorik = $ttv->MOTORIK ?? '';
                $verbal = $ttv->VERBAL ?? '';

                $gcsDetail = "E/{$eye} M/{$motorik} V/{$verbal}";

                $tekananDarah = ($ttv->SISTOLIK !== null && $ttv->DISTOLIK !== null)
                    ? number_format($ttv->SISTOLIK, 0) . '/' . number_format($ttv->DISTOLIK, 0) . ' mmHg'
                    : '';

                $suhu = $ttv->SUHU !== null
                    ? "{$ttv->SUHU} °C"
                    : '';

                $frekuensiNadi = $ttv->FREKUENSI_NADI !== null
                    ? number_format($ttv->FREKUENSI_NADI) ." x/menit"
                    : '';

                if ($frekuensiNadiCB !== '') {
                    $frekuensiNadi .= " ({$frekuensiNadiCB})";
                }

                $frekuensiNafas = $ttv->FREKUENSI_NAFAS !== null
                    ? number_format($ttv->FREKUENSI_NAFAS)." x/menit"
                    : '';

                if ($frekuensiNafasCB !== '') {
                    $frekuensiNafas .= " ({$frekuensiNafasCB})";
                }

                $diameterPupil = ($ttv->DIAMETER_PUPIL_UP !== null && $ttv->DIAMETER_PUPIL_DOWN !== null)
                    ? "{$ttv->DIAMETER_PUPIL_UP}mm/{$ttv->DIAMETER_PUPIL_DOWN}mm"
                    : '';

                $rc = ($ttv->RC_UP !== null && $ttv->RC_DOWN !== null)
                    ? "{$ttv->RC_UP}/{$ttv->RC_DOWN}"
                    : '';

                $pupilDetail = trim(
                    "{$pupil}, {$diameterPupil}, RC : {$rc}",
                    ', '
                );

                $ttvLines = [
                    "Keadaan Umum : {$ttv->KEADAAN_UMUM}",
                    "Tingkat Kesadaran : {$ttv->DESKRIPSI_TINGKAT_KESADARAN}",
                    "GCS : {$gcs} ({$gcsDetail}), VAS : {$ttv->VAS}",
                    "Tekanan Darah : {$tekananDarah}",
                    "Suhu : {$suhu}",
                    "Frekuensi Nadi : {$frekuensiNadi}",
                    "Frekuensi Nafas : {$frekuensiNafas}",
                    "Jalan Nafas : {$jalanNafas}",
                    "Pupil : {$pupilDetail}",
                    "Kulit : {$kulit}",
                    "Alat Bantu Nafas : {$alatBantuNafas}",
                ];

                $ttvText = implode("\n", $ttvLines);
            }

            // FORMAT PEMERIKSAAN FISIK
            $pemeriksaanFisikText = $ttvText;

            if ($pemeriksaanFisik) {
                $fisikText = collect((array) $pemeriksaanFisik)
                    ->except([
                        'ID',
                        'KUNJUNGAN',
                        'PENDAFTARAN',
                        'TANGGAL',
                        'STATUS',
                    ])
                    ->filter(function ($value) {
                        return $value !== null && $value !== '';
                    })
                    ->map(function ($value, $key) {
                        return "{$value}";
                    })
                    ->implode("\n");

                if ($fisikText !== '') {
                    $pemeriksaanFisikText .= ($pemeriksaanFisikText !== '' ? "\n\n" : '')
                        . "PEMERIKSAAN FISIK\n"
                        . $fisikText;
                }
            }

            // GET DIAGNOSA DOKTER
            $getDiagnosa = DB::table('medicalrecord.diagnosa as diag')
                    ->select(
                        'diag.ID',
                        'diag.DIAGNOSA',
                        DB::raw("CASE WHEN diag.UTAMA = 1 THEN 'UTAMA' ELSE 'SEKUNDER' END AS UTAMA"),
                        'diag.KODE as KODE_DIAGNOSA',
                        'mrc.STR as NAMA_DIAGNOSA'
                    )
                    ->leftJoin('master.mrconso as mrc', function ($join) {
                        $join->on('diag.KODE', '=', 'mrc.CODE')
                            ->whereNotIn('mrc.TTY', ['HT', 'PS'])
                            ->where(function ($q) {

                                // ==========================================
                                // PRIORITAS ICD10_2020
                                // ==========================================
                                $q->where('mrc.SAB', 'ICD10_2020')

                                    // ==========================================
                                    // FALLBACK ICD10_1998
                                    // hanya jika ICD10_2020 tidak ada
                                    // ==========================================
                                    ->orWhere(function ($q) {
                                        $q->where('mrc.SAB', 'ICD10_1998')
                                            ->whereNotExists(function ($sub) {
                                                $sub->select(DB::raw(1))
                                                    ->from('master.mrconso as mrc2020')
                                                    ->whereColumn('mrc2020.CODE', 'diag.KODE')
                                                    ->where('mrc2020.SAB', 'ICD10_2020')
                                                    ->whereNotIn('mrc2020.TTY', ['HT', 'PS']);
                                            });
                                    });
                            });
                    })
                    ->where('diag.NOPEN', $kunjunganUtama->NOPEN)
                    ->where('diag.STATUS', 1)
                    ->get();

            // DIAGNOSA UTAMA
            $diagUtamaText = $getDiagnosa
                ->where('UTAMA', 'UTAMA')
                ->sortBy('ID')
                ->map(function ($item) {
                    $kode = trim($item->KODE_DIAGNOSA ?? '');
                    $nama = trim($item->NAMA_DIAGNOSA ?? '');

                    if ($kode === '' && $nama === '') {
                        return null;
                    }

                    return "{$nama} ({$kode})";
                })
                ->filter()
                ->implode("\n");

            // DIAGNOSA SEKUNDER
            $diagSekunderText = $getDiagnosa
                ->where('UTAMA', 'SEKUNDER')
                ->sortBy('ID')
                ->map(function ($item) {
                    $kode = trim($item->KODE_DIAGNOSA ?? '');
                    $nama = trim($item->NAMA_DIAGNOSA ?? '');

                    if ($kode === '' && $nama === '') {
                        return null;
                    }

                    return "{$nama} ({$kode})";
                })
                ->filter()
                ->implode("\n");

            // KETERANGAN ICD-10
            $ketICD10Text = $getDiagnosa
                ->sortBy('ID')
                ->map(function ($item) {
                    $diagnosa = trim($item->DIAGNOSA ?? '');

                    return $diagnosa !== ''
                        ? "{$diagnosa}"
                        : null;
                })
                ->filter()
                ->implode(", ");

            // DIAGNOSA PERAWAT
            $getDiagPerawat = DB::table('medicalrecord.prosedur as prs')
                    ->select(
                        'prs.ID',
                        'prs.TINDAKAN',
                        'prs.KODE as KODE_DIAGNOSA',
                        'mrc.STR as NAMA_DIAGNOSA'
                    )
                    ->leftJoin('master.mrconso as mrc', function ($join) {
                        $join->on('prs.KODE', '=', 'mrc.CODE')
                            ->whereNotIn('mrc.TTY', ['HT', 'PS'])
                            ->where(function ($q) {

                                // ==========================================
                                // PRIORITAS ICD10_2020
                                // ==========================================
                                $q->where('mrc.SAB', 'ICD9CM_2020')

                                    // ==========================================
                                    // FALLBACK ICD10_1998
                                    // hanya jika ICD10_2020 tidak ada
                                    // ==========================================
                                    ->orWhere(function ($q) {
                                        $q->where('mrc.SAB', 'ICD_9CM_2010')
                                            ->whereNotExists(function ($sub) {
                                                $sub->select(DB::raw(1))
                                                    ->from('master.mrconso as mrc2020')
                                                    ->whereColumn('mrc2020.CODE', 'prs.KODE')
                                                    ->where('mrc2020.SAB', 'ICD9CM_2020')
                                                    ->whereNotIn('mrc2020.TTY', ['HT', 'PS']);
                                            });
                                    });
                            });
                    })
                    ->where('prs.NOPEN', $kunjunganUtama->NOPEN)
                    ->where('prs.STATUS', 1)
                    ->get();

            // DIAGNOSA / TINDAKAN PERAWAT
            $diagnosaPerawatText = $getDiagPerawat
                ->map(function ($item) {
                    $kode = trim($item->KODE_DIAGNOSA ?? '');
                    $nama = trim($item->NAMA_DIAGNOSA ?? '');

                    if ($kode === '' && $nama === '') {
                        return null;
                    }

                    return "{$nama} ({$kode})";
                })
                ->filter()
                ->implode("\n");

            // DIAGNOSA DOKTER ICD-9
            $diagnosaDokterICD9Text = $getDiagPerawat
                ->pluck('TINDAKAN')
                ->filter(fn($item) => !empty(trim($item ?? '')))
                ->map(fn($item) => "{$item}")
                ->implode(", ");

            // TERAPI DAN TINDAKAN
                // TATALAKSANA AWAL
                    // PERENCANAAN TERAPI
                    $pt = DB::table('medicalrecord.rencana_terapi')
                        ->where('KUNJUNGAN', $kunjunganPoli->NOMOR)
                        ->where('STATUS', 1)
                        ->orderByDesc('ID')
                        ->first();
                    // HASIL LAPOR DPJP
                    $hld = DB::table('medicalrecord.hasil_lapor_dpjp')
                        ->where('KUNJUNGAN', $kunjunganPoli->NOMOR)
                        ->where('STATUS', 1)
                        ->orderByDesc('ID')
                        ->first();

                    // Ambil isi field (sesuaikan nama kolom dengan tabel)
                    $perencanaanTerapi = trim($pt->DESKRIPSI ?? '');
                    $hasilLaporDPJP = trim($hld->DESKRIPSI ?? '');

                    // ==========================================
                    // SUSUN TEXT TERAPI
                    // ==========================================
                    $terapiText = implode("\n", [
                        "• TATALAKSANA AWAL",
                        $perencanaanTerapi,
                        $hasilLaporDPJP,
                        "",
                        "• TATALAKSANA TAMBAHAN",
                        ""
                    ]);

            // KONDISI SAAT KELUAR RS & CARA KELUAR / PASIEN PULANG
            $kondisi_cara_keluar = DB::table('layanan.pasien_pulang')
                ->where('KUNJUNGAN', $kunjunganUtama->KUNJUNGAN)
                ->where('STATUS', 1)
                ->orderByDesc('ID')
                ->first();

            // KONDISI UMUM (TTV RANAP TERAKHIR SEBELUM PASIEN PULANG)
            $kondisi_umum = DB::table('medicalrecord.tanda_vital')
                ->where('KUNJUNGAN', $kunjunganUtama->KUNJUNGAN)
                ->where('STATUS', 1)
                ->orderByDesc('ID')
                ->first();
            
            // SKALA NYERI
            $nyeri = DB::table('medicalrecord.penilaian_nyeri AS pn')
                // ->join('pendaftaran.kunjungan AS pkrp', 'pkrp.NOMOR', '=', 'pn.KUNJUNGAN')
                ->leftJoin('master.referensi AS mny', function ($join) {
                    $join->on('pn.METODE', '=', 'mny.ID')->where('mny.JENIS', 71);
                })
                ->select('pn.NYERI', 'mny.DESKRIPSI AS METODE')
                ->where('pn.KUNJUNGAN', $kunjunganUtama->KUNJUNGAN)
                ->where('pn.STATUS', 1)
                // ->where('pkrp.NOPEN', $kunjunganUtama)
                ->orderByDesc('pn.ID')
                ->first();
            
            $nyeriText = '';
            if ($nyeri) {
                if ($nyeri->NYERI == 0) {
                    $nyeriText = 'Tidak Ada';
                } elseif ($nyeri->NYERI == 1) {
                    $nyeriText = 'Ada, ' . ($nyeri->METODE ?? '');
                }
            } else {
                $nyeriText = 'Tidak Ada';
            }

            // EDUKASI
            $edukasiText = DB::table('medicalrecord.edukasi_emergency')
                ->where('KUNJUNGAN', $kunjunganUtama->KUNJUNGAN)
                ->where('STATUS', 1)
                ->orderByDesc('ID')
                ->value('EDUKASI');

            /*
             * PUSH VALUE INTO $OLD
             */
            $old->put('indikasi', $perencanaan_ri->INDIKASI ?? '');
            $old->put('rps', $anamnesis->DESKRIPSI ?? '');
            $old->put('rpd', $rpp->DESKRIPSI ?? '');
            $old->put('rpm', $riwayatPenggunaanObatText);
            $old->put('ra', $riwayatAlergiText);
            $old->put('pemeriksaan_fisik', $pemeriksaanFisikText);
            $old->put('diagnosa_utama', $diagUtamaText);
            $old->put('diagnosa_sekunder', $diagSekunderText);
            $old->put('diagnosa_dokter_icd10', $ketICD10Text);
            $old->put('diagnosa_perawat', $diagnosaPerawatText);
            $old->put('diagnosa_dokter_icd9', $diagnosaDokterICD9Text);
            $old->put('terapi', $terapiText);
            $old->put('kondisi_keluar', $kondisi_cara_keluar->KEADAAN);
            $old->put('cara_keluar', $kondisi_cara_keluar->CARA);
            $old->put('ku_td_up', (int) $kondisi_umum->SISTOLIK ?? '');
            $old->put('ku_td_down', (int) $kondisi_umum->DISTOLIK ?? '');
            $old->put('ku_nafas', (int) $kondisi_umum->FREKUENSI_NAFAS ?? '');
            $old->put('ku_tk', (int) $kondisi_umum->TINGKAT_KESADARAN ?? '');
            $old->put('ku_sn', $nyeriText);
            $old->put('ku_nadi', (int) $kondisi_umum->FREKUENSI_NADI ?? '');
            $old->put('ku_suhu', $kondisi_umum->SUHU ?? '');
            $old->put('edukasi', $edukasiText ?? '');

            // $old->put('perawat', $prosedurText);
            // $old->put('ket_icd9', $ketICD9Text);

            // if (empty($old->get('indikasi'))) {
                // indikasi kosong
            // }

            // print_r($old->get('riwayat_alergi'));
            // die();

            // REFERENSI TINGKAT KESADARAN
            $refTingkatKesadaran = DB::table('master.referensi as ref')
                                ->select('ID','DESKRIPSI','SCORING')
                                ->where('ref.JENIS', 179)
                                ->where('ref.STATUS', 1)
                                ->get();

            /*
             * Push ke Referensi
             */
            $ref = [
                'tingkat_kesadaran' => $refTingkatKesadaran
            ];

            /*
             * Pengambilan Data Tahap Final Resume Medis
             */
            $record = DB::table(self::TABLE)
                ->where('kunjungan', $kunjungan)
                ->where('status', 1)
                ->orderByDesc('id')
                ->first();

            if (!$record) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Resume Medis belum tersedia.',
                    'data' => null,
                    'old' => $old,
                    'ref' => $ref,
                ]);
            }

            $data = [];

            foreach (self::FIELDS as $field) {
                $data[$field] = $record->{$field};
            }

            /*
             * Status finalisasi.
             * Disesuaikan dengan nama kolom final pada tabel.
             * status_final digunakan oleh JavaScript Blade.
             */
            $data['status_final'] = (int) $record->final;
            $data['final_at'] = $record->tgl_difinal;
            $data['final_by'] = $record->oleh;

            return response()->json([
                'success' => true,
                'message' => 'Data Resume Medis berhasil diambil.',
                'data' => $data,
                'old' => $old,
                'ref' => $ref,
            ]);

        } catch (Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data Resume Medis.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }


    /**
     * ==========================================================
     * SIMPAN DATA / FINALISASI RESUME MEDIS
     * ==========================================================
     *
     * POST:
     * /api/v2/emr/resume/ranap/{kunjungan}/simpan
     *
     * finalisasi = 0 -> Simpan biasa
     * finalisasi = 1 -> Simpan sekaligus finalisasi
     */
    public function simpanData(Request $request, $kunjungan)
    {
        /*
         * Pengguna yang melakukan penyimpanan harus terautentikasi.
         * Kolom oleh pada tabel tidak boleh NULL.
         */
        $userId = Auth::id();

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi pengguna tidak ditemukan. Silakan login kembali.',
            ], 401);
        }

        /*
         * Validasi data.
         * Aturan disesuaikan dengan tipe kolom tabel.
         */
        $validated = $request->validate([

            'indikasi' => 'nullable|string',
            'rps' => 'nullable|string',
            'rpd' => 'nullable|string',
            'rpm' => 'nullable|string',
            'ra' => 'nullable|string',
            'pemeriksaan_fisik' => 'nullable|string',

            'diagnosa_utama' => 'nullable|string',
            'diagnosa_sekunder' => 'nullable|string',
            'diagnosa_dokter_icd10' => 'nullable|string',
            'diagnosa_perawat' => 'nullable|string',
            'diagnosa_dokter_icd9' => 'nullable|string',

            'terapi' => 'nullable|string',

            'kondisi_keluar' => 'nullable|integer|in:1,2,3,4,5,6,7',
            'cara_keluar' => 'nullable|integer|in:1,2,3,4,5,6,7',

            'ku_td' => 'nullable|integer|min:0',
            'ku_nafas' => 'nullable|integer|min:0',
            'ku_tk' => 'nullable|integer|min:0',
            'ku_sn' => 'nullable|string',
            'ku_nadi' => 'nullable|integer|min:0',
            'ku_suhu' => 'nullable|numeric',

            'layanan_lanjutan' => 'nullable|integer|in:1,2,3,4,5',

            'edukasi' => 'nullable|string',

            'finalisasi' => 'nullable|boolean',

        ]);

        $finalisasi = $request->boolean('finalisasi');

        $now = Carbon::now('Asia/Jakarta');

        /*
         * Ambil hanya field yang memang ada pada tabel.
         */
        $payload = [];

        foreach (self::FIELDS as $field) {
            $payload[$field] = $validated[$field] ?? null;
        }

        try {

            $result = DB::transaction(function () use (
                $kunjungan,
                $payload,
                $finalisasi,
                $now,
                $userId
            ) {

                /*
                 * Cari data berdasarkan kunjungan.
                 * Lock data agar tidak terjadi perubahan bersamaan.
                 */
                $record = DB::table(self::TABLE)
                    ->where('kunjungan', $kunjungan)
                    ->orderByDesc('id')
                    ->lockForUpdate()
                    ->first();

                /*
                 * Jika data sudah tidak aktif.
                 */
                if ($record && (int) $record->status !== 1) {
                    return 'inactive';
                }

                /*
                 * Jika sudah final, tidak boleh disimpan ulang.
                 * Harus melalui Batal Final terlebih dahulu.
                 */
                if ($record && (int) $record->final === 1) {
                    return 'locked';
                }

                /*
                 * Data yang akan disimpan.
                 */
                $saveData = array_merge($payload, [

                    'final' => $finalisasi ? 1 : 0,

                    'tgl_difinal' => $finalisasi
                        ? $now
                        : null,

                ]);

                /*
                 * INSERT jika belum ada.
                 */
                if (!$record) {

                    $saveData['kunjungan'] = $kunjungan;
                    $saveData['tgl_dibuat'] = $now;
                    $saveData['oleh'] = $userId;
                    $saveData['status'] = 1;

                    DB::table(self::TABLE)->insert($saveData);

                } else {

                    /*
                     * UPDATE jika sudah tersedia.
                     *
                     * oleh dan tgl_dibuat tidak diubah.
                     * Keduanya tetap mencatat pembuat awal.
                     */
                    DB::table(self::TABLE)
                        ->where('id', $record->id)
                        ->update($saveData);
                }

                return 'success';
            });

            /*
             * Penanganan hasil transaksi.
             */
            if ($result === 'inactive') {

                return response()->json([
                    'success' => false,
                    'message' => 'Data Resume Medis sudah tidak aktif.',
                ], 409);
            }

            if ($result === 'locked') {

                return response()->json([
                    'success' => false,
                    'message' => 'Resume Medis sudah difinalisasi. Silakan lakukan Batal Final terlebih dahulu.',
                ], 423);
            }

            return response()->json([
                'success' => true,
                'message' => $finalisasi
                    ? 'Resume Medis berhasil difinalisasi.'
                    : 'Data Resume Medis berhasil disimpan.',

                'data' => [
                    'kunjungan' => $kunjungan,
                    'status_final' => $finalisasi ? 1 : 0,
                ],
            ]);

        } catch (Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan Resume Medis.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }


    /**
     * ==========================================================
     * BATAL FINAL RESUME MEDIS
     * ==========================================================
     *
     * POST:
     * /api/v2/emr/resume/ranap/{kunjungan}/batal-final
     */
    public function batalFinal($kunjungan)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi pengguna tidak ditemukan. Silakan login kembali.',
            ], 401);
        }

        try {

            $result = DB::transaction(function () use ($kunjungan) {

                $record = DB::table(self::TABLE)
                    ->where('kunjungan', $kunjungan)
                    ->where('status', 1)
                    ->orderByDesc('id')
                    ->lockForUpdate()
                    ->first();

                if (!$record) {
                    return 'not_found';
                }

                if ((int) $record->final !== 1) {
                    return 'not_final';
                }

                /*
                 * Batalkan finalisasi.
                 * Data isian tidak dihapus.
                 */
                DB::table(self::TABLE)
                    ->where('id', $record->id)
                    ->update([

                        'final' => 0,

                        'tgl_difinal' => null,

                    ]);

                return 'success';
            });

            if ($result === 'not_found') {

                return response()->json([
                    'success' => false,
                    'message' => 'Data Resume Medis tidak ditemukan.',
                ], 404);
            }

            if ($result === 'not_final') {

                return response()->json([
                    'success' => false,
                    'message' => 'Resume Medis belum difinalisasi.',
                ], 422);
            }

            return response()->json([
                'success' => true,
                'message' => 'Finalisasi Resume Medis berhasil dibatalkan.',
                'data' => [
                    'kunjungan' => $kunjungan,
                    'status_final' => 0,
                ],
            ]);

        } catch (Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan finalisasi Resume Medis.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * ========================================================================
     * PRINT PREVIEW RESUME MEDIS RAWAT INAP
     * ========================================================================
     *
     * Word Template:
     * public/doc/klaim/resumemedis/rawatinap.docx
     *
     * Output PDF:
     * storage/app/public/files/klaim/resumemedis/preview/{kunjungan}/
     */
    public function printPreview($kunjungan)
    {
        try {
            /*
            |--------------------------------------------------------------------------
            | 1. AMBIL DATA RESUME MEDIS
            |--------------------------------------------------------------------------
            */

            $record = DB::table(self::TABLE)
                ->where('kunjungan', $kunjungan)
                ->where('status', 1)
                ->orderByDesc('id')
                ->first();

            if (!$record) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Resume Medis Rawat Inap belum tersedia.',
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | 2. IDENTITAS PASIEN DAN DPJP KUNJUNGAN UTAMA
            |--------------------------------------------------------------------------
            */

            $kunjunganUtama = DB::table('pendaftaran.kunjungan as pk')
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
                ->join('master.dokter as dr', function ($join) {
                    $join->on('dr.ID', '=', 'pk.DPJP')
                        ->where('dr.STATUS', 1);
                })
                ->select([
                    'pk.NOMOR as KUNJUNGAN',
                    'pk.NOPEN',
                    'pk.MASUK as TGLMASUK',
                    'pk.KELUAR as TGLKELUAR',
                    'pk.DPJP',

                    'pp.NORM',
                    'pp.TANGGAL as TANGGAL_PENDAFTARAN',

                    'ps.*',

                    'ru.DESKRIPSI as NAMARUANGAN',

                    'dr.NIP as NIP_DOKTER',

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
                ->where('pk.STATUS', '!=', 0)
                ->where('pp.STATUS', '!=', 0)
                ->first();

            if (!$kunjunganUtama) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data kunjungan utama atau DPJP tidak ditemukan.',
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | 3. AMBIL TANDA TANGAN DPJP
            |--------------------------------------------------------------------------
            */

            $ttd_pegawai = DB::table(
                'simrspku_klaim.tanda_tangan_pegawai as ttp'
            )
                ->where('ttp.nip', $kunjunganUtama->NIP_DOKTER)
                ->where('ttp.status', 1)
                ->whereNull('ttp.deleted_at')
                ->inRandomOrder()
                ->first();

            $ttdDpjpPath = $ttd_pegawai->signature_path ?? null;

            /*
            |--------------------------------------------------------------------------
            | 4. MAPPING KONDISI KELUAR
            |--------------------------------------------------------------------------
            */

            $kondisiKeluar = [
                1 => 'Sembuh',
                2 => 'Belum Sembuh',
                3 => 'Membaik',
                4 => 'DOA',
                5 => 'Meninggal < 48 Jam',
                6 => 'Meninggal > 48 Jam',
                7 => 'Rujuk RS Lain',
            ];

            $caraKeluar = [
                1 => 'Diijinkan Pulang',
                2 => 'Pulang Atas Permintaan Sendiri',
                3 => 'Pindah RS',
                4 => 'Dirujuk ke Rumah Sakit Lain',
                5 => 'Dead On Arrival (DOA)',
                6 => 'Meninggal',
                7 => 'Lain-lain',
            ];

            $layananLanjutan = [
                1 => 'Poliklinik RS',
                2 => 'RS Lain',
                3 => 'Puskesmas',
                4 => 'Dokter Luar',
                5 => 'Komunitas',
            ];

            /*
            |--------------------------------------------------------------------------
            | 5. HELPER FORMAT TANGGAL
            |--------------------------------------------------------------------------
            */

            $formatTanggal = function ($tanggal) {
                if (empty($tanggal)) {
                    return '-';
                }

                try {
                    return Carbon::parse($tanggal)
                        ->timezone('Asia/Jakarta')
                        ->locale('id')
                        ->translatedFormat('d F Y');
                } catch (\Throwable $e) {
                    return (string) $tanggal;
                }
            };

            $formatTanggalJam = function ($tanggal) {
                if (empty($tanggal)) {
                    return '-';
                }

                try {
                    return Carbon::parse($tanggal)
                        ->timezone('Asia/Jakarta')
                        ->format('d-m-Y H:i') . ' WIB';
                } catch (\Throwable $e) {
                    return (string) $tanggal;
                }
            };

            /*
            |--------------------------------------------------------------------------
            | 6. TEMPLATE WORD
            |--------------------------------------------------------------------------
            */

            $templatePath = public_path(
                'doc/klaim/resumemedis/rawatinap.docx'
            );

            if (!File::exists($templatePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Template Word Resume Medis Rawat Inap tidak ditemukan.',
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | 7. OUTPUT DIRECTORY
            |--------------------------------------------------------------------------
            */

            $outputDir = storage_path(
                'app/public/files/klaim/resumemedis/preview/' . $kunjungan
            );

            if (!File::exists($outputDir)) {
                File::makeDirectory(
                    $outputDir,
                    0755,
                    true
                );
            }

            /*
            |--------------------------------------------------------------------------
            | 8. NAMA FILE
            |--------------------------------------------------------------------------
            */

            $fileName = 'resume_ranap_' . $kunjungan;

            $temporaryWord = $outputDir
                . DIRECTORY_SEPARATOR
                . $fileName
                . '.docx';

            $finalPdf = $outputDir
                . DIRECTORY_SEPARATOR
                . $fileName
                . '.pdf';

            /*
            |--------------------------------------------------------------------------
            | 9. HAPUS FILE LAMA
            |--------------------------------------------------------------------------
            */

            if (File::exists($temporaryWord)) {
                File::delete($temporaryWord);
            }

            if (File::exists($finalPdf)) {
                File::delete($finalPdf);
            }

            /*
            |--------------------------------------------------------------------------
            | 10. TEMPLATE PROCESSOR
            |--------------------------------------------------------------------------
            */

            Settings::setOutputEscapingEnabled(true);

            $templateProcessor = new TemplateProcessor($templatePath);

            /*
            |--------------------------------------------------------------------------
            | 11. DATA TEMPLATE
            |--------------------------------------------------------------------------
            */

            $data = [

                // INSTANSI
                'NAMAINST' => 'RS PKU MUHAMMADIYAH SUKOHARJO',

                'ALAMATINST' => 'Jl. Mayor Sunaryo No.37, Gawanan, Sukoharjo',

                // IDENTITAS PASIEN
                'NORM' => str_pad(
                    (string) ($kunjunganUtama->NORM ?? ''),
                    8,
                    '0',
                    STR_PAD_LEFT
                ),

                'NAMALENGKAP' => $kunjunganUtama->NAMALENGKAP ?? '',

                'JK' => (
                    $kunjunganUtama->JENIS_KELAMIN
                    ?? $kunjunganUtama->JK
                    ?? ''
                ),

                'TGLLAHIR' => $formatTanggal(
                    $kunjunganUtama->TANGGAL_LAHIR ?? null
                ),

                'UMUR' => $kunjunganUtama->UMUR ?? '',

                'ALAMAT' => $kunjunganUtama->ALAMAT ?? '',

                // KUNJUNGAN
                'KUNJUNGAN' => $kunjungan,

                'TGLMASUK' => $formatTanggalJam(
                    $kunjunganUtama->TGLMASUK
                ),

                'TGLKELUAR' => $formatTanggalJam(
                    $kunjunganUtama->TGLKELUAR
                ),

                'NAMARUANGAN' => $kunjunganUtama->NAMARUANGAN ?? '',

                // INFORMASI RAWAT INAP
                'INDIKASI' => $record->indikasi ?? '',

                // RIWAYAT PENYAKIT
                'RPS' => $record->rps ?? '',

                'RPD' => $record->rpd ?? '',

                'RPM' => $record->rpm ?? '',

                'PEMERIKSAAN_FISIK' => $record->pemeriksaan_fisik ?? '',

                // DIAGNOSIS DAN TERAPI
                'DIAGNOSA_UTAMA' => $record->diagnosa_utama ?? '',

                'DIAGNOSA_SEKUNDER' => $record->diagnosa_sekunder ?? '',

                'DIAGNOSA_PERAWAT' => $record->diagnosa_perawat ?? '',

                'TERAPI' => $record->terapi ?? '',

                // KONDISI KELUAR
                'KONDISI_KELUAR' =>
                    $kondisiKeluar[$record->kondisi_keluar] ?? '',

                'CARA_KELUAR' =>
                    $caraKeluar[$record->cara_keluar] ?? '',

                // KONDISI UMUM
                'KU_TD' => $record->ku_td !== null
                    ? $record->ku_td . ' mmHg'
                    : '',

                'KU_NAFAS' => $record->ku_nafas !== null
                    ? $record->ku_nafas . ' x/Menit'
                    : '',

                'KU_TK' => $record->ku_tk ?? '',

                'KU_SN' => $record->ku_sn ?? '',

                'KU_NADI' => $record->ku_nadi !== null
                    ? $record->ku_nadi . ' x/Menit'
                    : '',

                'KU_SUHU' => $record->ku_suhu !== null
                    ? $record->ku_suhu . ' °C'
                    : '',

                // LAYANAN LANJUTAN
                'LAYANAN_LANJUTAN' =>
                    $layananLanjutan[$record->layanan_lanjutan] ?? '',

                'EDUKASI' => $record->edukasi ?? '',

                // DPJP
                'NAMA_DPJP' => $kunjunganUtama->NAMADOKTER ?? '',

                'NIP_DPJP' => $kunjunganUtama->NIP_DOKTER ?? '',

                // TANGGAL CETAK
                'TANGGAL_CETAK' => now('Asia/Jakarta')
                    ->locale('id')
                    ->isoFormat('D MMMM YYYY [Pukul] HH:mm:ss')
                    . ' WIB',
            ];

            /*
            |--------------------------------------------------------------------------
            | 12. ISI PLACEHOLDER TEKS
            |--------------------------------------------------------------------------
            */

            foreach ($data as $key => $value) {
                $templateProcessor->setValue(
                    $key,
                    (string) ($value ?? '')
                );
            }

            /*
            |--------------------------------------------------------------------------
            | 13. MASUKKAN GAMBAR TANDA TANGAN DPJP
            |--------------------------------------------------------------------------
            */

            if (
                $ttdDpjpPath &&
                Storage::disk('public')->exists($ttdDpjpPath)
            ) {
                $imagePath = Storage::disk('public')->path(
                    $ttdDpjpPath
                );

                [$originalWidth, $originalHeight] = getimagesize(
                    $imagePath
                );

                if ($originalWidth > 0) {
                    $targetWidth = 150;

                    $targetHeight = $targetWidth
                        * ($originalHeight / $originalWidth);

                    $templateProcessor->setImageValue(
                        'TTD_DPJP',
                        [
                            'path' => $imagePath,
                            'width' => $targetWidth,
                            'height' => $targetHeight,
                        ]
                    );
                } else {
                    $templateProcessor->setValue('TTD_DPJP', '');
                }
            } else {
                // Jika TTD belum tersedia, placeholder dikosongkan.
                $templateProcessor->setValue('TTD_DPJP', '');
            }

            /*
            |--------------------------------------------------------------------------
            | 14. SAVE DOCX
            |--------------------------------------------------------------------------
            */

            $templateProcessor->saveAs($temporaryWord);

            if (!File::exists($temporaryWord)) {
                throw new \Exception(
                    'Gagal membuat file DOCX Resume Medis Rawat Inap.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | 15. CONVERT DOCX -> PDF MENGGUNAKAN LIBREOFFICE
            |--------------------------------------------------------------------------
            */

            [$success, $convertLog, $exitCode] =
                $this->libreOffice->generatePdf(
                    $temporaryWord,
                    $outputDir
                );

            clearstatcache(true, $finalPdf);

            if (
                !$success ||
                !File::exists($finalPdf) ||
                File::size($finalPdf) === 0
            ) {
                Log::error('Print Preview Resume Ranap gagal generate PDF', [
                    'kunjungan' => $kunjungan,
                    'temporaryWord' => $temporaryWord,
                    'finalPdf' => $finalPdf,
                    'exitCode' => $exitCode,
                    'convertLog' => $convertLog,
                ]);

                throw new \Exception(
                    'Gagal generate PDF Resume Medis Rawat Inap.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | 16. HAPUS DOCX TEMPORARY
            |--------------------------------------------------------------------------
            */

            if (File::exists($temporaryWord)) {
                File::delete($temporaryWord);
            }

            /*
            |--------------------------------------------------------------------------
            | 17. RETURN PDF
            |--------------------------------------------------------------------------
            */

            return response()->file($finalPdf, [
                'Content-Type' => 'application/pdf',

                'Cache-Control' =>
                    'no-store, no-cache, must-revalidate, max-age=0',

                'Pragma' => 'no-cache',

                'Expires' => '0',
            ]);

        } catch (\Throwable $e) {

            Log::error('Print Preview Resume Ranap Exception', [
                'kunjungan' => $kunjungan,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
