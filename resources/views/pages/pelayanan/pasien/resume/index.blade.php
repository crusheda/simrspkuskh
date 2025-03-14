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
                        <h2 class="mb-0">Resume Pasien <b class="text-primary">A/N SUNARYO, TN</b></h2>
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
                        <div class="flex-shrink-0">
                            <img src="{{ asset('/images/user.png') }}" alt="user-image"
                                class="avtar rounded-circle wid-45 hei-45">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">William Bond</h6>
                            <p class="text-muted text-sm mb-0">DM on <a href="#" class="text-primary">@williambond</a></p>
                        </div>
                        <div class="dropdown">
                            <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ti ti-chevron-down f-18"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="javascript: void(0);">Active</a>
                                <a class="dropdown-item" href="javascript: void(0);">Disable</a>
                                <a class="dropdown-item" href="javascript: void(0);">Remove</a>
                            </div>
                        </div>
                    </div>
                    <div class="saprator my-3">
                        <span>..</span>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">William Bond</h6>
                            <p class="text-muted text-sm mb-0">DM on <a href="#" class="text-primary">@williambond</a></p>
                        </div>
                        <div class="flex-shrink-0">
                            <button class="btn btn-primary btn-sm" onclick="cetakResumeMedis">Cetak</button>
                            <button class="btn btn-outline-secondary btn-sm ms-1">Follow</button>
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
