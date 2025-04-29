<?php

namespace App\Http\Controllers\Klaim\Smart;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\simrspku_klaim\klaim_verifikasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PHPJasper\PHPJasper;
use setasign\Fpdi\Fpdi;
use Carbon\Carbon;
use Auth, Storage;

class ApiSmartKlaimController extends Controller
{
    function table($pel,$bln,$dpjp)
    {
        $time = Carbon::now()->isoFormat('YYYY-MM-DD HH:mm:ss');
        [$year, $month] = explode('-', $bln);
        // MAIN QUERY
        $show = DB::table('pendaftaran.kunjungan AS pk')
                ->select(
                    'pk.*',
                    'pp.NORM','pp.TANGGAL AS TGLDAFTAR',
                    'kjs.noSEP AS NOSEP','kjs.tglSEP AS TGLSEP',
                    'ru.DESKRIPSI AS NAMARUANGAN',
                    DB::raw('master.getNamaLengkap(ps.NORM) AS NAMAPASIEN'),
                    DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER')
                )
                ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                ->leftJoin('pendaftaran.penjamin AS pj','pj.NOPEN','=','pp.NOMOR')
                ->leftJoin('bpjs.kunjungan AS kjs','kjs.noSEP','=','pj.NOMOR')
                ->leftJoin('master.pasien AS ps','ps.NORM','=','pp.NORM')
                ->leftJoin('master.ruangan AS ru','ru.ID','=','pk.RUANGAN')
                ->leftJoin('master.dokter AS dr','dr.ID','=','pk.DPJP')
                ->where(function ($query) {
                    $query->where('pk.RUANGAN', 'LIKE', '1020101%');
                            // ->orWhere('pk.RUANGAN', 'LIKE', '1020201%');
                })
                // ->where(function ($query) use ($tgls,$tgle) {
                //     $query->whereRaw("LEFT(pk.MASUK, 10) BETWEEN ? AND ?", [$tgls, $tgle]);
                // })
                ->where(function ($query) use ($year,$month) {
                    $query->whereYear('pk.MASUK', $year)
                            ->whereMonth('pk.MASUK', $month);
                })
                ->when($dpjp != 0, function ($query) use ($dpjp) {
                    // Hanya menambahkan where jika $dpjp bukan 0
                    $query->where('dr.NIP', $dpjp);
                })
                ->where('pj.JENIS', 2) // PENJAMIN BPJS ONLY
                ->where('pk.BARU', 1) // KUNJUNGAN PERTAMA
                ->where('ru.STATUS', 1) // STATUS RUANGAN AKTIF
                ->where('pk.STATUS', 2) // KUNJUNGAN SELESAI
                // ->when($status != 5, function ($query) use ($status) { // 0=BATAL;1=MASIH DILAYANI;2=SELESAI;5=ALL
                //     $query->where('pk.STATUS', $status);
                // })
                ->where('pk.KELUAR', '!=', null)
                ->orderBy('pk.MASUK','DESC')
                ->get();

        $data = [
            'show' => $show,
            'time' => $time,
        ];

        return response()->json($data, 200);
    }

    function submit(Request $request)
    {
        $request->validate([
            // 'file' => ['max:5000','mimes:pdf'],
            'kunjungan' => 'required',
            // 'resume' => 'required',
            // 'sep' => 'required',
            // 'skdp' => 'required',
            // 'billing' => 'required',
            // 'individual' => 'required',
            // 'laboratorium' => 'required',
            // 'radiologi' => 'required',
            // 'triage' => 'required',
            // 'operasi' => 'required',
            'user' => 'required',
        ]);

        // INITIALIZE
        $now = Carbon::now();
        $basePath = storage_path('app/public/files/skdp/'.$tahun.'/'.$bulan.'/'.$tgl.'/');

        $files = [
            $basePath . 'file1.pdf',
            $basePath . 'file2.pdf',
            $basePath . 'file3.pdf',
        ];

        $pdf = new Fpdi();

        foreach ($files as $file) {
            if (!file_exists($file)) {
                return response()->json(['error' => "File tidak ditemukan: $file"], 404);
            }

            $pageCount = $pdf->setSourceFile($file);
            for ($page = 1; $page <= $pageCount; $page++) {
                $templateId = $pdf->importPage($page);
                $size = $pdf->getTemplateSize($templateId);
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId);
            }
        }

        $outputPath = storage_path("app/public/files/klaim/{$tahun}/{$bulan}/{$request->kunjungan}.pdf");
        $pdf->Output($outputPath, 'F');
        // return response()->download($outputPath);


            // $getFile = $request->file('file');
            // if ($getFile == null) {
            //     $path = null;
            //     $title = null;
            // } else {
            //     $find = surat_masuk::where('title',$getFile->getClientOriginalName())->first();
            //     if ($find == null) {
            //         $path = $getFile->store('public/files/tu/suratmasuk');
            //         $title = $getFile->getClientOriginalName();
            //     } else {
            //         return redirect()->back()->withErrors('Maaf, Nama file '.$getFile->getClientOriginalName().' sudah pernah diupload. Mohon Ganti Nama File yang berbeda. Disarankan untuk menambahkan kode yang unik pada File Anda.');
            //     }
            // }

        $data               = new klaim_verifikasi;
        $data->kunjungan    = $request->kunjungan;
        $data->user         = $request->user;
        $data->tgl          = $now;
        $data->title        = $title;
        $data->filename     = $filename;
        $data->koleksi      = $koleksi;
        $data->status       = true;
        $data->created_at   = $now;
        $data->updated_at   = $now;
        $data->deleted_at   = $now;

        // $data->save();

        return response()->json($data, 200);
    }

}
