/*
|--------------------------------------------------------------------------
| SIRMED V3 — CLAIM WORKSPACE
|--------------------------------------------------------------------------
| Fitur:
| - Membuka Smart Claim dalam tab workspace.
| - Membuka Smart Claim Farmasi dalam tab workspace.
| - Satu tab untuk satu jenis klaim + nomor kunjungan.
| - Tidak reload halaman utama.
| - Header, sidebar, dan topbar tetap.
| - Berpindah antar-tab.
| - Menutup tab.
| - Menutup tab lainnya.
| - Menutup semua tab.
| - Reload konten tab aktif.
| - Loading overlay iframe.
| - Error handling iframe.
| - Membuka detail melalui klik tabel.
| - Mendukung pencarian SEP.
|--------------------------------------------------------------------------
*/

(() => {
    'use strict';

    if (window.__SIRMED_CLAIM_WORKSPACE_BOOTSTRAP__) {
        return;
    }

    window.__SIRMED_CLAIM_WORKSPACE_BOOTSTRAP__ = true;

    const state = window.__SIRMED_CLAIM_WORKSPACE_STATE__ ||
        (window.__SIRMED_CLAIM_WORKSPACE_STATE__ = {
            tabs: new Map(),
            activeTabId: null,
            sequence: 0,
        });

    const SELECTORS = {
        root: '#claimWorkspace',
        tabBar: '#claimTabBar',
        tabScroll: '#claimTabBarScroll',
        content: '#claimWorkspaceContent',
        closeOther: '#claimCloseOtherTabs',
        closeAll: '#claimCloseAllTabs',
        refresh: '#claimRefreshActiveTab',
    };

    let root;
    let tabBar;
    let tabScroll;
    let content;
    let homePanel;

    function getElements() {
        root = document.querySelector(SELECTORS.root);
        tabBar = document.querySelector(SELECTORS.tabBar);
        tabScroll = document.querySelector(SELECTORS.tabScroll);
        content = document.querySelector(SELECTORS.content);
        homePanel = document.querySelector('#claimHomePanel');

        return Boolean(root && tabBar && tabScroll && content && homePanel);
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

    function getClaimUrl(nomor, type = 'smart_claim') {
        const visitId = normalizeVisitId(nomor);

        if (!visitId) {
            throw new Error('Nomor kunjungan tidak boleh kosong.');
        }

        const normalizedType = normalizeClaimType(type);

        return normalizedType === 'smart_claim_farmasi'
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
            <span>
                Memuat ${escapeHtml(label)}...
            </span>
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

    function scrollTabIntoView(tabElement) {
        if (!tabElement || !tabScroll) {
            return;
        }

        tabElement.scrollIntoView({
            behavior: 'smooth',
            block: 'nearest',
            inline: 'nearest',
        });
    }

    function getTabId(nomor, type) {
        return `${normalizeClaimType(type)}::${normalizeVisitId(nomor)}`;
    }

    function createTab(nomor, type, metadata = {}) {
        const normalizedNomor = normalizeVisitId(nomor);
        const normalizedType = normalizeClaimType(type);

        const tabId = getTabId(normalizedNomor, normalizedType);

        if (state.tabs.has(tabId)) {
            const existing = state.tabs.get(tabId);

            existing.metadata = {
                ...existing.metadata,
                ...metadata,
            };

            activateTab(tabId);

            return existing;
        }

        const url = getClaimUrl(normalizedNomor, normalizedType);
        const frameUrl = makeFrameUrl(url);

        const label = metadata.label ||
            `${getClaimLabel(normalizedType)} - ${normalizedNomor}`;

        const sequence = ++state.sequence;

        const tabElement = document.createElement('div');

        tabElement.className = 'claim-tab';
        tabElement.id = `claimTab-${sequence}`;
        tabElement.dataset.tabId = tabId;
        tabElement.setAttribute('role', 'tab');
        tabElement.setAttribute('aria-selected', 'false');
        tabElement.setAttribute('aria-controls', `claimPanel-${sequence}`);
        tabElement.tabIndex = -1;
        tabElement.title = label;

        const icon = document.createElement('i');
        icon.className = getClaimIcon(normalizedType);
        icon.setAttribute('aria-hidden', 'true');

        const labelElement = document.createElement('span');
        labelElement.className = 'claim-tab-label';
        labelElement.textContent = label;

        const closeButton = document.createElement('button');

        closeButton.type = 'button';
        closeButton.className = 'claim-tab-close';
        closeButton.dataset.closeClaimTab = tabId;
        closeButton.title = 'Tutup tab';
        closeButton.setAttribute('aria-label', `Tutup ${label}`);

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
        panel.dataset.panelId = tabId;
        panel.setAttribute('role', 'tabpanel');
        panel.setAttribute('aria-labelledby', tabElement.id);
        panel.hidden = true;

        const loading = createLoadingElement(label);

        const frame = document.createElement('iframe');

        frame.className = 'claim-detail-frame';
        frame.title = label;
        frame.loading = 'eager';
        frame.referrerPolicy = 'same-origin';
        frame.setAttribute('frameborder', '0');
        frame.setAttribute(
            'allow',
            'clipboard-read; clipboard-write'
        );

        panel.append(
            loading,
            frame
        );

        content.appendChild(panel);

        const tab = {
            tabId,
            nomor: normalizedNomor,
            type: normalizedType,
            label,
            url,
            frameUrl,
            tabElement,
            panel,
            frame,
            loading,
            metadata: {
                ...metadata,
            },
            loaded: false,
            loading: true,
            error: false,
        };

        state.tabs.set(tabId, tab);

        frame.addEventListener('load', () => {
            tab.loaded = true;
            tab.loading = false;
            tab.error = false;

            if (tab.loadingElement) {
                tab.loadingElement.hidden = true;
            } else {
                loading.hidden = true;
            }
        });

        frame.addEventListener('error', () => {
            tab.loading = false;
            tab.error = true;

            panel.replaceChildren(
                createErrorElement(
                    'Browser tidak dapat memuat halaman klaim.',
                    tabId
                )
            );
        });

        tab.loadingElement = loading;

        frame.src = frameUrl;

        return tab;
    }

    function activateTab(tabId) {
        const tab = state.tabs.get(tabId);

        if (!tab) {
            return false;
        }

        // Refresh references in case Livewire replaced the workspace DOM.
        getElements();

        state.tabs.forEach((item) => {
            const isActive = item.tabId === tabId;

            item.tabElement.classList.toggle('active', isActive);
            item.panel.classList.toggle('active', isActive);

            item.tabElement.setAttribute(
                'aria-selected',
                isActive ? 'true' : 'false'
            );

            item.tabElement.tabIndex = isActive ? 0 : -1;
            item.panel.hidden = !isActive;
        });

        state.activeTabId = tabId;

        if (homePanel) {
            homePanel.hidden = true;
        }

        if (content) {
            content.hidden = false;
        }

        if (root) {
            root.dataset.activeTabId = tabId;
        }

        scrollTabIntoView(tab.tabElement);

        return true;
    }

    function openClaim(nomor, type = 'smart_claim', metadata = {}) {
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
                metadata
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

        if (!tab) {
            return false;
        }

        getElements();

        const wasActive = state.activeTabId === tabId;

        const tabElements = Array.from(state.tabs.values());
        const currentIndex = tabElements.findIndex(
            item => item.tabId === tabId
        );

        tab.frame.src = 'about:blank';

        tab.tabElement.remove();
        tab.panel.remove();

        state.tabs.delete(tabId);

        if (wasActive) {
            state.activeTabId = null;

            const remainingTabs = Array.from(state.tabs.values());

            if (remainingTabs.length > 0) {
                const nextTab = remainingTabs[
                    Math.min(
                        Math.max(currentIndex - 1, 0),
                        remainingTabs.length - 1
                    )
                ];

                activateTab(nextTab.tabId);
            } else {
                if (root) {
                    root.removeAttribute('data-active-tab-id');
                }

                if (homePanel) {
                    homePanel.hidden = false;
                }

                if (content) {
                    content.hidden = true;
                }
            }
        }

        return true;
    }

    function closeOtherTabs(tabId) {
        if (!state.tabs.has(tabId)) {
            return false;
        }

        const tabs = Array.from(state.tabs.values());

        tabs.forEach((tab) => {
            if (tab.tabId !== tabId) {
                closeTab(tab.tabId);
            }
        });

        activateTab(tabId);

        return true;
    }

    function closeAllTabs() {
        const tabs = Array.from(state.tabs.values());

        tabs.forEach((tab) => {
            closeTab(tab.tabId);
        });

        state.activeTabId = null;

        getElements();

        if (root) {
            root.removeAttribute('data-active-tab-id');
        }

        if (homePanel) {
            homePanel.hidden = false;
        }

        if (content) {
            content.hidden = true;
        }

        return true;
    }

    function refreshTab(tabId) {
        const tab = state.tabs.get(tabId);

        if (!tab) {
            return false;
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
        if (!state.activeTabId) {
            return false;
        }

        return refreshTab(state.activeTabId);
    }

    function retryTab(tabId) {
        const tab = state.tabs.get(tabId);

        if (!tab) {
            return false;
        }

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
        tab.loading = true;
        tab.loaded = false;
        tab.error = false;

        frame.addEventListener('load', () => {
            tab.loaded = true;
            tab.loading = false;
            tab.error = false;
            loading.hidden = true;
        });

        frame.addEventListener('error', () => {
            tab.loading = false;
            tab.error = true;

            tab.panel.replaceChildren(
                createErrorElement(
                    'Browser tidak dapat memuat halaman klaim.',
                    tabId
                )
            );
        });

        frame.src = makeFrameUrl(tab.url);

        return true;
    }

    function handleWorkspaceClick(event) {
        const targetElement = event.target instanceof Element
            ? event.target
            : event.target?.parentElement;

        if (!targetElement) {
            return;
        }

        const closeButton = targetElement.closest(
            '[data-close-claim-tab]'
        );

        if (closeButton) {
            event.preventDefault();
            event.stopPropagation();

            closeTab(closeButton.dataset.closeClaimTab);

            return;
        }

        const retryButton = targetElement.closest(
            '[data-claim-retry]'
        );

        if (retryButton) {
            event.preventDefault();

            retryTab(retryButton.dataset.claimRetry);

            return;
        }

        const tabElement = targetElement.closest(
            '.claim-tab[data-tab-id]'
        );

        if (tabElement) {
            event.preventDefault();

            activateTab(tabElement.dataset.tabId);
        }
    }

    function handleDocumentClick(event) {
        const targetElement = event.target instanceof Element
            ? event.target
            : event.target?.parentElement;

        if (!targetElement) {
            return;
        }

        const row = targetElement.closest(
            '[data-claim-href], [data-href]'
        );

        if (!row) {
            return;
        }

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

        if (!href) {
            return;
        }

        let target;

        try {
            target = new URL(href, window.location.origin);
        } catch {
            return;
        }

        if (target.origin !== window.location.origin) {
            return;
        }

        const match = target.pathname.match(
            /^\/v3\/smartclaim\/([^/]+)(\/farmasi)?\/?$/
        );

        if (!match) {
            return;
        }

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

        openClaim(nomor, type);
    }

    function initializeWorkspace() {
        if (!getElements()) {
            return;
        }

        if (root.dataset.workspaceInitialized === 'true') {
            return;
        }

        root.dataset.workspaceInitialized = 'true';

        if (homePanel) {
            homePanel.hidden = false;
        }

        if (content) {
            content.hidden = true;
        }

        // Delegasi pada document agar klik tetap tertangani saat Livewire
        // mengganti DOM workspace.
        if (!window.__SIRMED_CLAIM_WORKSPACE_EVENTS__) {
            window.__SIRMED_CLAIM_WORKSPACE_EVENTS__ = true;

            document.addEventListener('click', (event) => {
                getElements();
                handleWorkspaceClick(event);
                handleDocumentClick(event);
            });
        }

        const closeOtherButton = document.querySelector(
            SELECTORS.closeOther
        );

        if (closeOtherButton) {
            closeOtherButton.addEventListener('click', () => {
                if (state.activeTabId) {
                    closeOtherTabs(state.activeTabId);
                }
            });
        }

        const closeAllButton = document.querySelector(
            SELECTORS.closeAll
        );

        if (closeAllButton) {
            closeAllButton.addEventListener(
                'click',
                closeAllTabs
            );
        }

        const refreshButton = document.querySelector(
            SELECTORS.refresh
        );

        if (refreshButton) {
            refreshButton.addEventListener(
                'click',
                refreshActiveTab
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PUBLIC API
    |--------------------------------------------------------------------------
    */

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

    if (document.readyState === 'loading') {
        document.addEventListener(
            'DOMContentLoaded',
            initializeWorkspace,
            { once: true }
        );
    } else {
        initializeWorkspace();
    }
})();
