<?php

namespace App\Http\Controllers\EMR\Form;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth, Storage;

class AddOnPengkajianController extends Controller
{
    public function cariPPK(Request $request)
    {
        $keyword = trim($request->get('q', ''));

        if (mb_strlen($keyword) < 2) {
            return response()->json([]);
        }

        $ppk = DB::table('master.ppk as p')
            ->leftJoin('master.referensi as r', function ($join) {
                $join->on('r.ID', '=', 'p.JENIS')
                    ->where('r.JENIS', 11)
                    ->where('r.STATUS', 1);
            })
            ->select(
                'p.ID',
                'r.DESKRIPSI as JENIS',
                'p.NAMA',
                'p.ALAMAT',
                'p.DESWILAYAH as WILAYAH'
            )
            ->where('p.STATUS', 1)
            ->where(function ($query) use ($keyword) {
                $query->where('p.NAMA', 'LIKE', "%{$keyword}%")
                    ->orWhere('p.ALAMAT', 'LIKE', "%{$keyword}%")
                    ->orWhere('p.DESWILAYAH', 'LIKE', "%{$keyword}%");
            })
            ->orderBy('p.NAMA')
            ->limit(15)
            ->get()
            ->map(function ($item) {
                return [
                    'id'      => $item->ID,
                    'jenis'   => $item->JENIS,
                    'nama'    => $item->NAMA,
                    'alamat'  => $item->ALAMAT,
                    'wilayah' => $item->WILAYAH,
                ];
            });

        return response()->json($ppk);
    }

    public function cariObat(Request $request)
    {
        $keyword = trim($request->get('q', ''));

        if (mb_strlen($keyword) < 2) {
            return response()->json([]);
        }

        $obat = DB::table('inventory.barang as ib')
            ->leftJoin('inventory.satuan as is', 'ib.SATUAN', '=', 'is.ID')
            ->leftJoin('inventory.kategori as ik', 'ib.KATEGORI', '=', 'ik.ID')
            ->select('ib.ID', 'ib.NAMA', 'ib.STOK', 'is.NAMA as SATUAN', 'is.DESKRIPSI as KET_SATUAN', 'ik.NAMA as KATEGORI')
            ->where('ib.NAMA', 'like', '%' . $keyword . '%')
            ->orderBy('ib.NAMA')
            ->where('ib.STATUS', 1)
            ->limit(15)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->ID,
                    'nama' => $item->NAMA,
                    'stok' => $item->STOK,
                    'satuan' => $item->SATUAN,
                    'ket_satuan' => $item->KET_SATUAN,
                    'kategori' => $item->KATEGORI,
                ];
            });

        return response()->json($obat);
    }

    public function getRiwayatPemberianObat($kunjungan)
    {
        $data = DB::table('medicalrecord.riwayat_pemberian_obat as rpo')
            ->leftJoin('master.frekuensi_aturan_resep as far', function($join){
                $join->on('rpo.FREKUENSI', '=', 'far.ID')
                    ->where('far.STATUS',1);
            })
            ->leftJoin('master.referensi as ref_rute', function($join){
                $join->on('rpo.RUTE', '=', 'ref_rute.ID')
                    ->where('ref_rute.JENIS',217)
                    ->where('ref_rute.STATUS',1);
            })
            ->select('rpo.*', 'far.FREKUENSI as FREKUENSI_NAMA', 'far.KETERANGAN as FREKUENSI_KETERANGAN', 'ref_rute.DESKRIPSI as RUTE_NAMA')
            ->where('rpo.KUNJUNGAN', $kunjungan)
            ->where('rpo.STATUS', 1)
            ->get();

        return response()->json($data);
    }

    public function simpanRiwayatPemberianObat(Request $request, $KUNJUNGAN)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nama_obat' => ['required'],
            ],
            [
                'nama_obat.required' => 'Nama obat wajib diisi.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        DB::table('medicalrecord.riwayat_pemberian_obat')->insert([
            'KUNJUNGAN'         => $KUNJUNGAN,
            'OBAT'              => $request->nama_obat,
            // 'DOSIS'             => $request->dosis,
            // 'FREKUENSI'         => $request->frekuensi,
            // 'RUTE'              => $request->rute,
            // 'LAMA_PENGGUNAAN'   => $request->lama,
            'OLEH'              => auth()->id(),
            'TANGGAL'           => now(),
            'STATUS'            => 1,
        ]);

        return response()->json(['message' => 'Data riwayat pemberian obat berhasil disimpan.'], 200);
    }

    public function hapusRiwayatPenggunaanObat($KUNJUNGAN, $ID)
    {
        DB::table('medicalrecord.riwayat_pemberian_obat')
            ->where('KUNJUNGAN', $KUNJUNGAN)
            ->where('ID', $ID)
            ->update(['STATUS' => 0]);

        return response()->json(['message' => 'Data riwayat pemberian obat berhasil dihapus.'], 200);
    }

    public function getRiwayatAlergi($kunjungan)
    {
        $data = DB::table('medicalrecord.riwayat_alergi as ra')
            ->leftJoin('master.referensi as ref', function($join){
                $join->on('ra.JENIS', '=', 'ref.ID')
                    ->where('ref.JENIS',180)
                    ->where('ref.STATUS',1);
            })
            ->select('ra.*', 'ref.DESKRIPSI as JENIS_ALERGI')
            ->where('ra.KUNJUNGAN', $kunjungan)
            ->where('ra.STATUS', 1)
            ->get();

        return response()->json($data);
    }

    public function simpanRiwayatAlergi(Request $request, $KUNJUNGAN)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'jenis' => ['required'],
                'deskripsi' => ['required'],
            ],
            [
                'jenis.required' => 'Jenis alergi wajib diisi.',
                'deskripsi.required' => 'Deskripsi alergi wajib diisi.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        DB::table('medicalrecord.riwayat_alergi')->insert([
            'KUNJUNGAN'         => $KUNJUNGAN,
            'JENIS'             => $request->jenis,
            'DESKRIPSI'         => $request->deskripsi,
            'OLEH'              => auth()->id(),
            'TANGGAL'           => now(),
            'STATUS'            => 1,
        ]);

        return response()->json(['message' => 'Data riwayat alergi berhasil disimpan.'], 200);
    }

    public function hapusRiwayatAlergi($KUNJUNGAN, $ID)
    {
        DB::table('medicalrecord.riwayat_alergi')
            ->where('KUNJUNGAN', $KUNJUNGAN)
            ->where('ID', $ID)
            ->update(['STATUS' => 0]);

        return response()->json(['message' => 'Data riwayat alergi berhasil dihapus.'], 200);
    }

    //Hasil Lab
    public function getRiwayatLab($kunjungan) {
        $data = DB::select("
            SELECT LPAD(p.NORM, 8, '0') AS NORM,
            master.getNamaLengkap(p.NORM) AS NAMALENGKAP,
            CONCAT(rjk.DESKRIPSI, ' / ', DATE_FORMAT(p.TANGGAL_LAHIR, '%d-%m-%Y')) AS JKTGLLAHIR,

            master.getNamaLengkapPegawai(mp.NIP) AS DOKTER,
            mp.NIP AS NIPDPJP,
            master.getNamaLengkapPegawai(mpasal.NIP) AS DOKTERASAL,
            master.getNamaLengkapPegawai(mpper.NIP) AS ANALIS,

            pk.NOPEN,
            pk.MASUK AS TGLREG,
            hlab.TANGGAL AS TANGGALHASIL,
            chl.CATATAN,

            r.DESKRIPSI AS UNITPENGANTAR,
            ks.ALASAN AS DIAGNOSA,

            tm.KUNJUNGAN,

            ggl.DESKRIPSI AS GROUPLAB,
            kgl.DESKRIPSI AS KLPLAB,

            mt.NAMA AS NAMATINDAKAN,
            ptl.PARAMETER,

            IFNULL(hlab.NILAI_NORMAL, ptl.NILAI_RUJUKAN) AS NILAI_RUJUKAN,
            hlab.HASIL,
            IFNULL(hlab.SATUAN, sl.DESKRIPSI) AS SATUAN,
            hlab.KETERANGAN

            FROM layanan.hasil_lab hlab

            JOIN layanan.tindakan_medis tm ON hlab.TINDAKAN_MEDIS = tm.ID
            LEFT JOIN layanan.catatan_hasil_lab chl ON tm.KUNJUNGAN = chl.KUNJUNGAN
            LEFT JOIN master.dokter dok ON chl.DOKTER = dok.ID
            LEFT JOIN master.pegawai mp ON dok.NIP = mp.NIP
            LEFT JOIN layanan.petugas_tindakan_medis ptm ON ptm.TINDAKAN_MEDIS = tm.ID AND ptm.JENIS = 6 AND ptm.KE = 1 AND ptm.STATUS <> 0
            LEFT JOIN master.pegawai mpper ON ptm.MEDIS = mpper.ID

            JOIN master.parameter_tindakan_lab ptl ON hlab.PARAMETER_TINDAKAN = ptl.ID
            LEFT JOIN master.referensi sl ON ptl.SATUAN = sl.ID AND sl.JENIS = 35

            JOIN master.tindakan mt ON ptl.TINDAKAN = mt.ID
            LEFT JOIN master.mapping_group_pemeriksaan mgp ON mt.ID = mgp.PEMERIKSAAN AND mgp.STATUS = 1
            LEFT JOIN master.group_pemeriksaan kgl ON mgp.GROUP_PEMERIKSAAN_ID = kgl.ID AND kgl.JENIS = 8 AND kgl.STATUS = 1
            LEFT JOIN master.group_pemeriksaan ggl ON ggl.KODE = LEFT(kgl.KODE, 2) AND ggl.JENIS = 8 AND ggl.STATUS = 1

            JOIN pendaftaran.kunjungan pk ON tm.KUNJUNGAN = pk.NOMOR

            JOIN pendaftaran.pendaftaran pp ON pk.NOPEN = pp.NOMOR

            JOIN master.pasien p ON pp.NORM = p.NORM
            LEFT JOIN master.referensi rjk ON p.JENIS_KELAMIN = rjk.ID AND rjk.JENIS = 2
            LEFT JOIN layanan.order_lab ks ON pk.REF = ks.NOMOR
            LEFT JOIN pendaftaran.kunjungan kj ON ks.KUNJUNGAN = kj.NOMOR
            LEFT JOIN master.ruangan r ON kj.RUANGAN = r.ID AND r.JENIS = 5
            LEFT JOIN master.dokter dokasal ON ks.DOKTER_ASAL = dokasal.ID
            LEFT JOIN master.pegawai mpasal ON dokasal.NIP = mpasal.NIP

            WHERE kj.NOMOR = ?
            AND hlab.STATUS = 1 AND hlab.HASIL IS NOT NULL AND hlab.HASIL <> ''

            ORDER BY ggl.ID,
            kgl.ID,
            mt.ID,
            ptl.INDEKS ", [$kunjungan]);

            return $data;
    }

    //Hasil Rad
    public function getRiwayatRad($kunjungan)
    {
        $data = DB::table('layanan.hasil_rad as hrad')
            ->leftJoin('master.dokter as dok', 'hrad.DOKTER', '=', 'dok.ID')
            ->leftJoin('master.pegawai as mp', 'dok.NIP', '=', 'mp.NIP')
            ->join('layanan.tindakan_medis as tm', 'hrad.TINDAKAN_MEDIS', '=', 'tm.ID')
            ->leftJoin('master.tindakan as t', 'tm.TINDAKAN', '=', 't.ID')
            ->leftJoin('pendaftaran.kunjungan as pku', 'pku.NOMOR', '=', 'tm.KUNJUNGAN')
            ->leftJoin('layanan.order_rad as orad', function($join){
                $join->on('orad.NOMOR', '=', 'pku.REF')
                    ->whereIn('orad.STATUS', [1,2]);
            })
            ->leftJoin('master.dokter as dokasal', 'orad.DOKTER_ASAL', '=', 'dokasal.ID')
            ->leftJoin('layanan.petugas_tindakan_medis as ptm', function($join){
                $join->on('ptm.TINDAKAN_MEDIS', '=', 'tm.ID')
                    ->where('ptm.JENIS', 3)
                    ->where('ptm.KE', 1)
                    ->where('ptm.STATUS', '!=', 0);
            })
            ->leftJoin('master.perawat as prad', 'ptm.MEDIS', '=', 'prad.ID')
            ->join('pendaftaran.kunjungan as pk', 'tm.KUNJUNGAN', '=', 'pk.NOMOR')
            ->leftJoin('layanan.order_rad as ks', 'pk.REF', '=', 'ks.NOMOR')
            ->leftJoin('pendaftaran.kunjungan as kj', 'ks.KUNJUNGAN', '=', 'kj.NOMOR')
            ->leftJoin('master.ruangan as r', function($join){
                $join->on('kj.RUANGAN', '=', 'r.ID')
                    ->where('r.JENIS', 5);
            })
            ->join('pendaftaran.pendaftaran as pp', 'pk.NOPEN', '=', 'pp.NOMOR')
            ->join('master.pasien as p', 'pp.NORM', '=', 'p.NORM')
            ->leftJoin('master.referensi as rjk', function($join){
                $join->on('p.JENIS_KELAMIN', '=', 'rjk.ID')
                    ->where('rjk.JENIS', 2);
            })
            ->select([
                DB::raw("DATE_FORMAT(SYSDATE(),'%d-%m-%Y %H:%i:%s') AS TGLSKRG"),
                DB::raw("LPAD(p.NORM,8,'0') AS NORM"),
                DB::raw("master.getNamaLengkap(p.NORM) AS NAMALENGKAP"),
                DB::raw("CONCAT(rjk.DESKRIPSI,' / ',DATE_FORMAT(p.TANGGAL_LAHIR,'%d-%m-%Y')) AS JKTGLALHIR"),
            'hrad.TANGGAL',
                'hrad.KLINIS',
                'hrad.KESAN',
                'hrad.USUL',
                'hrad.HASIL',
                'hrad.BTK',
            DB::raw("master.getNamaLengkapPegawai(mp.NIP) AS DOKTER"),
                'mp.NIP AS NIPDOKTER',
            'pk.NOPEN',
                'pk.MASUK AS TGLREG',
            't.NAMA AS NAMATINDAKAN',
                'r.DESKRIPSI AS UNITPENGANTAR',
                'orad.ALASAN AS DIAGNOSA',
            'p.ALAMAT',
            DB::raw("master.getNamaLengkapPegawai(dokasal.NIP) AS DOKTERASAL"),
                DB::raw("master.getNamaLengkapPegawai(prad.NIP) AS RADIOGRAFER"),
            ])

            ->whereIn('tm.STATUS', [1,2])
            ->where('hrad.STATUS', '!=', 0)
            ->where('kj.NOMOR', $kunjungan)

            ->orderBy('t.ID')
            ->get();

        return $data;
    }

    public function getDiagnosis($kunjungan)
    {
        $nopen = DB::table('pendaftaran.kunjungan')->where('NOMOR', $kunjungan)->value('NOPEN');

        $data = DB::table('medicalrecord.diagnosa as diag')
                ->select(
                    'diag.ID',
                    'diag.DIAGNOSA',
                    DB::raw("CASE WHEN diag.UTAMA = 1 THEN 'UTAMA' ELSE 'SEKUNDER' END AS UTAMA"),
                    'diag.KODE as KODE_DIAGNOSA',
                    'mrc.STR as NAMA_DIAGNOSA'
                )
                ->leftJoin('master.mrconso as mrc', function ($join) {
                    $join->on('diag.KODE', '=', 'mrc.CODE')
                        ->whereNotIn('mrc.TTY', ['HT', 'PS'])
                        ->where(function ($q) {

                            // ==========================================
                            // PRIORITAS ICD10_2020
                            // ==========================================
                            $q->where('mrc.SAB', 'ICD10_2020')

                                // ==========================================
                                // FALLBACK ICD10_1998
                                // hanya jika ICD10_2020 tidak ada
                                // ==========================================
                                ->orWhere(function ($q) {
                                    $q->where('mrc.SAB', 'ICD10_1998')
                                        ->whereNotExists(function ($sub) {
                                            $sub->select(DB::raw(1))
                                                ->from('master.mrconso as mrc2020')
                                                ->whereColumn('mrc2020.CODE', 'diag.KODE')
                                                ->where('mrc2020.SAB', 'ICD10_2020')
                                                ->whereNotIn('mrc2020.TTY', ['HT', 'PS']);
                                        });
                                });
                        });
                })
                ->where('diag.NOPEN', $nopen)
                ->where('diag.STATUS', 1)
                ->get();

        return response()->json($data);
    }

    public function simpanDiagnosis(Request $request, $KUNJUNGAN)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'utama' => ['required'],
                'diagnosa' => ['required'],
            ],
            [
                'utama.required' => 'Status Diagnosa Utama / Tidak (Sekunder) wajib diisi.',
                'diagnosa.required' => 'Diagnosa wajib diisi.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $nopen = DB::table('pendaftaran.kunjungan')->where('NOMOR', $KUNJUNGAN)->value('NOPEN');

        if ($nopen === null) {
            return response()->json(['message' => 'Kunjungan tidak ditemukan.'], 404);
        }

        DB::table('medicalrecord.diagnosa')->insert([
            'NOPEN'             => $nopen,
            'UTAMA'             => ($request->utama == 1) ? 1 : 0,
            'DIAGNOSA'          => $request->diagnosa,
            'KODE'              => '',
            'INACBG'            => 1,
            'BARU'              => 1,
            'INA_GROUPER'       => 1,
            'DIAGNOSA_OLEH'     => auth()->id(),
            'OLEH'              => auth()->id(),
            'DIAGNOSA_TANGGAL'           => now(),
            'TANGGAL'           => now(),
            'STATUS'            => 1,
        ]);

        return response()->json(['message' => 'Data diagnosa berhasil disimpan.'], 200);
    }

    public function hapusDiagnosis($KUNJUNGAN, $ID)
    {
        $nopen = DB::table('pendaftaran.kunjungan')->where('NOMOR', $KUNJUNGAN)->value('NOPEN');
        DB::table('medicalrecord.diagnosa')
            ->where('NOPEN', $nopen)
            ->where('ID', $ID)
            ->update(['STATUS' => 0]);

        return response()->json(['message' => 'Data diagnosa berhasil dihapus.'], 200);
    }

    public function getRiwayatObstetri($kunjungan)
    {
        $data = DB::table('medicalrecord.riwayat_obstetri as ro')

            // Usia kehamilan
            ->leftJoin('master.referensi as uk', function ($join) {
                $join->on('ro.USIA_KEHAMILAN', '=', 'uk.ID')
                    ->where('uk.JENIS', 299)
                    ->where('uk.STATUS', 1);
            })

            // Jenis persalinan
            ->leftJoin('master.referensi as jp', function ($join) {
                $join->on('ro.JENIS_PERSALINAN', '=', 'jp.ID')
                    ->where('jp.JENIS', 300)
                    ->where('jp.STATUS', 1);
            })

            // Penyulit
            ->leftJoin('master.referensi as py', function ($join) {
                $join->on('ro.PENYULIT', '=', 'py.ID')
                    ->where('py.JENIS', 301)
                    ->where('py.STATUS', 1);
            })

            // Jenis kelamin
            ->leftJoin('master.referensi as jk', function ($join) {
                $join->on('ro.JENIS_KELAMIN', '=', 'jk.ID')
                ->where('jk.JENIS', 2)
                ->where('jk.STATUS', 1);
            })

            // Penolong
            ->leftJoin('master.referensi as pn', function ($join) {
                $join->on('ro.PENOLONG', '=', 'pn.ID')
                    ->where('pn.JENIS', 303)
                    ->where('pn.STATUS', 1);
            })

            // Tempat
            ->leftJoin('master.referensi as tp', function ($join) {
                $join->on('ro.TEMPAT', '=', 'tp.ID')
                    ->where('tp.JENIS', 304)
                    ->where('tp.STATUS', 1);
            })

            // Keadaan saat ini
            ->leftJoin('master.referensi as ks', function ($join) {
                $join->on('ro.KEADAAN_SAAT_INI', '=', 'ks.ID')
                    ->where('ks.JENIS', 302)
                    ->where('ks.STATUS', 1);
            })

            ->select(
                'ro.*',

                'uk.DESKRIPSI as USIA_KEHAMILAN_DESC',
                'jp.DESKRIPSI as JENIS_PERSALINAN_DESC',
                'py.DESKRIPSI as PENYULIT_DESC',
                'jk.DESKRIPSI as JENIS_KELAMIN_DESC',
                'pn.DESKRIPSI as PENOLONG_DESC',
                'tp.DESKRIPSI as TEMPAT_DESC',
                'ks.DESKRIPSI as KEADAAN_SAAT_INI_DESC'
            )

            ->where('ro.KUNJUNGAN', $kunjungan)
            ->where('ro.STATUS', 1)

            ->orderBy('ro.TAHUN', 'desc')
            ->orderBy('ro.ID', 'desc')

            ->get();

        return response()->json($data);
    }

    public function simpanRiwayatObstetri(Request $request, $KUNJUNGAN)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'tahun' => ['required', 'integer', 'min:1900', 'max:2100'],
                'usia_kehamilan' => ['required'],
                'jenis_persalinan' => ['required'],
                'penyulit' => ['nullable'],
                'penolong' => ['required'],
                'keterangan_penolong' => ['nullable'],
                'tempat' => ['required'],
                'keterangan_tempat' => ['nullable'],
                'jenis_kelamin' => ['required'],
                'berat_badan' => ['required', 'integer', 'min:0'],
                'keadaan_saat_ini' => ['required'],
            ],
            [
                'tahun.required' =>
                    'Tahun wajib diisi.',

                'usia_kehamilan.required' =>
                    'Usia kehamilan wajib diisi.',

                'jenis_persalinan.required' =>
                    'Jenis persalinan wajib diisi.',

                'penolong.required' =>
                    'Penolong wajib diisi.',

                'tempat.required' =>
                    'Tempat persalinan wajib diisi.',

                'jenis_kelamin.required' =>
                    'Jenis kelamin wajib diisi.',

                'berat_badan.required' =>
                    'Berat badan wajib diisi.',

                'keadaan_saat_ini.required' =>
                    'Keadaan saat ini wajib diisi.',
            ]
        );

        if ($validator->fails()) {

            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::table('medicalrecord.riwayat_obstetri')->insert([

            'KUNJUNGAN' =>
                $KUNJUNGAN,

            'TAHUN' =>
                $request->tahun,

            'USIA_KEHAMILAN' =>
                $request->usia_kehamilan,

            'JENIS_PERSALINAN' =>
                $request->jenis_persalinan,

            'PENYULIT' =>
                $request->penyulit,

            'PENOLONG' =>
                $request->penolong,

            'KETERANGAN_PENOLONG' =>
                $request->keterangan_penolong ?? '',

            'TEMPAT' =>
                $request->tempat,

            'KETERANGAN_TEMPAT' =>
                $request->keterangan_tempat ?? '',

            'JENIS_KELAMIN' =>
                $request->jenis_kelamin,

            'BERAT_BADAN' =>
                $request->berat_badan,

            'KEADAAN_SAAT_INI' =>
                $request->keadaan_saat_ini,

            'OLEH' =>
                auth()->id(),

            'STATUS' =>
                1,

            'DIBUAT_TANGGAL' =>
                now(),

        ]);

        return response()->json([
            'message' =>
                'Data riwayat obstetri berhasil disimpan.'
        ], 200);
    }

    public function hapusRiwayatObstetri($KUNJUNGAN, $ID)
    {
        DB::table('medicalrecord.riwayat_obstetri')
            ->where('KUNJUNGAN', $KUNJUNGAN)
            ->where('ID', $ID)
            ->update([
                'STATUS' => 0
            ]);

        return response()->json([
            'message' =>
                'Data riwayat obstetri berhasil dihapus.'
        ], 200);
    }
}
