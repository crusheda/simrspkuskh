<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Detail Rekam Medis')</title>
    <link rel="icon" href="{{ asset('images/logo/logo.png') }}">

    <script>
        (() => {
            const root = document.documentElement;
            const THEME_KEY = 'sirmed-theme';

            function applyTheme(theme) {
                if (theme !== 'dark' && theme !== 'light') {
                    return;
                }

                root.setAttribute('data-bs-theme', theme);
                root.style.colorScheme = theme;
            }

            // Sinkronisasi dari halaman utama.
            window.addEventListener('message', (event) => {
                if (event.origin !== window.location.origin) {
                    return;
                }

                if (event.data?.type === 'SIRMED_THEME_CHANGE') {
                    applyTheme(event.data.theme);
                }
            });

            // Sinkronisasi jika tema diubah dari tab browser lain.
            window.addEventListener('storage', (event) => {
                if (event.key !== THEME_KEY && event.key !== null) {
                    return;
                }

                try {
                    const theme = localStorage.getItem(THEME_KEY);

                    if (theme === 'dark' || theme === 'light') {
                        applyTheme(theme);
                    }
                } catch (error) {
                    // Abaikan jika localStorage tidak tersedia.
                }
            });
        })();
    </script>

    @include('inc.v3.css')

    {{-- CSS khusus halaman detail --}}
    @stack('styles')

    @livewireStyles

    <style>
        html,
        body {
            width: 100%;
            min-height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }

        .emr-detail-page {
            width: 100%;
            height: 100%;
            min-height: 100vh;
            /* padding: 16px; */
        }
    </style>

</head>

<body>
    <main class="emr-detail-page">
        @yield('content')
    </main>

    {{-- JavaScript global aplikasi --}}
    @include('inc.v3.js')

    @livewireScripts

    {{-- JavaScript tambahan halaman detail --}}
    @stack('scripts')

</body>
</html>
