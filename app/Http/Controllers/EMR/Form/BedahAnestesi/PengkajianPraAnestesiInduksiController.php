<?php

namespace App\Http\Controllers\EMR\Form\BedahAnestesi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\FieldEmpty;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpWord\TemplateProcessor;
use App\Services\LibreOfficeService;
use PHPJasper\PHPJasper;
use Carbon\Carbon;
use Auth, Storage;

class PengkajianPraAnestesiInduksiController extends Controller
{
    use FieldEmpty;

    function index($kunjungan)
    {
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

        $data = [
            'kunjungan' => $kunjungan,
            'pasien' => $pasien
        ];

        return view('pages.v2.medicalrecord.detail.form.pengkajian.bedahanestesi.praanestesiinduksi.index')->with('list',$data);
    }

    function getForm($kunjungan)
    {
        $data = DB::table('simrspku_pengkajian.pengkajian_praanestesi')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    function simpanForm(Request $request, $kunjungan)
    {
        try {

            $data = [
                'KUNJUNGAN' => $kunjungan,

                'HILANG_GIGI' => $request->pai_cb_hg,
                'MOBILISASI_LEHER' => $request->pai_cb_mbl,
                'LEHER_PENDEK' => $request->pai_cb_lp,
                'BATUK' => $request->pai_cb_bat,
                'SESAK_NAFAS' => $request->pai_cb_sn,
                'INFEKSI_SALURAN_NAFAS' => $request->pai_cb_bsmi,
                'MENSTRUASI_TIDAK_NORMAL' => $request->pai_cb_pmtn,
                'STROKE' => $request->pai_cb_str,

                'SAKIT_DADA' => $request->pai_cb_sda,
                'DENYUT_JANTUNG_TIDAK_NORMAL' => $request->pai_cb_djtn,
                'MUNTAH' => $request->pai_cb_mth,
                'SUSAH_KENCING' => $request->pai_cb_skg,
                'KEJANG' => $request->pai_cb_kjg,
                'HAMIL' => $request->pai_cb_shl,
                'PINGSAN' => $request->pai_cb_pgs,
                'OBESITAS' => $request->pai_cb_obs,

                'PEMERIKSAAN_FISIK' => $request->pai_cb_pf,

                'KEPALA_LEHER' => $request->pai_keple,
                'THORAKS_COR' => $request->pai_thrcor,
                'THORAKS_PULMO' => $request->pai_thrpul,
                'ABDOMEN' => $request->pai_abd,
                'EKSTREMITAS' => $request->pai_eks,

                'DIAGNOSIS_1' => $request->pai_diag1,
                'DIAGNOSIS_2' => $request->pai_diag2,
                'DIAGNOSIS_3' => $request->pai_diag3,
                'DIAGNOSIS_4' => $request->pai_diag4,

                'ASA' => $request->pai_asa,
                'KESIMPULAN_ASA' => $request->pai_asa_kes,

                'SEDASI' => $request->pai_sedasi,
                'SEDASI_KETERANGAN' => $request->pai_sedasi_lain,

                'GA' => $request->pai_ga,
                'GA_KETERANGAN' => $request->pai_ga_lain,

                'REGIONAL_SPINAL' => $request->pai_reg_s,
                'REGIONAL_EPIDURAL' => $request->pai_reg_e,
                'REGIONAL_KAUDAL' => $request->pai_reg_k,
                'REGIONAL_BLOK_PERIFER' => $request->pai_reg_b,

                'PASCA_ANESTESIA' => $request->pai_ppa,

                'PRA_ANESTESIA_1' => $request->pai_pra1,
                'PRA_ANESTESIA_2' => $request->pai_pra2,
                'PRA_ANESTESIA_3' => $request->pai_pra3,
                'PRA_ANESTESIA_4' => $request->pai_pra4,

                'UPDATED_AT' => now(),
            ];

            $exists = DB::table('simrspku_pengkajian.pengkajian_praanestesi')
                ->where('KUNJUNGAN', $kunjungan)
                ->exists();

            if ($exists) {

                DB::table('simrspku_pengkajian.pengkajian_praanestesi')
                    ->where('KUNJUNGAN', $kunjungan)
                    ->update($data);

            } else {

                $data['CREATED_AT'] = now();

                DB::table('simrspku_pengkajian.pengkajian_praanestesi')
                    ->insert($data);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data pengkajian pra anestesi berhasil disimpan.'
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => 'Data gagal disimpan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
