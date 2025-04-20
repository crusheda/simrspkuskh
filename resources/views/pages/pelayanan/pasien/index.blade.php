@extends('layouts.index')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i></a></li>
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
            <div class="card-header p-3">
                <div class="d-sm-flex align-items-center justify-content-between ms-2">
                    <h6 class="mt-2"><i class="fas fa-filter text-primary me-2"></i> Filter</h6>
                    <div class="dropdown">
                        {{-- <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="javascript: void(0);"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ti ti-dots-vertical f-18"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="javascript: void(0);" onclick="refresh(0)">Batal Kunjungan</a>
                            <a class="dropdown-item" href="javascript: void(0);" onclick="refresh(1)">Sedang Dilayani</a>
                            <a class="dropdown-item" href="javascript: void(0);" onclick="refresh(2)">Selesai Kunjungan</a>
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="card-body pt-3">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label class="form-label">Status Kunjungan</label>
                            <select class="form-control" id="filter_status">
                                <option value="5">Tampilkan Semua Kunjungan</option>
                                <option value="0">Batal Kunjungan</option>
                                <option value="1" selected>Sedang Dilayani</option>
                                <option value="2">Selesai Kunjungan</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-group">
                            <label class="form-label">Rentang Tgl Kunjungan</label>
                            <div class="input-group">
                                <input type="text" id="filter_tgl" class="form-control flatpickr-input active" placeholder="Select date range" readonly="readonly">
                                <span class="input-group-text"><i class="feather icon-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label">DPJP</label>
                            <select class="form-control" id="filter_dpjp">
                                <option value="">Pilih Dokter</option>
                                @if ($list['dr'])
                                    @foreach ($list['dr'] as $item)
                                        <option value="{{ $item->NIP }}">{{ $item->NAMADOKTER }} ({{ $item->DESKRIPSI }})</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between p-3">
                <div class="text-start">
                    <h6 class="text-muted ms-2 mt-2">Tekan tombol <span class="badge text-bg-primary">Tampilkan</span> untuk menampilkan <mark>Tabel Kunjungan</mark></h6>
                </div>
                <div class="text-end btn-page mb-0">
                    <a class="btn btn-link-secondary" id="clear_text" href="javascript: void(0);" onclick="clearFilter()">Kosongkan</a>
                    <button type="button" class="btn btn-shadow btn-primary " onclick="filter()" data-bs-toggle="tooltip"
                    data-bs-offset="0,4" data-bs-placement="bottom" data-bs-html="true" title="Menampilkan Data Kunjungan"
                    id="tombol-tampilkan"><i class="fas fa-filter align-middle me-2"></i> Tampilkan</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12" id="showTable" hidden>
        <div class="card">
            <div class="card-header p-3">
                <div class="d-sm-flex align-items-center justify-content-between ms-2">
                    <h6 class="mt-2"><i class="fas fa-table text-primary me-2"></i> Tabel Kunjungan</h6>
                    <div class="dropdown">
                        <h6 class="mt-2">Waktu Update <a id="show-time" class="text-primary"><div class="ms-2 spinner-border text-primary spinner-border-sm" role="status"></div></a></h6>
                    </div>
                </div>
            </div>
            <div class="card-body pt-3">
                <div class="table-responsive">
                    <table class="table table-striped" id="dttable">
                        <thead>
                            <tr>
                                <th>Aksi</th>
                                <th><center>Kunjungan Pasien</center></th>
                                <th><center>Masuk Ruangan</center></th>
                                <th><center>Keluar Ruangan</center></th>
                            </tr>
                        </thead>
                        <tbody id="tampil-tbody">
                            <tr style='font-size:13px'>
                                <td colspan="15">
                                    <center>
                                        <div class="spinner-border spinner-border-sm" role="status"></div>
                                    </center>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="col-md-12">
        <div class="card">
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
    </div> --}}
</div>

<script>
    $(document).ready(function() {
        // $('#xpoli').on('change', function() {
        //     if (this.value) {
        //         $("#xtgl").prop('disabled', false);
        //     }
        // });
        const today = new Date(); // Hari ini
        const fiveYearsAgo = new Date();
        fiveYearsAgo.setFullYear(today.getFullYear() - 5); // 5 tahun ke belakang
        $("#filter_tgl").flatpickr(
            {
                // enableTime: true,
                // dateFormat: "Y-m-d H:i",
                mode: 'range',
                minDate: fiveYearsAgo, // Mulai dari 5 tahun yang lalu
                maxDate: today,        // Sampai hari ini
                dateFormat: 'Y-m-d',
                defaultDate: [today,today]
            }
        );

        // SELECT CHOICES
        elm = $('#filter_dpjp')[0];
        choices = new Choices(elm);
        filter();
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
                    console.log(item.NOPEN);
                    content += `<tr>
                                    <td>
                                        <div class="dropdown">
                                            <a class="avtar avtar-s btn-light-secondary dropdown-toggle arrow-none" href="#"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ti ti-chevron-down f-18"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end" style="">
                                                <a class="dropdown-item" href="/pelayanan/pasien/resume/${item.NOMOR}">Resume Medis</a>
                                                <a class="dropdown-item" href="/pelayanan/pasien/identitas/${item.NOMOR}">Detail Kunjungan</a>
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
                    // dom: 'Bfrtip',
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
    function filter() {
        $('#showTable').prop('hidden',false);
        $('#tombol-tampilkan').prop('disabled',true).find('i').removeClass('fa-filter').addClass('fa-sync fa-spin');
        $("#show-time").empty().html('<div class="ms-2 spinner-border text-primary spinner-border-sm" role="status"></div>');
        // if (status == 0) {
        //     $("#btn-refresh-0").prop('disabled',true).removeClass('btn-light-danger btn-danger').addClass('btn-danger');
        //     $("#btn-refresh-1").removeClass('btn-light-warning btn-warning').addClass('btn-light-warning');
        //     $("#btn-refresh-2").removeClass('btn-light-primary btn-primary').addClass('btn-light-primary');
        // } else {
        //     if (status == 1) {
        //         $("#btn-refresh-0").removeClass('btn-light-danger btn-danger').addClass('btn-light-danger');
        //         $("#btn-refresh-1").prop('disabled',true).removeClass('btn-light-warning btn-warning').addClass('btn-warning');
        //         $("#btn-refresh-2").removeClass('btn-light-primary btn-primary').addClass('btn-light-primary');
        //     } else {
        //         $("#btn-refresh-0").removeClass('btn-light-danger btn-danger').addClass('btn-light-danger');
        //         $("#btn-refresh-1").removeClass('btn-light-warning btn-warning').addClass('btn-light-warning');
        //         $("#btn-refresh-2").prop('disabled',true).removeClass('btn-light-primary btn-primary').addClass('btn-primary');
        //     }
        // }
        $("#tampil-tbody").empty().append(`<tr style='font-size:13px'><td colspan="15"><center><div class="spinner-border spinner-border-sm" role="status"></div></center></td></tr>`);
        // Initialize
        var status = $("#filter_status").val();
        var tgl = $("#filter_tgl").val();
        var dpjp = $("#filter_dpjp").val() ? $("#filter_dpjp").val() : '0'; // JIKA DPJP KOSONG = 0
        var exTgl = tgl.split(' to ');
        if (exTgl.length == 2) { // SPLIT FROM = "2024-01-01 to 2025-01-01"
            tgls = exTgl[0];
            tgle = exTgl[1];
        } else { // SPLIT FROM = "2024-01-01"
            tgls = exTgl[0];
            tgle = exTgl[0];
        }
        console.log(exTgl);
        // Process
        $.ajax({
            url: `/api/pelayanan/pasien/rj/${status}/${tgls}/${tgle}/${dpjp}`,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                // $("#list").empty();
                $("#show-time").empty().text(res.time);
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
                                                <a class="dropdown-item" href="/pelayanan/pasien/resume/${item.NOMOR}">Resume Medis</a>
                                                <a class="dropdown-item" href="/pelayanan/pasien/identitas/${item.NOMOR}">Detail Kunjungan</a>
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
                })
                // Showing Tooltip
                $('[data-bs-toggle="tooltip"]').tooltip({
                    trigger : 'hover'
                })
                var table = $('#dttable').DataTable({
                    // dom: 'Bfrtip',
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
                    buttons: ['pdf', 'excel']//, 'copy', 'colvis']
                });
                $('#tombol-tampilkan').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-filter');
            }, error: function(xhr, status, error) {
                // Gagal: tangani error di sini
                console.error('Terjadi kesalahan:', error);
                // Bisa juga tampilkan alert
                alert('Gagal mengambil data. Coba lagi.');
                $('#tombol-tampilkan').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-filter');
            }
        })
        // $("#btn-refresh-0").prop('disabled',false);
        // $("#btn-refresh-1").prop('disabled',false);
        // $("#btn-refresh-2").prop('disabled',false);
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
