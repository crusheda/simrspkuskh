<div class="form-wrapper">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN AWAL MEDIS <b class="">RAWAT JALAN</b> <b class="text-warning">JIWA</b></center></h1>
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
                    <div class="col-md-12 mb-2">
                        @include(
                            'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.anamnesis',
                            [
                                'section' => '#rjj_dokter',
                                'anak' => 'false',
                            ]
                        )
                    </div>
                    <div class="form-group mb-2">
                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                            <strong><em>Objective </em>(O) : </strong>
                        </h5>
                    </div>
                    <div class="col-md-12 mb-2">
                        @include(
                            'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.tanda_vital',
                            [
                                'section' => '#rjj_dokter',
                                'page' => 'dokter',
                                'editableFields' => [
                                    'tv_keu',
                                    'tv_gcs_e',
                                    'tv_gcs_v',
                                    'tv_gcs_m',
                                    'tv_td_up',
                                    'tv_td_down',
                                    'tv_nadi',
                                    'tv_nadi_cb',
                                    'tv_nafas',
                                    'tv_nafas_cb',
                                    'tv_suhu',
                                    'tv_spo2',
                                    'tv_bb',
                                    'tv_tb',
                                ],
                            ]
                        )
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
                    <div class="row align-items-start">
                        <div class="col-md-12 mb-3">
                            <div class="card card-body border border-dashed border-danger mb-0">
                                <div class="form-group">
                                    <h6>Diagnosis</h6>
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="flex-grow-1">
                                            <textarea name="diag_detail" id="diag_detail" class="form-control" placeholder="Masukkan Diagnosa" rows="4">Axis
I.
II.
III.
IV.
V.</textarea>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="form-check mb-0">
                                                <input class="form-check-input check-primary" type="checkbox" name="diag_utama" value="1" id="diag_utama">
                                                <label class="form-check-label" for="diag_utama">
                                                    Diagnosis Utama
                                                </label>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-success" id="btnTambahDiagnosis"
                                                    onclick="tambahDiagnosis()">
                                                    <i class="ri-add-box-line"></i>
                                                </button>
                                                <button type="button" class="btn btn-subtle-warning" id="btnRefreshDiagnosis"
                                                    onclick="getDiagnosis()">
                                                    <i class="ri-refresh-line"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table align-middle mb-1">
                                            <thead>
                                                <colgroup>
                                                    <col style="width: 1%;">
                                                    <col>
                                                    <col style="width: 5%;">
                                                    <col style="width: 1%;">
                                                </colgroup>
                                                <tr class="table_info">
                                                    <th>No</th>
                                                    <th>Diagnosis</th>
                                                    <th>Utama</th>
                                                    <th class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tblDiagnosisBody">
                                                <tr>
                                                    <td colspan="4" class="text-center">Tidak ada data</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                            <strong><em>Plan </em>(P) : </strong>
                        </h5>
                    </div>
                    <div class="row align-items-center">
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
                <div class="card card-body border border-dashed border-warning mb-1">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-bold">Materi Edukasi</label>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input check-primary" type="checkbox" name="me_1" id="me_1" checked>
                                            <label class="form-check-label"> Tanda dan gejala suatu penyakit </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input check-primary" type="checkbox" name="me_2" id="me_2" checked>
                                            <label class="form-check-label"> Hasil pemeriksaan </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input check-primary" type="checkbox" name="me_3" id="me_3" checked>
                                            <label class="form-check-label"> Diagnosis </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input check-primary" type="checkbox" name="me_4" id="me_4" checked>
                                            <label class="form-check-label"> Rencana penatalaksanaan penyakit </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input check-primary" type="checkbox" name="me_5" id="me_5" checked>
                                            <label class="form-check-label"> Tindakan dan tujuan terapi </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-bold">Sarana Informasi / Edukasi</label>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input check-primary" type="checkbox" name="sie_1" id="sie_1">
                                            <label class="form-check-label"> Leaflet </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input check-primary" type="checkbox" name="sie_2" id="sie_2" checked>
                                            <label class="form-check-label"> Lisan </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label fw-bold">Evaluasi</label>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input check-primary" type="checkbox" name="eval_1" id="eval_1" checked>
                                            <label class="form-check-label"> Sudah Mengerti </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input check-primary" type="checkbox" name="eval_2" id="eval_2">
                                            <label class="form-check-label"> Re - Edukasi </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.admission_note',['section' => '#rjj_dokter'])
            </div>
        </div>
    </div>
    <div class="form-footer">
        <button class="btn btn-secondary">
            <i class="ri-close-line me-1"></i> Batal
        </button>
        <button class="btn btn-danger" onclick="saveDataPengkajianRJJd(this)">
            <i class="ri-save-line me-1"></i> Simpan Pengkajian
        </button>
    </div>
</div>

<script>

    $(document).ready(function () {

        loadDataPengkajianRJJd();
        getDiagnosis();
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

    function loadDataPengkajianRJJd() {
        const kunjungan = $('#rjj_dokter').data('kunjungan');

        $.ajax({
            url: `/api/v2/emr/form/pengkajian/rjj/dr/get/${kunjungan}`,
            type: 'GET',
            success:function(res){
                isiFormPengkajianRJJd(res);
            }
        });
    }

    function setValIfExists(selector, value) {
        if (value !== null && value !== undefined && value !== '') {
            $(selector).val(value);
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

    function isiFormPengkajianRJJd(data) {

        $("#pfisik").val(data.pfisik);

        $("#tu").val(data.tu);
        $("#terapi_tind").val(data.terapi_tind);

        // DIAGNOSIS UTAMA
        if (
            data.diag_detail !== null &&
            data.diag_detail !== undefined &&
            data.diag_detail.trim() !== ''
        ) {
            $("#diag_detail").val(data.diag_detail);
        }

        if (data.diag_utama !== null && data.diag_utama !== undefined) {
            $('#diag_utama')
                .prop('checked', Number(data.diag_utama) === 1);
        }

        // MASALAH / EDUKASI
        setCheckedIfExists('#me_1', data.me_1);
        setCheckedIfExists('#me_2', data.me_2);
        setCheckedIfExists('#me_3', data.me_3);
        setCheckedIfExists('#me_4', data.me_4);
        setCheckedIfExists('#me_5', data.me_5);

        // SIE
        setCheckedIfExists('#sie_1', data.sie_1);
        setCheckedIfExists('#sie_2', data.sie_2);

        // EVALUASI
        setCheckedIfExists('#eval_1', data.eval_1);
        setCheckedIfExists('#eval_2', data.eval_2);

    }

    function saveDataPengkajianRJJd(btn) {
        const $button = $(btn);
        const $section = $('#rjj_dokter');

        const data = getFormDataByName($section, {
            NOKUNJ: $section.data('kunjungan')
        });

        $.ajax({
            url: '/api/v2/emr/form/pengkajian/rjj/dr/simpan',
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

    // ADD ON ---------------------------------------------------------------------------------------------------------------------------------------------------

    function getDiagnosis() {
        const $button = $('#btnRefreshDiagnosis');
        const kunjungan = $('#rjj_dokter').data('kunjungan');

        $.ajax({
            url: `/api/v2/emr/pengkajian/diagnosis/${kunjungan}`,
            type: 'GET',
            beforeSend: function () {
                $button.prop('disabled', true).html('<i class="ri-refresh-line ri-spin"></i>');
                $("#tblDiagnosisBody").html(`<tr><td colspan="4" class="text-center"><i class="ri-refresh-line ri-spin me-1"></i> Memproses data...</td></tr>`);
            },
            success: function (res) {
                let html = '';
                if (res.length > 0) {
                    $.each(res, function (i, v) {
                        html += `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${v.DIAGNOSA}</td>
                            <td>${v.UTAMA}</td>
                            <td class="text-center">
                                <button class="btn btn-danger btn-sm" onclick="hapusDiagnosis(${v.ID})">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                        `;
                    });
                } else {
                    html = `
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data</td>
                    </tr>
                    `;
                }
                $("#tblDiagnosisBody").html(html);
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
                $button.prop('disabled', false).html('<i class="ri-refresh-line"></i>');
            }
        });
    };

    function tambahDiagnosis() {
        const $button = $('#btnTambahDiagnosis');
        let utama = $("[name='diag_utama']").prop("checked") ? 1 : 0;
        let diagnosa = $("[name='diag_detail']").val();

        $.ajax({
            url: `/api/v2/emr/pengkajian/diagnosis/${kunjungan}/simpan`,
            type: 'POST',
            data: {
                'utama': utama,
                'diagnosa': diagnosa
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            beforeSend: function () {
                $button.prop('disabled', true).html('<i class="ri-refresh-line ri-spin"></i>');
            },

            success: function (res) {
                iziToast.success({
                    title: 'Proses Berhasil!',
                    message: res.message || 'Data berhasil disimpan.',
                    position: 'topRight'
                });
                $("[name='diag_detail']").val('');
                $("[name='diag_utama']").prop('checked', false);

                getDiagnosis();
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
                $button.prop('disabled', false).html('<i class="ri-add-box-line"></i>');
            }
        });
    };

    function hapusDiagnosis(id){
        const kunjungan = $('#rjj_dokter').data('kunjungan');
        $.ajax({
            url: `/api/v2/emr/pengkajian/diagnosis/${kunjungan}/hapus/${id}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                iziToast.success({
                    title: 'Proses Berhasil!',
                    message: res.message || 'Data berhasil dihapus.',
                    position: 'topRight'
                });
                getDiagnosis();
            },
            error: function (xhr) {
                let message = 'Data gagal dihapus.';

                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors)
                        .flat()
                        .join('\n');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                alert(message);
            }
        });
    };
</script>
