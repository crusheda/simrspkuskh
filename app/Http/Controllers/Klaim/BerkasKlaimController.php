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
                // $ttd_dokter = DB::table('simrspku_klaim.tanda_tangan_pegawai')
                //     ->where('nip', $getLabValue($lab,'DOKTER'))
                //     // ->where('nip', 1912314)
                //     ->where('status', 1)
                //     ->whereNull('deleted_at')
                //     ->inRandomOrder()
                //     ->first();
                // dd($getLabValue($lab,'NIPDPJP'));

                // if (!$ttd_dokter) {
                //     throw new \Exception('TTD Dokter Laboratorium tidak ditemukan');
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
            $ttd_petugas = DB::table('simrspku_klaim.tanda_tangan_pegawai')
                ->where('nip', $header->NIP)
                // ->where('nip', 1912314)
                ->where('status', 1)
                ->whereNull('deleted_at')
                ->inRandomOrder()
                ->first();

            if (!$ttd_petugas) {
                throw new \Exception('TTD petugas tidak ditemukan');
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
                    200
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

                mp.NIP AS NIP,
                qp.filename AS QR1,
                kq.filename AS QR2,

                IF(
                    pt.OLEH = 0,
                    pt.DESKRIPSI,
                    master.getNamaLengkapPegawai(mp.NIP)
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
