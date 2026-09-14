@push('styles')
    <link rel="stylesheet" href="{{ asset('v2/css/emr/tab.css') }}">
@endpush
<div id="apiLoadingBar" class="api-loading-bar" aria-hidden="true">
    <div class="api-loading-bar__progress"></div>
</div>
<div class="row">
    <div class="col-xl-3" id="pengkajian-sidebar-col">
        <a href="#" class="d-inline-flex align-items-center d-xl-none btn btn-dark w-100 mb-2" data-bs-toggle="offcanvas" data-bs-target="#offcanvas_component">
            <i class="ti ti-menu-2 me-2"></i> Menu Form Pengkajian
        </a>
        <div class="offcanvas-xl offcanvas-start component-offcanvas" tabindex="-1" id="offcanvas_component">
            <div class="offcanvas-header">
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#offcanvas_component" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-0" style="display: block;">
                <div class="card position-xl-fixed sidebar-menu mb-0">
                    {{-- <div class="card-header p-3">
                        <div class="form-search">
                            <i class="ph-duotone ph-magnifying-glass icon-search"></i>

                            <input
                                type="text"
                                class="form-control"
                                id="compo-menu-search"
                                placeholder="Cari Nama Formulir..."
                                autocomplete="off">

                            <button type="button" class="btn-clear-search" id="clear-search" hidden>
                                <i class="ph-duotone ph-eraser text-danger"></i>
                            </button>
                        </div>
                    </div> --}}
                    <div class="card-header p-3 d-flex align-items-center gap-2">
                        <div class="form-search flex-grow-1">
                            <i class="ph-duotone ph-magnifying-glass icon-search"></i>

                            <input
                                type="text"
                                class="form-control"
                                id="compo-menu-search"
                                placeholder="Cari Nama Formulir..."
                                autocomplete="off">

                            <button
                                type="button"
                                class="btn-clear-search"
                                id="clear-search"
                                hidden>
                                <i class="ph-duotone ph-eraser text-danger"></i>
                            </button>
                        </div>

                        <button
                            type="button"
                            class="btn btn-icon btn-action-telegram waves-effect waves-light"
                            id="btn-minimize-pengkajian"
                            title="Minimize Sidebar"
                            data-bs-toggle="tooltip"
                            data-bs-placement="bottom">
                            <i class="ri-sidebar-fold-line fs-25"></i>
                        </button>
                    </div>
                    <div class="card-body p-0 menu-scroll" id="pengkajianMenu">
                        <div class="list-group">

                            <div class="list-group-item menu-group-title p-2" data-group="awal">
                                <h5 class="mt-2 ms-3">PENGKAJIAN AWAL</h5>
                            </div>

                            <!-- Gawat Darurat -->
                            <a href="javascript:void(0);"
                                class="list-group-item list-group-item-action menu-item menu-parent js-final d-flex align-items-center"
                                data-form="pengkajian-gd"
                                data-group="awal">
                                <span>Form Pengkajian Gawat Darurat</span>

                                <span class="ms-auto d-flex align-items-center gap-1">
                                    <i class="ri-stethoscope-line text-bg-danger d-none js-final-icon px-1 rounded"
                                        data-final-key="gdd_dokter"
                                        title="Sudah difinalisasi dokter"></i>

                                    <i class="ri-nurse-line text-bg-success d-none js-final-icon px-1 rounded"
                                        data-final-key="gdp_perawat"
                                        title="Sudah difinalisasi perawat"></i>
                                </span>
                            </a>

                            <div class="menu-wrapper">
                                <!-- Rawat Jalan -->
                                <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center menu-collapse menu-parent"
                                data-bs-toggle="collapse"
                                href="#submenuRajal"
                                role="button" data-group="awal">
                                    <span>Form Pengkajian Rawat Jalan</span>
                                    <i class="ti ti-chevron-down submenu-icon"></i>
                                </a>
                                <div class="collapse submenu" id="submenuRajal">
                                    <div class="list-group">
                                        <a href="javascript:void(0);"
                                            class="list-group-item list-group-item-action ps-5 menu-child"
                                            data-form="pengkajian-rajal-dewasa"
                                            data-group="awal">
                                            <i class="ph-duotone ph-arrow-elbow-down-right me-1"></i>
                                            Form Dewasa
                                        </a>
                                        <a href="javascript:void(0);"
                                            class="list-group-item list-group-item-action ps-5 menu-child"
                                            data-form="pengkajian-rajal-anak"
                                            data-group="awal">
                                            <i class="ph-duotone ph-arrow-elbow-down-right me-1"></i>
                                            Form Anak
                                        </a>
                                        <a href="javascript:void(0);"
                                            class="list-group-item list-group-item-action ps-5 menu-child"
                                            data-form="pengkajian-rajal-psikiatri"
                                            data-group="awal">
                                            <i class="ph-duotone ph-arrow-elbow-down-right me-1"></i>
                                            Form Psikiatri
                                        </a>
                                        <a href="javascript:void(0);"
                                            class="list-group-item list-group-item-action ps-5 menu-child"
                                            data-form="pengkajian-rajal-geriatri"
                                            data-group="awal">
                                            <i class="ph-duotone ph-arrow-elbow-down-right me-1"></i>
                                            Form Geriatri
                                        </a>
                                        <a href="javascript:void(0);"
                                            class="list-group-item list-group-item-action ps-5 menu-child"
                                            data-form="pengkajian-rajal-obsgyn"
                                            data-group="awal">
                                            <i class="ph-duotone ph-arrow-elbow-down-right me-1"></i>
                                            Form Obsgyn
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="menu-wrapper">
                                <!-- Rawat Inap -->
                                <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center menu-collapse menu-parent"
                                    data-bs-toggle="collapse"
                                    href="#submenuRanap"
                                    role="button"
                                    data-group="awal">
                                    <span>Form Pengkajian Rawat Inap</span>
                                    <i class="ti ti-chevron-down submenu-icon"></i>
                                </a>

                                <div class="collapse submenu" id="submenuRanap">
                                    <div class="list-group">

                                        <a href="javascript:void(0);"
                                            class="list-group-item list-group-item-action ps-5 menu-child
                                                js-ranap-final d-flex align-items-center"
                                            data-form="pengkajian-ranap-dewasa"
                                            data-group="awal">
                                            <span>
                                                <i class="ph-duotone ph-arrow-elbow-down-right me-1"></i>
                                                Form Dewasa
                                            </span>

                                            <span class="ms-auto d-flex align-items-center gap-1">
                                                <i class="ri-stethoscope-line text-bg-danger d-none js-final-icon px-1 rounded"
                                                    data-final-key="rid_dokter"
                                                    title="Sudah difinalisasi dokter"></i>

                                                <i class="ri-nurse-line text-bg-success d-none js-final-icon px-1 rounded"
                                                    data-final-key="rid_perawat"
                                                    title="Sudah difinalisasi perawat"></i>
                                            </span>
                                        </a>

                                        <a href="javascript:void(0);"
                                            class="list-group-item list-group-item-action ps-5 menu-child
                                                js-ranap-final d-flex align-items-center"
                                            data-form="pengkajian-ranap-anak"
                                            data-group="awal">
                                            <span>
                                                <i class="ph-duotone ph-arrow-elbow-down-right me-1"></i>
                                                Form Anak
                                            </span>

                                            <span class="ms-auto d-flex align-items-center gap-1">
                                                <i class="ri-stethoscope-line text-bg-danger d-none js-final-icon px-1 rounded"
                                                    data-final-key="ria_dokter"
                                                    title="Sudah difinalisasi dokter"></i>

                                                <i class="ri-nurse-line text-bg-success d-none js-final-icon px-1 rounded"
                                                    data-final-key="ria_perawat"
                                                    title="Sudah difinalisasi perawat"></i>
                                            </span>
                                        </a>

                                        <a href="javascript:void(0);"
                                            class="list-group-item list-group-item-action ps-5 menu-child
                                                js-ranap-final d-flex align-items-center"
                                            data-form="pengkajian-ranap-neonatus"
                                            data-group="awal">
                                            <span>
                                                <i class="ph-duotone ph-arrow-elbow-down-right me-1"></i>
                                                Form Neonatus
                                            </span>

                                            <span class="ms-auto d-flex align-items-center gap-1">
                                                <i class="ri-stethoscope-line text-bg-danger d-none js-final-icon px-1 rounded"
                                                    data-final-key="rin_dokter"
                                                    title="Sudah difinalisasi dokter"></i>

                                                <i class="ri-nurse-line text-bg-success d-none js-final-icon px-1 rounded"
                                                    data-final-key="rin_perawat"
                                                    title="Sudah difinalisasi perawat"></i>
                                            </span>
                                        </a>

                                        <a href="javascript:void(0);"
                                            class="list-group-item list-group-item-action ps-5 menu-child
                                                js-ranap-final d-flex align-items-center"
                                            data-form="pengkajian-ranap-obsgyn"
                                            data-group="awal">
                                            <span>
                                                <i class="ph-duotone ph-arrow-elbow-down-right me-1"></i>
                                                Form Obstetri dan Ginekologi
                                            </span>

                                            <span class="ms-auto d-flex align-items-center gap-1">
                                                <i class="ri-stethoscope-line text-bg-danger d-none js-final-icon px-1 rounded"
                                                    data-final-key="rio_dokter"
                                                    title="Sudah difinalisasi dokter"></i>

                                                <i class="ri-nurse-line text-bg-success d-none js-final-icon px-1 rounded"
                                                    data-final-key="rio_perawat"
                                                    title="Sudah difinalisasi perawat"></i>
                                            </span>
                                        </a>

                                    </div>
                                </div>
                            </div>

                            <div class="menu-wrapper">
                                {{-- Bedah & Anestesi --}}
                                <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center menu-collapse menu-parent"
                                    data-bs-toggle="collapse"
                                    href="#submenuBedahAnestesi"
                                    role="button"
                                    data-group="awal">
                                    <span>Form Pengkajian Bedah & Anestesi</span>
                                    <i class="ti ti-chevron-down submenu-icon"></i>
                                </a>

                                <div class="collapse submenu" id="submenuBedahAnestesi">
                                    <div class="list-group">

                                        <a href="javascript:void(0);"
                                            class="list-group-item list-group-item-action ps-5 menu-child js-final d-flex align-items-center"
                                            data-form="pengkajian-prabedah"
                                            data-group="awal">
                                            <span>
                                                <i class="ph-duotone ph-arrow-elbow-down-right me-1"></i>
                                                Form Pra Bedah
                                            </span>

                                            <span class="ms-auto d-flex align-items-center gap-1">
                                                <i class="ri-nurse-line text-bg-warning d-none js-final-icon px-1 rounded"
                                                    data-final-key="prabedah"
                                                    title="Sudah difinalisasi"></i>
                                            </span>
                                        </a>

                                        <a href="javascript:void(0);"
                                            class="list-group-item list-group-item-action ps-5 menu-child js-final d-flex align-items-center"
                                            data-form="pengkajian-praanestesiinduksi"
                                            data-group="awal">
                                            <span>
                                                <i class="ph-duotone ph-arrow-elbow-down-right me-1"></i>
                                                Form Pra Anestesia Dan Induksi
                                            </span>

                                            <span class="ms-auto d-flex align-items-center gap-1">
                                                <i class="ri-nurse-line text-bg-warning d-none js-final-icon px-1 rounded"
                                                    data-final-key="praanestesiinduksi"
                                                    title="Sudah difinalisasi"></i>
                                            </span>
                                        </a>

                                        <a href="javascript:void(0);"
                                            class="list-group-item list-group-item-action ps-5 menu-child js-final d-flex align-items-center"
                                            data-form="pengkajian-laporananestesi"
                                            data-group="awal">
                                            <span>
                                                <i class="ph-duotone ph-arrow-elbow-down-right me-1"></i>
                                                Form Laporan Anestesi
                                            </span>

                                            <span class="ms-auto d-flex align-items-center gap-1">
                                                <i class="ri-stethoscope-line text-bg-danger d-none js-final-icon px-1 rounded"
                                                    data-final-key="lap_pasca_anestesi"
                                                    title="Sudah difinalisasi penata anestesi"></i>

                                                <i class="ri-nurse-line text-bg-success d-none js-final-icon px-1 rounded"
                                                    data-final-key="lap_anestesi"
                                                    title="Sudah difinalisasi dokter"></i>
                                            </span>
                                        </a>

                                    </div>
                                </div>
                            </div>

                            <div class="list-group-item menu-group-title p-2" data-group="khusus">
                                <h5 class="mt-2 ms-3">PENGKAJIAN KHUSUS</h5>
                            </div>

                            <a href="javascript:void(0);"
                                class="list-group-item list-group-item-action menu-item menu-parent js-final d-flex align-items-center"
                                data-form="pengkajian-khusus-remaja"
                                data-group="khusus">
                                <span>Form Pengkajian Remaja</span>

                                <span class="ms-auto d-flex align-items-center gap-1">
                                    <i class="ri-nurse-line text-bg-warning d-none js-final-icon px-1 rounded"
                                        data-final-key="kh_remaja"
                                        title="Sudah difinalisasi"></i>
                                </span>
                            </a>

                            <a href="javascript:void(0);"
                                class="list-group-item list-group-item-action menu-item menu-parent js-final d-flex align-items-center"
                                data-form="pengkajian-khusus-terminal"
                                data-group="khusus">
                                <span>Form Pengkajian Terminal</span>

                                <span class="ms-auto d-flex align-items-center gap-1">
                                    <i class="ri-nurse-line text-bg-warning d-none js-final-icon px-1 rounded"
                                        data-final-key="kh_terminal"
                                        title="Sudah difinalisasi"></i>
                                </span>
                            </a>

                            <a href="javascript:void(0);"
                                class="list-group-item list-group-item-action menu-item menu-parent js-final d-flex align-items-center"
                                data-form="pengkajian-khusus-nyerikronik"
                                data-group="khusus">
                                <span>Form Pengkajian Nyeri Kronik</span>

                                <span class="ms-auto d-flex align-items-center gap-1">
                                    <i class="ri-nurse-line text-bg-warning d-none js-final-icon px-1 rounded"
                                        data-final-key="kh_nyerikronik"
                                        title="Sudah difinalisasi"></i>
                                </span>
                            </a>

                            <a href="javascript:void(0);"
                                class="list-group-item list-group-item-action menu-item menu-parent js-final d-flex align-items-center"
                                data-form="pengkajian-khusus-sistemimunterganggu"
                                data-group="khusus">
                                <span>Form Pengkajian Sistem Imun Terganggu</span>

                                <span class="ms-auto d-flex align-items-center gap-1">
                                    <i class="ri-nurse-line text-bg-warning d-none js-final-icon px-1 rounded"
                                        data-final-key="kh_sistemimun"
                                        title="Sudah difinalisasi"></i>
                                </span>
                            </a>

                            <a href="javascript:void(0);"
                                class="list-group-item list-group-item-action menu-item menu-parent js-final d-flex align-items-center"
                                data-form="pengkajian-khusus-kecanduanobatalkohol"
                                data-group="khusus">
                                <span>Form Pengkajian Kecanduan Obat Terlarang</span>

                                <span class="ms-auto d-flex align-items-center gap-1">
                                    <i class="ri-nurse-line text-bg-warning d-none js-final-icon px-1 rounded"
                                        data-final-key="kh_kecanduanobat"
                                        title="Sudah difinalisasi"></i>
                                </span>
                            </a>

                            <a href="javascript:void(0);"
                                class="list-group-item list-group-item-action menu-item menu-parent js-final d-flex align-items-center"
                                data-form="pengkajian-khusus-korbankekerasan"
                                data-group="khusus">
                                <span>Form Pengkajian Korban Kekerasan</span>

                                <span class="ms-auto d-flex align-items-center gap-1">
                                    <i class="ri-nurse-line text-bg-warning d-none js-final-icon px-1 rounded"
                                        data-final-key="kh_korbankekerasan"
                                        title="Sudah difinalisasi"></i>
                                </span>
                            </a>

                            <a href="javascript:void(0);"
                                class="list-group-item list-group-item-action menu-item menu-parent js-final d-flex align-items-center"
                                data-form="pengkajian-khusus-penyakitmenular"
                                data-group="khusus">
                                <span>Form Pengkajian Penyakit Menular</span>

                                <span class="ms-auto d-flex align-items-center gap-1">
                                    <i class="ri-nurse-line text-bg-warning d-none js-final-icon px-1 rounded"
                                        data-final-key="kh_penyakitmenular"
                                        title="Sudah difinalisasi"></i>
                                </span>
                            </a>

                            <a href="javascript:void(0);"
                                class="list-group-item list-group-item-action menu-item menu-parent js-final d-flex align-items-center"
                                data-form="pengkajian-khusus-lanjutan"
                                data-group="khusus">
                                <span>Form Pengkajian Lanjutan</span>

                                <span class="ms-auto d-flex align-items-center gap-1">
                                    <i class="ri-nurse-line text-bg-warning d-none js-final-icon px-1 rounded"
                                        data-final-key="kh_lanjutan"
                                        title="Sudah difinalisasi"></i>
                                </span>
                            </a>

                            <div class="list-group-item menu-group-title p-2" data-group="lain">
                                <h5 class="mt-2 ms-3">PENGKAJIAN LAIN</h5>
                            </div>

                            <a href="javascript:void(0);"
                                class="list-group-item list-group-item-action menu-item menu-parent js-final d-flex align-items-center"
                                data-form="form-transfer-pasien"
                                data-group="lain">
                                <span>Lembar Transfer Pasien</span>

                                <span class="ms-auto d-flex align-items-center gap-1">
                                    <i class="ri-nurse-line text-bg-warning d-none js-final-icon px-1 rounded"
                                        data-final-key="ln_transfer"
                                        title="Sudah difinalisasi"></i>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-9" id="pengkajian-content-col">
        <div class="row">
            <div class="col-sm-12">
                <div id="formContent">
                    <div
                        class="d-flex justify-content-center align-items-center"
                        style="min-height: 70vh;"
                    >
                        <img
                            src="{{ asset('v2/images/auth/vector1.svg') }}"
                            alt="" style="width: 600px;height:auto;"
                            class="img-fluid cover-img"
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="showCppt" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="showCpptLabel">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showCpptLabel"><span class="badge text-bg-secondary">CPPT</span> | NORM.<a id="show-norm-cppt" class="text-primary"></a> | IDKUNJUNGAN : <a id="show-id-cppt" class="text-primary"></a></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <small><a><b>Tabel di bawah diurutkan berdasarkan <mark>TANGGAL</mark> datarecord CPPT pertama kali saat kunjungan pada tanggal tsb</b></a></small>
                <div class="table-responsive mt-2">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th style="width: 10%;">TANGGAL</th>
                                <th style="width: 40%;">CATATAN</th>
                                <th style="width: 20%;">PPA</th>
                                <th style="width: 10%;">JENIS</th>
                                <th style="width: 20%;">VERIFIKASI</th>
                            </tr>
                        </thead>
                        <tbody id="tampil-cppt">
                            <tr>
                                <td colspan="15">
                                    <center>
                                        <div class="spinner-border spinner-border-sm" role="status"></div>
                                    </center>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                {{-- <a href="#!" class="tooltip-test" data-bs-toggle="tooltip" title="Tooltip" data-container="#showCppt">that link</a> --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                {{-- <button type="button" class="btn btn-primary"></button> --}}
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {

        // Nilai kunjungan harus tersedia di scope yang sama dengan fungsi
        // global di bawah. Sebelumnya variabel ini belum pernah dideklarasikan.
        const kunjungan = @json($list['kunjungan'] ?? '');

        let activeApiRequests = 0;

        $(document).ready(function() {
            tampilkanPenandaFinalisasi();
        });

        $(document).ajaxSend(function () {
            activeApiRequests++;
            $('#apiLoadingBar').addClass('is-loading')
                .attr('aria-hidden', 'false');
        });

        $(document).ajaxComplete(function () {
            activeApiRequests = Math.max(0, activeApiRequests - 1);

            if (activeApiRequests === 0) {
                $('#apiLoadingBar').removeClass('is-loading')
                    .attr('aria-hidden', 'true');

                $(document).trigger('page:api-ready');
            }
        });

        // ==========================
        // STARTING _ DISABLED AFTER CHECKBOX IS TRUE / ADA (ONLY INPUT LAINNYA)
        // ==========================
            /**
             * Enable / disable input lain-lain
             * berdasarkan checkbox yang dipilih.
             */
            function toggleInputLainnya($checkbox) {
                const target = $checkbox.data('target');
                const $input = $(target);

                if (!$input.length) {
                    return;
                }

                // Cari checkbox yang sedang terpilih
                const $selected = $(
                    `[data-target="${target}"]:checked`
                );

                // Jika pilihan yang terpilih mempunyai class buka-lainnya
                if ($selected.hasClass('buka-lainnya')) {
                    $input.prop('disabled', false);
                } else {
                    $input
                        .prop('disabled', true)
                        .val('');
                }
            }

            /**
             * Ketika user memilih checkbox
             */
            $(document).on('change', '.single-checkbox[data-target]', function () {
                toggleInputLainnya($(this));
            });

            /**
             * Inisialisasi kondisi awal.
             *
             * Ini penting setelah data dari AJAX dimasukkan
             * ke checkbox.
             */
            function initInputLainnya() {
                $('.single-checkbox[data-target]').each(function () {
                    toggleInputLainnya($(this));
                });
            }

            // Jalankan saat halaman pertama kali selesai dimuat
            initInputLainnya();
        // ==========================
        // ENDED _ DISABLED AFTER CHECKBOX IS TRUE / ADA (ONLY INPUT LAINNYA)
        // ==========================

        $(document).on(
            'input',
            'input[type="number"][min][max]',
            function () {

                const min = parseFloat(this.min);
                const max = parseFloat(this.max);
                const value = parseFloat(this.value);

                // Kosongkan jika nilai di luar range
                if (!isNaN(value) && (value < min || value > max)) {
                    this.value = '';
                }
            }
        );

        // Menandai menu aktif jika URL sama (opsional)
        const menu = $('#pengkajianMenu');
        let pageUrl = window.location.href.split(/[?#]/)[0];

        $('.component-list-card a').each(function () {
            if ($(this).attr('href') !== '' && this.href === pageUrl) {
                $(this).addClass('active');
            }
        });

        // ==========================
// SEARCH MENU FORM
// ==========================
$('#compo-menu-search').on('input', function () {

    const keyword = $(this).val()
        .toLowerCase()
        .trim();

    const $menu = $('#pengkajianMenu');

    // =====================================================
    // RESET SEMUA MENU
    // =====================================================
    $menu.find('.menu-group-title').hide();
    $menu.find('.menu-item').hide();
    $menu.find('.menu-wrapper').hide();
    $menu.find('.menu-child').hide();

    $menu.find('.submenu').removeClass('show');

    $menu.find('.submenu-icon')
        .removeClass('ti-chevron-up')
        .addClass('ti-chevron-down');

    // =====================================================
    // SEARCH KOSONG
    // =====================================================
    if (keyword === '') {

        $menu.find('.menu-group-title').show();
        $menu.find('.menu-item').show();
        $menu.find('.menu-wrapper').show();
        $menu.find('.menu-child').show();

        return;
    }

    // =====================================================
    // PECAH KEYWORD MENJADI KATA
    // Contoh:
    // "transfer pasien"
    // menjadi ["transfer", "pasien"]
    // =====================================================
    const keywords = keyword
        .split(/\s+/)
        .filter(Boolean);

    const matchedGroups = new Set();

    // =====================================================
    // FUNGSI NORMALISASI TEXT
    // =====================================================
    function normalizeText(text) {
        return String(text || '')
            .toLowerCase()
            .replace(/\s+/g, ' ')
            .trim();
    }

    // =====================================================
    // FUNGSI CEK KEYWORD
    // SEMUA KATA HARUS ADA
    // =====================================================
    function isMatch(text) {

        const normalizedText = normalizeText(text);

        return keywords.every(function (word) {
            return normalizedText.includes(word);
        });
    }

    // =====================================================
    // 1. MENU BIASA
    // Contoh:
    // Lembar Transfer Pasien
    // =====================================================
    $menu.find('.menu-item').each(function () {

        const $item = $(this);

        // Ambil span pertama agar icon finalisasi
        // tidak ikut dihitung sebagai text pencarian
        const text = normalizeText(
            $item
                .children('span')
                .first()
                .text()
        );

        const group = String(
            $item.attr('data-group') || ''
        );

        if (isMatch(text)) {

            $item.css('display', 'flex');

            if (group) {
                matchedGroups.add(group);
            }

        } else {

            $item.css('display', 'none');

        }
    });

    // =====================================================
    // 2. MENU DENGAN SUBMENU
    // =====================================================
    $menu.find('.menu-wrapper').each(function () {

        const $wrapper = $(this);

        const $parent = $wrapper
            .find('.menu-collapse')
            .first();

        // =================================================
        // TEXT PARENT
        // =================================================
        const parentText = normalizeText(
            $parent
                .children('span')
                .first()
                .text()
        );

        const group = String(
            $parent.attr('data-group') || ''
        );

        let childMatched = false;

        // =================================================
        // CEK SETIAP CHILD
        // =================================================
        $wrapper.find('.menu-child').each(function () {

            const $child = $(this);

            /*
             * Ambil TEXT CHILD SAJA.
             *
             * Tidak menggunakan parentText + childText.
             *
             * Clone digunakan agar icon <i> tidak ikut
             * mempengaruhi text pencarian.
             *
             * Ini juga membuat struktur berikut sama-sama
             * bisa dibaca:
             *
             * Rawat Jalan:
             * <i>...</i> Form Dewasa
             *
             * Rawat Inap:
             * <span>
             *     <i>...</i> Form Dewasa
             * </span>
             */

            const $clone = $child.clone();

            // Hapus semua icon dari hasil clone
            $clone.find('i').remove();

            const childText = normalizeText(
                $clone.text()
            );

            // =============================================
            // CHILD COCOK
            // =============================================
            if (isMatch(childText)) {

                $child.css('display', 'flex');

                childMatched = true;

            } else {

                $child.css('display', 'none');

            }
        });

        // =================================================
        // ADA CHILD YANG COCOK
        // =================================================
        if (childMatched) {

            // Tampilkan wrapper
            $wrapper.css('display', 'block');

            // Tampilkan parent
            $parent.css('display', 'flex');

            // Buka submenu
            $wrapper.find('.submenu')
                .addClass('show');

            // Ubah icon menjadi chevron-up
            $wrapper.find('.submenu-icon')
                .removeClass('ti-chevron-down')
                .addClass('ti-chevron-up');

            // Tandai group
            if (group) {
                matchedGroups.add(group);
            }

        } else {

            // =================================================
            // TIDAK ADA CHILD YANG COCOK
            // =================================================

            /*
             * Cek apakah PARENT sendiri cocok.
             *
             * Contoh:
             * search "rawat jalan"
             *
             * Maka hanya dropdown Rawat Jalan yang tampil,
             * tanpa otomatis menampilkan seluruh child.
             */

            if (isMatch(parentText)) {

                $wrapper.css('display', 'block');

                $parent.css('display', 'flex');

                if (group) {
                    matchedGroups.add(group);
                }

            } else {

                $wrapper.css('display', 'none');

            }
        }
    });

    // =====================================================
    // 3. GROUP TITLE
    // HANYA TAMPIL JIKA GROUP MEMILIKI HASIL
    // =====================================================
    $menu.find('.menu-group-title').each(function () {

        const $title = $(this);

        const group = String(
            $title.attr('data-group') || ''
        );

        if (matchedGroups.has(group)) {

            $title.css('display', 'block');

        } else {

            $title.css('display', 'none');

        }
    });

});

        // ==========================
        // CLICK MENU FORM
        // ==========================
        $(document).on(
            'click',
            '#pengkajianMenu .menu-item, #pengkajianMenu .menu-child',
            function (e) {

                e.preventDefault();

                $('#pengkajianMenu')
                    .find('.menu-item, .menu-child')
                    .removeClass('active');

                $(this).addClass('active');

                loadForm($(this).data('form'));
            }
        );

        // =========================================================
        // SIDEBAR MINIMIZE
        // =========================================================
        function setSidebarMinimized(minimized) {

            const $sidebar = $('#pengkajian-sidebar-col');
            const $content = $('#pengkajian-content-col');
            const $button = $('#btn-minimize-pengkajian');

            if (!$sidebar.length) {
                return;
            }

            const $icon = $button.find('i');

            $sidebar.toggleClass('minimized', minimized);
            $content.toggleClass('expanded', minimized);

            if (minimized) {

                $button.attr('title', 'Maximize Menu');

                $icon
                    .removeClass('ri-sidebar-fold-line')
                    .addClass('ri-sidebar-unfold-line');

            } else {

                $button.attr('title', 'Minimize Menu');

                $icon
                    .removeClass('ri-sidebar-unfold-line')
                    .addClass('ri-sidebar-fold-line');
            }
        }


        // =========================================================
        // RESTORE STATE
        // =========================================================

        if (window.innerWidth >= 1200) {

            const savedSidebarState =
                localStorage.getItem('pengkajianSidebarMinimized');

            setSidebarMinimized(savedSidebarState === 'true');
        }


        // =========================================================
        // BUTTON
        // =========================================================

        $('#btn-minimize-pengkajian').on('click', function () {

            const $sidebar = $('#pengkajian-sidebar-col');

            const minimized = !$sidebar.hasClass('minimized');

            setSidebarMinimized(minimized);

            localStorage.setItem(
                'pengkajianSidebarMinimized',
                minimized ? 'true' : 'false'
            );
        });

        // START INPUT SEARCH JS
        const searchInput = $('#compo-menu-search');
        const clearButton = $('#clear-search');

        searchInput.on('input', function () {
            clearButton.prop('hidden', $(this).val().trim() === '');
        });

        clearButton.on('click', function () {
            searchInput.val('').trigger('input').focus();
        });
        // END INPUT SEARCH JS

    });

    // ==========================
    // LOAD FORM AJAX
    // ==========================
    function loadForm(form)
    {
        $('#formContent').html(`
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex flex-column align-items-center justify-content-center py-5">
                        <div class="spinner-border text-primary mb-3"></div>
                        <h6 class="mb-1">
                            Memuat Form...
                        </h6>
                        <small class="text-muted">
                            Mohon tunggu sebentar...
                        </small>
                    </div>
                </div>
            </div>
        `);

        $.ajax({
            url: `/v2/erm/form/${form}/${kunjungan}`,
            type: 'GET',
            success: function (html) {
                console.log(form);
                content = `<div class="card">
                                <div class="card-body p-3">
                                    ${html}
                                </div>
                            </div>`;
                $('#formContent').html(content);
            },
            error: function () {
                $('#formContent').html(`
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="alert alert-danger mb-0">
                                <strong>Gagal!</strong><br>
                                Form tidak dapat dimuat.
                            </div>
                        </div>
                    </div>
                `);
            }
        });
    }

    // ==========================
    // GET VALUE FORM BY NAME
    // ==========================
    function getFormDataByName($wrapper, additionalData = {}) {
        const data = { ...additionalData };

        $wrapper.find('[name]').each(function () {
            const $field = $(this);
            const name = this.name;
            const type = (this.type || '').toLowerCase();

            // Radio
            if (type === 'radio') {
                if ($field.is(':checked')) {
                    data[name] = $field.val();
                }
                return;
            }

            // // Checkbox
            // if (type === 'checkbox') {
            //     if (!(name in data)) {
            //         data[name] = null;
            //     }

            //     if ($field.is(':checked')) {
            //         data[name] = $field.val() || 1;
            //     }

            //     return;
            // }

            // ==========================================================
            // CHECKBOX
            // ==========================================================
            if (type === 'checkbox') {

                // ======================================================
                // CHECKBOX ARRAY
                // ======================================================
                if (name.endsWith('[]')) {

                    if (!Array.isArray(data[name])) {
                        data[name] = [];
                    }

                    if ($field.is(':checked')) {
                        data[name].push(
                            $field.val() || 1
                        );
                    }

                    return;
                }


                // ======================================================
                // CHECKBOX BIASA
                // ======================================================
                if (!(name in data)) {
                    data[name] = null;
                }

                if ($field.is(':checked')) {
                    data[name] = $field.val() || 1;
                }

                return;
            }

            // Input biasa, select, textarea
            data[name] = $field.val();
        });

        return data;
    }

    window.updatePenandaFinalisasi = function (formKey, isFinal) {
        const $icons = $(
            `.js-final-icon[data-final-key="${formKey}"]`
        );

        if (!$icons.length) {
            console.warn(
                'Icon penanda tidak ditemukan untuk formKey:',
                formKey
            );
            return;
        }

        $icons.toggleClass('d-none', !isFinal);
    };

    window.tampilkanPenandaFinalisasi = function () {
        const formKeys = $('.js-final-icon').map(function () {
            return $(this).data('final-key');
        }).get();

        $KUNJUNGAN = @json($list["KUNJUNGAN"]);

        $.ajax({
            url: `/api/v2/emr/pengkajian/status-finalisasi/${$KUNJUNGAN}`,
            type: 'GET',
            dataType: 'json',
            cache: false,
            data: {
                formKeys: formKeys,
                _ts: Date.now()
            },

            success: function (response) {
                const data = response?.data || {};

                Object.entries(data).forEach(function ([formKey, item]) {
                    window.updatePenandaFinalisasi(
                        formKey,
                        Boolean(item?.is_final)
                    );
                });
            }
        });
    };

    window.tampilkanPenandaFinalisasi();

    // ==========================
    // GET CPPT
    // ==========================
    function showCppt(kjg) {
        $('#show-id-cppt').text(kjg);
        const $btnCppt = $('.btnLihatCPPT');
        $.ajax({
            url: "/api/pasien/"+kjg+"/cppt",
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                $btnCppt.prop('disabled', true).html('<i class="ri-refresh-line ri-spin me-1"></i> Memuat CPPT...');
            },
            success: function(res) {
                $("#tampil-cppt").empty();
                $('#show-norm-cppt').text(res.pen.NORM);
                if (res.show.length != 0) {
                    res.show.forEach(item => {
                        content = ``;
                        content += `<tr>
                                        <td class="custom-column-cppt">${item.TANGGAL}</td>
                                        <td class="custom-column-cppt">${item.CATATAN}<br>${item.INSTRUKSI?"<b>I/ : </b>"+item.INSTRUKSI:''}</td>
                                        <td class="custom-column-cppt">${item.PPA}<br><span class="badge rounded-pill text-bg-primary">${item.JNSPPA}</span></td>
                                        <td class="custom-column-cppt">${item.TBAK_SBAR?item.TBAK_SBAR:'-'}</td>
                                        <td class="custom-column-cppt">${item.VERIFIKASI?'Diverifkasi Oleh<br><b class="text-success">'+item.VERIFIKATOR+'</b><br>Pada '+item.TGLVERIFIKASI:'Belum Diverifikasi'}</td>
                                    </tr>
                        `;
                        $('#tampil-cppt').append(content);
                    })
                    $('#showCppt').modal('show');
                } else {
                    iziToast.error({
                        title: 'Maaf!',
                        message: 'Data CPPT tidak ditemukan / belum diisi',
                        position: 'topRight'
                    });
                }
            },
            error: function (xhr) {
                let message = 'Data gagal ditampilkan.';

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors)
                        .flat()
                        .join('<br>');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                iziToast.error({
                    title: 'Proses Gagal!',
                    message: message,
                    position: 'topRight'
                });
            },
            complete: function() {
                $btnCppt.prop('disabled', false).html('<i class="ri-booklet-line me-1"></i> Lihat CPPT');
            }
        })
    }

    // FOR STATUS BAR PROGRESS API
    (function ($) {
    })(jQuery);
</script>
