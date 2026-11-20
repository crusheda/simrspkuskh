<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'BOT') · Sirmed v3</title>
    <link rel="icon" href="{{ asset('images/logo/logo.png') }}">

    {{-- Inisialisasi tema sebelum CSS --}}
    <script>
        (() => {
            let theme = 'light';

            try {
                const saved = localStorage.getItem('sirmed-theme');

                theme = saved === 'dark' || saved === 'light'
                    ? saved
                    : (
                        window.matchMedia('(prefers-color-scheme: dark)').matches
                            ? 'dark'
                            : 'light'
                    );
            } catch (e) {}

            document.documentElement.setAttribute('data-bs-theme', theme);
        })();
    </script>

    @include('inc.v3.css')

    <link rel="stylesheet" href="{{ asset('css/v3/components/workspace.css') }}">
    <link rel="stylesheet" href="{{ asset('css/v3/components/emr-workspace.css') }}">
    <link rel="stylesheet" href="{{ asset('css/v3/components/claim-workspace.css') }}">

    @livewireStyles

</head>

<body class="{{ request()->boolean('workspace') ? 'emr-workspace-frame' : '' }}">

    @if (request()->boolean('workspace'))

        {{-- ==================================================
             MODE DETAIL PASIEN
             Dipakai di dalam iframe setiap tab pasien.
             Tidak membuat sidebar/header kedua kali.
        =================================================== --}}
        <main class="emr-frame-content">
            {{ $slot }}
        </main>


    @else

        {{-- MODE APLIKASI UTAMA --}}
        <div class="app-shell" id="sirmedApp">

            {{-- Sidebar global --}}
            @include('inc.v3.sidebar')

            <div class="app-main">

                {{-- Header global --}}
                @include('inc.v3.header')

                @if (request()->routeIs('v3.emr'))

                    {{-- ==================================================
                        WORKSPACE KHUSUS MEDICAL RECORD
                    =================================================== --}}
                    <div class="emr-workspace" id="emrWorkspace">

                        {{-- TABBAR EMR --}}
                        <div class="emr-tabbar">

                            <div
                                class="emr-tabbar-scroll"
                                id="emrTabBar"
                                role="tablist"
                                aria-label="Tab Rekam Medis"
                            >
                                <div
                                    class="emr-tab active"
                                    id="emrTab-home"
                                    data-tab-id="home"
                                    role="tab"
                                    aria-controls="emrPanel-home"
                                    aria-selected="true"
                                    tabindex="0"
                                >
                                    <i class="bi bi-house-door"></i>

                                    <span class="emr-tab-label">
                                        My Pasien
                                    </span>
                                </div>
                            </div>

                            <div class="emr-tabbar-actions">

                                {{-- Refresh tab aktif --}}
                                <button
                                    type="button"
                                    class="emr-tab-action"
                                    id="emrRefreshActiveTab"
                                    title="Refresh tab aktif"
                                    aria-label="Refresh tab aktif"
                                >
                                    <i class="ti ti-refresh"></i>
                                </button>

                                {{-- Tutup tab lainnya --}}
                                <button
                                    type="button"
                                    class="emr-tab-action"
                                    id="emrCloseOtherTabs"
                                    title="Tutup tab lainnya"
                                    aria-label="Tutup tab lainnya"
                                >
                                    <i class="ti ti-square-x"></i>
                                </button>

                                {{-- Tutup semua tab --}}
                                <button
                                    type="button"
                                    class="emr-tab-action"
                                    id="emrCloseAllTabs"
                                    title="Tutup semua tab pasien"
                                    aria-label="Tutup semua tab pasien"
                                >
                                    <i class="ti ti-x"></i>
                                </button>

                            </div>
                        </div>

                        {{-- KONTEN EMR --}}
                        <main class="app-content">

                            <div id="emrWorkspaceContent">

                                <section
                                    class="emr-workspace-panel active"
                                    id="emrPanel-home"
                                    data-panel-id="home"
                                    role="tabpanel"
                                    aria-labelledby="emrTab-home"
                                >
                                    {{ $slot }}
                                </section>

                            </div>

                            @include('inc.v3.footer')

                        </main>

                    </div>

                @elseif (request()->routeIs('v3.smartclaim*'))

                    <div class="claim-workspace" id="claimWorkspace" data-user-id="{{ auth()->id() }}">
                        <div class="claim-tabbar" id="claimTabBar">
                            <div class="claim-tabbar-scroll" id="claimTabBarScroll" role="tablist">
                                <div id="claimHomeTab" class="claim-tab claim-home-tab active" data-claim-home>
                                    <i class="bi bi-house-door"></i>
                                    <span>Claim Table</span>
                                </div>
                            </div>

                            <div class="claim-tabbar-actions">
                                <button type="button" class="claim-tab-action" id="claimRefreshActiveTab" title="Refresh tab aktif" aria-label="Refresh tab aktif">
                                    <i class="ti ti-refresh"></i>
                                </button>
                                <button type="button" class="claim-tab-action" id="claimCloseOtherTabs" title="Tutup tab lainnya" aria-label="Tutup tab lainnya">
                                    <i class="ti ti-square-x"></i>
                                </button>
                                <button type="button" class="claim-tab-action" id="claimCloseAllTabs" title="Tutup semua tab" aria-label="Tutup semua tab">
                                    <i class="ti ti-x"></i>
                                </button>
                            </div>
                        </div>

                        <main class="app-content">
                            {{--
                                Home panel dan kontainer detail harus sejajar.
                                JS menyembunyikan #claimWorkspaceContent saat home aktif,
                                sehingga #claimHomePanel jangan dimasukkan ke dalamnya.
                            --}}
                            <section id="claimHomePanel" class="claim-workspace-panel active" data-panel-id="home">
                                {{ $slot }}
                            </section>

                            {{-- Panel iframe detail dibuat dinamis oleh claim-workspace.js. --}}
                            <div id="claimWorkspaceContent" hidden></div>

                            @include('inc.v3.footer')
                        </main>
                    </div>

                @else

                    {{-- ==================================================
                        HALAMAN APLIKASI SELAIN WORKSPACE
                    =================================================== --}}
                    <main class="app-content">

                        {{ $slot }}

                        @include('inc.v3.footer')

                    </main>

                @endif

                {{-- EMR CONFIRM ACTION MODAL --}}
                <div class="modal fade" id="emrConfirmActionModal" tabindex="-1"
                    aria-labelledby="emrConfirmActionTitle" aria-hidden="true">

                    <div class="modal-dialog modal-dialog-centered modal-sm">
                        <div class="modal-content">

                            <div class="modal-body text-center p-4">

                                <div class="emr-confirm-icon mb-3" id="emrConfirmIcon">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </div>

                                <h5 class="mb-2" id="emrConfirmActionTitle">
                                    Konfirmasi Tindakan
                                </h5>

                                <p class="text-muted small mb-4" id="emrConfirmActionMessage">
                                    Apakah Anda yakin ingin melanjutkan tindakan ini?
                                </p>

                                <div class="d-flex justify-content-center gap-2">

                                    <button type="button"
                                            class="btn btn-subtle-secondary"
                                            data-bs-dismiss="modal">
                                        Batal
                                    </button>

                                    <button type="button"
                                            class="btn btn-danger"
                                            id="emrConfirmActionSubmit">
                                        Ya, Lanjutkan
                                    </button>

                                </div>

                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

    @endif

    {{-- JavaScript global yang sudah ada --}}
    @include('inc.v3.js')

    @livewireScripts

    {{-- JavaScript workspace --}}
    <script src="{{ asset('js/v3/components/emr-workspace.js') }}"></script>
    <script src="{{ asset('js/v3/components/claim-workspace.js') }}" defer></script>

    @stack('scripts')

</body>

</html>
