<div class="d-flex justify-content-between">
    <button type="button" class="btn btn-danger waves-effect waves-light collapsed" data-bs-toggle="collapse" id="btnDokter"
        data-bs-target="#gd_dokter" aria-expanded="false" aria-controls="gd_dokter"><i class="ri-stethoscope-line me-1"></i> Pengkajian Dokter</button>
    <button type="button" class="btn btn-success waves-effect waves-light collapsed" data-bs-toggle="collapse" id="btnPerawat"
        data-bs-target="#gd_perawat" aria-expanded="false" aria-controls="gd_perawat"><i class="ri-stethoscope-line me-1"></i> Pengkajian Keperawatan</button>
</div>
<div class="accordion mt-3" id="gdAccordion">
    <div class="multi-collapse collapse show" data-bs-parent="#gdAccordion" id="gd_dokter">
        <div class="form-wrapper">
            <div class="form-content">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="row row-cols-1 row-cols-md-5 g-3">
                            <div class="col">
                                <div class="alert alert-danger">
                                    <h6>ATS 1 (Merah) - Segera</h6>
                                    <ul class="mb-0">
                                        <li>Obstruksi jalan nafas</li>
                                        <li>Henti nafas / henti jantung</li>
                                        <li>Distres nafas berat</li>
                                        <li>Gangguan hemodinamik berat</li>
                                        <li>GCS < 8</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col">
                                <div class="alert alert-warning">
                                    <h6>ATS 2 (Kuning) - ≤ 10 Menit</h6>
                                    <ul class="mb-0">
                                        <li>Resiko obstruksi jalan nafas</li>
                                        <li>Distres nafas sedang</li>
                                        <li>Gangguan hemodinamik sedang</li>
                                        <li>GCS 9 - 12</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col">
                                <div class="alert alert-warning">
                                    <h6>ATS 3 (Kuning) - ≤ 30 Menit</h6>
                                    <ul class="mb-0">
                                        <li>Resiko obstruksi jalan nafas</li>
                                        <li>Distres nafas sedang</li>
                                        <li>Gangguan hemodinamik sedang</li>
                                        <li>GCS 9 - 12</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col">
                                <div class="alert alert-success">
                                    <h6>ATS 4 (Hijau) - ≤ 60 Menit</h6>
                                    <ul class="mb-0">
                                        <li>Resiko obstruksi jalan nafas</li>
                                        <li>Distres nafas sedang</li>
                                        <li>Gangguan hemodinamik sedang</li>
                                        <li>GCS 9 - 12</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col">
                                <div class="alert alert-success">
                                    <h6>ATS 5 (Hijau) - ≤ 120 Menit</h6>
                                    <ul class="mb-0">
                                        <li>Resiko obstruksi jalan nafas</li>
                                        <li>Distres nafas sedang</li>
                                        <li>Gangguan hemodinamik sedang</li>
                                        <li>GCS 9 - 12</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <h6>Kriteria ATS (<i>Australasian Triage Scale</i>)</h6>
                            <select class="form-control" id="ats">
                                <option value="">Pilih Kriteria ATS</option>
                                <option value="1">ATS 1 (Merah) - Segera</option>
                                <option value="2">ATS 2 (Kuning) - ≤ 10 Menit</option>
                                <option value="3">ATS 3 (Kuning) - ≤ 10 Menit</option>
                                <option value="4">ATS 4 (Hijau) - ≤ 30 Menit</option>
                                <option value="5">ATS 5 (Hijau) - ≤ 120 Menit</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <h6>Resiko penularan infeksi</h6>
                            <div class="row">
                                <div class="col">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="rpi" value="1">
                                        <label class="form-check-label" for="checkPrimary"> Batuk > 2 minggu dengan demam dan sesak nafas </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="rpi" value="2">
                                        <label class="form-check-label" for="checkPrimary"> Rujukan dengan suspek (konfirmasi) airbone disease </label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="rpi" value="3">
                                        <label class="form-check-label" for="checkPrimary"> Tidak berisiko penularan airbone disease </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input check-primary single-checkbox" type="checkbox" name="rpi" value="4">
                                        <label class="form-check-label" for="checkPrimary"> B - 20 </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <h6>Anamnesis</h6>
                        <div class="form-group">
                            <div class="form-check mb-2">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="anam" value="1" checked="">
                                <label class="form-check-label" for="checkPrimary"> Autoanamnesis (tanya jawab langsung dengan pasien) </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="anam" value="2">
                                <label class="form-check-label" for="checkPrimary"> Alloanamnesis (tanya jawab dengan keluarga atau orang lain) </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <h6>Keluhan Utama</h6>
                                    <textarea class="form-control" name="ku" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <h6>Riwayat Penyakit Sekarang</h6>
                                    <textarea class="form-control" name="rps" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="card card-body border border-dashed border-warning mb-1">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <h6>Pengkajian Primary Survey</h6>
                                        <div class="form-group">
                                            <div class="form-check form-check-inline mb-2">
                                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="pps" value="1">
                                                <label class="form-check-label" for="checkPrimary"> Baik </label>
                                            </div>
                                            <div class="form-check form-check-inline mb-2">
                                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="pps" value="2">
                                                <label class="form-check-label" for="checkPrimary"> Sedang </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="pps" value="3">
                                                <label class="form-check-label" for="checkPrimary"> Lemah </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <h6>Jalan Nafas (<i>Airways</i>)</h6>
                                        <div class="form-group">
                                            <div class="form-check form-check-inline mb-2">
                                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="jn" value="1">
                                                <label class="form-check-label" for="checkPrimary"> Palen </label>
                                            </div>
                                            <div class="form-check form-check-inline mb-2">
                                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="jn" value="2">
                                                <label class="form-check-label" for="checkPrimary"> Obstruksi Parsial </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="jn" value="3">
                                                <label class="form-check-label" for="checkPrimary"> Obstruksi Total </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <h6>Sirkulasi ( C )</h6>
                                        <div class="form-group">
                                            <label class="form-label">Nadi</label>
                                            <div class="input-group input-group-sm mb-2">
                                                <input type="text" class="form-control" name="nadi">
                                                <div class="input-group-text">X/menit, Reguler / Ireguler</div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Tekanan Darah</label>
                                            <div class="input-group input-group-sm mb-2">
                                                <input type="text" class="form-control" name="td_up">
                                                <div class="input-group-text"> / </div>
                                                <input type="text" class="form-control" name="td_down">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Suhu</label>
                                            <div class="input-group input-group-sm mb-2">
                                                <input type="text" class="form-control" name="suhu">
                                                <div class="input-group-text">°C</div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">SpO2</label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" name="spo2">
                                                <div class="input-group-text">%</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <h6>Kulit</h6>
                                        <div class="form-group">
                                            <div class="form-check form-check-inline mb-2">
                                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="kulit" value="1">
                                                <label class="form-check-label" for="checkPrimary"> Normal </label>
                                            </div>
                                            <div class="form-check form-check-inline mb-2">
                                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="kulit" value=2">
                                                <label class="form-check-label" for="checkPrimary"> Jaundice </label>
                                            </div>
                                            <div class="form-check form-check-inline mb-2">
                                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="kulit" value="3">
                                                <label class="form-check-label" for="checkPrimary"> Akral Dingin </label>
                                            </div>
                                            <div class="form-check form-check-inline mb-2">
                                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="kulit" value="4">
                                                <label class="form-check-label" for="checkPrimary"> Sianotik </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input check-primary single-checkbox" type="checkbox" name="kulit" value="5">
                                                <label class="form-check-label" for="checkPrimary"> Berkeringat </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <h6>Pernafasan (Breathing)</h6>
                                        <div class="form-group">
                                            <label class="form-label">Frekuensi</label>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="input-group input-group-sm flex-grow-1">
                                                    <input type="text" class="form-control" name="fr">
                                                    <span class="input-group-text">X/menit</span>
                                                </div>
                                                <div class="form-check m-0">
                                                    <input class="form-check-input single-checkbox" type="checkbox" name="fr_cb" value="1">
                                                    <label class="form-check-label" for="fr_simetris">
                                                        Simetris
                                                    </label>
                                                </div>
                                                <div class="form-check m-0">
                                                    <input class="form-check-input single-checkbox" type="checkbox" name="fr_cb" value="2">
                                                    <label class="form-check-label" for="fr_asimetris">
                                                        Asimetris
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <h6>Neorologi ( D )</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-2">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <label class="form-label mb-0">Pupil</label>

                                                        <div class="form-check m-0">
                                                            <input class="form-check-input single-checkbox" type="checkbox" name="pupil" value="1" id="pupil1">
                                                            <label class="form-check-label" for="pupil1">Isokor</label>
                                                        </div>

                                                        <div class="form-check m-0">
                                                            <input class="form-check-input single-checkbox" type="checkbox" name="pupil" value="2" id="pupil2">
                                                            <label class="form-check-label" for="pupil2">Anisokor</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <label class="form-label">Diameter</label>
                                                    <div class="input-group input-group-sm mb-2">
                                                        <input type="text" class="form-control" name="dia_up">
                                                        <div class="input-group-text"> mm / </div>
                                                        <input type="text" class="form-control" name="dia_down">
                                                    </div>
                                                </div>
                                                <div class="form-group mb-2">
                                                    <label class="form-label">RC</label>
                                                    <div class="input-group input-group-sm mb-2">
                                                        <input type="text" class="form-control" name="rc_up">
                                                        <div class="input-group-text"> / </div>
                                                        <input type="text" class="form-control" name="rc_down">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">VAS</label>
                                                    <input type="text" class="form-control form-control-sm" name="vas">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>GCS</h6>
                                                <div class="form-group mb-2">
                                                    <div class="row align-items-center g-2 mb-2">
                                                        <label class="col-sm-2 col-form-label">Eye</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control form-control-sm" name="gcs_e">
                                                        </div>
                                                    </div>
                                                    <div class="row align-items-center g-2 mb-2">
                                                        <label class="col-sm-2 col-form-label">Verbal</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control form-control-sm" name="gcs_v">
                                                        </div>
                                                    </div>
                                                    <div class="row align-items-center g-2 mb-2">
                                                        <label class="col-sm-2 col-form-label">Move</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control form-control-sm" name="gcs_m">
                                                        </div>
                                                    </div>
                                                    <div class="row align-items-center g-2 mb-2">
                                                        <label class="col-sm-2 col-form-label">Total</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control form-control-sm" name="gcs_t">
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
                    <div class="col-md-12 mb-3">
                        <div class="card card-body border border-dashed border-success mb-1">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <h6 class="mb-0">Status Reproduksi</h6>
                                <div class="form-check m-0">
                                    <input class="form-check-input single-checkbox" type="checkbox" name="sr" value="1">
                                    <label class="form-check-label" for="sr1">
                                        Kasus Obstetri Ginekologi
                                    </label>
                                </div>
                                <div class="form-check m-0">
                                    <input class="form-check-input single-checkbox" type="checkbox" name="sr" value="2">
                                    <label class="form-check-label" for="sr2">
                                        Tidak
                                    </label>
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <div class="form-check m-0">
                                                <input class="form-check-input single-checkbox" type="checkbox" name="sr_cb" value="1">
                                            </div>
                                            <label class="form-label mb-0" for="hpht">
                                                HPHT
                                            </label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" name="sr_hpht">
                                            </div>
                                            <label class="form-label mb-0" for="siklus">
                                                Siklus
                                            </label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" name="sr_siklus">
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <div class="form-check m-0">
                                                <input class="form-check-input single-checkbox" type="checkbox" name="sr_cb" value="2">
                                            </div>
                                            <label class="form-label mb-0" for="kb">
                                                KB
                                            </label>
                                            <input type="text" class="form-control" name="sr_kb">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <div class="form-check m-0">
                                                <input class="form-check-input single-checkbox" type="checkbox" name="sr_cb" value="4">
                                            </div>
                                            <label class="form-label mb-0">
                                                Hamil
                                            </label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">Gravida</span>
                                                <input type="text" class="form-control" name="sr_grv">
                                                <span class="input-group-text">Paritas</span>
                                                <input type="text" class="form-control" name="sr_prt">
                                                <span class="input-group-text">Abortus</span>
                                                <input type="text" class="form-control" name="sr_abr">
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="form-check m-0">
                                                <input class="form-check-input single-checkbox" type="checkbox" name="sr_cb" value="3">
                                            </div>
                                            <label class="form-label mb-0">
                                                Tidak Hamil
                                            </label>
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
    </div>
    <div class="multi-collapse collapse" data-bs-parent="#gdAccordion" id="gd_perawat">
        <div class="form-wrapper">
            <div class="form-content">
                form-perawat
            </div>
            <div class="form-footer">
                <button class="btn btn-secondary">
                    <i class="ri-close-line me-1"></i> Batal
                </button>
                <button class="btn btn-success">
                    <i class="ri-save-line me-1"></i> Simpan Pengkajian
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Jalankan saat pertama kali halaman dibuka
        updateButton();

        // Saat collapse dibuka
        $('.multi-collapse').on('shown.bs.collapse', function () {
            updateButton();
        });

        // Saat collapse ditutup
        $('.multi-collapse').on('hidden.bs.collapse', function () {
            updateButton();
        });

        // Hanya memperbolehkan checkbox dipilih salah satu saja
        $('.single-checkbox').on('change', function () {
            if (!this.checked) return;
            $('input.single-checkbox[name="' + this.name + '"]')
                .not(this)
                .prop('checked', false);
        });
    })

    // update btn collapse
    function updateButton() {
        $('#btnDokter').prop('disabled', $('#gd_dokter').hasClass('show'));
        $('#btnPerawat').prop('disabled', $('#gd_perawat').hasClass('show'));
    }
</script>
