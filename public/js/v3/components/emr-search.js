/* =========================================================
   SIRMED V3 — EMR SEARCH
   Search patient by NORM and open patient visit workspace
========================================================= */

(() => {
    'use strict';

    const modalElement = document.getElementById('modalCariEMR');

    if (!modalElement || modalElement.dataset.initialized === 'true') {
        return;
    }

    modalElement.dataset.initialized = 'true';

    const modal = bootstrap.Modal.getOrCreateInstance(modalElement);

    const input = document.getElementById('cariEMRNorm');
    const searchButton = document.getElementById('btnCariEMR');
    const resultContainer = document.getElementById('cariEMRHasil');
    const infoContainer = document.getElementById('cariEMRInfo');
    const modalCariEMR = document.getElementById('modalCariEMR');
    const inputCariEMR = document.getElementById('cariEMRNorm');

    let autoSearchTimer = null;
    const AUTO_SEARCH_DELAY = 1000; // 5 Detik Auto Search after texting

    let lastSearchedNorm = '';
    let isSearching = false;

    const SEARCH_URL = '/api/v3/search/emr';
    const EMR_PAGE_URL = '/v3/emr';

    let searchRequest = null;


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

        const date = new Date(value.replace(' ', 'T'));

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

        const date = new Date(value.replace(' ', 'T'));

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
                <span class="spinner-border spinner-border-sm"></span>
              `
            : `
                <i class="bi bi-search"></i>
              `;
    }


    /* =====================================================
       OPEN MODAL
    ===================================================== */

    document.getElementById('cariEMRPasien')?.addEventListener('click', function () {

        modal.show();

        setTimeout(() => {
            input.focus();
        }, 300);

    });


    /* =====================================================
       INPUT ONLY NUMBER — MAX 8 DIGIT
    ===================================================== */

    input.addEventListener('input', function () {

        this.value = this.value
            .replace(/\D/g, '')
            .substring(0, 8);

    });


    /* =====================================================
    ENTER TO SEARCH
    ===================================================== */

    input.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            clearTimeout(autoSearchTimer);
            searchPatient();
        }
    });

    searchButton.addEventListener('click', function () {
        clearTimeout(autoSearchTimer);
        searchPatient();
    });


    /* =====================================================
    AUTO SEARCH - 5 SECONDS
    ===================================================== */

    input.addEventListener('input', function () {

        // Hanya angka, maksimal 8 digit
        this.value = this.value.replace(/\D/g, '').slice(0, 8);

        clearTimeout(autoSearchTimer);

        const norm = normalizeNorm(this.value);

        // Jika input kosong
        if (!norm) {
            lastSearchedNorm = '';
            showEmpty();
            clearInfo();
            return;
        }

        // Jika NORM sama dengan pencarian terakhir
        if (norm === lastSearchedNorm) {
            return;
        }

        // Auto-search setelah berhenti mengetik 5 detik
        autoSearchTimer = setTimeout(() => {
            searchPatient();
        }, AUTO_SEARCH_DELAY);

    });

    function normalizeNorm(value) {
        return String(value ?? '')
            .replace(/\D/g, '')
            .replace(/^0+/, '');
    }

    /* =====================================================
       SEARCH PATIENT
    ===================================================== */
    async function searchPatient() {
        clearTimeout(autoSearchTimer);

        const norm = normalizeNorm(input.value);

        if (!norm) {
            showEmpty();
            return;
        }

        // Jangan request jika nomor yang sama sedang dicari
        if (isSearching) {
            return;
        }

        // Jika nomor yang sama sudah selesai dicari, tidak perlu request ulang
        if (norm === lastSearchedNorm) {
            return;
        }

        isSearching = true;
        lastSearchedNorm = norm;

        clearInfo();
        showLoading();
        setLoading(true);

        searchRequest = $.ajax({
            url: SEARCH_URL,
            method: 'POST',
            dataType: 'json',
            data: {
                norm: norm
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            success: function (response) {

                if (!response || response.success !== true) {
                    showEmpty(
                        'Pencarian gagal',
                        'Server tidak memberikan respons pencarian yang valid.'
                    );
                    return;
                }

                const data = Array.isArray(response.data)
                    ? response.data
                    : [];

                if (data.length === 0) {
                    showEmpty(
                        'Pasien tidak ditemukan',
                        'Tidak ditemukan kunjungan pasien dengan nomor rekam medis tersebut.'
                    );

                    showInfo('Tidak ada data kunjungan ditemukan.', 'danger');
                    return;
                }

                renderPatientVisits(data);

                showInfo(
                    `${data.length} kunjungan ditemukan.`,
                    'success'
                );

            },

            error: function (xhr, status) {

                if (status === 'abort') {
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

            complete: function () {
                isSearching = false;
                setLoading(false);
                searchRequest = null;
            }
        });

    }


    /* =====================================================
       RENDER PATIENT + VISIT LIST
    ===================================================== */

    function renderPatientVisits(data) {

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

        resultContainer.querySelectorAll('[data-emr-visit-index]')
            .forEach(element => {

                element.addEventListener('click', function () {

                    const index = Number(this.dataset.emrVisitIndex);
                    const visit = data[index];

                    if (!visit) return;

                    openSelectedVisit(visit);

                });


                element.addEventListener('keydown', function (event) {

                    if (event.key === 'Enter' || event.key === ' ') {

                        event.preventDefault();

                        const index = Number(this.dataset.emrVisitIndex);
                        const visit = data[index];

                        if (!visit) return;

                        openSelectedVisit(visit);

                    }

                });

            });

    }


    /* =====================================================
       OPEN SELECTED VISIT
    ===================================================== */

    function openSelectedVisit(visit) {

        const nomor = String(visit.NOKUNJUNGAN ?? '').trim();

        if (!nomor) {
            showInfo('Nomor kunjungan tidak ditemukan.', 'danger');
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

            noAsuransi: '',

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

            modal.hide();

            window.EMRWorkspace.openPatient(patient);

            return;
        }


        /* =================================================
           NOT IN EMR PAGE
           Store patient temporarily and redirect
        ================================================= */

        try {

            sessionStorage.setItem(
                'sirmed_emr_search_open_patient',
                JSON.stringify(patient)
            );

        } catch (error) {

            console.error('[EMRSearch] Gagal menyimpan data kunjungan:', error);

        }

        modal.hide();

        window.location.href = EMR_PAGE_URL;

    }


    /* =====================================================
       AUTO OPEN PATIENT AFTER REDIRECT
       Dipanggil ketika halaman index EMR sudah siap
    ===================================================== */

    function openPendingPatient() {

        let patient = null;

        try {

            const raw = sessionStorage.getItem(
                'sirmed_emr_search_open_patient'
            );

            if (!raw) return;

            patient = JSON.parse(raw);

            sessionStorage.removeItem(
                'sirmed_emr_search_open_patient'
            );

        } catch (error) {

            console.error('[EMRSearch] Data pasien pending tidak valid:', error);

            sessionStorage.removeItem(
                'sirmed_emr_search_open_patient'
            );

            return;

        }

        if (!patient) return;


        // Workspace bisa saja belum siap saat script pertama berjalan.
        let attempt = 0;

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

            if (attempt >= 40) {

                clearInterval(openInterval);

                console.error(
                    '[EMRSearch] EMRWorkspace tidak berhasil diinisialisasi.'
                );

            }

        }, 250);

    }


    /* =====================================================
       INIT
    ===================================================== */

    openPendingPatient();


    /* =====================================================
       RESET WHEN MODAL CLOSED
    ===================================================== */

    modalElement.addEventListener('hidden.bs.modal', function () {

        input.value = '';
        clearInfo();

        showEmpty(
            'Cari pasien terlebih dahulu',
            'Masukkan nomor rekam medis pasien kemudian tekan Cari Pasien.'
        );

    });

    modalCariEMR.addEventListener('shown.bs.modal', function () {
        setTimeout(() => {
            inputCariEMR.focus();
            inputCariEMR.select();
        }, 100);
    });

})();
