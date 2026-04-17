<!doctype html>
<html lang="en">
<head>

    <script>
    (function() {
        var state = localStorage.getItem('sidebar');

        if (state !== 'minimize') return;

        var observer = new MutationObserver(function(mutations, me) {

            var sidebar = document.querySelector('.pc-sidebar');

            if (sidebar) {

                sidebar.classList.add('pc-sidebar-hide');

                me.disconnect();
            }

        });

        observer.observe(document.documentElement, {
            childList: true
        });
    })();
    </script>

    <title>Sistem Informasi Rekam Medis</title>

    @include('inc.meta')

    @include('inc.css')

</head>
<body data-pc-preset="preset-1" data-pc-sidebar-theme="light" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme="light" >

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
</body>
</html>
