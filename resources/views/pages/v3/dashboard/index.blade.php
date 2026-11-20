@section('title', 'Dashboard')
@section('page-sub-title', 'SISTEM INFORMASI RUMAH SAKIT')
@section('page-title', 'Dashboard Interaktif')

<section>
    <div>
        {{-- <div class="page-heading">
            <div><span class="page-eyebrow">RINGKASAN SISTEM</span>
                <h1 class="page-title">Dashboard</h1>
                <p class="page-description">Selamat datang kembali. Berikut ringkasan dan pintasan aplikasi SIRMED.</p>
            </div><span class="date-chip"><i class="bi bi-calendar3 me-1"></i>{{ now()->translatedFormat('d F Y') }}</span>
        </div> --}}
        <div class="welcome-banner mb-3">
            <h2>Halo, {{ DB::table('aplikasi.pengguna')
                            ->where('ID', Auth::id())
                            ->selectRaw('master.getNamaLengkapPegawai(NIP) AS nama')
                            ->value('nama') ?? Auth::user()->NAMA }} 👋</h2>
            <p>Semoga aktivitas hari ini berjalan lancar. Pilih modul yang Anda perlukan melalui menu di samping atau akses
                cepat berikut.</p><span class="welcome-chip"><i class="bi bi-shield-check"></i> Sesi Anda aktif dan
                terlindungi</span>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-12 col-xl-7">
                <div class="card-surface">
                    <div class="d-flex justify-content-between align-middle">
                        <h2 class="section-title">Informasi aplikasi</h2>
                        <span class="badge text-bg-info">Terintegrasi</span>
                    </div>
                    <h6 class="section-subtitle">Informasi lingkungan SIRMED yang sedang digunakan.</h6>
                    <div class="table-responsive">
                        <table class="table table-modern fs-14">
                            <tbody>
                                <tr>
                                    <td class="text-secondary">Aplikasi</td>
                                    <td class="fw-semibold">SIRMED (Sistem Informasi Rekam Medis) Versi 3</td>
                                    <td class="text-end"><span class="badge badge-soft-primary">Livewire</span></td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">Institusi</td>
                                    <td class="fw-semibold">RS PKU Muhammadiyah Sukoharjo</td>
                                    <td class="text-end"><span class="badge badge-soft-success">Aktif</span></td>
                                </tr>
                                <tr>
                                    <td class="text-secondary">Tanggal akses</td>
                                    <td class="fw-semibold">{{ now()->translatedFormat('l, d F Y') }}</td>
                                    <td class="text-end"><i class="bi bi-calendar-check"></i></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-5">
                <div class="card-surface">
                    <h2 class="section-title">Keamanan akun</h2>
                    <h6 class="section-subtitle mb-4">Pengingat singkat untuk pengguna.</h6>
                    <div class="d-flex gap-3 mb-3"><span class="quick-action-icon"><i class="bi bi-shield-lock"></i></span>
                        <div><strong class="d-block small">Jaga kerahasiaan akun</strong>
                            <p class="text-secondary small mb-0 mt-1">Jangan membagikan kredensial atau meninggalkan sesi
                                terbuka di komputer bersama.</p>
                        </div>
                    </div>
                    <div class="d-flex gap-3"><span class="quick-action-icon"><i class="bi bi-life-preserver"></i></span>
                        <div><strong class="d-block small">Laporkan kendala</strong>
                            <p class="text-secondary small mb-0 mt-1">Hubungi tim IT rumah sakit jika menemukan masalah pada
                                aplikasi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card-surface stat-card">
                    <div class="stat-icon"><i class="bi bi-grid-1x2"></i></div>
                    <div class="stat-label">Modul v3</div>
                    <div class="stat-value">04</div>
                    <div class="stat-foot"><span class="positive"><i class="bi bi-check-circle me-1"></i>Siap diakses</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card-surface stat-card">
                    <div class="stat-icon"><i class="bi bi-person-check"></i></div>
                    <div class="stat-label">Status akun</div>
                    <div class="stat-value fs-4">Aktif</div>
                    <div class="stat-foot">Login terautentikasi</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card-surface stat-card">
                    <div class="stat-icon"><i class="bi bi-calendar2-week"></i></div>
                    <div class="stat-label">Periode berjalan</div>
                    <div class="stat-value fs-4">{{ now()->translatedFormat('F Y') }}</div>
                    <div class="stat-foot">Periode sistem saat ini</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card-surface stat-card">
                    <div class="stat-icon"><i class="bi bi-heart-pulse"></i></div>
                    <div class="stat-label">Platform</div>
                    <div class="stat-value fs-4">Livewire</div>
                    <div class="stat-foot">SIRMED v3</div>
                </div>
            </div>
        </div> --}}
        {{-- <div class="card-surface mb-4">
            <div class="mb-3">
                <h2 class="section-title">Akses cepat</h2>
                <p class="section-subtitle">Buka modul yang sering digunakan.</p>
            </div>
            <div class="row g-3">
                <div class="col-12 col-md-6 col-xl-4"><a href="{{ route('v3.emr') }}" wire:navigate
                        class="quick-action"><span class="quick-action-icon"><i
                                class="bi bi-clipboard2-pulse"></i></span><span><strong>Medical Record</strong><small>Modul
                                rekam medis elektronik</small></span><i class="bi bi-arrow-up-right ms-auto"></i></a></div>
                <div class="col-12 col-md-6 col-xl-4"><a href="{{ route('v3.smartclaim') }}" wire:navigate
                        class="quick-action"><span class="quick-action-icon"><i
                                class="bi bi-file-earmark-medical"></i></span><span><strong>Smart
                                Claim</strong><small>Pengelolaan berkas klaim</small></span><i
                            class="bi bi-arrow-up-right ms-auto"></i></a></div>
            </div>
        </div> --}}

        {{-- =========================================================
            PENGKAJIAN CHAMPION
        ========================================================= --}}
        <div class="card-surface overflow-hidden">

            {{-- HEADER --}}
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                <div>
                    <span class="badge rounded-pill badge-soft-warning mb-2">
                        <i class="bi bi-trophy-fill me-1"></i>
                        CHAMPION
                    </span>

                    <h2 class="section-title mb-1">
                        Peringkat Pengisian Pengkajian Awal
                    </h2>

                    <h6 class="section-subtitle mb-0">
                        Konsistensi dan kontribusi pengkajian awal pasien.
                    </h6>
                </div>

                {{-- PERIOD --}}
                <div class="btn-group">
                    <button wire:click="$set('leaderboardPeriod', 'monthly')"
                        class="btn btn-sm {{ $leaderboardPeriod === 'monthly' ? 'btn-primary' : 'btn-outline-secondary' }}">
                        <i class="bi bi-calendar3 me-1"></i>
                        3 Bulan Terakhir
                    </button>

                    <button wire:click="$set('leaderboardPeriod', 'yearly')"
                        class="btn btn-sm {{ $leaderboardPeriod === 'yearly' ? 'btn-primary' : 'btn-outline-secondary' }}">
                        <i class="bi bi-calendar-range me-1"></i>
                        Tahun Ini
                    </button>
                </div>
            </div>

            {{-- ADMIN FILTER --}}
            @if ($leaderboardIsAdmin)
                <div class="row g-2 mb-4">
                    <div class="col-12 col-md-12 col-lg-12">
                        <label class="form-label small fw-semibold">
                            Leaderboard Role
                        </label>

                        <select wire:model.live="leaderboardRole" class="form-select">
                            <option value="all">✮ Semua Dokter & Perawat</option>
                            <option value="doctor">✮ Semua Dokter</option>
                            <option value="nurse">✮ Semua Perawat</option>

                            @foreach ($leaderboardRoleOptions as $role)
                                <option value="{{ $role->id }}">
                                    {{ $role->jabatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            {{-- MONTHLY CHAMPION --}}
            <div class="rounded-4 p-4 mb-4"
                style="background: linear-gradient(135deg, rgba(255,193,7,.16), rgba(255,193,7,.03)); border: 1px solid rgba(255,193,7,.25);">

                <div class="row align-items-center g-3">
                    <div class="col-12 col-md-auto text-center">
                        <div style="font-size: 4rem; line-height: 1;">
                            🏆
                        </div>
                    </div>

                    <div class="col">
                        <div class="small text-warning fw-bold mb-1">
                            MONTHLY CHAMPION
                        </div>

                        <h4 class="fw-bold mb-1">
                            {{ now()->translatedFormat('F Y') }}
                        </h4>

                        @if ($monthlyChampion)
                            <div class="fs-5 fw-bold">
                                {{ $monthlyChampion->nama }} - {{ $monthlyChampion->kategori }}</small>
                            </div>

                            <div class="small text-secondary mt-1">
                                {{ number_format($monthlyChampion->total_pengkajian) }}
                                pengkajian awal berhasil difinalisasi bulan ini
                            </div>
                        @else
                            <div class="fw-semibold">
                                Champion belum ditentukan
                            </div>

                            <div class="small text-secondary">
                                Jadilah pegawai pertama yang menyelesaikan pengkajian bulan ini!
                            </div>
                        @endif
                    </div>

                    @if ($monthlyChampion)
                        <div class="col-12 col-md-auto text-md-end">
                            <div class="badge rounded-pill badge-soft-warning px-3 py-2">
                                <i class="bi bi-stars me-1"></i>
                                CHAMPION
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- PERSONAL PERFORMANCE --}}
            <div class="row g-3 mb-4">

                {{-- PERSONAL RANK --}}
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card-surface h-100"
                        style="background: var(--bs-tertiary-bg);">

                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="small text-secondary fw-semibold">
                                PERINGKAT ANDA
                            </span>

                            <i class="bi bi-bar-chart-line fs-4 text-primary"></i>
                        </div>

                        @if ($myRanking['rank'])
                            <div class="display-4 fw-bold">
                                #{{ $myRanking['rank'] }}
                            </div>

                            <div class="text-secondary small mb-2">
                                dari {{ number_format($allRanking->count()) }} pegawai
                            </div>

                            <div class="mt-2">
                                <span class="badge rounded-pill badge-soft-primary">
                                    {{ number_format($myRanking['total']) }} Pengkajian
                                </span>
                            </div>
                        @else
                            <div class="display-5 fw-bold">—</div>

                            <div class="small text-secondary">
                                Belum ada pengkajian pada periode ini
                            </div>
                        @endif
                    </div>
                </div>

                {{-- STREAK --}}
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card-surface h-100"
                        style="background: linear-gradient(135deg, rgba(249,115,22,.10), rgba(249,115,22,.02));">

                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="small text-secondary fw-semibold">
                                CONSISTENCY STREAK
                            </span>

                            <i class="bi bi-fire fs-4 text-warning"></i>
                        </div>

                        <div class="display-4 fw-bold text-warning">
                            {{ $streakData['streak'] }}
                            <span class="fs-5">Hari</span>
                        </div>

                        <div class="small text-secondary mt-2">
                            @if ($streakData['streak'] > 0)
                                @if ($streakData['today'])
                                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                                    Anda sudah mengisi hari ini. Pertahankan!
                                @else
                                    🔥 Streak masih aktif, lanjutkan hari ini!
                                @endif
                            @else
                                Mulai streak Anda dengan finalisasi pengkajian hari ini.
                            @endif
                        </div>
                    </div>
                </div>

                {{-- PROGRESS --}}
                <div class="col-12 col-xl-4">
                    <div class="card-surface h-100"
                        style="background: var(--bs-tertiary-bg);">

                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="small text-secondary fw-semibold">
                                NEXT RANK
                            </span>

                            <i class="bi bi-rocket-takeoff fs-4 text-success"></i>
                        </div>

                        @if ($myRanking['rank'] === 1)
                            <div class="fs-4 fw-bold text-success">
                                🏆 Anda adalah Champion!
                            </div>

                            <div class="small text-secondary mt-2">
                                Pertahankan posisi terbaik Anda.
                            </div>

                        @elseif ($myRanking['next'])
                            <div class="fs-4 fw-bold">
                                #{{ $myRanking['rank'] - 1 }}
                            </div>

                            <div class="small text-secondary mb-3">
                                {{ $myRanking['needed'] }} pengkajian lagi untuk naik peringkat
                            </div>

                            <div class="d-flex justify-content-between small mb-1">
                                <span>Progress mengejar rank berikutnya</span>
                                <strong>{{ $myRanking['progress'] }}%</strong>
                            </div>

                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-success"
                                    role="progressbar"
                                    style="width: {{ $myRanking['progress'] }}%">
                                </div>
                            </div>
                        @else
                            <div class="fs-4 fw-bold">
                                Mulai perjalanan Anda 🚀
                            </div>

                            <div class="small text-secondary mt-2">
                                Finalisasi pengkajian untuk masuk ke leaderboard.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- LEADERBOARD --}}
            <div class="mb-3">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div>
                        <h5 class="fw-bold mb-1">
                            <i class="bi bi-trophy me-2 text-warning"></i>
                            Top 10 Pengkajian
                        </h5>

                        <div class="small text-secondary">
                            {{ $leaderboardPeriod === 'yearly'
                                ? 'Akumulasi tahun ' . now()->format('Y')
                                : 'Akumulasi 3 bulan terakhir' }}
                        </div>
                    </div>
                </div>
            </div>

            @if ($leaderboard->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 65px;">Rank</th>
                                <th>Nama Pegawai</th>
                                <th class="text-end">Pengkajian</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($leaderboard as $pegawai)
                                <tr @if ($pegawai->USER_CREATED == Auth::user()->getAuthIdentifier())
                                    style="background: rgba(var(--bs-primary-rgb), .06)"
                                @endif>

                                    <td>
                                        @if ($pegawai->rank === 1)
                                            <span class="fs-5">🥇</span>
                                        @elseif ($pegawai->rank === 2)
                                            <span class="fs-5">🥈</span>
                                        @elseif ($pegawai->rank === 3)
                                            <span class="fs-5">🥉</span>
                                        @else
                                            <span class="badge rounded-pill badge-soft-secondary">
                                                #{{ $pegawai->rank }}
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="fw-semibold">
                                            {{ $pegawai->nama }} @if (!empty($pegawai->kategori)) - <small class="">{{ $pegawai->kategori }}</small> @endif

                                            @if ($pegawai->USER_CREATED == Auth::user()->getAuthIdentifier())
                                                <span class="badge rounded-pill badge-soft-primary ms-1">
                                                    Anda
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="text-end">
                                        <span class="fw-bold">
                                            {{ number_format($pegawai->total_pengkajian) }}
                                        </span>

                                        <small class="text-secondary">Form</small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div style="font-size: 3rem;">🌱</div>

                    <h6 class="fw-bold mt-2">
                        Belum ada data leaderboard
                    </h6>

                    <p class="text-secondary small mb-0">
                        Jadilah yang pertama memulai capaian pada periode ini.
                    </p>
                </div>
            @endif

            {{-- MOTIVATION --}}
            <div class="rounded-3 p-3 mt-4"
                style="background: var(--bs-tertiary-bg);">

                <div class="d-flex gap-3 align-items-start">
                    <i class="bi bi-lightning-charge-fill text-warning fs-4"></i>

                    <div>
                        <strong>
                            Setiap pengkajian yang difinalisasi adalah kontribusi nyata untuk pelayanan pasien.
                        </strong>

                        <div class="small text-secondary mt-1">
                            Konsisten hari ini, lebih baik dari kemarin!
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
