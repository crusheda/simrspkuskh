<?php

namespace App\Http\Controllers\EMR\Form;

use App\Http\Controllers\Controller;
use App\Traits\FieldEmpty;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth, Storage;

class AddOnPengkajianController extends Controller
{
    use FieldEmpty;

    // Helper untuk mengambil 1 data berdasarkan kunjungan
    private function getData($KUNJUNGAN, $table, $columns = ['*'])
    {
        return DB::table($table)
            ->select($columns)
            ->where('KUNJUNGAN', $KUNJUNGAN)
            ->whereIn('STATUS', [1, 2])
            ->orderByDesc('ID')
            ->first();
    }

    // ======================================================================================================================= FUNCTION STARTED !!!!

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
        $ref_riw_alergi = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',180)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $riwayat_alergi = DB::table('medicalrecord.riwayat_alergi as ra')
            ->leftJoin('master.referensi as ref', function($join){
                $join->on('ra.JENIS', '=', 'ref.ID')
                    ->where('ref.JENIS',180)
                    ->where('ref.STATUS',1);
            })
            ->select('ra.*', 'ref.DESKRIPSI as JENIS_ALERGI')
            ->where('ra.KUNJUNGAN', $kunjungan)
            ->where('ra.STATUS', 1)
            ->get();

        $data = [
            'kunjungan' => $kunjungan,
            'ref_riw_alergi' => $ref_riw_alergi,
            'riw_alergi' => $riwayat_alergi,
        ];

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

    // SKRINING - SKRINING
    public function getSkriningNyeri($KUNJUNGAN)
    {
        $penilaianNyeri = $this->getData(
            $KUNJUNGAN,
            'medicalrecord.penilaian_nyeri',
            [
                'NYERI',
                'ONSET',
                'SKALA',
                'METODE',
                'SKOR1',
                'SKOR2',
                'SKOR3',
                'SKOR4',
                'SKOR5',
                'SKOR6',
                'PENCETUS',
                'GAMBARAN',
                'DURASI',
                'LOKASI',
            ]
        );

        return response()->json([
            'status' => true,
            'data' => $penilaianNyeri
        ]);
    }

    public function simpanSkriningNyeri(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'NOKUNJ' => 'required',
            ],
            [
                'NOKUNJ.required' => 'Kunjungan wajib diisi.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        DB::beginTransaction();

        try {

            // ==========================================
            // PENILAIAN / SKRINING NYERI
            // ==========================================
            $dataNyeri = [
                'NYERI'    => $request->sn_nyeri ?? 0,
                'ONSET'    => $request->sn_onset ?? 0,
                'SKALA'    => $request->sn_skala ?? 0,
                'METODE'   => $request->sn_metode ?? '',

                'SKOR1'    => 0,
                'SKOR2'    => 0,
                'SKOR3'    => 0,
                'SKOR4'    => 0,
                'SKOR5'    => 0,
                'SKOR6'    => 0,

                'PENCETUS' => $request->sn_pencetus ?? '',
                'GAMBARAN' => $request->sn_gambaran ?? '',
                'DURASI'   => $request->sn_durasi ?? '',
                'LOKASI'   => $request->sn_lokasi ?? '',

                'OLEH'     => auth()->id(),
                'STATUS'   => 1,
                'TANGGAL'  => now(),
            ];
            switch ((string) $request->sn_metode) {

                // BPS
                case '2':
                    $dataNyeri['SKOR1'] = $request->sn_bps_1 ?? 0;
                    $dataNyeri['SKOR2'] = $request->sn_bps_2 ?? 0;
                    $dataNyeri['SKOR3'] = $request->sn_bps_3 ?? 0;
                    break;

                // NIPS
                case '3':
                    $dataNyeri['SKOR1'] = $request->sn_nips_1 ?? 0;
                    $dataNyeri['SKOR2'] = $request->sn_nips_2 ?? 0;
                    $dataNyeri['SKOR3'] = $request->sn_nips_3 ?? 0;
                    $dataNyeri['SKOR4'] = $request->sn_nips_4 ?? 0;
                    $dataNyeri['SKOR5'] = $request->sn_nips_5 ?? 0;
                    $dataNyeri['SKOR6'] = $request->sn_nips_6 ?? 0;
                    break;

                // FLACC
                case '4':
                    $dataNyeri['SKOR1'] = $request->sn_flacc_1 ?? 0;
                    $dataNyeri['SKOR2'] = $request->sn_flacc_2 ?? 0;
                    $dataNyeri['SKOR3'] = $request->sn_flacc_3 ?? 0;
                    $dataNyeri['SKOR4'] = $request->sn_flacc_4 ?? 0;
                    $dataNyeri['SKOR5'] = $request->sn_flacc_5 ?? 0;
                    break;

                // NRS / VAS
                    // Tidak perlu SKOR1-SKOR6
                    case '1':
                    case '5':
                    default:
                    break;
            }
            DB::table('medicalrecord.penilaian_nyeri')
                ->updateOrInsert(
                    [
                        'KUNJUNGAN' => $request->NOKUNJ
                    ],
                    $dataNyeri
                );

            // ==========================================
            // COMMIT
            // ==========================================
            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Skrining Nyeri berhasil disimpan.'
            ], 200);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Data Skrining Nyeri gagal disimpan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function getSkriningResikoJatuhHumptyDumpty($KUNJUNGAN)
    {
        $humptyDumpty = $this->getData(
            $KUNJUNGAN,
            'medicalrecord.penilaian_skala_humpty_dumpty',
            [
                'UMUR',
                'JENIS_KELAMIN',
                'DIAGNOSA',
                'GANGGUAN_KONGNITIF',
                'FAKTOR_LINGKUNGAN',
                'RESPON',
                'PENGGUNAAN_OBAT',
            ]
        );

        return response()->json([
            'status' => true,
            'data' => $humptyDumpty
        ]);
    }

    public function simpanSkriningResikoJatuhHumptyDumpty(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'NOKUNJ' => 'required',
            ],
            [
                'NOKUNJ.required' => 'Kunjungan wajib diisi.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        DB::beginTransaction();

        try {

            $humptyFields = [
                'rj_usia',
                'rj_jk',
                'rj_hd_1',
                'rj_hd_2',
                'rj_hd_3',
                'rj_hd_4',
                'rj_hd_5',
            ];

            if ($this->isFieldEmpty($request, $humptyFields)) {

                // Semua kosong → hapus skrining lama
                DB::table('medicalrecord.penilaian_skala_humpty_dumpty')
                    ->where('KUNJUNGAN', $request->NOKUNJ)
                    ->update([
                        'OLEH'   => auth()->id(),
                        'STATUS' => 0,
                    ]);

            } else {

                DB::table('medicalrecord.penilaian_skala_humpty_dumpty')->updateOrInsert(
                    [
                        'KUNJUNGAN' => $request->NOKUNJ
                    ],
                    [
                        'UMUR'               => $request->input('rj_usia') ?? 0,
                        'JENIS_KELAMIN'      => $request->input('rj_jk') ?? 0,
                        'DIAGNOSA'           => $request->input('rj_hd_1') ?? 0,
                        'GANGGUAN_KONGNITIF' => $request->input('rj_hd_2') ?? 0,
                        'FAKTOR_LINGKUNGAN'  => $request->input('rj_hd_3') ?? 0,
                        'RESPON'             => $request->input('rj_hd_4') ?? 0,
                        'PENGGUNAAN_OBAT'    => $request->input('rj_hd_5') ?? 0,
                        'TANGGAL'            => now(),
                        'OLEH'               => auth()->id(),
                        'STATUS'             => 1,
                    ]
                );
            }

            // ==========================================
            // COMMIT
            // ==========================================
            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Skrining Resiko Jatuh berhasil disimpan.'
            ], 200);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Data Skrining Resiko Jatuh gagal disimpan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function getSkriningResikoJatuhSkalaMorse($KUNJUNGAN)
    {
        $morse = $this->getData(
            $KUNJUNGAN,
            'medicalrecord.penilaian_skala_morse',
            [
                'RIWAYAT_JATUH',
                'DIAGNOSIS',
                'ALAT_BANTU',
                'HEPARIN',
                'GAYA_BERJALAN',
                'KESADARAN',
            ]
        );

        return response()->json([
            'status' => true,
            'data' => $morse
        ]);
    }

    public function simpanSkriningResikoJatuhSkalaMorse(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'NOKUNJ' => 'required',
            ],
            [
                'NOKUNJ.required' => 'Kunjungan wajib diisi.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        DB::beginTransaction();

        try {

            $morseFields = [
                'rj_sm_1',
                'rj_sm_2',
                'rj_sm_3',
                'rj_sm_4',
                'rj_sm_5',
                'rj_sm_6',
            ];

            if ($this->isFieldEmpty($request, $morseFields)) {

                // Semua kosong → hapus skrining lama
                DB::table('medicalrecord.penilaian_skala_morse')
                    ->where('KUNJUNGAN', $request->NOKUNJ)
                    ->update([
                        'OLEH'   => auth()->id(),
                        'STATUS' => 0,
                    ]);

            } else {

                DB::table('medicalrecord.penilaian_skala_morse')->updateOrInsert(
                    [
                        'KUNJUNGAN' => $request->NOKUNJ
                    ],
                    [
                        'RIWAYAT_JATUH' => $request->input('rj_sm_1') ?? 0,
                        'DIAGNOSIS'     => $request->input('rj_sm_2') ?? 0,
                        'ALAT_BANTU'    => $request->input('rj_sm_3') ?? 0,
                        'HEPARIN'       => $request->input('rj_sm_4') ?? 0,
                        'GAYA_BERJALAN' => $request->input('rj_sm_5') ?? 0,
                        'KESADARAN'     => $request->input('rj_sm_6') ?? 0,
                        'TANGGAL'       => now(),
                        'OLEH'          => auth()->id(),
                        'STATUS'        => 1,
                    ]
                );
            }

            // ==========================================
            // COMMIT
            // ==========================================
            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Skrining Resiko Jatuh berhasil disimpan.'
            ], 200);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Data Skrining Resiko Jatuh gagal disimpan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function getSkriningResikoJatuhEPFRA($KUNJUNGAN)
    {
        $epfra = $this->getData(
            $KUNJUNGAN,
            'medicalrecord.penilaian_epfra',
            [
                'USIA',
                'STATUS_MENTAL',
                'ELIMINASI',
                'MEDIKASI',
                'DIAGNOSIS',
                'AMBULASI',
                'NUTRISI',
                'GANGGUAN_TIDUR',
                'RIWAYAT_JATUH',
            ]
        );

        return response()->json([
            'status' => true,
            'data' => $epfra
        ]);
    }

    public function simpanSkriningResikoJatuhEPFRA(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'NOKUNJ' => 'required',
            ],
            [
                'NOKUNJ.required' => 'Kunjungan wajib diisi.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        DB::beginTransaction();

        try {

            $epfraFields = [
                'rj_epfra_usia',
                'rj_epfra_1',
                'rj_epfra_2',
                'rj_epfra_3',
                'rj_epfra_4',
                'rj_epfra_5',
                'rj_epfra_6',
                'rj_epfra_7',
                'rj_epfra_8',
            ];

            if ($this->isFieldEmpty($request, $epfraFields)) {

                // Semua kosong → hapus skrining lama
                DB::table('medicalrecord.penilaian_epfra')
                    ->where('KUNJUNGAN', $request->NOKUNJ)
                    ->update([
                        'OLEH'   => auth()->id(),
                        'STATUS' => 0,
                    ]);

            } else {

                DB::table('medicalrecord.penilaian_epfra')->updateOrInsert(
                    [
                        'KUNJUNGAN' => $request->NOKUNJ
                    ],
                    [
                        'USIA'           => $request->input('rj_epfra_usia') ?? 0,
                        'STATUS_MENTAL'  => $request->input('rj_epfra_1') ?? 0,
                        'ELIMINASI'      => $request->input('rj_epfra_2') ?? 0,
                        'MEDIKASI'       => $request->input('rj_epfra_3') ?? 0,
                        'DIAGNOSIS'      => $request->input('rj_epfra_4') ?? 0,
                        'AMBULASI'       => $request->input('rj_epfra_5') ?? 0,
                        'NUTRISI'        => $request->input('rj_epfra_6') ?? 0,
                        'GANGGUAN_TIDUR' => $request->input('rj_epfra_7') ?? 0,
                        'RIWAYAT_JATUH'  => $request->input('rj_epfra_8') ?? 0,
                        'TANGGAL'        => now(),
                        'OLEH'           => auth()->id(),
                        'STATUS'         => 1,
                    ]
                );
            }

            // ==========================================
            // COMMIT
            // ==========================================
            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Skrining Resiko Jatuh berhasil disimpan.'
            ], 200);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Data Skrining Resiko Jatuh gagal disimpan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function getSkriningGiziMust($KUNJUNGAN)
    {
        $must = $this->getData(
            $KUNJUNGAN,
            'medicalrecord.permasalahan_gizi',
            [
                'BERAT_BADAN_SIGNIFIKAN',
                'PERUBAHAN_BERAT_BADAN',
                'INTAKE_MAKANAN',
                'KONDISI_KHUSUS',
                'SKOR',
                'STATUS_SKOR',
            ]
        );

        return response()->json([
            'status' => true,
            'data' => $must
        ]);
    }

    public function simpanSkriningGiziMust(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'NOKUNJ' => 'required',
            ],
            [
                'NOKUNJ.required' => 'Kunjungan wajib diisi.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        DB::beginTransaction();

        try {

            $mustFields = [
                'sgd1',
                'sgd1_c',
                'sgd2',
                'sgd3',
            ];

            if ($this->isFieldEmpty($request, $mustFields)) {

                // Semua kosong → hapus skrining lama
                DB::table('medicalrecord.permasalahan_gizi')
                    ->where('KUNJUNGAN', $request->NOKUNJ)
                    ->update([
                        'OLEH'   => auth()->id(),
                        'STATUS' => 0,
                    ]);

            } else {

                DB::table('medicalrecord.permasalahan_gizi')->updateOrInsert(
                    [
                        'KUNJUNGAN' => $request->NOKUNJ
                    ],
                    [
                        'BERAT_BADAN_SIGNIFIKAN' => $request->input('sgd1') ?? 0,
                        'PERUBAHAN_BERAT_BADAN'  => $request->input('sgd1_c') ?? 0,
                        'INTAKE_MAKANAN'         => $request->input('sgd2') ?? 0,
                        'KONDISI_KHUSUS'         => $request->input('sgd3') ?? 0,
                        'SKOR'                   => $request->input('skor_sgd') ?? 0,
                        'STATUS_SKOR'            => 1,
                        'TANGGAL'               => now(),
                        'OLEH'                   => auth()->id(),
                        'STATUS'                 => 1,
                    ]
                );
            }

            // ==========================================
            // COMMIT
            // ==========================================
            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Skrining Gizi berhasil disimpan.'
            ], 200);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Data Skrining Gizi gagal disimpan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function getSkriningGiziStrongKid($KUNJUNGAN)
    {
        $strongkid = $this->getData(
            $KUNJUNGAN,
            'medicalrecord.penilaian_strong_kid',
            [
                'TAMPAK_KURUS',
                'PENURUNAN_BERAT_BADAN',
                'DIARE_INTAKE_MAKANAN',
                'RESIKO_MALNUTRISI',
                'SKOR',
                'STATUS_SKOR',
            ]
        );

        return response()->json([
            'status' => true,
            'data' => $strongkid
        ]);
    }

    public function simpanSkriningGiziStrongKid(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'NOKUNJ' => 'required',
            ],
            [
                'NOKUNJ.required' => 'Kunjungan wajib diisi.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        DB::beginTransaction();

        try {

            $strongKidFields = [
                'sga1',
                'sga2',
                'sga3',
                'sga4',
            ];

            if ($this->isFieldEmpty($request, $strongKidFields)) {

                // Semua kosong → hapus skrining lama
                DB::table('medicalrecord.penilaian_strong_kid')
                    ->where('KUNJUNGAN', $request->NOKUNJ)
                    ->update([
                        'OLEH'   => auth()->id(),
                        'STATUS' => 0,
                    ]);

            } else {

                DB::table('medicalrecord.penilaian_strong_kid')->updateOrInsert(
                    [
                        'KUNJUNGAN' => $request->NOKUNJ
                    ],
                    [
                        'TAMPAK_KURUS'          => $request->input('sga1') ?? 0,
                        'PENURUNAN_BERAT_BADAN' => $request->input('sga2') ?? 0,
                        'DIARE_INTAKE_MAKANAN'  => $request->input('sga3') ?? 0,
                        'RESIKO_MALNUTRISI'     => $request->input('sga4') ?? 0,
                        'SKOR'                  => $request->input('skor_sga') ?? 0,
                        'STATUS_SKOR'           => 1,
                        'TANGGAL'               => now(),
                        'OLEH'                  => auth()->id(),
                        'STATUS'                => 1,
                    ]
                );
            }

            // ==========================================
            // COMMIT
            // ==========================================
            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Skrining Gizi berhasil disimpan.'
            ], 200);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Data Skrining Gizi gagal disimpan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    function getKebutuhanEdukasi($KUNJUNGAN)
    {
        $edu1 = $this->getData(
            $KUNJUNGAN,
            'medicalrecord.edukasi_pasien_keluarga',
            [
                'KESEDIAAN',

                'HAMBATAN',
                    'HAMBATAN_PENDENGARAN',
                    'HAMBATAN_PENGLIHATAN',
                    'HAMBATAN_KOGNITIF',
                    'HAMBATAN_FISIK',
                    'HAMBATAN_BUDAYA',
                    'HAMBATAN_EMOSI',
                    'HAMBATAN_BAHASA',

                'PENERJEMAH',
                    'BAHASA',

                'EDUKASI_DIAGNOSA',
                'EDUKASI_REHAB_MEDIK',
                'EDUKASI_HKP',
                'EDUKASI_PEMBERIAN_INFORMED_CONSENT',
                'EDUKASI_CUCI_TANGAN',
                'EDUKASI_PERENCANAAN_PULANG',
                'EDUKASI_OBAT',
                'EDUKASI_NYERI',
                'EDUKASI_HAK_BERPARTISIPASI',
                'EDUKASI_PENUNDAAN_PELAYANAN',
                'EDUKASI_BAHAYA_MEROKO',
                'EDUKASI_NUTRISI',
                'EDUKASI_PENGGUNAAN_ALAT',
                'EDUKASI_PROSEDURE_PENUNJANG',
                'EDUKASI_KELAMBATAN_PELAYANAN',
                'EDUKASI_RUJUKAN_PASIEN',
                'STATUS_LAIN',
                'DESKRIPSI_LAINYA',
            ]
        );

        $edu2 = $this->getData(
            $KUNJUNGAN,
            'medicalrecord.edukasi_emergency',
            [
                'EDUKASI',
            ]
        );

        $edu = array_merge(
            (array) $edu1,
            (array) $edu2
        );

        return response()->json([
            'status' => true,
            'data' => $edu
        ]);
    }

    function simpanKebutuhanEdukasi(Request $request, $KUNJUNGAN)
    {
        DB::beginTransaction();

        try {

            DB::table('medicalrecord.edukasi_pasien_keluarga')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    // Edukasi awal
                    'KESEDIAAN' => $request->edu_1 ?? 0,

                    'HAMBATAN' => $request->edu_2 ?? 0,

                    'PENERJEMAH' => $request->edu_3 ?? 0,
                    'PENERJEMAH' => $request->edu_3_lain ?? '',

                    // Kebutuhan Edukasi
                    'EDUKASI_DIAGNOSA' => $request->kb_edu_1 ?? 0,
                    'EDUKASI_REHAB_MEDIK' => $request->kb_edu_2 ?? 0,
                    'EDUKASI_HKP' => $request->kb_edu_3 ?? 0,

                    'EDUKASI_PEMBERIAN_INFORMED_CONSENT' => $request->kb_edu_4 ?? 0,

                    'EDUKASI_CUCI_TANGAN' => $request->kb_edu_5 ?? 0,
                    'EDUKASI_PERENCANAAN_PULANG' => $request->kb_edu_6 ?? 0,

                    'EDUKASI_OBAT' => $request->kb_edu_7 ?? 0,
                    'EDUKASI_NYERI' => $request->kb_edu_8 ?? 0,
                    'EDUKASI_HAK_BERPARTISIPASI' => $request->kb_edu_9 ?? 0,

                    'EDUKASI_PENUNDAAN_PELAYANAN' => $request->kb_edu_10 ?? 0,
                    'EDUKASI_BAHAYA_MEROKO' => $request->kb_edu_11 ?? 0,

                    'EDUKASI_NUTRISI' => $request->kb_edu_13 ?? 0,
                    'EDUKASI_PENGGUNAAN_ALAT' => $request->kb_edu_14 ?? 0,
                    'EDUKASI_PROSEDURE_PENUNJANG' => $request->kb_edu_15 ?? 0,

                    'EDUKASI_KELAMBATAN_PELAYANAN' => $request->kb_edu_16 ?? 0,
                    'EDUKASI_RUJUKAN_PASIEN' => $request->kb_edu_17 ?? 0,

                    // Lainnya
                    'STATUS_LAIN' => $request->kb_edu_12 ?? 0,
                    'DESKRIPSI_LAINYA' => $request->kb_edu_lain,

                    'TANGGAL' => now(),
                    'OLEH' => auth()->id(),
                    'STATUS' => 1,
                ]
            );

            DB::table('medicalrecord.edukasi_emergency')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'EDUKASI' => $request->kb_edu_deskripsi ?? '',
                    'KEMBALI_KE_UGD' => '',

                    'TANGGAL' => now(),
                    'OLEH' => auth()->id(),
                    'STATUS' => 1,
                ]
            );

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Kebutuhan Edukasi berhasil diperbarui.'
            ], 200);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Data Kebutuhan Edukasi gagal disimpan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    function getTandaVitalRI($KUNJUNGAN)
    {
        $ttv1 = $this->getData(
            $KUNJUNGAN,
            'medicalrecord.tanda_vital',
            [
                'KEADAAN_UMUM',
                'SISTOLIK',
                'DISTOLIK',
                'FREKUENSI_NADI',
                'FREKUENSI_NADI_CB',
                'SUHU',
                'SATURASI_O2',
                'FREKUENSI_NAFAS',
                'FREKUENSI_NAFAS_CB',
                'EYE',
                'VERBAL',
                'MOTORIK',
                'GCS',
            ]
        );

        $ttv2 = $this->getData(
            $KUNJUNGAN,
            'medicalrecord.edukasi_emergency',
            [
                'EDUKASI',
            ]
        );

        $ttv = array_merge(
            (array) $ttv1,
            (array) $ttv2
        );

        return response()->json([
            'status' => true,
            'data' => $ttv
        ]);
    }

    function simpanTandaVitalRI(Request $request, $KUNJUNGAN)
    {
        // $validator = Validator::make(
        //     $request->all(),
        //     [
        //         'NOKUNJ' => 'required',
        //     ],
        //     [
        //         'NOKUNJ.required' => 'Kunjungan wajib terisi.',
        //     ]
        // );

        // if ($validator->fails()) {
        //     return response()->json([
        //         'status'  => false,
        //         'message' => $validator->errors()->first()
        //     ], 422);
        // }

        DB::beginTransaction();

        try {

            DB::table('medicalrecord.tanda_vital')->updateOrInsert(
                [
                    'KUNJUNGAN' => $KUNJUNGAN
                ],
                [
                    'KEADAAN_UMUM'          => $request->tv_keu ?? '',
                    'SISTOLIK'              => $request->tv_td_up ?? 0,
                    'DISTOLIK'              => $request->tv_td_down ?? 0,
                    'FREKUENSI_NADI'        => $request->tv_nadi ?? 0,
                    'FREKUENSI_NADI_CB'     => $request->tv_nadi_cb ?? null,
                    'SUHU'                  => $request->tv_suhu ?? 0,
                    'SATURASI_O2'           => $request->tv_spo2 ?? 0,
                    'FREKUENSI_NAFAS'       => $request->tv_nafas ?? 0,
                    'FREKUENSI_NAFAS_CB'    => $request->tv_nafas_cb ?? null,
                    'EYE'                   => $request->tv_gcs_e ?? 0,
                    'VERBAL'                => $request->tv_gcs_v ?? 0,
                    'MOTORIK'               => $request->tv_gcs_m ?? 0,
                    'GCS'                   => $request->tv_gcs_t ?? 0,
                    'OLEH'                  => auth()->id(),
                    'STATUS'                => 1,
                    'TANGGAL'               => now()
                ]
            );

            DB::table('medicalrecord.nutrisi')->updateOrInsert(
                [
                    'KUNJUNGAN' => $KUNJUNGAN
                ],
                [
                    'DATA_PENGUKURAN'       => 1,
                    'BERAT_BADAN'           => $request->tv_bb,
                    'TINGGI_BADAN'          => $request->tv_tb,
                    'INDEX_MASSA_TUBUH'     => $request->filled('gizi_imt')
                                                ? round((float) $request->gizi_imt, 2)
                                                : 0,
                    'TANGGAL_PEMERIKSAAN'   => now(),
                    'OLEH'                  => auth()->id(),
                    'STATUS'                => 1,
                    'TANGGAL'               => now()
                ]
            );

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Tanda Vital berhasil diperbarui.'
            ], 200);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Data Tanda Vital gagal disimpan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
