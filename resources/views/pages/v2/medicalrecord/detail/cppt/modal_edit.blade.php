{{-- ========================================================= --}}
{{-- MODAL EDIT CPPT --}}
{{-- Letakkan di luar / setelah modalCPPT --}}
{{-- ========================================================= --}}
<div class="modal fade"
    id="modalEditCPPT"
    tabindex="-1"
    aria-labelledby="modalEditCPPTLabel"
    aria-hidden="true"
    data-bs-backdrop="static"
    data-bs-keyboard="false">

    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            {{-- HEADER --}}
            <div class="modal-header bg-warning-subtle text-dark border-0 px-4 py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="cppt-modal-icon bg-white bg-opacity-50 text-warning-emphasis">
                        <i class="ph-duotone ph-note-pencil fs-24"></i>
                    </div>

                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="fw-bold mb-0" id="modalEditCPPTLabel">
                                Ubah CPPT
                            </h5>

                            <span class="badge rounded-pill bg-dark text-white"
                                id="edit_cppt_mode_badge">
                                CPPT Biasa
                            </span>
                        </div>

                        <small class="text-dark-emphasis">
                            Perbarui Catatan Perkembangan Pasien Terintegrasi
                        </small>
                    </div>
                </div>

                <button type="button"
                        class="btn-close"
                        aria-label="Tutup"
                        onclick="batalEditCPPT()">
                </button>
            </div>

            {{-- BODY --}}
            <div class="modal-body p-3 p-md-4">

                <input type="hidden" id="edit_cppt_id">
                <input type="hidden" id="edit_cppt_kunjungan">
                <input type="hidden" id="edit_cppt_ppa_id">

                {{-- Informasi pasien --}}
                <div class="cppt-patient-info rounded-3 p-3 mb-4">
                    <div class="row g-3" id="edit_cppt_header">
                        {{-- Diisi lewat JavaScript --}}
                    </div>
                </div>

                {{-- Informasi waktu dan PPA --}}
                <div class="card border border-warning-subtle shadow-none rounded-3 mb-4">
                    <div class="card-header bg-warning-subtle border-0 py-2 px-3">
                        <div class="d-flex align-items-center gap-2 text-warning-emphasis">
                            <i class="ph-duotone ph-calendar-check fs-20"></i>
                            <strong>Informasi Catatan</strong>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Tanggal</label>
                                <input type="date"
                                    class="form-control"
                                    id="edit_cppt_tanggal">
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Jam</label>
                                <input type="time"
                                    class="form-control"
                                    id="edit_cppt_jam">
                            </div>

                            <div class="col-md-7">
                                <label class="form-label fw-semibold">
                                    PPA
                                    <small class="text-muted fw-normal">(Pencatat CPPT)</small>
                                </label>

                                <input type="text"
                                    class="form-control bg-light"
                                    id="edit_cppt_ppa"
                                    readonly>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ========================================================= --}}
                {{-- MODE CPPT BIASA --}}
                {{-- ========================================================= --}}
                <div id="edit_mode_biasa" class="row g-3">

                    <div class="col-12">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge text-bg-primary">SOAP + I</span>
                            <small class="text-muted">Catatan perkembangan reguler</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            S <small class="text-muted">(Subjective)</small>
                        </label>

                        <textarea class="form-control"
                                id="edit_cppt_s"
                                rows="5"
                                placeholder="Keluhan atau kondisi yang dirasakan pasien..."></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            O <small class="text-muted">(Objective)</small>
                        </label>

                        <textarea class="form-control"
                                id="edit_cppt_o"
                                rows="5"
                                placeholder="Hasil pemeriksaan objektif pasien..."></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            A <small class="text-muted">(Assessment)</small>
                        </label>

                        <textarea class="form-control"
                                id="edit_cppt_a"
                                rows="5"
                                placeholder="Assessment atau diagnosis pasien..."></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            P <small class="text-muted">(Planning)</small>
                        </label>

                        <textarea class="form-control"
                                id="edit_cppt_p"
                                rows="5"
                                placeholder="Rencana terapi atau tindak lanjut..."></textarea>
                    </div>
                </div>

                {{-- ========================================================= --}}
                {{-- MODE SBAR --}}
                {{-- ========================================================= --}}
                <div id="edit_mode_sbar" class="row g-3 d-none">

                    <div class="col-12">
                        <div class="alert alert-primary border-0 d-flex gap-2 align-items-center mb-0">
                            <i class="ph-duotone ph-chat-circle-text fs-22"></i>
                            <div>
                                <strong>Mode SBAR</strong>
                                <div class="small">Situation, Background, Assessment, Recommendation.</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Situation <small class="text-muted">(Situasi)</small>
                        </label>

                        <textarea class="form-control"
                                id="edit_sbar_situation"
                                rows="5"
                                placeholder="Kondisi pasien saat ini..."></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Background <small class="text-muted">(Latar Belakang)</small>
                        </label>

                        <textarea class="form-control"
                                id="edit_sbar_background"
                                rows="5"
                                placeholder="Riwayat atau informasi pendukung..."></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Assessment <small class="text-muted">(Penilaian)</small>
                        </label>

                        <textarea class="form-control"
                                id="edit_sbar_assessment"
                                rows="5"
                                placeholder="Hasil penilaian kondisi pasien..."></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">
                            Recommendation <small class="text-muted">(Rekomendasi)</small>
                        </label>

                        <textarea class="form-control"
                                id="edit_sbar_recommendation"
                                rows="5"
                                placeholder="Rekomendasi atau tindak lanjut..."></textarea>
                    </div>

                    <div class="col-md-6 position-relative">
                        <label class="form-label fw-semibold">Dokter yang Dihubungi</label>

                        <input type="text"
                                class="form-control"
                                id="edit_sbar_dokter"
                                placeholder="Cari nama atau NIP dokter..."
                                autocomplete="off">

                        <input type="hidden" id="edit_sbar_dokter_id">

                        <div id="edit_sbar_dokter_autocomplete"
                            class="list-group position-absolute start-0 end-0 shadow-sm bg-body"
                            style="z-index: 1060; display: none;">
                        </div>
                    </div>
                </div>

                {{-- ========================================================= --}}
                {{-- MODE TBAK --}}
                {{-- ========================================================= --}}
                <div id="edit_mode_tbak" class="row g-3 d-none">

                    <div class="col-12">
                        <div class="alert alert-success border-0 d-flex gap-2 align-items-center mb-0">
                            <i class="ph-duotone ph-phone-call fs-22"></i>
                            <div>
                                <strong>Mode TBAK</strong>
                                <div class="small">Tulis, Baca, Konfirmasi.</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Tulis</label>

                        <textarea class="form-control"
                                id="edit_tbak_tulis"
                                rows="6"
                                placeholder="Tuliskan informasi yang disampaikan kepada dokter..."></textarea>
                    </div>

                    <div class="col-md-3">
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input"
                                    type="checkbox"
                                    role="switch"
                                    id="edit_tbak_baca">

                            <label class="form-check-label fw-semibold"
                                   for="edit_tbak_baca">
                                Sudah Baca
                            </label>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input"
                                    type="checkbox"
                                    role="switch"
                                    id="edit_tbak_konfirmasi">

                            <label class="form-check-label fw-semibold" for="edit_tbak_konfirmasi">
                                Sudah Konfirmasi
                            </label>
                        </div>
                    </div>

                    <div class="col-md-6 position-relative">
                        <label class="form-label fw-semibold">Dokter yang Dihubungi</label>

                        <input type="text"
                                class="form-control"
                                id="edit_tbak_dokter"
                                placeholder="Cari nama atau NIP dokter..."
                                autocomplete="off">

                        <input type="hidden" id="edit_tbak_dokter_id">

                        <div id="edit_tbak_dokter_autocomplete"
                            class="list-group position-absolute start-0 end-0 shadow-sm bg-body"
                            style="z-index: 1060; display: none;">
                        </div>
                    </div>
                </div>

                {{-- Instruksi tampil untuk seluruh mode --}}
                <div id="edit_instruksi_cppt" class="card border border-info-subtle shadow-none rounded-3 mt-4">
                    <div class="card-header bg-info-subtle border-0 py-2 px-3">
                        <div class="d-flex align-items-center gap-2 text-info-emphasis">
                            <i class="ph-duotone ph-megaphone-simple fs-20"></i>
                            <strong>Instruksi / Handover</strong>
                        </div>
                    </div>

                    <div class="card-body">
                        <textarea class="form-control"
                                id="edit_cppt_instruksi"
                                rows="4"
                                placeholder="Instruksi untuk tindak lanjut pasien..."></textarea>
                    </div>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="modal-footer border-0 bg-body-tertiary px-4 py-3">
                <button type="button"
                        class="btn btn-outline-dark border-dashed waves-effect waves-light me-2"
                        onclick="batalEditCPPT()">
                    <i class="ph ph-arrow-u-up-left me-1"></i>
                    Batal & Kembali
                </button>

                <button type="button"
                        class="btn btn-warning"
                        id="btn-update-cppt"
                        onclick="simpanEditCPPT()">
                    <i class="ph-duotone ph-floppy-disk me-1"></i>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let editCPPTKembaliKeRiwayat = false;
    let editCPPTPerluRefresh = false;

    $(document).ready(function() {
        initModalEditCPPT();
    })

    function bersihkanTooltipCPPT(button) {
        if (button) {
            $(button).tooltip('hide');
            $(button).tooltip('dispose');
        }

        // Hapus tooltip yang mungkin masih tertinggal di body.
        $('.tooltip').remove();
    }

    function batalEditCPPT() {
        editCPPTKembaliKeRiwayat = true;
        editCPPTPerluRefresh = false;

        $('#modalEditCPPT').modal('hide');
    }

    function initModalEditCPPT() {
        $('#modalEditCPPT').off('hidden.bs.modal.editCPPT');

        $('#modalEditCPPT').on('hidden.bs.modal.editCPPT', function () {
            if (!editCPPTKembaliKeRiwayat) return;

            editCPPTKembaliKeRiwayat = false;

            // Setelah Simpan: ambil ulang riwayat dari server.
            if (editCPPTPerluRefresh) {
                editCPPTPerluRefresh = false;
                showModalCppt(currentKunjunganCppt);
                return;
            }

            // Setelah Batal: kembali ke riwayat yang sudah terbuka sebelumnya.
            $('#modalCPPT').modal('show');
        });
    }

    function setBadgeModeEditCPPT(mode) {
        const $badge = $('#edit_cppt_mode_badge');

        $badge.removeClass('bg-primary bg-success bg-dark');

        if (mode === 'SBAR') {
            $badge.text('Mode SBAR').addClass('bg-primary');
            return;
        }

        if (mode === 'TBAK') {
            $badge.text('Mode TBAK').addClass('bg-success');
            return;
        }

        $badge.text('CPPT Biasa').addClass('bg-dark');
    }

    function modeDariCPPT(data) {
        const mode = String(data.TBAK_SBAR || '').trim().toUpperCase();

        if (mode === 'SBAR') return 'SBAR';
        if (mode === 'TBAK') return 'TBAK';

        return 'BIASA';
    }

    function htmlKeTextCPPT(html) {
        if (!html) {
            return '';
        }

        const $temp = $('<div>').html(String(html));

        // Buang Simple Translate
        $temp.find('#simple-translate').remove();

        // Buang elemen dari extension
        $temp.find('[src^="chrome-extension://"]').remove();
        $temp.find('[href^="chrome-extension://"]').remove();

        // HTML -> text
        $temp.find('br').replaceWith('\n');

        return $temp
            .text()
            .replace(/\u00a0/g, ' ')
            .replace(/\r\n/g, '\n')
            .replace(/\n{3,}/g, '\n\n')
            .trim();
    }

    function escapeHtmlCPPT(value) {
        return $('<div>').text(value ?? '-').html();
    }

    function isiHeaderEditCPPT(data) {
        const header = `
            <div class="col-md-5">
                <div class="small text-muted">Pasien</div>
                <div class="fw-bold text-truncate">
                    ${escapeHtmlCPPT(data.NAMAPASIEN)}
                </div>
            </div>

            <div class="col-md-2">
                <div class="small text-muted">No. RM</div>
                <div class="fw-bold">
                    ${escapeHtmlCPPT(data.NORM)}
                </div>
            </div>

            <div class="col-md-3">
                <div class="small text-muted">Kunjungan</div>
                <div class="fw-bold">
                    ${escapeHtmlCPPT(data.KUNJUNGAN)}
                </div>
            </div>

            <div class="col-md-2">
                <div class="small text-muted">CPPT</div>
                <span class="badge text-bg-warning">
                    ${Number(data.COUNT_CPPT || 0)} Catatan
                </span>
            </div>
        `;

        $('#edit_cppt_header').html(header);
    }

    function ambilBagianCatatanCPPT(html, label, labelBerikutnya) {
        const semuaLabel = labelBerikutnya
            .map(function (item) {
                return '<b[^>]*>\\s*' + item + '\\s*\\/\\s*:\\s*<\\/b>';
            })
            .join('|');

        const polaAwal = '<b[^>]*>\\s*' + label + '\\s*\\/\\s*:\\s*<\\/b>';
        const regex = new RegExp(
            polaAwal + '([\\s\\S]*?)(?=' + semuaLabel + '|$)',
            'i'
        );

        const hasil = String(html || '').match(regex);

        return hasil ? htmlKeTextCPPT(hasil[1]) : '';
    }

    function formatTanggalInputCPPT(tanggal) {
        const value = String(tanggal || '').trim();

        // Sudah format YYYY-MM-DD
        if (/^\d{4}-\d{2}-\d{2}/.test(value)) {
            return value.substring(0, 10);
        }

        // Format dari API: DD-MM-YYYY HH:mm:ss
        const hasil = value.match(/^(\d{2})-(\d{2})-(\d{4})/);

        if (!hasil) return '';

        return hasil[3] + '-' + hasil[2] + '-' + hasil[1];
    }

    function formatJamInputCPPT(tanggal) {
        const hasil = String(tanggal || '').match(/(\d{2}:\d{2})(?::\d{2})?$/);

        return hasil ? hasil[1] : '';
    }

    function ubahModeEditCPPT(mode) {

        // Sembunyikan semua mode
        $('#edit_mode_biasa, #edit_mode_sbar, #edit_mode_tbak')
            .addClass('d-none')
            .find(':input')
            .prop('disabled', true);

        // Instruksi default: sembunyikan + disable
        $('#edit_instruksi_cppt')
            .addClass('d-none')
            .find(':input')
            .prop('disabled', true);

        if (mode === 'SBAR') {

            $('#edit_mode_sbar')
                .removeClass('d-none')
                .find(':input')
                .prop('disabled', false);

            return;
        }

        if (mode === 'TBAK') {

            $('#edit_mode_tbak')
                .removeClass('d-none')
                .find(':input')
                .prop('disabled', false);

            return;
        }

        // CPPT Biasa
        $('#edit_mode_biasa')
            .removeClass('d-none')
            .find(':input')
            .prop('disabled', false);

        // Instruksi hanya CPPT Biasa
        $('#edit_instruksi_cppt')
            .removeClass('d-none')
            .find(':input')
            .prop('disabled', false);
    }

    function resetFormEditCPPT() {
        $('#modalEditCPPT')
            .find('input[type="text"], input[type="hidden"], textarea')
            .val('');

        $('#modalEditCPPT')
            .find('input[type="checkbox"]')
            .prop('checked', false);

        $('#edit_cppt_tanggal, #edit_cppt_jam').val('');
    }

    function isiFormEditCPPT(data) {

        const mode = modeDariCPPT(data);

        resetFormEditCPPT();
        ubahModeEditCPPT(mode);
        setBadgeModeEditCPPT(mode);

        isiHeaderEditCPPT(data);

        $('#edit_cppt_id').val(data.ID || '');
        $('#edit_cppt_kunjungan').val(data.KUNJUNGAN || '');
        $('#edit_cppt_tanggal').val(data.TANGGAL || '');
        $('#edit_cppt_jam').val(data.JAM || '');
        $('#edit_cppt_ppa').val(data.PPA || '');
        $('#edit_cppt_ppa_id').val(data.PPA_ID || '');

        if (mode === 'SBAR') {

            $('#edit_sbar_situation').val(data.SUBYEKTIF || '');
            $('#edit_sbar_background').val(data.OBYEKTIF || '');
            $('#edit_sbar_assessment').val(data.ASSESMENT || '');
            $('#edit_sbar_recommendation').val(data.PLANNING || '');

            $('#edit_sbar_dokter').val(data.DOKTER || '');
            $('#edit_sbar_dokter_id').val(data.DOKTER_ID || '');

        } else if (mode === 'TBAK') {

            $('#edit_tbak_tulis').val(data.TULIS || '');

            $('#edit_tbak_baca')
                .prop('checked', Number(data.BACA) === 1);

            $('#edit_tbak_konfirmasi')
                .prop('checked', Number(data.KONFIRMASI) === 1);

            $('#edit_tbak_dokter').val(data.DOKTER || '');
            $('#edit_tbak_dokter_id').val(data.DOKTER_ID || '');

        } else {

            // =====================================================
            // CPPT BIASA
            // =====================================================

            $('#edit_cppt_s').val(data.SUBYEKTIF || '');
            $('#edit_cppt_o').val(data.OBYEKTIF || '');
            $('#edit_cppt_a').val(data.ASSESMENT || '');
            $('#edit_cppt_p').val(data.PLANNING || '');

            // Instruksi hanya CPPT biasa
            $('#edit_cppt_instruksi').val(data.INSTRUKSI || '');
        }

        $('#modalEditCPPT').data('mode', mode);
    }

    function editCPPT(id, button) {
        bersihkanTooltipCPPT(button);

        if (!id) {
            iziToast.error({
                title: 'Gagal!',
                message: 'ID CPPT tidak ditemukan.',
                position: 'topRight'
            });
            return;
        }

        const btnEdit = $(`#btn-edit-cppt-${id}`);

        $.ajax({
            url: `/api/v2/emr/cppt/${kunjungan}/detail/${encodeURIComponent(id)}`,
            type: 'GET',
            dataType: 'json',
            beforeSend: function () {
                btnEdit.prop('disabled', true);
                btnEdit.html('<i class="ph-duotone ph-spinner ph-spin"></i>');
            },
            success: function (res) {
                const data = res.data || res;

                if (!data || !data.ID) {
                    iziToast.error({
                        title: 'Gagal!',
                        message: 'Data CPPT tidak ditemukan.',
                        position: 'topRight'
                    });
                    return;
                }

                isiFormEditCPPT(data);

                editCPPTKembaliKeRiwayat = true;
                editCPPTPerluRefresh = false;

                $('#modalCPPT').one('hidden.bs.modal', function () {
                    $('#modalEditCPPT').modal('show');
                });

                $('#modalCPPT').modal('hide');
            },

            error: function (xhr) {
                iziToast.error({
                    title: 'Gagal!',
                    message: xhr.responseJSON?.message || 'Detail CPPT gagal dimuat.',
                    position: 'topRight'
                });
            },
            complete: function () {
                btnEdit.prop('disabled', false);
                btnEdit.html('<i class="ri-edit-line"></i>');
                // Showing Tooltip
                $('[data-bs-toggle="tooltip"]').tooltip('dispose');
                $('.tooltip').remove();
                $('[data-bs-toggle="tooltip"]').tooltip({
                    trigger : 'hover'
                })
            }
        });
    }

    function ambilPayloadEditCPPT() {

        const mode = $('#modalEditCPPT').data('mode');

        const payload = {
            id: $('#edit_cppt_id').val(),
            kunjungan: $('#edit_cppt_kunjungan').val(),
            tanggal: $('#edit_cppt_tanggal').val(),
            jam: $('#edit_cppt_jam').val(),

            // '' untuk CPPT biasa, 'SBAR', atau 'TBAK'
            tbak_sbar: mode === 'BIASA' ? '' : mode
        };

        // =========================================================
        // CPPT BIASA
        // =========================================================
        if (mode === 'BIASA') {

            payload.s = $('#edit_cppt_s').val();
            payload.o = $('#edit_cppt_o').val();
            payload.a = $('#edit_cppt_a').val();
            payload.p = $('#edit_cppt_p').val();

            // Instruksi hanya digunakan CPPT biasa
            payload.instruksi = $('#edit_cppt_instruksi').val();
        }

        // =========================================================
        // SBAR
        // =========================================================
        if (mode === 'SBAR') {

            payload.situation =
                $('#edit_sbar_situation').val();

            payload.background =
                $('#edit_sbar_background').val();

            payload.assessment =
                $('#edit_sbar_assessment').val();

            payload.recommendation =
                $('#edit_sbar_recommendation').val();

            payload.dokter_id =
                $('#edit_sbar_dokter_id').val();
        }

        // =========================================================
        // TBAK
        // =========================================================
        if (mode === 'TBAK') {

            payload.tulis =
                $('#edit_tbak_tulis').val();

            payload.baca =
                $('#edit_tbak_baca').is(':checked') ? 1 : 0;

            payload.konfirmasi =
                $('#edit_tbak_konfirmasi').is(':checked') ? 1 : 0;

            payload.dokter_id =
                $('#edit_tbak_dokter_id').val();
        }

        return payload;
    }

    function simpanEditCPPT() {
        const id = $('#edit_cppt_id').val();
        const payload = ambilPayloadEditCPPT();
        const $btnSimpanEditCppt = $('#btn-update-cppt');

        $.ajax({
            url: `/api/v2/emr/cppt/${kunjungan}/detail/${encodeURIComponent(id)}/update`,
            type: 'PUT',
            dataType: 'json',
            data: payload,
            headers: {
                'X-CSRF-TOKEN': $(
                    'meta[name="csrf-token"]'
                ).attr('content')
            },

            beforeSend: function () {
                $('#modalEditCPPT button').prop('disabled', true);
                $btnSimpanEditCppt.html('<i class="ph-duotone ph-spinner ph-spin me-1"></i> Menyimpan...');
            },

            success: function (res) {
                iziToast.success({
                    title: 'Berhasil!',
                    message: res.message || 'CPPT berhasil diperbarui.',
                    position: 'topRight'
                });

                editCPPTKembaliKeRiwayat = true;
                editCPPTPerluRefresh = true;

                $('#modalEditCPPT').modal('hide');
            },

            error: function (xhr) {
                iziToast.error({
                    title: 'Gagal!',
                    message: xhr.responseJSON?.message || 'CPPT gagal diperbarui.',
                    position: 'topRight'
                });
            },

            complete: function () {
                $('#modalEditCPPT button').prop('disabled', false);
                $btnSimpanEditCppt.html('<i class="ph-duotone ph-floppy-disk me-1"></i> Simpan Perubahan');
            }
        });
    }
</script>
