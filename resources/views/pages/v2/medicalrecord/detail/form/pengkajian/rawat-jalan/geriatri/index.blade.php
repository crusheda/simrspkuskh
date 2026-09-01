<div class="d-flex justify-content-between align-items-center">
    <button
        type="button"
        class="btn btn-danger waves-effect waves-light collapsed"
        id="btnDokterRJG"
        data-bs-toggle="collapse"
        data-bs-target="#rjg_dokter"
        aria-expanded="false"
        aria-controls="rjg_dokter"
    >
        <i class="ri-stethoscope-line me-1"></i>
        Pengkajian Medis
    </button>

    <h6 id="pilihPengkajianRJG" class="mb-0 pilih-form-title">
        Pilih Form Pengkajian
    </h6>

    <button
        type="button"
        class="btn btn-success waves-effect waves-light collapsed"
        id="btnPerawatRJG"
        data-bs-toggle="collapse"
        data-bs-target="#rjg_perawat"
        aria-expanded="false"
        aria-controls="rjg_perawat"
    >
        <i class="ri-stethoscope-line me-1"></i>
        Pengkajian Keperawatan
    </button>
</div>

<div class="accordion" id="rjgAccordion">

    <div
        class="multi-collapse collapse"
        data-bs-parent="#rjgAccordion"
        id="rjg_dokter"
        data-kunjungan="{{ $list['kunjungan'] }}"
        data-form-key="rjg_dokter"
        data-url="{{ route('v2.emr.form.sub.load', ['kunjungan' => $list['kunjungan'], 'formKey' => 'rjg_dokter']) }}"
    >
        <div class="form-content mt-3"></div>
    </div>

    <div
        class="multi-collapse collapse"
        data-bs-parent="#rjgAccordion"
        id="rjg_perawat"
        data-kunjungan="{{ $list['kunjungan'] }}"
        data-form-key="rjg_perawat"
        data-url="{{ route('v2.emr.form.sub.load', ['kunjungan' => $list['kunjungan'], 'formKey' => 'rjg_perawat']) }}"
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

        function updateRawatJalanGeriatriButton() {
            $('#btnDokterRJG').prop(
                'disabled',
                $('#rjg_dokter').hasClass('show')
            );

            $('#btnPerawatRJG').prop(
                'disabled',
                $('#rjg_perawat').hasClass('show')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | LOAD FORM
        |--------------------------------------------------------------------------
        */

        function loadRawatJalanGeriatriForm($section) {
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
                    'URL atau container form Rawat Jalan Geriatri tidak ditemukan.'
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
                |--------------------------------------------------------------------------
                */

                if (!$section.hasClass('show')) {
                    console.log(
                        '[RJG] Response diabaikan karena section sudah ditutup:',
                        $section.attr('id')
                    );
                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | Hapus content section lainnya.
                |
                | Penting agar elemen ID/name yang sama dari dokter dan
                | perawat tidak berada bersamaan di DOM.
                |--------------------------------------------------------------------------
                */

                $('#rjgAccordion')
                    .find('#rjg_dokter, #rjg_perawat')
                    .not($section)
                    .find('.form-content')
                    .empty();

                $('#rjgAccordion')
                    .find('#rjg_dokter, #rjg_perawat')
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
                | Tandai sudah berhasil dimuat.
                |--------------------------------------------------------------------------
                */

                $section.data('loaded', true);

                console.log(
                    '[RJG] Form berhasil dimuat:',
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
                        '[RJG] AJAX di-abort:',
                        $section.data('form-key')
                    );
                    return;
                }

                console.error(
                    'Gagal memuat form Rawat Jalan Geriatri:',
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
            | INITIAL STATE
            |--------------------------------------------------------------------------
            |
            | Tidak ada form yang otomatis di-load.
            |
            | Kedua accordion tertutup.
            | Kedua tombol aktif.
            |--------------------------------------------------------------------------
            */

            updateRawatJalanGeriatriButton();

            /*
            |--------------------------------------------------------------------------
            | HIDE TEXT FORM
            |--------------------------------------------------------------------------
            */

            $('#btnDokterRJG, #btnPerawatRJG')
                .off('click.rjgPengkajian')
                .on('click.rjgPengkajian', function () {
                    $('#pilihPengkajianRJG').addClass('is-hidden');
                });

            /*
            |--------------------------------------------------------------------------
            | ACCORDION SHOWN
            |--------------------------------------------------------------------------
            |
            | Form baru di-load setelah accordion benar-benar terbuka.
            |--------------------------------------------------------------------------
            */

            $('#rjgAccordion')
                .off(
                    'shown.bs.collapse.rjgPengkajian',
                    '#rjg_dokter, #rjg_perawat'
                )
                .on(
                    'shown.bs.collapse.rjgPengkajian',
                    '#rjg_dokter, #rjg_perawat',
                    function () {

                        const $section = $(this);

                        console.log(
                            '[RJG] Accordion dibuka:',
                            $section.attr('id')
                        );

                        /*
                        |--------------------------------------------------------------------------
                        | Update tombol
                        |--------------------------------------------------------------------------
                        */

                        updateRawatJalanGeriatriButton();

                        /*
                        |--------------------------------------------------------------------------
                        | Load form
                        |--------------------------------------------------------------------------
                        */

                        loadRawatJalanGeriatriForm($section);

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

            $('#rjgAccordion')
                .off(
                    'hidden.bs.collapse.rjgPengkajian',
                    '#rjg_dokter, #rjg_perawat'
                )
                .on(
                    'hidden.bs.collapse.rjgPengkajian',
                    '#rjg_dokter, #rjg_perawat',
                    function () {

                        const $section = $(this);

                        updateRawatJalanGeriatriButton();

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
                    'change.rjgSingleCheckbox',
                    '#rjgAccordion .form-content .single-checkbox'
                )
                .on(
                    'change.rjgSingleCheckbox',
                    '#rjgAccordion .form-content .single-checkbox',
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
                    'change.rjgSingleCheckboxBos',
                    '#rjgAccordion .form-content .single-checkbox-bos'
                )
                .on(
                    'change.rjgSingleCheckboxBos',
                    '#rjgAccordion .form-content .single-checkbox-bos',
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
            | FINAL INITIAL STATE
            |--------------------------------------------------------------------------
            */

            updateRawatJalanGeriatriButton();

        });

    })(jQuery);
</script>
