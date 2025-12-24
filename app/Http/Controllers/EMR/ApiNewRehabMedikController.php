<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\simrspku_klaim\klaim_verifikasi;
use App\Models\simrspku_klaim\klaim_file;
use App\Models\Pengguna;
use App\Models\simrspku_klaim\form_kfr;
use App\Models\simrspku_klaim\form_kfr_jp;
use App\Models\simrspku_klaim\form_kfr_ks;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\TemplateProcessor;
use PHPJasper\PHPJasper;
use Carbon\Carbon;
use Auth, Storage;

class ApiNewRehabMedikController extends Controller
{
    // CONTROLLER FOR LIBRE OFFICE ON LINUX SERVER
    public function libreOffice($input, $output)
    {
        $soffice = env('LIBREOFFICE_PATH', '/usr/bin/soffice');

        // Path absolut
        $input  = realpath($input);
        $output = realpath($output);

        if (!$input || !$output) {
            \Log::error('Path input/output tidak valid', compact('input','output'));
            return [false, [], -1];
        }

        // Environment aman untuk www-data
        putenv('HOME=/tmp');
        putenv('XDG_CACHE_HOME=/tmp');

        // Profile unik per proses (ANTI TABRAKAN)
        $profile = '/tmp/lo_' . uniqid();

        // Command convert
        $cmd = sprintf(
            '%s --headless --nologo --nofirststartwizard ' .
            '-env:UserInstallation=file://%s ' .
            '--convert-to pdf %s --outdir %s 2>&1',
            escapeshellcmd($soffice),
            escapeshellarg($profile),
            escapeshellarg($input),
            escapeshellarg($output)
        );

        exec($cmd, $log, $result);

        $outputPdf = $output . '/' . pathinfo($input, PATHINFO_FILENAME) . '.pdf';

        // Cleanup profile
        exec('rm -rf ' . escapeshellarg($profile));

        if ($result !== 0 || !file_exists($outputPdf)) {
            \Log::error('LibreOffice Linux gagal konversi', [
                'cmd' => $cmd,
                'log' => $log,
                'result' => $result,
            ]);
            return [false, $log, $result];
        }

        return [true, $outputPdf];
    }

    // CONTROLLER FOR LIBRE OFFICE ON WINDOWS SERVER
    // public function libreOffice($input, $output)
    // {
    //     // LINK DOWNLOAD LIBRE OFFICE = https://www.libreoffice.org/download/download
    //     // Ambil path dari .env (lebih fleksibel kalau update LibreOffice)
    //     $soffice = env('LIBREOFFICE_PATH', 'C:/Program Files/LibreOffice/program/soffice.exe');
    //     $soffice = '"'.$soffice.'"';

    //     // 🔹 Kill proses lama dulu (biar tidak nyangkut)
    //     exec('taskkill /IM soffice.bin /F 2> NUL');

    //     // Jalankan konversi
    //     $cmd = $soffice . ' --headless --convert-to pdf ' . escapeshellarg($input) . ' --outdir ' . escapeshellarg($output);
    //     exec($cmd, $log, $result);

    //     // 🔹 Cek hasil
    //     $outputPdf = $output . '/' . pathinfo($input, PATHINFO_FILENAME) . '.pdf';
    //     if ($result !== 0 || !file_exists($outputPdf)) {
    //         \Log::error('LibreOffice gagal konversi', [
    //             'cmd' => $cmd,
    //             'log' => $log,
    //             'result' => $result
    //         ]);
    //         return [false, $log, $result];
    //     }

    //     return [true, $log, $result];
    // }

    public function get($KUNJUNGAN)
    {
        $data = DB::table('simrspku_klaim.emr_form_kfr AS kfr')
            ->join('medicalrecord.cppt AS cppt', 'cppt.ID', '=', 'kfr.id_cppt')
            ->where('kfr.nomor', $KUNJUNGAN)
            ->where('kfr.status', 1)
            ->select(
                'cppt.ID AS ID_CPPT',
                'cppt.SUBYEKTIF',
                'cppt.OBYEKTIF',
                'cppt.ASSESMENT',
                'cppt.PLANNING',
                'cppt.INSTRUKSI'
            )
            ->first();

        if (!$data) {
            return response()->json([
                'status' => false,
                'message'=> 'Data tidak ditemukan'
            ]);
        }

        // === pecah PLANNING ===
        $planning = explode("\n", $data->PLANNING);

        // === mapping INSTRUKSI → select + textarea ===
        $instruksi_text = trim($data->INSTRUKSI);

        $cppt_i = 0;
        $cppt_i_rtl = '';

        if (str_starts_with($instruksi_text, "Evaluasi")) {
            $cppt_i = 1;
            $cppt_i_rtl = trim(str_replace("Evaluasi :", "", $instruksi_text));
        } elseif (str_starts_with($instruksi_text, "Rujuk")) {
            $cppt_i = 2;
            $cppt_i_rtl = trim(str_replace("Rujuk :", "", $instruksi_text));
        } elseif (str_starts_with($instruksi_text, "Selesai")) {
            $cppt_i = 3;
            $cppt_i_rtl = trim(str_replace("Selesai :", "", $instruksi_text));
        }

        return response()->json([
            'status' => true,
            'id_cppt' => $data->ID_CPPT,
            'data' => [
                's' => $data->SUBYEKTIF,
                'o' => $data->OBYEKTIF,
                'a' => $data->ASSESMENT,
                'p1'=> $planning[0] ?? '',
                'p2'=> $planning[1] ?? '',
                'p3'=> $planning[2] ?? '',
                'p4'=> $planning[3] ?? '',
                'cppt_i'      => $cppt_i,
                'cppt_i_rtl'  => $cppt_i_rtl,
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rm'           => 'required|integer',
            'kunjungan'    => 'required',
            'tgl'          => 'required|date',

            'cppt_s'       => 'required',
            'cppt_o'       => 'required',
            'cppt_a'       => 'required',

            'cppt_p_1'     => 'required',
            'cppt_p_2'     => 'required',
            'cppt_p_3'     => 'required',
            'cppt_p_4'     => 'required',

            'cppt_i'       => 'required|in:1,2,3',
            'cppt_i_rtl'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message'=> $validator->errors()->first()
            ], 422);
        }

        DB::beginTransaction();
        try {

            $kunjungan = $request->kunjungan;
            $dokter = DB::table('master.dokter as dr')
                        ->leftJoin('aplikasi.pengguna as pe', function($join) {
                            $join->on('pe.NIP', '=', 'dr.NIP')
                                ->where('pe.STATUS', '=', 1);
                        })
                        ->select('dr.ID', 'pe.NAMA AS DOKTER', 'dr.NIP', DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'))
                        ->where('pe.ID', auth()->id())
                        ->where('dr.STATUS', 1)
                        ->first();

            if (!$dokter) {
                throw new \Exception('Data dokter tidak ditemukan untuk user ini');
            }

            $ttd_pegawai = DB::table('simrspku_klaim.tanda_tangan_pegawai as ttp')
                ->where('ttp.nip', $dokter->NIP)
                ->where('status', 1)
                ->inRandomOrder()
                ->first();

            if ($request->cppt_i == 1) {
                $cppt_i = "Evaluasi :\n".$request->cppt_i_rtl;
            } elseif ($request->cppt_i == 2) {
                $cppt_i = "Rujuk :\n".$request->cppt_i_rtl;
            } elseif ($request->cppt_i == 3) {
                $cppt_i = "Selesai :\n".$request->cppt_i_rtl;
            } else {
                $cppt_i = "";
            }

            /* ==========================
            * 1. INSERT CPPT
            * ========================== */
            $id_cppt = DB::table('medicalrecord.cppt')->insertGetId([
                'KUNJUNGAN'    => $kunjungan,
                'TANGGAL'      => $request->tgl,
                'SUBYEKTIF'    => $request->cppt_s,
                'OBYEKTIF'     => $request->cppt_o,
                'ASSESMENT'    => $request->cppt_a,
                'PLANNING'     =>
                    $request->cppt_p_1 . "\n" .
                    $request->cppt_p_2 . "\n" .
                    $request->cppt_p_3 . "\n" .
                    $request->cppt_p_4,
                'INSTRUKSI'    => $cppt_i,
                'JENIS'        => 1,
                'TENAGA_MEDIS' => $dokter->ID,
                'OLEH'         => auth()->id(),
                'STATUS'       => 1,
            ]);

            /* ==========================
            * 2. INSERT EMR FORM KFR
            * ========================== */
            DB::table('simrspku_klaim.emr_form_kfr')->insert([
                'id_cppt'       => $id_cppt,
                'group'         => 1,
                'nomor_init'    => $kunjungan,
                'nomor'         => $kunjungan,
                'tgl_init'      => now()->toDateString(),
                'tgl'           => now()->toDateString(),
                'rm'            => $request->rm,
                'id_ttd_dokter' => $ttd_pegawai->id,
                'ttd_dokter'    => $ttd_pegawai->signature_path,
                'nip_dokter'    => $ttd_pegawai->nip,
                'nama_dokter'   => $dokter->NAMADOKTER,
                'status'        => 1,
                'user'          => auth()->id(),
                'created_at'    => now(),
                'updated_at'    => now()
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message'=> 'Form KFR & CPPT berhasil disimpan'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message'=> $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $IDCPPT)
    {
        $validator = Validator::make($request->all(), [
            'tgl'          => 'required|date',

            'cppt_s'       => 'required',
            'cppt_o'       => 'required',
            'cppt_a'       => 'required',

            'cppt_p_1'     => 'required',
            'cppt_p_2'     => 'required',
            'cppt_p_3'     => 'required',
            'cppt_p_4'     => 'required',

            'cppt_i'       => 'required|in:1,2,3',
            'cppt_i_rtl'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message'=> $validator->errors()->first()
            ], 422);
        }

        // === Build INSTRUKSI sesuai pilihan ===
        if ($request->cppt_i == 1) {
            $cppt_i = "Evaluasi : \n".$request->cppt_i_rtl;
        } elseif ($request->cppt_i == 2) {
            $cppt_i = "Rujuk : \n".$request->cppt_i_rtl;
        } else { // 3
            $cppt_i = "Selesai : \n".$request->cppt_i_rtl;
        }

        // PLANNING — gabung hanya yang terisi
        $planningParts = array_filter([
            trim($request->cppt_p_1),
            trim($request->cppt_p_2),
            trim($request->cppt_p_3),
            trim($request->cppt_p_4),
        ]);

        $planning = implode("\r\n", $planningParts);
        
        DB::beginTransaction();
        try {
            /* ==========================
            * 1. UPDATE CPPT
            * ========================== */
            DB::table('medicalrecord.cppt')
                ->where('ID', $IDCPPT)
                ->where('STATUS', 1)
                ->update([
                    'TANGGAL'      => $request->tgl,
                    'SUBYEKTIF'    => $request->cppt_s,
                    'OBYEKTIF'     => $request->cppt_o,
                    'ASSESMENT'    => $request->cppt_a,
                    'PLANNING'     => $planning,
                    'INSTRUKSI'    => $cppt_i,
                    'STATUS'       => 1,
                ]);

            /* ==========================
            * 2. UPDATE EMR FORM KFR
            * ========================== */
            DB::table('simrspku_klaim.emr_form_kfr')
                ->where('id_cppt', $IDCPPT)
                ->where('status', 1)
                ->update([
                    'user'        => auth()->id(),
                    'updated_at'  => now()
                ]);

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Data KFR & CPPT berhasil di-update'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message'=> $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id_cppt' => 'required|integer'
        ]);

        DB::beginTransaction();
        try {

            // cek data
            $form = DB::table('simrspku_klaim.emr_form_kfr')
                ->where('id_cppt', $request->id_cppt)
                ->where('status', 1)
                ->first();

            if (!$form) {
                throw new \Exception('Data Form KFR tidak ditemukan');
            }

            $now = now();

            /* ==========================
            * 1. Soft Delete EMR FORM KFR
            * ========================== */
            DB::table('simrspku_klaim.emr_form_kfr')
                ->where('id_cppt', $request->id_cppt)
                ->update([
                    'user'       => auth()->id(),
                    'status'     => 0,
                    'deleted_at' => $now
                ]);

            /* ==========================
            * 2. Soft Delete CPPT
            * ========================== */
            DB::table('medicalrecord.cppt')
                ->where('ID', $request->id_cppt)
                ->update([
                    'STATUS'     => 0
                ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message'=> 'Form KFR & CPPT berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message'=> $e->getMessage()
            ], 500);
        }
    }

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

    function formKfr() {

    }
}
