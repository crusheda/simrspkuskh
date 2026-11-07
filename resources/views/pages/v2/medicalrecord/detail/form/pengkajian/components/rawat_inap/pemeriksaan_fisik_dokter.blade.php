<div class="row align-items-center" id="form_pemeriksaan_fisik_dokter">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <h6 class="mb-2 text-primary">
                        <strong>Kepala / Leher</strong>
                    </h6>
                    <div class="d-flex align-items-center">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input single-checkbox" type="checkbox" name="pf_kepala" value="1" checked="">
                            <label class="form-check-label">
                                Normal
                            </label>
                        </div>
                        <div class="d-flex align-items-center w-100 gap-2 mb-0">
                            <div class="form-check mb-0 flex-shrink-0">
                                <input class="form-check-input single-checkbox" type="checkbox" name="pf_kepala" value="2">
                                <label class="form-check-label">Terdapat kelainan berupa, </label>
                            </div>
                            <input type="text" class="form-control form-control-sm flex-grow-1" name="pf_kepala_detail" placeholder="Sebutkan......" disabled>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <h6 class="mb-2 text-primary">
                        <strong>Thoraks</strong>
                    </h6>
                    <h6>Cor</h6>
                    <div class="d-flex align-items-center">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input single-checkbox" type="checkbox" name="pf_cor" value="1" checked="">
                            <label class="form-check-label">
                                Normal
                            </label>
                        </div>
                        <div class="d-flex align-items-center w-100 gap-2 mb-0">
                            <div class="form-check mb-0 flex-shrink-0">
                                <input class="form-check-input single-checkbox" type="checkbox" name="pf_cor" value="2">
                                <label class="form-check-label">Terdapat kelainan berupa, </label>
                            </div>
                            <input type="text" class="form-control form-control-sm flex-grow-1" name="pf_cor_detail" placeholder="Sebutkan......" disabled>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <h6>Pulmo</h6>
                    <div class="d-flex align-items-center">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input single-checkbox" type="checkbox" name="pf_pulmo" value="1" checked="">
                            <label class="form-check-label">
                                Normal
                            </label>
                        </div>
                        <div class="d-flex align-items-center w-100 gap-2 mb-0">
                            <div class="form-check mb-0 flex-shrink-0">
                                <input class="form-check-input single-checkbox" type="checkbox" name="pf_pulmo" value="2">
                                <label class="form-check-label">Terdapat kelainan berupa, </label>
                            </div>
                            <input type="text" class="form-control form-control-sm flex-grow-1" name="pf_pulmo_detail" placeholder="Sebutkan......" disabled>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <h6 class="mb-2 text-primary">
                        <strong>Abdomen</strong>
                    </h6>
                    <div class="d-flex align-items-center">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input single-checkbox" type="checkbox" name="pf_abdomen" value="1" checked="">
                            <label class="form-check-label">
                                Normal
                            </label>
                        </div>
                        <div class="d-flex align-items-center w-100 gap-2 mb-0">
                            <div class="form-check mb-0 flex-shrink-0">
                                <input class="form-check-input single-checkbox" type="checkbox" name="pf_abdomen" value="2">
                                <label class="form-check-label">Terdapat kelainan berupa, </label>
                            </div>
                            <input type="text" class="form-control form-control-sm flex-grow-1" name="pf_abdomen_detail" placeholder="Sebutkan......" disabled>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <h6 class="mb-2 text-primary">
                        <strong>Ekstremitas</strong>
                    </h6>
                    <div class="d-flex align-items-center">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input single-checkbox" type="checkbox" name="pf_eks" value="1" checked="">
                            <label class="form-check-label">
                                Normal
                            </label>
                        </div>
                        <div class="d-flex align-items-center w-100 gap-2 mb-0">
                            <div class="form-check mb-0 flex-shrink-0">
                                <input class="form-check-input single-checkbox" type="checkbox" name="pf_eks" value="2">
                                <label class="form-check-label">Terdapat kelainan berupa, </label>
                            </div>
                            <input type="text" class="form-control form-control-sm flex-grow-1" name="pf_eks_detail" placeholder="Sebutkan......" disabled>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <h6 class="mb-2 text-primary">
                        <strong>Status Lokalis</strong>
                    </h6>
                    <textarea class="form-control" name="status_lokalis" rows="3"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    // ==========================================================
    // SECTION & FORM
    // ==========================================================
    const $section = $(@json($section));
    const $form = $section.find('#form_pemeriksaan_fisik_dokter');

    let isPemeriksaanFisikLoading = false;
    let isPemeriksaanFisikSaving = false;
    let isPemeriksaanFisikSavePending = false;

    // ==========================================================
    // NORMALIZE OBJECT DATA
    // ==========================================================
    function normalizeData(data) {

        if (!data || typeof data !== 'object') {
            return {};
        }

        const normalized = {};

        Object.keys(data).forEach(function (key) {

            normalized[String(key).toLowerCase()] = data[key];

        });

        return normalized;
    }

    // ==========================================================
    // GET VALUE BERDASARKAN NAME
    // Case insensitive
    // ==========================================================
    function getDataValue(data, name) {

        if (!data || !name) {
            return undefined;
        }

        const normalizedName = String(name).toLowerCase();

        if (
            Object.prototype.hasOwnProperty.call(
                data,
                normalizedName
            )
        ) {
            return data[normalizedName];
        }

        return undefined;
    }

    // ==========================================================
    // INPUT TAMBAHAN BERDASARKAN CHECKBOX
    // Aktif jika checkbox pada blok yang sama tercentang.
    // Berlaku untuk centang manual maupun hasil GET AJAX.
    // ==========================================================
    // ==========================================================
    function updateConditionalInputs() {
        $form.find('input[name]').each(function () {
            const $input = $(this);

            if (
                $input.is(':checkbox') ||
                $input.is(':radio')
            ) {
                return;
            }

            // Jika menggunakan data-enable-when
            const enableWhen = $input.data('enable-when');

            if (enableWhen) {
                const [name, value] = String(enableWhen).split(':');

                const isChecked = $form
                    .find('input.single-checkbox[type="checkbox"]')
                    .filter(function () {
                        return this.name === name &&
                            this.value === value &&
                            this.checked;
                    }).length > 0;

                $input.prop('disabled', !isChecked);

                if (!isChecked) {
                    $input.val('');
                }

                return;
            }

            // Logika awal tetap dipertahankan
            const $parent = $input.parent();

            const $checkbox = $parent.find(
                'input.single-checkbox[type="checkbox"]'
            );

            if (!$checkbox.length) {
                return;
            }

            const isChecked = $checkbox.is(':checked');

            $input.prop('disabled', !isChecked);

            if (!isChecked) {
                $input.val('');
            }
        });
    }

    // ==========================================================
    // GET DATA
    // ==========================================================
    function getPemeriksaanFisik() {

        if (!$form.length) {

            // console.warn(
            //     'Form Pemeriksaan Fisik tidak ditemukan.'
            // );

            return;
        }

        // Jangan menjalankan GET jika sedang loading
        if (isPemeriksaanFisikLoading) {
            return;
        }

        isPemeriksaanFisikLoading = true;

        $.ajax({

            url: `/api/v2/emr/pengkajian/ri/pemfis/dokter/${kunjungan}`,

            type: 'GET',

            dataType: 'json',

            success: function (res) {

                // ==================================================
                // DATA API
                // ==================================================
                const data = normalizeData(res?.data);

                // console.log(
                //     '========== GET PEMERIKSAAN FISIK =========='
                // );

                // console.log(
                //     'Data Pemeriksaan Fisik:',
                //     res?.data
                // );

                // ==================================================
                // TIDAK ADA DATA
                // ==================================================
                if (
                    !res ||
                    !res.data ||
                    typeof res.data !== 'object'
                ) {

                    console.log(
                        'Belum ada data Pemeriksaan Fisik.'
                    );

                    return;
                }

                // ==================================================
                // LOAD SEMUA FIELD BERDASARKAN NAME
                // ==================================================
                const processedCheckboxGroups = new Set();

                $form
                    .find('input[name], textarea[name], select[name]')
                    .each(function () {

                        const $el = $(this);

                        const name = $el.attr('name');

                        if (!name) {
                            return;
                        }

                        // ==================================================
                        // CHECKBOX
                        // ==================================================
                        if (
                            $el.is(
                                'input[type="checkbox"]'
                            )
                        ) {

                            // ----------------------------------------------
                            // Jangan proses group checkbox berkali-kali
                            // ----------------------------------------------
                            if (
                                processedCheckboxGroups.has(name)
                            ) {
                                return;
                            }

                            processedCheckboxGroups.add(name);

                            // ----------------------------------------------
                            // Ambil value dari database
                            // ----------------------------------------------
                            const value = getDataValue(
                                data,
                                name
                            );

                            // ----------------------------------------------
                            // Jika field menggunakan single-checkbox
                            // ----------------------------------------------
                            const $group = $form.find(
                                `input[name="${name}"]`
                            );

                            if (
                                $group.hasClass(
                                    'single-checkbox'
                                )
                            ) {

                                FormHelper.setSingleCheckbox(
                                    $form,
                                    name,
                                    value
                                );

                            }

                            // ----------------------------------------------
                            // Checkbox biasa
                            // ----------------------------------------------
                            else {

                                FormHelper.setCheckbox(
                                    $form,
                                    name,
                                    value
                                );

                            }

                            return;
                        }

                        // ==================================================
                        // TEXT / NUMBER / TEXTAREA / SELECT
                        // ==================================================
                        const value = getDataValue(
                            data,
                            name
                        );

                        // --------------------------------------------------
                        // Jika key tidak ada di response,
                        // jangan mengubah nilai HTML
                        // --------------------------------------------------
                        if (value === undefined) {
                            return;
                        }

                        FormHelper.setValue(
                            $form,
                            name,
                            value
                        );

                    });

                updateConditionalInputs();

                // console.log(
                //     'Pemeriksaan Fisik berhasil dimuat.'
                // );

                // console.log(
                //     '============================================'
                // );
            },

            // ==========================================================
            // ERROR
            // ==========================================================
            error: function (xhr, status, error) {

                console.error(
                    'Error Pemeriksaan Fisik:',
                    xhr.responseText || error
                );

                let message =
                    'Gagal mengambil data Pemeriksaan Fisik.';

                if (
                    xhr.responseJSON?.message
                ) {

                    message =
                        xhr.responseJSON.message;

                }

                console.warn(message);
            },

            // ==========================================================
            // COMPLETE
            // ==========================================================
            complete: function () {

                isPemeriksaanFisikLoading = false;

            }

        });
    }

    // ==========================================================
    // SIMPAN DATA
    // ==========================================================
    function simpanPemeriksaanFisik() {

        if (!$form.length) {
            return;
        }

        // ======================================================
        // MASIH LOAD DATA
        // ======================================================
        if (isPemeriksaanFisikLoading) {
            return;
        }

        // ======================================================
        // MASIH SAVING
        //
        // Jangan buang event.
        // Tandai bahwa ada perubahan yang harus disimpan lagi.
        // ======================================================
        if (isPemeriksaanFisikSaving) {

            isPemeriksaanFisikSavePending = true;

            // console.log(
            //     'Save sedang berjalan -> tandai sebagai PENDING'
            // );

            return;
        }

        // ======================================================
        // AMBIL DATA FORM TERBARU
        // ======================================================
        const data = getFormDataByName(
            $form,
            {
                NOKUNJ: kunjungan
            }
        );

        // console.log(
        //     '========== SIMPAN PEMERIKSAAN FISIK =========='
        // );

        // console.log(
        //     'Data:',
        //     data
        // );

        // ======================================================
        // RESET PENDING
        // Karena data terbaru akan dikirim sekarang.
        // ======================================================
        isPemeriksaanFisikSavePending = false;

        // ======================================================
        // LOCK
        // ======================================================
        isPemeriksaanFisikSaving = true;

        $.ajax({

            url:
                `/api/v2/emr/pengkajian/ri/pemfis/dokter/${kunjungan}/simpan`,

            type: 'POST',

            data: data,

            headers: {

                'X-CSRF-TOKEN': $(
                    'meta[name="csrf-token"]'
                ).attr('content')

            },

            // ==================================================
            // SUCCESS
            // ==================================================
            success: function (res) {

                // console.log(
                //     'Pemeriksaan Fisik berhasil disimpan.',
                //     res
                // );

            },

            // ==================================================
            // ERROR
            // ==================================================
            error: function (xhr) {

                let message =
                    'Data Pemeriksaan Fisik gagal disimpan.';

                if (
                    xhr.status === 422 &&
                    xhr.responseJSON?.errors
                ) {

                    message = Object
                        .values(
                            xhr.responseJSON.errors
                        )
                        .flat()
                        .join('<br>');

                }
                else if (
                    xhr.responseJSON?.message
                ) {

                    message =
                        xhr.responseJSON.message;

                }

                iziToast.error({

                    title: 'Validasi Gagal!',

                    message: message,

                    position: 'topRight'

                });

            },

            // ==================================================
            // COMPLETE
            // ==================================================
            complete: function () {

                // Buka lock
                isPemeriksaanFisikSaving = false;

                // ==================================================
                // ADA PERUBAHAN SELAMA SAVE BERJALAN?
                // ==================================================
                if (isPemeriksaanFisikSavePending) {

                    // console.log(
                    //     'Ada SAVE PENDING -> simpan data terbaru.'
                    // );

                    // Reset sebelum menjalankan save berikutnya
                    isPemeriksaanFisikSavePending = false;

                    // Beri kesempatan browser menyelesaikan
                    // event/change/blur yang sedang berjalan.
                    setTimeout(function () {

                        simpanPemeriksaanFisik();

                    }, 0);

                }

            }

        });

    }

    // ==========================================================
    // AUTO SAVE
    // ==========================================================

    $(function () {

        if (!$form.length) {

            // console.warn(
            //     'Form Pemeriksaan Fisik tidak ditemukan.'
            // );

            return;
        }

        // ======================================================
        // LOAD DATA TERLEBIH DAHULU
        // ======================================================

        getPemeriksaanFisik();

        // ======================================================
        // TEXT / NUMBER / DATE / TIME / TEXTAREA
        //
        // SAVE SAAT BLUR
        // ======================================================

        $form.on(
            'blur',
            'textarea, input:not([type="checkbox"]):not([type="radio"])',
            function () {

                simpanPemeriksaanFisik();

            }
        );

        // ======================================================
        // CHECKBOX / RADIO / SELECT
        //
        // SAVE SAAT CHANGE
        // ======================================================

        $form.on(
            'change',
            'select, input[type="checkbox"], input[type="radio"]',
            function () {
                updateConditionalInputs();
                simpanPemeriksaanFisik();
            }
        );

    });

})();
</script>
