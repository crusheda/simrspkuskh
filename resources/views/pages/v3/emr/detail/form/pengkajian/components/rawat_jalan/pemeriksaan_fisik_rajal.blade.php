<div class="row align-items-center" id="form_target_terapi">
    <div class="col-md-12 mb-3">
        <label class="form-label fw-bold">Pemeriksaan Fisik</label>
        <textarea class="form-control" name="pfisik" id="pfisik" rows="5"></textarea>
    </div>
</div>

<script>
(function () {
    'use strict';

    const $section = $(@json($section));
    const $form = $section.find('#form_target_terapi');

    let isPemFisLoading = false;
    let isPemFisSaving = false;

    // ==========================================================
    // GET DATA
    // ==========================================================
    function getPemeriksaanFisik() {

        if (!$form.length) {
            console.warn('Form Pemeriksaan Fisik tidak ditemukan.');
            return;
        }

        isPemFisLoading = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/rajal/pemeriksaan_fisik/${kunjungan}`,
            type: 'GET',
            dataType: 'json',

            success: function (res) {

                const tlt = res.data;

                if (!tlt) {
                    return;
                }

                if (FormHelper.hasValue(tlt.DESKRIPSI)) {
                    FormHelper.setValue(
                        $section,
                        'pfisik',
                        tlt.DESKRIPSI
                    );
                }
            },

            error: function (xhr, status, error) {

                console.error(
                    'Error Pemeriksaan Fisik:',
                    xhr.responseText || error
                );

                let message =
                    'Gagal mengambil data Pemeriksaan Fisik.';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                console.warn(message);
            },

            complete: function () {
                isPemFisLoading = false;
            }
        });
    }

    // ==========================================================
    // SIMPAN DATA
    // ==========================================================
    function simpanPemeriksaanFisik() {

        if (
            !$form.length ||
            isPemFisLoading ||
            isPemFisSaving
        ) {
            return;
        }

        const data = getFormDataByName($form, {
            NOKUNJ: kunjungan
        });

        isPemFisSaving = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/rajal/pemeriksaan_fisik/${kunjungan}/simpan`,
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
                    'Data Pemeriksaan Fisik gagal disimpan.';

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
                isPemFisSaving = false;
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

        getPemeriksaanFisik();

        $form.on(
            'blur',
            'textarea,input',
            function () {

                if (isPemFisLoading) {
                    return;
                }

                simpanPemeriksaanFisik();
            }
        );

        $form.on(
            'change',
            'select,input[type="checkbox"],input[type="radio"]',
            function () {

                if (isPemFisLoading) {
                    return;
                }

                simpanPemeriksaanFisik();
            }
        );
    });

})();
</script>
