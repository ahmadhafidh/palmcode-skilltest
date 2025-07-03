<div x-data="{ editModalOpen: @entangle('editModalOpen') }" @keydown.escape.window="editModalOpen = false" class="py-8">
    <div class="max-w-6xl mx-auto bg-white shadow-sm rounded p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-700">Categories Management</h2>

        @if (session()->has('message'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        <div class="mb-4">
            <a href="{{ route('categories.create') }}" 
               class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Create Categories
            </a>
        </div>

        @if ($categories->isEmpty())
            <!-- No Data Hero -->
            <div class="text-center py-12 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10h.01M9 10h.01M9.75 15c.75.75 2.25.75 3 0" />
                </svg>
                <p class="mt-4 text-lg font-medium">No categories data.</p>
                <p class="text-sm">Klik "Create Categories" for creating data.</p>
            </div>
        @else
            <!-- Tabel Categories -->
            <table class="w-full border text-sm">
                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="border px-3 py-2">ID</th>
                        <th class="border px-3 py-2">Name</th>
                        <th class="border px-3 py-2">Slug</th>
                        <th class="border px-3 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-3 py-2">{{ $category->id }}</td>
                            <td class="border px-3 py-2">{{ $category->name }}</td>
                            <td class="border px-3 py-2 lowercase">{{ $category->slug }}</td>
                            <td class="border px-3 py-2 space-x-2">
                                <button 
                                    wire:click="openEditModal('{{ $category->id }}')" 
                                    type="button"
                                    class="inline-flex items-center rounded bg-blue-700 px-3 py-1.5 text-sm font-medium text-white hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                    title="Edit"
                                >
                                    <svg aria-hidden="true" class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20" 
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path>
                                        <path fill-rule="evenodd" d="M2 15.25V18h2.75l7.086-7.086-2.75-2.75L2 15.25z" clip-rule="evenodd"></path>
                                    </svg>
                                    Edit
                                </button>

                                <button wire:click="delete('{{ $category->id }}')" 
                                        onclick="return confirm('Delete this category?')" 
                                        type="button"
                                        class="inline-flex items-center px-3 py-1 text-sm font-medium text-white bg-red-600 rounded hover:bg-red-700 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900"
                                        title="Delete">
                                    <svg aria-hidden="true" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" 
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" 
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0a1 1 0 00-1 1v1h6V4a1 1 0 00-1-1m-4 0h4"></path>
                                    </svg>
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
            <h3 class="text-lg font-semibold mb-4">Edit Category</h3>

            <form wire:submit.prevent="update" class="space-y-4">
                <div>
                    <label for="name" class="block font-medium mb-1">Name</label>
                    <input type="text" id="name" wire:model="name" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                    @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="slug" class="block font-medium mb-1">Slug</label>
                    <input type="text" id="slug" wire:model="slug" readonly class="w-full border rounded px-3 py-2 bg-gray-100 cursor-not-allowed" />
                    @error('slug') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
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
