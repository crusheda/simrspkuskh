<div class="d-flex justify-content-between align-items-center">
    <button
        type="button"
        class="btn btn-danger waves-effect waves-light collapsed"
        id="btnDokterRJO"
        data-bs-toggle="collapse"
        data-bs-target="#rjo_dokter"
        aria-expanded="false"
        aria-controls="rjo_dokter"
    >
        <i class="ri-stethoscope-line me-1"></i>
        Pengkajian Medis
    </button>

    <h6 id="pilihPengkajianRJO" class="mb-0 pilih-form-title">
        Pilih Form Pengkajian
    </h6>

    <button
        type="button"
        class="btn btn-success waves-effect waves-light collapsed"
        id="btnPerawatRJO"
        data-bs-toggle="collapse"
        data-bs-target="#rjo_perawat"
        aria-expanded="false"
        aria-controls="rjo_perawat"
    >
        <i class="ri-stethoscope-line me-1"></i>
        Pengkajian Keperawatan
    </button>
</div>

<div class="accordion" id="rjoAccordion">

    <div
        class="multi-collapse collapse"
        data-bs-parent="#rjoAccordion"
        id="rjo_dokter"
        data-kunjungan="{{ $list['kunjungan'] }}"
        data-form-key="rjo_dokter"
        data-url="{{ route('v2.emr.form.sub.load', ['kunjungan' => $list['kunjungan'], 'formKey' => 'rjo_dokter']) }}"
    >
        <div class="form-content mt-3"></div>
    </div>

    <div
        class="multi-collapse collapse"
        data-bs-parent="#rjoAccordion"
        id="rjo_perawat"
        data-kunjungan="{{ $list['kunjungan'] }}"
        data-form-key="rjo_perawat"
        data-url="{{ route('v2.emr.form.sub.load', ['kunjungan' => $list['kunjungan'], 'formKey' => 'rjo_perawat']) }}"
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
        */

        function updateRawatJalanObsgynButton() {
            $('#btnDokterRJO').prop(
                'disabled',
                $('#rjo_dokter').hasClass('show')
            );

            $('#btnPerawatRJO').prop(
                'disabled',
                $('#rjo_perawat').hasClass('show')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | LOAD FORM
        |--------------------------------------------------------------------------
        */

        function loadRawatJalanObsgynForm($section) {
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
            const $content = $section.find('.form-content');

            /*
            |--------------------------------------------------------------------------
            | Validasi
            |--------------------------------------------------------------------------
            */

            if (!url || !$content.length) {
                console.error(
                    'URL atau container form Rawat Jalan Obsgyn tidak ditemukan.'
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
                        '[RJO] Response diabaikan karena section sudah ditutup:',
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

                $('#rjoAccordion')
                    .find('#rjo_dokter, #rjo_perawat')
                    .not($section)
                    .find('.form-content')
                    .empty();

                $('#rjoAccordion')
                    .find('#rjo_dokter, #rjo_perawat')
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
                    '[RJO] Form berhasil dimuat:',
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
                        '[RJO] AJAX di-abort:',
                        $section.data('form-key')
                    );
                    return;
                }

                console.error(
                    'Gagal memuat form Rawat Jalan Obsgyn:',
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
            | Tidak ada loadRawatJalanObsgynForm() di sini.
            |
            | Jadi saat pertama kali halaman dibuka:
            |
            | - Dokter tidak otomatis GET
            | - Perawat tidak otomatis GET
            | - Kedua tombol tetap aktif
            | - Kedua accordion tetap tertutup
            |--------------------------------------------------------------------------
            */

            updateRawatJalanObsgynButton();

            /*
            |--------------------------------------------------------------------------
            | HIDE TEXT FORM
            |--------------------------------------------------------------------------
            */

            $('#btnDokterRJO, #btnPerawatRJO')
                .off('click.rjoPengkajian')
                .on('click.rjoPengkajian', function () {
                    $('#pilihPengkajianRJO').addClass('is-hidden');
                });

            /*
            |--------------------------------------------------------------------------
            | ACCORDION SHOWN
            |--------------------------------------------------------------------------
            |
            | Form baru di-load setelah accordion benar-benar terbuka.
            |--------------------------------------------------------------------------
            */

            $('#rjoAccordion')
                .off(
                    'shown.bs.collapse.rjoPengkajian',
                    '#rjo_dokter, #rjo_perawat'
                )
                .on(
                    'shown.bs.collapse.rjoPengkajian',
                    '#rjo_dokter, #rjo_perawat',
                    function () {

                        const $section = $(this);

                        console.log(
                            '[RJO] Accordion dibuka:',
                            $section.attr('id')
                        );

                        /*
                        |--------------------------------------------------------------------------
                        | Update tombol
                        |--------------------------------------------------------------------------
                        */

                        updateRawatJalanObsgynButton();

                        /*
                        |--------------------------------------------------------------------------
                        | Load form
                        |--------------------------------------------------------------------------
                        */

                        loadRawatJalanObsgynForm($section);

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

            $('#rjoAccordion')
                .off(
                    'hidden.bs.collapse.rjoPengkajian',
                    '#rjo_dokter, #rjo_perawat'
                )
                .on(
                    'hidden.bs.collapse.rjoPengkajian',
                    '#rjo_dokter, #rjo_perawat',
                    function () {

                        const $section = $(this);

                        updateRawatJalanObsgynButton();

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
                    'change.rjoSingleCheckbox',
                    '#rjoAccordion .form-content .single-checkbox'
                )
                .on(
                    'change.rjoSingleCheckbox',
                    '#rjoAccordion .form-content .single-checkbox',
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
                    'change.rjoSingleCheckboxBos',
                    '#rjoAccordion .form-content .single-checkbox-bos'
                )
                .on(
                    'change.rjoSingleCheckboxBos',
                    '#rjoAccordion .form-content .single-checkbox-bos',
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
            */

            updateRawatJalanObsgynButton();

        });

    })(jQuery);
</script>
