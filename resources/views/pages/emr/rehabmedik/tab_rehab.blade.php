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
        //     const target = $(e.target).data('bsTarget');

        //     if (target === '#fmrehab' || target === '#frjkfr') {
        //         // validPageFormKfr();
        //         console.log('form kfr');
        //     } else if (target === '#lpterapi') {
        //         // validPageFormJp();
        //         console.log('form program terapi');
        //     } else {
        //         console.log('form lainnya');
        //     }
        // });
    });
</script>
