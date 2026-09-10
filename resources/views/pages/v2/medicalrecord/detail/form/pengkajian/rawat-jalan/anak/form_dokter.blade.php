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
                <div class="card card-body border border-dashed border-success mb-1">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Tindak Lanjut :</label>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tl" id="tl_mrs" value="1">
                                <label class="form-check-label">
                                    MRS
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tl" id="tl_pulang" value="2">
                                <label class="form-check-label">
                                    Pulang
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="card card-body card-header border border-dashed border-primary" id="pri">
                        <div class="card-header fw-bold">
                            Perencanaan Rawat Inap
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Jenis Ruang Perawatan</label>
                                        <select class="form-control" name="pri_ruang" id="pri_ruang">
                                            <option value="">Pilih Jenis Ruang Perawatan</option>
                                            @foreach ($list['jenis_ruang'] as $item)
                                                <option value="{{ $item->ID }}">
                                                    {{ $item->DESKRIPSI }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Jenis Perawatan</label>
                                        <select class="form-control" name="pri_perawatan" id="pri_perawatan">
                                            <option value="">Pilih Jenis Perawatan</option>
                                            @foreach ($list['jenis_perawatan'] as $item)
                                                <option value="{{ $item->ID }}">
                                                    {{ $item->DESKRIPSI }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Tanggal</label>
                                    <div class="input-group">
                                        <input type="text" name="pri_tgl" id="pri_tgl" class="form-control flatpickr-input active" placeholder="Pilih Rentang Tanggal" readonly="readonly">
                                        <span class="input-group-text"><i class="feather icon-calendar"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Indikasi</label>
                                    <textarea class="form-control" name="pri_indikasi" id="pri_indikasi" rows="3"></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Keterangan</label>
                                    <textarea class="form-control" name="pri_ket" id="pri_ket" rows="3"></textarea>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">DPJP</label>
                                    <input type="text" class="form-control" value="{{ $list['pasien']->NAMADOKTER ?? '' }}" placeholder="Nama DPJP" readonly>
                                    <input type="hidden" name="pri_dpjp" value="{{ $list['pasien']->ID ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <hr>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Dirujuk Ke</label>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rujuk" id="rujuk_gizi" value="1">
                                        <label class="form-check-label">
                                            Ahli Gizi
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rujuk" id="rujuk_rehab" value="2">
                                        <label class="form-check-label">
                                            Rehabilitasi Medik
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rujuk" id="rujuk_sp" value="3">
                                        <label class="form-check-label">
                                            Klinik Spesialis
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rujuk" id="rujuk_lain" value="4">
                                        <label class="form-check-label">
                                            Lainnya
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="rujuk_lainnya" id="rujuk_lainnya" placeholder="Sebutkan....">
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
        <button class="btn btn-danger" onclick="saveDataPengkajianRJAd(this)">
            <i class="ri-save-line me-1"></i> Simpan Pengkajian
        </button>
    </div>
</div>

<script>

    $(document).ready(function () {

        // Sembunyikan textarea saat pertama kali
        $('#pri').hide();
        $('#rujuk_lainnya').hide();

        // Perencanaan Rawat Inap
        $('input[name="tl"]').change(function () {
            if ($('#tl_mrs').is(':checked')) {
                $('#pri').slideDown();
            } else {
                $('#pri').slideUp();
            }
        });

        // Dirujuk Ke
        $('input[name="rujuk"]').change(function () {
            if ($('#rujuk_lain').is(':checked')) {
                $('#rujuk_lainnya').slideDown();
            } else {
                $('#rujuk_lainnya').slideUp().val('');
            }
        });

        // FLATPICKR DATE
        const today = new Date(); // Hari ini
        const fiveYearsAgo = new Date();
        fiveYearsAgo.setFullYear(today.getFullYear() - 5); // 5 tahun ke belakang
        $("#pri_tgl").flatpickr(
            {
                // enableTime: true,
                // dateFormat: "Y-m-d H:i",
                mode: 'single',
                minDate: fiveYearsAgo, // Mulai dari 5 tahun yang lalu
                maxDate: today,        // Sampai hari ini
                dateFormat: 'Y-m-d',
                defaultDate: [today]
            }
        );

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
                displayTTV(res);
            }
        });
    }

    function isiFormPengkajianRJAd(data){

        $("#pfisik").val(data.pfisik);

        $("#terapi_tind").val(data.terapi_tind);

        $("#tu").val(data.tu);

        $('input[name="tl"][value="' + data.tl + '"]').prop('checked', true).trigger('change');
        $('input[name="rujuk"][value="' + data.rujuk + '"]').prop('checked', true).trigger('change');
        $('#rujuk_lainnya').val(data.rujuk_lainnya);

        $('#pri_ruang').val(data.pri_ruang);
        $('#pri_perawatan').val(data.pri_perawatan);
        $('#pri_indikasi').val(data.pri_indikasi);
        $('#pri_ket').val(data.pri_ket);
        $('#pri_dpjp').val(data.pri_dpjp);

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
