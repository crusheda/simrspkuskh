<div class="d-flex justify-content-between">
    <button
        type="button"
        class="btn btn-danger waves-effect waves-light"
        id="btnDokter"
        data-bs-toggle="collapse"
        data-bs-target="#riA_dokter"
        aria-expanded="true"
        aria-controls="riA_dokter"
    >
        <i class="ri-stethoscope-line me-1"></i>
        Pengkajian Dokter
    </button>

    <button
        type="button"
        class="btn btn-success waves-effect waves-light collapsed"
        id="btnPerawat"
        data-bs-toggle="collapse"
        data-bs-target="#riA_perawat"
        aria-expanded="false"
        aria-controls="riA_perawat"
    >
        <i class="ri-stethoscope-line me-1"></i>
        Pengkajian Keperawatan
    </button>
</div>

<div class="accordion mt-3" id="riaAccordion">
    <div
        class="multi-collapse collapse show"
        data-bs-parent="#riaAccordion"
        id="riA_dokter"
        data-kunjungan="{{ $list['kunjungan'] }}"
        data-form-key="riA_dokter"
        data-url="{{ route('v2.emr.form.sub.rawat-inap.load', ['kunjungan' => $list['kunjungan'], 'formKey' => 'ria_dokter']) }}"
    >
        <div class="form-content"></div>
    </div>

    <div
        class="multi-collapse collapse"
        data-bs-parent="#riaAccordion"
        id="riA_perawat"
        data-kunjungan="{{ $list['kunjungan'] }}"
        data-form-key="riA_perawat"
        data-url="{{ route('v2.emr.form.sub.rawat-inap.load', ['kunjungan' => $list['kunjungan'], 'formKey' => 'ria_perawat']) }}"
    >
        <div class="form-content"></div>
    </div>
</div>

<script>
    (function ($) {
        'use strict';

        function updateRanapAnakButton() {
            $('#btnDokter').prop(
                'disabled',
                $('#riA_dokter').hasClass('show')
            );

            $('#btnPerawat').prop(
                'disabled',
                $('#riA_perawat').hasClass('show')
            );
        }

        function loadRanapAnakForm($section) {
            if (
                $section.data('loaded') ||
                $section.data('loading')
            ) {
                return;
            }

            const url = $section.data('url');
            const $content = $section.find('.form-content');

            if (!url || !$content.length) {
                console.error(
                    'URL atau container form Rawat Inap Anak tidak ditemukan.'
                );
                return;
            }

            $section.data('loading', true);

            $content.html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Memuat...</span>
                    </div>
                    <div class="mt-2">Memuat sub formulir...</div>
                </div>
            `);

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'html',
            })
                .done(function (html) {
                    /*
                     * Hapus form panel lain supaya elemen dengan ID/name
                     * yang sama tidak tampil bersama dalam DOM.
                     */
                    $('#riaAccordion')
                        .find('.multi-collapse')
                        .not($section)
                        .find('.form-content')
                        .empty();

                    $('#riaAccordion')
                        .find('.multi-collapse')
                        .not($section)
                        .removeData('loaded');

                    /*
                     * Script inline pada form dokter/perawat serta modul
                     * di dalamnya akan dijalankan setelah partial dipasang.
                     */
                    $content.empty().append(html);

                    $section.data('loaded', true);
                })
                .fail(function (xhr) {
                    console.error(
                        'Gagal memuat form Rawat Inap Anak:',
                        xhr.responseText
                    );

                    $content.html(`
                        <div class="alert alert-danger mb-0">
                            Form gagal dimuat. Silakan coba kembali.
                        </div>
                    `);
                })
                .always(function () {
                    $section.removeData('loading');
                });
        }

        $(function () {
            // Dokter adalah panel yang terbuka saat halaman ini pertama tampil.
            loadRanapAnakForm($('#riA_dokter'));

            $('#riaAccordion').on(
                'shown.bs.collapse',
                '.multi-collapse',
                function () {
                    const $section = $(this);

                    updateRanapAnakButton();
                    loadRanapAnakForm($section);
                    $section.find('.form-content').scrollTop(0);
                }
            );

            $('#riaAccordion').on(
                'hidden.bs.collapse',
                '.multi-collapse',
                function () {
                    updateRanapAnakButton();
                    $(this).find('.form-content').scrollTop(0);
                }
            );

            /*
             * Scope checkbox ke .form-content milik panel yang aktif.
             * Dokter tidak dapat menghapus pilihan checkbox Perawat.
             */
            $(document)
                .off(
                    'change.riaSingleCheckbox',
                    '#riaAccordion .form-content .single-checkbox'
                )
                .on(
                    'change.riaSingleCheckbox',
                    '#riaAccordion .form-content .single-checkbox',
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
                            .prop('checked', false);
                    }
                );

            $(document)
                .off(
                    'change.riaSingleCheckboxBos',
                    '#riaAccordion .form-content .single-checkbox-bos'
                )
                .on(
                    'change.riaSingleCheckboxBos',
                    '#riaAccordion .form-content .single-checkbox-bos',
                    function () {
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
                            .prop('checked', false);
                    }
                );

            updateRanapAnakButton();
        });
    })(jQuery);
</script>
