@extends('layouts.index')

@section('content')
<div class="container-fluid p-3">
    <div id="display-gate" class="display-gate">
        <div class="gate-card">
            <h2 class="mb-3">Layar Antrian Farmasi</h2>
            <p class="text-muted mb-4">
                Klik untuk menampilkan informasi antrian
            </p>
            <button class="btn btn-dark btn-lg px-5" onclick="startDisplay()">
                Tampilkan Layar
            </button>
        </div>
    </div>

    <div class="container-fluid p-3 position-relative">
        <button class="btn btn-dark btn-sm position-absolute top-0 end-0 m-2"
            onclick="toggleFullscreen()">
            ⛶ Full Screen
        </button>
    </div>

    {{-- AREA DISPLAY (INI YANG DI FULLSCREEN) --}}
    <div id="display-area">

        {{-- HEADER + FULLSCREEN --}}
        <div class="card shadow-sm mb-3 mt-4">
            <div class="d-flex justify-content-between align-items-center p-3">
                <h5 class="mb-0" id="clock"></h5>
            </div>
        </div>

        <div class="row">
            {{-- ANTRIAN AKTIF --}}
            <div class="col-md-8">
                <div class="card shadow-lg text-center p-4 h-100 d-flex flex-column">
                    <h2 class="fw-bold mb-3">Antrian Farmasi</h2>

                    <div class="current-number" id="nomor">---</div>
                    <div class="current-loket" id="loket">Menunggu</div>

                    {{-- RIWAYAT --}}
                    <div class="row mt-4" id="history"></div>
                </div>
            </div>

            {{-- PANEL SAMPING --}}
            <div class="col-md-4">
                <div class="card shadow-lg p-3 h-100 d-flex flex-column">
                    {{-- SLIDER POSTER --}}
                    <div class="poster-wrapper flex-grow-1">
                        <img id="poster" src="{{ asset('poster/informasi_farmasi1.jpg') }}" alt="Informasi Farmasi">
                    </div>
                </div>
            </div>
            {{-- RUNNING TEXT --}}
            <div class="running-text mt-3">
                <div class="running-inner">
                    • Mohon menunggu hingga nomor Anda dipanggil •
                </div>
            </div>
        </div>
    </div>
</div>

<style>
#display-area:fullscreen {
    background: #f5f7fb;
    padding: 30px;
}

#display-area:-webkit-full-screen {
    background: #f5f7fb;
}

.history-card {
    background: linear-gradient(135deg, #f8fafc, #eef2f7);
    border-radius: 18px;
    box-shadow: 0 8px 18px rgba(0,0,0,.08);
}

.history-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #111827;
}

.history-loket {
    font-size: 1.1rem;
    color: #4b5563;
}

.display-gate {
    position: fixed;
    inset: 0;
    background: linear-gradient(135deg, #f5f7fb, #e9ecf3);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}

.gate-card {
    background: white;
    padding: 40px 50px 25px;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,.15);
    text-align: center;
    max-width: 420px;
}
.current-number {
    font-size: 6rem;
    font-weight: 800;
    letter-spacing: 4px;
    color: #1f2937;
}

.current-number {
    animation: pop 0.4s ease;
}

@keyframes pop {
    0% { transform: scale(0.95); opacity: 0.6; }
    100% { transform: scale(1); opacity: 1; }
}

.current-loket {
    font-size: 1.8rem;
    font-weight: 500;
    color: #374151;
}

.card.shadow-lg.text-center.p-3 {
    border-radius: 24px;
    padding-bottom: 12px !important;
}

#history {
    margin-top: 30px;
}

/* ===== POSTER ===== */
.poster-wrapper {
    overflow: hidden;
    border-radius: 16px;
}

.poster-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: opacity .6s ease;
}

/* ===== RUNNING TEXT ===== */
.running-text {
    background: #111827;
    color: #fff;
    padding: 10px 0;
    border-radius: 12px;
    overflow: hidden;
    position: relative;
}

.running-inner {
    white-space: nowrap;
    display: inline-block;
    padding-left: 100%;
    animation: marquee 15s linear infinite;
    font-size: 1.1rem;
    font-weight: 500;
}

@keyframes marquee {
    0% { transform: translateX(0); }
    100% { transform: translateX(-100%); }
}

</style>

<script>
    let audioEnabled = false;
    let interval = null;
    let isCalling = false;

    /* ================= StartDisplay ================= */
    function startDisplay() {
        const area = document.getElementById('display-area');

        /* FULLSCREEN */
        if (!document.fullscreenElement) {
            area.requestFullscreen().catch(() => {});
        }

        /* AUDIO TRIGGER (silent) */
        const unlock = new SpeechSynthesisUtterance(' ');
        unlock.lang = 'id-ID';
        unlock.volume = 0;
        unlock.onend = () => {
            audioEnabled = true;
            document.getElementById('display-gate').remove();
            startPolling();
        };

        speechSynthesis.speak(unlock);
    }

    /* ===== SLIDER POSTER ===== */
    const posters = [
        '{{ asset("poster/informasi_farmasi1.jpg") }}',
        '{{ asset("poster/informasi_farmasi2.jpg") }}'
    ];

    let posterIndex = 0;
    const posterEl = document.getElementById('poster');

    setInterval(() => {
        posterEl.style.opacity = 0;

        setTimeout(() => {
            posterIndex = (posterIndex + 1) % posters.length;
            posterEl.src = posters[posterIndex];
            posterEl.style.opacity = 1;
        }, 500);

    }, 8000); // ganti poster tiap 8 detik

    /* ================= FULLSCREEN ================= */
    function toggleFullscreen() {
        const el = document.getElementById('display-area');

        if (!document.fullscreenElement) {
            el.requestFullscreen();
        } else {
            document.exitFullscreen();
        }
    }

    /* ================= POLLING ================= */
    function startPolling() {
        interval = setInterval(fetchData, 3000);
    }

    function stopPolling() {
        clearInterval(interval);
    }

    function fetchData() {
        if (isCalling) return;

        fetch('{{ url("api/display/antrian/farmasi/display/data") }}')
            .then(r => r.json())
            .then(res => {
                if (!res.current) return;

                isCalling = true;
                stopPolling();

                let nomor =
                    res.current.prefix + '-' +
                    String(res.current.nomor_antrian).padStart(3, '0');

                document.getElementById('nomor').innerText = nomor;
                document.getElementById('loket').innerText =
                    'Loket ' + res.current.nama_loket;

                renderHistory(res.history);

                playAudio(nomor, res.current.nama_loket, () => {
                    fetch('{{ url("api/display/antrian/farmasi/display") }}/' + res.current.log_id + '/tampil', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    }).then(() => {
                        isCalling = false;
                        startPolling();
                    });
                });
            });
    }

    /* ================= HISTORY ================= */
    function renderHistory(history) {
        let html = '';
        history.forEach(h => {
            let nomor =
                h.prefix + '-' +
                String(h.nomor_antrian).padStart(3, '0');

            html += `
            <div class="col-md-6 mb-2">
                <div class="history-card p-4 text-center">
                    <h4 class="fw-bold mb-3">Sudah Dipanggil</h4>
                    <div class="history-number">${nomor}</div>
                    <div class="history-loket">Loket ${h.nama_loket}</div>
                </div>
            </div>`;
        });

        document.getElementById('history').innerHTML = html;
    }

    /* ================= AUDIO ================= */
    function playAudio(nomor, loket, callback) {
        if (!audioEnabled) {
            callback();
            return;
        }

        let msg = new SpeechSynthesisUtterance(
            'Nomor antrian ' + nomor + ', silakan ke ' + loket
        );
        msg.lang = 'id-ID';
        msg.rate = 0.9;
        msg.onend = callback;
        speechSynthesis.speak(msg);
    }

    /* ================= CLOCK ================= */
    setInterval(() => {
        document.getElementById('clock').innerText =
            new Date().toLocaleString('id-ID', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            }) + ' WIB';
    }, 1000);

    // startPolling();
</script>
@endsection
