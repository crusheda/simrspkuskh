<div class="row align-items-center" id="form_rencana_konsultasi">
    <div class="col-md-12 mb-2">
        <div class="form-group">
            <h4 class="mb-0 text-danger">
                Rencana Konsultasi
            </h4>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <textarea
                class="form-control"
                name="rencana_konsultasi"
                rows="2"
                placeholder="Masukkan Rencana Konsultasi"></textarea>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    const $section = $(@json($section));
    const $form = $section.find('#form_rencana_konsultasi');

    let isRencanaKonsultasiLoading = false;
    let isRencanaKonsultasiSaving = false;

    // ==========================================================
    // GET DATA
    // ==========================================================
    function getRencanaKonsultasi() {

        if (!$form.length) {
            console.warn('Form Rencana Konsultasi tidak ditemukan.');
            return;
        }

        isRencanaKonsultasiLoading = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/rencanakonsultasi/${kunjungan}`,
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
                        'rencana_konsultasi',
                        tlt.DESKRIPSI
                    );
                }
            },

            error: function (xhr, status, error) {

                console.error(
                    'Error Rencana Konsultasi:',
                    xhr.responseText || error
                );

                let message =
                    'Gagal mengambil data Rencana Konsultasi.';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                console.warn(message);
            },

            complete: function () {
                isRencanaKonsultasiLoading = false;
            }
        });
    }

    // ==========================================================
    // SIMPAN DATA
    // ==========================================================
    function simpanRencanaKonsultasi() {

        if (
            !$form.length ||
            isRencanaKonsultasiLoading ||
            isRencanaKonsultasiSaving
        ) {
            return;
        }

        const data = getFormDataByName($form, {
            NOKUNJ: kunjungan
        });

        isRencanaKonsultasiSaving = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/rencanakonsultasi/${kunjungan}/simpan`,
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
                    'Data Rencana Konsultasi gagal disimpan.';

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
                isRencanaKonsultasiSaving = false;
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

        getRencanaKonsultasi();

        $form.on(
            'blur',
            'textarea,input',
            function () {

                if (isRencanaKonsultasiLoading) {
                    return;
                }

                simpanRencanaKonsultasi();
            }
        );

        $form.on(
            'change',
            'select,input[type="checkbox"],input[type="radio"]',
            function () {

                if (isRencanaKonsultasiLoading) {
                    return;
                }

                simpanRencanaKonsultasi();
            }
        );
    });

})();
</script>
