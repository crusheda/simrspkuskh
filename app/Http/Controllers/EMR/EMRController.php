<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\simrspku_klaim\klaim_verifikasi;
use App\Models\simrspku_klaim\klaim_file;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PHPJasper\PHPJasper;
use Carbon\Carbon;
use Auth, Storage;

// CONTROLLER FORM
use App\Http\Controllers\EMR\Form\GawatDarurat\PengkajianGawatDaruratController;

use App\Http\Controllers\EMR\Form\RawatJalan\PengkajianRawatJalanDewasaController;
use App\Http\Controllers\EMR\Form\RawatJalan\PengkajianRawatJalanAnakController;
use App\Http\Controllers\EMR\Form\RawatJalan\PengkajianRawatJalanJiwaController;
use App\Http\Controllers\EMR\Form\RawatJalan\PengkajianRawatJalanGeriatriController;
use App\Http\Controllers\EMR\Form\RawatJalan\PengkajianRawatJalanObsgynController;

use App\Http\Controllers\EMR\Form\RawatInap\PengkajianRawatInapDewasaController;
use App\Http\Controllers\EMR\Form\RawatInap\PengkajianRawatInapAnakController;
use App\Http\Controllers\EMR\Form\RawatInap\PengkajianRawatInapNeonatusController;
use App\Http\Controllers\EMR\Form\RawatInap\PengkajianRawatInapObsgynController;

use App\Http\Controllers\EMR\Form\Khusus\PengkajianKhususRemajaController;
use App\Http\Controllers\EMR\Form\Khusus\PengkajianKhususTerminalController;
use App\Http\Controllers\EMR\Form\Khusus\PengkajianKhususNyeriKronikController;
use App\Http\Controllers\EMR\Form\Khusus\PengkajianKhususSistemImunTergangguController;
use App\Http\Controllers\EMR\Form\Khusus\PengkajianKhususKecanduanObatAlkoholController;
use App\Http\Controllers\EMR\Form\Khusus\PengkajianKhususKorbanKekerasanController;
use App\Http\Controllers\EMR\Form\Khusus\PengkajianKhususPenyakitMenularController;
use App\Http\Controllers\EMR\Form\Khusus\PengkajianKhususLanjutanController;

class EMRController extends Controller
{
    // INDEX
    function index() // SIRMED v.1
    {
        $yearMonth = Carbon::now()->isoFormat('YYYY-MM');

        $tte_pegawai = DB::table('simrspku_klaim.tanda_tangan_pegawai')->where('nip',Auth::user()->NIP)->whereNull('deleted_at')->exists();

        $data = [
            'yearMonth' => $yearMonth,
            // 'dr' => $dr,
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
            // $riwayat = DB::table('pendaftaran.kunjungan AS pk')
            //         ->select(
            //             'pk.NOMOR AS NOKUNJUNGAN','pp.TANGGAL AS TGLDAFTAR',
            //             'pp.STATUS AS STATUSDAFTAR','pk.STATUS AS STATUSKUNJUNGAN',
            //             'kjs.noSEP AS NOSEP','kjs.tglSEP AS TGLSEP',
            //             'ru.DESKRIPSI AS NAMARUANGAN',
            //             DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'),
            //         )
            //         ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
            //         ->leftJoin('pendaftaran.penjamin AS pj','pj.NOPEN','=','pp.NOMOR')
            //         ->leftJoin('bpjs.kunjungan AS kjs','kjs.noSEP','=','pj.NOMOR')
            //         ->leftJoin('master.ruangan AS ru','ru.ID','=','pk.RUANGAN')
            //         ->leftJoin('master.dokter AS dr','dr.ID','=','pk.DPJP')
            //         ->where(function ($q) {
            //             $q->where('pk.RUANGAN', 'LIKE', '1020101%')
            //             ->orWhere('pk.RUANGAN', 'LIKE', '1020201%')
            //             ->orWhere('pk.RUANGAN', 'LIKE', '1020301%')
            //             ->orWhere('pk.RUANGAN', 'LIKE', '1020702%');
            //         })
            //         ->where('pp.NORM',$show->NORM)
            //         ->where('pp.STATUS', '!=', 0)
            //         ->orderBy('pp.TANGGAL','DESC')
            //         ->get();

            $tte_pegawai = DB::table('simrspku_klaim.tanda_tangan_pegawai')->where('nip',Auth::user()->NIP)->whereNull('deleted_at')->exists();

            $data = [
                'show' => $show,
                // 'riwayat' => $riwayat,
                'KUNJUNGAN' => $KUNJUNGAN,
                'tte_pegawai' => $tte_pegawai,
            ];

            return view('pages.emr.detail')->with('list', $data);
        } else {
            return redirect()->back()->withErrors('Kunjungan '.$KUNJUNGAN.' Tidak Ditemukan');
        }
    }

    function indexV2() // SIRMED v.2
    {
        $yearMonth = Carbon::now()->isoFormat('YYYY-MM');

        $tte_pegawai = DB::table('simrspku_klaim.tanda_tangan_pegawai')->where('nip',Auth::user()->NIP)->whereNull('deleted_at')->exists();

        $data = [
            'yearMonth' => $yearMonth,
            // 'dr' => $dr,
            'tte_pegawai' => $tte_pegawai,
        ];

        return view('pages.v2.medicalrecord.index')->with('list', $data);
    }

    function detailV2($KUNJUNGAN)
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

            $tte_pegawai = DB::table('simrspku_klaim.tanda_tangan_pegawai')->where('nip',Auth::user()->NIP)->whereNull('deleted_at')->exists();

            $data = [
                'show' => $show,
                // 'riwayat' => $riwayat,
                'KUNJUNGAN' => $KUNJUNGAN,
                'tte_pegawai' => $tte_pegawai,
            ];

            return view('pages.v2.medicalrecord.detail.index')->with('list', $data);
        } else {
            return redirect()->back()->withErrors('Kunjungan '.$KUNJUNGAN.' Tidak Ditemukan');
        }
    }

    // API
    function getRiwayatKunjungan($NORM)
    {
        $riwayat = DB::table('pendaftaran.kunjungan AS pk')
            ->select(
                'pk.NOMOR AS NOKUNJUNGAN',
                'pp.TANGGAL AS TGLDAFTAR',
                'pp.STATUS AS STATUSDAFTAR',
                'pk.STATUS AS STATUSKUNJUNGAN',
                'kjs.noSEP AS NOSEP',
                'kjs.tglSEP AS TGLSEP',
                'ru.DESKRIPSI AS NAMARUANGAN',
                DB::raw("
                    master.getNamaLengkapPegawai(
                        CASE
                            WHEN dr.NIP IS NULL OR dr.NIP = 0 OR dr.NIP = ''
                            THEN pj.DPJP_LAYANAN
                            ELSE dr.NIP
                        END
                    ) AS NAMADOKTER
                "),
            )
            ->leftJoin('pendaftaran.pendaftaran AS pp', 'pp.NOMOR', '=', 'pk.NOPEN')
            ->leftJoin('pendaftaran.penjamin AS pj', 'pj.NOPEN', '=', 'pp.NOMOR')
            ->leftJoin('bpjs.kunjungan AS kjs', 'kjs.noSEP', '=', 'pj.NOMOR')
            ->leftJoin('master.ruangan AS ru', 'ru.ID', '=', 'pk.RUANGAN')
            ->leftJoin('master.dokter AS dr', 'dr.ID', '=', 'pk.DPJP')
            ->where(function ($q) {
                $q->where('pk.RUANGAN', 'LIKE', '1020101%')
                    ->orWhere('pk.RUANGAN', 'LIKE', '1020201%')
                    ->orWhere('pk.RUANGAN', 'LIKE', '1020301%')
                    ->orWhere('pk.RUANGAN', 'LIKE', '1020702%');
            })
            ->where('pp.NORM', $NORM)
            ->where('pp.STATUS', '!=', 0)
            ->orderBy('pp.TANGGAL', 'DESC')
            ->get();

        return response()->json([
            'show' => $riwayat,
        ], 200);
    }

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

    function penjamin()
    {
        $show = DB::table('master.referensi')
                ->where('JENIS',10)
                ->where('STATUS',1)
                ->orderBy('ID','ASC')
                ->get();

        if (!$show) {
            return response()->json('Tidak ada Penjamin yang tersedia!', 404);
        }
        return response()->json($show, 200);
    }

    function dpjp($ruangan)
    {
        $show = DB::table('master.dokter_ruangan AS dru')
                ->join('master.dokter as dr', function($join) {
                    $join->on('dr.ID','=','dru.DOKTER')
                        ->where('dr.STATUS', 1);
                })
                ->join('master.pegawai AS pg', function($join) {
                    $join->on('pg.NIP','=','dr.NIP')
                        ->where('pg.STATUS', 1);
                })
                ->join('master.referensi AS ref', function($join) {
                    $join->on('ref.ID','=','pg.SMF')
                        ->where('ref.JENIS', '26');
                })
                ->select(
                    'dr.ID',
                    'dr.NIP',
                    DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'),
                    DB::raw('MIN(dru.RUANGAN) AS RUANGAN'),
                    'ref.DESKRIPSI'
                )
                ->when($ruangan != 5, function ($query) use ($ruangan) {
                    $query->where('dru.RUANGAN', $ruangan);
                })
                ->where('dru.STATUS',1)
                ->orderBy('ref.DESKRIPSI','ASC')
                ->groupBy('dr.ID','dr.NIP','ref.DESKRIPSI')
                ->get();

        if ($show->isEmpty()) {
            return response()->json('Tidak ada DPJP yang sesuai pada Ruangan Tersebut!', 404);
        }

        $user = Auth::user()->NIP;

        $data = [
            'show' => $show,
            'user' => $user,
        ];

        return response()->json($data, 200);
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
        $penjamin = (int) $request->penjamin;
        // $penjamin = 1;

        $time = Carbon::now()->isoFormat('YYYY-MM-DD HH:mm:ss');

        // MAIN QUERY
        $show = DB::table('pendaftaran.kunjungan AS pk')
                ->select(
                    'pk.*',
                    'pp.NORM','pp.TANGGAL AS TGLDAFTAR',
                    'ar.POS AS POS_ANTRIAN','ar.NOMOR AS NOMOR_ANTRIAN','ar.JENIS AS JENIS_ANTRIAN',
                    'ru.DESKRIPSI AS NAMARUANGAN',
                    'pj.JENIS AS JENISPENJAMIN',
                    'ref.DESKRIPSI AS NAMAPENJAMIN',
                    // DB::raw('kjs.noSEP AS NOSEP'),
                    // DB::raw('kjs.tglSEP AS TGLSEP'),
                    DB::raw('master.getNamaLengkap(ps.NORM) AS NAMAPASIEN'),
                    DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'),
                );
                if (in_array($penjamin,[0,2])) {
                    $show->addSelect(
                        DB::raw('kjs.noSEP AS NOSEP'),
                        DB::raw('kjs.tglSEP AS TGLSEP')
                    );
                } else {
                    $show->addSelect(
                        DB::raw('"" AS NOSEP'),
                        DB::raw('NULL AS TGLSEP')
                    );
                }
        $show = $show->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                ->when($penjamin != 0, function ($query) use ($penjamin) {
                    $query->join('pendaftaran.penjamin AS pj', function($join) use ($penjamin) {
                        $join->on('pj.NOPEN','=','pp.NOMOR')
                            ->where('pj.JENIS', $penjamin);
                    });
                }, function ($query) {
                    $query->leftJoin('pendaftaran.penjamin AS pj','pj.NOPEN','=','pp.NOMOR');
                })
                ->leftJoin('pendaftaran.antrian_ruangan AS ar', function($join){
                    $join->on('ar.REF','=','pp.NOMOR')
                        ->where('ar.NOMOR', '!=', 0)
                        ->where('ar.JENIS', 1); // RAWAT JALAN
                })
                ->join('master.referensi AS ref', function($join){
                    $join->on('ref.ID','=','pj.JENIS')
                        ->where('ref.STATUS', 1)
                        ->where('ref.JENIS', 10);
                })
                ->leftJoin('medicalrecord.perencanaan_rawat_inap AS pri','pri.KUNJUNGAN','=','pk.NOMOR')
                ->leftJoin('pembayaran.tagihan_pendaftaran AS tp','tp.PENDAFTARAN','=','pk.NOPEN')
                ->leftJoin('bpjs.kunjungan AS kjs', function($join){
                    $join->on('kjs.noSEP','=','pj.NOMOR')
                        ->where('kjs.STATUS', 1)
                        ->where('kjs.noSEP','!=',''); // di table bpjs.kunjungan ada kolom noSEP yg kosong / ''
                })
                ->leftJoin('master.pasien AS ps','ps.NORM','=','pp.NORM')
                ->leftJoin('aplikasi.pengguna','aplikasi.pengguna.ID','=','pk.DITERIMA_OLEH')
                ->join('master.ruangan AS ru', function($join){
                    $join->on('ru.ID','=','pk.RUANGAN')
                        ->where('ru.STATUS', 1);
                })
                ->leftJoin('master.dokter AS dr','dr.ID','=','pk.DPJP')
                // KHUSUS $penjamin = 2 / BPJS
                // ->when($penjamin == 2, function ($query) {
                //     $query->leftJoin('bpjs.kunjungan AS kjs','kjs.noSEP','=','pj.NOMOR');
                // })

                ->where(function ($query) use ($tgls,$tgle) {
                    $query->whereRaw("LEFT(pk.MASUK, 10) BETWEEN ? AND ?", [$tgls, $tgle]);
                })
                // ->where('pj.JENIS', 2) // PENJAMIN BPJS ONLY
                // ->where('pk.BARU', 1) // KUNJUNGAN PERTAMA
                // ->where('ru.STATUS', 1) // STATUS RUANGAN AKTIF
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
                ->when($dpjp != 0 && $dpjp != 5, function ($query) use ($dpjp) {
                    // Hanya menambahkan where jika $dpjp bukan 0 dan bukan 5
                    $query->where('dr.NIP', $dpjp);
                })
                ->orderBy('pk.MASUK','DESC')
                ->distinct()
                ->get();

                // print_r($show);
                // die();
        $data = [
            'show' => $show,
            'time' => $time,
        ];

        return response()->json($data, 200);
    }

    public function loadFormPengkajian($form, $kunjungan)
    {
        // switch ($form) {
        //     // PENGKAJIAN AWAL
        //         // GAWAT DARURAT
        //         case 'pengkajian-gd':
        //             return view('pages.v2.medicalrecord.detail.form.pengkajian.gawat-darurat.index');

        //         // RAWAT JALAN
        //         case 'pengkajian-rajal-dewasa':
        //             return view('pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.dewasa.index');
        //         case 'pengkajian-rajal-anak':
        //             return view('pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.anak.index');
        //         case 'pengkajian-rajal-psikiatri':
        //             return view('pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.psikiatri.index');
        //         case 'pengkajian-rajal-geriatri':
        //             return view('pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.geriatri.index');
        //         case 'pengkajian-rajal-obsgyn':
        //             return view('pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.obsgyn.index');

        //         // RAWAT INAP
        //         case 'pengkajian-ranap-dewasa-anak':
        //             return view('pages.v2.medicalrecord.detail.form.pengkajian.rawat-inap.dewasa-anak.index');
        //         case 'pengkajian-ranap-neonatus':
        //             return view('pages.v2.medicalrecord.detail.form.pengkajian.rawat-inap.neonatus.index');
        //         case 'pengkajian-ranap-obsgyn':
        //             return view('pages.v2.medicalrecord.detail.form.pengkajian.rawat-inap.obsgyn.index');

        //     // PENGKAJIAN KHUSUS
        //         case 'pengkajian-khusus-remaja':
        //             return view('pages.v2.medicalrecord.detail.form.pengkajian.khusus.remaja.index');
        //         case 'pengkajian-khusus-terminal':
        //             return view('pages.v2.medicalrecord.detail.form.pengkajian.khusus.terminal.index');
        //         case 'pengkajian-khusus-nyeri-kronik':
        //             return view('pages.v2.medicalrecord.detail.form.pengkajian.khusus.nyeri-kronik.index');
        //         case 'pengkajian-khusus-sistem-imun-terganggu':
        //             return view('pages.v2.medicalrecord.detail.form.pengkajian.khusus.sistem-imun-terganggu.index');
        //         case 'pengkajian-khusus-kecanduan-obat-terlarang':
        //             return view('pages.v2.medicalrecord.detail.form.pengkajian.khusus.kecanduan-obat-terlarang.index');
        //         case 'pengkajian-khusus-korban-kekerasan':
        //             return view('pages.v2.medicalrecord.detail.form.pengkajian.khusus.korban-kekerasan.index');
        //         case 'pengkajian-khusus-penyakit-menular':
        //             return view('pages.v2.medicalrecord.detail.form.pengkajian.khusus.penyakit-menular.index');
        //         case 'pengkajian-khusus-lanjutan':
        //             return view('pages.v2.medicalrecord.detail.form.pengkajian.khusus.lanjutan.index');
        //     default:
        //         abort(404);
        // }

        $controllers = [
            'pengkajian-gd'                             => PengkajianGawatDaruratController::class,

            'pengkajian-rajal-dewasa'                   => PengkajianRawatJalanDewasaController::class,
            'pengkajian-rajal-anak'                     => PengkajianRawatJalanAnakController::class,
            'pengkajian-rajal-psikiatri'                => PengkajianRawatJalanJiwaController::class,
            'pengkajian-rajal-geriatri'                 => PengkajianRawatJalanGeriatriController::class,
            'pengkajian-rajal-obsgyn'                   => PengkajianRawatJalanObsgynController::class,

            'pengkajian-ranap-dewasa'                   => PengkajianRawatInapDewasaController::class,
            'pengkajian-ranap-anak'                     => PengkajianRawatInapAnakController::class,
            'pengkajian-ranap-neonatus'                 => PengkajianRawatInapNeonatusController::class,
            'pengkajian-ranap-obsgyn'                   => PengkajianRawatInapObsgynController::class,

            'pengkajian-khusus-remaja'                  => PengkajianKhususRemajaController::class,
            'pengkajian-khusus-terminal'                => PengkajianKhususTerminalController::class,
            'pengkajian-khusus-nyerikronik'             => PengkajianKhususNyeriKronikController::class,
            'pengkajian-khusus-sistemimunterganggu'     => PengkajianKhususSistemImunTergangguController::class,
            'pengkajian-khusus-kecanduanobatalkohol'    => PengkajianKhususKecanduanObatAlkoholController::class,
            'pengkajian-khusus-korbankekerasan'         => PengkajianKhususKorbanKekerasanController::class,
            'pengkajian-khusus-penyakitmenular'         => PengkajianKhususPenyakitMenularController::class,
            'pengkajian-khusus-lanjutan'                => PengkajianKhususLanjutanController::class,
        ];

        abort_unless(isset($controllers[$form]), 404);

        return app($controllers[$form])->index($kunjungan);
    }

    public function loadSubFormPengkajian(
        Request $request,
        string $kunjungan,
        string $formKey
    ) {
        $forms = [
            // Rawat inap dewasa
            'rid_dokter' => [
                'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-inap.dewasa.form_dokter',
            ],
            'rid_perawat' => [
                'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-inap.dewasa.form_perawat',
            ],

            // Siapkan untuk Rawat Inap Anak
            'ria_dokter' => [
                'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-inap.anak.form_dokter',
            ],
            'ria_perawat' => [
                'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-inap.anak.form_perawat',
            ],
        ];

        abort_unless(
            array_key_exists($formKey, $forms),
            404,
            'Form pengkajian tidak ditemukan.'
        );

        /*
         * Tambahkan query kunjungan/pasien Anda di sini bila partial
         * membutuhkan data lain selain kunjungan.
         */
        $list = [
            'kunjungan' => $kunjungan,
        ];

        return view(
            $forms[$formKey]['view'],
            compact('list', 'kunjungan', 'formKey')
        );
    }


}
