@extends('layouts.index3')

@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Digital</a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Smart Klaim Farmasi</a></li>
                        <li class="breadcrumb-item" aria-current="page">Berkas</li>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">Klaim Berkas Farmasi - <b class="text-primary">{{ $list['show']->NAMAPASIEN }}</b></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body p-0">
                    <div data-back-button class="d-flex align-items-center btn btn-link-secondary">
                        <div class="flex-shrink-0 me-3">
                            <i class="ph-duotone ph-caret-double-left align-middle"></i>
                            {{-- <div class="btn btn-icon btn-link-secondary avtar">
                            </div> --}}
                        </div>
                        <div class="flex-grow-1 align-items-left">
                            <small>Kembali ke Halaman</small>
                            <h6 class="mb-0">Sebelumnya</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card list-group mb-0">
                <div class="card-header p-3">
                    <h6 class="mb-0"><i class="ti ti-sort-descending-2 me-1"></i> Pilihan Berkas</h6>
                </div>
                <div class="list-group-item d-flex align-items-center p-3 border-top-0 border-start-0 border-end-0">
                    <div class="input-group">
                        <input class="form-check-input" type="checkbox" id="ck_sep" style="width: 2em;height: 2.2em;margin-top:0px"
                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ceklist Berkas Klaim" onchange="if(this.checked){ sep('{{ $list['KUNJUNGAN'] }}'); }
                        else {$('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);}" disabled>
                    </div>
                    <a class="text-nowrap mt-1">SEP</a>
                </div>
                <div class="list-group-item d-flex align-items-center p-3 border-top-0 border-start-0 border-end-0">
                    <div class="input-group">
                        <input class="form-check-input" type="checkbox" id="ck_resume" style="width: 2em;height: 2.2em;margin-top:0px"
                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ceklist Berkas Klaim" onchange="if(this.checked){ resume('{{ $list['KUNJUNGAN'] }}'); }
                        else {$('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);}" disabled>
                    </div>
                    <a class="text-nowrap mt-1">Resume Medis</a>
                </div>
                <div class="list-group-item d-flex align-items-center p-3 border-top-0 border-start-0 border-end-0 border bottom-0">
                    <div class="input-group">
                        <input class="form-check-input" type="checkbox" id="ck_laboratorium" style="width: 2em;height: 2.2em;margin-top:0px"
                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ceklist Berkas Klaim" onchange="if(this.checked){ laboratorium('{{ $list['KUNJUNGAN'] }}'); }
                        else {$('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);}" disabled>
                    </div>
                    <a class="text-nowrap mt-1">Laboratorium</a>
                </div>
                <div class="list-group-item d-flex align-items-center p-3 border-top-0 border-start-0 border-end-0 border bottom-0">
                    <div class="input-group">
                        <input class="form-check-input" type="checkbox" id="ck_kwitansiresep" style="width: 2em;height: 2.2em;margin-top:0px"
                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ceklist Berkas Klaim" onchange="if(this.checked){ kwitansiresep('{{ $list['KUNJUNGAN'] }}'); }
                        else {$('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);}" disabled>
                    </div>
                    <a class="text-nowrap mt-1">Kwitansi Resep</a>
                </div>
                <div id="dokumen_tambahan"></div>
                <div id="footer_submit"></div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card social-profile">
                <div class="card-body p-2">
                    <div class="row justify-content-between d-flex align-items-center p-2">
                        <div class="col-md-4 col-xl-5 col-xxl-6 text-start">
                            <h4 class="text-truncate mb-1 align-middle"><a class="text-primary">{{ $list['show']->NAMAPASIEN }}</a></h4>
                            <p class="text-truncate mb-1" style="font-size: 12px">
                                <b>RM. <a class="text-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Nomor Rekam Medis Pasien"><u><b>{{ str_pad($list['show']->NORM, 8, '0', STR_PAD_LEFT) }}</b></u></a></b>
                                | <b>NOBPJS. <a class="text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Nomor Kartu BPJS Pasien">{{ $list['show']->NOBPJS }}</a></b>
                                | <b data-bs-toggle="tooltip" data-bs-placement="bottom" title="SEP Tgl. {{ $list['show']->TGLSEP?\Carbon\Carbon::parse($list['show']->TGLSEP)->translatedFormat('d F Y'):'' }}">SEP. <a class="text-info">{{ $list['show']->NOSEP?$list['show']->NOSEP:'Tidak Ditemukan' }}</a></b>
                            </p>
                            <p class="text-truncate mb-0" style="font-size: 12px"><mark data-bs-toggle="tooltip" data-bs-placement="bottom"
                                title="DPJP. {{ $list['show']->NAMADOKTER }}"><b>{{ $list['show']->NAMARUANGAN }} - {{ $list['show']->NAMADOKTER }}</b></mark></p>
                        </div>
                        <div class="col-md-8 col-xl-7 col-xxl-6 text-end">
                            <p class="text-truncate text-muted mb-2">
                                <b>Masuk :</b>&nbsp;&nbsp;
                                {{ \Carbon\Carbon::parse($list['show']->MASUK)->format('d M Y H.i') . ' WIB' }}
                            </p>
                            <p class="text-truncate text-muted mb-0">
                                <b>Keluar :</b>&nbsp;&nbsp;
                                {{ $list['show']->KELUAR ? \Carbon\Carbon::parse($list['show']->KELUAR)->format('d M Y H.i') . ' WIB' : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div id="alert_verif"></div>
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between py-3">
                    <div>
                        <h5 class="mb-2">Preview Berkas Klaim</h5>
                        <small class="m-0">Pilih daftar berkas di bilah menu kiri</small>
                    </div>
                    <div id="btn-refresh-klaim"></div>
                    {{-- <div class="dropdown">
                        <a class="avtar avtar-xs btn-link-secondary dropdown-toggle arrow-none" href="#" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false"><i class="material-icons-two-tone f-18">more_vert</i></a>
                        <div class="dropdown-menu dropdown-menu-end" style="">
                            <a class="dropdown-item" href="#">Build</a>
                            <a class="dropdown-item" href="#">Compile</a>
                        </div>
                    </div> --}}
                </div>
                <div class="card-body align-middle" id="preview">
                    @if ($list['klaim'])
                        <iframe src="/api/klaim/farmasi/{{ $list['klaim']->tahun }}/{{ $list['klaim']->bulan }}/{{ $list['klaim']->nomor }}/pdf" width="100%" height="500px" frameborder="0"></iframe>
                    @else
                        Area ini akan menampilkan Preview Berkas Klaim yang dipilih
                    @endif
                </div>
            </div>
        </div>
    </div>
<div class="modal animate__animated animate__rubberBand fade" id="hapus" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-simple modal-add-new-address modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    Form Hapus Klaim
                </h4>
            </div>
            <div class="modal-body">
                <input type="text" id="id_hapus" hidden>
                <p style="text-align: justify;">Perhatian, saat ini Anda akan melakukan penghapusan Berkas Klaim (ID#<a class="text-danger" id="tx_hapus"></a>), lakukanlah dengan hati-hati. Ceklis dibawah untuk melanjutkan penghapusan.</p>
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
                <button type="submit" id="btn-hapus-klaim" class="btn btn-danger me-sm-3 me-1" onclick="prosesHapus()"><i class="fa fa-trash me-1" style="font-size:13px"></i> Hapus</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times me-1" style="font-size:13px"></i> Batal</button>
            </div>
        </div>
    </div>
</div>
<div class="modal animate__animated animate__rubberBand fade" id="upload" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-simple modal-add-new-address modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    Form Upload Berkas Tambahan
                </h4>
            </div>
            <div class="modal-body">
                <input type="text" id="id_upload" hidden>
                <div class="alert alert-secondary alert-dismissible fade show" role="alert">
                    <small>
                        <i class="ti ti-arrow-narrow-right text-primary me-1"></i> Batas maksimal upload berkas adalah <b><u>1 mb</u></b> dan berformat <b>PDF</b> atau <b>Image (JPG/PNG)</b> <br>
                        <i class="ti ti-arrow-narrow-right text-primary me-1"></i> Untuk Mengupload 3 berkas maka silakan untuk melakukan upload pada masing-masing berkas satu persatu <br>
                        <i class="ti ti-arrow-narrow-right text-primary me-1"></i> Pada kolom isian Nama / Jenis Berkas, isikan Penamaan berkas contoh : Hasil EKG, dll <br>
                        <i class="ti ti-arrow-narrow-right text-primary me-1"></i> Isian-isian di bawah bersifat Wajib diisi atau tidak boleh dikosongi

                    </small>
                </div>
                <div class="form-group mb-3">
                    <input type="text" class="form-control" id="nama_tambahan" placeholder="Tuliskan Nama / Jenis Berkas * (e.g. Hasil EKG / dll)">
                </div>
                <div class="form-group">
                    <input type="file" class="form-control" id="filex">
                </div>
            </div>
            <div class="col-12 text-center mb-4">
                <button type="submit" id="btn-upload-proses" class="btn btn-primary me-sm-3 me-1" onclick="prosesUpload()"><i class="fa fa-upload me-1" style="font-size:13px"></i> Upload</button>
                <button type="reset" class="btn btn-link-secondary" data-bs-dismiss="modal" aria-label="Close">Batal <i class="fa fa-times ms-1" style="font-size:13px"></i></button>
            </div>
        </div>
    </div>
</div>
<div class="modal animate__animated animate__rubberBand fade" id="hapusTambahan" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-simple modal-add-new-address modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    Form Hapus Berkas Tambahan
                </h4>
            </div>
            <div class="modal-body">
                <input type="text" id="id_hapus_tambahan" hidden>
                <p style="text-align: justify;">Perhatian, saat ini Anda akan melakukan penghapusan Berkas Tambahan Klaim (ID#<a class="text-danger" id="tx_hapus_tambahan"></a>), lakukanlah dengan hati-hati. Ceklis dibawah untuk melanjutkan penghapusan.</p>
                <label class="switch">
                    <input type="checkbox" class="switch-input" id="setujuhapustambahan">
                    <span class="switch-toggle-slider">
                    <span class="switch-on"></span>
                    <span class="switch-off"></span>
                    </span>
                    <span class="switch-label">Anda siap menerima Risiko</span>
                </label>
            </div>
            <div class="col-12 text-center mb-4">
                <button type="submit" id="btn-hapus-tambahan" class="btn btn-danger me-sm-3 me-1" onclick="prosesHapusTambahan()"><i class="fa fa-trash me-1" style="font-size:13px"></i> Hapus</button>
                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times me-1" style="font-size:13px"></i> Batal</button>
            </div>
        </div>
    </div>
</div>
<script>
    var editorCatatanTambah; // global variable
    var editorCatatanEdit; // global variable
    $(document).ready(function() {
        $('[data-back-button]').on('click', function() {
            if (document.referrer) {
                window.history.back();
            } else {
                window.location.href = "{{ route('klaim.index') }}"; // fallback ke klaim
            }
        });
        verify();
    });
    function verify() {
        var kunjungan = "{{ $list['KUNJUNGAN'] }}";
        $('#daftar_catatan').empty()
            .append(`<div class="d-flex justify-content-center">
                        <div class="spinner-grow spinner-grow-sm me-2" role="status">
                            <span class="sr-only">Loading...</span>
                        </div> <a class="align-middle">Memproses Data Catatan..</a>
                    </div>`);
        $.ajax({
            url: `/api/klaim/farmasi/${kunjungan}/data`,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                console.log(res.file);
                // REFRESH HALAMAN
                if (!res.show) {
                    $('#alert_verif').empty();
                    $('#ck_sep').prop('checked', false).prop('disabled', false);
                    $('#ck_resume').prop('checked', false).prop('disabled', false);
                    $('#ck_laboratorium').prop('checked', false).prop('disabled', false);
                    $('#ck_kwitansiresep').prop('checked', false).prop('disabled', false);

                    submit = ``;
                    submit += `<div class="card-footer p-3">
                                    <div class="btn-group w-100">`;
                    submit += `         <button class="btn btn-light-warning btn-sm" onclick="clearCheckbox()"><i class="ti ti-eraser me-1"></i> Clear</button>
                                        <button class="btn btn-primary btn-sm" onclick="prosesSubmit('${kunjungan}')" id="btn-submit">Submit <i class="fas fa-paper-plane ms-1"></i></button>`;
                    submit += `     </div>
                                </div>`;
                    $('#footer_submit').empty().append(submit);
                } else {
                    let koleksi = JSON.parse(res.show.koleksi || '[]');
                    if (koleksi.includes(1)) { $('#ck_sep').prop('checked', true).prop('disabled', false); } else { $('#ck_sep').prop('checked', false).prop('disabled',false); }
                    if (koleksi.includes(2)) { $('#ck_resume').prop('checked', true).prop('disabled', false); } else { $('#ck_resume').prop('checked', false).prop('disabled',false); }
                    if (koleksi.includes(6)) { $('#ck_laboratorium').prop('checked', true).prop('disabled', false); } else { $('#ck_laboratorium').prop('checked', false).prop('disabled',false); }
                    if (koleksi.includes(12)) { $('#ck_kwitansiresep').prop('checked', true).prop('disabled', false); } else { $('#ck_kwitansiresep').prop('checked', false).prop('disabled',false); }

                    $('#btn-refresh-klaim').empty().append(`<button class="btn btn-light-primary" onclick="prosesSubmit('${kunjungan}')">Refresh Preview Klaim</button>`);

                    // DOKUMEN TAMBAHAN
                    $('#dokumen_tambahan').empty();
                    tambahan = ``;
                    // console.log(res.file);
                    res.file.forEach(item => {
                        if (item.jenis == 10) {
                            tambahan += `<div class="list-group-item d-flex align-items-center p-3 border-top-0 border-start-0 border-end-0"  style="justify-content: space-between;">
                                            <div class="dropdown">
                                                <button class="btn btn-link-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false"
                                                    style="width: 2em; height: 2.2em; display: flex; justify-content: center; align-items: center; padding: 0;"><i class="fas fa-angle-down" style="font-size:13px"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    ${res.show.verif == 0?
                                                        `<li><button class="dropdown-item text-primary" onclick="showUpload(${item.id})"><i class="fas fa-eye" style="font-size:13px"></i> Lihat</button></li>
                                                        <li><button class="dropdown-item text-danger" id="btn-hapus-tambahan${item.id}" onclick="hapusTambahan(${item.id})"><i class="fas fa-trash" style="font-size:13px"></i> Hapus</button></li>`
                                                    :
                                                        `<li><button class="dropdown-item text-primary" onclick="showUpload(${item.id})"><i class="fas fa-eye" style="font-size:13px"></i> Lihat</button></li>
                                                        <li><button class="dropdown-item text-secondary" disabled><i class="fas fa-trash" style="font-size:13px"></i> Hapus</button></li>`
                                                    }

                                                </ul>
                                            </div>
                                            <a class="text-nowrap mt-1" style="margin-left:auto;">${item.nama_tambahan}</a>
                                        </div>`;
                            console.log('muncul');
                        }
                    });
                    $('#dokumen_tambahan').append(tambahan);

                    submit = ``;
                    if (res.show.verif == 0) {
                        $('#alert_verif').empty().append(`<div class="alert alert-warning d-block text-center text-uppercase"><i class="feather icon-x-circle me-2"></i>Berkas Klaim Belum Diverifikasi</div>`);
                        submit += `<div class="card-footer p-3">`;
                        submit += `     <button class="btn btn-danger w-100 btn-sm mb-3" onclick="hapus('${kunjungan}')"><i class="fas fa-trash me-1"></i> Hapus Berkas Klaim</button>
                                        <button class="btn btn-warning btn-sm w-100 mb-3" onclick="upload('${kunjungan}')" id="btn-upload"><i class="fas fa-upload me-1"></i> Upload Berkas Tambahan</button>
                                        <button class="btn btn-success btn-sm w-100 mb-3" onclick="prosesSubmit('${kunjungan}')" id="btn-submit"><i class="fas fa-paper-plane me-1"></i> Submit Ulang Klaim</button>`;
                        submit += `     <button class="btn btn-secondary btn-sm w-100" onclick="verifikasi('${kunjungan}')" id="btn-verif"><i class="fas fa-check-square me-1"></i> Verifikasi Berkas</button>`;
                        submit += `</div>`;
                    } else {
                        $('#alert_verif').empty().append(`<div class="alert alert-success d-block text-center text-uppercase"><i class="feather icon-check-circle me-2"></i>Berkas Klaim Telah Diverifikasi</div>`);
                        submit += `<div class="card-footer p-3">`;
                        submit += `     <button class="btn btn-outline-secondary btn-sm w-100" onclick="batalVerifikasi('${kunjungan}')" id="btn-batal-verif"><i class="fas fa-times-circle me-1"></i> Batal Verifikasi</button>`;
                        submit += `</div>`;
                    }
                    $('#footer_submit').empty().append(submit);
                }
            }
        })
    }
    function sep(kunjungan) {
        $('#ck_sep').prop('disabled',true);
        $('#preview').empty().append(`
            <div class="spinner-grow align-middle me-2" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            Area ini akan menampilkan Preview Berkas Klaim yang dipilih
        `);

        // AJAX FETCH
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
            $('#preview').empty().html(`<iframe src="${fileURL}" width="100%" height="500px" frameborder="0"></iframe>`);
            $('#ck_sep').prop('disabled',false);
        })
        .catch(error => {
            iziToast.error({
                title: 'Maaf!',
                message: 'Data SEP tidak ditemukan atau belum dibuatkan oleh Simgos.',
                position: 'topRight'
            });
            console.error(error);
            $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);
            $('#ck_sep').prop('checked', false).prop('disabled',false);
        });
    }

    function resume(kunjungan) {
        $('#ck_resume').prop('disabled',true);
        $('#preview').empty().append(`
            <div class="spinner-grow align-middle me-2" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            Area ini akan menampilkan Preview Berkas Klaim yang dipilih
        `);

        // AJAX FETCH
        fetch("/api/pasien/"+kunjungan+"/resumeRj")
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
            $('#preview').empty().html(`<iframe src="${fileURL}" width="100%" height="500px" frameborder="0"></iframe>`);
            $('#ck_resume').prop('disabled',false);
        })
        .catch(error => {
            iziToast.error({
                title: 'Maaf!',
                message: 'Data Resume tidak ditemukan atau belum dibuatkan oleh Simgos.',
                position: 'topRight'
            });
            console.error(error);
            $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);
            $('#ck_resume').prop('checked', false).prop('disabled',false);
        });
    }

    function laboratorium(kunjungan) {
        $('#ck_laboratorium').prop('disabled',true);
        $('#preview').empty().append(`
            <div class="spinner-grow align-middle me-2" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            Area ini akan menampilkan Preview Berkas Klaim yang dipilih
        `);

        // AJAX FETCH
        fetch("/api/pasien/"+kunjungan+"/lab")
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
            $('#preview').empty().html(`<iframe src="${fileURL}" width="100%" height="500px" frameborder="0"></iframe>`);
            $('#ck_laboratorium').prop('disabled',false);
        })
        .catch(error => {
            iziToast.error({
                title: 'Maaf!',
                message: 'Data Hasil Laboratorium tidak ditemukan atau belum dibuatkan oleh Simgos.',
                position: 'topRight'
            });
            console.error(error);
            $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);
            $('#ck_laboratorium').prop('checked', false).prop('disabled',false);
        });
    }

    function kwitansiresep(kunjungan) {
        $('#ck_kwitansiresep').prop('disabled',true);
        $('#preview').empty().append(`
            <div class="spinner-grow align-middle me-2" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            Area ini akan menampilkan Preview Berkas Klaim yang dipilih
        `);

        // AJAX FETCH
        fetch("/api/pasien/"+kunjungan+"/kwitansiResep")
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
            $('#preview').empty().html(`<iframe src="${fileURL}" width="100%" height="500px" frameborder="0"></iframe>`);
            $('#ck_kwitansiresep').prop('disabled',false);
        })
        .catch(error => {
            iziToast.error({
                title: 'Maaf!',
                message: 'Data Kwitansi Resep tidak ditemukan atau belum dibuatkan oleh Simgos.',
                position: 'topRight'
            });
            console.error(error);
            $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);
            $('#ck_kwitansiresep').prop('checked', false).prop('disabled',false);
        });
    }

    function prosesSubmit(kunjungan) {
        $('#btn-submit').prop('disabled',true).find('i').removeClass('fa-paper-plane').addClass('fa-sync fa-spin');
        // console.log('submit diklik');

        var save = new FormData();
        save.append('kunjungan',kunjungan);
        save.append('sep',$('#ck_sep').prop('checked'));
        save.append('resume',$('#ck_resume').prop('checked'));
        save.append('laboratorium',$('#ck_laboratorium').prop('checked'));
        save.append('kwitansiresep',$('#ck_kwitansiresep').prop('checked'));
        save.append('user',"{{ Auth::user()->ID }}");

        // console.log($('#ck_resume').prop('checked'));
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('api.klaim.farmasi.submit') }}",
            method: 'POST',
            data: save,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(res) {
                // Apabila success
                const tahun = res.tahun;
                const bulan = res.bulan;
                const kunjungan = res.kunjungan;
                const fileURL = `/api/klaim/farmasi/${tahun}/${bulan}/${kunjungan}/pdf`;
                // $('#preview').empty();
                verify();
                $('#preview').empty().html(`<iframe src="${fileURL}" width="100%" height="500px" frameborder="0"></iframe>`);
                iziToast.success({
                    title: 'Pesan Sukses!',
                    message: res.message,
                    position: 'topRight'
                });
                $('#btn-submit').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-paper-plane');
            }, error: function(xhr, status, error) {
                // Gagal: tangani error di sini
                console.error('Terjadi kesalahan:', error);
                // Bisa juga tampilkan alert
                iziToast.error({
                    title: 'Maaf!',
                    message: 'Submit Berkas Gagal dilakukan. Silakan coba lagi.',
                    position: 'topRight'
                });
                $('#btn-submit').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-paper-plane');
            }
        })
    }

    function hapus(kunjungan) {
        $('#id_hapus').val(kunjungan);
        $('#tx_hapus').text(kunjungan);
        var inputs = document.getElementById('setujuhapus');
        inputs.checked = false;
        $('#hapus').modal('show');
    }

    function prosesHapus() {
        var checkboxHapus = $('#setujuhapus').is(":checked");
        if (checkboxHapus == false) {
            iziToast.error({
                title: 'Pesan Galat!',
                message: 'Mohon menyetujui untuk dilakukan penghapusan berkas klaim tersebut',
                position: 'topRight'
            });
        } else {
            // PROSES HAPUS
            var kunjungan = $('#id_hapus').val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: `/api/klaim/farmasi/${kunjungan}/hapus`,
                type: 'DELETE',
                dataType: 'json',
                success: function(res) {
                    $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);
                    verify();
                    iziToast.success({
                        title: 'Pesan Berhasil!',
                        message: `Berkas Klaim dengan No.Kunjungan : ${kunjungan} berhasil dihapus dari datarecord`,
                        position: 'topRight'
                    });
                    $('#hapus').modal('hide');
                }
            })
        }
    }

    function upload(kunjungan) {
        $('#id_upload').val(kunjungan);
        $('#nama_tambahan').val('');
        $('#filex').val('');
        $('#upload').modal('show');
    }

    function prosesUpload() {
        $('#btn-upload-proses').prop('disabled',true).find('i').removeClass('fa-upload').addClass('fa-sync fa-spin');

        var save = new FormData();
        var filesAdded = $('#filex')[0].files;
        save.append('file',filesAdded[0]);
        save.append('kunjungan',$('#id_upload').val());
        save.append('nama_tambahan',$('#nama_tambahan').val());
        save.append('user',"{{ Auth::user()->ID }}");

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('api.klaim.upload') }}",
            method: 'POST',
            data: save,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(res) {
                // Apabila success
                verify();
                iziToast.success({
                    title: 'Pesan Sukses!',
                    message: res.message,
                    position: 'topRight'
                });
                $('#btn-upload-proses').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-upload');
                $('#upload').modal('hide');
            }, error: function(xhr, status, error) {
                console.error('Terjadi kesalahan:', error);
                console.error('Status:', status);
                console.error('Response Text:', xhr.responseText);
                // Bisa juga tampilkan alert
                try {
                    let response = JSON.parse(xhr.responseText);
                    iziToast.error({
                        title: 'Maaf!',
                        message: response.message,
                        position: 'topRight'
                    });
                } catch (e) {
                    alert('Terjadi kesalahan: ' + error);
                }
                $('#btn-upload-proses').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-upload');
            }
        })
    }

    function showUpload(id) {
        $('#preview').empty().append(`
            <div class="spinner-grow align-middle me-2" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            Area ini akan menampilkan Preview Berkas Klaim yang dipilih
        `);

        // AJAX FETCH
        fetch("/api/klaim/upload/"+id+"/show")
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
            $('#preview').empty().html(`<iframe src="${fileURL}" width="100%" height="500px" frameborder="0"></iframe>`);
        })
        .catch(error => {
            iziToast.error({
                title: 'Maaf!',
                message: 'Berkas Upload Tambahan tidak ditemukan atau gagal ditampilkan.',
                position: 'topRight'
            });
            console.error(error);
            $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);
        });
    }

    function hapusTambahan(id) {
        $('#id_hapus_tambahan').val(id);
        $('#tx_hapus_tambahan').text(id);
        var inputs = document.getElementById('setujuhapustambahan');
        inputs.checked = false;
        $('#hapusTambahan').modal('show');
    }

    function prosesHapusTambahan() {
        var checkboxHapus = $('#setujuhapustambahan').is(":checked");
        if (checkboxHapus == false) {
            iziToast.error({
                title: 'Pesan Galat!',
                message: 'Mohon menyetujui untuk dilakukan penghapusan berkas Tambahan tersebut',
                position: 'topRight'
            });
        } else {
            // PROSES HAPUS
            var id_berkas = $('#id_hapus_tambahan').val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: `/api/klaim/upload/${id_berkas}/hapus`,
                type: 'DELETE',
                dataType: 'json',
                success: function(res) {
                    verify();
                    iziToast.success({
                        title: 'Pesan Berhasil!',
                        message: `Salah satu Berkas Tambahan terpilih dengan ID Berkas : ${id_berkas} berhasil dihapus dari datarecord`,
                        position: 'topRight'
                    });
                    $('#hapusTambahan').modal('hide');
                }
            })
        }
    }

    function verifikasi(kunjungan) {
        $('#btn-verif').prop('disabled',true).find('i').removeClass('fa-check-square').addClass('fa-sync fa-spin');
        // console.log($('#ck_resume').prop('checked'));

        $.ajax({
            url: `/api/klaim/farmasi/${kunjungan}/verifikasi`,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.code == 1) {
                    verify();
                    iziToast.success({
                        title: 'Pesan Sukses!',
                        message: res.message,
                        position: 'topRight'
                    });
                } else {
                    iziToast.warning({
                        title: 'Maaf!',
                        message: res.message,
                        position: 'topRight'
                    });
                }
                $('#btn-verif').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-check-square');
            }, error: function(xhr, status, error) {
                // Gagal: tangani error di sini
                console.error('Terjadi kesalahan:', error);
                // Bisa juga tampilkan alert
                iziToast.error({
                    title: 'Maaf!',
                    message: 'Verifikasi berkas gagal dilakukan. Silakan coba lagi.',
                    position: 'topRight'
                });
                $('#btn-verif').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-check-square');
            }
        })
    }

    function batalVerifikasi(kunjungan) {
        $('#btn-batal-verif').prop('disabled',true).find('i').removeClass('fa-times-circle').addClass('fa-sync fa-spin');
        // console.log($('#ck_resume').prop('checked'));

        $.ajax({
            url: `/api/klaim/farmasi/${kunjungan}/batalverifikasi`,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                verify();
                iziToast.success({
                    title: 'Pesan Sukses!',
                    message: 'Batal Verifikasi berkas berhasil dilakukan pada '+res,
                    position: 'topRight'
                });
                $('#btn-batal-verif').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-times-circle');
            }, error: function(xhr, status, error) {
                // Gagal: tangani error di sini
                console.error('Terjadi kesalahan:', error);
                // Bisa juga tampilkan alert
                iziToast.error({
                    title: 'Maaf!',
                    message: 'Batal Verifikasi berkas gagal dilakukan. Silakan coba lagi.',
                    position: 'topRight'
                });
                $('#btn-batal-verif').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-times-circle');
            }
        })
    }

    function clearCheckbox() {
        $('#ck_resume').prop('checked', false).prop('disabled',false);
        $('#ck_sep').prop('checked', false).prop('disabled',false);
        $('#ck_laboratorium').prop('checked', false).prop('disabled',false);
        $('#ck_kwitansiresep').prop('checked', false).prop('disabled',false);
        $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);
        iziToast.success({
            title: 'Pesan Berhasil!',
            message: 'Ceklist Berkas dan Area Preview berhasil dibersihkan',
            position: 'topRight'
        });
    }
</script>
@endsection
