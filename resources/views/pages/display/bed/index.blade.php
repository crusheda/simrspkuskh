@extends('layouts.index')

@section('title', 'Display Tempat Tidur')

@section('content')

<div class="card">
    <div class="card-body mb-0 d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Display Realtime - <b class="text-primary">Tempat Tidur Rawat Inap</b></h6>
        <div class="btn-group">
            <button id="btn-refresh" class="btn btn-warning btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                data-bs-placement="bottom" title="Refresh Data Display" disabled>
                <i class="fas fa-sync align-middle"></i>
            </button>
            <button id="openFullscreenBtn" class="btn btn-primary btn-wave waves-effect waves-light" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark"
                data-bs-placement="bottom" title="Terapkan Display Layar Penuh">
                <i class="ti ti-arrows-maximize align-middle me-1"></i> Full Screen Display
            </button>
        </div>
    </div>
</div>
<div class="container-fluid py-3 card" id="myDiv">
    <h3 class="text-center mt-3 mb-4">Display Tempat Tidur Rawat Inap</h3>

    <!-- Progress Bar -->
    <div class="progress mb-3 ms-3 me-3" style="height:7px; position: sticky; top:0; z-index:9999;">
        <div id="refreshProgress" class="progress-bar bg-brand-color-3" role="progressbar" style="width: 0%;"></div>
    </div>

    <div class="bed-summary-cards d-flex justify-content-center gap-4 mb-4">
        <div class="card-summary">
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"><path fill="#04A9F5" d="M22.102 11.147v1.731H1.904V6.672H0v12.414h1.904v-2.837h20.198v3.074H24v-8.178z"></path><path fill="#f3bede" d="M8.709 11.165v.001c0 .564-.457 1.022-1.022 1.022H3.793a1.022 1.022 0 0 1-1.022-1.022v-.002c0-.564.457-1.022 1.022-1.022h3.894c.564 0 1.022.457 1.022 1.022zm11.034-4.001h-2.37V4.8h-1.68v2.365h-2.365v1.68h2.364v2.365h1.68V8.845h2.37z"></path></svg>
            </div>
            <div class="number" id="totalBed">--</div>
            <div class="label">TOTAL BED</div>
        </div>
        <div class="card-summary">
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"><g fill="none"><path stroke="#04A9F5" stroke-linecap="round" stroke-linejoin="round" d="M21.5 18.5v-11a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v11m-3 0v-8h-7a1 1 0 0 0-1 1v7"></path><path stroke="#04A9F5" stroke-linecap="round" stroke-linejoin="round" d="M3.5 10.5h18v3h-19v-2a1 1 0 0 1 1-1"></path><path fill="#04A9F5" d="M10.5 12.5v-1a1 1 0 0 1 1-1h9a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1h-9a1 1 0 0 1-1-1"></path></g></svg>
            </div>
            <div class="number" id="totalKosong">--</div>
            <div class="label">TERSEDIA</div>
        </div>
        <div class="card-summary">
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"><path fill="#04A9F5" d="M19.2 9.5L16 7.7V4h1.5v2.8l2.4 1.4l-.7 1.3m3 2.2c.5.7.8 1.5.8 2.3v9h-2v-3H3v3H1V8h2v9h8v-6.4c-.6-1.1-1-2.3-1-3.6c0-3.9 3.1-7 7-7s7 3.1 7 7c0 1.8-.7 3.4-1.8 4.7M12 7c0 2.8 2.2 5 5 5s5-2.2 5-5s-2.2-5-5-5s-5 2.2-5 5m-5 9c1.7 0 3-1.3 3-3s-1.3-3-3-3s-3 1.3-3 3s1.3 3 3 3Z"></path></svg>
            </div>
            <div class="number" id="totalTerpesan">--</div>
            <div class="label">TERPESAN</div>
        </div>
        <div class="card-summary">
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 2048 1280"><path fill="#04A9F5" d="M256 768h1728q26 0 45 19t19 45v448h-256v-256H256v256H0V64q0-26 19-45T64 0h128q26 0 45 19t19 45v704zm576-320q0-106-75-181t-181-75t-181 75t-75 181t75 181t181 75t181-75t75-181zm1216 256v-64q0-159-112.5-271.5T1664 256H960q-26 0-45 19t-19 45v384h1152z"></path></svg>
            </div>
            <div class="number" id="totalTerisi">--</div>
            <div class="label">TERISI</div>
        </div>
    </div>

    <div class="p-3 scroll-wrapper">
        <div class="table-responsive scroll-container" id="scrollContainer" style="max-height: 700px; overflow-y: auto;">
            <table class="table table-bordered text-center align-middle mb-0 fixed-header-footer">

                <thead>
                    <tr>
                        <th>Bangsal</th>
                        <th>Kelas Ruangan</th>
                        <th>Kosong</th>
                        <th>Terisi</th>
                        <th>Total Bed</th>
                    </tr>
                </thead>

                <!-- tbody scrollable -->
                <tbody id="tampil-tbody">
                    <tr>
                        <td colspan="5">
                            <div class="text-center p-4">
                                <div class="spinner-border text-info" role="status">
                                    <span class="visually-hidden">Memuat Data...</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>

                {{-- <tfoot class="table-info fw-bold">
                    <tr>
                        <td colspan="2">TOTAL</td>
                        <td id="totalKosong" class="text-success"></td>
                        <td id="totalTerisi" class="text-danger"></td>
                        <td id="totalBed"></td>
                    </tr>
                </tfoot> --}}
            </table>
        </div>
    </div>

</div>

<style>
    /* Tabel full width */
    .fixed-header-footer {
        width: 100%;
        border-collapse: collapse;
    }

    .fixed-header-footer th,
    .fixed-header-footer td {
        min-width: 120px;
        text-align: center;
    }

    /* Scroll hanya di wrapper div, bukan tbody */
    .scroll-container {
        max-height: 600px; /* tinggi area scroll */
        overflow-y: auto;
    }

    /* Sticky header & footer */
    .fixed-header-footer thead th {
        position: sticky;
        top: 0;
        z-index: 2;
        background-color: #cfe2ff; /* sesuai table-primary */
    }

    .fixed-header-footer tfoot td {
        position: sticky;
        bottom: 0;
        z-index: 2;
        background-color: #cff4fc; /* sesuai table-info */
    }

    .bed-summary-cards {
        max-width: 900px;
        margin: 0 auto;
    }

    .card-summary {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 12px rgb(0 0 0 / 0.1);
        padding: 20px 30px;
        text-align: center;
        width: 150px;
        font-family: 'Arial', sans-serif;
        color: #333;
    }

    .card-summary .icon {
        font-size: 36px;
        margin-bottom: 12px;
        opacity: 0.3;
    }

    .card-summary .number {
        font-weight: 700;
        font-size: 26px;
        margin-bottom: 6px;
    }

    .card-summary .label {
        font-weight: 700;
        font-size: 14px;
        color: #666;
    }

    .fixed-header-footer thead th {
        font-size: 18px;           /* ukuran font lebih besar */
        font-weight: 700;          /* bold */
        color: #212529;            /* warna teks bootstrap dark blue */
        vertical-align: middle;    /* tengah vertikal */
        padding: 12px 10px;        /* padding sedikit lebih besar */
        text-align: center;
    }

    .fixed-header-footer td {
        font-size: 18px;           /* ukuran font lebih besar dari default */
        padding: 10px 8px;         /* padding nyaman */
        vertical-align: middle;    /* tengah vertikal */
        text-align: center;
        color: #212529;            /* warna teks default */
    }

</style>

<script>
    const refreshInterval = 10000; // 10 detik
    const progressBar = document.getElementById('refreshProgress');
    const container = document.getElementById('scrollContainer');
    $(document).ready(function() {
        refresh();
        requestAnimationFrame(startProgressBar);
        const elem = $("#myDiv")[0];

        $("#openFullscreenBtn").on("click", function() {
            if (elem.requestFullscreen) elem.requestFullscreen();
            else if (elem.mozRequestFullScreen) elem.mozRequestFullScreen();
            else if (elem.webkitRequestFullscreen) elem.webkitRequestFullscreen();
            else if (elem.msRequestFullscreen) elem.msRequestFullscreen();
        });

        let direction = 1;
        setInterval(() => {
            if (container.scrollHeight <= container.clientHeight) return; // skip jika konten tidak cukup scroll

            if (direction === 1) {
                container.scrollBy({ top: 200, behavior: 'smooth' });
                if (container.scrollTop + container.clientHeight >= container.scrollHeight) direction = -1;
            } else {
                container.scrollBy({ top: -200, behavior: 'smooth' });
                if (container.scrollTop <= 0) direction = 1;
            }
        }, 3000);
    });

    function refresh() {
        $('#btn-refresh').find('i').addClass('fa-spin');
        $.ajax({
            url: "/api/display/tt",
            type: "GET",
            dataType: "json",
            success: function(res) {
                let html = '';
                if (res && Array.isArray(res.bangsal)) {
                    res.bangsal.forEach(item => {
                        let first = true;
                        item.kelas.forEach(kelas => {
                            html += '<tr>';
                            if (first) {
                                html += `<td rowspan="${item.kelas.length}" class="fw-bold bg-light">${item.bangsal}</td>`;
                                first = false;
                            }
                            html += `
                                <td>${kelas.nama_kelas}</td>
                                <td class="text-success fw-bold">${kelas.kosong}</td>
                                <td class="text-danger fw-bold">${kelas.terisi}</td>
                                <td>${kelas.total_bed}</td>
                            </tr>`;
                        });
                    });

                    $("#tampil-tbody").html(html);
                    $("#totalKosong").text(res.total.kosong);
                    $("#totalTerpesan").text(res.total.tepesan);
                    $("#totalTerisi").text(res.total.terisi);
                    $("#totalBed").text(res.total.total_bed);
                } else {
                    $("#tampil-tbody").html('<tr><td colspan="5" class="text-center">Data tidak ditemukan</td></tr>');
                }
                $('#btn-refresh').find('i').removeClass('fa-spin');
            },
            error: function(xhr, status, error) {
                console.error("Gagal load data:", error);
                $("#tampil-tbody").html('<tr><td colspan="5" class="text-center text-danger">Gagal memuat data</td></tr>');
                $('#btn-refresh').find('i').removeClass('fa-spin');
            }
        });
    }

    function startProgressBar(timestamp) {
        if (!startProgressBar.start) startProgressBar.start = timestamp;

        const elapsed = timestamp - startProgressBar.start;
        const percent = Math.min((elapsed / refreshInterval) * 100, 100);
        progressBar.style.width = percent + '%';

        if (elapsed < refreshInterval) {
            requestAnimationFrame(startProgressBar);
        } else {
            // reset timestamp sebelum refresh agar progress bar langsung mulai dari 0
            startProgressBar.start = null;
            progressBar.style.width = '0%';

            // jalankan refresh data
            refresh();

            // mulai animasi progress baru dari 0
            requestAnimationFrame(startProgressBar);
        }
    }
</script>

@endsection
