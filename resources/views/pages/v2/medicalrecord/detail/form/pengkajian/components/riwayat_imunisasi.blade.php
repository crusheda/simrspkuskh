<div class="col-md-12" id="form_riwayat_imunisasi">

    <label class="form-label fw-bold">
        Riwayat Imunisasi
    </label>

    <div class="d-flex flex-wrap gap-4">

        <div class="form-check">
            <input
                class="form-check-input"
                type="radio"
                name="imunisasi"
                id="imunisasi_1"
                value="1"
            >
            <label class="form-check-label" for="imunisasi_1">
                Imunisasi Dasar Lengkap
            </label>
        </div>

        <div class="form-check">
            <input
                class="form-check-input"
                type="radio"
                name="imunisasi"
                id="imunisasi_2"
                value="2"
            >
            <label class="form-check-label" for="imunisasi_2">
                Imunisasi Dasar Tidak Lengkap
            </label>
        </div>

        <div class="form-check">
            <input
                class="form-check-input"
                type="radio"
                name="imunisasi"
                id="imunisasi_3"
                value="3"
            >
            <label class="form-check-label" for="imunisasi_3">
                Tidak Imunisasi
            </label>
        </div>

        <div class="form-check">
            <input
                class="form-check-input"
                type="radio"
                name="imunisasi"
                id="imunisasi_4"
                value="4"
            >
            <label class="form-check-label" for="imunisasi_4">
                Lain-lain
            </label>
        </div>

    </div>

    <div class="mt-2" id="imunisasi_lain_wrapper" style="display:none;">
        <input
            type="text"
            class="form-control"
            name="imunisasi_lain"
            id="imunisasi_lain"
            placeholder="Jelaskan..."
        >
    </div>

</div>

<script>
(function () {

    'use strict';


    // ==============================================================
    // STATE
    // ==============================================================

    let isRiwayatImunisasiDirty = false;


    // ==============================================================
    // GET DATA
    // ==============================================================

    function getRiwayatImunisasi() {

        $.ajax({

            url: `/api/v2/emr/pengkajian/riwayat_imunisasi/${kunjungan}`,

            type: 'GET',

            dataType: 'json',

            success: function (res) {

                const data = res.data;

                if (!data) {
                    return;
                }


                const $form =
                    $('#form_riwayat_imunisasi');


                // ==================================================
                // RADIO IMUNISASI
                // ==================================================

                $form
                    .find(
                        `[name="imunisasi"][value="${data.imunisasi}"]`
                    )
                    .prop('checked', true);


                // ==================================================
                // IMUNISASI LAIN
                // ==================================================

                $form
                    .find('[name="imunisasi_lain"]')
                    .val(data.imunisasi_lain ?? '');


                // ==================================================
                // TAMPILKAN / SEMBUNYIKAN INPUT LAIN
                // ==================================================

                toggleImunisasiLain();


                // GET tidak dianggap perubahan user
                isRiwayatImunisasiDirty = false;

            },


            error: function (xhr, status, error) {

                console.error(
                    'Error Riwayat Imunisasi:',
                    xhr.responseText || error
                );

            }

        });

    }


    // ==============================================================
    // TOGGLE INPUT LAIN-LAIN
    // ==============================================================

    function toggleImunisasiLain() {

        const value =
            $('#form_riwayat_imunisasi')
                .find('[name="imunisasi"]:checked')
                .val();

        const $input =
            $('#form_riwayat_imunisasi')
                .find('[name="imunisasi_lain"]');


        if (value == '4') {

            $('#imunisasi_lain_wrapper')
                .show();

        } else {

            $('#imunisasi_lain_wrapper')
                .hide();

            // Kosongkan jika bukan pilihan lain-lain
            $input.val('');
        }

    }


    // ==============================================================
    // SIMPAN DATA
    // ==============================================================

    function simpanRiwayatImunisasi() {

        const $form =
            $('#form_riwayat_imunisasi');


        const data = getFormDataByName(
            $form,
            {
                NOKUNJ: kunjungan
            }
        );


        $.ajax({

            url: `/api/v2/emr/pengkajian/riwayat_imunisasi/${kunjungan}/simpan`,

            type: 'POST',

            data: data,

            headers: {
                'X-CSRF-TOKEN':
                    $('meta[name="csrf-token"]').attr('content')
            },


            success: function (res) {

                console.log(
                    'Riwayat Imunisasi berhasil disimpan.',
                    res
                );

            },


            error: function (xhr) {

                let message =
                    'Data gagal disimpan.';


                if (
                    xhr.status === 422 &&
                    xhr.responseJSON &&
                    xhr.responseJSON.errors
                ) {

                    message =
                        Object.values(
                            xhr.responseJSON.errors
                        )
                        .flat()
                        .join('&nbsp;');

                }

                else if (
                    xhr.responseJSON &&
                    xhr.responseJSON.message
                ) {

                    message =
                        xhr.responseJSON.message;

                }


                iziToast.error({

                    title: 'Validasi Gagal!',

                    message: message,

                    position: 'topRight'

                });

            }

        });

    }


    // ==============================================================
    // DOCUMENT READY
    // ==============================================================

    $(function () {

        const $form =
            $('#form_riwayat_imunisasi');


        // ==========================================================
        // LOAD DATA
        // ==========================================================

        getRiwayatImunisasi();


        // ==========================================================
        // RADIO
        // ==========================================================

        $form.on(
            'change',
            'input[type="radio"]',
            function (e) {

                // Tampilkan / sembunyikan input lain
                toggleImunisasiLain();


                // Jangan save ketika radio diisi oleh GET
                if (!e.originalEvent) {
                    return;
                }


                simpanRiwayatImunisasi();

            }
        );


        // ==========================================================
        // INPUT IMUNISASI LAIN
        // ==========================================================

        $form.on(
            'input',
            '[name="imunisasi_lain"]',
            function () {

                isRiwayatImunisasiDirty = true;

            }
        );


        // ==========================================================
        // BLUR IMUNISASI LAIN
        // ==========================================================

        $form.on(
            'blur',
            '[name="imunisasi_lain"]',
            function () {

                if (!isRiwayatImunisasiDirty) {
                    return;
                }


                simpanRiwayatImunisasi();


                isRiwayatImunisasiDirty = false;

            }
        );

    });

})();
</script>