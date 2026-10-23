<!DOCTYPE html>
<html lang="id" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIRMED v3') · SIRMED</title>
    <link rel="icon" href="{{ asset('images/logo/logo.png') }}">

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
