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
        // $show = kunjungan::where('STATUS', 1)
        //         ->where('KELUAR', null)
        //         ->orderBy('MASUK','DESC')
        //         ->get();

        // print_r($show);
        // die();

        $data = [
            // 'show' => $show,
            'KUNJUNGAN' => $KUNJUNGAN,
        ];

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
        // $in = "E:/xampp8.2\htdocs\UltimatePOS\storage\app/report\compiled/expense.jasper";
        // $out = "E:/xampp8.2\htdocs\UltimatePOS\storage\app/report\output\business_1\user_1";
        // $op = [
        //     "format" => ["pdf"],
        //     'locale' => 'en',
        //     'params' => [],
        //     'db_connection' => [
        //         'driver' => env('db_pendaftaran'),
        //         'username' => env('DB_USERNAME'),
        //         'host' => env('DB_HOST'),
        //         'database' => env('DB_DATABASE_PENDAFTARAN'),
        //         'port' => env('DB_PORT')
        //     ]
        // ];
        // $tp = "expense";
        // $jasper = new PHPJasper;
        // return Response::json($jasper->process(
        //     $in,
        //     $out,
        //     $op
        // )->execute());
    }

    function report()
    {
        $input = public_path().'/doc/hello_world.jasper';
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
        return response()->file(public_path().'/doc/output.pdf');
    }
}
