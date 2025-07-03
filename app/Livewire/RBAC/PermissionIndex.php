<?php

namespace App\Livewire\RBAC;

use Livewire\Component;
use Spatie\Permission\Models\Permission;

class PermissionIndex extends Component
{
    public $name;
    public $editModalOpen = false;
    public $editingPermissionId;

    public function render()
    {
        return view('livewire.rbac.permission-index', [
            'permissions' => Permission::all(),
        ])->layout('layouts.app');
    }

    public function createPermission()
    {
        $this->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        Permission::create(['name' => $this->name]);
        session()->flash('message', 'Permission created successfully.');
        $this->name = '';
    }

    public function openEditModal($id)
    {
        $permission = Permission::findOrFail($id);
        $this->editingPermissionId = $permission->id;
        $this->name = $permission->name;
        $this->editModalOpen = true;
    }

    public function updatePermission()
    {
        $this->validate([
            'name' => 'required|string|unique:permissions,name,' . $this->editingPermissionId,
        ]);

        $permission = Permission::findOrFail($this->editingPermissionId);
        $permission->update(['name' => $this->name]);

        session()->flash('message', 'Permission updated successfully.');
        $this->reset(['name', 'editingPermissionId', 'editModalOpen']);
    }

    public function deletePermission($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        session()->flash('message', 'Permission deleted successfully.');
    }
}
