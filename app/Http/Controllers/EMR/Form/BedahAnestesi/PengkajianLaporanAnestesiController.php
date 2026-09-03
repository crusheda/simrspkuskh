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

    /*
    |--------------------------------------------------------------------------
    | GRAFIK MONITORING ANESTESI DETAIL
    |--------------------------------------------------------------------------
    |
    | Menangani:
    | 1. Zat Anestesi
    | 2. Temperatur
    | 3. Cairan
    |
    */

    public function getDiagramMonitoringAnestesiDetail(
        string $KUNJUNGAN
    ) {
        $data = DB::table(
            'simrspku_pengkajian.monitoring_anestesi_detail'
        )
            ->where('KUNJUNGAN', $KUNJUNGAN)
            ->orderBy('WAKTU')
            ->orderBy('ID')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => (int) $item->ID,
                    'kunjungan' => $item->KUNJUNGAN,
                    'jenis_data' => $item->JENIS_DATA,
                    'baris' => $item->BARIS !== null
                        ? (int) $item->BARIS
                        : null,
                    'waktu' => substr((string) $item->WAKTU, 0, 8),
                    'nilai' => $item->NILAI !== null
                        ? (float) $item->NILAI
                        : null,
                    'jenis' => $item->JENIS,
                    'zat' => $item->ZAT,
                    'keterangan' => $item->KETERANGAN,
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'data' => [
                'zat_anestesi' => $data
                    ->where('jenis_data', 'zat_anestesi')
                    ->values(),

                'temperatur' => $data
                    ->where('jenis_data', 'temperatur')
                    ->values(),

                'cairan' => $data
                    ->where('jenis_data', 'cairan')
                    ->values(),
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN GRAFIK DETAIL
    |--------------------------------------------------------------------------
    |
    | Satu method untuk:
    | - zat_anestesi
    | - temperatur
    | - cairan
    |
    */

    public function simpanDiagramMonitoringAnestesiDetail(
        Request $request,
        string $KUNJUNGAN
    ) {
        $validated = $request->validate([
            'id' => [
                'nullable',
                'integer',
            ],

            'jenis_data' => [
                'required',
                'in:zat_anestesi,temperatur,cairan',
            ],

            'waktu' => [
                'required',
                'date_format:H:i:s',
            ],

            'baris' => [
                'nullable',
                'integer',
                'min:1',
                'max:5',
            ],

            'nilai' => [
                'nullable',
                'numeric',
                'min:0',
                'max:300',
            ],

            'jenis' => [
                'nullable',
                'in:oral,rectal,masuk,keluar',
            ],

            'zat' => [
                'nullable',
                'string',
                'max:100',
            ],

            'keterangan' => [
                'nullable',
                'string',
            ],
        ]);

        $jenisData = $validated['jenis_data'];
        $waktu = $validated['waktu'];

        /*
        |--------------------------------------------------------------------------
        | VALIDASI INTERVAL
        |--------------------------------------------------------------------------
        */

        $interval = match ($jenisData) {
            'zat_anestesi' => 5,
            'temperatur', 'cairan' => 15,
        };

        $this->validateMonitoringAnestesiWaktu(
            $waktu,
            $interval
        );

        /*
        |--------------------------------------------------------------------------
        | VALIDASI ZAT ANESTESI
        |--------------------------------------------------------------------------
        */

        if ($jenisData === 'zat_anestesi') {
            if (
                !isset($validated['baris']) ||
                $validated['baris'] < 1 ||
                $validated['baris'] > 5
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Baris zat anestesi harus 1 sampai 5.',
                ], 422);
            }

            if (
                !isset($validated['zat']) ||
                trim($validated['zat']) === ''
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nama zat anestesi wajib diisi.',
                ], 422);
            }

            $validated['nilai'] = null;
            $validated['jenis'] = null;
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI TEMPERATUR
        |--------------------------------------------------------------------------
        */

        if ($jenisData === 'temperatur') {
            if (
                !in_array(
                    $validated['jenis'] ?? '',
                    ['oral', 'rectal'],
                    true
                )
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jenis temperatur harus Oral atau Rectal.',
                ], 422);
            }

            if (
                !isset($validated['nilai']) ||
                $validated['nilai'] === ''
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nilai temperatur wajib diisi.',
                ], 422);
            }

            $validated['baris'] = null;
            $validated['zat'] = null;
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI CAIRAN
        |--------------------------------------------------------------------------
        */

        if ($jenisData === 'cairan') {
            if (
                !in_array(
                    $validated['jenis'] ?? '',
                    ['masuk', 'keluar'],
                    true
                )
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jenis cairan harus Masuk atau Keluar.',
                ], 422);
            }

            if (
                !isset($validated['nilai']) ||
                $validated['nilai'] === ''
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah cairan wajib diisi.',
                ], 422);
            }

            $validated['baris'] = null;
            $validated['zat'] = null;
        }

        /*
        |--------------------------------------------------------------------------
        | DATA DATABASE
        |--------------------------------------------------------------------------
        */

        $data = [
            'KUNJUNGAN' => $KUNJUNGAN,
            'JENIS_DATA' => $jenisData,
            'BARIS' => $validated['baris'] ?? null,
            'WAKTU' => $waktu,
            'NILAI' => $validated['nilai'] ?? null,
            'JENIS' => $validated['jenis'] ?? null,
            'ZAT' => isset($validated['zat'])
                ? trim($validated['zat'])
                : null,
            'KETERANGAN' => $validated['keterangan'] ?? null,
            'updated_at' => now(),
        ];

        /*
        |--------------------------------------------------------------------------
        | UPDATE BERDASARKAN ID
        |--------------------------------------------------------------------------
        |
        | ID harus tetap berada pada KUNJUNGAN yang sama.
        |
        */

        if (!empty($validated['id'])) {
            $existing = DB::table(
                'simrspku_pengkajian.monitoring_anestesi_detail'
            )
                ->where('ID', $validated['id'])
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->first();

            if ($existing) {
                DB::table(
                    'simrspku_pengkajian.monitoring_anestesi_detail'
                )
                    ->where('ID', $existing->ID)
                    ->where('KUNJUNGAN', $KUNJUNGAN)
                    ->update($data);

                return $this->responseMonitoringAnestesiDetail(
                    $existing->ID,
                    'Data monitoring berhasil diperbarui.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CARI DATA PADA SLOT YANG SAMA
        |--------------------------------------------------------------------------
        |
        | ZAT:
        | KUNJUNGAN + JENIS_DATA + BARIS + WAKTU
        |
        | TEMPERATUR:
        | KUNJUNGAN + JENIS_DATA + JENIS + WAKTU
        |
        | CAIRAN:
        | KUNJUNGAN + JENIS_DATA + JENIS + WAKTU
        |
        */

        $query = DB::table(
            'simrspku_pengkajian.monitoring_anestesi_detail'
        )
            ->where('KUNJUNGAN', $KUNJUNGAN)
            ->where('JENIS_DATA', $jenisData)
            ->where('WAKTU', $waktu);

        if ($jenisData === 'zat_anestesi') {
            $query->where('BARIS', $validated['baris']);
        } else {
            $query->where('JENIS', $validated['jenis']);
        }

        $existing = $query->first();

        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        if ($existing) {
            DB::table(
                'simrspku_pengkajian.monitoring_anestesi_detail'
            )
                ->where('ID', $existing->ID)
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->update($data);

            return $this->responseMonitoringAnestesiDetail(
                $existing->ID,
                'Data monitoring berhasil diperbarui.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | INSERT
        |--------------------------------------------------------------------------
        */

        $data['created_at'] = now();

        $ID = DB::table(
            'simrspku_pengkajian.monitoring_anestesi_detail'
        )->insertGetId($data);

        return $this->responseMonitoringAnestesiDetail(
            $ID,
            'Data monitoring berhasil disimpan.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDASI WAKTU MONITORING DETAIL
    |--------------------------------------------------------------------------
    */

    private function validateMonitoringAnestesiWaktu(
        string $waktu,
        int $interval
    ): void {
        [$jam, $menit, $detik] = array_map(
            'intval',
            explode(':', $waktu)
        );

        $totalMenit = ($jam * 60) + $menit;

        if ($detik !== 0) {
            abort(response()->json([
                'success' => false,
                'message' => 'Waktu monitoring harus tepat pada menit.',
            ], 422));
        }

        if ($totalMenit < 0 || $totalMenit > 300) {
            abort(response()->json([
                'success' => false,
                'message' => 'Waktu monitoring harus antara menit 0 sampai 300.',
            ], 422));
        }

        if ($totalMenit % $interval !== 0) {
            abort(response()->json([
                'success' => false,
                'message' => "Waktu monitoring harus kelipatan {$interval} menit.",
            ], 422));
        }
    }

    /*
    |--------------------------------------------------------------------------
    | RESPONSE DETAIL
    |--------------------------------------------------------------------------
    */

    private function responseMonitoringAnestesiDetail(
        int $ID,
        string $message
    ) {
        $result = DB::table(
            'simrspku_pengkajian.monitoring_anestesi_detail'
        )
            ->where('ID', $ID)
            ->first();

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Data monitoring tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'id' => (int) $result->ID,
                'kunjungan' => $result->KUNJUNGAN,
                'jenis_data' => $result->JENIS_DATA,
                'baris' => $result->BARIS !== null
                    ? (int) $result->BARIS
                    : null,
                'waktu' => substr(
                    (string) $result->WAKTU,
                    0,
                    8
                ),
                'nilai' => $result->NILAI !== null
                    ? (float) $result->NILAI
                    : null,
                'jenis' => $result->JENIS,
                'zat' => $result->ZAT,
                'keterangan' => $result->KETERANGAN,
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | HAPUS GRAFIK DETAIL
    |--------------------------------------------------------------------------
    */

    public function hapusDiagramMonitoringAnestesiDetail(
        string $KUNJUNGAN,
        int $ID
    ) {
        $existing = DB::table(
            'simrspku_pengkajian.monitoring_anestesi_detail'
        )
            ->where('ID', $ID)
            ->where('KUNJUNGAN', $KUNJUNGAN)
            ->first();

        if (!$existing) {
            return response()->json([
                'success' => false,
                'message' => 'Data monitoring tidak ditemukan.',
            ], 404);
        }

        DB::table(
            'simrspku_pengkajian.monitoring_anestesi_detail'
        )
            ->where('ID', $ID)
            ->where('KUNJUNGAN', $KUNJUNGAN)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data monitoring berhasil dihapus.',
        ]);
    }
}
