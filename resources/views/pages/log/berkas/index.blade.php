@extends('layouts.index')

@section('content')
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Log</a></li>
                        <li class="breadcrumb-item" aria-current="page">Berkas Klaim</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Riwayat <b class="text-primary">Berkas</b> / <b class="text-primary">Generated File</b> History</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row gy-4">
        <div class="col-md-3">
            <div class="card statistics-card-1 mb-0">
                <div class="card-body">
                    <img src="{{ asset('images/widget/img-status-2.svg') }}" alt="img" class="img-fluid img-bg">
                    <div class="d-flex align-items-center">
                        <div class="avtar bg-brand-color-1 text-white me-3">
                            <i class="ph-duotone ph-files f-26"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0">Total Kapasitas Disk</p>
                            <div class="d-flex align-items-end">
                                <h2 class="mb-0 f-w-500" id="disk_total"><div class="ms-2 spinner-border text-primary spinner-border-sm" role="status"></div></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card statistics-card-1 mb-0">
                <div class="card-body">
                    <img src="{{ asset('images/widget/img-status-1.svg') }}" alt="img" class="img-fluid img-bg">
                    <div class="d-flex align-items-center">
                        <div class="avtar bg-brand-color-2 text-white me-3">
                            <i class="ph-duotone ph-file-arrow-down f-26"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0">Total Kapasitas Terpakai</p>
                            <div class="d-flex align-items-end">
                                <h2 class="mb-0 f-w-500" id="disk_used"><div class="ms-2 spinner-border text-primary spinner-border-sm" role="status"></div></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card statistics-card-1 mb-0">
                <div class="card-body">
                    <img src="{{ asset('images/widget/img-status-3.svg') }}" alt="img" class="img-fluid img-bg">
                    <div class="d-flex align-items-center">
                        <div class="avtar bg-brand-color-4 text-white me-3">
                            <i class="ph-duotone ph-file-arrow-up f-26"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0">Total Kapasitas Tersedia</p>
                            <div class="d-flex align-items-end">
                                <h2 class="mb-0 f-w-500" id="disk_free"><div class="ms-2 spinner-border text-primary spinner-border-sm" role="status"></div></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card statistics-card-1 mb-0">
                <div class="card-body">
                    <img src="{{ asset('images/widget/img-status-5.svg') }}" alt="img" class="img-fluid img-bg">
                    <div class="d-flex align-items-center">
                        <div class="avtar bg-brand-color-3 text-white me-3">
                            <i class="ph-duotone ph-file-pdf f-26"></i>
                        </div>
                        <div>
                            <p class="text-muted mb-0">Total Kapasitas PDF Terpakai</p>
                            <div class="d-flex align-items-end">
                                <h2 class="mb-0 f-w-500" id="size_storage"><div class="ms-2 spinner-border text-primary spinner-border-sm" role="status"></div></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header p-3">
                    <div class="d-sm-flex align-items-center justify-content-between ms-2">
                        <h6 class="mt-2"><i class="fas fa-table text-primary me-2"></i> Tabel Riwayat</h6>
                        <div class="dropdown">
                            <h6 class="mt-2">Waktu Update <a id="show-time" class="text-primary"><div class="ms-2 spinner-border text-primary spinner-border-sm" role="status"></div></a></h6>
                        </div>
                    </div>
                </div>
                <div class="card-body p-1 pb-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="dttable">
                            <thead>
                                <tr>
                                    <th><center>ID</center></th>
                                    <th>JENIS</th>
                                    <th>NOMOR KUNJUNGAN</th>
                                    <th>NAMA FILE</th>
                                    <th>PATH FILE</th>
                                    <th>DIPERBARUI</th>
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

    <script>
        $(document).ready(function() {
            refresh();
        })

        function refresh() {
            $('#show-time').empty().append('<div class="ms-2 spinner-border text-primary spinner-border-sm" role="status"></div>');
            $('#disk_total').empty().append('<div class="ms-2 spinner-border text-primary spinner-border-sm" role="status"></div>');
            $('#disk_used').empty().append('<div class="ms-2 spinner-border text-primary spinner-border-sm" role="status"></div>');
            $('#disk_free').empty().append('<div class="ms-2 spinner-border text-primary spinner-border-sm" role="status"></div>');
            $('#size_storage').empty().append('<div class="ms-2 spinner-border text-primary spinner-border-sm" role="status"></div>')
            $.ajax({
                url: `/api/log/berkas/table`,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    // INISIAL VAL
                    $('#show-time').text(res.now);
                    $('#disk_total').text(res.disk_total);
                    $('#disk_used').text(res.disk_used);
                    $('#disk_free').text(res.disk_free);
                    $('#size_storage').text(res.size_storage);

                    // TABLE
                    if ($.fn.DataTable.isDataTable('#dttable')) {
                        $('#dttable').DataTable().clear().destroy();
                    }
                    if (res.show && Array.isArray(res.show)) {
                        var content = ``;
                        res.show.forEach(item => {
                            const jenisMap = {
                                1: 'SEP',
                                2: 'Resume Medis',
                                3: 'SKDP',
                                4: 'Individual',
                                5: 'Billing',
                                6: 'Laboratorium',
                                7: 'Radiologi',
                                8: 'Triage',
                                9: 'Operasi',
                                10: 'Berkas Tambahan',
                                11: 'Berkas Rehab',
                                12: 'Konsul'
                            };
                            content += `
                                <tr id="data${item.id}">
                                    <td class="text-center">${item.id}</td>
                                    <td>
                                        ${jenisMap[item.jenis] || '<a class="text-danger">Tidak Diketahui</a>'}
                                        ${item.sub_jenis?'<span class="badge text-bg-secondary me-1">#'+item.sub_jenis+'</span>':''}
                                        ${item.kode?'<span class="badge text-bg-warning me-1">SUB#'+item.kode+'</span>':''}
                                        ${item.ref?'<span class="badge text-bg-danger me-1">REF#'+item.ref+'</span>':''}
                                    </td>
                                    <td>${item.nomor}</td>
                                    <td>${item.title}</td>
                                    <td>${item.filename}</td>
                                    <td>${new Date(item.updated_at).toLocaleString("sv-SE")}</td>
                                </tr>
                            `;
                        })
                        $('#tampil-tbody').empty().append(content);
                    }
                    var table = $('#dttable').DataTable({
                        // dom: 'Bfrtip',
                        order: [
                            [5, "desc"]
                        ],
                        bAutoWidth: false,
                        // aoColumns : [
                        // ],
                        columnDefs: [
                            { targets: [3], sortable: false },
                            { targets: [4], sortable: false },
                            // { targets: [3], sortable: false }
                            // { visible: false, targets: [7] },
                        ],
                        displayLength: 30,
                        lengthChange: true,
                        lengthMenu: [10, 30, 50, 75, 100, 250, 500, 1000, 3000, 7000, 15000, 50000, 100000],
                        buttons: ['excel', 'pdf'] // 'copy','colvis'
                    });
                    // Showing Tooltip
                    $('[data-bs-toggle="tooltip"]').tooltip('dispose');
                    $('.tooltip').remove();
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    })
                }, error: function(xhr, status, error) {
                    let pesan = xhr.responseJSON ?? xhr.responseText ?? 'Gagal mengambil data. Coba lagi.';
                    iziToast.error({
                        title: 'Pesan System!',
                        message: pesan,
                        position: 'topRight'
                    });
                    $('#show-time').text('-');
                    $("#tampil-tbody").empty().append(`<tr style='font-size:13px'><td colspan="15"><center>Gagal Memuat Data Klaim</center></td></tr>`);
                    // $('#tombol-tampilkan').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-filter');
                }
            })
        }
    </script>
@endsection
