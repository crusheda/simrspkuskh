<script>
(function () {

    function getCookie(name) {
        const match = document.cookie.match(
            new RegExp('(^| )' + name + '=([^;]+)')
        );
        return match ? match[2] : null;
    }

    const theme = getCookie('theme');

    document.documentElement.setAttribute(
        'data-bs-theme',
        theme ||
        (window.matchMedia('(prefers-color-scheme: dark)').matches
            ? 'dark'
            : 'light')
    );

})();
</script>

<link rel="preconnect" href="https://fonts.googleapis.com/">
<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&amp;display=swap"
    rel="stylesheet">

<link rel="stylesheet" href="{{ asset('v2/libs/flaticon/css/all/all.css') }}">
<link rel="stylesheet" href="{{ asset('v2/libs/lucide/lucide.css') }}">
{{-- <link rel="stylesheet" href="{{ asset('v2/libs/fontawesome/css/all.min.css') }}"> --}}
<link rel="stylesheet" href="{{ asset('v2/libs/simplebar/simplebar.css') }}">
<link rel="stylesheet" href="{{ asset('v2/libs/node-waves/waves.css') }}">
<link rel="stylesheet" href="{{ asset('v2/libs/bootstrap-select/css/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('v2/libs/flatpickr/flatpickr.min.css') }}">

<link rel="stylesheet" href="{{ asset('v2/libs/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('v2/libs/tagify/tagify.css') }}">
<link rel="stylesheet" href="{{ asset('v2/css/styles.css') }}">
{{-- <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}" /> --}}
{{-- <link rel="stylesheet" href="{{ asset('v2/css/styles-ui.css') }}"> --}}

<!-- Icons Css +_+ -->
<link href="{{ asset('v2/css/icons.css') }}" rel="stylesheet" >
<!-- [Inter Font] https://rsms.me/inter/ -->
<link rel="stylesheet" href="{{ asset('v2/fonts/inter/inter.css') }}" id="main-font-link">
<!-- [phosphor Icons] https://phosphoricons.com/ -->
<link rel="stylesheet" href="{{ asset('v2/fonts/phosphor/duotone/style.css') }}">
<!-- [Tabler Icons] https://tablericons.com -->
<link rel="stylesheet" href="{{ asset('v2/fonts/tabler-icons.min.css') }}">
<!-- [Font Awesome Icons] https://fontawesome.com/icons -->
<link rel="stylesheet" href="{{ asset('v2/fonts/fontawesome.css') }}">
<!-- [Material Icons] https://fonts.google.com/icons -->
<link rel="stylesheet" href="{{ asset('v2/fonts/material.css') }}">

<script src="{{ asset('js/jquery.min.js') }}"></script>
