<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\simrspku_klaim\klaim_verifikasi;
use App\Models\simrspku_klaim\klaim_file;
use App\Models\simrspku_klaim\form_kfr;
use App\Models\Pengguna;
use App\Models\simrspku_klaim\form_kfr_jp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\TemplateProcessor;
use PHPJasper\PHPJasper;
use Carbon\Carbon;
use Auth, Storage;

class ApiRehabMedikController extends Controller
{
    // --------------------------------------------------- ADD ONN ------------------------------------------------
    public function libreOffice($input, $output) {
        // LINK DOWNLOAD LIBRE OFFICE = https://www.libreoffice.org/download/download
        $soffice = '"C:\Program Files\LibreOffice\program\soffice.exe"';
        $cmd = $soffice . ' --headless --convert-to pdf ' . escapeshellarg($input) . ' --outdir ' . escapeshellarg($output);
        exec($cmd, $log, $result);
        return [$log, $result];
    }

    public static function setImgWord(TemplateProcessor $templateProcessor, string $key, string $imagePath, int $targetWidth)
    {
        if (!file_exists($imagePath)) {
            throw new \Exception("Gambar tidak ditemukan: {$imagePath}");
        }

        [$originalWidth, $originalHeight] = getimagesize($imagePath);

        if ($originalWidth === 0) {
            throw new \Exception("Lebar gambar 0: {$imagePath}");
        }

        $ratio = $originalHeight / $originalWidth;
        $targetHeight = $targetWidth * $ratio;

        $templateProcessor->setImageValue($key, [
            'path' => $imagePath,
            'width' => $targetWidth,
            'height' => $targetHeight,
        ]);
    }

    // --------------------------------------------- FORM LAYANAN KFR ---------------------------------------------
    function getFormKfr($NORM, $KUNJUNGAN)
    {
        $show = DB::table('simrspku_klaim.form_kfr AS kfr')
                ->select('kfr.group',DB::raw("LPAD(kfr.rm, 8, '0') as rm"),DB::raw("DATE_FORMAT(kfr.tgl, '%e %M %Y') as tgl"),'kfr.nama_dokter')
                ->where('kfr.rm',$NORM)
                ->whereNull('kfr.deleted_at')
                ->orderBy('kfr.id','ASC')
                ->groupBy('kfr.group',DB::raw("LPAD(kfr.rm, 8, '0')"),DB::raw("DATE_FORMAT(kfr.tgl, '%e %M %Y')"),'kfr.nama_dokter')
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

    function getFormKfrByGroup($GROUP)
    {
        $data = DB::table('simrspku_klaim.form_kfr AS kfr')
                ->where('kfr.group',$GROUP)
                ->whereNull('kfr.deleted_at')
                ->orderBy('kfr.id','ASC')
                ->first();

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
            'nama_dokter' => $getTtdDokter->nama_pegawai,
            'tte_pasien' => $path_tte_pasien,
            'tte_dokter' => $getTtdDokter->signature_path,
            'tgl_tte_pasien' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return Response::json(array(
            'message' => 'Formulir Rawat Jalan Layanan KFR telah berhasil diterbitkan',
            'code' => 200,
        ));
    }

    function simpanFormKfrLama(Request $request)
    {
        $formLama = form_kfr::where('group',$request->group)
                            ->whereNull('deleted_at')
                            ->orderBy('id','ASC')
                            ->first();
        if ($formLama) {
            $now = Carbon::now();

            // Clone model
            $formBaru = $formLama->replicate();

            // Change Value
            $formBaru->nomor = $request->nomor;
            $formBaru->created_at = $now;
            $formBaru->updated_at = $now;

            // Simpan sebagai baris baru
            $formBaru->save();

            return Response::json(array(
                'message' => 'Formulir Rawat Jalan Layanan KFR telah berhasil diterbitkan',
                'code' => 200,
            ));
        } else {
            return response()->json("Error mengambil Data Formulir KFR Lama dari (GROUPID:".$request->group.")", 401);
        }
    }

    function hapusFormKfr($NOMOR,$USER)
    {
        $now = Carbon::now()->isoFormat('YYYY-MM-DD HH:mm:ss');

        $show = form_kfr::where('nomor',$NOMOR)->first();
        $show->user_deleted = $USER;
        $show->save();
        $show->delete();

        return response()->json($now, 200);
    }

    function compileFormKfr($KUNJUNGAN,$GROUP)
    {
        $show = DB::table('simrspku_klaim.form_kfr AS fkfr')
                ->leftJoin('master.pasien AS ps', function($join) {
                    $join->on('ps.NORM','=','fkfr.rm')
                        ->where('ps.status', true);
                })
                ->leftJoin('master.kontak_pasien AS kps','ps.NORM','=','kps.NORM')
                ->leftJoin('aplikasi.pengguna AS pe','pe.ID','=','fkfr.dokter')
                ->select(
                    'fkfr.*',
                    'ps.TANGGAL_LAHIR AS TGLLAHIRPASIEN',
                    'kps.NOMOR AS NOHPPASIEN',
                    'pe.NIP AS NIPDOKTER',
                    DB::raw('master.getNamaLengkap(fkfr.rm) AS NAMAPASIEN'),
                    DB::raw('master.getAlamatPasienCustom(fkfr.rm) AS ALAMATPASIEN'),
                    DB::raw('master.getNamaLengkapPegawai(pe.NIP) AS NAMADOKTER'),
                    DB::raw('master.getCariUmur(fkfr.tgl,ps.TANGGAL_LAHIR) AS UMURPASIEN')
                )
                ->where('fkfr.nomor',$KUNJUNGAN)
                ->where('fkfr.GROUP',$GROUP)
                ->whereNull('fkfr.deleted_at')
                ->orderBy('fkfr.id','DESC')
                ->first();

        if (empty($show)) {
            return response()->json('Error mengambil Data Formulir KFR', 401);
        }

        $getTgl = Carbon::parse($show->tgl);
        $tgl = $getTgl->isoFormat('DD');
        $bulan = $getTgl->isoFormat('MM');
        $tahun = $getTgl->isoFormat('YYYY');

        // ----------------------------------------------------------------------

        // GET CAP DOKTER
        if ($show->NIPDOKTER == '2101341') { // Lidiawati
            $cap = public_path().'/signatures/cap/lidiawati.png';
        } elseif ($show->NIPDOKTER == '2505550') { // Stephanie Indrawati Sugiarto
            $cap = public_path().'/signatures/cap/stephanie.png';
        } else {
            // $cap = public_path().'/signatures/cap/lidiawati.png';
            $cap = '';
        }

        // SAVE TO DB
        $verify = klaim_file::where('nomor',$KUNJUNGAN)
                            ->where('jenis',11)
                            ->where('sub_jenis',1) // FORM KFR
                            ->where('status',true)
                            ->whereNull('deleted_at')
                            ->orderBy('id','DESC')
                            ->first();

        $path = 'files/rehabmedik/formlayanankfr/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$KUNJUNGAN.'-1';

        if (!$verify) {
            $post = new klaim_file;
            $post->jenis = 11;
            $post->sub_jenis = 1;
            $post->nomor = $KUNJUNGAN;
            $post->title = $KUNJUNGAN.'-1.pdf';
            $post->filename = $path.'.pdf';
            $post->nama_tambahan = 'Formulir Layanan KFR';
            $post->status = true;
            $post->user = Auth::user()->ID;
            $post->save();
        } else {
            $verify->user = Auth::user()->ID;
            $verify->save();
        }

        $output = storage_path().'/app/public/'.$path;

        // Pastikan folder tujuan ada
        $outputDir = dirname($output);
        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true); // true = recursive
        }

        $data = [
            'TANGGAL' => Carbon::parse($show->tgl)->translatedFormat('d F Y'),
            'NAMAPASIEN' => $show->NAMAPASIEN,
            'NAMADOKTER' => $show->NAMADOKTER,
            'TGLLAHIRPASIEN' => Carbon::parse($show->TGLLAHIRPASIEN)->translatedFormat('d F Y'),
            'UMURPASIEN' => $show->UMURPASIEN,
            'NOHPPASIEN' => (!empty($show->NOHPPASIEN) ? $show->NOHPPASIEN : '-'),
            'ALAMATPASIEN' => $show->ALAMATPASIEN,
            'ANAMNESA' => $show->anamnesa,
            'PEMERIKSAANFISIK' => $show->pemeriksaan_fisik,
            'DIAGMEDIS' => $show->diagnosa_medis,
            'DIAGFUNGSI' => $show->diagnosa_fungsi,
            'PEMERIKSAANPENUNJANG' => $show->pemeriksaan_penunjang,
            'TATALAKSANA' => $show->tata_laksana_kfr,
            'ANJURAN' => $show->anjuran,
            'EVALUASI' => $show->evaluasi,
            'TARGET' => $show->target,
            'SPAKCEK' => ($show->spak_index === 1) ? "✓" : "",
            'SPAKUNCEK' => ($show->spak_index === 0) ? "✓" : "",
            'SPAK' => ($show->spak) ? $show->spak : "",
            'CAP' => "",
        ];

        $templateProcessor = new TemplateProcessor(public_path('/doc/input/rehabmedik/cetakFormKFR.docx'));

        $this->setImgWord($templateProcessor, 'PATH_TTE_DOKTER', storage_path()."/app/public/".$show->tte_dokter, 170); // 150 is Width
        $this->setImgWord($templateProcessor, 'PATH_TTE_PASIEN', storage_path()."/app/public/".$show->tte_pasien, 300);
        if ($cap) {
            $this->setImgWord($templateProcessor, 'CAP', $cap, 150);
        }

        foreach ($data as $key => $value) {
            $templateProcessor->setValue($key, $value);
        }

        $outputWord = $output.'.docx';
        $templateProcessor->saveAs($outputWord);
        [$log, $result] = $this->libreOffice($outputWord, dirname($outputWord));

        if (File::exists($outputWord)) {
            File::delete($outputWord);
        }

        return response()->file($output.'.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }

    function compileFormJp($KUNJUNGAN,$GROUP)
    {
        $show = DB::table('simrspku_klaim.form_kfr AS fkfr')
                ->leftJoin('master.pasien AS ps', function($join) {
                    $join->on('ps.NORM','=','fkfr.rm')
                        ->where('ps.status', true);
                })
                ->leftJoin('master.kontak_pasien AS kps','ps.NORM','=','kps.NORM')
                ->leftJoin('aplikasi.pengguna AS pe','pe.ID','=','fkfr.dokter')
                ->select(
                    'fkfr.*',
                    'ps.TANGGAL_LAHIR AS TGLLAHIRPASIEN',
                    'kps.NOMOR AS NOHPPASIEN',
                    'pe.NIP AS NIPDOKTER',
                    DB::raw('master.getNamaLengkap(fkfr.rm) AS NAMAPASIEN'),
                    DB::raw('master.getAlamatPasienCustom(fkfr.rm) AS ALAMATPASIEN'),
                    DB::raw('master.getNamaLengkapPegawai(pe.NIP) AS NAMADOKTER'),
                    DB::raw('master.getCariUmur(fkfr.tgl,ps.TANGGAL_LAHIR) AS UMURPASIEN')
                )
                ->where('fkfr.nomor',$KUNJUNGAN)
                ->where('fkfr.GROUP',$GROUP)
                ->whereNull('fkfr.deleted_at')
                ->orderBy('fkfr.id','DESC')
                ->first();

        if (empty($show)) {
            return response()->json('Error mengambil Data Formulir Jadwal Pelayanan', 401);
        }

        $getTgl = Carbon::parse($show->tgl);
        $tgl = $getTgl->isoFormat('DD');
        $bulan = $getTgl->isoFormat('MM');
        $tahun = $getTgl->isoFormat('YYYY');

        // ----------------------------------------------------------------------

        // GET CAP DOKTER
        if ($show->NIPDOKTER == '2101341') { // Lidiawati
            $cap = public_path().'/signatures/cap/lidiawati.png';
        } elseif ($show->NIPDOKTER == '2505550') { // Stephanie Indrawati Sugiarto
            $cap = public_path().'/signatures/cap/stephanie.png';
        } else {
            // $cap = public_path().'/signatures/cap/lidiawati.png';
            $cap = '';
        }

        // SAVE TO DB
        $verify = klaim_file::where('nomor',$KUNJUNGAN)
                            ->where('jenis',11)
                            ->where('sub_jenis',2) // FORM JP
                            ->where('status',true)
                            ->whereNull('deleted_at')
                            ->orderBy('id','DESC')
                            ->first();
        // ----------------------------------------------------------------------
        $verify_formJp = form_kfr_jp::where('nomor',$KUNJUNGAN)
                            ->where('group',$GROUP)
                            ->whereNull('deleted_at')
                            ->orderBy('id','DESC')
                            ->first();

        $path = 'files/rehabmedik/jadwalpelayanan/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$KUNJUNGAN.'-2';

        if ($verify_formJp) {
            if ($verify) {
                $post2 = new klaim_file;
                $post2->jenis = 11;
                $post2->sub_jenis = 2;
                $post2->nomor = $KUNJUNGAN;
                $post2->title = $KUNJUNGAN.'-2.pdf';
                $post2->filename = $path.'.pdf';
                $post2->nama_tambahan = 'Formulir Layanan KFR';
                $post2->status = true;
                $post2->user = Auth::user()->ID;
                $post2->save();
            } else {
                $verify->user = Auth::user()->ID;
                $verify->save();
            }
        }

        $output = storage_path().'/app/public/'.$path;

        // Pastikan folder tujuan ada
        $outputDir = dirname($output);
        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true); // true = recursive
        }

        // SAMPAI SINI
        $data = [
            'TANGGAL' => Carbon::parse($show->tgl)->translatedFormat('d F Y'),
            'NAMAPASIEN' => $show->NAMAPASIEN,
            'NAMADOKTER' => $show->NAMADOKTER,
            'TGLLAHIRPASIEN' => Carbon::parse($show->TGLLAHIRPASIEN)->translatedFormat('d F Y'),
            'UMURPASIEN' => $show->UMURPASIEN,
            'NOHPPASIEN' => (!empty($show->NOHPPASIEN) ? $show->NOHPPASIEN : '-'),
            'ALAMATPASIEN' => $show->ALAMATPASIEN,
            'ANAMNESA' => $show->anamnesa,
            'PEMERIKSAANFISIK' => $show->pemeriksaan_fisik,
            'DIAGMEDIS' => $show->diagnosa_medis,
            'DIAGFUNGSI' => $show->diagnosa_fungsi,
            'PEMERIKSAANPENUNJANG' => $show->pemeriksaan_penunjang,
            'TATALAKSANA' => $show->tata_laksana_kfr,
            'ANJURAN' => $show->anjuran,
            'EVALUASI' => $show->evaluasi,
            'TARGET' => $show->target,
            'SPAKCEK' => ($show->spak_index === 1) ? "✓" : "",
            'SPAKUNCEK' => ($show->spak_index === 0) ? "✓" : "",
            'SPAK' => ($show->spak) ? $show->spak : "",
            'CAP' => "",
        ];

        $templateProcessor = new TemplateProcessor(public_path('/doc/input/rehabmedik/cetakFormKFR.docx'));

        $this->setImgWord($templateProcessor, 'PATH_TTE_DOKTER', storage_path()."/app/public/".$show->tte_dokter, 170); // 150 is Width
        $this->setImgWord($templateProcessor, 'PATH_TTE_PASIEN', storage_path()."/app/public/".$show->tte_pasien, 300);
        if ($cap) {
            $this->setImgWord($templateProcessor, 'CAP', $cap, 150);
        }

        foreach ($data as $key => $value) {
            $templateProcessor->setValue($key, $value);
        }

        $outputWord = $output.'.docx';
        $templateProcessor->saveAs($outputWord);
        [$log, $result] = $this->libreOffice($outputWord, dirname($outputWord));

        if (File::exists($outputWord)) {
            File::delete($outputWord);
        }

        return response()->file($output.'.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }

    // ----------------------------------------- FORM JADWAL PELAYANAN -------------------------------------------
    function getFormJp($NORM,$KUNJUNGAN)
    {
        $form_kfr = DB::table('simrspku_klaim.form_kfr')
            ->where('nomor', $KUNJUNGAN)
            ->whereNull('deleted_at')
            ->first();

        if ($form_kfr) {
            $form_jp = DB::table('simrspku_klaim.form_kfr_jp as fkfrjp')
                ->leftJoin('aplikasi.pengguna as pe','pe.ID','=','fkfrjp.user')
                ->leftJoin('pendaftaran.kunjungan AS kj','kj.NOMOR','=','fkfrjp.nomor')
                ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','kj.NOPEN')
                ->leftJoin('pendaftaran.penjamin AS pj','pp.NOMOR','=','pj.NOPEN')
                ->select('fkfrjp.*','pe.NAMA as nama_user','kj.MASUK','kj.KELUAR','pj.NOMOR AS NOSEP')
                ->where('fkfrjp.group', $form_kfr->group)
                ->whereNull('fkfrjp.deleted_at')
                ->orderBy('fkfrjp.tgl','ASC')
                ->get();
        } else {
            $form_jp = collect();
        }

        $data = [
            'form_kfr' => $form_kfr,
            'form_jp' => $form_jp,
        ];

        return response()->json($data, 200);
    }

    function getFormJpByGroup($GROUP)
    {
        $data = DB::table('simrspku_klaim.form_kfr_jp AS jp')
                ->where('jp.group',$GROUP)
                ->whereNull('jp.deleted_at')
                ->orderBy('jp.id','ASC')
                ->first();

        return response()->json($data, 200);
    }

    function simpanJp(Request $request)
    {
        $request->validate([
            'group' => 'required',
            'nomor' => 'required',
            'tgl' => 'required',
            'program' => 'required',
            'tte_p' => 'required|string',
            'tte_t' => 'required|string',
        ]);

        $now = Carbon::now();
        $getForm = form_kfr::where('nomor',$request->nomor)
                            ->where('group',$request->group)
                            ->whereNull('deleted_at')
                            ->first();
        $getPengguna = Pengguna::where('ID',$getForm->dokter)->first();
        $getTtdDokter = DB::table('simrspku_klaim.tanda_tangan_pegawai')
            ->where('nip', $getPengguna->NIP)
            ->whereNull('deleted_at')
            ->first();

        if (!$getTtdDokter) {
            return Response::json(array(
                'message' => 'Tanda tangan User/Dokter tidak ditemukan. Silakan memperbarui Data TTE pada halaman Profil.',
                'code' => 500,
            ));
        }

        // TTE PASIEN
        $imagep = str_replace('data:image/png;base64,', '', $request->tte_p);
        $imagep = str_replace(' ', '+', $imagep);
        $filename_p = time().'.png';
        $path_tte_pasien = "signatures/pasien/{$getForm->rm}/{$filename_p}";
        Storage::disk('public')->put($path_tte_pasien, base64_decode($imagep));

        // TTE TERAPIS
        $imaget = str_replace('data:image/png;base64,', '', $request->tte_t);
        $imaget = str_replace(' ', '+', $imaget);
        $filename_t = time().'.png';
        $path_tte_terapis = "signatures/terapis/{$getForm->rm}/{$filename_t}";
        Storage::disk('public')->put($path_tte_terapis, base64_decode($imaget));

        // HITUNG FORM TERSIMPAN
        $countForm = form_kfr_jp::where('group',$request->group)
                            ->whereNull('deleted_at')
                            ->count();

        $show = DB::table('simrspku_klaim.form_kfr AS fkfr')
                ->leftJoin('master.pasien AS ps', function($join) {
                    $join->on('ps.NORM','=','fkfr.rm')
                        ->where('ps.status', true);
                })
                ->leftJoin('master.kontak_pasien AS kps','ps.NORM','=','kps.NORM')
                ->leftJoin('aplikasi.pengguna AS pe','pe.ID','=','fkfr.dokter')
                ->select(
                    'fkfr.*',
                    'ps.TANGGAL_LAHIR AS TGLLAHIRPASIEN',
                    'kps.NOMOR AS NOHPPASIEN',
                    'pe.NIP AS NIPDOKTER',
                    DB::raw('master.getNamaLengkap(fkfr.rm) AS NAMAPASIEN'),
                    DB::raw('master.getAlamatPasienCustom(fkfr.rm) AS ALAMATPASIEN'),
                    DB::raw('master.getNamaLengkapPegawai(pe.NIP) AS NAMADOKTER'),
                    DB::raw('master.getCariUmur(fkfr.tgl,ps.TANGGAL_LAHIR) AS UMURPASIEN')
                )
                ->where('fkfr.nomor',$request->nomor)
                ->where('fkfr.GROUP',$request->group)
                ->whereNull('fkfr.deleted_at')
                ->orderBy('fkfr.id','DESC')
                ->first();

        $saveId = DB::table('simrspku_klaim.form_kfr_jp')->insertGetId([
            'group' => $request->group,
            'nomor' => $request->nomor,
            'tgl' => $request->tgl,
            'dokter' => $getForm->dokter,
            'terapis' => $request->id_user,
            'program' => $request->program,
            'tte_pasien' => $path_tte_pasien,
            'tte_dokter' => $getTtdDokter->signature_path,
            'tte_terapis' => $path_tte_terapis,
            'user' => $request->id_user,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // SAVE TO DB klaim_file
        // $verify = klaim_file::where('nomor',$request->nomor)
        //                     ->where('jenis',11)
        //                     ->where('sub_jenis',2) // FORM JP
        //                     ->whereNotNull('kode')
        //                     ->where('status',true)
        //                     ->whereNull('deleted_at')
        //                     ->orderBy('id','DESC')
        //                     ->first();
        // ----------------------------------------------------------------------
        // GET FORM SAVED
        $verify = form_kfr_jp::where('id',$saveId)->first();
        $getJp = form_kfr_jp::where('group', $request->group)
                            ->whereNull('deleted_at')
                            ->orderBy('tgl','ASC')
                            // ->orderBy('id','ASC')
                            ->get();

        if ($verify) {
            $getTgl = Carbon::parse($verify->tgl);
            $tgl = $getTgl->isoFormat('DD');
            $bulan = $getTgl->isoFormat('MM');
            $tahun = $getTgl->isoFormat('YYYY');

            $path = 'files/rehabmedik/jadwalpelayanan/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$request->nomor.'-2-'.($countForm+1);

            $post2 = new klaim_file;
            $post2->jenis = 11;
            $post2->sub_jenis = 2;
            $post2->kode = ($countForm+1);
            $post2->ref = $request->group;
            $post2->nomor = $request->nomor;
            $post2->title = $request->nomor.'-2-'.($countForm+1).'.pdf';
            $post2->filename = $path.'.pdf';
            $post2->nama_tambahan = 'Jadwal Program Pelayanan '.($countForm+1);
            $post2->status = true;
            $post2->user = Auth::user()->ID;
            $post2->save();

        }

        $output = storage_path().'/app/public/'.$path;

        // Pastikan folder tujuan ada
        $outputDir = dirname($output);
        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true); // true = recursive
        }

        // GET CAP DOKTER
        if ($show->NIPDOKTER == '2101341') { // Lidiawati
            $cap = public_path().'/signatures/cap/lidiawati.png';
        } elseif ($show->NIPDOKTER == '2505550') { // Stephanie Indrawati Sugiarto
            $cap = public_path().'/signatures/cap/stephanie.png';
        } else {
            // $cap = public_path().'/signatures/cap/lidiawati.png';
            $cap = '';
        }

        $data = [
            'TANGGAL' => Carbon::parse($show->tgl)->translatedFormat('d F Y'), // tgl di ttd bawah
            'NORM' => $show->rm,
            'NAMAPASIEN' => $show->NAMAPASIEN,
            'NAMADOKTER' => $show->NAMADOKTER,
            'TGLLAHIRPASIEN' => Carbon::parse($show->TGLLAHIRPASIEN)->translatedFormat('d F Y'),
            'UMURPASIEN' => $show->UMURPASIEN,
            'ALAMATPASIEN' => $show->ALAMATPASIEN,
            'CAP' => "",
        ];

        $templateProcessor = new TemplateProcessor(public_path('/doc/input/rehabmedik/cetakFormJP.docx'));

        // POST TTE
        foreach ($getJp as $i => $value) {
            $index = $i + 1;

            // Kolom lainnya
            $templateProcessor->setValue("PROGRAMPELAYANAN_{$index}", $value->program);
            $templateProcessor->setValue("TANGGALPELAYANAN_{$index}", $value->tgl);

            // Dokter
            if (!empty($value->tte_dokter) && file_exists(storage_path("app/public/{$value->tte_dokter}"))) {
                $this->setImgWord($templateProcessor, "PATH_TTE_DOKTER_{$index}", storage_path("app/public/{$value->tte_dokter}"), 170);
            } else {
                $templateProcessor->setValue("PATH_TTE_DOKTER_{$index}", '');
            }

            // Pasien
            if (!empty($value->tte_pasien) && file_exists(storage_path("app/public/{$value->tte_pasien}"))) {
                $this->setImgWord($templateProcessor, "PATH_TTE_PASIEN_{$index}", storage_path("app/public/{$value->tte_pasien}"), 300);
            } else {
                $templateProcessor->setValue("PATH_TTE_PASIEN_{$index}", '');
            }

            // Terapis
            if (!empty($value->tte_terapis) && file_exists(storage_path("app/public/{$value->tte_terapis}"))) {
                $this->setImgWord($templateProcessor, "PATH_TTE_TERAPIS_{$index}", storage_path("app/public/{$value->tte_terapis}"), 300);
            } else {
                $templateProcessor->setValue("PATH_TTE_TERAPIS_{$index}", '');
            }
        }

        // POST CAP
        if ($cap) {
            $this->setImgWord($templateProcessor, 'CAP', $cap, 150);
        }

        // POST DATA
        foreach ($data as $key => $value) {
            $templateProcessor->setValue($key, $value);
        }

        $outputWord = $output.'.docx';
        $templateProcessor->saveAs($outputWord);
        [$log, $result] = $this->libreOffice($outputWord, dirname($outputWord));

        if (File::exists($outputWord)) {
            File::delete($outputWord);
        }

        return Response::json(array(
            'message' => 'Formulir Jadwal Pelayanan telah berhasil diterbitkan',
            'code' => 200,
        ));
    }

    function hapusFormJp($id)
    {
        $now = Carbon::now()->isoFormat('YYYY-MM-DD HH:mm:ss');

        $show = form_kfr_jp::where('id',$id)->first();
        $show->user = Auth::user()->ID;
        $show->save();
        $show->delete();

        return response()->json($now, 200);
    }
}
