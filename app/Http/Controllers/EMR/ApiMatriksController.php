<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
}
