<?php

namespace App\Http\Controllers\Klaim\Smart;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\simrspku_klaim\klaim_verifikasi_catatan;
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
                    DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'),
                    'kv.id AS IDKLAIM','kv.verif AS STATUSVERIF','kv.verif_tgl AS TGLVERIF',DB::raw('master.getNamaLengkapPegawai(pe.NIP) AS NAMAVERIF'),
                    // DB::raw('CASE WHEN kvc.id IS NOT NULL THEN true ELSE false END AS CATATAN')
                    DB::raw('(SELECT CASE WHEN COUNT(*) > 0 THEN true ELSE false END FROM simrspku_klaim.klaim_verifikasi_catatan AS kvc WHERE kvc.nomor = pk.NOMOR AND kvc.status = true) AS CATATAN')
                )
                ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                ->leftJoin('pendaftaran.penjamin AS pj','pj.NOPEN','=','pp.NOMOR')
                ->leftJoin('medicalrecord.perencanaan_rawat_inap AS pri','pri.KUNJUNGAN','=','pk.NOMOR')
                ->leftJoin('pembayaran.tagihan_pendaftaran AS tp','tp.PENDAFTARAN','=','pk.NOPEN')
                ->leftJoin('bpjs.kunjungan AS kjs','kjs.noSEP','=','pj.NOMOR')
                ->leftJoin('master.pasien AS ps','ps.NORM','=','pp.NORM')
                ->leftJoin('master.ruangan AS ru','ru.ID','=','pk.RUANGAN')
                ->leftJoin('master.dokter AS dr','dr.ID','=','pk.DPJP')
                ->leftJoin('simrspku_klaim.klaim_verifikasi AS kv', function($join) {
                    $join->on('kv.nomor','=','pk.NOMOR')
                        ->where('kv.status', true);
                })
                // ->leftJoin('simrspku_klaim.klaim_verifikasi_catatan AS kvc', function($join) {
                //     $join->on('kvc.nomor','=','pk.NOMOR')
                //         ->where('kvc.status', true);
                // })
                ->leftJoin('aplikasi.pengguna AS pe','pe.ID','=','kv.verif_user')
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

    function showUpload($id)
    {
        $getFile = klaim_file::where('id',$id)->first();
        $output = storage_path('app/public/'.$getFile->filename);

        if (!file_exists($output)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->file($output,[
            'Content-Type' => 'application/pdf',
        ]);
    }

    function upload(Request $request)
    {
        $request->validate([
            'nama_tambahan' => ['required'],
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:1000'],
        ], [
            'nama_tambahan.required' => 'Isian Nama / Jenis Berkas Tidak Boleh Kosong.',
            'file.required' => 'File harus diunggah.',
            'file.mimes' => 'File harus berupa JPG, PNG, atau PDF.',
            'file.max' => 'Ukuran file maksimal 1 MB.',
        ]);
        $getSEP = DB::table('pendaftaran.kunjungan AS pk')
                ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                ->leftJoin('pendaftaran.penjamin AS pj','pp.NOMOR','=','pj.NOPEN')
                ->select('pj.NOMOR AS NOSEP')
                ->where('pk.NOMOR',$request->kunjungan)
                ->first();
        $show = DB::select('CALL simrspku_klaim.CetakSEP(?)',[$getSEP->NOSEP]);
        if (empty($show)) {
            return response()->json(['message' => 'SEP Tidak Ditemukan'], 400);
        }
        // ----------------------------------------------------------------------
        $getTgl = Carbon::parse($show[0]->TGLSEP);
        $tgl = $getTgl->isoFormat('DD');
        $bulan = $getTgl->isoFormat('MM');
        $tahun = $getTgl->isoFormat('YYYY');
        // ----------------------------------------------------------------------
        $validasi = klaim_file::where('nomor',$request->kunjungan)
                                ->where('jenis',10)
                                ->where('status',1)
                                ->whereNull('deleted_at')
                                ->count();

        $uploadedFile = $request->file('file');
        $path = 'files/tambahan/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$request->kunjungan .'-'. ($validasi+1). '.pdf';
        $output = storage_path('app/public/' . $path);

        // Buat folder kalau belum ada
        $outputDir = dirname($output);
        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }

        if ($uploadedFile) {
            $mime = $uploadedFile->getMimeType();

            if (in_array($mime, ['image/jpeg', 'image/png'])) {
                $pdf = new Fpdi();
                $pdf->AddPage();

                list($width, $height) = getimagesize($uploadedFile->getPathname());

                $widthMm = $width * 0.264583;
                $heightMm = $height * 0.264583;

                $scale = min(210 / $widthMm, 297 / $heightMm, 1);
                $widthMm *= $scale;
                $heightMm *= $scale;

                // Simpan sementara file dengan ekstensi asli
                $ext = $uploadedFile->getClientOriginalExtension();
                $tmpImagePath = storage_path('app/temp_image_' . uniqid() . '.' . $ext);
                $uploadedFile->move(dirname($tmpImagePath), basename($tmpImagePath));

                $pdf->Image($tmpImagePath, 0, 0, $widthMm, $heightMm);
                $pdf->Output($output, 'F');

                // Hapus file sementara
                if (file_exists($tmpImagePath)) {
                    unlink($tmpImagePath);
                }

            } elseif ($mime === 'application/pdf') {
                $uploadedFile->storeAs(dirname($path), basename($path), 'public');
            } else {
                return response()->json(['message' => 'Format file tidak didukung.'], 400);
            }

            // SAVE TO DB
            $post = new klaim_file;
            $post->jenis = 10;
            $post->sub_jenis = $validasi+1;
            $post->nomor = $request->kunjungan;
            $post->title = $request->kunjungan.'-'.($validasi+1).'.pdf';
            $post->filename = $path;
            $post->nama_tambahan = $request->nama_tambahan;
            $post->status = true;
            $post->user = Auth::user()->ID;
            $post->save();

            return response()->json(['message' => 'Berkas '.$request->nama_tambahan.' telah berhasil diupload dengan nama '.$request->kunjungan.'-'.($validasi+1).'.pdf'], 200);
        } else {
            return response()->json(['message' => 'File Berkas Upload tidak ditemukan'], 400);
        }
    }

    function hapusUpload($id)
    {
        $now = Carbon::now()->isoFormat('YYYY-MM-DD HH:mm:ss');

        $show = klaim_file::where('id',$id)->first();
        $show->status = false;

        if (Storage::disk('public')->exists($show->filename)) {
            Storage::disk('public')->delete($show->filename);
        }

        $show->save();
        $show->delete();

        return response()->json($now, 200);
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
                ->whereNull('deleted_at')
                ->whereIn('jenis', $jenisDipilih)
                ->get();

            $getFileKlaimTambahan = klaim_file::where('nomor', $request->kunjungan)
                ->where('status', true)
                ->whereNull('deleted_at')
                ->where('jenis', 10)
                ->where('sub_jenis', '!=', null)
                ->get();

            foreach ($getFileKlaim as $value) {
                $koleksi[] = $value->jenis;
                $files[] = $value->filename;
            }

            if ($getFileKlaimTambahan->isNotEmpty()) {
                foreach ($getFileKlaimTambahan as $value) {
                    $koleksi[] = (int)($value->jenis . $value->sub_jenis);
                    $files[] = $value->filename;
                }
            }
        } else {
            $getFileKlaimTambahan = klaim_file::where('nomor', $request->kunjungan)
                ->where('status', true)
                ->whereNull('deleted_at')
                ->where('jenis', 10)
                ->where('sub_jenis', '!=', null)
                ->get();

            if ($getFileKlaimTambahan->isNotEmpty()) {
                foreach ($getFileKlaimTambahan as $value) {
                    $koleksi[] = (int)($value->jenis . $value->sub_jenis);
                    $files[] = $value->filename;
                }
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

    function getCatatan($kunjungan) // Semua catatan berkas klaim kunjungan tersebut
    {
        $show = DB::table('simrspku_klaim.klaim_verifikasi_catatan AS kvc')
                        ->leftJoin('aplikasi.pengguna AS pe','pe.ID','=','kvc.user')
                        ->select('kvc.*',DB::raw('master.getNamaLengkapPegawai(pe.NIP) AS NAMAPEGAWAI'))
                        ->where('kvc.nomor',$kunjungan)
                        ->where('kvc.status',true)
                        ->orderBy('kvc.created_at','ASC')
                        ->get();

        $data = [
            'show' => $show,
        ];

        return response()->json($data, 200);
    }

    function showCatatan($id) // Hanya catatan spesifik GET BY ID
    {
        $show = DB::table('simrspku_klaim.klaim_verifikasi_catatan AS kvc')
                        ->leftJoin('aplikasi.pengguna AS pe','pe.ID','=','kvc.user')
                        ->select('kvc.*',DB::raw('master.getNamaLengkapPegawai(pe.NIP) AS NAMAPEGAWAI'))
                        ->where('kvc.id',$id)
                        ->where('kvc.status',true)
                        ->first();

        return response()->json($show, 200);
    }

    function solvedCatatan($id) // Hanya catatan spesifik GET BY ID
    {
        $show = klaim_verifikasi_catatan::find($id);
        $show->solved = true;
        $show->user_solved = Auth::user()->ID;
        $show->save();

        return response()->json($show, 200);
    }

    function unsolvedCatatan($id) // Hanya catatan spesifik GET BY ID
    {
        $show = klaim_verifikasi_catatan::find($id);
        $show->solved = false;
        $show->user_solved = Auth::user()->ID;
        $show->save();

        return response()->json($show, 200);
    }

    function simpanCatatan(Request $request)
    {
        $now = Carbon::now()->isoFormat('YYYY-MM-DD HH:mm:ss');

        // print_r(Auth::user()->ID);
        // die();
        $data = new klaim_verifikasi_catatan;
        $data->nomor        = $request->kunjungan;
        $data->user         = Auth::user()->ID;
        $data->deskripsi    = $request->catatan;
        $data->status       = true;
        $data->save();

        return response()->json($data, 200);
    }

    function ubahCatatan(Request $request)
    {
        $now = Carbon::now()->isoFormat('YYYY-MM-DD HH:mm:ss');

        $show               = klaim_verifikasi_catatan::find($request->id);
        $show->user         = Auth::user()->ID;
        $show->deskripsi    = $request->catatan;
        $show->save();

        $data = [
            'now' => $now,
            'show' => $show,
        ];

        return response()->json($data, 200);
    }

    function hapusCatatan($id)
    {
        $now = Carbon::now()->isoFormat('YYYY-MM-DD HH:mm:ss');
        $show = klaim_verifikasi_catatan::where('id',$id)->first();
        $show->status = false;
        $show->save();
        $show->delete();

        return response()->json($now, 200);
    }

    function getKlaim($kunjungan)
    {
        $show = klaim_verifikasi::where('nomor',$kunjungan)->where('status',true)->first();
        $catatan = DB::table('simrspku_klaim.klaim_verifikasi_catatan AS kvc')
                        ->leftJoin('aplikasi.pengguna AS pe','pe.ID','=','kvc.user')
                        ->select('kvc.*',DB::raw('master.getNamaLengkapPegawai(pe.NIP) AS NAMAPEGAWAI'))
                        ->where('kvc.nomor',$kunjungan)
                        ->where('kvc.status',true)
                        ->orderBy('kvc.created_at','DESC')
                        ->get();
        $file = klaim_file::where('nomor',$kunjungan)->where('status',true)->whereNull('deleted_at')->get();

        $data = [
            'show' => $show,
            'catatan' => $catatan,
            'file' => $file,
        ];

        return response()->json($data, 200);
    }

    function verifikasiKlaim($kunjungan)
    {
        $now = Carbon::now();
        $time = $now->isoFormat('YYYY-MM-DD HH:mm:ss');

        $show = klaim_verifikasi::where('nomor',$kunjungan)->where('status',true)->first();
        $show->verif = true;
        $show->verif_user = Auth::user()->ID;
        $show->verif_tgl = $now;
        $show->save();

        return response()->json($time, 200);
    }

    function batalVerifikasiKlaim($kunjungan)
    {
        $now = Carbon::now()->isoFormat('YYYY-MM-DD HH:mm:ss');

        $show = klaim_verifikasi::where('nomor',$kunjungan)->where('status',true)->first();
        $show->verif = false;
        $show->verif_user = null;
        $show->verif_tgl = null;
        $show->save();

        return response()->json($now, 200);
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
        $now = Carbon::now()->isoFormat('YYYY-MM-DD HH:mm:ss');
        $show = klaim_verifikasi::where('nomor',$kunjungan)->where('status',true)->first();
        $show->status = false;
        $show->save();
        $show->delete();

        return response()->json($now, 200);
    }

}
