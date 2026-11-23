<?php

namespace App\Http\Controllers\Klaim\Smart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\simrspku_klaim\klaim_verifikasi;

class SmartClaimMetadataController extends Controller
{
    /**
     * Metadata khusus workspace Smart Claim.
     *
     * Data diambil langsung berdasarkan nomor kunjungan
     * yang sedang dibuka, bukan dari metadata tab sebelumnya.
     */
    public function metadataKunjungan(string $kunjungan)
    {
        $kunjungan = trim($kunjungan);

        if ($kunjungan === '') {
            return response()->json([
                'success' => false,
                'message' => 'Nomor kunjungan tidak valid.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Status verifikasi klaim
        |--------------------------------------------------------------------------
        */

        $klaim = klaim_verifikasi::where('nomor', $kunjungan)
            ->where('status', true)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Data kunjungan berdasarkan nomor kunjungan target
        |--------------------------------------------------------------------------
        */

        $data = DB::table('pendaftaran.kunjungan AS pk')
            ->select(
                'pk.NOMOR',
                'pk.NOPEN',
                'pk.RUANGAN',
                'pk.RUANG_KAMAR_TIDUR',
                'pk.DPJP',
                'pk.MASUK',
                'pk.KELUAR',

                // Pendaftaran
                'pp.NORM',
                'pp.TANGGAL AS TGLDAFTAR',

                // Asuransi
                'npj.DESKRIPSI AS NAMAASURANSI',
                'npj.REF_ID AS REFIDASURANSI',
                'pj.JENIS AS IDASURANSI',
                'pj.NOMOR AS NOASURANSI',

                // BPJS
                'kjs.noKartu AS NOBPJS',
                'kjs.noSEP AS NOSEP',
                'kjs.tglSEP AS TGLSEP',

                'pjs.prolanisPRB AS PRBPASIEN',
                'pjs.ketStatusPeserta AS STATUSBPJS',

                // Ruangan
                'ru.ID AS IDRUANGAN',
                'ru.DESKRIPSI AS NAMARUANGAN',

                'rk.KAMAR AS NAMAKAMAR',
                'rkt.TEMPAT_TIDUR AS NAMATT',

                // Identitas pasien
                'ps.NAMA AS NAMALENGKAPPASIEN',
                'ps.PANGGILAN AS PANGGILANPASIEN',
                'ps.TANGGAL_LAHIR AS TGLLAHIRPASIEN',
                'ps.JENIS_KELAMIN AS JENISKELAMIN',

                'kips.NOMOR AS NIKPASIEN',
                'kps.NOMOR AS NOHPPASIEN',

                'kgs.NAMA AS KELUARGAPASIEN',

                DB::raw('master.getNamaLengkap(ps.NORM) AS NAMAPASIEN'),

                DB::raw("
                    CASE
                        WHEN ps.JENIS_KELAMIN = 1 THEN 'LAKI-LAKI'
                        WHEN ps.JENIS_KELAMIN = 2 THEN 'PEREMPUAN'
                        ELSE 'TIDAK DIKETAHUI'
                    END AS JKPASIEN
                "),

                DB::raw(
                    'master.getCariUmur(pp.TANGGAL, ps.TANGGAL_LAHIR) AS UMURPASIEN'
                ),

                // DPJP kunjungan ini
                DB::raw(
                    'master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'
                ),

                DB::raw("
                    CASE
                        WHEN pkonsul.NOMOR IS NOT NULL THEN 'KONSUL'
                        ELSE 'UTAMA'
                    END AS JENIS_KUNJUNGAN
                ")
            )

            // Pendaftaran
            ->leftJoin(
                'pendaftaran.pendaftaran AS pp',
                'pp.NOMOR',
                '=',
                'pk.NOPEN'
            )

            // Penjamin
            ->leftJoin(
                'pendaftaran.penjamin AS pj',
                'pj.NOPEN',
                '=',
                'pp.NOMOR'
            )

            // BPJS
            ->leftJoin(
                'bpjs.kunjungan AS kjs',
                'kjs.noSEP',
                '=',
                'pj.NOMOR'
            )

            // Pasien
            ->leftJoin(
                'master.pasien AS ps',
                'ps.NORM',
                '=',
                'pp.NORM'
            )

            // Ruangan kunjungan
            ->leftJoin(
                'master.ruangan AS ru',
                'ru.ID',
                '=',
                'pk.RUANGAN'
            )

            // DPJP
            ->leftJoin(
                'master.dokter AS dr',
                'dr.ID',
                '=',
                'pk.DPJP'
            )

            // Konsul
            ->leftJoin(
                'pendaftaran.konsul AS pkonsul',
                'pkonsul.NOMOR',
                '=',
                'pk.REF'
            )

            // Referensi asuransi
            ->leftJoin('master.referensi AS npj', function ($join) {
                $join->on('npj.ID', '=', 'pj.JENIS')
                    ->where('npj.JENIS', 10);
            })

            // Peserta BPJS
            ->leftJoin('bpjs.peserta AS pjs', function ($join) {
                $join->on('pjs.noKartu', '=', 'kjs.noKartu');
            })

            // Kontak pasien
            ->leftJoin('master.kontak_pasien AS kps', function ($join) {
                $join->on('ps.NORM', '=', 'kps.NORM');
            })

            // Keluarga pasien
            ->leftJoin('master.keluarga_pasien AS kgs', function ($join) {
                $join->on('ps.NORM', '=', 'kgs.NORM');
            })

            // Kartu identitas
            ->leftJoin('master.kartu_identitas_pasien AS kips', function ($join) {
                $join->on('ps.NORM', '=', 'kips.NORM');
            })

            // Kamar / tempat tidur
            ->leftJoin('master.ruang_kamar_tidur AS rkt', function ($join) {
                $join->on('rkt.ID', '=', 'pk.RUANG_KAMAR_TIDUR')
                    ->whereIn('rkt.STATUS', [1, 2, 3]);
            })

            ->leftJoin('master.ruang_kamar AS rk', function ($join) {
                $join->on('rk.ID', '=', 'rkt.RUANG_KAMAR')
                    ->where('rk.STATUS', 1);
            })

            // Penting: query langsung ke nomor kunjungan yang diminta
            ->where('pk.NOMOR', $kunjungan)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Kunjungan tidak ditemukan
        |--------------------------------------------------------------------------
        */

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data kunjungan utama tidak ditemukan.',
                'kunjungan' => $kunjungan,
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Nama ruangan + kamar
        |--------------------------------------------------------------------------
        */

        $namaRuangan = $data->NAMARUANGAN ?? '';

        if (!empty($data->RUANG_KAMAR_TIDUR)) {
            $namaRuangan = trim(
                $namaRuangan . ' - ' .
                ($data->NAMAKAMAR ?? '') . ' ' .
                ($data->NAMATT ?? '')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status verifikasi
        |--------------------------------------------------------------------------
        */

        $statusVerif = $klaim !== null;

        /*
        |--------------------------------------------------------------------------
        | Normalisasi metadata header
        |--------------------------------------------------------------------------
        */

        $metadata = [
            // Identitas pasien
            'norm' => $data->NORM ?? '',
            'nama' => $data->NAMAPASIEN ?? '',
            'namaPasien' => $data->NAMAPASIEN ?? '',
            'namaLengkap' => $data->NAMALENGKAPPASIEN ?? '',
            'panggilan' => $data->PANGGILANPASIEN ?? '',
            'tglLahir' => $data->TGLLAHIRPASIEN ?? '',
            'umur' => $data->UMURPASIEN ?? '',
            'jenisKelamin' => $data->JKPASIEN ?? '',
            'nik' => $data->NIKPASIEN ?? '',
            'noHP' => $data->NOHPPASIEN ?? '',

            // Asuransi
            'namaAsuransi' => $data->NAMAASURANSI ?? '',
            'idAsuransi' => $data->IDASURANSI ?? '',
            'refIdAsuransi' => $data->REFIDASURANSI ?? '',
            'noAsuransi' => $data->NOASURANSI ?? '',
            'noBPJS' => $data->NOBPJS ?? '',
            'noSEP' => $data->NOSEP ?? '',
            'tglSEP' => $data->TGLSEP ?? '',
            'statusBPJS' => $data->STATUSBPJS ?? '',
            'prbPasien' => $data->PRBPASIEN ?? '',

            // Metadata kunjungan
            'nomorKunjungan' => $data->NOMOR ?? $kunjungan,
            'noNopen' => $data->NOPEN ?? '',
            'idRuangan' => $data->IDRUANGAN ?? '',
            'namaRuangan' => $namaRuangan,
            'namaDokter' => $data->NAMADOKTER ?? '',

            // Tanggal kunjungan
            'tglDaftar' => $data->TGLDAFTAR ?? '',
            'tglMasuk' => $data->MASUK ?? '',
            'tglKeluar' => $data->KELUAR ?? '',

            // Identifikasi jenis kunjungan
            'jenisKunjungan' => $data->JENIS_KUNJUNGAN ?? '',

            // Verifikasi
            'statusVerif' => $statusVerif,
            'STATUSVERIF' => $statusVerif ? 1 : 0,
            'statusKlaim' => $statusVerif
                ? 'SUDAH DIVERIFIKASI'
                : 'BELUM DIVERIFIKASI',

            // Label tab workspace
            'label' => 'SEP. ' . ($data->NOSEP ?? '-'),
        ];

        return response()->json([
            'success' => true,
            'kunjungan' => $kunjungan,
            'metadata' => $metadata,
        ]);
    }
}
