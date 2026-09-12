@php
    $role = $role ?? 'dokter';
    $formId = $formId ?? ('form_ranap_' . $jenis . '_' . $role);
    $formKey = $formKey ?? '';
    // $form = $form ?? '';
    $sub = $sub ?? 'DOKTER';
    $kunjungan = $kunjungan ?? '';
    $initialFinalisasi = $initialFinalisasi ?? null;
    $isInitialFinal = (bool) ($initialFinalisasi['is_final'] ?? false);
@endphp

<div id="{{ $formId }}_finalisasi_action"
    class="position-relative d-flex justify-content-between align-items-center gap-2 mt-4 pt-3 border-top z-4">
    <div>
        <button type="button"
            class="btn btn-success {{ $isInitialFinal ? 'd-none' : '' }}"
            data-finalisasi-action="final" disabled>
            <i class="fa-solid fa-check me-1"></i> Finalisasi
        </button>
        <button type="button"
            class="btn btn-warning {{ $isInitialFinal ? '' : 'd-none' }}"
            data-finalisasi-action="batal" disabled>
            <i class="fa-solid fa-rotate-left me-1"></i> Batal Final
        </button>
    </div>
    <p class="mb-0 text-end" id="{{ $formId }}_final_identity"></p>
</div>

{{-- ==========================================================
    MODAL BATAL FINALISASI
========================================================== --}}
<div class="modal fade"
    id="{{ $formId }}_modal_batal_final"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa-solid fa-rotate-left me-2"></i>
                    Batal Finalisasi
                </h5>

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div class="alert alert-warning">
                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                    Form yang sudah difinalisasi akan dapat diedit kembali.
                </div>

                <label class="form-label fw-semibold">
                    Alasan Pembatalan
                    <span class="text-danger">*</span>
                </label>

                <textarea
                    class="form-control"
                    rows="4"
                    data-finalisasi-reason
                    placeholder="Masukkan alasan pembatalan finalisasi..."></textarea>

                <div class="invalid-feedback"
                    data-finalisasi-reason-error>
                    Alasan pembatalan wajib diisi.
                </div>

            </div>

            <div class="modal-footer">

                <button type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">
                    Batal
                </button>

                <button type="button"
                    class="btn btn-warning"
                    data-finalisasi-submit-batal>
                    <i class="fa-solid fa-rotate-left me-1"></i>
                    Batal Finalisasi
                </button>

            </div>

        </div>
    </div>
</div>

{{-- ==========================================================
    BACKDROP FINALISASI
========================================================== --}}
<div id="{{ $formId }}_finalisasi_backdrop"
    class="position-fixed pe-none bg-white bg-opacity-75 {{ $isInitialFinal ? '' : 'd-none' }} z-3">

    <div class="h-100 d-flex align-items-center justify-content-center text-center px-3">

        <div class="d-inline-flex align-items-center justify-content-center flex-column">

            <div class="fs-1 text-success mb-2">
                <i class="fa-solid fa-lock"></i>
            </div>

            <div class="fw-semibold fs-5">
                Form Telah Difinalisasi
            </div>

            <div class="text-muted small mt-1">
                Form tidak dapat diubah sampai finalisasi dibatalkan.
            </div>

        </div>

    </div>

</div>

<script>
(function(){
    'use strict';

    const formId = @json($formId);
    const formKey = @json($formKey);
    const kunjungan = @json($kunjungan);
    const initialFinalisasi = @json($initialFinalisasi);

    const $form = $('#' + formId);

    if(!$form.length){
        return;
    }

    const $formContent = $form.find('.form-content');
    const $formFields = $formContent.find('.row').first();

    const $action = $('#' + formId + '_finalisasi_action');

    const $finalIdentity = $('#' + formId + '_final_identity');

    const $btnFinal = $action.find(
        '[data-finalisasi-action="final"]'
    );

    const $btnBatal = $action.find(
        '[data-finalisasi-action="batal"]'
    );

    const $backdrop = $('#' + formId + '_finalisasi_backdrop');

    const modalId = formId + '_modal_batal_final';

    const $reason = $('#' + modalId).find(
        '[data-finalisasi-reason]'
    );

    const $reasonError = $('#' + modalId).find(
        '[data-finalisasi-reason-error]'
    );

    const $btnSubmitBatal = $('#' + modalId).find(
        '[data-finalisasi-submit-batal]'
    );

    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    const urlFinalisasi =
        `/api/v2/emr/pengkajian/finalisasi/${kunjungan}`;

    const urlBatalFinalisasi =
        `/api/v2/emr/pengkajian/batal-finalisasi/${kunjungan}`;

    function formatWaktuFinal(waktu) {
        const bulan = [
            'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
            'Jul', 'Agst', 'Sept', 'Okt', 'Nov', 'Des'
        ];

        const m = moment(waktu);

        return `${m.format('DD')} ${bulan[m.month()]} ${m.format('YYYY, [Pukul] HH:mm:ss [WIB]')}`;
    }

    function aktifkanTombolFinalisasi(isFinal) {
        $btnFinal.prop('disabled', isFinal);
        $btnBatal.prop('disabled', !isFinal);
    }

    // ==========================================================
    // BACKDROP
    // ==========================================================

    $backdrop.appendTo($formContent);

    function updateBackdrop(){

        if(!$formContent.length || !$backdrop.length){
            return;
        }

        const formContent = $formContent.get(0);

        // Backdrop dipasang fixed pada area form yang sedang terlihat.
        // Konten dapat discroll di bawah overlay tanpa backdrop ikut bergeser.
        if (!formContent || !document.body.contains(formContent)) {
            return;
        }

        const rect = formContent.getBoundingClientRect();
        const top = Math.max(rect.top, 0);
        const left = Math.max(rect.left, 0);
        const bottom = Math.min(rect.bottom, window.innerHeight);
        const right = Math.min(rect.right, window.innerWidth);
        const isVisible = bottom > top && right > left;

        $backdrop.css({
            top: `${top}px`,
            left: `${left}px`,
            width: `${Math.max(right - left, 0)}px`,
            height: `${Math.max(bottom - top, 0)}px`,
            visibility: isVisible ? 'visible' : 'hidden'
        });
    }

    // ==========================================================
    // TAMPILKAN FINALISASI
    // ==========================================================

    function tampilkanFinalisasi() {
        updateBackdrop();

        $formFields.attr('inert', '');

        $backdrop
            .stop(true, true)
            .removeClass('d-none')
            .show();

        $btnFinal.addClass('d-none');
        $btnBatal.removeClass('d-none');

        aktifkanTombolFinalisasi(true);
    }

    function tampilkanFinalIdentity(data){
        const nama_created = data?.NAMAUSER_CREATED ?? '-';
        const nama_updated = data?.NAMAUSER_UPDATED ?? '-';
        const date_created = data?.CREATED ?? '';
        const date_updated = data?.UPDATED ?? '';

        if (data?.STATUS == 2 && data?.REASON == null) {
            $finalIdentity.empty().html(
                date_created ? `<small>Difinalisasi oleh ${nama_created}<br>Pada ${formatWaktuFinal(date_created)}</small>` : ``
            );
        } else if (data?.STATUS == 2 && data?.REASON != null) {
            $finalIdentity.empty().html(
                date_updated ? `<small>Difinalisasi ulang oleh ${nama_updated}<br>Pada ${formatWaktuFinal(date_updated)}</small>` : ``
            );
        } else if (data?.STATUS == 1) {
            $finalIdentity.empty().html(
                date_updated ? `<small>Dibatal finalisasi oleh ${nama_updated}<br>Pada ${formatWaktuFinal(date_updated)}</small>` : ``
            );
        } else {
            $finalIdentity.empty().html('');
        }
    }

    // ==========================================================
    // SEMBUNYIKAN FINALISASI
    // =========================================================
    function sembunyikanFinalisasi() {
        $formFields.removeAttr('inert');

        $backdrop
            .stop(true, true)
            .hide()
            .addClass('d-none')
            .css('visibility', '');

        $btnFinal.removeClass('d-none');
        $btnBatal.addClass('d-none');

        aktifkanTombolFinalisasi(false);
    }

    // ==========================================================
    // RESIZE
    // ==========================================================

    $(window).on('resize.' + formId,function(){

        if(!$backdrop.hasClass('d-none')){
            updateBackdrop();
        }

    });

    // Capture memastikan scroll pada container mana pun turut
    // memperbarui posisi fixed backdrop.
    window.addEventListener('scroll', function () {
        if (!$backdrop.hasClass('d-none')) {
            updateBackdrop();
        }
    }, true);

    // ==========================================================
    // FINALISASI
    // ==========================================================

    $btnFinal.on('click',function(){

        Swal.fire({
            title: 'Finalisasi Pengkajian?',
            text: 'Setelah difinalisasi, data tidak dapat diubah sampai finalisasi dibatalkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Finalisasi',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then(function(result){

            if(!result.isConfirmed){
                return;
            }

            $btnFinal
                .prop('disabled',true)
                .html(
                    '<i class="fa-solid fa-spinner fa-spin me-1"></i>Memproses...'
                );

            $.ajax({
                url: urlFinalisasi,
                type: 'POST',
                data: {
                    _token: csrfToken,
                    formKey: formKey
                },

                success: function(response){

                    if(!response || !response.status){

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response?.message ||
                                'Finalisasi gagal.'
                        });

                        return;
                    }

                    tampilkanFinalisasi();

                    if (typeof window.updatePenandaFinalisasi === 'function') {
                        window.updatePenandaFinalisasi(formKey, true);
                    }

                    if (typeof window.tampilkanPenandaFinalisasi === 'function') {
                        window.tampilkanPenandaFinalisasi();
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message ||
                            'Pengkajian berhasil difinalisasi.',
                        timer: 1800,
                        showConfirmButton: false
                    });

                },

                error: function(xhr){

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text:
                            xhr.responseJSON?.message ||
                            'Terjadi kesalahan saat melakukan finalisasi.'
                    });

                },

                complete: function(){

                    $btnFinal
                        .prop('disabled',false)
                        .html(
                            '<i class="fa-solid fa-check me-1"></i>Finalisasi'
                        );

                }
            });

        });

    });

    // ==========================================================
    // BATAL FINALISASI - BUKA MODAL
    // ==========================================================

    $btnBatal.on('click',function(){

        $reason
            .val('')
            .removeClass('is-invalid');

        $reasonError.hide();

        const modalElement =
            document.getElementById(modalId);

        if(!modalElement){
            return;
        }

        bootstrap.Modal
            .getOrCreateInstance(modalElement)
            .show();

    });

    // ==========================================================
    // SUBMIT BATAL FINALISASI
    // ==========================================================

    $btnSubmitBatal.on('click',function(){

        const reason =
            String($reason.val() || '').trim();

        if(!reason){

            $reason.addClass('is-invalid');
            $reasonError.show();

            return;
        }

        $reason.removeClass('is-invalid');
        $reasonError.hide();

        $btnSubmitBatal
            .prop('disabled',true)
            .html(
                '<i class="fa-solid fa-spinner fa-spin me-1"></i>Memproses...'
            );

        $.ajax({
            url: urlBatalFinalisasi,
            type: 'POST',
            data: {
                _token: csrfToken,
                formKey: formKey,
                reason: reason
            },

            success: function(response){

                if(!response || !response.status){

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response?.message ||
                            'Pembatalan finalisasi gagal.'
                    });

                    return;
                }

                const modalElement =
                    document.getElementById(modalId);

                if(modalElement){

                    const modal =
                        bootstrap.Modal.getInstance(modalElement);

                    if(modal){
                        modal.hide();
                    }
                }

                sembunyikanFinalisasi();
                $finalIdentity.empty();

                if (typeof window.updatePenandaFinalisasi === 'function') {
                    window.updatePenandaFinalisasi(formKey, false);
                }

                if (typeof window.tampilkanPenandaFinalisasi === 'function') {
                    window.tampilkanPenandaFinalisasi();
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message ||
                        'Finalisasi berhasil dibatalkan.',
                    timer: 1800,
                    showConfirmButton: false
                });

            },
            error: function(xhr){

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text:
                        xhr.responseJSON?.message ||
                        'Terjadi kesalahan saat membatalkan finalisasi.'
                });

            },

            complete: function(){

                $btnSubmitBatal
                    .prop('disabled',false)
                    .html(
                        '<i class="fa-solid fa-rotate-left me-1"></i>Batal Finalisasi'
                    );

            }
        });

    });

    // ==========================================================
    // CEK STATUS FINALISASI
    // ==========================================================

    function cekStatusFinalisasi(){

        // Status sudah diambil saat server merender partial. Terapkan lebih
        // dulu supaya backdrop dan tombol tidak terlambat tampil setelah
        // isi form sempat terlihat.
        if (initialFinalisasi) {
            if (initialFinalisasi.is_final) {
                tampilkanFinalisasi();
                tampilkanFinalIdentity(initialFinalisasi.data);
            } else {
                sembunyikanFinalisasi();
                tampilkanFinalIdentity(initialFinalisasi.data);
            }

            $(document).trigger(
                'finalisasi:status-tab-berubah',
                [formKey, true]
            );

            return;
        }

        const statusUrl =
            `/api/v2/emr/pengkajian/finalisasi/${kunjungan}`;

        $.ajax({
            url: statusUrl,
            type: 'GET',

            data: {
                formKey: formKey
            },

            success: function(response){

                const status = parseInt(
                    response?.data?.STATUS ??
                    response?.data?.status ??
                    response?.STATUS ??
                    response?.status_finalisasi ??
                    1
                );

                if (status === 2) {
                    tampilkanFinalisasi();
                    tampilkanFinalIdentity(response?.data);
                } else {
                    sembunyikanFinalisasi();
                    $finalIdentity.empty();
                }

                $(document).trigger(
                    'finalisasi:status-tab-berubah',
                    [formKey, true]
                );

            },

            error: function(){

                // Jangan mengunci form jika status gagal diperiksa
                sembunyikanFinalisasi();

            }
        });

    }

    // ==========================================================
    // INIT
    // ==========================================================

    cekStatusFinalisasi();

})();
</script>
