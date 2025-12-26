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
        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            const target = $(e.target).data('bsTarget');

            if (target === '#frjkfr') {
                loadFormKfr();
                loadCpptKfr();
                console.log('masuk form kfr');
            } else if (target === '#pterapi') {
                console.log('masuk form program terapi');
            } else {
                console.log('masuk form lainnya');
            }
        });
    });

    function loadFormKfr() {
        kunjungan = $('#kunjungan_kfr').val();
        $.ajax({
            url: '/api/emr/kfr/' + kunjungan,
            type: 'GET',
            success: function(res) {

                if (!res.status) {
                    Swal.fire('Info', res.message, 'info');
                    $('#btn-kosongi-form-kfr').prop('hidden', false);
                    $('#btn-simpan-form-kfr').prop('hidden', false);
                    $('#btn-update-form-kfr').prop('hidden', true);
                    $('#btn-generate-form-kfr').prop('hidden', true);
                    $('#btn-lihat-form-kfr').prop('hidden', true);
                    $('#btn-hapus-form-kfr').prop('hidden', true);
                    return;
                }
                $('#btn-kosongi-form-kfr').prop('hidden', true);
                $('#btn-simpan-form-kfr').prop('hidden', true);
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
        });
    }
</script>
