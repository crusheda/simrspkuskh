<header class="app-topbar is-home-mode" id="appTopbar">
    <div class="topbar-left">

        {{-- SIDEBAR TOGGLE --}}
        <button
            type="button"
            class="icon-button sidebar-toggle"
            id="sidebarToggle"
            aria-label="Buka navigasi"
        >
            <i class="bi bi-list"></i>
        </button>

        {{-- PATIENT CONTEXT --}}
        <div class="topbar-context">

            {{-- KOLOM KIRI: INFORMASI PASIEN --}}
            <div class="topbar-patient-identity">

                {{-- BARIS 1: ASURANSI • NOMOR ASURANSI --}}
                <div class="topbar-insurance-meta" id="patientInsuranceMetaTopbar">
                    <span id="patientInsuranceNameTopbar">@yield('patient-insurance-name', '')</span>
                    <span class="topbar-meta-divider" id="topbarDivider1">•</span>
                    <span id="patientInsuranceTopbar">@yield('patient-insurance', '')</span>
                </div>

                {{-- BARIS 2: RM • NAMA PASIEN (TANGGAL LAHIR) --}}
                <div class="topbar-patient-meta">
                    <span id="pageSubTitleTopbar" title="Nomor Rekam Medis">
                        @yield('page-sub-title', '')
                    </span>
                    <span class="topbar-meta-divider" id="topbarDivider3">•</span>
                    <span id="pageTitleTopbar" title="@yield('page-title', '')">
                        @yield('page-title', '')
                    </span>
                    <span id="patientBirthTopbar" class="topbar-patient-birth">
                        @yield('patient-birth', '')
                    </span>
                </div>

                {{-- BARIS 3: RUANGAN • DOKTER --}}
                <div class="topbar-patient-room-doctor">
                    <span id="patientRoomTopbar">@yield('patient-room', '')</span>
                    <span class="topbar-meta-divider" id="topbarDivider2">•</span>
                    <span id="patientDoctorTopbar">@yield('patient-doctor', '')</span>
                </div>
            </div>

            {{-- KOLOM KANAN: INFORMASI WAKTU DAN STATUS --}}
            <div class="topbar-patient-visit">

                <div class="topbar-visit-date-row">
                    <span class="visit-date-label">Masuk :</span>
                    <span class="visit-date-value" id="patientTglMasukTopbar">
                        @yield('patient-tgl-masuk', '')
                    </span>
                </div>

                <div class="topbar-visit-date-row">
                    <span class="visit-date-label">Keluar :</span>
                    <span class="visit-date-value" id="patientTglKeluarTopbar">
                        @yield('patient-tgl-keluar', '')
                    </span>
                </div>

                <span
                    class="badge bg-secondary-subtle text-dark border border-secondary-subtle"
                    id="patientStatusTopbar"
                >@yield('patient-status', '')</span>
            </div>
        </div>
    </div>

    {{-- ACTIONS: MENU PASIEN, NOTIFIKASI, TEMA, PROFIL --}}
    <div class="topbar-actions">

        {{-- DROPDOWN MENU PASIEN (hanya aktif saat workspace pasien) --}}
        <div class="dropdown" id="patientDropdownContainer" style="display: none;">
            <button
                type="button"
                class="patient-dropdown-trigger dropdown-toggle"
                id="patientDropdown"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                aria-label="Menu pasien"
            >
                <i class="bi bi-person-vcard"></i>
                <span class="patient-menu-label">Menu Pasien</span>
            </button>

            <ul class="dropdown-menu dropdown-menu-end patient-dropdown-menu" aria-labelledby="patientDropdown">
                <li>
                    <button type="button" class="dropdown-item" id="btn-icare" onclick="window.showICareActiveEMR?.()">
                        <i class="bi bi-heart-pulse"></i>
                        <span>i-Care</span>
                    </button>
                </li>
                <li>
                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#dummySEPModal">
                        <i class="bi bi-file-earmark-medical"></i>
                        <span>Digital SEP</span>
                    </button>
                </li>
            </ul>
        </div>

        {{-- NOTIFIKASI: STRUKTUR ASLI DIPERTAHANKAN --}}
        <div class="dropdown notification-dropdown"
            data-endpoint="{{ route('v3.notifikasi.klaim') }}"
            data-all-url="{{ route('v3.setting.notifikasi') }}"
        >
            <button
                type="button"
                class="icon-button notification-trigger"
                id="notificationDropdown"
                data-bs-toggle="dropdown"
                data-bs-auto-close="outside"
                aria-expanded="false"
                aria-label="Buka notifikasi"
            >
                <i class="bi bi-bell"></i>
                <span id="notificationBadge" class="notification-dot" style="display: none;"></span>
            </button>

            <div class="dropdown-menu dropdown-menu-end notification-menu" aria-labelledby="notificationDropdown">
                <div class="notification-menu-header">
                    <div class="notification-heading-icon">
                        <i class="bi bi-bell"></i>
                    </div>
                    <div class="notification-heading-copy">
                        <h6>Notifikasi</h6>
                        <p>Informasi terbaru terkait klaim dan kunjungan</p>
                    </div>
                    <span id="notificationHeaderBadge" class="notification-count" style="display: none;">0</span>
                </div>

                <div class="notification-menu-caption">
                    <span class="notification-live-indicator"></span>
                    Pembaruan otomatis setiap 30 detik
                </div>

                <ul id="notificationList" class="notification-list" aria-live="polite">
                    <li class="notification-state">
                        <span class="spinner-border spinner-border-sm"></span>
                        <span>Memuat notifikasi...</span>
                    </li>
                </ul>

                <div class="notification-menu-footer">
                    <a href="{{ route('v3.setting.notifikasi') }}" wire:navigate class="notification-all-link">
                        <span>Buka Semua Notifikasi</span>
                        <i class="bi bi-arrow-up-right"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- THEME TOGGLE: TETAP --}}
        <button type="button" class="icon-button" id="themeToggleTop" aria-label="Ganti tema">
            <i class="bi bi-moon-stars"></i>
        </button>

        {{-- USER PROFILE: TETAP --}}
        <div class="dropdown">
            <button type="button" class="user-menu dropdown-toggle" data-bs-toggle="dropdown">
                <span class="user-avatar">
                    {{ mb_substr(Auth::user()->NAMA ?? (Auth::user()->name ?? 'U'), 0, 1) }}
                </span>
                <span class="user-menu-copy d-none d-sm-flex">
                    <strong>{{ Auth::user()->NAMA ?? (Auth::user()->name ?? 'Pengguna') }}</strong>
                    <small>{{ Auth::user()->NIP ?? 'Pengguna SIRMED' }}</small>
                </span>
            </button>

            <ul class="dropdown-menu dropdown-menu-end user-dropdown">
                <li class="dropdown-header">Akun pengguna</li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i>
                            Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>

{{-- MODAL DUMMY i-CARE --}}
<div class="modal fade" id="dummyICareModal" tabindex="-1" aria-labelledby="dummyICareModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dummyICareModalLabel">
                    <i class="bi bi-heart-pulse text-primary me-2"></i>Lihat i-Care
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Modal dummy i-Care. Integrasi i-Care dapat ditambahkan di sini.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DUMMY SEP --}}
<div class="modal fade" id="dummySEPModal" tabindex="-1" aria-labelledby="dummySEPModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dummySEPModalLabel">
                    <i class="bi bi-file-earmark-medical text-primary me-2"></i>Lihat SEP
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Modal dummy SEP. Informasi SEP dapat ditampilkan di sini.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
