<?php

namespace App\Http\Controllers\EMR\Form\Lain;

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

class DischargePlanningController extends Controller
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
            ->leftJoin('master.ruangan AS ru', 'ru.ID', '=', 'pk.RUANGAN')
            ->select('pd.TANGGAL AS TGL_KEDATANGAN','dok.ID', DB::raw('master.getNamaLengkapPegawai(dok.NIP) AS NAMADOKTER'), 'ru.DESKRIPSI AS RUANGAN', 'ag.DESKRIPSI AS AGAMA', 'kj.DESKRIPSI AS PEKERJAAN')
            ->where('pk.NOMOR', $kunjungan)
            ->first();
        $ruangan = DB::table('master.ruangan as ru')
            ->where(function ($query) {
                $query->where('ru.ID', 'like', '1020301%')
                    ->orWhere('ru.ID', 'like', '1020302%');
            })
            ->where('ru.JENIS', '5')
            ->get();

        $data = [
            'kunjungan' => $kunjungan,
            'pasien' => $pasien,
            'ruangan' => $ruangan,
        ];

        return view('pages.v2.medicalrecord.detail.form.lain.discharge-planning.index')->with('list',$data);
    }

    function getDischarge(string $kunjungan)
    {
        $data = DB::table('simrspku_pengkajian.discharge_planning')
            ->where('KUNJUNGAN', $kunjungan)
            ->first();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    function simpanDischarge(Request $request, string $kunjungan)
    {
        try {

            DB::table('simrspku_pengkajian.discharge_planning')
            ->updateOrInsert(
                [
                    'KUNJUNGAN' => $kunjungan
                ],
                [

                    // ==================================================
                    // TANGGAL / JAM
                    // ==================================================

                    'TANGGAL_DP' => $request->dp_tanggal,
                    'JAM_DP'     => $request->dp_jam,


                    // ==================================================
                    // KRITERIA PEMULANGAN KOMPLEKS
                    // ==================================================

                    'KRITERIA_UMUR' =>
                        $request->dp_kriteria_umur,

                    'KRITERIA_MOBILITAS' =>
                        $request->dp_kriteria_mobilitas,

                    'KRITERIA_PERAWATAN' =>
                        $request->dp_kriteria_perawatan,

                    'KRITERIA_BANTUAN' =>
                        $request->dp_kriteria_bantuan,

                    'KRITERIA_GIZI' =>
                        $request->dp_kriteria_gizi,

                    'KRITERIA_DIABETES' =>
                        $request->dp_kriteria_diabetes,

                    'KRITERIA_ICU' =>
                        $request->dp_kriteria_icu,

                    'KRITERIA_DPJP' =>
                        $request->dp_kriteria_dpjp,


                    // ==================================================
                    // EDUKASI KESEHATAN
                    // ==================================================

                    'EDUKASI_JADWAL_KONTROL' =>
                        $request->dp_edukasi_jadwal_kontrol ?? 0,

                    'EDUKASI_JADWAL_KONTROL_TEXT' =>
                        $request->dp_edukasi_jadwal_kontrol_text,

                    'EDUKASI_LAB' =>
                        $request->dp_edukasi_lab ?? 0,

                    'EDUKASI_LAB_TEXT' =>
                        $request->dp_edukasi_lab_text,

                    'EDUKASI_OBAT' =>
                        $request->dp_edukasi_obat ?? 0,

                    'EDUKASI_OBAT_TEXT' =>
                        $request->dp_edukasi_obat_text,


                    // ==================================================
                    // RINCIAN PEMULANGAN
                    // ==================================================

                    'RINCIAN_TANGGAL' =>
                        $request->dp_rincian_tanggal ?? 0,

                    'TANGGAL_PULANG' =>
                        $request->dp_tanggal_pulang,

                    'RINCIAN_PENDAMPING' =>
                        $request->dp_rincian_pendamping ?? 0,

                    'PENDAMPING' =>
                        $request->dp_pendamping,

                    'RINCIAN_TRANSPORTASI' =>
                        $request->dp_rincian_transportasi ?? 0,

                    'TRANSPORTASI' =>
                        $request->dp_transportasi,

                    'RINCIAN_RESUME' =>
                        $request->dp_rincian_resume ?? 0,

                    'RESUME' =>
                        $request->dp_resume,


                    // ==================================================
                    // MANAJEMEN DISCHARGE PLANNING
                    // ==================================================

                    'MANAJEMEN_PERAWATAN_DIRI' =>
                        $request->dp_manajemen_perawatan_diri ?? 0,

                    'MANAJEMEN_PERAWATAN_DIRI_TEXT' =>
                        $request->dp_manajemen_perawatan_diri_text,

                    'MANAJEMEN_OBAT' =>
                        $request->dp_manajemen_obat ?? 0,

                    'MANAJEMEN_OBAT_TEXT' =>
                        $request->dp_manajemen_obat_text,

                    'MANAJEMEN_DIET' =>
                        $request->dp_manajemen_diet ?? 0,

                    'MANAJEMEN_DIET_TEXT' =>
                        $request->dp_manajemen_diet_text,

                    'MANAJEMEN_LUKA' =>
                        $request->dp_manajemen_luka ?? 0,

                    'MANAJEMEN_LUKA_TEXT' =>
                        $request->dp_manajemen_luka_text,

                    'MANAJEMEN_LATIHAN' =>
                        $request->dp_manajemen_latihan ?? 0,

                    'MANAJEMEN_LATIHAN_TEXT' =>
                        $request->dp_manajemen_latihan_text,

                    'MANAJEMEN_TENAGA_KHUSUS' =>
                        $request->dp_manajemen_tenaga_khusus ?? 0,

                    'MANAJEMEN_TENAGA_KHUSUS_TEXT' =>
                        $request->dp_manajemen_tenaga_khusus_text,

                    'MANAJEMEN_HOMECARE' =>
                        $request->dp_manajemen_homecare ?? 0,

                    'MANAJEMEN_HOMECARE_TEXT' =>
                        $request->dp_manajemen_homecare_text,

                    'MANAJEMEN_AKTIVITAS' =>
                        $request->dp_manajemen_aktivitas ?? 0,

                    'MANAJEMEN_AKTIVITAS_TEXT' =>
                        $request->dp_manajemen_aktivitas_text,


                    // ==================================================
                    // PERAWATAN LANJUTAN
                    // ==================================================

                    'PERAWATAN_LANJUTAN' =>
                        $request->dp_perawatan_lanjutan ?? 0,

                    'LANJUTAN_KE' =>
                        $request->dp_lanjutan_ke,

                    'LANJUTAN_KE_KETERANGAN' =>
                        $request->dp_lanjutan_ke_keterangan,


                    // ==================================================
                    // LAIN-LAIN
                    // ==================================================

                    'MANAJEMEN_LAIN' =>
                        $request->dp_manajemen_lain ?? 0,

                    'MANAJEMEN_LAIN_TEXT' =>
                        $request->dp_manajemen_lain_text,


                    // ==================================================
                    // AUDIT
                    // ==================================================

                    'OLEH' =>
                        auth()->id(),

                    'STATUS' =>
                        1,

                    'TANGGAL' =>
                        now()

                ]
            );

            return response()->json([
                'status'  => true,
                'message' => 'Data discharge planning berhasil disimpan.'
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Data gagal disimpan.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
