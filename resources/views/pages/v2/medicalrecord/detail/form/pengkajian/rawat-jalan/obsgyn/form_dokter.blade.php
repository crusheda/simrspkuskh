<div class="form-wrapper">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN MEDIS <b class="">RAWAT JALAN</b> <b class="text-warning">OBSGYN</b></center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>(<a class="text-danger">Diisi Oleh Dokter</a>)</center></h1>
    <div class="form-content">
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <div class="form-group mb-2">
                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                            <strong><em>Subjective </em>(S) : </strong>
                        </h5>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            @include(
                                'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.anamnesis',
                                [
                                    'section' => '#rjo_dokter',
                                    'anak' => 'false',
                                ]
                            )
                        </div>
                        <div class="col-md-12">
                            @include('pages.v2.medicalrecord.detail.form.pengkajian.components.riwayat_obstetri')
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                            <strong><em>Objective </em>(O) : </strong>
                        </h5>
                    </div>
                    <div class="row align-items-start" id="pemeriksaan_fisik">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Pemeriksaan Fisik</label>
                        </div>
                        <div class="col-md-6 mb-3" >
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Palpasi Leopold</label>
                                </div>
                                <div class="col-md-8 mb-2">
                                    <input type="text" class="form-control form-control-sm" name="palpasi_leopold" id="palpasi_leopold" hidden>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Leopold I</label>
                                </div>
                                <div class="col-md-8 mb-2">
                                    <input type="text" class="form-control form-control-sm" name="leopold1" id="leopold1">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Leopold II</label>
                                </div>
                                <div class="col-md-8 mb-2">
                                    <input type="text" class="form-control form-control-sm" name="leopold2" id="leopold2">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Leopold III</label>
                                </div>
                                <div class="col-md-8 mb-2">
                                    <input type="text" class="form-control form-control-sm" name="leopold3" id="leopold3">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Leopold IV</label>
                                </div>
                                <div class="col-md-8 mb-2">
                                    <input type="text" class="form-control form-control-sm" name="leopold4" id="leopold4">
                                </div>
                                <div class="col-12 mb-2">
                                    <label class="form-label fw-bold">Auskultasi</label>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">DJJ</label>
                                </div>
                                <div class="col-md-8 mb-2">
                                    <div class="input-group input-group-sm">
                                        <input type="number" class="form-control form-control-sm" name="aus_nadi" id="aus_nadi">
                                        <span class="input-group-text">
                                            X/menit
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                </div>
                                <div class="col-md-8 mb-2">
                                    <div class="d-flex align-items-center gap-3 flex-wrap">
                                        <div class="form-check m-0 flex-shrink-0">
                                            <input class="form-check-input single-checkbox" type="checkbox" name="aus_nadi_cb" value="1" checked>
                                            <label class="form-check-label">
                                                Reguler
                                            </label>
                                        </div>

                                        {{-- IREGULER --}}
                                        <div class="form-check m-0 flex-shrink-0">
                                            <input class="form-check-input single-checkbox" type="checkbox" name="aus_nadi_cb" value="2" >
                                            <label class="form-check-label">
                                                Ireguler
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Pemeriksaan Lain</label>
                                </div>
                                <div class="col-md-8 mb-2">
                                    <div class="d-flex align-items-center gap-3 flex-wrap">
                                        <div class="form-check m-0 flex-shrink-0">
                                            <input class="form-check-input single-checkbox" type="checkbox" name="pem_lain" value="1" checked>
                                            <label class="form-check-label">
                                                Panggul
                                            </label>
                                        </div>

                                        {{-- IREGULER --}}
                                        <div class="form-check m-0 flex-shrink-0">
                                            <input class="form-check-input single-checkbox" type="checkbox" name="pem_lain" value="2" >
                                            <label class="form-check-label">
                                                Osborn
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Extremitas</label>
                                </div>
                                <div class="col-md-8 mb-2">
                                    <input type="text" class="form-control form-control-sm" name="extremitas" id="extremitas">
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Reflek Patela</label>
                                </div>
                                <div class="col-md-8 mb-2">
                                    <div class="input-group input-group-sm">
                                        <input type="number" class="form-control form-control-sm" name="patela1" id="patela1">
                                        <span class="input-group-text">
                                            /
                                        </span>
                                        <input type="number" class="form-control form-control-sm" name="patela2" id="patela2">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Oedema</label>
                                </div>
                                <div class="col-md-8 mb-2">
                                    <div class="input-group input-group-sm">
                                        <input type="number" class="form-control form-control-sm" name="uodema1" id="uodema1">
                                        <span class="input-group-text">
                                            /
                                        </span>
                                        <input type="number" class="form-control form-control-sm" name="uodema2" id="uodema2">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <h4 class="text-danger">Hasil Pemeriksaan Penunjang</h4>
                            <div class="mb-3">
                                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.pemeriksaan_lab')
                            </div>
                            <div class="mb-3">
                                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.pemeriksaan_rad')
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                            <strong><em>Assessment </em>(A) : </strong>
                        </h5>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="text-danger">Diagnosis (<b class="text-warning">ICD</b>)</h4>
                            <div class="mb-3">
                                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.diagnosis_icd')
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                            <strong><em>Plan </em>(P) : </strong>
                        </h5>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-12 mb-2">
                            @include(
                                'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.tanda_vital',
                                [
                                    'section' => '#rjo_dokter',
                                    'page' => 'dokter',
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
                            <label class="form-label fw-bold">Tolok Ukur / Sasaran yang Dicapai</label>
                            <textarea class="form-control" name="tu" id="tu" rows="3"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Terapi / Tindakan</label>
                            <textarea class="form-control" name="terapi_tind" id="terapi_tind" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.admission_note',['section' => '#rjo_dokter'])
            </div>
        </div>
    </div>
    <div class="form-footer">
        <button class="btn btn-secondary">
            <i class="ri-close-line me-1"></i> Batal
        </button>
        <button class="btn btn-danger" onclick="saveDataPengkajianRJOd(this)">
            <i class="ri-save-line me-1"></i> Simpan Pengkajian
        </button>
    </div>
</div>

<script>

    $(document).ready(function () {
        loadDataPengkajianRJOd();
    });

    function formatAngkaBulat(value) {
        if (value === null || value === undefined || value === '') {
            return '-';
        }

        return Number(value).toLocaleString('id-ID', {
            maximumFractionDigits: 0
        });
    }

    function formatSuhu(value) {
        if (value === null || value === undefined || value === '') {
            return '-';
        }

        return Number(value).toLocaleString('id-ID', {
            maximumFractionDigits: 2
        });
    }

    function getABNText(value) {
        if (value == 1) {
            return 'Ya';
        }
        if (value == 2) {
            return 'Tidak';
        }
        return '-';
    }

    function getKesadaranText(value) {
        if (value === null || value === undefined || value === '') {
            return '-';
        }
        return $('#kesadaran option[value="' + value + '"]').text() || '-';
    }

    function loadDataPengkajianRJOd() {
        const kunjungan = $('#rjo_dokter').data('kunjungan');

        $.ajax({
            url: `/api/v2/emr/form/pengkajian/rjo/dr/get/${kunjungan}`,
            type: 'GET',
            success:function(res){
                isiFormPengkajianRJOd(res);
            }
        });
    }

    function isiFormPengkajianRJOd(data){

        if (data.pfisik) {
            $("#pfisik").val(data.pfisik);
        }

        $("#tu").val(data.tu);
        $("#terapi_tind").val(data.terapi_tind);

        $('#palpasi_leopold').val(data.palpasi_leopold);
        $('#leopold1').val(data.leopold1);
        $('#leopold2').val(data.leopold2);
        $('#leopold3').val(data.leopold3);
        $('#leopold4').val(data.leopold4);
        $('#aus_nadi').val(data.aus_nadi);
        $('#extremitas').val(data.extremitas);
        $('#patela1').val(data.patela1);
        $('#patela2').val(data.patela2);
        $('#uodema1').val(data.uodema1);
        $('#uodema2').val(data.uodema2);
        $('#aus_nadi_cb').prop('checked', data.aus_nadi_cb == 1);
        $('#pem_lain').prop('checked', data.pem_lain == 1);
    }

    function saveDataPengkajianRJOd(btn) {
        const $button = $(btn);
        const $section = $('#rjo_dokter');

        const data = getFormDataByName($section, {
            NOKUNJ: $section.data('kunjungan')
        });

        $.ajax({
            url: '/api/v2/emr/form/pengkajian/rjo/dr/simpan',
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
