@extends('layouts.index')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Setting</a></li>
                    <li class="breadcrumb-item" aria-current="page">Jabatan</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Jabatan Pengguna</h2>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
    <div class="col-12">
        <div class="card table-card">
            <div class="card-header">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <h5 class="mb-3 mb-sm-0">Tabel</h5>
                    <div>
                        <button class="btn btn-warning me-2" id="btn-refresh" onclick="refresh()"><i class="fas fa-sync-alt me-1"></i> Refresh</button>
                        <button class="btn btn-primary" disabled><i class="fas fa-plus-square me-1"></i> Tambah Pengguna</button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-3">
                <div class="table-responsive">
                    <table class="table table-hover" id="vantable">
                        <thead>
                            <tr>
                                <th style="width: 5%;" class="text-center">#ID</th>
                                <th style="width: 45%;">Nama Lengkap</th>
                                <th style="width: 15%;" class="text-end">Username</th>
                                <th style="width: 15%;" class="text-end">Jabatan</th>
                                <th style="width: 10%;">Status</th>
                                <th style="width: 10%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tampil-tbody">
                            <tr>
                                <td colspan="3">
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
    });

    // function-function
    function refresh() {
        $('#btn-refresh').prop('disabled',true).find('i').addClass('fa-spin');
        if (window.dataTable) {
            window.dataTable.destroy();
        }
        $("#tampil-tbody").empty().append(`<tr style='font-size:13px'><td colspan="15"><center><div class="spinner-border spinner-border-sm" role="status"></div></center></td></tr>`);
        $.ajax({
            url: `/api/roles/user/table`,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.show && Array.isArray(res.show)) {
                    content = ``;
                    res.show.forEach(item => {
                        if (item.STATUS == 1) {
                            color = 'text-success';
                            stt = 'Aktif';
                        } else {
                            color = 'text-secondary';
                            stt = 'Non Aktif';
                        }
                        content += `<tr>
                                        <td class="text-center">${item.ID_PENGGUNA}</td>
                                        <td><b class="text-primary">${item.NAMALENGKAP?item.NAMALENGKAP:item.APNAMA}</b> <span class="text-muted text-sm d-block"><b>NIP. </b>${item.NIP?item.NIP:item.APNIP}</span></td>
                                        <td class="text-end">${item.USERNAME}</td>
                                        <td class="text-end">${item.ROLE_NAMES?item.ROLE_NAMES:''}</td>
                                        <td class="${color}"><i class="fas fa-circle f-10 m-r-10"></i> ${stt}</td>
                                        <td>
                                            <a href="#" onclick="ubah(${item.ID_PENGGUNA})" class="avtar avtar-xs btn-link-secondary">
                                                <i class="ti ti-edit f-20"></i>
                                            </a>
                                            <a href="#" onclick="hapus(${item.ID_PENGGUNA})" class="avtar avtar-xs btn-link-secondary">
                                                <i class="ti ti-trash f-20"></i>
                                            </a>
                                        </td>
                                    </tr>`;
                    })
                    $('#tampil-tbody').empty().append(content);
                }
                // VANILLA TABLE
                window.dataTable = new simpleDatatables.DataTable("#vantable", {
                    sortable: true,
                    searchable: true,
                    perPage: 10,
                    perPageSelect: [10, 20, 50, 100, 300, 500],
                    fixedColumns: true,
                    firstLast: true,
                    layout: "both",
                    labels: {
                        placeholder: "Cari data Pengguna...",
                        perPage: "Jumlah baris per halaman",
                        noRows: "Tidak ada record data yang tersedia",
                        info: "Menampilkan {start} - {end} dari {rows} data",
                    },
                    columns: [
                        { select: 0, sort: "asc" },   // Kolom ke-0, di-sort ascending
                        // { select: 1, sort: "desc" },  // Kolom ke-1, descending
                        { select: 5, sortable: false }, // Kolom ke-2 tidak bisa di-sort
                        // { select: 1, sort: 'desc' }, // Kolom ke-2 tidak bisa di-sort
                        // { select: 2, sortable: false } // Kolom ke-2 tidak bisa di-sort
                    ]
                });
                // Showing Tooltip
                $('[data-bs-toggle="tooltip"]').tooltip({
                    trigger : 'hover'
                })
                $('#btn-refresh').prop('disabled',false).find('i').removeClass('fa-spin');
            }, error: function(xhr, status, error) {
                // Gagal: tangani error di sini
                console.error('Terjadi kesalahan:', error);
                // Bisa juga tampilkan alert
                alert('Gagal mengambil data. Coba lagi.');
                $('#btn-refresh').prop('disabled',false).find('i').removeClass('fa-spin');
            }
        })
    }
</script>
@endsection
