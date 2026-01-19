<div class="row">
    <div class="col-md-7">
        <div class="card table-card border shadow-none">
            <div class="card-header d-flex align-items-center justify-content-between p-3">
                <h5 class="card-title mb-0"><i class="fas fa-file-signature me-1"></i> Formulir Rawat Jalan KFR <span class="badge bg-danger ms-1" id="form-kfr-utama" hidden>UTAMA</span></h5>
                <div class="btn-group">
                    @role(['admin','dokterspesialis'])
                        <button class="btn btn-info btn-sm" onclick="showListFormKfr()" id="btn-list-form-kfr-lama" hidden disabled><i class="ph-duotone ph-file-search me-1"></i> Gunakan Form Lama</button>
                    @endrole
                    <button class="btn btn-light-primary btn-sm" onclick="loadCpptKfr()" id="btn-refresh-riwayat-cppt" data-bs-toggle="tooltip" title="Refresh Riwayat CPPT"><i class="fas fa-sync"></i></button>
                    <button class="btn btn-light-warning btn-sm" onclick="loadRiwayatKfr()" id="btn-refresh-riwayat-form-kfr" data-bs-toggle="tooltip" title="Refresh Riwayat Formulir KFR"><i class="fas fa-sync"></i></button>
                </div>
            </div>

            {{-- INIT VALUE --}}
            <input type="hidden" id="id_cppt_kfr" value="">

            <div class="card-body p-3 pb-0">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label"><i><b>Subjective</b></i> <a class="text-danger">*</a></label>
                            <textarea id="cppt_s" rows="2" class="form-control" placeholder="..."></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label"><i><b>Objective</b></i> <a class="text-danger">*</a></label>
                            <textarea id="cppt_o" rows="2" class="form-control" placeholder="..."></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label"><i><b>Assessment</b></i> <a class="text-danger">*</a></label>
                            <textarea id="cppt_a" rows="2" class="form-control" placeholder="..."></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card table-card border shadow-none">
                            <div class="card-body p-3">
                                <div class="form-group">
                                    <label class="form-label"><i><b>Planning</b></i></label>
                                    <input id="cppt_p_1" class="form-control mb-3" placeholder="Goal of Treatment">
                                    <input id="cppt_p_2" class="form-control mb-3" placeholder="Tindakan/Program Rehabilitasi Medik">
                                    <input id="cppt_p_3" class="form-control mb-3" placeholder="Edukasi">
                                    <input id="cppt_p_4" class="form-control" placeholder="Frekuensi Kunjungan">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-3 pt-0">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label class="form-label"><b>Rencana Tindak Lanjut</b> <a class="text-danger">*</a></label>
                            <select class="form-select mb-2" id="cppt_i">
                                <option value="0">Pilih Tindak Lanjut</option>
                                <option value="1">Evaluasi</option>
                                <option value="2">Rujuk</option>
                                <option value="3">Selesai</option>
                            </select>
                            <textarea id="cppt_i_rtl" rows="3" class="form-control" placeholder="Tuliskan Rencana Tindak Lanjut (Wajib Diisi)"></textarea>
                        </div>
                    </div>
                    @role(['admin','dokterspesialis'])
                    <div class="col-md-12">
                        <div class="row align-items-center justify-content-between g-3">
                            <div class="col-sm-auto">
                                <button class="btn btn-warning me-2" onclick="kosongiFormKfr()" id="btn-kosongi-form-kfr" hidden>
                                    <i  class="fas fa-edit me-1"></i> Kosongkan Formulir
                                </button>
                                <button class="btn btn-danger me-2" onclick="deleteFormKfr()" id="btn-hapus-form-kfr" data-bs-toggle="tooltip" title="Hapus Formulir KFR" hidden>
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button class="btn btn-warning me-2" onclick="generateFormKfr()" id="btn-generate-form-kfr" data-bs-toggle="tooltip" title="Generate Ulang Formulir KFR" hidden>
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                                <button class="btn btn-info me-2" onclick="showFormKfr()" id="btn-lihat-form-kfr" data-bs-toggle="tooltip" title="Lihat Berkas Formulir KFR Saat Ini" hidden>
                                    <i class="fas fa-book-open"></i>
                                </button>
                                <button class="btn btn-danger" onclick="confirmUnSyncFormKfrLama()" id="btn-unsync-form-lama" data-bs-toggle="tooltip" title="Batalkan/Putuskan Hubungan dengan Form KFR Utama" hidden>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="col-sm-auto btn-page">
                                <button class="btn btn-primary" onclick="storeFormKfrBaru()" id="btn-simpan-form-kfr" hidden>
                                    <i class="fas fa-save me-1"></i> Simpan Formulir Baru
                                </button>
                                <button class="btn btn-success" onclick="updateFormKfr()" id="btn-update-form-kfr" data-bs-toggle="tooltip" title="Perbarui Formulir & TTE" hidden>
                                    <i class="fas fa-edit me-1"></i> Update Formulir
                                </button>
                                <button class="btn btn-light-dark" onclick="loadFormKfr()" id="btn-tutup-update-form-kfr" data-bs-toggle="tooltip" title="Batal Perubahan/Update Form" hidden>
                                    <i class="fas fa-reply me-1"></i>
                                </button>
                                <button class="btn btn-dark" onclick="bukaUpdateFormKfr()" id="btn-buka-update-form-kfr" data-bs-toggle="tooltip" title="Buat Form Baru di Group Yang Sama" hidden>
                                    <i class="fas fa-pen-square me-1"></i> Buka Akses Update Formulir
                                </button>
                            </div>
                        </div>
                    </div>
                    @endrole
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="accordion card" id="collapse-riwayat-form-kfr">
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading-riwayat-form-kfr">
                    <button class="accordion-button text-dark" style="background-color: #fef9ea" type="button" data-bs-toggle="collapse" data-bs-target="#btn-collapse-riwayat-form-kfr" aria-expanded="true" aria-controls="collapseOne">
                        <i class="fas fa-sort-alpha-down me-1"></i> Riwayat Formulir KFR <span class="badge text-bg-dark ms-1">By.NORM</span>
                    </button>
                </h2>
                <div id="btn-collapse-riwayat-form-kfr" class="accordion-collapse collapse show" aria-labelledby="heading-riwayat-form-kfr" data-bs-parent="#collapse-riwayat-form-kfr">
                    <div class="accordion-body rounded-bottom p-3" style="max-height: 613px; overflow-y: auto;">
                        <div id="load-riwayat-form-kfr">
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
        <div class="accordion card" id="collapse-riwayat-cppt-kfr">
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading-riwayat-cppt-kfr">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#btn-collapse-riwayat-cppt-kfr" aria-expanded="true" aria-controls="collapseOne">
                        <i class="fas fa-sort-amount-down me-1"></i> Riwayat CPPT <span class="badge text-bg-dark ms-1">By.KUNJUNGAN</span>
                    </button>
                </h2>
                <div id="btn-collapse-riwayat-cppt-kfr" class="accordion-collapse collapse show" aria-labelledby="heading-riwayat-cppt-kfr" data-bs-parent="#collapse-riwayat-cppt-kfr">
                    <div class="accordion-body rounded-bottom p-3" style="max-height: 613px; overflow-y: auto;">
                        <div id="load-riwayat-cppt-kfr">
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

<div id="showFormKfr" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="showFormKfrLabel">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showFormKfrLabel"><span class="badge text-bg-secondary">Formulir KFR</span> | IDKUNJUNGAN : <a id="show-id-formKFR" class="text-primary"></a></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div id="cetak-formkfr"></div>
            </div>
            <div class="modal-footer d-flex justify-content-between align-items-center">
                <h6 class="m-0">Silakan Generate Ulang<br>Apabila Formulir Gagal Dimuat</h6>
                <div>
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i> Tutup</button>
                    <button type="button" class="btn btn-warning" onclick="generateFormKfr()" id="btn-generate-ulang-form-kfr" hidden><i class="fas fa-paper-plane me-1"></i> Generate Ulang Formulir</button>
                    <button type="button" class="btn btn-success" id="btn-get-form-utama-kfr" hidden><i class="fas fa-external-link-alt me-1"></i> Lihat Isian Form KFR <span class="badge bg-danger text-white ms-1">Utama</span></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="showListFormKfr" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="showFormKfrLabel">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showFormKfrLabel">Daftar Formulir KFR <span class="badge bg-danger ms-1">UTAMA</span> (Kunjungan Sebelumnya)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div id="list-form-kfr"></div>
            </div>
            <div class="modal-footer d-flex justify-content-between align-items-center">
                <h6 class="m-0">Klik <span class="badge text-bg-secondary">Baris Formulir</span> pada Daftar di atas<br>Untuk menghubungkan formulir utama ke Kunjungan ini</h6>
                <div>
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i> Tutup</button>
                    <button type="button" class="btn btn-warning" onclick="showListFormKfr()"><i class="fas fa-sync me-1"></i> Refresh</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // aktifkan saat pertama kali load
        // aktifkanTabsDariHash();
    });

    function nl(v){
        return (v ?? '').replaceAll("\r\n", "\n");
    }

    function loadCpptKfr() {
        $('#load-riwayat-cppt-kfr').empty()
            .append(`<div class="d-flex justify-content-center">
                        <div class="spinner-grow spinner-grow-sm me-2" role="status">
                            <span class="sr-only">Loading...</span>
                        </div> <a class="align-middle">Memproses Data Riwayat..</a>
                    </div>`);
        $.ajax({
            url: `/api/emr/kfr/${kunjungan}/cppt`,
            type: 'GET',
            beforeSend: function () {
                $('#btn-refresh-riwayat-cppt').prop('disabled', true).find('i').addClass('fa-spin');
            },
            success: function(res) {
                if (!res.status) {
                    // Swal.fire('Info', res.message, 'info');
                    $('#load-riwayat-cppt-kfr').empty()
                        .append(`<div class="d-flex justify-content-center">
                                    <center><a class="align-middle">Data CPPT Tidak Ditemukan</a></center>
                                </div>`);
                    return;
                }
                let data = res.data;
                $('#load-riwayat-cppt-kfr').empty();
                data.forEach((item, index) => {
                    let tanggal = new Date(item.TANGGAL).toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    let card = `<div class="accordion card" id="heading${item.ID_CPPT}">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${item.ID_CPPT}" aria-expanded="true" aria-controls="collapseOne">
                                                <b class="text-pink-900">${tanggal}</b><i class="fas fa-arrow-right text-primary ms-2 me-2"></i><b>CPPT Oleh <u>${item.NAMAUSER}</u></b>
                                            </button>
                                        </h2>
                                        <div id="collapse${item.ID_CPPT}" class="accordion-collapse collapse" data-bs-parent="#heading${item.ID_CPPT}">
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
                    $('#load-riwayat-cppt-kfr').append(card);
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
                $('#btn-refresh-riwayat-cppt').prop('disabled', false).find('i').removeClass('fa-spin');
                // Showing Tooltip
                $('[data-bs-toggle="tooltip"]').tooltip('dispose');
                $('.tooltip').remove();
                $('[data-bs-toggle="tooltip"]').tooltip({
                    trigger : 'hover'
                })
            }
        });
    }

    function loadRiwayatKfr() { // RIWAYAT DI GRID KANAN
        $('#load-riwayat-form-kfr').empty()
            .append(`<div class="d-flex justify-content-center">
                        <div class="spinner-grow spinner-grow-sm me-2" role="status">
                            <span class="sr-only">Loading...</span>
                        </div> <a class="align-middle">Memproses Data Riwayat..</a>
                    </div>`);
        const btn = $('#btn-refresh-riwayat-form-kfr');
        const tgl_sep_date = tgl_sep.substring(0, 10);
        $.ajax({
            url: `/api/emr/kfr/rm/${rm}/${kunjungan}/${tgl_sep_date}`,
            type: 'GET',
            beforeSend: function () {
                btn.prop('disabled', true)
                    .find('i')
                    .addClass('fa-spin');
            },
            success: function(res) {
                if (!res.status) {
                    // Swal.fire('Info', res.message, 'info');
                    $('#load-riwayat-form-kfr').empty()
                        .append(`<div class="d-flex justify-content-center">
                                    <center><a class="align-middle">${res.message}</a></center>
                                </div>`);
                    $('#btn-list-form-kfr-lama').prop('hidden', false).prop('disabled', true).removeClass('btn-secondary btn-info').addClass('btn-secondary');
                    return;
                }
                let content = ``;
                content += `<ul class="list-group list-group-flush ">`;
                res.data.show.forEach((item, index) => {
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

                    var utama = '';
                    if (res.data && res.data.form) {
                        if (res.data.form.nomor_init == item.nomor) {
                            utama = '<span class="badge bg-danger">UTAMA</span>';
                        }
                    }

                    content += `<li class="list-group-item list-group-item-action kfr-item" data-id="${item.nomor}" data-init="${item.nomor_init}" data-group="${item.group}" data-queue="${item.queue}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat Berkas Formulir">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="GROUP - KUNJ. KE XX">
                                                <div class="avtar avtar-s border kfr-avatar"> ${item.group} <i class="ti ti-minus"></i> ${item.queue} </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="row g-1">
                                                    <div class="col-6">
                                                        <h6 class="mb-1">SEP<b class="text-info">#</b>${item.sep} ${item.nomor == kunjungan?'<span class="badge bg-primary">SAAT INI</span>':''} ${utama}</h6>
                                                        <p class="text-muted mb-0"><small><b class="text-danger">DPJP</b>: <u>${item.nama_dokter}</u></small></p>
                                                    </div>
                                                    <div class="col-6 text-end">
                                                        <h6 class="mb-1">Kunjungan/SEP Tgl. <span class="badge text-bg-secondary ms-1">${tanggalKunj}</span></h6>
                                                        <p class="text-muted mb-0"><small>Formulir dibuat pada ${tanggalDibuat}</small></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>`;

                });
                content += `</ul>`;
                $('#load-riwayat-form-kfr').empty().append(content);

                if (res.data && res.data.form) {
                    if (res.data.form.nomor_init != kunjungan) {
                        $('#btn-list-form-kfr-lama').prop('hidden', false).prop('disabled', true).removeClass('btn-secondary btn-info').addClass('btn-secondary');
                    } else {
                        $('#btn-list-form-kfr-lama').prop('hidden', false).prop('disabled', true).removeClass('btn-secondary btn-info').addClass('btn-secondary');
                    }
                } else {
                    $('#btn-list-form-kfr-lama').prop('hidden', false).prop('disabled', false).removeClass('btn-secondary btn-info').addClass('btn-info');
                }

                // JIKA LIST DIKLIK
                $('#load-riwayat-form-kfr').on('click', '.kfr-item', function () {

                    // ===== RESET SEMUA ITEM KE NORMAL =====
                    $('.kfr-item').each(function () {
                        const g = $(this).data('group');
                        const q = $(this).data('queue');
                        $(this).find('.kfr-avatar')
                            .html(`${g} <i class="ti ti-minus"></i> ${q}`);
                    });

                    // ===== ITEM YANG DIKLIK → SPINNER =====
                    $(this).find('.kfr-avatar').html(`
                        <i class="fas fa-spinner fa-spin"></i>
                    `);

                    const nomor_kunjungan = $(this).data('id');
                    const nomor_kunjungan_init = $(this).data('init');

                    // ===== LOGIC ASLI KAMU (TIDAK DIUBAH) =====
                    if (res.data && res.data.form) {
                        if (res.data.form.nomor_init == kunjungan) {
                            showFormKfr();
                            return;
                        } else if (nomor_kunjungan == kunjungan) {
                            showFormKfr();
                            return;
                        } else {
                            showFormKfr(nomor_kunjungan, nomor_kunjungan_init);
                            return;
                        }
                    } else {
                        showFormKfr(nomor_kunjungan, nomor_kunjungan_init);
                        return;
                    }
                });
            },
            error: function (xhr) {
                Swal.fire(
                    'Gagal',
                    xhr.responseJSON?.message ?? 'Terjadi kesalahan / Gagal memanggil Function loadRiwayatKfr',
                    'error'
                );
                $('#btn-list-form-kfr-lama').prop('hidden', false).prop('disabled', true).removeClass('btn-secondary btn-info').addClass('btn-secondary');
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

    function showListFormKfr() { // KETIKA KLIK TOMBOL GUNAKAN FORM LAMA
        $('#list-form-kfr').empty()
            .append(`<div class="d-flex justify-content-center">
                        <div class="spinner-grow spinner-grow-sm me-2" role="status">
                            <span class="sr-only">Loading...</span>
                        </div> <a class="align-middle">Memproses Data Riwayat..</a>
                    </div>`);
        const btn = $('#btn-list-form-kfr-lama');
        $.ajax({
            url: `/api/emr/kfr/rm/${rm}/${kunjungan}`,
            type: 'GET',
            beforeSend: function () {
                btn.prop('disabled', true)
                    .find('i')
                    .removeClass('ph-duotone ph-file-search')
                    .addClass('fas fa-sync fa-spin');
            },
            success: function(res) {
                if (!res.status) {
                    // Swal.fire('Info', res.message, 'info');
                    $('#list-form-kfr').empty()
                        .append(`<div class="d-flex justify-content-center">
                                    <center><a class="align-middle">${res.message}</a></center>
                                </div>`);
                    return;
                }
                $('#list-form-kfr').empty();
                let content = `<ul class="list-group list-group-flush ">`;
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

                    content += `<li class="list-group-item list-group-item-action" data-id="${item.nomor_init}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Klik Untuk Gunakan Formulir">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0" data-bs-toggle="tooltip" data-bs-placement="bottom" title="NOMOR GROUP FORM KFR">
                                                <div class="avtar avtar-s bg-dark text-white"> ${item.group} </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <div class="row g-1">
                                                    <div class="col-6">
                                                        <h6 class="mb-1">SEP<b class="text-info">#</b>${item.sep} ${item.nomor_init == kunjungan?'<span class="badge bg-primary">SAAT INI</span>':''}</h6>
                                                        <p class="text-muted mb-0"><small><b class="text-danger">DPJP</b>: <u>${item.nama_dokter}</u></small></p>
                                                    </div>
                                                    <div class="col-6 text-end">
                                                        <h6 class="mb-1">Kunjungan/SEP Tgl. <span class="badge text-bg-secondary ms-1">${tanggalKunj}</span></h6>
                                                        <p class="text-muted mb-0"><small>Formulir dibuat pada ${tanggalDibuat}</small></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>`;
                });
                content += `</ul>`;
                $('#list-form-kfr').append(content);
                $('#showListFormKfr').modal('show');

                // JIKA LIST DIKLIK
                $('#list-form-kfr').on('click', '.list-group-item', function () {

                    const nomor_init = $(this).data('id');
                    if (nomor_init == kunjungan) {
                        Swal.fire({title: 'Maaf!!', text: 'Formulir yang dipilih adalah formulir pada kunjungan saat ini', icon: 'info', timer: 5000, timerProgressBar: true});
                        return;
                    }
                    $('#showListFormKfr').modal('hide');

                    Swal.fire({
                        title: 'Hubungkan Formulir?',
                        text: `Anda akan menghubungkan Kunjungan saat ini dengan Formulir pada Kunjungan: ${nomor_init}`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, gunakan',
                        cancelButtonText: 'Batal'
                    }).then((result) => {

                        if (result.isConfirmed) {
                            syncFormKfrLama(nomor_init);
                        } else {
                            $('#showListFormKfr').modal('show');
                        }

                    });
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
                btn.prop('disabled', false)
                    .find('i')
                    .removeClass('fas fa-sync fa-spin')
                    .addClass('ph-duotone ph-file-search');
                // Showing Tooltip
                $('[data-bs-toggle="tooltip"]').tooltip('dispose');
                $('.tooltip').remove();
                $('[data-bs-toggle="tooltip"]').tooltip({
                    trigger : 'hover'
                })
            }
        });
    }

    function syncFormKfrLama(nomor_init) {
        $.ajax({
            url: '/api/emr/kfr/sync',
            type: 'POST',
            data: {
                nomor_init: nomor_init,
                nomor_kunjungan: kunjungan,
                rm: rm,
                sep: sep,
                tgl_sep: tgl_sep,
                tgl_kfr: tgl_kfr,
                tgl_masuk   : tgl_masuk,
                tgl_keluar  : tgl_keluar,
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function () {
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Sedang menghubungkan data formulir',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
            },
            success: function(res) {
                if (!res.status) {
                    Swal.fire('Info', res.message, 'info');
                    $('#list-form-kfr').empty()
                        .append(`<div class="d-flex justify-content-center">
                                    <center><a class="align-middle">Data Formulir KFR Tidak Ditemukan</a></center>
                                </div>`);
                    return;
                }
                Swal.fire({title: 'Proses Sinkronisasi Berhasil', text: res.message || 'Form berhasil dihubungkan', icon: 'success', timer: 5000, timerProgressBar: true});
                loadFormKfr();
                loadCpptKfr();
                loadRiwayatKfr();
            },
            error: function(xhr) {
                Swal.fire('Gagal', xhr.responseJSON?.message ?? 'Terjadi kesalahan', 'error');
            },
            complete: function() {
                // swal.close();
            }
        });
    }

    function confirmUnSyncFormKfrLama(params) {
        Swal.fire({
            title: 'Hapus Formulir KFR saat ini?',
            text: 'Formulir ini akan diputuskan hubungan dengan Form KFR Utama (Tanpa menghapus Form Utama) dan Data Form yang sudah dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Hapus Hanya Form KFR Saat Ini',
            cancelButtonText: 'Batal Hapus'
        }).then((result) => {

            if (result.isConfirmed) {
                unSyncFormKfrLama();
            } else {
                return;
            }

        });
    }

    function unSyncFormKfrLama() {
        $.ajax({
            url: '/api/emr/kfr/unsync',
            type: 'POST',
            data: {
                nomor_kunjungan: kunjungan,
                _token: '{{ csrf_token() }}'
            },
            beforeSend: function () {
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Sedang memutus hubungan data formulir utama',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
            },
            success: function(res) {
                if (!res.status) {
                    Swal.fire('Info', res.message, 'info');
                    $('#list-form-kfr').empty()
                        .append(`<div class="d-flex justify-content-center">
                                    <center><a class="align-middle">Data Formulir KFR Tidak Ditemukan</a></center>
                                </div>`);
                    return;
                }
                Swal.fire({title: 'Proses Penghapusan Berhasil', text: res.message || 'Form berhasil dihapus & diputus hubungan dengan Form KFR Utama', icon: 'success', timer: 5000, timerProgressBar: true});
                loadFormKfr();
                loadCpptKfr();
                loadRiwayatKfr();
            },
            error: function(xhr) {
                Swal.fire('Gagal', xhr.responseJSON?.message ?? 'Terjadi kesalahan', 'error');
            },
            complete: function() {
                // swal.close();
            }
        });
    }

    function storeFormKfrBaru() {
        const btn = $('#btn-simpan-form-kfr');

        let data = {
            _token      : $('meta[name="csrf-token"]').attr('content'),
            rm          : rm,
            kunjungan   : kunjungan,
            sep         : sep,
            tgl_sep     : tgl_sep,
            tgl         : tgl_kfr,
            tgl_masuk   : tgl_masuk,
            tgl_keluar  : tgl_keluar,

            cppt_s      : $('#cppt_s').val(),
            cppt_o      : $('#cppt_o').val(),
            cppt_a      : $('#cppt_a').val(),

            // cppt_p_1    : $('#cppt_p_1').val() || "-",
            // cppt_p_2    : $('#cppt_p_2').val() || "-",
            // cppt_p_3    : $('#cppt_p_3').val() || "-",
            // cppt_p_4    : $('#cppt_p_4').val() || "-",

            cppt_p_1    : $('#cppt_p_1').val(),
            cppt_p_2    : $('#cppt_p_2').val(),
            cppt_p_3    : $('#cppt_p_3').val(),
            cppt_p_4    : $('#cppt_p_4').val(),

            cppt_i      : $('#cppt_i').val(),
            cppt_i_rtl  : $('#cppt_i_rtl').val(),
        };

        $.ajax({
            url: "{{ route('api.emr.kfr.store') }}",
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
                loadFormKfr();
                loadCpptKfr();
                loadRiwayatKfr();
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
            }
        });
    }

    function generateFormKfr() {
        const btn = $('#btn-generate-form-kfr');
        $('#showFormKfr').modal('hide');

        $.ajax({
            url: `/api/emr/kfr/${kunjungan}/generate`,
            type: "GET",
            beforeSend: function () {
                // disable button & show spinner
                btn.prop('disabled', true)
                    .find('i')
                    .removeClass('fa-paper-plane')
                    .addClass('fa-sync fa-spin');

                Swal.fire({
                    title: 'Generating PDF...',
                    text: 'Mohon menunggu hingga proses selesai',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
            },
            success: function (res) {
                Swal.close();

                // Swal.fire({title: 'Proses Generate Berhasil', text: res.message, icon: 'success', timer: 5000, timerProgressBar: true});

                showFormKfr();
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
                    .addClass('fa-paper-plane');
            }
        });
    }

    function showFormKfr(pushkunjungan,pushkunjunganinit) {
        var generate = 0;
        if (!pushkunjungan && !pushkunjunganinit) {
            pushkunjungan = kunjungan;
            generate = 1;
        }
        $('#show-id-formKFR').text(pushkunjungan);
        $('#btn-lihat-form-kfr').prop('disabled',true).find('i').removeClass('fa-book-open').addClass('fa-sync fa-spin');

        fetch("/api/emr/kfr/"+pushkunjungan+"/show", {
            cache: "no-store"
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('File tidak ditemukan atau gagal diambil.');
            }
            return response.blob();

            $('#iframe-pdf-kfr').on('load', function () {
                $('.kfr-item').each(function () {
                    const g = $(this).data('group');
                    const q = $(this).data('queue');
                    $(this).find('.kfr-avatar')
                        .html(`${g} <i class="ti ti-minus"></i> ${q}`);
                });
            });
        })
        .then(blob => {
            // Buat object URL dari blob
            const fileURL = URL.createObjectURL(blob);

            // Tampilkan ke iframe dalam modal
            $('#cetak-formkfr').empty().html(`<iframe id="iframe-pdf-kfr" src="${fileURL}" width="100%" height="500px" frameborder="0"></iframe>`);
            if (generate == 0) {
                $('#btn-generate-ulang-form-kfr').prop('hidden',true);
                if (pushkunjunganinit == pushkunjungan) {
                    $('#btn-get-form-utama-kfr').prop('hidden',false).on('click', function () {
                        window.location.href = `/emr/${pushkunjunganinit}#fmrehab#frjkfr`;
                    });
                }
            } else {
                $('#show-id-formKFR').empty().html(pushkunjungan+' <span class="badge bg-primary me-1">SAAT INI</span>');
                $('#btn-generate-ulang-form-kfr').prop('hidden',false);
                $('#btn-get-form-utama-kfr').prop('hidden',true);
            }
            $('#showFormKfr').modal('show');

            $('#iframe-pdf-kfr').on('load', function () {
                $('.kfr-item').each(function () {
                    const g = $(this).data('group');
                    const q = $(this).data('queue');
                    $(this).find('.kfr-avatar')
                        .html(`${g} <i class="ti ti-minus"></i> ${q}`);
                });
            });
            $('#btn-lihat-form-kfr').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-book-open');
        })
        .catch(error => {
            iziToast.error({
                title: 'Maaf!',
                message: 'Data Formulir KFR tidak ditemukan atau belum digenerate.',
                position: 'topRight'
            });
            $('#iframe-pdf-kfr').on('load', function () {
                $('.kfr-item').each(function () {
                    const g = $(this).data('group');
                    const q = $(this).data('queue');
                    $(this).find('.kfr-avatar')
                        .html(`${g} <i class="ti ti-minus"></i> ${q}`);
                });
            });
            $('#btn-lihat-form-kfr').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-book-open');
        });
    }

    function updateFormKfr() {
        const btn = $('#btn-update-form-kfr');

        let data = {
            _token     : $('meta[name="csrf-token"]').attr('content'),
            id_cppt    : $('#id_cppt_kfr').val(),
            kunjungan  : kunjungan,
            tgl        : tgl_kfr,
            tgl_masuk  : tgl_masuk,
            tgl_keluar : tgl_keluar,

            cppt_s     : $('#cppt_s').val(),
            cppt_o     : $('#cppt_o').val(),
            cppt_a     : $('#cppt_a').val(),

            // cppt_p_1    : $('#cppt_p_1').val() || "-",
            // cppt_p_2    : $('#cppt_p_2').val() || "-",
            // cppt_p_3    : $('#cppt_p_3').val() || "-",
            // cppt_p_4    : $('#cppt_p_4').val() || "-",

            cppt_p_1   : $('#cppt_p_1').val(),
            cppt_p_2   : $('#cppt_p_2').val(),
            cppt_p_3   : $('#cppt_p_3').val(),
            cppt_p_4   : $('#cppt_p_4').val(),

            cppt_i     : $('#cppt_i').val(),
            cppt_i_rtl : $('#cppt_i_rtl').val(),
        };

        $.ajax({
            url  : '/api/emr/kfr/update/' + data.id_cppt,
            type : 'PUT',
            data : data,
            beforeSend: function () {
                // disable button & show spinner
                btn.prop('disabled', true)
                    .find('i')
                    .removeClass('fa-edit')
                    .addClass('fa-sync fa-spin');

                Swal.fire({
                    title: 'Memperbarui Data...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
            },
            success: function(res){
                Swal.fire({title: 'Berhasil Memperbarui!', text: res.message, icon: 'success', timer: 5000, timerProgressBar: true});
                loadFormKfr();
                loadCpptKfr();
                loadRiwayatKfr();
            },
            error: function(xhr){
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
                    .addClass('fa-edit');
            }
        });
    }

    function bukaUpdateFormKfr() {
        $('#cppt_s').prop('disabled', false);
        $('#cppt_o').prop('disabled', false);
        $('#cppt_a').prop('disabled', false);
        $('#cppt_p_1').prop('disabled', false);
        $('#cppt_p_2').prop('disabled', false);
        $('#cppt_p_3').prop('disabled', false);
        $('#cppt_p_4').prop('disabled', false);
        $('#cppt_i').prop('disabled', false);
        $('#cppt_i_rtl').prop('disabled', false);

        $('#btn-update-form-kfr').prop('hidden', false);
        $('#btn-tutup-update-form-kfr').prop('hidden', false);
        $('#btn-buka-update-form-kfr').prop('hidden', true);
        $('#btn-kosongi-form-kfr').prop('hidden', false);
    }

    function deleteFormKfr() {
        Swal.fire({
            title: 'Hapus formulir ini?',
            text: 'Data yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonText: 'Hapus Hanya Form KFR Ini',
            denyButtonText: 'Hapus Semua Form KFR di Group',
            cancelButtonText: 'Batal Hapus'
        }).then((result) => {

            // if (!result.isConfirmed) return;

            if (result.isConfirmed) {
                hapusFormKfr();
            }
            else if (result.isDenied) {
                hapusFormKfrGroup();
            }

        });
    }

    function hapusFormKfr() {
        $.ajax({
            url  : '/api/emr/kfr/destroy',
            type : 'POST',
            data : {
                id_cppt: $('#id_cppt_kfr').val(),
                _token : $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                Swal.fire({
                    title: 'Proses Menghapus Data',
                    text: 'Menghapus Data Form ini...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
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
                    timer: 5000,
                    timerProgressBar: true
                });

                kosongiFormKfr();
                loadFormKfr();
                loadCpptKfr();
                loadRiwayatKfr();

                $('#id_cppt_kfr').val('');
                $('#btn-kosongi-form-kfr').prop('hidden', false);
                $('#btn-simpan-form-kfr').prop('hidden', false);
                $('#btn-update-form-kfr').prop('hidden', true);
                $('#btn-hapus-form-kfr').prop('hidden', true);
            },
            error: function(xhr){
                Swal.fire(
                    'Gagal',
                    xhr.responseJSON?.message ?? 'Terjadi kesalahan',
                    'error'
                );
            }
        });
    }

    function hapusFormKfrGroup() {
        $.ajax({
            url  : '/api/emr/kfr/destroy/all',
            type : 'POST',
            data : {
                id_cppt: $('#id_cppt_kfr').val(),
                _token : $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                Swal.fire({
                    title: 'Proses Menghapus Data',
                    text: 'Menghapus Data Semua Form di Group...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
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
                    timer: 5000,
                    timerProgressBar: true
                });

                kosongiFormKfr();
                loadFormKfr();
                loadCpptKfr();
                loadRiwayatKfr();

                $('#id_cppt_kfr').val('');
                $('#btn-kosongi-form-kfr').prop('hidden', false);
                $('#btn-simpan-form-kfr').prop('hidden', false);
                $('#btn-update-form-kfr').prop('hidden', true);
                $('#btn-hapus-form-kfr').prop('hidden', true);
            },
            error: function(xhr){
                Swal.fire(
                    'Gagal',
                    xhr.responseJSON?.message ?? 'Terjadi kesalahan',
                    'error'
                );
            }
        });
    }

    function kosongiFormKfr() {
        $('#cppt_s').val('');
        $('#cppt_o').val('');
        $('#cppt_a').val('');
        $('#cppt_p_1').val('');
        $('#cppt_p_2').val('');
        $('#cppt_p_3').val('');
        $('#cppt_p_4').val('');
        $('#cppt_i').val('0').trigger('change');
        $('#cppt_i_rtl').val('');
    }

    // function aktifkanTabsDariHash() {
    //     const hash = window.location.hash; // contoh: #frehab#formlayanankfr
    //     if (!hash) return;

    //     // pecah jadi array ['frehab', 'formlayanankfr']
    //     const ids = hash.split('#').filter(Boolean);

    //     ids.forEach((id, index) => {
    //         const selector = '#' + id;
    //         const $tabBtn = $('[data-bs-target="' + selector + '"]');

    //         if ($tabBtn.length) {
    //             const tab = new bootstrap.Tab($tabBtn[0]);
    //             tab.show();

    //             // jalankan validasi sesuai target
    //             if (selector === '#frehab' || selector === '#formlayanankfr') {
    //                 validPageFormKfr();
    //                 console.log('jalan kfr');
    //             } else if (selector === '#formjadwalpelayanan') {
    //                 validPageFormJp();
    //                 console.log('jalan jp');
    //             } else if (selector === '#formkonsulkfr') {
    //                 validPageFormKs();
    //                 console.log('jalan ks');
    //             }
    //         }
    //     });
    // }
</script>
