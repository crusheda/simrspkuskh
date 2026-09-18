<div class="form-wrapper">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN KEPERAWATAN <b class="">RAWAT JALAN</b> <b class="text-warning">JIWA</b></center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>(<a class="text-success">Diisi Oleh Perawat</a>)</center></h1>
    <div class="form-content">
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <label class="form-label fw-bold"> Keluhan Utama </label>
                    <input class="form-control form-control" type="text" name="anm_ku" id="anm_ku">
                </div>
            </div>
            <div class="col-md-12 mb-2">
                @include(
                    'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.tanda_vital',
                    [
                        'section' => '#rjj_perawat',
                        'page' => 'perawat',
                        // 'editableFields' => [
                        //     'tv_keu',
                        //     'tv_gcs_e',
                        //     'tv_gcs_v',
                        //     'tv_gcs_m',
                        //     'tv_bb',
                        //     'tv_tb',
                        // ],
                    ]
                )
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.anamnesis_hubungan_status_psikososial',['section' => '#rjj_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group mb-2">
                    <h5 class="mb-0 text-success">
                        <strong>SKRINING NYERI</strong>
                    </h5>
                </div>
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_nyeri',
                    [
                        'section' => '#rio_perawat',
                        'metodeNyeri' => ['nrs', 'vas', 'bps']
                    ]
                )
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group mb-2">
                    <h5 class="mb-0 text-success">
                        <strong>SKRINING RESIKO JATUH</strong>
                    </h5>
                </div>
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_resiko_jatuh_gtg', ['section' => '#rjj_perawat'])
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_resiko_jatuh_epfra', ['section' => '#rjj_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group mb-2">
                    <h5 class="mb-0 text-success">
                        <strong>SKRINING GIZI</strong>
                    </h5>
                </div>
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_gizi_must', ['section' => '#rjj_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.kebutuhan_edukasi',['section' => '#rjj_perawat'])
            </div>
            <div class="col-md-12 mb-1">
                <div class="card card-body border border-dashed border-primary">
                    <label class="form-label fw-bold">
                        Masalah Keperawatan
                    </label>
                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-4">
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_jiwa_1" id="diag_jiwa_1">

                                <label class="form-check-label" for="diag_jiwa_1">
                                    Ansietas
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_jiwa_2" id="diag_jiwa_2">
                                <label class="form-check-label" for="diag_jiwa_2">
                                    Defisit Pengetahuan
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_jiwa_3" id="diag_jiwa_3">
                                <label class="form-check-label" for="diag_jiwa_3">
                                    Risiko Perilaku Kekerasan
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_jiwa_4" id="diag_jiwa_4">
                                <label class="form-check-label" for="diag_jiwa_4">
                                    Defisit Perawatan Diri
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_jiwa_5" id="diag_jiwa_5">
                                <label class="form-check-label" for="diag_jiwa_5">
                                    Harga Diri Rendah
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_jiwa_6" id="diag_jiwa_6">
                                <label class="form-check-label" for="diag_jiwa_6">
                                    Isolasi Sosial
                                </label>
                            </div>
                        </div>
                        <!-- Kolom Tengah -->
                        <div class="col-md-4">
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_jiwa_7" id="diag_jiwa_7">
                                <label class="form-check-label" for="diag_jiwa_7">
                                    Keputusasaan
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_jiwa_8" id="diag_jiwa_8">
                                <label class="form-check-label" for="diag_jiwa_8">
                                    Koping Tidak Efektif
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_jiwa_9" id="diag_jiwa_9">
                                <label class="form-check-label" for="diag_jiwa_9">
                                    Waham
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_jiwa_10" id="diag_jiwa_10">
                                <label class="form-check-label" for="diag_jiwa_10">
                                    Perilaku Kekerasan
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_jiwa_11" id="diag_jiwa_11">
                                <label class="form-check-label" for="diag_jiwa_11">
                                    Gangguan Persepsi Sensori
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <label class="form-input-label">Diagnosa Keperawatan Lainnya</label>
                                <input type="text" class="form-control" id="diag_lain" name="diag_lain" placeholder="Lainnya">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-1">
                <div class="card card-body border border-dashed border-primary">
                    <div class="row">
                        <label class="form-label fw-bold">
                            Perencanaan dan Tindakan
                        </label>
                        <div class="col-md-6 border-end">
                            <label class="form-label fw-semibold">
                                Mandiri
                            </label>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_jiwa_1" id="tin_jiwa_1" checked>
                                <label class="form-check-label" for="tin_jiwa_1">
                                    Ajarkan teknik relaksasi
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_jiwa_2" id="tin_jiwa_2" checked>
                                <label class="form-check-label" for="tin_jiwa_2">
                                    Bina Hubungan Saling Percaya
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_jiwa_3" id="tin_jiwa_3" checked>
                                <label class="form-check-label" for="tin_jiwa_3">
                                    Diskusikan dengan pasien / keluarga
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_jiwa_4" id="tin_jiwa_4" checked>
                                <label class="form-check-label" for="tin_jiwa_4">
                                    Latih Strategi Pelaksanaan
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row align-items-center mb-2">
                                <label class="col-md-12 col-form-label mb-0">Pemberian Terapi :</label>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_6" id="tin_6" checked>
                                        <label class="form-check-label">Oral</label>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="terapi_oral" id="terapi_oral">
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_7" id="tin_7">
                                        <label class="form-check-label">IV/SC/IM</label>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="terapi_iv" id="terapi_iv">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-footer">
        <button class="btn btn-secondary">
            <i class="ri-close-line me-1"></i> Batal
        </button>
        <button class="btn btn-success" onclick="saveDataPengkajianRJJp(this)">
            <i class="ri-save-line me-1"></i> Simpan Pengkajian
        </button>
    </div>
</div>

<script>
    $(document).ready(function () {
        const $section = $('#rjj_perawat');
        // Sembunyikan textarea saat pertama kali
        $('#terapi_oral').hide();
        $('#terapi_iv').hide();

        //Terapi Oral
        $('#tin_6').change(function () {
            if ($(this).is(':checked')) {
                $('#terapi_oral').show();
            } else {
                $('#terapi_oral').hide();
                $('#terapi_oral').val('');
            }
        });

        //Terapi Iv
        $('#tin_7').change(function () {
            if ($(this).is(':checked')) {
                $('#terapi_iv').show();
            } else {
                $('#terapi_iv').hide();
                $('#terapi_iv').val('');
            }
        });

        loadDataPengkajianRJJp();

    });

    function loadDataPengkajianRJJp() {
        const kunjungan = $('#rjj_perawat').data('kunjungan');

        $.ajax({
            url: `/api/v2/emr/form/pengkajian/rjj/pr/get/${kunjungan}`,
            type: 'GET',
            success:function(res){
                isiFormPengkajianRJJp(res);
            }
        });
    }

    function setValIfExists(selector, value) {
        if (value !== null && value !== undefined && value !== '') {
            $(selector).val(value);
        }
    }

    function setNumberIfExists(selector, value) {
        if (value !== null && value !== undefined && value !== '') {
            $(selector).val(Number(value).toLocaleString('id-ID', {
                maximumFractionDigits: 0
            }));
        }
    }

    function setSuhuIfExists(selector, value) {
        if (value !== null && value !== undefined && value !== '') {
            $(selector).val(Number(value).toLocaleString('id-ID', {
                maximumFractionDigits: 2
            }));
        }
    }

    function setCheckedIfExists(selector, value) {
        if (value !== null && value !== undefined && value !== '') {
            $(selector)
                .prop('checked', Number(value) === 1)
                .trigger('change');
        }
    }

    function setRadioIfExists(name, value) {
        if (value !== null && value !== undefined && value !== '') {
            $('input[name="' + name + '"][value="' + value + '"]')
                .prop('checked', true)
                .trigger('change');
        }
    }

    function isiFormPengkajianRJJp(data){

        // ======================================================
        // TANDA VITAL
        // ======================================================

        setValIfExists('#anm_ku', data.anm_ku);

        // Masalah Keperawatan Jiwa
        for (let i = 1; i <= 11; i++) {
            $('#diag_jiwa_' + i).prop(
                'checked',
                data['diag_jiwa_' + i] == 1
            );
        }

        // Tindakan
        for (let i = 1; i <= 6; i++) {
            $('#tin_jiwa_' + i).prop(
                'checked',
                data['tin_jiwa_' + i] == 1
            );
        }
        setValIfExists('#diag_lain', data.diag_lain);
        setCheckedIfExists('#tin_6', data.tin_6);
        setCheckedIfExists('#tin_7', data.tin_7);


        // Detail terapi
        setValIfExists('#terapi_oral', data.terapi_oral);
        setValIfExists('#terapi_iv', data.terapi_iv);

    }

    function saveDataPengkajianRJJp(btn) {
        const $button = $(btn);
        const $section = $('#rjj_perawat');

        const data = getFormDataByName($section, {
            NOKUNJ: $section.data('kunjungan')
        });

        $.ajax({
            url: '/api/v2/emr/form/pengkajian/rjj/pr/simpan',
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            beforeSend: function () {
                // $button.prop('disabled', true).html('<i class="ri-refresh-line ri-spin me-1"></i> Menyimpan...');
            },

            success: function (response) {
                // alert(response.message || 'Data berhasil disimpan.');
                iziToast.success({
                    title: 'Pesan Berhasil!',
                    message: 'Data berhasil disimpan.',
                    position: 'topRight'
                });
            },

            error: function (xhr) {
                let message = 'Data gagal disimpan.';

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors)
                        .flat()
                        .join('\n');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                alert(message);
            },

            complete: function () {
                // $button.prop('disabled', false).html('<i class="ri-save-line me-1"></i> Simpan Pengkajian');
            }
        });
    };
</script>
