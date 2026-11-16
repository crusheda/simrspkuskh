<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Detail Smart Claim' }}</title>

    <link
        rel="icon"
        href="{{ asset('images/logo/logo.png') }}"
    >

    {{-- THEME SYNC --}}
    <script>
        (() => {

            const root = document.documentElement;

            const THEME_KEY = 'sirmed-theme';

            function applyTheme(theme) {

                if (
                    theme !== 'dark' &&
                    theme !== 'light'
                ) {
                    return;
                }

                root.setAttribute(
                    'data-bs-theme',
                    theme
                );

                root.style.colorScheme = theme;

            }

            // Sinkronisasi dari halaman induk.
            window.addEventListener('message', (event) => {

                if (
                    event.origin !== window.location.origin
                ) {
                    return;
                }

                if (
                    event.data?.type === 'SIRMED_THEME_CHANGE'
                ) {
                    applyTheme(event.data.theme);
                }

            });

            // Sinkronisasi antar-tab browser.
            window.addEventListener('storage', (event) => {

                if (
                    event.key !== THEME_KEY &&
                    event.key !== null
                ) {
                    return;
                }

                try {

                    const theme = localStorage.getItem(
                        THEME_KEY
                    );

                    applyTheme(theme);

                } catch (error) {
                    // Abaikan apabila localStorage tidak tersedia.
                }

            });

        })();
    </script>

    @include('inc.v3.css')

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

        body.emr-workspace-frame {

            width: 100%;

            min-height: 100vh;

            margin: 0;
            padding: 0;

            overflow-x: hidden;

            background: var(--bg);
            color: var(--text);

        }

        .emr-detail-page {

            width: 100%;

            min-height: 100vh;

            margin: 0;
            padding: 0;

        }

    </style>

</head>

<body class="emr-workspace-frame">

    <main class="emr-detail-page emr-frame-content">

        {{ $slot }}

    </main>

    {{-- JavaScript global SIRMED --}}
    @include('inc.v3.js')

    @livewireScripts

    @stack('scripts')

</body>

</html>
