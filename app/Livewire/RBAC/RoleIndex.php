<?php

namespace App\Livewire\RBAC;

use Livewire\Component;
use Spatie\Permission\Models\Role;

class RoleIndex extends Component
{
    public $name;
    public $guard_name = 'web'; // default guard
    public $editModalOpen = false;
    public $editingRoleId;

    // Guard options, sesuaikan dengan guard di config/auth.php
    public $guards = ['web', 'api'];

    public function render()
    {
        return view('livewire.rbac.role-index', [
            'roles' => Role::all(),
            'guards' => $this->guards,
        ])->layout('layouts.app');
    }

    public function createRole()
    {
        $this->validate([
            'name' => 'required|string|unique:roles,name',
            'guard_name' => 'required|string|in:' . implode(',', $this->guards),
        ]);

        Role::create([
            'name' => $this->name,
            'guard_name' => $this->guard_name,
        ]);
        session()->flash('message', 'Role created successfully.');
        $this->reset(['name', 'guard_name']);
        $this->guard_name = 'web'; // reset ke default
    }

    public function openEditModal($id)
    {
        $role = Role::findOrFail($id);
        $this->editingRoleId = $role->id;
        $this->name = $role->name;
        $this->guard_name = $role->guard_name;
        $this->editModalOpen = true;
    }

    public function updateRole()
    {
        $this->validate([
            'name' => 'required|string|unique:roles,name,' . $this->editingRoleId,
            'guard_name' => 'required|string|in:' . implode(',', $this->guards),
        ]);

        $role = Role::findOrFail($this->editingRoleId);
        $role->update([
            'name' => $this->name,
            'guard_name' => $this->guard_name,
        ]);

        session()->flash('message', 'Role updated successfully.');
        $this->reset(['name', 'guard_name', 'editingRoleId', 'editModalOpen']);
        $this->guard_name = 'web'; // reset ke default
    }

    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        session()->flash('message', 'Role deleted successfully.');
    }
}
