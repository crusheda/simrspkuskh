<div class="form-wrapper" id="form_lembar_transfer_pasien">
    <h1 class="display-6 mb-3 mt-2 fs-23 fw-medium"><center>LEMBAR <b class="text-warning">TRANSFER PASIEN INTERNAL</b></center></h1>
    <div class="form-content">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <h6>DPJP</h6>
                            <input type="text" class="form-control" value="{{ $list['pasien']->NAMADOKTER ?? '' }}" placeholder="Nama DPJP" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <h6>Ruang Asal</h6>
                            <input type="text" class="form-control" value="{{ $list['pasien']->RUANGAN ?? '' }}" placeholder="Nama DPJP" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <h6>Unit Tujuan</h6>
                            <select class="form-select" name="unit_tujuan" id="unit_tujuan">
                                <option value="">Pilih</option>
                                @foreach ($list['ruangan'] as $item)
                                    <option value="{{ $item->ID }}">
                                        {{ $item->DESKRIPSI }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <h6>Petugas Pendamping</h6>
                            <div class="d-flex align-items-center gap-3">
                                <div class="form-check mb-0">
                                    <input class="form-check-input single-checkbox" type="checkbox" name="petugas" value="1">
                                    <label class="form-check-label">Dokter</label>
                                </div>
                                <div class="form-check mb-0">
                                    <input class="form-check-input single-checkbox" type="checkbox" name="petugas" value="2">
                                    <label class="form-check-label">Perawat</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <h6>Tanggal</h6>
                            <input type="date" class="form-control" name="sn_tanggal_lahir">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group mb-3">
                            <h6>Jam</h6>
                            <input type="time" class="form-control" name="sn_jam_lahir">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-1">
                @include(
                            'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.anamnesis_keperawatan',
                            [
                                'section' => '#form_lembar_transfer_pasien',
                                'anak' => 'false',
                            ]
                        )
            </div>
            <div class="col-md-12 mb-1">
                @include(
                    'pages.v2.medicalrecord.detail.form.pengkajian.components.rawat_inap.tanda_vital',
                    [
                        'section' => '#form_lembar_transfer_pasien',
                        'page' => 'dokter',
                    ]
                )
            </div>
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <label class="form-label">Temuan Klinis</label>
                    <textarea class="form-control" name="klinis" id="" rows="3"></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <h4 class="text-danger">Hasil Pemeriksaan Penunjang</h4>
                <div class="mb-3">
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.pemeriksaan_lab')
                </div>
                <div class="mb-3">
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.pemeriksaan_rad_global')
                </div>
            </div>
            <div class="col-md-12">
                <h4 class="text-danger">Diagnosis (<b class="text-warning">ICD</b>)</h4>
                <div class="mb-3">
                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.diagnosis_icd')
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <h6>Indikasi Dirawat</h6>
                    <textarea class="form-control" name="indikasi" id="" rows="3"></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <h6>Terapi dan Tindakan</h6>
                    <textarea class="form-control" name="terapi" id="" rows="3"></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <h6>Kategori Pasien Transfer</h6>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle text-left mb-0">
                            <thead class="text-center">
                                <tr>
                                    <th style="width: 120px;">Level</th>
                                    <th style="width: 80px;">Pilih</th>
                                    <th>Kategori</th>
                                    <th>Pendamping</th>
                                    <th>Peralatan</th>
                                </tr>
                            </thead>

                            <tbody>

                                {{-- DERAJAD 0 --}}
                                <tr>
                                    <td>
                                        <strong>DERAJAD 0</strong>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input single-checkbox" name="kategori_trans" value="1" id="kategori_trans">
                                    </td>
                                    <td>Pasien membutuhkan ruang perawatan biasa</td>
                                    <td>Perawat</td>
                                    <td>Status rekam medis, hasil pemeriksaan penunjang, format transfer internal, kursi roda / tempat tidur</td>
                                </tr>

                                {{-- DERAJAD 1 --}}
                                <tr>
                                    <td>
                                        <strong>DERAJAD 1</strong>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input single-checkbox" name="kategori_trans" value="2" id="kategori_trans">
                                    </td>
                                    <td>Pasien beresiko mengalami pemburukan, pasien baru pindah dari HCU/ICU, pasien yang akan dirawat di ruang perawatan biasa dengan pengawasan tim perawatan khusus</td>
                                    <td>Perawat</td>
                                    <td>Perawat derajad 0 + tabung oksigen dan canul, stand infus dan pulse oksimetri</td>
                                </tr>

                                {{-- DERAJAD 2 --}}
                                <tr>
                                    <td>
                                        <strong>DERAJAD 2</strong>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input single-checkbox" name="kategori_trans" value="3" id="kategori_trans">
                                    </td>
                                    <td>Pasien memerlukan pengawasan ketat atau intervensi khusus, mis : pada pasien yang mengalami kegagalan satu sistim organ</td>
                                    <td>Perawat</td>
                                    <td>Perawat derajad 1 + bedside monitor, syring pump</td>
                                </tr>

                                {{-- DERAJAD 3 --}}
                                <tr>
                                    <td>
                                        <strong>DERAJAD 3</strong>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input single-checkbox" name="kategori_trans" value="4" id="kategori_trans">
                                    </td>
                                    <td>Pasien mengalami kegagalan multi organ dan memerlukan bantuan hidup jangka panjang ditambah dengan kebutuhan akan alat bantu nafas</td>
                                    <td>Dokter dan Perawat</td>
                                    <td>Peralatan derajad 2 + alat bantu nafas</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-2">
                <h4 class="text-danger">Status Pasien</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-body border border-dashed border-success mb-1" id="form_lembar_transfer_pasien_sebelum">
                            <div class="border-bottom mb-3 text-center">
                                <h4>Sebelum Transfer</h4>
                            </div>
                            @include(
                                'pages.v2.medicalrecord.detail.form.pengkajian.components.tanda_vital_transfer',
                                [
                                    'section' => '#form_lembar_transfer_pasien_sebelum',
                                    'page' => 'perawat',
                                    'transfer' => 1,
                                    ]
                                    )
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="form-group mb-2">
                                        <h5 class="mb-0 text-success">
                                            <strong>SKRINING NYERI</strong>
                                        </h5>
                                    </div>
                                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_nyeri',
                                        [
                                            'section' => '#rio_perawat',
                                            'metodeNyeri' => ['vas']
                                        ]
                                    )
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-body border border-dashed border-success mb-1" id="form_lembar_transfer_pasien_sesudah">
                            <div class="border-bottom mb-3 text-center">
                                <h4>Setelah Transfer</h4>
                            </div>
                            @include(
                                'pages.v2.medicalrecord.detail.form.pengkajian.components.tanda_vital_transfer',
                                [
                                    'section' => '#form_lembar_transfer_pasien_sesudah',
                                    'page' => 'perawat',
                                    'transfer' => 2,
                                    ]
                                    )
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="form-group mb-2">
                                        <h5 class="mb-0 text-success">
                                            <strong>SKRINING NYERI</strong>
                                        </h5>
                                    </div>
                                    @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_nyeri',
                                        [
                                            'section' => '#rio_perawat',
                                            'metodeNyeri' => ['vas']
                                        ]
                                    )
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group mb-3">
                    <h4 class="mb-2 text-warning">Checklist Transfer</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="form-check mb-0"><input class="form-check-input check-primary" type="checkbox" name="spri" value="1"></div>
                                <label class="form-label mb-0" for="">Surat Perintah Rawat Inap</label>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="form-check mb-0"><input class="form-check-input check-primary" type="checkbox" name="shp" value="1"></div>
                                <label class="form-label mb-0" for="">Surat Hasil Pemeriksaan</label>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="form-check mb-0"><input class="form-check-input check-primary" type="checkbox" name="slain" value="1"></div>
                                <label class="form-label mb-0" for="">Lain-lain</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    const $form = $('#form_lembar_transfer_pasien');

    let isDataLoading = false;
    let isDataSaving = false;

    // ==========================================================
    // GET DATA
    // ==========================================================
    function getData() {

        if (!$form.length) {
            console.warn('Form Data tidak ditemukan.');
            return;
        }

        isDataLoading = true;

        $.ajax({
            url: `/api/v2/emr/form/lain/lembartransferpasien/${kunjungan}`,
            type: 'GET',
            dataType: 'json',

            success: function (res) {

                const tlt = res.data;

                if (!tlt) {
                    return;
                }

                // ======================================================
                // UNIT TUJUAN
                // ======================================================

                if (FormHelper.hasValue(tlt.UNIT_TUJUAN)) {
                    FormHelper.setValue(
                        $form,
                        'unit_tujuan',
                        tlt.UNIT_TUJUAN
                    );
                }


                // ======================================================
                // PETUGAS PENDAMPING
                // ======================================================

                setCheckboxValue('petugas', tlt.PETUGAS);


                // ======================================================
                // TANGGAL
                // ======================================================

                if (FormHelper.hasValue(tlt.TANGGAL_TRANSFER)) {
                    FormHelper.setValue(
                        $form,
                        'sn_tanggal_lahir',
                        tlt.TANGGAL_TRANSFER
                    );
                }


                // ======================================================
                // JAM
                // ======================================================

                if (FormHelper.hasValue(tlt.JAM_TRANSFER)) {
                    FormHelper.setValue(
                        $form,
                        'sn_jam_lahir',
                        tlt.JAM_TRANSFER
                    );
                }


                // ======================================================
                // TEMUAN KLINIS
                // ======================================================

                if (FormHelper.hasValue(tlt.KLINIS)) {
                    FormHelper.setValue(
                        $form,
                        'klinis',
                        tlt.KLINIS
                    );
                }


                // ======================================================
                // INDIKASI
                // ======================================================

                if (FormHelper.hasValue(tlt.INDIKASI)) {
                    FormHelper.setValue(
                        $form,
                        'indikasi',
                        tlt.INDIKASI
                    );
                }


                // ======================================================
                // TERAPI
                // ======================================================

                if (FormHelper.hasValue(tlt.TERAPI)) {
                    FormHelper.setValue(
                        $form,
                        'terapi',
                        tlt.TERAPI
                    );
                }


                // ======================================================
                // KATEGORI TRANSFER
                // ======================================================

                setCheckboxValue('kategori_trans', tlt.KATEGORI_TRANS);


                // ======================================================
                // CHECKLIST TRANSFER
                // ======================================================

                setCheckboxValue(
                    'spri',
                    tlt.SPRI
                );

                setCheckboxValue(
                    'shp',
                    tlt.SURAT_HASIL
                );

                setCheckboxValue(
                    'slain',
                    tlt.SURAT_LAIN
                );
            },

            error: function (xhr, status, error) {

                console.error(
                    'Error :',
                    xhr.responseText || error
                );

                let message =
                    'Gagal mengambil data.';

                if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                console.warn(message);
            },

            complete: function () {
                isDataLoading = false;
            }
        });
    }

    // ==========================================================
    // SIMPAN DATA
    // ==========================================================
    function simpanData() {

        if (
            !$form.length ||
            isDataLoading ||
            isDataSaving
        ) {
            return;
        }

        const data = getFormDataByName($form, {
            NOKUNJ: kunjungan
        });

        isDataSaving = true;

        $.ajax({
            url: `/api/v2/emr/form/lain/lembartransferpasien/${kunjungan}/simpan`,
            type: 'POST',
            data: data,

            headers: {
                'X-CSRF-TOKEN': $(
                    'meta[name="csrf-token"]'
                ).attr('content')
            },

            success: function (res) {

            },

            error: function (xhr) {

                let message =
                    'Data gagal disimpan.';

                if (
                    xhr.status === 422 &&
                    xhr.responseJSON?.errors
                ) {
                    message = Object
                        .values(xhr.responseJSON.errors)
                        .flat()
                        .join('<br>');
                }
                else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }

                iziToast.error({
                    title: 'Validasi Gagal!',
                    message: message,
                    position: 'topRight'
                });
            },

            complete: function () {
                isDataSaving = false;
            }
        });
    }

    // ==========================================================
    // AUTO SAVE
    // ==========================================================
    $(function () {

        if (!$form.length) {
            return;
        }

        $('.single-checkbox').on('change', function () {
            if (!this.checked) return;

            const $otherCheckboxes = $('input.single-checkbox[name="' + this.name + '"]').not(this);

            $otherCheckboxes.each(function() {
                if (this.checked) {
                    this.checked = false;
                    // Pemicu manual agar fungsi onchange="toggleSubOptions(...)" di HTML ikut berjalan
                    $(this).trigger('change');
                }
            });
        });
        $('.single-checkbox-bos').on('change', function () {
            // Jika checkbox di-uncheck, langsung kembalikan ke checked
            if (!this.checked) {
                this.checked = true;
                return;
            }
            // Uncheck pilihan lain dengan name yang sama
            $('input.single-checkbox-bos[name="' + this.name + '"]')
                .not(this)
                .prop('checked', false);
        });

        getData();

        $form.on(
            'blur',
            'textarea,input',
            function () {

                if (isDataLoading) {
                    return;
                }

                simpanData();
            }
        );

        $form.on(
            'change',
            'select,input[type="checkbox"],input[type="radio"]',
            function () {

                if (isDataLoading) {
                    return;
                }

                simpanData();
            }
        );
    });

    function setCheckboxValue(name, value) {
        const $checkboxes = $('input[type="checkbox"][name="' + name + '"]');

        // Bersihkan pilihan sebelumnya
        $checkboxes.prop('checked', false);

        if (value === null || value === undefined || value === '') {
            return;
        }

        // Cocokkan value checkbox dengan value dari database
        $checkboxes
            .filter(function () {
                return String($(this).val()) === String(value);
            })
            .prop('checked', true);
    }

})();
</script>
