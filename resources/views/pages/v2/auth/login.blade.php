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

    <meta name="description"
          content="Sistem Manajemen Rumah Sakit PKU Muhammadiyah Sukoharjo">

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
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Autentikasi SIRMED v2</title>

    <link rel="icon"
          type="image/png"
          href="{{ asset('images/logo/logo.png') }}">

    <link rel="apple-touch-icon"
          sizes="180x180"
          href="{{ asset('images/logo/logo.png') }}">

    <link rel="stylesheet" href="{{ asset('assets/v2/auth/css/style.css') }}">

    {{-- Bootstrap 5 untuk Modal --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/v2/fonts/fontawesome.css') }}">
</head>

<body>

<main class="cl-login04">

    {{-- =========================================================
        BACKGROUND
    ========================================================== --}}
    <img class="cl-login04__bg"
         src="{{ asset('images/pku/bg.png') }}"
         width="1200"
         height="800"
         alt=""
         style="opacity:20%">

    {{-- =========================================================
        INTRO
    ========================================================== --}}
    <div class="cl-login04__intro">

        {{-- Logo SIRMED --}}
        <a class="cl-login04__brand"
           href="{{ route('v2.dashboard') }}"
           aria-label="Sirmed v2"
           style="margin-right:20px">

            <img src="{{ asset('images/logo/logo.png') }}"
                 width="32"
                 height="32"
                 alt="SIRMED">

        </a>

        {{-- Logo SIMGOS --}}
        <a class="cl-login04__brand"
           href="http://192.168.1.2/"
           target="_blank"
           aria-label="Simgos v2"
           style="margin-right:20px">

            <img src="{{ asset('assets/v2/images/simgos.png') }}"
                 width="29"
                 height="29"
                 alt="SIMGOS">

        </a>

        <p class="cl-login04__kicker">
            Sistem Informasi Rekam Medis Elektronik
        </p>

        <p class="cl-login04__headline"
           style="margin-bottom:15px">

            SIR<b style="color:#00B3ED">MED</b> v.2

        </p>

        <p class="cl-login04__kicker"
           style="margin-top:0">

            RS PKU Muhammadiyah Sukoharjo

        </p>

        <ul class="cl-login04__chips"
            aria-label="System Information">

            <li>
                <span style="background:#00B3ED;margin-right:5px"></span>
                Digital Rekam Medis
            </li>

            <li>
                <span style="background:#6CC40E;margin-right:5px"></span>
                Bridging Simgos v2
            </li>

        </ul>

    </div>


    {{-- =========================================================
        LOGIN
    ========================================================== --}}
    <section class="cl-login04__glass"
             aria-labelledby="cl-login04-title">

        <div data-view>

            <h1 class="cl-login04__title"
                id="cl-login04-title">

                Sign in.

            </h1>

            <p class="cl-login04__sub">
                Silakan masuk menggunakan akun SIMGOS Anda.
            </p>


            {{-- =================================================
                ALERT ERROR
                Hanya satu pesan di atas input
            ================================================== --}}
            @if(session('error') || $errors->any())

                <div class="cl-login04__error"
                     style="display:block;margin-bottom:15px">

                    {{ session('error') ?? $errors->first() }}

                </div>

            @endif


            {{-- =================================================
                FORM LOGIN LARAVEL
            ================================================== --}}
            <form class="cl-login04__form"
                  method="POST"
                  action="{{ route('login') }}" data-form>

                @csrf


                {{-- USERNAME --}}
                <div class="cl-login04__field">

                    <label class="cl-login04__label"
                           for="cl-login04-email">

                        Username

                    </label>

                    <input class="cl-login04__input"
                           id="cl-login04-email"
                           name="name"
                           type="text"
                           autocomplete="username"
                           value="{{ old('name') }}"
                           placeholder="e.g. sunaryo"
                           autofocus
                           required>

                </div>


                {{-- PASSWORD --}}
                <div class="cl-login04__field">

                    <label class="cl-login04__label"
                           for="cl-login04-pw">

                        Password

                    </label>

                    <div class="cl-login04__pw">

                        <input class="cl-login04__input"
                               id="cl-login04-pw"
                               name="password"
                               type="password"
                               autocomplete="current-password"
                               placeholder="..."
                               required>

                        {{-- PASSWORD TOGGLE --}}
                        <button class="cl-login04__eye"
                                type="button"
                                id="togglePassword"
                                aria-label="Tampilkan password"
                                aria-pressed="false"
                                aria-controls="cl-login04-pw">

                            {{-- Eye --}}
                            <svg class="cl-login04__eye-on"
                                 viewBox="0 0 24 24"
                                 width="20"
                                 height="20"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="1.8"
                                 stroke-linecap="round"
                                 stroke-linejoin="round"
                                 aria-hidden="true">

                                <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/>
                                <circle cx="12" cy="12" r="3"/>

                            </svg>

                            {{-- Eye Off --}}
                            <svg class="cl-login04__eye-off"
                                 viewBox="0 0 24 24"
                                 width="20"
                                 height="20"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="1.8"
                                 stroke-linecap="round"
                                 stroke-linejoin="round"
                                 aria-hidden="true">

                                <path d="M3 3l18 18M10.6 5.1A10.8 10.8 0 0 1 12 5c6.4 0 10 7 10 7a17.6 17.6 0 0 1-3.2 4.1M6.6 6.6C3.9 8.3 2 12 2 12s3.6 7 10 7a9.8 9.8 0 0 0 5.4-1.6M9.9 9.9a3 3 0 0 0 4.2 4.2"/>

                            </svg>

                        </button>

                    </div>

                </div>


                {{-- =================================================
                    REMEMBER + FORGOT PASSWORD
                ================================================== --}}
                <div class="cl-login04__row">

                    <label class="cl-login04__check">

                        <input type="checkbox"
                               name="remember"
                               value="1"
                               {{ old('remember') ? 'checked' : '' }}>

                        <span>
                            Ingat Saya
                        </span>

                    </label>


                    <a class="cl-login04__link"
                       href="{{ route('lupapassword.index') }}">

                        Lupa Password?

                    </a>

                </div>


                {{-- =================================================
                    SUBMIT
                ================================================== --}}
                <button class="cl-login04__submit btn btn-info text-white" type="submit">
                    Masuk

                    <svg viewBox="0 0 24 24"
                        width="18"
                        height="18"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true">
                        <path d="M5 12h14M13 6l6 6-6 6"/>
                    </svg>
                </button>

            </form>


            {{-- =================================================
                ACCOUNT INFORMATION
            ================================================== --}}
            <p class="cl-login04__foot">

                Belum memiliki Akun?

                <a class="cl-login04__link"
                   href="#"
                   data-bs-toggle="modal"
                   data-bs-target="#hubsdi">

                    Buat Akun Baru

                </a>

            </p>

        </div>


        {{-- FOOTER --}}
        <p class="cl-login04__demo">
            @ Tim IT RS
        </p>

    </section>

</main>


{{-- =========================================================
    MODAL INFORMASI AKUN
========================================================== --}}
<div class="modal fade"
     id="hubsdi"
     tabindex="-1"
     aria-labelledby="hubsdiLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content">

            {{-- HEADER --}}
            <div class="modal-header">

                <h5 class="modal-title"
                    id="hubsdiLabel">

                    ✆ Daftar Nomor Yang Bisa Dihubungi

                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup">
                </button>

            </div>


            {{-- BODY --}}
            <div class="modal-body">

                <h6 class="mb-3">

                    Masalah <mark>Terkait Akun</mark>
                    silakan Hubungi No.Telp Bagian SDI:

                    <b class="text-primary">
                        188
                    </b>

                    (Hanya Disaat Jam Kerja)

                </h6>


                <h6>
                    Nomor Bagian SDI:
                </h6>

                <ul>

                    <li>
                        Novita Yuliani, S.KM, M.Kes
                        (<b>Whatsapp</b>:
                        <b>089689514960</b>)
                    </li>

                    <li>
                        Kholid Hidayat Al-Khoiri, S.Psi
                        (<b>Whatsapp</b>:
                        <b>0882003805027</b>)
                    </li>

                    <li>
                        Sri Suryani, SM
                        (<b>Whatsapp</b>:
                        <b>081330795309</b>)
                    </li>

                </ul>


                <h6 class="mb-0">

                    Masalah <mark>Teknis Sistem</mark>
                    silakan No.Telp IT:

                    <b class="text-primary">102</b>
                    /
                    <b class="text-primary">193</b>

                </h6>

            </div>


            {{-- FOOTER --}}
            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                    Tutup

                </button>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
    BOOTSTRAP JS
========================================================== --}}
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- <script src="{{ asset('assets/v2/auth/js/snippet.js') }}"></script> --}}

<script>

    $(function () {
        const $form = $('[data-form]');
        const $btn = $form.find('button[type="submit"]');

        if (!$form.length || !$btn.length) return;

        $form.on('submit', function (e) {

            /*
            |--------------------------------------------------------------------------
            | VALIDASI FIELD
            |--------------------------------------------------------------------------
            */

            const $fields = $form.find('[data-field]');
            let valid = true;
            let $firstInvalid = null;

            $fields.each(function () {

                const $field = $(this);
                const $input = $field.find('input');

                if (!$input.length) return;

                $field.attr('data-touched', '1');

                const value = $.trim($input.val());

                if ($input.prop('required') && !value) {

                    valid = false;

                    $field.addClass('is-error');
                    $input.attr('aria-invalid', 'true');

                    if (!$firstInvalid) {
                        $firstInvalid = $input;
                    }

                    return;
                }

                $field.removeClass('is-error');
                $input.removeAttr('aria-invalid');
            });

            /*
            |--------------------------------------------------------------------------
            | LOGIN TIDAK VALID
            |--------------------------------------------------------------------------
            */

            if (!valid) {

                e.preventDefault();

                const $live = $('[data-live]');

                if ($live.length) {
                    $live.text('Username dan password wajib diisi.');
                }

                if ($firstInvalid) {
                    $firstInvalid.trigger('focus');
                }

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | LOGIN VALID
            |--------------------------------------------------------------------------
            | Jangan menggunakan e.preventDefault().
            | Form akan tetap dikirim ke Laravel.
            |--------------------------------------------------------------------------
            */

            console.log('Login valid. Form dikirim ke Laravel.');

            /*
            |--------------------------------------------------------------------------
            | DISABLE BUTTON
            |--------------------------------------------------------------------------
            */

            $btn.prop('disabled', true);

            /*
            |--------------------------------------------------------------------------
            | SIMPAN HTML ASLI
            |--------------------------------------------------------------------------
            */

            $btn.data('original-html', $btn.html());

            /*
            |--------------------------------------------------------------------------
            | TAMPILKAN LOADING
            |--------------------------------------------------------------------------
            */

            $btn.html(`
                <i class="fas fa-sync fa-spin me-1" style="font-size:12pt"></i>
                Memproses...
            `);

            /*
            |--------------------------------------------------------------------------
            | TIDAK ADA e.preventDefault()
            |--------------------------------------------------------------------------
            | Browser akan melanjutkan submit POST ke route Laravel.
            |--------------------------------------------------------------------------
            */
        });
    });
</script>


{{-- =========================================================
    PASSWORD TOGGLE
========================================================== --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('cl-login04-pw');
    const button = document.getElementById('togglePassword');

    if (!input || !button) return;

    button.addEventListener('click', function () {

        const show = input.type === 'password';

        input.type = show ? 'text' : 'password';

        button.setAttribute('aria-pressed', show ? 'true' : 'false');

        button.setAttribute(
            'aria-label',
            show ? 'Sembunyikan password' : 'Tampilkan password'
        );

    });

});
</script>

</body>
</html>
