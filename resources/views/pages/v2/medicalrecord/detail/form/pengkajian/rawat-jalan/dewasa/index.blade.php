<div class="d-flex justify-content-between align-items-center">
    <button
        type="button"
        class="btn btn-danger waves-effect waves-light collapsed"
        id="btnDokterRJD"
        data-bs-toggle="collapse"
        data-bs-target="#rjd_dokter"
        aria-expanded="false"
        aria-controls="rjd_dokter"
    >
        <i class="ri-stethoscope-line me-1"></i>
        Pengkajian Medis
    </button>

    <h6 id="pilihPengkajianRJD" class="mb-0 pilih-form-title">
        Pilih Form Pengkajian
    </h6>

    <button
        type="button"
        class="btn btn-success waves-effect waves-light collapsed"
        id="btnPerawatRJD"
        data-bs-toggle="collapse"
        data-bs-target="#rjd_perawat"
        aria-expanded="false"
        aria-controls="rjd_perawat"
    >
        <i class="ri-stethoscope-line me-1"></i>
        Pengkajian Keperawatan
    </button>
</div>

<div class="accordion" id="rjdAccordion">

    <div
        class="multi-collapse collapse"
        data-bs-parent="#rjdAccordion"
        id="rjd_dokter"
        data-kunjungan="{{ $list['kunjungan'] }}"
        data-form-key="rjd_dokter"
        data-url="{{ route('v2.emr.form.sub.load', ['kunjungan' => $list['kunjungan'], 'formKey' => 'rjd_dokter']) }}"
    >
        <div class="form-content mt-3"></div>
    </div>

    <div
        class="multi-collapse collapse"
        data-bs-parent="#rjdAccordion"
        id="rjd_perawat"
        data-kunjungan="{{ $list['kunjungan'] }}"
        data-form-key="rjd_perawat"
        data-url="{{ route('v2.emr.form.sub.load', ['kunjungan' => $list['kunjungan'], 'formKey' => 'rjd_perawat']) }}"
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

        function updateRawatJalanDewasaButton() {
            $('#btnDokterRJD').prop(
                'disabled',
                $('#rjd_dokter').hasClass('show')
            );

            $('#btnPerawatRJD').prop(
                'disabled',
                $('#rjd_perawat').hasClass('show')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | LOAD FORM
        |--------------------------------------------------------------------------
        */

        function loadRawatJalanDewasaForm($section) {
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
                    'URL atau container form Rawat Jalan Dewasa tidak ditemukan.'
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
                        '[RJD] Response diabaikan karena section sudah ditutup:',
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

                $('#rjdAccordion')
                    .find('#rjd_dokter, #rjd_perawat')
                    .not($section)
                    .find('.form-content')
                    .empty();

                $('#rjdAccordion')
                    .find('#rjd_dokter, #rjd_perawat')
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
                    '[RJD] Form berhasil dimuat:',
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
                        '[RJD] AJAX di-abort:',
                        $section.data('form-key')
                    );
                    return;
                }

                console.error(
                    'Gagal memuat form Rawat Jalan Dewasa:',
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
            | Tidak ada loadRawatJalanDewasaForm() di sini.
            |
            | Jadi saat pertama kali halaman dibuka:
            |
            | - Dokter tidak otomatis GET
            | - Perawat tidak otomatis GET
            | - Kedua tombol tetap aktif
            | - Kedua accordion tetap tertutup
            |
            |--------------------------------------------------------------------------
            */

            updateRawatJalanDewasaButton();

            /*
            |--------------------------------------------------------------------------
            | HIDE TEXT FORM
            |--------------------------------------------------------------------------
            */

            $('#btnDokterRJD, #btnPerawatRJD')
                .off('click.rjdPengkajian')
                .on('click.rjdPengkajian', function () {
                    $('#pilihPengkajianRJD').addClass('is-hidden');
                });

            /*
            |--------------------------------------------------------------------------
            | ACCORDION SHOWN
            |--------------------------------------------------------------------------
            |
            | Form baru di-load setelah accordion benar-benar terbuka.
            |--------------------------------------------------------------------------
            */

            $('#rjdAccordion')
                .off(
                    'shown.bs.collapse.rjdPengkajian',
                    '#rjd_dokter, #rjd_perawat'
                )
                .on(
                    'shown.bs.collapse.rjdPengkajian',
                    '#rjd_dokter, #rjd_perawat',
                    function () {

                        const $section = $(this);

                        console.log(
                            '[RJD] Accordion dibuka:',
                            $section.attr('id')
                        );

                        /*
                        |--------------------------------------------------------------------------
                        | Update tombol
                        |--------------------------------------------------------------------------
                        */

                        updateRawatJalanDewasaButton();

                        /*
                        |--------------------------------------------------------------------------
                        | Load form
                        |--------------------------------------------------------------------------
                        */

                        loadRawatJalanDewasaForm($section);

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

            $('#rjdAccordion')
                .off(
                    'hidden.bs.collapse.rjdPengkajian',
                    '#rjd_dokter, #rjd_perawat'
                )
                .on(
                    'hidden.bs.collapse.rjdPengkajian',
                    '#rjd_dokter, #rjd_perawat',
                    function () {

                        const $section = $(this);

                        updateRawatJalanDewasaButton();

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
                    'change.rjdSingleCheckbox',
                    '#rjdAccordion .form-content .single-checkbox'
                )
                .on(
                    'change.rjdSingleCheckbox',
                    '#rjdAccordion .form-content .single-checkbox',
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
                    'change.rjdSingleCheckboxBos',
                    '#rjdAccordion .form-content .single-checkbox-bos'
                )
                .on(
                    'change.rjdSingleCheckboxBos',
                    '#rjdAccordion .form-content .single-checkbox-bos',
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

            updateRawatJalanDewasaButton();

        });

    })(jQuery);
</script>
