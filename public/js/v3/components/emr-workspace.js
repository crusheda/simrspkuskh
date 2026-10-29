/* =========================================================
   SIRMED V3 — EMR WORKSPACE
   Satu iframe untuk setiap nomor kunjungan pasien.

   Fitur:
   - Membuka detail pasien dalam tab baru.
   - Tidak memuat ulang halaman utama.
   - Sidebar, header, dan tabbar tetap.
   - Satu tab untuk setiap nomor kunjungan.
   - Berpindah antar-tab tanpa memuat ulang detail.
   - Menutup tab pasien.
   - Menutup tab lainnya.
   - Menutup semua tab pasien.
   - Memuat ulang detail pasien jika terjadi kegagalan.

   Detail pasien tetap dirender oleh Laravel melalui SSR.
========================================================= */

(() => {
    'use strict';

    function initializeEMRWorkspace() {
        const root = document.getElementById('emrWorkspace');

        // Tidak melakukan inisialisasi jika halaman bukan workspace.
        if (!root) {
            return;
        }

        // Mencegah inisialisasi ganda.
        if (root.dataset.workspaceInitialized === 'true') {
            return;
        }

        const tabBar = document.getElementById('emrTabBar');
        const content = document.getElementById('emrWorkspaceContent');
        const pageTitle = document.getElementById('pageTitleTopbar');

        const closeOtherButton = document.getElementById(
            'emrCloseOtherTabs'
        );

        const closeAllButton = document.getElementById(
            'emrCloseAllTabs'
        );

        const homeTab = document.getElementById('emrTab-home');
        const homePanel = document.getElementById('emrPanel-home');

        // Pastikan struktur HTML workspace lengkap.
        if (!tabBar || !content || !homeTab || !homePanel) {
            console.error(
                '[EMRWorkspace] Elemen workspace tidak lengkap.',
                {
                    tabBar: Boolean(tabBar),
                    content: Boolean(content),
                    homeTab: Boolean(homeTab),
                    homePanel: Boolean(homePanel)
                }
            );

            return;
        }

        root.dataset.workspaceInitialized = 'true';

        const initialPageTitle =
            pageTitle?.textContent?.trim() ||
            'Data Kunjungan Pasien';

        const originalDocumentTitle = document.title;

        /*
         * Key Map:
         * NOMOR KUNJUNGAN
         *
         * Kunjungan yang sama tidak dibuatkan tab baru.
         * Pasien yang sama dengan kunjungan berbeda tetap
         * dapat memiliki beberapa tab.
         */
        const patientTabs = new Map();

        let tabSequence = 0;
        let activeTabId = 'home';

        /* =====================================================
           UTILITAS
        ===================================================== */

        function normalizeVisitId(value) {
            return String(value ?? '').trim();
        }

        function formatRM(norm) {
            const value = String(norm ?? '').trim();

            return value
                ? value.padStart(8, '0')
                : '-';
        }

        /**
         * Menyiapkan URL detail untuk iframe.
         *
         * Parameter workspace=1 digunakan agar layout Laravel
         * merender konten detail tanpa sidebar/header utama.
         */
        function makeFrameUrl(url) {
            const target = new URL(
                String(url ?? ''),
                window.location.origin
            );

            // Hanya izinkan URL dari aplikasi SIRMED sendiri.
            if (target.origin !== window.location.origin) {
                throw new Error(
                    'URL detail pasien harus berasal dari aplikasi SIRMED.'
                );
            }

            target.searchParams.set('workspace', '1');

            return target.href;
        }

        function findTabElement(tabId) {
            return Array.from(
                tabBar.querySelectorAll('.emr-tab')
            ).find((element) => {
                return element.dataset.tabId === tabId;
            }) || null;
        }

        function findPanelElement(tabId) {
            return Array.from(
                content.querySelectorAll('.emr-workspace-panel')
            ).find((element) => {
                return element.dataset.panelId === tabId;
            }) || null;
        }

        function findRecordByTabId(tabId) {
            for (const record of patientTabs.values()) {
                if (record.tabId === tabId) {
                    return record;
                }
            }

            return null;
        }

        /* =====================================================
           UPDATE JUDUL HEADER
        ===================================================== */

        function updatePageTitle(tabId) {
            if (tabId === 'home') {
                if (pageTitle) {
                    pageTitle.textContent = initialPageTitle;
                }

                document.title = originalDocumentTitle;

                return;
            }

            const record = findRecordByTabId(tabId);

            if (!record) {
                return;
            }

            if (pageTitle) {
                pageTitle.textContent =
                    record.nama || 'Rekam Medis Pasien';
            }

            document.title =
                `${record.nama || 'Rekam Medis Pasien'} · Sirmed v3`;
        }

        /* =====================================================
           AKTIFKAN TAB
        ===================================================== */

        function activate(tabId) {
            const selectedTab = findTabElement(tabId);
            const selectedPanel = findPanelElement(tabId);

            if (!selectedTab || !selectedPanel) {
                console.warn(
                    `[EMRWorkspace] Tab/panel tidak ditemukan: ${tabId}`
                );

                return false;
            }

            activeTabId = tabId;

            // Status tab.
            tabBar.querySelectorAll('.emr-tab').forEach((tab) => {
                const isActive = tab.dataset.tabId === tabId;

                tab.classList.toggle('active', isActive);

                tab.setAttribute(
                    'aria-selected',
                    isActive ? 'true' : 'false'
                );

                tab.tabIndex = isActive ? 0 : -1;
            });

            // Status panel.
            content.querySelectorAll(
                '.emr-workspace-panel'
            ).forEach((panel) => {
                const isActive = panel.dataset.panelId === tabId;

                panel.classList.toggle('active', isActive);
                panel.hidden = !isActive;
            });

            updatePageTitle(tabId);

            // Pastikan tab aktif terlihat di tabbar.
            try {
                selectedTab.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest',
                    inline: 'nearest'
                });
            } catch (_) {
                // Tidak melakukan apa pun jika opsi tidak didukung.
            }

            const record = findRecordByTabId(tabId);

            // Beri tahu komponen lain bahwa tab berpindah.
            document.dispatchEvent(
                new CustomEvent('emr:workspace:activated', {
                    detail: {
                        tabId,
                        nomor: record?.nomor ?? null,
                        norm: record?.norm ?? null,
                        nama: record?.nama ?? null
                    }
                })
            );

            return true;
        }

        /* =====================================================
           TAMPILKAN PESAN ERROR
        ===================================================== */

        function renderFrameError(record, message) {
            if (!record || !record.panel) {
                return;
            }

            // Hapus loader dan iframe yang bermasalah.
            record.panel.innerHTML = '';

            record.frame = null;
            record.loaded = false;
            record.loading = false;

            const errorBox = document.createElement('div');
            errorBox.className = 'emr-panel-error';

            const icon = document.createElement('div');
            icon.className = 'error-icon';

            icon.innerHTML = `
                <i
                    class="bi bi-exclamation-triangle"
                    aria-hidden="true"
                ></i>
            `;

            const title = document.createElement('h5');

            title.textContent =
                'Detail rekam medis belum dapat dimuat';

            const description = document.createElement('p');

            description.textContent =
                message ||
                'Periksa route detail pasien dan koneksi server, kemudian coba lagi.';

            const retryButton = document.createElement('button');

            retryButton.type = 'button';
            retryButton.className = 'btn btn-primary btn-sm';

            retryButton.innerHTML = `
                <i
                    class="bi bi-arrow-clockwise me-1"
                    aria-hidden="true"
                ></i>
                Coba lagi
            `;

            retryButton.addEventListener('click', () => {
                loadRecordFrame(record, true);
            });

            errorBox.append(
                icon,
                title,
                description,
                retryButton
            );

            record.panel.appendChild(errorBox);
        }

        /* =====================================================
           LOADING PANEL
        ===================================================== */

        function createLoadingElement(nama) {
            const loading = document.createElement('div');

            loading.className = 'emr-panel-loading';
            loading.setAttribute('role', 'status');

            const spinner = document.createElement('span');

            spinner.className = 'spinner-border';
            spinner.setAttribute('aria-hidden', 'true');

            const loadingText = document.createElement('span');

            loadingText.textContent =
                `Memuat rekam medis ${nama || 'pasien'}...`;

            loading.append(spinner, loadingText);

            return loading;
        }

        /* =====================================================
           MEMUAT DETAIL PASIEN

           Halaman detail tetap dirender oleh Laravel.

           Bukan menggunakan AJAX untuk mengambil HTML lalu
           memasukkannya dengan innerHTML.
        ===================================================== */

        function loadRecordFrame(record, retry = false) {
            if (!record || !record.panel) {
                return;
            }

            // Cegah pemuatan ulang yang tidak disengaja.
            if (record.loading && !retry) {
                return;
            }

            // Jika retry, lepaskan iframe sebelumnya.
            if (retry) {
                record.frame?.remove();

                record.frame = null;
                record.loaded = false;
                record.loading = false;
            }

            // Hapus pesan error sebelumnya.
            record.panel
                .querySelector('.emr-panel-error')
                ?.remove();

            // Pastikan loader tersedia.
            let loading = record.panel.querySelector(
                '.emr-panel-loading'
            );

            if (!loading) {
                loading = createLoadingElement(record.nama);

                record.panel.appendChild(loading);
            }

            loading.hidden = false;

            // Buat iframe jika belum tersedia.
            let frame = record.panel.querySelector('iframe');

            if (!frame) {
                frame = document.createElement('iframe');

                frame.className = 'emr-patient-frame';

                frame.title =
                    `Rekam medis ${record.nama}, kunjungan ${record.nomor}`;

                frame.loading = 'eager';
                frame.referrerPolicy = 'same-origin';

                frame.setAttribute(
                    'allow',
                    'clipboard-read; clipboard-write'
                );

                frame.setAttribute(
                    'frameborder',
                    '0'
                );

                record.panel.appendChild(frame);
            }

            // Simpan referensi iframe terbaru.
            record.frame = frame;

            let didLoad = false;

            // Ketika iframe selesai memuat halaman.
            frame.onload = () => {
                didLoad = true;

                record.loaded = true;
                record.loading = false;

                loading.hidden = true;

                document.dispatchEvent(
                    new CustomEvent(
                        'emr:workspace:content-loaded',
                        {
                            detail: {
                                nomor: record.nomor,
                                norm: record.norm,
                                nama: record.nama,
                                frame
                            }
                        }
                    )
                );

                console.info(
                    '[EMRWorkspace] Detail berhasil dimuat:',
                    record.nomor
                );
            };

            frame.onerror = () => {
                loading.hidden = true;

                renderFrameError(
                    record,
                    'Browser gagal memuat halaman detail. Periksa alamat route, autentikasi, dan konfigurasi server.'
                );
            };

            record.loading = true;

            // Muat SSR detail pasien melalui route Laravel.
            frame.src = record.url;

            // Batas waktu untuk indikasi pemuatan terlalu lama.
            window.setTimeout(() => {
                if (
                    !didLoad &&
                    record.loading &&
                    record.frame === frame
                ) {
                    renderFrameError(
                        record,
                        'Pemuatan memerlukan waktu terlalu lama. Periksa koneksi atau route detail pasien.'
                    );
                }
            }, 30000);
        }

        /* =====================================================
           HAPUS RECORD PASIEN
        ===================================================== */

        function removeRecord(record) {
            if (!record) {
                return;
            }

            // Hentikan pemuatan iframe jika masih aktif.
            if (record.frame) {
                record.frame.onload = null;
                record.frame.onerror = null;

                record.frame.src = 'about:blank';
                record.frame.remove();
            }

            record.tab?.remove();
            record.panel?.remove();

            patientTabs.delete(record.key);
        }

        /* =====================================================
           TUTUP SATU TAB PASIEN
        ===================================================== */

        function closeTab(tabId) {
            if (!tabId || tabId === 'home') {
                return;
            }

            const record = findRecordByTabId(tabId);

            if (!record) {
                return;
            }

            const tabIds = Array.from(
                tabBar.querySelectorAll('.emr-tab')
            ).map((tab) => tab.dataset.tabId);

            const oldIndex = tabIds.indexOf(tabId);
            const wasActive = activeTabId === tabId;

            removeRecord(record);

            if (wasActive) {
                const remaining = Array.from(
                    tabBar.querySelectorAll('.emr-tab')
                ).map((tab) => tab.dataset.tabId);

                // Pilih tab sebelumnya jika ada.
                const targetIndex = Math.max(
                    0,
                    Math.min(oldIndex - 1, remaining.length - 1)
                );

                activate(remaining[targetIndex] || 'home');
            }
        }

        /* =====================================================
           TUTUP SEMUA TAB PASIEN
        ===================================================== */

        function closeAll() {
            Array.from(patientTabs.values()).forEach((record) => {
                removeRecord(record);
            });

            activate('home');
        }

        /* =====================================================
           TUTUP TAB LAINNYA
        ===================================================== */

        function closeOthers() {
            const keepTabId = activeTabId;

            Array.from(patientTabs.values()).forEach((record) => {
                if (record.tabId !== keepTabId) {
                    removeRecord(record);
                }
            });

            if (!findTabElement(keepTabId)) {
                activate('home');
            } else {
                activate(keepTabId);
            }
        }

        /* =====================================================
           BUKA PASIEN

           Contoh pemanggilan:

           EMRWorkspace.openPatient({
               nomor: '123456',
               norm: '12345',
               nama: 'Nama Pasien',
               url: '/v3/emr/123456'
           });
        ===================================================== */

        function openPatient(patient = {}) {
            const nomor = normalizeVisitId(patient.nomor);

            const norm = String(
                patient.norm ?? ''
            ).trim();

            const nama = String(
                patient.nama ?? ''
            ).trim() || 'Pasien';

            const rawUrl = String(
                patient.url ?? ''
            ).trim();

            if (!nomor || !rawUrl) {
                console.error(
                    '[EMRWorkspace] openPatient memerlukan nomor kunjungan dan URL.',
                    patient
                );

                return false;
            }

            /*
             * Jika kunjungan sudah terbuka, aktifkan tab lama.
             * Jangan membuat tab duplikat.
             */
            const existing = patientTabs.get(nomor);

            if (existing) {
                activate(existing.tabId);

                return true;
            }

            let frameUrl;

            try {
                frameUrl = makeFrameUrl(rawUrl);
            } catch (error) {
                console.error('[EMRWorkspace]', error);

                window.alert(
                    error.message ||
                    'URL detail pasien tidak valid.'
                );

                return false;
            }

            const tabId = `patient-${++tabSequence}`;

            const labelText =
                `RM. ${formatRM(norm)} · ${nama}`;

            const fullTitle =
                `${labelText} | Kunjungan ${nomor}`;

            /* ===============================================
               MEMBUAT TAB
            =============================================== */

            const tab = document.createElement('div');

            tab.className = 'emr-tab';
            tab.id = `emrTab-${tabId}`;
            tab.dataset.tabId = tabId;

            tab.setAttribute('role', 'tab');

            tab.setAttribute(
                'aria-controls',
                `emrPanel-${tabId}`
            );

            tab.setAttribute('aria-selected', 'false');

            tab.tabIndex = -1;
            tab.title = fullTitle;

            const icon = document.createElement('i');

            icon.className = 'bi bi-person-vcard';

            icon.setAttribute(
                'aria-hidden',
                'true'
            );

            const label = document.createElement('span');

            label.className = 'emr-tab-label';
            label.textContent = labelText;

            const closeButton = document.createElement('button');

            closeButton.type = 'button';
            closeButton.className = 'emr-tab-close';

            closeButton.dataset.closeTab = tabId;
            closeButton.title = 'Tutup tab pasien';

            closeButton.setAttribute(
                'aria-label',
                `Tutup ${labelText}`
            );

            closeButton.innerHTML = `
                <i
                    class="bi bi-x-lg"
                    aria-hidden="true"
                ></i>
            `;

            tab.append(
                icon,
                label,
                closeButton
            );

            tabBar.appendChild(tab);

            /* ===============================================
               MEMBUAT PANEL
            =============================================== */

            const panel = document.createElement('section');

            panel.className = 'emr-workspace-panel';
            panel.id = `emrPanel-${tabId}`;

            panel.dataset.panelId = tabId;

            panel.setAttribute('role', 'tabpanel');

            panel.setAttribute(
                'aria-labelledby',
                tab.id
            );

            panel.hidden = true;

            const loading = createLoadingElement(nama);

            const frame = document.createElement('iframe');

            frame.className = 'emr-patient-frame';

            frame.title =
                `Rekam medis ${nama}, kunjungan ${nomor}`;

            frame.loading = 'eager';
            frame.referrerPolicy = 'same-origin';

            frame.setAttribute(
                'allow',
                'clipboard-read; clipboard-write'
            );

            frame.setAttribute(
                'frameborder',
                '0'
            );

            panel.append(
                loading,
                frame
            );

            content.appendChild(panel);

            /* ===============================================
               SIMPAN DATA TAB
            =============================================== */

            const record = {
                key: nomor,
                tabId,

                nomor,
                norm,
                nama,

                url: frameUrl,

                tab,
                panel,
                frame,

                loaded: false,
                loading: false
            };

            patientTabs.set(nomor, record);

            // Aktifkan tab pasien yang baru dibuat.
            activate(tabId);

            // Mulai render detail pasien melalui server.
            loadRecordFrame(record);

            return true;
        }

        /* =====================================================
           EVENT KLIK TAB
        ===================================================== */

        tabBar.addEventListener('click', (event) => {
            const closeButton = event.target.closest(
                '[data-close-tab]'
            );

            if (closeButton) {
                event.preventDefault();
                event.stopPropagation();

                closeTab(
                    closeButton.dataset.closeTab
                );

                return;
            }

            const tab = event.target.closest('.emr-tab');

            if (tab) {
                activate(tab.dataset.tabId);
            }
        });

        /* =====================================================
           EVENT KEYBOARD TAB
        ===================================================== */

        tabBar.addEventListener('keydown', (event) => {
            if (event.target.closest('[data-close-tab]')) {
                return;
            }

            const tab = event.target.closest('.emr-tab');

            if (!tab) {
                return;
            }

            if (
                event.key === 'Enter' ||
                event.key === ' '
            ) {
                event.preventDefault();

                activate(tab.dataset.tabId);
            }
        });

        /* =====================================================
           EVENT TOMBOL AKSI TAB
        ===================================================== */

        closeOtherButton?.addEventListener(
            'click',
            closeOthers
        );

        closeAllButton?.addEventListener(
            'click',
            closeAll
        );

        /* =====================================================
           API GLOBAL WORKSPACE
        ===================================================== */

        window.EMRWorkspace = {
            openPatient,

            activate,

            closeTab,

            closeAll,

            closeOthers,

            getActiveTab() {
                const record = findRecordByTabId(
                    activeTabId
                );

                if (!record) {
                    return {
                        tabId: 'home',
                        nomor: null,
                        norm: null,
                        nama: null
                    };
                }

                return {
                    tabId: record.tabId,
                    nomor: record.nomor,
                    norm: record.norm,
                    nama: record.nama,
                    url: record.url,
                    active: true
                };
            },

            getTabs() {
                return Array.from(
                    patientTabs.values()
                ).map((record) => ({
                    tabId: record.tabId,

                    nomor: record.nomor,
                    norm: record.norm,
                    nama: record.nama,

                    url: record.url,

                    loaded: record.loaded,
                    loading: record.loading,

                    active:
                        record.tabId === activeTabId
                }));
            }
        };

        // Tampilkan halaman awal ketika workspace pertama kali aktif.
        activate('home');

        console.info(
            '[EMRWorkspace] Workspace berhasil diinisialisasi.'
        );
    }

    /* =========================================================
       INISIALISASI
    ========================================================= */

    if (document.readyState === 'loading') {
        document.addEventListener(
            'DOMContentLoaded',
            initializeEMRWorkspace,
            { once: true }
        );
    } else {
        initializeEMRWorkspace();
    }

})();
