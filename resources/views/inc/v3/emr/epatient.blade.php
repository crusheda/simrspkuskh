{{-- MODAL DIGITAL SEP --}}
<div
    class="modal fade"
    id="showSEPModal"
    tabindex="-1"
    aria-labelledby="showSEPModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="showSEPModalLabel">
                    <i class="bi bi-file-earmark-medical text-primary me-2"></i>
                    Digital SEP
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Tutup"
                ></button>
            </div>

            <div class="modal-body p-2">

                <div id="sepPreview" class="w-100">

                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-file-earmark-pdf fs-1"></i>
                        <div class="mt-2">
                            PDF SEP akan ditampilkan di sini.
                        </div>
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    <i class="bi bi-x-lg me-1"></i>
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>

<script>
(function () {
    const modalSEP = document.getElementById('showSEPModal');
    const previewSEP = document.getElementById('sepPreview');

    if (!modalSEP || !previewSEP) {
        return;
    }

    let currentPdfUrl = null;
    let abortController = null;

    function showLoading() {
        previewSEP.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>

                <div class="mt-3 text-muted">
                    Sedang mengambil dokumen SEP...
                </div>
            </div>
        `;
    }

    function showError(message) {
        previewSEP.innerHTML = `
            <div class="alert alert-warning m-3">
                <i class="bi bi-exclamation-triangle me-2"></i>
                ${message}
            </div>
        `;
    }

    function cleanupPdf() {
        if (currentPdfUrl) {
            URL.revokeObjectURL(currentPdfUrl);
            currentPdfUrl = null;
        }
    }

    async function loadSEP() {
        cleanupPdf();

        if (abortController) {
            abortController.abort();
        }

        abortController = new AbortController();

        showLoading();

        try {
            // Ambil kunjungan dari tab EMR yang sedang aktif
            const activeTab = window.EMRWorkspace?.getActiveTab?.();

            const kunjungan = activeTab?.nomor;

            if (!kunjungan) {
                throw new Error(
                    'Tidak ditemukan kunjungan pasien yang sedang aktif.'
                );
            }

            const response = await fetch(
                `/api/pasien/${encodeURIComponent(kunjungan)}/sep`,
                {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/pdf'
                    },
                    signal: abortController.signal
                }
            );

            if (!response.ok) {
                throw new Error(
                    'Dokumen SEP tidak ditemukan atau gagal diambil.'
                );
            }

            const blob = await response.blob();

            if (!blob.size) {
                throw new Error('Dokumen SEP kosong.');
            }

            currentPdfUrl = URL.createObjectURL(
                new Blob([blob], { type: 'application/pdf' })
            );

            previewSEP.innerHTML = `
                <iframe
                    src="${currentPdfUrl}"
                    width="100%"
                    height="75vh"
                    style="
                        width: 100%;
                        height: 75vh;
                        border: 0;
                        border-radius: 6px;
                    "
                    title="Preview Digital SEP"
                ></iframe>
            `;

        } catch (error) {
            if (error.name === 'AbortError') {
                return;
            }

            console.error('Gagal memuat SEP:', error);

            showError(
                error.message ||
                'Data SEP tidak ditemukan atau belum dibuatkan oleh SIMGOS.'
            );

            if (window.iziToast) {
                iziToast.error({
                    title: 'Maaf!',
                    message: 'Data SEP tidak ditemukan atau gagal dimuat.',
                    position: 'topRight'
                });
            }
        }
    }

    // Modal dibuka
    modalSEP.addEventListener('shown.bs.modal', loadSEP);

    // Modal ditutup
    modalSEP.addEventListener('hidden.bs.modal', function () {
        if (abortController) {
            abortController.abort();
            abortController = null;
        }

        cleanupPdf();

        previewSEP.innerHTML = `
            <div class="text-center py-5 text-muted">
                <i class="bi bi-file-earmark-pdf fs-1"></i>
                <div class="mt-2">
                    PDF SEP akan ditampilkan di sini.
                </div>
            </div>
        `;
    });
})();
</script>
