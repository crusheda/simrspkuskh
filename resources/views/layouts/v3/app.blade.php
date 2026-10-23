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
    @livewireStyles
</head>

<body>
    <div class="app-shell" id="sirmedApp">

        {{-- Sidebar --}}
        @include('inc.v3.sidebar')

        <div class="app-main">

            {{-- Header --}}
            @include('inc.v3.header')

            <main class="app-content">
                <div class="content-container">
                    {{ $slot }}
                </div>

                {{-- Footer --}}
                @include('inc.v3.footer')
            </main>

        </div>
    </div>

    {{-- Modal Tentang SIRMED --}}
    @include('inc.v3.about')

    {{-- JavaScript global --}}
    @include('inc.v3.js')

    @livewireScripts

    @stack('scripts')
</body>

</html>
