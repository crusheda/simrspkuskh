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
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Smart Klaim</a></li>
                    <li class="breadcrumb-item" aria-current="page">Rawat Jalan</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Daftar Kunjungan Pasien BPJS</h2>
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
                            <button class="btn btn-light-danger shadow" onclick="refresh(0)" id="btn-refresh-0"><i class="fas fa-sync me-2"></i> Batal Kunjungan</button>
                            <button class="btn btn-warning shadow" onclick="refresh(1)" id="btn-refresh-1"><i class="fas fa-sync me-2"></i> Sedang Dilayani</button>
                            <button class="btn btn-light-primary shadow" onclick="refresh(2)" id="btn-refresh-2"><i class="fas fa-sync me-2"></i> Selesai Kunjungan</button>
                        </div>
                        {{-- <button class="btn btn-info" disabled>Button 2</button> --}}
                    </div>
                    <div class="dropdown">
                        <h6>Waktu Update <a id="show-time" class="text-primary"><div class="ms-2 spinner-border text-primary spinner-border-sm" role="status"></div></a></h6>
                        {{-- <a class="avtar avtar-s btn-link-secondary dropdown-toggle arrow-none" href="javascript: void(0);"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ti ti-dots-vertical f-18"></i>
                        </a> --}}
                        <div class="dropdown-menu dropdown-menu-end">
                            {{-- <a class="dropdown-item" href="javascript: void(0);" onclick="refresh(0)">Batal Kunjungan</a>
                            <a class="dropdown-item" href="javascript: void(0);" onclick="refresh(1)">Sedang Dilayani</a>
                            <a class="dropdown-item" href="javascript: void(0);" onclick="refresh(2)">Selesai Kunjungan</a> --}}
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
                    <table class="table table-striped table-hover" id="dttable">
                        <thead>
                            <tr>
                                <th rowspan="2">Aksi</th>
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
<div id="showCppt" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="showCpptLabel">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showCpptLabel">NORM.<a id="show-norm-cppt" class="text-primary"></a> | IDKUNJUNGAN : <a id="show-id-cppt" class="text-primary"></a></h5>
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
<div id="showSEP" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="showSEPLabel">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showSEPLabel"></a>IDKUNJUNGAN : <a id="show-id-sep" class="text-primary"></a></h5>
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
        refresh(1);
    });

    // function-function
    function refresh(status) {
        $("#show-time").empty().html('<div class="ms-2 spinner-border text-primary spinner-border-sm" role="status"></div>');
        if (status == 0) {
            $("#btn-refresh-0").prop('disabled',true).removeClass('btn-light-danger btn-danger').addClass('btn-danger');
            $("#btn-refresh-1").removeClass('btn-light-warning btn-warning').addClass('btn-light-warning');
            $("#btn-refresh-2").removeClass('btn-light-primary btn-primary').addClass('btn-light-primary');
        } else {
            if (status == 1) {
                $("#btn-refresh-0").removeClass('btn-light-danger btn-danger').addClass('btn-light-danger');
                $("#btn-refresh-1").prop('disabled',true).removeClass('btn-light-warning btn-warning').addClass('btn-warning');
                $("#btn-refresh-2").removeClass('btn-light-primary btn-primary').addClass('btn-light-primary');
            } else {
                $("#btn-refresh-0").removeClass('btn-light-danger btn-danger').addClass('btn-light-danger');
                $("#btn-refresh-1").removeClass('btn-light-warning btn-warning').addClass('btn-light-warning');
                $("#btn-refresh-2").prop('disabled',true).removeClass('btn-light-primary btn-primary').addClass('btn-primary');
            }
        }
        $("#tampil-tbody").empty().append(`<tr style='font-size:13px'><td colspan="15"><center><div class="spinner-border spinner-border-sm" role="status"></div></center></td></tr>`);
        $.ajax({
            url: "/api/klaim/smart/rj/"+status,
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
                    // console.log(item.NOMOR);
                    content += `<tr>
                                    <td>
                                        <div class="dropdown">
                                            <a class="avtar avtar-s btn-light-secondary dropdown-toggle arrow-none" href="#"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ti ti-chevron-down f-18"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end" style="">
                                                <a class="dropdown-item" href="#" onclick="showKlaim('${item.NOMOR}')">Berkas Klaim</a>
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
                                                <h4 class="mb-1"><b data-bs-toggle="tooltip" data-bs-placement="bottom" title="Nomor Rekam Medis">RM.${item.NORM}</b> - <b class="text-primary">${item.NAMAPASIEN}</b></h4>
                                                <a class="mb-0 text-dark" href="javascript: void(0);">DPJP : ${item.NAMADOKTER}</a><br>
                                                <a class="mb-2 text-dark" href="javascript: void(0);">
                                                    <code>
                                                        Ruangan <b class="text-pink-900">${item.NAMARUANGAN}</b> | <b class="text-teal-900" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Nomor Registrasi">${item.NOPEN}</b>
                                                        | <b class="text-indigo-900" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Nomor SEP">${item.NOSEP?item.NOSEP:'<b class="text-danger">SEP Tidak Ditemukan</b>'}</b>
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
                                    <td><button type="button" class="btn btn-sm btn-icon btn-link-light" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat Detail"><i class="fas fa-question text-secondary"></i></button></td>
                                    <td>${item.TGLCPPT?`
                                        <button type="button" class="btn btn-sm btn-icon btn-link-success" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat Detail CPPT" onclick="showCppt('`+item.NOMOR+`')">
                                            <i class="fas fa-check text-success"></i>
                                        </button>`:`
                                        <button type="button" class="btn btn-sm btn-icon btn-link-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="CPPT tidak ditemukan" onclick="showCppt('`+item.NOMOR+`')">
                                            <i class="fas fa-times fs-5 text-danger"></i>
                                        </button>`}
                                    </td>
                                    <td><button type="button" class="btn btn-sm btn-icon btn-link-light" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat Detail"><i class="fas fa-question text-secondary"></i></button></td>
                                    <td><button type="button" class="btn btn-sm btn-icon btn-link-light" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat Detail"><i class="fas fa-question text-secondary"></i></button></td>
                                    <td><button type="button" class="btn btn-sm btn-icon btn-link-light" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat Detail"><i class="fas fa-question text-secondary"></i></button></td>
                                    <td><button type="button" class="btn btn-sm btn-icon btn-link-light" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat Detail"><i class="fas fa-question text-secondary"></i></button></td>
                                    <td>${item.NOSEP?`
                                        <button type="button" class="btn btn-sm btn-icon btn-link-success" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat Detail SEP" onclick="showSEP('`+item.NOMOR+`')">
                                            <i class="fas fa-check text-success"></i>
                                        </button>`:`
                                        <button type="button" class="btn btn-sm btn-icon btn-link-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="SEP tidak ditemukan">
                                            <i class="fas fa-times fs-5 text-danger"></i>
                                        </button>`}
                                    </td>
                                </tr>`;
                    $('#tampil-tbody').append(content);
                })
                var table = $('#dttable').DataTable({
                    dom: 'Bfrtip',
                    order: [
                        [2, "desc"]
                    ],
                    bAutoWidth: false,
                    aoColumns : [
                        { sWidth: '5%' },
                        { sWidth: '60%' },
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
                        { targets: [2], sortable: false },
                    ],
                    displayLength: 10,
                    lengthChange: true,
                    lengthMenu: [10, 25, 50, 75, 100],
                    buttons: ['excel', 'pdf'] // 'copy','colvis'
                });
                // Showing Tooltip
                $('[data-bs-toggle="tooltip"]').tooltip({
                    trigger : 'hover'
                })
            }
        })
        $("#btn-refresh-0").prop('disabled',false);
        $("#btn-refresh-1").prop('disabled',false);
        $("#btn-refresh-2").prop('disabled',false);
    }

    function showCppt(kunjungan) {
        // console.log($(this).find('i'));
        $('#show-id-cppt').text(kunjungan);
        $(this).find('i').removeClass('fa-check').addClass('fa-sync fa-spin');
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

    function showSEP(kunjungan) {
        $('#show-id-sep').text(kunjungan);
        $(this).find('i').removeClass('fa-check').addClass('fa-sync fa-spin');

        fetch("/api/klaim/smart/rj/sep/" + kunjungan)
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
            $('#cetak-sep').html(`<iframe src="${fileURL}" width="100%" height="500px" frameborder="0"></iframe>`);
            $('#showSEP').modal('show');
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
            message: 'Tombol itu akan memunculkan Berkas Klaim Pasien dengan Nomor Kunjungan '+kunjungan,
            position: 'topRight'
        });
    }
</script>
@endsection
