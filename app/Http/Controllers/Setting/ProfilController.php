<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Auth, Storage;

class ProfilController extends Controller
{
    // SIRMED v.1
    function index()
    {
        $show = DB::table('aplikasi.pengguna AS ap')
                    ->leftJoin('master.pegawai AS pg','pg.NIP','=','ap.NIP')
                    ->leftJoin('pegawai.kontak_pegawai AS kp','kp.NIP','=','pg.NIP')
                    ->leftJoin('master.referensi AS ref', function($join) {
                        $join->on('ref.ID', '=', 'kp.JENIS')
                                ->where('ref.JENIS', '=', 8);
                    })
                    ->select(
                        'pg.*',
                        'kp.NOMOR AS NOHP',
                        'ref.DESKRIPSI AS JENISNOHP',
                        DB::raw('master.getNamaLengkapPegawai(pg.NIP) AS NAMALENGKAP'),
                        'pg.nama AS NAMA'
                    )
                    ->where('ap.ID', Auth::user()->ID)
                    ->where('pg.STATUS', 1)
                    // ->where('kp.STATUS', 1)
                    ->first();
        // print_r($show);
        // die();
        $data = [
            'show' => $show,
        ];

        return view('pages.setting.profil')->with('list', $data);
    }

    // SIRMED v.2
    function indexV2()
    {
        $show = DB::table('aplikasi.pengguna AS ap')
                    ->leftJoin('master.pegawai AS pg','pg.NIP','=','ap.NIP')
                    ->leftJoin('pegawai.kontak_pegawai AS kp', function($join) {
                        $join->on('kp.NIP','=','pg.NIP')
                                ->where('kp.STATUS', '=', 1);
                    })
                    ->leftJoin('master.referensi AS ref', function($join) {
                        $join->on('ref.ID', '=', 'kp.JENIS')
                                ->where('ref.JENIS', '=', 8);
                    })
                    ->select(
                        'pg.*',
                        'kp.NOMOR AS NOHP',
                        'ref.DESKRIPSI AS JENISNOHP',
                        DB::raw('master.getNamaLengkapPegawai(pg.NIP) AS NAMALENGKAP'),
                        'pg.nama AS NAMA'
                    )
                    ->where('ap.ID', Auth::user()->ID)
                    ->where('pg.STATUS', 1)
                    ->first();

        $data = [
            'show' => $show,
        ];

        return view('pages.v2.setting.profil')->with('list', $data);
    }

    //TTD RESUME
    // public function showTtdPeg($NIP)
    // {
    //     $show = DB::table('aplikasi.pengguna AS ap')
    //         ->leftJoin('master.pegawai AS pg','pg.NIP','=','ap.NIP')
    //         ->leftJoin('pegawai.kontak_pegawai AS kp','kp.NIP','=','pg.NIP')
    //         ->leftJoin('master.referensi AS ref', function($join) {
    //             $join->on('ref.ID', '=', 'kp.JENIS')
    //                 ->where('ref.JENIS', '=', 8);
    //         })
    //         ->select(
    //             'pg.*',
    //             'kp.NOMOR AS NOHP',
    //             'ref.DESKRIPSI AS JENISNOHP',
    //             DB::raw('master.getNamaLengkapPegawai(pg.NIP) AS NAMALENGKAP')
    //         )
    //         ->where('ap.ID', Auth::user()->ID)
    //         ->where('pg.STATUS', 1)
    //         ->where('kp.STATUS', 1)
    //         ->where('ap.NIP',$NIP)
    //         ->first();

    //     $existing = DB::table('simrspku_klaim.tanda_tangan_pegawai')
    //         ->where('nip', $NIP)
    //         ->whereNull('deleted_at')
    //         ->where('status',true)
    //         ->first();

    //     $signature_url = $existing ? asset('storage/' . $existing->signature_path) : null;

    //     return response()->json([
    //         'show' => $show,
    //         'dbttd' => [
    //             'signature_path' => $existing ? $existing->signature_path : null,
    //             'signature_url' => $signature_url,
    //             'signature_date' => $existing ? $existing->updated_at : null,
    //         ],
    //     ]);
    // }

    // public function storeTtdPeg(Request $request)
    // {
    //     $request->validate([
    //         'nip' => 'required|string',
    //         'signature' => 'required|string',
    //     ]);

    //     $peg = DB::table('aplikasi.pengguna AS ap')
    //             ->leftJoin('master.pegawai AS pg','pg.NIP','=','ap.NIP')
    //             ->leftJoin('pegawai.kontak_pegawai AS kp','kp.NIP','=','pg.NIP')
    //             ->leftJoin('master.referensi AS ref', function($join) {
    //                 $join->on('ref.ID', '=', 'kp.JENIS')
    //                     ->where('ref.JENIS', '=', 8);
    //             })
    //             ->select(
    //                 'pg.*',
    //                 'kp.NOMOR AS NOHP',
    //                 'ref.DESKRIPSI AS JENISNOHP',
    //                 DB::raw('master.getNamaLengkapPegawai(pg.NIP) AS NAMALENGKAP'),
    //                 'pg.nama AS NAMA'
    //             )
    //             ->where('ap.ID', Auth::user()->ID)
    //             ->where('pg.STATUS', 1)
    //             // ->where('kp.STATUS', 1)
    //             ->where('ap.NIP',$request->nip)
    //             ->first();
    //     // print_r($peg);
    //     // die();
    //     $image = str_replace('data:image/png;base64,', '', $request->signature);
    //     $image = str_replace(' ', '+', $image);
    //     $filename = 'ttd_' . time() . '.png';

    //     Storage::disk('public')->put("/signatures/{$filename}", base64_decode($image));

    //     DB::table('simrspku_klaim.tanda_tangan_pegawai')
    //         ->where('nip', $request->nip)
    //         ->whereNull('deleted_at')
    //         ->where('status',true)
    //         ->update([
    //             'deleted_at' => now()
    //         ]);

    //     DB::table('simrspku_klaim.tanda_tangan_pegawai')->insert([
    //         'nip' => $request->nip,
    //         'nama_pegawai' => $peg->NAMALENGKAP ? $peg->NAMALENGKAP : $peg->NAMA,
    //         'signature_path' => "signatures/{$filename}",
    //         'created_at' => Carbon::now(),
    //         'updated_at' => Carbon::now(),
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'signature_url' => asset("storage/signatures/{$filename}"),
    //     ]);
    // }

    public function showTtdPeg($NIP)
    {
        $show = DB::table('aplikasi.pengguna AS ap')
            ->leftJoin('master.pegawai AS pg','pg.NIP','=','ap.NIP')
            ->select(
                'pg.*',
                DB::raw('master.getNamaLengkapPegawai(pg.NIP) AS NAMALENGKAP')
            )
            ->where('ap.ID', Auth::user()->ID)
            ->where('pg.STATUS', 1)
            ->where('ap.NIP',$NIP)
            ->first();

        $ttds = DB::table('simrspku_klaim.tanda_tangan_pegawai')
            ->where('nip', $NIP)
            ->whereNull('deleted_at')
            ->where('status', 1)
            ->orderBy('queue')
            ->get();

        return response()->json([
            'show' => $show,
            'ttds' => $ttds->map(function ($row) {
                return [
                    'queue' => $row->queue,
                    'signature_path' => $row->signature_path,
                    'signature_url' => asset('storage/'.$row->signature_path),
                    'updated_at' => $row->updated_at
                ];
            })
        ]);
    }

    public function storeTtdPeg(Request $request)
    {
        $request->validate([
            'nip' => 'required',
            'signatures' => 'required|array'
        ]);

        $peg = DB::table('aplikasi.pengguna AS ap')
                ->leftJoin('master.pegawai AS pg','pg.NIP','=','ap.NIP')
                ->leftJoin('pegawai.kontak_pegawai AS kp','kp.NIP','=','pg.NIP')
                ->leftJoin('master.referensi AS ref', function($join) {
                    $join->on('ref.ID', '=', 'kp.JENIS')
                        ->where('ref.JENIS', '=', 8);
                })
                ->select(
                    'pg.*',
                    'kp.NOMOR AS NOHP',
                    'ref.DESKRIPSI AS JENISNOHP',
                    DB::raw('master.getNamaLengkapPegawai(pg.NIP) AS NAMALENGKAP'),
                    'pg.nama AS NAMA'
                )
                ->where('ap.ID', Auth::user()->ID)
                ->where('pg.STATUS', 1)
                // ->where('kp.STATUS', 1)
                ->where('ap.NIP',$request->nip)
                ->first();

        DB::beginTransaction();

        try {
            foreach ($request->signatures as $ttd) {

                $image = preg_replace('#^data:image/\w+;base64,#i', '', $ttd['image']);
                $image = base64_decode($image);

                $filename = 'signatures/pegawai/ttd_' . time() . '_' . Str::random(5) . '.png';
                Storage::disk('public')->put($filename, $image);

                DB::table('simrspku_klaim.tanda_tangan_pegawai')
                    ->updateOrInsert(
                        [
                            'nip'            => $request->nip,
                            'nama_pegawai'   => $peg->NAMALENGKAP ? $peg->NAMALENGKAP : $peg->NAMA,
                            'queue'          => $ttd['queue']
                        ],
                        [
                            'signature_path' => $filename,
                            'status'         => 1,
                            'updated_at'     => now(),
                            'created_at'     => now()
                        ]
                    );
            }

            DB::commit();

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Cara Pakai Random Saat Generate Laporan
    // DB::table('tanda_tangan_pegawai')
    // ->where('nip', $nip)
    // ->where('status', 1)
    // ->inRandomOrder()
    // ->first();

}
