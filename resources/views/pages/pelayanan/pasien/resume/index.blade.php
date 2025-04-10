@extends('layouts.index2')

@section('content')
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
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
                        <h2 class="mb-0">Resume Pasien <b class="text-primary">A/N {{ $list['resume']->NAMAPASIEN }}</b></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-md-12">
            <div class="card user-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        {{-- <div class="flex-shrink-1 m-r-5 m-l-5">
                            <img src="{{ asset('/images/pku/logo.png') }}" alt="user-image"
                                class="avtar rounded-circle wid-65 hei-65" style="width: 65px; height: 65px">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h1 class="mb-1">RS PKU MUHAMMADIYAH SUKOHARJO</h1>
                            <h5 class="text-muted mb-1">JL. Mayor Sunaryo No. 37 Sukoharjo 57512</h5>
                        </div> --}}



                        {{-- <table class="table table-borderless border-dark border" style="width: 100%">
                            <thead>
                                <tr>
                                    <td rowspan="2" class="p-t-10 p-b-10" style="text-align: center; width:15%;">
                                        <img src="{{ asset('/images/pku/logo.png') }}" alt="user-image" class="avtar rounded-circle wid-65 hei-65" style="width: 120px; height: 120px">
                                    </td>
                                    <td><h1 class="mt-0 f-48">RS PKU MUHAMMADIYAH SUKOHARJO</h1></td>
                                </tr>
                                <tr>
                                    <td><h5 class="text-muted mt-0 f-36">JL. Mayor Sunaryo No. 37 Sukoharjo 57512</h5></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-dark border bg-gray-900">
                                    <td class="p-" style="text-align: center" colspan="2">
                                        <span class="f-32 text-gray-100">RESUME MEDIS</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table> --}}



                        <iframe src="{{ route('pelayanan.pasien.resume.print', ['KUNJUNGAN' => $list['KUNJUNGAN']]) }}" width="100%" height="800px" style="border: none;"></iframe>
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
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
    <script>
        $(document).ready(function() {
            // showLoader();
            // refresh();
        });
    </script>
@endsection
