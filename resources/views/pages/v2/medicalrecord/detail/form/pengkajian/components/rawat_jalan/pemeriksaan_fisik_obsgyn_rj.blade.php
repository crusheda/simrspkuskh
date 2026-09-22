<div class="row align-items-start" id="form_pemfis_obg">
    <div class="col-md-12 mb-3">
        <label class="form-label fw-bold">Pemeriksaan Fisik</label>
    </div>
    <div class="col-md-6 mb-3" >
        <div class="row">
            <div class="col-md-4 mb-2">
                <label class="form-label">Palpasi Leopold</label>
            </div>
            <div class="col-md-8 mb-2">
                <input type="text" class="form-control form-control-sm" name="palpasi_leopold" id="palpasi_leopold" hidden>
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">Leopold I</label>
            </div>
            <div class="col-md-8 mb-2">
                <input type="text" class="form-control form-control-sm" name="leopold1" id="leopold1">
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">Leopold II</label>
            </div>
            <div class="col-md-8 mb-2">
                <input type="text" class="form-control form-control-sm" name="leopold2" id="leopold2">
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">Leopold III</label>
            </div>
            <div class="col-md-8 mb-2">
                <input type="text" class="form-control form-control-sm" name="leopold3" id="leopold3">
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">Leopold IV</label>
            </div>
            <div class="col-md-8 mb-2">
                <input type="text" class="form-control form-control-sm" name="leopold4" id="leopold4">
            </div>
            <div class="col-12 mb-2">
                <label class="form-label fw-bold">Auskultasi</label>
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">DJJ</label>
            </div>
            <div class="col-md-8 mb-2">
                <div class="input-group input-group-sm">
                    <input type="number" class="form-control form-control-sm" name="aus_nadi" id="aus_nadi">
                    <span class="input-group-text">
                        X/menit
                    </span>
                </div>
            </div>
            <div class="col-md-4 mb-2">
            </div>
            <div class="col-md-8 mb-2">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="form-check m-0 flex-shrink-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="aus_nadi_cb" value="1" checked>
                        <label class="form-check-label">
                            Reguler
                        </label>
                    </div>

                    {{-- IREGULER --}}
                    <div class="form-check m-0 flex-shrink-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="aus_nadi_cb" value="2" >
                        <label class="form-check-label">
                            Ireguler
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="row">
            <div class="col-md-4 mb-2">
                <label class="form-label">Pemeriksaan Lain</label>
            </div>
            <div class="col-md-8 mb-2">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="form-check m-0 flex-shrink-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="pem_lain" value="1" checked>
                        <label class="form-check-label">
                            Panggul
                        </label>
                    </div>

                    {{-- IREGULER --}}
                    <div class="form-check m-0 flex-shrink-0">
                        <input class="form-check-input single-checkbox" type="checkbox" name="pem_lain" value="2" >
                        <label class="form-check-label">
                            Osborn
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">Extremitas</label>
            </div>
            <div class="col-md-8 mb-2">
                <input type="text" class="form-control form-control-sm" name="extremitas" id="extremitas">
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">Reflek Patela</label>
            </div>
            <div class="col-md-8 mb-2">
                <div class="input-group input-group-sm">
                    <input type="number" class="form-control form-control-sm" name="patela1" id="patela1">
                    <span class="input-group-text">
                        /
                    </span>
                    <input type="number" class="form-control form-control-sm" name="patela2" id="patela2">
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">Oedema</label>
            </div>
            <div class="col-md-8 mb-2">
                <div class="input-group input-group-sm">
                    <input type="number" class="form-control form-control-sm" name="uodema1" id="uodema1">
                    <span class="input-group-text">
                        /
                    </span>
                    <input type="number" class="form-control form-control-sm" name="uodema2" id="uodema2">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    const $section = $(@json($section));
    const $form = $section.find('#form_pemfis_obg');

    let isAskepLoading = false;
    let isAskepSaving = false;

    // ==========================================================
    // GET DATA
    // ==========================================================
    function getAskep() {

        if (!$form.length) {
            console.warn('Form Rencana Asuhan Keperawatan tidak ditemukan.');
            return;
        }

        isAskepLoading = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/rj/pemfis_obg/${kunjungan}`,
            type: 'GET',
            dataType: 'json',

            success: function (res) {

                const tlt = res.data;

                if (!tlt) {
                    return;
                }

                if (FormHelper.hasValue(tlt.RJ_PALPASI)) {
                    FormHelper.setValue(
                        $section,
                        'palpasi_leopold',
                        tlt.RJ_PALPASI
                    );
                }

                if (FormHelper.hasValue(tlt.RJ_LEOPOLD_1)) {
                    FormHelper.setValue(
                        $section,
                        'leopold1',
                        tlt.RJ_LEOPOLD_1
                    );
                }

                if (FormHelper.hasValue(tlt.RJ_LEOPOLD_2)) {
                    FormHelper.setValue(
                        $section,
                        'leopold2',
                        tlt.RJ_LEOPOLD_2
                    );
                }

                if (FormHelper.hasValue(tlt.RJ_LEOPOLD_3)) {
                    FormHelper.setValue(
                        $section,
                        'leopold3',
                        tlt.RJ_LEOPOLD_3
                    );
                }

                if (FormHelper.hasValue(tlt.RJ_LEOPOLD_4)) {
                    FormHelper.setValue(
                        $section,
                        'leopold4',
                        tlt.RJ_LEOPOLD_4
                    );
                }

                if (FormHelper.hasValue(tlt.RJ_DJJ)) {
                    FormHelper.setValue(
                        $section,
                        'aus_nadi',
                        tlt.RJ_DJJ
                    );
                }

                if (FormHelper.hasValue(tlt.RJ_AUSKULTASI)) {
                    FormHelper.setValue(
                        $section,
                        'aus_nadi_cb',
                        tlt.RJ_AUSKULTASI
                    );
                }

                if (FormHelper.hasValue(tlt.RJ_PEMERIKSAAN_LAIN)) {
                    FormHelper.setValue(
                        $section,
                        'pem_lain',
                        tlt.RJ_PEMERIKSAAN_LAIN
                    );
                }

                if (FormHelper.hasValue(tlt.RJ_EXTREMITAS)) {
                    FormHelper.setValue(
                        $section,
                        'extremitas',
                        tlt.RJ_EXTREMITAS
                    );
                }

                if (FormHelper.hasValue(tlt.RJ_PATELA_1)) {
                    FormHelper.setValue(
                        $section,
                        'patela1',
                        tlt.RJ_PATELA_1
                    );
                }

                if (FormHelper.hasValue(tlt.RJ_PATELA_2)) {
                    FormHelper.setValue(
                        $section,
                        'patela2',
                        tlt.RJ_PATELA_2
                    );
                }

                if (FormHelper.hasValue(tlt.RJ_UODEMA_1)) {
                    FormHelper.setValue(
                        $section,
                        'uodema1',
                        tlt.RJ_UODEMA_1
                    );
                }

                if (FormHelper.hasValue(tlt.RJ_UODEMA_2)) {
                    FormHelper.setValue(
                        $section,
                        'uodema2',
                        tlt.RJ_UODEMA_2
                    );
                }
            },

            error: function (xhr, status, error) {

                console.error(
                    'Error Rencana Asuhan Keperawatan:',
                    xhr.responseText || error
                );

                let message =
                    'Gagal mengambil data Rencana Asuhan Keperawatan.';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                console.warn(message);
            },

            complete: function () {
                isAskepLoading = false;
            }
        });
    }

    // ==========================================================
    // SIMPAN DATA
    // ==========================================================
    function simpanAskep() {

        if (
            !$form.length ||
            isAskepLoading ||
            isAskepSaving
        ) {
            return;
        }

        const data = getFormDataByName($form, {
            NOKUNJ: kunjungan
        });

        isAskepSaving = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/rj/pemfis_obg/${kunjungan}/simpan`,
            type: 'POST',
            data: data,

            headers: {
                'X-CSRF-TOKEN': $(
                    'meta[name="csrf-token"]'
                ).attr('content')
            },

            success: function (res) {

            },

            error: function (xhr) {

                let message =
                    'Data Rencana Asuhan Keperawatan gagal disimpan.';

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
                isAskepSaving = false;
            }
        });
    }

    // ==========================================================
    // AUTO SAVE
    // ==========================================================
    $(function () {

        if (!$form.length) {
            return;
        }

        getAskep();

        $form.on(
            'blur',
            'textarea,input',
            function () {

                if (isAskepLoading) {
                    return;
                }

                simpanAskep();
            }
        );

        $form.on(
            'change',
            'select,input[type="checkbox"],input[type="radio"]',
            function () {

                if (isAskepLoading) {
                    return;
                }

                simpanAskep();
            }
        );
    });

})();
</script>