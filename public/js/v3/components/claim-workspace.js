/*
|--------------------------------------------------------------------------
| SIRMED V3 — CLAIM WORKSPACE
|--------------------------------------------------------------------------
| - Mempertahankan daftar tab saat navigasi Livewire.
| - Saat kembali ke Smart Claim, hanya render header tab (tanpa iframe).
| - Iframe detail hanya dibuat ketika tab diklik (lazy loading).
| - F5 membersihkan tab karena state hanya berada di window memory.
| - Label tab menampilkan nomor SEP, bukan nomor RM/nama pasien.
| - Smart Claim dan Smart Claim Farmasi.
| - Close tab, close other, close all, refresh, retry, topbar identity.
|--------------------------------------------------------------------------
*/

(() => {
    'use strict';

    // Script dimuat sekali; Livewire navigation ditangani event livewire:navigated.
    if (window.__SIRMED_CLAIM_WORKSPACE_BOOTSTRAP__) return;
    window.__SIRMED_CLAIM_WORKSPACE_BOOTSTRAP__ = true;

    const state = window.__SIRMED_CLAIM_WORKSPACE_STATE__ ||
        (window.__SIRMED_CLAIM_WORKSPACE_STATE__ = {
            tabs: new Map(),
            activeTabId: null,
            sequence: 0,
        });

    // Pastikan struktur state sesuai versi ini.
    if (!(state.tabs instanceof Map)) state.tabs = new Map();
    if (!Number.isFinite(state.sequence)) state.sequence = 0;
    if (!('activeTabId' in state)) state.activeTabId = null;

    const SELECTORS = {
        root: '#claimWorkspace',
        tabBar: '#claimTabBar',
        tabScroll: '#claimTabBarScroll',
        content: '#claimWorkspaceContent',
        closeOther: '#claimCloseOtherTabs',
        closeAll: '#claimCloseAllTabs',
        refresh: '#claimRefreshActiveTab',
        homePanel: '#claimHomePanel',
    };

    let root = null;
    let tabBar = null;
    let tabScroll = null;
    let content = null;
    let homePanel = null;

    // Simpan kondisi awal topbar agar bisa dikembalikan
    // ketika user kembali ke halaman Home Smart Claim.
    if (!('initialTopbar' in state)) {
        state.initialTopbar = null;
    }

    if (!('initialDocumentTitle' in state)) {
        state.initialDocumentTitle = null;
    }

    function getElements() {
        root = document.querySelector(SELECTORS.root);
        tabBar = document.querySelector(SELECTORS.tabBar);
        tabScroll = document.querySelector(SELECTORS.tabScroll);
        content = document.querySelector(SELECTORS.content);
        homePanel = document.querySelector(SELECTORS.homePanel);

        return Boolean(root && tabBar && tabScroll && content && homePanel);
    }

    function setTopbarText(selector, value) {
        const element = document.querySelector(selector);
        if (element) element.textContent = value ?? '-';
    }

    function getTopbarText(selector) {
        return document.querySelector(selector)?.textContent?.trim() ?? '';
    }

    function cleanTopbarText(value) {
        return String(value ?? '')
            .replace(/[•·]/g, '')
            .replace(/\s+/g, ' ')
            .trim();
    }

    /**
     * Menyimpan kondisi awal topbar satu kali.
     * Dipakai oleh resetTopbar() ketika kembali ke Home.
     */
    function captureInitialTopbar() {
        if (state.initialTopbar) return;

        state.initialTopbar = {
            pageSubTitle: getTopbarText('#pageSubTitlebar'),
            pageTitle: getTopbarText('#pageTitlebar'),
            pageTitleTitle: document.querySelector('#pageTitlebar')?.getAttribute('title') ?? '',

            birth: getTopbarText('#patientBirthBar'),
            room: getTopbarText('#patientRoomTopbar'),
            doctor: getTopbarText('#patientDoctorTopbar'),

            idAsuransi: getTopbarText('#patientRefIdAsuransiTopbar'),
            insuranceName: getTopbarText('#patientInsuranceNameTopbar'),

            tglMasuk: getTopbarText('#patientTglMasukTopbar'),
            tglKeluar: getTopbarText('#patientTglKeluarTopbar'),
            noAsuransi: getTopbarText('#patientInsuranceTopbar'),

            status: getTopbarText('#patientStatusTopbar'),
            statusClass: document.querySelector('#patientStatusTopbar')?.className
                || 'badge'
        };

        state.initialDocumentTitle = document.title;
    }

    /**
     * Typography topbar.
     * Dipakai oleh Home maupun identitas pasien.
     */
    function applyTopbarTypography(element) {
        if (!element) return;

        element.classList.remove('text-muted');

        element.style.display = 'block';
        element.style.fontSize = '1.15rem';
        element.style.fontWeight = '700';
        element.style.lineHeight = '1.25';
        element.style.color = 'var(--bs-body-color)';
    }

    function normalizeText(value) {
        if (value === null || value === undefined) return '';
        return String(value).trim();
    }

    function firstNonEmpty(...values) {
        for (const value of values) {
            if (
                value !== null &&
                value !== undefined &&
                String(value).trim() !== ''
            ) {
                return String(value).trim();
            }
        }

        return '';
    }

    function formatRM(norm) {
        const value = normalizeText(norm);
        return value ? value.padStart(8, '0') : '-';
    }

    // Mengikuti format EMRWorkspace: YYYY-MM-DD menjadi DD-MM-YYYY.
    function formatTanggalLahir(value) {
        const date = normalizeText(value);
        if (!date) return '-';

        const match = date.match(/^(\d{4})-(\d{2})-(\d{2})/);

        return match
            ? `${match[3]}-${match[2]}-${match[1]}`
            : date;
    }

    // Mengikuti format EMRWorkspace: DD-MM-YYYY HH:mm WIB.
    function formatTanggalKunjungan(value) {
        const date = normalizeText(value);
        if (!date) return '-';

        const match = date.match(
            /^(\d{4})-(\d{2})-(\d{2})(?:[ T](\d{2}):(\d{2})(?::\d{2})?)?/
        );

        if (!match) return date;

        const tanggal = `${match[3]}-${match[2]}-${match[1]}`;

        return match[4] && match[5]
            ? `${tanggal} ${match[4]}:${match[5]}`
            : tanggal;
    }

    function updatePatientNameTooltip(element, title) {
        if (!element) return;

        element.setAttribute('title', title);
        element.setAttribute('data-bs-original-title', title);

        if (window.bootstrap?.Tooltip) {
            const existingTooltip = window.bootstrap.Tooltip.getInstance(element);

            if (existingTooltip) existingTooltip.dispose();

            new window.bootstrap.Tooltip(element, {
                placement: 'bottom',
                trigger: 'hover focus'
            });
        }
    }

    function applyInsuranceColor(idAsuransi) {
        const patientInsuranceName = document.querySelector(
            '#patientInsuranceNameTopbar'
        );

        if (!patientInsuranceName) return;

        const id = String(idAsuransi ?? '');

        patientInsuranceName.classList.remove(
            'text-muted',
            'text-warning',
            'text-primary'
        );

        patientInsuranceName.style.removeProperty('color');

        // Sama dengan pemetaan warna pada EMRWorkspace.
        if (id === '1') {
            patientInsuranceName.classList.add('text-warning');
            patientInsuranceName.style.color = 'var(--bs-warning)';
        } else if (id === '2') {
            patientInsuranceName.classList.add('text-primary');
            patientInsuranceName.style.color = 'var(--bs-primary)';
        } else {
            patientInsuranceName.style.color = 'var(--bs-body-color)';
        }
    }

    function getClaimVerificationStatus(metadata = {}) {
        const explicitStatus = firstNonEmpty(
            metadata.statusKlaim,
            metadata.statusVerifText,
            metadata.STATUSKLAIM
        ).toUpperCase();

        const rawStatus = firstNonEmpty(
            metadata.statusVerif,
            metadata.STATUSVERIF,
            metadata.verif,
            metadata.VERIF
        ).toLowerCase();

        const verified =
            explicitStatus.includes('SUDAH DIVERIFIKASI') ||
            explicitStatus.includes('TERVERIFIKASI') ||
            ['1', 'true', 'yes', 'verified'].includes(rawStatus);

        return verified
            ? {
                label: 'SUDAH DIVERIFIKASI',
                className: 'badge bg-success-subtle text-success border border-success-subtle'
            }
            : {
                label: 'BELUM DIVERIFIKASI',
                className: 'badge bg-danger-subtle text-danger border border-danger-subtle'
            };
    }

    function showClaimPatientIdentity(metadata = {}) {
        const topbar = document.querySelector('#appTopbar');
        const defaultIdentity = document.querySelector('#topbarDefaultIdentity');
        const workspaceIdentity = document.querySelector('#topbarWorkspaceIdentity');

        if (!topbar || !defaultIdentity || !workspaceIdentity) return;

        topbar.classList.remove('is-home-mode');
        topbar.classList.add('is-workspace-mode');

        defaultIdentity.style.display = 'none';
        workspaceIdentity.style.display = 'flex';

        const patientDropdownContainer = document.querySelector(
            '#patientDropdownContainer'
        );

        if (patientDropdownContainer) {
            patientDropdownContainer.style.display = 'none';
        }

        const topBarDivider1 = document.querySelector('#topbarDivider1');
        const topBarDivider2 = document.querySelector('#topbarDivider2');
        const topBarDivider3 = document.querySelector('#topbarDivider3');

        const pageSubTitle = document.querySelector('#pageSubTitlebar');
        const pageTitle = document.querySelector('#pageTitlebar');

        const patientRoom = document.querySelector('#patientRoomTopbar');
        const patientDoctor = document.querySelector('#patientDoctorTopbar');
        const patientInsuranceNumber = document.querySelector('#patientInsuranceTopbar');
        const patientInsuranceName = document.querySelector('#patientInsuranceNameTopbar');
        const patientTglMasuk = document.querySelector('#patientTglMasukTopbar');
        const patientTglKeluar = document.querySelector('#patientTglKeluarTopbar');
        const patientStatus = document.querySelector('#patientStatusTopbar');
        const patientBirth = document.querySelector('#patientBirthBar');

        const norm = firstNonEmpty(
            metadata.norm,
            metadata.NORM,
            metadata.noRM,
            metadata.NORMPASIEN
        );
        const nama = firstNonEmpty(
            metadata.nama,
            metadata.namaPasien,
            metadata.NAMAPASIEN,
            metadata.namaLengkap,
            metadata.NAMALENGKAPPASIEN,
            metadata.NAMA,
            metadata.NAMA_PASIEN
        ) || 'Rekam Medis Pasien';

        const tanggalLahir = formatTanggalLahir(firstNonEmpty(
            metadata.tglLahir,
            metadata.tanggalLahir,
            metadata.TGLLAHIRPASIEN,
            metadata.TGL_LAHIR
        ));

        const umurPasien = firstNonEmpty(
            metadata.umur,
            metadata.umurPasien,
            metadata.UMURPASIEN,
            metadata.UMUR,
            metadata.Umur
        ) || '-';

        const namaAsuransi = firstNonEmpty(
            metadata.namaAsuransi,
            metadata.NAMAASURANSI
        ) || '-';

        const refIdAsuransi = firstNonEmpty(
            metadata.refIdAsuransi,
            metadata.REFIDASURANSI,
            metadata.idAsuransi,
            metadata.IDASURANSI
        );

        const noAsuransi = firstNonEmpty(
            metadata.noAsuransi,
            metadata.NOASURANSI,
            metadata.noBPJS,
            metadata.NOBPJS,
            metadata.noSEP,
            metadata.NOSEP,
            metadata.no_sep,
            metadata.nomorSEP,
            metadata.nomor_sep,
            metadata.sep,
            metadata.SEP
        );

        const namaRuangan = firstNonEmpty(
            metadata.namaRuangan,
            metadata.NAMARUANGAN,
            metadata.namaRuanganAsalKonsul,
            metadata.RUANGAN
        );

        const namaDokter = firstNonEmpty(
            metadata.namaDokter,
            metadata.NAMADOKTER,
            metadata.namaDokterAsalKonsul,
            metadata.DOKTER
        );

        const tglMasuk = firstNonEmpty(
            metadata.tglMasuk,
            metadata.MASUK,
            metadata.TGLMASUK,
            metadata.tglDaftar,
            metadata.TGLDAFTAR
        );

        const tglKeluar = firstNonEmpty(
            metadata.tglKeluar,
            metadata.KELUAR,
            metadata.TGLKELUAR
        );

        // =================================================
        // IDENTITAS PASIEN — disamakan dengan EMRWorkspace
        // =================================================
        if (pageSubTitle) {
            pageSubTitle.innerHTML =
                `<i class="ri-user-3-line me-1"></i> RM. ${escapeHtml(formatRM(norm))}`;

            applyTopbarTypography(pageSubTitle);
        }

        if (pageTitle) {
            pageTitle.textContent = nama;

            applyTopbarTypography(pageTitle);

            const tooltipNamaPasien = [
                `Tanggal Lahir: ${tanggalLahir}`,
                `Umur: ${umurPasien}`
            ].join('\n');

            updatePatientNameTooltip(pageTitle, tooltipNamaPasien);
        }

        // Tooltip menjadi tempat informasi tanggal lahir dan umur,
        // sama seperti EMRWorkspace. Tidak ditampilkan sebagai teks terpisah.
        if (patientBirth) patientBirth.textContent = '';

        // =================================================
        // INFORMASI KUNJUNGAN
        // =================================================

        if (topBarDivider1) {
            topBarDivider1.textContent = noAsuransi ? '• ' : '';
        }

        if (topBarDivider2) {
            topBarDivider2.textContent = namaDokter ? '• ' : '';
        }

        if (topBarDivider3) {
            topBarDivider3.textContent = nama ? '• ' : '';
        }

        setTopbarText('#patientInsuranceNameTopbar', namaAsuransi);
        applyInsuranceColor(refIdAsuransi);

        setTopbarText('#patientInsuranceTopbar', noAsuransi || '');
        setTopbarText('#patientRoomTopbar', namaRuangan || '-');
        setTopbarText('#patientDoctorTopbar', namaDokter || '');

        if (patientTglMasuk) {
            patientTglMasuk.textContent =
                `${formatTanggalKunjungan(tglMasuk)} WIB`;
        }

        if (patientTglKeluar) {
            patientTglKeluar.textContent = tglKeluar
                ? `${formatTanggalKunjungan(tglKeluar)} WIB`
                : '';
        }

        // =================================================
        // BADGE STATUS VERIFIKASI KLAIM
        // =================================================

        if (patientStatus) {
            const status = getClaimVerificationStatus(metadata);
            patientStatus.textContent = status.label;
            patientStatus.className = status.className;
        }

        document.title = `${nama} · Smart Claim · Sirmed v3`;
    }
        /**
     * Mengembalikan seluruh informasi dan styling topbar
     * ke kondisi awal seperti EMRWorkspace.
     */
    function resetTopbar() {
        const initial = state.initialTopbar || {};

        const topbar = document.querySelector('#appTopbar');
        const defaultIdentity = document.querySelector('#topbarDefaultIdentity');
        const workspaceIdentity = document.querySelector('#topbarWorkspaceIdentity');
        const patientDropdownContainer = document.querySelector('#patientDropdownContainer');

        const pageSubTitle = document.querySelector('#pageSubTitlebar');
        const pageTitle = document.querySelector('#pageTitlebar');

        const patientBirth = document.querySelector('#patientBirthBar');
        const patientRoom = document.querySelector('#patientRoomTopbar');
        const patientDoctor = document.querySelector('#patientDoctorTopbar');

        const patientInsuranceName = document.querySelector('#patientInsuranceNameTopbar');
        const patientRefIdAsuransi = document.querySelector('#patientRefIdAsuransiTopbar');

        const patientTglMasuk = document.querySelector('#patientTglMasukTopbar');
        const patientTglKeluar = document.querySelector('#patientTglKeluarTopbar');
        const patientInsurance = document.querySelector('#patientInsuranceTopbar');

        const patientStatus = document.querySelector('#patientStatusTopbar');

        const divider1 = document.querySelector('#topbarDivider1');
        const divider2 = document.querySelector('#topbarDivider2');
        const divider3 = document.querySelector('#topbarDivider3');

        // =====================================================
        // MODE HOME
        // =====================================================

        if (topbar) {
            topbar.classList.remove('is-workspace-mode');
            topbar.classList.add('is-home-mode');
        }

        if (defaultIdentity) {
            defaultIdentity.style.display = '';
        }

        if (workspaceIdentity) {
            workspaceIdentity.style.display = 'none';
        }

        if (patientDropdownContainer) {
            patientDropdownContainer.style.display = 'none';
        }

        // =====================================================
        // PAGE SUBTITLE
        // =====================================================

        if (pageSubTitle) {
            pageSubTitle.textContent = cleanTopbarText(initial.pageSubTitle);

            applyTopbarTypography(pageSubTitle);
        }

        // =====================================================
        // PAGE TITLE
        // =====================================================

        if (pageTitle) {
            pageTitle.textContent = cleanTopbarText(initial.pageTitle);

            applyTopbarTypography(pageTitle);

            // Hapus tooltip identitas pasien.
            const tooltip = window.bootstrap?.Tooltip?.getInstance(pageTitle);

            if (tooltip) {
                tooltip.dispose();
            }

            pageTitle.removeAttribute('data-bs-original-title');

            if (initial.pageTitleTitle) {
                pageTitle.setAttribute('title', initial.pageTitleTitle);
            } else {
                pageTitle.removeAttribute('title');
            }
        }

        // =====================================================
        // DIVIDER
        // =====================================================

        if (divider1) divider1.textContent = '';
        if (divider2) divider2.textContent = '';
        if (divider3) divider3.textContent = '';

        // =====================================================
        // INFORMASI PASIEN
        // =====================================================

        if (patientBirth) {
            patientBirth.textContent = cleanTopbarText(initial.birth);
            patientBirth.style.color = 'var(--bs-body-color)';
        }

        if (patientRoom) {
            patientRoom.textContent = cleanTopbarText(initial.room);
            patientRoom.style.color = 'var(--bs-body-color)';
        }

        if (patientDoctor) {
            patientDoctor.textContent = cleanTopbarText(initial.doctor);
            patientDoctor.style.color = 'var(--bs-body-color)';
        }

        // =====================================================
        // ASURANSI
        // =====================================================

        if (patientInsuranceName) {
            patientInsuranceName.textContent = cleanTopbarText(initial.insuranceName);

            patientInsuranceName.classList.remove(
                'text-muted',
                'text-warning',
                'text-primary'
            );

            patientInsuranceName.style.color = 'var(--bs-body-color)';
        }

        if (patientRefIdAsuransi) {
            patientRefIdAsuransi.textContent = cleanTopbarText(initial.idAsuransi);
            patientRefIdAsuransi.style.color = 'var(--bs-body-color)';
        }

        if (patientInsurance) {
            patientInsurance.textContent = cleanTopbarText(initial.noAsuransi);
            patientInsurance.style.color = 'var(--bs-body-color)';
        }

        // =====================================================
        // TANGGAL KUNJUNGAN
        // =====================================================

        if (patientTglMasuk) {
            patientTglMasuk.textContent = cleanTopbarText(initial.tglMasuk);
            patientTglMasuk.style.color = 'var(--bs-body-color)';
        }

        if (patientTglKeluar) {
            patientTglKeluar.textContent = cleanTopbarText(initial.tglKeluar);
            patientTglKeluar.style.color = 'var(--bs-body-color)';
        }

        // =====================================================
        // STATUS
        // =====================================================

        if (patientStatus) {
            patientStatus.textContent = initial.status || '';
            patientStatus.className = initial.statusClass || 'badge';
        }

        // Kembalikan document title.
        if (state.initialDocumentTitle) {
            document.title = state.initialDocumentTitle;
        }
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function normalizeVisitId(value) {
        return String(value ?? '').trim();
    }

    function normalizeClaimType(value) {
        const type = String(value ?? 'smart_claim').trim().toLowerCase();

        if (
            type === 'farmasi' ||
            type === 'smart_claim_farmasi' ||
            type === 'smart-claim-farmasi'
        ) {
            return 'smart_claim_farmasi';
        }

        return 'smart_claim';
    }

    function getClaimLabel(type) {
        return normalizeClaimType(type) === 'smart_claim_farmasi'
            ? 'Smart Claim Farmasi'
            : 'Smart Claim';
    }

    function getClaimIcon(type) {
        return normalizeClaimType(type) === 'smart_claim_farmasi'
            ? 'ti ti-pill'
            : 'ti ti-file-check';
    }

    // Cari SEP dari beberapa variasi key metadata yang lazim digunakan.
    function getNoSEP(metadata = {}) {
        const candidates = [
            metadata.noSEP,
            metadata.NOSEP,
            metadata.no_sep,
            metadata.nomorSEP,
            metadata.nomor_sep,
            metadata.sep,
            metadata.SEP,
        ];

        const value = candidates.find(
            item =>
                item !== null &&
                item !== undefined &&
                String(item).trim() !== ''
        );

        return value ? String(value).trim() : '';
    }

    // Jenis kunjungan untuk badge tab: K = Konsul, U = Utama.
    function getVisitKind(metadata = {}) {
        const jenis = firstNonEmpty(
            metadata.jenisKunjungan,
            metadata.JENIS_KUNJUNGAN,
            metadata.jenis_kunjungan
        ).toUpperCase();

        if (['KONSUL', 'K'].includes(jenis)) return 'K';
        if (['UTAMA', 'U'].includes(jenis)) return 'U';

        return '';
    }

    function getVisitKindBadgeClass(kind) {
        return kind === 'K'
            ? 'badge bg-danger-subtle text-danger border border-danger-subtle badge-sm'
            : 'badge bg-primary-subtle text-primary border border-primary-subtle badge-sm';
    }

    // Tab caption hanya SEP. Tidak fallback ke nomor RM atau nama pasien.
    function getTabLabel(metadata = {}) {
        const noSEP = getNoSEP(metadata);

        return noSEP
            ? `SEP. ${noSEP}`
            : 'SEP belum tersedia';
    }

    // Render label tab sebagai DOM agar badge K/U menjadi elemen terpisah.
        // Nomor SEP ditambahkan sebagai text node, bukan HTML mentah.
    function renderTabLabel(element, metadata = {}) {
        if (!element) return;

        element.replaceChildren();

        const noSEP = getNoSEP(metadata);
        const kind = getVisitKind(metadata);

        element.appendChild(
            document.createTextNode(
                noSEP ? `SEP. ${noSEP}` : 'SEP belum tersedia'
            )
        );

        if (!kind) return;

        const badge = document.createElement('span');
        badge.className = getVisitKindBadgeClass(kind);
        badge.textContent = kind;

        element.appendChild(document.createTextNode(' '));
        element.appendChild(badge);
    }

    function getClaimUrl(nomor, type = 'smart_claim') {
        const visitId = normalizeVisitId(nomor);

        if (!visitId) {
            throw new Error('Nomor kunjungan tidak boleh kosong.');
        }

        return normalizeClaimType(type) === 'smart_claim_farmasi'
            ? `/v3/smartclaim/${encodeURIComponent(visitId)}/farmasi`
            : `/v3/smartclaim/${encodeURIComponent(visitId)}`;
    }

    function makeFrameUrl(url) {
        const target = new URL(
            String(url ?? ''),
            window.location.origin
        );

        if (target.origin !== window.location.origin) {
            throw new Error(
                'URL detail klaim harus berasal dari aplikasi SIRMED.'
            );
        }

        target.searchParams.set('workspace', '1');

        return target.href;
    }

    function createLoadingElement(label) {
        const loading = document.createElement('div');

        loading.className = 'claim-panel-loading';
        loading.setAttribute('role', 'status');
        loading.setAttribute('aria-live', 'polite');

        loading.innerHTML = `
            <span class="spinner-border" aria-hidden="true"></span>
            <span>Memuat ${escapeHtml(label)}...</span>
        `;

        return loading;
    }

    function createErrorElement(message, tabId) {
        const errorPanel = document.createElement('div');

        errorPanel.className = 'claim-panel-error';

        errorPanel.innerHTML = `
            <div class="error-icon">
                <i class="ti ti-alert-circle" aria-hidden="true"></i>
            </div>

            <h5>Gagal Memuat Detail Klaim</h5>

            <p>${escapeHtml(message)}</p>

            <button
                type="button"
                class="btn btn-primary btn-sm"
                data-claim-retry="${escapeHtml(tabId)}"
            >
                <i class="ti ti-refresh me-1"></i>
                Coba Lagi
            </button>
        `;

        return errorPanel;
    }

    function createIdleElement(label) {
        const idle = document.createElement('div');

        idle.className = 'claim-panel-loading claim-panel-idle';

        idle.innerHTML = `
            <span>
                <i class="ti ti-click me-1" aria-hidden="true"></i>
                Klik tab <strong>${escapeHtml(label)}</strong>
                untuk memuat detail klaim.
            </span>
        `;

        return idle;
    }

    function scrollTabIntoView(tabElement) {
        if (!tabElement || !tabScroll) return;

        tabElement.scrollIntoView({
            behavior: 'smooth',
            block: 'nearest',
            inline: 'nearest',
        });
    }

    function getTabId(nomor, type) {
        return `${normalizeClaimType(type)}::${normalizeVisitId(nomor)}`;
    }

    // Membuat header dan panel placeholder saja. Fungsi ini TIDAK membuat iframe.
    function renderTabShell(tab) {
        if (!tabScroll || !content) return;

        const sequence = ++state.sequence;

        const tabElement = document.createElement('div');

        tabElement.className = 'claim-tab';
        tabElement.id = `claimTab-${sequence}`;
        tabElement.dataset.tabId = tab.tabId;

        tabElement.setAttribute('role', 'tab');
        tabElement.setAttribute('aria-selected', 'false');
        tabElement.setAttribute('aria-controls', `claimPanel-${sequence}`);

        tabElement.tabIndex = -1;
        tabElement.title = tab.label;

        const icon = document.createElement('i');

        icon.className = getClaimIcon(tab.type);
        icon.setAttribute('aria-hidden', 'true');

        const labelElement = document.createElement('span');

        labelElement.className = 'claim-tab-label';
        renderTabLabel(labelElement, tab.metadata || {});

        const closeButton = document.createElement('button');

        closeButton.type = 'button';
        closeButton.className = 'claim-tab-close';
        closeButton.dataset.closeClaimTab = tab.tabId;
        closeButton.title = 'Tutup tab';
        closeButton.setAttribute(
            'aria-label',
            `Tutup ${tab.label}${getVisitKind(tab.metadata || {}) ? ` (${getVisitKind(tab.metadata || {})})` : ''}`
        );

        closeButton.innerHTML = `
            <i class="ti ti-x" aria-hidden="true"></i>
        `;

        tabElement.append(
            icon,
            labelElement,
            closeButton
        );

        tabScroll.appendChild(tabElement);

        const panel = document.createElement('section');

        panel.className = 'claim-workspace-panel';
        panel.id = `claimPanel-${sequence}`;
        panel.dataset.panelId = tab.tabId;

        panel.setAttribute('role', 'tabpanel');
        panel.setAttribute('aria-labelledby', tabElement.id);

        panel.hidden = true;

        panel.appendChild(createIdleElement(tab.label));

        content.appendChild(panel);

        tab.tabElement = tabElement;
        tab.panel = panel;

        tab.frame = null;
        tab.loadingElement = null;

        tab.loaded = false;
        tab.loading = false;
        tab.error = false;
    }

    function createTab(nomor, type, metadata = {}, options = {}) {
        const normalizedNomor = normalizeVisitId(nomor);
        const normalizedType = normalizeClaimType(type);

        if (!normalizedNomor) {
            throw new Error('Nomor kunjungan tidak boleh kosong.');
        }

        const tabId = getTabId(
            normalizedNomor,
            normalizedType
        );

        const existing = state.tabs.get(tabId);

        if (existing) {
            // =================================================
            // METADATA TAB EXISTING
            // =================================================
            // replaceMetadata = true:
            // metadata dari endpoint merupakan sumber authoritative.
            //
            // replaceMetadata = false:
            // pertahankan perilaku merge metadata lama.
            // =================================================
            existing.metadata = options.replaceMetadata
                ? { ...metadata }
                : {
                    ...(existing.metadata || {}),
                    ...metadata,
                };

            existing.label = getTabLabel(existing.metadata);

            if (existing.tabElement) {
                const labelElement = existing.tabElement.querySelector(
                    '.claim-tab-label'
                );

                if (labelElement) {
                    renderTabLabel(
                        labelElement,
                        existing.metadata || {}
                    );
                }

                existing.tabElement.title = existing.label;

                const closeButton = existing.tabElement.querySelector(
                    '[data-close-claim-tab]'
                );

                if (closeButton) {
                    closeButton.setAttribute(
                        'aria-label',
                        `Tutup ${existing.label}${
                            getVisitKind(existing.metadata || {})
                                ? ` (${getVisitKind(existing.metadata || {})})`
                                : ''
                        }`
                    );
                }
            }

            return existing;
        }

        const url = getClaimUrl(
            normalizedNomor,
            normalizedType
        );

        const tab = {
            tabId,
            nomor: normalizedNomor,
            type: normalizedType,

            label: getTabLabel(metadata),
            url,

            metadata: { ...metadata },

            tabElement: null,
            panel: null,

            frame: null,
            loadingElement: null,

            loaded: false,
            loading: false,
            error: false,
        };

        state.tabs.set(tabId, tab);

        renderTabShell(tab);

        return tab;
    }

    // Iframe dibuat hanya saat user memilih tab. Jika sudah ada, tidak reload.
    function ensureTabFrame(tab) {
        if (!tab || !tab.panel) return false;

        if (tab.frame && tab.loaded) return true;
        if (tab.frame && tab.loading) return true;

        const loading = createLoadingElement(tab.label);

        const frame = document.createElement('iframe');

        frame.className = 'claim-detail-frame';
        frame.title = tab.label;
        frame.loading = 'eager';
        frame.referrerPolicy = 'same-origin';

        frame.setAttribute('frameborder', '0');

        frame.setAttribute(
            'allow',
            'clipboard-read; clipboard-write'
        );

        tab.panel.replaceChildren(
            loading,
            frame
        );

        tab.frame = frame;
        tab.loadingElement = loading;

        tab.loaded = false;
        tab.loading = true;
        tab.error = false;
                frame.addEventListener('load', () => {
            tab.loaded = true;
            tab.loading = false;
            tab.error = false;

            if (tab.loadingElement) {
                tab.loadingElement.hidden = true;
            }
        });

        frame.addEventListener('error', () => {
            tab.loading = false;
            tab.loaded = false;
            tab.error = true;

            if (tab.panel) {
                tab.panel.replaceChildren(
                    createErrorElement(
                        'Browser tidak dapat memuat halaman klaim.',
                        tab.tabId
                    )
                );
            }

            tab.frame = null;
            tab.loadingElement = null;
        });

        frame.src = makeFrameUrl(tab.url);

        return true;
    }

    function activateTab(tabId) {
        const tab = state.tabs.get(tabId);

        if (!tab || !getElements()) return false;

        const homeTab = root.querySelector('[data-claim-home]');

        if (homeTab) {
            homeTab.classList.remove('active');

            homeTab.setAttribute(
                'aria-selected',
                'false'
            );

            homeTab.tabIndex = -1;
        }

        state.tabs.forEach(item => {
            const isActive = item.tabId === tabId;

            if (item.tabElement) {
                item.tabElement.classList.toggle(
                    'active',
                    isActive
                );

                item.tabElement.setAttribute(
                    'aria-selected',
                    isActive ? 'true' : 'false'
                );

                item.tabElement.tabIndex = isActive ? 0 : -1;
            }

            if (item.panel) {
                item.panel.classList.toggle(
                    'active',
                    isActive
                );

                item.panel.hidden = !isActive;
            }
        });

        state.activeTabId = tabId;

        if (homePanel) {
            homePanel.hidden = true;
        }

        content.hidden = false;

        root.dataset.activeTabId = tabId;

        ensureTabFrame(tab);

        scrollTabIntoView(tab.tabElement);

        showClaimPatientIdentity(tab.metadata || {});

        return true;
    }

    function showHomePanel() {
        state.activeTabId = null;

        state.tabs.forEach(item => {
            if (item.tabElement) {
                item.tabElement.classList.remove('active');

                item.tabElement.setAttribute(
                    'aria-selected',
                    'false'
                );

                item.tabElement.tabIndex = -1;
            }

            if (item.panel) {
                item.panel.classList.remove('active');
                item.panel.hidden = true;
            }
        });

        const homeTab = root
            ? root.querySelector('[data-claim-home]')
            : document.querySelector('[data-claim-home]');

        if (homeTab) {
            homeTab.classList.add('active');

            homeTab.setAttribute(
                'aria-selected',
                'true'
            );

            homeTab.tabIndex = 0;
        }

        if (homePanel) {
            homePanel.hidden = false;
        }

        if (content) {
            content.hidden = true;
        }

        if (root) {
            root.removeAttribute('data-active-tab-id');
        }

        resetTopbar();

        return true;
    }

    function openClaim(
        nomor,
        type = 'smart_claim',
        metadata = {},
        options = {}
    ) {
        if (!getElements()) {
            console.error(
                '[ClaimWorkspace] Elemen workspace tidak ditemukan.'
            );

            return false;
        }

        const normalizedNomor = normalizeVisitId(nomor);

        if (!normalizedNomor) {
            console.error(
                '[ClaimWorkspace] Nomor kunjungan wajib diisi.'
            );

            return false;
        }

        try {
            const tab = createTab(
                normalizedNomor,
                normalizeClaimType(type),
                metadata,
                options
            );

            activateTab(tab.tabId);

            return true;

        } catch (error) {
            console.error('[ClaimWorkspace]', error);

            if (typeof window.iziToast !== 'undefined') {
                window.iziToast.error({
                    title: 'Pesan System!',
                    message: error.message ||
                        'Gagal membuka detail klaim.',
                    position: 'topRight',
                });
            } else {
                window.alert(
                    error.message ||
                    'Gagal membuka detail klaim.'
                );
            }

            return false;
        }
    }

    function closeTab(tabId) {
        const tab = state.tabs.get(tabId);

        if (!tab) return false;

        const wasActive = state.activeTabId === tabId;

        const tabsBefore = Array.from(state.tabs.values());

        const currentIndex = tabsBefore.findIndex(
            item => item.tabId === tabId
        );

        if (tab.frame) {
            tab.frame.src = 'about:blank';
        }

        if (tab.tabElement) {
            tab.tabElement.remove();
        }

        if (tab.panel) {
            tab.panel.remove();
        }

        state.tabs.delete(tabId);

        if (wasActive) {
            state.activeTabId = null;

            const remainingTabs = Array.from(
                state.tabs.values()
            );

            if (remainingTabs.length) {
                const nextTab = remainingTabs[
                    Math.min(
                        Math.max(currentIndex - 1, 0),
                        remainingTabs.length - 1
                    )
                ];

                activateTab(nextTab.tabId);
            } else {
                showHomePanel();
            }
        }

        return true;
    }

    function closeOtherTabs(tabId) {
        if (!state.tabs.has(tabId)) return false;

        Array.from(state.tabs.values()).forEach(tab => {
            if (tab.tabId !== tabId) {
                closeTab(tab.tabId);
            }
        });

        activateTab(tabId);

        return true;
    }

    function closeAllTabs() {
        // Tutup langsung tanpa activateTab() di tengah proses, agar tidak terjadi
        // iframe baru yang sempat loading saat semua tab sedang ditutup.
        Array.from(state.tabs.values()).forEach(tab => {
            if (tab.frame) {
                tab.frame.src = 'about:blank';
            }

            if (tab.tabElement) {
                tab.tabElement.remove();
            }

            if (tab.panel) {
                tab.panel.remove();
            }
        });

        state.tabs.clear();

        state.activeTabId = null;

        showHomePanel();

        return true;
    }

    function refreshTab(tabId) {
        const tab = state.tabs.get(tabId);

        if (!tab) return false;

        if (!tab.frame) {
            return activateTab(tabId);
            // Tab yang belum diload akan diload pada aktivasi.
        }

        tab.loading = true;
        tab.loaded = false;
        tab.error = false;

        if (tab.loadingElement) {
            tab.loadingElement.hidden = false;
        }

        tab.frame.src = makeFrameUrl(tab.url);

        return true;
    }

    function refreshActiveTab() {
        if (!state.activeTabId) return false;

        return refreshTab(state.activeTabId);
    }

    function retryTab(tabId) {
        const tab = state.tabs.get(tabId);

        if (!tab || !tab.panel) return false;

        tab.frame = null;
        tab.loadingElement = null;

        tab.loaded = false;
        tab.loading = false;
        tab.error = false;

        return ensureTabFrame(tab);
    }

    // Pada navigasi Livewire: DOM lama sudah/akan diganti.
    // Recreate tab headers and blank panels only; iframe deliberately stays unloaded.
    function initializeWorkspace() {
        if (!getElements()) return;

        // Ambil kondisi awal topbar sebelum workspace mengubah tampilannya.
        captureInitialTopbar();
                // Ambil kondisi awal topbar sebelum workspace mengubah tampilannya.
        captureInitialTopbar();

        root.dataset.workspaceInitialized = 'true';

        const tabDescriptors = Array.from(
            state.tabs.values()
        ).map(tab => ({
            tabId: tab.tabId || getTabId(tab.nomor, tab.type),

            nomor: normalizeVisitId(tab.nomor),

            type: normalizeClaimType(tab.type),

            metadata: {
                ...(tab.metadata || {}),
            },
        })).filter(item => item.nomor && item.type);

        tabScroll
            .querySelectorAll('.claim-tab[data-tab-id]')
            .forEach(element => element.remove());

        content.replaceChildren();

        // Reattach descriptor data to fresh DOM shells. Force lazy load state.
        state.tabs.clear();

        state.activeTabId = null;

        tabDescriptors.forEach(item => {
            const tab = {
                tabId: item.tabId,

                nomor: item.nomor,

                type: item.type,

                label: getTabLabel(item.metadata),

                url: getClaimUrl(
                    item.nomor,
                    item.type
                ),

                metadata: item.metadata,

                tabElement: null,
                panel: null,

                frame: null,
                loadingElement: null,

                loaded: false,
                loading: false,
                error: false,
            };

            state.tabs.set(tab.tabId, tab);

            renderTabShell(tab);
        });

        // Return to Smart Claim landing/home panel; do not automatically activate
        // previously active claim, and do not issue any iframe request.
        showHomePanel();

        // Jika ada permintaan membuka klaim dari halaman lain,
        // langsung buka workspace pasien tersebut.
        consumePendingClaim();
    }

    // ======================================================
    // CONSUME PENDING CLAIM WORKSPACE
    // ======================================================
    function consumePendingClaim() {

        // Pastikan halaman workspace sudah tersedia.
        if (!getElements()) {
            return;
        }

        let pending = null;

        try {
            const raw = sessionStorage.getItem(
                'sirmed.pendingClaimWorkspace'
            );

            if (!raw) {
                return;
            }

            pending = JSON.parse(raw);

        } catch (error) {

            console.error(
                '[ClaimWorkspace] Pending claim tidak valid:',
                error
            );

            sessionStorage.removeItem(
                'sirmed.pendingClaimWorkspace'
            );

            return;
        }

        if (
            !pending ||
            !pending.nomor
        ) {
            sessionStorage.removeItem(
                'sirmed.pendingClaimWorkspace'
            );

            return;
        }

        const nomor = pending.nomor;

        const type = pending.type || 'smart_claim';

        const metadata = pending.metadata || {};

        // Buka tab pasien.
        const success = openClaim(
            nomor,
            type,
            metadata
        );

        // Hapus hanya jika berhasil membuka workspace.
        if (success) {

            sessionStorage.removeItem(
                'sirmed.pendingClaimWorkspace'
            );

            console.info(
                '[ClaimWorkspace] Pending claim berhasil dibuka:',
                nomor,
                type
            );
        }
    }

    function handleDocumentClick(event) {
        const targetElement = event.target instanceof Element
            ? event.target
            : null;

        if (!targetElement) return;

        const row = targetElement.closest(
            '[data-claim-href], [data-href]'
        );

        if (!row) return;

        const interactive = targetElement.closest(
            'a, button, input, select, textarea, label, [role="button"], [contenteditable="true"], [data-bs-toggle], [data-action]'
        );

        if (
            interactive &&
            interactive !== row &&
            !row.hasAttribute('data-claim-href')
        ) {
            return;
        }

        if (
            event.ctrlKey ||
            event.metaKey ||
            event.shiftKey ||
            event.altKey ||
            event.button !== 0
        ) {
            return;
        }

        const href = row.dataset.claimHref ||
            row.dataset.href ||
            row.getAttribute('href');

        if (!href) return;

        let target;

        try {
            target = new URL(
                href,
                window.location.origin
            );
        } catch {
            return;
        }

        if (target.origin !== window.location.origin) return;

        const match = target.pathname.match(
            /^\/v3\/smartclaim\/([^/]+)(\/farmasi)?\/?$/
        );

        if (!match || event.defaultPrevented) return;

        event.preventDefault();

        let nomor;

        try {
            nomor = decodeURIComponent(match[1]);
        } catch {
            return;
        }

        const type = match[2]
            ? 'smart_claim_farmasi'
            : 'smart_claim';

        let metadata = {};

        const rawMetadata = row.dataset.claimMeta;

        if (rawMetadata) {
            try {
                metadata = JSON.parse(
                    decodeURIComponent(rawMetadata)
                );
            } catch (error) {
                console.warn(
                    '[ClaimWorkspace] Metadata row tidak dapat dibaca:',
                    error
                );
            }
        }

        openClaim(
            nomor,
            type,
            metadata
        );
    }

    // Public API
    window.ClaimWorkspace = {
        openClaim,

        activateTab,

        closeTab,

        closeOtherTabs,

        closeAllTabs,

        refreshTab,

        refreshActiveTab,

        retryTab,

        getActiveTab() {
            return state.activeTabId
                ? state.tabs.get(state.activeTabId) || null
                : null;
        },

        getTabs() {
            return Array.from(state.tabs.values());
        },

        getState() {
            return state;
        },

        getClaimUrl,
    };

    // =====================================================
    // SINKRONISASI METADATA SEP DARI IFRAME SMART CLAIM
    // =====================================================
    window.addEventListener('message', function (event) {
        // Hanya menerima pesan dari origin aplikasi SIRMED.
        if (event.origin !== window.location.origin) return;

        const data = event.data;

        if (!data || data.type !== 'CLAIM_METADATA_UPDATED') {
            return;
        }

        const nomor = normalizeVisitId(data.kunjungan);

        if (!nomor) return;

        if (!data.metadata || typeof data.metadata !== 'object') {
            return;
        }

        // Pesan ini khusus untuk detail Smart Claim utama.
        const tab = Array.from(state.tabs.values()).find(item =>
            item.nomor === nomor &&
            item.type === 'smart_claim'
        );

        if (!tab || !tab.frame) return;

        // Pastikan pesan benar-benar dikirim oleh iframe tab tersebut.
        if (event.source !== tab.frame.contentWindow) return;

        const noSEP = firstNonEmpty(
            data.metadata.noSEP,
            data.metadata.NOSEP,
            data.metadata.no_sep,
            data.metadata.nomorSEP,
            data.metadata.nomor_sep
        );

        const jenisKunjungan = firstNonEmpty(
            data.metadata.jenisKunjungan,
            data.metadata.JENIS_KUNJUNGAN,
            data.metadata.jenis_kunjungan
        );

        // Perbarui jika setidaknya SEP atau jenis kunjungan tersedia.
        if (!noSEP && !jenisKunjungan) return;

        // Metadata yang sudah ada tetap dipertahankan.
        tab.metadata = {
            ...(tab.metadata || {}),
            ...(noSEP ? { noSEP: noSEP } : {}),
            ...(jenisKunjungan ? { jenisKunjungan: jenisKunjungan } : {})
        };

        // Perbarui label dan badge tab.
        tab.label = getTabLabel(tab.metadata);

        if (tab.tabElement) {
            const labelElement = tab.tabElement.querySelector(
                '.claim-tab-label'
            );

            if (labelElement) {
                renderTabLabel(labelElement, tab.metadata);
            }

            tab.tabElement.title = tab.label;

            const closeButton = tab.tabElement.querySelector(
                '[data-close-claim-tab]'
            );

            if (closeButton) {
                closeButton.setAttribute(
                    'aria-label',
                    `Tutup ${tab.label}${getVisitKind(tab.metadata) ? ` (${getVisitKind(tab.metadata)})` : ''}`
                );
            }
        }

        // Perbarui title iframe jika tersedia.
        if (tab.frame) {
            tab.frame.title = tab.label;
        }

        // Jika tab sedang aktif, update identitas topbar.
        if (state.activeTabId === tab.tabId) {
            showClaimPatientIdentity(tab.metadata);
        }
    });

    // =====================================================
    // SINKRONISASI STATUS VERIFIKASI DARI IFRAME SMART CLAIM
    // =====================================================
    window.addEventListener('message', function (event) {
        if (event.origin !== window.location.origin) return;

        const data = event.data;

        if (!data || data.type !== 'CLAIM_VERIFICATION_UPDATED') return;

        // Ambil tab yang sedang aktif.
        const activeTab = window.ClaimWorkspace.getActiveTab();

        // Abaikan pesan dari iframe yang bukan tab aktif.
        if (!activeTab || String(activeTab.nomor) !== String(data.kunjungan)) {
            return;
        }

        // Normalisasi nilai boolean.
        const verified = [
            true, 1, '1', 'true', 'yes'
        ].includes(
            typeof data.verifikasi === 'string'
                ? data.verifikasi.toLowerCase()
                : data.verifikasi
        );

        // Update metadata tab aktif.
        activeTab.metadata = {
            ...(activeTab.metadata || {}),
            statusVerif: verified ? 1 : 0,
            statusKlaim: verified
                ? 'SUDAH DIVERIFIKASI'
                : 'BELUM DIVERIFIKASI',
            statusVerifText: verified
                ? 'SUDAH DIVERIFIKASI'
                : 'BELUM DIVERIFIKASI',
        };

        // Update badge topbar.
        const badge = document.getElementById('patientStatusTopbar');

        if (!badge) return;

        badge.textContent = verified
            ? 'SUDAH DIVERIFIKASI'
            : 'BELUM DIVERIFIKASI';

        badge.className = verified
            ? 'badge bg-success-subtle text-success border border-success-subtle'
            : 'badge bg-danger-subtle text-danger border border-danger-subtle';
    });

    if (document.readyState === 'loading') {
        document.addEventListener(
            'DOMContentLoaded',
            initializeWorkspace,
            { once: true }
        );
    } else {
        initializeWorkspace();
    }

    document.addEventListener(
        'livewire:navigated',
        initializeWorkspace
    );

    document.addEventListener(
        'click',
        handleDocumentClick
    );

    // Delegated workspace interactions. Listeners attach once and survive DOM replacement.
    document.addEventListener('click', function (event) {
        const targetElement = event.target instanceof Element
            ? event.target
            : null;

        if (!targetElement) return;

        const workspaceRoot = document.querySelector(
            SELECTORS.root
        );

        if (
            !workspaceRoot ||
            !workspaceRoot.contains(targetElement)
        ) {
            return;
        }

        const homeTab = targetElement.closest(
            '[data-claim-home]'
        );

        if (homeTab) {
            event.preventDefault();

            showHomePanel();

            return;
        }

        const closeButton = targetElement.closest(
            '[data-close-claim-tab]'
        );

        if (closeButton) {
            event.preventDefault();
            event.stopPropagation();

            closeTab(
                closeButton.dataset.closeClaimTab
            );

            return;
        }

        const retryButton = targetElement.closest(
            '[data-claim-retry]'
        );

        if (retryButton) {
            event.preventDefault();

            retryTab(
                retryButton.dataset.claimRetry
            );

            return;
        }

        const tabElement = targetElement.closest(
            '.claim-tab[data-tab-id]'
        );

        if (tabElement) {
            event.preventDefault();

            activateTab(
                tabElement.dataset.tabId
            );
        }
    });

    document.addEventListener('click', function (event) {
        const targetElement = event.target instanceof Element
            ? event.target
            : null;

        if (!targetElement) return;

        const refreshButton = targetElement.closest(
            SELECTORS.refresh
        );

        if (refreshButton) {
            event.preventDefault();

            refreshActiveTab();

            return;
        }

        const closeOtherButton = targetElement.closest(
            SELECTORS.closeOther
        );

        if (closeOtherButton) {
            event.preventDefault();

            if (state.activeTabId) {
                closeOtherTabs(
                    state.activeTabId
                );
            }

            return;
        }

        const closeAllButton = targetElement.closest(
            SELECTORS.closeAll
        );

        if (closeAllButton) {
            event.preventDefault();

            closeAllTabs();
        }
    });
})();
