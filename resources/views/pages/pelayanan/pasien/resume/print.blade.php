<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    {{-- <link rel="stylesheet" href="{{ asset('css/plugins/jsvectormap.min.css') }}" />
    <!-- [Google Font : Public Sans] icon -->
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
    <!-- [phosphor Icons] https://phosphoricons.com/ -->
    <link rel="stylesheet" href="{{ asset('fonts/phosphor/duotone/style.css') }}" />
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="{{ asset('fonts/tabler-icons.min.css') }}" />
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="{{ asset('fonts/feather.css') }}" />
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="{{ asset('fonts/fontawesome.css') }}" />
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="{{ asset('fonts/material.css') }}" />
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/style-preset.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/iziToast.css') }}" />
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.2.2/b-3.2.2/b-colvis-3.2.2/b-html5-3.2.2/b-print-3.2.2/cr-2.0.4/r-3.0.4/datatables.min.css" rel="stylesheet" integrity="sha384-kxOno6NToQp/2ckZSEK36Pt54N8ZZEMvgi6P6HT6rrQlxneF3tFJatwUy2fbiy+V" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha384-VFQrHzqBh5qiJIU0uGU5CIW3+OWpdGGJM9LBnGbuIH2mkICcFZ7lPd/AAtI7SNf7" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" integrity="sha384-/RlQG9uf0M2vcTw3CX7fbqgbj/h8wKxw7C3zu9/GxcBPRKOEcESxaxufwRXqzq6n" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.2.2/b-3.2.2/b-colvis-3.2.2/b-html5-3.2.2/b-print-3.2.2/cr-2.0.4/r-3.0.4/datatables.min.js" integrity="sha384-trvaIo6kQqfNI7R38FNiGy3ZBpRQAJcMEWrgIYamfSspxVj6ezSrnKrLeGW97Wm4" crossorigin="anonymous"></script> --}}
    <style>
        @page { size: A4; margin: 10mm; }
        body { margin: 0; padding: 0; font-family: sans-serif; }
        .a4-page {
            width: 210mm;
            height: 297mm;
            padding: 10mm;
            margin: auto;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        @media print {
            body, .a4-page {
                margin: 0;
                box-shadow: none;
            }
        }
    </style>

</head>
<body>
    <div class="d-flex align-items-center">
        <table class="border-dark border" style="width: 100%;">
            <thead>
                <tr>
                    <td style="text-align: center; width:25%;">
                        <img src="{{ public_path('/images/pku/logo.png') }}" alt="user-image" class="avtar rounded-circle wid-65 hei-65" style="width: 80px; height: 80px">
                    </td>
                    <td style="align-items: flex-end"><h2>RS PKU MUHAMMADIYAH SUKOHARJO</h2><h5 class="text-muted mt-0">JL. Mayor Sunaryo No. 37 Sukoharjo 57512</h5></td>
                </tr>
            </thead>
            <tbody>
                <tr class="border-dark border">
                    <td class="p-2" style="text-align: center" colspan="2"><b>RESUME MEDIS</b></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="d-flex align-items-center">
        <table class="f-14" style="width: 100%; text-align: center;">
            <tbody>
                <tr>
                    <td class="m-5 p-2 border border-bottom-0 border-dark"  style="width: 30%;">Tanggal / Waktu Masuk</td>
                    <td class="border border-bottom-0 border-dark" style="width: 30%">Nomor Rekam Medis</td>
                    <td rowspan="2" class="border border-bottom-0 border-dark">Klinik Tujuan</td>
                </tr>
                <tr>
                    <td class="p-2 border border-top-0 border-dark">{{ \Carbon\Carbon::parse($resume->MASUK)->isoFormat('DD-MM-YYYY') }}</td>
                    <td class="p-2 border border-top-0 border-dark">{{ $resume->NORM }}</td>
                </tr>
                <tr>
                    <td class="p-2 border border-bottom-0 border-dark">Nama Pasien</td>
                    <td class="p-2 border border-bottom-0 border-dark">Tanggal Lahir / Jenis Kelamin</td>
                    <td rowspan="2" class="p-2 border border-top-0 border-dark">{{ $resume->DESKRIPSI }}</td>
                </tr>
                <tr>
                    <td class="p-2 border border-top-0 border-dark">{{ $resume->NAMAPASIEN }}</td>
                    <td class="p-2 border border-top-0 border-dark">{{ \Carbon\Carbon::parse($resume->TANGGAL_LAHIR)->isoFormat('DD-MM-YYYY') }} / {{ $resume->JK }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="d-flex align-items-center">
        <table class="f-14" style="width: 100%;">
            <tbody>
                <tr>
                    <td colspan="8">
                        <h5>I. PENGKAJIAN AWAL PASIEN</h5>
                    </td>
                </tr>
                <tr>
                    <td colspan="8" class="m-5 p-2 border border-dark">
                        <b>[Anamnesis] Keluhan Utama</b> :
                        @if (is_null($awal->DESKRIPSI))
                            'Tidak Ada'
                        @else
                            {{ $awal->DESKRIPSI }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="m-2 p-1 border border-dark" style="text-align: center" colspan="8">
                        <b>PEMERIKSAAN UMUM</b>
                    </td>
                </tr>
                <tr>
                    <td class="m-2 p-1 border border-dark" style="text-align: center" colspan="8">
                        <b>TANDA VITAL</b>
                    </td>
                </tr>
                <tr>
                    <td class="m-2 p-1 border-start border-dark" style="width: 20%">1. Tekanan Darah</td>
                    <td style="width: 5%">:</td>
                    <td style="width: 15%">-</td>
                    <td class="m-2 p-1 border-end border-dark" style="width: 10%">mmHg</td>
                    <td class="m-2 p-1 border-start border-dark" style="width: 20%">5. Saturasi O2</td>
                    <td style="width: 5%">:</td>
                    <td style="width: 15%">-</td>
                    <td class="m-2 p-1 border-end border-dark" style="width: 10%"></td>
                </tr>
                <tr>
                    <td class="m-2 p-1 border-start border-dark" style="width: 20%">2. Frekuensi Nadi</td>
                    <td style="width: 5%">:</td>
                    <td style="width: 15%">-</td>
                    <td class="m-2 p-1 border-end border-dark" style="width: 10%">x/menit</td>
                    <td class="m-2 p-1 border-start border-dark" style="width: 20%">6. Alat bantu Nafas</td>
                    <td style="width: 5%">:</td>
                    <td style="width: 15%">-</td>
                    <td class="m-2 p-1 border-end border-dark" style="width: 10%"></td>
                </tr>
                <tr>
                    <td class="m-2 p-1 border-start border-dark" style="width: 20%">3. Suhu (OC)</td>
                    <td style="width: 5%">:</td>
                    <td style="width: 15%">-</td>
                    <td class="m-2 p-1 border-end border-dark" style="width: 10%">°C</td>
                    <td class="m-2 p-1 border-start border-dark" style="width: 20%">7. Keadaan Umum</td>
                    <td style="width: 5%">:</td>
                    <td style="width: 15%">-</td>
                    <td class="m-2 p-1 border-end border-dark" style="width: 10%"></td>
                </tr>
                <tr>
                    <td class="m-2 p-1 border-start border-dark" style="width: 20%">4. Frekuensi Nafas</td>
                    <td style="width: 5%">:</td>
                    <td style="width: 15%">-</td>
                    <td class="m-2 p-1 border-end border-dark" style="width: 10%">x/menit</td>
                    <td class="m-2 p-1 border-start border-dark" style="width: 20%"></td>
                    <td style="width: 5%"></td>
                    <td style="width: 20%"></td>
                    <td class="m-2 p-1 border-end border-dark" style="width: 10%"></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="d-flex align-items-center">
        <table class="table table-bordered f-14">
            <tbody>
                <tr>
                    <td colspan="2"><h5>II. PENGKAJIAN Medis</h5></td>
                </tr>
                <tr>
                    <td style="width: 20%"><b>Waktu</b></td>
                    <td><b>Tanggal : </b> <b>Jam :</b> </td>
                </tr>
                <tr>
                    <td><b>Assesment (A)</b></td>
                    <td>-</td>
                </tr>
                <tr>
                    <td><b>Subyektif (S)</b></td>
                    <td>-</td>
                </tr>
                <tr>
                    <td><b>Obyektif (O)</b></td>
                    <td>-</td>
                </tr>
                <tr>
                    <td><b>Planning (P)</b></td>
                    <td>-</td>
                </tr>
                <tr>
                    <td><b>Instruksi (I)</b></td>
                    <td>-</td>
                </tr>
                <tr>
                    <td><b>Tindakan</b></td>
                    <td>-</td>
                </tr>
                <tr>
                    <td><b>Dirujuk / Konsul ke</b></td>
                    <td>-</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div>
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td>Nama dan Tanda Tangan Dokter</td>
                    <td>Obat</td>
                </tr>
                <tr>
                    <td>ttd</td>
                    <td>nama obat-obat</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="saprator my-3">
        {{-- <span>..</span> --}}
    </div>
    <div class="d-flex flex-wrap gap-2">
        <div class="flex-grow-1">
            <h6 class="mb-1">IT</h6>
            <p class="text-muted text-sm mb-0">DM on <a href="#" class="text-primary">@itpkuskh</a></p>
        </div>
        <div class="flex-shrink-0">
            <button class="btn btn-primary btn-sm" onclick="cetakResumeMedis">Simpan</button>
            <button class="btn btn-outline-secondary btn-sm ms-1">Cetak</button>
        </div>
    </div>


    {{-- <script src="{{ asset('js/plugins/apexcharts.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('js/plugins/world.js') }}"></script>
    <script src="{{ asset('js/plugins/world-merc.js') }}"></script>
    <script src="{{ asset('js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/icon/custom-font.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/theme.js') }}"></script>
    <script src="{{ asset('js/plugins/feather.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2-11.js') }}"></script>
    <script src="{{ asset('js/iziToast.js') }}"></script> --}}
</body>
</html>
{{-- <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Pelayanan</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('pelayanan.pasien') }}">Kunjungan Pasien</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Detail Kunjungan</a></li>
                        <li class="breadcrumb-item" aria-current="page">Resume Pasien</li>
                    </ul>
                </div>
                <div class="col-md-12 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">Resume Pasien <b class="text-primary">A/N {{ $resume->NAMAPASIEN }}</b></h2>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <!-- map-vector css -->












{{--

    <script>
        $(document).ready(function() {
            // showLoader();
            // refresh();
        });
    </script> --}}
