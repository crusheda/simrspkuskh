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

            {{-- KOLOM KIRI: IDENTITAS TOPBAR --}}
            <div class="topbar-patient-identity">

                {{-- =========================================
                    MODE DEFAULT HALAMAN
                    Tampil ketika tidak membuka EMR Workspace
                ========================================== --}}
                <div class="topbar-default-identity" id="topbarDefaultIdentity">

                    {{-- BARIS 1: SUBTITLE DEFAULT --}}
                    <div class="topbar-default-subtitle">
                        <span id="defaultPageSubTitlebar">
                            @yield('page-sub-title', '')
                        </span>
                    </div>

                    {{-- BARIS 2: TITLE DEFAULT --}}
                    <div class="topbar-default-title">
                        <span id="defaultPageTitlebar">
                            @yield('page-title', '')
                        </span>
                    </div>

                </div>


                {{-- =========================================
                    MODE EMR WORKSPACE
                    Tampil ketika tab pasien EMR aktif
                ========================================== --}}
                <div class="topbar-workspace-identity" id="topbarWorkspaceIdentity">

                    {{-- BARIS 1: ASURANSI • NOMOR ASURANSI --}}
                    <div class="topbar-insurance-meta" id="patientInsuranceMetaTopbar">
                        <span id="patientInsuranceNameTopbar">
                            @yield('patient-insurance-name', '')
                        </span>

                        <span class="topbar-meta-divider" id="topbarDivider1"></span>

                        <span id="patientInsuranceTopbar">
                            @yield('patient-insurance', '')
                        </span>
                    </div>


                    {{-- BARIS 2: RM • NAMA PASIEN • TANGGAL LAHIR --}}
                    <div class="topbar-patient-meta">

                        <span id="pageSubTitlebar" title="Nomor Rekam Medis">
                            @yield('page-sub-title', '')
                        </span>

                        <span class="topbar-meta-divider" id="topbarDivider3"></span>

                        <span id="pageTitlebar" title="@yield('page-title', '')">
                            @yield('page-title', '')
                        </span>

                        <span id="patientBirthBar" class="topbar-patient-birth">
                            @yield('patient-birth', '')
                        </span>

                    </div>


                    {{-- BARIS 3: RUANGAN • DOKTER --}}
                    <div class="topbar-patient-room-doctor">

                        <span id="patientRoomTopbar">
                            @yield('patient-room', '')
                        </span>

                        <span class="topbar-meta-divider" id="topbarDivider2"></span>

                        <span id="patientDoctorTopbar">
                            @yield('patient-doctor', '')
                        </span>

                    </div>

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

        <button type="button" class="icon-button" id="cariEMRPasien" style="display: none;" aria-label="Cari EMR Pasien">
            <i class="bi bi-search"></i>
            <span class="d-none d-md-inline ms-1">Cari EMR Pasien</span>
        </button>

        {{-- DROPDOWN MENU PASIEN (hanya aktif saat workspace pasien) --}}
        <div class="dropdown" id="patientDropdownContainer" style="display: none;">
            <button
                type="button"
                class="patient-dropdown-trigger"
                id="patientDropdown"
                data-bs-toggle="dropdown"
                aria-expanded="false"
                aria-label="Menu pasien"
            >
                <i class="bi bi-person-vcard"></i>
                <span class="patient-menu-label">E-Patient</span>
            </button>

            <ul class="dropdown-menu dropdown-menu-end patient-dropdown-menu" aria-labelledby="patientDropdown">
                <li>
                    <button type="button" class="dropdown-item" id="btn-icare" onclick="window.showICareActiveEMR?.()">
                        <i class="bi bi-heart-pulse"></i>
                        <span>i-Care</span>
                    </button>
                </li>
                <li>
                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#showSEPModal">
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

        {{-- USER PROFILE --}}
        @php
            $user = Auth::user();
            $namaUser = $user->NAMA ?? $user->name ?? 'Pengguna';
            $nipUser = $user->NIP ? 'NIP. ' . $user->NIP : 'Pengguna SIRMED';
        @endphp

        <div class="dropdown">
            <button
                type="button"
                class="user-menu dropdown-toggle"
                data-bs-toggle="dropdown"
                aria-expanded="false"
            >
                <span class="user-avatar">
                    {{ mb_substr($namaUser, 0, 1) }}
                </span>

                <span class="user-menu-copy d-none d-sm-flex">
                    <strong>{{ $namaUser }}</strong>
                    <small>{{ $nipUser }}</small>
                </span>
            </button>

            <ul class="dropdown-menu dropdown-menu-end user-dropdown">
                <li class="dropdown-header">
                    <div class="fw-semibold">{{ $namaUser }}</div>
                    <small class="text-muted">{{ $nipUser }}</small>
                </li>

                <li>
                    <hr class="dropdown-divider">
                </li>

                {{-- Profil Saya --}}
                <li>
                    <a href="{{ route('v3.profil') }}" class="dropdown-item" wire:navigate>
                        <i class="bi bi-person-circle me-2"></i>
                        Profil Saya
                    </a>
                </li>

                @hasrole('admin')
                {{-- Pengaturan Akun --}}
                <li>
                    <a href="{{ route('v2.roles') }}" class="dropdown-item">
                        <i class="bi bi-gear me-2"></i>
                        Pengaturan Akses Pengguna
                    </a>
                </li>
                @endhasrole

                @hasrole('admin')
                {{-- Capaian --}}
                <li>
                    <a href="{{ route('v3.capaian') }}" class="dropdown-item" wire:navigate>
                        <i class="bi bi-bezier2 me-2"></i>
                        Capaian
                    </a>
                </li>
                @endhasrole

                <li>
                    <hr class="dropdown-divider">
                </li>

                {{-- Logout --}}
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

@include('inc.v3.emr.epatient')

<!-- =========================================================
    MODAL CARI EMR PASIEN
========================================================= -->
<div class="modal fade" id="modalCariEMR" tabindex="-1"
     aria-labelledby="modalCariEMRLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">

        <div class="modal-content emr-search-modal">

            <!-- HEADER -->
            <div class="modal-header emr-search-header">

                <div class="d-flex align-items-center gap-3">

                    <div class="emr-search-icon">
                        <i class="bi bi-search"></i>
                    </div>

                    <div>
                        <h5 class="modal-title fw-bold mb-1" id="modalCariEMRLabel">
                            Cari Rekam Medis Pasien
                        </h5>

                        <div class="small text-body-secondary">
                            Cari Pasien berdasarkan Keyword
                        </div>
                    </div>

                </div>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup">
                </button>

            </div>

            <!-- BODY -->
            <div class="modal-body p-3 p-md-4">

                <!-- SEARCH -->
                <div class="emr-search-box mb-4">

                    <label for="cariEMRNorm" class="form-label fw-semibold">
                        Cari Rekam Medis Pasien
                    </label>

                    <div class="input-group input-group-lg emr-search-input-group">

                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>

                        <input type="text"
                            class="form-control fs-14"
                            id="cariEMRNorm"
                            placeholder="Tuliskan No.RM / No.Pendaftaran / No.Kunjungan / No.Asuransi / Nama Lengkap Pasien"
                            autocomplete="off">

                        <button type="button"
                                class="btn btn-primary"
                                id="btnCariEMR">

                            <i class="bi bi-search"></i>

                        </button>

                    </div>

                    <div class="form-text">
                        Ketik minimal 3 karakter. Pencarian dapat menggunakan nomor RM, nomor pendaftaran,
                        nomor kunjungan, nomor Asuransi, atau nama lengkap pasien.
                    </div>

                </div>

                <!-- INFO SEARCH -->
                <div id="cariEMRInfo" class="mb-3"></div>

                <!-- RESULT -->
                <div id="cariEMRHasil">

                    <div class="emr-search-empty">

                        <div class="emr-search-empty-icon">
                            <i class="bi bi-person-search"></i>
                        </div>

                        <h6 class="fw-semibold mb-1">
                            Cari pasien terlebih dahulu
                        </h6>

                        <p class="text-body-secondary small mb-0">
                            Masukkan nomor rekam medis pasien kemudian tekan Cari Pasien.
                        </p>

                    </div>

                </div>

            </div>


            <!-- FOOTER -->
            <div class="modal-footer d-flex justify-content-between">

                <div class="small text-body-secondary">
                    <i class="bi bi-info-circle me-1"></i>
                    Klik salah satu kunjungan untuk membuka EMR.
                </div>

                <button type="button"
                        class="btn btn-subtle-secondary border-dashed"
                        data-bs-dismiss="modal">

                    <i class="bi bi-x-lg me-1"></i>
                    Tutup

                </button>

            </div>

        </div>

    </div>

</div>
