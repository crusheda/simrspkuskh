<?php

namespace App\Http\Controllers\Pelayanan\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\pendaftaran\kunjungan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DaftarPasienController extends Controller
{
    function index()
    {
        $show = kunjungan::where('STATUS', 1)
                ->where('KELUAR', null)
                ->orderBy('MASUK','DESC')
                ->get();

        // print_r($show);
        // die();

        $data = [
            'show' => $show,
        ];

        return view('pages.pelayanan.pasien.index');
    }

    // API --
    function table()
    {
        $show = DB::table('pendaftaran.kunjungan AS pk')
                ->select(
                    'pk.*',
                    'pp.NORM',
                    DB::raw('master.getNamaLengkap(ps.NORM) AS NAMAPASIEN'),
                    DB::raw('master.getNamaLengkapPegawai(master.dokter.NIP) AS NAMADOKTER')
                )
                // ->selectRaw('SELECT master.getNamaLengkapPegawai("1708205") from master')
                ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
                ->leftJoin('master.pasien AS ps','ps.NORM','=','pp.NORM')
                ->leftJoin('aplikasi.pengguna','aplikasi.pengguna.ID','=','pk.DITERIMA_OLEH')
                ->leftJoin('master.ruangan AS ru','ru.ID','=','pk.RUANGAN')
                ->leftJoin('master.dokter','master.dokter.ID','=','pk.DPJP')

                ->where(function ($query) {
                    $query->where('pk.RUANGAN', 'LIKE', '1020101%')
                            ->orWhere('pk.RUANGAN', 'LIKE', '1020201%');
                })

                ->where('ru.STATUS', 1)
                ->where('pk.STATUS', 1)
                ->where('pk.KELUAR', null)
                ->orderBy('pk.MASUK','DESC')
                ->get();


        $data = [
            'show' => $show,
        ];

        return response()->json($data, 200);
    }
}

// ->join('master.ruangan','master.ruangan.ID','=','medicalrecord.jadwal_kontrol.RUANGAN')
// ->join('master.dokter','master.dokter.ID','=','medicalrecord.jadwal_kontrol.DOKTER')
// ->join('master.pegawai','master.pegawai.NIP','=','master.dokter.NIP')
// ->join('aplikasi.pengguna','aplikasi.pengguna.ID','=','medicalrecord.jadwal_kontrol.OLEH')
// ->join('pendaftaran.kunjungan','pendaftaran.kunjungan.NOMOR','=','medicalrecord.jadwal_kontrol.KUNJUNGAN')
// ->join('pendaftaran.pendaftaran','pendaftaran.pendaftaran.NOMOR','=','pendaftaran.kunjungan.NOPEN')
// ->join('master.pasien','master.pasien.NORM','=','pendaftaran.pendaftaran.NORM')
// ->join('master.kartu_asuransi_pasien','master.kartu_asuransi_pasien.NORM','=','pendaftaran.pendaftaran.NORM')
// ->select('pendaftaran.kunjungan.NOPEN','master.pasien.NORM','master.pasien.NAMA as NMPASIEN','master.pasien.ALAMAT as ALPASIEN','master.kartu_asuransi_pasien.NOMOR as NOBPJS','master.pegawai.NAMA as NMDOKTER','medicalrecord.jadwal_kontrol.*','master.ruangan.DESKRIPSI as NMRUANGAN','aplikasi.pengguna.NAMA as USER')
// ->where('medicalrecord.jadwal_kontrol.STATUS',1)
// ->where('master.kartu_asuransi_pasien.JENIS',2)
// ->orderBy('medicalrecord.jadwal_kontrol.DIBUAT_TANGGAL','DESC')
// -------------------------------------------------------------------------------------------------------------------------------
// GET SELECT DATA WITH QUERY
// $show = DB::select('SELECT * FROM master.bulan WHERE id=2');
// $show = DB::connection('db_pendaftaran')->select('CALL CetakSEP("0151R0130225V002145")');

// UPDATE DATA WITH QUERY
// DB::update('update users set username = ? , status = ? where user_id = ?', ["admin" , "active" , 1]);

// CALL PROCEDURE
// $show = DB::select('CALL CetakBarcodeRM("37804")');
