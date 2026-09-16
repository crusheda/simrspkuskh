<div class="form-wrapper">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN AWAL RAWAT JALAN ANAK</center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>PENGKAJIAN MEDIS (<a class="text-danger">Diisi Oleh Dokter</a>)</center></h1>
    <div class="form-content">
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <div class="form-group mb-2">
                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                            <strong><em>Subjective </em>(S) : </strong>
                        </h5>
                    </div>
                    <div class="col-md-12 mb-2">
                        @include(
                            'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.anamnesis',
                            [
                                'section' => '#rja_dokter',
                                'anak' => 'false',
                            ]
                        )
                    </div>
                    <div class="form-group mb-2">
                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                            <strong><em>Objective </em>(O) : </strong>
                        </h5>
                    </div>
                    <div class="row align-items-center" id="pemeriksaan_fisik">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Pemeriksaan Fisik</label>
                            <textarea class="form-control" name="pfisik" id="pfisik" rows="3"></textarea>
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
                    <div class="form-group mb-2">
                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                            <strong><em>Assessment </em>(A) : </strong>
                        </h5>
                    </div>
                    <div class="col-md-12">
                        <h4 class="text-danger">Diagnosis (<b class="text-warning">ICD</b>)</h4>
                        <div class="mb-3">
                            @include('pages.v2.medicalrecord.detail.form.pengkajian.components.diagnosis_icd')
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
                                    'section' => '#rja_dokter',
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
                        <div class="col-md-12">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Tolok Ukur / Sasaran yang Dicapai</label>
                                <textarea class="form-control" name="tu" id="tu" rows="3"></textarea>
                            </div>
                            <label class="form-label fw-bold">Terapi / Tindakan</label>
                            <textarea class="form-control" name="terapi_tind" id="terapi_tind" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.admission_note',['section' => '#rja_dokter'])
            </div>
        </div>
    </div>
    <div class="form-footer">
        <button class="btn btn-secondary">
            <i class="ri-close-line me-1"></i> Batal
        </button>
        <button class="btn btn-danger" onclick="saveDataPengkajianRJAd(this)">
            <i class="ri-save-line me-1"></i> Simpan Pengkajian
        </button>
    </div>
</div>

<script>

    $(document).ready(function () {

        loadDataPengkajianRJAd();
    });

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

    function loadDataPengkajianRJAd() {
        const kunjungan = $('#rja_dokter').data('kunjungan');

        $.ajax({
            url: `/api/v2/emr/form/pengkajian/rja/dr/get/${kunjungan}`,
            type: 'GET',
            success:function(res){
                isiFormPengkajianRJAd(res);
                // Tampilkan TTV
                // displayTTV(res);
            }
        });
    }

    function isiFormPengkajianRJAd(data){

        $("#pfisik").val(data.pfisik);

        $("#terapi_tind").val(data.terapi_tind);

        $("#tu").val(data.tu);

    }

    function saveDataPengkajianRJAd(btn) {
        const $button = $(btn);
        const $section = $('#rja_dokter');

        const data = getFormDataByName($section, {
            NOKUNJ: $section.data('kunjungan')
        });

        $.ajax({
            url: '/api/v2/emr/form/pengkajian/rja/dr/simpan',
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
