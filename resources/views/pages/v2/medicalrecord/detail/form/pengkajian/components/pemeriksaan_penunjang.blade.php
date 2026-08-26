<div class="row align-items-center" id="form_penunjang_lain">
    <div class="col-md-12 mb-1">
        <h4 class="text-warning">Pemeriksaan USG</h4>
        <div class="row">
            <div class="col-md-6 mb-3">
                <h6>Hasil Pemeriksaan</h6>
                <textarea class="form-control" name="usg_hasil" rows="3"></textarea>
            </div>
            <div class="col-md-6 mb-3">
                <h6>Kesimpulan</h6>
                <textarea class="form-control" name="usg_kesimpulan" rows="3"></textarea>
            </div>
        </div>
    </div>
    <div class="col-md-12 mb-1">
        <div class="mb-3">
            <h6>Pemeriksaan Penunjang Lainnya</h6>
            <textarea class="form-control" name="penlain" rows="3"></textarea>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    const $section = $(@json($section));
    const $form = $section.find('#form_penunjang_lain');

    let isPenunjangLainLoading = false;
    let isPenunjangLainSaving = false;

    // ==========================================================
    // GET DATA
    // ==========================================================
    function getPenunjangLain() {

        if (!$form.length) {
            console.warn('Form Penunjang Lain tidak ditemukan.');
            return;
        }

        isPenunjangLainLoading = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/penunjanglain/${kunjungan}`,
            type: 'GET',
            dataType: 'json',

            success: function (res) {

                const tlt = res.data;

                if (!tlt) {
                    return;
                }

                FormHelper.setValue(
                    $section,
                    'usg_hasil',
                    tlt.HASIL
                );
                FormHelper.setValue(
                    $section,
                    'usg_kesimpulan',
                    tlt.KESIMPULAN
                );
                FormHelper.setValue(
                    $section,
                    'penlain',
                    tlt.DESKRIPSI
                );
                // if (FormHelper.hasValue(tlt.FISIK)) {
                // }
            },

            error: function (xhr, status, error) {

                console.error(
                    'Error Penunjang Lain:',
                    xhr.responseText || error
                );

                let message =
                    'Gagal mengambil data Penunjang Lain.';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                console.warn(message);
            },

            complete: function () {
                isPenunjangLainLoading = false;
            }
        });
    }

    // ==========================================================
    // SIMPAN DATA
    // ==========================================================
    function simpanPenunjangLain() {

        if (
            !$form.length ||
            isPenunjangLainLoading ||
            isPenunjangLainSaving
        ) {
            return;
        }

        const data = getFormDataByName($form, {
            NOKUNJ: kunjungan
        });

        isPenunjangLainSaving = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/penunjanglain/${kunjungan}/simpan`,
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
                    'Data Penunjang Lain gagal disimpan.';

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
                isPenunjangLainSaving = false;
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

        console.log($section);

        getPenunjangLain();

        $form.on(
            'blur',
            'textarea,input',
            function () {

                if (isPenunjangLainLoading) {
                    return;
                }

                simpanPenunjangLain();
            }
        );

        $form.on(
            'change',
            'select,input[type="checkbox"],input[type="radio"]',
            function () {

                if (isPenunjangLainLoading) {
                    return;
                }

                simpanPenunjangLain();
            }
        );
    });

})();
</script>
