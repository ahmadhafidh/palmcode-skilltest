<?php

namespace App\Livewire\RBAC;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignPermissionToRole extends Component
{
    public $selectedRole = '';
    public $selectedPermissions = [];

    public $editMode = false;
    public $originalRole = null;

    public $showForm = false;  // tambahkan ini

    protected $rules = [
        'selectedRole' => 'required|exists:roles,name',
        'selectedPermissions' => 'required|array|min:1',
        'selectedPermissions.*' => 'exists:permissions,name',
    ];

    public function mount()
    {
        $fromQuery = request()->query('role');
        if ($fromQuery && Role::where('name', $fromQuery)->exists()) {
            $this->selectedRole = $fromQuery;
            $this->loadPermissions();
            $this->showForm = true; // buka form kalau dari query
        }
    }

    public function loadPermissions()
    {
        $role = Role::where('name', $this->selectedRole)->first();
        if ($role) {
            $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        }
    }

    public function assign()
    {
        $this->validate();

        $role = Role::where('name', $this->selectedRole)->first();
        if (!$role) {
            session()->flash('message', 'Role tidak ditemukan.');
            return;
        }

        $role->syncPermissions($this->selectedPermissions);

        session()->flash('message', $this->editMode ? 'Update berhasil.' : 'Assign berhasil.');

        $this->resetForm();
    }

    public function resetForm()
    {
        $this->selectedRole = '';
        $this->selectedPermissions = [];
        $this->editMode = false;
        $this->originalRole = null;
        $this->showForm = false; 
    }

    public function edit($roleName)
    {
        $this->editMode = true;
        $this->selectedRole = $roleName;
        $this->originalRole = $roleName;
        $this->loadPermissions();

        $this->showForm = true; 
    }

    public function deleteAllPermissions($roleName)
    {
        $role = Role::where('name', $roleName)->first();
        if ($role) {
            $role->syncPermissions([]);
            session()->flash('message', "Semua permission pada role '$roleName' dihapus.");
        }
    }

    public function deletePermission($roleName, $permissionName)
    {
        $role = Role::where('name', $roleName)->first();
        if ($role) {
            $role->revokePermissionTo($permissionName);
            session()->flash('message', "Permission '$permissionName' dihapus dari role '$roleName'.");

            if ($this->editMode && $this->selectedRole === $roleName) {
                $this->loadPermissions();
            }
        }
    }

    public function render()
    {
        return view('livewire.rbac.assign-permission-to-role', [
            'roles' => Role::all(),
            'permissions' => Permission::all(),
            'rolesWithPermissions' => Role::with('permissions')->get(),
        ])->layout('layouts.app');
    }
}
