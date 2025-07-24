<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\simrspku_klaim\klaim_verifikasi;
use App\Models\simrspku_klaim\klaim_file;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PHPJasper\PHPJasper;
use Carbon\Carbon;
use Auth, Storage;

class ApiRehabMedikController extends Controller
{
    function getFormKfr($NORM, $KUNJUNGAN)
    {
        $show = DB::table('simrspku_klaim.form_kfr AS kfr')
                ->where('kfr.rm',$NORM)
                ->whereNull('kfr.deleted_at')
                ->orderBy('kfr.tgl','DESC')
                ->get();

        $form = DB::table('simrspku_klaim.form_kfr AS kfr')
                ->where('kfr.nomor',$KUNJUNGAN)
                ->whereNull('kfr.deleted_at')
                ->first();

        $data = [
            'show' => $show,
            'form' => $form,
        ];

        return response()->json($data, 200);
    }

    function simpanFormKfrBaru(Request $request)
    {
        $request->validate([
            'tte' => 'required|string',
        ]);

        $now = Carbon::now();
        $getTtdDokter = DB::table('simrspku_klaim.tanda_tangan_pegawai')
            ->where('nip', $request->user)
            ->whereNull('deleted_at')
            ->first();

        if (!$getTtdDokter) {
            return Response::json(array(
                'message' => 'Tanda tangan User/Dokter tidak ditemukan. Silakan memperbarui Data TTE pada halaman Profil.',
                'code' => 500,
            ));
        }

        $validateDuplicate = DB::table('simrspku_klaim.form_kfr AS kfr')
                ->where('nomor',$request->nomor)
                ->whereNull('kfr.deleted_at')
                ->count();
        if ($validateDuplicate > 0) {
            return Response::json(array(
                'message' => 'Data Formulir sudah Ada di Database Kami.',
                'code' => 500,
            ));
        }

        $image = str_replace('data:image/png;base64,', '', $request->tte);
        $image = str_replace(' ', '+', $image);
        $filename = time().'.png';
        $path_tte_pasien = "signatures/pasien/{$request->rm}/{$filename}";
        Storage::disk('public')->put($path_tte_pasien, base64_decode($image));

        // Insert ke tabel simrspku_klaim.form_kfr
        $getGroupMax = DB::table('simrspku_klaim.form_kfr AS kfr')
                ->whereNull('kfr.deleted_at')
                ->max('kfr.group');
        $newGroup = $getGroupMax ? ($getGroupMax + 1) : 1;
        DB::table('simrspku_klaim.form_kfr')->insert([
            'group' => $newGroup,
            'nomor' => $request->nomor,
            'rm' => $request->rm,
            'tgl' => $now,
            'anamnesa' => $request->anamnesa,
            'pemeriksaan_fisik' => $request->fisik,
            'diagnosa_medis' => $request->diagmedis,
            'diagnosa_fungsi' => $request->diagfungsi,
            'pemeriksaan_penunjang' => $request->penunjang,
            'tata_laksana_kfr' => $request->tatalaksana,
            'anjuran' => $request->anjuran,
            'evaluasi' => $request->evaluasi,
            'target' => $request->target,
            'spak_index' => $request->suspek,
            'spak' => $request->suspekya,
            'dokter' => $request->dokter,
            'tte_pasien' => $path_tte_pasien,
            'tte_dokter' => $getTtdDokter->signature_path,
            'tgl_tte_pasien' => $now,
        ]);

        return Response::json(array(
            'message' => 'Formulir Rawat Jalan Layanan KFR telah berhasil diterbitkan',
            'code' => 200,
        ));
    }

    function compileFormKfr()
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
}
