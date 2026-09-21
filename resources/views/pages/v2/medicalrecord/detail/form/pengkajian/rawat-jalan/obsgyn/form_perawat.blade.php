<div class="form-wrapper">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN KEPERAWATAN <b class="">RAWAT JALAN</b> <b class="text-warning">OBSGYN</b></center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>(<a class="text-success">Diisi Oleh Bidan</a>)</center></h1>
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
                        'section' => '#rjo_perawat',
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
        </div>
        <div class="col-md-12 mb-1">
            @include('pages.v2.medicalrecord.detail.form.pengkajian.components.riwayat_obstetri')
        </div>
        <div class="col-md-12">
            @include('pages.v2.medicalrecord.detail.form.pengkajian.components.riwayat_menstruasi_kb')
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.anamnesis_hubungan_status_psikososial',['section' => '#rjo_perawat','kunjungan' => $kunjungan ?? $list['kunjungan']])
            </div>
            <div class="col-md-12 mb-3">
                <div class="card card-body border border-dashed border-primary">

                    <label class="form-label fw-bold">
                        Status Fungsional
                    </label>

                    <!-- Alat Bantu -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Alat Bantu Mobilitas
                        </label>

                        <div class="row">

                            <div class="col-md-3">
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary"
                                        type="radio"
                                        name="alat_bantu_fungsional"
                                        id="tanpa_alat_bantu"
                                        value="tanpa">
                                    <label class="form-check-label" for="tanpa_alat_bantu">
                                        Tanpa Alat Bantu
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary"
                                        type="radio"
                                        name="alat_bantu_fungsional"
                                        id="tongkat"
                                        value="tongkat">
                                    <label class="form-check-label" for="tongkat">
                                        Tongkat
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary"
                                        type="radio"
                                        name="alat_bantu_fungsional"
                                        id="kursi_roda"
                                        value="kursi_roda">
                                    <label class="form-check-label" for="kursi_roda">
                                        Kursi Roda
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary"
                                        type="radio"
                                        name="alat_bantu_fungsional"
                                        id="brankard"
                                        value="brankard">
                                    <label class="form-check-label" for="brankard">
                                        Brankard
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-check mb-2">
                                    <input class="form-check-input check-primary"
                                        type="radio"
                                        name="alat_bantu_fungsional"
                                        id="walker"
                                        value="walker">
                                    <label class="form-check-label" for="walker">
                                        Walker
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-9">
                                <input type="text"
                                    class="form-control"
                                    name="alat_bantu"
                                    id="alat_bantu"
                                    placeholder="Alat bantu lainnya...">
                            </div>

                        </div>
                    </div>

                    <hr>

                    <!-- Cacat Tubuh -->
                    <div>
                        <label class="form-label fw-semibold">
                            Cacat Tubuh
                        </label>

                        <div class="row mb-2">

                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input check-primary"
                                        type="radio"
                                        name="cacat_tubuh"
                                        id="cacat_tubuh_tidak"
                                        value="0">
                                    <label class="form-check-label" for="cacat_tubuh_tidak">
                                        Tidak
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input check-primary"
                                        type="radio"
                                        name="cacat_tubuh"
                                        id="cacat_tubuh_ya"
                                        value="1">
                                    <label class="form-check-label" for="cacat_tubuh_ya">
                                        Ya
                                    </label>
                                </div>
                            </div>

                        </div>

                        <textarea class="form-control"
                                name="ket_cacat_tubuh"
                                id="ket_cacat_tubuh"
                                rows="2"
                                placeholder="Keterangan cacat tubuh..."></textarea>
                    </div>

                </div>
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
                {{-- @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_resiko_jatuh_humpty_dumpty', ['section' => '#rjo_perawat']) --}}
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_resiko_jatuh_skala_morse', ['section' => '#rjo_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                <div class="form-group mb-2">
                    <h5 class="mb-0 text-success">
                        <strong>SKRINING GIZI</strong>
                    </h5>
                </div>
                @include('pages.v2.medicalrecord.detail.form.pengkajian.components.skrining_gizi_must', ['section' => '#rjo_perawat'])
            </div>
            <div class="col-md-12 mb-3">
                <div class="card card-body border border-dashed border-primary">
                    <label class="form-label fw-bold">
                        Masalah Keperawatan
                    </label>
                    <div class="row">
                        <!-- Kolom 1 -->
                        <div class="col-md-4">
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_keperawatan_1" id="diag_keperawatan_1">
                                <label class="form-check-label">
                                    Nyeri
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_keperawatan_2" id="diag_keperawatan_2">
                                <label class="form-check-label">
                                    Gangguan Perfusi Cerebral
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_keperawatan_3" id="diag_keperawatan_3">
                                <label class="form-check-label">
                                    Cemas
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_keperawatan_4" id="diag_keperawatan_4">
                                <label class="form-check-label">
                                    Sensori Persepsi
                                </label>
                            </div>
                        </div>
                        <!-- Kolom 2 -->
                        <div class="col-md-4">
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_keperawatan_5" id="diag_keperawatan_5">
                                <label class="form-check-label">
                                    Hipertermi
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_keperawatan_6" id="diag_keperawatan_6">
                                <label class="form-check-label">
                                    Kerusakan Integritas Kulit
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_keperawatan_7" id="diag_keperawatan_7">
                                <label class="form-check-label">
                                    Gangguan Perfusi Jaringan
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_keperawatan_8" id="diag_keperawatan_8">
                                <label class="form-check-label">
                                    Body Image
                                </label>
                            </div>
                        </div>
                        <!-- Kolom 3 -->
                        <div class="col-md-4">
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_keperawatan_9" id="diag_keperawatan_9">
                                <label class="form-check-label">
                                    Gangguan Mobilitas Fisik
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_keperawatan_10" id="diag_keperawatan_10">
                                <label class="form-check-label">
                                    Kurang Pengetahuan
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary" type="checkbox" value="1" name="diag_keperawatan_11" id="diag_keperawatan_11">
                                <label class="form-check-label">
                                    Perubahan Nutrisi Kurang dari Kebutuhan
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input type="text" class="form-control form-control-sm" id="diag_lain" name="diag_lain" placeholder="Masalah Keperawatan Lainnya">
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label fw-semibold">
                            Rencana Asuhan Keperawatan
                        </label>
                        <textarea class="form-control" name="rencana_asuhan_keperawatan" id="rencana_asuhan_keperawatan" rows="3" placeholder="Tuliskan rencana asuhan keperawatan..."></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-footer">
        <button class="btn btn-secondary">
            <i class="ri-close-line me-1"></i> Batal
        </button>
        <button class="btn btn-success" onclick="saveDataPengkajianRJOp(this)">
            <i class="ri-save-line me-1"></i> Simpan Pengkajian
        </button>
    </div>
</div>

<script>
    $(document).ready(function () {
        const $section = $('#rjo_perawat');
        // Sembunyikan textarea saat pertama kali
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

        loadDataPengkajianRJOp();

    });

    function loadDataPengkajianRJOp() {
        const kunjungan = $('#rjo_perawat').data('kunjungan');

        $.ajax({
            url: `/api/v2/emr/form/pengkajian/rjo/pr/get/${kunjungan}`,
            type: 'GET',
            success:function(res){
                isiFormPengkajianRJOp(res);
            }
        });
    }

    function isiFormPengkajianRJOp(data){

        // ======================================================
        // TANDA VITAL
        // ======================================================

        $('#anm_ku').val(data.anm_ku);

        $('input[name="alat_bantu_fungsional"][value="' + data.alat_bantu_fungsional + '"]')
            .prop('checked', true);

        $('#alat_bantu').val(data.alat_bantu);

        $('input[name="cacat_tubuh"][value="' + data.cacat_tubuh + '"]')
            .prop('checked', true);

        $('#ket_cacat_tubuh').val(data.ket_cacat_tubuh);

        // DIAGNOSIS KEPERAWATAN
        for (let i = 1; i <= 11; i++) {

            $('#diag_keperawatan_' + i).prop(
                'checked',
                data['diag_keperawatan_' + i] == 1
            );
        }
        setValIfExists('#diag_lain', data.diag_lain);

        // RENCANA ASUHAN KEPERAWATAN
        $('#rencana_asuhan_keperawatan').val(
            data.rencana_asuhan_keperawatan || ''
        );


    }

    function saveDataPengkajianRJOp(btn) {
        const $button = $(btn);
        const $section = $('#rjo_perawat');

        const data = getFormDataByName($section, {
            NOKUNJ: $section.data('kunjungan')
        });

        $.ajax({
            url: '/api/v2/emr/form/pengkajian/rjo/pr/simpan',
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
