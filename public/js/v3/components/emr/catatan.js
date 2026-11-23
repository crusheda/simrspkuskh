/* ============================================================
   SIRMED V3 - EMR CATATAN
============================================================ */

(() => {
    'use strict';

    const btnCatatan = document.getElementById('btn-floating-catatan');
    const btnRefreshCatatan = document.getElementById('btn-refresh-catatan-pasien');
    const modalElement = document.getElementById('modalCatatanEMR');

    if (!btnCatatan || !modalElement) return;

    const kunjungan = btnCatatan.dataset.kunjungan;
    const rm = btnCatatan.dataset.rm;
    const ruangan = btnCatatan.dataset.ruangan;

    const modalCatatan = bootstrap.Modal.getOrCreateInstance(modalElement);

    const modalFormElement = document.getElementById('modalFormCatatanInternal');
    const modalSolveElement = document.getElementById('modalSolveCatatanAsuransi');

    const modalForm = bootstrap.Modal.getOrCreateInstance(modalFormElement);
    const modalSolve = bootstrap.Modal.getOrCreateInstance(modalSolveElement);

    const listAll = document.getElementById('catatan-list-all');
    const listInsurance = document.getElementById('catatan-list-asuransi');
    const listInternal = document.getElementById('catatan-list-internal');

    const badge = document.getElementById('btn-count-catatan');

    const formId = document.getElementById('catatan-internal-id');
    const formDescription = document.getElementById('catatan-internal-description');
    const formRoom = document.getElementById('catatan-internal-ruangan');

    const solveDescription = document.getElementById('catatan-asuransi-solved-description');

    let currentSolveId = null;
    let currentSolveType = null; // insurance | internal
    let currentData = null;
    let loading = false;
    let catatanRequest = null;
    let returnToCatatanModal = false;

    const BASE_URL = `/api/v3/emr/catatan`;

    /* =========================================================
       UTILITIES
    ========================================================= */

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, char => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[char]));
    }

    function formatDate(value) {
        if (!value) return '-';

        const date = new Date(value);

        if (Number.isNaN(date.getTime())) return value;

        return date.toLocaleString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function csrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    }

    function apiRequest(url, method = 'GET', data = null) {
        return $.ajax({
            url,
            method,
            data,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': csrfToken(),
                'Accept': 'application/json'
            }
        });
    }

    function toast(type, message) {
        if (typeof iziToast !== 'undefined') {
            iziToast[type]({
                title: type === 'success' ? 'Berhasil' : 'Informasi',
                message,
                position: 'topRight'
            });
        } else {
            alert(message);
        }
    }

    /* =========================================================
       EMPTY / LOADING
    ========================================================= */

    function loadingHTML() {
        return `
            <div class="catatan-empty-state">
                <div class="spinner-border text-primary"></div>
                <div class="mt-3">Memuat catatan...</div>
            </div>
        `;
    }

    function emptyHTML(type) {
        return `
            <div class="catatan-empty-state">
                <div class="catatan-empty-icon">
                    <i class="ph-duotone ph-note"></i>
                </div>
                <div class="fw-semibold">Belum ada catatan</div>
                <div class="small text-body-secondary">
                    ${type === 'insurance'
                        ? 'Belum ada catatan dari asuransi.'
                        : type === 'internal'
                            ? 'Belum ada catatan internal untuk pasien ini.'
                            : 'Tidak ditemukan catatan untuk kunjungan ini.'}
                </div>
            </div>
        `;
    }

    /* =========================================================
       STATUS
    ========================================================= */

    function getInternalStatus(item) {
        return Number(item.STATUS) === 2
            ? { label: 'Selesai', class: 'catatan-status-solved', icon: 'ph-check-circle' }
            : { label: 'Belum Selesai', class: 'catatan-status-pending', icon: 'ph-clock' };
    }

    function getInsuranceStatus(item) {
        const solved = Number(item.status) === 1 && Number(item.solved) === 1;

        return solved
            ? { label: 'Solved', class: 'catatan-status-solved', icon: 'ph-check-circle' }
            : { label: 'Belum Solved', class: 'catatan-status-pending', icon: 'ph-warning-circle' };
    }

    /* =========================================================
       CARD INTERNAL
    ========================================================= */

    function renderInternal(item) {
        const status = getInternalStatus(item);

        const isSolved = Number(item.STATUS) === 2;

        const actionButtons = item.CAN_EDIT
            ? `
                <div class="catatan-card-actions">

                    ${!isSolved ? `
                        <button type="button"
                                class="btn btn-sm btn-subtle-success btn-solve-internal-catatan"
                                data-id="${item.ID}">
                            <i class="ph-duotone ph-check-circle"></i>
                            Selesaikan
                        </button>
                    ` : ''}

                    <button type="button"
                            class="btn btn-sm btn-subtle-warning btn-edit-catatan"
                            data-id="${item.ID}">
                        <i class="ph-duotone ph-pencil-simple"></i>
                        Edit
                    </button>

                    <button type="button"
                            class="btn btn-sm btn-subtle-danger btn-delete-catatan"
                            data-id="${item.ID}">
                        <i class="ph-duotone ph-trash"></i>
                        Hapus
                    </button>

                </div>
            `
            : '';

        return `
            <article class="catatan-card catatan-card-internal ${item.IS_SOLVED ? 'is-solved' : ''}">

                <div class="catatan-card-top">
                    <div class="catatan-source">
                        <span class="catatan-source-icon internal">
                            <i class="ph-duotone ph-users-three"></i>
                        </span>

                        <div>
                            <div class="catatan-source-title">Catatan Internal</div>
                            <div class="catatan-source-meta">
                                ${escapeHtml(item.NAMAUSER || 'User')}
                                · ${escapeHtml(formatDate(item.CREATED_AT))}
                            </div>
                        </div>
                    </div>

                    <span class="catatan-status ${status.class}">
                        <i class="ph-duotone ${status.icon}"></i>
                        ${status.label}
                    </span>
                </div>

                <div class="catatan-card-description">
                    ${escapeHtml(item.DESKRIPSI).replace(/\n/g, '<br>')}
                </div>

                <div class="catatan-card-footer">

                    <div class="catatan-room">
                        <i class="ph-duotone ph-map-pin"></i>
                        ${item.NAMARUANGAN
                            ? escapeHtml(item.NAMARUANGAN)
                            : 'Semua Ruangan'}
                    </div>

                    ${actionButtons}

                </div>

            </article>
        `;
    }

    /* =========================================================
       CARD INSURANCE
    ========================================================= */

    function renderInsurance(item) {
        const status = getInsuranceStatus(item);

        const solveButton = !item.IS_SOLVED
            ? `
                <button type="button"
                        class="btn btn-sm btn-success btn-solve-catatan"
                        data-id="${item.id}">
                    <i class="ph-duotone ph-check-circle me-1"></i>
                    Solve
                </button>
            `
            : '';

        return `
            <article class="catatan-card catatan-card-insurance ${item.IS_SOLVED ? 'is-solved' : ''}">

                <div class="catatan-card-top">
                    <div class="catatan-source">
                        <span class="catatan-source-icon insurance">
                            <i class="ph-duotone ph-shield-check"></i>
                        </span>

                        <div>
                            <div class="catatan-source-title">Catatan Asuransi</div>
                            <div class="catatan-source-meta">
                                ${escapeHtml(item.NAMAUSER || 'Asuransi')}
                                · ${escapeHtml(formatDate(item.created_at))}
                            </div>
                        </div>
                    </div>

                    <span class="catatan-status ${status.class}">
                        <i class="ph-duotone ${status.icon}"></i>
                        ${status.label}
                    </span>
                </div>

                <div class="catatan-card-description">
                    ${sanitizeHTML(item.deskripsi)}
                </div>

                ${item.IS_SOLVED && item.deskripsi_solved
                    ? `
                        <div class="catatan-solved-description">
                            <div class="catatan-solved-title">
                                <i class="ph-duotone ph-check-circle me-1"></i>
                                Deskripsi Penyelesaian
                            </div>
                            <div>
                                ${escapeHtml(item.deskripsi_solved).replace(/\n/g, '<br>')}
                            </div>
                        </div>
                    `
                    : ''
                }

                <div class="catatan-card-footer">
                    <div class="catatan-room">
                        <i class="ph-duotone ph-shield-check"></i>
                        Kunjungan ini
                    </div>

                    ${solveButton}
                </div>

            </article>
        `;
    }

    function sanitizeHTML(value) {
        if (typeof DOMPurify !== 'undefined') {
            return DOMPurify.sanitize(value ?? '');
        }

        // Fallback jika DOMPurify belum tersedia:
        return escapeHtml(value ?? '');
    }

    /* =========================================================
       RENDER DATA
    ========================================================= */

    function renderData(data) {
        const internal = data.internal || [];
        const insurance = data.insurance || [];

        const all = [
            ...insurance.map(item => ({ ...item, _source: 'insurance' })),
            ...internal.map(item => ({ ...item, _source: 'internal' }))
        ];

        // Urutkan berdasarkan tanggal terbaru
        all.sort((a, b) => {
            const dateA = new Date(a.created_at || a.CREATED_AT || 0).getTime();
            const dateB = new Date(b.created_at || b.CREATED_AT || 0).getTime();
            return dateB - dateA;
        });

        listAll.innerHTML = all.length
            ? all.map(item => item._source === 'insurance'
                ? renderInsurance(item)
                : renderInternal(item)).join('')
            : emptyHTML('all');

        listInsurance.innerHTML = insurance.length
            ? insurance.map(renderInsurance).join('')
            : emptyHTML('insurance');

        listInternal.innerHTML = internal.length
            ? internal.map(renderInternal).join('')
            : emptyHTML('internal');

        document.getElementById('catatan-count-all').textContent = all.length;
        document.getElementById('catatan-count-asuransi').textContent = insurance.length;
        document.getElementById('catatan-count-internal').textContent = internal.length;

        updateFloatingButton(data);

        // Update alert di header EMR
        updateAlertCatatanPasien(data);
    }

    /* ============================================================
    OPEN FULL CATATAN FROM HEADER ALERT
    ============================================================ */
    document.getElementById('btn-lihat-seluruh-catatan')
        ?.addEventListener('click', function () {

            // Buka modal Catatan Pasien yang sudah ada
            modalCatatan.show();

            // Refresh data agar modal selalu menampilkan data terbaru
            loadCatatan(true, true);
        });

    /* =========================================================
       FLOATING BUTTON STATUS
    ========================================================= */

    function updateFloatingButton(data) {
        const total = Number(data.total || 0);
        const unsolved = Number(data.unsolved || 0);

        btnCatatan.classList.remove(
            'catatan-state-empty',
            'catatan-state-pending',
            'catatan-state-solved'
        );

        if (total === 0) {
            btnCatatan.classList.add('catatan-state-empty');
        } else if (unsolved > 0) {
            btnCatatan.classList.add('catatan-state-pending');
        } else {
            btnCatatan.classList.add('catatan-state-solved');
        }

        badge.textContent = total;
    }

    /* =========================================================
       LOAD
    ========================================================= */
    function loadCatatan(showLoading = true, forceRefresh = false) {
        // Request sedang berjalan, abaikan kecuali Refresh paksa
        if (catatanRequest && !forceRefresh) {
            return;
        }

        // Refresh paksa: batalkan request sebelumnya
        if (forceRefresh && catatanRequest) {
            catatanRequest.abort();
        }

        loading = true;

        if (showLoading) {
            listAll.innerHTML = loadingHTML();
            listInsurance.innerHTML = loadingHTML();
            listInternal.innerHTML = loadingHTML();
        }

        const request = apiRequest(
            `${BASE_URL}/${encodeURIComponent(kunjungan)}`
        );

        catatanRequest = request;

        request
            .done(res => {
                if (!res.success) {
                    toast('error', res.message || 'Gagal mengambil catatan.');
                    return;
                }

                currentData = res.data;
                renderData(res.data);
            })
            .fail(xhr => {
                // Request yang dibatalkan bukan error
                if (xhr.statusText === 'abort') {
                    return;
                }

                const message =
                    xhr.responseJSON?.message || 'Gagal memuat catatan.';

                listAll.innerHTML = `
                    <div class="catatan-error-state">
                        <i class="ph-duotone ph-warning-circle"></i>
                        <div>${escapeHtml(message)}</div>
                    </div>
                `;

                toast('error', message);
            })
            .always(() => {
                // Jangan sampai request lama mengubah status request terbaru
                if (catatanRequest === request) {
                    catatanRequest = null;
                    loading = false;
                }
            });
    }

    /* ============================================================
    ALERT CATATAN PASIEN - EMR HEADER
    ============================================================ */
    function updateAlertCatatanPasien(data) {
        const alert = document.getElementById('alert-catatan-pasien');

        if (!alert) return;

        const internal = data.internal || [];
        const insurance = data.insurance || [];

        const all = [
            ...internal.map(item => ({
                ...item,
                _source: 'internal',
                _date: item.CREATED_AT
            })),

            ...insurance.map(item => ({
                ...item,
                _source: 'insurance',
                _date: item.created_at
            }))
        ];

        // Tidak ada catatan
        if (!all.length) {
            alert.classList.add('d-none');
            alert.classList.remove('is-internal', 'is-insurance');
            return;
        }

        // Urutkan terbaru
        all.sort((a, b) => {
            const dateA = new Date(a._date || 0).getTime();
            const dateB = new Date(b._date || 0).getTime();

            return dateB - dateA;
        });

        const latest = all[0];
        const isInternal = latest._source === 'internal';

        const description = isInternal
            ? latest.DESKRIPSI || ''
            : latest.deskripsi || '';

        const isSolved = isInternal
            ? Number(latest.STATUS) === 2
            : Boolean(latest.IS_SOLVED);

        const sourceLabel = isInternal
            ? 'Catatan Internal'
            : 'Catatan Asuransi';

        const statusLabel = isSolved
            ? 'Selesai'
            : isInternal
                ? 'Belum Selesai'
                : 'Belum Solved';

        const sourceIcon = isInternal
            ? 'ph-users-three'
            : 'ph-shield-check';

        // ========================================================
        // UPDATE CLASS
        // ========================================================

        alert.classList.remove('d-none', 'is-internal', 'is-insurance');

        alert.classList.add(
            isInternal ? 'is-internal' : 'is-insurance'
        );

        // ========================================================
        // UPDATE ELEMENT
        // ========================================================

        document.getElementById('alert-catatan-tipe').innerHTML = `
            <i class="ph-duotone ${sourceIcon}"></i>
            ${sourceLabel}
        `;

        document.getElementById('alert-catatan-tanggal').textContent =
            formatDate(latest._date);

        const statusElement =
            document.getElementById('alert-catatan-status');

        statusElement.innerHTML = `
            <span class="alert-catatan-status ${isSolved ? 'is-solved' : 'is-pending'}">
                <i class="ph-duotone ${isSolved ? 'ph-check-circle' : 'ph-clock'}"></i>
                ${statusLabel}
            </span>
        `;

        // Catatan internal adalah plain text
        // Catatan insurance mengikuti sanitizer yang sudah tersedia
        const descriptionElement = document.getElementById('alert-catatan-deskripsi');

        descriptionElement.innerHTML = isInternal
            ? escapeHtml(description).replace(/\n/g, '<br>')
            : sanitizeHTML(description).replace('<p>', '<p class="mb-0">');

        // ========================================================
        // TOMBOL LIHAT SELURUH CATATAN
        // ========================================================

        const action = document.getElementById('alert-catatan-action');
        const count = document.getElementById('alert-catatan-count');

        if (all.length > 1) {
            action.classList.remove('d-none');
            count.textContent = all.length;
        } else {
            action.classList.add('d-none');
        }
    }

    /* =========================================================
       OPEN MODAL
    ========================================================= */

    btnCatatan.addEventListener('click', function () {
        modalCatatan.show();
        loadCatatan();
    });

    btnRefreshCatatan?.addEventListener('click', function (event) {
        event.preventDefault();
        event.stopPropagation();

        loadCatatan(true, true);
    });

    // document.getElementById('btn-refresh-catatan')?.addEventListener('click', () => loadCatatan());

    function showFormAfterCatatanClosed() {
        returnToCatatanModal = true;

        if (modalElement.classList.contains('show')) {
            modalElement.addEventListener('hidden.bs.modal', function handler() {
                modalElement.removeEventListener('hidden.bs.modal', handler);
                modalForm.show();
            });

            modalCatatan.hide();
        } else {
            modalForm.show();
        }
    }

    /* =========================================================
       ADD INTERNAL NOTE
    ========================================================= */
    function openAddForm() {
        formId.value = '';
        formDescription.value = '';

        // Default ruangan mengikuti ruangan kunjungan saat ini
        formRoom.value = ruangan || '';

        document.getElementById('modalFormCatatanTitle').textContent =
            'Tambah Catatan Internal';

        showFormAfterCatatanClosed();
    }

    // Tombol Tambah Catatan pada tab Semua Catatan dan Internal
    document.querySelectorAll('.btn-add-catatan-internal')
        .forEach(button => {
            button.addEventListener('click', openAddForm);
        });

    // Batal Tambah Catatan → kembali ke modal Catatan Pasien
    document.getElementById('btn-cancel-catatan-internal')
        ?.addEventListener('click', function () {
            returnToCatatanModal = true;
            modalForm.hide();
        });

    // Batal Solved → kembali ke modal Catatan Pasien
    document.getElementById('btn-cancel-solved-catatan')
        ?.addEventListener('click', function () {
            returnToCatatanModal = true;
            modalSolve.hide();
        });

    // Atur perilaku ketika modal form ditutup
    modalFormElement.addEventListener('hidden.bs.modal', function () {

        if (returnToCatatanModal) {
            returnToCatatanModal = false;

            modalCatatan.show();
        }
    });
    modalSolveElement.addEventListener('hidden.bs.modal', function () {
        if (returnToCatatanModal) {
            returnToCatatanModal = false;
            modalCatatan.show();
        }
    });

    function openEditForm(item) {
        formId.value = item.ID;
        formDescription.value = item.DESKRIPSI || '';
        formRoom.value = item.RUANGAN || '';

        document.getElementById('modalFormCatatanTitle').textContent =
            'Ubah Catatan Internal';

        showFormAfterCatatanClosed();
    }

    /* =========================================================
       EVENT DELEGATION
    ========================================================= */

    document.addEventListener('click', function (event) {

        const solveInternalButton = event.target.closest('.btn-solve-internal-catatan');

        if (solveInternalButton) {
            currentSolveId = solveInternalButton.dataset.id;
            currentSolveType = 'internal';

            solveDescription.value = '';

            document.getElementById('modalSolveCatatanTitle').textContent =
                'Selesaikan Catatan Internal';

            document.getElementById('btn-confirm-solve-catatan').innerHTML = `
                <i class="ph-duotone ph-check-circle me-1"></i>
                Selesaikan Catatan
            `;

            if (modalElement.classList.contains('show')) {
                modalElement.addEventListener('hidden.bs.modal', function handler() {
                    modalElement.removeEventListener('hidden.bs.modal', handler);
                    modalSolve.show();
                });

                modalCatatan.hide();
            } else {
                modalSolve.show();
            }

            return;
        }

        const editButton = event.target.closest('.btn-edit-catatan');

        if (editButton) {
            const id = Number(editButton.dataset.id);
            const item = currentData?.internal?.find(note => Number(note.ID) === id);

            if (item && item.CAN_EDIT) {
                openEditForm(item);
            }

            return;
        }

        const deleteButton = event.target.closest('.btn-delete-catatan');

        if (deleteButton) {
            const id = deleteButton.dataset.id;

            const showDeleteConfirmation = () => {
                Swal.fire({
                    title: 'Hapus catatan?',
                    text: 'Catatan ini akan dihapus dari daftar aktif.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#dc3545',
                    theme: 'bootstrap-5'
                }).then(result => {
                    if (result.isConfirmed) {
                        apiRequest(`${BASE_URL}/internal/${id}`, 'DELETE')
                            .done(res => {
                                toast('success', res.message || 'Catatan berhasil dihapus.');
                                loadCatatan(true, true);
                            })
                            .fail(xhr => {
                                toast(
                                    'error',
                                    xhr.responseJSON?.message || 'Gagal menghapus catatan.'
                                );
                            });
                    }
                }).finally(() => {
                    // Kembalikan modal Catatan setelah Swal ditutup
                    if (returnToCatatanModal) {
                        returnToCatatanModal = false;
                        modalCatatan.show();
                    }
                });
            };

            if (modalElement.classList.contains('show')) {
                returnToCatatanModal = true;

                modalElement.addEventListener('hidden.bs.modal', function handler() {
                    modalElement.removeEventListener('hidden.bs.modal', handler);
                    showDeleteConfirmation();
                });

                modalCatatan.hide();
            } else {
                showDeleteConfirmation();
            }

            return;
        }

        const solveButton = event.target.closest('.btn-solve-catatan');

        if (solveButton) {
            currentSolveId = solveButton.dataset.id;
            currentSolveType = 'insurance';

            solveDescription.value = '';

            document.getElementById('modalSolveCatatanTitle').textContent =
                'Selesaikan Catatan Asuransi';

            document.getElementById('btn-confirm-solve-catatan').innerHTML = `
                <i class="ph-duotone ph-check-circle me-1"></i>
                Tandai Solved
            `;

            if (modalElement.classList.contains('show')) {
                modalElement.addEventListener('hidden.bs.modal', function handler() {
                    modalElement.removeEventListener('hidden.bs.modal', handler);
                    modalSolve.show();
                });

                modalCatatan.hide();
            } else {
                modalSolve.show();
            }

            return;
        }
    });

    /* =========================================================
       SAVE INTERNAL
    ========================================================= */

    document.getElementById('btn-save-catatan-internal')
        ?.addEventListener('click', function () {

            const id = formId.value;
            const description = formDescription.value.trim();
            const room = formRoom.value || null;

            if (description.length < 3) {
                toast('warning', 'Catatan minimal 3 karakter.');
                formDescription.focus();
                return;
            }

            const isEdit = !!id;

            const url = isEdit
                ? `${BASE_URL}/internal/${id}`
                : `${BASE_URL}/${encodeURIComponent(kunjungan)}/internal`;

            const method = isEdit ? 'PUT' : 'POST';

            this.disabled = true;

            apiRequest(url, method, {
                deskripsi: description,
                ruangan: room
            })
                .done(res => {
                    modalForm.hide();
                    toast('success', res.message || 'Catatan tersimpan.');
                    loadCatatan(true, true);
                })
                .fail(xhr => {
                    toast('error', xhr.responseJSON?.message || 'Gagal menyimpan catatan.');
                })
                .always(() => {
                    this.disabled = false;
                });
        });

    /* =========================================================
       SOLVE INSURANCE / INTERNAL
    ========================================================= */
    document.getElementById('btn-confirm-solve-catatan')
        ?.addEventListener('click', function () {

            if (!currentSolveId || !currentSolveType) return;

            const button = this;
            button.disabled = true;

            let url;
            let method = 'POST';
            let payload = {};

            if (currentSolveType === 'insurance') {
                url = `${BASE_URL}/insurance/${currentSolveId}/solve`;

                payload = {
                    deskripsi_solved: solveDescription.value.trim() || null
                };
            } else if (currentSolveType === 'internal') {
                url = `${BASE_URL}/internal/${currentSolveId}/solve`;

                payload = {
                    deskripsi_solved: solveDescription.value.trim() || null
                };
            }

            apiRequest(url, method, payload)
                .done(res => {
                    modalSolve.hide();

                    toast(
                        'success',
                        res.message || 'Catatan berhasil diselesaikan.'
                    );

                    loadCatatan(true, true);
                })
                .fail(xhr => {
                    toast(
                        'error',
                        xhr.responseJSON?.message || 'Gagal menyelesaikan catatan.'
                    );
                })
                .always(() => {
                    button.disabled = false;
                    currentSolveId = null;
                    currentSolveType = null;
                });
        });

    /* =========================================================
       INIT
    ========================================================= */

    // Ambil data saat halaman pertama kali dibuka agar badge floating akurat
    loadCatatan(false);

})();
