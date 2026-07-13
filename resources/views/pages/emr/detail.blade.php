@extends('layouts.index')

@section('content')
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Electronic</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('emr.index') }}">Medical Record</a></li>
                        <li class="breadcrumb-item" aria-current="page"><b data-bs-toggle="tooltip" data-bs-placement="bottom" title="Nomor Kunjungan Pasien">KUNJ#{{ $list['KUNJUNGAN'] }}</b></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="col-sm-12">
        <div class="card social-profile">
            <div class="card-body p-2">
                <div class="row">
                    <div class="col-md-auto">
                        <div data-back-button class="d-flex align-items-center btn btn-outline-primary">
                            <div class="flex-shrink-0 me-3">
                                <i class="ph-duotone ph-caret-double-left align-middle"></i>
                                {{-- <div class="btn btn-icon btn-link-secondary avtar">
                                </div> --}}
                            </div>
                            <div class="flex-grow-1 align-items-left">
                                <small>Kembali ke Halaman<br><a style="font-size: 15px">Sebelumnya</a></small>
                                {{-- <h6 class="mb-0">Sebelumnya</h6> --}}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-auto">
                        <div class="d-flex align-items-center btn btn-outline-warning" id="btn-icare" onclick="showICare()">
                            <div class="flex-shrink-0 me-3">
                                <i class="fas fa-clipboard-list align-middle"></i>
                                {{-- <div class="btn btn-icon btn-link-secondary avtar">
                                </div> --}}
                            </div>
                            <div class="flex-grow-1 align-items-left">
                                <small>Lihat<br><a style="font-size: 15px">i-Care</a></small>
                                {{-- <h6 class="mb-0">Sebelumnya</h6> --}}
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="row justify-content-between d-flex align-items-center p-2">
                            <div class="col-md-4 col-xl-5">
                                <h4 class="text-truncate mb-1 align-middle"><a class="text-primary">{{ $list['show']->NAMAPASIEN }}</a></h4>
                                <p class="text-truncate mb-0" style="font-size: 12px">
                                    <b>RM. <a class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Nomor Rekam Medis Pasien"><u><b>{{ str_pad($list['show']->NORM, 8, '0', STR_PAD_LEFT) }}</b></u></a></b>
                                    | <b>NOBPJS. <a class="text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Nomor Kartu BPJS Pasien">{{ $list['show']->NOBPJS }}</a></b>
                                    | <b data-bs-toggle="tooltip" data-bs-placement="bottom" title="SEP Tgl. {{ $list['show']->TGLSEP?\Carbon\Carbon::parse($list['show']->TGLSEP)->translatedFormat('d F Y'):'' }}">SEP. <a class="text-info">{{ $list['show']->NOSEP?$list['show']->NOSEP:'Tidak Ditemukan' }}</a></b>
                                </p>
                            </div>
                            <div class="col-md-8 col-xl-7 col-xxl-6">
                                <div class="row g-1 text-center">
                                    <div class="col-4 col-xxl-5 text-end">
                                        <p class="text-muted mb-0 me-3"><b>{{ $list['show']->NAMARUANGAN }}</b></p>
                                        <h6 class="text-truncate mb-0 me-3" data-bs-toggle="tooltip" data-bs-placement="bottom" title="DPJP. {{ $list['show']->NAMADOKTER }}">{{ $list['show']->NAMADOKTER }}</h6>
                                    </div>
                                    {{-- <div class="col-3 border border-top-0 border-bottom-0">
                                        <p class="text-muted mb-0">DPJP</p>
                                    </div> --}}
                                    <div class="col-6 col-xxl-5 border border-top-0 border-bottom-0 text-start">
                                        <p class="text-truncate text-muted mb-0 ms-3"><b>Masuk :</b>&nbsp;&nbsp;{{ \Carbon\Carbon::parse($list['show']->MASUK)->locale('id')->translatedFormat('d M Y H.i') . ' WIB' }}</p>
                                        <p class="text-truncate text-muted mb-0 ms-3"><b>Keluar :</b>&nbsp;&nbsp;{{ $list['show']->KELUAR?\Carbon\Carbon::parse($list['show']->KELUAR)->locale('id')->translatedFormat('d M Y H.i') . ' WIB':'-' }}</p>
                                    </div>
                                    <div class="col-2 col-xxl-2">
                                        <p class="text-muted mb-0" style="font-size: 12px">Status</p>
                                        @if ($list['show']->STATUS == 1)
                                            <span class="badge text-bg-warning">Dilayani</span>
                                        @else
                                            @if ($list['show']->STATUS == 2)
                                                <span class="badge text-bg-primary">Selesai</span>
                                            @else
                                                <span class="badge text-bg-danger">Batal</span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body py-0">
                <ul class="nav nav-tabs profile-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#identitas" role="tab"
                            aria-selected="false" tabindex="-1" aria-controls="identitas">
                            <i class="ph-duotone ph-user-switch me-2"></i> Identitas Pasien
                        </button>
                    </li>
                    @if (Str::startsWith($list['show']->IDRUANGAN, '10207'))
                        <li class="nav-item" role="presentation" hidden> <!-- TIDAK DIPAKAI LAGI -->
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#frehab" role="tab"
                                aria-selected="false" tabindex="-1" id="tab-frehab" disabled>
                                <i class="ph-duotone ph-archive-tray me-2"></i> Program Rehab Medik
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#fmrehab" role="tab"
                                aria-selected="false" tabindex="-1" id="tab-fmrehab" disabled>
                                <i class="ph-duotone ph-archive-box me-2"></i> Program Rehab Medik <span class="badge text-bg-primary ms-1">Baru</span>
                            </button>
                        </li>
                    @endif
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="" data-bs-toggle="tab" href="#konsul" role="tab"
                            aria-selected="false" tabindex="-1" id="tab-konsul" disabled>
                            <i class="ph-duotone ph-files me-2"></i> Form Konsul
                        </a>
                    </li>
                    @if (Str::startsWith($list['show']->IDRUANGAN, '10202'))
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="" data-bs-toggle="tab" href="#fmatriks" role="tab"
                                aria-selected="false" tabindex="-1" id="tab-fmatriks" disabled>
                                <i class="ph-duotone ph-film-script me-2"></i> Matriks
                            </a>
                        </li>
                    @endif
                    @hasrole('admin')
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#fpengkajian" role="tab"
                            aria-selected="false" tabindex="-1" id="tab-fpengkajian" disabled>
                            <i class="ph-duotone ph-user-list me-2"></i> Form Pengkajian
                        </button>
                    </li>
                    @endhasrole
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="" data-bs-toggle="tab" href="#fuploads" role="tab"
                            aria-selected="false" tabindex="-1" id="tab-fuploads" disabled>
                            <i class="ph-duotone ph-upload-simple me-2"></i> Upload File
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane active show" id="identitas" role="tabpanel">
                <div class="row">
                    <div class="col-lg-7 col-xxl-8">
                        <div class="card">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5>Biodata Pasien</h5>
                                <div class="dropdown">
                                    <a class="avtar avtar-xs btn-link-secondary dropdown-toggle arrow-none" href="javascript: void(0);"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ph-duotone ph-dots-three-outline-vertical"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                        <a class="dropdown-item" href="javascript: void(0);"><s>Ubah Data</s></a>
                                        {{-- <a class="dropdown-item" href="#">Delete</a> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item px-0 pt-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted">Nama Lengkap</p>
                                                <p class="mb-0">{{ $list['show']->NAMALENGKAPPASIEN }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted">Panggilan</p>
                                                <p class="mb-0">{{ $list['show']->PANGGILANPASIEN }}</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item px-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted">Nomor Induk Kependudukan</p>
                                                <p class="mb-0">{{ $list['show']->NIKPASIEN }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted">No.HP</p>
                                                <p class="mb-0">{{ $list['show']->NOHPPASIEN }}</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item px-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted">Lahir di</p>
                                                <p class="mb-0 text-uppercase">{{ $list['show']->TLPASIEN.', ' }}{{ \Carbon\Carbon::parse($list['show']->TGLLAHIRPASIEN)->translatedFormat('d F Y') }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted">Umur Sekarang</p>
                                                <p class="mb-0">{{ $list['show']->UMURPASIEN }}</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item px-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted">Jenis Kelamin</p>
                                                <p class="mb-0">{{ $list['show']->JKPASIEN }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1 text-muted">Keluarga/Orang Terdekat</p>
                                                <p class="mb-0 text-uppercase">{{ $list['show']->KELUARGAPASIEN?$list['show']->KELUARGAPASIEN:'-' }}{{ $list['show']->STKELUARGAPASIEN?' ('.$list['show']->STKELUARGAPASIEN.')':'' }}</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item px-0 pb-0">
                                        <p class="mb-1 text-muted">Alamat Lengkap</p>
                                        <p class="mb-0"> {{ $list['show']->ALAMATPASIEN }}</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 col-xxl-4">
                        <div class="card">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5>Riwayat Kunjungan</h5>
                                <button class="btn btn-sm btn-light-warning" onclick="loadRiwayatKunjunganPasien()" id="btn-refresh-riwayat-kunjungan"
                                    data-bs-toggle="tooltip" title="Refresh Riwayat Kunjungan Pasien"><i class="fas fa-sync"></i></button>
                            </div>
                            <div style="max-height: 420px; overflow-y: auto;" class="rounded-bottom">
                                <ul class="list-group list-group-flush" id="load-riwayat-kunjungan-pasien">
                                    <li class="list-group-item"><center><i class="fas fa-sync fa-spin me-1"></i> Menginisialisasi Riwayat Kunjungan</center></li>
                                    {{-- @if ($list['riwayat']->isNotEmpty())
                                        @foreach ($list['riwayat'] as $item)
                                            <li class="list-group-item">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <button class="avtar avtar-xs btn btn-light-secondary flex-shrink-0 me-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat Detail Kunjungan"
                                                            onclick="window.location.href='{{ route('emr.detail', ['KUNJUNGAN' => $item->NOKUNJUNGAN]) }}'">
                                                            <i class="ph-duotone ph-stethoscope"></i>
                                                        </button>
                                                    </div>
                                                    <div class="flex-grow-1 mx-2">
                                                        <h6 class="mb-0">{{ $item->NAMARUANGAN }}</h6>
                                                        <p class="mb-0">{{ Str::limit($item->NAMADOKTER, 25, '...') }}</p>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <p class="mb-0 text-end" style="font-size: 12px; cursor: pointer">
                                                            @if ($item->STATUSDAFTAR == 1)
                                                                Aktif
                                                            @else
                                                                @if ($item->STATUSDAFTAR == 2)
                                                                    Selesai
                                                                @else
                                                                    Non Aktif/Batal
                                                                @endif
                                                            @endif
                                                        </p>
                                                        <span class="mt-0 badge bg-light-dark" style="font-size: 10px; cursor: pointer" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ \Carbon\Carbon::parse($item->TGLDAFTAR)->translatedFormat('d F Y') }}">
                                                            {{ \Carbon\Carbon::parse($item->TGLDAFTAR)->diffForHumans() }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    @else
                                        <li class="list-group-item">
                                            <div class="d-flex align-items-center text-center">
                                                <span class="text-muted">Tidak ada kunjungan terakhir</span>
                                            </div>
                                        </li>
                                    @endif --}}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-content" hidden> <!-- TIDAK DIPAKAI LAGI -->
            <div class="tab-pane" id="frehab" role="tabpanel">
                @include('pages.emr.rehabmedik.form')
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane" id="fpengkajian" role="tabpanel">
                @include('pages.emr.form.pengkajian.tab_pengkajian')
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane" id="fmrehab" role="tabpanel">
                @include('pages.emr.rehabmedik.tab_rehab')
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane" id="konsul" role="tabpanel">
                @include('pages.emr.konsul.form')
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane" id="fuploads" role="tabpanel">
                @include('pages.emr.upload')
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane" id="fmatriks" role="tabpanel">
                @include('pages.emr.matriks')
            </div>
        </div>
    </div>

    {{-- MODAL STARTED --}}
    <div id="modalICare" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="catatanLabel" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><span class="badge text-bg-info">i-Care</span> <a id="show-id-icare" class="text-dark ms-1"></a></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="show-icare"></div>
                <div class="modal-footer">
                    <div id="btn-refresh-catatan"></div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    {{-- MODAL ENDED --}}

    <script>
        // INIT VARIABLE
        const kunjungan = @json($list["KUNJUNGAN"]);
        const rm = @json($list["show"]->NORM);
        const tgl_sep = @json($list["show"]->TGLSEP);
        const sep = @json($list["show"]->NOSEP);
        const tgl_kfr = @json(now()->format('Y-m-d H:i:s'));
        const tgl_masuk = @json($list["show"]->MASUK);
        const tgl_keluar = @json($list['show']->KELUAR);
        const tgl_sep_date = tgl_sep?tgl_sep.substring(0, 10):null;

        $(document).ready(function() {
            // aktifkan saat pertama kali load
            aktifkanTabsDariHash();

            // console.log("{{ $list['tte_pegawai'] }}");
            if ("{{ $list['tte_pegawai'] }}" != true) {
                // kalau ada tanda tangan pegawai
                Swal.fire({
                    title: `Tanda Tangan tidak ditemukan!`,
                    text: 'Silakan mengisi/menambahkan tanda tangan di menu Profil Akun Pengguna sebelum melakukan pengisian pada halaman Elektronik Medical Record.',
                    icon: `warning`,
                    showConfirmButton: false,
                    showCancelButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    timer: 3000,
                    timerProgressBar: true,
                    backdrop: `rgba(26,27,41,0.8)`,
                });
            }
            // TOMBOL KEMBALI
            $('[data-back-button]').on('click', function() {
                if (document.referrer) {
                    window.history.back();
                } else {
                    window.location.href = "{{ route('emr.index') }}"; // fallback ke klaim
                }
            });
            $('[data-bs-toggle="tooltip"]').tooltip();
            $('.nav-link').prop('disabled', false);

            loadRiwayatKunjunganPasien();
        });

        function showICare() {
            const btn = $('#btn-icare');
            $.ajax({
                url: `/api/emr/bpjs/icare/${rm}`,
                type: 'GET',
                beforeSend: function () {
                    btn.addClass('disabled')
                        .find('i')
                        .removeClass('fa-clipboard-list')
                        .addClass('fa-sync fa-spin');
                },
                success: function(res) {
                    if (res.status) {
                        $('#show-icare').empty().append(`<iframe
                                                            src="${res.url}"
                                                            width="100%"
                                                            height="800"
                                                            frameborder="0">
                                                        </iframe>`);
                        $('#show-id-icare').empty().html('NO BPJS PESERTA : <b class="text-success">'+res.no_kartu+'</b>' ?? '');
                        $('#modalICare').modal('show');
                    } else {
                        Swal.fire(
                            'Gagal',
                            res.message ?? 'Terjadi kegagalan saat menampilkan data pasien',
                            'error'
                        );
                    }

                }, error: function (xhr) {
                    Swal.fire(
                        'Gagal',
                        xhr.responseJSON?.message ?? 'Terjadi kesalahan saat memproses data',
                        'error'
                    );
                },
                complete: function () {
                    // always reset button (baik success maupun error)
                    btn.removeClass('disabled')
                        .find('i')
                        .removeClass('fa-sync fa-spin')
                        .addClass('fa-clipboard-list');
                }
            });
        }

        function loadRiwayatKunjunganPasien() { // RIWAYAT DI GRID KANAN
            const btn = $('#btn-refresh-riwayat-kunjungan');

            $.ajax({
                url: `/api/emr/riwayat/kunjungan/${rm}`,
                type: 'GET',
                beforeSend: function () {
                    btn.prop('disabled', true)
                        .find('i')
                        .addClass('fa-spin');
                    $('#load-riwayat-kunjungan-pasien').empty().append(`
                        <li class="list-group-item"><center><i class="fas fa-sync fa-spin me-1"></i> Memuat Data Kunjungan</center></li>
                    `);
                },
                success: function(res) {
                    content = ``;

                    res.show.forEach((item, index) => {

                        let statusdaftar = '';

                        if (item.STATUSDAFTAR == 1) {
                            statusdaftar = '<badge class="badge rounded-pill text-bg-success">Aktif</badge>';
                        } else if (item.STATUSDAFTAR == 2) {
                            statusdaftar = '<badge class="badge rounded-pill text-bg-primary">Selesai</badge>';
                        } else {
                            statusdaftar = '<badge class="badge rounded-pill text-bg-danger">Non Aktif/Batal</badge>';
                        }

                        content += `
                            <li class="list-group-item border-bottom">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <button
                                            class="avtar avtar-xs btn btn-light-${item.NOKUNJUNGAN == kunjungan? 'warning':'info'} flex-shrink-0 me-2"
                                            data-bs-toggle="tooltip"
                                            title="Lihat Detail Kunjungan"
                                            onclick="window.location.href='/emr/detail/${item.NOKUNJUNGAN}'">
                                            <i class="ph-duotone ph-stethoscope"></i>
                                        </button>
                                    </div>

                                    <div class="flex-grow-1 mx-2">
                                        <h6 class="mb-0">${item.NAMARUANGAN} ${item.NOKUNJUNGAN == kunjungan? `<badge class="badge text-bg-warning p-1">SAAT INI</badge>` : ``}</h6>
                                        <p class="mb-0">${item.NAMADOKTER.length > 25 ? item.NAMADOKTER.substring(0,25) + '...' : item.NAMADOKTER}</p>
                                    </div>

                                    <div class="flex-shrink-0">
                                        <p class="mb-0 text-end" style="font-size:12px" data-bs-toggle="tooltip" title="Status Kunjungan">
                                            Status : ${statusdaftar}
                                        </p>

                                        <span class="badge bg-light-dark"
                                            style="font-size:12px"
                                            data-bs-toggle="tooltip"
                                            title="${dayjs(item.TGLDAFTAR).fromNow()}">
                                            ${dayjs(item.TGLDAFTAR).format('DD MMMM YYYY, HH:mm WIB')}
                                        </span>
                                    </div>
                                </div>
                            </li>
                        `;
                    });

                    content += `</ul>`;

                    $('#load-riwayat-kunjungan-pasien').empty().append(content);
                },
                error: function (xhr) {
                    Swal.fire(
                        'Gagal',
                        xhr.responseJSON?.message ?? 'Terjadi kesalahan / Gagal memanggil Function loadRiwayatKfr',
                        'error'
                    );
                },
                complete: function () {
                    // always reset button (baik success maupun error)
                    btn.prop('disabled', false)
                        .find('i')
                        .removeClass('fa-spin');

                    // Showing Tooltip
                    $('[data-bs-toggle="tooltip"]').tooltip('dispose');
                    $('.tooltip').remove();
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    })
                }
            });
        }

        function aktifkanTabsDariHash() {
            const hash = window.location.hash; // contoh: #frehab#formlayanankfr
            if (!hash) return;

            // pecah jadi array ['frehab', 'formlayanankfr']
            const ids = hash.split('#').filter(Boolean);

            ids.forEach((id, index) => {
                const selector = '#' + id;
                const $tabBtn = $('[data-bs-target="' + selector + '"]');

                if ($tabBtn.length) {
                    const tab = new bootstrap.Tab($tabBtn[0]);
                    tab.show();

                    // jalankan validasi sesuai target
                    if (selector === '#frehab' || selector === '#formlayanankfr') {
                        validPageFormKfr();
                        // console.log('jalan kfr');
                    } else if (selector === '#formjadwalpelayanan') {
                        validPageFormJp();
                        // console.log('jalan jp');
                    } else if (selector === '#formkonsulkfr') {
                        validPageFormKs();
                        // console.log('jalan ks');
                    } else if (selector === '#fmrehab' || selector === 'frjkfr') {
                        // console.log('masuk form kfr');
                        loadFormKfr();
                        loadCpptKfr();
                        loadRiwayatKfr();
                    } else if (selector === 'pterapi') {
                        // console.log('masuk form program terapi');
                    } else if (selector === 'fpengkajian') {
                        // console.log('masuk form pengkajian');
                    } else {
                        console.log('tab lain');
                    }
                }
            });
        }
    </script>
@endsection
