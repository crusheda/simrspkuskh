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
                        <table class="table table-striped" id="dttable">
                            <thead>
                                <tr>
                                    <th><center>ID</center></th>
                                    <th>JENIS</th>
                                    <th>NOMOR</th>
                                    <th>TITLE</th>
                                    <th>FILENAME</th>
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
            $.ajax({
                url: `/api/log/berkas/table`,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    $("#tampil-tbody").empty();
                    $('#dttable').DataTable().clear().destroy();
                    if (res.show && Array.isArray(res.show)) {
                        var content = ``;
                        res.show.forEach(item => {
                            // JENIS (1:sep; 2:resume; 3:skdp; 4:invidual; 5:billing; 6:laboratorium; 7:radiologi; 8:triage; 9:operasi; 10:berkasTambahan; 11:berkasRehab; 12:konsul;)
                            if (item.jenis == 1) {

                            } else {

                            }
                            content += `
                                <tr id="data${item.id}">
                                    <td>${item.id}</td>
                                    <td>${item.jenis}</td>
                                    <td>${item.nomor}</td>
                                    <td>${item.title}</td>
                                    <td>${item.filename}</td>
                                    <td>${new Date(item.updated_at).toLocaleString("sv-SE")}</td>
                                </tr>
                            `;
                        })
                        $('#tampil-tbody').append(content);
                    }
                    var table = $('#dttable').DataTable({
                        // dom: 'Bfrtip',
                        order: [
                            [5, "desc"]
                        ],
                        bAutoWidth: false,
                        aoColumns : [
                        ],
                        columnDefs: [
                            // { targets: [0], sortable: false },
                            // { targets: [2], sortable: false },
                            // { targets: [3], sortable: false }
                            // { visible: false, targets: [7] },
                        ],
                        displayLength: 10,
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
                    $("#tampil-tbody").empty().append(`<tr style='font-size:13px'><td colspan="15"><center>Gagal Memuat Data Klaim</center></td></tr>`);
                    // $('#tombol-tampilkan').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-filter');
                }
            })
        }
    </script>
@endsection
