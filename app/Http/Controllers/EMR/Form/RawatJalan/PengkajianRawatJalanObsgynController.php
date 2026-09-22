<?php

namespace App\Http\Controllers\EMR\Form\RawatJalan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth, Storage;

class PengkajianRawatJalanObsgynController extends Controller
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

        $usia_kehamilan = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',299)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $jenis_persalinan = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',300)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $penyulit = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',301)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $jenis_kelamin = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',2)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $penolong = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',303)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $tempat = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',304)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $keadaan_sat_ini = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',302)
                ->where('STATUS',1)
                ->orderBy('TABEL_ID','ASC')
                ->get();

        $usia = DB::table('master.referensi')
                ->select('ID','DESKRIPSI')
                ->where('JENIS',192)
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
            'usia_kehamilan' => $usia_kehamilan,
            'jenis_persalinan' => $jenis_persalinan,
            'penyulit' => $penyulit,
            'jenis_kelamin' => $jenis_kelamin,
            'penolong' => $penolong,
            'tempat' => $tempat,
            'keadaan_sat_ini' => $keadaan_sat_ini,
            'usia' => $usia,
            ];
        // print_r($data);
        // die();
        return view('pages.v2.medicalrecord.detail.form.pengkajian.rawat-jalan.obsgyn.index')->with('list',$data);
    }

    function simpanFormDokterRJO(Request $request)
    {
        // print_r($request->all());
        // die();

        DB::beginTransaction();

        try {

            // Riwayat Pemeriksaan Fisik
            DB::table('medicalrecord.sirmed_pemeriksaan_fisik_obsgyn')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    // 'PENDAFTARAN'  => DB::table('pendaftaran.kunjungan')->where('NOMOR', $request->NOKUNJ)->value('NOPEN'),
                    'RJ_PALPASI'   => $request->palpasi_leopold,
                    'RJ_LEOPOLD_1' => $request->leopold1,
                    'RJ_LEOPOLD_2' => $request->leopold2,
                    'RJ_LEOPOLD_3' => $request->leopold3,
                    'RJ_LEOPOLD_4' => $request->leopold4,
                    'RJ_DJJ'       => $request->aus_nadi,
                    'RJ_AUSKULTASI'       => $request->aus_nadi_cb,
                    'RJ_PEMERIKSAAN_LAIN' => $request->pem_lain,
                    'RJ_EXTREMITAS'       => $request->extremitas,
                    'RJ_PATELA_1'       => $request->patela1,
                    'RJ_PATELA_2'       => $request->patela2,
                    'RJ_UODEMA_1'       => $request->uodema1,
                    'RJ_UODEMA_2'       => $request->uodema2,
                    'OLEH'         => auth()->id(),
                    'STATUS'       => 1,
                    'TANGGAL'      => now()
                ]
            );
            $auskultasi = '';

            if ($request->aus_nadi_cb == 1) {
                $auskultasi = 'Reguler';
            } elseif ($request->aus_nadi_cb == 2) {
                $auskultasi = 'Ireguler';
            }

            $pemeriksaanLain = '';

            if ($request->pem_lain == 1) {
                $pemeriksaanLain = 'Panggul';
            } elseif ($request->pem_lain == 2) {
                $pemeriksaanLain = 'Osborn';
            }

            $deskripsiItems = [];

            // Palpasi Leopold
            if ($request->filled('palpasi_leopold')) {
                $deskripsiItems[] = 'Palpasi Leopold: ' . $request->palpasi_leopold;
            }

            // Leopold I
            if ($request->filled('leopold1')) {
                $deskripsiItems[] = 'Leopold I: ' . $request->leopold1;
            }

            // Leopold II
            if ($request->filled('leopold2')) {
                $deskripsiItems[] = 'Leopold II: ' . $request->leopold2;
            }

            // Leopold III
            if ($request->filled('leopold3')) {
                $deskripsiItems[] = 'Leopold III: ' . $request->leopold3;
            }

            // Leopold IV
            if ($request->filled('leopold4')) {
                $deskripsiItems[] = 'Leopold IV: ' . $request->leopold4;
            }

            // DJJ
            if ($request->filled('aus_nadi')) {
                $deskripsiItems[] = 'DJJ: ' . $request->aus_nadi . ' X/menit';
            }

            // Auskultasi
            if ($request->filled('aus_nadi_cb')) {
                $auskultasi = '';

                if ($request->aus_nadi_cb == 1) {
                    $auskultasi = 'Reguler';
                } elseif ($request->aus_nadi_cb == 2) {
                    $auskultasi = 'Ireguler';
                }

                if ($auskultasi !== '') {
                    $deskripsiItems[] = 'Auskultasi: ' . $auskultasi;
                }
            }

            // Pemeriksaan Lain
            if ($request->filled('pem_lain')) {
                $pemeriksaanLain = '';

                if ($request->pem_lain == 1) {
                    $pemeriksaanLain = 'Panggul';
                } elseif ($request->pem_lain == 2) {
                    $pemeriksaanLain = 'Osborn';
                }

                if ($pemeriksaanLain !== '') {
                    $deskripsiItems[] = 'Pemeriksaan Lain: ' . $pemeriksaanLain;
                }
            }

            // Extremitas
            if ($request->filled('extremitas')) {
                $deskripsiItems[] = 'Extremitas: ' . $request->extremitas;
            }

            // Reflek Patela
            if ($request->filled('patela1') || $request->filled('patela2')) {
                $deskripsiItems[] = 'Reflek Patela: '
                    . ($request->patela1 ?? '')
                    . ' / '
                    . ($request->patela2 ?? '');
            }

            // Uodema
            if ($request->filled('uodema1') || $request->filled('uodema2')) {
                $deskripsiItems[] = 'Uodema: '
                    . ($request->uodema1 ?? '')
                    . ' / '
                    . ($request->uodema2 ?? '');
            }

            // Gabungkan hanya yang memiliki isi
            // $deskripsi = implode("\n", $deskripsiItems);
            $deskripsi = implode('<br><br>', $deskripsiItems);
            // dd($deskripsi);

            DB::table('medicalrecord.pemeriksaan_fisik')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    'PENDAFTARAN'  => DB::table('pendaftaran.kunjungan')->where('NOMOR', $request->NOKUNJ)->value('NOPEN'),
                    'DESKRIPSI'    => $deskripsi,
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

            // Riwayat Menstruasi
            DB::table('medicalrecord.sirmed_status_reproduksi')->updateOrInsert(
                [
                    'KUNJUNGAN' => $request->NOKUNJ
                ],
                [
                    // --------------------------
                    // Riwayat KB
                    // --------------------------
                    'KB_SUNTIK' => $request->input('kb_suntik') ? 1 : 0,
                    'KB_IUD' => $request->input('kb_iud') ? 1 : 0,
                    'KB_PIL' => $request->input('kb_pil') ? 1 : 0,
                    'KB_KONDOM' => $request->input('kb_kondom') ? 1 : 0,
                    'KB_KALENDER' => $request->input('kb_kalender') ? 1 : 0,
                    'KB_MOW' => $request->input('kb_mow') ? 1 : 0,
                    'KB_MOP' => $request->input('kb_mop') ? 1 : 0,
                    'KB_IMPLAN' => $request->input('kb_implan') ? 1 : 0,

                    'KB_KELUHAN' => $request->input('kb_keluhan'),

                    // --------------------------
                    // Riwayat Menstruasi
                    // --------------------------
                    'MENSTRUASI_TERATUR' =>
                        $request->has('menstruasi_teratur')
                            ? (int) $request->input('menstruasi_teratur')
                            : 0,

                    'MENSTRUASI_KELUHAN' =>
                        $request->input('menstruasi_keluhan'),

                    // --------------------------
                    // Audit
                    // --------------------------
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

    public function getFormDokterRJO($kunjungan)
    {
        $data = [];

        // Riwayat Pemeriksaan Fisik
        $pemeriksaan_fisik_obsgyn = DB::table('medicalrecord.sirmed_pemeriksaan_fisik_obsgyn')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        if ($pemeriksaan_fisik_obsgyn) {
            $data['palpasi_leopold'] = $pemeriksaan_fisik_obsgyn->RJ_PALPASI;
            $data['leopold1'] = $pemeriksaan_fisik_obsgyn->RJ_LEOPOLD_1;
            $data['leopold2'] = $pemeriksaan_fisik_obsgyn->RJ_LEOPOLD_2;
            $data['leopold3'] = $pemeriksaan_fisik_obsgyn->RJ_LEOPOLD_3;
            $data['leopold4'] = $pemeriksaan_fisik_obsgyn->RJ_LEOPOLD_4;
            $data['aus_nadi'] = $pemeriksaan_fisik_obsgyn->RJ_DJJ;
            $data['aus_nadi_cb'] = $pemeriksaan_fisik_obsgyn->RJ_AUSKULTASI;
            $data['pem_lain'] = $pemeriksaan_fisik_obsgyn->RJ_PEMERIKSAAN_LAIN;
            $data['extremitas'] = $pemeriksaan_fisik_obsgyn->RJ_EXTREMITAS;
            $data['patela1'] = $pemeriksaan_fisik_obsgyn->RJ_PATELA_1;
            $data['patela2'] = $pemeriksaan_fisik_obsgyn->RJ_PATELA_2;
            $data['uodema1'] = $pemeriksaan_fisik_obsgyn->RJ_UODEMA_1;
            $data['uodema2'] = $pemeriksaan_fisik_obsgyn->RJ_UODEMA_2;
        }

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

        $data['terapi_tind'] = '';

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

        $reproduksi = DB::table('medicalrecord.sirmed_status_reproduksi')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        if ($reproduksi) {
            $data['kb_suntik'] = (int) $reproduksi->KB_SUNTIK;
            $data['kb_iud'] = (int) $reproduksi->KB_IUD;
            $data['kb_pil'] = (int) $reproduksi->KB_PIL;
            $data['kb_kondom'] = (int) $reproduksi->KB_KONDOM;
            $data['kb_kalender'] = (int) $reproduksi->KB_KALENDER;
            $data['kb_mow'] = (int) $reproduksi->KB_MOW;
            $data['kb_mop'] = (int) $reproduksi->KB_MOP;
            $data['kb_implan'] = (int) $reproduksi->KB_IMPLAN;

            $data['kb_keluhan'] =
                $reproduksi->KB_KELUHAN ?? '';

            $data['menstruasi_teratur'] =
                $reproduksi->MENSTRUASI_TERATUR;

            $data['menstruasi_keluhan'] =
                $reproduksi->MENSTRUASI_KELUHAN ?? '';
        }

        return response()->json($data);
    }
}
