<div class="form-wrapper">
    <h1 class="display-6 mb-4 fs-27"><center>PENGKAJIAN MEDIS (<a class="text-danger">Diisi Oleh Dokter</a>)</center></h1>
    <div class="form-content">
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <div class="form-group mb-2">
                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                            <strong><em>Subjective </em>(S) : </strong>
                        </h5>
                    </div>
                    <div class="row align-items-center mb-3" id="anamnesis_diperoleh">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Anamnesis</label>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="anam" value="1" checked="">
                                <label class="form-check-label">
                                    Autoanamnesis
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="anam" value="2">
                                <label class="form-check-label">
                                    Alloanamnesis
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="anamnesis_oleh" id="anamnesis_oleh" placeholder="Oleh.....">
                        </div>
                    </div>
                    <div class="row align-items-center" id="anamnesis">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Keluhan Utama</label>
                            <textarea class="form-control" name="keluhan_utama" id="keluhan_utama" rows="3"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Riwayat Penyakit Sekarang</label>
                            <textarea class="form-control" name="rps" id="rps" rows="3"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Riwayat Penyakit Dahulu</label>
                            <textarea class="form-control" name="rpd" id="rpd" rows="3"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Riwayat Alergi</label>
                            <div class="row mb-2">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="ra" id="ra_tidak" value="0">
                                        <label class="form-check-label">
                                            Tidak
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="ra" id="ra_ya" value="1">
                                        <label class="form-check-label">
                                            Ya
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <select class="form-control" name="ra_jenis" id="ra_jenis">
                                    <option value="">Pilih Jenis Alergi</option>
                                    <option value="1">Obat</option>
                                    <option value="2">Makanan</option>
                                    <option value="3">Udara</option>
                                </select>
                            </div>
                            <textarea class="form-control" name="ra_des" id="ra_des" rows="3" placeholder="Sebutkan...."></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Riwayat Penggunaan Obat</label>
                            <div class="row mb-2">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rpo" id="rpo_tidak" value="0">
                                        <label class="form-check-label">
                                            Tidak
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rpo" id="rpo_ya" value="1">
                                        <label class="form-check-label">
                                            Ya
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <textarea class="form-control" name="rpo_des" id="rpo_des" rows="3" placeholder="Sebutkan...."></textarea>
                        </div>
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
                    <div class="row align-items-center" id="pemeriksaan_penunjang">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Pemeriksaan Penunjang</label>
                            <textarea class="form-control" name="penunjang" id="penunjang" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                            <strong><em>Assessment </em>(A) : </strong>
                        </h5>
                    </div>
                    <div class="row align-items-center" id="diagnosis">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Diagnosis</label>
                            <textarea class="form-control" name="diagnosis" id="diagnosis" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="row align-items-center" id="tolok_ukur">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Tolok Ukur / Sasaran yang Dicapai</label>
                            <textarea class="form-control" name="tu" id="tu" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <h5 class="border-bottom pb-2 mb-3 text-primary">
                            <strong><em>Plan </em>(P) : </strong>
                        </h5>
                    </div>
                    <div class="row align-items-center" id="terapi_tindakan">
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
                                            <input class="form-check-input check-primary" type="checkbox" name="me_1" id="me_1">
                                            <label class="form-check-label"> Tanda dan gejala suatu penyakit </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input check-primary" type="checkbox" name="me_2" id="me_2">
                                            <label class="form-check-label"> Hasil pemeriksaan </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input check-primary" type="checkbox" name="me_3" id="me_3">
                                            <label class="form-check-label"> Diagnosis </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input check-primary" type="checkbox" name="me_4" id="me_4">
                                            <label class="form-check-label"> Rencana penatalaksanaan penyakit </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input check-primary" type="checkbox" name="me_5" id="me_5">
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
                                            <input class="form-check-input check-primary" type="checkbox" name="sie_2" id="sie_2">
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
                                            <input class="form-check-input check-primary" type="checkbox" name="eval_1" id="eval_1">
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
                <div class="card card-body border border-dashed border-success mb-1">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Tindak Lanjut :</label>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tl" id="tl_mrs" value="2">
                                <label class="form-check-label">
                                    MRS
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tl" id="tl_pulang" value="1">
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
                                        <select class="form-control" name="pri_perawatan id="pri_perawatan">
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
                                    <input type="text" class="form-control" name="pri_dpjp" id="pri_dpjp" value="{{ $list['dpjp']->NAMADOKTER ?? '' }}" readonly>
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
                                        <input class="form-check-input" type="radio" name="rujuk" id="rujuk_gizi" value="0">
                                        <label class="form-check-label">
                                            Ahli Gizi
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rujuk" id="rujuk_rehab" value="1">
                                        <label class="form-check-label">
                                            Rehabilitasi Medik
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rujuk" id="rujuk_sp" value="1">
                                        <label class="form-check-label">
                                            Klinik Spesialis
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rujuk" id="rujuk_lain" value="1">
                                        <label class="form-check-label">
                                            Lainnya
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="rujuk_mana" id="rujuk_mana" placeholder="Sebutkan....">
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
        <button class="btn btn-danger"  onclick="saveDataPengkajianRJD(this)">
            <i class="ri-save-line me-1"></i> Simpan Pengkajian
        </button>
    </div>
</div>

<script>
    $(document).ready(function () {

        // Sembunyikan textarea saat pertama kali
        $('#ra_jenis').hide();
        $('#ra_des').hide();
        $('#rpo_des').hide();
        $('#pri').hide();
        $('#rujuk_mana').hide();

        // Riwayat Alergi
        $('input[name="ra"]').change(function () {
            if ($('#ra_ya').is(':checked')) {
                $('#ra_des').slideDown();
                $('#ra_jenis').slideDown();
            } else {
                $('#ra_jenis').slideUp().val('');
                $('#ra_des').slideUp().val('');
            }
        });

        // Riwayat Penggunaan Obat
        $('input[name="rpo"]').change(function () {
            if ($('#rpo_ya').is(':checked')) {
                $('#rpo_des').slideDown();
            } else {
                $('#rpo_des').slideUp().val('');
            }
        });

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
                $('#rujuk_mana').slideDown();
            } else {
                $('#rujuk_mana').slideUp().val('');
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

    });
    function saveDataPengkajianRJD(btn) {
        const $button = $(btn);
        const $section = $('#rjd_dokter');

        const data = getFormDataByName($section, {
            NOKUNJ: $section.data('kunjungan')
        });

        $.ajax({
            url: '/api/emr/form/pengkajian/rjd/dr/simpan',
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
