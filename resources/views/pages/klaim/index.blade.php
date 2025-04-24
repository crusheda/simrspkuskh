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
                    <table class="table mb-0" id="vantable">
                        <thead>
                            <tr>
                                <th>KUNJUNGAN</th>
                                <th class="text-end">STATUS BERKAS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <a class="avtar avtar-s btn-light-secondary dropdown-toggle arrow-none" href="#"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ti ti-chevron-down f-18"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right" style="">
                                                <a class="dropdown-item" href="#">Berkas Klaim</a>
                                                <a class="dropdown-item" href="#">Catatan</a>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="mb-1">0151R0130424V000024</h5>
                                            <p class="text-sm text-muted mb-0">RM.113736 - <b class="text-primary">RUSWATI, NY</b></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <a href="javascript: void(0);" class="avtar avtar-s btn-link-success">
                                        <i class="ti ti-square-check f-30"></i>
                                    </a>
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
                <h5><i class="ti ti-filter text-primary me-1"></i> Filter</h5>
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
                        <input type="month" class="form-control" value="" placeholder="Pilih Bulan" id="filter_bulan" />
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
            <button class="btn btn-primary">Tampilkan</button>
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
        const dataTable = new simpleDatatables.DataTable('#vantable', { sortable: false });
        // SELECT CHOICES
        elm = $('#filter_dpjp')[0];
        choices = new Choices(elm);
    });
</script>
@endsection
