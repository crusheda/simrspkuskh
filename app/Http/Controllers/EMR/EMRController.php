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

        $tte_pegawai = DB::table('simrspku_klaim.tanda_tangan_pegawai')->where('nip',Auth::user()->NIP)->whereNull('deleted_at')->exists();

        $data = [
            'yearMonth' => $yearMonth,
            'dr' => $dr,
            'tte_pegawai' => $tte_pegawai,
        ];

        return view('pages.emr.index')->with('list', $data);
    }

    function detail($KUNJUNGAN)
    {
        $show = DB::table('pendaftaran.kunjungan AS pk')
                ->select(
                    'pk.*',
                    'pp.NORM','pp.TANGGAL AS TGLDAFTAR',
                    'kjs.noSEP AS NOSEP','kjs.tglSEP AS TGLSEP',
                    'kjs.noKartu AS NOBPJS',
                    'ru.ID AS IDRUANGAN',
                    'ru.DESKRIPSI AS NAMARUANGAN',
                    'kips.NOMOR AS NIKPASIEN',
                    'ps.NAMA AS NAMALENGKAPPASIEN',
                    'ps.PANGGILAN AS PANGGILANPASIEN',
                    'ps.TANGGAL_LAHIR AS TGLLAHIRPASIEN',
                    'kps.NOMOR AS NOHPPASIEN',
                    'kgs.NAMA AS KELUARGAPASIEN',
                    DB::raw("
                        IF(
                            ps.JENIS_KELAMIN = 1,
                            'LAKI-LAKI',
                            IF(
                                ps.JENIS_KELAMIN = 2,
                                'PEREMPUAN',
                                'TIDAK DIKETAHUI'
                            )
                        ) AS JKPASIEN
                    "),
                    DB::raw("
                        IF(
                            kgs.JENIS_KELAMIN = 1,
                            'LAKI-LAKI',
                            IF(
                                kgs.JENIS_KELAMIN = 2,
                                'PEREMPUAN',
                                'TIDAK DIKETAHUI'
                            )
                        ) AS JKKELUARGAPASIEN
                    "),
                    DB::raw("(
                        SELECT DESKRIPSI
                        FROM master.referensi AS refkgs
                        WHERE refkgs.ID = kgs.SHDK
                        AND refkgs.JENIS = 7
                    ) AS STKELUARGAPASIEN"),
                    DB::raw('master.getNamaLengkap(ps.NORM) AS NAMAPASIEN'),
                    DB::raw('master.getAlamatPasienCustom(ps.NORM) AS ALAMATPASIEN'),
                    DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'),
                    DB::raw('master.getCariUmur(pp.TANGGAL,ps.TANGGAL_LAHIR) AS UMURPASIEN'),
                    DB::raw("(
                        SELECT DESKRIPSI
                        FROM master.wilayah AS wil
                        WHERE wil.ID = ps.TEMPAT_LAHIR
                    ) AS TLPASIEN"),
                )
                ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                ->leftJoin('pendaftaran.penjamin AS pj','pj.NOPEN','=','pp.NOMOR')
                ->leftJoin('bpjs.kunjungan AS kjs','kjs.noSEP','=','pj.NOMOR')
                ->leftJoin('master.pasien AS ps','ps.NORM','=','pp.NORM')
                ->leftJoin('master.kontak_pasien AS kps','ps.NORM','=','kps.NORM')
                ->leftJoin('master.keluarga_pasien AS kgs','ps.NORM','=','kgs.NORM')
                ->leftJoin('master.kartu_identitas_pasien AS kips','ps.NORM','=','kips.NORM')
                ->leftJoin('master.ruangan AS ru','ru.ID','=','pk.RUANGAN')
                ->leftJoin('master.dokter AS dr','dr.ID','=','pk.DPJP')
                ->where('pk.NOMOR',$KUNJUNGAN)
                ->first();

        if ($show) {
            $riwayat = DB::table('pendaftaran.kunjungan AS pk')
                    ->select(
                        'pk.NOMOR AS NOKUNJUNGAN','pp.TANGGAL AS TGLDAFTAR',
                        'pp.STATUS AS STATUSDAFTAR','pk.STATUS AS STATUSKUNJUNGAN',
                        'kjs.noSEP AS NOSEP','kjs.tglSEP AS TGLSEP',
                        'ru.DESKRIPSI AS NAMARUANGAN',
                        DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'),
                    )
                    ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                    ->leftJoin('pendaftaran.penjamin AS pj','pj.NOPEN','=','pp.NOMOR')
                    ->leftJoin('bpjs.kunjungan AS kjs','kjs.noSEP','=','pj.NOMOR')
                    ->leftJoin('master.ruangan AS ru','ru.ID','=','pk.RUANGAN')
                    ->leftJoin('master.dokter AS dr','dr.ID','=','pk.DPJP')
                    ->where(function ($q) {
                        $q->where('pk.RUANGAN', 'LIKE', '1020101%')
                        ->orWhere('pk.RUANGAN', 'LIKE', '1020201%')
                        ->orWhere('pk.RUANGAN', 'LIKE', '1020301%')
                        ->orWhere('pk.RUANGAN', 'LIKE', '1020702%');
                    })
                    ->where('pp.NORM',$show->NORM)
                    ->orderBy('pp.TANGGAL','DESC')
                    ->get();

            $tte_pegawai = DB::table('simrspku_klaim.tanda_tangan_pegawai')->where('nip',Auth::user()->NIP)->whereNull('deleted_at')->exists();

            $data = [
                'show' => $show,
                'riwayat' => $riwayat,
                'KUNJUNGAN' => $KUNJUNGAN,
                'tte_pegawai' => $tte_pegawai,
            ];

            return view('pages.emr.detail')->with('list', $data);
        } else {
            return redirect()->back()->withErrors('Kunjungan '.$KUNJUNGAN.' Tidak Ditemukan');
        }
    }

    // API
    function ruangan($id)
    {
        $prefix = '';
        if ($id == 1) {
            $prefix = ['1020101%', '1020702%'];
        } elseif ($id == 2) {
            $prefix = ['1020201%'];
        } elseif ($id == 3) {
            $prefix = ['1020301%'];
        } else {
            return response()->json('Tidak ada Ruangan yang sesuai!', 404);
        }

        $ruangan = DB::table('master.ruangan AS ru')
                ->where('ru.JENIS',5)
                ->where('ru.STATUS',1)
                ->where(function ($q) use ($prefix) {
                    foreach ($prefix as $p) {
                        $q->orWhere('ru.ID', 'LIKE', $p);
                    }
                })
                ->orderBy('ru.ID','ASC')
                ->get();

        return response()->json($ruangan, 200);
    }

    function table(Request $request)
    {
        $user = auth()->user();

        // INIT
        $tgls   = $request->tgls;
        $tgle   = $request->tgle;
        $dpjp   = $request->dpjp;
        $ruang   = $request->ruang;
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

                // FILTER JENIS PERAWATAN
                ->when(in_array($rawat, [1, 2, 3]), function ($query) use ($rawat) {
                    $prefix = [];
                    switch ($rawat) {
                        case 1:
                            $prefix = ['1020101%','1020702%'];
                            break;
                        case 2:
                            $prefix = ['1020201%'];
                            break;
                        case 3:
                            $prefix = ['1020301%'];
                            break;
                    }

                    $query->where(function ($q) use ($prefix) {
                        foreach ($prefix as $p) {
                            $q->orWhere('pk.RUANGAN', 'LIKE', $p);
                        }
                    });
                })
                ->when($rawat == 5, function ($query) {
                    $query->where(function ($q) {
                        $q->where('pk.RUANGAN', 'LIKE', '1020101%')
                            ->orWhere('pk.RUANGAN', 'LIKE', '1020702%')
                            ->orWhere('pk.RUANGAN', 'LIKE', '1020201%')
                            ->orWhere('pk.RUANGAN', 'LIKE', '1020301%');
                    });
                })

                // FILTER RUANGAN
                ->when($ruang != 5, function ($query) use ($ruang) { // 0=BATAL;1=MASIH DILAYANI;2=SELESAI;5=ALL
                    $query->where('pk.RUANGAN', $ruang);
                            // ->where('pp.STATUS', $status);
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
