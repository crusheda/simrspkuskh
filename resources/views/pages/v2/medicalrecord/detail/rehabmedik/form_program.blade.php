<div class="row">
    <div class="col-md-12" id="formJPel" hidden>
        <div class="card table-card border shadow-none">
            <div class="card-header d-flex align-items-center justify-content-between p-3">
                <h5 class="card-title mb-0"><i class="fas fa-file-contract me-1"></i> Form <b class="text-success">Jadwal Pelayanan Pasien</b></h5>
                <div>
                    Pengisian Formulir ini <b class="text-danger">Hanya Sekali Pada Setiap Kunjungan</b>, Pastikan Data <b class="text-success">Sudah Benar Sebelum Menyimpan</b>!
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between p-3">
                <div>
                    <button class="btn btn-subtle-info waves-effect waves-light btn-sm" onclick="lihatFormJadwalPelayanan()" id="btn-cetak-form-jp" data-bs-toggle="tooltip" title="Lihat Formulir Jadwal Pelayanan Program Terapi" disabled><i class="ti ti-file-invoice me-1"></i> Cetak Form</button>
                    <button class="btn btn-outline-warning border-dashed waves-effect waves-light btn-sm" onclick="generateUlangFormJadwalPelayanan()" id="btn-generate-form-jp" data-bs-toggle="tooltip" title="Generate Ulang Formulir Jadwal Pelayanan Program Terapi" disabled><i class="ti ti-file-symlink me-1"></i> Generate Ulang</button>
                </div>
                <div>
                    <button class="btn btn-subtle-danger btn-sm" onclick="hapusFormJadwalPelayanan()" id="btn-hapus-form-jp" data-bs-toggle="tooltip" title="Hapus Formulir Jadwal Pelayanan" disabled><i class="ti ti-trash me-1"></i> Hapus Form</button>
                    <button class="btn btn-success btn-sm" onclick="ttdPasienJadwalPelayanan()" id="btn-submit-form-jp" data-bs-toggle="tooltip" title="Tanda Tangan Jadwal Pelayanan Program Terapi" disabled><i class="ti ti-writing-sign me-1"></i> Tanda Tangan Pasien</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card table-card border shadow-none">
            <div class="card-header d-flex align-items-center justify-content-between p-3">
                <h5 class="card-title mb-0"><i class="fas fa-file-contract me-1"></i> Form <b class="text-primary">Program Terapi</b></h5>
                <div>
                    <a><i class="fas fa-exclamation-circle me-1"></i><i>Anda masuk sebagai <b><u class="text-primary">{{ Auth::user()->NAMA }}</u> (NIP#{{ Auth::user()->NIP }})</b></i></a>
                    <button class="btn btn-subtle-primary btn-sm ms-2" onclick="loadCpptProgramTerapi()" id="btn-refresh-riwayat-cppt-program" data-bs-toggle="tooltip" title="Refresh Riwayat CPPT"><i class="fas fa-sync"></i></button>
                    <button class="btn btn-subtle-success btn-sm" onclick="loadRiwayatProgramTerapi()" id="btn-refresh-riwayat-form-program" data-bs-toggle="tooltip" title="Refresh Riwayat Formulir Program Terapi"><i class="fas fa-sync"></i></button>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="row">
                    <input type="text" class="form-control" id="kode_program_terapi" hidden>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label"><i><b>Subjective</b></i> <a class="text-danger">*</a></label>
                            <textarea id="cppt_s_t" rows="4" class="form-control" placeholder="..."></textarea>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label"><i><b>Objective</b></i> <a class="text-danger">*</a></label>
                            <textarea id="cppt_o_t" rows="4" class="form-control" placeholder="..."></textarea>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label"><i><b>Assessment</b></i> <a class="text-danger">*</a></label>
                            <textarea id="cppt_a_t" rows="4" class="form-control" placeholder="..."></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label"><i><b>Procedure</b></i> <a class="text-danger">*</a></label>
                            <textarea id="cppt_p_t" rows="4" class="form-control" placeholder="..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer p-3">
                <div class="row align-items-center justify-content-between g-3">
                    <div class="col-sm-auto">
                        <button class="btn btn-outline-warning border-dashed waves-effect waves-light me-2" onclick="kosongiFormProgramTerapi()" id="btn-kosongi-form-program-terapi" hidden>
                            <i  class="fas fa-edit me-1"></i> Kosongkan Formulir
                        </button>
                        {{-- <button class="btn btn-danger me-2" onclick="deleteFormProgramTerapi()" id="btn-hapus-form-program-terapi" data-bs-toggle="tooltip" title="Hapus Formulir Program Terapi" hidden>
                            <i class="fas fa-trash"></i>
                        </button>
                        <button class="btn btn-warning me-2" onclick="generateFormProgramTerapi()" id="btn-generate-form-program-terapi" data-bs-toggle="tooltip" title="Generate Ulang Formulir Program Terapi" hidden>
                            <i class="fas fa-paper-plane"></i>
                        </button> --}}
                        <button class="btn btn-info me-2" onclick="showFormProgramTerapi()" id="btn-lihat-form-program-terapi" data-bs-toggle="tooltip" title="Lihat Berkas Formulir Program Terapi Saat Ini" hidden>
                            <i class="fas fa-book-open"></i>
                        </button>
                    </div>
                    <div class="col-sm-auto btn-page">
                        <button class="btn btn-primary" onclick="storeFormProgramTerapi()" id="btn-simpan-form-program-Terapi" disabled hidden>
                            <i class="fas fa-save me-1"></i> Simpan Formulir Baru
                        </button>
                        <button class="btn btn-subtle-dark" onclick="batalUpdateFormProgramTerapi()" id="btn-batal-update-form-program-terapi" data-bs-toggle="tooltip" title="Batalkan" hidden>
                            <i class="fas fa-reply me-1"></i>
                        </button>
                        <button class="btn btn-success" onclick="prosesEditProgramTerapi()" id="btn-update-form-program-Terapi" data-bs-toggle="tooltip" title="Perbarui Formulir & TTE Program Terapi" hidden>
                            <i class="fas fa-edit me-1"></i> Update Formulir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="accordion card" id="collapse-riwayat-program-kfr">
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading-riwayat-program-kfr">
                    <button class="accordion-button bg-success-subtle" type="button" data-bs-toggle="collapse" data-bs-target="#btn-collapse-riwayat-program-kfr" aria-expanded="true" aria-controls="collapseOne">
                        <i class="fas fa-sort-alpha-down me-1"></i> Riwayat Program Terapi <span class="badge bg-secondary-subtle text-secondary ms-1" data-bs-toggle="tooltip" title="Riwayat Program Terapi Diurutkan Dari Data Terbaru Khusus Hanya Kunjungan Saat Ini">By.KUNJUNGAN</span>
                    </button>
                </h2>
                <div id="btn-collapse-riwayat-program-kfr" class="accordion-collapse collapse show" aria-labelledby="heading-riwayat-program-kfr" data-bs-parent="#collapse-riwayat-program-kfr">
                    <div class="accordion-body rounded-bottom p-3" style="max-height: 613px; overflow-y: auto;">
                        <div id="load-riwayat-program-terapi">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-grow spinner-grow-sm me-2" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div> <a class="align-middle">Memproses Data Riwayat..</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion card" id="collapse-riwayat-cppt-program-kfr">
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading-riwayat-cppt-program-kfr">
                    <button class="accordion-button bg-primary-subtle" type="button" data-bs-toggle="collapse" data-bs-target="#btn-collapse-riwayat-cppt-program-kfr" aria-expanded="true" aria-controls="collapseOne">
                        <i class="fas fa-sort-amount-down me-1"></i> Riwayat CPPT <span class="badge bg-secondary-subtle text-secondary ms-1" data-bs-toggle="tooltip" title="Riwayat CPPT Diurutkan Dari Data Terbaru">By.NORM</span>
                    </button>
                </h2>
                <div id="btn-collapse-riwayat-cppt-program-kfr" class="accordion-collapse collapse show" aria-labelledby="heading-riwayat-cppt-program-kfr" data-bs-parent="#collapse-riwayat-cppt-program-kfr">
                    <div class="accordion-body rounded-bottom p-3" style="max-height: 613px; overflow-y: auto;">
                        <div id="load-riwayat-cppt-program-kfr">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-grow spinner-grow-sm me-2" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div> <a class="align-middle">Mengambil Data Riwayat..</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="modalPreviewJpel" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Preview <b class="text-primary">Jadwal Pelayanan Terapi</b> (<a id="showTxModalPJPel"><i class="fas fa-sync fa-spin ms-1 me-1 fs-15"></i></a>)
                </h5>
            </div>
            <div class="modal-body">
                <div id="prevFormJadwalPelayanan"></div>
            </div>
            <div class="modal-footer d-flex justify-content-between align-items-center">
                <button class="btn btn-outline-warning border-dashed waves-effect waves-light" onclick="generateUlangFormJadwalPelayanan()" data-bs-toggle="tooltip" title="Generate Ulang Formulir Jadwal Pelayanan Program Terapi"><i class="ti ti-file-symlink me-1"></i> Generate Ulang</button>
                <div>
                    <a id="showTxUserForm" class="text-dark me-2"></a>
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times me-1" style="font-size:13px"></i> Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="showFormProgramTerapi" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="showFormPTerapiLabel">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showFormPTerapiLabel"><span class="badge text-bg-secondary">Formulir Program Terapi</span> | IDKUNJUNGAN : <a id="show-id-form-programterapi" class="text-primary"></a></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div id="cetak-form-programterapi"></div>
            </div>
            <div class="modal-footer d-flex justify-content-between align-items-center">
                <h6 class="m-0">Silakan Generate Ulang<br>Apabila Formulir Gagal Dimuat</h6>
                <div>
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i> Tutup</button>
                    <button type="button" class="btn btn-warning" onclick="generateFormProgramTerapi()" id="btn-generate-ulang-form-programterapi" hidden><i class="fas fa-paper-plane me-1"></i> Generate Ulang Formulir</button>
                    <button type="button" class="btn btn-success" id="btn-get-form-utama-programterapi" hidden><i class="fas fa-external-link-alt me-1"></i> Lihat Isian Form Program Terapi <span class="badge bg-danger text-white ms-1">Utama</span></button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalTTDPasien" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tanda Tangan Pasien</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center">
                <div class="border rounded position-relative" style="width:100%; max-width:500px; margin:auto;">
                    <canvas id="signature-pad-pasien"></canvas>
                    {{-- <span id="placeholder-ttd-pasien" class="position-absolute top-50 start-50 translate-middle text-muted">
                        Silakan tanda tangan
                    </span> --}}
                </div>

                <div class="mt-3 d-flex justify-content-center gap-2">
                    <button class="btn btn-warning btn-sm" id="clear-ttd-pasien">
                        <i class="ti ti-eraser"></i> Hapus
                    </button>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" id="save-ttd-pasien" onclick="saveFormJadwalPelayanan()">
                    <i class="ti ti-device-floppy"></i> Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let padPasien;

    $(document).ready(function() {
        // tombol clear ttd pasien
        $('#clear-ttd-pasien').on('click', function () {
            if (padPasien) {
                padPasien.clear();
                $('#placeholder-ttd-pasien').show();
            }
        });
    });

    function storeFormProgramTerapi() {
        const btn = $('#btn-simpan-form-program-Terapi');

        let data = {
            _token      : $('meta[name="csrf-token"]').attr('content'),
            rm          : rm,
            kunjungan   : kunjungan,
            sep         : sep ?? 0,
            tgl_sep     : tgl_sep ?? tgl_masuk,
            tgl         : tgl_kfr,
            tgl_masuk   : tgl_masuk,
            tgl_keluar  : tgl_keluar,

            cppt_s_t    : $('#cppt_s_t').val(),
            cppt_o_t    : $('#cppt_o_t').val(),
            cppt_a_t    : $('#cppt_a_t').val(),
            cppt_p_t    : $('#cppt_p_t').val()
        };

        $.ajax({
            url: "/api/emr/pterapi/store",
            type: "POST",
            data: data,
            beforeSend: function () {
                btn.prop('disabled', true)
                    .find('i')
                    .removeClass('fa-save')
                    .addClass('fa-sync fa-spin');

                Swal.fire({
                    title: 'Menyimpan Data...',
                    text: 'Mohon menunggu hingga proses selesai',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    showCancelButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function (res) {
                Swal.fire({title: 'Proses Simpan Berhasil', text: res.message, icon: 'success', timer: 5000, timerProgressBar: true});
                loadFormProgramTerapi();
                loadCpptProgramTerapi();
                loadRiwayatProgramTerapi();
                kosongiFormProgramTerapi();
            },
            error: function (xhr) {
                Swal.fire(
                    'Gagal',
                    xhr.responseJSON?.message ?? 'Terjadi kesalahan',
                    'error'
                );
            },
            complete: function () {
                // always reset button (baik success maupun error)
                btn.prop('disabled', false)
                    .find('i')
                    .removeClass('fa-sync fa-spin')
                    .addClass('fa-save');
                loadFormJadwalPelayanan();
            }
        });
    }

    function loadCpptProgramTerapi() {
        $('#load-riwayat-cppt-program-kfr').empty()
            .append(`<div class="d-flex justify-content-center">
                        <div class="spinner-grow spinner-grow-sm me-2" role="status">
                            <span class="sr-only">Loading...</span>
                        </div> <a class="align-middle">Memproses Data Riwayat..</a>
                    </div>`);
        $.ajax({
            url: `/api/emr/pterapi/${rm}/cppt/${kunjungan}/${
                tgl_sep_date
                    ?? tgl_keluar?.substring(0, 10)
                    ?? tgl_masuk?.substring(0, 10)
            }`,
            type: 'GET',
            beforeSend: function () {
                $('#btn-refresh-riwayat-cppt-program').prop('disabled', true).find('i').addClass('fa-spin');
            },
            success: function(res) {
                if (!res.status) {
                    // Swal.fire('Info', res.message, 'info');
                    $('#load-riwayat-cppt-program-kfr').empty()
                        .append(`<div class="d-flex justify-content-center">
                                    <center><a class="align-middle">Data CPPT Tidak Ditemukan</a></center>
                                </div>`);
                    return;
                }
                let data = res.data;
                $('#load-riwayat-cppt-program-kfr').empty();
                data.forEach((item, index) => {
                    let tanggal = new Date(item.TANGGAL).toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    let tanggalDaftar = new Date(item.TGLPENDAFTARAN).toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    });

                    if (item.JENIS_CPPT == 1) {
                        jeniscppt = 'Dokter Spesialis';
                    } else {
                        if (item.JENIS_CPPT == 10) {
                            jeniscppt = 'Fisioterapis';
                        } else {
                            if (item.JENIS_CPPT == 11) {
                                jeniscppt = 'Terapi Wicara';
                            } else {
                                if (item.JENIS_CPPT == 12) {
                                    jeniscppt = 'Okupasi Terapis';
                                } else {
                                    jeniscppt = '';
                                }
                            }
                        }
                    }

                    // starForm = '';
                    // if (res.form) {
                    //     if (item.ID_CPPT == res.form.id_cppt) {
                    //         starForm = `<span class="badge bg-danger me-1" data-bs-toggle="tooltip" title="CPPT Formulir KFR Saat Ini"><i class="fas fa-star"></i></span>`;
                    //     }
                    // }

                    let badgeSistem = '';

                    // KFR
                    if(item.IS_KFR == 1){
                        badgeSistem += `
                            <span class="badge bg-primary badge-sm me-1" data-bs-toggle="tooltip" title="CPPT Form Rajal KFR">
                                <i class="fas fa-file-signature"></i>
                            </span>`;
                    }

                    // Terapi
                    if(item.IS_TERAPI == 1){
                        badgeSistem += `
                            <span class="badge bg-warning badge-sm me-1" data-bs-toggle="tooltip" title="CPPT Form Program Terapi">
                                <i class="fas fa-dumbbell"></i>
                            </span>`;
                    }

                    // Kalau dua-duanya TIDAK ada → SIMGOS
                    if(item.IS_KFR == 0 && item.IS_TERAPI == 0){
                        badgeSistem += `
                            <span class="badge bg-secondary badge-sm me-1" data-bs-toggle="tooltip" title="CPPT dari SIMGOS">
                                <i class="fas fa-database"></i>
                            </span>`;
                    }

                    let card = `<div class="accordion card" id="headingpterapi${item.ID_CPPT}">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header position-relative">
                                            <button class="accordion-button collapsed text-start"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapseptr${item.ID_CPPT}">
                                                <div class="d-flex flex-column w-100 min-w-0 pe-5">
                                                    <div class="text-truncate w-100">
                                                        ${badgeSistem}
                                                        ${kunjungan == item.KUNJUNGAN ? '<span class="badge bg-success badge-sm me-1" data-bs-toggle="tooltip" title="CPPT dari Kunjungan Saat Ini"><i class="fas fa-flag-checkered"></i></span>' : ''}
                                                        <b class="text-teal-900" data-bs-toggle="tooltip" title="Nama Ruangan">${item.NAMARUANGAN}</b>
                                                        <i class="fas fa-angle-right mx-1"></i>
                                                        <b class="text-pink-900" data-bs-toggle="tooltip" title="Tgl. Pendaftaran Kunjungan">${tanggalDaftar}</b>
                                                        <i class="fas fa-angle-right mx-1"></i>
                                                        <b class="text-purple-900" data-bs-toggle="tooltip" title="Nama Dokter DPJP">${item.NAMADPJP}</b>
                                                    </div>
                                                    <div class="small mt-1 text-truncate w-100">
                                                        <b data-bs-toggle="tooltip" title="Nama User Input CPPT"><i class="fas fa-user-md text-primary me-1"></i>
                                                        CPPT <b class="text-blue-900">${jeniscppt}</b> Oleh <u>${item.NAMAUSER}</u></b>
                                                    </div>
                                                    <div class="small mt-1 text-truncate w-100">
                                                        <b data-bs-toggle="tooltip" title="Tanggal Input CPPT"><i class="fas fa-calendar-check text-orange-400 me-1"></i>
                                                            Ditambahkan Tgl. ${tanggal} WIB
                                                        </b>
                                                    </div>
                                                </div>
                                            </button>
                                            <div class="position-absolute top-50 end-0 translate-middle-y me-5 d-flex gap-1" style="z-index: 3;">
                                                <button class="btn btn-sm btn-warning"
                                                        id="btn-copy-cppt-form-program-terapi-${item.ID_CPPT}"
                                                        onclick="event.stopPropagation(); copyCpptFormProgramTerapi(${item.ID_CPPT});"
                                                        data-bs-toggle="tooltip"
                                                        title="Copy CPPT">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </h2>
                                        <div id="collapseptr${item.ID_CPPT}" class="accordion-collapse collapse" data-bs-parent="#headingpterapi${item.ID_CPPT}">
                                            <div class="accordion-body">
                                                <p style="white-space: pre-line"><b class="text-primary">Subjective:</b><br>${item.SUBYEKTIF?nl(item.SUBYEKTIF):'-'}</p>
                                                <p style="white-space: pre-line"><b class="text-primary">Objective:</b><br>${item.OBYEKTIF?nl(item.OBYEKTIF):'-'}</p>
                                                <p style="white-space: pre-line"><b class="text-primary">Assessment:</b><br>${item.ASSESMENT?nl(item.ASSESMENT):'-'}</p>
                                                <p style="white-space: pre-line"><b class="text-primary">Planning:</b><br>${item.PLANNING?nl(item.PLANNING):'-'}</p>
                                                <p style="white-space: pre-line"><b class="text-primary">Rencana Tindak Lanjut:</b><br>${item.INSTRUKSI?nl(item.INSTRUKSI):'-'}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                    $('#load-riwayat-cppt-program-kfr').append(card);
                });
            },
            error: function (xhr) {
                Swal.fire(
                    'Gagal',
                    xhr.responseJSON?.message ?? 'Terjadi kesalahan',
                    'error'
                );
            },
            complete: function () {
                // always reset button (baik success maupun error)
                $('#btn-refresh-riwayat-cppt-program').prop('disabled', false).find('i').removeClass('fa-spin');
                // Showing Tooltip
                $('[data-bs-toggle="tooltip"]').tooltip('dispose');
                $('.tooltip').remove();
                $('[data-bs-toggle="tooltip"]').tooltip({
                    trigger : 'hover'
                })
            }
        });
    }

    function copyCpptFormProgramTerapi(IDCPPT) {
        const btn = $('#btn-copy-cppt-form-program-terapi-' + IDCPPT);
        // const tgl_sep_date = tgl_sep.substring(0, 10);
        $.ajax({
            url: `/api/emr/pterapi/${kunjungan}/cppt/${IDCPPT}/copy`,
            type: 'GET',
            beforeSend: function () {
                btn.prop('disabled', true)
                    .find('i')
                    .removeClass('fa-copy')
                    .addClass('fa-sync fa-spin');
            },
            success: function(res) {
                if (!res.status) {
                    Swal.fire({title: 'Maaf!', text: res.message, icon: 'warning', timer: 5000, timerProgressBar: true});
                    btn.prop('disabled', false)
                        .find('i')
                        .removeClass('fa-sync fa-spin')
                        .addClass('fa-copy');
                    $('#btn-batal-update-form-program-terapi').prop('hidden', true);
                    $('#btn-update-form-program-Terapi').prop('hidden', true);
                    $('#btn-lihat-form-program-terapi').removeData('kunjungan group queue').prop('hidden', true);
                    loadFormProgramTerapi();
                    return;
                }

                $('#kode_program_terapi').val('');
                $('#cppt_s_t').val(htmlToTextarea(res.data.SUBYEKTIF));
                $('#cppt_o_t').val(htmlToTextarea(res.data.OBYEKTIF));
                $('#cppt_a_t').val(htmlToTextarea(res.data.ASSESMENT));
                $('#cppt_p_t').val(htmlToTextarea(res.data.PROCEDURE));

                $('#btn-batal-update-form-program-terapi').prop('hidden', false);
                $('#btn-update-form-program-Terapi').prop('hidden', true);
                $('#btn-lihat-form-program-terapi').removeData('kunjungan group queue').prop('hidden', true);
                $('#btn-kosongi-form-program-terapi').prop('disabled', false).prop('hidden', false);
                $('#btn-simpan-form-program-Terapi').prop('disabled', false).prop('hidden', false);

            }, error: function (xhr) {
                Swal.fire(
                    'Gagal',
                    xhr.responseJSON?.message ?? 'Terjadi kesalahan',
                    'error'
                );
            },
            complete: function () {
                // always reset button (baik success maupun error)
                btn.prop('disabled', false)
                    .find('i')
                    .removeClass('fa-sync fa-spin')
                    .addClass('fa-copy');
                // loadCpptProgramTerapi();
            }
        })
    }

    function loadRiwayatProgramTerapi() {// RIWAYAT DI GRID KANAN
        $('#load-riwayat-program-terapi').empty()
            .append(`<div class="d-flex justify-content-center">
                        <div class="spinner-grow spinner-grow-sm me-2" role="status">
                            <span class="sr-only">Loading...</span>
                        </div> <a class="align-middle">Memproses Data Riwayat..</a>
                    </div>`);
        const btn = $('#btn-refresh-riwayat-form-program');
        $.ajax({
            url: `/api/emr/pterapi/${kunjungan}/riwayat`,
            type: 'GET',
            beforeSend: function () {
                btn.prop('disabled', true)
                    .find('i')
                    .addClass('fa-spin');
            },
            success: function(res) {
                if (!res.status) {
                    // Swal.fire('Info', res.message, 'info');
                    $('#load-riwayat-program-terapi').empty()
                        .append(`<div class="d-flex justify-content-center">
                                    <center><a class="align-middle">${res.message}</a></center>
                                </div>`);
                    $('#btn-list-form-kfr-lama').prop('hidden', false).prop('disabled', true).removeClass('btn-secondary btn-info').addClass('btn-secondary');
                    return;
                }
                let content = ``;
                content += `<ul class="list-group list-group-flush ">`;
                res.data.forEach((item, index) => {
                    let tanggalKunj = new Date(item.tgl_sep).toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric'
                    });
                    let tanggalDibuat = new Date(item.created_at).toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    jenis = '';
                    if (item.jenis == 1) {
                        jenis = 'Dokter Spesialis';
                    } else if (item.jenis == 10) {
                        jenis = 'Fisioterapi';
                    } else if (item.jenis == 12) {
                        jenis = 'Okupasi Terapi';
                    } else if (item.jenis == 11) {
                        jenis = 'Terapi Wicara';
                    } else {
                        jenis = 'Lainnya';
                    }

                    var admin = @json(Auth::user()->hasRole(['admin']));
                    var userId = @json(auth()->user()->ID);

                    let btnDis = (admin || item.user == userId) ? '' : 'disabled';
                    content += `<li class="list-group-item list-group-item-action ftr-item" data-id="${item.nomor}" data-group="${item.group}" data-queue="${item.queue}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat Berkas Formulir" style="cursor:pointer;">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="flex-shrink-0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="GROUP - PROGRAM. KE XX">
                                                <div class="badge bg-light-subtle border border-primary-subtle text-secondary shadow-lg fs-25 h-100"> ${item.group} <i class="ti ti-minus"></i> ${item.queue} </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="row g-1">
                                                    <div class="col-6">
                                                        <h6 class="mb-1">PROGRAM TERAPI <b class="text-info">#</b> <b class="text-red-900">${jenis}</b></h6>
                                                        <p class="text-muted mb-0"><small><b class="text-danger">DPJP</b>: <u>${item.nama_dokter}</u></small></p>
                                                        <p class="text-muted mb-0"><small><b class="text-primary">TIM</b>: ${item.nama_tim}</small></p>
                                                    </div>
                                                    <div class="col-6 text-end">
                                                        <h6 class="mb-1">Kunjungan/SEP Tgl. <span class="badge text-bg-secondary ms-1">${tanggalKunj}</span></h6>
                                                        <p class="text-muted mb-0"><small>Formulir dibuat pada ${tanggalDibuat}</small></p>
                                                        <p class="text-muted mb-0"><small>Oleh ${item.nama_lengkap_user}</small></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-2 ms-4 d-flex flex-column gap-1">
                                                <button class="btn btn-sm btn-outline-warning" data-queue="${item.queue}" id="btn-edit-form-ptr" onclick="event.stopPropagation(); editFormProgramTerapi('${item.nomor}', '${item.group}', '${item.queue}')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ubah Form Program Terapi" ${btnDis}>
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" data-queue="${item.queue}" id="btn-delete-form-ptr" onclick="event.stopPropagation(); deleteFormProgramTerapi('${item.nomor}', '${item.group}', '${item.queue}')" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Hapus Form Program Terapi" ${btnDis}>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </li>`;

                });
                content += `</ul>`;
                $('#load-riwayat-program-terapi').empty().append(content);

                // JIKA LIST DIKLIK
                $('#load-riwayat-program-terapi').on('click', '.ftr-item', function () {

                    // ===== RESET SEMUA ITEM KE NORMAL =====
                    $('.ftr-item').each(function () {
                        const g = $(this).data('group');
                        const q = $(this).data('queue');
                        $(this).find('.ftr-avatar')
                            .html(`${g} <i class="ti ti-minus"></i> ${q}`);
                    });

                    // ===== ITEM YANG DIKLIK → SPINNER =====
                    $(this).find('.ftr-avatar').html(`
                        <i class="fas fa-spinner fa-spin"></i>
                    `);

                    const nomor_kunjungan = $(this).data('id');
                    const group = $(this).data('group');
                    const queue = $(this).data('queue');

                    showFormProgramTerapi(nomor_kunjungan, group, queue);
                });
            },
            error: function (xhr) {
                Swal.fire(
                    'Gagal',
                    xhr.responseJSON?.message ?? 'Terjadi kesalahan / Gagal memanggil Function loadRiwayatKfr',
                    'error'
                );
                btn.prop('disabled', false)
                    .find('i')
                    .removeClass('fa-spin');
            },
            complete: function () {
                // always reset button (baik success maupun error)
                btn.prop('disabled', false)
                    .find('i')
                    .removeClass('fa-spin');
                // Showing Tooltip
                $('[data-bs-toggle="tooltip"]').tooltip('dispose');
                $('.tooltip').remove();
                $('[data-bs-toggle="tooltip"]').tooltip({
                    trigger : 'hover'
                })
            }
        });
    }

    function showFormProgramTerapi(kunjunganpush = null, grouppush = null, queuepush = null) {
        // Jika dipanggil dari klik list (pakai parameter)
        if (!kunjunganpush || !grouppush || !queuepush) {
            const btn = $('#btn-lihat-form-program-terapi');

            kunjunganpush = btn.data('kunjungan');
            grouppush = btn.data('group');
            queuepush = btn.data('queue');
        }

        // Final guard
        if (!kunjunganpush || !grouppush || !queuepush) {
            iziToast.error({
                title: 'Maaf!',
                message: 'Data Formulir Program Terapi belum siap dipreview.',
                position: 'topRight'
            });
            return;
        }

        $('#show-id-form-programterapi').text(kunjunganpush);

        fetch(`/api/emr/pterapi/${kunjunganpush}/${grouppush}/${queuepush}`) // , {cache: "no-store"}
        .then(response => {
            if (!response.ok) {
                throw new Error('File tidak ditemukan atau gagal diambil.');
            }
            return response.blob();

            $('#iframe-pdf-ftr').on('load', function () {
                $('.ftr-item').each(function () {
                    const g = $(this).data('group');
                    const q = $(this).data('queue');
                    $(this).find('.ftr-avatar')
                        .html(`${g} <i class="ti ti-minus"></i> ${q}`);
                });
            });
        })
        .then(blob => {
            // Buat object URL dari blob
            const fileURL = URL.createObjectURL(blob);

            // Tampilkan ke iframe dalam modal
            $('#cetak-form-programterapi').empty().html(`<iframe id="iframe-pdf-ftr" src="${fileURL}" width="100%" height="500px" frameborder="0"></iframe>`);
            $('#showFormProgramTerapi').modal('show');

            $('#iframe-pdf-ftr').on('load', function () {
                $('.ftr-item').each(function () {
                    const g = $(this).data('group');
                    const q = $(this).data('queue');
                    $(this).find('.ftr-avatar')
                        .html(`${g} <i class="ti ti-minus"></i> ${q}`);
                });
            });
        })
        .catch(error => {
            let message = 'Terjadi kesalahan pada sistem';

            if (error.response && error.response.data && error.response.data.message) {
                message = error.response.data.message;
            }

            iziToast.error({
                title: 'Maaf!',
                message: message,
                position: 'topRight'
            });
            $('#iframe-pdf-ftr').on('load', function () {
                $('.ftr-item').each(function () {
                    const g = $(this).data('group');
                    const q = $(this).data('queue');
                    $(this).find('.ftr-avatar')
                        .html(`${g} <i class="ti ti-minus"></i> ${q}`);
                });
            });
        });
    }

    function editFormProgramTerapi(kunjungan, group, queue) {
        const btn = $('#btn-edit-form-ptr[data-queue="' + queue + '"]');
        kosongiFormProgramTerapi();
        $.ajax({
            url: `/api/emr/pterapi/get/${kunjungan}/${group}/${queue}`,
            type: 'GET',
            beforeSend: function () {
                btn.prop('disabled', true)
                    .find('i')
                    .removeClass('fa-edit')
                    .addClass('fa-sync fa-spin');
            },
            success: function(res) {
                if (!res.status) {
                    kosongiFormProgramTerapi();
                    Swal.fire({
                        title: 'Ahh Maaf!',
                        text: res.message,
                        icon: 'warning',
                        timer: 10000,
                        timerProgressBar: true
                    });
                    $('#btn-batal-update-form-program-terapi').prop('hidden', true);
                    $('#btn-update-form-program-Terapi').prop('hidden', true);
                    $('#btn-lihat-form-program-terapi').removeData('kunjungan group queue').prop('hidden', true);
                    $('#btn-kosongi-form-program-terapi').prop('disabled', false).prop('hidden', false);
                    $('#btn-simpan-form-program-Terapi').prop('disabled', false).prop('hidden', false);
                    return;
                }

                $('#kode_program_terapi').val(queue);

                $('#cppt_s_t').val(res.data.SUBYEKTIF);
                $('#cppt_o_t').val(res.data.OBYEKTIF);
                $('#cppt_a_t').val(res.data.ASSESMENT);
                $('#cppt_p_t').val(res.data.PROCEDURE);

                $('#btn-batal-update-form-program-terapi').prop('hidden', false);
                $('#btn-update-form-program-Terapi').prop('hidden', false);
                $('#btn-lihat-form-program-terapi').data('kunjungan', res.kunjungan)
                                                    .data('group', res.group)
                                                    .data('queue', res.queue)
                                                    .prop('hidden', false);
                $('#btn-kosongi-form-program-terapi').prop('disabled', true).prop('hidden', true);
                $('#btn-simpan-form-program-Terapi').prop('disabled', true).prop('hidden', true);
            },
            error: function (xhr) {
                Swal.fire(
                    'Gagal',
                    xhr.responseJSON?.message ?? 'Terjadi kesalahan',
                    'error'
                );
                kosongiFormProgramTerapi();
                $('#btn-batal-update-form-program-terapi').prop('hidden', true);
                $('#btn-update-form-program-Terapi').prop('hidden', true);
                $('#btn-lihat-form-program-terapi').removeData('kunjungan group queue').prop('hidden', true);
                $('#btn-kosongi-form-program-terapi').prop('disabled', false).prop('hidden', false);
                $('#btn-simpan-form-program-Terapi').prop('disabled', false).prop('hidden', false);
            },
            complete: function () {
                // always reset button (baik success maupun error)
                btn.prop('disabled', false)
                    .find('i')
                    .removeClass('fa-sync fa-spin')
                    .addClass('fa-edit');
            }
        });
    }

    function prosesEditProgramTerapi() {
        let data = {
            _token      : $('meta[name="csrf-token"]').attr('content'),
            rm          : rm,
            kunjungan   : kunjungan,
            queue       : $('#kode_program_terapi').val(),
            sep         : sep ?? 0,
            tgl_sep     : tgl_sep ?? tgl_masuk,
            tgl         : tgl_kfr,
            tgl_masuk   : tgl_masuk,
            tgl_keluar  : tgl_keluar,

            cppt_s_t    : $('#cppt_s_t').val(),
            cppt_o_t    : $('#cppt_o_t').val(),
            cppt_a_t    : $('#cppt_a_t').val(),
            cppt_p_t    : $('#cppt_p_t').val()
        };

        $.ajax({
            url: `/api/emr/pterapi/update`,
            type: 'PUT',
            data: data,
            beforeSend: function () {
                $('#btn-update-form-program-Terapi').prop('disabled', true)
                    .find('i')
                    .removeClass('fa-edit')
                    .addClass('fa-sync fa-spin');
            },
            success: function(res) {
                if (!res.status) {
                    Swal.fire({
                        title: 'Ahh Maaf!',
                        text: res.message,
                        icon: 'warning',
                        timer: 10000,
                        timerProgressBar: true
                    });
                    return;
                }

                Swal.fire({
                    title: 'Update Berhasil!',
                    text: res.message,
                    icon: 'success',
                    timer: 5000,
                    timerProgressBar: true
                });

                batalUpdateFormProgramTerapi();
                loadFormProgramTerapi();
                loadCpptProgramTerapi();
                loadRiwayatProgramTerapi();
                kosongiFormProgramTerapi();
            },
            error: function (xhr) {
                Swal.fire(
                    'Gagal',
                    xhr.responseJSON?.message ?? 'Terjadi kesalahan',
                    'error'
                );
            },
            complete: function () {
                // always reset button (baik success maupun error)
                $('#btn-update-form-program-Terapi').prop('disabled', false)
                    .find('i')
                    .removeClass('fa-sync fa-spin')
                    .addClass('fa-edit');
            }
        });
    }

    function batalUpdateFormProgramTerapi() {
        $('#btn-batal-update-form-program-terapi').prop('hidden', true);
        $('#btn-update-form-program-Terapi').prop('hidden', true);
        $('#btn-lihat-form-program-terapi').removeData('kunjungan group queue').prop('hidden', true);
        $('#btn-kosongi-form-program-terapi').prop('disabled', false).prop('hidden', false);
        $('#btn-simpan-form-program-Terapi').prop('disabled', false).prop('hidden', false);
        kosongiFormProgramTerapi();
    }

    function deleteFormProgramTerapi(kunjunganp, groupp, queuep) {
        Swal.fire({
            title: 'Hapus formulir ini?',
            text: 'Data Formulir Program Terapi yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            showDenyButton: false,
            confirmButtonText: 'Hapus Form ini',
            cancelButtonText: 'Batal Hapus'
        }).then((result) => {

            if (!result.isConfirmed) return;

            hapusFormProgramTerapi(kunjunganp, groupp, queuep);

        });
    }

    function hapusFormProgramTerapi(kunjungans, groups, queues) {
        const btn       = $('#btn-delete-form-ptr[data-queue="' + queues + '"]');

        if (!queues) {
            Swal.fire('Maaf!', 'Data yang dibutuhkan untuk penghapusan form tidak lengkap.', 'warning');
            return;
        }

        $.ajax({
            url  : '/api/emr/pterapi/destroy',
            type : 'POST',
            data : {
                kunjungan: kunjungans,
                queue: queues,
                _token : $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                Swal.fire({
                    title: 'Proses Menghapus Data',
                    text: 'Menghapus Data Form ini...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
                btn.prop('disabled', true)
                    .find('i')
                    .removeClass('fa-trash')
                    .addClass('fa-sync fa-spin');
            },
            success: function(res){
                if (!res.status) {
                    Swal.fire({title: 'Maaf!', text: res.message, icon: 'warning', timer: 5000, timerProgressBar: true});
                    return;
                }

                Swal.fire({
                    title: 'Proses Hapus Berhasil!',
                    text: res.message,
                    icon: 'success',
                    timer: 10000,
                    timerProgressBar: true
                });

                batalUpdateFormProgramTerapi();
                loadFormProgramTerapi();
                loadCpptProgramTerapi();
                loadRiwayatProgramTerapi();
            },
            error: function(xhr){
                Swal.fire(
                    'Gagal',
                    xhr.responseJSON?.message ?? 'Terjadi kesalahan',
                    'error'
                );
            }, complete: function () {
                // always reset button (baik success maupun error)
                btn.prop('disabled', false)
                    .find('i')
                    .removeClass('fa-sync fa-spin')
                    .addClass('fa-trash');
            }
        });
    }

    function kosongiFormProgramTerapi() {
        $('#kode_program_terapi').val('');
        $('#cppt_s_t').val('');
        $('#cppt_o_t').val('');
        $('#cppt_a_t').val('');
        $('#cppt_p_t').val('');
    }

    // ---------------------------------------------------------------------------------------
    // ---------------------------------------------------------------------------------------
    // --------------------------  TTD PASIEN JADWAL PELAYANAN  ------------------------------
    // ---------------------------------------------------------------------------------------
    // ---------------------------------------------------------------------------------------

    function ttdPasienJadwalPelayanan() {
        $('#modalTTDPasien').modal('show');

        setTimeout(() => {
            const canvas = document.getElementById('signature-pad-pasien');

            padPasien = new SignaturePad(canvas);

            resizeCanvasPasien(canvas, padPasien);

            padPasien.onBegin = function () {
                $('#placeholder-ttd-pasien').hide();
            };
        }, 200);
    }

    // resize canvas
    function resizeCanvasPasien(canvas, pad) {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);

        const width = canvas.parentElement.offsetWidth;
        const height = 200;

        canvas.width = width * ratio;
        canvas.height = height * ratio;
        canvas.style.width = width + "px";
        canvas.style.height = height + "px";

        canvas.getContext("2d").scale(ratio, ratio);
    }

    function saveFormJadwalPelayanan() {

        if (!padPasien || padPasien.isEmpty()) {
            alert('Tanda tangan pasien tidak boleh kosong');
            return;
        }

        const image = padPasien.toDataURL('image/png');

        $('#save-ttd-pasien').prop('disabled', true);

        $.ajax({
            url: "{{ route('api.emr.jadwal.storeFormJadwalPelayanan') }}",
            method: "POST",
            contentType: "application/json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify({
                kunjungan: kunjungan,
                rm: rm,
                image: image,
                sep: sep,
                tgl_sep: tgl_sep_date,
                tgl: tgl_masuk
            }),
            success: function (res) {
                iziToast.success({
                    title: 'Berhasil',
                    message: 'Form Jadwal Pelayanan Terapi berhasil disimpan',
                    position: 'topRight'
                });

                $('#modalTTDPasien').modal('hide');

                // optional: simpan ke hidden input
                $('#ttd_pasien_path').val(res.path);
                $('#ttd_pasien_id').val(res.id);
            },
            error: function(xhr){
                iziToast.error({
                    title: 'Proses Gagal',
                    message: xhr.responseJSON?.message ?? 'Terjadi kesalahan',
                    position: 'topRight'
                });
            },
            complete: function () {
                $('#save-ttd-pasien').prop('disabled', false);
                loadFormJadwalPelayanan();
            }
        });
    }

    function generateUlangFormJadwalPelayanan() {
        const btn = $('#btn-generate-form-jp');
        $('#modalTTDPasien').modal('hide');
        $('#modalPreviewJpel').modal('hide');

        Swal.fire({
            title: 'Generate Ulang Formulir',
            text: 'Form Jadwal Pelayanan yang sudah ada akan digantikan dengan yang baru beserta isiannya. Data sebelumnya tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Generate Ulang',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: "/api/emr/jadwal/regenerate",
                method: "POST",
                contentType: "application/json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: JSON.stringify({
                    kunjungan: kunjungan,
                    rm: rm,
                    sep: sep,
                    tgl_sep: tgl_sep_date,
                    tgl: tgl_masuk
                }),
                beforeSend: function () {
                    btn.prop('disabled', true)
                        .find('i')
                        .addClass('ti-loader-quarter ti-spin')
                        .removeClass('ti-file-symlink');
                },
                success: function (res) {
                    iziToast.success({
                        title: 'Proses Berhasil!',
                        message: res.message,
                        position: 'topRight'
                    });

                    lihatFormJadwalPelayanan();
                },
                error: function(xhr){
                    iziToast.error({
                        title: 'Proses Gagal!',
                        message: xhr.responseJSON?.message ?? 'Terjadi kesalahan',
                        position: 'topRight'
                    });
                },
                complete: function () {
                    btn.prop('disabled', false)
                        .find('i')
                        .removeClass('ti-loader-quarter ti-spin')
                        .addClass('ti-file-symlink');

                    loadFormJadwalPelayanan();
                }
            });
        });
    }

    function lihatFormJadwalPelayanan() {

        const modalEl = document.getElementById('modalPreviewJpel');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();

        $('#prevFormJadwalPelayanan').html(`
            <div class="spinner-grow spinner-grow-sm me-2" role="status">
                <span class="sr-only">Loading...</span>
            </div> <a class="align-middle">Memuat Data Formulir..</a>
        `);

        fetch("/api/emr/jadwal/" + encodeURIComponent(kunjungan) + "/preview")
        .then(async response => {

            if (!response.ok) {
                let msg = 'Formulir tidak ditemukan';

                try {
                    const err = await response.json();
                    msg = err.message ?? msg;
                } catch(e) {}

                throw new Error(msg);
            }

            return response.blob();
        })
        .then(blob => {
            const fileURL = URL.createObjectURL(blob);

            $('#showTxModalPJPel').text(kunjungan);

            $('#prevFormJadwalPelayanan').html(`
                <iframe src="${fileURL}" width="100%" height="500px" frameborder="0" class="rounded"></iframe>
            `);
        })
        .catch(error => {
            iziToast.error({
                title: 'Maaf!',
                message: error.message,
                position: 'topRight'
            });

            $('#prevFormJadwalPelayanan').html(`
                <div class="text-danger text-center p-3">
                    ${error.message}
                </div>
            `);

            console.error(error);
        });
    }

    function hapusFormJadwalPelayanan() {
        Swal.fire({
            title: 'Hapus Formulir?',
            text: 'Data Formulir yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: "/api/emr/jadwal/hapus/" + encodeURIComponent(kunjungan),
                method: "DELETE",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    iziToast.success({
                        title: 'Berhasil',
                        message: res.message,
                        position: 'topRight'
                    });

                    $('#ttd_pasien_path').val('');
                    $('#ttd_pasien_id').val('');
                },
                error: function(xhr){
                    iziToast.error({
                        title: 'Proses Gagal',
                        message: xhr.responseJSON?.message ?? 'Terjadi kesalahan',
                        position: 'topRight'
                    });
                }, complete: function () {
                    $('#modalPreviewJpel').modal('hide');
                    loadFormJadwalPelayanan();
                }
            });
        });
    }
</script>
