@extends('layouts.index')

@section('content')

<!-- [ breadcrumb ] start -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Display</a></li>
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Antrian</a></li>
                    <li class="breadcrumb-item" aria-current="page">Poliklinik</li>
                </ul>
            </div>
            <div class="col-md-12">
                <div class="page-header-title">
                    <h2 class="mb-0">Display <b class="text-primary">Antrian Poliklinik</b></h2>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ breadcrumb ] end -->

<!-- [ Main Content ] start -->
<div class="row">
    <div class="card">
            <div class="card-header align-items-center d-flex justify-content-between py-2 px-0">
                <div>
                    <div class="input-group">
                        <select class="form-select" id="pilih_poli">
                            <option value="">Pilih Poliklinik</option>
                            @if (count($list['poli']) > 0)
                                @foreach ($list['poli'] as $item)
                                    <option value="{{ $item->IDRUANGAN }}">{{ $item->NAMARUANGAN }}</option>
                                @endforeach
                            @endif
                        </select>
                        <select class="form-select" id="pilih_dr" disabled>
                            <option value="">Pilih Dokter Spesialis</option>
                        </select>
                        {{-- <select class="form-select" id="pilih_dr">
                            <option value="">Pilih Dokter Spesialis</option>
                            @if (count($list['dokter']) > 0)
                                @foreach ($list['mapDokterPoli'] as $val)
                                    @if ($val->RUANGAN == $('pilih_poli').val())
                                        @foreach ($list['dokter'] as $item)
                                            @if ($val->DOKTER == $item->ID_DPJP_SIMRS)
                                                <option value="{{ $item->ID_DPJP_BPJS }}">{{ $item->NAMADOKTER }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        </select> --}}
                        <button class="btn btn-primary btn-wave me-1 waves-effect waves-light" id="tampil_antrian" onclick="refresh($('#pilih_poli').val(),$('#pilih_dr').val())" disabled>
                            <i class="fas fa-search me-1"></i> Terapkan
                        </button>
                    </div>
                </div>
                <div class="btn-group">
                    <button id="enableSound" class="btn btn-light-info">🔔 Aktifkan Suara Antrian</button>
                    <button id="openFullscreenBtn" class="btn btn-success" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                        data-bs-placement="bottom" title="Terapkan Display Layar Penuh">
                        <i class="ti ti-arrows-maximize"></i>
                    </button>
                </div>
            </div>
    </div>

    <div class="container-fluid py-3 card" id="myDiv" hidden>
        <div class="row">

            <div class="col-md-12">
                <div class="card custom-card">
                    <div class="card-header bg-dark-gradient rounded">
                        <div class="d-flex justify-content-between align-items-center w-100 text-wrap">
                            <div id="poli" class="fw-bold text-dark text-start mb-0" style="font-size:60px">
                                <div class="spinner-border text-white" role="status">
                                    <span class="visually-hidden">Memuat Nama Poliklinik...</span>
                                </div>
                            </div>
                            <div class="fs-1 fw-bold text-dark text-end mb-0">
                                <h1 id="antrian-tgl">. . .</h1>
                                <h1 id="antrian-detik">. . .</h1>
                            </div>
                        </div>
                    </div>
            <div class="progress" style="height: 15px;">
                <div id="refresh-progress"
                    class="progress-bar bg-warning"
                    role="progressbar"
                    aria-valuemin="0"
                    aria-valuemax="100"
                    style="width: 0%">
                </div>
            </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card custom-card" style="height: 90vh; max-height: calc(90vh - 100px);">
                    <div class="card-header bg-cyan-900">
                        <div class="align-items-center text-center w-100">
                            <div class="p-4">
                                <h1 class="text-uppercase text-gray-100 fw-bold">Belum Dipanggil</h1>
                            </div>
                        </div>
                    </div>
                    <div class="card-body card-bg-light overflow-auto" id="menunggu">
                        <div class="card custom-card mb-3 shadow">
                            <div class="text-center p-4"><div class="spinner-border text-info" role="status"><span class="visually-hidden">Memuat Antrean...</span></div></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card custom-card" style="height: 90vh; max-height: calc(90vh - 100px);">
                    <div class="card-header bg-red-900">
                        <div class="align-items-center w-100 text-center">
                            <div class="p-4">
                                <h1 class="text-uppercase text-gray-100 fw-bold">Saat ini Dipanggil</h1>
                            </div>
                        </div>
                    </div>
                    <div class="card-body card-bg-light d-flex flex-column justify-content-center align-items-center text-center" style="height: 100vh;" id="dipanggil">
                        <div class="text-center p-4"><div class="spinner-border text-danger" role="status"><span class="visually-hidden">Memuat Antrean...</span></div></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card custom-card" style="height: 90vh; max-height: calc(90vh - 100px);">
                    <div class="card-header bg-green-900">
                        <div class="align-items-center text-center w-100">
                            <div class="p-4">
                                <h1 class="text-uppercase text-gray-100 fw-bold">Sudah Dipanggil</h1>
                            </div>
                        </div>
                    </div>
                    <div class="card-body card-bg-light overflow-auto" id="selesai">
                        <div class="card custom-card mb-3 shadow">
                            <div class="text-center p-4"><div class="spinner-border text-success" role="status"><span class="visually-hidden">Memuat Antrean...</span></div></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- [ Main Content ] end -->

<script>
    let refreshInterval = 5000; // 1 menit = 60000 ms, 5000 = 5 detik
    let progressBar = $("#refresh-progress");
    let progressInterval; // simpan interval supaya bisa dihentikan
    let lastNomorDipanggil = null;
    let soundEnabled = false;
    const mapDokterPoli = @json($list['mapDokterPoli']);
    const semuaDokter = @json($list['dokter']);

    function playSound() {
        if (!soundEnabled) return;
        // ganti path sesuai file mp3 kamu
        let audio = new Audio('/sounds/in.wav');
        audio.play().catch(err => console.log("Audio tidak bisa diputar:", err));
    }

    $(document).ready(function() {
        $("#enableSound").on("click", function() {
            let audio = new Audio('/sounds/in.wav');
            audio.play().then(() => {
                soundEnabled = true;
                iziToast.success({
                    title: 'Suara Antrian berhasil diinisialisasi! ✅',
                    message: 'Silakan melanjutkan pemanggilan Antrian',
                    position: 'topRight'
                });
                $(this).hide(); // sembunyikan tombol setelah aktif
            }).catch(err => console.log("Gagal aktifkan suara:", err));
        });

        updateJam();
        setInterval(updateJam, 1000);

        var elem = $("#myDiv")[0]; // ambil elemen DOM murni dari jQuery object

        $("#openFullscreenBtn").on("click", function() {
            if (elem.requestFullscreen) {
            elem.requestFullscreen();
            } else if (elem.mozRequestFullScreen) { // Firefox
            elem.mozRequestFullScreen();
            } else if (elem.webkitRequestFullscreen) { // Chrome, Safari, Opera
            elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) { // IE/Edge
            elem.msRequestFullscreen();
            }
        });

        $('#pilih_poli').on('change', function () {
            const poliId = $(this).val();
            const $dokterSelect = $('#pilih_dr');

            $dokterSelect.empty().append('<option value="">Pilih Dokter Spesialis</option>');

            if (!poliId || poliId == '') {
                $dokterSelect.prop('disabled',true);
                return;
            } else {
                $dokterSelect.prop('disabled',false);
                $dokterSelect.val('');
                $('#tampil_antrian').prop('disabled',true);
            };

            // Ambil daftar dokter yang sesuai dengan poli yang dipilih
            const dokterTerkait = mapDokterPoli
                .filter(m => m.RUANGAN == poliId)
                .map(m => m.DOKTER);

            // Loop daftar dokter dan tampilkan hanya yang sesuai
            semuaDokter.forEach(d => {
                if (dokterTerkait.includes(d.ID_DPJP_SIMRS)) {
                    $dokterSelect.append(
                        `<option value="${d.ID_DPJP_BPJS}">${d.NAMADOKTER}</option>`
                    );
                }
            });
        });

        $('#pilih_dr').on('change', function () {
            const selDr = $(this).val();
            if (!selDr) {
                $('#tampil_antrian').prop('disabled',true);
            } else {
                $('#tampil_antrian').prop('disabled',false);
            };
        });

        // startProgressBar();
    });

    function startProgressBar() {
        clearInterval(progressInterval); // pastikan tidak ada interval lama

        iziToast.success({
            title: 'Antrian berhasil ditampilkan!',
            message: 'Antrian diperbarui setiap 5 detik.',
            position: 'topRight'
        });

        // Matikan animasi sementara
        progressBar.css({
            "transition": "none",
            "width": "0%"
        });

        // Force reflow supaya browser benar2 terapkan width 0%
        progressBar[0].offsetHeight;

        // Hidupkan lagi animasi
        progressBar.css("transition", "width 0.1s linear");

        let step = 100 / (refreshInterval / 100);
        let progress = 0;

        progressInterval = setInterval(() => {
            progress += step;
            if (progress > 100) progress = 100;

            progressBar.css("width", progress + "%");

            if (progress >= 100) {
                clearInterval(progressInterval);
                refresh($('#pilih_poli').val(),$('#pilih_dr').val());
            }
        }, 100);
    }

    function stopProgressBar() {
        clearInterval(progressInterval);
        progressBar.css("width", "0%"); // reset
    }

    function refresh(poli,dr) {
        if (poli == '') {
            iziToast.error({
                title: 'Maaf!',
                message: 'Pilih Poliklinik terlebih dahulu.',
                position: 'topRight'
            });
            return;
        }
        if (dr == '') {
            iziToast.error({
                title: 'Maaf!',
                message: 'Pilih Dokter Spesialis terlebih dahulu.',
                position: 'topRight'
            });
            return;
        }

        $('#tampil_antrian').find('i').removeClass('fa-search').addClass('fa-sync fa-spin').prop('disabled',true);
        const tgl = moment().format('YYYY-MM-DD'); // TGL HARI INI

        // $('#menunggu').html('<div class="text-center p-4"><div class="spinner-border text-info" role="status"><span class="visually-hidden">Memuat Antrean...</span></div></div>');
        // $('#dipanggil').html('<div class="text-center p-4"><div class="spinner-border text-danger" role="status"><span class="visually-hidden">Memuat Antrean...</span></div></div>');
        // $('#selesai').html('<div class="text-center p-4"><div class="spinner-border text-success" role="status"><span class="visually-hidden">Memuat Antrean...</span></div></div>');
        $.ajax({
            url: `/api/display/antrian/poli/${tgl}/${poli}/${dr}`,
            type: "GET",
            dataType: "json",
            success: function(res) {
                $('#poli').html(`
                    <h1 class="mb-0" style="font-size:80px">Antrean <u>${res.poli.NAMARUANGAN}</u></h1>
                    <h1 class="mb-0" style="font-size:50px">Dokter : <b class="text-primary">${res.dokter.NAMADOKTER}</b></h1>
                `);

                // render data
                let rows_menunggu = "";
                let rows_selesai = "";

                if (res.menunggu.length === 0) {
                    rows_menunggu = ` `;
                } else {
                    $.each(res.menunggu, function(index, item) {
                        rows_menunggu += `
                            <div class="card custom-card mb-3 shadow">
                                <div class="card-body card-bg-light d-flex align-items-center">
                                    <div class="me-3 border border-primary rounded d-flex justify-content-center align-items-center" style="height: auto; width: auto;">
                                        <span class="fw-bold p-2" style="font-size: 50px">${item.POS?item.POS.toString():'A2'}-${item.NOMORANTREAN.toString().padStart(3, '0')}</span>
                                    </div>
                                    <div>
                                        <div class="fs-1 fw-medium">Menunggu Dipanggil</div>
                                        <p class="mb-0 text-muted fs-2">RM. ${item.NORM.toString().padStart(8, '0')}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                }
                $("#menunggu").empty().html(rows_menunggu);

                if (res.dipanggil) {
                    $('#dipanggil').empty().append(`
                        <div class="mb-3">
                            <h2 class="fw-bold" style="font-size: 80px">NOMOR ANTRIAN</h2>
                            <h1 class="fw-bold text-danger" style="font-size: 250px">${res.dipanggil.POS?res.dipanggil.POS.toString()+'-':''}${res.dipanggil.NOMORANTREAN.toString().padStart(3, '0')}</h1>
                        </div>
                        <div class="mb-3">
                            <div class="fw-bold mb-3" style="font-size:80px"><u>${res.dipanggil.NAMARUANGAN}</u></div>
                            <p class="mb-3 fs-1 fw-bold">RM. ${res.dipanggil.NORM.toString().padStart(8, '0')}</p>
                        </div>
                    `);
                } else {
                    $('#dipanggil').empty().append(`
                        <div class="mb-3">
                            <h2 class="fw-bold" style="font-size: 50px">NOMOR ANTRIAN</h2>
                            <h1 class="fw-bold text-danger" style="font-size: 250px">00</h1>
                        </div>
                    `);
                }

                // 🔔 cek notifikasi suara
                if (res.dipanggil && res.dipanggil.NOMORANTREAN) {
                    let nomorBaru = res.dipanggil.NOMORANTREAN;
                    if (lastNomorDipanggil !== null && nomorBaru !== lastNomorDipanggil) {
                        playSound();
                    }
                    lastNomorDipanggil = nomorBaru;
                }

                if (res.selesai.length === 0) {
                    rows_selesai = ` `;
                } else {
                    $.each(res.selesai, function(index, item) {
                        rows_selesai += `
                            <div class="card custom-card mb-3 shadow">
                                <div class="card-body card-bg-light d-flex align-items-center">
                                    <div class="me-3 border border-primary rounded d-flex justify-content-center align-items-center" style="height: auto; width: auto;">
                                        <span class="fw-bold p-2" style="font-size: 50px">${item.POS.toString()}-${item.NOMORANTREAN.toString().padStart(3, '0')}</span>
                                    </div>
                                    <div>
                                        <div class="fs-1 fw-medium">Sudah Dipanggil</div>
                                        <p class="mb-0 text-muted fs-2">RM. ${item.NORM.toString().padStart(8, '0')}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                }
                $("#selesai").empty().html(rows_selesai);

                $('#myDiv').prop('hidden',false);

                // kalau sukses -> jalankan progress bar lagi
                startProgressBar();

                $('#tampil_antrian').find('i').removeClass('fa-sync fa-spin').addClass('fa-search').prop('disabled',false);
            },
            error: function(xhr, status, error) {
                iziToast.error({
                    title: 'Maaf, Ambil Antrian Gagal!',
                    message: error,
                    position: 'topRight'
                });
                console.error("Gagal load antrean:", error);
                $('#menunggu').html('');
                $('#dipanggil').html('');
                $('#selesai').html('');
                stopProgressBar();
                // coba ulang setelah 5 detik
                // setTimeout(refresh, 3000);
                $('#tampil_antrian').find('i').removeClass('fa-sync fa-spin').addClass('fa-search').prop('disabled',false);
            }
        });
    }

    function padZero(num) {
        return num < 10 ? '0' + num : num;
    }

    function updateJam() {
        const now = new Date();

        const hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const bulan = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        const namaHari = hari[now.getDay()];
        const tanggal = now.getDate();
        const namaBulan = bulan[now.getMonth()];
        const tahun = now.getFullYear();

        const jam = padZero(now.getHours());
        const menit = padZero(now.getMinutes());
        const detik = padZero(now.getSeconds());

        const waktuFormatTgl = `${namaHari}, ${tanggal} ${namaBulan} ${tahun}`;
        const waktuFormatDetik = `Pukul ${jam}:${menit}:${detik} WIB`;

        $('#antrian-tgl').text(waktuFormatTgl);
        $('#antrian-detik').text(waktuFormatDetik);
    }
</script>
@endsection
