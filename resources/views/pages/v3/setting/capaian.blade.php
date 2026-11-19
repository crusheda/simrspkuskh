<div class="app-shell py-3">

    {{-- HEADER --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="bi bi-leaderboard me-2 text-primary"></i>
                Capaian TTE Pegawai
            </h4>
            <div class="text-muted small">
                Monitoring enrollment dan penggunaan variasi tanda tangan elektronik pegawai
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary-subtle text-primary px-3 py-2">
                <i class="bi bi-shield-check me-1"></i>
                Monitoring TTE
            </span>
        </div>
    </div>

    {{-- STATISTIK --}}
    <div class="row g-3 mb-4">

        {{-- TOTAL PEGAWAI --}}
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small mb-2">Total Pegawai</div>
                            <h2 class="fw-bold mb-0">
                                {{ number_format($stats['totalPegawai']) }}
                            </h2>
                            <div class="small text-muted mt-2">
                                Pegawai aktif terdaftar
                            </div>
                        </div>

                        <div class="rounded-3 bg-primary-subtle text-primary p-3">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SUDAH TTE --}}
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small mb-2">Sudah TTE</div>
                            <h2 class="fw-bold text-success mb-0">
                                {{ number_format($stats['sudahTte']) }}
                            </h2>
                            <div class="small text-muted mt-2">
                                Pegawai telah enrollment
                            </div>
                        </div>

                        <div class="rounded-3 bg-success-subtle text-success p-3">
                            <i class="bi bi-check-circle fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- BELUM TTE --}}
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small mb-2">Belum TTE</div>
                            <h2 class="fw-bold text-danger mb-0">
                                {{ number_format($stats['belumTte']) }}
                            </h2>
                            <div class="small text-muted mt-2">
                                Belum memiliki tanda tangan
                            </div>
                        </div>

                        <div class="rounded-3 bg-danger-subtle text-danger p-3">
                            <i class="bi bi-person-x fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TOTAL VARIASI --}}
        <div class="col-xl-3 col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="text-muted small mb-2">Total Variasi TTE</div>
                            <h2 class="fw-bold text-info mb-0">
                                {{ number_format($stats['totalVariasi']) }}
                            </h2>
                            <div class="small text-muted mt-2">
                                Tanda tangan tersimpan aktif
                            </div>
                        </div>

                        <div class="rounded-3 bg-info-subtle text-info p-3">
                            <i class="bi bi-pen fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- PROGRESS CAPAIAN --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div>
                    <h6 class="fw-bold mb-1">Progress Enrollment TTE</h6>
                    <div class="small text-muted">
                        Persentase pegawai yang telah memiliki tanda tangan elektronik
                    </div>
                </div>

                <div class="text-end">
                    <h3 class="fw-bold text-primary mb-0">
                        {{ number_format($stats['persentase'], 1) }}%
                    </h3>
                </div>
            </div>

            <div class="progress rounded-pill" style="height: 14px;">
                <div class="progress-bar bg-success rounded-pill"
                     role="progressbar"
                     style="width: {{ $stats['persentase'] }}%"
                     aria-valuenow="{{ $stats['persentase'] }}"
                     aria-valuemin="0"
                     aria-valuemax="100">
                </div>
            </div>

            <div class="d-flex justify-content-between mt-2 small text-muted">
                <span>
                    {{ number_format($stats['sudahTte']) }} pegawai sudah TTE
                </span>
                <span>
                    {{ number_format($stats['belumTte']) }} pegawai belum TTE
                </span>
            </div>

        </div>
    </div>

    {{-- STATISTIK VARIASI --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent border-0 pt-3 px-3 px-md-4">
            <div>
                <h6 class="fw-bold mb-1">
                    Capaian Variasi Tanda Tangan
                </h6>
                <div class="small text-muted">
                    Jumlah pegawai dan total TTE yang tersimpan untuk setiap variasi
                </div>
            </div>
        </div>

        <div class="card-body pt-2 px-3 px-md-4">
            <div class="row g-3">

                @foreach($stats['variants'] as $queue => $variant)
                    <div class="col-xl-2 col-md-4 col-6">
                        <div class="border rounded-3 p-3 h-100">

                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="small fw-semibold">
                                    Variasi {{ $queue }}
                                </span>

                                <span class="badge bg-primary-subtle text-primary">
                                    {{ $variant['pegawai'] }} Pegawai
                                </span>
                            </div>

                            <div class="fs-4 fw-bold">
                                {{ number_format($variant['tte']) }}
                            </div>

                            <div class="small text-muted">
                                TTE tersimpan
                            </div>

                            <div class="progress mt-3" style="height: 5px;">
                                <div class="progress-bar"
                                     style="width: {{ $stats['totalPegawai'] > 0 ? min(100, ($variant['pegawai'] / $stats['totalPegawai']) * 100) : 0 }}%">
                                </div>
                            </div>

                            <div class="small text-muted mt-2">
                                {{ $stats['totalPegawai'] > 0
                                    ? number_format(($variant['pegawai'] / $stats['totalPegawai']) * 100, 1)
                                    : 0
                                }}%
                                dari pegawai
                            </div>

                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

    {{-- DATA PEGAWAI --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-transparent border-0 pt-3 px-3 px-md-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <h6 class="fw-bold mb-1">
                        Data Capaian Pegawai
                    </h6>
                    <div class="small text-muted">
                        Daftar status enrollment dan variasi TTE setiap pegawai
                    </div>
                </div>

                <div class="badge bg-secondary-subtle text-secondary">
                    {{ number_format($pegawai->total()) }} Data
                </div>
            </div>
        </div>

        <div class="card-body px-3 px-md-4">

            {{-- FILTER --}}
            <div class="row g-2 mb-3">

                <div class="col-xl-5 col-lg-5">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>

                        <input type="text"
                               class="form-control"
                               placeholder="Cari nama pegawai atau NIP..."
                               wire:model.live.debounce.300ms="search">

                        @if($search)
                            <button class="btn btn-outline-secondary"
                                    type="button"
                                    wire:click="$set('search', '')">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        @endif
                    </div>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-4">
                    <select class="form-select"
                            wire:model.live="filterStatus">
                        <option value="all">Semua Status TTE</option>
                        <option value="sudah">Sudah TTE</option>
                        <option value="belum">Belum TTE</option>
                    </select>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-4">
                    <select class="form-select"
                            wire:model.live="filterVariant">
                        <option value="all">Semua Variasi</option>
                        @for($i = 1; $i <= 6; $i++)
                            <option value="{{ $i }}">
                                Variasi {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="col-xl-2 col-lg-2 col-md-4">
                    <select class="form-select"
                            wire:model.live="perPage">
                        <option value="15">15 Data</option>
                        <option value="25">25 Data</option>
                        <option value="50">50 Data</option>
                        <option value="100">100 Data</option>
                    </select>
                </div>

            </div>

            {{-- LOADING --}}
            <div wire:loading.delay
                 wire:target="search,filterStatus,filterVariant,perPage"
                 class="small text-primary mb-2">
                <i class="bi bi-arrow-repeat me-1"></i>
                Memuat data...
            </div>

            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">

                    <thead>
                        <tr>
                            <th style="width: 55px;">No.</th>

                            <th role="button" wire:click="sortBy('nama')">
                                Pegawai
                                @if($sortBy === 'nama')
                                    <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                @endif
                            </th>

                            <th class="text-center" style="width: 120px;">
                                Status
                            </th>

                            <th class="text-center" style="width: 120px;" role="button"
                                wire:click="sortBy('jumlah_tte')">
                                Total TTE
                                @if($sortBy === 'jumlah_tte')
                                    <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                @endif
                            </th>

                            <th class="text-center">
                                Variasi TTE
                            </th>

                            <th class="text-center" style="width: 70px;">
                                Detail
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($pegawai as $index => $item)
                            @php
                                $hasTte = $item->jumlah_tte > 0;
                                $rowId = 'tte-detail-' . preg_replace('/[^A-Za-z0-9\-_]/', '', $item->nip);
                            @endphp

                            <tr wire:key="capaian-tte-{{ $item->nip }}">

                                <td class="text-muted">
                                    {{ $pegawai->firstItem() + $index }}
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $item->nama ?: 'Nama tidak tersedia' }}
                                    </div>

                                    <div class="small text-muted">
                                        NIP: {{ $item->nip }}
                                    </div>
                                </td>

                                <td class="text-center">
                                    @if($hasTte)
                                        <span class="badge rounded-pill bg-success-subtle text-success">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Sudah TTE
                                        </span>
                                    @else
                                        <span class="badge rounded-pill bg-danger-subtle text-danger">
                                            <i class="bi bi-x-circle me-1"></i>
                                            Belum TTE
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <span class="fw-bold {{ $hasTte ? 'text-success' : 'text-muted' }}">
                                        {{ $item->jumlah_tte }} / 6
                                    </span>
                                </td>

                                <td>
                                    <div class="d-flex justify-content-center flex-wrap gap-1">
                                        @for($q = 1; $q <= 6; $q++)
                                            @php
                                                $field = 'varian_' . $q;
                                                $active = (int) $item->$field === 1;
                                            @endphp

                                            <span class="badge rounded-pill {{ $active ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary opacity-50' }}"
                                                  title="Variasi {{ $q }} {{ $active ? 'tersimpan' : 'belum tersedia' }}"
                                                  style="min-width: 34px;">
                                                {{ $q }}
                                                @if($active)
                                                    <i class="bi bi-check2 ms-1"></i>
                                                @else
                                                    <i class="bi bi-dash ms-1"></i>
                                                @endif
                                            </span>
                                        @endfor
                                    </div>
                                </td>

                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-primary"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#{{ $rowId }}"
                                            aria-expanded="false"
                                            title="Lihat detail variasi">
                                        <i class="bi bi-chevron-down"></i>
                                    </button>
                                </td>

                            </tr>

                            {{-- DETAIL --}}
                            <tr class="border-0">
                                <td colspan="6" class="p-0 border-0">
                                    <div class="collapse" id="{{ $rowId }}">
                                        <div class="px-3 px-md-4 py-3 border-bottom">

                                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                                                <div>
                                                    <div class="fw-bold">
                                                        {{ $item->nama }}
                                                    </div>
                                                    <div class="small text-muted">
                                                        NIP {{ $item->nip }}
                                                    </div>
                                                </div>

                                                <div>
                                                    <span class="badge {{ $hasTte ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                                        {{ $item->jumlah_tte }} dari 6 variasi
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="row g-2">
                                                @for($q = 1; $q <= 6; $q++)
                                                    @php
                                                        $field = 'varian_' . $q;
                                                        $active = (int) $item->$field === 1;
                                                    @endphp

                                                    <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                                                        <div class="border rounded-3 p-3 text-center h-100 {{ $active ? 'border-success' : '' }}">

                                                            <div class="mb-2">
                                                                <i class="bi {{ $active ? 'bi-pen text-success' : 'bi-pen text-muted' }} fs-4"></i>
                                                            </div>

                                                            <div class="fw-semibold">
                                                                Variasi {{ $q }}
                                                            </div>

                                                            @if($active)
                                                                <span class="badge bg-success-subtle text-success mt-2">
                                                                    <i class="bi bi-check-circle me-1"></i>
                                                                    Tersimpan
                                                                </span>
                                                            @else
                                                                <span class="badge bg-secondary-subtle text-secondary mt-2">
                                                                    <i class="bi bi-dash-circle me-1"></i>
                                                                    Belum Ada
                                                                </span>
                                                            @endif

                                                        </div>
                                                    </div>
                                                @endfor
                                            </div>

                                        </div>
                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="mb-2">
                                        <i class="bi bi-search fs-1 text-muted"></i>
                                    </div>

                                    <div class="fw-semibold">
                                        Data pegawai tidak ditemukan
                                    </div>

                                    <div class="small text-muted">
                                        Silakan ubah kata kunci atau filter pencarian.
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if($pegawai->hasPages())
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">

                    <div class="small text-muted">
                        Menampilkan {{ $pegawai->firstItem() ?? 0 }}
                        - {{ $pegawai->lastItem() ?? 0 }}
                        dari {{ number_format($pegawai->total()) }} pegawai
                    </div>

                    <div>
                        {{ $pegawai->links() }}
                    </div>

                </div>
            @endif

        </div>
    </div>

</div>
