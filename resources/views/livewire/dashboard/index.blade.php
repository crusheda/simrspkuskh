<section>
    <div class="page-heading">
        <div><span class="page-eyebrow">RINGKASAN SISTEM</span>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-description">Selamat datang kembali. Berikut ringkasan dan pintasan aplikasi SIRMED.</p>
        </div><span class="date-chip"><i class="bi bi-calendar3 me-1"></i>{{ now()->translatedFormat('d F Y') }}</span>
    </div>
    <div class="welcome-banner mb-4">
        <h2>Halo, {{ Auth::user()->NAMA ?? (Auth::user()->name ?? 'Pengguna') }} 👋</h2>
        <p>Semoga aktivitas hari ini berjalan lancar. Pilih modul yang Anda perlukan melalui menu di samping atau akses
            cepat berikut.</p><span class="welcome-chip"><i class="bi bi-shield-check"></i> Sesi Anda aktif dan
            terlindungi</span>
    </div>
    <div class="row g-3 mb-4">
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
    </div>
    <div class="card-surface mb-4">
        <div class="mb-3">
            <h2 class="section-title">Akses cepat</h2>
            <p class="section-subtitle">Buka modul yang sering digunakan.</p>
        </div>
        <div class="row g-3">
            <div class="col-12 col-md-6 col-xl-4"><a href="{{ route('v3.monitoring') }}" wire:navigate
                    class="quick-action"><span class="quick-action-icon"><i
                            class="bi bi-activity"></i></span><span><strong>Monitoring</strong><small>Pantau informasi
                            sistem</small></span><i class="bi bi-arrow-up-right ms-auto"></i></a></div>
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
    </div>
    <div class="row g-3">
        <div class="col-12 col-xl-7">
            <div class="card-surface">
                <h2 class="section-title">Informasi aplikasi</h2>
                <p class="section-subtitle">Informasi lingkungan SIRMED yang sedang digunakan.</p>
                <div class="table-responsive">
                    <table class="table table-modern">
                        <tbody>
                            <tr>
                                <td class="text-secondary">Aplikasi</td>
                                <td class="fw-semibold">SIRMED v3</td>
                                <td class="text-end"><span class="badge rounded-pill badge-soft-primary">Livewire</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-secondary">Institusi</td>
                                <td class="fw-semibold">RS PKU Muhammadiyah Sukoharjo</td>
                                <td class="text-end"><span class="badge rounded-pill badge-soft-success">Aktif</span>
                                </td>
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
                <p class="section-subtitle mb-4">Pengingat singkat untuk pengguna.</p>
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
</section>
