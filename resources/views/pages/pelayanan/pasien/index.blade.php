@extends('layouts.index')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fas fa-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Pelayanan</a></li>
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
            <div class="card-header">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <div>
                        <div class="btn-group">
                            <button class="btn btn-primary shadow" onclick="refresh()">Refresh Tabel</button>
                        </div>
                        <button class="btn btn-info" disabled>Button 2</button>
                    </div>
                    <div class="dropdown">
                        <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="javascript: void(0);"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ti ti-dots-vertical f-18"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="javascript: void(0);">Batal Kunjungan</a>
                            <a class="dropdown-item" href="javascript: void(0);">Sedang Dilayani</a>
                            <a class="dropdown-item" href="javascript: void(0);">Selesai Kunjungan</a>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="card-body pt-3">
                <div id="list" hidden>
                    <div class="border rounded p-3 my-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <img src="{{ asset('/images/user.png') }}" alt="user-image"
                                    class="avtar rounded-circle wid-45 hei-45">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-0">000000005 - YUSSUF FAISAL</h4>
                                <a class="mb-0">DPJP : dr. Mustopa, Sp.PD., AIFO-K, FINASIM</a><br>
                                <a class="mb-2"><code>Ruangan Poli Dalam | BPJS/JKN</code></a><br>
                                <a class="mb-2"><code>04-03-2025 12:33:45</code></a>
                            </div>
                            <div class="dropdown">
                                <a class="avtar avtar-s btn-light-secondary dropdown-toggle arrow-none" href="#"
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
            </div> --}}
            <div class="card-body pt-3">
                <div class="table-responsive">
                    <table class="table table-hover" id="dttable">
                        <thead>
                            <tr>
                                <th>Aksi</th>
                                <th>Kunjungan Pasien</th>
                                <th>Masuk Ruangan</th>
                                <th>Keluar Ruangan</th>
                            </tr>
                        </thead>
                        <tbody id="tampil-tbody"><tr style='font-size:13px'><td colspan="9"><center><i class="fa fa-spinner fa-spin fa-fw"></i> Memproses data...</center></td></tr></tbody>
                    </table>
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
        $("#tampil-tbody").empty().append(`<tr style='font-size:13px'><td colspan="9"><center><i class="fa fa-spinner fa-spin fa-fw"></i> Memproses data...</center></td></tr>`);
        $.ajax({
            url: "/api/simgos/kunjungan/pasien",
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                // $("#list").empty();
                $("#tampil-tbody").empty();
                $('#dttable').DataTable().clear().destroy();
                res.show.forEach(item => {
                    console.log(item);
                    if (item.STATUS == 1) {
                        status = 'Pasien berada di ruangan ini / sedang dilayani';
                    } else {
                        if (item.STATUS == 2) {
                            status = 'Selesai';
                        } else {
                            status = 'Dibatalkan';
                        }
                    }
                    content = ``;
                    content += `<tr>
                                    <td>
                                        <div class="dropdown">
                                            <a class="avtar avtar-s btn-light-secondary dropdown-toggle arrow-none" href="#"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ti ti-chevron-down f-18"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end" style="">
                                                <a class="dropdown-item" href="javascript: void(0);">Data Diri Pasien</a>
                                                <a class="dropdown-item" href="javascript: void(0);">Resume Medis</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('/images/user.png') }}" alt="user image"
                                                    class="img-radius wid-40" />
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h4 class="mb-1">RM.${item.NORM} - <b class="text-primary">${item.NAMAPASIEN}</b></h4>
                                                <a class="mb-0">DPJP : ${item.NAMADOKTER}</a><br>
                                                <a class="mb-2"><code>Ruangan ${item.RUANGAN} | BPJS/JKN</code></a><br>
                                                <a class="mb-2"><code>${item.NOPEN} | ${status}</code></a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${item.MASUK}</td>
                                    <td>${item.KELUAR?item.KELUAR:'-'}</td>
                                </tr>`;
                    $('#tampil-tbody').append(content);
                    // content += `<div class="border rounded p-3 my-3">
                    //                 <div class="d-flex align-items-center">
                    //                     <div class="flex-shrink-0">
                    //                         <img src="{{ asset('/images/user.png') }}" alt="user-image"
                    //                             class="avtar rounded-circle wid-45 hei-45">
                    //                     </div>
                    //                     <div class="flex-grow-1 ms-3">
                    //                         <h4 class="mb-0">${item.NORM} - NY. </h4>
                    //                         <a class="mb-0">DPJP : ${item.NAMADOKTER}</a><br>
                    //                         <a class="mb-2"><code>Ruangan ${item.RUANGAN} | BPJS/JKN</code></a><br>
                    //                         <a class="mb-2"><code>${item.NOPEN} | ${item.MASUK}</code></a>
                    //                     </div>
                    //                     <div class="dropdown">
                    //                         <a class="avtar avtar-s btn-light-secondary dropdown-toggle arrow-none" href="#"
                    //                             data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    //                             <i class="ti ti-chevron-down f-18"></i>
                    //                         </a>
                    //                         <div class="dropdown-menu dropdown-menu-end" style="">
                    //                             <a class="dropdown-item" href="#">Active</a>
                    //                             <a class="dropdown-item" href="#">Disable</a>
                    //                             <a class="dropdown-item" href="#">Remove</a>
                    //                         </div>
                    //                     </div>
                    //                 </div>
                    //             </div>`;
                    // $('#list').append(content);
                })
                var table = $('#dttable').DataTable({
                    dom: 'Bfrtip',
                    order: [
                        [2, "desc"]
                    ],
                    bAutoWidth: false,
                    aoColumns : [
                        { sWidth: '5%' },
                        { sWidth: '65%' },
                        { sWidth: '15%' },
                        { sWidth: '15%' },
                    ],
                    columnDefs: [
                        // { visible: false, targets: [7] },
                    ],
                    displayLength: 7,
                    lengthChange: true,
                    lengthMenu: [7, 10, 25, 50, 75, 100],
                    buttons: ['copy', 'excel', 'pdf', 'colvis']
                });
            }
        })
    }
</script>
@endsection
{{--
"NOMOR": "1020101032503040002",
"NOPEN": "2503040011",
"RUANGAN": "102010103",
"MASUK": "2025-03-04 13:22:34",
"KELUAR": null,
"RUANG_KAMAR_TIDUR": 0,
"REF": null,
"DITERIMA_OLEH": 41,
"BARU": 0,
"TITIPAN": 0,
"TITIPAN_KELAS": 0,
"STATUS": 1,
"FINAL_HASIL": 0,
"FINAL_HASIL_OLEH": 0,
"FINAL_HASIL_TANGGAL": null,
"DPJP": 8,
"OTOMATIS": 0,
"NORM": 89963,
"NAMADOKTER": "dr. MUSTOPA, Sp.PD., AIFO-K, FINASIM" --}}
