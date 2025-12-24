<div class="row">
    <div class="col-md-8">
        <div class="card table-card border shadow-none">
            <div class="card-header d-flex align-items-center justify-content-between p-3">
                <h5 class="card-title mb-0">Formulir Rawat Jalan KFR</h5>
                <button class="btn btn-info btn-sm" onclick=""><i class="ph-duotone ph-file-search me-1"></i> Gunakan Form Lama</button>
            </div>

            {{-- INIT VALUE --}}
            <input type="hidden" id="kunjungan_kfr" value="{{ $list['KUNJUNGAN'] }}">
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
                                <button class="btn btn-danger" onclick="deleteFormKfr()" id="btn-hapus-form-kfr" hidden>
                                    <i class="fas fa-trash me-1"></i> Hapus Formulir
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
    </div>
    <div class="col-md-4">
        <div class="card table-card border shadow-none">
            <div class="card-header d-flex align-items-center justify-content-between p-3">
                <h5 class="card-title mb-0">Riwayat Formulir</h5>
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

<script>
    $(document).ready(function() {

    });

    function nl(v){
        return (v ?? '').replaceAll("\r\n", "\n");
    }

    function storeFormKfrBaru() {
        let data = {
            _token      : $('meta[name="csrf-token"]').attr('content'),
            // id_cppt     : $('#id_cppt_kfr').val(),
            rm          : $('#rm_kfr').val(),
            kunjungan   : $('#kunjungan_kfr').val(),
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
