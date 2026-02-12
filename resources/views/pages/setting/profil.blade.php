@extends('layouts.index')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.6/dist/signature_pad.umd.min.js"></script>
<style>
    canvas {
    width: 200px;
    height: 200px;
    touch-action: none; /* penting untuk mencegah scroll saat tanda tangan */
    pointer-events: auto !important;
}
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="ti ti-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Setting</a></li>
                        <li class="breadcrumb-item" aria-current="page">Profil Pengguna</li>
                    </ul>
                </div>
                <div class="col-md-12 mt-2">
                    <div class="page-header-title">
                        <h2 class="mb-0">Profil Akun <b class="text-primary">{{ Auth::user()->LOGIN }}</b></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-sm-12">
            {{-- <div class="card bg-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 me-3">
                            <h3 class="text-white">Email Verification</h3>
                            <p class="text-white text-opacity-75 text-opa mb-0">Your email is not confirmed.
                                Please check your inbox.
                                <a href="#" class="link-light"><u>Resend confirmation</u></a>
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <img src="{{ asset('/images/application/img-accout-alert.png') }}" alt="img"
                                class="img-fluid wid-80" />
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="row">
                <div class="col-lg-5 col-xxl-3">
                    <div class="card overflow-hidden">
                        <div class="card-body position-relative">
                            <div class="text-center mt-3">
                                <div class="chat-avtar d-inline-flex mx-auto">
                                    <img class="rounded-circle img-fluid wid-90 img-thumbnail"
                                        src="{{ asset('/images/user.png') }}" alt="User image" />
                                    <i class="chat-badge bg-success me-2 mb-2"></i>
                                </div>
                                <h5 class="mb-0"> </h5>
                                <p class="text-muted text-sm">
                                    <h5>{{ Auth::user()->NAMA }}</h5>
                                    <span class="badge bg-light-primary text-uppercase">{{ auth()->user()->getRoleNames()->first() ?? 'USER' }}</span>
                                </p>
                                {{-- <ul class="list-inline mx-auto my-4">
                                    <li class="list-inline-item">
                                        <a href="#" class="avtar avtar-s text-white bg-dribbble">
                                            <i class="ti ti-brand-dribbble f-24"></i>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="#" class="avtar avtar-s text-white bg-amazon">
                                            <i class="ti ti-brand-figma f-24"></i>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="#" class="avtar avtar-s text-white bg-pinterest">
                                            <i class="ti ti-brand-pinterest f-24"></i>
                                        </a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="#" class="avtar avtar-s text-white bg-behance">
                                            <i class="ti ti-brand-behance f-24"></i>
                                        </a>
                                    </li>
                                </ul> --}}
                                <div class="row g-3 mt-3">
                                    <div class="col-7">
                                        <h5 class="mb-0">{{ Auth::user()->NIK }}</h5>
                                        <small class="text-muted">NIK</small>
                                    </div>
                                    <div class="col-5 border border-top-0 border-bottom-0 border-end-0">
                                        <h5 class="mb-0">{{ Auth::user()->NIP }}</h5>
                                        <small class="text-muted">NIP</small>
                                    </div>
                                    {{-- <div class="col-4">
                                        <h5 class="mb-0">4.5K</h5>
                                        <small class="text-muted">Members</small>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                        <div class="nav flex-column nav-pills list-group list-group-flush account-pills mb-0"
                            id="user-set-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link list-group-item list-group-item-action active" id="user-set-profile-tab"
                                data-bs-toggle="pill" href="#user-set-profile" role="tab"
                                aria-controls="user-set-profile" aria-selected="true">
                                <span class="f-w-500"><i class="ph-duotone ph-user-circle m-r-10"></i>Akun Pengguna</span>
                            </a>
                            {{-- <a class="nav-link list-group-item list-group-item-action" id="user-set-information-tab"
                                data-bs-toggle="pill" href="#user-set-information" role="tab"
                                aria-controls="user-set-information" aria-selected="false">
                                <span class="f-w-500"><i class="ph-duotone ph-clipboard-text m-r-10"></i>Personal
                                    Information</span>
                            </a>
                            <a class="nav-link list-group-item list-group-item-action" id="user-set-account-tab"
                                data-bs-toggle="pill" href="#user-set-account" role="tab"
                                aria-controls="user-set-account" aria-selected="false">
                                <span class="f-w-500"><i class="ph-duotone ph-notebook m-r-10"></i>Account
                                    Information</span>
                            </a> --}}
                            <a class="nav-link list-group-item list-group-item-action" id="user-set-passwort-tab"
                                data-bs-toggle="pill" href="#user-set-passwort" role="tab"
                                aria-controls="user-set-passwort" aria-selected="false">
                                <span class="f-w-500"><i class="ph-duotone ph-key m-r-10"></i>Ubah Password</span>
                            </a>
                            {{-- <a class="nav-link list-group-item list-group-item-action" id="user-set-email-tab"
                                data-bs-toggle="pill" href="#user-set-email" role="tab"
                                aria-controls="user-set-email" aria-selected="false">
                                <span class="f-w-500"><i class="ph-duotone ph-envelope-open m-r-10"></i>Email
                                    settings</span>
                            </a> --}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 col-xxl-9">
                    <div class="tab-content" id="user-set-tabContent">
                        <div class="tab-pane fade show active" id="user-set-profile" role="tabpanel" aria-labelledby="user-set-profile-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Identitas Diri</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item px-0 pt-0">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <p class="mb-1 text-muted">Username</p>
                                                    <p class="mb-0">{{ Auth::user()->LOGIN }}</p>
                                                </div>
                                                <div class="col-md-8">
                                                    <p class="mb-1 text-muted">Nama Lengkap</p>
                                                    <p class="mb-0">{{ $list['show']->NAMALENGKAP ?? $list['show']->NAMA }}</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item px-0">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <p class="mb-1 text-muted">Nomor Handphone</p>
                                                    <p class="mb-0">{{ $list['show']->NOHP }} {{ $list['show']->JENISNOHP ? '('.$list['show']->JENISNOHP.')' : '' }}</p>
                                                </div>
                                                <div class="col-md-8">
                                                    <p class="mb-1 text-muted">Tempat, Tanggal Lahir</p>
                                                    <p class="mb-0">{{ $list['show']->TEMPAT_LAHIR }}{{ $list['show']->TANGGAL_LAHIR ? ', ' . \Carbon\Carbon::parse($list['show']->TANGGAL_LAHIR)->translatedFormat('d F Y') : '' }}</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item px-0 pb-0">
                                            <p class="mb-1 text-muted">Alamat Lengkap</p>
                                            <p class="mb-0">{{ $list['show']->ALAMAT }}</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header p-3">
                                    <div class="d-sm-flex align-items-center justify-content-between ms-2">
                                        <h5>Tanda Tangan Elektronik</h5>
                                        <button class="btn btn-light-warning" onclick="showTTDpeg({{ Auth::user()->NIP }})"><i class="fas fa-sync me-1"></i> Refresh</button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <input type="hidden" id="idstorettd" value="">

                                    <div id="loading-ttd">
                                        <div class="spinner-grow align-middle me-2" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        Mengambil Data...
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 mb-3" id="show-petunjuk" hidden>
                                            <strong>Keterangan:</strong>
                                            <p>
                                                Tanda tangan ini digunakan sebagai pengganti tanda tangan basah pada dokumen pasien. Dengan menambahkan tanda tangan,
                                                Anda menyatakan persetujuan bahwa tanda tangan tersebut dapat digunakan secara resmi dalam sistem, dengan tetap menjamin
                                                aspek keamanan agar tidak disalahgunakan. Setiap tanda tangan yang dibuat wajib sesuai dengan ketentuan dan kesepakatan
                                                yang berlaku di Rumah Sakit. Variasi (6x) tanda tangan yang disimpan akan digunakan secara acak pada saat pembubuhan tanda tangan
                                                pada berkas klaim pasien.
                                            </p>
                                            <strong>Hal yang perlu diperhatikan:</strong>
                                            <p class="mb-0">
                                                <ol>
                                                    <li><mark><b>TANDA TANGAN HARUS FULL RAPAT ATAS BAWAH</b></mark> (<i><b>Memenuhi bagian atas sampai bawah, untuk bagian kiri dan kanan opsional</b></i>), tidak menyisakan <b>SPACE Kosong</b> di bagian Atas maupun Bawah TTE</li>
                                                    <li>Tanda tangan wajib diisi minimal 1x ttd atau dapat juga bervariasi (Lebih dari 1x ttd)</li>
                                                    <li>Perubahan tanda tangan dapat dilakukan pada masing-masing variasi yang tersedia</li>
                                                    <li>Tanda tangan baru tidak akan berpengaruh pada laporan yang sudah dibuat</li>
                                                </ol>
                                            </p>
                                            <strong>Petunjuk Pengisian:</strong>
                                            <p class="mb-0">
                                                <ol>
                                                    <li>Gunakan layar sentuh atau mouse</li>
                                                    <li>Gambar tanda tangan pada canvas</li>
                                                    <li>Klik "<span class="badge text-bg-warning"><i class="fas fa-sync me-1"></i> Refresh</span>" untuk menampilkan TTD</li>
                                                    <li>Klik "<span class="badge text-bg-danger"><i class="ti ti-writing-sign"></i></span>" untuk menghapus</li>
                                                    <li>Klik "<span class="badge text-bg-primary"><i class="fas fa-save me-1"></i> Simpan Tanda Tangan</span>" untuk menyimpan</li>
                                                </ol>
                                            </p>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3" id="preview-wrapper" hidden>
                                                <div class="row" id="preview-ttd-list"></div>

                                                <div class="mt-3 text-center">
                                                    <button type="button" id="btn-ubah-ttd" class="btn btn-warning">
                                                        <i class="fa fa-pen me-1"></i> Ubah TTD
                                                    </button>
                                                </div>
                                                {{-- <img id="preview-ttd-peg" src="" alt="Belum ada tanda tangan" style="max-width: 300px; border: 1px solid #ccc;" />
                                                <div class="mt-3">
                                                    <button type="button" id="btn-ubah-ttd" class="btn btn-warning me-2"><i class="fa fa-pen me-1"></i> Ubah TTD</button>
                                                    <a id="date-updated"></a>
                                                </div> --}}
                                            </div>

                                            <div class="row" id="paduser" hidden>
                                                @for ($i = 1; $i <= 6; $i++)
                                                    <div class="col-xl-4 col-md-6 col-sm-12">
                                                        <div class="mb-4 border rounded p-3">
                                                            <h6 class="mb-2">Tanda Tangan <span class="badge text-bg-secondary">Variasi {{ $i }}</span></h6>

                                                            <div class="position-relative overflow-hidden mb-3" style="width:100%; max-width:500px; height:200px;">
                                                                <canvas id="signature-pad-user-{{ $i }}" class="border rounded w-100 h-100"></canvas>

                                                                <div id="placeholder-ttd-user-{{ $i }}"
                                                                    class="position-absolute top-50 start-50 translate-middle text-muted"
                                                                    style="pointer-events:none; opacity:.3;">
                                                                    Tanda tangan ke-{{ $i }}
                                                                </div>

                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger position-absolute clear-user"
                                                                    data-index="{{ $i }}"
                                                                    style="top:10px; right:10px; z-index:10;">
                                                                    <i class="ti ti-writing-sign"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endfor

                                                <button class="btn btn-primary" onclick="storeTTDpeg()" id="btn-save-ttd">
                                                    <i class="fas fa-save me-1"></i> Simpan Tanda Tangan
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="user-set-passwort" role="tabpanel"
                            aria-labelledby="user-set-passwort-tab">
                            <div class="card alert alert-warning p-0">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 me-3">
                                            <h4 class="alert-heading">Perhatian!</h4>
                                            <p class="mb-2">Password Anda akan kadaluarsa setiap tahunnya. Disarankan untuk melakukan perubahan secara periodik.</p>
                                            <a href="#" class="alert-link"><u>Jangan memberikan password Anda kepada orang lain.</u></a>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <img src="{{ asset('/images/application/img-accout-password-alert.png') }}"
                                                alt="img" class="img-fluid wid-80" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header d-flex align-items-center justify-content-between py-3">
                                    <h5 class="mb-0">Ubah Password</h5>
                                    <h6 class="mb-0">{!! Auth::user()->TERAKHIR_UBAH_PASSWOD
                                        ? '<span class="badge text-bg-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Terakhir Ubah Password">'
                                            . \Carbon\Carbon::parse(Auth::user()->TERAKHIR_UBAH_PASSWOD)->translatedFormat('d F Y \P\u\k\u\l H:i') . ' WIB' . '</span>'
                                        : '' !!}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item pt-0 px-0">
                                            <div class="row mb-0">
                                                <label class="col-form-label col-md-4 col-sm-12 text-md-end">Password Sekarang <span class="text-danger">*</span>
                                                </label>
                                                <div class="col-md-8 col-sm-12">
                                                    <input type="password" class="form-control" id="password_lama" disabled/>
                                                    {{-- <div class="form-text"> Lupa Password? <a href="#" class="link-primary">Klik Disini</a> </div> --}}
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item px-0">
                                            <div class="row mb-0">
                                                <label class="col-form-label col-md-4 col-sm-12 text-md-end">Password Baru <span class="text-danger">*</span></label>
                                                <div class="col-md-8 col-sm-12">
                                                    <input type="password" class="form-control" id="password_baru" disabled/>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="list-group-item pb-0 px-0">
                                            <div class="row mb-0">
                                                <label class="col-form-label col-md-4 col-sm-12 text-md-end">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                                                <div class="col-md-8 col-sm-12">
                                                    <input type="password" class="form-control" id="password_baru_confirm" disabled/>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-footer text-end p-3">
                                    <button class="btn btn-link-secondary me-2" disabled>Kosongkan</button>
                                    <button class="btn btn-primary" disabled>Perbarui Password</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->

    <script>
        $(document).ready(function() {
            // showLoader();
            // refresh();
            const pegawaiNIP = "{{ Auth::user()->NIP }}"; // Dikirim dari controller ke blade
            if (pegawaiNIP) {
                showTTDpeg(pegawaiNIP);
            }
        });

        let padUsers = {};

        function showTTDpeg(NIP) {
            $('#paduser').prop('hidden', true);
            $('#show-petunjuk').prop('hidden', true);
            $('#preview-wrapper').prop('hidden', true);
            $('#preview-ttd-list').empty();

            $('#loading-ttd').html(`
                <div class="spinner-grow me-2"></div>
                Memproses Tanda Tangan...
            `);
            $('#idstorettd').val(NIP);

            $.ajax({
                url: `/api/pegawai/${NIP}/ttd`,
                type: 'GET',
                dataType: 'json',
                success: function(res) {

                    $('#loading-ttd').empty();

                    if (res.ttds && res.ttds.length > 0) {

                        res.ttds.forEach(function(ttd) {
                            $('#preview-ttd-list').append(`
                                <div class="col-md-4 text-center mb-3">
                                    <small class="text-muted">Variasi ${ttd.queue}</small>
                                    <img src="${ttd.signature_url}"
                                        class="img-fluid border rounded"
                                        style="max-height:150px;">
                                    <div class="small text-muted mt-1">
                                        ${moment(ttd.updated_at).format('DD MMM YYYY HH:mm')}
                                    </div>
                                </div>
                            `);
                        });

                        $('#preview-wrapper').prop('hidden', false);

                        $('#btn-ubah-ttd').off('click').on('click', function () {
                            $('#preview-wrapper').prop('hidden', true);
                            $('#show-petunjuk').prop('hidden', false);
                            $('#paduser').prop('hidden', false);

                            // 🔥 reset semua signature pad
                            padUsers = {};

                            // ⏱️ tunggu DOM benar-benar tampil
                            setTimeout(() => {
                                tampilkanCanvasTTD();
                            }, 50);
                        });

                    } else {
                        // belum ada TTD sama sekali
                        $('#paduser').prop('hidden', false);
                        $('#show-petunjuk').prop('hidden', false);
                        tampilkanCanvasTTD();
                    }
                }
            });
        }

        function tampilkanCanvasTTD() {
            for (let i = 1; i <= 6; i++) {
                // if (padUsers[i]) continue;

                const canvas = document.getElementById('signature-pad-user-' + i);
                if (!canvas) continue;

                padUsers[i] = new SignaturePad(canvas);
                resizeCanvasResponsive(canvas, padUsers[i]);

                padUsers[i].onBegin = function () {
                    $('#placeholder-ttd-user-' + i).hide();
                };
            }

            $('.clear-user').off('click').on('click', function () {
                const idx = $(this).data('index');
                padUsers[idx].clear();
                $('#placeholder-ttd-user-' + idx).show();
            });

            $(window).off('resize.user');
            $(window).on('resize.user', function () {
                for (let i = 1; i <= 6; i++) {
                    const canvas = document.getElementById('signature-pad-user-' + i);
                    if (canvas) resizeCanvasResponsive(canvas, padUsers[i]);
                }
            });
        }

        function resizeCanvasResponsive(canvas, pad) {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);

            // simpan dulu konten lama
            const data = pad.toData();

            const parentWidth = canvas.parentElement.offsetWidth;
            const width = parentWidth > 500 ? 500 : parentWidth;
            const height = 200;

            canvas.style.width = width + "px";
            canvas.style.height = height + "px";

            canvas.width = width * ratio;
            canvas.height = height * ratio;
            canvas.getContext("2d").scale(ratio, ratio);

            // restore konten lama
            pad.fromData(data);
        }

        function storeTTDpeg() {
            const nip = $('#idstorettd').val().trim();
            let signatures = [];

            for (let i = 1; i <= 6; i++) {
                if (padUsers[i] && !padUsers[i].isEmpty()) {
                    signatures.push({
                        queue: i,
                        image: padUsers[i].toDataURL('image/png')
                    });
                }
            }

            if (signatures.length === 0) {
                alert('Minimal 1 tanda tangan harus diisi');
                return;
            }

            $('#btn-save-ttd').prop('disabled', true)
                .find('i').addClass('fa-spin fa-sync');

            $.ajax({
                url: "{{ route('api.pegawai.storeTtdPeg') }}",
                method: "POST",
                contentType: "application/json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: JSON.stringify({
                    nip: nip,
                    signatures: signatures
                }),
                success: function (res) {
                    iziToast.success({
                        title: 'Berhasil',
                        message: 'Tanda tangan berhasil disimpan'
                    });
                    showTTDpeg(nip);
                },
                complete: function () {
                    $('#btn-save-ttd').prop('disabled', false)
                        .find('i').removeClass('fa-spin fa-sync');
                }
            });
        }
    </script>
@endsection
