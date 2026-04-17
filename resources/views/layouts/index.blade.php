<!doctype html>
<html lang="en">
<head>
    <script>
    (function() {

        var sidebarState = localStorage.getItem('sidebar');
        var savedTheme = localStorage.getItem("theme") || "light";

        function setLogoByTheme(theme) {
            const logo = document.getElementById("app-logo");
            if (!logo) return;

            if (theme === "dark") {
                logo.src = "{{ asset('images/logo/logoname_w.png') }}";
            } else {
                logo.src = "{{ asset('images/logo/logoname.png') }}";
            }
        }

        var observer = new MutationObserver(function(mutations, me) {

            // ===== APPLY SIDEBAR =====
            if (sidebarState === 'minimize') {
                var sidebar = document.querySelector('.pc-sidebar');
                if (sidebar) {
                    sidebar.classList.add('pc-sidebar-hide');
                }
            }

            // ===== APPLY THEME =====
            if (document.body) {
                document.body.setAttribute("data-pc-theme", savedTheme);
                setLogoByTheme(savedTheme);
            }

            // stop kalau semua sudah siap
            if (document.body && document.querySelector('.pc-sidebar')) {
                me.disconnect();
            }

        });

        observer.observe(document.documentElement, {
            childList: true,
            subtree: true
        });

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

    <script>
    $(function() {

        $('#sidebar-hide, #mobile-collapse').on('click', function() {

            setTimeout(function() {
                if ($('.pc-sidebar').hasClass('pc-sidebar-hide')) {
                    localStorage.setItem('sidebar', 'minimize');
                } else {
                    localStorage.setItem('sidebar', 'open');
                }
            }, 50);

        });

    });
    </script>

    <script>
        // RUNNING SERVICE WORKER
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('/sw.js')
                .then(function(registration) {
                    console.log('Service Worker registered:', registration.scope);
                })
                .catch(function(error) {
                    console.log('Service Worker registration failed:', error);
                });
            });
        }

        let deferredPrompt;

        window.addEventListener('beforeinstallprompt', (e) => {

            console.log("Install available");

            e.preventDefault();
            deferredPrompt = e;

            document.getElementById("installBtn").style.display = "block";

        });

        document.getElementById("installBtn").addEventListener("click", async () => {

            if (!deferredPrompt) return;

            deferredPrompt.prompt();

            const { outcome } = await deferredPrompt.userChoice;

            console.log(outcome);

            deferredPrompt = null;

        });
    </script>

</body>
</html>
