<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\simrspku_klaim\klaim_file;
use Auth;

class ApiUploadController extends Controller
{
    public function index($nomor)
    {
        $files = DB::table('simrspku_klaim.klaim_file')
            ->where('nomor', $nomor)
            ->where('jenis', 10)
            ->whereNull('deleted_at')
            ->orderBy('sub_jenis')
            ->get();

        $list = ['KUNJUNGAN' => $nomor];
        return view('emr.file-upload', compact('files', 'list'));
    }

    public function store(Request $request, $nomor)
    {
        $request->validate([
            'file' => 'required|file|max:2048',
            'nama_tambahan' => 'required|string|max:255',
        ]);

        // cari sub_jenis terakhir untuk nomor dan jenis 10
        $lastSubJenis = DB::table('simrspku_klaim.klaim_file')
            ->where('nomor', $nomor)
            ->where('jenis', 10)
            ->max('sub_jenis');

        $subJenis = $lastSubJenis ? $lastSubJenis + 1 : 1;

        // format urutan dengan 2 digit: 01, 02, 03, dst
        $urutanFormatted = str_pad($subJenis, 2, '0', STR_PAD_LEFT);

        // ambil ekstensi asli file
        $ext = $request->file('file')->getClientOriginalExtension();

        // buat nama file custom: NOMOR-URUTAN.EXT
        $filename = $nomor . '-' . $urutanFormatted . '.' . $ext;

        // simpan file dengan nama custom ke folder public/files/upload
        $path = $request->file('file')->storeAs('files/upload', $filename, 'public');

        // $path = $request->file('file')->store('files/upload', 'public');
        // // print_r($path.basename($path));
        // // die();
        // // hitung sub_jenis terakhir untuk nomor dan jenis 10
        // $lastSubJenis = DB::table('simrspku_klaim.klaim_file')
        //     ->where('nomor', $nomor)
        //     ->where('jenis', 10)
        //     ->max('sub_jenis');

        // $subJenis = $lastSubJenis ? $lastSubJenis + 1 : 1;

        DB::table('simrspku_klaim.klaim_file')->insert([
            'jenis' => 10,
            'sub_jenis' => $subJenis,
            'nomor' => $nomor,
            'title' => $filename,
            'filename' => $path,
            'nama_tambahan' => $request->input('nama_tambahan'),
            'user' => auth()->id() ?? null,
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function listFiles($nomor)
    {
        $files = DB::table('simrspku_klaim.klaim_file')
            ->where('nomor', $nomor)
            ->where('jenis', 10)
            ->whereNull('deleted_at')
            ->get();

        return response()->json($files);
    }

    public function destroy($nomor, $id)
    {
        $file = DB::table('simrspku_klaim.klaim_file')->where('id', $id)->first();

        if ($file) {
            Storage::disk('public')->delete('files/upload/' . $file->filename);
            DB::table('simrspku_klaim.klaim_file')->where('id', $id)->update(['deleted_at' => now()]);
        }

        return response()->json(['success' => true]);
    }
}
