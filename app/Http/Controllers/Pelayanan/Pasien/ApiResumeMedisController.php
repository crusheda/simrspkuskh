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
    function compileResumeRj($kunjungan)
    {
        $getRESUMERJ = DB::table('pendaftaran.kunjungan AS pk')
                ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                ->leftJoin('pendaftaran.penjamin AS pj','pp.NOMOR','=','pj.NOPEN')
                ->select('pj.NOMOR AS NOSEP','pp.NOMOR AS NOPEN','pk.NOMOR AS NOMOR')
                ->where('pk.NOMOR',$kunjungan)
                ->first();

        $show = DB::select('CALL medicalrecord.CetakMR5Custom(?,?)',[$getRESUMERJ->NOPEN,$getRESUMERJ->NOMOR]);
        $obat = DB::select('CALL medicalrecord.CetakObatRJ(?)',[$getRESUMERJ->NOPEN]);

        $keluhan = $show[0]->KELUHAN = str_replace("//", "/", $show[0]->KELUHAN);
        $subyektif = $show[0]->SUBYEKTIF = str_replace("//", "/", $show[0]->SUBYEKTIF);
        // print_r($obat);
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
                'ASSESMENT' => $show[0]->ASSESMENT,
                'SUBYEKTIF' => $subyektif,
                'OBYEKTIF' => $show[0]->OBYEKTIF,
                'PLANNING' => $show[0]->PLANNING,
                'INSTRUKSI' => $show[0]->INSTRUKSI,
                'TINDAKAN' => $show[0]->TINDAKAN,
                'KONSUL' => $show[0]->KONSUL,
                'DOKTER' => $show[0]->DOKTER,
                'NAMAOBAT' => $show[0]->NAMAPASIEN,
                'KELUHAN' => $keluhan,
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
}
