@extends('layouts.v2.index')

@section('content')

<div class="container-fluid">

    <!-- [ breadcrumb ] start -->
    <div class="app-page-head d-flex align-items-center justify-content-between">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('v2.dashboard') }}">
                        <i class="fi fi-rr-home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item" aria-current="page">Setting</li>
                <li class="breadcrumb-item active" aria-current="page">Profil Pengguna</li>
            </ol>
        </nav>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-lg-5 col-xxl-3">
            <div class="card">
                <div class="card-header pb-0 border-0">
                    <div class="mb-4 border-bottom pb-4 d-flex border-0 justify-content-between align-items-start">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-xl rounded-circle position-relative me-3">
                                <img src="{{ asset('/images/user.png') }}" alt="">
                                {{-- <a href="javascript:void(0);"
                                    class="avatar avatar-xxs bg-primary rounded-circle text-white position-absolute top-0 mt-n1 me-n1 end-0">
                                    <i class="fi fi-rr-camera text-1xs"></i>
                                </a> --}}
                            </div>
                            <div class="clearfix">
                                <h4 class="fw-bold mb-0">{{ Auth::user()->LOGIN }}</h4>
                                <small class="mb-0 text-uppercase">{{ auth()->user()->getRoleNames()->first() ?? 'USER' }}</small>
                            </div>
                        </div>
                        <button class="btn btn-white btn-sm btn-shadow btn-icon waves-effect" type="button" onclick="showUbahBiodata()">
                            <i class="fi fi-rr-pencil"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="">
                        <div class="mb-3">
                            <h4 class="card-title mb-0">Biodata Diri</h4>
                        </div>
                        <div class="clearfix">
                            <div class="mb-3">
                                <span class="mb-1">Nama Lengkap</span>
                                <p class="text-dark fw-semibold mb-0">
                                    {{ $list['show']->NAMALENGKAP ?? $list['show']->NAMA }}</p>
                            </div>
                            <div class="mb-3">
                                <span class="mb-1">NIP</span>
                                <p class="text-dark fw-semibold mb-0">{{ Auth::user()->NIP }}</p>
                            </div>
                            <div class="mb-3">
                                <span class="mb-1">NIK</span>
                                <p class="text-dark fw-semibold mb-0">{{ Auth::user()->NIK }}</p>
                            </div>
                            <div class="mb-3">
                                <span class="mb-1">Nomor HP</span>
                                <p class="text-dark fw-semibold mb-0">{{ $list['show']->NOHP }}
                                    {{ $list['show']->JENISNOHP ? '(' . $list['show']->JENISNOHP . ')' : '' }}</p>
                            </div>
                            <div class="mb-3">
                                <span class="mb-1">Tanggal Lahir</span>
                                <p class="text-dark fw-semibold mb-0">
                                    {{ $list['show']->TEMPAT_LAHIR }}{{ $list['show']->TANGGAL_LAHIR ? ', ' . \Carbon\Carbon::parse($list['show']->TANGGAL_LAHIR)->translatedFormat('d F Y') : '' }}
                                </p>
                            </div>
                            <div class="mb-0">
                                <span class="mb-1">Alamat Lengkap</span>
                                <p class="text-dark fw-semibold mb-0">{{ $list['show']->ALAMAT }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="mb-4 border-bottom pb-4">
                        <div class="mb-3">
                            <h4 class="card-title mb-0">Social Media Links</h4>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="javascript:void(0);"
                                class="btn btn-icon btn-sm btn-subtle-facebook waves-effect waves-light">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            <a href="javascript:void(0);"
                                class="btn btn-icon btn-sm btn-subtle-twitter waves-effect waves-light">
                                <i class="fa-brands fa-x-twitter"></i>
                            </a>
                            <a href="javascript:void(0);"
                                class="btn btn-icon btn-sm btn-subtle-instagram waves-effect waves-light">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                            <a href="javascript:void(0);"
                                class="btn btn-icon btn-sm btn-subtle-linkedin waves-effect waves-light">
                                <i class="fa-brands fa-linkedin"></i>
                            </a>
                        </div>
                    </div> --}}

                </div>
            </div>
        </div>
        <div class="col-lg-7 col-xxl-9">
            <div class="card" id="ubah-biodata" hidden>
                <div class="card-header">
                    <h4 class="card-title">Ubah Biodata</h4>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <label class="form-label">NIP (<b class="text-yellow">Terisi Otomatis</b>)</label>
                                <input type="number" class="form-control" value="{{ Auth::user()->NIP }}"
                                    disabled>
                            </div>
                            <div class="col-md-10">
                                <label class="form-label">Nama Lengkap + Gelar</label>
                                <input type="text" class="form-control"
                                    value="{{ $list['show']->NAMALENGKAP ?? $list['show']->NAMA }}" disabled>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">NIK</label>
                                <input type="number" class="form-control" value="{{ Auth::user()->NIK }}"
                                    disabled>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Nomor HP</label>
                                <input type="tel" class="form-control" value="{{ $list['show']->NOHP }}"
                                    disabled>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tempat Lahir</label>
                                <input type="text" class="form-control"
                                    value="{{ $list['show']->TEMPAT_LAHIR }}" disabled>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control"
                                    value="{{ $list['show']->TANGGAL_LAHIR }}" disabled>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Alamat Lengkap</label>
                                <textarea row="2" class="form-control" disabled>{{ $list['show']->ALAMAT }}</textarea>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-success waves-effect waves-light" disabled><i class="fas fa-save me-1"></i> Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header p-3">
                    <div class="d-sm-flex align-items-center justify-content-between ms-2">
                        <h5>Tanda Tangan Elektronik</h5>
                        <button class="btn btn-outline-warning" onclick="showTTDpeg({{ Auth::user()->NIP }})"><i
                                class="fas fa-sync me-1"></i> Refresh</button>
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
                                Tanda tangan ini digunakan sebagai pengganti tanda tangan basah pada dokumen pasien.
                                Dengan menambahkan tanda tangan,
                                Anda menyatakan persetujuan bahwa tanda tangan tersebut dapat digunakan secara resmi
                                dalam sistem, dengan tetap menjamin
                                aspek keamanan agar tidak disalahgunakan. Setiap tanda tangan yang dibuat wajib
                                sesuai dengan ketentuan dan kesepakatan
                                yang berlaku di Rumah Sakit. Variasi (6x) tanda tangan yang disimpan akan digunakan
                                secara acak pada saat pembubuhan tanda tangan
                                pada berkas klaim pasien.
                            </p>
                            <strong>Hal yang perlu diperhatikan:</strong>
                            <p class="mb-0">
                            <ol>
                                <li><code><b>TANDA TANGAN HARUS FULL RAPAT ATAS BAWAH</b></code> (<i><b>Memenuhi
                                            bagian atas sampai bawah, untuk bagian kiri dan kanan opsional</b></i>),
                                    tidak menyisakan <b>SPACE Kosong</b> di bagian Atas maupun Bawah TTE</li>
                                <li>Tanda tangan wajib diisi minimal 1x ttd atau dapat juga bervariasi (Lebih dari
                                    1x ttd)</li>
                                <li>Perubahan tanda tangan dapat dilakukan pada masing-masing variasi yang tersedia
                                </li>
                                <li>Tanda tangan baru tidak akan berpengaruh pada laporan yang sudah dibuat</li>
                            </ol>
                            </p>
                            <strong>Petunjuk Pengisian:</strong>
                            <p class="mb-0">
                            <ol>
                                <li>Gunakan layar sentuh atau mouse</li>
                                <li>Gambar tanda tangan pada canvas</li>
                                <li>Klik "<span class="badge text-bg-warning badge-sm"><i class="fas fa-sync me-1"></i>
                                        Refresh</span>" untuk menampilkan TTD</li>
                                <li>Klik "<span class="badge text-bg-danger badge-sm"><i
                                            class="ti ti-writing-sign"></i></span>" untuk menghapus</li>
                                <li>Klik "<span class="badge text-bg-primary badge-sm"><i class="fas fa-save me-1"></i>
                                        Simpan Tanda Tangan</span>" untuk menyimpan</li>
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
                            </div>

                            <div class="row" id="paduser" hidden>
                                @for ($i = 1; $i <= 6; $i++)
                                    <div class="col-xl-4 col-md-6 col-sm-12">
                                        <div class="mb-4 border rounded p-3">
                                            <h6 class="mb-2">Tanda Tangan <span
                                                    class="badge text-bg-secondary badge-sm">Variasi
                                                    {{ $i }}</span></h6>

                                            <div class="position-relative overflow-hidden mb-3"
                                                style="width:100%; max-width:500px; height:200px;">
                                                <canvas id="signature-pad-user-{{ $i }}"
                                                    class="border rounded w-100 h-100"></canvas>

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

                                <div class="col-xl-12 col-md-12 col-sm-12 text-center">
                                    <button class="btn btn-primary" onclick="storeTTDpeg()" id="btn-save-ttd">
                                        <i class="fas fa-save me-1"></i> Simpan Tanda Tangan
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card border border-danger border-1">
                <div class="card-header d-flex align-items-center justify-content-between py-3">
                    <h5 class="text-danger fw-bold mb-0">Ubah Password</h5>
                    <h6 class="mb-0">{!! Auth::user()->TERAKHIR_UBAH_PASSWOD
                        ? '<span class="badge text-bg-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Terakhir Ubah Password">' .
                            \Carbon\Carbon::parse(Auth::user()->TERAKHIR_UBAH_PASSWOD)->translatedFormat('d F Y \P\u\k\u\l H:i') .
                            ' WIB' .
                            '</span>'
                        : '' !!}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center alert border border-danger text-danger-emphasis border-dashed alert-dismissible fade show" role="alert">
                        <div class="flex-grow-1 me-3">
                            <h4 class="alert-heading">Perhatian!</h4>
                            <p class="mb-1">Password Anda akan kadaluarsa setiap tahunnya. Disarankan untuk melakukan perubahan secara periodik.</p>
                            <a href="#" class="alert-link mb-2"><u>Jangan memberikan password Anda kepada orang
                                    lain.</u></a>
                        </div>
                        <div class="flex-shrink-0">
                            <img src="{{ asset('/images/application/img-accout-password-alert.png') }}" alt="img" class="img-fluid wid-80" />
                        </div>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item pt-0 px-0">
                            <div class="row mb-0">
                                <label class="col-form-label col-md-3 col-sm-12 text-md-start">Password Sekarang
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-md-9 col-sm-12">
                                    <input type="password" class="form-control" id="password_lama" value="********" disabled />
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item px-0">
                            <div class="row mb-0">
                                <label class="col-form-label col-md-3 col-sm-12 text-md-start">Password Baru <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-9 col-sm-12">
                                    <input type="password" class="form-control" id="password_baru" disabled />
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item pb-0 px-0">
                            <div class="row mb-0">
                                <label class="col-form-label col-md-3 col-sm-12 text-md-start">Konfirmasi Password
                                    Baru <span class="text-danger">*</span></label>
                                <div class="col-md-9 col-sm-12">
                                    <input type="password" class="form-control" id="password_baru_confirm"
                                        disabled />
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

<script>
    $(document).ready(function() {
        // showLoader();
        // refresh();
        const pegawaiNIP = @json(Auth::user()->NIP); // Dikirim dari controller ke blade
        if (pegawaiNIP) {
            showTTDpeg(pegawaiNIP);
        }
    });

    // FUNCTION AREA
    function showUbahBiodata() {
        const $el = $('#ubah-biodata');
        $el.prop('hidden', !$el.prop('hidden'));
    }

    let padUsers = {};

    function showTTDpeg(NIP) {
        $('#paduser').prop('hidden', true);
        $('#show-petunjuk').prop('hidden', true);
        $('#preview-wrapper').prop('hidden', true);
        $('#preview-ttd-list').empty();

        $('#loading-ttd').html(`
            <div class="spinner-grow align-middle me-2" role="status">
                <span class="sr-only">Loading...</span>
            </div>
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
                                <small class="text-muted me-2">Variasi ${ttd.queue}</small>
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

                    $('#btn-ubah-ttd').off('click').on('click', function() {
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

            padUsers[i].onBegin = function() {
                $('#placeholder-ttd-user-' + i).hide();
            };
        }

        $('.clear-user').off('click').on('click', function() {
            const idx = $(this).data('index');
            padUsers[idx].clear();
            $('#placeholder-ttd-user-' + idx).show();
        });

        $(window).off('resize.user');
        $(window).on('resize.user', function() {
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
            success: function(res) {
                iziToast.success({
                    title: 'Berhasil',
                    message: 'Tanda tangan berhasil disimpan'
                });
                showTTDpeg(nip);
            },
            complete: function() {
                $('#btn-save-ttd').prop('disabled', false)
                    .find('i').removeClass('fa-spin fa-sync');
            }
        });
    }
</script>
@endsection
