<div class="row">
    <div class="col-md-8">
        <div class="card table-card border shadow-none">
            <div class="card-header d-flex align-items-center justify-content-between p-3">
                <h5 class="card-title mb-0"><i class="fas fa-file-signature me-1"></i> Formulir Rawat Jalan KFR</h5>
                <button class="btn btn-info btn-sm" onclick=""><i class="ph-duotone ph-file-search me-1"></i> Gunakan Form Lama</button>
            </div>

            {{-- INIT VALUE --}}
            <input type="hidden" id="kunjungan_kfr" value="{{ $list['KUNJUNGAN'] }}">
            <input type="hidden" id="sep_kfr" value="{{ $list['show']->NOSEP }}">
            <input type="hidden" id="tgl_sep_kfr" value="{{ $list['show']->TGLSEP }}">
            <input type="hidden" id="rm_kfr" value="{{ $list['show']->NORM ?? '' }}">
            <input type="hidden" id="tgl_kfr" value="{{ now()->format('Y-m-d H:i:s') }}">
            <input type="hidden" id="id_cppt_kfr" value="">

            <div class="card-body p-3 pb-0">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label"><i><b>Subjective</b></i> <a class="text-danger">*</a></label>
                            <textarea id="cppt_s" rows="2" class="form-control"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label"><i><b>Objective</b></i> <a class="text-danger">*</a></label>
                            <textarea id="cppt_o" rows="2" class="form-control"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label"><i><b>Assessment</b></i> <a class="text-danger">*</a></label>
                            <textarea id="cppt_a" rows="2" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card table-card border shadow-none">
                            <div class="card-body p-3">
                                <div class="form-group">
                                    <label class="form-label"><i><b>Planning</b></i> <a class="text-danger">*</a></label>
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
                                <option value="0">Pilih</option>
                                <option value="1">Evaluasi</option>
                                <option value="2">Rujuk</option>
                                <option value="3">Selesai</option>
                            </select>
                            <textarea id="cppt_i_rtl" rows="3" class="form-control" placeholder="Tuliskan Rencana Tindak Lanjut"></textarea>
                        </div>
                    </div>
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
                                <button class="btn btn-info me-2" onclick="showFormKfr()" id="btn-lihat-form-kfr" data-bs-toggle="tooltip" title="Lihat Formulir" hidden>
                                    <i class="fas fa-book-open"></i>
                                </button>
                                {{-- <button class="btn btn-secondary" onclick="showformKfrLama()" id="btn-form-lama" hidden>
                                    <i class="fab fa-wpforms me-1"></i> Lihat Formulir Yang Ada
                                </button> --}}
                            </div>
                            <div class="col-sm-auto btn-page">
                                <button class="btn btn-primary" onclick="storeFormKfrBaru()" id="btn-simpan-form-kfr" hidden>
                                    <i class="fas fa-save me-1"></i> Simpan Formulir Baru
                                </button>
                                <button class="btn btn-success" onclick="updateFormKfr()" id="btn-update-form-kfr" hidden>
                                    <i class="fas fa-save me-1"></i> Update Formulir
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card table-card border shadow-none">
            <div class="card-header d-flex align-items-center justify-content-between p-3">
                <h5 class="card-title mb-0"><i class="fas fa-sort-amount-down me-1"></i> Riwayat CPPT</h5>
                <button class="btn btn-light-warning btn-sm" onclick="loadCpptKfr()" id="btn-refresh-riwayat-cppt" data-bs-toggle="tooltip" title="Refresh CPPT"><i class="fas fa-sync"></i></button>
            </div>
            <div class="card-body p-3" id="load-riwayat-cppt-kfr">
                <div class="d-flex justify-content-center">
                    <div class="spinner-grow spinner-grow-sm me-2" role="status">
                        <span class="sr-only">Loading...</span>
                    </div> <a class="align-middle">Mengambil Data Riwayat..</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card table-card border shadow-none">
            <div class="card-header d-flex align-items-center justify-content-between p-3">
                <h5 class="card-title mb-0"><i class="fas fa-sort-alpha-down me-1"></i> Riwayat Formulir</h5>
                <div class="dropdown">
                    <a class="avtar avtar-xs btn-light-secondary dropdown-toggle arrow-none" href="javascript: void(0);"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ph-duotone ph-dots-three-outline-vertical"></i></a>
                    <div class="dropdown-menu dropdown-menu-end" style="">
                        <a class="dropdown-item" href="javascript: void(0);"><s>Semua Formulir Pasien</s></a>
                        {{-- <a class="dropdown-item" href="#">Delete</a> --}}
                    </div>
                </div>
            </div>
            <div class="card-body p-3">
                ini row riwayat formulir
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Tutup</button>
                    <button type="button" class="btn btn-danger" onclick="generateFormKfr()"><i class="fas fa-paper-plane me-1"></i> Generate Ulang Formulir</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

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
        $('#btn-refresh-riwayat-cppt').prop('disabled', true).find('i').addClass('fa-spin');
        $.ajax({
            url: '/api/emr/kfr/' + kunjungan + '/cppt',
            type: 'GET',
            success: function(res) {
                if (!res.status) {
                    Swal.fire('Info', res.message, 'info');
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
                                                <b>CPPT Oleh <u>${item.NAMAUSER}</u></b>&nbsp;-&nbsp;<b class="text-pink-900">${tanggal}</b>
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
                $('#btn-refresh-riwayat-cppt').prop('disabled', false).find('i').removeClass('fa-spin');
            },
            error: function (xhr) {
                Swal.fire(
                    'Gagal',
                    xhr.responseJSON?.message ?? 'Terjadi kesalahan',
                    'error'
                );
            }
        });
    }

    function storeFormKfrBaru() {
        let data = {
            _token      : $('meta[name="csrf-token"]').attr('content'),
            // id_cppt     : $('#id_cppt_kfr').val(),
            rm          : $('#rm_kfr').val(),
            kunjungan   : $('#kunjungan_kfr').val(),
            sep         : $('#sep_kfr').val(),
            tgl_sep     : $('#tgl_sep_kfr').val(),
            tgl         : $('#tgl_kfr').val(),

            cppt_s      : $('#cppt_s').val(),
            cppt_o      : $('#cppt_o').val(),
            cppt_a      : $('#cppt_a').val(),

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
                Swal.fire({
                    title: 'Menyimpan...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
            },
            success: function (res) {
                Swal.fire('Berhasil', res.message, 'success');
                loadFormKfr();
                loadCpptKfr();
            },
            error: function (xhr) {
                Swal.fire(
                    'Gagal',
                    xhr.responseJSON?.message ?? 'Terjadi kesalahan',
                    'error'
                );
            }
        });
    }

    function generateFormKfr() {
        const kunjungan = $('#kunjungan_kfr').val();
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
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
            },
            success: function (res) {
                Swal.close();

                Swal.fire('Berhasil', res.message, 'success');

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

    function showFormKfr() {
        let kunjungan = $('#kunjungan_kfr').val();
        $('#show-id-formKFR').text(kunjungan);
        $('#btn-lihat-form-kfr').prop('disabled',true).find('i').removeClass('fa-book-open').addClass('fa-sync fa-spin');

        fetch("/api/emr/kfr/"+kunjungan+"/show")
        .then(response => {
            if (!response.ok) {
                throw new Error('File tidak ditemukan atau gagal diambil.');
            }
            return response.blob();
        })
        .then(blob => {
            // Buat object URL dari blob
            const fileURL = URL.createObjectURL(blob);

            // Tampilkan ke iframe dalam modal
            $('#cetak-formkfr').empty().html(`<iframe src="${fileURL}" width="100%" height="500px" frameborder="0"></iframe>`);
            $('#showFormKfr').modal('show');
            $('#btn-lihat-form-kfr').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-book-open');
        })
        .catch(error => {
            iziToast.error({
                title: 'Maaf!',
                message: 'Data Formulir KFR tidak ditemukan atau belum digenerate.',
                position: 'topRight'
            });
            $('#btn-lihat-form-kfr').prop('disabled',false).find('i').removeClass('fa-sync fa-spin').addClass('fa-book-open');
        });
    }

    function updateFormKfr() {

        let data = {
            _token     : $('meta[name="csrf-token"]').attr('content'),
            id_cppt    : $('#id_cppt_kfr').val(),
            tgl        : $('#tgl_kfr').val(),

            cppt_s     : $('#cppt_s').val(),
            cppt_o     : $('#cppt_o').val(),
            cppt_a     : $('#cppt_a').val(),

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
            success: function(res){
                Swal.fire('Berhasil', res.message, 'success');
                loadFormKfr();
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

    function deleteFormKfr(){

        if(!confirm('Yakin ingin menghapus data ini?')) return;

        $.ajax({
            url  : '/api/emr/kfr/destroy',
            type : 'POST',
            data : {
                id_cppt: $('#id_cppt_kfr').val(),
                _token : $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res){
                if(res.status){
                    Swal.fire('Berhasil', res.message, 'success');
                    kosongiFormKfr();
                    $('#id_cppt_kfr').val('');
                    $('#btn-kosongi-form-kfr').prop('hidden', false);
                    $('#btn-simpan-form-kfr').prop('hidden', false);
                    $('#btn-update-form-kfr').prop('hidden', true);
                    $('#btn-hapus-form-kfr').prop('hidden', true);
                } else {
                    Swal.fire('Perhatian', res.message, 'warning');
                }
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
</script>
