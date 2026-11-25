<?php

namespace App\Livewire\Setting;

use Livewire\Component;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class Roles extends Component
{
    public $roles = [];
    public $permissions = [];

    public $search = '';

    public $roleId = null;
    public $roleName = '';
    public $selectedPermissions = [];

    public $permissionId = null;
    public $permissionName = '';

    public $deleteRoleId = null;
    public $deleteRoleName = '';

    public $deletePermissionId = null;
    public $deletePermissionName = '';

    public $modalRole = false;
    public $modalPermission = false;
    public $modalDeleteRole = false;
    public $modalDeletePermission = false;
    public $modalPermissionsReference = false;

    public $deleteRoleConfirmed = false;
    public $deletePermissionConfirmed = false;

    public $mode = 'create';

    public function mount()
    {
        $this->loadRoles();
        $this->loadPermissions();
    }

    public function loadRoles()
    {
        $this->roles = Role::with('permissions')
            ->orderByDesc('id')
            ->get();
    }

    public function loadPermissions()
    {
        $this->permissions = Permission::on('db_custom')
            ->orderBy('name')
            ->get();
    }

    public function refreshData()
    {
        $this->loadRoles();
        $this->loadPermissions();

        $this->dispatch('roles-refreshed');
    }

    public function openCreateRole()
    {
        $this->resetRoleForm();

        $this->mode = 'create';
        $this->modalRole = true;
    }

    public function openEditRole($id)
    {
        $role = Role::on('db_custom')
            ->with('permissions')
            ->find($id);

        if (!$role) {
            $this->notify('error', 'Role tidak ditemukan.');
            return;
        }

        $this->resetRoleForm();

        $this->mode = 'edit';
        $this->roleId = $role->id;
        $this->roleName = $role->name;
        $this->selectedPermissions = $role->permissions
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->toArray();

        $this->modalRole = true;
    }

    public function resetRoleForm()
    {
        $this->roleId = null;
        $this->roleName = '';
        $this->selectedPermissions = [];
    }

    public function saveRole()
    {
        $validated = Validator::make([
            'roleName' => $this->roleName,
            'selectedPermissions' => $this->selectedPermissions,
        ], [
            'roleName' => ['required', 'string', 'max:255'],
            'selectedPermissions' => ['nullable', 'array'],
            'selectedPermissions.*' => ['integer'],
        ], [
            'roleName.required' => 'Nama jabatan wajib diisi.',
        ])->validate();

        $connection = DB::connection('db_custom');

        try {
            $connection->beginTransaction();

            if ($this->mode === 'create') {
                $role = Role::on('db_custom')->create([
                    'name' => trim($this->roleName),
                    'guard_name' => 'web',
                ]);
            } else {
                $role = Role::on('db_custom')->find($this->roleId);

                if (!$role) {
                    $connection->rollBack();
                    $this->notify('error', 'Role tidak ditemukan.');
                    return;
                }

                $role->name = trim($this->roleName);
                $role->save();
            }

            $permissionNames = Permission::on('db_custom')
                ->whereIn('id', $this->selectedPermissions ?: [])
                ->pluck('name')
                ->toArray();

            $role->syncPermissions($permissionNames);

            $connection->commit();

            $this->modalRole = false;
            $this->refreshData();

            $message = $this->mode === 'create'
                ? 'Jabatan berhasil ditambahkan.'
                : 'Jabatan dan akses berhasil diperbarui.';

            $this->notify('success', $message);

        } catch (\Throwable $e) {
            $connection->rollBack();

            report($e);
            $this->notify('error', 'Terjadi kesalahan saat menyimpan jabatan.');
        }
    }

    public function confirmDeleteRole($id)
    {
        $role = Role::on('db_custom')->find($id);

        if (!$role) {
            $this->notify('error', 'Role tidak ditemukan.');
            return;
        }

        $this->deleteRoleId = $role->id;
        $this->deleteRoleName = $role->name;

        $this->modalDeleteRole = true;
        $this->deleteRoleConfirmed = false;
        $this->deletePermissionConfirmed = false;
    }

    public function deleteRole()
    {
        if (!$this->deleteRoleId) {
            return;
        }

        if (!$this->deleteRoleConfirmed) {
            $this->notify('error', 'Mohon centang persetujuan sebelum menghapus jabatan.');
            return;
        }

        try {
            $id = $this->deleteRoleId;

            DB::connection('db_custom')->transaction(function () use ($id) {
                DB::connection('db_custom')
                    ->table('role_has_permissions')
                    ->where('role_id', $id)
                    ->delete();

                DB::connection('db_custom')
                    ->table('model_has_roles')
                    ->where('role_id', $id)
                    ->delete();

                DB::connection('db_custom')
                    ->table('roles')
                    ->where('id', $id)
                    ->delete();
            });

            $this->modalDeleteRole = false;
            $this->refreshData();

            $this->notify('success', 'Jabatan berhasil dihapus.');

        } catch (\Throwable $e) {
            report($e);
            $this->notify('error', 'Gagal menghapus jabatan.');
        }
    }

    public function openPermissionReference()
    {
        $this->loadPermissions();
        $this->modalPermissionsReference = true;
    }

    public function openCreatePermission()
    {
        $this->permissionId = null;
        $this->permissionName = '';
        $this->modalPermission = true;
    }

    public function savePermission()
    {
        $this->validate([
            'permissionName' => 'required|string|max:255',
        ], [
            'permissionName.required' => 'Nama akses wajib diisi.',
        ]);

        $name = trim($this->permissionName);

        $exists = Permission::on('db_custom')
            ->where('name', $name)
            ->where('guard_name', 'web')
            ->exists();

        if ($exists) {
            $this->addError('permissionName', 'Akses tersebut sudah tersedia.');
            return;
        }

        try {
            Permission::on('db_custom')->create([
                'name' => $name,
                'guard_name' => 'web',
            ]);

            $this->modalPermission = false;
            $this->permissionName = '';

            $this->loadPermissions();
            $this->loadRoles();

            $this->notify('success', 'Referensi akses berhasil ditambahkan.');

        } catch (\Throwable $e) {
            report($e);
            $this->notify('error', 'Gagal menambahkan referensi akses.');
        }
    }

    public function confirmDeletePermission($id)
    {
        $permission = Permission::on('db_custom')->find($id);

        if (!$permission) {
            $this->notify('error', 'Akses tidak ditemukan.');
            return;
        }

        $this->deletePermissionId = $permission->id;
        $this->deletePermissionName = $permission->name;
        $this->modalDeletePermission = true;
        $this->deleteRoleConfirmed = false;
        $this->deletePermissionConfirmed = false;
    }

    public function deletePermission()
    {
        if (!$this->deletePermissionId) {
            return;
        }

        if (!$this->deletePermissionConfirmed) {
            $this->notify('error', 'Mohon centang persetujuan sebelum menghapus akses.');
            return;
        }

        $id = $this->deletePermissionId;

        try {
            DB::connection('db_custom')->transaction(function () use ($id) {
                DB::connection('db_custom')
                    ->table('role_has_permissions')
                    ->where('permission_id', $id)
                    ->delete();

                DB::connection('db_custom')
                    ->table('model_has_permissions')
                    ->where('permission_id', $id)
                    ->delete();

                DB::connection('db_custom')
                    ->table('permissions')
                    ->where('id', $id)
                    ->delete();
            });

            $this->modalDeletePermission = false;

            $this->loadPermissions();
            $this->loadRoles();

            $this->notify('success', 'Akses berhasil dihapus.');

        } catch (\Throwable $e) {
            report($e);
            $this->notify('error', 'Gagal menghapus akses.');
        }
    }

    public function notify($type, $message)
    {
        $this->dispatch('sirmed-toast', type: $type, message: $message);
    }

    public function render()
    {
        $roles = collect($this->roles);

        if (trim($this->search) !== '') {
            $keyword = mb_strtolower(trim($this->search));

            $roles = $roles->filter(function ($role) use ($keyword) {
                return str_contains(mb_strtolower($role->name), $keyword)
                    || $role->permissions->contains(function ($permission) use ($keyword) {
                        return str_contains(mb_strtolower($permission->name), $keyword);
                    });
            });
        }

        return view('pages.v3.setting.roles', [
            'filteredRoles' => $roles,
        ])->layout('layouts.v3.app');
    }
}
