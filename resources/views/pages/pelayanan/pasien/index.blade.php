@extends('layouts.index')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0)">Pelayanan</a></li>
                    <li class="breadcrumb-item" aria-current="page">Daftar Pasien</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">My Pasien</h2>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="btn-group">
                        <button class="btn btn-secondary shadow" onclick="refresh()">Refresh Tabel</button>
                    </div>
                    {{-- <h5 class="mb-0">Kunjungan Pasien</h5> --}}
                    <div class="dropdown">
                        <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ti ti-dots-vertical f-18"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">Batal Kunjungan</a>
                            <a class="dropdown-item" href="#">Sedang Dilayani</a>
                            <a class="dropdown-item" href="#">Selesai Kunjungan</a>
                        </div>
                    </div>
                </div>
                <div class="border rounded p-3 my-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <img src="{{ asset('/images/user.png') }}" alt="user-image"
                                class="avtar rounded-circle wid-45 hei-45">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">Able pro</h6>
                            <small class="text-muted">@ableprodevelop</small>
                        </div>
                        <div class="dropdown">
                            <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="#"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ti ti-chevron-down f-18"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" style="">
                                <a class="dropdown-item" href="#">Active</a>
                                <a class="dropdown-item" href="#">Disable</a>
                                <a class="dropdown-item" href="#">Remove</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // $('#xpoli').on('change', function() {
        //     if (this.value) {
        //         $("#xtgl").prop('disabled', false);
        //     }
        // });
        refresh();
    });

    // function-function
    function refresh() {
        $.ajax({
            url: "/api/simgos/kunjungan/pasien",
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                $("#list").empty();
                res.show.forEach(item => {
                    console.log(item);
                })
            }
        })
    }
</script>
@endsection
