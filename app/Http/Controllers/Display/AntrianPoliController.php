<?php

namespace App\Http\Controllers\Display;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\pendaftaran\panggilan_antrian_ruangan;
use Carbon\Carbon;

class AntrianPoliController extends Controller
{
    function index()
    {
        $poli = DB::table('master.ruangan AS ru')
            ->select('ru.ID AS IDRUANGAN','ru.DESKRIPSI AS NAMARUANGAN')
            ->where(function ($query) {
                $query->where('ru.ID', 'LIKE', '1020101%')
                    ->orWhere('ru.ID', 'LIKE', '1020702%');
            })
            ->where('ru.JENIS',5)
            ->where('ru.STATUS',1)
            ->get();

        $dokter = DB::table('penjamin_rs.dpjp as dpjp')
            ->leftJoin('master.dokter as md','md.ID','=','dpjp.DPJP_RS')
            ->leftJoin('aplikasi.pengguna as pe','pe.NIP','=','md.NIP')
            ->select('dpjp.DPJP_RS as ID_DPJP_SIMRS','dpjp.DPJP_PENJAMIN as ID_DPJP_BPJS',DB::raw('master.getNamaLengkapPegawai(md.NIP) AS NAMADOKTER'))
            ->where('dpjp.STATUS',1)
            ->get();

        $mapDokterPoli = DB::table('master.dokter_ruangan')->select('DOKTER','RUANGAN')->where('STATUS',1)->get();

        $data = [
            'poli' => $poli,
            'dokter' => $dokter,
            'mapDokterPoli' => $mapDokterPoli,
        ];

        // print_r($data);
        // die();
        return view('pages.display.antrian.poli.index')->with('list', $data);
    }

    function getDisplayAntrianPoli(Request $request)
    {
        $debug = '';
        // $tgl,$ruangan,$dr,$md
        $tgl = Carbon::now()->isoFormat('YYYY-MM-DD');
        // $tgl = '2025-11-06';
        // $ruangan = '102010105'; // POLI BEDAH

        // Ambil yang sedang dipanggil dulu
        $dipanggil1 = DB::table('pendaftaran.panggilan_antrian_ruangan AS par')
            ->select(
                'par.ID',
                'pp.NORM',
                DB::raw('master.getNamaLengkap(pp.NORM) AS NAMAPASIEN'),
                DB::raw('master.getNamaDokterSingkat(dr.NIP) AS NAMADOKTER'),
                'ar.POS','ar.NOMOR AS NOMORANTREAN','ar.STATUS AS STATUSANTREAN',
                'par.STATUS AS STATUSPANGGILAN',
                'ru.DESKRIPSI AS NAMARUANGAN',
                'ar.ID AS ANTRIAN_ID'
            )
            ->join('pendaftaran.antrian_ruangan AS ar', function($join) {
                $join->on('ar.ID','=','par.ANTRIAN_RUANGAN')
                    ->where('ar.STATUS', '!=', 0); // TIDAK BATAL KUNJUNGAN
            })
            ->leftJoin('pendaftaran.pendaftaran AS pp', function($join) {
                $join->on('pp.NOMOR','=','ar.REF')
                    ->where('pp.STATUS', '!=', 0);
            })
            ->join('pendaftaran.tujuan_pasien AS tp', function($join) use ($request) {
                $join->on('tp.NOPEN','=','pp.NOMOR')
                    ->where('tp.STATUS', '=', 2) // SUDAH DITERIMA DI KUNJUNGAN PASIEN
                    ->where('tp.RUANGAN', '=', $request->poli1);
            })
            ->leftJoin('master.ruangan AS ru','ar.RUANGAN','=','ru.ID')
            ->leftJoin('penjamin_rs.dpjp as dpjp', function($join) {
                $join->on('dpjp.DPJP_PENJAMIN','=','ar.DOKTER')
                    ->where('dpjp.STATUS', '!=', 0);
            })
            ->leftJoin('master.dokter as dr','dr.ID','=','dpjp.DPJP_RS')
            ->where('ar.RUANGAN', $request->poli1)
            ->where('ar.DOKTER', $request->dr1)
            ->where('par.STATUS', 1)
            ->where('ar.TANGGAL',$tgl)
            ->orderBy('par.ID', 'ASC')
            ->first();

        $antrianDipanggilId1 = $dipanggil1->ANTRIAN_ID ?? null;

        // MENUNGGU (exclude yang dipanggil)
        $menunggu1 = DB::table('pendaftaran.antrian_ruangan AS ar')
            ->select(
                'pp.NORM',
                'ar.ID',
                DB::raw('master.getNamaLengkap(pp.NORM) AS NAMAPASIEN'),
                'ar.POS','ar.NOMOR AS NOMORANTREAN','ar.STATUS AS STATUSANTREAN',
                // 'par.STATUS AS STATUSPANGGILAN',
                'ru.DESKRIPSI AS NAMARUANGAN'
            )
            ->leftJoin('master.ruangan AS ru','ar.RUANGAN','=','ru.ID')
            ->leftJoin('pendaftaran.pendaftaran AS pp', function($join) {
                $join->on('pp.NOMOR','=','ar.REF')
                    ->where('pp.STATUS', '!=', 0);
            })
            ->join('pendaftaran.tujuan_pasien AS tp', function($join) use ($request) {
                $join->on('tp.NOPEN','=','pp.NOMOR')
                    ->where('tp.STATUS', '=', 2) // SUDAH DITERIMA DI KUNJUNGAN PASIEN
                    ->where('tp.RUANGAN', '=', $request->poli1);
            })
            ->where('ar.RUANGAN', $request->poli1)
            ->where('ar.DOKTER', $request->dr1)
            ->where('ar.STATUS', '!=', 0) // TIDAK BATAL ANTRIAN
            ->where('ar.TANGGAL',$tgl)
            ->when($antrianDipanggilId1, function($q) use ($antrianDipanggilId1) {
                $q->where('ar.ID','!=',$antrianDipanggilId1);
            })
            ->whereNotExists(function($q) {
                $q->select(DB::raw(1))
                ->from('pendaftaran.panggilan_antrian_ruangan AS par2')
                ->whereRaw('par2.ANTRIAN_RUANGAN = ar.ID');
            })
            ->orderBy('ar.NOMOR', 'ASC')
            ->get();

        $poli1 = DB::table('master.ruangan AS ru')
            ->select('ru.DESKRIPSI AS NAMARUANGAN')
            ->where('ru.ID', $request->poli1)
            ->first();

        $dokter1 = DB::table('penjamin_rs.dpjp as dpjp')
            ->leftJoin('master.dokter as md','md.ID','=','dpjp.DPJP_RS')
            ->leftJoin('aplikasi.pengguna as pe','pe.NIP','=','md.NIP')
            ->select(DB::raw('master.getNamaLengkapPegawai(md.NIP) AS NAMADOKTER'))
            ->where('dpjp.STATUS',1)
            ->where('dpjp.DPJP_PENJAMIN',$request->dr1)
            ->first();

        $selesai1 = '';
        $dipanggil2 = '';
        $menunggu2 = '';
        $poli2 = '';
        $dokter2 = '';

        if ($request->md != 1) { // JIKA DISPLAY SINGLE
            $menungguIds = $menunggu1->pluck('ID')->toArray(); // ambil ID dari $menunggu

            $selesai1 = DB::table('pendaftaran.antrian_ruangan AS ar')
                ->select(
                    'pp.NORM','ar.ID',
                    DB::raw('master.getNamaLengkap(pp.NORM) AS NAMAPASIEN'),
                    'ar.POS','ar.NOMOR AS NOMORANTREAN','ar.STATUS AS STATUSANTREAN',
                    'par.STATUS AS STATUSPANGGILAN',
                    'ru.DESKRIPSI AS NAMARUANGAN'
                )
                ->leftJoin('pendaftaran.panggilan_antrian_ruangan AS par', function($join) {
                    $join->on('ar.ID','=','par.ANTRIAN_RUANGAN')
                        ->where('par.STATUS', 2);
                })
                ->leftJoin('pendaftaran.pendaftaran AS pp', function($join) {
                    $join->on('pp.NOMOR','=','ar.REF')
                        ->where('pp.STATUS', '!=', 0);
                })
                ->join('pendaftaran.tujuan_pasien AS tp', function($join) use ($request) {
                    $join->on('tp.NOPEN','=','pp.NOMOR')
                        ->where('tp.STATUS', '=', 2) // SUDAH DITERIMA DI KUNJUNGAN PASIEN
                        ->where('tp.RUANGAN', '=', $request->poli1);
                })
                ->leftJoin('master.ruangan AS ru','ar.RUANGAN','=','ru.ID')
                ->where('ar.RUANGAN', $request->poli1)
                ->where('ar.DOKTER', $request->dr1)
                ->where('ar.STATUS', '!=', 0)
                ->where('ar.TANGGAL',$tgl)
                ->when($antrianDipanggilId1, function($q) use ($antrianDipanggilId1) {
                    $q->where('ar.ID','!=',$antrianDipanggilId1);
                })
                ->when(!empty($menungguIds), function($q) use ($menungguIds) {
                    $q->whereNotIn('ar.ID', $menungguIds); // filter yang ada di menunggu
                })
                ->groupBy('NORM','ID','NAMAPASIEN','POS','NOMORANTREAN','STATUSANTREAN','STATUSPANGGILAN','NAMARUANGAN')
                // ->orderByRaw('CASE WHEN par.ID IS NULL THEN 1 ELSE 0 END, par.ID DESC, ar.NOMOR DESC')
                ->orderBy('ar.NOMOR','DESC')
                ->get();
            $debug = 'berhasil';
        } else {
            // Ambil yang sedang dipanggil dulu
            $dipanggil2 = DB::table('pendaftaran.panggilan_antrian_ruangan AS par')
                ->select(
                    'par.ID',
                    'pp.NORM',
                    DB::raw('master.getNamaLengkap(pp.NORM) AS NAMAPASIEN'),
                    DB::raw('master.getNamaDokterSingkat(dr.NIP) AS NAMADOKTER'),
                    'ar.POS','ar.NOMOR AS NOMORANTREAN','ar.STATUS AS STATUSANTREAN',
                    'par.STATUS AS STATUSPANGGILAN',
                    'ru.DESKRIPSI AS NAMARUANGAN',
                    'ar.ID AS ANTRIAN_ID'
                )
                ->join('pendaftaran.antrian_ruangan AS ar', function($join) {
                    $join->on('ar.ID','=','par.ANTRIAN_RUANGAN')
                        ->where('ar.STATUS', '!=', 0); // TIDAK BATAL KUNJUNGAN
                })
                ->leftJoin('pendaftaran.pendaftaran AS pp', function($join) {
                    $join->on('pp.NOMOR','=','ar.REF')
                        ->where('pp.STATUS', '!=', 0);
                })
                ->join('pendaftaran.tujuan_pasien AS tp', function($join) use ($request) {
                    $join->on('tp.NOPEN','=','pp.NOMOR')
                        ->where('tp.STATUS', '=', 2) // SUDAH DITERIMA DI KUNJUNGAN PASIEN
                        ->where('tp.RUANGAN', '=', $request->poli1);
                })
                ->leftJoin('master.ruangan AS ru','ar.RUANGAN','=','ru.ID')
                ->leftJoin('penjamin_rs.dpjp as dpjp', function($join) {
                    $join->on('dpjp.DPJP_PENJAMIN','=','ar.DOKTER')
                        ->where('dpjp.STATUS', '!=', 0);
                })
                ->leftJoin('master.dokter as dr','dr.ID','=','dpjp.DPJP_RS')
                ->where('ar.RUANGAN', $request->poli2)
                ->where('ar.DOKTER', $request->dr2)
                ->where('par.STATUS', 1)
                ->where('ar.TANGGAL',$tgl)
                ->orderBy('par.ID', 'ASC')
                ->first();

            $antrianDipanggilId2 = $dipanggil2->ANTRIAN_ID ?? null;

            // MENUNGGU (exclude yang dipanggil)
            $menunggu2 = DB::table('pendaftaran.antrian_ruangan AS ar')
                ->select(
                    'pp.NORM',
                    'ar.ID',
                    DB::raw('master.getNamaLengkap(pp.NORM) AS NAMAPASIEN'),
                    'ar.POS','ar.NOMOR AS NOMORANTREAN','ar.STATUS AS STATUSANTREAN',
                    // 'par.STATUS AS STATUSPANGGILAN',
                    'ru.DESKRIPSI AS NAMARUANGAN'
                )
                // ->join('pendaftaran.antrian_ruangan AS ar', function($join) {
                //     $join->on('ar.ID','=','par.ANTRIAN_RUANGAN')
                //         ->where('ar.STATUS', '!=', 0); // TIDAK BATAL KUNJUNGAN
                // })
                ->leftJoin('master.ruangan AS ru','ar.RUANGAN','=','ru.ID')
                ->leftJoin('pendaftaran.pendaftaran AS pp', function($join) {
                    $join->on('pp.NOMOR','=','ar.REF')
                        ->where('pp.STATUS', '!=', 0);
                })
                ->join('pendaftaran.tujuan_pasien AS tp', function($join) use ($request) {
                    $join->on('tp.NOPEN','=','pp.NOMOR')
                        ->where('tp.STATUS', '=', 2) // SUDAH DITERIMA DI KUNJUNGAN PASIEN
                        ->where('tp.RUANGAN', '=', $request->poli1);
                })
                ->where('ar.RUANGAN', $request->poli2)
                ->where('ar.DOKTER', $request->dr2)
                // ->where('ar.STATUS', 1) // MENUNGGU DIPANGGIL
                ->where('ar.STATUS', '!=', 0) // TIDAK BATAL ANTRIAN
                ->where('ar.TANGGAL',$tgl)
                ->when($antrianDipanggilId2, function($q) use ($antrianDipanggilId2) {
                    $q->where('ar.ID','!=',$antrianDipanggilId2);
                })
                ->whereNotExists(function($q) {
                    $q->select(DB::raw(1))
                    ->from('pendaftaran.panggilan_antrian_ruangan AS par2')
                    ->whereRaw('par2.ANTRIAN_RUANGAN = ar.ID');
                })
                ->orderBy('ar.NOMOR', 'ASC')
                ->get();

            $poli2 = DB::table('master.ruangan AS ru')
                ->select('ru.DESKRIPSI AS NAMARUANGAN')
                ->where('ru.ID', $request->poli2)
                ->first();

            $dokter2 = DB::table('penjamin_rs.dpjp as dpjp')
                ->leftJoin('master.dokter as md','md.ID','=','dpjp.DPJP_RS')
                ->leftJoin('aplikasi.pengguna as pe','pe.NIP','=','md.NIP')
                ->select(DB::raw('master.getNamaLengkapPegawai(md.NIP) AS NAMADOKTER'))
                ->where('dpjp.STATUS',1)
                ->where('dpjp.DPJP_PENJAMIN',$request->dr2)
                ->first();
        }

        return response()->json([
            // ANTRIAN PERTAMA
            'menunggu1' => $menunggu1,
            'dipanggil1' => $dipanggil1,
            'selesai1' => $selesai1,
            'poli1' => $poli1,
            'dokter1' => $dokter1,
            // ANTRIAN KEDUA
            'menunggu2' => $menunggu2,
            'dipanggil2' => $dipanggil2,
                // 'selesai2' => $selesai2,
            'poli2' => $poli2,
            'dokter2' => $dokter2,
            'debug' => $debug,
        ], 200);
    }

    function updatePanggilanAntrian(Request $request)
    {
        $updated = panggilan_antrian_ruangan::where('ANTRIAN_RUANGAN', $request->ID)
            ->where('STATUS', 1)
            ->update(['STATUS' => 2]);

        return response()->json(['updated' => $updated]);
    }
}
