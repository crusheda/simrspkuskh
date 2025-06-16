<?php

namespace App\Http\Controllers\Pelayanan\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PHPJasper\PHPJasper;
use Carbon\Carbon;
use Auth, Storage;

class ApiResumeMedisController extends Controller
{
    function table($rawat, $status, $tgls, $tgle, $dpjp, $berkas)
    {
        // INIT
        $time = Carbon::now()->isoFormat('YYYY-MM-DD HH:mm:ss');

        // MAIN QUERY
        $show = DB::table('pendaftaran.kunjungan AS pk')
                ->select(
                    'pk.*',
                    'pp.NORM','pp.TANGGAL AS TGLDAFTAR',
                    'kjs.noSEP AS NOSEP','kjs.tglSEP AS TGLSEP',
                    'ru.DESKRIPSI AS NAMARUANGAN',
                    // 'cp.TANGGAL AS TGLCPPT',
                    // 'td.TANGGAL AS TGLTINDAKAN',
                    'jk.NOMOR AS NOSURKON','jk.NOMOR_BOOKING AS NOMORBOOKING',
                    DB::raw('master.getNamaLengkap(ps.NORM) AS NAMAPASIEN'),
                    DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER')
                )
                // ->leftJoinSub($subCppt, 'cp', function ($join) { // CPPT
                //     $join->on('cp.KUNJUNGAN', '=', 'pk.NOMOR');
                // })
                // ->leftJoinSub($subTindakan, 'td', function ($join) { // TINDAKAN
                //     $join->on('td.KUNJUNGAN', '=', 'pk.NOMOR');
                // })
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
                ->where(function ($query) use ($tgls,$tgle) {
                    $query->whereRaw("LEFT(pk.MASUK, 10) BETWEEN ? AND ?", [$tgls, $tgle]);
                })
                ->when($dpjp != 0, function ($query) use ($dpjp) {
                    // Hanya menambahkan where jika $dpjp bukan 0
                    $query->where('dr.NIP', $dpjp);
                })
                ->where('pj.JENIS', 2) // PENJAMIN BPJS ONLY
                ->where('pk.BARU', 1) // KUNJUNGAN PERTAMA
                ->where('ru.STATUS', 1) // STATUS RUANGAN AKTIF
                // ->where('jk.STATUS', 1) // STATUS RENCANA KONTROL AKTIF

                ->when($status != 5, function ($query) use ($status) { // 0=BATAL;1=MASIH DILAYANI;2=SELESAI;5=ALL
                    $query->where('pk.STATUS', $status);
                })
                // ->where('pk.KELUAR', null)
                ->orderBy('pk.MASUK','DESC')
                ->get();
                // print_r($show);
                // die();
        $data = [
            'show' => $show,
            'time' => $time,
        ];

        return response()->json($data, 200);
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
        $output = storage_path().'/app/public/files/resume/RJ/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$show[0]->KUNJUNGAN;
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

    function cleanText($text) {
        $allowedTags = '<br><b><i><u>';
        $text = strip_tags($text, $allowedTags);
        return str_replace("\n", "<br>", $text);
    }
}
