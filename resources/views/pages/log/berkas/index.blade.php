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
                        <h6 class="mt-2"><i class="fas fa-table text-primary me-2"></i> Tabel Riwayat <i class="ti ti-direction-horizontal text-primary"></i> Waktu Update <a id="show-time" class="text-primary"><div class="ms-2 spinner-border text-primary spinner-border-sm" role="status"></div></a></h6>
                        <button class="btn btn-warning rounded" id="btn-refresh" onclick="refresh()"><i class="fas fa-sync fa-spin me-1"></i> Refresh</button>
                    </div>
                </div>
                <div class="card-body p-1 pb-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered" id="dttable">
                            <thead>
                                <tr>
                                    <th>AKSI</th>
                                    <th>JENIS</th>
                                    <th>NOMOR KUNJUNGAN</th>
                                    <th>NAMA FILE (UKURAN FILE)</th>
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
    <div id="modalLihat" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="showSKDPLabel">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="show-tx-file"><span class="badge text-bg-secondary">SURAT RENCANA KONTROL</span> | IDKUNJUNGAN : <a id="show-id-skdp" class="text-primary"></a></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div id="cetak"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-simple modal-add-new-address modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        Form Hapus Berkas Klaim
                    </h4>
                </div>
                <div class="modal-body">
                    <input type="text" id="id_hapus" hidden>
                    <p style="text-align: justify;">Anda akan menonaktifkan dan melakukan penghapusan Berkas Klaim tersebut, lakukanlah dengan hati-hati. Ceklis dibawah untuk melanjutkan penghapusan.</p>
                    <label class="switch">
                        <input type="checkbox" class="switch-input" id="setujuhapus">
                        <span class="switch-toggle-slider">
                        <span class="switch-on"></span>
                        <span class="switch-off"></span>
                        </span>
                        <span class="switch-label">Anda siap menerima Risiko</span>
                    </label>
                </div>
                <div class="col-12 text-center mb-4">
                    <button type="submit" class="btn btn-danger me-sm-3 me-1" onclick="prosesHapus()"><i class="fa fa-trash me-1" style="font-size:13px"></i> Hapus</button>
                    <button type="reset" class="btn btn-light-secondary" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times me-1" style="font-size:13px"></i> Batal</button>
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
            if ($.fn.DataTable.isDataTable('#dttable')) {
                $('#dttable').DataTable().clear().destroy();
            }
            $('#tampil-tbody').empty().append(`<tr style='font-size:13px'><td colspan="15"><center><div class="spinner-border spinner-border-sm" role="status"></div></center></td></tr>`);
            $('#btn-refresh').find('i').addClass('fa-spin');
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
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="dropdown">
                                                <a href="javascript:;" class="btn btn-light-secondary btn-sm dropdown-toggle hide-arrow rounded" data-bs-toggle="dropdown">${item.id}</a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="javascript:;" onclick="lihat(${item.id})" class="dropdown-item text-info"><i class='ti ti-file-invoice me-1'></i> Lihat</a>
                                                    <a href="javascript:;" onclick="hapus(${item.id})" class="dropdown-item text-danger"><i class='ti ti-file-shredder me-1'></i> Hapus</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        ${jenisMap[item.jenis] || '<a class="text-danger">Tidak Diketahui</a>'}
                                        ${item.sub_jenis?'<span class="badge text-bg-secondary me-1">#'+item.sub_jenis+'</span>':''}
                                        ${item.kode?'<span class="badge text-bg-warning me-1">SUB#'+item.kode+'</span>':''}
                                        ${item.ref?'<span class="badge text-bg-danger me-1">REF#'+item.ref+'</span>':''}
                                    </td>
                                    <td>${item.nomor}</td>
                                    <td>${item.title}${item.size?' (<b class="text-info">'+item.size+'</b>)':''}</td>
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
                    $('#btn-refresh').find('i').removeClass('fa-spin');
                }, error: function(xhr, status, error) {
                    let pesan = xhr.responseJSON ?? xhr.responseText ?? 'Gagal mengambil data. Coba lagi.';
                    iziToast.error({
                        title: 'Pesan System!',
                        message: pesan,
                        position: 'topRight'
                    });
                    $('#show-time').text('-');
                    $('#btn-refresh').find('i').removeClass('fa-spin');
                    $("#tampil-tbody").empty().append(`<tr style='font-size:13px'><td colspan="15"><center>Gagal Memuat Data Klaim</center></td></tr>`);
                    // $('#tombol-tampilkan').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-filter');
                }
            })
        }

        function lihat(id) {
            $('#show-tx-file').text(id);
            fetch("/api/log/berkas/"+id+"/show")
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
                $('#cetak').empty().html(`<iframe src="${fileURL}" width="100%" height="500px" frameborder="0"></iframe>`);
                $('#modalLihat').modal('show');
            })
            .catch(error => {
                iziToast.error({
                    title: 'Maaf!',
                    message: 'Berkas Klaim tidak ditemukan atau belum digenerate.',
                    position: 'topRight'
                });
                console.error(error);
            });
        }

        function hapus(id) {
            $("#id_hapus").val(id);
            var inputs = document.getElementById('setujuhapus');
            inputs.checked = false;
            $('#modalHapus').modal('show');
        }

        function prosesHapus() {
            // SWITCH BTN HAPUS
            var checkboxHapus = $('#setujuhapus').is(":checked");
            if (checkboxHapus == false) {
                iziToast.error({
                    title: 'Pesan Galat!',
                    message: 'Mohon menyetujui untuk dilakukan penghapusan berkas tersebut',
                    position: 'topRight'
                });
            } else {
                // PROSES HAPUS
                var id = $("#id_hapus").val();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/api/log/berkas/"+id+"/delete",
                    type: 'DELETE',
                    success: function(res) {
                        iziToast.success({
                            title: 'Pesan Berhasil!',
                            message: 'Berkas Klaim telah berhasil dihapus pada '+res,
                            position: 'topRight'
                        });
                        $('#modalHapus').modal('hide');
                        refresh();
                    },
                    error: function(res) {
                        iziToast.error({
                            title: 'API Error!',
                            message: 'Berkas Klaim gagal dihapus',
                            position: 'topRight'
                        });
                    }
                });
            }
        }
    </script>
@endsection
