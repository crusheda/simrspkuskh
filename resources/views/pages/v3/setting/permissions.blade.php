@section('title', 'Permissions Setting')
@section('page-sub-title', 'SISTEM INFORMASI RUMAH SAKIT')
@section('page-title', 'Pengaturan Akses ✗ Jabatan Pengguna')

<div class="sirmed-setting-page sirmed-users-page">

    {{-- SUMMARY --}}
    <div class="sirmed-setting-summary">
        <div class="sirmed-summary-item">
            <div class="sirmed-summary-icon primary">
                <i class="ri-user-line"></i>
            </div>
            <div>
                <span class="sirmed-summary-label">Total Pengguna</span>
                <strong>{{ count($users) }}</strong>
            </div>
        </div>

        <div class="sirmed-summary-item">
            <div class="sirmed-summary-icon info">
                <i class="ri-shield-user-line"></i>
            </div>
            <div>
                <span class="sirmed-summary-label">Total Jabatan</span>
                <strong>{{ count($roles) }}</strong>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="sirmed-setting-card">
        <div class="sirmed-setting-card-header d-flex justify-content-between align-items-center">
            <div>
                <h6>Daftar Pengguna</h6>
                <span>Pengaturan jabatan dan akses pengguna</span>
            </div>
            <div class="sirmed-page-actions">
                <button type="button"
                    class="btn btn-subtle-secondary"
                    wire:click="refreshData"
                    wire:loading.attr="disabled"
                    wire:target="refreshData">

                    <i class="ri-refresh-line"
                        wire:loading.remove
                        wire:target="refreshData"></i>

                    <span class="spinner-border spinner-border-sm"
                        wire:loading
                        wire:target="refreshData"></span>

                    <span wire:loading.remove wire:target="refreshData">Refresh</span>
                    <span wire:loading wire:target="refreshData">Memuat...</span>
                </button>

                <a href="{{ route('v3.setting.roles') }}"
                    class="btn btn-subtle-info" wire:navigate>
                    Tambah Roles & Permissions
                    <i class="ri-arrow-right-line ms-1"></i>
                </a>
            </div>
        </div>

        <div class="table-responsive p-3">
            <table class="table sirmed-v3-table align-middle sirmed-datatable" data-order='[[3, "asc"],[4, "asc"]]'>
                <thead>
                    <tr>
                        <th style="width:80px" class="text-center">ID</th>
                        <th>Nama Lengkap</th>
                        <th style="width:180px">Username</th>
                        <th style="width:220px">Jabatan</th>
                        <th style="width:120px">Status</th>
                        <th style="width:100px" class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($users as $user)
                        <tr wire:key="user-role-{{ $user->ID_PENGGUNA }}">
                            <td class="text-center text-muted">
                                {{ $user->ID_PENGGUNA }}
                            </td>

                            <td>
                                <div class="sirmed-user-cell">
                                    <div class="sirmed-user-avatar">
                                        <i class="ri-user-3-line"></i>
                                    </div>

                                    <div>
                                        <strong>
                                            {{ $user->NAMALENGKAP ?: $user->APNAMA }}
                                        </strong>

                                        <small>
                                            NIP. {{ $user->NIP ?: $user->APNIP ?: '-' }}
                                        </small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="sirmed-username">
                                    {{ $user->USERNAME ?: '-' }}
                                </span>
                            </td>

                            <td>
                                @if($user->ROLE_NAMES)
                                    <span class="sirmed-role-badge">
                                        {{ $user->ROLE_NAMES }}
                                    </span>
                                @else
                                    <span class="text-muted small">

                                    </span>
                                @endif
                            </td>

                            <td>
                                @if((int) $user->STATUS === 1)
                                    <span class="sirmed-status active">
                                        <i class="ri-checkbox-circle-fill"></i>
                                        Aktif
                                    </span>
                                @else
                                    <span class="sirmed-status inactive">
                                        <i class="ri-close-circle-fill"></i>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="sirmed-table-actions">

                                    {{-- EDIT --}}
                                    @if((int) $user->STATUS === 1)
                                        <button type="button"
                                            class="btn btn-subtle-warning btn-sm"
                                            title="Ubah jabatan"
                                            wire:click="openEdit({{ $user->ID_PENGGUNA }})"
                                            wire:loading.attr="disabled"
                                            wire:target="openEdit({{ $user->ID_PENGGUNA }})">

                                            <i class="ri-edit-line"
                                                wire:loading.remove
                                                wire:target="openEdit({{ $user->ID_PENGGUNA }})"></i>

                                            <span class="spinner-border spinner-border-sm"
                                                wire:loading
                                                wire:target="openEdit({{ $user->ID_PENGGUNA }})"></span>
                                        </button>
                                    @else
                                        <button type="button"
                                            class="btn btn-subtle-secondary btn-sm"
                                            disabled>
                                            <i class="ri-edit-line"></i>
                                        </button>
                                    @endif

                                    {{-- DELETE --}}
                                    @if($user->ROLE_NAMES)
                                        <button type="button"
                                            class="btn btn-subtle-danger btn-sm"
                                            title="Hapus jabatan"
                                            wire:click="confirmDelete({{ $user->ID_PENGGUNA }})"
                                            wire:loading.attr="disabled"
                                            wire:target="confirmDelete({{ $user->ID_PENGGUNA }})">

                                            <i class="ri-delete-bin-6-line"
                                                wire:loading.remove
                                                wire:target="confirmDelete({{ $user->ID_PENGGUNA }})"></i>

                                            <span class="spinner-border spinner-border-sm"
                                                wire:loading
                                                wire:target="confirmDelete({{ $user->ID_PENGGUNA }})"></span>
                                        </button>
                                    @else
                                        <button type="button"
                                            class="btn btn-subtle-secondary btn-sm"
                                            disabled>
                                            <i class="ri-delete-bin-6-line"></i>
                                        </button>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="sirmed-empty-state">
                                    <i class="ri-user-search-line"></i>
                                    <span>Pengguna tidak ditemukan.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="sirmed-setting-card-footer">
            <span>
                Menampilkan {{ $filteredUsers->count() }} pengguna
            </span>
        </div>
    </div>

    {{-- MODAL EDIT ROLE USER --}}
    @if($modalEdit)
        <div class="sirmed-modal-backdrop" wire:click.self="$set('modalEdit', false)">
            <div class="sirmed-modal sirmed-modal-sm">
                <div class="sirmed-modal-header">
                    <div>
                        <span class="sirmed-modal-eyebrow">PENGATURAN PENGGUNA</span>
                        <h5>Perbarui Jabatan</h5>
                    </div>

                    <button type="button"
                        class="sirmed-modal-close"
                        wire:click="$set('modalEdit', false)">
                        <i class="ri-close-line"></i>
                    </button>
                </div>

                <div class="sirmed-modal-body">
                    <div class="sirmed-user-edit-info">
                        <div class="sirmed-user-avatar large">
                            <i class="ri-user-3-line"></i>
                        </div>

                        <div>
                            <span class="text-muted small">ID Pengguna</span>
                            <strong>#{{ $userId }}</strong>
                        </div>
                    </div>

                    <label class="form-label">
                        Jabatan <span class="text-danger">*</span>
                    </label>

                    <select class="form-select" wire:model="selectedRole">
                        <option value="">Pilih Jabatan</option>

                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('selectedRole')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="sirmed-modal-footer">
                    <button type="button"
                        class="btn btn-subtle-secondary"
                        wire:click="$set('modalEdit', false)">
                        Batal
                    </button>

                    <button type="button"
                        class="btn btn-primary"
                        wire:click="saveRoleUser"
                        wire:loading.attr="disabled"
                        wire:target="saveRoleUser">

                        <span wire:loading.remove wire:target="saveRoleUser">
                            <i class="ri-save-line me-1"></i>
                            Simpan Perubahan
                        </span>

                        <span wire:loading wire:target="saveRoleUser">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL DELETE ROLE USER --}}
    @if($modalDelete)
        <div class="sirmed-modal-backdrop" wire:click.self="$set('modalDelete', false)">
            <div class="sirmed-modal sirmed-modal-sm">
                <div class="sirmed-modal-header">
                    <h5>Hapus Jabatan Pengguna</h5>

                    <button type="button"
                        class="sirmed-modal-close"
                        wire:click="$set('modalDelete', false)">
                        <i class="ri-close-line"></i>
                    </button>
                </div>

                <div class="sirmed-modal-body">
                    <div class="sirmed-delete-icon">
                        <i class="ri-user-unfollow-line"></i>
                    </div>

                    <p>
                        Anda akan menghapus jabatan dari pengguna
                        <strong>{{ $deleteUserName }}</strong>.
                    </p>

                    @if($deleteUserRole)
                        <div class="sirmed-delete-detail">
                            <span>Jabatan saat ini</span>
                            <strong>{{ $deleteUserRole }}</strong>
                        </div>
                    @endif

                    <p class="text-muted small mb-0">
                        Tindakan ini tidak menghapus akun pengguna,
                        hanya melepaskan jabatan dan akses yang dimilikinya.
                    </p>
                </div>

                <div class="form-check p-3">
                    <input type="checkbox"
                        class="form-check-input"
                        id="confirm-delete-user-role"
                        wire:model="deleteConfirmed">

                    <label class="form-check-label small" for="confirm-delete-user-role">
                        Saya memahami bahwa tindakan ini akan menghapus jabatan dan akses pengguna.
                    </label>
                </div>

                <div class="sirmed-modal-footer">
                    <button type="button"
                        class="btn btn-subtle-secondary"
                        wire:click="$set('modalDelete', false)">
                        Batal
                    </button>

                    <button type="button"
                        class="btn btn-danger"
                        wire:click="deleteRoleUser"
                        wire:loading.attr="disabled"
                        wire:target="deleteRoleUser"
                        @disabled(!$deleteConfirmed)>

                        <span wire:loading.remove wire:target="deleteRoleUser">
                            <i class="ri-delete-bin-6-line me-1"></i>
                            Hapus Jabatan
                        </span>

                        <span wire:loading wire:target="deleteRoleUser">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Menghapus...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>

@script
<script>
    $wire.on('sirmed-toast', (event) => {
        const data = event;

        if (typeof iziToast !== 'undefined') {
            if (data.type === 'success') {
                iziToast.success({
                    title: 'Berhasil',
                    message: data.message,
                    position: 'topRight'
                });
            } else {
                iziToast.error({
                    title: 'Terjadi Kesalahan',
                    message: data.message,
                    position: 'topRight'
                });
            }
        }
    });
</script>
@endscript
