<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class ApiKonsulController extends Controller
{
    public function showKonsul($NOMOR)
    {
        // Query dari database pendaftaran.konsul
        $query1 = DB::table('pendaftaran.konsul AS kon')
            ->select(
                'kon.NOMOR',
                'kon.KUNJUNGAN',
                'kon.TANGGAL',
                'kon.DOKTER_ASAL',
                'kon.DOKTER_TUJUAN',
                'kon.ALASAN',
                'kon.PERMINTAAN_TINDAKAN',
                'kon.TUJUAN',
                'kon.OLEH',
                'kon.STATUS',
                'ru.DESKRIPSI AS NAMARUANGAN',
                DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'),
                DB::raw('NULL AS created_at'),
                DB::raw('NULL AS updated_at'),
                DB::raw('NULL AS deleted_at')
            )
            ->leftJoin('pendaftaran.kunjungan AS pk', 'pk.NOMOR', '=', 'kon.KUNJUNGAN')
            ->leftJoin('master.ruangan AS ru', 'ru.ID', '=', 'kon.TUJUAN')
            ->leftJoin('master.dokter AS dr', 'dr.ID', '=', 'pk.DPJP')
            ->where('kon.KUNJUNGAN', $NOMOR);

        // Query dari database simrspku_klaim.konsul
        $query2 = DB::table('simrspku_klaim.konsul AS kon')
            ->select(
                'kon.NOMOR',
                'kon.KUNJUNGAN',
                'kon.TANGGAL',
                'kon.DOKTER_ASAL',
                'kon.DOKTER_TUJUAN',
                'kon.ALASAN',
                'kon.PERMINTAAN_TINDAKAN',
                'kon.TUJUAN',
                'kon.OLEH',
                'kon.STATUS',
                'ru.DESKRIPSI AS NAMARUANGAN',
                DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'),
                'kon.created_at',
                'kon.updated_at',
                'kon.deleted_at'
            )
            ->leftJoin('pendaftaran.kunjungan AS pk', 'pk.NOMOR', '=', 'kon.KUNJUNGAN')
            ->leftJoin('master.ruangan AS ru', 'ru.ID', '=', 'kon.TUJUAN')
            ->leftJoin('master.dokter AS dr', 'dr.ID', '=', 'pk.DPJP')
            ->where('kon.KUNJUNGAN', $NOMOR);

        // Gabungkan kedua query
        $data = $query1->unionAll($query2)->get();

        return response()->json($data, 200);
    }
    public function masukKonsul($NOMOR)
    {
        $show = DB::table('pendaftaran.kunjungan AS pk')
            ->select(
                'pk.NOMOR AS KUNJUNGAN',
                'pp.NORM AS NORM',
                'pp.NOMOR AS NOPEN',
                'pk.RUANGAN AS RUANGAN',
                'pk.DPJP AS DPJP'
            )
            ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
            ->where('pk.NOMOR',$NOMOR)
            ->first();
        // print_r($show);
        // die();
        // Pastikan daftar kolom sama di kedua query

        $query1 = DB::table('pendaftaran.konsul AS kon')
            ->select(
                'kon.NOMOR',
                'kon.KUNJUNGAN',
                'kon.TANGGAL',
                'kon.DOKTER_ASAL',
                'kon.DOKTER_TUJUAN',
                'kon.ALASAN',
                'kon.PERMINTAAN_TINDAKAN',
                'kon.TUJUAN',
                'kon.OLEH',
                'kon.STATUS',
                'ru.DESKRIPSI AS NAMARUANGAN',
                'ruang.DESKRIPSI AS TUJUAN_NAMA',
                DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'),
                DB::raw('master.getNamaLengkapPegawai(mdr.NIP) AS DOKTER'),
            )
            ->leftJoin('pendaftaran.kunjungan AS pk','pk.NOMOR','=','kon.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
            ->leftJoin('master.ruangan AS ru','ru.ID','=','pk.RUANGAN')
            ->leftJoin('master.ruangan AS ruang','ruang.ID','=','kon.TUJUAN')
            ->leftJoin('master.dokter AS dr','dr.ID','=','pk.DPJP')
            ->leftJoin('master.dokter AS mdr','mdr.ID','=','kon.DOKTER_TUJUAN')
            ->where('pp.NORM', $show->NORM)
            ->where('kon.TUJUAN', $show->RUANGAN)
            ->where('kon.DOKTER_TUJUAN', $show->DPJP);

        $query2 = DB::table('simrspku_klaim.konsul AS kon')
            ->select(
                'kon.NOMOR',
                'kon.KUNJUNGAN',
                'kon.TANGGAL',
                'kon.DOKTER_ASAL',
                'kon.DOKTER_TUJUAN',
                'kon.ALASAN',
                'kon.PERMINTAAN_TINDAKAN',
                'kon.TUJUAN',
                'kon.OLEH',
                'kon.STATUS',
                'ru.DESKRIPSI AS NAMARUANGAN',
                'ruang.DESKRIPSI AS TUJUAN_NAMA',
                DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'),
                DB::raw('master.getNamaLengkapPegawai(mdr.NIP) AS DOKTER'),
            )
            ->leftJoin('pendaftaran.kunjungan AS pk','pk.NOMOR','=','kon.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
            ->leftJoin('master.ruangan AS ru','ru.ID','=','pk.RUANGAN')
            ->leftJoin('master.ruangan AS ruang','ruang.ID','=','kon.TUJUAN')
            ->leftJoin('master.dokter AS dr','dr.ID','=','pk.DPJP')
            ->leftJoin('master.dokter AS mdr','mdr.ID','=','kon.DOKTER_TUJUAN')
            ->where('pp.NORM', $show->NORM)
            ->where('kon.TUJUAN', $show->RUANGAN)
            ->where('kon.DOKTER_TUJUAN', $show->DPJP);

        // print_r($query2);
        // die();

        $data = $query1->unionAll($query2)->get();
        return response()->json($data, 200);
    }
    public function getJawabanKonsul($NOMOR)
    {
        // print_r($NOMOR);
        // die();
        $query1 = DB::table('pendaftaran.konsul AS kon')
            ->select(
                'kon.NOMOR',
                'jk.JAWABAN AS JAWABAN',
                'jk.ANJURAN AS ANJURAN',
                'jk.TANGGAL AS TANGGAL_JAWABAN',
                'dr.NIP AS KODE_DOKTER',
                DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS JAWABDOKTER')
            )
            ->leftJoin('pendaftaran.jawaban_konsul AS jk','jk.KONSUL_NOMOR','=','kon.NOMOR')
            ->leftJoin('master.dokter AS dr','dr.ID','=','jk.DOKTER')
            ->where('kon.NOMOR', $NOMOR);
        $query2 = DB::table('simrspku_klaim.konsul AS kon')
            ->select(
                'kon.NOMOR',
                'jk.JAWABAN AS JAWABAN',
                'jk.ANJURAN AS ANJURAN',
                'jk.TANGGAL AS TANGGAL_JAWABAN',
                'dr.NIP AS KODE_DOKTER',
                DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS JAWABDOKTER')
            )
            ->leftJoin('simrspku_klaim.jawaban_konsul AS jk','jk.KONSUL_NOMOR','=','kon.NOMOR')
            ->leftJoin('master.dokter AS dr','dr.ID','=','jk.DOKTER')
            ->where('kon.NOMOR', $NOMOR);
        // print_r($jawaban);
        // die();
        $data = $query1->unionAll($query2)->get();
        return response()->json($data, 200);
    }
    public function store(Request $request)
    {
        // print_r($request->all());
        // die();
        $validated = $request->validate([
            'alasan' => 'required|string',
            'permintaan' => 'required|string',
            'tujuan' => 'required|string',
            'dokter' => 'required|string',
            'kunjungan' => 'required|string',
        ]);
        $now = Carbon::now();

        $kunjungan = $request->input('kunjungan');

        // Hitung sudah berapa konsul untuk kunjungan ini
        $jumlahKonsul = DB::table('simrspku_klaim.konsul AS kon')
                ->where('kon.KUNJUNGAN', $kunjungan)
                ->count();

        // Nomor urut baru = jumlah konsul + 1
        $nomorUrut = $jumlahKonsul + 1;

        // Format nomor urut 2 digit, contoh "01", "12"
        $urutFormatted = str_pad($nomorUrut, 2, '0', STR_PAD_LEFT);

        // Gabungkan nomor kunjungan + nomor urut
        $nomorKonsul = $kunjungan . $urutFormatted;

        $asal = DB::table('pendaftaran.kunjungan AS pk')
            ->select(
                'pk.*'
            )
            ->where('pk.NOMOR', $kunjungan)
            ->first();

        // Simpan ke database
        DB::table('simrspku_klaim.konsul')->insert([
            'NOMOR' => $nomorKonsul,
            'KUNJUNGAN' => $request->input('kunjungan'),
            'TANGGAL' => $now,
            'DOKTER_ASAL' => $asal ? $asal->DPJP : null,
            'DOKTER_TUJUAN' => $request->input('dokter'),
            'ALASAN' => $request->input('alasan'),
            'PERMINTAAN_TINDAKAN' => $request->input('permintaan'),
            'TUJUAN' => $request->input('tujuan'),
            'OLEH' => $request->input('oleh'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return Response::json(array(
            'message' => 'Konsul Berhasil ditambahkan',
            'code' => 201,
        ));
    }
    public function listRuangan()
    {
        // print_r('bisa');
        // die();
        $data = DB::table('master.ruangan AS ru')
            ->select(
                'ru.*'
            )
            ->where(function ($q) {
                $q->where('ru.ID', 'LIKE', '1020101%')
                    ->orWhere('ru.ID', 'LIKE', '1020702%');
            })
            ->where('ru.STATUS', 1)
            ->get();
        // print_r($data);
        // die();
        return response()->json($data, 200);
    }

    public function dokterByRuangan($id)
    {
        $data = DB::table('master.dokter_ruangan AS mdr')
            ->select(
                'dok.ID AS ID',
                DB::raw('master.getNamaLengkapPegawai(dok.NIP) AS NAMADOKTER')
            )
            ->leftJoin('master.dokter AS dok','dok.ID','=','mdr.DOKTER')
            ->leftJoin('master.pegawai AS peg','peg.NIP','=','dok.NIP')
            ->where('mdr.STATUS', 1)
            ->where('dok.STATUS', 1)
            ->where('mdr.RUANGAN', $id)
            ->get();
        // print_r($data);
        // die();
        return response()->json($data, 200);
    }

    public function getJawabKonsul($nomor)
    {
        $jawaban = DB::table('simrspku_klaim.jawaban_konsul')
            ->where('KONSUL_NOMOR', $nomor)
            ->first();

        return response()->json([
            'success' => true,
            'data' => $jawaban
        ]);
    }

    public function simpanJawaban(Request $request)
    {
        // print_r($request->all());
        // die();
        $request->validate([
            'nomor' => 'required|string',
            'jawaban' => 'required|string',
            'anjuran' => 'nullable|string',
            'oleh' => 'required|integer'
        ]);

        $now = Carbon::now();
        $nomorKonsul = $request->input('nomor');

        $doktere = DB::table('simrspku_klaim.konsul')
            ->where('NOMOR', $nomorKonsul)
            ->first();

        if (!$doktere) {
            return response()->json([
                'success' => false,
                'message' => 'Data konsul tidak ditemukan'
            ], 404);
        }

        $existing = DB::table('simrspku_klaim.jawaban_konsul')
            ->where('KONSUL_NOMOR', $nomorKonsul)
            ->first();

        if ($existing) {
            // Update
            DB::table('simrspku_klaim.jawaban_konsul')
                ->where('KONSUL_NOMOR', $nomorKonsul)
                ->update([
                    'JAWABAN' => $request->input('jawaban'),
                    'ANJURAN' => $request->input('anjuran'),
                    'DOKTER' => $doktere->DOKTER_TUJUAN,
                    'OLEH' => $request->input('oleh'),
                    'updated_at' => $now,
                ]);
        } else {
            // Insert
            $urutFormatted = str_pad(1, 2, '0', STR_PAD_LEFT);
            $nomorBaru = $nomorKonsul . $urutFormatted;

            DB::table('simrspku_klaim.jawaban_konsul')->insert([
                'NOMOR' => $nomorBaru,
                'KONSUL_NOMOR' => $nomorKonsul,
                'TANGGAL' => $now,
                'JAWABAN' => $request->input('jawaban'),
                'ANJURAN' => $request->input('anjuran'),
                'DOKTER' => $doktere->DOKTER_TUJUAN,
                'OLEH' => $request->input('oleh'),
                'STATUS' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Jawaban konsul berhasil disimpan'
        ]);
    }
}
