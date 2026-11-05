<div class="row align-items-center" id="form_plan_tata_laksana_penunjang">
    <div class="col-md-12 mb-2">
        <div class="form-group">
            <h4 class="mb-0 text-danger">
                Plan Tata Laksana Penunjang
            </h4>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <textarea
                class="form-control"
                name="plan_tata_laksana_penunjang"
                rows="2"
                placeholder=""></textarea>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    const $section = $(@json($section));
    const $form = $section.find('#form_plan_tata_laksana_penunjang');

    let isPlanTataLaksanaPenunjangLoading = false;
    let isPlanTataLaksanaPenunjangSaving = false;

    // ==========================================================
    // GET DATA
    // ==========================================================
    function getPlanTataLaksanaPenunjang() {

        if (!$form.length) {
            console.warn('Form Plan Tata Laksana Penunjang tidak ditemukan.');
            return;
        }

        isPlanTataLaksanaPenunjangLoading = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/plantatalaksanapenunjang/${kunjungan}`,
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
                        'plan_tata_laksana_penunjang',
                        tlt.DESKRIPSI
                    );
                }
            },

            error: function (xhr, status, error) {

                console.error(
                    'Error Plan Tata Laksana Penunjang:',
                    xhr.responseText || error
                );

                let message =
                    'Gagal mengambil data Plan Tata Laksana Penunjang.';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                console.warn(message);
            },

            complete: function () {
                isPlanTataLaksanaPenunjangLoading = false;
            }
        });
    }

    // ==========================================================
    // SIMPAN DATA
    // ==========================================================
    function simpanPlanTataLaksanaPenunjang() {

        if (
            !$form.length ||
            isPlanTataLaksanaPenunjangLoading ||
            isPlanTataLaksanaPenunjangSaving
        ) {
            return;
        }

        const data = getFormDataByName($form, {
            NOKUNJ: kunjungan
        });

        isPlanTataLaksanaPenunjangSaving = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/plantatalaksanapenunjang/${kunjungan}/simpan`,
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
                    'Data Plan Tata Laksana Penunjang gagal disimpan.';

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
                isPlanTataLaksanaPenunjangSaving = false;
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

        getPlanTataLaksanaPenunjang();

        $form.on(
            'blur',
            'textarea,input',
            function () {

                if (isPlanTataLaksanaPenunjangLoading) {
                    return;
                }

                simpanPlanTataLaksanaPenunjang();
            }
        );

        $form.on(
            'change',
            'select,input[type="checkbox"],input[type="radio"]',
            function () {

                if (isPlanTataLaksanaPenunjangLoading) {
                    return;
                }

                simpanPlanTataLaksanaPenunjang();
            }
        );
    });

})();
</script>
