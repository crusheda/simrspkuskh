<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\simrspku_klaim\klaim_file;
use App\Models\simrspku_klaim\klaim_verifikasi;
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

        // print_r($dpjp);
        // die();

        $time = Carbon::now()->isoFormat('YYYY-MM-DD HH:mm:ss');

        // SUB QUERY FROM ANY TABEL
        // $subTindakan = DB::table(DB::raw('
        //     (
        //         SELECT *,
        //             ROW_NUMBER() OVER (PARTITION BY KUNJUNGAN ORDER BY TANGGAL DESC) AS rn
        //         FROM layanan.tindakan_medis
        //         WHERE STATUS = 1
        //     ) AS td
        // '))->where('td.rn', 1); // hanya baris TINDAKAN terakhir per kunjungan
        // $subCppt = DB::table(DB::raw('
        //     (
        //         SELECT *,
        //             ROW_NUMBER() OVER (PARTITION BY KUNJUNGAN ORDER BY TANGGAL DESC) AS rn
        //         FROM medicalrecord.cppt
        //         WHERE STATUS = 1
        //     ) AS cp
        // '))->where('cp.rn', 1); // hanya baris CPPT terakhir per kunjungan
        // $subTTD = DB::table(DB::raw('
        //     (
        //         SELECT *,
        //             ROW_NUMBER() OVER (PARTITION BY KUNJUNGAN ORDER BY created_at DESC) AS rn
        //         FROM simrspku_klaim.tanda_tangan
        //         WHERE deleted_at IS null
        //     ) AS ttd
        // '))->where('ttd.rn', 1); // hanya baris TTD terakhir per kunjungan
        // $subCatatan = DB::table(DB::raw('
        //     (
        //         SELECT *,
        //             ROW_NUMBER() OVER (PARTITION BY nomor ORDER BY created_at DESC) AS rn
        //         FROM simrspku_klaim.klaim_verifikasi_catatan
        //         WHERE deleted_at IS null AND status = 1
        //     ) AS cat
        // '))->where('cat.rn', 1);

        // ===============================
        // TINDAKAN TERAKHIR
        // ===============================
        $subTindakan = DB::table('layanan.tindakan_medis')
            ->select(
                'KUNJUNGAN',
                DB::raw('MAX(TANGGAL) AS TANGGAL')
            )
            ->where('STATUS', 1)
            ->groupBy('KUNJUNGAN');

        // ===============================
        // CPPT TERAKHIR
        // ===============================
        $subCppt = DB::table('medicalrecord.cppt')
            ->select(
                'KUNJUNGAN',
                DB::raw('MAX(TANGGAL) AS TANGGAL')
            )
            ->where('STATUS', 1)
            ->groupBy('KUNJUNGAN');

        // ===============================
        // TANDA TANGAN TERAKHIR
        // ===============================
        $subTTD = DB::table('simrspku_klaim.tanda_tangan')
            ->select(
                'KUNJUNGAN',
                DB::raw('MAX(created_at) AS created_at')
            )
            ->whereNull('deleted_at')
            ->groupBy('KUNJUNGAN');

        // ===============================
        // CATATAN TERAKHIR
        // ===============================
        $lastCatatan = DB::table('simrspku_klaim.klaim_verifikasi_catatan')
            ->select(
                'nomor',
                DB::raw('MAX(created_at) AS created_at')
            )
            ->whereNull('deleted_at')
            ->where('status',1)
            ->groupBy('nomor');
        $subCatatan = DB::table('simrspku_klaim.klaim_verifikasi_catatan as cat')
            ->joinSub($lastCatatan,'last',function($join){

                $join->on('cat.nomor','=','last.nomor')
                    ->on('cat.created_at','=','last.created_at');

            })
            ->select(
                'cat.nomor',
                'cat.created_at AS updated_at',
                'cat.solved',
                'cat.status'
            );

        // ===============================
        // STATUS CATATAN
        // ===============================
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
                    'pj.JENIS AS JENISPENJAMIN',
                    'ref.DESKRIPSI AS NAMAPENJAMIN',
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
                ->join('master.referensi AS ref', function($join){
                    $join->on('ref.ID','=','pj.JENIS')
                        ->where('ref.STATUS', 1)
                        ->where('ref.JENIS', 10);
                })
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
                    // $prefix = '';
                    // switch ($rawat) {
                    //     case 1:
                    //         $prefix = '1020101%'; // RAJAL
                    //         break;
                    //     case 2:
                    //         $prefix = '1020201%'; // RADAR
                    //         break;
                    //     case 3:
                    //         $prefix = '1020301%'; // RANAP
                    //         break;
                    // }
                    // $query->where('pk.RUANGAN', 'LIKE', $prefix);
                    $map = [
                        1 => ['1020101%', '1020702%'], // RAJAL (dengan REHAB MEDIK)
                        2 => ['1020201%'],            // RADAR
                        3 => ['1020301%'],            // RANAP
                    ];

                    $query->where(function ($q) use ($map, $rawat) {
                        foreach ($map[$rawat] as $prefix) {
                            $q->orWhere('pk.RUANGAN', 'LIKE', $prefix);
                        }
                    });
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
            $getData = DB::table('pendaftaran.kunjungan AS pk')
                        ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                        ->select('pk.NOMOR AS NOMOR', 'pp.NOMOR AS NOPEN','pk.RUANGAN','pp.NORM','pp.TANGGAL')
                        ->where('pk.NOMOR',$kunjungan)
                        ->first();

            // $show = DB::select('CALL simrspku_klaim.RencanaKontrolCustom(?)',[$kunjungan]);

            if (!$getData) {
                return response()->json('Kunjungan Pasien Tidak Ditemukan', 400);
            }

            // $getSKDP = DB::select('CALL simrspku_klaim.CariSKDP(?)',[$getData->NOMOR]);

            $getSKDP = DB::table('pendaftaran.penjamin AS pj') // AMBIL DATA BERDASARKAN RIWAYAT NO.SURKON PADA KUNJUNGAN SEBELUMNYA (DAFTAR PASIENNYA LEWAT QRCODE SURKON)
                            ->join('medicalrecord.jadwal_kontrol AS jk','jk.NOMOR_REFERENSI','=','pj.NO_SURAT')
                            ->join('pendaftaran.kunjungan AS pk','pk.NOMOR','=','jk.KUNJUNGAN')
                            ->select(
                                'pj.NOPEN',
                                'pj.NO_SURAT',
                                'jk.KUNJUNGAN',
                                'pk.NOMOR'
                            )
                            ->where('pj.NOPEN', $getData->NOPEN)
                            ->whereNotNull('pj.NO_SURAT')
                            ->where('pj.NO_SURAT', '!=', '')
                            ->first();

            if (!$getSKDP) { // AMBIL DATA KUNJUNGAN DARI KUNJUNGAN PASIEN SEBELUMNYA (DENGAN RUANGAN YANG SAMA)
                $getSKDP = DB::table('pendaftaran.pendaftaran AS pp')
                            ->join('pendaftaran.kunjungan AS pk', function($join) use ($getData) {
                                $join->on('pp.NOMOR', '=', 'pk.NOPEN')
                                    ->where('pk.RUANGAN', $getData->RUANGAN)
                                    // ->where('pk.RUANGAN', 'LIKE', '10203%')
                                    ->whereIn('pk.STATUS', [1,2,3]);
                            })
                            //tambahan cek tanggal
                            ->leftJoin('medicalrecord.jadwal_kontrol AS jk', function($join) {
                                $join->on('jk.KUNJUNGAN', '=', 'pk.NOMOR')
                                    ->where('jk.STATUS', '!=', 0);
                            })
                            ->select('pk.NOMOR','pk.RUANGAN','pp.TANGGAL', 'jk.TANGGAL AS TANGGAL_SKDP')
                            ->where('pp.NORM', $getData->NORM)
                            ->whereIn('pp.STATUS', [1,2])
                            ->where('pp.TANGGAL', '<', $getData->TANGGAL)
                            ->orderBy('pp.TANGGAL', 'desc')
                            ->first();

                $tgl_kunjungan = Carbon::parse($getData->TANGGAL)->format('Y-m-d');
                // print_r($getSKDP);
                // die();

                // Jika rawat inap, cek lagi apakah ada kunjungan rawat inap setelah kunjungan baseline
                // if ($getSKDP) {
                if ($getSKDP && !empty($getSKDP->TANGGAL_SKDP) && Carbon::parse($getSKDP->TANGGAL_SKDP)->format('Y-m-d') != $tgl_kunjungan) {
                    $lanjutan = DB::table('pendaftaran.pendaftaran AS pp')
                            ->join('pendaftaran.kunjungan AS pk', function($join) use ($getData) {
                                $join->on('pp.NOMOR', '=', 'pk.NOPEN')
                                    ->where('pk.RUANGAN', $getData->RUANGAN)
                                    // ->where('pk.RUANGAN', 'LIKE', '10203%')
                                    ->whereIn('pk.STATUS', [1,2,3]);
                            })
                            //tambahan cek tanggal
                            ->join('medicalrecord.jadwal_kontrol AS jk', function($join) {
                                $join->on('jk.KUNJUNGAN', '=', 'pk.NOMOR')
                                    ->where('jk.STATUS', '!=', 0);
                            })
                            ->select('pk.NOMOR','pk.RUANGAN','pp.TANGGAL', 'jk.TANGGAL AS TANGGAL_SKDP')
                            ->where('pp.NORM', $getData->NORM)
                            ->whereIn('pp.STATUS', [1,2])
                            ->where('jk.TANGGAL', '=', $tgl_kunjungan)
                            ->orderBy('jk.TANGGAL', 'desc')
                            ->first();
                            // print_r($lanjutan);
                            // die();
                    if ($lanjutan) {
                        $getSKDP = $lanjutan; // override pakai lanjutan
                    }
                }
                if ($getSKDP && !empty($getSKDP->TANGGAL_SKDP) && Carbon::parse($getSKDP->TANGGAL_SKDP)->format('Y-m-d') != $tgl_kunjungan) {
                    $rawatInap = DB::table('pendaftaran.pendaftaran AS pp')
                        ->join('pendaftaran.kunjungan AS pk', function($join) {
                            $join->on('pp.NOMOR', '=', 'pk.NOPEN')
                                ->where('pk.RUANGAN', 'LIKE', '10203%') // ✅ cek rawat inap
                                ->whereIn('pk.STATUS', [1,2,3]);
                        })
                        ->select('pk.NOMOR','pk.RUANGAN','pp.TANGGAL')
                        ->where('pp.NORM', $getData->NORM)
                        ->whereIn('pp.STATUS', [1,2])
                        ->where('pp.TANGGAL', '>=', $getSKDP->TANGGAL) // setelah kunjungan baseline
                        ->orderBy('pp.TANGGAL', 'asc')
                        ->first();

                    if ($rawatInap) {
                        $getSKDP = $rawatInap; // override pakai rawat inap
                    }
                }
            }

            if (!$getSKDP) { // JIKA SKDP memang tidak ditemukan di DB, ada kemungkinan pasien didaftar tanpa menggunakan /menghubungkan SKDP, di menu 'Ubah Penjamin'
                return response()->json('SKDP Tidak Ditemukan atau Belum Diterbitkan, pastikan Pendaftaran Pasien telah terhubung dengan Surat Kontrol (SKDP) sebelumnya di menu Ubah Penjamin Simgos.', 400);
            }

            // print_r($getData->NORM.' - '.$getData->NOMOR.' - '.$getData->TANGGAL);

            $cetakSKDP = DB::select('CALL simrspku_klaim.RencanaKontrolCustom(?)',[$getSKDP->NOMOR]);

            if (empty($cetakSKDP) || !isset($cetakSKDP[0])) {
                return response()->json([
                    'message' => 'Data SKDP untuk kunjungan ini tidak ditemukan saat proses cetak SKDP, pastikan SKDP sudah diterbitkan dengan benar pada kunjungan sebelumnya.'
                ], 400);
            }

            // ----------------------------------------------------------------------
            $getTgl = Carbon::parse($getData->TANGGAL);
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

            if (empty($skdp)) {
                return response()->json([
                    'message' => 'Nomor Surat SKDP tidak ditemukan untuk kunjungan pasien ini, pastikan SKDP sudah diterbitkan dengan benar pada kunjungan sebelumnya dan nomor suratnya sudah terisi dengan benar di SIMRS'
                ], 404);
            }
            $skdp = trim($skdp); // Hapus spasi di awal/akhir jika ada

            // Generate QR code PNG base64 (bukan data:image/png;base64,... hanya base64 murni)
            $image = $generator->getBarcodePNG($skdp, 'QRCODE');

            // Decode base64 jadi binary PNG
            $decodedImage = base64_decode($image);
            $token = Crypt::encrypt($skdp);
            $titleQrcode = Crypt::encrypt($skdp).'.png';
            $verif = klaim_qrcode_pegawai::where('nomor',$skdp)->first();

            // Simpan ke file storage Laravel (storage/app/public/files/qrcode{nip}.png)
            $pathQrcode = 'files/qrcode/' . $titleQrcode;
            $outputQrcode = storage_path('app/public/' . $pathQrcode);
            $outputQrcodeMerged = dirname($outputQrcode);
            if (!File::exists($outputQrcodeMerged)) {
                File::makeDirectory($outputQrcodeMerged, 0755, true); // true = recursive
            }

            // SAVE TO DB
            if (!$verif) {
                file_put_contents($outputQrcode, $decodedImage);
                $post = new klaim_qrcode_pegawai;
                $post->token = $token;
                $post->nomor = $skdp;
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

            if (empty($skdp2)) {
                return response()->json([
                    'message' => 'Nomor BPJS tidak ditemukan pada kunjungan pasien ini, pastikan nomor BPJS sudah terdaftar dan terhubung dengan benar pada pendaftaran pasien'
                ], 404);
            }

            // Generate QR code PNG base64 (bukan data:image/png;base64,... hanya base64 murni)
            $image2 = $generator2->getBarcodePNG($skdp2, 'QRCODE');
            // print_r($generator);
            // die();

            // Decode base64 jadi binary PNG
            $decodedImage2 = base64_decode($image2);
            $token2 = Crypt::encrypt($skdp2);
            $titleQrcode2 = Crypt::encrypt($skdp2).'.png';
            $verif2 = klaim_qrcode::where('nomor',$skdp2)->where('jenis',3)->first();

            // Simpan ke file storage Laravel (storage/app/public/files/qrcode{nip}.png)
            $pathQrcode2 = 'files/qrcode/' . $titleQrcode2;
            $outputQrcode2 = storage_path('app/public/' . $pathQrcode2);

            // SAVE TO DB
            if (!$verif2) {
                file_put_contents($outputQrcode2, $decodedImage2);
                $post = new klaim_qrcode;
                $post->token = $token2;
                $post->jenis = 3;
                $post->nomor = $skdp2;
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
                    'PKUNJUNGAN' => $getSKDP->NOMOR,
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
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma'        => 'no-cache',
                'Expires'       => '0',
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

            // $show = DB::select('CALL simrspku_klaim.CetakSEP(?)',[$getSEP->NOSEP]);
            $show = DB::table('bpjs.kunjungan as k')
                    ->selectRaw("
                        p.noKartu as NOMORKARTU,
                        DATE_FORMAT(k.tglSEP,'%d-%m-%Y') TGLSEP
                    ")
                    ->leftJoin('bpjs.peserta as p', 'k.noKartu', '=', 'p.noKartu')
                    ->where('k.cetak', 0)
                    ->where('k.noSEP', '=', $getSEP->NOSEP)
                    ->groupBy('k.noSEP')
                    ->first();

            if (!$show || !$getSEP->NOSEP) {
                return response()->json($getSEP, 400);
            }
            // ----------------------------------------------------------------------
            $getTgl = Carbon::parse($show->TGLSEP);
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
            $sep = $show->NOMORKARTU;

            // Generate QR code PNG base64 (bukan data:image/png;base64,... hanya base64 murni)
            $image = $generator->getBarcodePNG($sep, 'QRCODE');
            // print_r($generator);
            // die();

            // Decode base64 jadi binary PNG
            $decodedImage = base64_decode($image);
            $token = Crypt::encrypt($show->NOMORKARTU);
            $titleQrcode = Crypt::encrypt($show->NOMORKARTU).'.png';
            $verif = klaim_qrcode_pegawai::where('nomor',$show->NOMORKARTU)->first();

            // Simpan ke file storage Laravel (storage/app/public/files/qrcode{nip}.png)
            $pathQrcode = 'files/qrcode/' . $titleQrcode;
            $outputQrcode = storage_path('app/public/' . $pathQrcode);
            $outputQrcodeMerged = dirname($outputQrcode);
            if (!File::exists($outputQrcodeMerged)) {
                File::makeDirectory($outputQrcodeMerged, 0755, true); // true = recursive
            }

            // SAVE TO DB
            if (!$verif) {
                file_put_contents($outputQrcode, $decodedImage);
                $post = new klaim_qrcode_pegawai;
                $post->token = $token;
                $post->nomor = $show->NOMORKARTU;
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
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma'        => 'no-cache',
                'Expires'       => '0',
            ]);
        }

        //TTD RESUME
        function showTtdResumeRj($kunjungan)
        {
            $getRESUMERJ = DB::table('pendaftaran.kunjungan AS pk')
                ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                ->leftJoin('pendaftaran.penjamin AS pj','pp.NOMOR','=','pj.NOPEN')
                ->select('pj.NOMOR AS NOSEP','pp.NOMOR AS NOPEN','pk.NOMOR AS NOMOR','pk.RUANGAN AS RUANGAN','pk.DPJP','pp.TANGGAL AS TGLPERIKSA')
                ->where('pk.NOMOR',$kunjungan)
                ->first();

            $show = DB::select('CALL simrspku_klaim.CetakResumeRJ(?,?)',[$getRESUMERJ->NOPEN,$getRESUMERJ->NOMOR]);

            $isExist = false; // default false

            if ($show) {
                $getTgl = Carbon::parse($show[0]->TGLPERIKSA);
                $tgl = $getTgl->isoFormat('DD');
                $bulan = $getTgl->isoFormat('MM');
                $tahun = $getTgl->isoFormat('YYYY');

                $path = 'files/resume/RJ/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$kunjungan;
                $output = storage_path('app/public/'.$path.'.pdf');

                if (file_exists($output)) {
                    // cek di DB, termasuk soft deleted atau status = 0
                    $verify = klaim_file::withTrashed()
                        ->where('nomor', $kunjungan)
                        ->where('jenis', 2)
                        ->first();

                    if (!$verify || !$verify->status) {
                        // hapus file karena database sudah tidak valid
                        @unlink($output);
                        DB::table('simrspku_klaim.tanda_tangan')
                            ->where('kunjungan', $kunjungan)
                            ->whereNull('deleted_at')
                            ->update(['deleted_at' => now()]);
                        $isExist = false;
                    } else {
                        // file valid, simpan jika belum ada
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
                    }
                } else {
                    $isExist = false;
                }
            }

            $data = [
                'show' => $show,
                'resume' => $getRESUMERJ,
                'isExist' => $isExist,
            ];

            return response()->json($data, 200);
        }

        public function storeTtdResumeRj(Request $request)
        {
            $existing = DB::table('simrspku_klaim.tanda_tangan')
                ->where('kunjungan', $request->nama)
                ->whereNull('deleted_at')
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
                'kunjungan' => $request->nama
            ]);
        }

        // function compileResumeRjjj($kunjungan)
        // {
        //     $getRESUMERJ = DB::table('pendaftaran.kunjungan AS pk')
        //         ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
        //         ->leftJoin('pendaftaran.penjamin AS pj','pp.NOMOR','=','pj.NOPEN')
        //         ->leftJoin('master.ruangan AS ru','ru.ID','=','pk.RUANGAN')
        //         ->leftJoin('master.dokter AS dr','dr.ID','=','pk.DPJP')
        //         ->select('pj.NOMOR AS NOSEP','pp.NOMOR AS NOPEN','pk.NOMOR AS NOMOR','pk.RUANGAN AS RUANGAN','pk.DPJP','dr.NIP AS NIPDOKTER','pp.TANGGAL AS TGLPERIKSA')
        //         ->where('pk.NOMOR',$kunjungan)
        //         ->first();

        //     // $show = DB::select('CALL simrspku_klaim.CetakResumeRJ(?,?)',[$getRESUMERJ->NOPEN,$getRESUMERJ->NOMOR]);
        //     // $obat = DB::select('CALL simrspku_klaim.CetakObatRJ(?)',[$getRESUMERJ->NOPEN]);

        //     // if (empty($show)) {
        //     //     return response()->json($data, 400);
        //     // }
        //     // $keluhan    = $this->cleanText($show[0]->KELUHAN);
        //     // $assesment  = $this->cleanText($show[0]->ASSESMENT);
        //     // $subyektif  = $this->cleanText($show[0]->SUBYEKTIF);
        //     // $obyektif   = $this->cleanText($show[0]->OBYEKTIF);
        //     // $planning   = $this->cleanText($show[0]->PLANNING);
        //     // $instruksi  = $this->cleanText($show[0]->INSTRUKSI);

        //     // $NAMA_OBAT = collect($obat)->pluck('NAMAOBAT')->implode(', ');
        //     $konsul = DB::table('pendaftaran.konsul as kon')
        //             ->where('kon.KUNJUNGAN',$kunjungan)
        //             ->where('kon.STATUS','!=','0')
        //             ->get();
        //     // print_r($konsul);
        //     // die();
        //     // ----------------------------------------------------------------------
        //     $ttd = DB::table('simrspku_klaim.tanda_tangan AS ttd')
        //         ->where('ttd.kunjungan',$kunjungan)
        //         ->whereNull('deleted_at')
        //         ->first();
        //     if ($ttd) {
        //         $imagePath2 = storage_path()."/app/public/".$ttd->signature_path;
        //     } else {
        //         $getIDUser = DB::table('master.dokter AS dr')
        //                         ->leftJoin('aplikasi.pengguna AS pe','pe.NIP','=','dr.NIP')
        //                         ->select('pe.ID')
        //                         ->where('dr.ID',$getRESUMERJ->DPJP)
        //                         ->where('dr.STATUS',1)
        //                         ->first();
        //         if (str_starts_with($getRESUMERJ->RUANGAN, '1020702')) { // Khusus Rehab Medik
        //             $getTtd = DB::table('simrspku_klaim.tanda_tangan_pegawai as ttp')
        //                 ->where('ttp.nip', $getRESUMERJ->NIPDOKTER)
        //                 ->where('status', 1)
        //                 ->inRandomOrder()
        //                 ->first();
        //         } else {
        //             $getTtd = DB::table('simrspku_klaim.tanda_tangan AS ttd')
        //                             ->where('ttd.user',$getIDUser->ID)
        //                             ->whereNull('deleted_at')
        //                             ->first();
        //         }
        //         if ($getTtd) {
        //             $imagePath2 = storage_path()."/app/public/".$getTtd->signature_path;
        //             DB::table('simrspku_klaim.tanda_tangan')->insert([
        //                 'kunjungan' => $kunjungan,
        //                 'signature_path' => $getTtd->signature_path,
        //                 'created_at' => Carbon::now(),
        //                 'updated_at' => Carbon::now(),
        //                 'user' => Auth::user()->ID,
        //             ]);
        //         } else {
        //             $imagePath2 = null;
        //         }
        //     }

        //     // ----------------------------------------------------------------------
        //     $getTgl = Carbon::parse($getRESUMERJ->TGLPERIKSA);
        //     $tgl = $getTgl->isoFormat('DD');
        //     $bulan = $getTgl->isoFormat('MM');
        //     $tahun = $getTgl->isoFormat('YYYY');

        //     // ----------------------------------------------------------------------
        //     $path = 'files/resume/RJ/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$kunjungan;
        //     $output = storage_path().'/app/public/'.$path;

        //     // cek di DB
        //     $verify = klaim_file::where('nomor', $kunjungan)
        //         ->where('jenis', 2)
        //         ->where('status', true)
        //         ->first();

        //     // if (file_exists($output.'.pdf')) {
        //     //     if (!$verify) {
        //     //         $post = new klaim_file;
        //     //         $post->jenis = 2;
        //     //         $post->nomor = $kunjungan;
        //     //         $post->title = $kunjungan.'.pdf';
        //     //         $post->filename = $path.'.pdf';
        //     //         $post->status = true;
        //     //         $post->user = Auth::user()->ID;
        //     //         $post->save();
        //     //     }

        //     //     return response()->file($output.'.pdf',[
        //     //         'Content-Type' => 'application/pdf',
        //     //     ]);
        //     // }

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

        //     // Pastikan folder tujuan ada
        //     $outputDir = dirname($output);
        //     if (!File::exists($outputDir)) {
        //         File::makeDirectory($outputDir, 0755, true); // true = recursive
        //     }

        //     // print_r($getRESUMERJ);
        //     // die();

        //     // ----------------------------------------------------------------------
        //     if (str_starts_with($getRESUMERJ->RUANGAN, '1020201')) {
        //         $input = public_path().'/doc/input/resumeRD/CetakResumeRadar.jrxml';
        //         $options = [
        //             'format' => ['pdf'],
        //             'params' => [
        //                 'PNOPEN' => $getRESUMERJ->NOPEN,
        //                 'IMAGES_PATH' => public_path()."/doc/input/resumeRD/",
        //                 'IMAGES_PATH2' => $imagePath2,
        //             ],
        //             'db_connection' => [
        //                 'driver'   => config('database.connections.db_custom.driver'),
        //                 'host'     => config('database.connections.db_custom.host'),
        //                 'port'     => config('database.connections.db_custom.port'),
        //                 'username' => config('database.connections.db_custom.username'),
        //                 'password' => config('database.connections.db_custom.password'),
        //                 'database' => config('database.connections.db_custom.database'),
        //             ],
        //             // 'db_connection' => [
        //             //     'driver'   => 'mysql',
        //             //     'host'     => env('DB_HOST'),
        //             //     'port'     => env('DB_PORT'),
        //             //     'username' => env('DB_USERNAME'),
        //             //     'password' => env('DB_PASSWORD'),
        //             //     'database' => env('DB_DATABASE_CUSTOM'),
        //             // ],
        //         ];
        //     } else {
        //         $input = public_path().'/doc/input/resumeRJ/CetakResumeRJ.jrxml';
        //         $options = [
        //             'format' => ['pdf'],
        //             'params' => [
        //                 'PNOPEN' => $getRESUMERJ->NOPEN,
        //                 'PKUNJUNGAN' => $getRESUMERJ->NOMOR,
        //                 'COBA' => 'KRINCING RT 14/06 KRINCING RT. 0 RW. 0 Kel/Desa. KRINCING Kec. INI HANYA CONTOH. KRINCING RT 14/06 KRINCING RT. 0 RW. 0 Kel/Desa. KRINCING Kec. SELESAIIIIIII',
        //                 'IMAGES_PATH' => public_path()."/doc/input/resumeRJ/",
        //                 'IMAGES_PATH2' => $imagePath2,
        //             ],
        //             'db_connection' => [
        //                 'driver'   => config('database.connections.db_custom.driver'),
        //                 'host'     => config('database.connections.db_custom.host'),
        //                 'port'     => config('database.connections.db_custom.port'),
        //                 'username' => config('database.connections.db_custom.username'),
        //                 'password' => config('database.connections.db_custom.password'),
        //                 'database' => config('database.connections.db_custom.database'),
        //             ],
        //             // 'db_connection' => [
        //             //     'driver'   => 'mysql',
        //             //     'host'     => env('DB_HOST'),
        //             //     'port'     => env('DB_PORT'),
        //             //     'username' => env('DB_USERNAME'),
        //             //     'password' => env('DB_PASSWORD'),
        //             //     'database' => env('DB_DATABASE_CUSTOM'),
        //             // ],
        //         ];
        //     }

        //     // print_r($options);
        //     // die();

        //     $jasper = new PHPJasper;

        //     $jasper->process(
        //         $input,
        //         $output,
        //         $options
        //     )->execute();

        //     return response()->file($output.'.pdf',[
        //         'Content-Type' => 'application/pdf',
        //         'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        //         'Pragma'        => 'no-cache',
        //         'Expires'       => '0',
        //     ]);
        // }

        function compileResumeRj($kunjungan)
        {
            $getRESUMERJ = DB::table('pendaftaran.kunjungan AS pk')
                ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                ->leftJoin('pendaftaran.penjamin AS pj','pp.NOMOR','=','pj.NOPEN')
                ->leftJoin('master.ruangan AS ru','ru.ID','=','pk.RUANGAN')
                ->leftJoin('master.dokter AS dr','dr.ID','=','pk.DPJP')
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
                ->where('pk.NOMOR',$kunjungan)
                ->first();

            if (!$getRESUMERJ) {
                return response()->json(['message'=>'Data Resume Utama tidak ditemukan'],404);
            }

            /*
            |--------------------------------------------------------------------------
            | Ambil Data Konsul
            |--------------------------------------------------------------------------
            */
            $konsul = DB::table('pendaftaran.konsul as kon')
                ->leftJoin('pendaftaran.kunjungan as pk','pk.REF','=','kon.NOMOR')
                ->leftJoin('master.dokter as md','md.ID','=','pk.DPJP')
                ->select('pk.NOMOR AS KUNJUNGAN', 'pk.DPJP AS DPJP', 'pk.RUANGAN AS RUANGAN', DB::raw('master.getNamaLengkapPegawai(md.NIP) AS NAMADOKTER'), 'md.NIP AS NIPDOKTER')
                ->where('kon.KUNJUNGAN',$kunjungan)
                ->where('kon.STATUS','!=','0')
                ->get();
            /*
            |--------------------------------------------------------------------------
            | Ambil TTD
            |--------------------------------------------------------------------------
            */
            $ttd = DB::table('simrspku_klaim.tanda_tangan AS ttd')
                ->where('ttd.kunjungan',$kunjungan)
                ->whereNull('ttd.deleted_at')
                ->orderBy('ttd.id','desc')
                ->first();

            $imagePath2 = null;

            if ($ttd) {
                if (Str::startsWith($ttd->signature_path, 'signatures/pegawai')) {
                    // TTD PEGAWAI
                    $imagePath2 = storage_path()."/app/public/".$ttd->signature_path;
                }
            }

            $ruanganKhusus = ['1020702', '102010103'];

            $isKhusus = false;

            foreach ($ruanganKhusus as $ruangan) {
                if (str_starts_with($getRESUMERJ->RUANGAN, $ruangan)) {
                    $isKhusus = true;
                    break;
                }
            }

            if ($imagePath2 == null) {
                $getIDUser = DB::table('master.dokter AS dr')
                    ->leftJoin('aplikasi.pengguna AS pe','pe.NIP','=','dr.NIP')
                    ->select('pe.ID')
                    ->where('dr.ID',$getRESUMERJ->DPJP)
                    ->where('dr.STATUS',1)
                    ->first();

                if ($isKhusus) { // Khusus Rehab Medik & Poli Tertentu
                    $getTtd = DB::table('simrspku_klaim.tanda_tangan_pegawai as ttp')
                        ->where('ttp.nip', $getRESUMERJ->NIPDOKTER)
                        ->where('ttp.status', 1)
                        ->inRandomOrder()
                        ->first();
                } else {
                    $getTtd = DB::table('simrspku_klaim.tanda_tangan AS ttd')
                                    ->where('ttd.user',$getIDUser->ID)
                                    ->whereNull('ttd.deleted_at')
                                    ->first();
                }

                if ($getTtd) {
                    $imagePath2 = storage_path()."/app/public/".$getTtd->signature_path;

                    // DB::table('simrspku_klaim.tanda_tangan')->insert([
                    //     'kunjungan' => $kunjungan,
                    //     'signature_path' => $getTtd->signature_path,
                    //     'created_at' => Carbon::now(),
                    //     'updated_at' => Carbon::now(),
                    //     'user' => Auth::user()->ID,
                    // ]);
                } else {
                    return response()->json([
                        'message' => "Tanda tangan Dokter DPJP ({$getRESUMERJ->NAMADOKTER}) pada Resume Utama tidak ditemukan."
                    ], 404);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Path & Folder
            |--------------------------------------------------------------------------
            */
            $getTgl = Carbon::parse($getRESUMERJ->TGLPERIKSA);
            $tgl = $getTgl->isoFormat('DD');
            $bulan = $getTgl->isoFormat('MM');
            $tahun = $getTgl->isoFormat('YYYY');

            $path = 'files/resume/RJ/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$kunjungan;
            $output = storage_path().'/app/public/'.$path;

            if (!File::exists(dirname($output))) {
                File::makeDirectory(dirname($output),0755,true);
            }

            /*
            |--------------------------------------------------------------------------
            | Tentukan Template
            |--------------------------------------------------------------------------
            */
            if (str_starts_with($getRESUMERJ->RUANGAN, '1020201')) {
                $input = public_path().'/doc/input/resumeRD/CetakResumeRadar.jrxml';
                $baseParams = [
                    'PNOPEN' => $getRESUMERJ->NOPEN,
                    'IMAGES_PATH' => public_path()."/doc/input/resumeRD/",
                    'IMAGES_PATH2' => $imagePath2,
                ];
            } else {
                $input = public_path().'/doc/input/resumeRJ/CetakResumeRJ.jrxml';
                $baseParams = [
                    'PNOPEN' => $getRESUMERJ->NOPEN,
                    'PKUNJUNGAN' => $getRESUMERJ->NOMOR,
                    'IMAGES_PATH' => public_path()."/doc/input/resumeRJ/",
                    'IMAGES_PATH2' => $imagePath2,
                ];
            }

            $jasper = new PHPJasper;
            $tempPaths = [];

            /*
            |--------------------------------------------------------------------------
            | 1️⃣ Generate Resume Utama
            |--------------------------------------------------------------------------
            */
            $mainOutput = $output.'_main';

            $jasper->process(
                $input,
                $mainOutput,
                [
                    'format'=>['pdf'],
                    'params'=>$baseParams,
                    'db_connection' => [
                        'driver'   => config('database.connections.db_custom.driver'),
                        'host'     => config('database.connections.db_custom.host'),
                        'port'     => config('database.connections.db_custom.port'),
                        'username' => config('database.connections.db_custom.username'),
                        'password' => config('database.connections.db_custom.password'),
                        'database' => config('database.connections.db_custom.database'),
                    ],
                ]
            )->execute();

            $tempPaths[] = $mainOutput.'.pdf';


            /*
            |--------------------------------------------------------------------------
            | 2️⃣ Generate Resume Konsul (Jika Ada)
            |--------------------------------------------------------------------------
            */
            if (!$konsul->isEmpty()) {

                foreach ($konsul as $item) {

                    $konsulOutput = $output.'_konsul_'.$item->KUNJUNGAN;

                    $paramsKonsul = $baseParams;

                    if (isset($paramsKonsul['PKUNJUNGAN'])) {
                        $paramsKonsul['PKUNJUNGAN'] = $item->KUNJUNGAN;

                        $ttd = DB::table('simrspku_klaim.tanda_tangan AS ttd')
                            ->where('ttd.kunjungan',$item->KUNJUNGAN)
                            ->whereNull('ttd.deleted_at')
                            ->first();

                        if ($ttd) {
                            $imagePath2 = storage_path()."/app/public/".$ttd->signature_path;
                        } else {

                            $getIDUser = DB::table('master.dokter AS dr')
                                ->leftJoin('aplikasi.pengguna AS pe','pe.NIP','=','dr.NIP')
                                ->select('pe.ID')
                                ->where('dr.ID',$item->DPJP)
                                ->where('dr.STATUS',1)
                                ->first();

                            if ($isKhusus) { // Khusus Rehab Medik dan Poli Tertentu
                                $getTtd = DB::table('simrspku_klaim.tanda_tangan_pegawai as ttp')
                                    ->where('ttp.nip', $item->NIPDOKTER)
                                    ->where('ttp.status', 1)
                                    ->inRandomOrder()
                                    ->first();
                            } else {
                                $getTtd = DB::table('simrspku_klaim.tanda_tangan AS ttd')
                                                ->where('ttd.user',$getIDUser->ID)
                                                ->whereNull('ttd.deleted_at')
                                                ->first();
                            }

                            if ($getTtd) {
                                $imagePath2 = storage_path()."/app/public/".$getTtd->signature_path;

                                // DB::table('simrspku_klaim.tanda_tangan')->insert([
                                //     'kunjungan' => $kunjungan,
                                //     'signature_path' => $getTtd->signature_path,
                                //     'created_at' => Carbon::now(),
                                //     'updated_at' => Carbon::now(),
                                //     'user' => Auth::user()->ID,
                                // ]);
                            } else {
                                return response()->json([
                                    'message' => "Tanda tangan Dokter DPJP ({$item->NAMADOKTER}) pada Resume Konsul tidak ditemukan."
                                ], 404);
                            }
                        }
                        $paramsKonsul['IMAGES_PATH2'] = $imagePath2;
                    }

                    $jasper->process(
                        $input,
                        $konsulOutput,
                        [
                            'format'=>['pdf'],
                            'params'=>$paramsKonsul,
                            'db_connection' => [
                                'driver'   => config('database.connections.db_custom.driver'),
                                'host'     => config('database.connections.db_custom.host'),
                                'port'     => config('database.connections.db_custom.port'),
                                'username' => config('database.connections.db_custom.username'),
                                'password' => config('database.connections.db_custom.password'),
                                'database' => config('database.connections.db_custom.database'),
                            ],
                        ]
                    )->execute();

                    $tempPaths[] = $konsulOutput.'.pdf';
                }
            }


            /*
            |--------------------------------------------------------------------------
            | 3️⃣ Merge Semua PDF
            |--------------------------------------------------------------------------
            */
            $pdf = new \setasign\Fpdi\Fpdi();

            foreach ($tempPaths as $file) {

                $pageCount = $pdf->setSourceFile($file);

                for ($page = 1; $page <= $pageCount; $page++) {
                    $tpl = $pdf->importPage($page);
                    $size = $pdf->getTemplateSize($tpl);
                    $pdf->AddPage($size['orientation'],[$size['width'],$size['height']]);
                    $pdf->useTemplate($tpl);
                }
            }

            $pdf->Output('F',$output.'.pdf');


            /*
            |--------------------------------------------------------------------------
            | 4️⃣ Hapus File Temporary
            |--------------------------------------------------------------------------
            */
            foreach ($tempPaths as $file) {
                if (File::exists($file)) {
                    File::delete($file);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | 5️⃣ Simpan ke klaim_file jika belum ada
            |--------------------------------------------------------------------------
            */
            $verify = klaim_file::where('nomor',$kunjungan)
                ->where('jenis',2)
                ->where('status',true)
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

            return response()->file($output.'.pdf',[
                'Content-Type'=>'application/pdf',
                'Cache-Control'=>'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma'=>'no-cache',
                'Expires'=>'0',
            ]);
        }

        function compileIndividual($kunjungan)
        {
            $getSEP = DB::table('pendaftaran.kunjungan AS pk')
                    ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                    ->leftJoin('pendaftaran.penjamin AS pj','pp.NOMOR','=','pj.NOPEN')
                    ->select('pj.NOMOR AS NOSEP','pp.NOMOR AS NOPEN','pp.TANGGAL AS TGLREG')
                    ->where('pk.NOMOR',$kunjungan)
                    ->first();
            // $show = DB::select('CALL simrspku_klaim.CetakLapIndividual5(?,?)',[$getSEP->NOPEN,3]);
            if (!$getSEP) {
                return response()->json('SEP Tidak ditemukan. Periksa data kunjungan sekali lagi.', 400);
            }
            $CETAK_HEADER = "1";
            // ----------------------------------------------------------------------
            $getTgl = Carbon::parse($getSEP->TGLREG);
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
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma'        => 'no-cache',
                'Expires'       => '0',
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
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma'        => 'no-cache',
                'Expires'       => '0',
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

            // $show = DB::select('CALL simrspku_klaim.CetakRincianPasienPerDokterCustom(?,?)',[$getSEP->TAGIHAN,$getSEP->STATUS]);

            $show = DB::table('pembayaran.tagihan as t')
                ->leftJoin('master.pasien as p', 'p.NORM', '=', 't.REF')
                // ->leftJoin('master.keluarga_pasien as kpx', 't.REF', '=', 'kpx.NORM') // opsional
                ->leftJoin('master.referensi as rjk', function ($join) {
                    $join->on('p.JENIS_KELAMIN', '=', 'rjk.ID')->where('rjk.JENIS', '=', 2);
                })
                ->leftJoin('pembayaran.tagihan_pendaftaran as tp', function ($join) {
                    $join->on('tp.TAGIHAN', '=', 't.ID')
                        ->where('tp.UTAMA', '=', 1)
                        ->where('tp.STATUS', '=', 1);
                })
                ->leftJoin('pendaftaran.pendaftaran as pd', 'pd.NOMOR', '=', 'tp.PENDAFTARAN')
                ->leftJoin('master.kartu_asuransi_pasien as kap', function ($join) {
                    $join->on('pd.NORM', '=', 'kap.NORM')->where('kap.JENIS', '=', 2);
                })
                ->leftJoin('pendaftaran.penjamin as pj', 'pd.NOMOR', '=', 'pj.NOPEN')
                ->leftJoin('master.referensi as rf', function ($join) {
                    $join->on('pj.JENIS', '=', 'rf.ID')->where('rf.JENIS', '=', 10);
                })
                ->leftJoin('pembayaran.pembayaran_tagihan as pt', function ($join) {
                    $join->on('pt.TAGIHAN', '=', 't.ID')
                        ->where('pt.JENIS', '=', 1)
                        ->where('pt.STATUS', '=', 2);
                })
                ->leftJoin('pembayaran.penjamin_tagihan as pjt', function ($join) {
                    $join->on('t.ID', '=', 'pjt.TAGIHAN')->where('pjt.KE', '=', 1);
                })
                ->leftJoin('aplikasi.pengguna as us', 'us.ID', '=', 'pt.OLEH')
                ->leftJoin('master.pegawai as mp', 'mp.NIP', '=', 'us.NIP')
                ->leftJoin('simrspku_klaim.klaim_qrcode_pegawai as qp', 'qp.nomor', '=', 'mp.NIP')
                ->leftJoin('simrspku_klaim.klaim_qrcode as kq', 'kq.nomor', '=', 'pd.NORM')
                ->leftJoin('master.diagnosa_masuk as dm', 'dm.ID', '=', 'pd.DIAGNOSA_MASUK')
                ->crossJoin('aplikasi.instansi as i')
                ->crossJoin('master.ppk as ppk')
                ->crossJoin('master.wilayah as w')
                ->where('t.ID', $getSEP->TAGIHAN)
                ->where('t.JENIS', 1)
                ->whereIn('t.STATUS', [1, 2])
                ->whereColumn('ppk.ID', '=', 'i.PPK')
                ->whereColumn('w.ID', '=', 'ppk.WILAYAH')
                ->select([
                    // 'i.PPK',
                    // 'ppk.NAMA as NAMAINSTANSI',
                    // 'ppk.ALAMAT as ALAMATINSTANSI',
                    't.ID as NOMOR_TAGIHAN',
                    // DB::raw("INSERT(INSERT(INSERT(LPAD(p.NORM,8,'0'),3,0,'-'),6,0,'-'),9,0,'-') as NORM"),
                    'p.NORM as RM',
                    // 'pd.NOMOR as NOPEN',
                    // DB::raw("(SELECT tp.PENDAFTARAN FROM pembayaran.tagihan_pendaftaran tp WHERE tp.TAGIHAN = t.ID ORDER BY tp.PENDAFTARAN ASC LIMIT 1) as PENDAFTARAN_PERTAMA"),
                    // DB::raw("(SELECT DATE_FORMAT(pd2.TANGGAL,'%d-%m-%Y %H:%i:%s') FROM pendaftaran.pendaftaran pd2 WHERE pd2.NOMOR = PENDAFTARAN_PERTAMA) as TANGGALREG"),
                    DB::raw("master.getNamaLengkap(p.NORM) as NAMALENGKAP"),
                    // DB::raw("master.getAlamatPasienCustom(p.NORM) as ALAMATLENGKAP"),
                    // DB::raw("master.getNamaLengkapPegawai((SELECT dkt.NIP FROM master.dokter dkt JOIN pendaftaran.kunjungan kjg ON kjg.NOPEN = pd.NOMOR WHERE dkt.ID = kjg.DPJP AND kjg.REF IS NULL ORDER BY kjg.MASUK DESC LIMIT 1)) as DPJP"),
                    // DB::raw("master.getNamaLengkapKeluarga(p.NORM) as NAMA_KELUARGA_PASIEN"),
                    // DB::raw("NOW() as NOW"),
                    // 'pj.JENIS as IDCARABAYAR',
                    // 'kap.NOMOR as NOMORKARTU',
                    // 'rf.DESKRIPSI as CARABAYAR',
                    // 'p.TANGGAL_LAHIR',
                    // DB::raw("CONCAT(CAST(rjk.DESKRIPSI AS CHAR(15)),' (',master.getCariUmur(pd.TANGGAL,p.TANGGAL_LAHIR),')') as UMUR"),
                    'mp.NIP',
                    // 'qp.filename as QR1',
                    // 'kq.filename as QR2',
                    DB::raw("IF(pt.OLEH=0, pt.DESKRIPSI, master.getNamaLengkapPegawai(mp.NIP)) as PENGGUNA"),
                    // 't.ID as IDTAGIHAN',
                    // DB::raw("(t.TOTAL + pembayaran.getTarifAmbulance(t.ID)) as TOTALRS"),
                    // 'w.DESKRIPSI as WILAYAH',
                    // DB::raw("pembayaran.getInfoTagihanKunjungan(t.ID) as JENISKUNJUNGAN"),
                    // DB::raw("IF(pt.TANGGAL IS NULL, SYSDATE(), pt.TANGGAL) as TANGGALBAYAR"),
                    // 't.TANGGAL as TANGGALTAGIHAN',
                    // DB::raw("IF(pj.JENIS=2 AND pjt.NAIK_KELAS=1, pjt.TOTAL_NAIK_KELAS,
                    //         IF(pj.JENIS=2 AND pjt.NAIK_KELAS_VIP=1, pjt.TARIF_INACBG_KELAS1,
                    //         (t.TOTAL + pembayaran.getTarifAmbulance(t.ID)))) + IFNULL(pjt.SELISIH_MINIMAL,0) as TOTALTAGIHAN"),
                    // DB::raw("(pembayaran.getTotalDiskon(t.ID) + pembayaran.getTotalDiskonDokter(t.ID)) as TOTALDISKON"),
                    // DB::raw("pembayaran.getTotalNonTunai(t.ID) as TOTALEDC"),
                    // DB::raw("pembayaran.getTotalPenjaminTagihan(t.ID) as TOTALPENJAMINTAGIHAN"),
                    // DB::raw("(pembayaran.getTotalPiutangPasien(t.ID) + pembayaran.getTotalPiutangPerusahaan(t.ID)) as TOTALPIUTANG"),
                    // DB::raw("(pembayaran.getTotalDeposit(t.ID) - pembayaran.getTotalPengembalianDeposit(t.ID)) as TOTALDEPOSIT"),
                    // DB::raw("pembayaran.getTotalSubsidiTagihan(t.ID) as TOTALSUBSIDI"),
                    // DB::raw("((IF(pt.TOTAL IS NULL,
                    //                 IF((ROUND(pembayaran.getTotalTagihanPembayaran(t.ID)) + ROUND(t.PEMBULATAN)) < 0,
                    //                     0,
                    //                     (ROUND(pembayaran.getTotalTagihanPembayaran(t.ID)) + ROUND(t.PEMBULATAN))
                    //                 ),
                    //                 pt.TOTAL
                    //             )) + pembayaran.getTarifAmbulance(t.ID)) as TOTALJUMLAHBAYAR"),
                    // DB::raw("((IF(pt.TOTAL IS NULL,
                    //                 IF((ROUND(pembayaran.getTotalTagihanPembayaran(t.ID)) + ROUND(t.PEMBULATAN)) < 0,
                    //                     0,
                    //                     (ROUND(pembayaran.getTotalTagihanPembayaran(t.ID)) + ROUND(t.PEMBULATAN))
                    //                 ),
                    //                 pt.TOTAL
                    //             ) - (pembayaran.getTotalDeposit(t.ID) - pembayaran.getTotalPengembalianDeposit(t.ID)))
                    //             + pembayaran.getTarifAmbulance(t.ID)) as JUMLAHBAYAR"),
                    // DB::raw("ROUND(t.PEMBULATAN) as PEMBULATAN"),
                    // DB::raw("IF(INSTR(dm.DIAGNOSA, 'B20') > 1 OR INSTR(dm.DIAGNOSA, 'HIV') > 1,'',dm.DIAGNOSA) as DIAGNOSA"),
                    // DB::raw("(SELECT DATE_FORMAT(pl.TANGGAL,'%d-%m-%Y %H:%i:%s') FROM layanan.pasien_pulang pl WHERE pl.NOPEN=pd.NOMOR AND pl.STATUS!=0 LIMIT 1) as TGLKELUAR"),
                    // 'pj.NOMOR as NOSEP'
                ])
                ->first();

            //-----------------------------------------------------------------------
            //GENERATE QR CODE
            $generator = new DNS2D();
            $pegawai = $show->NIP . '-' . $show->PENGGUNA;

            // Generate QR code PNG base64 (bukan data:image/png;base64,... hanya base64 murni)
            $image = $generator->getBarcodePNG($pegawai, 'QRCODE');

            if ($show && $show->NIP) {
                // Decode base64 jadi binary PNG
                $decodedImage = base64_decode($image);
                $token = Crypt::encrypt($show->NIP);
                $titleQrcode = Crypt::encrypt($show->NIP).'.png';
                $verif = klaim_qrcode_pegawai::where('nomor',$show->NIP)->first();

                // Simpan ke file storage Laravel (storage/app/public/files/qrcode{nip}.png)
                $pathQrcode = 'files/qrcode/' . $titleQrcode;
                $outputQrcode = storage_path('/app/public/' . $pathQrcode);
                $outputQrcodeMerged = dirname($outputQrcode);
                if (!File::exists($outputQrcodeMerged)) {
                    File::makeDirectory($outputQrcodeMerged, 0755, true); // true = recursive
                }

                // SAVE TO DB
                if (!$verif) {
                    file_put_contents($outputQrcode, $decodedImage);
                    $post = new klaim_qrcode_pegawai;
                    $post->token = $token;
                    $post->nomor = $show->NIP;
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
            $pasien = $show->RM .'-'.$show->NAMALENGKAP;

            // Generate QR code PNG base64 (bukan data:image/png;base64,... hanya base64 murni)
            $image2 = $generator2->getBarcodePNG($pasien, 'QRCODE');

            // Decode base64 jadi binary PNG
            $decodedImage2 = base64_decode($image2);
            $token2 = Crypt::encrypt($show->RM);
            $titleQrcode2 = Crypt::encrypt($show->RM).'.png';
            $verif2 = klaim_qrcode::where('nomor',$show->RM)->where('jenis',1)->first();

            // Simpan ke file storage Laravel (storage/app/public/files/qrcode{nip}.png)
            $pathQrcode2 = 'files/qrcode/' . $titleQrcode2;
            $outputQrcode2 = storage_path('app/public/' . $pathQrcode2);

            // SAVE TO DB
            if (!$verif2) {
                // \Log::info('Menyimpan file ke: ' . $outputQrcode2);
                file_put_contents($outputQrcode2, $decodedImage2);
                $post = new klaim_qrcode;
                $post->jenis = 1;
                $post->token = $token2;
                $post->nomor = $show->RM;
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

            $params = [
                'PTAGIHAN' => $getSEP->TAGIHAN ?? '',
                'PSTATUS'  => $getSEP->STATUS ?? '',
                'IMAGES_PATH' => public_path() . "/doc/input/billing/",
                'QRCODE_PATH' => storage_path() . "/app/public/",
            ];

            $options = [
                'format' => ['pdf'],
                'params' => $params,
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
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma'        => 'no-cache',
                'Expires'       => '0',
            ]);
        }

        function compileLab($kunjungan)
        {
            $getSEP = DB::table('pendaftaran.kunjungan AS pk')
                    ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                    ->leftJoin('pendaftaran.penjamin AS pj','pp.NOMOR','=','pj.NOPEN')
                    ->leftJoin('pembayaran.tagihan_pendaftaran AS tp','tp.PENDAFTARAN','=','pp.NOMOR')
                    ->select(
                        'pj.NOMOR AS NOSEP',
                        'pp.NOMOR AS NOPEN',
                        'tp.TAGIHAN',
                        'pk.MASUK AS TANGGALMASUK'
                    )
                    ->where('pk.NOMOR',$kunjungan)
                    ->where(function ($q) {
                        $q->where('tp.STATUS',1)
                        ->orWhere('tp.UTAMA',1);
                    })
                    ->first();

            if (!$getSEP) {
                return response()->json([
                    'message' => 'Data SEP / Tagihan tidak ditemukan'
                ], 404);
            }

            $listNopen = DB::table('pembayaran.tagihan_pendaftaran')
                    ->where('TAGIHAN', $getSEP->TAGIHAN)
                    ->where('STATUS',1)
                    ->pluck('PENDAFTARAN');

            if ($listNopen->isEmpty()) {
                return response()->json([
                    'message' => 'Tidak ditemukan pendaftaran dalam tagihan yang sama dengan Kunjungan Utama'
                ], 404);
            }

            $getTgl = Carbon::parse($getSEP->TANGGALMASUK);
            $tgl = $getTgl->isoFormat('DD');
            $bulan = $getTgl->isoFormat('MM');
            $tahun = $getTgl->isoFormat('YYYY');

            $show = DB::table('pendaftaran.pendaftaran AS pd')
                        ->leftJoin('pendaftaran.kunjungan AS k', 'k.NOPEN', '=', 'pd.NOMOR')
                        ->leftJoin('layanan.tindakan_medis AS tm','tm.KUNJUNGAN','=','k.NOMOR')
                        ->select('k.NOMOR AS NOMOR', 'tm.ID AS TINDAKAN')
                        ->whereIn('pd.NOMOR', $listNopen)
                        ->where('k.RUANGAN', '=', '102040101')
                        ->where('tm.STATUS',1)
                        ->get();

            if ($show->isEmpty()) {
                return response()->json([
                    'message' => 'Tidak ada tindakan lab aktif'
                ], 404);
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
                        $outputQrcodeMerged = dirname($outputQrcode);
                        if (!File::exists($outputQrcodeMerged)) {
                            File::makeDirectory($outputQrcodeMerged, 0755, true); // true = recursive
                        }

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
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma'        => 'no-cache',
                'Expires'       => '0',
            ]);
        }

        function compileRad($kunjungan)
            {
            $getSEP = DB::table('pendaftaran.kunjungan AS pk')
                    ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                    ->leftJoin('pendaftaran.penjamin AS pj','pp.NOMOR','=','pj.NOPEN')
                    ->leftJoin('pembayaran.tagihan_pendaftaran AS tp','tp.PENDAFTARAN','=','pp.NOMOR')
                    ->select('pj.NOMOR AS NOSEP','pp.NOMOR AS NOPEN','tp.TAGIHAN','pk.MASUK AS TANGGALMASUK')
                    ->where('pk.NOMOR',$kunjungan)
                    ->where(function ($q) {
                        $q->where('tp.STATUS',1)
                        ->orWhere('tp.UTAMA',1);
                    })
                    ->first();

            if (!$getSEP) {
                return response()->json([
                    'message' => 'Data SEP / Tagihan tidak ditemukan'
                ], 404);
            }

            $listNopen = DB::table('pembayaran.tagihan_pendaftaran')
                    ->where('TAGIHAN', $getSEP->TAGIHAN)
                    ->where('STATUS',1)
                    ->pluck('PENDAFTARAN');

            if ($listNopen->isEmpty()) {
                return response()->json([
                    'message' => 'Tidak ditemukan pendaftaran dalam tagihan yang sama dengan Kunjungan Utama'
                ], 404);
            }

            $getTgl = Carbon::parse($getSEP->TANGGALMASUK);
            $tgl = $getTgl->isoFormat('DD');
            $bulan = $getTgl->isoFormat('MM');
            $tahun = $getTgl->isoFormat('YYYY');

            $show = DB::table('pendaftaran.pendaftaran AS pd')
                    ->leftJoin('pendaftaran.kunjungan AS k', 'k.NOPEN', '=', 'pd.NOMOR')
                    ->leftJoin('layanan.tindakan_medis AS tm','tm.KUNJUNGAN','=','k.NOMOR')
                    ->select('k.NOMOR AS NOMOR', 'tm.ID AS TINDAKAN')
                    ->whereIn('pd.NOMOR', $listNopen)
                    ->where('k.RUANGAN', '=', '102050101')
                    ->where('tm.STATUS',1)
                    ->get();
                        // dd($show);
            if ($show->isEmpty()) {
                return response()->json([
                    'message' => 'Tidak ada tindakan Radiologi yang aktif'
                ], 404);
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
                $outputQrcodeMerged = dirname($outputQrcode);
                if (!File::exists($outputQrcodeMerged)) {
                    File::makeDirectory($outputQrcodeMerged, 0755, true); // true = recursive
                }

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
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma'        => 'no-cache',
                'Expires'       => '0',
            ]);
        }

        function compileTriage($kunjungan)
        {
            $getData = DB::table('pendaftaran.kunjungan AS pk')
                            ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                            ->leftJoin('pendaftaran.penjamin AS pj','pp.NOMOR','=','pj.NOPEN')
                            ->leftJoin('medicalrecord.triage AS tr','tr.KUNJUNGAN','=','pk.NOMOR')
                            ->leftJoin('aplikasi.pengguna AS us','us.ID','=','tr.OLEH')
                            ->leftJoin('master.pasien AS ps','pp.NORM','=','ps.NORM')
                            ->select(
                                'pj.NOMOR AS NOSEP',
                                'pk.MASUK AS TANGGALMASUK',
                                'tr.ID AS PID',
                                'us.NIP AS NIP',
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
            // print_r($getData);
            // die();
            if (!$getData) {
                return response()->json('Data tidak ditemukan', 400);
            }
            $show = DB::select('CALL simrspku_klaim.CetakTriage(?)',[$getData->PID]);
            if (empty($getData)) {
                return response()->json('Triage tidak ditemukan', 400);
            }
            // ----------------------------------------------------------------------
            //GENERATE QR CODE
            $generator = new DNS2D();
            $dokter = $getData->NIP;

            // Generate QR code PNG base64 (bukan data:image/png;base64,... hanya base64 murni)
            $image = $generator->getBarcodePNG($dokter, 'QRCODE');
            // print_r($generator);
            // die();

            // Decode base64 jadi binary PNG
            $decodedImage = base64_decode($image);
            $token = Crypt::encrypt($getData->NIP);
            $titleQrcode = Crypt::encrypt($getData->NIP).'.png';
            $verif = klaim_qrcode_pegawai::where('nomor',$getData->NIP)->first();

            // Simpan ke file storage Laravel (storage/app/public/files/qrcode{nip}.png)
            $pathQrcode = 'files/qrcode/' . $titleQrcode;
            $outputQrcode = storage_path('app/public/' . $pathQrcode);
            $outputQrcodeMerged = dirname($outputQrcode);
            if (!File::exists($outputQrcodeMerged)) {
                File::makeDirectory($outputQrcodeMerged, 0755, true); // true = recursive
            }

            // SAVE TO DB
            if (!$verif) {
                file_put_contents($outputQrcode, $decodedImage);
                $post = new klaim_qrcode_pegawai;
                $post->token = $token;
                $post->nomor = $getData->NIP;
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

            return response()->file($output.'.pdf',[
                'Content-Type' => 'application/pdf',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma'        => 'no-cache',
                'Expires'       => '0',
            ]);
        }

        function compileOperasi($kunjungan)
        {
            try {
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
                    throw new \Exception('Data Laporan operasi tidak ditemukan pada kunjungan ini');
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
                    $getOp = DB::table('medicalrecord.operasi AS op')
                            ->leftJoin('master.dokter AS dok','dok.ID','=','op.DOKTER')
                            ->leftJoin('medicalrecord.pelaksana_operasi AS podok', function($join) {
                                $join->on('podok.OPERASI_ID','=','op.ID')
                                    ->where('podok.STATUS', '!=', 0);
                            })
                            ->leftJoin('master.pegawai AS pg','podok.PELAKSANA','=','pg.ID')
                            ->selectRaw(
                                'op.ID AS PID,
                                pg.NIP AS NIP_DOKTER1,
                                pg.NIP AS NIP_DOKTER1,
                                dok.NIP AS NIPDOKTER2,
                                master.getPelaksanaOperasi(?, ?) as NAMADOKTEROPERATOR',
                                [$PID, 1]
                            )
                            // ->select('op.ID AS PID','dok.NIP AS NIPDOKTER2', DB::raw("master.getPelaksanaOperasi({$PID},1) as NAMADOKTEROPERATOR"))
                            ->where('op.ID', $PID)
                            ->first();

                    // print_r($getOp);
                    // die();
                    $nipDokter = $getOp->NIP_DOKTER1
                                ?? $getOp->NIPDOKTER2
                                ?? null;
                    $ttd_pegawai = DB::table('simrspku_klaim.tanda_tangan_pegawai as ttp')
                                ->when($nipDokter, function($q) use ($nipDokter){
                                    $q->where('ttp.nip', $nipDokter);
                                })
                                ->where('status', 1)
                                ->inRandomOrder()
                                ->first();

                    if (!$ttd_pegawai) {
                        throw new \Exception('Data TTD dokter tidak ditemukan untuk laporan operasi ini');
                    }

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
                            'IMAGES_PATH2' => storage_path()."/app/public/".$ttd_pegawai->signature_path,
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
                'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma'        => 'no-cache',
                'Expires'       => '0',
                ]);
            } catch (\Throwable $e) {

                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 400);
            }
        }

        function hapusTtdResumeRj($KUNJUNGAN)
        {
            $data = klaim_file::where('nomor',$KUNJUNGAN)
                    ->where('jenis',2)
                    ->whereNull('deleted_at')
                    ->first();

            $verif = klaim_verifikasi::where('nomor',$KUNJUNGAN)
                    ->where('status',1)
                    ->whereNull('deleted_at')
                    ->first();

            if ($verif) {
                if ($verif->verif != 0) {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Dokumen telah diverifikasi oleh Bagian Klaim, silakan konfirmasi kepada Bagian Terkait',
                    ]);
                } else {
                    // Decode satu kali
                    $koleksi = json_decode($verif->koleksi, true);

                    // Pastikan hasilnya array
                    if (!is_array($koleksi)) {
                        $koleksi = [];
                    }

                    // Jika ada Resume (angka 2)
                    if (in_array(2, $koleksi)) {

                        // Hapus angka 2
                        $koleksi = array_filter($koleksi, function ($item) {
                            return $item != 2;
                        });

                        // Re-index
                        $koleksi = array_values($koleksi);

                        // Simpan kembali
                        $verif->koleksi = json_encode($koleksi);
                        $verif->save();
                    }
                }
            }

            if ($data) {
                $data->user_deleted = Auth::user()->ID;
                $data->status = 0;
                $data->save();
                $data->delete();

                // Storage::disk('public')->delete($data->filename);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Dokumen Resume Berhasil Dihapus dari Database',
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
