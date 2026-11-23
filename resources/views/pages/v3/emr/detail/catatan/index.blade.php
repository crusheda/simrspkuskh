{{-- ============================================================
    MODAL CATATAN
============================================================ --}}
<div class="modal fade"
    id="modalCatatanEMR"
    tabindex="-1"
    aria-labelledby="modalCatatanEMRLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">

        <div class="modal-content emr-catatan-modal">

            {{-- HEADER --}}
            <div class="modal-header emr-catatan-header">

                <div class="d-flex align-items-center gap-3">

                    <div class="emr-catatan-modal-icon">
                        <i class="ph-duotone ph-note"></i>
                    </div>

                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modalCatatanEMRLabel">
                            Catatan Pasien
                        </h5>

                        <div class="small text-body-secondary">
                            Catatan Asuransi & Catatan Internal
                        </div>
                    </div>

                </div>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup">
                </button>

            </div>

            {{-- PATIENT INFO --}}
            <div class="emr-catatan-patient-bar">
                <div class="emr-catatan-patient-item">
                    <i class="ph-duotone ph-user"></i>
                    <span id="catatan-patient-name">{{ $list['show']->NAMAPASIEN }}</span>
                </div>

                <div class="emr-catatan-patient-item">
                    <i class="ph-duotone ph-identification-card"></i>
                    <span>RM <strong>{{ $list['show']->NORM }}</strong></span>
                </div>

                <div class="emr-catatan-patient-item">
                    <i class="ph-duotone ph-bed"></i>
                    <span>{{ $list['show']->NAMARUANGAN }}</span>
                </div>
            </div>

            {{-- BODY --}}
            <div class="modal-body emr-catatan-body">

                {{-- TABS --}}
                <ul class="nav nav-pills emr-catatan-tabs mb-3"
                    id="catatanTab"
                    role="tablist">

                    <li class="nav-item" role="presentation">
                        <button class="nav-link active"
                                data-bs-toggle="tab"
                                data-bs-target="#catatan-tab-all"
                                type="button"
                                role="tab">
                            <i class="ph-duotone ph-list-bullets me-1"></i>
                            Semua Catatan
                            <span class="badge" id="catatan-count-all">0</span>
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link"
                                data-bs-toggle="tab"
                                data-bs-target="#catatan-tab-asuransi"
                                type="button"
                                role="tab">
                            <i class="ph-duotone ph-shield-check me-1"></i>
                            Asuransi
                            <span class="badge" id="catatan-count-asuransi">0</span>
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button class="nav-link"
                                data-bs-toggle="tab"
                                data-bs-target="#catatan-tab-internal"
                                type="button"
                                role="tab">
                            <i class="ph-duotone ph-users-three me-1"></i>
                            Internal
                            <span class="badge" id="catatan-count-internal">0</span>
                        </button>
                    </li>

                </ul>

                {{-- CONTENT --}}
                <div class="tab-content">

                    <div class="tab-pane fade show active"
                        id="catatan-tab-all"
                        role="tabpanel">

                        <div class="d-flex justify-content-between align-items-center gap-2 mb-3 flex-wrap">
                            <div class="small text-body-secondary">
                                Seluruh catatan pasien dari Asuransi dan Internal.
                            </div>

                            <button type="button" class="btn btn-primary btn-sm btn-add-catatan-internal">
                                <i class="ph-duotone ph-plus-circle me-1"></i>
                                Tambah Catatan
                            </button>
                        </div>

                        <div id="catatan-list-all"></div>
                    </div>

                    <div class="tab-pane fade"
                        id="catatan-tab-asuransi"
                        role="tabpanel">
                        <div id="catatan-list-asuransi"></div>
                    </div>

                    <div class="tab-pane fade"
                        id="catatan-tab-internal"
                        role="tabpanel">

                        <div class="d-flex justify-content-between align-items-center gap-2 mb-3 flex-wrap">
                            <div class="small text-body-secondary">
                                Catatan internal berdasarkan RM dan ruangan.
                            </div>

                            <button type="button" class="btn btn-primary btn-sm btn-add-catatan-internal">
                                <i class="ph-duotone ph-plus-circle me-1"></i>
                                Tambah Catatan
                            </button>
                        </div>

                        <div id="catatan-list-internal"></div>
                    </div>

                </div>

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer emr-catatan-footer">

                <button type="button"
                        class="btn btn-subtle-secondary border-dashed"
                        data-bs-dismiss="modal">
                    <i class="ph-duotone ph-x me-1"></i>
                    Tutup
                </button>

                <button type="button"
                        class="btn btn-primary"
                        id="btn-refresh-catatan-pasien">
                    <i class="ph-duotone ph-arrows-clockwise me-1"></i>
                    Refresh
                </button>

            </div>

        </div>
    </div>
</div>

{{-- MODAL SOLVE ASURANSI --}}
<div class="modal fade"
    id="modalSolveCatatanAsuransi"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content emr-catatan-modal">

            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalSolveCatatanTitle">
                    <i class="ph-duotone ph-check-circle text-success me-2"></i>
                    Selesaikan Catatan
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <div class="mb-2">
                    <label class="form-label fw-semibold">
                        Deskripsi Penyelesaian
                    </label>

                    <textarea class="form-control"
                            id="catatan-asuransi-solved-description"
                            rows="4"
                            placeholder="Tuliskan keterangan penyelesaian jika diperlukan..."></textarea>

                    <div class="small text-body-secondary mt-2">
                        Deskripsi penyelesaian bersifat opsional.
                    </div>
                </div>

            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-subtle-secondary"
                        id="btn-cancel-solved-catatan">
                    Batal
                </button>

                <button type="button"
                        class="btn btn-success"
                        id="btn-confirm-solve-catatan">
                    <i class="ph-duotone ph-check-circle me-1"></i>
                    Tandai Solved
                </button>

            </div>

        </div>
    </div>
</div>

{{-- MODAL TAMBAH / EDIT CATATAN INTERNAL --}}
<div class="modal fade"
    id="modalFormCatatanInternal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content emr-catatan-modal">

            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalFormCatatanTitle">
                    Tambah Catatan Internal
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="catatan-internal-id">

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Catatan
                    </label>

                    <textarea class="form-control"
                            id="catatan-internal-description"
                            rows="6"
                            placeholder="Masukkan catatan..."></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Ruangan
                    </label>

                    <select class="form-select"
                            id="catatan-internal-ruangan">

                        <option value="">
                            Semua Ruangan (Global)
                        </option>

                        {{-- Gunakan data ruangan yang sudah ada jika tersedia --}}
                        @foreach ($list['ruangan_catatan'] ?? [] as $ruangan)
                            <option value="{{ $ruangan->ID }}">
                                {{ $ruangan->DESKRIPSI }}
                            </option>
                        @endforeach

                    </select>

                    <div class="form-text">
                        Pilih ruangan agar catatan hanya muncul pada kunjungan
                        pasien di ruangan tersebut. Kosongkan untuk berlaku
                        di semua ruangan.
                    </div>
                </div>

            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-subtle-secondary"
                        id="btn-cancel-catatan-internal">
                    Batal
                </button>

                <button type="button"
                        class="btn btn-primary"
                        id="btn-save-catatan-internal">
                    <i class="ph-duotone ph-floppy-disk me-1"></i>
                    Simpan Catatan
                </button>

            </div>

        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/v3/components/emr/catatan.js') }}" data-navigate-once></script>
@endpush
