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

class PengkajianLaporanAnestesiController extends Controller
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

        return view('pages.v2.medicalrecord.detail.form.pengkajian.bedahanestesi.laporananestesi.index')->with('list',$data);
    }

    public function getForm(string $KUNJUNGAN)
    {
        $data = DB::table('simrspku_pengkajian.laporan_anestesi')
            ->where('NOKUNJ', $KUNJUNGAN)
            ->first();

        if (!$data) {
            return response()->json([
                'success' => true,
                'data' => null,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function simpanForm(Request $request, $KUNJUNGAN)
    {
        $validated = $request->validate([
            'NOKUNJ' => ['nullable', 'string', 'max:50'],

            'la_bb' => ['nullable', 'string', 'max:50'],
            'la_ekg' => ['nullable', 'string', 'max:255'],
            'la_al' => ['nullable', 'string', 'max:255'],
            'la_lain' => ['nullable', 'string', 'max:255'],
            'la_asa' => ['nullable', 'string', 'max:10'],

            'la_tensi_sis' => ['nullable', 'numeric'],
            'la_tensi_dia' => ['nullable', 'numeric'],

            'la_hb' => ['nullable', 'string', 'max:50'],
            'la_ht' => ['nullable', 'string', 'max:50'],
            'la_gd' => ['nullable', 'string', 'max:50'],
            'la_puasa' => ['nullable', 'numeric'],

            'la_prem' => ['nullable', 'string'],
            'la_prem_jam' => ['nullable', 'string', 'max:50'],
            'la_prem_rute' => ['nullable', 'string', 'max:10'],

            'la_diag_pre' => ['nullable', 'string'],
            'la_diag_post' => ['nullable', 'string'],

            'la_nama_operasi' => ['nullable', 'string'],
            'la_nama_ahli_bedah' => ['nullable', 'string'],
            'la_nama_ahli_anestesi' => ['nullable', 'string'],
            'la_nama_perawat_bidan' => ['nullable', 'string'],

            'la_anes_mulai' => ['nullable', 'date_format:H:i:s'],
            'la_anes_selesai' => ['nullable', 'date_format:H:i:s'],

            'la_op_mulai' => ['nullable', 'date_format:H:i:s'],
            'la_op_selesai' => ['nullable', 'date_format:H:i:s'],
        ]);

        $data = [
            'NOKUNJ' => $KUNJUNGAN,

            'LA_BB' => $request->input('la_bb'),
            'LA_EKG' => $request->input('la_ekg'),
            'LA_AL' => $request->input('la_al'),
            'LA_LAIN' => $request->input('la_lain'),
            'LA_ASA' => $request->input('la_asa'),

            'LA_TENSI_SIS' => $request->input('la_tensi_sis'),
            'LA_TENSI_DIA' => $request->input('la_tensi_dia'),

            'LA_HB' => $request->input('la_hb'),
            'LA_HT' => $request->input('la_ht'),
            'LA_GD' => $request->input('la_gd'),
            'LA_PUASA' => $request->input('la_puasa'),

            'LA_PREM' => $request->input('la_prem'),
            'LA_PREM_JAM' => $request->input('la_prem_jam'),
            'LA_PREM_RUTE' => $request->input('la_prem_rute'),

            'LA_DIAG_PRE' => $request->input('la_diag_pre'),
            'LA_DIAG_POST' => $request->input('la_diag_post'),

            'LA_NAMA_OPERASI' => $request->input('la_nama_operasi'),
            'LA_NAMA_AHLI_BEDAH' => $request->input('la_nama_ahli_bedah'),
            'LA_NAMA_AHLI_ANESTESI' => $request->input('la_nama_ahli_anestesi'),
            'LA_NAMA_PERAWAT_BIDAN' => $request->input('la_nama_perawat_bidan'),

            'LA_ANES_MULAI' => $request->input('la_anes_mulai'),
            'LA_ANES_SELESAI' => $request->input('la_anes_selesai'),

            'LA_OP_MULAI' => $request->input('la_op_mulai'),
            'LA_OP_SELESAI' => $request->input('la_op_selesai'),

            'updated_at' => now(),
        ];

        $exists = DB::table('simrspku_pengkajian.laporan_anestesi')
            ->where('NOKUNJ', $KUNJUNGAN)
            ->exists();

        if ($exists) {
            DB::table('simrspku_pengkajian.laporan_anestesi')
                ->where('NOKUNJ', $KUNJUNGAN)
                ->update($data);
        } else {
            $data['created_at'] = now();

            DB::table('simrspku_pengkajian.laporan_anestesi')
                ->insert($data);
        }

        return response()->json([
            'success' => true,
            'message' => 'Laporan anestesi berhasil disimpan.',
            'data' => $data,
        ]);
    }

    public function getDiagramMonitoringAnestesi(string $KUNJUNGAN)
    {
        $data = DB::table('simrspku_pengkajian.monitoring_anestesi')
            ->where('KUNJUNGAN', $KUNJUNGAN)
            ->orderBy('WAKTU')
            ->orderBy('ID')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->ID,
                    'waktu' => substr((string) $item->WAKTU, 0, 8),
                    'indikator' => $item->INDIKATOR,
                    'nilai' => is_numeric($item->NILAI)
                        ? (int) $item->NILAI
                        : null,
                    'keterangan' => $item->KETERANGAN,
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function simpanDiagramMonitoringAnestesi(
        Request $request,
        string $KUNJUNGAN
    ) {
        $validated = $request->validate([
            'waktu' => [
                'required',
                'date_format:H:i:s',
            ],

            'nilai' => [
                'required',
                'integer',
                'min:0',
                'max:300',
            ],

            'indikator' => [
                'required',
                'in:tensi_rendah,tensi_tinggi,nadi,resp_sr,resp_ar,resp_cr',
            ],

            'keterangan' => [
                'nullable',
                'string',
            ],
        ]);

        // Pastikan nilai merupakan kelipatan 20
        // if ($validated['nilai'] % 20 !== 0) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Nilai harus merupakan kelipatan 20.',
        //     ], 422);
        // }

        $data = [
            'KUNJUNGAN' => $KUNJUNGAN,
            'WAKTU' => $validated['waktu'],
            'INDIKATOR' => $validated['indikator'],
            'NILAI' => (string) $validated['nilai'],
            'KETERANGAN' => $validated['keterangan'] ?? null,
            'updated_at' => now(),
        ];

        /*
        |--------------------------------------------------------------------------
        | Cek data berdasarkan:
        | KUNJUNGAN + WAKTU + INDIKATOR
        |--------------------------------------------------------------------------
        */
        $existing = DB::table('simrspku_pengkajian.monitoring_anestesi')
            ->where('KUNJUNGAN', $KUNJUNGAN)
            ->where('WAKTU', $validated['waktu'])
            ->where('INDIKATOR', $validated['indikator'])
            ->first();

        if ($existing) {

            DB::table('simrspku_pengkajian.monitoring_anestesi')
                ->where('ID', $existing->ID)
                ->update($data);

            $ID = $existing->ID;

        } else {

            $data['created_at'] = now();

            $ID = DB::table('simrspku_pengkajian.monitoring_anestesi')
                ->insertGetId($data);
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil kembali data yang baru disimpan
        |--------------------------------------------------------------------------
        */
        $result = DB::table('simrspku_pengkajian.monitoring_anestesi')
            ->where('ID', $ID)
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Monitoring anestesi berhasil disimpan.',
            'data' => [
                'id' => $result->ID,
                'waktu' => substr((string) $result->WAKTU, 0, 5),
                'indikator' => $result->INDIKATOR,
                'nilai' => is_numeric($result->NILAI)
                    ? (int) $result->NILAI
                    : null,
                'keterangan' => $result->KETERANGAN,
            ],
        ]);
    }
}
