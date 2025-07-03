<?php

namespace App\Livewire\RBAC;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class Users extends Component
{
    public $users;
    public $name, $email, $password, $password_confirmation;
    public $selectedRole = '';
    public $roles;
    public $editMode = false;
    public $userIdBeingEdited = null;

    public function mount()
    {
        $this->loadUsers();
        $this->roles = Role::pluck('name')->toArray(); // Ambil nama role
    }

    public function loadUsers()
    {
        $this->users = User::with('roles')->latest()->get();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->selectedRole = '';
        $this->editMode = false;
        $this->userIdBeingEdited = null;
    }

    public function store()
    {
        $validated = $this->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|same:password_confirmation',
            'password_confirmation' => 'required',
            'selectedRole' => 'required|string|exists:roles,name',
        ]);

        $validated['password'] = Hash::make($this->password);
        unset($validated['password_confirmation']);

        $user = User::create($validated);
        $user->assignRole($this->selectedRole);

        session()->flash('message', 'User successfully created.');
        $this->resetForm();
        $this->loadUsers();
    }

    public function render()
    {
        return view('livewire.rbac.users')->layout('layouts.app');
    }
}
