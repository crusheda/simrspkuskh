@extends('layouts.index3')

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
                    <li class="breadcrumb-item" aria-current="page">Berkas</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Klaim Berkas - <b class="text-primary">{{ $list['show']->NAMAPASIEN }}</b></h2>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
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
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Submit Berkas Klaim" disabled>
                    <button class="btn btn-secondary btn-sm rounded-end" id="btn_sep" style="width: 2.2em;height: 2.2em" data-bs-toggle="tooltip" data-bs-placement="bottom"
                    title="Preview Berkas SEP" onclick="sep('{{ $list['KUNJUNGAN'] }}')"><i class="fas fa-file-signature"></i></button>
                </div>
                <a class="text-nowrap mt-1">SEP</a>
            </div>
            <div class="list-group-item d-flex align-items-center p-3 border-top-0 border-start-0 border-end-0">
                <div class="input-group">
                    <input class="form-check-input" type="checkbox" id="ck_resume" style="width: 2em;height: 2.2em;margin-top:0px"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Submit Berkas Klaim" disabled>
                    <button class="btn btn-secondary btn-sm rounded-end" id="btn_resume" style="width: 2.2em;height: 2.2em" data-bs-toggle="tooltip" data-bs-placement="bottom"
                    title="Preview Berkas Resume Medis" onclick="resume('{{ $list['KUNJUNGAN'] }}')"><i class="fas fa-file-signature"></i></button>
                </div>
                <a class="text-nowrap mt-1">Resume Medis</a>
            </div>
            <div class="list-group-item d-flex align-items-center p-3 border-top-0 border-start-0 border-end-0">
                <div class="input-group">
                    <input class="form-check-input" type="checkbox" id="ck_skdp" style="width: 2em;height: 2.2em;margin-top:0px"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Submit Berkas Klaim" disabled>
                    <button class="btn btn-secondary btn-sm rounded-end" id="btn_skdp" style="width: 2.2em;height: 2.2em" data-bs-toggle="tooltip" data-bs-placement="bottom"
                    title="Preview Berkas SKDP" onclick="skdp('{{ $list['KUNJUNGAN'] }}')"><i class="fas fa-file-signature"></i></button>
                </div>
                <a class="text-nowrap mt-1">SKDP</a>
            </div>
            <div class="list-group-item d-flex align-items-center p-3 border-top-0 border-start-0 border-end-0 border bottom-0">
                <div class="input-group">
                    <input class="form-check-input" type="checkbox" id="ck_individual" style="width: 2em;height: 2.2em;margin-top:0px"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Submit Berkas Klaim" disabled>
                    <button class="btn btn-secondary btn-sm rounded-end" id="btn_individual" style="width: 2.2em;height: 2.2em" data-bs-toggle="tooltip" data-bs-placement="bottom"
                    title="Preview Berkas Individual" onclick="individual('{{ $list['KUNJUNGAN'] }}')"><i class="fas fa-file-signature"></i></button>
                </div>
                <a class="text-nowrap mt-1">Individual</a>
            </div>
            <div class="list-group-item d-flex align-items-center p-3 border-top-0 border-start-0 border-end-0">
                <div class="input-group">
                    <input class="form-check-input" type="checkbox" id="ck_billing" style="width: 2em;height: 2.2em;margin-top:0px"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Submit Berkas Klaim" disabled>
                    <button class="btn btn-secondary btn-sm rounded-end" id="btn_billing" style="width: 2.2em;height: 2.2em" data-bs-toggle="tooltip" data-bs-placement="bottom"
                    title="Preview Berkas Billing" onclick="billing('{{ $list['KUNJUNGAN'] }}')"><i class="fas fa-file-signature"></i></button>
                </div>
                <a class="text-nowrap mt-1">Billing</a>
            </div>
            <div class="list-group-item d-flex align-items-center p-3 border-top-0 border-start-0 border-end-0 border bottom-0">
                <div class="input-group">
                    <input class="form-check-input" type="checkbox" id="ck_laboratorium" style="width: 2em;height: 2.2em;margin-top:0px"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Submit Berkas Klaim" disabled>
                    <button class="btn btn-secondary btn-sm rounded-end" id="btn_laboratorium" style="width: 2.2em;height: 2.2em" data-bs-toggle="tooltip" data-bs-placement="bottom"
                    title="Preview Berkas Hasil Laboratorium" onclick="laboratorium('{{ $list['KUNJUNGAN'] }}')"><i class="fas fa-file-signature"></i></button>
                </div>
                <a class="text-nowrap mt-1">Laboratorium</a>
            </div>
            <div class="list-group-item d-flex align-items-center p-3 border-top-0 border-start-0 border-end-0 border bottom-0">
                <div class="input-group">
                    <input class="form-check-input" type="checkbox" id="ck_radiologi" style="width: 2em;height: 2.2em;margin-top:0px"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Submit Berkas Klaim" disabled>
                    <button class="btn btn-secondary btn-sm rounded-end" id="btn_radiologi" style="width: 2.2em;height: 2.2em" data-bs-toggle="tooltip" data-bs-placement="bottom"
                    title="Preview Berkas Hasil Radiologi" onclick="radiologi('{{ $list['KUNJUNGAN'] }}')"><i class="fas fa-file-signature"></i></button>
                </div>
                <a class="text-nowrap mt-1">Radiologi</a>
            </div>
            <div class="list-group-item d-flex align-items-center p-3 border-top-0 border-start-0 border-end-0 border bottom-0">
                <div class="input-group">
                    <input class="form-check-input" type="checkbox" id="ck_triage" style="width: 2em;height: 2.2em;margin-top:0px"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Submit Berkas Klaim" disabled>
                    <button class="btn btn-secondary btn-sm rounded-end" id="btn_triage" style="width: 2.2em;height: 2.2em" data-bs-toggle="tooltip" data-bs-placement="bottom"
                    title="Preview Berkas Triage IGD" onclick="triage('{{ $list['KUNJUNGAN'] }}')"><i class="fas fa-file-signature"></i></button>
                </div>
                <a class="text-nowrap mt-1">Triage IGD</a>
            </div>
            <div class="list-group-item d-flex align-items-center p-3 border-top-0 border-start-0 border-end-0 border bottom-0">
                <div class="input-group">
                    <input class="form-check-input" type="checkbox" id="ck_operasi" style="width: 2em;height: 2.2em;margin-top:0px"
                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Submit Berkas Klaim" disabled>
                    <button class="btn btn-secondary btn-sm rounded-end" id="btn_operasi" style="width: 2.2em;height: 2.2em" data-bs-toggle="tooltip" data-bs-placement="bottom"
                    title="Preview Berkas Laporan Operasi" onclick="operasi('{{ $list['KUNJUNGAN'] }}')"><i class="fas fa-file-signature"></i></button>
                </div>
                <a class="text-nowrap mt-1">Laporan Operasi</a>
            </div>
            @if ($list['klaim'])
                <div class="card-footer p-3">
                    <button class="btn btn-danger w-100 btn-sm mb-3" onclick="" disabled><i class="fas fa-trash me-1"></i> Hapus Klaim</button>
                    <button class="btn btn-success btn-sm w-100" onclick="submit('{{ $list['KUNJUNGAN'] }}')" id="btn-submit"><i class="fas fa-paper-plane me-1"></i> Submit Ulang</button>
                </div>
            @else
                <div class="card-footer p-3">
                    <div class="btn-group w-100">
                        <button class="btn btn-light-warning btn-sm" onclick="clearCheckbox()"><i class="ti ti-eraser me-1"></i> Clear</button>
                        <button class="btn btn-primary btn-sm" onclick="submit('{{ $list['KUNJUNGAN'] }}')" id="btn-submit">Submit <i class="fas fa-paper-plane ms-1"></i></button>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between py-3">
                <div>
                    <h5 class="mb-2">Preview Berkas Klaim</h5>
                    <small class="m-0">Pilih daftar berkas di bilah menu kiri</small>
                </div>
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
                    <iframe src="/api/klaim/{{ $list['klaim']->tahun }}/{{ $list['klaim']->bulan }}/{{ $list['klaim']->nomor }}/pdf" width="100%" height="500px" frameborder="0"></iframe>
                @else
                    Area ini akan menampilkan Preview Berkas Klaim yang dipilih
                @endif
            </div>
        </div>
    </div>
</div>

<!-- [ Main Content ] end -->

<script>
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
        $.ajax({
            url: `/api/klaim/${kunjungan}/data`,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                // if (Array.isArray(koleksi) && koleksi.length > 0) {
                // }
                // koleksi.forEach(item => {
                // });
                if (!res.show) {
                    $('#ck_sep').prop('checked', false).prop('disabled', false);
                    $('#ck_resume').prop('checked', false).prop('disabled', false);
                    $('#ck_skdp').prop('checked', false).prop('disabled', false);
                    $('#ck_individual').prop('checked', false).prop('disabled', false);
                    $('#ck_billing').prop('checked', false).prop('disabled', false);
                    $('#ck_laboratorium').prop('checked', false).prop('disabled', false);
                    $('#ck_radiologi').prop('checked', false).prop('disabled', false);
                    $('#ck_triage').prop('checked', false).prop('disabled', false);
                    $('#ck_operasi').prop('checked', false).prop('disabled', false);
                } else {
                    let koleksi = JSON.parse(res.show.koleksi || '[]');
                    if (koleksi.includes(1)) { $('#ck_sep').prop('checked', true).prop('disabled', true); } else { $('#ck_sep').prop('checked', false).prop('disabled',false); }
                    if (koleksi.includes(2)) { $('#ck_resume').prop('checked', true).prop('disabled', true); } else { $('#ck_resume').prop('checked', false).prop('disabled',false); }
                    if (koleksi.includes(3)) { $('#ck_skdp').prop('checked', true).prop('disabled', true); } else { $('#ck_skdp').prop('checked', false).prop('disabled',false); }
                    if (koleksi.includes(4)) { $('#ck_individual').prop('checked', true).prop('disabled', true); } else { $('#ck_individual').prop('checked', false).prop('disabled',false); }
                    if (koleksi.includes(5)) { $('#ck_billing').prop('checked', true).prop('disabled', true); } else { $('#ck_billing').prop('checked', false).prop('disabled',false); }
                    if (koleksi.includes(6)) { $('#ck_laboratorium').prop('checked', true).prop('disabled', true); } else { $('#ck_laboratorium').prop('checked', false).prop('disabled',false); }
                    if (koleksi.includes(7)) { $('#ck_radiologi').prop('checked', true).prop('disabled', true); } else { $('#ck_radiologi').prop('checked', false).prop('disabled',false); }
                    if (koleksi.includes(8)) { $('#ck_triage').prop('checked', true).prop('disabled', true); } else { $('#ck_triage').prop('checked', false).prop('disabled',false); }
                    if (koleksi.includes(9)) { $('#ck_operasi').prop('checked', true).prop('disabled', true); } else { $('#ck_operasi').prop('checked', false).prop('disabled',false); }
                }
            }
        })
    }

    function resume(kunjungan) {
        $('#btn_resume').prop('disabled',true).find('i').removeClass('fa-file-signature').addClass('fa-sync fa-spin');
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
            $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);
            $('#btn_resume').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-file-signature');
            return response.blob();
        })
        .then(blob => {
            // Buat object URL dari blob
            const fileURL = URL.createObjectURL(blob);

            // Tampilkan ke iframe dalam modal
            $('#preview').empty().html(`<iframe src="${fileURL}" width="100%" height="500px" frameborder="0"></iframe>`);
            $('#btn_resume').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-file-signature');
            $('#ck_resume').prop('checked', true);
        })
        .catch(error => {
            iziToast.error({
                title: 'Maaf!',
                message: 'Data Resume tidak ditemukan atau belum dibuatkan oleh Simgos.',
                position: 'topRight'
            });
            console.error(error);
            $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);
            $('#btn_resume').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-file-signature');
        });
    }

    function sep(kunjungan) {
        $('#btn_sep').prop('disabled',true).find('i').removeClass('fa-file-signature').addClass('fa-sync fa-spin');
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
            $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);
            $('#btn_sep').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-file-signature');
            return response.blob();
        })
        .then(blob => {
            // Buat object URL dari blob
            const fileURL = URL.createObjectURL(blob);

            // Tampilkan ke iframe dalam modal
            $('#preview').empty().html(`<iframe src="${fileURL}" width="100%" height="500px" frameborder="0"></iframe>`);
            $('#btn_sep').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-file-signature');
            $('#ck_sep').prop('checked', true);
        })
        .catch(error => {
            iziToast.error({
                title: 'Maaf!',
                message: 'Data SEP tidak ditemukan atau belum dibuatkan oleh Simgos.',
                position: 'topRight'
            });
            console.error(error);
            $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);
            $('#btn_sep').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-file-signature');
        });
    }

    function skdp(kunjungan) {
        $('#btn_skdp').prop('disabled',true).find('i').removeClass('fa-file-signature').addClass('fa-sync fa-spin');
        $('#preview').empty().append(`
            <div class="spinner-grow align-middle me-2" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            Area ini akan menampilkan Preview Berkas Klaim yang dipilih
        `);

        // AJAX FETCH
        fetch("/api/pasien/"+kunjungan+"/skdp")
        .then(response => {
            if (!response.ok) {
                throw new Error('File tidak ditemukan atau gagal diambil.');
            }
            $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);
            $('#btn_skdp').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-file-signature');
            return response.blob();
        })
        .then(blob => {
            // Buat object URL dari blob
            const fileURL = URL.createObjectURL(blob);

            // Tampilkan ke iframe dalam modal
            $('#preview').empty().html(`<iframe src="${fileURL}" width="100%" height="500px" frameborder="0"></iframe>`);
            $('#btn_skdp').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-file-signature');
            $('#ck_skdp').prop('checked', true);
        })
        .catch(error => {
            iziToast.error({
                title: 'Maaf!',
                message: 'Data SKDP tidak ditemukan atau belum dibuatkan oleh Simgos.',
                position: 'topRight'
            });
            console.error(error);
            $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);
            $('#btn_skdp').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-file-signature');
        });
    }

    function billing(kunjungan) {
        $('#btn_billing').prop('disabled',true).find('i').removeClass('fa-file-signature').addClass('fa-sync fa-spin');
        $('#preview').empty().append(`
            <div class="spinner-grow align-middle me-2" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            Area ini akan menampilkan Preview Berkas Klaim yang dipilih
        `);

        // AJAX FETCH
        fetch("/api/pasien/"+kunjungan+"/billing")
        .then(response => {
            if (!response.ok) {
                throw new Error('File tidak ditemukan atau gagal diambil.');
            }
            $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);
            $('#btn_billing').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-file-signature');
            return response.blob();
        })
        .then(blob => {
            // Buat object URL dari blob
            const fileURL = URL.createObjectURL(blob);

            // Tampilkan ke iframe dalam modal
            $('#preview').empty().html(`<iframe src="${fileURL}" width="100%" height="500px" frameborder="0"></iframe>`);
            $('#btn_billing').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-file-signature');
            $('#ck_billing').prop('checked', true);
        })
        .catch(error => {
            iziToast.error({
                title: 'Maaf!',
                message: 'Data Billing tidak ditemukan atau belum dibuatkan oleh Simgos.',
                position: 'topRight'
            });
            console.error(error);
            $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);
            $('#btn_billing').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-file-signature');
        });
    }

    function individual(kunjungan) {
        $('#btn_individual').prop('disabled',true).find('i').removeClass('fa-file-signature').addClass('fa-sync fa-spin');
        $('#preview').empty().append(`
            <div class="spinner-grow align-middle me-2" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            Area ini akan menampilkan Preview Berkas Klaim yang dipilih
        `);

        // AJAX FETCH
        fetch("/api/pasien/"+kunjungan+"/individual")
        .then(response => {
            if (!response.ok) {
                throw new Error('File tidak ditemukan atau gagal diambil.');
            }
            $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);
            $('#btn_individual').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-file-signature');
            return response.blob();
        })
        .then(blob => {
            // Buat object URL dari blob
            const fileURL = URL.createObjectURL(blob);

            // Tampilkan ke iframe dalam modal
            $('#preview').empty().html(`<iframe src="${fileURL}" width="100%" height="500px" frameborder="0"></iframe>`);
            $('#btn_individual').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-file-signature');
            $('#ck_individual').prop('checked', true);
        })
        .catch(error => {
            iziToast.error({
                title: 'Maaf!',
                message: 'Data Individual tidak ditemukan atau belum dibuatkan oleh Simgos.',
                position: 'topRight'
            });
            console.error(error);
            $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);
            $('#btn_individual').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-file-signature');
        });
    }

    function laboratorium(kunjungan) {
        $('#btn_laboratorium').prop('disabled',true).find('i').removeClass('fa-file-signature').addClass('fa-sync fa-spin');
        $('#preview').empty().append(`
            <div class="spinner-grow align-middle me-2" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            Area ini akan menampilkan Preview Berkas Klaim yang dipilih
        `);

        // AJAX FETCH
        fetch("/api/pasien/"+kunjungan+"/laboratorium")
        .then(response => {
            if (!response.ok) {
                throw new Error('File tidak ditemukan atau gagal diambil.');
            }
            $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);
            $('#btn_laboratorium').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-file-signature');
            return response.blob();
        })
        .then(blob => {
            // Buat object URL dari blob
            const fileURL = URL.createObjectURL(blob);

            // Tampilkan ke iframe dalam modal
            $('#preview').empty().html(`<iframe src="${fileURL}" width="100%" height="500px" frameborder="0"></iframe>`);
            $('#btn_laboratorium').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-file-signature');
            $('#ck_laboratorium').prop('checked', true);
        })
        .catch(error => {
            iziToast.error({
                title: 'Maaf!',
                message: 'Data Hasil Laboratorium tidak ditemukan atau belum dibuatkan oleh Simgos.',
                position: 'topRight'
            });
            console.error(error);
            $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);
            $('#btn_laboratorium').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-file-signature');
        });
    }

    function radiologi(kunjungan) {
        $('#btn_radiologi').prop('disabled',true).find('i').removeClass('fa-file-signature').addClass('fa-sync fa-spin');
        $('#preview').empty().append(`
            <div class="spinner-grow align-middle me-2" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            Area ini akan menampilkan Preview Berkas Klaim yang dipilih
        `);

        // AJAX FETCH
        fetch("/api/pasien/"+kunjungan+"/radiologi")
        .then(response => {
            if (!response.ok) {
                throw new Error('File tidak ditemukan atau gagal diambil.');
            }
            $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);
            $('#btn_radiologi').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-file-signature');
            return response.blob();
        })
        .then(blob => {
            // Buat object URL dari blob
            const fileURL = URL.createObjectURL(blob);

            // Tampilkan ke iframe dalam modal
            $('#preview').empty().html(`<iframe src="${fileURL}" width="100%" height="500px" frameborder="0"></iframe>`);
            $('#btn_radiologi').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-file-signature');
            $('#ck_radiologi').prop('checked', true);
        })
        .catch(error => {
            iziToast.error({
                title: 'Maaf!',
                message: 'Data Hasil Radiologi tidak ditemukan atau belum dibuatkan oleh Simgos.',
                position: 'topRight'
            });
            console.error(error);
            $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);
            $('#btn_radiologi').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-file-signature');
        });
    }

    function triage(kunjungan) {
        $('#btn_triage').prop('disabled',true).find('i').removeClass('fa-file-signature').addClass('fa-sync fa-spin');
        $('#preview').empty().append(`
            <div class="spinner-grow align-middle me-2" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            Area ini akan menampilkan Preview Berkas Klaim yang dipilih
        `);

        // AJAX FETCH
        fetch("/api/pasien/"+kunjungan+"/triage")
        .then(response => {
            if (!response.ok) {
                throw new Error('File tidak ditemukan atau gagal diambil.');
            }
            $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);
            $('#btn_triage').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-file-signature');
            return response.blob();
        })
        .then(blob => {
            // Buat object URL dari blob
            const fileURL = URL.createObjectURL(blob);

            // Tampilkan ke iframe dalam modal
            $('#preview').empty().html(`<iframe src="${fileURL}" width="100%" height="500px" frameborder="0"></iframe>`);
            $('#btn_triage').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-file-signature');
            $('#ck_triage').prop('checked', true);
        })
        .catch(error => {
            iziToast.error({
                title: 'Maaf!',
                message: 'Data Triage IGD tidak ditemukan atau belum dibuatkan oleh Simgos.',
                position: 'topRight'
            });
            console.error(error);
            $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);
            $('#btn_triage').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-file-signature');
        });
    }
    function operasi(kunjungan) {
        $('#btn_operasi').prop('disabled',true).find('i').removeClass('fa-file-signature').addClass('fa-sync fa-spin');
        $('#preview').empty().append(`
            <div class="spinner-grow align-middle me-2" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            Area ini akan menampilkan Preview Berkas Klaim yang dipilih
        `);

        // AJAX FETCH
        fetch("/api/pasien/"+kunjungan+"/operasi")
        .then(response => {
            if (!response.ok) {
                throw new Error('File tidak ditemukan atau gagal diambil.');
            }
            $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);
            $('#btn_operasi').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-file-signature');
            return response.blob();
        })
        .then(blob => {
            // Buat object URL dari blob
            const fileURL = URL.createObjectURL(blob);

            // Tampilkan ke iframe dalam modal
            $('#preview').empty().html(`<iframe src="${fileURL}" width="100%" height="500px" frameborder="0"></iframe>`);
            $('#btn_operasi').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-file-signature');
            $('#ck_operasi').prop('checked', true);
        })
        .catch(error => {
            iziToast.error({
                title: 'Maaf!',
                message: 'Data Laporan Operasi tidak ditemukan atau belum dibuatkan oleh Simgos.',
                position: 'topRight'
            });
            console.error(error);
            $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);
            $('#btn_operasi').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-file-signature');
        });
    }

    function submit(kunjungan) {
        $('#btn-submit').prop('disabled',true).find('i').removeClass('fa-paper-plane').addClass('fa-sync fa-spin');
        // console.log($('#ck_resume').prop('checked'));
        var save = new FormData();
        save.append('kunjungan',kunjungan);
        save.append('user',"{{ Auth::user()->ID }}");
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('api.klaim.submit') }}",
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
                const fileURL = `/api/klaim/${tahun}/${bulan}/${kunjungan}/pdf`;
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

    function clearCheckbox() {
        $('#ck_resume').prop('checked', false);
        $('#ck_sep').prop('checked', false);
        $('#ck_skdp').prop('checked', false);
        $('#ck_billing').prop('checked', false);
        $('#ck_individual').prop('checked', false);
        $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);
        iziToast.success({
            title: 'Pesan Berhasil!',
            message: 'Ceklist Berkas dan Area Preview berhasil dibersihkan',
            position: 'topRight'
        });
    }

    // Mengecek apakah checkbox tercentang
    // var isChecked = $('input[type="checkbox"].form-check-input').prop('checked');
    // console.log(isChecked); // true jika tercentang, false jika tidak
</script>
@endsection
