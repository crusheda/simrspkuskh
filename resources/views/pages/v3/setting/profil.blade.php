@section('title', 'Profil Saya')
@section('page-sub-title', 'INFORMASI AKUN')
@section('page-title', 'Profil Pengguna')

<div>
    <div class="profil-v3">

        <div class="row g-4">

            {{-- LEFT --}}
            <div class="col-xl-4 col-lg-5">

                <div class="card profile-hero mb-4">
                    <div class="card-body p-4">

                        <div class="d-flex align-items-center gap-3">
                            <div class="profile-avatar">
                                <i class="ti ti-user"></i>
                            </div>

                            <div class="min-w-0">
                                <h5 class="text-white fw-semibold mb-1">
                                    {{ $pegawai->NAMALENGKAP ?? $pegawai->NAMA ?? $user->NAMA }}
                                </h5>

                                <div class="small text-white opacity-75 mb-2">
                                    {{ $role }}
                                </div>

                                <span class="badge rounded-pill bg-white text-primary">
                                    Pegawai Aktif
                                </span>
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-6">
                                <div class="small text-white opacity-75">NIK</div>
                                <div class="fw-semibold text-white">
                                    {{ $user->NIK ?? $pegawai->NIK ?? '-' }}
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="small text-white opacity-75">NIP</div>
                                <div class="fw-semibold text-white">
                                    {{ $user->NIP ?? $pegawai->NIP ?? '-' }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- NAV --}}
                <div class="card profile-card">
                    <div class="card-body p-3">

                        <div class="profile-nav" id="profil-menu">

                            <button type="button"
                                    wire:click="setTab('profil-pane')"
                                    class="{{ $activeTab === 'profil-pane' ? 'active' : '' }}">
                                <i class="ti ti-user-circle fs-5"></i>
                                Informasi Profil
                            </button>

                            <button type="button"
                                    wire:click="setTab('ttd-pane')"
                                    class="{{ $activeTab === 'ttd-pane' ? 'active' : '' }}">
                                <i class="ti ti-writing-sign fs-5"></i>
                                Tanda Tangan Elektronik
                            </button>

                            <button type="button"
                                    wire:click="setTab('password-pane')"
                                    class="{{ $activeTab === 'password-pane' ? 'active' : '' }}">
                                <i class="ti ti-lock fs-5"></i>
                                Ubah Password

                                <span class="badge bg-secondary-subtle text-secondary ms-auto">
                                    Belum Aktif
                                </span>
                            </button>

                        </div>
                    </div>
                </div>

            </div>

            {{-- RIGHT --}}
            <div class="col-xl-8 col-lg-7">

                {{-- PROFIL PANE --}}
                <div id="profil-pane" class="profil-pane {{ $activeTab !== 'profil-pane' ? 'd-none' : '' }}">

                    <div class="card profile-card">
                        <div class="card-body p-4">

                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <div>
                                    <h5 class="fw-semibold mb-1">Informasi Identitas</h5>
                                    <div class="small profile-muted">
                                        Informasi pegawai dari sistem SIRMED.
                                    </div>
                                </div>

                                <div class="rounded-3 p-2 bg-primary-subtle text-primary">
                                    <i class="ti ti-id-badge-2 fs-4"></i>
                                </div>
                            </div>

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="info-label">Username</span>
                                        <div class="info-value">{{ $user->LOGIN ?? '-' }}</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="info-label">Nama Lengkap</span>
                                        <div class="info-value">
                                            {{ $pegawai->NAMALENGKAP ?? $pegawai->NAMA ?? '-' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="info-label">NIK</span>
                                        <div class="info-value">
                                            {{ $user->NIK ?? $pegawai->NIK ?? '-' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="info-label">NIP</span>
                                        <div class="info-value">
                                            {{ $user->NIP ?? $pegawai->NIP ?? '-' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="info-label">Nomor Handphone</span>
                                        <div class="info-value">
                                            {{ $pegawai->NOHP ?? '-' }}
                                            @if($pegawai->JENISNOHP)
                                                <span class="small profile-muted">
                                                    ({{ $pegawai->JENISNOHP }})
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="info-label">Tempat, Tanggal Lahir</span>
                                        <div class="info-value">
                                            {{ $pegawai->TEMPAT_LAHIR ?? '-' }}
                                            @if($pegawai->TANGGAL_LAHIR)
                                                ,
                                                {{ \Carbon\Carbon::parse($pegawai->TANGGAL_LAHIR)->translatedFormat('d F Y') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="info-item">
                                        <span class="info-label">Alamat Lengkap</span>
                                        <div class="info-value">
                                            {{ $pegawai->ALAMAT ?? '-' }}
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>

                {{-- TTE PANE --}}
                <div id="ttd-pane" class="profil-pane {{ $activeTab !== 'ttd-pane' ? 'd-none' : '' }}">

                    <div class="card profile-card">
                        <div class="card-body p-4">

                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                                <div>
                                    <h5 class="fw-semibold mb-1">
                                        Enrollment Tanda Tangan Elektronik
                                    </h5>

                                    <div class="small profile-muted">
                                        Kelola enam variasi tanda tangan elektronik.
                                    </div>
                                </div>

                                <button type="button"
                                        class="btn btn-outline-primary btn-sm"
                                        wire:click="refreshTtd"
                                        wire:loading.attr="disabled"
                                        wire:target="refreshTtd">
                                    <i class="ti ti-refresh me-1"></i>
                                    Refresh
                                </button>
                            </div>

                            {{-- SSR PREVIEW --}}
                            @if(count($ttds) > 0 && !$editingTtd)

                                <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                    <div>
                                        <h6 class="fw-semibold mb-1">TTE Terdaftar</h6>
                                        <div class="small profile-muted">
                                            {{ count($ttds) }} variasi tanda tangan tersimpan.
                                        </div>
                                    </div>

                                    <span class="badge rounded-pill bg-success-subtle text-success">
                                        <i class="ti ti-check me-1"></i>
                                        Aktif
                                    </span>
                                </div>

                                <div class="row g-3">
                                    @foreach($ttds as $ttd)
                                        <div class="col-xl-4 col-md-6 col-12" wire:key="ttd-preview-{{ $ttd['queue'] }}">
                                            <div class="ttd-preview">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <span class="small fw-semibold">
                                                        Variasi {{ $ttd['queue'] }}
                                                    </span>

                                                    <span class="badge bg-success-subtle text-success">
                                                        Tersimpan
                                                    </span>
                                                </div>

                                                <img src="{{ $ttd['signature_url'] }}"
                                                     alt="TTE variasi {{ $ttd['queue'] }}">

                                                <div class="small profile-muted text-center mt-2">
                                                    {{ $ttd['updated_at'] ? \Carbon\Carbon::parse($ttd['updated_at'])->translatedFormat('d M Y, H:i') : '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="text-end mt-4">
                                    <button type="button"
                                            class="btn btn-warning"
                                            wire:click="editTtd"
                                            wire:loading.attr="disabled">
                                        <i class="ti ti-edit me-1"></i>
                                        Ubah TTE
                                    </button>
                                </div>

                            @elseif(count($ttds) === 0 && !$editingTtd)

                                <div class="text-center py-5">
                                    <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center mb-3"
                                         style="width:70px;height:70px">
                                        <i class="ti ti-writing-sign fs-1"></i>
                                    </div>

                                    <h6 class="fw-semibold">Belum Ada TTE</h6>

                                    <p class="small profile-muted">
                                        Anda belum melakukan enrollment tanda tangan elektronik.
                                    </p>

                                    <button type="button"
                                            class="btn btn-primary"
                                            wire:click="editTtd">
                                        <i class="ti ti-plus me-1"></i>
                                        Enrollment TTE
                                    </button>
                                </div>

                            @endif

                            {{-- ENROLLMENT FORM --}}
                            @if($editingTtd)

                                <div class="ttd-guidance p-3 mb-4">
                                    <div class="fw-semibold mb-2">
                                        <i class="ti ti-info-circle text-primary me-1"></i>
                                        Petunjuk Enrollment
                                    </div>

                                    <ul class="small mb-0 ps-3">
                                        <li>Tanda tangan harus rapat memenuhi bagian atas dan bawah canvas.</li>
                                        <li>Minimal satu variasi tanda tangan wajib diisi.</li>
                                        <li>Anda dapat mengisi hingga enam variasi tanda tangan.</li>
                                        <li>Gunakan mouse atau layar sentuh.</li>
                                        <li>Variasi akan digunakan secara acak pada dokumen klaim.</li>
                                    </ul>
                                </div>

                                <div class="row g-3" id="ttd-canvas-list">

                                    @for($i = 1; $i <= 6; $i++)
                                        <div class="col-xl-6 col-md-6 col-12"
                                             wire:key="ttd-canvas-{{ $i }}">

                                            <div class="ttd-card">

                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <div>
                                                        <h6 class="fw-semibold mb-0">
                                                            Variasi {{ $i }}
                                                        </h6>
                                                        <div class="small profile-muted">
                                                            Tanda tangan {{ $i }}
                                                        </div>
                                                    </div>

                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-danger btn-clear-ttd"
                                                            data-queue="{{ $i }}">
                                                        <i class="ti ti-eraser"></i>
                                                    </button>
                                                </div>

                                                <div class="ttd-canvas-wrapper">
                                                    <canvas id="signature-pad-user-{{ $i }}"
                                                            class="ttd-canvas"
                                                            data-queue="{{ $i }}"></canvas>

                                                    <div class="ttd-placeholder"
                                                         id="ttd-placeholder-{{ $i }}">
                                                        <span class="text-center">
                                                            <i class="ti ti-writing-sign fs-3 d-block mb-1"></i>
                                                            Gambar tanda tangan di sini
                                                        </span>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    @endfor

                                </div>

                                <div class="d-flex flex-wrap justify-content-between gap-3 mt-4">
                                    <button type="button"
                                            class="btn btn-light"
                                            wire:click="cancelEditTtd">
                                        <i class="ti ti-arrow-left me-1"></i>
                                        Batal
                                    </button>

                                    <button type="button"
                                            id="btn-save-ttd"
                                            class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-1"></i>
                                        Simpan TTE
                                    </button>
                                </div>

                            @endif

                        </div>
                    </div>

                </div>

                {{-- PASSWORD PANE --}}
                <div id="password-pane" class="profil-pane {{ $activeTab !== 'password-pane' ? 'd-none' : '' }}">

                    <div class="card profile-card">
                        <div class="card-body p-4">

                            <h5 class="fw-semibold mb-1">Ubah Password</h5>

                            <div class="small profile-muted mb-4">
                                Pengelolaan password akun.
                            </div>

                            <div class="alert alert-warning">
                                <i class="ti ti-alert-triangle me-1"></i>
                                Fitur ubah password belum tersedia pada logic Profil V2 yang Anda kirim.
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password Sekarang</label>
                                <input type="password" class="form-control" disabled>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password Baru</label>
                                <input type="password" class="form-control" disabled>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" disabled>
                            </div>

                            <div class="text-end">
                                <button class="btn btn-primary" disabled>
                                    Perbarui Password
                                </button>
                            </div>

                            @if($lastPasswordUpdated)
                                <div class="small profile-muted mt-3">
                                    Terakhir mengubah password:
                                    {{ $lastPasswordUpdated }} WIB
                                </div>
                            @endif

                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

@script
<script>
    const profilComponent = $wire;

    let profilSignaturePads = {};

    function profilInitSignaturePads() {
        profilSignaturePads = {};

        for (let i = 1; i <= 6; i++) {
            const canvas = document.getElementById(`signature-pad-user-${i}`);

            if (!canvas) continue;

            const pad = new SignaturePad(canvas, {
                minWidth: 0.8,
                maxWidth: 2.5,
                penColor: '#111827',
                backgroundColor: 'rgba(255,255,255,0)'
            });

            profilSignaturePads[i] = pad;

            pad.onBegin = function () {
                document.getElementById(`ttd-placeholder-${i}`)
                    ?.classList.add('d-none');
            };

            pad.onEnd = function () {
                if (pad.isEmpty()) {
                    document.getElementById(`ttd-placeholder-${i}`)
                        ?.classList.remove('d-none');
                }
            };

            profilResizeCanvas(canvas, pad);
        }
    }

    function profilResizeCanvas(canvas, pad) {
        if (!canvas || !pad) return;

        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        const data = pad.toData();

        const rect = canvas.getBoundingClientRect();

        if (!rect.width || !rect.height) return;

        canvas.width = rect.width * ratio;
        canvas.height = rect.height * ratio;

        const context = canvas.getContext('2d');

        context.setTransform(ratio, 0, 0, ratio, 0, 0);

        pad.clear();

        if (data.length > 0) {
            pad.fromData(data);
        }
    }

    document.addEventListener('click', function (event) {
        const clearButton = event.target.closest('.btn-clear-ttd');

        if (clearButton) {
            const queue = parseInt(clearButton.dataset.queue, 10);
            const pad = profilSignaturePads[queue];

            if (pad) {
                pad.clear();
            }

            document.getElementById(`ttd-placeholder-${queue}`)
                ?.classList.remove('d-none');
        }
    });

    document.addEventListener('click', async function (event) {
        const saveButton = event.target.closest('#btn-save-ttd');

        if (!saveButton) return;

        const signatures = [];

        for (let i = 1; i <= 6; i++) {
            const pad = profilSignaturePads[i];

            if (pad && !pad.isEmpty()) {
                signatures.push({
                    queue: i,
                    image: pad.toDataURL('image/png')
                });
            }
        }

        if (signatures.length === 0) {
            if (window.iziToast) {
                iziToast.warning({
                    title: 'Perhatian',
                    message: 'Minimal satu variasi tanda tangan harus diisi.'
                });
            } else {
                alert('Minimal satu variasi tanda tangan harus diisi.');
            }

            return;
        }

        saveButton.disabled = true;
        saveButton.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2"></span> Menyimpan...';

        try {
            const result = await profilComponent.storeTtd(signatures);

            if (result && result.success) {
                if (window.iziToast) {
                    iziToast.success({
                        title: 'Berhasil',
                        message: result.message
                    });
                } else {
                    alert(result.message);
                }

                profilSignaturePads = {};
            } else {
                const message = result?.message || 'Gagal menyimpan TTE.';

                if (window.iziToast) {
                    iziToast.error({
                        title: 'Gagal',
                        message: message
                    });
                } else {
                    alert(message);
                }
            }
        } catch (error) {
            const message = error.message || 'Terjadi kesalahan saat menyimpan TTE.';

            if (window.iziToast) {
                iziToast.error({
                    title: 'Gagal',
                    message: message
                });
            } else {
                alert(message);
            }
        } finally {
            saveButton.disabled = false;
            saveButton.innerHTML =
                '<i class="ti ti-device-floppy me-1"></i> Simpan TTE';
        }
    });

    window.addEventListener('resize', function () {
        if (!document.getElementById('ttd-pane') ||
            document.getElementById('ttd-pane').classList.contains('d-none')) {
            return;
        }

        for (let i = 1; i <= 6; i++) {
            const canvas = document.getElementById(`signature-pad-user-${i}`);
            const pad = profilSignaturePads[i];

            if (canvas && pad) {
                profilResizeCanvas(canvas, pad);
            }
        }
    });

    Livewire.on('profil-ttd-refreshed', (event) => {
        if (window.iziToast) {
            iziToast.success({
                title: 'Berhasil',
                message: event.message
            });
        }
    });

    Livewire.on('profil-ttd-saved', (event) => {
        if (window.iziToast) {
            iziToast.success({
                title: 'Berhasil',
                message: event.message
            });
        }
    });
</script>
@endscript
</div>
