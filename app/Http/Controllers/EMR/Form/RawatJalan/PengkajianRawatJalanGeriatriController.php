<?php

namespace App\Http\Controllers\EMR\Form\RawatJalan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth, Storage;

class PengkajianRawatJalanGeriatriController extends Controller
{
    function index($kunjungan)
    {
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

        $pasien = DB::table('pendaftaran.kunjungan AS pk')
            ->leftJoin('pendaftaran.pendaftaran AS pd', 'pd.NOMOR', '=', 'pk.NOPEN')
            ->leftJoin('master.pasien AS p', 'p.NORM', '=', 'pd.NORM')
            ->leftJoin('master.referensi AS ag', function ($join) {
                $join->on('ag.ID', '=', 'p.AGAMA')
                    ->where('ag.JENIS', '=', '1');
            })
            ->leftJoin('master.referensi AS kj', function ($join) {
                $join->on('kj.ID', '=', 'p.PEKERJAAN')
                    ->where('kj.JENIS', '=', '4');
            })
            ->leftJoin('master.dokter AS dok', 'dok.ID', '=', 'pk.DPJP')
            ->select('dok.ID', DB::raw('master.getNamaLengkapPegawai(dok.NIP) AS NAMADOKTER'), 'ag.DESKRIPSI AS AGAMA', 'kj.DESKRIPSI AS PEKERJAAN')
            ->where('pk.NOMOR', $kunjungan)
            ->first();

        // $frekuensi_obat = DB::table('master.frekuensi_aturan_resep')
        //         ->select('ID','FREKUENSI')
        //         ->where('STATUS',1)
        //         ->orderBy('ID','ASC')
        //         ->get();

        // $rute_obat = DB::table('master.referensi')
        //         ->select('ID','DESKRIPSI')
        //         ->where('JENIS',217)
        //         ->where('STATUS',1)
        //         ->orderBy('TABEL_ID','ASC')
        //         ->get();

        $jenis_alergi = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',180)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $kesadaran = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',179)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $data = [
            'kunjungan' => $kunjungan,
            'jenis_ruang' => $jenis_ruang,
            'jenis_perawatan' => $jenis_perawatan,
            'pasien' => $pasien,
            'jenis_alergi' => $jenis_alergi,
            'kesadaran' => $kesadaran,
        ];
        // print_r($data);
        // die();
        return view('pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.geriatri.index')->with('list',$data);
    }

    function simpanFormDokterRJG(Request $request)
    {
        // print_r($request->all());
        // die();

        DB::beginTransaction();

        try {

            // Riwayat Pemeriksaan Fisik
            DB::table('medicalrecord.pemeriksaan_fisik')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'PENDAFTARAN'  => DB::table('pendaftaran.kunjungan')->where('NOMOR', $request->NOKUNJ)->value('NOPEN'),
                    'DESKRIPSI'    => $request->pfisik,
                    'OLEH'         => auth()->id(),
                    'STATUS'       => 1,
                    'TANGGAL'      => now()
                ]
            );

            // Rencana Terapi
            DB::table('medicalrecord.rencana_terapi')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'DESKRIPSI'    => $request->terapi_tind,
                    'OLEH'         => auth()->id(),
                    'STATUS'       => 1,
                    'TANGGAL'      => now()
                ]
            );

            // ASSESMENT
            DB::table('medicalrecord.sirmed_assesment')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'TOLAK_UKUR'   => $request->tu,
                    'OLEH'         => auth()->id(),
                    'STATUS'       => 1,
                    'TANGGAL'      => now()
                ]
            );

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Berhasil disimpan'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);

        }
    }

    public function getFormDokterRJG($kunjungan)
    {
        $data = [];

        // Riwayat Pemeriksaan Fisik
        $pemeriksaan_fisik = DB::table('medicalrecord.pemeriksaan_fisik')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        if ($pemeriksaan_fisik) {
            $data['pfisik'] = $pemeriksaan_fisik->DESKRIPSI;
        }

        // Riwayat Terapi
        $rencana_terapi = DB::table('medicalrecord.rencana_terapi')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        if ($rencana_terapi) {
            $data['terapi_tind'] = $rencana_terapi->DESKRIPSI;
        }

        // Riwayat Terapi
        $assesment = DB::table('medicalrecord.sirmed_assesment')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        if ($assesment) {
            $data['tu'] = $assesment->TOLAK_UKUR;
        }
        // dd($data);

        return response()->json($data);
    }

    function simpanFormPerawatRJG(Request $request)
    {
        // print_r($request->all());
        // die();

        DB::beginTransaction();

        try {

            // TANDA VITAL
            DB::table('medicalrecord.tanda_vital')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'KELUHAN_UTAMA' => $request->anm_ku,
                    'WAKTU_PEMERIKSAAN' => now(),

                    'OLEH'         => auth()->id(),
                    'STATUS'       => 1,
                    'TANGGAL'      => now(),
                ]
            );

            // DIAGNOSIS KEPERAWATAN
            DB::table('medicalrecord.sirmed_diagnosa_keperawatan')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'NYERI' =>
                        $request->input('diag_keperawatan_1') ? 1 : 0,

                    'GANGGUAN_PERFUSI_CEREBRAL' =>
                        $request->input('diag_keperawatan_2') ? 1 : 0,

                    'CEMAS' =>
                        $request->input('diag_keperawatan_3') ? 1 : 0,

                    'SENSORI_PERSEPSI' =>
                        $request->input('diag_keperawatan_4') ? 1 : 0,

                    'HIPERTERMI' =>
                        $request->input('diag_keperawatan_5') ? 1 : 0,

                    'KERUSAKAN_INTEGRITAS_KULIT' =>
                        $request->input('diag_keperawatan_6') ? 1 : 0,

                    'GANGGUAN_PERFUSI_JARINGAN' =>
                        $request->input('diag_keperawatan_7') ? 1 : 0,

                    'BODY_IMAGE' =>
                        $request->input('diag_keperawatan_8') ? 1 : 0,

                    'GANGGUAN_MOBILITAS_FISIK' =>
                        $request->input('diag_keperawatan_9') ? 1 : 0,

                    'KURANG_PENGETAHUAN' =>
                        $request->input('diag_keperawatan_10') ? 1 : 0,

                    'PERUBAHAN_NUTRISI_KURANG_DARI_KEBUTUHAN' =>
                        $request->input('diag_keperawatan_11') ? 1 : 0,

                    'MASALAH_LAIN' =>
                        $request->input('diag_lain') ? 1 : 0,

                    'RENCANA_ASUHAN_KEPERAWATAN' =>
                        $request->input('rencana_asuhan_keperawatan'),

                    'OLEH' => auth()->id(),
                    'STATUS' => 1,
                    'TANGGAL' => now(),
                ]
            );

            // ======================================================
            // ASSESMEN SINDROM GERIATRI
            // ======================================================
            DB::table('medicalrecord.sirmed_assesmen_sindrom_geriatri')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'ADL' => $request->input('geriatri_adl'),
                    'IADL' => $request->input('geriatri_iadl'),
                    'ACS' => $request->input('geriatri_acs'),
                    'NUTRISI' => $request->input('geriatri_nutrisi'),
                    'KOGNITIF' => $request->input('geriatri_kognitif'),
                    'DEPRESI' => $request->input('geriatri_depresi'),
                    'INKONTINENSIA' => $request->input('geriatri_inkontinensia'),
                    'DVT' => $request->input('geriatri_dvt'),
                    'ULKUS' => $request->input('geriatri_ulkus'),
                    'INSOMNIA' => $request->input('geriatri_insomnia'),

                    'OLEH' => auth()->id(),
                    'STATUS' => 1,
                    'TANGGAL' => now(),
                ]
            );

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Berhasil disimpan'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);

        }
    }

    public function getFormPerawatRJG($kunjungan)
    {
        $data = [];

        // ======================================================
        // TANDA VITAL
        // ======================================================
        $tanda_vital = DB::table('medicalrecord.tanda_vital')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        if ($tanda_vital) {

            $data['anm_ku']      = $tanda_vital->KELUHAN_UTAMA;
        }
        // dd($tanda_vital);

        // ======================================================
        // DIAGNOSIS KEPERAWATAN
        // ======================================================
        $diagnosa_keperawatan = DB::table('medicalrecord.sirmed_diagnosa_keperawatan')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        $diagnosa_keperawatan_field = [

            'diag_keperawatan_1' =>
                'NYERI',

            'diag_keperawatan_2' =>
                'GANGGUAN_PERFUSI_CEREBRAL',

            'diag_keperawatan_3' =>
                'CEMAS',

            'diag_keperawatan_4' =>
                'SENSORI_PERSEPSI',

            'diag_keperawatan_5' =>
                'HIPERTERMI',

            'diag_keperawatan_6' =>
                'KERUSAKAN_INTEGRITAS_KULIT',

            'diag_keperawatan_7' =>
                'GANGGUAN_PERFUSI_JARINGAN',

            'diag_keperawatan_8' =>
                'BODY_IMAGE',

            'diag_keperawatan_9' =>
                'GANGGUAN_MOBILITAS_FISIK',

            'diag_keperawatan_10' =>
                'KURANG_PENGETAHUAN',

            'diag_keperawatan_11' =>
                'PERUBAHAN_NUTRISI_KURANG_DARI_KEBUTUHAN',
        ];

        if ($diagnosa_keperawatan) {

            foreach ($diagnosa_keperawatan_field as $key => $column) {

                $data[$key] =
                    (int) ($diagnosa_keperawatan->$column ?? 0);
            }

            $data['diag_lain'] =
                $diagnosa_keperawatan->MASALAH_LAIN ?? '';

            $data['rencana_asuhan_keperawatan'] =
                $diagnosa_keperawatan->RENCANA_ASUHAN_KEPERAWATAN ?? '';
        }

        // ======================================================
        // ASSESMEN SINDROM GERIATRI
        // ======================================================
        $geriatri = DB::table('medicalrecord.sirmed_assesmen_sindrom_geriatri')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        if ($geriatri) {

            $data['geriatri_adl'] =
                $geriatri->ADL ?? '';

            $data['geriatri_iadl'] =
                $geriatri->IADL ?? '';

            $data['geriatri_acs'] =
                $geriatri->ACS ?? '';

            $data['geriatri_nutrisi'] =
                $geriatri->NUTRISI ?? '';

            $data['geriatri_kognitif'] =
                $geriatri->KOGNITIF ?? '';

            $data['geriatri_depresi'] =
                $geriatri->DEPRESI ?? '';

            $data['geriatri_inkontinensia'] =
                $geriatri->INKONTINENSIA ?? '';

            $data['geriatri_dvt'] =
                $geriatri->DVT ?? '';

            $data['geriatri_ulkus'] =
                $geriatri->ULKUS ?? '';

            $data['geriatri_insomnia'] =
                $geriatri->INSOMNIA ?? '';
        }

        return response()->json($data);
    }
}
