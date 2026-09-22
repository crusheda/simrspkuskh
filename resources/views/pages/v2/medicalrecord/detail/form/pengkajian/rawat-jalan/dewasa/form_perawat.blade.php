<div class="form-wrapper">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN AWAL KEPERAWATAN <b class="">RAWAT JALAN</b> <b class="text-warning">DEWASA</b></center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>(<a class="text-success">Diisi Oleh Perawat</a>)</center></h1>
    <div class="form-content">
        <div class="row">
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_jalan.keluhan_utama',['section' => '#rjd_perawat'])
            </div>
            <div class="col-md-12 mb-2">
                @include(
                    'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.tanda_vital',
                    [
                        'section' => '#rjd_perawat',
                        'page' => 'perawat',
                        'idRuangan' => $list['dataKunjungan']->IDRUANGAN ?? $list['dataKunjungan']['IDRUANGAN'] ?? null,
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
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.anamnesis_hubungan_status_psikososial',['section' => '#rjd_perawat','kunjungan' => $kunjungan ?? $list['kunjungan']])
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
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_resiko_jatuh_gtg', ['section' => '#rjd_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group mb-2">
                    <h5 class="mb-0 text-success">
                        <strong>SKRINING GIZI</strong>
                    </h5>
                </div>
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_gizi_must', ['section' => '#rjd_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.kebutuhan_edukasi',['section' => '#rjd_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                <div class="card card-body border border-dashed border-warning mb-1">
                    <div class="row">
                        <div class="col-md-12 mb-1">
                            <div class="form-group">
                                <h5 class="border-bottom pb-2 text-bold">
                                    <strong>Masalah Keperawatan </strong>
                                </h5>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="row">

                                <!-- Kolom Kiri -->
                                <div class="col-md-6">

                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_1" id="diag_1">
                                        <label class="form-check-label">Bersihkan jalan nafas tidak efektif</label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_2" id="diag_2">
                                        <label class="form-check-label">Pola nafas tidak efektif</label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_3" id="diag_3">
                                        <label class="form-check-label">Perfusi perifer tidak efektif</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_4" id="diag_4">
                                        <label class="form-check-label">Diare</label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_5" id="diag_5">
                                        <label class="form-check-label">Nyeri Akut</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_6" id="diag_6">
                                        <label class="form-check-label">Nausea</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_7" id="diag_7">
                                        <label class="form-check-label">Hipertermi</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_8" id="diag_8">
                                        <label class="form-check-label">Ansietas</label>
                                    </div>

                                </div>

                                <!-- Kolom Kanan -->
                                <div class="col-md-6">

                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_9" id="diag_9">
                                        <label class="form-check-label">Gangguan integritas kulit / jaringan</label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_10" id="diag_10">
                                        <label class="form-check-label">Gangguan eliminasi urin</label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_11" id="diag_11">
                                        <label class="form-check-label">Intoleransi aktifitas</label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_12" id="diag_12">
                                        <label class="form-check-label">Gangguan mobilitas fisik</label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_13" id="diag_13">
                                        <label class="form-check-label">Gangguan pertukaran gas</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <label class="form-input-label">Masalah Keperawatan Lainnya</label>
                                        <input type="text" class="form-control" id="diag_lain" name="diag_lain" placeholder="Lainnya">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Perencanaan dan Tindakan -->
                        <div class="col-md-12 mb-3">
                            <div class="row">
                                <label class="form-label fw-bold">Perencanaan dan Tindakan</label>

                                <!-- Mandiri -->
                                <div class="col-md-6 border-end">

                                    <label class="form-label fw-semibold">Mandiri</label>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_1" id="tin_1">
                                        <label class="form-check-label" for="tin_1">Ajarkan teknik relaksasi dan nafas dalam</label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_2" id="tin_2">
                                        <label class="form-check-label" for="tin_2">Pertahankan body alignment dan posisi yang nyaman</label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_3" id="tin_3" checked>
                                        <label class="form-check-label" for="tin_3">Tenangkan Pasien</label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_4" id="tin_4" checked>
                                        <label class="form-check-label" for="tin_4">Berikan Pendidikan Kesehatan ke pasien dan keluarga</label>
                                    </div>

                                </div>

                                <!-- Kolaborasi -->
                                <div class="col-md-6">

                                    <label class="form-label fw-semibold">Kolaborasi</label>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_5" id="tin_5">
                                        <label class="form-check-label" for="tin_5">Rawat Luka</label>
                                    </div>

                                    <div class="row align-items-center mb-2">
                                        <label class="col-md-12 col-form-label mb-0">Pemberian Terapi :</label>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input check-primary" type="checkbox" value="1" name="tin_6" id="tin_6">
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
        </div>
    </div>
    <div class="form-footer">
        <button class="btn btn-secondary">
            <i class="ri-close-line me-1"></i> Batal
        </button>
        <button class="btn btn-success" onclick="saveDataPengkajianRJDp(this)">
            <i class="ri-save-line me-1"></i> Simpan Pengkajian
        </button>
    </div>
</div>

<script>
    $(document).ready(function () {
        const $section = $('#rjd_perawat');
        // Sembunyikan textarea saat pertama kali
        $('#pse_lain').hide();
        $('#sm_2_detail').hide();
        $('#sm_3_detail').hide();
        $('#nk_lain').hide();
        $('#terapi_oral').hide();
        $('#terapi_iv').hide();
        $('#perubahan_berat').hide();

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

        loadDataPengkajianRJDp();

    });

    function loadDataPengkajianRJDp() {
        const kunjungan = $('#rjd_perawat').data('kunjungan');

        $.ajax({
            url: `/api/v2/emr/form/pengkajian/rjd/pr/get/${kunjungan}`,
            type: 'GET',
            success:function(res){
                // Isi form
                isiFormPengkajianRJDp(res);
                // console.log(res);
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

    function isiFormPengkajianRJDp(data){
        // ======================================================
        // MASALAH KEPERAWATAN
        // ======================================================

        setCheckedIfExists('#diag_1', data.diag_1);
        setCheckedIfExists('#diag_2', data.diag_2);
        setCheckedIfExists('#diag_3', data.diag_3);
        setCheckedIfExists('#diag_4', data.diag_4);
        setCheckedIfExists('#diag_5', data.diag_5);
        setCheckedIfExists('#diag_6', data.diag_6);
        setCheckedIfExists('#diag_7', data.diag_7);
        setCheckedIfExists('#diag_8', data.diag_8);
        setCheckedIfExists('#diag_9', data.diag_9);
        setCheckedIfExists('#diag_10', data.diag_10);
        setCheckedIfExists('#diag_11', data.diag_11);
        setCheckedIfExists('#diag_12', data.diag_12);
        setCheckedIfExists('#diag_13', data.diag_13);

        setValIfExists('#diag_lain', data.diag_lain);


        // ======================================================
        // TINDAKAN
        // ======================================================

        setCheckedIfExists('#tin_1', data.tin_1);
        setCheckedIfExists('#tin_2', data.tin_2);
        setCheckedIfExists('#tin_3', data.tin_3);
        setCheckedIfExists('#tin_4', data.tin_4);
        setCheckedIfExists('#tin_5', data.tin_5);

        setCheckedIfExists('#tin_6', data.tin_6);
        setCheckedIfExists('#tin_7', data.tin_7);


        // ======================================================
        // DETAIL TERAPI
        // ======================================================

        setValIfExists('#terapi_oral', data.terapi_oral);
        setValIfExists('#terapi_iv', data.terapi_iv);

    }

    function saveDataPengkajianRJDp(btn) {
        const $button = $(btn);
        const $section = $('#rjd_perawat');

        const data = getFormDataByName($section, {
            NOKUNJ: $section.data('kunjungan')
        });

        $.ajax({
            url: '/api/v2/emr/form/pengkajian/rjd/pr/simpan',
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
