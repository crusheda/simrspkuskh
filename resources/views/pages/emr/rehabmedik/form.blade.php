<div class="card table-card border shadow-none">
    <div class="card-header pb-0 pt-2">
        <ul class="nav nav-tabs profile-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#formlayanankfr" type="button"
                    role="tab" aria-controls="formlayanankfr" aria-selected="true">Form Layanan KFR</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#formjadwalpelayanan" type="button"
                    role="tab" aria-controls="formjadwalpelayanan" aria-selected="true">Jadwal Pelayanan</button>
            </li>
            {{-- <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#riwayatkfr" type="button" role="tab"
                    aria-controls="riwayatkfr" aria-selected="false">Riwayat</button>
            </li> --}}
        </ul>
    </div>
    <div class="card-body p-3">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade active show" id="formlayanankfr" role="tabpanel" aria-labelledby="home-tab">
                <div id="previewformkfr" hidden></div>
                <div id="allformkfr" hidden>
                    <div id="formLamaKfr" hidden>
                        <div class="form-floating mb-3">
                            <select class="form-select" id="filterformLamaKfr"
                                aria-label="Floating label select example" disabled>
                                <option selected="">...</option>
                            </select>
                            <label for="floatingSelect">Formulir Layanan KFR Yang Tersedia</label>
                        </div>
                        <div class="row" id="formLamaKfrPick" hidden>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <div class="form-floating">
                                        <textarea class="form-control" id="anamnesa_pick" style="height: 100px" disabled></textarea>
                                        <label for="anamnesa">Anamnesa <a class="text-danger">*</a></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <div class="form-floating">
                                        <textarea class="form-control" id="fisik_pick" style="height: 100px" disabled></textarea>
                                        <label for="fisik">Pemeriksaan Fisik dan Uji Fungsi <a
                                                class="text-danger">*</a></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <div class="form-floating">
                                        <textarea class="form-control" id="penunjang_pick" style="height: 100px" disabled></textarea>
                                        <label for="penunjang">Pemeriksaan Penunjang <a class="text-danger">*</a></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <div class="form-floating">
                                        <textarea class="form-control" id="tatalaksana_pick" style="height: 100px" disabled></textarea>
                                        <label for="tatalaksana">Tata Laksana KFR (ICD 9 CM) <a
                                                class="text-danger">*</a></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <hr class="mt-0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <div class="form-floating mb-0">
                                        <input type="text" class="form-control" id="anjuran_pick" placeholder="" disabled>
                                        <label for="anjuran">Anjuran <a class="text-danger">*</a></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <div class="form-floating mb-0">
                                        <input type="text" class="form-control" id="evaluasi_pick" placeholder="" disabled>
                                        <label for="evaluasi">Evaluasi <a class="text-danger">*</a></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group mb-3">
                                    <div class="form-floating mb-0">
                                        <input type="text" class="form-control" id="target_pick" placeholder="" disabled>
                                        <label for="target">Target <a class="text-danger">*</a></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group mb-3">
                                    <div class="form-floating mb-0">
                                        <input type="text" class="form-control" id="suspekya_pick" placeholder="" disabled>
                                        <label for="suspekya">Suspek Penyakit Akibat Kerja <a class="text-danger">*</a></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <button class="btn btn-secondary" onclick="showformBaruKfr()">
                                <i class="fas fa-file-signature me-1"></i> Buat Formulir Baru
                            </button>
                            <button class="btn btn-primary" onclick="simpanFormulirKfrLama()">
                                <i class="fas fa-lock me-1"></i> Tetapkan Formulir Terpilih
                            </button>
                        </div>
                    </div>
                    <div class="row" id="formBaruKfr" hidden>
                        <div class="col-md-12 d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">Formulir Ini Diisi Oleh Dokter Sp.KFR</h5>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <div class="form-floating mb-0">
                                    <input type="text" class="form-control" id="diagmedis" placeholder="">
                                    <label for="diagmedis">Diagnosis Medis (ICD-10) <a class="text-danger">*</a></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <div class="form-floating mb-0">
                                    <input type="text" class="form-control" id="diagfungsi" placeholder="">
                                    <label for="diagfungsi">Diagnosis Fungsi (ICD-10) <a
                                            class="text-danger">*</a></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr class="mt-0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <div class="form-floating">
                                    <textarea class="form-control" id="anamnesa" style="height: 100px"></textarea>
                                    <label for="anamnesa">Anamnesa <a class="text-danger">*</a></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <div class="form-floating">
                                    <textarea class="form-control" id="fisik" style="height: 100px"></textarea>
                                    <label for="fisik">Pemeriksaan Fisik dan Uji Fungsi <a
                                            class="text-danger">*</a></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <div class="form-floating">
                                    <textarea class="form-control" id="penunjang" style="height: 100px"></textarea>
                                    <label for="penunjang">Pemeriksaan Penunjang <a class="text-danger">*</a></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <div class="form-floating">
                                    <textarea class="form-control" id="tatalaksana" style="height: 100px"></textarea>
                                    <label for="tatalaksana">Tata Laksana KFR (ICD 9 CM) <a
                                            class="text-danger">*</a></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr class="mt-0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <div class="form-floating mb-0">
                                    <input type="text" class="form-control" id="anjuran" placeholder="">
                                    <label for="anjuran">Anjuran <a class="text-danger">*</a></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <div class="form-floating mb-0">
                                    <input type="text" class="form-control" id="evaluasi" placeholder="">
                                    <label for="evaluasi">Evaluasi <a class="text-danger">*</a></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group mb-3">
                                <div class="form-floating mb-0">
                                    <input type="text" class="form-control" id="target" placeholder="">
                                    <label for="target">Target <a class="text-danger">*</a></label>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label class="mb-2">Suspek Penyakit Akibat Kerja <a
                                        class="text-danger">*</a></label>
                                <div class="row">
                                    <div class="col-md-1">
                                        <div class="form-check mt-3 mb-3">
                                            <input class="form-check-input" type="radio" name="suspek"
                                                value="1" id="suspek1">
                                            <label class="form-check-label" for="flexCheckDefault"> Ya </label>
                                        </div>
                                    </div>
                                    <div class="col-md-11" id="showsuspekya" hidden>
                                        <div class="form-floating mb-0">
                                            <input type="text" class="form-control" id="suspekya"
                                                placeholder="">
                                            <label for="suspekya">Penjelasan <a class="text-danger">*</a></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="radio" name="suspek" value="0"
                                        id="suspek0" checked>
                                    <label class="form-check-label" for="flexCheckDefault"> Tidak </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group" id="padkfr">
                                <div class="position-relative overflow-hidden">
                                    <canvas id="signature-pad-kfr" class="w-100 border rounded"
                                        style="height: 200px;"></canvas>
                                    <div id="placeholder-ttd-kfr"
                                        class="position-absolute top-50 start-50 translate-middle text-muted"
                                        style="pointer-events: none; opacity: 0.3;">
                                        Tanda tangan KFR
                                    </div>
                                    <button id="clear-kfr"
                                        class="btn btn-sm btn-danger position-absolute align-middle"
                                        style="top: 10px; right: 10px; z-index: 10;">
                                        <i class="ti ti-writing-sign"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row align-items-center justify-content-between g-3">
                                <div class="col-sm-auto">
                                    <div class="btn-group">
                                        <button class="btn btn-warning" onclick="kosongiKfr()">
                                            <i  class="fas fa-edit me-1"></i> Kosongkan Formulir
                                        </button>
                                        <button class="btn btn-secondary" onclick="showformLamaKfr()">
                                            <i class="fab fa-wpforms me-1"></i> Lihat Formulir Yang Ada
                                        </button>
                                    </div>
                                </div>
                                <div class="col-sm-auto btn-page">
                                    <button class="btn btn-primary" onclick="simpanFormulirKfrBaru()">
                                        <i class="fas fa-save me-1"></i> Simpan Formulir Baru
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="formjadwalpelayanan" role="tabpanel" aria-labelledby="profile-tab">
                <div id="hideFormJp" class="text-center mt-2" hidden>
                    <h5>Formulir Layanan KFR tidak ditemukan pada kunjungan ini. Silakan melakukan Pengisian pada Menu Form Layanan KFR terlebih dahulu.</h5>
                </div>
                <div id="showFormJp" hidden>
                    <h5 class="text-start">Form <b class="text-primary">Program Pelayanan</b></h5>
                    <input type="text" class="form-control" id="groupid" hidden>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <div class="form-floating mb-0">
                                    <textarea class="form-control" id="diagmedis_jp" style="height: 50px" placeholder="Terisi Otomatis" readonly>Terisi Otomatis oleh Sistem</textarea>
                                    <label for="diagmedis">Diagnosis Medis (ICD-10) <a class="text-danger">*</a></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <div class="form-floating">
                                    <textarea class="form-control" id="tatalaksana_jp" style="height: 50px" placeholder="Terisi Otomatis" readonly>Terisi Otomatis oleh Sistem</textarea>
                                    <label for="tatalaksana">Permintaan Terapi <a class="text-danger">*</a></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-0">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label class="form-label">Tanggal Pelayanan <a class="text-danger">*</a></label>
                                        <div class="input-group">
                                            <input type="text" id="filter_tgl_jp" class="form-control flatpickr-input" placeholder="Pilih Rentang Tanggal">
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
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col mb-3">
                                    <div class="form-group" id="padjpp">
                                        <div class="position-relative overflow-hidden">
                                            <canvas id="signature-pad-jpp" class="w-100 border rounded"
                                                style="height: 200px;"></canvas>
                                            <div id="placeholder-ttd-jpp"
                                                class="position-absolute top-50 start-50 translate-middle text-muted"
                                                style="pointer-events: none; opacity: 0.3;">
                                                Tanda tangan Pasien <a class="text-danger">*</a>
                                            </div>
                                            <button id="clear-jpp" class="btn btn-sm btn-danger position-absolute align-middle"
                                                style="top: 10px; right: 10px; z-index: 10;">
                                                <i class="ti ti-writing-sign"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col mb-3">
                                    <div class="form-group" id="padjpt">
                                        <div class="position-relative overflow-hidden">
                                            <canvas id="signature-pad-jpt" class="w-100 border rounded"
                                                style="height: 200px;"></canvas>
                                            <div id="placeholder-ttd-jpt"
                                                class="position-absolute top-50 start-50 translate-middle text-muted"
                                                style="pointer-events: none; opacity: 0.3;">
                                                Tanda tangan Terapis <a class="text-danger">*</a>
                                            </div>
                                            <button id="clear-jpt" class="btn btn-sm btn-danger position-absolute align-middle"
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
                                    <div class="btn-group">
                                        <button class="btn btn-warning" onclick="kosongiJp()"><i
                                                class="fas fa-edit me-1"></i> Kosongkan Formulir</button>
                                        <button class="btn btn-light-secondary" onclick="refreshRiwayatJp()"
                                            id="btn-refresh-riwayatjp" hidden>
                                            <i class="fas fa-sync me-1"></i> Refresh Riwayat
                                        </button>
                                    </div>
                                </div>
                                <div class="col-sm-auto btn-page">
                                    <button class="btn btn-primary" onclick="simpanFormulirJp()"><i
                                            class="fas fa-save me-1"></i> Simpan Formulir</button>
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
                                <th style="width: 60%;">PROGRAM PELAYANAN</th>
                                <th style="width: 15%;" class="text-center">TANGGAL</th>
                                <th style="width: 15%;" class="text-end">DITAMBAHKAN OLEH</th>
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
            </div>
            {{-- <div class="tab-pane fade" id="riwayatkfr" role="tabpanel" aria-labelledby="profile-tab">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>A</th>
                                <th>B</th>
                                <th>C</th>
                                <th>D</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>demo</td>
                                <td>/demo</td>
                                <td><span class="badge text-bg-danger">demo</span></td>
                                <td>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-eye f-20"></i>
                                    </a>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-edit f-20"></i>
                                    </a>
                                    <a href="#" class="avtar avtar-xs btn-link-secondary">
                                        <i class="ti ti-trash f-20"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div> --}}
        </div>
    </div>
</div>

<div class="modal animate__animated animate__rubberBand fade" id="modalHapusJp" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-simple modal-add-new-address modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    Form Hapus Program Pelayanan
                </h4>
            </div>
            <div class="modal-body">
                <input type="text" id="id_hapus_jp" hidden>
                <p style="text-align: justify;">Anda akan melakukan penghapusan Program Pelayanan tersebut, lakukanlah dengan hati-hati. Ceklis dibawah untuk melanjutkan penghapusan.</p>
                <label class="switch">
                    <input type="checkbox" class="switch-input" id="setujuhapusjp">
                    <span class="switch-toggle-slider">
                    <span class="switch-on"></span>
                    <span class="switch-off"></span>
                    </span>
                    <span class="switch-label">Anda siap menerima Risiko</span>
                </label>
            </div>
            <div class="col-12 text-center mb-4">
                <button type="submit" class="btn btn-danger me-sm-3 me-1" onclick="prosesHapusJp()"><i class="fa fa-trash me-1" style="font-size:13px"></i> Hapus</button>
                <button type="reset" class="btn btn-light-secondary" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times me-1" style="font-size:13px"></i> Batal</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Variabel Global
    let padKfr = null,
        padJpP = null,
        padJpT = null,
        filterTglJp = null;

    $(document).ready(function() {
        const today = new Date(); // Hari ini
        const fiveYearsAgo = new Date();
        fiveYearsAgo.setFullYear(today.getFullYear() - 5); // 5 tahun ke belakang
        filterTglJp = $(".flatpickr-input").flatpickr(
            {
                // enableTime: true,
                // dateFormat: "Y-m-d H:i",
                minDate: fiveYearsAgo, // Mulai dari 5 tahun yang lalu
                maxDate: today,        // Sampai hari ini
                dateFormat: 'Y-m-d',
                defaultDate: today
            }
        );

        $('input[type=radio][name=suspek]').change(function() {
            var selectedValue = $(this).val();
            $('#suspekya').val('');
            if (selectedValue == 1) {
                $('#showsuspekya').prop('hidden', false);
            } else {
                $('#showsuspekya').prop('hidden', true);
            }
        });

        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            const target = $(e.target).data('bsTarget');

            if (target === '#frehab' || target === '#formlayanankfr') {
                validPageFormKfr();
                console.log('jalan kfr');
            } else if (target === '#formjadwalpelayanan') {
                validPageFormJp();
                console.log('jalan jp');
            }
        });
    });

    function initPadKfr() {
        if (padKfr) return; // Sudah inisiasi
        const canvas = document.getElementById('signature-pad-kfr');
        padKfr = new SignaturePad(canvas);
        resizeCanvas(canvas);

        $('#clear-kfr').on('click', function() {
            padKfr.clear();
            $('#placeholder-ttd-kfr').show();
        });

        padKfr.onBegin = function() {
            $('#placeholder-ttd-kfr').hide();
        };

        $(window).on('resize.kfr', function() {
            resizeCanvas(canvas);
        });
    }

    function initPadJp() {
        if (!padJpP) {
            const canvasP = document.getElementById('signature-pad-jpp');
            padJpP = new SignaturePad(canvasP);
            resizeCanvas(canvasP);

            $('#clear-jpp').on('click', function() {
                padJpP.clear();
                $('#placeholder-ttd-jpp').show();
            });

            padJpP.onBegin = function() {
                $('#placeholder-ttd-jpp').hide();
            };

            $(window).on('resize.jpp', function() {
                resizeCanvas(canvasP);
            });
        }

        if (!padJpT) {
            const canvasT = document.getElementById('signature-pad-jpt');
            padJpT = new SignaturePad(canvasT);
            resizeCanvas(canvasT);

            $('#clear-jpt').on('click', function() {
                padJpT.clear();
                $('#placeholder-ttd-jpt').show();
            });

            padJpT.onBegin = function() {
                $('#placeholder-ttd-jpt').hide();
            };

            $(window).on('resize.jpt', function() {
                resizeCanvas(canvasT);
            });
        }
    }

    function resizeCanvas(canvas) {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext('2d').scale(ratio, ratio);
    }

    // FORMULIR LAYANAN KFR
    function showformBaruKfr() {
        $('#formLamaKfr').prop('hidden', true);
        $('#formBaruKfr').prop('hidden', false);
        kosongiKfr();
    }

    function showformLamaKfr() {
        $('#formLamaKfr').prop('hidden', false);
        $('#formBaruKfr').prop('hidden', true);
        kosongiKfr();
    }

    function validPageFormKfr() {
        $.ajax({
            url: `/api/emr/{{ $list['show']->NORM }}/fkfr/{{ $list['KUNJUNGAN'] }}`,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                $('#formLamaKfrPick').prop('hidden', true);
                if (res.form) {
                    showPreviewFormKfr(res.form.group);
                    $('#previewformkfr').prop('hidden', false);
                    $('#allformkfr').prop('hidden', true);
                } else {
                    $('#previewformkfr').prop('hidden', true);
                    $('#allformkfr').prop('hidden', false);
                    if (res.show.length != 0) {
                        $('#filterformLamaKfr').prop('disabled', false);
                        $("#filterformLamaKfr").find('option').remove();
                        $("#filterformLamaKfr").append(`
                            <option value="" selected hidden>Pilih Salah Satu</option>
                        `);
                        res.show.forEach(pouch => {
                            $("#filterformLamaKfr").append(`
                                <option value="${pouch.group}">RM. ${pouch.rm} | Tgl. ${pouch.tgl} | ${pouch.nama_dokter}</option>
                            `);
                        });

                        showformLamaKfr();
                    } else {
                        $('#filterformLamaKfr').prop('disabled', true);
                        $("#filterformLamaKfr").find('option').remove();
                        $("#filterformLamaKfr").append(`
                            <option value="" selected>Tidak Ditemukan</option>
                        `);

                        showformBaruKfr();
                    }
                }

                $('#filterformLamaKfr').change(function() {
                    $.ajax({
                        url: `/api/emr/fkfr/${$(this).val()}`,
                        type: 'GET',
                        dataType: 'json',
                        success: function(res) {
                            $('#formLamaKfrPick').prop('hidden', false);
                            // show value
                            $('#diagmedis_pick').val(res.diagnosa_medis);
                            $('#diagfungsi_pick').val(res.diagnosa_fungsi);
                            $('#anamnesa_pick').val(res.anamnesa);
                            $('#fisik_pick').val(res.pemeriksaan_fisik);
                            $('#penunjang_pick').val(res.pemeriksaan_penunjang);
                            $('#tatalaksana_pick').val(res.tata_laksana_kfr);
                            $('#anjuran_pick').val(res.anjuran);
                            $('#evaluasi_pick').val(res.evaluasi);
                            $('#target_pick').val(res.target);
                            if (res.spak_index == 1) {
                                $('#suspekya_pick').val(res.spak);
                            } else {
                                $('#suspekya_pick').val('-');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Terjadi kesalahan:', error);
                        }
                    })
                })
                // JALANKAN FUNCTION TTE
                initPadKfr();
                // showTTEKFR();

            },
            error: function(xhr, status, error) {
                console.error('Terjadi kesalahan:', error);
            }
        })
    }

    function showPreviewFormKfr(GROUP) {
        $('#previewformkfr').empty().append(`
            <div class="spinner-grow align-middle me-2" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            Memuat Data...
        `);

        fetch("/api/emr/fkfr/" + GROUP + "/preview")
            .then(response => {
                if (!response.ok) {
                    throw new Error('Formulir tidak ditemukan atau gagal diambil.');
                }
                return response.blob();
            })
            .then(blob => {
                // Buat object URL dari blob
                const fileURL = URL.createObjectURL(blob);

                // Tampilkan ke iframe dalam modal
                $('#previewformkfr').prop('hidden', false);
                $('#previewformkfr').empty().html(
                    `<iframe src="${fileURL}" width="100%" height="500px" frameborder="0" class="rounded"></iframe>`
                );
                $('#previewformkfr').append(`
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <button class="btn btn-warning" onclick="showPreviewFormKfr(${GROUP})">
                            <i class="fas fa-sync me-1"></i> Muat Ulang Laporan
                        </button>
                        <button class="btn btn-danger" onclick="hapusFormKfr()">
                            <i class="fas fa-trash me-1"></i> Hapus Formulir Kunjungan
                        </button>
                    </div>
                `);
            })
            .catch(error => {
                iziToast.error({
                    title: 'Maaf!',
                    message: 'Data Formulir tidak ditemukan atau gagal diproses.',
                    position: 'topRight'
                });
                console.error(error);
            });
    }

    function simpanFormulirKfrBaru() {
        const save = new FormData();

        save.append('nomor', "{{ $list['KUNJUNGAN'] }}");
        save.append('rm', "{{ $list['show']->NORM }}");
        save.append('diagmedis', $('#diagmedis').val().trim());
        save.append('diagfungsi', $('#diagfungsi').val().trim());
        save.append('anamnesa', $('#anamnesa').val().trim());
        save.append('fisik', $('#fisik').val().trim());
        save.append('penunjang', $('#penunjang').val().trim());
        save.append('tatalaksana', $('#tatalaksana').val().trim());
        save.append('anjuran', $('#anjuran').val().trim());
        save.append('evaluasi', $('#evaluasi').val().trim());
        save.append('target', $('#target').val().trim());
        save.append('suspek', $('input[name="suspek"]:checked').val());

        if ($('input[name="suspek"]:checked').val() == '1') {
            save.append('suspekya', $('#suspekya').val().trim());
        }

        if (padKfr && !padKfr.isEmpty()) {
            save.append('tte', padKfr.toDataURL('image/png'));
        } else {
            iziToast.warning({
                title: 'Tanda Tangan Kosong!',
                message: 'Silakan tanda tangan terlebih dahulu.',
                position: 'topRight'
            });
            return;
        }

        save.append('dokter', "{{ Auth::user()->ID }}");
        save.append('user', "{{ Auth::user()->NIP }}");

        let isFormValid = validateInput([{
                selector: '#diagmedis'
            },
            {
                selector: '#diagfungsi'
            },
            {
                selector: '#anamnesa'
            },
            {
                selector: '#fisik'
            },
            {
                selector: '#penunjang'
            },
            {
                selector: '#tatalaksana'
            },
            {
                selector: '#anjuran'
            },
            {
                selector: '#evaluasi'
            },
            {
                selector: '#target'
            },
        ]);

        $('#suspekya').removeClass('is-invalid is-valid');
        if ($('input[name="suspek"]:checked').val() === '1') {
            if ($('#suspekya').val().trim() === '') {
                $('#suspekya').addClass('is-invalid');
                isFormValid = false;
            } else {
                $('#suspekya').addClass('is-valid');
            }
        }

        if (!isFormValid) {
            iziToast.warning({
                title: 'Form Belum Lengkap!',
                message: 'Pastikan isian wajib tidak ada yang kosong.',
                position: 'topRight'
            });
            return;
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('api.emr.fkfr.simpanformbaru') }}",
            method: 'POST',
            data: save,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(res) {
                if (res.code === 200) {
                    iziToast.success({
                        title: 'Berhasil!',
                        message: res.message,
                        position: 'topRight'
                    });
                    validPageFormKfr();
                } else {
                    iziToast.warning({
                        title: 'API Error!',
                        message: res.message,
                        position: 'topRight'
                    });
                }
            },
            error: function(xhr, status, error) {
                iziToast.error({
                    title: 'Gagal!',
                    message: error,
                    position: 'topRight'
                });
            }
        });
    }

    function simpanFormulirKfrLama() {
        var group = $('#filterformLamaKfr').val();
        var nomor = "{{ $list['KUNJUNGAN'] }}";

        const save = new FormData();
        save.append('group', group);
        save.append('nomor', nomor);

        if (group == '') {
            iziToast.warning({
                title: 'Maaf!',
                message: 'Mohon untuk memilih formulir layanan KFR yang tersedia',
                position: 'topRight'
            });
        } else {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('api.emr.fkfr.simpanformlama') }}",
                method: 'POST',
                data: save,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(res) {
                    if (res.code === 200) {
                        iziToast.success({
                            title: 'Berhasil!',
                            message: res.message,
                            position: 'topRight'
                        });
                        validPageFormKfr();
                    } else {
                        iziToast.warning({
                            title: 'API Error!',
                            message: res.message,
                            position: 'topRight'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    iziToast.error({
                        title: 'Gagal!',
                        message: error,
                        position: 'topRight'
                    });
                }
            });
        }
    }

    function hapusFormKfr() {
        var nomor = "{{ $list['KUNJUNGAN'] }}";
        var id_user = "{{ Auth::user()->ID }}";

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: `/api/emr/fkfr/${nomor}/hapus/${id_user}`,
            type: 'DELETE',
            dataType: 'json',
            success: function(res) {
                iziToast.success({
                    title: 'Pesan Berhasil!',
                    message: `Formulir Layanan KFR kunjungan ini berhasil dihapus dari Grup Berkas Klaim pada `+res,
                    position: 'topRight'
                });
                validPageFormKfr();
            },
            error: function(xhr, status, error) {
                iziToast.error({
                    title: 'Gagal!',
                    message: error,
                    position: 'topRight'
                });
            }
        })
    }

    function kosongiKfr() {
        $('#diagmedis').val('');
        $('#diagfungsi').val('');
        $('#anamnesa').val('');
        $('#fisik').val('');
        $('#penunjang').val('');
        $('#tatalaksana').val('');
        $('#anjuran').val('');
        $('#evaluasi').val('');
        $('#target').val('');
        // $('#suspek1').prop('checked', false);
        $('#suspek0').prop('checked', true);
        $('#suspekya').val('');
        $('#showsuspekya').prop('hidden', true);
        if (padKfr) {
            padKfr.clear();
        }
        $('.form-control').removeClass('is-valid is-invalid');
    }

    // FORM JADWAL PELAYANAN
    function validPageFormJp() {
        if (window.dataTable) {
            window.dataTable.destroy();
        }
        $.ajax({
            url: `/api/emr/jp/{{ $list['KUNJUNGAN'] }}`,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.form_kfr) {
                    $('#hideFormJp').prop('hidden',true);
                    $('#showFormJp').prop('hidden',false);
                    $('#groupid').val(res.form_kfr.group);
                    $('#diagmedis_jp').val(res.form_kfr.diagnosa_medis);
                    $('#tatalaksana_jp').val(res.form_kfr.tata_laksana_kfr);

                    // JALANKAN FUNCTION TTE
                    initPadJp();

                    if (!res.form_jp || res.form_jp.length === 0) {
                        $('#btn-refresh-riwayatjp').prop('hidden',true);
                        $('#show_table_jp').prop('hidden',true);
                    } else {
                        $('#btn-refresh-riwayatjp').prop('hidden',false);
                        $('#show_table_jp').prop('hidden',false);
                        $("#tampil-tbody-jp").empty();
                        if (res.form_jp && Array.isArray(res.form_jp)) {
                            res.form_jp.forEach((item, index) => {
                                $("#tampil-tbody-jp").append(`
                                    <tr id="jpid_${item.id}">
                                        <td>
                                            <button class="btn btn-icon btn-danger avtar-s mb-0" onclick="hapusJp(${item.id})">
                                                <i class="fas fa-trash" style="font-size: 13px;"></i>
                                            </button>
                                        </td>
                                        <td>${index + 1}</td>
                                        <td>${item.program}</td>
                                        <td class="text-center">${formatTanggal(item.tgl)}</td>
                                        <td class="text-end">${item.nama_user}</td>
                                    </tr>
                                `);
                            })
                        }
                        // VANILLA TABLE
                        window.dataTable = new simpleDatatables.DataTable("#vantable_jp", {
                            sortable: true,
                            searchable: true,
                            perPage: 10,
                            perPageSelect: [10, 20, 50, 100, 300, 500],
                            fixedColumns: true,
                            firstLast: true,
                            layout: "both",
                            labels: {
                                placeholder: "Cari Program Kunjungan...",
                                perPage: "Jumlah baris per halaman",
                                noRows: "Tidak ada data Jadwal Pelayanan yang tersedia",
                                info: "Menampilkan {start} - {end} dari {rows} data",
                            },
                            columns: [
                                { select: 0, sortable: false },
                                { select: 1, sortable: false },
                                { select: 2, sortable: false },
                                { select: 3, sort: 'ASC' },
                                { select: 4, sortable: false },
                            ]
                        });
                        // Showing Tooltip
                        $('[data-bs-toggle="tooltip"]').tooltip('dispose');
                        $('.tooltip').remove();
                        $('[data-bs-toggle="tooltip"]').tooltip({
                            trigger : 'hover'
                        })
                    }
                } else {
                    $('#hideFormJp').prop('hidden',false);
                    $('#groupid').val('');
                    $('#diagmedis_jp').val('');
                    $('#tatalaksana_jp').val('');
                    $('#showFormJp').prop('hidden',true);
                }
            },
            error: function(xhr, status, error) {
                console.error('Terjadi kesalahan:', error);
            }
        })
    }

    function refreshRiwayatJp() {
        $('#btn-refresh-riwayatjp').find('i').addClass('fa-spin');
        $('#show_table_jp').prop('hidden',false);
        if (window.dataTable) {
            window.dataTable.destroy();
        }
        $("#tampil-tbody-jp").empty().append(`
            <tr style='font-size:13px'>
                <td colspan="15">
                    <center>
                        <div class="spinner-border spinner-border-sm" role="status"></div>
                    </center>
                </td>
            </tr>
        `);
        validPageFormJp();
        $('#btn-refresh-riwayatjp').find('i').removeClass('fa-spin');
    }

    function simpanFormulirJp() {
        var group = $('#groupid').val();
        var nomor = "{{ $list['KUNJUNGAN'] }}";
        var tgl = $('#filter_tgl_jp').val();
        var program = $('#program_jp').val();
        var id_user = "{{ Auth::user()->ID }}";

        const save = new FormData();
        save.append('group', group);
        save.append('nomor', nomor);
        save.append('tgl', tgl);
        save.append('program', program);
        save.append('id_user', id_user);

        if (padJpP && !padJpP.isEmpty()) {
            save.append('tte_p', padJpP.toDataURL('image/png'));
        } else {
            iziToast.warning({
                title: 'Tanda Tangan Pasien Kosong!',
                message: 'Silakan tanda tangan terlebih dahulu.',
                position: 'topRight'
            });
            return;
        }

        if (padJpT && !padJpT.isEmpty()) {
            save.append('tte_t', padJpT.toDataURL('image/png'));
        } else {
            iziToast.warning({
                title: 'Tanda Tangan Terapis Kosong!',
                message: 'Silakan tanda tangan terlebih dahulu.',
                position: 'topRight'
            });
            return;
        }

        if (group == '') {
            iziToast.warning({
                title: 'Maaf, Terjadi Error!',
                message: 'ID Group tidak ditemukan! Mohon refresh Browser Anda.',
                position: 'topRight'
            });
        } else {
            if (program == '' || tgl == '') {
                iziToast.warning({
                    title: 'Isian Program masih kosong!',
                    message: 'Mohon untuk mengisi Program Pelayanan Pasien dan Tanggal Program pada kunjungan saat ini terlebih dahulu.',
                    position: 'topRight'
                });
            } else {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('api.emr.jp.simpanJp') }}",
                    method: 'POST',
                    data: save,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(res) {
                        if (res.code === 200) {
                            iziToast.success({
                                title: 'Berhasil!',
                                message: res.message,
                                position: 'topRight'
                            });
                            kosongiJp();
                            validPageFormJp();
                        } else {
                            iziToast.warning({
                                title: 'API Error!',
                                message: res.message,
                                position: 'topRight'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        iziToast.error({
                            title: 'Gagal!',
                            message: error,
                            position: 'topRight'
                        });
                    }
                });
            }
        }
    }

    function hapusJp(id) {
        $("#id_hapus_jp").val(id);
        var inputs = document.getElementById('setujuhapusjp');
        inputs.checked = false;
        $('#modalHapusJp').modal('show');
    }

    function prosesHapusJp() {
        // SWITCH BTN HAPUS
        var checkboxHapus = $('#setujuhapusjp').is(":checked");
        if (checkboxHapus == false) {
            iziToast.error({
                title: 'Pesan Galat!',
                message: 'Mohon menyetujui untuk dilakukan penghapusan berkas tersebut',
                position: 'topRight'
            });
        } else {
            // PROSES HAPUS
            var id = $("#id_hapus_jp").val();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/api/emr/jp/hapus/"+id,
                type: 'DELETE',
                success: function(res) {
                    iziToast.success({
                        title: 'Pesan Berhasil!',
                        message: 'Program Pelayanan telah berhasil dihapus pada '+res,
                        position: 'topRight'
                    });
                    $('#modalHapusJp').modal('hide');
                    kosongiJp();
                    validPageFormJp();
                },
                error: function(res) {
                    iziToast.error({
                        title: 'API Error!',
                        message: 'Program Pelayanan Anda gagal dihapus',
                        position: 'topRight'
                    });
                }
            });
        }
    }

    function kosongiJp() {
        if (filterTglJp) {
            filterTglJp.setDate(new Date(), true);
        }
        $('#program_jp').val('');
        if (padJpP) {
            padJpP.clear();
        }
        if (padJpT) {
            padJpT.clear();
        }
        $('.form-control').removeClass('is-valid is-invalid');
    }
    /**
     * Cek field satu atau banyak, kalau kosong tambah class is-invalid.
     * @param {Array} fields - Array selector ID atau name
     * @returns {Boolean} true = valid, false = ada yang kosong
     */

    // FUNCTION PUBLIC
    function validateInput(fields) {
        var isValid = true;

        fields.forEach(f => {
            if (f.type === 'radio') {
                var val = $(`input[name="${f.selector}"]:checked`).val();
                var radios = $(`input[name="${f.selector}"]`);
                radios.removeClass('is-invalid is-valid');

                if (!val) {
                    radios.addClass('is-invalid');
                    isValid = false;
                } else {
                    radios.addClass('is-valid');
                }

            } else {
                var el = $(f.selector);
                var val = el.val();
                el.removeClass('is-invalid is-valid');

                if (!val || val.trim() === '') {
                    el.addClass('is-invalid');
                    isValid = false;
                } else {
                    el.addClass('is-valid');
                }
            }
        });

        return isValid;
    }

    function formatTanggal(tanggal) {
        const bulanIndo = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        const parts = tanggal.split('-'); // ["2025","08","12"]
        const tahun = parts[0];
        const bulan = parseInt(parts[1], 10) - 1; // index array
        const hari = parts[2];

        return `${parseInt(hari, 10)} ${bulanIndo[bulan]} ${tahun}`;
    }
</script>
