<div class="row align-items-center" id="form_kriteria_pulang">

    {{-- ==========================================================
        TITLE
    =========================================================== --}}
    <div class="col-md-12 mb-2">
        <div class="form-group">
            <h4 class="mb-0 text-danger">
                Kriteria Pulang
            </h4>
        </div>
    </div>


    {{-- ==========================================================
        PERKIRAAN LAMA RAWAT
    =========================================================== --}}
    <div class="col-md-3 fw-bold">
        Perkiraan Lama Rawat :
    </div>

    <div class="col-md-9">

        {{-- ======================================================
            SUDAH BISA DITETAPKAN
        ======================================================= --}}
        <div class="d-flex align-items-center flex-wrap mb-2">

            <div class="form-check me-2">
                <input
                    class="form-check-input check-primary single-checkbox"
                    type="checkbox"
                    name="kp_plr"
                    value="1"
                >

                <label
                    class="form-check-label"
                >
                    Sudah bisa ditetapkan :
                </label>
            </div>

            <input
                type="number"
                class="form-control form-control-sm me-2"
                name="kp_plr_hari"
                min="1"
                style="width: 120px;"
                disabled
            >

            <span>Hari</span>

        </div>


        {{-- ======================================================
            BELUM BISA DITETAPKAN
        ======================================================= --}}
        <div class="d-flex align-items-center">

            <div class="form-check">

                <input
                    class="form-check-input check-danger single-checkbox"
                    type="checkbox"
                    name="kp_plr"
                    value="0"
                >

                <label
                    class="form-check-label"
                >
                    Belum bisa ditetapkan, karena :
                </label>

            </div>

            <input
                type="text"
                class="form-control form-control-sm ms-2"
                name="kp_plr_karena"
                style="max-width: 400px;"
                disabled
            >

        </div>

    </div>
</div>


<script>
(function () {
    'use strict';

    const $section = $(@json($section));
    const $form = $section.find('#form_kriteria_pulang');

    let isKriteriaPulangLoading = false;
    let isKriteriaPulangSaving = false;


    // ==============================================================
    // UPDATE STATE INPUT
    // ==============================================================
    function updateKriteriaPulangState() {

        const $selected = $form.find(
            'input[name="kp_plr"]:checked'
        );

        const value = $selected.length
            ? String($selected.val())
            : null;


        const $hari = $form.find(
            'input[name="kp_plr_hari"]'
        );

        const $karena = $form.find(
            'input[name="kp_plr_karena"]'
        );


        // ==========================================================
        // VALUE = 1
        // Sudah bisa ditetapkan
        // ==========================================================
        if (value === '1') {

            $hari
                .prop('disabled', false);

            $karena
                .val('')
                .prop('disabled', true);

            return;
        }


        // ==========================================================
        // VALUE = 0
        // Belum bisa ditetapkan
        // ==========================================================
        if (value === '0') {

            $hari
                .val('')
                .prop('disabled', true);

            $karena
                .prop('disabled', false);

            return;
        }


        // ==========================================================
        // BELUM ADA PILIHAN
        // ==========================================================
        $hari
            .val('')
            .prop('disabled', true);

        $karena
            .val('')
            .prop('disabled', true);
    }


    // ==============================================================
    // GET DATA
    // ==============================================================
    function getKriteriaPulang() {

        if (!$form.length) {
            console.warn(
                'Form Kriteria Pulang tidak ditemukan.'
            );

            return;
        }

        isKriteriaPulangLoading = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/kriteriapulang/${kunjungan}`,
            type: 'GET',
            dataType: 'json',

            success: function (res) {

                const kpl = res.data;

                if (!kpl) {
                    updateKriteriaPulangState();
                    return;
                }


                // ==================================================
                // KRITERIA PULANG
                // ==================================================
                FormHelper.setSingleCheckbox(
                    $section,
                    'kp_plr',
                    kpl.KRITERIA_PULANG
                );


                // ==================================================
                // HARI
                // ==================================================
                if (FormHelper.hasValue(kpl.HARI)) {

                    FormHelper.setValue(
                        $section,
                        'kp_plr_hari',
                        kpl.HARI
                    );
                }


                // ==================================================
                // KARENA
                // ==================================================
                if (FormHelper.hasValue(kpl.KARENA)) {

                    FormHelper.setValue(
                        $section,
                        'kp_plr_karena',
                        kpl.KARENA
                    );
                }


                // ==================================================
                // UPDATE ENABLE / DISABLE INPUT
                // ==================================================
                updateKriteriaPulangState();
            },

            error: function (xhr, status, error) {

                console.error(
                    'Error Kriteria Pulang:',
                    xhr.responseText || error
                );

                let message =
                    'Gagal mengambil data Kriteria Pulang.';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                console.warn(message);
            },

            complete: function () {

                isKriteriaPulangLoading = false;

                // Pastikan state terakhir tetap benar
                updateKriteriaPulangState();
            }
        });
    }


    // ==============================================================
    // SIMPAN DATA
    // ==============================================================
    function simpanKriteriaPulang() {

        if (
            !$form.length ||
            isKriteriaPulangLoading ||
            isKriteriaPulangSaving
        ) {
            return;
        }


        // Pastikan status input sesuai pilihan terakhir
        updateKriteriaPulangState();


        const data = getFormDataByName($form, {
            NOKUNJ: kunjungan
        });


        isKriteriaPulangSaving = true;


        $.ajax({
            url: `/api/v2/emr/pengkajian/kriteriapulang/${kunjungan}/simpan`,
            type: 'POST',
            data: data,

            headers: {
                'X-CSRF-TOKEN': $(
                    'meta[name="csrf-token"]'
                ).attr('content')
            },

            success: function (res) {

                console.log(
                    'Kriteria Pulang berhasil disimpan.'
                );
            },

            error: function (xhr) {

                let message =
                    'Data Kriteria Pulang gagal disimpan.';


                // ==================================================
                // VALIDATION ERROR
                // ==================================================
                if (
                    xhr.status === 422 &&
                    xhr.responseJSON?.errors
                ) {

                    message = Object
                        .values(xhr.responseJSON.errors)
                        .flat()
                        .join('<br>');

                }

                // ==================================================
                // GENERAL ERROR
                // ==================================================
                else if (xhr.responseJSON?.message) {

                    message =
                        xhr.responseJSON.message;
                }


                iziToast.error({
                    title: 'Validasi Gagal!',
                    message: message,
                    position: 'topRight'
                });
            },

            complete: function () {

                isKriteriaPulangSaving = false;
            }
        });
    }


    // ==============================================================
    // DOCUMENT READY
    // ==============================================================
    $(function () {

        // ==========================================================
        // GET DATA TERLEBIH DAHULU
        // ==========================================================
        getKriteriaPulang();


        // ==========================================================
        // CHECKBOX
        //
        // Ketika kp_plr berubah:
        // 1. Update disabled state
        // 2. Simpan otomatis
        // ==========================================================
        $section
            .off(
                'change.kpl',
                '#form_kriteria_pulang input[name="kp_plr"]'
            )
            .on(
                'change.kpl',
                '#form_kriteria_pulang input[name="kp_plr"]',
                function () {

                    if (isKriteriaPulangLoading) {
                        return;
                    }


                    updateKriteriaPulangState();

                    simpanKriteriaPulang();
                }
            );


        // ==========================================================
        // INPUT HARI
        //
        // Simpan ketika user selesai mengisi / keluar dari input
        // ==========================================================
        $section
            .off(
                'blur.kpl',
                '#form_kriteria_pulang input[name="kp_plr_hari"]'
            )
            .on(
                'blur.kpl',
                '#form_kriteria_pulang input[name="kp_plr_hari"]',
                function () {

                    if (isKriteriaPulangLoading) {
                        return;
                    }


                    // Hanya boleh diproses jika value = 1
                    const selectedValue =
                        $form.find(
                            'input[name="kp_plr"]:checked'
                        ).val();


                    if (String(selectedValue) !== '1') {
                        return;
                    }


                    simpanKriteriaPulang();
                }
            );


        // ==========================================================
        // INPUT KARENA
        //
        // Simpan ketika user selesai mengisi / keluar dari input
        // ==========================================================
        $section
            .off(
                'blur.kpl',
                '#form_kriteria_pulang input[name="kp_plr_karena"]'
            )
            .on(
                'blur.kpl',
                '#form_kriteria_pulang input[name="kp_plr_karena"]',
                function () {

                    if (isKriteriaPulangLoading) {
                        return;
                    }


                    // Hanya boleh diproses jika value = 0
                    const selectedValue =
                        $form.find(
                            'input[name="kp_plr"]:checked'
                        ).val();


                    if (String(selectedValue) !== '0') {
                        return;
                    }


                    simpanKriteriaPulang();
                }
            );

    });

})();
</script>
