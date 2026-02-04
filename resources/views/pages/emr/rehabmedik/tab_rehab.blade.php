<div class="card">
    <div class="card-body py-0">
        <ul class="nav nav-tabs profile-tabs" role="tablist">
            @if (Str::startsWith($list['show']->IDRUANGAN, '10207'))
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#frjkfr" role="tab"
                        aria-selected="false" tabindex="-1" id="tab-frjkfr" disabled>
                        <i class="ph-duotone ph-files me-2"></i> Formulir Rawat Jalan KFR
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pterapi" role="tab"
                        aria-selected="false" tabindex="-1" id="tab-pterapi" disabled>
                        <i class="ph-duotone ph-files me-2"></i> Program Terapi
                    </button>
                </li>
            @endif
        </ul>
    </div>
</div>
<div class="tab-content">
    <div class="tab-pane" id="frjkfr" role="tabpanel">
        @include('pages.emr.rehabmedik.form_kfr')
    </div>
</div>
<div class="tab-content">
    <div class="tab-pane" id="pterapi" role="tabpanel">
        @include('pages.emr.rehabmedik.form_program')
    </div>
</div>

<script>
    $(document).ready(function() {
        // $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            // const tabId = $(e.target).attr('id');
        $('button[data-bs-toggle="tab"]').on('click', function () {
            const tabId = $(this).attr('id');
            // console.log(tabId);

            if (tabId === 'tab-frjkfr') {
                // console.log('masuk form kfr');
                loadFormKfr();
                loadCpptKfr();
                loadRiwayatKfr();
            }
            else if (tabId === 'tab-pterapi') {
                batalUpdateFormProgramTerapi();
                loadFormProgramTerapi();
                loadCpptProgramTerapi();
                loadRiwayatProgramTerapi();
            }
            else {
                console.log('tab lain');
            }
        });
    });

    function loadFormKfr() {
        $.ajax({
            url: '/api/emr/kfr/' + kunjungan,
            type: 'GET',
            success: function(res) {
                if (!res.status) { // Jika Tidak Ada Data Kunjungan / Form KFR
                    // Swal.fire('Info', res.message, 'info');
                    $('#btn-kosongi-form-kfr').prop('hidden', false);
                    $('#btn-simpan-form-kfr').prop('hidden', false);
                    $('#form-kfr-utama').prop('hidden', true);
                    $('#btn-update-form-kfr').prop('hidden', true);
                    $('#btn-buka-update-form-kfr').prop('hidden', true);
                    $('#btn-tutup-update-form-kfr').prop('hidden', true);
                    $('#btn-generate-form-kfr').prop('hidden', true);
                    $('#btn-lihat-form-kfr').prop('hidden', true);
                    $('#btn-unsync-form-lama').prop('hidden', true);
                    $('#btn-hapus-form-kfr').prop('hidden', true).prop('disabled', true);

                    $('#id_cppt_kfr').val('');
                    $('#cppt_s').val('').prop('disabled', false);
                    $('#cppt_o').val('').prop('disabled', false);
                    $('#cppt_a').val('').prop('disabled', false);
                    $('#cppt_p_1').val('').prop('disabled', false);
                    $('#cppt_p_2').val('').prop('disabled', false);
                    $('#cppt_p_3').val('').prop('disabled', false);
                    $('#cppt_p_4').val('').prop('disabled', false);
                    $('#cppt_i').val('0').trigger('change').prop('disabled', false);
                    $('#inp_cppt_i_rtl').prop('hidden', true);
                    $('#cppt_i_tgl').prop('required', false).prop('disabled',true);
                    filterTglCpptRtl?.clear();
                    $('#cppt_i_rtl').val('').prop('disabled', false);
                    return;
                }

                if (res.kunjungan_init !== kunjungan) { // Jika Kunjungan / Form KFR Tidak UTAMA
                    // Swal.fire('Info', 'Form KFR ini berasal dari kunjungan sebelumnya. Silakan sinkronisasi data jika ingin menggunakannya pada kunjungan ini.', 'info');
                    $('#btn-kosongi-form-kfr').prop('hidden', true);
                    $('#btn-simpan-form-kfr').prop('hidden', true);
                    $('#form-kfr-utama').prop('hidden', true);
                    $('#btn-update-form-kfr').prop('hidden', true);
                    $('#btn-buka-update-form-kfr').prop('hidden', false);
                    $('#btn-tutup-update-form-kfr').prop('hidden', true);
                    $('#btn-generate-form-kfr').prop('hidden', true);
                    $('#btn-lihat-form-kfr').prop('hidden', false);
                    $('#btn-unsync-form-lama').prop('hidden', false);
                    $('#btn-hapus-form-kfr').prop('hidden', true).prop('disabled', true);

                    $('#id_cppt_kfr').val(res.id_cppt);

                    $('#cppt_s').val(res.data.s).prop('disabled', true);
                    $('#cppt_o').val(res.data.o).prop('disabled', true);
                    $('#cppt_a').val(res.data.a).prop('disabled', true);

                    $('#cppt_p_1').val(res.data.p1).prop('disabled', true);
                    $('#cppt_p_2').val(res.data.p2).prop('disabled', true);
                    $('#cppt_p_3').val(res.data.p3).prop('disabled', true);
                    $('#cppt_p_4').val(res.data.p4).prop('disabled', true);

                    $('#cppt_i').val(res.data.cppt_i).trigger('change').prop('disabled', true);
                    if (res.data.cppt_i == 1) {
                        $('#inp_cppt_i_rtl').prop('hidden', false);
                        $('#cppt_i_tgl').prop('required', true).prop('disabled',true);
                        // filterTglCpptRtl?.setDate(res.data.cppt_i_tgl, true);
                    } else {
                        $('#inp_cppt_i_rtl').prop('hidden', true);
                        $('#cppt_i_tgl').prop('required', false).prop('disabled',true);
                        filterTglCpptRtl?.clear();
                    }
                    $('#cppt_i_rtl').val(res.data.cppt_i_rtl).prop('disabled', true);
                } else { // Jika Kunjungan / Form KFR Adalah Form UTAMA
                    $('#btn-kosongi-form-kfr').prop('hidden', true);
                    $('#btn-simpan-form-kfr').prop('hidden', true);
                    $('#form-kfr-utama').prop('hidden', false);
                    $('#btn-update-form-kfr').prop('hidden', false);
                    $('#btn-buka-update-form-kfr').prop('hidden', true);
                    $('#btn-tutup-update-form-kfr').prop('hidden', true);
                    $('#btn-generate-form-kfr').prop('hidden', false);
                    $('#btn-lihat-form-kfr').prop('hidden', false);
                    $('#btn-unsync-form-lama').prop('hidden', true);

                    if (!res.hidden_delete) {
                        $('#btn-hapus-form-kfr').prop('hidden', false).prop('disabled', false);
                    } else {
                        $('#btn-hapus-form-kfr').prop('hidden', false).prop('disabled', true);
                    }

                    $('#id_cppt_kfr').val(res.id_cppt);

                    $('#cppt_s').val(res.data.s);
                    $('#cppt_o').val(res.data.o);
                    $('#cppt_a').val(res.data.a);

                    $('#cppt_p_1').val(res.data.p1);
                    $('#cppt_p_2').val(res.data.p2);
                    $('#cppt_p_3').val(res.data.p3);
                    $('#cppt_p_4').val(res.data.p4);

                    $('#cppt_i').val(res.data.cppt_i).trigger('change').prop('disabled', false);
                    if (res.data.cppt_i == 1) {
                        $('#inp_cppt_i_rtl').prop('hidden', false);
                        $('#cppt_i_tgl').prop('required', true).prop('disabled',false);
                        // filterTglCpptRtl?.setDate(res.data.cppt_i_tgl, true);
                    } else {
                        $('#inp_cppt_i_rtl').prop('hidden', true);
                        $('#cppt_i_tgl').prop('required', false).prop('disabled',true);
                        filterTglCpptRtl?.clear();
                    }
                    $('#cppt_i_rtl').val(res.data.cppt_i_rtl);
                }
            }, complete: function(res) {
                // pastikan data ada dan input terlihat
                if (res.responseJSON?.data?.cppt_i_tgl) {
                    filterTglCpptRtl?.set('minDate', null);
                    filterTglCpptRtl?.setDate(res.responseJSON.data.cppt_i_tgl, true);
                    // setTimeout(() => {
                    // }, 10); // delay kecil supaya input visible dulu
                } else {
                    filterTglCpptRtl?.clear();
                }
            }
        });
    }

    function loadFormProgramTerapi() {
        $.ajax({
            url: '/api/emr/pterapi/' + kunjungan,
            type: 'GET',
            beforeSend: function() {
                $('#btn-kosongi-form-program-terapi').prop('disabled', true).prop('hidden', false);
                $('#btn-simpan-form-program-Terapi').prop('disabled', true).prop('hidden', false);
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
                    $('#btn-kosongi-form-program-terapi').prop('disabled', true).prop('hidden', false);
                    $('#btn-simpan-form-program-Terapi').prop('disabled', true).prop('hidden', false);
                    return;
                }

                $('#btn-kosongi-form-program-terapi').prop('disabled', false).prop('hidden', false);
                $('#btn-simpan-form-program-Terapi').prop('disabled', false).prop('hidden', false);

                // $('#cppt_s_t').val(res.data.s);
                // $('#cppt_o_t').val(res.data.o);
                // $('#cppt_a_t').val(res.data.a);
                // $('#cppt_p_t').val(res.data.p);

            },
            error: function(xhr) {
                Swal.fire({
                    title: 'Pesan Error!',
                    text: xhr.responseJSON?.message || 'Telah terjadi kesalahan pada saat pengambilan Data Program Terapi',
                    icon: 'error',
                    timer: 10000,
                    timerProgressBar: true
                });
            }
        });
    }
</script>
