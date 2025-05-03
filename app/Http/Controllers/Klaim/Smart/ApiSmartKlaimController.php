<?php

namespace App\Http\Controllers\Klaim\Smart;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\simrspku_klaim\klaim_verifikasi;
use App\Models\simrspku_klaim\klaim_file;
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
        // $parts = explode('-', $bln);
        // $year = $parts[0] ?? null;
        // $month = $parts[1] ?? null;

        // if (!$year || !$month) {
        //     // Tampilkan pesan atau logging
        //     throw new \Exception("Format bulan tidak valid: $bln");
        // }

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
                ->leftJoin('medicalrecord.perencanaan_rawat_inap AS pri','pri.KUNJUNGAN','=','pk.NOMOR')
                ->leftJoin('pembayaran.tagihan_pendaftaran AS tp','tp.PENDAFTARAN','=','pk.NOPEN')
                ->leftJoin('bpjs.kunjungan AS kjs','kjs.noSEP','=','pj.NOMOR')
                ->leftJoin('master.pasien AS ps','ps.NORM','=','pp.NORM')
                ->leftJoin('master.ruangan AS ru','ru.ID','=','pk.RUANGAN')
                ->leftJoin('master.dokter AS dr','dr.ID','=','pk.DPJP')
                // ->where(function ($query) {
                //     $query->where('pk.RUANGAN', 'LIKE', '1020101%');
                // })

                // FILTER RUANGAN
                ->when(in_array($pel, [1, 2, 3]), function ($query) use ($pel) {
                    $prefix = '';
                    switch ($pel) {
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
                ->when($pel == 5, function ($query) {
                    $query->where(function ($q) {
                        $q->where('pk.RUANGAN', 'LIKE', '1020101%')
                            ->orWhere('pk.RUANGAN', 'LIKE', '1020201%')
                            ->orWhere('pk.RUANGAN', 'LIKE', '1020301%');
                    });
                })

                // ->where(function ($query) use ($tgls,$tgle) {
                //     $query->whereRaw("LEFT(pk.MASUK, 10) BETWEEN ? AND ?", [$tgls, $tgle]);
                // })
                ->where(function ($query) use ($year,$month) {
                    $query->whereYear('pk.MASUK', $year)
                            ->whereMonth('pk.MASUK', $month);
                })

                // KHUSUS RAWAT DARURAT
                ->when($pel == 2, function ($query) use ($pel) {
                    $query->where(function ($q) {
                        $q->where('tp.UTAMA', 1)
                            ->where('tp.STATUS', 1)
                            ->whereNull('pri.KUNJUNGAN');
                    });
                })

                ->when($dpjp != 0, function ($query) use ($dpjp) {
                    // Hanya menambahkan where jika $dpjp bukan 0
                    $query->where('dr.NIP', $dpjp);
                })
                ->where('pj.JENIS', 2) // PENJAMIN BPJS ONLY
                ->where('pk.BARU', 1) // KUNJUNGAN PERTAMA
                ->where('ru.STATUS', 1) // STATUS RUANGAN AKTIF
                ->where('pk.STATUS', 2) // KUNJUNGAN SELESAI
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
            'sep' => 'required',
            'resume' => 'required',
            'skdp' => 'required',
            'individual' => 'required',
            'billing' => 'required',
            'laboratorium' => 'required',
            'radiologi' => 'required',
            'triage' => 'required',
            'operasi' => 'required',
            'user' => 'required',
        ]);

        // INITIALIZE
        $now = Carbon::now();
        $getSEP = DB::table('pendaftaran.kunjungan AS pk')
                ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                ->leftJoin('pendaftaran.penjamin AS pj','pp.NOMOR','=','pj.NOPEN')
                ->select('pj.NOMOR AS NOSEP')
                ->where('pk.NOMOR',$request->kunjungan)
                ->first();
        $show = DB::select('CALL simrspku_klaim.CetakSEP(?)',[$getSEP->NOSEP]);
        $getTgl = Carbon::parse($show[0]->TGLSEP);
        $bulan = $getTgl->isoFormat('MM');
        $tahun = $getTgl->isoFormat('YYYY');

        // MAKE ARRAY ALL COLLECTION FILE PDF KLAIM
        $koleksi = [];
        $files = [];
        $mapJenis = [
            'sep'          => 1,
            'resume'       => 2,
            'skdp'         => 3,
            'individual'   => 4,
            'billing'      => 5,
            'laboratorium' => 6,
            'radiologi'    => 7,
            'triage'       => 8,
            'operasi'      => 9,
        ];

        // Ambil hanya jenis yang checkbox-nya dicentang
        $jenisDipilih = [];

        foreach ($mapJenis as $key => $jenis) {
            if ($request->boolean($key)) {
                $jenisDipilih[] = $jenis;
            }
        }

        if (!empty($jenisDipilih)) {
            $getFileKlaim = klaim_file::where('nomor', $request->kunjungan)
                ->where('status', true)
                ->whereIn('jenis', $jenisDipilih)
                ->get();

            foreach ($getFileKlaim as $value) {
                $koleksi[] = $value->jenis;
                $files[] = $value->filename;
            }
        }

        // EXECUTE PROCESS
        $pdf = new Fpdi();
        foreach ($files as $file) {
            if (!file_exists(storage_path().'/app/public/'.$file)) {
                return response()->json(['error' => "File tidak ditemukan: $file"], 404);
            }

            $pageCount = $pdf->setSourceFile(storage_path().'/app/public/'.$file);
            for ($page = 1; $page <= $pageCount; $page++) {
                $templateId = $pdf->importPage($page);
                $size = $pdf->getTemplateSize($templateId);
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId);
            }
        }

        // STORE GROUP OF PDF
        $title = $request->kunjungan.'.pdf';
        $path ="files/klaim/{$tahun}/{$bulan}/{$title}";
        $outputPath = storage_path()."/app/public/".$path;
        $outputDir = dirname($outputPath);
        if (!File::exists($outputDir)) { // Buat folder baru apabila tidak ada foldernya
            File::makeDirectory($outputDir, 0755, true); // true = recursive
        }
        if (File::exists($outputPath)) { // ✅ Aman untuk overwrite
            File::delete($outputPath); // hapus file lama sebelum simpan
        }
        // 'F' → File: simpan ke path file di server.
        // 'I' → Inline: tampilkan langsung di browser (sebagai preview PDF).
        // 'D' → Download: langsung paksa download via browser.
        // 'S' → String: kembalikan isi PDF sebagai string (bisa simpan ke variabel).

        // SAVING RECORD TO DB
        $verify = klaim_verifikasi::where('nomor',$request->kunjungan)->where('status',true)->first();
        if (!$verify) {
            $push               = new klaim_verifikasi;
            $push->nomor        = $request->kunjungan;
            $push->user         = $request->user;
            $push->bulan        = $bulan;
            $push->tahun        = $tahun;
            $push->title        = $title;
            $push->filename     = $path;
            $push->koleksi      = json_encode($koleksi);
            $push->status       = true;
            $push->save();
        } else {
            $push               = klaim_verifikasi::find($verify->id);
            $push->koleksi      = json_encode($koleksi);
            $push->save();
        }
        $pdf->Output($outputPath, 'F');

        $data = [
            'pdf' => $pdf,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'kunjungan' => $request->kunjungan,
            'message' => 'PDF Berhasil di Submit',
        ];

        return response()->json($data, 200);
    }

    function verifSep($sep)
    {
        $show = DB::table('pendaftaran.penjamin AS pj')
                    ->leftJoin('pendaftaran.kunjungan AS pk','pk.NOPEN','=','pj.NOPEN')
                    ->select('pk.NOMOR')
                    ->where(function ($query) {
                        $query->where('pk.RUANGAN', 'LIKE', '1020101%')
                                ->orWhere('pk.RUANGAN', 'LIKE', '1020201%')
                                ->orWhere('pk.RUANGAN', 'LIKE', '1020301%');
                    })
                    ->where('pk.BARU', 1) // KUNJUNGAN BARU
                    ->where('pj.JENIS', 2) // PENJAMIN BPJS ONLY
                    ->where('pj.NOMOR', $sep)
                    ->orderBy('pk.MASUK','ASC')
                    ->first();

        // print_r($show);
        // die();
        if ($show) {
            $data = [
                'message' => 'No. SEP '.$sep.' Ditemukan',
                'kunjungan' => $show->NOMOR,
            ];
            $status = 200;
        } else {
            $data = 'No. SEP '.$sep.' Tidak Ditemukan';
            $status = 400;
        }

        return response()->json($data, $status);
    }

    function getKlaim($kunjungan)
    {
        $show = klaim_verifikasi::where('nomor',$kunjungan)->where('status',true)->first();
        $file = klaim_file::where('nomor',$kunjungan)->where('status',true)->get();

        $data = [
            'show' => $show,
            'file' => $file,
        ];

        // print_r($file);
        // die();
        return response()->json($data, 200);
    }

    function showKlaim($tahun, $bulan, $kunjungan)
    {
        $path = 'files/klaim/'.$tahun.'/'.$bulan.'/'.$kunjungan.'.pdf';
        $output = storage_path('app/public/'.$path);

        if (!file_exists($output)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($output,[
            'Content-Type' => 'application/pdf',
        ]);
        // return response()->file($output, [
        //     'Content-Type' => 'application/pdf',
        //     'Content-Disposition' => 'inline; filename="merged.pdf"'
        // ]);
    }

    function hapusKlaim($kunjungan)
    {
        $now = Carbon::now();
        $show = klaim_verifikasi::where('nomor',$kunjungan)->where('status',true)->first();
        $show->status = false;
        $show->delete();

        return response()->json($now, 200);

    }

}
