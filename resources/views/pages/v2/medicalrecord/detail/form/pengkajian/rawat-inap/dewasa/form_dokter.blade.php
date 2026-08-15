<div class="form-wrapper">
    <h1 class="display-6 mb-1 fs-27 fw-bold"><center>PENGKAJIAN AWAL <b class="text-danger">RAWAT INAP</b> <b class="text-warning">DEWASA</b></center></h1>
    <h1 class="display-6 mb-4 fs-18"><center>PENGKAJIAN MEDIS (<a class="text-danger">Diisi Oleh Dokter</a>)</center></h1>
    <div class="form-content">
        <div class="row">
            <div class="col-md-12">
                <h5>Anamnesis</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <h6>Keluhan Utama</h6>
                            <textarea class="form-control" name="ku" rows="1"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <h6>Riwayat Penyakit Dahulu</h6>
                            <textarea class="form-control" name="rpd" rows="1"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <h6>Riwayat Penyakit Sekarang</h6>
                            <textarea class="form-control" name="rps" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <h6>Riwayat Penyakit Keluarga</h6>
                            <div class="form-check form-check-inline mb-2">
                                <input class="form-check-input check-primary" type="checkbox" name="rpk_h">
                                <label class="form-check-label"> Hipertensi </label>
                            </div>
                            <div class="form-check form-check-inline mb-2">
                                <input class="form-check-input check-primary" type="checkbox" name="rpk_d">
                                <label class="form-check-label"> Diabetes Melitus </label>
                            </div>
                            <div class="form-check form-check-inline mb-2">
                                <input class="form-check-input check-primary" type="checkbox" name="rpk_p">
                                <label class="form-check-label"> Penyakit Jantung </label>
                            </div>
                            <div class="form-check form-check-inline mb-2">
                                <input class="form-check-input check-primary" type="checkbox" name="rpk_a">
                                <label class="form-check-label"> Asma </label>
                            </div>
                            <textarea class="form-control" name="rpk" rows="1"></textarea>
                        </div>
                        @include('pages.v2.medicalrecord.detail.form.pengkajian.components.riwayat_penggunaan_obat')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
