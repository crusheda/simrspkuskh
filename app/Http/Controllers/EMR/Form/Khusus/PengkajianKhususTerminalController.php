<?php

namespace App\Http\Controllers\EMR\Form\Khusus;

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

class PengkajianKhususTerminalController extends Controller
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

        return view('pages.v2.medicalrecord.detail.form.pengkajian.khusus.terminal.index')->with('list',$data);
    }

    function getFormKhusus($KUNJUNGAN)
    {
        try {
            $data = DB::table('simrspku_pengkajian.pengkajian_terminal')
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->where('STATUS', 1)
                ->first();

            if (!$data) {
                return response()->json([
                    'status'  => true,
                    'success' => true,
                    'data'    => null,
                ]);
            }

            /*
             * JSON column dari MySQL biasanya dikembalikan sebagai
             * string JSON oleh Query Builder.
             *
             * Front-end sudah menangani string JSON maupun object,
             * jadi tidak perlu decode wajib di sini.
             */
            return response()->json([
                'status'  => true,
                'success' => true,
                'data'    => $data,
            ]);

        } catch (Throwable $e) {

            report($e);

            return response()->json([
                'status'  => false,
                'success' => false,
                'message' => 'Gagal mengambil data pengkajian terminal.',
                'detail'  => config('app.debug')
                    ? $e->getMessage()
                    : null,
            ], 500);
        }
    }

    function simpanFormKhusus(Request $request, $KUNJUNGAN)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'NOKUNJ' => ['nullable', 'string', 'max:19'],

                'KEGAWATAN_PERNAFASAN' => ['nullable', 'json'],
                'KEHILANGAN_TONUS_OTOT' => ['nullable', 'json'],
                'NYERI' => ['nullable', 'json'],
                'PERLAMBATAN_SIRKULASI' => ['nullable', 'json'],
                'FAKTOR_YANG_MENINGKATKAN_GEJALA_FISIK' => ['nullable', 'json'],
                'MASALAH_KEPERAWATAN' => ['nullable', 'json'],
                'ORIENTASI_SPIRITUAL_PASIEN_DAN_KELUARGA' => ['nullable', 'json'],
                'URUSAN_DAN_KEBUTUHAN_SPIRITUAL_PASIEN' => ['nullable', 'json'],
                'STATUS_PSIKOSOSIAL_PASIEN_KELUARGA' => ['nullable', 'json'],
                'KEBUTUHAN_DUKUNGAN_PELAYANAN' => ['nullable', 'json'],
                'APAKAH_ADA_KEBUTUHAN_ALTERNATIF' => ['nullable', 'json'],
                'FAKTOR_RESIKO_BAGI_KELUARGA' => ['nullable', 'json'],
            ],
            [
                'required' => ':attribute wajib diisi.',
                'json'     => ':attribute harus berupa JSON yang valid.',
                'max'      => ':attribute maksimal :max karakter.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'success' => false,
                'message' => 'Validasi data gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {

            /*
             * Jangan mengambil OLEH dari request.
             *
             * Sesuaikan bagian ini dengan sistem login aplikasi Anda.
             *
             * Contoh umum:
             * $oleh = auth()->id();
             *
             * Jika aplikasi Anda menggunakan session user ID:
             * $oleh = session('id_user');
             */
            $oleh = auth()->id() ?? 0;

            /*
             * Kolom yang memang diperbolehkan disimpan.
             * Ini mencegah field lain dari request ikut masuk database.
             */
            $jsonColumns = [
                'KEGAWATAN_PERNAFASAN',
                'KEHILANGAN_TONUS_OTOT',
                'NYERI',
                'PERLAMBATAN_SIRKULASI',
                'FAKTOR_YANG_MENINGKATKAN_GEJALA_FISIK',
                'MASALAH_KEPERAWATAN',
                'ORIENTASI_SPIRITUAL_PASIEN_DAN_KELUARGA',
                'URUSAN_DAN_KEBUTUHAN_SPIRITUAL_PASIEN',
                'STATUS_PSIKOSOSIAL_PASIEN_KELUARGA',
                'KEBUTUHAN_DUKUNGAN_PELAYANAN',
                'APAKAH_ADA_KEBUTUHAN_ALTERNATIF',
                'FAKTOR_RESIKO_BAGI_KELUARGA',
            ];

            $data = [
                'KUNJUNGAN' => $KUNJUNGAN,
                'OLEH'      => $oleh,
                'STATUS'    => 1,
            ];

            foreach ($jsonColumns as $column) {

                /*
                 * Request mengirim JSON string.
                 * Decode lalu encode kembali agar yang masuk DB
                 * benar-benar JSON valid dan konsisten.
                 */
                $value = $request->input($column);

                if ($value === null || $value === '') {
                    $data[$column] = null;
                    continue;
                }

                $decoded = json_decode($value, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    return response()->json([
                        'status'  => false,
                        'success' => false,
                        'message' => "Format JSON {$column} tidak valid.",
                    ], 422);
                }

                $data[$column] = json_encode(
                    $decoded,
                    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                );
            }

            /*
             * Cari record aktif berdasarkan KUNJUNGAN.
             */
            $existing = DB::table('simrspku_pengkajian.pengkajian_terminal')
                ->where('KUNJUNGAN', $KUNJUNGAN)
                ->where('STATUS', 1)
                ->first();

            if ($existing) {

                /*
                 * OLEH diperbarui setiap kali autosave.
                 * Jika Anda ingin OLEH tetap menjadi pembuat pertama,
                 * pindahkan OLEH keluar dari update.
                 */
                DB::table('simrspku_pengkajian.pengkajian_terminal')
                    ->where('ID', $existing->ID)
                    ->update($data);

                $id = $existing->ID;

            } else {

                $id = DB::table('simrspku_pengkajian.pengkajian_terminal')
                    ->insertGetId($data);
            }

            return response()->json([
                'status'  => true,
                'success' => true,
                'message' => 'Pengkajian terminal berhasil disimpan.',
                'data'    => [
                    'ID'        => $id,
                    'KUNJUNGAN' => $KUNJUNGAN,
                ],
            ]);

        } catch (Throwable $e) {

            report($e);

            return response()->json([
                'status'  => false,
                'success' => false,
                'message' => 'Data pengkajian terminal gagal disimpan.',
                'detail'  => config('app.debug')
                    ? $e->getMessage()
                    : null,
            ], 500);
        }
    }
}
