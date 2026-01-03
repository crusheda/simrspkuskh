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
                console.log('masuk form kfr');
                loadFormKfr();
                loadCpptKfr();
                loadRiwayatKfr();
            }
            else if (tabId === 'tab-pterapi') {
                console.log('masuk form program terapi');
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
                    $('#btn-generate-form-kfr').prop('hidden', true);
                    $('#btn-lihat-form-kfr').prop('hidden', true);
                    $('#btn-hapus-form-kfr').prop('hidden', true);
                    return;
                }

                if (res.kunjungan_init !== kunjungan) { // Jika Kunjungan / Form KFR Tidak UTAMA
                    // Swal.fire('Info', 'Form KFR ini berasal dari kunjungan sebelumnya. Silakan sinkronisasi data jika ingin menggunakannya pada kunjungan ini.', 'info');
                    $('#btn-kosongi-form-kfr').prop('hidden', true);
                    $('#btn-simpan-form-kfr').prop('hidden', true);
                    $('#form-kfr-utama').prop('hidden', true);
                    $('#btn-update-form-kfr').prop('hidden', true);
                    $('#btn-generate-form-kfr').prop('hidden', true);
                    $('#btn-lihat-form-kfr').prop('hidden', false);
                    $('#btn-hapus-form-kfr').prop('hidden', true);

                    $('#id_cppt_kfr').val(res.id_cppt);

                    $('#cppt_s').val(res.data.s).prop('disabled', true);
                    $('#cppt_o').val(res.data.o).prop('disabled', true);
                    $('#cppt_a').val(res.data.a).prop('disabled', true);

                    $('#cppt_p_1').val(res.data.p1).prop('disabled', true);
                    $('#cppt_p_2').val(res.data.p2).prop('disabled', true);
                    $('#cppt_p_3').val(res.data.p3).prop('disabled', true);
                    $('#cppt_p_4').val(res.data.p4).prop('disabled', true);

                    $('#cppt_i').val(res.data.cppt_i).trigger('change').prop('disabled', true);
                    $('#cppt_i_rtl').val(res.data.cppt_i_rtl).prop('disabled', true);
                } else { // Jika Kunjungan / Form KFR Adalah Form UTAMA
                    $('#btn-kosongi-form-kfr').prop('hidden', true);
                    $('#btn-simpan-form-kfr').prop('hidden', true);
                    $('#form-kfr-utama').prop('hidden', false);
                    $('#btn-update-form-kfr').prop('hidden', false);
                    $('#btn-generate-form-kfr').prop('hidden', false);
                    $('#btn-lihat-form-kfr').prop('hidden', false);
                    $('#btn-hapus-form-kfr').prop('hidden', false);

                    $('#id_cppt_kfr').val(res.id_cppt);

                    $('#cppt_s').val(res.data.s);
                    $('#cppt_o').val(res.data.o);
                    $('#cppt_a').val(res.data.a);

                    $('#cppt_p_1').val(res.data.p1);
                    $('#cppt_p_2').val(res.data.p2);
                    $('#cppt_p_3').val(res.data.p3);
                    $('#cppt_p_4').val(res.data.p4);

                    $('#cppt_i').val(res.data.cppt_i).trigger('change');
                    $('#cppt_i_rtl').val(res.data.cppt_i_rtl);
                }
            }
        });
    }
</script>
