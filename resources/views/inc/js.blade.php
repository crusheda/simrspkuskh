<!-- [Page Specific JS] start -->
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.3/dist/sweetalert2.all.min.js"></script>
{{-- <script src="{{ asset('js/plugins/apexcharts.min.js') }}"></script> --}}
<script src="{{ asset('js/plugins/jsvectormap.min.js') }}"></script>
<script src="{{ asset('js/plugins/world.js') }}"></script>
<script src="{{ asset('js/plugins/world-merc.js') }}"></script>
{{-- <script src="{{ asset('js/widgets/earnings-users-chart.js') }}"></script> --}}
{{-- <script src="{{ asset('js/widgets/world-map-markers.js') }}"></script> --}}
<!-- [Page Specific JS] end -->

<!-- Required Js -->
<script src="{{ asset('js/plugins/popper.min.js') }}"></script>
<script src="{{ asset('js/plugins/simplebar.min.js') }}"></script>
<script src="{{ asset('js/plugins/bootstrap.min.js') }}"></script>
{{-- <script src="{{ asset('js/plugins/i18next.min.js') }}"></script>
<script src="{{ asset('js/plugins/i18nextHttpBackend.min.js') }}"></script> --}}
<script src="{{ asset('js/icon/custom-font.js') }}"></script>
<script src="{{ asset('js/script.js') }}"></script>
<script src="{{ asset('js/theme.js') }}"></script>
{{-- <script src="{{ asset('js/multi-lang.js') }}"></script> --}}
<script src="{{ asset('js/plugins/feather.min.js') }}"></script>
{{-- <script src="{{ asset('js/sweetalert2-11.js') }}"></script> --}}
<script src="{{ asset('js/iziToast.js') }}"></script>

{{-- ADDON --}}
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.6/dist/signature_pad.umd.min.js"></script>
<script src="{{ asset('js/plugins/simple-datatables.js') }}"></script>
<script src="{{ asset('js/plugins/choices.min.js') }}"></script>
<script src="{{ asset('js/plugins/ckeditor/classic/ckeditor.js') }}"></script>

{{-- DIAGRAM --}}
{{-- <script src="{{ asset('js/plugins/apexcharts.min.js') }}"></script> --}}

{{-- CDN --}}
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
{{-- <script src="{{ asset('js/jquery.min.js') }}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

{{-- DAYJS --}}
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/relativeTime.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/localizedFormat.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/locale/id.js"></script>
<script>
    dayjs.extend(dayjs_plugin_relativeTime);
    dayjs.extend(dayjs_plugin_localizedFormat);
    dayjs.locale('id');
</script>

{{-- MANUAL JQUERY / JAVASCRIPT --}}
    {{-- DATA PC THEME START --}}
    <script>
        function layout_change(theme) {
            console.log('Theme changed to : '+theme);
            // ubah attribute body
            document.body.setAttribute("data-pc-theme", theme);

            // simpan pilihan ke localStorage agar persist
            localStorage.setItem("theme", theme);
            setLogoByTheme(theme); // update logo juga
        }

        function fullscreenPage() {

            let elem = document.documentElement; // seluruh halaman

            if (!document.fullscreenElement) {

                if (elem.requestFullscreen) {
                    elem.requestFullscreen();
                } else if (elem.webkitRequestFullscreen) { // Safari
                    elem.webkitRequestFullscreen();
                } else if (elem.msRequestFullscreen) { // IE11
                    elem.msRequestFullscreen();
                } else {
                    document.body.classList.toggle("fake-fullscreen");
                }

            } else {

                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }

            }

        }
    </script>
    {{-- DATA PC THEME END --}}

{{-- <script>
    layout_change('dark');
</script>

<script>
    layout_sidebar_change('light');
</script>

<script>
    change_box_container('false');
</script>

<script>
    layout_caption_change('true');
</script>

<script>
    layout_rtl_change('false');
</script>

<script>
    preset_change('preset-1');
</script> --}}


    <script>
        if (!!document.querySelector('.customer-body')) {
            new SimpleBar(document.querySelector('.customer-body'));
        }
    </script>
