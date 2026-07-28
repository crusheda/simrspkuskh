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
            <label class="form-label">Kriteria ATS (<i>Australasian Triage Scale</i>)</label>
            <select class="form-control" id="kriteria_ats">
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
            <label class="form-label">Resiko penularan infeksi</label>
            <div class="row">
                <div class="col">
                    <div class="form-check mb-2">
                        <input class="form-check-input check-primary" type="checkbox" id="rpi_1">
                        <label class="form-check-label" for="checkPrimary"> Batuk > 2 minggu dengan demam dan sesak nafas </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input check-primary" type="checkbox" id="rpi_2">
                        <label class="form-check-label" for="checkPrimary"> Rujukan dengan suspek (konfirmasi) airbone disease </label>
                    </div>
                </div>
                <div class="col">
                    <div class="form-check mb-2">
                        <input class="form-check-input check-primary" type="checkbox" id="rpi_3">
                        <label class="form-check-label" for="checkPrimary"> Tidak berisiko penularan airbone disease </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input check-primary" type="checkbox" id="rpi_4">
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
                <input class="form-check-input check-primary" type="checkbox" id="a_auto" checked="">
                <label class="form-check-label" for="checkPrimary"> Autoanamnesis (tanya jawab langsung dengan pasien) </label>
            </div>
            <div class="form-check mb-2">
                <input class="form-check-input check-primary" type="checkbox" id="a_allo">
                <label class="form-check-label" for="checkPrimary"> Alloanamnesis (tanya jawab dengan keluarga atau orang lain) </label>
            </div>
        </div>
    </div>
</div>
