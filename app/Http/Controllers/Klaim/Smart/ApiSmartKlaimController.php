<?php

namespace App\Http\Controllers\Klaim\Smart;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PHPJasper\PHPJasper;
use Carbon\Carbon;
use Auth, Storage;

class ApiSmartKlaimController extends Controller
{
    function tableRj($status) // RAWAT JALAN
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
        // MAIN QUERY
        $show = DB::table('pendaftaran.kunjungan AS pk')
                ->select(
                    'pk.*',
                    'pp.NORM','pp.TANGGAL AS TGLDAFTAR',
                    'kjs.noSEP AS NOSEP','kjs.tglSEP AS TGLSEP',
                    'ru.DESKRIPSI AS NAMARUANGAN',
                    'cp.TANGGAL AS TGLCPPT',
                    'td.TANGGAL AS TGLTINDAKAN',
                    'jk.NOMOR AS NOSURKON','jk.NOMOR_BOOKING AS NOMORBOOKING',
                    DB::raw('master.getNamaLengkap(ps.NORM) AS NAMAPASIEN'),
                    DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER')
                )
                ->leftJoinSub($subCppt, 'cp', function ($join) { // CPPT
                    $join->on('cp.KUNJUNGAN', '=', 'pk.NOMOR');
                })
                ->leftJoinSub($subTindakan, 'td', function ($join) { // TINDAKAN
                    $join->on('td.KUNJUNGAN', '=', 'pk.NOMOR');
                })
                ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                ->leftJoin('pendaftaran.penjamin AS pj','pj.NOPEN','=','pp.NOMOR')
                ->leftJoin('bpjs.kunjungan AS kjs','kjs.noSEP','=','pj.NOMOR')
                ->leftJoin('medicalrecord.jadwal_kontrol AS jk','jk.KUNJUNGAN','=','pk.NOMOR')
                ->leftJoin('master.pasien AS ps','ps.NORM','=','pp.NORM')
                // ->leftJoin('master.kartu_identitas_pasien AS kip','kip.NORM','=','pp.NORM')
                ->leftJoin('aplikasi.pengguna','aplikasi.pengguna.ID','=','pk.DITERIMA_OLEH')
                ->leftJoin('master.ruangan AS ru','ru.ID','=','pk.RUANGAN')
                ->leftJoin('master.dokter AS dr','dr.ID','=','pk.DPJP')
                ->where(function ($query) {
                    $query->where('pk.RUANGAN', 'LIKE', '1020101%');
                            // ->orWhere('pk.RUANGAN', 'LIKE', '1020201%');
                })
                ->where('pj.JENIS', 2) // PENJAMIN BPJS ONLY
                ->where('pk.BARU', 1) // KUNJUNGAN PERTAMA
                ->where('ru.STATUS', 1) // STATUS RUANGAN AKTIF
                // ->where('jk.STATUS', 1) // STATUS RENCANA KONTROL AKTIF
                ->where('pk.STATUS', $status) // 0=BATAL;1=MASIH DILAYANI;2=SELESAI
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
            // ----------------------------------------------------------------------
            // print_r($show);
            // die();
            $getTgl = Carbon::parse($show[0]->JKONTROL);
            $tgl = $getTgl->isoFormat('DD');
            $bulan = $getTgl->isoFormat('MM');
            $tahun = $getTgl->isoFormat('YYYY');
            // ----------------------------------------------------------------------
            $input = public_path().'/doc/input/skdp/CetakSKDP.jrxml';
            $output = storage_path().'/app/public/files/skdp/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$show[0]->NOMOR;
            // Pastikan folder tujuan ada
            $outputDir = dirname($output);
            if (!File::exists($outputDir)) {
                File::makeDirectory($outputDir, 0755, true); // true = recursive
            }
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
            // ----------------------------------------------------------------------
            $getTgl = Carbon::parse($show[0]->TGLSEP);
            $tgl = $getTgl->isoFormat('DD');
            $bulan = $getTgl->isoFormat('MM');
            $tahun = $getTgl->isoFormat('YYYY');
            // ----------------------------------------------------------------------
            $input = public_path().'/doc/input/sep/CetakSEP.jrxml';
            $output = storage_path().'/app/public/files/sep/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$show[0]->NOMORSEP;
            // Pastikan folder tujuan ada
            $outputDir = dirname($output);
            if (!File::exists($outputDir)) {
                File::makeDirectory($outputDir, 0755, true); // true = recursive
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
            // return response()->download($output.'.pdf', null, [
            //     'Content-Type' => 'application/pdf',
            // ]);
            // return response()->json([
            //     'success' => true,
            //     'message' => 'File generated successfully.',
            //     'file_url' => '/doc/output/sep/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$show[0]->NOMORSEP.'.pdf',
            //     'nomor_sep' => '0151R0130124V002638'
            // ]);
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

            // try {
            //     $jasper->process($input, $output, $options)->execute();

            //     // dd($options);
            //     // 3. Hapus file JSON setelah proses berhasil
            //     if (file_exists($jsonPath)) {
            //         unlink($jsonPath);
            //     }

            //     // 4. Bisa langsung return response download atau apapun yang dibutuhkan
            //     return response()->file($output . '.pdf');

            // } catch (\Exception $e) {
            //     // Pastikan JSON tetap dihapus meskipun error
            //     if (file_exists($jsonPath)) {
            //         unlink($jsonPath);
            //     }
            //     throw $e; // Atau handle error sesuai kebutuhan
            // }

            // return response()->file($output.'.pdf',[
            //     'Content-Type' => 'application/pdf',
            // ]);
        }

        // function sep()
        // {
        //     return response()->file(public_path().'/doc/output/sep.pdf',[
        //         'Content-Type' => 'application/pdf',
        //     ]);
        // }
}
