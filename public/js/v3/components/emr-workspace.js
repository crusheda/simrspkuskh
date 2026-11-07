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
   - Sinkronisasi informasi pasien ke topbar.
   - Informasi identitas dan kunjungan mengikuti tab aktif.
   - Memulihkan seluruh metadata pasien saat workspace diinisialisasi ulang.

   Detail pasien tetap dirender oleh Laravel melalui SSR.
========================================================= */

(() => {
    'use strict';

    // Cegah bootstrap dan event listener terdaftar berulang kali.
    if (window.__SIRMED_EMR_WORKSPACE_BOOTSTRAP__) {
        return;
    }

    window.__SIRMED_EMR_WORKSPACE_BOOTSTRAP__ = true;

    // State hanya hidup selama dokumen browser ini berjalan.
    // Tidak menggunakan localStorage maupun sessionStorage.
    const workspaceState =
        window.__SIRMED_EMR_WORKSPACE_STATE__ ||
        (window.__SIRMED_EMR_WORKSPACE_STATE__ = {
            tabs: new Map(),
            activeNomor: 'home',
            tabSequence: 0
        });

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

        // =====================================================
        // TOPBAR ELEMENTS
        // =====================================================

        const pageSubTitle = document.getElementById('pageSubTitleTopbar');
        const pageTitle = document.getElementById('pageTitleTopbar');

        const patientBirth = document.getElementById('patientBirthTopbar');
        const patientRoom = document.getElementById('patientRoomTopbar');

        const patientTglMasuk = document.getElementById(
            'patientTglMasukTopbar'
        );

        const patientTglKeluar = document.getElementById(
            'patientTglKeluarTopbar'
        );

        const patientAsuransi = document.getElementById(
            'patientInsuranceTopbar'
        );

        const patientStatus = document.getElementById(
            'patientStatusTopbar'
        );

        // =====================================================
        // WORKSPACE BUTTONS
        // =====================================================

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

        // =====================================================
        // SIMPAN KONDISI AWAL TOPBAR
        // Digunakan ketika kembali ke halaman Home.
        // =====================================================

        const initialPageTitle =
            pageTitle?.textContent?.trim() ||
            'Data Kunjungan Pasien';

        const initialPageSubTitle =
            pageSubTitle?.textContent?.trim() ||
            'SISTEM INFORMASI RUMAH SAKIT';

        const initialTopbar = {
            birth: patientBirth?.textContent?.trim() ?? '',
            room: patientRoom?.textContent?.trim() ?? '',
            tglMasuk: patientTglMasuk?.textContent?.trim() ?? '',
            tglKeluar: patientTglKeluar?.textContent?.trim() ?? '',
            noAsuransi: patientAsuransi?.textContent?.trim() ?? '',
            status: patientStatus?.textContent?.trim() ?? '',
            statusClass: patientStatus?.className || 'topbar-status-badge'
        };

        const originalDocumentTitle = document.title;

        /*
         * Key Map:
         * NOMOR KUNJUNGAN
         *
         * Kunjungan yang sama tidak dibuatkan tab baru.
         * Pasien yang sama dengan kunjungan berbeda tetap
         * dapat memiliki beberapa tab.
         */

        // Map lokal berisi record beserta elemen DOM untuk halaman saat ini.
        const patientTabs = new Map();

        // Nomor urut dan tab aktif berasal dari state global.
        let tabSequence = workspaceState.tabSequence;
        let activeTabId = 'home';

        /* =====================================================
           UTILITAS
        ===================================================== */

        function normalizeVisitId(value) {
            return String(value ?? '').trim();
        }

        function normalizeText(value) {
            if (value === null || value === undefined) {
                return '';
            }

            return String(value).trim();
        }

        /**
         * Mengambil nilai pertama yang tidak kosong.
         * Nilai angka 0 tetap dianggap valid.
         */
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

        /**
         * Format Nomor Rekam Medis.
         */
        function formatRM(norm) {
            const value = String(norm ?? '').trim();

            return value
                ? value.padStart(8, '0')
                : '-';
        }

        /**
         * Format tanggal lahir.
         * Input: 1992-07-21
         * Output: 21-07-1992
         */
        function formatTanggalLahir(value) {
            const date = normalizeText(value);

            if (!date) {
                return '-';
            }

            const match = date.match(
                /^(\d{4})-(\d{2})-(\d{2})/
            );

            if (match) {
                return `${match[3]}-${match[2]}-${match[1]}`;
            }

            return date;
        }

        /**
         * Format tanggal masuk dan tanggal keluar.
         *
         * Input:
         * 2026-11-07 08:30:00
         *
         * Output:
         * 07-11-2026 08:30
         *
         * Jika hanya tanggal:
         * 2026-11-07
         *
         * Output:
         * 07-11-2026
         */
        function formatTanggalKunjungan(value) {
            const date = normalizeText(value);

            if (!date) {
                return '-';
            }

            const match = date.match(
                /^(\d{4})-(\d{2})-(\d{2})(?:[ T](\d{2}):(\d{2})(?::\d{2})?)?/
            );

            if (match) {
                const tanggal =
                    `${match[3]}-${match[2]}-${match[1]}`;

                if (match[4] && match[5]) {
                    return `${tanggal} ${match[4]}:${match[5]}`;
                }

                return tanggal;
            }

            return date;
        }

        /**
         * Menentukan label dan class status kunjungan.
         *
         * 1 = Sedang Dilayani
         * 2 = Selesai / Final
         * Selain itu = Batal Periksa
         */
        function getStatusKunjungan(value) {
            const status = normalizeText(value);

            if (!status) {
                return {
                    label: '-',
                    className: ''
                };
            }

            const normalizedStatus = status.toLowerCase();

            // Pasien sedang dilayani.
            if (
                normalizedStatus === '1' ||
                normalizedStatus.includes('dilayani') ||
                normalizedStatus === 'aktif'
            ) {
                return {
                    label: 'SEDANG DILAYANI',
                    className: 'status-active'
                };
            }

            // Kunjungan selesai.
            if (
                normalizedStatus === '2' ||
                normalizedStatus.includes('selesai') ||
                normalizedStatus.includes('final')
            ) {
                return {
                    label: 'SELESAI / FINAL',
                    className: 'status-complete'
                };
            }

            // Kunjungan batal.
            if (
                normalizedStatus === '0' ||
                normalizedStatus === '3' ||
                normalizedStatus.includes('batal')
            ) {
                return {
                    label: 'BATAL PERIKSA',
                    className: 'status-cancelled'
                };
            }

            return {
                label: status.toUpperCase(),
                className: ''
            };
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

        /**
         * Mengambil data pasien dari object pemanggil.
         *
         * Nama properti dibuat fleksibel untuk mengakomodasi
         * beberapa format data yang mungkin digunakan.
         */
        function normalizePatientData(patient = {}) {
            return {
                nomor: normalizeVisitId(patient.nomor),

                norm: firstNonEmpty(
                    patient.norm,
                    patient.rm,
                    patient.NORM
                ),

                nama: firstNonEmpty(
                    patient.nama,
                    patient.namaPasien,
                    patient.NAMAPASIEN
                ) || 'Pasien',

                tglLahir: firstNonEmpty(
                    patient.tglLahir,
                    patient.tanggalLahir,
                    patient.tgl_lahir,
                    patient.TGLLAHIRPASIEN
                ),

                namaRuangan: firstNonEmpty(
                    patient.namaRuangan,
                    patient.nama_ruangan,
                    patient.namaruangan,
                    patient.NAMARUANGAN,
                    patient.roomName,
                    patient.ruanganNama
                ),

                tglMasuk: firstNonEmpty(
                    patient.tglMasuk,
                    patient.tgl_masuk,
                    patient.tanggalMasuk,
                    patient.TGLMASUK,
                    patient.MASUK
                ),

                tglKeluar: firstNonEmpty(
                    patient.tglKeluar,
                    patient.tgl_keluar,
                    patient.tanggalKeluar,
                    patient.TGLKELUAR,
                    patient.KELUAR
                ),

                statusKunjungan: firstNonEmpty(
                    patient.statusKunjungan,
                    patient.status_kunjungan,
                    patient.STATUSKUNJUNGAN,
                    patient.status,
                    patient.STATUS
                ),

                url: normalizeText(patient.url)
            };
        }

        /**
         * Menyimpan metadata record ke workspaceState.
         *
         * Metadata tetap tersedia ketika Livewire melakukan
         * navigasi dan workspace diinisialisasi ulang.
         */
        function persistRecordMetadata(record) {
            if (!record) {
                return;
            }

            workspaceState.tabs.set(record.nomor, {
                nomor: record.nomor,
                norm: record.norm,
                nama: record.nama,
                tglLahir: record.tglLahir,
                namaRuangan: record.namaRuangan,
                tglMasuk: record.tglMasuk,
                tglKeluar: record.tglKeluar,
                statusKunjungan: record.statusKunjungan,
                url: record.url
            });
        }

        /**
         * Memperbarui label tab jika data pasien berubah.
         */
        function updatePatientTabLabel(record) {
            if (!record || !record.tab) {
                return;
            }

            const labelText =
                `RM. ${formatRM(record.norm)} · ${record.nama}`;

            const label = record.tab.querySelector(
                '.emr-tab-label'
            );

            if (label) {
                label.textContent = labelText;
            }

            record.tab.title =
                `${labelText} | Kunjungan ${record.nomor}`;

            const closeButton = record.tab.querySelector(
                '[data-close-tab]'
            );

            if (closeButton) {
                closeButton.setAttribute(
                    'aria-label',
                    `Tutup ${labelText}`
                );
            }

            if (record.frame) {
                record.frame.title =
                    `Rekam medis ${record.nama}, kunjungan ${record.nomor}`;
            }
        }

        /**
         * Memperbarui metadata record dengan data baru.
         *
         * Hanya nilai yang tersedia yang akan menggantikan
         * metadata sebelumnya.
         */
        function mergeRecordData(record, data = {}) {
            if (!record || !data) {
                return;
            }

            if (data.norm) {
                record.norm = data.norm;
            }

            if (data.nama) {
                record.nama = data.nama;
            }

            if (data.tglLahir) {
                record.tglLahir = data.tglLahir;
            }

            if (data.namaRuangan) {
                record.namaRuangan = data.namaRuangan;
            }

            if (data.tglMasuk) {
                record.tglMasuk = data.tglMasuk;
            }

            if (data.tglKeluar) {
                record.tglKeluar = data.tglKeluar;
            }

            if (data.statusKunjungan) {
                record.statusKunjungan = data.statusKunjungan;
            }

            updatePatientTabLabel(record);

            persistRecordMetadata(record);
        }

        /**
         * Sinkronisasi data dari window.EMRData di dalam iframe.
         *
         * Hanya dapat dilakukan jika iframe berasal dari origin
         * yang sama dengan halaman workspace.
         */
        function syncRecordFromFrame(record, frame) {
            if (!record || !frame) {
                return false;
            }

            try {
                const emrData = frame.contentWindow?.EMRData;

                if (!emrData || typeof emrData !== 'object') {
                    console.warn(
                        '[EMRWorkspace] window.EMRData tidak ditemukan di iframe.',
                        record.nomor
                    );

                    return false;
                }

                const patientData = {
                    norm: firstNonEmpty(
                        emrData.rm,
                        emrData.norm,
                        emrData.NORM
                    ),

                    nama: firstNonEmpty(
                        emrData.namaPasien,
                        emrData.nama,
                        emrData.NAMAPASIEN
                    ),

                    tglLahir: firstNonEmpty(
                        emrData.tglLahir,
                        emrData.tanggalLahir,
                        emrData.tgl_lahir,
                        emrData.TGLLAHIRPASIEN
                    ),

                    namaRuangan: firstNonEmpty(
                        emrData.namaRuangan,
                        emrData.nama_ruangan,
                        emrData.namaruangan,
                        emrData.NAMARUANGAN,
                        emrData.roomName,
                        emrData.ruanganNama
                    ),

                    tglMasuk: firstNonEmpty(
                        emrData.tgl_masuk,
                        emrData.tglMasuk,
                        emrData.tanggalMasuk,
                        emrData.MASUK
                    ),

                    tglKeluar: firstNonEmpty(
                        emrData.tgl_keluar,
                        emrData.tglKeluar,
                        emrData.tanggalKeluar,
                        emrData.KELUAR
                    ),

                    noAsuransi: firstNonEmpty(
                        emrData.noBpjs,
                        emrData.no_bpjs,
                        emrData.noAsuransi,
                        emrData.nomorAsuransi,
                        emrData.no_asuransi
                    ),

                    statusKunjungan: firstNonEmpty(
                        emrData.statusKunjungan,
                        emrData.status_kunjungan,
                        emrData.STATUSKUNJUNGAN,
                        emrData.status,
                        emrData.STATUS
                    )
                };

                mergeRecordData(record, patientData);

                // Perbarui topbar jika tab ini sedang aktif.
                if (activeTabId === record.tabId) {
                    updatePageTitle(record.tabId);
                }

                console.info(
                    '[EMRWorkspace] Data pasien berhasil disinkronkan:',
                    {
                        nomor: record.nomor,
                        nama: record.nama,
                        norm: record.norm,
                        tglLahir: record.tglLahir,
                        namaRuangan: record.namaRuangan,
                        tglMasuk: record.tglMasuk,
                        tglKeluar: record.tglKeluar,
                        statusKunjungan: record.statusKunjungan
                    }
                );

                return true;

            } catch (error) {
                console.error(
                    '[EMRWorkspace] Gagal membaca data pasien dari iframe:',
                    error
                );

                return false;
            }
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
           UPDATE TOPBAR
        ===================================================== */

        /**
         * Mengembalikan seluruh informasi topbar ke kondisi awal.
         *
         * Kondisi ini digunakan ketika tab Home aktif.
         */
        function resetTopbar() {

            if (pageSubTitle) {
                pageSubTitle.textContent = initialPageSubTitle;
            }

            if (pageTitle) {
                pageTitle.textContent = initialPageTitle;
            }

            if (patientBirth) {
                patientBirth.textContent = initialTopbar.birth;
            }

            if (patientRoom) {
                patientRoom.textContent = initialTopbar.room;
            }

            if (patientTglMasuk) {
                patientTglMasuk.textContent =
                    initialTopbar.tglMasuk;
            }

            if (patientTglKeluar) {
                patientTglKeluar.textContent =
                    initialTopbar.tglKeluar;
            }

            if (patientAsuransi) {
                patientAsuransi.textContent =
                    initialTopbar.noAsuransi;
            }

            if (patientStatus) {
                patientStatus.textContent =
                    initialTopbar.status;

                patientStatus.className =
                    initialTopbar.statusClass;
            }
        }

        /**
         * Memperbarui semua informasi topbar berdasarkan pasien aktif.
         */
        function updatePageTitle(tabId) {

            // Jika kembali ke halaman Home.
            if (tabId === 'home') {
                resetTopbar();

                document.title = originalDocumentTitle;

                return;
            }

            const record = findRecordByTabId(tabId);

            if (!record) {
                return;
            }

            // =================================================
            // IDENTITAS PASIEN
            // =================================================

            // Nomor RM.
            // if (pageSubTitle) {
            //     pageSubTitle.textContent =
            //         `RM. ${formatRM(record.norm)}`;
            // }
            if (pageSubTitle) {
                pageSubTitle.textContent =
                    ``;
            }

            // Nama pasien.
            if (pageTitle) {
                pageTitle.textContent =
                    record.nama || 'Rekam Medis Pasien';
            }

            // Tanggal lahir.
            if (patientBirth) {
                patientBirth.innerHTML =
                    `•&nbsp; Lahir ${formatTanggalLahir(record.tglLahir)}`;
            }

            // =================================================
            // INFORMASI KUNJUNGAN
            // =================================================

            // Nama ruangan.
            if (patientRoom) {
                patientRoom.textContent =
                    record.namaRuangan || '-';
            }

            // Tanggal masuk.
            if (patientTglMasuk) {
                patientTglMasuk.innerHTML =
                    `<i class="bi bi-calendar2-range me-1"></i> ${formatTanggalKunjungan(record.tglMasuk)}`;
            }

            // Tanggal keluar.
            if (patientTglKeluar) {
                patientTglKeluar.innerHTML =
                    record.tglKeluar ? `- ${formatTanggalKunjungan(record.tglKeluar)}`: '';
            }

            // Nomor asuransi.
            if (patientAsuransi) {
                patientAsuransi.innerHTML =
                    `<i class="bi bi-shield-check me-1"></i> ${formatTanggalKunjungan(record.noAsuransi)}`;
            }

            // =================================================
            // STATUS KUNJUNGAN
            // =================================================

            if (patientStatus) {
                const statusMap = {
                    '1': {
                        label: 'Sedang Dilayani',
                        className: 'badge bg-warning-subtle text-dark border border-warning-subtle'
                    },
                    '2': {
                        label: 'Selesai Dilayani',
                        className: 'badge bg-success-subtle text-dark border border-success-subtle'
                    },
                    '0': {
                        label: 'Batal Periksa',
                        className: 'badge bg-danger-subtle text-dark border border-danger-subtle'
                    }
                };

                const status = statusMap[String(record.statusKunjungan)] || {
                    label: '<i class="me-1 ri-loader-line ri-spin"></i> Memproses',
                    className: 'badge bg-dark-subtle text-dark border border-light-subtle'
                };

                // Set teks badge.
                patientStatus.innerHTML = status.label;

                // Set class badge.
                patientStatus.className = status.className;
            }

            // Judul browser.
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

            const activeRecord = findRecordByTabId(tabId);

            workspaceState.activeNomor =
                tabId === 'home'
                    ? 'home'
                    : (activeRecord?.nomor || 'home');

            // =================================================
            // STATUS TAB
            // =================================================

            tabBar.querySelectorAll('.emr-tab').forEach((tab) => {
                const isActive = tab.dataset.tabId === tabId;

                tab.classList.toggle('active', isActive);

                tab.setAttribute(
                    'aria-selected',
                    isActive ? 'true' : 'false'
                );

                tab.tabIndex = isActive ? 0 : -1;
            });

            // =================================================
            // STATUS PANEL
            // =================================================

            content.querySelectorAll(
                '.emr-workspace-panel'
            ).forEach((panel) => {
                const isActive = panel.dataset.panelId === tabId;

                panel.classList.toggle('active', isActive);
                panel.hidden = !isActive;
            });

            // =================================================
            // UPDATE TOPBAR
            // =================================================

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

            // Muat iframe hanya ketika tab pasien benar-benar diaktifkan.
            const record = findRecordByTabId(tabId);

            if (record && !record.loaded && !record.loading) {
                loadRecordFrame(record);
            }

            // Beri tahu komponen lain bahwa tab berpindah.
            document.dispatchEvent(
                new CustomEvent('emr:workspace:activated', {
                    detail: {
                        tabId,

                        nomor: record?.nomor ?? null,
                        norm: record?.norm ?? null,
                        nama: record?.nama ?? null,

                        tglLahir: record?.tglLahir ?? null,
                        namaRuangan: record?.namaRuangan ?? null,

                        tglMasuk: record?.tglMasuk ?? null,
                        tglKeluar: record?.tglKeluar ?? null,

                        statusKunjungan:
                            record?.statusKunjungan ?? null
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
                if (record.frame) {
                    record.frame.onload = null;
                    record.frame.onerror = null;
                    record.frame.src = 'about:blank';
                    record.frame.remove();
                }

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

            // =================================================
            // KETIKA IFRAME SELESAI MEMUAT HALAMAN
            // =================================================

            frame.onload = () => {
                didLoad = true;

                record.loaded = true;
                record.loading = false;

                loading.hidden = true;

                // =================================================
                // SINKRONISASI DATA PASIEN DARI IFRAME
                // =================================================

                syncRecordFromFrame(record, frame);

                // Pastikan topbar diperbarui apabila tab ini aktif.
                if (activeTabId === record.tabId) {
                    updatePageTitle(record.tabId);
                }

                // =================================================
                // EVENT CONTENT LOADED
                // =================================================

                document.dispatchEvent(
                    new CustomEvent(
                        'emr:workspace:content-loaded',
                        {
                            detail: {
                                nomor: record.nomor,
                                norm: record.norm,
                                nama: record.nama,

                                tglLahir: record.tglLahir,
                                namaRuangan: record.namaRuangan,

                                tglMasuk: record.tglMasuk,
                                tglKeluar: record.tglKeluar,

                                statusKunjungan:
                                    record.statusKunjungan,

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

            // Hapus permanen dari state memori untuk sesi halaman ini.
            workspaceState.tabs.delete(record.key);
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
               tglLahir: '1992-07-21',
               namaRuangan: 'RAWAT INAP KELAS I',
               tglMasuk: '2026-11-07 08:30:00',
               tglKeluar: null,
               statusKunjungan: 1,
               url: '/v3/emr/123456'
           });
        ===================================================== */

        function openPatient(patient = {}, options = {}) {

            const activateAfterOpen = options.activate !== false;

            const patientData = normalizePatientData(patient);

            const nomor = patientData.nomor;

            if (!nomor || !patientData.url) {
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

                // Sinkronkan metadata baru jika tersedia.
                mergeRecordData(existing, patientData);

                activate(existing.tabId);

                return true;
            }

            let frameUrl;

            try {
                frameUrl = makeFrameUrl(patientData.url);
            } catch (error) {
                console.error('[EMRWorkspace]', error);

                window.alert(
                    error.message ||
                    'URL detail pasien tidak valid.'
                );

                return false;
            }

            const tabId = `patient-${++tabSequence}`;

            workspaceState.tabSequence = tabSequence;

            const labelText =
                `RM. ${formatRM(patientData.norm)} · ${patientData.nama}`;

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

            const loading = createLoadingElement(patientData.nama);

            const frame = document.createElement('iframe');

            frame.className = 'emr-patient-frame';

            frame.title =
                `Rekam medis ${patientData.nama}, kunjungan ${nomor}`;

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

                norm: patientData.norm,
                nama: patientData.nama,

                tglLahir: patientData.tglLahir,

                namaRuangan: patientData.namaRuangan,

                tglMasuk: patientData.tglMasuk,
                tglKeluar: patientData.tglKeluar,

                statusKunjungan: patientData.statusKunjungan,

                url: frameUrl,

                tab,
                panel,
                frame,

                loaded: false,
                loading: false
            };

            patientTabs.set(nomor, record);

            // Simpan metadata tab di memori JavaScript global.
            persistRecordMetadata(record);

            // Aktifkan hanya jika diminta.
            if (activateAfterOpen) {
                activate(tabId);
            }

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

            /**
             * Mengambil informasi tab yang sedang aktif.
             */
            getActiveTab() {

                const record = findRecordByTabId(
                    activeTabId
                );

                if (!record) {
                    return {
                        tabId: 'home',

                        nomor: null,
                        norm: null,
                        nama: null,

                        tglLahir: null,
                        namaRuangan: null,

                        tglMasuk: null,
                        tglKeluar: null,

                        statusKunjungan: null
                    };
                }

                return {
                    tabId: record.tabId,

                    nomor: record.nomor,
                    norm: record.norm,
                    nama: record.nama,

                    tglLahir: record.tglLahir,
                    namaRuangan: record.namaRuangan,

                    tglMasuk: record.tglMasuk,
                    tglKeluar: record.tglKeluar,

                    statusKunjungan: record.statusKunjungan,

                    url: record.url,

                    active: true
                };
            },

            /**
             * Mengambil seluruh daftar tab pasien.
             */
            getTabs() {

                return Array.from(
                    patientTabs.values()
                ).map((record) => ({
                    tabId: record.tabId,

                    nomor: record.nomor,
                    norm: record.norm,
                    nama: record.nama,

                    tglLahir: record.tglLahir,
                    namaRuangan: record.namaRuangan,

                    tglMasuk: record.tglMasuk,
                    tglKeluar: record.tglKeluar,

                    statusKunjungan: record.statusKunjungan,

                    url: record.url,

                    loaded: record.loaded,
                    loading: record.loading,

                    active:
                        record.tabId === activeTabId
                }));
            }
        };

        // =====================================================
        // TAMPILKAN HALAMAN AWAL
        // =====================================================

        activate('home');

        // =====================================================
        // PULIHKAN TAB PASIEN
        // =====================================================

        // Bangun seluruh tab tanpa memuat iframe.
        for (const patient of workspaceState.tabs.values()) {
            openPatient(patient, { activate: false });
        }

        // Pulihkan tab yang terakhir aktif.
        const savedActiveNomor =
            workspaceState.activeNomor || 'home';

        if (
            savedActiveNomor !== 'home' &&
            patientTabs.has(savedActiveNomor)
        ) {
            activate(
                patientTabs.get(savedActiveNomor).tabId
            );
        } else {
            activate('home');
        }

        // =====================================================
        // BUKA KUNJUNGAN DARI NOTIFIKASI
        // =====================================================

        // Alur notifikasi yang sudah ada tetap dipertahankan.
        try {
            const pendingPatient = sessionStorage.getItem(
                'sirmed.pendingEmrPatient'
            );

            if (pendingPatient) {

                // Hapus terlebih dahulu agar tidak dibuka berulang.
                sessionStorage.removeItem(
                    'sirmed.pendingEmrPatient'
                );

                const patient = JSON.parse(pendingPatient);

                if (patient && patient.nomor && patient.url) {
                    openPatient(patient);
                }
            }

        } catch (error) {

            console.error(
                '[EMRWorkspace] Gagal membuka kunjungan tertunda:',
                error
            );

            sessionStorage.removeItem(
                'sirmed.pendingEmrPatient'
            );
        }

        console.info(
            '[EMRWorkspace] Workspace berhasil diinisialisasi.'
        );

    }

    /* =========================================================
       INISIALISASI
    ========================================================= */

    // Inisialisasi awal, termasuk ketika script dimuat di halaman lain.
    if (document.readyState === 'loading') {

        document.addEventListener(
            'DOMContentLoaded',
            initializeEMRWorkspace,
            { once: true }
        );

    } else {

        initializeEMRWorkspace();

    }

    // Saat Livewire mengganti halaman, bangun ulang workspace
    // menggunakan state global yang masih berada di memori.
    document.addEventListener(
        'livewire:navigated',
        initializeEMRWorkspace
    );

})();
