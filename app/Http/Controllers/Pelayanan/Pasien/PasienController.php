<?php

namespace App\Http\Controllers\Pelayanan\Pasien;
// require __DIR__ . '/vendor/autoload.php';
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\pendaftaran\kunjungan;
use Illuminate\Support\Facades\DB;
use PHPJasper\PHPJasper;
use Carbon\Carbon;
use Auth, Storage;

class PasienController extends Controller
{
    function indexIdentitas($KUNJUNGAN)
    {
        $data = [
            'KUNJUNGAN' => $KUNJUNGAN,
        ];

        return view('pages.pelayanan.pasien.identitas.index')->with('list',$data);
    }

    function indexResume($KUNJUNGAN)
    {
        // $data = [
        //     // 'show' => $show,
        //     'KUNJUNGAN' => $KUNJUNGAN,
        // ];
        $resume = DB::table('pendaftaran.kunjungan AS pk')
                ->select(
                    'pk.*')
                ->where('pk.NOMOR',$KUNJUNGAN)
                ->get();

        print_r($resume);
        die();
        // return view('layouts.index2');
        return view('pages.pelayanan.pasien.resume.index')->with('list',$data);
    }

    function compile()
    {
        $input = public_path().'/doc/hello_world.jrxml';
        // $input = '/vendor/geekcom/phpjasper/examples/hello_world.jrxml';
        // print_r($input);
        // die();
        $jasper = new PHPJasper;
        $jasper->compile($input)->execute();
    }

    function fullJasper()
    {
        // $input = public_path().'/doc/cetak.jasper';
        $show = DB::select('CALL pendaftaran.CetakBarcodePendaftaranRSUD(2404130124)');

        // print_r(storage_path('jdbc'));
        // die();

        $file = 'CetakBarcodePendaftaran';
        $input = public_path().'/doc/'.$file.'.jrxml';
        $output = public_path().'/doc/output/';
        // $jdbc_dir = __DIR__ . '/vendor/geekcom/phpjasper/bin/jaspertarter/jdbc';
        // $jdbc_dir = storage_path('app\public\files\jdbc\mysql-connector-java-8.0.11.jar');
        $options = [
            'format' => ['pdf'],
            'locale' => 'en',
            'params' => [
                // 'PNOPEN' => $show[0]->PNOPEN,
                'NAMALENGKAP' => $show[0]->NAMALENGKAP,
                'NOJKN' => $show[0]->NOJKN,
                'AGAMA' => $show[0]->AGAMA,
                'STATUS_PERKAWINAN' => $show[0]->STATUS_PERKAWINAN,
                'TGL_LAHIR' => $show[0]->TGL_LAHIR,
                'NORM2' => $show[0]->NORM2,
                'NIK' => $show[0]->NIK,
                'UMUR' => $show[0]->UMUR,
                'ALAMAT_LENGKAP' => $show[0]->ALAMAT_LENGKAP
            ],
            // 'db_pendaftaran' => [
            //     'driver' => 'mysql',
            //     'host' => env('DB_HOST'),
            //     'port' => env('DB_PORT'),
            //     'database' => env('DB_DATABASE_PENDAFTARAN'),
            //     'username' => env('DB_USERNAME'),
            //     'password' => env('DB_PASSWORD'),
            //     'jdbc_driver' => 'com.mysql.cj.jdbc.Driver',
            //     'jdbc_url' => 'jdbc:mysql://192.168.1.4/pendaftaran',
            //     'jdbc_dir' => storage_path('jdbc') // <--- Path JDBC
            // ]
        ];

        // print_r(storage_path('jdbc'));
        // die();
        $jasper = new PHPJasper;
        $jasper->process(
            $input,
            $output,
            $options
        );
        // $jasper->compile($input);
        $jasper->execute();
        return response()->file($output.$file.'.pdf');
        // dd($jasper);
        // return response()->download($output . '.pdf');
        // $cek = $jasper->listParameters($input)->execute();
        // foreach ($cek as $key => $value) {
        //     print_r($value);
        // }
        // die();

    }

    function report()
    {
        $input = public_path().'/doc/hello_world.jrxml';
        $output = public_path().'/doc/output';
        $options = [
            'format' => ['pdf'] // 'xls' / 'rtf
        ];

        $jasper = new PHPJasper;

        $jasper->process(
            $input,
            $output,
            $options
        )->execute();
    }

    function view()
    {
        return response()->file(public_path().'/doc/output/cetak.pdf');
    }
}
