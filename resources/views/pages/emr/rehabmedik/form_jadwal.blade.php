<div id="loading-jp"></div>
<div id="hideFormJp" class="text-center mt-2" hidden>
    <h5>Formulir <b class="text-primary">Jadwal Program Layanan KFR</b> tidak ditemukan pada kunjungan ini. Silakan
        melakukan Pengisian pada Menu Form Layanan KFR terlebih dahulu.</h5>
</div>
<div id="showFormJp" hidden>
    <h5 class="text-start">Form <b class="text-primary">Program Pelayanan</b></h5>
    <input type="text" class="form-control" id="groupid" hidden>
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="form-group">
                <div class="form-floating mb-0">
                    <textarea class="form-control" id="diagmedis_jp" style="height: 50px" placeholder="Terisi Otomatis" readonly disabled>...</textarea>
                    <label for="diagmedis">Diagnosis Medis (ICD-10) (<a class="text-danger">Terisi Otomatis oleh Sistem</a>)</label>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="form-group">
                <div class="form-floating">
                    <textarea class="form-control" id="tatalaksana_jp" style="height: 50px" placeholder="Terisi Otomatis" readonly disabled>...</textarea>
                    <label for="tatalaksana">Permintaan Terapi (<a class="text-danger">Terisi Otomatis oleh Sistem</a>)</label>
                </div>
            </div>
        </div>
    </div>
    <hr class="mt-0">
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-group">
                        <label class="form-label">Tanggal Pelayanan <a class="text-danger">*</a></label>
                        <div class="input-group">
                            <input type="text" id="filter_tgl_jp" class="form-control flatpickr-input-jp"
                                placeholder="Pilih Tanggal">
                            <span class="input-group-text"><i class="feather icon-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <div class="form-group">
                        <div class="form-floating">
                            <textarea class="form-control" id="program_jp" style="height: 100px" placeholder=""></textarea>
                            <label for="tatalaksana">Program Pelayanan <a class="text-danger">*</a></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row justify-center items-center text-center">
                <div class="col-6 mb-3 justify-center items-center text-center">
                    <div class="form-group" id="padjpp">
                        <!-- responsive wrapper -->
                        <div class="position-relative overflow-hidden"
                            style="width:100%; max-width:500px; height:200px;">

                            <!-- canvas ikut parent -->
                            <canvas id="signature-pad-jpp" class="border rounded w-100 h-100"></canvas>

                            <!-- placeholder di tengah -->
                            <div id="placeholder-ttd-jpp"
                                class="position-absolute top-50 start-50 translate-middle text-muted"
                                style="pointer-events: none; opacity: 0.3;">
                                Tanda tangan Pasien <a class="text-danger">*</a>
                            </div>

                            <!-- tombol clear dalam kotak -->
                            <button id="clear-jpp" class="btn btn-sm btn-danger position-absolute"
                                style="top: 10px; right: 10px; z-index: 10;">
                                <i class="ti ti-writing-sign"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-6 mb-3 justify-center items-center text-center">
                    <div class="form-group" id="padjpt">
                        <!-- responsive wrapper -->
                        <div class="position-relative overflow-hidden"
                            style="width:100%; max-width:500px; height:200px;">

                            <!-- canvas ikut parent -->
                            <canvas id="signature-pad-jpt" class="border rounded w-100 h-100"></canvas>

                            <!-- placeholder di tengah -->
                            <div id="placeholder-ttd-jpt"
                                class="position-absolute top-50 start-50 translate-middle text-muted"
                                style="pointer-events: none; opacity: 0.3;">
                                Tanda tangan Terapis <a class="text-danger">*</a>
                            </div>

                            <!-- tombol clear pojok kanan atas -->
                            <button id="clear-jpt" class="btn btn-sm btn-danger position-absolute"
                                style="top: 10px; right: 10px; z-index: 10;">
                                <i class="ti ti-writing-sign"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row align-items-center justify-content-between g-3">
                <div class="col-sm-auto btn-page">
                    <button class="btn btn-warning me-2" onclick="kosongiJp()">
                        <i class="fas fa-edit me-1"></i> Kosongkan Form
                    </button>
                    <button class="btn btn-light-secondary" onclick="refreshRiwayatJp()" id="btn-refresh-riwayatjp"
                        hidden>
                        <i class="fas fa-sync me-1"></i> Refresh Riwayat
                    </button>
                </div>
                <div class="col-sm-auto btn-page">
                    <button class="btn btn-success me-2" id="btn-preview-form-jp" hidden>
                        <i class="fas fa-file-contract me-1"></i> Preview Form
                    </button>
                    <button class="btn btn-primary" onclick="simpanFormulirJp()">
                        <i class="fas fa-save me-1"></i> Simpan Form Program
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="table-responsive mt-3" id="show_table_jp" hidden>
    <hr class="mt-0">
    <h5 class="text-center">Riwayat <b class="text-primary">Program Pelayanan</b></h5>
    <table class="table mb-0 table-hover table-display" id="vantable_jp">
        <thead>
            <tr>
                <th style="width: 5%;">AKSI</th>
                <th style="width: 5%;">NO</th>
                <th style="width: 15%;">KUNJUNGAN PASIEN</th>
                <th style="width: 55%;">PROGRAM PELAYANAN</th>
                <th style="width: 10%;" class="text-center">TANGGALPELAYANAN</th>
                <th style="width: 10%;" class="text-end">DITAMBAHKAN OLEH</th>
            </tr>
        </thead>
        <tbody id="tampil-tbody-jp">
            <tr style='font-size:13px'>
                <td colspan="5">
                    <center>
                        <div class="spinner-border spinner-border-sm" role="status"></div>
                    </center>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {

    });
</script>
