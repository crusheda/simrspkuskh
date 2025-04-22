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

class SmartKlaimController extends Controller
{
    // INDEX
    function index()
    {
        $dr = DB::table('master.dokter AS dr')
                ->select(
                    'dr.id',
                    'dr.NIP',
                    DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'),
                    'ref.DESKRIPSI'
                )
                ->leftJoin('master.pegawai AS pg','pg.NIP','=','dr.NIP')
                ->leftJoin('master.referensi AS ref', function($join) {
                    $join->on('ref.ID','=','pg.SMF')
                        ->where('ref.JENIS', '26');
                })
                ->leftJoin('master.dokter_ruangan AS dru','dru.DOKTER','=','dr.ID')
                ->where('dr.STATUS','1')
                ->where('dru.STATUS','1')
                ->where(function ($query) {
                    $query->where('dru.RUANGAN', 'LIKE', '1020101%');
                })
                // ->orderByRaw("CASE WHEN ref.ID = '0' THEN 1 ELSE 0 END")
                ->orderBy('ref.DESKRIPSI','ASC')
                ->groupBy('dr.id','dr.NIP','NAMADOKTER')
                ->get();

        $data = [
            'dr' => $dr,
        ];

        return view('pages.klaim.index')->with('list', $data);
    }

    // function compileSep($kunjungan)
    // {
    //     $getSEP = DB::table('pendaftaran.kunjungan AS pk')
    //             ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
    //             ->leftJoin('pendaftaran.penjamin AS pj','pp.NOMOR','=','pj.NOPEN')
    //             ->select('pj.NOMOR AS NOSEP')
    //             ->where('pk.NOMOR',$kunjungan)
    //             ->first();
    //     $show = DB::select('CALL simrspku_klaim.CetakSEP(?)',[$getSEP->NOSEP]);
    //     // ----------------------------------------------------------------------
    //     $getTgl = Carbon::parse($show[0]->TGLSEP);
    //     $tgl = $getTgl->isoFormat('DD');
    //     $bulan = $getTgl->isoFormat('MM');
    //     $tahun = $getTgl->isoFormat('YYYY');
    //     // ----------------------------------------------------------------------
    //     $input = public_path().'/doc/input/sep/CetakSEP.jrxml';
    //     $output = public_path().'/doc/output/sep/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$show[0]->NOMORSEP;
    //     // Pastikan folder tujuan ada
    //     $outputDir = dirname($output);
    //     if (!File::exists($outputDir)) {
    //         File::makeDirectory($outputDir, 0755, true); // true = recursive
    //     }
    //     $options = [
    //         'format' => ['pdf'], // 'xls' / 'rtf
    //         'params' => [
    //             'ASPEL' => $show[0]->ASPEL,
    //             'CATATAN' => $show[0]->CATATAN,
    //             'CETAKAN' => $show[0]->CETAKAN,
    //             'COB' => $show[0]->COB,
    //             'DIAGNOSA' => $show[0]->DIAGNOSA,
    //             'DOKTER' => $show[0]->DOKTER,
    //             'IMAGES_PATH' => public_path()."/doc/input/sep/",
    //             'JENISKELAMIN' => $show[0]->JENISKELAMIN,
    //             'JENISRAWAT' => $show[0]->JENISRAWAT,
    //             'KATARAK' => $show[0]->KATARAK,
    //             'KELAS' => $show[0]->KELAS,
    //             'klsRawat' => $show[0]->klsRawat,
    //             'NAMAINSTANSI' => $show[0]->NAMAINSTANSI,
    //             'NAMALENGKAP' => $show[0]->NAMALENGKAP,
    //             'NOMORKARTU' => $show[0]->NOMORKARTU,
    //             'NOMORSEP' => $show[0]->NOMORSEP,
    //             'NORM' => $show[0]->NORM,
    //             'NOTELP' => $show[0]->NOTELP,
    //             'PENJAMIN' => $show[0]->PENJAMIN,
    //             'PENUNJANG' => $show[0]->PENUNJANG,
    //             'PESERTA' => $show[0]->PESERTA,
    //             'POLIPERUJUK' => $show[0]->POLIPERUJUK,
    //             'poliTujuan' => $show[0]->poliTujuan,
    //             'PRB' => $show[0]->PRB,
    //             'PROC' => $show[0]->PROC,
    //             'RUJUKAN' => $show[0]->RUJUKAN,
    //             'TGL_LAHIR' => $show[0]->TGL_LAHIR,
    //             'TGLSEP' => $show[0]->TGLSEP,
    //             'TJKUNJ' => $show[0]->TJKUNJ,
    //             'UNITPELAYANAN' => $show[0]->UNITPELAYANAN
    //         ],
    //     ];

    //     $jasper = new PHPJasper;

    //     $jasper->process(
    //         $input,
    //         $output,
    //         $options
    //     )->execute();
    // }

}
