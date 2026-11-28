/* ==========================================================
 * RESUME MEDIS RANAP
 * ----------------------------------------------------------
 * 1. FIELD INFORMATION MODAL
 * 2. FORM INITIALIZATION
 * 3. GET / LOAD DATA
 * 4. AUTO SAVE
 * 5. FINALISASI
 * 6. BATAL FINAL
 * 7. PDF PREVIEW MODAL
 * ========================================================== */

(function ($) {
    'use strict';

    // Hindari inisialisasi global berulang
    if (window.__ResumeRanapJSInitialized) {
        return;
    }

    window.__ResumeRanapJSInitialized = true;

    // ==========================================================
    // HELPER
    // ==========================================================

    function getValue(value, fallback = '') {
        return value === null || value === undefined ? fallback : value;
    }

    function getFirstDefined(...values) {
        for (const value of values) {
            if (value !== null && value !== undefined && value !== '') {
                return value;
            }
        }

        return '';
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // ==========================================================
    // 1. FIELD INFORMATION MODAL
    // ==========================================================

    const resumeInfoData = {

        indikasi: {
            title: 'Indikasi Rawat Inap',

            source: 'medicalrecord.triage → KRITERIA_TAMBAHAN',

            description:
                'Informasi ini berasal dari kriteria tambahan yang ' +
                'dicatat pada triase IGD atau perencanaan rawat inap kunjungan sebelumnya.',

            detail:
                'Sistem mengambil pilihan kriteria tambahan dari data ' +
                'triase pasien maupun dari perencanaan rawat inap bagian Indikasi. ' +
                'Pilihan yang tersimpan kemudian diterjemahkan ' +
                'menjadi kalimat indikasi rawat inap, sehingga tidak perlu ' +
                'ditulis ulang secara manual.',

            images: [
                {
                    src: '/images/v3/resume-ranap/info/indikasi-rawat-inap.png',
                    caption: 'Lokasi pengambilan kriteria tambahan pada data Triase'
                }
            ]
        },

        rps: {
            title: 'Ringkasan Penyakit Sekarang (RPS)',

            source: 'medicalrecord.anamnesis → DESKRIPSI / CPPT Subyektif',

            description:
                'Berisi ringkasan keluhan dan perjalanan penyakit ' +
                'pasien yang telah dicatat pada kunjungan Poli atau IGD sebelumnya.',

            detail:
                'Sistem mengambil deskripsi Anamnesis pada kunjungan IGD ' +
                'atau mengambil dari CPPT DPJP terakhir sebelum Ranap bagian Subyektif (S) ' +
                'pada kunjungan Poliklinik sebelum pasien masuk Rawat Inap.',

            images: [
                {
                    src: '/images/v3/resume-ranap/info/rps_anam.png',
                    caption:
                        'Penyakit Sekarang dari Anamnesis apabila kunjungan sebelumnya adalah Rawat Darurat'
                },
                {
                    src: '/images/v3/resume-ranap/info/rps.png',
                    caption:
                        'Riwayat Penyakit Sekarang dari CPPT DPJP terakhir sebelum Ranap bagian Subyektif apabila kunjungan sebelumnya adalah Rawat Jalan / Poliklinik'
                }
            ]
        },

        rpd: {
            title: 'Ringkasan Penyakit Dahulu (RPD)',

            source: 'medicalrecord.rpp → DESKRIPSI',

            description:
                'Berisi riwayat penyakit yang pernah dialami pasien ' +
                'dan telah dicatat sebelumnya.',

            detail:
                'Sistem mengambil catatan Riwayat Penyakit Pasien (RPP) ' +
                'yang tersedia pada kunjungan Poli atau IGD sebelumnya. ' +
                'Data yang ditemukan digunakan sebagai ringkasan riwayat ' +
                'penyakit terdahulu.',

            images: [
                {
                    src: '/images/v3/resume-ranap/info/rpd.png',
                    caption: 'Lokasi pengambilan Ringkasan Penyakit Dahulu'
                }
            ]
        },

        rpm: {
            title: 'Riwayat Pengobatan Medis',

            source: 'medicalrecord.riwayat_pemberian_obat → OBAT',

            description:
                'Berisi riwayat obat yang telah tercatat pada pelayanan ' +
                'medis pasien sebelumnya.',

            detail:
                'Sistem mengambil nama obat dari catatan pemberian obat ' +
                'pada kunjungan Poli atau IGD sebelumnya. Apabila terdapat ' +
                'lebih dari satu obat, nama obat akan digabungkan menjadi ' +
                'satu ringkasan.',

            images: [
                {
                    src: '/images/v3/resume-ranap/info/rpo.png',
                    caption: 'Lokasi pengambilan Riwayat Pengobatan Medis'
                }
            ]
        },

        ra: {
            title: 'Riwayat Alergi',

            source: 'medicalrecord.riwayat_alergi',

            description:
                'Berisi riwayat alergi pasien yang sudah tercatat ' +
                'dalam rekam medis.',

            detail:
                'Sistem mencari catatan alergi berdasarkan identitas ' +
                'pasien (NORM), bukan hanya dari kunjungan Rawat Inap ' +
                'yang sedang dibuka. Jenis alergi dan keterangannya ' +
                'digabungkan untuk ditampilkan pada Resume Medis.',

            images: [
                {
                    src: '/images/v3/resume-ranap/info/ra.png',
                    caption: 'Lokasi pengambilan Data Riwayat Alergi pasien'
                }
            ]
        },

        pemeriksaan_fisik: {
            title: 'Pemeriksaan Fisik',

            source:
                'medicalrecord.tanda_vital + medicalrecord.pemeriksaan_fisik / CPPT Obyektif',

            description:
                'Berisi hasil pemeriksaan kondisi fisik pasien, termasuk ' +
                'tanda vital dan hasil pemeriksaan fisik yang telah dicatat.',

            detail:
                'Sistem menggabungkan data tanda vital seperti tekanan darah, ' +
                'suhu, frekuensi nadi, frekuensi pernapasan, tingkat ' +
                'kesadaran, GCS, VAS, jalan napas, pupil, kondisi kulit, ' +
                'dan alat bantu napas dengan data pemeriksaan fisik ' +
                'apabila kunjungan pasien sebelumnya adalah Rawat Darurat / IGD. ' +
                'Apabila kunjungan sebelumnya dari Rawat Jalan / Poliklinik, ' +
                'pengambilan data murni berasal dari CPPT DPJP terakhir bagian Obyektif.',

            images: [
                {
                    src: '/images/v3/resume-ranap/info/rps_anam.png',
                    caption:
                        'Pemeriksaan fisik dari sumber IGD / Rawat Darurat'
                },
                {
                    src: '/images/v3/resume-ranap/info/rps.png',
                    caption:
                        'Pemeriksaan fisik dari CPPT Obyektif (O) apabila kunjungan sebelumnya adalah Rawat Jalan / Poliklinik'
                }
            ]
        },

        diagnosa_utama: {
            title: 'Diagnosa Utama',

            source: 'medicalrecord.diagnosa + master.mrconso',

            description:
                'Berisi diagnosa utama pasien yang telah ditetapkan ' +
                'dan dicatat oleh dokter.',

            detail:
                'Sistem mengambil diagnosa dokter pada pendaftaran Rawat Inap ' +
                'yang ditandai sebagai diagnosa utama. Nama diagnosa dan ' +
                'kode ICD-10 dicari dari data referensi diagnosa. ' +
                'Referensi ICD-10 tahun 2020 diprioritaskan, dengan ' +
                'referensi lama sebagai alternatif apabila tersedia.'
        },

        diagnosa_sekunder: {
            title: 'Diagnosa Sekunder',

            source: 'medicalrecord.diagnosa + master.mrconso',

            description:
                'Berisi diagnosa tambahan selain diagnosa utama ' +
                'yang telah dicatat oleh dokter.',

            detail:
                'Sistem mengambil diagnosa dokter yang ditandai sebagai ' +
                'diagnosa sekunder pada pendaftaran Rawat Inap. Nama ' +
                'diagnosa dan kode ICD-10 diambil dari referensi diagnosa ' +
                'yang tersedia.'
        },

        diagnosa_dokter_icd10: {
            title: 'Keterangan Diagnosa ICD-10',

            source: 'medicalrecord.diagnosa → DIAGNOSA',

            description:
                'Berisi keterangan diagnosa yang ditulis atau dicatat ' +
                'pada data diagnosa dokter.',

            detail:
                'Sistem mengambil kolom keterangan diagnosa dari seluruh ' +
                'data diagnosa dokter yang aktif pada pendaftaran Rawat Inap, ' +
                'kemudian menggabungkannya menjadi satu teks.'
        },

        diagnosa_perawat: {
            title: 'Diagnosa Perawat',

            source: 'medicalrecord.prosedur + master.mrconso',

            description:
                'Berisi data tindakan atau prosedur yang tercatat ' +
                'pada pelayanan keperawatan.',

            detail:
                'Sistem mengambil data prosedur berdasarkan pendaftaran ' +
                'Rawat Inap. Kode prosedur dicocokkan dengan referensi ' +
                'ICD-9-CM untuk memperoleh nama yang sesuai. Referensi ' +
                'tahun 2020 digunakan sebagai prioritas.'
        },

        diagnosa_dokter_icd9: {
            title: 'Diagnosa Dokter ICD-9',

            source: 'medicalrecord.prosedur → TINDAKAN',

            description:
                'Berisi daftar tindakan atau prosedur yang tercatat ' +
                'pada pelayanan Rawat Inap.',

            detail:
                'Sistem mengambil keterangan tindakan dari data prosedur ' +
                'Rawat Inap. Seluruh tindakan yang tersedia akan ' +
                'digabungkan menjadi satu keterangan.'
        },

        terapi: {
            title: 'Terapi',

            source:
                'medicalrecord.rencana_terapi + medicalrecord.hasil_lapor_dpjp',

            description:
                'Berisi rencana terapi dan hasil laporan DPJP ' +
                'yang telah dicatat sebelumnya.',

            detail:
                'Sistem menggabungkan data perencanaan terapi dengan ' +
                'hasil laporan DPJP. Kedua catatan tersebut digunakan ' +
                'sebagai bahan penyusunan ringkasan terapi pasien.'
        },

        kondisi_keluar: {
            title: 'Kondisi Waktu Keluar RS',

            source: 'layanan.pasien_pulang → KEADAAN',

            description:
                'Menjelaskan kondisi pasien pada saat proses keluar ' +
                'atau pulang dari rumah sakit.',

            detail:
                'Sistem mengambil kondisi pasien dari data pasien pulang ' +
                'yang tercatat pada kunjungan Rawat Inap.'
        },

        cara_keluar: {
            title: 'Cara Keluar / Pasien Pulang',

            source: 'layanan.pasien_pulang → CARA',

            description:
                'Menjelaskan bagaimana pasien menyelesaikan pelayanan ' +
                'Rawat Inap atau keluar dari rumah sakit.',

            detail:
                'Sistem mengambil cara keluar pasien dari data pasien pulang ' +
                'yang tersimpan pada kunjungan Rawat Inap.'
        },

        ku_td_up: {
            title: 'Tekanan Darah Sistolik',

            source: 'medicalrecord.tanda_vital → SISTOLIK',

            description:
                'Berisi nilai tekanan darah sistolik pasien.',

            detail:
                'Sistem mengambil nilai sistolik dari catatan tanda vital ' +
                'Rawat Inap terakhir yang tersedia pada kunjungan pasien.'
        },

        ku_td_down: {
            title: 'Tekanan Darah Diastolik',

            source: 'medicalrecord.tanda_vital → DISTOLIK',

            description:
                'Berisi nilai tekanan darah diastolik pasien.',

            detail:
                'Sistem mengambil nilai diastolik dari catatan tanda vital ' +
                'Rawat Inap terakhir yang tersedia pada kunjungan pasien.'
        },

        ku_nafas: {
            title: 'Frekuensi Nafas',

            source: 'medicalrecord.tanda_vital → FREKUENSI_NAFAS',

            description:
                'Berisi jumlah frekuensi pernapasan pasien.',

            detail:
                'Sistem mengambil nilai frekuensi pernapasan dari catatan ' +
                'tanda vital Rawat Inap terakhir yang tersedia.'
        },

        ku_tk: {
            title: 'Tingkat Kesadaran',

            source: 'medicalrecord.tanda_vital → TINGKAT_KESADARAN',

            description:
                'Berisi tingkat kesadaran pasien berdasarkan pemeriksaan.',

            detail:
                'Sistem mengambil nilai tingkat kesadaran dari catatan ' +
                'tanda vital Rawat Inap terakhir. Nilai tersebut kemudian ' +
                'digunakan untuk menentukan tingkat kesadaran yang ditampilkan.'
        },

        ku_sn: {
            title: 'Skala Nyeri',

            source: 'medicalrecord.penilaian_nyeri → NYERI + METODE',

            description:
                'Berisi hasil penilaian nyeri pasien yang telah dicatat.',

            detail:
                'Sistem mengambil penilaian nyeri terakhir yang tersedia. ' +
                'Apabila pasien tercatat tidak mengalami nyeri, akan ditampilkan ' +
                'Tidak Ada. Jika terdapat nyeri, keterangan metode penilaian ' +
                'akan ditampilkan apabila tersedia.'
        },

        ku_nadi: {
            title: 'Frekuensi Nadi',

            source: 'medicalrecord.tanda_vital → FREKUENSI_NADI',

            description:
                'Berisi jumlah frekuensi denyut nadi pasien.',

            detail:
                'Sistem mengambil nilai frekuensi nadi dari catatan tanda ' +
                'vital Rawat Inap terakhir yang tersedia.'
        },

        ku_suhu: {
            title: 'Suhu Tubuh',

            source: 'medicalrecord.tanda_vital → SUHU',

            description:
                'Berisi suhu tubuh pasien yang tercatat saat pemeriksaan.',

            detail:
                'Sistem mengambil nilai suhu tubuh dari catatan tanda vital ' +
                'Rawat Inap terakhir yang tersedia.'
        },

        layanan_lanjutan: {
            title: 'Layanan Lanjutan',

            source: 'Pengisian oleh petugas Resume Medis',

            description:
                'Berisi rencana layanan atau tindak lanjut pasien ' +
                'setelah menyelesaikan perawatan di rumah sakit.',

            detail:
                'Bagian ini tidak diambil otomatis dari sumber data ' +
                'getData() yang saat ini digunakan. Petugas mengisi ' +
                'sesuai kebutuhan tindak lanjut pasien.'
        },

        edukasi: {
            title: 'Edukasi',

            source: 'medicalrecord.edukasi_emergency → EDUKASI',

            description:
                'Berisi informasi edukasi yang telah diberikan ' +
                'kepada pasien atau keluarga.',

            detail:
                'Sistem mengambil catatan edukasi yang tersimpan pada ' +
                'rekam medis pasien dari data edukasi sebelumnya, ' +
                'kemudian menampilkannya pada Resume Medis.'
        }
    };

    // ==========================================================
    // RENDER INFO IMAGES
    // ==========================================================

    function renderResumeInfoImages(info) {

        const container = document.getElementById('resumeInfoImages');

        if (!container) return;

        container.replaceChildren();

        const images = Array.isArray(info.images) ? info.images : [];

        if (!images.length) {

            container.innerHTML = `
                <div class="info-image-placeholder">
                    <i class="ph-duotone ph-image-square"></i>

                    <div class="info-image-placeholder-title">
                        Screenshot Sumber Data
                    </div>

                    <div class="info-image-placeholder-text">
                        Gambar ilustrasi sementara.<br>
                        Dokumentasi sumber data akan ditambahkan kemudian.
                    </div>
                </div>
            `;

            return;
        }

        images.forEach((item, index) => {

            const wrapper = document.createElement('div');
            wrapper.className = 'info-image-item';

            const img = document.createElement('img');

            img.className = 'info-image';
            img.src = item.src;
            img.alt = item.caption || info.title;
            img.loading = 'lazy';

            const caption = document.createElement('div');

            caption.className = 'info-image-caption';
            caption.textContent = item.caption || `Ilustrasi ${index + 1}`;

            wrapper.appendChild(img);
            wrapper.appendChild(caption);

            container.appendChild(wrapper);
        });
    }

    // ==========================================================
    // INIT INFO MODAL
    // ==========================================================

    function initResumeInfoModal() {

        const modal = document.getElementById('resumeInfoModal');

        if (!modal || modal.dataset.initialized === '1') {
            return;
        }

        modal.dataset.initialized = '1';

        modal.addEventListener('show.bs.modal', function (event) {

            const button = event.relatedTarget;

            if (!button) return;

            const key = button.dataset.infoKey;
            const info = resumeInfoData[key];

            if (!info) {
                console.warn('[Resume Info] Informasi tidak ditemukan:', key);
                return;
            }

            const title = modal.querySelector('#resumeInfoModalLabel');
            const source = modal.querySelector('#resumeInfoSource span');
            const description = modal.querySelector('#resumeInfoDescription');
            const detail = modal.querySelector('#resumeInfoDetail');

            renderResumeInfoImages(info);

            if (title) title.textContent = info.title;
            if (source) source.textContent = info.source;
            if (description) description.textContent = info.description;
            if (detail) detail.textContent = info.detail;
        });
    }

    // ==========================================================
    // 2. RESUME FORM INITIALIZATION
    // ==========================================================

    function initResumeRanapForm() {

        const $form = $('#form_resume_medis_ranap');

        if (!$form.length) return;

        // Prevent duplicate initialization
        if ($form.data('initialized')) return;

        $form.data('initialized', true);

        const $fieldset = $form.find('#resume_fields');
        const $badge = $form.find('#badge_final_resume');

        const $tabResume = $('#tab-resume-ranap');
        const $tabBadge = $tabResume.find('#badge_tab_final_resume');

        const $infoFinal = $form.find('#info_final_resume');
        const $infoFinalOleh = $form.find('#info_final_oleh');
        const $infoFinalTanggal = $form.find('#info_final_tanggal');

        const $btnFinal = $form.find('#btn_final_resume');
        const $btnBatalFinal = $form.find('#btn_batal_final_resume');
        const $btnPrint = $form.find('#btn_preview_resume_ranap');

        // ======================================================
        // DATA EMR
        // ======================================================

        const emrData = window.EMRData || {};

        // Local variable, tidak membuat deklarasi global kunjungan
        const visitNumber = emrData.kunjungan;

        if (!visitNumber) {
            console.error(
                '[Resume Ranap] Nomor kunjungan tidak ditemukan di window.EMRData.'
            );
            return;
        }

        const encodedVisit = encodeURIComponent(visitNumber);

        const baseUrl = `/api/v2/emr/resume/ranap/${encodedVisit}`;
        const previewUrl = `/api/v2/emr/resume/ranap/preview/${encodedVisit}`;
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        // ======================================================
        // PATIENT / FAMILY SIGNATURE
        // ======================================================

        const patientSignatureUrl =
            `${baseUrl}/ttd-pasien`;

        const patientSignatureCanvas = document.getElementById(
            'canvas_signature_resume_ranap_patient'
        );

        const patientSignatureCtx = patientSignatureCanvas
            ? patientSignatureCanvas.getContext('2d')
            : null;

        const patientSignatureName = document.getElementById(
            'resume_ranap_nama_pihak'
        );

        const patientSignatureSaved = document.getElementById(
            'resume_ranap_patient_signature_saved'
        );

        const patientSignatureEditor = document.getElementById(
            'resume_ranap_patient_signature_editor'
        );

        const patientSignatureImage = document.getElementById(
            'resume_ranap_patient_signature_image'
        );

        const patientSignatureSavedNameElement = document.getElementById(
            'resume_ranap_patient_signature_name'
        );

        const patientSignatureSavedAt = document.getElementById(
            'resume_ranap_patient_signature_saved_at'
        );

        const patientSignatureUpdateAction = document.getElementById(
            'resume_ranap_patient_signature_update_action'
        );

        const patientSignatureStatus = document.getElementById(
            'resume_ranap_patient_signature_status'
        );

        const btnClearPatientSignature = document.getElementById(
            'btn_clear_signature_resume_ranap_patient'
        );

        const btnSavePatientSignature = document.getElementById(
            'btn_save_signature_resume_ranap_patient'
        );

        const btnUpdatePatientSignature = document.getElementById(
            'btn_update_signature_resume_ranap_patient'
        );

        let patientSignatureDrawing = false;
        let patientSignatureHasInk = false;
        let patientSignatureExists = false;
        let patientSignatureSaving = false;

        let patientSignatureLastPoint = null;
        let patientSignatureSavedUrl = null;
        let patientSignatureSavedName = '';

        // ======================================================
        // PREPARE PATIENT SIGNATURE CANVAS
        // ======================================================

        let patientSignatureResizeObserver = null;

        function configurePatientSignatureContext() {

            if (!patientSignatureCtx) {
                return;
            }

            patientSignatureCtx.lineWidth = 2.5;
            patientSignatureCtx.lineCap = 'round';
            patientSignatureCtx.lineJoin = 'round';
            patientSignatureCtx.strokeStyle = '#111827';
        }

        function resizePatientSignatureCanvas() {

            if (!patientSignatureCanvas || !patientSignatureCtx) {
                return;
            }

            const rect = patientSignatureCanvas.getBoundingClientRect();

            if (!rect.width || !rect.height) {
                return;
            }

            const ratio = Math.max(
                window.devicePixelRatio || 1,
                1
            );

            const newWidth = Math.round(rect.width * ratio);
            const newHeight = Math.round(rect.height * ratio);

            // ==================================================
            // UKURAN CANVAS TIDAK BERUBAH
            // JANGAN RESET CANVAS
            // ==================================================

            if (
                patientSignatureCanvas.width === newWidth &&
                patientSignatureCanvas.height === newHeight
            ) {

                configurePatientSignatureContext();

                return;
            }

            // ==================================================
            // SIMPAN GAMBAR LAMA SEBELUM RESIZE
            // ==================================================

            let previousCanvas = null;

            if (
                patientSignatureCanvas.width > 0 &&
                patientSignatureCanvas.height > 0
            ) {

                previousCanvas = document.createElement('canvas');

                previousCanvas.width = patientSignatureCanvas.width;
                previousCanvas.height = patientSignatureCanvas.height;

                const previousCtx = previousCanvas.getContext('2d');

                previousCtx.drawImage(
                    patientSignatureCanvas,
                    0,
                    0
                );
            }

            const previousWidth = patientSignatureCanvas.width;
            const previousHeight = patientSignatureCanvas.height;

            // ==================================================
            // RESIZE CANVAS
            // ==================================================

            patientSignatureCanvas.width = newWidth;
            patientSignatureCanvas.height = newHeight;

            patientSignatureCtx.setTransform(
                ratio,
                0,
                0,
                ratio,
                0,
                0
            );

            configurePatientSignatureContext();

            // ==================================================
            // RESTORE DRAWING LAMA JIKA ADA
            // ==================================================

            if (previousCanvas) {

                patientSignatureCtx.drawImage(
                    previousCanvas,
                    0,
                    0,
                    previousWidth,
                    previousHeight,
                    0,
                    0,
                    rect.width,
                    rect.height
                );
            }

            console.log(
                '[Resume Ranap TTE] Canvas initialized:',
                {
                    cssWidth: rect.width,
                    cssHeight: rect.height,
                    canvasWidth: newWidth,
                    canvasHeight: newHeight,
                    ratio: ratio
                }
            );
        }


        // ======================================================
        // CLEAR PATIENT SIGNATURE CANVAS
        // ======================================================

        function clearPatientSignatureCanvas() {

            if (!patientSignatureCanvas || !patientSignatureCtx) {
                return;
            }

            const rect = patientSignatureCanvas.getBoundingClientRect();

            patientSignatureCtx.clearRect(
                0,
                0,
                rect.width,
                rect.height
            );

            patientSignatureHasInk = false;
            patientSignatureLastPoint = null;
            patientSignatureDrawing = false;
        }


        // ======================================================
        // INITIALIZE CANVAS
        // ======================================================

        function initializePatientSignatureCanvas() {

            if (!patientSignatureCanvas || !patientSignatureCtx) {
                return;
            }

            // Mencegah browser menginterpretasikan gesture canvas
            // sebagai scroll / zoom pada area tanda tangan.
            patientSignatureCanvas.style.touchAction = 'none';

            patientSignatureCanvas.style.userSelect = 'none';
            patientSignatureCanvas.style.webkitUserSelect = 'none';

            requestAnimationFrame(() => {

                requestAnimationFrame(() => {

                    resizePatientSignatureCanvas();

                });

            });

            // ==================================================
            // OBSERVE PERUBAHAN UKURAN CANVAS
            // ==================================================

            if (typeof ResizeObserver !== 'undefined') {

                patientSignatureResizeObserver =
                    new ResizeObserver(() => {

                        requestAnimationFrame(() => {

                            resizePatientSignatureCanvas();

                        });

                    });

                patientSignatureResizeObserver.observe(
                    patientSignatureCanvas
                );
            }

            // Fallback resize window
            window.addEventListener(
                'resize',
                function () {

                    requestAnimationFrame(() => {

                        resizePatientSignatureCanvas();

                    });

                }
            );
        }

        initializePatientSignatureCanvas();


        // ======================================================
        // DRAWING PATIENT SIGNATURE
        // ======================================================

        function getPatientSignaturePoint(event) {

            const rect = patientSignatureCanvas.getBoundingClientRect();

            return {
                x: event.clientX - rect.left,
                y: event.clientY - rect.top
            };
        }


        // ======================================================
        // START DRAWING
        // ======================================================

        function startPatientSignatureDrawing(event) {

            if (isFinal || patientSignatureSaving) {
                return;
            }

            if (event.pointerType === 'mouse' && event.button !== 0) {
                return;
            }

            event.preventDefault();

            patientSignatureDrawing = true;
            patientSignatureHasInk = true;

            patientSignatureLastPoint =
                getPatientSignaturePoint(event);

            // Pointer capture agar pointer tetap diterima
            // meskipun bergerak sedikit keluar area canvas.
            try {

                patientSignatureCanvas.setPointerCapture(
                    event.pointerId
                );

            } catch (error) {

                console.debug(
                    '[Resume Ranap TTE] Pointer capture tidak tersedia:',
                    error
                );
            }

            // Buat titik awal terlihat apabila user hanya tap
            patientSignatureCtx.beginPath();

            patientSignatureCtx.arc(
                patientSignatureLastPoint.x,
                patientSignatureLastPoint.y,
                1.25,
                0,
                Math.PI * 2
            );

            patientSignatureCtx.fillStyle = '#111827';
            patientSignatureCtx.fill();
        }


        // ======================================================
        // DRAW
        // ======================================================

        function drawPatientSignature(event) {

            if (
                !patientSignatureDrawing ||
                !patientSignatureLastPoint
            ) {
                return;
            }

            event.preventDefault();

            const point = getPatientSignaturePoint(event);

            patientSignatureCtx.beginPath();

            patientSignatureCtx.moveTo(
                patientSignatureLastPoint.x,
                patientSignatureLastPoint.y
            );

            patientSignatureCtx.lineTo(
                point.x,
                point.y
            );

            patientSignatureCtx.stroke();

            patientSignatureLastPoint = point;
        }


        // ======================================================
        // STOP DRAWING
        // ======================================================

        function stopPatientSignatureDrawing(event) {

            if (!patientSignatureDrawing) {
                return;
            }

            patientSignatureDrawing = false;
            patientSignatureLastPoint = null;

            if (
                event &&
                patientSignatureCanvas.hasPointerCapture?.(event.pointerId)
            ) {

                try {

                    patientSignatureCanvas.releasePointerCapture(
                        event.pointerId
                    );

                } catch (error) {

                    // Pointer sudah dilepas browser.
                }
            }
        }


        // ======================================================
        // PATIENT SIGNATURE EVENTS
        // ======================================================

        if (patientSignatureCanvas && patientSignatureCtx) {

            patientSignatureCanvas.addEventListener(
                'pointerdown',
                startPatientSignatureDrawing
            );

            patientSignatureCanvas.addEventListener(
                'pointermove',
                drawPatientSignature
            );

            patientSignatureCanvas.addEventListener(
                'pointerup',
                stopPatientSignatureDrawing
            );

            patientSignatureCanvas.addEventListener(
                'pointercancel',
                stopPatientSignatureDrawing
            );

            patientSignatureCanvas.addEventListener(
                'lostpointercapture',
                stopPatientSignatureDrawing
            );

        }

        // ======================================================
        // CROP PATIENT SIGNATURE
        // ======================================================
        function cropPatientSignatureCanvas(
            sourceCanvas,
            padding = 24
        ) {

            if (!sourceCanvas) {
                return null;
            }

            const ctx = sourceCanvas.getContext('2d');

            if (!ctx) {
                return null;
            }

            const width = sourceCanvas.width;
            const height = sourceCanvas.height;

            const imageData = ctx.getImageData(
                0,
                0,
                width,
                height
            );

            const pixels = imageData.data;

            let minX = width;
            let minY = height;
            let maxX = -1;
            let maxY = -1;

            /*
            * Canvas menggunakan background transparan.
            * Deteksi tinta berdasarkan alpha.
            */
            for (let y = 0; y < height; y++) {

                for (let x = 0; x < width; x++) {

                    const index = (y * width + x) * 4;

                    const alpha = pixels[index + 3];

                    if (alpha > 10) {

                        minX = Math.min(minX, x);
                        minY = Math.min(minY, y);

                        maxX = Math.max(maxX, x);
                        maxY = Math.max(maxY, y);
                    }
                }
            }

            if (maxX < minX || maxY < minY) {
                return null;
            }

            /*
            * Padding dalam pixel canvas,
            * bukan CSS pixel.
            */
            const ratio = Math.max(
                window.devicePixelRatio || 1,
                1
            );

            const extraPadding = Math.round(padding * ratio);

            const cropX = Math.max(
                0,
                minX - extraPadding
            );

            const cropY = Math.max(
                0,
                minY - extraPadding
            );

            const cropRight = Math.min(
                width,
                maxX + extraPadding + 1
            );

            const cropBottom = Math.min(
                height,
                maxY + extraPadding + 1
            );

            const cropWidth = cropRight - cropX;
            const cropHeight = cropBottom - cropY;

            const croppedCanvas = document.createElement('canvas');

            croppedCanvas.width = cropWidth;
            croppedCanvas.height = cropHeight;

            const croppedCtx = croppedCanvas.getContext('2d');

            croppedCtx.drawImage(
                sourceCanvas,
                cropX,
                cropY,
                cropWidth,
                cropHeight,
                0,
                0,
                cropWidth,
                cropHeight
            );

            return croppedCanvas.toDataURL('image/png');
        }

        // ======================================================
        // DISPLAY SAVED PATIENT SIGNATURE
        // ======================================================
        function setPatientSignatureSaved(data) {

            patientSignatureExists = Boolean(data);
            patientSignatureSavedUrl = data?.signature || null;
            patientSignatureSavedName = data?.nama_pihak || '';

            if (patientSignatureName) {
                patientSignatureName.value =
                    patientSignatureSavedName;
            }

            if (patientSignatureExists && patientSignatureSavedUrl) {

                patientSignatureImage.src =
                    patientSignatureSavedUrl;

                patientSignatureSavedNameElement.textContent =
                    patientSignatureSavedName || '-';

                patientSignatureSavedAt.textContent =
                    data.updated_at
                        ? `Diperbarui: ${data.updated_at}`
                        : '';

                patientSignatureSaved.classList.remove('d-none');

                patientSignatureEditor.classList.add('d-none');

                patientSignatureUpdateAction.classList.toggle(
                    'd-none',
                    isFinal
                );

            } else {

                patientSignatureSaved.classList.add('d-none');

                patientSignatureEditor.classList.remove('d-none');

                patientSignatureUpdateAction.classList.add('d-none');
            }

            updatePatientSignatureStatus();
        }

        function updatePatientSignatureStatus() {

            if (!patientSignatureStatus) {
                return;
            }

            if (isFinal) {

                patientSignatureStatus.className =
                    'resume-ranap-patient-signature-status alert alert-success';

                patientSignatureStatus.innerHTML = `
                    <i class="ph-duotone ph-lock-key me-1"></i>
                    Resume Medis telah difinalisasi. Tanda tangan terkunci.
                `;

                patientSignatureStatus.classList.remove('d-none');

                patientSignatureEditor.classList.add('d-none');
                patientSignatureUpdateAction.classList.add('d-none');

                return;
            }

            if (patientSignatureExists) {

                patientSignatureStatus.className =
                    'resume-ranap-patient-signature-status alert alert-success';

                patientSignatureStatus.innerHTML = `
                    <i class="ph-duotone ph-check-circle me-1"></i>
                    Tanda tangan pasien/keluarga telah tersimpan.
                `;

            } else {

                patientSignatureStatus.className =
                    'resume-ranap-patient-signature-status alert alert-warning';

                patientSignatureStatus.innerHTML = `
                    <i class="ph-duotone ph-info me-1"></i>
                    Tanda tangan pasien/keluarga belum tersedia.
                `;
            }

            patientSignatureStatus.classList.remove('d-none');
        }

        // ======================================================
        // LOAD SAVED PATIENT SIGNATURE
        // ======================================================
        async function loadPatientSignature() {

            try {

                const response = await $.ajax({
                    url: patientSignatureUrl,
                    type: 'GET',
                    dataType: 'json'
                });

                if (response.success && response.exists) {

                    setPatientSignatureSaved(
                        response.data
                    );

                } else {

                    setPatientSignatureSaved(null);
                }

            } catch (xhr) {

                console.error(
                    '[Resume Ranap Patient Signature] Gagal memuat TTE:',
                    xhr.responseText || xhr
                );

                notify(
                    'error',
                    'Gagal Memuat TTE Pasien',
                    xhr.responseJSON?.message ||
                        'Tanda tangan pasien/keluarga tidak dapat dimuat.'
                );
            }
        }

        // ======================================================
        // SAVE / UPDATE PATIENT SIGNATURE
        // ======================================================
        async function savePatientSignature() {
            const namaPihakTTE = ($('#resume_ranap_nama_pihak').val() || '').trim();

            // =========================================
            // VALIDASI NAMA PASIEN / KELUARG
            // =========================================
            if (!namaPihakTTE) {
                notify(
                    'error',
                    'Validasi TTE',
                    'Nama Pasien atau Keluarga wajib diisi sebelum menyimpan TTE.'
                );

                // $('#patient_signature_name').focus();
                return;
            }

            // =========================================
            // VALIDASI TTE
            // =========================================
            if (!patientSignatureHasInk) {
                notify(
                    'error',
                    'Validasi TTE',
                    'Tanda tangan wajib dibuat terlebih dahulu.'
                );
                return;
            }

            // =========================================
            // VALIDASI STATUS FINALISASI
            // =========================================
            if (isFinal) {
                notify(
                    'warning',
                    'Resume Medis',
                    'Resume medis sudah difinalisasi. Batalkan finalisasi terlebih dahulu.'
                );
                return;
            }

            if (patientSignatureSaving || isSaving || isLoading) {
                return;
            }

            if (isFinal) {

                notify(
                    'warning',
                    'Resume sudah final',
                    'Batalkan finalisasi terlebih dahulu untuk memperbarui tanda tangan.'
                );

                return;
            }

            if (patientSignatureSaving || isSaving || isLoading) {
                return;
            }

            const namaPihak = (
                patientSignatureName?.value || ''
            ).trim();

            if (!namaPihak) {

                notify(
                    'warning',
                    'Nama belum diisi',
                    'Silakan isi nama pasien atau keluarga yang menandatangani.'
                );

                patientSignatureName?.focus();

                return;
            }

            if (!patientSignatureHasInk) {

                notify(
                    'warning',
                    'Tanda tangan kosong',
                    'Silakan bubuhkan tanda tangan terlebih dahulu.'
                );

                return;
            }

            const signature = cropPatientSignatureCanvas(
                patientSignatureCanvas
            );

            if (!signature) {

                notify(
                    'warning',
                    'Tanda tangan tidak ditemukan',
                    'Silakan ulangi tanda tangan pada area yang tersedia.'
                );

                return;
            }

            patientSignatureSaving = true;

            btnSavePatientSignature.disabled = true;
            btnClearPatientSignature.disabled = true;

            btnSavePatientSignature.innerHTML = `
                <span class="spinner-border spinner-border-sm me-1"></span>
                Menyimpan TTE...
            `;

            try {

                const response = await $.ajax({
                    url: patientSignatureUrl,
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: {
                        nama_pihak: namaPihak,
                        signature: signature
                    }
                });

                clearPatientSignatureCanvas();

                await loadPatientSignature();

                notify(
                    'success',
                    'TTE Berhasil Disimpan',
                    response.message ||
                        'Tanda tangan pasien/keluarga berhasil disimpan.'
                );

            } catch (xhr) {

                let message =
                    'Tanda tangan pasien/keluarga gagal disimpan.';

                if (xhr.responseJSON?.errors) {

                    message = Object
                        .values(xhr.responseJSON.errors)
                        .flat()
                        .join('\n');

                } else if (xhr.responseJSON?.message) {

                    message = xhr.responseJSON.message;
                }

                notify(
                    'error',
                    'Gagal Menyimpan TTE',
                    message
                );

                console.error(
                    '[Resume Ranap Patient Signature] Error:',
                    xhr.responseText || xhr
                );

            } finally {

                patientSignatureSaving = false;

                btnSavePatientSignature.disabled = isFinal;
                btnClearPatientSignature.disabled = isFinal;

                btnSavePatientSignature.innerHTML = `
                    <i class="ph-duotone ph-floppy-disk me-1"></i>
                    <span>
                        ${patientSignatureExists
                            ? 'Simpan Perubahan TTE'
                            : 'Simpan Tanda Tangan'}
                    </span>
                `;

                updatePatientSignatureStatus();
            }
        }

        // ======================================================
        // EDIT / UPDATE SIGNATURE
        // ======================================================
        function editPatientSignature() {

            if (isFinal) {
                return;
            }

            patientSignatureSaved.classList.add('d-none');
            patientSignatureUpdateAction.classList.add('d-none');

            patientSignatureEditor.classList.remove('d-none');

            requestAnimationFrame(() => {

                resizePatientSignatureCanvas();

                clearPatientSignatureCanvas();

            });

            btnSavePatientSignature.innerHTML = `
                <i class="ph-duotone ph-floppy-disk me-1"></i>
                <span>Simpan Perubahan TTE</span>
            `;
        }

        // ======================================================
        // PATIENT SIGNATURE EVENTS
        // ======================================================
        btnClearPatientSignature?.addEventListener(
            'click',
            function () {

                if (isFinal || patientSignatureSaving) {
                    return;
                }

                clearPatientSignatureCanvas();
            }
        );

        btnSavePatientSignature?.addEventListener(
            'click',
            savePatientSignature
        );

        btnUpdatePatientSignature?.addEventListener(
            'click',
            editPatientSignature
        );

        // ======================================================
        // STATE
        // ======================================================

        let isLoading = false;
        let isSaving = false;
        let isFinal = false;

        let saveTimer = null;

        let finalMeta = {
            oleh_nama: '',
            tgl_difinal: ''
        };

        // ======================================================
        // FINAL META
        // ======================================================

        function setFinalMeta(response = {}) {

            finalMeta = {
                oleh_nama: response.oleh_nama ?? '',
                tgl_difinal:
                    response.tgl_difinal_text ??
                    response.tgl_difinal ??
                    ''
            };
        }

        // ======================================================
        // UPDATE TAB FINAL STATUS
        // ======================================================

        function updateTabFinalStatus() {

            if (!$tabResume.length) return;

            if (isLoading) {

                $tabBadge
                    .removeClass(
                        'bg-success bg-danger bg-warning text-dark'
                    )
                    .addClass('bg-dark-subtle text-body-secondary')
                    .html('<i class="ri-loader-line ri-spin"></i>');

                $tabResume.attr(
                    'title',
                    'Memuat status Resume Medis...'
                );

                return;
            }

            if (isFinal) {

                $tabBadge
                    .removeClass(
                        'bg-danger bg-warning text-dark bg-dark-subtle text-body-secondary'
                    )
                    .addClass('bg-success')
                    .text('Sudah Final');

                const detail = [];

                if (finalMeta.oleh_nama) {
                    detail.push(`Difinalkan oleh ${finalMeta.oleh_nama}`);
                }

                if (finalMeta.tgl_difinal) {
                    detail.push(finalMeta.tgl_difinal);
                }

                $tabResume.attr(
                    'title',
                    detail.join(' · ') || 'Resume Medis sudah difinalisasi'
                );

            } else {

                $tabBadge
                    .removeClass(
                        'bg-success bg-warning text-dark bg-dark-subtle text-body-secondary'
                    )
                    .addClass('bg-danger')
                    .text('Belum Final');

                $tabResume.attr(
                    'title',
                    'Resume Medis belum difinalisasi'
                );
            }
        }

        // ======================================================
        // UPDATE INFORMASI FINAL
        // ======================================================

        function updateInfoFinalResume() {

            if (isLoading) {
                $infoFinal.addClass('d-none');
                return;
            }

            if (isFinal) {

                $infoFinalOleh.text(
                    finalMeta.oleh_nama || 'Petugas tidak diketahui'
                );

                $infoFinalTanggal.text(
                    finalMeta.tgl_difinal || '-'
                );

                $infoFinal.removeClass('d-none');

            } else {

                $infoFinal.addClass('d-none');

                $infoFinalOleh.text('');
                $infoFinalTanggal.text('');
            }
        }

        // ======================================================
        // NOTIFICATION
        // ======================================================

        function notify(type, title, message) {

            if (typeof iziToast !== 'undefined' && iziToast[type]) {

                iziToast[type]({
                    title: title,
                    message: message,
                    position: 'topRight',
                    timeout: 3500
                });

                return;
            }

            console[type === 'error' ? 'error' : 'log'](
                `${title}: ${message}`
            );
        }

        // ======================================================
        // CONFIRMATION
        // ======================================================

        async function confirmAction(title, message, confirmText) {

            if (typeof Swal !== 'undefined') {

                const result = await Swal.fire({
                    title: title,
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: confirmText,
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                });

                return result.isConfirmed;
            }

            return window.confirm(message);
        }

        // ======================================================
        // UPDATE FORM STATUS
        // ======================================================
        function updateFormStatus() {

            $fieldset.prop(
                'disabled',
                isLoading || isSaving || isFinal
            );

            if (isLoading) {

                $badge
                    .removeClass(
                        'bg-success bg-danger bg-warning text-dark'
                    )
                    .addClass('bg-dark-subtle text-body-secondary')
                    .html('<i class="ri-loader-line ri-spin"></i>');

            } else if (isFinal) {

                $badge
                    .removeClass(
                        'bg-danger bg-warning text-dark bg-dark-subtle text-body-secondary'
                    )
                    .addClass('bg-success')
                    .text('Sudah Final');

                $btnFinal.addClass('d-none');
                $btnBatalFinal.removeClass('d-none');

            } else {

                $badge
                    .removeClass(
                        'bg-success bg-warning text-dark bg-dark-subtle text-body-secondary'
                    )
                    .addClass('bg-danger')
                    .text('Belum Final');

                $btnFinal.removeClass('d-none');
                $btnBatalFinal.addClass('d-none');
            }

            updateTabFinalStatus();
            updateInfoFinalResume();

            const busy = isLoading || isSaving;

            $btnFinal.prop('disabled', busy);
            $btnBatalFinal.prop('disabled', busy);
            $btnPrint.prop('disabled', busy);

            // ======================================================
            // PATIENT SIGNATURE STATUS
            // ======================================================

            if (btnSavePatientSignature) {
                btnSavePatientSignature.disabled =
                    isFinal || patientSignatureSaving;
            }

            if (btnClearPatientSignature) {
                btnClearPatientSignature.disabled =
                    isFinal || patientSignatureSaving;
            }

            if (btnUpdatePatientSignature) {
                btnUpdatePatientSignature.disabled =
                    isFinal || patientSignatureSaving;
            }

            if (patientSignatureName) {
                patientSignatureName.disabled =
                    isFinal || patientSignatureSaving;
            }

            updatePatientSignatureStatus();
        }

        // ======================================================
        // GET FORM DATA
        // ======================================================

        function getFormData() {

            const data = {};

            $fieldset.find('[name]').each(function () {

                const field = this;
                const name = field.name;
                const type = (field.type || '').toLowerCase();

                if (type === 'radio') {

                    if (!(name in data)) {
                        data[name] = null;
                    }

                    if (field.checked) {
                        data[name] = field.value;
                    }

                    return;
                }

                if (type === 'checkbox') {

                    data[name] = field.checked
                        ? (field.value || 1)
                        : null;

                    return;
                }

                const value = $(field).val();

                data[name] = value === '' ? null : value;
            });

            return data;
        }

        // ======================================================
        // SET FIELD VALUE
        // ======================================================

        function setFieldValue(section, name, value = null) {

            const $fields = section.find(`[name="${name}"]`);

            if (!$fields.length) return;

            const type = ($fields.first().attr('type') || '').toLowerCase();

            if (type === 'radio') {

                $fields.each(function () {
                    this.checked =
                        value !== null &&
                        String(this.value) === String(value);
                });

                return;
            }

            if (type === 'checkbox') {

                $fields.each(function () {
                    this.checked =
                        value !== null &&
                        String(this.value) === String(value);
                });

                return;
            }

            $fields.val(value ?? '');
        }

        // ======================================================
        // LOAD DATA
        // ======================================================

        async function getData() {

            if (isLoading) return;

            isLoading = true;
            updateFormStatus();

            try {

                const response = await $.ajax({
                    url: baseUrl,
                    type: 'GET',
                    dataType: 'json'
                });

                const data = response.data || {};
                const old = response.old || {};
                const ref = response.ref || {};

                // Helper fallback data
                const fieldData = (name) => getFirstDefined(
                    data[name],
                    old[name],
                    ''
                );

                // ==================================================
                // TEXT FIELDS
                // ==================================================

                const fields = [
                    'indikasi',
                    'rps',
                    'rpd',
                    'rpm',
                    'ra',
                    'pemeriksaan_fisik',
                    'diagnosa_utama',
                    'diagnosa_sekunder',
                    'diagnosa_dokter_icd10',
                    'diagnosa_perawat',
                    'diagnosa_dokter_icd9',
                    'terapi',
                    'kondisi_keluar',
                    'cara_keluar',
                    'ku_td_up',
                    'ku_td_down',
                    'ku_nafas',
                    'ku_sn',
                    'ku_nadi',
                    'ku_suhu',
                    'edukasi'
                ];

                fields.forEach(name => {
                    setFieldValue($form, name, fieldData(name));
                });

                // ==================================================
                // TINGKAT KESADARAN REFERENCE
                // ==================================================

                const $selectKesadaran = $form.find('[name="ku_tk"]');

                if ($selectKesadaran.length) {

                    $selectKesadaran.empty().append(
                        $('<option>', {
                            value: '',
                            text: 'Pilih Tingkat Kesadaran'
                        })
                    );

                    const tingkatKesadaran = Array.isArray(ref.tingkat_kesadaran)
                        ? ref.tingkat_kesadaran
                        : [];

                    tingkatKesadaran.forEach(item => {

                        $selectKesadaran.append(
                            $('<option>', {
                                value: item.ID,
                                text: item.DESKRIPSI
                            })
                        );
                    });
                }

                setFieldValue(
                    $form,
                    'ku_tk',
                    fieldData('ku_tk')
                );

                // ==================================================
                // STATUS FINAL
                // ==================================================

                isFinal = Number(data.status_final ?? 0) === 1;

                setFinalMeta(response);

                updateFormStatus();

            } catch (xhr) {

                console.error(
                    '[Resume Ranap] Gagal mengambil data:',
                    xhr.responseText || xhr
                );

                notify(
                    'error',
                    'Gagal Memuat',
                    xhr.responseJSON?.message ||
                    'Gagal mengambil data Resume Medis.'
                );

            } finally {

                isLoading = false;
                updateFormStatus();
            }
        }

        // ======================================================
        // SAVE DATA
        // ======================================================

        async function simpanData(finalisasi = false) {

            if (isLoading || isSaving || isFinal) {
                return false;
            }

            isSaving = true;
            updateFormStatus();

            const payload = {
                ...getFormData(),
                NOKUNJ: visitNumber,
                finalisasi: finalisasi ? 1 : 0
            };

            try {

                const response = await $.ajax({
                    url: `${baseUrl}/simpan`,
                    type: 'POST',
                    data: payload,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    dataType: 'json'
                });

                isFinal = Number(
                    response.data?.status_final ?? (finalisasi ? 1 : 0)
                ) === 1;

                setFinalMeta(response.data || {});

                if (finalisasi) {

                    notify(
                        'success',
                        'Finalisasi Berhasil',
                        'Resume Medis berhasil difinalisasi.'
                    );

                } else if (!isFinal) {

                    notify(
                        'success',
                        'Berhasil',
                        'Data Resume Medis berhasil disimpan.'
                    );
                }

                updateFormStatus();

                return true;

            } catch (xhr) {

                let message = 'Data Resume Medis gagal disimpan.';

                if (xhr.status === 422 && xhr.responseJSON?.errors) {

                    message = Object
                        .values(xhr.responseJSON.errors)
                        .flat()
                        .join('\n');

                } else if (xhr.responseJSON?.message) {

                    message = xhr.responseJSON.message;
                }

                notify(
                    'error',
                    'Gagal Menyimpan',
                    message
                );

                console.error(
                    '[Resume Ranap] Error simpan:',
                    xhr.responseText || xhr
                );

                return false;

            } finally {

                isSaving = false;
                updateFormStatus();
            }
        }

        // ======================================================
        // BATAL FINAL
        // ======================================================

        async function batalFinal() {

            if (isLoading || isSaving || !isFinal) {
                return;
            }

            const confirmed = await confirmAction(
                'Batal Finalisasi?',
                'Resume Medis akan dibuka kembali dan dapat diedit.',
                'Ya, Batal Final'
            );

            if (!confirmed) return;

            isSaving = true;
            updateFormStatus();

            try {

                const response = await $.ajax({
                    url: `${baseUrl}/batal`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    dataType: 'json'
                });

                isFinal = false;

                finalMeta = {
                    oleh_nama: '',
                    tgl_difinal: ''
                };

                updateFormStatus();

                notify(
                    'success',
                    'Berhasil',
                    response.message ||
                    'Finalisasi Resume Medis berhasil dibatalkan.'
                );

            } catch (xhr) {

                notify(
                    'error',
                    'Gagal',
                    xhr.responseJSON?.message ||
                    'Gagal membatalkan finalisasi Resume Medis.'
                );

                console.error(
                    '[Resume Ranap] Error batal final:',
                    xhr.responseText || xhr
                );

            } finally {

                isSaving = false;
                updateFormStatus();
            }
        }

        // ======================================================
        // AUTO SAVE
        // ======================================================
        function scheduleAutoSave() {

            if (isLoading || isSaving || isFinal) {
                return;
            }

            clearTimeout(saveTimer);

            saveTimer = setTimeout(function () { // Waktu Otomatis Menyimpan Form
                simpanData(false);
            }, 4000); // 1 detik = 1000
        }

        // ======================================================
        // EVENTS
        // ======================================================

        $form.on(
            'input.resumeRanap',
            '#resume_fields textarea, #resume_fields input:not([type="radio"]):not([type="checkbox"])',
            function () {
                scheduleAutoSave();
            }
        );

        $form.on(
            'change.resumeRanap',
            '#resume_fields select, #resume_fields input[type="radio"], #resume_fields input[type="checkbox"]',
            function () {
                scheduleAutoSave();
            }
        );

        // ======================================================
        // FINALISASI
        // ======================================================

        $btnFinal.on('click.resumeRanap', async function () {

            clearTimeout(saveTimer);

            const confirmed = await confirmAction(
                'Finalisasi Resume Medis?',
                'Setelah difinalisasi, seluruh isian Resume Medis akan dikunci dan tidak dapat diubah sampai dilakukan Batal Final.',
                'Ya, Finalisasi'
            );

            if (!confirmed) return;

            await simpanData(true);
        });

        // ======================================================
        // BATAL FINAL
        // ======================================================

        $btnBatalFinal.on('click.resumeRanap', function () {
            clearTimeout(saveTimer);
            batalFinal();
        });

        // ======================================================
        // PRINT PREVIEW
        // ======================================================

        $btnPrint.on('click.resumeRanap', async function () {

            clearTimeout(saveTimer);

            if (isLoading || isSaving) return;

            // Jika belum final, simpan terlebih dahulu
            if (!isFinal) {

                const saved = await simpanData(false);

                if (!saved) return;
            }

            // Gunakan modal preview internal, bukan popup window
            if (window.ResumeRanapPreview) {
                window.ResumeRanapPreview.open(previewUrl);
            } else {
                console.error(
                    '[Resume Ranap] ResumeRanapPreview belum diinisialisasi.'
                );
            }
        });

        // ======================================================
        // LOAD AWAL
        // ======================================================
        getData();
        loadPatientSignature();
    }

    // ==========================================================
    // 3. PDF PREVIEW MODAL
    // ==========================================================

    function initResumeRanapPreview() {

        const modalElement = document.getElementById(
            'modal_preview_resume_ranap'
        );

        if (!modalElement || modalElement.dataset.previewInitialized === '1') {
            return;
        }

        modalElement.dataset.previewInitialized = '1';

        const iframe = document.getElementById(
            'resume_ranap_preview_iframe'
        );

        const loading = document.getElementById(
            'resume_ranap_preview_loading'
        );

        const error = document.getElementById(
            'resume_ranap_preview_error'
        );

        const status = document.getElementById(
            'resume_ranap_preview_status'
        );

        const btnRefresh = document.getElementById(
            'btn_refresh_preview_resume_ranap'
        );

        const btnPrint = document.getElementById(
            'btn_print_preview_resume_ranap'
        );

        const btnRetry = document.getElementById(
            'btn_retry_preview_resume_ranap'
        );

        if (!iframe) {
            console.error('[Resume Ranap Preview] Iframe PDF tidak ditemukan.');
            return;
        }

        // ======================================================
        // STATE
        // ======================================================

        let currentPreviewUrl = null;
        let currentBlobUrl = null;
        let requestController = null;
        let requestSequence = 0;

        const previewModal = bootstrap.Modal.getOrCreateInstance(
            modalElement
        );

        // ======================================================
        // LOADING STATE
        // ======================================================

        function showLoading() {

            loading?.classList.remove('d-none');
            error?.classList.add('d-none');

            iframe.classList.add('d-none');

            if (status) {
                status.className =
                    'resume-ranap-preview-status badge bg-secondary-subtle text-secondary';

                status.innerHTML = `
                    <i class="ph-duotone ph-spinner-gap me-1"></i>
                    Memuat
                `;
            }
        }

        // ======================================================
        // SUCCESS STATE
        // ======================================================

        function showSuccess() {

            loading?.classList.add('d-none');
            error?.classList.add('d-none');

            iframe.classList.remove('d-none');

            if (status) {
                status.className =
                    'resume-ranap-preview-status badge bg-success-subtle text-success';

                status.innerHTML = `
                    <i class="ph-duotone ph-check-circle me-1"></i>
                    Final
                `;
            }
        }

        // ======================================================
        // ERROR STATE
        // ======================================================
        function showError(message = 'Terjadi kesalahan saat menampilkan dokumen Resume Medis.') {

            loading?.classList.add('d-none');
            error?.classList.remove('d-none');

            if (iframe) {
                iframe.classList.add('d-none');
            }

            // Tampilkan pesan error dari controller
            const errorMessage = document.getElementById(
                'resume_ranap_preview_error_message'
            );

            if (errorMessage) {
                errorMessage.textContent = message;
            }

            if (status) {
                status.className =
                    'resume-ranap-preview-status badge bg-danger-subtle text-danger';

                status.innerHTML = `
                    <i class="ph-duotone ph-warning-circle me-1"></i>
                    Gagal
                `;
            }
        }

        // ======================================================
        // CLEANUP BLOB
        // ======================================================
        function revokeBlobUrl() {

            if (!currentBlobUrl) return;

            URL.revokeObjectURL(currentBlobUrl);
            currentBlobUrl = null;
        }

        // ======================================================
        // CANCEL REQUEST
        // ======================================================
        function cancelCurrentRequest() {

            if (requestController) {
                requestController.abort();
                requestController = null;
            }
        }

        // ======================================================
        // LOAD PDF
        // ======================================================
        async function loadPreview(url) {

            if (!url) {
                showError();
                return;
            }

            cancelCurrentRequest();

            const sequence = ++requestSequence;

            requestController = new AbortController();

            currentPreviewUrl = url;

            showLoading();
            revokeBlobUrl();

            iframe.onload = null;
            iframe.onerror = null;
            iframe.src = 'about:blank';

            try {

                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        Accept: 'application/pdf'
                    },
                    cache: 'no-store',
                    signal: requestController.signal
                });

                if (sequence !== requestSequence) return;

                // =====================================================
                // HTTP ERROR HANDLING
                // =====================================================

                if (!response.ok) {

                    let message = `Gagal memuat PDF Resume Medis (HTTP ${response.status}).`;

                    const contentType = response.headers.get('content-type') || '';

                    if (!contentType.toLowerCase().includes('application/pdf')) {

                        let message = 'Response server bukan file PDF.';

                        if (
                            contentType.toLowerCase().includes('application/json')
                        ) {

                            try {
                                const result = await response.json();
                                message = result.message || message;
                            } catch (_) {}

                        } else if (
                            contentType.toLowerCase().includes('text/html')
                        ) {

                            message = 'Server mengembalikan halaman HTML. Kemungkinan sesi login berakhir atau terjadi error pada server.';
                        }

                        throw new Error(message);
                    }
                }

                const contentType =
                    response.headers.get('content-type') || '';

                if (!contentType.toLowerCase().includes('application/pdf')) {
                    throw new Error(
                        'Response server bukan file PDF.'
                    );
                }

                const blob = await response.blob();

                if (sequence !== requestSequence) return;

                if (!blob || blob.size === 0) {
                    throw new Error('File PDF kosong.');
                }

                currentBlobUrl = URL.createObjectURL(blob);

                iframe.onload = function () {

                    if (sequence !== requestSequence) return;

                    showSuccess();
                };

                iframe.onerror = function () {

                    if (sequence !== requestSequence) return;

                    showError();
                };

                iframe.src = currentBlobUrl;

            } catch (err) {

                if (sequence !== requestSequence) return;

                if (err.name === 'AbortError') {
                    return;
                }

                console.error('[Resume Ranap Preview]', err);

                showError(
                    err.message ||
                    'Terjadi kesalahan saat menampilkan PDF Resume Medis Rawat Inap.'
                );

                // isLoading = false;

            } finally {

                if (sequence === requestSequence) {
                    requestController = null;
                }
            }
        }

        // ======================================================
        // OPEN MODAL
        // ======================================================

        function openPreview(url) {

            if (!url) {
                console.error(
                    '[Resume Ranap Preview] URL tidak tersedia.'
                );
                return;
            }

            currentPreviewUrl = url;

            previewModal.show();

            loadPreview(url);
        }

        // ======================================================
        // REFRESH
        // ======================================================

        btnRefresh?.addEventListener('click', function () {

            if (!currentPreviewUrl) return;

            loadPreview(currentPreviewUrl);
        });

        // ======================================================
        // RETRY
        // ======================================================

        btnRetry?.addEventListener('click', function () {

            if (!currentPreviewUrl) return;

            loadPreview(currentPreviewUrl);
        });

        // ======================================================
        // PRINT
        // ======================================================

        btnPrint?.addEventListener('click', function () {

            if (!currentBlobUrl) {
                console.warn(
                    '[Resume Ranap Preview] PDF belum tersedia untuk dicetak.'
                );
                return;
            }

            try {

                iframe.contentWindow.focus();
                iframe.contentWindow.print();

            } catch (err) {

                console.error(
                    '[Resume Ranap Print]',
                    err
                );

                window.open(currentBlobUrl, '_blank');
            }
        });

        // ======================================================
        // MODAL CLOSED
        // ======================================================

        modalElement.addEventListener('hidden.bs.modal', function () {

            cancelCurrentRequest();

            requestSequence++;

            revokeBlobUrl();

            currentPreviewUrl = null;

            iframe.onload = null;
            iframe.onerror = null;
            iframe.src = 'about:blank';
            iframe.classList.add('d-none');

            loading?.classList.remove('d-none');
            error?.classList.add('d-none');

            if (status) {
                status.className =
                    'resume-ranap-preview-status badge bg-success-subtle text-success';

                status.innerHTML = `
                    <i class="ph-duotone ph-check-circle me-1"></i>
                    Final
                `;
            }
        });

        // ======================================================
        // PUBLIC API
        // ======================================================

        window.ResumeRanapPreview = {
            open: openPreview,

            refresh: function () {
                if (currentPreviewUrl) {
                    loadPreview(currentPreviewUrl);
                }
            }
        };
    }

    // ==========================================================
    // 4. INITIALIZE
    // ==========================================================

    function initializeResumeRanap() {

        initResumeInfoModal();
        initResumeRanapPreview();
        initResumeRanapForm();
    }

    // DOM ready
    $(initializeResumeRanap);

})(jQuery);
