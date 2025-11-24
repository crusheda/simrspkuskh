<!doctype html>
<html lang="en">
<head>
    {{-- DATA PC THEME START --}}
    <script>
        function setLogoByTheme(theme) {
            const logo = document.getElementById("app-logo");
            if (!logo) return;

            if (theme === "dark") {
                logo.src = "{{ asset('images/logo/logoname_w.png') }}";
            } else {
                logo.src = "{{ asset('images/logo/logoname.png') }}";
            }
        }

        (function() {
            var savedTheme = localStorage.getItem("theme") || "light"; // default dark
            console.log('Saved Theme : '+savedTheme);

            // pasang theme ke body juga
            // gunakan MutationObserver untuk tunggu body muncul
            var observer = new MutationObserver(function(mutations, me) {
                if (document.body) {
                    document.body.setAttribute("data-pc-theme", savedTheme);
                    setLogoByTheme(savedTheme); // update logo juga
                    me.disconnect(); // stop observer
                }
            });
            observer.observe(document.documentElement, {childList: true});
        })();
    </script>

    <title>Sistem Informasi Rekam Medis</title>

    @include('inc.meta')

    @include('inc.css')

</head>
<body id="main-body" data-pc-preset="preset-1" data-pc-sidebar-theme="light" data-pc-sidebar-caption="true" data-pc-direction="ltr"> <!-- data-pc-theme="dark" -->

    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <!-- Logout Form -->
    <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>

    @include('inc.navbar.navbar')
    @include('inc.header.header')

    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">

            @yield('content')

        </div>
    </div>
    <!-- [ Main Content ] end -->

    @include('inc.footer')
    @include('inc.js')

</body>
</html>
