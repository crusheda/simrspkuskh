<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\simrspku_klaim\klaim_verifikasi;
use App\Models\simrspku_klaim\klaim_file;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\TemplateProcessor;
use PHPJasper\PHPJasper;
use Carbon\Carbon;
use Auth, Storage;

class ApiRehabMedikController extends Controller
{
    public function libreOffice($input, $output) {
        // LINK DOWNLOAD LIBRE OFFICE = https://www.libreoffice.org/download/download
        $soffice = '"C:\Program Files\LibreOffice\program\soffice.exe"';
        $cmd = $soffice . ' --headless --convert-to pdf ' . escapeshellarg($input) . ' --outdir ' . escapeshellarg($output);
        exec($cmd, $log, $result);
        return [$log, $result];
    }

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
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return Response::json(array(
            'message' => 'Formulir Rawat Jalan Layanan KFR telah berhasil diterbitkan',
            'code' => 200,
        ));
    }

    function compileFormKfr($GROUP)
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
                ->where('fkfr.GROUP',$GROUP)
                ->whereNull('fkfr.deleted_at')
                ->first();

        if (empty($show)) {
            return response()->json('Error mengambil Data Formulir KFR', 401);
        }

        $getTgl = Carbon::parse($show->tgl);
        $tgl = $getTgl->isoFormat('DD');
        $bulan = $getTgl->isoFormat('MM');
        $tahun = $getTgl->isoFormat('YYYY');
        // ----------------------------------------------------------------------
        $validasi = klaim_file::where('nomor',$show->nomor)
                                ->where('jenis',11)
                                ->where('status',1)
                                ->whereNull('deleted_at')
                                ->count();

        $path = 'files/rehabmedik/formlayanankfr/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$show->nomor.'-'.($validasi+1);
        $output = storage_path().'/app/public/'.$path;

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
        $post = new klaim_file;
        $post->jenis = 11;
        $post->sub_jenis = $validasi+1;
        $post->nomor = $show->nomor;
        $post->title = $show->nomor.'-'.($validasi+1).'.pdf';
        $post->filename = $path;
        $post->nama_tambahan = 'Formulir Layanan KFR';
        $post->status = true;
        $post->user = Auth::user()->ID;
        $post->save();

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
}
