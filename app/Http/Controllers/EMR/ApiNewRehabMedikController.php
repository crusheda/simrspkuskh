<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\simrspku_klaim\emr_form_kfr;
use App\Models\simrspku_klaim\emr_form_terapi;
use App\Models\simrspku_klaim\klaim_verifikasi;
use App\Models\simrspku_klaim\klaim_file;
use App\Models\Pengguna;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\TemplateProcessor;
use PHPJasper\PHPJasper;
use App\Services\LibreOfficeService;
use Carbon\Carbon;
use Auth, Storage;

class ApiNewRehabMedikController extends Controller
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
    // ====================================================================================================================================

    // CONTROLLER FOR LIBRE OFFICE ON LINUX SERVER
    // public function libreOffice($input, $output)
    // {
    //     $soffice = env('LIBREOFFICE_PATH', '/usr/bin/soffice');

    //     // Path absolut
    //     $input  = realpath($input);
    //     $output = realpath($output);

    //     if (!$input || !$output) {
    //         \Log::error('Path input/output tidak valid', compact('input','output'));
    //         return [false, [], -1];
    //     }

    //     // Environment aman untuk www-data
    //     putenv('HOME=/tmp');
    //     putenv('XDG_CACHE_HOME=/tmp');

    //     // Profile unik per proses (ANTI TABRAKAN)
    //     $profile = '/tmp/lo_' . uniqid();

    //     // Command convert
    //     $cmd = sprintf(
    //         '%s --headless --nologo --nofirststartwizard ' .
    //         '-env:UserInstallation=file://%s ' .
    //         '--convert-to pdf %s --outdir %s 2>&1',
    //         escapeshellcmd($soffice),
    //         escapeshellarg($profile),
    //         escapeshellarg($input),
    //         escapeshellarg($output)
    //     );

    //     exec($cmd, $log, $result);

    //     $outputPdf = $output . '/' . pathinfo($input, PATHINFO_FILENAME) . '.pdf';

    //     // Cleanup profile
    //     exec('rm -rf ' . escapeshellarg($profile));

    //     if ($result !== 0 || !file_exists($outputPdf)) {
    //         \Log::error('LibreOffice Linux gagal konversi', [
    //             'cmd' => $cmd,
    //             'log' => $log,
    //             'result' => $result,
    //         ]);
    //         return [false, $log, $result];
    //     }

    //     return [true, $outputPdf, $result];
    // }

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

    private function getSectionCopyCppt(string $text, string $title): ?string
    {
        $pattern = sprintf(
            '/%s\s*:\s*(.*?)(?=\n[A-Z][^:\n]{2,50}\s*:|\z)/is',
            preg_quote($title, '/')
        );

        if (preg_match($pattern, $text, $match)) {
            return trim($match[1]);
        }

        return null;
    }

    // ====================================================================================================================================
    // ==================================================  FORMULIR RAWAT JALAN KFR  ======================================================
    // ====================================================================================================================================
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
                'cppt.INSTRUKSI',
                'kfr.rtl',
                'kfr.rtl_kontrol'
            )
            ->first();

        if (!$data) {
            return response()->json([
                'status' => false,
                'message'=> 'Data tidak ditemukan'
            ]);
        }

        $JumlahData = DB::table('simrspku_klaim.emr_form_kfr AS kfr')
            ->join('medicalrecord.cppt AS cppt', 'cppt.ID', '=', 'kfr.id_cppt')
            ->where('kfr.nomor_init', $data->KUNJUNGAN)
            ->where('kfr.status', 1)
            ->count();

        $hiddenDelete = true;
        if ($JumlahData <= 1) {
            $hiddenDelete = false;
        }

        // Normalisasi line break
        $planningText = preg_replace("/\r?\n/", "\n", $data->PLANNING);

        // Ambil tiap bagian planning
        $p1 = $this->getSection($planningText, 'Goal of Treatment');
        $p2 = $this->getSection($planningText, 'Tindakan/Program Rehabilitasi Medik');
        $p3 = $this->getSection($planningText, 'Edukasi');
        $p4 = $this->getSection($planningText, 'Frekuensi Kunjungan');

        // Mapping instruksi
        $cppt_i = (int) ($data->rtl ?? 0);
        $cppt_i_tgl = $data->rtl_kontrol;

        // Ambil teks RTL TANPA baris tanggal kontrol
        $cppt_i_rtl = '';

        if ($data->INSTRUKSI) {
            $rtlText = preg_replace(
                '/Kontrol kembali ke Rumah Sakit pada tanggal\s*:\s*.+$/i',
                '',
                $data->INSTRUKSI
            );

            $rtlText = preg_replace('/^(Evaluasi|Rujuk|Selesai)\s*:\s*/i', '', $rtlText);

            $cppt_i_rtl = trim($rtlText);
        }
        // $instruksi_text = trim($data->INSTRUKSI);
        // $cppt_i = 0; $cppt_i_rtl = '';

        // if (str_starts_with($instruksi_text, "Evaluasi")) {
        //     $cppt_i = 1;
        //     $cppt_i_rtl = trim(str_replace("Evaluasi :", "", $instruksi_text));
        // } elseif (str_starts_with($instruksi_text, "Rujuk")) {
        //     $cppt_i = 2;
        //     $cppt_i_rtl = trim(str_replace("Rujuk :", "", $instruksi_text));
        // } elseif (str_starts_with($instruksi_text, "Selesai")) {
        //     $cppt_i = 3;
        //     $cppt_i_rtl = trim(str_replace("Selesai :", "", $instruksi_text));
        // }

        return response()->json([
            'status' => true,
            'kunjungan_init' => $data->KUNJUNGAN,
            'id_cppt' => $data->ID_CPPT,
            'hidden_delete' => $hiddenDelete,
            'data' => [
                's' => $data->SUBYEKTIF,
                'o' => $data->OBYEKTIF,
                'a' => $data->ASSESMENT,
                'p1'=> $p1,
                'p2'=> $p2,
                'p3'=> $p3,
                'p4'=> $p4,
                'cppt_i'     => $cppt_i,
                'cppt_i_tgl'  => $cppt_i_tgl,
                'cppt_i_rtl' => $cppt_i_rtl,
            ]
        ]);
    }

    function getByRM($NORM, $KUNJUNGAN) // LOAD GUNAKAN FORM LAMA
    {
        $data = DB::table('simrspku_klaim.emr_form_kfr AS kfr')
            ->select(
                'kfr.group',
                'kfr.queue',
                'kfr.nomor_init',
                DB::raw("LPAD(kfr.rm, 8, '0') as rm"),
                DB::raw("DATE_FORMAT(kfr.tgl_init, '%e %M %Y') as tgl_init"),
                'kfr.nama_dokter',
                'kfr.sep',
                'kfr.tgl_sep',
                DB::raw("MIN(kfr.created_at) AS created_at")
            )
            ->where('kfr.rm', $NORM)
            ->whereColumn('kfr.nomor_init', 'kfr.nomor')
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

    function getByRMnTgl($NORM, $KUNJUNGAN, $TGLS) // LOAD RIWAYAT GRID KANAN
    {
        $show = DB::table('simrspku_klaim.emr_form_kfr AS kfr')
            ->select(
                'kfr.group',
                'kfr.queue',
                'kfr.nomor_init',
                'kfr.nomor',
                DB::raw("LPAD(kfr.rm, 8, '0') as rm"),
                DB::raw("DATE_FORMAT(kfr.tgl_init, '%e %M %Y') as tgl_init"),
                'kfr.nip_dokter',
                'kfr.nama_dokter',
                'kfr.sep',
                'kfr.tgl_sep',
                'kfr.tgl',
                'kfr.bertemu_dokter',
                'kfr.created_at'
                // DB::raw("MIN(kfr.created_at) AS created_at")
            )
            ->where('kfr.rm', $NORM)
            ->whereNull('kfr.deleted_at')
            ->whereDate('kfr.tgl_sep', '<=', $TGLS)
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
            // ->whereDate('tgl_sep', $TGLS)
            ->where('status', 1)
            ->whereNull('deleted_at')
            ->first();

        // print_r($formKfr); die();

        $data = [
            'rm' => $NORM,
            'kunjungan' => $KUNJUNGAN,
            'tglsep' => $TGLS,
            'form' => $formKfr,
            'show' => $show
        ];

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    function getCppt($RM, $KUNJUNGAN, $TGLS)
    {
        $tgl = DB::table('pendaftaran.kunjungan')->where('NOMOR', $KUNJUNGAN)->where('STATUS','!=',0)->first();

        if ($tgl) {
            if ($tgl->KELUAR != null) {
                $tglpush = $tgl->KELUAR;
            } else {
                $tglpush = $TGLS;
            }
        } else {
            return response()->json([
                'status' => false,
                'message'=> 'Data Kunjungan tidak ditemukan untuk kunjungan ini'
            ]);
        }

        $formKfr = emr_form_kfr::where('nomor', $KUNJUNGAN)
            ->where('status', 1)
            ->whereNull('deleted_at')
            ->first();

        $data = DB::table('medicalrecord.cppt AS cppt')
            // ->leftJoin('master.dokter as dr', function($join) {
            //     $join->on('dr.ID', '=', 'cppt.TENAGA_MEDIS')
            //         ->where('dr.STATUS', '=', 1);
            // })
            // ->leftJoin('master.pegawai as pg', function($join) {
            //     $join->on('pg.ID', '=', 'cppt.TENAGA_MEDIS')
            //         ->where('pg.STATUS', '=', 1);
            // })
            // ->leftJoin('aplikasi.pengguna as pe', function($join) {
            //     $join->on('pe.ID', '=', 'cppt.OLEH')
            //         ->where('pe.STATUS', '=', 1);
            // })
            ->leftJoin('master.pegawai as pg','pg.ID', '=', 'cppt.TENAGA_MEDIS')
            ->leftJoin('aplikasi.pengguna as pe','pe.ID', '=', 'cppt.OLEH')
            ->join('pendaftaran.kunjungan as kj','kj.NOMOR','=','cppt.KUNJUNGAN')
            ->join('pendaftaran.pendaftaran as pf', function($join) use ($RM) {
                $join->on('pf.NOMOR','=','kj.NOPEN')
                    ->where('pf.NORM', $RM);
            })
            ->leftJoin('pendaftaran.penjamin AS pj','pj.NOPEN','=','pf.NOMOR')
            ->leftJoin('bpjs.kunjungan AS kjs','kjs.noSEP','=','pj.NOMOR')
            ->leftJoin('master.dokter AS dpjp','dpjp.ID','=','kj.DPJP')
            ->leftJoin('master.ruangan AS ru','ru.ID','=','kj.RUANGAN')
            // ->where('cppt.KUNJUNGAN', $KUNJUNGAN)
            ->where('cppt.STATUS', 1)
            ->where('kj.RUANGAN',$tgl->RUANGAN)
            ->whereDate('cppt.TANGGAL', '<=', $tglpush)
            ->select(
                'pf.TANGGAL as TGLPENDAFTARAN',
                'ru.DESKRIPSI AS NAMARUANGAN',
                'cppt.ID AS ID_CPPT',
                'cppt.KUNJUNGAN',
                'cppt.TANGGAL',
                'cppt.SUBYEKTIF',
                'cppt.OBYEKTIF',
                'cppt.ASSESMENT',
                'cppt.PLANNING',
                'cppt.INSTRUKSI',
                DB::raw('master.getNamaLengkapPegawai(pg.NIP) AS NAMADOKTER'),
                DB::raw('master.getNamaLengkapPegawai(dpjp.NIP) AS NAMADPJP'),
                DB::raw('master.getNamaLengkapPegawai(pe.NIP) AS NAMAUSER'),
            )
            ->orderBy('cppt.TANGGAL', 'DESC')
            ->get();

        if ($data->isEmpty()) {
            return response()->json([
                'status' => false,
                'message'=> 'Data CPPT tidak ditemukan untuk kunjungan ini'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $data,
            'form' => $formKfr
        ]);
    }

    function copyCpptKfr($KUNJUNGAN, $IDCPPT)
    {
        $formKfr = emr_form_kfr::where('nomor', $KUNJUNGAN)
            ->where('status', 1)
            ->whereNull('deleted_at')
            ->first();

        if ($formKfr) {
            return response()->json([
                'status' => false,
                'message'=> 'Form KFR untuk kunjungan ini sudah ada, tidak dapat menyalin CPPT.'
            ], 400);
        }

        $JumlahData = DB::table('simrspku_klaim.emr_form_kfr AS kfr')
            ->join('medicalrecord.cppt AS cppt', 'cppt.ID', '=', 'kfr.id_cppt')
            ->where('kfr.nomor_init', $KUNJUNGAN)
            ->where('kfr.status', 1)
            ->count();

        $hiddenDelete = true;
        if ($JumlahData <= 1) {
            $hiddenDelete = false;
        }

        $data = DB::table('medicalrecord.cppt AS cppt')
            ->select(
                'cppt.ID AS ID_CPPT',
                'cppt.SUBYEKTIF',
                'cppt.OBYEKTIF',
                'cppt.ASSESMENT',
                'cppt.PLANNING',
                'cppt.INSTRUKSI'
            )
            ->where('cppt.ID', $IDCPPT)
            ->where('cppt.STATUS', 1)
            ->first();

        if (!$data) {
            return response()->json([
                'status' => false,
                'message'=> 'Data CPPT tidak ditemukan atau telah terhapus'
            ], 404);
        }

        // Mapping
        $planningHeaders = [
            'Goal of Treatment',
            'Tindakan/Program Rehabilitasi Medik',
            'Edukasi',
            'Frekuensi Kunjungan',
        ];

        $planningRaw = $data->PLANNING ?? '';

        // Hilangkan HTML (AMAN untuk data lama)
        $planningText = trim(
            preg_replace("/\r?\n/", "\n", strip_tags($planningRaw))
        );

        $hasAllSections = true;

        foreach ($planningHeaders as $header) {
            if (!preg_match('/' . preg_quote($header, '/') . '\s*:/i', $planningText)) {
                $hasAllSections = false;
                break;
            }
        }

        if ($hasAllSections) {
            $data->PLANNING1 = $this->getSection($planningText, 'Goal of Treatment');
            $data->PLANNING2 = $this->getSection($planningText, 'Tindakan/Program Rehabilitasi Medik');
            $data->PLANNING3 = $this->getSection($planningText, 'Edukasi');
            $data->PLANNING4 = $this->getSection($planningText, 'Frekuensi Kunjungan');

            $data->PLANNING = null;
        } else {
            $data->PLANNING1 = null;
            $data->PLANNING2 = null;
            $data->PLANNING3 = null;
            $data->PLANNING4 = null;
        }


        // =============================
        // Mapping RTL (SUMBER DARI KFR)
        // =============================
        $cppt_i = (int) ($formKfr->rtl ?? 0);
        $cppt_i_tgl = $formKfr->rtl_kontrol ?? null;


        // =============================
        // Ambil isi RTL dari INSTRUKSI
        // =============================
        $cppt_i_rtl = '';

        if (!empty($data->INSTRUKSI)) {

            // Hapus baris tanggal kontrol
            $rtlText = preg_replace(
                '/Kontrol kembali ke Rumah Sakit pada tanggal\s*:\s*.+$/i',
                '',
                $data->INSTRUKSI
            );

            // Hapus prefix Evaluasi/Rujuk/Selesai
            $rtlText = preg_replace(
                '/^(Evaluasi|Rujuk|Selesai)\s*:\s*/i',
                '',
                $rtlText
            );

            $cppt_i_rtl = trim($rtlText);
        }


        // =============================
        // Inject ke response
        // =============================
        $data->CPPT_I      = $cppt_i;
        $data->CPPT_I_TGL  = $cppt_i_tgl;
        $data->CPPT_I_RTL  = $cppt_i_rtl;

        return response()->json([
            'status' => true,
            'data' => $data,
            'form' => $formKfr,
            'hidden_delete' => $hiddenDelete
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
                'tgl_masuk'         => 'required',
                'bertemu_dokter'    => 'required',
            ],
            [
                'nomor_init.required'        => 'Nomor Init Kunjungan wajib terkirim.',
                'nomor_kunjungan.required'   => 'Nomor Kunjungan saat ini wajib terkirim.',
                'rm.required'                => 'Nomor RM wajib terkirim.',
                'sep.required'               => 'Nomor SEP wajib terkirim.',
                'tgl_sep.required'           => 'Tanggal SEP wajib terkirim.',
                'tgl_kfr.required'           => 'Tanggal KFR wajib terkirim.',
                'tgl_masuk.required'         => 'Tanggal Masuk Kunjungan wajib terkirim.',
                'bertemu_dokter.required'    => 'Status bertemu dokter wajib terkirim.',
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

        $ttd_pegawai = DB::table('simrspku_klaim.tanda_tangan_pegawai as ttp')
            ->where('ttp.nip', $dokter->NIP)
            ->where('status', 1)
            ->inRandomOrder()
            ->first();

        if (!$ttd_pegawai) {
            return response()->json([
                'status' => false,
                'message'=> 'Data Tandatangan dokter tidak ditemukan untuk user ini'
            ], 404);
        }

        DB::beginTransaction();

        try {
            $tglPush = now()->toTimeString();
            $tglMasuk  = Carbon::parse($request->tgl_masuk);
            if ($request->filled('tgl_keluar')) {
                $tglKeluar = Carbon::parse($request->tgl_keluar);

                if ($tglMasuk->isSameDay($tglKeluar)) {
                    $tglPush = $tglMasuk->toDateString() . ' ' . now()->toTimeString();
                }
            } else {
                $tglPush = $tglMasuk->toDateString() . ' ' . now()->toTimeString();
            }

            $formLama = emr_form_kfr::where('rm', $request->rm)
                ->where('nomor_init', $request->nomor_init)
                // ->where('nomor', $request->nomor_init)
                ->where('status', 1)
                ->whereNull('deleted_at')
                ->latest('queue')
                ->first();

            if (!$formLama) {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message'=> 'Form KFR Lama tidak ditemukan / telah terhapus'
                ], 404);
            }

            // PERTEMUAN DOKTER
            if ($request->bertemu_dokter) { // TRUE = BERTEMU DOKTER
                $tglForm = $tglPush;
            } else { // FALSE = TIDAK BERTEMU DOKTER
                $tglForm = $formLama->tgl; // PAKAI TGL LAMA
            }

            $cpptLama = DB::table('medicalrecord.cppt')
                            ->where('ID', $formLama->id_cppt)
                            // ->where('KUNJUNGAN', $formLama->nomor_init)
                            ->where('KUNJUNGAN', $formLama->nomor)
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

            // GET LAST QUEUE
            $lastQueue = DB::table('simrspku_klaim.emr_form_kfr')
                        ->where('group', $formLama->group)
                        ->where('nomor_init', $request->nomor_init)
                        ->where('status', 1)
                        ->whereNull('deleted_at')
                        ->max('queue');

            // INSERT NEW CPPT WITH OLD DATA CPPT
            $id_cppt = DB::table('medicalrecord.cppt')->insertGetId([
                'KUNJUNGAN'    => $request->nomor_kunjungan,
                'TANGGAL'      => $tglPush,
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
            $formBaru->queue = $lastQueue + 1;
            $formBaru->nomor = $request->nomor_kunjungan;
            $formBaru->sep = $request->sep;
            $formBaru->tgl_sep = $request->tgl_sep;
            $formBaru->tgl = $tglForm;
            $formBaru->id_ttd_dokter = $ttd_pegawai->id;
            $formBaru->ttd_dokter = $ttd_pegawai->signature_path;
            $formBaru->nip_dokter = $ttd_pegawai->nip;
            $formBaru->nama_dokter = $dokter->NAMADOKTER;
            $formBaru->bertemu_dokter = $request->bertemu_dokter;
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
                'TANGGAL'        => $tglForm,
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

    function unsyncFormLama(Request $request)
    {
        $now = Carbon::now();
        $validator = Validator::make(
            $request->all(),
            [
                'nomor_kunjungan'   => 'required',
            ],
            [
                'nomor_kunjungan.required'   => 'Nomor Kunjungan saat ini wajib terkirim.',
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
            $form = DB::table('simrspku_klaim.emr_form_kfr')
                ->where('nomor', $request->nomor_kunjungan)
                ->where('status', 1)
                ->whereNull('deleted_at')
                ->update([
                    'status' => 0,
                    'deleted_at' => now()
                ]);

            $form = DB::table('simrspku_klaim.emr_form_kfr')
                ->where('nomor', $request->nomor_kunjungan)
                ->where('status', 1)
                ->whereNull('deleted_at')
                ->orderByDesc('id')
                ->first();

            if ($form) {
                $cppt = DB::table('medicalrecord.cppt')
                    ->where('ID', $form->id_cppt)
                    ->where('STATUS', 1)
                    ->update([
                        'STATUS' => 0,
                    ]);

                DB::table('simrspku_klaim.emr_form_kfr')
                    ->where('id', $form->id)
                    ->update([
                        'status'     => 0,
                        'deleted_at' => now()
                    ]);
            }

            $file = DB::table('simrspku_klaim.klaim_file')
                ->where('nomor', $request->nomor_kunjungan)
                ->where('status', 1)
                ->whereNull('deleted_at')
                ->update([
                    'status' => 0,
                    'deleted_at' => now()
                ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message'=> 'Form KFR, CPPT, & Berkas Klaim telah berhasil terhapus dan telah terputus hubungan dengan Form KFR Utama'
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
                'tgl_masuk'    => 'required',

                'cppt_s'       => 'required',
                'cppt_o'       => 'required',
                'cppt_a'       => 'required',

                'cppt_p_1'     => 'required',
                'cppt_p_2'     => 'required',
                'cppt_p_3'     => 'required',
                'cppt_p_4'     => 'required',

                'cppt_i'       => 'required|in:1,2,3',
                'cppt_i_tgl'   => 'required_if:cppt_i,1',
                'cppt_i_rtl'   => 'required',
            ],
            [
                'rm.required'               => 'Nomor RM wajib diisi.',
                'rm.integer'                => 'Nomor RM harus berupa angka.',
                'kunjungan.required'        => 'Kunjungan wajib diisi.',
                'sep.required'              => 'SEP wajib diisi.',
                'tgl_sep.required'          => 'Tanggal SEP wajib diisi.',
                'tgl_sep.date'              => 'Tanggal SEP tidak valid.',
                'tgl.required'              => 'Tanggal wajib diisi.',
                'tgl.date'                  => 'Tanggal tidak valid.',
                'tgl_masuk.required'        => 'Tanggal Masuk Kunjungan wajib terkirim.',
                'cppt_s.required'           => 'Isian Subjective wajib diisi.',
                'cppt_o.required'           => 'Isian Objective wajib diisi.',
                'cppt_a.required'           => 'Isian Assessment wajib diisi.',

                'cppt_p_1.required'         => 'Isian Planning (Goal of Treatment) wajib diisi.',
                'cppt_p_2.required'         => 'Isian Planning (Tindakan/Program Rehabilitasi Medik) wajib diisi.',
                'cppt_p_3.required'         => 'Isian Planning (Edukasi) wajib diisi.',
                'cppt_p_4.required'         => 'Isian Planning (Frekuensi Kunjungan) wajib diisi.',

                'cppt_i.required'           => 'Pilihan Rencana Tindak Lanjut wajib diisi.',
                'cppt_i.in'                 => 'Pilihan Rencana Tindak Lanjut tidak valid.',
                'cppt_i_tgl.required_if'    => 'Tanggal Rencana Kontrol wajib diisi jika pilihan adalah Evaluasi.',
                'cppt_i_rtl.required'       => 'Isian Rencana Tindak Lanjut wajib diisi.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message'=> $validator->errors()->first()
            ], 422);
        }

        $tglPush = now()->toTimeString();
        $tglMasuk  = Carbon::parse($request->tgl_masuk);
        if ($request->filled('tgl_keluar')) {
            $tglKeluar = Carbon::parse($request->tgl_keluar);

            if ($tglMasuk->isSameDay($tglKeluar)) {
                $tglPush = $tglMasuk->toDateString() . ' ' . now()->toTimeString();
            }
        } else {
            $tglPush = $tglMasuk->toDateString() . ' ' . now()->toTimeString();
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

            if (!$ttd_pegawai) {
                throw new \Exception('Data TTD dokter tidak ditemukan untuk user ini');
            }

            if ($request->cppt_i == 1) {
                $tglKontrol = Carbon::parse($request->cppt_i_tgl)
                                ->locale('id')
                                ->translatedFormat('d F Y');
                $cppt_i =
                    "Evaluasi :\n" .
                    $request->cppt_i_rtl . "\n\n" .
                    "Kontrol kembali ke Rumah Sakit pada tanggal : " . $tglKontrol;
                // $cppt_i = "Evaluasi :\n".$request->cppt_i_rtl;
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
                'TANGGAL'      => $tglPush,
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
            // $lastGroup = DB::table('simrspku_klaim.emr_form_kfr')
            //                 ->where('nomor_init', $kunjungan)
            //                 ->where('rm', $request->rm)
            //                 ->where('status', 1)
            //                 ->whereNull('deleted_at')
            //                 ->orderBy('group', 'DESC')
            //                 ->first();
            // $newGroup = $lastGroup ? $lastGroup->group + 1 : 1;
            DB::table('simrspku_klaim.emr_form_kfr')->insert([
                'id_cppt'       => $id_cppt,
                'group'         => 1,
                'queue'         => 1,
                'nomor_init'    => $kunjungan,
                'nomor'         => $kunjungan,
                'sep'           => $request->sep,
                'tgl_sep'       => $request->tgl_sep,
                'tgl_init'      => $request->tgl,
                'tgl'           => $tglPush,
                'rm'            => $request->rm,
                'id_ttd_dokter' => $ttd_pegawai->id,
                'ttd_dokter'    => $ttd_pegawai->signature_path,
                'nip_dokter'    => $ttd_pegawai->nip,
                'rtl'           => $request->cppt_i,
                'rtl_kontrol'   => $request->cppt_i == 1 ? $request->cppt_i_tgl : null,
                'nama_dokter'   => $dokter->NAMADOKTER,
                'bertemu_dokter'=> 1,
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
                'GROUP'          => 1,
                'TANGGAL'        => $tglPush,
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
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
            // return response()->json(['error' => $e->getMessage()], 422);
        }

        foreach ($data as $key => $value) {
            $templateProcessor->setValue($key, $value);
        }

        $outputWord = $output.'.docx';
        $templateProcessor->saveAs($outputWord);

        [$success, $log, $result] = $this->libreOffice->generatePdf($outputWord, dirname($outputWord));

        if (!$success) {
            return response()->json("Gagal membuat PDF Formulir KFR (Periksa File/Instal Ulang Libre Office di Server)", 500);
        }

        if (File::exists($outputWord)) {
            File::delete($outputWord);
        }

        if (file_exists($output.'.pdf')) {
            return [
                'success' => true
            ];
        }

        return [
            'success' => false,
            'message' => 'File PDF tidak ditemukan'
        ];
        // return false;
    }

    function generateUlangFormKfr($kunjungan)
    {
        $showInit = DB::table('simrspku_klaim.emr_form_kfr as kfr')
            ->where('kfr.nomor',$kunjungan)
            ->where('kfr.status',1)
            ->first();

        if (!$showInit) {
            return [
                'success' => false,
                'message' => 'Data Form KFR tidak ditemukan'
            ];
        }

        // $ttd_pegawai = DB::table('simrspku_klaim.tanda_tangan_pegawai')
        //         ->where('nip', Auth::user()->NIP)
        //         ->where('status', 1)
        //         ->inRandomOrder()
        //         ->first();

        // if (!$ttd_pegawai) {
        //     return [
        //         'success' => false,
        //         'message' => 'Data TTD dokter tidak ditemukan untuk user ini'
        //     ];
        // }

        // $dokter = DB::table('master.dokter as dr')
        //             ->leftJoin('aplikasi.pengguna as pe', function($join) {
        //                 $join->on('pe.NIP', '=', 'dr.NIP')
        //                     ->where('pe.STATUS', '=', 1);
        //             })
        //             ->select('dr.ID', 'pe.NAMA AS DOKTER', 'dr.NIP', DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'))
        //             ->where('pe.ID', auth()->id())
        //             ->where('dr.STATUS', 1)
        //             ->first();

        // if (!$dokter) {
        //     return [
        //         'success' => false,
        //         'message' => 'Data dokter tidak ditemukan untuk user ini'
        //     ];
        // }

        // // UPDATE TTD DOKTER
        // $updated = DB::table('simrspku_klaim.emr_form_kfr')
        //             ->where('nomor', $kunjungan)
        //             ->where('status', 1)
        //             ->update([
        //                 'id_ttd_dokter' => $ttd_pegawai->id,
        //                 'ttd_dokter'    => $ttd_pegawai->signature_path,
        //                 'nip_dokter'    => $ttd_pegawai->nip,
        //                 'nama_dokter'   => $dokter->NAMADOKTER,
        //                 'updated_at'    => now(),
        //             ]);

        $show = DB::table('simrspku_klaim.emr_form_kfr as kfr')
            ->join('medicalrecord.cppt as cppt', function($join) {
                $join->on('cppt.ID','=','kfr.id_cppt')
                    ->where('cppt.STATUS', '=', 1);
            })
            // ->join('medicalrecord.cppt as cppt','cppt.ID','=','kfr.id_cppt')
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
            ->where('kfr.nomor', $kunjungan)
            ->where('kfr.status', 1)
            ->whereNull('kfr.deleted_at')
            ->first();

        if (!$show) {
            return [
                'success' => false,
                'message' => 'Periksa kembali data Form Program Terapi dan CPPT yang bersangkutan'
            ];
        }

        // Normalisasi line break
        $planningText = preg_replace("/\r?\n/", "\n", $show->PLANNING);

        // Ambil tiap bagian planning
        $show->PLANNING1 = $this->getSection($planningText, 'Goal of Treatment');
        $show->PLANNING2 = $this->getSection($planningText, 'Tindakan/Program Rehabilitasi Medik');
        $show->PLANNING3 = $this->getSection($planningText, 'Edukasi');
        $show->PLANNING4 = $this->getSection($planningText, 'Frekuensi Kunjungan');

        $result  = $this->generateFormKfr($show);

        if (!$result ['success']) {
            return [
                'success' => false,
                'message' => $result ['message'] ?? 'Gagal generate Form KFR'
            ];
        }

        // buat URL PDF
        $tgl = Carbon::parse($show->TGLSEP)->isoFormat('DD');
        $bulan = Carbon::parse($show->TGLSEP)->isoFormat('MM');
        $tahun = Carbon::parse($show->TGLSEP)->isoFormat('YYYY');

        $relative = "files/rehabmedik/formlayanankfr/$tahun/$bulan/$tgl/{$show->KUNJUNGAN}-1.pdf";

        return [
            'success' => true,
            'message' => 'PDF berhasil dibuat',
            'pdf_url' => asset('storage/'.$relative)
        ];
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

        if (!$show) {
            abort(404);
        }

        $path = storage_path('app/public/'.$show->filename);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Content-Type'  => 'application/pdf',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma'        => 'no-cache',
            'Expires'       => '0',
        ]);

        // return response()->file($output, [
        //     'Content-Type' => 'application/pdf',
        // ]);
    }

    public function update(Request $request, $IDCPPT)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'kunjungan'    => 'required',
                'tgl'          => 'required|date',
                'tgl_masuk'    => 'required',

                'cppt_s'       => 'required',
                'cppt_o'       => 'required',
                'cppt_a'       => 'required',

                'cppt_p_1'     => 'required',
                'cppt_p_2'     => 'required',
                'cppt_p_3'     => 'required',
                'cppt_p_4'     => 'required',

                'cppt_i'       => 'required|in:1,2,3',
                'cppt_i_tgl'   => 'required_if:cppt_i,1',
                'cppt_i_rtl'   => 'required',
            ],
            [
                'kunjungan.required'        => 'Kunjungan wajib diisi.',
                'tgl.required'              => 'Tanggal wajib diisi.',
                'tgl.date'                  => 'Tanggal tidak valid.',
                'tgl_masuk.required'        => 'Tanggal Masuk Kunjungan wajib terkirim.',

                'cppt_s.required'           => 'Isian Subyektif wajib diisi.',
                'cppt_o.required'           => 'Isian Obyektif wajib diisi.',
                'cppt_a.required'           => 'Isian Asesment wajib diisi.',

                'cppt_p_1.required'         => 'Planning (Goal of Treatment) wajib diisi.',
                'cppt_p_2.required'         => 'Planning (Tindakan/Program Rehabilitasi Medik) wajib diisi.',
                'cppt_p_3.required'         => 'Planning (Edukasi) wajib diisi.',
                'cppt_p_4.required'         => 'Planning (Frekuensi Kunjungan) wajib diisi.',

                'cppt_i.required'           => 'Pilihan Rencana Tindak Lanjut wajib diisi.',
                'cppt_i.in'                 => 'Pilihan Rencana Tindak Lanjut tidak valid.',
                'cppt_i_tgl.required_if'    => 'Tanggal Rencana Kontrol wajib diisi jika pilihan adalah Evaluasi.',
                'cppt_i_rtl.required'       => 'Isian Rencana Tindak Lanjut wajib diisi.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message'=> $validator->errors()->first()
            ], 422);
        }

        $tglPush = now()->toTimeString();
        $tglMasuk  = Carbon::parse($request->tgl_masuk);
        if ($request->filled('tgl_keluar')) {
            $tglKeluar = Carbon::parse($request->tgl_keluar);

            if ($tglMasuk->isSameDay($tglKeluar)) {
                $tglPush = $tglMasuk->toDateString() . ' ' . now()->toTimeString();
            }
        } else {
            $tglPush = $tglMasuk->toDateString() . ' ' . now()->toTimeString();
        }

        // === Build INSTRUKSI sesuai pilihan ===
        if ($request->cppt_i == 1) {
            $tglKontrol = Carbon::parse($request->cppt_i_tgl)
                            ->locale('id')
                            ->translatedFormat('d F Y');
            $cppt_i =
                "Evaluasi :\n" .
                $request->cppt_i_rtl . "\n\n" .
                "Kontrol kembali ke Rumah Sakit pada tanggal : " . $tglKontrol;
            // $cppt_i = "Evaluasi : \n".$request->cppt_i_rtl;
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
                    'TANGGAL'      => $tglPush,
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
                    'rtl'           => $request->cppt_i,
                    'rtl_kontrol'   => $request->cppt_i == 1 ? $request->cppt_i_tgl : null,
                    'user'          => auth()->id(),
                    'updated_at'    => now(),
                    'id_ttd_dokter' => $ttd_pegawai->id,
                    'ttd_dokter'    => $ttd_pegawai->signature_path,
                    'nip_dokter'    => $ttd_pegawai->nip,
                    'nama_dokter'   => $dokter->NAMADOKTER,
                ]);

            DB::commit();

            /* 3. KIRIM LANGSUNG KE GENERATOR */
            $generateUlangForm = $this->generateUlangFormKfr($request->kunjungan);

            if (!$generateUlangForm['success']) {
                return response()->json([
                    'status' => false,
                    'message'=> $generateUlangForm['message'] ?? 'Gagal generate Form KFR'
                ], 422);
            }

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

    // ====================================================================================================================================
    // ===================================================  FORMULIR PROGRAM TERAPI  ======================================================
    // ====================================================================================================================================
    public function getProgram($KUNJUNGAN)
    {
        $validasi = emr_form_kfr::where('nomor',$KUNJUNGAN)->where('status',1)->whereNull('deleted_at')->latest('id')->first();

        if (!$validasi) {
            return response()->json([
                'status' => false,
                'message' => 'Data Formulir KFR tidak ditemukan untuk kunjungan ini. Silakan melakukan pengisian pada Tab Formulir Rawat Jalan KFR terlebih dahulu'
            ]);
        }

        // $data = DB::table('simrspku_klaim.emr_form_terapi AS ft')
        //     ->join('medicalrecord.cppt AS cppt', 'cppt.ID', '=', 'ft.id_cppt')
        //     ->where('ft.nomor', $KUNJUNGAN)
        //     ->where('ft.status', 1)
        //     ->select(
        //         'ft.nomor AS KUNJUNGAN',
        //         'cppt.ID AS ID_CPPT',
        //         'cppt.SUBYEKTIF',
        //         'cppt.OBYEKTIF',
        //         'cppt.ASSESMENT',
        //         'cppt.PLANNING as PROCEDURE',
        //         'cppt.INSTRUKSI'
        //     )
        //     ->first();

        // if (!$data) {
        //     return response()->json([
        //         'status' => false,
        //         'nullData' => 'pterapi',
        //         'message'=> 'Data Formulir Program tidak ditemukan'
        //     ]);
        // }

        return response()->json([
            'status' => true,
            'data' => $validasi
            // 'data' => [
            //     's' => $data->SUBYEKTIF,
            //     'o' => $data->OBYEKTIF,
            //     'a' => $data->ASSESMENT,
            //     'p' => $data->PROCEDURE,
            // ]
        ], 200);
    }

    function getRiwayatProgram($KUNJUNGAN)
    {
        $data = DB::table('simrspku_klaim.emr_form_terapi AS ftr')
            ->leftJoin('aplikasi.pengguna AS pu', 'pu.ID', '=', 'ftr.user')
            ->select('ftr.*','pu.NAMA AS nama_user',DB::raw('master.getNamaLengkapPegawai(pu.NIP) AS nama_lengkap_user'))
            ->where('ftr.nomor', $KUNJUNGAN)
            ->where('ftr.status', 1)
            ->whereNull('ftr.deleted_at')
            ->orderBy('ftr.created_at', 'DESC')
            ->get();

        if ($data->isEmpty()) {
            return response()->json([
                'status' => false,
                'message'=> 'Data Formulir Program Terapi tidak ditemukan pada kunjungan ini'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $data
        ], 200);
    }

    function getCpptProgram($RM, $KUNJUNGAN, $TGLS)
    {
        $tgl = DB::table('pendaftaran.kunjungan')->where('NOMOR', $KUNJUNGAN)->where('STATUS','!=',0)->first();

        if ($tgl) {
            if ($tgl->KELUAR != null) {
                $tglpush = $tgl->KELUAR;
            } else {
                $tglpush = $TGLS;
            }
        } else {
            return response()->json([
                'status' => false,
                'message'=> 'Data Kunjungan tidak ditemukan untuk kunjungan ini'
            ]);
        }

        $data = DB::table('medicalrecord.cppt AS cppt')
            // ->leftJoin('master.dokter as dr', function($join) {
            //     $join->on('dr.ID', '=', 'cppt.TENAGA_MEDIS')
            //         ->where('dr.STATUS', '=', 1);
            // })
            // ->leftJoin('master.pegawai as pg', function($join) {
            //     $join->on('pg.ID', '=', 'cppt.TENAGA_MEDIS')
            //         ->where('pg.STATUS', '=', 1);
            // })
            // ->leftJoin('aplikasi.pengguna as pe', function($join) {
            //     $join->on('pe.ID', '=', 'cppt.OLEH')
            //         ->where('pe.STATUS', '=', 1);
            // })
            ->leftJoin('master.pegawai as pg','pg.ID', '=', 'cppt.TENAGA_MEDIS')
            ->leftJoin('aplikasi.pengguna as pe','pe.ID', '=', 'cppt.OLEH')
            ->join('pendaftaran.kunjungan as kj','kj.NOMOR','=','cppt.KUNJUNGAN')
            ->join('pendaftaran.pendaftaran as pf', function($join) use ($RM) {
                $join->on('pf.NOMOR','=','kj.NOPEN')
                    ->where('pf.NORM', $RM);
            })
            ->leftJoin('pendaftaran.penjamin AS pj','pj.NOPEN','=','pf.NOMOR')
            ->leftJoin('bpjs.kunjungan AS kjs','kjs.noSEP','=','pj.NOMOR')
            ->leftJoin('master.dokter AS dpjp','dpjp.ID','=','kj.DPJP')
            ->leftJoin('master.ruangan AS ru','ru.ID','=','kj.RUANGAN')
            // ->where('cppt.KUNJUNGAN', $KUNJUNGAN)
            ->where('cppt.STATUS', 1)
            ->where('kj.RUANGAN',$tgl->RUANGAN)
            ->whereDate('cppt.TANGGAL', '<=', $tglpush)
            ->select(
                'pf.TANGGAL as TGLPENDAFTARAN',
                'ru.DESKRIPSI AS NAMARUANGAN',
                'cppt.KUNJUNGAN',
                'cppt.ID AS ID_CPPT',
                'cppt.TANGGAL',
                'cppt.SUBYEKTIF',
                'cppt.OBYEKTIF',
                'cppt.ASSESMENT',
                'cppt.PLANNING',
                'cppt.INSTRUKSI',
                DB::raw('master.getNamaLengkapPegawai(pg.NIP) AS NAMADOKTER'),
                DB::raw('master.getNamaLengkapPegawai(dpjp.NIP) AS NAMADPJP'),
                DB::raw('master.getNamaLengkapPegawai(pe.NIP) AS NAMAUSER'),
            )
            ->orderBy('cppt.TANGGAL', 'DESC')
            ->get();

        if ($data->isEmpty()) {
            return response()->json([
                'status' => false,
                'message'=> 'Data CPPT tidak ditemukan untuk kunjungan ini'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    function copyCpptProgram($KUNJUNGAN,$IDCPPT)
    {
        $data = DB::table('medicalrecord.cppt AS cppt')
            ->select(
                'cppt.ID AS ID_CPPT',
                'cppt.KUNJUNGAN',
                'cppt.SUBYEKTIF',
                'cppt.OBYEKTIF',
                'cppt.ASSESMENT',
                'cppt.PLANNING AS PROCEDURE'
            )
            ->where('cppt.ID', $IDCPPT)
            ->where('cppt.STATUS', 1)
            ->first();

        if (!$data) {
            return response()->json([
                'status' => false,
                'message'=> 'Data CPPT tidak ditemukan atau telah terhapus'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    function getProgramEdit($KUNJUNGAN, $GROUP, $QUEUE)
    {
        $data = DB::table('simrspku_klaim.emr_form_terapi AS ftr')
            ->join('medicalrecord.cppt AS cppt', function($join) {
                $join->on('cppt.ID', '=', 'ftr.id_cppt')
                    ->where('cppt.STATUS', '=', 1);
            })
            ->select(
                'ftr.nomor AS KUNJUNGAN',
                'cppt.ID AS ID_CPPT',
                'cppt.SUBYEKTIF',
                'cppt.OBYEKTIF',
                'cppt.ASSESMENT',
                'cppt.PLANNING as PROCEDURE'
            )
            ->where('ftr.group', $GROUP)
            ->where('ftr.queue', $QUEUE)
            ->where('ftr.status', 1)
            ->whereNull('ftr.deleted_at')
            ->orderBy('ftr.created_at', 'DESC')
            ->first();

        if (!$data) {
            return response()->json([
                'status' => false,
                'message'=> 'Data Formulir Program Terapi tidak ditemukan pada kunjungan ini'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $data,
            'kunjungan' => $KUNJUNGAN,
            'group' => $GROUP,
            'queue' => $QUEUE,
        ], 200);
    }

    function storeProgram(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'rm'           => 'required|integer',
                'kunjungan'    => 'required',
                'sep'          => 'required',
                'tgl_sep'      => 'required|date',
                'tgl'          => 'required|date',
                'tgl_masuk'    => 'required',

                'cppt_s_t'     => 'required',
                'cppt_o_t'     => 'required',
                'cppt_a_t'     => 'required',
                'cppt_p_t'     => 'required',
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
                'tgl_masuk.required' => 'Tanggal Masuk Kunjungan wajib terkirim.',

                'cppt_s_t.required'  => 'Isian Subjective wajib diisi.',
                'cppt_o_t.required'  => 'Isian Objective wajib diisi.',
                'cppt_a_t.required'  => 'Isian Assessment wajib diisi.',
                'cppt_p_t.required'  => 'Isian Procedure wajib diisi.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message'=> $validator->errors()->first()
            ], 422);
        }

        $tglPush = now()->toTimeString();
        $tglMasuk  = Carbon::parse($request->tgl_masuk);
        if ($request->filled('tgl_keluar')) {
            $tglKeluar = Carbon::parse($request->tgl_keluar);

            if ($tglMasuk->isSameDay($tglKeluar)) {
                $tglPush = $tglMasuk->toDateString() . ' ' . now()->toTimeString();
            }
        } else {
            $tglPush = $tglMasuk->toDateString() . ' ' . now()->toTimeString();
        }

        DB::beginTransaction();
        try {
            $kunjungan = $request->kunjungan;

            // GET GROUP KUNJUNGAN
            $getKFR = emr_form_kfr::where('nomor',$kunjungan)->where('status',1)->whereNull('deleted_at')->first();

            if (!$getKFR) {
                return response()->json([
                    'status' => false,
                    'message'=> 'Form KFR Tidak ditemukan untuk kunjungan ini. Wajib mengisi Form KFR terlebih dahulu.'
                ], 404);
            }

            $dokter = DB::table('master.dokter as dr')
                        ->leftJoin('aplikasi.pengguna as pe', function($join) {
                            $join->on('pe.NIP', '=', 'dr.NIP')
                                ->where('pe.STATUS', '=', 1);
                        })
                        ->select('dr.ID', 'pe.NAMA AS DOKTER', 'dr.NIP', DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'))
                        ->where('pe.NIP', $getKFR->nip_dokter)
                        ->where('dr.STATUS', 1)
                        ->first();

            if (!$dokter) {
                return response()->json([
                    'status' => false,
                    'message'=> 'Data dokter tidak ditemukan untuk user ini'
                ], 404);
            }

            // GET TTD DOKTER
            $ttd_pegawai_dr = DB::table('simrspku_klaim.tanda_tangan_pegawai')
                ->where('nip', $dokter->NIP)
                ->where('status', 1)
                ->inRandomOrder()
                ->first();

            if (!$ttd_pegawai_dr) {
                return response()->json([
                    'status' => false,
                    'message'=> 'Data TTD dokter tidak ditemukan untuk user ini'
                ], 404);
            }

            // GET TTD TERAPIS
            $ttd_pegawai_tr = DB::table('simrspku_klaim.tanda_tangan_pegawai')
                ->where('nip', Auth::user()->NIP)
                ->where('status', 1)
                ->whereNull('deleted_at')
                ->inRandomOrder()
                ->first();

            if (!$ttd_pegawai_tr) {
                return response()->json([
                    'status' => false,
                    'message'=> 'Data TTD tim tidak ditemukan untuk user ini'
                ], 404);
            }

            // GET ROLE TIM (1:DR, 2:FT, 3:OT, 4:TW)
            $role = Auth::user()->getRoleNames()->first();
            $jenis = 0;
            if ($role == 'dokterspesialis') {
                $jenis = 1;
            } else if($role == 'fisioterapis') {
                $jenis = 10;
            } else if($role == 'okupasiterapi') {
                $jenis = 12;
            } else if($role == 'terapiwicara') {
                $jenis = 11;
            } else {
                return response()->json([
                    'status' => false,
                    'message'=> 'Hak Akses Anda tidak valid untuk mengisi Form Program Terapi'
                ], 404);
            }

            $tim = DB::table('aplikasi.pengguna as pe')
                        ->select('pe.ID', 'pe.NIP', DB::raw('master.getNamaLengkapPegawai(pe.NIP) AS NAMATIM'))
                        ->where('pe.NIP', Auth::user()->NIP)
                        ->where('pe.STATUS', 1)
                        ->first();

            if (!$tim) {
                return response()->json([
                    'status' => false,
                    'message'=> 'Data Akun tim tidak ditemukan'
                ], 404);
            }

            // GET DATA PASIEN
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
                return response()->json([
                    'status' => false,
                    'message'=> 'Data Pasien tidak ditemukan'
                ], 404);
            }

            /* ==========================
            * 1. INSERT CPPT
            * ========================== */
            $nakes = null;
            if ($jenis == 1) {
                $nakes = $dokter->ID;
            } else {
                $nakes = DB::table('master.pegawai')->where('NIP',Auth::user()->NIP)->where('STATUS',1)->first();
            }
            if (!$nakes) {
                return response()->json([
                    'status' => false,
                    'message'=> 'Data Tenaga Medis tidak ditemukan. Silakan Hubungi Admin.'
                ], 404);
            }

            $id_cppt = DB::table('medicalrecord.cppt')->insertGetId([
                'KUNJUNGAN'    => $kunjungan,
                'TANGGAL'      => $tglPush,
                'SUBYEKTIF'    => $request->cppt_s_t,
                'OBYEKTIF'     => $request->cppt_o_t,
                'ASSESMENT'    => $request->cppt_a_t,
                'PLANNING'     => $request->cppt_p_t,
                'INSTRUKSI'    => '-',
                'JENIS'        => $jenis,
                'TENAGA_MEDIS' => $nakes,
                'OLEH'         => auth()->id(),
                'STATUS'       => 1,
            ]);

            /* ==========================
            * 2. INSERT EMR FORM PROGRAM TERAPI
            * ========================== */
            $verify = emr_form_terapi::where('nomor',$kunjungan)
                                ->where('group',$getKFR->group)
                                // ->where('jenis',$jenis)
                                ->where('status',1)
                                ->whereNull('deleted_at')
                                ->count();
            $kode = $verify + 1;
            DB::table('simrspku_klaim.emr_form_terapi')->insert([
                'id_cppt'       => $id_cppt,
                'group'         => $getKFR->group,
                'queue'         => $kode,
                'jenis'         => $jenis,
                'nomor'         => $getKFR->nomor,
                'sep'           => $request->sep,
                'tgl_sep'       => $request->tgl_sep,
                'tgl'           => $tglPush,
                'rm'            => $request->rm,

                'id_ttd_dokter' => $ttd_pegawai_dr->id,
                'ttd_dokter'    => $ttd_pegawai_dr->signature_path,
                'nip_dokter'    => $ttd_pegawai_dr->nip,
                'nama_dokter'   => $dokter->NAMADOKTER,

                'id_ttd_tim'    => $ttd_pegawai_tr->id,
                'ttd_tim'       => $ttd_pegawai_tr->signature_path,
                'nip_tim'       => $ttd_pegawai_tr->nip,
                'nama_tim'      => $tim->NAMATIM,

                'user'          => auth()->id(),
                'status'        => 1,
                'created_at'    => now(),
                'updated_at'    => now()
            ]);

            /* ==========================
            * 3. BANGUN DATA UNTUK PDF (tanpa query ulang)
            * ========================== */
            $show = (object)[
                'TGLSEP'            => $request->tgl_sep,
                'KUNJUNGAN'         => $kunjungan,
                'GROUP'             => $getKFR->group,
                'QUEUE'             => $kode,
                'TANGGAL'           => $tglPush,
                'NORM'              => $request->rm,
                'NAMAPASIEN'        => $dataPasien->NAMAPASIEN ?? '',
                'NAMADOKTER'        => $dokter->NAMADOKTER,
                'NAMATIM'           => $tim->NAMATIM,
                'TGLLAHIRPASIEN'    => $dataPasien->TGLLAHIRPASIEN ?? '',
                'UMURPASIEN'        => $dataPasien->UMURPASIEN ?? '',
                'ALAMATPASIEN'      => $dataPasien->ALAMATPASIEN ?? '',
                'SUBYEKTIF'         => $request->cppt_s_t,
                'OBYEKTIF'          => $request->cppt_o_t,
                'ASSESMENT'         => $request->cppt_a_t,
                'PROCEDURE'         => $request->cppt_p_t,
                'PATH_TTE_DOKTER'   => $ttd_pegawai_dr->signature_path,
                'PATH_TTE_TIM'      => $ttd_pegawai_tr->signature_path,
            ];

            /* 4. KIRIM LANGSUNG KE GENERATOR */
            $generateForm = $this->generateFormProgramTerapi($show);

            if (!$generateForm) {
                return response()->json([
                    'status' => false,
                    'message'=> 'Gagal generate Form Program Terapi'
                ], 500);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message'=> 'Form Program Terapi & CPPT berhasil disimpan'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message'=> $e->getMessage()
            ], 500);
        }
    }

    function updateProgramTerapi(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'rm'           => 'required|integer',
                'kunjungan'    => 'required',
                'queue'        => 'required',
                'sep'          => 'required',
                'tgl_sep'      => 'required|date',
                'tgl'          => 'required|date',
                'tgl_masuk'    => 'required',

                'cppt_s_t'     => 'required',
                'cppt_o_t'     => 'required',
                'cppt_a_t'     => 'required',
                'cppt_p_t'     => 'required',
            ],
            [
                'rm.required'        => 'Nomor RM wajib diisi.',
                'rm.integer'         => 'Nomor RM harus berupa angka.',
                'kunjungan.required' => 'Kunjungan wajib diisi.',
                'queue.required'     => 'Queue wajib terkirim.',
                'sep.required'       => 'SEP wajib diisi.',
                'tgl_sep.required'   => 'Tanggal SEP wajib diisi.',
                'tgl_sep.date'       => 'Tanggal SEP tidak valid.',
                'tgl.required'       => 'Tanggal wajib diisi.',
                'tgl.date'           => 'Tanggal tidak valid.',
                'tgl_masuk.required' => 'Tanggal Masuk Kunjungan wajib terkirim.',

                'cppt_s_t.required'  => 'Isian Subjective wajib diisi.',
                'cppt_o_t.required'  => 'Isian Objective wajib diisi.',
                'cppt_a_t.required'  => 'Isian Assessment wajib diisi.',
                'cppt_p_t.required'  => 'Isian Procedure wajib diisi.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message'=> $validator->errors()->first()
            ], 422);
        }

        $tglPush = now()->toTimeString();
        $tglMasuk  = Carbon::parse($request->tgl_masuk);
        if ($request->filled('tgl_keluar')) {
            $tglKeluar = Carbon::parse($request->tgl_keluar);

            if ($tglMasuk->isSameDay($tglKeluar)) {
                $tglPush = $tglMasuk->toDateString() . ' ' . now()->toTimeString();
            }
        } else {
            $tglPush = $tglMasuk->toDateString() . ' ' . now()->toTimeString();
        }

        // GET GROUP KUNJUNGAN
        $getKFR = emr_form_kfr::where('nomor',$request->kunjungan)->where('status',1)->whereNull('deleted_at')->first();

        if (!$getKFR) {
            return response()->json([
                'status' => false,
                'message'=> 'Form KFR Tidak ditemukan untuk kunjungan ini. Wajib mengisi Form KFR terlebih dahulu.'
            ], 404);
        }

        $dokter = DB::table('master.dokter as dr')
                    ->leftJoin('aplikasi.pengguna as pe', function($join) {
                        $join->on('pe.NIP', '=', 'dr.NIP')
                            ->where('pe.STATUS', '=', 1);
                    })
                    ->select('dr.ID', 'pe.NAMA AS DOKTER', 'dr.NIP', DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'))
                    ->where('pe.NIP', $getKFR->nip_dokter)
                    ->where('dr.STATUS', 1)
                    ->first();

        if (!$dokter) {
            return response()->json([
                'status' => false,
                'message'=> 'Data dokter tidak ditemukan untuk user ini'
            ], 404);
        }

        // GET TTD DOKTER
        $ttd_pegawai_dr = DB::table('simrspku_klaim.tanda_tangan_pegawai')
            ->where('nip', $dokter->NIP)
            ->where('status', 1)
            ->inRandomOrder()
            ->first();

        if (!$ttd_pegawai_dr) {
            return response()->json([
                'status' => false,
                'message'=> 'Data TTD dokter tidak ditemukan untuk user ini'
            ], 404);
        }

        // GET TTD TERAPIS
        $ttd_pegawai_tr = DB::table('simrspku_klaim.tanda_tangan_pegawai')
            ->where('nip', Auth::user()->NIP)
            ->where('status', 1)
            ->whereNull('deleted_at')
            ->inRandomOrder()
            ->first();

        if (!$ttd_pegawai_tr) {
            return response()->json([
                'status' => false,
                'message'=> 'Data TTD tim tidak ditemukan untuk user ini'
            ], 404);
        }

        // GET ROLE TIM (1:DR, 2:FT, 3:OT, 4:TW)
        $role = Auth::user()->getRoleNames()->first();
        $jenis = 0;
        if ($role == 'dokterspesialis') {
            $jenis = 1;
        } else if($role == 'fisioterapis') {
            $jenis = 2;
        } else if($role == 'okupasiterapi') {
            $jenis = 3;
        } else if($role == 'terapiwicara') {
            $jenis = 4;
        } else {
            return response()->json([
                'status' => false,
                'message'=> 'Hak Akses Anda tidak valid untuk mengisi Form Program Terapi'
            ], 404);
        }

        $tim = DB::table('aplikasi.pengguna as pe')
                    ->select('pe.ID', 'pe.NIP', DB::raw('master.getNamaLengkapPegawai(pe.NIP) AS NAMATIM'))
                    ->where('pe.NIP', Auth::user()->NIP)
                    ->where('pe.STATUS', 1)
                    ->first();

        if (!$tim) {
            return response()->json([
                'status' => false,
                'message'=> 'Data Akun tim tidak ditemukan'
            ], 404);
        }

        // GET DATA PASIEN
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
            return response()->json([
                'status' => false,
                'message'=> 'Data Pasien tidak ditemukan'
            ], 404);
        }

        DB::beginTransaction();
        try {
            $getFtr = emr_form_terapi::where('nomor',$request->kunjungan)
                                ->where('group',$getKFR->group)
                                ->where('queue',$request->queue)
                                ->where('status',1)
                                ->whereNull('deleted_at')
                                ->first();
            if (!$getFtr) {
                return response()->json([
                    'status' => false,
                    'message'=> 'Data Form Program Terapi tidak ditemukan untuk diupdate'
                ], 404);
            }

            /* ==========================
            * 1. UPDATE CPPT
            * ========================== */
            DB::table('medicalrecord.cppt')->where('ID', $getFtr->id_cppt)->update([
                'TANGGAL'      => $tglPush,
                'SUBYEKTIF'    => $request->cppt_s_t,
                'OBYEKTIF'     => $request->cppt_o_t,
                'ASSESMENT'    => $request->cppt_a_t,
                'PLANNING'     => $request->cppt_p_t,
                'INSTRUKSI'    => '-',
                'STATUS'       => 1,
            ]);

            /* ==========================
            * 2. UPDATE EMR FORM PROGRAM TERAPI
            * ========================== */
            DB::table('simrspku_klaim.emr_form_terapi')->where('id_cppt', $getFtr->id_cppt)->update([
                'jenis'         => $jenis,
                'sep'           => $request->sep,
                'tgl_sep'       => $request->tgl_sep,
                'tgl'           => $tglPush,

                'id_ttd_dokter' => $ttd_pegawai_dr->id,
                'ttd_dokter'    => $ttd_pegawai_dr->signature_path,
                'nip_dokter'    => $ttd_pegawai_dr->nip,
                'nama_dokter'   => $dokter->NAMADOKTER,

                'id_ttd_tim'    => $ttd_pegawai_tr->id,
                'ttd_tim'       => $ttd_pegawai_tr->signature_path,
                'nip_tim'       => $ttd_pegawai_tr->nip,
                'nama_tim'      => $tim->NAMATIM,

                'user'          => auth()->id(),
                'updated_at'    => now()
            ]);

            /* ==========================
            * 3. BANGUN DATA UNTUK PDF (tanpa query ulang)
            * ========================== */
            $show = (object)[
                'TGLSEP'            => $request->tgl_sep,
                'KUNJUNGAN'         => $request->kunjungan,
                'GROUP'             => $getKFR->group,
                'QUEUE'             => $getFtr->queue,
                'TANGGAL'           => $tglPush,
                'NORM'              => $request->rm,
                'NAMAPASIEN'        => $dataPasien->NAMAPASIEN ?? '',
                'NAMADOKTER'        => $dokter->NAMADOKTER,
                'NAMATIM'           => $tim->NAMATIM,
                'TGLLAHIRPASIEN'    => $dataPasien->TGLLAHIRPASIEN ?? '',
                'UMURPASIEN'        => $dataPasien->UMURPASIEN ?? '',
                'ALAMATPASIEN'      => $dataPasien->ALAMATPASIEN ?? '',
                'SUBYEKTIF'         => $request->cppt_s_t,
                'OBYEKTIF'          => $request->cppt_o_t,
                'ASSESMENT'         => $request->cppt_a_t,
                'PROCEDURE'         => $request->cppt_p_t,
                'PATH_TTE_DOKTER'   => $ttd_pegawai_dr->signature_path,
                'PATH_TTE_TIM'      => $ttd_pegawai_tr->signature_path,
            ];

            /* 4. KIRIM LANGSUNG KE GENERATOR */
            $generateForm = $this->generateFormProgramTerapi($show);

            if (!$generateForm) {
                return response()->json([
                    'status' => false,
                    'message'=> 'Gagal generate Form Program Terapi'
                ], 500);
            }

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Data Form Program Terapi & CPPT berhasil di-update'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message'=> $e->getMessage()
            ], 500);
        }
    }

    function generateFormProgramTerapi(object $show)
    {
        $getTgl = Carbon::parse($show->TGLSEP);
        $tgl = $getTgl->isoFormat('DD');
        $bulan = $getTgl->isoFormat('MM');
        $tahun = $getTgl->isoFormat('YYYY');

        $path = 'files/rehabmedik/formprogramterapi/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$show->KUNJUNGAN.'-'.$show->QUEUE;

        $verify = klaim_file::where('nomor',$show->KUNJUNGAN)
                            ->where('jenis',11)
                            ->where('sub_jenis',2) // FORM PROGRAM TERAPI
                            ->where('kode',$show->QUEUE)
                            ->where('ref',$show->GROUP)
                            ->where('status',true)
                            ->whereNull('deleted_at')
                            ->orderBy('id','DESC')
                            ->first();

        if (!$verify) {
            $post = new klaim_file;
            $post->jenis = 11;
            $post->sub_jenis = 2;
            $post->kode = $show->QUEUE;
            $post->ref = $show->GROUP;
            $post->nomor = $show->KUNJUNGAN;
            $post->title = $show->KUNJUNGAN.'-'.$show->QUEUE.'.pdf';
            $post->filename = $path.'.pdf';
            $post->nama_tambahan = 'Formulir Program Terapi '.$show->QUEUE;
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
            'NAMATIM' => $show->NAMATIM,
            'TGLLAHIRPASIEN' => Carbon::parse($show->TGLLAHIRPASIEN)->translatedFormat('d F Y'),
            'UMURPASIEN' => $show->UMURPASIEN,
            'ALAMATPASIEN' => $show->ALAMATPASIEN,
            'SUBYEKTIF' => $show->SUBYEKTIF,
            'OBYEKTIF' => $show->OBYEKTIF,
            'ASSESMENT' => $show->ASSESMENT,
            'PROCEDURE' => $show->PROCEDURE,
        ];

        $templateProcessor = new TemplateProcessor(public_path('/doc/input/rehabmedik/cetakNewFormProgramTerapi.docx'));

        try {
            $this->setImgWord($templateProcessor, 'PATH_TTE_DOKTER', storage_path()."/app/public/".$show->PATH_TTE_DOKTER, 170);
            // if ($cap) {
            //     $this->setImgWord($templateProcessor, 'CAP', $cap, 150);
            // }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        try {
            $this->setImgWord($templateProcessor, 'PATH_TTE_TIM', storage_path()."/app/public/".$show->PATH_TTE_TIM, 170);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        foreach ($data as $key => $value) {
            $templateProcessor->setValue($key, $value);
        }

        $outputWord = $output.'.docx';
        $templateProcessor->saveAs($outputWord);

        [$success, $log, $result] = $this->libreOffice->generatePdf($outputWord, dirname($outputWord));

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

    function generateUlangFormProgramTerapi($kunjungan,$queue) // belum dipakai
    {
        $showInit = DB::table('simrspku_klaim.emr_form_terapi')
            ->where('nomor',$kunjungan)
            ->where('queue',$queue)
            ->where('status',1)
            ->first();

        if (!$showInit) {
            return response()->json([
                'success' => false,
                'message' => 'Data Form Program Terapi tidak ditemukan'
            ], 404);
        }

        $dokter = DB::table('master.dokter as dr')
                    ->leftJoin('aplikasi.pengguna as pe', function($join) {
                        $join->on('pe.NIP', '=', 'dr.NIP')
                            ->where('pe.STATUS', '=', 1);
                    })
                    ->select('dr.ID', 'pe.NAMA AS DOKTER', 'dr.NIP', DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'))
                    ->where('pe.NIP', $showInit->nip_dokter)
                    ->where('dr.STATUS', 1)
                    ->first();

        if (!$dokter) {
            return response()->json([
                'success' => false,
                'message' => 'Data dokter tidak ditemukan untuk user ini'
            ], 404);
        }

        // GET TTD DOKTER
        $ttd_pegawai_dr = DB::table('simrspku_klaim.tanda_tangan_pegawai')
            ->where('nip', $dokter->NIP)
            ->where('status', 1)
            ->inRandomOrder()
            ->first();

        if (!$ttd_pegawai_dr) {
            return response()->json([
                'status' => false,
                'message'=> 'Data TTD dokter tidak ditemukan untuk user ini'
            ], 404);
        }

        // GET TTD TERAPIS
        $ttd_pegawai_tr = DB::table('simrspku_klaim.tanda_tangan_pegawai')
            ->where('nip', $showInit->nip_tim)
            ->where('status', 1)
            ->whereNull('deleted_at')
            ->inRandomOrder()
            ->first();

        if (!$ttd_pegawai_tr) {
            return response()->json([
                'status' => false,
                'message'=> 'Data TTD tim tidak ditemukan untuk user ini'
            ], 404);
        }

        $tim = DB::table('aplikasi.pengguna as pe')
                    ->select('pe.ID', 'pe.NIP', DB::raw('master.getNamaLengkapPegawai(pe.NIP) AS NAMATIM'))
                    ->where('pe.NIP', $showInit->nip_tim)
                    ->where('pe.STATUS', 1)
                    ->first();

        if (!$tim) {
            return response()->json([
                'status' => false,
                'message'=> 'Data Akun tim tidak ditemukan'
            ], 404);
        }

        // UPDATE TTD DOKTER & TIM
        $updated = DB::table('simrspku_klaim.emr_form_terapi')
                    ->where('nomor', $kunjungan)
                    ->where('queue', $queue)
                    ->where('status', 1)
                    ->whereNull('deleted_at')
                    ->update([
                        'id_ttd_dokter' => $ttd_pegawai_dr->id,
                        'ttd_dokter'    => $ttd_pegawai_dr->signature_path,
                        'nip_dokter'    => $ttd_pegawai_dr->nip,
                        'nama_dokter'   => $dokter->NAMADOKTER,
                        'id_ttd_tim' => $ttd_pegawai_tr->id,
                        'ttd_tim'    => $ttd_pegawai_tr->signature_path,
                        'nip_tim'    => $ttd_pegawai_tr->nip,
                        'nama_tim'   => $tim->NAMATIM,
                        'updated_at'    => now(),
                    ]);

        $show = DB::table('simrspku_klaim.emr_form_terapi as ptr')
            ->join('medicalrecord.cppt as cppt', function($join) {
                $join->on('cppt.ID','=','ptr.id_cppt')
                    ->where('cppt.STATUS', '=', 1);
            })
            // ->join('medicalrecord.cppt as cppt','cppt.ID','=','ptr.id_cppt')
            ->leftJoin('master.pasien as ps','ps.NORM','=','ptr.rm')
            ->select([
                'ptr.group as GROUP',
                'ptr.queue as QUEUE',
                'ptr.nomor as KUNJUNGAN',
                'ptr.tgl as TANGGAL',
                'ptr.tgl_sep as TGLSEP',
                'ptr.ttd_dokter as PATH_TTE_DOKTER',
                'ptr.ttd_tim as PATH_TTE_TIM',
                'ptr.rm as NORM',
                DB::raw('master.getNamaLengkap(ptr.rm) as NAMAPASIEN'),
                DB::raw('master.getAlamatPasienCustom(ptr.rm) as ALAMATPASIEN'),
                DB::raw('master.getNamaLengkapPegawai(ptr.nip_dokter) as NAMADOKTER'),
                DB::raw('master.getCariUmur(ptr.tgl, ps.TANGGAL_LAHIR) as UMURPASIEN'),
                'ps.TANGGAL_LAHIR as TGLLAHIRPASIEN',
                'cppt.SUBYEKTIF',
                'cppt.OBYEKTIF',
                'cppt.ASSESMENT',
                'cppt.PLANNING as PROCEDURE',
            ])
            ->where('ptr.nomor', $kunjungan)
            ->where('ptr.queue', $queue)
            ->where('ptr.status', 1)
            ->whereNull('ptr.deleted_at')
            ->first();

        if (!$show) {
            return response()->json([
                'success' => false,
                'message' => 'Periksa kembali data Form Program Terapi dan CPPT yang bersangkutan'
            ], 500);
        }

        // // Normalisasi line break
        // $planningText = preg_replace("/\r?\n/", "\n", $show->PLANNING);

        $success = $this->generateFormProgramTerapi($show);

        if (!$success) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate Form Program Terapi'
            ], 500);
        }

        // buat URL PDF
        $url = klaim_file::where('nomor',$show->KUNJUNGAN)
                            ->where('jenis',11)
                            ->where('sub_jenis',2) // FORM PROGRAM TERAPI
                            ->where('kode',$showInit->queue)
                            ->where('ref',$showInit->group)
                            ->where('status',true)
                            ->whereNull('deleted_at')
                            ->orderBy('id','DESC')
                            ->first();

        // $tgl = Carbon::parse($show->TGLSEP)->isoFormat('DD');
        // $bulan = Carbon::parse($show->TGLSEP)->isoFormat('MM');
        // $tahun = Carbon::parse($show->TGLSEP)->isoFormat('YYYY');

        // $relative = "files/rehabmedik/formprogramterapi/$tahun/$bulan/$tgl/{$show->KUNJUNGAN}-1.pdf";

        return response()->json([
            'success' => true,
            'message' => 'PDF berhasil dibuat',
            'pdf_url' => asset('storage/'.$url->filename)
        ]);
    }

    function lihatFormProgramTerapi($KUNJUNGAN,$GROUP,$QUEUE)
    {
        $show = DB::table('simrspku_klaim.klaim_file')
            ->where('nomor',$KUNJUNGAN)
            ->where('kode',$QUEUE)
            ->where('ref',$GROUP)
            ->where('jenis',11)
            ->where('sub_jenis',2) // FORM PROGRAM TERAPI
            ->where('status',1)
            ->whereNull('deleted_at')
            ->orderBy('id','DESC')
            ->first();

        if (!$show) {
            return response()->json([
                'status' => false,
                'message'=> 'Berkas Klaim Form Program Terapi tidak ditemukan'
            ], 404);
        }

        $path = storage_path('app/public/'.$show->filename);

        if (!file_exists($path)) {
            return response()->json([
                'status' => false,
                'message'=> 'File Berkas Klaim Form Program Terapi tidak ditemukan di server'
            ], 404);
        }

        return response()->file($path, [
            'Content-Type'  => 'application/pdf',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma'        => 'no-cache',
            'Expires'       => '0',
        ]);

        // return response()->file($output, [
        //     'Content-Type' => 'application/pdf',
        // ]);
    }

    function destroyProgram(Request $request)
    {
        $request->validate([
            'kunjungan' => 'required',
            'queue' => 'required',
        ]);

        DB::beginTransaction();
        try {

            // cek data
            $form = DB::table('simrspku_klaim.emr_form_terapi')
                ->where('nomor', $request->kunjungan)
                ->where('queue', $request->queue)
                ->where('status', 1)
                ->whereNull('deleted_at')
                ->first();

            if (!$form) {
                return response()->json([
                    'status' => false,
                    'message'=> 'Data Form Program Terapi tidak ditemukan'
                ], 404);
            }

            $now = now();

            /* ==========================
            * 1. Hapus file klaim_file FORM PROGRAM TERAPI & Reset Status to 0
            * ========================== */
            DB::table('simrspku_klaim.klaim_file')
                ->where('nomor', $form->nomor)
                ->where('sub_jenis', 2) // FORM PROGRAM TERAPI
                ->where('kode', $form->queue)
                ->where('ref', $form->group)
                ->where('status', 1)
                ->whereNull('deleted_at')
                ->update([
                    'user_deleted'      => auth()->id(),
                    'status'            => 0,
                    'deleted_at'        => $now
                ]);

            /* ==========================
            * 2. Soft Delete EMR FORM PROGRAM TERAPI & Reset Status to 0
            * ========================== */
            DB::table('simrspku_klaim.emr_form_terapi')
                ->where('nomor', $form->nomor)
                ->where('group', $form->group)
                ->where('queue', $form->queue)
                ->where('status', 1)
                ->whereNull('deleted_at')
                ->update([
                    'user'       => auth()->id(),
                    'status'     => 0,
                    'deleted_at' => $now
                ]);

            /* ==========================
            * 3. Delete CPPT & Reset Status to 0
            * ========================== */
            DB::table('medicalrecord.cppt')
                ->where('ID', $form->id_cppt)
                ->where('STATUS', 1)
                ->update([
                    'STATUS'     => 0
                ]);

            // $output = storage_path('app/public/'.$klaim_file->filename);
            // if (file_exists($output)) {
            //     File::delete($output);
            // }

            DB::commit();

            return response()->json([
                'status' => true,
                'message'=> 'Form Program Terapi & CPPT berhasil terhapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message'=> $e->getMessage()
            ], 500);
        }
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
