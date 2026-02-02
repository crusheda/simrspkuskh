<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use PHPJasper\PHPJasper;
use App\Models\simrspku_klaim\klaim_file;
use Auth, Storage;

class ApiKonsulController extends Controller
{
    public function showKonsul($NOMOR)
    {
        // Query dari database pendaftaran.konsul
        $query1 = DB::table('pendaftaran.konsul AS kon')
            ->select(
                'kon.NOMOR',
                'kon.KUNJUNGAN',
                'kon.TANGGAL',
                'kon.DOKTER_ASAL',
                'kon.DOKTER_TUJUAN',
                'kon.ALASAN',
                'kon.PERMINTAAN_TINDAKAN',
                'kon.TUJUAN',
                'kon.OLEH',
                'kon.STATUS',
                'ru.DESKRIPSI AS NAMARUANGAN',
                DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'),
                'jk.NOMOR AS JAWABAN',
                DB::raw('NULL AS created_at'),
                DB::raw('NULL AS updated_at'),
                DB::raw('NULL AS deleted_at'),
            )
            ->leftJoin('pendaftaran.jawaban_konsul AS jk','jk.KONSUL_NOMOR','=','kon.NOMOR')
            ->leftJoin('pendaftaran.kunjungan AS pk', 'pk.NOMOR', '=', 'kon.KUNJUNGAN')
            ->leftJoin('master.ruangan AS ru', 'ru.ID', '=', 'kon.TUJUAN')
            ->leftJoin('master.dokter AS dr', 'dr.ID', '=', 'pk.DPJP')
            ->where('kon.KUNJUNGAN', $NOMOR)
            ->where('kon.STATUS', '!=', '0');

        // Query dari database simrspku_klaim.konsul
        $query2 = DB::table('simrspku_klaim.konsul AS kon')
            ->select(
                'kon.NOMOR',
                'kon.KUNJUNGAN',
                'kon.TANGGAL',
                'kon.DOKTER_ASAL',
                'kon.DOKTER_TUJUAN',
                'kon.ALASAN',
                'kon.PERMINTAAN_TINDAKAN',
                'kon.TUJUAN',
                'kon.OLEH',
                'kon.STATUS',
                'ru.DESKRIPSI AS NAMARUANGAN',
                DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'),
                'jk.NOMOR AS JAWABAN',
                'kon.created_at',
                'kon.updated_at',
                'kon.deleted_at',
            )
            ->leftJoin('simrspku_klaim.jawaban_konsul AS jk','jk.KONSUL_NOMOR','=','kon.NOMOR')
            ->leftJoin('pendaftaran.kunjungan AS pk', 'pk.NOMOR', '=', 'kon.KUNJUNGAN')
            ->leftJoin('master.ruangan AS ru', 'ru.ID', '=', 'kon.TUJUAN')
            ->leftJoin('master.dokter AS dr', 'dr.ID', '=', 'pk.DPJP')
            ->where('kon.KUNJUNGAN', $NOMOR)
            ->whereNull('kon.deleted_at');

        // Gabungkan kedua query
        $data = $query1->unionAll($query2)->get();

        return response()->json($data, 200);
    }
    public function masukKonsul($NOMOR)
    {
        $show = DB::table('pendaftaran.kunjungan AS pk')
            ->select(
                'pk.NOMOR AS KUNJUNGAN',
                'pp.NORM AS NORM',
                'pp.NOMOR AS NOPEN',
                'pk.RUANGAN AS RUANGAN',
                'pk.DPJP AS DPJP'
            )
            ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
            ->where('pk.NOMOR',$NOMOR)
            ->first();
        // print_r($show);
        // die();
        // Pastikan daftar kolom sama di kedua query

        $query1 = DB::table('pendaftaran.konsul AS kon')
            ->select(
                'kon.NOMOR',
                'kon.KUNJUNGAN',
                'kon.TANGGAL',
                'kon.DOKTER_ASAL',
                'kon.DOKTER_TUJUAN',
                'kon.ALASAN',
                'kon.PERMINTAAN_TINDAKAN',
                'kon.TUJUAN',
                'kon.OLEH',
                'kon.STATUS',
                DB::raw('NULL AS KONSULTASI'),
                DB::raw('NULL AS RAWAT_BERSAMA'),
                DB::raw('NULL AS ALIH_RAWAT'),
                DB::raw("CAST('SIMGOS' AS CHAR) AS SUMBER"),
                'ru.DESKRIPSI AS NAMARUANGAN',
                'ruang.DESKRIPSI AS TUJUAN_NAMA',
                DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'),
                DB::raw('master.getNamaLengkapPegawai(mdr.NIP) AS DOKTER'),
            )
            ->leftJoin('pendaftaran.kunjungan AS pk','pk.NOMOR','=','kon.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
            ->leftJoin('master.ruangan AS ru','ru.ID','=','pk.RUANGAN')
            ->leftJoin('master.ruangan AS ruang','ruang.ID','=','kon.TUJUAN')
            ->leftJoin('master.dokter AS dr','dr.ID','=','pk.DPJP')
            ->leftJoin('master.dokter AS mdr','mdr.ID','=','kon.DOKTER_TUJUAN')
            ->where('pp.NORM', $show->NORM)
            ->where('kon.TUJUAN', $show->RUANGAN);
            // ->where('kon.DOKTER_TUJUAN', $show->DPJP)
            // ->get();

        $query2 = DB::table('simrspku_klaim.konsul AS kon')
            ->select(
                'kon.NOMOR',
                'kon.KUNJUNGAN',
                'kon.TANGGAL',
                'kon.DOKTER_ASAL',
                'kon.DOKTER_TUJUAN',
                'kon.ALASAN',
                'kon.PERMINTAAN_TINDAKAN',
                'kon.TUJUAN',
                'kon.OLEH',
                'kon.STATUS',
                'kon.KONSULTASI',
                'kon.RAWAT_BERSAMA',
                DB::raw("CAST('SIRMED' AS CHAR) AS SUMBER"),
                'kon.ALIH_RAWAT',
                'ru.DESKRIPSI AS NAMARUANGAN',
                'ruang.DESKRIPSI AS TUJUAN_NAMA',
                DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS NAMADOKTER'),
                DB::raw('master.getNamaLengkapPegawai(mdr.NIP) AS DOKTER'),
            )
            ->leftJoin('pendaftaran.kunjungan AS pk','pk.NOMOR','=','kon.KUNJUNGAN')
            ->leftJoin('pendaftaran.pendaftaran AS pp','pp.NOMOR','=','pk.NOPEN')
            ->leftJoin('master.ruangan AS ru','ru.ID','=','pk.RUANGAN')
            ->leftJoin('master.ruangan AS ruang','ruang.ID','=','kon.TUJUAN')
            ->leftJoin('master.dokter AS dr','dr.ID','=','pk.DPJP')
            ->leftJoin('master.dokter AS mdr','mdr.ID','=','kon.DOKTER_TUJUAN')
            ->where('pp.NORM', $show->NORM)
            ->where('kon.TUJUAN', $show->RUANGAN)
            ->where('kon.DOKTER_TUJUAN', $show->DPJP)
            ->whereNull('deleted_at');

        // print_r($query1);
        // die();

        $data = $query1->unionAll($query2)->get();
        return response()->json($data, 200);
    }
    public function getJawabanKonsul($NOMOR)
    {
        // print_r($NOMOR);
        // die();
        $query1 = DB::table('pendaftaran.konsul AS kon')
            ->select(
                'kon.NOMOR',
                'jk.JAWABAN AS JAWABAN',
                'jk.ANJURAN AS ANJURAN',
                'jk.TANGGAL AS TANGGAL_JAWABAN',
                'dr.NIP AS KODE_DOKTER',
                DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS JAWABDOKTER')
            )
            ->leftJoin('pendaftaran.jawaban_konsul AS jk','jk.KONSUL_NOMOR','=','kon.NOMOR')
            ->leftJoin('master.dokter AS dr','dr.ID','=','jk.DOKTER')
            ->where('kon.NOMOR', $NOMOR);
        $query2 = DB::table('simrspku_klaim.konsul AS kon')
            ->select(
                'kon.NOMOR',
                'jk.JAWABAN AS JAWABAN',
                'jk.ANJURAN AS ANJURAN',
                'jk.TANGGAL AS TANGGAL_JAWABAN',
                'dr.NIP AS KODE_DOKTER',
                DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS JAWABDOKTER')
            )
            ->leftJoin('simrspku_klaim.jawaban_konsul AS jk','jk.KONSUL_NOMOR','=','kon.NOMOR')
            ->leftJoin('master.dokter AS dr','dr.ID','=','jk.DOKTER')
            ->where('kon.NOMOR', $NOMOR)
            ->whereNull('deleted_at');
        // print_r($jawaban);
        // die();
        $data = $query1->unionAll($query2)->get();
        return response()->json($data, 200);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'alasan' => 'required|string',
            'permintaan' => 'required|string',
            'tujuan' => 'required|string',
            'dokter' => 'required|string',
            'kunjungan' => 'required|string',
        ]);

        $now = Carbon::now();
        $kunjungan = $request->input('kunjungan');
        $isHariIni = $request->input('konsul_hari_ini', 0);

        // Ambil 9 digit dari KUNJUNGAN mulai dari digit ke-3
        $kunjunganPart = substr($kunjungan, 0, 9);

        // Format tanggal yymmdd
        $tanggalPart = $now->format('ymd');

        // Hitung nomor urut
        if ($isHariIni) {
            $jumlahKonsul = DB::table('pendaftaran.konsul')
                ->where('KUNJUNGAN', $kunjungan)
                ->whereDate('TANGGAL', $now->toDateString())
                ->count();
        } else {
            $jumlahKonsul = DB::table('simrspku_klaim.konsul')
                ->where('KUNJUNGAN', $kunjungan)
                ->count();
        }
        $nomorUrutPart = str_pad($jumlahKonsul + 1, 4, '0', STR_PAD_LEFT);

        $nomorKonsul = '10' . $kunjunganPart . $tanggalPart . $nomorUrutPart;

        // Ambil DOKTER_ASAL dari kunjungan
        $asal = DB::table('pendaftaran.kunjungan')
            ->where('NOMOR', $kunjungan)
            ->first();

        if ($isHariIni) {
            // STATUS_RENCANA_OPERASI jika tujuan mengandung kode 10208
            $statusRencanaOperasi = str_contains($request->tujuan, '10208') ? 1 : 0;

            DB::table('pendaftaran.konsul')->insert([
                'NOMOR' => $nomorKonsul,
                'KUNJUNGAN' => $kunjungan,
                'TANGGAL' => $now,
                'DOKTER_ASAL' => $asal?->DPJP ?? 0,
                'DOKTER_TUJUAN' => $request->dokter,
                'ALASAN' => $request->alasan,
                'PERMINTAAN_TINDAKAN' => $request->permintaan,
                'STATUS_RENCANA_OPERASI' => $statusRencanaOperasi,
                'KODE_REQUEST_OPERASI' => '',
                'TUJUAN' => $request->tujuan,
                'OLEH' => $request->oleh,
                'STATUS' => 1,
            ]);
        } else {
            // Insert ke simrspku_klaim.konsul
            DB::table('simrspku_klaim.konsul')->insert([
                'NOMOR' => $nomorKonsul,
                'KUNJUNGAN' => $kunjungan,
                'TANGGAL' => $now,
                'DOKTER_ASAL' => $asal?->DPJP ?? 0,
                'DOKTER_TUJUAN' => $request->dokter,
                'ALASAN' => $request->alasan,
                'PERMINTAAN_TINDAKAN' => $request->permintaan,
                'TUJUAN' => $request->tujuan,
                'OLEH' => $request->oleh,
                'KONSULTASI' => $request->input('layanan_konsultasi', 0),
                'RAWAT_BERSAMA' => $request->input('layanan_rawat_bersama', 0),
                'ALIH_RAWAT' => $request->input('layanan_alih_rawat', 0),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        return response()->json([
            'message' => 'Konsul berhasil ditambahkan',
            'code' => 201
        ]);
    }
    public function listRuangan()
    {
        // print_r('bisa');
        // die();
        $data = DB::table('master.ruangan AS ru')
            ->select(
                'ru.*'
            )
            ->where(function ($q) {
                $q->where('ru.ID', 'LIKE', '1020101%')
                    ->orWhere('ru.ID', 'LIKE', '1020702%');
            })
            ->where('ru.STATUS', 1)
            ->get();
        // print_r($data);
        // die();
        return response()->json($data, 200);
    }

    public function dokterByRuangan(Request $request, $id)
    {
        $konsulHariIni = $request->boolean('konsul_hari_ini');

        $hariAngka = Carbon::now()->dayOfWeekIso;

        $query = DB::table('master.dokter_ruangan AS mdr')
            ->select(
                'dok.ID AS ID',
                DB::raw('master.getNamaLengkapPegawai(dok.NIP) AS NAMADOKTER')
            )
            ->join('master.dokter AS dok','dok.ID','=','mdr.DOKTER')
            ->join('master.pegawai AS peg','peg.NIP','=','dok.NIP')
            ->where('mdr.STATUS', 1)
            ->where('dok.STATUS', 1)
            ->where('mdr.RUANGAN', $id);

        // 🔥 FILTER KHUSUS JIKA KONSUL HARI INI
        if ($konsulHariIni) {
            $query
                ->join('penjamin_rs.dpjp AS dpjp', function ($join) {
                    $join->on('dpjp.DPJP_RS', '=', 'dok.ID')
                        ->where('dpjp.STATUS', 1);
                })
                ->join('regonline.jadwal_dokter_hfis AS jd', function ($join) use ($hariAngka) {
                    $join->on('jd.KD_DOKTER', '=', 'dpjp.DPJP_PENJAMIN')
                        ->where('jd.STATUS', 1)
                        ->where(function ($q) use ($hariAngka) {
                            $q->where('jd.HARI', $hariAngka);
                        });
                });
        }

        $data = $query
            ->groupBy('dok.ID', 'dok.NIP')
            ->orderBy('NAMADOKTER')
            ->get();

        return response()->json($data, 200);
    }

    public function getJawabKonsul($nomor)
    {
        $jawaban = DB::table('simrspku_klaim.jawaban_konsul AS jk')
            ->select(
                'jk.*',
                'dr.NIP AS NIP',
                DB::raw('master.getNamaLengkapPegawai(dr.NIP) AS JAWABDOKTER')
            )
            ->leftJoin('master.dokter AS dr','dr.ID','=','jk.DOKTER')
            ->where('KONSUL_NOMOR', $nomor)
            ->first();

        return response()->json([
            'success' => true,
            'data' => $jawaban
        ]);
    }

    public function simpanJawaban(Request $request)
    {
        // print_r($request->all());
        // die();
        DB::beginTransaction();
        try {
            $request->validate([
                'nomor' => 'required|string',
                'jawaban' => 'required|string',
                'anjuran' => 'nullable|string',
                'KUNJUNGAN' => 'nullable|string',
                'oleh' => 'required|integer'
            ]);

            $now = Carbon::now();
            $nomorKonsul = $request->input('nomor');

            $doktere = DB::table('simrspku_klaim.konsul')
                ->where('NOMOR', $nomorKonsul)
                ->first();

            if (!$doktere) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data konsul tidak ditemukan'
                ], 404);
            }

            $existing = DB::table('simrspku_klaim.jawaban_konsul')
                ->where('KONSUL_NOMOR', $nomorKonsul)
                ->first();

            if ($existing) {
                // Update
                DB::table('simrspku_klaim.jawaban_konsul')
                    ->where('KONSUL_NOMOR', $nomorKonsul)
                    ->update([
                        'JAWABAN' => $request->input('jawaban'),
                        'ANJURAN' => $request->input('anjuran'),
                        'KUNJUNGAN' => $request->input('KUNJUNGAN'),
                        'DOKTER' => $doktere->DOKTER_TUJUAN,
                        'OLEH' => $request->input('oleh'),
                        'updated_at' => $now,
                    ]);
            } else {
                // Insert
                $urutFormatted = str_pad(1, 2, '0', STR_PAD_LEFT);
                $nomorBaru = $nomorKonsul . $urutFormatted;
                // print_r($nomorBaru);
                // die();
                DB::table('simrspku_klaim.jawaban_konsul')->insert([
                    'NOMOR' => $nomorBaru,
                    'KONSUL_NOMOR' => $nomorKonsul,
                    'TANGGAL' => $now,
                    'JAWABAN' => $request->input('jawaban'),
                    'ANJURAN' => $request->input('anjuran'),
                    'DOKTER' => $doktere->DOKTER_TUJUAN,
                    'OLEH' => $request->input('oleh'),
                    'STATUS' => 1,
                    'KUNJUNGAN' => $request->input('KUNJUNGAN'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: '.$e->getMessage()
            ], 500);
        }
        $asalKonsul = DB::table('simrspku_klaim.konsul')
                ->where('NOMOR', $nomorKonsul)
                ->whereNull('deleted_at')
                ->first();

        $getKonsul = DB::table('simrspku_klaim.konsul AS kon')
                ->select(
                    'kon.*',
                    'pk.NOPEN',
                    'ttd.signature_path as path_ttd',
                    DB::raw('master.getNamaLengkapPegawai(dk.NIP) AS nama_ttd'),
                    'ttd2.signature_path as path_ttd2',
                    DB::raw('master.getNamaLengkapPegawai(dkd.NIP) AS nama_ttd2'),
                )
                ->leftJoin('pendaftaran.kunjungan AS pk','pk.NOMOR','=','kon.KUNJUNGAN')
                ->leftJoin('master.dokter AS dk','dk.ID','=','kon.DOKTER_ASAL')
                ->leftJoin('master.dokter AS dkd','dkd.ID','=','kon.DOKTER_TUJUAN')
                ->leftJoin('simrspku_klaim.tanda_tangan_pegawai AS ttd', function($join) {
                        $join->on('dk.NIP','=','ttd.nip')
                            ->whereNull('ttd.deleted_at');
                    })
                ->leftJoin('simrspku_klaim.tanda_tangan_pegawai AS ttd2', function($join) {
                        $join->on('dkd.NIP','=','ttd2.nip')
                            ->whereNull('ttd2.deleted_at');
                    })
                ->where('kon.NOMOR', $nomorKonsul)
                ->whereNull('kon.deleted_at')
                ->first();

        if (empty($getKonsul)) {
            return Response::json(array(
                'message' => 'Pengambilan data konsul gagal.'
            ), 400);
        }

        if (!$getKonsul->path_ttd) {
            return Response::json(array(
                'message' => 'Tanda tangan '.$getKonsul->nama_ttd.' tidak ditemukan/belum ditambahkan. Silakan memperbarui Data TTE pada halaman Profil.',
            ), 400);
        }
        if (!$getKonsul->path_ttd2) {
            return Response::json(array(
                'message' => 'Tanda tangan '.$getKonsul->nama_ttd2.' tidak ditemukan/belum ditambahkan. Silakan memperbarui Data TTE pada halaman Profil.',
            ), 400);
        }

        $getJawaban = DB::table('simrspku_klaim.jawaban_konsul')
                ->where('KONSUL_NOMOR', $getKonsul->NOMOR)
                ->whereNull('deleted_at')
                ->first();
        if (!$getJawaban) {
            return response()->json([
                'success' => false,
                'message' => 'Data jawaban tidak ditemukan.'
            ], 404);
        }

        $CETAK_HEADER = "1";
        // ----------------------------------------------------------------------
        $getTgl = Carbon::parse($getKonsul->created_at);
        $tgl = $getTgl->isoFormat('DD');
        $bulan = $getTgl->isoFormat('MM');
        $tahun = $getTgl->isoFormat('YYYY');
        // ----------------------------------------------------------------------
        $input = public_path().'/doc/input/konsul/CetakKonsul.jrxml';
        $path = 'files/konsul/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$getJawaban->KUNJUNGAN;
        $output = storage_path().'/app/public/'.$path;
        // print_r($output);
        // die();
        $verify = klaim_file::where('nomor', $getJawaban->KUNJUNGAN)
            ->where('jenis', 12)
            ->where('status', true)
            ->first();

        if (!$verify) {
            // Data tidak ada, maka buat baru
            $post = new klaim_file;
        } else {
            // Data sudah ada, maka update
            $post = $verify;
        }

        // Baik buat baru atau update, set data berikut
        $post->jenis = 12;
        $post->ref = $asalKonsul->KUNJUNGAN;
        $post->nomor = $getJawaban->KUNJUNGAN;
        $post->title = $getJawaban->KUNJUNGAN . '.pdf';
        $post->filename = $path . '.pdf';
        $post->nama_tambahan = 'Lembar Konsul';
        $post->status = true;
        $post->user = Auth::user()->ID;
        $post->save();

        // Pastikan folder tujuan ada
        $outputDir = dirname($output);
        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true); // true = recursive
        }

        $options = [
            'format' => ['pdf'],
            'params' => [
                'PKONSUL' => $getKonsul->NOMOR,
                'PNOPEN' => $getKonsul->NOPEN,
                'IMAGES_PATH' => public_path()."/doc/input/konsul/",
                'TTD_PATH' => storage_path()."/app/public/",
            ],
            'db_connection' => [
                'driver'   => config('database.connections.db_custom.driver'),
                'host'     => config('database.connections.db_custom.host'),
                'port'     => config('database.connections.db_custom.port'),
                'username' => config('database.connections.db_custom.username'),
                'password' => config('database.connections.db_custom.password'),
                'database' => config('database.connections.db_custom.database'),
            ],
        ];

        $jasper = new PHPJasper;

        try {
            $jasper->process($input, $output, $options)->execute();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat file PDF: '.$e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Jawaban konsul berhasil disimpan'
        ]);
    }
    public function batal($nomor)
    {
        // Cek di simrspku_klaim.konsul
        $konsul = DB::table('simrspku_klaim.konsul')
            ->where('NOMOR', $nomor)
            ->whereNull('deleted_at')
            ->first();

        $tabel = 'simrspku_klaim.konsul';

        // Jika tidak ditemukan, cek di pendaftaran.konsul
        if (!$konsul) {
            $konsul = DB::table('pendaftaran.konsul')
                ->where('NOMOR', $nomor)
                ->first();

            if (!$konsul) {
                return response()->json(['message' => 'Konsul tidak ditemukan atau sudah dibatalkan.'], 404);
            }

            $tabel = 'pendaftaran.konsul';
        }

        // Pastikan belum dijawab (hanya untuk tabel simrspku_klaim)
        if ($tabel === 'simrspku_klaim.konsul') {
            $sudahDijawab = DB::table('simrspku_klaim.jawaban_konsul')
                ->where('KONSUL_NOMOR', $nomor)
                ->exists();

            if ($sudahDijawab) {
                return response()->json(['message' => 'Konsul sudah dijawab, tidak dapat dibatalkan.'], 400);
            }
        }

        // Lakukan pembatalan
        if ($tabel === 'simrspku_klaim.konsul') {
            DB::table($tabel)
                ->where('NOMOR', $nomor)
                ->update(['deleted_at' => Carbon::now()]);
        } else {
            // Untuk pendaftaran.konsul, bisa pakai STATUS = 0
            DB::table($tabel)
                ->where('NOMOR', $nomor)
                ->update(['STATUS' => 0]);
        }

        return response()->json(['message' => 'Konsul berhasil dibatalkan.']);
    }

    public function cetakPDF($nomor)
    {
        $getKonsul = DB::table('simrspku_klaim.konsul AS kon')
                ->select(
                    'kon.*',
                    'pk.NOPEN',
                    'ttd.signature_path as path_ttd',
                    DB::raw('master.getNamaLengkapPegawai(dk.NIP) AS nama_ttd'),
                    'ttd2.signature_path as path_ttd2',
                    DB::raw('master.getNamaLengkapPegawai(dkd.NIP) AS nama_ttd2'),
                )
                ->leftJoin('pendaftaran.kunjungan AS pk','pk.NOMOR','=','kon.KUNJUNGAN')
                ->leftJoin('master.dokter AS dk','dk.ID','=','kon.DOKTER_ASAL')
                ->leftJoin('master.dokter AS dkd','dkd.ID','=','kon.DOKTER_TUJUAN')
                ->leftJoin('simrspku_klaim.tanda_tangan_pegawai AS ttd', function($join) {
                        $join->on('dk.NIP','=','ttd.nip')
                            ->whereNull('ttd.deleted_at');
                    })
                ->leftJoin('simrspku_klaim.tanda_tangan_pegawai AS ttd2', function($join) {
                        $join->on('dkd.NIP','=','ttd2.nip')
                            ->whereNull('ttd2.deleted_at');
                    })
                ->where('kon.NOMOR', $nomor)
                ->whereNull('kon.deleted_at')
                ->first();

        if (empty($getKonsul)) {
            return Response::json(array(
                'message' => 'Pengambilan data konsul gagal.'
            ), 400);
        }

        if (!$getKonsul->path_ttd) {
            return Response::json(array(
                'message' => 'Tanda tangan '.$getKonsul->nama_ttd.' tidak ditemukan/belum ditambahkan. Silakan memperbarui Data TTE pada halaman Profil.',
            ), 400);
        }
        if (!$getKonsul->path_ttd2) {
            return Response::json(array(
                'message' => 'Tanda tangan '.$getKonsul->nama_ttd2.' tidak ditemukan/belum ditambahkan. Silakan memperbarui Data TTE pada halaman Profil.',
            ), 400);
        }

        $getJawaban = DB::table('simrspku_klaim.jawaban_konsul')
                ->where('KONSUL_NOMOR', $getKonsul->NOMOR)
                ->whereNull('deleted_at')
                ->first();

        $CETAK_HEADER = "1";
        // ----------------------------------------------------------------------
        $getTgl = Carbon::parse($getKonsul->created_at);
        $tgl = $getTgl->isoFormat('DD');
        $bulan = $getTgl->isoFormat('MM');
        $tahun = $getTgl->isoFormat('YYYY');
        // ----------------------------------------------------------------------
        $input = public_path().'/doc/input/konsul/CetakKonsul.jrxml';
        $path = 'files/konsul/'.$tahun.'/'.$bulan.'/'.$tgl.'/'.$nomor;
        $output = storage_path().'/app/public/'.$path;

        $verify = klaim_file::where('nomor', $getJawaban->KUNJUNGAN)
            ->where('jenis', 12)
            ->where('status', true)
            ->first();

        if (!$verify) {
            // Data tidak ada, maka buat baru
            $post = new klaim_file;
        } else {
            // Data sudah ada, maka update
            $post = $verify;
        }

        // Baik buat baru atau update, set data berikut
        $post->jenis = 12;
        $post->nomor = $getJawaban->KUNJUNGAN;
        $post->title = $nomor . '.pdf';
        $post->filename = $path . '.pdf';
        $post->nama_tambahan = 'Lembar Konsul';
        $post->status = true;
        $post->user = Auth::user()->ID;
        $post->save();

        // Pastikan folder tujuan ada
        $outputDir = dirname($output);
        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true); // true = recursive
        }

        $options = [
            'format' => ['pdf'],
            'params' => [
                'PKONSUL' => $getKonsul->NOMOR,
                'PNOPEN' => $getKonsul->NOPEN,
                'IMAGES_PATH' => public_path()."/doc/input/konsul/",
                'TTD_PATH' => storage_path()."/app/public/",
            ],
            'db_connection' => [
                'driver'   => config('database.connections.db_custom.driver'),
                'host'     => config('database.connections.db_custom.host'),
                'port'     => config('database.connections.db_custom.port'),
                'username' => config('database.connections.db_custom.username'),
                'password' => config('database.connections.db_custom.password'),
                'database' => config('database.connections.db_custom.database'),
            ],
        ];

        $jasper = new PHPJasper;

        $jasper->process(
            $input,
            $output,
            $options
        )->execute();

        return response()->file($output.'.pdf',[
            'Content-Type' => 'application/pdf',
        ]);
    }
}
