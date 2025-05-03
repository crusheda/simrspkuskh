<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\simrspku_klaim\klaim_file;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PHPJasper\PHPJasper;
use Carbon\Carbon;
use Auth, Storage;

class ApiMonitoringController extends Controller
{
    function tableRj($rawat,$status,$tgls,$tgle,$dpjp) // RAWAT JALAN
    {
        $time = Carbon::now()->isoFormat('YYYY-MM-DD HH:mm:ss');
        // $subCppt = DB::table('medicalrecord.cppt')
        //         ->select('KUNJUNGAN', 'TANGGAL')
        //         ->where('STATUS', 1) // kalau perlu
        //         // ->where('KUNJUNGAN', '1020101042406080005')
        //         ->orderBy('TANGGAL', 'desc')
        //         ->get(); // ambil 1 cppt terakhir

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
        // MAIN QUERY
        $show = DB::table('pendaftaran.kunjungan AS pk')
                ->select(
                    'pk.*',
                    'pp.NORM','pp.TANGGAL AS TGLDAFTAR',
                    'kjs.noSEP AS NOSEP','kjs.tglSEP AS TGLSEP',
                    'ru.DESKRIPSI AS NAMARUANGAN',
                    'ttd.created_at AS TGLTTD',
                    'cp.TANGGAL AS TGLCPPT',
                    'td.TANGGAL AS TGLTINDAKAN',
                    'jk.NOMOR AS NOSURKON','jk.NOMOR_BOOKING AS NOMORBOOKING',
                    DB::raw('master.getNamaLengkap(ps.NORM) AS NAMAPASIEN'),
                    DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER')
                )
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
                ->leftJoin('medicalrecord.jadwal_kontrol AS jk','jk.KUNJUNGAN','=','pk.NOMOR')
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
                ->when($dpjp != 0, function ($query) use ($dpjp) {
                    // Hanya menambahkan where jika $dpjp bukan 0
                    $query->where('dr.NIP', $dpjp);
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
                            $prefix = '1020101%';
                            break;
                        case 2:
                            $prefix = '1020201%';
                            break;
                        case 3:
                            $prefix = '1020301%';
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
                // KHUSUS RAWAT DARURAT
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
                })
                // ->where('pk.KELUAR', null)
                ->orderBy('pk.MASUK','DESC')
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
            $show = DB::select('CALL simrspku_klaim.CetakSKDP(?)',[$kunjungan]);
            if (empty($show)) {
                return response()->json($data, 400);
            }
            // ----------------------------------------------------------------------
            // print_r($show);
            // die();
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

            // SAVE TO DB
            $verify = klaim_file::where('nomor',$kunjungan)->where('jenis',3)->where('status',true)->first();
            if (!$verify) {
                $post = new klaim_file;
                $post->jenis = 3;
                $post->nomor = $kunjungan;
                $post->title = $kunjungan.'.pdf';
                $post->filename = $path.'.pdf';
                $post->status = true;
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
                'format' => ['pdf'], // 'xls' / 'rtf
                'params' => [
                    'KODEBPJS' => $show[0]->KODEBPJS,
                    'NOMOR' => $show[0]->NOMOR,
                    'IDPENJAMIN' => $show[0]->IDPENJAMIN,
                    'NOMORKARTU' => $show[0]->NOMORKARTU,
                    'NORMBPJS' => $show[0]->NORMBPJS,
                    'NOBPJS' => $show[0]->NOBPJS,
                    'PESERTA' => $show[0]->PESERTA,
                    'NAMALENGKAP1' => $show[0]->NAMALENGKAP1,
                    'NAMA_LENGKAP' => $show[0]->NAMA_LENGKAP,
                    'TANGGAL_LAHIR' => $show[0]->TANGGAL_LAHIR,
                    'NORM' => $show[0]->NORM,
                    'KOTA' => $show[0]->KOTA,
                    'DIBUAT_TANGGAL' => $show[0]->DIBUAT_TANGGAL,
                    'RUANGAN' => $show[0]->RUANGAN,
                    'DOKTER' => $show[0]->DOKTER,
                    'NIP' => $show[0]->NIP,
                    'DRSEP' => $show[0]->DRSEP,
                    'DRKONTROL' => $show[0]->DRKONTROL,
                    'SPESIALISTIK' => $show[0]->SPESIALISTIK,
                    'SMF' => $show[0]->SMF,
                    'DIAGNOSIS' => $show[0]->DIAGNOSIS,
                    'NOMOR_ANTRIAN' => $show[0]->NOMOR_ANTRIAN,
                    'NOMOR_BOOKING' => $show[0]->NOMOR_BOOKING,
                    'DIAGMASUK' => $show[0]->DIAGMASUK,
                    'JADWAL_KONTROL' => $show[0]->JADWAL_KONTROL,
                    'TGLSO' => $show[0]->TGLSO,
                    'KETSO' => $show[0]->KETSO,
                    'KET' => $show[0]->KET,
                    'JADWALBPJS' => $show[0]->JADWALBPJS,
                    'BLN' => $show[0]->BLN,
                    'THN' => $show[0]->THN,
                    'RENCANA_TERAPI' => $show[0]->RENCANA_TERAPI,
                    'JENIS_KUNJUNGAN' => $show[0]->JENIS_KUNJUNGAN,
                    'NOSBPJS' => $show[0]->NOSBPJS,
                    'NOSURAT' => $show[0]->NOSURAT,
                    'JENISKONTROL' => $show[0]->JENISKONTROL,
                    'NORJK' => $show[0]->NORJK,
                    'TGLRJK' => $show[0]->TGLRJK,
                    'MASABERLAKU' => $show[0]->MASABERLAKU,
                    'TUJUANRUJUK' => $show[0]->TUJUANRUJUK,
                    'nama' => $show[0]->nama,
                    'kode' => $show[0]->kode,
                    'JENIS_RUANG_PERAWATAN' => $show[0]->JENIS_RUANG_PERAWATAN,
                    'JENIS_PERAWATAN' => $show[0]->JENIS_PERAWATAN,
                    'JKONTROL' => $show[0]->JKONTROL,
                    'JADWAL_KONTROL1' => $show[0]->JADWAL_KONTROL1,
                    'USRP' => $show[0]->USRP,
                    'NORJK' => $show[0]->NORJK,
                    'IMAGES_PATH' => public_path()."/doc/input/skdp/",
                ],
                'classpath' => [
                    public_path() . "/jasper-libs/core-3.5.3.jar",
                    public_path() . "/jasper-libs/javase-3.5.3.jar",
                    public_path() . "/jasper-libs/barcode4j.jar"
                    // public_path() . "/jasper-libs/core-3.3.3.jar",
                    // public_path() . "/jasper-libs/javase-3.3.3.jar"
                ],
            ];

            // dd($options);
            // print_r($options);
            // die();

            // print_r(public_path()."\jasper-libs\core-3.3.3.jar");
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

            // SAVE TO DB
            $verify = klaim_file::where('nomor',$kunjungan)->where('jenis',1)->where('status',true)->first();
            if (!$verify) {
                $post = new klaim_file;
                $post->jenis = 1;
                $post->nomor = $kunjungan;
                $post->title = $kunjungan.'.pdf';
                $post->filename = $path.'.pdf';
                $post->status = true;
                $post->save();
            }

            $options = [
                'format' => ['pdf'], // 'xls' / 'rtf
                'params' => [
                    'ASPEL' => $show[0]->ASPEL,
                    'CATATAN' => $show[0]->CATATAN,
                    'CETAKAN' => $show[0]->CETAKAN,
                    'COB' => $show[0]->COB,
                    'DIAGNOSA' => $show[0]->DIAGNOSA,
                    'DOKTER' => $show[0]->DOKTER,
                    'IMAGES_PATH' => public_path()."/doc/input/sep/",
                    'JENISKELAMIN' => $show[0]->JENISKELAMIN,
                    'JENISRAWAT' => $show[0]->JENISRAWAT,
                    'KATARAK' => $show[0]->KATARAK,
                    'KELAS' => $show[0]->KELAS,
                    'klsRawat' => $show[0]->klsRawat,
                    'NAMAINSTANSI' => $show[0]->NAMAINSTANSI,
                    'NAMALENGKAP' => $show[0]->NAMALENGKAP,
                    'NOMORKARTU' => $show[0]->NOMORKARTU,
                    'NOMORSEP' => $show[0]->NOMORSEP,
                    'NORM' => $show[0]->NORM,
                    'NOTELP' => $show[0]->NOTELP,
                    'PENJAMIN' => $show[0]->PENJAMIN,
                    'PENUNJANG' => $show[0]->PENUNJANG,
                    'PESERTA' => $show[0]->PESERTA,
                    'POLIPERUJUK' => $show[0]->POLIPERUJUK,
                    'poliTujuan' => $show[0]->poliTujuan,
                    'PRB' => $show[0]->PRB,
                    'PROC' => $show[0]->PROC,
                    'RUJUKAN' => $show[0]->RUJUKAN,
                    'TGL_LAHIR' => $show[0]->TGL_LAHIR,
                    'TGLSEP' => $show[0]->TGLSEP,
                    'TJKUNJ' => $show[0]->TJKUNJ,
                    'UNITPELAYANAN' => $show[0]->UNITPELAYANAN
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

            $data = [
                'show' => $show,
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
            ->select('pj.NOMOR AS NOSEP','pp.NOMOR AS NOPEN','pk.NOMOR AS NOMOR')
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
            // print_r($obyektif2);
            // die();

            // ----------------------------------------------------------------------
            $ttd = DB::table('simrspku_klaim.tanda_tangan AS ttd')
                ->where('ttd.kunjungan',$kunjungan)
                ->first();
            if ($ttd) {
                $imagePath2 = storage_path()."/app/public/".$ttd->signature_path;
            } else {
                $imagePath2 = null;
            }

            // ----------------------------------------------------------------------
            $getTgl = Carbon::parse($show[0]->TGLPERIKSA);
            $tgl = $getTgl->isoFormat('DD');
            $bulan = $getTgl->isoFormat('MM');
            $tahun = $getTgl->isoFormat('YYYY');

            // ----------------------------------------------------------------------

            $input = public_path().'/doc/input/resumeRJ/CetakResumeRJ2.jrxml';
            $path = 'files/resume/RJ/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$kunjungan;
            $output = storage_path().'/app/public/'.$path;

            // SAVE TO DB
            $verify = klaim_file::where('nomor',$kunjungan)->where('jenis',2)->where('status',true)->first();
            if (!$verify) {
                $post = new klaim_file;
                $post->jenis = 2;
                $post->nomor = $kunjungan;
                $post->title = $kunjungan.'.pdf';
                $post->filename = $path.'.pdf';
                $post->status = true;
                $post->save();
            }

            // Pastikan folder tujuan ada
            $outputDir = dirname($output);
            if (!File::exists($outputDir)) {
                File::makeDirectory($outputDir, 0755, true); // true = recursive
            }

            // ----------------------------------------------------------------------
            // $table_obat = [
            //     ['NAMAOBAT' => 'coba1','ATURANPAKAI' => 'coba2'],
            // ];

            // ----------------------------------------------------------------------
            $jsonPath = storage_path('/app/public/files/resume/RJ/'.$tahun.'/'.$bulan.'/'.$tgl.'/obat_table.json');
            // file_put_contents($jsonPath, json_encode($table_obat));
            $options = [
                'format' => ['pdf'], // 'xls' / 'rtf
                'params' => [
                    'NAMAINSTANSI' => $show[0]->NAMAINSTANSI,
                    'ALAMAT' => $show[0]->ALAMAT,
                    'NORM' => $show[0]->NORM,
                    'DOKTER' => $show[0]->DOKTER,
                    'IMAGES_PATH' => public_path()."/doc/input/resumeRJ/",
                    'IMAGES_PATH2' => $imagePath2,
                    'NAMAPASIEN' => $show[0]->NAMAPASIEN,
                    'TANGGAL_LAHIR' => $show[0]->TANGGAL_LAHIR,
                    'TGLMASUK' => $show[0]->TGLMASUK,
                    'UNIT' => $show[0]->UNIT,
                    'KEADAAN_UMUM' => $show[0]->KEADAAN_UMUM,
                    'DARAH' => $show[0]->DARAH,
                    'FREKUENSI_NADI' => $show[0]->FREKUENSI_NADI,
                    'FREKUENSI_NAFAS' => $show[0]->FREKUENSI_NAFAS,
                    'SUHU' => $show[0]->SUHU,
                    'ABN' => $show[0]->ABN,
                    'SATURASIO2' => $show[0]->SATURASIO2,
                    'TGLPERIKSA' => $show[0]->TGLPERIKSA,
                    'JAMPERIKSA' => $show[0]->JAMPERIKSA,
                    'ASSESMENT' => $assesment,
                    'OBYEKTIF' => $obyektif,
                    'PLANNING' => $planning,
                    'INSTRUKSI' => $instruksi,
                    'TINDAKAN' => $show[0]->TINDAKAN,
                    'KONSUL' => $show[0]->KONSUL,
                    'DOKTER' => $show[0]->DOKTER,
                    'KELUHAN' => $keluhan,
                    'SUBYEKTIF' => $subyektif,

                    'NAMAOBAT' => $NAMA_OBAT,
                ],
                // Data untuk tabel obat, bukan untuk report utama
                // 'data_source' => $jsonPath
            ];
            // print_r($options);
            // die();
            // if (!file_exists(storage_path("app/public/".$ttd->signature_path))) {
            //     dd('File not found:', storage_path("app/public/".$ttd->signature_path));
            // }
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
                $post->save();
            }

            // Pastikan folder tujuan ada
            $outputDir = dirname($output);
            if (!File::exists($outputDir)) {
                File::makeDirectory($outputDir, 0755, true); // true = recursive
            }

            $options = [
                'format' => ['pdf'], // 'xls' / 'rtf
                'params' => [
                    'KODERS' => $show[0]->KODERS,
                    'NAMAINSTANSI' => $show[0]->NAMAINSTANSI,
                    'KELASRS' => $show[0]->KELASRS,
                    'JENISTARIF' => $show[0]->JENISTARIF,
                    'NOKARTU' => $show[0]->NOKARTU,
                    'NORM' => $show[0]->NORM,
                    'UMURTAHUN' => $show[0]->UMURTAHUN,
                    'UMURHARI' => $show[0]->UMURHARI,
                    'TANGGAL_LAHIR' => date('d/m/Y', strtotime($show[0]->TANGGAL_LAHIR)),
                    'JENISKELAMIN' => $show[0]->JENISKELAMIN,
                    'KELASHAK' => $show[0]->KELASHAK,
                    'NOMORSEP' => $show[0]->NOMORSEP,
                    'TGLREG' => date('d/m/Y', strtotime($show[0]->TGLREG)),
                    'TGLKELUAR' => $show[0]->TGLKELUAR,
                    'JENISPASIEN' => $show[0]->JENISPASIEN,
                    'CARAPULANG' => $show[0]->CARAPULANG,
                    'LOS' => $show[0]->LOS,
                    'BERATLAHIR' => $show[0]->BERATLAHIR,
                    'KODEDIAGNOSAUTAMA' => $show[0]->KODEDIAGNOSAUTAMA,
                    'DIAGNOSAUTAMA' => $show[0]->DIAGNOSAUTAMA,
                    'KODEDIAGNOSASEKUNDER' => $show[0]->KODEDIAGNOSASEKUNDER,
                    'DIAGNOSASEKUNDER' => $show[0]->DIAGNOSASEKUNDER,
                    'KODETINDAKAN' => (!empty($show[0]->KODETINDAKAN) ? $show[0]->KODETINDAKAN : '-'),
                    'TINDAKAN' => $show[0]->TINDAKAN,
                    'ADLAKUT' => $show[0]->ADLAKUT,
                    'ADLKRONIK' => $show[0]->ADLKRONIK,
                    'INACBG' => $show[0]->INACBG,
                    'DESKRIPSIINACBG' => $show[0]->DESKRIPSIINACBG,
                    'UNUSA' => $show[0]->UNUSA,
                    'DESUNUSA' => $show[0]->DESUNUSA,
                    'UNUSC' => $show[0]->UNUSC,
                    'DESUNUSC' => $show[0]->DESUNUSC,
                    'KODESPESIAL' => $show[0]->KODESPESIAL,
                    'DESKKODE' => $show[0]->DESKKODE,
                    'TARIFINACBG' => $show[0]->TARIFINACBG,
                    'TARIFUNUSA' => $show[0]->TARIFUNUSA,
                    'TARIFUNUSC' => $show[0]->TARIFUNUSC,
                    'TARIFKODE' => $show[0]->TARIFKODE,
                    'CODER' => $show[0]->CODER,
                    'VERIFIKATOR' => $show[0]->VERIFIKATOR,
                    'RUANG_RAWAT' => $show[0]->RUANG_RAWAT,
                    'TOTALTARIFINACBG' => $show[0]->TOTALTARIFINACBG,
                    'NO_URUT' => (!empty($show[0]->NO_URUT) ? $show[0]->NO_URUT : 'JKN'),
                    'CATATAN' => $show[0]->CATATAN,
                    'ALOS' => $show[0]->ALOS,
                    'RPKODE' => $show[0]->RPKODE,
                    'BIAYARS' => $show[0]->BIAYARS,
                    'SPECIALPROSEDUR' => $show[0]->SPECIALPROSEDUR,
                    'NAMALENGKAP' => $show[0]->NAMALENGKAP,
                    'IMAGES_PATH' => public_path()."/doc/input/individual/",
                    'CETAK_HEADER' => $CETAK_HEADER,
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
            // print_r($getSEP);
            // die();
            // $show = DB::select('CALL simrspku_klaim.CetakRincianPasienPerDokterCustom(?,?)',[$getSEP->TAGIHAN,$getSEP->STATUS]);
            // dd($show);
            if (!$getSEP) {
                return response()->json($data, 400);
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
                ],
                'db_connection' => [
                    'driver'   => 'mysql',
                    'host'     => env('DB_HOST'),
                    'port'     => env('DB_PORT'),
                    'username' => env('DB_USERNAME'),
                    'password' => env('DB_PASSWORD'),
                    'database' => env('DB_DATABASE_CUSTOM'),
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
                        ->leftJoin('layanan.order_detil_lab AS odl', 'odl.ORDER_ID', '=', 'k.REF')
                        ->select('k.NOMOR AS NOMOR', 'odl.REF AS TINDAKAN')
                        ->where('pd.NOMOR', $getSEP->NOPEN)
                        ->where('k.RUANGAN', '=', '102040101')
                        ->get();

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
                    ],
                    'db_connection' => [
                        'driver'   => 'mysql',
                        'host'     => env('DB_HOST'),
                        'port'     => env('DB_PORT'),
                        'username' => env('DB_USERNAME'),
                        'password' => env('DB_PASSWORD'),
                        'database' => env('DB_DATABASE_CUSTOM'),
                    ],
                ];

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
                $post->filename = $outputMerged.'.pdf';
                $post->status = true;
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
                    ],
                    'db_connection' => [
                        'driver'   => 'mysql',
                        'host'     => env('DB_HOST'),
                        'port'     => env('DB_PORT'),
                        'username' => env('DB_USERNAME'),
                        'password' => env('DB_PASSWORD'),
                        'database' => env('DB_DATABASE_CUSTOM'),
                    ],
                ];

                // Proses JasperReport untuk setiap PNOMOR
                $jasper->process($input, $output, $options)->execute();
                // print_r($jasper);
                // die();
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
                $post->filename = $outputMerged.'.pdf';
                $post->status = true;
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
                    'driver'   => 'mysql',
                    'host'     => env('DB_HOST'),
                    'port'     => env('DB_PORT'),
                    'username' => env('DB_USERNAME'),
                    'password' => env('DB_PASSWORD'),
                    'database' => env('DB_DATABASE_CUSTOM'),
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
                        'driver'   => 'mysql',
                        'host'     => env('DB_HOST'),
                        'port'     => env('DB_PORT'),
                        'username' => env('DB_USERNAME'),
                        'password' => env('DB_PASSWORD'),
                        'database' => env('DB_DATABASE_CUSTOM'),
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
                $post->filename = $outputMerged.'.pdf';
                $post->status = true;
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
