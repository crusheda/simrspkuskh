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
                    <li class="breadcrumb-item" aria-current="page">Akses</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Setelan Akses</h2>
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
                    <h5 class="mb-3 mb-sm-0">Tabel Jabatan x Akses</h5>
                    <div class="btn-group">
                        <button class="btn btn-secondary" disabled><i class="fas fa-plus-square me-1"></i> Tambah Jabatan</button>
                        <button class="btn btn-warning" id="btn-refresh" onclick="refresh()"><i class="fas fa-sync-alt me-1"></i> Refresh</button>
                        <button class="btn btn-secondary" disabled><i class="fas fa-plus-square me-1"></i> Tambah Akses</button>
                    </div>
                </div>
            </div>
            <div class="card-body pt-3">
                <div class="table-responsive">
                    <table class="table table-hover" id="vantable">
                        <thead>
                            <tr>
                                <th style="width: 5%;" class="text-center">#ID</th>
                                <th style="width: 15%;" class="text-center">Jabatan</th>
                                <th style="width: 70%;" class="text-start">Akses</th>
                                <th style="width: 10%;" class="text-center">Aksi</th>
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
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
            url: `/api/roles/data`,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                console.log(res);
                content = ``;
                res.show.forEach(item => {
                    content += `<tr>
                                    <td class="text-center align-middle">${item.id}</td>
                                    <td class="text-center align-middle">${item.name?'<span class="badge text-bg-secondary">'+item.name+'</span>':''}</td>`;
                        if (item.permissions) {
                            content += `<td class="text-start">
                                            <ol class="list-group-numbered" style="margin-bottom:0px;padding-left:0px">`;
                            item.permissions.forEach(val => {
                                content += `    <li class="list-group-item">${val.name}</li>`;
                            })
                            content += `    </ol>
                                        </td>`;
                        } else {
                            content += `<td></td>`;
                        }
                        content += `<td class="text-center align-middle">
                                        <a href="javascript:void(0);" onclick="ubah(${item.id})" class="avtar avtar-xs btn-link-warning">
                                            <i class="ti ti-edit f-20"></i>
                                        </a>
                                        <a href="javascript:void(0);" onclick="hapus(${item.id})" class="avtar avtar-xs btn-link-danger">
                                            <i class="ti ti-trash f-20"></i>
                                        </a>
                                    </td>
                                </tr>`;
                })
                $('#tampil-tbody').empty().append(content);
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
                        placeholder: "Cari data ...",
                        perPage: "Jumlah baris per halaman",
                        noRows: "Tidak ada record data yang tersedia",
                        info: "Menampilkan {start} - {end} dari {rows} data",
                    },
                    columns: [
                        { select: 0, sort: "asc" },
                        { select: 3, sortable: false },
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
                $('#btn-refresh').prop('disabled',false).find('i').removeClass('fa-spin');
            }
        })
    }
</script>
@endsection
