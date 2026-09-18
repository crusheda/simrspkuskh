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
use Illuminate\Validation\Rule;
use PHPJasper\PHPJasper;
use Carbon\Carbon;
use Auth, Storage;

//////////////////////////////////////////////////////////////////////////////////////////
// CONTROLLER FORM
//////////////////////////////////////////////////////////////////////////////////////////
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

use App\Http\Controllers\EMR\Form\BedahAnestesi\PengkajianPraBedahController;
use App\Http\Controllers\EMR\Form\BedahAnestesi\PengkajianPraAnestesiInduksiController;
use App\Http\Controllers\EMR\Form\BedahAnestesi\PengkajianLaporanAnestesiController;

use App\Http\Controllers\EMR\Form\Khusus\PengkajianKhususRemajaController;
use App\Http\Controllers\EMR\Form\Khusus\PengkajianKhususTerminalController;
use App\Http\Controllers\EMR\Form\Khusus\PengkajianKhususNyeriKronikController;
use App\Http\Controllers\EMR\Form\Khusus\PengkajianKhususSistemImunTergangguController;
use App\Http\Controllers\EMR\Form\Khusus\PengkajianKhususKecanduanObatAlkoholController;
use App\Http\Controllers\EMR\Form\Khusus\PengkajianKhususKorbanKekerasanController;
use App\Http\Controllers\EMR\Form\Khusus\PengkajianKhususPenyakitMenularController;
use App\Http\Controllers\EMR\Form\Khusus\PengkajianKhususLanjutanController;

use App\Http\Controllers\EMR\Form\Lain\LembarTransferPasienInternalController;

use App\Support\FinalisasiMap;

//////////////////////////////////////////////////////////////////////////////////////////
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

            $countCppt = DB::table('medicalrecord.cppt as cp')
                ->join('pendaftaran.kunjungan as pk', 'cp.KUNJUNGAN', '=', 'pk.NOMOR')
                ->where('cp.STATUS', '!=', 0)
                ->where('pk.NOPEN', $show->NOPEN)
                ->where('cp.KUNJUNGAN', $KUNJUNGAN)
                ->count();

            $data = [
                'show' => $show,
                'cpptCount' => $countCppt,
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
                // 'kjs.noSEP AS NOSEP',
                // 'kjs.tglSEP AS TGLSEP',
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
            // ->leftJoin('bpjs.kunjungan AS kjs', 'kjs.noSEP', '=', 'pj.NOMOR')
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

            'pengkajian-prabedah'                       => PengkajianPraBedahController::class,
            'pengkajian-praanestesiinduksi'             => PengkajianPraAnestesiInduksiController::class,
            'pengkajian-laporananestesi'                => PengkajianLaporanAnestesiController::class,

            'pengkajian-khusus-remaja'                  => PengkajianKhususRemajaController::class,
            'pengkajian-khusus-terminal'                => PengkajianKhususTerminalController::class,
            'pengkajian-khusus-nyerikronik'             => PengkajianKhususNyeriKronikController::class,
            'pengkajian-khusus-sistemimunterganggu'     => PengkajianKhususSistemImunTergangguController::class,
            'pengkajian-khusus-kecanduanobatalkohol'    => PengkajianKhususKecanduanObatAlkoholController::class,
            'pengkajian-khusus-korbankekerasan'         => PengkajianKhususKorbanKekerasanController::class,
            'pengkajian-khusus-penyakitmenular'         => PengkajianKhususPenyakitMenularController::class,
            'pengkajian-khusus-lanjutan'                => PengkajianKhususLanjutanController::class,

            'form-transfer-pasien'                      => LembarTransferPasienInternalController::class,
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
            // Gawat Darurat
                'gd_dokter' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.gawat-darurat.form_dokter',
                ],
                'gd_perawat' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.gawat-darurat.form_perawat',
                ],

            // Rawat Jalan
                // Dewasa
                'rjd_dokter' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.dewasa.form_dokter',
                ],
                'rjd_perawat' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.dewasa.form_perawat',
                ],

                // Anak
                'rja_dokter' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.anak.form_dokter',
                ],
                'rja_perawat' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.anak.form_perawat',
                ],

                // Geriatri
                'rjg_dokter' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.geriatri.form_dokter',
                ],
                'rjg_perawat' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.geriatri.form_perawat',
                ],

                // Jiwa
                'rjj_dokter' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.jiwa.form_dokter',
                ],
                'rjj_perawat' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.jiwa.form_perawat',
                ],

                // Obsgyn
                'rjo_dokter' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.obsgyn.form_dokter',
                ],
                'rjo_perawat' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.obsgyn.form_perawat',
                ],

            // Rawat Inap
                // Dewasa
                'rid_dokter' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-inap.dewasa.form_dokter',
                ],
                'rid_perawat' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-inap.dewasa.form_perawat',
                ],

                // Anak
                'ria_dokter' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-inap.anak.form_dokter',
                ],
                'ria_perawat' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-inap.anak.form_perawat',
                ],

                // Neonatus
                'rin_dokter' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-inap.neonatus.form_dokter',
                ],
                'rin_perawat' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-inap.neonatus.form_perawat',
                ],

                // Obsgyn
                'rio_dokter' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-inap.obsgyn.form_dokter',
                ],
                'rio_perawat' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.rawat-inap.obsgyn.form_perawat',
                ],

            // BEDAH & ANESTESI
                // LAPORAN ANESTESI
                'lap_anestesi' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.bedahanestesi.laporananestesi.laporananestesi',
                ],
                'lap_pasca_anestesi' => [
                    'view' => 'pages.v2.medicalrecord.detail.form.pengkajian.bedahanestesi.laporananestesi.laporanpascaanestesi',
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

        if (
            in_array(
                $formKey,
                [
                    'gd_dokter',
                    'gd_perawat',

                    'rjd_dokter',
                    'rjd_perawat',
                    'rja_dokter',
                    'rja_perawat',
                    'rjg_dokter',
                    'rjg_perawat',
                    'rjj_dokter',
                    'rjj_perawat',
                    'rjo_dokter',
                    'rjo_perawat',

                    'rio_dokter',
                    'rio_perawat',
                ],
                true
            )
        ) {
            $list = $this->getDataMaster($kunjungan);
        }

        $identification = $this->formInputIdentification($kunjungan);
        $init = [
            'input_date' => $identification['inputdate'],
        ];

        // Kirim status finalisasi bersama partial. Dengan demikian backdrop
        // dan tombol yang tepat sudah dirender pada paint pertama, tidak
        // perlu menunggu request AJAX kedua setelah form terlihat.
        $initialFinalisasi = null;

        try {
            $initialFinalisasi = $this->getStatusFinalisasi(
                $kunjungan,
                $formKey
            );
        } catch (\InvalidArgumentException $e) {
            // Form tanpa konfigurasi finalisasi tetap dapat dimuat seperti biasa.
        }

        return view(
            $forms[$formKey]['view'],
            compact(
                'init',
                'list',
                'kunjungan',
                'formKey',
                'initialFinalisasi'
            )
        );
    }

    private function getFinalisasiFormSub(string $formKey): array
    {
        return FinalisasiMap::get($formKey);
    }

    private function getStatusFinalisasi(
        string $kunjungan,
        string $formKey
    ): array {
        $mapping = $this->getFinalisasiFormSub($formKey);

        $data = DB::table('simrspku_pengkajian.finalisasi as fin')
            ->leftJoin(
                'aplikasi.pengguna as usr1',
                'usr1.ID',
                '=',
                'fin.USER_CREATED'
            )
            ->leftJoin(
                'aplikasi.pengguna as usr2',
                'usr2.ID',
                '=',
                'fin.USER_UPDATED'
            )
            ->select(
                'fin.*',
                DB::raw(
                    'master.getNamaLengkapPegawai(usr1.NIP) AS NAMAUSER_CREATED'
                ),
                DB::raw(
                    'master.getNamaLengkapPegawai(usr2.NIP) AS NAMAUSER_UPDATED'
                ),
            )
            ->where('fin.KUNJUNGAN', $kunjungan)
            ->where('fin.FORM', $mapping['form'])
            ->where('fin.SUB', $mapping['sub'])
            ->first();

        return [
            'status' => $data ? (int) $data->STATUS : 1,
            'is_final' => $data && (int) $data->STATUS === 2,
            'data' => $data,
        ];
    }

    public function statusFinalisasi(
        Request $request,
        string $kunjungan
    ) {
        $formKeys = collect($request->input('formKeys', []))
            ->filter(fn ($formKey) => is_string($formKey))
            ->unique()
            ->values();

        $result = [];

        foreach ($formKeys as $formKey) {
            try {
                $status = $this->getStatusFinalisasi(
                    $kunjungan,
                    $formKey
                );

                $result[$formKey] = [
                    'status' => $status['status'],
                    'is_final' => $status['is_final'],
                ];
            } catch (\InvalidArgumentException $e) {
                // Abaikan form key yang tidak ada di FinalisasiMap.
            }
        }

        return response()
            ->json([
                'status' => true,
                'data' => $result,
            ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function showCPPT($PKUNJUNGAN)
    {
        $getInit = DB::table('pendaftaran.kunjungan AS pk')
            ->leftJoin('pendaftaran.pendaftaran AS pp', 'pp.NOMOR', '=', 'pk.NOPEN')
            ->leftJoin('master.pasien AS ps', 'ps.NORM', '=', 'pp.NORM')
            ->select(
                'pk.NOPEN',
                'ps.NORM',
                DB::raw('master.getNamaLengkap(ps.NORM) AS NAMAPASIEN')
            )
            ->where('pk.NOMOR', $PKUNJUNGAN)
            ->first();

        if (!$getInit) {
            return response()->json([
                'message' => 'Data kunjungan tidak ditemukan.'
            ], 404);
        }

        $PNOPEN = $getInit->NOPEN;

        $show = DB::table('medicalrecord.cppt as cp')
            ->leftJoin('master.referensi as ref', function ($join) {
                $join->on('cp.JENIS', '=', 'ref.ID')
                    ->where('ref.JENIS', '=', 32);
            })
            ->leftJoin('medicalrecord.push_cppt as pcp', function ($join) {
                $join->on('pcp.ID_CPPT', '=', 'cp.ID')
                    ->where('pcp.STATUS', '=', 1);
            })
            ->leftJoin('master.pegawai as p', 'cp.TENAGA_MEDIS', '=', 'p.ID')
            ->leftJoin('master.dokter as d', 'cp.TENAGA_MEDIS', '=', 'd.ID')
            ->leftJoin('master.dokter as dc', 'cp.DOKTER_TBAK_OR_SBAR', '=', 'dc.ID')
            ->leftJoin('master.perawat as pr', 'cp.TENAGA_MEDIS', '=', 'pr.ID')
            ->leftJoin('medicalrecord.verifikasi_cppt as vcp', 'cp.VERIFIKASI', '=', 'vcp.ID')
            ->leftJoin('aplikasi.pengguna as vr', 'vcp.OLEH', '=', 'vr.ID')
            ->leftJoin('pendaftaran.kunjungan as pk', 'cp.KUNJUNGAN', '=', 'pk.NOMOR')
            ->where('cp.STATUS', '!=', 0)
            ->where('pk.NOPEN', $PNOPEN)
            ->where('cp.KUNJUNGAN', $PKUNJUNGAN)
            ->select([
                'cp.ID',
                'pcp.ID_CPPT AS CPPT_SIRMED',
                DB::raw("
                    CONCAT(
                        DATE_FORMAT(cp.TANGGAL, '%d-%m-%Y'),
                        ' ',
                        TIME(cp.TANGGAL)
                    ) AS TANGGAL
                "),

                DB::raw("'' AS CATATAN"),

                DB::raw("
                    master.getReplaceFont(cp.INSTRUKSI) AS INSTRUKSI
                "),

                DB::raw("
                    IF(
                        ref.REF_ID = '4',
                        master.getNamaLengkapPegawai(d.NIP),
                        ''
                    ) AS DOKTER
                "),

                DB::raw("
                    IF(
                        ref.REF_ID = '6',
                        master.getNamaLengkapPegawai(pr.NIP),
                        IF(
                            ref.REF_ID NOT IN ('6', '4'),
                            master.getNamaLengkapPegawai(p.NIP),
                            ''
                        )
                    ) AS PERAWAT
                "),

                'ref.DESKRIPSI as JNSPPA',

                DB::raw("
                    CONCAT(
                        IF(
                            ref.REF_ID = '4',
                            master.getNamaLengkapPegawai(d.NIP),
                            IF(
                                ref.REF_ID = '6',
                                master.getNamaLengkapPegawai(pr.NIP),
                                IF(
                                    ref.REF_ID NOT IN ('6', '4'),
                                    master.getNamaLengkapPegawai(p.NIP),
                                    ''
                                )
                            )
                        ),
                        ' ',
                        IF(
                            cp.STATUS_SBAR = 1,
                            '( SBAR )',
                            IF(
                                cp.STATUS_TBAK = 1,
                                '( TBAK )',
                                ''
                            )
                        )
                    ) AS PPA
                "),

                DB::raw("
                    CONCAT(
                        DATE_FORMAT(vcp.TANGGAL, '%d-%m-%Y'),
                        ' ',
                        TIME(vcp.TANGGAL)
                    ) AS TGLVERIFIKASI
                "),

                DB::raw("
                    master.getNamaLengkapPegawai(vr.NIP) AS VERIFIKATOR
                "),

                DB::raw("
                    CONCAT(
                        master.getNamaLengkapPegawai(vr.NIP),
                        ' ',
                        DATE_FORMAT(vcp.TANGGAL, '%d-%m-%Y'),
                        ' ',
                        TIME(vcp.TANGGAL)
                    ) AS VERIFIKASI
                "),

                DB::raw("
                    IF(
                        cp.STATUS_SBAR = 1,
                        'SBAR',
                        IF(
                            cp.STATUS_TBAK = 1,
                            'TBAK',
                            ''
                        )
                    ) AS TBAK_SBAR
                "),

                // Field internal untuk membangun CATATAN.
                'cp.SUBYEKTIF as _CPPT_SUBYEKTIF',
                'cp.OBYEKTIF as _CPPT_OBYEKTIF',
                'cp.ASSESMENT as _CPPT_ASSESMENT',
                'cp.PLANNING as _CPPT_PLANNING',
                'cp.TULIS as _CPPT_TULIS',
                'cp.BACA as _CPPT_BACA',
                'cp.KONFIRMASI as _CPPT_KONFIRMASI',
                'cp.STATUS_SBAR as _CPPT_STATUS_SBAR',
                'cp.STATUS_TBAK as _CPPT_STATUS_TBAK',

                DB::raw("
                    ref.CONFIG->>'$.dietisen' AS _CPPT_DIETISEN
                "),

                DB::raw("
                    IFNULL(
                        master.getNamaLengkapPegawai(dc.NIP),
                        ''
                    ) AS _CPPT_DOKTER_TBAK
                "),
            ])
            ->orderBy('cp.TANGGAL', 'DESC')
            ->get();

        $show->transform(function ($item) {

            $subyektif = $this->normalizeCpptHtml($item->_CPPT_SUBYEKTIF);
            $obyektif = $this->normalizeCpptHtml($item->_CPPT_OBYEKTIF);
            $assesment = $this->normalizeCpptHtml($item->_CPPT_ASSESMENT);
            $planning = $this->normalizeCpptHtml($item->_CPPT_PLANNING);
            $tulis = $this->normalizeCpptHtml($item->_CPPT_TULIS);
            $dokterTbak = $item->_CPPT_DOKTER_TBAK ?? '';

            if ($item->_CPPT_DIETISEN === 'true') {

                $item->CATATAN =
                    '<b>A/ :</b> ' . $subyektif .
                    '<br><br>' .
                    '<b>D/ :</b> ' . $obyektif .
                    '<br><br>' .
                    '<b>I/ :</b> ' . $assesment .
                    '<br><br>' .
                    '<b>ME/ :</b> ' . $planning .
                    '<br><br>';

            } elseif ((int) $item->_CPPT_STATUS_SBAR === 1) {

                $item->CATATAN =
                    '<b>S/ :</b> ' . $subyektif .
                    '<br><br>' .
                    '<b>B/ :</b> ' . $obyektif .
                    '<br><br>' .
                    '<b>A/ :</b> ' . $assesment .
                    '<br><br>' .
                    '<b>R/ :</b> ' . $planning .
                    '<br><br>' .
                    '<b>Dokter/ :</b> ' . e($dokterTbak);

            } elseif ((int) $item->_CPPT_STATUS_TBAK === 1) {

                $baca = ((int) $item->_CPPT_BACA === 0)
                    ? 'Belum Baca'
                    : 'Sudah Baca';

                $konfirmasi = ((int) $item->_CPPT_KONFIRMASI === 0)
                    ? 'Belum Konfirmasi'
                    : 'Sudah Konfirmasi';

                $item->CATATAN =
                    '<b>Tulis/ :</b> ' . $tulis .
                    '<br><br>' .
                    '<b>Baca/ :</b> ' . e($baca) .
                    ' ' .
                    '<b>Konfirmasi/ :</b> ' . e($konfirmasi) .
                    ' ' .
                    '<b>Dokter/ :</b> ' . e($dokterTbak);

            } else {

                $item->CATATAN =
                    '<b>S/ :</b> ' . $subyektif .
                    '<br><br>' .
                    '<b>O/ :</b> ' . $obyektif .
                    '<br><br>' .
                    '<b>A/ :</b> ' . $assesment .
                    '<br><br>' .
                    '<b>P/ :</b> ' . $planning .
                    '<br><br>';
            }

            // Hapus field internal sebelum response.
            unset(
                $item->_CPPT_SUBYEKTIF,
                $item->_CPPT_OBYEKTIF,
                $item->_CPPT_ASSESMENT,
                $item->_CPPT_PLANNING,
                $item->_CPPT_TULIS,
                $item->_CPPT_BACA,
                $item->_CPPT_KONFIRMASI,
                $item->_CPPT_STATUS_SBAR,
                $item->_CPPT_STATUS_TBAK,
                $item->_CPPT_DIETISEN,
                $item->_CPPT_DOKTER_TBAK
            );

            return $item;
        });

        $ppa = DB::table('aplikasi.pengguna AS pe')
            ->select(
                'pe.ID',
                'pe.NIP',
                DB::raw('master.getNamaLengkapPegawai(pe.NIP) AS NAMA')
            )
            ->get();

        $dokter = DB::table('aplikasi.pengguna AS pe')
            ->leftJoin('master.pegawai AS peg','peg.NIP','=','pe.NIP')
            ->select(
                'pe.ID',
                'peg.NIP',
                DB::raw('master.getNamaLengkapPegawai(peg.NIP) AS NAMA')
            )
            ->where('peg.PROFESI','=','4')
            ->get();

        $ppaLogin = DB::table('aplikasi.pengguna AS pe')
            ->select(
                'pe.ID',
                'pe.NIP',
                DB::raw('master.getNamaLengkapPegawai(pe.NIP) AS NAMA')
            )
            ->where('pe.ID', auth()->id())
            ->first();

        $data = [
            'norm' => $getInit->NORM,
            'ppa' => $ppa,
            'dokter' => $dokter,
            'ppa_login' => $ppaLogin,
            'namapasien' => $getInit->NAMAPASIEN,
            'count' => $show->count(),
            'show' => $show,
        ];

        return response()->json($data, 200);
    }

    public function simpanCPPT(Request $request, $PKUNJUNGAN)
    {
        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'tanggal' => 'required|date',
            'jam' => 'required',
            'ppa_id' => 'required|integer',

            'mode' => 'required|in:BIASA,SBAR,TBAK',

            'dokter_id' => 'nullable|integer',

            's' => 'nullable|string',
            'o' => 'nullable|string',
            'a' => 'nullable|string',
            'p' => 'nullable|string',
            'i' => 'nullable|string',

            'tulis' => 'nullable|string',
            'baca' => 'nullable|integer|in:0,1',
            'konfirmasi' => 'nullable|integer|in:0,1',
        ]);


        /*
        |--------------------------------------------------------------------------
        | CEK KUNJUNGAN
        |--------------------------------------------------------------------------
        */

        $kunjungan = DB::table('pendaftaran.kunjungan AS pk')
            ->where('pk.NOMOR', $PKUNJUNGAN)
            ->select(
                'pk.NOMOR',
                'pk.NOPEN'
            )
            ->first();

        if (!$kunjungan) {
            return response()->json([
                'message' => 'Data kunjungan tidak ditemukan.'
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | CARI TENAGA MEDIS BERDASARKAN NIP
        |--------------------------------------------------------------------------
        */

        $tenagaMedis = DB::table('aplikasi.pengguna AS pe')
            ->leftJoin('master.pegawai AS peg','peg.NIP','=','pe.NIP')
            ->where('pe.ID', $request->dokter_id)
            ->select(
                'peg.ID',
                'peg.NIP',
                'peg.PROFESI'
            )
            ->first();

        if (!$tenagaMedis) {
            return response()->json([
                'message' => 'Data tenaga medis untuk PPA tersebut tidak ditemukan.'
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | DATA DASAR CPPT
        |--------------------------------------------------------------------------
        */

        $data = [
            'KUNJUNGAN' => $PKUNJUNGAN,

            'TANGGAL' => $request->tanggal . ' ' . $request->jam,

            'SUBYEKTIF' => '',
            'OBYEKTIF' => '',
            'ASSESMENT' => '',
            'PLANNING' => '',
            'INSTRUKSI' => '',

            /*
            | ID pengguna = ID tenaga medis
            */
            'TENAGA_MEDIS' => $request->ppa_id,

            /*
            | Sementara
            | Nanti kita isi sesuai jenis PPA
            */
            'JENIS' => $tenagaMedis->PROFESI,

            'RENCANA_PULANG' => 0,

            'TANGGAL_RENCANA_PULANG' => date('Y-m-d'),

            'SUB_DEVISI' => 0,

            /*
            | User yang membuat CPPT
            */
            'OLEH' => auth()->id(),

            'VERIFIKASI' => 0,

            'STATUS' => 1,

            'TULIS' => '',

            'STATUS_TBAK' => 0,

            'STATUS_SBAR' => 0,

            'BACA' => 0,

            'KONFIRMASI' => 0,

            'ADIME' => 0,

            'DOKTER_TBAK_OR_SBAR' => 0,
        ];


        /*
        |--------------------------------------------------------------------------
        | CPPT BIASA
        |--------------------------------------------------------------------------
        */

        if ($request->mode === 'BIASA') {

            $data['SUBYEKTIF'] = $request->s ?? '';
            $data['OBYEKTIF'] = $request->o ?? '';
            $data['ASSESMENT'] = $request->a ?? '';
            $data['PLANNING'] = $request->p ?? '';
            $data['INSTRUKSI'] = $request->i ?? '';

            $data['STATUS_TBAK_SBAR'] = 0;
            $data['STATUS_SBAR'] = 0;
            $data['STATUS_TBAK'] = 0;
        }


        /*
        |--------------------------------------------------------------------------
        | CPPT SBAR
        |--------------------------------------------------------------------------
        */

        elseif ($request->mode === 'SBAR') {

            /*
            | Situation -> SUBYEKTIF
            | Background -> OBYEKTIF
            | Assessment -> ASSESMENT
            | Recommendation -> PLANNING
            */

            $data['SUBYEKTIF'] = $request->s ?? '';
            $data['OBYEKTIF'] = $request->o ?? '';
            $data['ASSESMENT'] = $request->a ?? '';
            $data['PLANNING'] = $request->p ?? '';

            $data['INSTRUKSI'] = '';
            $data['STATUS_TBAK_SBAR'] = 1;
            $data['STATUS_SBAR'] = 1;
            $data['STATUS_TBAK'] = 0;

            /*
            | Dokter SBAR
            */

            $data['DOKTER_TBAK_OR_SBAR'] =
                $tenagaMedis->ID ?? 0;
        }


        /*
        |--------------------------------------------------------------------------
        | CPPT TBAK
        |--------------------------------------------------------------------------
        */

        elseif ($request->mode === 'TBAK') {

            $data['SUBYEKTIF'] = '';
            $data['OBYEKTIF'] = '';
            $data['ASSESMENT'] = '';
            $data['PLANNING'] = '';
            $data['INSTRUKSI'] = '';

            $data['TULIS'] = $request->tulis ?? '';

            $data['BACA'] =
                (int) ($request->baca ?? 0);

            $data['KONFIRMASI'] =
                (int) ($request->konfirmasi ?? 0);
            $data['STATUS_TBAK_SBAR'] = 1;
            $data['STATUS_SBAR'] = 0;
            $data['STATUS_TBAK'] = 1;

            /*
            | Dokter TBAK
            */

            $data['DOKTER_TBAK_OR_SBAR'] =
                $tenagaMedis->ID ?? 0;
        }


        /*
        |--------------------------------------------------------------------------
        | SIMPAN
        |--------------------------------------------------------------------------
        */

        try {

            DB::table('medicalrecord.cppt')
                ->insert($data);

            return response()->json([
                'status' => true,
                'message' => 'CPPT berhasil ditambahkan.'
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'CPPT gagal disimpan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function detailCPPT($kunjungan,$id)
    {
        $cppt = DB::table('medicalrecord.cppt as cp')
            ->leftJoin('master.referensi as ref', function ($join) {
                $join->on('cp.JENIS', '=', 'ref.ID')
                    ->where('ref.JENIS', '=', 32);
            })
            ->leftJoin('master.pegawai as p', 'cp.TENAGA_MEDIS', '=', 'p.ID')
            ->leftJoin('master.dokter as d', 'cp.TENAGA_MEDIS', '=', 'd.ID')
            ->leftJoin('master.perawat as pr', 'cp.TENAGA_MEDIS', '=', 'pr.ID')
            ->leftJoin(
                'master.dokter as dokter_tbak',
                'cp.DOKTER_TBAK_OR_SBAR',
                '=',
                'dokter_tbak.ID'
            )
            ->leftJoin(
                'aplikasi.pengguna as pengguna_dokter',
                'dokter_tbak.NIP',
                '=',
                'pengguna_dokter.NIP'
            )
            ->leftJoin('pendaftaran.kunjungan as pk', 'cp.KUNJUNGAN', '=', 'pk.NOMOR')
            ->leftJoin('pendaftaran.pendaftaran as pp', 'pk.NOPEN', '=', 'pp.NOMOR')
            ->leftJoin('master.pasien as ps', 'pp.NORM', '=', 'ps.NORM')
            ->where('cp.ID', $id)
            ->where('cp.KUNJUNGAN', $kunjungan)
            ->where('cp.STATUS', '!=', 0)
            ->select([
                'cp.ID',
                'cp.KUNJUNGAN',

                DB::raw("DATE_FORMAT(cp.TANGGAL, '%Y-%m-%d') as TANGGAL"),
                DB::raw("DATE_FORMAT(cp.TANGGAL, '%H:%i') as JAM"),

                'cp.SUBYEKTIF',
                'cp.OBYEKTIF',
                'cp.ASSESMENT',
                'cp.PLANNING',
                'cp.INSTRUKSI',

                'cp.TULIS',
                'cp.BACA',
                'cp.KONFIRMASI',

                'cp.STATUS_TBAK',
                'cp.STATUS_SBAR',

                'cp.TENAGA_MEDIS as PPA_ID',
                'cp.DOKTER_TBAK_OR_SBAR as DOKTER_MASTER_ID',
                'pengguna_dokter.ID as DOKTER_ID',

                DB::raw("
                    IF(
                        cp.STATUS_SBAR = 1,
                        'SBAR',
                        IF(cp.STATUS_TBAK = 1, 'TBAK', '')
                    ) as TBAK_SBAR
                "),

                DB::raw("
                    IFNULL(
                        master.getNamaLengkapPegawai(dokter_tbak.NIP),
                        ''
                    ) as DOKTER
                "),

                DB::raw("
                    IF(
                        ref.REF_ID = '4',
                        master.getNamaLengkapPegawai(d.NIP),
                        IF(
                            ref.REF_ID = '6',
                            master.getNamaLengkapPegawai(pr.NIP),
                            master.getNamaLengkapPegawai(p.NIP)
                        )
                    ) as PPA
                "),

                'ps.NORM',
                DB::raw("
                    master.getNamaLengkap(ps.NORM) as NAMAPASIEN
                "),
            ])
            ->first();

        if (!$cppt) {
            return response()->json([
                'message' => 'Data CPPT tidak ditemukan.'
            ], 404);
        }

        $cppt->COUNT_CPPT = DB::table('medicalrecord.cppt')
                                ->where('KUNJUNGAN', $cppt->KUNJUNGAN)
                                ->where('STATUS', '!=', 0)
                                ->count();

        // Hindari HTML database masuk mentah ke textarea.
        $cppt->SUBYEKTIF = $this->cpptHtmlKeText($cppt->SUBYEKTIF);
        $cppt->OBYEKTIF = $this->cpptHtmlKeText($cppt->OBYEKTIF);
        $cppt->ASSESMENT = $this->cpptHtmlKeText($cppt->ASSESMENT);
        $cppt->PLANNING = $this->cpptHtmlKeText($cppt->PLANNING);
        $cppt->INSTRUKSI = $this->cpptHtmlKeText($cppt->INSTRUKSI);
        $cppt->TULIS = $this->cpptHtmlKeText($cppt->TULIS);

        return response()->json([
            'data' => $cppt
        ]);
    }

    public function updateCPPT(Request $request, $kunjungan,$id)
    {
        $validated = $request->validate([
            // 'kunjungan' => ['required', 'string'],
            'tanggal' => ['required', 'date_format:Y-m-d'],
            'jam' => ['required', 'date_format:H:i'],
            'tbak_sbar' => ['nullable', Rule::in(['', 'SBAR', 'TBAK'])],
            'instruksi' => ['nullable', 'string'],

            // CPPT Biasa
            's' => ['nullable', 'string'],
            'o' => ['nullable', 'string'],
            'a' => ['nullable', 'string'],
            'p' => ['nullable', 'string'],

            // SBAR
            'situation' => ['nullable', 'string'],
            'background' => ['nullable', 'string'],
            'assessment' => ['nullable', 'string'],
            'recommendation' => ['nullable', 'string'],

            // TBAK
            'tulis' => ['nullable', 'string'],
            'baca' => ['nullable', 'boolean'],
            'konfirmasi' => ['nullable', 'boolean'],

            // ID aplikasi.pengguna dari autocomplete dokter
            'dokter_id' => ['nullable', 'integer'],
        ]);

        $cppt = DB::table('medicalrecord.cppt')
            ->where('ID', $id)
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', '!=', 0)
            ->first();

        if (!$cppt) {
            return response()->json([
                'message' => 'Data CPPT tidak ditemukan atau bukan milik kunjungan ini.'
            ], 404);
        }

        /*
        | Aktifkan bila hanya pembuat CPPT boleh mengedit.
        |
        | if ((int) $cppt->OLEH !== (int) auth()->id()) {
        |     return response()->json([
        |         'message' => 'Anda tidak berhak mengubah CPPT ini.'
        |     ], 403);
        | }
        */

        /*
        | Aktifkan bila CPPT yang sudah diverifikasi tidak boleh diedit.
        |
        | if (!empty($cppt->VERIFIKASI) && (int) $cppt->VERIFIKASI > 0) {
        |     return response()->json([
        |         'message' => 'CPPT yang sudah diverifikasi tidak dapat diubah.'
        |     ], 422);
        | }
        */

        $modeLama = ((int) $cppt->STATUS_SBAR === 1)
            ? 'SBAR'
            : (((int) $cppt->STATUS_TBAK === 1) ? 'TBAK' : '');

        $modeRequest = strtoupper(trim($validated['tbak_sbar'] ?? ''));

        /*
        | Modal edit tidak mengizinkan jenis CPPT berubah.
        | Ini mencegah record SBAR berubah menjadi TBAK secara tidak sengaja.
        */
        if ($modeLama !== $modeRequest) {
            return response()->json([
                'message' => 'Jenis CPPT tidak boleh diubah saat proses edit.'
            ], 422);
        }

        $tanggal = $validated['tanggal'] . ' ' . $validated['jam'] . ':00';

        $update = [
            'TANGGAL' => $tanggal,
            'INSTRUKSI' => $this->cpptTextKeHtml($validated['instruksi'] ?? ''),
        ];

        /*
        |--------------------------------------------------------------------------
        | CPPT Biasa
        |--------------------------------------------------------------------------
        */
        if ($modeRequest === '') {
            $update['SUBYEKTIF'] = $this->cpptTextKeHtml($validated['s'] ?? '');
            $update['OBYEKTIF'] = $this->cpptTextKeHtml($validated['o'] ?? '');
            $update['ASSESMENT'] = $this->cpptTextKeHtml($validated['a'] ?? '');
            $update['PLANNING'] = $this->cpptTextKeHtml($validated['p'] ?? '');

            $update['TULIS'] = '';
            $update['BACA'] = 0;
            $update['KONFIRMASI'] = 0;
            $update['DOKTER_TBAK_OR_SBAR'] = 0;
            $update['STATUS_TBAK'] = 0;
            $update['STATUS_SBAR'] = 0;
        }

        /*
        |--------------------------------------------------------------------------
        | SBAR
        |--------------------------------------------------------------------------
        */
        if ($modeRequest === 'SBAR') {
            $update['SUBYEKTIF'] = $this->cpptTextKeHtml($validated['situation'] ?? '');
            $update['OBYEKTIF'] = $this->cpptTextKeHtml($validated['background'] ?? '');
            $update['ASSESMENT'] = $this->cpptTextKeHtml($validated['assessment'] ?? '');
            $update['PLANNING'] = $this->cpptTextKeHtml($validated['recommendation'] ?? '');

            $update['TULIS'] = '';
            $update['BACA'] = 0;
            $update['KONFIRMASI'] = 0;
            $update['STATUS_TBAK'] = 0;
            $update['STATUS_SBAR'] = 1;
            $update['DOKTER_TBAK_OR_SBAR'] = $this->dokterCpptDariPengguna(
                $validated['dokter_id'] ?? null
            );
        }

        /*
        |--------------------------------------------------------------------------
        | TBAK
        |--------------------------------------------------------------------------
        */
        if ($modeRequest === 'TBAK') {
            /*
            | Kolom SOAP tidak dikosongkan: data lama tetap aman.
            | Yang diubah hanya elemen TBAK.
            */
            $update['TULIS'] = $this->cpptTextKeHtml($validated['tulis'] ?? '');
            $update['BACA'] = !empty($validated['baca']) ? 1 : 0;
            $update['KONFIRMASI'] = !empty($validated['konfirmasi']) ? 1 : 0;
            $update['STATUS_TBAK'] = 1;
            $update['STATUS_SBAR'] = 0;
            $update['DOKTER_TBAK_OR_SBAR'] = $this->dokterCpptDariPengguna(
                $validated['dokter_id'] ?? null
            );
        }

        DB::transaction(function () use ($id, $update) {
            DB::table('medicalrecord.cppt')
                ->where('ID', $id)
                ->update($update);
        });

        return response()->json([
            'message' => 'CPPT berhasil diperbarui.'
        ]);
    }

    public function hapusCPPT(Request $request, $id)
    {
        try {

            /*
            |--------------------------------------------------------------------------
            | CARI DATA CPPT
            |--------------------------------------------------------------------------
            */

            $cppt = DB::table('medicalrecord.cppt')
                ->where('ID', $id)
                ->first();

            if (!$cppt) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data CPPT tidak ditemukan.'
                ], 404);
            }


            /*
            |--------------------------------------------------------------------------
            | HAPUS CPPT
            |--------------------------------------------------------------------------
            */

            // DB::table('medicalrecord.cppt')
            //     ->where('ID', $id)
            //     ->delete();
            DB::table('medicalrecord.cppt')
                ->where('ID', $id)
                ->update([
                    'STATUS' => 0
                ]);

            /*
            |--------------------------------------------------------------------------
            | RESPONSE
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'status' => true,
                'message' => 'CPPT berhasil dihapus.'
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => false,
                'message' => 'CPPT gagal dihapus.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ubah HTML yang disimpan di database menjadi teks aman untuk textarea.
     */
    private function cpptHtmlKeText(?string $value): string
    {
        $value = (string) $value;

        if ($value === '') {
            return '';
        }

        // Ubah ordered list menjadi "1. ...", "2. ...", dst.
        $value = preg_replace_callback(
            '/<ol\b[^>]*>(.*?)<\/ol>/is',
            function ($match) {
                preg_match_all(
                    '/<li\b[^>]*>(.*?)<\/li>/is',
                    $match[1],
                    $items
                );

                $hasil = [];

                foreach ($items[1] as $index => $item) {
                    $isi = $this->cpptHtmlKeText($item);

                    if ($isi !== '') {
                        $hasil[] = ($index + 1) . '. ' . $isi;
                    }
                }

                return "\n" . implode("\n", $hasil) . "\n";
            },
            $value
        );

        // Ubah unordered list menjadi "- ..."
        $value = preg_replace_callback(
            '/<ul\b[^>]*>(.*?)<\/ul>/is',
            function ($match) {
                preg_match_all(
                    '/<li\b[^>]*>(.*?)<\/li>/is',
                    $match[1],
                    $items
                );

                $hasil = [];

                foreach ($items[1] as $item) {
                    $isi = $this->cpptHtmlKeText($item);

                    if ($isi !== '') {
                        $hasil[] = '- ' . $isi;
                    }
                }

                return "\n" . implode("\n", $hasil) . "\n";
            },
            $value
        );

        // Tag yang secara visual berarti ganti baris.
        $value = preg_replace('/<br\s*\/?>/i', "\n", $value);
        // $value = preg_replace('/<\/div\s*>/i', "\n", $value);
        // $value = preg_replace('/<\/p\s*>/i', "\n", $value);

        // Baik tag pembuka maupun penutup div/p dianggap sebagai ganti baris.
        $value = preg_replace('/<\/?(?:div|p)\b[^>]*>/i', "\n", $value);

        $value = preg_replace('/<\/h[1-6]\s*>/i', "\n", $value);
        $value = preg_replace('/<\/tr\s*>/i', "\n", $value);

        // Antisipasi tag li yang tidak berada dalam ol/ul sempurna.
        $value = preg_replace('/<li\b[^>]*>/i', "\n- ", $value);
        $value = preg_replace('/<\/li\s*>/i', "\n", $value);

        // Hilangkan tag HTML tersisa, tetapi pertahankan teks.
        $value = strip_tags($value);

        // &nbsp; dan entity lain menjadi karakter normal.
        $value = html_entity_decode(
            $value,
            ENT_QUOTES | ENT_HTML5,
            'UTF-8'
        );

        $value = str_replace("\xc2\xa0", ' ', $value);
        $value = preg_replace("/[ \t]+\n/", "\n", $value);
        $value = preg_replace("/\n[ \t]+/", "\n", $value);
        $value = preg_replace("/\n{3,}/", "\n\n", $value);

        return trim($value);
    }

    /**
     * Simpan teks textarea sebagai HTML aman untuk format lama tabel CPPT.
     */
    private function cpptTextKeHtml(?string $value): string
    {
        $value = trim((string) $value);

        return nl2br(e($value), false);
    }

    /**
     * Konversi ID aplikasi.pengguna menjadi ID master.dokter.
     *
     * Front-end autocomplete Anda mengirim ID dari aplikasi.pengguna,
     * sementara medicalrecord.cppt.DOKTER_TBAK_OR_SBAR memakai master.dokter.ID.
     */
    private function dokterCpptDariPengguna(?int $penggunaId): int
    {
        if (!$penggunaId) {
            return 0;
        }

        $dokterId = DB::table('aplikasi.pengguna as pe')
            ->join('master.dokter as d', 'd.NIP', '=', 'pe.NIP')
            ->where('pe.ID', $penggunaId)
            ->value('d.ID');

        return (int) ($dokterId ?? 0);
    }

    /**
     * Normalisasi HTML CPPT agar aman ditampilkan di front-end.
     * Hanya tag tertentu yang diperbolehkan, sisanya dihapus.
     * Tag yang diperbolehkan: <b>, <strong>, <i>, <em>, <u>, <br>, <ul>, <ol>, <li>, <sub>, <sup>.
     * Semua tag lain akan dihapus, termasuk atribut HTML.
     * Line break akan dinormalisasi menjadi <br>.
     */
    private function normalizeCpptHtml(?string $html): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        // Normalisasi line break dan whitespace.
        $html = str_replace(
            [
                "\r\n",
                "\r",
                "\n",
                '&nbsp;',
            ],
            [
                "\n",
                "\n",
                "\n",
                ' ',
            ],
            $html
        );

        // Newline dari textarea harus menjadi <br>. Jika dibiarkan sebagai
        // whitespace biasa di dalam HTML, browser akan menggabungkannya
        // menjadi satu spasi saat riwayat CPPT ditampilkan.
        $html = str_replace("\n", '<br>', $html);

        // Block element dijadikan line break.
        $html = preg_replace('/<div\b[^>]*>/i', '<br>', $html);
        $html = preg_replace('/<\/div\s*>/i', '', $html);
        $html = preg_replace('/<p\b[^>]*>/i', '<br>', $html);
        $html = preg_replace('/<\/p\s*>/i', '', $html);
        $html = preg_replace('/<br\s*\/?>/i', '<br>', $html);

        // Pertahankan isi font/span tanpa formatting-nya.
        $html = preg_replace('/<font\b[^>]*>/i', '', $html);
        $html = preg_replace('/<\/font\s*>/i', '', $html);
        $html = preg_replace('/<span\b[^>]*>/i', '', $html);
        $html = preg_replace('/<\/span\s*>/i', '', $html);

        $allowedTags = [
            'b',
            'strong',
            'i',
            'em',
            'u',
            'br',
            'ul',
            'ol',
            'li',
            'sub',
            'sup',
        ];

        $dom = new \DOMDocument('1.0', 'UTF-8');

        libxml_use_internal_errors(true);

        $wrappedHtml =
            '<!DOCTYPE html>' .
            '<html>' .
            '<head><meta charset="UTF-8"></head>' .
            '<body>' .
            $html .
            '</body>' .
            '</html>';

        $loaded = $dom->loadHTML(
            '<?xml encoding="UTF-8" ?>' . $wrappedHtml,
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        if (!$loaded) {
            libxml_clear_errors();

            return e(strip_tags($html));
        }

        $sanitizeNode = function (\DOMNode $node) use (
            &$sanitizeNode,
            $allowedTags
        ) {
            if ($node->nodeType === XML_TEXT_NODE) {
                return;
            }

            if ($node->nodeType === XML_COMMENT_NODE) {
                $node->parentNode?->removeChild($node);

                return;
            }

            if ($node->nodeType === XML_ELEMENT_NODE) {

                $tagName = strtolower($node->nodeName);

                if (!in_array($tagName, $allowedTags, true)) {

                    $parent = $node->parentNode;

                    if ($parent) {
                        while ($node->firstChild) {
                            $parent->insertBefore(
                                $node->firstChild,
                                $node
                            );
                        }

                        $parent->removeChild($node);
                    }

                    return;
                }

                // CPPT tidak membutuhkan attribute HTML.
                while ($node->attributes->length > 0) {
                    $node->removeAttributeNode(
                        $node->attributes->item(0)
                    );
                }
            }

            $children = [];

            foreach ($node->childNodes as $child) {
                $children[] = $child;
            }

            foreach ($children as $child) {
                $sanitizeNode($child);
            }
        };

        $body = $dom->getElementsByTagName('body')->item(0);

        if (!$body) {
            libxml_clear_errors();

            return e(strip_tags($html));
        }

        $children = [];

        foreach ($body->childNodes as $child) {
            $children[] = $child;
        }

        foreach ($children as $child) {
            $sanitizeNode($child);
        }

        $result = '';

        foreach ($body->childNodes as $child) {
            $result .= $dom->saveHTML($child);
        }

        libxml_clear_errors();

        // Rapikan hasil akhir.
        $result = str_replace(
            [
                '&nbsp;',
                "\xc2\xa0",
            ],
            ' ',
            $result
        );

        $result = preg_replace(
            '/(?:\s*<br>\s*){3,}/i',
            '<br><br>',
            $result
        );

        $result = preg_replace(
            '/^(?:\s*<br>\s*)+/i',
            '',
            $result
        );

        $result = preg_replace(
            '/(?:\s*<br>\s*)+$/i',
            '',
            $result
        );

        return trim($result);
    }

    private function getDataMaster($kunjungan)
    {
        $tingkat_kesadaran = DB::table('master.referensi')
            ->select('ID', 'DESKRIPSI')
            ->where('JENIS', 179)
            ->where('STATUS', 1)
            ->orderBy('TABEL_ID', 'ASC')
            ->get();

        $pasien = DB::table('pendaftaran.kunjungan AS pk')
            ->leftJoin(
                'pendaftaran.pendaftaran AS pd',
                'pd.NOMOR',
                '=',
                'pk.NOPEN'
            )
            ->leftJoin(
                'master.pasien AS p',
                'p.NORM',
                '=',
                'pd.NORM'
            )
            ->leftJoin('master.referensi AS ag', function ($join) {
                $join->on('ag.ID', '=', 'p.AGAMA')
                    ->where('ag.JENIS', 1);
            })
            ->leftJoin('master.referensi AS kj', function ($join) {
                $join->on('kj.ID', '=', 'p.PEKERJAAN')
                    ->where('kj.JENIS', 4);
            })
            ->leftJoin(
                'master.dokter AS dok',
                'dok.ID',
                '=',
                'pk.DPJP'
            )
            ->select(
                'pd.TANGGAL AS TGL_KEDATANGAN',
                'dok.ID',
                DB::raw(
                    'master.getNamaLengkapPegawai(dok.NIP) AS NAMADOKTER'
                ),
                'ag.DESKRIPSI AS AGAMA',
                'kj.DESKRIPSI AS PEKERJAAN'
            )
            ->where('pk.NOMOR', $kunjungan)
            ->first();

        $dokter = DB::table('master.dokter AS dok')
                ->select('dok.ID', DB::raw('master.getNamaLengkapPegawai(dok.NIP) AS NAMADOKTER'))
                ->where('dok.STATUS',1)
                ->get();

        $riwayat_alergi = DB::table('master.referensi')
            ->select('ID', 'DESKRIPSI')
            ->where('JENIS', 180)
            ->where('STATUS', 1)
            ->orderBy('TABEL_ID', 'ASC')
            ->get();

        $usia_kehamilan = DB::table('master.referensi')
            ->select('ID', 'DESKRIPSI')
            ->where('JENIS', 299)
            ->where('STATUS', 1)
            ->orderBy('TABEL_ID', 'ASC')
            ->get();

        $jenis_persalinan = DB::table('master.referensi')
            ->select('ID', 'DESKRIPSI')
            ->where('JENIS', 300)
            ->where('STATUS', 1)
            ->orderBy('TABEL_ID', 'ASC')
            ->get();

        $penyulit = DB::table('master.referensi')
            ->select('ID', 'DESKRIPSI')
            ->where('JENIS', 301)
            ->where('STATUS', 1)
            ->orderBy('TABEL_ID', 'ASC')
            ->get();

        $jenis_kelamin = DB::table('master.referensi')
            ->select('ID', 'DESKRIPSI')
            ->where('JENIS', 2)
            ->where('STATUS', 1)
            ->orderBy('TABEL_ID', 'ASC')
            ->get();

        $penolong = DB::table('master.referensi')
            ->select('ID', 'DESKRIPSI')
            ->where('JENIS', 303)
            ->where('STATUS', 1)
            ->orderBy('TABEL_ID', 'ASC')
            ->get();

        $tempat = DB::table('master.referensi')
            ->select('ID', 'DESKRIPSI')
            ->where('JENIS', 304)
            ->where('STATUS', 1)
            ->orderBy('TABEL_ID', 'ASC')
            ->get();

        $keadaan_sat_ini = DB::table('master.referensi')
            ->select('ID', 'DESKRIPSI')
            ->where('JENIS', 302)
            ->where('STATUS', 1)
            ->orderBy('TABEL_ID', 'ASC')
            ->get();

        $usia = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',192)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $jk = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',193)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $cara_keluar = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',45)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $keadaan_keluar = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',46)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $frekuensi_obat = DB::table('master.frekuensi_aturan_resep')
                ->select('ID','FREKUENSI')
                ->where('STATUS',1)
                ->orderBy('ID','ASC')
                ->get();

        $rute_obat = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',217)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $jenis_ruang = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',242)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $jenis_perawatan = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',243)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $jenis_alergi = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',180)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $jenisBarthel = [
            232 => 'Mengendalikan rangsang defekasi',
            233 => 'Mengendalikan rangsang berkemih',
            234 => 'Membersihkan diri (seka muka, sisir rambut, sikat gigi)',
            235 => 'Penggunaan jamban, masuk dan keluar',
            236 => 'Makan',
            237 => 'Berubah sikap dari berbaring ke duduk',
            238 => 'Berpindah/berjalan',
            239 => 'Memakai baju',
            240 => 'Naik turun tangga',
            241 => 'Mandi',
        ];

        $referensiBarthel = DB::table('master.referensi')
            ->whereIn('JENIS', array_keys($jenisBarthel))
            ->where('STATUS', 1)
            ->orderBy('JENIS')
            ->orderBy('ID')
            ->get()
            ->groupBy('JENIS');

        return [
            'kunjungan' => $kunjungan,
            'tingkat_kesadaran' => $tingkat_kesadaran,
            'pasien' => $pasien,
            'dokter' => $dokter,
            'riwayat_alergi' => $riwayat_alergi,
            'usia_kehamilan' => $usia_kehamilan,
            'jenis_persalinan' => $jenis_persalinan,
            'penyulit' => $penyulit,
            'jenis_kelamin' => $jenis_kelamin,
            'penolong' => $penolong,
            'tempat' => $tempat,
            'keadaan_sat_ini' => $keadaan_sat_ini,
            'usia' => $usia,
            'jk' => $jk,
            'cara_keluar' => $cara_keluar,
            'keadaan_keluar' => $keadaan_keluar,
            'frekuensi_obat' => $frekuensi_obat,
            'rute_obat' => $rute_obat,
            'jenis_ruang' => $jenis_ruang,
            'jenis_perawatan' => $jenis_perawatan,
            'jenis_alergi' => $jenis_alergi,
            'jenisBarthel' => $jenisBarthel,
            'referensiBarthel' => $referensiBarthel,
        ];
    }

    private function formInputIdentification($kunjungan)
    {
        $getData = DB::table('pendaftaran.kunjungan AS pk')
            ->select(
                'pk.NOMOR AS NOKUNJUNGAN',
                'pk.MASUK AS TGLMASUK'
            )
            ->where('pk.NOMOR', $kunjungan)
            ->where('pk.STATUS', '!=', 0)
            ->orderBy('pk.MASUK', 'DESC')
            ->first();

        // if (!$getData) {
        //     return null;
        // }

        return [
            'inputdate' => $getData?->TGLMASUK,
        ];
    }
}
