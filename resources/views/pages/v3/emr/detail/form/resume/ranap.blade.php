<div class="row" id="form_resume_medis_ranap">
    <div class="col-md-12">
        <div class="card">

            {{-- HEADER --}}
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">

                {{-- HEADER KIRI --}}
                <div class="d-flex align-items-center gap-3">
                    <div class="cppt-modal-icon bg-white bg-opacity-50 text-warning-emphasis">
                        <i class="ph-duotone ph-clipboard-text fs-5"></i>
                    </div>

                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="fw-bold mb-0">
                                Formulir Resume Medis
                            </h5>

                            <span class="badge rounded-pill bg-dark-subtle text-body-secondary"
                                id="badge_final_resume">
                                <i class="ri-loader-line ri-spin"></i>
                            </span>
                        </div>

                        <small class="text-body-secondary">
                            Finalisasi Resume Medis Rawat Inap
                        </small>
                    </div>
                </div>

                {{-- HEADER KANAN: INFO FINALISASI --}}
                <div id="info_final_resume" class="d-none text-end">
                    <div class="d-flex align-items-center justify-content-end gap-2">
                        <i class="ri-shield-check-line text-success fs-5"></i>

                        <div>
                            <div class="fw-semibold text-success" id="info_final_oleh">
                                -
                            </div>

                            <small class="text-body-secondary" id="info_final_tanggal">
                                -
                            </small>
                        </div>
                    </div>
                </div>

            </div>

            {{-- BODY --}}
            <div class="card-body">

                <fieldset id="resume_fields" class="border-0 p-0 m-0">

                    <div class="row">

                        {{-- INDIKASI RAWAT INAP --}}
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <h6>Indikasi Rawat Inap</h6>
                                <textarea
                                    class="form-control"
                                    name="indikasi"
                                    rows="2"
                                    placeholder="Tuliskan indikasi pasien menjalani rawat inap..."></textarea>
                            </div>
                        </div>

                        {{-- RIWAYAT PENYAKIT --}}
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <h6>Ringkasan Penyakit Sekarang</h6>
                                <textarea
                                    class="form-control"
                                    name="rps"
                                    rows="4"></textarea>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <h6>Ringkasan Penyakit Dahulu</h6>
                                <textarea
                                    class="form-control"
                                    name="rpd"
                                    rows="4"></textarea>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <h6>Riwayat Pengobatan Medis</h6>
                                <textarea
                                    class="form-control"
                                    name="rpm"
                                    rows="2"></textarea>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <h6>Riwayat Alergi</h6>
                                <textarea
                                    class="form-control"
                                    name="ra"
                                    rows="2"></textarea>
                            </div>
                        </div>

                        {{-- PEMERIKSAAN FISIK --}}
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <h6>Pemeriksaan Fisik</h6>
                                <textarea
                                    class="form-control"
                                    name="pemeriksaan_fisik"
                                    rows="9"></textarea>
                            </div>
                        </div>

                        {{-- DIAGNOSIS --}}
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="card card-body border border-dashed border-danger mb-0">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="form-group">
                                                <h6>
                                                    Coding Diagnosa <span class="badge text-bg-info ms-1 me-1 p-1 py-0">Utama</span>
                                                    [<b class="text-danger">ICD 10</b>]
                                                </h6>

                                                <textarea
                                                    class="form-control"
                                                    name="diagnosa_utama"
                                                    rows="2"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="form-group">
                                                <h6>
                                                    Coding Diagnosa <span class="badge text-bg-warning ms-1 me-1 p-1 py-0">Sekunder</span>
                                                    [<b class="text-danger">ICD 10</b>]
                                                </h6>

                                                <textarea
                                                    class="form-control"
                                                    name="diagnosa_sekunder"
                                                    rows="2"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="form-group">
                                                <h6>
                                                    Diagnosa
                                                    [<b class="text-danger">ICD 10</b>]
                                                </h6>

                                                <textarea
                                                    class="form-control"
                                                    name="diagnosa_dokter_icd10"
                                                    rows="2"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TINDAKAN --}}
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="card card-body border border-dashed border-success mb-0">
                                    <div class="row">
                                        <div class="col-md-8 mb-3">
                                            <div class="form-group">
                                                <h6>
                                                    Coding Tindakan / Prosedur Lainnya
                                                    [<b class="text-success">ICD 9</b>]
                                                </h6>

                                                <textarea
                                                    class="form-control"
                                                    name="diagnosa_perawat"
                                                    rows="2"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="form-group">
                                                <h6>
                                                    Tindakan
                                                    [<b class="text-success">ICD 9</b>]
                                                </h6>

                                                <textarea
                                                    class="form-control"
                                                    name="diagnosa_dokter_icd9"
                                                    rows="2"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TERAPI --}}
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <h6>Terapi dan Tindakan</h6>
                                <textarea
                                    class="form-control"
                                    name="terapi"
                                    rows="5"></textarea>
                            </div>
                        </div>

                        {{-- KONDISI KELUAR --}}
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <h6>Kondisi Waktu Keluar RS</h6>

                                <div class="d-flex flex-wrap gap-3">

                                    @php
                                        $kondisiKeluar = [
                                            1 => 'Sembuh',
                                            2 => 'Membaik',
                                            3 => 'Belum Sembuh',
                                            4 => 'Meninggal <= 48 Jam',
                                            5 => 'Meninggal > 48 Jam',
                                            6 => 'DOA',
                                            7 => 'Rujuk RS Lain',
                                        ];
                                    @endphp

                                    @foreach ($kondisiKeluar as $value => $label)
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="radio"
                                                name="kondisi_keluar"
                                                id="kondisi_keluar_{{ $value }}"
                                                value="{{ $value }}">

                                            <label
                                                class="form-check-label"
                                                for="kondisi_keluar_{{ $value }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>

                        {{-- CARA KELUAR --}}
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <h6>Cara Keluar / Pasien Pulang</h6>

                                <div class="d-flex flex-wrap gap-3">

                                    @php
                                        $caraKeluar = [
                                            1 => 'Diijinkan Pulang',
                                            2 => 'Pulang Atas Permintaan Sendiri',
                                            3 => 'Dirujuk ke Rumah Sakit Lain',
                                            4 => 'Lari',
                                            5 => 'Pindah RS <Lain></Lain>',
                                            6 => 'Meninggal',
                                            7 => 'Dead On Arrival (DOA)',
                                            8 => 'Lain-lain',
                                        ];
                                    @endphp

                                    @foreach ($caraKeluar as $value => $label)
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="radio"
                                                name="cara_keluar"
                                                id="cara_keluar_{{ $value }}"
                                                value="{{ $value }}">

                                            <label
                                                class="form-check-label"
                                                for="cara_keluar_{{ $value }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>

                        {{-- KONDISI UMUM PASIEN --}}
                        <div class="col-md-12 mb-3">

                            <h6>Kondisi Umum Pasien</h6>

                            <div class="row">

                                <div class="col-md-6">

                                    {{-- TEKANAN DARAH --}}
                                    <div class="form-group mb-3">
                                        <label class="form-label">Tekanan Darah</label>

                                        <div class="input-group">
                                            <input
                                                class="form-control"
                                                type="number"
                                                name="ku_td_up"
                                                min="0"
                                                step="1">
                                            <div class="input-group-text">
                                                /
                                            </div>
                                            <input
                                                class="form-control"
                                                type="number"
                                                name="ku_td_down"
                                                min="0"
                                                step="1">
                                            <div class="input-group-text">
                                                mmHg
                                            </div>
                                        </div>
                                    </div>

                                    {{-- FREKUENSI NAFAS --}}
                                    <div class="form-group mb-3">
                                        <label class="form-label">
                                            Frekuensi Nafas
                                        </label>

                                        <div class="input-group">
                                            <input
                                                class="form-control"
                                                type="number"
                                                name="ku_nafas"
                                                min="0"
                                                step="1">

                                            <div class="input-group-text">
                                                x/Menit
                                            </div>
                                        </div>
                                    </div>

                                    {{-- TINGKAT KESADARAN --}}
                                    <div class="form-group mb-3">
                                        <label class="form-label">
                                            Tingkat Kesadaran
                                        </label>

                                        <select class="form-control" name="ku_tk" id="ku_tk"></select>
                                    </div>

                                </div>

                                <div class="col-md-6">

                                    {{-- FREKUENSI NADI --}}
                                    <div class="form-group mb-3">
                                        <label class="form-label">
                                            Frekuensi Nadi
                                        </label>

                                        <div class="input-group">
                                            <input
                                                class="form-control"
                                                type="number"
                                                name="ku_nadi"
                                                min="0"
                                                step="1">

                                            <div class="input-group-text">
                                                x/Menit
                                            </div>
                                        </div>
                                    </div>

                                    {{-- SUHU --}}
                                    <div class="form-group mb-3">
                                        <label class="form-label">Suhu</label>

                                        <div class="input-group">
                                            <input
                                                class="form-control"
                                                type="number"
                                                name="ku_suhu"
                                                min="0"
                                                step="1">

                                            <div class="input-group-text">
                                                °C
                                            </div>
                                        </div>
                                    </div>

                                    {{-- SKALA NYERI --}}
                                    <div class="form-group mb-3">
                                        <label class="form-label">
                                            Skala Nyeri
                                        </label>

                                        <input
                                            class="form-control"
                                            type="text"
                                            name="ku_sn">
                                    </div>

                                </div>

                            </div>
                        </div>

                        {{-- LAYANAN LANJUTAN --}}
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <h6>Layanan Lanjutan</h6>

                                <div class="d-flex flex-wrap gap-3">

                                    @php
                                        $layananLanjutan = [
                                            1 => 'Poliklinik RS',
                                            2 => 'RS Lain',
                                            3 => 'Puskesmas',
                                            4 => 'Dokter Luar',
                                            5 => 'Komunitas',
                                        ];
                                    @endphp

                                    @foreach ($layananLanjutan as $value => $label)
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="radio"
                                                name="layanan_lanjutan"
                                                id="layanan_lanjutan_{{ $value }}"
                                                value="{{ $value }}" @if ($value==1) checked @endif>

                                            <label
                                                class="form-check-label"
                                                for="layanan_lanjutan_{{ $value }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>

                        {{-- EDUKASI --}}
                        <div class="col-md-12">
                            <div class="form-group">
                                <h6>Edukasi</h6>

                                <textarea
                                    class="form-control"
                                    name="edukasi"
                                    rows="3"></textarea>
                            </div>
                        </div>

                    </div>
                </fieldset>
            </div>

            {{-- FOOTER --}}
            <div class="card-footer d-flex justify-content-left align-items-center flex-wrap gap-2">

                <button
                    type="button"
                    class="btn btn-info"
                    id="btn_print_resume">

                    <i class="ph-duotone ph-printer me-2"></i>
                    Print Preview
                </button>

                    {{-- <button
                        type="button"
                        class="btn btn-success"
                        id="btn_simpan_resume">

                        <i class="ph-duotone ph-floppy-disk me-2"></i>
                        Simpan
                    </button> --}}

                    <button
                        type="button"
                        class="btn btn-primary"
                        id="btn_final_resume">

                        <i class="ph-duotone ph-paper-plane-tilt me-2"></i>
                        Final Resume Medis
                    </button>

                    <button
                        type="button"
                        class="btn btn-danger d-none"
                        id="btn_batal_final_resume">

                        <i class="ph-duotone ph-lock-open me-2"></i>
                        Batal Final
                    </button>

            </div>

        </div>
    </div>
</div>

<script>
(() => {

    // ==========================================================
    // INITIALIZATION
    // ==========================================================

    const $form = $('#form_resume_medis_ranap');

    if (!$form.length) {
        return;
    }

    // Hindari inisialisasi ganda
    if ($form.data('initialized')) {
        return;
    }

    $form.data('initialized', true);

    const $fieldset = $form.find('#resume_fields');

    const $badge = $form.find('#badge_final_resume');

    // TAB RESUME RANAP
    const $tabResume = $('#tab-resume-ranap');
    const $tabBadge = $tabResume.find('#badge_tab_final_resume');

    const $infoFinal = $form.find('#info_final_resume');
    const $infoFinalOleh = $form.find('#info_final_oleh');
    const $infoFinalTanggal = $form.find('#info_final_tanggal');

    // INFORMASI FINALISASI
    let finalMeta = {
        oleh_nama: '',
        tgl_difinal: ''
    };

    // const $btnSimpan = $form.find('#btn_simpan_resume');
    const $btnFinal = $form.find('#btn_final_resume');
    const $btnBatalFinal = $form.find('#btn_batal_final_resume');
    const $btnPrint = $form.find('#btn_print_resume');

    const emrData = window.EMRData || {};

    const kunjungan = emrData.kunjungan;

    if (!kunjungan) {
        console.error('Nomor kunjungan tidak ditemukan pada window.EMRData.');
        return;
    }

    const baseUrl =
        `/api/v2/emr/resume/ranap/${encodeURIComponent(kunjungan)}`;

    const previewUrl =
        `/api/v2/emr/resume/ranap/preview/${encodeURIComponent(kunjungan)}`;

    const csrfToken =
        $('meta[name="csrf-token"]').attr('content');

    let isLoading = false;
    let isSaving = false;
    let isFinal = false;

    let saveTimer = null;

    // ==========================================================
    // INFORMASI FINALISASI
    // ==========================================================
    function setFinalMeta(response = {}) {

        finalMeta = {
            oleh_nama: response.oleh_nama ?? '',
            tgl_difinal: response.tgl_difinal_text ?? response.tgl_difinal ?? ''
        };

        console.log('Final Meta:', finalMeta);
    }

    // ==========================================================
    // UPDATE BADGE TAB RESUME
    // ==========================================================
    function updateTabFinalStatus() {

        if (!$tabResume.length) {
            return;
        }

        if (isLoading) {

            $tabBadge
                .removeClass('bg-success bg-danger bg-warning text-dark')
                .addClass('bg-dark-subtle text-body-secondary')
                .html('<i class="ri-loader-line ri-spin"></i>');

            $tabResume.attr('title', 'Memuat status Resume Medis...');

            return;
        }

        if (isFinal) {

            $tabBadge
                .removeClass('bg-danger bg-warning text-dark bg-dark-subtle text-body-secondary')
                .addClass('bg-success')
                .text('Sudah Final');

            const detail = [];

            if (finalMeta.oleh_nama) {
                detail.push(`Difinalkan oleh ${finalMeta.oleh_nama}`);
            }

            if (finalMeta.tgl_difinal) {
                detail.push(finalMeta.tgl_difinal);
            }

            const informasi = detail.join(' · ');

            $tabResume.attr(
                'title',
                informasi || 'Resume Medis sudah difinalisasi'
            );

        } else {

            $tabBadge
                .removeClass('bg-success bg-warning text-dark bg-dark-subtle text-body-secondary')
                .addClass('bg-danger')
                .text('Belum Final');

            $tabResume.attr(
                'title',
                'Resume Medis belum difinalisasi'
            );
        }
    }

    function updateInfoFinalResume() {

        if (isLoading) {
            $infoFinal.addClass('d-none');
            return;
        }

        if (isFinal) {

            $infoFinalOleh.text(
                finalMeta.oleh_nama || 'Petugas tidak diketahui'
            );

            $infoFinalTanggal.text(
                finalMeta.tgl_difinal || '-'
            );

            $infoFinal.removeClass('d-none');

        } else {

            $infoFinal.addClass('d-none');

            $infoFinalOleh.text('');
            $infoFinalTanggal.text('');
        }
    }

    // ==========================================================
    // NOTIFICATION
    // ==========================================================

    function notify(type, title, message) {

        if (typeof iziToast !== 'undefined') {

            iziToast[type]({
                title: title,
                message: message,
                position: 'topRight',
                timeout: 3000
            });

            return;
        }

        console.log(`${title}: ${message}`);
    }


    // ==========================================================
    // CONFIRMATION
    // ==========================================================

    async function confirmAction(title, text, confirmText) {

        if (typeof Swal !== 'undefined') {

            const result = await Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: 'Batal',
                reverseButtons: true
            });

            return result.isConfirmed;
        }

        return window.confirm(text);
    }

    // ==========================================================
    // STATUS FORM
    // ==========================================================
    function updateFormStatus() {

        $fieldset.prop(
            'disabled',
            isLoading || isSaving || isFinal
        );

        // BADGE STATUS FINALISASI
        if (isLoading) {

            $badge
                .removeClass('bg-success bg-danger bg-warning text-dark')
                .addClass('bg-dark-subtle text-body-secondary')
                .html('<i class="ri-loader-line ri-spin"></i>');

        } else if (isFinal) {

            $badge
                .removeClass('bg-danger bg-warning text-dark bg-dark-subtle text-body-secondary')
                .addClass('bg-success')
                .text('Sudah Final');

            $btnFinal.addClass('d-none');
            $btnBatalFinal.removeClass('d-none');

        } else {

            $badge
                .removeClass('bg-success bg-warning text-dark bg-dark-subtle text-body-secondary')
                .addClass('bg-danger')
                .text('Belum Final');

            $btnFinal.removeClass('d-none');
            $btnBatalFinal.addClass('d-none');
        }

        // UPDATE BADGE PADA TAB UTAMA
        updateTabFinalStatus();
        updateInfoFinalResume();

        const busy = isLoading || isSaving;

        $btnFinal.prop('disabled', busy);
        $btnBatalFinal.prop('disabled', busy);
        $btnPrint.prop('disabled', busy);
    }

    // ==========================================================
    // GET FORM DATA
    // ==========================================================

    function getFormData() {

        const data = {};

        $fieldset.find('[name]').each(function () {

            const field = this;

            const name = field.name;
            const type = (field.type || '').toLowerCase();

            // RADIO
            if (type === 'radio') {

                if (!(name in data)) {
                    data[name] = null;
                }

                if (field.checked) {
                    data[name] = field.value;
                }

                return;
            }

            // CHECKBOX
            if (type === 'checkbox') {

                data[name] = field.checked
                    ? (field.value || 1)
                    : null;

                return;
            }

            // INPUT / TEXTAREA / SELECT
            const value = $(field).val();

            data[name] = value === '' ? null : value;
        });

        return data;
    }


    // ==========================================================
    // SET FORM DATA
    // ==========================================================

    function setFormData(data = {}) {

        $fieldset.find('[name]').each(function () {

            const field = this;

            const name = field.name;
            const type = (field.type || '').toLowerCase();

            const value = data[name] ?? null;

            if (type === 'radio') {

                field.checked =
                    value !== null &&
                    String(field.value) === String(value);

                return;
            }

            if (type === 'checkbox') {

                field.checked =
                    value !== null &&
                    String(field.value) === String(value);

                return;
            }

            $(field).val(value ?? '');
        });
    }


    // ==========================================================
    // GET DATA
    // ==========================================================
    function setFieldValue(section, name, value = null) {

        const $fields = section.find(`[name="${name}"]`);

        if (!$fields.length) return;

        const type = ($fields.first().attr('type') || '').toLowerCase();

        // Radio
        if (type === 'radio') {
            $fields.each(function () {
                this.checked =
                    value !== null &&
                    String(this.value) === String(value);
            });

            return;
        }

        // Checkbox
        if (type === 'checkbox') {
            $fields.each(function () {
                this.checked =
                    value !== null &&
                    String(this.value) === String(value);
            });

            return;
        }

        // Text, Number, Textarea, Select
        $fields.val(value ?? '');
    }

    // ==========================================================
    // GET DATA
    // ==========================================================
    async function getData() {

        if (isLoading) {
            return;
        }

        isLoading = true;
        updateFormStatus();

        try {

            const response = await $.ajax({
                url: baseUrl,
                type: 'GET',
                dataType: 'json'
            });

            const data = response.data || {};
            const old = response.old || {};
            const ref = response.ref || {};

            setFieldValue($form, 'indikasi',
                data.indikasi || old.indikasi || ''
            );
            setFieldValue($form, 'rps',
                data.rps || old.rps || ''
            );
            setFieldValue($form, 'rpd',
                data.rpd || old.rpd || ''
            );
            setFieldValue($form, 'rpm',
                data.rpm || old.rpm || ''
            );
            setFieldValue($form, 'ra',
                data.ra || old.ra || ''
            );
            setFieldValue($form, 'pemeriksaan_fisik',
                data.pemeriksaan_fisik || old.pemeriksaan_fisik || ''
            );
            setFieldValue($form, 'diagnosa_utama',
                data.diagnosa_utama || old.diagnosa_utama || ''
            );
            setFieldValue($form, 'diagnosa_sekunder',
                data.diagnosa_sekunder || old.diagnosa_sekunder || ''
            );
            setFieldValue($form, 'diagnosa_dokter_icd10',
                data.diagnosa_dokter_icd10 || old.diagnosa_dokter_icd10 || ''
            );
            setFieldValue($form, 'diagnosa_perawat',
                data.diagnosa_perawat || old.diagnosa_perawat || ''
            );
            setFieldValue($form, 'diagnosa_dokter_icd9',
                data.diagnosa_dokter_icd9 || old.diagnosa_dokter_icd9 || ''
            );
            setFieldValue($form, 'terapi',
                data.terapi || old.terapi || ''
            );
            setFieldValue($form, 'kondisi_keluar',
                data.kondisi_keluar || old.kondisi_keluar || ''
            );
            setFieldValue($form, 'cara_keluar',
                data.cara_keluar || old.cara_keluar || ''
            );

            // TINGKAT KESADARAN
            let select = $form.find('[name="ku_tk"]');
            select.empty().append('<option value="">Pilih Tingkat Kesadaran</option>');
            $.each(ref.tingkat_kesadaran, function (index, item) {
                select.append(
                    $('<option>', {
                        value: item.ID,
                        text: item.DESKRIPSI
                    })
                );
            });
            setFieldValue($form, 'ku_tk',
                data.ku_tk || old.ku_tk || ''
            );

            setFieldValue($form, 'ku_td_up',
                data.ku_td_up || old.ku_td_up || ''
            );
            setFieldValue($form, 'ku_td_down',
                data.ku_td_down || old.ku_td_down || ''
            );
            setFieldValue($form, 'ku_nafas',
                data.ku_nafas || old.ku_nafas || ''
            );
            setFieldValue($form, 'ku_sn',
                data.ku_sn || old.ku_sn || ''
            );
            setFieldValue($form, 'ku_nadi',
                data.ku_nadi || old.ku_nadi || ''
            );
            setFieldValue($form, 'ku_suhu',
                data.ku_suhu || old.ku_suhu || ''
            );

            setFieldValue($form, 'edukasi',
                data.edukasi || old.edukasi || ''
            );

            isFinal = Number(data.status_final || 0) === 1;

            // Ambil informasi siapa dan kapan finalisasi
            setFinalMeta(response);

            updateFormStatus();

        } catch (xhr) {

            console.error(
                'Gagal mengambil data Resume Medis:',
                xhr.responseText || xhr
            );

            notify(
                'error',
                'Gagal',
                xhr.responseJSON?.message ||
                'Gagal mengambil data Resume Medis.'
            );

        } finally {

            isLoading = false;
            updateFormStatus();
        }
    }


    // ==========================================================
    // SIMPAN DATA
    // ==========================================================

    async function simpanData(finalisasi = false) {

        if (isLoading || isSaving || isFinal) {
            return false;
        }

        isSaving = true;
        updateFormStatus();

        const payload = {
            ...getFormData(),
            NOKUNJ: kunjungan,
            finalisasi: finalisasi ? 1 : 0
        };

        try {

            const response = await $.ajax({

                url: `${baseUrl}/simpan`,

                type: 'POST',

                data: payload,

                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },

                dataType: 'json'
            });

            isFinal = Number(response.data?.status_final || 0) === 1;

            setFinalMeta(response.data || {});

            if (finalisasi) {

                notify(
                    'success',
                    'Finalisasi Berhasil',
                    'Resume Medis berhasil difinalisasi.'
                );

            } else if (!isFinal) {

                notify(
                    'success',
                    'Berhasil',
                    'Data Resume Medis berhasil disimpan.'
                );
            }

            updateFormStatus();

            return true;

        } catch (xhr) {

            let message =
                'Data Resume Medis gagal disimpan.';

            if (
                xhr.status === 422 &&
                xhr.responseJSON?.errors
            ) {

                message = Object
                    .values(xhr.responseJSON.errors)
                    .flat()
                    .join('<br>');

            } else if (xhr.responseJSON?.message) {

                message = xhr.responseJSON.message;
            }

            notify(
                'error',
                'Gagal Menyimpan',
                message
            );

            console.error(
                'Error simpan Resume Medis:',
                xhr.responseText || xhr
            );

            return false;

        } finally {

            isSaving = false;
            updateFormStatus();
        }
    }


    // ==========================================================
    // BATAL FINAL
    // ==========================================================

    async function batalFinal() {

        if (isLoading || isSaving || !isFinal) {
            return;
        }

        const confirmed = await confirmAction(
            'Batal Finalisasi?',
            'Resume Medis akan dibuka kembali dan dapat diedit.',
            'Ya, Batal Final'
        );

        if (!confirmed) {
            return;
        }

        isSaving = true;
        updateFormStatus();

        try {

            const response = await $.ajax({

                url: `${baseUrl}/batal`,

                type: 'POST',

                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },

                dataType: 'json'
            });

            isFinal = false;

            finalMeta = {
                oleh_nama: '',
                tgl_difinal: ''
            };

            updateFormStatus();

            notify(
                'success',
                'Berhasil',
                response.message ||
                'Finalisasi Resume Medis berhasil dibatalkan.'
            );

        } catch (xhr) {

            notify(
                'error',
                'Gagal',
                xhr.responseJSON?.message ||
                'Gagal membatalkan finalisasi Resume Medis.'
            );

            console.error(
                'Error batal final:',
                xhr.responseText || xhr
            );

        } finally {

            isSaving = false;
            updateFormStatus();
        }
    }


    // ==========================================================
    // PRINT PREVIEW
    // ==========================================================

    async function printPreview() {

        if (isLoading || isSaving) {
            return;
        }

        // Buka tab terlebih dahulu agar tidak diblokir popup blocker.
        const previewWindow = window.open('about:blank', '_blank');

        if (!previewWindow) {

            notify(
                'warning',
                'Popup Diblokir',
                'Izinkan popup browser untuk membuka Print Preview.'
            );

            return;
        }

        // Pastikan data terakhir tersimpan sebelum preview.
        if (!isFinal) {

            const saved = await simpanData(false);

            if (!saved) {
                previewWindow.close();
                return;
            }
        }

        previewWindow.location.href = previewUrl;
    }


    // ==========================================================
    // AUTO SAVE
    // ==========================================================

    function scheduleAutoSave() {

        if (
            isLoading ||
            isSaving ||
            isFinal
        ) {
            return;
        }

        clearTimeout(saveTimer);

        saveTimer = setTimeout(() => {
            simpanData(false);
        }, 800);
    }


    // ==========================================================
    // EVENT HANDLERS
    // ==========================================================

    // Auto-save ketika mengetik.
    $form.on(
        'input',
        '#resume_fields textarea, #resume_fields input:not([type="radio"]):not([type="checkbox"])',
        function () {
            scheduleAutoSave();
        }
    );

    // Auto-save ketika radio/select/checkbox berubah.
    $form.on(
        'change',
        '#resume_fields select, #resume_fields input[type="radio"], #resume_fields input[type="checkbox"]',
        function () {
            scheduleAutoSave();
        }
    );

    // Tombol simpan.
    // $btnSimpan.on('click', function () {

    //     clearTimeout(saveTimer);

    //     simpanData(false);
    // });

    // Tombol finalisasi.
    $btnFinal.on('click', async function () {

        clearTimeout(saveTimer);

        const confirmed = await confirmAction(
            'Finalisasi Resume Medis?',
            'Setelah difinalisasi, seluruh isian Resume Medis akan dikunci dan tidak dapat diubah sampai dilakukan Batal Final.',
            'Ya, Finalisasi'
        );

        if (!confirmed) {
            return;
        }

        await simpanData(true);
    });

    // Tombol batal final.
    $btnBatalFinal.on('click', function () {

        batalFinal();
    });

    // Tombol print preview.
    $btnPrint.on('click', function () {

        printPreview();
    });


    // ==========================================================
    // LOAD AWAL
    // ==========================================================

    getData();

})();
</script>
