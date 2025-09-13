<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\simrspku_klaim\klaim_file;
use App\Models\simrspku_klaim\klaim_qrcode;
use App\Models\simrspku_klaim\klaim_qrcode_pegawai;
use Illuminate\Support\Facades\Crypt;
use Milon\Barcode\DNS2D;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PHPJasper\PHPJasper;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Auth, Storage;

class ApiMonitoringController extends Controller
{
    function table(Request $request)
    {
        $user = auth()->user();

        // INIT
        $tgls   = $request->tgls;
        $tgle   = $request->tgle;
        $dpjp   = $request->dpjp;
        $status = (int) $request->status;
        $rawat = (int) $request->rawat;
        $berkas = (int) $request->berkas;
        // Berkas = 5 = Semua Status
        // Berkas = 0 = Berkas Belum Lengkap (Sudah ada Tindakan, Cppt, TTE, dan SEP)
        // Berkas = 1 = Berkas Masih Ada Catatan
        // Berkas = 2 = Berkas Sudah Lengkap

        $time = Carbon::now()->isoFormat('YYYY-MM-DD HH:mm:ss');

        // SUB QUERY FROM ANY TABEL
        $subTindakan = DB::table(DB::raw('
            (
                SELECT *,
                    ROW_NUMBER() OVER (PARTITION BY KUNJUNGAN ORDER BY TANGGAL DESC) AS rn
                FROM layanan.tindakan_medis
                WHERE STATUS = 1
            ) AS td
        '))->where('td.rn', 1); // hanya baris TINDAKAN terakhir per kunjungan
        $subCppt = DB::table(DB::raw('
            (
                SELECT *,
                    ROW_NUMBER() OVER (PARTITION BY KUNJUNGAN ORDER BY TANGGAL DESC) AS rn
                FROM medicalrecord.cppt
                WHERE STATUS = 1
            ) AS cp
        '))->where('cp.rn', 1); // hanya baris CPPT terakhir per kunjungan
        $subTTD = DB::table(DB::raw('
            (
                SELECT *,
                    ROW_NUMBER() OVER (PARTITION BY KUNJUNGAN ORDER BY created_at DESC) AS rn
                FROM simrspku_klaim.tanda_tangan
                WHERE deleted_at IS null
            ) AS ttd
        '))->where('ttd.rn', 1); // hanya baris TTD terakhir per kunjungan
        $subCatatan = DB::table(DB::raw('
            (
                SELECT *,
                    ROW_NUMBER() OVER (PARTITION BY nomor ORDER BY created_at DESC) AS rn
                FROM simrspku_klaim.klaim_verifikasi_catatan
                WHERE deleted_at IS null AND status = 1
            ) AS cat
        '))->where('cat.rn', 1);
        $subCatatanAgg = DB::table('simrspku_klaim.klaim_verifikasi_catatan') // subquery yang menghitung status catatan per nomor
            ->select('nomor', DB::raw('
                CASE
                    WHEN COUNT(*) = 0 THEN 0
                    WHEN SUM(CASE WHEN status = true THEN 1 ELSE 0 END) = 0 THEN 0
                    WHEN SUM(CASE WHEN status = true AND solved = 0 THEN 1 ELSE 0 END) > 0 THEN 1
                    ELSE 2
                END AS catatan_status
            '))
            ->whereNull('deleted_at')
            ->where('status', 1)
            ->groupBy('nomor');
        // MAIN QUERY
        $show = DB::table('pendaftaran.kunjungan AS pk')
                ->select(
                    'pk.*',
                    'pp.NORM','pp.TANGGAL AS TGLDAFTAR',
                    'kjs.noSEP AS NOSEP','kjs.tglSEP AS TGLSEP',
                    'ru.DESKRIPSI AS NAMARUANGAN',
                    'cat.updated_at AS TGLCATATAN',
                    'ttd.created_at AS TGLTTD',
                    'cp.TANGGAL AS TGLCPPT',
                    'td.TANGGAL AS TGLTINDAKAN',
                    'pj.NO_SURAT AS NOMORBOOKING',
                    // 'jk.NOMOR AS NOSURKON','jk.NOMOR_BOOKING AS NOMORBOOKING',
                    DB::raw('master.getNamaLengkap(ps.NORM) AS NAMAPASIEN'),
                    DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'),
                    'catagg.catatan_status AS CATATAN'
                    // DB::raw("(
                    //     SELECT
                    //         CASE
                    //             WHEN COUNT(*) = 0 THEN 0
                    //             WHEN SUM(CASE WHEN kvc.status = true THEN 1 ELSE 0 END) = 0 THEN 0
                    //             WHEN SUM(CASE WHEN kvc.status = true AND kvc.solved = 0 THEN 1 ELSE 0 END) > 0 THEN 1
                    //             ELSE 2
                    //         END
                    //     FROM simrspku_klaim.klaim_verifikasi_catatan AS kvc
                    //     WHERE kvc.nomor = pk.NOMOR
                    // ) AS CATATAN")
                )
                ->leftJoinSub($subCatatan, 'cat', function ($join) { // CATATAN
                    $join->on('cat.nomor', '=', 'pk.NOMOR');
                })
                ->leftJoinSub($subCatatanAgg, 'catagg', function ($join) {
                    $join->on('catagg.nomor', '=', 'pk.NOMOR');
                })
                ->leftJoinSub($subTTD, 'ttd', function ($join) { // CPPT
                    $join->on('ttd.kunjungan', '=', 'pk.NOMOR');
                })
                ->leftJoinSub($subCppt, 'cp', function ($join) { // CPPT
                    $join->on('cp.KUNJUNGAN', '=', 'pk.NOMOR');
                })
                ->leftJoinSub($subTindakan, 'td', function ($join) { // TINDAKAN
                    $join->on('td.KUNJUNGAN', '=', 'pk.NOMOR');
                })
                ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                ->leftJoin('pendaftaran.penjamin AS pj','pj.NOPEN','=','pp.NOMOR')
                ->leftJoin('medicalrecord.perencanaan_rawat_inap AS pri','pri.KUNJUNGAN','=','pk.NOMOR')
                ->leftJoin('pembayaran.tagihan_pendaftaran AS tp','tp.PENDAFTARAN','=','pk.NOPEN')
                ->leftJoin('bpjs.kunjungan AS kjs','kjs.noSEP','=','pj.NOMOR')
                // ->leftJoin('medicalrecord.jadwal_kontrol AS jk','jk.KUNJUNGAN','=','pk.NOMOR')
                ->leftJoin('master.pasien AS ps','ps.NORM','=','pp.NORM')
                // ->leftJoin('master.kartu_identitas_pasien AS kip','kip.NORM','=','pp.NORM')
                ->leftJoin('aplikasi.pengguna','aplikasi.pengguna.ID','=','pk.DITERIMA_OLEH')
                ->leftJoin('master.ruangan AS ru','ru.ID','=','pk.RUANGAN')
                ->leftJoin('master.dokter AS dr','dr.ID','=','pk.DPJP')
                // ->where(function ($query) {
                //     $query->where('pk.RUANGAN', 'LIKE', '1020101%');
                // })
                ->where(function ($query) use ($tgls,$tgle) {
                    $query->whereRaw("LEFT(pk.MASUK, 10) BETWEEN ? AND ?", [$tgls, $tgle]);
                })
                ->where('pj.JENIS', 2) // PENJAMIN BPJS ONLY
                // ->where('pk.BARU', 1) // KUNJUNGAN PERTAMA
                ->where('ru.STATUS', 1) // STATUS RUANGAN AKTIF
                // ->where('jk.STATUS', 1) // STATUS RENCANA KONTROL AKTIF

                // FILTER RUANGAN
                ->when(in_array($rawat, [1, 2, 3]), function ($query) use ($rawat) {
                    $prefix = '';
                    switch ($rawat) {
                        case 1:
                            $prefix = '1020101%'; // RAJAL
                            break;
                        case 2:
                            $prefix = '1020201%'; // RADAR
                            break;
                        case 3:
                            $prefix = '1020301%'; // RANAP
                            break;
                    }
                    $query->where('pk.RUANGAN', 'LIKE', $prefix);
                })
                ->when($rawat == 5, function ($query) {
                    $query->where(function ($q) {
                        $q->where('pk.RUANGAN', 'LIKE', '1020101%')
                            ->orWhere('pk.RUANGAN', 'LIKE', '1020201%')
                            ->orWhere('pk.RUANGAN', 'LIKE', '1020301%');
                    });
                })

                // FILTER STATUS BERKAS
                ->when($berkas == 2, function ($query) { // BERKAS SUDAH LENGKAP
                    $query->where(function ($q) {
                        $q->whereNotNull('cp.TANGGAL') // CPPT
                            ->whereNotNull('td.TANGGAL') // TINDAKAN
                            ->whereNotNull('kjs.noSEP') // SEP
                            ->whereNotNull('ttd.created_at') // TTE
                            ->whereNotNull('cat.updated_at')
                            ->whereNotNull('cat.solved')
                            ->where('cat.solved',1);
                    });
                })
                ->when($berkas == 1, function ($query) { // BERKAS MASIH ADA CATATAN
                    $query->where(function ($q) {
                        $q->whereNotNull('cat.updated_at')
                            ->whereNotNull('cat.solved')
                            ->where('cat.status',1)
                            ->where('cat.solved',0);
                    });
                })
                ->when($berkas == 0, function ($query) { // BERKAS BELUM LENGKAP
                    $query->where(function ($q) {
                        $q->where('cp.TANGGAL',null) // CPPT
                            ->orWhere('td.TANGGAL',null) // TINDAKAN
                            ->orWhere('kjs.noSEP',null) // SEP
                            ->orWhere('ttd.created_at',null) // TTE
                            ->whereNull('cat.updated_at');
                    });
                })

                // KHUSUS RAWAT DARURAT (TANPA PERENCANAAN RAWAT INAP)
                ->when($rawat == 2, function ($query) use ($rawat) {
                    $query->where(function ($q) {
                        $q->where('tp.UTAMA', 1)
                            ->where('tp.STATUS', 1)
                            ->whereNull('pri.KUNJUNGAN');
                    });
                })

                // FILTER STATUS KUNJUNGAN
                ->when($status != 5, function ($query) use ($status) { // 0=BATAL;1=MASIH DILAYANI;2=SELESAI;5=ALL
                    $query->where('pk.STATUS', $status);
                            // ->where('pp.STATUS', $status);
                });

                if (Auth::user()->hasAnyRole(['dokterspesialis'])) {
                    $show->where('dr.NIP', Auth::user()->NIP);
                } else {
                    $show->when($dpjp != 0, function ($query) use ($dpjp) {
                        // Hanya menambahkan where jika $dpjp bukan 0
                        $query->where('dr.NIP', $dpjp);
                    });
                }

        $show = $show->orderBy('pk.MASUK','DESC')
                ->get();

        $data = [
            'show' => $show,
            'time' => $time,
        ];

        return response()->json($data, 200);
    }

    // MONITORING
        // CPPT
        function cppt($kunjungan)
        {
            // $cppt = DB::table('medicalrecord.cppt')
            //         ->where('KUNJUNGAN', $kunjungan) // kalau perlu
            //         ->orderBy('TANGGAL', 'desc')
            //         ->get(); // ambil 1 cppt terakhir

            $getNopen = DB::table('pendaftaran.kunjungan AS pk')
                        ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                        ->select('pk.NOPEN','pp.NORM')
                        ->where('pk.NOMOR',$kunjungan)
                        ->first();
            $show = DB::select('CALL simrspku_klaim.CetakCPPT(?, ?)', [$getNopen->NOPEN, $kunjungan]); // NOPEN & NOKUNJUNGAN
            // print_r($show);
            // die();

            $data = [
                'pen' => $getNopen,
                'show' => $show,
            ];

            return response()->json($data, 200);
        }

        function tindakan($kunjungan)
        {
            $show = DB::table('layanan.tindakan_medis AS tm')
                    // ->leftJoin('layanan.petugas_tindakan_medis AS ptm','ptm.TINDAKAN_MEDIS','=','tm.ID')
                    ->leftJoin('layanan.petugas_tindakan_medis AS ptm', function($join) {
                        $join->on('ptm.TINDAKAN_MEDIS', '=', 'tm.ID')
                            ->where('ptm.STATUS', 1);
                    })
                    ->leftJoin('master.referensi AS ref', function($join) {
                        $join->on('ref.ID', '=', 'ptm.JENIS')
                            ->where('ref.JENIS', '=', 32);
                    })
                    ->leftJoin('master.tindakan AS mt','mt.ID','=','tm.TINDAKAN')
                    ->leftJoin('master.referensi AS tref', function($join) {
                        $join->on('tref.ID', '=', 'mt.JENIS')
                            ->where('tref.JENIS', '=', 74);
                    })
                    ->leftJoin('aplikasi.pengguna AS ap','ap.ID','=','tm.OLEH')
                    ->leftJoin('master.dokter AS dr', function($join) {
                        $join->on('ptm.MEDIS', '=', 'dr.id')
                            ->whereIn('ptm.JENIS', [1,2]);
                    })
                    ->leftJoin('master.perawat AS pr', function($join) {
                        $join->on('ptm.MEDIS', '=', 'pr.id')
                            ->whereIn('ptm.JENIS', [3,4,5,6,7,8,9,10]);
                    })
                    ->select(
                        'tm.ID','tm.KUNJUNGAN','tm.TANGGAL','tm.VERIFIKASI',
                        // 'ref.DESKRIPSI AS JENISTENAGAMEDIS',
                        'mt.NAMA AS NAMATINDAKAN',
                        'tref.DESKRIPSI AS JENISTINDAKAN',
                        DB::raw("GROUP_CONCAT(
                        CONCAT(
                            '- ',
                            master.getNamaLengkapPegawai(
                                CASE
                                    WHEN ptm.JENIS IN (1,2) THEN dr.NIP
                                    WHEN ptm.JENIS IN (3,4,5,6,7,8,9,10) THEN pr.NIP
                                    ELSE '-'
                                END
                            ), ' (', ref.DESKRIPSI, ')'
                        ) SEPARATOR '<br>') AS TENAGAMEDIS"),
                        DB::raw('master.getNamaLengkapPegawai(ap.NIP) AS NAMAUSER'),
                        // 'ptm.JENIS',
                        // 'ptm.MEDIS',
                        // 'dr.id as id_dokter',
                        // 'pr.id as id_perawat',
                    )
                    ->where('tm.KUNJUNGAN', $kunjungan)
                    ->where('tm.STATUS', 1)
                    ->orderBy('tm.TANGGAL', 'ASC')
                    ->groupBy('tm.ID', 'tm.KUNJUNGAN', 'tm.TANGGAL', 'tm.VERIFIKASI', 'mt.NAMA', 'tref.DESKRIPSI', 'ap.NIP')
                    // ->orderBy('ptm.KE', 'ASC')
                    ->get();

            // print_r($show);
            // die();

            $data = [
                'show' => $show,
            ];

            return response()->json($data, 200);
        }

        function compileSkdp($kunjungan)
        {
            // $getSEP = DB::table('pendaftaran.kunjungan AS pk')
            //         ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
            //         ->leftJoin('pendaftaran.penjamin AS pj','pp.NOMOR','=','pj.NOPEN')
            //         ->select('pj.NOMOR AS NOSEP')
            //         ->where('pk.NOMOR',$kunjungan)
            //         ->first();
            $show = DB::select('CALL simrspku_klaim.RencanaKontrolCustom(?)',[$kunjungan]);
            if (empty($show)) {
                return response()->json($data, 400);
            }
            // print_r($show);
            // die();
            $getSKDP = DB::select('CALL simrspku_klaim.CariSKDP(?)',[$show[0]->NOPEN]);
            // print_r($getSKDP);
            // die();
            if (empty($getSKDP)) {
                return response()->json($getSKDP, 400);
            }
            $cetakSKDP = DB::select('CALL simrspku_klaim.RencanaKontrolCustom(?)',[$getSKDP[0]->LAMA]);
            // print_r($cetakSKDP);
            // die();
            // ----------------------------------------------------------------------
            $getTgl = Carbon::parse($show[0]->JKONTROL);
            $tgl = $getTgl->isoFormat('DD');
            $bulan = $getTgl->isoFormat('MM');
            $tahun = $getTgl->isoFormat('YYYY');
            // ----------------------------------------------------------------------
            $input = public_path().'/doc/input/skdp/CetakSKDP.jrxml';
            $path = 'files/skdp/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$kunjungan;
            $output = storage_path().'/app/public/'.$path;
            // Pastikan folder tujuan ada
            $outputDir = dirname($output);
            if (!File::exists($outputDir)) {
                File::makeDirectory($outputDir, 0755, true); // true = recursive
            }

            //GENERATE QR CODE
            $generator = new DNS2D();
            $skdp = $cetakSKDP[0]->NOSURAT;

            // Generate QR code PNG base64 (bukan data:image/png;base64,... hanya base64 murni)
            $image = $generator->getBarcodePNG($skdp, 'QRCODE');
            // print_r($generator);
            // die();

            // Decode base64 jadi binary PNG
            $decodedImage = base64_decode($image);
            $token = Crypt::encrypt($cetakSKDP[0]->NOSURAT);
            $titleQrcode = Crypt::encrypt($cetakSKDP[0]->NOSURAT).'.png';
            $verif = klaim_qrcode_pegawai::where('nomor',$cetakSKDP[0]->SURAT)->first();

            // Simpan ke file storage Laravel (storage/app/public/files/qrcode{nip}.png)
            $pathQrcode = 'files/qrcode/' . $titleQrcode;
            $outputQrcode = storage_path('app/public/' . $pathQrcode);

            // SAVE TO DB
            if (!$verif) {
                file_put_contents($outputQrcode, $decodedImage);
                $post = new klaim_qrcode_pegawai;
                $post->token = $token;
                $post->nomor = $cetakSKDP[0]->SURAT;
                $post->title = $titleQrcode;
                $post->filename = $pathQrcode;
                $post->save();
            } else {
                if (!Storage::disk('public')->exists($verif->filename)) {
                    file_put_contents($outputQrcode, $decodedImage);
                    $verif->token = $token;
                    $verif->title = $titleQrcode;
                    $verif->filename = $pathQrcode;
                    $verif->save();
                }
            }

            //GENERATE QR CODE
            $generator2 = new DNS2D();
            $skdp2 = $cetakSKDP[0]->NOBPJS;

            // Generate QR code PNG base64 (bukan data:image/png;base64,... hanya base64 murni)
            $image2 = $generator2->getBarcodePNG($skdp2, 'QRCODE');
            // print_r($generator);
            // die();

            // Decode base64 jadi binary PNG
            $decodedImage2 = base64_decode($image2);
            $token2 = Crypt::encrypt($cetakSKDP[0]->NOBPJS);
            $titleQrcode2 = Crypt::encrypt($cetakSKDP[0]->NOBPJS).'.png';
            $verif2 = klaim_qrcode::where('nomor',$cetakSKDP[0]->NOBPJS)->where('jenis',3)->first();

            // Simpan ke file storage Laravel (storage/app/public/files/qrcode{nip}.png)
            $pathQrcode2 = 'files/qrcode/' . $titleQrcode2;
            $outputQrcode2 = storage_path('app/public/' . $pathQrcode2);

            // SAVE TO DB
            if (!$verif2) {
                file_put_contents($outputQrcode2, $decodedImage2);
                $post = new klaim_qrcode;
                $post->token = $token2;
                $post->jenis = 3;
                $post->nomor = $cetakSKDP[0]->NOBPJS;
                $post->title = $titleQrcode2;
                $post->filename = $pathQrcode2;
                $post->save();
            } else {
                if (!Storage::disk('public')->exists($verif2->filename)) {
                    file_put_contents($outputQrcode2, $decodedImage2);
                    $verif2->token = $token2;
                    $verif2->title = $titleQrcode2;
                    $verif2->filename = $pathQrcode2;
                    $verif2->save();
                }
            }

            // SAVE TO DB
            $verify = klaim_file::where('nomor',$kunjungan)->where('jenis',3)->where('status',true)->first();
            if (!$verify) {
                $post = new klaim_file;
                $post->jenis = 3;
                $post->nomor = $kunjungan;
                $post->title = $kunjungan.'.pdf';
                $post->filename = $path.'.pdf';
                $post->status = true;
                $post->user = Auth::user()->ID;
                $post->save();
            }

            // if (file_exists($output . '.pdf')) {
            //     // File ada
            //     return response()->file($output.'.pdf',[
            //         'Content-Type' => 'application/pdf',
            //     ]);
            // } else {
            // }

            $options = [
                'format' => ['pdf'],
                'params' => [
                    'PKUNJUNGAN' => $getSKDP[0]->LAMA,
                    'IMAGES_PATH' => public_path()."/doc/input/skdp/",
                    'QRCODE_PATH' => storage_path()."/app/public/",
                ],
                'db_connection' => [
                    'driver'   => config('database.connections.db_custom.driver'),
                    'host'     => config('database.connections.db_custom.host'),
                    'port'     => config('database.connections.db_custom.port'),
                    'username' => config('database.connections.db_custom.username'),
                    'password' => config('database.connections.db_custom.password'),
                    'database' => config('database.connections.db_custom.database'),
                ],
            ];

            // dd($options);
            // print_r($options);
            // die();

            $jasper = new PHPJasper;

            $jasper->process(
                $input,
                $output,
                $options
            )->execute();

            return response()->file($output.'.pdf',[
                'Content-Type' => 'application/pdf',
            ]);
        }

        function compileSep($kunjungan)
        {
            $getSEP = DB::table('pendaftaran.kunjungan AS pk')
                    ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                    ->leftJoin('pendaftaran.penjamin AS pj','pp.NOMOR','=','pj.NOPEN')
                    ->select('pj.NOMOR AS NOSEP')
                    ->where('pk.NOMOR',$kunjungan)
                    ->first();
            $show = DB::select('CALL simrspku_klaim.CetakSEP(?)',[$getSEP->NOSEP]);
            if (empty($show)) {
                return response()->json($data, 400);
            }
            // ----------------------------------------------------------------------
            $getTgl = Carbon::parse($show[0]->TGLSEP);
            $tgl = $getTgl->isoFormat('DD');
            $bulan = $getTgl->isoFormat('MM');
            $tahun = $getTgl->isoFormat('YYYY');
            // ----------------------------------------------------------------------
            $input = public_path().'/doc/input/sep/CetakSEP.jrxml';
            $path = 'files/sep/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$kunjungan;
            $output = storage_path().'/app/public/'.$path;
            // Pastikan folder tujuan ada
            $outputDir = dirname($output);
            if (!File::exists($outputDir)) {
                File::makeDirectory($outputDir, 0755, true); // true = recursive
            }

            //GENERATE QR CODE
            $generator = new DNS2D();
            $sep = $show[0]->NOMORKARTU;

            // Generate QR code PNG base64 (bukan data:image/png;base64,... hanya base64 murni)
            $image = $generator->getBarcodePNG($sep, 'QRCODE');
            // print_r($generator);
            // die();

            // Decode base64 jadi binary PNG
            $decodedImage = base64_decode($image);
            $token = Crypt::encrypt($show[0]->NOMORKARTU);
            $titleQrcode = Crypt::encrypt($show[0]->NOMORKARTU).'.png';
            $verif = klaim_qrcode_pegawai::where('nomor',$show[0]->NOMORKARTU)->first();

            // Simpan ke file storage Laravel (storage/app/public/files/qrcode{nip}.png)
            $pathQrcode = 'files/qrcode/' . $titleQrcode;
            $outputQrcode = storage_path('app/public/' . $pathQrcode);

            // SAVE TO DB
            if (!$verif) {
                file_put_contents($outputQrcode, $decodedImage);
                $post = new klaim_qrcode_pegawai;
                $post->token = $token;
                $post->nomor = $show[0]->NOMORKARTU;
                $post->title = $titleQrcode;
                $post->filename = $pathQrcode;
                $post->save();
            } else {
                if (!Storage::disk('public')->exists($verif->filename)) {
                    file_put_contents($outputQrcode, $decodedImage);
                    $verif->token = $token;
                    $verif->title = $titleQrcode;
                    $verif->filename = $pathQrcode;
                    $verif->save();
                }
            }

            // SAVE TO DB
            $verify = klaim_file::where('nomor',$kunjungan)->where('jenis',1)->where('status',true)->first();
            if (!$verify) {
                $post = new klaim_file;
                $post->jenis = 1;
                $post->nomor = $kunjungan;
                $post->title = $kunjungan.'.pdf';
                $post->filename = $path.'.pdf';
                $post->status = true;
                $post->user = Auth::user()->ID;
                $post->save();
            }

            $options = [
                'format' => ['pdf'],
                'params' => [
                    'PSEP' => $getSEP->NOSEP,
                    'IMAGES_PATH' => public_path()."/doc/input/sep/",
                    'QRCODE_PATH' => storage_path()."/app/public/",
                ],
                'db_connection' => [
                    'driver'   => config('database.connections.db_custom.driver'),
                    'host'     => config('database.connections.db_custom.host'),
                    'port'     => config('database.connections.db_custom.port'),
                    'username' => config('database.connections.db_custom.username'),
                    'password' => config('database.connections.db_custom.password'),
                    'database' => config('database.connections.db_custom.database'),
                ],
            ];

            $jasper = new PHPJasper;

            $jasper->process(
                $input,
                $output,
                $options
            )->execute();

            // print_r($output);
            // die();

            return response()->file($output.'.pdf',[
                'Content-Type' => 'application/pdf',
            ]);
        }

        //TTD RESUME
        function showTtdResumeRj($kunjungan)
        {
            $getRESUMERJ = DB::table('pendaftaran.kunjungan AS pk')
                    ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                    ->leftJoin('pendaftaran.penjamin AS pj','pp.NOMOR','=','pj.NOPEN')
                    ->select('pj.NOMOR AS NOSEP','pp.NOMOR AS NOPEN','pk.NOMOR AS NOMOR')
                    ->where('pk.NOMOR',$kunjungan)
                    ->first();

            $show = DB::select('CALL simrspku_klaim.CetakResumeRJ(?,?)',[$getRESUMERJ->NOPEN,$getRESUMERJ->NOMOR]);

            if ($show) {
                $getTgl = Carbon::parse($show[0]->TGLPERIKSA);
                $tgl = $getTgl->isoFormat('DD');
                $bulan = $getTgl->isoFormat('MM');
                $tahun = $getTgl->isoFormat('YYYY');

                $path = 'files/resume/RJ/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$kunjungan;
                $output = storage_path().'/app/public/'.$path;

                if (file_exists($output.'.pdf')) {
                    $verify = klaim_file::where('nomor', $kunjungan)
                        ->where('jenis', 2)
                        ->where('status', true)
                        ->first();

                    if (!$verify) {
                        $post = new klaim_file;
                        $post->jenis = 2;
                        $post->nomor = $kunjungan;
                        $post->title = $kunjungan.'.pdf';
                        $post->filename = $path.'.pdf';
                        $post->status = true;
                        $post->user = Auth::user()->ID;
                        $post->save();
                    }

                    $isExist = true;
                } else {
                    $isExist = false;
                }
            } else {
                $isExist = false;
            }

            $data = [
                'show' => $show,
                'isExist' => $isExist,
            ];

            return response()->json($data, 200);
        }

        public function storeTtdResumeRj(Request $request)
        {
            $existing = DB::table('simrspku_klaim.tanda_tangan')
                ->where('kunjungan', $request->nama)
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tanda tangan untuk kunjungan ini sudah ada.',
                ], 409); // 409 = Conflict
            }
            $request->validate([
                // 'nama' => 'required|string|max:255',
                // 'signature' => 'required|string',
            ]);

            $image = str_replace('data:image/png;base64,', '', $request->signature);
            $image = str_replace(' ', '+', $image);
            $filename = 'ttd_' . time() . '.png';

            Storage::disk('public')->put("/signatures/{$filename}", base64_decode($image));

            $pasien = DB::table('simrspku_klaim.tanda_tangan')->insert([
                'kunjungan' => $request->nama,
                'signature_path' => "signatures/{$filename}",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'user' => Auth::user()->ID,
            ]);

            return response()->json([
                'success' => true,
                // 'id' => $pasien->kunjungan
            ]);
        }

        function compileResumeRj($kunjungan)
        {
            $getRESUMERJ = DB::table('pendaftaran.kunjungan AS pk')
            ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
            ->leftJoin('pendaftaran.penjamin AS pj','pp.NOMOR','=','pj.NOPEN')
            ->leftJoin('master.ruangan AS ru','ru.ID','=','pk.RUANGAN')
            ->select('pj.NOMOR AS NOSEP','pp.NOMOR AS NOPEN','pk.NOMOR AS NOMOR','pk.RUANGAN AS RUANGAN','pk.DPJP')
            ->where('pk.NOMOR',$kunjungan)
            ->first();

            $show = DB::select('CALL simrspku_klaim.CetakResumeRJ(?,?)',[$getRESUMERJ->NOPEN,$getRESUMERJ->NOMOR]);
            $obat = DB::select('CALL simrspku_klaim.CetakObatRJ(?)',[$getRESUMERJ->NOPEN]);

            if (empty($show)) {
                return response()->json($data, 400);
            }
            $keluhan    = $this->cleanText($show[0]->KELUHAN);
            $assesment  = $this->cleanText($show[0]->ASSESMENT);
            $subyektif  = $this->cleanText($show[0]->SUBYEKTIF);
            $obyektif   = $this->cleanText($show[0]->OBYEKTIF);
            $planning   = $this->cleanText($show[0]->PLANNING);
            $instruksi  = $this->cleanText($show[0]->INSTRUKSI);

            $NAMA_OBAT = collect($obat)->pluck('NAMAOBAT')->implode(', ');

            // ----------------------------------------------------------------------
            $ttd = DB::table('simrspku_klaim.tanda_tangan AS ttd')
                ->where('ttd.kunjungan',$kunjungan)
                ->whereNull('deleted_at')
                ->first();
            if ($ttd) {
                $imagePath2 = storage_path()."/app/public/".$ttd->signature_path;
            } else {
                $getIDUser = DB::table('master.dokter AS dr')
                                ->leftJoin('aplikasi.pengguna AS pe','pe.NIP','=','dr.NIP')
                                ->select('pe.ID')
                                ->where('dr.ID',$getRESUMERJ->DPJP)
                                ->where('dr.STATUS',1)
                                ->first();
                $getTtdLast = DB::table('simrspku_klaim.tanda_tangan AS ttd')
                                ->where('ttd.user',$getIDUser->ID)
                                ->whereNull('deleted_at')
                                ->first();
                if ($getTtdLast) {
                    $imagePath2 = storage_path()."/app/public/".$getTtdLast->signature_path;
                    DB::table('simrspku_klaim.tanda_tangan')->insert([
                        'kunjungan' => $kunjungan,
                        'signature_path' => $getTtdLast->signature_path,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'user' => Auth::user()->ID,
                    ]);
                } else {
                    $imagePath2 = null;
                }
            }

            // ----------------------------------------------------------------------
            $getTgl = Carbon::parse($show[0]->TGLPERIKSA);
            $tgl = $getTgl->isoFormat('DD');
            $bulan = $getTgl->isoFormat('MM');
            $tahun = $getTgl->isoFormat('YYYY');

            // ----------------------------------------------------------------------
            $path = 'files/resume/RJ/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$kunjungan;
            $output = storage_path().'/app/public/'.$path;

            // cek di DB
            $verify = klaim_file::where('nomor', $kunjungan)
                ->where('jenis', 2)
                ->where('status', true)
                ->first();

            // if (file_exists($output.'.pdf')) {
            //     if (!$verify) {
            //         $post = new klaim_file;
            //         $post->jenis = 2;
            //         $post->nomor = $kunjungan;
            //         $post->title = $kunjungan.'.pdf';
            //         $post->filename = $path.'.pdf';
            //         $post->status = true;
            //         $post->user = Auth::user()->ID;
            //         $post->save();
            //     }

            //     return response()->file($output.'.pdf',[
            //         'Content-Type' => 'application/pdf',
            //     ]);
            // }

            if (!$verify) {
                $post = new klaim_file;
                $post->jenis = 2;
                $post->nomor = $kunjungan;
                $post->title = $kunjungan.'.pdf';
                $post->filename = $path.'.pdf';
                $post->status = true;
                $post->user = Auth::user()->ID;
                $post->save();
            }

            // Pastikan folder tujuan ada
            $outputDir = dirname($output);
            if (!File::exists($outputDir)) {
                File::makeDirectory($outputDir, 0755, true); // true = recursive
            }

            // ----------------------------------------------------------------------
            if (str_starts_with($getRESUMERJ->RUANGAN, '1020201')) {
                $input = public_path().'/doc/input/resumeRD/CetakResumeRadar.jrxml';
                $options = [
                    'format' => ['pdf'],
                    'params' => [
                        'PNOPEN' => $getRESUMERJ->NOPEN,
                        'IMAGES_PATH' => public_path()."/doc/input/resumeRD/",
                        'IMAGES_PATH2' => $imagePath2,
                    ],
                    'db_connection' => [
                        'driver'   => config('database.connections.db_custom.driver'),
                        'host'     => config('database.connections.db_custom.host'),
                        'port'     => config('database.connections.db_custom.port'),
                        'username' => config('database.connections.db_custom.username'),
                        'password' => config('database.connections.db_custom.password'),
                        'database' => config('database.connections.db_custom.database'),
                    ],
                    // 'db_connection' => [
                    //     'driver'   => 'mysql',
                    //     'host'     => env('DB_HOST'),
                    //     'port'     => env('DB_PORT'),
                    //     'username' => env('DB_USERNAME'),
                    //     'password' => env('DB_PASSWORD'),
                    //     'database' => env('DB_DATABASE_CUSTOM'),
                    // ],
                ];
            } else {
                $input = public_path().'/doc/input/resumeRJ/CetakResumeRJ.jrxml';
                $options = [
                    'format' => ['pdf'],
                    'params' => [
                        'PNOPEN' => $getRESUMERJ->NOPEN,
                        'PKUNJUNGAN' => $getRESUMERJ->NOMOR,
                        'COBA' => 'KRINCING RT 14/06 KRINCING RT. 0 RW. 0 Kel/Desa. KRINCING Kec. INI HANYA CONTOH. KRINCING RT 14/06 KRINCING RT. 0 RW. 0 Kel/Desa. KRINCING Kec. SELESAIIIIIII',
                        'IMAGES_PATH' => public_path()."/doc/input/resumeRJ/",
                        'IMAGES_PATH2' => $imagePath2,
                    ],
                    'db_connection' => [
                        'driver'   => config('database.connections.db_custom.driver'),
                        'host'     => config('database.connections.db_custom.host'),
                        'port'     => config('database.connections.db_custom.port'),
                        'username' => config('database.connections.db_custom.username'),
                        'password' => config('database.connections.db_custom.password'),
                        'database' => config('database.connections.db_custom.database'),
                    ],
                    // 'db_connection' => [
                    //     'driver'   => 'mysql',
                    //     'host'     => env('DB_HOST'),
                    //     'port'     => env('DB_PORT'),
                    //     'username' => env('DB_USERNAME'),
                    //     'password' => env('DB_PASSWORD'),
                    //     'database' => env('DB_DATABASE_CUSTOM'),
                    // ],
                ];
            }

            // print_r($options);
            // die();

            $jasper = new PHPJasper;

            $jasper->process(
                $input,
                $output,
                $options
            )->execute();

            return response()->file($output.'.pdf',[
                'Content-Type' => 'application/pdf',
            ]);
        }

        function compileIndividual($kunjungan)
        {
            $getSEP = DB::table('pendaftaran.kunjungan AS pk')
                    ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                    ->leftJoin('pendaftaran.penjamin AS pj','pp.NOMOR','=','pj.NOPEN')
                    ->select('pj.NOMOR AS NOSEP','pp.NOMOR AS NOPEN')
                    ->where('pk.NOMOR',$kunjungan)
                    ->first();
            $show = DB::select('CALL simrspku_klaim.CetakLapIndividual5(?,?)',[$getSEP->NOPEN,3]);
            if (empty($show)) {
                return response()->json($data, 400);
            }
            $CETAK_HEADER = "1";
            // ----------------------------------------------------------------------
            $getTgl = Carbon::parse($show[0]->TGLREG);
            $tgl = $getTgl->isoFormat('DD');
            $bulan = $getTgl->isoFormat('MM');
            $tahun = $getTgl->isoFormat('YYYY');
            // ----------------------------------------------------------------------
            $input = public_path().'/doc/input/individual/CetakLapIndividual.jrxml';
            $path = 'files/individual/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$kunjungan;
            $output = storage_path().'/app/public/'.$path;

            // SAVE TO DB
            $verify = klaim_file::where('nomor',$kunjungan)->where('jenis',4)->where('status',true)->first();
            if (!$verify) {
                $post = new klaim_file;
                $post->jenis = 4;
                $post->nomor = $kunjungan;
                $post->title = $kunjungan.'.pdf';
                $post->filename = $path.'.pdf';
                $post->status = true;
                $post->user = Auth::user()->ID;
                $post->save();
            }

            // Pastikan folder tujuan ada
            $outputDir = dirname($output);
            if (!File::exists($outputDir)) {
                File::makeDirectory($outputDir, 0755, true); // true = recursive
            }

            $options = [
                'format' => ['pdf'],
                'params' => [
                    'PNOPEN' => $getSEP->NOPEN,
                    'PKELAS'  => 3,
                    'IMAGES_PATH' => public_path()."/doc/input/individual/",
                ],
                'db_connection' => [
                    'driver'   => config('database.connections.db_custom.driver'),
                    'host'     => config('database.connections.db_custom.host'),
                    'port'     => config('database.connections.db_custom.port'),
                    'username' => config('database.connections.db_custom.username'),
                    'password' => config('database.connections.db_custom.password'),
                    'database' => config('database.connections.db_custom.database'),
                ],
            ];

            // print_r($options);
            // die();

            $jasper = new PHPJasper;

            $jasper->process(
                $input,
                $output,
                $options
            )->execute();



            return response()->file($output.'.pdf',[
                'Content-Type' => 'application/pdf',
            ]);
        }

        function compileKwitansiResep($kunjungan)
        {
            $getSEP = DB::table('pendaftaran.kunjungan AS pk')
                    ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                    ->leftJoin('pendaftaran.penjamin AS pj','pp.NOMOR','=','pj.NOPEN')
                    ->select('pj.NOMOR AS NOSEP','pp.NOMOR AS NOPEN','pk.MASUK AS TANGGALMASUK')
                    ->where('pk.NOMOR',$kunjungan)
                    ->first();

            $CETAK_HEADER = "1";
            // ----------------------------------------------------------------------
            $getTgl = Carbon::parse($getSEP->TANGGALMASUK);
            $tgl = $getTgl->isoFormat('DD');
            $bulan = $getTgl->isoFormat('MM');
            $tahun = $getTgl->isoFormat('YYYY');

            $show = DB::table('pendaftaran.pendaftaran AS pd')
                        ->leftJoin('pendaftaran.kunjungan AS k', 'k.NOPEN', '=', 'pd.NOMOR')
                        ->select('k.NOMOR AS NOMOR')
                        ->where('pd.NOMOR', $getSEP->NOPEN)
                        ->whereIn('k.RUANGAN', ['102060103', '102060104'])
                        ->get();
            // print_r($show);
            // die();
            if ($show->isEmpty() || empty($show) || !$show) {
                return response()->json($data, 400);
            }

            $listKunjungan = $show->pluck('NOMOR')->unique(); // Collection of string


            // Inisialisasi objek PHPJasper
            $jasper = new PHPJasper;

            // Tentukan path untuk input dan output file
            $input = public_path().'/doc/input/kwitansiResep/CetakKwitansiResep.jrxml'; // Ganti dengan path file .jrxml yang sesuai
            $tempPaths = [];

            // Proses setiap PNOMOR
            foreach ($listKunjungan as $index => $PKUNJUNGAN) {
                $show2 = DB::select('CALL simrspku_klaim.CetakFakturResep(?)',[$PKUNJUNGAN]);
                // print_r($show2);
                // die();

                $path = 'files/kwitansiResep/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$kunjungan;
                $output = storage_path().'/app/public/'.$path;

                $outputDir = dirname($output);
                if (!File::exists($outputDir)) {
                    File::makeDirectory($outputDir, 0755, true); // true = recursive
                }

                $options = [
                    'format' => ['pdf'],
                    'params' => [
                        'PKUNJUNGAN' => $PKUNJUNGAN,
                        'IMAGES_PATH' => public_path() . "/doc/input/kwitansiResep/",
                    ],
                    'db_connection' => [
                        'driver'   => config('database.connections.db_custom.driver'),
                        'host'     => config('database.connections.db_custom.host'),
                        'port'     => config('database.connections.db_custom.port'),
                        'username' => config('database.connections.db_custom.username'),
                        'password' => config('database.connections.db_custom.password'),
                        'database' => config('database.connections.db_custom.database'),
                    ],
                ];
                // print_r($options);
                // die();

                // Proses JasperReport untuk setiap PNOMOR
                $jasper->process($input, $output, $options)->execute();
                $tempPaths[] = "{$output}.pdf"; // Simpan path PDF sementara
            }

            // Gabungkan semua PDF yang dihasilkan
            $pathMerged = 'files/kwitansiResep/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$kunjungan;
            $outputMerged = storage_path().'/app/public/'.$pathMerged;
            $outputDirMerged = dirname($outputMerged);
            if (!File::exists($outputDirMerged)) {
                File::makeDirectory($outputDirMerged, 0755, true); // true = recursive
            }

            // SAVE TO DB
            $verify = klaim_file::where('nomor',$kunjungan)->where('jenis',12)->where('status',true)->first();
            if (!$verify) {
                $post = new klaim_file;
                $post->jenis = 12;
                $post->nomor = $kunjungan;
                $post->title = $kunjungan.'.pdf';
                $post->filename = $pathMerged.'.pdf';
                $post->status = true;
                $post->user = Auth::user()->ID;
                $post->save();
            }

            $pdf = new Fpdi();

            // Gabungkan setiap file PDF hasil proses untuk setiap PNOMOR
            foreach ($tempPaths as $file) {
                $pageCount = $pdf->setSourceFile($file);
                for ($page = 1; $page <= $pageCount; $page++) {
                    $tpl = $pdf->importPage($page);
                    $size = $pdf->getTemplateSize($tpl);
                    $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                    $pdf->useTemplate($tpl);
                }
            }

            // Simpan file PDF gabungan
            $pdf->Output('F', $outputMerged.'.pdf');
            $output = storage_path().'/app/public/files/kwitansiResep/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$kunjungan;
            if (File::exists($output)) {
                File::deleteDirectory($output);
            }

            return response()->file($outputMerged.'.pdf',[
                'Content-Type' => 'application/pdf',
            ]);
        }

        function compileBilling($kunjungan)
        {
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

            $show = DB::select('CALL simrspku_klaim.CetakRincianPasienPerDokterCustom(?,?)',[$getSEP->TAGIHAN,$getSEP->STATUS]);
            // print_r($show);
            // die();
            //-----------------------------------------------------------------------
            //GENERATE QR CODE
            $generator = new DNS2D();
            $pegawai = $show[0]->NIP . '-' . $show[0]->PENGGUNA;

            // Generate QR code PNG base64 (bukan data:image/png;base64,... hanya base64 murni)
            $image = $generator->getBarcodePNG($pegawai, 'QRCODE');
            // print_r($generator);
            // die();

            if ($show[0]->NIP) {
                // Decode base64 jadi binary PNG
                $decodedImage = base64_decode($image);
                $token = Crypt::encrypt($show[0]->NIP);
                $titleQrcode = Crypt::encrypt($show[0]->NIP).'.png';
                $verif = klaim_qrcode_pegawai::where('nomor',$show[0]->NIP)->first();

                // Simpan ke file storage Laravel (storage/app/public/files/qrcode{nip}.png)
                $pathQrcode = 'files/qrcode/' . $titleQrcode;
                $outputQrcode = storage_path('app/public/' . $pathQrcode);

                // SAVE TO DB
                if (!$verif) {
                    file_put_contents($outputQrcode, $decodedImage);
                    $post = new klaim_qrcode_pegawai;
                    $post->token = $token;
                    $post->nomor = $show[0]->NIP;
                    $post->title = $titleQrcode;
                    $post->filename = $pathQrcode;
                    $post->save();
                } else {
                    if (!Storage::disk('public')->exists($verif->filename)) {
                        file_put_contents($outputQrcode, $decodedImage);
                        $verif->token = $token;
                        $verif->title = $titleQrcode;
                        $verif->filename = $pathQrcode;
                        $verif->save();
                    }
                }
            }

            //GENERATE QR CODE
            $generator2 = new DNS2D();
            $nama = $show[0]->NAMA_KELUARGA_PASIEN ? $show[0]->NAMA_KELUARGA_PASIEN : $show[0]->NAMALENGKAP;
            $pasien = $show[0]->RM .'-'.$nama;

            // Generate QR code PNG base64 (bukan data:image/png;base64,... hanya base64 murni)
            $image2 = $generator2->getBarcodePNG($pasien, 'QRCODE');
            // print_r($generator);
            // die();

            // Decode base64 jadi binary PNG
            $decodedImage2 = base64_decode($image2);
            $token2 = Crypt::encrypt($nama);
            $titleQrcode2 = Crypt::encrypt($nama).'.png';
            $verif2 = klaim_qrcode::where('nomor',$show[0]->RM)->where('jenis',1)->first();

            // Simpan ke file storage Laravel (storage/app/public/files/qrcode{nip}.png)
            $pathQrcode2 = 'files/qrcode/' . $titleQrcode2;
            $outputQrcode2 = storage_path('app/public/' . $pathQrcode2);

            // SAVE TO DB
            if (!$verif2) {
                file_put_contents($outputQrcode2, $decodedImage2);
                $post = new klaim_qrcode;
                $post->jenis = 1;
                $post->token = $token2;
                $post->nomor = $show[0]->RM;
                $post->title = $titleQrcode2;
                $post->filename = $pathQrcode2;
                $post->save();
            } else {
                if (!Storage::disk('public')->exists($verif2->filename)) {
                    file_put_contents($outputQrcode2, $decodedImage2);
                    $verif2->token = $token2;
                    $verif2->title = $titleQrcode2;
                    $verif2->filename = $pathQrcode2;
                    $verif2->save();
                }
            }

            //-----------------------------------------------------------------------
            if (!$getSEP) {
                return response()->json($show, 400);
            }
            // ----------------------------------------------------------------------
            $getTgl = Carbon::parse($getSEP->TANGGALMASUK);
            $tgl = $getTgl->isoFormat('DD');
            $bulan = $getTgl->isoFormat('MM');
            $tahun = $getTgl->isoFormat('YYYY');
            // ----------------------------------------------------------------------
            $input = public_path().'/doc/input/billing/CetakBillingmaster.jrxml';
            $path = 'files/billing/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$kunjungan;
            $output = storage_path().'/app/public/'.$path;

            // SAVE TO DB
            $verify = klaim_file::where('nomor',$kunjungan)->where('jenis',5)->where('status',true)->first();
            if (!$verify) {
                $post = new klaim_file;
                $post->jenis = 5;
                $post->nomor = $kunjungan;
                $post->title = $kunjungan.'.pdf';
                $post->filename = $path.'.pdf';
                $post->status = true;
                $post->user = Auth::user()->ID;
                $post->save();
            }

            // Pastikan folder tujuan ada
            $outputDir = dirname($output);
            if (!File::exists($outputDir)) {
                File::makeDirectory($outputDir, 0755, true); // true = recursive
            }

            $options = [
                'format' => ['pdf'],
                'params' => [
                    'PTAGIHAN' => $getSEP->TAGIHAN,
                    'PSTATUS'  => $getSEP->STATUS,
                    'IMAGES_PATH' => public_path()."/doc/input/billing/",
                    'QRCODE_PATH' => storage_path()."/app/public/",
                ],
                'db_connection' => [
                    'driver'   => config('database.connections.db_custom.driver'),
                    'host'     => config('database.connections.db_custom.host'),
                    'port'     => config('database.connections.db_custom.port'),
                    'username' => config('database.connections.db_custom.username'),
                    'password' => config('database.connections.db_custom.password'),
                    'database' => config('database.connections.db_custom.database'),
                ],
            ];
            // print_r($options);
            // die();

            $jasper = new PHPJasper;

            $jasper->process(
                $input,
                $output,
                $options
            )->execute();



            return response()->file($output.'.pdf',[
                'Content-Type' => 'application/pdf',
            ]);
        }

        function compileLab($kunjungan)
        {
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

            $getTgl = Carbon::parse($getSEP->TANGGALMASUK);
            $tgl = $getTgl->isoFormat('DD');
            $bulan = $getTgl->isoFormat('MM');
            $tahun = $getTgl->isoFormat('YYYY');

            $show = DB::table('pendaftaran.pendaftaran AS pd')
                        ->leftJoin('pendaftaran.kunjungan AS k', 'k.NOPEN', '=', 'pd.NOMOR')
                        ->leftJoin('layanan.tindakan_medis AS tm','tm.KUNJUNGAN','=','k.NOMOR')
                        ->select('k.NOMOR AS NOMOR', 'tm.ID AS TINDAKAN')
                        ->where('pd.NOMOR', $getSEP->NOPEN)
                        ->where('k.RUANGAN', '=', '102040101')
                        ->where('tm.STATUS',1)
                        ->get();
            // print_r($show);
            // die();
            if ($show->isEmpty()) {
                return response()->json($data, 400);
            }

            // Kelompokkan data berdasarkan PNOMOR dan gabungkan PTINDAKAN dalam satu string
            $groupedData = $show->groupBy('NOMOR')->map(function ($group) {
                return $group->pluck('TINDAKAN')->unique()->implode(',');
            });

            // Inisialisasi objek PHPJasper
            $jasper = new PHPJasper;

            // Tentukan path untuk input dan output file
            $input = public_path().'/doc/input/laborat/CetakLab.jrxml'; // Ganti dengan path file .jrxml yang sesuai
            $tempPaths = [];

            // Proses setiap PNOMOR
            foreach ($groupedData as $PNOMOR => $PTINDAKAN) {
                $show2 = DB::select('CALL simrspku_klaim.CetakHasilLab(?,?)',[$PNOMOR,$PTINDAKAN]);
                // print_r($show2);
                // die();
                foreach ($show2 as $key => $value) {
                    //GENERATE QR CODE
                    $generator = new DNS2D();
                    $pegawai = $value->NIP_ANALIS . '-' . $value->ANALIS;

                    // Generate QR code PNG base64 (bukan data:image/png;base64,... hanya base64 murni)
                    $image = $generator->getBarcodePNG($pegawai, 'QRCODE');
                    // print_r($generator);
                    // die();

                    // Decode base64 jadi binary PNG
                    $decodedImage = base64_decode($image);
                    $token = Crypt::encrypt($value->NIP_ANALIS);
                    $titleQrcode = Crypt::encrypt($value->NIP_ANALIS).'.png';
                    if ($value->NIP_ANALIS) {
                        $verif = klaim_qrcode_pegawai::where('nomor',$value->NIP_ANALIS)->first();
                        // Simpan ke file storage Laravel (storage/app/public/files/qrcode{nip}.png)
                        $pathQrcode = 'files/qrcode/' . $titleQrcode;
                        $outputQrcode = storage_path('app/public/' . $pathQrcode);

                        // SAVE TO DB
                        if (!$verif) {
                            file_put_contents($outputQrcode, $decodedImage);
                            $post = new klaim_qrcode_pegawai;
                            $post->token = $token;
                            $post->nomor = $value->NIP_ANALIS;
                            $post->title = $titleQrcode;
                            $post->filename = $pathQrcode;
                            $post->save();
                        } else {
                            if (!Storage::disk('public')->exists($verif->filename)) {
                                file_put_contents($outputQrcode, $decodedImage);
                                $verif->token = $token;
                                $verif->title = $titleQrcode;
                                $verif->filename = $pathQrcode;
                                $verif->save();
                            }
                        }
                    }
                }

                $path = 'files/laborat/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$kunjungan.'/laporan_'.$PNOMOR;
                $output = storage_path().'/app/public/'.$path;
                // $outputPath = storage_path("app/reports/laporan_{$PNOMOR}");

                $outputDir = dirname($output);
                if (!File::exists($outputDir)) {
                    File::makeDirectory($outputDir, 0755, true); // true = recursive
                }

                $options = [
                    'format' => ['pdf'],
                    'params' => [
                        'PNOMOR' => $PNOMOR,      // Kirim data PNOMOR ke report
                        'PTINDAKAN' => $PTINDAKAN,  // Kirim data PTINDAKAN ke report
                        'IMAGES_PATH' => public_path() . "/doc/input/laborat/",  // Ganti dengan path gambar jika ada
                        'QRCODE_PATH' => storage_path()."/app/public/",
                    ],
                    'db_connection' => [
                        'driver'   => config('database.connections.db_custom.driver'),
                        'host'     => config('database.connections.db_custom.host'),
                        'port'     => config('database.connections.db_custom.port'),
                        'username' => config('database.connections.db_custom.username'),
                        'password' => config('database.connections.db_custom.password'),
                        'database' => config('database.connections.db_custom.database'),
                    ],
                ];
                // print_r($options);
                // die();

                // Proses JasperReport untuk setiap PNOMOR
                $jasper->process($input, $output, $options)->execute();
                $tempPaths[] = "{$output}.pdf"; // Simpan path PDF sementara
            }

            // Gabungkan semua PDF yang dihasilkan
            $pathMerged = 'files/laborat/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$kunjungan;
            $outputMerged = storage_path().'/app/public/'.$pathMerged;
            $outputDirMerged = dirname($outputMerged);
            if (!File::exists($outputDirMerged)) {
                File::makeDirectory($outputDirMerged, 0755, true); // true = recursive
            }

            // SAVE TO DB
            $verify = klaim_file::where('nomor',$kunjungan)->where('jenis',6)->where('status',true)->first();
            if (!$verify) {
                $post = new klaim_file;
                $post->jenis = 6;
                $post->nomor = $kunjungan;
                $post->title = $kunjungan.'.pdf';
                $post->filename = $pathMerged.'.pdf';
                $post->status = true;
                $post->user = Auth::user()->ID;
                $post->save();
            }

            $pdf = new Fpdi();

            // Gabungkan setiap file PDF hasil proses untuk setiap PNOMOR
            foreach ($tempPaths as $file) {
                $pageCount = $pdf->setSourceFile($file);
                for ($page = 1; $page <= $pageCount; $page++) {
                    $tpl = $pdf->importPage($page);
                    $size = $pdf->getTemplateSize($tpl);
                    $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                    $pdf->useTemplate($tpl);
                }
            }

            // Simpan file PDF gabungan
            $pdf->Output('F', $outputMerged.'.pdf');
            $output = storage_path().'/app/public/files/laborat/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$kunjungan;
            if (File::exists($output)) {
                File::deleteDirectory($output);
            }

            return response()->file($outputMerged.'.pdf',[
                'Content-Type' => 'application/pdf',
            ]);
        }

        function compileRad($kunjungan)
            {
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

            $getTgl = Carbon::parse($getSEP->TANGGALMASUK);
            $tgl = $getTgl->isoFormat('DD');
            $bulan = $getTgl->isoFormat('MM');
            $tahun = $getTgl->isoFormat('YYYY');

            $show = DB::table('pendaftaran.pendaftaran AS pd')
                    ->leftJoin('pendaftaran.kunjungan AS k', 'k.NOPEN', '=', 'pd.NOMOR')
                    ->leftJoin('layanan.tindakan_medis AS tm','tm.KUNJUNGAN','=','k.NOMOR')
                    ->select('k.NOMOR AS NOMOR', 'tm.ID AS TINDAKAN')
                    ->where('pd.NOMOR', $getSEP->NOPEN)
                    ->where('k.RUANGAN', '=', '102050101')
                    ->where('tm.STATUS',1)
                    ->get();

            // print_r($show);
            // die();

            if ($show->isEmpty() || empty($show) || !$show) {
                return response()->json($data, 400);
            }

            $listTindakan = $show->pluck('TINDAKAN')->unique(); // Collection of string


            // Inisialisasi objek PHPJasper
            $jasper = new PHPJasper;

            // Tentukan path untuk input dan output file
            $input = public_path().'/doc/input/radiologi/CetakRadiologi.jrxml'; // Ganti dengan path file .jrxml yang sesuai
            $tempPaths = [];

            // Proses setiap PNOMOR
            foreach ($listTindakan as $index => $PTINDAKAN) {
                $show2 = DB::select('CALL simrspku_klaim.CetakHasilRadrspkuskh(?)',[$PTINDAKAN]);
                // print_r($show2);
                // die();
                //GENERATE QR CODE
                $generator = new DNS2D();
                $pegawai = $show2[0]->NIP . '-' . $show2[0]->RADIOGRAFER;

                // Generate QR code PNG base64 (bukan data:image/png;base64,... hanya base64 murni)
                $image = $generator->getBarcodePNG($pegawai, 'QRCODE');
                // print_r($generator);
                // die();

                // Decode base64 jadi binary PNG
                $decodedImage = base64_decode($image);
                $token = Crypt::encrypt($show2[0]->NIP);
                $titleQrcode = Crypt::encrypt($show2[0]->NIP).'.png';
                $verif = klaim_qrcode_pegawai::where('nomor',$show2[0]->NIP)->first();

                // Simpan ke file storage Laravel (storage/app/public/files/qrcode{nip}.png)
                $pathQrcode = 'files/qrcode/' . $titleQrcode;
                $outputQrcode = storage_path('app/public/' . $pathQrcode);

                // SAVE TO DB
                if (!$verif) {
                    file_put_contents($outputQrcode, $decodedImage);
                    $post = new klaim_qrcode_pegawai;
                    $post->token = $token;
                    $post->nomor = $show2[0]->NIP;
                    $post->title = $titleQrcode;
                    $post->filename = $pathQrcode;
                    $post->save();
                } else {
                    if (!Storage::disk('public')->exists($verif->filename)) {
                        file_put_contents($outputQrcode, $decodedImage);
                        $verif->token = $token;
                        $verif->title = $titleQrcode;
                        $verif->filename = $pathQrcode;
                        $verif->save();
                    }
                }

                $path = 'files/radiologi/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$kunjungan.'/laporan_'.$index;
                $output = storage_path().'/app/public/'.$path;

                $outputDir = dirname($output);
                if (!File::exists($outputDir)) {
                    File::makeDirectory($outputDir, 0755, true); // true = recursive
                }

                $options = [
                    'format' => ['pdf'],
                    'params' => [
                        'PTINDAKAN' => $PTINDAKAN,  // Kirim data PTINDAKAN ke report
                        'IMAGES_PATH' => public_path() . "/doc/input/radiologi/",  // Ganti dengan path gambar jika ada
                        'QRCODE_PATH' => storage_path()."/app/public/",
                    ],
                    'db_connection' => [
                        'driver'   => config('database.connections.db_custom.driver'),
                        'host'     => config('database.connections.db_custom.host'),
                        'port'     => config('database.connections.db_custom.port'),
                        'username' => config('database.connections.db_custom.username'),
                        'password' => config('database.connections.db_custom.password'),
                        'database' => config('database.connections.db_custom.database'),
                    ],
                ];
                // print_r($options);
                // die();

                // Proses JasperReport untuk setiap PNOMOR
                $jasper->process($input, $output, $options)->execute();
                $tempPaths[] = "{$output}.pdf"; // Simpan path PDF sementara
            }

            // Gabungkan semua PDF yang dihasilkan
            $pathMerged = 'files/radiologi/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$kunjungan;
            $outputMerged = storage_path().'/app/public/'.$pathMerged;
            $outputDirMerged = dirname($outputMerged);
            if (!File::exists($outputDirMerged)) {
                File::makeDirectory($outputDirMerged, 0755, true); // true = recursive
            }

            // SAVE TO DB
            $verify = klaim_file::where('nomor',$kunjungan)->where('jenis',7)->where('status',true)->first();
            if (!$verify) {
                $post = new klaim_file;
                $post->jenis = 7;
                $post->nomor = $kunjungan;
                $post->title = $kunjungan.'.pdf';
                $post->filename = $pathMerged.'.pdf';
                $post->status = true;
                $post->user = Auth::user()->ID;
                $post->save();
            }

            $pdf = new Fpdi();

            // Gabungkan setiap file PDF hasil proses untuk setiap PNOMOR
            foreach ($tempPaths as $file) {
                $pageCount = $pdf->setSourceFile($file);
                for ($page = 1; $page <= $pageCount; $page++) {
                    $tpl = $pdf->importPage($page);
                    $size = $pdf->getTemplateSize($tpl);
                    $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                    $pdf->useTemplate($tpl);
                }
            }

            // Simpan file PDF gabungan
            $pdf->Output('F', $outputMerged.'.pdf');
            $output = storage_path().'/app/public/files/radiologi/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$kunjungan;
            if (File::exists($output)) {
                File::deleteDirectory($output);
            }

            return response()->file($outputMerged.'.pdf',[
                'Content-Type' => 'application/pdf',
            ]);
        }

        function compileTriage($kunjungan)
        {
            $getData = DB::table('pendaftaran.kunjungan AS pk')
                            ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                            ->leftJoin('pendaftaran.penjamin AS pj','pp.NOMOR','=','pj.NOPEN')
                            ->leftJoin('medicalrecord.triage AS tr','tr.KUNJUNGAN','=','pk.NOMOR')
                            ->leftJoin('master.pasien AS ps','pp.NORM','=','ps.NORM')
                            ->select(
                                'pj.NOMOR AS NOSEP',
                                'pk.MASUK AS TANGGALMASUK',
                                'tr.ID AS PID',
                                DB::raw("
                                    CASE
                                        WHEN JSON_UNQUOTE(JSON_EXTRACT(tr.OBGYN, '$.USIA_GESTASI')) != ''
                                        OR JSON_UNQUOTE(JSON_EXTRACT(tr.OBGYN, '$.DETAK_JANTUNG')) != ''
                                        OR JSON_UNQUOTE(JSON_EXTRACT(tr.OBGYN, '$.DILATASI_SERVIKS')) != ''
                                        OR JSON_UNQUOTE(JSON_EXTRACT(tr.OBGYN, '$.KONTRAKSI_UTERUS')) != ''
                                        THEN tr.OBGYN
                                        ELSE NULL
                                    END AS OBGYN
                                "),
                                DB::raw('simrspku_klaim.getCariUmurTh(pp.TANGGAL, ps.TANGGAL_LAHIR) AS UMURPASIEN')
                            )
                            ->where('pk.NOMOR', $kunjungan)
                            ->where('tr.STATUS', 2)
                            ->first();
            $show = DB::select('CALL simrspku_klaim.CetakTriage(?)',[$getData->PID]);
            if ($show->isEmpty()) {
                return response()->json($data, 400);
            }
            // ----------------------------------------------------------------------
            $getTgl = Carbon::parse($getData->TANGGALMASUK);
            $tgl = $getTgl->isoFormat('DD');
            $bulan = $getTgl->isoFormat('MM');
            $tahun = $getTgl->isoFormat('YYYY');
            // ----------------------------------------------------------------------
            // VALIDASI UMUR
            if ($getData->OBGYN != null) {
                $input = public_path().'/doc/input/triage/CetakTriageObgyn.jrxml';
            } else {
                if ($getData->UMURPASIEN < 18) {
                    $input = public_path().'/doc/input/triage/CetakTriageAnak.jrxml';
                } else {
                    $input = public_path().'/doc/input/triage/CetakTriageDewasa.jrxml';
                }
            }

            $path = 'files/triage/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$kunjungan;
            $output = storage_path().'/app/public/'.$path;

            // SAVE TO DB
            $verify = klaim_file::where('nomor',$kunjungan)->where('jenis',8)->where('status',true)->first();
            if (!$verify) {
                $post = new klaim_file;
                $post->jenis = 5;
                $post->nomor = $kunjungan;
                $post->title = $kunjungan.'.pdf';
                $post->filename = $path.'.pdf';
                $post->status = true;
                $post->user = Auth::user()->ID;
                $post->save();
            }

            // Pastikan folder tujuan ada
            $outputDir = dirname($output);
            if (!File::exists($outputDir)) {
                File::makeDirectory($outputDir, 0755, true); // true = recursive
            }

            $options = [
                'format' => ['pdf'],
                'params' => [
                    'PID' => $getData->PID,
                    'IMAGES_PATH' => public_path()."/doc/input/triage/",
                ],
                'db_connection' => [
                    'driver'   => config('database.connections.db_custom.driver'),
                    'host'     => config('database.connections.db_custom.host'),
                    'port'     => config('database.connections.db_custom.port'),
                    'username' => config('database.connections.db_custom.username'),
                    'password' => config('database.connections.db_custom.password'),
                    'database' => config('database.connections.db_custom.database'),
                ],
            ];

            $jasper = new PHPJasper;

            $jasper->process(
                $input,
                $output,
                $options
            )->execute();

            return response()->file($output.'.pdf',[
                'Content-Type' => 'application/pdf',
            ]);
        }

        function compileOperasi($kunjungan)
        {
            $getSEP = DB::table('pendaftaran.kunjungan AS pk')
                    ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                    ->leftJoin('pendaftaran.penjamin AS pj','pp.NOMOR','=','pj.NOPEN')
                    ->select('pj.NOMOR AS NOSEP','pp.NOMOR AS NOPEN','pk.MASUK AS TANGGALMASUK')
                    ->where('pk.NOMOR',$kunjungan)
                    ->first();
            // $getSEP = DB::table('pendaftaran.kunjungan AS pk')
            //         ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
            //         // ->leftJoin('pendaftaran.penjamin AS pj','pp.NOMOR','=','pj.NOPEN')
            //         ->select('pp.NOMOR AS NOPEN')
            //         ->where('pk.NOMOR',$kunjungan)
            //         ->first();

            $getTgl = Carbon::parse($getSEP->TANGGALMASUK);
            $tgl = $getTgl->isoFormat('DD');
            $bulan = $getTgl->isoFormat('MM');
            $tahun = $getTgl->isoFormat('YYYY');
            // $tgl = '23';
            // $bulan = '05';
            // $tahun = '2023';

            $show = DB::table('pendaftaran.kunjungan AS pk')
                    ->leftJoin('pendaftaran.konsul AS ks','pk.REF','=','ks.NOMOR')
                    ->leftJoin('medicalrecord.operasi AS op','pk.NOMOR','=','op.KUNJUNGAN')
                    ->select('op.ID AS PID', 'op.KUNJUNGAN')
                    ->where('pk.NOPEN', $getSEP->NOPEN)
                    ->where('pk.RUANGAN', '=', '102080101')
                    ->get();

            if ($show->isEmpty()) {
                return response()->json($data, 400);
            }
            // print_r($show);
            // die();
            // Kelompokkan data berdasarkan PID dan gabungkan KUNJUNGAN dalam satu string
            $groupedData = $show->groupBy('PID')->map(function ($group) {
                return $group->pluck('KUNJUNGAN')->unique()->implode(',');
            });

            // Inisialisasi objek PHPJasper
            $jasper = new PHPJasper;

            // Tentukan path untuk input dan output file
            $input = public_path().'/doc/input/operasi/CetakLaporanOperasi.jrxml'; // Ganti dengan path file .jrxml yang sesuai
            $tempPaths = [];

            // Proses setiap PID
            foreach ($groupedData as $PID  => $KUNJUNGAN) {
                $path = 'files/operasi/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$kunjungan.'/laporan_'.$PID;
                $output = storage_path().'/app/public/'.$path;
                // $outputPath = storage_path("app/reports/laporan_{$PNOMOR}");

                $outputDir = dirname($output);
                if (!File::exists($outputDir)) {
                    File::makeDirectory($outputDir, 0755, true); // true = recursive
                }

                $options = [
                    'format' => ['pdf'],
                    'params' => [
                        'PID' => $PID,      // Kirim data PNOMOR ke report
                        'IMAGES_PATH' => public_path() . "/doc/input/operasi/",  // Ganti dengan path gambar jika ada
                    ],
                    'db_connection' => [
                        'driver'   => config('database.connections.db_custom.driver'),
                        'host'     => config('database.connections.db_custom.host'),
                        'port'     => config('database.connections.db_custom.port'),
                        'username' => config('database.connections.db_custom.username'),
                        'password' => config('database.connections.db_custom.password'),
                        'database' => config('database.connections.db_custom.database'),
                    ],
                ];

                // Proses JasperReport untuk setiap PNOMOR
                $jasper->process($input, $output, $options)->execute();
                $tempPaths[] = "{$output}.pdf"; // Simpan path PDF sementara
            }

            // Gabungkan semua PDF yang dihasilkan
            $pathMerged = 'files/operasi/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$kunjungan;
            $outputMerged = storage_path().'/app/public/'.$pathMerged;
            $outputDirMerged = dirname($outputMerged);
            if (!File::exists($outputDirMerged)) {
                File::makeDirectory($outputDirMerged, 0755, true); // true = recursive
            }

            // SAVE TO DB
            $verify = klaim_file::where('nomor',$kunjungan)->where('jenis',5)->where('status',true)->first();
            if (!$verify) {
                $post = new klaim_file;
                $post->jenis = 9;
                $post->nomor = $kunjungan;
                $post->title = $kunjungan.'.pdf';
                $post->filename = $pathMerged.'.pdf';
                $post->status = true;
                $post->user = Auth::user()->ID;
                $post->save();
            }

            $pdf = new Fpdi();

            // Gabungkan setiap file PDF hasil proses untuk setiap PNOMOR
            foreach ($tempPaths as $file) {
                $pageCount = $pdf->setSourceFile($file);
                for ($page = 1; $page <= $pageCount; $page++) {
                    $tpl = $pdf->importPage($page);
                    $size = $pdf->getTemplateSize($tpl);
                    $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                    $pdf->useTemplate($tpl);
                }
            }

            // Simpan file PDF gabungan
            $pdf->Output('F', $outputMerged.'.pdf');
            $output = storage_path().'/app/public/files/operasi/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$kunjungan;
            if (File::exists($output)) {
                File::deleteDirectory($output);
            }

            return response()->file($outputMerged.'.pdf',[
                'Content-Type' => 'application/pdf',
            ]);
        }

        function cleanText($text) {
            $allowedTags = '<br><b><i><u>';
            $text = strip_tags($text, $allowedTags);
            return str_replace("\n", "<br>", $text);
        }

        function compile()
        {
            $data = [
                [
                    "NAMA" => "Yussuf Faisal",
                ]
            ];

            // 1. Simpan sebagai JSON
            $jsonPath = public_path().'/doc/input/ujicoba.json';
            file_put_contents($jsonPath, json_encode($data));

            // 2. Jalankan Jasper
            $jasper = new PHPJasper;
            $input = public_path().'/doc/input/ujicoba.jrxml';
            $output = public_path().'/doc/input/outputujicoba';

            $options = [
                'format' => ['pdf'],
                'params' => [
                    'REPORT_LOCALE' => 'id_ID',
                ],
                'data_file' => $jsonPath,
                'db_connection' => false,
            ];

            // dd(file_get_contents($jsonPath));
            $jasper->process($input, $output, $options)->execute();
            return response()->file($output . '.pdf');

        }

}
