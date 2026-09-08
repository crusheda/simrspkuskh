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
use Auth, Storage;

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
            $fileName = $kunjungan . '_lab';

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

                // ==========================
                // GET TTD ANALIS
                // ==========================
                $ttd_analis = DB::table('simrspku_klaim.tanda_tangan_pegawai')
                    // ->where('nip', $nipAnalis)
                    ->where('nip', 1912314)
                    ->where('status', 1)
                    ->whereNull('deleted_at')
                    ->inRandomOrder()
                    ->first();

                if (!$ttd_analis) {
                    throw new \Exception('TTD analis tidak ditemukan');
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
                        'File TTD analis tidak ditemukan: '
                        . $ttd_analis->signature_path
                    );
                }

                // ==========================
                // GET TTD DOKTER
                // ==========================
                $ttd_dokter = DB::table('simrspku_klaim.tanda_tangan_pegawai')
                    // ->where('nip', $getLabValue($lab,'DOKTER'))
                    ->where('nip', 1912314)
                    ->where('status', 1)
                    ->whereNull('deleted_at')
                    ->inRandomOrder()
                    ->first();

                if (!$ttd_dokter) {
                    throw new \Exception('TTD Dokter Laboratorium tidak ditemukan');
                }

                if (
                    Storage::disk('public')->exists(
                        $ttd_dokter->signature_path
                    )
                ) {
                    $this->setImgWord(
                        $templateProcessor,
                        'TTD_DOKTER',
                        Storage::disk('public')->path(
                            $ttd_dokter->signature_path
                        ),
                        200
                    );
                } else {
                    throw new \Exception(
                        'File TTD Dokter Laboratorium tidak ditemukan: '
                        . $ttd_dokter->signature_path
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
            if (count($temporaryFiles) === 1) {

                $finalPdf = $output . '.pdf';

                if (File::exists($finalPdf)) {
                    File::delete($finalPdf);
                }

                File::move(
                    $temporaryFiles[0],
                    $finalPdf
                );

                return true;
            }

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
            | OUTPUT PDF FINAL
            |--------------------------------------------------------------------------
            */
            $finalPdf = $output . '.pdf';

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
            | HAPUS PDF FINAL LAMA
            |--------------------------------------------------------------------------
            */
            if (File::exists($finalPdf)) {

                File::delete($finalPdf);
            }

            /*
            |--------------------------------------------------------------------------
            | SAVE PDF FINAL
            |--------------------------------------------------------------------------
            */
            $pdf->Output(
                $finalPdf,
                'F'
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

            DB::commit();

            return response()->file($finalPdf, [
                'Content-Type' => 'application/pdf',
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
