<div class="card table-card border shadow-none">
    <div class="card-header pb-0 pt-2">
        <ul class="nav nav-tabs profile-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#formlayanankfr"
                type="button" role="tab" aria-controls="formlayanankfr" aria-selected="true">Form Layanan</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#formjadwalpelayanan"
                type="button" role="tab" aria-controls="formjadwalpelayanan" aria-selected="true">Jadwal Pelayanan</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#riwayatkfr"
                    type="button" role="tab" aria-controls="riwayatkfr" aria-selected="false">Riwayat</button>
            </li>
        </ul>
    </div>
    <div class="card-body p-3">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade active show" id="formlayanankfr" role="tabpanel" aria-labelledby="home-tab">
                <div id="previewformkfr" hidden>
                    preview
                </div>
                <div id="allformkfr" hidden>
                    <div id="formLama" hidden>
                        <div class="form-floating mb-3">
                            <select class="form-select" id="filterformlama" aria-label="Floating label select example" disabled>
                                <option selected="">...</option>
                            </select>
                            <label for="floatingSelect">Formulir Layanan KFR Yang Tersedia</label>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <button class="btn btn-primary"><i class="fas fa-lock me-1"></i> Tetapkan Formulir Terpilih</button>
                            <button class="btn btn-secondary" onclick="showFormBaru()"><i class="fas fa-file-signature me-1"></i> Buat Formulir Baru</button>
                        </div>
                    </div>
                    <div class="row" id="formBaru" hidden>
                        <div class="col-md-12 d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">Formulir Ini Diisi Oleh Dokter Sp.KFR</h5>
                            <button class="btn btn-sm btn-light-secondary" onclick="showFormLama()"><i class="fab fa-wpforms me-1"></i> Lihat Formulir Lama</button>
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
                                    <label for="diagfungsi">Diagnosis Fungsi (ICD-10) <a class="text-danger">*</a></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12"><hr class="mt-0"></div>
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
                                    <label for="fisik">Pemeriksaan Fisik dan Uji Fungsi <a class="text-danger">*</a></label>
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
                                    <label for="tatalaksana">Tata Laksana KFR (ICD 9 CM) <a class="text-danger">*</a></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12"><hr class="mt-0"></div>
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
                                <label class="mb-2">Suspek Penyakit Akibat Kerja <a class="text-danger">*</a></label>
                                <div class="row">
                                    <div class="col-md-1">
                                        <div class="form-check mt-3 mb-3">
                                            <input class="form-check-input" type="radio" name="suspek" value="1" id="suspek1">
                                            <label class="form-check-label" for="flexCheckDefault"> Ya </label>
                                        </div>
                                    </div>
                                    <div class="col-md-11" id="showsuspekya" hidden>
                                        <div class="form-floating mb-0">
                                            <input type="text" class="form-control" id="suspekya" placeholder="">
                                            <label for="suspekya">Penjelasan <a class="text-danger">*</a></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="radio" name="suspek" value="0" id="suspek0" checked>
                                    <label class="form-check-label" for="flexCheckDefault"> Tidak </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <div class="position-relative overflow-hidden">
                                    <canvas id="signature-pad-kfr" class="w-100 border rounded" style="height: 200px;"></canvas>
                                    <div id="placeholder-ttd"
                                        class="position-absolute top-50 start-50 translate-middle text-muted text-center"
                                        style="pointer-events: none; opacity: 0.3;">
                                        Tanda Tangan Persetujuan<br>Pasien / Keluarga Pasien
                                    </div>
                                    <button id="clear" class="btn btn-sm btn-danger position-absolute align-middle" style="top: 10px; right: 10px; z-index: 10;" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ulangi Tanda Tangan">
                                        <i class="ph-duotone ph-signature"></i>
                                    </button>
                                </div>
                                <input class="form-control" type="hidden" id="tte_formkfr" value="">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row align-items-center justify-content-between g-3">
                                <div class="col-sm-auto">
                                    <button class="btn btn-light-secondary" onclick="kosongi()"><i class="fas fa-edit me-1"></i> Kosongkan Formulir</button>
                                </div>
                                <div class="col-sm-auto btn-page">
                                    <button class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Formulir</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="formjadwalpelayanan" role="tabpanel" aria-labelledby="profile-tab">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group mb-3">
                            <div class="form-floating mb-0">
                                <textarea class="form-control" id="diagmedis" style="height: 50px" placeholder="Terisi Otomatis" disabled></textarea>
                                <label for="diagmedis">Diagnosis Medis (ICD-10) <a class="text-danger">*</a></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <div class="form-floating">
                                <textarea class="form-control" id="tatalaksana" style="height: 50px" placeholder="Terisi Otomatis" disabled></textarea>
                                <label for="tatalaksana">Permintaan Terapi <a class="text-danger">*</a></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <div class="form-floating">
                                <textarea class="form-control" id="program" style="height: 100px" placeholder=""></textarea>
                                <label for="tatalaksana">Program <a class="text-danger">*</a></label>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-0">
                    <div class="col-md-4 mb-3">
                        <div class="form-group" id="padjp1">
                            <div class="position-relative overflow-hidden">
                                <canvas id="signature-pad-jp" class="w-100 border rounded" style="height: 200px;"></canvas>
                                <div id="placeholder-ttd"
                                    class="position-absolute top-50 start-50 translate-middle text-muted"
                                    style="pointer-events: none; opacity: 0.3;">
                                    Tanda tangan Pasien / Keluarga Pasien
                                </div>
                                <button id="clear" class="btn btn-sm btn-danger position-absolute align-middle" style="top: 10px; right: 10px; z-index: 10;" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ulangi Tanda Tangan">
                                    <i class="ph-duotone ph-signature"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="riwayatkfr" role="tabpanel" aria-labelledby="profile-tab">
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
            </div>
        </div>
    </div>
</div>

<script>
    let signaturePad = null; // Global
    let canvas = null;

    $(document).ready(function() {
        $('input[type=radio][name=suspek]').change(function() {
            var selectedValue = $(this).val();
            $('#suspekya').val('');
            if (selectedValue == 1) {
                $('#showsuspekya').prop('hidden',false);
            } else {
                $('#showsuspekya').prop('hidden',true);
            }
        });

        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).data('bsTarget');
            if (target === '#formlayanankfr' || target === '#frehab') {
                showTTE();
                validPageFormKfr();
            }
        });

        $('button[data-bs-toggle="tab"]').on('hidden.bs.tab', function (e) {
            var target = $(e.target).data('bsTarget');
            if (target === '#formlayanankfr') {
                destroyTTE();
            }
        });

    });

    function validPageFormKfr() {
        $.ajax({
            url: `/api/emr/{{ $list['show']->NORM }}/fkfr/{{ $list['KUNJUNGAN'] }}`,
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.form) {
                    $('#previewformkfr').prop('hidden',false);
                    $('#allformkfr').prop('hidden',true);
                } else {
                    $('#previewformkfr').prop('hidden',true);
                    $('#allformkfr').prop('hidden',false);
                    if (res.show.length != 0) {
                        $('#filterformlama').prop('disabled',false);
                        $("#filterformlama").find('option').remove();
                        $("#filterformlama").append(`
                            <option value="" selected>Pilih Salah Satu</option>
                        `);
                        res.show.forEach(pouch => {
                            $("#filterformlama").append(`
                                <option value="${pouch.id}">${pouch.rm}</option>
                            `);
                        });

                        showFormLama();
                    } else {
                        $('#filterformlama').prop('disabled',true);
                        $("#filterformlama").find('option').remove();
                        $("#filterformlama").append(`
                            <option value="" selected>Tidak Ditemukan</option>
                        `);

                        showFormBaru();
                    }
                }
                // console.log(res);
            }, error: function(xhr, status, error) {
                console.error('Terjadi kesalahan:', error);
            }
        })
    }

    function showTTE() {
        console.log('Init SignaturePad...');
        canvas = document.getElementById('signature-pad-kfr');
        signaturePad = new SignaturePad(canvas);
        resizeCanvas();

        $('#clear').on('click', function () {
            signaturePad.clear();
            $('#placeholder-ttd').show();
        });

        // Hide placeholder kalau mulai tanda tangan
        signaturePad.onBegin = function () {
            $('#placeholder-ttd').hide();
        };

        $(window).off('resize.signature').on('resize.signature', resizeCanvas);
    }

    function destroyTTE() {
        if (signaturePad) {
            signaturePad.off(); // Versi SignaturePad modern ada off(), tapi kalau tidak, skip
            signaturePad = null;
        }
        $(window).off('resize.signature');
        console.log('SignaturePad destroyed!');
    }

    function resizeCanvas() {
        if (!canvas) return;
        var ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
    }

    function showFormulirKfr() {
    }

    function showJadwalPelayanan() {
        $.ajax({
            url: `/api/emr/{{ $list['KUNJUNGAN'] }}/jp`,
            type: 'GET',
            dataType: 'json',
            success: function(res) {

            }, error: function(xhr, status, error) {
                console.error('Terjadi kesalahan:', error);
            }
        })
    }

    function showFormBaru() {
        $('#formLama').prop('hidden',true);
        $('#formBaru').prop('hidden',false);
        kosongi();
    }

    function showFormLama() {
        $('#formLama').prop('hidden',false);
        $('#formBaru').prop('hidden',true);
        kosongi();
    }

    function kosongi() {
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
        signaturePad.clear();
    }
</script>
