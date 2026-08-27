<div class="col-md-12 mb-1" id="form_pemeriksaan_fisik_neonatus">

    <h6 class="fw-bold text-dark mb-2">
        PEMERIKSAAN FISIK NEONATUS
    </h6>

    {{-- ========================================================== --}}
    {{-- 1. KEPALA --}}
    {{-- ========================================================== --}}

    <div class="row mb-3">

        <div class="col-md-12">
            <h6>Kepala</h6>
        </div>

        {{-- Bentuk --}}
        <div class="col-md-6 mb-2">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <label class="form-label mb-0">Bentuk</label>
                </div>
                <div class="col-md-8">
                    <input type="text"
                        class="form-control form-control-sm"
                        name="bentuk">
                </div>
            </div>
        </div>

        {{-- Suturae --}}
        <div class="col-md-6 mb-2">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <label class="form-label mb-0">Suturae</label>
                </div>
                <div class="col-md-8">
                    <input type="text"
                        class="form-control form-control-sm"
                        name="suturae">
                </div>
            </div>
        </div>

        {{-- Fontanella --}}
        <div class="col-md-6 mb-2">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <label class="form-label mb-0">Fontanella</label>
                </div>
                <div class="col-md-8">
                    <input type="text"
                        class="form-control form-control-sm"
                        name="fontanella">
                </div>
            </div>
        </div>

        {{-- Mata --}}
        <div class="col-md-6 mb-2">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <label class="form-label mb-0">Mata</label>
                </div>
                <div class="col-md-8">
                    <input type="text"
                        class="form-control form-control-sm"
                        name="mata">
                </div>
            </div>
        </div>

        {{-- Hidung --}}
        <div class="col-md-6 mb-2">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <label class="form-label mb-0">Hidung</label>
                </div>
                <div class="col-md-8">
                    <input type="text"
                        class="form-control form-control-sm"
                        name="hidung">
                </div>
            </div>
        </div>

        {{-- Caput Succedaneum --}}
        <div class="col-md-6 mb-2">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <label class="form-label mb-0">Caput Succedaneum</label>
                </div>
                <div class="col-md-8">
                    <input type="text"
                        class="form-control form-control-sm"
                        name="caput_succedaneum">
                </div>
            </div>
        </div>

        {{-- Chepal Hematom --}}
        <div class="col-md-6 mb-2">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <label class="form-label mb-0">Chepal Hematom</label>
                </div>
                <div class="col-md-8">
                    <input type="text"
                        class="form-control form-control-sm"
                        name="chepal_hematom">
                </div>
            </div>
        </div>

        {{-- Telinga --}}
        <div class="col-md-6 mb-2">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <label class="form-label mb-0">Telinga</label>
                </div>
                <div class="col-md-8">
                    <input type="text"
                        class="form-control form-control-sm"
                        name="telinga">
                </div>
            </div>
        </div>

        {{-- Mulut --}}
        <div class="col-md-6 mb-2">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <label class="form-label mb-0">Mulut</label>
                </div>
                <div class="col-md-8">
                    <input type="text"
                        class="form-control form-control-sm"
                        name="mulut">
                </div>
            </div>
        </div>

        {{-- Leher --}}
        <div class="col-md-6 mb-2">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <label class="form-label mb-0">Leher</label>
                </div>
                <div class="col-md-8">
                    <input type="text"
                        class="form-control form-control-sm"
                        name="leher">
                </div>
            </div>
        </div>

    </div>

    <div class="row mb-3">

        <div class="col-md-6">
            <h6>Paru</h6>
            <input type="text"
                class="form-control form-control-sm"
                name="paru">
        </div>

        <div class="col-md-6">
            <h6>Jantung</h6>
            <input type="text"
                class="form-control form-control-sm"
                name="jantung">
        </div>

    </div>

    <div class="row mb-3">

        <div class="col-md-6">
            <h6>Abdomen</h6>
            <input type="text"
                class="form-control form-control-sm"
                name="abdomen">
        </div>

        <div class="col-md-6">
            <h6>Ekstremitas</h6>
            <input type="text"
                class="form-control form-control-sm"
                name="ekstremitas">
        </div>

    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            <h6>Neurologi</h6>

            <div class="d-flex align-items-center flex-wrap gap-3">

                {{-- Rooting --}}
                <div class="form-check m-0">
                    <input class="form-check-input"
                        type="checkbox"
                        name="rooting"
                        value="1"
                        id="rooting">

                    <label class="form-check-label"
                        for="rooting">
                        Rooting
                    </label>
                </div>

                {{-- Sucking --}}
                <div class="form-check m-0">
                    <input class="form-check-input"
                        type="checkbox"
                        name="sucking"
                        value="1"
                        id="sucking">

                    <label class="form-check-label"
                        for="sucking">
                        Sucking (Menghisap)
                    </label>
                </div>

                {{-- Moro --}}
                <div class="form-check m-0">
                    <input class="form-check-input"
                        type="checkbox"
                        name="moro"
                        value="1"
                        id="moro">

                    <label class="form-check-label"
                        for="moro">
                        Moro
                    </label>
                </div>

                {{-- Asymmetric Tonic Neck --}}
                <div class="form-check m-0">
                    <input class="form-check-input"
                        type="checkbox"
                        name="asymmetric_tonic_neck"
                        value="1"
                        id="asymmetric_tonic_neck">

                    <label class="form-check-label"
                        for="asymmetric_tonic_neck">
                        Asymmetric Tonic Neck
                    </label>
                </div>

                {{-- Babinski --}}
                <div class="form-check m-0">
                    <input class="form-check-input"
                        type="checkbox"
                        name="babinski"
                        value="1"
                        id="babinski">

                    <label class="form-check-label"
                        for="babinski">
                        Babinski
                    </label>
                </div>

                {{-- Menggenggam --}}
                <div class="form-check m-0">
                    <input class="form-check-input"
                        type="checkbox"
                        name="menggenggam"
                        value="1"
                        id="menggenggam">

                    <label class="form-check-label"
                        for="menggenggam">
                        Menggenggam
                    </label>
                </div>

            </div>

        </div>

    </div>

    <div class="row mb-3">
        <div class="col">
            <h6>Suara</h6>
            <div class="d-flex align-items-center flex-wrap gap-3">

                {{-- Diam --}}
                <div class="form-check m-0">
                    <input class="form-check-input"
                        type="checkbox"
                        name="suara_diam"
                        value="1"
                        id="suara_diam">

                    <label class="form-check-label"
                        for="suara_diam">
                        Diam
                    </label>
                </div>

                {{-- Merintih --}}
                <div class="form-check m-0">
                    <input class="form-check-input"
                        type="checkbox"
                        name="suara_merintih"
                        value="1"
                        id="suara_merintih">

                    <label class="form-check-label"
                        for="suara_merintih">
                        Merintih
                    </label>
                </div>

                {{-- Kuat --}}
                <div class="form-check m-0">
                    <input class="form-check-input"
                        type="checkbox"
                        name="suara_kuat"
                        value="1"
                        id="suara_kuat">

                    <label class="form-check-label"
                        for="suara_kuat">
                        Kuat
                    </label>
                </div>

            </div>

        </div>
        <div class="col">
            <h6>Kulit</h6>
            <div class="d-flex align-items-center gap-3">

                {{-- Ikrenik --}}
                <div class="form-check m-0">
                    <input class="form-check-input"
                        type="checkbox"
                        name="kulit_ikrenik"
                        value="1"
                        id="kulit_ikrenik">

                    <label class="form-check-label"
                        for="kulit_ikrenik">
                        Ikrenik
                    </label>
                </div>

                {{-- Keterangan --}}
                <input type="text"
                    class="form-control form-control-sm"
                    name="kulit_keterangan"
                    placeholder="Keterangan...">

            </div>

        </div>
    </div>

</div>

<script>
(function () {

    'use strict';


    // ==========================================================
    // STATE
    // ==========================================================

    let isPemeriksaanFisikNeonatusLoading = false;


    // ==========================================================
    // ELEMENT
    // ==========================================================

    const $form =
        $('#form_pemeriksaan_fisik_neonatus');


    // ==========================================================
    // GET DATA
    // ==========================================================

    function getPemeriksaanFisikNeonatus() {

        isPemeriksaanFisikNeonatusLoading = true;


        $.ajax({

            url:
                `/api/v2/emr/pengkajian/pemeriksaan_fisik_neonatus/${kunjungan}`,

            type: 'GET',

            dataType: 'json',


            success: function (res) {

                console.log(
                    'GET Pemeriksaan Fisik Neonatus:',
                    res
                );


                /*
                 * Bisa menerima:
                 *
                 * {
                 *     success: true,
                 *     data: {...}
                 * }
                 *
                 * atau langsung:
                 *
                 * {...}
                 */

                const data =
                    res.data || res;


                if (!data) {

                    isPemeriksaanFisikNeonatusLoading = false;

                    return;

                }


                // ==================================================
                // KEPALA
                // ==================================================

                $form.find(
                    '[name="bentuk"]'
                ).val(
                    data.BENTUK ?? ''
                );


                $form.find(
                    '[name="suturae"]'
                ).val(
                    data.SUTURAE ?? ''
                );


                $form.find(
                    '[name="fontanella"]'
                ).val(
                    data.FONTANELLA ?? ''
                );


                $form.find(
                    '[name="mata"]'
                ).val(
                    data.MATA ?? ''
                );


                $form.find(
                    '[name="hidung"]'
                ).val(
                    data.HIDUNG ?? ''
                );


                $form.find(
                    '[name="caput_succedaneum"]'
                ).val(
                    data.CAPUT_SUCCEDANEUM ?? ''
                );


                $form.find(
                    '[name="chepal_hematom"]'
                ).val(
                    data.CHEPAL_HEMATOM ?? ''
                );


                $form.find(
                    '[name="telinga"]'
                ).val(
                    data.TELINGA ?? ''
                );


                $form.find(
                    '[name="mulut"]'
                ).val(
                    data.MULUT ?? ''
                );


                $form.find(
                    '[name="leher"]'
                ).val(
                    data.LEHER ?? ''
                );


                // ==================================================
                // PARU
                // ==================================================

                $form.find(
                    '[name="paru"]'
                ).val(
                    data.PARU ?? ''
                );


                // ==================================================
                // JANTUNG
                // ==================================================

                $form.find(
                    '[name="jantung"]'
                ).val(
                    data.JANTUNG ?? ''
                );


                // ==================================================
                // ABDOMEN
                // ==================================================

                $form.find(
                    '[name="abdomen"]'
                ).val(
                    data.ABDOMEN ?? ''
                );


                // ==================================================
                // EKSTREMITAS
                // ==================================================

                $form.find(
                    '[name="ekstremitas"]'
                ).val(
                    data.EKSTREMITAS ?? ''
                );


                // ==================================================
                // NEUROLOGI
                // ==================================================

                $form.find(
                    '[name="rooting"]'
                ).prop(
                    'checked',
                    Number(data.ROOTING) === 1
                );


                $form.find(
                    '[name="sucking"]'
                ).prop(
                    'checked',
                    Number(data.SUCKING) === 1
                );


                $form.find(
                    '[name="moro"]'
                ).prop(
                    'checked',
                    Number(data.MORO) === 1
                );


                $form.find(
                    '[name="asymmetric_tonic_neck"]'
                ).prop(
                    'checked',
                    Number(data.ASYMMETRIC_TONIC_NECK) === 1
                );


                $form.find(
                    '[name="babinski"]'
                ).prop(
                    'checked',
                    Number(data.BABINSKI) === 1
                );


                $form.find(
                    '[name="menggenggam"]'
                ).prop(
                    'checked',
                    Number(data.MENGGENGGAM) === 1
                );


                // ==================================================
                // SUARA
                // ==================================================

                $form.find(
                    '[name="suara_diam"]'
                ).prop(
                    'checked',
                    Number(data.SUARA_DIAM) === 1
                );


                $form.find(
                    '[name="suara_merintih"]'
                ).prop(
                    'checked',
                    Number(data.SUARA_MERINTIH) === 1
                );


                $form.find(
                    '[name="suara_kuat"]'
                ).prop(
                    'checked',
                    Number(data.SUARA_KUAT) === 1
                );


                // ==================================================
                // KULIT
                // ==================================================

                $form.find(
                    '[name="kulit_ikrenik"]'
                ).prop(
                    'checked',
                    Number(data.IKRENIK) === 1
                );


                $form.find(
                    '[name="kulit_keterangan"]'
                ).val(
                    data.KULIT_KETERANGAN ?? ''
                );


                console.log(
                    'Pemeriksaan Fisik Neonatus berhasil dimuat.'
                );


                // GET selesai
                isPemeriksaanFisikNeonatusLoading = false;

            },


            error: function (xhr) {

                console.error(
                    'Error GET Pemeriksaan Fisik Neonatus:',
                    xhr.responseText || xhr.statusText
                );


                isPemeriksaanFisikNeonatusLoading = false;

            }

        });

    }


    // ==========================================================
    // SIMPAN
    // ==========================================================

    function simpanPemeriksaanFisikNeonatus() {

        // Jangan POST ketika GET masih berjalan
        if (
            isPemeriksaanFisikNeonatusLoading
        ) {
            return;
        }


        if (!$form.length) {
            return;
        }


        const data =
            getFormDataByName(
                $form,
                {
                    NOKUNJ: kunjungan
                }
            );


        console.log(
            'Data Pemeriksaan Fisik Neonatus:',
            data
        );


        $.ajax({

            url:
                `/api/v2/emr/pengkajian/pemeriksaan_fisik_neonatus/${kunjungan}/simpan`,

            type: 'POST',

            data: data,

            headers: {

                'X-CSRF-TOKEN':
                    $('meta[name="csrf-token"]').attr('content')

            },


            success: function (res) {

                console.log(
                    'Pemeriksaan Fisik Neonatus berhasil disimpan.',
                    res
                );

            },


            error: function (xhr) {

                console.error(
                    'Gagal simpan Pemeriksaan Fisik Neonatus:',
                    xhr.responseText
                );


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
                        .join('<br>');

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


    // ==========================================================
    // DOCUMENT READY
    // ==========================================================

    $(function () {


        // ======================================================
        // CEK FORM
        // ======================================================

        if (!$form.length) {

            console.warn(
                '#form_pemeriksaan_fisik_neonatus tidak ditemukan.'
            );

            return;

        }


        // ======================================================
        // GET DATA
        // ======================================================

        getPemeriksaanFisikNeonatus();


        // ======================================================
        // INPUT TEXT
        // SAVE SAAT BLUR
        // ======================================================

        $form.on(

            'blur',

            'input[type="text"], ' +
            'input[type="number"], ' +
            'input[type="date"], ' +
            'input[type="time"], ' +
            'textarea',

            function () {

                if (
                    isPemeriksaanFisikNeonatusLoading
                ) {
                    return;
                }


                simpanPemeriksaanFisikNeonatus();

            }

        );


        // ======================================================
        // CHECKBOX
        // SAVE SAAT CHANGE
        // ======================================================

        $form.on(

            'change',

            'input[type="checkbox"]',

            function (e) {

                if (
                    isPemeriksaanFisikNeonatusLoading
                ) {
                    return;
                }


                simpanPemeriksaanFisikNeonatus();

            }

        );

    });

})();
</script>