/* Resume Medis Rawat Jalan V3 */
(function ($) {
    'use strict';

    function initResumeRajal() {
        const $root = $('#form_resume_medis_rajal');
        if (!$root.length || $root.data('initialized')) return;
        $root.data('initialized', true);

        const $form = $('#resume_rj_form');
        const $fieldset = $('#resume_rj_fields');
        const $badge = $('#badge_final_resume_rj');
        const $tab = $('#tab-resume-rajal');
        const $tabBadge = $('#badge_tab_final_resume_rj');
        const $finalInfo = $('#info_final_resume_rj');
        const $btnFinal = $('#btn_final_resume_rj');
        const $btnBatal = $('#btn_batal_final_resume_rj');
        const $btnPrint = $('#btn_print_resume_rj');
        const $btnClearSignature = $('#btn_clear_signature_resume_rj');
        const $tteStatus = $('#resume_rj_tte_status');
        const $signaturePreviewWrap = $('#resume_rj_signature_preview_wrap');
        const $signaturePreview = $('#resume_rj_signature_preview');
        const $signatureDate = $('#resume_rj_signature_saved_at');

        const kunjungan = (window.EMRData || {}).kunjungan;

        if (!kunjungan) {
            console.error('[Resume RJ] EMRData.kunjungan tidak tersedia');
            return;
        }

        const baseUrl = `/api/v2/emr/resume/rajal/${encodeURIComponent(kunjungan)}`;
        const previewUrl = `${baseUrl}/preview`;
        const csrf = $('meta[name="csrf-token"]').attr('content');

        const headers = {
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json'
        };

        let loading = false;
        let saving = false;
        let isFinal = false;
        let hasSignature = false;
        let saveTimer = null;

        let finalMeta = {
            oleh_nama: '',
            tgl_difinal: ''
        };

        /* =========================================================
        * SIGNATURE STATE
        * ========================================================= */
        let signatureSavedUrl = null;
        let signatureSavedAt = null;
        let signatureEditing = false;

        const canvas = document.getElementById('canvas_signature_resume_rj');
        const ctx = canvas ? canvas.getContext('2d') : null;

        let drawing = false;
        let hasInk = false;
        let lastPoint = null;

        /* =========================================================
         * RESUME RAJAL ACCESS BY RUANGAN CHOICED
         * ========================================================= */
        function isResumeRajalExcluded(idRuangan) {
            const excludedRooms = ['10207%', '102010103'];
            const id = String(idRuangan ?? '');

            return excludedRooms.some(pattern => {
                const regex = new RegExp(
                    '^' + pattern
                        .replace(/[.*+?^${}()|[\]\\]/g, '\\$&')
                        .replace(/%/g, '.*') + '$'
                );

                return regex.test(id);
            });
        }

        function toggleResumeRajal(idRuangan) {
            const excluded = isResumeRajalExcluded(idRuangan);

            if (excluded) {
                // Badge Final / Belum Final
                $badge.prop('hidden',true);
                $tabBadge.prop('hidden',true);
            }

            // Section TTE Dokter
            document.getElementById('resume_rj_signature_section')
                ?.classList.toggle('d-none', excluded);

            // Semua tombol Resume Rajal
            document.getElementById('resume_rj_actions')
                ?.classList.toggle('d-none', excluded);
        }

        /* =========================================================
         * NOTIFICATION
         * ========================================================= */
        function notify(type, title, message) {
            if (
                window.iziToast &&
                typeof iziToast[type] === 'function'
            ) {
                iziToast[type]({
                    title: title,
                    message: message,
                    position: 'topRight',
                    timeout: 3200
                });
            } else {
                console[type === 'error' ? 'error' : 'log'](
                    `${title}: ${message}`
                );
            }
        }

        /* =========================================================
         * CONFIRMATION
         * ========================================================= */
        async function confirmBox(
            title,
            text,
            confirmText = 'Ya, lanjutkan'
        ) {
            if (window.Swal) {
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

        /* =========================================================
         * UPDATE UI STATUS
         * ========================================================= */
        function updateStatus() {
            $fieldset.prop(
                'disabled',
                loading || saving || isFinal
            );

            $btnClearSignature.prop(
                'disabled',
                loading || saving || isFinal
            );

            $btnFinal.prop(
                'disabled',
                loading || saving || isFinal
            );

            $btnBatal.prop(
                'disabled',
                loading || saving || !isFinal
            );

            $btnPrint.prop(
                'disabled',
                loading || saving
            );

            $('#btn_update_signature_resume_rj').prop(
                'disabled',
                loading || saving || isFinal
            );

            $('#btn_save_signature_resume_rj').prop(
                'disabled',
                loading || saving || isFinal
            );

            $('#btn_cancel_signature_resume_rj').prop(
                'disabled',
                loading || saving
            );

            if (loading) {
                $badge
                    .attr('class', 'badge bg-secondary')
                    .text('Memuat...');

                if ($tabBadge.length) {
                    $tabBadge
                        .attr('class', 'badge bg-secondary')
                        .text('...');
                }
            } else if (isFinal) {
                $badge
                    .attr('class', 'badge bg-success')
                    .text('Sudah Final');

                $btnFinal.addClass('d-none');
                $btnBatal.removeClass('d-none');

                if ($tabBadge.length) {
                    $tabBadge
                        .attr('class', 'badge bg-success')
                        .text('Final');
                }
            } else {
                $badge
                    .attr('class', 'badge bg-danger')
                    .text('Belum Final');

                $btnFinal.removeClass('d-none');
                $btnBatal.addClass('d-none');

                if ($tabBadge.length) {
                    $tabBadge
                        .attr('class', 'badge bg-danger')
                        .text('Belum Final');
                }
            }

            if (isFinal) {
                $('#info_final_oleh_rj')
                    .text(finalMeta.oleh_nama || 'Pengguna');

                $('#info_final_tanggal_rj')
                    .text(finalMeta.tgl_difinal || '-');

                $finalInfo.removeClass('d-none');
            } else {
                $finalInfo.addClass('d-none');
            }

            if ($tab.length) {
                $tab.attr(
                    'title',
                    isFinal
                        ? `Final oleh ${finalMeta.oleh_nama || '-'} • ${finalMeta.tgl_difinal || '-'}`
                        : 'Resume RJ belum difinalisasi'
                );
            }
        }

        /* =========================================================
         * FORM DATA
         * ========================================================= */
        function formData() {
            const result = {};

            $fieldset.find('[name]').each(function () {
                result[this.name] = $(this).val() || null;
            });

            return result;
        }

        /* =========================================================
        * FILL FORM
        * ========================================================= */
        function fill(data_ori = {}, data_final = {}) {
            Object.keys(data_ori).forEach(function (name) {
                const finalValue = data_final[name];

                const hasFinalValue =
                    finalValue !== null &&
                    finalValue !== undefined &&
                    finalValue !== '';

                const rawValue = hasFinalValue
                    ? finalValue
                    : (data_ori[name] ?? '');

                // Convert HTML menjadi plain text
                const value = htmlToText(rawValue);

                $fieldset
                    .find(`[name="${name}"]`)
                    .val(value);
            });

            // Field yang hanya terdapat pada data_final
            Object.keys(data_final).forEach(function (name) {
                if (Object.prototype.hasOwnProperty.call(data_ori, name)) {
                    return;
                }

                const value = htmlToText(data_final[name] ?? '');

                $fieldset
                    .find(`[name="${name}"]`)
                    .val(value);
            });
        }

        /* =========================================================
        * HTML TO PLAIN TEXT
        * ========================================================= */
        function htmlToText(value) {
            if (value === null || value === undefined) {
                return '';
            }

            const html = String(value);

            const temp = document.createElement('div');
            temp.innerHTML = html;

            // BR menjadi newline
            temp.querySelectorAll('br').forEach(function (br) {
                br.replaceWith('\n');
            });

            // Beberapa elemen block diberi pemisah baris
            temp.querySelectorAll('p, div, li, tr, h1, h2, h3, h4, h5, h6')
                .forEach(function (element) {
                    element.appendChild(document.createTextNode('\n'));
                });

            // Ambil text tanpa HTML
            let text = temp.textContent || temp.innerText || '';

            // Bersihkan spasi HTML
            text = text.replace(/\u00a0/g, ' ');

            // Rapikan newline berlebih, maksimal 2 newline berurutan
            text = text.replace(/\n{3,}/g, '\n\n');

            // Rapikan spasi berlebih per baris
            text = text
                .split('\n')
                .map(line => line.trim())
                .join('\n');

            return text.trim();
        }

        /* =========================================================
         * SIGNATURE STATUS
         * ========================================================= */
        function setSignatureSaved(url, date) {
            signatureSavedUrl = url || null;
            signatureSavedAt = date || null;

            if (!url) {
                resetSignatureSaved();
                return;
            }

            const image = new Image();

            image.onload = function () {
                hasSignature = true;

                $('#resume_rj_signature_preview')
                    .attr('src', url)
                    .removeClass('d-none');

                renderSignatureState();
            };

            image.onerror = function () {
                hasSignature = false;

                signatureSavedUrl = null;
                signatureSavedAt = null;

                $('#resume_rj_signature_preview')
                    .attr('src', '')
                    .addClass('d-none');

                renderSignatureState();

                notify(
                    'error',
                    'Tanda Tangan',
                    'File tanda tangan ditemukan, tetapi gambar tidak dapat dimuat.'
                );

                console.error('Gagal memuat file TTE:', url);
            };

            image.src = url + (url.includes('?') ? '&' : '?') + '_=' + Date.now();
        }
        function resetSignatureSaved() {
            hasSignature = false;
            signatureSavedUrl = null;
            signatureSavedAt = null;

            $('#resume_rj_signature_preview')
                .attr('src', '')
                .addClass('d-none');

            renderSignatureState();
        }

        /* =========================================================
        * RENDER SIGNATURE STATE
        * ========================================================= */
        function renderSignatureState() {

            const $saved = $('#resume_rj_signature_saved');
            const $canvasWrap = $('#resume_rj_signature_canvas_wrap');

            const $update = $('#btn_update_signature_resume_rj');
            const $clear = $('#btn_clear_signature_resume_rj');
            const $cancel = $('#btn_cancel_signature_resume_rj');
            const $save = $('#btn_save_signature_resume_rj');

            const $hint = $('#resume_rj_signature_hint');

            if (hasSignature && signatureSavedUrl && !signatureEditing) {

                // Signature tersimpan
                $saved.removeClass('d-none');
                $canvasWrap.addClass('d-none');

                $update.removeClass('d-none');
                $clear.addClass('d-none');
                $cancel.addClass('d-none');
                $save.addClass('d-none');

                $hint.text('Tanda tangan elektronik dokter telah tersimpan.');

                $tteStatus
                    .attr('class', 'badge bg-success')
                    .text('Sudah ditandatangani');

            } else if (signatureEditing) {

                // Mode update TTE
                $saved.addClass('d-none');
                $canvasWrap.removeClass('d-none');

                $update.addClass('d-none');
                $clear.removeClass('d-none');
                $cancel.removeClass('d-none');
                $save.removeClass('d-none');

                $hint.text('Silakan buat tanda tangan baru pada area di atas.');

                $tteStatus
                    .attr('class', 'badge bg-warning text-dark')
                    .text('Memperbarui TTE');

            } else {

                // Belum memiliki TTE
                $saved.addClass('d-none');
                $canvasWrap.removeClass('d-none');

                $update.addClass('d-none');
                $clear.removeClass('d-none');
                $cancel.addClass('d-none');
                $save.removeClass('d-none');

                $hint.text('Tanda tangan pada area di atas menggunakan mouse atau layar sentuh.');

                $tteStatus
                    .attr('class', 'badge bg-secondary')
                    .text('Belum ditandatangani');
            }

            $('#resume_rj_signature_saved_at').text(
                hasSignature && signatureSavedAt && !signatureEditing
                    ? 'Tanda tangan tersimpan pada ' + signatureSavedAt
                    : ''
            );
        }

        /* =========================================================
         * CANVAS COORDINATE
         * ========================================================= */
        /**
         * Canvas harus memiliki ukuran CSS yang valid
         * sebelum drawing dapat dilakukan.
         */
        function getCanvasSize() {
            if (!canvas) return null;

            const rect = canvas.getBoundingClientRect();

            if (!rect.width || !rect.height) {
                return null;
            }

            return {
                width: rect.width,
                height: rect.height
            };
        }

        /**
         * Resize canvas mengikuti ukuran CSS dan device pixel ratio.
         * Dipanggil saat awal, tab dibuka, dan window resize.
         */
        function resizeCanvas(preserve = true) {
            if (!canvas || !ctx) return;

            const size = getCanvasSize();

            // Tab belum visible atau ukuran belum tersedia.
            if (!size) return;

            const oldImage = (
                preserve && hasInk
                    ? canvas.toDataURL('image/png')
                    : null
            );

            const ratio = Math.max(
                window.devicePixelRatio || 1,
                1
            );

            canvas.width = Math.round(size.width * ratio);
            canvas.height = Math.round(size.height * ratio);

            // Koordinat drawing menggunakan CSS pixel.
            ctx.setTransform(
                ratio,
                0,
                0,
                ratio,
                0,
                0
            );

            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            ctx.lineWidth = 2.5;
            ctx.strokeStyle = '#202124';
            ctx.fillStyle = '#202124';

            if (oldImage) {
                const img = new Image();

                img.onload = function () {
                    ctx.clearRect(
                        0,
                        0,
                        size.width,
                        size.height
                    );

                    ctx.drawImage(
                        img,
                        0,
                        0,
                        size.width,
                        size.height
                    );
                };

                img.src = oldImage;
            }
        }

        /**
         * Mengambil posisi pointer relatif canvas.
         */
        function getPoint(e) {
            const rect = canvas.getBoundingClientRect();

            return {
                x: e.clientX - rect.left,
                y: e.clientY - rect.top
            };
        }

        /**
         * Pointer mulai menggambar.
         */
        function startDrawing(e) {
            if (isFinal || loading || saving) return;

            if (!canvas || !ctx) return;

            // Pastikan canvas sudah memiliki ukuran.
            if (!canvas.width || !canvas.height) {
                resizeCanvas(false);
            }

            if (!canvas.width || !canvas.height) {
                return;
            }

            e.preventDefault();

            drawing = true;
            hasInk = true;

            lastPoint = getPoint(e);

            // Pointer capture agar pointer tidak kehilangan canvas
            // ketika bergerak keluar sedikit dari area canvas.
            if (canvas.setPointerCapture) {
                try {
                    canvas.setPointerCapture(e.pointerId);
                } catch (error) {
                    // Tidak perlu menghentikan drawing.
                }
            }

            ctx.beginPath();
            ctx.moveTo(
                lastPoint.x,
                lastPoint.y
            );

            // Titik awal tetap terlihat meskipun hanya tap.
            ctx.beginPath();
            ctx.arc(
                lastPoint.x,
                lastPoint.y,
                ctx.lineWidth / 2,
                0,
                Math.PI * 2
            );
            ctx.fill();
        }

        /**
         * Pointer bergerak.
         */
        function draw(e) {
            if (!drawing || isFinal || loading || saving) {
                return;
            }

            e.preventDefault();

            const point = getPoint(e);

            ctx.beginPath();
            ctx.moveTo(
                lastPoint.x,
                lastPoint.y
            );
            ctx.lineTo(
                point.x,
                point.y
            );
            ctx.stroke();

            lastPoint = point;
        }

        /**
         * Pointer selesai.
         */
        function stopDrawing(e) {
            if (!drawing) return;

            drawing = false;
            lastPoint = null;

            if (
                canvas &&
                canvas.releasePointerCapture &&
                e &&
                e.pointerId !== undefined
            ) {
                try {
                    if (canvas.hasPointerCapture(e.pointerId)) {
                        canvas.releasePointerCapture(e.pointerId);
                    }
                } catch (error) {
                    // Ignore release capture error.
                }
            }
        }

        /**
         * Bersihkan canvas.
         */
        function clearCanvas() {
            if (!canvas || !ctx) return;

            const size = getCanvasSize();

            if (size) {
                ctx.clearRect(
                    0,
                    0,
                    size.width,
                    size.height
                );
            }

            hasInk = false;
            drawing = false;
            lastPoint = null;
        }

        /**
         * Initialize canvas.
         */
        if (canvas && ctx) {

            // Jangan pakai touch event terpisah.
            // Pointer event sudah mendukung mouse, stylus, dan touch.
            canvas.addEventListener(
                'pointerdown',
                startDrawing
            );

            canvas.addEventListener(
                'pointermove',
                draw
            );

            canvas.addEventListener(
                'pointerup',
                stopDrawing
            );

            canvas.addEventListener(
                'pointercancel',
                stopDrawing
            );

            canvas.addEventListener(
                'lostpointercapture',
                stopDrawing
            );

            // Resize saat browser berubah ukuran.
            window.addEventListener(
                'resize',
                function () {
                    resizeCanvas(true);
                }
            );

            // Initial resize.
            requestAnimationFrame(function () {
                resizeCanvas(false);
            });

            // Resize ulang ketika tab Resume RJ ditampilkan.
            document.addEventListener(
                'shown.bs.tab',
                function (e) {
                    if (
                        e.target &&
                        (
                            e.target.id === 'tab-resume-rajal' ||
                            e.target.getAttribute('data-bs-target') === '#fresumerajal'
                        )
                    ) {
                        requestAnimationFrame(function () {
                            resizeCanvas(true);
                        });
                    }
                }
            );

            // Resize ulang jika tab parent dibuka.
            $(document).on(
                'shown.bs.tab',
                '[data-bs-toggle="tab"]',
                function () {
                    setTimeout(function () {
                        const size = getCanvasSize();

                        if (size) {
                            resizeCanvas(true);
                        }
                    }, 100);
                }
            );
        }

        /* =========================================================
         * CLEAR CANVAS
         * ========================================================= */
        function clearCanvas() {
            if (!canvas || !ctx) return;

            ctx.clearRect(
                0,
                0,
                canvas.width,
                canvas.height
            );

            hasInk = false;
        }

        /* =========================================================
         * SAVE RESUME
         * ========================================================= */
        async function saveData(silent = false) {
            if (isFinal || loading || saving) {
                return false;
            }

            saving = true;
            updateStatus();

            try {
                const response = await $.ajax({
                    url: `${baseUrl}/simpan`,
                    method: 'POST',
                    headers: headers,
                    data: formData()
                });

                if (!silent) {
                    notify(
                        'success',
                        'Berhasil',
                        response.message ||
                            'Resume tersimpan'
                    );
                }

                return true;
            } catch (xhr) {
                if (!silent) {
                    notify(
                        'error',
                        'Gagal menyimpan',
                        xhr.responseJSON?.message ||
                            'Terjadi kesalahan saat menyimpan resume'
                    );
                }

                return false;
            } finally {
                saving = false;
                updateStatus();
            }
        }

        /* =========================================================
         * AUTOSAVE
         * ========================================================= */
        function scheduleSave() {
            if (isFinal || loading) return;

            clearTimeout(saveTimer);

            saveTimer = setTimeout(function () {
                saveData(true);
            }, 800);
        }

        /* =========================================================
         * LOAD RESUME
         * ========================================================= */
        async function loadData() {
            loading = true;
            updateStatus();

            try {
                const response = await $.ajax({
                    url: baseUrl,
                    method: 'GET',
                    dataType: 'json'
                });

                // fill(response.data || {});
                fill(response.data_ori, response.data_final);

                isFinal = !!response.is_final;

                finalMeta = (
                    response.final_meta ||
                    finalMeta
                );

                // console.log('Signature element check:', {
                //     status: $('#resume_rj_tte_status').length,
                //     preview: $('#resume_rj_signature_preview').length,
                //     previewWrap: $('#resume_rj_signature_preview_wrap').length,
                //     url: response.signature
                // });

                toggleResumeRajal(response.kunjungan.RUANGAN);

                setSignatureSaved(
                    response.signature || null,
                    response.signature_meta?.tanggal || null
                );

                updateStatus();
            } catch (xhr) {
                notify(
                    'error',
                    'Gagal memuat Resume RJ',
                    xhr.responseJSON?.message ||
                        'Data resume tidak dapat dimuat'
                );
            } finally {
                loading = false;
                updateStatus();
            }
        }

        /* =========================================================
        * SAVE SIGNATURE
        * ========================================================= */
        async function saveSignature() {

            if (isFinal) {
                notify(
                    'warning',
                    'Resume sudah final',
                    'Batalkan finalisasi terlebih dahulu jika ingin memperbarui TTE.'
                );
                return;
            }

            if (!hasInk) {
                notify(
                    'warning',
                    'Tanda tangan kosong',
                    'Silakan tanda tangan terlebih dahulu.'
                );
                return;
            }

            saving = true;
            updateStatus();

            try {
                const payload = canvas.toDataURL('image/png');

                const response = await $.ajax({
                    url: `${baseUrl}/ttd`,
                    method: 'POST',
                    headers: headers,
                    data: {
                        signature: payload
                    }
                });

                // Keluar dari mode edit
                signatureEditing = false;

                // Reset ink canvas
                clearCanvas();

                // Load signature yang baru tersimpan
                setSignatureSaved(
                    response.signature,
                    response.signature_tanggal
                );

                notify(
                    'success',
                    'TTE tersimpan',
                    response.message || 'Tanda tangan berhasil disimpan'
                );

            } catch (xhr) {
                notify(
                    'error',
                    'Gagal menyimpan TTE',
                    xhr.responseJSON?.message || 'Tanda tangan tidak dapat disimpan'
                );

            } finally {
                saving = false;
                updateStatus();
            }
        }

        /* =========================================================
         * FINALIZE RESUME
         * ========================================================= */
        async function finalize() {
            if (!hasSignature) {
                notify(
                    'warning',
                    'TTE diperlukan',
                    'Simpan tanda tangan dokter terlebih dahulu.'
                );

                return;
            }

            const fields = formData();

            const requiredFields = [
                'SUBYEKTIF',
                'OBYEKTIF',
                'ASSESMENT',
                'PLANNING',
                'INSTRUKSI'
            ];

            const empty = requiredFields.filter(function (key) {
                return !String(fields[key] || '').trim();
            });

            if (empty.length) {
                notify(
                    'warning',
                    'Form belum lengkap',
                    `Lengkapi: ${empty.join(', ')}`
                );

                return;
            }

            const confirmed = await confirmBox(
                'Finalisasi Resume Medis?',
                'Setelah final, data resume akan terkunci sampai finalisasi dibatalkan.',
                'Finalisasi'
            );

            if (!confirmed) return;

            const saved = await saveData(true);

            if (!saved) return;

            saving = true;
            updateStatus();

            try {
                const response = await $.ajax({
                    url: `${baseUrl}/finalisasi`,
                    method: 'POST',
                    headers: headers
                });

                isFinal = true;

                finalMeta = response.final_meta || {};

                notify(
                    'success',
                    'Finalisasi berhasil',
                    response.message ||
                        'Resume berhasil difinalisasi'
                );
            } catch (xhr) {
                notify(
                    'error',
                    'Finalisasi gagal',
                    xhr.responseJSON?.message ||
                        'Tidak dapat memfinalisasi resume'
                );
            } finally {
                saving = false;
                updateStatus();
            }
        }

        /* =========================================================
         * CANCEL FINALIZATION
         * ========================================================= */
        async function cancelFinal() {
            const confirmed = await confirmBox(
                'Batalkan finalisasi?',
                'Resume akan kembali dapat diedit.',
                'Batalkan Final'
            );

            if (!confirmed) return;

            saving = true;
            updateStatus();

            try {
                const response = await $.ajax({
                    url: `${baseUrl}/batal-final`,
                    method: 'POST',
                    headers: headers
                });

                isFinal = false;
                finalMeta = {};

                notify(
                    'success',
                    'Berhasil',
                    response.message ||
                        'Finalisasi dibatalkan'
                );
            } catch (xhr) {
                notify(
                    'error',
                    'Gagal',
                    xhr.responseJSON?.message ||
                        'Tidak dapat membatalkan finalisasi'
                );
            } finally {
                saving = false;
                updateStatus();
            }
        }

        /* =========================================================
         * PRINT PREVIEW
         * ========================================================= */
        const previewModalEl = document.getElementById('modal_preview_resume_rj');
        const previewModal = previewModalEl
            ? bootstrap.Modal.getOrCreateInstance(previewModalEl)
            : null;

        const previewIframe = document.getElementById('resume_rj_preview_iframe');
        const previewLoading = document.getElementById('resume_rj_preview_loading');
        const previewError = document.getElementById('resume_rj_preview_error');

        const btnPreviewRefresh = document.getElementById('btn_refresh_preview_resume_rj');
        const btnPreviewPrint = document.getElementById('btn_print_preview_resume_rj');
        const btnPreviewRetry = document.getElementById('btn_retry_preview_resume_rj');

        let previewBlobUrl = null;
        let previewRequestController = null;


        /* =========================================================
        TOAST
        ========================================================= */

        function previewToastError(message) {

            if (typeof iziToast !== 'undefined') {
                iziToast.error({
                    title: 'Print Preview',
                    message: message,
                    position: 'topRight',
                    timeout: 5000
                });
            } else {
                alert(message);
            }

        }


        /* =========================================================
        UI
        ========================================================= */

        function showPreviewLoading() {

            previewLoading?.classList.remove('d-none');
            previewError?.classList.add('d-none');
            previewIframe?.classList.remove('d-none');

        }

        function hidePreviewLoading() {

            previewLoading?.classList.add('d-none');

        }

        function showPreviewError() {

            previewLoading?.classList.add('d-none');
            previewError?.classList.remove('d-none');

        }


        /* =========================================================
        CLEANUP PDF BLOB
        ========================================================= */

        function clearPreviewBlob() {

            if (previewBlobUrl) {
                URL.revokeObjectURL(previewBlobUrl);
                previewBlobUrl = null;
            }

        }


        /* =========================================================
        LOAD PDF
        ========================================================= */

        async function loadPreview() {

            if (!previewIframe) return;

            // Cancel request sebelumnya
            if (previewRequestController) {
                previewRequestController.abort();
            }

            previewRequestController = new AbortController();

            showPreviewLoading();

            previewError?.classList.add('d-none');

            // Hapus PDF lama
            clearPreviewBlob();

            previewIframe.src = 'about:blank';

            try {

                const response = await fetch(`${baseUrl}/preview?_=${Date.now()}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/pdf',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                    signal: previewRequestController.signal
                });

                /*
                * Jika HTTP error, baca JSON error dari Laravel
                */
                if (!response.ok) {

                    let message = 'Gagal memuat Preview Resume Medis.';

                    try {
                        const errorData = await response.json();

                        if (errorData.message) {
                            message = errorData.message;
                        }
                    } catch (e) {
                        // Response bukan JSON
                    }

                    throw new Error(message);
                }

                /*
                * Pastikan response benar-benar PDF
                */
                const contentType = response.headers.get('content-type') || '';

                if (!contentType.toLowerCase().includes('application/pdf')) {

                    let message = 'Server tidak mengembalikan dokumen PDF yang valid.';

                    try {
                        const errorData = await response.json();

                        if (errorData.message) {
                            message = errorData.message;
                        }
                    } catch (e) {}

                    throw new Error(message);
                }

                /*
                * Konversi PDF ke Blob
                */
                const blob = await response.blob();

                if (!blob.size) {
                    throw new Error('File PDF kosong.');
                }

                /*
                * Buat URL lokal Blob
                */
                previewBlobUrl = URL.createObjectURL(blob);

                /*
                * PDF berhasil didapatkan
                * Baru sekarang tampilkan pada iframe
                */
                previewIframe.src = previewBlobUrl;

                hidePreviewLoading();

            } catch (error) {

                if (error.name === 'AbortError') {
                    return;
                }

                console.error('Resume RJ Preview Error:', error);

                showPreviewError();

                // Modal ditutup jika gagal
                previewModal?.hide();

                // Tampilkan error secara elegan
                previewToastError(
                    error.message || 'Gagal menampilkan Preview Resume Medis.'
                );

            } finally {

                previewRequestController = null;

            }

        }


        /* =========================================================
        OPEN PREVIEW
        ========================================================= */

        function openPrintPreview() {

            if (!previewModal) return;

            showPreviewLoading();

            previewError?.classList.add('d-none');

            previewModal.show();

            loadPreview();

        }


        /* =========================================================
        BUTTON EVENTS
        ========================================================= */

        btnPreviewRefresh?.addEventListener('click', function () {
            loadPreview();
        });

        btnPreviewRetry?.addEventListener('click', function () {
            loadPreview();
        });


        /* =========================================================
        PRINT PDF
        ========================================================= */

        btnPreviewPrint?.addEventListener('click', function () {

            if (!previewBlobUrl) {
                previewToastError('Dokumen PDF belum siap dicetak.');
                return;
            }

            try {

                previewIframe.contentWindow.print();

            } catch (error) {

                console.error('Resume RJ Print Error:', error);

                previewToastError('Tidak dapat membuka dialog print.');

            }

        });


        /* =========================================================
        MODAL CLOSED
        ========================================================= */

        previewModalEl?.addEventListener('hidden.bs.modal', function () {

            if (previewRequestController) {
                previewRequestController.abort();
            }

            clearPreviewBlob();

            if (previewIframe) {
                previewIframe.src = 'about:blank';
            }

        });

        /* =========================================================
         * EVENT LISTENER
         * ========================================================= */
        $fieldset.on(
            'input change',
            'textarea,input,select',
            scheduleSave
        );

        $btnClearSignature.on(
            'click',
            clearCanvas
        );

        $btnFinal.on(
            'click',
            finalize
        );

        $btnBatal.on(
            'click',
            cancelFinal
        );

        $btnPrint.on(
            'click',
            openPrintPreview
        );

        /* =========================================================
         * BUTTON SAVE TTE
         * Dibuat dinamis agar markup Blade tidak perlu diubah.
         * ========================================================= */
        const $saveTte = $(
            '<button type="button" class="btn btn-success" id="btn_save_signature_resume_rj">' +
                '<i class="ph-duotone ph-floppy-disk me-1"></i>' +
                'Simpan TTE' +
            '</button>'
        );

        $btnClearSignature.after($saveTte);

        $saveTte.on(
            'click',
            saveSignature
        );

        /* =========================================================
        * UPDATE SIGNATURE
        * ========================================================= */
        $('#btn_update_signature_resume_rj')
            .off('click')
            .on('click', function () {

                if (isFinal) {
                    notify(
                        'warning',
                        'Resume sudah final',
                        'Batalkan finalisasi terlebih dahulu untuk memperbarui TTE.'
                    );
                    return;
                }

                signatureEditing = true;

                clearCanvas();
                renderSignatureState();

                requestAnimationFrame(function () {
                    resizeCanvas(false);
                });
            });

        /* =========================================================
        * CANCEL UPDATE SIGNATURE
        * ========================================================= */
        $('#btn_cancel_signature_resume_rj')
            .off('click')
            .on('click', function () {

                signatureEditing = false;

                // Bersihkan coretan sementara
                clearCanvas();

                // Kembali menampilkan TTE tersimpan
                renderSignatureState();
            });

        /* =========================================================
        * CLEAR SIGNATURE CANVAS
        * ========================================================= */
        // $('#btn_clear_signature_resume_rj').on('click', function () {

        //     const canvas = document.getElementById('canvas_signature_resume_rj');

        //     if (!canvas) return;

        //     const ctx = canvas.getContext('2d');

        //     ctx.clearRect(0, 0, canvas.width, canvas.height);
        // });

        /* =========================================================
         * INITIAL LOAD
         * ========================================================= */
        loadData();
    }

    /* DOM READY */
    $(initResumeRajal);

    /* Support component injected after DOM ready */
    document.addEventListener(
        'shown.bs.tab',
        initResumeRajal
    );

})(jQuery);
