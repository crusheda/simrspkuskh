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

class PengkajianLaporanPascaAnestesiController extends Controller
{
    use FieldEmpty;

    function index($kunjungan)
    {
        // $pasien = DB::table('pendaftaran.kunjungan AS pk')
        //     ->leftJoin('pendaftaran.pendaftaran AS pd', 'pd.NOMOR', '=', 'pk.NOPEN')
        //     ->leftJoin('master.pasien AS p', 'p.NORM', '=', 'pd.NORM')
        //     ->leftJoin('master.referensi AS ag', function ($join) {
        //         $join->on('ag.ID', '=', 'p.AGAMA')
        //             ->where('ag.JENIS', '=', '1');
        //     })
        //     ->leftJoin('master.referensi AS kj', function ($join) {
        //         $join->on('kj.ID', '=', 'p.PEKERJAAN')
        //             ->where('kj.JENIS', '=', '4');
        //     })
        //     ->leftJoin('master.dokter AS dok', 'dok.ID', '=', 'pk.DPJP')
        //     ->select('dok.ID', DB::raw('master.getNamaLengkapPegawai(dok.NIP) AS NAMADOKTER'), 'ag.DESKRIPSI AS AGAMA', 'kj.DESKRIPSI AS PEKERJAAN')
        //     ->where('pk.NOMOR', $kunjungan)
        //     ->first();

        $data = [
            'kunjungan' => $kunjungan,
            // 'pasien' => $pasien
        ];

        return view('pages.v2.medicalrecord.detail.form.pengkajian.bedahanestesi.laporananestesi.index')->with('list',$data);
    }

    public function getForm(string $KUNJUNGAN)
    {
        $data = DB::table('simrspku_pengkajian.instruksi_pasca_anestesi')
            ->where('NOKUNJ', $KUNJUNGAN)
            ->first();

        if (!$data) {
            return response()->json([
                'status' => 404,
                'success' => false,
                'data' => null,
            ]);
        }

        return response()->json([
            'status' => 200,
            'success' => true,
            'data' => $data,
        ]);
    }

    public function simpanForm(Request $request, $KUNJUNGAN)
    {
        $validated = $request->validate([
            'NOKUNJ' => ['nullable', 'string', 'max:50'],

            'ipa_posisi' => ['nullable', 'string'],
            'ipa_oksigen' => ['nullable', 'string'],
            'ipa_tensitiap' => ['nullable', 'string'],
            'ipa_tensibawah' => ['nullable', 'string'],
            'ipa_tensiberi' => ['nullable', 'string'],
            'ipa_muntahberi' => ['nullable', 'string'],
            'ipa_sakitberi' => ['nullable', 'string'],
            'ipa_extra' => ['nullable', 'string'],

            'ipa_transfusi' => ['nullable', 'string'],
            'ipa_tetesan' => ['nullable', 'string'],
            'ipa_cairan' => ['nullable', 'string'],

            'ipa_ujp_1' => ['nullable', 'string'],
            'ipa_ujp_2' => ['nullable', 'string'],
            'ipa_ujp_3' => ['nullable', 'string'],
            'ipa_ujp_4' => ['nullable', 'string'],
            'ipa_ujp_tetesan' => ['nullable', 'string'],

            'ipa_ss_resep' => ['nullable', 'string'],
            'ipa_ss_nd' => ['nullable', 'string'],
            'ipa_ss_tensi' => ['nullable', 'string'],

            'ipa_ss_ranap' => ['nullable'],
            'ipa_ss_rr' => ['nullable'],
            'ipa_ss_icu' => ['nullable'],

            'ipa_obt_1' => ['nullable', 'string'],
            'ipa_obt_2' => ['nullable', 'string'],
            'ipa_obt_3' => ['nullable', 'string'],
        ]);

        $NOKUNJ = $request->NOKUNJ ?? $KUNJUNGAN;

        $data = [
            'NOKUNJ' => $NOKUNJ,

            'ipa_posisi' => $request->input('ipa_posisi'),
            'ipa_oksigen' => $request->input('ipa_oksigen'),
            'ipa_tensitiap' => $request->input('ipa_tensitiap'),
            'ipa_tensibawah' => $request->input('ipa_tensibawah'),
            'ipa_tensiberi' => $request->input('ipa_tensiberi'),
            'ipa_muntahberi' => $request->input('ipa_muntahberi'),
            'ipa_sakitberi' => $request->input('ipa_sakitberi'),
            'ipa_extra' => $request->input('ipa_extra'),

            'ipa_transfusi' => $request->input('ipa_transfusi'),
            'ipa_tetesan' => $request->input('ipa_tetesan'),
            'ipa_cairan' => $request->input('ipa_cairan'),

            'ipa_ujp_1' => $request->input('ipa_ujp_1'),
            'ipa_ujp_2' => $request->input('ipa_ujp_2'),
            'ipa_ujp_3' => $request->input('ipa_ujp_3'),
            'ipa_ujp_4' => $request->input('ipa_ujp_4'),
            'ipa_ujp_tetesan' => $request->input('ipa_ujp_tetesan'),

            'ipa_ss_resep' => $request->input('ipa_ss_resep'),
            'ipa_ss_nd' => $request->input('ipa_ss_nd'),
            'ipa_ss_tensi' => $request->input('ipa_ss_tensi'),

            'ipa_ss_ranap' => $request->boolean('ipa_ss_ranap'),
            'ipa_ss_rr' => $request->boolean('ipa_ss_rr'),
            'ipa_ss_icu' => $request->boolean('ipa_ss_icu'),

            'ipa_obt_1' => $request->input('ipa_obt_1'),
            'ipa_obt_2' => $request->input('ipa_obt_2'),
            'ipa_obt_3' => $request->input('ipa_obt_3'),

            'TANGGAL' => now(),
            'OLEH' => auth()->user()->ID ?? auth()->id(),
            'STATUS' => 1,

            'updated_at' => now(),
        ];

        DB::table('simrspku_pengkajian.instruksi_pasca_anestesi')
            ->updateOrInsert(
                [
                    'NOKUNJ' => $NOKUNJ,
                ],
                $data
            );

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Instruksi pasca anestesi berhasil disimpan.',
        ]);
    }

    public function getMonitoringPascaAnestesi($NOKUNJ)
    {
        $data = DB::table('simrspku_pengkajian.monitoring_pasca_anestesi')
            ->where('NOKUNJ', $NOKUNJ)
            ->orderBy('waktu')
            ->orderBy('id')
            ->get();

        return response()->json([
            'status' => 200,
            'success' => true,
            'data' => $data,
        ]);
    }

    public function simpanMonitoringPascaAnestesi(Request $request, $NOKUNJ)
    {
        $validated = $request->validate([
            'id' => ['nullable', 'integer'],
            'waktu' => ['required', 'date_format:H:i:s'],
            'nilai' => ['required', 'integer', 'min:0', 'max:300'],
            'indikator' => [
                'required',
                'string',
                'in:tensi_rendah,tensi_tinggi,nadi,resp_sr,resp_ar,resp_cr'
            ],
            'keterangan' => ['nullable', 'string'],
        ]);

        if (!empty($validated['id'])) {

            $monitoring = DB::table('simrspku_pengkajian.monitoring_pasca_anestesi')
                ->where('id', $validated['id'])
                ->where('NOKUNJ', $NOKUNJ)
                ->first();

            if (!$monitoring) {

                return response()->json([
                    'status' => 404,
                    'success' => false,
                    'message' => 'Data monitoring tidak ditemukan.',
                ], 404);

            }

            DB::table('simrspku_pengkajian.monitoring_pasca_anestesi')
                ->where('id', $validated['id'])
                ->where('NOKUNJ', $NOKUNJ)
                ->update([
                    'waktu' => $validated['waktu'],
                    'nilai' => $validated['nilai'],
                    'indikator' => $validated['indikator'],
                    'keterangan' => $validated['keterangan'] ?? null,
                    'TANGGAL' => now(),
                    'OLEH' => auth()->user()->ID ?? auth()->id(),
                    'STATUS' => 1,
                    'updated_at' => now(),
                ]);

        } else {

            DB::table('simrspku_pengkajian.monitoring_pasca_anestesi')
                ->updateOrInsert(
                    [
                        'NOKUNJ' => $NOKUNJ,
                        'waktu' => $validated['waktu'],
                        'indikator' => $validated['indikator'],
                    ],
                    [
                        'nilai' => $validated['nilai'],
                        'keterangan' => $validated['keterangan'] ?? null,
                        'TANGGAL' => now(),
                        'OLEH' => auth()->user()->ID ?? auth()->id(),
                        'STATUS' => 1,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );

        }

        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'Monitoring pasca anestesi berhasil disimpan.',
        ]);
    }

    public function hapusMonitoringPascaAnestesi($id)
    {
        try {
            $data = DB::table('simrspku_pengkajian.monitoring_pasca_anestesi')
                ->where('id', $id)
                ->first();

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data monitoring tidak ditemukan.',
                ], 404);
            }

            DB::table('simrspku_pengkajian.monitoring_pasca_anestesi')
                ->where('id', $id)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data monitoring pasca anestesi berhasil dihapus.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data monitoring pasca anestesi.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
