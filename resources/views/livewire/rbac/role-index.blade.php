<div class="max-w-6xl mx-auto bg-white shadow-sm rounded p-6">

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Manage Roles</h2>
        <a href="{{ route('roles.assign') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            Assign Role to User
        </a>
    </div>

    <div class="flex space-x-2 mb-4">
        <input wire:model.defer="name" type="text" placeholder="New role name" class="border rounded px-3 py-2 flex-grow" />

        <select wire:model.defer="guard_name" class="border rounded px-3 py-2">
            @foreach ($guards as $guard)
                <option value="{{ $guard }}">{{ ucfirst($guard) }}</option>
            @endforeach
        </select>

        <button wire:click="createRole" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Create</button>
    </div>

    @if(session()->has('message'))
        <div class="mb-4 p-2 bg-green-100 text-green-700 rounded">{{ session('message') }}</div>
    @endif

    <table class="w-full text-left text-sm border">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-3 py-2">ID</th>
                <th class="border px-3 py-2">Name</th>
                <th class="border px-3 py-2">Guard</th>
                <th class="border px-3 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($roles as $role)
                <tr class="hover:bg-gray-50">
                    <td class="border px-3 py-2">{{ $role->id }}</td>
                    <td class="border px-3 py-2">{{ $role->name }}</td>
                    <td class="border px-3 py-2">{{ $role->guard_name }}</td>
                    <td class="border px-3 py-2 space-x-2">
                        <button wire:click="openEditModal({{ $role->id }})" class="bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700">Edit</button>
                        <button wire:click="deleteRole({{ $role->id }})" onclick="return confirm('Delete this role?')" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Edit Modal -->
    <div
        x-data="{ open: @entangle('editModalOpen') }"
        x-show="open"
        x-cloak
        x-transition.opacity
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        @click.self="open = false"
    >
        <div
            x-show="open"
            x-transition.scale
            class="bg-white rounded p-6 max-w-md w-full"
            @click.stop
        >
            <h3 class="text-lg font-semibold mb-4">Edit Role</h3>

            <form wire:submit.prevent="updateRole" class="space-y-4">
                <div>
                    <label for="name" class="block font-medium mb-1">Name</label>
                    <input type="text" id="name" wire:model.defer="name" class="w-full border rounded px-3 py-2" />
                    @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" @click="open = false" class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-100">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
