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
                    <li class="breadcrumb-item" aria-current="page">Smart Klaim</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Digital Smart Klaim</h2>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row gy-4">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header align-items-center">
                <h5 class="mb-0"><i class="ti ti-table text-primary me-1"></i> Tabel Klaim <span class="ms-2 f-14 px-2 badge bg-light-secondary rounded-pill">1</span></h5>
            </div>
            <div class="card-body p-0 table-body">
                <div class="table-responsive">
                    <table class="table mb-0 table-hover table-striped" id="vantable">
                        <thead>
                            <tr>
                                <th style="width: 60%;">KUNJUNGAN</th>
                                <th style="width: 30%;" class="text-end">TGL KUNJUNGAN</th>
                                <th style="width: 10%;" class="text-end">STATUS</th>
                            </tr>
                        </thead>
                        <tbody id="tampil-tbody">
                            <tr style='font-size:13px'>
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
        {{-- <div class="text-end">
            <a href="ecom_product.html" class="btn btn-link-secondary d-inline-flex align-items-center"><i class="ti ti-chevron-left me-2"></i> Back to Shopping</a>
        </div> --}}
    </div>
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h5><i class="ti ti-list-search text-primary me-1"></i> Pencarian No.SEP</h5>
                <div class="input-group my-3 mb-0">
                    <input type="text" class="form-control" placeholder="Masukkan No.SEP Pasien" />
                    <button class="btn btn-outline-secondary" type="button">Cari</button>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="mb-2"><i class="ti ti-filter text-primary me-1"></i> Filter</h5>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label class="form-label">Jenis Perawatan</label>
                            <select id="filter_rawat" class="form-control">
                                <option value="5">Semua Perawatan</option>
                                <option value="1" selected>Rawat Jalan</option>
                                <option value="2">Rawat Darurat</option>
                                <option value="3">Rawat Inap</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Bulan Kunjungan</label>
                        <input type="month" class="form-control" value="{{ $list['yearMonth'] }}" placeholder="Pilih Bulan" id="filter_bulan" />
                    </div>
                    <div class="col-md-12">
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
        </div>
        <div class="d-grid mb-3">
            <button class="btn btn-primary" onclick="filter()" id="tombol-tampilkan"><i class="fa fa-filter me-1"></i> Tampilkan</button>
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
{{-- MODAL ENDED --}}
<script>
    $(document).ready(function() {
        // TABLE
        // const dataTable = new simpleDatatables.DataTable('#vantable', { sortable: false });
        // SELECT CHOICES
        elm = $('#filter_dpjp')[0];
        choices = new Choices(elm);

        filter();
    });

    // function-function
    function filter() {
        $('#tombol-tampilkan').prop('disabled',true).find('i').removeClass('fa-filter').addClass('fa-sync fa-spin');
        if (window.dataTable) {
            window.dataTable.destroy();
        }
        $("#tampil-tbody").empty().append(`<tr style='font-size:13px'><td colspan="15"><center><div class="spinner-border spinner-border-sm" role="status"></div></center></td></tr>`);
        // Initialize
        var pel = $("#filter_rawat").val();
        var bln = $("#filter_bulan").val() ? $("#filter_bulan").val() : '0';
        var dpjp = $("#filter_dpjp").val() ? $("#filter_dpjp").val() : '0'; // JIKA DPJP KOSONG = 0
        // console.log(bln);
        // Process
        $.ajax({
            url: `/api/klaim/table/${pel}/${bln}/${dpjp}`,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                $("#tampil-tbody").empty();
                if (res.show && Array.isArray(res.show)) {
                    res.show.forEach(item => {
                        if (item.NOSEP) {
                            valSEP = item.NOSEP.substring(8, 12); // 0624
                            parts = item.TGLSEP.split("-"); // hasil: ['08', '06', '2024'] || e.g. 2024-01-12 00:00:00
                            valTGLSEP = parts[1]+parts[0].slice(-2); // '0624'
                            // console.log(valSEP);
                            // console.log(valTGLSEP);
                            if (valSEP == valTGLSEP) {
                                SEP = '<b class="text-purple-700">'+item.NOSEP+'</b>';
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
                        content = ``;
                        content += `<tr class="clickable" data-href="klaim/${item.NOMOR}">
                                        <td>
                                            <h5 class="mb-1"><a href="javascript: void(0);"><b data-bs-toggle="tooltip" data-bs-placement="bottom" title="Nomor Surat Elegibilitas Peserta">${SEP}</b></a></h5>
                                            <p class="text-sm text-muted mb-0">RM.${item.NORM} - <b class="text-primary">${item.NAMAPASIEN}</b><br>${item.NAMARUANGAN} - ${item.NAMADOKTER}</p>
                                        </td>
                                        <td class="text-end align-middle">
                                            <a href="javascript: void(0);" class="text-muted">${item.MASUK}</a>
                                        </td>
                                        <td class="text-end">
                                            <a href="javascript: void(0);" class="avtar avtar-s btn-link-success" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Berkas Telah Diverifikasi">
                                                <i class="ti ti-square-check f-30"></i>
                                            </a>
                                        </td>
                                    </tr>`;
                        $('#tampil-tbody').append(content);
                    })
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
                        placeholder: "Cari data SEP...",
                        perPage: "Jumlah baris per halaman",
                        noRows: "Tidak ada data Kunjungan Pasien yang tersedia",
                        info: "Menampilkan {start} - {end} dari {rows} data",
                    },
                    columns: [
                        // { select: 0, sort: "asc" },   // Kolom ke-0, di-sort ascending
                        // { select: 1, sort: "desc" },  // Kolom ke-1, descending
                        { select: 0, sortable: false }, // Kolom ke-2 tidak bisa di-sort
                        { select: 1, sort: 'desc' }, // Kolom ke-2 tidak bisa di-sort
                        { select: 2, sortable: false } // Kolom ke-2 tidak bisa di-sort
                    ]
                });
                    // Showing Tooltip
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    })
                // TOMBOL FILTER TAMPILKAN
                $('#tombol-tampilkan').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-filter');
                $(document).on('click', '.clickable', function() {
                    var url = $(this).data('href');
                    window.location.href = url;
                });
            }, error: function(xhr, status, error) {
                // Gagal: tangani error di sini
                console.error('Terjadi kesalahan:', error);
                // Bisa juga tampilkan alert
                alert('Gagal mengambil data. Coba lagi.');
                $('#tombol-tampilkan').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-filter');
            }
        })
    }

</script>
@endsection
