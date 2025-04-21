@extends('layouts.index')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Digital</a></li>
                    <li class="breadcrumb-item" aria-current="page">Monitoring</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Monitoring Pasien</h2>
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
                    <div class="col-md-2 mb-3">
                        <div class="form-group">
                            <label class="form-label">Status Perawatan</label>
                            <select class="form-control" id="filter_rawat" disabled>
                                <option value="5">Semua Perawatan</option>
                                <option value="1" selected>Rawat Jalan</option>
                                <option value="2">Rawat Darurat</option>
                                <option value="3">Rawat Inap</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <div class="form-group">
                            <label class="form-label">Status Kunjungan</label>
                            <select class="form-control" id="filter_status">
                                <option value="5">Semua Kunjungan</option>
                                <option value="0">Batal Kunjungan</option>
                                <option value="1" selected>Sedang Dilayani</option>
                                <option value="2">Selesai Kunjungan</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
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
                    <h6 class="text-muted ms-2 mt-3">Tekan tombol <span class="badge text-bg-primary">Tampilkan</span> untuk menampilkan <mark>Tabel Kunjungan</mark></h6>
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
                    <h6 class="mt-2"><i class="fas fa-table text-primary me-2"></i> Tabel</h6>
                    <div class="dropdown">
                        <h6 class="mt-2">Waktu Update <a id="show-time" class="text-primary"><div class="ms-2 spinner-border text-primary spinner-border-sm" role="status"></div></a></h6>
                    </div>
                </div>
            </div>
            <div class="card-body p-1 pb-3">
                <div class="table-responsive">
                    <table class="table table-striped" id="dttable">
                        <thead>
                            <tr>
                                <th rowspan="2"><center>Kunjungan Pasien</center></th>
                                <th rowspan="2">Tanggal Kunjungan</th>
                                <th colspan="7"><center>Monitoring</center></th>
                            </tr>
                            <tr>
                                <th>TDKN</th>
                                <th>CPPT</th>
                                <th>ICD9</th>
                                <th>ICD10</th>
                                <th>TTE</th>
                                <th>SKDP</th>
                                <th>SEP</th>
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
</div>

{{-- MODAL STARTED --}}
<div id="showTindakan" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="showTindakanLabel">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showTindakanLabel"><span class="badge text-bg-secondary">DAFTAR TINDAKAN</span> | IDKUNJUNGAN : <a id="show-id-tindakan" class="text-primary"></a></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <small><a><b>Tabel di bawah diurutkan berdasarkan <mark>TANGGAL</mark> datarecord Tindakan pertama kali dimasukkan saat kunjungan pada tanggal tsb</b></a></small>
                <div class="table-responsive mt-2">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10%;">TANGGAL</th>
                                <th style="width: 40%;">NAMA TINDAKAN</th>
                                <th style="width: 30%;">PETUGAS MEDIS</th>
                                <th style="width: 20%;">USER INPUT</th>
                            </tr>
                        </thead>
                        <tbody id="tampil-tindakan">
                            <tr>
                                <td colspan="15">
                                    <center>
                                        <div class="spinner-border spinner-border-sm" role="status"></div>
                                    </center>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                {{-- <a href="#!" class="tooltip-test" data-bs-toggle="tooltip" title="Tooltip" data-container="#showCppt">that link</a> --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                {{-- <button type="button" class="btn btn-primary"></button> --}}
            </div>
        </div>
    </div>
</div>
<div id="showCppt" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="showCpptLabel">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showCpptLabel"><span class="badge text-bg-secondary">CPPT</span> | NORM.<a id="show-norm-cppt" class="text-primary"></a> | IDKUNJUNGAN : <a id="show-id-cppt" class="text-primary"></a></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <small><a><b>Tabel di bawah diurutkan berdasarkan <mark>TANGGAL</mark> datarecord CPPT pertama kali saat kunjungan pada tanggal tsb</b></a></small>
                <div class="table-responsive mt-2">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10%;">TANGGAL</th>
                                <th style="width: 40%;">CATATAN</th>
                                <th style="width: 20%;">PPA</th>
                                <th style="width: 10%;">JENIS</th>
                                <th style="width: 20%;">VERIFIKASI</th>
                            </tr>
                        </thead>
                        <tbody id="tampil-cppt">
                            <tr>
                                <td colspan="15">
                                    <center>
                                        <div class="spinner-border spinner-border-sm" role="status"></div>
                                    </center>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                {{-- <a href="#!" class="tooltip-test" data-bs-toggle="tooltip" title="Tooltip" data-container="#showCppt">that link</a> --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                {{-- <button type="button" class="btn btn-primary"></button> --}}
            </div>
        </div>
    </div>
</div>
<div id="showSKDP" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="showSKDPLabel">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showSKDPLabel"><span class="badge text-bg-secondary">SURAT RENCANA KONTROL</span> | IDKUNJUNGAN : <a id="show-id-skdp" class="text-primary"></a></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div id="cetak-skdp"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                {{-- <button type="button" class="btn btn-primary"></button> --}}
            </div>
        </div>
    </div>
</div>
<div id="showSEP" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="showSEPLabel">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showSEPLabel"><span class="badge text-bg-secondary">SEP</span> | IDKUNJUNGAN : <a id="show-id-sep" class="text-primary"></a></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div id="cetak-sep"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                {{-- <button type="button" class="btn btn-primary"></button> --}}
            </div>
        </div>
    </div>
</div>
{{-- MODAL ENDED --}}
<script>
    $(document).ready(function() {
        // $('#xpoli').on('change', function() {
        //     if (this.value) {
        //         $("#xtgl").prop('disabled', false);
        //     }
        // });

        // FLATPICKR DATE
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

        // refresh(1);
    });

    // function-function
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
            url: `/api/monitoring/rj/${status}/${tgls}/${tgle}/${dpjp}`,
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
                    // START VERIFIKASI SEP
                    if (item.NOSEP) {
                        valSEP = item.NOSEP.substring(8, 12); // 0624
                        parts = item.TGLSEP.split("-"); // hasil: ['08', '06', '2024'] || e.g. 2024-01-12 00:00:00
                        valTGLSEP = parts[1]+parts[0].slice(-2); // '0624'
                        console.log(valSEP);
                        console.log(valTGLSEP);
                        if (valSEP == valTGLSEP) {
                            SEP = '<b class="text-indigo-900">'+item.NOSEP+'</b>';
                            btnSEP = `<button type="button" class="btn btn-sm btn-icon btn-link-success" id="sep`+item.NOMOR+`" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat Detail SEP" onclick="showSEP('`+item.NOMOR+`')">
                                            <i class="fas fa-check text-success"></i>
                                        </button>`;
                        } else {
                            SEP = '<b class="text-danger">Tanggal SEP Tidak Sesuai!</b>';
                            btnSEP = `<button type="button" class="btn btn-sm btn-icon btn-link-danger" id="sep`+item.NOMOR+`" data-bs-toggle="tooltip" data-bs-placement="bottom" title="No.SEP tidak sesuai dengan Tanggal SEP" onclick="showSEP('`+item.NOMOR+`')">
                                            <i class="fas fa-check fs-5 text-danger"></i>
                                        </button>`;
                        }
                    } else {
                        SEP = '<b class="text-secondary">SEP Tidak Ditemukan</b>';
                        btnSEP = `<button type="button" class="btn btn-sm btn-icon btn-link-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="SEP tidak ditemukan">
                                        <i class="fas fa-times fs-5 text-secondary"></i>
                                    </button>`;
                    }
                    // END VERIFIKASI SEP
                    content += `<tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="{{ asset('/images/user.png') }}" alt="user image"
                                                    class="img-radius wid-40" />
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h4 class="mb-1"><b data-bs-toggle="tooltip" data-bs-placement="bottom" title="Nomor Rekam Medis">RM.${item.NORM}</b> - <b class="text-primary">${item.NAMAPASIEN}</b></h4>
                                                <a class="mb-0 text-dark" href="javascript: void(0);">DPJP : ${item.NAMADOKTER}</a><br>
                                                <a class="mb-2 text-dark" href="javascript: void(0);">
                                                    <code>
                                                        Ruangan <b class="text-pink-900">${item.NAMARUANGAN}</b> | <b class="text-teal-900" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Nomor Registrasi">${item.NOPEN}</b>
                                                        | <b class="text-indigo-900" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Nomor SEP">${SEP}</b>
                                                    </code>
                                                </a><br>
                                                <a class="mb-2 text-dark" href="javascript: void(0);"><code>${status}</code></a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-link-secondary text-muted btn-sm mb-0">Daftar <span class="badge bg-light text-dark ms-2">${item.TGLDAFTAR}</span></button><br>
                                        <button type="button" class="btn btn-link-info btn-sm mb-0">Masuk <span class="badge bg-light text-dark ms-2">${item.MASUK}</span></button><br>
                                        <button type="button" class="btn btn-link-danger btn-sm mb-0">Keluar <span class="badge bg-light text-dark ms-2">${item.KELUAR?item.KELUAR:'-'}</span></button>
                                    </td>
                                    <td>${item.TGLTINDAKAN?`
                                        <button type="button" class="btn btn-sm btn-icon btn-link-success" id="tdk`+item.NOMOR+`" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat Detail Tindakan" onclick="showTindakan('`+item.NOMOR+`')">
                                            <i class="fas fa-check text-success"></i>
                                        </button>`:`
                                        <button type="button" class="btn btn-sm btn-icon btn-link-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Tindakan tidak ditemukan">
                                            <i class="fas fa-times fs-5 text-secondary"></i>
                                        </button>`}
                                    </td>
                                    <td>${item.TGLCPPT?`
                                        <button type="button" class="btn btn-sm btn-icon btn-link-success" id="cppt`+item.NOMOR+`" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat Detail CPPT" onclick="showCppt('`+item.NOMOR+`')">
                                            <i class="fas fa-check text-success"></i>
                                        </button>`:`
                                        <button type="button" class="btn btn-sm btn-icon btn-link-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="CPPT tidak ditemukan">
                                            <i class="fas fa-times fs-5 text-secondary"></i>
                                        </button>`}
                                    </td>
                                    <td><button type="button" class="btn btn-sm btn-icon btn-link-light" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat Detail"><i class="fas fa-question text-secondary"></i></button></td>
                                    <td><button type="button" class="btn btn-sm btn-icon btn-link-light" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat Detail"><i class="fas fa-question text-secondary"></i></button></td>
                                    <td><button type="button" class="btn btn-sm btn-icon btn-link-light" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat Detail"><i class="fas fa-question text-secondary"></i></button></td>
                                    <td>${item.NOMORBOOKING?`
                                        <button type="button" class="btn btn-sm btn-icon btn-link-success" id="skdp`+item.NOMOR+`" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat Detail SKDP" onclick="showSKDP('`+item.NOMOR+`')">
                                            <i class="fas fa-check text-success"></i>
                                        </button>`:`
                                        <button type="button" class="btn btn-sm btn-icon btn-link-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="SKDP tidak ditemukan">
                                            <i class="fas fa-times fs-5 text-secondary"></i>
                                        </button>`}
                                    </td>
                                    <td>${btnSEP}
                                    </td>
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
                        { sWidth: '65%' },
                        { sWidth: '14%' },
                        { sWidth: '3%' },
                        { sWidth: '3%' },
                        { sWidth: '3%' },
                        { sWidth: '3%' },
                        { sWidth: '3%' },
                        { sWidth: '3%' },
                        { sWidth: '3%' },
                    ],
                    columnDefs: [
                        // { visible: false, targets: [7] },
                        { targets: [0], sortable: false },
                        { targets: [1], sortable: false },
                    ],
                    displayLength: 20,
                    lengthChange: true,
                    lengthMenu: [20, 50, 75, 100, 250, 500, 1000, 3000, 7000, 15000, 50000, 100000],
                    buttons: ['excel', 'pdf'] // 'copy','colvis'
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

    function showTindakan(kunjungan) {
        // console.log($(this).find('i'));
        $('#show-id-tindakan').text(kunjungan);
        $('#tdk'+kunjungan).find('i').removeClass('fa-check').addClass('fa-sync fa-spin');
        $.ajax({
            url: "/api/pasien/"+kunjungan+"/tindakan",
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                $("#tampil-tindakan").empty();
                if (res.show.length != 0) {
                    res.show.forEach(item => {
                        content = ``;
                        content += `<tr>
                                        <td class="custom-column">${item.TANGGAL}</td>
                                        <td class="custom-column">${item.NAMATINDAKAN}<br><span class="badge rounded-pill text-bg-primary">Kategori : ${item.JENISTINDAKAN}</span>&nbsp;<span class="badge rounded-pill text-bg-secondary">ID#${item.ID}</span></td>
                                        <td class="custom-column">${item.TENAGAMEDIS?'<b class="text-gray-800">'+item.TENAGAMEDIS+'</b>':'<b class="text-danger">Paramedis belum terisi/Tidak Ditemukan</b>'}</td>
                                        <td class="custom-column">${item.NAMAUSER?'Dimasukkan Oleh<br><b class="text-pink-900">'+item.NAMAUSER+'</b>':'-'}</td>
                                    </tr>
                        `;
                        $('#tampil-tindakan').append(content);
                    })
                    $('#showTindakan').modal('show');
                    $('#tdk'+kunjungan).find('i').removeClass('fa-sync fa-spin').addClass('fa-check');
                } else {
                    // Swal.fire({
                    //     position: "top-end",
                    //     icon: "error",
                    //     title: "CPPT Belum Terisi",
                    //     showConfirmButton: false,
                    //     timer: 1500,
                    //     backdrop: `
                    //         rgba(0,0,123,0.4)
                    //         url("/images/nyan-cat.gif")
                    //         left top
                    //         no-repeat
                    //     `
                    // });
                    iziToast.error({
                        title: 'Maaf!',
                        message: 'Data Tindakan tidak ditemukan / belum diisi',
                        position: 'topRight'
                    });
                }
            }
        })
    }

    function showCppt(kunjungan) {
        // console.log($(this).find('i'));
        $('#show-id-cppt').text(kunjungan);
        $('#cppt'+kunjungan).find('i').removeClass('fa-check').addClass('fa-sync fa-spin');
        $.ajax({
            url: "/api/pasien/"+kunjungan+"/cppt",
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                $("#tampil-cppt").empty();
                $('#show-norm-cppt').text(res.pen.NORM);
                if (res.show.length != 0) {
                    res.show.forEach(item => {
                        content = ``;
                        content += `<tr>
                                        <td class="custom-column">${item.TANGGAL}</td>
                                        <td class="custom-column">${item.CATATAN}<br>${item.INSTRUKSI?"<b>I/ : </b>"+item.INSTRUKSI:''}</td>
                                        <td class="custom-column">${item.PPA}<br><span class="badge rounded-pill text-bg-primary">${item.JNSPPA}</span></td>
                                        <td class="custom-column">${item.TBAK_SBAR?item.TBAK_SBAR:'-'}</td>
                                        <td class="custom-column">${item.VERIFIKASI?'Diverifkasi Oleh<br><b class="text-success">'+item.VERIFIKATOR+'</b><br>Pada '+item.TGLVERIFIKASI:'Belum Diverifikasi'}</td>
                                    </tr>
                        `;
                        $('#tampil-cppt').append(content);
                    })
                    $('#showCppt').modal('show');
                    $('#cppt'+kunjungan).find('i').removeClass('fa-sync fa-spin').addClass('fa-check');
                } else {
                    // Swal.fire({
                    //     position: "top-end",
                    //     icon: "error",
                    //     title: "CPPT Belum Terisi",
                    //     showConfirmButton: false,
                    //     timer: 1500,
                    //     backdrop: `
                    //         rgba(0,0,123,0.4)
                    //         url("/images/nyan-cat.gif")
                    //         left top
                    //         no-repeat
                    //     `
                    // });
                    iziToast.error({
                        title: 'Maaf!',
                        message: 'Data CPPT tidak ditemukan / belum diisi',
                        position: 'topRight'
                    });
                }
            }
        })
    }

    function showSKDP(kunjungan) {
        $('#show-id-skdp').text(kunjungan);
        $('#skdp'+kunjungan).find('i').removeClass('fa-check').addClass('fa-sync fa-spin');

        fetch("/api/pasien/"+kunjungan+"/skdp")
        .then(response => {
            if (!response.ok) {
                throw new Error('File tidak ditemukan atau gagal diambil.');
            }
            return response.blob();
        })
        .then(blob => {
            // Buat object URL dari blob
            const fileURL = URL.createObjectURL(blob);

            // Tampilkan ke iframe dalam modal
            $('#cetak-skdp').empty().html(`<iframe src="${fileURL}" width="100%" height="500px" frameborder="0"></iframe>`);
            $('#showSKDP').modal('show');
            $('#skdp'+kunjungan).find('i').removeClass('fa-sync fa-spin').addClass('fa-check');
        })
        .catch(error => {
            iziToast.error({
                title: 'Maaf!',
                message: 'Data SKDP tidak ditemukan atau belum dibuatkan oleh Simgos.',
                position: 'topRight'
            });
            console.error(error);
        });
    }

    function showSEP(kunjungan) {
        $('#show-id-sep').text(kunjungan);
        $('#sep'+kunjungan).find('i').removeClass('fa-check').addClass('fa-sync fa-spin');

        fetch("/api/pasien/"+kunjungan+"/sep")
        .then(response => {
            if (!response.ok) {
                throw new Error('File tidak ditemukan atau gagal diambil.');
            }
            return response.blob();
        })
        .then(blob => {
            // Buat object URL dari blob
            const fileURL = URL.createObjectURL(blob);

            // Tampilkan ke iframe dalam modal
            $('#cetak-sep').empty().html(`<iframe src="${fileURL}" width="100%" height="500px" frameborder="0"></iframe>`);
            $('#showSEP').modal('show');
            $('#sep'+kunjungan).find('i').removeClass('fa-sync fa-spin').addClass('fa-check');
        })
        .catch(error => {
            iziToast.error({
                title: 'Maaf!',
                message: 'Data SEP tidak ditemukan atau belum digenerate.',
                position: 'topRight'
            });
            console.error(error);
        });
    }

    function showKlaim(kunjungan) {
        iziToast.success({
            title: 'Yeayy!',
            message: 'Tombol ini nantinya akan memunculkan Berkas Klaim Pasien secara instan dan efektif dengan Nomor Kunjungan '+kunjungan,
            position: 'topRight'
        });
    }

    function clearFilter() {
        // GET TODAY DATE
        const today = new Date(); // Hari ini
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0'); // bulan dimulai dari 0
        const dd = String(today.getDate()).padStart(2, '0');

        // CHANGE FILTER VALUE
        $("#filter_status").val('1');
        $("#filter_dpjp").val('');
        // $('#filter_tgl').val(`${yyyy}-${mm}-${dd}`).trigger('change'); // update select
        // $("#filter_tgl").val().change();

        // EMPTY TABLE AND HIDE DIV
        $("#tampil-tbody").empty();
        $('#showTable').prop('hidden',true);
    }
</script>
@endsection
