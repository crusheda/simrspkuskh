<?php

namespace App\Http\Controllers\EMR\Form\ResumeMedis;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Klaim\BerkasKlaimController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class ResumeRajalController extends Controller
{
    /**
     * TABLE ASSUMPTION:
     * simrspku_klaim.resume_rawat_jalan
     * kunjungan (PK), SUBYEKTIF, OBYEKTIF, ASSESMENT, PLANNING, INSTRUKSI,
     * finalisasi (0/1), finalisasi_oleh, finalisasi_tanggal,
     * signature_path, signature_oleh, signature_tanggal, created_at, updated_at.
     * Adjust table/columns to your actual schema if it already exists.
     */
    private string $table = 'simrspku_klaim.resume_rawat_jalan';

    /**
     * Mengambil TTE aktif dari tabel tanda_tangan
     */
    private function signatureRecord(string $kunjungan): ?object
    {
        return DB::table('simrspku_klaim.tanda_tangan')
            ->where('kunjungan', $kunjungan)
            ->whereNull('deleted_at')
            ->orderByDesc('id')
            ->first();
    }

    private function validKunjungan(string $kunjungan): object
    {
        $visit = DB::table('pendaftaran.kunjungan as k')
            ->leftJoin('pendaftaran.pendaftaran as p', 'p.NOMOR', '=', 'k.NOPEN')
            ->leftJoin('master.pasien as ps', 'ps.NORM', '=', 'p.NORM')
            ->leftJoin('master.ruangan as r', 'r.ID', '=', 'k.RUANGAN')
            ->leftJoin('master.dokter as d', 'd.ID', '=', 'k.DPJP')
            ->select(
                'k.NOMOR as KUNJUNGAN', 'k.NOPEN', 'k.RUANGAN', 'k.DPJP', 'k.MASUK',
                'p.NORM', 'r.DESKRIPSI as UNIT',
                DB::raw('master.getNamaLengkap(ps.NORM) as NAMAPASIEN'),
                DB::raw('master.getNamaLengkapPegawai(d.NIP) as NAMADOKTER')
            )->where('k.NOMOR', $kunjungan)->first();

        abort_if(!$visit, 404, 'Kunjungan tidak ditemukan.');
        // abort_unless(Str::startsWith((string) $visit->RUANGAN, ['10201', '10207']), 403, 'Resume RJ hanya untuk ruang Rawat Jalan/Rehabilitasi Medik.');
        return $visit;
    }

    private function record(string $kunjungan): object
    {
        return DB::table($this->table)->where('kunjungan', $kunjungan)->first()
            ?? (object) [
                'kunjungan' => $kunjungan,
                'SUBYEKTIF' => '', 'OBYEKTIF' => '', 'ASSESMENT' => '', 'PLANNING' => '', 'INSTRUKSI' => '',
                'finalisasi' => 0, 'finalisasi_oleh' => null, 'finalisasi_tanggal' => null,
                'signature_path' => null, 'signature_oleh' => null, 'signature_tanggal' => null,
            ];
    }

    public function show(string $kunjungan)
    {
        $visit = $this->validKunjungan($kunjungan);

        $resume = $this->record($kunjungan);

        $finalUser = $resume->finalisasi_oleh
            ? DB::table('aplikasi.pengguna')
                ->where('ID', $resume->finalisasi_oleh)
                ->first()
            : null;

        // TTE dari tabel simrspku_klaim.tanda_tangan
        $signature = $this->signatureRecord($kunjungan);

        return response()->json([
            'success' => true,
            'kunjungan' => $visit,
            'data' => $resume,
            'is_final' => (bool) ($resume->finalisasi ?? 0),

            'final_meta' => [
                'oleh_nama' => $finalUser->NAMA ?? null,
                'tgl_difinal' => $resume->finalisasi_tanggal,
                'tgl_difinal_text' => $resume->finalisasi_tanggal
                    ? Carbon::parse($resume->finalisasi_tanggal)
                        ->translatedFormat('d F Y H:i')
                    : null,
            ],

            // TTE diambil dari tabel tanda_tangan
            'signature' => $signature
                ? Storage::disk('public')->url($signature->signature_path)
                : null,

            'signature_meta' => [
                'id' => $signature->id ?? null,
                'tanggal' => $signature->created_at ?? null,
                'user' => $signature->user ?? null,
            ],
        ]);
    }

    public function store(Request $request, string $kunjungan)
    {
        $this->validKunjungan($kunjungan);
        $resume = $this->record($kunjungan);
        abort_if((bool) $resume->finalisasi, 409, 'Resume sudah difinalisasi. Batalkan finalisasi terlebih dahulu.');
        $data = $request->validate([
            'SUBYEKTIF' => ['nullable', 'string', 'max:20000'],
            'OBYEKTIF' => ['nullable', 'string', 'max:20000'],
            'ASSESMENT' => ['nullable', 'string', 'max:20000'],
            'PLANNING' => ['nullable', 'string', 'max:20000'],
            'INSTRUKSI' => ['nullable', 'string', 'max:20000'],
        ]);
        $data['kunjungan'] = $kunjungan;
        $data['updated_at'] = now();
        if (empty($resume->created_at)) $data['created_at'] = now();
        DB::table($this->table)->updateOrInsert(['kunjungan' => $kunjungan], $data);
        return response()->json(['success' => true, 'message' => 'Resume RJ berhasil disimpan.', 'data' => $this->record($kunjungan)]);
    }

    public function finalisasi(Request $request, string $kunjungan)
    {
        $this->validKunjungan($kunjungan);

        $resume = $this->record($kunjungan);

        abort_if(
            (bool) $resume->finalisasi,
            409,
            'Resume sudah difinalisasi.'
        );

        // Pastikan ada TTE aktif di tabel tanda_tangan
        $signature = $this->signatureRecord($kunjungan);

        abort_if(
            !$signature,
            422,
            'Tanda tangan dokter harus disimpan terlebih dahulu.'
        );

        foreach ([
            'SUBYEKTIF',
            'OBYEKTIF',
            'ASSESMENT',
            'PLANNING',
            'INSTRUKSI'
        ] as $field) {
            abort_if(
                trim((string) ($resume->{$field} ?? '')) === '',
                422,
                "Bagian {$field} belum diisi."
            );
        }

        DB::table($this->table)
            ->where('kunjungan', $kunjungan)
            ->update([
                'finalisasi' => 1,
                'finalisasi_oleh' => auth()->id(),
                'finalisasi_tanggal' => now(),
                'updated_at' => now(),
            ]);

        $updated = $this->record($kunjungan);

        $finalUser = $updated->finalisasi_oleh
            ? DB::table('aplikasi.pengguna')
                ->where('ID', $updated->finalisasi_oleh)
                ->first()
            : null;

        return response()->json([
            'success' => true,
            'message' => 'Resume berhasil difinalisasi.',
            'data' => $updated,

            'final_meta' => [
                'oleh_nama' => $finalUser->NAMA ?? null,
                'tgl_difinal' => $updated->finalisasi_tanggal
                    ? Carbon::parse($updated->finalisasi_tanggal)
                        ->translatedFormat('d F Y H:i')
                    : null,
            ],
        ]);
    }

    public function batalFinal(Request $request, string $kunjungan)
    {
        $this->validKunjungan($kunjungan);
        $resume = $this->record($kunjungan);
        abort_unless((bool) $resume->finalisasi, 409, 'Resume belum dalam status final.');
        DB::table($this->table)->where('kunjungan', $kunjungan)->update([
            'finalisasi' => 0, 'finalisasi_oleh' => null, 'finalisasi_tanggal' => null, 'updated_at' => now(),
        ]);
        return response()->json(['success' => true, 'message' => 'Finalisasi resume berhasil dibatalkan.']);
    }

    public function storeTtd(Request $request, string $kunjungan)
    {
        $this->validKunjungan($kunjungan);

        $resume = $this->record($kunjungan);

        abort_if(
            (bool) $resume->finalisasi,
            409,
            'Resume sudah final, tanda tangan tidak dapat diubah.'
        );

        $validated = $request->validate([
            'signature' => [
                'required',
                'string',
                'regex:/^data:image\/png;base64,[A-Za-z0-9+\/=]+$/',
            ],
        ]);

        $base64 = substr(
            $validated['signature'],
            strpos($validated['signature'], ',') + 1
        );

        $binary = base64_decode($base64, true);

        abort_if(
            !$binary || strlen($binary) > 2_000_000,
            422,
            'Data tanda tangan tidak valid atau terlalu besar.'
        );

        // Pastikan data benar-benar PNG
        $imageInfo = @getimagesizefromstring($binary);

        abort_if(
            !$imageInfo || $imageInfo['mime'] !== 'image/png',
            422,
            'Format tanda tangan harus PNG.'
        );

        $disk = Storage::disk('public');

        // Format nama mengikuti mekanisme V2
        $timestamp = time();

        $filename = "ttd_{$timestamp}.png";
        $path = "signatures/{$filename}";

        // Menghindari nama file bentrok
        while ($disk->exists($path)) {
            $timestamp++;
            $filename = "ttd_{$timestamp}.png";
            $path = "signatures/{$filename}";
        }

        $saved = $disk->put($path, $binary);

        abort_if(
            !$saved,
            500,
            'File tanda tangan gagal disimpan.'
        );

        try {
            DB::transaction(function () use ($kunjungan, $path, $resume) {

                $now = now();

                /*
                * Soft delete TTE aktif sebelumnya untuk kunjungan ini.
                * Record lama tetap disimpan sebagai histori.
                */
                DB::table('simrspku_klaim.tanda_tangan')
                    ->where('kunjungan', $kunjungan)
                    ->whereNull('deleted_at')
                    ->update([
                        'deleted_at' => $now,
                        'updated_at' => $now,
                    ]);

                /*
                * INSERT TTE baru ke tabel existing V2.
                */
                DB::table('simrspku_klaim.tanda_tangan')
                    ->insert([
                        'kunjungan' => $kunjungan,
                        'signature_path' => $path,
                        'created_at' => $now,
                        'updated_at' => $now,
                        'deleted_at' => null,
                        'user' => auth()->id(),
                    ]);

                /*
                * Optional mirror ke resume_rawat_jalan.
                * Tabel tanda_tangan tetap menjadi sumber utama.
                */
                DB::table($this->table)
                    ->updateOrInsert(
                        ['kunjungan' => $kunjungan],
                        [
                            'signature_path' => $path,
                            'signature_oleh' => auth()->id(),
                            'signature_tanggal' => $now,
                            'updated_at' => $now,
                            'created_at' => $resume->created_at ?? $now,
                        ]
                    );
            });
        } catch (\Throwable $e) {

            // Jika transaksi database gagal, file baru dibersihkan
            $disk->delete($path);

            throw $e;
        }

        return response()->json([
            'success' => true,
            'message' => 'Tanda tangan berhasil disimpan.',

            'signature' => $disk->url($path),

            'signature_path' => $path,

            'signature_tanggal' => now()->format('d-m-Y H:i'),

            'signature_user' => auth()->id(),
        ]);
    }

    public function preview(string $kunjungan)
    {
        $this->validKunjungan($kunjungan);

        $signature = $this->signatureRecord($kunjungan);

        // TTE belum tersedia
        if (!$signature) {
            return response()->json([
                'success' => false,
                'message' => 'Tanda tangan dokter belum tersedia. Silakan tanda tangani Resume Medis terlebih dahulu.',
            ], 422);
        }

        try {

            $response = app(BerkasKlaimController::class)
                ->generateResumeMedis($kunjungan);

            // Pastikan response berupa PDF
            $response->headers->set('Content-Type', 'application/pdf');
            $response->headers->set(
                'Content-Disposition',
                'inline; filename="resume-medis-' . $kunjungan . '.pdf"'
            );

            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('X-Content-Type-Options', 'nosniff');

            return $response;

        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat Preview Resume Medis. Silakan coba kembali.',
            ], 500);
        }
    }
}
