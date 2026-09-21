<div class="row align-items-center" id="form_hubungan_status_fungsional">

    <div class="col-md-12">
        <div class="card card-body border border-dashed border-primary">

            <label class="form-label fw-bold">
                Status Fungsional
            </label>

            {{-- ==========================================================
                ALAT BANTU MOBILITAS
            =========================================================== --}}
            <div class="mb-3">

                <label class="form-label fw-semibold">
                    Alat Bantu Mobilitas
                </label>

                <div class="row">

                    <div class="col-md-3">
                        <div class="form-check mb-2">
                            <input
                                class="form-check-input check-primary"
                                type="radio"
                                name="alat_bantu_fungsional"
                                id="{{ $instance }}_tanpa_alat_bantu"
                                value="tanpa"
                            >
                            <label
                                class="form-check-label"
                                for="{{ $instance }}_tanpa_alat_bantu"
                            >
                                Tanpa Alat Bantu
                            </label>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-check mb-2">
                            <input
                                class="form-check-input check-primary"
                                type="radio"
                                name="alat_bantu_fungsional"
                                id="{{ $instance }}_tongkat"
                                value="tongkat"
                            >
                            <label
                                class="form-check-label"
                                for="{{ $instance }}_tongkat"
                            >
                                Tongkat
                            </label>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-check mb-2">
                            <input
                                class="form-check-input check-primary"
                                type="radio"
                                name="alat_bantu_fungsional"
                                id="{{ $instance }}_kursi_roda"
                                value="kursi_roda"
                            >
                            <label
                                class="form-check-label"
                                for="{{ $instance }}_kursi_roda"
                            >
                                Kursi Roda
                            </label>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-check mb-2">
                            <input
                                class="form-check-input check-primary"
                                type="radio"
                                name="alat_bantu_fungsional"
                                id="{{ $instance }}_brankard"
                                value="brankard"
                            >
                            <label
                                class="form-check-label"
                                for="{{ $instance }}_brankard"
                            >
                                Brankard
                            </label>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-check mb-2">
                            <input
                                class="form-check-input check-primary"
                                type="radio"
                                name="alat_bantu_fungsional"
                                id="{{ $instance }}_walker"
                                value="walker"
                            >
                            <label
                                class="form-check-label"
                                for="{{ $instance }}_walker"
                            >
                                Walker
                            </label>
                        </div>
                    </div>

                    <div class="col-md-9">
                        <input
                            type="text"
                            class="form-control"
                            name="alat_bantu"
                            id="{{ $instance }}_alat_bantu"
                            placeholder="Alat bantu lainnya..."
                        >
                    </div>

                </div>
            </div>

            <hr>

            {{-- ==========================================================
                CACAT TUBUH
            =========================================================== --}}
            <div>

                <label class="form-label fw-semibold">
                    Cacat Tubuh
                </label>

                <div class="row mb-2">

                    <div class="col-md-3">
                        <div class="form-check">
                            <input
                                class="form-check-input check-primary"
                                type="radio"
                                name="cacat_tubuh"
                                id="{{ $instance }}_cacat_tubuh_tidak"
                                value="0"
                            >
                            <label
                                class="form-check-label"
                                for="{{ $instance }}_cacat_tubuh_tidak"
                            >
                                Tidak
                            </label>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-check">
                            <input
                                class="form-check-input check-primary"
                                type="radio"
                                name="cacat_tubuh"
                                id="{{ $instance }}_cacat_tubuh_ya"
                                value="1"
                            >
                            <label
                                class="form-check-label"
                                for="{{ $instance }}_cacat_tubuh_ya"
                            >
                                Ya
                            </label>
                        </div>
                    </div>

                </div>

                <textarea
                    class="form-control"
                    name="ket_cacat_tubuh"
                    id="{{ $instance }}_ket_cacat_tubuh"
                    rows="2"
                    placeholder="Keterangan cacat tubuh..."
                ></textarea>

            </div>

        </div>
    </div>

</div>


<script>
(function () {
    'use strict';

    const $section = $(@json($section));
    const $form = $section.find('#form_hubungan_status_fungsional');

    let isStatusFungsionalLoading = false;
    let isStatusFungsionalSaving = false;


    // ==========================================================
    // GET DATA
    // ==========================================================
    function getStatusFungsional() {

        if (!$form.length) {
            console.warn('Form Status Fungsional tidak ditemukan.');
            return;
        }

        isStatusFungsionalLoading = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/hubunganstatusfungsional/${kunjungan}`,
            type: 'GET',
            dataType: 'json',

            success: function (res) {

                const data = res.data;

                if (!data) {
                    return;
                }

                // ==================================================
                // ALAT BANTU
                // ==================================================

                let alatBantuFungsional = null;

                if (parseInt(data.TANPA_ALAT_BANTU) === 1) {
                    alatBantuFungsional = 'tanpa';
                }
                else if (parseInt(data.TONGKAT) === 1) {
                    alatBantuFungsional = 'tongkat';
                }
                else if (parseInt(data.KURSI_RODA) === 1) {
                    alatBantuFungsional = 'kursi_roda';
                }
                else if (parseInt(data.BRANKARD) === 1) {
                    alatBantuFungsional = 'brankard';
                }
                else if (parseInt(data.WALKER) === 1) {
                    alatBantuFungsional = 'walker';
                }

                if (alatBantuFungsional !== null) {

                    $form
                        .find(
                            `input[name="alat_bantu_fungsional"][value="${alatBantuFungsional}"]`
                        )
                        .prop('checked', true);
                }

                // Alat bantu lainnya
                if (FormHelper.hasValue(data.ALAT_BANTU)) {

                    FormHelper.setValue(
                        $section,
                        'alat_bantu',
                        data.ALAT_BANTU
                    );
                }


                // ==================================================
                // CACAT TUBUH
                // ==================================================

                let cacatTubuh = null;

                if (parseInt(data.CACAT_TUBUH_TIDAK) === 1) {
                    cacatTubuh = '0';
                }
                else if (parseInt(data.CACAT_TUBUH_YA) === 1) {
                    cacatTubuh = '1';
                }

                if (cacatTubuh !== null) {

                    $form
                        .find(
                            `input[name="cacat_tubuh"][value="${cacatTubuh}"]`
                        )
                        .prop('checked', true);
                }


                // Keterangan cacat tubuh
                if (FormHelper.hasValue(data.KET_CACAT_TUBUH)) {

                    FormHelper.setValue(
                        $section,
                        'ket_cacat_tubuh',
                        data.KET_CACAT_TUBUH
                    );
                }
            },

            error: function (xhr, status, error) {

                console.error(
                    'Error Status Fungsional:',
                    xhr.responseText || error
                );

                let message =
                    'Gagal mengambil data Status Fungsional.';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                console.warn(message);
            },

            complete: function () {
                isStatusFungsionalLoading = false;
            }
        });
    }


    // ==========================================================
    // SIMPAN DATA
    // ==========================================================
    function simpanStatusFungsional() {

        if (
            !$form.length ||
            isStatusFungsionalLoading ||
            isStatusFungsionalSaving
        ) {
            return;
        }

        const data = getFormDataByName($form, {
            NOKUNJ: kunjungan
        });

        isStatusFungsionalSaving = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/hubunganstatusfungsional/${kunjungan}/simpan`,
            type: 'POST',
            data: data,

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            success: function (res) {

            },

            error: function (xhr) {

                let message =
                    'Data Status Fungsional gagal disimpan.';

                if (
                    xhr.status === 422 &&
                    xhr.responseJSON?.errors
                ) {
                    message = Object
                        .values(xhr.responseJSON.errors)
                        .flat()
                        .join('<br>');
                }
                else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                iziToast.error({
                    title: 'Validasi Gagal!',
                    message: message,
                    position: 'topRight'
                });
            },

            complete: function () {
                isStatusFungsionalSaving = false;
            }
        });
    }


    // ==========================================================
    // AUTO SAVE
    // Tanpa timer / debounce
    // ==========================================================
    $(function () {

        if (!$form.length) {
            return;
        }

        // GET pertama kali
        getStatusFungsional();


        // Radio
        $form.on(
            'change',
            'input[type="radio"]',
            function () {

                if (isStatusFungsionalLoading) {
                    return;
                }

                simpanStatusFungsional();
            }
        );


        // Input / textarea
        $form.on(
            'change',
            'input[type="text"], textarea',
            function () {

                if (isStatusFungsionalLoading) {
                    return;
                }

                simpanStatusFungsional();
            }
        );

    });

})();
</script>
