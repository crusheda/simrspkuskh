<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\simrspku_klaim\emr_form_kfr;
use App\Models\simrspku_klaim\klaim_verifikasi;
use App\Models\simrspku_klaim\klaim_file;
use App\Models\Pengguna;
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

        return [true, $outputPdf, $result];
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

    public function getSection($text, $label)
    {
        $safeLabel = preg_quote($label, '/');

        $pattern = "/{$safeLabel}:\s*\n(.*?)(?:\n{2,}|$)/s";
        preg_match($pattern, $text, $m);

        return isset($m[1]) ? trim($m[1]) : '';
    }

    public function get($KUNJUNGAN)
    {
        $data = DB::table('simrspku_klaim.emr_form_kfr AS kfr')
            ->join('medicalrecord.cppt AS cppt', 'cppt.ID', '=', 'kfr.id_cppt')
            ->where('kfr.nomor', $KUNJUNGAN)
            ->where('kfr.status', 1)
            ->select(
                'kfr.nomor_init AS KUNJUNGAN',
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

        // Normalisasi line break
        $planningText = preg_replace("/\r?\n/", "\n", $data->PLANNING);

        // Ambil tiap bagian planning
        $p1 = $this->getSection($planningText, 'Goal of Treatment');
        $p2 = $this->getSection($planningText, 'Tindakan/Program Rehabilitasi Medik');
        $p3 = $this->getSection($planningText, 'Edukasi');
        $p4 = $this->getSection($planningText, 'Frekuensi Kunjungan');

        // Mapping instruksi
        $instruksi_text = trim($data->INSTRUKSI);
        $cppt_i = 0; $cppt_i_rtl = '';

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
            'kunjungan_init' => $data->KUNJUNGAN,
            'id_cppt' => $data->ID_CPPT,
            'data' => [
                's' => $data->SUBYEKTIF,
                'o' => $data->OBYEKTIF,
                'a' => $data->ASSESMENT,
                'p1'=> $p1,
                'p2'=> $p2,
                'p3'=> $p3,
                'p4'=> $p4,
                'cppt_i'     => $cppt_i,
                'cppt_i_rtl' => $cppt_i_rtl,
            ]
        ]);
    }

    function getByRM($NORM, $KUNJUNGAN) // LOAD GUNAKAN FORM LAMA
    {
        $data = DB::table('simrspku_klaim.emr_form_kfr AS kfr')
            ->select(
                'kfr.group',
                'kfr.nomor_init',
                DB::raw("LPAD(kfr.rm, 8, '0') as rm"),
                DB::raw("DATE_FORMAT(kfr.tgl_init, '%e %M %Y') as tgl_init"),
                'kfr.nama_dokter',
                'kfr.sep',
                'kfr.tgl_sep',
                DB::raw("MIN(kfr.created_at) AS created_at")
            )
            ->where('kfr.rm', $NORM)
            ->whereNull('kfr.deleted_at')
            ->groupBy(
                'kfr.group',
                'kfr.nomor_init',
                DB::raw("LPAD(kfr.rm, 8, '0')"),
                DB::raw("DATE_FORMAT(kfr.tgl_init, '%e %M %Y')"),
                'kfr.nama_dokter',
                'kfr.sep',
                'kfr.tgl_sep'
            )
            ->orderBy('kfr.tgl_sep', 'DESC')
            ->get();

        if ($data->isEmpty()) {
            return response()->json([
                'status' => false,
                'message'=> 'Data Form KFR Tidak Ditemukan'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    function getByRMnTgl($NORM, $KUNJUNGAN, $TGLSEP) // LOAD RIWAYAT GRID KANAN
    {
        $show = DB::table('simrspku_klaim.emr_form_kfr AS kfr')
            ->select(
                'kfr.group',
                'kfr.nomor_init',
                'kfr.nomor',
                DB::raw("LPAD(kfr.rm, 8, '0') as rm"),
                DB::raw("DATE_FORMAT(kfr.tgl_init, '%e %M %Y') as tgl_init"),
                'kfr.nama_dokter',
                'kfr.sep',
                'kfr.tgl_sep',
                'kfr.created_at'
                // DB::raw("MIN(kfr.created_at) AS created_at")
            )
            ->where('kfr.rm', $NORM)
            ->whereNull('kfr.deleted_at')
            ->whereDate('kfr.tgl_sep', '<=', $TGLSEP)
            // ->groupBy(
            //     'kfr.group',
            //     'kfr.nomor_init',
            //     DB::raw("LPAD(kfr.rm, 8, '0')"),
            //     DB::raw("DATE_FORMAT(kfr.tgl_init, '%e %M %Y')"),
            //     'kfr.nama_dokter',
            //     'kfr.sep',
            //     'kfr.tgl_sep'
            // )
            ->orderBy('kfr.tgl_sep', 'DESC')
            ->get();

        if ($show->isEmpty()) {
            return response()->json([
                'status' => false,
                'message'=> 'Data Form KFR Tidak Ditemukan'
            ]);
        }

        $formKfr = emr_form_kfr::where('rm', $NORM)
            ->where('nomor', $KUNJUNGAN)
            ->whereDate('tgl_sep', $TGLSEP)
            ->where('status', 1)
            ->whereNull('deleted_at')
            ->first();

        // print_r($formKfr); die();

        $data = [
            'rm' => $NORM,
            'kunjungan' => $KUNJUNGAN,
            'tglsep' => $TGLSEP,
            'form' => $formKfr,
            'show' => $show
        ];

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    function getCppt($KUNJUNGAN) {
        $data = DB::table('medicalrecord.cppt AS cppt')
            ->leftJoin('master.dokter as dr', function($join) {
                $join->on('dr.ID', '=', 'cppt.TENAGA_MEDIS')
                    ->where('dr.STATUS', '=', 1);
            })
            ->leftJoin('aplikasi.pengguna as pe', function($join) {
                $join->on('pe.ID', '=', 'cppt.OLEH')
                    ->where('pe.STATUS', '=', 1);
            })
            ->where('cppt.KUNJUNGAN', $KUNJUNGAN)
            ->where('cppt.STATUS', 1)
            ->select(
                'cppt.ID AS ID_CPPT',
                'cppt.TANGGAL',
                'cppt.SUBYEKTIF',
                'cppt.OBYEKTIF',
                'cppt.ASSESMENT',
                'cppt.PLANNING',
                'cppt.INSTRUKSI',
                DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'),
                DB::raw('master.getNamaLengkapPegawai(pe.NIP) AS NAMAUSER'),
            )
            ->orderBy('cppt.TANGGAL', 'DESC')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    function syncFormLama(Request $request)
    {
        $now = Carbon::now();
        $validator = Validator::make(
            $request->all(),
            [
                'nomor_init'        => 'required',
                'nomor_kunjungan'   => 'required',
                'rm'                => 'required',
                'sep'               => 'required',
                'tgl_sep'           => 'required',
                'tgl_kfr'           => 'required',
            ],
            [
                'nomor_init.required'        => 'Nomor Init Kunjungan wajib terkirim.',
                'nomor_kunjungan.required'   => 'Nomor Kunjungan saat ini wajib terkirim.',
                'rm.required'                => 'Nomor RM wajib terkirim.',
                'sep.required'               => 'Nomor SEP wajib terkirim.',
                'tgl_sep.required'           => 'Tanggal SEP wajib terkirim.',
                'tgl_kfr.required'           => 'Tanggal KFR wajib terkirim.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message'=> $validator->errors()->first()
            ], 422);
        }

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
            return response()->json([
                'status' => false,
                'message'=> 'Data dokter tidak ditemukan untuk user ini'
            ], 404);
        }

        DB::beginTransaction();

        try {
            $formLama = emr_form_kfr::where('rm', $request->rm)
                ->where('nomor_init', $request->nomor_init)
                ->where('nomor', $request->nomor_init)
                ->where('status', 1)
                ->whereNull('deleted_at')
                ->latest('id')
                ->first();

            if (!$formLama) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message'=> 'Form KFR Lama tidak ditemukan / telah terhapus'
                ], 404);
            }

            $cpptLama = DB::table('medicalrecord.cppt')
                            ->where('ID', $formLama->id_cppt)
                            ->where('KUNJUNGAN', $formLama->nomor_init)
                            ->where('STATUS', 1)
                            ->orderBy('id','desc')
                            ->first();

            if (!$cpptLama) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message'=> 'CPPT Lama tidak ditemukan / telah terhapus'
                ], 404);
            }

            // INSERT NEW CPPT WITH OLD DATA CPPT
            $id_cppt = DB::table('medicalrecord.cppt')->insertGetId([
                'KUNJUNGAN'    => $request->nomor_kunjungan,
                'TANGGAL'      => now(),
                'SUBYEKTIF'    => $cpptLama->SUBYEKTIF,
                'OBYEKTIF'     => $cpptLama->OBYEKTIF,
                'ASSESMENT'    => $cpptLama->ASSESMENT,
                'PLANNING'     => $cpptLama->PLANNING,
                'INSTRUKSI'    => $cpptLama->INSTRUKSI,
                'JENIS'        => $cpptLama->JENIS,
                'TENAGA_MEDIS' => $cpptLama->TENAGA_MEDIS,
                'OLEH'         => auth()->id(),
                'STATUS'       => 1,
            ]);

            // Clone model
            $formBaru = $formLama->replicate();

            // Change Value
            $formBaru->id_cppt = $id_cppt;
            $formBaru->nomor = $request->nomor_kunjungan;
            $formBaru->sep = $request->sep;
            $formBaru->tgl_sep = $request->tgl_sep;
            $formBaru->tgl = $request->tgl_kfr;
            $formBaru->created_at = $now;
            $formBaru->updated_at = $now;

            // Simpan sebagai baris baru
            $formBaru->save();

            // SAVING INTO KLAIM_FILE WITH FUNCTION GENERATEFORMKFR
            $dataPasien = DB::table('master.pasien')
                        ->select(
                            'TANGGAL_LAHIR AS TGLLAHIRPASIEN',
                            DB::raw('master.getCariUmur(now(),TANGGAL_LAHIR) AS UMURPASIEN'),
                            DB::raw('master.getNamaLengkap(NORM) AS NAMAPASIEN'),
                            DB::raw('master.getAlamatPasienCustom(NORM) AS ALAMATPASIEN'),
                        )
                        ->where('NORM',$request->rm)
                        ->where('STATUS', true)
                        ->first();

            if (!$dataPasien) {
                return response()->json(['error' => 'Data Pasien tidak ditemukan.'], 404);
            }

            // Normalisasi line break
            $planningText = preg_replace("/\r?\n/", "\n", $cpptLama->PLANNING);

            // Ambil tiap bagian planning
            $p1 = $this->getSection($planningText, 'Goal of Treatment');
            $p2 = $this->getSection($planningText, 'Tindakan/Program Rehabilitasi Medik');
            $p3 = $this->getSection($planningText, 'Edukasi');
            $p4 = $this->getSection($planningText, 'Frekuensi Kunjungan');

            $show = (object)[
                'TGLSEP'         => $request->tgl_sep,
                'KUNJUNGAN'      => $request->nomor_kunjungan,
                'GROUP'          => $formLama->group,
                'TANGGAL'        => now()->toDateString(),
                'NORM'           => $formLama->rm,
                'NAMAPASIEN'     => $dataPasien->NAMAPASIEN ?? '',
                'NAMADOKTER'     => $dokter->NAMADOKTER,
                'TGLLAHIRPASIEN' => $dataPasien->TGLLAHIRPASIEN ?? '',
                'UMURPASIEN'     => $dataPasien->UMURPASIEN ?? '',
                'ALAMATPASIEN'   => $dataPasien->ALAMATPASIEN ?? '',
                'SUBYEKTIF'      => $cpptLama->SUBYEKTIF,
                'OBYEKTIF'       => $cpptLama->OBYEKTIF,
                'ASSESMENT'      => $cpptLama->ASSESMENT,
                'PLANNING1'      => $p1,
                'PLANNING2'      => $p2,
                'PLANNING3'      => $p3,
                'PLANNING4'      => $p4,
                'INSTRUKSI'      => $cpptLama->INSTRUKSI,
                'PATH_TTE_DOKTER'=> $formLama->ttd_dokter,
            ];

            /* 4. KIRIM LANGSUNG KE GENERATOR */
            $generateForm = $this->generateFormKfr($show);

            if (!$generateForm) {
                return response()->json([
                    'status' => false,
                    'message'=> 'Gagal generate Form KFR'
                ], 500);
            }

            /* KIRIM LANGSUNG KE GENERATOR */
            // $generateUlangForm = $this->generateUlangFormKfr($request->nomor_kunjungan);

            // if (!$generateUlangForm) {
            //     return response()->json([
            //         'status' => false,
            //         'message'=> 'Gagal generate PDF untuk Form KFR kunjungan ini'
            //     ], 500);
            // }

            DB::commit();

            return response()->json([
                'status' => true,
                'message'=> 'Form KFR & CPPT berhasil tersimpan dan telah digabungkan'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message'=> $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'rm'           => 'required|integer',
                'kunjungan'    => 'required',
                'sep'          => 'required',
                'tgl_sep'      => 'required|date',
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
            ],
            [
                'rm.required'        => 'Nomor RM wajib diisi.',
                'rm.integer'         => 'Nomor RM harus berupa angka.',
                'kunjungan.required' => 'Kunjungan wajib diisi.',
                'sep.required'       => 'SEP wajib diisi.',
                'tgl_sep.required'   => 'Tanggal SEP wajib diisi.',
                'tgl_sep.date'       => 'Tanggal SEP tidak valid.',
                'tgl.required'       => 'Tanggal wajib diisi.',
                'tgl.date'           => 'Tanggal tidak valid.',

                'cppt_s.required'    => 'Isian Subyektif wajib diisi.',
                'cppt_o.required'    => 'Isian Obyektif wajib diisi.',
                'cppt_a.required'    => 'Isian Asesment wajib diisi.',

                'cppt_p_1.required'  => 'Planning (Goal of Treatment) wajib diisi.',
                'cppt_p_2.required'  => 'Planning (Tindakan/Program Rehabilitasi Medik) wajib diisi.',
                'cppt_p_3.required'  => 'Planning (Edukasi) wajib diisi.',
                'cppt_p_4.required'  => 'Planning (Frekuensi Kunjungan) wajib diisi.',

                'cppt_i.required'    => 'Pilihan Rencana Tindak Lanjut wajib diisi.',
                'cppt_i.in'          => 'Pilihan Rencana Tindak Lanjut tidak valid.',
                'cppt_i_rtl.required'=> 'Isian Rencana Tindak Lanjut wajib diisi.',
            ]
        );

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

            $planning =
                "Goal of Treatment:\n" .
                $request->cppt_p_1 . "\n\n" .

                "Tindakan/Program Rehabilitasi Medik:\n" .
                $request->cppt_p_2 . "\n\n" .

                "Edukasi:\n" .
                $request->cppt_p_3 . "\n\n" .

                "Frekuensi Kunjungan:\n" .
                $request->cppt_p_4;

            /* ==========================
            * 1. INSERT CPPT
            * ========================== */
            $id_cppt = DB::table('medicalrecord.cppt')->insertGetId([
                'KUNJUNGAN'    => $kunjungan,
                'TANGGAL'      => $request->tgl,
                'SUBYEKTIF'    => $request->cppt_s,
                'OBYEKTIF'     => $request->cppt_o,
                'ASSESMENT'    => $request->cppt_a,
                'PLANNING'     => $planning,
                'INSTRUKSI'    => $cppt_i,
                'JENIS'        => 1,
                'TENAGA_MEDIS' => $dokter->ID,
                'OLEH'         => auth()->id(),
                'STATUS'       => 1,
            ]);

            /* ==========================
            * 2. INSERT EMR FORM KFR
            * ========================== */
            // GET LAST GROUP
            $lastGroup = DB::table('simrspku_klaim.emr_form_kfr')
                            ->where('nomor_init', $kunjungan)
                            ->where('status', 1)
                            ->whereNull('deleted_at')
                            ->orderBy('group', 'DESC')
                            ->first();
            $newGroup = $lastGroup ? $lastGroup->group + 1 : 1;
            DB::table('simrspku_klaim.emr_form_kfr')->insert([
                'id_cppt'       => $id_cppt,
                'group'         => $newGroup,
                'nomor_init'    => $kunjungan,
                'nomor'         => $kunjungan,
                'sep'           => $request->sep,
                'tgl_sep'       => $request->tgl_sep,
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

            /* ==========================
            * 3. BANGUN DATA UNTUK PDF (tanpa query ulang)
            * ========================== */
            $dataPasien = DB::table('master.pasien')
                        ->select(
                            'TANGGAL_LAHIR AS TGLLAHIRPASIEN',
                            DB::raw('master.getCariUmur(now(),TANGGAL_LAHIR) AS UMURPASIEN'),
                            DB::raw('master.getNamaLengkap(NORM) AS NAMAPASIEN'),
                            DB::raw('master.getAlamatPasienCustom(NORM) AS ALAMATPASIEN'),
                        )
                        ->where('NORM',$request->rm)
                        ->where('STATUS', true)
                        ->first();

            if (!$dataPasien) {
                return response()->json(['error' => 'Data Pasien tidak ditemukan.'], 404);
            }

            $show = (object)[
                'TGLSEP'         => $request->tgl_sep,
                'KUNJUNGAN'      => $kunjungan,
                'GROUP'          => $newGroup,
                'TANGGAL'        => now()->toDateString(),
                'NORM'           => $request->rm,
                'NAMAPASIEN'     => $dataPasien->NAMAPASIEN ?? '',
                'NAMADOKTER'     => $dokter->NAMADOKTER,
                'TGLLAHIRPASIEN' => $dataPasien->TGLLAHIRPASIEN ?? '',
                'UMURPASIEN'     => $dataPasien->UMURPASIEN ?? '',
                'ALAMATPASIEN'   => $dataPasien->ALAMATPASIEN ?? '',
                'SUBYEKTIF'      => $request->cppt_s,
                'OBYEKTIF'       => $request->cppt_o,
                'ASSESMENT'      => $request->cppt_a,
                'PLANNING1'      => $request->cppt_p_1,
                'PLANNING2'      => $request->cppt_p_2,
                'PLANNING3'      => $request->cppt_p_3,
                'PLANNING4'      => $request->cppt_p_4,
                'INSTRUKSI'      => $cppt_i,
                'PATH_TTE_DOKTER'=> $ttd_pegawai->signature_path,
            ];

            /* 4. KIRIM LANGSUNG KE GENERATOR */
            $generateForm = $this->generateFormKfr($show);

            if (!$generateForm) {
                return response()->json([
                    'status' => false,
                    'message'=> 'Gagal generate Form KFR'
                ], 500);
            }

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

    function generateFormKfr(object $show)
    {
        $getTgl = Carbon::parse($show->TGLSEP);
        $tgl = $getTgl->isoFormat('DD');
        $bulan = $getTgl->isoFormat('MM');
        $tahun = $getTgl->isoFormat('YYYY');

        $path = 'files/rehabmedik/formlayanankfr/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$show->KUNJUNGAN.'-1';

        $verify = klaim_file::where('nomor',$show->KUNJUNGAN)
                            ->where('jenis',11)
                            ->where('sub_jenis',1) // FORM KFR
                            ->where('status',true)
                            ->whereNull('deleted_at')
                            ->orderBy('id','DESC')
                            ->first();

        if (!$verify) {
            $post = new klaim_file;
            $post->jenis = 11;
            $post->sub_jenis = 1;
            $post->ref = $show->GROUP;
            $post->nomor = $show->KUNJUNGAN;
            $post->title = $show->KUNJUNGAN.'-1.pdf';
            $post->filename = $path.'.pdf';
            $post->nama_tambahan = 'Formulir Layanan KFR';
            $post->status = true;
            $post->user = Auth::user()->ID;
            $post->created_at = now();
            $post->updated_at = now();
            $post->save();
        } else {
            $verify->user = Auth::user()->ID;
            $verify->updated_at = now();
            $verify->save();
        }

        $output = storage_path().'/app/public/'.$path;

        // Pastikan folder tujuan ada
        $outputDir = dirname($output);
        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true); // true = recursive
        }

        $data = [
            'TANGGAL' => Carbon::parse($show->TANGGAL)->translatedFormat('d F Y'),
            'NORM' => $show->NORM,
            'NAMAPASIEN' => $show->NAMAPASIEN,
            'NAMADOKTER' => $show->NAMADOKTER,
            'TGLLAHIRPASIEN' => Carbon::parse($show->TGLLAHIRPASIEN)->translatedFormat('d F Y'),
            'UMURPASIEN' => $show->UMURPASIEN,
            'ALAMATPASIEN' => $show->ALAMATPASIEN,
            'SUBYEKTIF' => $show->SUBYEKTIF,
            'OBYEKTIF' => $show->OBYEKTIF,
            'ASSESMENT' => $show->ASSESMENT,
            'PLANNING1' => $show->PLANNING1,
            'PLANNING2' => $show->PLANNING2,
            'PLANNING3' => $show->PLANNING3,
            'PLANNING4' => $show->PLANNING4,
            'INSTRUKSI' => $show->INSTRUKSI,
        ];

        $templateProcessor = new TemplateProcessor(public_path('/doc/input/rehabmedik/cetakNewFormKFR.docx'));

        try {
            $this->setImgWord($templateProcessor, 'PATH_TTE_DOKTER', storage_path()."/app/public/".$show->PATH_TTE_DOKTER, 170);
            // if ($cap) {
            //     $this->setImgWord($templateProcessor, 'CAP', $cap, 150);
            // }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        foreach ($data as $key => $value) {
            $templateProcessor->setValue($key, $value);
        }

        $outputWord = $output.'.docx';
        $templateProcessor->saveAs($outputWord);

        [$success, $log, $result] = $this->libreOffice($outputWord, dirname($outputWord));

        if (!$success) {
            return response()->json("Gagal membuat PDF Formulir KFR (Periksa File/Instal Ulang Libre Office di Server)", 500);
        }

        if (File::exists($outputWord)) {
            File::delete($outputWord);
        }

        if (file_exists($output.'.pdf')) {
            return true;
        }

        return false;
    }

    function generateUlangFormKfr($kunjungan)
    {
        $show = DB::table('simrspku_klaim.emr_form_kfr as kfr')
            ->join('medicalrecord.cppt as cppt','cppt.ID','=','kfr.id_cppt')
            ->leftJoin('master.pasien as ps','ps.NORM','=','kfr.rm')
            ->select([
                'kfr.group as GROUP',
                'kfr.nomor as KUNJUNGAN',
                'kfr.tgl as TANGGAL',
                'kfr.tgl_sep as TGLSEP',
                'kfr.ttd_dokter as PATH_TTE_DOKTER',
                'kfr.rm as NORM',
                DB::raw('master.getNamaLengkap(kfr.rm) as NAMAPASIEN'),
                DB::raw('master.getAlamatPasienCustom(kfr.rm) as ALAMATPASIEN'),
                DB::raw('master.getNamaLengkapPegawai(kfr.nip_dokter) as NAMADOKTER'),
                DB::raw('master.getCariUmur(kfr.tgl, ps.TANGGAL_LAHIR) as UMURPASIEN'),
                'ps.TANGGAL_LAHIR as TGLLAHIRPASIEN',
                'cppt.SUBYEKTIF',
                'cppt.OBYEKTIF',
                'cppt.ASSESMENT',
                'cppt.PLANNING',
                'cppt.INSTRUKSI',
            ])
            ->where('kfr.nomor',$kunjungan)
            ->where('kfr.status',1)
            ->first();

        if (!$show) {
            return response()->json([
                'success' => false,
                'message' => 'Data Form KFR tidak ditemukan'
            ], 404);
        }

        // Normalisasi line break
        $planningText = preg_replace("/\r?\n/", "\n", $show->PLANNING);

        // Ambil tiap bagian planning
        $show->PLANNING1 = $this->getSection($planningText, 'Goal of Treatment');
        $show->PLANNING2 = $this->getSection($planningText, 'Tindakan/Program Rehabilitasi Medik');
        $show->PLANNING3 = $this->getSection($planningText, 'Edukasi');
        $show->PLANNING4 = $this->getSection($planningText, 'Frekuensi Kunjungan');

        $success = $this->generateFormKfr($show);

        if (!$success) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate Form KFR'
            ], 500);
        }

        // print_r($success); exit;

        // buat URL PDF
        $tgl = Carbon::parse($show->TGLSEP)->isoFormat('DD');
        $bulan = Carbon::parse($show->TGLSEP)->isoFormat('MM');
        $tahun = Carbon::parse($show->TGLSEP)->isoFormat('YYYY');

        $relative = "files/rehabmedik/formlayanankfr/$tahun/$bulan/$tgl/{$show->KUNJUNGAN}-1.pdf";

        return response()->json([
            'success' => true,
            'message' => 'PDF berhasil dibuat',
            'pdf_url' => asset('storage/'.$relative)
        ]);
    }

    function lihatFormKfr($KUNJUNGAN)
    {
        $getGroup = DB::table('simrspku_klaim.emr_form_kfr')
            ->where('nomor',$KUNJUNGAN)
            ->where('status',1)
            ->whereNull('deleted_at')
            ->orderBy('id','DESC')
            ->value('group');

        $show = DB::table('simrspku_klaim.klaim_file')
            ->where('nomor',$KUNJUNGAN)
            ->where('ref',$getGroup)
            ->where('jenis',11)
            ->where('sub_jenis',1)
            ->where('status',1)
            ->whereNull('deleted_at')
            ->orderBy('id','DESC')
            ->first();

        $output = storage_path().'/app/public/'.$show->filename;

        if (file_exists($output.'.pdf')) {
            return true;
        }

        return response()->file($output, [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function update(Request $request, $IDCPPT)
    {
        $validator = Validator::make(
            $request->all(),
            [
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
            ],
            [
                'kunjungan.required' => 'Kunjungan wajib diisi.',
                'tgl.required'       => 'Tanggal wajib diisi.',
                'tgl.date'           => 'Tanggal tidak valid.',

                'cppt_s.required'    => 'Isian Subyektif wajib diisi.',
                'cppt_o.required'    => 'Isian Obyektif wajib diisi.',
                'cppt_a.required'    => 'Isian Asesment wajib diisi.',

                'cppt_p_1.required'  => 'Planning (Goal of Treatment) wajib diisi.',
                'cppt_p_2.required'  => 'Planning (Tindakan/Program Rehabilitasi Medik) wajib diisi.',
                'cppt_p_3.required'  => 'Planning (Edukasi) wajib diisi.',
                'cppt_p_4.required'  => 'Planning (Frekuensi Kunjungan) wajib diisi.',

                'cppt_i.required'    => 'Pilihan Rencana Tindak Lanjut wajib diisi.',
                'cppt_i.in'          => 'Pilihan Rencana Tindak Lanjut tidak valid.',
                'cppt_i_rtl.required'=> 'Isian Rencana Tindak Lanjut wajib diisi.',
            ]
        );

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
        } elseif ($request->cppt_i == 3) {
            $cppt_i = "Selesai :\n".$request->cppt_i_rtl;
        } else {
            $cppt_i = "";
        }

        $planning =
            "Goal of Treatment:\n" .
            $request->cppt_p_1 . "\n\n" .

            "Tindakan/Program Rehabilitasi Medik:\n" .
            $request->cppt_p_2 . "\n\n" .

            "Edukasi:\n" .
            $request->cppt_p_3 . "\n\n" .

            "Frekuensi Kunjungan:\n" .
            $request->cppt_p_4;

        // PLANNING — gabung hanya yang terisi
        // $planningParts = array_filter([
        //     trim($request->cppt_p_1),
        //     trim($request->cppt_p_2),
        //     trim($request->cppt_p_3),
        //     trim($request->cppt_p_4),
        // ]);

        // $planning = implode("\r\n", $planningParts);

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
                    'updated_at'  => now(),
                    'ttd_dokter'  => $ttd_pegawai->signature_path,
                    'nama_dokter' => $dokter->NAMADOKTER,
                ]);

            /* 4. KIRIM LANGSUNG KE GENERATOR */
            $generateUlangForm = $this->generateUlangFormKfr($request->kunjungan);

            if (!$generateUlangForm) {
                return response()->json([
                    'status' => false,
                    'message'=> 'Gagal generate Form KFR'
                ], 500);
            }

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
            * 1. Hapus file klaim_file FORM KFR & Reset Status to 0
            * ========================== */
            DB::table('simrspku_klaim.klaim_file')
                ->where('nomor', $form->nomor)
                ->where('status', 1)
                ->update([
                    'user_deleted'      => auth()->id(),
                    'status'            => 0,
                    'deleted_at'        => $now
                ]);

            /* ==========================
            * 2. Soft Delete EMR FORM KFR & Reset Status to 0
            * ========================== */
            DB::table('simrspku_klaim.emr_form_kfr')
                ->where('id_cppt', $request->id_cppt)
                ->where('status', 1)
                ->update([
                    'user'       => auth()->id(),
                    'status'     => 0,
                    'deleted_at' => $now
                ]);

            /* ==========================
            * 3. Delete CPPT & Reset Status to 0
            * ========================== */
            DB::table('medicalrecord.cppt')
                ->where('ID', $request->id_cppt)
                ->where('STATUS', 1)
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
