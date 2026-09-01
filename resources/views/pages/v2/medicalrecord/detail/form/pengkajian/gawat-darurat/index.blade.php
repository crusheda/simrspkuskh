<div class="d-flex justify-content-between align-items-center">
    <button
        type="button"
        class="btn btn-danger waves-effect waves-light"
        id="btnDokter"
        data-bs-toggle="collapse"
        data-bs-target="#gd_dokter"
        aria-expanded="false"
        aria-controls="gd_dokter"
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
        data-bs-target="#gd_perawat"
        aria-expanded="false"
        aria-controls="gd_perawat"
    >
        <i class="ri-stethoscope-line me-1"></i>
        Pengkajian Keperawatan
    </button>
</div>
<div class="accordion" id="gdAccordion">
    <div
        class="multi-collapse collapse"
        data-bs-parent="#gdAccordion"
        id="gd_dokter"
        data-kunjungan="{{ $list['kunjungan'] }}"
        data-form-key="gd_dokter"
        data-url="{{ route('v2.emr.form.sub.load', ['kunjungan' => $list['kunjungan'], 'formKey' => 'gd_dokter']) }}"
    >
        <div class="form-content mt-3"></div>
    </div>
    <div
        class="multi-collapse collapse"
        data-bs-parent="#gdAccordion"
        id="gd_perawat"
        data-kunjungan="{{ $list['kunjungan'] }}"
        data-form-key="gd_perawat"
        data-url="{{ route('v2.emr.form.sub.load', ['kunjungan' => $list['kunjungan'], 'formKey' => 'gd_perawat']) }}"
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
        | Saat awal kedua accordion tertutup,
        | kedua tombol aktif.
        |
        | Setelah salah satu accordion terbuka,
        | tombol accordion tersebut menjadi disabled.
        |
        |--------------------------------------------------------------------------
        */

        function updateGawatDaruratButton() {

            $('#btnDokter').prop(
                'disabled',
                $('#gd_dokter').hasClass('show')
            );

            $('#btnPerawat').prop(
                'disabled',
                $('#gd_perawat').hasClass('show')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | LOAD FORM
        |--------------------------------------------------------------------------
        |
        | Form hanya di-load ketika accordion dibuka.
        |
        |--------------------------------------------------------------------------
        */

        function loadGawatDaruratForm($section) {

            if (!$section || !$section.length) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Jangan load ulang jika sudah pernah berhasil dimuat
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
                    'URL atau container form Gawat Darurat tidak ditemukan.'
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
                | Pastikan section masih terbuka
                |--------------------------------------------------------------------------
                |
                | Kalau user berpindah ke form lain sebelum response selesai,
                | response tidak dimasukkan ke DOM.
                |
                |--------------------------------------------------------------------------
                */

                if (!$section.hasClass('show')) {

                    console.log(
                        '[GD] Response diabaikan karena section sudah ditutup:',
                        $section.attr('id')
                    );

                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | Hapus content section lainnya
                |--------------------------------------------------------------------------
                |
                | Ini penting agar form dokter dan perawat tidak
                | berada bersamaan di DOM.
                |
                |--------------------------------------------------------------------------
                */

                $('#gdAccordion')
                    .find('#gd_dokter, #gd_perawat')
                    .not($section)
                    .find('.form-content')
                    .empty();

                $('#gdAccordion')
                    .find('#gd_dokter, #gd_perawat')
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
                | Tandai berhasil dimuat
                |--------------------------------------------------------------------------
                */

                $section.data('loaded', true);

                console.log(
                    '[GD] Form berhasil dimuat:',
                    $section.data('form-key')
                );
            })
            .fail(function (xhr, status, error) {

                /*
                |--------------------------------------------------------------------------
                | Kalau AJAX dibatalkan
                |--------------------------------------------------------------------------
                */

                if (status === 'abort') {

                    console.log(
                        '[GD] AJAX di-abort:',
                        $section.data('form-key')
                    );

                    return;
                }

                console.error(
                    'Gagal memuat form Gawat Darurat:',
                    {
                        status: status,
                        error: error,
                        response: xhr.responseText
                    }
                );

                /*
                |--------------------------------------------------------------------------
                | Hanya tampilkan error jika section masih terbuka
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
            | Tidak ada form yang di-load saat halaman pertama dibuka.
            |
            | Kedua tombol aktif.
            |
            |--------------------------------------------------------------------------
            */

            updateGawatDaruratButton();

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
            |
            |--------------------------------------------------------------------------
            */

            $('#gdAccordion')
                .off(
                    'shown.bs.collapse.gawatDarurat',
                    '#gd_dokter, #gd_perawat'
                )
                .on(
                    'shown.bs.collapse.gawatDarurat',
                    '#gd_dokter, #gd_perawat',
                    function () {

                        const $section = $(this);

                        console.log(
                            '[GD] Accordion dibuka:',
                            $section.attr('id')
                        );

                        /*
                        |--------------------------------------------------------------------------
                        | Update tombol
                        |--------------------------------------------------------------------------
                        */

                        updateGawatDaruratButton();

                        /*
                        |--------------------------------------------------------------------------
                        | Load form
                        |--------------------------------------------------------------------------
                        */

                        loadGawatDaruratForm($section);

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

            $('#gdAccordion')
                .off(
                    'hidden.bs.collapse.gawatDarurat',
                    '#gd_dokter, #gd_perawat'
                )
                .on(
                    'hidden.bs.collapse.gawatDarurat',
                    '#gd_dokter, #gd_perawat',
                    function () {

                        const $section = $(this);

                        updateGawatDaruratButton();

                        $section
                            .find('.form-content')
                            .scrollTop(0);
                    }
                );

            /*
            |--------------------------------------------------------------------------
            | SINGLE CHECKBOX
            |--------------------------------------------------------------------------
            |
            | Checkbox dengan class single-checkbox
            | hanya boleh memilih satu value dalam form yang sama.
            |
            |--------------------------------------------------------------------------
            */

            $(document)
                .off(
                    'change.gdSingleCheckbox',
                    '#gdAccordion .form-content .single-checkbox'
                )
                .on(
                    'change.gdSingleCheckbox',
                    '#gdAccordion .form-content .single-checkbox',
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
            |
            | Checkbox tidak boleh sampai semuanya unchecked.
            |
            |--------------------------------------------------------------------------
            */

            $(document)
                .off(
                    'change.gdSingleCheckboxBos',
                    '#gdAccordion .form-content .single-checkbox-bos'
                )
                .on(
                    'change.gdSingleCheckboxBos',
                    '#gdAccordion .form-content .single-checkbox-bos',
                    function () {

                        /*
                        |--------------------------------------------------------------------------
                        | Tidak boleh uncheck semuanya
                        |--------------------------------------------------------------------------
                        */

                        if (!this.checked) {

                            this.checked = true;

                            return;
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Uncheck pilihan lain
                        |--------------------------------------------------------------------------
                        */

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

            updateGawatDaruratButton();

        });

    })(jQuery);
</script>
