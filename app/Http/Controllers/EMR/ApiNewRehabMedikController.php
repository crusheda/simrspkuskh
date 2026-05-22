<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\simrspku_klaim\emr_form_kfr;
use App\Models\simrspku_klaim\emr_form_terapi;
use App\Models\simrspku_klaim\emr_form_jadwal;
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

    public function escapeDocx(?string $text): string
    {
        if (!$text) return '';
        // ENT_XML1 untuk escape karakter XML Contoh (&, <, >, dll)
        return htmlspecialchars($text, ENT_XML1, 'UTF-8');
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
            // JOIN EMR_FORM_TERAPI
            ->leftJoin('simrspku_klaim.emr_form_terapi as eft', function($join){
                $join->on('eft.id_cppt','=','cppt.ID')
                    ->whereNull('eft.deleted_at')
                    ->where('eft.status',1);
            })
            // JOIN EMR_FORM_KFR
            ->leftJoin('simrspku_klaim.emr_form_kfr as ekf', function($join){
                $join->on('ekf.id_cppt','=','cppt.ID')
                    ->whereNull('ekf.deleted_at')
                    ->where('ekf.status',1);
            })
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
                'cppt.JENIS AS JENIS_CPPT',
                'cppt.KUNJUNGAN',
                'cppt.TANGGAL',
                'cppt.SUBYEKTIF',
                'cppt.OBYEKTIF',
                'cppt.ASSESMENT',
                'cppt.PLANNING',
                'cppt.INSTRUKSI',
                DB::raw('IF(eft.id IS NULL, 0, 1) as IS_TERAPI'),
                DB::raw('IF(ekf.id IS NULL, 0, 1) as IS_KFR'),
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

        $dokter = DB::table('master.dokter as dr')
                    ->leftJoin('aplikasi.pengguna as pe', function($join) {
                        $join->on('pe.NIP', '=', 'dr.NIP')
                            ->where('pe.STATUS', '=', 1);
                    })
                    ->select('dr.ID', 'pe.NAMA AS DOKTER', 'dr.NIP', DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'))
                    ->where('pe.NIP', $formLama->nip_dokter)
                    // ->where('pe.ID', auth()->id())
                    // ->where('dr.NIP', Auth::user()->NIP)
                    ->where('dr.STATUS', 1)
                    ->first();

        if (!$dokter) {
            return response()->json([
                'status' => false,
                'message'=> 'Data dokter tidak ditemukan untuk user ini'
            ], 404);
        }

        $ttd_pegawai = DB::table('simrspku_klaim.tanda_tangan_pegawai as ttp')
            // ->where('ttp.nip', $formLama->nip_dokter)
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
                // 'TENAGA_MEDIS' => $cpptLama->TENAGA_MEDIS,
                'TENAGA_MEDIS' => $dokter->ID,
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
                ->orderByDesc('id')
                ->first();

            if (!$form) {
                return response()->json([
                    'status' => false,
                    'message'=> 'Form KFR tidak ditemukan atau telah terhapus oleh Sistem'
                ], 500);
            }

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

            $getFile = DB::table('simrspku_klaim.klaim_file')
                ->where('nomor', $request->nomor_kunjungan)
                ->where('sub_jenis', 1)
                ->whereNull('kode')
                ->where('status', 1)
                ->whereNull('deleted_at')
                ->get();

            foreach ($getFile as $file) {
                $path = storage_path('app/public/' . $file->filename);

                if (file_exists($path)) {
                    unlink($path);
                }
            }

            DB::table('simrspku_klaim.klaim_file')
                ->where('nomor', $request->nomor_kunjungan)
                ->where('sub_jenis', 1)
                ->whereNull('kode')
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
            $lastGroup = DB::table('simrspku_klaim.emr_form_kfr')
                            // ->where('nomor_init', $kunjungan)
                            ->where('rm', $request->rm)
                            ->where('status', 1)
                            ->whereNull('deleted_at')
                            ->orderBy('group', 'DESC')
                            ->first();
            $newGroup = $lastGroup ? $lastGroup->group + 1 : 1;
            DB::table('simrspku_klaim.emr_form_kfr')->insert([
                'id_cppt'       => $id_cppt,
                'group'         => $newGroup,
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
            $templateProcessor->setValue($key, $this->escapeDocx($value));
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

        if (!$ttd_pegawai) {
            throw new \Exception('Data TTD dokter tidak ditemukan untuk user ini');
        }

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
                    'TENAGA_MEDIS' => $dokter->ID,
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

    function ubah($KUNJUNGAN, $NORM, $KODE) // PENYESUAIAN GROUP & PERIODE KUNJUNGAN
    {
        // 1 = Buat Group Baru
        // 0 = Lanjut GROUP Sebelumnya

        DB::beginTransaction();
        try {
            /* ==========================
            * 1. UPDATE EMR FORM KFR & KLAIM FILE
            * ========================== */
            $getDataFirst = DB::table('simrspku_klaim.emr_form_kfr')->where('rm', $NORM)->where('status', 1)->whereNull('deleted_at')->orderByDesc('group')->first();
            if (!$getDataFirst) {
                return response()->json([
                    'status' => false,
                    'message'=> 'Data Kunjungan pada No. RM ini tidak ditemukan. Mohon periksa semua kunjungan dari pasien ini'
                ], 422);
            }

            $getKunj = DB::table('simrspku_klaim.emr_form_kfr')->where('nomor', $KUNJUNGAN)->where('status', 1)->whereNull('deleted_at')->first();
            if (!$getKunj) {
                return response()->json([
                    'status' => false,
                    'message'=> 'Data Kunjungan saat ini tidak ditemukan / telah terhapus. mohon refresh data riwayat sekali lagi'
                ], 422);
            }

            if ($KODE == 1) { // Buat Group Baru
                // EMR FORM KFR
                DB::table('simrspku_klaim.emr_form_kfr')
                    ->where('rm', $NORM)
                    ->where('nomor', $KUNJUNGAN)
                    ->where('status', 1)
                    ->whereNull('deleted_at')
                    ->update([
                        'group'             => $getDataFirst->group + 1,
                        'queue'             => 1, // NEW GROUP = NEW QUEUE
                        'nomor_init'        => $KUNJUNGAN,
                        'tgl_init'          => $getKunj->tgl_sep,
                        'tgl'               => $getKunj->tgl_sep,
                        'bertemu_dokter'    => 1,
                        // 'user'              => auth()->id(),
                        'updated_at'        => now(),
                    ]);

                // EMR FORM TERAPI
                DB::table('simrspku_klaim.emr_form_terapi')
                    ->where('rm', $NORM)
                    ->where('nomor', $KUNJUNGAN)
                    ->where('status', 1)
                    ->whereNull('deleted_at')
                    ->update([
                        'group'             => $getDataFirst->group + 1,
                        'tgl'               => $getKunj->tgl_sep,
                        // 'user'              => auth()->id(),
                        'updated_at'        => now(),
                    ]);

                // KLAIM FILE FORM KFR & FORM TERAPI
                DB::table('simrspku_klaim.klaim_file')
                    ->where('jenis', 11)
                    ->whereIn('sub_jenis', [1, 2]) // KODE FORM
                    ->where('nomor', $KUNJUNGAN)
                    ->where('status', 1)
                    ->whereNull('deleted_at')
                    ->update([
                        'ref'           => $getDataFirst->group + 1,
                        'user'          => auth()->id(),
                        'updated_at'    => now(),
                    ]);

                // KLAIM FILE FORM JADWAL PELAYANAN
                // DB::table('simrspku_klaim.klaim_file')
                //     ->where('jenis', 11)
                //     ->where('sub_jenis', 3)
                //     ->where('nomor', $KUNJUNGAN)
                //     ->where('status', 1)
                //     ->whereNull('deleted_at')
                //     ->update([
                //         'ref'           => $getDataFirst->group + 1,
                //         'kode'          => 1, // NEW GROUP = NEW QUEUE
                //         'user'          => auth()->id(),
                //         'updated_at'    => now(),
                //     ]);

            } else {
                if ($KODE == 0) { // Lanjut GROUP Sebelumnya
                    $gOld = $getKunj->group - 1;
                    if ($gOld != 0) {
                        $getKunjGroupOld = DB::table('simrspku_klaim.emr_form_kfr')
                                                ->where('rm',$NORM)
                                                ->where('group', $gOld)
                                                ->where('status', 1)
                                                ->whereNull('deleted_at')
                                                ->orderByDesc('queue')
                                                ->orderByDesc('id')
                                                ->first();

                        if (!$getKunjGroupOld) {
                            return response()->json([
                                'status' => false,
                                'message'=> 'Data Form Kunjungan KFR Lama pasien ini tidak ditemukan atau telah terhapus. mohon periksa riwayat Form KFR pada RM Pasien ini'
                            ], 422);
                        }

                        DB::table('simrspku_klaim.emr_form_kfr')
                            ->where('rm', $NORM)
                            ->where('nomor', $KUNJUNGAN)
                            ->where('status', 1)
                            ->whereNull('deleted_at')
                            ->update([
                                'group'             => $getKunjGroupOld->group,
                                'queue'             => $getKunjGroupOld->queue + 1,
                                'nomor_init'        => $getKunjGroupOld->nomor_init,
                                'tgl_init'          => $getKunjGroupOld->tgl_init,
                                'tgl'               => $getKunjGroupOld->tgl,
                                'bertemu_dokter'    => 1,
                                // 'user'              => auth()->id(),
                                'updated_at'        => now(),
                            ]);

                        // EMR FORM TERAPI
                        DB::table('simrspku_klaim.emr_form_terapi')
                            ->where('rm', $NORM)
                            ->where('nomor', $KUNJUNGAN)
                            ->where('status', 1)
                            ->whereNull('deleted_at')
                            ->update([
                                'group'             => $getKunjGroupOld->group,
                                'tgl'               => $getKunjGroupOld->tgl,
                                // 'user'              => auth()->id(),
                                'updated_at'        => now(),
                            ]);

                        // KLAIM FILE FORM KFR & FORM TERAPI
                        DB::table('simrspku_klaim.klaim_file')
                            ->where('jenis', 11)
                            ->whereIn('sub_jenis', [1, 2]) // KODE FORM
                            ->where('nomor', $KUNJUNGAN)
                            ->where('status', 1)
                            ->whereNull('deleted_at')
                            ->update([
                                'ref'           => $getKunjGroupOld->group,
                                'user'          => auth()->id(),
                                'updated_at'    => now(),
                            ]);
                    } else {
                        return response()->json([
                            'status' => false,
                            'message'=> 'Data Kunjungan saat ini adalah kunjungan pertama pasien yang tercatat di SIRMED alias GROUP pertama'
                        ], 422);
                    }
                } else {
                    return response()->json([
                        'status' => false,
                        'message'=> 'KODE SISTEM tidak terkirim saat menjalankan proses perubahan Group'
                    ], 422);
                }
            }

            DB::commit();

            /* 3. KIRIM LANGSUNG KE GENERATOR */
            $generateUlangForm = $this->generateUlangFormKfr($KUNJUNGAN);

            if (!$generateUlangForm['success']) {
                return response()->json([
                    'status' => false,
                    'message'=> $generateUlangForm['message'] ?? 'Gagal memperbarui Group Form KFR'
                ], 422);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Data GROUP & Periode Kunjungan berhasil diperbarui'
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
            $getFile = DB::table('simrspku_klaim.klaim_file')
                ->where('nomor', $form->nomor)
                ->where('sub_jenis', 1)
                ->whereNull('kode')
                ->where('status', 1)
                ->whereNull('deleted_at')
                ->get();

            foreach ($getFile as $file) {
                $path = storage_path('app/public/' . $file->filename);

                if (file_exists($path)) {
                    unlink($path);
                }
            }

            DB::table('simrspku_klaim.klaim_file')
                ->where('nomor', $form->nomor)
                ->where('sub_jenis', 1)
                ->whereNull('kode')
                ->where('status', 1)
                ->whereNull('deleted_at')
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

        $formKfr = emr_form_kfr::where('nomor', $KUNJUNGAN)
            ->where('status', 1)
            ->whereNull('deleted_at')
            ->first();

        $data = DB::table('medicalrecord.cppt AS cppt')
            // JOIN EMR_FORM_TERAPI
            ->leftJoin('simrspku_klaim.emr_form_terapi as eft', function($join){
                $join->on('eft.id_cppt','=','cppt.ID')
                    ->whereNull('eft.deleted_at')
                    ->where('eft.status',1);
            })
            // JOIN EMR_FORM_KFR
            ->leftJoin('simrspku_klaim.emr_form_kfr as ekf', function($join){
                $join->on('ekf.id_cppt','=','cppt.ID')
                    ->whereNull('ekf.deleted_at')
                    ->where('ekf.status',1);
            })
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
                'cppt.JENIS AS JENIS_CPPT',
                'cppt.TANGGAL',
                'cppt.SUBYEKTIF',
                'cppt.OBYEKTIF',
                'cppt.ASSESMENT',
                'cppt.PLANNING',
                'cppt.INSTRUKSI',
                DB::raw('IF(eft.id IS NULL, 0, 1) as IS_TERAPI'),
                DB::raw('IF(ekf.id IS NULL, 0, 1) as IS_KFR'),
                // DB::raw('IF(eft.id IS NULL, 0, 1) as IS_SIRMED'),
                DB::raw('master.getNamaLengkapPegawai(pg.NIP) AS NAMADOKTER'),
                DB::raw('master.getNamaLengkapPegawai(dpjp.NIP) AS NAMADPJP'),
                DB::raw('master.getNamaLengkapPegawai(pe.NIP) AS NAMAUSER'),
            )
            ->orderBy('cppt.TANGGAL', 'DESC')

            // urutkan supaya CPPT yg sudah KFR / Terapi muncul dulu
            ->orderByRaw('(IF(ekf.id IS NULL,0,1)+IF(eft.id IS NULL,0,1)) DESC')
            ->orderBy('cppt.TANGGAL','DESC')

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
            ->where('ftr.nomor', $KUNJUNGAN)
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
                $nakes = $nakes->ID;
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
                                ->latest('queue')
                                ->first();
            $kode = $verify ? $verify->queue + 1 : 1;
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

        // ADMIN OR ELSE
        $isAdmin = Auth::user()->hasRole(['admin']);

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
        // $ttd_pegawai_tr = DB::table('simrspku_klaim.tanda_tangan_pegawai')
        //     ->where('nip', Auth::user()->NIP)
        //     ->where('status', 1)
        //     ->whereNull('deleted_at')
        //     ->inRandomOrder()
        //     ->first();

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
            if ($isAdmin) {
                $jenis = 99;
            } else {
                return response()->json([
                    'status' => false,
                    'message'=> 'Hak Akses Anda tidak valid untuk mengisi Form Program Terapi'
                ], 404);
            }

        }

        // $tim = DB::table('aplikasi.pengguna as pe')
        //             ->select('pe.ID', 'pe.NIP', DB::raw('master.getNamaLengkapPegawai(pe.NIP) AS NAMATIM'))
        //             ->where('pe.NIP', Auth::user()->NIP)
        //             ->where('pe.STATUS', 1)
        //             ->first();

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

            $nipTim = $isAdmin
                ? $getFtr->nip_tim
                : Auth::user()->NIP;

            // GET TTD TERAPIS
            $ttd_pegawai_tr = DB::table('simrspku_klaim.tanda_tangan_pegawai')
                ->where('nip', $nipTim)
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

            // GET TTD TIM
            $tim = DB::table('aplikasi.pengguna as pe')
                ->select(
                    'pe.ID',
                    'pe.NIP',
                    DB::raw('master.getNamaLengkapPegawai(pe.NIP) AS NAMATIM')
                )
                ->where('pe.NIP', $nipTim)
                ->where('pe.STATUS', 1)
                ->first();

            if (!$tim) {
                return response()->json([
                    'status' => false,
                    'message'=> 'Data Akun tim tidak ditemukan'
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
            $jenisAdm = $jenis == 99 ? $getFtr->jenis : $jenis;
            $userAdm = $jenis == 99 ? $getFtr->user : auth()->id();
            DB::table('simrspku_klaim.emr_form_terapi')->where('id_cppt', $getFtr->id_cppt)->update([
                'jenis'         => $jenisAdm,
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

                'user'          => $userAdm,
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
            $pathDokter = Storage::disk('public')->path($show->PATH_TTE_DOKTER);

            $this->setImgWord(
                $templateProcessor,
                'PATH_TTE_DOKTER',
                $pathDokter,
                150
            );

            // $this->setImgWord($templateProcessor, 'PATH_TTE_DOKTER', storage_path()."/app/public/".$show->PATH_TTE_DOKTER, 170);
            // if ($cap) {
            //     $this->setImgWord($templateProcessor, 'CAP', $cap, 150);
            // }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        try {
            $pathTim = Storage::disk('public')->path($show->PATH_TTE_TIM);

            $this->setImgWord(
                $templateProcessor,
                'PATH_TTE_TIM',
                $pathTim,
                150
            );

            // $this->setImgWord($templateProcessor, 'PATH_TTE_TIM', storage_path()."/app/public/".$show->PATH_TTE_TIM, 170);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        foreach ($data as $key => $value) {
            $templateProcessor->setValue($key, $this->escapeDocx($value));
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
                'message'=> 'Berkas Klaim Form Program Terapi tidak ditemukan atau terhapus dari server.'
            ], 404);
        }

        $path = storage_path('app/public/'.$show->filename);

        if (!file_exists($path)) {
            // GET FORM FIRST
            $form = DB::table('simrspku_klaim.emr_form_terapi')
                ->where('nomor',$KUNJUNGAN)
                ->where('queue',$QUEUE)
                ->where('group',$GROUP)
                ->where('status',1)
                ->whereNull('deleted_at')
                ->orderBy('id','DESC')
                ->first();

            if (!$form) {
                return response()->json([
                    'status' => false,
                    'message'=> 'Berkas Form Program Terapi tidak ditemukan atau terhapus.'
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
                        ->where('NORM',$form->rm)
                        ->where('STATUS', true)
                        ->first();

            // GET DOKTER
            $dokter = DB::table('master.dokter as dr')
                        ->leftJoin('aplikasi.pengguna as pe', function($join) {
                            $join->on('pe.NIP', '=', 'dr.NIP')
                                ->where('pe.STATUS', '=', 1);
                        })
                        ->select('dr.ID', 'pe.NAMA AS DOKTER', 'dr.NIP', DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'))
                        ->where('pe.NIP', $form->nip_dokter)
                        ->first();

            // GET TIM
            $tim = DB::table('aplikasi.pengguna as pe')
                        ->select('pe.ID', 'pe.NIP', DB::raw('master.getNamaLengkapPegawai(pe.NIP) AS NAMATIM'))
                        ->where('pe.NIP', $form->nip_tim)
                        ->first();

            // GET TTD DOKTER
            // $ttd_pegawai_dr = DB::table('simrspku_klaim.tanda_tangan_pegawai')
            //     ->where('nip', $dokter->NIP)
            //     ->where('status', 1)
            //     ->inRandomOrder()
            //     ->first();

            // GET TTD TERAPIS
            // $ttd_pegawai_tr = DB::table('simrspku_klaim.tanda_tangan_pegawai')
            //     ->where('nip', Auth::user()->NIP)
            //     ->where('status', 1)
            //     ->whereNull('deleted_at')
            //     ->inRandomOrder()
            //     ->first();

            // GET CPPT
            $cppt = DB::table('medicalrecord.cppt')->where('ID', $form->id_cppt)->first();

            $show = (object)[
                'TGLSEP'            => $form->tgl_sep,
                'KUNJUNGAN'         => $form->nomor,
                'GROUP'             => $form->group,
                'QUEUE'             => $form->queue,
                'TANGGAL'           => $form->tgl,
                'NORM'              => $form->rm,
                'NAMAPASIEN'        => $dataPasien->NAMAPASIEN ?? '',
                'NAMADOKTER'        => $dokter->NAMADOKTER,
                'NAMATIM'           => $tim->NAMATIM,
                'TGLLAHIRPASIEN'    => $dataPasien->TGLLAHIRPASIEN ?? '',
                'UMURPASIEN'        => $dataPasien->UMURPASIEN ?? '',
                'ALAMATPASIEN'      => $dataPasien->ALAMATPASIEN ?? '',
                'SUBYEKTIF'         => $cppt->SUBYEKTIF,
                'OBYEKTIF'          => $cppt->OBYEKTIF,
                'ASSESMENT'         => $cppt->ASSESMENT,
                'PROCEDURE'         => $cppt->PLANNING,
                'PATH_TTE_DOKTER'   => $form->ttd_dokter,
                'PATH_TTE_TIM'      => $form->ttd_tim,
            ];

            /* 4. KIRIM LANGSUNG KE GENERATOR */
            $generateForm = $this->generateFormProgramTerapi($show);

            // return response()->json([
            //     'status' => false,
            //     'message'=> 'File Berkas Klaim Form Program Terapi tidak ditemukan di server'
            // ], 404);
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
            $files = DB::table('simrspku_klaim.klaim_file')
                ->where('nomor', $form->nomor)
                ->where('sub_jenis', 2)
                ->where('kode', $form->queue)
                ->where('ref', $form->group)
                ->where('status', 1)
                ->whereNull('deleted_at')
                ->get();

            foreach ($files as $file) {

                $path = storage_path('app/public/' . $file->filename);

                if (file_exists($path)) {
                    unlink($path); // hapus file fisik
                }
            }

            DB::table('simrspku_klaim.klaim_file')
                ->where('nomor', $form->nomor)
                ->where('sub_jenis', 2)
                ->where('kode', $form->queue)
                ->where('ref', $form->group)
                ->where('status', 1)
                ->whereNull('deleted_at')
                ->update([
                    'user_deleted' => auth()->id(),
                    'status'       => 0,
                    'deleted_at'   => $now
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
    // ===================================================  FORMULIR PROGRAM TERAPI  ======================================================
    // ====================================================================================================================================
    function getFormJadwalPelayanan($KUNJUNGAN)
    {
        $data = DB::table('simrspku_klaim.emr_form_jadwal as efj')
                    ->leftJoin('aplikasi.pengguna as pe', function ($join) {
                        $join->on('pe.ID', '=', 'efj.user')
                            ->where('pe.STATUS', 1);
                    })
                    ->select(
                        'efj.*',
                        DB::raw('master.getNamaLengkapPegawai(pe.NIP) AS NAMAUSER')
                    )
                    ->where('efj.nomor', $KUNJUNGAN)
                    ->where('efj.status', 1)
                    ->whereNull('efj.deleted_at')
                    ->latest('efj.id')
                    ->first();

        $terapi = emr_form_terapi::where('nomor', $KUNJUNGAN)
                ->where('status', 1)
                ->whereNull('deleted_at')
                ->orderBy('id', 'DESC')
                ->get();

        if ($terapi->isEmpty()) {
            // jika tidak ada terapi sama sekali, maka jadwal pelayanan dianggap belum diisi (0),
            // jika ada minimal 1 terapi, maka jadwal pelayanan dianggap siap untuk diisi (1)
            $valt = 0;
        } else {
            $valt = 1;
        }

        return response()->json([
            'status' => true,
            'data' => $data,
            'terapi' => $valt
        ]);
    }

    public function previewFormJadwalPelayanan($KUNJUNGAN)
    {
        $data = emr_form_jadwal::leftJoin('simrspku_klaim.klaim_file as kf', function($join) {
                $join->on('kf.nomor', '=', 'emr_form_jadwal.nomor')
                    ->where('kf.jenis', 11)
                    ->where('kf.sub_jenis', 3)
                    ->whereColumn('kf.kode', 'emr_form_jadwal.queue')
                    ->whereColumn('kf.ref', 'emr_form_jadwal.group')
                    ->where('kf.status', 1)
                    ->whereNull('kf.deleted_at');
            })
            ->select('emr_form_jadwal.*', 'kf.filename')
            ->where('emr_form_jadwal.nomor', $KUNJUNGAN)
            ->where('emr_form_jadwal.status', 1)
            ->whereNull('emr_form_jadwal.deleted_at')
            ->orderBy('emr_form_jadwal.id', 'DESC')
            ->first();

        if (!$data || !$data->filename) {
            return response()->json([
                'status' => false,
                'message'=> 'File tidak ditemukan'
            ], 404);
        }

        $filePath = storage_path('app/public/' . $data->filename);

        if (!file_exists($filePath)) {
            return response()->json([
                'status' => false,
                'message'=> 'File PDF tidak ditemukan di server'
            ], 404);
        }

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma'        => 'no-cache',
            'Expires'       => '0',
        ]);
    }

    public function storeFormJadwalPelayanan(Request $request)
    {
        DB::beginTransaction();

        try {
            // ==========================
            // VALIDASI BASIC
            // ==========================
            if (!$request->kunjungan || !$request->rm || !$request->image) {
                return response()->json([
                    'status' => false,
                    'message'=> 'Parameter tidak lengkap'
                ], 422);
            }

            $kunjungan = $request->kunjungan;

            // ==========================
            // GET DATA PASIEN
            // ==========================
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
                throw new \Exception('Data Pasien tidak ditemukan');
            }

            // ==========================
            // GET FORM TERAPI
            // ==========================
            $formTerapi = DB::table('simrspku_klaim.emr_form_terapi as terapi')
                ->leftJoin('medicalrecord.cppt', function($join) {
                    $join->on('cppt.ID', '=', 'terapi.id_cppt')
                        ->where('cppt.STATUS', '=', 1);
                })
                ->select(
                    'terapi.*',
                    'cppt.PLANNING as program_terapi',
                    DB::raw("(
                        SELECT signature_path
                        FROM simrspku_klaim.tanda_tangan_pegawai ttp
                        WHERE ttp.nip = terapi.nip_tim
                        AND ttp.status = 1
                        AND ttp.deleted_at IS NULL
                        ORDER BY RAND()
                        LIMIT 1
                    ) as ttd_terapis")
                )
                ->where('terapi.nomor', $kunjungan)
                ->where('terapi.status', 1)
                ->whereNull('terapi.deleted_at')
                ->orderBy('terapi.queue', 'ASC')
                ->get();

            if ($formTerapi->isEmpty()) {
                throw new \Exception('Form Program Terapi belum diisi');
            }

            // ==========================
            // VALIDASI TTD TERAPIS
            // ==========================
            if ($formTerapi->contains(fn($x) => empty($x->ttd_terapis))) {
                throw new \Exception('TTD Terapis belum lengkap');
            }

            // ==========================
            // GET FORM KFR
            // ==========================
            $formKfr = DB::table('simrspku_klaim.emr_form_kfr as kfr')
                ->leftJoin('medicalrecord.cppt', function($join) {
                    $join->on('cppt.ID', '=', 'kfr.id_cppt')
                        ->where('cppt.STATUS', '=', 1);
                })
                // ->select('kfr.*', 'cppt.ASSESMENT AS diag_medis','cppt.PLANNING AS permintaan_terapi')
                ->select('kfr.*', 'cppt.ASSESMENT AS diag_medis','cppt.PLANNING')
                ->where('kfr.nomor', $kunjungan)
                ->where('kfr.status', 1)
                ->whereNull('deleted_at')
                ->first();

            if (!$formKfr) {
                throw new \Exception('Form KFR belum diisi');
            }

            $doubleCheck = DB::table('simrspku_klaim.emr_form_jadwal')
                            ->where('nomor', $kunjungan)
                            // ->where('queue', $formKfr->queue)
                            // ->where('group', $formKfr->group)
                            ->where('status', 1)
                            ->whereNull('deleted_at')
                            ->first();
            // $doubleCheck = klaim_file::where('nomor',$show->KUNJUNGAN)
            //                 ->where('jenis',11)
            //                 ->where('sub_jenis',3) // FORM JADWAL PELAYANAN TERAPI
            //                 ->where('kode',$formKfr->queue)
            //                 ->where('ref',$formKfr->group)
            //                 ->where('status',true)
            //                 ->whereNull('deleted_at')
            //                 ->orderBy('id','DESC')
            //                 ->first();

            if ($doubleCheck) {
                throw new \Exception('Form Jadwal Pelayanan untuk kunjungan ini sudah pernah dibuat sebelumnya. Silakan menggunakan Form Jadwal Pelayanan dengan data yang sudah ada sebelumnya atau dapat mengenerate ulang form jadwal pelayanan baru.');
            }

            $planningText = preg_replace("/\r?\n/", "\n", $formKfr->PLANNING ?? '');

            $permintaanTerapi = $this->getSection(
                $planningText,
                'Tindakan/Program Rehabilitasi Medik'
            );

            if (empty($permintaanTerapi)) {
                $permintaanTerapi = $formKfr->PLANNING; // fallback full text
            }

            // ==========================
            // SIMPAN TTD PASIEN
            // ==========================
            $image = str_replace('data:image/png;base64,', '', $request->image);
            $image = str_replace(' ', '+', $image);

            $fileName = 'ttd_'.$request->rm.'_'.time().'.png';
            $path = 'signatures/pasien/'.$fileName;

            Storage::disk('public')->put($path, base64_decode($image));

            $id_ttd = DB::table('simrspku_klaim.tanda_tangan')->insertGetId([
                'kunjungan'     => $kunjungan,
                'signature_path'=> $path,
                'user'          => auth()->id(),
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            // ==========================
            // GET TTD DOKTER
            // ==========================
            $ttd_dokter = DB::table('simrspku_klaim.tanda_tangan_pegawai')
                ->where('nip', $formKfr->nip_dokter)
                ->where('status', 1)
                ->whereNull('deleted_at')
                ->inRandomOrder()
                ->first();

            if (!$ttd_dokter) {
                throw new \Exception('TTD dokter tidak ditemukan');
            }

            // ==========================
            // INSERT EMR JADWAL
            // ==========================
            DB::table('simrspku_klaim.emr_form_jadwal')->insert([
                'group' => $formKfr->group,
                'queue' => $formKfr->queue,
                'nomor' => $kunjungan,
                'tgl'   => $request->tgl,
                'sep'   => $request->sep,
                'tgl_sep'=> $request->tgl_sep ?? $request->tgl,
                'rm'    => $request->rm,

                'diag_medis'        => $formKfr->diag_medis,
                'permintaan_terapi' => $permintaanTerapi,

                'program_terapis1' => $formTerapi[0]->program_terapi ?? '',
                'program_terapis2' => $formTerapi[1]->program_terapi ?? '',

                'id_ttd_pasien' => $id_ttd,
                'ttd_pasien'    => $path,

                'nip_dokter'   => $formKfr->nip_dokter,
                'nip_terapis1' => $formTerapi[0]->nip_tim ?? '',
                'nip_terapis2' => $formTerapi[1]->nip_tim ?? '',
                'nip_dpjp'     => $formKfr->nip_dokter,
                'ttd_dokter'   => $ttd_dokter->signature_path,
                'ttd_terapis1' => $formTerapi[0]->ttd_terapis ?? '',
                'ttd_terapis2' => $formTerapi[1]->ttd_terapis ?? '',
                'ttd_dpjp'     => $ttd_dokter->signature_path,
                'nama_dpjp'    => $formKfr->nama_dokter,

                'user' => auth()->id(),
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // ==========================
            // GENERATE PDF
            // ==========================
            $data = (object)[
                'KUNJUNGAN' => $kunjungan,
                'GROUP'     => $formKfr->group,
                'QUEUE'     => $formKfr->queue,
                'TGLKUNJUNGAN'  => $request->tgl,
                'TGLSURAT'  => $request->tgl_sep ?? $request->tgl,
                'TGLSEP'    => $request->tgl_sep ?? $request->tgl,

                'DIAG_MEDIS' => $formKfr->diag_medis ?? '',
                'PERMINTAAN_TERAPI' => $permintaanTerapi ?? '',
                'PROGRAM1' => $formTerapi[0]->program_terapi ?? '',
                'PROGRAM2' => $formTerapi[1]->program_terapi ?? '',

                'RM'        => $request->rm,
                'NAMAPASIEN' => $dataPasien->NAMAPASIEN ?? '',
                'TGLLAHIRPASIEN' => $dataPasien->TGLLAHIRPASIEN ?? '',
                'UMURPASIEN' => $dataPasien->UMURPASIEN ?? '',
                'ALAMATPASIEN' => $dataPasien->ALAMATPASIEN ?? '',

                'PATH_TTD_PASIEN'  => $path,
                'PATH_TTD_DOKTER'  => $ttd_dokter->signature_path ?? '',
                'PATH_TTD_TERAPIS1'     => $formTerapi[0]->ttd_terapis ?? '',
                'PATH_TTD_TERAPIS2'     => $formTerapi[1]->ttd_terapis ?? null,
                'PATH_TTD_DOKTER_PEMERIKSA' => $ttd_dokter->signature_path ?? '',
                'DOKTER_PEMERIKSA' => $formKfr->nama_dokter,
            ];

            // ==========================
            // GENERATE PDF
            // ==========================
            DB::afterCommit(function () use ($data) {
                try {
                    $this->generateFormJadwalPelayanan($data);
                } catch (\Exception $e) {
                    \Log::error('Generate PDF gagal: '.$e->getMessage());
                }
            });
            // $result = $this->generateFormJadwalPelayanan($data);

            // if (!$result) {
            //     throw new \Exception('Generate PDF gagal');
            // }

            // ==========================
            DB::commit();

            return response()->json([
                'status' => true,
                'path'   => $path
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message'=> $e->getMessage()
            ], 500);
        }
    }

    public function generateFormJadwalPelayanan(object $show)
    {
        $templateProcessor = new TemplateProcessor(
            public_path('/doc/input/rehabmedik/cetakFormJadwalPelayanan.docx')
        );

        $getTgl = Carbon::parse($show->TGLSEP);
        $tgl = $getTgl->isoFormat('DD');
        $bulan = $getTgl->isoFormat('MM');
        $tahun = $getTgl->isoFormat('YYYY');

        $path = 'files/rehabmedik/formjadwalpelayanan/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$show->KUNJUNGAN.'-'.$show->QUEUE;
        // ==========================
        // AMBIL SEMUA DATA JADWAL
        // ==========================
        $jadwalList = DB::table('simrspku_klaim.emr_form_jadwal')
            // ->where('nomor', $show->KUNJUNGAN)
            ->where('rm', $show->RM)
            ->where('group', $show->GROUP)
            // ->where('queue', '<=', $show->QUEUE)
            ->where('tgl_sep','<=', $show->TGLSEP)
            ->where('status', 1)
            ->whereNull('deleted_at')
            // ->orderBy('queue', 'ASC')
            ->orderBy('tgl', 'ASC')
            ->limit(8)
            ->get();

        // print_r($show);
        // print_r($jadwalList);
        // die();

        if ($jadwalList->isEmpty()) {
            throw new \Exception('Data jadwal kosong');
        }

        // ==========================
        // CLONE ROW
        // ==========================
        $templateProcessor->cloneRow('TGL', $jadwalList->count());

        foreach ($jadwalList as $i => $item) {
            $no = $i + 1;

            // ==========================
            // TEXT
            // ==========================
            $templateProcessor->setValue("NO#$no",
                $no
            );

            $templateProcessor->setValue("TGL#$no",
                $item->tgl ? Carbon::parse($item->tgl)->translatedFormat('d-m-Y') : '-'
            );

            $program = $item->program_terapis1 ?? '';

            if (!empty($item->program_terapis2)) {
                $program .= "\n" . $item->program_terapis2;
            }

            $templateProcessor->setValue(
                "PROGRAM_TERAPIS#$no",
                htmlspecialchars($program)
            );

            // ==========================
            // TTD PASIEN
            // ==========================
            if (!empty($item->ttd_pasien) && Storage::disk('public')->exists($item->ttd_pasien)) {
                $this->setImgWord(
                    $templateProcessor,
                    "TTD_PASIEN#$no",
                    Storage::disk('public')->path($item->ttd_pasien),
                    80
                );
            } else {
                $templateProcessor->setValue("TTD_PASIEN#$no", '');
            }

            // ==========================
            // TTD DOKTER
            // ==========================
            if (!empty($item->ttd_dokter) && Storage::disk('public')->exists($item->ttd_dokter)) {
                $this->setImgWord(
                    $templateProcessor,
                    "TTD_DOKTER#$no",
                    Storage::disk('public')->path($item->ttd_dokter),
                    50
                );
            } else {
                $templateProcessor->setValue("TTD_DOKTER#$no", '');
            }

            // ==========================
            // TTD TERAPIS 1
            // ==========================
            if (!empty($item->ttd_terapis1) && Storage::disk('public')->exists($item->ttd_terapis1)) {
                $this->setImgWord(
                    $templateProcessor,
                    "TTD_TERAPIS1#$no",
                    Storage::disk('public')->path($item->ttd_terapis1),
                    50
                );
            } else {
                $templateProcessor->setValue("TTD_TERAPIS1#$no", '');
            }

            // ==========================
            // TTD TERAPIS 2
            // ==========================
            if (!empty($item->ttd_terapis2) && Storage::disk('public')->exists($item->ttd_terapis2)) {
                $this->setImgWord(
                    $templateProcessor,
                    "TTD_TERAPIS2#$no",
                    Storage::disk('public')->path($item->ttd_terapis2),
                    50
                );
            } else {
                $templateProcessor->setValue("TTD_TERAPIS2#$no", '');
            }
        }

        if (!empty($show->PATH_TTD_DOKTER_PEMERIKSA) && Storage::disk('public')->exists($show->PATH_TTD_DOKTER_PEMERIKSA)) {
            $this->setImgWord(
                $templateProcessor,
                'TTD_DOKTER_PEMERIKSA',
                Storage::disk('public')->path($show->PATH_TTD_DOKTER_PEMERIKSA),
                50
            );
        }

        $data = [
            'KUNJUNGAN' => $show->KUNJUNGAN,
            'GROUP'     => $show->GROUP,
            'QUEUE'     => $show->QUEUE,
            'TGLKUNJUNGAN'  => Carbon::parse($show->TGLKUNJUNGAN)->translatedFormat('d F Y'),
            'TGLSURAT'  => Carbon::parse($show->TGLSURAT)->translatedFormat('d F Y'),
            'TGLSEP'    => Carbon::parse($show->TGLSEP)->translatedFormat('d F Y'),

            'DIAG_MEDIS' => $show->DIAG_MEDIS ?? '',
            'PERMINTAAN_TERAPI' => $show->PERMINTAAN_TERAPI ?? '',

            'NORM'        => $show->RM,
            'NAMAPASIEN' => $show->NAMAPASIEN ?? '',
            'TGLLAHIRPASIEN' => Carbon::parse($show->TGLLAHIRPASIEN)->translatedFormat('d F Y') ?? '',
            'UMURPASIEN' => $show->UMURPASIEN ?? '',
            'ALAMATPASIEN' => $show->ALAMATPASIEN ?? '',

            'DOKTER_PEMERIKSA' => $show->DOKTER_PEMERIKSA ?? '',
        ];

        foreach ($data as $key => $value) {
            $templateProcessor->setValue($key, $this->escapeDocx($value));
        }

        $verify = klaim_file::where('nomor',$show->KUNJUNGAN)
                            ->where('jenis',11)
                            ->where('sub_jenis',3) // FORM JADWAL PELAYANAN TERAPI
                            // ->where('kode',$show->QUEUE)
                            // ->where('ref',$show->GROUP)
                            ->where('status',true)
                            ->whereNull('deleted_at')
                            ->orderBy('id','DESC')
                            ->first();
        if ($verify) {

            // Hapus file fisik lama
            $oldFile = storage_path('app/public/' . $verify->filename);

            if (File::exists($oldFile)) {
                File::delete($oldFile);
            }

            // Soft delete data lama
            $verify->status = 0;
            $verify->deleted_at = now();
            $verify->user_deleted = Auth::user()->ID;
            $verify->updated_at = now();
            $verify->save();
        }

        /* =========================================
        * BUAT DATA KLAIM_FILE BARU
        * ========================================= */
        $post = new klaim_file;
        $post->jenis = 11;
        $post->sub_jenis = 3; // FORM JADWAL PELAYANAN TERAPI
        $post->kode = $show->QUEUE;
        $post->ref = $show->GROUP;
        $post->nomor = $show->KUNJUNGAN;
        $post->title = $show->KUNJUNGAN . '-' . $show->QUEUE . '.pdf';
        $post->filename = $path . '.pdf';
        $post->nama_tambahan = 'Formulir Jadwal Pelayanan Terapi';
        $post->status = true;
        $post->user = Auth::user()->ID;
        $post->created_at = now();
        $post->updated_at = now();
        $post->save();

        // if (!$verify) {
        //     $post = new klaim_file;
        //     $post->jenis = 11;
        //     $post->sub_jenis = 3; // FORM JADWAL PELAYANAN TERAPI
        //     $post->kode = $show->QUEUE;
        //     $post->ref = $show->GROUP;
        //     $post->nomor = $show->KUNJUNGAN;
        //     $post->title = $show->KUNJUNGAN.'-'.$show->QUEUE.'.pdf';
        //     $post->filename = $path.'.pdf';
        //     $post->nama_tambahan = 'Formulir Jadwal Pelayanan Terapi';
        //     $post->status = true;
        //     $post->user = Auth::user()->ID;
        //     $post->created_at = now();
        //     $post->updated_at = now();
        //     $post->save();
        // } else {
        //     $verify->user = Auth::user()->ID;
        //     $verify->updated_at = now();
        //     $verify->save();
        // }

        $output = storage_path().'/app/public/'.$path;

        // Pastikan folder tujuan ada
        $outputDir = dirname($output);
        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true); // true = recursive
        }

        $outputWord = $output.'.docx';

        // $path = storage_path('app/public/files/jadwal_'.$show->KUNJUNGAN.'.docx');
        $templateProcessor->saveAs($outputWord);

        [$success] = $this->libreOffice->generatePdf($outputWord, dirname($outputWord));

        if (!$success) {
            throw new \Exception('Gagal generate PDF');
        }

        if (File::exists($outputWord)) {
            File::delete($outputWord);
        }

        if (file_exists($output.'.pdf')) {
            return true;
        }

        return false;
    }

    function regenerateFormJadwalPelayanan(Request $request)
    {
        $kunjungan = $request->kunjungan;

        DB::beginTransaction();

        try {
            $formKfr = DB::table('simrspku_klaim.emr_form_kfr as kfr')
                    ->leftJoin('medicalrecord.cppt', function($join) {
                        $join->on('cppt.ID', '=', 'kfr.id_cppt')
                            ->where('cppt.STATUS', '=', 1);
                    })
                    // ->select('kfr.*', 'cppt.ASSESMENT AS diag_medis','cppt.PLANNING AS permintaan_terapi')
                    ->select('kfr.*', 'cppt.ASSESMENT AS diag_medis','cppt.PLANNING')
                    ->where('kfr.nomor', $request->kunjungan)
                    ->where('kfr.status', 1)
                    ->whereNull('deleted_at')
                    ->first();

            if (!$formKfr) {
                return response()->json([
                    'status' => false,
                    'message'=> 'Form KFR belum diisi atau tidak ditemukan'
                ], 404);
            }

            // PERMINTAAN TERAPI
            $planningText = preg_replace("/\r?\n/", "\n", $formKfr->PLANNING ?? '');

            $permintaanTerapi = $this->getSection(
                $planningText,
                'Tindakan/Program Rehabilitasi Medik'
            );

            if (empty($permintaanTerapi)) {
                $permintaanTerapi = $formKfr->PLANNING; // fallback full text
            }

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

            $formTerapi = DB::table('simrspku_klaim.emr_form_terapi as terapi')
                    ->leftJoin('medicalrecord.cppt', function($join) {
                        $join->on('cppt.ID', '=', 'terapi.id_cppt')
                            ->where('cppt.STATUS', '=', 1);
                    })
                    ->select(
                        'terapi.*',
                        'cppt.PLANNING as program_terapi',
                        DB::raw("(
                            SELECT signature_path
                            FROM simrspku_klaim.tanda_tangan_pegawai ttp
                            WHERE ttp.nip = terapi.nip_tim
                            AND ttp.status = 1
                            AND ttp.deleted_at IS NULL
                            ORDER BY RAND()
                            LIMIT 1
                        ) as ttd_terapis")
                    )
                    ->where('terapi.nomor', $request->kunjungan)
                    ->where('terapi.status', 1)
                    ->whereNull('terapi.deleted_at')
                    ->orderBy('terapi.queue', 'ASC')
                    ->get();

            if ($formTerapi->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message'=> 'Form Program Terapi belum diisi'
                ], 404);
            }

            if ($formTerapi->contains(fn($x) => empty($x->ttd_terapis))) {
                return response()->json([
                    'status' => false,
                    'message'=> 'TTD Terapis belum lengkap'
                ], 422);
            }

            $ttd_dokter = DB::table('simrspku_klaim.tanda_tangan_pegawai')
                ->where('nip', $formKfr->nip_dokter)
                ->where('status', 1)
                ->whereNull('deleted_at')
                ->inRandomOrder()
                ->first();

            if (!$ttd_dokter) {
                return response()->json([
                    'status' => false,
                    'message'=> 'TTD dokter tidak ditemukan'
                ], 404);
            }

            // $formJadwal = DB::table('simrspku_klaim.emr_form_jadwal')
            //     ->where('nomor', $kunjungan)
            //     ->where('rm', $request->rm)
            //     ->where('status', 1)
            //     ->whereNull('deleted_at')
            //     ->orderBy('id', 'DESC')
            //     ->first();

            $formJadwals = DB::table('simrspku_klaim.emr_form_jadwal')
                ->where('nomor', $kunjungan)
                ->where('rm', $request->rm)
                // ->where('status', 1)
                // ->whereNull('deleted_at')
                ->orderByDesc('id')
                ->get();

            if ($formJadwals->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message'=> 'Form Jadwal Pelayanan Tidak Ditemukan, gagal mengenerate Berkas Form Baru'
                ], 404);
            }

            $formJadwalFinal = $formJadwals->first(); // data terbaru

            // Jika ada data lama
            if ($formJadwals->count() > 1) {

                $duplicateForms = $formJadwals->skip(1);

                $duplicateIds = $duplicateForms
                    ->pluck('id')
                    ->toArray();

                // Soft delete form lama
                DB::table('simrspku_klaim.emr_form_jadwal')
                    ->whereIn('id', $duplicateIds)
                    ->update([
                        'status'     => 0,
                        'deleted_at' => now(),
                        'user'       => auth()->id(),
                        'updated_at' => now(),
                    ]);

                // Soft delete file lama
                foreach ($duplicateForms as $oldForm) {

                    DB::table('simrspku_klaim.klaim_file')
                        ->where('nomor', $kunjungan)
                        ->where('jenis', 11)
                        ->where('sub_jenis', 3)
                        ->where('kode', $oldForm->queue)
                        ->where('ref', $oldForm->group)
                        ->where('status', 1)
                        ->whereNull('deleted_at')
                        ->update([
                            'status'       => 0,
                            'deleted_at'   => now(),
                            'user_deleted' => auth()->id(),
                            'updated_at'   => now(),
                        ]);
                }
            }

            // ==========================
            // UPDATE DATA TERBARU
            // ==========================

            DB::table('simrspku_klaim.emr_form_jadwal')
                ->where('id', $formJadwalFinal->id)
                ->update([

                    'tgl'   => $request->tgl,
                    'sep'   => $request->sep,
                    'tgl_sep'=> $request->tgl_sep ?? $request->tgl,

                    'group' => $formKfr->group,
                    'queue' => $formKfr->queue,

                    'diag_medis'        => $formKfr->diag_medis,
                    'permintaan_terapi' => $permintaanTerapi,

                    'program_terapis1' => $formTerapi[0]->program_terapi ?? '',
                    'program_terapis2' => $formTerapi[1]->program_terapi ?? '',

                    'id_ttd_pasien' => $formJadwalFinal->id_ttd_pasien ?? null,
                    'ttd_pasien'    => $formJadwalFinal->ttd_pasien ?? null,

                    'nip_dokter'   => $formKfr->nip_dokter,
                    'nip_terapis1' => $formTerapi[0]->nip_tim ?? '',
                    'nip_terapis2' => $formTerapi[1]->nip_tim ?? '',
                    'nip_dpjp'     => $formKfr->nip_dokter,

                    'ttd_dokter'   => $ttd_dokter->signature_path ?? '',
                    'ttd_terapis1' => $formTerapi[0]->ttd_terapis ?? '',
                    'ttd_terapis2' => $formTerapi[1]->ttd_terapis ?? '',
                    'ttd_dpjp'     => $ttd_dokter->signature_path ?? '',

                    'nama_dpjp' => $formKfr->nama_dokter,

                    'user' => auth()->id(),
                    'updated_at' => now()
                ]);

            $data = (object)[
                'KUNJUNGAN' => $kunjungan,
                'GROUP'     => $formKfr->group,
                'QUEUE'     => $formKfr->queue,
                'TGLKUNJUNGAN'  => $request->tgl,
                'TGLSURAT'  => $request->tgl_sep ?? $request->tgl,
                'TGLSEP'    => $request->tgl_sep ?? $request->tgl,

                'DIAG_MEDIS' => $formKfr->diag_medis ?? '',
                'PERMINTAAN_TERAPI' => $permintaanTerapi ?? '',
                'PROGRAM1' => $formTerapi[0]->program_terapi ?? '',
                'PROGRAM2' => $formTerapi[1]->program_terapi ?? '',

                'RM'        => $request->rm,
                'NAMAPASIEN' => $dataPasien->NAMAPASIEN ?? '',
                'TGLLAHIRPASIEN' => $dataPasien->TGLLAHIRPASIEN ?? '',
                'UMURPASIEN' => $dataPasien->UMURPASIEN ?? '',
                'ALAMATPASIEN' => $dataPasien->ALAMATPASIEN ?? '',

                'PATH_TTD_PASIEN'  => $formJadwalFinal->ttd_pasien ?? '',
                'PATH_TTD_DOKTER'  => $ttd_dokter->signature_path ?? '',
                'PATH_TTD_TERAPIS1'     => $formTerapi[0]->ttd_terapis ?? '',
                'PATH_TTD_TERAPIS2'     => $formTerapi[1]->ttd_terapis ?? null,
                'PATH_TTD_DOKTER_PEMERIKSA' => $ttd_dokter->signature_path ?? '',
                'DOKTER_PEMERIKSA' => $formKfr->nama_dokter,
            ];

            // $result = $this->generateFormJadwalPelayanan($data);

            // if (!$result) {
            //     return response()->json([
            //         'status' => false,
            //         'message'=> 'Generate Ulang Form Jadwal Pelayanan Gagal'
            //     ], 500);
            // }

            // ==========================
            // GENERATE PDF
            // ==========================
            DB::afterCommit(function () use ($data) {
                try {
                    $this->generateFormJadwalPelayanan($data);
                } catch (\Exception $e) {
                    \Log::error('Generate PDF gagal: '.$e->getMessage());
                }
            });

            DB::commit();

            return response()->json([
                'status' => true,
                'message'=> 'Generate Ulang Form Jadwal Pelayanan Berhasil Dilakukan',
                // 'path'   => $path
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message'=> $e->getMessage()
            ], 500);
        }
        // return response()->json([
        //     'status' => true,
        //     'message'=> 'Generate Ulang Form Jadwal Pelayanan Berhasil Dilakukan',
        //     'path'   => $path
        // ]);
    }

    function destroyFormJadwalPelayanan($KUNJUNGAN)
    {
        DB::beginTransaction();

        try {
            $now = now();

            // $verify = DB::table('simrspku_klaim.emr_form_jadwal')
            //     ->where('nomor', $KUNJUNGAN)
            //     ->where('status', 1)
            //     ->whereNull('deleted_at')
            //     ->orderBy('id', 'DESC')
            //     ->first();

            $verify = DB::table('simrspku_klaim.emr_form_jadwal as efj')
                        ->leftJoin('aplikasi.pengguna as pe', function ($join) {
                            $join->on('pe.ID', '=', 'efj.user')
                                ->where('pe.STATUS', 1);
                        })
                        ->select(
                            'efj.*',
                            DB::raw('master.getNamaLengkapPegawai(pe.NIP) AS NAMAUSER')
                        )
                        ->where('efj.nomor', $KUNJUNGAN)
                        ->where('efj.status', 1)
                        ->whereNull('efj.deleted_at')
                        ->latest('efj.id')
                        ->first();

            if (!$verify) {
                return response()->json([
                    'status' => false,
                    'message' => 'Form Jadwal Pelayanan tidak ditemukan atau sudah dihapus'
                ], 404);
            }

            if ($verify->user != auth()->id()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Anda tidak memiliki izin untuk menghapus form ini. Hanya user (' . $verify->NAMAUSER . ') yang membuat form ini yang dapat menghapusnya.'
                ], 403);
            }

            DB::table('simrspku_klaim.emr_form_jadwal')
                ->where('nomor', $KUNJUNGAN)
                ->where('status', 1)
                ->whereNull('deleted_at')
                ->update([
                    'user' => auth()->id(),
                    'status' => 0,
                    'deleted_at' => $now
                ]);

            DB::table('simrspku_klaim.klaim_file')
                ->where('nomor', $KUNJUNGAN)
                ->where('jenis', 11)
                ->where('sub_jenis', 3) // FORM JADWAL PELAYANAN TERAPI
                ->where('status', 1)
                ->whereNull('deleted_at')
                ->update([
                    'user_deleted' => auth()->id(),
                    'status' => 0,
                    'deleted_at' => $now
                ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message'=> 'Form Jadwal Pelayanan berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message'=> $e->getMessage()
            ], 500);
        }
    }

    function getJadwalPelayanan($KUNJUNGAN)
    {
        // $validasi = emr_form_kfr::where('nomor',$KUNJUNGAN)->where('status',1)->whereNull('deleted_at')->latest('id')->first();

        // if (!$validasi) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Data Formulir KFR tidak ditemukan untuk kunjungan ini. Silakan melakukan pengisian pada Tab Formulir Rawat Jalan terlebih dahulu'
        //     ]);
        // }

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

        // return response()->json([
        //     'status' => true,
        //     'data' => $validasi
        //     // 'data' => [
        //     //     's' => $data->SUBYEKTIF,
        //     //     'o' => $data->OBYEKTIF,
        //     //     'a' => $data->ASSESMENT,
        //     //     'p' => $data->PROCEDURE,
        //     // ]
        // ], 200);
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
