<!doctype html>
<html lang="en">
<head>

    <title>Sistem Informasi Rekam Medis</title>

    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Sistem Manajemen Rumah Sakit PKU Muhammadiyah Sukoharjo" />
    <meta name="keywords" content="simrs, simrsmu, sim rspkuskh, pkuskh, rspkuskh, sistem pku, sistem informasi majemen rumah sakit, rumah sakit pku, pku muhammadiyah sukoharjo, pku sukoharjo">
    <meta name="author" content="Yussuf Faisal" />
    <link rel="shortcut icon" href="{{ asset('images/logo/logo.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('images/logo/logo.png') }}">
    <link rel="icon" href="{{ asset('images/logo/logo.png') }}" type="image/x-icon" />

    @include('inc.css')

</head>
<body class="layout-modern" data-pc-preset="preset-1" data-pc-sidebar-theme="light" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme="light">

    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <div class="loader">
        <div class="p-4 text-center">
            <div class="custom-loader"></div>
            <h2 class="my-3 f-w-400">Loading..</h2>
            <p class="mb-0">Please wait while we get your information from the web</p>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    @include('inc.navbar.navbar2')
    @include('inc.header.header2')

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">

            @yield('content')

        </div>
    </div>
    <!-- [ Main Content ] end -->

    @include('inc.footer')

    <!-- Required Js -->
    @include('inc.js')

    <script>
        function showLoader() {
            // var control = document.querySelector('#show-loader');
            var elem = document.querySelector('.loader'),
                fadeInInterval,
                fadeOutInterval;

            // if (control) {
            //     control.addEventListener('click', function () {
                    if (!elem.classList.contains('is-active')) {
                        clearInterval(fadeInInterval);
                        clearInterval(fadeOutInterval);
                        elem.fadeIn = function (timing) {
                            var newValue = 0;
                            elem.style.display = 'flex';
                            elem.style.opacity = 0;
                            fadeInInterval = setInterval(function () {
                                if (newValue < 1) {
                                    newValue += 0.01;
                                } else if (newValue === 1) {
                                    clearInterval(fadeInInterval);
                                }
                                elem.style.opacity = newValue;
                            }, timing);
                        };
                        elem.fadeIn(3);
                        setTimeout(function () {
                            clearInterval(fadeInInterval);
                            clearInterval(fadeOutInterval);
                            elem.fadeOut = function (timing) {
                                var newValue = 1;
                                elem.style.opacity = 1;
                                fadeOutInterval = setInterval(function () {
                                    if (newValue > 0) {
                                        newValue -= 0.01;
                                    } else if (newValue < 0) {
                                        elem.style.opacity = 0;
                                        elem.style.display = 'none';
                                        clearInterval(fadeOutInterval);
                                    }
                                    elem.style.opacity = newValue;
                                }, timing);
                            };
                            elem.fadeOut(3);
                        }, 4000);
                    }
            //     });
            // }
        }
    </script>
</body>

</html>
