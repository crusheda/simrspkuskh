<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="utf-8">
    <meta name="theme-color" content="#5955D1">
    <meta name="robots" content="index, follow">
    <meta name="author" content="Programmer RS PKU Muhammadiyah Sukoharjo">
    <meta name="format-detection" content="telephone=no">
    <meta name="keywords" content="simrs, simrsmu, sim rspkuskh, pkuskh, rspkuskh, sistem pku, sistem informasi majemen rumah sakit, rumah sakit pku, pku muhammadiyah sukoharjo, pku sukoharjo">
    <meta name="description" content="Sistem Manajemen Rumah Sakit PKU Muhammadiyah Sukoharjo">

    <meta property="og:url" content="{{ route('v2.dashboard') }}">
    <meta property="og:site_name" content="SIRMED | Sistem Informasi Rekam Medis">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="id_ID">
    <meta property="og:title" content="SIRMED | Sistem Informasi Rekam Medis">
    <meta property="og:description" content="Sistem Manajemen Rumah Sakit PKU Muhammadiyah Sukoharjo">
    <meta property="og:image" content="{{ asset('images/logo/logo.png') }}">

    <meta name="twitter:card" content="summary">
    <meta name="twitter:url" content="{{ route('v2.dashboard') }}">
    <meta name="twitter:creator" content="Programmer RS PKU Muhammadiyah Sukoharjo">
    <meta name="twitter:title" content="SIRMED | Sistem Informasi Rekam Medis">
    <meta name="twitter:description" content="Sistem Manajemen Rumah Sakit PKU Muhammadiyah Sukoharjo">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'SIRMED')</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo/logo.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logo/logo.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('inc.v2.css')
    @stack('styles')

    <script>
        const originalTitle = document.title + "   •   Sistem Informasi Rekam Medis   •   RS PKU Muhammadiyah Sukoharjo   •   ";
        let index = 0;

        setInterval(() => {
            document.title =
                originalTitle.substring(index) +
                originalTitle.substring(0, index);

            index++;

            if (index >= originalTitle.length) {
                index = 0;
            }
        }, 250);
    </script>
</head>

<body>
    <div class="page-layout">

        <!-- Logout Form -->
        <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>

        @include('inc.v2.header')
        @include('inc.v2.sidebar')

            <main class="app-wrapper">
                @yield('content')
            </main>

        @include('inc.v2.footer')

    </div>

    @include('inc.v2.js')

</body>
</html>

