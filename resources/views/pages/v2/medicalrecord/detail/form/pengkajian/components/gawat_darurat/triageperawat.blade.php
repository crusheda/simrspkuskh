<div id="form_gd_perawat_triage">
    <div class="row">

        <div class="col-md-12">
            <h6 class="mb-2 flex-shrink-0">Cara Kedatangan</h6>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="form-check mb-0 flex-shrink-0">
                            <input class="form-check-input check-primary single-checkbox" type="checkbox" name="dd_ck" value="1">
                            <label class="form-check-label ms-1">
                                Datang sendiri, diantar oleh
                            </label>
                        </div>
                        <input type="text" class="form-control form-control-sm" name="dd_ck_p" placeholder="Masukkan Nama Pengantar" disabled>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="form-check mb-0 flex-shrink-0">
                            <input class="form-check-input check-primary single-checkbox" type="checkbox" name="dd_ck" value="2">
                            <label class="form-check-label ms-1">
                                Rujukan dari
                            </label>
                        </div>
                        <input type="text" class="form-control form-control-sm" name="dd_ck_k" id="dd_ck_k" placeholder="Tuliskan Asal Rujukan" disabled>
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="form-check mb-0 flex-shrink-0">
                            <input class="form-check-input check-primary single-checkbox" type="checkbox" name="dd_ck" value="3">
                            <label class="form-check-label ms-1">
                                Dikirim oleh Polisi dari
                            </label>
                        </div>
                        <input type="text" class="form-control form-control-sm" name="dd_ck_a" placeholder="Masukkan Unit Kepolisian" disabled>
                        <div class="form-check mb-0 flex-shrink-0">
                            <input class="form-check-input check-primary" type="checkbox" name="dd_ck_a_v" >
                            <label class="form-check-label ms-1">
                                Disertai permintaan visum et repertum
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="mb-0 flex-shrink-0">
                            <label class="form-label">Tgl. Kedatangan</label>
                        </div>
                        <input type="datetime-local" class="form-control form-control-sm" name="tgl_ck" value="{{ $list['pasien']->TGL_KEDATANGAN }}">
                    </div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="mb-0 flex-shrink-0">
                            <label class="form-label">Alat Transportasi</label>
                        </div>
                        <input type="text" class="form-control form-control-sm" name="tr_ck">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-3">
            <h6>Jenis Kasus</h6>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="jks" value="1">
                                <label class="form-check-label fw-bold"> Trauma </label>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary" type="checkbox" name="jks_kll">
                                <label class="form-check-label"> Kecelakaan Lalu Lintas </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary" type="checkbox" name="jks_kk">
                                <label class="form-check-label"> Kecelakaan Kerja </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input check-primary" type="checkbox" name="jks_uppa">
                                <label class="form-check-label"> Kasus Perempuan & Anak (UPPA) </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="jks" value="0">
                                <label class="form-check-label fw-bold"> Non Trauma </label>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="d-flex align-items-center gap-2">
                                <div class="form-check mb-0 flex-shrink-0">
                                    <input class="form-check-input check-primary" type="checkbox" name="jks_end">
                                    <label class="form-check-label"> Riwayat ke Daerah Endemis </label>
                                </div>
                                <input type="text" class="form-control form-control-sm flex-grow-1" name="jks_end_dm" placeholder="Dimana ?" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-3">
            <div class="form-group">
                <h6>Resiko penularan infeksi</h6>
                <div class="row">
                    <div class="col">
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary single-checkbox" type="checkbox" name="rpi" value="1">
                            <label class="form-check-label"> Batuk > 2 minggu dengan demam dan sesak nafas </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary single-checkbox" type="checkbox" name="rpi" value="2">
                            <label class="form-check-label"> Rujukan dengan suspek (konfirmasi) airbone disease </label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-check mb-2">
                            <input class="form-check-input check-primary single-checkbox" type="checkbox" name="rpi" value="3">
                            <label class="form-check-label"> Tidak berisiko penularan airbone disease </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input check-primary single-checkbox" type="checkbox" name="rpi" value="4">
                            <label class="form-check-label"> B - 20 </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-3">
            <h6>Anamnesis</h6>
            <div class="row">
                <div class="col">
                    <div class="form-group mb-2">
                        <label class="form-label"> Keluhan Utama </label>
                        <input class="form-control" type="text" name="anm_ku">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label class="form-label"> Terpimpin </label>
                        <input class="form-control" type="text" name="anm_tp">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            @include(
                'pages.v2.medicalrecord.detail.form.pengkajian.components.gawat_darurat.primary_survey',
                [
                    'section' => '#gd_perawat',
                    'page' => 'perawat',
                    'kunjungan' => $kunjungan ?? $list['kunjungan'],
                ]
            )
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        const $section = $('#gd_perawat');
        const $form = $section.find('#form_gd_perawat_triage').first();

        const kunjungan = @json($kunjungan ?? $list['kunjungan']);

        let isDataLoading = false;
        let isDataSaving = false;


        /* =========================================================
         * ADDITIONAL INPUT TRIASE PERAWAT
         *
         * Mengatur input tambahan berdasarkan checkbox induknya.
         *
         * Checkbox tidak dicentang
         * -> input tambahan disabled
         *
         * Checkbox dicentang
         * -> input tambahan enabled
         * ========================================================= */
        function AdditionalInputTriagePerawat() {

            if (!$form.length) {
                return;
            }

            /*
             * dd_ck
             *
             * 1 = Datang sendiri, diantar oleh
             *     -> dd_ck_p
             *
             * 2 = Rujukan dari
             *     -> dd_ck_k
             *
             * 3 = Dikirim oleh Polisi dari
             *     -> dd_ck_a
             */
            const additionalInputDdCk = {
                '1': ['dd_ck_p'],
                '2': ['dd_ck_k'],
                '3': ['dd_ck_a']
            };

            $form.find('input[name="dd_ck"]').each(function () {

                const $checkbox = $(this);
                const value = String($checkbox.val());

                const inputNames = additionalInputDdCk[value] ?? [];

                inputNames.forEach(function (inputName) {

                    const $input = $form.find(
                        `[name="${inputName}"]`
                    );

                    if (!$input.length) {
                        return;
                    }

                    $input.prop(
                        'disabled',
                        !$checkbox.prop('checked')
                    );
                });
            });


            /*
             * jks_end
             *
             * Riwayat ke Daerah Endemis
             * -> jks_end_dm
             */
            const $jksEnd = $form.find(
                'input[name="jks_end"]'
            );

            const $jksEndDm = $form.find(
                'input[name="jks_end_dm"]'
            );

            if ($jksEnd.length && $jksEndDm.length) {

                $jksEndDm.prop(
                    'disabled',
                    !$jksEnd.prop('checked')
                );
            }
        }


        /* =========================================================
         * GET DATA TRIASE PERAWAT
         * ========================================================= */
        function getDataTriagePerawat() {

            if (!$form.length) {
                return;
            }

            isDataLoading = true;

            $.ajax({
                url: `/api/v2/emr/pengkajian/gd/tp/${kunjungan}`,
                type: 'GET',
                dataType: 'json',

                success: function (response) {

                    const triage = response?.data?.triage ?? null;

                    if (!triage) {
                        AdditionalInputTriagePerawat();
                        return;
                    }


                    /*
                    * =====================================================
                    * PARSE JSON DARI DATABASE
                    * =====================================================
                    */
                    let kedatangan = {};
                    let kasus = {};
                    let anamnesis = {};
                    let tandaVital = {};

                    try {
                        kedatangan = triage.KEDATANGAN
                            ? JSON.parse(triage.KEDATANGAN)
                            : {};
                    } catch (e) {
                        console.error('Gagal parse KEDATANGAN:', e);
                    }

                    try {
                        kasus = triage.KASUS
                            ? JSON.parse(triage.KASUS)
                            : {};
                    } catch (e) {
                        console.error('Gagal parse KASUS:', e);
                    }

                    try {
                        anamnesis = triage.ANAMNESE
                            ? JSON.parse(triage.ANAMNESE)
                            : {};
                    } catch (e) {
                        console.error('Gagal parse ANAMNESE:', e);
                    }

                    try {
                        tandaVital = triage.TANDA_VITAL
                            ? JSON.parse(triage.TANDA_VITAL)
                            : {};
                    } catch (e) {
                        console.error('Gagal parse TANDA_VITAL:', e);
                    }

                    /*
                    * =====================================================
                    * SINGLE CHECKBOX
                    *
                    * Checkbox yang mempunyai beberapa pilihan dengan
                    * name yang sama.
                    * =====================================================
                    */

                    // Cara Kedatangan
                    FormHelper.setSingleCheckbox(
                        $form,
                        'dd_ck',
                        kedatangan.JENIS
                    );


                    // Jenis Kasus
                    FormHelper.setSingleCheckbox(
                        $form,
                        'jks',
                        kasus.JENIS
                    );


                    // Risiko Penularan Infeksi
                    FormHelper.setSingleCheckbox(
                        $form,
                        'rpi',
                        triage.RISIKO_PENULARAN_INFEKSI
                    );


                    /*
                    * =====================================================
                    * CHECKBOX BIASA
                    * =====================================================
                    */

                    // Disertai permintaan visum
                    FormHelper.setCheckbox(
                        $form,
                        'dd_ck_a_v',
                        kedatangan.VISUM
                    );


                    // Kecelakaan Lalu Lintas
                    FormHelper.setCheckbox(
                        $form,
                        'jks_kll',
                        kasus.LAKA_LANTAS
                    );


                    // Kecelakaan Kerja
                    FormHelper.setCheckbox(
                        $form,
                        'jks_kk',
                        kasus.KECELAKAAN_KERJA
                    );


                    // Kasus Perempuan & Anak
                    FormHelper.setCheckbox(
                        $form,
                        'jks_uppa',
                        kasus.UPPA
                    );


                    /*
                    * Riwayat ke Daerah Endemis
                    *
                    * Status checkbox ditentukan dari ada/tidaknya
                    * DIMANA.
                    */
                    const isEndemis = (
                        kasus.DIMANA !== null &&
                        kasus.DIMANA !== undefined &&
                        kasus.DIMANA !== ''
                    );

                    FormHelper.setCheckbox(
                        $form,
                        'jks_end',
                        isEndemis
                    );


                    /*
                    * =====================================================
                    * INPUT BIASA
                    * =====================================================
                    */

                    const data = {

                        /* =========================
                        * CARA KEDATANGAN
                        * ========================= */
                        dd_ck_p: kedatangan.PENGANTAR,
                        dd_ck_k: kedatangan.ASAL_RUJUKAN,
                        dd_ck_a: kedatangan.KEPOLISIAN,
                        tgl_ck: kedatangan.TANGGAL,
                        tr_ck: kedatangan.ALAT_TRANSPORTASI,


                        /* =========================
                        * JENIS KASUS
                        * ========================= */
                        jks_end_dm: kasus.DIMANA,


                        /* =========================
                        * ANAMNESIS
                        * ========================= */
                        anm_ku: anamnesis.KELUHAN_UTAMA,
                        anm_tp: anamnesis.TERPIMPIN,

                    };


                    /*
                    * =====================================================
                    * SET VALUE INPUT
                    * =====================================================
                    */
                    Object.keys(data).forEach(function (name) {

                        const value = data[name];

                        /*
                        * Jangan skip angka 0.
                        *
                        * Tetapi null / undefined tidak perlu di-set.
                        */
                        if (
                            value === null ||
                            value === undefined
                        ) {
                            return;
                        }

                        FormHelper.setValue(
                            $form,
                            name,
                            value
                        );
                    });


                    /*
                    * =====================================================
                    * UPDATE ADDITIONAL INPUT
                    *
                    * Harus dilakukan SETELAH checkbox selesai di-set.
                    * =====================================================
                    */
                    AdditionalInputTriagePerawat();
                },

                error: function (xhr) {

                    console.error(
                        'Gagal mengambil data triage perawat:',
                        xhr.responseText
                    );
                },

                complete: function () {

                    isDataLoading = false;
                }
            });
        }


        /* =========================================================
         * SIMPAN DATA
         * ========================================================= */
        function simpanDataTriagePerawat() {

            if (!$form.length) {
                return;
            }

            /*
             * Jangan simpan ketika sedang proses GET.
             */
            if (isDataLoading) {
                return;
            }

            /*
             * Jangan membuat request POST baru jika
             * request sebelumnya masih berjalan.
             */
            if (isDataSaving) {
                return;
            }

            const data = getFormDataByName(
                $form,
                {
                    NOKUNJ: kunjungan
                }
            );

            isDataSaving = true;

            $.ajax({
                url: `/api/v2/emr/pengkajian/gd/tp/${kunjungan}/simpan`,
                type: 'POST',
                data: data,

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                success: function (response) {

                    console.log(
                        'Data triage perawat berhasil disimpan.'
                    );
                },

                error: function (xhr) {

                    console.error(
                        'Gagal menyimpan triage perawat:',
                        xhr.responseText
                    );
                },

                complete: function () {

                    isDataSaving = false;
                }
            });
        }


        /* =========================================================
         * CHECKBOX TAMBAHAN
         *
         * Ketika checkbox berubah:
         *
         * 1. Update disabled/enabled input
         * 2. Autosave
         * ========================================================= */
        $form.on(
            'change',
            'input[name="dd_ck"], input[name="jks_end"]',
            function () {

                if (isDataLoading) {
                    return;
                }

                /*
                 * Update status input tambahan terlebih dahulu.
                 */
                AdditionalInputTriagePerawat();

                /*
                 * Kemudian simpan.
                 */
                simpanDataTriagePerawat();
            }
        );


        /* =========================================================
         * AUTO SAVE INPUT
         *
         * Text / Number / Datetime / Textarea
         * disimpan ketika field kehilangan fokus.
         * ========================================================= */
        $form.on(
            'blur',
            'input[type="text"], input[type="number"], input[type="datetime-local"], textarea',
            function () {

                if (isDataLoading) {
                    return;
                }

                simpanDataTriagePerawat();
            }
        );


        /* =========================================================
         * AUTO SAVE CHECKBOX / RADIO / SELECT
         *
         * Selain dd_ck dan jks_end, checkbox/radio/select lainnya
         * tetap menggunakan mekanisme autosave sebelumnya.
         * ========================================================= */
        $form.on(
            'change',
            'input[type="checkbox"], input[type="radio"], select',
            function () {

                if (isDataLoading) {
                    return;
                }

                /*
                 * Untuk checkbox tambahan, status input sudah
                 * diperbarui pada handler khusus di atas.
                 *
                 * Untuk checkbox lainnya, function ini tidak
                 * mengubah apa pun.
                 */
                simpanDataTriagePerawat();
            }
        );


        /* =========================================================
         * LOAD DATA PERTAMA KALI
         * ========================================================= */
        getDataTriagePerawat();

    });
</script>
