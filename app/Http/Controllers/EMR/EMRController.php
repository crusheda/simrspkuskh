<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\simrspku_klaim\klaim_verifikasi;
use App\Models\simrspku_klaim\klaim_file;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PHPJasper\PHPJasper;
use Carbon\Carbon;
use Auth, Storage;

class EMRController extends Controller
{
    // INDEX
    function index()
    {
        $yearMonth = Carbon::now()->isoFormat('YYYY-MM');
        $dr = DB::table('master.dokter AS dr')
                ->select(
                    'dr.id',
                    'dr.NIP',
                    DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'),
                    'ref.DESKRIPSI'
                )
                ->leftJoin('master.pegawai AS pg','pg.NIP','=','dr.NIP')
                ->leftJoin('master.referensi AS ref', function($join) {
                    $join->on('ref.ID','=','pg.SMF')
                        ->where('ref.JENIS', '26');
                })
                ->leftJoin('master.dokter_ruangan AS dru','dru.DOKTER','=','dr.ID')
                ->where('dr.STATUS','1')
                ->where('dru.STATUS','1')
                ->where(function ($query) {
                    $query->where('dru.RUANGAN', 'LIKE', '1020101%');
                })
                // ->orderByRaw("CASE WHEN ref.ID = '0' THEN 1 ELSE 0 END")
                ->orderBy('ref.DESKRIPSI','ASC')
                ->groupBy('dr.id','dr.NIP','NAMADOKTER')
                ->get();

        $data = [
            'yearMonth' => $yearMonth,
            'dr' => $dr,
        ];

        return view('pages.emr.index')->with('list', $data);
    }

    function detail($KUNJUNGAN)
    {
        return view('pages.emr.detail')->with('KUNJUNGAN', $KUNJUNGAN);
    }

    // API
    function table(Request $request)
    {
        $user = auth()->user();

        // INIT
        $tgls   = $request->tgls;
        $tgle   = $request->tgle;
        $dpjp   = $request->dpjp;
        $status = (int) $request->status;
        $rawat = (int) $request->rawat;

        $time = Carbon::now()->isoFormat('YYYY-MM-DD HH:mm:ss');

        // MAIN QUERY
        $show = DB::table('pendaftaran.kunjungan AS pk')
                ->select(
                    'pk.*',
                    'pp.NORM','pp.TANGGAL AS TGLDAFTAR',
                    'kjs.noSEP AS NOSEP','kjs.tglSEP AS TGLSEP',
                    'ru.DESKRIPSI AS NAMARUANGAN',
                    DB::raw('master.getNamaLengkap(ps.NORM) AS NAMAPASIEN'),
                    DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'),
                )
                ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                ->leftJoin('pendaftaran.penjamin AS pj','pj.NOPEN','=','pp.NOMOR')
                ->leftJoin('medicalrecord.perencanaan_rawat_inap AS pri','pri.KUNJUNGAN','=','pk.NOMOR')
                ->leftJoin('pembayaran.tagihan_pendaftaran AS tp','tp.PENDAFTARAN','=','pk.NOPEN')
                ->leftJoin('bpjs.kunjungan AS kjs','kjs.noSEP','=','pj.NOMOR')
                ->leftJoin('master.pasien AS ps','ps.NORM','=','pp.NORM')
                ->leftJoin('aplikasi.pengguna','aplikasi.pengguna.ID','=','pk.DITERIMA_OLEH')
                ->leftJoin('master.ruangan AS ru','ru.ID','=','pk.RUANGAN')
                ->leftJoin('master.dokter AS dr','dr.ID','=','pk.DPJP')

                ->where(function ($query) use ($tgls,$tgle) {
                    $query->whereRaw("LEFT(pk.MASUK, 10) BETWEEN ? AND ?", [$tgls, $tgle]);
                })
                ->where('pj.JENIS', 2) // PENJAMIN BPJS ONLY
                // ->where('pk.BARU', 1) // KUNJUNGAN PERTAMA
                ->where('ru.STATUS', 1) // STATUS RUANGAN AKTIF
                // ->where('jk.STATUS', 1) // STATUS RENCANA KONTROL AKTIF

                // FILTER RUANGAN
                ->when(in_array($rawat, [1, 2, 3]), function ($query) use ($rawat) {
                    $prefix = '';
                    switch ($rawat) {
                        case 1:
                            $prefix = '1020101%'; // RAJAL
                            break;
                        case 2:
                            $prefix = '1020201%'; // RADAR
                            break;
                        case 3:
                            $prefix = '1020301%'; // RANAP
                            break;
                    }
                    $query->where('pk.RUANGAN', 'LIKE', $prefix);
                })
                ->when($rawat == 5, function ($query) {
                    $query->where(function ($q) {
                        $q->where('pk.RUANGAN', 'LIKE', '1020101%')
                            ->orWhere('pk.RUANGAN', 'LIKE', '1020201%')
                            ->orWhere('pk.RUANGAN', 'LIKE', '1020301%');
                    });
                })

                // KHUSUS RAWAT DARURAT (TANPA PERENCANAAN RAWAT INAP)
                ->when($rawat == 2, function ($query) use ($rawat) {
                    $query->where(function ($q) {
                        $q->where('tp.UTAMA', 1)
                            ->where('tp.STATUS', 1)
                            ->whereNull('pri.KUNJUNGAN');
                    });
                })

                // FILTER STATUS KUNJUNGAN
                ->when($status != 5, function ($query) use ($status) { // 0=BATAL;1=MASIH DILAYANI;2=SELESAI;5=ALL
                    $query->where('pk.STATUS', $status);
                            // ->where('pp.STATUS', $status);
                })
                ->when($dpjp != 0, function ($query) use ($dpjp) {
                    // Hanya menambahkan where jika $dpjp bukan 0
                    $query->where('dr.NIP', $dpjp);
                })
                ->orderBy('pk.MASUK','DESC')
                ->get();

        $data = [
            'show' => $show,
            'time' => $time,
        ];

        return response()->json($data, 200);
    }
}
