@section('title', 'Roles Setting')
@section('page-sub-title', 'SISTEM INFORMASI RUMAH SAKIT')
@section('page-title', 'Pengaturan Jabatan Pengguna')

<div class="sirmed-setting-page sirmed-roles-page">

    {{-- SUMMARY --}}
    <div class="sirmed-setting-summary">
        <div class="sirmed-summary-item">
            <div class="sirmed-summary-icon primary">
                <i class="ri-team-line"></i>
            </div>
            <div>
                <span class="sirmed-summary-label">Total Jabatan</span>
                <strong>{{ count($roles) }}</strong>
            </div>
        </div>

        <div class="sirmed-summary-item">
            <div class="sirmed-summary-icon info">
                <i class="ri-shield-check-line"></i>
            </div>
            <div>
                <span class="sirmed-summary-label">Total Referensi Akses</span>
                <strong>{{ count($permissions) }}</strong>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="sirmed-setting-card">
        <div class="sirmed-setting-card-header d-flex justify-content-between align-items-center">
            <div>
                <h6>Daftar Jabatan</h6>
                <span>Daftar jabatan dan akses yang dimiliki</span>
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

                <button type="button"
                    class="btn btn-subtle-warning"
                    wire:click="openPermissionReference"
                    wire:loading.attr="disabled"
                    wire:target="openPermissionReference">

                    <i class="ri-shield-keyhole-line"
                        wire:loading.remove
                        wire:target="openPermissionReference"></i>

                    <span class="spinner-border spinner-border-sm"
                        wire:loading
                        wire:target="openPermissionReference"></span>

                    <span wire:loading.remove wire:target="openPermissionReference">
                        Referensi Akses
                    </span>

                    <span wire:loading wire:target="openPermissionReference">
                        Membuka...
                    </span>
                </button>

                <a class="btn btn-subtle-info"
                    href="{{ route('v3.setting.permissions') }}" wire:navigate>
                    <i class="ri-git-pull-request-line me-1"></i>
                    Sync
                </a>

                <button type="button"
                    class="btn btn-primary"
                    wire:click="openCreateRole"
                    wire:loading.attr="disabled"
                    wire:target="openCreateRole">

                    <i class="ri-add-line"
                        wire:loading.remove
                        wire:target="openCreateRole"></i>

                    <span class="spinner-border spinner-border-sm"
                        wire:loading
                        wire:target="openCreateRole"></span>

                    <span wire:loading.remove wire:target="openCreateRole">
                        Tambah Jabatan
                    </span>

                    <span wire:loading wire:target="openCreateRole">
                        Membuka...
                    </span>
                </button>
            </div>
        </div>

        <div class="table-responsive p-3">
            <table class="table sirmed-v3-table align-middle sirmed-datatable" data-order='[[0, "desc"]]'>
                <thead>
                    <tr>
                        <th style="width:70px">ID</th>
                        <th style="width:250px">Jabatan</th>
                        <th>Akses</th>
                        <th class="text-center" style="width:110px">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($roles as $role)
                        <tr wire:key="role-{{ $role->id }}">
                            <td class="text-muted">{{ $role->id }}</td>

                            <td>
                                <span class="sirmed-role-name">
                                    {{ $role->name }}
                                </span>
                                <small class="d-block text-muted">
                                    Guard: {{ $role->guard_name }}
                                </small>
                            </td>

                            <td>
                                <div class="sirmed-permission-list">
                                    @forelse($role->permissions as $permission)
                                        <span class="sirmed-permission-chip">
                                            {{ $permission->name }}
                                        </span>
                                    @empty
                                        <span class="text-muted small">
                                            Belum memiliki akses
                                        </span>
                                    @endforelse
                                </div>
                            </td>

                            <td class="text-center">
                                <div class="sirmed-table-actions">

                                    {{-- EDIT --}}
                                    <button type="button"
                                        class="btn btn-subtle-warning btn-sm"
                                        title="Edit jabatan dan akses"
                                        wire:click="openEditRole({{ $role->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="openEditRole({{ $role->id }})">

                                        <i class="ri-edit-line"
                                            wire:loading.remove
                                            wire:target="openEditRole({{ $role->id }})"></i>

                                        <span class="spinner-border spinner-border-sm"
                                            wire:loading
                                            wire:target="openEditRole({{ $role->id }})"></span>
                                    </button>

                                    {{-- DELETE --}}
                                    <button type="button"
                                        class="btn btn-subtle-danger btn-sm"
                                        title="Hapus jabatan"
                                        wire:click="confirmDeleteRole({{ $role->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="confirmDeleteRole({{ $role->id }})">

                                        <i class="ri-delete-bin-6-line"
                                            wire:loading.remove
                                            wire:target="confirmDeleteRole({{ $role->id }})"></i>

                                        <span class="spinner-border spinner-border-sm"
                                            wire:loading
                                            wire:target="confirmDeleteRole({{ $role->id }})"></span>
                                    </button>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="sirmed-empty-state">
                                    <i class="ri-search-line"></i>
                                    <span>Data jabatan tidak ditemukan.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL ROLE --}}
    @if($modalRole)
        <div class="sirmed-modal-backdrop" wire:click.self="$set('modalRole', false)">
            <div class="sirmed-modal sirmed-modal-lg">
                <div class="sirmed-modal-header">
                    <div>
                        <span class="sirmed-modal-eyebrow">
                            {{ $mode === 'create' ? 'ROLE BARU' : 'EDIT ROLE' }}
                        </span>
                        <h5>{{ $mode === 'create' ? 'Tambah Jabatan' : 'Ubah Jabatan' }}</h5>
                    </div>

                    <button type="button"
                        class="sirmed-modal-close"
                        wire:click="$set('modalRole', false)">
                        <i class="ri-close-line"></i>
                    </button>
                </div>

                <div class="sirmed-modal-body">
                    <div class="mb-3">
                        <label class="form-label">
                            Nama Jabatan <span class="text-danger">*</span>
                        </label>

                        <input type="text"
                            class="form-control"
                            wire:model="roleName"
                            placeholder="Masukkan nama jabatan">

                        @error('roleName')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="sirmed-permission-selector">
                        <div class="sirmed-permission-selector-header">
                            <div>
                                <label class="form-label mb-1">Hak Akses</label>
                                <small class="text-muted">
                                    Pilih akses yang diberikan kepada jabatan ini.
                                </small>
                            </div>

                            <span class="badge bg-primary-subtle text-primary">
                                {{ count($selectedPermissions) }} dipilih
                            </span>
                        </div>

                        <div class="sirmed-permission-selector-list">
                            @forelse($permissions as $permission)
                                <label class="sirmed-permission-option"
                                    wire:key="permission-select-{{ $permission->id }}">
                                    <input type="checkbox"
                                        value="{{ $permission->id }}"
                                        wire:model="selectedPermissions">

                                    <span class="sirmed-permission-option-content">
                                        <strong>{{ $permission->name }}</strong>
                                        <small>{{ $permission->guard_name }}</small>
                                    </span>
                                </label>
                            @empty
                                <div class="text-muted text-center p-3">
                                    Belum ada referensi akses.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="sirmed-modal-footer">
                    <button type="button"
                        class="btn btn-subtle-secondary"
                        wire:click="$set('modalRole', false)">
                        Batal
                    </button>

                    <button type="button"
                        class="btn btn-primary"
                        wire:click="saveRole"
                        wire:loading.attr="disabled"
                        wire:target="saveRole">

                        <span wire:loading.remove wire:target="saveRole">
                            <i class="ri-save-line me-1"></i>
                            {{ $mode === 'create' ? 'Tambah Jabatan' : 'Simpan Perubahan' }}
                        </span>

                        <span wire:loading wire:target="saveRole">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL DELETE ROLE --}}
    @if($modalDeleteRole)
        <div class="sirmed-modal-backdrop" wire:click.self="$set('modalDeleteRole', false)">
            <div class="sirmed-modal sirmed-modal-sm">
                <div class="sirmed-modal-header">
                    <h5>Hapus Jabatan</h5>
                    <button type="button"
                        class="sirmed-modal-close"
                        wire:click="$set('modalDeleteRole', false)">
                        <i class="ri-close-line"></i>
                    </button>
                </div>

                <div class="sirmed-modal-body">
                    <div class="sirmed-delete-icon">
                        <i class="ri-delete-bin-6-line"></i>
                    </div>

                    <p>
                        Anda akan menghapus jabatan
                        <strong>{{ $deleteRoleName }}</strong>.
                    </p>

                    <p class="text-muted small mb-0">
                        Penghapusan ini juga akan melepaskan akses dan jabatan
                        tersebut dari pengguna terkait. Pastikan Anda telah
                        mempertimbangkan tindakan ini.
                    </p>
                </div>

                <div class="form-check p-3">
                    <input type="checkbox"
                        class="form-check-input"
                        id="confirm-delete-role"
                        wire:model.live="deleteRoleConfirmed">

                    <label class="form-check-label small" for="confirm-delete-role">
                        Saya memahami risiko dan menyetujui penghapusan jabatan ini.
                    </label>
                </div>

                <div class="sirmed-modal-footer">
                    <button type="button"
                        class="btn btn-subtle-secondary"
                        wire:click="$set('modalDeleteRole', false)">
                        Batal
                    </button>

                    <button type="button"
                        class="btn btn-danger"
                        wire:click="deleteRole"
                        wire:loading.attr="disabled"
                        wire:target="deleteRole"
                        @disabled(!$deleteRoleConfirmed)>

                        <span wire:loading.remove wire:target="deleteRole">
                            <i class="ri-delete-bin-6-line me-1"></i>
                            Hapus Jabatan
                        </span>

                        <span wire:loading wire:target="deleteRole">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Menghapus...
                        </span>
                    </button>

                </div>
            </div>
        </div>
    @endif

    {{-- MODAL REFERENSI AKSES --}}
    @if($modalPermissionsReference)
        <div class="sirmed-modal-backdrop"
            wire:click.self="$set('modalPermissionsReference', false)">
            <div class="sirmed-modal sirmed-modal-xl">
                <div class="sirmed-modal-header">
                    <div>
                        <span class="sirmed-modal-eyebrow">REFERENSI SISTEM</span>
                        <h5>Daftar Referensi Akses</h5>
                    </div>

                    <button type="button"
                        class="sirmed-modal-close"
                        wire:click="$set('modalPermissionsReference', false)">
                        <i class="ri-close-line"></i>
                    </button>
                </div>

                <div class="sirmed-modal-body">
                    <div class="sirmed-reference-toolbar">
                        <span>
                            Total {{ count($permissions) }} referensi akses
                        </span>

                        <button type="button"
                            class="btn btn-subtle-primary btn-sm"
                            wire:click="openCreatePermission">
                            <i class="ri-add-line me-1"></i>
                            Tambah Akses
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table sirmed-v3-table align-middle sirmed-datatable" data-order='[[3, "desc"]]'>
                            <thead>
                                <tr>
                                    <th style="width:70px">ID</th>
                                    <th>Nama Akses</th>
                                    <th style="width:120px">Guard</th>
                                    <th style="width:180px">Diperbarui</th>
                                    <th style="width:80px" class="text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($permissions as $permission)
                                    <tr wire:key="reference-{{ $permission->id }}">
                                        <td>{{ $permission->id }}</td>

                                        <td>{{ $permission->name }}</td>

                                        <td>
                                            <span class="badge bg-secondary-subtle text-secondary">
                                                {{ $permission->guard_name }}
                                            </span>
                                        </td>

                                        <td>
                                            {{ $permission->updated_at
                                                ? \Carbon\Carbon::parse($permission->updated_at)->format('d-m-Y H:i')
                                                : '-' }}
                                        </td>

                                        <td class="text-center">
                                            <button type="button"
                                                class="btn btn-subtle-danger btn-sm"
                                                wire:click="confirmDeletePermission({{ $permission->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="confirmDeletePermission({{ $permission->id }})">

                                                <i class="ri-delete-bin-6-line"
                                                    wire:loading.remove
                                                    wire:target="confirmDeletePermission({{ $permission->id }})"></i>

                                                <span class="spinner-border spinner-border-sm"
                                                    wire:loading
                                                    wire:target="confirmDeletePermission({{ $permission->id }})"></span>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            Belum ada referensi akses.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="sirmed-modal-footer">
                    <button type="button"
                        class="btn btn-subtle-secondary"
                        wire:click="$set('modalPermissionsReference', false)">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL ADD PERMISSION --}}
    @if($modalPermission)
        <div class="sirmed-modal-backdrop sirmed-modal-nested"
            wire:click.self="$set('modalPermission', false)">
            <div class="sirmed-modal sirmed-modal-sm">
                <div class="sirmed-modal-header">
                    <h5>Tambah Referensi Akses</h5>
                    <button type="button"
                        class="sirmed-modal-close"
                        wire:click="$set('modalPermission', false)">
                        <i class="ri-close-line"></i>
                    </button>
                </div>

                <div class="sirmed-modal-body">
                    <label class="form-label">Nama Akses <span class="text-danger">*</span></label>
                    <input type="text"
                        class="form-control"
                        wire:model="permissionName"
                        placeholder="Tuliskan nama akses">

                    @error('permissionName')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="sirmed-modal-footer">
                    <button type="button"
                        class="btn btn-subtle-secondary"
                        wire:click="$set('modalPermission', false)">
                        Batal
                    </button>

                    <button type="button"
                        class="btn btn-primary"
                        wire:click="savePermission"
                        wire:loading.attr="disabled"
                        wire:target="savePermission">

                        <span wire:loading.remove wire:target="savePermission">
                            <i class="ri-save-line me-1"></i>
                            Tambah Akses
                        </span>

                        <span wire:loading wire:target="savePermission">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL DELETE PERMISSION --}}
    @if($modalDeletePermission)
        <div class="sirmed-modal-backdrop sirmed-modal-nested"
            wire:click.self="$set('modalDeletePermission', false)">
            <div class="sirmed-modal sirmed-modal-sm">
                <div class="sirmed-modal-header">
                    <h5>Hapus Referensi Akses</h5>
                    <button type="button"
                        class="sirmed-modal-close"
                        wire:click="$set('modalDeletePermission', false)">
                        <i class="ri-close-line"></i>
                    </button>
                </div>

                <div class="sirmed-modal-body">
                    <div class="sirmed-delete-icon">
                        <i class="ri-delete-bin-6-line"></i>
                    </div>

                    <p>
                        Anda akan menghapus akses
                        <strong>{{ $deletePermissionName }}</strong>.
                    </p>

                    <p class="text-muted small">
                        Akses ini juga akan dilepas dari seluruh jabatan
                        yang menggunakannya.
                    </p>
                </div>

                <div class="form-check p-3">
                    <input type="checkbox"
                        class="form-check-input"
                        id="confirm-delete-permission"
                        wire:model="deletePermissionConfirmed">

                    <label class="form-check-label small" for="confirm-delete-permission">
                        Saya memahami risiko dan menyetujui penghapusan akses ini.
                    </label>
                </div>

                <div class="sirmed-modal-footer">
                    <button type="button"
                        class="btn btn-subtle-secondary"
                        wire:click="$set('modalDeletePermission', false)">
                        Batal
                    </button>

                    <button type="button"
                        class="btn btn-danger"
                        wire:click="deletePermission"
                        wire:loading.attr="disabled"
                        @disabled(!$deletePermissionConfirmed)>
                        Hapus Akses
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
