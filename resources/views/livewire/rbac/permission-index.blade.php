<div x-data="{ editModalOpen: @entangle('editModalOpen') }" @keydown.escape.window="editModalOpen = false" class="py-8">
    <div class="max-w-6xl mx-auto bg-white shadow-sm rounded p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-700">Manage Permissions</h2>
            <a href="{{ route('permissions.assign') }}" 
            class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Assign Permission to Role
            </a>
        </div>

        @if (session()->has('message'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        <div class="flex space-x-2 mb-4">
            <input wire:model="name" type="text" placeholder="New permission name" 
                class="border rounded px-3 py-2 flex-grow" />
            <button wire:click="createPermission" 
                    class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Create
            </button>
        </div>

        @error('name') <div class="text-red-600 text-sm mb-4">{{ $message }}</div> @enderror

        @if ($permissions->isEmpty())
            <div class="text-center py-12 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <p class="mt-4 text-lg font-medium">No permission Data.</p>
            </div>
        @else
            <table class="w-full border text-sm">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="border px-3 py-2">ID</th>
                        <th class="border px-3 py-2">Name</th>
                        <th class="border px-3 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissions as $permission)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-3 py-2">{{ $permission->id }}</td>
                            <td class="border px-3 py-2">{{ $permission->name }}</td>
                            <td class="border px-3 py-2 space-x-2">
                                <button wire:click="openEditModal('{{ $permission->id }}')"
                                    class="inline-flex items-center rounded bg-blue-600 px-3 py-1 text-sm font-medium text-white hover:bg-blue-700"
                                    title="Edit">
                                    Edit
                                </button>
                                <button wire:click="deletePermission('{{ $permission->id }}')"
                                    onclick="return confirm('Hapus permission ini?')"
                                    class="inline-flex items-center bg-red-600 px-3 py-1 text-sm font-medium text-white rounded hover:bg-red-700"
                                    title="Delete">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Modal Edit -->
    <div 
        x-show="editModalOpen" 
        x-cloak
        x-transition.opacity.duration.200ms
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        @click.self="editModalOpen = false"
        role="dialog"
        aria-modal="true"
    >
        <div 
            x-show="editModalOpen" 
            x-transition.scale.duration.200ms
            class="bg-white rounded shadow-lg max-w-md w-full p-6"
            @click.stop
        >
            <h3 class="text-lg font-semibold mb-4">Edit Permission</h3>

            <form wire:submit.prevent="updatePermission" class="space-y-4">
                <div>
                    <label for="name" class="block font-medium mb-1">Name</label>
                    <input type="text" id="name" wire:model="name" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" @click="editModalOpen = false" class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-100">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
