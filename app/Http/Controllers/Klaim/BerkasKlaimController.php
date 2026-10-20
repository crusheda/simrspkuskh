<?php

namespace App\Http\Controllers\Klaim;

use App\Http\Controllers\Controller;
use App\Services\LibreOfficeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;
use App\Models\simrspku_klaim\klaim_file;
use App\Models\simrspku_klaim\klaim_verifikasi;
use App\Models\simrspku_klaim\klaim_qrcode;
use App\Models\simrspku_klaim\klaim_qrcode_pegawai;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Style\Font;
use Milon\Barcode\DNS2D;
use setasign\Fpdi\Fpdi;
use PHPJasper\PHPJasper;
use Carbon\Carbon;
use Auth, Storage, Log;

class BerkasKlaimController extends Controller
{
    // ====================================================================================================================================
    // ==========================================================  GENERATOR PDF  =========================================================
    // ====================================================================================================================================
    protected LibreOfficeService $libreOffice;

    public function __construct(LibreOfficeService $libreOffice)
    {
        $this->libreOffice = $libreOffice;
    }

    // ====================================================================================================================================
    // ==========================================================  INDIVIDUAL  ============================================================
    // ====================================================================================================================================
    public function generateIndividual($kunjungan)
    {
        $docxPath = null;

        try {
            /* ================================================================
            * 0. KONFIGURASI
            * ================================================================
            * Default tetap kelas 3. Untuk mengubah sewaktu-waktu, edit
            * KLAIM_INDIVIDUAL_KELAS di .env atau config/klaim.php.
            */
            $pKelas = (int) config('klaim.individual_kelas', 3);

            if (!in_array($pKelas, [1, 2, 3], true)) {
                return response()->json([
                    'success' => false,
                    'message' => 'PKELAS harus bernilai 1, 2, atau 3.',
                ], 422);
            }

            /* ================================================================
            * 1. AMBIL NOPEN, TANGGAL REGISTRASI, DAN SEP DARI KUNJUNGAN
            * ================================================================ */
            $getSEP = DB::table('pendaftaran.kunjungan as pk')
                ->leftJoin('pendaftaran.pendaftaran as pd', 'pd.NOMOR', '=', 'pk.NOPEN')
                ->leftJoin('pendaftaran.penjamin as pj', 'pd.NOMOR', '=', 'pj.NOPEN')
                ->select([
                    'pj.NOMOR as NOSEP',
                    'pd.NOMOR as NOPEN',
                    'pd.TANGGAL as TGLREG',
                ])
                ->where('pk.NOMOR', $kunjungan)
                ->first();

            if (!$getSEP || !$getSEP->NOPEN) {
                return response()->json([
                    'success' => false,
                    'message' => 'SEP / pendaftaran tidak ditemukan. Periksa data kunjungan sekali lagi.',
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | JUDUL DOKUMEN = NOSEP
            |--------------------------------------------------------------------------
            */
            $NODOKUMEN = trim((string) ($getSEP->NOSEP ?? ''));

            if ($NODOKUMEN === '') {
                $NODOKUMEN = (string) $kunjungan;
            }

            // Pastikan aman digunakan sebagai nama file.
            $NODOKUMEN = preg_replace(
                '/[^A-Za-z0-9._-]/',
                '_',
                $NODOKUMEN
            );

            if ($NODOKUMEN === '') {
                $NODOKUMEN = (string) $kunjungan;
            }

            /* ================================================================
            * 2. QUERY HEADER INDIVIDUAL
            * ================================================================
            * Query Builder menggantikan CALL CetakLapIndividual5().
            * Fungsi skalar database (mis. getNamaLengkap/getValuePropertyJSON)
            * tetap digunakan agar hasilnya sejalan dengan laporan sebelumnya;
            * stored procedure tidak dipanggil.
            */
            $header = DB::table('master.pasien as p')
                ->join('pendaftaran.pendaftaran as pd', 'pd.NORM', '=', 'p.NORM')
                ->leftJoin('pendaftaran.penjamin as pj', 'pd.NOMOR', '=', 'pj.NOPEN')
                ->leftJoin('master.referensi as ref', function ($join) {
                    $join->on('pj.JENIS', '=', 'ref.ID')
                        ->where('ref.JENIS', '=', 10);
                })
                ->leftJoin('master.kartu_asuransi_pasien as kap', function ($join) {
                    $join->on('pd.NORM', '=', 'kap.NORM')
                        ->on('ref.ID', '=', 'kap.JENIS');
                })
                ->leftJoin('layanan.pasien_pulang as pp', function ($join) {
                    $join->on('pd.NOMOR', '=', 'pp.NOPEN')
                        ->whereIn('pp.STATUS', [1, 2]);
                })
                ->leftJoin('inacbg.map_inacbg_simrs as kl', function ($join) {
                    $join->on('pj.KELAS', '=', 'kl.SIMRS')
                        ->where('kl.JENIS', '=', 4);
                })
                ->leftJoin('inacbg.inacbg as kls', function ($join) {
                    $join->on('kl.INACBG', '=', 'kls.KODE')
                        ->where('kls.JENIS', '=', 8);
                })
                ->leftJoin('inacbg.hasil_grouping as hl', 'pd.NOMOR', '=', 'hl.NOPEN')
                ->leftJoin('inacbg.inacbg as sp', function ($join) {
                    $join->on(DB::raw("REPLACE(LEFT(hl.UNUSP, 5), '-', '')"), '=', 'sp.KODE')
                        ->where('sp.JENIS', '=', 6)
                        ->where('sp.VERSION', '=', 5);
                })
                ->leftJoin('inacbg.inacbg as sa', function ($join) {
                    $join->on(DB::raw("REPLACE(LEFT(hl.UNUSA, 5), '-', '')"), '=', 'sa.KODE')
                        ->where('sa.JENIS', '=', 1)
                        ->where('sa.VERSION', '=', 5);
                })
                ->leftJoin('inacbg.inacbg as sr', function ($join) {
                    $join->on(DB::raw("REPLACE(LEFT(hl.UNUSR, 5), '-', '')"), '=', 'sr.KODE')
                        ->where('sr.JENIS', '=', 4)
                        ->where('sr.VERSION', '=', 5);
                })
                ->leftJoin('inacbg.inacbg as si', function ($join) {
                    $join->on(DB::raw("REPLACE(LEFT(hl.UNUSI, 5), '-', '')"), '=', 'si.KODE')
                        ->where('si.JENIS', '=', 5)
                        ->where('si.VERSION', '=', 5);
                })
                ->leftJoin('inacbg.inacbg as sd', function ($join) {
                    $join->on(DB::raw("REPLACE(LEFT(hl.UNUSD, 5), '-', '')"), '=', 'sd.KODE')
                        ->where('sd.JENIS', '=', 3)
                        ->where('sd.VERSION', '=', 5);
                })
                ->leftJoin('inacbg.inacbg as sc', function ($join) {
                    $join->on(DB::raw("REPLACE(LEFT(hl.UNUSC, 5), '-', '')"), '=', 'sc.KODE')
                        ->where('sc.JENIS', '=', 1)
                        ->where('sc.VERSION', '=', 5);
                })
                ->leftJoin('inacbg.inacbg as kd', function ($join) {
                    $join->on('hl.CODECBG', '=', 'kd.KODE')
                        ->where('kd.JENIS', '=', 1)
                        ->where('kd.VERSION', '=', 5);
                })
                ->leftJoin('inacbg.grouping as gr', 'pd.NOMOR', '=', 'gr.NOPEN')
                ->leftJoin('inacbg.no_urut_berkas as nobr', 'pd.NOMOR', '=', 'nobr.NOPEN')
                ->leftJoin('aplikasi.pengguna as pg', function ($join) {
                    $join->on('hl.USER', '=', 'pg.ID')
                        ->where('pg.STATUS', '!=', 0);
                })
                ->leftJoin('master.pegawai as pw', function ($join) {
                    $join->on('pg.NIP', '=', 'pw.NIP')
                        ->where('pw.STATUS', '!=', 0);
                })
                ->leftJoin('medicalrecord.diagnosa as dgs', function ($join) {
                    $join->on('pd.NOMOR', '=', 'dgs.NOPEN')
                        ->where('dgs.UTAMA', '=', 1)
                        ->where('dgs.INACBG', '=', 1)
                        ->where('dgs.INA_GROUPER', '=', 0)
                        ->where('dgs.STATUS', '=', 1);
                })
                ->leftJoin('aplikasi.pengguna as cd', function ($join) {
                    $join->on('dgs.OLEH', '=', 'cd.ID')
                        ->where('cd.STATUS', '!=', 0);
                })
                ->leftJoin('master.pegawai as pwc', function ($join) {
                    $join->on('cd.NIP', '=', 'pwc.NIP')
                        ->where('pwc.STATUS', '!=', 0)
                        ->where('pwc.PROFESI', '=', 7);
                })
                ->leftJoin('pendaftaran.kunjungan as pk', function ($join) {
                    $join->on('pk.NOMOR', '=', 'pp.KUNJUNGAN')
                        ->whereIn('pk.STATUS', [1, 2]);
                })
                ->leftJoin('master.ruangan as rg', 'pk.RUANGAN', '=', 'rg.ID')
                ->join('pendaftaran.tujuan_pasien as tp', 'pd.NOMOR', '=', 'tp.NOPEN')
                ->leftJoin('master.ruangan as r', function ($join) {
                    $join->on('tp.RUANGAN', '=', 'r.ID')
                        ->where('r.JENIS', '=', 5);
                })
                ->leftJoin('inacbg.map_inacbg_simrs as s', function ($join) {
                    $join->on('tp.RUANGAN', '=', 's.SIMRS')
                        ->where('s.JENIS', '=', 5)
                        ->where('s.INACBG', '=', 1)
                        ->where('s.VERSION', '=', 5);
                })
                ->crossJoin('aplikasi.instansi as ai')
                ->join('master.ppk as inst', 'ai.PPK', '=', 'inst.ID')
                ->where('pd.NOMOR', $getSEP->NOPEN)
                ->select([
                    'inst.KODE as KODERS',
                    'inst.KELAS as KELASRS',
                    'inst.KEPEMILIKAN as KEPEMILIKAN',
                    'ai.PPK as PPK_INSTANSI',
                    DB::raw('UPPER(inst.NAMA) as NAMAINSTANSI'),
                    DB::raw("LPAD(p.NORM, 8, '0') as NORM"),
                    DB::raw('master.getNamaLengkap(p.NORM) as NAMALENGKAP'),
                    'p.TANGGAL_LAHIR as TANGGAL_LAHIR',
                    DB::raw("IF((UNIX_TIMESTAMP(pd.TANGGAL) - UNIX_TIMESTAMP(p.TANGGAL_LAHIR)) < 691200, 0, FLOOR(DATEDIFF(pd.TANGGAL, p.TANGGAL_LAHIR) / 365)) as UMURTAHUN"),
                    DB::raw('DATEDIFF(pd.TANGGAL, p.TANGGAL_LAHIR) as UMURHARI'),
                    DB::raw("IF(p.JENIS_KELAMIN = 1, '1 - Laki-laki', '2 - Perempuan') as JENISKELAMIN"),
                    'kap.NOMOR as NOKARTU',
                    'pd.NOMOR as NOPEN',
                    'pd.TANGGAL as TGLREG',
                    DB::raw("IF(r.JENIS_KUNJUNGAN = 3, DATE_FORMAT(pp.TANGGAL, '%d/%m/%Y'), DATE_FORMAT(pd.TANGGAL, '%d/%m/%Y')) as TGLKELUAR"),
                    DB::raw("CONCAT(DATEDIFF(IF(r.JENIS_KUNJUNGAN = 3, pp.TANGGAL, pd.TANGGAL), pd.TANGGAL) + 1, ' hari') as LOS"),
                    'ref.DESKRIPSI as CARABAYAR',
                    'pj.NOMOR as NOMORSEP',
                    DB::raw("IF(r.JENIS_KUNJUNGAN = 3, '1 - Rawat Inap', '2 - Rawat Jalan') as JENISPASIEN"),
                    DB::raw("aplikasi.getValuePropertyJSON(gr.DATA, 'cara_keluar') as CARA_KELUAR_KODE"),
                    DB::raw("IF(aplikasi.getValuePropertyJSON(gr.DATA, 'berat_lahir') = '', '-', aplikasi.getValuePropertyJSON(gr.DATA, 'berat_lahir')) as BERATLAHIR"),
                    DB::raw("IF(r.JENIS_KUNJUNGAN = 3, CONCAT(kls.KODE, ' - ', kls.DESKRIPSI), IF(r.JENIS_KUNJUNGAN != 3 AND s.INACBG = 1, 'Eksekutif', 'Reguler')) as KELASHAK"),
                    DB::raw("IF((aplikasi.getValuePropertyJSON(gr.DATA, 'adl_sub_acute') = '' OR aplikasi.getValuePropertyJSON(gr.DATA, 'adl_sub_acute') = '0'), '-', aplikasi.getValuePropertyJSON(gr.DATA, 'adl_sub_acute')) as ADLAKUT"),
                    DB::raw("IF((aplikasi.getValuePropertyJSON(gr.DATA, 'adl_chronic') = '' OR aplikasi.getValuePropertyJSON(gr.DATA, 'adl_chronic') = '0'), '-', aplikasi.getValuePropertyJSON(gr.DATA, 'adl_chronic')) as ADLKRONIK"),
                    'hl.CODECBG as INACBG',
                    'kd.DESKRIPSI as DESKRIPSIINACBG',
                    DB::raw("'-' as ALOS"),
                    'hl.TIPE as TIPE',
                    'hl.TARIFRS as BIAYARS',
                    'hl.UNUSR as UNUSR_RAW',
                    'hl.UNUSI as UNUSI_RAW',
                    'hl.UNUSP as UNUSP_RAW',
                    'hl.UNUSD as UNUSD_RAW',
                    'hl.UNUSA as UNUSA_RAW',
                    'hl.UNUSC as UNUSC_RAW',
                    'hl.TARIFSR as TARIFSR_RAW',
                    'hl.TARIFSI as TARIFSI_RAW',
                    'hl.TARIFSP as TARIFSP_RAW',
                    'hl.TARIFSD as TARIFSD_RAW',
                    'hl.TARIFSA as TARIFSA_RAW',
                    'hl.TARIFSC as TARIFSC_RAW',
                    'sr.DESKRIPSI as DESK_UNUSR',
                    'si.DESKRIPSI as DESK_UNUSI',
                    'sp.DESKRIPSI as DESK_UNUSP',
                    'sd.DESKRIPSI as DESK_UNUSD',
                    DB::raw("IF(hl.UNUSA = 'None', '-', IF(hl.UNUSA = aplikasi.getValuePropertyJSON(aplikasi.getValuePropertyJSON(hl.RESPONSE, 'sub_acute'), 'code'), aplikasi.getValuePropertyJSON(aplikasi.getValuePropertyJSON(hl.RESPONSE, 'sub_acute'), 'description'), sa.DESKRIPSI)) as DESUNUSA"),
                    DB::raw("IF(UPPER(hl.UNUSC) = 'NONE', '-', IF(hl.UNUSC = aplikasi.getValuePropertyJSON(aplikasi.getValuePropertyJSON(hl.RESPONSE, 'chronic'), 'code'), aplikasi.getValuePropertyJSON(aplikasi.getValuePropertyJSON(hl.RESPONSE, 'chronic'), 'description'), sc.DESKRIPSI)) as DESUNUSC"),
                    DB::raw("IF(hl.UNUSA = 'None', 0, hl.TARIFSA) as TARIFUNUSA"),
                    DB::raw("IF(hl.UNUSC = 'None', 0, hl.TARIFSC) as TARIFUNUSC"),
                    'hl.TARIFCBG as TARIFCBG_RAW',
                    'hl.TARIFKLS1 as TARIFKLS1_RAW',
                    'hl.TARIFKLS2 as TARIFKLS2_RAW',
                    'hl.TOTALTARIF as TOTALTARIF_RAW',
                    'hl.TARIFSP as TARIFSP_TOTAL',
                    'hl.TARIFSR as TARIFSR_TOTAL',
                    'hl.TARIFSI as TARIFSI_TOTAL',
                    'hl.TARIFSD as TARIFSD_TOTAL',
                    'hl.TARIFSA as TARIFSA_TOTAL',
                    'hl.TARIFSC as TARIFSC_TOTAL',
                    'nobr.NOMOR as NOMOR_URUT_RAW',
                    'pw.PROFESI as PROFESI_VERIFIKATOR',
                    'pw.NIP as NIP_VERIFIKATOR',
                    'pwc.NIP as NIP_CODER',
                    'r.JENIS_KUNJUNGAN as JENIS_KUNJUNGAN',
                    'r.DESKRIPSI as DESKRIPSI_RUANGAN',
                    'rg.DESKRIPSI as DESKRIPSI_RUANGAN_INAP',
                    'hl.USER as USER_GROUPING',
                ])
                ->selectRaw(
                    "IF(? = kls.KODE, hl.TARIFCBG, IF(? = 1, hl.TARIFKLS1, IF(? = 2, hl.TARIFKLS2, hl.TARIFCBG))) as TARIFINACBG",
                    [$pKelas, $pKelas, $pKelas]
                )
                ->selectRaw(
                    "IF(? = kls.KODE, hl.TOTALTARIF, IF(? = 1, (hl.TARIFKLS1 + hl.TARIFSP + hl.TARIFSR + hl.TARIFSI + hl.TARIFSD + hl.TARIFSA + hl.TARIFSC), IF(? = 2, (hl.TARIFKLS2 + hl.TARIFSP + hl.TARIFSR + hl.TARIFSI + hl.TARIFSD + hl.TARIFSA + hl.TARIFSC), hl.TOTALTARIF))) as TOTALTARIFINACBG",
                    [$pKelas, $pKelas, $pKelas]
                )
                ->selectRaw(
                    "IF(? = 1, 'TOTAL TARIF INACBG Kelas 1', IF(? = 2, 'TOTAL TARIF INACBG Kelas 2', IF(r.JENIS_KUNJUNGAN = 3, CONCAT('TOTAL TARIF INACBG ', kls.DESKRIPSI), 'TOTAL TARIF INACBG Kelas 3'))) as CATATAN",
                    [$pKelas, $pKelas]
                )
                ->first();

            if (!$header) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data laporan individual tidak ditemukan untuk NOPEN ' . $getSEP->NOPEN . '.',
                ], 404);
            }

            /* ================================================================
            * 3. DIAGNOSA UTAMA DAN SEKUNDER
            * ================================================================ */
            $diagnosaUtamaKode = DB::table('medicalrecord.diagnosa as md')
                ->where('md.NOPEN', $getSEP->NOPEN)
                ->where('md.UTAMA', 1)
                ->where('md.STATUS', 1)
                ->where('md.INA_GROUPER', 0)
                ->where('md.INACBG', 1)
                ->value('md.KODE');

            $diagnosaUtamaNama = DB::table('master.mrconso as mr')
                ->join('medicalrecord.diagnosa as md', 'mr.CODE', '=', 'md.KODE')
                ->where('md.NOPEN', $getSEP->NOPEN)
                ->where('md.UTAMA', 1)
                ->where('md.STATUS', 1)
                ->where('md.INA_GROUPER', 0)
                ->where('md.INACBG', 1)
                ->where('mr.SAB', 'ICD10_1998')
                ->whereIn('mr.TTY', ['PX', 'PT'])
                ->where('mr.VERSION', '!=', 4)
                ->select('mr.STR')
                ->value('mr.STR');

            $diagnosaSekunderRows = DB::table('master.mrconso as mr')
                ->join('medicalrecord.diagnosa as md', 'mr.CODE', '=', 'md.KODE')
                ->where('md.NOPEN', $getSEP->NOPEN)
                ->where('md.UTAMA', 2)
                ->where('md.STATUS', 1)
                ->where('md.INA_GROUPER', 0)
                ->where('md.INACBG', 1)
                ->where('mr.SAB', 'ICD10_1998')
                ->whereIn('mr.TTY', ['PX', 'PT'])
                ->where('mr.VERSION', '!=', 4)
                ->select(['md.ID as ID_DIAGNOSA', 'mr.CODE as KODE', 'mr.STR as DESKRIPSI'])
                ->orderBy('md.ID')
                ->get()
                ->unique('KODE')
                ->values();

            $kodeDiagnosaSekunder = $diagnosaSekunderRows->pluck('KODE')->implode("\n");
            $namaDiagnosaSekunder = $diagnosaSekunderRows->pluck('DESKRIPSI')->implode("\n");

            /* ================================================================
            * 4. PROSEDUR / TINDAKAN ICD-9-CM
            * ================================================================ */
            $prosedurRows = DB::table('master.mrconso as mr')
                ->join('medicalrecord.prosedur as pr', 'mr.CODE', '=', 'pr.KODE')
                ->where('pr.NOPEN', $getSEP->NOPEN)
                ->where('pr.STATUS', 1)
                ->where('pr.INA_GROUPER', 0)
                ->where('pr.INACBG', 1)
                ->where('mr.SAB', 'ICD9CM_2005')
                ->whereIn('mr.TTY', ['PX', 'PT'])
                ->select(['pr.ID as ID_PROSEDUR', 'mr.CODE as KODE', 'mr.STR as DESKRIPSI'])
                ->orderBy('pr.ID')
                ->get()
                ->unique('KODE')
                ->values();

            $kodeTindakan = $prosedurRows->pluck('KODE')->implode("\n");
            $namaTindakan = $prosedurRows->pluck('DESKRIPSI')->implode("\n");

            /* ================================================================
            * 5. NILAI TURUNAN YANG DAHULU DIHASILKAN OLEH JRXML / PROSEDUR
            * ================================================================ */
            $rujuk = DB::table('pendaftaran.rujukan_keluar')
                ->where('NOPEN', $getSEP->NOPEN)
                ->where('STATUS', 1)
                ->orderByDesc('NOMOR')
                ->value('NOMOR');

            $caraPulang = null;
            if ((int) $header->JENIS_KUNJUNGAN === 1) {
                $caraPulang = '1 - Atas Persetujuan Dokter';
            } elseif (!empty($header->CARA_KELUAR_KODE)) {
                $cara = DB::table('inacbg.inacbg')
                    ->where('KODE', $header->CARA_KELUAR_KODE)
                    ->where('JENIS', 9)
                    ->where('VERSION', 5)
                    ->select(['KODE', 'DESKRIPSI'])
                    ->first();

                if ($cara) {
                    $caraPulang = $cara->KODE . ' - ' . $cara->DESKRIPSI;
                }
            }

            if ($rujuk) {
                $caraPulang = '2 - Rujuk';
            }

            $jenisTarif = null;
            if ((int) $header->TIPE === 3) {
                $kepemilikanTarif = ((string) $header->KEPEMILIKAN === '14') ? 'S' : 'P';

                $jenisTarif = DB::table('inacbg.map_tarif as mt')
                    ->join('inacbg.inacbg as i', 'mt.JENISTARIF', '=', 'i.KODE')
                    ->where('i.JENIS', 10)
                    ->where('mt.KELASRS', $header->KELASRS)
                    ->where('mt.KEPEMILIKAN', $kepemilikanTarif)
                    ->value('i.DESKRIPSI');
            } else {
                $kodePPK = DB::table('aplikasi.instansi as ai')
                    ->join('master.ppk as ppk', 'ai.PPK', '=', 'ppk.ID')
                    ->value('ppk.KODE');

                $pemetaanKodeTarif = [
                    'RSAB' => '3174260',
                    'RSCM' => '3173014',
                    'RSD'  => '3174063',
                    'RSJP' => '3174282',
                ];
                $kodeInacbgTarif = array_search((string) $kodePPK, $pemetaanKodeTarif, true);

                if ($kodeInacbgTarif !== false) {
                    $jenisTarif = DB::table('inacbg.inacbg')
                        ->where('JENIS', 10)
                        ->where('KODE', $kodeInacbgTarif)
                        ->value('DESKRIPSI');
                }
            }

            $specialItems = [
                ['code' => $header->UNUSR_RAW, 'description' => $header->DESK_UNUSR, 'tariff' => $header->TARIFSR_RAW],
                ['code' => $header->UNUSI_RAW, 'description' => $header->DESK_UNUSI, 'tariff' => $header->TARIFSI_RAW],
                ['code' => $header->UNUSP_RAW, 'description' => $header->DESK_UNUSP, 'tariff' => $header->TARIFSP_RAW],
                ['code' => $header->UNUSD_RAW, 'description' => $header->DESK_UNUSD, 'tariff' => $header->TARIFSD_RAW],
            ];

            $specialItems = array_values(array_filter($specialItems, function ($item) {
                $value = trim((string) ($item['code'] ?? ''));
                return $value !== '' && strcasecmp($value, 'None') !== 0 && strcasecmp($value, 'null') !== 0;
            }));

            $kodeSpesial = $specialItems
                ? implode("\n", array_map(fn ($item) => ': ' . trim((string) $item['code']), $specialItems))
                : ': -';
            $deskripsiSpesial = $specialItems
                ? implode("\n", array_map(fn ($item) => trim((string) ($item['description'] ?? '-')) ?: '-', $specialItems))
                : '-';
            $tarifSpesial = $specialItems
                ? implode("\n", array_map(fn ($item) => number_format((float) ($item['tariff'] ?? 0), 2, ',', '.'), $specialItems))
                : '0,00';
            $rupiahSpesial = $specialItems
                ? implode("\n", array_fill(0, count($specialItems), 'Rp'))
                : 'Rp';

            $unusaRaw = trim((string) ($header->UNUSA_RAW ?? ''));
            $unuscRaw = trim((string) ($header->UNUSC_RAW ?? ''));
            $unusa = ($unusaRaw === '' || strcasecmp($unusaRaw, 'None') === 0) ? '-' : $unusaRaw;
            $unusc = ($unuscRaw === '' || strcasecmp($unuscRaw, 'None') === 0) ? '-' : $unuscRaw;
            $tarifUnusa = ((float) ($header->TARIFUNUSA ?? 0) == 0.0) ? '0,00' : number_format((float) $header->TARIFUNUSA, 0, ',', '.');
            $tarifUnusc = ((float) ($header->TARIFUNUSC ?? 0) == 0.0) ? '0,00' : number_format((float) $header->TARIFUNUSC, 0, ',', '.');

            $nomorUrut = ((string) $header->PPK_INSTANSI !== '1') ? '' : (string) ($header->NOMOR_URUT_RAW ?? '');
            $verifikator = ((int) ($header->PROFESI_VERIFIKATOR ?? 0) === 7 || empty($header->NIP_VERIFIKATOR))
                ? ''
                : (string) (DB::table('master.pegawai')
                    ->where('NIP', $header->NIP_VERIFIKATOR)
                    ->selectRaw('master.getNamaLengkapPegawai(NIP) as NAMA')
                    ->first()?->NAMA ?? '');
            $coder = empty($header->NIP_CODER)
                ? ''
                : (string) (DB::table('master.pegawai')
                    ->where('NIP', $header->NIP_CODER)
                    ->selectRaw('master.getNamaLengkapPegawai(NIP) as NAMA')
                    ->first()?->NAMA ?? '');
            $ruangRawat = ((int) $header->JENIS_KUNJUNGAN === 3)
                ? (string) ($header->DESKRIPSI_RUANGAN_INAP ?? '')
                : (string) ($header->DESKRIPSI_RUANGAN ?? '');

            $tanggalRegistrasi = !empty($header->TGLREG)
                ? Carbon::parse($header->TGLREG)->format('d/m/Y')
                : '';
            $tanggalLahir = !empty($header->TANGGAL_LAHIR)
                ? Carbon::parse($header->TANGGAL_LAHIR)->format('d/m/Y')
                : '';
            $tanggalKeluar = (string) ($header->TGLKELUAR ?? '');

            /* ================================================================
            * 6. SIAPKAN NILAI UNTUK TEMPLATE WORD
            * ================================================================ */
            $values = [
                'KODERS' => $header->KODERS ?? '',
                'KELASRS' => $header->KELASRS ?? '',
                'NAMAINSTANSI' => $header->NAMAINSTANSI ?? '',
                'JENISTARIF' => $jenisTarif ?: 'TARIF RS KELAS C SWASTA',
                'NORM' => $header->NORM ?? '',
                'NAMALENGKAP' => $header->NAMALENGKAP ?? '',
                'TANGGAL_LAHIR' => $tanggalLahir,
                'UMURTAHUN' => $header->UMURTAHUN ?? '0',
                'UMURHARI' => $header->UMURHARI ?? '0',
                'JENISKELAMIN' => $header->JENISKELAMIN ?? '',
                'NOKARTU' => $header->NOKARTU ?? '',
                'NOPEN' => $header->NOPEN ?? '',
                'TGLREG' => $tanggalRegistrasi,
                'TGLKELUAR' => $tanggalKeluar,
                'LOS' => $header->LOS ?? '',
                'CARABAYAR' => $header->CARABAYAR ?? '',
                'NOMORSEP' => $header->NOMORSEP ?? '',
                'JENISPASIEN' => $header->JENISPASIEN ?? '',
                'CARAPULANG' => $caraPulang ?: '-',
                'BERATLAHIR' => (trim((string) ($header->BERATLAHIR ?? '')) === '' || strtolower(trim((string) $header->BERATLAHIR)) === 'null') ? '-' : $header->BERATLAHIR,
                'KELASHAK' => $header->KELASHAK ?? '',
                'KODEDIAGNOSAUTAMA' => $diagnosaUtamaKode ?: '-',
                'DIAGNOSAUTAMA' => $diagnosaUtamaNama ?: '-',
                'KODEDIAGNOSASEKUNDER' => $kodeDiagnosaSekunder ?: '-',
                'DIAGNOSASEKUNDER' => $namaDiagnosaSekunder ?: '-',
                'KODETINDAKAN' => $kodeTindakan ?: '-',
                'TINDAKAN' => $namaTindakan ?: '-',
                'ADLAKUT' => $header->ADLAKUT ?: '-',
                'ADLKRONIK' => $header->ADLKRONIK ?: '-',
                'INACBG' => $header->INACBG ?: '-',
                'DESKRIPSIINACBG' => $header->DESKRIPSIINACBG ?: '-',
                'ALOS' => $header->ALOS ?? '-',
                'TARIFINACBG' => ($header->TARIFINACBG === null || $header->TARIFINACBG === '') ? '-' : number_format((float) $header->TARIFINACBG, 0, ',', '.'),
                'SPECIALPROSEDUR' => str_ireplace('None', '', implode('', array_map(fn ($item) => (string) ($item['code'] ?? ''), [
                    ['code' => $header->UNUSR_RAW], ['code' => $header->UNUSI_RAW], ['code' => $header->UNUSP_RAW], ['code' => $header->UNUSD_RAW], ['code' => $header->UNUSA_RAW],
                ]))),
                'UNUSA' => $unusa,
                'UNUSC' => $unusc,
                'DESUNUSA' => $header->DESUNUSA ?: '-',
                'DESUNUSC' => $header->DESUNUSC ?: '-',
                'TARIFUNUSA' => $tarifUnusa,
                'TARIFUNUSC' => $tarifUnusc,
                'KODESPESIAL' => $kodeSpesial,
                'DESKKODE' => $deskripsiSpesial,
                'TARIFKODE' => $tarifSpesial,
                'RPKODE' => $rupiahSpesial,
                'BIAYARS' => $header->BIAYARS ?? '-',
                'TOTALTARIFINACBG' => ($header->TOTALTARIFINACBG === null || $header->TOTALTARIFINACBG === '') ? '-' : number_format((float) $header->TOTALTARIFINACBG, 0, ',', '.'),
                'CATATAN' => $header->CATATAN ?? '',
                'NO_URUT' => $nomorUrut,
                'VERIFIKATOR' => $verifikator,
                'CODER' => $coder,
                'RUANG_RAWAT' => $ruangRawat,
                'RUJUK' => $rujuk ?: '',
                'GENERATED_AT' => Carbon::now('Asia/Jakarta')->format('d/m/Y H:i:s'),
            ];

            /* ================================================================
            * 7. PATH TEMPLATE DAN PATH OUTPUT
            * ================================================================ */
            $templatePath = public_path('doc/klaim/individual/cetakBerkasIndividual.docx');

            if (!File::exists($templatePath)) {
                throw new \RuntimeException('Template Word tidak ditemukan: ' . $templatePath);
            }

            $tanggalPath = !empty($getSEP->TGLREG)
                ? Carbon::parse($getSEP->TGLREG)
                : Carbon::now('Asia/Jakarta');

            $tgl = $tanggalPath->format('d');
            $bulan = $tanggalPath->format('m');
            $tahun = $tanggalPath->format('Y');
            $relativePath = "files/klaim/{$tahun}/{$bulan}/{$tgl}/{$NODOKUMEN}_individual";
            $outputDir = storage_path('app/public/' . dirname($relativePath));
            $fileName = basename($relativePath);
            $docxPath = $outputDir . DIRECTORY_SEPARATOR . $fileName . '.docx';
            $pdfPath = $outputDir . DIRECTORY_SEPARATOR . $fileName . '.pdf';

            File::ensureDirectoryExists($outputDir, 0755, true);

            /* ================================================================
            * 8. ISI TEMPLATE WORD
            * ================================================================ */
            $templateProcessor = new TemplateProcessor($templatePath);

            $logoPath = public_path('doc/klaim/individual/kemenkesbaru.jpg');
            if (File::exists($logoPath)) {
                $templateProcessor->setImageValue('LOGO_KEMENKES', [
                    'path' => $logoPath,
                    'width' => 52,
                    'height' => 57,
                    'ratio' => true,
                ]);
            } else {
                $templateProcessor->setValue('LOGO_KEMENKES', '');
            }

            $multilineKeys = [
                'KODEDIAGNOSASEKUNDER',
                'DIAGNOSASEKUNDER',
                'KODETINDAKAN',
                'TINDAKAN',
                'KODESPESIAL',
                'DESKKODE',
                'TARIFKODE',
                'RPKODE',
            ];

            foreach ($values as $key => $value) {
                if (in_array($key, $multilineKeys, true)) {
                    continue;
                }
                $templateProcessor->setValue($key, (string) ($value ?? ''));
            }

            $setMultiline = function (string $key, $value) use ($templateProcessor) {
                $text = (string) ($value ?? '');
                $lines = preg_split('/\r\n|\r|\n/', $text);
                $run = new TextRun();
                foreach ($lines as $index => $line) {
                    if ($index > 0) {
                        $run->addTextBreak();
                    }
                    $run->addText($line === '' ? ' ' : $line);
                }
                $templateProcessor->setComplexValue($key, $run);
            };

            foreach ($multilineKeys as $key) {
                $setMultiline($key, $values[$key] ?? '-');
            }

            $templateProcessor->saveAs($docxPath);

            if (!File::exists($docxPath) || File::size($docxPath) === 0) {
                throw new \RuntimeException('Gagal membuat DOCX berkas individual.');
            }

            /* ================================================================
            * 9. KONVERSI DOCX KE PDF MELALUI LIBREOFFICE
            * ================================================================ */
            if (File::exists($pdfPath)) {
                File::delete($pdfPath);
            }

            [$pdfSuccess, $convertResult, $convertExitCode] = $this->libreOffice->generatePdf($docxPath, $outputDir);

            if (!$pdfSuccess || !File::exists($pdfPath) || File::size($pdfPath) === 0) {
                $convertLog = is_array($convertResult)
                    ? implode(PHP_EOL, $convertResult)
                    : (string) $convertResult;

                throw new \RuntimeException(
                    'PDF individual gagal dibuat oleh LibreOffice. Exit code: ' . $convertExitCode
                    . ($convertLog !== '' ? '. Detail: ' . $convertLog : '')
                    . '. Target: ' . $pdfPath
                );
            }

            if (File::exists($docxPath)) {
                File::delete($docxPath);
            }

            /* ================================================================
            * 10. SIMPAN METADATA BERKAS KE simrspku_klaim.klaim_file
            * ================================================================ */
            $verify = klaim_file::where('nomor', $kunjungan)
                ->where('jenis', 4)
                ->where('status', true)
                ->first();

            if (!$verify) {
                $post = new klaim_file();
                $post->jenis = 4;
                $post->nomor = $kunjungan;
                $post->title = $fileName . '.pdf';
                $post->filename = $relativePath . '.pdf';
                $post->status = true;
                $post->user = Auth::user()?->ID ?? Auth::id();
                $post->save();
            }

            /* ================================================================
            * 11. KEMBALIKAN FILE PDF
            * ================================================================ */
            return response()->file($pdfPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $fileName . '.pdf"',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
        } catch (\Throwable $e) {
            if ($docxPath && File::exists($docxPath)) {
                File::delete($docxPath);
            }

            report($e);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // ====================================================================================================================================
    // =============================================================  SEP  ================================================================
    // ====================================================================================================================================
    public function generateSEP($kunjungan)
    {
        $docxPath = null;
        $finalPdf = null;

        try {
            /* ================================================================
            * 1. DATA SEP DARI PENDAFTARAN
            * ================================================================ */
            $getSEP = DB::table('pendaftaran.kunjungan AS pk')
                ->leftJoin('pendaftaran.pendaftaran AS pp', 'pp.NOMOR', '=', 'pk.NOPEN')
                ->leftJoin('pendaftaran.penjamin AS pj', 'pp.NOMOR', '=', 'pj.NOPEN')
                ->select('pj.NOMOR AS NOSEP')
                ->where('pk.NOMOR', $kunjungan)
                ->first();

            $NODOKUMEN = trim((string) ($getSEP->NOSEP ?? ''));

            if ($NODOKUMEN === '') {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor SEP tidak ditemukan untuk kunjungan ini.',
                ], 404);
            }

            // Amankan nomor SEP jika dipakai sebagai nama file.
            $NODOKUMEN = preg_replace('/[^A-Za-z0-9._-]/', '_', $NODOKUMEN);

            DB::beginTransaction();

            /* ================================================================
            * 2. DATA INSTANSI
            * ================================================================ */
            $namaInstansi = DB::table('aplikasi.instansi AS ai')
                ->join('master.ppk AS mp', 'ai.PPK', '=', 'mp.ID')
                ->value('mp.NAMA') ?? 'RS PKU MUHAMMADIYAH SUKOHARJO';

            /* ================================================================
            * 3. INCREMENT JUMLAH CETAK SEP
            *    Meniru proses UPDATE di stored procedure CetakSEP.
            * ================================================================ */
            DB::table('bpjs.kunjungan')
                ->where('noSEP', $getSEP->NOSEP)
                ->update([
                    'jmlCetak' => DB::raw('IFNULL(jmlCetak, 0) + 1'),
                ]);

            /* ================================================================
            * 4. QUERY SEP - PENGGANTI SELECT DI STORED PROCEDURE CetakSEP
            * ================================================================ */
            $query = DB::table('bpjs.kunjungan AS k')
                ->leftJoin('bpjs.peserta AS p', 'k.noKartu', '=', 'p.noKartu')
                ->leftJoin('simrspku_klaim.klaim_qrcode_pegawai AS qp', 'qp.nomor', '=', 'p.noKartu')
                ->leftJoin('master.ppk AS rp', 'k.ppkRujukan', '=', 'rp.BPJS')
                ->leftJoin('master.mrconso AS r', function ($join) {
                    $join->on('k.diagAwal', '=', 'r.CODE')
                        ->whereIn('r.SAB', ['ICD10_2020', 'ICD10_1998'])
                        ->whereNotIn('r.TTY', ['HT', 'PS']);
                })
                ->leftJoin('pendaftaran.penjamin AS pp', 'k.noSEP', '=', 'pp.NOMOR')
                ->leftJoin('pendaftaran.surat_rujukan_pasien AS srp', 'srp.NOMOR', '=', 'pp.NOMOR')
                ->leftJoin('pendaftaran.tujuan_pasien AS tp', 'pp.NOPEN', '=', 'tp.NOPEN')
                ->leftJoin('pendaftaran.pendaftaran AS pd', 'pd.NOMOR', '=', 'pp.NOPEN')
                ->leftJoin('master.pasien AS mp', 'pd.NORM', '=', 'mp.NORM')
                ->leftJoin('master.kontak_pasien AS hp', function ($join) {
                    $join->on('pd.NORM', '=', 'hp.NORM')->where('hp.JENIS', 3);
                })
                ->leftJoin('master.referensi AS smf', function ($join) {
                    $join->on('tp.SMF', '=', 'smf.ID')->where('smf.JENIS', 26);
                })
                ->leftJoin('master.ruangan AS mr', function ($join) {
                    $join->on('tp.RUANGAN', '=', 'mr.ID')->where('mr.JENIS', 5);
                })
                ->leftJoin('master.referensi AS penj', function ($join) {
                    $join->on('k.penjamin', '=', 'penj.ID')->where('penj.JENIS', 80);
                })
                ->leftJoin('bpjs.poli AS pl', 'k.poliTujuan', '=', 'pl.kode')
                ->leftJoin('bpjs.dpjp AS dr', 'k.dpjpSKDP', '=', 'dr.kode')
                ->leftJoin('bpjs.dpjp AS drl', 'k.dpjpLayan', '=', 'drl.kode')
                ->leftJoin('bpjs.referensi AS tjk', function ($join) {
                    $join->on('k.tujuanKunj', '=', 'tjk.kode')->where('tjk.jenis_referensi_id', 7);
                })
                ->leftJoin('bpjs.referensi AS proc', function ($join) {
                    $join->on('k.flagProcedure', '=', 'proc.kode')->where('proc.jenis_referensi_id', 8);
                })
                ->leftJoin('bpjs.referensi AS kdp', function ($join) {
                    $join->on('k.kdPenunjang', '=', 'kdp.kode')->where('kdp.jenis_referensi_id', 9);
                })
                ->leftJoin('bpjs.referensi AS assp', function ($join) {
                    $join->on('k.assesmentPel', '=', 'assp.kode')->where('assp.jenis_referensi_id', 10);
                })
                ->leftJoin('bpjs.referensi AS klsr', function ($join) {
                    $join->on('k.klsRawat', '=', 'klsr.kode')->where('klsr.jenis_referensi_id', 3);
                })
                ->leftJoin('bpjs.referensi AS klsn', function ($join) {
                    $join->on('k.klsRawatNaik', '=', 'klsn.kode')->where('klsn.jenis_referensi_id', 11);
                })
                ->leftJoin('penjamin_rs.dpjp AS drs', function ($join) {
                    $join->on('drs.DPJP_PENJAMIN', '=', 'k.dpjpSKDP')
                        ->where('drs.PENJAMIN', 2)
                        ->where('drs.STATUS', 1);
                })
                ->leftJoin('master.dokter AS md', function ($join) {
                    $join->on('drs.DPJP_RS', '=', 'md.ID')->where('md.STATUS', 1);
                })
                ->leftJoin('master.dokter_smf AS mds', function ($join) {
                    $join->on('md.ID', '=', 'mds.DOKTER')->where('mds.STATUS', 1);
                })
                ->leftJoin('master.penjamin_sub_spesialistik AS pss', function ($join) {
                    $join->on('pss.SUB_SPESIALIS_RS', '=', 'mds.SMF')->where('pss.PENJAMIN', 2);
                })
                ->leftJoin('bpjs.poli AS pls', 'pss.SUB_SPESIALIS_PENJAMIN', '=', 'pls.kode')
                ->leftJoin('bpjs.poli AS bpnr', 'srp.BAGIAN_DOKTER', '=', 'bpnr.nama')
                ->leftJoin('bpjs.rujukan_masuk AS rjm', 'k.noRujukan', '=', 'rjm.noKunjungan')
                ->leftJoin('regonline.reservasi AS rr', function ($join) {
                    $join->on('k.noKartu', '=', 'rr.NO_KARTU_BPJS')
                        ->on(DB::raw("DATE_FORMAT(k.tglSEP, '%y-%m-%d')"), '=', 'rr.TANGGALKUNJUNGAN')
                        ->where('rr.STATUS', '!=', 0);
                })
                ->selectRaw('? AS NAMAINSTANSI', [$namaInstansi])
                ->selectRaw("
                    k.noSEP AS NOMORSEP,
                    DATE_FORMAT(k.tglSEP, '%d-%m-%Y') AS TGLSEP,
                    k.tglSEP AS TGLSEP_RAW,
                    IF(
                        k.klsRawatNaik = 0,
                        CONVERT(klsr.deskripsi USING utf8mb4) COLLATE utf8mb4_unicode_ci,
                        CONVERT(klsn.deskripsi USING utf8mb4) COLLATE utf8mb4_unicode_ci
                    ) AS klsRawat,
                    p.noKartu AS NOMORKARTU,
                    pd.NORM AS NORM,
                    CASE
                        WHEN CONVERT(k.poliTujuan USING utf8mb4) COLLATE utf8mb4_unicode_ci = 'HDL'
                            OR CONVERT(k.poliTujuan USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE '%HEMO%'
                        THEN 'HEMODIALISA'
                        WHEN k.poliTujuan IS NULL OR k.poliTujuan = ''
                        THEN CONVERT(pls.nama USING utf8mb4) COLLATE utf8mb4_unicode_ci
                        ELSE CONVERT(pl.nama USING utf8mb4) COLLATE utf8mb4_unicode_ci
                    END AS poliTujuan,
                    mr.DESKRIPSI AS UNITPELAYANAN,
                    IF(
                        IFNULL(k.jmlCetak, 0) > 0,
                        CONCAT('*Cetakan Ke ', k.jmlCetak, ' ', DATE_FORMAT(k.tglSEP, '%d-%m-%Y %H:%i:%s')),
                        ''
                    ) AS CETAKAN,
                    k.catatan AS CATATAN,
                    p.nmJenisPeserta AS PESERTA,
                    p.nama AS NAMALENGKAP,
                    CONCAT(
                        DATE_FORMAT(p.tglLahir, '%d-%m-%Y'),
                        _utf8mb4'  Kelamin : ' COLLATE utf8mb4_unicode_ci,
                        CASE
                            WHEN CONVERT(p.sex USING utf8mb4) COLLATE utf8mb4_unicode_ci = 'L'
                            THEN _utf8mb4'Laki-laki' COLLATE utf8mb4_unicode_ci
                            ELSE _utf8mb4'Perempuan' COLLATE utf8mb4_unicode_ci
                        END
                    ) AS TGL_LAHIR,
                    IF(
                        k.jenisPelayanan = 1,
                        _utf8mb4'Rawat Inap' COLLATE utf8mb4_unicode_ci,
                        _utf8mb4'Rawat Jalan' COLLATE utf8mb4_unicode_ci
                    ) AS JENISRAWAT,
                    IF(
                        k.tujuanKunj = 0 AND k.assesmentPel IN ('1','2','3','4'),
                        _utf8mb4'Rujukan Internal' COLLATE utf8mb4_unicode_ci,
                        CONVERT(tjk.deskripsi USING utf8mb4) COLLATE utf8mb4_unicode_ci
                    ) AS TJKUNJ,
                    proc.deskripsi AS PROC,
                    kdp.deskripsi AS PENUNJANG,
                    assp.deskripsi AS ASPEL,
                    IF(
                        CONVERT(p.sex USING utf8mb4) COLLATE utf8mb4_unicode_ci = _utf8mb4'L' COLLATE utf8mb4_unicode_ci,
                        _utf8mb4'Laki-laki' COLLATE utf8mb4_unicode_ci,
                        _utf8mb4'Perempuan' COLLATE utf8mb4_unicode_ci
                    ) AS JENISKELAMIN,
                    p.nmKelas AS KELAS,
                    CONVERT(
                        IF(
                            r.CODE IS NULL,
                            IFNULL((
                                SELECT CONCAT(ro.CODE, ' (', ro.STR, ')')
                                FROM master.mrconso AS ro
                                WHERE ro.STR LIKE CONVERT(k.diagAwal USING utf8mb3)
                                AND ro.SAB IN ('ICD10_2020','ICD10_1998')
                                AND ro.TTY NOT IN ('HT','PS')
                                LIMIT 1
                            ), CONVERT(k.diagAwal USING utf8mb3)),
                            CONCAT(r.CODE, ' (', r.STR, ')')
                        ) USING utf8mb4
                    ) COLLATE utf8mb4_unicode_ci AS DIAGNOSA,
                    IF(
                        k.ppkRujukan IS NULL,
                        CONVERT(
                            IFNULL(
                                (
                                    SELECT masp.NAMA
                                    FROM bpjs.kunjungan AS bk
                                    LEFT JOIN master.ppk AS masp
                                        ON masp.BPJS = bk.ppkRujukan
                                    WHERE bk.noRujukan = k.noRujukan
                                    AND bk.ppkRujukan IS NOT NULL
                                    LIMIT 1
                                ),
                                'RS PKU MUHAMMADIYAH SUKOHARJO'
                            ) USING utf8mb4
                        ) COLLATE utf8mb4_unicode_ci,
                        CONVERT(rp.NAMA USING utf8mb4) COLLATE utf8mb4_unicode_ci
                    ) AS RUJUKAN,
                    p.prolanisPRB AS PRB,
                    penj.DESKRIPSI AS PENJAMIN,
                    IF(
                        k.cob = 0,
                        _utf8mb4'-' COLLATE utf8mb4_unicode_ci,
                        _utf8mb4'Ya' COLLATE utf8mb4_unicode_ci
                    ) AS COB,
                    hp.NOMOR AS NOTELP,
                    IF(
                        k.katarak = 1,
                        _utf8mb4'* PASIEN OPERASI KATARAK' COLLATE utf8mb4_unicode_ci,
                        _utf8mb4'' COLLATE utf8mb4_unicode_ci
                    ) AS KATARAK,
                    IF(
                        dr.kode IS NULL AND drl.kode IS NULL,
                        IFNULL((
                            SELECT CONVERT(dp.nama USING utf8mb4) COLLATE utf8mb4_unicode_ci
                            FROM bpjs.kunjungan AS bk
                            LEFT JOIN bpjs.dpjp AS dp ON dp.kode = bk.dpjpSKDP
                            WHERE bk.noRujukan = k.noRujukan
                            AND bk.dpjpSKDP IS NOT NULL
                            AND bk.noSEP = k.noSEP
                            LIMIT 1
                        ), IFNULL((
                            SELECT CONVERT(master.getNamaLengkapPegawai(dokt.NIP) USING utf8mb4) COLLATE utf8mb4_unicode_ci
                            FROM pendaftaran.kunjungan AS kj
                            LEFT JOIN master.dokter AS dokt ON dokt.ID = kj.DPJP
                            WHERE kj.NOPEN = pd.NOMOR AND kj.REF IS NULL
                            LIMIT 1
                        ), IFNULL((
                            SELECT CONVERT(dpl.nama USING utf8mb4) COLLATE utf8mb4_unicode_ci
                            FROM pendaftaran.penjamin AS pjst
                            LEFT JOIN bpjs.dpjp AS dpl ON dpl.kode = pjst.DPJP_LAYANAN
                            WHERE pjst.NOMOR = k.noSEP
                            AND pjst.DPJP_LAYANAN IS NOT NULL
                            LIMIT 1
                        ), '-'))),
                        IF(
                            dr.kode = drl.kode,
                            CONVERT(dr.nama USING utf8mb4) COLLATE utf8mb4_unicode_ci,
                            CONVERT(drl.nama USING utf8mb4) COLLATE utf8mb4_unicode_ci
                        )
                    ) AS DOKTER,
                    IF(
                        k.tujuanKunj = 0 AND k.assesmentPel IN ('1','2','3','4'),
                        rjm.poliRujukan->>'$.nama',
                        NULL
                    ) AS POLIPERUJUK,
                    IF(
                        CONVERT(k.noRujukan USING utf8mb4) COLLATE utf8mb4_unicode_ci LIKE _utf8mb4'%V%' COLLATE utf8mb4_unicode_ci,
                        CONVERT(pp.NOMOR USING utf8mb4) COLLATE utf8mb4_unicode_ci,
                        CONVERT(IFNULL(k.noRujukan, '') USING utf8mb4) COLLATE utf8mb4_unicode_ci
                    ) AS NORJK,
                    IF(
                        k.noRujukan LIKE '%V%',
                        DATE_FORMAT(p.TANGGAL, '%d-%m-%Y'),
                        IFNULL(DATE_FORMAT(rjm.tglKunjungan, '%d-%m-%Y'), '')
                    ) AS TGLRJK,
                    IF(
                        k.noRujukan LIKE '%V%',
                        'Digunakan 1 kali kunjungan Pasca Rawat Inap',
                        DATE_FORMAT(DATE_ADD(IFNULL(k.tglRujukan, srp.TANGGAL), INTERVAL 89 DAY), '%d-%m-%Y')
                    ) AS MASABERLAKU,
                    rjm.poliRujukan->>'$.kode' AS kode,
                    rjm.poliRujukan->>'$.nama' AS nama,
                    IF(
                        mr.JENIS_KUNJUNGAN != 3,
                        CONVERT(rjm.poliRujukan->>'$.nama' USING utf8mb4) COLLATE utf8mb4_unicode_ci,
                        CONVERT(smf.DESKRIPSI USING utf8mb4) COLLATE utf8mb4_unicode_ci
                    ) AS TUJUANRUJUK,
                    IFNULL(CONCAT(rr.POS_ANTRIAN, '-', rr.`NO`), 'TIDAK ADA ANTRIAN') AS ANTRIAN_ADMISI,
                    IFNULL(rr.ANTRIAN_POLI, 'TIDAK ADA ANTRIAN') AS ANTRIAN_POLI,
                    IFNULL(rr.NOMOR_ANTRIAN, 'TIDAK ADA ANTRIAN') AS ANTRIAN_DOKTER,
                    IFNULL(master.getAlamatPasien(p.NORM), mp.ALAMAT) AS ALAMATPASIEN,
                    qp.filename AS QR1,
                    IFNULL(k.jmlCetak, 0) AS JMLCETAK
                ")
                ->where('k.cetak', 0)
                ->where('k.noSEP', $getSEP->NOSEP)
                ->groupBy('k.noSEP');

            $show = $query->first();

            if (!$show) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Data SEP tidak ditemukan atau status cetaknya bukan 0.',
                ], 404);
            }

            /* ================================================================
            * 5. TANGGAL DAN PATH OUTPUT
            * ================================================================ */
            $tglSEP = Carbon::parse($show->TGLSEP_RAW);
            $tgl = $tglSEP->format('d');
            $bulan = $tglSEP->format('m');
            $tahun = $tglSEP->format('Y');

            $fileName = $NODOKUMEN . '_sep';
            $relativePath = "files/klaim/{$tahun}/{$bulan}/{$tgl}/{$fileName}";
            $output = storage_path('app/public/' . $relativePath);
            $outputDir = dirname($output);
            $finalPdf = $output . '.pdf';
            $docxPath = $output . '.docx';

            if (!File::exists($outputDir)) {
                File::makeDirectory($outputDir, 0755, true);
            }

            if (File::exists($docxPath)) {
                File::delete($docxPath);
            }
            if (File::exists($finalPdf)) {
                File::delete($finalPdf);
            }

            /* ================================================================
            * 6. SIAPKAN FILE QR CODE NOMOR KARTU
            * ================================================================ */
            $nomorKartu = trim((string) ($show->NOMORKARTU ?? ''));
            if ($nomorKartu === '') {
                throw new \RuntimeException('Nomor kartu BPJS tidak ditemukan.');
            }

            $qrRecord = DB::table('simrspku_klaim.klaim_qrcode_pegawai')
                ->where('nomor', $nomorKartu)
                ->first();

            $qrRelativePath = $qrRecord->filename ?? null;
            $qrFileExists = $qrRelativePath
                && Storage::disk('public')->exists($qrRelativePath);

            if (!$qrFileExists) {
                $qrImage = (new \Milon\Barcode\DNS2D())->getBarcodePNG($nomorKartu, 'QRCODE');
                $qrBinary = base64_decode($qrImage, true);

                if ($qrBinary === false) {
                    throw new \RuntimeException('Gagal membuat gambar QR Code nomor kartu.');
                }

                // Nama file hash aman untuk path, token tetap disimpan terenkripsi.
                $qrRelativePath = $qrRelativePath ?: ('files/qrcode/' . hash('sha256', $nomorKartu) . '.png');
                $qrAbsolutePath = Storage::disk('public')->path($qrRelativePath);
                $qrDirectory = dirname($qrAbsolutePath);

                if (!File::exists($qrDirectory)) {
                    File::makeDirectory($qrDirectory, 0755, true);
                }

                if (file_put_contents($qrAbsolutePath, $qrBinary) === false) {
                    throw new \RuntimeException('Gagal menyimpan gambar QR Code nomor kartu.');
                }

                $qrData = [
                    'token' => Crypt::encrypt($nomorKartu),
                    'nomor' => $nomorKartu,
                    'title' => $qrRecord->title ?? basename($qrRelativePath),
                    'filename' => $qrRelativePath,
                    'updated_at' => now(),
                ];

                if (!$qrRecord) {
                    $qrData['created_at'] = now();
                }

                if ($qrRecord) {
                    DB::table('simrspku_klaim.klaim_qrcode_pegawai')
                        ->where('nomor', $nomorKartu)
                        ->update($qrData);
                } else {
                    DB::table('simrspku_klaim.klaim_qrcode_pegawai')->insert($qrData);
                }
            }

            $qrAbsolutePath = Storage::disk('public')->path($qrRelativePath);

            /* ================================================================
            * 7. ISI TEMPLATE DOCX SEP
            *    Simpan template ke public/doc/klaim/sep/cetakBerkasSEP.docx
            * ================================================================ */
            $templatePath = public_path('doc/klaim/sep/cetakBerkasSEP.docx');
            if (!File::exists($templatePath)) {
                throw new \RuntimeException('Template DOCX SEP tidak ditemukan: ' . $templatePath);
            }

            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

            $display = static function ($value, $fallback = '-') {
                if ($value === null || trim((string) $value) === '') {
                    return $fallback;
                }
                return trim((string) $value);
            };

            $jenisRawat = $display($show->JENISRAWAT, '');
            $faskesRujukan = $jenisRawat === 'Rawat Jalan'
                ? $display($show->RUJUKAN)
                : 'RS PKU MUHAMMADIYAH SUKOHARJO';

            $kelasRawat = $jenisRawat === 'Rawat Jalan'
                ? '-'
                : $display($show->klsRawat, $display($show->KELAS));

            $templateValues = [
                'NAMAINSTANSI' => $display($show->NAMAINSTANSI),
                'PRB' => $display($show->PRB, ''),
                'NOMORSEP' => $display($show->NOMORSEP),
                'TGLSEP' => $display($show->TGLSEP),
                'NOMORKARTU_NORM' => $display($show->NOMORKARTU) . ' ( MR. ' . $display($show->NORM) . ' )',
                'NAMALENGKAP' => $display($show->NAMALENGKAP),
                'TGL_LAHIR' => $display($show->TGL_LAHIR),
                'NOTELP' => $display($show->NOTELP, ''),
                'POLITUJUAN' => $display($show->poliTujuan),
                'DOKTER' => $display($show->DOKTER),
                'FASKESRUJUKAN' => $faskesRujukan,
                'DIAGNOSA' => $display($show->DIAGNOSA),
                'CATATAN' => $display($show->CATATAN, ''),
                'KATARAK' => $display($show->KATARAK, ''),
                'PESERTA' => $display($show->PESERTA),
                'COB' => $display($show->COB, '-'),
                'JENISRAWAT' => $display($show->JENISRAWAT),
                'TJKUNJ' => $display($show->TJKUNJ),
                'PROC' => $display($show->PROC, '-'),
                'POLIPERUJUK' => $display($show->POLIPERUJUK),
                'KELAS' => $display($show->KELAS),
                'KELASRAWAT' => $kelasRawat,
                'PENJAMIN' => $display($show->PENJAMIN, ''),
                'UNITPELAYANAN' => $display($show->UNITPELAYANAN, ''),
                'CETAKAN' => $display($show->CETAKAN, ''),
            ];

            foreach ($templateValues as $key => $value) {
                $templateProcessor->setValue($key, (string) $value);
            }

            // $logoPath = public_path('doc/input/sep/logo-bpjs.png');
            // if (File::exists($logoPath)) {
            //     $templateProcessor->setImageValue('LOGO_BPJS', [
            //         'path' => $logoPath,
            //         'width' => 180,
            //         'height' => 39,
            //         'ratio' => true,
            //     ]);
            // } else {
            //     $templateProcessor->setValue('LOGO_BPJS', 'BPJS Kesehatan');
            // }

            if (File::exists($qrAbsolutePath)) {
                $templateProcessor->setImageValue('QR1', [
                    'path' => $qrAbsolutePath,
                    'width' => 48,
                    'height' => 48,
                    'ratio' => true,
                ]);
            } else {
                $templateProcessor->setValue('QR1', '');
            }

            $templateProcessor->saveAs($docxPath);

            if (!File::exists($docxPath)) {
                throw new \RuntimeException('Gagal membuat DOCX SEP.');
            }

            /* ================================================================
            * 8. SET METADATA TITLE DOCX = NOMOR SEP
            * ================================================================ */
            $zip = new \ZipArchive();
            if ($zip->open($docxPath) !== true) {
                throw new \RuntimeException('Gagal membuka DOCX untuk mengatur judul dokumen.');
            }

            $coreXml = $zip->getFromName('docProps/core.xml');
            if ($coreXml !== false) {
                $titleXml = htmlspecialchars($fileName, ENT_XML1 | ENT_COMPAT, 'UTF-8');

                if (preg_match('~<dc:title(?:\s[^>]*)?>.*?</dc:title>~s', $coreXml)) {
                    $coreXml = preg_replace(
                        '~<dc:title(?:\s[^>]*)?>.*?</dc:title>~s',
                        '<dc:title>' . $titleXml . '</dc:title>',
                        $coreXml,
                        1
                    );
                } else {
                    $coreXml = preg_replace(
                        '~</cp:coreProperties>~',
                        '<dc:title>' . $titleXml . '</dc:title></cp:coreProperties>',
                        $coreXml,
                        1
                    );
                }

                if (!$zip->addFromString('docProps/core.xml', $coreXml)) {
                    $zip->close();
                    throw new \RuntimeException('Gagal menyimpan metadata judul DOCX SEP.');
                }
            }
            $zip->close();

            /* ================================================================
            * 9. KONVERSI DOCX KE PDF DENGAN LibreOfficeService
            * ================================================================ */
            [$success, $generatedPdf] = $this->libreOffice->generatePdf($docxPath, $outputDir);

            if (File::exists($docxPath)) {
                File::delete($docxPath);
            }

            if (!$success || !File::exists($finalPdf)) {
                // Fallback jika service mengembalikan lokasi output yang berbeda.
                if ($success && !empty($generatedPdf) && File::exists($generatedPdf) && $generatedPdf !== $finalPdf) {
                    File::move($generatedPdf, $finalPdf);
                }
            }

            if (!$success || !File::exists($finalPdf)) {
                throw new \RuntimeException('Gagal membuat PDF SEP.');
            }

            /* ================================================================
            * 10. SIMPAN / PERBARUI DATA klaim_file
            * ================================================================ */
            DB::table('simrspku_klaim.klaim_file')->updateOrInsert(
                [
                    'nomor' => $kunjungan,
                    'jenis' => 1,
                ],
                [
                    'title' => $fileName . '.pdf',
                    'filename' => $relativePath . '.pdf',
                    'user' => auth()->user()->ID,
                    'status' => true,
                    'deleted_at' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            DB::commit();

            return response()->file($finalPdf, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $fileName . '.pdf"',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
        } catch (\Throwable $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            if ($docxPath && File::exists($docxPath)) {
                File::delete($docxPath);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat PDF SEP.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // ====================================================================================================================================
    // =========================================================  RESUME MEDIS  ===========================================================
    // ====================================================================================================================================
    public function generateResumeMedis($kunjungan)
    {
        $temporaryFiles = [];
        $temporaryDocs = [];

        try {
            $dbCustom = DB::connection('db_custom');

            // Pastikan PHPWord meng-escape karakter XML seperti <, >, dan &.
            if (method_exists(Settings::class, 'setOutputEscapingEnabled')) {
                Settings::setOutputEscapingEnabled(true);
            }

            /* ---------------------------------------------------------------
            * 1. DATA KUNJUNGAN UTAMA
            * ------------------------------------------------------------- */
            $resume = DB::table('pendaftaran.kunjungan AS pk')
                ->leftJoin('pendaftaran.pendaftaran AS pp', 'pp.NOMOR', '=', 'pk.NOPEN')
                ->leftJoin('pendaftaran.penjamin AS pj', 'pp.NOMOR', '=', 'pj.NOPEN')
                ->leftJoin('master.ruangan AS ru', 'ru.ID', '=', 'pk.RUANGAN')
                ->leftJoin('master.dokter AS dr', 'dr.ID', '=', 'pk.DPJP')
                ->select(
                    'pj.NOMOR AS NOSEP',
                    'pp.NOMOR AS NOPEN',
                    'pk.NOMOR AS NOMOR',
                    'pk.RUANGAN AS RUANGAN',
                    'pk.DPJP',
                    DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'),
                    'dr.NIP AS NIPDOKTER',
                    'pp.TANGGAL AS TGLPERIKSA'
                )
                ->where('pk.NOMOR', $kunjungan)
                ->first();

            if (!$resume) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Resume Medis tidak ditemukan.',
                ], 404);
            }

            $nopen = (string) $resume->NOPEN;
            $isRadar = str_starts_with((string) $resume->RUANGAN, '1020201');
            $ruanganKhusus = ['1020702', '102010103'];
            $isKhusus = false;

            foreach ($ruanganKhusus as $kodeRuangan) {
                if (str_starts_with((string) $resume->RUANGAN, $kodeRuangan)) {
                    $isKhusus = true;
                    break;
                }
            }

            /* ---------------------------------------------------------------
            * 2. HELPER DATA / TEKS
            * ------------------------------------------------------------- */
            $read = function ($row, string $key, $default = null) {
                if (is_array($row)) {
                    if (isset($row[$key])) {
                        return $row[$key];
                    }
                    foreach ($row as $column => $value) {
                        if (strcasecmp((string) $column, $key) === 0) {
                            return $value ?? $default;
                        }
                    }
                    return $default;
                }

                if (is_object($row)) {
                    if (isset($row->{$key})) {
                        return $row->{$key};
                    }
                    foreach (get_object_vars($row) as $column => $value) {
                        if (strcasecmp((string) $column, $key) === 0) {
                            return $value ?? $default;
                        }
                    }
                }

                return $default;
            };

            $clean = function ($value, string $fallback = '-') {
                if ($value === null) {
                    return $fallback;
                }

                $text = (string) $value;
                // Teks dari SIMRS terkadang berisi markup HTML hasil editor RME.
                $text = preg_replace('~<br\s*/?>~i', ' ', $text);
                $text = preg_replace('~</(?:p|div|li|tr|h[1-6])\s*>~i', ' ', $text);
                $text = strip_tags($text);
                $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $text = preg_replace('/[\x{00A0}\t ]+/u', ' ', $text);
                $text = preg_replace('/\s*\n\s*/u', ' ', $text);
                $text = trim($text);

                return $text === '' ? $fallback : $text;
            };

            $checked = function ($row, string $key) use ($read) {
                return (string) $read($row, $key, '0') === '1' ? '☑' : '☐';
            };

            $getSignature = function (
                $visitNumber,
                $doctorId,
                $doctorNip,
                $doctorName,
                bool $allowAnyVisitSignature = false
            ) use ($isKhusus) {
                // Untuk kunjungan utama, sama seperti compileResumeRj:
                // tanda tangan langsung dipakai jika tersimpan pada folder signatures/pegawai.
                $ttdKunjungan = DB::table('simrspku_klaim.tanda_tangan AS ttd')
                    ->where('ttd.kunjungan', $visitNumber)
                    ->whereNull('ttd.deleted_at')
                    ->orderBy('ttd.id', 'desc')
                    ->first();

                if ($ttdKunjungan) {
                    $signaturePath = (string) ($ttdKunjungan->signature_path ?? '');
                    $bolehDipakai = $allowAnyVisitSignature
                        || Str::startsWith($signaturePath, 'signatures/pegawai');

                    if ($bolehDipakai && $signaturePath !== '') {
                        $candidate = storage_path('app/public/' . $signaturePath);
                        if (is_file($candidate)) {
                            return $candidate;
                        }
                    }
                }

                // Fallback tanda tangan berdasarkan user/pegawai DPJP.
                $userId = DB::table('master.dokter AS dr')
                    ->leftJoin('aplikasi.pengguna AS pe', 'pe.NIP', '=', 'dr.NIP')
                    ->where('dr.ID', $doctorId)
                    ->where('dr.STATUS', 1)
                    ->value('pe.ID');

                if ($isKhusus) {
                    $ttdPegawai = DB::table('simrspku_klaim.tanda_tangan_pegawai AS ttp')
                        ->where('ttp.nip', $doctorNip)
                        ->where('ttp.status', 1)
                        ->inRandomOrder()
                        ->first();
                } else {
                    $ttdPegawai = $userId
                        ? DB::table('simrspku_klaim.tanda_tangan AS ttd')
                            ->where('ttd.user', $userId)
                            ->whereNull('ttd.deleted_at')
                            ->orderBy('ttd.id', 'desc')
                            ->first()
                        : null;
                }

                if ($ttdPegawai && !empty($ttdPegawai->signature_path)) {
                    $candidate = storage_path('app/public/' . $ttdPegawai->signature_path);
                    if (is_file($candidate)) {
                        return $candidate;
                    }
                }

                return null;
            };

            $formatMedicineRadar = function ($row) use ($read, $clean) {
                $racikan = $clean($read($row, 'RACIKAN'), '');
                return [
                    'OBAT_NAMA' => $clean($read($row, 'NAMAOBAT')),
                    'OBAT_JUMLAH' => $clean($read($row, 'JUMLAH')),
                    'OBAT_DOSIS' => $clean($read($row, 'DOSIS')),
                    'OBAT_FREKUENSI' => $clean($read($row, 'FREKUENSI')),
                    'OBAT_CARA' => $clean($read($row, 'KETERANGAN')),
                    'OBAT_RACIKAN' => trim(implode(' | ', array_filter([
                        $racikan,
                        $clean($read($row, 'PETUNJUK_RACIKAN'), ''),
                    ]))) ?: '-',
                ];
            };

            $formatMedicineRj = function ($row) use ($read, $clean) {
                $catatan = array_filter([
                    $clean($read($row, 'KETERANGAN'), ''),
                    $clean($read($row, 'RACIKAN'), ''),
                    $clean($read($row, 'PETUNJUK_RACIKAN'), ''),
                ], fn ($value) => $value !== '');

                return [
                    'OBAT_NAMA' => $clean($read($row, 'NAMAOBAT')),
                    'OBAT_JUMLAH' => $clean($read($row, 'JUMLAH')),
                    'OBAT_ATURAN' => $clean($read($row, 'ATURANPAKAI')),
                    'OBAT_CATATAN' => $catatan ? implode(' | ', $catatan) : '-',
                ];
            };

            /* ---------------------------------------------------------------
            * 3. QUERY OBAT SESUAI TEMPLATE LAMA
            * ------------------------------------------------------------- */
            $getObatRows = function (bool $radar) use ($dbCustom, $nopen) {
                if ($radar) {
                    $sql = "
                        SELECT pk.NOPEN,
                            ib.NAMA AS NAMAOBAT,
                            lf.JUMLAH,
                            f.FREKUENSI,
                            lf.DOSIS,
                            lf.KETERANGAN,
                            CONCAT(lf.RACIKAN, lf.GROUP_RACIKAN) AS RACIKAN,
                            lf.PETUNJUK_RACIKAN,
                            lf.`STATUS` AS STATUSLAYANAN
                        FROM layanan.farmasi AS lf
                        LEFT JOIN master.referensi AS ref
                            ON ref.ID = lf.ATURAN_PAKAI AND ref.JENIS = 41
                        LEFT JOIN master.frekuensi_aturan_resep AS f
                            ON lf.FREKUENSI = f.ID AND f.`STATUS` != 0
                        INNER JOIN pendaftaran.kunjungan AS pk
                                ON lf.KUNJUNGAN = pk.NOMOR
                        LEFT JOIN layanan.order_resep AS o ON o.NOMOR = pk.REF
                        LEFT JOIN master.dokter AS md ON o.DOKTER_DPJP = md.ID
                        LEFT JOIN master.pegawai AS mp ON md.NIP = mp.NIP
                        LEFT JOIN pendaftaran.kunjungan AS asal ON o.KUNJUNGAN = asal.NOMOR
                        LEFT JOIN master.ruangan AS r ON asal.RUANGAN = r.ID AND r.JENIS = 5
                        LEFT JOIN master.referensi AS jenisk
                            ON r.JENIS_KUNJUNGAN = jenisk.ID AND jenisk.JENIS = 15
                        INNER JOIN pendaftaran.pendaftaran AS pp ON pk.NOPEN = pp.NOMOR
                        INNER JOIN inventory.barang AS ib ON lf.FARMASI = ib.ID
                        INNER JOIN pembayaran.rincian_tagihan AS rt
                                ON lf.ID = rt.REF_ID AND rt.JENIS = 4
                        WHERE lf.`STATUS` != 0
                        AND pk.`STATUS` IN (1, 2)
                        AND pk.NOPEN = ?
                        AND o.RESEP_PASIEN_PULANG = 1
                        AND LEFT(ib.KATEGORI, 3) IN ('101', '102')
                        ORDER BY lf.RACIKAN, lf.GROUP_RACIKAN
                    ";
                } else {
                    $sql = "
                        SELECT pk.NOPEN,
                            ib.NAMA AS NAMAOBAT,
                            lf.JUMLAH,
                            IF(ref.DESKRIPSI IS NULL, lf.ATURAN_PAKAI, ref.DESKRIPSI) AS ATURANPAKAI,
                            lf.KETERANGAN,
                            CONCAT(lf.RACIKAN, lf.GROUP_RACIKAN) AS RACIKAN,
                            lf.PETUNJUK_RACIKAN,
                            lf.`STATUS` AS STATUSLAYANAN
                        FROM layanan.farmasi AS lf
                        LEFT JOIN master.referensi AS ref
                            ON ref.ID = lf.ATURAN_PAKAI AND ref.JENIS = 41
                        INNER JOIN pendaftaran.kunjungan AS pk
                                ON lf.KUNJUNGAN = pk.NOMOR
                        LEFT JOIN layanan.order_resep AS o ON o.NOMOR = pk.REF
                        LEFT JOIN master.dokter AS md ON o.DOKTER_DPJP = md.ID
                        LEFT JOIN master.pegawai AS mp ON md.NIP = mp.NIP
                        LEFT JOIN pendaftaran.kunjungan AS asal ON o.KUNJUNGAN = asal.NOMOR
                        LEFT JOIN master.ruangan AS r ON asal.RUANGAN = r.ID AND r.JENIS = 5
                        LEFT JOIN master.referensi AS jenisk
                            ON r.JENIS_KUNJUNGAN = jenisk.ID AND jenisk.JENIS = 15
                        INNER JOIN pendaftaran.pendaftaran AS pp ON pk.NOPEN = pp.NOMOR
                        INNER JOIN inventory.barang AS ib ON lf.FARMASI = ib.ID
                        INNER JOIN pembayaran.rincian_tagihan AS rt
                                ON lf.ID = rt.REF_ID AND rt.JENIS = 4
                        WHERE lf.`STATUS` != 0
                        AND pk.`STATUS` IN (1, 2)
                        AND pk.NOPEN = ?
                        AND LEFT(ib.KATEGORI, 3) = '101'
                        ORDER BY lf.RACIKAN, lf.GROUP_RACIKAN
                    ";
                }

                return $dbCustom->select($sql, [$nopen]);
            };

            /* ---------------------------------------------------------------
             * 4. QUERY RESUME UTAMA, DIAGNOSIS, PROSEDUR, OBAT, DAN TANDA TANGAN
             *    Seluruh query stored procedure dijalankan langsung di function ini.
             *    Tidak menggunakan CALL dan tidak membuat function/model tambahan.
             * ------------------------------------------------------------- */
            $signatureMain = $getSignature(
                $resume->NOMOR,
                $resume->DPJP,
                $resume->NIPDOKTER,
                $resume->NAMADOKTER,
                false
            );

            if (!$signatureMain) {
                return response()->json([
                    'success' => false,
                    'message' => "Tanda tangan Dokter DPJP ({$resume->NAMADOKTER}) pada Resume Utama tidak ditemukan.",
                ], 404);
            }

            $obatRows = $getObatRows($isRadar);

            $diagnosaUtamaRows = [];
            $diagnosaSekunderRows = [];
            $prosedurRows = [];
            $resumeRadarRow = null;

            // print_r($isRadar);
            // die();

            if ($isRadar) {
                /* =============================================================
                 * A. RESUME UTAMA: pengganti simrspku_klaim.CetakMR2Custom
                 * =========================================================== */
                $dbCustom->statement('SET @PNOPEN = ?', [$nopen]);

                $dbCustom->statement('DROP TEMPORARY TABLE IF EXISTS TEMP_DATA_HASIL_LAB');
                $dbCustom->statement("
                    CREATE TEMPORARY TABLE TEMP_DATA_HASIL_LAB ENGINE=MEMORY
                    SELECT DISTINCT hl.ID
                    FROM medicalrecord.`resume` r,
                        JSON_TABLE(r.HASIL_LAB,
                        '$[*]' COLUMNS(ID CHAR(12) PATH '$.ID')
                        ) AS hl
                    WHERE r.NOPEN = @PNOPEN AND r.`STATUS` != 0
                ");

                $dbCustom->statement('
                    ALTER TABLE TEMP_DATA_HASIL_LAB
                        CHANGE COLUMN ID ID CHAR(12) NOT NULL,
                        ADD PRIMARY KEY (ID)
                ');

                $mainRows = $dbCustom->select("
                    SELECT inst.PPK IDPPK,UPPER(inst.NAMA) NAMAINSTANSI, inst.KOTA KOTA, inst.ALAMAT ALAMATINST
                            , inst.TELEPON TLP, inst.FAX, inst.EMAIL, inst.WEBSITE
                            , INSERT(INSERT(INSERT(LPAD(p.NORM,8,'0'),3,0,'-'),6,0,'-'),9,0,'-') NORM
                            , INSERT(INSERT(INSERT(LPAD(p.NORM,8,'0'),3,0,''),6,0,''),9,0,'') NO_RM
                            , master.getNamaLengkap(p.NORM) NAMALENGKAP
                            , DATE_FORMAT(p.TANGGAL_LAHIR,'%d-%m-%Y') TANGGAL_LAHIR
                            , rjk.DESKRIPSI JENISKELAMIN
                            , DATE_FORMAT(pd.TANGGAL,'%d-%m-%Y %H:%i:%s') TGLREG
                            , DATE_FORMAT(pl.TANGGAL,'%d-%m-%Y %H:%i:%s') TGLKELUAR
                            , CONCAT(
                                DAY(IF(pl.TANGGAL IS NULL, pd.TANGGAL, pl.TANGGAL)),
                                ' ',
                                ELT(
                                    MONTH(IF(pl.TANGGAL IS NULL, pd.TANGGAL, pl.TANGGAL)),
                                    'Januari','Februari','Maret','April','Mei','Juni',
                                    'Juli','Agustus','September','Oktober','November','Desember'
                                ),
                                ' ',
                                YEAR(IF(pl.TANGGAL IS NULL, pd.TANGGAL, pl.TANGGAL))
                            ) AS TGLSURAT
                            , DATE_FORMAT(pd.TANGGAL,'%d-%m-%Y') TANGGALKELUAR
                            , DATEDIFF(pl.TANGGAL, pd.TANGGAL) LAMADIRAWAT
                            , CONCAT(u.DESKRIPSI,IF(pk.RUANG_KAMAR_TIDUR = null,'',CONCAT(' - ',rk.KAMAR))) RUANG_RAWAT_TERAKHIR
                            , cr.DESKRIPSI CARA_BAYAR
                            , pd.NOMOR NOPEN
                            , p.NORM NORM_CEK
                            , IF(IFNULL(an.DESKRIPSI,(SELECT a.DESKRIPSI
                                    FROM medicalrecord.anamnesis a, pendaftaran.kunjungan kg, `master`.ruangan rg
                                    WHERE a.PENDAFTARAN=pd.NOMOR AND a.KUNJUNGAN=kg.NOMOR AND kg.RUANGAN=rg.ID AND kg.STATUS!=0 AND rg.JENIS_KUNJUNGAN=3
                                    ORDER BY a.TANGGAL DESC
                                    LIMIT 1))='-','Tidak Ada',
                                                IFNULL(an.DESKRIPSI,(SELECT a.DESKRIPSI
                                    FROM medicalrecord.anamnesis a, pendaftaran.kunjungan kg, `master`.ruangan rg
                                    WHERE a.PENDAFTARAN=pd.NOMOR AND a.KUNJUNGAN=kg.NOMOR AND kg.RUANGAN=rg.ID AND kg.STATUS!=0 AND rg.JENIS_KUNJUNGAN=3
                                    ORDER BY a.TANGGAL DESC
                                    LIMIT 1))) anamnesis
                            , IF(IFNULL(rpp.DESKRIPSI,(SELECT rp.DESKRIPSI
                                    FROM medicalrecord.rpp rp
                                    LEFT JOIN pendaftaran.kunjungan pkrp ON pkrp.NOMOR=rp.KUNJUNGAN
                                    WHERE pkrp.NOPEN=pd.NOMOR ORDER BY rp.TANGGAL LIMIT 1))='-','Tidak Ada',IFNULL(rpp.DESKRIPSI,(SELECT rp.DESKRIPSI
                                    FROM medicalrecord.rpp rp
                                    LEFT JOIN pendaftaran.kunjungan pkrp ON pkrp.NOMOR=rp.KUNJUNGAN
                                    WHERE pkrp.NOPEN=pd.NOMOR ORDER BY rp.TANGGAL LIMIT 1))) RPP
                            , IF(IFNULL(rps.DESKRIPSI,(SELECT rp.DESKRIPSI
                                    FROM medicalrecord.rps rp
                                    LEFT JOIN pendaftaran.kunjungan pkrp ON pkrp.NOMOR=rp.KUNJUNGAN
                                    WHERE pkrp.NOPEN=pd.NOMOR ORDER BY rp.TANGGAL LIMIT 1))='-','Tidak Ada',IFNULL(rps.DESKRIPSI,(SELECT rp.DESKRIPSI
                                    FROM medicalrecord.rps rp
                                    LEFT JOIN pendaftaran.kunjungan pkrp ON pkrp.NOMOR=rp.KUNJUNGAN
                                    WHERE pkrp.NOPEN=pd.NOMOR ORDER BY rp.TANGGAL LIMIT 1))) RPS
                            , IF(IFNULL(ku.DESKRIPSI, (SELECT ku.DESKRIPSI
                                    FROM medicalrecord.keluhan_utama ku
                                    LEFT JOIN pendaftaran.kunjungan pku ON pku.NOMOR=ku.KUNJUNGAN
                                    WHERE pku.NOPEN=pd.NOMOR AND pku.REF IS NULL
                                    ORDER BY ku.TANGGAL DESC LIMIT 1))='-','Tidak Ada',IFNULL(ku.DESKRIPSI, (SELECT ku.DESKRIPSI
                                    FROM medicalrecord.keluhan_utama ku
                                    LEFT JOIN pendaftaran.kunjungan pku ON pku.NOMOR=ku.KUNJUNGAN
                                    WHERE pku.NOPEN=pd.NOMOR AND pku.REF IS NULL
                                    ORDER BY ku.TANGGAL DESC LIMIT 1))) keluhan_utama
                            , IF(ck.ID IN (6,7),'Tidak ada',(SELECT CONCAT(ROUND(pn.NYERI),' Metode : ',mny.DESKRIPSI)
                                    FROM medicalrecord.penilaian_nyeri pn
                                    LEFT JOIN pendaftaran.kunjungan pkrp ON pkrp.NOMOR=pn.KUNJUNGAN
                                    LEFT JOIN master.referensi mny ON pn.METODE=mny.ID AND mny.JENIS=71
                                    WHERE pkrp.NOPEN=pd.NOMOR LIMIT 1)) NYERI
                            , tv.KESADARAN, rtv.DESKRIPSI TINGKAT_KESADARAN
                            , (SELECT CONCAT(ROUND(tvt.SISTOLIK),'/',ROUND(tvt.DISTOLIK))
                                FROM medicalrecord.tanda_vital tvt
                                LEFT JOIN pendaftaran.kunjungan pkt ON tvt.KUNJUNGAN=pkt.NOMOR AND pkt.`STATUS`!=0
                                LEFT JOIN layanan.pasien_pulang plt ON pkt.NOMOR=plt.KUNJUNGAN AND plt.`STATUS`=1
                                LEFT JOIN pendaftaran.pendaftaran pdt ON plt.NOPEN=pdt.NOMOR
                                    WHERE plt.NOPEN=pd.NOMOR
                                    ORDER BY tvt.WAKTU_PEMERIKSAAN DESC LIMIT 1) TEKANAN_DARAH
                            , (SELECT tvt.KEADAAN_UMUM
                                FROM medicalrecord.tanda_vital tvt
                                LEFT JOIN pendaftaran.kunjungan pkt ON tvt.KUNJUNGAN=pkt.NOMOR AND pkt.`STATUS`!=0
                                LEFT JOIN layanan.pasien_pulang plt ON pkt.NOMOR=plt.KUNJUNGAN AND plt.`STATUS`=1
                                LEFT JOIN pendaftaran.pendaftaran pdt ON plt.NOPEN=pdt.NOMOR
                                    WHERE plt.NOPEN=pd.NOMOR
                                    ORDER BY tvt.WAKTU_PEMERIKSAAN DESC LIMIT 1) KEADAANUMUM
                            , (SELECT ROUND(tvt.FREKUENSI_NADI)
                                FROM medicalrecord.tanda_vital tvt
                                LEFT JOIN pendaftaran.kunjungan pkt ON tvt.KUNJUNGAN=pkt.NOMOR AND pkt.`STATUS`!=0
                                LEFT JOIN layanan.pasien_pulang plt ON pkt.NOMOR=plt.KUNJUNGAN AND plt.`STATUS`=1
                                LEFT JOIN pendaftaran.pendaftaran pdt ON plt.NOPEN=pdt.NOMOR
                                    WHERE plt.NOPEN=pd.NOMOR
                                    ORDER BY tvt.WAKTU_PEMERIKSAAN DESC LIMIT 1) FREKUENSI_NADI
                            , (SELECT ROUND(tvt.FREKUENSI_NAFAS)
                                FROM medicalrecord.tanda_vital tvt
                                LEFT JOIN pendaftaran.kunjungan pkt ON tvt.KUNJUNGAN=pkt.NOMOR AND pkt.`STATUS`!=0
                                LEFT JOIN layanan.pasien_pulang plt ON pkt.NOMOR=plt.KUNJUNGAN AND plt.`STATUS`=1
                                LEFT JOIN pendaftaran.pendaftaran pdt ON plt.NOPEN=pdt.NOMOR
                                    WHERE plt.NOPEN=pd.NOMOR
                                    ORDER BY tvt.WAKTU_PEMERIKSAAN DESC LIMIT 1) FREKUENSI_NAFAS
                            , (SELECT ROUND(tvt.SUHU)
                                FROM medicalrecord.tanda_vital tvt
                                LEFT JOIN pendaftaran.kunjungan pkt ON tvt.KUNJUNGAN=pkt.NOMOR AND pkt.`STATUS`!=0
                                LEFT JOIN layanan.pasien_pulang plt ON pkt.NOMOR=plt.KUNJUNGAN AND plt.`STATUS`=1
                                LEFT JOIN pendaftaran.pendaftaran pdt ON plt.NOPEN=pdt.NOMOR
                                    WHERE plt.NOPEN=pd.NOMOR
                                    ORDER BY tvt.WAKTU_PEMERIKSAAN DESC LIMIT 1) SUHU
                            , (SELECT ROUND(tvt.SATURASI_O2)
                                FROM medicalrecord.tanda_vital tvt
                                LEFT JOIN pendaftaran.kunjungan pkt ON tvt.KUNJUNGAN=pkt.NOMOR AND pkt.`STATUS`!=0
                                LEFT JOIN layanan.pasien_pulang plt ON pkt.NOMOR=plt.KUNJUNGAN AND plt.`STATUS`=1
                                LEFT JOIN pendaftaran.pendaftaran pdt ON plt.NOPEN=pdt.NOMOR
                                    WHERE plt.NOPEN=pd.NOMOR
                                    ORDER BY tvt.WAKTU_PEMERIKSAAN DESC LIMIT 1) SATURASI_O2
                            , (SELECT ROUND(tvt.GCS)
                                FROM medicalrecord.tanda_vital tvt
                                LEFT JOIN pendaftaran.kunjungan pkt ON tvt.KUNJUNGAN=pkt.NOMOR AND pkt.`STATUS`!=0
                                LEFT JOIN layanan.pasien_pulang plt ON pkt.NOMOR=plt.KUNJUNGAN AND plt.`STATUS`=1
                                LEFT JOIN pendaftaran.pendaftaran pdt ON plt.NOPEN=pdt.NOMOR
                                    WHERE plt.NOPEN=pd.NOMOR
                                    ORDER BY tvt.WAKTU_PEMERIKSAAN DESC LIMIT 1) GCS
                            , rt.DESKRIPSI rencana_terapi
                            , rm.DESKRIPSI_KONSUL DESKRIPSI_KONSUL
                            , IF(kd.ID IN (4,5),'Tidak Ada',IFNULL(ee.EDUKASI, (SELECT e.EDUKASI
                                        FROM medicalrecord.edukasi_emergency e
                                            , pendaftaran.kunjungan pk
                                        WHERE e.KUNJUNGAN=pk.NOMOR AND e.`STATUS`!=0
                                        AND pk.NOPEN=pd.NOMOR
                                        ORDER BY e.TANGGAL DESC LIMIT 1))) EDUKASI
                            , IF(kd.ID IN (4,5),'Tidak Ada',IFNULL(ee.KEMBALI_KE_UGD, (SELECT e.KEMBALI_KE_UGD
                                        FROM medicalrecord.edukasi_emergency e
                                            , pendaftaran.kunjungan pk
                                        WHERE e.KUNJUNGAN=pk.NOMOR AND e.`STATUS`!=0
                                        AND pk.NOPEN=pd.NOMOR
                                        ORDER BY e.TANGGAL DESC LIMIT 1))) KEMBALI_KE_UGD
                            , IF(kd.ID IN (4,5),'Tidak Ada',jd.NOMOR) NOMOR_KONTROL
                            , IF(kd.ID IN (4,5),null,jd.TANGGAL) TANGGAL_KONTROL
                            , IF(kd.ID IN (4,5),'Tidak Ada',r.DESKRIPSI) RUANG_KONTROL
                            , '' KET_KONTROL
                            , IF(kd.ID IN (4,5),'Tidak Ada',jd.NOMOR_REFERENSI) NOMOR_REFERENSI
                            , IF(kd.ID IN (4,5),0,IF(jd.NOMOR IS NOT NULL,1,0)) POLIKLINIK_RS
                            , rm.INDIKASI_RAWAT_INAP
                            , (SELECT CONCAT(pri.INDIKASI,'\r',pri.DESKRIPSI)
                                FROM pendaftaran.kunjungan k
                                    LEFT JOIN medicalrecord.perencanaan_rawat_inap pri ON pri.KUNJUNGAN=k.NOMOR AND pri.`STATUS`!=0
                                    , pendaftaran.pendaftaran d
                                    , `master`.ruangan r
                                WHERE   k.NOPEN=d.NOMOR AND k.`STATUS`!=0 AND k.RUANGAN=r.ID AND r.JENIS_KUNJUNGAN NOT IN (0,3,4,5,6,11,13,14)
                                AND d.`STATUS`!=0 AND d.NORM=pd.NORM AND d.TANGGAL < pd.TANGGAL
                                ORDER BY d.TANGGAL DESC
                                LIMIT 1) INDIKASI_RANAP
                            , IF(pl.DOKTER IS NULL,`master`.getDPJP(pd.NOMOR, 2),master.getNamaLengkapPegawai(mpdokdpjp.NIP)) DPJP
                            , pdr.NIP NIP
                            , IF(IF(rm.DESKRIPSI_KONSUL='-' OR rm.DESKRIPSI_KONSUL='', master.getJawabanKonsul(rm.NOPEN), rm.DESKRIPSI_KONSUL)='','Tidak Ada'
                                , IF(rm.DESKRIPSI_KONSUL='-' OR rm.DESKRIPSI_KONSUL='', master.getJawabanKonsul(rm.NOPEN), rm.DESKRIPSI_KONSUL)) KONSULTASI
                            , NOW() HARI_INI
                            , DATE_FORMAT(pd.TANGGAL,'%d %M %Y') TANGGAL_HARI_INI
                        , kd.DESKRIPSI KEADAAN_KELUAR
                        , IF(psx.NAMA IS NULL OR psx.NAMA = '', IF(pesx.NAMA IS NULL OR pesx.NAMA = '', 'Mohon Inputkan Data Penanggungjawab/Pengantar Pasien', pesx.NAMA), psx.NAMA) PENANGGUNG_JAWAB_PASIEN
                        , kps.NAMA NAMA_KELUARGA_PASIEN
                        , master.getNamaLengkapPegawai(pdr.NIP) DOKTER
                            , IF(kd.ID=1,1,0) SEMBUH
                            , IF(kd.ID=2,1,0) MEMBAIK
                            , IF(kd.ID=3,1,0) BELUM_SEMBUH
                            , IF(kd.ID=4,1,0) MENINGGAL_KURANG48
                            , IF(kd.ID=5,1,0) MENINGGAL_LEBIH48
                            , IF(kd.ID=6,1,0) KDOA
                            , IF(kd.ID=7,1,0) KRUJUK
                            , ck.DESKRIPSI CARA_KELUAR
                            , IF(ck.ID=1,1,0) DIIJINKAN_PULANG
                            , IF(ck.ID=2,1,0) PULANG_PAKSA
                            , IF(ck.ID=3,1,0) DIRUJUK
                            , IF(ck.ID=4,1,0) LAIN_LAIN
                            , IF(ck.ID=5,1,0) PINDAH_RS
                            , IF(ck.ID=6,1,0) CMENINGGAL
                            , IF(ck.ID=7,1,0) CDOA
                        , IF(rm.ALERGI_REAKSI_OBAT='-' OR rm.ALERGI_REAKSI_OBAT='','Tidak Ada',rm.ALERGI_REAKSI_OBAT) ALERGI
                        , IF(rm.DESKRIPSI_HASIL_LAB=''
                                    ,(SELECT REPLACE(GROUP_CONCAT(hasil SEPARATOR ';'),';','\r')
                                        FROM (
                                                SELECT CONCAT(
                                                    'Tgl Hasil ',
                                                    DATE_FORMAT(MIN(hlab.TANGGAL), '%d-%m-%Y'),
                                                    ', ',
                                                    GROUP_CONCAT(
                                                        CONCAT(
                                                            ptl.PARAMETER,
                                                            '=',
                                                            hlab.HASIL,
                                                            ' ',
                                                            IF(sl.DESKRIPSI IS NULL, '', sl.DESKRIPSI)
                                                        )
                                                    )
                                                ) AS hasil
                                                FROM TEMP_DATA_HASIL_LAB r,
                                                    layanan.hasil_lab hlab
                                                LEFT JOIN master.parameter_tindakan_lab ptl
                                                    ON hlab.PARAMETER_TINDAKAN = ptl.ID
                                                    AND ptl.`STATUS` != 0
                                                LEFT JOIN master.referensi sl
                                                    ON ptl.SATUAN = sl.ID
                                                    AND sl.JENIS = 35
                                                WHERE hlab.ID = r.ID
                                                AND hlab.`STATUS` != 0
                                                GROUP BY DATE(hlab.TANGGAL)
                                            ) gh), rm.DESKRIPSI_HASIL_LAB) LAB
                            , IF(rm.DESKRIPSI_HASIL_RAD='',(SELECT GROUP_CONCAT(t.NAMA,' = ',hr.KESAN)
                                    FROM medicalrecord.`resume` r,
                                            JSON_TABLE(r.HASIL_RAD,
                                            '$[*]' COLUMNS(
                                                    ID INT PATH '$.ID'
                                                )
                                        ) AS hl
                                        , layanan.hasil_rad hr
                                        , layanan.tindakan_medis tm
                                        , `master`.tindakan t
                                    WHERE r.NOPEN=@PNOPEN AND hl.ID=hr.ID AND hr.`STATUS`!=0 AND hr.TINDAKAN_MEDIS=tm.ID AND tm.`STATUS`!=0 AND r.`STATUS`!=0
                                        AND tm.TINDAKAN=t.ID), rm.DESKRIPSI_HASIL_RAD) RAD
                            , IFNULL(layanan.getResepPulang(@PNOPEN),0) RESEPPULANG
                            , IFNULL((SELECT jt.ID  IDDIAGNOSA
                                        FROM medicalrecord.`resume` r,
                                                JSON_TABLE(r.DIAGNOSA_PROSEDUR,
                                                '$[*]' COLUMNS(
                                                        ID INT PATH '$.ID',
                                                        UTAMA INT PATH '$.UTAMA',
                                                        NESTED PATH '$.PROSEDUR[*]'
                                                            COLUMNS (
                                                            IDPROSEDUR VARCHAR(10) PATH '$.IDPROSEDUR',
                                                            TINDAKANMEDIS CHAR(11) PATH '$.TINDAKANMEDIS'
                                                            )
                                                    )
                                            ) AS jt
                                        WHERE r.NOPEN=pd.NOMOR AND UTAMA=1 AND r.`STATUS`!=0
                                        LIMIT 1),0) STS_DIAG_UTAMA
                            , IFNULL((SELECT jt.ID  IDDIAGNOSA
                                        FROM medicalrecord.`resume` r,
                                                JSON_TABLE(r.DIAGNOSA_PROSEDUR,
                                                '$[*]' COLUMNS(
                                                        ID INT PATH '$.ID',
                                                        UTAMA INT PATH '$.UTAMA',
                                                        NESTED PATH '$.PROSEDUR[*]'
                                                            COLUMNS (
                                                            IDPROSEDUR VARCHAR(10) PATH '$.IDPROSEDUR',
                                                            TINDAKANMEDIS CHAR(11) PATH '$.TINDAKANMEDIS'
                                                            )
                                                    )
                                            ) AS jt
                                        WHERE r.NOPEN=pd.NOMOR AND UTAMA=2 AND r.`STATUS`!=0
                                        LIMIT 1),0) STS_DIAG_SEKUNDER
                            , IFNULL(master.getKodeICD9CM(@PNOPEN),0) STS_PROSEDUR
                            , UPPER((master.getDiagnosa(@PNOPEN,1))) DIAGNOSAUTAMA, (master.getKodeDiagnosa(@PNOPEN,1)) KODEDIAGNOSAUTAMA
                            , (master.getDiagnosa(@PNOPEN,2)) DIAGNOSASEKUNDER, (master.getKodeDiagnosa(@PNOPEN,2)) KODEDIAGNOSASEKUNDER
                            , (SELECT REPLACE(REPLACE(pf1.DESKRIPSI,'<div','<br><div'),'<p','<br><p')
                                FROM medicalrecord.pemeriksaan_fisik pf1
                                    WHERE pf1.PENDAFTARAN=pd.NOMOR
                                    ORDER BY pf1.TANGGAL DESC LIMIT 1) FISIK
                            , IF
                            (
                                    (SELECT DISTINCT dgg.`DIAGNOSA`
                                FROM medicalrecord.`diagnosa` dgg
                                    WHERE dgg.NOPEN=pd.NOMOR AND dgg.`STATUS` = 1 AND dgg.INA_GROUPER = 1 ORDER BY dgg.`DIAGNOSA` DESC LIMIT 1
                                    ) = '','Tidak ada tambahan diagnosa Dokter',
                                    /*CONCAT(
                                        (
                                            SELECT DISTINCT dgg.`DIAGNOSA` FROM medicalrecord.`diagnosa` dgg
                                            WHERE dgg.NOPEN=pd.NOMOR AND dgg.`STATUS` = 1 AND dgg.UTAMA = 1
                                        ),
                                        (
                                            SELECT DISTINCT dgg.`DIAGNOSA` FROM medicalrecord.`diagnosa` dgg
                                            WHERE dgg.NOPEN=pd.NOMOR AND dgg.`STATUS` = 1 AND dgg.UTAMA = 2 ORDER BY dgg.ID ASC
                                        )
                                    )*/
                                    (
                                        SELECT GROUP_CONCAT(DISTINCT dgg.`DIAGNOSA` ORDER BY dgg.UTAMA ASC, dgg.id SEPARATOR ',')
                                    FROM medicalrecord.`diagnosa` dgg
                                        WHERE dgg.NOPEN=pd.NOMOR AND dgg.`STATUS` = 1 AND dgg.INA_GROUPER = 1 ORDER BY dgg.ID ASC
                                    )
                                ) DIAGNOSA_DOKTER_ICD10
                            , IF((SELECT DISTINCT psr.KODE
                                FROM medicalrecord.`prosedur` psr
                                    WHERE psr.NOPEN=pd.NOMOR AND psr.`STATUS` = 1 LIMIT 1
                                    ) IS NULL,'Tidak ada tambahan',(SELECT CONCAT('(',GROUP_CONCAT(DISTINCT psr.KODE),') ',GROUP_CONCAT(DISTINCT psr.TINDAKAN SEPARATOR ','))
                                    FROM medicalrecord.`prosedur` psr
                                    WHERE psr.NOPEN=pd.NOMOR AND psr.`STATUS` = 1
                                    )) DIAGNOSA_DOKTER_ICD9
                            , IF(DESKRIPSI_HASIL_PENUNJANG_LAINYA='','Tidak Ada',DESKRIPSI_HASIL_PENUNJANG_LAINYA) DESKRIPSI_HASIL_PENUNJANG_LAINYA
                            , IF(kd.ID IN (4,5),'Tidak Ada',IF(RENCANA_DIET='','Tidak Ada',RENCANA_DIET)) RENCANA_DIET
                            , IF(tg.LABORATORIUM != '0,00','Terlampir','Tidak Ada') BERKAS_LAB
                            , IF(tg.RADIOLOGI != '0,00','Terlampir','Tidak Ada') BERKAS_RAD
                            , DATE_FORMAT((SELECT p1.TANGGAL FROM pendaftaran.pendaftaran p1 LEFT JOIN pembayaran.tagihan_pendaftaran tp1 ON p1.NOMOR=tp1.PENDAFTARAN
                                WHERE tp1.TAGIHAN=tpdf.TAGIHAN AND tp1.`STATUS`!=0
                                ORDER BY p1.TANGGAL ASC LIMIT 1),'%d-%m-%Y %H:%i:%s') TGL_DAFTAR_AWAL
                        FROM pendaftaran.pendaftaran pd
                            LEFT JOIN master.keluarga_pasien kps ON pd.NORM = kps.NORM
                            LEFT JOIN pendaftaran.penanggung_jawab_pasien psx ON psx.NOPEN AND pd.NOMOR
                            LEFT JOIN pendaftaran.pengantar_pasien pesx ON pesx.NOPEN AND pd.NOMOR
                        LEFT JOIN layanan.pasien_pulang pl ON pd.NOMOR=pl.NOPEN AND pl.`STATUS`=1
                        LEFT JOIN pendaftaran.penjamin pj ON pd.NOMOR=pj.NOPEN
                            LEFT JOIN master.referensi cr ON pj.JENIS=cr.ID AND cr.JENIS=10
                            LEFT JOIN pendaftaran.kunjungan pk ON pl.KUNJUNGAN=pk.NOMOR AND pk.`STATUS`!=0
                            LEFT JOIN medicalrecord.penilaian_nyeri pny ON pk.NOMOR=pny.KUNJUNGAN
                            LEFT JOIN master.referensi mny ON pny.METODE=mny.ID AND mny.JENIS=71
                            LEFT JOIN master.ruangan u ON pk.RUANGAN=u.ID AND u.JENIS=5
                            LEFT JOIN master.ruang_kamar_tidur rkt ON pk.RUANG_KAMAR_TIDUR = rkt.ID
                            LEFT JOIN master.ruang_kamar rk ON rkt.RUANG_KAMAR = rk.ID
                            LEFT JOIN master.dokter dokdpjp ON pl.DOKTER=dokdpjp.ID
                            LEFT JOIN master.pegawai mpdokdpjp ON dokdpjp.NIP=mpdokdpjp.NIP
                            LEFT JOIN master.referensi kd ON pl.KEADAAN=kd.ID AND kd.JENIS=46
                            LEFT JOIN master.referensi ck ON pl.CARA=ck.ID AND ck.JENIS=45
                            LEFT JOIN pembayaran.tagihan_pendaftaran tpdf ON pd.NOMOR=tpdf.PENDAFTARAN AND tpdf.UTAMA=1 AND tpdf.`STATUS`!=0
                            LEFT JOIN pembayaran.tagihan tg ON tpdf.TAGIHAN = tg.ID AND tg.`STATUS`!=0
                            LEFT JOIN medicalrecord.`resume` rm ON rm.NOPEN=pd.NOMOR AND rm.`STATUS`!=0
                        LEFT JOIN medicalrecord.anamnesis an ON pk.NOMOR=an.KUNJUNGAN AND an.`STATUS`!=0
                        LEFT JOIN medicalrecord.rpp ON pk.NOMOR=rpp.KUNJUNGAN AND rpp.`STATUS`!=0
                        LEFT JOIN medicalrecord.rps ON pk.NOMOR=rps.KUNJUNGAN AND rps.`STATUS`!=0
                        LEFT JOIN medicalrecord.keluhan_utama ku ON pk.NOMOR=ku.KUNJUNGAN AND ku.`STATUS`!=0
                        LEFT JOIN medicalrecord.tanda_vital tv ON pk.NOMOR=tv.KUNJUNGAN AND tv.`STATUS`!=0
                        LEFT JOIN master.referensi rtv ON tv.TINGKAT_KESADARAN=rtv.ID AND rtv.JENIS=179
                        LEFT JOIN medicalrecord.rencana_terapi rt ON pk.NOMOR=rt.KUNJUNGAN AND rt.`STATUS`!=0
                        LEFT JOIN medicalrecord.edukasi_emergency ee ON pk.NOMOR=ee.KUNJUNGAN AND ee.`STATUS`!=0
                        LEFT JOIN medicalrecord.jadwal_kontrol jd ON pk.NOMOR=jd.KUNJUNGAN AND jd.`STATUS`!=0
                        LEFT JOIN `master`.ruangan r ON jd.RUANGAN=r.ID AND r.`STATUS`!=0
                            , pendaftaran.tujuan_pasien tp
                                LEFT JOIN master.ruangan rg ON tp.RUANGAN=rg.ID AND rg.JENIS=5
                                LEFT JOIN master.dokter drreg ON tp.DOKTER=drreg.ID
                                LEFT JOIN master.pegawai pdr ON pdr.NIP=drreg.NIP
                        ,  master.pasien p
                                LEFT JOIN master.referensi rjk ON p.JENIS_KELAMIN=rjk.ID AND rjk.JENIS=2
                                #LEFT JOIN master.keluarga_pasien kps ON kps.NORM AND p.NORM
                            , (SELECT mp.NAMA, ai.PPK, w.DESKRIPSI KOTA, mp.ALAMAT , mp.TELEPON, mp.FAX
                                        , ai.EMAIL, ai.WEBSITE
                                        FROM aplikasi.instansi ai
                                            , master.ppk mp
                                            , master.wilayah w
                                        WHERE ai.PPK=mp.ID AND mp.WILAYAH=w.ID) inst
                        WHERE pd.NOMOR=@PNOPEN  AND pd.`STATUS`!=0 AND pd.NORM=p.NORM
                            AND pd.NOMOR=tp.NOPEN
                ");

                $resumeRadarRow = $mainRows[0] ?? null;

                if (!$resumeRadarRow) {
                    $dbCustom->statement('DROP TEMPORARY TABLE IF EXISTS TEMP_DATA_HASIL_LAB');
                    return response()->json([
                        'success' => false,
                        'message' => 'Data Resume Medis Radar dari query resume utama tidak ditemukan.',
                    ], 404);
                }

                $dbCustom->statement('DROP TEMPORARY TABLE IF EXISTS TEMP_DATA_HASIL_LAB');

                /* =============================================================
                 * B. DIAGNOSIS UTAMA & SEKUNDER
                 *    Pengganti medicalrecord.CetakMR2Diagnosa(PNOPEN, PUTAMA).
                 *    Temp table dan query ditulis langsung di function ini.
                 * =========================================================== */
                foreach ([1, 2] as $utama) {
                    $dbCustom->statement('SET @PNOPEN = ?', [$nopen]);
                    $dbCustom->statement('SET @PUTAMA = ?', [$utama]);
                    $dbCustom->statement('SET @id=0, @idd=0, @idf=0, @iddf=0, @id1=0, @idd1=0, @idf1=0, @iddf1=0, @idg=0');

                    $dbCustom->statement('DROP TEMPORARY TABLE IF EXISTS TEMP_DATA');
                    $dbCustom->statement('DROP TEMPORARY TABLE IF EXISTS TEMP_DATA_PROSEDUR');
                    $dbCustom->statement('DROP TEMPORARY TABLE IF EXISTS TEMP_DATA_FARMASI');
                    $dbCustom->statement('DROP TEMPORARY TABLE IF EXISTS TEMP_DATA_FARMASI_TEMP');
                    $dbCustom->statement('DROP TEMPORARY TABLE IF EXISTS TEMP_DATA_ALL');

                    $dbCustom->statement("
                        CREATE TEMPORARY TABLE TEMP_DATA ENGINE=MEMORY
                        SELECT NOPEN, BARIS, IDDIAGNOSA
                        FROM (
                            SELECT r.NOPEN,
                                IF(@idd1 != jt.ID, @id1 := 1, @id1 := @id1 + 1) AS BARIS,
                                @idd1 := jt.ID AS IDDIAGNOSA,
                                jt.UTAMA
                            FROM medicalrecord.`resume` r,
                                JSON_TABLE(r.DIAGNOSA_PROSEDUR,
                                    '$[*]' COLUMNS(
                                        ID INT PATH '$.ID',
                                        UTAMA TINYINT PATH '$.UTAMA',
                                        NESTED PATH '$.PROSEDUR[*]' COLUMNS(
                                            IDPROSEDUR VARCHAR(10) PATH '$.IDPROSEDUR',
                                            TINDAKANMEDIS CHAR(11) PATH '$.TINDAKANMEDIS'
                                        )
                                    )
                                ) AS jt
                            WHERE r.NOPEN = @PNOPEN AND jt.UTAMA = @PUTAMA

                            UNION ALL

                            SELECT r.NOPEN,
                                IF(@iddf1 != jt.ID, @idf1 := 1, @idf1 := @idf1 + 1) AS BARIS,
                                @iddf1 := jt.ID AS IDDIAGNOSA,
                                jt.UTAMA
                            FROM medicalrecord.`resume` r,
                                JSON_TABLE(r.DIAGNOSA_FARMASI,
                                    '$[*]' COLUMNS(
                                        ID INT PATH '$.ID',
                                        UTAMA TINYINT PATH '$.UTAMA',
                                        NESTED PATH '$.FARMASI[*]' COLUMNS(
                                            IDFARMASI CHAR(11) PATH '$.IDFARMASI'
                                        )
                                    )
                                ) AS jt
                            WHERE r.NOPEN = @PNOPEN AND jt.UTAMA = @PUTAMA
                        ) AS cd
                        GROUP BY NOPEN, IDDIAGNOSA, BARIS
                    ");

                    $dbCustom->statement("
                        CREATE TEMPORARY TABLE TEMP_DATA_ALL ENGINE=MEMORY
                        SELECT NOPEN, IDDIAGNOSA
                        FROM (
                            SELECT r.NOPEN, jt.ID AS IDDIAGNOSA, jt.UTAMA
                            FROM medicalrecord.`resume` r,
                                JSON_TABLE(r.DIAGNOSA_PROSEDUR,
                                    '$[*]' COLUMNS(
                                        ID INT PATH '$.ID',
                                        UTAMA TINYINT PATH '$.UTAMA',
                                        NESTED PATH '$.PROSEDUR[*]' COLUMNS(
                                            IDPROSEDUR VARCHAR(10) PATH '$.IDPROSEDUR',
                                            TINDAKANMEDIS CHAR(11) PATH '$.TINDAKANMEDIS'
                                        )
                                    )
                                ) AS jt
                            WHERE r.NOPEN = @PNOPEN AND jt.UTAMA = @PUTAMA

                            UNION ALL

                            SELECT r.NOPEN, jt.ID AS IDDIAGNOSA, jt.UTAMA
                            FROM medicalrecord.`resume` r,
                                JSON_TABLE(r.DIAGNOSA_FARMASI,
                                    '$[*]' COLUMNS(
                                        ID INT PATH '$.ID',
                                        UTAMA TINYINT PATH '$.UTAMA',
                                        NESTED PATH '$.FARMASI[*]' COLUMNS(
                                            IDFARMASI CHAR(11) PATH '$.IDFARMASI'
                                        )
                                    )
                                ) AS jt
                            WHERE r.NOPEN = @PNOPEN AND jt.UTAMA = @PUTAMA
                        ) AS cd
                        GROUP BY NOPEN, IDDIAGNOSA
                    ");

                    $dbCustom->statement("
                        CREATE TEMPORARY TABLE TEMP_DATA_PROSEDUR ENGINE=MEMORY
                        SELECT r.NOPEN,
                            IF(@idd != jt.ID, @id := 1, @id := @id + 1) AS BARIS,
                            @idd := jt.ID AS IDDIAGNOSA,
                            jt.UTAMA,
                            jt.TINDAKANMEDIS,
                            jt.IDPROSEDUR
                        FROM medicalrecord.`resume` r,
                            JSON_TABLE(r.DIAGNOSA_PROSEDUR,
                                '$[*]' COLUMNS(
                                    ID INT PATH '$.ID',
                                    UTAMA TINYINT PATH '$.UTAMA',
                                    NESTED PATH '$.PROSEDUR[*]' COLUMNS(
                                        IDPROSEDUR VARCHAR(10) PATH '$.IDPROSEDUR',
                                        TINDAKANMEDIS CHAR(11) PATH '$.TINDAKANMEDIS'
                                    )
                                )
                            ) AS jt
                        WHERE r.NOPEN = @PNOPEN AND jt.UTAMA = @PUTAMA
                    ");

                    $dbCustom->statement("
                        CREATE TEMPORARY TABLE TEMP_DATA_FARMASI ENGINE=MEMORY
                        SELECT r.NOPEN, jt.ID AS IDDIAGNOSA, jt.UTAMA, jt.IDFARMASI
                        FROM medicalrecord.`resume` r,
                            JSON_TABLE(r.DIAGNOSA_FARMASI,
                                '$[*]' COLUMNS(
                                    ID INT PATH '$.ID',
                                    UTAMA TINYINT PATH '$.UTAMA',
                                    NESTED PATH '$.FARMASI[*]' COLUMNS(
                                        IDFARMASI CHAR(11) PATH '$.IDFARMASI'
                                    )
                                )
                            ) AS jt
                        WHERE r.NOPEN = @PNOPEN AND jt.UTAMA = @PUTAMA
                    ");

                    $dbCustom->statement("
                        CREATE TEMPORARY TABLE TEMP_DATA_FARMASI_TEMP ENGINE=MEMORY
                        SELECT NOPEN,
                            IF(@iddf != ef.IDDIAGNOSA, @idf := 1, @idf := @idf + 1) AS BARIS,
                            @iddf := ef.IDDIAGNOSA AS IDDIAGNOSA,
                            ef.UTAMA,
                            ef.IDFARMASI,
                            ef.NAMABARANG
                        FROM (
                            SELECT DISTINCT
                                fr.NOPEN,
                                fr.IDDIAGNOSA,
                                fr.UTAMA,
                                fr.IDFARMASI,
                                ib.NAMA AS NAMABARANG
                            FROM TEMP_DATA_FARMASI fr
                            LEFT JOIN layanan.farmasi f
                                ON fr.IDFARMASI = f.ID
                                AND f.`STATUS` != 0
                            LEFT JOIN inventory.barang ib
                                ON f.FARMASI = ib.ID
                            WHERE fr.NOPEN = @PNOPEN
                            AND fr.UTAMA = @PUTAMA
                        ) AS ef
                    ");

                    /* Hasil akhir diagnosis dari query CetakMR2Diagnosa */
                    $diagnosaRows = $dbCustom->select("
                        SELECT *
                        FROM (
                            SELECT NOPEN, BARIS, TINDAKANMEDIS, IDPROSEDUR, IDFARMASI,
                                IF(@idg != IDDIAGNOSA, KODE_DIAGNOSA, '') AS KODE_DIAGNOSA,
                                IF(@idg != IDDIAGNOSA, DESKRIPSI_DIAGNOSA, '') AS DESKRIPSI_DIAGNOSA,
                                @idg := IDDIAGNOSA AS IDDIAGNOSA,
                                DIAGNOSA, NAMATINDAKAN, KODE_PROSEDUR, DESKRIPSI_PROSEDUR, NAMABARANG
                            FROM (
                                SELECT d.NOPEN, d.BARIS, p.TINDAKANMEDIS, p.IDPROSEDUR, frs.IDFARMASI,
                                    dg.KODE AS KODE_DIAGNOSA,
                                    `master`.getDeskripsiICD(dg.KODE) AS DESKRIPSI_DIAGNOSA,
                                    d.IDDIAGNOSA,
                                    dg.DIAGNOSA,
                                    t.NAMA AS NAMATINDAKAN,
                                    pr.KODE AS KODE_PROSEDUR,
                                    `master`.getDeskripsiICD(pr.KODE) AS DESKRIPSI_PROSEDUR,
                                    frs.NAMABARANG
                                FROM TEMP_DATA d
                                LEFT JOIN TEMP_DATA_PROSEDUR p
                                    ON d.NOPEN = p.NOPEN AND d.IDDIAGNOSA = p.IDDIAGNOSA AND d.BARIS = p.BARIS
                                LEFT JOIN TEMP_DATA_FARMASI_TEMP frs
                                    ON d.NOPEN = frs.NOPEN AND d.IDDIAGNOSA = frs.IDDIAGNOSA AND d.BARIS = frs.BARIS
                                LEFT JOIN layanan.tindakan_medis tm
                                    ON p.TINDAKANMEDIS = tm.ID AND tm.`STATUS` != 0
                                LEFT JOIN `master`.tindakan t ON tm.TINDAKAN = t.ID
                                LEFT JOIN medicalrecord.prosedur pr
                                    ON p.IDPROSEDUR = pr.ID AND pr.`STATUS` != 0 AND pr.INA_GROUPER = 0,
                                    medicalrecord.diagnosa dg
                                WHERE d.IDDIAGNOSA = dg.ID
                                AND d.NOPEN = dg.NOPEN
                                AND dg.`STATUS` != 0
                                AND dg.INA_GROUPER = 0
                                AND dg.NOPEN = @PNOPEN
                                AND dg.UTAMA = @PUTAMA
                                AND dg.INACBG = 1
                                ORDER BY IDDIAGNOSA, BARIS
                            ) AS ab

                            UNION ALL

                            SELECT ds.NOPEN, 1 AS BARIS, NULL AS TINDAKANMEDIS, NULL AS IDPROSEDUR,
                                NULL AS IDFARMASI, ds.KODE AS KODE_DIAGNOSA,
                                `master`.getDeskripsiICD(ds.KODE) AS DESKRIPSI_DIAGNOSA,
                                ds.ID AS IDDIAGNOSA, ds.DIAGNOSA,
                                NULL AS NAMATINDAKAN, NULL AS KODE_PROSEDUR,
                                NULL AS DESKRIPSI_PROSEDUR, NULL AS NAMABARANG
                            FROM medicalrecord.diagnosa ds
                            WHERE ds.NOPEN = @PNOPEN
                            AND ds.UTAMA = @PUTAMA
                            AND ds.`STATUS` != 0
                            AND ds.INA_GROUPER = 0
                            AND ds.INACBG = 1
                            AND ds.ID NOT IN (
                                SELECT IDDIAGNOSA FROM TEMP_DATA_ALL WHERE IDDIAGNOSA = ds.ID
                            )
                        ) AS cd
                        WHERE KODE_DIAGNOSA != '' OR NAMATINDAKAN IS NOT NULL OR NAMABARANG IS NOT NULL
                    ");

                    if ($utama === 1) {
                        $diagnosaUtamaRows = $diagnosaRows;
                    } else {
                        $diagnosaSekunderRows = $diagnosaRows;
                    }

                    $dbCustom->statement('DROP TEMPORARY TABLE IF EXISTS TEMP_DATA');
                    $dbCustom->statement('DROP TEMPORARY TABLE IF EXISTS TEMP_DATA_PROSEDUR');
                    $dbCustom->statement('DROP TEMPORARY TABLE IF EXISTS TEMP_DATA_FARMASI');
                    $dbCustom->statement('DROP TEMPORARY TABLE IF EXISTS TEMP_DATA_FARMASI_TEMP');
                    $dbCustom->statement('DROP TEMPORARY TABLE IF EXISTS TEMP_DATA_ALL');
                }

                /* =============================================================
                 * C. PROSEDUR LAIN
                 *    Pengganti medicalrecord.CetakMR2ProsedurLain.
                 *    TEMP_DATA_PROSEDUR diganti dengan NOT EXISTS + JSON_TABLE.
                 * =========================================================== */
                $prosedurRows = $dbCustom->select("
                    SELECT pr.NOPEN,
                        pr.KODE AS KODE_PROSEDUR,
                        IFNULL(`master`.getDeskripsiICD(pr.KODE), pr.TINDAKAN) AS DESKRIPSI_PROSEDUR
                    FROM medicalrecord.prosedur pr
                    WHERE pr.`STATUS` != 0
                    AND pr.INA_GROUPER = 0
                    AND pr.NOPEN = ?
                    AND pr.INACBG = 1
                    AND NOT EXISTS (
                        SELECT 1
                        FROM medicalrecord.`resume` r,
                            JSON_TABLE(r.DIAGNOSA_PROSEDUR,
                                '$[*]' COLUMNS(
                                    ID INT PATH '$.ID',
                                    UTAMA INT PATH '$.UTAMA',
                                    NESTED PATH '$.PROSEDUR[*]' COLUMNS(
                                        IDPROSEDUR VARCHAR(10) PATH '$.IDPROSEDUR'
                                    )
                                )
                            ) AS jt
                        WHERE r.NOPEN = pr.NOPEN
                            AND jt.IDPROSEDUR = pr.ID
                    )
                ", [$nopen]);
            }

            /* ---------------------------------------------------------------
            * 5. FOLDER OUTPUT (POLA LAMA COMPILE RESUME RJ)
            * ------------------------------------------------------------- */
            $tanggalFolder = $resume->TGLPERIKSA
                ? Carbon::parse($resume->TGLPERIKSA)
                : Carbon::now();

            $relativePath = 'files/resume/RJ/'
                . $tanggalFolder->format('Y') . '/'
                . $tanggalFolder->format('m') . '/'
                . $tanggalFolder->format('d') . '/'
                . $kunjungan;

            $output = storage_path('app/public/' . $relativePath);
            $outputDir = dirname($output);

            if (!File::exists($outputDir)) {
                File::makeDirectory($outputDir, 0755, true);
            }

            $finalPdf = $output . '.pdf';
            if (File::exists($finalPdf)) {
                File::delete($finalPdf);
            }

            $templateRadar = public_path('doc/klaim/resumemedis/rawatdarurat.docx');
            $templateRj = public_path('doc/klaim/resumemedis/rawatjalan.docx');
            $templatePath = $isRadar ? $templateRadar : $templateRj;

            if (!File::exists($templatePath)) {
                throw new \RuntimeException('Template DOCX Resume Medis tidak ditemukan: ' . $templatePath);
            }

            // Logo mengikuti lokasi gambar yang direferensikan oleh JRXML lama.
            $logoPath = public_path(
                ($isRadar ? 'doc/klaim/resumemedis' : 'doc/klaim/resumemedis') . '/logo-pku.png'
            );

            /* ---------------------------------------------------------------
            * 6. HELPER GENERATE DOCX -> PDF
            * ------------------------------------------------------------- */
            $generateDocxPdf = function (
                string $template,
                string $basePath,
                array $values,
                array $repeatRows,
                array $repeatKeys,
                ?string $signaturePath
            ) use ($clean, &$temporaryDocs, &$temporaryFiles, $outputDir, $logoPath) {
                $templateProcessor = new TemplateProcessor($template);

                // SetValue harus menggunakan 2 parameter. PHPWord yang melakukan XML escaping.
                foreach ($values as $key => $value) {
                    $templateProcessor->setValue($key, $clean($value, ''));
                }

                if (is_file($logoPath)) {
                    self::setImgWord($templateProcessor, 'LOGO_RS', $logoPath, 52);
                } else {
                    $templateProcessor->setValue('LOGO_RS', '');
                }

                // Gandakan baris diagnosis/prosedur/obat sesuai jumlah hasil query.
                foreach ($repeatKeys as $marker => $keys) {
                    $rows = $repeatRows[$marker] ?? [];
                    if (empty($rows)) {
                        $empty = [];
                        foreach ($keys as $key) {
                            $empty[$key] = '-';
                        }
                        $rows = [$empty];
                    }

                    $templateProcessor->cloneRow($marker, count($rows));

                    foreach ($rows as $index => $rowValues) {
                        $suffix = '#' . ($index + 1);
                        foreach ($keys as $key) {
                            $templateProcessor->setValue(
                                $key . $suffix,
                                $clean($rowValues[$key] ?? '-', '-')
                            );
                        }
                    }
                }

                if ($signaturePath && is_file($signaturePath)) {
                    self::setImgWord($templateProcessor, 'TTD_DOKTER', $signaturePath, 125);
                } else {
                    throw new \RuntimeException('File tanda tangan dokter tidak tersedia untuk template Resume Medis.');
                }

                $docxPath = $basePath . '.docx';
                $pdfPath = $basePath . '.pdf';
                $temporaryDocs[] = $docxPath;
                $temporaryFiles[] = $pdfPath;

                // Bersihkan hasil lama agar pemeriksaan PDF tidak membaca file stale.
                foreach ([$docxPath, $pdfPath] as $oldFile) {
                    if (File::exists($oldFile)) {
                        File::delete($oldFile);
                    }
                }

                $templateProcessor->saveAs($docxPath);
                clearstatcache(true, $docxPath);

                if (!File::exists($docxPath) || File::size($docxPath) === 0) {
                    throw new \RuntimeException('Gagal membuat DOCX Resume Medis: ' . $docxPath);
                }

                [$success, $convertLog, $exitCode] = $this->libreOffice->generatePdf(
                    $docxPath,
                    $outputDir
                );

                clearstatcache(true, $pdfPath);
                if (!$success || !File::exists($pdfPath) || File::size($pdfPath) === 0) {
                    throw new \RuntimeException(
                        'Gagal konversi DOCX ke PDF: ' . basename($docxPath)
                        . ' | exit=' . (string) $exitCode
                        . ' | log=' . (string) $convertLog
                    );
                }

                if (File::exists($docxPath)) {
                    File::delete($docxPath);
                }

                return $pdfPath;
            };

            /* ---------------------------------------------------------------
            * 7. FORMAT BARIS DIAGNOSA/PROSEDUR RADAR
            * ------------------------------------------------------------- */
            $formatCodeDescriptionRows = function (
                array $rows,
                string $sourceCodeKey,
                string $outputCodeKey,
                array $descriptionKeys,
                $fallbackCode = null,
                $fallbackDescription = null
            ) use ($read, $clean) {
                $result = [];
                foreach ($rows as $row) {
                    $code = $clean($read($row, $sourceCodeKey), '');
                    $description = '';
                    foreach ($descriptionKeys as $descriptionKey) {
                        $candidate = $clean($read($row, $descriptionKey), '');
                        if ($candidate !== '') {
                            $description = $candidate;
                            break;
                        }
                    }
                    if ($code !== '' || $description !== '') {
                        $result[] = [$outputCodeKey => $code ?: '-', 'DESC' => $description ?: '-'];
                    }
                }

                if (empty($result) && ($fallbackCode !== null || $fallbackDescription !== null)) {
                    $code = $clean($fallbackCode, '');
                    $description = $clean($fallbackDescription, '');
                    if ($code !== '' || $description !== '') {
                        $result[] = [$outputCodeKey => $code ?: '-', 'DESC' => $description ?: '-'];
                    }
                }

                return $result;
            };

            /* ---------------------------------------------------------------
            * 8. GENERATE RESUME RADAR ATAU RESUME RJ
            * ------------------------------------------------------------- */
            if ($isRadar) {
                $row = $resumeRadarRow;

                $diagUtama = $formatCodeDescriptionRows(
                    $diagnosaUtamaRows,
                    'KODE_DIAGNOSA',
                    'DIAG_UTAMA_KODE',
                    ['DESKRIPSI_DIAGNOSA', 'DIAGNOSA'],
                    $read($row, 'KODEDIAGNOSAUTAMA'),
                    $read($row, 'DIAGNOSAUTAMA')
                );
                $diagSekunder = $formatCodeDescriptionRows(
                    $diagnosaSekunderRows,
                    'KODE_DIAGNOSA',
                    'DIAG_SEK_KODE',
                    ['DESKRIPSI_DIAGNOSA', 'DIAGNOSA'],
                    $read($row, 'KODEDIAGNOSASEKUNDER'),
                    $read($row, 'DIAGNOSASEKUNDER')
                );
                $prosedur = $formatCodeDescriptionRows(
                    $prosedurRows,
                    'KODE_PROSEDUR',
                    'PROSEDUR_KODE',
                    ['DESKRIPSI_PROSEDUR', 'NAMATINDAKAN'],
                    null,
                    null
                );

                $radarRepeatRows = [
                    'DIAG_UTAMA_KODE' => array_map(fn ($item) => [
                        'DIAG_UTAMA_KODE' => $item['DIAG_UTAMA_KODE'] ?? '-',
                        'DIAG_UTAMA_DESC' => $item['DESC'] ?? '-',
                    ], $diagUtama),
                    'DIAG_SEK_KODE' => array_map(fn ($item) => [
                        'DIAG_SEK_KODE' => $item['DIAG_SEK_KODE'] ?? '-',
                        'DIAG_SEK_DESC' => $item['DESC'] ?? '-',
                    ], $diagSekunder),
                    'PROSEDUR_KODE' => array_map(fn ($item) => [
                        'PROSEDUR_KODE' => $item['PROSEDUR_KODE'] ?? '-',
                        'PROSEDUR_DESC' => $item['DESC'] ?? '-',
                    ], $prosedur),
                    'OBAT_NAMA' => array_map($formatMedicineRadar, $obatRows),
                ];

                $title = $read($row, 'RUANG_RAWAT_TERAKHIR') === null
                    ? 'RESUME MEDIS RAWAT DARURAT'
                    : 'RESUME MEDIS RAWAT INAP';

                $values = [
                    'TITLE' => $title,
                    'NAMAINSTANSI' => $read($row, 'NAMAINSTANSI'),
                    'ALAMATINST' => $read($row, 'ALAMATINST', $read($row, 'ALAMAT')),
                    'NAMALENGKAP' => $read($row, 'NAMALENGKAP'),
                    'NORM' => $read($row, 'NORM'),
                    'TANGGAL_LAHIR' => $read($row, 'TANGGAL_LAHIR'),
                    'JENISKELAMIN' => $read($row, 'JENISKELAMIN'),
                    'TGL_DAFTAR' => $read($row, 'TGL_DAFTAR_AWAL') ?: $read($row, 'TGLREG'),
                    'TGL_KELUAR' => $read($row, 'TGLKELUAR') ?: $read($row, 'TANGGALKELUAR'),
                    'RUANG_RAWAT' => $read($row, 'RUANG_RAWAT_TERAKHIR') ?: '-',
                    'CARA_BAYAR' => $read($row, 'CARA_BAYAR'),
                    'INDIKASI_RANAP' => $read($row, 'INDIKASI_RANAP') ?: $read($row, 'INDIKASI_RAWAT_INAP') ?: '-',
                    'ANAMNESIS' => $read($row, 'ANAMNESIS') ?: 'Tidak Ada',
                    'RPS' => $read($row, 'RPS') ?: 'Tidak Ada',
                    'RPP' => $read($row, 'RPP') ?: 'Tidak Ada',
                    'ALERGI' => $read($row, 'ALERGI') ?: 'Tidak Ada',
                    'FISIK' => $read($row, 'FISIK') ?: 'Tidak Ada',
                    'BERKAS_LAB' => $read($row, 'BERKAS_LAB') ?: $read($row, 'LAB', 'Tidak Ada'),
                    'BERKAS_RAD' => $read($row, 'BERKAS_RAD') ?: $read($row, 'RAD', 'Tidak Ada'),
                    'PENUNJANG_LAIN' => $read($row, 'DESKRIPSI_HASIL_PENUNJANG_LAINYA') ?: 'Tidak Ada',
                    'KETERANGAN_ICD10' => $read($row, 'DIAGNOSA_DOKTER_ICD10') ?: 'Tidak Ada',
                    'KETERANGAN_ICD9' => $read($row, 'DIAGNOSA_DOKTER_ICD9') ?: 'Tidak Ada',
                    'RENCANA_TERAPI' => $read($row, 'RENCANA_TERAPI') ?: $read($row, 'RENCANA_TERAPI', 'Tidak Ada'),
                    'DESKRIPSI_KONSUL' => $read($row, 'DESKRIPSI_KONSUL') ?: $read($row, 'KONSULTASI', 'Tidak Ada'),
                    'TEKANAN_DARAH' => $read($row, 'TEKANAN_DARAH'),
                    'FREKUENSI_NADI' => $read($row, 'FREKUENSI_NADI'),
                    'FREKUENSI_NAFAS' => $read($row, 'FREKUENSI_NAFAS'),
                    'SUHU' => $read($row, 'SUHU'),
                    'KESADARAN' => $read($row, 'TINGKAT_KESADARAN') ?: $read($row, 'KEADAANUMUM') ?: 'Tidak Ada',
                    'NYERI' => $read($row, 'NYERI') ?: 'Tidak Ada',
                    'CHK_SEMBUH' => $checked($row, 'SEMBUH'),
                    'CHK_BELUM_SEMBUH' => $checked($row, 'BELUM_SEMBUH'),
                    'CHK_MEMBAIK' => $checked($row, 'MEMBAIK'),
                    'CHK_MENINGGAL_KURANG48' => $checked($row, 'MENINGGAL_KURANG48'),
                    'CHK_MENINGGAL_LEBIH48' => $checked($row, 'MENINGGAL_LEBIH48'),
                    'CHK_DIIJINKAN_PULANG' => $checked($row, 'DIIJINKAN_PULANG'),
                    'CHK_PULANG_PAKSA' => $checked($row, 'PULANG_PAKSA'),
                    'CHK_PINDAH_RS' => $checked($row, 'PINDAH_RS'),
                    'CHK_DIRUJUK' => $checked($row, 'DIRUJUK'),
                    'CHK_LAIN_LAIN' => $checked($row, 'LAIN_LAIN'),
                    'CHK_POLIKLINIK_RS' => (string) $read($row, 'POLIKLINIK_RS', '0') === '1' ? '☑' : '☐',
                    'TERAPI_PULANG' => (string) ($read($row, 'RESEPPULANG') ?? (count($obatRows) > 0 ? 1 : 0)) === '0'
                        ? 'Terapi Pulang Tidak Ada'
                        : 'Terapi Pulang',
                    'TANGGAL_KONTROL' => $read($row, 'TANGGAL_KONTROL') ?: 'Tidak Ada',
                    'RUANG_KONTROL' => $read($row, 'RUANG_KONTROL') ?: 'Tidak Ada',
                    'NOMOR_KONTROL' => $read($row, 'NOMOR_KONTROL') ?: 'Tidak Ada',
                    'NOMOR_REFERENSI' => $read($row, 'NOMOR_REFERENSI') ?: 'Tidak Ada',
                    'EDUKASI' => $read($row, 'EDUKASI') ?: 'Tidak Ada',
                    'TGLSURAT' => $read($row, 'TGLSURAT') ?: Carbon::now()->isoFormat('D MMMM YYYY'),
                    'NAMA_KELUARGA_PASIEN' => $read($row, 'NAMA_KELUARGA_PASIEN') ?: '-',
                    'DOKTER' => $read($row, 'DOKTER') ?: $resume->NAMADOKTER,
                ];

                $base = $output . '_main';
                $generateDocxPdf(
                    $templatePath,
                    $base,
                    $values,
                    $radarRepeatRows,
                    [
                        'DIAG_UTAMA_KODE' => ['DIAG_UTAMA_KODE', 'DIAG_UTAMA_DESC'],
                        'DIAG_SEK_KODE' => ['DIAG_SEK_KODE', 'DIAG_SEK_DESC'],
                        'PROSEDUR_KODE' => ['PROSEDUR_KODE', 'PROSEDUR_DESC'],
                        'OBAT_NAMA' => ['OBAT_NAMA', 'OBAT_JUMLAH', 'OBAT_DOSIS', 'OBAT_FREKUENSI', 'OBAT_CARA', 'OBAT_RACIKAN'],
                    ],
                    $signatureMain
                );
            } else {
                // Rawat jalan tetap mencetak resume kunjungan utama dan halaman kunjungan konsul,
                // seperti compileResumeRj() lama.
                $visits = collect([[
                    'KUNJUNGAN' => $resume->NOMOR,
                    'DPJP' => $resume->DPJP,
                    'NIPDOKTER' => $resume->NIPDOKTER,
                    'NAMADOKTER' => $resume->NAMADOKTER,
                    'IS_MAIN' => true,
                ]]);

                $konsulVisits = DB::table('pendaftaran.konsul AS kon')
                    ->leftJoin('pendaftaran.kunjungan AS pk', 'pk.REF', '=', 'kon.NOMOR')
                    ->leftJoin('master.dokter AS md', 'md.ID', '=', 'pk.DPJP')
                    ->select(
                        'pk.NOMOR AS KUNJUNGAN',
                        'pk.DPJP AS DPJP',
                        'pk.RUANGAN AS RUANGAN',
                        DB::raw('master.getNamaLengkapPegawai(md.NIP) AS NAMADOKTER'),
                        'md.NIP AS NIPDOKTER'
                    )
                    ->where('kon.KUNJUNGAN', $kunjungan)
                    ->where('kon.STATUS', '!=', '0')
                    ->get()
                    ->map(fn ($item) => [
                        'KUNJUNGAN' => $item->KUNJUNGAN,
                        'DPJP' => $item->DPJP,
                        'NIPDOKTER' => $item->NIPDOKTER,
                        'NAMADOKTER' => $item->NAMADOKTER,
                        'IS_MAIN' => false,
                    ]);

                $visits = $visits->concat($konsulVisits);
                $rjRepeatRows = [
                    'OBAT_NAMA' => array_map($formatMedicineRj, $obatRows),
                ];

                foreach ($visits as $index => $visit) {
                    $visitNumber = (string) $visit['KUNJUNGAN'];
                    $doctorId = $visit['DPJP'] ?? null;
                    $doctorNip = $visit['NIPDOKTER'] ?? null;
                    $doctorName = $visit['NAMADOKTER'] ?? $resume->NAMADOKTER;
                    $isMainVisit = (bool) ($visit['IS_MAIN'] ?? false);

                    $signaturePath = $isMainVisit
                        ? $signatureMain
                        : $getSignature($visitNumber, $doctorId, $doctorNip, $doctorName, true);

                    if (!$signaturePath) {
                        foreach (array_unique(array_merge($temporaryDocs, $temporaryFiles)) as $temporaryFile) {
                            if (File::exists($temporaryFile)) {
                                File::delete($temporaryFile);
                            }
                        }

                        return response()->json([
                            'success' => false,
                            'message' => "Tanda tangan Dokter DPJP ({$doctorName}) pada Resume Konsul tidak ditemukan.",
                        ], 404);
                    }

                    $rjRows = $dbCustom->select(
                        'CALL simrspku_klaim.CetakMR5Custom(?, ?)',
                        [$nopen, $visitNumber]
                    );
                    $row = $rjRows[0] ?? null;

                    if (!$row) {
                        throw new \RuntimeException(
                            'Data CetakMR5Custom tidak ditemukan untuk kunjungan ' . $visitNumber
                        );
                    }

                    $values = [
                        'NAMAINSTANSI' => $read($row, 'NAMAINSTANSI'),
                        'ALAMAT' => $read($row, 'ALAMAT'),
                        'TGLMASUK' => $read($row, 'TGLMASUK'),
                        'NAMAPASIEN' => $read($row, 'NAMAPASIEN'),
                        'NORM' => $read($row, 'NORM'),
                        'TANGGAL_LAHIR' => $read($row, 'TANGGAL_LAHIR'),
                        'UNIT' => $read($row, 'UNIT'),
                        'KELUHAN' => $read($row, 'KELUHAN') ?: 'Tidak Ada',
                        'DARAH' => $read($row, 'DARAH'),
                        'FREKUENSI_NADI' => $read($row, 'FREKUENSI_NADI'),
                        'SUHU' => $read($row, 'SUHU'),
                        'FREKUENSI_NAFAS' => $read($row, 'FREKUENSI_NAFAS'),
                        'SATURASIO2' => $read($row, 'SATURASIO2'),
                        'ABN' => (string) $read($row, 'ABN', '') === '2' ? 'Ya' : 'Tidak',
                        'KEADAAN_UMUM' => $read($row, 'KEADAAN_UMUM') ?: '-',
                        'TGLPERIKSA' => $read($row, 'TGLPERIKSA'),
                        'JAMPERIKSA' => $read($row, 'JAMPERIKSA'),
                        'ASSESMENT' => $read($row, 'ASSESMENT') ?: '-',
                        'SUBYEKTIF' => $read($row, 'SUBYEKTIF') ?: '-',
                        'OBYEKTIF' => $read($row, 'OBYEKTIF') ?: '-',
                        'PLANNING' => $read($row, 'PLANNING') ?: '-',
                        'INSTRUKSI' => $read($row, 'INSTRUKSI') ?: '-',
                        'TINDAKAN' => $read($row, 'TINDAKAN') ?: '-',
                        'KONSUL' => $read($row, 'KONSUL') ?: '-',
                        'DOKTER' => $read($row, 'DOKTER') ?: $doctorName,
                    ];

                    $suffix = $isMainVisit ? '_main' : '_konsul_' . preg_replace('/[^A-Za-z0-9_-]/', '_', $visitNumber);
                    $base = $output . $suffix;

                    $generateDocxPdf(
                        $templatePath,
                        $base,
                        $values,
                        $rjRepeatRows,
                        [
                            'OBAT_NAMA' => ['OBAT_NAMA', 'OBAT_JUMLAH', 'OBAT_ATURAN', 'OBAT_CATATAN'],
                        ],
                        $signaturePath
                    );
                }
            }

            /* ---------------------------------------------------------------
            * 9. MERGE PDF UTAMA + PDF KONSUL
            * ------------------------------------------------------------- */
            if (empty($temporaryFiles)) {
                throw new \RuntimeException('Tidak ada halaman Resume Medis yang berhasil dibuat.');
            }

            $pdf = new Fpdi();
            foreach ($temporaryFiles as $temporaryPdf) {
                if (!File::exists($temporaryPdf) || File::size($temporaryPdf) === 0) {
                    continue;
                }

                $pageCount = $pdf->setSourceFile($temporaryPdf);
                for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                    $templateId = $pdf->importPage($pageNo);
                    $size = $pdf->getTemplateSize($templateId);
                    $orientation = $size['width'] > $size['height'] ? 'L' : 'P';
                    $pdf->AddPage($orientation, [$size['width'], $size['height']]);
                    $pdf->useTemplate($templateId, 0, 0, $size['width'], $size['height']);
                }
            }

            $pdf->Output($finalPdf, 'F');
            clearstatcache(true, $finalPdf);

            if (!File::exists($finalPdf) || File::size($finalPdf) === 0) {
                throw new \RuntimeException('PDF final Resume Medis gagal dibuat.');
            }

            /* ---------------------------------------------------------------
            * 10. SIMPAN / UPDATE BERKAS KLAIM (JENIS 2 SEPERTI FUNCTION LAMA)
            * ------------------------------------------------------------- */
            $verify = klaim_file::where('nomor', $kunjungan)
                ->where('jenis', 2)
                ->where('status', true)
                ->first();

            if (!$verify) {
                $post = new klaim_file();
                $post->jenis = 2;
                $post->nomor = $kunjungan;
                $post->title = $kunjungan . '.pdf';
                $post->filename = $relativePath . '.pdf';
                $post->status = true;
                $post->user = Auth::user()->ID;
                $post->save();
            }

            // Hapus semua berkas temporary; file PDF final tetap disimpan.
            foreach (array_unique(array_merge($temporaryDocs, $temporaryFiles)) as $temporaryFile) {
                if (File::exists($temporaryFile)) {
                    File::delete($temporaryFile);
                }
            }

            return response()->file($finalPdf, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $kunjungan . '_resume_medis.pdf"',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ]);
        } catch (\Throwable $e) {
            foreach (array_unique(array_merge($temporaryDocs, $temporaryFiles)) as $temporaryFile) {
                if (File::exists($temporaryFile)) {
                    File::delete($temporaryFile);
                }
            }

            Log::error('Generate Resume Medis gagal', [
                'kunjungan' => $kunjungan,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // ====================================================================================================================================
    // =========================================================  LABORATORIUM  ===========================================================
    // ====================================================================================================================================
    public function generateLab($kunjungan)
    {
        try {
            DB::beginTransaction();
            /*
            |--------------------------------------------------------------------------
            | DATA SEP / PENDAFTARAN
            |--------------------------------------------------------------------------
            */
            $getSEP = DB::table('pendaftaran.kunjungan AS pk')
                ->leftJoin(
                    'pendaftaran.pendaftaran AS pp',
                    'pp.NOMOR',
                    '=',
                    'pk.NOPEN'
                )
                ->leftJoin(
                    'pendaftaran.penjamin AS pj',
                    'pp.NOMOR',
                    '=',
                    'pj.NOPEN'
                )
                ->leftJoin(
                    'pembayaran.tagihan_pendaftaran AS tp',
                    'tp.PENDAFTARAN',
                    '=',
                    'pp.NOMOR'
                )
                ->select(
                    'pj.NOMOR AS NOSEP',
                    'pp.NOMOR AS NOPEN',
                    'tp.TAGIHAN',
                    'pk.MASUK AS TANGGALMASUK'
                )
                ->where('pk.NOMOR', $kunjungan)
                ->where(function ($q) {
                    $q->where('tp.STATUS', 1)
                        ->orWhere('tp.UTAMA', 1);
                })
                ->first();

            if (!$getSEP) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data SEP / Tagihan tidak ditemukan'
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | JUDUL DOKUMEN = NOSEP
            |--------------------------------------------------------------------------
            */

            $NODOKUMEN = trim((string) ($getSEP->NOSEP ?? ''));

            if ($NODOKUMEN === '') {
                $NODOKUMEN = (string) $kunjungan;
            }

            // Pastikan aman digunakan sebagai nama file.
            $NODOKUMEN = preg_replace(
                '/[^A-Za-z0-9._-]/',
                '_',
                $NODOKUMEN
            );

            if ($NODOKUMEN === '') {
                $NODOKUMEN = (string) $kunjungan;
            }

            /*
            |--------------------------------------------------------------------------
            | AMBIL SEMUA PENDAFTARAN DALAM TAGIHAN YANG SAMA
            |--------------------------------------------------------------------------
            */
            $listNopen = DB::table('pembayaran.tagihan_pendaftaran')
                ->where('TAGIHAN', $getSEP->TAGIHAN)
                ->where('STATUS', 1)
                ->pluck('PENDAFTARAN');

            if ($listNopen->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ditemukan pendaftaran dalam tagihan yang sama dengan Kunjungan Utama'
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | TANGGAL
            |--------------------------------------------------------------------------
            */
            $getTgl = Carbon::parse($getSEP->TANGGALMASUK);

            $tgl = $getTgl->isoFormat('DD');
            $bulan = $getTgl->isoFormat('MM');
            $tahun = $getTgl->isoFormat('YYYY');

            /*
            |--------------------------------------------------------------------------
            | AMBIL SEMUA TINDAKAN LAB
            |--------------------------------------------------------------------------
            */
            $show = DB::table('pendaftaran.pendaftaran AS pd')
                ->leftJoin(
                    'pendaftaran.kunjungan AS k',
                    'k.NOPEN',
                    '=',
                    'pd.NOMOR'
                )
                ->leftJoin(
                    'layanan.tindakan_medis AS tm',
                    'tm.KUNJUNGAN',
                    '=',
                    'k.NOMOR'
                )
                ->select(
                    'k.NOMOR AS NOMOR',
                    'tm.ID AS TINDAKAN'
                )
                ->whereIn('pd.NOMOR', $listNopen)
                ->where('k.RUANGAN', '=', '102040101')
                ->where('tm.STATUS', 1)
                ->get();

            if ($show->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada tindakan lab aktif'
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | GROUP BERDASARKAN NOMOR PEMERIKSAAN
            |--------------------------------------------------------------------------
            |
            | SATU NOMOR PEMERIKSAAN = SATU REPORT
            |
            | Contoh:
            |
            | 1020401012502100121
            |   - HEMATOLOGI
            |       - DARAH RUTIN
            |           - LEKOSIT
            |           - ERITROSIT
            |           - HEMOGLOBIN
            |       - dst
            |   - KIMIA DARAH
            |       - GULA DARAH SEWAKTU
            |           - GULA DARAH SEWAKTU
            |   - IMUNOLOGI / SEROLOGI
            |       - SALMONELLA IGM
            |           - SALMONELLA IGM
            |       - SALMONELLA IGG
            |           - SALMONELLA IGG
            |
            | 1020401012502110035
            |   - URINALISA
            |       - URIN LENGKAP
            |           - WARNA
            |           - KEJERNIHAN
            |           - dst
            |
            */
            $groupedData = $show
                ->groupBy('NOMOR')
                ->map(function ($group) {
                    return $group
                        ->pluck('TINDAKAN')
                        ->unique()
                        ->values()
                        ->all();
                });

            /*
            |--------------------------------------------------------------------------
            | PATH OUTPUT FINAL
            |--------------------------------------------------------------------------
            */
            $fileName = $NODOKUMEN . '_lab';

            $path = 'files/klaim/'
                . $tahun . '/'
                . $bulan . '/'
                . $tgl . '/'
                . $fileName;

            $output = storage_path(
                'app/public/' . $path
            );

            /*
            |--------------------------------------------------------------------------
            | DIRECTORY OUTPUT
            |--------------------------------------------------------------------------
            */
            $outputDir = dirname($output);

            if (!File::exists($outputDir)) {
                File::makeDirectory(
                    $outputDir,
                    0755,
                    true
                );
            }

            /*
            |--------------------------------------------------------------------------
            | TEMPORARY FILES
            |--------------------------------------------------------------------------
            |
            | SATU NOMOR PEMERIKSAAN = SATU PDF
            |
            */
            $temporaryFiles = [];

            /*
            |--------------------------------------------------------------------------
            | COUNTER HASIL
            |--------------------------------------------------------------------------
            */
            $totalTindakan = 0;

            /*
            |--------------------------------------------------------------------------
            | SUPPORT OBJECT / ARRAY
            |--------------------------------------------------------------------------
            */
            $getLabValue = function ($data, $key, $default = '') {

                if (is_array($data)) {
                    return $data[$key] ?? $default;
                }

                return $data->{$key} ?? $default;
            };

            /*
            |--------------------------------------------------------------------------
            | PROSES SETIAP NOMOR PEMERIKSAAN
            |--------------------------------------------------------------------------
            */
            foreach ($groupedData as $PNOMOR => $tindakanList) {

                /*
                |--------------------------------------------------------------------------
                | PASTIKAN ARRAY
                |--------------------------------------------------------------------------
                */
                if (!is_array($tindakanList)) {
                    $tindakanList = preg_split(
                        '/\s*,\s*/',
                        (string) $tindakanList,
                        -1,
                        PREG_SPLIT_NO_EMPTY
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | AMBIL SEMUA HASIL LAB DALAM SATU NOMOR PEMERIKSAAN
                |--------------------------------------------------------------------------
                */
                $hasil_lab = $this->cetakHasilLab(
                    $PNOMOR,
                    $tindakanList
                );

                /*
                |--------------------------------------------------------------------------
                | HANDLE JSON RESPONSE
                |--------------------------------------------------------------------------
                */
                if (
                    $hasil_lab instanceof
                    \Illuminate\Http\JsonResponse
                ) {
                    $hasil_lab = $hasil_lab->getData(true);
                }

                /*
                |--------------------------------------------------------------------------
                | VALIDASI HASIL
                |--------------------------------------------------------------------------
                */
                if (
                    !is_array($hasil_lab) ||
                    !isset($hasil_lab['data'])
                ) {
                    continue;
                }

                if (empty($hasil_lab['data'])) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | DATA LAB
                |--------------------------------------------------------------------------
                */
                $dataLab = collect(
                    $hasil_lab['data']
                );

                if ($dataLab->isEmpty()) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | DATA PERTAMA UNTUK HEADER
                |--------------------------------------------------------------------------
                */
                $lab = $dataLab->first();

                /*
                |--------------------------------------------------------------------------
                | DATA ANALIS
                |--------------------------------------------------------------------------
                */
                $getAnalis = $this->getAnalisLab(
                    $PNOMOR,
                    $tindakanList
                );

                if (!is_array($getAnalis)) {
                    $getAnalis = [
                        'ANALIS' => '',
                        'NIP_ANALIS' => '',
                    ];
                }

                $nipAnalis = $getAnalis['NIP_ANALIS'] ?? '';
                $namaAnalis = $getAnalis['ANALIS'] ?? '';

                /*
                |--------------------------------------------------------------------------
                | TEMPLATE BARU UNTUK SETIAP NOMOR PEMERIKSAAN
                |--------------------------------------------------------------------------
                */
                $templateProcessor = new TemplateProcessor(
                    public_path(
                        '/doc/klaim/lab/cetakBerkasLab.docx'
                    )
                );
                // dd($nipAnalis);

                // ==========================
                // GET TTD ANALIS
                // ==========================
                $ttd_analis = DB::table('simrspku_klaim.tanda_tangan_pegawai')
                    ->where('nip', $nipAnalis)
                    // ->where('nip', 1912314)
                    ->where('status', 1)
                    ->whereNull('deleted_at')
                    ->inRandomOrder()
                    ->first();

                if (!$ttd_analis) {
                    throw new \Exception("TTD {$namaAnalis} (Analis) tidak ditemukan atau belum melakukan Enrollment pada menu Profil");
                }

                if (
                    Storage::disk('public')->exists(
                        $ttd_analis->signature_path
                    )
                ) {
                    $this->setImgWord(
                        $templateProcessor,
                        'TTD_ANALIS',
                        Storage::disk('public')->path(
                            $ttd_analis->signature_path
                        ),
                        200
                    );
                } else {
                    throw new \Exception(
                        "File TTD {$namaAnalis} (Analis) tidak ditemukan: "
                        . $ttd_analis->signature_path
                    );
                }

                // ==========================
                // GET TTD DOKTER
                // ==========================
                // $ttd_dokter = DB::table('simrspku_klaim.tanda_tangan_pegawai')
                //     ->where('nip', $getLabValue($lab,'DOKTER'))
                //     // ->where('nip', 1912314)
                //     ->where('status', 1)
                //     ->whereNull('deleted_at')
                //     ->inRandomOrder()
                //     ->first();
                // dd($getLabValue($lab,'NIPDPJP'));

                // if (!$ttd_dokter) {
                //     throw new \Exception("TTD Dokter Laboratorium ({$getLabValue($lab,'NIPDPJP')}) tidak ditemukan atau belum melakukan Enrollment pada menu Profil");
                // }

                // if (
                //     Storage::disk('public')->exists(
                //         $ttd_dokter->signature_path
                //     )
                // ) {
                //     $this->setImgWord(
                //         $templateProcessor,
                //         'TTD_DOKTER',
                //         Storage::disk('public')->path(
                //             $ttd_dokter->signature_path
                //         ),
                //         200
                //     );
                // } else {
                //     throw new \Exception(
                //         'File TTD Dokter Laboratorium tidak ditemukan: '
                //         . $ttd_dokter->signature_path
                //     );
                // }

                // TTD DOKTER LABORATORIUM STATIC
                $ttd_dokter = public_path() . "/doc/input/laborat/ttd-dokter.png";

                if (File::exists($ttd_dokter)) {
                    $this->setImgWord(
                        $templateProcessor,
                        'TTD_DOKTER',
                        $ttd_dokter,
                        200
                    );
                } else {
                    throw new \Exception(
                        'File TTD Dokter Laboratorium tidak ditemukan: '
                        . $ttd_dokter
                    );
                }

                // ==========================
                // GET CAP LAB
                // ==========================
                $cap_lab = public_path(
                    'doc/klaim/lab/cap_lab.png'
                );
                if (File::exists($cap_lab)) {
                    $this->setImgWord(
                        $templateProcessor,
                        'CAP',
                        $cap_lab,
                        150
                    );
                } else {
                    throw new \Exception(
                        'File PNG - CAP Laboratorium tidak ditemukan: '
                        . $cap_lab
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | HEADER DOKUMEN
                |--------------------------------------------------------------------------
                */
                $templateProcessor->setValue(
                    'NAMAINST',
                    $getLabValue(
                        $lab,
                        'NAMAINST'
                    )
                );

                $templateProcessor->setValue(
                    'ALAMATINST',
                    $getLabValue(
                        $lab,
                        'ALAMATINST'
                    )
                );

                $templateProcessor->setValue(
                    'NORM',
                    $getLabValue(
                        $lab,
                        'NORM'
                    )
                );

                $templateProcessor->setValue(
                    'NAMALENGKAP',
                    $getLabValue(
                        $lab,
                        'NAMALENGKAP'
                    )
                );

                $templateProcessor->setValue(
                    'JK',
                    $getLabValue(
                        $lab,
                        'JK'
                    )
                );

                $templateProcessor->setValue(
                    'TGLLAHIR',
                    $getLabValue(
                        $lab,
                        'TGLLAHIR'
                    )
                );

                $templateProcessor->setValue(
                    'UMUR',
                    $getLabValue(
                        $lab,
                        'UMUR'
                    )
                );

                $templateProcessor->setValue(
                    'ALAMAT_LENGKAP',
                    $getLabValue(
                        $lab,
                        'ALAMAT_LENGKAP'
                    )
                );

                $templateProcessor->setValue(
                    'DOKTER',
                    $getLabValue(
                        $lab,
                        'DOKTER'
                    )
                );

                $templateProcessor->setValue(
                    'NIPDPJP',
                    $getLabValue(
                        $lab,
                        'NIPDPJP'
                    )
                );

                $templateProcessor->setValue(
                    'DOKTERASAL',
                    $getLabValue(
                        $lab,
                        'DOKTERASAL'
                    )
                );

                $templateProcessor->setValue(
                    'ANALIS',
                    !empty($namaAnalis)
                        ? $namaAnalis
                        : $getLabValue(
                            $lab,
                            'ANALIS'
                        )
                );

                $templateProcessor->setValue(
                    'NIP_ANALIS',
                    !empty($nipAnalis)
                        ? $nipAnalis
                        : $getLabValue(
                            $lab,
                            'NIP_ANALIS'
                        )
                );

                $templateProcessor->setValue(
                    'NOPEN',
                    $getLabValue(
                        $lab,
                        'NOPEN'
                    )
                );

                $templateProcessor->setValue(
                    'TGLREG',
                    $getLabValue(
                        $lab,
                        'TGLREG'
                    )
                );

                $templateProcessor->setValue(
                    'TANGGALHASIL',
                    Carbon::parse(
                        $getLabValue(
                            $lab,
                            'TANGGALHASIL'
                        )
                    )->translatedFormat('d F Y')
                );

                $templateProcessor->setValue(
                    'CATATAN',
                    $getLabValue(
                        $lab,
                        'CATATAN'
                    )
                );

                $templateProcessor->setValue(
                    'UNITPENGANTAR',
                    $getLabValue(
                        $lab,
                        'UNITPENGANTAR'
                    )
                );

                $templateProcessor->setValue(
                    'DIAGNOSA',
                    $getLabValue(
                        $lab,
                        'DIAGNOSA'
                    )
                );

                $templateProcessor->setValue(
                    'KAMAR',
                    $getLabValue(
                        $lab,
                        'KAMAR'
                    )
                );

                /*
                |--------------------------------------------------------------------------
                | NO PEMERIKSAAN
                |--------------------------------------------------------------------------
                */
                $templateProcessor->setValue(
                    'NOPEMERIKSAAN',
                    $PNOMOR
                );

                /*
                |--------------------------------------------------------------------------
                | SUSUN STRUKTUR PEMERIKSAAN
                |--------------------------------------------------------------------------
                |
                | GROUPLAB
                |     NAMATINDAKAN
                |         PARAMETER
                |
                | Semua dimasukkan ke kolom PARAMETER.
                |
                */
                $dataLab = $dataLab
                    ->groupBy('GROUPLAB')
                    ->flatMap(function ($group, $grouplab) {

                        return collect([
                            [
                                'PARAMETER' => $grouplab,
                                'HASIL' => '',
                                'NILAI_RUJUKAN' => '',
                                'SATUAN' => '',
                                'KETERANGAN' => '',
                                'TYPE' => 'GROUPLAB',
                            ]
                        ])->concat(
                            $group
                                ->groupBy('NAMATINDAKAN')
                                ->flatMap(function ($items, $namatindakan) {

                                    return collect([
                                        [
                                            'PARAMETER' =>
                                                "\u{00A0}\u{00A0}\u{00A0}\u{00A0}" .
                                                $namatindakan,

                                            'HASIL' => '',
                                            'NILAI_RUJUKAN' => '',
                                            'SATUAN' => '',
                                            'KETERANGAN' => '',
                                            'TYPE' => 'NAMATINDAKAN',
                                        ]
                                    ])->concat(
                                        $items->map(function ($item) {

                                            return [
                                                'PARAMETER' =>
                                                    "\u{00A0}\u{00A0}\u{00A0}\u{00A0}" .
                                                    "\u{00A0}\u{00A0}\u{00A0}\u{00A0}" .
                                                    ($item->PARAMETER ?? ''),
                                                'HASIL' =>
                                                    $item->HASIL ?? '',
                                                'NILAI_RUJUKAN' =>
                                                    $item->NILAI_RUJUKAN ?? '',
                                                'SATUAN' =>
                                                    $item->SATUAN ?? '',
                                                'KETERANGAN' =>
                                                    $item->KETERANGAN ?? '',
                                                'TYPE' => 'PARAMETER',
                                            ];
                                        })
                                    );
                                })
                        );
                    })
                    ->values();

                /*
                |--------------------------------------------------------------------------
                | CLONE ROW PARAMETER
                |--------------------------------------------------------------------------
                |
                | Template Word cukup memiliki SATU baris:
                |
                | ${PARAMETER}
                | ${HASIL}
                | ${SATUAN}
                | ${NILAI_RUJUKAN}
                | ${KETERANGAN}
                |
                */
                $templateProcessor->cloneRow(
                    'PARAMETER',
                    $dataLab->count()
                );

                /*
                |--------------------------------------------------------------------------
                | ISI DETAIL HASIL LAB
                |--------------------------------------------------------------------------
                */
                foreach ($dataLab as $index => $item) {

                    $row = $index + 1;

                    $textRun = new TextRun();

                    /*
                    |--------------------------------------------------------------------------
                    | PEMERIKSAAN
                    |--------------------------------------------------------------------------
                    */
                    if ($item['TYPE'] === 'GROUPLAB') {

                        $textRun->addText(
                            $item['PARAMETER'],
                            [
                                'bold' => true,
                            ]
                        );

                    } elseif ($item['TYPE'] === 'NAMATINDAKAN') {

                        $textRun->addText(
                            $item['PARAMETER'],
                            [
                                'bold' => true,
                            ]
                        );

                    } else {

                        $textRun->addText(
                            $item['PARAMETER']
                        );
                    }

                    $templateProcessor->setComplexValue(
                        "PARAMETER#$row",
                        $textRun
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | HASIL
                    |--------------------------------------------------------------------------
                    */
                    $templateProcessor->setValue(
                        "HASIL#{$row}",
                        $getLabValue(
                            $item,
                            'HASIL'
                        )
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | NILAI RUJUKAN
                    |--------------------------------------------------------------------------
                    */
                    $templateProcessor->setValue(
                        "NILAI_RUJUKAN#{$row}",
                        $getLabValue(
                            $item,
                            'NILAI_RUJUKAN'
                        )
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | SATUAN
                    |--------------------------------------------------------------------------
                    */
                    $templateProcessor->setValue(
                        "SATUAN#{$row}",
                        $getLabValue(
                            $item,
                            'SATUAN'
                        )
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | KETERANGAN
                    |--------------------------------------------------------------------------
                    */
                    $templateProcessor->setValue(
                        "KETERANGAN#{$row}",
                        $getLabValue(
                            $item,
                            'KETERANGAN'
                        )
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | IDENTIFIER FILE SEMENTARA
                |--------------------------------------------------------------------------
                */
                $totalTindakan++;

                $temporaryBase = $outputDir
                    . DIRECTORY_SEPARATOR
                    . $getSEP->NOSEP
                    . '_lab_' .
                    $totalTindakan;

                $temporaryWord = $temporaryBase . '.docx';
                $temporaryPdf = $temporaryBase . '.pdf';

                /*
                |--------------------------------------------------------------------------
                | SAVE DOCX SEMENTARA
                |--------------------------------------------------------------------------
                */
                $templateProcessor->saveAs(
                    $temporaryWord
                );

                if (!File::exists($temporaryWord)) {

                    throw new \Exception(
                        'Gagal membuat DOCX hasil lab untuk nomor pemeriksaan '
                        . $PNOMOR
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | UBAH METADATA TITLE DOCX
                |--------------------------------------------------------------------------
                |
                | LibreOffice menggunakan properti dokumen sebagai salah satu
                | sumber metadata judul PDF yang dihasilkan.
                |
                */

                $zip = new \ZipArchive();

                if ($zip->open($temporaryWord) !== true) {
                    throw new \Exception(
                        'Gagal membuka DOCX untuk mengubah judul dokumen.'
                    );
                }

                $coreXml = $zip->getFromName('docProps/core.xml');

                if ($coreXml === false) {
                    $zip->close();

                    throw new \Exception(
                        'Metadata dokumen DOCX tidak ditemukan.'
                    );
                }

                $titleXml = htmlspecialchars(
                    $NODOKUMEN.'_lab',
                    ENT_XML1 | ENT_COMPAT,
                    'UTF-8'
                );

                if (preg_match('~<dc:title(?:\s[^>]*)?>.*?</dc:title>~s', $coreXml)) {

                    $coreXml = preg_replace(
                        '~<dc:title(?:\s[^>]*)?>.*?</dc:title>~s',
                        '<dc:title>' . $titleXml . '</dc:title>',
                        $coreXml,
                        1
                    );

                } else {

                    $coreXml = preg_replace(
                        '~</cp:coreProperties>~',
                        '<dc:title>' . $titleXml . '</dc:title></cp:coreProperties>',
                        $coreXml,
                        1
                    );
                }

                if (!$zip->addFromString('docProps/core.xml', $coreXml)) {
                    $zip->close();

                    throw new \Exception(
                        'Gagal menyimpan metadata judul dokumen.'
                    );
                }

                $zip->close();

                /*
                |--------------------------------------------------------------------------
                | CONVERT DOCX KE PDF
                |--------------------------------------------------------------------------
                */
                [$success] = $this->libreOffice->generatePdf(
                    $temporaryWord,
                    $outputDir
                );

                if (!$success || !File::exists($temporaryPdf)) {

                    if (File::exists($temporaryWord)) {
                        File::delete($temporaryWord);
                    }

                    throw new \Exception(
                        'Gagal generate PDF hasil lab untuk nomor pemeriksaan '
                        . $PNOMOR
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | SIMPAN FILE PDF SEMENTARA
                |--------------------------------------------------------------------------
                */
                $temporaryFiles[] = $temporaryPdf;

                /*
                |--------------------------------------------------------------------------
                | HAPUS DOCX SEMENTARA
                |--------------------------------------------------------------------------
                */
                if (File::exists($temporaryWord)) {

                    File::delete($temporaryWord);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | VALIDASI FILE
            |--------------------------------------------------------------------------
            */
            if (empty($temporaryFiles)) {

                return response()->json([
                    'success' => false,
                    'message' => 'Data hasil lab tidak ditemukan'
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | JIKA HANYA ADA SATU PDF
            |--------------------------------------------------------------------------
            */
            // if (count($temporaryFiles) === 1) {

            //     $finalPdf = $output . '.pdf';

            //     if (File::exists($finalPdf)) {
            //         File::delete($finalPdf);
            //     }

            //     File::move(
            //         $temporaryFiles[0],
            //         $finalPdf
            //     );

            //     return true;
            // }

            // /*
            // |--------------------------------------------------------------------------
            // | MERGE SEMUA PDF
            // |--------------------------------------------------------------------------
            // */
            // $pdf = new Fpdi();

            // foreach ($temporaryFiles as $temporaryPdf) {

            //     if (!File::exists($temporaryPdf)) {
            //         continue;
            //     }

            //     /*
            //     |--------------------------------------------------------------------------
            //     | AMBIL JUMLAH HALAMAN PDF
            //     |--------------------------------------------------------------------------
            //     */
            //     $pageCount = $pdf->setSourceFile(
            //         $temporaryPdf
            //     );

            //     /*
            //     |--------------------------------------------------------------------------
            //     | IMPORT SETIAP HALAMAN
            //     |--------------------------------------------------------------------------
            //     */
            //     for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {

            //         $templateId = $pdf->importPage(
            //             $pageNo
            //         );

            //         $size = $pdf->getTemplateSize(
            //             $templateId
            //         );

            //         /*
            //         |--------------------------------------------------------------------------
            //         | ORIENTASI
            //         |--------------------------------------------------------------------------
            //         */
            //         $orientation = (
            //             $size['width'] > $size['height']
            //         )
            //             ? 'L'
            //             : 'P';

            //         /*
            //         |--------------------------------------------------------------------------
            //         | BUAT HALAMAN DENGAN UKURAN YANG SAMA
            //         |--------------------------------------------------------------------------
            //         */
            //         $pdf->AddPage(
            //             $orientation,
            //             [
            //                 $size['width'],
            //                 $size['height']
            //             ]
            //         );

            //         /*
            //         |--------------------------------------------------------------------------
            //         | LETAKKAN HALAMAN HASIL
            //         |--------------------------------------------------------------------------
            //         */
            //         $pdf->useTemplate(
            //             $templateId,
            //             0,
            //             0,
            //             $size['width'],
            //             $size['height']
            //         );
            //     }
            // }

            // /*
            // |--------------------------------------------------------------------------
            // | OUTPUT PDF FINAL
            // |--------------------------------------------------------------------------
            // */
            // $finalPdf = $output . '.pdf';

            // /*
            // |--------------------------------------------------------------------------
            // | SAVE TO DB
            // |--------------------------------------------------------------------------
            // */
            // DB::table('simrspku_klaim.klaim_file')
            //     ->updateOrInsert(
            //         [
            //             'nomor' => $kunjungan,
            //             'jenis' => 6,
            //         ],
            //         [
            //             'title' => $fileName . '.pdf',
            //             'filename' => $path . '.pdf',
            //             'user' => auth()->user()->ID,
            //             'status' => true,
            //             'deleted_at' => null,
            //             'created_at' => now(),
            //             'updated_at' => now(),
            //         ]
            //     );

            // /*
            // |--------------------------------------------------------------------------
            // | HAPUS PDF FINAL LAMA
            // |--------------------------------------------------------------------------
            // */
            // if (File::exists($finalPdf)) {

            //     File::delete($finalPdf);
            // }

            // /*
            // |--------------------------------------------------------------------------
            // | SAVE PDF FINAL
            // |--------------------------------------------------------------------------
            // */
            // $pdf->Output(
            //     $finalPdf,
            //     'F'
            // );

            // /*
            // |--------------------------------------------------------------------------
            // | HAPUS SEMUA FILE SEMENTARA
            // |--------------------------------------------------------------------------
            // */
            // foreach ($temporaryFiles as $temporaryPdf) {

            //     if (File::exists($temporaryPdf)) {

            //         File::delete($temporaryPdf);
            //     }
            // }

            // /*
            // |--------------------------------------------------------------------------
            // | VALIDASI FINAL
            // |--------------------------------------------------------------------------
            // */
            // if (!File::exists($finalPdf)) {
            //     throw new \Exception(
            //         'PDF hasil lab gagal dibuat'
            //     );
            // }

            // DB::commit();

            // return response()->file($finalPdf, [
            //     'Content-Type' => 'application/pdf',
            //     'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            //     'Pragma'        => 'no-cache',
            //     'Expires'       => '0',
            // ]);

            /*
            |--------------------------------------------------------------------------
            | OUTPUT PDF FINAL
            |--------------------------------------------------------------------------
            */

            $finalPdf = $output . '.pdf';


            /*
            |--------------------------------------------------------------------------
            | JIKA HANYA ADA SATU PDF
            |--------------------------------------------------------------------------
            |
            | Tidak perlu merge dengan FPDI.
            | Cukup pindahkan PDF sementara menjadi PDF final.
            |
            */

            if (count($temporaryFiles) === 1) {

                if (File::exists($finalPdf)) {
                    File::delete($finalPdf);
                }

                File::move(
                    $temporaryFiles[0],
                    $finalPdf
                );

            } else {

                /*
                |--------------------------------------------------------------------------
                | MERGE SEMUA PDF
                |--------------------------------------------------------------------------
                */

                $pdf = new Fpdi();

                foreach ($temporaryFiles as $temporaryPdf) {

                    if (!File::exists($temporaryPdf)) {
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | AMBIL JUMLAH HALAMAN PDF
                    |--------------------------------------------------------------------------
                    */

                    $pageCount = $pdf->setSourceFile(
                        $temporaryPdf
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | IMPORT SETIAP HALAMAN
                    |--------------------------------------------------------------------------
                    */

                    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {

                        $templateId = $pdf->importPage(
                            $pageNo
                        );

                        $size = $pdf->getTemplateSize(
                            $templateId
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | ORIENTASI
                        |--------------------------------------------------------------------------
                        */

                        $orientation = (
                            $size['width'] > $size['height']
                        )
                            ? 'L'
                            : 'P';


                        /*
                        |--------------------------------------------------------------------------
                        | BUAT HALAMAN DENGAN UKURAN YANG SAMA
                        |--------------------------------------------------------------------------
                        */

                        $pdf->AddPage(
                            $orientation,
                            [
                                $size['width'],
                                $size['height']
                            ]
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | LETAKKAN HALAMAN HASIL
                        |--------------------------------------------------------------------------
                        */

                        $pdf->useTemplate(
                            $templateId,
                            0,
                            0,
                            $size['width'],
                            $size['height']
                        );
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | SIMPAN PDF HASIL MERGE
                |--------------------------------------------------------------------------
                */

                $pdf->Output(
                    $finalPdf,
                    'F'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | SAVE TO DB
            |--------------------------------------------------------------------------
            */

            DB::table('simrspku_klaim.klaim_file')
                ->updateOrInsert(
                    [
                        'nomor' => $kunjungan,
                        'jenis' => 6,
                    ],
                    [
                        'title' => $fileName . '.pdf',
                        'filename' => $path . '.pdf',
                        'user' => auth()->user()->ID,
                        'status' => true,
                        'deleted_at' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );


            /*
            |--------------------------------------------------------------------------
            | HAPUS SEMUA FILE SEMENTARA
            |--------------------------------------------------------------------------
            */

            foreach ($temporaryFiles as $temporaryPdf) {

                if (File::exists($temporaryPdf)) {
                    File::delete($temporaryPdf);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | VALIDASI FINAL
            |--------------------------------------------------------------------------
            */

            if (!File::exists($finalPdf)) {

                throw new \Exception(
                    'PDF hasil lab gagal dibuat'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | COMMIT
            |--------------------------------------------------------------------------
            */

            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | RETURN PDF
            |--------------------------------------------------------------------------
            */
            return response()->file($finalPdf, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $NODOKUMEN . '_lab.pdf"',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma'        => 'no-cache',
                'Expires'       => '0',
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getAnalisLab($PNOMOR, $PTINDAKAN)
    {
        $tindakan = is_array($PTINDAKAN)
            ? $PTINDAKAN
            : preg_split('/\s*,\s*/', (string) $PTINDAKAN, -1, PREG_SPLIT_NO_EMPTY);

        $nomor = is_array($PNOMOR)
            ? $PNOMOR
            : preg_split('/\s*,\s*/', (string) $PNOMOR, -1, PREG_SPLIT_NO_EMPTY);

        $tindakan = collect($tindakan)
            ->map(fn ($v) => trim((string) $v))
            ->filter(fn ($v) => ctype_digit($v))
            ->map(fn ($v) => (int) $v)
            ->unique()
            ->values()
            ->all();

        $nomor = collect($nomor)
            ->map(fn ($v) => trim((string) $v))
            ->filter(fn ($v) => ctype_digit($v))
            ->map(fn ($v) => (int) $v)
            ->unique()
            ->values()
            ->all();

        if (!$tindakan || !$nomor) {
            return [
                'ANALIS' => '',
                'NIP_ANALIS' => '',
            ];
        }

        $data = DB::table('pendaftaran.kunjungan as pk')
            ->join(
                'layanan.tindakan_medis as tm',
                'tm.KUNJUNGAN',
                '=',
                'pk.NOMOR'
            )
            ->join(
                'layanan.hasil_lab as hlab',
                'hlab.TINDAKAN_MEDIS',
                '=',
                'tm.ID'
            )
            ->leftJoin(
                'aplikasi.pengguna as pe',
                'pe.ID',
                '=',
                'tm.OLEH'
            )
            ->leftJoin(
                'layanan.petugas_tindakan_medis as ptm',
                function ($join) {
                    $join->on(
                        'ptm.TINDAKAN_MEDIS',
                        '=',
                        'tm.ID'
                    )
                    ->where('ptm.JENIS', 6)
                    ->where('ptm.KE', 1)
                    ->where('ptm.STATUS', '!=', 0);
                }
            )
            ->leftJoin(
                'master.pegawai as mpper',
                'ptm.MEDIS',
                '=',
                'mpper.ID'
            )
            ->where('hlab.STATUS', 1)
            ->whereIn('pk.NOMOR', $nomor)
            ->whereIn('hlab.TINDAKAN_MEDIS', $tindakan)
            ->whereNotNull('hlab.HASIL')
            ->where('hlab.HASIL', '!=', '')
            ->select([
                DB::raw("
                    master.getNamaLengkapPegawai(
                        IFNULL(mpper.NIP, pe.NIP)
                    ) as ANALIS
                "),
                DB::raw("
                    IFNULL(
                        mpper.NIP,
                        pe.NIP
                    ) as NIP_ANALIS
                "),
            ])
            ->first();

        return [
            'ANALIS' => $data->ANALIS ?? '',
            'NIP_ANALIS' => $data->NIP_ANALIS ?? '',
        ];
    }

    public function cetakHasilLab($PNOMOR, $PTINDAKAN)
    {
        $tindakan = is_array($PTINDAKAN)
            ? $PTINDAKAN
            : preg_split('/\s*,\s*/', (string) $PTINDAKAN, -1, PREG_SPLIT_NO_EMPTY);

        $nomor = is_array($PNOMOR)
            ? $PNOMOR
            : preg_split('/\s*,\s*/', (string) $PNOMOR, -1, PREG_SPLIT_NO_EMPTY);

        $tindakan = collect($tindakan)
            ->map(fn ($v) => trim((string) $v))
            ->filter(fn ($v) => ctype_digit($v))
            ->map(fn ($v) => (int) $v)
            ->unique()
            ->values()
            ->all();

        $nomor = collect($nomor)
            ->map(fn ($v) => trim((string) $v))
            ->filter(fn ($v) => ctype_digit($v))
            ->map(fn ($v) => (int) $v)
            ->unique()
            ->values()
            ->all();

        if (!$tindakan || !$nomor) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter tindakan atau nomor tidak valid.'
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | INSTANSI
        |--------------------------------------------------------------------------
        */

        $instansi = DB::table('aplikasi.instansi as ai')
            ->join('master.ppk as mp', 'ai.PPK', '=', 'mp.ID')
            ->join('master.wilayah as w', 'mp.WILAYAH', '=', 'w.ID')
            ->select([
                'mp.NAMA as NAMAINST',
                'mp.ALAMAT as ALAMATINST',
                'ai.PPK',
                'w.DESKRIPSI as KOTA',
                'mp.TELEPON',
                'mp.FAX',
                'ai.EMAIL',
            ])
            ->first();

        /*
        |--------------------------------------------------------------------------
        | QUERY BASE
        |--------------------------------------------------------------------------
        */

        $query = DB::table('pendaftaran.kunjungan as pk')

            ->join(
                'pendaftaran.pendaftaran as pp',
                'pk.NOPEN',
                '=',
                'pp.NOMOR'
            )

            ->join(
                'master.pasien as p',
                'pp.NORM',
                '=',
                'p.NORM'
            )

            ->join(
                'layanan.tindakan_medis as tm',
                'tm.KUNJUNGAN',
                '=',
                'pk.NOMOR'
            )

            ->join(
                'layanan.hasil_lab as hlab',
                'hlab.TINDAKAN_MEDIS',
                '=',
                'tm.ID'
            )

            ->join(
                'master.parameter_tindakan_lab as ptl',
                'hlab.PARAMETER_TINDAKAN',
                '=',
                'ptl.ID'
            )

            ->join(
                'master.tindakan as mt',
                'ptl.TINDAKAN',
                '=',
                'mt.ID'
            )

            ->leftJoin('layanan.catatan_hasil_lab as chl', function ($join) {
                $join->on(
                    'tm.KUNJUNGAN',
                    '=',
                    'chl.KUNJUNGAN'
                );
            })

            ->leftJoin(
                'master.dokter as dok',
                'chl.DOKTER',
                '=',
                'dok.ID'
            )

            ->leftJoin(
                'master.pegawai as mp',
                'dok.NIP',
                '=',
                'mp.NIP'
            )

            ->leftJoin(
                'aplikasi.pengguna as pe',
                'pe.ID',
                '=',
                'tm.OLEH'
            )

            ->leftJoin(
                'simrspku_klaim.klaim_qrcode_pegawai as qpe',
                'qpe.nomor',
                '=',
                'pe.NIP'
            )

            ->leftJoin(
                'simrspku_klaim.tanda_tangan_pegawai as ttd',
                'ttd.nip',
                '=',
                'pe.NIP'
            )

            ->leftJoin('layanan.petugas_tindakan_medis as ptm', function ($join) {
                $join->on(
                    'ptm.TINDAKAN_MEDIS',
                    '=',
                    'tm.ID'
                )
                ->where('ptm.JENIS', 6)
                ->where('ptm.KE', 1)
                ->where('ptm.STATUS', '!=', 0);
            })

            ->leftJoin(
                'master.pegawai as mpper',
                'ptm.MEDIS',
                '=',
                'mpper.ID'
            )

            ->leftJoin(
                'simrspku_klaim.klaim_qrcode_pegawai as qp',
                'qp.nomor',
                '=',
                'mpper.NIP'
            )

            ->leftJoin(
                'simrspku_klaim.tanda_tangan_pegawai as ttd2',
                'ttd2.nip',
                '=',
                'mpper.NIP'
            )

            ->leftJoin('master.referensi as sl', function ($join) {
                $join->on(
                    'ptl.SATUAN',
                    '=',
                    'sl.ID'
                )
                ->where('sl.JENIS', 35);
            })

            ->leftJoin('master.mapping_group_pemeriksaan as mgp', function ($join) {
                $join->on(
                    'mt.ID',
                    '=',
                    'mgp.PEMERIKSAAN'
                )
                ->where('mgp.STATUS', 1);
            })

            ->leftJoin('master.group_pemeriksaan as kgl', function ($join) {
                $join->on(
                    'mgp.GROUP_PEMERIKSAAN_ID',
                    '=',
                    'kgl.ID'
                )
                ->where('kgl.JENIS', 8)
                ->where('kgl.STATUS', 1);
            })

            ->leftJoin('master.group_pemeriksaan as ggl', function ($join) {
                $join->on(
                    'ggl.KODE',
                    '=',
                    DB::raw('LEFT(kgl.KODE,2)')
                )
                ->where('ggl.JENIS', 8)
                ->where('ggl.STATUS', 1);
            })

            ->leftJoin(
                'pendaftaran.penjamin as pj',
                'pp.NOMOR',
                '=',
                'pj.NOPEN'
            )

            ->leftJoin('master.referensi as ref', function ($join) {
                $join->on(
                    'pj.JENIS',
                    '=',
                    'ref.ID'
                )
                ->where('ref.JENIS', 10);
            })

            ->leftJoin(
                'layanan.order_lab as ks',
                'pk.REF',
                '=',
                'ks.NOMOR'
            )

            ->leftJoin(
                'pendaftaran.kunjungan as kj',
                'ks.KUNJUNGAN',
                '=',
                'kj.NOMOR'
            )

            ->leftJoin('master.ruangan as r', function ($join) {
                $join->on(
                    'kj.RUANGAN',
                    '=',
                    'r.ID'
                )
                ->where('r.JENIS', 5);
            })

            ->leftJoin(
                'master.ruang_kamar_tidur as rkt',
                'kj.RUANG_KAMAR_TIDUR',
                '=',
                'rkt.ID'
            )

            ->leftJoin(
                'master.ruang_kamar as rk',
                'rkt.RUANG_KAMAR',
                '=',
                'rk.ID'
            )

            ->leftJoin(
                'master.dokter as dokasal',
                'ks.DOKTER_ASAL',
                '=',
                'dokasal.ID'
            )

            ->leftJoin(
                'master.pegawai as mpasal',
                'dokasal.NIP',
                '=',
                'mpasal.NIP'
            )

            ->leftJoin('master.referensi as rjk', function ($join) {
                $join->on(
                    'p.JENIS_KELAMIN',
                    '=',
                    'rjk.ID'
                )
                ->where('rjk.JENIS', 2);
            })

            ->where('hlab.STATUS', 1)
            ->whereIn('pk.NOMOR', $nomor)
            ->whereIn('hlab.TINDAKAN_MEDIS', $tindakan)
            ->whereNotNull('hlab.HASIL')
            ->where('hlab.HASIL', '!=', '');

        /*
        |--------------------------------------------------------------------------
        | TOTAL ROW
        |--------------------------------------------------------------------------
        */

        $rows = (clone $query)->count();

        /*
        |--------------------------------------------------------------------------
        | HASIL LAB
        |--------------------------------------------------------------------------
        */

        $data = (clone $query)
            ->select([
                DB::raw("'" . addslashes($instansi->NAMAINST ?? '') . "' as NAMAINST"),
                DB::raw("'" . addslashes($instansi->ALAMATINST ?? '') . "' as ALAMATINST"),
                DB::raw("'" . addslashes($instansi->PPK ?? '') . "' as PPK"),
                DB::raw("'" . addslashes($instansi->KOTA ?? '') . "' as KOTA"),
                DB::raw("'" . addslashes($instansi->TELEPON ?? '') . "' as TELEPON"),
                DB::raw("'" . addslashes($instansi->FAX ?? '') . "' as FAX"),
                DB::raw("'" . addslashes($instansi->EMAIL ?? '') . "' as EMAIL"),

                DB::raw("DATE_FORMAT(SYSDATE(), '%d-%m-%Y %H:%i:%s') as TGLSKRG"),

                DB::raw("LPAD(p.NORM,8,'0') as NORM"),
                DB::raw("master.getNamaLengkap(p.NORM) as NAMALENGKAP"),

                DB::raw("
                    CONCAT(
                        rjk.DESKRIPSI,
                        ' / ',
                        DATE_FORMAT(p.TANGGAL_LAHIR,'%d-%m-%Y')
                    ) as JKTGLALHIR
                "),

                'rjk.DESKRIPSI as JK',

                DB::raw("
                    DATE_FORMAT(
                        p.TANGGAL_LAHIR,
                        '%d-%m-%Y'
                    ) as TGLLAHIR
                "),

                DB::raw("
                    master.getCariUmur(
                        pk.MASUK,
                        p.TANGGAL_LAHIR
                    ) as UMUR
                "),

                DB::raw("
                    master.getAlamatPasienCustom(p.NORM)
                    as ALAMAT_LENGKAP
                "),

                DB::raw("
                    master.getNamaLengkapPegawai(mp.NIP)
                    as DOKTER
                "),

                'mp.NIP as NIPDPJP',

                DB::raw("
                    master.getNamaLengkapPegawai(mpasal.NIP)
                    as DOKTERASAL
                "),

                DB::raw("
                    master.getNamaLengkapPegawai(
                        IFNULL(mpper.NIP, pe.NIP)
                    ) as ANALIS
                "),

                DB::raw("
                    IFNULL(
                        mpper.NIP,
                        pe.NIP
                    ) as NIP_ANALIS
                "),

                DB::raw("
                    IFNULL(
                        qp.filename,
                        qpe.filename
                    ) as QR1
                "),

                DB::raw("
                    IFNULL(
                        ttd.signature_path,
                        ttd2.signature_path
                    ) as TTD
                "),

                'pk.NOPEN',
                'pk.MASUK as TGLREG',
                'hlab.TANGGAL as TANGGALHASIL',
                'chl.CATATAN',
                'r.DESKRIPSI as UNITPENGANTAR',
                'ks.ALASAN as DIAGNOSA',
                'tm.KUNJUNGAN',

                DB::raw("
                    IFNULL(
                        rk.KAMAR,
                        '-'
                    ) as KAMAR
                "),

                'kgl.DESKRIPSI as KLPLAB',
                'ggl.DESKRIPSI as GROUPLAB',
                'mt.NAMA as NAMATINDAKAN',
                'ptl.PARAMETER',

                DB::raw("
                    IFNULL(
                        hlab.NILAI_NORMAL,
                        ptl.NILAI_RUJUKAN
                    ) as NILAI_RUJUKAN
                "),

                'hlab.HASIL',

                DB::raw("
                    IFNULL(
                        hlab.SATUAN,
                        sl.DESKRIPSI
                    ) as SATUAN
                "),

                'hlab.KETERANGAN',
                'ggl.ID',
                'ptl.INDEKS',

                DB::raw($rows . ' as `ROWS`'),
            ])
            ->distinct()
            ->orderBy('ggl.ID')
            ->orderBy('kgl.ID')
            ->orderBy('mt.ID')
            ->orderBy('ptl.INDEKS')
            ->get();

        return [
            'success' => true,
            'rows' => $rows,
            'instansi' => $instansi,
            'data' => $data,
        ];
    }

    // ====================================================================================================================================
    // ===========================================================  BILLING  ==============================================================
    // ====================================================================================================================================
    public function generateBilling($kunjungan)
    {
        try {
            $getSEP = DB::table('pendaftaran.kunjungan AS pk')
                    ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                    ->leftJoin('pendaftaran.penjamin AS pj','pp.NOMOR','=','pj.NOPEN')
                    ->leftJoin('pembayaran.tagihan_pendaftaran AS tp','tp.PENDAFTARAN','=','pp.NOMOR')
                    ->select('pj.NOMOR AS NOSEP','pp.NOMOR AS NOPEN','tp.TAGIHAN AS TAGIHAN','tp.STATUS AS STATUS','pk.MASUK AS TANGGALMASUK')
                    ->where('pk.NOMOR',$kunjungan)
                    ->where(function ($query) {
                        $query->where('tp.STATUS', '=', '1')
                                ->orWhere('tp.UTAMA', '=', '1');
                    })
                    ->first();

            if (!$getSEP || !$getSEP->TAGIHAN) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data kunjungan atau tagihan tidak ditemukan.',
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | JUDUL DOKUMEN = NOSEP
            |--------------------------------------------------------------------------
            */

            $NODOKUMEN = trim((string) ($getSEP->NOSEP ?? ''));

            if ($NODOKUMEN === '') {
                $NODOKUMEN = (string) $kunjungan;
            }

            // Pastikan aman digunakan sebagai nama file.
            $NODOKUMEN = preg_replace(
                '/[^A-Za-z0-9._-]/',
                '_',
                $NODOKUMEN
            );

            if ($NODOKUMEN === '') {
                $NODOKUMEN = (string) $kunjungan;
            }

            /*
            |--------------------------------------------------------------------------
            | 1. AMBIL HEADER DAN DETAIL BILLING
            |--------------------------------------------------------------------------
            |
            | Parameter $kunjungan di sini harus berupa ID TAGIHAN,
            | bukan nomor kunjungan pendaftaran.
            |
            | Jika parameter dari route Anda memang berupa nomor kunjungan,
            | perlu dibuat query untuk mendapatkan ID tagihan terlebih dahulu.
            |
            */

            $nomorTagihan = $getSEP->TAGIHAN;

            // return response()->json($this->queryBillingJenisLain($nomorTagihan)->get());

            $header = $this->getBillingHeader($nomorTagihan);

            if (!$header) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tagihan tidak ditemukan.',
                ], 404);
            }

            $detail = $this->getBillingDetail($nomorTagihan);

            $penjamin = $this->getBillingPenjamin($nomorTagihan);

            /*
            |--------------------------------------------------------------------------
            | 2. PERSIAPAN DATA HEADER
            |--------------------------------------------------------------------------
            */

            $header->TGLKELUAR = $header->TGLKELUAR
                ?: $header->TANGGALREG;

            $header->NAMA_KASIR = $header->PENGGUNA
                ?: Auth::user()?->name
                ?: '';

            /*
            |--------------------------------------------------------------------------
            | 3. FORMAT DETAIL BILLING
            |--------------------------------------------------------------------------
            */

            $detail = collect($detail)->map(function ($item) {
                $item->JUMLAH = (float) ($item->JUMLAH ?? 0);
                $item->TARIF = (float) ($item->TARIF ?? 0);

                $item->TOTAL = $item->TARIF * $item->JUMLAH;

                return $item;
            });

            /*
            |--------------------------------------------------------------------------
            | 4. SUSUN BARIS BILLING SEPERTI JRXML
            |--------------------------------------------------------------------------
            |
            | Urutan yang akan dicetak:
            |   - Header jenis kunjungan / nomor pendaftaran
            |   - Header ruangan (sekali per grup)
            |   - Rincian layanan (menjorok ke dalam)
            |   - Jumlah per ruangan di sisi kanan
            |
            */

            $billingRows = [];

            $jenisKunjunganLabel = trim((string) ($header->JENISKUNJUNGAN ?? ''));
            $nomorPendaftaran = trim((string) ($header->NOPEN ?? ''));
            $tanggalPendaftaran = trim((string) ($header->TANGGALREG ?? ''));

            $visitLabel = $jenisKunjunganLabel !== ''
                ? $jenisKunjunganLabel
                : 'Kunjungan';

            if ($nomorPendaftaran !== '') {
                $visitLabel .= ' [' . $nomorPendaftaran . ']';
            }

            if ($tanggalPendaftaran !== '') {
                $visitLabel .= ' / ' . $tanggalPendaftaran;
            }

            $billingRows[] = [
                'type' => 'visit',
                'values' => [
                    'BILLING_VISIT_LABEL' => $visitLabel,
                ],
            ];

            $detailGrouped = $detail
                ->groupBy('JENIS_KUNJUNGAN')
                ->map(function ($items) {
                    return $items->groupBy(function ($item) {
                        $ruangan = trim((string) ($item->RUANGAN ?? ''));

                        return $ruangan !== '' ? $ruangan : 'Layanan Lainnya';
                    });
                });

            $normalizeLineBreaks = static function ($value): string {
                return str_replace(
                    ["\\r\\n", "\\n", "\\r", "\r\n", "\r"],
                    "\n",
                    (string) ($value ?? '')
                );
            };

            foreach ($detailGrouped as $ruanganList) {
                foreach ($ruanganList as $namaRuangan => $items) {
                    $billingRows[] = [
                        'type' => 'room',
                        'values' => [
                            'BILLING_ROOM_LABEL' => (string) $namaRuangan,
                        ],
                    ];

                    foreach ($items as $item) {
                        $isRincianTindakan = (int) ($item->STATUSTINDAKANRINCIAN ?? 0) === 1;

                        $tanggal = '';
                        if (!empty($item->TANGGAL)) {
                            try {
                                $tanggal = Carbon::parse($item->TANGGAL)->format('d/m/Y H:i:s');
                            } catch (\Throwable $e) {
                                $tanggal = (string) $item->TANGGAL;
                            }
                        }

                        if ($isRincianTindakan) {
                            $layanan = $normalizeLineBreaks(
                                $item->LAYANAN_OK ?? $item->LAYANAN1 ?? ''
                            );
                            $rp = $normalizeLineBreaks($item->RP ?? '');
                            $tarif = $normalizeLineBreaks($item->TARIF_LAYANAN_OK ?? '');
                            $total = $normalizeLineBreaks($item->TARIF_LAYANAN_OK ?? '');
                        } else {
                            $layanan = (string) ($item->LAYANAN1 ?? '');
                            $rp = 'Rp. ';
                            $tarif = number_format(
                                (float) ($item->TARIF ?? 0),
                                0,
                                '.',
                                ','
                            );
                            $total = number_format(
                                (float) ($item->TOTAL ?? 0),
                                0,
                                '.',
                                ','
                            );
                        }

                        $billingRows[] = [
                            'type' => 'item',
                            'values' => [
                                'LAYANAN1' => $layanan,
                                'TANGGAL' => $tanggal,
                                'JUMLAH' => number_format(
                                    (float) ($item->JUMLAH ?? 0),
                                    2,
                                    '.',
                                    ','
                                ),
                                'RP' => $rp,
                                'TARIF' => $tarif,
                                'TOTAL' => $total,
                            ],
                        ];
                    }

                    $subtotal = (float) $items->sum(function ($item) {
                        return (float) ($item->TOTAL ?? 0);
                    });

                    $billingRows[] = [
                        'type' => 'subtotal',
                        'values' => [
                            'BILLING_SUBTOTAL_LABEL' => 'Jumlah ' . $namaRuangan,
                            'BILLING_SUBTOTAL_VALUE' => 'Rp. ' . number_format(
                                $subtotal,
                                0,
                                '.',
                                ','
                            ),
                        ],
                    ];
                }
            }

            if ($detail->isEmpty()) {
                $billingRows[] = [
                    'type' => 'room',
                    'values' => [
                        'BILLING_ROOM_LABEL' => 'Rincian Billing',
                    ],
                ];
                $billingRows[] = [
                    'type' => 'item',
                    'values' => [
                        'LAYANAN1' => 'Tidak ada rincian',
                        'TANGGAL' => '',
                        'JUMLAH' => '0.00',
                        'RP' => 'Rp. ',
                        'TARIF' => '0',
                        'TOTAL' => '0',
                    ],
                ];
                $billingRows[] = [
                    'type' => 'subtotal',
                    'values' => [
                        'BILLING_SUBTOTAL_LABEL' => 'Jumlah Rincian Billing',
                        'BILLING_SUBTOTAL_VALUE' => 'Rp. 0',
                    ],
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | 6. SIAPKAN TEMPLATE WORD
            |--------------------------------------------------------------------------
            */

            $templatePath = public_path(
                'doc/klaim/billing/cetakBerkasBilling.docx'
            );

            if (!File::exists($templatePath)) {
                throw new \Exception(
                    'Template Word tidak ditemukan: ' . $templatePath
                );
            }

            $templateProcessor = new TemplateProcessor($templatePath);

            /*
            |--------------------------------------------------------------------------
            | 7. ISI HEADER WORD
            |--------------------------------------------------------------------------
            */

            $templateProcessor->setValue(
                'NAMAINSTANSI',
                $header->NAMAINSTANSI ?? ''
            );

            $templateProcessor->setValue(
                'ALAMATINSTANSI',
                $header->ALAMATINSTANSI ?? ''
            );

            $templateProcessor->setValue(
                'NORM',
                $header->NORM ?? ''
            );

            $templateProcessor->setValue(
                'NAMALENGKAP',
                $header->NAMALENGKAP ?? ''
            );

            $templateProcessor->setValue(
                'NOPEN',
                $header->NOPEN ?? ''
            );

            $templateProcessor->setValue(
                'TANGGALREG',
                $header->TANGGALREG ?? ''
            );

            $templateProcessor->setValue(
                'IDTAGIHAN',
                $header->IDTAGIHAN ?? ''
            );

            $templateProcessor->setValue(
                'NOMORKARTU',
                $header->NOMORKARTU ?? ''
            );

            $templateProcessor->setValue(
                'CARABAYAR',
                $header->CARABAYAR ?? ''
            );

            $templateProcessor->setValue(
                'UMUR',
                $header->UMUR ?? ''
            );

            $templateProcessor->setValue(
                'ALAMATLENGKAP',
                $header->ALAMATLENGKAP ?? ''
            );

            $templateProcessor->setValue(
                'NOSEP',
                $header->NOSEP ?? ''
            );

            $templateProcessor->setValue(
                'TGLKELUAR',
                $header->TGLKELUAR ?? ''
            );

            $templateProcessor->setValue(
                'DPJP',
                $header->DPJP ?? ''
            );

            $templateProcessor->setValue(
                'DIAGNOSA',
                $header->DIAGNOSA ?? ''
            );

            $templateProcessor->setValue(
                'WILAYAH',
                $header->WILAYAH ?? ''
            );

            $templateProcessor->setValue(
                'TANGGALBAYAR',
                $header->TANGGALBAYAR
                    ? Carbon::parse($header->TANGGALBAYAR)
                        ->translatedFormat('d F Y')
                    : ''
            );

            $templateProcessor->setValue(
                'PENGGUNA',
                $header->PENGGUNA ?: ($header->NAMA_KASIR ?? '')
            );

            $templateProcessor->setValue(
                'NAMA_KELUARGA_PASIEN',
                $header->NAMA_KELUARGA_PASIEN ?? ''
            );

            /*
            |--------------------------------------------------------------------------
            | 8. ISI TOTAL TAGIHAN
            |--------------------------------------------------------------------------
            */

            $kolomNominal = [
                'TOTALTAGIHAN',
                'TOTALDISKON',
                'TOTALEDC',
                'TOTALPENJAMINTAGIHAN',
                'TOTALPIUTANG',
                'TOTALDEPOSIT',
                'TOTALSUBSIDI',
                'JUMLAHBAYAR',
                'TOTALJUMLAHBAYAR',
                'PEMBULATAN',
            ];

            foreach ($kolomNominal as $kolom) {
                $templateProcessor->setValue(
                    $kolom,
                    number_format(
                        (float) ($header->{$kolom} ?? 0),
                        0,
                        ',',
                        '.'
                    )
                );
            }

            /*
            |--------------------------------------------------------------------------
            | 9. RINCIAN PENJAMIN
            |--------------------------------------------------------------------------
            |
            | Bagian ini menggunakan cloneRow agar jumlah penjamin
            | bisa lebih dari satu.
            |
            */

            if ($penjamin->isNotEmpty()) {
                $templateProcessor->cloneRow(
                    'NAMAPENJAMIN',
                    $penjamin->count()
                );

                foreach ($penjamin as $index => $item) {
                    $row = $index + 1;

                    $templateProcessor->setValue(
                        "NAMAPENJAMIN#{$row}",
                        $item->NAMAPENJAMIN ?? ''
                    );

                    $templateProcessor->setValue(
                        "TOTALPENJAMIN#{$row}",
                        number_format(
                            (float) ($item->TOTAL ?? 0),
                            0,
                            ',',
                            '.'
                        )
                    );
                }
            } else {
                $templateProcessor->setValue('NAMAPENJAMIN', '-');
                $templateProcessor->setValue('TOTALPENJAMIN', '0');
            }

            /*
            |--------------------------------------------------------------------------
            | 10. GET TTD PETUGAS
            |--------------------------------------------------------------------------
            */
            $ttd_petugas = DB::table('simrspku_klaim.tanda_tangan_pegawai as ttd')
                ->where('ttd.nip', $header->NIP)
                // ->where('nip', 1912314)
                ->where('ttd.status', 1)
                ->whereNull('ttd.deleted_at')
                // ->select('ttd.*', DB::raw('master.getNamaLengkapPegawai(ttd.nip) as nama_pegawai'))
                ->inRandomOrder()
                ->first();

            if (!$ttd_petugas) {
                throw new \Exception("Tanda Tangan {$header->PENGGUNA} tidak ditemukan atau belum dilakukan Enrollment.");
            }

            if (
                Storage::disk('public')->exists(
                    $ttd_petugas->signature_path
                )
            ) {
                $this->setImgWord(
                    $templateProcessor,
                    'TTD_PETUGAS',
                    Storage::disk('public')->path(
                        $ttd_petugas->signature_path
                    ),
                    150
                );
            } else {
                throw new \Exception(
                    'File TTD petugas tidak ditemukan: '
                    . $ttd_petugas->signature_path
                );
            }

            /*
            |--------------------------------------------------------------------------
            | 11. SIAPKAN DIREKTORI OUTPUT (SAMA DENGAN generateLab)
            |--------------------------------------------------------------------------
            */

            // Gunakan tanggal masuk kunjungan, bukan tanggal saat PDF dibuat.
            $getTgl = $getSEP->TANGGALMASUK
                ? Carbon::parse($getSEP->TANGGALMASUK)
                : Carbon::now();

            $tahun = $getTgl->format('Y');
            $bulan = $getTgl->format('m');
            $tgl   = $getTgl->format('d');

            // Nama wajib berdasarkan nomor kunjungan, bukan ID tagihan.
            $fileName = $NODOKUMEN . '_billing';

            $relativePath = 'files/klaim/'
                . $tahun . '/'
                . $bulan . '/'
                . $tgl . '/'
                . $fileName;

            $outputDir = storage_path(
                'app/public/files/klaim/'
                . $tahun . '/'
                . $bulan . '/'
                . $tgl
            );

            if (!File::exists($outputDir)) {
                File::makeDirectory($outputDir, 0755, true);
            }

            /*
            |--------------------------------------------------------------------------
            | 12. SIMPAN DOCX SEMENTARA
            |--------------------------------------------------------------------------
            */

            $docxPath = $outputDir . DIRECTORY_SEPARATOR . $fileName . '.docx';
            $pdfPath  = $outputDir . DIRECTORY_SEPARATOR . $fileName . '.pdf';

            $templateProcessor->saveAs($docxPath);

            if (!File::exists($docxPath)) {
                throw new \Exception('Gagal menyimpan DOCX billing: ' . $docxPath);
            }

            // Susun baris visit, ruangan, detail, dan subtotal mengikuti JRXML.
            $this->injectBillingRowsIntoDocx($docxPath, $billingRows);

            /*
            |--------------------------------------------------------------------------
            | UBAH METADATA TITLE DOCX
            |--------------------------------------------------------------------------
            |
            | LibreOffice menggunakan properti dokumen sebagai salah satu
            | sumber metadata judul PDF yang dihasilkan.
            |
            */

            $zip = new \ZipArchive();

            if ($zip->open($docxPath) !== true) {
                throw new \Exception(
                    'Gagal membuka DOCX untuk mengubah judul dokumen.'
                );
            }

            $coreXml = $zip->getFromName('docProps/core.xml');

            if ($coreXml === false) {
                $zip->close();

                throw new \Exception(
                    'Metadata dokumen DOCX tidak ditemukan.'
                );
            }

            $titleXml = htmlspecialchars(
                $NODOKUMEN.'_billing',
                ENT_XML1 | ENT_COMPAT,
                'UTF-8'
            );

            if (preg_match('~<dc:title(?:\s[^>]*)?>.*?</dc:title>~s', $coreXml)) {

                $coreXml = preg_replace(
                    '~<dc:title(?:\s[^>]*)?>.*?</dc:title>~s',
                    '<dc:title>' . $titleXml . '</dc:title>',
                    $coreXml,
                    1
                );

            } else {

                $coreXml = preg_replace(
                    '~</cp:coreProperties>~',
                    '<dc:title>' . $titleXml . '</dc:title></cp:coreProperties>',
                    $coreXml,
                    1
                );
            }

            if (!$zip->addFromString('docProps/core.xml', $coreXml)) {
                $zip->close();

                throw new \Exception(
                    'Gagal menyimpan metadata judul dokumen.'
                );
            }

            $zip->close();

            /*
            |--------------------------------------------------------------------------
            | 13. KONVERSI DOCX KE PDF
            |--------------------------------------------------------------------------
            |
            | Parameter kedua LibreOfficeService adalah DIREKTORI output,
            | bukan path lengkap file PDF.
            */

            // Hapus hasil lama supaya file dengan nama sama selalu dibuat ulang.
            if (File::exists($pdfPath)) {
                File::delete($pdfPath);
            }

            [$pdfSuccess, $convertResult, $convertExitCode] = $this->libreOffice->generatePdf($docxPath, $outputDir);

            /*
            |--------------------------------------------------------------------------
            | 14. VALIDASI HASIL PDF
            |--------------------------------------------------------------------------
            */

            if (!$pdfSuccess || !File::exists($pdfPath)) {
                $convertLog = is_array($convertResult)
                    ? implode(PHP_EOL, $convertResult)
                    : (string) $convertResult;

                throw new \Exception(
                    'PDF billing gagal dibuat oleh LibreOffice. '
                    . 'Exit code: ' . $convertExitCode
                    . ($convertLog !== '' ? '. Detail: ' . $convertLog : '')
                    . '. Target: ' . $pdfPath
                );
            }

            /*
            |--------------------------------------------------------------------------
            | SAVE TO DB
            |--------------------------------------------------------------------------
            */

            DB::table('simrspku_klaim.klaim_file')
                ->updateOrInsert(
                    [
                        'nomor' => $kunjungan,
                        'jenis' => 5,
                    ],
                    [
                        'title' => $fileName . '.pdf',
                        'filename' => $relativePath . '.pdf',
                        'user' => auth()->user()->ID,
                        'status' => true,
                        'deleted_at' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );


            /*
            |--------------------------------------------------------------------------
            | 15. HAPUS DOCX SEMENTARA
            |--------------------------------------------------------------------------
            */

            File::delete($docxPath);

            /*
            |--------------------------------------------------------------------------
            | 16. KEMBALIKAN PDF
            |--------------------------------------------------------------------------
            */

            return response()->file($pdfPath, [
                'Content-Type' => 'application/pdf',

                'Content-Disposition' => 'inline; filename="' . $NODOKUMEN . '_billing.pdf"',

                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma'        => 'no-cache',
                'Expires'       => '0',
            ]);

        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mengganti empat baris prototype di tabel rincian billing dengan baris
     * yang tersusun dinamis: visit, ruangan, detail, lalu subtotal.
     *
     * Proses ini menjaga layout masing-masing jenis baris di template DOCX.
     */
    private function injectBillingRowsIntoDocx(string $docxPath, array $billingRows): void
    {
        $zip = new \ZipArchive();
        $opened = $zip->open($docxPath);

        if ($opened !== true) {
            throw new \RuntimeException('Tidak dapat membuka DOCX untuk menyusun baris billing.');
        }

        try {
            $xml = $zip->getFromName('word/document.xml');

            if ($xml === false) {
                throw new \RuntimeException('Dokumen Word tidak memiliki word/document.xml.');
            }

            $dom = new \DOMDocument('1.0', 'UTF-8');
            $dom->preserveWhiteSpace = true;
            $dom->formatOutput = false;

            if (!$dom->loadXML($xml, LIBXML_NONET)) {
                throw new \RuntimeException('Gagal membaca XML template billing.');
            }

            $wordNs = 'http://schemas.openxmlformats.org/wordprocessingml/2006/main';
            $xpath = new \DOMXPath($dom);
            $xpath->registerNamespace('w', $wordNs);

            $markers = [
                'visit' => '${BILLING_VISIT_LABEL}',
                'room' => '${BILLING_ROOM_LABEL}',
                'item' => '${LAYANAN1}',
                'subtotal' => '${BILLING_SUBTOTAL_LABEL}',
            ];

            $targetTable = null;
            $prototypeNodes = [];

            foreach ($xpath->query('//w:tbl') as $tableNode) {
                $found = [];

                foreach ($xpath->query('./w:tr', $tableNode) as $rowNode) {
                    $rowText = $rowNode->textContent;

                    foreach ($markers as $type => $marker) {
                        if (strpos($rowText, $marker) !== false) {
                            $found[$type] = $rowNode;
                        }
                    }
                }

                if (count($found) === count($markers)) {
                    $targetTable = $tableNode;
                    $prototypeNodes = $found;
                    break;
                }
            }

            if (!$targetTable || count($prototypeNodes) !== count($markers)) {
                throw new \RuntimeException(
                    'Prototype baris billing tidak lengkap. Pastikan template DOCX terbaru digunakan.'
                );
            }

            // Simpan salinan prototype sebelum baris sumber dihapus dari tabel.
            $prototypeXml = [];
            foreach ($prototypeNodes as $type => $node) {
                $prototypeXml[$type] = $node->cloneNode(true);
            }

            foreach ($prototypeNodes as $node) {
                if ($node->parentNode === $targetTable) {
                    $targetTable->removeChild($node);
                }
            }

            foreach ($billingRows as $billingRow) {
                $type = $billingRow['type'] ?? '';

                if (!isset($prototypeXml[$type])) {
                    throw new \RuntimeException('Jenis baris billing tidak dikenal: ' . $type);
                }

                /** @var \DOMElement $newRow */
                $newRow = $prototypeXml[$type]->cloneNode(true);
                $values = $billingRow['values'] ?? [];
                $textNodes = [];

                foreach ($xpath->query('.//w:t', $newRow) as $textNode) {
                    $textNodes[] = $textNode;
                }

                foreach ($textNodes as $textNode) {
                    $newText = $textNode->nodeValue;

                    foreach ($values as $key => $value) {
                        $newText = str_replace(
                            '${' . $key . '}',
                            (string) ($value ?? ''),
                            $newText
                        );
                    }

                    if ($newText === $textNode->nodeValue) {
                        continue;
                    }

                    // Word membutuhkan w:br untuk line break, bukan newline literal di w:t.
                    $parts = preg_split('/\r\n|\r|\n/', $newText);
                    $parent = $textNode->parentNode;

                    if (!$parent) {
                        continue;
                    }

                    foreach ($parts as $index => $part) {
                        $newTextNode = $dom->createElementNS($wordNs, 'w:t');
                        $newTextNode->appendChild($dom->createTextNode($part));

                        if ($part !== trim($part) || $part === '') {
                            $newTextNode->setAttributeNS(
                                'http://www.w3.org/XML/1998/namespace',
                                'xml:space',
                                'preserve'
                            );
                        }

                        $parent->insertBefore($newTextNode, $textNode);

                        if ($index < count($parts) - 1) {
                            $breakNode = $dom->createElementNS($wordNs, 'w:br');
                            $parent->insertBefore($breakNode, $textNode);
                        }
                    }

                    $parent->removeChild($textNode);
                }

                // Appending after the column heading preserves the row sequence above.
                $targetTable->appendChild($newRow);
            }

            $zip->deleteName('word/document.xml');

            if (!$zip->addFromString('word/document.xml', $dom->saveXML())) {
                throw new \RuntimeException('Gagal menulis XML hasil billing ke DOCX.');
            }
        } finally {
            $zip->close();
        }
    }

    private function getBillingHeader(string $nomorTagihan): ?object
    {
        return DB::table('pembayaran.tagihan as t')
            ->leftJoin('master.pasien as p', 'p.NORM', '=', 't.REF')
            ->leftJoin('master.referensi as rjk', function ($join) {
                $join->on('rjk.ID', '=', 'p.JENIS_KELAMIN')
                    ->where('rjk.JENIS', '=', 2);
            })
            ->leftJoin('pembayaran.tagihan_pendaftaran as tp', function ($join) {
                $join->on('tp.TAGIHAN', '=', 't.ID')
                    ->where('tp.UTAMA', '=', 1)
                    ->where('tp.STATUS', '=', 1);
            })
            ->leftJoin('pendaftaran.pendaftaran as pd', 'pd.NOMOR', '=', 'tp.PENDAFTARAN')
            ->leftJoin('master.kartu_asuransi_pasien as kap', function ($join) {
                $join->on('kap.NORM', '=', 'pd.NORM')
                    ->where('kap.JENIS', '=', 2);
            })
            ->leftJoin('pendaftaran.penjamin as pj', 'pj.NOPEN', '=', 'pd.NOMOR')
            ->leftJoin('master.referensi as rf', function ($join) {
                $join->on('rf.ID', '=', 'pj.JENIS')
                    ->where('rf.JENIS', '=', 10);
            })
            ->leftJoin('pembayaran.pembayaran_tagihan as pt', function ($join) {
                $join->on('pt.TAGIHAN', '=', 't.ID')
                    ->where('pt.JENIS', '=', 1)
                    ->where('pt.STATUS', '=', 2);
            })
            ->leftJoin('pembayaran.penjamin_tagihan as pjt', function ($join) {
                $join->on('pjt.TAGIHAN', '=', 't.ID')
                    ->where('pjt.KE', '=', 1);
            })
            ->leftJoin('aplikasi.pengguna as us', 'us.ID', '=', 'pt.OLEH')
            ->leftJoin('master.pegawai as mp', 'mp.NIP', '=', 'us.NIP')
            ->leftJoin('aplikasi.pengguna as usu', 'usu.ID', '=', 'pt.DIUBAH_OLEH')
            ->leftJoin('simrspku_klaim.klaim_qrcode_pegawai as qp', 'qp.nomor', '=', 'mp.NIP')
            ->leftJoin('simrspku_klaim.klaim_qrcode as kq', 'kq.nomor', '=', 'pd.NORM')
            ->leftJoin('master.diagnosa_masuk as dm', 'dm.ID', '=', 'pd.DIAGNOSA_MASUK')
            ->join('aplikasi.instansi as i', DB::raw('1'), '=', DB::raw('1'))
            ->join('master.ppk as ppk', 'ppk.ID', '=', 'i.PPK')
            ->join('master.wilayah as w', 'w.ID', '=', 'ppk.WILAYAH')
            ->where('t.ID', $nomorTagihan)
            ->where('t.JENIS', 1)
            ->whereIn('t.STATUS', [1, 2])
            ->selectRaw("
                i.PPK,
                ppk.NAMA AS NAMAINSTANSI,
                ppk.ALAMAT AS ALAMATINSTANSI,
                t.ID AS NOMOR_TAGIHAN,

                INSERT(
                    INSERT(
                        INSERT(LPAD(p.NORM, 8, '0'), 3, 0, '-'),
                        6, 0, '-'
                    ),
                    9, 0, '-'
                ) AS NORM,

                p.NORM AS RM,
                pd.NOMOR AS NOPEN,

                (
                    SELECT tp2.PENDAFTARAN
                    FROM pembayaran.tagihan_pendaftaran tp2
                    WHERE tp2.TAGIHAN = t.ID
                    ORDER BY tp2.PENDAFTARAN ASC
                    LIMIT 1
                ) AS PENDAFTARAN_PERTAMA,

                (
                    SELECT DATE_FORMAT(pd2.TANGGAL, '%d-%m-%Y %H:%i:%s')
                    FROM pembayaran.tagihan_pendaftaran tp2
                    JOIN pendaftaran.pendaftaran pd2
                        ON pd2.NOMOR = tp2.PENDAFTARAN
                    WHERE tp2.TAGIHAN = t.ID
                    ORDER BY tp2.PENDAFTARAN ASC
                    LIMIT 1
                ) AS TANGGALREG,

                master.getNamaLengkap(p.NORM) AS NAMALENGKAP,
                master.getAlamatPasienCustom(p.NORM) AS ALAMATLENGKAP,

                master.getNamaLengkapPegawai((
                    SELECT dkt.NIP
                    FROM master.dokter dkt
                    JOIN pendaftaran.kunjungan kjg
                        ON kjg.NOPEN = pd.NOMOR
                    WHERE dkt.ID = kjg.DPJP
                    AND kjg.REF IS NULL
                    ORDER BY kjg.MASUK DESC
                    LIMIT 1
                )) AS DPJP,

                master.getNamaLengkapKeluarga(p.NORM)
                    AS NAMA_KELUARGA_PASIEN,

                NOW() AS `NOW`,
                pj.JENIS AS IDCARABAYAR,
                kap.NOMOR AS NOMORKARTU,
                rf.DESKRIPSI AS CARABAYAR,
                p.TANGGAL_LAHIR,

                CONCAT(
                    CAST(rjk.DESKRIPSI AS CHAR(15)),
                    ' (',
                    master.getCariUmur(pd.TANGGAL, p.TANGGAL_LAHIR),
                    ')'
                ) AS UMUR,

                COALESCE(
                    NULLIF(us.NIP, 0),
                    NULLIF(mp.NIP, 0),
                    NULLIF(usu.NIP, 0)
                ) AS NIP,

                qp.filename AS QR1,
                kq.filename AS QR2,

                IF(
                    us.NIP IS NOT NULL AND us.NIP != 0,
                    master.getNamaLengkapPegawai(us.NIP),
                    IF(
                        mp.NIP IS NOT NULL AND mp.NIP != 0,
                        master.getNamaLengkapPegawai(mp.NIP),
                        master.getNamaLengkapPegawai(usu.NIP)
                    )
                ) AS PENGGUNA,

                t.ID AS IDTAGIHAN,
                t.TOTAL + pembayaran.getTarifAmbulance(t.ID) AS TOTALRS,
                w.DESKRIPSI AS WILAYAH,

                pembayaran.getInfoTagihanKunjungan(t.ID)
                    AS JENISKUNJUNGAN,

                IF(pt.TANGGAL IS NULL, SYSDATE(), pt.TANGGAL)
                    AS TANGGALBAYAR,

                t.TANGGAL AS TANGGALTAGIHAN,

                (
                    IF(
                        pj.JENIS = 2 AND pjt.NAIK_KELAS = 1,
                        pjt.TOTAL_NAIK_KELAS,
                        IF(
                            pj.JENIS = 2 AND pjt.NAIK_KELAS_VIP = 1,
                            pjt.TARIF_INACBG_KELAS1,
                            t.TOTAL + pembayaran.getTarifAmbulance(t.ID)
                        )
                    )
                    + IFNULL(pjt.SELISIH_MINIMAL, 0)
                ) AS TOTALTAGIHAN,

                (
                    pembayaran.getTotalDiskon(t.ID)
                    + pembayaran.getTotalDiskonDokter(t.ID)
                ) AS TOTALDISKON,

                pembayaran.getTotalNonTunai(t.ID) AS TOTALEDC,

                pembayaran.getTotalPenjaminTagihan(t.ID)
                    AS TOTALPENJAMINTAGIHAN,

                (
                    pembayaran.getTotalPiutangPasien(t.ID)
                    + pembayaran.getTotalPiutangPerusahaan(t.ID)
                ) AS TOTALPIUTANG,

                (
                    pembayaran.getTotalDeposit(t.ID)
                    - pembayaran.getTotalPengembalianDeposit(t.ID)
                ) AS TOTALDEPOSIT,

                pembayaran.getTotalSubsidiTagihan(t.ID)
                    AS TOTALSUBSIDI,

                (
                    IF(
                        pt.TOTAL IS NULL,
                        IF(
                            ROUND(pembayaran.getTotalTagihanPembayaran(t.ID))
                            + ROUND(t.PEMBULATAN) < 0,
                            0,
                            ROUND(pembayaran.getTotalTagihanPembayaran(t.ID))
                            + ROUND(t.PEMBULATAN)
                        ),
                        pt.TOTAL
                    )
                    + pembayaran.getTarifAmbulance(t.ID)
                ) AS TOTALJUMLAHBAYAR,

                (
                    IF(
                        pt.TOTAL IS NULL,
                        IF(
                            ROUND(pembayaran.getTotalTagihanPembayaran(t.ID))
                            + ROUND(t.PEMBULATAN) < 0,
                            0,
                            ROUND(pembayaran.getTotalTagihanPembayaran(t.ID))
                            + ROUND(t.PEMBULATAN)
                        ),
                        pt.TOTAL
                    )
                    - (
                        pembayaran.getTotalDeposit(t.ID)
                        - pembayaran.getTotalPengembalianDeposit(t.ID)
                    )
                    + pembayaran.getTarifAmbulance(t.ID)
                ) AS JUMLAHBAYAR,

                ROUND(t.PEMBULATAN) AS PEMBULATAN,

                IF(
                    INSTR(dm.DIAGNOSA, 'B20') > 1
                    OR INSTR(dm.DIAGNOSA, 'HIV') > 1,
                    '',
                    dm.DIAGNOSA
                ) AS DIAGNOSA,

                (
                    SELECT DATE_FORMAT(pl.TANGGAL, '%d-%m-%Y %H:%i:%s')
                    FROM layanan.pasien_pulang pl
                    WHERE pl.NOPEN = pd.NOMOR
                    AND pl.STATUS != 0
                    LIMIT 1
                ) AS TGLKELUAR,

                pj.NOMOR AS NOSEP
            ")
            ->first();
    }

    private function getBillingPenjamin(string $nomorTagihan)
    {
        return DB::table('pembayaran.penjamin_tagihan as ppt')
            ->leftJoin('master.referensi as crbyr', function ($join) {
                $join->on('ppt.PENJAMIN', '=', 'crbyr.ID')
                    ->where('crbyr.JENIS', '=', 10);
            })
            ->where('ppt.TAGIHAN', $nomorTagihan)
            ->selectRaw("
                crbyr.DESKRIPSI AS NAMAPENJAMIN,
                SUM(ppt.TOTAL) AS TOTAL
            ")
            ->groupBy('ppt.PENJAMIN', 'crbyr.DESKRIPSI')
            ->get();
    }

    private function getBillingDetail(string $nomorTagihan)
    {
        $administrasi = $this->queryBillingAdministrasi($nomorTagihan);
        $paket = $this->queryBillingPaket($nomorTagihan);
        $kunjungan = $this->queryBillingKunjungan($nomorTagihan);
        $tindakanMedis = $this->queryBillingTindakanMedis($nomorTagihan);
        $tindakanKeperawatan = $this->queryBillingTindakanKeperawatan($nomorTagihan);
        $farmasi = $this->queryBillingFarmasi($nomorTagihan);
        $jenisLain = $this->queryBillingJenisLain($nomorTagihan);

        /*
        * Semua query wajib menghasilkan kolom yang sama dan dalam
        * urutan yang sama seperti TEMP_DETIL_RINCIAN.
        *
        * Termasuk:
        * TAGIHAN, RUANGAN, LAYANAN, TANGGAL, JUMLAH, TARIF,
        * JENIS_KUNJUNGAN, DOKTER, DOKTERKEDUA, DOKTERANASTESI,
        * ADMINISTRASI, SARANA, BHP, DOKTER_OPERATOR,
        * DOKTER_ANASTESI, DOKTER_LAINNYA, PENATA_ANASTESI,
        * PARAMEDIS, NON_MEDIS, STATUSTINDAKANRINCIAN,
        * LAYANAN1, LAYANAN_OK, TARIF_LAYANAN_OK, RP.
        */

        $query = $administrasi
            ->unionAll($paket)
            ->unionAll($kunjungan)
            ->unionAll($tindakanMedis)
            ->unionAll($tindakanKeperawatan)
            ->unionAll($farmasi)
            ->unionAll($jenisLain);

        return DB::query()
            ->fromSub($query, 'billing')
            ->orderBy('JENIS_KUNJUNGAN')
            ->orderBy('TANGGAL')
            ->get();
    }

    private function queryBillingAdministrasi(string $nomorTagihan)
    {
        return DB::table('pembayaran.rincian_tagihan as rt')
            ->leftJoin('cetakan.kartu_pasien as krtp', 'krtp.ID', '=', 'rt.REF_ID')
            ->leftJoin('cetakan.karcis_pasien as kp', function ($join) {
                $join->on('kp.ID', '=', 'rt.REF_ID')
                    ->where('rt.JENIS', '=', 1);
            })
            ->leftJoin('pendaftaran.pendaftaran as p', function ($join) {
                $join->on('p.NOMOR', '=', 'kp.NOPEN')
                    ->where('p.STATUS', '!=', 0);
            })
            ->leftJoin('master.tarif_administrasi as tadm', 'tadm.ID', '=', 'rt.TARIF_ID')
            ->leftJoin('master.administrasi as adm', 'adm.ID', '=', 'tadm.ADMINISTRASI')
            ->leftJoin('pendaftaran.tujuan_pasien as tp', 'tp.NOPEN', '=', 'p.NOMOR')
            ->leftJoin('pendaftaran.reservasi as res', 'res.NOMOR', '=', 'tp.RESERVASI')
            ->leftJoin('master.ruang_kamar_tidur as rkt', 'rkt.ID', '=', 'res.RUANG_KAMAR_TIDUR')
            ->leftJoin('master.ruang_kamar as rk', 'rk.ID', '=', 'rkt.RUANG_KAMAR')
            ->leftJoin('master.ruangan as r', 'r.ID', '=', 'rk.RUANGAN')
            ->leftJoin('master.ruangan as r1', 'r1.ID', '=', 'tp.RUANGAN')
            ->leftJoin('master.referensi as kls', function ($join) {
                $join->on('kls.ID', '=', 'rk.KELAS')
                    ->where('kls.JENIS', '=', 19);
            })
            ->leftJoin('pendaftaran.kunjungan as kj', function ($join) {
                $join->on('kj.NOMOR', '=', 'rt.REF_ID')
                    ->whereIn('rt.TARIF_ID', [3, 4]);
            })
            ->leftJoin('master.ruangan as r2', 'r2.ID', '=', 'kj.RUANGAN')
            ->where('rt.TAGIHAN', $nomorTagihan)
            ->where('rt.JENIS', 1)
            ->where('rt.STATUS', 1)
            ->selectRaw("
                rt.TAGIHAN,

                CONCAT(
                    IF(
                        r.JENIS_KUNJUNGAN = 3,
                        CONCAT(
                            CONVERT(r.DESKRIPSI USING utf8mb4),
                            _utf8mb4' (',
                            CONVERT(rk.KAMAR USING utf8mb4),
                            _utf8mb4'/',
                            CONVERT(rkt.TEMPAT_TIDUR USING utf8mb4),
                            _utf8mb4'/',
                            CONVERT(kls.DESKRIPSI USING utf8mb4),
                            _utf8mb4')'
                        ),
                        CONVERT(
                            IF(
                                r1.DESKRIPSI IS NOT NULL,
                                r1.DESKRIPSI,
                                r2.DESKRIPSI
                            ) USING utf8mb4
                        )
                    )
                ) AS RUANGAN,

                IF(
                    r1.JENIS_KUNJUNGAN = 15,
                    'Administrasi',
                    adm.NAMA
                ) AS LAYANAN,

                IF(
                    r1.JENIS_KUNJUNGAN = 15,
                    p.TANGGAL,
                    IF(
                        rt.JENIS = 1,
                        IF(
                            tadm.ADMINISTRASI = 1,
                            krtp.TANGGAL,
                            IF(tadm.ADMINISTRASI = 2, kp.TANGGAL, kj.KELUAR)
                        ),
                        NULL
                    )
                ) AS TANGGAL,

                rt.JUMLAH,
                rt.TARIF,

                IF(
                    r.JENIS_KUNJUNGAN = 3,
                    r.JENIS_KUNJUNGAN,
                    r1.JENIS_KUNJUNGAN
                ) AS JENIS_KUNJUNGAN,

                '' AS DOKTER,
                '' AS DOKTERKEDUA,
                '' AS DOKTERANASTESI,

                0 AS ADMINISTRASI,
                0 AS SARANA,
                0 AS BHP,
                0 AS DOKTER_OPERATOR,
                0 AS DOKTER_ANASTESI,
                0 AS DOKTER_LAINNYA,
                0 AS PENATA_ANASTESI,
                0 AS PARAMEDIS,
                0 AS NON_MEDIS,
                0 AS STATUSTINDAKANRINCIAN,

                IF(
                    r1.JENIS_KUNJUNGAN = 15,
                    'Administrasi',
                    adm.NAMA
                ) AS LAYANAN1,

                '' AS LAYANAN_OK,
                '' AS TARIF_LAYANAN_OK,
                '' AS RP
            ");
    }

    private function queryBillingPaket(string $nomorTagihan)
    {
        return DB::table('pembayaran.rincian_tagihan as rt')
            ->leftJoin('pendaftaran.pendaftaran as p', function ($join) {
                $join->on('p.NOMOR', '=', 'rt.REF_ID')
                    ->where('rt.JENIS', '=', 5)
                    ->where('p.STATUS', '!=', 0);
            })
            ->leftJoin('master.paket as pkt', 'pkt.ID', '=', 'p.PAKET')
            ->leftJoin('master.distribusi_tarif_paket as dtp', function ($join) {
                $join->on('dtp.PAKET', '=', 'pkt.ID')
                    ->where('dtp.STATUS', '=', 1);
            })
            ->leftJoin('pendaftaran.tujuan_pasien as tp', 'tp.NOPEN', '=', 'p.NOMOR')
            ->leftJoin('pendaftaran.reservasi as res', 'res.NOMOR', '=', 'tp.RESERVASI')
            ->leftJoin('master.ruang_kamar_tidur as rkt', 'rkt.ID', '=', 'res.RUANG_KAMAR_TIDUR')
            ->leftJoin('master.ruang_kamar as rk', 'rk.ID', '=', 'rkt.RUANG_KAMAR')
            ->leftJoin('master.ruangan as r', 'r.ID', '=', 'rk.RUANGAN')
            ->leftJoin('master.referensi as kls', function ($join) {
                $join->on('kls.ID', '=', 'rk.KELAS')
                    ->where('kls.JENIS', '=', 19);
            })
            ->where('rt.TAGIHAN', $nomorTagihan)
            ->where('rt.JENIS', 5)
            ->where('rt.STATUS', 1)
            ->selectRaw("
                rt.TAGIHAN,

                CONCAT(
                    r.DESKRIPSI,
                    IF(
                        r.JENIS_KUNJUNGAN = 3,
                        CONCAT(
                            ' (', rk.KAMAR, '/',
                            rkt.TEMPAT_TIDUR, '/',
                            kls.DESKRIPSI, ')'
                        ),
                        ''
                    )
                ) AS RUANGAN,

                pkt.NAMA AS LAYANAN,

                p.TANGGAL AS TANGGAL,
                rt.JUMLAH,
                rt.TARIF,
                r.JENIS_KUNJUNGAN,

                '' AS DOKTER,
                '' AS DOKTERKEDUA,
                '' AS DOKTERANASTESI,

                0 AS ADMINISTRASI,
                0 AS SARANA,
                0 AS BHP,
                0 AS DOKTER_OPERATOR,
                0 AS DOKTER_ANASTESI,
                0 AS DOKTER_LAINNYA,
                0 AS PENATA_ANASTESI,
                0 AS PARAMEDIS,
                0 AS NON_MEDIS,
                0 AS STATUSTINDAKANRINCIAN,

                pkt.NAMA AS LAYANAN1,
                '' AS LAYANAN_OK,
                '' AS TARIF_LAYANAN_OK,
                '' AS RP
            ");
    }

    private function queryBillingKunjungan(string $nomorTagihan)
    {
        return DB::table('pembayaran.rincian_tagihan as rt')
            ->leftJoin('pendaftaran.kunjungan as kjgn', function ($join) {
                $join->on('kjgn.NOMOR', '=', 'rt.REF_ID')
                    ->where('rt.JENIS', '=', 2)
                    ->where('kjgn.STATUS', '!=', 0);
            })
            ->leftJoin('pendaftaran.pendaftaran as p', function ($join) {
                $join->on('p.NOMOR', '=', 'kjgn.NOPEN')
                    ->where('p.STATUS', '!=', 0);
            })
            ->leftJoin('master.ruangan as r', 'r.ID', '=', 'kjgn.RUANGAN')
            ->leftJoin('master.ruang_kamar_tidur as rkt', 'rkt.ID', '=', 'kjgn.RUANG_KAMAR_TIDUR')
            ->leftJoin('master.ruang_kamar as rk', 'rk.ID', '=', 'rkt.RUANG_KAMAR')
            ->leftJoin('master.referensi as kls', function ($join) {
                $join->on('kls.ID', '=', 'rk.KELAS')
                    ->where('kls.JENIS', '=', 19);
            })
            ->where('rt.TAGIHAN', $nomorTagihan)
            ->where('rt.JENIS', 2)
            ->where('rt.STATUS', 1)
            ->selectRaw("
                rt.TAGIHAN,

                CONCAT(
                    r.DESKRIPSI,
                    IF(
                        r.JENIS_KUNJUNGAN = 3,
                        CONCAT(
                            ' (', rk.KAMAR, '/',
                            rkt.TEMPAT_TIDUR, '/',
                            kls.DESKRIPSI, ')'
                        ),
                        ''
                    )
                ) AS RUANGAN,

                IF(
                    r.JENIS_KUNJUNGAN = 3,
                    CONCAT(
                        ' (', rk.KAMAR, '/',
                        rkt.TEMPAT_TIDUR, '/',
                        kls.DESKRIPSI, ')'
                    ),
                    ''
                ) AS LAYANAN,

                kjgn.MASUK AS TANGGAL,
                rt.JUMLAH,

                rt.TARIF - IF(
                    rt.PERSENTASE_DISKON = 0,
                    rt.DISKON,
                    rt.TARIF * (rt.DISKON / 100)
                ) AS TARIF,

                r.JENIS_KUNJUNGAN,

                '' AS DOKTER,
                '' AS DOKTERKEDUA,
                '' AS DOKTERANASTESI,

                0 AS ADMINISTRASI,
                0 AS SARANA,
                0 AS BHP,
                0 AS DOKTER_OPERATOR,
                0 AS DOKTER_ANASTESI,
                0 AS DOKTER_LAINNYA,
                0 AS PENATA_ANASTESI,
                0 AS PARAMEDIS,
                0 AS NON_MEDIS,
                0 AS STATUSTINDAKANRINCIAN,

                IF(
                    r.JENIS_KUNJUNGAN = 3,
                    CONCAT(
                        ' (', rk.KAMAR, '/',
                        rkt.TEMPAT_TIDUR, '/',
                        kls.DESKRIPSI, ')'
                    ),
                    ''
                ) AS LAYANAN1,

                '' AS LAYANAN_OK,
                '' AS TARIF_LAYANAN_OK,
                '' AS RP
            ");
    }

    private function queryBillingTindakanMedis($tagihan)
    {
        $dokterOperator = "
            (
                SELECT CONVERT(
                    master.getNamaLengkapPegawai(mpdok.NIP)
                    USING utf8mb4
                )
                FROM layanan.petugas_tindakan_medis ptm
                LEFT JOIN master.dokter dok
                    ON ptm.MEDIS = dok.ID
                LEFT JOIN master.pegawai mpdok
                    ON dok.NIP = mpdok.NIP
                WHERE ptm.TINDAKAN_MEDIS = rt.REF_ID
                    AND ptm.JENIS = 1
                    AND ptm.MEDIS != 0
                    AND ptm.KE = 1
                    AND ptm.STATUS != 0
                LIMIT 1
            )
        ";

        $dokterAsisten = "
            (
                SELECT master.getNamaLengkapPegawai(mpdok.NIP)
                FROM layanan.petugas_tindakan_medis ptm
                LEFT JOIN master.dokter dok
                    ON ptm.MEDIS = dok.ID
                LEFT JOIN master.pegawai mpdok
                    ON dok.NIP = mpdok.NIP
                WHERE ptm.TINDAKAN_MEDIS = rt.REF_ID
                    AND ptm.JENIS = 1
                    AND ptm.MEDIS != 0
                    AND ptm.KE = 2
                    AND ptm.STATUS != 0
                LIMIT 1
            )
        ";

        $dokterAnestesi = "
            (
                SELECT master.getNamaLengkapPegawai(mpdok.NIP)
                FROM layanan.petugas_tindakan_medis ptm
                LEFT JOIN master.dokter dok
                    ON ptm.MEDIS = dok.ID
                LEFT JOIN master.pegawai mpdok
                    ON dok.NIP = mpdok.NIP
                WHERE ptm.TINDAKAN_MEDIS = rt.REF_ID
                    AND ptm.JENIS = 2
                    AND ptm.MEDIS != 0
                    AND ptm.KE = 1
                    AND ptm.STATUS != 0
                LIMIT 1
            )
        ";

        $query = DB::table('pembayaran.rincian_tagihan as rt')
            ->leftJoin('layanan.tindakan_medis as tm', function ($join) {
                $join->on('tm.ID', '=', 'rt.REF_ID')
                    ->where('tm.STATUS', '!=', 0);
            })
            ->leftJoin('master.tindakan as t', 't.ID', '=', 'tm.TINDAKAN')
            ->leftJoin('pendaftaran.kunjungan as kjgn', function ($join) {
                $join->on('kjgn.NOMOR', '=', 'tm.KUNJUNGAN')
                    ->where('kjgn.STATUS', '!=', 0);
            })
            ->leftJoin('pendaftaran.pendaftaran as p', function ($join) {
                $join->on('p.NOMOR', '=', 'kjgn.NOPEN')
                    ->where('p.STATUS', '!=', 0);
            })
            ->leftJoin('master.ruangan as r', 'r.ID', '=', 'kjgn.RUANGAN')
            ->leftJoin(
                'master.ruang_kamar_tidur as rkt',
                'rkt.ID',
                '=',
                'kjgn.RUANG_KAMAR_TIDUR'
            )
            ->leftJoin('master.ruang_kamar as rk', 'rk.ID', '=', 'rkt.RUANG_KAMAR')
            ->leftJoin('master.referensi as kls', function ($join) {
                $join->on('kls.ID', '=', 'rk.KELAS')
                    ->where('kls.JENIS', '=', 19);
            })
            ->leftJoin('master.tarif_tindakan as mtt', 'mtt.ID', '=', 'rt.TARIF_ID')
            ->leftJoin('master.tindakan_rincian as tr', function ($join) {
                $join->on('tr.TINDAKAN', '=', 'mtt.TINDAKAN')
                    ->where('tr.STATUS', '=', 1);
            })
            ->leftJoin('master.tindakan_keperawatan as tk', function ($join) {
                $join->on('tk.TINDAKAN', '=', 'tm.TINDAKAN')
                    ->where('tk.STATUS', '=', 1);
            })
            ->where('rt.TAGIHAN', $tagihan)
            ->where('rt.JENIS', 3)
            ->where('rt.STATUS', 1)
            ->whereNull('tk.ID')
            ->selectRaw("
                rt.TAGIHAN,

                CONCAT(
                    CONVERT(r.DESKRIPSI USING utf8mb4),
                    IF(
                        r.JENIS_KUNJUNGAN = 3,
                        CONCAT(
                            _utf8mb4' (',
                            CONVERT(rk.KAMAR USING utf8mb4),
                            _utf8mb4'/',
                            CONVERT(rkt.TEMPAT_TIDUR USING utf8mb4),
                            _utf8mb4'/',
                            CONVERT(kls.DESKRIPSI USING utf8mb4),
                            _utf8mb4')'
                        ),
                        _utf8mb4''
                    )
                ) AS RUANGAN,

                CONVERT(t.NAMA USING utf8mb4) AS LAYANAN,
                tm.TANGGAL AS TANGGAL,
                rt.JUMLAH,
                rt.TARIF + pembayaran.getTarifAmbulance(rt.TAGIHAN) AS TARIF,
                r.JENIS_KUNJUNGAN,

                IF(
                    {$dokterOperator} IS NULL,
                    '',
                    CONCAT(' [', {$dokterOperator}, ']')
                ) AS DOKTER,

                IF(
                    {$dokterAsisten} IS NULL,
                    '',
                    CONCAT(' [', {$dokterAsisten}, ']')
                ) AS DOKTERKEDUA,

                IF(
                    {$dokterAnestesi} IS NULL,
                    '',
                    CONCAT(' [', {$dokterAnestesi}, ']')
                ) AS DOKTERANASTESI,

                mtt.ADMINISTRASI,
                mtt.SARANA,
                mtt.BHP,
                mtt.DOKTER_OPERATOR,
                mtt.DOKTER_ANASTESI,
                mtt.DOKTER_LAINNYA,
                mtt.PENATA_ANASTESI,
                mtt.PARAMEDIS,
                mtt.NON_MEDIS,

                IF(tr.ID IS NULL, 0, 1) AS STATUSTINDAKANRINCIAN,

                CONCAT(
                    CONVERT(t.NAMA USING utf8mb4),
                    IF(
                        {$dokterOperator} IS NULL,
                        _utf8mb4'',
                        CONCAT(
                            _utf8mb4' [',
                            {$dokterOperator},
                            _utf8mb4']'
                        )
                    )
                ) AS LAYANAN1,

                CONCAT(
                    t.NAMA, '\\r',
                    SPACE(3), 'Jasa dr. Operator ',
                    IFNULL({$dokterOperator}, ''), '\\r',
                    SPACE(3), 'Jasa dr. Anastesi ',
                    IFNULL({$dokterAnestesi}, ''), '\\r',
                    SPACE(3), 'Jasa dr. Asisten ',
                    IFNULL({$dokterAsisten}, ''), '\\r',
                    SPACE(3), 'Jasa Penata Anastesi ', '\\r'
                ) AS LAYANAN_OK,

                CONCAT(
                    ' ', '\\r',
                    REPLACE(FORMAT(mtt.DOKTER_OPERATOR, 0), ',', '.'), '\\r',
                    REPLACE(FORMAT(mtt.DOKTER_ANASTESI, 0), ',', '.'), '\\r',
                    REPLACE(FORMAT(mtt.DOKTER_LAINNYA, 0), ',', '.'), '\\r',
                    REPLACE(FORMAT(mtt.PENATA_ANASTESI, 0), ',', '.'), '\\r'
                ) AS TARIF_LAYANAN_OK,

                CONCAT(
                    ' ', '\\r',
                    'Rp.', '\\r',
                    'Rp.', '\\r',
                    'Rp.', '\\r',
                    'Rp.'
                ) AS RP
            ");

        return $query;
    }

    private function queryBillingTindakanKeperawatan($tagihan)
    {
        $dokterOperator = "
            (
                SELECT master.getNamaLengkapPegawai(mpdok.NIP)
                FROM layanan.petugas_tindakan_medis ptm
                LEFT JOIN master.dokter dok
                    ON ptm.MEDIS = dok.ID
                LEFT JOIN master.pegawai mpdok
                    ON dok.NIP = mpdok.NIP
                WHERE ptm.TINDAKAN_MEDIS = rt.REF_ID
                    AND ptm.JENIS = 1
                    AND ptm.MEDIS != 0
                    AND ptm.KE = 1
                    AND ptm.STATUS != 0
                LIMIT 1
            )
        ";

        $dokterAsisten = "
            (
                SELECT master.getNamaLengkapPegawai(mpdok.NIP)
                FROM layanan.petugas_tindakan_medis ptm
                LEFT JOIN master.dokter dok
                    ON ptm.MEDIS = dok.ID
                LEFT JOIN master.pegawai mpdok
                    ON dok.NIP = mpdok.NIP
                WHERE ptm.TINDAKAN_MEDIS = rt.REF_ID
                    AND ptm.JENIS = 1
                    AND ptm.MEDIS != 0
                    AND ptm.KE = 2
                    AND ptm.STATUS != 0
                LIMIT 1
            )
        ";

        $dokterAnestesi = "
            (
                SELECT master.getNamaLengkapPegawai(mpdok.NIP)
                FROM layanan.petugas_tindakan_medis ptm
                LEFT JOIN master.dokter dok
                    ON ptm.MEDIS = dok.ID
                LEFT JOIN master.pegawai mpdok
                    ON dok.NIP = mpdok.NIP
                WHERE ptm.TINDAKAN_MEDIS = rt.REF_ID
                    AND ptm.JENIS = 2
                    AND ptm.MEDIS != 0
                    AND ptm.KE = 1
                    AND ptm.STATUS != 0
                LIMIT 1
            )
        ";

        return DB::table('pembayaran.rincian_tagihan as rt')
            ->leftJoin('layanan.tindakan_medis as tm', function ($join) {
                $join->on('tm.ID', '=', 'rt.REF_ID')
                    ->where('tm.STATUS', '!=', 0);
            })
            ->leftJoin('master.tindakan as t', 't.ID', '=', 'tm.TINDAKAN')
            ->leftJoin('pendaftaran.kunjungan as kjgn', function ($join) {
                $join->on('kjgn.NOMOR', '=', 'tm.KUNJUNGAN')
                    ->where('kjgn.STATUS', '!=', 0);
            })
            ->leftJoin('pendaftaran.pendaftaran as p', function ($join) {
                $join->on('p.NOMOR', '=', 'kjgn.NOPEN')
                    ->where('p.STATUS', '!=', 0);
            })
            ->leftJoin('master.ruangan as r', 'r.ID', '=', 'kjgn.RUANGAN')
            ->leftJoin(
                'master.ruang_kamar_tidur as rkt',
                'rkt.ID',
                '=',
                'kjgn.RUANG_KAMAR_TIDUR'
            )
            ->leftJoin('master.ruang_kamar as rk', 'rk.ID', '=', 'rkt.RUANG_KAMAR')
            ->leftJoin('master.referensi as kls', function ($join) {
                $join->on('kls.ID', '=', 'rk.KELAS')
                    ->where('kls.JENIS', '=', 19);
            })
            ->leftJoin('master.tarif_tindakan as mtt', 'mtt.ID', '=', 'rt.TARIF_ID')
            ->leftJoin('master.tindakan_rincian as tr', function ($join) {
                $join->on('tr.TINDAKAN', '=', 'mtt.TINDAKAN')
                    ->where('tr.STATUS', '=', 1);
            })
            ->leftJoin('master.tindakan_keperawatan as tk', function ($join) {
                $join->on('tk.TINDAKAN', '=', 'tm.TINDAKAN')
                    ->where('tk.STATUS', '=', 1);
            })
            ->where('rt.TAGIHAN', $tagihan)
            ->where('rt.JENIS', 3)
            ->where('rt.STATUS', 1)
            ->whereNotNull('tk.ID')
            ->selectRaw("
                rt.TAGIHAN,

                CONCAT(
                    r.DESKRIPSI,
                    IF(
                        r.JENIS_KUNJUNGAN = 3,
                        CONCAT(
                            ' (',
                            rk.KAMAR,
                            '/',
                            rkt.TEMPAT_TIDUR,
                            '/',
                            kls.DESKRIPSI,
                            ')'
                        ),
                        ''
                    )
                ) AS RUANGAN,

                'Tindakan Keperawatan' AS LAYANAN,
                tm.TANGGAL AS TANGGAL,
                rt.JUMLAH,
                SUM(rt.TARIF) AS TARIF,
                r.JENIS_KUNJUNGAN,

                IF(
                    {$dokterOperator} IS NULL,
                    '',
                    CONCAT(' [', {$dokterOperator}, ']')
                ) AS DOKTER,

                IF(
                    {$dokterAsisten} IS NULL,
                    '',
                    CONCAT(' [', {$dokterAsisten}, ']')
                ) AS DOKTERKEDUA,

                IF(
                    {$dokterAnestesi} IS NULL,
                    '',
                    CONCAT(' [', {$dokterAnestesi}, ']')
                ) AS DOKTERANASTESI,

                mtt.ADMINISTRASI,
                mtt.SARANA,
                mtt.BHP,
                mtt.DOKTER_OPERATOR,
                mtt.DOKTER_ANASTESI,
                mtt.DOKTER_LAINNYA,
                mtt.PENATA_ANASTESI,
                mtt.PARAMEDIS,
                mtt.NON_MEDIS,

                IF(tr.ID IS NULL, 0, 1) AS STATUSTINDAKANRINCIAN,

                'Tindakan Keperawatan' AS LAYANAN1,

                CONCAT(
                    t.NAMA, '\\r',
                    SPACE(3), 'Jasa dr. Operator ',
                    IFNULL({$dokterOperator}, ''), '\\r',
                    SPACE(3), 'Jasa dr. Anastesi ',
                    IFNULL({$dokterAnestesi}, ''), '\\r',
                    SPACE(3), 'Jasa dr. Asisten ',
                    IFNULL({$dokterAsisten}, ''), '\\r',
                    SPACE(3), 'Jasa Penata Anastesi ', '\\r'
                ) AS LAYANAN_OK,

                CONCAT(
                    ' ', '\\r',
                    REPLACE(FORMAT(mtt.DOKTER_OPERATOR, 0), ',', '.'), '\\r',
                    REPLACE(FORMAT(mtt.DOKTER_ANASTESI, 0), ',', '.'), '\\r',
                    REPLACE(FORMAT(mtt.DOKTER_LAINNYA, 0), ',', '.'), '\\r',
                    REPLACE(FORMAT(mtt.PENATA_ANASTESI, 0), ',', '.'), '\\r'
                ) AS TARIF_LAYANAN_OK,

                CONCAT(
                    ' ', '\\r',
                    'Rp.', '\\r',
                    'Rp.', '\\r',
                    'Rp.', '\\r',
                    'Rp.'
                ) AS RP
            ")
            ->groupByRaw('RUANGAN, LAYANAN')
            ->orderBy('JENIS_KUNJUNGAN');
    }

    private function queryBillingFarmasi(string $nomorTagihan)
    {
        return DB::table('pembayaran.rincian_tagihan as rt')
            ->leftJoin('layanan.farmasi as f', function ($join) {
                $join->on('f.ID', '=', 'rt.REF_ID')
                    ->where('rt.JENIS', '=', 4)
                    ->where('f.STATUS', '!=', 0);
            })
            ->leftJoin('inventory.barang as b', 'b.ID', '=', 'f.FARMASI')
            ->leftJoin('pendaftaran.kunjungan as kjgn', function ($join) {
                $join->on('kjgn.NOMOR', '=', 'f.KUNJUNGAN')
                    ->where('kjgn.STATUS', '!=', 0);
            })
            ->leftJoin('pendaftaran.pendaftaran as p', function ($join) {
                $join->on('p.NOMOR', '=', 'kjgn.NOPEN')
                    ->where('p.STATUS', '!=', 0);
            })
            ->leftJoin('master.ruangan as r', 'r.ID', '=', 'kjgn.RUANGAN')
            ->leftJoin('master.ruang_kamar_tidur as rkt', 'rkt.ID', '=', 'kjgn.RUANG_KAMAR_TIDUR')
            ->leftJoin('master.ruang_kamar as rk', 'rk.ID', '=', 'rkt.RUANG_KAMAR')
            ->leftJoin('master.referensi as kls', function ($join) {
                $join->on('kls.ID', '=', 'rk.KELAS')
                    ->where('kls.JENIS', '=', 19);
            })
            ->where('rt.TAGIHAN', $nomorTagihan)
            ->where('rt.JENIS', 4)
            ->where('rt.STATUS', 1)
            ->selectRaw("
                rt.TAGIHAN,

                CONCAT(
                    r.DESKRIPSI,
                    IF(
                        r.JENIS_KUNJUNGAN = 3,
                        CONCAT(
                            ' (', rk.KAMAR, '/',
                            rkt.TEMPAT_TIDUR, '/',
                            kls.DESKRIPSI, ')'
                        ),
                        ''
                    )
                ) AS RUANGAN,

                b.NAMA AS LAYANAN,
                f.TANGGAL AS TANGGAL,
                rt.JUMLAH,
                rt.TARIF,
                r.JENIS_KUNJUNGAN,

                '' AS DOKTER,
                '' AS DOKTERKEDUA,
                '' AS DOKTERANASTESI,

                0 AS ADMINISTRASI,
                0 AS SARANA,
                0 AS BHP,
                0 AS DOKTER_OPERATOR,
                0 AS DOKTER_ANASTESI,
                0 AS DOKTER_LAINNYA,
                0 AS PENATA_ANASTESI,
                0 AS PARAMEDIS,
                0 AS NON_MEDIS,
                0 AS STATUSTINDAKANRINCIAN,

                b.NAMA AS LAYANAN1,
                '' AS LAYANAN_OK,
                '' AS TARIF_LAYANAN_OK,
                '' AS RP
            ");
    }

    private function queryBillingJenisLain(string $nomorTagihan)
    {
        return DB::table('pembayaran.rincian_tagihan as rt')
            ->leftJoin('pendaftaran.kunjungan as kjgn', function ($join) {
                $join->on('kjgn.NOMOR', '=', 'rt.REF_ID')
                    ->where('kjgn.STATUS', '!=', 0);
            })
            ->leftJoin('pendaftaran.pendaftaran as p', function ($join) {
                $join->on('p.NOMOR', '=', 'kjgn.NOPEN')
                    ->where('p.STATUS', '!=', 0);
            })
            ->leftJoin('master.ruangan as r', 'r.ID', '=', 'kjgn.RUANGAN')
            ->leftJoin('master.ruang_kamar_tidur as rkt', 'rkt.ID', '=', 'kjgn.RUANG_KAMAR_TIDUR')
            ->leftJoin('master.ruang_kamar as rk', 'rk.ID', '=', 'rkt.RUANG_KAMAR')
            ->leftJoin('master.referensi as kls', function ($join) {
                $join->on('kls.ID', '=', 'rk.KELAS')
                    ->where('kls.JENIS', '=', 19);
            })
            ->leftJoin('master.referensi as ref', function ($join) {
                $join->on('ref.ID', '=', 'rt.JENIS')
                    ->where('ref.JENIS', '=', 30);
            })
            ->where('rt.TAGIHAN', $nomorTagihan)
            ->where('rt.JENIS', 6)
            ->where('rt.STATUS', 1)
            ->selectRaw("
                rt.TAGIHAN,

                CONCAT(
                    r.DESKRIPSI,
                    IF(
                        r.JENIS_KUNJUNGAN = 3,
                        CONCAT(
                            ' (', rk.KAMAR, '/',
                            rkt.TEMPAT_TIDUR, '/',
                            kls.DESKRIPSI, ')'
                        ),
                        ''
                    )
                ) AS RUANGAN,

                ref.DESKRIPSI AS LAYANAN,
                kjgn.MASUK AS TANGGAL,
                rt.JUMLAH,
                rt.TARIF,
                r.JENIS_KUNJUNGAN,

                '' AS DOKTER,
                '' AS DOKTERKEDUA,
                '' AS DOKTERANASTESI,

                0 AS ADMINISTRASI,
                0 AS SARANA,
                0 AS BHP,
                0 AS DOKTER_OPERATOR,
                0 AS DOKTER_ANASTESI,
                0 AS DOKTER_LAINNYA,
                0 AS PENATA_ANASTESI,
                0 AS PARAMEDIS,
                0 AS NON_MEDIS,
                0 AS STATUSTINDAKANRINCIAN,

                ref.DESKRIPSI AS LAYANAN1,
                '' AS LAYANAN_OK,
                '' AS TARIF_LAYANAN_OK,
                '' AS RP
            ");
    }

    private function billingSelectColumns(): string
    {
        return "
            rt.TAGIHAN,
            '' AS RUANGAN,
            '' AS LAYANAN,
            NULL AS TANGGAL,
            rt.JUMLAH,
            rt.TARIF,
            0 AS JENIS_KUNJUNGAN,
            '' AS DOKTER,
            '' AS DOKTERKEDUA,
            '' AS DOKTERANASTESI,
            0 AS ADMINISTRASI,
            0 AS SARANA,
            0 AS BHP,
            0 AS DOKTER_OPERATOR,
            0 AS DOKTER_ANASTESI,
            0 AS DOKTER_LAINNYA,
            0 AS PENATA_ANASTESI,
            0 AS PARAMEDIS,
            0 AS NON_MEDIS,
            0 AS STATUSTINDAKANRINCIAN,
            '' AS LAYANAN1,
            '' AS LAYANAN_OK,
            '' AS TARIF_LAYANAN_OK,
            '' AS RP
        ";
    }

    // ====================================================================================================================================
    // =============================================================  ADD ONS  ============================================================
    // ====================================================================================================================================
    public static function setImgWord(TemplateProcessor $templateProcessor, string $key, string $imagePath, int $targetWidth)
    {
        if (!file_exists($imagePath)) {
            throw new \Exception("Gambar TTE tidak ditemukan: {$imagePath}");
        }

        [$originalWidth, $originalHeight] = getimagesize($imagePath);

        if ($originalWidth === 0) {
            throw new \Exception("Lebar gambar 0: {$imagePath}");
        }

        $ratio = $originalHeight / $originalWidth;
        $targetHeight = $targetWidth * $ratio;

        $templateProcessor->setImageValue($key, [
            'path' => $imagePath,
            'width' => $targetWidth,
            'height' => $targetHeight,
        ]);
    }
}
