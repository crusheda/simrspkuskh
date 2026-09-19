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

class PengkajianPraBedahController extends Controller
{
    use FieldEmpty;

    function index($kunjungan)
    {
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
                    ->where('ag.JENIS', '=', '1');
            })
            ->leftJoin('master.referensi AS kj', function ($join) {
                $join->on('kj.ID', '=', 'p.PEKERJAAN')
                    ->where('kj.JENIS', '=', '4');
            })
            ->leftJoin(
                'master.dokter AS dok',
                'dok.ID',
                '=',
                'pk.DPJP'
            )
            ->select(
                'dok.ID',
                DB::raw(
                    'master.getNamaLengkapPegawai(dok.NIP) AS NAMADOKTER'
                ),
                'ag.DESKRIPSI AS AGAMA',
                'kj.DESKRIPSI AS PEKERJAAN'
            )
            ->where('pk.NOMOR', $kunjungan)
            ->first();

        $data = [
            'kunjungan' => $kunjungan,
            'pasien'    => $pasien
        ];

        return view(
            'pages.v2.medicalrecord.detail.form.pengkajian.bedahanestesi.prabedah.index'
        )->with('list', $data);
    }


    /**
     * GET DATA
     */
    function getForm($kunjungan)
    {
        try {

            $data = DB::table(
                'simrspku_pengkajian.pengkajian_prabedah'
            )
                ->where('KUNJUNGAN', $kunjungan)
                ->first();

            return response()->json([
                'success' => true,
                'data'    => $data
            ]);

        } catch (Throwable $e) {

            \Log::error(
                'Gagal mengambil pengkajian pra bedah',
                [
                    'NOKUNJ' => $kunjungan,
                    'error'  => $e->getMessage(),
                    'line'   => $e->getLine(),
                    'file'   => $e->getFile(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data pengkajian pra bedah.'
            ], 500);
        }
    }


    /**
     * SIMPAN DATA
     */
    function simpanForm(Request $request, $kunjungan)
    {
        DB::beginTransaction();

        try {

            $data = [

                'KUNJUNGAN' => $kunjungan,

                // =====================================================
                // DATA SUBYEKTIF
                // =====================================================

                'BATUK' => $request->boolean('pb_ds_batuk'),

                'PUSING' => $request->boolean('pb_ds_pusing'),

                'SESAKNAFAS' => $request->boolean(
                    'pb_ds_sesaknafas'
                ),

                'PILEK' => $request->boolean('pb_ds_pilek'),

                'MUAL' => $request->boolean('pb_ds_mual'),

                'PUASA' => $request->boolean('pb_ds_puasa'),

                'GIGIPALSU' => $request->boolean(
                    'pb_ds_gigipalsu'
                ),

                'LAIN' => $request->boolean('pb_ds_lain'),

                'LAIN_SEBUT' => $request->input(
                    'pb_ds_lain_sebut'
                ),


                // =====================================================
                // RIWAYAT OPERASI
                // =====================================================

                'ROS' => $request->input('pb_ros'),


                // =====================================================
                // DATA OBYEKTIF
                // =====================================================

                'DO' => $request->input('pb_do'),


                // =====================================================
                // RIWAYAT PENYAKIT
                // =====================================================

                'DM' => $request->boolean('pb_rp_dm'),

                'TB' => $request->boolean('pb_rp_tb'),

                'HEPATITIS' => $request->boolean(
                    'pb_rp_hepatitis'
                ),

                'HIPERTENSI' => $request->boolean(
                    'pb_rp_hipertensi'
                ),

                'STEMI' => $request->boolean(
                    'pb_rp_stemi'
                ),

                'HIV' => $request->boolean(
                    'pb_rp_hiv'
                ),

                'ASMA' => $request->boolean(
                    'pb_rp_asma'
                ),

                'CHF' => $request->boolean(
                    'pb_rp_chf'
                ),

                'RP_LAIN' => $request->boolean(
                    'pb_rp_lain'
                ),

                'RP_LAIN_SEBUT' => $request->input(
                    'pb_rp_lain_sebut'
                ),


                // =====================================================
                // TANDA VITAL
                // =====================================================

                'TV_TD_UP' => $request->input(
                    'pb_tv_td_up'
                ),

                'TV_TD_DOWN' => $request->input(
                    'pb_tv_td_down'
                ),

                'TV_NADI' => $request->input(
                    'pb_tv_nadi'
                ),

                'TV_NADI_CB' => $request->input(
                    'pb_tv_nadi_cb',
                    1
                ),

                'TV_NAFAS' => $request->input(
                    'pb_tv_nafas'
                ),

                'TV_NAFAS_CB' => $request->input(
                    'pb_tv_nafas_cb',
                    1
                ),

                'TV_SUHU' => $request->input(
                    'pb_tv_suhu'
                ),

                'TV_SPO2' => $request->input(
                    'pb_tv_spo2'
                ),


                // =====================================================
                // PEMERIKSAAN FISIK
                // =====================================================

                'THORAK' => $request->input(
                    'pb_tho'
                ),

                'ABDOMEN' => $request->input(
                    'pb_abd'
                ),

                'EKSTREMITAS' => $request->input(
                    'pb_eks'
                ),


                // =====================================================
                // PEMERIKSAAN PENUNJANG
                // =====================================================

                'LAB' => $request->input(
                    'pb_lab'
                ),

                'RAD' => $request->input(
                    'pb_rad'
                ),


                // =====================================================
                // CATATAN PENTING
                // =====================================================

                'CP' => $request->input(
                    'pb_cp'
                ),


                // =====================================================
                // RENCANA PRA OPERASI
                // =====================================================

                'DPO' => $request->input(
                    'pb_dpo'
                ),

                'PPDO' => $request->input(
                    'pb_ppdo'
                ),

                'PRO' => $request->input(
                    'pb_pro'
                ),

                'PLO' => $request->input(
                    'pb_plo'
                ),

                'AK' => $request->input(
                    'pb_ak'
                ),

                'RO' => $request->input(
                    'pb_ro'
                ),

                'updated_at' => now(),
            ];


            DB::table(
                'simrspku_pengkajian.pengkajian_prabedah'
            )->updateOrInsert(
                [
                    'KUNJUNGAN' => $kunjungan
                ],
                $data
            );


            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data pengkajian pra bedah berhasil disimpan.'
            ]);

        } catch (Throwable $e) {

            DB::rollBack();

            \Log::error(
                'Gagal menyimpan pengkajian pra bedah',
                [
                    'NOKUNJ' => $kunjungan,
                    'error'  => $e->getMessage(),
                    'line'   => $e->getLine(),
                    'file'   => $e->getFile(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'Data pengkajian pra bedah gagal disimpan.',
                'error'   => config('app.debug')
                    ? $e->getMessage()
                    : null,
            ], 500);
        }
    }
}
