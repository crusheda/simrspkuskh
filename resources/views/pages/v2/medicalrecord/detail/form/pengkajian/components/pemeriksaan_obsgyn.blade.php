<div class="row align-items-center" id="form_pemeriksaan_obsgyn">
    <div class="col-md-12 mb-1">
        <h4 class="mb-3 text-danger">Pemeriksaan</h4>
        <div class="mb-3">
            <h6>Pemeriksaan Fisik</h6>
            <textarea class="form-control" name="pfisik" rows="3"></textarea>
        </div>
    </div>
    <div class="col-md-12 mb-1">
        <div class="mb-3">
            <h6>Pemeriksaan Obstetri</h6>
            <textarea class="form-control" name="pobs" rows="3"></textarea>
        </div>
    </div>
    <div class="col-md-12 mb-1">
        <div class="mb-3">
            <h6>Pemeriksaan Gynekologi</h6>
            <textarea class="form-control" name="pgyn" rows="3"></textarea>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    const $section = $(@json($section));
    const $form = $section.find('#form_pemeriksaan_obsgyn');

    let isPemeriksaanObsgynLoading = false;
    let isPemeriksaanObsgynSaving = false;

    // ==========================================================
    // GET DATA
    // ==========================================================
    function getPemeriksaanObsgyn() {

        if (!$form.length) {
            console.warn('Form Pemeriksaan Obsgyn tidak ditemukan.');
            return;
        }

        isPemeriksaanObsgynLoading = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/pobgn/${kunjungan}`,
            type: 'GET',
            dataType: 'json',

            success: function (res) {

                const tlt = res.data;

                if (!tlt) {
                    return;
                }

                FormHelper.setValue(
                    $section,
                    'pfisik',
                    tlt.FISIK
                );
                FormHelper.setValue(
                    $section,
                    'pobs',
                    tlt.OBSTETRI
                );
                FormHelper.setValue(
                    $section,
                    'pgyn',
                    tlt.GYNEKOLOGI
                );
                // if (FormHelper.hasValue(tlt.FISIK)) {
                // }
            },

            error: function (xhr, status, error) {

                console.error(
                    'Error Pemeriksaan Obsgyn:',
                    xhr.responseText || error
                );

                let message =
                    'Gagal mengambil data Pemeriksaan Obsgyn.';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                console.warn(message);
            },

            complete: function () {
                isPemeriksaanObsgynLoading = false;
            }
        });
    }

    // ==========================================================
    // SIMPAN DATA
    // ==========================================================
    function simpanPemeriksaanObsgyn() {

        if (
            !$form.length ||
            isPemeriksaanObsgynLoading ||
            isPemeriksaanObsgynSaving
        ) {
            return;
        }

        const data = getFormDataByName($form, {
            NOKUNJ: kunjungan
        });

        isPemeriksaanObsgynSaving = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/pobgn/${kunjungan}/simpan`,
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
                    'Data Pemeriksaan Obsgyn gagal disimpan.';

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
                isPemeriksaanObsgynSaving = false;
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

        getPemeriksaanObsgyn();

        $form.on(
            'blur',
            'textarea,input',
            function () {

                if (isPemeriksaanObsgynLoading) {
                    return;
                }

                simpanPemeriksaanObsgyn();
            }
        );

        $form.on(
            'change',
            'select,input[type="checkbox"],input[type="radio"]',
            function () {

                if (isPemeriksaanObsgynLoading) {
                    return;
                }

                simpanPemeriksaanObsgyn();
            }
        );
    });

})();
</script>
