<!doctype html>
<html lang="en">
<head>

    <title>Lupa Password | SIRMED</title>

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

    <!-- [Google Font : Public Sans] icon -->
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&amp;display=swap"
        rel="stylesheet" />
    <!-- [phosphor Icons] https://phosphoricons.com/ -->
    <link rel="stylesheet" href="{{ asset('fonts/phosphor/duotone/style.css') }}" />
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="{{ asset('fonts/tabler-icons.min.css') }}" />
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="{{ asset('fonts/feather.css') }}" />
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="{{ asset('fonts/fontawesome.css') }}" />
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="{{ asset('fonts/material.css') }}" />
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('css/style-preset.css') }}" />

    <script src="{{ asset('js/jquery.min.js') }}"></script>
</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-preset="preset-1" data-pc-sidebar-theme="light" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme="light">

    <div class="auth-main v1">
        <div class="auth-wrapper">
            <div class="auth-form">
                <div class="card my-5">
                    <div class="card-body">
                        <form method="POST" action="{{ route('lupapassword.update') }}">
                            @csrf
                            <div class="text-center mb-3">
                                <center><img class="mb-3" src="{{ asset('images/pku/logo-kop-blue.png') }}" width="300" alt=""></center>
                                <h3 class="mb-1">LUPA PASSWORD</h3>
                                <h6><b class="text-success">SIMGOS</b> | <b class="text-info">SIRMED</b></h6>
                            </div>
                            <hr>
                            @if ($errors->any())
                                <div class="alert alert-danger mb-3">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if (session('message'))
                                <div class="alert alert-success mb-3">
                                    {{ session('message') }}
                                </div>
                            @endif
                            <div class="mb-3">
                                <div class="form-group">
                                    <label class="form-label">NIP (Nomor Induk Pegawai) : <a data-bs-toggle="modal" data-bs-target="#hubsdi" href="javascript:void(0);">Lupa NIP Anda?</a></label>
                                    <input type="number" class="form-control" name="nip" id="nip" placeholder="Tuliskan NIP Anda (7 Digit)" autofocus required/>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-group">
                                    <label class="form-label">Username : <a data-bs-toggle="modal" data-bs-target="#hubsdi" href="javascript:void(0);">Belum Memiliki Akun?</a></label>
                                    <input type="text" class="form-control" name="username" id="username" placeholder="Tuliskan Username Anda" required/>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-group">
                                    <label class="form-label">Password Baru :</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="password" id="password" placeholder="Tuliskan Password Baru Anda" required />
                                        <button href="javascript:void(0);" type="button" class="btn btn-outline-primary" id="open-password" style="border-color: #ced4da;border-top-right-radius:8px;border-bottom-right-radius:8px">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-group">
                                    <label class="form-label">Konfirmasi Password Baru :</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Konfirmasi Password Baru Anda" required />
                                        <button href="javascript:void(0);" type="button" class="btn btn-outline-primary" id="open-password-confirm" style="border-color: #ced4da;border-top-right-radius:8px;border-bottom-right-radius:8px">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-danger">Submit Password Baru</button>
                            </div>
                            <div class="saprator my-3">
                                <span>Atau lanjutkan dengan</span>
                            </div>
                            <div class="d-grid mt-3">
                                <a class="btn btn-light-info mb-3" href="{{ route('dashboard') }}">Login SIRMED v.1.0</a>
                                <a class="btn btn-light-success" href="http://192.168.1.2/apps/SIMpel">Login SIMGOS v.2</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div id="hubsdi" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="catatanLabel">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="catatanLabel">Daftar Nomor Bagian SDI (Sumber Daya Insani)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <h6 class="mb-3">No.Telp Kantor : Hubungi <b class="text-primary">188</b> (Hanya Disaat Jam Kerja)</h6>
                    <h6>Nomor Lainnya :</h6>
                    <ul>
                        <li>Novita Yuliani, S.KM, M.Kes (<b class="text-success">Whatsapp</b> : <b class="text-danger">089689514960</b>)</li>
                        <li>Kholid Hidayat Al-Khoiri, S.Psi (<b class="text-success">Whatsapp</b> : <b class="text-danger">0882003805027</b>)</li>
                        <li>Sri Suryani, SM (<b class="text-success">Whatsapp</b> : <b class="text-danger">081330795309</b>)</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            // 🔹 Batasi maksimal 7 digit
            function limitNIP(input) {
                if (input.value.length > 7) {
                    input.value = input.value.slice(0, 7);
                }
            }

            // Jalankan limitNIP saat user mengetik
            $('#nip').on('input', function() {
                limitNIP(this);
            });

            // 🔹 Validasi sebelum submit
            $('form').on('submit', function(e) {
                const nipInput = document.getElementById('nip');
                const errorDiv = document.getElementById('nipError');

                if (nipInput.value.length !== 7) {
                    e.preventDefault(); // hentikan submit
                    errorDiv.style.display = 'block';
                    nipInput.classList.add('is-invalid');
                } else {
                    errorDiv.style.display = 'none';
                    nipInput.classList.remove('is-invalid');
                }
            });

            // 🔹 Toggle password visibility
            $("#open-password").on("click", function() {
                const x = $("#password");
                const icon = $(this).find("i");

                if (x.attr("type") === "password") {
                    x.attr("type", "text");
                    icon.removeClass("fa-eye").addClass("fa-eye-slash");
                } else {
                    x.attr("type", "password");
                    icon.removeClass("fa-eye-slash").addClass("fa-eye");
                }
            });

            $("#open-password-confirm").on("click", function() {
                const x = $("#password_confirmation");
                const icon = $(this).find("i");

                if (x.attr("type") === "password") {
                    x.attr("type", "text");
                    icon.removeClass("fa-eye").addClass("fa-eye-slash");
                } else {
                    x.attr("type", "password");
                    icon.removeClass("fa-eye-slash").addClass("fa-eye");
                }
            });
        });
    </script>
    <!-- [ Main Content ] end -->
    <!-- Required Js -->
    <script src="{{ asset('js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/plugins/i18next.min.js') }}"></script>
    <script src="{{ asset('js/plugins/i18nextHttpBackend.min.js') }}"></script>
    <script src="{{ asset('js/icon/custom-font.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/theme.js') }}"></script>
    {{-- <script src="{{ asset('js/multi-lang.js') }}"></script> --}}
    <script src="{{ asset('js/plugins/feather.min.js') }}"></script>

    <script>
        layout_change('light');
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
    </script>

</body>
</html>
