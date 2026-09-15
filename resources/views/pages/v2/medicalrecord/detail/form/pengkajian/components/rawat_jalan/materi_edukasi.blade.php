<div class="row" id="form_materi_edukasi">
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label fw-bold">Materi Edukasi</label>
            <div class="row">
                <div class="col">
                    <div class="form-check mb-2">
                        <input class="form-check-input check-primary" type="checkbox" name="me_1" id="me_1" checked>
                        <label class="form-check-label"> Tanda dan gejala suatu penyakit </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input check-primary" type="checkbox" name="me_2" id="me_2" checked>
                        <label class="form-check-label"> Hasil pemeriksaan </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input check-primary" type="checkbox" name="me_3" id="me_3" checked>
                        <label class="form-check-label"> Diagnosis </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input check-primary" type="checkbox" name="me_4" id="me_4" checked>
                        <label class="form-check-label"> Rencana penatalaksanaan penyakit </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input check-primary" type="checkbox" name="me_5" id="me_5" checked>
                        <label class="form-check-label"> Tindakan dan tujuan terapi </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label fw-bold">Sarana Informasi / Edukasi</label>
            <div class="row">
                <div class="col">
                    <div class="form-check mb-2">
                        <input class="form-check-input check-primary" type="checkbox" name="sie_1" id="sie_1">
                        <label class="form-check-label"> Leaflet </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input check-primary" type="checkbox" name="sie_2" id="sie_2" checked>
                        <label class="form-check-label"> Lisan </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label class="form-label fw-bold">Evaluasi</label>
            <div class="row">
                <div class="col">
                    <div class="form-check mb-2">
                        <input class="form-check-input check-primary" type="checkbox" name="eval_1" id="eval_1" checked>
                        <label class="form-check-label"> Sudah Mengerti </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input check-primary" type="checkbox" name="eval_2" id="eval_2">
                        <label class="form-check-label"> Re - Edukasi </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        'use strict';

        const $section = $(@json($section));
        const $form = $section.find('#form_materi_edukasi');

        let isMateriEdukasiLoading = false;
        let isMateriEdukasiSaving = false;

        // ==========================================================
        // GET DATA
        // ==========================================================
        function getMateriEdukasi() {

            if (!$form.length) {
                console.warn('Form Materi Edukasi tidak ditemukan.');
                return;
            }

            isMateriEdukasiLoading = true;

            $.ajax({
                url: `/api/v2/emr/pengkajian/rajal/materiedukasi/${kunjungan}`,
                type: 'GET',
                dataType: 'json',

                success: function (res) {

                    const tlt = res.data;

                    if (!tlt) {
                        return;
                    }

                    if (FormHelper.hasValue(tlt.ME_TANDA_GEJALA)) {
                        FormHelper.setValue(
                            $section,
                            'me_1',
                            tlt.ME_TANDA_GEJALA
                        );
                    }

                    if (FormHelper.hasValue(tlt.ME_HASIL_PEMERIKSAAN)) {
                        FormHelper.setValue(
                            $section,
                            'me_2',
                            tlt.ME_HASIL_PEMERIKSAAN
                        );
                    }

                    if (FormHelper.hasValue(tlt.ME_DIAGNOSIS)) {
                        FormHelper.setValue(
                            $section,
                            'me_3',
                            tlt.ME_DIAGNOSIS
                        );
                    }

                    if (FormHelper.hasValue(tlt.ME_RENCANA_PENATALAKSANAAN)) {
                        FormHelper.setValue(
                            $section,
                            'me_4',
                            tlt.ME_RENCANA_PENATALAKSANAAN
                        );
                    }

                    if (FormHelper.hasValue(tlt.ME_TINDAKAN_TUJUAN_TERAPI)) {
                        FormHelper.setValue(
                            $section,
                            'me_5',
                            tlt.ME_TINDAKAN_TUJUAN_TERAPI
                        );
                    }

                    if (FormHelper.hasValue(tlt.SIE_LEAFLET)) {
                        FormHelper.setValue(
                            $section,
                            'sie_1',
                            tlt.SIE_LEAFLET
                        );
                    }

                    if (FormHelper.hasValue(tlt.SIE_LISAN)) {
                        FormHelper.setValue(
                            $section,
                            'sie_2',
                            tlt.SIE_LISAN
                        );
                    }

                    if (FormHelper.hasValue(tlt.EVAL_SUDAH_MENGERTI)) {
                        FormHelper.setValue(
                            $section,
                            'eval_1',
                            tlt.EVAL_SUDAH_MENGERTI
                        );
                    }

                    if (FormHelper.hasValue(tlt.EVAL_RE_EDUKASI)) {
                        FormHelper.setValue(
                            $section,
                            'eval_2',
                            tlt.EVAL_RE_EDUKASI
                        );
                    }
                },

                error: function (xhr, status, error) {

                    console.error(
                        'Error Materi Edukasi:',
                        xhr.responseText || error
                    );

                    let message =
                        'Gagal mengambil data Materi Edukasi.';

                    if (xhr.responseJSON?.message) {
                        message = xhr.responseJSON.message;
                    }

                    console.warn(message);
                },

                complete: function () {
                    isMateriEdukasiLoading = false;
                }
            });
        }

        // ==========================================================
        // SIMPAN DATA
        // ==========================================================
        function simpanMateriEdukasi() {

            if (
                !$form.length ||
                isMateriEdukasiLoading ||
                isMateriEdukasiSaving
            ) {
                return;
            }

            const data = getFormDataByName($form, {
                NOKUNJ: kunjungan
            });

            isMateriEdukasiSaving = true;

            $.ajax({
                url: `/api/v2/emr/pengkajian/rajal/materiedukasi/${kunjungan}/simpan`,
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
                        'Data Materi Edukasi gagal disimpan.';

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
                    isMateriEdukasiSaving = false;
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

            getMateriEdukasi();

            $form.on(
                'blur',
                'textarea,input',
                function () {

                    if (isMateriEdukasiLoading) {
                        return;
                    }

                    simpanMateriEdukasi();
                }
            );

            $form.on(
                'change',
                'select,input[type="checkbox"],input[type="radio"]',
                function () {

                    if (isMateriEdukasiLoading) {
                        return;
                    }

                    simpanMateriEdukasi();
                }
            );
        });

    })();
</script>
