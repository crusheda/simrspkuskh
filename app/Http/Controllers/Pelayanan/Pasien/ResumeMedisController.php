<?php

namespace App\Http\Controllers\Pelayanan\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\pendaftaran\kunjungan;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Storage, Carbon\Carbon;

class ResumeMedisController extends Controller
{
    function indexResume($KUNJUNGAN)
    {
        $resume = DB::table('pendaftaran.kunjungan AS pk')
                ->select(
                    'pk.*',
                    'pp.NORM',
                    'ps.TANGGAL_LAHIR',
                    'ru.DESKRIPSI',
                    'rjk.DESKRIPSI AS JK',
                    DB::raw('master.getNamaLengkap(ps.NORM) AS NAMAPASIEN'),
                    DB::raw('master.getNamaLengkapPegawai(md.NIP) AS NAMADOKTER'))
                ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                ->leftJoin('master.pasien AS ps','ps.NORM','=','pp.NORM')
                ->leftJoin('master.ruangan AS ru','ru.ID','=','pk.RUANGAN')
                ->leftJoin('master.dokter AS md','md.ID','=','pk.DPJP')
                ->leftJoin('master.referensi AS rjk', function($join) {
                                $join->on('rjk.ID','=','ps.JENIS_KELAMIN')
                                    ->where('rjk.JENIS',2);
                            })
                ->where(function ($query) {
                    $query->where('pk.RUANGAN', 'LIKE', '1020101%')
                            ->orWhere('pk.RUANGAN', 'LIKE', '1020201%');
                })

                ->where('ru.STATUS', 1)
                ->where('pk.NOMOR',$KUNJUNGAN)
                ->where(function ($query) {
                    $query->where('pk.STATUS', '=', '1')
                            ->orWhere('pk.STATUS', '=', '2');
                })
                ->first();

        // $awal = DB::table('pendaftaran.kunjungan AS pku')
        //         ->select(
        //             'pku.*',
        //             'ku.DESKRIPSI'
        //         )
        //         ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pku.NOPEN')
        //         ->leftJoin('medicalrecord.keluhan_utama AS ku','ku.KUNJUNGAN','=','pku.NOMOR')
        //         ->where('pku.NOMOR',$KUNJUNGAN)
        //         ->where('pku.REF', null)
        //         ->first();

        $data = [
            'resume' => $resume,
            // 'awal' => $awal,
            'KUNJUNGAN' => $KUNJUNGAN,
        ];
        // print_r($awal);
        // die();
        // return view('layouts.index2');
        return view('pages.pelayanan.pasien.resume.index')->with('list',$data);
    }

    function printResume($KUNJUNGAN)
    {
        $resume = DB::table('pendaftaran.kunjungan AS pk')
                ->select(
                    'pk.*',
                    'pp.NORM',
                    'ps.TANGGAL_LAHIR',
                    'ru.DESKRIPSI',
                    'rjk.DESKRIPSI AS JK',
                    DB::raw('master.getNamaLengkap(ps.NORM) AS NAMAPASIEN'),
                    DB::raw('master.getNamaLengkapPegawai(md.NIP) AS NAMADOKTER'))
                ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                ->leftJoin('master.pasien AS ps','ps.NORM','=','pp.NORM')
                ->leftJoin('master.ruangan AS ru','ru.ID','=','pk.RUANGAN')
                ->leftJoin('master.dokter AS md','md.ID','=','pk.DPJP')
                ->leftJoin('master.referensi AS rjk', function($join) {
                                $join->on('rjk.ID','=','ps.JENIS_KELAMIN')
                                    ->where('rjk.JENIS',2);
                            })
                ->where(function ($query) {
                    $query->where('pk.RUANGAN', 'LIKE', '1020101%')
                            ->orWhere('pk.RUANGAN', 'LIKE', '1020201%');
                })

                ->where('ru.STATUS', 1)
                ->where('pk.NOMOR',$KUNJUNGAN)
                ->where(function ($query) {
                    $query->where('pk.STATUS', '=', '1')
                            ->orWhere('pk.STATUS', '=', '2');
                })
                ->first();

        $awal = DB::table('pendaftaran.kunjungan AS pku')
                ->select(
                    'pku.*',
                    'ku.DESKRIPSI'
                )
                ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pku.NOPEN')
                ->leftJoin('medicalrecord.keluhan_utama AS ku','ku.KUNJUNGAN','=','pku.NOMOR')
                ->where('pku.NOMOR',$KUNJUNGAN)
                ->where('pku.REF', null)
                ->first();

        // $data = [
        //     'resume' => $resume,
        //     'awal' => $awal,
        //     'KUNJUNGAN' => $KUNJUNGAN,
        // ];

        // return view('pages.pelayanan.pasien.resume.print')->with('list',$data);

        $data = [
            'resume' => $resume,
            'awal' => $awal,
            'KUNJUNGAN' => $KUNJUNGAN,
            'title' => 'Laporan A4',
            'body' => 'Ini adalah isi HTML yang akan dijadikan PDF.'
        ];

        $html = view('pages.pelayanan.pasien.resume.print', $data)->render();

        $pdf = PDF::loadHTML($html)->setPaper('a4', 'portrait');
        // $pdf = Pdf::loadView('pages.pelayanan.pasien.resume.print', $data)->setPaper('a4', 'portrait');

        // return stream agar bisa ditampilkan langsung
        return $pdf->stream('laporan.pdf');
    }

    public function storeTtd(Request $request)
    {
        $existing = DB::table('simrspku_klaim.tanda_tangan')
            ->where('kunjungan', $request->nama)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Tanda tangan untuk kunjungan ini sudah ada.',
            ], 409); // 409 = Conflict
        }
        $request->validate([
            // 'nama' => 'required|string|max:255',
            // 'signature' => 'required|string',
        ]);

        $image = str_replace('data:image/png;base64,', '', $request->signature);
        $image = str_replace(' ', '+', $image);
        $filename = 'ttd_' . time() . '.png';

        Storage::disk('public')->put("/signatures/{$filename}", base64_decode($image));

        $pasien = DB::table('simrspku_klaim.tanda_tangan')->insert([
            'kunjungan' => $request->nama,
            'signature_path' => "signatures/{$filename}",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            // 'id' => $pasien->kunjungan
        ]);
    }
}
