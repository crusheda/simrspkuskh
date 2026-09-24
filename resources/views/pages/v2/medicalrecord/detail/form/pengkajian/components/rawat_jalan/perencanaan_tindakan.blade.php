@php
    $form = strtolower($form ?? 'umum');
@endphp
<div class="col-md-12 mb-3" id="form_perencanaan">
    <div class="row">

        <label class="form-label fw-bold">
            Perencanaan dan Tindakan
        </label>

        {{-- //=====================================================
        //MANDIRI
        //===================================================== --}}
        <div class="col-md-6 border-end {{ $form === 'umum' ? '' : 'd-none' }}" id="perencanaan_umum">

            <label class="form-label fw-semibold">
                Mandiri
            </label>

            <div class="form-check mb-2">
                <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_1" id="tin_1">
                <label class="form-check-label" for="tin_1">
                    Ajarkan teknik relaksasi dan nafas dalam
                </label>
            </div>

            <div class="form-check mb-2">
                <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_2" id="tin_2">
                <label class="form-check-label" for="tin_2">
                    Pertahankan body alignment dan posisi yang nyaman
                </label>
            </div>

            <div class="form-check mb-2">
                <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_3" id="tin_3">
                <label class="form-check-label" for="tin_3">
                    Tenangkan Pasien
                </label>
            </div>

            <div class="form-check mb-2">
                <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_4" id="tin_4">
                <label class="form-check-label" for="tin_4">
                    Berikan Pendidikan Kesehatan ke pasien dan keluarga
                </label>
            </div>

        </div>


        {{-- //=====================================================
        //PSIKIATRI
        //===================================================== --}}
        <div class="col-md-6 border-end {{ $form === 'psikiatri' ? '' : 'd-none' }}" id="perencanaan_psikiatri">

            <label class="form-label fw-semibold">
                Mandiri
            </label>

            <div class="form-check mb-2">
                <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_jiwa_1" id="tin_jiwa_1">
                <label class="form-check-label" for="tin_jiwa_1">
                    Ajarkan teknik relaksasi
                </label>
            </div>

            <div class="form-check mb-2">
                <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_jiwa_2" id="tin_jiwa_2">
                <label class="form-check-label" for="tin_jiwa_2">
                    Bina Hubungan Saling Percaya
                </label>
            </div>

            <div class="form-check mb-2">
                <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_jiwa_3" id="tin_jiwa_3">
                <label class="form-check-label" for="tin_jiwa_3">
                    Diskusikan dengan pasien / keluarga
                </label>
            </div>

            <div class="form-check mb-2">
                <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_jiwa_4" id="tin_jiwa_4">
                <label class="form-check-label" for="tin_jiwa_4">
                    Latih Strategi Pelaksanaan
                </label>
            </div>

        </div>


        {{-- //=====================================================
        //KOLABORASI
        //Tetap muncul untuk umum maupun psikiatri
        //===================================================== --}}
        <div class="col-md-6">

            <label class="form-label fw-semibold">
                Kolaborasi
            </label>

            <div class="form-check mb-2">
                <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_5" id="tin_5">
                <label class="form-check-label" for="tin_5">
                    Rawat Luka
                </label>
            </div>

            <div class="row align-items-center mb-2">

                <label class="col-md-12 col-form-label mb-0">
                    Pemberian Terapi :
                </label>

                <div class="col-md-4">
                    <div class="form-check">

                        <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_6" id="tin_6">

                        <label class="form-check-label" for="tin_6">
                            Oral
                        </label>

                    </div>
                </div>

                <div class="col-md-8">
                    <input type="text" class="form-control form-control-sm" name="terapi_oral" id="terapi_oral">
                </div>

            </div>

            <div class="row align-items-center">

                <div class="col-md-4">
                    <div class="form-check">

                        <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_7" id="tin_7">

                        <label class="form-check-label" for="tin_7">
                            IV/SC/IM
                        </label>

                    </div>
                </div>

                <div class="col-md-8">
                    <input type="text" class="form-control form-control-sm" name="terapi_iv" id="terapi_iv">
                </div>

            </div>

        </div>

    </div>
</div>

<script>
(function () {
    'use strict';

    const $section = $(@json($section));
    const $form = $section.find('#form_perencanaan');

    const jenisForm = @json($form);

    let isPerencanaanLoading = false;
    let isPerencanaanSaving = false;


    // ==========================================================
    // SET CHECKBOX
    // ==========================================================
    function setCheckbox(name, value) {

        const $checkbox = $form.find(
            `input[type="checkbox"][name="${name}"]`
        );

        if (!$checkbox.length) {
            return;
        }

        $checkbox.prop(
            'checked',
            Number(value) === 1
        );
    }


    // ==========================================================
    // GET DATA
    // ==========================================================
    function getPerencanaan() {

        if (!$form.length) {
            console.warn(
                'Form Perencanaan Tindakan tidak ditemukan.'
            );
            return;
        }

        isPerencanaanLoading = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/rj/perencanaan/${kunjungan}`,
            type: 'GET',
            dataType: 'json',

            success: function (res) {

                const data = res.data;

                if (!data) {
                    return;
                }

                // ==================================================
                // PERENCANAAN UMUM
                // ==================================================
                if (jenisForm === 'umum') {

                    setCheckbox(
                        'tin_1',
                        data.TINDAKAN_RELAKSASI_NAFAS_DALAM
                    );

                    setCheckbox(
                        'tin_2',
                        data.TINDAKAN_BODY_ALIGNMENT
                    );

                    setCheckbox(
                        'tin_3',
                        data.TINDAKAN_TENANGKAN_PASIEN
                    );

                    setCheckbox(
                        'tin_4',
                        data.TINDAKAN_PENDIDIKAN_KESEHATAN
                    );
                }


                // ==================================================
                // PERENCANAAN PSIKIATRI
                // ==================================================
                if (jenisForm === 'psikiatri') {

                    setCheckbox(
                        'tin_jiwa_1',
                        data.JIWA_TINDAKAN_RELAKSASI
                    );

                    setCheckbox(
                        'tin_jiwa_2',
                        data.JIWA_TINDAKAN_BINA_HUBUNGAN_SALING_PERCAYA
                    );

                    setCheckbox(
                        'tin_jiwa_3',
                        data.JIWA_TINDAKAN_DISKUSI_PASIEN_KELUARGA
                    );

                    setCheckbox(
                        'tin_jiwa_4',
                        data.JIWA_TINDAKAN_STRATEGI_PELAKSANAAN
                    );
                }


                // ==================================================
                // KOLABORASI
                // ==================================================

                setCheckbox(
                    'tin_5',
                    data.TINDAKAN_RAWAT_LUKA
                );

                setCheckbox(
                    'tin_6',
                    data.TERAPI_ORAL
                );

                setCheckbox(
                    'tin_7',
                    data.TERAPI_IV_SC_IM
                );


                // Detail terapi
                $form.find('[name="terapi_oral"]').val(
                    data.TERAPI_ORAL_DETAIL ?? ''
                );

                $form.find('[name="terapi_iv"]').val(
                    data.TERAPI_IV_SC_IM_DETAIL ?? ''
                );
            },

            error: function (xhr, status, error) {

                console.error(
                    'Error Perencanaan Tindakan:',
                    xhr.responseText || error
                );

                let message =
                    'Gagal mengambil data Perencanaan Tindakan.';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                console.warn(message);
            },

            complete: function () {
                isPerencanaanLoading = false;
            }
        });
    }


    // ==========================================================
    // SIMPAN DATA
    // ==========================================================
    function simpanPerencanaan() {

        if (
            !$form.length ||
            isPerencanaanLoading ||
            isPerencanaanSaving
        ) {
            return;
        }

        const data = getFormDataByName($form, {
            NOKUNJ: kunjungan,
            FORM: jenisForm
        });

        isPerencanaanSaving = true;

        $.ajax({
            url: `/api/v2/emr/pengkajian/rj/perencanaan/${kunjungan}/simpan`,
            type: 'POST',
            data: data,

            headers: {
                'X-CSRF-TOKEN': $(
                    'meta[name="csrf-token"]'
                ).attr('content')
            },

            success: function (res) {

                console.log(
                    'Perencanaan berhasil disimpan.',
                    res
                );
            },

            error: function (xhr) {

                let message =
                    'Data Perencanaan Tindakan gagal disimpan.';

                if (
                    xhr.status === 422 &&
                    xhr.responseJSON?.errors
                ) {

                    message = Object
                        .values(xhr.responseJSON.errors)
                        .flat()
                        .join('<br>');

                } else if (xhr.responseJSON?.message) {

                    message = xhr.responseJSON.message;
                }

                iziToast.error({
                    title: 'Validasi Gagal!',
                    message: message,
                    position: 'topRight'
                });
            },

            complete: function () {
                isPerencanaanSaving = false;
            }
        });
    }


    // ==========================================================
    // EVENT
    // ==========================================================
    $(function () {

        if (!$form.length) {
            return;
        }

        getPerencanaan();

        $form.on(
            'blur',
            'textarea,input',
            function () {

                if (isPerencanaanLoading) {
                    return;
                }

                simpanPerencanaan();
            }
        );

        $form.on(
            'change',
            'select,input[type="checkbox"],input[type="radio"]',
            function () {

                if (isPerencanaanLoading) {
                    return;
                }

                simpanPerencanaan();
            }
        );

    });

})();
</script>
