<!DOCTYPE html>
<html lang="en">

<head>

    <base>

    <meta charset="utf-8">
    <meta name="theme-color" content="#5955D1">
    <meta name="robots" content="index, follow">
    <meta name="author" content="Programmer RS PKU Muhammadiyah Sukoharjo">
    <meta name="format-detection" content="telephone=no">
    <meta name="keywords"
        content="simrs, simrsmu, sim rspkuskh, pkuskh, rspkuskh, sistem pku, sistem informasi majemen rumah sakit, rumah sakit pku, pku muhammadiyah sukoharjo, pku sukoharjo">
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

    <title>SIRMED v2 - Error 404 - Not Found</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo/logo.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logo/logo.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- begin::NexLink Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&amp;display=swap"
        rel="stylesheet">
    <!-- end::NexLink Google Fonts -->

    <!-- begin::NexLink Required Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/v2/libs/flaticon/css/all/all.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/v2/libs/lucide/lucide.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/v2/libs/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/v2/libs/simplebar/simplebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/v2/libs/node-waves/waves.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/v2/libs/bootstrap-select/css/bootstrap-select.min.css') }}">
    <!-- end::NexLink Required Stylesheet -->

    <!-- begin::NexLink CSS Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/v2/css/styles.css') }}">
    <!-- end::NexLink CSS Stylesheet -->



</head>

<body>
    <div class="page-layout">

        <div class="error-full-wrapper">
            <div class="row g-xl-7 g-5 justify-content-center">
                <div class="col-md-5">
                    <div class="pe-lg-5">
                        <div id="error001"></div>
                    </div>
                </div>
                <div class="col-md-6 align-self-center">
                    <h2 class="error-heading mb-3">Oops!
                        <br> Terjadi Kesalahan
                    </h2>
                    <p class="error-text mb-5">Kami tidak dapat menemukan halaman yang Anda cari. Silakan kembali ke beranda.</p>
                    <a href="{{ route('v2.dashboard') }}" class="btn btn-primary waves-effect waves-light">
                        <i class="fi fi-rr-arrow-small-left scale-4x me-1"></i> Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>

    </div>
    <script src="{{ asset('assets/v2/libs/global/global.min.js') }}"></script>
    <script src="{{ asset('assets/v2/libs/lottiefiles/lottie.min.js') }}"></script>
    <script src="{{ asset('assets/v2/js/lottie.js') }}"></script>
    <script src="{{ asset('assets/v2/js/appSettings.js') }}"></script>
    <script src="{{ asset('assets/v2/js/main.js') }}"></script>
</body>

</html>
