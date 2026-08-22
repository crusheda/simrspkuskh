@push('styles')
    <link rel="stylesheet" href="{{ asset('v2/css/folder/tab_pengkajian.css') }}">
@endpush
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
                            title="Minimize Menu" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Minimize Sidebar">
                            <i class="ri-sidebar-fold-line fs-25"></i>
                        </button>

                    </div>
                    <div class="card-body p-0 menu-scroll" id="pengkajianMenu">
                        <div class="list-group">

                            <div class="list-group-item menu-group-title p-2" data-group="awal">
                                <h5 class="mt-2 ms-3">PENGKAJIAN AWAL</h5>
                            </div>

                            <!-- Gawat Darurat -->
                            <a href="javascript:void(0);" class="list-group-item list-group-item-action menu-item menu-parent" data-form="pengkajian-gd" data-group="awal">
                                Form Pengkajian Gawat Darurat
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
                                            class="list-group-item list-group-item-action ps-5 menu-child"
                                            data-form="pengkajian-ranap-dewasa"
                                            data-group="awal">
                                            <i class="ph-duotone ph-arrow-elbow-down-right me-1"></i>
                                            Form Dewasa
                                        </a>
                                        <a href="javascript:void(0);"
                                            class="list-group-item list-group-item-action ps-5 menu-child"
                                            data-form="pengkajian-ranap-anak"
                                            data-group="awal">
                                            <i class="ph-duotone ph-arrow-elbow-down-right me-1"></i>
                                            Form Anak
                                        </a>
                                        <a href="javascript:void(0);"
                                            class="list-group-item list-group-item-action ps-5 menu-child"
                                            data-form="pengkajian-ranap-neonatus"
                                            data-group="awal">
                                            <i class="ph-duotone ph-arrow-elbow-down-right me-1"></i>
                                            Form Neonatus
                                        </a>
                                        <a href="javascript:void(0);"
                                            class="list-group-item list-group-item-action ps-5 menu-child"
                                            data-form="pengkajian-ranap-obsgyn"
                                            data-group="awal">
                                            <i class="ph-duotone ph-arrow-elbow-down-right me-1"></i>
                                            Form Obstetri dan Ginekologi
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="list-group-item menu-group-title p-2" data-group="khusus">
                                <h5 class="mt-2 ms-3">PENGKAJIAN KHUSUS</h5>
                            </div>

                            <a href="javascript:void(0);" class="list-group-item list-group-item-action menu-item menu-parent" data-form="pengkajian-khusus-remaja" data-group="khusus">
                                Form Pengkajian Remaja
                            </a>
                            <a href="javascript:void(0);" class="list-group-item list-group-item-action menu-item menu-parent" data-form="pengkajian-khusus-terminal" data-group="khusus">
                                Form Pengkajian Terminal
                            </a>
                            <a href="javascript:void(0);" class="list-group-item list-group-item-action menu-item menu-parent" data-form="pengkajian-khusus-nyeri-kronik" data-group="khusus">
                                Form Pengkajian Nyeri Kronik
                            </a>
                            <a href="javascript:void(0);" class="list-group-item list-group-item-action menu-item menu-parent" data-form="pengkajian-khusus-sistem-imun-terganggu" data-group="khusus">
                                Form Pengkajian Sistem Imun Terganggu
                            </a>
                            <a href="javascript:void(0);" class="list-group-item list-group-item-action menu-item menu-parent" data-form="pengkajian-khusus-kecanduan-obat-terlarang" data-group="khusus">
                                Form Pengkajian Kecanduan Obat Terlarang
                            </a>
                            <a href="javascript:void(0);" class="list-group-item list-group-item-action menu-item menu-parent" data-form="pengkajian-khusus-korban-kekerasan" data-group="khusus">
                                Form Pengkajian Korban Kekerasan
                            </a>
                            <a href="javascript:void(0);" class="list-group-item list-group-item-action menu-item menu-parent" data-form="pengkajian-khusus-penyakit-menular" data-group="khusus">
                                Form Pengkajian Penyakit Menular
                            </a>
                            <a href="javascript:void(0);" class="list-group-item list-group-item-action menu-item menu-parent" data-form="pengkajian-khusus-lanjutan" data-group="khusus">
                                Form Pengkajian Lanjutan
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
                <div class="card">
                    <div class="card-body p-3" id="formContent">
                        <div class="text-center">
                            Silakan pilih form.
                        </div>
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

            const keyword = $(this).val().trim().toLowerCase();

            // reset
            menu.find('.menu-group-title').hide();
            menu.find('.menu-item').hide();
            $('.menu-wrapper').hide();
            $('.menu-child').hide();

            $('.submenu').removeClass('show');

            $('.submenu-icon')
                .removeClass('ti-chevron-up')
                .addClass('ti-chevron-down');

            // ==========================
            // JIKA KOSONG
            // ==========================
            if (keyword === '') {

                $('.menu-group-title').show();
                menu.find('.menu-item').show();
                $('.menu-wrapper').show();
                $('.menu-child').show();

                return;
            }

            // ==========================
            // MENU TANPA SUBMENU
            // ==========================
            menu.find('.menu-item').each(function () {

                const text = $(this).text().trim().toLowerCase();

                if (text.includes(keyword)) {

                    $(this).show();

                    $('.menu-group-title[data-group="' + $(this).data('group') + '"]').show();
                }

            });

            // ==========================
            // MENU WRAPPER
            // ==========================
            $('.menu-wrapper').each(function () {

                const wrapper = $(this);
                const childs = wrapper.find('.menu-child');

                let found = false;

                childs.each(function () {

                    const text = $(this).text().trim().toLowerCase();

                    if (text.includes(keyword)) {
                        $(this).show();
                        found = true;
                    }

                });

                if (found) {

                    wrapper.show();

                    wrapper.find('.submenu')
                        .addClass('show');

                    wrapper.find('.submenu-icon')
                        .removeClass('ti-chevron-down')
                        .addClass('ti-chevron-up');

                    const group = wrapper.find('.menu-collapse').data('group');

                    $('.menu-group-title[data-group="' + group + '"]').show();
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
            <div class="d-flex flex-column align-items-center justify-content-center py-5">
                <div class="spinner-border text-primary mb-3"></div>
                <h6 class="mb-1">
                    Memuat Form...
                </h6>
                <small class="text-muted">
                    Mohon tunggu sebentar...
                </small>
            </div>
        `);

        $.ajax({
            url: `/v2/erm/form/${form}/${kunjungan}`,
            type: 'GET',
            success: function (html) {
                console.log(form);
                $('#formContent').html(html);
            },
            error: function () {
                $('#formContent').html(`
                    <div class="alert alert-danger mb-0">
                        <strong>Gagal!</strong><br>
                        Form tidak dapat dimuat.
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

            // Checkbox
            if (type === 'checkbox') {
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
</script>
