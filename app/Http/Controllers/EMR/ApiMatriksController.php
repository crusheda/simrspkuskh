<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\simrspku_klaim\klaim_file;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PHPJasper\PHPJasper;
use Carbon\Carbon;
use Auth;

class ApiMatriksController extends Controller
{
    public function showMatriks($NOMOR)
    {
        // Cek apakah data sudah ada di database
        $data = DB::table('simrspku_klaim.matriks')
            ->where('nomor', $NOMOR)
            ->first();

        // return view('emr.matriks', [
        //     'nomor' => $nomor,
        //     'data' => $data
        // ]);
        return response()->json($data, 200);
    }

    public function store(Request $request)
    {
        $now = Carbon::now();

        // Validasi input radio (boleh null, tapi jika ada harus 1, 2, atau 3)
        $validated = $request->validate([
            'nomor' => 'required|string',
            'no1a' => 'nullable|in:0,1,2,3',
            'no1b' => 'nullable|in:0,1,2,3',
            'no1c' => 'nullable|in:0,1,2,3',
            'no1d' => 'nullable|in:0,1,2,3',
            'no1e' => 'nullable|in:0,1,2,3',
            'no2a' => 'nullable|in:0,1,2,3',
            'no2b' => 'nullable|in:0,1,2,3',
            'no3'  => 'nullable|in:0,1,2,3',
            'no4'  => 'nullable|in:0,1,2,3',
            'no5'  => 'nullable|in:0,1,2,3',
            'no6'  => 'nullable|in:0,1,2,3',
            'no7'  => 'nullable|in:0,1,2,3',
            'no8'  => 'nullable|in:0,1,2,3',
        ]);

        // Bersihkan nilai default kosong menjadi 0 jika tidak ada
        $fields = [
            'no1a', 'no1b', 'no1c', 'no1d', 'no1e',
            'no2a', 'no2b', 'no3', 'no4', 'no5',
            'no6', 'no7', 'no8'
        ];

        $dataInsert = ['nomor' => $request->nomor];

        foreach ($fields as $field) {
            $dataInsert[$field] = $request->input($field, '0');
        }

        $dataInsert['updated_at'] = $now;

        // Cek apakah data sudah ada (update) atau baru (insert)
        $existing = DB::table('simrspku_klaim.matriks')
            ->where('nomor', $request->nomor)
            ->first();

        if ($existing) {
            DB::table('simrspku_klaim.matriks')
                ->where('nomor', $request->nomor)
                ->update($dataInsert);
        } else {
            $dataInsert['created_at'] = $now;
            DB::table('simrspku_klaim.matriks')->insert($dataInsert);
        }

        return response()->json([
            'message' => 'Data berhasil disimpan.'
        ], 200);
    }
    function compileMatriks($NOMOR)
    {
        $kunjungan = $NOMOR;
        $getMatriks = DB::table('simrspku_klaim.matriks AS mat')
                ->where('mat.nomor',$NOMOR)
                ->first();
        // $show = DB::select('CALL simrspku_klaim.CetakLapIndividual5(?,?)',[$getSEP->NOPEN,3]);
        if (empty($getMatriks)) {
            return response()->json($data, 400);
        }
        $CETAK_HEADER = "1";
        // ----------------------------------------------------------------------
        $getTgl = Carbon::parse($getMatriks->created_at);
        $tgl = $getTgl->isoFormat('DD');
        $bulan = $getTgl->isoFormat('MM');
        $tahun = $getTgl->isoFormat('YYYY');
        // ----------------------------------------------------------------------
        $input = public_path().'/doc/input/matriks/CetakMatriks.jrxml';
        $path = 'files/matriks/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$NOMOR;
        $output = storage_path().'/app/public/'.$path;

        // Pastikan folder tujuan ada
        $outputDir = dirname($output);
        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true); // true = recursive
        }

        // SAVE TO DB
        $validasi = klaim_file::where('nomor',$kunjungan)
                                ->where('jenis',10)
                                ->where('status',1)
                                ->whereNull('deleted_at')
                                ->count();
        $verify = klaim_file::where('nomor',$kunjungan)->where('jenis',10)->where('nama_tambahan','matriks')->where('status',true)->first();
        if (!$verify) {
            $post = new klaim_file;
            $post->jenis = 10;
            $post->sub_jenis = $validasi+1;
            $post->nomor = $kunjungan;
            $post->title = $kunjungan.'-'.($validasi+1).'.pdf';
            $post->filename = $path.'.pdf';
            $post->nama_tambahan = 'matriks';
            $post->status = true;
            $post->user = Auth::user()->ID;
            $post->save();
        }else {
            $verify->filename = $path . '.pdf';
            $verify->save(); //
        }

        $options = [
            'format' => ['pdf'],
            'params' => [
                'nomor' => $getMatriks->nomor,
                'no1a' => $getMatriks->no1a,
                'no1b' => $getMatriks->no1b,
                'no1c' => $getMatriks->no1c,
                'no1d' => $getMatriks->no1d,
                'no1e' => $getMatriks->no1e,
                'no2a' => $getMatriks->no2a,
                'no2b' => $getMatriks->no2b,
                'no3' => $getMatriks->no3,
                'no4' => $getMatriks->no4,
                'no5' => $getMatriks->no5,
                'no6' => $getMatriks->no6,
                'no7' => $getMatriks->no7,
                'no8' => $getMatriks->no8,
                'IMAGES_PATH' => public_path()."/doc/input/matriks/",
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
