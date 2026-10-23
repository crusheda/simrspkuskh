<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="theme-color" content="#5955D1">
    <meta name="robots" content="index, follow">
    <meta name="author" content="Programmer RS PKU Muhammadiyah Sukoharjo">
    <meta name="format-detection" content="telephone=no">

    <meta name="keywords"
        content="simrs, simrsmu, sim rspkuskh, pkuskh, rspkuskh, sistem pku, sistem informasi manajemen rumah sakit, rumah sakit pku, pku muhammadiyah sukoharjo">

    <meta name="description" content="Sistem Manajemen Rumah Sakit PKU Muhammadiyah Sukoharjo">

    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="SIRMED | Sistem Informasi Rekam Medis">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="id_ID">
    <meta property="og:title" content="SIRMED | Sistem Informasi Rekam Medis">
    <meta property="og:description" content="Sistem Manajemen Rumah Sakit PKU Muhammadiyah Sukoharjo">
    <meta property="og:image" content="{{ asset('images/logo/logo.png') }}">

    <meta name="twitter:card" content="summary">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:creator" content="Programmer RS PKU Muhammadiyah Sukoharjo">
    <meta name="twitter:title" content="SIRMED | Sistem Informasi Rekam Medis">
    <meta name="twitter:description" content="Sistem Manajemen Rumah Sakit PKU Muhammadiyah Sukoharjo">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Autentikasi SIRMED v3</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo/logo.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logo/logo.png') }}">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap"
        rel="stylesheet">

    {{-- Bootstrap 5: diperlukan untuk modal login --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    {{-- CSS khusus halaman autentikasi --}}
    <link rel="stylesheet" href="{{ asset('assets/v2/auth/css/style.css') }}">

    {{-- Font Awesome khusus autentikasi --}}
    <link rel="stylesheet" href="{{ asset('assets/v2/fonts/fontawesome.css') }}">

    {{-- Stylesheet utama NexLink --}}
    <link rel="stylesheet" href="{{ asset('assets/v2/libs/flaticon/css/all/all.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/v2/libs/lucide/lucide.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/v2/libs/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/v2/libs/simplebar/simplebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/v2/libs/node-waves/waves.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/v2/libs/bootstrap-select/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/v2/css/styles.css') }}">

    {{-- Livewire --}}
    @livewireStyles
</head>

<body>

    {{-- Konten komponen Livewire --}}
    {{ $slot }}

    {{-- Bootstrap JS: modal dan komponen Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Script NexLink --}}
    <script src="{{ asset('assets/v2/libs/global/global.min.js') }}"></script>
    <script src="{{ asset('assets/v2/js/appSettings.js') }}"></script>
    <script src="{{ asset('assets/v2/js/main.js') }}"></script>

    {{-- Livewire --}}
    @livewireScripts

</body>

</html>
