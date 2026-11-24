
/* =========================================================
   SIRMED V3 — EMR SEARCH
   Smart Search:
   - Nomor RM (NORM)
   - Nomor Kunjungan (NOKUNJUNGAN)
   - Nomor Asuransi (pendaftaran.penjamin.NOMOR)
   - Nama Pasien
   - Partial Search
   - Debounce
   - Abort previous request
   - Prevent stale response
   - Open EMR Workspace
========================================================= */

(() => {
    'use strict';

    /* =====================================================
       ELEMENTS
    ===================================================== */

    const modalElement = document.getElementById('modalCariEMR');

    if (!modalElement || modalElement.dataset.initialized === 'true') {
        return;
    }

    const input = document.getElementById('cariEMRNorm');
    const searchButton = document.getElementById('btnCariEMR');
    const resultContainer = document.getElementById('cariEMRHasil');
    const infoContainer = document.getElementById('cariEMRInfo');

    if (!input || !searchButton || !resultContainer || !infoContainer) {
        console.warn('[EMRSearch] Element modal tidak lengkap.');
        return;
    }

    if (typeof bootstrap === 'undefined' || !bootstrap.Modal) {
        console.error('[EMRSearch] Bootstrap Modal tidak tersedia.');
        return;
    }

    if (typeof $ === 'undefined' || !$.ajax) {
        console.error('[EMRSearch] jQuery AJAX tidak tersedia.');
        return;
    }

    modalElement.dataset.initialized = 'true';

    const modal = bootstrap.Modal.getOrCreateInstance(modalElement);

    /* =====================================================
       CONFIG
    ===================================================== */

    const SEARCH_URL = '/api/v3/search/emr';
    const EMR_PAGE_URL = '/v3/emr';

    const AUTO_SEARCH_DELAY = 700;
    const MIN_SEARCH_LENGTH = 3;

    const PENDING_PATIENT_KEY = 'sirmed_emr_search_open_patient';

    /* =====================================================
       STATE
    ===================================================== */

    let autoSearchTimer = null;

    let lastSearchedKeyword = '';
    let currentRequestKeyword = '';

    let searchRequest = null;
    let requestSequence = 0;

    /* =====================================================
       UTILITIES
    ===================================================== */

    function escapeHTML(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }


    function formatDate(value) {
        if (!value) return '-';

        const date = new Date(String(value).replace(' ', 'T'));

        if (Number.isNaN(date.getTime())) {
            return value;
        }

        return new Intl.DateTimeFormat('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        }).format(date);
    }


    function formatDateTime(value) {
        if (!value) return '-';

        const date = new Date(String(value).replace(' ', 'T'));

        if (Number.isNaN(date.getTime())) {
            return value;
        }

        return new Intl.DateTimeFormat('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        }).format(date);
    }


    function getStatusKunjungan(status) {
        const value = String(status ?? '');

        if (value === '1') {
            return {
                text: 'Sedang Dilayani',
                class: 'status-active',
                icon: 'bi-activity'
            };
        }

        if (value === '2') {
            return {
                text: 'Kunjungan Selesai',
                class: 'status-finished',
                icon: 'bi-check-circle'
            };
        }

        if (value === '0') {
            return {
                text: 'Kunjungan Batal',
                class: 'status-cancelled',
            icon: 'bi-x-circle'
            };
        }

        return {
            text: 'Status Tidak Diketahui',
            class: '',
            icon: 'bi-question-circle'
        };
    }


    /* =====================================================
       INFO
    ===================================================== */

    function showInfo(message, type = 'info') {
        const icon = type === 'danger'
            ? 'bi-exclamation-circle'
            : type === 'success'
                ? 'bi-check-circle'
                : 'bi-info-circle';

        const color = type === 'danger'
            ? 'text-danger'
            : type === 'success'
                ? 'text-success'
                : 'text-body-secondary';

        infoContainer.innerHTML = `
            <div class="small ${color}">
                <i class="bi ${icon} me-1"></i>
                ${escapeHTML(message)}
            </div>
        `;
    }


    function clearInfo() {
        infoContainer.innerHTML = '';
    }


    /* =====================================================
       EMPTY STATE
    ===================================================== */

    function showEmpty(message, description = '') {
        resultContainer.innerHTML = `
            <div class="emr-search-empty">

                <div class="emr-search-empty-icon">
                    <i class="bi bi-person"></i>
                </div>

                <h6 class="fw-semibold mb-1">
                    ${escapeHTML(message)}
                </h6>

                <p class="text-body-secondary small mb-0">
                    ${escapeHTML(description)}
                </p>

            </div>
        `;
    }


    function showInitialState() {
        showEmpty(
            'Cari pasien terlebih dahulu',
            'Masukkan nomor RM, nomor kunjungan, nomor asuransi, atau nama pasien.'
        );
    }


    /* =====================================================
       LOADING
    ===================================================== */

    function showLoading() {
        resultContainer.innerHTML = `
            <div class="emr-search-loading">

                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading</span>
                </div>

                <div class="fw-semibold">
                    Mencari data pasien...
                </div>

                <div class="small">
                    Mohon tunggu sebentar
                </div>

            </div>
        `;
    }


    function setLoading(isLoading) {
        searchButton.disabled = isLoading;

        searchButton.innerHTML = isLoading
            ? `
                <span class="spinner-border spinner-border-sm"
                      role="status"
                      aria-hidden="true"></span>
              `
            : `
                <i class="bi bi-search"></i>
              `;
    }


    /* =====================================================
       CANCEL REQUEST
    ===================================================== */

    function cancelCurrentRequest() {
        requestSequence++;

        if (searchRequest && searchRequest.readyState !== 4) {
            searchRequest.abort();
        }

        searchRequest = null;
        currentRequestKeyword = '';

        setLoading(false);
    }


    /* =====================================================
       OPEN MODAL
    ===================================================== */

    document.addEventListener('click', function (event) {
        const button = event.target.closest('#cariEMRPasien');

        if (!button) return;

        event.preventDefault();

        modal.show();
    });


    /* =====================================================
       SEARCH ACTION
    ===================================================== */
    input.addEventListener('keydown', function (event) {
        if (event.key !== 'Enter') return;

        event.preventDefault();

        clearTimeout(autoSearchTimer);

        searchPatient(input.value.trim(), true);
    });


    searchButton.addEventListener('click', function () {
        clearTimeout(autoSearchTimer);

        searchPatient(input.value.trim(), true);
    });


    /* =====================================================
       AUTO SEARCH
    ===================================================== */
    input.addEventListener('input', function () {
        const keyword = this.value.trim();

        clearTimeout(autoSearchTimer);

        // Batalkan request sebelumnya jika keyword berubah
        if (currentRequestKeyword && currentRequestKeyword !== keyword) {
            cancelCurrentRequest();
        }

        // Keyword kosong
        if (!keyword) {
            lastSearchedKeyword = '';

            cancelCurrentRequest();

            clearInfo();
            showInitialState();

            return;
        }

        // Keyword kurang dari minimum
        if (keyword.length < MIN_SEARCH_LENGTH) {
            lastSearchedKeyword = '';

            cancelCurrentRequest();

            clearInfo();

            showEmpty(
                'Masukkan minimal 3 karakter',
                'Pencarian akan dilakukan setelah minimal 3 karakter.'
            );

            return;
        }

        // Jika keyword sama dengan yang sedang dicari
        if (keyword === currentRequestKeyword) {
            return;
        }

        // Jika keyword sama dengan hasil pencarian sebelumnya
        if (keyword === lastSearchedKeyword) {
            return;
        }

        autoSearchTimer = setTimeout(() => {
            searchPatient(keyword);
        }, AUTO_SEARCH_DELAY);
    });


    /* =====================================================
       SEARCH PATIENT
    ===================================================== */

    function searchPatient(keyword = input.value.trim(), force = false) {
        clearTimeout(autoSearchTimer);

        keyword = String(keyword ?? '').trim();

        if (!keyword) {
            cancelCurrentRequest();

            clearInfo();
            showInitialState();

            return;
        }

        if (keyword.length < MIN_SEARCH_LENGTH) {
            cancelCurrentRequest();

            clearInfo();

            showEmpty(
                'Masukkan minimal 3 karakter',
                'Pencarian akan dilakukan setelah minimal 3 karakter.'
            );

            return;
        }

        // Jangan mengulang keyword yang sama
        if (!force && keyword === lastSearchedKeyword) {
            return;
        }

        // Jangan mengulang request keyword yang sama
        if (!force && keyword === currentRequestKeyword) {
            return;
        }

        // Batalkan request sebelumnya jika masih berjalan
        if (searchRequest && searchRequest.readyState !== 4) {
            searchRequest.abort();
        }

        const currentSequence = ++requestSequence;

        currentRequestKeyword = keyword;
        lastSearchedKeyword = keyword;

        clearInfo();
        showLoading();
        setLoading(true);

        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        searchRequest = $.ajax({
            url: SEARCH_URL,
            method: 'POST',
            dataType: 'json',
            data: {
                keyword: keyword
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },

            /* =============================================
               SUCCESS
            ============================================= */

            success: function (response) {

                // Abaikan response dari request lama
                if (currentSequence !== requestSequence) {
                    return;
                }

                // Input sudah berubah sejak request dikirim
                if (input.value.trim() !== keyword) {
                    return;
                }

                if (!response || response.success !== true) {
                    showEmpty(
                        'Pencarian gagal',
                        'Server tidak memberikan respons pencarian yang valid.'
                    );

                    showInfo(
                        'Respons server tidak valid.',
                        'danger'
                    );

                    return;
                }

                const data = Array.isArray(response.data)
                    ? response.data
                    : [];

                if (data.length === 0) {
                    showEmpty(
                        'Pasien tidak ditemukan',
                        'Tidak ditemukan pasien atau kunjungan sesuai kata pencarian.'
                    );

                    showInfo(
                        'Tidak ada data ditemukan.',
                        'danger'
                    );

                    return;
                }

                renderPatientVisits(data);

                showInfo(
                    `${data.length} kunjungan ditemukan.`,
                    'success'
                );
            },


            /* =============================================
               ERROR
            ============================================= */

            error: function (xhr, status) {

                // Abort bukan error yang perlu ditampilkan
                if (status === 'abort') {
                    return;
                }

                // Abaikan error dari request lama
                if (currentSequence !== requestSequence) {
                    return;
                }

                console.error('[EMRSearch]', xhr);

                showEmpty(
                    'Terjadi kesalahan',
                    xhr.responseJSON?.message ??
                    'Gagal menghubungi server pencarian EMR.'
                );

                showInfo(
                    'Pencarian gagal. Silakan coba kembali.',
                    'danger'
                );
            },


            /* =============================================
               COMPLETE
            ============================================= */

            complete: function () {

                // Callback request lama tidak boleh mengubah UI
                // request terbaru
                if (currentSequence !== requestSequence) {
                    return;
                }

                searchRequest = null;
                currentRequestKeyword = '';

                setLoading(false);
            }
        });
    }


    /* =====================================================
       RENDER PATIENT + VISIT LIST
    ===================================================== */

    function renderPatientVisits(data) {
        if (!Array.isArray(data) || data.length === 0) {
            showInitialState();
            return;
        }

        const patient = data[0];

        const norm = patient.NORM ?? '';
        const nama = patient.NAMAPASIEN ?? 'Pasien';
        const alamat = patient.ALAMATPASIEN ?? '-';
        const umur = patient.UMURPASIEN ?? '-';

        let html = `
            <div class="emr-search-patient-card">

                <!-- PATIENT HEADER -->
                <div class="emr-search-patient-header">

                    <div class="d-flex align-items-center gap-3">

                        <div class="emr-search-patient-avatar">
                            <i class="bi bi-person-vcard"></i>
                        </div>

                        <div class="min-w-0 flex-grow-1">

                            <div class="emr-search-patient-name">
                                ${escapeHTML(nama)}
                            </div>

                            <div class="emr-search-patient-meta">
                                RM. ${escapeHTML(norm)}
                                <span class="mx-1">•</span>
                                ${escapeHTML(umur)}
                            </div>

                        </div>

                    </div>

                    <div class="small text-body-secondary mt-3">
                        <i class="bi bi-geo-alt me-1"></i>
                        ${escapeHTML(alamat)}
                    </div>

                </div>


                <!-- VISIT HEADER -->
                <div class="px-3 px-md-4 py-3 border-bottom">

                    <div class="d-flex align-items-center justify-content-between">

                        <div class="fw-bold">
                            <i class="bi bi-clock-history text-primary me-2"></i>
                            Riwayat Kunjungan
                        </div>

                        <span class="badge bg-primary-subtle text-primary">
                            ${data.length} Kunjungan
                        </span>

                    </div>

                </div>


                <!-- VISITS -->
                <div class="emr-search-visit-list">
        `;


        data.forEach((item, index) => {
            const status = getStatusKunjungan(item.STATUSKUNJUNGAN);

            const statusBadge = `
                <span class="emr-search-status ${status.class}">
                    <i class="bi ${status.icon}"></i>
                    ${escapeHTML(status.text)}
                </span>
            `;

            html += `
                <div class="emr-search-visit"
                     role="button"
                     tabindex="0"
                     data-emr-visit-index="${index}">

                    <div class="emr-search-visit-icon">
                        <i class="bi bi-hospital"></i>
                    </div>

                    <div class="emr-search-visit-content">

                        <div class="emr-search-visit-title">
                            ${escapeHTML(item.NAMARUANGAN ?? '-')}
                        </div>

                        <div class="emr-search-visit-info mb-2">

                            <span>
                                <i class="bi bi-calendar3"></i>
                                ${escapeHTML(formatDateTime(item.TGLMASUK))}
                            </span>

                            <span>
                                <i class="bi bi-box-arrow-right"></i>
                                Keluar: ${escapeHTML(formatDateTime(item.TGLKELUAR))}
                            </span>

                        </div>

                        <div class="emr-search-visit-info mb-2">

                            <span>
                                <i class="bi bi-person-badge"></i>
                                ${escapeHTML(item.NAMADOKTER ?? '-')}
                            </span>

                            <span>
                                <i class="bi bi-shield-check"></i>
                                ${escapeHTML(item.NAMAPENJAMIN ?? '-')}
                            </span>

                        </div>

                        ${statusBadge}

                    </div>

                    <div class="emr-search-visit-arrow">
                        <i class="bi bi-chevron-right fs-5"></i>
                    </div>

                </div>
            `;
        });


        html += `
                </div>
            </div>
        `;

        resultContainer.innerHTML = html;


        /* =================================================
           CLICK VISIT
        ================================================= */

        resultContainer
            .querySelectorAll('[data-emr-visit-index]')
            .forEach(element => {

                element.addEventListener('click', function () {
                    const index = Number(this.dataset.emrVisitIndex);
                    const visit = data[index];

                    if (!visit) return;

                    openSelectedVisit(visit);
                });


                element.addEventListener('keydown', function (event) {
                    if (event.key !== 'Enter' && event.key !== ' ') {
                        return;
                    }

                    event.preventDefault();

                    const index = Number(this.dataset.emrVisitIndex);
                    const visit = data[index];

                    if (!visit) return;

                    openSelectedVisit(visit);
                });

            });
    }


    /* =====================================================
       HIDE MODAL THEN CALLBACK
    ===================================================== */

    function hideModalThen(callback) {

        // Modal sudah tertutup
        if (!modalElement.classList.contains('show')) {
            if (typeof callback === 'function') {
                callback();
            }

            return;
        }

        modalElement.addEventListener('hidden.bs.modal', function () {
            if (typeof callback === 'function') {
                callback();
            }
        }, { once: true });

        modal.hide();
    }


    /* =====================================================
       OPEN SELECTED VISIT
    ===================================================== */

    function openSelectedVisit(visit) {
        const nomor = String(visit.NOKUNJUNGAN ?? '').trim();

        if (!nomor) {
            showInfo(
                'Nomor kunjungan tidak ditemukan.',
                'danger'
            );

            return;
        }

        const patient = {
            nomor: nomor,

            norm: String(visit.NORM ?? ''),
            nama: String(visit.NAMAPASIEN ?? 'Pasien'),

            umur: String(visit.UMURPASIEN ?? ''),

            namaRuangan: String(visit.NAMARUANGAN ?? ''),
            namaDokter: String(visit.NAMADOKTER ?? ''),

            refIdAsuransi: String(visit.IDPENJAMIN ?? ''),
            namaAsuransi: String(visit.NAMAPENJAMIN ?? ''),

            noAsuransi: String(visit.NOMORASURANSI ?? ''),

            tglMasuk: String(visit.TGLMASUK ?? ''),
            tglKeluar: String(visit.TGLKELUAR ?? ''),

            statusKunjungan: String(visit.STATUSKUNJUNGAN ?? ''),

            url: `/v3/emr/${encodeURIComponent(nomor)}`
        };


        /* =================================================
           CURRENT EMR PAGE
        ================================================= */

        if (
            window.EMRWorkspace &&
            typeof window.EMRWorkspace.openPatient === 'function'
        ) {
            hideModalThen(() => {
                window.EMRWorkspace.openPatient(patient);
            });

            return;
        }


        /* =================================================
           NOT IN EMR PAGE
           STORE PATIENT TEMPORARILY AND REDIRECT
        ================================================= */

        try {
            sessionStorage.setItem(
                PENDING_PATIENT_KEY,
                JSON.stringify(patient)
            );
        } catch (error) {
            console.error(
                '[EMRSearch] Gagal menyimpan data kunjungan:',
                error
            );
        }

        modal.hide();

        window.location.href = EMR_PAGE_URL;
    }


    /* =====================================================
       AUTO OPEN PATIENT AFTER REDIRECT
    ===================================================== */

    function openPendingPatient() {
        let patient = null;

        try {
            const raw = sessionStorage.getItem(PENDING_PATIENT_KEY);

            if (!raw) return;

            patient = JSON.parse(raw);

            sessionStorage.removeItem(PENDING_PATIENT_KEY);

        } catch (error) {
            console.error(
                '[EMRSearch] Data pasien pending tidak valid:',
                error
            );

            sessionStorage.removeItem(PENDING_PATIENT_KEY);

            return;
        }

        if (!patient) return;

        // Tunggu EMRWorkspace tersedia
        let attempt = 0;

        const maxAttempt = 40;

        const openInterval = setInterval(() => {
            attempt++;

            if (
                window.EMRWorkspace &&
                typeof window.EMRWorkspace.openPatient === 'function'
            ) {
                clearInterval(openInterval);

                window.EMRWorkspace.openPatient(patient);

                return;
            }

            if (attempt >= maxAttempt) {
                clearInterval(openInterval);

                console.error(
                    '[EMRSearch] EMRWorkspace tidak berhasil diinisialisasi.'
                );
            }

        }, 250);
    }


    /* =====================================================
       RESET WHEN MODAL CLOSED
    ===================================================== */

    modalElement.addEventListener('hidden.bs.modal', function () {
        clearTimeout(autoSearchTimer);

        cancelCurrentRequest();

        input.value = '';
        lastSearchedKeyword = '';

        clearInfo();

        showInitialState();
    });


    /* =====================================================
       MODAL FOCUS
    ===================================================== */

    modalElement.addEventListener('shown.bs.modal', function () {
        requestAnimationFrame(() => {
            input.focus();
            input.select();
        });
    });


    /* =====================================================
       INIT
    ===================================================== */

    showInitialState();

    openPendingPatient();

})();
