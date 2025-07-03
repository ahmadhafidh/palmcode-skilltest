<?php

namespace App\Livewire\RBAC;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignRoleToUser extends Component
{
    public $selectedUser = '';
    public $selectedRoles = [];

    public $editMode = false;
    public $originalUserId = null;

    protected $rules = [
        'selectedUser' => 'required|exists:users,id',
        'selectedRoles' => 'required|array|min:1',
        'selectedRoles.*' => 'exists:roles,name',
    ];

    public function mount()
    {
        // Optional: bisa isi dari query param user
        $requestedUserId = request()->query('user');
        if ($requestedUserId && User::where('id', $requestedUserId)->exists()) {
            $this->selectedUser = $requestedUserId;
            $this->loadRoles();
        }
    }

    public function loadRoles()
    {
        $user = User::find($this->selectedUser);
        if ($user) {
            $this->selectedRoles = $user->roles->pluck('name')->toArray();
        }
    }

    public function assignRoles()
    {
        $this->validate();

        $user = User::find($this->selectedUser);
        if (!$user) {
            session()->flash('message', 'User tidak ditemukan.');
            return;
        }

        $user->syncRoles($this->selectedRoles);

        session()->flash('message', $this->editMode ? 'Update role berhasil.' : 'Assign role berhasil.');

        $this->resetForm();
    }

    public function resetForm()
    {
        $this->selectedUser = '';
        $this->selectedRoles = [];
        $this->editMode = false;
        $this->originalUserId = null;
    }

    public function edit($userId)
    {
        $this->editMode = true;
        $this->selectedUser = $userId;
        $this->originalUserId = $userId;
        $this->loadRoles();
        // Tidak pakai dispatchBrowserEvent, user bisa toggle form manual
    }

    public function deleteRoleFromUser($userId, $roleName)
    {
        $user = User::find($userId);
        if ($user) {
            $user->removeRole($roleName);
            session()->flash('message', "Role '$roleName' dihapus dari user.");

            // Update selectedRoles kalau sedang edit user yg sama
            if ($this->editMode && $this->selectedUser == $userId) {
                $this->loadRoles();
            }
        }
    }

    public function deleteAllRolesFromUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->syncRoles([]); // hapus semua role
            session()->flash('message', "Semua role dihapus dari user.");
            if ($this->editMode && $this->selectedUser == $userId) {
                $this->loadRoles();
            }
        }
    }

    public function render()
    {
        return view('livewire.rbac.assign-role-to-user', [
            'users' => User::all(),
            'roles' => Role::all(),
            'usersWithRoles' => User::with('roles')->get(),
        ])->layout('layouts.app');
    }
}
