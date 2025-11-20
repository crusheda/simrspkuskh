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
                        {{-- POLI DOKTER 1 --}}
                        <select class="form-select" id="pilih_poli1" disabled>
                            <option value="">Pilih Poliklinik</option>
                            @if (count($list['poli']) > 0)
                                @foreach ($list['poli'] as $item)
                                    <option value="{{ $item->IDRUANGAN }}">{{ $item->NAMARUANGAN }}</option>
                                @endforeach
                            @endif
                        </select>
                        <select class="form-select" id="pilih_dr1" disabled>
                            <option value="">Pilih Dokter Spesialis</option>
                        </select>
                        {{-- POLI DOKTER 2 --}}
                        <select class="form-select" id="pilih_poli2" disabled hidden>
                            <option value="">Pilih Poliklinik</option>
                            @if (count($list['poli']) > 0)
                                @foreach ($list['poli'] as $item)
                                    <option value="{{ $item->IDRUANGAN }}">{{ $item->NAMARUANGAN }}</option>
                                @endforeach
                            @endif
                        </select>
                        <select class="form-select" id="pilih_dr2" disabled hidden>
                            <option value="">Pilih Dokter Spesialis</option>
                        </select>
                        <div class="input-group-text" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="bottom" title="Centang untuk Memperlihatkan Nama Pasien Di Antrian">
                            <div class="form-check">
                                <input class="form-check-input input-primary" type="checkbox" id="showNama" value="" aria-label="">
                                <label class="form-check-label">Identitas?</label>
                            </div>
                        </div>
                        <div class="input-group-text" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" data-bs-placement="bottom" title="Centang untuk Menampilkan 2 Display Antrian Poliklinik">
                            <div class="form-check">
                                <input class="form-check-input input-danger" type="checkbox" id="showMultipleDisplay" value="" aria-label="">
                                <label class="form-check-label">Dual?</label>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-wave me-1 waves-effect waves-light" id="tampil_antrian" onclick="refresh($('#pilih_poli1').val(),$('#pilih_dr1').val(),$('#pilih_poli2').val(),$('#pilih_dr2').val())" disabled>
                            <i class="fas fa-search me-1"></i> Terapkan
                        </button>
                    </div>
                </div>
                <div class="btn-group">
                    {{-- <button id="enableSound" class="btn btn-light-info">🔔 Aktifkan Suara Antrian</button> --}}
                    <button id="openFullscreenBtn" class="btn btn-success d-inline-flex align-items-center" data-bs-toggle="tooltip"
                        data-bs-placement="left" title="Terapkan Display Layar Penuh" disabled>
                        <i class="ti ti-arrows-maximize me-2"></i> <span>Tampilan Layar Penuh</span>
                    </button>
                    <button class="btn btn-danger" id="pause" onclick="pauseProgressBar()" hidden><i class="ti ti-player-pause"></i></button>
                    <button class="btn btn-info" id="resume" onclick="resumeProgressBar()" hidden><i class="ti ti-player-play"></i></button>
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
                                <h1 id="antrian-detik" style="font-size:70px">. . .</h1>
                            </div>
                        </div>
                    </div>
                    <div class="progress" style="height: 5px;">
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
                <div class="card custom-card" style="height: 87vh; max-height: calc(87vh - 100px);">
                    <div class="card-header bg-cyan-900">
                        <div class="align-items-center text-center w-100">
                            <div class="p-4">
                                <h1 class="text-uppercase text-gray-100 fw-bold" style="font-size: 50px">Belum Dipanggil</h1>
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
                <div class="card custom-card" style="height: 87vh; max-height: calc(87vh - 100px);">
                    <div class="card-header bg-red-900">
                        <div class="align-items-center w-100 text-wrap">
                            <div class="text-center p-4">
                                <h1 class="text-uppercase text-gray-100 fw-bold" style="font-size: 50px">Saat ini Dipanggil</h1>
                            </div>
                        </div>
                    </div>
                    <div class="card-body card-bg-light d-flex flex-column justify-content-center align-items-center text-center" style="height: 100vh;" id="dipanggil">
                        <div class="text-center p-4"><div class="spinner-border text-danger" role="status"><span class="visually-hidden">Memuat Antrean...</span></div></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card custom-card" style="height: 87vh; max-height: calc(87vh - 100px);">
                    <div class="card-header bg-green-900">
                        <div class="align-items-center text-center w-100">
                            <div class="p-4">
                                <h1 class="text-uppercase text-gray-100 fw-bold" style="font-size: 50px">Sudah Dipanggil</h1>
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

    {{-- FOR DUAL DISPLAY --}}
    <div class="container-fluid py-3 card" id="myDivDual" hidden>
        <div class="row">

            <div class="col-md-12">
                <div class="card custom-card mb-1">
                    <div class="card-header bg-dark-gradient rounded p-2 ms-2 me-2 pb-1">
                        <div class="d-flex justify-content-between align-items-center w-100 text-wrap">
                            <h1 id="antrian-tgl-m" class="mb-0" style="font-size:30px">. . .</h1>
                            <h1 id="antrian-detik-m" class="mb-0" style="font-size:30px">. . .</h1>
                        </div>
                    </div>
                    <div class="progress" style="height: 5px;">
                        <div id="refresh-progress-dual"
                            class="progress-bar bg-warning"
                            role="progressbar"
                            aria-valuemin="0"
                            aria-valuemax="100"
                            style="width: 0%">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6"> {{-- DISPLAY KIRI --}}
                <div class="card custom-card mb-1">
                    <div class="card-header bg-dark-gradient rounded p-2">
                        <div class="align-items-center w-100 text-wrap">
                            <div id="poli-m1" class="fw-bold text-dark text-start mb-0" style="font-size:30px">
                                <div class="spinner-border text-white" role="status">
                                    <span class="visually-hidden">Memuat Nama Poliklinik...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card custom-card mb-1" style="height: 44vh; max-height: calc(44vh - 100px);">
                    <div class="card-header bg-red-900 p-2">
                        <div class="align-items-center w-100 text-center">
                            <div class="text-center p-0">
                                <h1 class="text-uppercase text-gray-100 fw-bold mb-0" style="font-size: 20px">Saat ini Dipanggil</h1>
                            </div>
                        </div>
                    </div>
                    <div class="card-body card-bg-light d-flex flex-column justify-content-center align-items-center text-center pt-3" style="height: 50vh;" id="dipanggil-m1">
                        <div class="text-center p-4"><div class="spinner-border text-danger" role="status"><span class="visually-hidden">Memuat Antrean...</span></div></div>
                    </div>
                </div>
                <div class="card custom-card mb-1" style="height: 54vh; max-height: calc(54vh - 100px);">
                    <div class="card-header bg-cyan-600 p-2">
                        <div class="align-items-center text-center w-100">
                            <div class="p-0">
                                <h1 class="text-uppercase text-gray-100 fw-bold mb-0" style="font-size: 20px">Belum Dipanggil</h1>
                            </div>
                        </div>
                    </div>
                    <div class="card-body card-bg-light overflow-auto p-2" id="menunggu-m1">
                        <div class="card custom-card mb-3 shadow">
                            <div class="text-center p-4"><div class="spinner-border text-info" role="status"><span class="visually-hidden">Memuat Antrean...</span></div></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6"> {{-- DISPLAY KANAN --}}
                <div class="card custom-card mb-1">
                    <div class="card-header bg-dark-gradient rounded p-2">
                        <div class="d-flex justify-content-between align-items-center w-100 text-wrap">
                            <div id="poli-m2" class="fw-bold text-dark text-start mb-0" style="font-size:30px">
                                <div class="spinner-border text-white" role="status">
                                    <span class="visually-hidden">Memuat Nama Poliklinik...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card custom-card mb-1" style="height: 44vh; max-height: calc(44vh - 100px);">
                    <div class="card-header bg-red-900 p-2">
                        <div class="align-items-center w-100 text-center">
                            <div class="text-center p-0">
                                <h1 class="text-uppercase text-gray-100 fw-bold mb-0" style="font-size: 20px">Saat ini Dipanggil</h1>
                            </div>
                        </div>
                    </div>
                    <div class="card-body card-bg-light d-flex flex-column justify-content-center align-items-center text-center pt-3" style="height: 50vh;" id="dipanggil-m2">
                        <div class="text-center p-4"><div class="spinner-border text-danger" role="status"><span class="visually-hidden">Memuat Antrean...</span></div></div>
                    </div>
                </div>
                <div class="card custom-card mb-1" style="height: 54vh; max-height: calc(54vh - 100px);">
                    <div class="card-header bg-cyan-600 p-2">
                        <div class="align-items-center text-center w-100">
                            <div class="p-0">
                                <h1 class="text-uppercase text-gray-100 fw-bold mb-0" style="font-size: 20px">Belum Dipanggil</h1>
                            </div>
                        </div>
                    </div>
                    <div class="card-body card-bg-light overflow-auto p-2" id="menunggu-m2">
                        <div class="card custom-card mb-3 shadow">
                            <div class="text-center p-4"><div class="spinner-border text-info" role="status"><span class="visually-hidden">Memuat Antrean...</span></div></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="position-fixed bottom-0 start-0 w-100 fw-bold py-2" style="overflow:hidden;white-space:nowrap; z-index:9999;background:#570a0a; color:#ffd705;">
                    <div class="d-inline-block text-uppercase" style="padding-left:100%; animation: runtext 35s linear infinite;font-size:18px;">
                        Selamat Datang di Poliklinik Spesialis Rumah Sakit PKU Muhammadiyah Sukoharjo.&nbsp;&nbsp;&nbsp;Bila pasien dipanggil tidak ada maka akan dilewati 5 pasien berikutnya.
                        &nbsp;&nbsp;&nbsp;Harap menunggu dengan tertib.&nbsp;&nbsp;&nbsp;Pastikan nomor antrian sesuai urutan untuk mempercepat pelayanan.&nbsp;&nbsp;&nbsp;Terima kasih.
                    </div>

                    <style>
                        @keyframes runtext {
                            from { transform: translateX(0); }
                            to   { transform: translateX(-100%); }
                        }
                    </style>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- [ Main Content ] end -->

<script>
    let elemm = $("#myDiv")[0];
    let refreshInterval = 5000; // 1 menit = 60000 ms, 5000 = 5 detik
    let progressBar = $("#refresh-progress");
    let progressBarDual = $("#refresh-progress-dual");
    let progressInterval; // simpan interval supaya bisa dihentikan
    let lastDipanggil = null; // simpan nomor terakhir yang sudah dipanggil
    let lastDipanggil1 = null; // simpan nomor terakhir yang sudah dipanggil
    let lastDipanggil2 = null; // simpan nomor terakhir yang sudah dipanggil
    let soundEnabled = false;
    let soundQueue = Promise.resolve();
    let soundLock = false;
    let identityEnabled = false;
    let identityEnabled1 = false;
    let identityEnabled2 = false;
    let multipleDisplayEnabled = false;
    let progressPaused = false;
    let menungguScrollTop = $('#menunggu').scrollTop();
    let menungguScrollTop1 = $('#menunggu-m1').scrollTop();
    let menungguScrollTop2 = $('#menunggu-m2').scrollTop();
    let selesaiScrollTop = $('#selesai').scrollTop();
    const mapDokterPoli = @json($list['mapDokterPoli']);
    const semuaDokter = @json($list['dokter']);

    $(document).ready(function() {
        $("#tampil_antrian").on("click", function() {
            // let audio = new Audio('/sounds/in.wav');
            let audio = new Audio('/sounds/opening2.mp3');
            audio.play().then(() => {
                soundEnabled = true;
                // iziToast.success({
                //     title: 'Suara Antrian berhasil diinisialisasi! ✅',
                //     message: 'Silakan melanjutkan pemanggilan Antrian',
                //     position: 'topRight'
                // });
                console.log('Suara Antrian berhasil diinisialisasi');
                // $(this).hide(); // sembunyikan tombol setelah aktif
            }).catch(err => console.log("Gagal aktifkan suara:", err));
            $('#resume').prop('hidden',true);
            $('#pause').prop('hidden',false);
        });

        updateJam();
        setInterval(updateJam, 1000);

        $('#pilih_poli1').on('change', function () {
            const poliId = $(this).val();
            const $dokterSelect = $('#pilih_dr1');

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

        $('#pilih_dr1').on('change', function () {
            const selDr = $(this).val();
            if (!selDr) {
                $('#tampil_antrian').prop('disabled',true);
            } else {
                if ($('#showMultipleDisplay').is(':checked')) {
                    if ($('#pilih_poli2').val() == '' || $('#pilih_dr2').val() == '') {
                        $('#tampil_antrian').prop('disabled',true);
                    } else {
                        $('#tampil_antrian').prop('disabled',false);
                    }
                } else {
                    $('#tampil_antrian').prop('disabled',false);
                }
            };
        });

        $('#showNama').on('change', function() {
            const showNama = $(this).is(':checked');

            if (showNama) {
                identityEnabled = true;
                identityEnabled1 = true;
                identityEnabled2 = true;
            } else {
                identityEnabled = false;
                identityEnabled1 = false;
                identityEnabled2 = false;
            }

            // $(this).prop('disabled',true);
            // console.log('Identitas Diperlihatkan : '+identityEnabled);
        });

        $('#showMultipleDisplay').on('change', function() {
            const showMultipleDisplay = $(this).is(':checked');

            if (showMultipleDisplay) { // ambil elemen DOM murni dari jQuery object
                elemm = $("#myDivDual")[0];
            } else {
                elemm = $("#myDiv")[0];
            }

            if (showMultipleDisplay) {
                $('nav.pc-sidebar').addClass('pc-sidebar-hide'); // Hide Sidebar
                $('#tampil_antrian').prop('disabled',true);
                multipleDisplayEnabled = true;
                $('#pilih_poli2').prop('disabled',false).prop('hidden',false);
                $('#pilih_dr2').prop('hidden',false);

                $('#pilih_poli2').on('change', function () {
                    const poliId = $(this).val();
                    const $dokterSelect = $('#pilih_dr2');

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

                $('#pilih_dr2').on('change', function () {
                    const selDr2 = $(this).val();
                    if (!selDr2) {
                        $('#tampil_antrian').prop('disabled',true);
                    } else {
                        if ($('#pilih_poli1').val() == '' || $('#pilih_dr1').val() == '') {
                            $('#tampil_antrian').prop('disabled',true);
                        } else {
                            $('#tampil_antrian').prop('disabled',false);
                        }
                    };
                });
            } else {
                if ($('#pilih_poli1').val() == '' || $('#pilih_dr1').val() == '') {
                    $('#tampil_antrian').prop('disabled',true);
                } else {
                    $('#tampil_antrian').prop('disabled',false);
                }
                multipleDisplayEnabled = false;
                $('#pilih_poli2').val('').prop('disabled',true).prop('hidden',true);
                $('#pilih_dr2').val('').prop('disabled',true).prop('hidden',true);
            }
        });

        // startProgressBar();
        $('#pilih_poli1').prop('disabled',false);

        startAutoScroll('#menunggu', 1); // 0.3
        startAutoScroll('#menunggu-m1', 1); // 0.3
        startAutoScroll('#menunggu-m2', 1); // 0.3
        startAutoScroll('#selesai', 1); // 0.3

        $("#openFullscreenBtn").on("click", function() {
            if (elemm.requestFullscreen) {
            elemm.requestFullscreen();
            } else if (elemm.mozRequestFullScreen) { // Firefox
            elemm.mozRequestFullScreen();
            } else if (elemm.webkitRequestFullscreen) { // Chrome, Safari, Opera
            elemm.webkitRequestFullscreen();
            } else if (elemm.msRequestFullscreen) { // IE/Edge
            elemm.msRequestFullscreen();
            }
        });
    });

    // function playSound(resDipanggil = null) {
    //     if (!soundEnabled) return;
    //     console.log('audio dipanggil');
    //     const inSound = new Audio('/sounds/in.wav');
    //     const outSound = new Audio('/sounds/out.wav');

    //     // 🔹 pastikan data antrian tersedia
    //     if (!resDipanggil) return;

    //     // Bentuk teks ucapan, contoh: "Nomor Antrian A2 013 Silakan masuk ke Poliklinik Penyakit Dalam"
    //     const nomor = `${resDipanggil.POS ? resDipanggil.POS.toString() + " " : ""}${resDipanggil.NOMORANTREAN.toString().padStart(3, '0')}`;
    //     const ruangan = resDipanggil.NAMARUANGAN || "ruangan pemeriksaan poliklinik";
    //     const dokter = resDipanggil.NAMADOKTER || "dokter spesialis";
    //     const textToSpeak = `Nomor Antrian ${nomor}. Silakan masuk ke ${ruangan}.`;

    //     // 🔹 fungsi untuk bicara
    //     const speakText = (text) => {
    //         return new Promise((resolve) => {
    //             const utter = new SpeechSynthesisUtterance(text);
    //             utter.lang = "id-ID"; // Bahasa Indonesia
    //             utter.rate = 0.9;     // agak pelan supaya jelas
    //             utter.pitch = 1.0;
    //             utter.volume = 1.0;

    //             utter.onend = resolve; // lanjut setelah selesai bicara
    //             window.speechSynthesis.speak(utter);
    //         });
    //     };

    //     // 🔹 urutkan suara -> in.wav -> ucapkan -> out.wav
    //     inSound.play()
    //         .then(() => {
    //             return new Promise(resolve => {
    //                 inSound.onended = resolve; // tunggu sampai in.wav selesai
    //             });
    //         })
    //         .then(() => speakText(textToSpeak))
    //         .then(() => outSound.play())
    //         .catch(err => console.error("Gagal memainkan suara:", err));
    // }

    async function playSound(resDipanggil = null) {
        if (!soundEnabled) return;
        console.log('audio dipanggil');

        const inSound = new Audio('/sounds/in.wav');
        const outSound = new Audio('/sounds/out.wav');

        if (!resDipanggil) return;

        // Format nomor antrian (contoh: A1-003)
        const nomor = `${resDipanggil.POS ? resDipanggil.POS.toString() + " " : ""}${resDipanggil.NOMORANTREAN.toString().padStart(3, '0')}`;
        // const dokter = "Dokter " + resDipanggil.NAMADOKTER || "";
        const dokterAsli = "Dokter " + resDipanggil.NAMADOKTER || "";

        // → turunkan dulu
        let dokter = dokterAsli.toLowerCase();

        // → perbaiki kapitalisasi tiap kata (biar TTS baca lebih natural)
        dokter = dokter.replace(/\b\w/g, c => c.toUpperCase());

        const ruangan = resDipanggil.NAMARUANGAN || "ruangan pemeriksaan poliklinik";

        // ✅ ubah angka jadi penyebutan per karakter
        const nomorSpelled = spellNomorAntrian(nomor);

        const textToSpeak = `Nomor Antrian ${nomorSpelled}. Silakan masuk ke ${ruangan}. ${dokter}.`;

        // fungsi bicara dengan jeda
        const speakText = (text) => {
            return new Promise((resolve) => {
                const utter = new SpeechSynthesisUtterance(text);
                utter.lang = "id-ID";
                utter.rate = 0.9;
                utter.pitch = 1.0;
                utter.volume = 1.0;
                utter.onend = resolve;
                window.speechSynthesis.speak(utter);
            });
        };

        // Tunggu urutan audio selesai
        await inSound.play().catch(() => {});
        await new Promise(r => inSound.onended = r);
        await speakText(textToSpeak);
        await outSound.play().catch(() => {});
        await new Promise(r => outSound.onended = r);
    }

    function spellNomorAntrian(nomor) {
        const mapAngka = {
            '0': 'kosong',
            '1': 'satu',
            '2': 'dua',
            '3': 'tiga',
            '4': 'empat',
            '5': 'lima',
            '6': 'enam',
            '7': 'tujuh',
            '8': 'delapan',
            '9': 'sembilan'
        };

        let hasil = '';
        for (let char of nomor) {
            if (/[A-Za-z]/.test(char)) {
                hasil += `${char.toUpperCase()} `;
            } else if (/[0-9]/.test(char)) {
                hasil += `${mapAngka[char]} `; // sebut tiap angka
            } else if (char === '-' || char === ' ') {
                hasil += ' '; // jeda kecil
            }
        }
        return hasil.trim();
    }

    function pauseProgressBar() {
        progressPaused = true;
        $('#pause').prop('hidden',true).prop('disabled',false);
        $('#resume').prop('hidden',false).prop('disabled',false);
    }

    function resumeProgressBar() {
        progressPaused = false;
        $('#pause').prop('hidden',false).prop('disabled',false);
        $('#resume').prop('hidden',true).prop('disabled',false);
    }

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
            if (!progressPaused) {
                progress += step;
                if (progress > 100) progress = 100;
                progressBar.css("width", progress + "%");
            }

            if (progress >= 100) {
                clearInterval(progressInterval);
                refresh($('#pilih_poli1').val(),$('#pilih_dr1').val(),$('#pilih_poli2').val(),$('#pilih_dr2').val());
            }
        }, 100);
    }

    function stopProgressBar() {
        clearInterval(progressInterval);
        progressBar.css("width", "0%"); // reset
    }

    function startProgressBarDual() {
        clearInterval(progressInterval); // pastikan tidak ada interval lama

        iziToast.success({
            title: 'Antrian berhasil ditampilkan!',
            message: 'Antrian diperbarui setiap 5 detik.',
            position: 'topRight'
        });

        // Matikan animasi sementara
        progressBarDual.css({
            "transition": "none",
            "width": "0%"
        });

        // Force reflow supaya browser benar2 terapkan width 0%
        progressBarDual[0].offsetHeight;

        // Hidupkan lagi animasi
        progressBarDual.css("transition", "width 0.1s linear");

        let step = 100 / (refreshInterval / 100);
        let progress = 0;

        progressInterval = setInterval(() => {
            if (!progressPaused) {
                progress += step;
                if (progress > 100) progress = 100;
                progressBarDual.css("width", progress + "%");
            }

            if (progress >= 100) {
                clearInterval(progressInterval);
                refresh($('#pilih_poli1').val(),$('#pilih_dr1').val(),$('#pilih_poli2').val(),$('#pilih_dr2').val());
            }
        }, 100);
    }

    function stopProgressBarDual() {
        clearInterval(progressInterval);
        progressBarDual.css("width", "0%"); // reset
    }

    function refresh(poli1,dr1,poli2,dr2) {
        var md = $('#showMultipleDisplay').is(':checked');

        if (poli1 == '') {
            iziToast.error({
                title: 'Maaf!',
                message: 'Pilih Poliklinik terlebih dahulu.',
                position: 'topRight'
            });
            return;
        }
        if (dr1 == '') {
            iziToast.error({
                title: 'Maaf!',
                message: 'Pilih Dokter Spesialis terlebih dahulu.',
                position: 'topRight'
            });
            return;
        }

        if (md) { // Jika Multiple Display AKTIF!!
            if (poli2 == '') {
                iziToast.error({
                    title: 'Maaf!',
                    message: 'Pilih Poliklinik Display Kedua terlebih dahulu.',
                    position: 'topRight'
                });
                return;
            }
            if (dr2 == '') {
                iziToast.error({
                    title: 'Maaf!',
                    message: 'Pilih Dokter Spesialis Display Kedua terlebih dahulu.',
                    position: 'topRight'
                });
                return;
            }
        }

        $('#tampil_antrian').find('i').removeClass('fa-search').addClass('fa-sync fa-spin').prop('disabled',true);
        const tgl = moment().format('YYYY-MM-DD'); // TGL HARI INI

        var save = new FormData();
        save.append('tgl', tgl);
        save.append('poli1', poli1);
        save.append('dr1', dr1);
        save.append('poli2', poli2);
        save.append('dr2', dr2);
        // save.append('md', md);
        save.append('md', md ? "1" : "0");

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: `/api/display/antrian/poli`,
            method: 'post',
            data: save,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(res) {
                if (md) {
                    startDisplayMultiple(res);
                } else {
                    startDisplaySingle(res);
                }
                $('#openFullscreenBtn').prop('disabled',false);
            },
            error: function(xhr, status, error) {
                iziToast.error({
                    title: 'Maaf, Ambil Antrian Gagal!',
                    message: error,
                    position: 'topRight'
                });
                console.error("Gagal load antrean:", error);
                $('#menunggu').html('');
                $('#menunggu-m1').html('');
                $('#menunggu-m2').html('');
                $('#dipanggil').html('');
                $('#dipanggil-m1').html('');
                $('#dipanggil-m2').html('');
                $('#selesai').html('');
                stopProgressBar();
                stopProgressBarDual();
                // coba ulang setelah 5 detik
                // setTimeout(refresh, 3000);
                $('#tampil_antrian').find('i').removeClass('fa-sync fa-spin').addClass('fa-search').prop('disabled',false);
            }
        });
    }

    function startDisplaySingle(res) {
        $('#myDivDual').prop('hidden',true);

        $('#poli').html(`
            <h1 class="mb-0" style="font-size:80px">Antrean <b class="text-danger fw-bold">${res.poli1.NAMARUANGAN}</b></h1>
            <h1 class="mb-0" style="font-size:50px">Dokter : <b class="text-primary fw-bold">${res.dokter1.NAMADOKTER}</b></h1>
        `);

        // render data
        let rows_menunggu = "";
        let rows_selesai = "";

        if (res.menunggu1.length === 0) {
            rows_menunggu = ` `;
        } else {
            $.each(res.menunggu1, function(index, item) {
                rows_menunggu += `
                    <div class="card custom-card mb-3 shadow">
                        <div class="card-body card-bg-light d-flex align-items-center gap-3">
                            <div class="me-3 border border-primary rounded d-flex justify-content-center align-items-center"
                                style="height: auto; width: auto; white-space: nowrap; flex-shrink: 0;">
                                <span class="fw-bold p-2" style="font-size: 50px">
                                    ${item.POS ? item.POS.toString() : 'A2'}-${item.NOMORANTREAN.toString().padStart(3, '0')}
                                </span>
                            </div>
                            <div class="flex-grow-1" style="min-width: 0;">
                                <div class="fs-2 fw-medium text-truncate">
                                    ${identityEnabled ? item.NAMAPASIEN : 'Menunggu Dipanggil'}
                                </div>
                                <p class="mb-0 text-muted fs-3">RM. ${item.NORM.toString().padStart(8, '0')}</p>
                            </div>
                        </div>
                    </div>
                `;
            });
        }
        $("#menunggu").empty().html(rows_menunggu);

        if (res.dipanggil1 && res.dipanggil1.NOMORANTREAN) {
            lastDipanggil = res.dipanggil1; // simpan seluruh objek
            $('#dipanggil').empty().append(`
                <div class="mb-3">
                    <h2 class="fw-bold" style="font-size: 60px">NOMOR ANTRIAN</h2>
                    <h1 class="fw-bold text-danger" style="font-size: 170px">${res.dipanggil1.POS?res.dipanggil1.POS.toString()+'-':''}${res.dipanggil1.NOMORANTREAN.toString().padStart(3, '0')}</h1>
                </div>
                <div class="mb-3">
                    <div class="fw-bold mb-3" style="font-size:55px"><u>${res.dipanggil1.NAMARUANGAN}</u></div>
                    <div class="fw-bold mb-3 text-truncate" style="font-size:45px" id="namaShowDipanggil" hidden>${res.dipanggil1.NAMAPASIEN}</div>
                    <div class="fw-bold mb-3" style="font-size:40px">RM. ${res.dipanggil1.NORM.toString().padStart(8, '0')}</div>
                </div>
            `);

            if (identityEnabled) {
                $('#namaShowDipanggil').prop('hidden',false);
            } else {
                $('#namaShowDipanggil').prop('hidden',true);
            }

            pauseProgressBar();
            $('#pause').prop('disabled',true);
            $('#resume').prop('disabled',true);

            playSound(res.dipanggil1).then(() => {
                resumeProgressBar(); // ▶️ lanjutkan progress setelah suara selesai
                $('#pause').prop('disabled',false);
                $('#resume').prop('disabled',false);
            });

            // Change STATUS from 1 into 2
            const save = new FormData();
            save.append('ID', res.dipanggil1.ANTRIAN_ID);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('api.display.antrian.update') }}",
                method: 'POST',
                data: save,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    console.log(`Antrian ID#${res.dipanggil1.ANTRIAN_ID} Dipanggil Diupdate ke STATUS = 2. Total Antrian Terupdate = ${response}`);
                },
                error: function(xhr, status, error) {
                    console.log(`Gagal Mengupdate Antrian ID#${res.dipanggil1.ANTRIAN_ID} Dipanggil ke STATUS = 2.`);
                }
            })
        } else if (lastDipanggil) {
            // res.dipanggil kosong → tampilkan nomor terakhir
            $('#dipanggil').empty().append(`
                <div class="mb-3">
                    <h2 class="fw-bold" style="font-size:60px">NOMOR ANTRIAN</h2>
                    <h1 class="fw-bold text-danger" style="font-size:170px">${lastDipanggil.POS?lastDipanggil.POS+'-':''}${lastDipanggil.NOMORANTREAN.toString().padStart(3,'0')}</h1>
                </div>
                <div class="mb-3">
                    <div class="fw-bold mb-3" style="font-size:55px"><u>${lastDipanggil.NAMARUANGAN}</u></div>
                    <div class="fw-bold mb-3 text-truncate" style="font-size:45px" id="namaShowDipanggil" hidden>${identityEnabled?lastDipanggil.NAMAPASIEN:''}</div>
                    <div class="fw-bold mb-3" style="font-size:40px">RM. ${lastDipanggil.NORM.toString().padStart(8,'0')}</div>
                </div>
            `);

            if (identityEnabled) {
                $('#namaShowDipanggil').prop('hidden',false);
            } else {
                $('#namaShowDipanggil').prop('hidden',true);
            }
        } else {
            $('#dipanggil').empty();
        }

        let selesaiFiltered = '';
        if (res.selesai1.length > 0) {
            selesaiFiltered = res.selesai1;

            // jika ada lastDipanggil dan saat ini dipanggil kosong → exclude dari rows_selesai
            if (lastDipanggil && (!res.dipanggil || res.dipanggil1.NOMORANTREAN === null)) {
                selesaiFiltered = res.selesai1.filter(item => item.ID !== lastDipanggil.ANTRIAN_ID);
            }

            $.each(selesaiFiltered, function(index, item) {
                rows_selesai += `
                    <div class="card custom-card mb-3 shadow">
                        <div class="card-body card-bg-light d-flex align-items-center gap-3">
                            <div class="me-3 border border-success rounded d-flex justify-content-center align-items-center" style="height: auto; width: auto; white-space: nowrap; flex-shrink: 0;">
                                <span class="fw-bold p-2" style="font-size: 50px">${item.POS.toString()}-${item.NOMORANTREAN.toString().padStart(3, '0')}</span>
                            </div>
                            <div class="flex-grow-1" style="min-width: 0;">
                                <div class="fs-2 fw-medium text-truncate ${item.STATUSANTREAN == 2?'text-indigo-900':''}">${identityEnabled?item.NAMAPASIEN:'Sudah Dipanggil'}</div>
                                <p class="mb-0 text-muted fs-3">RM. ${item.NORM.toString().padStart(8, '0')}</p>
                            </div>
                        </div>
                    </div>
                `;
            });
        }

        $("#selesai").empty().html(rows_selesai);

        // console.log("lastDipanggil:", lastDipanggil);
        // console.log("res.selesai:", res.selesai);
        // console.log("selesaiFiltered:", selesaiFiltered);

        $('#myDiv').prop('hidden',false);

        // kalau sukses -> jalankan progress bar lagi
        startProgressBar();

        // kembalikan posisi scroll sebelumnya
        $('#menunggu').scrollTop(menungguScrollTop);
        $('#selesai').scrollTop(selesaiScrollTop);

        // Jalankan auto scroll untuk menunggu dan selesai
        // startAutoScroll('#menunggu', 50);
        // startAutoScroll('#selesai', 50);

        $('#tampil_antrian').find('i').removeClass('fa-sync fa-spin').addClass('fa-search').prop('disabled',false);
    }

    function startDisplayMultiple(res) {
        $('#myDiv').prop('hidden',true);

        // DISPLAY GRID KIRI
            $('#poli-m1').html(`
                <h1 class="mb-0 text-wrap" style="font-size:30px">Antrean <b class="text-danger fw-bold">${res.poli1.NAMARUANGAN}</b></h1>
                <h1 class="mb-0 text-truncate" style="font-size:20px">Dokter : <b class="text-primary fw-bold">${res.dokter1.NAMADOKTER}</b></h1>
            `);

            // render data
            let rows1_menunggu = "";

            if (res.menunggu1.length === 0) {
                rows1_menunggu = ` `;
            } else {
                $.each(res.menunggu1, function(index, item) {
                    rows1_menunggu += `
                        <div class="card custom-card mb-3 shadow">
                            <div class="card-body card-bg-light d-flex align-items-center gap-3 p-2">
                                <div class="me-3 border border-primary rounded d-flex justify-content-center align-items-center"
                                    style="height: auto; width: auto; white-space: nowrap; flex-shrink: 0;">
                                    <span class="fw-bold p-2" style="font-size: 30px">
                                        ${item.POS ? item.POS.toString() : 'A2'}-${item.NOMORANTREAN.toString().padStart(3, '0')}
                                    </span>
                                </div>
                                <div class="flex-grow-1" style="min-width: 0;">
                                    <div class="fs-4 fw-medium text-truncate">
                                        ${identityEnabled1 ? item.NAMAPASIEN : 'Menunggu Dipanggil'}
                                    </div>
                                    <p class="mb-0 text-muted fs-5">RM. ${item.NORM.toString().padStart(8, '0')}</p>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }
            $("#menunggu-m1").empty().html(rows1_menunggu);

            if (res.dipanggil1 && res.dipanggil1.NOMORANTREAN) {
                lastDipanggil1 = res.dipanggil1; // simpan seluruh objek
                $('#dipanggil-m1').empty().append(`
                    <div class="mb-0">
                        <h2 class="fw-bold mb-0" style="font-size: 20px">NOMOR ANTRIAN</h2>
                        <h1 class="fw-bold text-danger mb-0" style="font-size: 70px">${res.dipanggil1.POS?res.dipanggil1.POS.toString()+'-':''}${res.dipanggil1.NOMORANTREAN.toString().padStart(3, '0')}</h1>
                    </div>
                    <div class="mb-3">
                        <div class="fw-bold mb-1" style="font-size:35px" hidden><u>${res.dipanggil1.NAMARUANGAN}</u></div>
                        <div class="fw-bold mb-0 text-truncate" style="font-size:15px" id="namaShowDipanggil-m1" hidden>${res.dipanggil1.NAMAPASIEN}</div>
                        <div class="fw-bold mb-3" style="font-size:15px">RM. ${res.dipanggil1.NORM.toString().padStart(8, '0')}</div>
                    </div>
                `);

                if (identityEnabled1) {
                    $('#namaShowDipanggil-m1').prop('hidden',false);
                } else {
                    $('#namaShowDipanggil-m1').prop('hidden',true);
                }

                soundQueue = soundQueue
                .then(() => {
                    // sebelum memainkan suara, pause progress dan disable tombol
                    pauseProgressBar();
                    $('#pause').prop('disabled',true);
                    $('#resume').prop('disabled',true);

                    soundLock = true;
                    return playSound(res.dipanggil1);
                })
                .then(() => {
                    soundLock = false;
                    // setelah suara selesai -> resume progress & enable tombol
                    resumeProgressBar();
                    $('#pause').prop('disabled',false);
                    $('#resume').prop('disabled',false);
                });

                // playSound(res.dipanggil1).then(() => {
                //     resumeProgressBar(); // ▶️ lanjutkan progress setelah suara selesai
                //     $('#pause').prop('disabled',false);
                //     $('#resume').prop('disabled',false);
                // });

                // Change STATUS from 1 into 2
                const save1 = new FormData();
                save1.append('ID', res.dipanggil1.ANTRIAN_ID);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('api.display.antrian.update') }}",
                    method: 'POST',
                    data: save1,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        console.log(`Antrian ID#${res.dipanggil1.ANTRIAN_ID} Dipanggil Diupdate ke STATUS = 2. Total Antrian Terupdate = ${response}`);
                    },
                    error: function(xhr, status, error) {
                        console.log(`Gagal Mengupdate Antrian ID#${res.dipanggil1.ANTRIAN_ID} Dipanggil ke STATUS = 2.`);
                    }
                })
            } else if (lastDipanggil1) {
                // res.dipanggil kosong → tampilkan nomor terakhir
                $('#dipanggil-m1').empty().append(`
                    <div class="mb-0">
                        <h2 class="fw-bold mb-0" style="font-size:20px">NOMOR ANTRIAN</h2>
                        <h1 class="fw-bold text-danger mb-0" style="font-size:70px">${lastDipanggil1.POS?lastDipanggil1.POS+'-':''}${lastDipanggil1.NOMORANTREAN.toString().padStart(3,'0')}</h1>
                    </div>
                    <div class="mb-3">
                        <div class="fw-bold mb-1" style="font-size:35px" hidden><u>${lastDipanggil1.NAMARUANGAN}</u></div>
                        <div class="fw-bold mb-0 text-truncate" style="font-size:15px" id="namaShowDipanggil-m1" hidden>${identityEnabled1?lastDipanggil1.NAMAPASIEN:''}</div>
                        <div class="fw-bold mb-3" style="font-size:15px">RM. ${lastDipanggil1.NORM.toString().padStart(8,'0')}</div>
                    </div>
                `);

                if (identityEnabled1) {
                    $('#namaShowDipanggil-m1').prop('hidden',false);
                } else {
                    $('#namaShowDipanggil-m1').prop('hidden',true);
                }
            } else {
                $('#dipanggil-m1').empty();
            }

            $('#menunggu-m1').scrollTop(menungguScrollTop);

        // DISPLAY GRID KANAN
            $('#poli-m2').html(`
                <h1 class="mb-0 text-wrap" style="font-size:30px">Antrean <b class="text-danger fw-bold">${res.poli2.NAMARUANGAN}</b></h1>
                <h1 class="mb-0 text-truncate" style="font-size:20px">Dokter : <b class="text-primary fw-bold">${res.dokter2.NAMADOKTER}</b></h1>
            `);

            // render data
            let rows2_menunggu = "";

            if (res.menunggu2.length === 0) {
                rows2_menunggu = ` `;
            } else {
                $.each(res.menunggu2, function(index, item) {
                    rows2_menunggu += `
                        <div class="card custom-card mb-3 shadow">
                            <div class="card-body card-bg-light d-flex align-items-center gap-3 p-2">
                                <div class="me-3 border border-primary rounded d-flex justify-content-center align-items-center"
                                    style="height: auto; width: auto; white-space: nowrap; flex-shrink: 0;">
                                    <span class="fw-bold p-2" style="font-size: 30px">
                                        ${item.POS ? item.POS.toString() : 'A2'}-${item.NOMORANTREAN.toString().padStart(3, '0')}
                                    </span>
                                </div>
                                <div class="flex-grow-1" style="min-width: 0;">
                                    <div class="fs-4 fw-medium text-truncate">
                                        ${identityEnabled2 ? item.NAMAPASIEN : 'Menunggu Dipanggil'}
                                    </div>
                                    <p class="mb-0 text-muted fs-5">RM. ${item.NORM.toString().padStart(8, '0')}</p>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }
            $("#menunggu-m2").empty().html(rows2_menunggu);

            if (res.dipanggil2 && res.dipanggil2.NOMORANTREAN) {
                lastDipanggil2 = res.dipanggil2; // simpan seluruh objek
                $('#dipanggil-m2').empty().append(`
                    <div class="mb-0">
                        <h2 class="fw-bold mb-0" style="font-size: 20px">NOMOR ANTRIAN</h2>
                        <h1 class="fw-bold text-danger mb-0" style="font-size: 70px">${res.dipanggil2.POS?res.dipanggil2.POS.toString()+'-':''}${res.dipanggil2.NOMORANTREAN.toString().padStart(3, '0')}</h1>
                    </div>
                    <div class="mb-3">
                        <div class="fw-bold mb-1" style="font-size:35px" hidden><u>${res.dipanggil2.NAMARUANGAN}</u></div>
                        <div class="fw-bold mb-0 text-truncate" style="font-size:15px" id="namaShowDipanggil-m2" hidden>${res.dipanggil2.NAMAPASIEN}</div>
                        <div class="fw-bold mb-3" style="font-size:15px">RM. ${res.dipanggil2.NORM.toString().padStart(8, '0')}</div>
                    </div>
                `);

                if (identityEnabled2) {
                    $('#namaShowDipanggil-m2').prop('hidden',false);
                } else {
                    $('#namaShowDipanggil-m2').prop('hidden',true);
                }

                soundQueue = soundQueue
                .then(() => {
                    // sebelum memainkan suara, pause progress dan disable tombol
                    pauseProgressBar();
                    $('#pause').prop('disabled',true);
                    $('#resume').prop('disabled',true);

                    soundLock = true;
                    return playSound(res.dipanggil2);
                })
                .then(() => {
                    soundLock = false;
                    // setelah suara selesai -> resume progress & enable tombol
                    resumeProgressBar();
                    $('#pause').prop('disabled',false);
                    $('#resume').prop('disabled',false);
                });

                // playSound(res.dipanggil2).then(() => {
                //     resumeProgressBar(); // ▶️ lanjutkan progress setelah suara selesai
                //     $('#pause').prop('disabled',false);
                //     $('#resume').prop('disabled',false);
                // });

                // Change STATUS from 1 into 2
                const save2 = new FormData();
                save2.append('ID', res.dipanggil2.ANTRIAN_ID);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('api.display.antrian.update') }}",
                    method: 'POST',
                    data: save2,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        console.log(`Antrian ID#${res.dipanggil2.ANTRIAN_ID} Dipanggil Diupdate ke STATUS = 2. Total Antrian Terupdate = ${response}`);
                    },
                    error: function(xhr, status, error) {
                        console.log(`Gagal Mengupdate Antrian ID#${res.dipanggil2.ANTRIAN_ID} Dipanggil ke STATUS = 2.`);
                    }
                })
            } else if (lastDipanggil2) {
                // res.dipanggil kosong → tampilkan nomor terakhir
                $('#dipanggil-m2').empty().append(`
                    <div class="mb-0">
                        <h2 class="fw-bold mb-0" style="font-size:20px">NOMOR ANTRIAN</h2>
                        <h1 class="fw-bold text-danger mb-0" style="font-size:70px">${lastDipanggil2.POS?lastDipanggil2.POS+'-':''}${lastDipanggil2.NOMORANTREAN.toString().padStart(3,'0')}</h1>
                    </div>
                    <div class="mb-3">
                        <div class="fw-bold mb-1" style="font-size:35px" hidden><u>${lastDipanggil2.NAMARUANGAN}</u></div>
                        <div class="fw-bold mb-0 text-truncate" style="font-size:15px" id="namaShowDipanggil-m2" hidden>${identityEnabled2?lastDipanggil2.NAMAPASIEN:''}</div>
                        <div class="fw-bold mb-3" style="font-size:15px">RM. ${lastDipanggil2.NORM.toString().padStart(8,'0')}</div>
                    </div>
                `);

                if (identityEnabled2) {
                    $('#namaShowDipanggil-m2').prop('hidden',false);
                } else {
                    $('#namaShowDipanggil-m2').prop('hidden',true);
                }
            } else {
                $('#dipanggil-m2').empty();
            }

            $('#menunggu-m2').scrollTop(menungguScrollTop);

        // FRESH
        $('#myDivDual').prop('hidden',false);
        startProgressBarDual();
        $('#tampil_antrian').find('i').removeClass('fa-sync fa-spin').addClass('fa-search').prop('disabled',false);
    }

    function startAutoScroll(containerSelector, speed = 1) {
        const $container = $(containerSelector);
        if (!$container.length) return;

        let scrollTop = 0;

        function scrollStep() {
            const scrollHeight = $container[0].scrollHeight;
            scrollTop += speed; // speed = px per frame
            if (scrollTop >= scrollHeight - $container.height()) {
                scrollTop = 0;
            }
            $container.scrollTop(scrollTop);
            requestAnimationFrame(scrollStep);
        }

        scrollStep();
    }

    // function startAutoScroll(containerSelector, speed = 50) {
    //     const $container = $(containerSelector);
    //     if (!$container.length) return;

    //     let scrollHeight = $container[0].scrollHeight; // total scroll
    //     let scrollTop = 0;
    //     let direction = 0.5; // scroll ke bawah

    //     function scrollStep() {
    //         scrollTop += direction;
    //         const scrollHeight = $container[0].scrollHeight; // ambil baru tiap frame
    //         if (scrollTop >= scrollHeight - $container.height()) {
    //             scrollTop = 0;
    //         }
    //         $container.scrollTop(scrollTop);
    //         requestAnimationFrame(scrollStep);
    //     }

    //     scrollStep();
    // }

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

        if ($('#showMultipleDisplay').is(':checked')) {
            $('#antrian-tgl-m').text(waktuFormatTgl);
            $('#antrian-detik-m').text(waktuFormatDetik);
        } else {
            $('#antrian-tgl').text(waktuFormatTgl);
            $('#antrian-detik').text(waktuFormatDetik);
        }
    }
</script>
@endsection
