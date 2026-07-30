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
                                <input class="form-check-input" type="checkbox" id="an_auto">
                                <label class="form-check-label" for="an_auto">
                                    Autoanamnesis
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="an_allo">
                                <label class="form-check-label" for="an_allo">
                                    Alloanamnesis
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="anamnesis_oleh" placeholder="Oleh.....">
                        </div>
                    </div>
                    <div class="row align-items-center" id="anamnesis">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Keluhan Utama</label>
                            <textarea class="form-control" id="keluhan_utama" rows="3"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Riwayat Penyakit Sekarang</label>
                            <textarea class="form-control" id="rps" rows="3"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Riwayat Penyakit Dahulu</label>
                            <textarea class="form-control" id="rpd" rows="3"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Riwayat Alergi</label>
                            <div class="row mb-2">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="ra" id="ra_tidak" value="0">
                                        <label class="form-check-label" for="ra_tidak">
                                            Tidak
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="ra" id="ra_ya" value="1">
                                        <label class="form-check-label" for="ra_ya">
                                            Ya
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <textarea class="form-control" id="ra_des" rows="3" placeholder="Sebutkan...."></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Riwayat Penggunaan Obat</label>
                            <div class="row mb-2">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rpo" id="rpo_tidak" value="0">
                                        <label class="form-check-label" for="rpo_tidak">
                                            Tidak
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="rpo" id="rpo_ya" value="1">
                                        <label class="form-check-label" for="rpo_ya">
                                            Ya
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <textarea class="form-control" id="rpo_des" rows="3" placeholder="Sebutkan...."></textarea>
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
                            <textarea class="form-control" id="pfisik" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="row align-items-center" id="pemeriksaan_penunjang">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Pemeriksaan Penunjang</label>
                            <textarea class="form-control" id="penunjang" rows="3"></textarea>
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
                            <textarea class="form-control" id="diagnosis" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="row align-items-center" id="tolok_ukur">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Tolok Ukur / Sasaran yang Dicapai</label>
                            <textarea class="form-control" id="tu" rows="3"></textarea>
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
                            <textarea class="form-control" id="terapi" rows="3"></textarea>
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
                                            <input class="form-check-input check-primary" type="checkbox" id="me_1">
                                            <label class="form-check-label" for="checkPrimary"> Tanda dan gejala suatu penyakit </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input check-primary" type="checkbox" id="me_2">
                                            <label class="form-check-label" for="checkPrimary"> Hasil pemeriksaan </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input check-primary" type="checkbox" id="me_3">
                                            <label class="form-check-label" for="checkPrimary"> Diagnosis </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input check-primary" type="checkbox" id="me_4">
                                            <label class="form-check-label" for="checkPrimary"> Rencana penatalaksanaan penyakit </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input check-primary" type="checkbox" id="me_5">
                                            <label class="form-check-label" for="checkPrimary"> Tindakan dan tujuan terapi </label>
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
                                            <input class="form-check-input check-primary" type="checkbox" id="sie_1">
                                            <label class="form-check-label" for="checkPrimary"> Leaflet </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input check-primary" type="checkbox" id="sie_2">
                                            <label class="form-check-label" for="checkPrimary"> Lisan </label>
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
                                            <input class="form-check-input check-primary" type="checkbox" id="eval_1">
                                            <label class="form-check-label" for="checkPrimary"> Sudah Mengerti </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input check-primary" type="checkbox" id="eval_2">
                                            <label class="form-check-label" for="checkPrimary"> Re - Edukasi </label>
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
                                <input class="form-check-input" type="radio" name="tl" id="tl_mrs" value="0">
                                <label class="form-check-label" for="tl_mrs">
                                    MRS
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tl" id="tl_pulang" value="1">
                                <label class="form-check-label" for="tl_pulang">
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
                                        <select class="form-control" id="pri_ruang">
                                            <option value="">Pilih Jenis Ruang Perawatan</option>
                                            <option value="1">Perawatan Biasa</option>
                                            <option value="2">Perawatan Intensive</option>
                                            <option value="3">Perawatan Isolasi</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label class="form-label fw-bold">Jenis Perawatan</label>
                                        <select class="form-control" id="pri_perawatan">
                                            <option value="">Pilih Jenis Perawatan</option>
                                            <option value="1">Preventif (Mencegah)</option>
                                            <option value="2">Kuratif (Menolong)</option>
                                            <option value="3">Rehabilitatif (Rehabilitasi)</option>
                                            <option value="4">Paliatif (Meredakan)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">Tanggal</label>
                                    <div class="input-group">
                                        <input type="text" id="pri_tgl" class="form-control flatpickr-input active" placeholder="Pilih Rentang Tanggal" readonly="readonly">
                                        <span class="input-group-text"><i class="feather icon-calendar"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Indikasi</label>
                                    <textarea class="form-control" id="pri_indikasi" rows="3"></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Keterangan</label>
                                    <textarea class="form-control" id="pri_ket" rows="3"></textarea>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">DPJP</label>
                                    <input type="text" class="form-control" id="pri_dpjp" placeholder="Dokter Otomatis">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
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
                                    <input type="text" class="form-control" id="rujuk_mana" placeholder="Sebutkan....">
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
        <button class="btn btn-danger">
            <i class="ri-save-line me-1"></i> Simpan Pengkajian
        </button>
    </div>
</div>

<script>
    $(document).ready(function () {

        // Sembunyikan textarea saat pertama kali
        $('#ra_des').hide();
        $('#rpo_des').hide();
        $('#pri').hide();
        $('#rujuk_mana').hide();

        // Riwayat Alergi
        $('input[name="ra"]').change(function () {
            if ($('#ra_ya').is(':checked')) {
                $('#ra_des').slideDown();
            } else {
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
</script>
