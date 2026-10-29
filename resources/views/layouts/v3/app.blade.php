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

    <link rel="stylesheet" href="{{ asset('css/v3/components/emr-workspace.css') }}">

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

        {{-- ==================================================
             MODE APLIKASI UTAMA
        =================================================== --}}
        <div class="app-shell" id="sirmedApp">

            {{-- Sidebar global --}}
            @include('inc.v3.sidebar')

            <div class="app-main">

                {{-- Header global --}}
                @include('inc.v3.header')

                {{-- Workspace --}}
                <div class="emr-workspace" id="emrWorkspace">

                    {{-- ======================================
                         TAB BAR
                    ======================================= --}}
                    <div class="emr-tabbar">

                        <div
                            class="emr-tabbar-scroll"
                            id="emrTabBar"
                            role="tablist"
                            aria-label="Tab Rekam Medis"
                        >

                            {{-- Tab halaman awal --}}
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
                                    Daftar Kunjungan
                                </span>
                            </div>

                        </div>

                        {{-- Aksi pengelolaan tab --}}
                        <div class="emr-tabbar-actions">

                            <button
                                type="button"
                                class="emr-tab-action"
                                id="emrCloseOtherTabs"
                                title="Tutup tab lainnya"
                                aria-label="Tutup tab lainnya"
                            >
                                <i class="bi bi-copy"></i>
                            </button>

                            <button
                                type="button"
                                class="emr-tab-action"
                                id="emrCloseAllTabs"
                                title="Tutup semua tab pasien"
                                aria-label="Tutup semua tab pasien"
                            >
                                <i class="bi bi-x-lg"></i>
                            </button>

                        </div>

                    </div>

                    {{-- ======================================
                         WORKSPACE CONTENT
                    ======================================= --}}
                    <main class="app-content">

                        <div id="emrWorkspaceContent">

                            {{-- Panel halaman awal --}}
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

                        {{-- Footer global --}}
                        @include('inc.v3.footer')

                    </main>

                </div>

            </div>

        </div>

    @endif

    {{-- JavaScript global yang sudah ada --}}
    @include('inc.v3.js')

    @livewireScripts

    {{-- JavaScript workspace --}}
    <script src="{{ asset('js/v3/components/emr-workspace.js') }}"></script>

    @stack('scripts')

</body>

</html>
