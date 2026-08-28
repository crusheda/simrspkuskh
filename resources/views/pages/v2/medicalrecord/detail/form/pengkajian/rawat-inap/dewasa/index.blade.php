<div class="d-flex justify-content-between align-items-center">
    <button
        type="button"
        class="btn btn-danger waves-effect waves-light"
        id="btnDokter"
        data-bs-toggle="collapse"
        data-bs-target="#riD_dokter"
        aria-expanded="true"
        aria-controls="riD_dokter"
    >
        <i class="ri-stethoscope-line me-1"></i>
        Pengkajian Medis
    </button>
    <h6 id="pilihPengkajian" class="mb-0 pilih-form-title">Pilih Form Pengkajian</h6>
    <button
        type="button"
        class="btn btn-success waves-effect waves-light collapsed"
        id="btnPerawat"
        data-bs-toggle="collapse"
        data-bs-target="#riD_perawat"
        aria-expanded="false"
        aria-controls="riD_perawat"
    >
        <i class="ri-stethoscope-line me-1"></i>
        Pengkajian Keperawatan
    </button>
</div>
<div class="accordion" id="ridAccordion">
    <div
        class="multi-collapse collapse"
        data-bs-parent="#ridAccordion"
        id="riD_dokter"
        data-kunjungan="{{ $list['kunjungan'] }}"
        data-form-key="rid_dokter"
        data-url="{{ route('v2.emr.form.sub.rawat-inap.load', ['kunjungan' => $list['kunjungan'], 'formKey' => 'rid_dokter']) }}"
    >
        <div class="form-content mt-3"></div>
    </div>
    <div
        class="multi-collapse collapse"
        data-bs-parent="#ridAccordion"
        id="riD_perawat"
        data-kunjungan="{{ $list['kunjungan'] }}"
        data-form-key="rid_perawat"
        data-url="{{ route('v2.emr.form.sub.rawat-inap.load', ['kunjungan' => $list['kunjungan'], 'formKey' => 'rid_perawat']) }}"
    >
        <div class="form-content mt-3"></div>
    </div>
</div>
<script>
    (function ($) {
        'use strict';

        /*
        |--------------------------------------------------------------------------
        | UPDATE BUTTON
        |--------------------------------------------------------------------------
        |
        | Saat awal kedua tombol aktif karena kedua accordion tertutup.
        | Setelah salah satu accordion terbuka, tombol accordion tersebut
        | akan disabled.
        |
        |--------------------------------------------------------------------------
        */

        function updateRanapDewasaButton() {
            $('#btnDokter').prop(
                'disabled',
                $('#riD_dokter').hasClass('show')
            );
            $('#btnPerawat').prop(
                'disabled',
                $('#riD_perawat').hasClass('show')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | LOAD FORM
        |--------------------------------------------------------------------------
        */

        function loadRanapDewasaForm($section) {
            if (!$section || !$section.length) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Jangan load ulang jika sudah pernah berhasil dimuat.
            |--------------------------------------------------------------------------
            */

            if (
                $section.data('loaded') === true ||
                $section.data('loading') === true
            ) {
                return;
            }

            const url = $section.data('url');
            const $content =
                $section.find('.form-content');

            /*
            |--------------------------------------------------------------------------
            | Validasi
            |--------------------------------------------------------------------------
            */

            if (!url || !$content.length) {
                console.error(
                    'URL atau container form Rawat Inap Dewasa tidak ditemukan.'
                );
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Loading
            |--------------------------------------------------------------------------
            */

            $section.data('loading', true);
            $content.html(`
                <div class="text-center py-4">
                    <div
                        class="spinner-border text-primary"
                        role="status"
                    >
                        <span class="visually-hidden">
                            Memuat...
                        </span>
                    </div>
                    <div class="mt-2">
                        Memuat sub formulir...
                    </div>
                </div>
            `);

            /*
            |--------------------------------------------------------------------------
            | AJAX GET
            |--------------------------------------------------------------------------
            */

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'html',
                cache: false
            })
            .done(function (html) {
                /*
                |--------------------------------------------------------------------------
                | Pastikan section masih terbuka.
                |
                | Kalau user sudah pindah ke form lain sebelum response
                | selesai, response ini tidak ditampilkan.
                |--------------------------------------------------------------------------
                */

                if (!$section.hasClass('show')) {
                    console.log(
                        '[RI] Response diabaikan karena section sudah ditutup:',
                        $section.attr('id')
                    );
                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | Hapus content section lainnya.
                |
                | Ini penting supaya tidak ada elemen ID/name yang sama
                | dari dokter dan perawat berada bersamaan di DOM.
                |--------------------------------------------------------------------------
                */

                $('#ridAccordion')
                    .find('.multi-collapse')
                    .not($section)
                    .find('.form-content')
                    .empty();

                $('#ridAccordion')
                    .find('.multi-collapse')
                    .not($section)
                    .removeData('loaded');

                /*
                |--------------------------------------------------------------------------
                | Masukkan partial
                |--------------------------------------------------------------------------
                */

                $content
                    .empty()
                    .append(html);

                /*
                |--------------------------------------------------------------------------
                | Tandai sudah berhasil dimuat
                |--------------------------------------------------------------------------
                */

                $section.data('loaded', true);
                console.log(
                    '[RI] Form berhasil dimuat:',
                    $section.data('form-key')
                );
            })
            .fail(function (xhr, status, error) {
                /*
                |--------------------------------------------------------------------------
                | Kalau AJAX dibatalkan, jangan tampilkan error.
                |--------------------------------------------------------------------------
                */

                if (status === 'abort') {
                    console.log(
                        '[RI] AJAX di-abort:',
                        $section.data('form-key')
                    );
                    return;
                }

                console.error(
                    'Gagal memuat form Rawat Inap Dewasa:',
                    {
                        status: status,
                        error: error,
                        response: xhr.responseText
                    }
                );

                /*
                |--------------------------------------------------------------------------
                | Hanya tampilkan error jika section masih terbuka.
                |--------------------------------------------------------------------------
                */

                if ($section.hasClass('show')) {
                    $content.html(`
                        <div class="alert alert-danger mb-0">
                            <i class="ri-error-warning-line me-1"></i>
                            Form gagal dimuat.
                            Silakan coba kembali.
                        </div>
                    `);
                }
            })
            .always(function () {
                $section.removeData('loading');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | DOCUMENT READY
        |--------------------------------------------------------------------------
        */

        $(function () {
            /*
            |--------------------------------------------------------------------------
            | PENTING:
            |
            | Tidak ada loadRanapDewasaForm() di sini.
            |
            | Jadi saat pertama kali halaman dibuka:
            |
            | - Dokter tidak otomatis GET
            | - Perawat tidak otomatis GET
            | - Kedua tombol tetap aktif
            | - Kedua accordion tetap tertutup
            |--------------------------------------------------------------------------
            */

            updateRanapDewasaButton();

            // HIDE TEXT FORM pilih pengkajian
            $('#btnDokter, #btnPerawat')
                .off('click')
                .on('click', function () {
                    $('#pilihPengkajian').addClass('is-hidden');
                });

            /*
            |--------------------------------------------------------------------------
            | ACCORDION SHOWN
            |--------------------------------------------------------------------------
            |
            | Form baru di-load setelah accordion benar-benar terbuka.
            |--------------------------------------------------------------------------
            */

            $('#ridAccordion')
                .off(
                    'shown.bs.collapse.ridRanap',
                    '#riD_dokter, #riD_perawat'
                )
                .on(
                    'shown.bs.collapse.ridRanap',
                    '#riD_dokter, #riD_perawat',
                    function () {
                        const $section =
                            $(this);

                        console.log(
                            '[RI] Accordion dibuka:',
                            $section.attr('id')
                        );

                        /*
                        |--------------------------------------------------------------------------
                        | Update tombol
                        |--------------------------------------------------------------------------
                        */

                        updateRanapDewasaButton();

                        /*
                        |--------------------------------------------------------------------------
                        | Load form
                        |--------------------------------------------------------------------------
                        */

                        loadRanapDewasaForm(
                            $section
                        );

                        /*
                        |--------------------------------------------------------------------------
                        | Scroll content ke atas
                        |--------------------------------------------------------------------------
                        */

                        $section
                            .find('.form-content')
                            .scrollTop(0);
                    }
                );

            /*
            |--------------------------------------------------------------------------
            | ACCORDION HIDDEN
            |--------------------------------------------------------------------------
            */

            $('#ridAccordion')
                .off(
                    'hidden.bs.collapse.ridRanap',
                    '#riD_dokter, #riD_perawat'
                )
                .on(
                    'hidden.bs.collapse.ridRanap',
                    '#riD_dokter, #riD_perawat',
                    function () {
                        const $section =
                            $(this);

                        updateRanapDewasaButton();

                        $section
                            .find('.form-content')
                            .scrollTop(0);
                    }
                );

            /*
            |--------------------------------------------------------------------------
            | SINGLE CHECKBOX
            |--------------------------------------------------------------------------
            */

            $(document)
                .off(
                    'change.ridSingleCheckbox',
                    '#ridAccordion .form-content .single-checkbox'
                )
                .on(
                    'change.ridSingleCheckbox',
                    '#ridAccordion .form-content .single-checkbox',
                    function () {
                        if (!this.checked) {
                            return;
                        }

                        $(this)
                            .closest('.form-content')
                            .find(
                                `input.single-checkbox[name="${this.name}"]`
                            )
                            .not(this)
                            .prop(
                                'checked',
                                false
                            );
                    }
                );

            /*
            |--------------------------------------------------------------------------
            | SINGLE CHECKBOX BOS
            |--------------------------------------------------------------------------
            */

            $(document)
                .off(
                    'change.ridSingleCheckboxBos',
                    '#ridAccordion .form-content .single-checkbox-bos'
                )
                .on(
                    'change.ridSingleCheckboxBos',
                    '#ridAccordion .form-content .single-checkbox-bos',
                    function () {
                        /*
                        |--------------------------------------------------------------------------
                        | Tidak boleh uncheck semuanya.
                        |--------------------------------------------------------------------------
                        */

                        if (!this.checked) {
                            this.checked = true;
                            return;
                        }

                        $(this)
                            .closest('.form-content')
                            .find(
                                `input.single-checkbox-bos[name="${this.name}"]`
                            )
                            .not(this)
                            .prop(
                                'checked',
                                false
                            );
                    }
                );

            /*
            |--------------------------------------------------------------------------
            | INITIAL STATE
            |--------------------------------------------------------------------------
            |
            | Tidak ada form yang di-load.
            |
            | Kedua tombol aktif.
            |--------------------------------------------------------------------------
            */

            updateRanapDewasaButton();
        });
    })(jQuery);
</script>
