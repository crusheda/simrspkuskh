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
                <div class="list-group-item d-flex align-items-center p-3 border-top-0 border-start-0 border-end-0">
                    <div class="input-group">
                        <input class="form-check-input" type="checkbox" id="ck_billing" style="width: 2em;height: 2.2em;margin-top:0px"
                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ceklist Berkas Klaim" onchange="if(this.checked){ billing('{{ $list['KUNJUNGAN'] }}'); }
                        else {$('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);}" disabled>
                    </div>
                    <a class="text-nowrap mt-1">Billing</a>
                </div>
                <div class="list-group-item d-flex align-items-center p-3 border-top-0 border-start-0 border-end-0 border bottom-0">
                    <div class="input-group">
                        <input class="form-check-input" type="checkbox" id="ck_laboratorium" style="width: 2em;height: 2.2em;margin-top:0px"
                        data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ceklist Berkas Klaim" onchange="if(this.checked){ laboratorium('{{ $list['KUNJUNGAN'] }}'); }
                        else {$('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);}" disabled>
                    </div>
                    <a class="text-nowrap mt-1">Laboratorium</a>
                </div>
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
                        <iframe src="/api/klaim/{{ $list['klaim']->tahun }}/{{ $list['klaim']->bulan }}/{{ $list['klaim']->nomor }}/pdf" width="100%" height="500px" frameborder="0"></iframe>
                    @else
                        Area ini akan menampilkan Preview Berkas Klaim yang dipilih
                    @endif
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
            url: `/api/klaim/${kunjungan}/data`,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                console.log(res.file);
                // REFRESH HALAMAN
                if (!res.show) {
                    $('#alert_verif').empty();
                    $('#ck_sep').prop('checked', false).prop('disabled', false);
                    $('#ck_resume').prop('checked', false).prop('disabled', false);
                    $('#ck_billing').prop('checked', false).prop('disabled', false);
                    $('#ck_laboratorium').prop('checked', false).prop('disabled', false);

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
                    if (koleksi.includes(5)) { $('#ck_billing').prop('checked', true).prop('disabled', false); } else { $('#ck_billing').prop('checked', false).prop('disabled',false); }
                    if (koleksi.includes(6)) { $('#ck_laboratorium').prop('checked', true).prop('disabled', false); } else { $('#ck_laboratorium').prop('checked', false).prop('disabled',false); }

                    $('#btn-refresh-klaim').empty().append(`<button class="btn btn-light-primary" onclick="prosesSubmit('${kunjungan}')">Refresh Preview Klaim</button>`);

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

    function billing(kunjungan) {
        $('#ck_billing').prop('disabled',true);
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
            return response.blob();
        })
        .then(blob => {
            // Buat object URL dari blob
            const fileURL = URL.createObjectURL(blob);

            // Tampilkan ke iframe dalam modal
            $('#preview').empty().html(`<iframe src="${fileURL}" width="100%" height="500px" frameborder="0"></iframe>`);
            $('#ck_billing').prop('disabled',false);
        })
        .catch(error => {
            iziToast.error({
                title: 'Maaf!',
                message: 'Data Billing tidak ditemukan atau belum dibuatkan oleh Simgos.',
                position: 'topRight'
            });
            console.error(error);
            $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);
            $('#ck_billing').prop('checked', false).prop('disabled',false);
        });
    }

    function individual(kunjungan) {
        $('#ck_individual').prop('disabled',true);
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
            return response.blob();
        })
        .then(blob => {
            // Buat object URL dari blob
            const fileURL = URL.createObjectURL(blob);

            // Tampilkan ke iframe dalam modal
            $('#preview').empty().html(`<iframe src="${fileURL}" width="100%" height="500px" frameborder="0"></iframe>`);
            $('#ck_individual').prop('disabled',false);
        })
        .catch(error => {
            iziToast.error({
                title: 'Maaf!',
                message: 'Data Individual tidak ditemukan atau belum dibuatkan oleh Simgos.',
                position: 'topRight'
            });
            console.error(error);
            $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);
            $('#ck_individual').prop('checked', false).prop('disabled',false);
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

    function prosesSubmit(kunjungan) {
        $('#btn-submit').prop('disabled',true).find('i').removeClass('fa-paper-plane').addClass('fa-sync fa-spin');
        // console.log('submit diklik');

        var save = new FormData();
        save.append('kunjungan',kunjungan);
        save.append('sep',$('#ck_sep').prop('checked'));
        save.append('resume',$('#ck_resume').prop('checked'));
        save.append('billing',$('#ck_billing').prop('checked'));
        save.append('laboratorium',$('#ck_laboratorium').prop('checked'));
        save.append('user',"{{ Auth::user()->ID }}");

        // console.log($('#ck_resume').prop('checked'));
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
        $('#ck_resume').prop('checked', false).prop('disabled',false);
        $('#ck_sep').prop('checked', false).prop('disabled',false);
        $('#ck_billing').prop('checked', false).prop('disabled',false);
        $('#ck_laboratorium').prop('checked', false).prop('disabled',false);
        $('#preview').empty().append(`Area ini akan menampilkan Preview Berkas Klaim yang dipilih`);
        iziToast.success({
            title: 'Pesan Berhasil!',
            message: 'Ceklist Berkas dan Area Preview berhasil dibersihkan',
            position: 'topRight'
        });
    }
</script>
@endsection
