<div class="col-md-12 mb-3" id="form_pemeriksaan_khusus_obs">
    <h4 class="text-danger">Pemeriksaan Khusus</h4>
    <!-- DADA -->
    <div class="row align-items-center mb-2">
        <div class="col-md-2">
            <label class="form-label mb-0">Dada</label>
        </div>
        <!-- Mammae -->
        <div class="col-md-3">
            <div class="d-flex align-items-center gap-2">
                <div class="form-check">
                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="dada_1" value="1">
                    <label class="form-check-label">
                        Mammae simetris / Asimetris
                    </label>
                </div>
            </div>
        </div>

        <!-- Areola -->
        <div class="col-md-3">
            <div class="form-check">
                <input class="form-check-input check-primary" type="checkbox" name="dada_2" value="2">
                <label class="form-check-label">
                    Areola hiperpigmentasi
                </label>
            </div>
        </div>

        <!-- Puting -->
        <div class="col-md-4">
            <div class="d-flex align-items-center gap-2">
                <div class="form-check">
                    <input class="form-check-input check-primary checkbox" type="checkbox" name="dada_3" value="3">
                    <label class="form-check-label">
                        Puting susu menonjol / Tidak
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- KOLOSTRUM -->
    <div class="row align-items-center mb-2">
        <div class="col-md-2"></div>

        <div class="col-md-3">
            <div class="d-flex align-items-center gap-2">

                <div class="form-check">
                    <input class="form-check-input check-primary checkbox" type="checkbox" name="dada_4" value="4">
                    <label class="form-check-label">
                        Kolostrum (+) / (-)
                    </label>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="d-flex align-items-center gap-2">
                <div class="form-check flex-shrink-0">
                    <input class="form-check-input check-primary" type="checkbox" name="dada_5" value="5">
                </div>
                <input type="text" class="form-control" name="kolostrum_keterangan" id="kolostrum_keterangan" placeholder="Lainnya">
            </div>
        </div>
    </div>


    <!-- ABDOMEN - INSPEKSI -->
    <div class="row align-items-center mb-2">
        <div class="col-md-2">
            <label class="form-label mb-0">Abdomen Inspeksi</label>
        </div>

        <div class="col-md-10">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <div class="form-check">
                    <input class="form-check-input check-primary" type="checkbox" name="abdomen_luka_bekas_op" value="1">
                    <label class="form-check-label">
                        Luka bekas OP
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input check-primary" type="checkbox" name="abdomen_linea_alba" value="1">
                    <label class="form-check-label">
                        Linea Alba
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input check-primary" type="checkbox" name="abdomen_linea_nigra" value="1">
                    <label class="form-check-label">
                        Linea Nigra
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input check-primary" type="checkbox" name="abdomen_striae_livida" value="1">
                    <label class="form-check-label">
                        Striae Livida
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input check-primary" type="checkbox" name="abdomen_striae_albican" value="1">
                    <label class="form-check-label">
                        Striae Albican
                    </label>
                </div>

            </div>
        </div>
    </div>


    <!-- ABDOMEN - PALPASI -->
    <div class="row align-items-center mb-2">
        <div class="col-md-2">
            <label class="form-label mb-0">Palpasi</label>
        </div>
        <div class="col-md-4 mb-2">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <span>Leopold I :</span>
                <div class="form-check">
                    <label class="form-check-label">
                        TFU
                    </label>
                </div>
                <input type="text" class="form-control form-control-sm" name="leopold_1_tfu" id="leopold_1_tfu" placeholder="TFU" style="width: 100px;">
                <span>cm</span>

            </div>
        </div>
        <div class="col-md-6 mb-2">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <span>Leopold II :</span>
                <div class="form-check">
                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="leopold_2" value="1">
                    <label class="form-check-label">
                        Punggung Kanan
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="leopold_2" value="2">
                    <label class="form-check-label">
                        Punggung Kiri
                    </label>
                </div>
            </div>
        </div>
    </div>
    <!-- LEOPOLD III -->
    <div class="row align-items-center mb-2">
        <div class="col-md-2"></div>
        <div class="col-md-4 mb-2">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <span>Leopold III :</span>
                <div class="form-check">
                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="leopold_3" value="1">
                    <label class="form-check-label">
                        Kepala
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="leopold_3" value="2">
                    <label class="form-check-label">
                        Bokong
                    </label>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-2">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <span>Leopold IV :</span>
                <div class="form-check">
                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="leopold_4" value="1">
                    <label class="form-check-label">
                        Sudah masuk PAP
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="leopold_4" value="2">
                    <label class="form-check-label">
                        Belum masuk PAP
                    </label>
                </div>
            </div>
        </div>
    </div>
    <!-- AUSKULTASI -->
    <div class="row align-items-center mb-2">
        <div class="col-md-2">
            <label class="form-label mb-0">Auskultasi</label>
        </div>

        <div class="col-md-10">
            <div class="d-flex align-items-center flex-wrap gap-3">

                <span>DJJ :</span>

                <input type="text" class="form-control" name="djj" id="djj" placeholder="DJJ" style="width: 100px;">

                <span>X/menit</span>

                <div class="form-check">
                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="djj_kondisi" value="1">
                    <label class="form-check-label">
                        Teratur
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="djj_kondisi" value="2">
                    <label class="form-check-label">
                        Tidak teratur
                    </label>
                </div>

            </div>
        </div>
    </div>


    <!-- HIS/KONTRAKSI -->
    <div class="row align-items-center mb-2">
        <div class="col-md-2">
            <label class="form-label mb-0">HIS/Kontraksi</label>
        </div>

        <div class="col-md-10">
            <div class="d-flex align-items-center flex-wrap gap-3">

                <input type="text" class="form-control" name="his" id="his" placeholder="HIS" style="width: 100px;">
                <span>X/menit, Durasi</span>

                <input type="text" class="form-control" name="his_durasi" id="his_durasi" placeholder="Durasi" style="width: 100px;">

                <span>detik,</span>

                <div class="form-check">
                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="his_kekuatan" value="1">
                    <label class="form-check-label">
                        Kuat
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="his_kekuatan" value="2">
                    <label class="form-check-label">
                        Sedang
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input check-primary single-checkbox" type="checkbox" name="his_kekuatan" value="3">
                    <label class="form-check-label">
                        Lemah
                    </label>
                </div>

            </div>
        </div>
    </div>


    <!-- ANOGENITAL - INSPEKSI -->
    <div class="row align-items-center mb-2">
        <div class="col-md-2">
            <label class="form-label mb-0">Anogenital Inspeksi</label>
        </div>

        <div class="col-md-10">
            <div class="d-flex align-items-center flex-wrap gap-3">
                <span>Pengeluaran per Vagina</span>

                <div class="form-check">
                    <input class="form-check-input check-primary" type="checkbox" name="anogenital_darah" value="1">
                    <label class="form-check-label">
                        Darah
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input check-primary" type="checkbox" name="anogenital_lendir" value="1">
                    <label class="form-check-label">
                        Lendir
                    </label>
                </div>

                <div class="form-check">
                    <input class="form-check-input check-primary" type="checkbox" name="anogenital_air_ketuban" value="1">
                    <label class="form-check-label">
                        Air Ketuban
                    </label>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <div class="form-check">
                        <input class="form-check-input check-primary" type="checkbox" name="anogenital_lainnya" value="1">
                        <label class="form-check-label">
                            Lainnya
                        </label>
                    </div>

                    <input type="text" class="form-control" name="anogenital_lainnya_keterangan" id="anogenital_lainnya_keterangan" placeholder="Keterangan...">
                </div>

            </div>
        </div>
    </div>


    <!-- VAGINA TAUCHER -->
    <div class="row align-items-center mb-2">
        <div class="col-md-2">
            <label class="form-label mb-0">Vagina Taucher</label>
        </div>

        <div class="col-md-10">
            <input type="text" class="form-control" name="vagina_taucher" id="vagina_taucher" placeholder="Hasil pemeriksaan vagina taucher...">
        </div>
    </div>


    <!-- LAIN-LAIN -->
    <div class="row align-items-center mb-2">
        <div class="col-md-2">
            <label class="form-label mb-0">Lain-lain</label>
        </div>

        <div class="col-md-10">
            <input type="text" class="form-control" name="pemeriksaan_lain_lain" id="pemeriksaan_lain_lain" placeholder="Lain-lain...">
        </div>
    </div>

</div>
<script>
(function () {

    'use strict';

    // ==============================================================
    // STATE
    // ==============================================================

    let isPemeriksaanKhususObsDirty = false;


    // ==============================================================
    // GET DATA
    // ==============================================================

    function getPemeriksaanKhususObs() {

        const $form = $('#form_pemeriksaan_khusus_obs');

        $.ajax({

            url: `/api/v2/emr/pengkajian/ri/pemeriksaankhususobsgyn/${kunjungan}`,

            type: 'GET',

            dataType: 'json',

            success: function (res) {

                const data = res.data;

                if (!data) {
                    return;
                }


                // ==================================================
                // DADA
                // ==================================================

                $form
                    .find('[name="dada_1"]')
                    .prop('checked', data.dada_1 == 1);

                $form
                    .find('[name="dada_2"]')
                    .prop('checked', data.dada_2 == 1);

                $form
                    .find('[name="dada_3"]')
                    .prop('checked', data.dada_3 == 1);

                $form
                    .find('[name="dada_4"]')
                    .prop('checked', data.dada_4 == 1);

                $form
                    .find('[name="dada_5"]')
                    .prop('checked', data.dada_5 == 1);


                $form
                    .find('[name="kolostrum_keterangan"]')
                    .val(data.kolostrum_keterangan ?? '');


                // ==================================================
                // ABDOMEN - INSPEKSI
                // ==================================================

                $form
                    .find('[name="abdomen_luka_bekas_op"]')
                    .prop('checked', data.abdomen_luka_bekas_op == 1);

                $form
                    .find('[name="abdomen_linea_alba"]')
                    .prop('checked', data.abdomen_linea_alba == 1);

                $form
                    .find('[name="abdomen_linea_nigra"]')
                    .prop('checked', data.abdomen_linea_nigra == 1);

                $form
                    .find('[name="abdomen_striae_livida"]')
                    .prop('checked', data.abdomen_striae_livida == 1);

                $form
                    .find('[name="abdomen_striae_albican"]')
                    .prop('checked', data.abdomen_striae_albican == 1);


                // ==================================================
                // LEOPOLD
                // ==================================================

                $form
                    .find('[name="leopold_1_tfu"]')
                    .val(data.leopold_1_tfu ?? '');


                $form
                    .find(
                        `[name="leopold_2"][value="${data.leopold_2}"]`
                    )
                    .prop('checked', true);


                $form
                    .find(
                        `[name="leopold_3"][value="${data.leopold_3}"]`
                    )
                    .prop('checked', true);


                $form
                    .find(
                        `[name="leopold_4"][value="${data.leopold_4}"]`
                    )
                    .prop('checked', true);


                // ==================================================
                // AUSKULTASI
                // ==================================================

                $form
                    .find('[name="djj"]')
                    .val(data.djj ?? '');


                $form
                    .find(
                        `[name="djj_kondisi"][value="${data.djj_kondisi}"]`
                    )
                    .prop('checked', true);


                // ==================================================
                // HIS / KONTRAKSI
                // ==================================================

                $form
                    .find('[name="his"]')
                    .val(data.his ?? '');

                $form
                    .find('[name="his_durasi"]')
                    .val(data.his_durasi ?? '');


                $form
                    .find(
                        `[name="his_kekuatan"][value="${data.his_kekuatan}"]`
                    )
                    .prop('checked', true);


                // ==================================================
                // ANOGENITAL
                // ==================================================

                $form
                    .find('[name="anogenital_darah"]')
                    .prop('checked', data.anogenital_darah == 1);

                $form
                    .find('[name="anogenital_lendir"]')
                    .prop('checked', data.anogenital_lendir == 1);

                $form
                    .find('[name="anogenital_air_ketuban"]')
                    .prop('checked', data.anogenital_air_ketuban == 1);

                $form
                    .find('[name="anogenital_lainnya"]')
                    .prop('checked', data.anogenital_lainnya == 1);


                $form
                    .find('[name="anogenital_lainnya_keterangan"]')
                    .val(data.anogenital_lainnya_keterangan ?? '');


                // ==================================================
                // LAIN-LAIN
                // ==================================================

                $form
                    .find('[name="vagina_taucher"]')
                    .val(data.vagina_taucher ?? '');

                $form
                    .find('[name="pemeriksaan_lain_lain"]')
                    .val(data.pemeriksaan_lain_lain ?? '');

            },

            error: function (xhr, status, error) {

                console.error(
                    'Error Pemeriksaan Khusus:',
                    xhr.responseText || error
                );

            }

        });

    }


    // ==============================================================
    // SIMPAN DATA
    // ==============================================================

    function simpanPemeriksaanKhususObs() {

        const $form =
            $('#form_pemeriksaan_khusus_obs');


        const data = getFormDataByName(
            $form,
            {
                NOKUNJ: kunjungan
            }
        );


        $.ajax({

            url: `/api/v2/emr/pengkajian/ri/pemeriksaankhususobsgyn/${kunjungan}/simpan`,

            type: 'POST',

            data: data,

            headers: {

                'X-CSRF-TOKEN':
                    $('meta[name="csrf-token"]').attr('content')

            },

            success: function (res) {

                console.log(
                    'Pemeriksaan Khusus berhasil disimpan.',
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
            $('#form_pemeriksaan_khusus_obs');


        // ==========================================================
        // LOAD DATA
        // ==========================================================

        getPemeriksaanKhususObs();


        // ==========================================================
        // INPUT
        // ==========================================================

        $form.on(
            'input',
            'textarea, input:not([type="checkbox"]):not([type="radio"])',
            function () {

                isPemeriksaanKhususObsDirty = true;

            }
        );


        // ==========================================================
        // BLUR
        // ==========================================================

        $form.on(
            'blur',
            'textarea, input:not([type="checkbox"]):not([type="radio"])',
            function () {

                if (!isPemeriksaanKhususObsDirty) {
                    return;
                }


                simpanPemeriksaanKhususObs();


                isPemeriksaanKhususObsDirty = false;

            }
        );


        // ==========================================================
        // CHECKBOX
        // ==========================================================

        $form.on(
            'change',
            'input[type="checkbox"]',
            function (e) {

                // Jangan save saat GET mengisi form
                if (!e.originalEvent) {
                    return;
                }


                simpanPemeriksaanKhususObs();

            }
        );

    });

})();
</script>