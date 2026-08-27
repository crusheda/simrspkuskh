<div class="form-wrapper" id="form_khusus_lanjutan">
    <h1 class="display-6 mb-1 mt-2 fs-23 fw-medium"><center>PENGKAJIAN <b class="text-primary">PASIEN LANJUTAN</b></center></h1>
    <div class="form-content">
        <div class="row">
            {{-- COL --}}
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    const $form = $('#form_khusus_lanjutan');

    let isDataLoading = false;
    let isDataSaving = false;

    // ==========================================================
    // GET DATA
    // ==========================================================
    function getData() {

        if (!$form.length) {
            console.warn('Form Data tidak ditemukan.');
            return;
        }

        isDataLoading = true;

        $.ajax({
            url: `/api/v2/emr/form/pengkajian/khu/lanjutan/${kunjungan}`,
            type: 'GET',
            dataType: 'json',

            success: function (res) {

                const tlt = res.data;

                if (!tlt) {
                    return;
                }

                if (FormHelper.hasValue(tlt.DESKRIPSI)) {
                    FormHelper.setValue(
                        $form,
                        'lanjutan', // name inputnya
                        tlt.DESKRIPSI
                    );
                }
            },

            error: function (xhr, status, error) {

                console.error(
                    'Error :',
                    xhr.responseText || error
                );

                let message =
                    'Gagal mengambil data.';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                console.warn(message);
            },

            complete: function () {
                isDataLoading = false;
            }
        });
    }

    // ==========================================================
    // SIMPAN DATA
    // ==========================================================
    function simpanData() {

        if (
            !$form.length ||
            isDataLoading ||
            isDataSaving
        ) {
            return;
        }

        const data = getFormDataByName($form, {
            NOKUNJ: kunjungan
        });

        isDataSaving = true;

        $.ajax({
            url: `/api/v2/emr/form/pengkajian/khu/lanjutan/${kunjungan}/simpan`,
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
                    'Data gagal disimpan.';

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
                isDataSaving = false;
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

        getData();

        $form.on(
            'blur',
            'textarea,input',
            function () {

                if (isDataLoading) {
                    return;
                }

                simpanData();
            }
        );

        $form.on(
            'change',
            'select,input[type="checkbox"],input[type="radio"]',
            function () {

                if (isDataLoading) {
                    return;
                }

                simpanData();
            }
        );
    });

})();
</script>
