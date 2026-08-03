<?php

namespace App\Http\Controllers\EMR\Form;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth, Storage;

class AddOnPengkajianController extends Controller
{
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

    function getRiwayatPemberianObat($kunjungan)
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

    function simpanRiwayatPemberianObat(Request $request, $KUNJUNGAN)
    {
        DB::table('medicalrecord.riwayat_pemberian_obat')->insert([
            'KUNJUNGAN'         => $KUNJUNGAN,
            'OBAT'              => $request->nama_obat,
            'DOSIS'             => $request->dosis,
            'FREKUENSI'         => $request->frekuensi,
            'RUTE'              => $request->rute,
            'LAMA_PENGGUNAAN'   => $request->lama,
            'OLEH'              => auth()->id(),
            'TANGGAL'           => now(),
            'STATUS'            => 1,
        ]);

        return response()->json(['message' => 'Data riwayat pemberian obat berhasil disimpan.'], 200);
    }

    function hapusRiwayatPenggunaanObat($KUNJUNGAN, $ID)
    {
        DB::table('medicalrecord.riwayat_pemberian_obat')
            ->where('KUNJUNGAN', $KUNJUNGAN)
            ->where('ID', $ID)
            ->update(['STATUS' => 0]);

        return response()->json(['message' => 'Data riwayat pemberian obat berhasil dihapus.'], 200);
    }
}
