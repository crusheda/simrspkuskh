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

    public function getSkriningDekubitus($KUNJUNGAN)
    {
        $dekubitus = $this->getData(
            $KUNJUNGAN,
            'medicalrecord.penilaian_dekubitus',
            [
                'KONDISI_FISIK',
                'KESADARAN',
                'AKTIVITAS',
                'MOBILITAS',
                'INKONTINENSIA',
            ]
        );

        return response()->json([
            'status' => true,
            'data' => $dekubitus
        ]);
    }

    public function simpanSkriningDekubitus(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'NOKUNJ' => 'required',
                'decu_1' => 'nullable|integer|min:0|max:4',
                'decu_2' => 'nullable|integer|min:0|max:4',
                'decu_3' => 'nullable|integer|min:0|max:4',
                'decu_4' => 'nullable|integer|min:0|max:4',
                'decu_5' => 'nullable|integer|min:0|max:4',
            ],
            [
                'NOKUNJ.required' => 'Kunjungan wajib diisi.',
                'decu_1.integer' => 'Nilai kondisi fisik tidak valid.',
                'decu_2.integer' => 'Nilai kesadaran tidak valid.',
                'decu_3.integer' => 'Nilai aktivitas tidak valid.',
                'decu_4.integer' => 'Nilai mobilitas tidak valid.',
                'decu_5.integer' => 'Nilai inkontinensia tidak valid.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            $data = [
                'KONDISI_FISIK' => (int) ($request->decu_1 ?? 0),
                'KESADARAN' => (int) ($request->decu_2 ?? 0),
                'AKTIVITAS' => (int) ($request->decu_3 ?? 0),
                'MOBILITAS' => (int) ($request->decu_4 ?? 0),
                'INKONTINENSIA' => (int) ($request->decu_5 ?? 0),
                'OLEH' => auth()->id(),
                'STATUS' => 1,
                'TANGGAL' => now(),
            ];

            DB::table('medicalrecord.penilaian_dekubitus')
                ->updateOrInsert(
                    [
                        'KUNJUNGAN' => $request->NOKUNJ,
                    ],
                    $data
                );

            DB::commit();

            $skor =
                $data['KONDISI_FISIK'] +
                $data['KESADARAN'] +
                $data['AKTIVITAS'] +
                $data['MOBILITAS'] +
                $data['INKONTINENSIA'];

            if ($skor <= 11) {
                $kategori = 'Peningkatan Risiko';
                $keterangan = 'Risiko 50x lebih besar terjadinya ulkus decubitus.';
            } elseif ($skor <= 13) {
                $kategori = 'Risiko Sedang';
                $keterangan = 'Pasien memiliki risiko sedang terjadinya ulkus decubitus.';
            } elseif ($skor === 14) {
                $kategori = 'Risiko Tinggi';
                $keterangan = 'Risiko tinggi terjadinya ulkus decubitus.';
            } else {
                $kategori = 'Risiko Kecil';
                $keterangan = 'Risiko kecil terjadinya ulkus decubitus.';
            }

            return response()->json([
                'status' => true,
                'message' => 'Skrining Dekubitus berhasil disimpan.',
                'data' => [
                    'skor_s_decu' => $skor,
                    'kategori_s_decu' => $kategori,
                    'keterangan_s_decu' => $keterangan,
                ],
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Data Skrining Dekubitus gagal disimpan.',
                'error' => $e->getMessage(),
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

    function getHubunganStatusPsikososial($KUNJUNGAN)
    {
        $hubspsi = $this->getData(
            $KUNJUNGAN,
            'medicalrecord.kondisi_sosial',
            [
                    'TIDAK_ADA_KELAINAN',
                    'MARAH',
                    'CEMAS',
                    'TAKUT',
                    'SEDIH',
                    'BUNUH_DIRI',
                    'LAINNYA',

                    'STATUS_MENTAL',
                    'MASALAH_PERILAKU',
                    'PERILAKU_KEKERASAN_DIALAMI_SEBELUMNYA',

                    'HUBUNGAN_PASIEN_DENGAN_KELUARGA',
                    'TEMPAT_TINGGAL',
                    'TEMPAT_TINGGAL_LAINNYA',

                    'KEBIASAAN_BERIBADAH_TERATUR',
                    'NILAI_KEPERCAYAAN',
                    'NILAI_KEPERCAYAAN_DESKRIPSI',
                    'PENGAMBIL_KEPUTUSAN_DALAM_KELUARGA',

                    'PENGHASILAN_PERBULAN',
            ]
        );

        return response()->json([
            'status' => true,
            'data' => $hubspsi
        ]);
    }

    function simpanHubunganStatusPsikososial(Request $request, $KUNJUNGAN)
    {
        DB::beginTransaction();

        try {

            DB::table('medicalrecord.kondisi_sosial')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    // Status Psikologi
                    'TIDAK_ADA_KELAINAN' => $request->tak ? 1 : 0,
                    'MARAH'              => $request->marah ? 1 : 0,
                    'CEMAS'              => $request->cemas ? 1 : 0,
                    'TAKUT'              => $request->takut ? 1 : 0,
                    'SEDIH'              => $request->sedih ? 1 : 0,
                    'BUNUH_DIRI'         => $request->bundir ? 1 : 0,
                    'LAINNYA'            => $request->pse_lain ?? '',

                    // Status Mental
                    'STATUS_MENTAL'                         => $request->sm ?? 0,
                    'MASALAH_PERILAKU'                      => $request->sm2_lain ?? '',
                    'PERILAKU_KEKERASAN_DIALAMI_SEBELUMNYA' => $request->sm3_lain ?? '',

                    // Hubungan Sosial
                    'HUBUNGAN_PASIEN_DENGAN_KELUARGA' => $request->hub ?? 0,
                    'TEMPAT_TINGGAL'                  => $request->tinggal ?? 0,
                    'TEMPAT_TINGGAL_LAINNYA'          => $request->tinggal_lain ?? '',

                    // Spiritual
                    'KEBIASAAN_BERIBADAH_TERATUR' => $request->kbt ?? 0,
                    'NILAI_KEPERCAYAAN'           => $request->nk ?? 0,
                    'NILAI_KEPERCAYAAN_DESKRIPSI' => $request->nk_lain ?? '',
                    'PENGAMBIL_KEPUTUSAN_DALAM_KELUARGA' => $request->pk ?? '',

                    // Ekonomi
                    'PENGHASILAN_PERBULAN' => $request->hasil ?? 0,

                    // Audit
                    'OLEH'    => auth()->id(),
                    'STATUS'  => 1,
                    'TANGGAL' => now(),
                ]
            );

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Hubungan Status Psikososial berhasil diperbarui.'
            ], 200);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Data Hubungan Status Psikososial gagal disimpan.',
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
                    'KESEDIAAN' => $request->edu_1,

                    'HAMBATAN' => $request->edu_2,
                    'HAMBATAN_PENDENGARAN' => $request->hb_edu_1,
                    'HAMBATAN_PENGLIHATAN' => $request->hb_edu_2,
                    'HAMBATAN_KOGNITIF' => $request->hb_edu_3,
                    'HAMBATAN_FISIK' => $request->hb_edu_4,
                    'HAMBATAN_BUDAYA' => $request->hb_edu_5,
                    'HAMBATAN_EMOSI' => $request->hb_edu_6,
                    'HAMBATAN_BAHASA' => $request->hb_edu_7,
                    'HAMBATAN_LAINNYA' => $request->hb_edu_8,

                    'PENERJEMAH' => $request->edu_3,
                    'BAHASA' => $request->edu_3_lain,

                    // Kebutuhan Edukasi
                    'EDUKASI_DIAGNOSA' => $request->kb_edu_1,
                    'EDUKASI_REHAB_MEDIK' => $request->kb_edu_2,
                    'EDUKASI_HKP' => $request->kb_edu_3,

                    'EDUKASI_PEMBERIAN_INFORMED_CONSENT' => $request->kb_edu_4,

                    'EDUKASI_CUCI_TANGAN' => $request->kb_edu_5,
                    'EDUKASI_PERENCANAAN_PULANG' => $request->kb_edu_6,

                    'EDUKASI_OBAT' => $request->kb_edu_7,
                    'EDUKASI_NYERI' => $request->kb_edu_8,
                    'EDUKASI_HAK_BERPARTISIPASI' => $request->kb_edu_9,

                    'EDUKASI_PENUNDAAN_PELAYANAN' => $request->kb_edu_10,
                    'EDUKASI_BAHAYA_MEROKO' => $request->kb_edu_11,

                    'EDUKASI_NUTRISI' => $request->kb_edu_13,
                    'EDUKASI_PENGGUNAAN_ALAT' => $request->kb_edu_14,
                    'EDUKASI_PROSEDURE_PENUNJANG' => $request->kb_edu_15,

                    'EDUKASI_KELAMBATAN_PELAYANAN' => $request->kb_edu_16,
                    'EDUKASI_RUJUKAN_PASIEN' => $request->kb_edu_17,

                    // Lainnya
                    'STATUS_LAIN' => $request->kb_edu_12,
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

    function getALPH($KUNJUNGAN)
    {
        $aplh = $this->getData(
            $KUNJUNGAN,
            'medicalrecord.sirmed_aktivitas_latihan_personal_hygiene',
            [
                'TINGKAT_KETERGANTUNGAN',
                'MANDI',
                'GANTI_PAKAIAN',
                'KERAMAS',
                'GOSOK_GIGI',
                'MEMOTONG_KUKU',
                'TIDUR_SIANG',
                'TIDUR_MALAM',
            ]
        );

        return response()->json([
            'status' => true,
            'data' => $aplh
        ]);
    }

    function simpanALPH(Request $request, $KUNJUNGAN)
    {
        DB::beginTransaction();

        try {

            DB::table('medicalrecord.sirmed_aktivitas_latihan_personal_hygiene')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ ?? $KUNJUNGAN
                ],
                [
                    // Edukasi awal
                    'TINGKAT_KETERGANTUNGAN' => $request->ph_tk,
                    'MANDI' => $request->ph_m,
                    'GANTI_PAKAIAN' => $request->ph_gp,
                    'KERAMAS' => $request->ph_k,
                    'GOSOK_GIGI' => $request->ph_gg,
                    'MEMOTONG_KUKU' => $request->ph_mk,
                    'TIDUR_SIANG' => $request->ph_ts,
                    'TIDUR_MALAM' => $request->ph_tm,

                    'TANGGAL' => now(),
                    'OLEH' => auth()->id(),
                    'STATUS' => 1,
                ]
            );

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Aktivitas dan Latihan, Personal Hygiene berhasil diperbarui.'
            ], 200);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Data Aktivitas dan Latihan, Personal Hygiene gagal disimpan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    function getDischargePlanning($KUNJUNGAN)
    {
        $dc1 = $this->getData(
            $KUNJUNGAN,
            'medicalrecord.discharge_planning_faktor_risiko',
            [
                'PASIEN_TINGGAL_SENDIRI',
                'PASIEN_KHAWATIR_KETIKA_DIRUMAH',
                'PASIEN_TAK_ADA_YANG_MERAWAT',
                'PASIEN_DILANTAI_ATAS',
                'PERAWATAN_LANJUTAN_PASIEN',
                'PENGAJUAN_PENDAMPINGAN_PASIEN',
            ]
        );

        $dc2 = $this->getData(
            $KUNJUNGAN,
            'medicalrecord.discharge_planning_skrining',
            [
                'PASIEN_PULANG',
                'PASIEN_MENGAJUKAN',
                'TIDAK_ADA_KRITERIA',

                'PERAWATAN_LANJUTAN_MEDIS',
                'PLM_KATETER_URIN',
                'PLM_NGT',
                'PLM_TRAECHOSTOMY',
                'PLM_COLOSTOMY',
                'KEBUTUHAN_PELAYANAN_BERKELANJUTAN_LAINNYA',

                'KEBUTUHAN_PELAYANAN_BERKELANJUTAN_KPB',
                'KPB_RAWAT_LUKA',
                'KPB_HIV',
                'KPB_TB',
                'KPB_DM',
                'KPB_DM_TERAPI_INSULIN',
                'KPB_STROKE',
                'KPB_PPOK',
                'KPB_CKD',
                'KPB_PASIEN_KEMO',
                'KEBUTUHAN_PELAYANAN_BERKELANJUTAN_LAINNYA',

                'PENGGUNAAN_ALAT_MEDIS_PAM',
                'PAM_KATETER_URIN',
                'PAM_NGT',
                'PAM_TRAECHOSTOMY',
                'PAM_COLOSTOMY',
                'PAM_LAINNYA',

                'SKRINING_LANJUTAN',
                'SKRINING',
            ]
        );

        return response()->json([
            'status' => true,

            'data' => [
                'dc1' => $dc1,
                'dc2' => $dc2,
            ]
        ]);
    }

    function simpanDischargePlanning(Request $request, $KUNJUNGAN)
    {
        DB::beginTransaction();

        try {

            // ==========================================
            // DISCHARGE PLANNING - FAKTOR RISIKO
            // ==========================================
            DB::table('medicalrecord.discharge_planning_faktor_risiko')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'PASIEN_TINGGAL_SENDIRI'          => $request->input('dp_1', 0) ?? 0,
                    'PASIEN_KHAWATIR_KETIKA_DIRUMAH'  => $request->input('dp_2', 0) ?? 0,
                    'PASIEN_TAK_ADA_YANG_MERAWAT'     => $request->input('dp_3', 0) ?? 0,
                    'PASIEN_DILANTAI_ATAS'             => $request->input('dp_4', 0) ?? 0,
                    // 'PERAWATAN_LANJUTAN_PASIEN'        => $request->input('dp_5', 0) ?? 0,
                    'PENGAJUAN_PENDAMPINGAN_PASIEN'    => 0,

                    'TANGGAL'                           => now(),
                    'OLEH'                              => auth()->id(),
                    'STATUS'                            => 1,
                ]
            );

            // ==========================================
            // DISCHARGE PLANNING - SKRINING
            // ==========================================
            DB::table('medicalrecord.discharge_planning_skrining')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    // ------------------------------------------
                    // Kriteria dasar
                    // ------------------------------------------
                    'PASIEN_PULANG'                         => $request->input('dp_6', 0) ?? 0,
                    'PASIEN_MENGAJUKAN'                     => $request->input('dp_7', 0) ?? 0,
                    'TIDAK_ADA_KRITERIA'                    => $request->input('dp_8', 0) ?? 0,

                    // ------------------------------------------
                    // Pasien masih ada perawatan lanjutan / penggunaan alat medis yang dilakukan di rumah
                    // ------------------------------------------
                    'PERAWATAN_LANJUTAN_MEDIS'                  => $request->input('dp_5', 0) ?? 0,

                    'PLM_KATETER_URIN'                          => $request->boolean('dp_5_1') ? 1 : 0,
                    'PLM_TRAECHOSTOMY'                          => $request->boolean('dp_5_2') ? 1 : 0,
                    'PLM_NGT'                                   => $request->boolean('dp_5_3') ? 1 : 0,
                    'PLM_COLOSTOMY'                             => $request->boolean('dp_5_4') ? 1 : 0,
                    'KEBUTUHAN_PELAYANAN_BERKELANJUTAN_LAINNYA' => $request->input('dp_5_lain', '') ?? '',

                    // ------------------------------------------
                    // Kebutuhan Pelayanan Berkelanjutan (KPB)
                    // ------------------------------------------
                    'KEBUTUHAN_PELAYANAN_BERKELANJUTAN_KPB' => $request->input('dp_9', 0) ?? 0,

                    'KPB_RAWAT_LUKA'                        => $request->boolean('dp_9_1') ? 1 : 0,
                    'KPB_TB'                                => $request->boolean('dp_9_2') ? 1 : 0,
                    'KPB_DM_TERAPI_INSULIN'                 => $request->boolean('dp_9_3') ? 1 : 0,
                    'KPB_PPOK'                              => $request->boolean('dp_9_4') ? 1 : 0,
                    'KPB_PASIEN_KEMO'                       => $request->boolean('dp_9_5') ? 1 : 0,
                    'KPB_HIV'                               => $request->boolean('dp_9_6') ? 1 : 0,
                    'KPB_DM'                                => $request->boolean('dp_9_7') ? 1 : 0,
                    'KPB_STROKE'                            => $request->boolean('dp_9_8') ? 1 : 0,
                    'KPB_CKD'                               => $request->boolean('dp_9_9') ? 1 : 0,

                    // Lainnya pada "Perawatan lanjutan pasien"
                    // dp_5_lain berasal dari field "Jika Ada, sebutkan"
                    'KEBUTUHAN_PELAYANAN_BERKELANJUTAN_LAINNYA' => $request->input('dp_5_lain', '') ?? '',

                    // ------------------------------------------
                    // Penggunaan alat medis / bantu
                    // ------------------------------------------
                    'PENGGUNAAN_ALAT_MEDIS_PAM'             => $request->input('dp_10', 0) ?? 0,

                    'PAM_KATETER_URIN'                      => $request->boolean('dp_10_1') ? 1 : 0,
                    'PAM_TRAECHOSTOMY'                      => $request->boolean('dp_10_2') ? 1 : 0,
                    'PAM_NGT'                               => $request->boolean('dp_10_3') ? 1 : 0,
                    'PAM_COLOSTOMY'                         => $request->boolean('dp_10_4') ? 1 : 0,
                    'PAM_LAINNYA'                           => $request->input('dp_10_lain', '') ?? '',

                    // ------------------------------------------
                    // Skrining lanjutan
                    // ------------------------------------------
                    'SKRINING_LANJUTAN'                     => $request->input('dp_11', 0) ?? 0,

                    // 1 = Konsul MPP
                    // 2 = Edukasi
                    'SKRINING'                              => $request->input('dp_11_skrining', 0) ?? 0,

                    'TANGGAL'                               => now(),
                    'OLEH'                                  => auth()->id(),
                    'STATUS'                                => 1,
                ]
            );

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Discharge Planning berhasil diperbarui.'
            ], 200);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Data Discharge Planning gagal disimpan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    function getTataLaksanaTerapi($KUNJUNGAN)
    {
        $data = $this->getData(
            $KUNJUNGAN,
            'medicalrecord.sirmed_tata_laksana_terapi',
            [
                'DESKRIPSI',
            ]
        );

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    function simpanTataLaksanaTerapi(Request $request, $KUNJUNGAN)
    {
        DB::beginTransaction();

        try {

            DB::table('medicalrecord.sirmed_tata_laksana_terapi')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ ?? $KUNJUNGAN
                ],
                [
                    'DESKRIPSI' => $request->tatalaksana_terapi,

                    'TANGGAL' => now(),
                    'OLEH' => auth()->id(),
                    'STATUS' => 1,
                ]
            );

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Tata Laksana Terapi berhasil diperbarui.'
            ], 200);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Data Tata Laksana Terapi gagal disimpan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    function getTargetTerapi($KUNJUNGAN)
    {
        $data = $this->getData(
            $KUNJUNGAN,
            'medicalrecord.sirmed_target_terapi',
            [
                'DESKRIPSI',
            ]
        );

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    function simpanTargetTerapi(Request $request, $KUNJUNGAN)
    {
        DB::beginTransaction();

        try {

            DB::table('medicalrecord.sirmed_target_terapi')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ ?? $KUNJUNGAN
                ],
                [
                    'DESKRIPSI' => $request->target_terapi,

                    'TANGGAL' => now(),
                    'OLEH' => auth()->id(),
                    'STATUS' => 1,
                ]
            );

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Target Terapi berhasil diperbarui.'
            ], 200);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Data Target Terapi gagal disimpan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    function getRencanaKonsultasi($KUNJUNGAN)
    {
        $data = $this->getData(
            $KUNJUNGAN,
            'medicalrecord.sirmed_rencana_konsultasi',
            [
                'DESKRIPSI',
            ]
        );

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    function simpanRencanaKonsultasi(Request $request, $KUNJUNGAN)
    {
        DB::beginTransaction();

        try {

            DB::table('medicalrecord.sirmed_rencana_konsultasi')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ ?? $KUNJUNGAN
                ],
                [
                    'DESKRIPSI' => $request->rencana_konsultasi,

                    'TANGGAL' => now(),
                    'OLEH' => auth()->id(),
                    'STATUS' => 1,
                ]
            );

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Rencana Konsultasi berhasil diperbarui.'
            ], 200);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Data Rencana Konsultasi gagal disimpan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    function getKriteriaPulang($KUNJUNGAN)
    {
        $aplh = $this->getData(
            $KUNJUNGAN,
            'medicalrecord.sirmed_kriteria_pulang',
            [
                'KRITERIA_PULANG',
                'HARI',
                'KARENA',
            ]
        );

        return response()->json([
            'status' => true,
            'data' => $aplh
        ]);
    }

    function simpanKriteriaPulang(Request $request, $KUNJUNGAN)
    {
        DB::beginTransaction();

        try {

            DB::table('medicalrecord.sirmed_kriteria_pulang')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ ?? $KUNJUNGAN
                ],
                [
                    // Edukasi awal
                    'KRITERIA_PULANG' => $request->kp_plr,
                    'HARI' => $request->kp_plr_hari,
                    'KARENA' => $request->kp_plr_karena,

                    'TANGGAL' => now(),
                    'OLEH' => auth()->id(),
                    'STATUS' => 1,
                ]
            );

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Kriteria Pulang berhasil diperbarui.'
            ], 200);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Data Kriteria Pulang gagal disimpan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    function getMasalahKeperawatanRI($KUNJUNGAN)
    {
        $data = $this->getData(
            $KUNJUNGAN,
            'medicalrecord.masalah_keperawatan',
            [
                'BERSIHAN_JALAN_NAFAS_TIDAK_EFEKTIF',
                'GANGGUAN_PERTUKARAN_GAS',
                'GANGGUAN_VENTILASI_SPONTAN',
                'POLA_NYERI_TIDAK_EFEKTIF',
                'GANGGUAN_SIRKULASI_SPONTAN',
                'PENURUNAN_CURAH_JANTUNG',
                'PERFUSI_PERIFER_TIDAK_EFEKTIF',
                'TERMOREGULASI_TIDAK_EFEKTIF',
                'RESIKO_PERFUSI_GASTROINTESTINAL_TIDAK_EFEKTIF',
                'RESIKO_PERDARAHAN',
                'DEFISIT_NUTRISI',
                'DIARE',
                'KETIDAKSTABILAN_KADAR_GLUKOSA_DARAH',
                'RESIKO_KETIDAKSEIMBANGAN_CAIRAN',
                'RESIKO_KETIDAKSEIMBANGAN_ELEKTROLIT',
                'RESIKO_SYOK',
                'DISFUNGSI_MOTILITAS_GASTROINTESTINAL',
                'GANGGUAN_ELIMINASI_URINE',
                'KONSTIPASI',
                'RETENSI_URINE',
                'GANGGUAN_MOBILITAS_FISIK',
                'GANGGUAN_POLA_TIDUR',
                'INTOLERANSI_AKTIVITAS',
                'GANGGUAN_MENELAN',
                'GANGGUAN_RASA_NYAMAN',
                'NAUSEA',
                'NYERI_AKUT',
                'NYERI_KRONIS',
                'ANSIETAS',
                'GANGGUAN_PERSEPSI_SENSORI',
                'DEFISIT_PERAWATAN_DIRI',
                'DEFISIT_PENGETAHUAN',
                'GANGGUAN_INTERAKSI_SOSIAL',
                'GANGGUAN_KOMUNIKASI_VERBAL',
                'GANGGUAN_INTEGRITAS_KULIT_JARINGAN',
                'HIPERTERMI',
                'HIPOTERMI',
                'PERLAMBATAN_PEMULIHAN_PASCA_BEDAH',
                'RESIKO_ALERGI',
                'RESIKO_CIDERA',
                'RESIKO_INFEKSI',
                'HIPERVOLEMIA',
                'HIPOVOLEMIA',
                'BERAT_BADAN_LEBIH',
                'CEMAS',
            ]
        );

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    function simpanMasalahKeperawatanRI(Request $request, $KUNJUNGAN)
    {
        DB::beginTransaction();

        try {

            $kolom = [
                'BERSIHAN_JALAN_NAFAS_TIDAK_EFEKTIF',
                'GANGGUAN_PERTUKARAN_GAS',
                'GANGGUAN_VENTILASI_SPONTAN',
                'POLA_NYERI_TIDAK_EFEKTIF',
                'GANGGUAN_SIRKULASI_SPONTAN',
                'PENURUNAN_CURAH_JANTUNG',
                'PERFUSI_PERIFER_TIDAK_EFEKTIF',
                'TERMOREGULASI_TIDAK_EFEKTIF',
                'RESIKO_PERFUSI_GASTROINTESTINAL_TIDAK_EFEKTIF',
                'RESIKO_PERDARAHAN',
                'DEFISIT_NUTRISI',
                'DIARE',
                'KETIDAKSTABILAN_KADAR_GLUKOSA_DARAH',
                'RESIKO_KETIDAKSEIMBANGAN_CAIRAN',
                'RESIKO_KETIDAKSEIMBANGAN_ELEKTROLIT',
                'RESIKO_SYOK',
                'DISFUNGSI_MOTILITAS_GASTROINTESTINAL',
                'GANGGUAN_ELIMINASI_URINE',
                'KONSTIPASI',
                'RETENSI_URINE',
                'GANGGUAN_MOBILITAS_FISIK',
                'GANGGUAN_POLA_TIDUR',
                'INTOLERANSI_AKTIVITAS',
                'GANGGUAN_MENELAN',
                'GANGGUAN_RASA_NYAMAN',
                'NAUSEA',
                'NYERI_AKUT',
                'NYERI_KRONIS',
                'ANSIETAS',
                'GANGGUAN_PERSEPSI_SENSORI',
                'DEFISIT_PERAWATAN_DIRI',
                'DEFISIT_PENGETAHUAN',
                'GANGGUAN_INTERAKSI_SOSIAL',
                'GANGGUAN_KOMUNIKASI_VERBAL',
                'GANGGUAN_INTEGRITAS_KULIT_JARINGAN',
                'HIPERTERMI',
                'HIPOTERMI',
                'PERLAMBATAN_PEMULIHAN_PASCA_BEDAH',
                'RESIKO_ALERGI',
                'RESIKO_CIDERA',
                'RESIKO_INFEKSI',
                'HIPERVOLEMIA',
                'HIPOVOLEMIA',
                'BERAT_BADAN_LEBIH',
                'CEMAS',
            ];

            $data = [
                'KUNJUNGAN' => $request->NOKUNJ ?? $KUNJUNGAN,
                'OLEH'      => auth()->id(),
                'STATUS'    => 1,
                'TANGGAL'   => now(),
            ];

            foreach ($kolom as $index => $namaKolom) {
                $nomor = $index + 1;

                $data[$namaKolom] = $request->input(
                    "dmk_{$nomor}",
                    0
                );
            }

            DB::table('medicalrecord.masalah_keperawatan')
                ->updateOrInsert(
                    [
                        'KUNJUNGAN' => $data['KUNJUNGAN'],
                    ],
                    $data
                );

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Daftar Masalah Keperawatan berhasil diperbarui.',
            ], 200);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Data Masalah Keperawatan gagal disimpan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function getPemeriksaanFisikRI($KUNJUNGAN)
    {
        try {

            $pernafasan = DB::table('medicalrecord.sirmed_sistem_pernafasan')
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->where('STATUS', 1)
                ->first();

            $kardiovaskuler = DB::table('medicalrecord.sirmed_sistem_kardiovaskuler')
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->where('STATUS', 1)
                ->first();

            $persyarafan = DB::table('medicalrecord.sirmed_sistem_persyarafan')
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->where('STATUS', 1)
                ->first();

            $perkemihan = DB::table('medicalrecord.sirmed_sistem_perkemihan')
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->where('STATUS', 1)
                ->first();

            $pencernaan = DB::table('medicalrecord.sirmed_sistem_pencernaan')
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->where('STATUS', 1)
                ->first();

            $muskuloskeletal = DB::table('medicalrecord.sirmed_sistem_muskuloskeletal_integumen')
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->where('STATUS', 1)
                ->first();

            $endokrin = DB::table('medicalrecord.sirmed_sistem_endokrin')
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->where('STATUS', 1)
                ->first();

            /*
            |--------------------------------------------------------------------------
            | Mapping database -> nama field form
            |--------------------------------------------------------------------------
            */

            $data = [

                // ==========================================================
                // SISTEM PERNAFASAN
                // ==========================================================
                'pfn_sn'          => $pernafasan->SESAK_NAFAS ?? null,
                'pfn_sn_jenis'    => $pernafasan->SESAK_NAFAS_JENIS ?? null,
                'pfn_ba'          => $pernafasan->BATUK ?? null,
                'pfn_nwn'         => $pernafasan->NYERI_WAKTU_NAFAS ?? null,

                'pfn_swa'         => $pernafasan->SEKRET_WARNA ?? null,
                'pfn_sju'         => $pernafasan->SEKRET_JUMLAH ?? null,
                'pfn_sba'         => $pernafasan->SEKRET_BAU ?? null,
                'pfn_sje'         => $pernafasan->SEKRET_JENIS ?? null,

                'pfn_irn'         => $pernafasan->IRAMA_NAFAS ?? null,
                'pfn_irnj'        => $pernafasan->IRAMA_NAFAS_JENIS ?? null,

                'pfn_snaf'        => $pernafasan->SUARA_NAFAS ?? null,
                'pfn_snaf_lain'   => $pernafasan->SUARA_NAFAS_LAIN ?? null,

                'pfn_abn'         => $pernafasan->ALAT_BANTU_NAFAS ?? null,
                'pfn_abn_jenis'   => $pernafasan->ALAT_BANTU_NAFAS_JENIS ?? null,
                'pfn_abn_flow'    => $pernafasan->ALAT_BANTU_NAFAS_FLOW ?? null,


                // ==========================================================
                // SISTEM KARDIOVASKULER
                // ==========================================================
                'psk_nd'          => $kardiovaskuler->NYERI_DADA ?? null,
                'psk_pul'         => $kardiovaskuler->PULSASI ?? null,
                'psk_pul_detik'   => $kardiovaskuler->PULSASI_CRT ?? null,

                'psk_ak'          => $kardiovaskuler->AKRAL ?? null,
                'psk_ak_lain'     => $kardiovaskuler->AKRAL_LAIN ?? null,

                'psk_ij'          => $kardiovaskuler->IRAMA_JANTUNG ?? null,

                'psk_pd'          => $kardiovaskuler->PERDARAHAN ?? null,
                'psk_pd_lain'     => $kardiovaskuler->PERDARAHAN_LOKASI ?? null,

                'psk_lain'        => $kardiovaskuler->LAIN_LAIN ?? null,


                // ==========================================================
                // SISTEM PERSYARAFAN
                // ==========================================================
                'psp_pul'         => $persyarafan->SKLERA ?? null,
                'psp_pup'         => $persyarafan->PUPIL ?? null,
                'psp_pgl'         => $persyarafan->PENGLIHATAN ?? null,
                'psp_pdg'         => $persyarafan->PENDENGARAN ?? null,
                'psp_pcm'         => $persyarafan->PENCIUMAN ?? null,
                'psp_kjg'         => $persyarafan->KEJANG ?? null,

                'psp_ist'         => $persyarafan->ISTIRAHAT_TIDUR ?? null,
                'psp_gt'          => $persyarafan->GANGGUAN_TIDUR ?? null,

                'psp_lain'        => $persyarafan->LAIN_LAIN ?? null,


                // ==========================================================
                // SISTEM PERKEMIHAN
                // ==========================================================
                'psh_kag'         => $perkemihan->KEBERSIHAN_AREA_GENITAL ?? null,

                'psh_kkm'         => $perkemihan->KANDUNG_KEMIH_MEMBESAR ?? null,

                'psh_bak'         => $perkemihan->BAK ?? null,
                'psh_bak_anuria'  => $perkemihan->BAK_ANURIA ?? null,
                'psh_bak_warna'   => $perkemihan->BAK_WARNA ?? null,

                'psh_nyt'         => $perkemihan->NYERI_TEKAN ?? null,

                'psh_abk'         => $perkemihan->ALAT_BANTU_KATETER ?? null,
                'psh_abk_tgl'     => $perkemihan->SEJAK_TANGGAL ?? null,

                'psh_lain'        => $perkemihan->LAIN_LAIN ?? null,


                // ==========================================================
                // SISTEM PENCERNAAN
                // ==========================================================
                'psc_mul'         => $pencernaan->MULUT ?? null,
                'psc_muk'         => $pencernaan->MUKOSA ?? null,
                'psc_jdi'         => $pencernaan->JENIS_DIET ?? null,

                'psc_nfm'         => $pencernaan->NAFSU_MAKAN ?? null,
                'psc_nfm_frek'    => $pencernaan->NAFSU_MAKAN_FREKUENSI ?? null,

                'psc_mua'         => $pencernaan->MUAL ?? null,
                'psc_mun'         => $pencernaan->MUNTAH ?? null,

                'psc_teng'        => $pencernaan->TENGGOROKAN ?? null,
                'psc_abd'         => $pencernaan->ABDOMEN ?? null,

                'psc_nyt'         => $pencernaan->NYERI_TEKAN ?? null,
                'psc_kon'         => $pencernaan->KONSISTENSI ?? null,

                'psc_per'         => $pencernaan->PERISTALTIK ?? null,
                'psc_bab'         => $pencernaan->BAB ?? null,

                'psc_lain'        => $pencernaan->LAIN_LAIN ?? null,


                // ==========================================================
                // SISTEM MUSKULOSKELETAL & INTEGUMEN
                // ==========================================================
                'psmi_pse'        => $muskuloskeletal->PERGERAKAN_SENDI ?? null,
                'psmi_kes'        => $muskuloskeletal->KELAINAN_EKSTREMITAS ?? null,
                'psmi_ktb'        => $muskuloskeletal->KELAINAN_TULANG_BELAKANG ?? null,

                'psmi_fra'        => $muskuloskeletal->FRAKTUR ?? null,

                'psmi_gip'        => $muskuloskeletal->GIPS_SPALK_TRAKSI ?? null,
                'psmi_ksy'        => $muskuloskeletal->KOMPARTEMEN_SYNDROME ?? null,

                'psmi_kul'        => $muskuloskeletal->KULIT ?? null,

                'psmi_tur'        => $muskuloskeletal->TURGOR ?? null,

                'psmi_oed'        => $muskuloskeletal->OEDEMA ?? null,
                'psmi_oed_lok'    => $muskuloskeletal->OEDEMA_LOKASI ?? null,

                'psmi_lde'        => $muskuloskeletal->LUKA_DEKUBITUS ?? null,

                'psmi_lain'       => $muskuloskeletal->LAIN_LAIN ?? null,


                // ==========================================================
                // SISTEM ENDOKRIN
                // ==========================================================
                'psr_pkt'        => $endokrin->PERBESARAN_KELENJAR_TIROID ?? null,
                'psr_pkg'        => $endokrin->PEMBESARAN_KELENJAR_GETAH_BENING ?? null,
                'psr_hpo'        => $endokrin->HIPOGLIKEMIA ?? null,
                'psr_hpi'        => $endokrin->HIPERGLIKEMIA ?? null,
            ];

            return response()->json([
                'status' => true,
                'data'   => $data
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Gagal mengambil data Pemeriksaan Fisik.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function simpanPemeriksaanFisikRI(Request $request, $KUNJUNGAN)
    {
        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | USER
            |--------------------------------------------------------------------------
            */
            $oleh = auth()->user()->ID ?? auth()->id();


            /*
            |--------------------------------------------------------------------------
            | SISTEM PERNAFASAN
            |--------------------------------------------------------------------------
            */
            DB::table('medicalrecord.sirmed_sistem_pernafasan')->updateOrInsert(
                [
                    'KUNJUNGAN' => $KUNJUNGAN
                ],
                [
                    'SESAK_NAFAS'             => $request->input('pfn_sn'),
                    'SESAK_NAFAS_JENIS'       => $request->input('pfn_sn_jenis'),

                    'BATUK'                   => $request->input('pfn_ba'),
                    'NYERI_WAKTU_NAFAS'       => $request->input('pfn_nwn'),

                    'SEKRET_WARNA'            => $request->input('pfn_swa'),
                    'SEKRET_JUMLAH'           => $request->input('pfn_sju'),
                    'SEKRET_BAU'              => $request->input('pfn_sba'),
                    'SEKRET_JENIS'            => $request->input('pfn_sje'),

                    'IRAMA_NAFAS'             => $request->input('pfn_irn'),
                    'IRAMA_NAFAS_JENIS'       => $request->input('pfn_irnj'),

                    'SUARA_NAFAS'             => $request->input('pfn_snaf'),
                    'SUARA_NAFAS_LAIN'        => $request->input('pfn_snaf_lain'),

                    'ALAT_BANTU_NAFAS'        => $request->input('pfn_abn'),
                    'ALAT_BANTU_NAFAS_JENIS'  => $request->input('pfn_abn_jenis'),
                    'ALAT_BANTU_NAFAS_FLOW'   => $request->input('pfn_abn_flow'),

                    'TANGGAL'                 => now(),
                    'OLEH'                    => $oleh,
                    'STATUS'                  => 1,
                ]
            );


            /*
            |--------------------------------------------------------------------------
            | SISTEM KARDIOVASKULER
            |--------------------------------------------------------------------------
            */
            DB::table('medicalrecord.sirmed_sistem_kardiovaskuler')->updateOrInsert(
                [
                    'KUNJUNGAN' => $KUNJUNGAN
                ],
                [
                    'NYERI_DADA'       => $request->input('psk_nd'),

                    'PULSASI'          => $request->input('psk_pul'),
                    'PULSASI_CRT'      => $request->input('psk_pul_detik'),

                    'AKRAL'            => $request->input('psk_ak'),
                    'AKRAL_LAIN'       => $request->input('psk_ak_lain'),

                    'IRAMA_JANTUNG'    => $request->input('psk_ij'),

                    'PERDARAHAN'       => $request->input('psk_pd'),
                    'PERDARAHAN_LOKASI'=> $request->input('psk_pd_lain'),

                    'LAIN_LAIN'        => $request->input('psk_lain'),

                    'TANGGAL'          => now(),
                    'OLEH'             => $oleh,
                    'STATUS'           => 1,
                ]
            );


            /*
            |--------------------------------------------------------------------------
            | SISTEM PERSYARAFAN
            |--------------------------------------------------------------------------
            */
            DB::table('medicalrecord.sirmed_sistem_persyarafan')->updateOrInsert(
                [
                    'KUNJUNGAN' => $KUNJUNGAN
                ],
                [
                    'SKLERA'          => $request->input('psp_pul'),
                    'PUPIL'           => $request->input('psp_pup'),
                    'PENGLIHATAN'     => $request->input('psp_pgl'),
                    'PENDENGARAN'     => $request->input('psp_pdg'),
                    'PENCIUMAN'       => $request->input('psp_pcm'),
                    'KEJANG'          => $request->input('psp_kjg'),

                    'ISTIRAHAT_TIDUR' => $request->input('psp_ist'),
                    'GANGGUAN_TIDUR'  => $request->input('psp_gt'),

                    'LAIN_LAIN'       => $request->input('psp_lain'),

                    'TANGGAL'         => now(),
                    'OLEH'            => $oleh,
                    'STATUS'          => 1,
                ]
            );


            /*
            |--------------------------------------------------------------------------
            | SISTEM PERKEMIHAN
            |--------------------------------------------------------------------------
            */
            DB::table('medicalrecord.sirmed_sistem_perkemihan')->updateOrInsert(
                [
                    'KUNJUNGAN' => $KUNJUNGAN
                ],
                [
                    'KEBERSIHAN_AREA_GENITAL' => $request->input('psh_kag'),

                    'KANDUNG_KEMIH_MEMBESAR'  => $request->input('psh_kkm'),

                    'BAK'                     => $request->input('psh_bak'),
                    'BAK_ANURIA'              => $request->input('psh_bak_anuria'),
                    'BAK_WARNA'               => $request->input('psh_bak_warna'),

                    'NYERI_TEKAN'             => $request->input('psh_nyt'),

                    'ALAT_BANTU_KATETER'      => $request->input('psh_abk'),
                    'SEJAK_TANGGAL'           => $request->input('psh_abk_tgl'),

                    'LAIN_LAIN'               => $request->input('psh_lain'),

                    'TANGGAL'                 => now(),
                    'OLEH'                    => $oleh,
                    'STATUS'                  => 1,
                ]
            );


            /*
            |--------------------------------------------------------------------------
            | SISTEM PENCERNAAN
            |--------------------------------------------------------------------------
            */
            DB::table('medicalrecord.sirmed_sistem_pencernaan')->updateOrInsert(
                [
                    'KUNJUNGAN' => $KUNJUNGAN
                ],
                [
                    'MULUT'                    => $request->input('psc_mul'),
                    'MUKOSA'                   => $request->input('psc_muk'),
                    'JENIS_DIET'               => $request->input('psc_jdi'),

                    'NAFSU_MAKAN'              => $request->input('psc_nfm'),
                    'NAFSU_MAKAN_FREKUENSI'    => $request->input('psc_nfm_frek'),

                    'MUAL'                     => $request->input('psc_mua'),
                    'MUNTAH'                   => $request->input('psc_mun'),

                    'TENGGOROKAN'              => $request->input('psc_teng'),
                    'ABDOMEN'                  => $request->input('psc_abd'),

                    'NYERI_TEKAN'              => $request->input('psc_nyt'),
                    'KONSISTENSI'              => $request->input('psc_kon'),

                    'PERISTALTIK'              => $request->input('psc_per'),
                    'BAB'                      => $request->input('psc_bab'),

                    'LAIN_LAIN'                => $request->input('psc_lain'),

                    'TANGGAL'                  => now(),
                    'OLEH'                     => $oleh,
                    'STATUS'                   => 1,
                ]
            );


            /*
            |--------------------------------------------------------------------------
            | SISTEM MUSKULOSKELETAL & INTEGUMEN
            |--------------------------------------------------------------------------
            */
            DB::table('medicalrecord.sirmed_sistem_muskuloskeletal_integumen')->updateOrInsert(
                [
                    'KUNJUNGAN' => $KUNJUNGAN
                ],
                [
                    'PERGERAKAN_SENDI'        => $request->input('psmi_pse'),

                    'KELAINAN_EKSTREMITAS'    => $request->input('psmi_kes'),
                    'KELAINAN_TULANG_BELAKANG'=> $request->input('psmi_ktb'),

                    'FRAKTUR'                 => $request->input('psmi_fra'),

                    'GIPS_SPALK_TRAKSI'       => $request->input('psmi_gip'),
                    'KOMPARTEMEN_SYNDROME'    => $request->input('psmi_ksy'),

                    'KULIT'                   => $request->input('psmi_kul'),

                    'TURGOR'                  => $request->input('psmi_tur'),

                    'OEDEMA'                  => $request->input('psmi_oed'),
                    'OEDEMA_LOKASI'           => $request->input('psmi_oed_lok'),

                    'LUKA_DEKUBITUS'          => $request->input('psmi_lde'),

                    'LAIN_LAIN'               => $request->input('psmi_lain'),

                    'TANGGAL'                 => now(),
                    'OLEH'                    => $oleh,
                    'STATUS'                  => 1,
                ]
            );


            /*
            |--------------------------------------------------------------------------
            | SISTEM ENDOKRIN
            |--------------------------------------------------------------------------
            */
            DB::table('medicalrecord.sirmed_sistem_endokrin')->updateOrInsert(
                [
                    'KUNJUNGAN' => $KUNJUNGAN
                ],
                [
                    'PERBESARAN_KELENJAR_TIROID'
                        => $request->input('psr_pkt'),

                    'PEMBESARAN_KELENJAR_GETAH_BENING'
                        => $request->input('psr_pkg'),

                    'HIPOGLIKEMIA'
                        => $request->input('psr_hpo'),

                    'HIPERGLIKEMIA'
                        => $request->input('psr_hpi'),

                    'TANGGAL'   => now(),
                    'OLEH'      => $oleh,
                    'STATUS'    => 1,
                ]
            );


            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Pemeriksaan Fisik berhasil disimpan.'
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Pemeriksaan Fisik gagal disimpan.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    function getAnamnesisRI($KUNJUNGAN)
    {
        $anam1 = $this->getData(
            $KUNJUNGAN,
            'medicalrecord.anamnesis_diperoleh',
            [
                'AUTOANAMNESIS',
                'ALLOANAMNESIS',
            ]
        );

        $anam2 = $this->getData(
            $KUNJUNGAN,
            'medicalrecord.keluhan_utama',
            [
                'DESKRIPSI',
            ]
        );

        $anam3 = $this->getData(
            $KUNJUNGAN,
            'medicalrecord.anamnesis',
            [
                'DESKRIPSI',
            ]
        );

        $anam4 = $this->getData(
            $KUNJUNGAN,
            'medicalrecord.rpp',
            [
                'DESKRIPSI',
            ]
        );

        $anam5 = $this->getData(
            $KUNJUNGAN,
            'medicalrecord.riwayat_penyakit_keluarga',
            [
                'HIPERTENSI',
                'DIABETES_MELITUS',
                'PENYAKIT_JANTUNG',
                'ASMA',
                'LAINNYA',
            ]
        );

        $anam6 = $this->getData(
            $KUNJUNGAN,
            'medicalrecord.sirmed_status_reproduksi',
            [
                'RIWAYAT_TUMBUH_KEMBANG',
                'RIWAYAT_KELAHIRAN',
                'USIA_KEHAMILAN',
                'PERSALINAN',
                'PERSALINAN_LAINNYA',
            ]
        );

        $anam = array_merge(
            (array) $anam1,
            (array) $anam2,
            (array) $anam3,
            (array) $anam4,
            (array) $anam5,
            (array) $anam6
        );

        return response()->json([
            'status' => true,

            'data' => [
                'anam1' => $anam1,
                'anam2' => $anam2,
                'anam3' => $anam3,
                'anam4' => $anam4,
                'anam5' => $anam5,
                'anam6' => $anam6,
            ]
        ]);

        // return response()->json([
        //     'status' => true,
        //     'data' => $anam
        // ]);
    }

    function simpanAnamnesisRI(Request $request, $KUNJUNGAN)
    {
        // ==========================================
        // DATA KUNJUNGAN
        // ==========================================
        $getDataKunjungan = DB::table('pendaftaran.kunjungan as pk')
            ->join(
                'pendaftaran.pendaftaran as pp',
                'pp.NOMOR',
                '=',
                'pk.NOPEN'
            )
            ->select(
                'pp.NORM',
                'pp.NOMOR as NOPEN'
            )
            ->where('pk.NOMOR', $request->NOKUNJ)
            ->first();

        if (!$getDataKunjungan) {
            return response()->json([
                'status'  => false,
                'message' => 'Data kunjungan tidak ditemukan.'
            ], 404);
        }

        DB::beginTransaction();

        try {

            // ==========================================
            // AUTO / ALLO
            // TABEL ANAMNESIS_DIPEROLEH
            // ==========================================
            if ($request->has('anam')) {

                DB::table('medicalrecord.anamnesis_diperoleh')
                    ->updateOrInsert(
                        [
                            'KUNJUNGAN' => $request->NOKUNJ
                        ],
                        [
                            'AUTOANAMNESIS' => ($request->anam == 1) ? 1 : 0,
                            'ALLOANAMNESIS' => ($request->anam == 2) ? 1 : 0,
                            'DARI'          => "",
                            'OLEH'          => auth()->id(),
                            'STATUS'        => 1,
                            'TANGGAL'       => now()
                        ]
                    );
            }


            // ==========================================
            // KELUHAN UTAMA
            // TABEL KELUHAN_UTAMA
            // ==========================================
            if (
                $request->has('ku') &&
                $request->filled('ku')
            ) {

                DB::table('medicalrecord.keluhan_utama')
                    ->updateOrInsert(
                        [
                            'KUNJUNGAN' => $request->NOKUNJ
                        ],
                        [
                            'DESKRIPSI'    => $request->ku,
                            'SNOMED_CT_ID' => 0,
                            'TANGGAL'      => now(),
                            'OLEH'         => auth()->id(),
                            'STATUS'       => 1,
                        ]
                    );
            }


            // ==========================================
            // RIWAYAT PENYAKIT SEKARANG
            // TABEL ANAMNESIS
            // ==========================================
            if (
                $request->has('rps') &&
                $request->filled('rps')
            ) {

                DB::table('medicalrecord.anamnesis')
                    ->updateOrInsert(
                        [
                            'KUNJUNGAN'  => $request->NOKUNJ,
                            'PENDAFTARAN' => $getDataKunjungan->NOPEN
                        ],
                        [
                            'SNOMED_CT_ID' => 0,
                            'DESKRIPSI'    => $request->rps,
                            'TANGGAL'      => now(),
                            'OLEH'         => auth()->id(),
                            'STATUS'       => 1,
                        ]
                    );
            }


            // ==========================================
            // RIWAYAT PENYAKIT DAHULU
            // TABEL RPP
            // ==========================================
            if (
                $request->has('rpd') &&
                $request->filled('rpd')
            ) {

                DB::table('medicalrecord.rpp')
                    ->updateOrInsert(
                        [
                            'KUNJUNGAN' => $request->NOKUNJ
                        ],
                        [
                            'SNOMED_CT_ID' => 0,
                            'DESKRIPSI'    => $request->rpd,
                            'TANGGAL'      => now(),
                            'OLEH'         => auth()->id(),
                            'STATUS'       => 1,
                        ]
                    );
            }


            // ==========================================
            // RIWAYAT PENYAKIT KELUARGA
            // TABEL RIWAYAT_PENYAKIT_KELUARGA
            // ==========================================
            if (
                $request->has('rpk_h') ||
                $request->has('rpk_d') ||
                $request->has('rpk_p') ||
                $request->has('rpk_a') ||
                (
                    $request->has('rpk_lain') &&
                    $request->filled('rpk_lain')
                )
            ) {

                DB::table('medicalrecord.riwayat_penyakit_keluarga')
                    ->updateOrInsert(
                        [
                            'KUNJUNGAN' => $request->NOKUNJ
                        ],
                        [
                            'HIPERTENSI'       => $request->rpk_h ?? 0,
                            'DIABETES_MELITUS' => $request->rpk_d ?? 0,
                            'PENYAKIT_JANTUNG' => $request->rpk_p ?? 0,
                            'ASMA'             => $request->rpk_a ?? 0,
                            'LAINNYA'          => $request->rpk_lain,
                            'TANGGAL'          => now(),
                            'OLEH'             => auth()->id(),
                            'STATUS'           => 1,
                        ]
                    );
            }


            // ==========================================
            // RIWAYAT KELAHIRAN / DATA ANAK
            // TABEL SIRMED_STATUS_REPRODUKSI
            // ==========================================
            if (
                $request->has('anam_rtk') ||
                $request->has('anam_k') ||
                $request->has('anam_uk') ||
                $request->has('anam_p') ||
                (
                    $request->has('anam_p_lain') &&
                    $request->filled('anam_p_lain')
                )
            ) {

                DB::table('medicalrecord.sirmed_status_reproduksi')
                    ->updateOrInsert(
                        [
                            'KUNJUNGAN' => $request->NOKUNJ
                        ],
                        [
                            'RIWAYAT_TUMBUH_KEMBANG' => $request->anam_rtk,
                            'RIWAYAT_KELAHIRAN'      => $request->anam_k,
                            'USIA_KEHAMILAN'         => $request->anam_uk,
                            'PERSALINAN'             => $request->anam_p,
                            'PERSALINAN_LAINNYA'    => $request->anam_p_lain,
                            'TANGGAL'                => now(),
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
                'message' => 'Anamnesis berhasil diperbarui.'
            ], 200);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Data Anamnesis gagal disimpan.',
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

    function getPemeriksaanObsgyn($KUNJUNGAN)
    {
        $data = $this->getData(
            $KUNJUNGAN,
            'medicalrecord.sirmed_pemeriksaan_obsgyn',
            [
                'FISIK',
                'OBSTETRI',
                'GYNEKOLOGI',
            ]
        );

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    function simpanPemeriksaanObsgyn(Request $request, $KUNJUNGAN)
    {
        DB::beginTransaction();

        try {

            DB::table('medicalrecord.sirmed_pemeriksaan_obsgyn')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ ?? $KUNJUNGAN
                ],
                [
                    'PENDAFTARAN'  => DB::table('pendaftaran.kunjungan')->where('NOMOR', $request->NOKUNJ ?? $KUNJUNGAN)->value('NOPEN'),
                    'FISIK' => $request->pfisik,
                    'OBSTETRI' => $request->pobs,
                    'GYNEKOLOGI' => $request->pgyn,

                    'TANGGAL' => now(),
                    'OLEH' => auth()->id(),
                    'STATUS' => 1,
                ]
            );

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Pemeriksaan Obsgyn berhasil diperbarui.'
            ], 200);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Data Pemeriksaan Obsgyn gagal disimpan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    function getPenunjangLain($KUNJUNGAN)
    {

        $eeg = DB::table('medicalrecord.pemeriksaan_eeg')
            ->where('KUNJUNGAN', $KUNJUNGAN)
            ->first([
                'HASIL',
                'KESIMPULAN',
            ]);

        $penunjang = DB::table('medicalrecord.sirmed_pemeriksaan_penunjang_lain')
            ->where('KUNJUNGAN', $KUNJUNGAN)
            ->first([
                'DESKRIPSI',
            ]);

        return response()->json([
            'status' => true,
            'data' => (object) [
                'KUNJUNGAN'  => $KUNJUNGAN,
                'HASIL'      => $eeg->HASIL ?? null,
                'KESIMPULAN' => $eeg->KESIMPULAN ?? null,
                'DESKRIPSI'  => $penunjang->DESKRIPSI ?? null,
            ]
        ]);
    }

    function simpanPenunjangLain(Request $request, $KUNJUNGAN)
    {
        DB::beginTransaction();

        try {

            DB::table('medicalrecord.pemeriksaan_eeg')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ ?? $KUNJUNGAN
                ],
                [
                    'HASIL' => $request->usg_hasil,
                    'KESIMPULAN' => $request->usg_kesimpulan,

                    'OLEH' => auth()->id(),
                    'STATUS' => 1,
                ]
            );

            DB::table('medicalrecord.sirmed_pemeriksaan_penunjang_lain')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ ?? $KUNJUNGAN
                ],
                [
                    'PENDAFTARAN'  => DB::table('pendaftaran.kunjungan')->where('NOMOR', $request->NOKUNJ ?? $KUNJUNGAN)->value('NOPEN'),
                    'DESKRIPSI' => $request->penlain,

                    'TANGGAL' => now(),
                    'OLEH' => auth()->id(),
                    'STATUS' => 1,
                ]
            );

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Pemeriksaan Penunjang Lain berhasil diperbarui.'
            ], 200);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Data Pemeriksaan Penunjang Lain gagal disimpan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function getRiwayatNikah($kunjungan)
    {
        $riwayat_nikah = DB::table('medicalrecord.sirmed_riwayat_nikah as rn')
            ->select('rn.*')
            ->where('rn.KUNJUNGAN', $kunjungan)
            ->where('rn.STATUS', 1)
            ->get();

        $data = [
            'kunjungan' => $kunjungan,
            'riw_nikah' => $riwayat_nikah,
        ];

        return response()->json($data);
    }

    public function simpanRiwayatNikah(Request $request, $KUNJUNGAN)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'tahun' => ['required'],
            ],
            [
                'tahun.required' => 'Lama Pernikahan wajib diisi.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        DB::table('medicalrecord.sirmed_riwayat_nikah')->insert([
            'KUNJUNGAN'         => $KUNJUNGAN,
            'LAMA_NIKAH'        => $request->tahun,
            'KETERANGAN'        => $request->ket,
            'OLEH'              => auth()->id(),
            'TANGGAL'           => now(),
            'STATUS'            => 1,
        ]);

        return response()->json(['message' => 'Data riwayat pernikahan berhasil disimpan.'], 200);
    }

    public function hapusRiwayatNikah($KUNJUNGAN, $ID)
    {
        DB::table('medicalrecord.sirmed_riwayat_nikah')
            ->where('KUNJUNGAN', $KUNJUNGAN)
            ->where('ID', $ID)
            ->update(['STATUS' => 0]);

        return response()->json(['message' => 'Data riwayat pernikahan berhasil dihapus.'], 200);
    }

    public function getRiwayatKb($kunjungan)
    {
        $riwayat_kb = DB::table('medicalrecord.sirmed_riwayat_kb_menstruasi as rkm')
            ->where('rkm.KUNJUNGAN', $kunjungan)
            ->where('rkm.STATUS', 1)
            ->first();

        $data = [
            'kunjungan' => $kunjungan,
            'riw_kb'    => $riwayat_kb,
        ];

        return response()->json($data);
    }


    public function simpanRiwayatKb(Request $request, $KUNJUNGAN)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'kb_suntik'          => ['nullable'],
                'kb_iud'             => ['nullable'],
                'kb_pil'             => ['nullable'],
                'kb_kondom'          => ['nullable'],
                'kb_kalender'        => ['nullable'],
                'kb_mow'             => ['nullable'],
                'kb_mop'             => ['nullable'],
                'kb_implan'          => ['nullable'],

                'kb_keluhan'         => ['nullable'],
                'menstruasi_teratur' => ['nullable'],
                'menstruasi_keluhan' => ['nullable'],
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {

            // ==========================================================
            // DATA YANG AKAN DISIMPAN
            // ==========================================================

            $data = [
                'KB_SUNTIK'   => $request->kb_suntik == 1 ? 1 : 0,
                'KB_IUD'      => $request->kb_iud == 1 ? 1 : 0,
                'KB_PIL'      => $request->kb_pil == 1 ? 1 : 0,
                'KB_KONDOM'   => $request->kb_kondom == 1 ? 1 : 0,
                'KB_KALENDER' => $request->kb_kalender == 1 ? 1 : 0,
                'KB_MOW'      => $request->kb_mow == 1 ? 1 : 0,
                'KB_MOP'      => $request->kb_mop == 1 ? 1 : 0,
                'KB_IMPLAN'   => $request->kb_implan == 1 ? 1 : 0,

                'KB_KELUHAN'         => $request->kb_keluhan,

                'MENSTRUASI_TERATUR' => $request->menstruasi_teratur,

                'MENSTRUASI_KELUHAN' => $request->menstruasi_keluhan,

                'OLEH'               => auth()->id(),
                'TANGGAL'            => now(),
                'STATUS'             => 1,
            ];


            // ==========================================================
            // SIMPAN DENGAN UPDATE OR INSERT
            // ==========================================================

            $existing = DB::table('medicalrecord.sirmed_riwayat_kb_menstruasi')
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->first();

            if ($existing) {

                DB::table('medicalrecord.sirmed_riwayat_kb_menstruasi')
                    ->where('ID', $existing->ID)
                    ->update($data);

            } else {

                $data['KUNJUNGAN'] = $KUNJUNGAN;

                DB::table('medicalrecord.sirmed_riwayat_kb_menstruasi')
                    ->insert($data);
            }


            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Data riwayat KB dan menstruasi berhasil disimpan.'
            ], 200);

        } catch (\Throwable $e) {

            DB::rollBack();

            \Log::error('Gagal menyimpan riwayat KB dan menstruasi', [
                'KUNJUNGAN' => $KUNJUNGAN,
                'error'     => $e->getMessage(),
                'line'      => $e->getLine(),
                'file'      => $e->getFile(),
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'Data riwayat KB dan menstruasi gagal disimpan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function hapusRiwayatKb($KUNJUNGAN, $ID)
    {
        DB::table('medicalrecord.sirmed_riwayat_kb_menstruasi')
            ->where('KUNJUNGAN', $KUNJUNGAN)
            ->where('ID', $ID)
            ->update([
                'STATUS' => 0
            ]);

        return response()->json([
            'message' => 'Data riwayat KB dan menstruasi berhasil dihapus.'
        ], 200);
    }

    public function getPemeriksaanFisikObs($KUNJUNGAN)
    {
        try {

            $data = DB::table('medicalrecord.sirmed_pemeriksaan_fisik_obsgyn')
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->where('STATUS', 1)
                ->first();


            return response()->json([
                'status' => true,
                'data'   => $data
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Gagal mengambil data Pemeriksaan Fisik Obsgyn.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function simpanPemeriksaanFisikObs(Request $request, $KUNJUNGAN)
    {
        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | USER
            |--------------------------------------------------------------------------
            */

            $oleh = auth()->user()->ID ?? auth()->id();


            /*
            |--------------------------------------------------------------------------
            | PEMERIKSAAN FISIK OBSGYN
            |--------------------------------------------------------------------------
            */

            DB::table('medicalrecord.sirmed_pemeriksaan_fisik_obsgyn')
                ->updateOrInsert(
                    [
                        'KUNJUNGAN' => $KUNJUNGAN
                    ],
                    [

                        // ==========================================================
                        // MATA
                        // ==========================================================

                        'MATA'              => $request->input('mata'),
                        'MATA_KETERANGAN'   => $request->input('mata_keterangan'),


                        // ==========================================================
                        // SKLERA
                        // ==========================================================

                        'SKLERA'            => $request->input('sklera'),


                        // ==========================================================
                        // KEPALA
                        // ==========================================================

                        'KEPALA'            => $request->input('kepala'),
                        'KEPALA_KETERANGAN' => $request->input('kepala_keterangan'),


                        // ==========================================================
                        // TELINGA
                        // ==========================================================

                        'TELINGA'            => $request->input('telinga'),
                        'TELINGA_KETERANGAN' => $request->input('telinga_keterangan'),


                        // ==========================================================
                        // HIDUNG
                        // ==========================================================

                        'HIDUNG'            => $request->input('hidung'),
                        'HIDUNG_KETERANGAN' => $request->input('hidung_keterangan'),


                        // ==========================================================
                        // TENGGOROKAN
                        // ==========================================================

                        'TENGGOROKAN'            => $request->input('tenggorokan'),
                        'TENGGOROKAN_KETERANGAN' => $request->input('tenggorokan_keterangan'),


                        // ==========================================================
                        // LEHER
                        // ==========================================================

                        'LEHER'            => $request->input('leher'),
                        'LEHER_KETERANGAN' => $request->input('leher_keterangan'),


                        // ==========================================================
                        // DADA
                        // ==========================================================

                        'DADA'            => $request->input('dada'),
                        'DADA_KETERANGAN' => $request->input('dada_keterangan'),


                        // ==========================================================
                        // JANTUNG
                        // ==========================================================

                        'JANTUNG'            => $request->input('jantung'),
                        'JANTUNG_KETERANGAN' => $request->input('jantung_keterangan'),


                        // ==========================================================
                        // PARU
                        // ==========================================================

                        'PARU'            => $request->input('paru'),
                        'PARU_KETERANGAN' => $request->input('paru_keterangan'),


                        // ==========================================================
                        // ABDOMEN
                        // ==========================================================

                        'ABDOMEN'            => $request->input('abdomen'),
                        'ABDOMEN_KETERANGAN' => $request->input('abdomen_keterangan'),


                        // ==========================================================
                        // ANGGOTA GERAK ATAS
                        // ==========================================================

                        'ANGGOTA_GERAK_ATAS'
                            => $request->input('anggota_gerak_atas'),


                        // ==========================================================
                        // ANGGOTA GERAK BAWAH
                        // ==========================================================

                        'ANGGOTA_GERAK_BAWAH'
                            => $request->input('anggota_gerak_bawah'),


                        // ==========================================================
                        // AUDIT
                        // ==========================================================

                        'TANGGAL' => now(),
                        'OLEH'    => $oleh,
                        'STATUS'  => 1,
                    ]
                );


            DB::commit();


            return response()->json([
                'status'  => true,
                'message' => 'Pemeriksaan Fisik Obsgyn berhasil disimpan.'
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();


            return response()->json([
                'status'  => false,
                'message' => 'Pemeriksaan Fisik Obsgyn gagal disimpan.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function getPemeriksaanKhususObs($KUNJUNGAN)
    {
        try {

            $dataDb = DB::table('medicalrecord.sirmed_pemeriksaan_khusus_obsgyn')
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->where('STATUS', 1)
                ->first();

            if (!$dataDb) {
                return response()->json([
                    'status' => true,
                    'data'   => null
                ]);
            }

            $data = [

                // ==========================================================
                // DADA
                // ==========================================================
                'dada_1' => $dataDb->DADA_1 ?? 0,
                'dada_2' => $dataDb->DADA_2 ?? 0,
                'dada_3' => $dataDb->DADA_3 ?? 0,
                'dada_4' => $dataDb->DADA_4 ?? 0,
                'dada_5' => $dataDb->DADA_5 ?? 0,

                'kolostrum_keterangan'
                    => $dataDb->KOLOSTRUM_KETERANGAN ?? null,


                // ==========================================================
                // ABDOMEN - INSPEKSI
                // ==========================================================
                'abdomen_luka_bekas_op'
                    => $dataDb->ABDOMEN_LUKA_BEKAS_OP ?? 0,

                'abdomen_linea_alba'
                    => $dataDb->ABDOMEN_LINEA_ALBA ?? 0,

                'abdomen_linea_nigra'
                    => $dataDb->ABDOMEN_LINEA_NIGRA ?? 0,

                'abdomen_striae_livida'
                    => $dataDb->ABDOMEN_STRIAE_LIVIDA ?? 0,

                'abdomen_striae_albican'
                    => $dataDb->ABDOMEN_STRIAE_ALBICAN ?? 0,


                // ==========================================================
                // LEOPOLD
                // ==========================================================
                'leopold_1_tfu'
                    => $dataDb->LEOPOLD_1_TFU ?? null,

                'leopold_2'
                    => $dataDb->LEOPOLD_2 ?? null,

                'leopold_3'
                    => $dataDb->LEOPOLD_3 ?? null,

                'leopold_4'
                    => $dataDb->LEOPOLD_4 ?? null,


                // ==========================================================
                // AUSKULTASI
                // ==========================================================
                'djj'
                    => $dataDb->DJJ ?? null,

                'djj_kondisi'
                    => $dataDb->DJJ_KONDISI ?? null,


                // ==========================================================
                // HIS / KONTRAKSI
                // ==========================================================
                'his'
                    => $dataDb->HIS ?? null,

                'his_durasi'
                    => $dataDb->HIS_DURASI ?? null,

                'his_kekuatan'
                    => $dataDb->HIS_KEKUATAN ?? null,


                // ==========================================================
                // ANOGENITAL
                // ==========================================================
                'anogenital_darah'
                    => $dataDb->ANOGENITAL_DARAH ?? 0,

                'anogenital_lendir'
                    => $dataDb->ANOGENITAL_LENDIR ?? 0,

                'anogenital_air_ketuban'
                    => $dataDb->ANOGENITAL_AIR_KETUBAN ?? 0,

                'anogenital_lainnya'
                    => $dataDb->ANOGENITAL_LAINNYA ?? 0,

                'anogenital_lainnya_keterangan'
                    => $dataDb->ANOGENITAL_LAINNYA_KETERANGAN ?? null,


                // ==========================================================
                // LAIN-LAIN
                // ==========================================================
                'vagina_taucher'
                    => $dataDb->VAGINA_TAUCHER ?? null,

                'pemeriksaan_lain_lain'
                    => $dataDb->PEMERIKSAAN_LAIN_LAIN ?? null,
            ];

            return response()->json([
                'status' => true,
                'data'   => $data
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Gagal mengambil data Pemeriksaan Khusus.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function simpanPemeriksaanKhususObs(Request $request, $KUNJUNGAN)
    {
        DB::beginTransaction();

        try {

            $oleh = auth()->user()->ID ?? auth()->id();

            DB::table('medicalrecord.sirmed_pemeriksaan_khusus_obsgyn')
                ->updateOrInsert(
                    [
                        'KUNJUNGAN' => $KUNJUNGAN
                    ],
                    [

                        // ==================================================
                        // DADA
                        // ==================================================
                        'DADA_1'
                            => $request->input('dada_1', 0),

                        'DADA_2'
                            => $request->input('dada_2', 0),

                        'DADA_3'
                            => $request->input('dada_3', 0),

                        'DADA_4'
                            => $request->input('dada_4', 0),

                        'DADA_5'
                            => $request->input('dada_5', 0),

                        'KOLOSTRUM_KETERANGAN'
                            => $request->input('kolostrum_keterangan'),


                        // ==================================================
                        // ABDOMEN - INSPEKSI
                        // ==================================================
                        'ABDOMEN_LUKA_BEKAS_OP'
                            => $request->input('abdomen_luka_bekas_op', 0),

                        'ABDOMEN_LINEA_ALBA'
                            => $request->input('abdomen_linea_alba', 0),

                        'ABDOMEN_LINEA_NIGRA'
                            => $request->input('abdomen_linea_nigra', 0),

                        'ABDOMEN_STRIAE_LIVIDA'
                            => $request->input('abdomen_striae_livida', 0),

                        'ABDOMEN_STRIAE_ALBICAN'
                            => $request->input('abdomen_striae_albican', 0),


                        // ==================================================
                        // LEOPOLD
                        // ==================================================
                        'LEOPOLD_1_TFU'
                            => $request->input('leopold_1_tfu'),

                        'LEOPOLD_2'
                            => $request->input('leopold_2'),

                        'LEOPOLD_3'
                            => $request->input('leopold_3'),

                        'LEOPOLD_4'
                            => $request->input('leopold_4'),


                        // ==================================================
                        // AUSKULTASI
                        // ==================================================
                        'DJJ'
                            => $request->input('djj'),

                        'DJJ_KONDISI'
                            => $request->input('djj_kondisi'),


                        // ==================================================
                        // HIS / KONTRAKSI
                        // ==================================================
                        'HIS'
                            => $request->input('his'),

                        'HIS_DURASI'
                            => $request->input('his_durasi'),

                        'HIS_KEKUATAN'
                            => $request->input('his_kekuatan'),


                        // ==================================================
                        // ANOGENITAL
                        // ==================================================
                        'ANOGENITAL_DARAH'
                            => $request->input('anogenital_darah', 0),

                        'ANOGENITAL_LENDIR'
                            => $request->input('anogenital_lendir', 0),

                        'ANOGENITAL_AIR_KETUBAN'
                            => $request->input('anogenital_air_ketuban', 0),

                        'ANOGENITAL_LAINNYA'
                            => $request->input('anogenital_lainnya', 0),

                        'ANOGENITAL_LAINNYA_KETERANGAN'
                            => $request->input('anogenital_lainnya_keterangan'),


                        // ==================================================
                        // LAIN-LAIN
                        // ==================================================
                        'VAGINA_TAUCHER'
                            => $request->input('vagina_taucher'),

                        'PEMERIKSAAN_LAIN_LAIN'
                            => $request->input('pemeriksaan_lain_lain'),


                        // ==================================================
                        // AUDIT
                        // ==================================================
                        'TANGGAL' => now(),
                        'OLEH'    => $oleh,
                        'STATUS'  => 1,
                    ]
                );

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Pemeriksaan Khusus berhasil disimpan.'
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Pemeriksaan Khusus gagal disimpan.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function getRiwayatImunisasi($KUNJUNGAN)
    {
        try {

            $data = DB::table('medicalrecord.riwayat_tumbuh_kembang')
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->where('STATUS', 1)
                ->first();

            if (!$data) {
                return response()->json([
                    'status' => true,
                    'data'   => null
                ]);
            }

            return response()->json([
                'status' => true,
                'data'   => [
                    'imunisasi'     => $data->IMUNISASI ?? null,
                    'imunisasi_lain'=> $data->IMUNISASI_LAIN ?? null,
                ]
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Gagal mengambil data Riwayat Imunisasi.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function simpanRiwayatImunisasi(Request $request, $KUNJUNGAN)
    {
        DB::beginTransaction();

        try {

            $oleh = auth()->user()->ID ?? auth()->id();

            DB::table('medicalrecord.riwayat_tumbuh_kembang')->updateOrInsert(
                [
                    'KUNJUNGAN' => $KUNJUNGAN
                ],
                [
                    'IMUNISASI'      => $request->input('imunisasi'),
                    'IMUNISASI_LAIN' => $request->input('imunisasi_lain'),

                    'TANGGAL'        => now(),
                    'OLEH'           => $oleh,
                    'STATUS'         => 1,
                ]
            );

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Riwayat Imunisasi berhasil disimpan.'
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Riwayat Imunisasi gagal disimpan.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function getStatusObstetriNeonatus($KUNJUNGAN)
    {
        try {

            $obstetri = DB::table('medicalrecord.sirmed_status_obstetri')
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->where('STATUS', 1)
                ->first();

            $neonatus = DB::table('medicalrecord.sirmed_status_neonatus')
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->where('STATUS', 1)
                ->first();


            return response()->json([
                'status' => true,

                'obstetri' => $obstetri ? [
                    'so_umur_ibu'          => $obstetri->UMUR_IBU,
                    'so_g'                 => $obstetri->G,
                    'so_p'                 => $obstetri->P,
                    'so_a'                 => $obstetri->A,

                    'so_umur_kehamilan'    => $obstetri->UMUR_KEHAMILAN,

                    'so_komplikasi'        => $obstetri->KOMPLIKASI,
                    'so_komplikasi_ket'    => $obstetri->KOMPLIKASI_KET,

                    'so_gol_darah_ibu'     => $obstetri->GOL_DARAH_IBU,
                    'so_rh_ibu'            => $obstetri->RH_IBU,

                    'so_gol_darah_ayah'    => $obstetri->GOL_DARAH_AYAH,
                    'so_rh_ayah'           => $obstetri->RH_AYAH,
                    'so_gol_ayah_tidak'    => $obstetri->GOL_AYAH_TIDAK,

                    'so_kk_pecah_jam'      => $obstetri->KK_PECAH_JAM,
                ] : null,


                'neonatus' => $neonatus ? [
                    'sn_tanggal_lahir'          => $neonatus->TANGGAL_LAHIR,
                    'sn_jam_lahir'              => $neonatus->JAM_LAHIR,

                    'sn_jenis_kelamin'         => $neonatus->JENIS_KELAMIN,

                    'sn_bb_lahir'              => $neonatus->BB_LAHIR,
                    'sn_pb_lahir'              => $neonatus->PB_LAHIR,

                    'sn_lk'                    => $neonatus->LK,
                    'sn_ld'                    => $neonatus->LD,
                    'sn_lp'                    => $neonatus->LP,
                    'sn_lila'                  => $neonatus->LILA,

                    'sn_resusitasi_intubasi'   => $neonatus->RESUSITASI_INTUBASI,
                    'sn_resusitasi_pompa'      => $neonatus->RESUSITASI_POMPA,

                    'sn_berulang'              => $neonatus->BERULANG,

                    'sn_jenis_partus'          => $neonatus->JENIS_PARTUS,

                    'sn_indikasi'              => $neonatus->INDIKASI,
                ] : null,
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Gagal mengambil Status Obstetri dan Neonatus.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function simpanStatusObstetriNeonatus(
        Request $request,
        $KUNJUNGAN
    ) {
        DB::beginTransaction();

        try {

            $oleh = auth()->user()->ID ?? auth()->id();


            /*
            |--------------------------------------------------------------------------
            | STATUS OBSTETRI
            |--------------------------------------------------------------------------
            */

            DB::table('medicalrecord.sirmed_status_obstetri')
                ->updateOrInsert(
                    [
                        'KUNJUNGAN' => $KUNJUNGAN
                    ],
                    [
                        'UMUR_IBU'
                            => $request->input('so_umur_ibu'),

                        'G'
                            => $request->input('so_g'),

                        'P'
                            => $request->input('so_p'),

                        'A'
                            => $request->input('so_a'),

                        'UMUR_KEHAMILAN'
                            => $request->input('so_umur_kehamilan'),

                        'KOMPLIKASI'
                            => $request->input('so_komplikasi'),

                        'KOMPLIKASI_KET'
                            => $request->input('so_komplikasi_ket'),

                        'GOL_DARAH_IBU'
                            => $request->input('so_gol_darah_ibu'),

                        'RH_IBU'
                            => $request->input('so_rh_ibu'),

                        'GOL_DARAH_AYAH'
                            => $request->input('so_gol_darah_ayah'),

                        'RH_AYAH'
                            => $request->input('so_rh_ayah'),

                        'GOL_AYAH_TIDAK'
                            => $request->input('so_gol_ayah_tidak', 0),

                        'KK_PECAH_JAM'
                            => $request->input('so_kk_pecah_jam'),

                        'TANGGAL' => now(),
                        'OLEH'    => $oleh,
                        'STATUS'  => 1,
                    ]
                );


            /*
            |--------------------------------------------------------------------------
            | STATUS NEONATUS
            |--------------------------------------------------------------------------
            */

            DB::table('medicalrecord.sirmed_status_neonatus')
                ->updateOrInsert(
                    [
                        'KUNJUNGAN' => $KUNJUNGAN
                    ],
                    [
                        'TANGGAL_LAHIR'
                            => $request->input('sn_tanggal_lahir'),

                        'JAM_LAHIR'
                            => $request->input('sn_jam_lahir'),

                        'JENIS_KELAMIN'
                            => $request->input('sn_jenis_kelamin'),

                        'BB_LAHIR'
                            => $request->input('sn_bb_lahir'),

                        'PB_LAHIR'
                            => $request->input('sn_pb_lahir'),

                        'LK'
                            => $request->input('sn_lk'),

                        'LD'
                            => $request->input('sn_ld'),

                        'LP'
                            => $request->input('sn_lp'),

                        'LILA'
                            => $request->input('sn_lila'),

                        'RESUSITASI_INTUBASI'
                            => $request->input(
                                'sn_resusitasi_intubasi',
                                0
                            ),

                        'RESUSITASI_POMPA'
                            => $request->input(
                                'sn_resusitasi_pompa',
                                0
                            ),

                        'BERULANG'
                            => $request->input('sn_berulang'),

                        'JENIS_PARTUS'
                            => $request->input('sn_jenis_partus'),

                        'INDIKASI'
                            => $request->input('sn_indikasi'),

                        'TANGGAL' => now(),
                        'OLEH'    => $oleh,
                        'STATUS'  => 1,
                    ]
                );


            DB::commit();


            return response()->json([
                'status'  => true,
                'message' => 'Status Obstetri dan Neonatus berhasil disimpan.'
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Status Obstetri dan Neonatus gagal disimpan.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function getPenilaianAwalBayi($kunjungan)
    {
        $data = DB::table('medicalrecord.sirmed_penilaian_awal_bayi')
            ->where('KUNJUNGAN', $kunjungan)
            ->where('STATUS', 1)
            ->first();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function simpanPenilaianAwalBayi(Request $request, $kunjungan)
    {
        DB::beginTransaction();

        try {

            $oleh = auth()->user()->ID ?? auth()->id();


            // ======================================================
            // STATUS BAYI
            // ======================================================

            $statusBayi = $request->apgar_status_bayi;


            // ======================================================
            // DEFAULT
            // ======================================================

            $data = [

                'APGAR_STATUS_BAYI' => $statusBayi,

                // APGAR
                'APGAR_1_MENIT_DENYUT'       => $request->apgar_1_menit_denyut,
                'APGAR_1_MENIT_PERNAFASAN'   => $request->apgar_1_menit_pernafasan,
                'APGAR_1_MENIT_TONUS'        => $request->apgar_1_menit_tonus,
                'APGAR_1_MENIT_RANGSANG'     => $request->apgar_1_menit_rangsang,
                'APGAR_1_MENIT_WARNA'        => $request->apgar_1_menit_warna,
                'APGAR_TOTAL_1_MENIT'        => $request->apgar_total_1_menit,

                'APGAR_5_MENIT_DENYUT'       => $request->apgar_5_menit_denyut,
                'APGAR_5_MENIT_PERNAFASAN'   => $request->apgar_5_menit_pernafasan,
                'APGAR_5_MENIT_TONUS'        => $request->apgar_5_menit_tonus,
                'APGAR_5_MENIT_RANGSANG'     => $request->apgar_5_menit_rangsang,
                'APGAR_5_MENIT_WARNA'        => $request->apgar_5_menit_warna,
                'APGAR_TOTAL_5_MENIT'        => $request->apgar_total_5_menit,

                'APGAR_10_MENIT_DENYUT'      => $request->apgar_10_menit_denyut,
                'APGAR_10_MENIT_PERNAFASAN'  => $request->apgar_10_menit_pernafasan,
                'APGAR_10_MENIT_TONUS'       => $request->apgar_10_menit_tonus,
                'APGAR_10_MENIT_RANGSANG'    => $request->apgar_10_menit_rangsang,
                'APGAR_10_MENIT_WARNA'       => $request->apgar_10_menit_warna,
                'APGAR_TOTAL_10_MENIT'       => $request->apgar_total_10_menit,


                // RESUSITASI
                'APGAR_RESUSITASI'
                    => $request->apgar_resusitasi,

                'APGAR_LANGKAH_AWAL'
                    => $request->apgar_langkah_awal ?? 0,

                'APGAR_LANGKAH_AWAL_DETIK'
                    => $request->apgar_langkah_awal_detik,

                'APGAR_VTP'
                    => $request->apgar_vtp ?? 0,

                'APGAR_VTP_DETIK'
                    => $request->apgar_vtp_detik,

                'APGAR_KOMPRESI_DADA'
                    => $request->apgar_kompresi_dada ?? 0,

                'APGAR_KOMPRESI_DADA_DETIK'
                    => $request->apgar_kompresi_dada_detik,

                'APGAR_ETT'
                    => $request->apgar_ett ?? 0,

                'APGAR_RESUSITASI_DIHENTIKAN'
                    => $request->apgar_resusitasi_dihentikan ?? 0,

                'APGAR_RESUSITASI_DIHENTIKAN_MENIT'
                    => $request->apgar_resusitasi_dihentikan_menit,


                // UMUM
                'APGAR_TANGGAL'
                    => $request->apgar_tanggal,

                'APGAR_JAM'
                    => $request->apgar_jam,

                'APGAR_BB_SEKARANG'
                    => $request->apgar_bb_sekarang,

                'OLEH' => $oleh,
                'STATUS' => 1,
            ];


            // ======================================================
            // PENTING
            // JIKA BUGAR -> HAPUS SEMUA RESUSITASI
            // ======================================================

            if ($statusBayi === 'bugar') {

                $data['APGAR_RESUSITASI'] = null;

                $data['APGAR_LANGKAH_AWAL'] = 0;
                $data['APGAR_LANGKAH_AWAL_DETIK'] = null;

                $data['APGAR_VTP'] = 0;
                $data['APGAR_VTP_DETIK'] = null;

                $data['APGAR_KOMPRESI_DADA'] = 0;
                $data['APGAR_KOMPRESI_DADA_DETIK'] = null;

                $data['APGAR_ETT'] = 0;

                $data['APGAR_RESUSITASI_DIHENTIKAN'] = 0;
                $data['APGAR_RESUSITASI_DIHENTIKAN_MENIT'] = null;

            }


            // ======================================================
            // JIKA TIDAK BUGAR -> HAPUS SEMUA APGAR
            // ======================================================

            if ($statusBayi === 'tidak_bugar') {

                $data['APGAR_1_MENIT_DENYUT'] = null;
                $data['APGAR_1_MENIT_PERNAFASAN'] = null;
                $data['APGAR_1_MENIT_TONUS'] = null;
                $data['APGAR_1_MENIT_RANGSANG'] = null;
                $data['APGAR_1_MENIT_WARNA'] = null;
                $data['APGAR_TOTAL_1_MENIT'] = null;


                $data['APGAR_5_MENIT_DENYUT'] = null;
                $data['APGAR_5_MENIT_PERNAFASAN'] = null;
                $data['APGAR_5_MENIT_TONUS'] = null;
                $data['APGAR_5_MENIT_RANGSANG'] = null;
                $data['APGAR_5_MENIT_WARNA'] = null;
                $data['APGAR_TOTAL_5_MENIT'] = null;


                $data['APGAR_10_MENIT_DENYUT'] = null;
                $data['APGAR_10_MENIT_PERNAFASAN'] = null;
                $data['APGAR_10_MENIT_TONUS'] = null;
                $data['APGAR_10_MENIT_RANGSANG'] = null;
                $data['APGAR_10_MENIT_WARNA'] = null;
                $data['APGAR_TOTAL_10_MENIT'] = null;

            }


            // ======================================================
            // SIMPAN
            // ======================================================

            DB::table('medicalrecord.sirmed_penilaian_awal_bayi')
                ->updateOrInsert(
                    [
                        'KUNJUNGAN' => $kunjungan
                    ],
                    $data
                );


            DB::commit();


            return response()->json([
                'success' => true,
                'message' => 'Penilaian awal bayi berhasil disimpan'
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getPemeriksaanFisikNeo($KUNJUNGAN)
    {
        try {

            $data = DB::table('medicalrecord.sirmed_pemeriksaan_fisik_neonatus')
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->where('STATUS', 1)
                ->first();


            return response()->json([
                'status' => true,
                'data'   => $data
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Gagal mengambil data Pemeriksaan Fisik Neonatus.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    public function simpanPemeriksaanFisikNeo(Request $request, $KUNJUNGAN)
    {
        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | USER
            |--------------------------------------------------------------------------
            */

            $oleh = auth()->user()->ID ?? auth()->id();


            /*
            |--------------------------------------------------------------------------
            | PEMERIKSAAN FISIK NEONATUS
            |--------------------------------------------------------------------------
            */

            DB::table('medicalrecord.sirmed_pemeriksaan_fisik_neonatus')
                ->updateOrInsert(
                    [
                        'KUNJUNGAN' => $KUNJUNGAN
                    ],
                    [

                        // ==========================================================
                        // KEPALA
                        // ==========================================================

                        'BENTUK'
                            => $request->bentuk,

                        'SUTURAE'
                            => $request->suturae,

                        'FONTANELLA'
                            => $request->fontanella,

                        'MATA'
                            => $request->mata,

                        'HIDUNG'
                            => $request->hidung,

                        'CAPUT_SUCCEDANEUM'
                            => $request->caput_succedaneum,

                        'CEPHAL_HEMATOM'
                            => $request->cephal_hematom,

                        'TELINGA'
                            => $request->telinga,

                        'MULUT'
                            => $request->mulut,

                        'LEHER'
                            => $request->leher,


                        // ==========================================================
                        // PARU
                        // ==========================================================

                        'PARU'
                            => $request->paru,


                        // ==========================================================
                        // JANTUNG
                        // ==========================================================

                        'JANTUNG'
                            => $request->jantung,


                        // ==========================================================
                        // ABDOMEN
                        // ==========================================================

                        'ABDOMEN'
                            => $request->abdomen,


                        // ==========================================================
                        // EKSTREMITAS
                        // ==========================================================

                        'EKSTREMITAS'
                            => $request->ekstremitas,


                        // ==========================================================
                        // NEUROLOGI
                        // ==========================================================

                        'ROOTING'
                            => $request->rooting ? 1 : 0,

                        'SUCKING'
                            => $request->sucking ? 1 : 0,

                        'MORO'
                            => $request->moro ? 1 : 0,

                        'ASYMMETRIC_TONIC_NECK'
                            => $request->asymmetric_tonic_neck ? 1 : 0,

                        'BABINSKI'
                            => $request->babinski ? 1 : 0,

                        'MENGGENGGAM'
                            => $request->menggenggam ? 1 : 0,


                        // ==========================================================
                        // SUARA
                        // ==========================================================

                        'SUARA_DIAM'
                            => $request->suara_diam ? 1 : 0,

                        'SUARA_MERINTIH'
                            => $request->suara_merintih ? 1 : 0,

                        'SUARA_KUAT'
                            => $request->suara_kuat ? 1 : 0,


                        // ==========================================================
                        // KULIT
                        // ==========================================================

                        'IKRENIK'
                            => $request->kulit_ikrenik ? 1 : 0,

                        'KULIT_KETERANGAN'
                            => $request->kulit_keterangan,


                        // ==========================================================
                        // AUDIT
                        // ==========================================================

                        'TANGGAL' => now(),
                        'OLEH'    => $oleh,
                        'STATUS'  => 1,
                    ]
                );


            DB::commit();


            return response()->json([
                'status'  => true,
                'message' => 'Pemeriksaan Fisik Neonatus berhasil disimpan.'
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();


            return response()->json([
                'status'  => false,
                'message' => 'Pemeriksaan Fisik Neonatus gagal disimpan.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

}
