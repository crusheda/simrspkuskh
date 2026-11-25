<?php

namespace App\Livewire\Setting;

use Livewire\Component;
use App\Models\Role;
use App\Models\Pengguna;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Permissions extends Component
{
    public $users = [];
    public $roles = [];

    public $search = '';

    public $userId = null;
    public $selectedRole = '';

    public $deleteUserId = null;
    public $deleteUserName = '';
    public $deleteUserRole = '';

    public $modalEdit = false;
    public $modalDelete = false;

    public $deleteConfirmed = false;

    public function mount()
    {
        $this->loadUsers();
        $this->loadRoles();
    }

    public function loadRoles()
    {
        $this->roles = Role::on('db_custom')
            ->orderBy('name')
            ->get();
    }

    public function loadUsers()
    {
        $this->users = DB::table('aplikasi.pengguna AS ap')
            ->leftJoin('master.pegawai AS pg', 'pg.NIP', '=', 'ap.NIP')
            ->leftJoin('simrspku_klaim.model_has_roles AS mhr', function ($join) {
                $join->on('mhr.model_id', '=', 'ap.ID')
                    ->where('mhr.model_type', '=', 'App\Models\Pengguna');
            })
            ->leftJoin('simrspku_klaim.roles AS r', 'r.id', '=', 'mhr.role_id')
            ->select(
                'pg.*',
                'ap.NAMA as APNAMA',
                'ap.NIP as APNIP',
                'ap.ID as ID_PENGGUNA',
                'ap.LOGIN as USERNAME',
                'ap.PASSWORD',
                'ap.TERAKHIR_UBAH_PASSWOD',
                DB::raw('master.getNamaLengkapPegawai(pg.NIP) AS NAMALENGKAP'),
                DB::raw("GROUP_CONCAT(r.name SEPARATOR ', ') as ROLE_NAMES")
            )
            ->where('ap.ID', '!=', '0')
            ->groupBy('ap.ID')
            ->orderBy('NAMALENGKAP')
            ->get();
    }

    public function refreshData()
    {
        $this->loadUsers();
        $this->loadRoles();

        $this->dispatch('permissions-user-refreshed');
    }

    public function openEdit($id)
    {
        $user = Pengguna::find($id);

        if (!$user) {
            $this->notify('error', 'Pengguna tidak ditemukan.');
            return;
        }

        $this->userId = $user->ID;
        $this->selectedRole = (string) ($user->roles->first()->id ?? '');

        $this->modalEdit = true;
    }

    public function saveRoleUser()
    {
        Validator::make([
            'userId' => $this->userId,
            'selectedRole' => $this->selectedRole,
        ], [
            'userId' => 'required',
            'selectedRole' => 'required',
        ], [
            'selectedRole.required' => 'Silakan pilih jabatan.',
        ])->validate();

        $user = Pengguna::find($this->userId);

        if (!$user) {
            $this->notify('error', 'Pengguna tidak ditemukan.');
            return;
        }

        $role = Role::on('db_custom')->find($this->selectedRole);

        if (!$role) {
            $this->notify('error', 'Role tidak ditemukan.');
            return;
        }

        try {
            $user->syncRoles([$role->name]);

            $this->modalEdit = false;
            $this->refreshData();

            $this->notify('success', 'Jabatan pengguna berhasil diperbarui.');

        } catch (\Throwable $e) {
            report($e);
            $this->notify('error', 'Gagal memperbarui jabatan pengguna.');
        }
    }

    public function confirmDelete($id)
    {
        $user = Pengguna::find($id);

        if (!$user) {
            $this->notify('error', 'Pengguna tidak ditemukan.');
            return;
        }

        $this->deleteUserId = $user->ID;
        $this->deleteUserName = $user->NAMA ?? $user->LOGIN ?? $user->ID;
        $this->deleteUserRole = $user->roles->pluck('name')->implode(', ');

        $this->modalDelete = true;
        $this->deleteConfirmed = false;
    }

    public function deleteRoleUser()
    {
        if (!$this->deleteUserId) {
            return;
        }

        if (!$this->deleteConfirmed) {
            $this->notify('error', 'Mohon centang persetujuan sebelum menghapus jabatan pengguna.');
            return;
        }

        $user = Pengguna::find($this->deleteUserId);

        if (!$user) {
            $this->notify('error', 'Pengguna tidak ditemukan.');
            return;
        }

        try {
            $user->syncRoles([]);

            $this->modalDelete = false;
            $this->refreshData();

            $this->notify('success', 'Jabatan pengguna berhasil dihapus.');

        } catch (\Throwable $e) {
            report($e);
            $this->notify('error', 'Gagal menghapus jabatan pengguna.');
        }
    }

    public function notify($type, $message)
    {
        $this->dispatch('sirmed-toast', type: $type, message: $message);
    }

    public function render()
    {
        $users = collect($this->users);

        if (trim($this->search) !== '') {
            $keyword = mb_strtolower(trim($this->search));

            $users = $users->filter(function ($user) use ($keyword) {
                return str_contains(mb_strtolower((string) $user->NAMALENGKAP), $keyword)
                    || str_contains(mb_strtolower((string) $user->APNAMA), $keyword)
                    || str_contains(mb_strtolower((string) $user->APNIP), $keyword)
                    || str_contains(mb_strtolower((string) $user->USERNAME), $keyword)
                    || str_contains(mb_strtolower((string) $user->ROLE_NAMES), $keyword);
            });
        }

        return view('pages.v3.setting.permissions', [
            'filteredUsers' => $users,
        ])->layout('layouts.v3.app');
    }
}
