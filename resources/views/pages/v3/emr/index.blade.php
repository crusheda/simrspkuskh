@section('title', 'Digital Medical Record')
@section('page-sub-title', 'SISTEM INFORMASI RUMAH SAKIT')
@section('page-title', 'Digital Medical Record')

<section>
    <div class="wire-content-emr">
        <div class="row g-3">
            <div class="col-xl-12">
                <div class="card rounded-0">
                    <div class="card-body p-0 pb-2">
                        <div class="row p-3">
                            <div class="col-md-3 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Jenis Perawatan</label>

                                    <select id="filter_rawat" class="form-control" onchange="EMRPage.getRuangan()">
                                        @foreach ($list['jenisPerawatan'] as $value => $label)
                                            <option
                                                value="{{ $value }}"
                                                {{ $value == 5 ? 'selected' : '' }}
                                            >
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Ruangan</label>
                                    <select id="filter_ruang" class="form-control" onchange="EMRPage.getDPJP()" disabled><option value="5" selected>...</option></select>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Status Kunjungan</label>
                                    <select class="form-control" id="filter_status">
                                        <option value="5">Semua Kunjungan</option>
                                        <option value="0">Batal Kunjungan</option>
                                        <option value="1" selected>Sedang Dilayani</option>
                                        <option value="2">Selesai Kunjungan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Rentang Tgl Kunjungan</label>
                                    <div class="input-group">
                                        <input type="text" id="filter_tgl" class="form-control flatpickr-input active" placeholder="Pilih Rentang Tanggal" readonly="readonly">
                                        <span class="input-group-text"><i class="feather icon-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 mb-3">
                                <div class="form-group">
                                    <label class="form-label" for="filter_dpjp">DPJP</label>
                                    <select class="form-select" id="filter_dpjp" disabled>
                                        <option value="5" selected>Memuat dokter...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="form-group">
                                    <label class="form-label">Jenis Penjamin</label>
                                    <select id="filter_penjamin" class="form-control" disabled><option value="0" selected>...</option></select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="d-sm-flex align-items-center justify-content-between">
                                    <button class="btn btn-outline-warning border-dashed waves-effect waves-light me-2" href="javascript: void(0);" id="clear_text" onclick="EMRPage.clearFilter()"><i class="ph-duotone ph-eraser me-1"></i> Kosongkan Filter</button>
                                    <div class="d-flex justify-content-end align-items-center gap-2">
                                        <span class="f-14 px-2">Total : <a id="jumlah_data">0 Data</a></span>
                                        <button class="btn btn-primary" id="tombol-tampilkan" onclick="EMRPage.filter()" disabled><i class="ph-duotone ph-sort-ascending me-1"></i> Refresh Table</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive border-top p-3" id="show_table" hidden>

                            {{-- ACTIVE FILTERS --}}
                            <div id="emr-active-filters" class="emr-active-filters mb-3"></div>

                            <table class="table mb-0 table-hover table-display" id="vantable">
                                <thead>
                                    <tr>
                                        <th class="kolom-antrian">ANTRIAN</th>
                                        <th>KUNJUNGAN PASIEN</th>
                                        <th class="text-start">WAKTU BERKUNJUNG</th>
                                    </tr>
                                </thead>
                                <tbody id="tampil-tbody">
                                    <tr style='font-size:13px'>
                                        <td colspan="5">
                                            <center>
                                                <div class="spinner-border spinner-border-sm" role="status"></div>
                                            </center>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- <div class="text-end">
                    <a href="ecom_product.html" class="btn btn-link-secondary d-inline-flex align-items-center"><i class="ti ti-chevron-left me-2"></i> Back to Shopping</a>
                </div> --}}
            </div>
        </div>
    </div>
</section>

<script>
    (() => {
        // Variabel di-reset setiap kali script EMR dieksekusi
        let tableEMR = null;

        window.EMRPage = {
            getRuangan,
            getDPJP,
            filter,
            clearFilter
        };

        $(document).ready(function() {
            if ("{{ $list['tte_pegawai'] }}" != true) {
                // kalau tidak ada tanda tangan pegawai
                Swal.fire({
                    title: `Tanda Tangan tidak ditemukan!`,
                    text: 'Silakan menambahkan tanda tangan pada halaman Profil sebelum melakukan pengisian Elektronik Medical Record.',
                    icon: `warning`,
                    theme: 'bootstrap-5',
                    showCloseButton: true,
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonText: `
                        <i class="fa fa-thumbs-up me-1"></i> Enrollment Sekarang
                    `,
                    confirmButtonAriaLabel: "Tanda Tangan",
                    cancelButtonText: `
                        <i class="fa fa-thumbs-down me-1"></i> Nanti
                    `,
                    cancelButtonAriaLabel: "Tutup",
                    // backdrop: `
                    //     rgba(0,0,0,0.6)
                    //     left top
                    //     no-repeat
                    // `,
                    allowOutsideClick: false, // supaya user fokus ke alert
                    allowEscapeKey: false,    // tidak bisa ditutup pakai tombol ESC
                }).then((result) => {
                    if (result.isConfirmed) {
                        // buka halaman profil
                        window.location.href = "{{ route('v3.profil') }}";
                    }
                });
            }

            // FLATPICKR DATE
            const today = new Date(); // Hari ini
            const fiveYearsAgo = new Date();
            fiveYearsAgo.setFullYear(today.getFullYear() - 5); // 5 tahun ke belakang
            $("#filter_tgl").flatpickr(
                {
                    // enableTime: true,
                    // dateFormat: "Y-m-d H:i",
                    mode: 'range',
                    minDate: fiveYearsAgo, // Mulai dari 5 tahun yang lalu
                    maxDate: today,        // Sampai hari ini
                    dateFormat: 'Y-m-d',
                    defaultDate: [today,today]
                }
            );

            // // ENTER TAMPILKAN
            // $('#filter_rawat, #filter_bulan, #filter_dpjp').on('keydown', function (e) {
            //     if (e.key === 'Enter') {
            //         e.preventDefault(); // mencegah aksi default
            //         filter(); // panggil fungsi filter
            //     }
            // });

            // filter();
            // alert(@json(auth()->user()->roles->first()?->name));
            // alert(@json(auth()->user()->roles->pluck('name')));
            initSelectDPJP();
            getRuangan();
            getPenjamin();

            // BUTTON FILTER READY
            $('#tombol-tampilkan').prop('disabled', false);

            // =====================================================
            // EMR WORKSPACE — SHORTCUT TAB CONTENT
            // =====================================================
            $('#vantable')
                .off('click.emrShortcut', '.emr-shortcut-item')
                .on(
                    'click.emrShortcut',
                    '.emr-shortcut-item',
                    function (event) {
                        event.preventDefault();
                        event.stopPropagation();

                        const targetTabId = this.dataset.emrTab;
                        const row = this.closest('tr.emr-patient-row');

                        if (!row || !targetTabId) {
                            console.error('Data pasien atau target tab tidak ditemukan.');
                            return;
                        }

                        // ==========================================
                        // DECODE DATA
                        // ==========================================
                        function decodeData(value) {
                            if (!value) return '';

                            try {
                                return decodeURIComponent(value);
                            } catch (error) {
                                return value;
                            }
                        }

                        // ==========================================
                        // DATA PASIEN DARI ROW
                        // ==========================================
                        const patient = {
                            nomor: row.dataset.nomor,
                            norm: row.dataset.norm,
                            nama: decodeData(row.dataset.nama),
                            tglLahir: row.dataset.tgllahir,
                            umur: row.dataset.umur,
                            namaRuangan: row.dataset.namaRuangan,
                            namaDokter: decodeData(row.dataset.namaDokter),
                            refIdAsuransi: row.dataset.refIdAsuransi,
                            namaAsuransi: row.dataset.namaAsuransi,
                            noAsuransi: decodeData(row.dataset.noAsuransi),
                            tglMasuk: decodeData(row.dataset.tglMasuk),
                            tglKeluar: decodeData(row.dataset.tglKeluar),
                            statusKunjungan: row.dataset.statusKunjungan,
                            url: row.dataset.href
                        };

                        // ==========================================
                        // HENTIK AUTO-OPEN PENGKAJIAN
                        // JIKA PASIEN DIBUKA MELALUI SHORTCUT FORM
                        // ==========================================
                        window.__SIRMED_EMR_ROW_PENGKAJIAN_VISITS__
                            ?.delete(String(patient.nomor));

                        // ==========================================
                        // VALIDASI NOMOR KUNJUNGAN
                        // ==========================================
                        if (!patient.nomor) {
                            console.error(
                                '[EMR] Nomor kunjungan tidak ditemukan.'
                            );
                            return;
                        }

                        // ==========================================
                        // VALIDASI EMR WORKSPACE
                        // ==========================================
                        if (
                            !window.EMRWorkspace ||
                            typeof window.EMRWorkspace.openPatient !== 'function'
                        ) {
                            console.error('EMRWorkspace belum terinisialisasi.');

                            Swal.fire({
                                icon: 'error',
                                title: 'Workspace belum tersedia',
                                text: 'EMR Workspace belum terinisialisasi.'
                            });

                            return;
                        }

                        // ==========================================
                        // OPEN TARGET CONTENT DALAM IFRAME PASIEN
                        // ==========================================
                        function openTargetContent(frame) {
                            if (!frame) {
                                console.warn('Iframe pasien tidak ditemukan.');
                                return;
                            }

                            try {
                                const frameWindow = frame.contentWindow;
                                const frameDocument = frame.contentDocument;

                                if (!frameWindow || !frameDocument) {
                                    console.warn('Dokumen iframe pasien belum tersedia.');
                                    return;
                                }

                                const targetButton =
                                    frameDocument.getElementById(targetTabId);

                                if (!targetButton) {
                                    console.warn(`Tab EMR tidak ditemukan: ${targetTabId}`);
                                    return;
                                }

                                // Jangan biarkan target tab disabled
                                targetButton.disabled = false;

                                // Aktifkan tab target
                                if (frameWindow.bootstrap?.Tab) {
                                    frameWindow.bootstrap.Tab
                                        .getOrCreateInstance(targetButton)
                                        .show();
                                } else {
                                    targetButton.click();
                                }

                                // ==========================================
                                // KHUSUS SHORTCUT FORM PENGKAJIAN
                                // ==========================================
                                if (targetTabId === 'tab-fpengkajian') {
                                    // Hanya shortcut Pengkajian Awal
                                    // yang boleh otomatis membuka submenu dan form
                                    function openPengkajianShortcut(attempt = 0) {

                                        const shortcutApi =
                                            frameWindow.EMRPengkajianShortcut;

                                        // ----------------------------------
                                        // API SUDAH SIAP
                                        // ----------------------------------
                                        if (
                                            shortcutApi &&
                                            typeof shortcutApi.openFirstAllowedForm === 'function'
                                        ) {
                                            const success =
                                                shortcutApi.openFirstAllowedForm();

                                            if (!success) {
                                                console.warn(
                                                    '[EMR Shortcut] Gagal membuka form pengkajian otomatis.'
                                                );
                                            }

                                            return;
                                        }

                                        // ----------------------------------
                                        // TUNGGU SCRIPT FORM SELESAI DI-LOAD
                                        // Maksimal 20 percobaan
                                        // ----------------------------------
                                        if (attempt < 20) {
                                            setTimeout(function () {
                                                openPengkajianShortcut(attempt + 1);
                                            }, 100);

                                            return;
                                        }

                                        console.error(
                                            '[EMR Shortcut] API pengkajian tidak tersedia pada iframe.'
                                        );
                                    }

                                    // Berikan waktu Bootstrap mengaktifkan tab
                                    setTimeout(function () {
                                        openPengkajianShortcut();
                                    }, 100);
                                }

                                console.info(
                                    '[EMR Shortcut] Tab berhasil dibuka:',
                                    targetTabId,
                                    patient.nomor
                                );

                            } catch (error) {
                                console.error(
                                    '[EMR Shortcut] Gagal membuka content:',
                                    error
                                );
                            }
                        }

                        // ==========================================
                        // EVENT CONTENT LOADED
                        // ==========================================
                        function onWorkspaceContentLoaded(event) {
                            const detail = event.detail || {};

                            // Pastikan event untuk pasien yang sesuai
                            if (
                                String(detail.nomor) !==
                                String(patient.nomor)
                            ) {
                                return;
                            }

                            document.removeEventListener(
                                'emr:workspace:content-loaded',
                                onWorkspaceContentLoaded
                            );

                            openTargetContent(detail.frame);
                        }

                        document.addEventListener(
                            'emr:workspace:content-loaded',
                            onWorkspaceContentLoaded
                        );

                        // ==========================================
                        // BUKA / AKTIFKAN WORKSPACE PASIEN
                        // ==========================================
                        window.EMRWorkspace.openPatient(patient);

                        // ==========================================
                        // CEK TAB EXISTING
                        // ==========================================
                        const existingTab = window.EMRWorkspace
                            .getTabs?.()
                            .find(function (tab) {
                                return String(tab.nomor) ===
                                    String(patient.nomor);
                            });

                        // ==========================================
                        // JIKA TAB SUDAH LOADED
                        // LANGSUNG BUKA TARGET CONTENT
                        // ==========================================
                        if (existingTab && existingTab.loaded) {

                            const panel = document.getElementById(
                                `emrPanel-${existingTab.tabId}`
                            );

                            const frame = panel?.querySelector(
                                'iframe.emr-patient-frame'
                            );

                            if (frame) {
                                document.removeEventListener(
                                    'emr:workspace:content-loaded',
                                    onWorkspaceContentLoaded
                                );

                                requestAnimationFrame(function () {
                                    openTargetContent(frame);
                                });
                            }
                        }
                    }
                );

            // =====================================================
            // OPEN EMR VISIT DIRECTLY TO TAB PENGKAJIAN
            // TANPA AUTO OPEN MENU / FORM PENGKAJIAN
            // =====================================================
            // $('#vantable')
            //     .off('click.emrVisitDirect', '[data-open-kunjungan]')
            //     .on(
            //         'click.emrVisitDirect',
            //         '[data-open-kunjungan]',
            //         function (event) {
            //             event.preventDefault();
            //             event.stopPropagation();

            //             let patient = {};

            //             try {
            //                 patient = JSON.parse(
            //                     decodeURIComponent(
            //                         this.dataset.kunjunganPatient || '{}'
            //                     )
            //                 );
            //             } catch (error) {
            //                 console.error(
            //                     'Gagal membaca data kunjungan pasien:',
            //                     error
            //                 );
            //                 return;
            //             }

            //             if (!patient.nomor || !patient.url) {
            //                 console.error('Data kunjungan tidak lengkap:', patient);
            //                 return;
            //             }

            //             if (
            //                 !window.EMRWorkspace ||
            //                 typeof window.EMRWorkspace.openPatient !== 'function'
            //             ) {
            //                 console.error('EMRWorkspace belum terinisialisasi.');
            //                 return;
            //             }

            //             const targetTabId = 'tab-fpengkajian';

            //             // ==========================================
            //             // BUKA / AKTIFKAN WORKSPACE PASIEN
            //             // ==========================================
            //             window.EMRWorkspace.openPatient(patient);

            //             // ==========================================
            //             // AKTIFKAN TAB PENGKAJIAN SAJA
            //             // TIDAK MEMANGGIL MENU / loadForm()
            //             // ==========================================
            //             function activatePengkajian(frame, attempt = 0) {
            //                 if (!frame) return;

            //                 const frameDocument = frame.contentDocument;
            //                 const frameWindow = frame.contentWindow;

            //                 if (!frameDocument || !frameWindow) {
            //                     if (attempt < 30) {
            //                         setTimeout(() => {
            //                             activatePengkajian(frame, attempt + 1);
            //                         }, 100);
            //                     }
            //                     return;
            //                 }

            //                 const targetButton =
            //                     frameDocument.getElementById(targetTabId);

            //                 if (!targetButton) {
            //                     if (attempt < 30) {
            //                         setTimeout(() => {
            //                             activatePengkajian(frame, attempt + 1);
            //                         }, 100);
            //                     }
            //                     return;
            //                 }

            //                 if (targetButton.disabled) {
            //                     targetButton.disabled = false;
            //                 }

            //                 if (frameWindow.bootstrap?.Tab) {
            //                     frameWindow.bootstrap.Tab
            //                         .getOrCreateInstance(targetButton)
            //                         .show();
            //                 } else {
            //                     targetButton.click();
            //                 }

            //                 console.info(
            //                     '[EMR Visit] Form Pengkajian dibuka tanpa membuka menu pengkajian:',
            //                     patient.nomor
            //                 );
            //             }

            //             // ==========================================
            //             // CEK WORKSPACE YANG SUDAH TERBUKA
            //             // ==========================================
            //             const existingTab = window.EMRWorkspace
            //                 .getTabs?.()
            //                 .find(tab =>
            //                     String(tab.nomor) === String(patient.nomor)
            //                 );

            //             if (existingTab && existingTab.loaded) {
            //                 const panel = document.getElementById(
            //                     `emrPanel-${existingTab.tabId}`
            //                 );

            //                 const frame = panel?.querySelector(
            //                     'iframe.emr-patient-frame'
            //                 );

            //                 if (frame) {
            //                     requestAnimationFrame(() => {
            //                         activatePengkajian(frame);
            //                     });
            //                 }
            //             } else {
            //                 // ======================================
            //                 // WORKSPACE BARU / SEDANG LOAD
            //                 // ======================================
            //                 const onContentLoaded = function (event) {
            //                     const detail = event.detail || {};

            //                     if (
            //                         String(detail.nomor) !==
            //                         String(patient.nomor)
            //                     ) {
            //                         return;
            //                     }

            //                     document.removeEventListener(
            //                         'emr:workspace:content-loaded',
            //                         onContentLoaded
            //                     );

            //                     activatePengkajian(detail.frame);
            //                 };

            //                 document.addEventListener(
            //                     'emr:workspace:content-loaded',
            //                     onContentLoaded
            //                 );
            //             }
            //         }
            //     );

            /* =========================================================
            EMR ROW — AUTO OPEN PENGKAJIAN SAAT RESTORE

            State kunjungan dipertahankan di window selama navigasi
            Livewire, bukan sessionStorage.

            Pasien yang dibuka melalui emr-patient-row akan selalu
            diarahkan kembali ke Form Pengkajian ketika halaman
            Medical Record dikunjungi kembali.
            ========================================================= */

            (() => {
                if (window.__SIRMED_EMR_ROW_PENGKAJIAN_INIT__) {
                    return;
                }

                window.__SIRMED_EMR_ROW_PENGKAJIAN_INIT__ = true;

                // Daftar nomor kunjungan yang harus kembali ke Pengkajian
                window.__SIRMED_EMR_ROW_PENGKAJIAN_VISITS__ =
                    window.__SIRMED_EMR_ROW_PENGKAJIAN_VISITS__ ||
                    new Set();


                /* =====================================================
                OPEN PENGKAJIAN DI IFRAME PASIEN
                ===================================================== */
                // window.__SIRMED_EMR_ROW_OPEN_PENGKAJIAN__ =
                //     function (frame, attempt = 0) {

                //         const maxAttempt = 30;

                //         if (!frame) return false;

                //         const frameWindow = frame.contentWindow;
                //         const frameDocument = frame.contentDocument;

                //         if (!frameWindow || !frameDocument) {
                //             if (attempt < maxAttempt) {
                //                 setTimeout(() => {
                //                     window.__SIRMED_EMR_ROW_OPEN_PENGKAJIAN__(
                //                         frame,
                //                         attempt + 1
                //                     );
                //                 }, 100);
                //             }

                //             return false;
                //         }

                //         const tabButton = frameDocument.getElementById(
                //             'tab-fpengkajian'
                //         );

                //         if (!tabButton) {
                //             if (attempt < maxAttempt) {
                //                 setTimeout(() => {
                //                     window.__SIRMED_EMR_ROW_OPEN_PENGKAJIAN__(
                //                         frame,
                //                         attempt + 1
                //                     );
                //                 }, 100);
                //             }

                //             return false;
                //         }

                //         // Aktifkan tab Pengkajian
                //         tabButton.disabled = false;

                //         if (frameWindow.bootstrap?.Tab) {
                //             frameWindow.bootstrap.Tab
                //                 .getOrCreateInstance(tabButton)
                //                 .show();
                //         } else {
                //             tabButton.click();
                //         }

                //         // Buka form sesuai izin controller detailV3
                //         function openFirstAllowedForm(formAttempt = 0) {

                //             const shortcutApi =
                //                 frameWindow.EMRPengkajianShortcut;

                //             if (
                //                 shortcutApi &&
                //                 typeof shortcutApi.openFirstAllowedForm === 'function'
                //             ) {
                //                 shortcutApi.openFirstAllowedForm();
                //                 return;
                //             }

                //             if (formAttempt < maxAttempt) {
                //                 setTimeout(() => {
                //                     openFirstAllowedForm(formAttempt + 1);
                //                 }, 100);
                //             } else {
                //                 console.error(
                //                     '[EMR Row Restore] EMRPengkajianShortcut tidak tersedia.'
                //                 );
                //             }
                //         }

                //         setTimeout(() => {
                //             openFirstAllowedForm();
                //         }, 150);

                //         return true;
                //     };
                window.__SIRMED_EMR_ROW_OPEN_PENGKAJIAN__ = function (frame, attempt = 0) {
                    if (!frame || attempt >= 30) return;

                    const frameWindow = frame.contentWindow;
                    const frameDocument = frameWindow?.document;

                    if (!frameWindow || !frameDocument) {
                        setTimeout(() => {
                            window.__SIRMED_EMR_ROW_OPEN_PENGKAJIAN__(frame, attempt + 1);
                        }, 100);
                        return;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | KHUSUS WORKSPACE RUANGAN PROGRAM REHAB MEDIK
                    |--------------------------------------------------------------------------
                    | Syarat: tab-fmrehab tersedia di workspace EMR pasien.
                    |
                    | Alur:
                    | 1. Buka tab utama Program Rehab Medik (tab-fmrehab)
                    | 2. Buka tab internal Formulir Rawat Jalan (tab-frjkfr)
                    | 3. Tidak menjalankan Form Pengkajian Awal
                    |--------------------------------------------------------------------------
                    */

                    const tabRehab = frameDocument.getElementById('tab-fmrehab');

                    if (tabRehab) {
                        const bootstrap = frameWindow.bootstrap;

                        // Aktifkan tab utama Program Rehab Medik
                        if (bootstrap?.Tab) {
                            bootstrap.Tab.getOrCreateInstance(tabRehab).show();
                        } else {
                            tabRehab.click();
                        }

                        // Tunggu tab Program Rehab Medik tampil
                        setTimeout(() => {
                            const tabKfr = frameDocument.getElementById('tab-frjkfr');

                            if (!tabKfr) return;

                            // Simpan status disabled sebelumnya
                            const sebelumnyaDisabled = tabKfr.disabled;

                            // Aktifkan sementara agar Bootstrap bisa membuka tab
                            tabKfr.disabled = false;
                            tabKfr.removeAttribute('disabled');

                            if (bootstrap?.Tab) {
                                bootstrap.Tab.getOrCreateInstance(tabKfr).show();
                            } else {
                                tabKfr.click();
                            }

                            // Kembalikan status disabled setelah tab berhasil dipilih
                            setTimeout(() => {
                                tabKfr.disabled = sebelumnyaDisabled;

                                if (sebelumnyaDisabled) {
                                    tabKfr.setAttribute('disabled', '');
                                }
                            }, 100);

                        }, 150);

                        // STOP — jangan jalankan Form Pengkajian Awal
                        return;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | DEFAULT RUANGAN LAIN
                    |--------------------------------------------------------------------------
                    | Jika tab-fmrehab tidak ditemukan, tetap jalankan mekanisme
                    | Form Pengkajian Awal seperti sebelumnya.
                    |--------------------------------------------------------------------------
                    */
                    const tabPengkajian = frameDocument.getElementById('tab-fpengkajian');

                    if (!tabPengkajian) {
                        window.__SIRMED_EMR_ROW_OPEN_PENGKAJIAN__(
                            frame,
                            attempt + 1
                        );
                        return;
                    }

                    // Cek status tab sebelum dibuka
                    const pengkajianSudahAktif = tabPengkajian.classList.contains('active');

                    // Buka tab Pengkajian
                    if (frameWindow.bootstrap?.Tab) {
                        frameWindow.bootstrap.Tab
                            .getOrCreateInstance(tabPengkajian)
                            .show();
                    } else {
                        tabPengkajian.click();
                    }

                    // Buka form pengkajian
                    const shortcut = frameWindow.EMRPengkajianShortcut;

                    if (shortcut?.openFirstAllowedForm) {
                        shortcut.openFirstAllowedForm();
                    }

                    // Jika tab sudah aktif sebelumnya, .show() tidak menghasilkan event shown.bs.tab.
                    // Trigger event secara manual hanya untuk kondisi tersebut.
                    if (pengkajianSudahAktif) {
                        triggerCekKunjunganRowEMR(frame);
                    }
                };

                function triggerCekKunjunganRowEMR(frame, attempt = 0) {
                    const maxAttempt = 30;

                    if (!frame?.contentWindow || !frame.contentDocument) {
                        if (attempt < maxAttempt) {
                            setTimeout(() => {
                                triggerCekKunjunganRowEMR(frame, attempt + 1);
                            }, 100);
                        }

                        return;
                    }

                    const frameWindow = frame.contentWindow;
                    const frameDocument = frame.contentDocument;

                    // Pastikan halaman EMR detail sudah siap
                    if (!frameDocument.getElementById('tab-fpengkajian')) {
                        if (attempt < maxAttempt) {
                            setTimeout(() => {
                                triggerCekKunjunganRowEMR(frame, attempt + 1);
                            }, 100);
                        }

                        return;
                    }

                    // Trigger khusus dari klik row Index EMR
                    frameWindow.dispatchEvent(
                        new CustomEvent('emr:cek-kunjungan-row')
                    );
                }

                /* =====================================================
                CONTENT LOADED — UNTUK PASIEN YANG DITANDAI
                ===================================================== */

                document.addEventListener(
                    'emr:workspace:content-loaded',
                    function (event) {

                        const detail = event.detail || {};
                        const nomor = String(detail.nomor ?? '');

                        if (!nomor) return;

                        const visits =
                            window.__SIRMED_EMR_ROW_PENGKAJIAN_VISITS__;

                        if (!visits.has(nomor)) {
                            return;
                        }

                        // Pastikan hanya tab pasien aktif yang dibuka
                        const activeTab =
                            window.EMRWorkspace?.getActiveTab?.();

                        if (
                            activeTab &&
                            activeTab.nomor &&
                            String(activeTab.nomor) !== nomor
                        ) {
                            return;
                        }

                        window.__SIRMED_EMR_ROW_OPEN_PENGKAJIAN__(
                            detail.frame
                        );
                    }
                );


                /* =====================================================
                LIVEWIRE NAVIGATION — KEMBALI KE MEDICAL RECORD
                ===================================================== */

                document.addEventListener(
                    'livewire:navigated',
                    function () {

                        let attempt = 0;
                        const maxAttempt = 30;

                        function restorePengkajian() {

                            attempt++;

                            const workspace =
                                document.getElementById('emrWorkspace');

                            const emrApi = window.EMRWorkspace;

                            if (!workspace || !emrApi) {
                                if (attempt < maxAttempt) {
                                    setTimeout(restorePengkajian, 100);
                                }

                                return;
                            }

                            const activeTab = emrApi.getActiveTab?.();

                            if (
                                !activeTab ||
                                !activeTab.nomor ||
                                activeTab.tabId === 'home'
                            ) {
                                return;
                            }

                            const nomor = String(activeTab.nomor);

                            // Hanya auto-open pasien yang berasal dari row EMR
                            const visits =
                                window.__SIRMED_EMR_ROW_PENGKAJIAN_VISITS__;

                            if (!visits.has(nomor)) {
                                return;
                            }

                            const panel = document.getElementById(
                                `emrPanel-${activeTab.tabId}`
                            );

                            const frame = panel?.querySelector(
                                'iframe.emr-patient-frame'
                            );

                            if (frame) {
                                window.__SIRMED_EMR_ROW_OPEN_PENGKAJIAN__(
                                    frame
                                );
                            }

                            // Jika iframe belum tersedia, nantinya event
                            // emr:workspace:content-loaded akan menangani.
                        }

                        setTimeout(restorePengkajian, 200);
                    }
                );

            })();

            // =====================================================
            // EMR WORKSPACE — OPEN PATIENT TAB
            // BUKA WORKSPACE + FORM PENGKAJIAN + AUTO PILIH FORM CONTROLLER
            // =====================================================
            $(document)
                .off('click.emrPatientPengkajian', '.emr-patient-row')
                .on(
                    'click.emrPatientPengkajian',
                    '.emr-patient-row',
                    function (e) {

                        e.preventDefault();
                        e.stopPropagation();

                        // Abaikan klik elemen interaktif dalam row
                        if (
                            $(e.target).closest(
                                'button, a, input, select, textarea, .dropdown, .dropdown-menu'
                            ).length
                        ) {
                            return;
                        }

                        const row = this;

                        const patient = {
                            nomor: row.dataset.nomor,
                            norm: row.dataset.norm,
                            nama: decodeURIComponent(row.dataset.nama || ''),
                            tglLahir: row.dataset.tgllahir,
                            umur: row.dataset.umur,
                            namaRuangan: row.dataset.namaRuangan,
                            namaDokter: decodeURIComponent(row.dataset.namaDokter || ''),
                            tglMasuk: decodeURIComponent(row.dataset.tglMasuk || ''),
                            tglKeluar: decodeURIComponent(row.dataset.tglKeluar || ''),
                            idAsuransi: row.dataset.refIdAsuransi,
                            namaAsuransi: row.dataset.namaAsuransi,
                            noAsuransi: decodeURIComponent(row.dataset.noAsuransi || ''),
                            statusKunjungan: row.dataset.statusKunjungan,
                            url: row.dataset.href
                        };

                        if (!patient.nomor) {
                            console.error(
                                '[EMR] Nomor kunjungan tidak ditemukan.'
                            );
                            return;
                        }

                        if (
                            !window.EMRWorkspace ||
                            typeof window.EMRWorkspace.openPatient !== 'function'
                        ) {
                            console.error(
                                '[EMR] EMRWorkspace belum terinisialisasi.'
                            );

                            Swal.fire({
                                icon: 'error',
                                title: 'Workspace belum tersedia',
                                text: 'EMR Workspace belum terinisialisasi.'
                            });

                            return;
                        }

                        /* ==========================================
                        TANDAI PASIEN UNTUK AUTO-OPEN PENGKAJIAN
                        SAAT KEMBALI KE HALAMAN MEDICAL RECORD
                        ========================================== */

                        window.__SIRMED_EMR_ROW_PENGKAJIAN_VISITS__
                            ?.add(String(patient.nomor));

                        /* ==========================================
                        BUKA / AKTIFKAN WORKSPACE PASIEN
                        ========================================== */

                        const opened =
                            window.EMRWorkspace.openPatient(patient);

                        if (opened === false) {
                            return;
                        }

                        /* ==========================================
                        JIKA IFRAME SUDAH LOADED
                        LANGSUNG BUKA PENGKAJIAN
                        ========================================== */

                        const tabs =
                            window.EMRWorkspace.getTabs?.() || [];

                        const existingTab = tabs.find(tab =>
                            String(tab.nomor) === String(patient.nomor)
                        );

                        if (existingTab && existingTab.loaded) {

                            const panel = document.getElementById(
                                `emrPanel-${existingTab.tabId}`
                            );

                            const frame = panel?.querySelector(
                                'iframe.emr-patient-frame'
                            );

                            if (frame) {
                                requestAnimationFrame(() => {
                                    window.__SIRMED_EMR_ROW_OPEN_PENGKAJIAN__(
                                        frame
                                    );
                                });
                            }

                        }

                        // Jika iframe belum selesai dimuat, tidak perlu
                        // membuat listener tambahan di sini.
                        // Listener global emr:workspace:content-loaded
                        // di atas akan menangani pasien yang sudah ditandai.
                    }
                );

            // $(document)
            //     .off(
            //         'click.emrWorkspace',
            //         '#vantable tbody tr.emr-patient-row'
            //     )
            //     .on(
            //         'click.emrWorkspace',
            //         '#vantable tbody tr.emr-patient-row',
            //         function (event) {

            //             // Jangan membuka tab ketika tombol atau kontrol
            //             // interaktif pada baris sedang diklik.
            //             if (
            //                 $(event.target).closest(
            //                     'button, input, select, textarea, form, a'
            //                 ).length
            //             ) {
            //                 return;
            //             }

            //             event.preventDefault();
            //             event.stopPropagation();

            //             const nomor = this.dataset.nomor;
            //             const norm = this.dataset.norm;
            //             const tglLahir = this.dataset.tgllahir;
            //             const idAsuransi = this.dataset.idAsuransi;
            //             const refIdAsuransi = this.dataset.refIdAsuransi;
            //             const namaAsuransi = this.dataset.namaAsuransi;
            //             const noAsuransi = this.dataset.noAsuransi;
            //             const namaRuangan = this.dataset.namaRuangan;
            //             const namaDokter = this.dataset.namaDokter;
            //             const umur = this.dataset.umur;
            //             const tglMasuk = this.dataset.tglMasuk;
            //             const tglKeluar = this.dataset.tglKeluar;
            //             const statusKunjungan = this.dataset.statusKunjungan;
            //             const url = this.dataset.href;

            //             let push_nama = '';
            //             let push_noAsuransi = '';
            //             let push_namaDokter = '';
            //             let push_tglMasuk = '';
            //             let push_tglKeluar = '';

            //             try {
            //                 push_nama = decodeURIComponent(
            //                     this.dataset.nama || ''
            //                 );
            //             } catch (error) {
            //                 push_nama = this.dataset.nama || '';
            //             }

            //             try {
            //                 push_noAsuransi = decodeURIComponent(
            //                     this.dataset.noAsuransi || ''
            //                 );
            //             } catch (error) {
            //                 push_noAsuransi = this.dataset.noAsuransi || '';
            //             }

            //             try {
            //                 push_namaDokter = decodeURIComponent(
            //                     this.dataset.namaDokter || ''
            //                 );
            //             } catch (error) {
            //                 push_namaDokter = this.dataset.namaDokter || '';
            //             }

            //             try {
            //                 push_tglMasuk = decodeURIComponent(
            //                     this.dataset.tglMasuk || ''
            //                 );
            //             } catch (error) {
            //                 push_tglMasuk = this.dataset.tglMasuk || '';
            //             }

            //             try {
            //                 push_tglKeluar = decodeURIComponent(
            //                     this.dataset.tglKeluar || ''
            //                 );
            //             } catch (error) {
            //                 push_tglKeluar = this.dataset.tglKeluar || '';
            //             }

            //             if (!nomor || !url) {
            //                 console.error(
            //                     'Nomor kunjungan atau URL detail tidak tersedia.'
            //                 );
            //                 return;
            //             }

            //             // Jangan diam-diam melakukan navigasi biasa apabila
            //             // workspace belum aktif.
            //             if (
            //                 !window.EMRWorkspace ||
            //                 typeof window.EMRWorkspace.openPatient !== 'function'
            //             ) {
            //                 console.error(
            //                     'EMRWorkspace belum terinisialisasi. ' +
            //                     'Periksa pemuatan emr-workspace.js dan layout.'
            //                 );

            //                 return;
            //             }

            //             // Buka tab pasien. Halaman induk tidak berpindah URL.
            //             window.EMRWorkspace.openPatient({
            //                 nomor: nomor,

            //                 norm: norm,
            //                 nama: push_nama,

            //                 tglLahir: tglLahir,
            //                 umur: umur,

            //                 namaRuangan: namaRuangan,
            //                 namaDokter: push_namaDokter,

            //                 refIdAsuransi: refIdAsuransi,
            //                 namaAsuransi: namaAsuransi,
            //                 noAsuransi: push_noAsuransi,

            //                 tglMasuk: push_tglMasuk,
            //                 tglKeluar: push_tglKeluar,

            //                 statusKunjungan: statusKunjungan,

            //                 url: url
            //             });

            //         }
            //     );

            // =========================================================
            // ACTIVE FILTER - UPDATE CHIP
            // =========================================================
            $('#filter_rawat, #filter_ruang, #filter_status, #filter_dpjp, #filter_penjamin')
                .off('change.emrActiveFilter')
                .on('change.emrActiveFilter', function () {
                    renderActiveFilters();
                });

            // =========================================================
            // ACTIVE FILTER - REMOVE SINGLE CHIP
            // =========================================================
            $('#emr-active-filters')
                .off('click.emrRemoveFilter', '[data-remove-filter]')
                .on('click.emrRemoveFilter', '[data-remove-filter]', function (event) {

                    event.preventDefault();
                    event.stopPropagation();

                    const filterKey = this.dataset.removeFilter;

                    switch (filterKey) {

                        // ==============================================
                        // JENIS PERAWATAN
                        // Reset rawat -> 5
                        // Ruangan dan DPJP mengikuti perubahan rawat
                        // ==============================================
                        case 'rawat':
                            $('#filter_rawat')
                                .val('5')
                                .trigger('change');

                            // getRuangan() akan mengatur Ruangan dan DPJP
                            EMRPage.getRuangan();
                            break;

                        // ==============================================
                        // RUANGAN
                        // Reset ruangan -> 5
                        // Kemudian refresh pilihan DPJP
                        // ==============================================
                        case 'ruang':
                            $('#filter_ruang')
                                .val('5')
                                .trigger('change');

                            EMRPage.getDPJP();
                            break;

                        // ==============================================
                        // STATUS KUNJUNGAN
                        // Reset status -> 5
                        // ==============================================
                        case 'status':
                            $('#filter_status')
                                .val('5')
                                .trigger('change');

                            EMRPage.filter();
                            break;

                        // ==============================================
                        // DPJP
                        // Reset dokter -> 5
                        // ==============================================
                        case 'dpjp':
                            $('#filter_dpjp')
                                .val('5')
                                .trigger('change.select2');

                            renderActiveFilters();
                            EMRPage.filter();
                            break;

                        // ==============================================
                        // PENJAMIN
                        // Reset penjamin -> 0
                        // ==============================================
                        case 'penjamin':
                            $('#filter_penjamin')
                                .val('0')
                                .trigger('change');

                            EMRPage.filter();
                            break;
                    }
                });
        });

        /**
         * SELECT2 DPJP
         * Select biasa dengan fitur pencarian.
         */
        function initSelectDPJP() {
            const $dpjp = $('#filter_dpjp');

            // Hindari inisialisasi ganda saat navigasi Livewire
            if ($dpjp.hasClass('select2-hidden-accessible')) {
                $dpjp.select2('destroy');
            }

            $dpjp.select2({
                width: '100%',
                placeholder: 'Pilih Dokter',
                allowClear: false,
                closeOnSelect: true,
                minimumResultsForSearch: 0,
                language: {
                    noResults: function () {
                        return 'Dokter tidak ditemukan';
                    },
                    searching: function () {
                        return 'Mencari dokter...';
                    }
                }
            });
        }

        function getRuangan() {
            idRuang = $('#filter_rawat').val();
            if (idRuang == 5) {
                $('#filter_ruang').prop('disabled',true);
                $("#filter_ruang").find('option').remove();
                $("#filter_ruang").append(`
                    <option value="5" selected>Semua Ruangan</option>
                `);
                getDPJP();
            } else {
                $.ajax({
                    url: `/api/v2/emr/ruangan/${idRuang}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        $('#filter_ruang').prop('disabled',false);
                        $("#filter_ruang").find('option').remove();
                        $("#filter_ruang").append(`
                            <option value="5" selected>Semua Ruangan</option>
                        `);
                        res.forEach(pouch => {
                            $("#filter_ruang").append(`
                                <option value="${pouch.ID}">${pouch.DESKRIPSI}</option>
                            `);
                        });
                        getDPJP();
                        // $("#filter_ruang").val(up).change();
                    },
                    error: function (xhr) {
                        Swal.fire(
                            'Gagal',
                            xhr.responseJSON?.message ?? 'Terjadi kesalahan / Gagal memanggil Function!',
                            'error'
                        );
                    },
                })
            }
        }

        function getPenjamin() {
            $.ajax({
                url: `/api/v2/emr/penjamin`,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    $('#filter_penjamin').prop('disabled',false);
                    $("#filter_penjamin").find('option').remove();
                    $("#filter_penjamin").append(`
                        <option value="0">Semua Penjamin</option>
                    `);
                    res.forEach(pouch => {
                        $("#filter_penjamin").append(`
                            <option value="${pouch.ID}">${pouch.DESKRIPSI}</option>
                        `);
                        // <option value="${pouch.ID}" ${pouch.ID == 2 ? 'selected' : ''}>${pouch.DESKRIPSI}</option>
                    });
                    $("#filter_penjamin").val('0').trigger('change');
                },
                error: function (xhr) {
                    Swal.fire(
                        'Gagal',
                        xhr.responseJSON?.message ?? 'Terjadi kesalahan / Gagal memanggil Function!',
                        'error'
                    );
                },
            })
        }

        function getDPJP() {
            const idRuangPerawatan = $('#filter_ruang').val();
            const $dpjp = $('#filter_dpjp');

            // Reset pilihan dokter
            $dpjp
                .prop('disabled', true)
                .empty()
                .append(new Option('Memuat dokter...', '5', true, true))
                .trigger('change.select2');

            $.ajax({
                url: `/api/v2/emr/ruangan/${idRuangPerawatan}/dpjp`,
                type: 'GET',
                dataType: 'json',

                success: function (res) {
                    const pilihanDokter = [
                        new Option('Semua Dokter', '5', false, false)
                    ];

                    if (Array.isArray(res.show)) {
                        res.show.forEach(function (dokter) {
                            pilihanDokter.push(
                                new Option(
                                    `${dokter.NAMADOKTER} (${dokter.DESKRIPSI})`,
                                    String(dokter.NIP),
                                    false,
                                    false
                                )
                            );
                        });
                    }

                    // Ganti seluruh pilihan dokter
                    $dpjp.empty().append(pilihanDokter);

                    // Default: semua dokter
                    let dokterTerpilih = '5';

                    // Jika semua ruangan, pilih DPJP user jika tersedia
                    if (
                        String(idRuangPerawatan) === '5' &&
                        res.user !== null &&
                        res.user !== undefined
                    ) {
                        const userDPJP = String(res.user);

                        const tersedia = $dpjp.find('option').filter(function () {
                            return this.value === userDPJP;
                        }).length > 0;

                        if (tersedia) {
                            dokterTerpilih = userDPJP;
                        }
                    }

                    $dpjp
                        .val(dokterTerpilih)
                        .prop('disabled', false)
                        .trigger('change.select2');

                    // Update chip filter
                    renderActiveFilters();

                    // Perbarui tabel menggunakan filter yang terpilih
                    filter();
                },

                error: function (xhr) {
                    $dpjp
                        .empty()
                        .append(new Option('Tidak Ada Dokter', '0', true, true))
                        .prop('disabled', true)
                        .trigger('change.select2');

                    Swal.fire(
                        'Gagal',
                        xhr.responseJSON?.message ??
                            'Terjadi kesalahan saat mengambil data dokter.',
                        'error'
                    );
                }
            });
        }

        // =========================================================
        // EMR ACTIVE FILTER CHIPS
        // =========================================================
        function renderActiveFilters() {
            const container = $('#emr-active-filters');

            if (!container.length) return;

            const filters = [
                {
                    key: 'rawat',
                    selector: '#filter_rawat',
                    defaultValue: '5',
                    label: 'Rawat'
                },
                {
                    key: 'ruang',
                    selector: '#filter_ruang',
                    defaultValue: '5',
                    label: 'Ruangan'
                },
                {
                    key: 'status',
                    selector: '#filter_status',
                    defaultValue: '5',
                    label: 'Status'
                },
                {
                    key: 'dpjp',
                    selector: '#filter_dpjp',
                    defaultValue: '5',
                    label: 'DPJP'
                },
                {
                    key: 'penjamin',
                    selector: '#filter_penjamin',
                    defaultValue: '0',
                    label: 'Penjamin'
                }
            ];

            const activeFilters = filters.filter(function (filter) {
                const element = $(filter.selector);

                return element.length &&
                    String(element.val() ?? '') !== filter.defaultValue;
            });

            if (!activeFilters.length) {
                container
                    .removeClass('has-filters')
                    .empty();

                return;
            }

            let html = `
                <div class="emr-filter-heading">
                    <i class="ph-duotone ph-funnel"></i>
                    Filter Aktif
                    <span class="emr-filter-count">${activeFilters.length}</span>
                </div>
            `;

            activeFilters.forEach(function (filter) {
                const selectedOption = $(filter.selector)
                    .find('option:selected');

                const selectedText = selectedOption.text().trim();

                html += `
                    <div class="emr-filter-chip"
                        data-filter-key="${filter.key}"
                        title="${filter.label}: ${selectedText}">

                        <span class="emr-filter-chip-label">
                            ${filter.label}: ${selectedText}
                        </span>

                        <button type="button"
                            class="emr-filter-remove"
                            data-remove-filter="${filter.key}"
                            aria-label="Hapus filter ${filter.label}"
                            title="Hapus filter ${filter.label}">

                            <i class="ph-duotone ph-x"></i>
                        </button>
                    </div>
                `;
            });

            container
                .html(html)
                .addClass('has-filters');
        }

        function filter() {
            // Update visual filter aktif
            renderActiveFilters();
            // Initialize
            var rawat = $("#filter_rawat").val();
            var ruang = $("#filter_ruang").val();
            var status = $("#filter_status").val();
            var tgl = $("#filter_tgl").val();
            var dpjp = $("#filter_dpjp").val() ? $("#filter_dpjp").val() : '0'; // JIKA DPJP KOSONG = 0
            var penjamin = $("#filter_penjamin").val();

            if (tgl == '') {
                Swal.fire(
                    'Data Filter Tidak Valid!',
                    'Pastikan Pilihan Rentang Tgl Kunjungan pada Filter Data terisi dengan benar',
                    'error'
                );
                return;
            }
            var exTgl = tgl.split(' to ');
            if (exTgl.length == 2) { // SPLIT FROM = "2024-01-01 to 2025-01-01"
                tgls = exTgl[0];
                tgle = exTgl[1];
            } else { // SPLIT FROM = "2024-01-01"
                tgls = exTgl[0];
                tgle = exTgl[0];
            }

            // Remove init Table EMR if exist
            if (tableEMR) {
                tableEMR.destroy();
            }
            $('#show_table').prop('hidden',false);
            $("#tampil-tbody").empty().append(`<tr style='font-size:13px'><td colspan="15"><center><div class="spinner-border spinner-border-sm" role="status"></div></center></td></tr>`);

            // Process
            $.ajax({
                url: '/api/v2/emr',
                type: 'POST',
                data: {
                    rawat: rawat,
                    ruang: ruang,
                    status: status,
                    tgls: tgls,
                    tgle: tgle,
                    dpjp: dpjp,
                    penjamin: penjamin,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    $('#tombol-tampilkan').prop('disabled',true).find('i').removeClass('ph-duotone ph-sort-ascending').addClass('ri-loader-line ri-spin').css('font-size', '15px');
                    $('#jumlah_data').empty().html(`<i class="ri-loader-line ri-spin"></i>`);
                },
                success: function(res) {
                    $("#tampil-tbody").empty();
                    $('#jumlah_data').empty().text(res.show.length + ' Data');
                    if (res.show && Array.isArray(res.show)) {
                        content = ``;
                        res.show.forEach(item => {
                            if (item.STATUS == 1) {
                                status = '<span class="badge bg-warning-subtle text-dark border border-warning-subtle badge-sm fs-12 p-0 ps-1 pe-1 fw-medium" style="font-size:8pt" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Status Kunjungan Pasien">Pasien berada di ruangan ini / sedang dilayani</span>';
                            } else {
                                if (item.STATUS == 2) {
                                    status = '<span class="badge bg-success-subtle text-dark border border-success-subtle badge-sm fs-12 p-0 ps-1 pe-1 fw-medium" style="font-size:8pt" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Status Kunjungan Pasien">Kunjungan Selesai</span>';
                                } else {
                                    status = '<span class="badge bg-danger-subtle text-dark border border-danger-subtle badge-sm fs-12 p-0 ps-1 pe-1 fw-medium" style="font-size:8pt" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Status Kunjungan Pasien">Kunjungan Dibatalkan</span>';
                                }
                            }
                            sepNF = false;
                            if (item.NOSEP) {
                                valSEP = item.NOSEP.substring(8, 12); // 0624
                                parts = item.TGLSEP.split("-"); // hasil: ['08', '06', '2024'] || e.g. 2024-01-12 00:00:00
                                valTGLSEP = parts[1]+parts[0].slice(-2); // '0624'
                                if (valSEP == valTGLSEP) {
                                    SEP = '<b class="text-purple-400">'+item.NOSEP+'</b>';
                                    btnSEP = `<button type="button" class="btn btn-sm btn-icon btn-link-success" id="sep`+item.NOMOR+`" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lihat Detail SEP" onclick="showSEP('`+item.NOMOR+`')">
                                                    <i class="fas fa-check text-success"></i>
                                                </button>`;
                                } else {
                                    SEP = '<b class="text-danger">Tanggal SEP Tidak Sesuai!</b>';
                                    btnSEP = `<button type="button" class="btn btn-sm btn-icon btn-link-danger" id="sep`+item.NOMOR+`" data-bs-toggle="tooltip" data-bs-placement="bottom" title="No.SEP tidak sesuai dengan Tanggal SEP" onclick="showSEP('`+item.NOMOR+`')">
                                                    <i class="fas fa-check fs-5 text-danger"></i>
                                                </button>`;
                                }
                                sepNF = false;
                            } else {
                                SEP = '<b class="text-secondary">SEP Tidak Ditemukan</b>';
                                btnSEP = `<button type="button" class="btn btn-sm btn-icon btn-link-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="SEP tidak ditemukan">
                                                <i class="fas fa-times fs-5 text-secondary"></i>
                                            </button>`;
                                sepNF = true;
                            }
                            if (item.JENISPENJAMIN == 1) {
                                clrTxPj = 'text-emerald';
                            } else {
                                if (item.JENISPENJAMIN == 2) {
                                    clrTxPj = 'text-blue';
                                } else {
                                    clrTxPj = 'text-orange';
                                }
                            }

                            const showAntrian =
                                item.RUANGAN?.startsWith('10201') ||
                                item.RUANGAN?.startsWith('10207');

                            if (showAntrian && item.POS_ANTRIAN && item.NOMOR_ANTRIAN) {
                                const nomorAntrian = String(item.NOMOR_ANTRIAN).padStart(3, '0');

                                antrian = `
                                    <span class="badge bg-light-subtle border border-primary-subtle text-secondary"
                                        style="font-size:35pt;width:100%;height:auto;cursor:pointer;">
                                        ${item.POS_ANTRIAN}-${nomorAntrian}
                                    </span>
                                `;
                            } else {
                                antrian = '';
                            }

                            content += `<tr
                                            class="emr-patient-row"
                                            data-nomor="${item.NOMOR}"
                                            data-norm="${item.NORM}"
                                            data-nama="${encodeURIComponent(item.NAMAPASIEN ?? '')}"
                                            data-tgllahir="${item.TGLLAHIRPASIEN}"
                                            data-umur="${item.UMURPASIEN}"
                                            data-ref-id-asuransi="${item.REFJENISPENJAMIN}"
                                            data-nama-asuransi="${item.NAMAPENJAMIN}"
                                            data-no-asuransi="${encodeURIComponent(item.NOMORPENJAMIN ?? '')}"
                                            data-nama-ruangan="${item.NAMARUANGAN}"
                                            data-nama-dokter="${encodeURIComponent(item.NAMADOKTER ?? '')}"
                                            data-tgl-masuk="${encodeURIComponent(item.MASUK ?? '')}"
                                            data-tgl-keluar="${encodeURIComponent(item.KELUAR ?? '')}"
                                            data-status-kunjungan="${item.STATUS}"
                                            data-href="/v3/emr/${encodeURIComponent(item.NOMOR)}"
                                            style="cursor:pointer;"
                                        >
                                            <!-- Nomor Antrian -->
                                            <td class="text-center align-middle kolom-antrian">${antrian}</td>

                                            <!-- Informasi Pasien -->
                                            <td class="ps-3">
                                                <h4>
                                                    <b data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    title="Nomor Rekam Medis">
                                                        RM. ${item.NORM}
                                                    </b>
                                                    -
                                                    <b class="text-primary">${item.NAMAPASIEN}</b>
                                                </h4>

                                                <h6 class="text-dark">
                                                    <b>DPJP : ${item.NAMADOKTER}</b>
                                                </h6>

                                                <h6 class="fw-medium text-dark">
                                                    Ruangan <b class="text-pink">${item.NAMARUANGAN}</b>
                                                    <i class="ri-subtract-line"></i>

                                                    <b ${sepNF?'hidden':''}>
                                                        <b class="text-lavender"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="bottom"
                                                        title="Nomor SEP">${SEP}</b>
                                                        <i class="ri-subtract-line"></i>
                                                    </b>

                                                    <b class="${clrTxPj}">${item.NAMAPENJAMIN}</b>
                                                </h6>

                                                ${status}

                                                <!-- SHORTCUT EMR -->
                                                <div class="dropdown mt-2 emr-row-shortcut">
                                                    <button
                                                        type="button"
                                                        class="btn btn-sm btn-outline-primary dropdown-toggle"
                                                        data-bs-toggle="dropdown"
                                                        aria-expanded="false">

                                                        <i class="ph-duotone ph-squares-four me-1"></i>
                                                        Shortcut EMR
                                                    </button>

                                                    <div class="dropdown-menu shadow-sm">
                                                        <button
                                                            type="button"
                                                            class="dropdown-item emr-shortcut-item"
                                                            data-emr-tab="tab-forder" disabled>
                                                            <i class="ph-duotone ph-clipboard-text me-2"></i>
                                                            <s>Order Penunjang</s>
                                                        </button>
                                                        ${
                                                            item.RUANGAN?.startsWith('10203')
                                                                ? `
                                                                    <button
                                                                        type="button"
                                                                        class="dropdown-item emr-shortcut-item"
                                                                        data-emr-tab="tab-resume-ranap">
                                                                        <i class="ph-duotone ph-clipboard-text me-2"></i>
                                                                        Resume Medis Rawat Inap
                                                                    </button>
                                                                `
                                                                : ''
                                                        }
                                                        ${
                                                            item.RUANGAN?.startsWith('10201') ||
                                                            item.RUANGAN?.startsWith('10207')
                                                                ? `
                                                                    <button
                                                                        type="button"
                                                                        class="dropdown-item emr-shortcut-item"
                                                                        data-emr-tab="tab-resume-rajal">
                                                                        <i class="ph-duotone ph-clipboard-text me-2"></i>
                                                                        Resume Medis Rawat Jalan
                                                                    </button>
                                                                `
                                                                : ''
                                                        }
                                                        ${
                                                            item.RUANGAN?.startsWith('10207')
                                                                ? `
                                                                    <button
                                                                        type="button"
                                                                        class="dropdown-item emr-shortcut-item"
                                                                        data-emr-tab="tab-fmrehab">
                                                                        <i class="ph-duotone ph-archive-box me-2"></i>
                                                                        Program Rehab Medik
                                                                    </button>
                                                                `
                                                                : ''
                                                        }
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Waktu -->
                                            <td class="text-start align-middle">
                                                <button type="button" class="btn btn-link-info btn-sm mb-0"
                                                    onclick="event.stopPropagation();">
                                                    Masuk
                                                    <span class="badge badge-soft-light badge-lg text-dark ms-2">
                                                        ${item.MASUK}
                                                    </span>
                                                </button>

                                                <br>

                                                <button type="button" class="btn btn-link-danger btn-sm mb-0"
                                                    onclick="event.stopPropagation();">
                                                    Keluar
                                                    <span class="badge badge-soft-light badge-lg text-dark ms-2">
                                                        ${item.KELUAR ?? '-'}
                                                    </span>
                                                </button>
                                            </td>

                                        </tr>`;
                        })

                        $('#tampil-tbody').append(content);

                        // if (rawat == 5 || rawat == 1) {
                        //     $('.kolom-antrian').prop('hidden',false);
                        // } else {
                        //     $('.kolom-antrian').prop('hidden',true);
                        // }
                    }
                    // VANILLA TABLE
                    tableEMR = $('#vantable').DataTable({
                        // processing: true,
                        displayLength: 10,
                        lengthMenu: [10, 20, 50, 100, 300, 500],
                        aoColumns : [
                            { sWidth: '10%' },
                            { sWidth: '75%' },
                            { sWidth: '15%' },
                        ],
                        order: [[2, 'desc']], // kolom ke-3 descending
                        columnDefs: [
                            {
                                targets: [0, 1],
                                orderable: false
                            }
                        ],
                    });

                    tableEMR.column(0).visible(rawat == 1 || rawat == 5);

                    // Showing Tooltip
                    $('[data-bs-toggle="tooltip"]').tooltip('dispose');
                    $('.tooltip').remove();
                    $('[data-bs-toggle="tooltip"]').tooltip({
                        trigger : 'hover'
                    })
                }, error: function(xhr, status, error) {
                    Swal.fire(
                        'Gagal',
                        xhr.responseJSON?.message ?? 'Terjadi kesalahan saat pengambilan data',
                        'error'
                    );
                    $('#jumlah_data').empty().text('0 Data');
                }, complete: function() {
                    $('#tombol-tampilkan').prop('disabled',false).find('i').removeClass('ri-loader-line ri-spin').addClass('ph-duotone ph-sort-ascending').css('font-size', '');
                    const cache = JSON.parse(sessionStorage.getItem('emr_filter'));

                    if (cache) {
                        setTimeout(function () {
                            window.scrollTo({
                                top: cache.scroll || 0,
                                behavior: 'instant'
                            });
                        }, 100);
                    }
                }
            })
        }

        function clearFilter() {
            $('#jumlah_data').text('0 Data');

            $('#filter_status').val('1');
            $('#filter_rawat').val('5');
            $('#filter_penjamin').val('0');
            $('#filter_ruang').val('5').prop('disabled', true);

            $('#filter_dpjp')
                .val('5')
                .trigger('change');

            // Kosongkan tabel dan sembunyikan area tabel
            $('#tampil-tbody').empty();
            $('#show_table').prop('hidden', true);

            // Muat ulang daftar dokter sesuai kondisi semua ruangan
            getDPJP();
        }

    })();
</script>
