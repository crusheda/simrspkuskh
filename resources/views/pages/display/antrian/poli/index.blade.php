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
                        <select class="form-select" id="pilih_poli" disabled>
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
                        <div class="input-group-text">
                            <input class="form-check-input" type="checkbox" id="showNama" value="" aria-label="" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                            data-bs-placement="bottom" title="Centang untuk Memperlihatkan Nama Pasien Di Antrian">
                        </div>
                        <button class="btn btn-primary btn-wave me-1 waves-effect waves-light" id="tampil_antrian" onclick="refresh($('#pilih_poli').val(),$('#pilih_dr').val())" disabled>
                            <i class="fas fa-search me-1"></i> Terapkan
                        </button>
                    </div>
                </div>
                <div class="btn-group">
                    {{-- <button id="enableSound" class="btn btn-light-info">🔔 Aktifkan Suara Antrian</button> --}}
                    <button id="openFullscreenBtn" class="btn btn-success d-inline-flex align-items-center" data-bs-toggle="tooltip"
                        data-bs-placement="left" title="Terapkan Display Layar Penuh">
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
                        <div class="align-items-center w-100 text-center">
                            <div class="p-4">
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
</div>
<!-- [ Main Content ] end -->

<script>
    let refreshInterval = 5000; // 1 menit = 60000 ms, 5000 = 5 detik
    let progressBar = $("#refresh-progress");
    let progressInterval; // simpan interval supaya bisa dihentikan
    // let lastNomorDipanggil = null;
    let soundEnabled = false;
    let identityEnabled = false;
    let progressPaused = false;
    let menungguScrollTop = $('#menunggu').scrollTop();
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

        $('#showNama').on('change', function() {
            const showNama = $(this).is(':checked');

            if (showNama) {
                identityEnabled = true;
            } else {
                identityEnabled = false;
            }

            // $(this).prop('disabled',true);
            console.log('Identitas Diperlihatkan : '+identityEnabled);
        });

        // startProgressBar();
        $('#pilih_poli').prop('disabled',false);

        startAutoScroll('#menunggu', 0.3);
        startAutoScroll('#selesai', 0.3);
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
        const ruangan = resDipanggil.NAMARUANGAN || "ruangan pemeriksaan poliklinik";

        // ✅ ubah angka jadi penyebutan per karakter
        const nomorSpelled = spellNomorAntrian(nomor);

        const textToSpeak = `Nomor Antrian ${nomorSpelled}. Silakan masuk ke ${ruangan}.`;

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

            // progress += step;
            // if (progress > 100) progress = 100;

            // progressBar.css("width", progress + "%");

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

        $.ajax({
            url: `/api/display/antrian/poli/${tgl}/${poli}/${dr}`,
            type: "GET",
            dataType: "json",
            success: function(res) {
                $('#poli').html(`
                    <h1 class="mb-0" style="font-size:80px">Antrean <b class="text-danger fw-bold">${res.poli.NAMARUANGAN}</b></h1>
                    <h1 class="mb-0" style="font-size:50px">Dokter : <b class="text-primary fw-bold">${res.dokter.NAMADOKTER}</b></h1>
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

                if (res.dipanggil && res.dipanggil.NOMORANTREAN) {
                    $('#dipanggil').empty().append(`
                        <div class="mb-3">
                            <h2 class="fw-bold" style="font-size: 60px">NOMOR ANTRIAN</h2>
                            <h1 class="fw-bold text-danger" style="font-size: 170px">${res.dipanggil.POS?res.dipanggil.POS.toString()+'-':''}${res.dipanggil.NOMORANTREAN.toString().padStart(3, '0')}</h1>
                        </div>
                        <div class="mb-3">
                            <div class="fw-bold mb-3" style="font-size:55px"><u>${res.dipanggil.NAMARUANGAN}</u></div>
                            <div class="fw-bold mb-3 text-truncate" style="font-size:45px" id="namaShowDipanggil" hidden>${res.dipanggil.NAMAPASIEN}</div>
                            <div class="fw-bold mb-3" style="font-size:40px">RM. ${res.dipanggil.NORM.toString().padStart(8, '0')}</div>
                        </div>
                    `);

                    if (identityEnabled) {
                        $('#namaShowDipanggil').prop('hidden',false);
                    } else {
                        $('#namaShowDipanggil').prop('hidden',true);
                    }

                    // let nomorBaru = res.dipanggil.NOMORANTREAN;
                    // console.log(nomorBaru);
                    // console.log(lastNomorDipanggil);
                    // if (nomorBaru !== lastNomorDipanggil) { // lastNomorDipanggil !== null &&

                        // Panggil Nomor Antrian STATUS = 1
                        pauseProgressBar();
                        $('#pause').prop('disabled',true);
                        $('#resume').prop('disabled',true);
                        playSound(res.dipanggil).then(() => {
                            resumeProgressBar(); // ▶️ lanjutkan progress setelah suara selesai
                            $('#pause').prop('disabled',false);
                            $('#resume').prop('disabled',false);
                        });
                        // playSound(res.dipanggil);

                        // Change STATUS from 1 into 2
                        const save = new FormData();
                        save.append('ID', res.dipanggil.ANTRIAN_ID);
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
                                console.log(`Antrian ID#${res.dipanggil.ANTRIAN_ID} Dipanggil Diupdate ke STATUS = 2. Total Antrian Terupdate = ${response}`);
                            },
                            error: function(xhr, status, error) {
                                console.log(`Gagal Mengupdate Antrian ID#${res.dipanggil.ANTRIAN_ID} Dipanggil ke STATUS = 2.`);
                            }
                        })
                    // }
                    // console.log('update nomorBaru ke LastNomorDipanggil');
                    // lastNomorDipanggil = nomorBaru;
                } else {
                    $('#dipanggil').empty();
                }

                if (res.selesai.length === 0) {
                    rows_selesai = ` `;
                } else {
                    $.each(res.selesai, function(index, item) {
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

        $('#antrian-tgl').text(waktuFormatTgl);
        $('#antrian-detik').text(waktuFormatDetik);
    }
</script>
@endsection
