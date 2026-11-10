<?php

namespace App\Http\Controllers\EMR\Form\ResumeMedis;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Throwable;

class ResumeRanapController extends Controller
{
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
        'pemeriksaan_fisik',
        'diagnosa_utama',
        'diagnosa_sekunder',
        'diagnosa_perawat',
        'terapi',
        'kondisi_keluar',
        'cara_keluar',
        'ku_td',
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
            'pemeriksaan_fisik' => 'nullable|string',

            'diagnosa_utama' => 'nullable|string',
            'diagnosa_sekunder' => 'nullable|string',
            'diagnosa_perawat' => 'nullable|string',

            'terapi' => 'nullable|string',

            'kondisi_keluar' => 'nullable|integer|in:1,2,3,4,5,6,7',
            'cara_keluar' => 'nullable|integer|in:1,2,3,4,5,6,7',

            'ku_td' => 'nullable|integer|min:0',
            'ku_nafas' => 'nullable|integer|min:0',
            'ku_tk' => 'nullable|integer|min:0',
            'ku_sn' => 'nullable|integer|min:0',
            'ku_nadi' => 'nullable|integer|min:0',
            'ku_suhu' => 'nullable|integer|min:0',

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
     * ==========================================================
     * PRINT PREVIEW
     * ==========================================================
     *
     * GET:
     * /api/v2/emr/resume/ranap/preview/{kunjungan}
     */
    public function printPreview($kunjungan)
    {
        try {

            $record = DB::table(self::TABLE)
                ->where('kunjungan', $kunjungan)
                ->where('status', 1)
                ->orderByDesc('id')
                ->first();

            if (!$record) {
                abort(404, 'Data Resume Medis belum tersedia.');
            }

            /*
             * Konversi nilai pilihan menjadi keterangan.
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
             * Helper untuk membuat field HTML.
             */
            $renderField = function ($label, $value) {

                $value = ($value === null || $value === '')
                    ? '-'
                    : (string) $value;

                return '
                    <div class="field">
                        <div class="label">' . e($label) . '</div>
                        <div class="value">' . nl2br(e($value)) . '</div>
                    </div>
                ';
            };

            /*
             * Helper untuk membuat bagian dokumen.
             */
            $renderSection = function ($title, $fields) use ($renderField) {

                $html = '
                    <div class="section">
                        <h3>' . e($title) . '</h3>
                        <div class="section-content">
                ';

                foreach ($fields as $label => $value) {
                    $html .= $renderField($label, $value);
                }

                $html .= '
                        </div>
                    </div>
                ';

                return $html;
            };

            /*
             * Susun isi Resume Medis.
             */
            $html = '';

            $html .= $renderSection('Informasi Rawat Inap', [

                'Nomor Kunjungan' => $record->kunjungan,

                'Indikasi Rawat Inap' => $record->indikasi,

            ]);

            $html .= $renderSection('Riwayat Penyakit', [

                'Riwayat Penyakit Sekarang' => $record->rps,

                'Riwayat Penyakit Dahulu' => $record->rpd,

                'Riwayat Pengobatan Medis' => $record->rpm,

                'Pemeriksaan Fisik' => $record->pemeriksaan_fisik,

            ]);

            $html .= $renderSection('Diagnosis dan Terapi', [

                'Diagnosis Utama' => $record->diagnosa_utama,

                'Diagnosis Sekunder' => $record->diagnosa_sekunder,

                'Tindakan / Prosedur Lainnya' => $record->diagnosa_perawat,

                'Terapi dan Tindakan' => $record->terapi,

            ]);

            $html .= $renderSection('Kondisi Keluar Rumah Sakit', [

                'Kondisi Waktu Keluar RS' =>
                    $kondisiKeluar[$record->kondisi_keluar] ?? '-',

                'Cara Keluar / Pasien Pulang' =>
                    $caraKeluar[$record->cara_keluar] ?? '-',

            ]);

            $html .= $renderSection('Kondisi Umum Pasien', [

                'Tekanan Darah' =>
                    $record->ku_td !== null
                        ? $record->ku_td . ' mmHg'
                        : '-',

                'Frekuensi Nafas' =>
                    $record->ku_nafas !== null
                        ? $record->ku_nafas . ' x/Menit'
                        : '-',

                'Tingkat Kesadaran' => $record->ku_tk,

                'Skala Nyeri' => $record->ku_sn,

                'Frekuensi Nadi' =>
                    $record->ku_nadi !== null
                        ? $record->ku_nadi . ' x/Menit'
                        : '-',

                'Suhu' =>
                    $record->ku_suhu !== null
                        ? $record->ku_suhu . ' °C'
                        : '-',

            ]);

            $html .= $renderSection('Layanan Lanjutan dan Edukasi', [

                'Layanan Lanjutan' =>
                    $layananLanjutan[$record->layanan_lanjutan] ?? '-',

                'Edukasi' => $record->edukasi,

            ]);

            $statusFinal = (int) $record->final === 1;

            $statusText = $statusFinal
                ? 'SUDAH FINAL'
                : 'BELUM FINAL';

            $tanggalDibuat = $record->tgl_dibuat
                ? Carbon::parse($record->tgl_dibuat)
                    ->timezone('Asia/Jakarta')
                    ->format('d-m-Y H:i:s')
                : '-';

            $tanggalFinal = $record->tgl_difinal
                ? Carbon::parse($record->tgl_difinal)
                    ->timezone('Asia/Jakarta')
                    ->format('d-m-Y H:i:s')
                : '-';

            $document = '
                <!DOCTYPE html>
                <html lang="id">

                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1">

                    <title>Resume Medis Rawat Inap</title>

                    <style>
                        * {
                            box-sizing: border-box;
                        }

                        body {
                            font-family: Arial, Helvetica, sans-serif;
                            color: #222;
                            font-size: 12px;
                            line-height: 1.5;
                            margin: 30px;
                        }

                        .toolbar {
                            margin-bottom: 20px;
                            display: flex;
                            justify-content: flex-end;
                            gap: 10px;
                        }

                        .toolbar button {
                            border: 0;
                            padding: 10px 18px;
                            cursor: pointer;
                            border-radius: 5px;
                            color: #fff;
                            background: #2563eb;
                        }

                        .toolbar .close {
                            background: #64748b;
                        }

                        .header {
                            text-align: center;
                            padding-bottom: 15px;
                            margin-bottom: 20px;
                            border-bottom: 2px solid #222;
                        }

                        .header h2 {
                            font-size: 20px;
                            margin: 0 0 5px;
                            text-transform: uppercase;
                        }

                        .header p {
                            margin: 0;
                        }

                        .meta {
                            display: flex;
                            justify-content: space-between;
                            gap: 10px;
                            margin-bottom: 20px;
                        }

                        .section {
                            margin-bottom: 18px;
                            page-break-inside: avoid;
                        }

                        .section h3 {
                            font-size: 13px;
                            background: #f1f5f9;
                            border: 1px solid #cbd5e1;
                            padding: 7px 10px;
                            margin: 0 0 10px;
                        }

                        .section-content {
                            padding: 0 5px;
                        }

                        .field {
                            display: flex;
                            margin-bottom: 9px;
                            gap: 15px;
                        }

                        .label {
                            width: 220px;
                            min-width: 220px;
                            font-weight: bold;
                        }

                        .value {
                            flex: 1;
                            white-space: pre-wrap;
                            overflow-wrap: anywhere;
                        }

                        .footer {
                            margin-top: 35px;
                            padding-top: 10px;
                            border-top: 1px solid #ddd;
                            font-size: 10px;
                            color: #555;
                        }

                        @media print {
                            body {
                                margin: 15mm;
                            }

                            .toolbar {
                                display: none !important;
                            }

                            .section h3 {
                                background: #eee !important;
                                -webkit-print-color-adjust: exact;
                                print-color-adjust: exact;
                            }
                        }
                    </style>
                </head>

                <body>

                    <div class="toolbar">
                        <button onclick="window.print()">
                            Print
                        </button>

                        <button class="close" onclick="window.close()">
                            Tutup
                        </button>
                    </div>

                    <div class="header">
                        <h2>Resume Medis</h2>
                        <p>Rawat Inap</p>
                    </div>

                    <div class="meta">
                        <div>
                            <strong>Nomor Kunjungan:</strong>
                            ' . e($record->kunjungan) . '
                        </div>

                        <div>
                            <strong>Status:</strong>
                            ' . e($statusText) . '
                        </div>
                    </div>

                    ' . $html . '

                    <div class="footer">
                        Status Finalisasi: ' . e($statusText) . '<br>
                        Tanggal Dibuat: ' . e($tanggalDibuat) . '<br>
                        Tanggal Finalisasi: ' . e($tanggalFinal) . '
                    </div>

                </body>
                </html>
            ';

            return response($document, 200, [
                'Content-Type' => 'text/html; charset=UTF-8',
            ]);

        } catch (Throwable $e) {

            report($e);

            return response(
                'Gagal membuat Print Preview Resume Medis.',
                500
            );
        }
    }
}
